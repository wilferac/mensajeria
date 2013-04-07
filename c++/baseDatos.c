
#include <iostream>
#include <string.h>
#include <mysql.h>
#include <vector>
#include <fstream>
#include "Guia.h"
#include "Ciudad.h"

using namespace std;
vector<Guia*> guias;
vector<Ciudad*> ciudades;

MYSQL mysql;

//se encarga de buscar el id ciudad para las guias
void buscarIdCiudad()
{
    int ta = guias.size();

    for (int c = 0; c < ta; c++) {
        //guias[c]->mostrar();
        guias[c]->buscarId(ciudades);
        
    }
}



//muestra el contenido del vector
void showVector() {
    int ta = guias.size();

    for (int c = 0; c < ta; c++) {
        guias[c]->mostrar();
    }

}

//muestra el contenido del vector
void showVectorCiudades() {
    int ta = ciudades.size();

    for (int c = 0; c < ta; c++) {
        ciudades[c]->mostrar();
    }

}
//hace una consulta a la BD

void consultar(char * query) {
    mysql_query(&mysql,"SET NAMES 'utf8'");
    mysql_real_query(&mysql, query, strlen(query));
    MYSQL_RES *res = mysql_store_result(&mysql);
    MYSQL_ROW row;

    while ((row = mysql_fetch_row(res))) {
        int nada = 0;
        if (row[4] != NULL)
            nada = row[4][0] -'0';
        //cout << row[0] << " " << row[1] << " " << row[2] << " " << row[3] << " " << nada << endl;
        Ciudad *objCiu = new Ciudad(row[1], row[3], row[0], row[2], nada);
        ciudades.push_back(objCiu);
    }
    //showVectorCiudades();
}
//separa por ';' el string que le pasen

void separarCadenas(string in, int num) {
    int ta = in.size();
    string *res = new string[9];
    int contRes = 0;
    res[0] = "";
    for (int cont = 0; cont < ta; cont++) {
        if (in[cont] != ';') {
            res[contRes] += in[cont];
        } else {
            contRes++;
            res[contRes] = "";
        }
    }
    Guia *obj = new Guia(num, res[0], res[1], res[2], res[3], res[4], res[5], res[6], res[7], res[8]);
    //obj->mostrar();
    guias.push_back(obj);

    //cout<< res << '\n';
}

//lee un archivo csv

void leerCsv() {
    ifstream file("daticos.csv");
    string value;
    int numLinea = 0;
    while (file.good()) {
        getline(file, value);

        separarCadenas(value, numLinea);
        numLinea++;

        //cout << value << '\n';
    }
    //cuando termino de leerlo lo muestro
    //showVector();
}

int main(int argc, char *argv[]) {

    //inicio la conexion con mysql
    mysql_init(&mysql);
    if (!mysql_real_connect(&mysql
            , "localhost"
            , "root"
            , "toor"
            , "mensajeria", 0, NULL, 0)) {
        cerr << "Horror ! : "
                << mysql_error(&mysql);
    }
    //obtengo los departamentos
    char *query = "SELECT c.idciudad , c.nombre_ciudad, d.iddepartamento , d.nombre_departamento, c.trayecto_especial_ciudad  FROM ciudad c INNER JOIN departamento d ON c.departamento_iddepartamento = d.iddepartamento order by c.nombre_ciudad";

    leerCsv();
    consultar(query);
    
    buscarIdCiudad();
    showVector();

    mysql_close(&mysql);
    return 0;
}


