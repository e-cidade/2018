<?php

use Classes\PostgresMigration;

class M7762CalculoIptuAlegrete extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
      $this->execute(
        <<<LABEL
       drop function if exists fc_calculoiptu_ale_2009(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);
create or replace function fc_calculoiptu_ale_2009(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula 	  	alias   for $1;
   iAnousu    	  	alias   for $2;
   bGerafinanc      alias   for $3;
   bAtualizap	 	  	alias   for $4;
   bNovonumpre	  	alias   for $5;
   bCalculogeral   	alias   for $6;
   bDemo		       	alias   for $7;
   iParcelaini     	alias   for $8;
   iParcelafim     	alias   for $9;

   iIdbql           integer default 0;
   iNumcgm          integer default 0;
   iCodcli          integer default 0;
   iCodisen         integer default 0;
   iTipois          integer default 0;
   iParcelas        integer default 0;
   iNumconstr       integer default 0;
   iCodErro         integer default 0;

   dDatabaixa       date;

   nAreal           numeric default 0;
   nAreac           numeric default 0;
   nTotarea         numeric default 0;
   nFracao          numeric default 0;
   nFracaolote      numeric default 0;

   nAliquota            numeric default 0;
   nAliquotaTerritorial numeric default 0;
   nAliquotaPredial     numeric default 0;

   nProporcaoVVT        numeric(15,2) default 0;
   nVVCIsencao          numeric(15,2) default 0;
   nVVTIsencao          numeric(15,2) default 0;
   nVVCVVTIsencao       numeric(15,2) default 0;

   iMesesPredial     integer;
   iMesesTerritorial integer;
   nVIptuVvt         numeric(15,2) default 0;
   nVIptuVvc         numeric(15,2) default 0;

   nIsenaliq        numeric default 0;
   nArealo          numeric default 0;
   nVvc             numeric(15,2) default 0;
   nVvt             numeric(15,2) default 0;
   nVv              numeric(15,2) default 0;
   nViptu           numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';

   bFinanceiro      boolean;
   bDadosIptu       boolean;
   bErro            boolean;
   bIsentaxas       boolean;
   bTempagamento    boolean;
   bEmpagamento     boolean;
   bTaxasCalculadas boolean;
   lRaise           boolean default false; -- true para abilitar raise na funcao principal
   lSulRaise        boolean default false; -- true para abilitar raise nas sub-funcoes

   rCfiptu          record;

begin

  lRaise    := (fc_getsession('DB_debugon') <> '');
  lSulRaise := (fc_getsession('DB_debugon') <> '');

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

/* VERIFICA SE OS PARAMETROS PASSADOS ESTAO CORRETOS */
  select riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rberro, rtretorno
    into iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno
    from fc_iptu_verificaparametros(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if lRaise then
    raise notice 'IDBQL - %  AREAL - %  FRACAO - %  CGM - %   DATABAIXA - %   ERRO - %  RETORNO - %',  iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno;
  end if;

  /* VERIFICA SE O CALCULO PODE SER REALIZADO */
  select rbErro,
         riCodErro
    into bErro,
         iCodErro
    from fc_iptu_verificacalculo(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if bErro is true and bDemo is false then
    select fc_iptu_geterro(iCodErro,'') into tRetorno;
    return tRetorno;
  end if;

  /* VERIFICA SE MATRICULA ESTA BAIXADA */
  if dDataBaixa is not null and to_char(dDataBaixa,'Y')::integer <= iAnousu then
     /* criar funcao para exclusao de calculo */
     delete from arrecad using iptunump
      where k00_numpre = iptunump.j20_numpre
        and iptunump.j20_anousu = iAnousu
        and iptunump.j20_matric = iMatricula;
     delete from iptunump
      where j20_anousu = iAnousu
        and j20_matric = iMatricula;

     select fc_iptu_geterro(2,'') into tRetorno;
     return tRetorno;
  end if;


  /* CRIA AS TABELAS TEMPORARIAS */
  select * into bErro from fc_iptu_criatemptable(lSulRaise);

  /* GUARDA OS PARAMETROS DO CALCULO */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /* FRACIONA LOTE */
  if lRaise then
    raise notice 'PARAMETROS IPTU_FRACIONALOTE FRACAO DO LOTE : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select rnfracao, rtdemo, rtmsgerro, rberro
    into nFracaolote, tDemo, tRetorno, bErro
    from fc_iptu_fracionalote(iMatricula,iAnousu,bDemo,lSulRaise);
    update tmpdadosiptu set fracao = nFracaolote;
  if lRaise then
    raise notice 'RETORNO FC_IPTU_FRACIONALOTE --->>> FRACAO DO LOTE : % - DEMONS : % - MSGRETORNO : % - ERRO : % ',nFracaolote, tDemo, tRetorno, bErro;
  end if;

  /* VERIFICA PAGAMENTOS */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_verificapag VERIFICANDO PARGAMENTOS  : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select rbtempagamento, rbempagamento, rtmsgretorno, rberro
    into bTempagamento, bEmpagamento, tRetorno, bErro
    from fc_iptu_verificapag(iMatricula,iAnousu,bCalculogeral,bAtualizap,false,bDemo,lSulRaise);
  if lRaise then
    raise notice 'RETORNO fc_iptu_verificapag -->>> TEMPAGAMENTO : % -- EMPAGAMENTO % -- RETORNO % -- ERRO % ',bTempagamento, bEmpagamento, tRetorno, bErro;
  end if;

  /* CALCULA VALOR DO TERRENO */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvt_ale_2009  IDBQL : % -- FRACAO DO LOTE % -- DEMO % -- ERRO % ',iIdbql, nFracaolote, tRetorno, bErro;
  end if;

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro
    into nVvt, nAreac, tDemo, tRetorno, bErro
    from fc_iptu_calculavvt_ale_2009(iMatricula, iIdbql, iAnousu, nFracaolote, nAreal, bDemo, lSulRaise);
  if lRaise then
    raise notice 'RETORNO fc_iptu_calculavvt_ale_2009 -->>> VVT : % -- AREA CONTRUIDA % --  RETORNO % -- ERRO % ',nVvt, nAreac, tRetorno, bErro;
  end if;
  if bErro is true then

    select fc_iptu_geterro(99,tRetorno) into tRetorno;
    return tRetorno;
  end if;


  /* VERIFICA ISENCOES */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_verificaisencoes  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select ricodisen, ritipois, rnisenaliq, rbisentaxas, rnarealo
    into iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo
    from fc_iptu_verificaisencoes(iMatricula,iAnousu,bDemo,lSulRaise);
  if iTipois is not null then
    update tmpdadosiptu set tipoisen = iTipois;
  end if;
  if lRaise then
    raise notice 'RETORNO fc_iptu_verificaisencoes -->>> CODISEN : % -- TIPOISEN : % --  ALIQ INSEN : % -- INSENTAXAS: % -- AREALO : % ',iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo;
  end if;

  /* CALCULA VALOR DA CONSTRUCAO */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvc_ale_2009  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, lSulRaise;
  end if;

  select rnvvc,
         rntotarea,
         rimesespredial,
         rnValorIsencao,
         rinumconstr,
         rtdemo,
         rtmsgerro,
         rberro
    into nVvc,
         nTotarea,
         iMesesPredial,
         nVVCIsencao,
         iNumconstr,
         tDemo,
         tRetorno,
         bErro
    from fc_iptu_calculavvc_ale_2009(iMatricula, iAnousu, bDemo, lSulRaise);

  if lRaise then
    raise notice 'RETORNO fc_iptu_calculavvc_ale_2009 -->>> VVC : % -- AREA TOTAL : % --  NUMERO DE CONTRUCOES : % -- RETORNO : % -- ERRO : % ', nVvc, nTotarea, iNumconstr, tRetorno, bErro;
  end if;

  if (nVvc is null or nVvc = 0 or bErro is true) and iNumconstr <> 0 then
    select fc_iptu_geterro( 29, tRetorno ) into tRetorno;
    return tRetorno;
  end if;

  -- BUSCA AS ALIQUOTAS
  select fc_iptu_getaliquota_ale_2009(iMatricula,iIdbql,iNumcgm,true,lSulRaise) into nAliquotaPredial;
  select fc_iptu_getaliquota_ale_2009(iMatricula,iIdbql,iNumcgm,false,lSulRaise) into nAliquotaTerritorial;

  if nAliquotaTerritorial = 0 or nAliquotaPredial = 0 then
    select fc_iptu_geterro(13, '') into tRetorno;
    return tRetorno;
  end if;

  -- nVVTIsencao := nVvt * ()

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Verifica se esta instalado o plugin cálculo de IPTU proporcional
  if not found then

    nAliquota := nAliquotaTerritorial;

    if iNumconstr > 0 then
      nAliquota := nAliquotaPredial;
    end if;

    nVv    := nVvc + nVvt;
    nViptu := nVv * (nAliquota / 100);

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- Efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  -- Verifica se a mudanças de predial e territorial
  elseif found then

    perform fc_debug(' <iptu_proporcional> ---------------- I N I C I O ----------------', lRaise, false, false);

    if iMesesPredial <> 12 and iMesesPredial <> 0 then

      nProporcaoVVT := (nVvt / 12) * (12 - iMesesPredial);
      nVIptuVvt := (nProporcaoVVT * (nAliquotaTerritorial / 100)) + ((nVvt - nProporcaoVVT) * (nAliquotaPredial / 100));

      nAliquota := nAliquotaPredial;

    else

      nAliquota := nAliquotaTerritorial;

      if iNumconstr > 0 then
        nAliquota := nAliquotaPredial;
      end if;

      nVIptuVvt := nVvt * (nAliquota / 100);

    end if;

    nVIptuVvc := nVvc * (nAliquotaPredial / 100);

    nVvcIsencao := nVIptuVvc - (nVvcIsencao * (nAliquotaPredial / 100));
    nVvtIsencao := nVIptuVvt * (nIsenaliq / 100);

    nViptu := nVIptuVvc + nVIptuVvt;

    nVvcVvtIsencao := nVvcIsencao + nVvtIsencao;

    nIsenaliq := (nVvcVvtIsencao * 100) / nViptu;

    perform fc_debug(' <iptu_proporcional> nVvc .............: ' || nVvc,                  lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvt .............: ' || nVvt,                  lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nAliquotaPredial .: ' || nAliquotaPredial,      lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVIptuVvc ........: ' || nVIptuVvc,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVIptuVvt ........: ' || nVIptuVvt,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvcIsencao ......: ' || nVvcIsencao,           lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvtIsencao ......: ' || nVvtIsencao,           lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvcVvtIsencao ...: ' || nVvcVvtIsencao,        lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nIsenaliq ........: ' || nIsenaliq,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nViptu ...........: ' || nViptu,                lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> ------------------- F I M -------------------', lRaise, false, false);

  end if;

  update tmpdadosiptu set aliq = nAliquota;

  perform fc_debug('nViptu : '||nViptu,lRaise,false,false);

  perform predial from tmpdadosiptu where predial is true;

  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  if not found or iParcelas = 0 then
     select fc_iptu_geterro(14, '') into tRetorno;
     return tRetorno;
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

/* CALCULA AS TAXAS */
  select db21_codcli
    into iCodcli
    from db_config
    where prefeitura is true;


  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculataxas  ANOUSU % -- CODCLI % ',iAnousu, iCodcli;
  end if;

  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSulRaise)
    into bTaxasCalculadas;

  if lRaise then
    raise notice 'RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - %',bTaxasCalculadas;
  end if;

/* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSulRaise )
     into tDemo;

/* GERA FINANCEIRO */
  if bDemo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,bDemo,lSulRaise)
      into bDadosIptu;
      if bGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,bCalculogeral,bTempagamento,bNovonumpre,bDemo,lSulRaise)
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if bDemo is false then
     update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise,false,true);

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';

