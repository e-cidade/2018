/**
 * Tributario
 */
select fc_executa_ddl('CREATE SEQUENCE zonassetorvalor_j141_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;');

select fc_executa_ddl('CREATE TABLE zonassetorvalor(
j141_sequencial   int4 NOT NULL  default nextval(\'zonassetorvalor_j141_sequencial_seq\'),
j141_anousu       int4 NOT NULL, j141_zonas        int8 NOT NULL , j141_setor        varchar(4) NOT NULL , j141_valorminimo  float8 NOT NULL ,
j141_valorm2      float8 , CONSTRAINT zonassetorvalor_ae_zona_seto_pk PRIMARY KEY (j141_anousu,j141_zonas,j141_setor) );');

select fc_executa_ddl('ALTER TABLE zonassetorvalor ADD CONSTRAINT zonassetorvalor_zonas_fk FOREIGN KEY (j141_zonas) REFERENCES zonas;');
select fc_executa_ddl('ALTER TABLE zonassetorvalor ADD CONSTRAINT zonassetorvalor_setor_fk FOREIGN KEY (j141_setor) REFERENCES setor;');
select fc_executa_ddl('CREATE UNIQUE INDEX zonas_setor_inc ON zonassetorvalor(j141_anousu,j141_zonas,j141_setor);');
select fc_executa_ddl('insert into iptutabelas values ( nextval(\'iptutabelas_j121_sequencial_seq\'), 3783 );');

--Função de calculo de taquari
insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo)
     select 172, 'fc_calculoiptu_taquari_2015', 'calculo de iptu', '.', '0', 'calculoiptu_taquari_2015.sql'
      where not exists (select 1 from db_sysfuncoes where codfuncao = 172);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 1, 'iMatricula',    'int4',    0, 0, '0',     'MATRICULA'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 1);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 2, 'iAnousu',       'int4',    0, 0, '0',     'ANO DE CALCULO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 2);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 3, 'bGerafinanc',   'bool',    0, 0, '0',     'SE GERA FINANCEIRO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 3);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 4, 'bAtualizap',    'bool',    0, 0, '0',     'ATUALIZA PARCELAS'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 4);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 5, 'bNovonumpre',   'bool',    0, 0, '0',     'SE GERA UM NOVO NUMPRE'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 5);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 6, 'bCalculogeral', 'bool',    0, 0, '0',     'SE CALCULO GERAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 6);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 7, 'bDemo',         'bool',    0, 0, '0',     'SE E DEMONSTRATIVO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 7);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 8, 'iParcelaini',   'int4',    0, 0, '0',     'PARCELA INICIAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 8);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 172, 9, 'iParcelafim',   'int4',    0, 0, '0',     'PARCELA FINAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 172 and db42_ordem = 9);

update iptucadlogcalc SET j62_descr = 'FATOR PROFUNDIDADE NÃO ENCONTRADO. VERIFIQUE A TESTADA E A ÁREA DO LOTE PARA ESTA MATRÍCULA' where j62_codigo = 111;
update iptucadlogcalc SET j62_descr = 'NÃO HÁ VALOR DO METRO QUADRADO CADASTRADO NO AGRUPAMENTO PARA O EXERCÍCIO' where j62_codigo = 110;
update iptucadlogcalc SET j62_descr = 'NÃO HÁ FATOR DE DEPRECIAÇÃO CADASTRADO NO AGRUPAMENTO PARA O EXERCÍCIO' where j62_codigo = 109;

insert into iptucadlogcalc values (113,'LOTE OU VALOR VENAL DO TERRENO NAO ENCONTRADOS', 't');

select fc_executa_ddl('create unique index issconfiguracaogruposervico_issgruposervico_exercicio_in on issconfiguracaogruposervico (q136_issgruposervico, q136_exercicio);');

/**
 * Funções de calculo de jaguarao
 */
