<?php

use Classes\PostgresMigration;

class M8642ManutencaoJustificativaEmLote extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10430 ,'Lançamento de Justificativas em Lote' ,'Lançamento de Justificativas em Lote' ,'rec4_manutencaojustificativaslote.php' ,'1' ,'1' ,'Inclui justificativas para vários servidores através de seleção ou matrículas.' ,'true' );");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,10430 ,5 ,2323 );");
    }

    public function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 10430 AND modulo = 2323;");
        $this->execute("delete from db_itensmenu where id_item = 10430;");
    }
}
