<?php

use Classes\PostgresMigration;

class M9624LicitaConEstruturaEcidade extends PostgresMigration
{

    public function up()
    {
        $this->upDicionarioDados();
        $this->upAtributosDinamicos();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downAtributosDinamicos();
    }

    private function upDicionarioDados()
    {

        $this->execute("insert into db_syscampo values(1009481,'pc23_taxahomologada','float4','Taxa Homologada','0', 'Taxa Homologada',10,'t','f','f',4,'text','Taxa Homologada');");
        $this->execute("insert into db_syscampo values(1009482,'pc23_taxaestimada','float4','Taxa Estimada','0', 'Taxa Estimada',10,'t','f','f',4,'text','Taxa Estimada');");
        $this->execute("insert into db_sysarqcamp values(863,1009482,13,0);");
        $this->execute("insert into db_sysarqcamp values(863,1009481,14,0);");

        $this->execute("alter table pcorcamval add column pc23_taxaestimada   numeric default null;");
        $this->execute("alter table pcorcamval add column pc23_taxahomologada numeric default null;");

        $this->execute("insert into db_syscampo values(1009483,'ac07_numeroatodesignacao','varchar(20)','Número do Ato de Designação','', 'Número do Ato de Designação',20,'t','t','f',0,'text','Número do Ato de Designação');");
        $this->execute("insert into db_syscampo values(1009484,'ac07_anoatodesignacao','int4','Ano do Ato de Designação','0', 'Ano do Ato de Designação',4,'t','f','f',1,'text','Ano do Ato de Designação');");
        $this->execute("insert into db_syscampo values(1009485,'ac07_nomearquivo','varchar(200)','Nome do Arquivo do Ato de Designação','', 'Nome do Arquivo do Ato de Designação',200,'t','t','f',0,'text','Nome do Arquivo do Ato de Designação');");
        $this->execute("insert into db_syscampo values(1009486,'ac07_arquivo','oid','Arquivo','', 'Arquivo',1,'t','f','f',1,'text','Arquivo');");
        $this->execute("insert into db_sysarqcamp values(2831,1009483,7,0);");
        $this->execute("insert into db_sysarqcamp values(2831,1009484,8,0);");
        $this->execute("insert into db_sysarqcamp values(2831,1009485,9,0);");
        $this->execute("insert into db_sysarqcamp values(2831,1009486,10,0);");

        $this->execute("alter table acordocomissaomembro add column ac07_numeroatodesignacao varchar(20) default null;");
        $this->execute("alter table acordocomissaomembro add column ac07_anoatodesignacao integer default null;");
        $this->execute("alter table acordocomissaomembro add column ac07_nomearquivo varchar(200) default null;");
        $this->execute("alter table acordocomissaomembro add column ac07_arquivo oid default null;");

        $this->execute("
            UPDATE pctipocompratribunal SET l44_descricao = 'RDC Presencial' WHERE l44_descricao = 'RDC';
            UPDATE pctipocompratribunal SET l44_descricao = 'Leilão Presencial' WHERE l44_descricao = 'Leilão';
            INSERT INTO pctipocompratribunal VALUES (57, '99', 'LEI 13.303/2016 Eletrônico', 'RS', 'ESE');
            INSERT INTO pctipocompratribunal VALUES (58, '99', 'LEI 13.303/2016 Presencial', 'RS', 'EST');
            INSERT INTO pctipocompratribunal VALUES (59, '99', 'Leilão Eletrônico', 'RS', 'LEE');
            INSERT INTO pctipocompratribunal VALUES (60, '99', 'RDC Eletrônico', 'RS', 'RDE');
        ");

        $this->execute("insert into db_syscampo values(1009491,'pc21_taxaestimadaglobal','float4','Taxa estimada global do orçamento.','0', 'Taxa Estimada Global',10,'t','f','f',4,'text','Taxa Estimada Global');");
        $this->execute("insert into db_sysarqcamp values(858,1009491,7,0);");

        $this->execute("alter table pcorcamforne add column pc21_taxaestimadaglobal float4 default null;");
    }

    private function downDicionarioDados()
    {

        $this->execute("alter table pcorcamval drop column pc23_taxaestimada;");
        $this->execute("alter table pcorcamval drop column pc23_taxahomologada;");
        $this->execute("alter table acordocomissaomembro drop column ac07_numeroatodesignacao;");
        $this->execute("alter table acordocomissaomembro drop column ac07_anoatodesignacao;");
        $this->execute("alter table acordocomissaomembro drop column ac07_nomearquivo;");
        $this->execute("alter table acordocomissaomembro drop column ac07_arquivo;");

        $this->execute("delete from db_sysarqcamp where codcam in (1009481, 1009482, 1009483, 1009484, 1009485, 1009486);");
        $this->execute("delete from db_syscampo where codcam in (1009481, 1009482, 1009483, 1009484, 1009485, 1009486);");

        $this->execute("
            UPDATE pctipocompratribunal SET l44_descricao = 'RDC' WHERE l44_descricao = 'RDC Presencial';
            UPDATE pctipocompratribunal SET l44_descricao = 'Leilão' WHERE l44_descricao = 'Leilão Presencial';
            DELETE FROM pctipocompratribunal WHERE l44_sequencial IN (57, 58, 59, 60);
        ");

        $this->execute("alter table pcorcamforne drop column pc21_taxaestimadaglobal;");

        $this->execute("delete from db_sysarqcamp where codarq = 858 and codcam = 1009491;");
        $this->execute("delete from db_syscampo where codcam = 1009491;");
    }

    private function upAtributosDinamicos()
    {
        $codigoTpBeneficioEPP = $this->getCodigoAtributoDinamico('tipobeneficiomicroepp');
        $codigoRegimeExecucao = $this->getCodigoAtributoDinamico('regimeexecucao');
        $codigoTpLicitacao    = $this->getCodigoAtributoDinamico('tipolicitacao');
        $this->execute("

            insert into db_cadattdinamicoatributosopcoes
                values ( nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoTpBeneficioEPP}, 'R', 'Cota reservada'),
                       ( nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoTpBeneficioEPP}, 'P', 'Cota principal'),
                       ( nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoTpLicitacao}, 'MTX', 'Menor taxa'),
                       ( nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoTpLicitacao}, 'MDB', 'Melhor destinação de bens alienados'),
                       ( nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoRegimeExecucao}, 'S', 'Semi-integrada');
        ");
    }

    private function downAtributosDinamicos()
    {

        $codigoTpBeneficioEPP = $this->getCodigoAtributoDinamico('tipobeneficiomicroepp');
        $codigoRegimeExecucao = $this->getCodigoAtributoDinamico('regimeexecucao');
        $codigoTpLicitacao    = $this->getCodigoAtributoDinamico('tipolicitacao');
        $this->execute("
            delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = {$codigoTpBeneficioEPP} and db18_opcao in ('R', 'P');
            delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = {$codigoRegimeExecucao} and db18_opcao = 'S';
            delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = {$codigoTpLicitacao} and db18_opcao in ('MTX', 'MDB');
        ");
    }

    private function getCodigoAtributoDinamico($nomeAtributo)
    {
        $busca = $this->fetchRow("select db109_sequencial from db_cadattdinamicoatributos where db109_nome = '{$nomeAtributo}'");
        return $busca['db109_sequencial'];
    }
}
