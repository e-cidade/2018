<?php

use Classes\PostgresMigration;

class M9005ProcessoJuridicoInstituicao extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    protected function upDicionario()
    {
        $sUpDicionarioinsert  = " insert into db_syscampo values(1009352,'v62_instit','int4','Código da Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'); ";
        $sUpDicionarioinsert .= " insert into db_sysarqcamp values(2212,1009352,11,0); ";
        $sUpDicionarioinsert .= " insert into db_sysforkey values(2212,1009352,1,83,0);     ";

        $this->execute($sUpDicionarioinsert);
    }

    protected function upEstrutura()
    {
        $sUpEstrutura  = "ALTER TABLE procjur ADD v62_instit int4;";
        $sUpEstrutura .= "UPDATE procjur set v62_instit = codigo from db_config where prefeitura is true;";
        $sUpEstrutura .= "ALTER TABLE procjur ALTER COLUMN v62_instit set not null;";

        $this->execute($sUpEstrutura);
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    protected function downEstrutura()
    {
        $sDownEstrutura = "ALTER TABLE procjur DROP COLUMN v62_instit;";
        $this->execute($sDownEstrutura);
    }

    protected function downDicionario()
    {
        $sDownDicionario  = " delete from db_sysforkey  where codcam = 1009352; ";
        $sDownDicionario .= " delete from db_sysarqcamp where codcam = 1009352; ";
        $sDownDicionario .= " delete from db_syscampo   where codcam = 1009352; ";

        $this->execute($sDownDicionario);
    }
}
