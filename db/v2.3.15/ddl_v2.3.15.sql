/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * TIME A 
 * TAREFA #63870
 */

CREATE SEQUENCE rhferias_rh109_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE rhferiasperiodo_rh110_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE rhferias(
rh109_sequencial    int4 NOT NULL default 0,
rh109_regist    int4 NOT NULL default 0,
rh109_periodoaquisitivoinicial    date NOT NULL default null,
rh109_periodoaquisitivofinal    date NOT NULL default null,
rh109_diasdireito   int4 NOT NULL default 0,
rh109_faltasperiodoaquisitivo   int4 default 0,
rh109_observacao   text,
CONSTRAINT rhferias_sequ_pk PRIMARY KEY (rh109_sequencial));

CREATE TABLE rhferiasperiodo(
rh110_sequencial    int4 NOT NULL default 0,
rh110_rhferias    int4 NOT NULL default 0,
rh110_dias    int4 NOT NULL default 0,
rh110_datainicial   date  default null,
rh110_datafinal   date  default null,
rh110_observacao    text  ,
rh110_anopagamento    int4 NOT NULL default 0,
rh110_mespagamento    int4 NOT NULL default 0,
rh110_diasabono   int4  default 0,
rh110_pagaterco   bool NOT NULL default 'f',
rh110_tipoponto   char(1) NOT NULL ,
rh110_periodoespecificoinicial    date  default null,
rh110_periodoespecificofinal    date  default null,
rh110_situacao    int4 default 0,
CONSTRAINT rhferiasperiodo_sequ_pk PRIMARY KEY (rh110_sequencial));

ALTER TABLE rhferias
ADD CONSTRAINT rhferias_regist_fk FOREIGN KEY (rh109_regist)
REFERENCES rhpessoal;

ALTER TABLE rhferiasperiodo
ADD CONSTRAINT rhferiasperiodo_rhferias_fk FOREIGN KEY (rh110_rhferias)
REFERENCES rhferias;

CREATE  INDEX rhferias_dias_in ON rhferias(rh109_diasdireito);

CREATE  INDEX rhferias_periodoaquisitivoinicial_in ON rhferias(rh109_periodoaquisitivoinicial);

CREATE  INDEX rhferias_periodoaquisitivofinal_in ON rhferias(rh109_periodoaquisitivofinal);

CREATE  INDEX rhferias_regist_in ON rhferias(rh109_regist);

CREATE  INDEX rhferiasperiodo_tipoponto_in ON rhferiasperiodo(rh110_tipoponto);

CREATE  INDEX rhferiasperiodo_anopagamento_mespagamento_in ON rhferiasperiodo(rh110_anopagamento,rh110_mespagamento);

CREATE  INDEX rhferiasperiodo_datafinal_in ON rhferiasperiodo(rh110_datafinal);

CREATE  INDEX rhferiasperiodo_datainicial_in ON rhferiasperiodo(rh110_datainicial);

CREATE  INDEX rhferiasperiodo_rhferias_in ON rhferiasperiodo(rh110_rhferias);


ALTER TABLE rhregime ADD rh30_periodoaquisitivo int4 default 12;
ALTER TABLE rhregime ADD rh30_periodogozoferias int4 default 30;


CREATE SEQUENCE rhferiasperiodopontofe_rh112_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE rhferiasperiodopontofe(
rh112_sequencial    int4 NOT NULL default 0,
rh112_rhferiasperiodo   int4 NOT NULL default 0,
rh112_anousu    int4 NOT NULL default 0,
rh112_mesusu    int4 NOT NULL default 0,
rh112_regist    int4 NOT NULL default 0,
rh112_rubric    char(4) NOT NULL ,
rh112_tpp   char(1) NOT NULL ,
rh112_quantidade    float8 NOT NULL default 0,
rh112_valor   float8 default 0,
CONSTRAINT rhferiasperiodopontofe_sequ_pk PRIMARY KEY (rh112_sequencial));

ALTER TABLE rhferiasperiodopontofe
ADD CONSTRAINT rhferiasperiodopontofe_rhferiasperiodo_fk FOREIGN KEY (rh112_rhferiasperiodo)
REFERENCES rhferiasperiodo;

CREATE  INDEX rhferiasperiodopontofe_rhferiasperiodo_in ON rhferiasperiodopontofe(rh112_rhferiasperiodo);

