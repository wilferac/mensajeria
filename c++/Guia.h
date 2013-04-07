#ifndef GUIA
#define GUIA

#include <string.h>
#include <iostream>
#include <vector>
#include <stdlib.h>
#include "Ciudad.h"

using namespace std;

class Guia {
private:
    string ciudad, departamento, documento, nombre, apellido, direccion, telefono, celular, extra;
    int numLinea;
    int idCiudad, idDepartamento;
    
public:
    Guia(int num, string ciudad, string departamento, string documento, string nombre, string apellido, string direccion, string telefono, string celular, string extra);
    bool validar();
    void mostrar();
    bool buscarId(vector<Ciudad*> ciudades);
};

#endif