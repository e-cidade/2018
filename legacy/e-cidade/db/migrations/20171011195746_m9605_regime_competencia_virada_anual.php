<?php

use Classes\PostgresMigration;

class M9605RegimeCompetenciaViradaAnual extends PostgresMigration
{
    public function up()
    {
        $this->criaDocumentos();
        $this->criaVinculosContabeis();
    }

    public function down()
    {
        $this->excluiVinculoContabil();
        $this->excluirDocumentos();
    }

    public function criaDocumentos()
    {
        $this->execute("insert into conhistdoc values (4010, 'COMPETENCIA ENCERRAMENTO', 4000)");
        $this->execute("insert into conhistdoc values (333,'LIQUIDAÇÂO RP COMPETÊNCIA', 20)");
        $this->execute("insert into conhistdoc values (334,'ANULAÇÂO LIQUIDAÇÂO RP COMPETÊNCIA', 21)");
    }

    public function excluirDocumentos()
    {
        $this->execute("DELETE FROM conhistdoc where c53_coddoc in (4010,333,334)");
    }

    public function criaVinculosContabeis()
    {

        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 4010, null)");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 333, 334)");
    }

    public function excluiVinculoContabil()
    {
        $this->execute("delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (4010,333)");
    }
}
