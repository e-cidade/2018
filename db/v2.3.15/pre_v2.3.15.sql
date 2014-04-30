select fc_executa_ddl('alter table meigrupoevento set schema tributario');
create table w_rhparam as
select * from rhparam;

create table w_portariatipodocindividual as 
select * from portariatipodocindividual;

create table w_portariatipodoccoletiva as 
select * from portariatipodoccoletiva;

create table w_db_relatoriousuario as
select * from db_relatoriousuario;

create table w_db_geradorrelatoriotemplate as 
select * from db_geradorrelatoriotemplate;

create table w_db_relatoriodepart as 
select * from db_relatoriodepart;

create table w_db_relatorio as 
select * from db_relatorio;

truncate rhparam, 
         portariatipodocindividual, 
         portariatipodoccoletiva, 
         db_relatoriousuario, 
         db_geradorrelatoriotemplate, 
         db_relatoriodepart, 
         db_relatorio; 

insert into db_relatorio
select db63_sequencial + 1000000, db63_db_gruporelatorio, db63_db_tiporelatorio, db63_nomerelatorio, db63_versao_xml, db63_data, db63_xmlestruturarel, db63_db_relatorioorigem
  from w_db_relatorio;

insert into db_relatoriodepart
select db07_sequencial, db07_db_relatorio + 1000000, db07_db_depart 
  from w_db_relatoriodepart;

insert into db_geradorrelatoriotemplate
select db15_sequencial, db15_db_relatorio + 1000000, db15_documento   
  from db_geradorrelatoriotemplate;

insert into db_relatoriousuario
select db09_sequencial, db09_db_relatorio + 1000000, db09_db_usuarios
  from db_relatoriousuario;

insert into portariatipodoccoletiva
select h38_sequencial, h38_portariatipo, h38_modportariacoletiva + 1000000
  from w_portariatipodoccoletiva;

insert into portariatipodocindividual
select h37_sequencial, h37_portariatipo, h37_modportariaindividual + 1000000
  from w_portariatipodocindividual;

insert into rhparam
select h36_modtermoposse + 1000000, h36_instit, h36_modportariacoletiva + 1000000, h36_modportariaindividual + 1000000, h36_ultimaportaria, h36_intersticio, h36_pontuacaominpromocao 
  from w_rhparam;

select setval('db_relatorio_db63_sequencial_seq', coalesce((select max(db63_sequencial) from db_relatorio where db63_sequencial > 999999)::integer, 999999));

drop table w_rhparam, w_portariatipodocindividual, w_portariatipodoccoletiva, w_db_relatoriousuario, w_db_geradorrelatoriotemplate, w_db_relatoriodepart, w_db_relatorio;

-- Novas colunas no "db_listadump"
ALTER TABLE db_listadump
    ADD db54_exportadump BOOLEAN DEFAULT true;

ALTER TABLE db_listadump
    ADD db54_sqlcopia TEXT;
    
    

/** *******************************************************************************************************************
 ** ************************************************** TIME C ********************************************************* 
 ** ***************************************************************************************************************** */
alter table sau_tiposatendimento alter COLUMN s145_c_descr type varchar(70);    

CREATE SEQUENCE etnia_s200_codigo_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE etnia(
s200_codigo int4 NOT NULL default 0,
s200_identificador varchar(4) NOT NULL ,
s200_descricao varchar(100) ,
CONSTRAINT etnia_codi_pk PRIMARY KEY (s200_codigo));

CREATE UNIQUE INDEX etnia_identificador_in ON etnia(s200_identificador);

/** *******************************************************************************************************************
 ** *********************************************** FIM TIME C ******************************************************** 
 ** ***************************************************************************************************************** */


/******************************************** TIME B Tarefa 79624 - INICIO ********************************************/

CREATE SEQUENCE reconhecimentocontabiltipo_c111_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE reconhecimentocontabiltipo(
c111_sequencial   int4 NOT NULL default 0,
c111_descricao    varchar(100) NOT NULL ,
c111_conhistdoc   int4 NOT NULL default 0,
c111_conhistdocestorno    int4 default 0,
CONSTRAINT reconhecimentocontabiltipo_sequ_pk PRIMARY KEY (c111_sequencial));

ALTER TABLE reconhecimentocontabiltipo
ADD CONSTRAINT reconhecimentocontabiltipo_conhistdoc_fk FOREIGN KEY (c111_conhistdoc)
REFERENCES conhistdoc;

ALTER TABLE reconhecimentocontabiltipo
ADD CONSTRAINT reconhecimentocontabiltipo_conhistdocestorno_fk FOREIGN KEY (c111_conhistdocestorno)
REFERENCES conhistdoc;

CREATE  INDEX reconhecimentocontabiltipo_conhistdocestorno_in ON reconhecimentocontabiltipo(c111_conhistdocestorno);
CREATE  INDEX reconhecimentocontabiltipo_conhistdoc_in ON reconhecimentocontabiltipo(c111_conhistdoc);


/* TAREFA 73195 - inicio */
CREATE SEQUENCE finalidadepagamentofundeb_e151_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE finalidadepagamentofundeb(
e151_sequencial  int4 NOT NULL default 0,
e151_codigo      varchar(5) NOT NULL ,
e151_descricao   varchar(200) NOT NULL,
CONSTRAINT finalidadepagamentofundeb_sequ_pk PRIMARY KEY (e151_sequencial));

CREATE  INDEX finalidadepagamentofundeb_codigo_in ON finalidadepagamentofundeb(e151_codigo);
CREATE  INDEX finalidadepagamentofundeb_sequencial_in ON finalidadepagamentofundeb(e151_sequencial);
/* TAREFA 73195 - FIM */

/**********************************************************************************************************************/
/************************************************* TIME B - FIM *******************************************************/
/**********************************************************************************************************************/
