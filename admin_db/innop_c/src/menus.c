#include <mysql/mysql.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "dados.h"
#include "debug.h"
#include "inno_mysql.h"
#include "menus.h"
#include "utils.h"

// Menu principal
void menu()
{
    int opc;
    printf("\n********************************\n");
    printf("1. Introduzir registos\n");
    printf("2. Listar registos\n");
    printf("3. Remover registos\n");
    printf("4. Modificar registos\n");
    printf("5. Suporte\n");
    printf("0. Sair\n\n > ");
    scanf("%d", &opc);

    system("clear");
    switch (opc)
    {
        case 1:
            menu_inserir();
            break;
        case 2:
            menu_listar();
            break;
        case 3:
            menu_remover();
            break;
        case 4:
            menu_modificar();
            break;
        case 5:
            suporte();
            break;
        case 0:
            exit(EXIT_SUCCESS);
        default:
            erro("Introduza uma opção válida!");
            menu();
            break;
    }
}

/*****************
    INSERIR
******************/
void menu_inserir()
{
    int opc;
    printf("\n********************************\n");
    printf("1. Introduzir manualmente\n");
    printf("2. Introduzir por query\n");
    printf("3. Introduzir varias queries usando um ficheiro\n");
    printf("0. Voltar\n");
    printf("\n > ");
    scanf("%d", &opc);
    switch (opc)
    {
        case 1:
            inserir_manualmente();
            break;
        case 2:
            inserir_query();
            break;
        case 3:
            inserir_ficheiro();
            break;
        case 0:
            system("clear");
            menu();
            break;
        default:
            erro("Introduza uma opcao valida!");
            menu_inserir();
            break;
    }
}

void inserir_manualmente()
{
    system("clear");
    mostrar_tabelas_mysql(inno.con, inno.db);

    char tabela[32];
    printf("\nNome da tabela que pretende introduzir (0 = voltar): ");
    scanf("%s", tabela);

    if (!tabela_existe(inno.con, inno.db, tabela))
    {
        printf(RED "\nTabela nao existe!" NORMAL);
        pressione_tecla_para_continuar();
        system("clear");
        inserir_manualmente();
        return;
    }

    if (!strcmp(tabela, "0"))
    {
        system("clear");
        menu_inserir();
        return;
    }

    char c[16][128]; // campos
    pedir_campos_mysql(inno.con, tabela, c);

    // optimizar??? n cnsg pensar
    char query[512];
    if (!strcmp(tabela, "aluno"))
        format_str(query,
                   "(%s,%s,'%s',%s,'%s','%s','%s',%s,'%s',NULL)",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5],
                   c[6],
                   c[7],
                   c[8]);
    else if (!strcmp(tabela, "cartao"))
        format_str(query, "(%s, '%s', %s)", c[0], c[1], c[2]);
    else if (!strcmp(tabela, "disciplina"))
        format_str(query, "(%s, '%s', %s, %s)", c[0], c[1], c[2], c[3]);
    else if (!strcmp(tabela, "dt"))
        format_str(query, "(%s, %s)", c[0], c[1]);
    else if (!strcmp(tabela, "ee"))
        format_str(query,
                   "(%s,'%s','%s','%s',%s,'%s')",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5]);
    else if (!strcmp(tabela, "faltas"))
        format_str(query,
                   "(%s,%s,'%s','%s',%s,%s,'%s')",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5],
                   c[6]);
    else if (!strcmp(tabela, "horarios"))
        format_str(query,
                   "(%s,%s,'%s','%s','%s','%s','%s')",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5],
                   c[6]);
    else if (!strcmp(tabela, "notas"))
        format_str(query, "(%s,%s,%s,%s,%s)", c[0], c[1], c[2], c[3], c[4]);
    else if (!strcmp(tabela, "professor"))
        format_str(query,
                   "(%s,'%s',%s,'%s','%s',%s,'%s',NULL)",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5],
                   c[6]);
    else if (!strcmp(tabela, "sumarios"))
        format_str(query,
                   "(%s,%s,%s,'%s',%s,'%s',%s)",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4],
                   c[5],
                   c[6]);
    else if (!strcmp(tabela, "turma"))
        format_str(query, "(%s,%s,%s,%s,%s)", c[0], c[1], c[2], c[3], c[4]);
    else if (!strcmp(tabela, "turmas"))
        format_str(query,
                   "(%s,'%s',%s,'%s',%s)",
                   c[0],
                   c[1],
                   c[2],
                   c[3],
                   c[4]);

    char query_asr[600];
    format_str(query_asr, "INSERT INTO %s VALUES %s", tabela, query);

    if (executar_query_mysql(inno.con, query_asr))
    {
        printf(GREEN "\nRegisto introduzido com sucesso!\n" NORMAL);
        pressione_tecla_para_continuar();
        menu();
    }

    menu_inserir();
}

