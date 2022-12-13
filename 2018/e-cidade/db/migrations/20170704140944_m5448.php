<?php

use Classes\PostgresMigration;

class M5448 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

select fc_executa_ddl('
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 10304 ,\'Rotinas de Implantação\' ,\'Rotinas de Implantação\' ,\'Rotinas de Implantação\' ,\'1\' ,\'1\' ,\'Rotinas de implantação possui cadastros base para as escolas.\' ,\'true\' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
     values ( 3470 ,10304 ,39 ,7159 ),
            ( 10304 ,1100849 ,1 ,7159 ),
            ( 1100849 ,1100850 ,4 ,7159 ),
            ( 1100849 ,1100851 ,5 ,7159 ),
            ( 1100849 ,1100852 ,6 ,7159 );
');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}