<?php

use Classes\PostgresMigration;

class M8714EstornoAutenticado extends PostgresMigration
{
    public function up()
    {
        $sSqlAutenesto =
<<<EOL

drop function if exists fc_autenesto(integer, integer, date, date, integer, integer, varchar(20), integer, integer);
drop type if exists tp_autenticacao_estorno;

create type tp_autenticacao_estorno as (id integer, data date, codautent integer, autenticacao text, erro boolean, mensagem text);

create or replace function fc_autenesto(integer, integer, date, date, integer, integer, varchar(20), integer, integer)
returns tp_autenticacao_estorno
as $$

declare

  NUMPRE               alias for $1;
  NUMPAR               alias for $2;
  DTEMITE              alias for $3;
  DTVENC               alias for $4;
  SUBDIR               alias for $5;
  CONTA                alias for $6;
  IPTERM               alias for $7;
  INSTIT               alias for $8;
  iCodigoGrupo         alias for $9; --Codigo do grupo de autenticação

  CODAUT               integer;
  IDTERM               integer;
  HORA                 char(5) := to_char(now(), 'HH24:MI');
  IDENT1               char(1);
  IDENT2               char(1);
  IDENT3               char(1);

  UNICA                boolean := false;
  NUMERO_ERRO          char(1);

  NUMTOT               integer;
  NUMDIG               integer;
  NUMCGM               integer;

  GRAVA_CORNUMP        record;
  RECORD_NUMPRE        record;

  valor                float8;
  VLRCORRECAO          float8 := 0;
  VLRJUROS             float8 := 0;
  VLRMULTA             float8 := 0;
  VLRDESCONTO          float8 := 0;

  NUM_PAR              integer;
  TEM_ARRECANT         integer;
  TIPOAUTENT           integer;
  VINSTIT              integer;

  AUTENTICACAO         text;

  VTIPO                varchar(1);
  PROCESSA             boolean := FALSE;
  RECIBO_PAGA          boolean;
  GRAVA_AUTENT         boolean;

  iAutenticado          integer;

  rAbatimentos         record;
  rArrecantPgtoParcial record;

  iAbatimento          integer;
  iTipoAbatimento      integer;

  rtp_autenticacao tp_autenticacao_estorno%ROWTYPE;

begin

  RECIBO_PAGA := false;

  rtp_autenticacao.id           := 0;
  rtp_autenticacao.data         := null;
  rtp_autenticacao.codautent    := 0;
  rtp_autenticacao.autenticacao := '';
  rtp_autenticacao.erro         := true;
  rtp_autenticacao.mensagem     := '';

  if NUMPAR = 0 then

    -- ESTORNO DE NUMPRE
    -- O estorno de debitos com o numpre original do débito em aberto so é realizado apos todos os numpares quitados.
    -- 1 - Pode estar em um recibo normal pago, se estiver tem que ter arquivo bancário de pagamento autenticado.
    -- 2 - Pode estar em um recibo carne pago via baixa de banco. Se estiver tem que ter arquivo bancário de pagamento autenticado.
    -- 3 - Pode estar em um recibo carne pago, direto no caixa. Se for pode estornar. Esse processo de pagamento gera autenticação.
    select sum(k00_valor),
           case when
            -- Tem numpar em aberto
            (
              select 1
                from arrecad
               where k00_numpre = NUMPRE
            ) > 0 or
            -- Tem numpre em recibo pago e não esta autenticado
            -- Tem numpre pado por recibo carne e não esta autenticado
            (
              select sum(1)
                from disbanco
                     inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                     left join discla on discla.codret = disbanco.codret
               where recibopaga.k00_numpre = NUMPRE
                 and (    discla.codret is not null
                      and discla.dtaute is null)
            ) > 0 or
            -- Resibo avulso
            (
              select sum(1)
                from disbanco
                     inner join recibo on recibo.k00_numpre = disbanco.k00_numpre
                     left join discla on discla.codret = disbanco.codret
               where recibo.k00_numpre = NUMPRE
                 and (    discla.codret is not null
                      and discla.dtaute is null)
            ) > 0
            then null
            else 1
           end as autenticado
      into valor,
           iAutenticado
      from arrepaga
     where k00_numpre = NUMPRE;

     select sum(k00_valor)
      into valor
      from arrepaga
     where k00_numpre = NUMPRE;

    if valor is null then

      -- ESTORNO DE RECIBO NORMAL
      -- 1 - Tem que ter arquivo bancário de pagamento autenticado.
      select sum(k00_valor),
             (select 1
                from disbanco
                     inner join discla on discla.codret = disbanco.codret
               where disbanco.k00_numpre = NUMPRE
                 and discla.dtaute is not null
             ) as autenticado
        into valor,
             iAutenticado
        from recibopaga
       where k00_numnov = NUMPRE
         and k00_conta != 0;

      RECIBO_PAGA := true;

    end if;

  else

    -- ESTORNO DE NUMPRE E NUMPAR
    -- Um estorno de débito por numpre e numpar original, pode ser feito indiferente do meio de pagamento.
    -- Um estorno de débito por numpre e numpar original, pode ser feito indiferente do status dos outros numpar's do débito.
    -- 1 - Pode estar em um recibo normal pago, se estiver tem que ter arquivo bancário de pagamento autenticado.
    -- 2 - Pode estar em um recibo carne pago via baixa de banco. Se estiver tem que ter arquivo bancário de pagamento autenticado.
    -- 3 - Pode estar em um recibo carne pago, direto no caixa. Se for pode estornar. Esse processo de pagamento gera autenticação.
    select sum(k00_valor),
           case when
            -- Tem receita em aberto
            (
              select 1
                from arrecad
               where k00_numpre = NUMPRE
                 and k00_numpar = NUMPAR
            ) > 0 or
            -- Tem numpre em recibo pago e não esta autenticado
            -- Tem numpre pado por recibo carne e não esta autenticado
            (
              select sum(1)
                from disbanco
                     inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                     left join discla on discla.codret = disbanco.codret
               where recibopaga.k00_numpre = NUMPRE
                 and recibopaga.k00_numpar = NUMPAR
                 and (    discla.codret is not null
                      and discla.dtaute is null)
            ) > 0
            then null
            else 1
           end as autenticado
      into valor,
           iAutenticado
      from arrepaga
     where k00_numpre = NUMPRE
       and k00_numpar = NUMPAR;

  end if;

  if iAutenticado is null then

    rtp_autenticacao.erro     := true;
    rtp_autenticacao.mensagem := '6 DÉBITO A RECEBER ESTORNO NÃO FOI AUTENTICADO - ' || NUMPRE;
    return rtp_autenticacao;

  end if;

  IF NOT valor IS NULL THEN

    SELECT K11_ID,
           K11_IDENT1,
           K11_IDENT2,
           K11_IDENT3,
           K11_TIPAUTENT
      INTO IDTERM,
           IDENT1,
           IDENT2,
           IDENT3,
           TIPOAUTENT
      FROM CFAUTENT
     WHERE K11_IPTERM = IPTERM
       AND K11_INSTIT = INSTIT;

    IF NOT IDTERM IS NULL THEN

      SELECT MAX(K12_AUTENT)
        INTO CODAUT
        FROM CORRENTE
       WHERE K12_ID     = IDTERM
         AND K12_DATA   = DTEMITE
         AND K12_INSTIT = INSTIT;

      IF CODAUT IS NULL THEN
        CODAUT := 1;
      ELSE
        CODAUT := CODAUT + 1;
      END IF;

      -- GRAVA AUTENTICACAO
      valor        := valor * -1;
      GRAVA_AUTENT := FALSE;

      FOR RECORD_NUMPRE IN SELECT DISTINCT
                                  K00_NUMPRE,
                                  K00_NUMPAR
                                 FROM ARREPAGA
                                WHERE K00_NUMPRE = NUMPRE
                              AND K00_NUMPAR = NUMPAR

                            UNION

                           SELECT DISTINCT
                                  K00_NUMPRE,
                                  K00_NUMPAR
                             FROM RECIBOPAGA
                            WHERE K00_NUMNOV = NUMPRE

                            UNION

                           SELECT DISTINCT
                                  K00_NUMPRE,
                                  K00_NUMPAR
                             FROM ARREPAGA
                            WHERE K00_NUMPRE = NUMPRE
                              AND NUMPAR = 0

      LOOP

        IF TIPOAUTENT != 3 THEN

            FOR GRAVA_CORNUMP IN SELECT K00_RECEIT,
                                      K00_NUMTOT,
                                      K00_NUMDIG,
                                      SUM(K00_VALOR)
                                     FROM ARREPAGA
                                    WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                                  AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                                   GROUP BY K00_RECEIT,
                                      K00_NUMTOT,
                                      K00_NUMDIG
          LOOP

              IF GRAVA_AUTENT = FALSE THEN

              GRAVA_AUTENT := TRUE;

                BEGIN
                INSERT INTO CORRENTE VALUES (IDTERM,
                                                         DTEMITE,
                                                         CODAUT,
                                                         hora,
                                                         conta,
                                                         valor,
                                                         true,
                                                         INSTIT);

                  EXCEPTION WHEN OTHERS THEN
                rtp_autenticacao.erro := true;
                rtp_autenticacao.mensagem := SQLERRM;
                return rtp_autenticacao;
              END;

              IF iCodigoGrupo <> 0 THEN

                    INSERT INTO corgrupocorrente (k105_sequencial,
                                                          k105_corgrupo,
                                                            k105_data,
                                                            k105_autent,
                                                            k105_id,
                                                          k105_corgrupotipo) VALUES (nextval('corgrupocorrente_k105_sequencial_seq'),
                                                                                       iCodigoGrupo,
                                                                                       DTEMITE,
                                                                                       CODAUT,
                                                                                       IDTERM,
                                                                                     6);

                END IF;

              END IF;

              -- agora verifica a instituição da receita,
              -- para impedir estorno de receita que não seja da instituição corrente
            SELECT K02_TIPO
              INTO VTIPO
              FROM TABREC
             WHERE K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT;

            IF VTIPO = 'O' THEN

              SELECT O70_INSTIT
                    INTO VINSTIT
                FROM TABORC
                     INNER JOIN orcreceita ON o70_codrec = k02_codrec
                                          AND o70_anousu = k02_anousu
               WHERE TABORC.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                 AND TABORC.K02_ANOUSU = TO_CHAR(DTEMITE, 'YYYY')::integer;

            ELSE

                SELECT C61_INSTIT
                  INTO VINSTIT
                  FROM TABPLAN
                       INNER JOIN CONPLANOREDUZ ON C61_REDUZ  = k02_REDUZ
                                             AND C61_ANOUSU = K02_ANOUSU
                                             AND C61_INSTIT = INSTIT
                 WHERE TABPLAN.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                 AND K02_ANOUSU = TO_CHAR(DTEMITE, 'YYYY')::integer;

            END IF;

            IF VINSTIT IS NULL OR VINSTIT != INSTIT THEN
              rtp_autenticacao.erro     := true;
              rtp_autenticacao.mensagem := '5 RECEITA ' || GRAVA_CORNUMP.K00_RECEIT || ' DE INSTITUIÇÃO DIFERENTE';
              return rtp_autenticacao;

            END IF;

            INSERT INTO CORNUMP VALUES (IDTERM,
                                                    DTEMITE,
                                                      CODAUT,
                                                      RECORD_NUMPRE.K00_NUMPRE,
                                                      RECORD_NUMPRE.K00_NUMPAR,
                                                      GRAVA_CORNUMP.K00_NUMTOT,
                                                      GRAVA_CORNUMP.K00_NUMDIG,
                                                      GRAVA_CORNUMP.K00_RECEIT,
                                                      GRAVA_CORNUMP.SUM * -1,
                                                      NUMPRE);

          END LOOP;

          END IF;

          SELECT K00_NUMPRE
               INTO TEM_ARRECANT
            FROM ARRECANT
           WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
           AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

          IF NOT TEM_ARRECANT IS NULL THEN

            INSERT INTO ARRECAD SELECT *
                                FROM ARRECANT
                               WHERE K00_NUMPRE = RECORD_NUMPRE.k00_NUMPRE
                                 AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;
            DELETE FROM ARRECANT
                WHERE K00_NUMPRE = RECORD_NUMPRE.k00_NUMPRE
                  AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

          END IF;

          DELETE FROM ARREPAGA
              WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

        delete from arreidret
              where k00_numpre = RECORD_NUMPRE.K00_NUMPRE
                and k00_numpar = RECORD_NUMPRE.K00_NUMPAR;

          PROCESSA := TRUE;

      END LOOP;

    ELSE

        -- ERRO QUANDO O TERMINAL NAO ESTA CADASTRADO
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '2 - AUTENTICADORA NAO CADASTRADA';
        return rtp_autenticacao;

    END IF;

    -- Verifica se o estorno é referente a um recibo avulso de pagamento parcial
    select abatimento.k125_sequencial,
           abatimento.k125_tipoabatimento
      into iAbatimento,
           iTipoAbatimento
      from abatimentorecibo
           inner join abatimento         on abatimento.k125_sequencial         = abatimentorecibo.k127_abatimento
           inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial
           inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey
     where abatimentorecibo.k127_numprerecibo = NUMPRE
       and abatimento.k125_tipoabatimento in (1, 3);

    -- Caso seja pagamento parcial então e percorrido todos os valores abatidos
    if iAbatimento is not null then

      if iTipoAbatimento = 1 then

        for rAbatimentos in select *,
                                   (select 1
                                      from arrecad
                                     where arrecad.k00_numpre = arreckey.k00_numpre
                                       and arrecad.k00_numpar = arreckey.k00_numpar
                                       and arrecad.k00_receit = arreckey.k00_receit
                                     limit 1) as arrecad
                              from abatimentoarreckey
                                   inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey
                                   left  join abatimentoarreckeyarrecadcompos on abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                             where abatimentoarreckey.k128_abatimento = iAbatimento
        loop

          if rAbatimentos.arrecad is null then

            -- Verifica o débito foi quitado pelo pagamento parcial
            select *
              into rArrecantPgtoParcial
              from arrecantpgtoparcial
             where arrecantpgtoparcial.k00_numpre = rAbatimentos.k00_numpre
               and arrecantpgtoparcial.k00_numpar = rAbatimentos.k00_numpar
               and arrecantpgtoparcial.k00_receit = rAbatimentos.k00_receit;

            if rArrecantPgtoParcial.k00_numpre is not null then

              insert into arrecad (k00_numpre,
                                                 k00_numpar,
                                                   k00_numcgm,
                                                 k00_dtoper,
                                                 k00_receit,
                                                 k00_hist,
                                                 k00_valor,
                                                 k00_dtvenc,
                                                 k00_numtot,
                                                 k00_numdig,
                                                 k00_tipo,
                                                 k00_tipojm) values (rArrecantPgtoParcial.k00_numpre,
                                                                     rArrecantPgtoParcial.k00_numpar,
                                                                     rArrecantPgtoParcial.k00_numcgm,
                                                                     rArrecantPgtoParcial.k00_dtoper,
                                                                     rArrecantPgtoParcial.k00_receit,
                                                                     rArrecantPgtoParcial.k00_hist,
                                                                     rAbatimentos.k128_valorabatido,
                                                                     rArrecantPgtoParcial.k00_dtvenc,
                                                                     rArrecantPgtoParcial.k00_numtot,
                                                                     rArrecantPgtoParcial.k00_numdig,
                                                                     rArrecantPgtoParcial.k00_tipo,
                                                                     rArrecantPgtoParcial.k00_tipojm);

              delete
                from arrecantpgtoparcial
               where arrecantpgtoparcial.k00_numpre = rAbatimentos.k00_numpre
                 and arrecantpgtoparcial.k00_numpar = rAbatimentos.k00_numpar
                 and arrecantpgtoparcial.k00_receit = rAbatimentos.k00_receit;

            else

              raise exception 'OPERAÇÃO CANCELADA, EXISTEM DÉBITOS DE ORIGEM DO PAGAMENTO PARCIAL INFORMADO QUE NÃO ESTÃO EM ABERTO!';

            end if;

          else

            -- Devolve o valor abatido para a tabela ARRECAD
            update arrecad
               set k00_valor  = (k00_valor + rAbatimentos.k128_valorabatido)
             where k00_numpre = rAbatimentos.k00_numpre
               and k00_numpar = rAbatimentos.k00_numpar
               and k00_receit = rAbatimentos.k00_receit;

            -- Devolve o valor abatido para a tabela ARRECADCOMPOS
            update arrecadcompos
               set k00_vlrhist    = (k00_vlrhist  + rAbatimentos.k129_vlrhist),
                   k00_correcao   = (k00_correcao + rAbatimentos.k129_correcao),
                   k00_juros      = (k00_juros    + rAbatimentos.k129_juros),
                   k00_multa      = (k00_multa    + rAbatimentos.k129_multa)
             where k00_sequencial = rAbatimentos.k129_arrecadcompos;

          end if;

        end loop;

      end if;

      -- Deleta todos registros referente ao recibo encontrado
      delete
        from abatimentoarreckeyarrecadcompos
       where abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey in (select abatimentoarreckey.k128_sequencial
                                                                           from abatimentoarreckey
                                                                          where abatimentoarreckey.k128_abatimento = iAbatimento);

        delete
          from abatimentoarreckey
         where abatimentoarreckey.k128_abatimento = iAbatimento;

      delete
        from abatimentorecibo
       where abatimentorecibo.k127_abatimento = iAbatimento;

      delete
        from abatimento
       where abatimento.k125_sequencial = iAbatimento;

    end if;

  END IF;

  IF PROCESSA = TRUE THEN

    IF RECIBO_PAGA = TRUE THEN

      UPDATE RECIBOPAGA
         SET K00_CONTA  = 0,
             K00_DTPAGA = NULL
       WHERE K00_NUMNOV = NUMPRE;

    END IF;

    -- AUTENTICACAO CORRETA
    IF TIPOAUTENT != 3 THEN

      AUTENTICACAO := TO_CHAR(CODAUT, '999999') || DTEMITE || IDENT1 || IDENT2 || IDENT3 || TO_CHAR(NUMPRE, '99999999') || TO_CHAR(NUMPAR, '999') || TO_CHAR(ABS(valor), '99999999.99') || '-';

      INSERT INTO CORAUTENT (K12_ID,
                             K12_DATA,
                             K12_AUTENT,
                             K12_CODAUTENT) VALUES (IDTERM,
                                                          DTEMITE,
                                                            CODAUT,
                                                          AUTENTICACAO);

      rtp_autenticacao.erro         := false;
      rtp_autenticacao.id           := IDTERM;
      rtp_autenticacao.data         := DTEMITE;
      rtp_autenticacao.codautent    := CODAUT;
      rtp_autenticacao.autenticacao := '1'||AUTENTICACAO;
      rtp_autenticacao.mensagem     := '1'||AUTENTICACAO;
      return rtp_autenticacao;

    ELSE

      rtp_autenticacao.erro     := false;
      rtp_autenticacao.mensagem := '1 AUTENTICACAO NAO GERADA';
      return rtp_autenticacao;

    END IF;

  ELSE

    -- NUMPRE NAO PROCESSADO
    rtp_autenticacao.erro     := true;
    rtp_autenticacao.mensagem := '3 - ERRO DURANTE O PROCESSAMENTO DO NUMPRE';
    return rtp_autenticacao;

  END IF;

END;
$$
language 'plpgsql';

EOL;

        $this->execute($sSqlAutenesto);
    }

