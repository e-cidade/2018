<?php

use Classes\PostgresMigration;

class M5475 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
update db_syscampo set descricao = 'Filia��o 1', rotulo = 'Filia��o 1', rotulorel = 'Filia��o 1' where codcam = 1008900;
update db_syscampo set descricao = 'Filia��o 2', rotulo = 'Filia��o 2', rotulorel = 'Filia��o 2' where codcam = 1008899;

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}