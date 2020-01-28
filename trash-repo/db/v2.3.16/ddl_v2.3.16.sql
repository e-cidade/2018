/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * TIME A 
 * TAREFA #31037
 */
alter table certidao add column p50_arquivo oid;
alter table certidao add column p50_diasvalidade integer DEFAULT 0;

alter table numpref add column k03_diasvalidadecertidao integer;
alter table numpref add column k03_diasreemissaocertidao integer;

update numpref 
   set k03_diasvalidadecertidao  = x.diasvenccertidao, 
       k03_diasreemissaocertidao = 10 
  from ( select codigo, 
  						  coalesce( w13_diasvenccertidao, 0 ) as diasvenccertidao 
  				 from db_config 
  				 			left join configdbpref on codigo = w13_instit ) as x 
 where x.codigo = k03_instit;

alter table configdbpref drop column w13_diasvenccertidao;
/**
 * TIME A - FIM 31037
 */
/**
 * TIME A
 * TAREFA #76943
 */
ALTER TABLE paritbi ADD COLUMN it24_taxabancaria float8 default 0;
/**
 * TIME A - FIM 76943
 */


/**
 * TIME A
 * TAREFA #80635
 */
alter table rhpesdoc add rh16_emissao date;


/**
 * TIME A
 * TAREFA #79689
 */
alter table sepultamentos add cm01_observacoes text;
/**
 * TIME A - FIM #79689
 */

/**
 * TIME A
 * TAREFA #73412
 */

CREATE SEQUENCE localidaderural_j137_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE localidaderural(
j137_sequencial		int4 NOT NULL default 0,
j137_descricao		varchar(100) NOT NULL ,
j137_valorminimo		float8 NOT NULL default 0,
j137_valormaximo		float8 default 0,
CONSTRAINT localidaderural_sequ_pk PRIMARY KEY (j137_sequencial));

CREATE SEQUENCE itbilocalidaderural_it33_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE itbilocalidaderural(
it33_sequencial		int4 NOT NULL default 0,
it33_guia		int8 NOT NULL default 0,
it33_localidaderural		int4 default 0,
CONSTRAINT itbilocalidaderural_sequ_pk PRIMARY KEY (it33_sequencial));

ALTER TABLE itbilocalidaderural
ADD CONSTRAINT itbilocalidaderural_guia_fk FOREIGN KEY (it33_guia)
REFERENCES itbi;

ALTER TABLE itbilocalidaderural
ADD CONSTRAINT itbilocalidaderural_localidaderural_fk FOREIGN KEY (it33_localidaderural)
REFERENCES localidaderural;

CREATE  INDEX itbilocalidaderural_localidaderural_in ON itbilocalidaderural(it33_localidaderural);

CREATE  INDEX itbilocalidaderural_guia_in ON itbilocalidaderural(it33_guia);

CREATE SEQUENCE caractercaracteristica_db143_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE caractercaracteristica(
db143_sequencial		int4 NOT NULL default 0,
db143_caracteristica		int8 NOT NULL default 0,
db143_caracter		int4 default 0,
CONSTRAINT caractercaracteristica_sequ_pk PRIMARY KEY (db143_sequencial));

ALTER TABLE caractercaracteristica
ADD CONSTRAINT caractercaracteristica_caracteristica_fk FOREIGN KEY (db143_caracteristica)
REFERENCES caracteristica;

ALTER TABLE caractercaracteristica
ADD CONSTRAINT caractercaracteristica_caracter_fk FOREIGN KEY (db143_caracter)
REFERENCES caracter;

CREATE  INDEX caractercarcteristica_caracter_in ON caractercaracteristica(db143_caracter);
CREATE  INDEX caractercarcteristica_caracteristica_in ON caractercaracteristica(db143_caracteristica);


/**/
CREATE SEQUENCE configuracaogrupocaracteristicas_db144_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE configuracaogrupocaracteristicas(
db144_sequencial		int4 NOT NULL default 0,
db144_tipoutilizacaoiptu		int4  default 0,
db144_tipoutilizacaoitbiurbano		int4  default 0,
db144_utilizacaoitbirural		int4 default 0,
db144_tipoutilizacaoitbirural		int4 default 0,
CONSTRAINT configuracaogrupocaracteristicas_sequ_pk PRIMARY KEY (db144_sequencial));


ALTER TABLE configuracaogrupocaracteristicas
ADD CONSTRAINT configuracaogrupocaracteristicas_tipoutilizacaoiptu_fk FOREIGN KEY (db144_tipoutilizacaoiptu)
REFERENCES cargrup;

ALTER TABLE configuracaogrupocaracteristicas
ADD CONSTRAINT configuracaogrupocaracteristicas_tipoutilizacaoitbiurbano_fk FOREIGN KEY (db144_tipoutilizacaoitbiurbano)
REFERENCES cargrup;

ALTER TABLE configuracaogrupocaracteristicas
ADD CONSTRAINT configuracaogrupocaracteristicas_utilizacaoitbirural_fk FOREIGN KEY (db144_utilizacaoitbirural)
REFERENCES cargrup;

ALTER TABLE configuracaogrupocaracteristicas
ADD CONSTRAINT configuracaogrupocaracteristicas_tipoutilizacaoitbirural_fk FOREIGN KEY (db144_tipoutilizacaoitbirural)
REFERENCES cargrup;

CREATE  INDEX configuracaogrupocaracteristicas_tipoutilizacaoitbirural_in ON configuracaogrupocaracteristicas(db144_tipoutilizacaoitbirural);

CREATE  INDEX configuracaogrupocaracteristicas_utilizacaoitbirural_in ON configuracaogrupocaracteristicas(db144_utilizacaoitbirural);

