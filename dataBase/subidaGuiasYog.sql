CREATE TEMPORARY TABLE   guia_temp
(
	ciudad VARCHAR(45) NOT NULL,
	departamento VARCHAR(45) NOT NULL,
	cedula VARCHAR(45) NOT NULL,
	nombre VARCHAR(45) NOT NULL,
	apellidos VARCHAR(45) NOT NULL,
	direccion VARCHAR(45) NOT NULL,
	telefono VARCHAR(45) NOT NULL,
	celular VARCHAR(45),
	extra VARCHAR(45)
);

DROP TABLE guia_temp

SELECT * FROM guia_temp

DELETE FROM guia_temp




LOAD DATA LOCAL INFILE '/home/inovate/public_html/Mensajeria/tmp/daticos.csv' INTO TABLE guia_temp
          FIELDS TERMINATED BY ',' ENCLOSED BY '"'
          LINES TERMINATED BY '\n';

          
          
          UPDATE guia_temp SET ciudad = id