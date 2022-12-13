<?php

use Classes\PostgresMigration;

class M7769EmissaoContratoAgua extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_documentotemplatetipo values(55, 'Água - Contrato')");
        $this->execute("insert into db_documentotemplatepadrao values(58, 55, 'Contrato', 'documentos/templates/agua/contrato.docx')");
    }

    public function down()
    {
        $this->execute("delete from db_documentotemplatepadrao where db81_templatetipo = 55");
        $this->execute("delete from db_documentotemplatetipo where db80_sequencial = 55");
    }
}
