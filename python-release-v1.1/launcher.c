/* Minimal Windows launcher — Ver 1.1 (paired with Ver1.1.py) */
#include <windows.h>

int WINAPI WinMain(HINSTANCE hInstance, HINSTANCE hPrev, LPSTR lpCmdLine, int nShowCmd)
{
    (void)hInstance;
    (void)hPrev;
    (void)lpCmdLine;
    (void)nShowCmd;
    MessageBoxA(
        NULL,
        "Python release Ver 1.1\n\n"
        "Run Ver1.1.py with Python 3:\n"
        "  python Ver1.1.py",
        "Ver 1.1",
        MB_OK | MB_ICONINFORMATION);
    return 0;
}
