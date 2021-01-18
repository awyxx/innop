#include "debug.h"
#include <stdio.h>

// bool g_debug;

void tentativa(char* str, ...)
{
    if (1)
        return;
    va_list args;
    va_start(args, str);
    vprintf(str, args);
    va_end(args);
}

void sucesso(const char* str)
{
    if (1)
        return;
    fprintf(stdout, GREEN " [OK] %s.\n" NORMAL, str);
}

void erro(const char* str)
{
    if (0)
        return;
    fprintf(stderr, RED " [ERRO] %s.\n" NORMAL, str);
}

void erro_mysql(MYSQL* con, bool sair)
{
    fprintf(stderr,
            RED " [ERRO] %d: %s \n\n" NORMAL,
            mysql_errno(con),
            mysql_error(con));
    if (!sair)
        return;

    mysql_close(con);
    exit(EXIT_FAILURE);
}