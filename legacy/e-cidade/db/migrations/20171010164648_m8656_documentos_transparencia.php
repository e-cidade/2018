<?php

use Classes\PostgresMigration;

class M8656DocumentosTransparencia extends PostgresMigration
{
    public function up()
    {
        $this->criarMenu();
        $this->addDicionarioDados();
        $this->criarTabela();
    }

    public function down()
    {
        $this->removeritensMenu();
        $this->removerDicionarioDados();
        $this->removerTabela();
    }

    private function criarMenu()
    {
        // Cria o item de MENU
        $aColumns   =  array('id_item' ,'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente');
        $aValues    =  array(
            array(10462 ,'Documentos do Portal Transparência' ,'Documentos do Portal Transparência' ,'lic4_documentostransparencia001.php' ,'1' ,'1' ,'Configuração de documentos do LicitaCon que irão para o Portal Transparência.' ,'true' ),
        );
        $table      = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Víncula item de menu
        $aColumns   =    array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
        $aValues    =    array(
            array( 10212 ,10462 ,5 ,381 ),
        );
        $table      =  $this->table('db_menu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

    }

    private function addDicionarioDados()
    {
        // Cadastro de Tabelas
        $aColumns  = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues   = array(
            array(1010230, 'documentolicitacaotransparencia', 'Documento de Licitação para o Portal Transparência', 'l48', '2017-10-10', 'Documento de Licitação para o Portal Transparência', 0, 'f', 'f', 'f', 'f' ),
        );
        $table     = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

         // Vínculo da tabela com o módulo
        $aColumns  =  array('codmod', 'codarq');
        $aValues   =  array(
            array(19,1010230),
        );
        $table     =  $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de campos
        $aColumns  = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues   = array(
            array(1009467,'l48_sequencial','int4','Código Sequencial da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
            array(1009468,'l48_documento','int4','Código do tipo de Documento do LicitaCon','0', 'Documento',10,'f','f','f',1,'text','Documento'),
        );
        $table     = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Vínculo dos campos com a tabela
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1010230,1009467,1,0),
            array(1010230,1009468,2,0),
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro da PK
        $aColumns = array('codarq', 'codcam','sequen', 'camiden');
        $aValues  = array(
            array(1010230,1009467,1,1009467),
        );
        $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues  = array(
            array(1008227,'documentolicitacaotransparencia_documento_in',1010230,'1')
        );
        $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues  = array(
            array(1008227,1009468,1),
        );
        $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de sequências
        $aColumns   = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues    = array(
          array(1000693, 'documentolicitacaotransparencia_l48_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table      =  $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $this->execute("update db_sysarqcamp set codsequencia = 1000693 where codarq = 1010230 and codcam = 1009467");
            
    }

    private function criarTabela()
    {
        $this->execute("CREATE SEQUENCE licitacao.documentolicitacaotransparencia_l48_sequencial_seq");
        $documentolicitacaotransparencia = $this->table('documentolicitacaotransparencia', array('schema' => 'licitacao', 'id' => false, 'primary_key' => 'l48_sequencial', 'constraint' => 'licitacao.l48_sequencial_pk'));
        $documentolicitacaotransparencia->addColumn('l48_sequencial',     'integer' )
                        ->addColumn('l48_documento',         'integer' )
                        ->create();
        $this->execute("ALTER TABLE licitacao.documentolicitacaotransparencia ALTER COLUMN l48_sequencial SET DEFAULT nextval('licitacao.documentolicitacaotransparencia_l48_sequencial_seq')");
        $this->execute("CREATE UNIQUE INDEX documentolicitacaotransparencia_documento_in ON documentolicitacaotransparencia(l48_documento);");
    }

    private function removeritensMenu()
    {
        $this->execute("delete from configuracoes.db_menu where id_item_filho = 10462 AND modulo = 381");
        $this->execute("delete from configuracoes.db_itensmenu where id_item = 10462");
    }

    public function removerDicionarioDados()
    {
        $this->execute('delete from configuracoes.db_syscadind  where codind in (1008227) ');
        $this->execute('delete from configuracoes.db_sysindices where codind in (1008227) ');
        $this->execute("delete from configuracoes.db_sysarqcamp where codcam in (1009467, 1009468)");
        $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010230)');
        $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010230)');
        $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010230)');
        $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000693)');
        $this->execute("delete from configuracoes.db_syscampo where codcam in (1009467, 1009468)");

    }


    private function removerTabela()
    {
        $this->execute("DROP TABLE IF EXISTS documentolicitacaotransparencia");
        $this->execute("DROP SEQUENCE IF EXISTS documentolicitacaotransparencia_l48_sequencial_seq;");
    }


}