/**
 * --------------------------------------------------------
 * TIME A - INICIO #54783
 * --------------------------------------------------------
 */

  CREATE SEQUENCE issbaseparalisacao_q140_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

  CREATE SEQUENCE issmotivoparalisacao_q141_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

  CREATE TABLE issbaseparalisacao(
  q140_sequencial   int4 NOT NULL default 0,
  q140_issbase    int4 NOT NULL default 0,
  q140_issmotivoparalisacao   int4 NOT NULL default 0,
  q140_datainicio   date NOT NULL default null,
  q140_datafim    date  default null,
  q140_observacao   text ,
  CONSTRAINT issbaseparalisacao_sequ_pk PRIMARY KEY (q140_sequencial));

  CREATE TABLE issmotivoparalisacao(
  q141_sequencial   int4 NOT NULL default 0,
  q141_descricao    varchar(250) ,
  CONSTRAINT issmotivoparalisacao_sequ_pk PRIMARY KEY (q141_sequencial));

  ALTER TABLE issbaseparalisacao
  ADD CONSTRAINT issbaseparalisacao_issbase_fk FOREIGN KEY (q140_issbase)
  REFERENCES issbase;

  ALTER TABLE issbaseparalisacao
  ADD CONSTRAINT issbaseparalisacao_issmotivoparalisacao_fk FOREIGN KEY (q140_issmotivoparalisacao)
  REFERENCES issmotivoparalisacao;

  CREATE  INDEX issbaseparalisacao_q140_issmotivoparalisacao ON issbaseparalisacao(q140_issmotivoparalisacao);

  CREATE  INDEX issbaseparalisacao_q140_issbase_in ON issbaseparalisacao(q140_issbase);

/**
 * --------------------------------------------------------
 * TIME A - FIM #54783
 * --------------------------------------------------------
 */
  
  
/**
 * --------------------------------------------------------
 * TIME A - INICIO 75835
 */  
-- Criando  sequences
CREATE SEQUENCE ruashistorico_j136_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


-- TABELAS E ESTRUTURA

-- Módulo: cadastro
CREATE TABLE ruashistorico(
j136_sequencial   int8 NOT NULL default 0,
j136_ruas   int4 NOT NULL default 0,
j136_ruastipo   int4 NOT NULL default 0,
j136_lei    varchar(50)  ,
j136_datalei    date  default null,
j136_nomeanterior   varchar(50) NOT NULL ,
j136_dataalteracao    date default null,
CONSTRAINT ruashistorico_sequ_pk PRIMARY KEY (j136_sequencial));




-- CHAVE ESTRANGEIRA


ALTER TABLE ruashistorico
ADD CONSTRAINT ruashistorico_ruas_fk FOREIGN KEY (j136_ruas)
REFERENCES ruas;

ALTER TABLE ruashistorico
ADD CONSTRAINT ruashistorico_ruastipo_fk FOREIGN KEY (j136_ruastipo)
REFERENCES ruastipo;

CREATE  INDEX ruashistorico_ruastipo_in ON ruashistorico(j136_ruastipo);
CREATE  INDEX ruashistorico_ruas_in ON ruashistorico(j136_ruas);



-- Criando  sequences
CREATE SEQUENCE rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- Módulo: pessoal
CREATE TABLE rhcadregimefaltasperiodoaquisitivo(
rh125_sequencial	    int4 NOT NULL,
rh125_rhcadregime	    int4 NOT NULL default 0,
rh125_faixainicial		int4 NOT NULL default 0,
rh125_faixafinal		  int4 NOT NULL default 0,
rh125_diasdesconto		int4 NOT NULL default 0,
CONSTRAINT rhcadregimefaltasperiodoaquisitivo_sequ_pk PRIMARY KEY (rh125_sequencial));

-- CHAVE ESTRANGEIRA
   ALTER TABLE rhcadregimefaltasperiodoaquisitivo
ADD CONSTRAINT rhcadregimefaltasperiodoaquisitivo_rhcadregime_fk 
   FOREIGN KEY (rh125_rhcadregime)
    REFERENCES rhcadregime;

-- INDICES
CREATE INDEX rhcadregimefaltasperiodoaquisitivo_rhcadregime_in 
    ON rhcadregimefaltasperiodoaquisitivo(rh125_rhcadregime);

/**
 * Regime de Faltas CLT
 */
