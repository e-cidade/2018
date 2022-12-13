<?php

use Classes\PostgresMigration;

class M9756EnvioEsocialApi extends PostgresMigration
{

    public function up()
    {
        $this->execute("
            alter table avaliacaogrupopergunta add column db102_identificadorcampo varchar(255) default null;
            alter table avaliacaopergunta add column db103_identificadorcampo varchar(255) default null;

            insert into db_syscampo values(1009511,'db102_identificadorcampo','varchar(255)','Identificador campo','', 'Identificador Campo',255,'t','t','f',0,'text','Identificador Campo');
            insert into db_syscampo values(1009512,'db103_identificadorcampo','varchar(255)','Identificador Campo','', 'Identificador Campo',255,'t','t','f',0,'text','Identificador Campo');
            delete from db_sysarqcamp where codarq = 2983;
            insert into db_sysarqcamp values(2983,16915,1,1888);
            insert into db_sysarqcamp values(2983,17046,2,0);
            insert into db_sysarqcamp values(2983,16916,3,0);
            insert into db_sysarqcamp values(2983,16917,4,0);
            insert into db_sysarqcamp values(2983,19378,5,0);
            insert into db_sysarqcamp values(2983,16918,6,0);
            insert into db_sysarqcamp values(2983,16919,7,0);
            insert into db_sysarqcamp values(2983,17023,8,0);
            insert into db_sysarqcamp values(2983,21839,9,0);
            insert into db_sysarqcamp values(2983,21840,10,0);
            insert into db_sysarqcamp values(2983,1009307,11,0);
            insert into db_sysarqcamp values(2983,1009305,12,0);
            insert into db_sysarqcamp values(2983,1009304,13,0);
            insert into db_sysarqcamp values(2983,1009512,14,0);

            delete from db_sysarqcamp where codarq = 2981;
            insert into db_sysarqcamp values(2981,16912,1,1887);
            insert into db_sysarqcamp values(2981,16913,2,0);
            insert into db_sysarqcamp values(2981,16914,3,0);
            insert into db_sysarqcamp values(2981,19377,4,0);
            insert into db_sysarqcamp values(2981,1009511,5,0);
        ");
        $this->removePergunta();
    }

    public function down()
    {
        $this->execute("
            alter table avaliacaogrupopergunta drop column db102_identificadorcampo;
            alter table avaliacaopergunta drop column db103_identificadorcampo;

            delete from db_sysarqcamp where codarq = 2983 and codcam = 1009512;
            delete from db_sysarqcamp where codarq = 2981 and codcam = 1009511;
            delete from db_syscampo where codcam = 1009511;
            delete from db_syscampo where codcam = 1009512;

        ");
    }

    public function removePergunta()
    {
        $this->execute("
            delete from avaliacaogrupoperguntaresposta where db108_sequencial in ( SELECT db108_sequencial FROM avaliacaogrupoperguntaresposta WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial FROM avaliacaoresposta where db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial FROM avaliacaoperguntaopcao WHERE db104_avaliacaopergunta = 3000851)));
            delete from avaliacaoresposta where db106_sequencial in (SELECT db106_sequencial FROM avaliacaoresposta WHERE db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial FROM avaliacaoperguntaopcao WHERE db104_avaliacaopergunta = 3000851));
            delete from avaliacaoperguntaopcao where db104_sequencial in (SELECT db104_sequencial FROM avaliacaoperguntaopcao WHERE db104_avaliacaopergunta = 3000851);
            delete from avaliacaopergunta where db103_sequencial in (SELECT db103_sequencial FROM avaliacaopergunta WHERE db103_sequencial = 3000851);
        ");
    }
}
