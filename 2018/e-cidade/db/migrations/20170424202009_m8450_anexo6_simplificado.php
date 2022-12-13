<?php

use Classes\PostgresMigration;

class M8450Anexo6Simplificado extends PostgresMigration
{
  public function up()
  {
    $sql = "update db_itensmenu 
               set descricao = 'Anexo 6 - Dem. Simplificado do RGF - a partir de 2015',
                   help = 'Anexo 6 - Dem. Simplificado do RGF - a partir de 2015'
             where id_item = 10077";
    $this->execute($sql);
  }

  public function down()
  {

    $sql = "update db_itensmenu 
               set descricao = 'Anexo 6 - Dem. Simplificado do RGF - 2015',
                   help = 'Anexo 6 - Dem. Simplificado do RGF - 2015'
             where id_item = 10077";
    $this->execute($sql);
  }

}
