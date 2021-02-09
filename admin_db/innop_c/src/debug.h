#include <mysql/mysql.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>

/* ANSI codes usados para printar com cores */
#define NORMAL "\x1b[0m"
#define RED    "\x1b[91m"
#define GREEN  "\x1b[92m"

// extern bool g_debug;

/* funções para dar debug ao programa */
void tentativa(char* str, ...);
void sucesso(const char* str);
void erro(const char* str);
void erro_mysql(MYSQL* con, bool sair);