void inserir_query()
{
    system("clear");
    mostrar_tabelas_mysql(inno.con, inno.db);
    printf("\nDica: INSERT INTO tabela (campo1, campo2, ...) VALUES "
           "(val1, val2, ...)\n");
    printf("\n Digite a query (-1 para voltar): -> ");
    char* query = ler_query();
    if (strstr(query, "-1") != NULL)
    {
        system("clear");
        menu_inserir();
        return;
    }
    executar_query_mysql(inno.con, query);
    menu_inserir();
}

void inserir_ficheiro()
{
    tentativa("inserir_ficheiro()\n");
    system("clear");

    char nome_ficheiro[64];
    printf("\nDigite o nome do ficheiro onde as suas queries estao (0 = "
           "voltar): ");
    scanf("%s", nome_ficheiro);

    if (!strcmp(nome_ficheiro, "0"))
    {
        system("clear");
        menu_inserir();
        return;
    }

    FILE* f;
    f = fopen(nome_ficheiro, "r");
    if (f == NULL)
    {
        printf(RED "Ficheiro nao existe!\n" NORMAL);
        pressione_tecla_para_continuar();
        inserir_ficheiro();
    }

    // ler linha a linha e tentar executar query
    char query[512];
    int n = 0;
    while (fgets(query, sizeof(query), f))
    {
        if (executar_query_mysql(inno.con, query))
        {
            printf(GREEN
                   "Linha/Query Nº %d executada com sucesso!\n" NORMAL,
                   n);
        }
        n++;
    }

    if (!n)
    {
        printf(RED "Ficheiro vazio!\n" NORMAL);
        pressione_tecla_para_continuar();
        inserir_ficheiro();
    }

    fclose(f);
    pressione_tecla_para_continuar();
    menu();
}

/***************
    LISTAR
****************/
void menu_listar()
{
    int opc;
    printf("\n********************************\n");
    printf("1. Listar dados de uma tabela\n");
    printf("2. Listar todas as tabelas\n");
    printf("0. Voltar\n\n > ");
    scanf("%d", &opc);
    switch (opc)
    {
        case 1:
            listar_uma();
            break;
        case 2:
            listar_todas();
            break;
        case 0:
            system("clear");
            menu();
            break;
        default:
            erro("Introduza uma opcao valida!");
            menu_listar();
            break;
    }
}

void listar_uma()
{
    system("clear");
    mostrar_tabelas_mysql(inno.con, inno.db);
    char tabela[32];
    printf("\n Digite o nome da tabela: ");
    scanf("%s", tabela);
    if (tabela_ta_vazia(inno.con, tabela))
    {
        erro("Tabela esta vazia!");
        pressione_tecla_para_continuar();
        menu_listar();
    }
    system("clear");
    listar_uma_mysql(inno.con, tabela);
    pressione_tecla_para_continuar();
    menu_listar();
}

void listar_todas()
{
    system("clear");
    mostrar_tabelas_mysql(inno.con, inno.db);
    printf("\n\nIMPORTANTE: Ficheiro 'todas.txt' criado! (output inteiro "
           "com todos os campos/registos)\n");
    // Criar ficheiro com o ouput todo (loop listar_uma basicamente)
    listar_todas_ficheiro();
    pressione_tecla_para_continuar();
    menu_listar();
}

void listar_todas_ficheiro()
{
    FILE* f       = fopen("todas.txt", "w");
    int n_tabelas = numero_tabelas_mysql(inno.con, inno.db);

    // matrix com o nome das tabelas (funcao para reduzir codigo?)
    char tabelas[32][32];
    matrix_nome_tabelas(tabelas, inno.con, inno.db);
    // loop e listar cada uma po ficheiro
    for (int i = 0; i < n_tabelas; i++)
    {
        char query[128];
        format_str(query, "SELECT * FROM %s", tabelas[i]);
        if (!executar_query_mysql(inno.con, query))
            return;

        MYSQL_RES* result = mysql_store_result(inno.con);
        if (result == NULL)
            erro_mysql(inno.con, false);

        MYSQL_ROW row;
        MYSQL_FIELD* field;

        int num_fields = mysql_num_fields(result);
        int linha      = sizeof(tabelas[i]) + sizeof("Tabela") + 1;

        fprintf(f, "\n\n");
        for (int i = 0; i < linha; i++)
            fprintf(f, "*");
        fprintf(f, "\nTabela: %s\n", tabelas[i]);
        for (int i = 0; i < linha; i++)
            fprintf(f, "-");
        fprintf(f, "\n");

        while ((row = mysql_fetch_row(result)))
        {
            for (int i = 0; i < num_fields; i++)
            {
                if (i == 0)
                {
                    while (field = mysql_fetch_field(result))
                        fprintf(f, "%s ", field->name);

                    fprintf(f, "\n");
                    for (int i = 0; i < 84; i++)
                        fprintf(f, "-");
                    fprintf(f, "\n");
                }
                fprintf(f, "%s ", row[i] ? row[i] : "NULL");
            }
        }

        fprintf(f, "\n");
        mysql_free_result(result);
    }

    fclose(f);
}

