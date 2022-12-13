<?php

use Classes\PostgresMigration;

class M5457 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
create temporary table upx_db_itensmenu as select * from db_itensmenu limit 0;
insert into upx_db_itensmenu values ( 10330 ,'Linha' ,'Linha' ,'tre2_linha001.php' ,'1' ,'1' ,'Imprime os dados da linha.
Itinerário, ponto de paradas...' ,'true' );

insert into db_itensmenu
select * from upx_db_itensmenu
 where not exists ( select 1 from db_itensmenu where db_itensmenu.id_item = upx_db_itensmenu.id_item);


create temporary table upx_db_menu      as select * from db_menu limit 0;
insert into upx_db_menu values ( 30 ,10330 ,458 ,7147 );

insert into db_menu
  select * from upx_db_menu
   where not exists(select 1 from db_menu where upx_db_menu.id_item = db_menu.id_item and upx_db_menu.id_item_filho = db_menu.id_item_filho);

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}