<?php

use Classes\PostgresMigration;

class M9632AlteracaoMenus extends PostgresMigration
{
    public function up()
    {
        $this->execute( <<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10466 ,'Preenchimento' ,'Preenchimento' ,'' ,'1' ,'1' ,'Preenchimento' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10466 ,492 ,10216 );
            delete from db_menu where id_item_filho in(10220,10426, 10244)  AND modulo = 10216;
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10220 ,1 ,10216 );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10426 ,2 ,10216 );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10244 ,3 ,10216 );
SQL
        );
    }

    public function down()
    {
        $this->execute( <<<SQL

            insert into db_menu
            values (32, 10426, 484, 10216),
                (32, 10220, 469, 10216),
                (32, 10244, 490, 10216);

            delete from db_menu where id_item_filho = 10466 AND modulo = 10216;
            delete from db_itensmenu where id_item = 10466;
SQL
    );
    }
}
