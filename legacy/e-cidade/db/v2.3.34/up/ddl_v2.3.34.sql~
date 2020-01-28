------
-- [FINANCEIRO - INICIO]
------

-- 97342
alter table empprestatip add column e44_naturezaevento int4 default 1;
alter table emppresta add column e45_processoadministrativo varchar(20);
alter table emppresta add column e45_datalimiteaplicacao date;
------
-- [FINANCEIRO - FIM]
------

------
-- [FOLHA - INICIO]
------
CREATE SEQUENCE rhreajusteparidade_rh148_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE rhreajusteparidade(
rh148_sequencial    int4 NOT NULL default 0,
rh148_descricao   varchar(50) ,
CONSTRAINT rhreajusteparidade_sequ_pk PRIMARY KEY (rh148_sequencial));

INSERT INTO rhreajusteparidade values (0, null);
INSERT INTO rhreajusteparidade values (nextval('rhreajusteparidade_rh148_sequencial_seq'), 'Real');
INSERT INTO rhreajusteparidade values (nextval('rhreajusteparidade_rh148_sequencial_seq'), 'Paridade');



-- MIGRAÇÃO
DROP TABLE IF EXISTS w_migracao_reajusteparidade CASCADE;

CREATE TEMP TABLE w_migracao_reajusteparidade as SELECT distinct
        on (rh01_regist)
         rh01_regist,
         rh01_instit,
         rh01_reajusteparidade,
         rh30_vinculo,
         rh02_anousu,
         rh02_mesusu,
         max(rh02_seqpes) as seqpes
    from rhpessoal
    inner join rhpessoalmov on rh02_regist = rh01_regist
    inner join rhregime on rh30_codreg = rh02_codreg
    group by rh01_regist,rh30_vinculo, rh02_anousu,rh02_mesusu
    order by rh01_regist,
             rh02_anousu desc,
             rh02_mesusu desc;

alter table rhpessoal drop column rh01_reajusteparidade;
alter table rhpessoal add COLUMN rh01_reajusteparidade int4 default 0;

ALTER TABLE rhpessoal
ADD CONSTRAINT rhpessoal_reajusteparidade_fk FOREIGN KEY (rh01_reajusteparidade)
REFERENCES rhreajusteparidade;

UPDATE rhpessoal set rh01_reajusteparidade = (select  CASE WHEN rh01_reajusteparidade = false then 0
                                                           WHEN rh01_reajusteparidade = true  then 2
                                                       END
                                                from w_migracao_reajusteparidade
                                               where rhpessoal.rh01_regist = w_migracao_reajusteparidade.rh01_regist);

-- UPDATE APENAS PARA GUAÍBA
/**
UPDATE rhpessoal set rh01_reajusteparidade = 2 from w_migracao_reajusteparidade
                                               where w_migracao_reajusteparidade.rh30_vinculo = 'A' and rhpessoal.rh01_regist = w_migracao_reajusteparidade.rh01_regist
                                                                                                    and w_migracao_reajusteparidade.rh01_instit = 3;
**/

alter table rhpespadrao add column rh03_padraoprev varchar(10);

-- Comparativo de Férias
alter table cfpess add column r11_compararferias bool default 'f';
alter table cfpess add column r11_baseferias  varchar(4);
alter table cfpess add column r11_basesalario varchar(4);

-- Campo para vinculação de instituição à fundamentação legal
ALTER TABLE rhfundamentacaolegal ADD COLUMN rh137_instituicao int4 DEFAULT 1;
ALTER TABLE rhfundamentacaolegal ADD CONSTRAINT rhfundamentacaolegal_instituicao_fk FOREIGN KEY (rh137_instituicao) REFERENCES db_config;

-- MIGRAÇÃO FUNDAMENTAÇÃO LEGAL
-- Verifica as novas instituições e cria uma tabela temporaria
CREATE TEMP TABLE w_migracao_fundamentacao_legal_rubrica AS
SELECT DISTINCT rh137_tipodocumentacao,
                rh137_numero,
                rh137_datainicio,
                rh137_datafim,
                rh137_descricao,
                rh27_instit AS rh137_instituicao
  FROM rhrubricas
  INNER JOIN rhfundamentacaolegal
    ON rh27_rhfundamentacaolegal = rh137_sequencial
 WHERE rh27_instit > 1;

