<?php

use Classes\PostgresMigration;

class M7304WebserviceCobrancaRegistrada extends PostgresMigration
{
    /**
     * Fazemos as alterações necessárias no banco de dados
     */
    public function up()
    {
        $this->upDicionarioMenu();
        $this->upDicionarioTabela();
        $this->upEstrutura();
    }

    /**
     * Desfazemos as alterações acima para que o banco de dados volte a ser o que era
     */
    public function down()
    {
        $this->downDicionarioMenu();
        $this->downDicionarioTabela();
        $this->downEstrutura();
    }

    private function upDicionarioMenu()
    {
        $sQuery  = " insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10428 ,'Cobrança Registrada' ,'Parâmetros de Configuração para a Cobrança Registrada' ,'arr4_parametroscobrancaregistrada001.php' ,'1' ,'1' ,'Parâmetros de Configuração para a Cobrança Registrada' ,'true' ); ";
        $sQuery .= " insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5383 ,10428 ,7 ,1985522 ); ";

        $this->execute($sQuery);
    }

    private function downDicionarioMenu()
    {
        $sQuery  = " delete from db_menu where id_item_filho = 10428 AND modulo = 1985522; ";
        $sQuery .= " delete from db_itensmenu where id_item  = 10428; ";

        $this->execute($sQuery);
    }

    private function upDicionarioTabela()
    {
        $sQuery  = " insert into db_sysarquivo values (1010208, 'parametroscobrancaregistrada', 'Parâmetros da cobrança registrada', '', '2017-06-22', 'Parâmetros da cobrança registrada', 0, 'f', 'f', 'f', 'f' ); ";
        $sQuery .= " insert into db_sysarqmod values (54,1010208); ";
        $sQuery .= " update db_sysarquivo set nomearq = 'parametroscobrancaregistrada', descricao = 'Parâmetros da cobrança registrada', sigla = 'ar28', dataincl = '2017-06-22', rotulo = 'Parâmetros da cobrança registrada', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010208; ";
        $sQuery .= " insert into db_sysarqarq values(0,1010208); ";
        $sQuery .= " insert into db_syscampo values(1009337,'ar28_sequencial','int4','Código sequencial dos parâmetros da cobrança registrada.','0', 'Código',10,'f','f','f',1,'text','Código'); ";
        $sQuery .= " insert into db_syscampo values(1009338,'ar28_usuario','varchar(30)','Usuário do Webservice da Caixa','', 'Usuário',30,'f','t','f',0,'text','Usuário'); ";
        $sQuery .= " insert into db_sysarqcamp values(1010208,1009337,1,0); ";
        $sQuery .= " insert into db_sysarqcamp values(1010208,1009338,2,0); ";
        $sQuery .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010208,1009337,1,1009337); ";
        $sQuery .= " insert into db_sysindices values(1008205,'parametroscobrancaregistrada_sequencial_in',1010208,'1'); ";
        $sQuery .= " insert into db_syscadind values(1008205,1009337,1); ";
        $sQuery .= " insert into db_syssequencia values(1000673, 'parametroscobrancaregistrada_ar28_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $sQuery .= " update db_sysarqcamp set codsequencia = 1000673 where codarq = 1010208 and codcam = 1009337;";

        $this->execute($sQuery);
    }

    private function downDicionarioTabela()
    {
        $sQuery  = " delete from db_sysarqarq where codarq = 1010208; ";
        $sQuery .= " delete from db_sysarqcamp where codarq = 1010208; ";
        $sQuery .= " delete from db_sysprikey where codarq = 1010208; ";
        $sQuery .= " delete from db_syscadind where codind = 1008205; ";
        $sQuery .= " delete from db_sysindices where codind = 1008205; ";
        $sQuery .= " delete from db_sysarqmod where codarq = 1010208; ";
        $sQuery .= " delete from db_syscampo where codcam in (1009337, 1009338); ";
        $sQuery .= " delete from db_sysarquivo where codarq = 1010208; ";
        $sQuery .= " delete from db_syssequencia where codsequencia = 1000673; ";

        $this->execute($sQuery);
    }

    private function upEstrutura()
    {
        $sQuery  = " CREATE SEQUENCE parametroscobrancaregistrada_ar28_sequencial_seq ";
        $sQuery .= " INCREMENT 1 ";
        $sQuery .= " MINVALUE 1 ";
        $sQuery .= " MAXVALUE 9223372036854775807 ";
        $sQuery .= " START 1 ";
        $sQuery .= " CACHE 1; ";

        $sQuery .= " CREATE TABLE arrecadacao.parametroscobrancaregistrada( ";
        $sQuery .= " ar28_sequencial     int4 NOT NULL default 0, ";
        $sQuery .= " ar28_usuario        varchar(30) , ";
        $sQuery .= " CONSTRAINT parametroscobrancaregistrada_sequ_pk PRIMARY KEY (ar28_sequencial)); ";

        $sQuery .= " CREATE UNIQUE INDEX parametroscobrancaregistrada_sequencial_in ON parametroscobrancaregistrada(ar28_sequencial); ";

        $this->execute($sQuery);
    }

    private function downEstrutura()
    {
        $sQuery  = " DROP SEQUENCE IF EXISTS parametroscobrancaregistrada_ar28_sequencial_seq; ";
        $sQuery .= " DROP TABLE IF EXISTS arrecadacao.parametroscobrancaregistrada CASCADE; ";

        $this->execute($sQuery);
    }
}
