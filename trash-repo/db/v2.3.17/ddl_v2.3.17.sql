/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * TIME A
 * Tarefa #81530
 */
ALTER TABLE paritbi add column it24_grupopadraoconstrutivobenurbana int4;

ALTER TABLE paritbi
ADD CONSTRAINT paritbi_grupopadraoconstrutivobenurbana_fk FOREIGN KEY (it24_grupopadraoconstrutivobenurbana)
REFERENCES cargrup;

CREATE TABLE itbiconstrpadraoconstrutivo(
it34_codigo             int4 NOT NULL default 0,
it34_caract             int4 default 0,
CONSTRAINT itbiconstrpadraoconstrutivo_codi_cara_pk PRIMARY KEY (it34_codigo,it34_caract));

ALTER TABLE itbiconstrpadraoconstrutivo
ADD CONSTRAINT itbiconstrpadraoconstrutivo_codigo_fk FOREIGN KEY (it34_codigo)
REFERENCES itbiconstr;

ALTER TABLE itbiconstrpadraoconstrutivo
ADD CONSTRAINT itbiconstrpadraoconstrutivo_caract_fk FOREIGN KEY (it34_caract)
REFERENCES caracter;

CREATE  INDEX itbiconstrpadraoconstrutivo_it34_caract_in ON itbiconstrpadraoconstrutivo(it34_caract);

CREATE  INDEX itbiconstrpadraoconstrutivo_it34_codigo_in ON itbiconstrpadraoconstrutivo(it34_codigo);

ALTER TABLE configuracaogrupocaracteristicas DROP COLUMN db144_tipoutilizacaoitbiurbano;
ALTER TABLE configuracaogrupocaracteristicas DROP COLUMN db144_utilizacaoitbirural;
ALTER TABLE configuracaogrupocaracteristicas DROP COLUMN db144_tipoutilizacaoitbirural;

/**
 * FIM TAREFA #81530
 */

/**
 * TIME A
 * Tarefa #62798
 */
CREATE SEQUENCE arquivosimplesimportacao_q64_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE arquivosimplesimportacaodetalhe_q142_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE arquivosimplesimportacao(
q64_sequencial    int4 NOT NULL default 0,
q64_nomearquivo   varchar(60) NOT NULL ,
q64_data    date NOT NULL default null,
q64_processado    bool default 'f',
q64_datalimitevencimentos   date default null,
CONSTRAINT arquivosimplesimportacao_sequ_pk PRIMARY KEY (q64_sequencial));

CREATE TABLE arquivosimplesimportacaodetalhe(
q142_sequencial   int4 NOT NULL default 0,
q142_arquivosimplesimportacao   int4 NOT NULL default 0,
q142_cnpj   varchar(14) ,
q142_cnae   int4 NOT NULL default 0,
q142_apto   bool NOT NULL default 'f',
q142_observacao   text ,
CONSTRAINT arquivosimplesimportacaodetalhe_sequ_pk PRIMARY KEY (q142_sequencial));

ALTER TABLE arquivosimplesimportacaodetalhe
ADD CONSTRAINT arquivosimplesimportacaodetalhe_arquivosimplesimportacao_fk FOREIGN KEY (q142_arquivosimplesimportacao)
REFERENCES arquivosimplesimportacao;

CREATE  INDEX arquivosimplesimportacaodetalhe_arquivosimplesimportacao_in ON arquivosimplesimportacaodetalhe(q142_arquivosimplesimportacao);

ALTER TABLE arquivosimplesimportacaodetalhe ALTER COLUMN q142_cnae TYPE varchar(20);

/**
 * FIM TAREFA #62798
 */
/**
 * Tarefa do MODULO CONFIGURACAO
 */
ALTER TABLE db_usuarios alter COLUMN senha type varchar(40);

/**
 * FIM TAREFA MODULO CONFIGURACAO
 */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B - INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

 /*
  * #70942
  *  PROTPROCESSODOCUMENTO
  *    estrutura que ira guardar os documentos de processos
  */
