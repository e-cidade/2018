<?php

use Classes\PostgresMigration;

class M8507Auditoria extends PostgresMigration
{

  public function up(){
    $sql = <<<'SQL'
    
--utils
-- Funcao para fazer um IF simples
create or replace function fc_iif(boolean, anyelement, anyelement) returns anyelement as
$$
  select case when $1 is true then $2 else $3 end;
$$
language 'sql';


-- Funcao que retorna o Maior entre 2 valores
create or replace function fc_max(anyelement, anyelement) returns anyelement as
$$
  select case when $1 >= $2 then $1 else $2 end;
$$
language 'sql';


-- Funcao que retorna o Menor entre 2 valores
create or replace function fc_min(anyelement, anyelement) returns anyelement as
$$
  select case when $1 <= $2 then $1 else $2 end;
$$
language 'sql';


-- Funcao que retorna o Maior entre 3 valores
create or replace function fc_max(anyelement, anyelement, anyelement) returns anyelement as
$$
  select fc_max($1, fc_max($2, $3));
$$
language 'sql';

-- Funcao que retorna o Menor entre 3 valores
create or replace function fc_min(anyelement, anyelement, anyelement) returns anyelement as
$$
  select fc_min($1, fc_min($2, $3));
$$
language 'sql';

-- Funcao que retorna uma string em Maiusculas incluindo acentos e cedilha
create or replace function fc_upper(text) returns text as
$$
  select translate( upper($1),
         text 'áéíóúàèìòùãõâêîôôäëïöüç',
         text 'ÁÉÍÓÚÀÈÌÒÙÃÕÂÊÎÔÛÄËÏÖÜÇ')
$$ 
language 'sql' ;


-- Funcao que retorna uma string em Minusculas incluindo acentos e cedilha
create or replace function fc_lower(text) returns text as
$$
  select translate( lower($1),
         text 'ÁÉÍÓÚÀÈÌÒÙÃÕÂÊÎÔÛÄËÏÖÜÇ',
         text 'áéíóúàèìòùãõâêîôôäëïöüç')
$$ 
language 'sql' ;


-- Funcao para converter um TIME para um VALOR FLOAT
create or replace function fc_time_base10(interval) returns float as
$$
  select extract(epoch from $1)/extract(epoch from interval '01:00:00');
$$
language 'sql';


-- Funcao para gerar uma lista de datas dado um determinado periodo
create or replace function fc_datelist(date, date, integer) returns setof date as
$$
  select $1 + x
    from generate_series(0, ($2 - $1), $3) as x;
$$
language 'sql';

create or replace function fc_datelist(date, date) returns setof date as
$$
  select *
    from fc_datelist($1, $2, 1);
$$
language 'sql';


-- Funcao que retorna o número de meses entre duas datas (data inicio e data fim)
create or replace function fc_conta_meses (date, date) returns smallint as
$$
declare
  dDataIni  alias for $1;
  dDataFim  alias for $2;
  iMeses    int;
  iAnoIni   int;
  iMesIni   int;
  iAnoFim   int;
  iMesFim   int;
  iAnos     int;
  iQtdMeses int;
begin
  iAnoIni := extract(year  from dDataIni);
  iMesIni := extract(month from dDataIni);

  iAnoFim := extract(year  from dDataFim);
  iMesFim := extract(month from dDataFim);

  iAnos     := (iAnoFim - iAnoIni) * 12;
  iMeses    := (iMesFim - iMesIni) + 1;
  iQtdMeses := (iMeses  + iAnos)   - 1;

  return iQtdMeses;
end;
$$ 
language plpgsql;

-- Funcao que retorna a versao do postgresql como um integer (retirado da contrib dbilink)
create or replace function fc_pgversion() returns integer as
$$
select
    sum(
        pg_catalog.substring(
            pg_catalog.split_part(
                pg_catalog.current_setting(
                    'server_version'
                ),
                '.',
                i
            ),
            '^[[:digit:]]+'
        )::numeric * 10^(6-i*2)
    )::integer as server_version_integer
from
    generate_series(1,3) as s(i);
$$
language sql;

-- Funcao que retorna uma versao (1.1 = 10100, 2.1.97 = 20197 como um integer (baseado na contrib dbilink)
create or replace function fc_version_base10(text, integer) returns integer as
$$
  select sum(split_part($1, '.', i)::numeric * 10^($2-i*2))::integer
    from generate_series(1, (array_upper(string_to_array($1, '.'), 1))) as s(i);
$$
language sql;

create or replace function fc_version_base10(text) returns integer as
$$
  select fc_version_base10($1, 6);
$$
language sql;


-- Remove Acentos (agudo, crase, circunflexo), Tils, Tremas ou Cedilhas de uma string
create or replace function fc_remove_acentos(text) returns text as
$$
declare

  sString text;
  
begin

  sString := $1;

  sString := translate(sString, 'á', 'a');
  sString := translate(sString, 'à', 'a');
  sString := translate(sString, 'ã', 'a');
  sString := translate(sString, 'ä', 'a');
  sString := translate(sString, 'â', 'a');
  
  sString := translate(sString, 'Á', 'A');
  sString := translate(sString, 'À', 'A');
  sString := translate(sString, 'Ã', 'A');
  sString := translate(sString, 'Â', 'A');
  sString := translate(sString, 'Ä', 'A');
  
  sString := translate(sString, 'é', 'e');
  sString := translate(sString, 'è', 'e');
  sString := translate(sString, 'ê', 'e');
  sString := translate(sString, 'ë', 'e');

  sString := translate(sString, 'É', 'E');
  sString := translate(sString, 'È', 'E');
  sString := translate(sString, 'Ê', 'E');
  sString := translate(sString, 'Ë', 'E');

  sString := translate(sString, 'í', 'i');
  sString := translate(sString, 'ì', 'i');
  sString := translate(sString, 'î', 'i');
  sString := translate(sString, 'ï', 'i');

  sString := translate(sString, 'Í', 'I');
  sString := translate(sString, 'Ì', 'I');
  sString := translate(sString, 'Î', 'I');
  sString := translate(sString, 'Ï', 'I');

  sString := translate(sString, 'ó', 'o');
  sString := translate(sString, 'ò', 'o');
  sString := translate(sString, 'õ', 'o');
  sString := translate(sString, 'ô', 'o');
  sString := translate(sString, 'ö', 'o');

  sString := translate(sString, 'Ó', 'O');
  sString := translate(sString, 'Ò', 'O');
  sString := translate(sString, 'Õ', 'O');
  sString := translate(sString, 'Ô', 'O');
  sString := translate(sString, 'Ö', 'O');

  sString := translate(sString, 'ú', 'u');
  sString := translate(sString, 'ù', 'u');
  sString := translate(sString, 'û', 'u');
  sString := translate(sString, 'ü', 'u');

  sString := translate(sString, 'Ú', 'U');
  sString := translate(sString, 'Ù', 'U');
  sString := translate(sString, 'Û', 'U');
  sString := translate(sString, 'Ü', 'U');

  sString := translate(sString, 'º', 'o');
  sString := translate(sString, '&', 'e');
  sString := translate(sString, 'ç', 'c');
  sString := translate(sString, 'Ç', 'C');

  return sString;

end;
  
$$ language 'plpgsql';

-- Funcao Generica para Ordenar um Array (by David Fetter)
create or replace function array_sort (anyarray) returns anyarray
as $$
  select array(
    select $1[s.i] as "foo"
      from generate_series(array_lower($1,1), array_upper($1,1)) as s(i)
     order by foo
  );
$$
language sql;

-- Funcao utilizada no ETL do BI
create or replace function tiracaracteres(text) returns text
as $$
  select pg_catalog.translate($1,
    'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇªºäÄ§°+*!@#$%&{}[]?|"\'',
    'aeiouAEIOUaAAaEeoOuUiIoOnNaAoOcCaoaA                  ');
$$
language sql immutable;

-- Funcao para retornar o indice (posicao) de um elemento dentro de um array
create or replace function array_position(anyelement, anyarray) returns integer
as $$
  select i
    from generate_series(1, array_upper($2,1)) as i
   where $2[i] = $1;
$$
language sql;

-- Funcao para verificar se uma string é um número válido (considerando decimais)
create or replace function fc_isnumeric(text) returns boolean as
$$
declare
  x numeric;
begin
  x = $1::numeric;
  return true;
exception when others then
  return false;
end;
$$
language plpgsql immutable;

-- Funcao para fazer multiplas substituicoes em uma string dado array de procura e substituicao
create or replace function fc_replace_multi(text, aProcura text[], aSubstitui text[]) returns text as
$$
declare
  iConta integer;
  iLinha integer;
  sAlvo  text;
begin
  sAlvo  := $1;
  iConta := array_upper(aProcura, 1);

  if iConta <> array_upper(aSubstitui, 1) then
    raise exception 'Quantidade de strings de procura diferente da quantidade a substituir';
  end if;
  
  for iLinha in 1..iConta
  loop
    sAlvo := replace(sAlvo, aProcura[iLinha], aSubstitui[iLinha]);
  end loop;

  return sAlvo;
end;
$$
language plpgsql;


--02_auditoria_particiona
/***
 *
 *  fc_auditoria_particao_cria()
 * 
 *  . FunÃ§Ã£o acessora responsÃ¡vel pela criaÃ§Ã£o da partiÃ§Ã£o (clonar tabela) 
 * 
 *
 */

CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particao_cria (
  sEsquema         TEXT,
  sTabela          TEXT,
  sEsquemaParticao TEXT,
  sTabelaParticao  TEXT,
  sCheck           TEXT
) RETURNS void AS
$$
DECLARE
  sSQL TEXT;
BEGIN

  IF fc_clone_table(sEsquema||'.'||sTabela, sEsquemaParticao||'.'||sTabelaParticao, null, true) IS TRUE THEN

    sSQL := 'ALTER TABLE '||sEsquemaParticao||'.'||sTabelaParticao;
    sSQL := sSQL || ' ADD CONSTRAINT '||sTabelaParticao||'_datahora_servidor_ck';
    sSQL := sSQL || ' CHECK ('||sCheck||');';

    EXECUTE sSQL;

  END IF;

  RETURN;
END;
$$
LANGUAGE plpgsql;

/***
 *
 *  fc_auditoria_particiona_inc()
 * 
 *  . FunÃ§Ã£o responsÃ¡vel pelo particionamento da tabela configuracoes.db_auditoria
 *    em segmentos de ANO/MES/INSTITUICAO, ex:
 * 
 *     configuracoes.db_auditoria_201101_1
 *     configuracoes.db_auditoria_201101_2
 *     configuracoes.db_auditoria_201102_1
 *     configuracoes.db_auditoria_??????_?
 * 
 *    A chave para determinar o particionamento Ã© o atributo "datahora_servidor" e "instit"
 *
 */
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particiona_inc() RETURNS trigger AS
$$
DECLARE
  sEsquema TEXT;
  sTabela  TEXT;

  sEsquemaParticao TEXT;
  sTabelaParticao  TEXT;

  sDataIni    TEXT;
  sDataFim    TEXT;
  iAno        INTEGER;
  iMes        INTEGER;
  
  sSQL        TEXT;
BEGIN

  sEsquema := TG_TABLE_SCHEMA;
  sTabela  := TG_TABLE_NAME;
  iAno     := extract(year  from NEW.datahora_servidor);
  iMes     := extract(month from NEW.datahora_servidor);
  sDataIni := iAno::TEXT || '-' || iMes::TEXT || '-01 00:00:00.000000';
  sDataFim := iAno::TEXT || '-' || iMes::TEXT || '-' || fc_ultimodiames(iAno, iMes)::TEXT || ' 23:59:59.999999';

  sEsquemaParticao := COALESCE(fc_getsession('db_esquema_auditoria_particao'), sEsquema);
  sTabelaParticao  := sTabela || '_' ||
    to_char(iAno, 'FM0000') ||
    to_char(iMes, 'FM00') || '_' ||
    NEW.instit::TEXT;

  PERFORM configuracoes.fc_auditoria_particao_cria (
    sEsquema,
    sTabela,
    sEsquemaParticao,
    sTabelaParticao,
    'datahora_servidor BETWEEN '||quote_literal(sDataIni)||' AND '||quote_literal(sDataFim)|| ' AND instit = ' || NEW.instit::TEXT
  );

  sSQL := FORMAT('INSERT INTO %I.%I ('
    || ' sequencial, '
    || ' esquema, '
    || ' tabela, '
    || ' operacao, '
    || ' datahora_sessao, '
    || ' datahora_servidor, '
    || ' tempo, '
    || ' usuario, '
    || ' chave, '
    || ' mudancas, '
    || ' logsacessa, '
    || ' instit '
    || ') VALUES ( '
    || '$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)', sEsquemaParticao, sTabelaParticao);

  IF NEW.sequencial IS NULL THEN
    -- Usar sequence do acount para compatibilidade
    NEW.sequencial := NEXTVAL('db_acount_id_acount_seq');
  END IF;

  EXECUTE sSQL
    USING NEW.sequencial, NEW.esquema, NEW.tabela, NEW.operacao, NEW.datahora_sessao, NEW.datahora_servidor,
          (clock_timestamp() - COALESCE(CAST(fc_getsession('clock_timestamp') AS TIMESTAMP WITH TIME ZONE), NOW())),
          NEW.usuario, NEW.chave, NEW.mudancas, NEW.logsacessa, NEW.instit;

  RETURN NULL;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tg_auditoria_particiona_inc ON configuracoes.db_auditoria;
CREATE TRIGGER tg_auditoria_particiona_inc BEFORE INSERT ON configuracoes.db_auditoria
  FOR EACH ROW EXECUTE PROCEDURE configuracoes.fc_auditoria_particiona_inc();

/***
 *
 *  fc_logsacessa_particiona_inc()
 * 
 *  . FunÃ§Ã£o responsÃ¡vel pelo particionamento da tabela configuracoes.db_logsacessa
 *    em segmentos de ANO/MES/INSTITUICAO, ex:
 * 
 *     configuracoes.db_logsacessa_201101_1
 *     configuracoes.db_logsacessa_201101_2
 *     configuracoes.db_logsacessa_201102_1
 *     configuracoes.db_logsacessa_??????_?
 * 
 *    A chave para determinar o particionamento Ã© o atributo "data" e "instit"
 *
 */
CREATE OR REPLACE FUNCTION configuracoes.fc_logsacessa_particiona_inc() RETURNS trigger AS
$$
DECLARE
  sEsquema TEXT;
  sTabela  TEXT;

  sEsquemaParticao TEXT;
  sTabelaParticao  TEXT;

  sDataIni    TEXT;
  sDataFim    TEXT;
  iAno        INTEGER;
  iMes        INTEGER;
  iInstit     INTEGER;
  
  sSQL        TEXT;
BEGIN

  sEsquema := TG_TABLE_SCHEMA;
  sTabela  := TG_TABLE_NAME;
  iAno     := extract(year  from NEW.data);
  iMes     := extract(month from NEW.data);
  sDataIni := iAno::TEXT || '-' || iMes::TEXT || '-01';
  sDataFim := iAno::TEXT || '-' || iMes::TEXT || '-' || fc_ultimodiames(iAno, iMes)::TEXT;
  iInstit  := coalesce(NEW.instit, 0);

  sEsquemaParticao := COALESCE(fc_getsession('db_esquema_auditoria_particao'), sEsquema);
  sTabelaParticao  := sTabela || '_' ||
    to_char(iAno, 'FM0000') ||
    to_char(iMes, 'FM00') || '_' ||
    iInstit::TEXT;

  PERFORM configuracoes.fc_auditoria_particao_cria (
    sEsquema,
    sTabela,
    sEsquemaParticao,
    sTabelaParticao,
    'data BETWEEN '||quote_literal(sDataIni)||' AND '||quote_literal(sDataFim)|| ' AND instit = ' || iInstit::TEXT
  );

  sSQL := FORMAT('INSERT INTO %I.%I ('
    || ' codsequen, '
    || ' ip, '
    || ' data, '
    || ' hora, '
    || ' arquivo, '
    || ' obs, '
    || ' id_usuario, '
    || ' id_modulo, '
    || ' id_item, '
    || ' coddepto, '
    || ' instit, '
    || ' auditoria '
    || ') VALUES ( '
    || '$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)', sEsquemaParticao, sTabelaParticao);

  IF NEW.codsequen IS NULL THEN
    NEW.codsequen := NEXTVAL('db_logsacessa_codsequen_seq');
  END IF;

  EXECUTE sSQL
    USING NEW.codsequen, NEW.ip, NEW.data, NEW.hora, NEW.arquivo, NEW.obs, NEW.id_usuario,
          NEW.id_modulo, NEW.id_item, NEW.coddepto, iInstit, NEW.auditoria;

  RETURN NULL;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tg_logsacessa_particiona_inc ON configuracoes.db_logsacessa;
CREATE TRIGGER tg_logsacessa_particiona_inc BEFORE INSERT ON configuracoes.db_logsacessa
  FOR EACH ROW EXECUTE PROCEDURE configuracoes.fc_logsacessa_particiona_inc();


--03_auditoria_template    
/***
 *
 *  TEMPLATE de PL para auditoria de tabelas
 *
 *  Variáveis do Template
 *   . %tpl_tabela_esquema               = nome do esquema da tabela a ser auditada
 *   . %tpl_tabela_nome                  = nome da tabela a ser auditada
 *   . %tpl_bloco_codigo_definicao_chave = bloco de codigo da definicao da chave (xChave)
 *   . %tpl_bloco_codigo_update          = bloco de codigo a ser processado no UPDATE para montar Array com valores realmente alterados
 *   . %tpl_array_campo_nome             = definicao de array com nome dos campos da tabela a ser auditada
 *   . %tpl_array_insert_campo_valor_old = definicao de array com valores OLD dos campos para INSERT
 *   . %tpl_array_insert_campo_valor_new = definicao de array com valores NEW dos campos para INSERT
 *   . %tpl_array_delete_campo_valor_old = definicao de array com valores OLD dos campos para DELETE
 *   . %tpl_array_delete_campo_valor_new = definicao de array com valores NEW dos campos para DELETE
 *
 */


CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_template() RETURNS TEXT AS
$$
  SELECT
E'CREATE OR REPLACE FUNCTION {%tpl_tabela_esquema}.fc_{%tpl_tabela_nome}_auditoria() RETURNS trigger AS
\$\$
DECLARE
  xMudancas configuracoes.tp_auditoria_mudancas_campo;
  xChave    configuracoes.tp_auditoria_chave_primaria;

  tDataHora   TIMESTAMP   DEFAULT (COALESCE(fc_getsession(\'DB_datausu\')::TIMESTAMP, NOW()));
  sLogin      VARCHAR(20) DEFAULT fc_getsession(\'DB_login\');
  iLogsAcessa INTEGER     DEFAULT (NULLIF(fc_getsession(\'DB_acessado\'), \'\')::INTEGER);
  iInstit     INTEGER     DEFAULT (NULLIF(fc_getsession(\'DB_instit\'), \'\')::INTEGER);

  rRegistro   {%tpl_tabela_esquema}.{%tpl_tabela_nome}%ROWTYPE;

  aCampo      TEXT[];
  aValorOld   TEXT[];
  aValorNew   TEXT[];
BEGIN

  IF fc_getsession(\'__disable_audit__\') IS NOT NULL OR
     fc_getsession(\'__disable_audit_{%tpl_tabela_esquema}_{%tpl_tabela_nome}__\') IS NOT NULL THEN
    IF TG_OP = \'DELETE\' THEN
      RETURN OLD;
    END IF;

    RETURN NEW;
  END IF;

  PERFORM fc_putsession(\'clock_timestamp\', clock_timestamp()::TEXT);

  IF TG_OP = \'DELETE\' THEN
    rRegistro := OLD;
  ELSE
    rRegistro := NEW;
  END IF;

  IF iInstit IS NULL THEN
    SELECT codigo
      INTO iInstit
      FROM configuracoes.db_config
     WHERE prefeitura IS TRUE
     ORDER BY codigo
     LIMIT 1;

    IF iInstit IS NULL THEN
      SELECT codigo
        INTO iInstit
        FROM configuracoes.db_config
       ORDER BY codigo
       LIMIT 1;
      IF iInstit IS NULL THEN
        RAISE EXCEPTION \'Impossível realizar auditoria. Nenhuma instituição presente nesta base de dados!\';
      END IF;
    END IF; 
  END IF;

  {%tpl_bloco_codigo_definicao_chave}

  IF TG_OP = \'INSERT\' THEN

    xMudancas := ROW(
      ARRAY[ {%tpl_array_campo_nome} ],
      ARRAY[ {%tpl_array_insert_campo_valor_old} ],
      ARRAY[ {%tpl_array_insert_campo_valor_new} ] ); 
  
  ELSIF TG_OP = \'UPDATE\' THEN

    IF ROW(OLD.*) IS DISTINCT FROM ROW(NEW.*) THEN

{%tpl_bloco_codigo_update}

    ELSE
      RETURN NEW;
    END IF;

    xMudancas := ROW(aCampo, aValorOld, aValorNew);
  ELSE

    xMudancas := ROW(
      ARRAY[ {%tpl_array_campo_nome} ],
      ARRAY[ {%tpl_array_delete_campo_valor_old} ],
      ARRAY[ {%tpl_array_delete_campo_valor_new} ] ); 

  END IF;

  INSERT INTO configuracoes.db_auditoria (
    sequencial,
    esquema, 
    tabela, 
    operacao, 
    datahora_sessao, 
    usuario, 
    chave, 
    mudancas, 
    logsacessa, 
    instit
  ) VALUES (
    NEXTVAL(\'db_acount_id_acount_seq\'), -- FUTURAMENTE REMOVER
    TG_TABLE_SCHEMA, 
    TG_TABLE_NAME, 
    SUBSTR(TG_OP,1,1), 
    tDataHora, 
    sLogin,
    xChave,
    xMudancas,
    iLogsAcessa,
    iInstit 
  );

  IF TG_OP = \'DELETE\' THEN
    RETURN OLD;
  END IF;

  RETURN NEW;
END;
\$\$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tg_{%tpl_tabela_nome}_auditoria ON {%tpl_tabela_esquema}.{%tpl_tabela_nome};
CREATE TRIGGER tg_{%tpl_tabela_nome}_auditoria AFTER INSERT OR UPDATE OR DELETE ON {%tpl_tabela_esquema}.{%tpl_tabela_nome}
  FOR EACH ROW EXECUTE PROCEDURE {%tpl_tabela_esquema}.fc_{%tpl_tabela_nome}_auditoria(); '::TEXT;

$$
LANGUAGE sql;


--04_auditoria_cria_funcao

CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_cria_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
  sEsquema TEXT;
  sTabela  TEXT;
  aTabela  TEXT[];

  aProcura   TEXT[];
  aSubstitui TEXT[];

  sTemplate  TEXT;
  sColunas   TEXT;
  sNulls     TEXT;
  sValores   TEXT;

  sBlocoUpdate TEXT;
  sBlocoChave  TEXT;

  rTabela    RECORD;
BEGIN

  -- Separa Esquema e Tabela
  IF position('.' in $1) > 0 THEN
    aTabela  := string_to_array($1, '.');
    sEsquema := aTabela[1];
    sTabela  := aTabela[2];
  ELSE
    sEsquema := 'public';
    sTabela  := $1;
  END IF;

  FOR rTabela IN
    SELECT esquema,
           nome
      FROM configuracoes.vw_auditoria_lista_tabelas
     WHERE esquema LIKE sEsquema
       AND nome    LIKE sTabela
  LOOP
    aProcura   := '{}';
    aSubstitui := '{}';

    -- Variaveis para macro-substituicao
    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_tabela_esquema}');
    aSubstitui := ARRAY_APPEND(aSubstitui, rTabela.esquema::TEXT);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_tabela_nome}');
    aSubstitui := ARRAY_APPEND(aSubstitui, rTabela.nome::TEXT);

    -- Carrega template de PL de auditoria
    sTemplate := configuracoes.fc_auditoria_template();

    -- Monta Bloco de Codigo da Chave Primaria, se existir
    SELECT 'xChave := ROW( ARRAY['||
           array_to_string(array_accum(quote_literal(attname::TEXT)), ', ')||'], ARRAY['||
           array_to_string(array_accum('rRegistro.'::TEXT||attname::TEXT||'::TEXT'), ', ')||'] );'
      INTO sBlocoChave
      FROM pg_class a
           INNER JOIN pg_constraint b  ON b.conrelid = a.oid
           INNER JOIN pg_namespace c   ON c.oid      = a.relnamespace
           INNER JOIN pg_attribute t   ON t.attrelid = b.conrelid
                                      AND t.attnum   = ANY(b.conkey)
     WHERE c.nspname = rTabela.esquema
       AND a.relname = rTabela.nome
       AND b.contype = 'p';

    IF sBlocoChave = 'xChave := ROW( ARRAY[], ARRAY[] );' THEN
      sBlocoChave := '';
    END IF;

    -- Colunas da tabela e Bloco de Código para UPDATE
    -- @TODO: Usar "encode" para colunas do tipo BYTEA
    SELECT array_to_string(array_accum(quote_literal(column_name::TEXT)), ', '),
           array_to_string(array_accum('NULL'::TEXT), ', '),
           array_to_string(array_accum( 'rRegistro.'::TEXT||column_name::TEXT||'::TEXT'), ', '),

           array_to_string(array_accum(
            '      IF OLD.'||column_name||' IS DISTINCT FROM NEW.'||column_name||' THEN
          aCampo    := ARRAY_APPEND(aCampo,    '||quote_literal(column_name)||');
          aValorOld := ARRAY_APPEND(aValorOld, OLD.'||column_name||'::TEXT);
          aValorNew := ARRAY_APPEND(aValorNew, NEW.'||column_name||'::TEXT);
        END IF;'), '\n\n')

      INTO sColunas,
           sNulls,
           sValores,
           sBlocoUpdate
      FROM information_schema.columns
     WHERE table_schema = rTabela.esquema
       AND table_name   = rTabela.nome;

    -- Variaveis para macro-substituicao
    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_campo_nome}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sColunas);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_insert_campo_valor_old}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sNulls);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_insert_campo_valor_new}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sValores);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_delete_campo_valor_old}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sValores);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_array_delete_campo_valor_new}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sNulls);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_bloco_codigo_definicao_chave}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sBlocoChave);

    aProcura   := ARRAY_APPEND(aProcura,   '{%tpl_bloco_codigo_update}');
    aSubstitui := ARRAY_APPEND(aSubstitui, sBlocoUpdate);

    -- Macro-substituicao das variáveis dentro do bloco de código do template
    sTemplate := fc_replace_multi(sTemplate, aProcura, aSubstitui);

    -- Execução do código do template
    EXECUTE sTemplate;
  END LOOP;

  RETURN;
END;
$$
LANGUAGE plpgsql;

--05_auditoria_remove_funcao

-- Função para remover trigger de auditoria das tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
  sEsquema TEXT;
  sTabela  TEXT;
  aTabela  TEXT[];

  sSQL     TEXT;

  rTabela  RECORD;
BEGIN

  -- Separa Esquema e Tabela
  IF position('.' in $1) > 0 THEN
    aTabela  := string_to_array($1, '.');
    sEsquema := aTabela[1];
    sTabela  := aTabela[2];
  ELSE
    sEsquema := 'public';
    sTabela  := $1;
  END IF;

  FOR rTabela IN
    SELECT esquema,
           nome
      FROM configuracoes.vw_auditoria_lista_tabelas
     WHERE esquema LIKE sEsquema
       AND nome    LIKE sTabela
  LOOP
  
    -- Apaga Trigger de Auditoria
    sSQL := 'DROP TRIGGER IF EXISTS tg_'||rTabela.nome||'_auditoria ON '||rTabela.esquema||'.'||rTabela.nome||';';
    EXECUTE sSQL;

    -- Apaga Funcao de Auditoria
    sSQL := 'DROP FUNCTION IF EXISTS '||rTabela.esquema||'.fc_'||rTabela.nome||'_auditoria();';
    EXECUTE sSQL;

  END LOOP;

  RETURN;
END;
$$
LANGUAGE plpgsql;


--06_auditoria_consulta_mudancas

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


--07_auditoria_existe_mudanca

CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_existe_mudanca(
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
) RETURNS BOOLEAN AS
$$
DECLARE
	sSQL			TEXT;
	sConector		TEXT DEFAULT 'OR';
	sConexaoRemota	TEXT;
	sBaseAuditoria	TEXT DEFAULT current_database()||'_auditoria';

	tInicioAno				TIMESTAMPTZ;
	lExisteBaseAuditoria	BOOLEAN;
	lRetorno				BOOLEAN DEFAULT FALSE;
BEGIN
	lExisteBaseAuditoria := EXISTS (SELECT 1 FROM pg_database WHERE datname = sBaseAuditoria);

	sSQL := 'SELECT EXISTS (SELECT 1 FROM configuracoes.db_auditoria ';
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

	sSQL := sSQL || ')';

	tInicioAno := (extract(year from current_date)||'-01-01 00:00:00.00000')::timestamptz;

	-- SE o ano da Data/Hora de inicio for igual ao ano da Data/Hora corrente 
	-- OU a base de auditoria NAO EXISTIR, entao executa a query na base corrente
	IF extract(year from tDataHoraInicio) = extract(year from current_date) OR lExisteBaseAuditoria IS FALSE THEN
		EXECUTE sSQL INTO lRetorno;
	END IF;

	IF lRetorno IS TRUE THEN
		RETURN lRetorno;
	END IF;

	-- SE a Data/Hora de inicio for menor que o Inicio do Ano Corrente 
	-- E  a base de auditoria EXISTIR, entao executa a query na base de auditoria
	IF tDataHoraInicio < tInicioAno AND lExisteBaseAuditoria IS TRUE THEN
		sConexaoRemota := 'auditoria';
		IF array_position(sConexaoRemota, dblink_get_connections()) IS NULL THEN
			PERFORM dblink_connect(sConexaoRemota, 'dbname='||sBaseAuditoria);
		END IF;

		SELECT	retorno
		INTO	lRetorno
		FROM	dblink(sConexaoRemota, sSQL) AS (retorno BOOLEAN);
	END IF;

	RETURN lRetorno;
END;
$$
LANGUAGE plpgsql;


--08_auditoria_consulta_acessos

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


--09_auditoria_adiciona_fila

/*
 * $1 = id_acount apartir da contagem pra gerar mapas de migracao (NULL apartir inicio) 
 * $2 = tamanho do bloco do mapa de migraÃ§Ã£o (padrao usado = 1000)
 *
 */
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila(INTEGER, INTEGER)
RETURNS void
AS $$
	/* Controle de concorrencia para evitar execuÃ§Ãµes simultÃ¢neas */
	SELECT pg_advisory_xact_lock(-123456789);

	SELECT fc_putsession('configuracoes.db_auditoria_migracao_sequencial_seq',
		(SELECT last_value+1 FROM configuracoes.db_auditoria_migracao_sequencial_seq)::text);

	INSERT	INTO configuracoes.db_auditoria_migracao (sequencial, id_acount_ini, id_acount_fim, status)
	SELECT	NEXTVAL('db_auditoria_migracao_sequencial_seq'),
			minimo + (soma * id) - soma + (
			CASE
				WHEN id = 1
					THEN 0
					ELSE 1
				END
			) AS id_acount_ini,
			CASE
				WHEN (minimo + (soma * id)) > maximo
				THEN maximo
				ELSE (minimo + (soma * id))
			END AS id_acount_fim,
			cast('NAO INICIADO' AS TEXT)
	FROM 	(SELECT	(SELECT	min(id_acount)
					 FROM	ONLY db_acount
					 WHERE	id_acount > coalesce($1, 0)) AS minimo,
					(SELECT	max(id_acount)
					 FROM	ONLY db_acount
					 WHERE	id_acount > coalesce($1, 0)) AS maximo,
					id,
					$2 AS soma
			 FROM	generate_series(1, (
						SELECT	ceil((max(id_acount) - min(id_acount) + 1) / $2::float8)
						FROM	ONLY db_acount
						WHERE	id_acount > coalesce($1, 0)
					)::integer) AS id LIMIT 10) AS x
	WHERE (minimo+maximo) > 0;

	UPDATE	db_auditoria_migracao
	SET		datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
			datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
			instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
	FROM	(SELECT	sequencial,
					fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, id_acount_ini, id_acount_fim) AS dhi
			 FROM	db_auditoria_migracao
			 WHERE	sequencial >= fc_getsession('configuracoes.db_auditoria_migracao_sequencial_seq')::integer) AS x
	WHERE	db_auditoria_migracao.sequencial = x.sequencial;
$$
LANGUAGE sql;

DROP FUNCTION IF EXISTS public.fc_auditoria_adiciona_acount_fila();
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila()
RETURNS void
AS $$
	SELECT configuracoes.fc_auditoria_adiciona_acount_fila(
				(SELECT	id_acount_fim
				 FROM	configuracoes.db_auditoria_migracao
				 ORDER	BY id_acount_fim DESC
				 LIMIT	1), 1000 );
$$
LANGUAGE sql;

CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_todos_acount_fila()
RETURNS void
AS $$
	SELECT configuracoes.fc_auditoria_adiciona_acount_fila(NULL, 1000);
$$
LANGUAGE sql;


--10_auditoria_busca_datahora_e_instit

DROP FUNCTION IF EXISTS public.fc_auditoria_busca_datahora_e_instit(DATE, INTEGER, INTEGER);
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_busca_datahora_e_instit(
	data_inicial DATE,
	id_acount_ini INTEGER,
	id_acount_fim INTEGER,
	OUT datahora_ini TIMESTAMPTZ,
	OUT datahora_fim TIMESTAMPTZ,
	OUT instit INTEGER[])

AS $$
  select min(datahora_ini) as datahora_ini,
         max(datahora_fim) as datahora_fim,
         array_agg(distinct instit) as instit
    from ( (select to_timestamp(min(datahr)) as datahora_ini,
                   to_timestamp(max(datahr)) as datahora_fim,
                   instit
              from (select datahr,
                           coalesce((select min(i.id_instit) from db_userinst i where i.id_usuario=a.id_usuario),
                           (select codigo from db_config where prefeitura is true limit 1)) as instit
                      from db_acount a
                     where not exists
                             (select 1
                                from db_acountacesso ac
                                     join db_logsacessa la  on la.codsequen = ac.codsequen
                                                           and la.data >= $1
                                                           and la.instit = coalesce((select min(i.id_instit) from db_userinst i where i.id_usuario=a.id_usuario),
                                                                                    (select codigo from db_config where prefeitura is true limit 1))
                               where ac.id_acount = a.id_acount)
                       and a.id_acount between $2 and $3
                   ) as y
             group by instit)
           union all
           (select (min(data)||' '||min(hora))::timestamptz as datahora_ini,
                   (max(data)||' '||max(hora))::timestamptz as datahora_fim,
                   la.instit
              from db_acountacesso ac join db_logsacessa la on la.codsequen=ac.codsequen
                                                           and la.data >= $1
                                                           and la.instit in (select codigo from db_config)
             where ac.id_acount between $2 and $3
             group by la.instit)
         ) as x
$$
LANGUAGE sql;

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
