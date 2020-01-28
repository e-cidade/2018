<?php

use Classes\PostgresMigration;

class M9198IptuBomPagadorInsencao extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL

create or replace function fc_calculoiptu_sapiranga_2017(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$
declare

   iMatricula        alias   for $1;
   iAnousu           alias   for $2;
   lGerafinanc       alias   for $3;
   lAtualizaParcela  alias   for $4;
   lNovonumpre       alias   for $5;
   lCalculogeral     alias   for $6;
   lDemonstrativo    alias   for $7;
   iParcelaini       alias   for $8;
   iParcelafim       alias   for $9;

   iIdbql               integer default 0;
   iNumcgm              integer default 0;
   iCodcli              integer default 0;
   iCodisen             integer default 0;
   iTipois              integer default 0;
   iParcelas            integer default 0;
   iNumconstr           integer default 0;
   iReceitaBomPagador   integer default 0;
   iCodErro             integer default 0;

   dDatabaixa           date;

   nAreal                              numeric default 0;
   nAreac                              numeric default 0;
   nTotarea                            numeric default 0;
   nFracao                             numeric default 0;
   nFracaolote                         numeric default 0;
   nAliquota                           numeric default 0;
   nIsenaliq                           numeric default 0;
   nArealo                             numeric default 0;
   nOneracao                           numeric default 0;
   nVvc                                numeric(15,2) default 0;
   nVvt                                numeric(15,2) default 0;
   nVv                                 numeric(15,2) default 0;
   nViptu                              numeric(15,2) default 0;
   nDescontoBomPagador                 numeric(15,2) default 0;
   nValorDescontoIptuCalv              numeric(15,2) default 0;
   nTotalCreditoReceita                numeric(15,2) default 0;
   nValorCalculoAnteriorReceita        numeric(15,2) default 0;
   nValorCalculoAnteriorReceitaIsencao numeric(15,2) default 0;
   nValorCalculoAtualReceita           numeric(15,2) default 0;
   nValorCalculoAtualReceitaIsencao    numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';
   tErro            text default '';

   lFinanceiro      boolean;
   lDadosIptu       boolean;
   lErro            boolean;
   lIsentaxas       boolean;
   lTempagamento    boolean;
   lEmpagamento     boolean;
   lTaxasCalculadas boolean;
   lRaise           boolean default false; -- true para abilitar raise na funcao principal
   lSubRaise        boolean default false; -- true para abilitar raise nas sub-funcoes
   lCalculoQuitado  boolean default false;

   rCfiptu          record;
   rIptucalv        record;
   iNumpreDiversos  integer default 0;

   iReceitaDestinoDiversoBomPagador integer default null;
   iTotalProcedencias               integer default 0;
   iCodigoHistoricoIPTUCalV         integer default null;
   iNumpreCredito                   integer default null;

begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_sapiranga_2017 Anousu: '||iAnousu||' - IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||lDemonstrativo||'- j18_vlrref: '||rCfiptu.j18_vlrref::numeric, lRaise);

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvt_sap_2017( iIdbql, iAnousu, nFracaolote, nArealo, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvt_sapiranga_2017 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
  end if;

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_sapiranga_2017 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvc_sapiranga_2017( iMatricula, iAnousu, lDemonstrativo,lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvc_sapiranga_2017 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRUÇÕES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  /* BUSCA A ALIQUOTA  */
  -- so executar se nao for isento
  if iNumconstr is not null and iNumconstr > 0 then
    select fc_iptu_getaliquota_sap_2008(iMatricula,iIdbql,iNumcgm,true,lSubRaise) into nAliquota;
  else
    select fc_iptu_getaliquota_sap_2008(iMatricula,iIdbql,iNumcgm,false,lSubRaise) into nAliquota;
  end if;

  if not found or nAliquota = 0 then
    select fc_iptu_geterro(13,'') into tRetorno;
    return tRetorno;
  end if;

  /*--------- CALCULA O VALOR VENAL -----------*/
  perform fc_debug('nVvc - '||nVvc||' nVvt - '||nVvt, lRaise);

  nVv    := nVvc + nVvt;

  perform fc_debug('valor sem aliquota - '||nVv, lRaise);

  nViptu := nVv * ( nAliquota / 100 );

  perform fc_debug('valor com aliquota - '||nViptu, lRaise);

  /*-------------------------------------------*/

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;

  if not found or iParcelas = 0 then
    select fc_iptu_geterro(14,'') into tRetorno;
    return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

  /* CALCULA AS TAXAS */
  perform fc_debug('PARAMETROS fc_iptu_calculataxas  ANOUSU '||iAnousu||' -- CODCLI '||iCodcli, lRaise);

  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSubRaise)
    into lTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - '||lTaxasCalculadas, lRaise);

  /* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise )
    into tDemo;

  /* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo

    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise)
      into lDadosIptu;

      if lGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise)
          into lFinanceiro;
      end if;
  else
    return tDemo;
  end if;

    perform *
       from tmpipturecalculo
      where matricula = iMatricula
        and anousu    = iAnousu;

    if not found then -- não é recalculo

      if exists( select *
                   from iptucalcconfrec
                  where j23_matric = iMatricula
                    and j23_anousu = iAnousu ) then

        for rIptucalv in

           select arrecad.k00_numpre,
                  arrecad.k00_receit,
                  (select j21_codhis
                     from iptucalv
                    where j21_matric = iMatricula
                      and j21_anousu = iAnousu
                      and j21_receit = arrecad.k00_receit
                      and j21_codhis <> rCfiptu.j18_iptuhistisen
                      and j21_valor > 0) as hist,
              ( sum( coalesce( substr( fc_calcula( arrecad.k00_numpre,
                                       arrecad.k00_numpar,
                                       arrecad.k00_receit,
                                       current_date,
                                       arrecad.k00_dtvenc,
                                       iAnousu ) ,54,13 )::numeric ,0 ) ) +
                    coalesce(
                    ( ( select sum(coalesce(k00_valor,0))
                        from arrecant
                       where arrecant.k00_numpre = arrecad.k00_numpre
                         and arrecant.k00_receit = arrecad.k00_receit ) -
                      ( select sum(coalesce(k00_valor,0))
                          from arrepaga
                         where arrepaga.k00_numpre = arrecad.k00_numpre
                           and arrepaga.k00_receit = arrecad.k00_receit ) ) ,0)
              ) as valor_desconto
             from iptucalcconfrec
                  inner join iptunump on iptunump.j20_matric = iptucalcconfrec.j23_matric
                                     and iptunump.j20_anousu = iptucalcconfrec.j23_anousu
                  inner join arrecad  on arrecad.k00_numpre  = iptunump.j20_numpre
                                     and arrecad.k00_receit  = iptucalcconfrec.j23_recdst
            where j23_matric = iMatricula
              and j23_anousu = iAnousu
            group by arrecad.k00_numpre,
                 arrecad.k00_receit

        loop

          if rIptucalv.hist = 7 then
        insert into iptucalv (j21_anousu,j21_matric,j21_codhis,j21_receit,j21_valor,j21_quant)
                      values (iAnousu, iMatricula, 12, rIptucalv.k00_receit, ( abs(rIptucalv.valor_desconto) * -1 ), 0);
          elsif rIptucalv.hist = 1 then
        insert into iptucalv (j21_anousu,j21_matric,j21_codhis,j21_receit,j21_valor,j21_quant)
                      values (iAnousu, iMatricula, 11, rIptucalv.k00_receit, ( abs(rIptucalv.valor_desconto) * -1 ), 0);
          end if;

        end loop;

      end if;

    else -- é recalculo

      -- possui desconto de bom pagador
      if exists(select *
                  from iptucalcconfrec
                 where j23_matric = iMatricula
                   and j23_anousu = iAnousu)
      then

        -- percorre receitas a lançar desconto
        for rIptucalv in

          select iptucalv.j21_receit,
                 iptucalv.j21_valor,
                 iptucalv.j21_codhis,
                 tabrecjm.k02_desco4 as valor_desconto
            from iptucalv
                 inner join iptucalcconfrec on iptucalcconfrec.j23_recdst = iptucalv.j21_receit
                 inner join tabrec on tabrec.k02_codigo = iptucalcconfrec.j23_recdst
                 inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
           where iptucalv.j21_matric = iMatricula
             and iptucalv.j21_anousu = iAnousu
             and iptucalv.j21_codhis <> rCfiptu.j18_iptuhistisen
             and iptucalcconfrec.j23_matric = iMatricula
             and iptucalcconfrec.j23_anousu = iAnousu

        loop

          iCodigoHistoricoIPTUCalV := null;

          if rIptucalv.j21_codhis = 7 then
            iCodigoHistoricoIPTUCalV := 12;
          elsif rIptucalv.j21_codhis = 1 then
            iCodigoHistoricoIPTUCalV := 11;
          end if;

          if iCodigoHistoricoIPTUCalV is not null then

            -- insere valor negativo na receita que vai receber desconto de bom pagador
            insert into iptucalv
                 select iAnousu,
                        iMatricula,
                        rIptucalv.j21_receit,
                        round((abs(sum(j21_valor) * (rIptucalv.valor_desconto / 100)) * -1), 2) as j21_valor,
                        0,
                        iCodigoHistoricoIPTUCalV
                   from iptucalv
                  where j21_matric = iMatricula
                    and j21_anousu = iAnousu
                    and j21_receit = rIptucalv.j21_receit;

            -- soma valores de calculo e recalculo da receita
            select coalesce(valor, 0),
                   coalesce(j21_valor, 0)
              into nValorCalculoAnteriorReceita,
                   nValorCalculoAtualReceita
              from tmpipturecalculo
                   inner join iptucalv on iptucalv.j21_matric = tmpipturecalculo.matricula
                                      and iptucalv.j21_anousu = tmpipturecalculo.anousu
                                      and iptucalv.j21_receit = tmpipturecalculo.receita
             where j21_valor > 0
               and matricula = iMatricula
               and anousu = iAnousu
               and receita = rIptucalv.j21_receit;

            -- soma os valores de isenção da receita
            select coalesce(sum(valor_isencao), 0)
              into nValorCalculoAnteriorReceitaIsencao
              from tmpipturecalculo
             where matricula = iMatricula
               and anousu = iAnousu
               and receita = rIptucalv.j21_receit;

            -- desconta a isenção do valor da receita anterior
            nValorCalculoAnteriorReceita := nValorCalculoAnteriorReceita - nValorCalculoAnteriorReceitaIsencao;

            -- soma os valores de isenção da receita
            select coalesce(abs(sum(j21_valor)), 0)
              into nValorCalculoAtualReceitaIsencao
              from iptucalv
             where j21_matric = iMatricula
               and j21_anousu = iAnousu
               and j21_receit = rIptucalv.j21_receit
               and j21_codhis = rCfiptu.j18_iptuhistisen;

            -- desconta a isenção do valor da receita atual
            nValorCalculoAtualReceita := nValorCalculoAtualReceita - nValorCalculoAtualReceitaIsencao;

            -- verificar se cálculo anterior houve desconto de bom pagador
            perform 1
               from tmpipturecalculo
              where valor < 0
                and matricula = iMatricula
                and anousu = iAnousu
                and receita = rIptucalv.j21_receit;

            if not found then -- não teve desconto de bom pagador no cálculo anterior

              if nValorCalculoAtualReceita > nValorCalculoAnteriorReceita then

                -- pega valor do cálculo anterior que deveria ter disconto de bom pagador
                select valor - valor_isencao
                  into nValorDescontoIptuCalv
                  from tmpipturecalculo
                 where matricula = iMatricula
                   and anousu = iAnousu
                   and receita = rIptucalv.j21_receit;

                -- calcula valor que devia ter sido dado desconto na receita
                nValorDescontoIptuCalv := nValorDescontoIptuCalv * (rIptucalv.valor_desconto / 100);

                -- deduz valor do diverso lançado no recalculo
                update diversos
                   set dv05_vlrhis = dv05_vlrhis - nValorDescontoIptuCalv,
                       dv05_valor = dv05_valor - nValorDescontoIptuCalv
                 where dv05_coddiver = (select dv05_coddiver
                                          from diversos
                                               inner join arrecad on arrecad.k00_numpre = diversos.dv05_numpre
                                               inner join tmpipturecalculonump on tmpipturecalculonump.numpre = diversos.dv05_numpre
                                         where tmpipturecalculonump.matricula = iMatricula
                                           and tmpipturecalculonump.anousu = iAnousu
                                           and arrecad.k00_receit = rIptucalv.j21_receit);

                -- deduz valor do arrecad lançado no recalculo
                update arrecad
                   set k00_valor = k00_valor - nValorDescontoIptuCalv
                 where k00_receit = rIptucalv.j21_receit
                   and k00_numpre in (select tmpipturecalculonump.numpre
                                        from tmpipturecalculonump
                                       where tmpipturecalculonump.matricula = iMatricula
                                         and tmpipturecalculonump.anousu = iAnousu);

              elsif nValorCalculoAtualReceita <= nValorCalculoAnteriorReceita then

                nTotalCreditoReceita := nTotalCreditoReceita + (nValorCalculoAtualReceita * (rIptucalv.valor_desconto / 100));

              end if;

            else -- teve desconto de bom pagador no cálculo anterior

              if nValorCalculoAtualReceita <= nValorCalculoAnteriorReceita then

                nTotalCreditoReceita := nTotalCreditoReceita - ((nValorCalculoAnteriorReceita * (rIptucalv.valor_desconto / 100)) - (nValorCalculoAtualReceita * (rIptucalv.valor_desconto / 100)));

              end if;

            end if;

          end if;

        end loop;

        select tmpipturecalculocreditonump.numpre
          into iNumpreCredito
          from tmpipturecalculocreditonump
         where tmpipturecalculocreditonump.matricula = iMatricula
           and tmpipturecalculocreditonump.anousu = iAnousu;

        if iNumpreCredito is not null then

          update recibo
             set k00_valor = k00_valor + nTotalCreditoReceita
           where recibo.k00_numpre = iNumpreCredito;

          update abatimento
             set k125_valor = k125_valor + nTotalCreditoReceita,
                 k125_valordisponivel = k125_valordisponivel + nTotalCreditoReceita
            from abatimentorecibo
           where abatimento.k125_sequencial = abatimentorecibo.k127_abatimento
             and abatimentorecibo.k127_numprerecibo = iNumpreCredito;

        else

          if nTotalCreditoReceita > 0 then

            select fc_iptu_geracreditorecalculo(iMatricula, iAnousu, nTotalCreditoReceita, lRaise)
              into lCalculoQuitado;

            if lCalculoQuitado is false then

              perform fc_debug('Erro ao gerar crédito no recálculo de IPTU quitado.', lRaise, false, false);
              return false;
            end if;

          end if;

        end if;

      end if;

    end if;

  if lDemonstrativo is false then

    update iptucalc
       set j23_manual = tDemo
     where j23_matric = iMatricula
       and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL

create or replace function fc_calculoiptu_sapiranga_2017(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$
declare

   iMatricula        alias   for $1;
   iAnousu           alias   for $2;
   lGerafinanc       alias   for $3;
   lAtualizaParcela  alias   for $4;
   lNovonumpre       alias   for $5;
   lCalculogeral     alias   for $6;
   lDemonstrativo    alias   for $7;
   iParcelaini       alias   for $8;
   iParcelafim       alias   for $9;

   iIdbql               integer default 0;
   iNumcgm              integer default 0;
   iCodcli              integer default 0;
   iCodisen             integer default 0;
   iTipois              integer default 0;
   iParcelas            integer default 0;
   iNumconstr           integer default 0;
   iReceitaBomPagador   integer default 0;
   iCodErro             integer default 0;

   dDatabaixa           date;

   nAreal                              numeric default 0;
   nAreac                              numeric default 0;
   nTotarea                            numeric default 0;
   nFracao                             numeric default 0;
   nFracaolote                         numeric default 0;
   nAliquota                           numeric default 0;
   nIsenaliq                           numeric default 0;
   nArealo                             numeric default 0;
   nOneracao                           numeric default 0;
   nVvc                                numeric(15,2) default 0;
   nVvt                                numeric(15,2) default 0;
   nVv                                 numeric(15,2) default 0;
   nViptu                              numeric(15,2) default 0;
   nDescontoBomPagador                 numeric(15,2) default 0;
   nValorDescontoIptuCalv              numeric(15,2) default 0;
   nTotalCreditoReceita                numeric(15,2) default 0;
   nValorCalculoAnteriorReceita        numeric(15,2) default 0;
   nValorCalculoAnteriorReceitaIsencao numeric(15,2) default 0;
   nValorCalculoAtualReceita           numeric(15,2) default 0;
   nValorCalculoAtualReceitaIsencao    numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';
   tErro            text default '';

   lFinanceiro      boolean;
   lDadosIptu       boolean;
   lErro            boolean;
   lIsentaxas       boolean;
   lTempagamento    boolean;
   lEmpagamento     boolean;
   lTaxasCalculadas boolean;
   lRaise           boolean default false; -- true para abilitar raise na funcao principal
   lSubRaise        boolean default false; -- true para abilitar raise nas sub-funcoes
   lCalculoQuitado  boolean default false;

   rCfiptu          record;
   rIptucalv        record;
   iNumpreDiversos  integer default 0;

   iReceitaDestinoDiversoBomPagador integer default null;
   iTotalProcedencias               integer default 0;
   iCodigoHistoricoIPTUCalV         integer default null;
   iNumpreCredito                   integer default null;

begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_sapiranga_2017 Anousu: '||iAnousu||' - IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||lDemonstrativo||'- j18_vlrref: '||rCfiptu.j18_vlrref::numeric, lRaise);

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvt_sap_2017( iIdbql, iAnousu, nFracaolote, nArealo, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvt_sapiranga_2017 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
  end if;

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_sapiranga_2017 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvc_sapiranga_2017( iMatricula, iAnousu, lDemonstrativo,lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvc_sapiranga_2017 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRUÇÕES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  /* BUSCA A ALIQUOTA  */
  -- so executar se nao for isento
  if iNumconstr is not null and iNumconstr > 0 then
    select fc_iptu_getaliquota_sap_2008(iMatricula,iIdbql,iNumcgm,true,lSubRaise) into nAliquota;
  else
    select fc_iptu_getaliquota_sap_2008(iMatricula,iIdbql,iNumcgm,false,lSubRaise) into nAliquota;
  end if;

  if not found or nAliquota = 0 then
    select fc_iptu_geterro(13,'') into tRetorno;
    return tRetorno;
  end if;

  /*--------- CALCULA O VALOR VENAL -----------*/
  perform fc_debug('nVvc - '||nVvc||' nVvt - '||nVvt, lRaise);

  nVv    := nVvc + nVvt;

  perform fc_debug('valor sem aliquota - '||nVv, lRaise);

  nViptu := nVv * ( nAliquota / 100 );

  perform fc_debug('valor com aliquota - '||nViptu, lRaise);

  /*-------------------------------------------*/

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;

  if not found or iParcelas = 0 then
    select fc_iptu_geterro(14,'') into tRetorno;
    return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

  /* CALCULA AS TAXAS */
  perform fc_debug('PARAMETROS fc_iptu_calculataxas  ANOUSU '||iAnousu||' -- CODCLI '||iCodcli, lRaise);

  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSubRaise)
    into lTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - '||lTaxasCalculadas, lRaise);

  /* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise )
    into tDemo;

  /* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo

    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise)
      into lDadosIptu;

      if lGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise)
          into lFinanceiro;
      end if;
  else
    return tDemo;
  end if;

    perform *
       from tmpipturecalculo
      where matricula = iMatricula
        and anousu    = iAnousu;

    if not found then -- não é recalculo

      if exists( select *
                   from iptucalcconfrec
                  where j23_matric = iMatricula
                    and j23_anousu = iAnousu ) then

        for rIptucalv in

           select arrecad.k00_numpre,
                  arrecad.k00_receit,
                  (select j21_codhis
                     from iptucalv
                    where j21_matric = iMatricula
                      and j21_anousu = iAnousu
                      and j21_receit = arrecad.k00_receit
                  and j21_codhis <> rCfiptu.j18_iptuhistisen ) as hist,
              ( sum( coalesce( substr( fc_calcula( arrecad.k00_numpre,
                                       arrecad.k00_numpar,
                                       arrecad.k00_receit,
                                       current_date,
                                       arrecad.k00_dtvenc,
                                       iAnousu ) ,54,13 )::numeric ,0 ) ) +
                    coalesce(
                    ( ( select sum(coalesce(k00_valor,0))
                        from arrecant
                       where arrecant.k00_numpre = arrecad.k00_numpre
                         and arrecant.k00_receit = arrecad.k00_receit ) -
                      ( select sum(coalesce(k00_valor,0))
                          from arrepaga
                         where arrepaga.k00_numpre = arrecad.k00_numpre
                           and arrepaga.k00_receit = arrecad.k00_receit ) ) ,0)
              ) as valor_desconto
             from iptucalcconfrec
                  inner join iptunump on iptunump.j20_matric = iptucalcconfrec.j23_matric
                                     and iptunump.j20_anousu = iptucalcconfrec.j23_anousu
                  inner join arrecad  on arrecad.k00_numpre  = iptunump.j20_numpre
                                     and arrecad.k00_receit  = iptucalcconfrec.j23_recdst
            where j23_matric = iMatricula
              and j23_anousu = iAnousu
            group by arrecad.k00_numpre,
                 arrecad.k00_receit

        loop

          if rIptucalv.hist = 7 then
        insert into iptucalv (j21_anousu,j21_matric,j21_codhis,j21_receit,j21_valor,j21_quant)
                      values (iAnousu, iMatricula, 12, rIptucalv.k00_receit, ( abs(rIptucalv.valor_desconto) * -1 ), 0);
          elsif rIptucalv.hist = 1 then
        insert into iptucalv (j21_anousu,j21_matric,j21_codhis,j21_receit,j21_valor,j21_quant)
                      values (iAnousu, iMatricula, 11, rIptucalv.k00_receit, ( abs(rIptucalv.valor_desconto) * -1 ), 0);
          end if;

        end loop;

      end if;

    else -- é recalculo

      -- possui desconto de bom pagador
      if exists(select *
                  from iptucalcconfrec
                 where j23_matric = iMatricula
                   and j23_anousu = iAnousu)
      then

        -- percorre receitas a lançar desconto
        for rIptucalv in

          select iptucalv.j21_receit,
                 iptucalv.j21_valor,
                 iptucalv.j21_codhis,
                 tabrecjm.k02_desco4 as valor_desconto
            from iptucalv
                 inner join iptucalcconfrec on iptucalcconfrec.j23_recdst = iptucalv.j21_receit
                 inner join tabrec on tabrec.k02_codigo = iptucalcconfrec.j23_recdst
                 inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
           where iptucalv.j21_matric = iMatricula
             and iptucalv.j21_anousu = iAnousu
             and iptucalv.j21_codhis <> rCfiptu.j18_iptuhistisen
             and iptucalcconfrec.j23_matric = iMatricula
             and iptucalcconfrec.j23_anousu = iAnousu

        loop

          iCodigoHistoricoIPTUCalV := null;

          if rIptucalv.j21_codhis = 7 then
            iCodigoHistoricoIPTUCalV := 12;
          elsif rIptucalv.j21_codhis = 1 then
            iCodigoHistoricoIPTUCalV := 11;
          end if;

          if iCodigoHistoricoIPTUCalV is not null then

            -- insere valor negativo na receita que vai receber desconto de bom pagador
            insert into iptucalv
                 select iAnousu,
                        iMatricula,
                        rIptucalv.j21_receit,
                        round((abs(sum(j21_valor) * (rIptucalv.valor_desconto / 100)) * -1), 2) as j21_valor,
                        0,
                        iCodigoHistoricoIPTUCalV
                   from iptucalv
                  where j21_matric = iMatricula
                    and j21_anousu = iAnousu
                    and j21_receit = rIptucalv.j21_receit;

            -- soma valores de calculo e recalculo da receita
            select coalesce(valor, 0),
                   coalesce(j21_valor, 0)
              into nValorCalculoAnteriorReceita,
                   nValorCalculoAtualReceita
              from tmpipturecalculo
                   inner join iptucalv on iptucalv.j21_matric = tmpipturecalculo.matricula
                                      and iptucalv.j21_anousu = tmpipturecalculo.anousu
                                      and iptucalv.j21_receit = tmpipturecalculo.receita
             where j21_valor > 0
               and matricula = iMatricula
               and anousu = iAnousu
               and receita = rIptucalv.j21_receit;

            -- soma os valores de isenção da receita
            select coalesce(sum(valor_isencao), 0)
              into nValorCalculoAnteriorReceitaIsencao
              from tmpipturecalculo
             where matricula = iMatricula
               and anousu = iAnousu
               and receita = rIptucalv.j21_receit;

            -- desconta a isenção do valor da receita anterior
            nValorCalculoAnteriorReceita := nValorCalculoAnteriorReceita - nValorCalculoAnteriorReceitaIsencao;

            -- soma os valores de isenção da receita
            select coalesce(abs(sum(j21_valor)), 0)
              into nValorCalculoAtualReceitaIsencao
              from iptucalv
             where j21_matric = iMatricula
               and j21_anousu = iAnousu
               and j21_receit = rIptucalv.j21_receit
               and j21_codhis = rCfiptu.j18_iptuhistisen;

            -- desconta a isenção do valor da receita atual
            nValorCalculoAtualReceita := nValorCalculoAtualReceita - nValorCalculoAtualReceitaIsencao;

            -- verificar se cálculo anterior houve desconto de bom pagador
            perform 1
               from tmpipturecalculo
              where valor < 0
                and matricula = iMatricula
                and anousu = iAnousu
                and receita = rIptucalv.j21_receit;

            if not found then -- não teve desconto de bom pagador no cálculo anterior

              if nValorCalculoAtualReceita > nValorCalculoAnteriorReceita then

                -- pega valor do cálculo anterior que deveria ter disconto de bom pagador
                select valor - valor_isencao
                  into nValorDescontoIptuCalv
                  from tmpipturecalculo
                 where matricula = iMatricula
                   and anousu = iAnousu
                   and receita = rIptucalv.j21_receit;

                -- calcula valor que devia ter sido dado desconto na receita
                nValorDescontoIptuCalv := nValorDescontoIptuCalv * (rIptucalv.valor_desconto / 100);

                -- deduz valor do diverso lançado no recalculo
                update diversos
                   set dv05_vlrhis = dv05_vlrhis - nValorDescontoIptuCalv,
                       dv05_valor = dv05_valor - nValorDescontoIptuCalv
                 where dv05_coddiver = (select dv05_coddiver
                                          from diversos
                                               inner join arrecad on arrecad.k00_numpre = diversos.dv05_numpre
                                               inner join tmpipturecalculonump on tmpipturecalculonump.numpre = diversos.dv05_numpre
                                         where tmpipturecalculonump.matricula = iMatricula
                                           and tmpipturecalculonump.anousu = iAnousu
                                           and arrecad.k00_receit = rIptucalv.j21_receit);

                -- deduz valor do arrecad lançado no recalculo
                update arrecad
                   set k00_valor = k00_valor - nValorDescontoIptuCalv
                 where k00_receit = rIptucalv.j21_receit
                   and k00_numpre in (select tmpipturecalculonump.numpre
                                        from tmpipturecalculonump
                                       where tmpipturecalculonump.matricula = iMatricula
                                         and tmpipturecalculonump.anousu = iAnousu);

              elsif nValorCalculoAtualReceita <= nValorCalculoAnteriorReceita then

                nTotalCreditoReceita := nTotalCreditoReceita + (nValorCalculoAtualReceita * (rIptucalv.valor_desconto / 100));

              end if;

            else -- teve desconto de bom pagador no cálculo anterior

              if nValorCalculoAtualReceita <= nValorCalculoAnteriorReceita then

                nTotalCreditoReceita := nTotalCreditoReceita - ((nValorCalculoAnteriorReceita * (rIptucalv.valor_desconto / 100)) - (nValorCalculoAtualReceita * (rIptucalv.valor_desconto / 100)));

              end if;

            end if;

          end if;

        end loop;

        select tmpipturecalculocreditonump.numpre
          into iNumpreCredito
          from tmpipturecalculocreditonump
         where tmpipturecalculocreditonump.matricula = iMatricula
           and tmpipturecalculocreditonump.anousu = iAnousu;

        if iNumpreCredito is not null then

          update recibo
             set k00_valor = k00_valor + nTotalCreditoReceita
           where recibo.k00_numpre = iNumpreCredito;

          update abatimento
             set k125_valor = k125_valor + nTotalCreditoReceita,
                 k125_valordisponivel = k125_valordisponivel + nTotalCreditoReceita
            from abatimentorecibo
           where abatimento.k125_sequencial = abatimentorecibo.k127_abatimento
             and abatimentorecibo.k127_numprerecibo = iNumpreCredito;

        else

          if nTotalCreditoReceita > 0 then

            select fc_iptu_geracreditorecalculo(iMatricula, iAnousu, nTotalCreditoReceita, lRaise)
              into lCalculoQuitado;

            if lCalculoQuitado is false then

              perform fc_debug('Erro ao gerar crédito no recálculo de IPTU quitado.', lRaise, false, false);
              return false;
            end if;

          end if;

        end if;

      end if;

    end if;

  if lDemonstrativo is false then

    update iptucalc
       set j23_manual = tDemo
     where j23_matric = iMatricula
       and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }
}
