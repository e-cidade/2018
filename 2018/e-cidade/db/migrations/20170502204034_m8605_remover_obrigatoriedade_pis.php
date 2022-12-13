<?php

use Classes\PostgresMigration;

class M8605RemoverObrigatoriedadePis extends PostgresMigration
{
    
    public function up()
    {
        $this->execute("alter table pontoeletronicoarquivodata alter column rh197_pis drop not null");
    }

    public function down()
    {
        
    }
}
