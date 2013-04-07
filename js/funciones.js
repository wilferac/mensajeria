/***************************************/
function validar(forma)
{
    var sw = 0, alerta;

    alerta = "Falta por ingresar: \n\n";
    if (forma.login.value == "")
    {
        alerta = alerta + "Usuario\n";
        sw = 1;
    }
    if (forma.password.value == "")
    {
        alerta = alerta + "Contrase�a";
        sw = 1;

    }


    if (sw == 1)
    {
        alert(alerta);
        return false;
    }
    else
        return true;

}
/*************************************/
//Este script y otros muchos pueden
//descarse on-line de forma gratuita
//en El C�digo: www.elcodigo.com
//
//	Version 1
//	03/02/2001

function DiferenciaFechas(fechaMayor, fechaMenor, output, operacion, plazo) {

    //Obtiene los datos del formulario
    // CadenaFecha1 = formulario.fecha1.value
    //CadenaFecha2 = formulario.fecha2.value


    CadenaFecha1 = fechaMayor;
    CadenaFecha2 = fechaMenor;

    //Obtiene dia, mes y a�o
    var fecha1 = new fecha(CadenaFecha1);
    var fecha2 = new fecha(CadenaFecha2);

    var miFecha1 = new Date(fecha1.anio, fecha1.mes, fecha1.dia);
    if (operacion = "")
    {
        //Obtiene objetos Date
        var miFecha2 = new Date(fecha2.anio, fecha2.mes, fecha2.dia);
    }
    else
    {
        var miFechatmp = new Date(fecha2.anio, fecha2.mes, fecha2.dia);

        //var fechaInicial = new Date(2010, 1, 22);
        valorFecha = miFechatmp.valueOf();
        valorFechaTermino = valorFecha + (plazo * 24 * 60 * 60 * 1000);
        miFecha2 = new Date(valorFechaTermino);

    }
    //Resta fechas y redondea
    var diferencia = (miFecha1.getTime() - miFecha2.getTime()) * (-1);
    var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
    var segundos = Math.floor(diferencia / 1000);
    //alert ('La diferencia es de ' + dias + ' dias,\no ' + segundos + ' segundos.');
    output.value = dias;

    return true;
}

function fecha(cadena) {

    //Separador para la introduccion de las fechas
    var separador = "/";

    //Separa por dia, mes y a�o
    if (cadena.indexOf(separador) != -1) {
        var posi1 = 0;
        var posi2 = cadena.indexOf(separador, posi1 + 1);
        var posi3 = cadena.indexOf(separador, posi2 + 1);
        this.dia = cadena.substring(posi1, posi2);
        this.mes = cadena.substring(posi2 + 1, posi3);
        this.anio = cadena.substring(posi3 + 1, cadena.length);
    } else {
        this.dia = 0;
        this.mes = 0;
        this.anio = 0;
    }
}

function wo(obj, nombre)
{
    window.open(obj.href, nombre, "location=0,toolbar=0,menubar=0,resizable=1,width=900,height=500");
    return false;
}

