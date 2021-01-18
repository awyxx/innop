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

extern _inno inno;