CREATE SEQUENCE protprocessodocumento_p01_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE protprocessodocumento(
p01_sequencial    int4 NOT NULL default 0,
p01_protprocesso    int4 NOT NULL default 0,
p01_descricao   varchar(200) NOT NULL ,
p01_documento  oid NOT NULL ,
p01_nomedocumento varchar(255) NOT NULL,
CONSTRAINT protprocessodocumento_sequ_pk PRIMARY KEY (p01_sequencial));
ALTER TABLE protprocessodocumento
ADD CONSTRAINT protprocessodocumento_protprocesso_fk FOREIGN KEY (p01_protprocesso)
REFERENCES protprocesso;
CREATE  INDEX protprocessodocumento_p01_protprocesso_in ON protprocessodocumento(p01_protprocesso);
/*
 * ============================  fim PROTPROCESSODOCUMENTO   TIME B ===================================================
 */


/**
 * -----------------------------------------------------------------------------
 * TIME B #81550 - INICIO
 * -----------------------------------------------------------------------------
 */

/**
 * contranslanelemento {
 */
CREATE SEQUENCE contranslanelemento_c114_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE contranslanelemento(
c114_sequencial   int4 NOT NULL default 0,
c114_contranslan  int4 NOT NULL default 0,
c114_elemento     varchar(15) ,
CONSTRAINT contranslanelemento_sequ_pk PRIMARY KEY (c114_sequencial));

ALTER TABLE contranslanelemento
ADD CONSTRAINT contranslanelemento_contranslan_fk FOREIGN KEY (c114_contranslan)
REFERENCES contranslan;

CREATE INDEX contranslanelemento_contranslan_in ON contranslanelemento(c114_contranslan);
/**
 * }
 */

/**
 * contranslrvinculo {{{
 */
CREATE SEQUENCE contranslrvinculo_c116_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE contranslrvinculo(
c116_sequencial          int4 NOT NULL default 0,
c116_contranslrinclusao  int4 NOT NULL default 0,
c116_contranslrestorno   int4 NOT NULL default 0,
CONSTRAINT contranslrvinculo_sequ_pk PRIMARY KEY (c116_sequencial));

ALTER TABLE contranslrvinculo ADD CONSTRAINT contranslrvinculo_contranslrinclusao_fk FOREIGN KEY (c116_contranslrinclusao) REFERENCES contranslr;
ALTER TABLE contranslrvinculo ADD CONSTRAINT contranslrvinculo_contranslrestorno_fk  FOREIGN KEY (c116_contranslrestorno)  REFERENCES contranslr;

CREATE INDEX contranslrvinculo_contranslrestorno_in ON contranslrvinculo(c116_contranslrestorno);
CREATE INDEX contranslrvinculo_contranslrinclusao_in ON contranslrvinculo(c116_contranslrinclusao);
/**
 * }}}
 */

/**
 * TIME B - #80944 {
 */
CREATE SEQUENCE conlancammatestoqueinimei_c103_sequencial_seq
INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE conlancammatestoqueinimei(
  c103_sequencial   int4 NOT NULL default 0,
  c103_conlancam    int4 NOT NULL default 0,
  c103_matestoqueinimei   int8 default 0,
  CONSTRAINT conlancammatestoqueinimei_sequ_pk PRIMARY KEY (c103_sequencial)
);

ALTER TABLE conlancammatestoqueinimei
ADD CONSTRAINT conlancammatestoqueinimei_matestoqueinimei_fk FOREIGN KEY (c103_matestoqueinimei)
REFERENCES matestoqueinimei;

ALTER TABLE conlancammatestoqueinimei
ADD CONSTRAINT conlancammatestoqueinimei_conlancam_fk FOREIGN KEY (c103_conlancam)
REFERENCES conlancam;

CREATE INDEX conlancammatestoqueinimei_matestoqueinimei_ini ON conlancammatestoqueinimei(c103_matestoqueinimei);
CREATE INDEX conlancammatestoqueinimei_conlancam_in ON conlancammatestoqueinimei(c103_conlancam);

CREATE TABLE w_bkp_conlancammatestoqueini AS SELECT * FROM conlancammatestoqueini;
DROP TABLE IF EXISTS conlancammatestoqueini;
/**
 * }
 */

 /**
  * TIME B #81552 {
  */
