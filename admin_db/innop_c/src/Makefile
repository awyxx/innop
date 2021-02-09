main: main.c inno_mysql.o menus.o utils.o debug.o
	gcc main.c inno_mysql.o menus.o utils.o debug.o -o main -lmysqlclient

inno_mysql.o: inno_mysql.c inno_mysql.h
	gcc inno_mysql.c -c -o inno_mysql.o

menus.o: menus.c menus.h
	gcc menus.c -c -o menus.o

utils.o: utils.c utils.h
	gcc utils.c -c -o utils.o

debug.o: debug.c debug.h
	gcc debug.c -c -o debug.o

clean:
	rm *.o
