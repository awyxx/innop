#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

#include "dados.h"
#include "debug.h"
#include "inno_mysql.h"
#include "menus.h"
#include "utils.h"

// variavel da nossa struct
_inno inno;

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

bool verificar_login(const char* np, const char* cc)
{
    return true;
}

int main(int argc, char** argv)
{
    if (argc > 1
        && (!strcmp("-debug", argv[1]) || !strcmp("-d", argv[1])))
    {
        printf("DEBUG MODE!\n");
        // g_debug = true;
    }

    init();

    // verificar login de admin!
    char np[32], cc[32];
    printf("N Processo: ");
    scanf("%s", np);
    printf("CC: ");
    scanf("%s", cc);

    if (!verificar_login(np, cc)) {
        erro("Dados incorretos!");
        pressione_tecla_para_continuar();
        mysql_close(inno.con);
        exit(EXIT_SUCCESS);
    }

    printf("A verificar......");

    sleep(2); system("clear");

    printf(GREEN "\nBem vindo Admin! \n" NORMAL);
    printf("Programa em C para a gestao da base de dados (experiencia em MySQL necessaria!)\n");
    printf("Recomendavel: Usar o site para a gestão da base de dados (mais fácil e tem interface gráfica)\n");

    pressione_tecla_para_continuar();

    char registos[16][128];
    pedir_campos_mysql(inno.con, "aluno", registos);

    int n = numero_campos_mysql(inno.con, "aluno");
    for (int i = 0; i < n; i++)
        printf("%s ", registos[i]);

    //menu();
    mysql_close(inno.con);

    return 0;
}