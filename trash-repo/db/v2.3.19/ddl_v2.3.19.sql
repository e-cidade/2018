/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */
 
 -- Tarefa 53953 / Reajuste Salarial
 alter table tipoasse add column h12_tiporeajuste int4 default 0;

 -- Tarefa 81883 / Tabelas do PCASP
create table w_81883_bkp as select * from rhempenhoelementopcasp;
create table w_81883_excluidos as 
          select * from rhempenhoelementopcasp 
           where not exists (select * from rhelementoemp where rh119_codeledef = rh38_codele) 
              or not exists (select * from rhelementoemp where rh119_codelenov = rh38_codele);

delete from rhempenhoelementopcasp where rh119_sequencial in (select rh119_sequencial from w_81883_excluidos);

alter table rhempenhoelementopcasp add column rh119_rhelementoempdef int4,
                                   add column rh119_rhelementoempnov int4;

update rhempenhoelementopcasp 
   set rh119_rhelementoempdef = ( select rh38_seq 
                                    from rhelementoemp 
                                   where rh38_codele = rh119_codeledef 
                                     and rh38_anousu = (select max(rh38_anousu) from rhelementoemp) )
      ,rh119_rhelementoempnov = ( select rh38_seq 
                                    from rhelementoemp 
                                   where rh38_codele = rh119_codelenov 
                                     and rh38_anousu = (select max(rh38_anousu) from rhelementoemp) );

alter table rhempenhoelementopcasp drop column rh119_codeledef,
                                   drop column rh119_codelenov,
                                   alter column rh119_rhelementoempdef set not null,
                                   alter column rh119_rhelementoempnov set not null;

ALTER TABLE rhempenhoelementopcasp
ADD CONSTRAINT rhempenhoelementopcasp_rhelementoempdef_fk FOREIGN KEY (rh119_rhelementoempdef)
REFERENCES rhelementoemp;

ALTER TABLE rhempenhoelementopcasp
ADD CONSTRAINT rhempenhoelementopcasp_rhelementoempnov_fk FOREIGN KEY (rh119_rhelementoempnov)
REFERENCES rhelementoemp;


/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A FIM
 * --------------------------------------------------------------------------------------------------------------------
 */


/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */
CREATE SEQUENCE posicaoestoqueprocessamento_m05_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE posicaoestoque_m06_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE posicaoestoquematestoqueinimei_m07_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE TABLE posicaoestoqueprocessamento(
m05_sequencial   int4 NOT NULL default 0,
m05_usuario      int4 NOT NULL default 0,
m05_data         date NOT NULL default null,
m05_instit       int4 default 0,
CONSTRAINT posicaoestoqueprocessamento_sequ_pk PRIMARY KEY (m05_sequencial));

CREATE TABLE posicaoestoque(
m06_sequencial   int4 NOT NULL default 0,
m06_posicaoestoqueprocessamento   int4 NOT NULL default 0,
m06_matestoque   int4 NOT NULL default 0,
m06_quantidade   numeric NOT NULL ,
m06_valor   numeric NOT NULL ,
m06_precomedio   numeric,
CONSTRAINT posicaoestoque_sequ_pk PRIMARY KEY (m06_sequencial));

CREATE TABLE posicaoestoquematestoqueinimei(
m07_sequencial   int4 NOT NULL default 0,
m07_posicaoestoque   int4 NOT NULL default 0,
m07_matestoqueinimei   int4 default 0,
CONSTRAINT posicaoestoquematestoqueinimei_sequ_pk PRIMARY KEY (m07_sequencial));


ALTER TABLE posicaoestoqueprocessamento
ADD CONSTRAINT posicaoestoqueprocessamento_usuario_fk FOREIGN KEY (m05_usuario)
REFERENCES db_usuarios;

ALTER TABLE posicaoestoqueprocessamento
ADD CONSTRAINT posicaoestoqueprocessamento_instit_fk FOREIGN KEY (m05_instit)
REFERENCES db_config;

