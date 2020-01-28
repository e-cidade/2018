<?php

use Classes\PostgresMigration;

class M9506MultasTransito extends PostgresMigration
{
    public function up()
    {
        $this->criarMenu();
        $this->addDicionarioDados();
        $this->criarTabela();
    }

    public function down()
    {
        $this->removerDicionarioDados();
        $this->removerTabela();
    }

    private function criarMenu()
    {
       $this->execute("update db_itensmenu set descricao = 'Infração de Trânsito', help = 'Infração de Trânsito', libcliente='true' where id_item = 10455");
       $this->execute("update db_itensmenu set descricao = 'Consolidado por Nível', help = 'Consolidado por Nível', funcao = 'inf2_arrecmultastransito001.php' , libcliente='true' where id_item = 10456");
       $this->execute("update db_itensmenu set descricao = 'Pagamentos Duplicados', help = 'Pagamentos Duplicados', funcao = 'inf2_pagamentoduplicidade001.php', libcliente='true' where id_item = 10457");
    }

    private function addDicionarioDados()
    {
        // Cadastro de Tabelas
        $aColumns  = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues   = array(
            array(1010228, 'arquivoinfracaomulta', 'Guarda as multas do arquivo de infração importado.', 'i08', '2017-09-26', 'Multas', 0, 'f', 'f', 'f', 'f' ),
        );
        $table     = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Vínculo da tabela com o módulo
        $aColumns  =  array('codmod', 'codarq');
        $aValues   =  array(
            array(5,1010228),
        );
        $table     =  $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de campos
        $aColumns  = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues   = array(
            array(1009452,'i08_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'),
            array(1009453,'i08_arquivoinfracao','int4','Vínculo das multas com o arquivo de infração importado.','0', 'Arquivo de Infração',10,'f','f','f',1,'text','Arquivo de Infração'),
            array(1009454,'i08_dtpagamento','date','Data de pagamento da multa.','null', 'Data de Pagamento',10,'f','f','f',1,'text','Data de Pagamento'),
            array(1009455,'i08_dtrepasse','date','Data do repasse da multa.','null', 'Data de Repasse',10,'f','f','f',1,'text','Data de Repasse'),
            array(1009456,'i08_nivel','int4','Nível da multa.','0', 'Nível',10,'f','f','f',1,'text','Nível'),
            array(1009457,'i08_vlfunset','float4','Valor de repasse para a FUNSET.','0', 'Valor FUNSET',10,'t','f','f',4,'text','Valor FUNSET'),
            array(1009458,'i08_vldetran','float4','Valor de repasse para o DETRAN.','0', 'Valor DETRAN',10,'t','f','f',4,'text','Valor DETRAN'),
            array(1009459,'i08_vlprefeitura','float4','Valor de repasse para a Prefeitura.','0', 'Valor Prefeitura',10,'f','f','f',4,'text','Valor Prefeitura'),
            array(1009460,'i08_vlbruto','float4','Valor bruto da multa.','0', 'Valor Bruto',10,'f','f','f',4,'text','Valor Bruto'),
            array(1009461,'i08_codigoinfracao','varchar(10)','Código da infração.', '0', 'Código da Infração',10,'f','t','f',0,'text','Código da Infração'),
            array(1009462,'i08_nossonumero', 'varchar(11)','Identificação do Titulo no banco(Nosso Número).','', 'Nosso Número', 11,'f','t','f',0,'text','Nosso Número'),
            array(1009463,'i08_autoinfracao','varchar(13)','Código do auto de infração.','', 'Auto de Infração',13,'f','t','f',0,'text','Auto de Infração'),
            array(1009464,'i08_duplicado','bool','Mostra se é um pagamento de multa duplicado.','f', 'Duplicado',1,'f','f','f',5,'text','Duplicado'),
        );
        $table     = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Vínculo dos campos com a tabela
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1010228,1009452,1,0),
            array(1010228,1009453,2,0),
            array(1010228,1009454,3,0),
            array(1010228,1009455,4,0),
            array(1010228,1009456,5,0),
            array(1010228,1009457,6,0),
            array(1010228,1009458,7,0),
            array(1010228,1009459,8,0),
            array(1010228,1009460,9,0),
            array(1010228,1009461,10,0),
            array(1010228,1009462,11,0),
            array(1010228,1009463,12,0),
            array(1010228,1009464,13,0),
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro da PK
        $aColumns = array('codarq', 'codcam','sequen', 'camiden');
        $aValues  = array(
          array(1010228,1009452,1,1009452),
        );
        $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro da FK
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues  = array(
            array(1010228,1009453,1,1010226,0),
        );
        $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues  = array(
            array(1008225,'arquivoinfracaomulta_arquivoinfracao_in',1010228,'0'),
        );
        $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues  = array(
            array(1008225,1009453,1),
        );
        $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de sequências
        $aColumns   = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues    = array(
          array(1000692, 'arquivoinfracaomulta_i08_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table      =  $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
        $this->execute("update db_sysarqcamp set codsequencia = 1000692 where codarq = 1010228 and codcam = 1009452");
    }

    private function criarTabela()
    {

        $this->execute("CREATE SEQUENCE caixa.arquivoinfracaomulta_i08_sequencial_seq");
        $atualizacaoiptuschema = $this->table('arquivoinfracaomulta', array('schema' => 'caixa', 'id' => false, 'primary_key' => 'i08_sequencial', 'constraint' => 'caixa.i08_sequencial_pk'));
        $atualizacaoiptuschema->addColumn('i08_sequencial',     'integer' )
                        ->addColumn('i08_arquivoinfracao', 'integer')
                        ->addForeignKey('i08_arquivoinfracao', 'caixa.arquivoinfracao', 'i07_sequencial', array('constraint'=>'arquivoinfracaomulta_i08_arquivoinfracao_fk'))
                        ->addIndex(array('i08_arquivoinfracao'), array('name' => 'arquivoinfracaomulta_arquivoinfracao_in'))
                        ->addColumn('i08_dtpagamento', 'date')
                        ->addColumn('i08_dtrepasse', 'date')
                        ->addColumn('i08_nivel', 'integer')
                        ->addColumn('i08_vlfunset', 'float')
                        ->addColumn('i08_vldetran', 'float')
                        ->addColumn('i08_vlprefeitura', 'float')
                        ->addColumn('i08_vlbruto', 'float')
                        ->addColumn('i08_codigoinfracao', 'string', array('limit' => 10))
                        ->addColumn('i08_nossonumero',  'string', array('limit' => 11) )
                        ->addColumn('i08_autoinfracao', 'string', array('limit' => 13) )
                        ->addColumn('i08_duplicado', 'boolean', array('default' => false) )
                        ->create();
        $this->execute("ALTER TABLE caixa.arquivoinfracaomulta ALTER COLUMN i08_sequencial SET DEFAULT nextval('caixa.arquivoinfracaomulta_i08_sequencial_seq')");
    }

    public function removerDicionarioDados()
    {
        $this->execute('delete from configuracoes.db_syscadind  where codind in (1008225) ');
        $this->execute('delete from configuracoes.db_sysindices where codind in (1008225) ');
        $this->execute('delete from configuracoes.db_sysforkey where codarq in (1010228) ');

        $this->execute("delete from configuracoes.db_sysarqcamp where codcam in (1009452, 1009453, 1009454, 1009455, 1009456, 1009457, 1009458, 1009459, 1009460, 1009461, 1009462, 1009463, 1009464)");
        $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010228)');
        $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010228)');
        $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010228)');
        $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000692)');
        $this->execute("delete from configuracoes.db_syscampo where codcam in (1009452, 1009453, 1009454, 1009455, 1009456, 1009457, 1009458, 1009459, 1009460, 1009461, 1009462, 1009463, 1009464)");
    }


    private function removerTabela()
    {
        $this->execute("DROP TABLE IF EXISTS arquivoinfracaomulta");
        $this->execute("DROP SEQUENCE IF EXISTS arquivoinfracaomulta_i08_sequencial_seq");
    }
}
