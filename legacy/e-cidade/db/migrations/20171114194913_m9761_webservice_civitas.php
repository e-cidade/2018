<?php

use Classes\PostgresMigration;

class M9761WebserviceCivitas extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
        $this->upEstrutura();
        $this->upDados();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstrutura();
    }


    private function upDicionarioDados()
    {
        $tabelaSituacao  = " insert into db_sysarquivo values (1010237, 'requisicaocivitassituacao', 'Tabela com todas as situações das requisições do CIVITAS e suas descrições', 'rq02', '2017-11-16', 'requisicaocivitassituacao', 0, 'f', 'f', 'f', 'f' );";
        $tabelaSituacao .= " insert into db_sysarqmod values (46,1010237);";
        $tabelaSituacao .= " insert into db_syscampo values(1009517,'rq02_sequencial','int4','Código Sequencial da tabela de situações do CIVITAS','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');";
        $tabelaSituacao .= " insert into db_syscampo values(1009518,'rq02_codigo','int4','Código da situação do CIVITAS','0', 'Código Situação',10,'f','f','f',1,'text','Código Situação');";
        $tabelaSituacao .= " insert into db_syscampo values(1009519,'rq02_descricao','text','Descrição da Situação da requisição do CIVITAS','', 'Descrição Situação',100,'f','t','f',0,'text','Descrição Situação');";
        $tabelaSituacao .= " insert into db_sysarqcamp values(1010237,1009517,1,0);";
        $tabelaSituacao .= " insert into db_sysarqcamp values(1010237,1009518,2,0);";
        $tabelaSituacao .= " insert into db_sysarqcamp values(1010237,1009519,3,0);";
        $tabelaSituacao .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010237,1009517,1,1009517);";
        $tabelaSituacao .= " insert into db_sysindices values(1008232,'requisicaocivitassituacao_sequencial_in',1010237,'1');";
        $tabelaSituacao .= " insert into db_syscadind values(1008232,1009517,1);";
        $tabelaSituacao .= " insert into db_sysindices values(1008233,'requisicaocivitassituacao_codigo_descricao_in',1010237,'1');";
        $tabelaSituacao .= " insert into db_syscadind values(1008233,1009518,1);";
        $tabelaSituacao .= " insert into db_syscadind values(1008233,1009519,2);";
        $tabelaSituacao .= " insert into db_syssequencia values(1000697, 'requisicaocivitassituacao_rq02_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $tabelaSituacao .= " update db_sysarqcamp set codsequencia = 1000697 where codarq = 1010237 and codcam = 1009517;";

        $tabelaRequisicaoCivitas  = " insert into db_sysarquivo values (1010236, 'requisicaocivitas', 'Tabela de requisições feitas ao Webservice do civitas.', 'rq01', '2017-11-16', 'requisicaocivitas', 0, 'f', 'f', 't', 't' );";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqmod values (46,1010236);";
        $tabelaRequisicaoCivitas .= " insert into db_syscampo values(1009514,'rq01_sequencial','int4','Código sequencial das requisições.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');";
        $tabelaRequisicaoCivitas .= " insert into db_syscampo values(1009515,'rq01_dataenvio','date','Data de envio da requisição','null', 'Envio',10,'f','f','f',1,'text','Envio');";
        $tabelaRequisicaoCivitas .= " insert into db_syscampo values(1009516,'rq01_situacao','int4','Status da requisição','0', 'Situação',10,'f','f','f',1,'text','Situação');";
        $tabelaRequisicaoCivitas .= " delete from db_sysarqcamp where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010236,1009514,1,0);";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010236,1009515,2,0);";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010236,1009516,3,0);";
        $tabelaRequisicaoCivitas .= " delete from db_sysprikey where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " delete from db_sysforkey where codarq = 1010236 and referen = 0;";
        $tabelaRequisicaoCivitas .= " insert into db_sysforkey values(1010236,1009516,1,1010237,0);";
        $tabelaRequisicaoCivitas .= " delete from db_sysprikey where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010236,1009514,1,1009514);";
        $tabelaRequisicaoCivitas .= " insert into db_sysindices values(1008234,'rq01_sequencial_in',1010236,'1');";
        $tabelaRequisicaoCivitas .= " insert into db_syscadind values(1008234,1009514,1);";
        $tabelaRequisicaoCivitas .= " delete from db_sysarqcamp where codarq = 1010237;";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010237,1009517,1,1000697);";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010237,1009518,2,0);";
        $tabelaRequisicaoCivitas .= " insert into db_sysarqcamp values(1010237,1009519,3,0);";
        $tabelaRequisicaoCivitas .= " delete from db_syscadind where codind = 1008234;";
        $tabelaRequisicaoCivitas .= " insert into db_syscadind values(1008234,1009514,1);";
        $tabelaRequisicaoCivitas .= " delete from db_syscadind where codind = 1008234;";
        $tabelaRequisicaoCivitas .= " insert into db_syscadind values(1008234,1009514,1);";
        $tabelaRequisicaoCivitas .= " insert into db_syssequencia values(1000698, 'requisicaocivitas_rq01_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $tabelaRequisicaoCivitas .= " update db_sysarqcamp set codsequencia = 1000698 where codarq = 1010236 and codcam = 1009514;";

        $this->execute($tabelaSituacao);
        $this->execute($tabelaRequisicaoCivitas);
    }

    private function downDicionarioDados()
    {
        $tabelaRequisicaoCivitas  = " delete from db_acount where codarq = 1010236;";
        $tabelaRequisicaoCivitas  = " delete from db_syssequencia where codsequencia = 1000698;";
        $tabelaRequisicaoCivitas .= " delete from db_syscadind where codind = 1008234;";
        $tabelaRequisicaoCivitas .= " delete from db_sysarqcamp where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " delete from db_sysindices where codind = 1008234;";
        $tabelaRequisicaoCivitas .= " delete from db_sysprikey where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " delete from db_sysforkey where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " delete from db_syscampo where codcam in (1009514, 1009515, 1009516);";
        $tabelaRequisicaoCivitas .= " delete from db_sysarqmod where codarq = 1010236;";
        $tabelaRequisicaoCivitas .= " delete from db_sysarquivo where codarq = 1010236;";

        $tabelaSituacao  = " delete from db_acount where codarq = 1010237;";
        $tabelaSituacao  = " delete from db_sysarqcamp where codarq = 1010237;";
        $tabelaSituacao .= " delete from db_sysarqmod where codarq = 1010237;";
        $tabelaSituacao .= " delete from db_sysprikey where codarq = 1010237;";
        $tabelaSituacao .= " delete from db_syscadind where codind in (1008233,1008232);";
        $tabelaSituacao .= " delete from db_sysindices where codind in (1008233,1008232);";
        $tabelaSituacao .= " delete from db_syssequencia where codsequencia = 1000697;";
        $tabelaSituacao .= " delete from db_syscampo where codcam in (1009517, 1009518, 1009519);";
        $tabelaSituacao .= " delete from db_sysarquivo where codarq = 1010237;";

        $this->execute($tabelaRequisicaoCivitas);
        $this->execute($tabelaSituacao);
    }

    private function upEstrutura()
    {
        $situacao = <<<CREATEESTRUTURA
CREATE SEQUENCE tributario.requisicaocivitassituacao_rq02_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE tributario.requisicaocivitassituacao(
rq02_sequencial		int4 NOT NULL default 0,
rq02_codigo		int4 NOT NULL default 0,
rq02_descricao		text ,
CONSTRAINT requisicaocivitassituacao_sequ_pk PRIMARY KEY (rq02_sequencial));

CREATE UNIQUE INDEX requisicaocivitassituacao_sequencial_in ON requisicaocivitassituacao(rq02_sequencial);
CREATE UNIQUE INDEX requisicaocivitassituacao_codigo_descricao_in ON requisicaocivitassituacao(rq02_codigo,rq02_descricao);

CREATEESTRUTURA;

        $requisicaocivitas = <<<CREATEESTRUTURAREQ
CREATE SEQUENCE tributario.requisicaocivitas_rq01_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE tributario.requisicaocivitas(
rq01_sequencial     int4 NOT NULL default 0,
rq01_dataenvio      date NOT NULL,
rq01_situacao       int4 NOT NULL default 0,
CONSTRAINT requisicaocivitas_sequ_pk PRIMARY KEY (rq01_sequencial));

ALTER TABLE requisicaocivitas
ADD CONSTRAINT requisicaocivitas_situacao_fk FOREIGN KEY (rq01_situacao)
REFERENCES requisicaocivitassituacao;

CREATE UNIQUE INDEX requisicaocivitas_sequencial_in ON requisicaocivitas(rq01_sequencial);


CREATEESTRUTURAREQ;

        $this->execute($situacao);
        $this->execute($requisicaocivitas);
    }

    private function downEstrutura()
    {
        $situacao = <<<DROPESTRUTURA
DROP SEQUENCE IF EXISTS requisicaocivitassituacao_rq02_sequencial_seq;
DROP TABLE IF EXISTS requisicaocivitassituacao CASCADE;

DROPESTRUTURA;

        $requisicaocivitas = <<<DROPESTRUTURAREQ
DROP TABLE IF EXISTS requisicaocivitas CASCADE;
DROP SEQUENCE IF EXISTS requisicaocivitas_rq01_sequencial_seq;

DROPESTRUTURAREQ;

        $this->execute($requisicaocivitas);
        $this->execute($situacao);
    }

    private function upDados()
    {
        $this->execute("insert into tributario.requisicaocivitassituacao select nextval('requisicaocivitassituacao_rq02_sequencial_seq'), 1, 'SUCESSO';");
        $this->execute("insert into tributario.requisicaocivitassituacao select nextval('requisicaocivitassituacao_rq02_sequencial_seq'), 2, 'ERRO';");
        $this->execute("insert into tributario.requisicaocivitassituacao select nextval('requisicaocivitassituacao_rq02_sequencial_seq'), 3, 'PENDENTE';");
    }
}
