<?php

use Classes\PostgresMigration;

class M5782Vistorias extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL
create or replace function fc_vistorias(integer) returns varchar(200)
as $$
DECLARE

  iCodigoVistoria             alias for $1;

  V_ATIVTIPO                  integer;
  iNumpreGerado               integer;
  iNumpreArreinscr            integer;
  iNumpreArrecant             integer;
  iNumpreArrecad              integer;
  V_DATA                      date;

  V_DIASGERAL                 integer;
  V_MESGERAL                  integer;
  V_PARCIAL                   boolean;

  V_ACHOU                     boolean default false;
  lCalculou                   boolean default false;
  V_DATAVENC                  date;
  V_Y74_CODSANI               integer;
  V_Y74_INSCRSANI             integer;
  V_Y80_NUMCGM                integer;
  V_Y69_NUMPRE                integer;
  iCodigoReceitaExercicio     integer;
  iCodigoHistoricoCalculo     integer;
  nValorExercicio             float8;
  iCodigoArretipo             integer;
  V_Y71_INSCR                 integer;
  V_Q02_NUMCGM                integer;
  V_ATIV                      integer;
  iAnousu                     integer;
  iCodigoAtividade            integer;
  V_DIASVENC                  integer;
  iFormaCalculo               integer;
  iCodVencimento              integer default 0;

  lIsSanitario                boolean;
  V_INSCR                     boolean;
  V_AREA                      float8;
  nValorInflator              float8;
  nValorBase                  float8;
  nValorVistoria              float8;
  sSql                        text default '';
  nValorExcedente             float8;
  iQuantidadeAtividades       integer default 0;

  iFormulaCalculoVistoria     integer;

  nPontuacaoClasse            integer default NULL;
  iPontuacaoPorZonaFiscal     integer;
  iPontuacaoPorEmpregadosArea integer;
  iPontuacaoPorEmpregados     integer;
  iPontuacaoPorArea           integer;
  iPontuacaoGeral             integer;

  v_tipo_quant                integer;

  iInstit                     integer;
  iCodCli                     integer;

  dDataVistoria               date;
  dDataAtual                  date;

  rAtivtipo                   record;
  rSaniAtividade              record;
  rFinanceiro                 record;

  sTipoVistoriaSanitario      text;

  lRaise                      boolean default true;

  lCalculaVistoriaMei         boolean default true;
  lContribuinteMei            boolean default false;
  lVistoriaAtrasada           boolean default false;

  nQuantidadeAtividade        float8 default 1; -- Quantidade informada no campo q07_quant da tabativ
  nMultiplicadorIssQuantidade float8 default 1; -- Quantidade informada no campo q30_multi da issquant

  BEGIN

    /**
     * Verifica pl por Cliente Especifico
     */
    select db21_codcli
      into iCodCli
      from db_config
     where prefeitura is true;

    lRaise     := ( case when fc_getsession('DB_debugon') is null then false else true end );
    iInstit    := fc_getsession('DB_instit');
    dDataAtual := fc_getsession('DB_datausu');


    /**
     * 3 = VISTORIA LOCALIZACAO
     * 5 = TAXA
     * 6 = VISTORIA SANITARIO
     */
    begin

      if iCodCli = 4 then

        create temp table w_tipos_localizacao as select 3 as codigo;
        create temp table w_tipos_sanitario   as select 6 as codigo;
      else

        create temp table w_tipos_localizacao as select 3 as codigo union select 5;
        create temp table w_tipos_sanitario   as select 5 as codigo union select 6;
      end if;
    exception

         when duplicate_table then

           truncate w_tipos_localizacao;
           truncate w_tipos_sanitario;
           if iCodCli = 4 then

             insert into w_tipos_localizacao values (3);
             insert into w_tipos_sanitario   values (6);
           else

             insert into w_tipos_localizacao values (3),(5);
             insert into w_tipos_sanitario   values (5),(6);
           end if;
    end;

    select fc_getsession('DB_funcao_vistorias_sanitario')
      into sTipoVistoriaSanitario;

    if sTipoVistoriaSanitario is not null and sTipoVistoriaSanitario <> '' then

      delete from w_tipos_sanitario;
      insert into w_tipos_sanitario select unnest(string_to_array(sTipoVistoriaSanitario, ','))::integer;
    end if;

    select extract(year from y70_data), y70_data
      into iAnousu, dDataVistoria
      from vistorias
     where y70_codvist = iCodigoVistoria;

    /**
     *  Verifica se a vistoria eh parcial ou geral, para montar a data de vencimento a ser gravada no arrecad
     */
    select y70_parcial
      into V_PARCIAL
      from vistorias
     where y70_codvist = iCodigoVistoria;

    if V_PARCIAL is not null AND V_PARCIAL = false then

      perform fc_debug('<fc_vistorias> GERAL', lRaise);

      select y77_diasgeral, y77_mesgeral, y70_data
        into  V_DIASGERAL,V_MESGERAL,V_DATA
        from tipovistorias
             inner join vistorias on y77_codtipo = y70_tipovist
       where y70_codvist = iCodigoVistoria;


      if V_DIASGERAL is null OR
         V_DIASGERAL = 0     OR
         V_MESGERAL is null  OR
         V_MESGERAL = 0      then

        return '01- TIPO DE VISTORIA SEM DIA OU MES PARA VENCIMENTO CONFIGURADO!';
      end if;

      V_DATAVENC = iAnousu||'-'||V_MESGERAL||'-'||V_DIASGERAL;

    else

      perform fc_debug('<fc_vistorias> PARCIAL', lRaise);

      select y77_dias, y70_data, y70_data, y77_diasgeral, y77_mesgeral
        into V_DIASVENC, V_DATA, V_DATAVENC, V_DIASGERAL, V_MESGERAL
        from tipovistorias
             inner join vistorias on y77_codtipo = y70_tipovist
       where y70_codvist = iCodigoVistoria;

      if V_DIASVENC is null OR V_DIASVENC = 0 then

        if V_DIASGERAL is null then
          return '02- TIPO DE VISTORIA SEM DIAS PARA VENCIMENTO CONFIGURADO!';
        else
          V_DATAVENC = iAnousu||'-'||V_MESGERAL||'-'||V_DIASGERAL;
        end if;
      end if;

      perform fc_debug('<fc_vistorias> V_DIASVENC: ' || V_DIASVENC || ' V_DATAVENC: ' || V_DATAVENC, lRaise);

      /**
       * V_DATA = V_DATAVENC;
       */
      if V_DIASVENC is null then
        V_DIASVENC = 0;
      end if;

      select V_DATAVENC + V_DIASVENC
        into V_DATAVENC;

    end if;

    perform fc_debug('<fc_vistorias> V_DATAVENC: ' || V_DATAVENC, lRaise);

    select y32_formvist,
           y32_calculavistoriamei
      into iFormulaCalculoVistoria,
           lCalculaVistoriaMei
      from parfiscal
     where y32_instit = iInstit ;

    select q04_vbase
      into nValorBase
      from cissqn
     where cissqn.q04_anousu = iAnousu;

    if nValorBase = 0 OR nValorBase is null then
      return '03- SEM VALOR BASE CADASTRADO NOS PARAMETROS ';
    end if;

    select distinct i02_valor
      into nValorInflator
      from cissqn
           inner join infla on q04_inflat = i02_codigo
      where cissqn.q04_anousu       = iAnousu
        and date_part('y',i02_data) = iAnousu;

    perform fc_debug('<fc_vistorias> Inflator: ' || nValorInflator, lRaise);

    if nValorInflator is null then
      nValorInflator = 1;
    end if;

    select y74_codsani,y80_numcgm,y69_numpre
      into V_Y74_CODSANI, V_Y80_NUMCGM, V_Y69_NUMPRE
      from vistsanitario
           inner join sanitario      on y74_codsani = y80_codsani
           left  join vistorianumpre on y69_codvist = iCodigoVistoria
     where Y74_CODVIST = iCodigoVistoria;

    perform fc_debug('<fc_vistorias> V_Y74_CODSANI: ' || V_Y74_CODSANI || ' iCodigoVistoria: ' || iCodigoVistoria, lRaise);

    if V_Y74_CODSANI = 0 OR V_Y74_CODSANI is null then

      lIsSanitario = false;
      select y71_inscr,q02_numcgm,y69_numpre
        into V_Y71_INSCR,V_Q02_NUMCGM,V_Y69_NUMPRE
        from vistinscr
             inner join issbase        on q02_inscr   = y71_inscr
             left  join vistorianumpre on y69_codvist = iCodigoVistoria
      where Y71_CODVIST = iCodigoVistoria;

      if V_Y71_INSCR is null then
        V_INSCR = false;
      else
        V_INSCR = true;
      end if;

    else
      lIsSanitario = true;
    end if;

    perform fc_debug('<fc_vistorias> lIsSanitario: ' || lIsSanitario || ' iFormulaCalculoVistoria: ' || iFormulaCalculoVistoria, lRaise);

    if iFormulaCalculoVistoria = 1 then

      if lIsSanitario = true OR V_INSCR = true then

        if lIsSanitario is true then

          V_ACHOU = false;
          perform fc_debug('<fc_vistorias> V_Y74_CODSANI: ' || V_Y74_CODSANI, lRaise);

          select min(q85_forcal)
            into iFormaCalculo
            from tipcalc
                 inner join cadcalc       on tipcalc.q81_cadcalc    = cadcalc.q85_codigo
                 inner join ativtipo      on ativtipo.q80_tipcal    = tipcalc.q81_codigo
                 inner join saniatividade on saniatividade.y83_ativ = ativtipo.q80_ativ
           where saniatividade.y83_codsani = V_Y74_CODSANI
             and saniatividade.y83_dtfim is null
             and tipcalc.q81_tipo in ( select codigo from w_tipos_sanitario );

          if iFormaCalculo is null then
            return '11-SEM FORMA DE CALCULO ENCONTRADA (SANI)!';
          end if;

          perform fc_debug('<fc_vistorias> iFormaCalculo: ' || iFormaCalculo, lRaise);

          if iFormaCalculo = 1 then

            select q80_ativ
              into iCodigoAtividade
              from saniatividade
                   inner join ativtipo on saniatividade.y83_ativ = ativtipo.q80_ativ
                   inner join tipcalc  on tipcalc.q81_codigo     = ativtipo.q80_tipcal
             where y83_codsani = V_Y74_CODSANI
               and y83_dtfim is null
               and q81_tipo in ( select codigo from w_tipos_sanitario )
               and y83_ativprinc is true;

            if iCodigoAtividade is not null then
              V_ACHOU = true;
            end if;

          elsif iFormaCalculo = 2 then

              select  Q80_ATIV
                into iCodigoAtividade
                from saniatividade
                     inner join ativtipo on saniatividade.y83_ativ = ativtipo.q80_ativ
                     inner join tipcalc  on tipcalc.q81_codigo     = ativtipo.q80_tipcal
               where y83_codsani = V_Y74_CODSANI
                 and y83_dtfim is null
                 and q81_tipo in ( select codigo from w_tipos_sanitario )
            order by q81_valexe desc
               limit 1;

            if iCodigoAtividade is not null then
              V_ACHOU = true;
            end if;

          end if;

          perform fc_debug('<fc_vistorias> iCodigoAtividade: ' || iCodigoAtividade, lRaise);

          if V_ACHOU is false then
            return '04- NENHUMA ATIVIDADE COM TIPO 6 CADASTRADA';
          end if;
        end if;

        if V_INSCR = true then

          select MIN(Q85_FORCAL)
          into iFormaCalculo
          from TIPCALC
          INNER JOIN CADCALC                ON TIPCALC.Q81_CADCALC = CADCALC.Q85_CODIGO
          INNER JOIN ATIVTIPO               ON ATIVTIPO.Q80_TIPCAL = TIPCALC.Q81_CODIGO
          INNER JOIN TABATIV          ON TABATIV.Q07_ATIV = ATIVTIPO.Q80_ATIV
          where Q07_INSCR = V_Y71_INSCR AND
          TABATIV.Q07_DATAFI is null AND
          TIPCALC.Q81_TIPO IN ( select codigo from w_tipos_localizacao );

          if iFormaCalculo is null then

            select MIN(Q85_FORCAL)
            into iFormaCalculo
            from ISSPORTETIPO
            INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR
                                   and q45_codporte = q41_codporte
            INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
            INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
            INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
            INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
            INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr and ATIVPRINC.q88_seq = TABATIV.q07_seq
            where Q45_CODPORTE = Q41_CODPORTE AND Q81_TIPO IN ( select codigo from w_tipos_localizacao ) and
            case when q07_datafi is null then true else q07_datafi >= V_DATA end AND
            q07_databx is null;

            if iFormaCalculo is null then
              return '17-SEM FORMA DE CALCULO ENCONTRADA (INSCR)!';
            end if;

          end if;

          perform fc_debug('Forma de Calculo encontrada: ' || iFormaCalculo, lRaise);
          perform fc_debug('v_data: ' ||v_data ||' V_Y71_INSCR: ' || V_Y71_INSCR, lRaise);

          /**
           * Pontuacao das classes
           */
            select Q82_ATIV, MAX(Q25_PONTUACAO)
              into iCodigoAtividade, nPontuacaoClasse
              from TABATIV
                   INNER JOIN CLASATIV   ON Q82_ATIV   = Q07_ATIV
                   INNER JOIN CLASSEPONT ON Q25_CLASSE = Q82_CLASSE
             where Q07_INSCR = V_Y71_INSCR
               AND case when q07_datafi is null
                        then true
                        else q07_datafi >= V_DATA end
               AND q07_databx is null
          GROUP BY Q82_ATIV
          ORDER BY MAX(Q25_PONTUACAO) DESC
             limit 1;

          if nPontuacaoClasse is not null then

            /**
             * Pontuacao zona fiscal
             */
            select Q26_PONTUACAO
              into iPontuacaoPorZonaFiscal
              from ZONAPONT
                   INNER JOIN ISSZONA ON Q26_ZONA = Q35_ZONA
             where Q35_INSCR = V_Y71_INSCR;

            if iPontuacaoPorZonaFiscal is null then
              return '12-PONTUACAO DA ZONA NAO ENCONTRADA';
            end if;

            /**
             * Pontuacao empregados/area
             */
            select Q30_QUANT, Q30_AREA
              into iPontuacaoPorEmpregadosArea, V_AREA
              from ISSQUANT
             where ISSQUANT.Q30_INSCR  = V_Y71_INSCR
               AND ISSQUANT.Q30_ANOUSU = iAnousu;

            if iPontuacaoPorEmpregadosArea is null then
              select Q30_QUANT, Q30_AREA
              from ISSQUANT
              into iPontuacaoPorEmpregadosArea, V_AREA
              where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu - 1);
              if iPontuacaoPorEmpregadosArea is null then
                select Q30_QUANT, Q30_AREA
                from ISSQUANT
                into iPontuacaoPorEmpregadosArea, V_AREA
                where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu + 1);
                if iPontuacaoPorEmpregadosArea is null then
                  INSERT into ISSQUANT select * from ISSQUANT where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu - 1);
                end if;
              end if;
            end if;

            /**
             * Pontuacao pelos empregados
             */
            select Q27_PONTUACAO
              into iPontuacaoPorEmpregados
              from EMPREGPONT
             where iPontuacaoPorEmpregadosArea >= Q27_QUANTINI AND
                   iPontuacaoPorEmpregadosArea <= Q27_QUANTFIM;

            if iPontuacaoPorEmpregados is null then
              return '13-PONTUACAO DO NUMERO DE EMPREGADOS NAO ENCONTRADA';
            end if;

            if lRaise is true then
              raise notice 'V_AREA: %', V_AREA;
            end if;

            /**
             * Pontuacao pela area
             */
            select Q28_PONTUACAO
              into iPontuacaoPorArea
              from AREAPONT
             where V_AREA >= Q28_QUANTINI
               AND V_AREA <= Q28_QUANTFIM;

            if iPontuacaoPorArea is null then
              return '14-PONTUACAO DA AREA NAO ENCONTRADA';
            end if;

            if lRaise is true then
              raise notice 'nPontuacaoClasse: % - iPontuacaoPorZonaFiscal: %  - iPontuacaoPorEmpregados: %  - iPontuacaoPorArea: %', nPontuacaoClasse, iPontuacaoPorZonaFiscal, iPontuacaoPorEmpregados, iPontuacaoPorArea;
            end if;

            iPontuacaoGeral = nPontuacaoClasse + iPontuacaoPorZonaFiscal + iPontuacaoPorEmpregados + iPontuacaoPorArea;

            perform fc_debug('Pontuacaogeral: ' || iPontuacaoGeral ,lRaise);

            select Q81_CODIGO, Q81_RECEXE, Q92_HIST, Q81_VALEXE, Q92_TIPO
              into V_ATIVTIPO, iCodigoReceitaExercicio, iCodigoHistoricoCalculo, nValorExercicio, iCodigoArretipo
              from TIPCALC
                   inner join tipcalcexe on tipcalcexe.q83_anousu = iAnousu
                                        and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                   INNER JOIN CADVENCDESC ON Q92_CODIGO = tipcalcexe.Q83_CODVEN
             where iPontuacaoGeral >= Q81_QIEXE
               AND iPontuacaoGeral <= Q81_QFEXE
               AND Q81_TIPO IN ( select codigo from w_tipos_localizacao );

         /**
          * Por ativtipo
          */
          else

            if iFormaCalculo = 1 then

              select Q80_ATIV
                into iCodigoAtividade
                from TABATIV
                     INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr
                                         and ATIVPRINC.q88_seq = TABATIV.q07_seq
                     INNER JOIN ATIVTIPO  ON TABATIV.Q07_ativ = ATIVTIPO.q80_ativ
                     INNER JOIN TIPCALC   ON TIPCALC.Q81_CODIGO = ATIVTIPO.Q80_TIPCAL
              where Q07_INSCR = V_Y71_INSCR
                AND TABATIV.Q07_DATAFI is null
                AND Q81_TIPO IN ( select codigo from w_tipos_localizacao );

              if iCodigoAtividade is not null then
                V_ACHOU = true;
              else

                select Q07_ATIV
                  into iCodigoAtividade
                  from ISSPORTETIPO
                       INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR and q45_codporte = q41_codporte
                       INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
                       INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
                       INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
                       INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
                       INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr
                                           and ATIVPRINC.q88_seq = TABATIV.q07_seq
                where Q45_CODPORTE = Q41_CODPORTE
                  AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
                  and case when q07_datafi is null then true else q07_datafi >= V_DATA end
                  AND q07_databx is null;

                if iCodigoAtividade is not null then
                  V_ACHOU = true;
                end if;

              end if;

            elsif iFormaCalculo = 2 then

                select Q80_ATIV
                  into iCodigoAtividade
                  from TABATIV
                       INNER JOIN ATIVTIPO ON TABATIV.Q07_ativ = ATIVTIPO.q80_ativ
                       INNER JOIN TIPCALC ON TIPCALC.Q81_CODIGO = ATIVTIPO.Q80_TIPCAL
                 where Q07_INSCR = V_Y71_INSCR
                   AND TABATIV.Q07_DATAFI is null
                   AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
              ORDER BY Q81_VALEXE DESC
                 LIMIT 1;

              if iCodigoAtividade is not null then
                V_ACHOU = true;
              end if;

            end if;

            if V_ACHOU is false then
              return '16 - SEM ATIVIDADE PRINCIPAL';
            end if;

            select TIPCALC.Q81_CODIGO
              into V_ATIVTIPO
              from ATIVTIPO
                   INNER JOIN TABATIV ON Q07_ATIV = q80_ativ
                   INNER JOIN TIPCALC ON Q80_TIPCAL = Q81_CODIGO
                   INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
             where Q81_TIPO IN ( select codigo from w_tipos_localizacao )
               AND Q07_INSCR = V_Y71_INSCR
               AND case when q07_datafi is null then true else q07_datafi >= V_DATA end
               AND q07_databx is null
               AND Q07_ATIV = iCodigoAtividade;

            if V_ATIVTIPO is null then

              select TIPCALC.Q81_CODIGO
                into V_ATIVTIPO
                from ISSPORTETIPO
                     INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR and q45_codporte = q41_codporte
                     INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
                     INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
                     INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
                     INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
               where Q45_CODPORTE = Q41_CODPORTE
                 AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
                 and case when q07_datafi is null then true else q07_datafi >= V_DATA end
                 AND q07_databx is null
                 AND Q82_ATIV = iCodigoAtividade;

              if V_ATIVTIPO is null then
                return '06-SEM TIPO DE CALCULO CONFIGURADO!';
              end if;
            end if;

          end if;

        end if;

        if V_Y69_NUMPRE = 0 OR V_Y69_NUMPRE is null then

          select NEXTVAL('numpref_k03_numpre_seq')
            into iNumpreGerado;

          INSERT into VISTORIANUMPRE VALUES(iCodigoVistoria, iNumpreGerado);
        else

          iNumpreGerado = V_Y69_NUMPRE;
          select k00_numpre
            into iNumpreArrecant
            from arrecant
           where k00_numpre = iNumpreGerado;

          if iNumpreArrecant != 0 OR iNumpreArrecant is not null then
            return '07- VISTORIA JA PAGA OU CANCELADA ';
          end if;

          select k00_numpre
            into iNumpreArrecad
            from arrecad
           where k00_numpre = iNumpreGerado;

          if iNumpreArrecad != 0 OR iNumpreArrecad is not null then
            delete from arrecad where k00_numpre = iNumpreGerado;
          end if;

        end if;
      end if;

      perform fc_debug('Verifica se sanitario lIsSanitario: ' || lIsSanitario,  lRaise);
      perform fc_debug('                           V_INSCR: ' || V_INSCR, lRaise);

      /**
       * SE FOR POR SANITARIO SEGUE AQUI
       */
      if lIsSanitario = true then

        FOR rSaniAtividade IN
          select Y83_ATIV
            from SANIATIVIDADE
           where Y83_CODSANI = V_Y74_CODSANI
             AND Y83_ATIV    = iCodigoAtividade

          LOOP

          if lRaise is true then
            raise notice 'Y83_ATIV (2): % - anousu: %', rSaniAtividade.Y83_ATIV, iAnousu;
          end if;

          select Y18_INSCR
            into V_Y74_INSCRSANI
            from sanitarioinscr
           where y18_codsani = V_Y74_CODSANI;

          if lCalculaVistoriaMei is false then
            lContribuinteMei := fc_verifica_contribuinte_mei(V_Y74_INSCRSANI, dDataAtual);

            if lContribuinteMei then

              delete from vistorianumpre where y69_codvist = iCodigoVistoria and y69_numpre = iNumpreGerado;
              return '25 - CONTRIBUINTE OPTANTE PELO SIMPLES NACIONAL NA CATEGORIA MEI';
            end if;
          end if;

          select q81_recexe, q92_hist, q81_valexe, q92_tipo,
                 (select distinct q83_codven
                    from tipcalcexe
                   where q83_tipcalc = q81_codigo
                     and q83_anousu  = iAnousu)
            into iCodigoReceitaExercicio,
                 iCodigoHistoricoCalculo,
                 nValorExercicio,
                 iCodigoArretipo,
                 iCodVencimento
            from ATIVTIPO
                 inner join tipcalc     on q80_tipcal             = q81_codigo
                 inner join tipcalcexe  on tipcalcexe.q83_anousu  = iAnousu
                                       and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                 inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven
           where q80_ativ = rsaniatividade.y83_ativ
             and ( select y80_area from sanitario where y80_codsani = V_Y74_CODSANI) >= q81_qiexe
             and ( select y80_area from sanitario where y80_codsani = V_Y74_CODSANI) <= q81_qfexe
             and q81_tipo in ( select codigo from w_tipos_sanitario );

          if iCodigoReceitaExercicio is not null then

            nValorVistoria = round(nValorExercicio * nValorInflator * nValorBase,2);

            -- Verifica se esta instalado o plugin para calcular a vistoria atrasada em dobro.
            perform *
               from db_plugin
              where db145_nome = 'PluginVistoriasAtrasadas'
                and db145_situacao is true;

            if found then

              lVistoriaAtrasada :=  fc_verifica_vistoria_atrasada(V_Y74_CODSANI, iCodigoVistoria, iAnousu);

              if lVistoriaAtrasada is true then
                nValorVistoria := nValorVistoria * 2;
              end if ;
            end if;


            if lRaise is true then
              raise notice 'inserindo no arrecad... nValorVistoria (2): % - iCodVencimento: %', nValorVistoria, iCodVencimento;
            end if;

            lCalculou = true;
            --
            -- Inserindo por sanitario
            --

            --
            -- Funcao para gerar o financeiro
            --
            if iCodVencimento is null then
              return '18-SEM VENCIMENTO CONFIGURADO PARA O EXERCICIO!';
            end if;

            if lRaise is true then
              raise notice 'executando fc_gerafinanceiro(%,%,%,%,%,%,%)', iNumpreGerado,nValorVistoria,iCodVencimento,V_Y80_NUMCGM,V_DATA,iCodigoReceitaExercicio,dDataVistoria;
            end if;

            select *
              into rFinanceiro
              from fc_gerafinanceiro(iNumpreGerado,nValorVistoria,iCodVencimento,V_Y80_NUMCGM,V_DATA,iCodigoReceitaExercicio,dDataVistoria);

            if V_Y74_INSCRSANI is not null then

              select k00_numpre
                into iNumpreArreinscr
                from arreinscr
               where k00_numpre = iNumpreGerado;

              if iNumpreArreinscr != 0 OR iNumpreArreinscr is not null then
                DELETE from ARREINSCR where K00_NUMPRE = iNumpreArreinscr;
              end if;

              INSERT into ARREINSCR (k00_numpre, k00_inscr)
                             VALUES (iNumpreGerado, V_Y74_INSCRSANI);

            end if;
          end if;
        end loop;

        if lCalculou IS true then
          return '08 - OK ';
        else
          return '15 - ERRO DURANTE O CALCULO';
        end if;
      /**
       * FIM DO if DO SANITARIO
       */

      /**
       * SE FOR POR INSCRICAO SEGUE AQUI
       */
      elsif V_INSCR = true then

        perform fc_debug('<fc_vistorias> Inscricao - nPontuacaoClasse: ' || nPontuacaoClasse || ' iCodigoAtividade: ' || iCodigoAtividade, lRaise);

        if lCalculaVistoriaMei is false then

          lContribuinteMei := fc_verifica_contribuinte_mei(V_Y71_INSCR, dDataAtual);

          if lContribuinteMei then

            delete from vistorianumpre where y69_codvist = iCodigoVistoria and y69_numpre = iNumpreGerado;
            return '25 - CONTRIBUINTE OPTANTE PELO SIMPLES NACIONAL NA CATEGORIA MEI';
          end if;
        end if;

        if nPontuacaoClasse is null then

          sSql = sSql || ' select q81_qiexe, q81_qfexe, q81_codigo, q81_uqcad, q81_uqtab                ';
          sSql = sSql || '   from ativtipo                                                              ';
          sSql = sSql || '          inner join tipcalc on q81_codigo = q80_tipcal                       ';
          sSql = sSql || '  where q80_ativ = ' || iCodigoAtividade;
          sSql = sSql || '    and q81_tipo in ( select codigo from w_tipos_localizacao )                ';
          sSql = sSql || ' UNION                                                                        ';
          sSql = sSql || ' select q81_qiexe, q81_qfexe, q81_codigo, q81_uqcad, q81_uqtab                ';
          sSql = sSql || '   from issportetipo                                                          ';
          sSql = sSql || '        inner join issbaseporte on q45_inscr          = ' || V_Y71_INSCR;
          sSql = sSql || '        inner join tipcalc      on q41_codtipcalc     = q81_codigo            ';
          sSql = sSql || '        inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc   ';
          sSql = sSql || '        inner join clasativ     on q82_classe         = q41_codclasse         ';
          sSql = sSql || '  where q45_codporte = q41_codporte                                           ';
          sSql = sSql || '    and q81_tipo in (select codigo from w_tipos_localizacao )                 ';
          sSql = sSql || '    and q82_ativ = ' || iCodigoAtividade;

          select q60_campoutilcalc
            into v_tipo_quant
            from parissqn;

          if v_tipo_quant = 2 then

            select q30_quant
              into V_AREA
              from issquant
             where q30_inscr  = V_Y71_INSCR
               and q30_anousu = iAnousu;
          else

            select q30_area
              into V_AREA
              from issquant
             where q30_inscr  = V_Y71_INSCR
               and q30_anousu = iAnousu;
          end if;

          perform fc_debug('<fc_vistorias> V_AREA: ' || V_AREA, lRaise);
          perform fc_debug('<fc_vistorias> inscr : ' || V_Y71_INSCR, lRaise);
          perform fc_debug('<fc_vistorias> anousu: ' || iAnousu, lRaise);

          if V_AREA is null then
            V_AREA = 0;
          end if;

        else

          sSql = sSql || ' select q81_codigo,                                                              ';
          sSql = sSql || '        q81_recexe,                                                              ';
          sSql = sSql || '        q92_hist,                                                                ';
          sSql = sSql || '        q81_valexe,                                                              ';
          sSql = sSql || '        q92_tipo,                                                                ';
          sSql = sSql || '        q81_qiexe,                                                               ';
          sSql = sSql || '        q81_qfexe,                                                               ';
          sSql = sSql || '        q81_uqcad,                                                               ';
          sSql = sSql || '        q81_uqtab                                                                ';
          sSql = sSql || '   from ativtipo                                                                 ';
          sSql = sSql || '        inner join tipcalc     on tipcalc.q81_codigo     = ativtipo.q80_tipcal   ';
          sSql = sSql || '        inner join tipcalcexe  on tipcalcexe.q83_anousu  = ' || iAnousu;
          sSql = sSql || '                              and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo    ';
          sSql = sSql || '        inner join cadcalc     on q81_cadcalc            = q85_codigo            ';
          ssql = ssql || '        inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven ';
          sSql = sSql || '      where ' || iPontuacaoGeral || ' >= q81_qiexe and                          ';
          sSql = sSql ||                iPontuacaoGeral || '    <= q81_qfexe and                          ';
          sSql = sSql || '        Q81_TIPO IN ( select codigo from w_tipos_localizacao ) AND ATIVTIPO.Q80_ATIV = ' || iCodigoAtividade;

          V_AREA = iPontuacaoGeral;

          perform fc_debug('<fc_vistorias> V_AREA: ' || V_AREA, lRaise);
        end if;

        select count(*)
          into iQuantidadeAtividades
          from ( select distinct
                        q07_seq
                   from tabativ
                        inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                        inner join tipcalc on q81_codigo = q80_tipcal
                  where q81_tipo in (3,5,6)
                    and q07_inscr = V_Y71_INSCR
                    and (q07_datafi is null or q07_datafi >= current_date)
                    and (q07_databx is null or q07_databx >= current_date) ) as x;

        perform fc_debug('<fc_vistorias> iQuantidadeAtividades: ' || iQuantidadeAtividades, lRaise);

        FOR rAtivtipo IN EXECUTE sSql LOOP

          if lRaise is true then
            raise notice 'dentro do for... vcalculou : % - tipcalc: % - area: % - qiexe: % - qfexe: %',lCalculou, rAtivtipo.Q81_CODIGO, V_AREA, rAtivtipo.Q81_QIEXE, rAtivtipo.Q81_QFEXE;
          end if;

          if lRaise is true then
            raise notice 'antes do if... area - % q81_qiexe - % q81_qfexe - %',V_AREA,rAtivtipo.Q81_QIEXE,rAtivtipo.Q81_QFEXE;
          end if;

          if V_AREA >= rAtivtipo.Q81_QIEXE and V_AREA <= rAtivtipo.Q81_QFEXE then

            select q81_recexe, q92_hist, q81_valexe, q92_tipo, q81_excedenteativ,
                   (select distinct q83_codven from tipcalcexe where q83_tipcalc = q81_codigo and q83_anousu = iAnousu)
              into iCodigoReceitaExercicio,
                   iCodigoHistoricoCalculo,
                   nValorExercicio,
                   iCodigoArretipo,
                   nValorExcedente, -- Valor excedente configurado na atividade
                   iCodVencimento
              from tipcalc
                   inner join tipcalcexe  on tipcalcexe.q83_anousu  = ianousu
                                         and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                   inner join cadcalc     on q81_cadcalc            = q85_codigo
                   inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven
            where Q81_CODIGO = rAtivtipo.Q81_CODIGO;


            if iCodVencimento is null then
              return '18-SEM VENCIMENTO CONFIGURADO PARA O EXERCICIO!';
            end if;

            perform fc_debug('Calculando valor do debito:',        lRaise);
            perform fc_debug('nValorExercicio  : ' || nValorExercicio,   lRaise);
            perform fc_debug('nValorInflator: ' || nValorInflator, lRaise);
            perform fc_debug('nValorBase    : ' || nValorBase,     lRaise);

            /**
             * Verifica se o parametro Utiliza quantidade do cadastro configurado no tipo de calculo esta setado
             */
            if rAtivtipo.q81_uqcad is true then

              select q30_mult
                into nMultiplicadorIssQuantidade
                from issquant
               where q30_inscr  = v_y71_inscr
                 and q30_anousu = iAnousu;
              if not found then
                  return '22 - inscricao sem multiplicador cadastrado na issquant';
              end if;

              if nMultiplicadorIssQuantidade is null or nMultiplicadorIssQuantidade = 0 then
                  nMultiplicadorIssQuantidade := 1;
              end if;
            end if;

            /**
             * Verifica se o parametro Utiliza quantidade da tabela de atividades configurado no
             * tipo de calculo esta setado
             */
            if rAtivtipo.q81_uqtab is true then

               select q07_quant
                 into nQuantidadeAtividade
                 from tabativ
                where q07_inscr = v_y71_inscr
                  and q07_ativ = iCodigoAtividade
                  and q07_databx is null;
               if not found then
                  return '23 - inscricao sem atividade cadastrada na tabativ ou atividade baixada';
               end if;

               if nQuantidadeAtividade is null or nQuantidadeAtividade = 0 then
                  nQuantidadeAtividade := 1;
               end if;
            end if;

            nValorVistoria = round(nValorExercicio * nValorInflator * nValorBase * nMultiplicadorIssQuantidade * nQuantidadeAtividade, 2);
            perform fc_debug('Resultado -> nValorVistoria: ' || nValorVistoria,  lRaise);

            lCalculou = true;

            if lRaise is true then
              raise notice 'nValorExcedente-: % nValorVistoria (1): % - k00_numpre: % - nValorInflator: % - nValorBase: %', nValorExcedente, nValorVistoria, iNumpreGerado, nValorInflator, nValorBase;
            end if;

            if nValorExcedente > 0 then

              raise notice 'valor antes: %', nValorVistoria;
              nValorVistoria = nValorVistoria + (nValorVistoria * 0.3 * (iQuantidadeAtividades - 1));
              raise notice 'valor depois: %', nValorVistoria;
            end if;

            --
            -- Funcao para gerar o financeiro
            --
            select *
              into rFinanceiro
              from fc_gerafinanceiro(iNumpreGerado, nValorVistoria, iCodVencimento, V_q02_NUMCGM, V_DATA, iCodigoReceitaExercicio, dDataVistoria);

            select k00_numpre
              into iNumpreArreinscr
              from arreinscr
             where k00_numpre = iNumpreGerado;

            if iNumpreArreinscr != 0 OR iNumpreArreinscr is not null then
              DELETE from ARREINSCR where K00_NUMPRE = iNumpreArreinscr;
            end if;
            insert into arreinscr (k00_numpre, k00_inscr)
                           values (iNumpreGerado, V_Y71_INSCR);
          end if;

        end loop;

        if lRaise is true then
          raise notice 'fora do for... lCalculou: %', lCalculou;
        end if;

        if lCalculou IS true then
          return '09-OK INSCRICAO NUMERO ' || V_Y71_INSCR;
        else
          return '19-OCORREU ALGUM ERRO DURANTE O CALCULO (2)!!!';
        end if;

      end if;

      if V_INSCR = false AND lIsSanitario = false then
          return '10- CALCULO NAO CONFIGURADO PARA A VISTORIA NUMERO ' || iCodigoVistoria;
      end if;

    else
          return '20-PROCEDIMENTO NÃO PREPARADO PARA CALCULO POR FORMA DIFERENTE DE 1 (NORMAL)';
    end if;

  end;

