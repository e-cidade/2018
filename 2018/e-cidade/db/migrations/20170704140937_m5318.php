<?php

use Classes\PostgresMigration;

class M5318 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

        select fc_executa_ddl('INSERT INTO db_menu VALUES (2458, 437509, 9, 952)');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
