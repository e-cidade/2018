<?php

use Classes\PostgresMigration;

class M7773AddFcUltimoDiaUtil extends PostgresMigration
{

     public function up()
    {
        $sFcUltimoDiaUtil = <<<EOL
create or replace function fc_ultimo_dia_util(date) returns date as
$$
declare
  dDataBase   date;
begin
  dDataBase := $1;
  loop
    perform k13_data
       from calend
      where k13_data = dDataBase;
    if not found then
      exit;
    end if;
    dDataBase := dDataBase - 1;
  end loop;
  return dDataBase;
end;
$$ language plpgsql;

EOL;
        $this->execute($sFcUltimoDiaUtil);
    }

    public function down()
    {
        $this->execute('DROP FUNCTION IF EXISTS fc_ultimo_dia_util(date)');
    }
}