-- Cria as novas fundamentações legais
INSERT INTO rhfundamentacaolegal
  SELECT nextval('rhfundamentacaolegal_rh137_sequencial_seq'),
         rh137_tipodocumentacao,
         rh137_numero,
         rh137_datainicio,
         rh137_datafim,
         rh137_descricao,
         rh137_instituicao
    FROM w_migracao_fundamentacao_legal_rubrica;

-- Verifica qual é a nova sequencial
CREATE TEMP TABLE w_migracao_fundamentacao_legal_sequencial AS
  SELECT new.rh137_sequencial  AS nova,
         old.rh137_sequencial  AS antiga,
         new.rh137_instituicao AS instituicao
    FROM rhfundamentacaolegal new
         INNER JOIN rhfundamentacaolegal old
            ON old.rh137_instituicao = 1
           AND old.rh137_descricao   = new.rh137_descricao
   WHERE new.rh137_instituicao > 1;

-- Atualiza a sequencial da rubrica para a sua nova fundamentacao legal
UPDATE rhrubricas SET rh27_rhfundamentacaolegal = fundamentacao_legal.nova
  FROM (
    SELECT *
      FROM w_migracao_fundamentacao_legal_sequencial
  ) AS fundamentacao_legal
 WHERE rh27_instit               = fundamentacao_legal.instituicao
   AND rh27_rhfundamentacaolegal = fundamentacao_legal.antiga;

-- Limpa as tabelas temporarias
DROP TABLE IF EXISTS w_migracao_fundamentacao_legal_rubrica;
DROP TABLE IF EXISTS w_migracao_fundamentacao_legal_sequencial;

-- Retira o padrão da coluna fundamentação legal
ALTER TABLE rhrubricas
ALTER COLUMN rh27_rhfundamentacaolegal
SET DEFAULT NULL;

-- Remove o vínculo das rubricas com a fundamentação legal 0
UPDATE rhrubricas
SET rh27_rhfundamentacaolegal   = NULL
WHERE rh27_rhfundamentacaolegal = 0;

-- Cria fundamentação legal default de 'Migração'
INSERT INTO rhfundamentacaolegal
  SELECT nextval('rhfundamentacaolegal_rh137_sequencial_seq'), 6, 123, current_date, null, 'Migração', 1
   WHERE NOT EXISTS (
     SELECT 1
       FROM rhfundamentacaolegal
      WHERE rh137_instituicao = 1
        AND rh137_descricao   = 'Migração'
   );

-- Cria fundamentações legais de 'Migração' à partir das rubricas com vinculos inválidos com a fundamentação legal
INSERT INTO rhfundamentacaolegal
  SELECT DISTINCT ON (rh27_instit)
         nextval('rhfundamentacaolegal_rh137_sequencial_seq'),
         6, 123, current_date, null, 'Migração', rh27_instit
    FROM rhrubricas WHERE rh27_instit > 1
                      AND rh27_rhfundamentacaolegal NOT IN (
      SELECT rh137_sequencial
        FROM rhfundamentacaolegal
    );

-- Migra as rubricas com vinculos inválidos com a fundamentação legal pra fundamentação legal 'Migração'
UPDATE rhrubricas
SET rh27_rhfundamentacaolegal = migracao.fundamentacaolegal
FROM (
  SELECT rh27_rubric AS rubrica,
         rh27_instit AS instituicao,
         (
           SELECT rh137_sequencial
             FROM rhfundamentacaolegal
            WHERE rh137_descricao   = 'Migração'
              AND rh137_instituicao = rh27_instit
         ) AS fundamentacaolegal
    FROM rhrubricas
   WHERE rh27_rhfundamentacaolegal NOT IN (
      SELECT rh137_sequencial
        FROM rhfundamentacaolegal
   )
 ) AS migracao
WHERE rh27_rubric = migracao.rubrica
  AND rh27_instit = migracao.instituicao;

-- Vinculo da tabela rhrubricas com a rhfundamentacaolegal
ALTER TABLE rhrubricas
DROP CONSTRAINT IF EXISTS rhrubricas_rhfundamentacaolegal_fk;

ALTER TABLE rhrubricas
ADD CONSTRAINT rhrubricas_rhfundamentacaolegal_fk FOREIGN KEY (rh27_rhfundamentacaolegal)
REFERENCES rhfundamentacaolegal;

-- Limpa paramêtros da DIRF
DELETE FROM rhdirfparametros WHERE rh132_anobase IN (2010, 2011, 2012, 2013, 2014);

-- Adiciona os paramêtros da DIRF ano base 2010
INSERT INTO rhdirfparametros
     SELECT nextval('rhdirfparametros_rh132_sequencial_seq'), 2010, 22487.25, '6E0E0AF';

