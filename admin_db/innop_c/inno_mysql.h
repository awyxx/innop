#include <mysql/mysql.h>

// dependencies: default-libmysqlclient-dev

// biblioteca c para o trabalho innop

/* ANSI codes usados para printar com cores */
#define NORMAL "\x1b[0m"
#define RED    "\x1b[91m"
#define GREEN  "\x1b[92m"

/* verifica se é possivel fazer conexão com MySQL */
MYSQL* init_mysql();

/* verifica se conseguimos conectar à db com os dados do usuario */
void conectar_mysql(MYSQL* con,
                    const char* host,
                    const char* user,
                    const char* pw,
                    const char* db);

/* executar query simples */
bool executar_query_mysql(MYSQL* con, const char* query);

/* da return ao numero de campos(colunas) de uma tabela */
int numero_campos_mysql(MYSQL* con, const char* tabela);

/* pede os campos de um registo e armazena-os numa matrix(usado para inserir!) */ 
void pedir_campos_mysql(MYSQL* con, const char* tabela, char registo[16][128]);

/* mostra os campos (colunas) de uma tabela */
void mostrar_campos_mysql(MYSQL* con, const char* tabela);

/* mostra as tabelas de uma base de dados e os seus campos */
void mostrar_tabelas_mysql(MYSQL* con, const char* db);

/* lista a tabela, os campos e os dados */
void listar_uma_mysql(MYSQL* con, const char* tabela);

/* remover um campo dado o nome d tabela, o nome do campo e o campo */
void remover_campo_mysql(MYSQL* con,
                         const char* tabela,
                         const char* campo,
                         const char* valcampo);

/* da return ao numero de tabelas da base de dados */
int numero_tabelas_mysql(MYSQL* con, const char* db);

/* mostra o tipo do campo */
char* get_type(enum enum_field_types type);

/* da store em dest o nome de todas as tabelas */
void matrix_nome_tabelas(char dest[32][32], MYSQL* con, const char* db);

/* da return true se a tabela estiver vazia */
bool tabela_ta_vazia(MYSQL* con, char* nometabela);

/* da return true se a tabela existir */
bool tabela_existe(MYSQL* con, const char* db, const char* nometabela);

/* da return do index da tabela na db (uso estranho mas funciona pra
 * desenrrascar) */
int tabela_num(MYSQL* con, const char* db, const char* nometabela);