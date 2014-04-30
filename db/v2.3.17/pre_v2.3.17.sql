/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B - INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * TIME B #81550 - INICIO 
 * -----------------------------------------------------------------------------
 */

/**
 * vinculoeventoscontabeis {{{
 */
CREATE SEQUENCE vinculoeventoscontabeis_c115_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE vinculoeventoscontabeis(
c115_sequencial   int4 NOT NULL default 0,
c115_conhistdocinclusao   int4 NOT NULL default 0,
c115_conhistdocestorno    int4 default 0,
CONSTRAINT vinculoeventoscontabeis_sequ_pk PRIMARY KEY (c115_sequencial));

ALTER TABLE vinculoeventoscontabeis ADD CONSTRAINT vinculoeventoscontabeis_conhistdocinclusao_fk FOREIGN KEY (c115_conhistdocinclusao) REFERENCES conhistdoc;
ALTER TABLE vinculoeventoscontabeis ADD CONSTRAINT vinculoeventoscontabeis_conhistdocestorno_fk  FOREIGN KEY (c115_conhistdocestorno)  REFERENCES conhistdoc;

CREATE INDEX vinculoeventoscontabeis_conhistdocestorno_in  ON vinculoeventoscontabeis(c115_conhistdocestorno);
CREATE INDEX vinculoeventoscontabeis_conhistdocinclusao_in ON vinculoeventoscontabeis(c115_conhistdocinclusao);
/**
 * }}}
 */

/**
 * -----------------------------------------------------------------------------
 * TIME B #81550 - FIM 
 * -----------------------------------------------------------------------------
 */
 select fc_executa_ddl('alter table meigrupoevento set schema issqn');
