/*
* este procedure se va a encargar de guardar una guia. 
* al mismo tiempo debe asignar la guia a una orden de servicio
*
*
*/
-- idguia 	numero_guia 	orden_servicio_idorden_servicio 	zona_idzona 	causal_devolucion_idcausal_devolucion 	manifiesto_idmanifiesto 	producto_idproducto 	ciudad_iddestino 	valor_declarado_guia 	nombre_destinatario_guia 	direccion_destinatario_guia 	telefono_destinatario_guia 	peso_guia el peso de la guia en kg	ciudad_idorigen 	tercero_idremitente 	tercero_iddestinatario 	fecha 	remitenteInfo 	destinatarioInfo 	owner 	flete 	prima 	contenido 	referencia 	largo 	ancho 	alto 	estado

DELIMITER //
CREATE PROCEDURE addGuia(IN idUsu int(11),in idGrupo int(11))

BEGIN
    insert into UsuarioGrupo(usuId, gruId) 
    values(idUsu, idGrupo)
    ON DUPLICATE KEY UPDATE usuId=usuId;
END

//
DELIMITER ;


/*
* este procedure se encargara de actualizar los datos de una guia.
* se supone que se va a usar solo para guia incompletas???
* tener cuidado ya que puede afectar el osTotal si cambiar algun valor en la guia.
*
*/
DELIMITER //
CREATE PROCEDURE updateGuia(IN idUsu int(11),in idGrupo int(11))

BEGIN
    insert into UsuarioGrupo(usuId, gruId) 
    values(idUsu, idGrupo)
    ON DUPLICATE KEY UPDATE usuId=usuId;
END

//
DELIMITER ;



/*
* cancelar guia es un proceso muy comun, ademas de cancelarla se debe restar
* el valor de la misma de la orden de servicio
*
*
*/
DELIMITER //
CREATE PROCEDURE cancelGuia(IN idUsu int(11),in idGrupo int(11))

BEGIN
    insert into UsuarioGrupo(usuId, gruId) 
    values(idUsu, idGrupo)
    ON DUPLICATE KEY UPDATE usuId=usuId;
END

//
DELIMITER ;
