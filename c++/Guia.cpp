#include "Guia.h"

Guia::Guia(int num, string ciudad, string departamento, string documento, string nombre, string apellido, string direccion, string telefono, string celular, string extra) {
    this->numLinea = num;
    this->ciudad = ciudad;
    this->departamento = departamento;
    this-> documento = documento;
    this-> nombre = nombre;
    this-> apellido = apellido;
    this-> direccion = direccion;
    this-> telefono = telefono;
    this-> celular = celular;
    this-> extra = extra;
    
    idCiudad=0;
    idDepartamento=0;
}

bool Guia::validar() {
    return true;
}

void Guia::mostrar() {
    cout << numLinea <<" "<<idCiudad<<" "<< " " << ciudad << " " << departamento << " " << documento << " " << nombre << " " << apellido << " " << direccion << " " << telefono << " " << celular << " " << extra << '\n';
}

bool Guia::buscarId(vector<Ciudad*> ciudades) {

    //     if(x.compare(cadena[medio]) > 0)
    int ta = ciudades.size();

    int mitad= ta/2;
    int variacion = mitad/2;
    while (mitad>=0 && mitad < ta) {
       // cout<<"validando "<<mitad;
        if(ciudad.compare(ciudades[mitad]->ciudad) > 0)
        {
            mitad+=variacion;
            variacion/=2;
            if(variacion ==0)
                variacion=1;
            continue;
        }
        else if(ciudad.compare(ciudades[mitad]->ciudad) < 0)
        {
            mitad-=variacion;
            variacion/=2;
            if(variacion ==0)
                variacion=1;
            continue;
        }
        else
        {
            this->idCiudad = atoi(ciudades[mitad]->idCiudad.c_str());
            cout<<"encontrada "<<ciudades[mitad]->idCiudad<<'\n';
            mitad=ta;
        }
    }
    
    



    return true;
}