<?php

use Classes\PostgresMigration;

class M8204 extends PostgresMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE assentamentofuncional DROP CONSTRAINT IF EXISTS assentamentofuncional_rh193_assentamento_efetividade_fk");
        $this->execute("
            INSERT INTO assentamentofuncional (SELECT distinct h16_codigo 
                                                 FROM recursoshumanos.assenta
                                                WHERE h16_codigo not in (SELECT distinct rh193_assentamento_funcional
                                                                           FROM assentamentofuncional
                                                                        )
                                                  AND not exists (SELECT * FROM w_bkp_assentamentofuncional_7315)
                                              )
        ");
    }
}
