<?php

use Classes\PostgresMigration;

class M9894MomenclaturaMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set descricao = 'Emiss�o Geral de IPTU Cobran�a' where id_item = 10336;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set descricao = 'Emiss�o Geral de IPTU' where id_item = 10336;");
    }
}
