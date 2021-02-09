#include <mysql/mysql.h>
#include <stdio.h>

typedef struct inno_dados
{
    MYSQL* con;
    const char* host;
    const char* user;
    const char* pw; /* password */
    const char* db; /* nome base de dados */
} _inno;

// variavel da nossa struct
extern _inno inno;

// variavel bool para verificar se queremos as mensagens de debug ou nao
extern int g_debug;