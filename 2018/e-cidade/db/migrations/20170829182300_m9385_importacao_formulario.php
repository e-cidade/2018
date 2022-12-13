<?php

use Classes\PostgresMigration;

class M9385ImportacaoFormulario extends PostgresMigration
{

  public function up()
  {
    $this->execute( <<<SQL
      insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10442 ,'Importar' ,'Importar formulário' ,'con1_importarformulario001.php' ,'1' ,'1' ,'Importa arquivos yaml' ,'true' );
      insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8528 ,10442 ,5 ,1 );
      insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10443 ,'Exportar' ,'Exportar formulário' ,'con1_exportarformulario001.php' ,'1' ,'1' ,'Exporta um formulário em formato yaml' ,'true' );
      insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8528 ,10443 ,6 ,1 );
SQL
    );
  }

  public function down()
  {
    $this->execute( <<<SQL
      delete from db_menu where id_item_filho in ( 10442, 10443) AND modulo = 1;
      delete from db_itensmenu where id_item in( 10442, 10443);
SQL
    );
  }
}