    public function down()
    {

        $sSqlAutenesto =
<<<EOL

drop function if exists fc_autenesto(integer,integer,date,date,integer,integer,varchar(20),integer, integer);
drop type     if exists tp_autenticacao_estorno;

create type tp_autenticacao_estorno as (id           integer,
                                        data         date,
                                        codautent    integer,
                                        autenticacao text,
                                        erro         boolean,
                                        mensagem     text);

create or replace function fc_autenesto(integer,integer,date,date,integer,integer,varchar(20),integer, integer)
returns tp_autenticacao_estorno
as $$

DECLARE

    NUMPRE               ALIAS FOR $1;
    NUMPAR               ALIAS FOR $2;
    DTEMITE              ALIAS FOR $3;
    DTVENC               ALIAS FOR $4;
    SUBDIR               ALIAS FOR $5;
    CONTA                ALIAS FOR $6;
    IPTERM               ALIAS FOR $7;
    INSTIT               ALIAS FOR $8;
    iCodigoGrupo         ALIAS FOR $9; --Codigo do grupo de autenticação

    CODAUT               INTEGER ;
    IDTERM               INTEGER ;
    HORA                 CHAR(5) := to_char(now(), 'HH24:MI') ;
    IDENT1               CHAR(1);
    IDENT2               CHAR(1);
    IDENT3               CHAR(1);

    UNICA                BOOLEAN := FALSE;
    NUMERO_ERRO          char(1);

    NUMTOT               INTEGER;
    NUMDIG               INTEGER;
    NUMCGM               INTEGER;

    GRAVA_CORNUMP        RECORD;
    RECORD_NUMPRE        RECORD;

    VALOR                FLOAT8;
    VLRCORRECAO          FLOAT8 := 0;
    VLRJUROS             FLOAT8 := 0;
    VLRMULTA             FLOAT8 := 0;
    VLRDESCONTO          FLOAT8 := 0;

    NUM_PAR              INTEGER;
    TEM_ARRECANT         INTEGER;
    TIPOAUTENT           INTEGER;
    VINSTIT              INTEGER;

    AUTENTICACAO         TEXT;

    VTIPO                VARCHAR(1);
    PROCESSA             BOOLEAN := FALSE;
    RECIBO_PAGA          BOOLEAN;
    GRAVA_AUTENT         BOOLEAN;

    rAbatimentos         record;
    rArrecantPgtoParcial record;

    iAbatimento          integer;
    iTipoAbatimento      integer;

    rtp_autenticacao tp_autenticacao_estorno%ROWTYPE;


BEGIN

RECIBO_PAGA := FALSE;

 rtp_autenticacao.id           := 0;
 rtp_autenticacao.data         := null;
 rtp_autenticacao.codautent    := 0;
 rtp_autenticacao.autenticacao := '';
 rtp_autenticacao.erro         := true;
 rtp_autenticacao.mensagem     := '';

IF NUMPAR = 0 THEN

  SELECT SUM(K00_VALOR)
    INTO VALOR
    FROM ARREPAGA
   WHERE K00_NUMPRE = NUMPRE ;

  IF VALOR IS NULL THEN

    SELECT SUM(K00_VALOR)
      INTO VALOR
      FROM RECIBOPAGA
     WHERE k00_NUMNOV = NUMPRE
       AND NOT K00_CONTA = 0;

    RECIBO_PAGA := TRUE;

  END IF;

ELSE

  SELECT SUM(K00_VALOR)
    INTO VALOR
    FROM ARREPAGA
   WHERE K00_NUMPRE = NUMPRE
     AND K00_NUMPAR = NUMPAR;

END IF;

IF NOT VALOR IS NULL THEN

  SELECT K11_ID,
         K11_IDENT1,
         K11_IDENT2,
         K11_IDENT3,
         K11_TIPAUTENT
    INTO IDTERM,
         IDENT1,
         IDENT2,
         IDENT3,
         TIPOAUTENT
    FROM CFAUTENT
   WHERE K11_IPTERM = IPTERM
     AND K11_INSTIT = INSTIT;

  IF NOT IDTERM IS NULL THEN

    SELECT MAX(K12_AUTENT)
      INTO CODAUT
      FROM CORRENTE
     WHERE K12_ID     = IDTERM
       AND K12_DATA   = DTEMITE
       AND K12_INSTIT = INSTIT;

    IF CODAUT IS NULL THEN
      CODAUT := 1;
    ELSE
      CODAUT := CODAUT + 1;
    END IF;

    -- GRAVA AUTENTICACAO
    VALOR        := VALOR * -1;
    GRAVA_AUTENT := FALSE;


    FOR RECORD_NUMPRE IN SELECT DISTINCT
                                K00_NUMPRE,
                                K00_NUMPAR
                           FROM ARREPAGA
                          WHERE K00_NUMPRE = NUMPRE
                            AND K00_NUMPAR = NUMPAR

                          UNION

                         SELECT DISTINCT
                                K00_NUMPRE,
                                K00_NUMPAR
                           FROM RECIBOPAGA
                          WHERE K00_NUMNOV = NUMPRE

                          UNION

                         SELECT DISTINCT
                                K00_NUMPRE,
                                K00_NUMPAR
                           FROM ARREPAGA
                          WHERE K00_NUMPRE = NUMPRE
                            AND NUMPAR = 0

    LOOP

      IF TIPOAUTENT != 3 THEN

        FOR GRAVA_CORNUMP IN SELECT K00_RECEIT,
                                    K00_NUMTOT,
                                    K00_NUMDIG,
                                    SUM(K00_VALOR)
                               FROM ARREPAGA
                              WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                                AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                           GROUP BY K00_RECEIT,
                                    K00_NUMTOT,
                                    K00_NUMDIG
        LOOP

          IF GRAVA_AUTENT = FALSE THEN

            GRAVA_AUTENT := TRUE;

            BEGIN
              INSERT INTO CORRENTE VALUES ( IDTERM,
                                            DTEMITE,
                                            CODAUT,
                                            hora,
                                            conta,
                                            valor,
                                            true,
                                            INSTIT );
            EXCEPTION WHEN OTHERS THEN
              rtp_autenticacao.erro     := true;
              rtp_autenticacao.mensagem := SQLERRM;
              return rtp_autenticacao;
            END;

          IF iCodigoGrupo <> 0 THEN

                INSERT INTO corgrupocorrente ( k105_sequencial,
                                               k105_corgrupo,
                                               k105_data,
                                               k105_autent,
                                               k105_id,
                                               k105_corgrupotipo )
                                      VALUES ( nextval('corgrupocorrente_k105_sequencial_seq'),
                                               iCodigoGrupo,
                                               DTEMITE,
                                               CODAUT,
                                               IDTERM,
                                               6 );

            END IF;

          END IF;

          -- agora verifica a instituição da receita,
          -- para impedir estorno de receita que não seja da instituição corrente

          SELECT K02_TIPO
            INTO VTIPO
            FROM TABREC
           WHERE K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT;

          IF VTIPO = 'O' THEN

            SELECT O70_INSTIT
              INTO VINSTIT
              FROM TABORC
                   INNER JOIN orcreceita ON o70_codrec = k02_codrec
                                        AND o70_anousu = k02_anousu
             WHERE TABORC.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
               AND TABORC.K02_ANOUSU=TO_CHAR(DTEMITE,'YYYY')::integer;

          ELSE

            SELECT C61_INSTIT
              INTO VINSTIT
              FROM TABPLAN
                   INNER JOIN CONPLANOREDUZ ON C61_REDUZ  = k02_REDUZ
                                           AND C61_ANOUSU = K02_ANOUSU
                                           AND C61_INSTIT = INSTIT
             WHERE TABPLAN.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
               AND K02_ANOUSU = TO_CHAR(DTEMITE,'YYYY')::integer;

          END IF ;

          IF VINSTIT IS NULL OR VINSTIT != INSTIT THEN
            rtp_autenticacao.erro     := true;
            rtp_autenticacao.mensagem := '5 RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' DE INSTITUIÇÃO DIFERENTE';
            return rtp_autenticacao;

          END IF;

          INSERT INTO CORNUMP VALUES ( IDTERM,
                                       DTEMITE,
                                       CODAUT,
                                       RECORD_NUMPRE.K00_NUMPRE,
                                       RECORD_NUMPRE.K00_NUMPAR,
                                       GRAVA_CORNUMP.K00_NUMTOT,
                                       GRAVA_CORNUMP.K00_NUMDIG,
                                       GRAVA_CORNUMP.K00_RECEIT,
                                       GRAVA_CORNUMP.SUM*-1,
                                       NUMPRE
                                     );
        END LOOP;

      END IF;

      SELECT K00_NUMPRE
        INTO TEM_ARRECANT
        FROM ARRECANT
       WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
         AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

      IF NOT TEM_ARRECANT IS NULL THEN

        INSERT INTO ARRECAD SELECT *
                              FROM ARRECANT
                             WHERE K00_NUMPRE = RECORD_NUMPRE.k00_NUMPRE
                               AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;
        DELETE
          FROM ARRECANT
         WHERE K00_NUMPRE = RECORD_NUMPRE.k00_NUMPRE
           AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

      END IF;


      DELETE
        FROM ARREPAGA
       WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
         AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;

      PROCESSA := TRUE;

    END LOOP;

  ELSE
      -- ERRO QUANDO O TERMINAL NAO ESTA CADASTRADO
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '2 - AUTENTICADORA NAO CADASTRADA';
      return rtp_autenticacao;

  END IF;


  -- Verifica se o estorno é referente a um recibo avulso de pagamento parcial

  select abatimento.k125_sequencial,
         abatimento.k125_tipoabatimento
    into iAbatimento,
         iTipoAbatimento
    from abatimentorecibo
         inner join abatimento         on abatimento.k125_sequencial         = abatimentorecibo.k127_abatimento
         inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial
         inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey
   where abatimentorecibo.k127_numprerecibo = NUMPRE
     and abatimento.k125_tipoabatimento in (1,3);


  -- Caso seja pagamento parcial então e percorrido todos os valores abatidos

  if iAbatimento is not null then

    if iTipoAbatimento = 1 then

      for rAbatimentos in select *,
                                 ( select 1
                                     from arrecad
                                    where arrecad.k00_numpre = arreckey.k00_numpre
                                      and arrecad.k00_numpar = arreckey.k00_numpar
                                      and arrecad.k00_receit = arreckey.k00_receit
                                    limit 1 ) as arrecad
                            from abatimentoarreckey
                                 inner join arreckey                        on arreckey.k00_sequencial                                 = abatimentoarreckey.k128_arreckey
                                 left  join abatimentoarreckeyarrecadcompos on abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                           where abatimentoarreckey.k128_abatimento = iAbatimento
      loop

        if rAbatimentos.arrecad is null then

          -- Verifica o débito foi quitado pelo pagamento parcial

          select *
            into rArrecantPgtoParcial
            from arrecantpgtoparcial
           where arrecantpgtoparcial.k00_numpre = rAbatimentos.k00_numpre
             and arrecantpgtoparcial.k00_numpar = rAbatimentos.k00_numpar
             and arrecantpgtoparcial.k00_receit = rAbatimentos.k00_receit;


          if rArrecantPgtoParcial.k00_numpre is not null then


            insert into arrecad ( k00_numpre,
                                  k00_numpar,
                                  k00_numcgm,
                                  k00_dtoper,
                                  k00_receit,
                                  k00_hist,
                                  k00_valor,
                                  k00_dtvenc,
                                  k00_numtot,
                                  k00_numdig,
                                  k00_tipo,
                                  k00_tipojm
                                ) values (
                                  rArrecantPgtoParcial.k00_numpre,
                                  rArrecantPgtoParcial.k00_numpar,
                                  rArrecantPgtoParcial.k00_numcgm,
                                  rArrecantPgtoParcial.k00_dtoper,
                                  rArrecantPgtoParcial.k00_receit,
                                  rArrecantPgtoParcial.k00_hist,
                                  rAbatimentos.k128_valorabatido,
                                  rArrecantPgtoParcial.k00_dtvenc,
                                  rArrecantPgtoParcial.k00_numtot,
                                  rArrecantPgtoParcial.k00_numdig,
                                  rArrecantPgtoParcial.k00_tipo,
                                  rArrecantPgtoParcial.k00_tipojm
                                );

            delete
              from arrecantpgtoparcial
             where arrecantpgtoparcial.k00_numpre = rAbatimentos.k00_numpre
               and arrecantpgtoparcial.k00_numpar = rAbatimentos.k00_numpar
               and arrecantpgtoparcial.k00_receit = rAbatimentos.k00_receit;

          else

            raise exception 'OPERAÇÃO CANCELADA, EXISTEM DÉBITOS DE ORIGEM DO PAGAMENTO PARCIAL INFORMADO QUE NÃO ESTÃO EM ABERTO!';

          end if;

        else

          -- Devolve o valor abatido para a tabela ARRECAD

          update arrecad
             set k00_valor  = ( k00_valor + rAbatimentos.k128_valorabatido )
           where k00_numpre = rAbatimentos.k00_numpre
             and k00_numpar = rAbatimentos.k00_numpar
             and k00_receit = rAbatimentos.k00_receit;


          -- Devolve o valor abatido para a tabela ARRECADCOMPOS

          update arrecadcompos
             set k00_vlrhist    = ( k00_vlrhist  + rAbatimentos.k129_vlrhist  ),
                 k00_correcao   = ( k00_correcao + rAbatimentos.k129_correcao ),
                 k00_juros      = ( k00_juros    + rAbatimentos.k129_juros    ),
                 k00_multa      = ( k00_multa    + rAbatimentos.k129_multa    )
           where k00_sequencial = rAbatimentos.k129_arrecadcompos;

        end if;

      end loop;

    end if;


    -- Deleta todos registros referente ao recibo encontrado

    delete
      from abatimentoarreckeyarrecadcompos
     where abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey in ( select abatimentoarreckey.k128_sequencial
                                                                          from abatimentoarreckey
                                                                         where abatimentoarreckey.k128_abatimento = iAbatimento );

    delete
      from abatimentoarreckey
     where abatimentoarreckey.k128_abatimento = iAbatimento;

    delete
      from abatimentorecibo
     where abatimentorecibo.k127_abatimento   = iAbatimento;

    delete
      from abatimento
     where abatimento.k125_sequencial         = iAbatimento;

  end if;


END IF;

IF PROCESSA = TRUE THEN

  IF RECIBO_PAGA = TRUE THEN

    UPDATE RECIBOPAGA
       SET K00_CONTA  = 0,
           K00_DTPAGA = NULL
     WHERE K00_NUMNOV = NUMPRE;

  END IF;

  -- AUTENTICACAO CORRETA
  IF TIPOAUTENT != 3 THEN

    AUTENTICACAO:= TO_CHAR(CODAUT,'999999') || DTEMITE || IDENT1 || IDENT2 || IDENT3 || TO_CHAR(NUMPRE,'99999999') || TO_CHAR(NUMPAR,'999') || TO_CHAR(ABS(VALOR),'99999999.99')||'-';

    INSERT INTO CORAUTENT ( K12_ID,
                            K12_DATA,
                            K12_AUTENT,
                            K12_CODAUTENT
                          ) VALUES (
                            IDTERM,
                                    DTEMITE,
                                      CODAUT,
                                    AUTENTICACAO
                          );

    rtp_autenticacao.erro         := false;
    rtp_autenticacao.id           := IDTERM;
    rtp_autenticacao.data         := DTEMITE;
    rtp_autenticacao.codautent    := CODAUT;
    rtp_autenticacao.autenticacao := '1'||AUTENTICACAO;
    rtp_autenticacao.mensagem     := '1'||AUTENTICACAO;
    return rtp_autenticacao;

  ELSE

    rtp_autenticacao.erro     := false;
    rtp_autenticacao.mensagem := '1 AUTENTICACAO NAO GERADA';
    return rtp_autenticacao;

  END IF;

ELSE

  -- NUMPRE NAO PROCESSADO
  rtp_autenticacao.erro     := true;
  rtp_autenticacao.mensagem := '3 - ERRO DURANTE O PROCESSAMENTO DO NUMPRE';
  return rtp_autenticacao;

END IF;

END;
$$
language 'plpgsql';

EOL;

        $this->execute($sSqlAutenesto);
    }
}
