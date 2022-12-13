<?php

use Classes\PostgresMigration;

class M9835Token extends PostgresMigration
{
    public function up()
    {
        $sSql  = "ALTER TABLE db_sysregrasacessoip ADD COLUMN db48_tokenprivado VARCHAR(64);";
        $sSql .= "ALTER TABLE db_sysregrasacessoip ADD COLUMN db48_tokenpublico VARCHAR(64);";
        $sSql .= "create index histocorrencia_ar23_data_in on histocorrencia USING btree(ar23_data);";
        $this->execute($sSql);
        $this->upDicionario();
    }

    public function down()
    {
        $sSql  = "ALTER TABLE db_sysregrasacessoip DROP COLUMN db48_tokenprivado RESTRICT;";
        $sSql .= "ALTER TABLE db_sysregrasacessoip DROP COLUMN db48_tokenpublico RESTRICT;";
        $sSql .= "drop index histocorrencia_ar23_data_in;";
        $this->execute($sSql);
        $this->downDicionario();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1009550,'db48_tokenprivado','varchar(64)','Token privado para acesso externo ao e-cidade.','', 'Token privado',64,'t','f','f',0,'text','Token privado');
            insert into db_syscampo values(1009551,'db48_tokenpublico','varchar(64)','Token publico para acesso externo ao e-cidade.','', 'Token publico',64,'t','f','f',0,'text','Token publico');
            delete from db_sysarqcamp where codarq = 1774;
            insert into db_sysarqcamp values(1774,10270,1,0);
            insert into db_sysarqcamp values(1774,10271,2,0);
            insert into db_sysarqcamp values(1774,1009551,3,0);
            insert into db_sysarqcamp values(1774,1009550,4,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam in (1009551, 1009550);
            delete from db_syscampo where codcam in (1009551, 1009550);
SQL
        );
    }
}
