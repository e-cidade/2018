<?php

use Classes\PostgresMigration;

class M9704IptuM2EdificTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<M2EDIFICACAO
create or replace function fc_iptu_getvaloredificacao_taquari_2018(iMatricula    integer,
                                                                   iIdContrucao  integer,
                                                                   iAnousu       integer,
                                                                   lRaise        boolean,

                                                                   OUT rnValorM2Edificacao numeric,
                                                                   OUT rlErro              boolean,
                                                                   OUT riCodErro           integer,
                                                                   OUT rtErro              text) returns record as
$$
declare

  iMatricula            alias for $1;
  iIdContrucao          alias for $2;
  iAnousu               alias for $3;
  lRaise                alias for $4;

  iCaracteristicaTipo   integer default 0;
  iCaracteristicaPadrao integer default 0;

begin

  rnValorM2Edificacao := 0;
  rlErro              := false;
  riCodErro           := 0;
  rtErro              := '';

  select j48_caract
    into iCaracteristicaTipo
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 20
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristicaTipo is null then

    rlErro    := true;
    riCodErro := 104;
    rtErro    := '20 - TIPO UNIDADE';
    return;
  end if;

  perform fc_debug('iCaracteristicaTipo: ' || iCaracteristicaTipo, lRaise);

  select j48_caract
    into iCaracteristicaPadrao
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 21
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristicaPadrao is null then

    rlErro    := true;
    riCodErro := 104;
    rtErro    := '21 - PADRAO UNIDADE';
    return;
  end if;

  perform fc_debug('iCaracteristicaPadrao: ' || iCaracteristicaPadrao, lRaise);

  select j140_valor
    into rnValorM2Edificacao
    from agrupamentocaracteristicavalor
   where j140_sequencial in ( select grupoTipo.j139_agrupamentocaracteristicavalor
                                from agrupamentocaracteristica grupoTipo
                               where grupoTipo.j139_caracter = iCaracteristicaTipo
                                 and  exists ( select 1
                                                 from agrupamentocaracteristica grupoPadrao
                                                where grupoPadrao.j139_caracter                     = iCaracteristicaPadrao
                                                  and grupoPadrao.j139_anousu                       = iAnousu
                                                  and grupoTipo.j139_agrupamentocaracteristicavalor = grupoPadrao.j139_agrupamentocaracteristicavalor ) );

  perform fc_debug(' <fc_iptu_getvaloredificacao_taquari_2018> Buscando valor metro quadrado construcao:', lRaise);
  perform fc_debug(' <fc_iptu_getvaloredificacao_taquari_2018> iMatricula      : ' || iMatricula         , lRaise);
  perform fc_debug(' <fc_iptu_getvaloredificacao_taquari_2018> iIdContrucao    : ' || iIdContrucao       , lRaise);
  perform fc_debug(' <fc_iptu_getvaloredificacao_taquari_2018> Anousu          : ' || iAnousu            , lRaise);
  perform fc_debug(' <fc_iptu_getvaloredificacao_taquari_2018> Valor Retornado : ' || rnValorM2Edificacao, lRaise);
  perform fc_debug('', lRaise);

  if rnValorM2Edificacao is null then

    rlErro    := true;
    riCodErro := 110;
    rtErro    := iAnousu;

    return;
  end if;

end;
$$  language 'plpgsql';
M2EDIFICACAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getvaloredificacao_taquari_2018 ( integer, integer, integer, boolean) ;");
    }
}
