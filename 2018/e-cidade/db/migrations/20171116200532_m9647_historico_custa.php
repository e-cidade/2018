<?php

use Classes\PostgresMigration;

class M9647HistoricoCusta extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into histcalc (k01_codigo, k01_descr) values (11403, 'RECIBO CUSTAS')");
    }

    public function down()
    {
        $this->execute("delete from histcalc where k01_codigo = 11403");
    }
}
