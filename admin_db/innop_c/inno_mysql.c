#include <mysql/mysql.h>
#include <stdarg.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "debug.h"
#include "inno_mysql.h"
#include "utils.h"

MYSQL* init_mysql()
{
    MYSQL* con = mysql_init(NULL);
    if (con == NULL)
        erro_mysql(con, true);
    return con;
}

void conectar_mysql(MYSQL* con,
                    const char* host,
                    const char* user,
                    const char* pw,
                    const char* db)
{
    tentativa("conectar_mysql()",
              "Conectando à base de dados: '%s-%s:%s:%d' ...\n",
              db,
              host,
              user,
              3306);

    if (mysql_real_connect(con, host, user, pw, db, 3306, NULL, 0)
        == NULL)
        erro_mysql(con, true);

    sucesso("Conexão com MySQL realizada com sucesso");
    sucesso("Autentificação feita com sucesso");
}

bool executar_query_mysql(MYSQL* con, const char* query)
{
    tentativa("executar_query_mysql()\nQuery: %s", query);

    if ((mysql_query(con, query)))
    {
        erro_mysql(con, false);
        return false;
    }

    sucesso("Query executada");
    return true;
}

int numero_campos_mysql(MYSQL* con, const char* tabela)
{
    tentativa("numero_campos_mysql(%p, %s)", con, tabela);

    char query[32];
    format_str(query, "SHOW COLUMNS FROM %s", tabela);
    if (!executar_query_mysql(con, query))
        return -1;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return -1;

    int ncampos = 0;
    MYSQL_ROW* campo;
    while (campo = (MYSQL_ROW*)mysql_fetch_row(result)) {
        if (!strcmp((char*)campo[0], "Imagem") || !strcmp((char*)campo[0], "img"))
            continue;
        ncampos++;
    }

    return ncampos;
}

void pedir_campos_mysql(MYSQL* con, const char* tabela, char registo[16][128])
{
    tentativa("pedir_campos_mysql(%p, %s)", con, tabela);

    char query[32];
    format_str(query, "SHOW COLUMNS FROM %s", tabela);
    if (!executar_query_mysql(con, query))
        return;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return;

    int x = 0;
    MYSQL_ROW* campo;
    while (campo = (MYSQL_ROW*)mysql_fetch_row(result))
    {
        if (!strcmp((char*)campo[0], "Imagem") || !strcmp((char*)campo[0], "img"))
            continue;
        printf("%s: ", campo[0]);
        scanf("%s", registo[x]);
        x++;
    }

}
void mostrar_campos_mysql(MYSQL* con, const char* tabela)
{
    tentativa("mostrar_campos_mysql(%p, %s)", con, tabela);

    char query[64];
    format_str(query, "SELECT * FROM %s", tabela);
    if (!executar_query_mysql(con, query))
        return;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return;

    int num_fields = mysql_num_fields(result);
    MYSQL_FIELD* field;

    for (int i = 0; i < num_fields; i++)
    {
        if (i == 0)
        {
            while (field = mysql_fetch_field(result))
                printf("%s,  ", field->name);
            // printf("field %s", get_type(field->type));
        }
    }

    mysql_free_result(result);
}

void mostrar_tabelas_mysql(MYSQL* con, const char* db)
{
    tentativa("mostrar_tabelas_mysql(%p, %s)", con, db);

    char query[32];
    format_str(query, "SHOW TABLES FROM %s", db);
    if (!executar_query_mysql(con, query))
        return;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return;

    printf("\nTabelas:\tCampos: \n");
    MYSQL_ROW* tabela;
    while (tabela = (MYSQL_ROW*)mysql_fetch_row(result))
    {
        printf(" -> %s \t( ", tabela[0]);
        mostrar_campos_mysql(con, (const char*)tabela[0]);
        printf(" )\n");
    }

    mysql_free_result(result);
}

