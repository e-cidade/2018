<?php

use Classes\PostgresMigration;

class M9704IptuAliquotaTaquari2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<ALIQUOTA
create or replace function fc_iptu_getaliquota_taquari_2018(integer, boolean) returns numeric as
$$
declare

  iMatricula              alias for $1;
  lRaise                  alias for $2;

  tSql                    text default '';
  nAliquota               numeric default 0;
  iQuantidadeConstr       integer default 0;

begin

  perform fc_debug( '<fc_iptu_getaliquota_taquari_2018> DEFININDO ALIQUOTA A APLICAR', lRaise);
  perform fc_debug( '<fc_iptu_getaliquota_taquari_2018> Matricula: ' || iMatricula, lRaise);

  /**
   * Predial     - a aliquota de 1%
   * Territorial - a aliquota de 2%
   */
  select count(*)
    into iQuantidadeConstr
    from iptubase
         inner join iptuconstr on j39_matric = j01_matric
   where j01_matric = iMatricula;

  nAliquota = 1;

  if iQuantidadeConstr = 0 then
    nAliquota = 2;
  end if;

  perform fc_debug('<fc_iptu_getaliquota_taquari_2018> Aliquota: ' || nAliquota || '%', lRaise);

  execute 'update tmpdadosiptu set aliq = ' || coalesce( nAliquota, 0 );

  return nAliquota;

end;
$$ language 'plpgsql';
ALIQUOTA;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getaliquota_taquari_2018 ( integer, boolean);");
    }
}