ALTER TABLE posicaoestoque
ADD CONSTRAINT posicaoestoque_matestoque_fk FOREIGN KEY (m06_matestoque)
REFERENCES matestoque;

ALTER TABLE posicaoestoque
ADD CONSTRAINT posicaoestoque_posicaoestoqueprocessamento_fk FOREIGN KEY (m06_posicaoestoqueprocessamento)
REFERENCES posicaoestoqueprocessamento;

ALTER TABLE posicaoestoquematestoqueinimei
ADD CONSTRAINT posicaoestoquematestoqueinimei_matestoqueinimei_fk FOREIGN KEY (m07_matestoqueinimei)
REFERENCES matestoqueinimei;

ALTER TABLE posicaoestoquematestoqueinimei
ADD CONSTRAINT posicaoestoquematestoqueinimei_posicaoestoque_fk FOREIGN KEY (m07_posicaoestoque)
REFERENCES posicaoestoque;

CREATE  INDEX posicaoestoqueprocessamento_sequencial_in ON posicaoestoqueprocessamento(m05_sequencial);
CREATE  INDEX posicaoestoquematestoqueinimei_posicaoestoque_in ON posicaoestoquematestoqueinimei(m07_posicaoestoque);
CREATE UNIQUE INDEX posicaoestoque_matestoque_processamento_in ON posicaoestoque(m06_posicaoestoqueprocessamento,m06_matestoque);



-- contranslrelemento  INICIO
CREATE SEQUENCE contranslrelemento_c114_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
-- TABELAS E ESTRUTURA
-- Módulo: contabilidade
CREATE TABLE contranslrelemento(
c114_sequencial   int4 NOT NULL default 0,
c114_contranslr   int4 NOT NULL default 0,
c114_elemento   varchar(15) ,
CONSTRAINT contranslrelemento_sequ_pk PRIMARY KEY (c114_sequencial));
-- CHAVE ESTRANGEIRA
ALTER TABLE contranslrelemento
ADD CONSTRAINT contranslrelemento_contranslr_fk FOREIGN KEY (c114_contranslr)
REFERENCES contranslr;
CREATE  INDEX contranslrelemento_c114_contranslr_in ON contranslrelemento(c114_contranslr);

DROP TABLE contranslanelemento CASCADE;
drop sequence contranslanelemento_c114_sequencial_seq;
---- contranslrelemento  INICIO



CREATE SEQUENCE parametrointegracaopatrimonial_c01_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE parametrointegracaopatrimonial(
c01_sequencial    int4 NOT NULL default 0,
c01_modulo    int4 NOT NULL default 0,
c01_data    date NOT NULL default null,
c01_instit    int4 default 0,
CONSTRAINT parametrointegracaopatrimonial_sequ_pk PRIMARY KEY (c01_sequencial));
ALTER TABLE parametrointegracaopatrimonial
ADD CONSTRAINT parametrointegracaopatrimonial_instit_fk FOREIGN KEY (c01_instit)
REFERENCES db_config;
CREATE UNIQUE INDEX parametrointegracaopatrimonial_c01_instit_in ON parametrointegracaopatrimonial(c01_instit,c01_modulo);




/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B FIM
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

-- Tarefa 80466 - Progressão Parcial (incluir progressão para aluno)
CREATE SEQUENCE progressaoparcialalunodiariofinalorigem_ed107_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE progressaoparcialalunodiariofinalorigem (
  ed107_sequencial int4 NOT NULL default 0,
  ed107_progressaoparcialaluno    int4 NOT NULL default 0,
  ed107_diariofinal int4 default 0,
CONSTRAINT progressaoparcialalunodiariofinalorigem_sequ_pk PRIMARY KEY (ed107_sequencial));

ALTER TABLE progressaoparcialalunodiariofinalorigem ADD CONSTRAINT progressaoparcialalunodiariofinalorigem_progressaoparcialaluno_fk FOREIGN KEY (ed107_progressaoparcialaluno) REFERENCES progressaoparcialaluno;
ALTER TABLE progressaoparcialalunodiariofinalorigem ADD CONSTRAINT progressaoparcialalunodiariofinalorigem_diariofinal_fk FOREIGN KEY (ed107_diariofinal) REFERENCES diariofinal;