$$ language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL
create or replace function fc_vistorias(integer) returns varchar(200)
as $$
DECLARE

  iCodigoVistoria             alias for $1;

  V_ATIVTIPO                  integer;
  iNumpreGerado               integer;
  iNumpreArreinscr            integer;
  iNumpreArrecant             integer;
  iNumpreArrecad              integer;
  V_DATA                      date;

  V_DIASGERAL                 integer;
  V_MESGERAL                  integer;
  V_PARCIAL                   boolean;

  V_ACHOU                     boolean default false;
  lCalculou                   boolean default false;
  V_DATAVENC                  date;
  V_Y74_CODSANI               integer;
  V_Y74_INSCRSANI             integer;
  V_Y80_NUMCGM                integer;
  V_Y69_NUMPRE                integer;
  iCodigoReceitaExercicio     integer;
  iCodigoHistoricoCalculo     integer;
  nValorExercicio             float8;
  iCodigoArretipo             integer;
  V_Y71_INSCR                 integer;
  V_Q02_NUMCGM                integer;
  V_ATIV                      integer;
  iAnousu                     integer;
  iCodigoAtividade            integer;
  V_DIASVENC                  integer;
  iFormaCalculo               integer;
  iCodVencimento              integer default 0;

  lIsSanitario                boolean;
  V_INSCR                     boolean;
  V_AREA                      float8;
  nValorInflator              float8;
  nValorBase                  float8;
  nValorVistoria              float8;
  sSql                        text default '';
  nValorExcedente             float8;
  iQuantidadeAtividades       integer default 0;

  iFormulaCalculoVistoria     integer;

  nPontuacaoClasse            integer default NULL;
  iPontuacaoPorZonaFiscal     integer;
  iPontuacaoPorEmpregadosArea integer;
  iPontuacaoPorEmpregados     integer;
  iPontuacaoPorArea           integer;
  iPontuacaoGeral             integer;

  v_tipo_quant                integer;

  iInstit                     integer;
  iCodCli                     integer;

  dDataVistoria               date;
  dDataAtual                  date;

  rAtivtipo                   record;
  rSaniAtividade              record;
  rFinanceiro                 record;

  sTipoVistoriaSanitario      text;

  lRaise                      boolean default true;

  lCalculaVistoriaMei         boolean default true;
  lContribuinteMei            boolean default false;

  nQuantidadeAtividade        float8 default 1; -- Quantidade informada no campo q07_quant da tabativ
  nMultiplicadorIssQuantidade float8 default 1; -- Quantidade informada no campo q30_multi da issquant

  BEGIN

    /**
     * Verifica pl por Cliente Especifico
     */
    select db21_codcli
      into iCodCli
      from db_config
     where prefeitura is true;

    lRaise     := ( case when fc_getsession('DB_debugon') is null then false else true end );
    iInstit    := fc_getsession('DB_instit');
    dDataAtual := fc_getsession('DB_datausu');


    /**
     * 3 = VISTORIA LOCALIZACAO
     * 5 = TAXA
     * 6 = VISTORIA SANITARIO
     */
    begin

      if iCodCli = 4 then

        create temp table w_tipos_localizacao as select 3 as codigo;
        create temp table w_tipos_sanitario   as select 6 as codigo;
      else

        create temp table w_tipos_localizacao as select 3 as codigo union select 5;
        create temp table w_tipos_sanitario   as select 5 as codigo union select 6;
      end if;
    exception

         when duplicate_table then

           truncate w_tipos_localizacao;
           truncate w_tipos_sanitario;
           if iCodCli = 4 then

             insert into w_tipos_localizacao values (3);
             insert into w_tipos_sanitario   values (6);
           else

             insert into w_tipos_localizacao values (3),(5);
             insert into w_tipos_sanitario   values (5),(6);
           end if;
    end;

    select fc_getsession('DB_funcao_vistorias_sanitario')
      into sTipoVistoriaSanitario;

    if sTipoVistoriaSanitario is not null and sTipoVistoriaSanitario <> '' then

      delete from w_tipos_sanitario;
      insert into w_tipos_sanitario select unnest(string_to_array(sTipoVistoriaSanitario, ','))::integer;
    end if;

    select extract(year from y70_data), y70_data
      into iAnousu, dDataVistoria
      from vistorias
     where y70_codvist = iCodigoVistoria;

    /**
     *  Verifica se a vistoria eh parcial ou geral, para montar a data de vencimento a ser gravada no arrecad
     */
    select y70_parcial
      into V_PARCIAL
      from vistorias
     where y70_codvist = iCodigoVistoria;

    if V_PARCIAL is not null AND V_PARCIAL = false then

      perform fc_debug('<fc_vistorias> GERAL', lRaise);

      select y77_diasgeral, y77_mesgeral, y70_data
        into  V_DIASGERAL,V_MESGERAL,V_DATA
        from tipovistorias
             inner join vistorias on y77_codtipo = y70_tipovist
       where y70_codvist = iCodigoVistoria;


      if V_DIASGERAL is null OR
         V_DIASGERAL = 0     OR
         V_MESGERAL is null  OR
         V_MESGERAL = 0      then

        return '01- TIPO DE VISTORIA SEM DIA OU MES PARA VENCIMENTO CONFIGURADO!';
      end if;

      V_DATAVENC = iAnousu||'-'||V_MESGERAL||'-'||V_DIASGERAL;

    else

      perform fc_debug('<fc_vistorias> PARCIAL', lRaise);

      select y77_dias, y70_data, y70_data, y77_diasgeral, y77_mesgeral
        into V_DIASVENC, V_DATA, V_DATAVENC, V_DIASGERAL, V_MESGERAL
        from tipovistorias
             inner join vistorias on y77_codtipo = y70_tipovist
       where y70_codvist = iCodigoVistoria;

      if V_DIASVENC is null OR V_DIASVENC = 0 then

        if V_DIASGERAL is null then
          return '02- TIPO DE VISTORIA SEM DIAS PARA VENCIMENTO CONFIGURADO!';
        else
          V_DATAVENC = iAnousu||'-'||V_MESGERAL||'-'||V_DIASGERAL;
        end if;
      end if;

      perform fc_debug('<fc_vistorias> V_DIASVENC: ' || V_DIASVENC || ' V_DATAVENC: ' || V_DATAVENC, lRaise);

      /**
       * V_DATA = V_DATAVENC;
       */
      if V_DIASVENC is null then
        V_DIASVENC = 0;
      end if;

      select V_DATAVENC + V_DIASVENC
        into V_DATAVENC;

    end if;

    perform fc_debug('<fc_vistorias> V_DATAVENC: ' || V_DATAVENC, lRaise);

    select y32_formvist,
           y32_calculavistoriamei
      into iFormulaCalculoVistoria,
           lCalculaVistoriaMei
      from parfiscal
     where y32_instit = iInstit ;

    select q04_vbase
      into nValorBase
      from cissqn
     where cissqn.q04_anousu = iAnousu;

    if nValorBase = 0 OR nValorBase is null then
      return '03- SEM VALOR BASE CADASTRADO NOS PARAMETROS ';
    end if;

    select distinct i02_valor
      into nValorInflator
      from cissqn
           inner join infla on q04_inflat = i02_codigo
      where cissqn.q04_anousu       = iAnousu
        and date_part('y',i02_data) = iAnousu;

    perform fc_debug('<fc_vistorias> Inflator: ' || nValorInflator, lRaise);

    if nValorInflator is null then
      nValorInflator = 1;
    end if;

    select y74_codsani,y80_numcgm,y69_numpre
      into V_Y74_CODSANI, V_Y80_NUMCGM, V_Y69_NUMPRE
      from vistsanitario
           inner join sanitario      on y74_codsani = y80_codsani
           left  join vistorianumpre on y69_codvist = iCodigoVistoria
     where Y74_CODVIST = iCodigoVistoria;

    perform fc_debug('<fc_vistorias> V_Y74_CODSANI: ' || V_Y74_CODSANI || ' iCodigoVistoria: ' || iCodigoVistoria, lRaise);

    if V_Y74_CODSANI = 0 OR V_Y74_CODSANI is null then

      lIsSanitario = false;
      select y71_inscr,q02_numcgm,y69_numpre
        into V_Y71_INSCR,V_Q02_NUMCGM,V_Y69_NUMPRE
        from vistinscr
             inner join issbase        on q02_inscr   = y71_inscr
             left  join vistorianumpre on y69_codvist = iCodigoVistoria
      where Y71_CODVIST = iCodigoVistoria;

      if V_Y71_INSCR is null then
        V_INSCR = false;
      else
        V_INSCR = true;
      end if;

    else
      lIsSanitario = true;
    end if;

    perform fc_debug('<fc_vistorias> lIsSanitario: ' || lIsSanitario || ' iFormulaCalculoVistoria: ' || iFormulaCalculoVistoria, lRaise);

    if iFormulaCalculoVistoria = 1 then

      if lIsSanitario = true OR V_INSCR = true then

        if lIsSanitario is true then

          V_ACHOU = false;
          perform fc_debug('<fc_vistorias> V_Y74_CODSANI: ' || V_Y74_CODSANI, lRaise);

          select min(q85_forcal)
            into iFormaCalculo
            from tipcalc
                 inner join cadcalc       on tipcalc.q81_cadcalc    = cadcalc.q85_codigo
                 inner join ativtipo      on ativtipo.q80_tipcal    = tipcalc.q81_codigo
                 inner join saniatividade on saniatividade.y83_ativ = ativtipo.q80_ativ
           where saniatividade.y83_codsani = V_Y74_CODSANI
             and saniatividade.y83_dtfim is null
             and tipcalc.q81_tipo in ( select codigo from w_tipos_sanitario );

          if iFormaCalculo is null then
            return '11-SEM FORMA DE CALCULO ENCONTRADA (SANI)!';
          end if;

          perform fc_debug('<fc_vistorias> iFormaCalculo: ' || iFormaCalculo, lRaise);

          if iFormaCalculo = 1 then

            select q80_ativ
              into iCodigoAtividade
              from saniatividade
                   inner join ativtipo on saniatividade.y83_ativ = ativtipo.q80_ativ
                   inner join tipcalc  on tipcalc.q81_codigo     = ativtipo.q80_tipcal
             where y83_codsani = V_Y74_CODSANI
               and y83_dtfim is null
               and q81_tipo in ( select codigo from w_tipos_sanitario )
               and y83_ativprinc is true;

            if iCodigoAtividade is not null then
              V_ACHOU = true;
            end if;

          elsif iFormaCalculo = 2 then

              select  Q80_ATIV
                into iCodigoAtividade
                from saniatividade
                     inner join ativtipo on saniatividade.y83_ativ = ativtipo.q80_ativ
                     inner join tipcalc  on tipcalc.q81_codigo     = ativtipo.q80_tipcal
               where y83_codsani = V_Y74_CODSANI
                 and y83_dtfim is null
                 and q81_tipo in ( select codigo from w_tipos_sanitario )
            order by q81_valexe desc
               limit 1;

            if iCodigoAtividade is not null then
              V_ACHOU = true;
            end if;

          end if;

          perform fc_debug('<fc_vistorias> iCodigoAtividade: ' || iCodigoAtividade, lRaise);

          if V_ACHOU is false then
            return '04- NENHUMA ATIVIDADE COM TIPO 6 CADASTRADA';
          end if;
        end if;

        if V_INSCR = true then

          select MIN(Q85_FORCAL)
          into iFormaCalculo
          from TIPCALC
          INNER JOIN CADCALC                ON TIPCALC.Q81_CADCALC = CADCALC.Q85_CODIGO
          INNER JOIN ATIVTIPO               ON ATIVTIPO.Q80_TIPCAL = TIPCALC.Q81_CODIGO
          INNER JOIN TABATIV          ON TABATIV.Q07_ATIV = ATIVTIPO.Q80_ATIV
          where Q07_INSCR = V_Y71_INSCR AND
          TABATIV.Q07_DATAFI is null AND
          TIPCALC.Q81_TIPO IN ( select codigo from w_tipos_localizacao );

          if iFormaCalculo is null then

            select MIN(Q85_FORCAL)
            into iFormaCalculo
            from ISSPORTETIPO
            INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR
                                   and q45_codporte = q41_codporte
            INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
            INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
            INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
            INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
            INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr and ATIVPRINC.q88_seq = TABATIV.q07_seq
            where Q45_CODPORTE = Q41_CODPORTE AND Q81_TIPO IN ( select codigo from w_tipos_localizacao ) and
            case when q07_datafi is null then true else q07_datafi >= V_DATA end AND
            q07_databx is null;

            if iFormaCalculo is null then
              return '17-SEM FORMA DE CALCULO ENCONTRADA (INSCR)!';
            end if;

          end if;

          perform fc_debug('Forma de Calculo encontrada: ' || iFormaCalculo, lRaise);
          perform fc_debug('v_data: ' ||v_data ||' V_Y71_INSCR: ' || V_Y71_INSCR, lRaise);

          /**
           * Pontuacao das classes
           */
            select Q82_ATIV, MAX(Q25_PONTUACAO)
              into iCodigoAtividade, nPontuacaoClasse
              from TABATIV
                   INNER JOIN CLASATIV   ON Q82_ATIV   = Q07_ATIV
                   INNER JOIN CLASSEPONT ON Q25_CLASSE = Q82_CLASSE
             where Q07_INSCR = V_Y71_INSCR
               AND case when q07_datafi is null
                        then true
                        else q07_datafi >= V_DATA end
               AND q07_databx is null
          GROUP BY Q82_ATIV
          ORDER BY MAX(Q25_PONTUACAO) DESC
             limit 1;

          if nPontuacaoClasse is not null then

            /**
             * Pontuacao zona fiscal
             */
            select Q26_PONTUACAO
              into iPontuacaoPorZonaFiscal
              from ZONAPONT
                   INNER JOIN ISSZONA ON Q26_ZONA = Q35_ZONA
             where Q35_INSCR = V_Y71_INSCR;

            if iPontuacaoPorZonaFiscal is null then
              return '12-PONTUACAO DA ZONA NAO ENCONTRADA';
            end if;

            /**
             * Pontuacao empregados/area
             */
            select Q30_QUANT, Q30_AREA
              into iPontuacaoPorEmpregadosArea, V_AREA
              from ISSQUANT
             where ISSQUANT.Q30_INSCR  = V_Y71_INSCR
               AND ISSQUANT.Q30_ANOUSU = iAnousu;

            if iPontuacaoPorEmpregadosArea is null then
              select Q30_QUANT, Q30_AREA
              from ISSQUANT
              into iPontuacaoPorEmpregadosArea, V_AREA
              where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu - 1);
              if iPontuacaoPorEmpregadosArea is null then
                select Q30_QUANT, Q30_AREA
                from ISSQUANT
                into iPontuacaoPorEmpregadosArea, V_AREA
                where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu + 1);
                if iPontuacaoPorEmpregadosArea is null then
                  INSERT into ISSQUANT select * from ISSQUANT where ISSQUANT.Q30_INSCR = V_Y71_INSCR AND ISSQUANT.Q30_ANOUSU = (iAnousu - 1);
                end if;
              end if;
            end if;

            /**
             * Pontuacao pelos empregados
             */
            select Q27_PONTUACAO
              into iPontuacaoPorEmpregados
              from EMPREGPONT
             where iPontuacaoPorEmpregadosArea >= Q27_QUANTINI AND
                   iPontuacaoPorEmpregadosArea <= Q27_QUANTFIM;

            if iPontuacaoPorEmpregados is null then
              return '13-PONTUACAO DO NUMERO DE EMPREGADOS NAO ENCONTRADA';
            end if;

            if lRaise is true then
              raise notice 'V_AREA: %', V_AREA;
            end if;

            /**
             * Pontuacao pela area
             */
            select Q28_PONTUACAO
              into iPontuacaoPorArea
              from AREAPONT
             where V_AREA >= Q28_QUANTINI
               AND V_AREA <= Q28_QUANTFIM;

            if iPontuacaoPorArea is null then
              return '14-PONTUACAO DA AREA NAO ENCONTRADA';
            end if;

            if lRaise is true then
              raise notice 'nPontuacaoClasse: % - iPontuacaoPorZonaFiscal: %  - iPontuacaoPorEmpregados: %  - iPontuacaoPorArea: %', nPontuacaoClasse, iPontuacaoPorZonaFiscal, iPontuacaoPorEmpregados, iPontuacaoPorArea;
            end if;

            iPontuacaoGeral = nPontuacaoClasse + iPontuacaoPorZonaFiscal + iPontuacaoPorEmpregados + iPontuacaoPorArea;

            perform fc_debug('Pontuacaogeral: ' || iPontuacaoGeral ,lRaise);

            select Q81_CODIGO, Q81_RECEXE, Q92_HIST, Q81_VALEXE, Q92_TIPO
              into V_ATIVTIPO, iCodigoReceitaExercicio, iCodigoHistoricoCalculo, nValorExercicio, iCodigoArretipo
              from TIPCALC
                   inner join tipcalcexe on tipcalcexe.q83_anousu = iAnousu
                                        and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                   INNER JOIN CADVENCDESC ON Q92_CODIGO = tipcalcexe.Q83_CODVEN
             where iPontuacaoGeral >= Q81_QIEXE
               AND iPontuacaoGeral <= Q81_QFEXE
               AND Q81_TIPO IN ( select codigo from w_tipos_localizacao );

         /**
          * Por ativtipo
          */
          else

            if iFormaCalculo = 1 then

              select Q80_ATIV
                into iCodigoAtividade
                from TABATIV
                     INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr
                                         and ATIVPRINC.q88_seq = TABATIV.q07_seq
                     INNER JOIN ATIVTIPO  ON TABATIV.Q07_ativ = ATIVTIPO.q80_ativ
                     INNER JOIN TIPCALC   ON TIPCALC.Q81_CODIGO = ATIVTIPO.Q80_TIPCAL
              where Q07_INSCR = V_Y71_INSCR
                AND TABATIV.Q07_DATAFI is null
                AND Q81_TIPO IN ( select codigo from w_tipos_localizacao );

              if iCodigoAtividade is not null then
                V_ACHOU = true;
              else

                select Q07_ATIV
                  into iCodigoAtividade
                  from ISSPORTETIPO
                       INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR and q45_codporte = q41_codporte
                       INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
                       INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
                       INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
                       INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
                       INNER JOIN ATIVPRINC ON ATIVPRINC.q88_inscr = TABATIV.q07_inscr
                                           and ATIVPRINC.q88_seq = TABATIV.q07_seq
                where Q45_CODPORTE = Q41_CODPORTE
                  AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
                  and case when q07_datafi is null then true else q07_datafi >= V_DATA end
                  AND q07_databx is null;

                if iCodigoAtividade is not null then
                  V_ACHOU = true;
                end if;

              end if;

            elsif iFormaCalculo = 2 then

                select Q80_ATIV
                  into iCodigoAtividade
                  from TABATIV
                       INNER JOIN ATIVTIPO ON TABATIV.Q07_ativ = ATIVTIPO.q80_ativ
                       INNER JOIN TIPCALC ON TIPCALC.Q81_CODIGO = ATIVTIPO.Q80_TIPCAL
                 where Q07_INSCR = V_Y71_INSCR
                   AND TABATIV.Q07_DATAFI is null
                   AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
              ORDER BY Q81_VALEXE DESC
                 LIMIT 1;

              if iCodigoAtividade is not null then
                V_ACHOU = true;
              end if;

            end if;

            if V_ACHOU is false then
              return '16 - SEM ATIVIDADE PRINCIPAL';
            end if;

            select TIPCALC.Q81_CODIGO
              into V_ATIVTIPO
              from ATIVTIPO
                   INNER JOIN TABATIV ON Q07_ATIV = q80_ativ
                   INNER JOIN TIPCALC ON Q80_TIPCAL = Q81_CODIGO
                   INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
             where Q81_TIPO IN ( select codigo from w_tipos_localizacao )
               AND Q07_INSCR = V_Y71_INSCR
               AND case when q07_datafi is null then true else q07_datafi >= V_DATA end
               AND q07_databx is null
               AND Q07_ATIV = iCodigoAtividade;

            if V_ATIVTIPO is null then

              select TIPCALC.Q81_CODIGO
                into V_ATIVTIPO
                from ISSPORTETIPO
                     INNER JOIN ISSBASEPORTE ON Q45_INSCR = V_Y71_INSCR and q45_codporte = q41_codporte
                     INNER JOIN TIPCALC ON Q41_CODTIPCALC = Q81_CODIGO
                     INNER JOIN CADCALC ON CADCALC.Q85_CODIGO = TIPCALC.Q81_CADCALC
                     INNER JOIN CLASATIV ON Q82_CLASSE = Q41_CODCLASSE
                     INNER JOIN TABATIV ON Q82_ATIV = Q07_ATIV AND Q07_INSCR = V_Y71_INSCR
               where Q45_CODPORTE = Q41_CODPORTE
                 AND Q81_TIPO IN ( select codigo from w_tipos_localizacao )
                 and case when q07_datafi is null then true else q07_datafi >= V_DATA end
                 AND q07_databx is null
                 AND Q82_ATIV = iCodigoAtividade;

              if V_ATIVTIPO is null then
                return '06-SEM TIPO DE CALCULO CONFIGURADO!';
              end if;
            end if;

          end if;

        end if;

        if V_Y69_NUMPRE = 0 OR V_Y69_NUMPRE is null then

          select NEXTVAL('numpref_k03_numpre_seq')
            into iNumpreGerado;

          INSERT into VISTORIANUMPRE VALUES(iCodigoVistoria, iNumpreGerado);
        else

          iNumpreGerado = V_Y69_NUMPRE;
          select k00_numpre
            into iNumpreArrecant
            from arrecant
           where k00_numpre = iNumpreGerado;

          if iNumpreArrecant != 0 OR iNumpreArrecant is not null then
            return '07- VISTORIA JA PAGA OU CANCELADA ';
          end if;

          select k00_numpre
            into iNumpreArrecad
            from arrecad
           where k00_numpre = iNumpreGerado;

          if iNumpreArrecad != 0 OR iNumpreArrecad is not null then
            delete from arrecad where k00_numpre = iNumpreGerado;
          end if;

        end if;
      end if;

      perform fc_debug('Verifica se sanitario lIsSanitario: ' || lIsSanitario,  lRaise);
      perform fc_debug('                           V_INSCR: ' || V_INSCR, lRaise);

      /**
       * SE FOR POR SANITARIO SEGUE AQUI
       */
      if lIsSanitario = true then

        FOR rSaniAtividade IN
          select Y83_ATIV
            from SANIATIVIDADE
           where Y83_CODSANI = V_Y74_CODSANI
             AND Y83_ATIV    = iCodigoAtividade

          LOOP

          if lRaise is true then
            raise notice 'Y83_ATIV (2): % - anousu: %', rSaniAtividade.Y83_ATIV, iAnousu;
          end if;

          select Y18_INSCR
            into V_Y74_INSCRSANI
            from sanitarioinscr
           where y18_codsani = V_Y74_CODSANI;

          if lCalculaVistoriaMei is false then

            lContribuinteMei := fc_verifica_contribuinte_mei(V_Y74_INSCRSANI, dDataAtual);

            if lContribuinteMei then

              delete from vistorianumpre where y69_codvist = iCodigoVistoria and y69_numpre = iNumpreGerado;
              return '25 - CONTRIBUINTE OPTANTE PELO SIMPLES NACIONAL NA CATEGORIA MEI';
            end if;
          end if;

          select q81_recexe, q92_hist, q81_valexe, q92_tipo,
                 (select distinct q83_codven
                    from tipcalcexe
                   where q83_tipcalc = q81_codigo
                     and q83_anousu  = iAnousu)
            into iCodigoReceitaExercicio,
                 iCodigoHistoricoCalculo,
                 nValorExercicio,
                 iCodigoArretipo,
                 iCodVencimento
            from ATIVTIPO
                 inner join tipcalc     on q80_tipcal             = q81_codigo
                 inner join tipcalcexe  on tipcalcexe.q83_anousu  = iAnousu
                                       and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                 inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven
           where q80_ativ = rsaniatividade.y83_ativ
             and ( select y80_area from sanitario where y80_codsani = V_Y74_CODSANI) >= q81_qiexe
             and ( select y80_area from sanitario where y80_codsani = V_Y74_CODSANI) <= q81_qfexe
             and q81_tipo in ( select codigo from w_tipos_sanitario );

          if iCodigoReceitaExercicio is not null then

            nValorVistoria = round(nValorExercicio * nValorInflator * nValorBase,2);

            if lRaise is true then
              raise notice 'inserindo no arrecad... nValorVistoria (2): % - iCodVencimento: %', nValorVistoria, iCodVencimento;
            end if;

            lCalculou = true;
            --
            -- Inserindo por sanitario
            --

            --
            -- Funcao para gerar o financeiro
            --
            if iCodVencimento is null then
              return '18-SEM VENCIMENTO CONFIGURADO PARA O EXERCICIO!';
            end if;

            if lRaise is true then
              raise notice 'executando fc_gerafinanceiro(%,%,%,%,%,%,%)', iNumpreGerado,nValorVistoria,iCodVencimento,V_Y80_NUMCGM,V_DATA,iCodigoReceitaExercicio,dDataVistoria;
            end if;

            select *
              into rFinanceiro
              from fc_gerafinanceiro(iNumpreGerado,nValorVistoria,iCodVencimento,V_Y80_NUMCGM,V_DATA,iCodigoReceitaExercicio,dDataVistoria);

            if V_Y74_INSCRSANI is not null then

              select k00_numpre
                into iNumpreArreinscr
                from arreinscr
               where k00_numpre = iNumpreGerado;

              if iNumpreArreinscr != 0 OR iNumpreArreinscr is not null then
                DELETE from ARREINSCR where K00_NUMPRE = iNumpreArreinscr;
              end if;

              INSERT into ARREINSCR (k00_numpre, k00_inscr)
                             VALUES (iNumpreGerado, V_Y74_INSCRSANI);

            end if;
          end if;
        end loop;

        if lCalculou IS true then
          return '08 - OK ';
        else
          return '15 - ERRO DURANTE O CALCULO';
        end if;
      /**
       * FIM DO if DO SANITARIO
       */

      /**
       * SE FOR POR INSCRICAO SEGUE AQUI
       */
      elsif V_INSCR = true then

        perform fc_debug('<fc_vistorias> Inscricao - nPontuacaoClasse: ' || nPontuacaoClasse || ' iCodigoAtividade: ' || iCodigoAtividade, lRaise);

        if lCalculaVistoriaMei is false then

          lContribuinteMei := fc_verifica_contribuinte_mei(V_Y71_INSCR, dDataAtual);

          if lContribuinteMei then

            delete from vistorianumpre where y69_codvist = iCodigoVistoria and y69_numpre = iNumpreGerado;
            return '25 - CONTRIBUINTE OPTANTE PELO SIMPLES NACIONAL NA CATEGORIA MEI';
          end if;
        end if;

        if nPontuacaoClasse is null then

          sSql = sSql || ' select q81_qiexe, q81_qfexe, q81_codigo, q81_uqcad, q81_uqtab                ';
          sSql = sSql || '   from ativtipo                                                              ';
          sSql = sSql || '          inner join tipcalc on q81_codigo = q80_tipcal                       ';
          sSql = sSql || '  where q80_ativ = ' || iCodigoAtividade;
          sSql = sSql || '    and q81_tipo in ( select codigo from w_tipos_localizacao )                ';
          sSql = sSql || ' UNION                                                                        ';
          sSql = sSql || ' select q81_qiexe, q81_qfexe, q81_codigo, q81_uqcad, q81_uqtab                ';
          sSql = sSql || '   from issportetipo                                                          ';
          sSql = sSql || '        inner join issbaseporte on q45_inscr          = ' || V_Y71_INSCR;
          sSql = sSql || '        inner join tipcalc      on q41_codtipcalc     = q81_codigo            ';
          sSql = sSql || '        inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc   ';
          sSql = sSql || '        inner join clasativ     on q82_classe         = q41_codclasse         ';
          sSql = sSql || '  where q45_codporte = q41_codporte                                           ';
          sSql = sSql || '    and q81_tipo in (select codigo from w_tipos_localizacao )                 ';
          sSql = sSql || '    and q82_ativ = ' || iCodigoAtividade;

          select q60_campoutilcalc
            into v_tipo_quant
            from parissqn;

          if v_tipo_quant = 2 then

            select q30_quant
              into V_AREA
              from issquant
             where q30_inscr  = V_Y71_INSCR
               and q30_anousu = iAnousu;
          else

            select q30_area
              into V_AREA
              from issquant
             where q30_inscr  = V_Y71_INSCR
               and q30_anousu = iAnousu;
          end if;

          perform fc_debug('<fc_vistorias> V_AREA: ' || V_AREA, lRaise);
          perform fc_debug('<fc_vistorias> inscr : ' || V_Y71_INSCR, lRaise);
          perform fc_debug('<fc_vistorias> anousu: ' || iAnousu, lRaise);

          if V_AREA is null then
            V_AREA = 0;
          end if;

        else

          sSql = sSql || ' select q81_codigo,                                                              ';
          sSql = sSql || '        q81_recexe,                                                              ';
          sSql = sSql || '        q92_hist,                                                                ';
          sSql = sSql || '        q81_valexe,                                                              ';
          sSql = sSql || '        q92_tipo,                                                                ';
          sSql = sSql || '        q81_qiexe,                                                               ';
          sSql = sSql || '        q81_qfexe,                                                               ';
          sSql = sSql || '        q81_uqcad,                                                               ';
          sSql = sSql || '        q81_uqtab                                                                ';
          sSql = sSql || '   from ativtipo                                                                 ';
          sSql = sSql || '        inner join tipcalc     on tipcalc.q81_codigo     = ativtipo.q80_tipcal   ';
          sSql = sSql || '        inner join tipcalcexe  on tipcalcexe.q83_anousu  = ' || iAnousu;
          sSql = sSql || '                              and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo    ';
          sSql = sSql || '        inner join cadcalc     on q81_cadcalc            = q85_codigo            ';
          ssql = ssql || '        inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven ';
          sSql = sSql || '      where ' || iPontuacaoGeral || ' >= q81_qiexe and                          ';
          sSql = sSql ||                iPontuacaoGeral || '    <= q81_qfexe and                          ';
          sSql = sSql || '        Q81_TIPO IN ( select codigo from w_tipos_localizacao ) AND ATIVTIPO.Q80_ATIV = ' || iCodigoAtividade;

          V_AREA = iPontuacaoGeral;

          perform fc_debug('<fc_vistorias> V_AREA: ' || V_AREA, lRaise);
        end if;

        select count(*)
          into iQuantidadeAtividades
          from ( select distinct
                        q07_seq
                   from tabativ
                        inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                        inner join tipcalc on q81_codigo = q80_tipcal
                  where q81_tipo in (3,5,6)
                    and q07_inscr = V_Y71_INSCR
                    and (q07_datafi is null or q07_datafi >= current_date)
                    and (q07_databx is null or q07_databx >= current_date) ) as x;

        perform fc_debug('<fc_vistorias> iQuantidadeAtividades: ' || iQuantidadeAtividades, lRaise);

        FOR rAtivtipo IN EXECUTE sSql LOOP

          if lRaise is true then
            raise notice 'dentro do for... vcalculou : % - tipcalc: % - area: % - qiexe: % - qfexe: %',lCalculou, rAtivtipo.Q81_CODIGO, V_AREA, rAtivtipo.Q81_QIEXE, rAtivtipo.Q81_QFEXE;
          end if;

          if lRaise is true then
            raise notice 'antes do if... area - % q81_qiexe - % q81_qfexe - %',V_AREA,rAtivtipo.Q81_QIEXE,rAtivtipo.Q81_QFEXE;
          end if;

          if V_AREA >= rAtivtipo.Q81_QIEXE and V_AREA <= rAtivtipo.Q81_QFEXE then

            select q81_recexe, q92_hist, q81_valexe, q92_tipo, q81_excedenteativ,
                   (select distinct q83_codven from tipcalcexe where q83_tipcalc = q81_codigo and q83_anousu = iAnousu)
              into iCodigoReceitaExercicio,
                   iCodigoHistoricoCalculo,
                   nValorExercicio,
                   iCodigoArretipo,
                   nValorExcedente, -- Valor excedente configurado na atividade
                   iCodVencimento
              from tipcalc
                   inner join tipcalcexe  on tipcalcexe.q83_anousu  = ianousu
                                         and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
                   inner join cadcalc     on q81_cadcalc            = q85_codigo
                   inner join cadvencdesc on q92_codigo             = tipcalcexe.q83_codven
            where Q81_CODIGO = rAtivtipo.Q81_CODIGO;


            if iCodVencimento is null then
              return '18-SEM VENCIMENTO CONFIGURADO PARA O EXERCICIO!';
            end if;

            perform fc_debug('Calculando valor do debito:',        lRaise);
            perform fc_debug('nValorExercicio  : ' || nValorExercicio,   lRaise);
            perform fc_debug('nValorInflator: ' || nValorInflator, lRaise);
            perform fc_debug('nValorBase    : ' || nValorBase,     lRaise);

            /**
             * Verifica se o parametro Utiliza quantidade do cadastro configurado no tipo de calculo esta setado
             */
            if rAtivtipo.q81_uqcad is true then

              select q30_mult
                into nMultiplicadorIssQuantidade
                from issquant
               where q30_inscr  = v_y71_inscr
                 and q30_anousu = iAnousu;
              if not found then
                  return '22 - inscricao sem multiplicador cadastrado na issquant';
              end if;

              if nMultiplicadorIssQuantidade is null or nMultiplicadorIssQuantidade = 0 then
                  nMultiplicadorIssQuantidade := 1;
              end if;
            end if;

            /**
             * Verifica se o parametro Utiliza quantidade da tabela de atividades configurado no
             * tipo de calculo esta setado
             */
            if rAtivtipo.q81_uqtab is true then

               select q07_quant
                 into nQuantidadeAtividade
                 from tabativ
                where q07_inscr = v_y71_inscr
                  and q07_ativ = iCodigoAtividade
                  and q07_databx is null;
               if not found then
                  return '23 - inscricao sem atividade cadastrada na tabativ ou atividade baixada';
               end if;

               if nQuantidadeAtividade is null or nQuantidadeAtividade = 0 then
                  nQuantidadeAtividade := 1;
               end if;
            end if;

            nValorVistoria = round(nValorExercicio * nValorInflator * nValorBase * nMultiplicadorIssQuantidade * nQuantidadeAtividade, 2);
            perform fc_debug('Resultado -> nValorVistoria: ' || nValorVistoria,  lRaise);

            lCalculou = true;

            if lRaise is true then
              raise notice 'nValorExcedente-: % nValorVistoria (1): % - k00_numpre: % - nValorInflator: % - nValorBase: %', nValorExcedente, nValorVistoria, iNumpreGerado, nValorInflator, nValorBase;
            end if;

            if nValorExcedente > 0 then

              raise notice 'valor antes: %', nValorVistoria;
              nValorVistoria = nValorVistoria + (nValorVistoria * 0.3 * (iQuantidadeAtividades - 1));
              raise notice 'valor depois: %', nValorVistoria;
            end if;

            --
            -- Funcao para gerar o financeiro
            --
            select *
              into rFinanceiro
              from fc_gerafinanceiro(iNumpreGerado, nValorVistoria, iCodVencimento, V_q02_NUMCGM, V_DATA, iCodigoReceitaExercicio, dDataVistoria);

            select k00_numpre
              into iNumpreArreinscr
              from arreinscr
             where k00_numpre = iNumpreGerado;

            if iNumpreArreinscr != 0 OR iNumpreArreinscr is not null then
              DELETE from ARREINSCR where K00_NUMPRE = iNumpreArreinscr;
            end if;
            insert into arreinscr (k00_numpre, k00_inscr)
                           values (iNumpreGerado, V_Y71_INSCR);
          end if;

        end loop;

        if lRaise is true then
          raise notice 'fora do for... lCalculou: %', lCalculou;
        end if;

        if lCalculou IS true then
          return '09-OK INSCRICAO NUMERO ' || V_Y71_INSCR;
        else
          return '19-OCORREU ALGUM ERRO DURANTE O CALCULO (2)!!!';
        end if;

      end if;

      if V_INSCR = false AND lIsSanitario = false then
          return '10- CALCULO NAO CONFIGURADO PARA A VISTORIA NUMERO ' || iCodigoVistoria;
      end if;

    else
          return '20-PROCEDIMENTO NÃO PREPARADO PARA CALCULO POR FORMA DIFERENTE DE 1 (NORMAL)';
    end if;

  end;

$$ language 'plpgsql';

SQL;

        $this->execute($sSql);
    }
}
