<?php

use Classes\PostgresMigration;

class M9339NomeCamposTabelaSetorFiscal extends PostgresMigration
{
  public function up()
  {

    $this->execute("update db_syscampo set descricao = 'Descri��o do Setor Fiscal', rotulo = 'Descri��o do Setor Fiscal' where codcam = 7867;");
    $this->execute("update db_syscampo set descricao = 'C�digo do Setor Fiscal', rotulo = 'C�digo do Setor Fiscal' where codcam = 7866;");

  }
  public function down()
  {

  }
}