insert into db_sysfuncoes (codfuncao, nomefuncao, obsfuncao, corpofuncao, triggerfuncao, nomearquivo)
     select 173, 'fc_calculoiptu_jaguarao_2015', 'calculo de iptu', '.', '0', 'calculoiptu_jaguarao_2015.sql'
      where not exists (select 1 from db_sysfuncoes where codfuncao = 173);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 1, 'iMatricula',    'int4',    0, 0, '0',     'MATRICULA'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 1);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 2, 'iAnousu',       'int4',    0, 0, '0',     'ANO DE CALCULO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 2);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 3, 'bGerafinanc',   'bool',    0, 0, '0',     'SE GERA FINANCEIRO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 3);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 4, 'bAtualizap',    'bool',    0, 0, '0',     'ATUALIZA PARCELAS'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 4);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 5, 'bNovonumpre',   'bool',    0, 0, '0',     'SE GERA UM NOVO NUMPRE'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 5);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 6, 'bCalculogeral', 'bool',    0, 0, '0',     'SE CALCULO GERAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 6);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 7, 'bDemo',         'bool',    0, 0, '0',     'SE E DEMONSTRATIVO'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 7);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 8, 'iParcelaini',   'int4',    0, 0, '0',     'PARCELA INICIAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 8);

insert into db_sysfuncoesparam (db42_sysfuncoesparam, db42_funcao, db42_ordem, db42_nome, db42_tipo, db42_tamanho, db42_precisao, db42_valor_default, db42_descricao)
     select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq'), 173, 9, 'iParcelafim',   'int4',    0, 0, '0',     'PARCELA FINAL'
      where not exists (select 1 from db_sysfuncoesparam where db42_funcao = 173 and db42_ordem = 9);
/**
 * Fim Tributario
 */

------------------------------------------
----------- INICIO TIME FOLHA ------------
------------------------------------------
ALTER TABLE rhpessoalmov add column rh02_abonopermanencia boolean default false;

ALTER TABLE cfpess add column r11_suplementar boolean default false;

--Sequencias para tabelas dos consignados (Consignet)
create sequence rhconsignadomovimento_rh151_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;
create sequence rhconsignadomovimentoservidor_rh152_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;
create sequence rhconsignadomovimentoservidorrubrica_rh153_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;
create sequence rhconsignadomotivo_rh154_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

--Tableas para os consignados (Consignet)
create table rhconsignadomovimento(
    rh151_sequencial    int4 not null default nextval('rhconsignadomovimento_rh151_sequencial_seq'),
    rh151_ano           int4 not null,
    rh151_mes           int4 not null,
    rh151_nomearquivo   varchar(100) not null,
    rh151_instit        int4,
    rh151_relatorio     oid null,
    rh151_processado    boolean default false,
  constraint rhconsignadomovimento_pk primary key (rh151_sequencial)
);
create table rhconsignadomovimentoservidor(
    rh152_sequencial            int4 not null default nextval('rhconsignadomovimentoservidor_rh152_sequencial_seq'),
    rh152_consignadomovimento   int4 not null,
    rh152_regist                varchar(10),
    rh152_nome                  varchar(40),
    rh152_consignadomotivo      int4,
  constraint rhconsignadomovimentoservidor_pk primary key (rh152_sequencial)
);
create table rhconsignadomovimentoservidorrubrica(
    rh153_sequencial                    int4 not null default nextval('rhconsignadomovimentoservidorrubrica_rh153_sequencial_seq'),
    rh153_consignadomovimentoservidor   int4 not null,
    rh153_rubrica                       varchar(4) not null,
    rh153_instit                        int4,
    rh153_valordescontar                varchar(10),
    rh153_valordescontado               varchar(10),
    rh153_parcela                       varchar(3),
    rh153_totalparcelas                 varchar(3),
  constraint rhconsignadomovimentoservidorrubrica_pk primary key (rh153_sequencial)
);
create table rhconsignadomotivo(
    rh154_sequencial   int4 not null default nextval('rhconsignadomotivo_rh154_sequencial_seq'),
    rh154_motivo       varchar(100) not null,
  constraint rhconsignadomotivo_sequ_pk PRIMARY key (rh154_sequencial)
);

--Chaves estrangeiras das tabelas para os consignados (Consignet)
alter table rhconsignadomovimento
  add constraint rh151_instit_fk foreign key (rh151_instit)
references db_config;
alter table rhconsignadomovimentoservidor
  add constraint rh152_consignadomovimento_fk foreign key (rh152_consignadomovimento)
references rhconsignadomovimento;
alter table rhconsignadomovimentoservidor
  ADD constraint rh152_consignadomotivo_fk foreign key (rh152_consignadomotivo)
references rhconsignadomotivo;
alter table rhconsignadomovimentoservidorrubrica
  add constraint rh153_consignadomovimentoservidor_fk foreign key (rh153_consignadomovimentoservidor)
