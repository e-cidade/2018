<?php

use Classes\PostgresMigration;

class M9807AlteracaoCadastroBancos extends PostgresMigration
{

    public function up()
    {

        $linha = $this->fetchRow("select conname from pg_catalog.pg_constraint where conname = 'cadban_codbco_fk'");

        if (!$linha) {

            $sql = "
                alter table cadban alter column  k15_codbco type integer using k15_codbco::integer;
    
                alter table cadban
                add constraint cadban_codbco_fk
                foreign key (k15_codbco)
                references bancos(codbco);
    
                alter table cadban alter column  k15_codbco type integer;
    
                update cadban  set k15_codbco = (select codbco from bancos where codbco = k15_codbco);
            ";
            $this->execute($sql);
        }
    }

    public function down()
    {

    }
}
