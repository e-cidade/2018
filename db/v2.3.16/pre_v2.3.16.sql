/** *******************************************************************************************************************
 ** ************************************************** TIME C ********************************************************* 
 ** ***************************************************************************************************************** */
CREATE SEQUENCE tipoeventocalendario_ed333_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE tipoeventocalendario(
ed333_sequencial int4 NOT NULL default 0,
ed333_nome varchar(60) NOT NULL,
ed333_abreviatura varchar(5),
CONSTRAINT tipoeventocalendario_sequ_pk PRIMARY KEY (ed333_sequencial));
/** *******************************************************************************************************************
 ** *********************************************** FIM TIME C ******************************************************** 
 ** ***************************************************************************************************************** */


/**
 * TIME A
 */

CREATE SEQUENCE caracteristicapitsefaz_db142_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE caracteristicapitsefaz(
db142_sequencial		int4 NOT NULL default 0,
db142_caracteristica		int8 NOT NULL default 0,
db142_codigopitsefaz		varchar(5) ,
CONSTRAINT caracteristicapitsefaz_sequ_pk PRIMARY KEY (db142_sequencial));

ALTER TABLE caracteristicapitsefaz
ADD CONSTRAINT caracteristicapitsefaz_caracteristica_fk FOREIGN KEY (db142_caracteristica)
REFERENCES caracteristica;

CREATE UNIQUE INDEX caracteristicapitsefaz_caracteristica_uq ON caracteristicapitsefaz(db142_caracteristica);

CREATE  INDEX caracteristicapitsefaz_caracteristica_in ON caracteristicapitsefaz(db142_caracteristica);


/**
 * FIM TIME A
 */