references rhconsignadomovimentoservidor;
alter table rhconsignadomovimentoservidorrubrica
  add constraint rh153_instit_fk foreign key (rh153_instit)
references db_config;

--Indices para tabelas dos consignados (Consignet)
create index rhconsignadomovimento_sequencial_in on rhconsignadomovimento(rh151_sequencial);
create index rhconsignadomotivo_sequencial_in ON rhconsignadomotivo(rh154_sequencial);

-- Preenchimento da tabela de motivos para os consignados (Consignet)
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'FALECIMENTO');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'SERVIDOR NÃO IDENTIFICADO');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'TIPO DE CONTRATO NÃO PERMITE EMPRÉSTIMO');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'MARGEM CONSIGNÁVEL EXCEDIDA');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'NÃO DESCONTADO - OUTROS MOTIVOS');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'SERVIDOR DESLIGADO');
insert into rhconsignadomotivo values (nextval('rhconsignadomotivo_rh154_sequencial_seq'),'SERVIDOR AFASTADO EM LICENÇA SAÚDE');

--- Alteração de Seleções
alter table selecao alter r44_desc1 type text;
alter table selecao alter r44_desc2 type text;

------------------------------------------
------------- FIM TIME FOLHA -------------
------------------------------------------

------------------------------------------
------------------- TIME C ---------------
------------------------------------------

ALTER TABLE tipoausencia add column ed320_licenca boolean default false;
ALTER TABLE sau_config add column s103_obrigarcns boolean default false;


-------------------------------------------------------
------------- TAREFA TIPO HORA INICIO -----------------
-------------------------------------------------------

CREATE SEQUENCE agendaatividade_ed129_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE tipohoratrabalho_ed128_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE agendaatividade(
ed129_codigo int4 NOT NULL  default nextval('agendaatividade_ed129_codigo_seq'),
ed129_tipohoratrabalho int4 NOT NULL ,
ed129_diasemana   int4 NOT NULL ,
ed129_turno   int4 NOT NULL ,
ed129_rechumanoativ   int4 NOT NULL ,
ed129_horainicio    varchar(5) NOT NULL ,
ed129_horafim   varchar(5) ,
CONSTRAINT agendaatividade_codi_pk PRIMARY KEY (ed129_codigo));

CREATE TABLE tipohoratrabalho(
ed128_codigo int4 NOT NULL  default nextval('tipohoratrabalho_ed128_codigo_seq'),
ed128_descricao varchar(70) NOT NULL ,
ed128_abreviatura varchar(10) NOT NULL ,
ed128_tipoefetividade int4 NOT NULL ,
ed128_ativo bool NOT NULL default 'true',
ed128_escola int4 ,
CONSTRAINT tipohoratrabalho_codi_pk PRIMARY KEY (ed128_codigo));

alter table rechumanoativ      add column ed22_ativo boolean default true;
alter table rechumanohoradisp  add column ed33_tipohoratrabalho int4;
alter table relacaotrabalho    add column ed23_tipohoratrabalho int4;
alter table relacaotrabalho    add column ed23_ativo boolean default true;

ALTER TABLE agendaatividade ADD CONSTRAINT agendaatividade_tipohoratrabalho_fk FOREIGN KEY (ed129_tipohoratrabalho) REFERENCES tipohoratrabalho;
ALTER TABLE agendaatividade ADD CONSTRAINT agendaatividade_rechumanoativ_fk FOREIGN KEY (ed129_rechumanoativ) REFERENCES rechumanoativ;
ALTER TABLE agendaatividade ADD CONSTRAINT agendaatividade_diasemana_fk FOREIGN KEY (ed129_diasemana) REFERENCES diasemana;
ALTER TABLE rechumanohoradisp ADD CONSTRAINT rechumanohoradisp_tipohoratrabalho_fk FOREIGN KEY (ed33_tipohoratrabalho) REFERENCES tipohoratrabalho;
ALTER TABLE relacaotrabalho ADD CONSTRAINT relacaotrabalho_tipohoratrabalho_fk FOREIGN KEY (ed23_tipohoratrabalho) REFERENCES tipohoratrabalho;
ALTER TABLE tipohoratrabalho ADD CONSTRAINT tipohoratrabalho_escola_fk FOREIGN KEY (ed128_escola) REFERENCES escola;