/***************
    MODIFICAR
****************/
void menu_modificar()
{
    mostrar_tabelas_mysql(inno.con, inno.db);
    char nometabela[32];
    printf("\nIntroduza o nome da tabela que pretende fazer modificações "
           "(0 = voltar): ");
    scanf("%s", nometabela);

    if (!strcmp("0", nometabela))
    {
        system("clear");
        menu();
    }

    if (!tabela_existe(inno.con, inno.db, nometabela))
    {
        erro("Tabela não existe!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    if (tabela_ta_vazia(inno.con, nometabela))
    {
        erro("Tabela esta vazia!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    int num_tabela; // index da tabela na db
    if ((num_tabela = tabela_num(inno.con, inno.db, nometabela)) == -1)
    {
        erro("Erro estranho!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    system("clear");
    listar_uma_mysql(inno.con, nometabela);

    // codigo optimizado de uma maneira estranha... mas funciona xD
    char define_pri[32][32] = {
        "codaluno", "codcartao", "coddisciplina", "coddt",
        "codee",    "codfalta",  "codhorario",    "codaluno",
        "codprof",  "codprof",   "codaluno",      "codturma"
    };
    char primary[64], primary_val[64], campo[64], campo_atualizado[64],
      query[256];
    strcpy(primary, define_pri[num_tabela]);

    printf("\n%s que pretende atualizar(-1 = voltar): ", primary);
    scanf("%s", primary_val);

    if (!strcmp("-1", primary_val))
    {
        system("clear");
        menu_modificar();
    }

    printf("Campo que pretende atualizar: ");
    scanf("%s", campo);
    printf("%s: ", campo);
    scanf("%s", campo_atualizado);
    format_str(query,
               "UPDATE %s SET %s = '%s' WHERE %s = '%s'",
               nometabela,
               campo,
               campo_atualizado,
               primary,
               primary_val);

    if (executar_query_mysql(inno.con, query))
    {
        printf("\nRegisto alterado com sucesso!\n");
    }

    pressione_tecla_para_continuar();
    menu_modificar();
}

/***************
    REMOVER
****************/

void menu_remover()
{
    mostrar_tabelas_mysql(inno.con, inno.db);
    char nometabela[32];
    printf("\nIntroduza o nome da tabela que pretende remover registos "
           "(0 = voltar): ");
    scanf("%s", nometabela);

    if (!strcmp("0", nometabela))
    {
        system("clear");
        menu();
    }

    if (!tabela_existe(inno.con, inno.db, nometabela))
    {
        erro("Tabela não existe!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    if (tabela_ta_vazia(inno.con, nometabela))
    {
        erro("Tabela esta vazia!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    int num_tabela; // index da tabela na db
    if ((num_tabela = tabela_num(inno.con, inno.db, nometabela)) == -1)
    {
        erro("Erro estranho!");
        pressione_tecla_para_continuar();
        menu_remover();
    }

    system("clear");
    listar_uma_mysql(inno.con, nometabela);

    // codigo optimizado de uma maneira estranha... mas funciona xD
    char define_pri[32][32] = {
        "codaluno", "codcartao", "coddisciplina", "coddt",
        "codee",    "codfalta",  "codhorario",    "codaluno",
        "codprof",  "codprof",   "codaluno",      "codturma"
    };

    char primary[64], primary_val[64], query[256];
    strcpy(primary, define_pri[num_tabela]);

    printf("\n%s que pretende remover (-1 = voltar): ", primary);
    scanf("%s", primary_val);

    if (!strcmp("-1", primary_val))
    {
        system("clear");
        menu_remover();
    }

    format_str(query,
               "DELETE FROM %s WHERE %s = '%s'",
               nometabela,
               primary,
               primary_val);

    if (executar_query_mysql(inno.con, query))
    {
        printf("\nRegisto removido com sucesso!\n");
    }

    pressione_tecla_para_continuar();
    menu_remover();
}

/******************
    suporte
******************/

void suporte()
{
    printf(
      ""
      "InnoPlus é um projeto desenvolvido por 2 alunos do curso "
      "profissional de GSPI, Nelson e Roberto, feito para o trabalho "
      "final de curso.\n"
      "O seu objetivo é reunir algumas das utilidades mais importantes "
      "para a gestão escolar, de uma forma optimizada, mais apelativa e "
      "fácil de usar.\n"
      "Possuímos várias ferramentas, para aumentar a compatibilidade "
      "entre os vários sistemas operativos ou dispositivos.\n\n"
      "Este programa em C, destina-se ao uso avançado do administrador "
      "da base de dados, sendo assim ligeiramente mais difícil de usar "
      "mas só deve ser usado quando preciso.\n"
      "Temos um programa em vb.NET, destinando-se ao uso dos "
      "professores, é semelhante ao site, mas pode ser usado nativamente "
      "para computadores com Windows.\n"
      "O site irá servir como a 'ponte' de compatibilidade com outros "
      "dispositivos/plataformas, como por exemplo, telemóveis, tablets, "
      "ou computadores com Linux ou MacOS. "
      "Tem as mesmas utilidades que o programa em vb.NET, também vai ter "
      "um login para o administrador caso queira usar uma interface mais "
      "amigável para a gestão da base de dados (invés de usar o programa "
      "em C).\n\n");
    pressione_tecla_para_continuar();
    menu();
}