CREATE  INDEX configuracaogrupocaracteristicas_tipoutilizacaoitbiurbano_in ON configuracaogrupocaracteristicas(db144_tipoutilizacaoitbiurbano);

CREATE  INDEX configuracaogrupocaracteristicas_tipoutilizacaoiptu_in ON configuracaogrupocaracteristicas(db144_tipoutilizacaoiptu);

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B INICIO #timeb
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * TAREFA #78473
 */

ALTER TABLE acordoposicao add column ac26_numeroaditamento varchar(20);

/**
 * TAREFA #79848
 */
CREATE SEQUENCE bancoshistmovexcecao_k166_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE bancoshistmovexcecao(
k166_sequencial   int4 NOT NULL default 0,
k166_bancoshistmov    int4 default 0,
CONSTRAINT bancoshistmovexcecao_sequ_pk PRIMARY KEY (k166_sequencial));

ALTER TABLE bancoshistmovexcecao
ADD CONSTRAINT bancoshistmovexcecao_bancoshistmov_fk FOREIGN KEY (k166_bancoshistmov)
REFERENCES bancoshistmov;

CREATE INDEX bancoshistmovexcecao_bancoshistmov_in ON bancoshistmovexcecao(k166_bancoshistmov);

/**
 * tarefa #80134
 */

CREATE SEQUENCE bensdispensatombamento_e139_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE bensdispensatombamento(
e139_sequencial   int4 NOT NULL default 0,
e139_empnotaitem    int4 NOT NULL default 0,
e139_matestoqueitem   int8 NOT NULL default 0,
e139_justificativa    text ,
CONSTRAINT bensdispensatombamento_sequ_pk PRIMARY KEY (e139_sequencial));

ALTER TABLE bensdispensatombamento
ADD CONSTRAINT bensdispensatombamento_empnotaitem_fk FOREIGN KEY (e139_empnotaitem)
REFERENCES empnotaitem;

ALTER TABLE bensdispensatombamento
ADD CONSTRAINT bensdispensatombamento_matestoqueitem_fk FOREIGN KEY (e139_matestoqueitem)
REFERENCES matestoqueitem;

CREATE INDEX bensdispensatombamento_matstoqueitem_in ON bensdispensatombamento(e139_matestoqueitem);
CREATE INDEX bensdispensatombamento_empnotaitem_in ON bensdispensatombamento(e139_empnotaitem);


/**
 * TAREFA 78890 - DEVOLUCAO
 */

CREATE SEQUENCE emppresta_e45_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

ALTER TABLE emppresta ADD COLUMN e45_codmov int4;
ALTER TABLE emppresta ADD COLUMN e45_sequencial int4;
ALTER TABLE empprestaitem ADD COLUMN e46_emppresta int4;

ALTER TABLE emppresta
ADD CONSTRAINT empagemov_codmov_fk FOREIGN KEY (e45_codmov)
REFERENCES empagemov;

CREATE UNIQUE INDEX emppresta_numemp_codmov_in ON emppresta(e45_numemp,e45_codmov);

update emppresta set e45_sequencial = nextval('emppresta_e45_sequencial_seq');
update empprestaitem set e46_emppresta = (select e45_sequencial from emppresta where e45_numemp = e46_numemp);
update emppresta set e45_codmov = (select e81_codmov from empagemov where e81_numemp = e45_numemp limit 1);

alter table emppresta drop constraint emppresta_nume_pk cascade;
alter table emppresta add constraint emppresta_sequencial_pk primary key (e45_sequencial);

ALTER TABLE empprestaitem
ADD CONSTRAINT empagemov_emppresta_fk FOREIGN KEY (e46_emppresta)
REFERENCES emppresta;

/*
 * vinculo emppresta recibo
 */
CREATE SEQUENCE empprestarecibo_e170_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empprestarecibo(
e170_sequencial  int4 NOT NULL default 0,
e170_numpre    int4 NOT NULL default 0,
e170_numpar    int4 NOT NULL default 0,
e170_emppresta  int4 default 0,
CONSTRAINT empprestarecibo_sequ_pk PRIMARY KEY (e170_sequencial));

ALTER TABLE empprestarecibo
ADD CONSTRAINT empprestarecibo_emppresta_fk FOREIGN KEY (e170_emppresta)
REFERENCES emppresta;

CREATE  INDEX empprestarecibo_emppresta_in ON empprestarecibo(e170_emppresta);



/*
 * criando historico. setado numero 11000 pois virificado em outros clientes
 *  existem numero altos ja, e nao é DUMP.
 */
insert into histcalc values(11000, 'DEVOL. ADIANTAMENTO', null);

/**
 * TIME B FIM #timeb
 */


/**
 * KETTLE
 */

/**
 * TAREFA 77786
 */
drop index if exists conplanoreduz_instit_anousu_codcon_in;

create index conplanoreduz_instit_anousu_codcon_in on conplanoreduz (c61_instit, c61_anousu, c61_codcon);


/**
 * INFRAESTRUTURA
 */

-- Indices GIN para campos ARRAY
CREATE INDEX db_auditoria_mudancas_nome_campo_in ON configuracoes.db_auditoria USING GIN (((mudancas).nome_campo));

SELECT fc_executa_ddl('CREATE INDEX '||table_name||'_mudancas_nome_campo_in ON '||table_schema||'.'||table_name||' USING GIN (((mudancas).nome_campo));')
  FROM information_schema.tables
 WHERE table_schema = 'configuracoes'
   AND table_name ~ '^db_auditoria_[0-9]{6}_[0-9]{1}'
   AND table_type = 'BASE TABLE';
