<?php

use Classes\PostgresMigration;

class M7437 extends PostgresMigration
{
public function up(){
    $sql = <<<'SQL'

create temporary table upx_db_syscampo as select * from db_syscampo limit 0;
insert into upx_db_syscampo
values(22147,'ed40_desativado','bool','Se o procedimento de avaliação esta desativado.','f', 'Desativado',1,'f','f','f',5,'text','Desativado');

create temporary table upx_db_sysarqcamp as select * from db_sysarqcamp limit 0;
insert into upx_db_sysarqcamp values(1010074,22147,7,0);

insert into db_syscampo
select * from upx_db_syscampo
 where not exists ( select 1 from db_syscampo where db_syscampo.codcam = upx_db_syscampo.codcam);

insert into db_sysarqcamp
  select * from upx_db_sysarqcamp
   where not exists ( select 1 from db_sysarqcamp where db_sysarqcamp.codcam = upx_db_sysarqcamp.codcam);


select fc_executa_ddl('alter table procedimento add COLUMN ed40_desativado boolean default false;');

create temporary table upx_db_itensmenu as select * from db_itensmenu limit 0;
create temporary table upx_db_menu      as select * from db_menu limit 0;

insert into upx_db_itensmenu
values ( 10331 ,'Ativar / Desativar' ,'Ativar / Desativar Precedimento de avaliacao' ,'edu1_desativarprocedimento001.php' ,'1' ,'1' ,'Ativar / Desativar Precedimento de avaliacao' ,'true' );

insert into upx_db_menu
values ( 1100865 ,10331 ,4 ,1100747 );


insert into db_itensmenu
  select * from upx_db_itensmenu
   where not exists ( select 1 from db_itensmenu where db_itensmenu.id_item = upx_db_itensmenu.id_item);

insert into db_menu
  select * from upx_db_menu
   where not exists ( select 1 from db_menu
                       where db_menu.id_item       = upx_db_menu.id_item
                         and db_menu.id_item_filho = upx_db_menu.id_item_filho
                    );

SQL;
  
    $this->execute($sql);
  }

public function down(){}
}
