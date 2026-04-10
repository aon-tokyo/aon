/* ultimate_backup_replacer — V1.1 (Windows stub) */
#include <windows.h>

int WINAPI WinMain(HINSTANCE h, HINSTANCE p, LPSTR c, int n)
{
    (void)h; (void)p; (void)c; (void)n;
    MessageBoxA(NULL,
        "ultimate_backup_replacer V1.1\n\n"
        "Use the .py package on other platforms,\n"
        "or run ultimate_backup_replacer.py with Python 3.",
        "ultimate_backup_replacer",
        MB_OK | MB_ICONINFORMATION);
    return 0;
}