-- Adiciona os paramêtros da DIRF ano base 2011
INSERT INTO rhdirfparametros
     SELECT nextval('rhdirfparametros_rh132_sequencial_seq'), 2011, 23499.15, '1A4MA1R';

-- Adiciona os paramêtros da DIRF ano base 2012
INSERT INTO rhdirfparametros
     SELECT nextval('rhdirfparametros_rh132_sequencial_seq'), 2012, 24556.65, '7C2DE7J';

-- Adiciona os paramêtros da DIRF ano base 2013
INSERT INTO rhdirfparametros
     SELECT nextval('rhdirfparametros_rh132_sequencial_seq'), 2013, 25661.70, 'F8UCL6S';

-- Adiciona os paramêtros da DIRF ano base 2014
INSERT INTO rhdirfparametros
     SELECT nextval('rhdirfparametros_rh132_sequencial_seq'), 2014, 26816.55, 'M1LB5V2';

/**
 * Criação da Tabela rhpreponto
 */
CREATE TABLE rhpreponto(
rh149_instit    int4 NOT NULL default 0,
rh149_regist    int4 NOT NULL default 0,
rh149_rubric    varchar(20) NOT NULL ,
rh149_valor   float4 NOT NULL default 0,
rh149_quantidade    int4 NOT NULL default 0,
rh149_tipofolha   int4 NOT NULL default 0);

ALTER TABLE rhpreponto
ADD CONSTRAINT rhpreponto_instit_fk FOREIGN KEY (rh149_instit)
REFERENCES db_config;

ALTER TABLE rhpreponto
ADD CONSTRAINT rhpreponto_tipofolha_fk FOREIGN KEY (rh149_tipofolha)
REFERENCES rhtipofolha;

ALTER TABLE rhpreponto
ADD CONSTRAINT rhpreponto_regist_fk FOREIGN KEY (rh149_regist)
REFERENCES rhpessoal;


------
-- [FOLHA - FIM]
------

------
-- [TRIBUTARIO - INICIO]
------
alter table vistexec  alter COLUMN y11_compl type varchar(100);
alter table vistlocal alter COLUMN y10_compl type varchar(100);

insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo) values (166, 'fc_calculoiptu_riopardo_2015',         'calculo de iptu',            '.', '0', 'calculoiptu_riopardo_2015.sql');
insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo) values (167, 'fc_iptu_taxacoletalixo_riopardo_2015', 'calculo da taxa de limpeza', 'a', '0', 'iptu_taxalimpeza_riopardo_2015.sql');

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 1, 'iMatricula',    'int4',    0, 0, '0',     'MATRICULA');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 2, 'iAnousu',       'int4',    0, 0, '0',     'ANO DE CALCULO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 3, 'bGerafinanc',   'bool',    0, 0, '0',     'SE GERA FINANCEIRO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 4, 'bAtualizap',    'bool',    0, 0, '0',     'ATUALIZA PARCELAS');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 5, 'bNovonumpre',   'bool',    0, 0, '0',     'SE GERA UM NOVO NUMPRE');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 6, 'bCalculogeral', 'bool',    0, 0, '0',     'SE CALCULO GERAL');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 7, 'bDemo',         'bool',    0, 0, '0',     'SE E DEMONSTRATIVO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 8, 'iParcelaini',   'int4',    0, 0, '0',     'PARCELA INICIAL');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 166, 9, 'iParcelafim',   'int4',    0, 0, '0',     'PARCELA FINAL');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 1, 'iReceita',      'int4',    0, 0, '0',     'RECEITA');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 2, 'iAliquota',     'numeric', 0, 0, '0',     'ALIQUOTA');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 3, 'iHistCalc',     'int4',    0, 0, '0',     'HISTORICO DE CALCULO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 4, 'iPercIsen',     'numeric', 0, 0, '0',     'PERCENTUAL DE ISENCAO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 5, 'nValpar',       'numeric', 0, 0, '0',     'VALOR POR PARAMETRO');
insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao) values ( nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 167, 6, 'bRaise',        'bool',    0, 0, 'FALSE', 'DEBUG');

/**
 * Funções de calculo de arroio
 */
insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo)
     select 163, 'fc_calculoiptu_arr_2015', 'calculo de iptu', '.', '0', 'calculoiptu_arr_2015.sql'
      where not exists (select 1 from db_sysfuncoes where codfuncao = 163);

insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo)
     select 164, 'fc_iptu_taxalimpeza_arr_2015', 'calculo da taxa de limpeza', 'a', '0', 'iptu_taxalimpeza_arr_2015.sql'
      where not exists (select 1 from db_sysfuncoes where codfuncao = 164);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 1, 'iReceita',      'int4',    0, 0, '0',     'RECEITA'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 1);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 2, 'iAliquota',     'numeric', 0, 0, '0',     'ALIQUOTA'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 2);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 3, 'iHistCalc',     'int4',    0, 0, '0',     'HISTORICO DE CALCULO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 3);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 4, 'iPercIsen',     'numeric', 0, 0, '0',     'PERCENTUAL DE ISENCAO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 4);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 5, 'nValpar',       'numeric', 0, 0, '0',     'VALOR POR PARAMETRO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 5);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 164, 6, 'bRaise',        'bool',    0, 0, 'FALSE', 'DEBUG'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 164 and db42_ordem = 6);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 1, 'iMatricula',    'int4',    0, 0, '0',     'MATRICULA'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 1);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 2, 'iAnousu',       'int4',    0, 0, '0',     'ANO DE CALCULO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 2);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 3, 'bGerafinanc',   'bool',    0, 0, '0',     'SE GERA FINANCEIRO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 3);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 4, 'bAtualizap',    'bool',    0, 0, '0',     'ATUALIZA PARCELAS'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 4);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 5, 'bNovonumpre',   'bool',    0, 0, '0',     'SE GERA UM NOVO NUMPRE'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 5);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 6, 'bCalculogeral', 'bool',    0, 0, '0',     'SE CALCULO GERAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 6);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 7, 'bDemo',         'bool',    0, 0, '0',     'SE E DEMONSTRATIVO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 7);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 8, 'iParcelaini',   'int4',    0, 0, '0',     'PARCELA INICIAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 8);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 163, 9, 'iParcelafim',   'int4',    0, 0, '0',     'PARCELA FINAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 163 and db42_ordem = 9);

alter table empreendimento add am05_areatotal float8;

--Tabela caractercaracter
insert into iptutabelas ( j121_sequencial, j121_codarq )
     select nextval('iptutabelas_j121_sequencial_seq'), 3698
      where not exists ( select 1 from iptutabelas where j121_codarq = 3698 );

------
-- [TRIBUTARIO - FIM]
------

------
-- [TIME C - INICIO]
------

alter table sau_triagemavulsa ALTER COLUMN s152_i_pressaosistolica  DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_pressaodiastolica DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_cintura           DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_n_peso              DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_altura            DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_glicemia          DROP not null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_pressaosistolica  SET DEFAULT null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_pressaodiastolica SET DEFAULT null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_cintura           SET DEFAULT null;
alter table sau_triagemavulsa ALTER COLUMN s152_n_peso              SET DEFAULT null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_altura            SET DEFAULT null;
alter table sau_triagemavulsa ALTER COLUMN s152_i_glicemia          SET DEFAULT null;

/**
 * Migracao cbos
 */
create temp table profissional_vinculos_unidade_cbos as
       select sd04_i_unidade, sd04_i_medico,
              min(fa54_i_codigo) as primeiro_cbos,
              array_accum(sd04_i_codigo) as vinculo_unidade,
              array_accum(fa54_i_codigo) as vinculo_cbos
         from unidademedicos
        inner join far_cbosprofissional on fa54_i_unidademedico = sd04_i_codigo
        group by sd04_i_unidade, sd04_i_medico;

update sau_triagemavulsa set s152_i_cbosprofissional = primeiro_cbos
  from profissional_vinculos_unidade_cbos
 where s152_i_cbosprofissional = any (vinculo_cbos);

delete from far_cbosprofissional
 using profissional_vinculos_unidade_cbos
 where fa54_i_unidademedico = any(vinculo_unidade)
   and fa54_i_codigo <> primeiro_cbos;

drop table profissional_vinculos_unidade_cbos;

-- TAREFA 104640



-- criando  sequences
create sequence motivoalta_sd01_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
create sequence prontuariosmotivoalta_sd25_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;

create table motivoalta(
sd01_codigo int4 not null default 0,
sd01_codigosus int4 not null default 0,
sd01_descricao varchar(80) not null ,
sd01_finalizaatendimento bool default 'false',
constraint motivoalta_codi_pk primary key (sd01_codigo));

