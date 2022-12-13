<?php

use Classes\PostgresMigration;

class M9704IptuTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<CALCULOIPTU
create or replace function fc_calculoiptu_taquari_2018(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula 	  	    alias   for $1;
   iAnousu              alias   for $2;
   lGerafinanceiro      alias   for $3;
   lAtualizaParcela     alias   for $4;
   lNovonumpre          alias   for $5;
   lCalculogeral        alias   for $6;
   lDemonstrativo       alias   for $7;
   iParcelaini          alias   for $8;
   iParcelafim          alias   for $9;

   iIdbql               integer default 0;
   iNumcgm              integer default 0;
   iCodcli              integer default 0;
   iCodisen             integer default 0;
   iTipois              integer default 0;
   iParcelas            integer default 0;
   iNumconstr           integer default 0;
	 iZona				        integer default 0;

   dDatabaixa           date;

   nAreal               numeric default 0;
   nAreac               numeric default 0;
   nTotarea             numeric default 0;
   nFracao              numeric default 0;
   nFracaolote          numeric default 0;
   nAliquota            numeric default 0;
   nIsenaliq            numeric default 0;
   nArealo              numeric default 0;
   nAreaLoteIsento      numeric default 0;
   nAreaRealLote        numeric default 0;
   nVvc                 numeric(15,2) default 0;
   nVvt                 numeric(15,2) default 0;
   nVv                  numeric(15,2) default 0;
   nViptu               numeric(15,2) default 0;
   nValorMaxAnoAnterior numeric(15,2) default 0;
   nFatorGleba          numeric default 0;

   tRetorno             text default '';
   tDemo                text default '';

   bFinanceiro          boolean;
   bDadosIptu           boolean;
   lErro                boolean;
	 iCodErro					    integer;
	 tErro						    text;
   bIsentaxas           boolean;
   bTempagamento        boolean;
   bEmpagamento         boolean;
   bTaxasCalculadas     boolean;
   lRaise               boolean default false;

   rCfiptu              record;

   nAreaLoteAnterior    numeric default 0;
   nValorIptuAnterior   numeric default 0;
   nAreaConstrAnterior  numeric default 0;
   iQuantConstrAnterior numeric default 0;
   nValorIdeal          numeric default 0;
   nValorLimite         numeric default 0;
   lValorExtrapolado    boolean default false;
   lAlterarValor        boolean default true;

begin

  lRaise    := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, bTempagamento,
         bEmpagamento, iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || bTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || bEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || bIsentaxas,    lRaise);
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

  update tmpdadosiptu set matric = iMatricula;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;
  
  if nAreal is null then

    select fc_iptu_geterro(6, '') into tRetorno;
    return tRetorno;
  end if;

  select rnarealo
    into nAreaLoteIsento
    from fc_iptu_verificaisencoes(iMatricula, iAnousu, lDemonstrativo, lRaise);

  if nAreaLoteIsento > 0 then

    nAreaRealLote = nAreal - nAreaLoteIsento;
    if nAreaRealLote < 0 then

      select fc_iptu_geterro(6, 'Area real do lote não pode ser menor que 0 (zero)') into tRetorno;
      return tRetorno;
    end if;

    perform fc_debug(' Area isenta do lote: ' || nAreaLoteIsento,lRaise);
  else

    nAreaRealLote = nAreal;
  end if;

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_taquari_2018 IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||tRetorno||'- ERRO: '||lErro, lRaise);
  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvt_taquari_2018( iMatricula, iIdbql, iAnousu, nFracaolote, nAreaRealLote, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvt_taquari_2018 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

	if lErro is true then

    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
	end if;

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_taquari_2018 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu||' - DEMO: '||lDemonstrativo, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvc_taquari_2018( iMatricula, iAnousu, rCfiptu.j18_vlrref::numeric, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvc_taquari_2018 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRUÇÕES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then

    select fc_iptu_geterro(22, '') into tRetorno;
    return tRetorno;
  end if;

  /**
   * Busca a aliquota
   */
  select fc_iptu_getaliquota_taquari_2018(iMatricula, lRaise) into nAliquota;

  if nAliquota = 0 then

    select fc_iptu_geterro(13, '') into tRetorno;
    return trim(tRetorno) || ', VERIFIQUE A CARACTERISTICA DO GRUPO 3.';
  end if;

  /**
   * Calcula o Valor Venal
   */
  perform fc_debug('' || lpad('',60,'-'), lRaise);

  nVv    := (nVvc + nVvt) * 0.2;
  perform fc_debug(' CALCULO DO VALOR VENAL: Vvc= '||nVvc||' nVvt= '||nVvt||' VALOR VENAL= '||nVv, lRaise);

  perform fc_debug(' CALCULO DO VALOR DO IPTU: Vvi= '||nVv||' Aliquota= '||nAliquota/100, lRaise);
  nViptu := nVv * (nAliquota / 100);

  perform fc_debug('nViptu : ' || nViptu, lRaise);

  /**
   * FAZ A MÃO DE VALIDAR CÁLCULOS PASSADOS
   */
  nValorLimite = 1.2;
  perform fc_debug('' || lpad('',60,'-'), lRaise);

  select j21_valor
    into nValorIptuAnterior
    from iptucalv
         where j21_matric = iMatricula
           and j21_anousu = iAnousu - 1
           and j21_receit in (rCfiptu.j18_rterri, rCfiptu.j18_rpredi);

  if nViptu < nValorIptuAnterior then

    lValorExtrapolado := true;
    nValorIdeal := nValorIptuAnterior;
  end if;

  if nViptu > nValorIptuAnterior * nValorLimite then

    lValorExtrapolado := true;
    nValorIdeal := nValorIptuAnterior * nValorLimite;
  end if;

  if lValorExtrapolado THEN

    perform fc_debug('VALOR EXTRAPOLOU', lRaise);

    select COALESCE(j23_arealo, 0)
      into nAreaLoteAnterior
      from iptucalc
     where j23_matric = iMatricula
       and j23_anousu = iAnousu - 1;

    perform fc_debug('nAreaLoteAnterior        : ' || nAreaLoteAnterior, lRaise);
    perform fc_debug('nAreaReallote fracionado : ' || round(nAreaReallote * ( nFracaolote / 100 ), 2), lRaise);

    if  nAreaLoteAnterior <> round(nAreaReallote * ( nFracaolote / 100 ), 2) then
      lAlterarValor := false;
    end if;

    select count(*), sum(j22_areaed)
      into iQuantConstrAnterior, nAreaConstrAnterior
      from iptucale
     where j22_matric = iMatricula
       and j22_anousu = iAnousu - 1
     group by j22_matric;

    perform fc_debug('Quantidade Construcoes anterior: ' || iQuantConstrAnterior, lRaise);
    perform fc_debug('Quantidade Construcoes         : ' || iNumconstr, lRaise);
    perform fc_debug('Area Construida anterior: ' || nAreaConstrAnterior, lRaise);
    perform fc_debug('Area Construida         : ' || nTotarea, lRaise);

    if iQuantConstrAnterior <> iNumconstr or nAreaConstrAnterior <> nTotarea THEN
      lAlterarValor := false;
    end if;

    if lAlterarValor then
      nViptu = nValorIdeal;
    end if;
  end if;

  perform fc_debug('' || lpad('',60,'-'), lRaise);
  perform fc_debug('nViptu : ' || nViptu, lRaise);
  perform fc_debug('iptu anterior : ' || nValorIptuAnterior, lRaise);

  --================================================================

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim;

  if not found or iParcelas = 0 then

    select fc_iptu_geterro(14, '') into tRetorno;
    return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu
     set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa
     set anousu  = iAnousu,
         matric  = iMatricula,
         idbql   = iIdbql,
         valiptu = nViptu,
         valref  = rCfiptu.j18_vlrref,
         vvt     = nVvt,
         nparc   = iParcelas;

  perform fc_debug('PARAMETROS fc_iptu_calculataxas ANOUSU: '||iAnousu||' - CODCLI: '||iCodcli, lRaise);

  /**
   * Calcula as taxas
   */
  select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lRaise)
    into bTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas -> TAXASCALCULADAS: ' || bTaxasCalculadas, lRaise);

  /**
   * Monta o demonstrativo
   */
  select fc_iptu_demonstrativo(iMatricula, iAnousu, iIdbql, lRaise)
    into tDemo;

  /**
   * Gera financeiro
   *  -> Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
   */
  if lDemonstrativo is false then

    select fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, lDemonstrativo, lRaise)
      into bDadosIptu;

      if lGerafinanceiro then

        select fc_iptu_gerafinanceiro( iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, bTempagamento, lNovonumpre, lDemonstrativo, lRaise )
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if lDemonstrativo is false then

     update iptucalc
        set j23_manual = tDemo
      where j23_matric = iMatricula
        and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise, false, true);

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$ language 'plpgsql';
CALCULOIPTU;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("DROP FUNCTION fc_calculoiptu_taquari_2018(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);");
    }
}
