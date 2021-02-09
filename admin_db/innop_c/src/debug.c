#include "debug.h"
#include "dados.h"
#include <stdio.h>

// bool g_debug;

void tentativa(char* str, ...)
{
    if (!g_debug)
        return;
    va_list args;
    va_start(args, str);
    vprintf(str, args);
    va_end(args);
}

void sucesso(const char* str)
{
    if (!g_debug)
        return;
    fprintf(stdout, GREEN " \n[OK] %s.\n" NORMAL, str);
}

void erro(const char* str)
{
    fprintf(stderr, RED " \n[ERRO] %s.\n" NORMAL, str);
}

void erro_mysql(MYSQL* con, bool sair)
{
    fprintf(stderr,
            RED " \n[ERRO] %d: %s \n\n" NORMAL,
            mysql_errno(con),
            mysql_error(con));
    if (!sair)
        return;

    mysql_close(con);
    exit(EXIT_FAILURE);
}