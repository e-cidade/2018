<?php

use Classes\PostgresMigration;

class M9350CorrecaoLabelCamposIptucalhconf extends PostgresMigration
{
  public function up()
  {
    $this->execute("UPDATE db_syscampo SET descricao = 'Código', rotulo = 'Código', rotulorel = 'Código' WHERE codcam = 10761");
  }

  public function down()
  {
    $this->execute("UPDATE db_syscampo SET descricao = 'Codigo', rotulo = 'Codigo', rotulorel = 'Codigo' WHERE codcam = 10761");
  }
}
