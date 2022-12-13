<?php

use Classes\PostgresMigration;

class M9621RelatorioRegimeCompetencia extends PostgresMigration
{

    public function up()
    {
        $this->execute( "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente)
                         values (10464 ,'Contratos sem Programação de Competência', 'Contratos sem Programação de Competência', 'con2_acordosemprogramacao001.php' ,'1' ,'1' ,'Relátorio que lista a programação' ,'true'); ");

        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (30, 10464, 470, 8251);" );
    }

    public function down()
    {
       $this->execute("delete from db_menu where id_item_filho = 10464 AND modulo = 8251;");
       $this->execute("delete from db_itensmenu where id_item = 10464;");
    }


}
