#Requires -Version 5.1
<#
.SYNOPSIS
  Push the current branch to GitHub, then mirror the repo to a local folder (default E:\).

.DESCRIPTION
  1) git push -u origin <current-branch> (retry once on failure)
  2) robocopy mirror of the working tree to LOCAL_MIRROR_PATH (excludes .git, node_modules, build caches)

  Set environment variable LOCAL_MIRROR_PATH to override the destination (e.g. E:\repos\aon).

  Usage (from repo root):
    .\scripts\sync-github-and-local-mirror.ps1
    $env:LOCAL_MIRROR_PATH = "E:\my-mirror\aon"; .\scripts\sync-github-and-local-mirror.ps1
    .\scripts\sync-github-and-local-mirror.ps1 -SkipPush
    .\scripts\sync-github-and-local-mirror.ps1 -SkipRobocopy
#>
param(
  [switch] $SkipPush,
  [switch] $SkipRobocopy,
  [string] $MirrorPath = $env:LOCAL_MIRROR_PATH
)

$ErrorActionPreference = "Stop"

$RepoRoot = Resolve-Path (Join-Path $PSScriptRoot "..")
if (-not $MirrorPath -or $MirrorPath.Trim() -eq "") {
  $MirrorPath = "E:\aon-mirror"
}

function Invoke-GitPush {
  $branch = (git -C $RepoRoot rev-parse --abbrev-ref HEAD 2>$null)
  if ($branch) { $branch = $branch.Trim() }
  if (-not $branch -or $branch -eq "HEAD") {
    throw "Could not detect current git branch. Run this from a clone with a checked-out branch."
  }
  Write-Host "==> git push -u origin $branch" -ForegroundColor Cyan
  git -C $RepoRoot push -u origin $branch
  if ($LASTEXITCODE -ne 0) {
    Write-Host "Push failed; retrying once after 4s..." -ForegroundColor Yellow
    Start-Sleep -Seconds 4
    git -C $RepoRoot push -u origin $branch
    if ($LASTEXITCODE -ne 0) { throw "git push failed with exit code $LASTEXITCODE" }
  }
}

function Invoke-RobocopyMirror {
  $dest = $MirrorPath.Trim()
  $parent = Split-Path -Parent $dest
  if ($parent -and -not (Test-Path -LiteralPath $parent)) {
    throw "Parent folder does not exist: $parent — create it or set LOCAL_MIRROR_PATH (e.g. E:\)."
  }
  if (-not (Test-Path -LiteralPath $dest)) {
    New-Item -ItemType Directory -Path $dest -Force | Out-Null
  }

  $src = $RepoRoot.Path
  if (-not $src) { $src = $RepoRoot.ToString() }

  # /MIR = mirror (deletes extra files in dest); /FFT /DST tolerate FAT time skew
  $excludeDirs = @(
    ".git",
    "node_modules",
    ".cursor",
    "__pycache__",
    ".venv",
    "dist",
    "build"
  )
  $xdArgs = @()
  foreach ($d in $excludeDirs) { $xdArgs += @("/XD", $d) }

  Write-Host "==> robocopy mirror -> $dest" -ForegroundColor Cyan
  $args = @(
    $src.TrimEnd("\"),
    $dest,
    "/MIR",
    "/Z",
    "/FFT",
    "/R:2",
    "/W:4",
    "/NFL",
    "/NDL",
    "/NP",
    "/NJH",
    "/NJS"
  ) + $xdArgs

  & robocopy @args
  $rc = $LASTEXITCODE
  # robocopy: 0-7 = success with different meanings; 8+ = error
  if ($rc -ge 8) {
    throw "robocopy failed with exit code $rc"
  }
  Write-Host "robocopy finished (code $rc)." -ForegroundColor Green
}

Push-Location $RepoRoot
try {
  if (-not $SkipPush) {
    Invoke-GitPush
  } else {
    Write-Host "Skipping git push (-SkipPush)." -ForegroundColor Yellow
  }

  if (-not $SkipRobocopy) {
    Invoke-RobocopyMirror
  } else {
    Write-Host "Skipping robocopy (-SkipRobocopy)." -ForegroundColor Yellow
  }

  Write-Host "Done: GitHub push" $(if (-not $SkipPush) { "OK" } else { "skipped" }) ", local mirror -> $MirrorPath" $(if (-not $SkipRobocopy) { "OK" } else { "skipped" }) -ForegroundColor Green
} finally {
  Pop-Location
}
