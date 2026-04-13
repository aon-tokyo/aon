$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$RepoRoot = Resolve-Path (Join-Path $Root "..\..")

Write-Host "deploy-hub installer"
Write-Host "  Repo: $RepoRoot"

if (-not (Get-Command node -ErrorAction SilentlyContinue)) {
  Write-Host "Install Node.js 18+ from https://nodejs.org/"
  exit 1
}

Push-Location (Join-Path $RepoRoot "tools\deploy-sync")
npm install
Pop-Location

Push-Location $Root
npm install
Pop-Location

$LocalBin = Join-Path $env:USERPROFILE ".local\bin"
New-Item -ItemType Directory -Force -Path $LocalBin | Out-Null

$HubBat = Join-Path $LocalBin "deploy-hub.cmd"
"@echo off`r`nnode `"$Root\cli.js`" %*" | Set-Content -Path $HubBat -Encoding ASCII
Write-Host "Created: $HubBat"
Write-Host "Add to PATH: $LocalBin"

$DsBat = Join-Path $LocalBin "deploy-sync.cmd"
"@echo off`r`nnode `"$RepoRoot\tools\deploy-sync\cli.js`" %*" | Set-Content -Path $DsBat -Encoding ASCII
Write-Host "Created: $DsBat"

Write-Host "Done. Run: deploy-hub check"
