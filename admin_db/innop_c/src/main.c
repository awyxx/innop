#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

#include "dados.h"
#include "debug.h"
#include "inno_mysql.h"
#include "menus.h"
#include "utils.h"

_inno inno;
int g_debug = 0;

// definir as váriaveis da nossa BD e conectar ao MySQL
void init()
{
    // Mais tarde fazer para q consigamos por ficheiro ou mudar nas
    // settings!
    inno.con  = init_mysql();
    inno.host = "localhost";
    inno.user = "root";
    inno.pw   = "";
    inno.db   = "innoplus";
    conectar_mysql(inno.con, inno.host, inno.user, inno.pw, inno.db);
}

int verificar_login(const char* np, const char* cc)
{
    tentativa("verificar_login(%s, %s)\n", np, cc);

    char query[256];
    format_str(query,
               "SELECT * FROM professor INNER JOIN cartao ON codprof = "
               "codcartao WHERE cc = %s AND codprof = %s",
               cc,
               np);
    int existe = 0;
    if (executar_query_mysql(inno.con, query))
    {
        MYSQL_RES* result = mysql_store_result(inno.con);
        if (result == NULL)
            return -1;

        MYSQL_ROW* campo;
        while (campo = (MYSQL_ROW*)mysql_fetch_row(result))
        {
            if (!strcmp((char*)campo[0], "Imagem")
                || !strcmp((char*)campo[0], "img"))
                continue;
            existe++;
        }
    }
    else
    {
        erro("Erro de syntax __verificar_login()");
        return 0;
    }

    // bue esperto
    return existe;
}

int main(int argc, char** argv)
{
    // ver se passamos debug como argumento
    if (argc > 1
        && (!strcmp("-debug", argv[1]) || !strcmp("-d", argv[1])))
    {
        printf(GREEN "DEBUG MODE ATIVADO\n\n" NORMAL);
        g_debug = 1;
    }

    init();

    // verificar login de admin!
    char np[32], cc[32];
    printf("\nN Processo: ");
    scanf("%s", np);
    printf("CC: ");
    scanf("%s", cc);

    if (!verificar_login(np, cc))
    {
        erro("Dados incorretos!");
        pressione_tecla_para_continuar();
        mysql_close(inno.con);
        exit(EXIT_SUCCESS);
    }

    sleep(2);
    system("clear");

    sucesso("Login feito com sucesso");
    printf(GREEN "\nBem-vindo Admin! \n" NORMAL);
    printf("Programa em C para a gestao da base de dados!\nCaso queira "
           "as mensagens de debug, execute o programa com o argmuento "
           "-d.\n");
    printf("Recomendavel: Usar o site para a gestão da base de dados "
           "(interface gráfica, mais simples de usar)\n");

    pressione_tecla_para_continuar();

    menu();
    mysql_close(inno.con);

    return 0;
}