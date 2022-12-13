<?php

use Classes\PostgresMigration;

class M9353CadastroTaxas extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_syscampo set descricao = 'Código da Taxa', rotulo = 'Código da Taxa', rotulorel = 'Código da Taxa' where codcam = 9487;
                        update db_syscampo set descricao = 'Código da Taxa', rotulo = 'Código da Taxa', rotulorel = 'Código da Taxa' where codcam = 9490;
                        update db_syscampo set descricao = 'Histórico do iptucalh', rotulo = 'Histórico', rotulorel = 'Histórico' where codcam = 9519;
                        update db_syscampo set descricao = 'Alíquota por taxa', rotulo = 'Alíquota', rotulorel = 'Alíquota' where codcam = 9517;");
    }

    public function down()
    {

    }
}