CREATE UNIQUE INDEX progressaoparcialalunodiariofinalorigem_diariofinal_in ON progressaoparcialalunodiariofinalorigem(ed107_diariofinal);
CREATE INDEX progressaoparcialalunodiariofinalorigem_progressaoparcialaluno_in ON progressaoparcialalunodiariofinalorigem(ed107_progressaoparcialaluno);

insert into progressaoparcialalunodiariofinalorigem select nextval('progressaoparcialalunodiariofinalorigem_ed107_sequencial_seq'),
                                                           ed114_sequencial, 
                                                           ed114_diariofinal 
                                                      from progressaoparcialaluno;

alter table progressaoparcialaluno ADD COLUMN ed114_ano    int4 default null;
alter table progressaoparcialaluno ADD COLUMN ed114_escola int4 default null;

create TEMPORARY table w_anoprogressaoparcialaluno as select ed114_sequencial, ed52_i_ano, ed38_i_escola
                                                   from progressaoparcialaluno 
                                                  inner join diariofinal on diariofinal.ed74_i_codigo =  progressaoparcialaluno.ed114_diariofinal
                                                  inner join diario      on diario.ed95_i_codigo      = diariofinal.ed74_i_diario
                                                  inner join calendario  on calendario.ed52_i_codigo  = diario.ed95_i_calendario
                                                  inner join calendarioescola on calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo;

update progressaoparcialaluno set ed114_ano    = w_anoprogressaoparcialaluno.ed52_i_ano, 
                                  ed114_escola = w_anoprogressaoparcialaluno.ed38_i_escola
  from w_anoprogressaoparcialaluno
 where progressaoparcialaluno.ed114_sequencial = w_anoprogressaoparcialaluno.ed114_sequencial;

alter table progressaoparcialaluno drop COLUMN ed114_diariofinal;

ALTER TABLE progressaoparcialaluno ADD CONSTRAINT progressaoparcialaluno_escola_fk FOREIGN KEY (ed114_escola)
REFERENCES escola;

CREATE  INDEX progressaoparcialaluno_escola_in ON progressaoparcialaluno(ed114_escola);

alter table progressaoparcialaluno ALTER COLUMN ed114_ano    SET not null;
alter table progressaoparcialaluno ALTER COLUMN ed114_escola SET not null;


ALTER TABLE progressaoparcialaluno ADD ed114_situacaoeducacao int;

update progressaoparcialaluno set ed114_situacaoeducacao = case when ed114_concluida then 3 else 1 end;

ALTER TABLE progressaoparcialaluno
ADD CONSTRAINT progressaoparcialaluno_situacaoeducacao_fk FOREIGN KEY (ed114_situacaoeducacao)
REFERENCES situacaoeducacao;

ALTER TABLE progressaoparcialaluno DROP COLUMN ed114_concluida;

CREATE  INDEX progressaoparcialaluno_situacaoeducacao_in ON progressaoparcialaluno(ed114_situacaoeducacao);

alter table procavaliacao add ed41_numerodisciplinasrecuperacao integer;

CREATE SEQUENCE diarioresultadorecuperacao_ed116_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE diarioresultadorecuperacao(
  ed116_sequencial		int4 NOT NULL default 0,
  ed116_diarioresultado		int8 default 0,
  CONSTRAINT diarioresultadorecuperacao_sequ_pk PRIMARY KEY (ed116_sequencial));


ALTER TABLE diarioresultadorecuperacao
ADD CONSTRAINT diarioresultadorecuperacao_diarioresultado_fk FOREIGN KEY (ed116_diarioresultado)
REFERENCES diarioresultado;


CREATE UNIQUE INDEX diarioresultadorecuperacao_resultado_in ON diarioresultadorecuperacao(ed116_diarioresultado);

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C FIM
 * --------------------------------------------------------------------------------------------------------------------
 */