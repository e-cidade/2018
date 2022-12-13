<?php

use Classes\PostgresMigration;

class M9422RelatorioRegin extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10458 ,'Integração com a Junta Comercial (REGIN)' ,'Integração com a Junta Comercial (REGIN)' ,'iss2_relatorioregin001.php' ,'1' ,'1' ,'Relatorio do Regin' ,'false' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10458 ,469 ,40 );
SQL
);
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 10458 AND modulo = 40;
delete from db_itensmenu where id_item = 10458;
SQL
);
    }
}
