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
    // n�o existe down, pois � somente uma sequence e n�o faz diferen�a alter�-la
  }

}
