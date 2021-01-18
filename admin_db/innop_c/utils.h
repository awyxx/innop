#include <stdio.h>
#include <stdlib.h>

/* forma de ler strings mais segura e precisa, iremos usar isto
   para ler as querys do utilizador (inseridas manualmente) */
char* ler_query();

/* formata uma string com os argumentos passados */
void format_str(char* newstr, const char* text, ...);

/* estetica... faz getchar so */
void pressione_tecla_para_continuar();