<?php

use Classes\PostgresMigration;

class M8867CredenciamentoLicitacao extends PostgresMigration
{

    public function up()
    {
      $this->execute("update configuracoes.db_itensmenu set libcliente = true where id_item = 10406");
      $this->execute("update cflicita set l03_pctipocompratribunal  = 54 where l03_pctipocompratribunal  = 1000");
      $this->execute("update pctipocompra set pc50_pctipocompratribunal = 54 where pc50_pctipocompratribunal = 1000");
    }

    public function down()
    {
      $this->execute("update configuracoes.db_itensmenu set libcliente = false where id_item = 10406");
      $this->execute("update cflicita set l03_pctipocompratribunal  = 1000 where l03_pctipocompratribunal  = 54");
      $this->execute("update pctipocompra set pc50_pctipocompratribunal = 1000 where pc50_pctipocompratribunal = 54");
    }
}