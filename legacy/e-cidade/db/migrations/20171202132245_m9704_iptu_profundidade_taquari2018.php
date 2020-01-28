<?php

use Classes\PostgresMigration;

class M9704IptuProfundidadeTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<PROFUNDIDADE
create or replace function fc_iptu_getfatorprofundidade_taquari_2018(integer, numeric, boolean,
                                                              OUT rnFatorProfundidade numeric,
                                                              OUT rlErro              boolean,
                                                              OUT riCodigoErro        integer,
                                                              OUT rtTextoErro         text ) returns record as
$$
declare

  iIdbql               alias for $1;
  nAreaLote            alias for $2;
  lRaise               alias for $3;

  nTestada                 numeric;
  nProfundidadeEquivalente numeric;

begin

  perform fc_debug('<fc_iptu_getfatorprofundidade_taquari_2018> INICIANDO CALCULO DE FATOR DA PROFUNDIDADE'::text, lRaise);

  rnFatorProfundidade := 0;
  rlErro       := false;
  riCodigoErro := 0;
  rtTextoErro  := '';

  if nAreaLote > 2500 THEN
    rnFatorProfundidade := 1;
    return;
  end if;

  select case
           when j36_testle > 0 then
             j36_testle
           else
             j36_testad
         end as testada
    into nTestada
    from testada
         inner join testpri on j36_face = j49_face
                           and j36_idbql = j49_idbql
   where j36_idbql = iIdbql;

  perform fc_debug('<fc_iptu_getfatorprofundidade_taquari_2018> Testada Principal: '||nTestada, lRaise);

  nProfundidadeEquivalente := nAreaLote / nTestada;

  perform fc_debug('<fc_iptu_getfatorprofundidade_taquari_2018> Profundidade Equivalente: '||nProfundidadeEquivalente, lRaise);

  if nProfundidadeEquivalente < 25 then
    rnFatorProfundidade := (nProfundidadeEquivalente / 25) ^ 0.5;
  end if;

  if nProfundidadeEquivalente >= 25 and nProfundidadeEquivalente <= 40 then
    rnFatorProfundidade := 1;
  end if;

  if nProfundidadeEquivalente > 40 and nProfundidadeEquivalente < 105 then
    rnFatorProfundidade := (40 / nProfundidadeEquivalente) ^ 0.5;
  end if;

  if nProfundidadeEquivalente >= 105 THEN
    rnFatorProfundidade := 0.6;
  end if;

  perform fc_debug('<fc_iptu_getfatorprofundidade_taquari_2018> Fator Profundidade: ' || rnFatorProfundidade, lRaise);

  return;
end;
$$ language 'plpgsql';
PROFUNDIDADE;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfatorprofundidade_taquari_2018 ( integer, numeric, boolean);");
    }
}
