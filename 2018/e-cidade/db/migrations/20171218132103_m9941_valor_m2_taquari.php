<?php

use Classes\PostgresMigration;

class M9941ValorM2Taquari extends PostgresMigration
{

    public function up()
    {
        $sFuncao = <<<FUNCAO
create or replace function fc_iptu_calculavvt_taquari_2018( integer, integer, integer, numeric, numeric, boolean, boolean,
                                                            OUT rnVvt        numeric(15,2),
                                                            OUT rnAreaTotalC numeric,
                                                            OUT rnArea       numeric,
                                                            OUT rnTestada    numeric,
                                                            OUT rtDemo       text,
                                                            OUT rtMsgerro    text,
                                                            OUT rbErro       boolean,
                                                            OUT riCoderro    integer,
                                                            OUT rtErro       text ) returns record as
$$
declare

    iMatricula       alias for $1;
    iIdbql           alias for $2;
    iAnousu          alias for $3;
    nFracao          alias for $4;
    nAreal           alias for $5;
    lDemonstrativo   alias for $6;
    lRaise           alias for $7;

    rnArealote       numeric default 0;
    nAreaLoteIsento  numeric default 0;
    nAreaRealLote    numeric default 0;
    rnAreaCorrigida  numeric default 0;
    rnVm2terreno     numeric default 0;

    nFatorGleba         numeric default 0;
    nFatorProfundidade  numeric default 0;
    nFatorTopografia    numeric default 0;
    nFatorSitQuadra     numeric default 0;
    nFatorSitLote       numeric default 0;
    nFracaoIdealTerreno numeric default 0;
    nFatorPedologia     numeric default 0;

    lErro            boolean default false;
    iCodErro         integer;
    tRetorno         text default '';

begin

    rnVvt        := 0;
    rnAreaTotalC := 0;
    rnArea       := 0;
    rnTestada    := 0;
    rtDemo       := '';
    rtMsgerro    := '';
    rbErro       := 'f';
    riCoderro    := 0;
    rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> * INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    rnArealote := nAreal;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Area real do lote: ' || rnArealote,lRaise);

    rnAreaCorrigida := ( rnArealote * ( nFracao / 100 ) );
    rnAreaCorrigida := round(rnAreaCorrigida, 2);

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Area corrigida: ' || rnAreaCorrigida,lRaise);

    select COALESCE(j82_valorterreno, 0)
      into rnVm2terreno
      from lote
           inner join lotesetorfiscal on j91_idbql = j34_idbql
           inner join setorfiscal on j91_codigo = j90_codigo
           inner join setorfiscalvalor on j82_setorfiscal = j90_codigo
     where j34_idbql = iIdbql
       and j82_anousu = iAnousu;

    if rnVm2terreno = 0 or rnVm2terreno is null THEN

      rbErro    := true;
      riCodErro := 5;
      rtErro    := '. VERIFIQUE O SETOR FISCAL DO LOTE.';

      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Valor do M2 do terreno: ' || rnVm2terreno,lRaise);

    select rnFatorGleba, rlErro, riCodigoErro, rtTextoErro
      into nFatorGleba, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorgleba_taquari_2018( iIdbql, rnAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Gleba: ' || nFatorGleba,lRaise);

    select rnFatorProfundidade, rlErro, riCodigoErro, rtTextoErro
      into nFatorProfundidade, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorprofundidade_taquari_2018( iIdbql, rnAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Profundidade: ' || nFatorProfundidade, lRaise);

    select rnFatorIdealTerreno, rlErro, riCodigoErro, rtTextoErro
      into nFracaoIdealTerreno, lErro, iCodErro, tRetorno
      from fc_iptu_getfracaoidealterreno_taquari_2018( iIdbql, iMatricula, nAreal, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fracao Ideal do Terreno: ' || nFracaoIdealTerreno, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorTopografia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 96;

    if nFatorTopografia = 0 or nFatorTopografia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'TOPOGRAFIA (96)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Topografia: ' || nFatorTopografia, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorSitQuadra
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 46;

    if nFatorSitQuadra = 0 or nFatorSitQuadra is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'POSICIONAMENTO (46)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Situacao de Quadra: ' || nFatorSitQuadra, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorSitLote
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 100;

    if nFatorSitLote = 0 or nFatorSitLote is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'SITUACAO NO LOTE (100)';
      return;
    end if;

    select COALESCE(j74_fator, 0)
      into nFatorPedologia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 97;

    if nFatorPedologia = 0 or nFatorPedologia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'PEDOLOGIA (97)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Situacao de Quadra: ' || nFatorSitLote, lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> : rnVm2terreno' || rnVm2terreno, lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> ############### FORMULA: ' || nFatorSitLote, lRaise);
    perform fc_debug('rnAreaCorrigida * rnVm2terreno * nFracaoIdealTerreno * (nFatorProfundidade * nFatorTopografia * nFatorSitQuadra * nFatorSitLote * nFatorGleba)', lRaise);

    rnVvt := rnAreaCorrigida * rnVm2terreno * nFracaoIdealTerreno * (nFatorProfundidade * nFatorTopografia * nFatorPedologia * nFatorSitQuadra * nFatorSitLote * nFatorGleba);

    perform fc_debug('Valor venal calculado: ' || rnVvt, lRaise);

    update tmpdadosiptu
       set vvt   = rnVvt,
           vm2t  = rnVm2terreno,
           areat = rnAreaCorrigida;

    perform fc_debug('' || lpad('',60,'-'), lRaise);

    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sFuncao);
    }

    public function down()
    {
        $sFuncao = <<<FUNCAO
create or replace function fc_iptu_calculavvt_taquari_2018( integer, integer, integer, numeric, numeric, boolean, boolean,
                                                            OUT rnVvt        numeric(15,2),
                                                            OUT rnAreaTotalC numeric,
                                                            OUT rnArea       numeric,
                                                            OUT rnTestada    numeric,
                                                            OUT rtDemo       text,
                                                            OUT rtMsgerro    text,
                                                            OUT rbErro       boolean,
                                                            OUT riCoderro    integer,
                                                            OUT rtErro       text ) returns record as
$$
declare

    iMatricula       alias for $1;
    iIdbql           alias for $2;
    iAnousu          alias for $3;
    nFracao          alias for $4;
    nAreal           alias for $5;
    lDemonstrativo   alias for $6;
    lRaise           alias for $7;

    rnArealote       numeric default 0;
    nAreaLoteIsento  numeric default 0;
    nAreaRealLote    numeric default 0;
    rnAreaCorrigida  numeric default 0;
    rnVm2terreno     numeric default 0;

    nFatorGleba         numeric default 0;
    nFatorProfundidade  numeric default 0;
    nFatorTopografia    numeric default 0;
    nFatorSitQuadra     numeric default 0;
    nFatorSitLote       numeric default 0;
    nFracaoIdealTerreno numeric default 0;
    nFatorPedologia     numeric default 0;

    lErro            boolean default false;
    iCodErro         integer;
    tRetorno         text default '';

begin

    rnVvt        := 0;
    rnAreaTotalC := 0;
    rnArea       := 0;
    rnTestada    := 0;
    rtDemo       := '';
    rtMsgerro    := '';
    rbErro       := 'f';
    riCoderro    := 0;
    rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> * INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    rnArealote := nAreal;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Area real do lote: ' || rnArealote,lRaise);

    rnAreaCorrigida := ( rnArealote * ( nFracao / 100 ) );
    rnAreaCorrigida := round(rnAreaCorrigida, 2);

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Area corrigida: ' || rnAreaCorrigida,lRaise);

--     select COALESCE(j81_valorterreno, 0)
--       into rnVm2terreno
--       from lote
--            inner join testada on j36_idbql = j34_idbql
--            inner join testpri on j49_idbql = j36_idbql
--                              and j49_face = j36_face
--            inner join facevalor on j49_face = j81_face
--      where j36_idbql = iIdbql
--        and j81_anousu = iAnousu;

    select COALESCE(j90_valor, 0)
      into rnVm2terreno
      from lote
           inner join lotesetorfiscal on j91_idbql = j34_idbql
           inner join setorfiscal on j91_codigo = j90_codigo
     where j34_idbql = iIdbql;

    if rnVm2terreno = 0 or rnVm2terreno is null THEN

      rbErro    := true;
      riCodErro := 5;
      rtErro    := '. VERIFIQUE O SETOR FISCAL DO LOTE.';

      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Valor do M2 do terreno: ' || rnVm2terreno,lRaise);

    select rnFatorGleba, rlErro, riCodigoErro, rtTextoErro
      into nFatorGleba, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorgleba_taquari_2018( iIdbql, rnAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Gleba: ' || nFatorGleba,lRaise);

    select rnFatorProfundidade, rlErro, riCodigoErro, rtTextoErro
      into nFatorProfundidade, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorprofundidade_taquari_2018( iIdbql, rnAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Profundidade: ' || nFatorProfundidade, lRaise);

    select rnFatorIdealTerreno, rlErro, riCodigoErro, rtTextoErro
      into nFracaoIdealTerreno, lErro, iCodErro, tRetorno
      from fc_iptu_getfracaoidealterreno_taquari_2018( iIdbql, iMatricula, nAreal, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fracao Ideal do Terreno: ' || nFracaoIdealTerreno, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorTopografia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 96;

    if nFatorTopografia = 0 or nFatorTopografia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'TOPOGRAFIA (96)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Topografia: ' || nFatorTopografia, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorSitQuadra
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 46;

    if nFatorSitQuadra = 0 or nFatorSitQuadra is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'POSICIONAMENTO (46)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Situacao de Quadra: ' || nFatorSitQuadra, lRaise);

    select COALESCE(j74_fator, 0)
      into nFatorSitLote
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 100;

    if nFatorSitLote = 0 or nFatorSitLote is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'SITUACAO NO LOTE (100)';
      return;
    end if;

    select COALESCE(j74_fator, 0)
      into nFatorPedologia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 97;

    if nFatorPedologia = 0 or nFatorPedologia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'PEDOLOGIA (97)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> Fator Situacao de Quadra: ' || nFatorSitLote, lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> : rnVm2terreno' || rnVm2terreno, lRaise);
    perform fc_debug('<fc_iptu_calculavvt_taquari_2018> ############### FORMULA: ' || nFatorSitLote, lRaise);
    perform fc_debug('rnAreaCorrigida * rnVm2terreno * nFracaoIdealTerreno * (nFatorProfundidade * nFatorTopografia * nFatorSitQuadra * nFatorSitLote * nFatorGleba)', lRaise);

    rnVvt := rnAreaCorrigida * rnVm2terreno * nFracaoIdealTerreno * (nFatorProfundidade * nFatorTopografia * nFatorPedologia * nFatorSitQuadra * nFatorSitLote * nFatorGleba);

    perform fc_debug('Valor venal calculado: ' || rnVvt, lRaise);

    update tmpdadosiptu
       set vvt   = rnVvt,
           vm2t  = rnVm2terreno,
           areat = rnAreaCorrigida;

    perform fc_debug('' || lpad('',60,'-'), lRaise);

    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sFuncao);
    }
}
