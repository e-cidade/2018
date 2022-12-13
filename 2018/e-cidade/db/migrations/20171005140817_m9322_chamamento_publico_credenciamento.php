<?php

use Classes\PostgresMigration;

class M9322ChamamentoPublicoCredenciamento extends PostgresMigration
{
    public function up()
    {
        $this->execute("UPDATE cflicita SET l03_pctipocompratribunal      = 54 WHERE l03_pctipocompratribunal  = 1000;");
        $this->execute("UPDATE pctipocompra SET pc50_pctipocompratribunal = 54 WHERE pc50_pctipocompratribunal = 1000;");
        $this->execute("DELETE FROM pctipocompratribunal WHERE l44_sequencial = 1000;");
    }

    public function down()
    {
        $this->execute("
            INSERT INTO pctipocompratribunal (SELECT 1000, l44_codigotribunal, l44_descricao, l44_uf, l44_sigla
                                                FROM pctipocompratribunal WHERE l44_sequencial = 54);
        ");
        $this->execute("UPDATE cflicita     SET l03_pctipocompratribunal  = 1000 WHERE l03_pctipocompratribunal  = 54;");
        $this->execute("UPDATE pctipocompra SET pc50_pctipocompratribunal = 1000 WHERE pc50_pctipocompratribunal = 54;");
    }
}
