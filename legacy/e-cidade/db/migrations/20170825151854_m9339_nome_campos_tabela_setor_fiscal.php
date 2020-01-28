<?php

use Classes\PostgresMigration;

class M9339NomeCamposTabelaSetorFiscal extends PostgresMigration
{
  public function up()
  {

    $this->execute("update db_syscampo set descricao = 'Descrição do Setor Fiscal', rotulo = 'Descrição do Setor Fiscal' where codcam = 7867;");
    $this->execute("update db_syscampo set descricao = 'Código do Setor Fiscal', rotulo = 'Código do Setor Fiscal' where codcam = 7866;");

  }
  public function down()
  {

  }
}
