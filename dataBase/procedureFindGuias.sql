DELIMITER $$
    DROP PROCEDURE IF EXISTS `findUsedGuias`$$
CREATE
    
    PROCEDURE findUsedGuias(IN idAsig INT(11))
 
    BEGIN
	DECLARE tipo INT(1) DEFAULT 0;
	-- aca guardo el prefijo para hacer el like :D
	DECLARE prefijo VARCHAR(4) DEFAULT '';
	
	SELECT ag.asigTipo , s.facturacion INTO tipo, prefijo FROM asignacion_guias ag 
	INNER JOIN sucursal s ON s.idsucursal = ag.idasignacion_guias 
	WHERE ag.idasignacion_guias =idAsig;
	-- si es uno filtro los otros
	IF tipo = 1 THEN
		SELECT g.numero_guia,  ag.inicial_asignacion, ag.cantidad_asignacion, ag.asigTipo, tipo AS tipo FROM asignacion_guias ag
		INNER JOIN  guia g ON SUBSTRING(g.`numero_guia`,5) BETWEEN ag.`inicial_asignacion` AND ag.`inicial_asignacion`+ag.`cantidad_asignacion`
		WHERE ag.`idasignacion_guias` =3 AND g.`numero_guia` NOT LIKE 'CC%' AND g.`numero_guia` NOT LIKE 'MM%'
		AND g.`numero_guia` NOT LIKE 'CACO%' AND g.`numero_guia` NOT LIKE 'BACO%' AND g.`numero_guia` NOT LIKE 'BOCO%';
	ELSEIF tipo = 2 THEN
	-- si es contado (2) entonces uso un like para el prefijo
		SELECT CONCAT(prefijo,(ag.`inicial_asignacion`+ag.`cantidad_asignacion`))AS lol,g.numero_guia,  ag.inicial_asignacion,cantidad_asignacion, ag.asigTipo, tipo AS tipo FROM asignacion_guias ag
		INNER JOIN  guia g ON SUBSTRING(g.`numero_guia`,5) BETWEEN ag.`inicial_asignacion` AND (ag.`inicial_asignacion`+ag.`cantidad_asignacion`)
		WHERE ag.`idasignacion_guias` =3 AND g.`numero_guia` NOT LIKE 'CC%' AND g.`numero_guia` NOT LIKE 'MM%' AND g.numero_guia LIKE CONCAT(prefijo,'%');
	END IF;
	
	
	
    END$$

DELIMITER ;