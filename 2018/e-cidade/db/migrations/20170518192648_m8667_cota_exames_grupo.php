<?php

use Classes\PostgresMigration;

class M8667CotaExamesGrupo extends PostgresMigration
{
    public function up()
    {
        $this->executarPreUp();
        $this->criarNovasTabelas();
        $this->migrarDados();
        $this->alterarEstruturaUp();
    }

    public function down()
    {
        $this->alterarEstruturaDown();
        $this->removerNovasTabelas();
        $this->executarPreDown();
    }

    public function executarPreUp()
    {
        $sSql  = "insert into db_sysarquivo values (1010195, 'grupoexame', 'Grupo de Exames para liberação de cotas', 'age02', '2017-05-18', 'Grupo de Exames', 0, 'f', 'f', 'f', 'f' );";
        $sSql .= "insert into db_sysarqmod values (30,1010195);";
        $sSql .= "insert into db_syscampo values(1009278,'age02_sequencial','int4','Código sequencial do Grupo de Exames','0', 'Grupo de Exames',10,'f','f','f',1,'text','Grupo de Exames');";
        $sSql .= "insert into db_syscampo values(1009279,'age02_cotaprestadoraexamemensal','int4','Cota do grupo de exames','0', 'Cota',10,'f','f','f',1,'text','Cota');";
        $sSql .= "insert into db_syscampo values(1009280,'age02_nome','varchar(60)','Nome do Grupo de exames','', 'Nome do Grupo',60,'f','f','f',0,'text','Nome do Grupo');";
        $sSql .= "delete from db_sysarqcamp where codarq = 1010195;";
        $sSql .= "insert into db_sysarqcamp values(1010195,1009278,1,0);";
        $sSql .= "insert into db_sysarqcamp values(1010195,1009279,2,0);";
        $sSql .= "insert into db_sysarqcamp values(1010195,1009280,3,0);";
        $sSql .= "delete from db_sysprikey where codarq = 1010195;";
        $sSql .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010195,1009278,1,1009278);";
        $sSql .= "delete from db_sysforkey where codarq = 1010195 and referen = 0;";
        $sSql .= "insert into db_sysforkey values(1010195,1009279,1,1010194,0);";
        $sSql .= "insert into db_syssequencia values(1000661, 'grupoexame_age02_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $sSql .= "update db_sysarqcamp set codsequencia = 1000661 where codarq = 1010195 and codcam = 1009278;";
        $sSql .= "insert into db_sysindices values(1008187,'grupoexame_cotaprestadoraexamemensal_in',1010195,'1');";
        $sSql .= "insert into db_syscadind values(1008187,1009279,1);";
        $sSql .= "insert into db_sysindices values(1008188,'grupoexame_sequencial_in',1010195,'1');";
        $sSql .= "insert into db_syscadind values(1008188,1009278,1);";
        $sSql .= "insert into db_sysarquivo values (1010196, 'grupoexameprestador', 'Grupo de Exames por Prestador', 'age03', '2017-05-18', 'Grupo de Exames por Prestador', 0, 'f', 'f', 'f', 'f' );";
        $sSql .= "insert into db_sysarqmod values (30,1010196);";
        $sSql .= "insert into db_syscampo values(1009281,'age03_sequencial','int4','Grupo de Exames por Prestador','0', 'Grupo de Exames por Prestador',10,'f','f','f',1,'text','Grupo de Exames por Prestador');";
        $sSql .= "insert into db_syscampo values(1009282,'age03_grupoexame','int4','Código sequencial do Grupo de Exames','0', 'Grupo de Exames',10,'f','f','f',1,'text','Grupo de Exames');";
        $sSql .= "insert into db_syscampo values(1009283,'age03_prestadorvinculos','int4','Exame por Prestador','0', 'Exame por Prestador',10,'f','f','f',1,'text','Exame por Prestador');";
        $sSql .= "delete from db_sysarqcamp where codarq = 1010196;";
        $sSql .= "insert into db_sysarqcamp values(1010196,1009281,1,0);";
        $sSql .= "insert into db_sysarqcamp values(1010196,1009282,2,0);";
        $sSql .= "insert into db_sysarqcamp values(1010196,1009283,3,0);";
        $sSql .= "delete from db_sysprikey where codarq = 1010196;";
        $sSql .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010196,1009281,1,1009281);";
        $sSql .= "delete from db_sysforkey where codarq = 1010196 and referen = 0;";
        $sSql .= "insert into db_sysforkey values(1010196,1009282,1,1010195,0);";
        $sSql .= "delete from db_sysforkey where codarq = 1010196 and referen = 0;";
        $sSql .= "insert into db_sysforkey values(1010196,1009283,1,2374,0);";
        $sSql .= "insert into db_sysindices values(1008189,'grupoexameprestador_grupoexame_prestadorvinculos_in',1010196,'1');";
        $sSql .= "insert into db_syscadind values(1008189,1009282,1);";
        $sSql .= "insert into db_syscadind values(1008189,1009283,2);";
        $sSql .= "insert into db_sysindices values(1008190,'grupoexameprestador_sequencial_in',1010196,'1');";
        $sSql .= "insert into db_syscadind values(1008190,1009281,1);";
        $sSql .= "insert into db_syssequencia values(1000662, 'grupoexameprestador_age03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $sSql .= "update db_sysarqcamp set codsequencia = 1000662 where codarq = 1010196 and codcam = 1009281;";
        $sSql .= "delete from db_sysforkey where codcam  = 1009266;";
        $sSql .= "delete from db_sysarqcamp where codcam = 1009266;";
        $sSql .= "delete from db_syscampo where codcam   = 1009266;";
        $sSql .= "insert into db_syscampo values(1009289,'age01_tipo','int4','Tipo de cota mensal: grupo ou individual','0', 'Tipo',5,'f','f','f',1,'text','Tipo');";
        $sSql .= "insert into db_sysarqcamp values(1010194,1009289,5,0);";

        $this->execute($sSql);
    }

    public function executarPreDown()
    {
        $sSql  = "delete from db_sysarqcamp where codarq in (1010195, 1010196);";
        $sSql .= "delete from db_sysprikey where codarq in (1010195, 1010196);";
        $sSql .= "delete from db_sysforkey where codarq in (1010195, 1010196);";
        $sSql .= "delete from db_syscadind where codind in (1008189, 1008190, 1008188, 1008187);";
        $sSql .= "delete from db_sysindices where codind in (1008189, 1008190, 1008188, 1008187);";
        $sSql .= "delete from db_syssequencia where codsequencia in (1000662, 1000661);";
        $sSql .= "delete from db_sysarqcamp where codcam in (1009289);";
        $sSql .= "delete from db_syscampo where codcam in (1009278, 1009279, 1009280,1009281, 1009282, 1009283, 1009289);";
        $sSql .= "delete from db_sysarqmod where codarq in (1010195, 1010196);";
        $sSql .= "delete from db_sysarqcamp where codcam in (1009289);";
        $sSql .= "delete from db_sysarquivo where codarq in (1010195, 1010196);";

        $sSql .= "insert into db_syscampo values(1009266,'age01_prestadorvinculos','int4','Código do prestador de vinculos ','0', 'Código do Prestador Vinculos',10,'f','f','f',1,'text','Código do Prestador Vinculos');";
        $sSql .= "insert into db_sysarqcamp values(1010194,1009266,5,0);";
        $sSql .= "insert into db_sysforkey values(1010194,1009266,1,2374,0);";

        $this->execute($sSql);
    }

    public function criarNovasTabelas()
    {
        $sSql  = " CREATE SEQUENCE agendamento.grupoexame_age02_sequencial_seq ";
        $sSql .= " INCREMENT 1 ";
        $sSql .= " MINVALUE 1 ";
        $sSql .= " MAXVALUE 9223372036854775807 ";
        $sSql .= " START 1 ";
        $sSql .= " CACHE 1; ";

        $sSql .= " CREATE TABLE agendamento.grupoexame( ";
        $sSql .= " age02_sequencial    int4 NOT NULL default 0, ";
        $sSql .= " age02_cotaprestadoraexamemensal   int4 NOT NULL default 0, ";
        $sSql .= " age02_nome    varchar(250) , ";
        $sSql .= " CONSTRAINT grupoexame_sequ_pk PRIMARY KEY (age02_sequencial)); ";

        $sSql .= " ALTER TABLE grupoexame ";
        $sSql .= " ADD CONSTRAINT grupoexame_cotaprestadoraexamemensal_fk FOREIGN KEY (age02_cotaprestadoraexamemensal) ";
        $sSql .= " REFERENCES cotaprestadoraexamemensal; ";

        $sSql .= "CREATE UNIQUE INDEX grupoexame_cotaprestadoraexamemensal_in ON grupoexame(age02_cotaprestadoraexamemensal);";

        $sSql .= "CREATE UNIQUE INDEX grupoexame_sequencial_in ON grupoexame(age02_sequencial);";


        $sSql .= " CREATE SEQUENCE agendamento.grupoexameprestador_age03_sequencial_seq ";
        $sSql .= " INCREMENT 1 ";
        $sSql .= " MINVALUE 1 ";
        $sSql .= " MAXVALUE 9223372036854775807 ";
        $sSql .= " START 1 ";
        $sSql .= " CACHE 1; ";

        $sSql .= " CREATE TABLE agendamento.grupoexameprestador( ";
        $sSql .= " age03_sequencial    int4 NOT NULL default 0, ";
        $sSql .= " age03_grupoexame    int4 NOT NULL default 0, ";
        $sSql .= " age03_prestadorvinculos   int4 default 0, ";
        $sSql .= " CONSTRAINT grupoexameprestador_sequ_pk PRIMARY KEY (age03_sequencial)); ";

        $sSql .= " ALTER TABLE grupoexameprestador ";
        $sSql .= " ADD CONSTRAINT grupoexameprestador_prestadorvinculos_fk FOREIGN KEY (age03_prestadorvinculos) ";
        $sSql .= " REFERENCES sau_prestadorvinculos; ";

        $sSql .= " ALTER TABLE grupoexameprestador ";
        $sSql .= " ADD CONSTRAINT grupoexameprestador_grupoexame_fk FOREIGN KEY (age03_grupoexame) ";
        $sSql .= " REFERENCES grupoexame; ";

        $sSql .= "CREATE UNIQUE INDEX grupoexameprestador_grupoexame_prestadorvinculos_in ON grupoexameprestador(age03_grupoexame,age03_prestadorvinculos);";

        $sSql .= "CREATE UNIQUE INDEX grupoexameprestador_sequencial_in ON grupoexameprestador(age03_sequencial);";


        $this->execute($sSql);
    }

    public function removerNovasTabelas()
    {
        $sSql  = "DROP TABLE IF EXISTS grupoexameprestador CASCADE;";
        $sSql .= "DROP SEQUENCE IF EXISTS grupoexameprestador_age03_sequencial_seq;";

        $sSql .= "DROP TABLE IF EXISTS grupoexame CASCADE;";
        $sSql .= "DROP SEQUENCE IF EXISTS grupoexame_age02_sequencial_seq;";

        $this->execute($sSql);
    }

    public function alterarEstruturaUp()
    {
        $sSql  = "alter table cotaprestadoraexamemensal drop constraint cotaprestadoraexamemensal_prestadorvinculos_fk;";
        $sSql .= "alter table cotaprestadoraexamemensal drop column age01_prestadorvinculos;";
        $sSql .= "alter table cotaprestadoraexamemensal add column age01_tipo integer default 0;";

        $sSql .= " ALTER TABLE cotaprestadoraexamemensal SET SCHEMA agendamento;";
        $sSql .= " ALTER SEQUENCE cotaprestadoraexamemensal_age01_sequencial_seq SET SCHEMA agendamento;";

        $this->execute($sSql);
    }

    public function alterarEstruturaDown()
    {
        $sSql  = " alter table cotaprestadoraexamemensal add column age01_prestadorvinculos integer;";
        $sSql .= " alter table cotaprestadoraexamemensal drop column age01_tipo;";

        $sSql .= " ALTER TABLE cotaprestadoraexamemensal SET SCHEMA public;";
        $sSql .= " ALTER SEQUENCE cotaprestadoraexamemensal_age01_sequencial_seq SET SCHEMA public;";

        $sSql .= " ALTER TABLE cotaprestadoraexamemensal ";
        $sSql .= " ADD CONSTRAINT cotaprestadoraexamemensal_prestadorvinculos_fk FOREIGN KEY (age01_prestadorvinculos) ";
        $sSql .= " REFERENCES sau_prestadorvinculos; ";

        $this->execute($sSql);
    }

    public function migrarDados()
    {
        $sSql  = "insert into grupoexame";
        $sSql .= "     select nextval('grupoexame_age02_sequencial_seq'),";
        $sSql .= "            age01_sequencial as sequencial_cotaprestadoraexamemensal,";
        $sSql .= "            sd63_c_nome as nome_procedimento";
        $sSql .= "       from cotaprestadoraexamemensal";
        $sSql .= "            inner join sau_prestadorvinculos on age01_prestadorvinculos = s111_i_codigo";
        $sSql .= "            inner join sau_procedimento on sd63_i_codigo = s111_procedimento;";

        $sSql .= "insert into grupoexameprestador";
        $sSql .= "     select nextval('grupoexameprestador_age03_sequencial_seq'),";
        $sSql .= "            age02_sequencial as sequencial_grupoexame,";
        $sSql .= "            age01_prestadorvinculos as prestadorvinculos";
        $sSql .= "       from cotaprestadoraexamemensal";
        $sSql .= "            inner join grupoexame ON age02_cotaprestadoraexamemensal = age01_sequencial";

        $this->execute($sSql);
    }
}
