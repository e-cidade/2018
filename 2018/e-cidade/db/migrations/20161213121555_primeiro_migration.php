<?php

use Classes\PostgresMigration;


// Migration inicial para teste do phinx
class PrimeiroMigration extends PostgresMigration
{
    public function up()
    {
        $this->execute('create table w_migration_test ();');
    }

    public function down()
    {
        $this->execute('drop table w_migration_test;');
    }
}
