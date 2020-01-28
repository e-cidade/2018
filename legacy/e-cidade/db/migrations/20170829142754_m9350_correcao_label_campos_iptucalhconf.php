<?php

use Classes\PostgresMigration;

class M9350CorrecaoLabelCamposIptucalhconf extends PostgresMigration
{
  public function up()
  {
    $this->execute("UPDATE db_syscampo SET descricao = 'C�digo', rotulo = 'C�digo', rotulorel = 'C�digo' WHERE codcam = 10761");
  }

  public function down()
  {
    $this->execute("UPDATE db_syscampo SET descricao = 'Codigo', rotulo = 'Codigo', rotulorel = 'Codigo' WHERE codcam = 10761");
  }
}
