<?php

use Classes\PostgresMigration;

class M5935 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
        select fc_executa_ddl('insert into db_layoutcampos values (13211, 261, \'consumo_saldo\', \'SALDO DE CONSUMO\', 1, 2889, \'\', 8, false, true, \'e\', \'\', 0 )');
        select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10229 ,\'Relatório de Créditos\' ,\'Relatório de Créditos\' ,\'cai2_relcredito001.php\' ,\'1\' ,\'1\' ,\'Relatório de Créditos\' ,\'true\' )');
        select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10229 ,451 ,1985522 )');  

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
