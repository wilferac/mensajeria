DELIMITER $$

USE `mensajeria`$$

DROP VIEW IF EXISTS `viewGuiasManifiesto`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewGuiasManifiesto` AS 

SELECT
  `gm`.`guiId`            AS `guiId`,
  `gm`.`gmId`             AS `gmId`,
  `gm`.`manId`            AS `manId`,
  `tm`.`idtercero`        AS `idtercero`,
 --  `t`.`nombres_tercero`   AS `nombres_tercero`,
 --  `t`.`apellidos_tercero` AS `apellidos_tercero`,
  GROUP_CONCAT( CONCAT (t.nombres_tercero, ' ',t.apellidos_tercero )SEPARATOR ' ,  ') AS nombres ,
  --  GROUP_CONCAT(t.apellidos_tercero  SEPARATOR ',  ') AS apellidos,
 GROUP_CONCAT(  tm.`tipo`  SEPARATOR ',  ') AS tipos
 

 FROM  manifiesto m  
INNER JOIN guia_manifiesto  gm ON gm.`manId` = m.`idmanifiesto`
INNER JOIN tercero_manifiesto tm ON tm.`idmanifiesto` = m.`idmanifiesto`
INNER JOIN tercero t ON  t.`idtercero` = tm.`idtercero`
WHERE `gm`.`estado` = 1
             AND `gm`.`idEstadoGuia` = 4
        AND m.sucursal_idsucursal IS NULL
        AND (tm.`tipo` = 2 OR tm.`tipo` = 4)
       GROUP BY  gm.gmId 

$$

DELIMITER ;