LABEL

      );

      $this->execute(<<<LABEL
drop function if exists fc_iptu_calculavvc_ale_2009(integer,integer,boolean,boolean);

create or replace function fc_iptu_calculavvc_ale_2009(iMatricula integer,
                                                       iAnousu integer,
                                                       bMostrademo boolean,
                                                       lRaise boolean,
                                                       OUT rnVvc          numeric(15,2),
                                                       OUT rnTotarea      numeric,
                                                       OUT riNumconstr    integer,
                                                       OUT riMesesPredial integer,
                                                       OUT rtDemo         text,
                                                       OUT rtMsgerro      text,
                                                       OUT rbErro         boolean,
                                                       OUT riCodErro      integer,
                                                       OUT rnValorIsencao numeric,
                                                       OUT rtErro         text)
returns record as
$$
declare

  bAtualiza              boolean default true;

  iTotalConstrucoes integer default 0;
  iMesesPredial     integer default 0;

  iConstrucao       integer;
  rConstrucao       record;

  nValorConstrucao        numeric;
  nValorVenalPredial      numeric;
  nValorVenalPredialTotal numeric default 0;
  nAreaTotalEdificada     numeric default 0;
  aTotalMeses             integer[];

  nValorIsencao           numeric default 0;

begin

  perform fc_debug('INICIANDO CALCULO VVC ...', lRaise, false, false);

  rnVvc          := 0;
  rnTotarea      := 0;
  riNumconstr    := 0;
  riMesesPredial := 0;
  rnValorIsencao := 0;
  rtDemo         := '';
  rtMsgerro      := 'Retorno ok' ;
  rbErro         := 'f';
  riCodErro      := 0;
  rtErro         := '';

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calculo_vvc> - Plugin de Cálculo de IPTU proporcional instalado', lRaise, false, false);
    perform fc_debug(' <calculo_vvc> - Calculando o valor proporcional mes a mes', lRaise, false, false);

    for rConstrucao in

      select array_accum(mes) as meses,
             j39_idcons,
             j39_area,
             sum(coalesce((select area
                         from plugins.iptuconstrareahistorico
                        where matricula = j39_matric
                          and id_constr = j39_idcons
                          and data >= (iAnousu||'-'||mes||'-01')::date
                        order by data
                        limit 1), j39_area)::numeric / 12) as area,
             sum(
                coalesce(
                  (select
                    (
                      (
                        (coalesce(
                          (select area
                             from plugins.iptuconstrareahistorico
                            where matricula = j39_matric
                              and id_constr = j39_idcons
                              and data >= (iAnousu||'-'||mes||'-01')::date
                            order by data
                            limit 1
                          ), j39_area)
                        ) / 100
                      ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_competencia(j39_matric, iAnousu, mes, lRaise)), 0))
                    )
                  ), 0)
              )::numeric / 12 as area_isencao
        from iptuconstr, generate_series(1,12) as mes
       where iptuconstr.j39_matric = iMatricula
         and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
         and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
       group by j39_idcons,
                j39_area
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(iMatricula, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      nValorIsencao := nValorIsencao + (nValorVenalPredial - (nValorConstrucao * rConstrucao.area_isencao));

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área com Isenção: '||rConstrucao.area_isencao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

      aTotalMeses := array_cat(aTotalMeses, rConstrucao.meses);
    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calculo_vvc> - Meses para cálculo predial: '||iMesesPredial, lRaise, false, false);

  else

    for rConstrucao in
      select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
        from iptuconstr
       where iptuconstr.j39_dtdemo is null
         and iptuconstr.j39_matric = iMatricula
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(rConstrucao.j39_matric, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.j39_area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.j39_area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

    end loop;

  end if;

  perform fc_debug('Valor Venal Predial Total: '||nValorVenalPredialTotal, lRaise, false, false);
  perform fc_debug('Área Edificada Total: '||nAreaTotalEdificada, lRaise, false, false);
  perform fc_debug('Valor da Isenção: '||nValorIsencao, lRaise, false, false);


  rnVvc          := nValorVenalPredialTotal;
  rnTotarea      := nAreaTotalEdificada;
  riNumconstr    := iTotalConstrucoes;
  riMesesPredial := iMesesPredial;
  rnValorIsencao := nValorIsencao;
  rtDemo         := '';

  update tmpdadosiptu set vvc = rnVvc;
  return;

end;
$$  language 'plpgsql';

LABEL
);
    }

    public function down()
    {

      $this->execute(<<<LABEL
      drop function if exists fc_iptu_calculavvc_ale_2009(integer,integer,boolean,boolean);

create or replace function fc_iptu_calculavvc_ale_2009(iMatricula integer,
                                                       iAnousu integer,
                                                       bMostrademo boolean,
                                                       lRaise boolean,
                                                       OUT rnVvc          numeric(15,2),
                                                       OUT rnTotarea      numeric,
                                                       OUT riNumconstr    integer,
                                                       OUT riMesesPredial integer,
                                                       OUT rtDemo         text,
                                                       OUT rtMsgerro      text,
                                                       OUT rbErro         boolean,
                                                       OUT riCodErro      integer,
                                                       OUT rnValorIsencao numeric,
                                                       OUT rtErro         text)
returns record as
$$
declare

  bAtualiza              boolean default true;

  iTotalConstrucoes integer default 0;
  iMesesPredial     integer default 0;

  iConstrucao       integer;
  rConstrucao       record;

  nValorConstrucao        numeric;
  nValorVenalPredial      numeric;
  nValorVenalPredialTotal numeric default 0;
  nAreaTotalEdificada     numeric default 0;
  aTotalMeses             integer[];

  nValorIsencao           numeric default 0;

begin

  perform fc_debug('INICIANDO CALCULO VVC ...', lRaise, false, false);

  rnVvc          := 0;
  rnTotarea      := 0;
  riNumconstr    := 0;
  riMesesPredial := 0;
  rnValorIsencao := 0;
  rtDemo         := '';
  rtMsgerro      := 'Retorno ok' ;
  rbErro         := 'f';
  riCodErro      := 0;
  rtErro         := '';

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calculo_vvc> - Plugin de Cálculo de IPTU proporcional instalado', lRaise, false, false);
    perform fc_debug(' <calculo_vvc> - Calculando o valor proporcional mes a mes', lRaise, false, false);

    for rConstrucao in

      select array_accum(mes) as meses,
             j39_idcons,
             j39_area,
             sum(coalesce((select area
                         from plugins.iptuconstrareahistorico
                        where matricula = j39_matric
                          and id_constr = j39_idcons
                          and data >= (iAnousu||'-'||mes||'-01')::date
                        order by data
                        limit 1), j39_area)::numeric / 12) as area,
             sum(
                coalesce(
                  (select
                    (
                      (
                        (coalesce(
                          (select area
                             from plugins.iptuconstrareahistorico
                            where matricula = j39_matric
                              and id_constr = j39_idcons
                              and data >= (iAnousu||'-'||mes||'-01')::date
                            order by data
                            limit 1
                          ), j39_area)
                        ) / 100
                      ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_competencia(j39_matric, iAnousu, mes, lRaise)), 0))
                    )
                  ), 0)
              )::numeric / 12 as area_isencao
        from iptuconstr, generate_series(1,12) as mes
       where iptuconstr.j39_matric = iMatricula
         and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
         and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
       group by j39_idcons,
                j39_area
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(iMatricula, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      nValorIsencao := nValorIsencao + (nValorVenalPredial - (nValorConstrucao * rConstrucao.area_isencao));

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área com Isenção: '||rConstrucao.area_isencao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riNumconstr    := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        return;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

      aTotalMeses := array_cat(aTotalMeses, rConstrucao.meses);
    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calculo_vvc> - Meses para cálculo predial: '||iMesesPredial, lRaise, false, false);

  else

    for rConstrucao in
      select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
        from iptuconstr
       where iptuconstr.j39_dtdemo is null
         and iptuconstr.j39_matric = iMatricula
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(rConstrucao.j39_matric, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.j39_area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.j39_area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riNumconstr    := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        return;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

    end loop;

  end if;

  perform fc_debug('Valor Venal Predial Total: '||nValorVenalPredialTotal, lRaise, false, false);
  perform fc_debug('Área Edificada Total: '||nAreaTotalEdificada, lRaise, false, false);
  perform fc_debug('Valor da Isenção: '||nValorIsencao, lRaise, false, false);

  rnVvc          := nValorVenalPredialTotal;
  rnTotarea      := nAreaTotalEdificada;
  riNumconstr    := iTotalConstrucoes;
  riMesesPredial := iMesesPredial;
  rnValorIsencao := nValorIsencao;
  rtDemo         := '';
  rbErro         := 'f';

  update tmpdadosiptu set vvc = rnVvc;
  return;

end;
$$  language 'plpgsql';


LABEL
);
      $this->execute(<<<LABEL
   drop function if exists fc_calculoiptu_ale_2009(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);
create or replace function fc_calculoiptu_ale_2009(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula 	  	alias   for $1;
   iAnousu    	  	alias   for $2;
   bGerafinanc      alias   for $3;
   bAtualizap	 	  	alias   for $4;
   bNovonumpre	  	alias   for $5;
   bCalculogeral   	alias   for $6;
   bDemo		       	alias   for $7;
   iParcelaini     	alias   for $8;
   iParcelafim     	alias   for $9;

   iIdbql           integer default 0;
   iNumcgm          integer default 0;
   iCodcli          integer default 0;
   iCodisen         integer default 0;
   iTipois          integer default 0;
   iParcelas        integer default 0;
   iNumconstr       integer default 0;
   iCodErro         integer default 0;

   dDatabaixa       date;

   nAreal           numeric default 0;
   nAreac           numeric default 0;
   nTotarea         numeric default 0;
   nFracao          numeric default 0;
   nFracaolote      numeric default 0;

   nAliquota            numeric default 0;
   nAliquotaTerritorial numeric default 0;
   nAliquotaPredial     numeric default 0;

   nProporcaoVVT        numeric(15,2) default 0;
   nVVCIsencao          numeric(15,2) default 0;
   nVVTIsencao          numeric(15,2) default 0;
   nVVCVVTIsencao       numeric(15,2) default 0;

   iMesesPredial     integer;
   iMesesTerritorial integer;
   nVIptuVvt         numeric(15,2) default 0;
   nVIptuVvc         numeric(15,2) default 0;

   nIsenaliq        numeric default 0;
   nArealo          numeric default 0;
   nVvc             numeric(15,2) default 0;
   nVvt             numeric(15,2) default 0;
   nVv              numeric(15,2) default 0;
   nViptu           numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';

   bFinanceiro      boolean;
   bDadosIptu       boolean;
   bErro            boolean;
   bIsentaxas       boolean;
   bTempagamento    boolean;
   bEmpagamento     boolean;
   bTaxasCalculadas boolean;
   lRaise           boolean default false; -- true para abilitar raise na funcao principal
   lSulRaise        boolean default false; -- true para abilitar raise nas sub-funcoes

   rCfiptu          record;

begin

  lRaise    := (fc_getsession('DB_debugon') <> '');
  lSulRaise := (fc_getsession('DB_debugon') <> '');

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

/* VERIFICA SE OS PARAMETROS PASSADOS ESTAO CORRETOS */
  select riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rberro, rtretorno
    into iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno
    from fc_iptu_verificaparametros(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if lRaise then
    raise notice 'IDBQL - %  AREAL - %  FRACAO - %  CGM - %   DATABAIXA - %   ERRO - %  RETORNO - %',  iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno;
  end if;

  /* VERIFICA SE O CALCULO PODE SER REALIZADO */
  select rbErro,
         riCodErro
    into bErro,
         iCodErro
    from fc_iptu_verificacalculo(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if bErro is true and bDemo is false then
    select fc_iptu_geterro(iCodErro,'') into tRetorno;
    return tRetorno;
  end if;

  /* VERIFICA SE MATRICULA ESTA BAIXADA */
  if dDataBaixa is not null and to_char(dDataBaixa,'Y')::integer <= iAnousu then
     /* criar funcao para exclusao de calculo */
     delete from arrecad using iptunump
      where k00_numpre = iptunump.j20_numpre
        and iptunump.j20_anousu = iAnousu
        and iptunump.j20_matric = iMatricula;
     delete from iptunump
      where j20_anousu = iAnousu
        and j20_matric = iMatricula;

     select fc_iptu_geterro(2,'') into tRetorno;
     return tRetorno;
  end if;


  /* CRIA AS TABELAS TEMPORARIAS */
  select * into bErro from fc_iptu_criatemptable(lSulRaise);

  /* GUARDA OS PARAMETROS DO CALCULO */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /* FRACIONA LOTE */
  if lRaise then
    raise notice 'PARAMETROS IPTU_FRACIONALOTE FRACAO DO LOTE : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select rnfracao, rtdemo, rtmsgerro, rberro
    into nFracaolote, tDemo, tRetorno, bErro
    from fc_iptu_fracionalote(iMatricula,iAnousu,bDemo,lSulRaise);
    update tmpdadosiptu set fracao = nFracaolote;
  if lRaise then
    raise notice 'RETORNO FC_IPTU_FRACIONALOTE --->>> FRACAO DO LOTE : % - DEMONS : % - MSGRETORNO : % - ERRO : % ',nFracaolote, tDemo, tRetorno, bErro;
  end if;

  /* VERIFICA PAGAMENTOS */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_verificapag VERIFICANDO PARGAMENTOS  : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select rbtempagamento, rbempagamento, rtmsgretorno, rberro
    into bTempagamento, bEmpagamento, tRetorno, bErro
    from fc_iptu_verificapag(iMatricula,iAnousu,bCalculogeral,bAtualizap,false,bDemo,lSulRaise);
  if lRaise then
    raise notice 'RETORNO fc_iptu_verificapag -->>> TEMPAGAMENTO : % -- EMPAGAMENTO % -- RETORNO % -- ERRO % ',bTempagamento, bEmpagamento, tRetorno, bErro;
  end if;

  /* CALCULA VALOR DO TERRENO */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvt_ale_2009  IDBQL : % -- FRACAO DO LOTE % -- DEMO % -- ERRO % ',iIdbql, nFracaolote, tRetorno, bErro;
  end if;

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro
    into nVvt, nAreac, tDemo, tRetorno, bErro
    from fc_iptu_calculavvt_ale_2009(iMatricula, iIdbql, iAnousu, nFracaolote, nAreal, bDemo, lSulRaise);
  if lRaise then
    raise notice 'RETORNO fc_iptu_calculavvt_ale_2009 -->>> VVT : % -- AREA CONTRUIDA % --  RETORNO % -- ERRO % ',nVvt, nAreac, tRetorno, bErro;
  end if;
  if bErro is true then

    select fc_iptu_geterro(99,tRetorno) into tRetorno;
    return tRetorno;
  end if;


  /* VERIFICA ISENCOES */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_verificaisencoes  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, lSulRaise;
  end if;
  select ricodisen, ritipois, rnisenaliq, rbisentaxas, rnarealo
    into iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo
    from fc_iptu_verificaisencoes(iMatricula,iAnousu,bDemo,lSulRaise);
  if iTipois is not null then
    update tmpdadosiptu set tipoisen = iTipois;
  end if;
  if lRaise then
    raise notice 'RETORNO fc_iptu_verificaisencoes -->>> CODISEN : % -- TIPOISEN : % --  ALIQ INSEN : % -- INSENTAXAS: % -- AREALO : % ',iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo;
  end if;

  /* CALCULA VALOR DA CONSTRUCAO */
  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvc_ale_2009  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, lSulRaise;
  end if;

  select rnvvc,
         rntotarea,
         rimesespredial,
         rnValorIsencao,
         rinumconstr,
         rtdemo,
         rtmsgerro,
         rberro
    into nVvc,
         nTotarea,
         iMesesPredial,
         nVVCIsencao,
         iNumconstr,
         tDemo,
         tRetorno,
         bErro
    from fc_iptu_calculavvc_ale_2009(iMatricula, iAnousu, bDemo, lSulRaise);

  if lRaise then
    raise notice 'RETORNO fc_iptu_calculavvc_ale_2009 -->>> VVC : % -- AREA TOTAL : % --  NUMERO DE CONTRUCOES : % -- RETORNO : % -- ERRO : % ', nVvc, nTotarea, iNumconstr, tRetorno, bErro;
  end if;

  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then
    select fc_iptu_geterro( 29, tRetorno ) into tRetorno;
    return tRetorno;
  end if;

  -- BUSCA AS ALIQUOTAS
  select fc_iptu_getaliquota_ale_2009(iMatricula,iIdbql,iNumcgm,true,lSulRaise) into nAliquotaPredial;
  select fc_iptu_getaliquota_ale_2009(iMatricula,iIdbql,iNumcgm,false,lSulRaise) into nAliquotaTerritorial;

  if nAliquotaTerritorial = 0 or nAliquotaPredial = 0 then
    select fc_iptu_geterro(13, '') into tRetorno;
    return tRetorno;
  end if;

  -- nVVTIsencao := nVvt * ()

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Verifica se esta instalado o plugin cálculo de IPTU proporcional
  if not found then

    nAliquota := nAliquotaTerritorial;

    if iNumconstr > 0 then
      nAliquota := nAliquotaPredial;
    end if;

    nVv    := nVvc + nVvt;
    nViptu := nVv * (nAliquota / 100);

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- Efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  -- Verifica se a mudanças de predial e territorial
  elseif found then

    perform fc_debug(' <iptu_proporcional> ---------------- I N I C I O ----------------', lRaise, false, false);

    if iMesesPredial <> 12 and iMesesPredial <> 0 then

      nProporcaoVVT := (nVvt / 12) * (12 - iMesesPredial);
      nVIptuVvt := (nProporcaoVVT * (nAliquotaTerritorial / 100)) + ((nVvt - nProporcaoVVT) * (nAliquotaPredial / 100));

      nAliquota := nAliquotaPredial;

    else

      nAliquota := nAliquotaTerritorial;

      if iNumconstr > 0 then
        nAliquota := nAliquotaPredial;
      end if;

      nVIptuVvt := nVvt * (nAliquota / 100);

    end if;

    nVIptuVvc := nVvc * (nAliquotaPredial / 100);

    nVvcIsencao := nVIptuVvc - (nVvcIsencao * (nAliquotaPredial / 100));
    nVvtIsencao := nVIptuVvt * (nIsenaliq / 100);

    nViptu := nVIptuVvc + nVIptuVvt;

    nVvcVvtIsencao := nVvcIsencao + nVvtIsencao;

    nIsenaliq := (nVvcVvtIsencao * 100) / nViptu;

    perform fc_debug(' <iptu_proporcional> nVvc .............: ' || nVvc,                  lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvt .............: ' || nVvt,                  lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nAliquotaPredial .: ' || nAliquotaPredial,      lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVIptuVvc ........: ' || nVIptuVvc,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVIptuVvt ........: ' || nVIptuVvt,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvcIsencao ......: ' || nVvcIsencao,           lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvtIsencao ......: ' || nVvtIsencao,           lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nVvcVvtIsencao ...: ' || nVvcVvtIsencao,        lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nIsenaliq ........: ' || nIsenaliq,             lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> nViptu ...........: ' || nViptu,                lRaise, false, false);
    perform fc_debug(' <iptu_proporcional> ------------------- F I M -------------------', lRaise, false, false);

  end if;

  update tmpdadosiptu set aliq = nAliquota;

  perform fc_debug('nViptu : '||nViptu,lRaise,false,false);

  perform predial from tmpdadosiptu where predial is true;

  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  if not found or iParcelas = 0 then
     select fc_iptu_geterro(14, '') into tRetorno;
     return tRetorno;
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

/* CALCULA AS TAXAS */
  select db21_codcli
    into iCodcli
    from db_config
    where prefeitura is true;


  if lRaise then
    raise notice 'PARAMETROS fc_iptu_calculataxas  ANOUSU % -- CODCLI % ',iAnousu, iCodcli;
  end if;

  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSulRaise)
    into bTaxasCalculadas;

  if lRaise then
    raise notice 'RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - %',bTaxasCalculadas;
  end if;

/* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSulRaise )
     into tDemo;

/* GERA FINANCEIRO */
  if bDemo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,bDemo,lSulRaise)
      into bDadosIptu;
      if bGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,bCalculogeral,bTempagamento,bNovonumpre,bDemo,lSulRaise)
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if bDemo is false then
     update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise,false,true);

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';

LABEL
      );
    }
}
