<?php

use Classes\PostgresMigration;

class M8414AlteradoLayoutCategoriaConsumo extends PostgresMigration
{
  public function up() {

    $sUpdateSql = '
      update configuracoes.db_layoutcampos
      set db52_layoutformat = 1
      where db52_codigo in (15055, 15059, 15060, 15061, 15065);
    ';

    $this->execute($sUpdateSql);
  }

  public function down() {

    $sUpdateSql = '
      update configuracoes.db_layoutcampos
      set db52_layoutformat = 2
      where db52_codigo in (15055, 15059, 15060, 15061, 15065);
    ';

    $this->execute($sUpdateSql);
  }
}
