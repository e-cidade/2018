<?php

use Classes\PostgresMigration;

class M6444 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

delete from db_menu where id_item = 30 and modulo = 2323 and id_item_filho = 437509;    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
