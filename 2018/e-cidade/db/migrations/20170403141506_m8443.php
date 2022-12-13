<?php

use Classes\PostgresMigration;

class M8443 extends PostgresMigration
{

    public function up()
    {
       $this->execute("update causaafastamento set rh115_descricao = 'Rescisão do contrato de trabalho por falecimento do empregado' where rh115_sequencial = 8");
    }

    public function down()
    {
        $this->execute("update causaafastamento set rh115_descricao = 'Rescisão contratual a pedido do empregado' where rh115_sequencial = 8");
    }
}
