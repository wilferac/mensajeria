
-- este procedure es para agregar una guia, la idea es solo llamar al procedure y listo
-- llamado
 CALL addGuia (
           2,51,NULL,  
               2, 'inoi', 589,'sss',
                 13,'uno dos', 'tres cuatro','320407',
572, 'valle de lily', 'asdasd', 
               '', 'unitario', 2, 5000,
                 50,   'aaaa', 1, 1, 1,1 
               )
               
CALL addGuia (
           2,51,NULL,  
               2, 'inoi', 589,'sss',
                 13,'uno dos', 'tres cuatro','320407',
572, 'valle de lily', 'asdasd', 
               '112233', 'unitario', 2, 8000,
                 52,   'funca', 5, 6, 7 ,1 
               )               
               
-- codigo del procedure
DELIMITER $$

USE `mensajeria`$$

DROP PROCEDURE IF EXISTS `addGuia`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addGuia`(IN duenoOrden INT(11),IN creador INT(11), IN ordenServi INT(11),
IN remitente INT(11), IN remiInfo VARCHAR(50),IN remiCiu INT(11), IN referencia VARCHAR(30),
IN destinatario INT(11), IN destiNom VARCHAR(45),  IN destiApel VARCHAR(45),IN destiTel VARCHAR(45),
IN destiCiu INT(11), IN destiDirec VARCHAR(70), IN destiInfo VARCHAR(50),  
IN numero VARCHAR(45), IN nomProduc VARCHAR(30), IN idTipoProduc INT(11),IN vrDeclarado INT(11),  
IN peso DECIMAL(10,2),  IN contenido VARCHAR(32), 
 IN largo DECIMAL(10,2), IN ancho DECIMAL(10,2), IN alto DECIMAL(10,2), IN estadoDevolucion INT(11)
)
BEGIN
DECLARE idOr INT DEFAULT -1;
DECLARE prima INT DEFAULT 0;
DECLARE flete INT DEFAULT 0;
DECLARE idProducto INT DEFAULT 0;
DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
-- DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;
START TRANSACTION;
-- busco el idproducto para insertar en la tabla guia :D
SELECT p.idproducto INTO  idProducto FROM producto p WHERE LOWER(p.nombre_producto) = LOWER(nomProduc) AND p.tipo_producto_idtipo_producto=idTipoProduc ;
-- recuerda que falta calcular el valor a pagar de la orden de servicio.
-- la idea es ir acumulando valores de guias en cada orden , para que al momento de facturar sea rapido :O
IF ordenServi IS NULL THEN
	SELECT idorden_servicio INTO idOr FROM orden_servicio WHERE tercero_idcliente = duenoOrden AND  fechaentrada IS NULL  AND estado = 1 AND factura_idfactura IS NULL LIMIT 1;
	IF (idOr = -1) THEN
		INSERT INTO orden_servicio(tercero_idcliente,unidades,osTotal) 
		VALUES(duenoOrden,0,0);
		SET idOr= LAST_INSERT_ID();
	END IF;
	SET ordenServi  = idOr;
END IF;
	INSERT INTO guia (
	alto,ancho, largo,ciudad_iddestino,ciudad_idorigen,contenido,destinatarioInfo,
	direccion_destinatario_guia,flete,nombre_destinatario_guia,numero_guia,
	orden_servicio_idorden_servicio,OWNER,peso_guia,prima,producto_idproducto,referencia,remitenteInfo,
	telefono_destinatario_guia,tercero_iddestinatario,tercero_idremitente,valor_declarado_guia, causal_devolucion_idcausal_devolucion
	)
	VALUES(alto,ancho , largo ,  destiCiu ,remiCiu ,contenido , destiInfo ,destiDirec ,flete , CONCAT(destiNom, '  ', destiApel) , 
	numero , ordenServi , creador,peso ,  prima  ,  idProducto ,  referencia ,remiInfo , destiTel ,
	destinatario ,  remitente ,   vrDeclarado , estadoDevolucion )
	
	ON DUPLICATE KEY UPDATE 
	guia.alto=alto, 	ancho = ancho,  largo = largo,  
	ciudad_iddestino=destiCiu ,
	ciudad_idorigen=remiCiu ,contenido= contenido ,destinatarioInfo= destiInfo,
	direccion_destinatario_guia=destiDirec ,flete= flete,nombre_destinatario_guia=  CONCAT(destiNom, '  ', destiApel)  ,
	numero_guia= numero ,
	orden_servicio_idorden_servicio= ordenServi  ,OWNER= creador ,peso_guia= peso ,prima= prima ,producto_idproducto= idProducto,
	referencia= referencia,
	remitenteInfo= remiInfo ,telefono_destinatario_guia= destiTel ,tercero_iddestinatario= destinatario,tercero_idremitente= remitente,
	valor_declarado_guia= vrDeclarado, causal_devolucion_idcausal_devolucion= estadoDevolucion;
	 
IF destinatario IS NOT NULL THEN	 
	 UPDATE destinatario 
		SET nombres_destinatario= destiNom, 
		apellidos_destinatario = destiApel,
		telefono_destinatario= destiTel,
		direccion_destinatario= destiDirec
	WHERE iddestinatario=destinatario ;
END IF;
-- SELECT * FROM destinatario WHERE  iddestinatario=destinatario ;
-- select * from guia g inner join orden_servicio o     on g.orden_servicio_idorden_servicio = o.idorden_servicio where o.idorden_servicio = ordenServi ;
     
     
     
	 COMMIT;
	
END$$

DELIMITER ;




-- faltan otros dos procedures para la actualizacion de guia, lo que conlleva cambios en los registros de orden de servicio
-- y otro para cancelar las guias, lo que tambien conlleva cambios en orden de servicio.