create table prontuariosmotivoalta(
sd25_codigo int4 not null default 0,
sd25_motivoalta int4 not null default 0,
sd25_prontuarios int4 not null default 0,
sd25_data date not null default null,
sd25_hora varchar(5) not null ,
sd25_db_usuarios int4 default 0,
constraint prontuariosmotivoalta_codi_pk primary key (sd25_codigo));

alter table prontuariosmotivoalta add constraint prontuariosmotivoalta_motivoalta_fk foreign key (sd25_motivoalta) references motivoalta;
alter table prontuariosmotivoalta add constraint prontuariosmotivoalta_prontuarios_fk foreign key (sd25_prontuarios) references prontuarios;
alter table prontuariosmotivoalta add constraint prontuariosmotivoalta_usuarios_fk foreign key (sd25_db_usuarios) references db_usuarios;
create index prontuariosmotivoalta_db_usuarios_in on prontuariosmotivoalta(sd25_db_usuarios);
create index prontuariosmotivoalta_prontuarios_in on prontuariosmotivoalta(sd25_prontuarios);
create index prontuariosmotivoalta_motivoalta_in on prontuariosmotivoalta(sd25_motivoalta);

insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 10, 'DESISTÊNCIA', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 11, 'ALTA CURADO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 12, 'ALTA MELHORADO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 14, 'ALTA A PEDIDO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 15, 'ALTA COM PREVISÃO DE RETORNO P/ACOMP.PAC', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 16, 'ALTA POR EVASÃO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 18, 'ALTA POR OUTROS MOTIVOS', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 31, 'TRANSFERIDO P/OUTRO ESTABELECIMENTO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 41, 'ÓBITO-C/DECL.ÓBITO FORNEC.MED.ASSISTENTE', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 42, 'ÓBITO-C/DECL.DE ÓBITO FORNECIDA PELO IML', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 43, 'ÓBITO-C/DECL.DE ÓBITO FORNECIDA PELO SVO', true);
insert into motivoalta values ( nextval('motivoalta_sd01_codigo_seq'), 51, 'ENCERRAMENTO ADMINSTRATIVO', true);


CREATE SEQUENCE classificacaorisco_sd78_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE prontuariosclassificacaorisco_sd101_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE TABLE classificacaorisco(
sd78_codigo    int4 NOT NULL default 0,
sd78_descricao varchar(15) NOT NULL ,
sd78_peso      int4 NOT NULL default 0,
sd78_labelcor  varchar(10) NOT NULL ,
sd78_cor       varchar(7) ,
CONSTRAINT classificacaorisco_codi_pk PRIMARY KEY (sd78_codigo));

CREATE TABLE prontuariosclassificacaorisco(
sd101_codigo             int4 NOT NULL default 0,
sd101_prontuarios        int4 NOT NULL default 0,
sd101_classificacaorisco int4 default 0,
CONSTRAINT prontuariosclassificacaorisco_codi_pk PRIMARY KEY (sd101_codigo));

ALTER TABLE prontuariosclassificacaorisco ADD CONSTRAINT prontuariosclassificacaorisco_classificacaorisco_fk FOREIGN KEY (sd101_classificacaorisco) REFERENCES classificacaorisco;
ALTER TABLE prontuariosclassificacaorisco ADD CONSTRAINT prontuariosclassificacaorisco_prontuarios_fk FOREIGN KEY (sd101_prontuarios) REFERENCES prontuarios;
CREATE UNIQUE INDEX prontuariosclassificacaorisco_prontuarios_classificacaorisco_in ON prontuariosclassificacaorisco(sd101_prontuarios,sd101_classificacaorisco);

insert into classificacaorisco values ( nextval('classificacaorisco_sd78_codigo_seq'), 'EMERGÊNCIA   ', 5, 'VERMELHO', '#EC3136');
insert into classificacaorisco values ( nextval('classificacaorisco_sd78_codigo_seq'), 'MUITO URGENTE', 4, 'LARANJA ', '#F68634');
insert into classificacaorisco values ( nextval('classificacaorisco_sd78_codigo_seq'), 'URGENTE      ', 3, 'AMARELO ', '#FAD902');
insert into classificacaorisco values ( nextval('classificacaorisco_sd78_codigo_seq'), 'POUCO URGENTE', 2, 'VERDE   ', '#01A85A');
insert into classificacaorisco values ( nextval('classificacaorisco_sd78_codigo_seq'), 'NÃO URGENTE  ', 1, 'AZUL    ', '#0095DF');

------
-- [TIME C - FIM]
------