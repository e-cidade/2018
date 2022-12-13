<?php

use Classes\PostgresMigration;

class M6861 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
update db_itensmenu set descricao = 'Reprocessar Saldo de Contas', help = 'Reprocessa o saldo das contas correntes já existentes', funcao = 'con4_reprocessacontacorrente001.php', itemativo = '1', desctec = 'Reprocessa o saldo das contas correntes já existentes', libcliente = '1' where id_item = 9683;
delete from db_itensfilho where id_item = 9683;
insert into db_itensfilho select 9683, 1 where not exists(select 1 from db_itensfilho where (id_item, codfilho) =  (9683, 1));
insert into db_itensmenu select 10265, 'Processar', 'Cria novas contas correntes', 'con4_processarcontacorrente001.php', '1', '1', '', true where not exists (select 1 from db_itensmenu where id_item = 10265);
insert into db_itensfilho select 10265,1 where not exists (select 1 from db_itensfilho where (id_item, codfilho) = (10265, 1));
delete from db_menu where id_item_filho = 10265 AND modulo = 209;
insert into db_menu select 9680, 10265, 3, 209 where not exists (select 1 from db_menu where (id_item, id_item_filho, modulo) = (9680, 10265, 209));

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
