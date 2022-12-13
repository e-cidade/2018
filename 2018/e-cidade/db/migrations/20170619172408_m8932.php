<?php

use Classes\PostgresMigration;

class M8932 extends PostgresMigration
{

    public function up()
    {
        $this->execute("UPDATE grupocaracteristica SET db139_descricao  = 'EXIGIBILIDADE' WHERE db139_sequencial = 5;");
    }

    public function down()
    {
        $this->execute("UPDATE grupocaracteristica SET db139_descricao  = 'EXEGIBILIDADE' WHERE db139_sequencial = 5;");
    }

}