ALTER TABLE orciniciativa add column o147_ano int4 default 0;
update orciniciativa set o147_ano = 2014;
 /**
  * }
  */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME B - FIM
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C - INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */
alter table edu_relatmodel add column ed217_exibeturma bool default 'f';
alter table edu_relatmodel add column ed217_exibecargahoraria bool default 'f';
alter table historicomps  add column ed62_percentualfrequencia numeric(5,2);


-- Tarefa 80891
create temp table w_migracao as select tf12_i_codigo, tf12_faturabpa, (tf12_faturabpa = 1)::boolean as novo
                                  from tfd_ajudacusto;
alter table tfd_ajudacusto drop COLUMN tf12_i_automatico;
alter table tfd_ajudacusto drop COLUMN tf12_faturabpa;
alter table tfd_ajudacusto ADD COLUMN tf12_faturabpa    boolean default false;
alter table tfd_ajudacusto ADD COLUMN tf12_acompanhente boolean default false;
update tfd_ajudacusto set tf12_faturabpa = novo
  from w_migracao
where tfd_ajudacusto.tf12_i_codigo = w_migracao.tf12_i_codigo;


-- Tarefa 77818
CREATE SEQUENCE fechamentotfdprocedimento_tf40_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;



CREATE SEQUENCE fechamentoarquivotfd_tf36_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;


CREATE TABLE fechamentotfdprocedimento(
tf40_sequencial            int4 NOT NULL default 0,
tf40_tfd_fechamento        int4 NOT NULL default 0,
tf40_tfd_pedidotfd         int4 NOT NULL default 0,
tf40_cgs_und               int4 NOT NULL default 0,
tf40_sau_procedimento      int4 NOT NULL default 0,
tf40_faturamentoautomatico bool NOT NULL default 'f',
tf40_paciente              bool default 'f',
CONSTRAINT fechamentotfdprocedimento_sequ_pk PRIMARY KEY (tf40_sequencial));



CREATE TABLE fechamentoarquivotfd(
tf36_sequencial   int4 NOT NULL default 0,
tf36_id_usuario   int4 NOT NULL default 0,
tf36_tfd_fechamento   int4 NOT NULL default 0,
tf36_data   date NOT NULL default null,
tf36_hora   char(5) NOT NULL ,
tf36_nomearquivo    varchar(100) NOT NULL ,
tf36_oidarquivo   oid ,
CONSTRAINT fechamentoarquivotfd_sequ_pk PRIMARY KEY (tf36_sequencial));


ALTER TABLE sau_medicosforarede ADD s154_rhcbo int4 default null;
ALTER TABLE sau_medicosforarede ADD CONSTRAINT sau_medicosforarede_rhcbo_fk FOREIGN KEY (s154_rhcbo) REFERENCES rhcbo;

ALTER TABLE tfd_pedidotfd ADD tf01_rhcbosolicitante int4 default null;
update tfd_pedidotfd set tf01_rhcbosolicitante = tf01_i_rhcbo where tf01_i_codigo = tf01_i_codigo;
ALTER TABLE tfd_pedidotfd ALTER tf01_rhcbosolicitante SET NOT NULL;

ALTER TABLE tfd_pedidotfd ADD CONSTRAINT tfd_pedidotfd_rhcbosolicitante_fk FOREIGN KEY (tf01_rhcbosolicitante) REFERENCES rhcbo;

ALTER TABLE fechamentotfdprocedimento ADD CONSTRAINT fechamentotfdprocedimento_fechamento_fk FOREIGN KEY (tf40_tfd_fechamento) REFERENCES tfd_fechamento;
ALTER TABLE fechamentotfdprocedimento ADD CONSTRAINT fechamentotfdprocedimento_pedidotfd_fk FOREIGN KEY (tf40_tfd_pedidotfd) REFERENCES tfd_pedidotfd;
ALTER TABLE fechamentotfdprocedimento ADD CONSTRAINT fechamentotfdprocedimento_und_fk FOREIGN KEY (tf40_cgs_und) REFERENCES cgs_und;
ALTER TABLE fechamentotfdprocedimento ADD CONSTRAINT fechamentotfdprocedimento_procedimento_fk FOREIGN KEY (tf40_sau_procedimento) REFERENCES sau_procedimento;


ALTER TABLE fechamentoarquivotfd ADD CONSTRAINT fechamentoarquivotfd_usuario_fk FOREIGN KEY (tf36_id_usuario) REFERENCES db_usuarios;

