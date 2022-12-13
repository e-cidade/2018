<?php

use Classes\PostgresMigration;

class M7809 extends PostgresMigration
{
    public function up()
    {
        $this->execute("alter table retornocobrancaregistrada add column k168_codret integer;");
        $this->execute("alter table retornocobrancaregistrada add foreign key (k168_codret) references disarq (codret);");
        $this->execute("insert into db_syscampo values(22322, 'k168_codret', 'int4', 'Campo que liga com a tabela disarq.', '0', 'Arquivo bancário', 10, 'f', 'f', 'f', 1, 'text', 'Arquivo bancário');");
        $this->execute("insert into db_sysarqcamp values(3998, 22322, 3, 0);");
        $this->execute("insert into db_sysforkey values(3998, 22322, 1, 213, 0);");
    }

    public function down()
    {
        $this->execute("alter table retornocobrancaregistrada drop column k168_codret;");
        $this->execute("delete from db_sysarqcamp where codarq = 3998 and codcam = 22322;");
        $this->execute("delete from db_sysforkey where codarq = 3998 and codcam = 22322;");
        $this->execute("delete from db_syscampo where codcam = 22322;");
    }
}
