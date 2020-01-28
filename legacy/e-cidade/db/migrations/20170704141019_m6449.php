<?php

use Classes\PostgresMigration;

class M6449 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
select fc_executa_ddl('INSERT INTO db_menu VALUES (30, 437509, 9, 2323)');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