ALTER TABLE fechamentoarquivotfd ADD CONSTRAINT fechamentoarquivotfd_fechamento_fk FOREIGN KEY (tf36_tfd_fechamento) REFERENCES tfd_fechamento;
CREATE INDEX tfd_pedidotfd_tf01_rhcbosolicitante_in ON tfd_pedidotfd(tf01_rhcbosolicitante);
CREATE INDEX sau_medicosforarede_rhcbo_in ON sau_medicosforarede(s154_rhcbo);

CREATE INDEX fechamentotfdprocedimento_tfd_fechamento_in ON fechamentotfdprocedimento(tf40_tfd_fechamento);
CREATE INDEX fechamentotfdprocedimento_tfd_pedidotfd_in ON fechamentotfdprocedimento(tf40_tfd_pedidotfd);
CREATE INDEX fechamentotfdprocedimento_cgs_und_in ON fechamentotfdprocedimento(tf40_cgs_und);
CREATE INDEX fechamentotfdprocedimento_sau_procedimento_in ON fechamentotfdprocedimento(tf40_sau_procedimento);
CREATE  INDEX fechamentoarquivotfd_id_usuario_in ON fechamentoarquivotfd(tf36_id_usuario);
CREATE  INDEX fechamentoarquivotfd_fechamento_in ON fechamentoarquivotfd(tf36_tfd_fechamento);

-- tarefa 81818
alter table basemps add column ed34_lancarhistorico bool default 't';
alter table histmpsdisc add column ed65_opcional bool default 'f';
alter table histmpsdiscfora add column ed100_opcional bool default 'f';
alter table regencia add column ed59_lancarhistorico bool default 't';

update basemps set ed34_lancarhistorico = case when ed34_c_condicao = 'OB' then true else false end;
update regencia set ed59_lancarhistorico = case when ed59_c_condicao = 'OB' then true else false end;

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME C - FIM
 * --------------------------------------------------------------------------------------------------------------------
 */


/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME INFRAESTRUTURA - INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

-- tarefa 82626

-- Seta o 'maintenance_work_mem' para o valor de 'effective_cache_size' se este nao for superior a '2GB'
-- pequeno tunning para melhorar o desempenho de criação de indices no servidor
SELECT	CASE
			WHEN (SELECT setting::bigint*(current_setting('block_size')::int/1024) FROM pg_settings WHERE name = 'effective_cache_size') > 2097151 THEN
				set_config('maintenance_work_mem', '2GB', false)
			ELSE
				set_config('maintenance_work_mem', (SELECT setting::bigint*(current_setting('block_size')::int/1024)/1024 FROM pg_settings WHERE name = 'effective_cache_size')||'MB', false)
		END;

-- Indices GIN para campos ARRAY
DROP INDEX if exists db_auditoria_mudancas_nome_campo_in;
CREATE INDEX db_auditoria_mudancas_nome_campo_in ON configuracoes.db_auditoria USING GIN (((mudancas).nome_campo));

SELECT fc_executa_ddl('CREATE INDEX '||table_name||'_mudancas_nome_campo_in ON '||table_schema||'.'||table_name||' USING GIN (((mudancas).nome_campo));')
  FROM information_schema.tables
 WHERE table_schema = 'configuracoes'
   AND table_name ~ '^db_auditoria_[0-9]{6}_[0-9]{1}'
   AND table_type = 'BASE TABLE';

-- Ajustes particoes (mudar predicado das particoes de datahora_sessao para datahora_servidor)
DROP TABLE IF EXISTS w_exception_tables;
CREATE TABLE w_exception_tables (
	id				TIMESTAMP PRIMARY KEY,
	table_schema	TEXT,
	table_name		TEXT,
	constraint_name	TEXT,
	constraint_def	TEXT,
	period_start	TIMESTAMPTZ,
	period_end		TIMESTAMPTZ,
	query			TEXT,
	query_select	TEXT,
	query_delete	TEXT
);

CREATE OR REPLACE FUNCTION __tmp_date(TEXT, INTEGER, TIME) RETURNS timestamptz AS
$$
	SELECT
		to_timestamp(split_part($1, '_', 3) || to_char(coalesce($2, fc_ultimodiames(substr(split_part($1, '_', 3),1,4)::integer, substr(split_part($1,'_',3),5,2)::integer)), 'FM00')||coalesce($3, '23:59:59.999999'), 'YYYYMMDDHH24:MI:SS.US');
