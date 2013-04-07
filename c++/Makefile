all: Guia.o Ciudad.o
	 g++  baseDatos.c Guia.o Ciudad.o  -o baseDatos  -I/usr/include/mysql -L/usr/lib/mysql   -lmysqlclient -lstdc++ -lz
	
Guia.o:
	g++ -c Guia.cpp
Ciudad.o:
	g++ -c Ciudad.cpp
