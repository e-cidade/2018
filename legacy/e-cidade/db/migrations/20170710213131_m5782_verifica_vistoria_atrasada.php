<?php

use Classes\PostgresMigration;

class M5782VerificaVistoriaAtrasada extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL
create or replace function fc_verifica_vistoria_atrasada(integer, integer, integer) returns boolean
as $$
declare

  iCodSani alias for $1;
  iCodVist alias for $2;
  iAnousu  alias for $3;

  iCodigoVistoria    integer;
  iAnoUltimaVistoria integer;
  lReturn            boolean default false;

begin

  select y74_codvist
    into iCodigoVistoria
    from vistsanitario
         inner join vistorias on vistorias.y70_codvist = vistsanitario.y74_codvist
   where y74_codsani = iCodSani
     and y74_codvist != iCodVist
   order by y70_data desc
   limit 1;

  if iCodigoVistoria is not null then

    select extract(year from y70_data)
      into iAnoUltimaVistoria
      from vistorias
     where y70_codvist = iCodigoVistoria;

    if iAnoUltimaVistoria < (iAnousu - 1) then
      lReturn := true;
    end if;

  end if;

    return lReturn;

end;
$$ language 'plpgsql';
SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL
drop function fc_verifica_vistoria_atrasada(integer, integer, integer)
SQL;

        $this->execute($sSql);
    }
}
