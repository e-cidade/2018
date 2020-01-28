<?php

use Classes\PostgresMigration;

class M5888 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
create or replace function fc_consultadescontounica(integer) returns integer as
$$
declare

  iNumpre      alias for $1;
  dtPaga       date;
  dtVencRecibo date;
  sSql         text    default '';
  iDesconto    integer default 0;
  rReciboUnica record;

begin

  select recibopaga.k00_dtoper
    into dtPaga
    from arrepaga
         left join arreidret on arreidret.k00_numpre = arrepaga.k00_numpre
                            and arreidret.k00_numpar = arrepaga.k00_numpar
         left join disbanco  on disbanco.idret       = arreidret.idret
         left join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
   where arrepaga.k00_numpre = iNumpre;

  for rReciboUnica in

  select *
    from recibounica
   where k00_numpre = iNumpre
   order by k00_dtvenc desc

  loop

    if dtPaga = rReciboUnica.k00_dtvenc  then
      iDesconto := rReciboUnica.k00_percdes;
    end if;

  end loop;

 return iDesconto;

end;
$$
language 'plpgsql'
        ";

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
create or replace function fc_consultadescontounica(integer) returns integer  as
$$
declare

  iNumpre      alias for $1;

  dtPaga       date;

  sSql         text    default '';

  iDesconto    integer default 0;

  rReciboUnica record;


begin

  select max( case when disbanco.dtpago is not null then disbanco.dtpago else arrepaga.k00_dtpaga end)
    into dtPaga
    from arrepaga
         left join arreidret on arreidret.k00_numpre = arrepaga.k00_numpre
                            and arreidret.k00_numpar = arrepaga.k00_numpar
         left join disbanco  on disbanco.idret       = arreidret.idret
   where arrepaga.k00_numpre = iNumpre;

  sSql := ' select *
              from recibounica
             where k00_numpre = '||iNumpre||'
             order by k00_dtvenc desc';


  for rReciboUnica in execute sSql loop

    if dtPaga <= rReciboUnica.k00_dtvenc  then
      iDesconto := rReciboUnica.k00_percdes;
    end if;

  end loop;

 return iDesconto;

end;
$$
language 'plpgsql'
        ";

        $this->execute($sSql);
    }
}