$$
LANGUAGE sql;

CREATE OR REPLACE FUNCTION __execute(
	table_schema	TEXT,
	table_name		TEXT,
	constraint_name	TEXT,
	definition		TEXT,
	query			TEXT
) RETURNS void AS
$$
BEGIN
	EXECUTE query;
EXCEPTION
	WHEN others THEN
		INSERT INTO w_exception_tables
		VALUES (
			clock_timestamp(), table_schema, table_name, constraint_name, definition,
			__tmp_date(table_name, 1, '00:00:00.000000'), __tmp_date(table_name, null, null),
			query
		);
END;
$$
LANGUAGE plpgsql;

-- Recriar checks constraints
DO
$$
DECLARE
	rTable		RECORD;
	rConstraint	RECORD;
	sSQLDrop	TEXT;
	sSQLCreate	TEXT;
	sSQLUpdate	TEXT;
BEGIN
	FOR rTable IN	SELECT	table_schema, table_name
					FROM	information_schema.tables
					WHERE	table_name ~ '^db_auditoria_[0-9]'
					ORDER	BY table_name
	LOOP
		SELECT	pg_get_constraintdef(oid) AS def,
				conname
		INTO	rConstraint
		FROM	pg_constraint
		WHERE	conrelid = rTable.table_name::regclass
		AND		contype = 'c'
		AND		conname = (rTable.table_name||'_datahora_sessao_ck');

		IF rConstraint.def IS NOT NULL THEN
			sSQLDrop := FORMAT('ALTER TABLE %I.%I DROP CONSTRAINT %I;',
				rTable.table_schema,
				rTable.table_name,
				rConstraint.conname);

			PERFORM __execute(rTable.table_schema, rTable.table_name, NULL, NULL, sSQLDrop);
		END IF;

		rConstraint.conname :=
			COALESCE(REPLACE(rConstraint.conname, 'datahora_sessao', 'datahora_servidor'),
						rTable.table_name || '_datahora_servidor_ck');

		rConstraint.def :=
			COALESCE(REPLACE(rConstraint.def, 'datahora_sessao', 'datahora_servidor'),
						'CHECK (datahora_servidor BETWEEN '||
						quote_literal(__tmp_date(rTable.table_name, 1, '00:00:00.000000')::text)||
						' AND '||
						quote_literal(__tmp_date(rTable.table_name, null, null)::text)||
						' AND instit = '||split_part(rTable.table_name, '_', 4)||')');

		sSQLUpdate := FORMAT (
			E'UPDATE %I.%I SET datahora_servidor = to_timestamp(data||hora, \'YYYY-MM-DDHH24:MI:SS\') '||
			'FROM db_logsacessa WHERE logsacessa = codsequen AND data <> datahora_servidor::date', rTable.table_schema, rTable.table_name);

		PERFORM __execute(rTable.table_schema, rTable.table_name, NULL, NULL, sSQLUpdate);

		sSQLCreate := FORMAT ('ALTER TABLE %I.%I ADD CONSTRAINT %I %s;',
				rTable.table_schema,
				rTable.table_name,
				rConstraint.conname,
				rConstraint.def);
		PERFORM __execute(
			rTable.table_schema,
			rTable.table_name,
			rConstraint.conname,
			rConstraint.def,
			sSQLCreate);

	END LOOP;
END;
$$
LANGUAGE plpgsql;

UPDATE w_exception_tables
   SET query_select = format('SELECT * FROM %I.%I WHERE %s', table_schema, table_name, replace(replace(replace(constraint_def, 'CHECK ', ''), 'BETWEEN', 'NOT BETWEEN'), 'AND instit =', 'OR instit <> ')),
       query_delete = format('DELETE FROM %I.%I WHERE %s;', table_schema, table_name, replace(replace(replace(constraint_def, 'CHECK ', ''), 'BETWEEN', 'NOT BETWEEN'), 'AND instit =', 'OR instit <> '));

/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME INFRAESTRUTURA - FIM
 * --------------------------------------------------------------------------------------------------------------------
 */
