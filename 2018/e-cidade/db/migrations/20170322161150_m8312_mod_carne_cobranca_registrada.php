<?php

use Classes\PostgresMigration;

class M8312ModCarneCobrancaRegistrada extends PostgresMigration
{
    public function up()
    {
        $this->execute("select setval('cadmodcarne_k47_sequencial_seq', 100)");
        $this->execute("insert into cadmodcarne (k47_sequencial, k47_descr, k47_obs, k47_altura, k47_largura, k47_orientacao, k47_tipoconvenio) values (100, 'CARNE COBRANCA REGISTRADA', '', 0, 0, '', null)");
    }

    public function down()
    {
        $this->execute("delete from cadmodcarne where k47_sequencial = 100");
    }
}
