/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */


/**
 * TAREFA 81917
 */

 -- Criando  sequences
CREATE SEQUENCE regimeprevidencia_rh127_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE regimeprevidenciasistemaexterno_rh130_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE TABLE regimeprevidencia(
rh127_sequencial    int4 NOT NULL default 0,
rh127_descricao   varchar(100) ,
CONSTRAINT regimeprevidencia_sequ_pk PRIMARY KEY (rh127_sequencial));

CREATE TABLE regimeprevidenciasistemaexterno(
rh130_sequencial    int4 NOT NULL default 0,
rh130_regimeprevidencia   int4 NOT NULL default 0,
rh130_db_sistemaexterno   int4 NOT NULL default 0,
rh130_codigosistema   varchar(50) ,
CONSTRAINT regimeprevidenciasistemaexterno_sequ_pk PRIMARY KEY (rh130_sequencial));

ALTER TABLE regimeprevidenciasistemaexterno
ADD CONSTRAINT regimeprevidenciasistemaexterno_sistemaexterno_fk FOREIGN KEY (rh130_db_sistemaexterno)
REFERENCES db_sistemaexterno;

ALTER TABLE regimeprevidenciasistemaexterno
ADD CONSTRAINT regimeprevidenciasistemaexterno_regimeprevidencia_fk FOREIGN KEY (rh130_regimeprevidencia)
REFERENCES regimeprevidencia;

CREATE  INDEX regimeprevidenciasistemaexterno_db_sistemaexterno_in ON regimeprevidenciasistemaexterno(rh130_db_sistemaexterno);

CREATE  INDEX regimeprevidenciasistemaexterno_regimeprevidencia_in ON regimeprevidenciasistemaexterno(rh130_regimeprevidencia);

/**
 * FIM TAREFA 81917
 */ 


/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A - FIM
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C - INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

CREATE SEQUENCE tipoingresso_ed334_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE tipoingresso(
ed334_sequencial int4 NOT NULL default 0,
ed334_tipo varchar(40) ,
CONSTRAINT tipoingresso_sequ_pk PRIMARY KEY (ed334_sequencial));

INSERT INTO tipoingresso VALUES(1, 'Normal'), (2, 'Classificado'), (3, 'Reclassificado'), (4, 'Avanço');

/**
 * -----------------------------------------------------------------------------
 * TIME C - FIM 
 * -----------------------------------------------------------------------------
 */
