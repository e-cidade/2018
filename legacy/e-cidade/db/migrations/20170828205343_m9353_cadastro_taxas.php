<?php

use Classes\PostgresMigration;

class M9353CadastroTaxas extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_syscampo set descricao = 'C�digo da Taxa', rotulo = 'C�digo da Taxa', rotulorel = 'C�digo da Taxa' where codcam = 9487;
                        update db_syscampo set descricao = 'C�digo da Taxa', rotulo = 'C�digo da Taxa', rotulorel = 'C�digo da Taxa' where codcam = 9490;
                        update db_syscampo set descricao = 'Hist�rico do iptucalh', rotulo = 'Hist�rico', rotulorel = 'Hist�rico' where codcam = 9519;
                        update db_syscampo set descricao = 'Al�quota por taxa', rotulo = 'Al�quota', rotulorel = 'Al�quota' where codcam = 9517;");
    }

    public function down()
    {

    }
}
