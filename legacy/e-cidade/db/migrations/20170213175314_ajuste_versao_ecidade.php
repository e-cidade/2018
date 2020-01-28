<?php

use Classes\PostgresMigration;

class AjusteVersaoEcidade extends PostgresMigration
{
    public function up()
    {
      $this->execute("select setval('configuracoes.db_versao_db30_codver_seq', 600);");
    }

    public function down()
    {
    }
}
