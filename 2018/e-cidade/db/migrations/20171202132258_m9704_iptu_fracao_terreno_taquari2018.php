<?php

use Classes\PostgresMigration;

class M9704IptuFracaoTerrenoTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FRACAOTERRENO
create or replace function fc_iptu_getfracaoidealterreno_taquari_2018(integer, integer, numeric, boolean,
                                                                      OUT rnFatorIdealTerreno numeric,
                                                                      OUT rlErro       boolean,
                                                                      OUT riCodigoErro integer,
                                                                      OUT rtTextoErro  text ) returns record as
$$
declare

  iIdbql               alias for $1;
  iMatricula           alias for $2;
  nAreaTotalLote       alias for $3;
  lRaise               alias for $4;

  nAreaTotalConstruido numeric;
  nAreaConstruido      numeric;
  iTotalConstrucoes    integer;
  iCaracteristicaTipo  integer;

begin

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> INICIANDO CALCULO DA FRACAO IDEAL DO TERRENO'::text, lRaise);

  rnFatorIdealTerreno := 0;
  rlErro              := false;
  riCodigoErro        := 0;
  rtTextoErro         := '';
  iCaracteristicaTipo := 30466;

  select count(*)
    into iTotalConstrucoes
    from iptubase
         inner join iptuconstr on j39_matric = j01_matric
   where j01_matric = iMatricula
     and j39_dtdemo is null;

  if iTotalConstrucoes = 0 THEN
    rnFatorIdealTerreno := 1;
    return;
  end if;

  perform *
    from iptuconstr
         inner join carconstr on j39_matric = j48_matric
                             and j48_idcons = j39_idcons
         inner join caracter on j48_caract = j31_codigo
   where j48_matric = iMatricula
     and j31_codigo = iCaracteristicaTipo;

  if not FOUND then
    rnFatorIdealTerreno := 1;
    return;
  end if;

  if iTotalConstrucoes > 1 THEN
    rlErro       := true;
    riCodigoErro := 115;

    return;
  end if;

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> Caracteristica do grupo 20(tipo): '||iCaracteristicaTipo, lRaise);

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> Calcular fracao idela do terreno', lRaise);

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> Area total do lote: '||nAreaTotalLote, lRaise);

  select sum(COALESCE(j39_area, 0))
    into nAreaTotalConstruido
    from iptubase
         inner join iptuconstr on j39_matric = j01_matric
   where j01_idbql = iIdbql;

  if nAreaTotalConstruido = 0 then
    rlErro       := true;
    riCodigoErro := 112;
    rtTextoErro  := 'PARA AS CONSTRUCOES NO LOTE';

    return;
  end if;

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> Area total construido no lote: '||nAreaTotalConstruido, lRaise);

  select COALESCE(j39_area, 0)
    into nAreaConstruido
    from iptubase
         INNER JOIN iptuconstr on j39_matric = j01_matric
   where j01_matric = iMatricula
     and j39_idprinc is true;

  if nAreaConstruido = 0 then
    rlErro       := true;
    riCodigoErro := 112;

    return;
  end if;

  perform fc_debug('<fc_iptu_getfracaoidealterreno_taquari_2018> Area da construcao: '||nAreaConstruido, lRaise);

  rnFatorIdealTerreno := nAreaTotalLote * (nAreaConstruido / nAreaTotalConstruido);
  rnFatorIdealTerreno := rnFatorIdealTerreno / 100;

  return;
end;
$$ language 'plpgsql';
FRACAOTERRENO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfracaoidealterreno_taquari_2018 ( integer, integer, numeric, boolean) ;");
    }
}
