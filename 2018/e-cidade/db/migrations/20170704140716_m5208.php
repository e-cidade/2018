<?php

use Classes\PostgresMigration;

class M5208 extends PostgresMigration
{  

 public function up(){
    $sql = <<<'SQL'

    create temporary table w_up_5208_db_itensmenu as select * from db_itensmenu limit 0;
           insert into w_up_5208_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                values ( 10286 ,'Agenda Médica' ,'Agenda Médica' ,'sau2_agendamedica001.php' ,'1' ,'1' ,'Formulário para impressão da agenda médica de um profissional da saúde.' ,'true' );

insert into db_itensmenu
     select *
       from w_up_5208_db_itensmenu
      where not exists ( select 1
                           from db_itensmenu
                          where db_itensmenu.id_item = w_up_5208_db_itensmenu.id_item);

create temporary table w_up_5208_db_menu as select * from db_menu limit 0;
           insert into w_up_5208_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
               values ( 30 ,10286 ,456 ,6952 );

insert into db_menu
     select *
       from w_up_5208_db_menu
      where not exists ( select 1
                           from db_menu
                          where db_menu.id_item       = w_up_5208_db_menu.id_item
                            and db_menu.id_item_filho = w_up_5208_db_menu.id_item_filho);

SQL;
  
    $this->execute($sql);
  }

public function down(){}
    
}