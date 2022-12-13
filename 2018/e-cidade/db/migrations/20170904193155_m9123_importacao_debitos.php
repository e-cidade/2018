<?php

use Classes\PostgresMigration;

class M9123ImportacaoDebitos extends PostgresMigration
{
  public function up(){

    $dbitensMenu = "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )";
    $dbitensMenu .= "                 values ( 10445 ,'Importação de Débitos para Cobrança Administrativa' ,'Importação de Débitos para Cobrança Administrativa' ,'' ,'1' ,'1' ,'Menu para importação de débitos, parcial ou geral' ,'false' ),";
    $dbitensMenu .= "                        ( 10446 ,'Parcial' ,'parcial' ,'arr4_importacaodebitoscobrancaadministrativaparcial001.php' ,'1' ,'1' ,'Rotina para importação parcial dos débitos do exercício.' ,'false' ),";
    $dbitensMenu .= "                        ( 10447 ,'Geral' ,'Geral' ,'arr4_importacaodebitoscobrancaadministrativageral001.php' ,'1' ,'1' ,'Rotina para importação geral dos débitos do exercício.' ,'false' )";

    $dbMenu  = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )";
    $dbMenu .= "             values ( 32 ,10445 ,488 ,1985522 ),";
    $dbMenu .= "                    ( 10445 ,10446 ,1 ,1985522 ),";
    $dbMenu .= "                    ( 10445 ,10447 ,2 ,1985522 )";

    $this->execute($dbitensMenu);
    $this->execute($dbMenu);


    $row = $this->fetchRow("select db21_codcli from db_config limit 1");
    if ((int)$row['db21_codcli'] === 7107) {
      $this->execute(
        <<<SQL
update db_itensmenu set libcliente = false where id_item in (9422);
update db_itensmenu set libcliente = true where id_item in (10445,10446,10447);
SQL
      );
    }

  }

  public function down() {

    $this->execute("delete from db_menu where id_item_filho = 10447 AND modulo = 1985522");
    $this->execute("delete from db_menu where id_item_filho = 10446 AND modulo = 1985522");
    $this->execute("delete from db_menu where id_item_filho = 10445 AND modulo = 1985522");
    $this->execute("delete from db_itensmenu where id_item in(10445, 10446, 10447)");

    $row = $this->fetchRow("select db21_codcli from db_config limit 1");
    if ((int)$row['db21_codcli'] === 7107) {
      $this->execute(
        <<<SQL
update db_itensmenu set libcliente = true where id_item in (9422);
update db_itensmenu set libcliente = false where id_item in (10445,10446,10447);
SQL
      );
    }
  }
}
