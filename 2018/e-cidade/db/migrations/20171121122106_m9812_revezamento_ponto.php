<?php

use Classes\PostgresMigration;

class M9812RevezamentoPonto extends PostgresMigration
{
    public function up()
    {
        $sql = "insert into db_syscampo values(1009521,'rh190_revezamento','bool','Determina se a escala é de revezamento.','f', 'Revezamento',1,'t','f','f',5,'text','Revezamento');
                insert into db_syscampodef values(1009521,'f','');
                insert into db_sysarqcamp values(4007,1009521,4,0);";
        $this->execute($sql);
        $this->adicionaCampoTabela();
    }

    public function down()
    {
        $sql = "
            delete from db_sysarqcamp where codarq = 4007;
            insert into db_sysarqcamp values(4007,22237,1,1000634);
            insert into db_sysarqcamp values(4007,22238,2,0);
            insert into db_sysarqcamp values(4007,22239,3,0);
            delete from db_syscampodef where codcam = 1009521;
            delete from db_syscampo where codcam = 1009521;
        "; 
        $this->execute($sql);
        $this->removeCampoTabela();
    }

    public function adicionaCampoTabela()
    {
        $sql = "alter table gradeshorarios add column rh190_revezamento bool default 'f';";
        $this->execute($sql);
    }

    public function removeCampoTabela()
    {
        $sql = "alter table gradeshorarios drop column rh190_revezamento;";
        $this->execute($sql);
    }
}
