function ElementosClientesVisibles(readonly, id, cc, nombres, apellidos, direccion)
{
    document.getElementById('tituloclientes').style.visibility = 'visible';
    document.getElementById('labcccliente').style.visibility = 'visible';

    document.getElementById('idcliente').value = id;

    document.getElementById('cccliente').style.visibility = 'visible';
    document.getElementById('cccliente').value = cc;
    //document.getElementById('cccliente').readOnly = readonly;

    document.getElementById('labnombrescliente').style.visibility = 'visible';
    document.getElementById('nombrescliente').style.visibility = 'visible';
    document.getElementById('nombrescliente').value = nombres;
    document.getElementById('nombrescliente').readOnly = readonly;

    document.getElementById('labapellidoscliente').style.visibility = 'visible';
    document.getElementById('apellidoscliente').style.visibility = 'visible';
    document.getElementById('apellidoscliente').value = apellidos;
    document.getElementById('apellidoscliente').readOnly = readonly;

    document.getElementById('labdireccioncliente').style.visibility = 'visible';
    document.getElementById('direccioncliente').style.visibility = 'visible';
    document.getElementById('direccioncliente').value = direccion;
    document.getElementById('direccioncliente').readOnly = readonly;

    document.getElementById('labExtraRemitente').style.visibility = 'visible';
    document.getElementById('extraRemitente').style.visibility = 'visible';
}



function ElementosClientesInvisibles()
{
    document.getElementById('tituloclientes').style.visibility = 'hidden';
    document.getElementById('labcccliente').style.visibility = 'hidden';

    document.getElementById('cccliente').style.visibility = 'hidden';
    document.getElementById('cccliente').value = '';
    document.getElementById('cccliente').readOnly = false;

    document.getElementById('labnombrescliente').style.visibility = 'hidden';
    document.getElementById('nombrescliente').style.visibility = 'hidden';
    document.getElementById('nombrescliente').value = '';
    document.getElementById('nombrescliente').readOnly = false;

    document.getElementById('labapellidoscliente').style.visibility = 'hidden';
    document.getElementById('apellidoscliente').style.visibility = 'hidden';
    document.getElementById('apellidoscliente').value = '';
    document.getElementById('apellidoscliente').readOnly = false;

    document.getElementById('labdireccioncliente').style.visibility = 'hidden';
    document.getElementById('direccioncliente').style.visibility = 'hidden';
    document.getElementById('direccioncliente').value = '';
    document.getElementById('direccioncliente').readOnly = false;


}
function ElementosClientesAbuscarVisibles()
{
    document.getElementById('tituloclientes').style.visibility = 'visible';
    document.getElementById('labcccliente').style.visibility = 'visible';
    document.getElementById('cccliente').style.visibility = 'visible';

}

function ElementosDestinatariosVisibles(readonly, cc, nombres, apellidos, direccion, telefono, celular)
{
    document.getElementById('labccdestinatario').style.visibility = 'visible';
    document.getElementById('ccdestinatario').style.visibility = 'visible';
    document.getElementById('ccdestinatario').value = cc;
    document.getElementById('ccdestinatario').readOnly = readonly;

    document.getElementById('labnombresdestinatario').style.visibility = 'visible';
    document.getElementById('nombresdestinatario').style.visibility = 'visible';
    document.getElementById('nombresdestinatario').value = nombres;


    document.getElementById('labapellidosdestinatario').style.visibility = 'visible';
    document.getElementById('apellidosdestinatario').style.visibility = 'visible';
    document.getElementById('apellidosdestinatario').value = apellidos;

    document.getElementById('labdirecciondestinatario').style.visibility = 'visible';
    document.getElementById('direcciondestinatario').style.visibility = 'visible';
    document.getElementById('direcciondestinatario').value = direccion;

    document.getElementById('labtelefono1destinatario').style.visibility = 'visible';
    document.getElementById('telefono1destinatario').style.visibility = 'visible';
    document.getElementById('telefono1destinatario').value = telefono;

    document.getElementById('labcelulardestinatario').style.visibility = 'visible';
    document.getElementById('celulardestinatario').style.visibility = 'visible';
    document.getElementById('celulardestinatario').value = celular;

    document.getElementById('capadatosguia').style.visibility = 'visible';


}



function ElementosDestinatariosInvisibles()
{

    document.getElementById('labccdestinatario').style.visibility = 'hidden';
    document.getElementById('ccdestinatario').style.visibility = 'hidden';
    document.getElementById('ccdestinatario').value = '';


    document.getElementById('labnombresdestinatario').style.visibility = 'hidden';
    document.getElementById('nombresdestinatario').style.visibility = 'hidden';
    document.getElementById('nombresdestinatario').value = '';


    document.getElementById('labapellidosdestinatario').style.visibility = 'hidden';
    document.getElementById('apellidosdestinatario').style.visibility = 'hidden';
    document.getElementById('apellidosdestinatario').value = '';


    document.getElementById('labdirecciondestinatario').style.visibility = 'hidden';
    document.getElementById('direcciondestinatario').style.visibility = 'hidden';
    document.getElementById('direcciondestinatario').value = '';

    document.getElementById('labcelulardestinatario').style.visibility = 'hidden';
    document.getElementById('celulardestinatario').style.visibility = 'hidden';
    document.getElementById('celulardestinatario').value = '';

    document.getElementById('labtelefono1destinatario').style.visibility = 'hidden';
    document.getElementById('telefono1destinatario').style.visibility = 'hidden';
    document.getElementById('telefono1destinatario').value = '';

    document.getElementById('capadatosguia').style.visibility = 'hidden';



}

function ElementosDatosABuscarDestinatarioVisibles()
{
    document.getElementById('titulodestinatarios').style.visibility = 'visible';
    document.getElementById('labdatoArecordar').style.visibility = 'visible';
    document.getElementById('datoArecordar').style.visibility = 'visible';
    document.getElementById('capaPeso').style.visibility = 'visible';
    document.getElementById('datoArecordar').value = '';

}


function ElementosDatosABuscarDestinatarioInvisibles()
{

    document.getElementById('titulodestinatarios').style.visibility = 'hidden';
    document.getElementById('labdatoArecordar').style.visibility = 'hidden';
    document.getElementById('datoArecordar').style.visibility = 'hidden';
    document.getElementById('datoArecordar').value = '';
}








