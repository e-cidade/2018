/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */
CREATE SEQUENCE situacaoeducacao_ed109_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE tiposituacaoeducacao_ed108_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE tiposituacaoeducacao(
ed108_sequencial int4 NOT NULL default nextval('tiposituacaoeducacao_ed108_sequencial_seq'),
ed108_tipo varchar(50) ,
CONSTRAINT tiposituacaoeducacao_sequ_pk PRIMARY KEY (ed108_sequencial));

CREATE TABLE situacaoeducacao(
ed109_sequencial int4 NOT NULL  default nextval('situacaoeducacao_ed109_sequencial_seq'),
ed109_descricao varchar(50) NOT NULL ,
ed109_tiposituacaoeducacao int4 NOT NULL ,
ed109_ativo bool default 'f',
CONSTRAINT situacaoeducacao_sequ_pk PRIMARY KEY (ed109_sequencial));

ALTER TABLE situacaoeducacao ADD CONSTRAINT situacaoeducacao_tiposituacaoeducacao_fk FOREIGN KEY (ed109_tiposituacaoeducacao) 
 REFERENCES tiposituacaoeducacao;

CREATE INDEX situacaoeducacao_tiposituacaoeducacao_in ON situacaoeducacao(ed109_tiposituacaoeducacao);
/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C FIM
 * --------------------------------------------------------------------------------------------------------------------
 */