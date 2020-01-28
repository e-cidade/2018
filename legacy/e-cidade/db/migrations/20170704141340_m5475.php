<?php

use Classes\PostgresMigration;

class M5475 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
update db_syscampo set descricao = 'Filiação 1', rotulo = 'Filiação 1', rotulorel = 'Filiação 1' where codcam = 1008900;
update db_syscampo set descricao = 'Filiação 2', rotulo = 'Filiação 2', rotulorel = 'Filiação 2' where codcam = 1008899;

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}