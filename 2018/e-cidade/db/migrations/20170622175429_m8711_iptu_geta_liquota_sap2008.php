<?php

use Classes\PostgresMigration;

class M8711IptuGetaLiquotaSap2008 extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL

create or replace function fc_iptu_getaliquota_sap_2008(integer,integer,integer,boolean,boolean) returns numeric as
$$


declare

    iMatricula alias for $1;
    iIdbql     alias for $2;
    iNumcgm    alias for $3;
    bPredial   alias for $4;
    bRaise     alias for $5;

    rnAliq           numeric default 0;
    nAlipre          numeric default 0;
    nAliter          numeric default 0;
    iSetor           integer default 0;
    iCaract          integer default 0;
    iImoTerritoriais integer default 0;
    iNumcalculos     integer default 0;

begin
  /* EXECUTAR SOMENTE SE NAO TIVER ISENCAO */
  if bRaise then
      raise notice 'DEFININDO QUAL ALIQUOTA APLICAR ...';
      raise notice 'IPTU : %', case when bPredial is true then 'PREDIAL' else 'TERRITORIAL' end;
  end if;

  select j30_aliter, j30_alipre
      into nAliter,nAlipre
      from lote
           inner join setor on j34_setor = j30_codi
   where j34_idbql = iIdbql;

 -- criterios para escolha da aliquota
 if bPredial then -- predial
   rnAliq = nAlipre;
 else  -- territorial
   rnAliq = nAliter;
 end if;

 if bRaise then
   raise notice 'aliquota final : %',rnAliq;
 end if;
 execute 'update tmpdadosiptu set aliq = '||rnAliq;

 return rnAliq;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL
drop function fc_iptu_getaliquota_sap_2008(integer,integer,integer,boolean,boolean);
SQL;

        $this->execute($sSql);
    }

}