CREATE INDEX agendaatividade_diasemana_in ON agendaatividade(ed129_diasemana);
CREATE INDEX agendaatividade_rechumanoativ_in ON agendaatividade(ed129_rechumanoativ);
CREATE INDEX agendaatividade_tipohoratrabalho_in ON agendaatividade(ed129_tipohoratrabalho);
CREATE INDEX relacaotrabalho_tipohoratrabalho_in ON relacaotrabalho(ed23_tipohoratrabalho);
CREATE UNIQUE INDEX tipohoratrabalho_abreviatura_escola_in ON tipohoratrabalho(ed128_abreviatura,ed128_escola);
CREATE UNIQUE INDEX tipohoratrabalho_descricao_escola_in ON tipohoratrabalho(ed128_descricao,ed128_escola);

-- - Horários de Funcionamento
--
-- Migração para incluir o horário de funcionamento da escola de acordo com os horários de aula informados.
-- Buscado o Menor e o Maior valor para cada tudo referente
-- Incluido a migração caso não haja nenhum horário de funcionamento (horarioescola) informado
create table w_horariosfuncionamento as
  select ed17_i_escola as ed123_escola, ed231_i_referencia as ed123_turnoreferencia, min(ed17_h_inicio) as ed123_horainicio, max(ed17_h_fim) as ed123_horafim
  from periodoescola
  inner join turnoreferente on turnoreferente.ed231_i_turno = periodoescola.ed17_i_turno
  where ed231_i_turno not in (select ed231_i_turno from turnoreferente group by ed231_i_turno HAVING count(ed231_i_turno) > 1)
    and not exists( select 1 from horarioescola where ed123_escola = ed17_i_escola)
  group by ed17_i_escola, ed231_i_referencia
  order by ed17_i_escola, ed231_i_referencia;

insert into horarioescola
     select nextval('horarioescola_ed123_sequencial_seq'), ed123_turnoreferencia, ed123_escola,  ed123_horainicio, ed123_horafim
       from w_horariosfuncionamento;

-- - Tipo Hora de trabalho
--
-- Incluido tipo de hora de trabalho 'NORMAL' para todas as
insert into tipohoratrabalho
     select nextval('tipohoratrabalho_ed128_codigo_seq'), '1 - NORMAL', 'NORMAL', 1, true, ed18_i_codigo
       from escola;

-- - Relação de Trabalho
--
-- Alterado o campo tipo hora de trabalho da relação de trabalho para o default de cada escola
update relacaotrabalho set
                        ed23_tipohoratrabalho = migra_tipohoratrabalho.cod_tipohoratrabalho
  from (select ed128_codigo as cod_tipohoratrabalho, ed23_i_codigo as cod_relacaotrabalho from relacaotrabalho
         inner join rechumanoescola  on rechumanoescola.ed75_i_codigo = relacaotrabalho.ed23_i_rechumanoescola
         inner join tipohoratrabalho on tipohoratrabalho.ed128_escola = rechumanoescola.ed75_i_escola) as migra_tipohoratrabalho
  where ed23_i_codigo = migra_tipohoratrabalho.cod_relacaotrabalho;

-- - Horários da Regência (Disponibilidade)
--
-- Alterado o campo tipo hora de trabalho da disponibilidade para o default de cada escola
update rechumanohoradisp set
                        ed33_tipohoratrabalho = migra_tipohoratrabalho.cod_tipohoratrabalho
  from (select ed128_codigo as cod_tipohoratrabalho, ed33_i_codigo as cod_disponibilidade from rechumanohoradisp
         inner join rechumanoescola  on rechumanoescola.ed75_i_codigo = rechumanohoradisp.ed33_rechumanoescola
         inner join tipohoratrabalho on tipohoratrabalho.ed128_escola = rechumanoescola.ed75_i_escola) as migra_tipohoratrabalho
  where ed33_i_codigo = migra_tipohoratrabalho.cod_disponibilidade;

ALTER TABLE relacaotrabalho ALTER COLUMN ed23_tipohoratrabalho SET NOT NULL;
ALTER TABLE rechumanohoradisp ALTER COLUMN ed33_tipohoratrabalho SET NOT NULL;

-------------------------------------------------------
-------------- TAREFA TIPO HORA FIM -------------------
-------------------------------------------------------


------------------------------------------
--------------- FIM TIME C ---------------
------------------------------------------
