<?php

use Classes\PostgresMigration;

class M9704IptuGlebaTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<GLEBA
create or replace function fc_iptu_getfatorgleba_taquari_2018(integer, numeric, boolean,
                                                              OUT rnFatorGleba numeric,
                                                              OUT rlErro       boolean,
                                                              OUT riCodigoErro integer,
                                                              OUT rtTextoErro  text ) returns record as
$$
declare

  iIdbql               alias for $1;
  nAreaLote            alias for $2;
  lRaise               alias for $3;

  nTestada                 numeric;
  nProfundidadeEquivalente numeric;
  iZona                    INTEGER;

begin

  perform fc_debug('<fc_iptu_getfatorgleba_taquari_2018> INICIANDO CALCULO DE FATOR DA GLEBA'::text, lRaise);

  rnFatorGleba := 0;
  rlErro       := false;
  riCodigoErro := 0;
  rtTextoErro  := '';

  if nAreaLote <= 2500 THEN
    rnFatorGleba := 1;
    return;
  end if;

  select case
           when j36_testle > 0 then
             j36_testle
           else
             j36_testad
         end as testada,
         j34_zona
    into nTestada, iZona
    from lote
         inner join testada on j34_idbql = j36_idbql
         inner join testpri on j36_face = j49_face
                           and j36_idbql = j49_idbql
   where j36_idbql = iIdbql;

  if nTestada is null or nTestada = 0 THEN
    riCodigoErro := 6;
    rlErro       := true;

    return;
  end if;

  if iZona is null or iZona = 0 THEN
    riCodigoErro := 113;
    rlErro       := true;
    rtTextoErro  := '. VERIFIQUE A ZONA FISCAL DO LOTE';
    return;
  end if;

  if iZona = 1 or iZona = 2 or iZona = 6 THEN
    rnFatorGleba := 1;
    return;
  end if;

  perform fc_debug('<fc_iptu_getfatorgleba_taquari_2018> Testada Principal: '||nTestada, lRaise);

  nProfundidadeEquivalente := nAreaLote / nTestada;

  perform fc_debug('<fc_iptu_getfatorgleba_taquari_2018> Profundidade Equivalente: '||nProfundidadeEquivalente, lRaise);

  if nProfundidadeEquivalente <= 105 THEN
      rnFatorGleba := 1;
      return;
  end if;

  rnFatorGleba = 5 * nTestada ^ 0.2 * nAreaLote ^ -0.4;

  perform fc_debug('<fc_iptu_getfatorgleba_taquari_2018>Fator Gleba : ' || rnFatorGleba, lRaise);

  return;
end;
$$ language 'plpgsql';
GLEBA;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfatorgleba_taquari_2018 ( integer, numeric, boolean) ;");
    }
}