void listar_uma_mysql(MYSQL* con, const char* nometabela)
{
    tentativa("listar_uma_mysql(%p, %s)", con, nometabela);

    char query[128];
    format_str(query, "SELECT * FROM %s", nometabela);
    if (!executar_query_mysql(con, query))
        return;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return;

    MYSQL_ROW row;
    MYSQL_FIELD* field;

    int num_fields = mysql_num_fields(result);
    int linha      = sizeof(nometabela) + sizeof("Tabela") + 1;

    printf("\n");
    for (int i = 0; i < linha; i++)
        printf("-");
    printf("\nTabela: %s\n", nometabela);
    for (int i = 0; i < linha; i++)
        printf("-");
    printf("\n");

    while ((row = mysql_fetch_row(result)))
    {
        for (int i = 0; i < num_fields; i++)
        {
            if (i == 0)
            {
                while (field = mysql_fetch_field(result))
                    printf("%s ", field->name);

                printf("\n");
                for (int i = 0; i < 84; i++)
                    printf("-");
                printf("\n");
            }
            printf("%s ", row[i] ? row[i] : "NULL");
        }
    }

    printf("\n");
    mysql_free_result(result);
}

void remover_campo_mysql(MYSQL* con,
                         const char* tabela,
                         const char* campo,
                         const char* valcampo)
{
    tentativa("remover_campo_mysql(%p, %s, %s, %s)",
              con,
              tabela,
              campo,
              valcampo);

    char query[128];
    format_str(query, "DELETE FROM %s WHERE %s", campo, valcampo);
    if (!executar_query_mysql(con, query))
        return;

    printf("\nRegisto '%s' removido com sucesso!", campo);
}

int numero_tabelas_mysql(MYSQL* con, const char* db)
{
    tentativa("numero_tabelas(%p, %s)", con, db);

    char query[64];
    format_str(query, "SHOW TABLES FROM %s", db);
    if (!executar_query_mysql(con, query))
        return -1;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return -1;

    int num_tabelas = 0;
    MYSQL_ROW* tabela;
    while (tabela = (MYSQL_ROW*)mysql_fetch_row(result))
        num_tabelas++;

    mysql_free_result(result);
    return num_tabelas;
}

void matrix_nome_tabelas(char dest[32][32], MYSQL* con, const char* db)
{
    tentativa("matrix_nome_tabelas(dest, %p, %s)", con, db);

    char query[32];
    format_str(query, "SHOW TABLES FROM %s", db);
    if (!executar_query_mysql(con, query))
        return;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return;

    int x = 0;
    MYSQL_ROW* tabela;
    while (tabela = (MYSQL_ROW*)mysql_fetch_row(result))
    {
        strcpy(dest[x], (const char*)tabela[0]);
        x++;
    }

    mysql_free_result(result);
}

bool tabela_ta_vazia(MYSQL* con, char* nometabela)
{
    tentativa("tabela_ta_vazia(%p, %s)", con, nometabela);

    char query[64];
    format_str(query, "SELECT * FROM %s", nometabela);
    if (!executar_query_mysql(con, query))
        return true;

    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL)
        return -1;

    int num_campos = 0;
    MYSQL_ROW* campo;
    while (campo = (MYSQL_ROW*)mysql_fetch_row(result))
        num_campos++;

    mysql_free_result(result);
    if (num_campos == 0)
        return true;

    return false;
}

bool tabela_existe(MYSQL* con, const char* db, const char* nometabela)
{
    tentativa("tabela_existe(%p, %s, %s)", con, db, nometabela);

    char tabelas[32][32];
    matrix_nome_tabelas(tabelas, con, db);
    int ntabelas = numero_tabelas_mysql(con, db);
    int valid    = 0;
    for (int i = 0; i < ntabelas; i++)
    {
        if (!strcmp(tabelas[i], nometabela)) {
            sucesso("tabela_existe() -> Tabela existe!");
            valid++;
        }
    }

    if (!valid)
        return false;
    else
        return true;
}

int tabela_num(MYSQL* con, const char* db, const char* nometabela)
{
    tentativa("tabela_num(%p, %s, %s)", con, db, nometabela);

    char tabelas[32][32];
    matrix_nome_tabelas(tabelas, con, db);
    int ntabelas = numero_tabelas_mysql(con, db);
    int valid = 0, num_tabela = -1;
    for (int i = 0; i < ntabelas; i++)
    {
        if (!strcmp(tabelas[i], nometabela)) {
            sucesso("tabela_num() -> Index da tabela valido!");
            return i;
        }
    }

    if (num_tabela = -1)
    {
        erro("Erro! tabela_num()");
        return -1;
    }
}