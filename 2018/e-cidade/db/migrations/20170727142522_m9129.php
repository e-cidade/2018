<?php

use Classes\PostgresMigration;

class M9129 extends PostgresMigration
{
    public function up()
    {
        $this->execute("alter table issqn.issplanit alter column q21_nome type varchar(100);");
    }

    public function down()
    {
    }
}
