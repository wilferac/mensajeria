#ifndef CIUDAD
#define CIUDAD

#include <string.h>
#include <iostream>

using namespace std;

class Ciudad {
public:
    string ciudad, departamento;
    string idCiudad, idDepartamento;
    bool especial;
    
public:
    Ciudad(string ciudad, string departamento, string idCiu, string idDep, bool espe);
    void mostrar();
};

#endif