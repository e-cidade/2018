<?php

use Classes\PostgresMigration;

class M8359CotaExamePrestadorMensal extends PostgresMigration
{
    /**
     * Função padrão do Phinx para fazer o UPGRADE da base
     */
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    /**
     * Função padrão do Phinx para fazer o DOWNGRADE da base
     */
    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    public function dicionarioUp()
    {
        $sQuery  = " insert into db_sysarquivo values (1010194, 'cotaprestadoraexamemensal', 'Cota de exame mensal por prestador.', 'age01', '2017-04-20', 'Cota de Prestadores', 0, 'f', 'f', 'f', 'f' ); ";
        $sQuery .= " insert into db_sysarqmod values (30,1010194); ";
        $sQuery .= " insert into db_syscampo values(1009265,'age01_sequencial','int4','Código sequencial da tabela cotaprestadoraexamemensal','0', 'Código ',10,'f','f','f',1,'text','Código '); ";
        $sQuery .= " insert into db_syscampo values(1009266,'age01_prestadorvinculos','int4','Código do prestador de vinculos ','0', 'Código do Prestador Vinculos',10,'f','f','f',1,'text','Código do Prestador Vinculos'); ";
        $sQuery .= " insert into db_syscampo values(1009267,'age01_ano','int4','Campo ano do exame ','0', 'Ano',4,'f','f','f',1,'text','Ano'); ";
        $sQuery .= " insert into db_syscampo values(1009268,'age01_mes','int4','Campo de mês do exame','0', 'Mês',2,'f','f','f',1,'text','Mês'); ";
        $sQuery .= " insert into db_syscampo values(1009269,'age01_quantidade','int4','Campo de quantidade de exames por prestadora','0', 'Quantidade',10,'f','f','f',1,'text','Quantidade'); ";
        $sQuery .= " delete from db_sysarqcamp where codarq = 1010194; ";
        $sQuery .= " insert into db_sysarqcamp values(1010194,1009265,1,0); ";
        $sQuery .= " insert into db_sysarqcamp values(1010194,1009269,2,0); ";
        $sQuery .= " insert into db_sysarqcamp values(1010194,1009268,3,0); ";
        $sQuery .= " insert into db_sysarqcamp values(1010194,1009267,4,0); ";
        $sQuery .= " insert into db_sysarqcamp values(1010194,1009266,5,0); ";
        $sQuery .= " delete from db_sysprikey where codarq = 1010194; ";
        $sQuery .= " delete from db_sysprikey where codarq = 1010194; ";
        $sQuery .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010194,1009265,1,1009265); ";
        $sQuery .= " delete from db_sysforkey where codarq = 1010194 and referen = 0; ";
        $sQuery .= " insert into db_sysforkey values(1010194,1009266,1,2374,0); ";
        $sQuery .= " insert into db_sysindices values(1008186,'cotaprestadoraexamemensal_sequencial_in',1010194,'1'); ";
        $sQuery .= " insert into db_syscadind values(1008186,1009265,1); ";
        $sQuery .= " insert into db_syssequencia values(1000660, 'cotaprestadoraexamemensal_age01_sequencial_seq', 1, 1, 9223372036854775807, 1, 1); ";
        $sQuery .= " update db_sysarqcamp set codsequencia = 1000660 where codarq = 1010194 and codcam = 1009265; ";

        $this->execute($sQuery);
    }

    public function dicionarioDown()
    {
        $sQuery  = " delete from db_syssequencia where codsequencia = 1000660; ";
        $sQuery .= " delete from db_syscadind where  codind = 1008186; ";
        $sQuery .= " delete from db_sysindices where  codind = 1008186; ";
        $sQuery .= " delete from db_sysforkey where codarq = 1010194; ";
        $sQuery .= " delete from db_sysprikey where codarq = 1010194; ";
        $sQuery .= " delete from db_sysarqcamp where codarq = 1010194; ";
        $sQuery .= " delete from db_syscampo where codcam in (1009265,1009266,1009267,1009268,1009269); ";
        $sQuery .= " delete from db_sysarqmod where codarq = 1010194; ";
        $sQuery .= " delete from db_sysarquivo where codarq = 1010194; ";

        $this->execute($sQuery);
    }

    public function estruturaUp()
    {

        $sQuery  = " CREATE SEQUENCE cotaprestadoraexamemensal_age01_sequencial_seq ";
        $sQuery .= " INCREMENT 1 ";
        $sQuery .= " MINVALUE 1 ";
        $sQuery .= " MAXVALUE 9223372036854775807 ";
        $sQuery .= " START 1 ";
        $sQuery .= " CACHE 1; ";

        $sQuery .= " CREATE TABLE cotaprestadoraexamemensal( ";
        $sQuery .= " age01_sequencial        int4 NOT NULL default 0, ";
        $sQuery .= " age01_quantidade        int4 NOT NULL default 0, ";
        $sQuery .= " age01_mes       int4 NOT NULL default 0, ";
        $sQuery .= " age01_ano       int4 NOT NULL default 0, ";
        $sQuery .= " age01_prestadorvinculos     int4 default 0, ";
        $sQuery .= " CONSTRAINT cotaprestadoraexamemensal_sequ_pk PRIMARY KEY (age01_sequencial)); ";

        $sQuery .= " ALTER TABLE cotaprestadoraexamemensal ";
        $sQuery .= " ADD CONSTRAINT cotaprestadoraexamemensal_prestadorvinculos_fk FOREIGN KEY (age01_prestadorvinculos) ";
        $sQuery .= " REFERENCES sau_prestadorvinculos; ";

        $sQuery .= " CREATE UNIQUE INDEX cotaprestadoraexamemensal_sequencial_in ON cotaprestadoraexamemensal(age01_sequencial); ";

        $this->execute($sQuery);
    }

    public function estruturaDown()
    {
        $sQuery  = " DROP SEQUENCE IF EXISTS cotaprestadoraexamemensal_age01_sequencial_seq; ";
        $sQuery .= " DROP TABLE IF EXISTS cotaprestadoraexamemensal CASCADE; ";

        $this->execute($sQuery);
    }
}
