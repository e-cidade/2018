<?php

use Classes\PostgresMigration;

class M7960AcertoVersao extends PostgresMigration
{
  public function up()
  {
    $this->execute("select setval('configuracoes.db_versao_db30_codver_seq', 500);");

  }

  public function down()
  {
    // não existe down, pois é somente uma sequence e não faz diferença alterá-la
  }

}
