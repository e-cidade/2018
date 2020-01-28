<?php

use Classes\PostgresMigration;

class M9140AlterarProcessoForo extends PostgresMigration
{
    public function up()
    {
        $this->execute("update processoforo set v70_cartorio = 1 where v70_cartorio not in (select v82_sequencial from cartorio);");
    }

    public function down()
    {
        // N�o temos como rodar o rollback neste caso, devido a n�o termos os c�digos de cart�rio dos registros alterados, pois os mesmos n�o existem na base.

    }
}
