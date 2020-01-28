<?php

use Classes\PostgresMigration;

class M9730ArrayPosition extends PostgresMigration
{
    public function up()
    {
        $this->criaConsultaAcesso();
        $this->criaConsultaMudancas();
    }

    public function down()
    {
        $this->deletaConsultaAcesso();
        $this->deletaConsultaMudancas();
    }

    private function criaConsultaAcesso()
    {
        $sSql = "
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_acessos(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                sEsquema        TEXT,
                sTabela         TEXT,
                sUsuario        TEXT,
                iInstit         INTEGER,
                sCampo          TEXT,
                sValorAntigo    TEXT,
                sValorNovo      TEXT
            ) RETURNS SETOF INTEGER AS
            $$
            DECLARE
                rRetorno		INTEGER;
                rAuditoria		RECORD;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConector		TEXT DEFAULT 'OR';
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := 'SELECT logsacessa FROM configuracoes.db_auditoria ';
                sSQL := sSQL || ' WHERE datahora_servidor BETWEEN '||quote_literal(tDataHoraInicio::TEXT)||'::TIMESTAMPTZ AND '||quote_literal(tDataHoraFim::TEXT)||'::TIMESTAMPTZ';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
            
                IF sEsquema IS NOT NULL THEN
                    sSQL := sSQL || '   AND esquema = '||quote_literal(sEsquema);
                END IF;
            
                IF sTabela IS NOT NULL THEN
                    sSQL := sSQL || '   AND tabela  = '||quote_literal(sTabela);
                END IF;
            
                IF sUsuario IS NOT NULL THEN
                    sSQL := sSQL || '   AND usuario  = '||quote_literal(sUsuario);
                END IF;
            
                IF sCampo IS NOT NULL AND (sValorAntigo IS NOT NULL OR sValorNovo IS NOT NULL) THEN
                    sSQL := sSQL || '   AND (((mudancas).nome_campo    @> ARRAY['||quote_literal(sCampo)||'] ';
                    sSQL := sSQL || '    OR   (chave).nome_campo       @> ARRAY['||quote_literal(sCampo)||']) ';
            
                    IF sValorAntigo IS NULL AND sValorNovo IS NOT NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_novo @> ARRAY['||quote_literal(sValorNovo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    ELSIF sValorAntigo IS NOT NULL AND sValorNovo IS NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'])) ';
                    ELSE
                        sSQL := sSQL || '   AND (((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] OR ';
                        sSQL := sSQL || '         (mudancas).valor_novo   @> ARRAY['||quote_literal(sValorNovo)||']) AND ';
                        sSQL := sSQL || '        (((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||' OR ';
                        sSQL := sSQL || '         ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||'))';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'] OR (chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    END IF;
                END IF;
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    END IF;
                PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rAuditoria
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (sequencial         integer,
                                    esquema            text,
                                    tabela             text,
                                    operacao           dm_operacao_tabela,
                                    transacao          bigint,
                                    datahora_sessao    timestamp with time zone,
                                    datahora_servidor  timestamp with time zone,
                                    tempo              interval,
                                    usuario            character varying(20),
                                    chave              tp_auditoria_chave_primaria,
                                    mudancas           tp_auditoria_mudancas_campo,
                                    logsacessa         integer,
                                    instit             integer);
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rAuditoria.logsacessa;
            
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rAuditoria;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rAuditoria.logsacessa;
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_acessos(
              tDataHoraInicio TIMESTAMP,
              tDataHoraFim    TIMESTAMP,
              sEsquema        TEXT,
              sTabela         TEXT,
              sUsuario        TEXT,
              iInstit         INTEGER
            ) RETURNS SETOF INTEGER AS
            $$
              SELECT *
                FROM configuracoes.fc_auditoria_consulta_acessos($1, $2, $3, $4, $5, $6, NULL, NULL, NULL);
            $$
            LANGUAGE sql;
        ";

        $this->execute($sSql);
    }

    private function criaConsultaMudancas()
    {
        $sSql = "
            DROP TYPE IF EXISTS configuracoes.tp_auditoria_consulta_mudancas CASCADE;
            CREATE TYPE configuracoes.tp_auditoria_consulta_mudancas AS (
                esquema           TEXT,
                tabela            TEXT,
                operacao          CHAR(1),
                chave             VARCHAR,
                transacao         BIGINT,
                datahora_sessao   TIMESTAMP WITH TIME ZONE,
                datahora_servidor TIMESTAMP WITH TIME ZONE,
                usuario           VARCHAR(20),
                nome_campo        TEXT,
                valor_antigo      TEXT,
                valor_novo        TEXT,
                logsacessa        INTEGER,
                instit            INTEGER
            );
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_mudancas(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                sEsquema        TEXT,
                sTabela         TEXT,
                sUsuario        TEXT,
                iLogsAcessa     INTEGER,
                iInstit         INTEGER,
                sCampo          TEXT,
                sValorAntigo    TEXT,
                sValorNovo      TEXT
            ) RETURNS SETOF configuracoes.tp_auditoria_consulta_mudancas AS
            $$
            DECLARE
                rRetorno		configuracoes.tp_auditoria_consulta_mudancas;
                rAuditoria		RECORD;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConector		TEXT DEFAULT 'OR';
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := E'SELECT *, (select string_agg(coalesce((chave).nome_campo[id], \'NULL\') || \'=\' || coalesce((chave).valor[id], \'NULL\'), \'\\n\') from generate_series(1, array_upper((chave).nome_campo, 1)) as id) as chave_text   FROM configuracoes.db_auditoria ';
                sSQL := sSQL || ' WHERE datahora_servidor BETWEEN '||quote_literal(tDataHoraInicio::TEXT)||'::TIMESTAMPTZ AND '||quote_literal(tDataHoraFim::TEXT)||'::TIMESTAMPTZ';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
            
                IF sEsquema IS NOT NULL THEN
                    sSQL := sSQL || '   AND esquema = '||quote_literal(sEsquema);
                END IF;
            
                IF sTabela IS NOT NULL THEN
                    sSQL := sSQL || '   AND tabela  = '||quote_literal(sTabela);
                END IF;
            
                IF sUsuario IS NOT NULL THEN
                    sSQL := sSQL || '   AND usuario  = '||quote_literal(sUsuario);
                END IF;
            
                IF iLogsAcessa IS NOT NULL THEN
                    sSQL := sSQL || '   AND logsacessa  = '||cast(iLogsAcessa as text);
                END IF;
            
                IF sCampo IS NOT NULL AND (sValorAntigo IS NOT NULL OR sValorNovo IS NOT NULL) THEN
                    sSQL := sSQL || '   AND (((mudancas).nome_campo    @> ARRAY['||quote_literal(sCampo)||'] ';
                    sSQL := sSQL || '    OR   (chave).nome_campo       @> ARRAY['||quote_literal(sCampo)||']) ';
            
                    IF sValorAntigo IS NULL AND sValorNovo IS NOT NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_novo @> ARRAY['||quote_literal(sValorNovo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    ELSIF sValorAntigo IS NOT NULL AND sValorNovo IS NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'])) ';
                    ELSE
                        sSQL := sSQL || '   AND (((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] OR ';
                        sSQL := sSQL || '         (mudancas).valor_novo   @> ARRAY['||quote_literal(sValorNovo)||']) AND ';
                        sSQL := sSQL || '        (((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||' OR ';
                        sSQL := sSQL || '         ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||'::text, (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||'))';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'] OR (chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    END IF;
                END IF;
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE AND EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'dblink') THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    ELSE
                        PERFORM dblink_exec(sConexaoRemota, 'DISCARD ALL');
                    END IF;
                    PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rAuditoria
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (sequencial         integer,
                                    esquema            text,
                                    tabela             text,
                                    operacao           dm_operacao_tabela,
                                    transacao          bigint,
                                    datahora_sessao    timestamp with time zone,
                                    datahora_servidor  timestamp with time zone,
                                    tempo              interval,
                                    usuario            character varying(20),
                                    chave              tp_auditoria_chave_primaria,
                                    mudancas           tp_auditoria_mudancas_campo,
                                    logsacessa         integer,
                                    instit             integer,
                                    chave_text         text);
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        rRetorno.esquema           = rAuditoria.esquema;
                        rRetorno.tabela            = rAuditoria.tabela;
                        rRetorno.operacao          = rAuditoria.operacao;
                        rRetorno.chave             = rAuditoria.chave_text;
                        rRetorno.transacao         = rAuditoria.transacao;
                        rRetorno.datahora_sessao   = rAuditoria.datahora_sessao;
                        rRetorno.datahora_servidor = rAuditoria.datahora_servidor;
                        rRetorno.usuario           = rAuditoria.usuario;
                        rRetorno.logsacessa        = rAuditoria.logsacessa;
                        rRetorno.instit            = rAuditoria.instit;
            
                        iQtdMudancas := ARRAY_UPPER((rAuditoria.mudancas).nome_campo, 1);
            
                        FOR iMudanca IN 1..iQtdMudancas
                        LOOP
                            rRetorno.nome_campo   := (rAuditoria.mudancas).nome_campo[iMudanca];
                            rRetorno.valor_antigo := (rAuditoria.mudancas).valor_antigo[iMudanca];
                            rRetorno.valor_novo   := (rAuditoria.mudancas).valor_novo[iMudanca];
            
                            RETURN NEXT rRetorno;
                        END LOOP;
            
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rAuditoria;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        rRetorno.esquema           = rAuditoria.esquema;
                        rRetorno.tabela            = rAuditoria.tabela;
                        rRetorno.operacao          = rAuditoria.operacao;
                        rRetorno.chave             = rAuditoria.chave_text;
                        rRetorno.transacao         = rAuditoria.transacao;
                        rRetorno.datahora_sessao   = rAuditoria.datahora_sessao;
                        rRetorno.datahora_servidor = rAuditoria.datahora_servidor;
                        rRetorno.usuario           = rAuditoria.usuario;
                        rRetorno.logsacessa        = rAuditoria.logsacessa;
                        rRetorno.instit            = rAuditoria.instit;
            
                        iQtdMudancas := ARRAY_UPPER((rAuditoria.mudancas).nome_campo, 1);
            
                        FOR iMudanca IN 1..iQtdMudancas
                        LOOP
                            rRetorno.nome_campo   := (rAuditoria.mudancas).nome_campo[iMudanca];
                            rRetorno.valor_antigo := (rAuditoria.mudancas).valor_antigo[iMudanca];
                            rRetorno.valor_novo   := (rAuditoria.mudancas).valor_novo[iMudanca];
            
                            RETURN NEXT rRetorno;
                        END LOOP;
            
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_mudancas(
              tDataHoraInicio TIMESTAMP,
              tDataHoraFim    TIMESTAMP,
              sEsquema        TEXT,
              sTabela         TEXT,
              sUsuario        TEXT,
              iLogsAcessa     INTEGER,
              iInstit         INTEGER
            ) RETURNS SETOF configuracoes.tp_auditoria_consulta_mudancas AS
            $$
              SELECT *
                FROM configuracoes.fc_auditoria_consulta_mudancas($1, $2, $3, $4, $5, $6, $7, NULL, NULL, NULL);
            $$
            LANGUAGE sql;
            
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_logsacessa_consulta(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                iInstit         INTEGER,
                sWhere          TEXT
            ) RETURNS SETOF configuracoes.db_logsacessa AS
            $$
            DECLARE
                rRetorno		configuracoes.db_logsacessa;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := E'SELECT * FROM configuracoes.db_logsacessa';
                sSQL := sSQL || ' WHERE data BETWEEN '||quote_literal(tDataHoraInicio::DATE::TEXT)||'::DATE AND '||quote_literal(tDataHoraFim::DATE::TEXT)||'::DATE';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
                sSQL := sSQL || COALESCE(' AND '||sWhere, '');
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE AND EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'dblink') THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    ELSE
                        PERFORM dblink_exec(sConexaoRemota, 'DISCARD ALL');
                    END IF;
                    PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rRetorno
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (codsequen   integer,
                                    ip          character varying(50),
                                    data        date,
                                    hora        character varying(10),
                                    arquivo     text,
                                    obs         text,
                                    id_usuario  integer,
                                    id_modulo   integer,
                                    id_item     integer,
                                    coddepto    integer,
                                    instit      integer,
                                    auditoria   boolean);
            
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rRetorno;
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                --IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rRetorno;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rRetorno;
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                --END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
        ";

        $this->execute($sSql);
    }

    private function deletaConsultaAcesso()
    {
        $sSql = "
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_acessos(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                sEsquema        TEXT,
                sTabela         TEXT,
                sUsuario        TEXT,
                iInstit         INTEGER,
                sCampo          TEXT,
                sValorAntigo    TEXT,
                sValorNovo      TEXT
            ) RETURNS SETOF INTEGER AS
            $$
            DECLARE
                rRetorno		INTEGER;
                rAuditoria		RECORD;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConector		TEXT DEFAULT 'OR';
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := 'SELECT logsacessa FROM configuracoes.db_auditoria ';
                sSQL := sSQL || ' WHERE datahora_servidor BETWEEN '||quote_literal(tDataHoraInicio::TEXT)||'::TIMESTAMPTZ AND '||quote_literal(tDataHoraFim::TEXT)||'::TIMESTAMPTZ';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
            
                IF sEsquema IS NOT NULL THEN
                    sSQL := sSQL || '   AND esquema = '||quote_literal(sEsquema);
                END IF;
            
                IF sTabela IS NOT NULL THEN
                    sSQL := sSQL || '   AND tabela  = '||quote_literal(sTabela);
                END IF;
            
                IF sUsuario IS NOT NULL THEN
                    sSQL := sSQL || '   AND usuario  = '||quote_literal(sUsuario);
                END IF;
            
                IF sCampo IS NOT NULL AND (sValorAntigo IS NOT NULL OR sValorNovo IS NOT NULL) THEN
                    sSQL := sSQL || '   AND (((mudancas).nome_campo    @> ARRAY['||quote_literal(sCampo)||'] ';
                    sSQL := sSQL || '    OR   (chave).nome_campo       @> ARRAY['||quote_literal(sCampo)||']) ';
            
                    IF sValorAntigo IS NULL AND sValorNovo IS NOT NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_novo @> ARRAY['||quote_literal(sValorNovo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    ELSIF sValorAntigo IS NOT NULL AND sValorNovo IS NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'])) ';
                    ELSE
                        sSQL := sSQL || '   AND (((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] OR ';
                        sSQL := sSQL || '         (mudancas).valor_novo   @> ARRAY['||quote_literal(sValorNovo)||']) AND ';
                        sSQL := sSQL || '        (((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||' OR ';
                        sSQL := sSQL || '         ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||'))';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'] OR (chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    END IF;
                END IF;
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    END IF;
                    PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rAuditoria
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (sequencial         integer,
                                    esquema            text,
                                    tabela             text,
                                    operacao           dm_operacao_tabela,
                                    transacao          bigint,
                                    datahora_sessao    timestamp with time zone,
                                    datahora_servidor  timestamp with time zone,
                                    tempo              interval,
                                    usuario            character varying(20),
                                    chave              tp_auditoria_chave_primaria,
                                    mudancas           tp_auditoria_mudancas_campo,
                                    logsacessa         integer,
                                    instit             integer);
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rAuditoria.logsacessa;
            
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rAuditoria;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rAuditoria.logsacessa;
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_acessos(
              tDataHoraInicio TIMESTAMP,
              tDataHoraFim    TIMESTAMP,
              sEsquema        TEXT,
              sTabela         TEXT,
              sUsuario        TEXT,
              iInstit         INTEGER
            ) RETURNS SETOF INTEGER AS
            $$
              SELECT *
                FROM configuracoes.fc_auditoria_consulta_acessos($1, $2, $3, $4, $5, $6, NULL, NULL, NULL);
            $$
            LANGUAGE sql;
        ";

        $this->execute($sSql);
    }

    private function deletaConsultaMudancas()
    {
        $sSql = "
            DROP TYPE IF EXISTS configuracoes.tp_auditoria_consulta_mudancas CASCADE;
            CREATE TYPE configuracoes.tp_auditoria_consulta_mudancas AS (
                esquema           TEXT,
                tabela            TEXT,
                operacao          CHAR(1),
                chave             VARCHAR,
                transacao         BIGINT,
                datahora_sessao   TIMESTAMP WITH TIME ZONE,
                datahora_servidor TIMESTAMP WITH TIME ZONE,
                usuario           VARCHAR(20),
                nome_campo        TEXT,
                valor_antigo      TEXT,
                valor_novo        TEXT,
                logsacessa        INTEGER,
                instit            INTEGER
            );
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_mudancas(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                sEsquema        TEXT,
                sTabela         TEXT,
                sUsuario        TEXT,
                iLogsAcessa     INTEGER,
                iInstit         INTEGER,
                sCampo          TEXT,
                sValorAntigo    TEXT,
                sValorNovo      TEXT
            ) RETURNS SETOF configuracoes.tp_auditoria_consulta_mudancas AS
            $$
            DECLARE
                rRetorno		configuracoes.tp_auditoria_consulta_mudancas;
                rAuditoria		RECORD;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConector		TEXT DEFAULT 'OR';
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := E'SELECT *, (select string_agg(coalesce((chave).nome_campo[id], \'NULL\') || \'=\' || coalesce((chave).valor[id], \'NULL\'), \'\\n\') from generate_series(1, array_upper((chave).nome_campo, 1)) as id) as chave_text   FROM configuracoes.db_auditoria ';
                sSQL := sSQL || ' WHERE datahora_servidor BETWEEN '||quote_literal(tDataHoraInicio::TEXT)||'::TIMESTAMPTZ AND '||quote_literal(tDataHoraFim::TEXT)||'::TIMESTAMPTZ';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
            
                IF sEsquema IS NOT NULL THEN
                    sSQL := sSQL || '   AND esquema = '||quote_literal(sEsquema);
                END IF;
            
                IF sTabela IS NOT NULL THEN
                    sSQL := sSQL || '   AND tabela  = '||quote_literal(sTabela);
                END IF;
            
                IF sUsuario IS NOT NULL THEN
                    sSQL := sSQL || '   AND usuario  = '||quote_literal(sUsuario);
                END IF;
            
                IF iLogsAcessa IS NOT NULL THEN
                    sSQL := sSQL || '   AND logsacessa  = '||cast(iLogsAcessa as text);
                END IF;
            
                IF sCampo IS NOT NULL AND (sValorAntigo IS NOT NULL OR sValorNovo IS NOT NULL) THEN
                    sSQL := sSQL || '   AND (((mudancas).nome_campo    @> ARRAY['||quote_literal(sCampo)||'] ';
                    sSQL := sSQL || '    OR   (chave).nome_campo       @> ARRAY['||quote_literal(sCampo)||']) ';
            
                    IF sValorAntigo IS NULL AND sValorNovo IS NOT NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_novo @> ARRAY['||quote_literal(sValorNovo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    ELSIF sValorAntigo IS NOT NULL AND sValorNovo IS NULL THEN
                        sSQL := sSQL || '   AND ((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] AND ';
                        sSQL := sSQL || '        ((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||') ';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'])) ';
                    ELSE
                        sSQL := sSQL || '   AND (((mudancas).valor_antigo @> ARRAY['||quote_literal(sValorAntigo)||'] OR ';
                        sSQL := sSQL || '         (mudancas).valor_novo   @> ARRAY['||quote_literal(sValorNovo)||']) AND ';
                        sSQL := sSQL || '        (((mudancas).valor_antigo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorAntigo)||' OR ';
                        sSQL := sSQL || '         ((mudancas).valor_novo)[array_position('||quote_literal(sCampo)||', (mudancas).nome_campo)] = '||quote_literal(sValorNovo)||'))';
                        sSQL := sSQL || '    OR ((chave).valor @> ARRAY['||quote_literal(sValorAntigo)||'] OR (chave).valor @> ARRAY['||quote_literal(sValorNovo)||'])) ';
                    END IF;
                END IF;
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE AND EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'dblink') THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    ELSE
                        PERFORM dblink_exec(sConexaoRemota, 'DISCARD ALL');
                    END IF;
                    PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rAuditoria
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (sequencial         integer,
                                    esquema            text,
                                    tabela             text,
                                    operacao           dm_operacao_tabela,
                                    transacao          bigint,
                                    datahora_sessao    timestamp with time zone,
                                    datahora_servidor  timestamp with time zone,
                                    tempo              interval,
                                    usuario            character varying(20),
                                    chave              tp_auditoria_chave_primaria,
                                    mudancas           tp_auditoria_mudancas_campo,
                                    logsacessa         integer,
                                    instit             integer,
                                    chave_text         text);
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        rRetorno.esquema           = rAuditoria.esquema;
                        rRetorno.tabela            = rAuditoria.tabela;
                        rRetorno.operacao          = rAuditoria.operacao;
                        rRetorno.chave             = rAuditoria.chave_text;
                        rRetorno.transacao         = rAuditoria.transacao;
                        rRetorno.datahora_sessao   = rAuditoria.datahora_sessao;
                        rRetorno.datahora_servidor = rAuditoria.datahora_servidor;
                        rRetorno.usuario           = rAuditoria.usuario;
                        rRetorno.logsacessa        = rAuditoria.logsacessa;
                        rRetorno.instit            = rAuditoria.instit;
            
                        iQtdMudancas := ARRAY_UPPER((rAuditoria.mudancas).nome_campo, 1);
            
                        FOR iMudanca IN 1..iQtdMudancas
                        LOOP
                            rRetorno.nome_campo   := (rAuditoria.mudancas).nome_campo[iMudanca];
                            rRetorno.valor_antigo := (rAuditoria.mudancas).valor_antigo[iMudanca];
                            rRetorno.valor_novo   := (rAuditoria.mudancas).valor_novo[iMudanca];
            
                            RETURN NEXT rRetorno;
                        END LOOP;
            
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rAuditoria;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        rRetorno.esquema           = rAuditoria.esquema;
                        rRetorno.tabela            = rAuditoria.tabela;
                        rRetorno.operacao          = rAuditoria.operacao;
                        rRetorno.chave             = rAuditoria.chave_text;
                        rRetorno.transacao         = rAuditoria.transacao;
                        rRetorno.datahora_sessao   = rAuditoria.datahora_sessao;
                        rRetorno.datahora_servidor = rAuditoria.datahora_servidor;
                        rRetorno.usuario           = rAuditoria.usuario;
                        rRetorno.logsacessa        = rAuditoria.logsacessa;
                        rRetorno.instit            = rAuditoria.instit;
            
                        iQtdMudancas := ARRAY_UPPER((rAuditoria.mudancas).nome_campo, 1);
            
                        FOR iMudanca IN 1..iQtdMudancas
                        LOOP
                            rRetorno.nome_campo   := (rAuditoria.mudancas).nome_campo[iMudanca];
                            rRetorno.valor_antigo := (rAuditoria.mudancas).valor_antigo[iMudanca];
                            rRetorno.valor_novo   := (rAuditoria.mudancas).valor_novo[iMudanca];
            
                            RETURN NEXT rRetorno;
                        END LOOP;
            
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_consulta_mudancas(
              tDataHoraInicio TIMESTAMP,
              tDataHoraFim    TIMESTAMP,
              sEsquema        TEXT,
              sTabela         TEXT,
              sUsuario        TEXT,
              iLogsAcessa     INTEGER,
              iInstit         INTEGER
            ) RETURNS SETOF configuracoes.tp_auditoria_consulta_mudancas AS
            $$
              SELECT *
                FROM configuracoes.fc_auditoria_consulta_mudancas($1, $2, $3, $4, $5, $6, $7, NULL, NULL, NULL);
            $$
            LANGUAGE sql;
            
            
            CREATE OR REPLACE FUNCTION configuracoes.fc_logsacessa_consulta(
                tDataHoraInicio TIMESTAMP,
                tDataHoraFim    TIMESTAMP,
                iInstit         INTEGER,
                sWhere          TEXT
            ) RETURNS SETOF configuracoes.db_logsacessa AS
            $$
            DECLARE
                rRetorno		configuracoes.db_logsacessa;
            
                rCursorRetorno	REFCURSOR;
            
                iQtdMudancas	INTEGER;
                iMudanca		INTEGER;
            
                sSQL			TEXT;
                sConexaoRemota	TEXT;
                sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';
            
                tInicioAno				TIMESTAMPTZ;
                lExisteBaseAuditoria	BOOLEAN;
            BEGIN
                lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);
            
                sSQL := E'SELECT * FROM configuracoes.db_logsacessa';
                sSQL := sSQL || ' WHERE data BETWEEN '||quote_literal(tDataHoraInicio::DATE::TEXT)||'::DATE AND '||quote_literal(tDataHoraFim::DATE::TEXT)||'::DATE';
                sSQL := sSQL || '   AND instit  = '||iInstit::TEXT;
                sSQL := sSQL || COALESCE(' AND '||sWhere, '');
            
                tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;
            
                -- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
                -- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
                IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE AND EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'dblink') THEN
                    sConexaoRemota := 'auditoria';
                    IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
                        PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
                    ELSE
                        PERFORM dblink_exec(sConexaoRemota, 'DISCARD ALL');
                    END IF;
                    PERFORM dblink_open(sConexaoRemota, 'log', sSQL);
            
                    LOOP
                        SELECT	*
                        INTO	rRetorno
                        FROM	dblink_fetch(sConexaoRemota, 'log', 1)
                                AS (codsequen   integer,
                                    ip          character varying(50),
                                    data        date,
                                    hora        character varying(10),
                                    arquivo     text,
                                    obs         text,
                                    id_usuario  integer,
                                    id_modulo   integer,
                                    id_item     integer,
                                    coddepto    integer,
                                    instit      integer,
                                    auditoria   boolean);
            
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rRetorno;
                    END LOOP;
            
                    PERFORM dblink_close(sConexaoRemota, 'log');
                END IF;
            
                -- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
                -- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
                --IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
            
                    OPEN rCursorRetorno FOR EXECUTE sSQL;
            
                    LOOP
                        FETCH rCursorRetorno INTO rRetorno;
                        IF NOT FOUND THEN
                            EXIT;
                        END IF;
            
                        RETURN NEXT rRetorno;
                    END LOOP;
            
                    CLOSE rCursorRetorno;
                --END IF;
            
                RETURN;
            END;
            $$
            LANGUAGE plpgsql;
        ";

        $this->execute($sSql);
    }
}
