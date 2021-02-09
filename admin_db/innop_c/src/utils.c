#include <stdarg.h>
#include <stdio.h>
#include <stdlib.h>

#include "utils.h"

char* ler_query()
{
    char* line = NULL;
    size_t len = 0;
    ssize_t read;
    int c = 0; /* por alguma razao, o stdin recebe sempre primeiro \n,
               logo
               usamos um contador que so retorna a variavel a segunda */

    while ((read = getline(&line, &len, stdin)) != -1)
    {
        if (read > 0 && c == 1)
            break;
        c++;
    }

    return line;
}

void format_str(char* newstr, const char* text, ...)
{
    va_list argptr;
    va_start(argptr, text);
    vsprintf(newstr, text, argptr);
    va_end(argptr);
}

void pressione_tecla_para_continuar()
{
    printf("\nPressione ENTER para continuar...");
    getchar();
    getchar();
    system("clear");
}