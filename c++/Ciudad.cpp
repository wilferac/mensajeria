#include "Ciudad.h"

Ciudad::Ciudad(string ciudad, string departamento, string idCiu, string idDep, bool espe) {
    
    this->ciudad = ciudad;
    this->departamento = departamento;
    this-> idCiudad = idCiu;
    this-> idDepartamento = idDep;
    this-> especial = espe;

}

void Ciudad::mostrar() {
    cout << ciudad << " " << departamento << " " << idCiudad << " " << idDepartamento << " " << especial <<'\n';
}