insert into rhcadregimefaltasperiodoaquisitivo 
values (nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq'), 2,  0,   5,  0),
       (nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq'), 2,  6,  14,  6),
       (nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq'), 2, 15,  23, 12),
       (nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq'), 2, 24,  32, 18),
       (nextval('rhcadregimefaltasperiodoaquisitivo_rh125_sequencial_seq'), 2, 33, 999, 30);

/**
 * TIME A INICIO 68567
 */
-- Criando  sequences
CREATE SEQUENCE abatimentoprocessoexterno_k160_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE abatimentoprotprocesso_k159_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE abatimentotransferencia_k158_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE abatimentoutilizacao_k157_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


-- TABELAS E ESTRUTURA

-- Módulo: arrecadacao
CREATE TABLE abatimentoprocessoexterno(
k160_sequencial   int8 NOT NULL default 0,
k160_abatimento   int4 NOT NULL default 0,
k160_numeroprocesso   varchar(50) NOT NULL ,
k160_data   date NOT NULL default null,
k160_nometitular    varchar(50) ,
CONSTRAINT abatimentoprocessoexterno_sequ_pk PRIMARY KEY (k160_sequencial));


-- Módulo: arrecadacao
CREATE TABLE abatimentoprotprocesso(
k159_sequencial   int4 NOT NULL default 0,
k159_abatimento   int4 NOT NULL default 0,
k159_protprocesso   int4 default 0,
CONSTRAINT abatimentoprotprocesso_sequ_pk PRIMARY KEY (k159_sequencial));


-- Módulo: arrecadacao
CREATE TABLE abatimentotransferencia(
k158_sequencial   int4 NOT NULL default 0,
k158_abatimentoutilizacao   int4 NOT NULL default 0,
k158_abatimentoorigem   int4 NOT NULL default 0,
k158_abatimentodestino    int4 default 0,
CONSTRAINT abatimentotransferencia_sequ_pk PRIMARY KEY (k158_sequencial));


-- Módulo: arrecadacao
CREATE TABLE abatimentoutilizacao(
k157_sequencial   int4 NOT NULL default 0,
k157_tipoutilizacao   char(1) NOT NULL ,
k157_data   date NOT NULL default null,
k157_valor    numeric(15,2) NOT NULL ,
k157_hora   varchar(5) NOT NULL ,
k157_usuario    int4 NOT NULL default 0,
k157_abatimento   int4 default 0,
CONSTRAINT abatimentoutilizacao_sequ_pk PRIMARY KEY (k157_sequencial));




-- CHAVE ESTRANGEIRA


ALTER TABLE abatimentoprocessoexterno
ADD CONSTRAINT abatimentoprocessoexterno_abatimento_fk FOREIGN KEY (k160_abatimento)
REFERENCES abatimento;

ALTER TABLE abatimentoprotprocesso
ADD CONSTRAINT abatimentoprotprocesso_abatimento_fk FOREIGN KEY (k159_abatimento)
REFERENCES abatimento;

ALTER TABLE abatimentoprotprocesso
ADD CONSTRAINT abatimentoprotprocesso_protprocesso_fk FOREIGN KEY (k159_protprocesso)
REFERENCES protprocesso;

ALTER TABLE abatimentotransferencia
ADD CONSTRAINT abatimentotransferencia_abatimentoorigem_fk FOREIGN KEY (k158_abatimentoorigem)
REFERENCES abatimento;

ALTER TABLE abatimentotransferencia
ADD CONSTRAINT abatimentotransferencia_abatimentodestino_fk FOREIGN KEY (k158_abatimentodestino)
REFERENCES abatimento;

ALTER TABLE abatimentotransferencia
ADD CONSTRAINT abatimentotransferencia_abatimentoutilizacao_fk FOREIGN KEY (k158_abatimentoutilizacao)
REFERENCES abatimentoutilizacao;

ALTER TABLE abatimentoutilizacao
ADD CONSTRAINT abatimentoutilizacao_abatimento_fk FOREIGN KEY (k157_abatimento)
REFERENCES abatimento;

ALTER TABLE abatimentoutilizacao
ADD CONSTRAINT abatimentoutilizacao_usuario_fk FOREIGN KEY (k157_usuario)
REFERENCES db_usuarios;




-- INDICES


CREATE  INDEX abatimentoprocessoexterno_k160_abatimento_in ON abatimentoprocessoexterno(k160_abatimento);

CREATE  INDEX abatimentoprotprocesso_protprocesso_in ON abatimentoprotprocesso(k159_protprocesso);

CREATE  INDEX abatimentoprotprocesso_abatimento_in ON abatimentoprotprocesso(k159_abatimento);

CREATE  INDEX abatimentotransferencia_abatimentodestino_in ON abatimentotransferencia(k158_abatimentodestino);

CREATE  INDEX abatimentotransferencia_abatimentoorigem_in ON abatimentotransferencia(k158_abatimentoorigem);

CREATE  INDEX abatimentotransferencia_abatimentoutilizacao_in ON abatimentotransferencia(k158_abatimentoutilizacao);

CREATE  INDEX abatimentoutilizacao_abatimento_in ON abatimentoutilizacao(k157_abatimento);

alter table abatimento add column k125_valordisponivel numeric(15,2);
/**
 * TIME A - FIM 68567
 */

/**
 * TIME A - INICIO 78284
 */       
alter table abatimento add column k125_abatimentosituacao int4 not null default 1;
alter table abatimento add constraint abatimento_abatimentosituacao_fk FOREIGN KEY (k125_abatimentosituacao) references abatimentosituacao (k165_sequencial) MATCH FULL;
alter table abatimento add column k125_observacao text;

insert into histcalc (k01_codigo, k01_descr, k01_tipo) values (10918, 'DESCONTO CANCELADO', '');   
/**
 * TIME A - FIM 78284
 */

/**
 * TIME A 68981
 */
alter table numpref add column k03_receitapadraocredito integer;

ALTER TABLE numpref ADD CONSTRAINT numpref_receitapadraocredito_fk FOREIGN KEY (k03_receitapadraocredito) REFERENCES tabrec;

update abatimento set k125_valordisponivel = k125_valor where k125_tipoabatimento = 3;

alter table abatimento drop column k125_dataexpira;
/**
 * TIME A 68981
 */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A FIM
 * --------------------------------------------------------------------------------------------------------------------
 */


/**
 * -----------------------------------------------------
 * TIME B T78708 - placa: INICIO 
 */

  ALTER TABLE cfpatri ADD COLUMN t06_controlaplacainstituicao bool default false;

/**
 * TIME B  T78708 - placa: FIM 
 * -----------------------------------------------------
 */

/**
 * TIME B  T79624 - reconhecimentocontabil e conlancamreconhecimentocontabil
 * -----------------------------------------------------
 */

CREATE SEQUENCE conlancamreconhecimentocontabil_c113_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE reconhecimentocontabil_c112_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE conlancamreconhecimentocontabil(
c113_sequencial		int4 NOT NULL default 0,
c113_reconhecimentocontabil		int4 NOT NULL default 0,
c113_codlan		int4 default 0,
CONSTRAINT conlancamreconhecimentocontabil_sequ_pk PRIMARY KEY (c113_sequencial));

CREATE TABLE reconhecimentocontabil(
c112_sequencial		int4 NOT NULL default 0,
c112_reconhecimentocontabiltipo		int4 NOT NULL default 0,
c112_numcgm		int4 NOT NULL default 0,
c112_processoadm		varchar(20) NOT NULL ,
c112_valor		float8 NOT NULL default 0,
c112_estornado		bool default 'f',
CONSTRAINT reconhecimentocontabil_sequ_pk PRIMARY KEY (c112_sequencial));

ALTER TABLE conlancamreconhecimentocontabil
ADD CONSTRAINT conlancamreconhecimentocontabil_reconhecimentocontabil_fk FOREIGN KEY (c113_reconhecimentocontabil)
REFERENCES reconhecimentocontabil;

ALTER TABLE conlancamreconhecimentocontabil
ADD CONSTRAINT conlancamreconhecimentocontabil_codlan_fk FOREIGN KEY (c113_codlan)
REFERENCES conlancam;

ALTER TABLE reconhecimentocontabil
ADD CONSTRAINT reconhecimentocontabil_numcgm_fk FOREIGN KEY (c112_numcgm)
REFERENCES cgm;

ALTER TABLE reconhecimentocontabil
ADD CONSTRAINT reconhecimentocontabil_reconhecimentocontabiltipo_fk FOREIGN KEY (c112_reconhecimentocontabiltipo)
REFERENCES reconhecimentocontabiltipo;

CREATE  INDEX conlancamreconhecimentocontabil_codlan_in ON conlancamreconhecimentocontabil(c113_codlan);
CREATE  INDEX conlancamreconhecimentocontabil_reconhecimentocontabil_in ON conlancamreconhecimentocontabil(c113_reconhecimentocontabil);
CREATE  INDEX reconhecimentocontabil_numcgm_in ON reconhecimentocontabil(c112_numcgm);
CREATE  INDEX reconhecimentocontabil_reconhecimentocontabiltipo_in ON reconhecimentocontabil(c112_reconhecimentocontabiltipo);

/**
 * TIME B  T79624 - final
 * -----------------------------------------------------
 */

/**********************************************************************************************************************/
/************************************************* TIME C - INICIO ****************************************************/
/**********************************************************************************************************************/

/********************************************  Tarefa 60492 - INÍCIO **************************************************/
CREATE SEQUENCE alunocidadao_ed330_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE alunocidadaoresponsavel_ed331_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE cidadaofiliacao_ov29_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE alunocidadaocontato_ed332_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE alunocidadao(
ed330_sequencial    int4 NOT NULL default 0,
ed330_cidadao   int4 NOT NULL default 0,
ed330_cidadao_seq   int4 NOT NULL default 0,
ed330_aluno   int4 default 0,
CONSTRAINT alunocidadao_sequ_pk PRIMARY KEY (ed330_sequencial));

CREATE TABLE alunocidadaoresponsavel(
ed331_sequencial    int4 NOT NULL default 0,
ed331_cidadao   int4 NOT NULL default 0,
ed331_cidadao_seq   int4 NOT NULL default 0,
ed331_aluno   int4 default 0,
CONSTRAINT alunocidadaoresponsavel_sequ_pk PRIMARY KEY (ed331_sequencial));

CREATE TABLE cidadaofiliacao(
ov29_sequencial   int4 NOT NULL default 0,
ov29_cidadao    int4 NOT NULL default 0,
ov29_cidadao_seq    int4 NOT NULL default 0,
ov29_tipofamiliar   int4 NOT NULL default 0,
ov29_cidadaovinculo   int4 NOT NULL default 0,
ov29_cidadaovinculo_seq   int4 default 0,
CONSTRAINT cidadaofiliacao_sequ_pk PRIMARY KEY (ov29_sequencial));

CREATE TABLE alunocidadaocontato(
ed332_sequencial    int4 NOT NULL default 0,
ed332_aluno   int4 NOT NULL default 0,
ed332_cidadao   int4 NOT NULL default 0,
ed332_cidadao_seq   int4 default 0,
CONSTRAINT alunocidadaocontato_sequ_pk PRIMARY KEY (ed332_sequencial));

ALTER TABLE alunocidadao
ADD CONSTRAINT alunocidadao_cidadao_seq_fk FOREIGN KEY (ed330_cidadao,ed330_cidadao_seq)
REFERENCES cidadao;

ALTER TABLE alunocidadao
ADD CONSTRAINT alunocidadao_aluno_fk FOREIGN KEY (ed330_aluno)
REFERENCES aluno;

ALTER TABLE alunocidadaoresponsavel
ADD CONSTRAINT alunocidadaoresponsavel_cidadao_seq_fk FOREIGN KEY (ed331_cidadao,ed331_cidadao_seq)
REFERENCES cidadao;

ALTER TABLE alunocidadaoresponsavel
ADD CONSTRAINT alunocidadaoresponsavel_aluno_fk FOREIGN KEY (ed331_aluno)
REFERENCES aluno;

ALTER TABLE cidadaofiliacao
ADD CONSTRAINT cidadaofiliacao_cidadao_fk FOREIGN KEY (ov29_cidadao,ov29_cidadao_seq)
REFERENCES cidadao;

ALTER TABLE cidadaofiliacao
ADD CONSTRAINT cidadaofiliacao_cidadaovinculo_fk FOREIGN KEY (ov29_cidadaovinculo,ov29_cidadaovinculo_seq)
REFERENCES cidadao;

ALTER TABLE cidadaofiliacao
ADD CONSTRAINT cidadaofiliacao_tipofamiliar_fk FOREIGN KEY (ov29_tipofamiliar)
REFERENCES tipofamiliar;

ALTER TABLE alunocidadaocontato
ADD CONSTRAINT alunocidadaocontato_cidadao_seq_fk FOREIGN KEY (ed332_cidadao,ed332_cidadao_seq)
REFERENCES cidadao;

ALTER TABLE alunocidadaocontato
ADD CONSTRAINT alunocidadaocontato_aluno_fk FOREIGN KEY (ed332_aluno)
REFERENCES aluno;

CREATE  INDEX alunocidadao_aluno_in ON alunocidadao(ed330_aluno);

CREATE  INDEX alunocidadao_cidadao_cidadao_seq_in ON alunocidadao(ed330_cidadao,ed330_cidadao_seq);

CREATE  INDEX alunocidadaoresponsavel_aluno_in ON alunocidadaoresponsavel(ed331_aluno);

CREATE  INDEX alunocidadaoresponsavel_cidadao_in ON alunocidadaoresponsavel(ed331_cidadao,ed331_cidadao_seq);

CREATE UNIQUE INDEX cidadaofiliacao_cidadao_tipofamiliar_in ON cidadaofiliacao(ov29_cidadao,ov29_cidadao_seq,ov29_tipofamiliar);

CREATE  INDEX cidadaofiliacao_cidadaovinculo_in ON cidadaofiliacao(ov29_cidadaovinculo);

CREATE  INDEX alunocidadaocontato_cidadao_in ON alunocidadaocontato(ed332_cidadao,ed332_cidadao_seq);

CREATE  INDEX alunocidadaocontato_aluno_in ON alunocidadaocontato(ed332_aluno);

 update cidadao set ov02_datanascimento =  ed47_d_nasc 
  from aluno, 
       leitoraluno,
       leitorcidadao 
 where bi11_aluno              = ed47_i_codigo 
   and bi28_leitor             = bi11_leitor 
   and bi28_cidadao_sequencial = ov02_sequencial 
   and bi28_cidadao_seq        = ov02_seq;
   
   
create 
 table migracao_aluno_cidadao_antes_da_migracao as 
select ed47_i_codigo, 
       trim(ed47_v_nome) as ed47_v_nome, 
       ed47_d_nasc, 
       trim(ed47_v_mae) as ed47_v_mae, 
       (select min(ov02_sequencial) 
          from cidadao 
         where ov02_nome = trim(ed47_v_nome) 
           and ed47_d_nasc = ov02_datanascimento) as cidadao 
   from aluno;
      
      
      
  insert into cidadao (ov02_sequencial, 
                       ov02_seq, 
                       ov02_nome, 
                       ov02_ident, 
                       ov02_cnpjcpf, 
                       ov02_endereco, 
                       ov02_numero, 
                       ov02_compl, 
                       ov02_bairro, 
                       ov02_munic, 
                       ov02_uf,                 
                       ov02_cep, 
                       ov02_situacaocidadao, 
                       ov02_ativo, 
                       ov02_data, 
                       ov02_datanascimento, 
                       ov02_sexo) 
         select nextval('cidadao_ov02_sequencial_seq'), 
                1, 
                trim(aluno.ed47_v_nome) as nome, 
                ed47_v_ident,
                ed47_v_cpf,
                trim(ed47_v_ender) as endereco, 
                cast(case when trim(ed47_c_numero) ~ '^[0-9]+$' then trim(ed47_c_numero) else null end as integer) as numero,  
                trim(ed47_v_compl) as complemento, 
                trim(ed47_v_bairro) as bairro, 
                trim(ed261_c_nome) as municipio,
                ed260_c_sigla as uf, 
                trim(ed47_v_cep) as cep,
                2,
                true, 
                current_date,
                aluno.ed47_d_nasc as data_nascimento,
                ed47_v_sexo as sexo
           from aluno 
                inner join migracao_aluno_cidadao_antes_da_migracao m on aluno.ed47_i_codigo = m.ed47_i_codigo 
                left join censouf on ed47_i_censoufend = ed260_i_codigo 
                left join censomunic on ed47_i_censomunicend = ed261_i_codigo 
          where cidadao is null;
                
      


  create 
   table w_migracao_alunocidadao as 
  select ed47_i_codigo, 
         trim(ed47_v_nome) as ed47_v_nome, 
         ed47_d_nasc, 
         trim(ed47_v_mae) as ed47_v_mae, 
        (select min(ov02_sequencial) 
           from cidadao 
          where ov02_nome = trim(ed47_v_nome) 
            and ed47_d_nasc = ov02_datanascimento) as cidadao 
   from aluno;      
      
  insert into alunocidadao (ed330_sequencial , ed330_aluno,ed330_cidadao,ed330_cidadao_seq) 
       select nextval('alunocidadao_ed330_sequencial_seq'), 
              ed47_i_codigo, 
              cidadao, 
              1 
         from w_migracao_alunocidadao
        where cidadao is not null
        and not exists(select 1 
                         from alunocidadao 
                        where ed330_aluno = ed47_i_codigo
                      );

                      
/*******************************************  Tarefa 14637 - INÍCIO **************************************************
                      
CREATE SEQUENCE pontoparada_tre04_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE pontoparadadepartamento_tre05_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE veiculotransportemunicipal_tre01_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE veiculotransportemunicipalterceiro_tre03_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE veiculotransportemunicipalveiculos_tre02_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE pontoparada(
tre04_sequencial int4 NOT NULL default 0,
tre04_cadenderbairrocadenderrua int4 NOT NULL default 0,
tre04_nome varchar(70) NOT NULL,
tre04_abreviatura varchar(10) NOT NULL,
tre04_pontoreferencia text,
tre04_latitude numeric(23,20),
tre04_longitude numeric(23,20),
tre04_tipo int4 default 0,
CONSTRAINT pontoparada_sequ_pk PRIMARY KEY (tre04_sequencial));

CREATE TABLE pontoparadadepartamento(
tre05_sequencial int4 NOT NULL default 0,
tre05_pontoparada int4 NOT NULL default 0,
tre05_db_depart int4 default 0,
CONSTRAINT pontoparadadepartamento_sequ_pk PRIMARY KEY (tre05_sequencial));

CREATE TABLE veiculotransportemunicipal(
tre01_sequencial int4 NOT NULL default 0,
tre01_tipotransportemunicipal int4 NOT NULL default 0,
tre01_identificacao varchar(25) NOT NULL,
tre01_numeropassageiros int4 default 0,
CONSTRAINT veiculotransportemunicipal_sequ_pk PRIMARY KEY (tre01_sequencial));

CREATE TABLE veiculotransportemunicipalterceiro(
tre03_sequencial int4 NOT NULL default 0,
tre03_cgm int4 NOT NULL default 0,
tre03_veiculotransportemunicipal int4 default 0,
CONSTRAINT veiculotransportemunicipalterceiro_sequ_pk PRIMARY KEY (tre03_sequencial));

CREATE TABLE veiculotransportemunicipalveiculos(
tre02_sequencial int4 NOT NULL default 0,
tre02_veiculos int4 NOT NULL default 0,
tre02_veiculotransportemunicipal int4 default 0,
CONSTRAINT veiculotransportemunicipalveiculos_sequ_pk PRIMARY KEY (tre02_sequencial));

ALTER TABLE pontoparada
ADD CONSTRAINT pontoparada_cadenderbairrocadenderrua_fk FOREIGN KEY (tre04_cadenderbairrocadenderrua)
REFERENCES cadenderbairrocadenderrua;

ALTER TABLE pontoparadadepartamento
ADD CONSTRAINT pontoparadadepartamento_pontoparada_fk FOREIGN KEY (tre05_pontoparada)
REFERENCES pontoparada;

ALTER TABLE pontoparadadepartamento
ADD CONSTRAINT pontoparadadepartamento_depart_fk FOREIGN KEY (tre05_db_depart)
REFERENCES db_depart;

ALTER TABLE veiculotransportemunicipal
ADD CONSTRAINT veiculotransportemunicipal_tipotransportemunicipal_fk FOREIGN KEY (tre01_tipotransportemunicipal)
REFERENCES tipotransportemunicipal;

ALTER TABLE veiculotransportemunicipalterceiro
ADD CONSTRAINT veiculotransportemunicipalterceiro_cgm_fk FOREIGN KEY (tre03_cgm)
REFERENCES cgm;

ALTER TABLE veiculotransportemunicipalterceiro
ADD CONSTRAINT veiculotransportemunicipalterceiro_veiculotransportemunicipal_fk FOREIGN KEY (tre03_veiculotransportemunicipal)
REFERENCES veiculotransportemunicipal;

ALTER TABLE veiculotransportemunicipalveiculos
ADD CONSTRAINT veiculotransportemunicipalveiculos_veiculos_fk FOREIGN KEY (tre02_veiculos)
REFERENCES veiculos;

ALTER TABLE veiculotransportemunicipalveiculos
ADD CONSTRAINT veiculotransportemunicipalveiculos_veiculotransportemunicipal_fk FOREIGN KEY (tre02_veiculotransportemunicipal)
REFERENCES veiculotransportemunicipal;

CREATE  INDEX pontoparada_cadenderbairrocadenderrua_in ON pontoparada(tre04_cadenderbairrocadenderrua);

CREATE  INDEX pontoparadadepartamento_db_depart_in ON pontoparadadepartamento(tre05_db_depart);

CREATE  INDEX pontoparadadepartamento_pontoparada_in ON pontoparadadepartamento(tre05_pontoparada);

CREATE  INDEX veiculotransportemunicipal_tipotransportemunicipal_in ON veiculotransportemunicipal(tre01_tipotransportemunicipal);

CREATE  INDEX veiculotransportemunicipalterceiro_veiculotransportemunicipal_in ON veiculotransportemunicipalterceiro(tre03_veiculotransportemunicipal);

CREATE  INDEX veiculotransportemunicipalterceiro_cgm_in ON veiculotransportemunicipalterceiro(tre03_cgm);

CREATE  INDEX veiculotransportemunicipalveiculos_veiculotransportemunicipal_in ON veiculotransportemunicipalveiculos(tre02_veiculotransportemunicipal);

CREATE  INDEX veiculotransportemunicipalveiculos_veiculos_in ON veiculotransportemunicipalveiculos(tre02_veiculos);

CREATE SEQUENCE itinerariologradouro_tre10_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransporte_tre06_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransportehorario_tre07_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransportehorarioveiculo_tre08_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransporteitinerario_tre09_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransportepontoparada_tre11_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhatransportepontoparadaaluno_tre12_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE itinerariologradouro(
tre10_sequencial int4 NOT NULL default 0,
tre10_linhatransporteitinerario int4 NOT NULL default 0,
tre10_cadenderbairrocadenderrua int4 NOT NULL default 0,
tre10_ordem int4 default 0,
CONSTRAINT itinerariologradouro_sequ_pk PRIMARY KEY (tre10_sequencial));

CREATE TABLE linhatransporte(
tre06_sequencial int4 NOT NULL default 0,
tre06_nome varchar(60) NOT NULL,
tre06_abreviatura varchar(10),
CONSTRAINT linhatransporte_sequ_pk PRIMARY KEY (tre06_sequencial));

CREATE TABLE linhatransportehorario(
tre07_sequencial int4 NOT NULL default 0,
tre07_linhatransporteitinerario int4 NOT NULL default 0,
tre07_horasaida varchar(5) NOT NULL,
tre07_horachegada varchar(5),
CONSTRAINT linhatransportehorario_sequ_pk PRIMARY KEY (tre07_sequencial));

CREATE TABLE linhatransportehorarioveiculo(
tre08_sequencial int4 NOT NULL default 0,
tre08_linhatransportehorario int4 NOT NULL default 0,
tre08_veiculotransportemunicipal int4 default 0,
CONSTRAINT linhatransportehorarioveiculo_sequ_pk PRIMARY KEY (tre08_sequencial));

CREATE TABLE linhatransporteitinerario(
tre09_sequencial int4 NOT NULL default 0,
tre09_linhatransporte int4 NOT NULL default 0,
tre09_tipo int4 default 0,
CONSTRAINT linhatransporteitinerario_sequ_pk PRIMARY KEY (tre09_sequencial));

CREATE TABLE linhatransportepontoparada(
tre11_sequencial int4 NOT NULL default 0,
tre11_pontoparada int4 NOT NULL default 0,
tre11_itinerariologradouro int4 NOT NULL default 0,
tre11_ordem int4 default 0,
CONSTRAINT linhatransportepontoparada_sequ_pk PRIMARY KEY (tre11_sequencial));

CREATE TABLE linhatransportepontoparadaaluno(
tre12_sequencial int4 NOT NULL default 0,
tre12_linhatransportepontoparada int4 NOT NULL default 0,
tre12_aluno int8 NOT NULL default 0,
tre12_observacao text,
CONSTRAINT linhatransportepontoparadaaluno_sequ_pk PRIMARY KEY (tre12_sequencial));

ALTER TABLE itinerariologradouro
ADD CONSTRAINT itinerariologradouro_linhatransporteitinerario_fk FOREIGN KEY (tre10_linhatransporteitinerario)
REFERENCES linhatransporteitinerario;

ALTER TABLE itinerariologradouro
ADD CONSTRAINT itinerariologradouro_cadenderbairrocadenderrua_fk FOREIGN KEY (tre10_cadenderbairrocadenderrua)
REFERENCES cadenderbairrocadenderrua;

ALTER TABLE linhatransportehorario
ADD CONSTRAINT linhatransportehorario_linhatransporteitinerario_fk FOREIGN KEY (tre07_linhatransporteitinerario)
REFERENCES linhatransporteitinerario;

ALTER TABLE linhatransportehorarioveiculo
ADD CONSTRAINT linhatransportehorarioveiculo_linhatransportehorario_fk FOREIGN KEY (tre08_linhatransportehorario)
REFERENCES linhatransportehorario;

ALTER TABLE linhatransportehorarioveiculo
ADD CONSTRAINT linhatransportehorarioveiculo_veiculotransportemunicipal_fk FOREIGN KEY (tre08_veiculotransportemunicipal)
REFERENCES veiculotransportemunicipal;

ALTER TABLE linhatransporteitinerario
ADD CONSTRAINT linhatransporteitinerario_linhatransporte_fk FOREIGN KEY (tre09_linhatransporte)
REFERENCES linhatransporte;

ALTER TABLE linhatransportepontoparada
ADD CONSTRAINT linhatransportepontoparada_pontoparada_fk FOREIGN KEY (tre11_pontoparada)
REFERENCES pontoparada;

ALTER TABLE linhatransportepontoparada
ADD CONSTRAINT linhatransportepontoparada_itinerariologradouro_fk FOREIGN KEY (tre11_itinerariologradouro)
REFERENCES itinerariologradouro;

ALTER TABLE linhatransportepontoparadaaluno
ADD CONSTRAINT linhatransportepontoparadaaluno_linhatransportepontoparada_fk FOREIGN KEY (tre12_linhatransportepontoparada)
REFERENCES linhatransportepontoparada;

ALTER TABLE linhatransportepontoparadaaluno
ADD CONSTRAINT linhatransportepontoparadaaluno_aluno_fk FOREIGN KEY (tre12_aluno)
REFERENCES aluno;

CREATE UNIQUE INDEX itinerariologradouro_linhatransporteitinerario_cadenderbairrocadenderrua_in ON itinerariologradouro(tre10_linhatransporteitinerario,tre10_cadenderbairrocadenderrua);

CREATE  INDEX linhatransportehorario_linhatransporteitinerario_in ON linhatransportehorario(tre07_linhatransporteitinerario);

CREATE  INDEX linhatransportehorarioveiculo_veiculotransportemunicipal_in ON linhatransportehorarioveiculo(tre08_veiculotransportemunicipal);

CREATE  INDEX linhatransportehorarioveiculo_linhatransportehorario_in ON linhatransportehorarioveiculo(tre08_linhatransportehorario);

CREATE UNIQUE INDEX linhatransporteitinerario_linhatransporte_tipo_in ON linhatransporteitinerario(tre09_linhatransporte,tre09_tipo);

CREATE  INDEX linhatransportepontoparada_pontoparada_in ON linhatransportepontoparada(tre11_pontoparada);

CREATE  INDEX linhatransportepontoparada_itinerariologradouro_in ON linhatransportepontoparada(tre11_itinerariologradouro);

CREATE  INDEX linhatransportepontoparadaaluno_aluno_in ON linhatransportepontoparadaaluno(tre12_aluno);

CREATE  INDEX linhatransportepontoparadaaluno_linhatransportepontoparada_in ON linhatransportepontoparadaaluno(tre12_linhatransportepontoparada);

*******************************************  Tarefa 14637 - FIM ******************************************************/



/***************************************** Tarefa 78614 Inicio ********************************************************/
alter table far_retirada add column fa04_numeronotificacao int8 default 0;
/****************************************** Tarefa 78614 Final *******************************************************/



/***************************************** Tarefa 79609 Inicio ********************************************************/
CREATE SEQUENCE cgs_undetnia_s201_codigo_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE cgs_undetnia(
s201_codigo int4 NOT NULL default 0,
s201_cgs_unid int4 NOT NULL default 0,
s201_etnia int4 default 0,
CONSTRAINT cgs_undetnia_codi_pk PRIMARY KEY (s201_codigo));

ALTER TABLE cgs_undetnia ADD CONSTRAINT cgs_undetnia_unid_fk  FOREIGN KEY (s201_cgs_unid) REFERENCES cgs_und;
ALTER TABLE cgs_undetnia ADD CONSTRAINT cgs_undetnia_etnia_fk FOREIGN KEY (s201_etnia)    REFERENCES etnia;

CREATE  INDEX cgs_undetnia_etnia_in    ON cgs_undetnia(s201_etnia);
CREATE  INDEX cgs_undetnia_cgs_unid_in ON cgs_undetnia(s201_cgs_unid);

/****************************************** Tarefa 79609 Final *******************************************************/



/***************************************
 ************* TIME C - FIM ************
 ***************************************/




/**********************************************************************************************************************/
/************************************************* TIME D - INICIO ****************************************************/
/**********************************************************************************************************************/

/********************************************  Tarefa 73456 - INÍCIO **************************************************/

/** Sprint 01 ini **/

ALTER TABLE issqn.issplanit ALTER COLUMN q21_servico TYPE TEXT;
alter table fiscal.requisicaoaidof add y116_codigoaidof integer;


/********************************************  Tarefa 73456 -  FIM  ***************************************************/


/**********************************************************************************************************************/
/************************************************* TIME D - FIM *******************************************************/
/**********************************************************************************************************************/


/*
 * Tarefa 75815
 */
select fc_executa_ddl('alter table matestoqueinimeipm add m89_valorfinanceiro numeric default 0;');

update matestoqueinimeipm 
   set m89_valorfinanceiro = round(m89_precomedio*m82_quant, 2) 
  from matestoqueinimei 
 where m89_matestoqueinimei = m82_codigo;
