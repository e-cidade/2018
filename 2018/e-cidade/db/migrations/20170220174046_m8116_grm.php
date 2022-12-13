<?php

use Classes\PostgresMigration;

class M8116Grm extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
      $this->addDicionarioDados();    
      $this->criarTabelas();
      $this->execute("insert into caixa.cadtipomod values (26, 'GRM')");
      $this->execute("insert into caixa.cadmodcarne values (93, 'GRM', null, 0, 0, null, null);");
    }
    
    public function down()
    {
      $this->removerDicionarioDados();
      $this->droparTabelas();
      $this->execute("delete from caixa.cadtipomod where k46_sequencial = 26");
      $this->execute("delete from caixa.cadmodcarne where k47_sequencial = 93");
    }
    
    private function addDicionarioDados() {

      // tabelas
      $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
      $aValues  = array(
        array(4030, 'unidadegestora', 'Unidade Gestora', 'k171', '2017-02-20', 'Unidade Gestora', 0, 'f', 'f', 'f', 'f' ),
        array(4031, 'tiporecolhimento', 'Tipo de Recolhimento', 'k172', '2017-02-20', 'Tipo de Recolhimento', 0, 'f', 'f', 'f', 'f' ),
        array(4032, 'unidadegestoratiporecolhimento', 'unidadegestoratiporecolhimento', 'k173', '2017-02-20', 'unidadegestoratiporecolhimento', 0, 'f', 'f', 'f', 'f' ),
        array(4033, 'guiarecolhimento', 'Guia de Recolhimento Municipal', 'K174', '2017-02-20', 'Guia de Recolhimento Municipal', 0, 'f', 'f', 'f', 'f'),
      );
      $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // vincula modulo
      $aColumns = array('codmod', 'codarq' );
      $aValues  = array(
        array(5,4030),
        array(5,4031),
        array(5,4032),
        array(5,4033),
      );
      $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // campos
      $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
      $aValues  = array(
        array(22363 ,'k171_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' ),       
        array(22364 ,'k171_nome' ,'varchar(100)' ,'Nome' ,'' ,'Nome' ,100 ,'false' ,'true' ,'false' ,0 ,'text' ,'Nome' ),       
        array(22365 ,'k172_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código'),       
        array(22366 ,'k172_nome' ,'varchar(100)' ,'Nome do Recolhimento' ,'' ,'Nome do Recolhimento' ,100 ,'false' ,'true' ,'false' ,0 ,'text' ,'Nome do Recolhimento' ),       
        array(22367 ,'k173_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código'),       
        array(22368 ,'k173_unidadegestora' ,'int4' ,'Unida Gestora' ,'' ,'Unida Gestora' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Unida Gestora'),       
        array(22369 ,'k173_tiporecolhimento' ,'int4' ,'Tipo de Recolhimento' ,'' ,'Tipo de Recolhimento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Recolhimento'),       
        array(22370 ,'k173_receita' ,'int4' ,'Receita' ,'' ,'Receita' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Receita'),       
        array(22371 ,'k174_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código'),       
        array(22372 ,'k174_unidadegestora' ,'int4' ,'Unidade Gestora' ,'' ,'Unidade Gestora' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Unidade Gestora'),       
        array(22373 ,'k174_tiporecolhimento' ,'int4' ,'Tipo de Recolhimento' ,'' ,'Tipo de Recolhimento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Recolhimento'),       
        array(22374 ,'k174_cgm' ,'int4' ,'Cgm' ,'' ,'Cgm' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Cgm'),       
        array(22375 ,'k174_numpre' ,'int4' ,'Código de Arrecadação' ,'' ,'Código de Arrecadação' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código de Arrecadação'),       
        array(22376 ,'k174_numeroreferencia' ,'varchar(200)' ,'Número de Referencia' ,'' ,'Número de Referência' ,200 ,'true' ,'true' ,'false' ,0 ,'text' ,'Número de Referência'),       
        array(22377 ,'k174_competencia' ,'varchar(9)' ,'Competência' ,'' ,'Competência' ,9 ,'true' ,'true' ,'false' ,0 ,'text' ,'Competência'),       
        array(22378 ,'k174_datavencimento' ,'date' ,'Data de Vencimento' ,'' ,'Data de Vencimento' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data de Vencimento'),       
        array(22379 ,'k174_valor' ,'float4' ,'Valor Principal' ,'' ,'Valor Principal' ,10 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor Principal'),       
        array(22380 ,'k174_desconto' ,'float4' ,'Desconto/Abatimento' ,'' ,'Desconto/Abatimento' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Desconto/Abatimento'),       
        array(22381 ,'k174_multa' ,'float4' ,'Mora/Multa' ,'' ,'Mora/Multa' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Mora/Multa'),       
        array(22382 ,'k174_juros' ,'float4' ,'Juros/Encargos' ,'' ,'Juros/Encargos' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Juros/Encargos'),       
        array(22383 ,'k174_outrosacrescimos' ,'float4' ,'Outros Acréscimos' ,'' ,'Outros Acréscimos' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Outros Acréscimos'),       
        array(22384 ,'k174_valortotal' ,'float4' ,'Valor Total' ,'' ,'Valor Total' ,10 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor Total'),       
        array(22385 ,'k172_tipopessoa' ,'int4' ,'Tipo de Pessoa' ,'3' ,'Tipo de Pessoa' ,1 ,'true' ,'false' ,'false' ,1 ,'text' ,'Tipo de Pessoa'),       
        array(22386 ,'k172_obriganumeroreferencia' ,'bool' ,'Obriga Número de Refêrencia' ,'false' ,'Obriga Número de Refêrencia' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Obriga Número de Refêrencia'),       
        array(22387 ,'k172_codigorecolhimento' ,'varchar(40)' ,'Código Recolhimento' ,'false' ,'Código Recolhimento' ,1 ,'true' ,'false' ,'false' ,0 ,'text' ,'Código Recolhimento'),
        array(22388 ,'k172_especieingresso' ,'int4' ,'Espécie de Ingresso' ,'' ,'Espécie de Ingresso' ,1 ,'true' ,'false' ,'false' ,1 ,'text' ,'Espécie de Ingresso'),
        array(22389 ,'k172_desconto' ,'bool' ,'Informar Desconto/Abatimento' ,'false' ,'Informar Desconto/Abatimento' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Informar Desconto/Abatimento'),       
        array(22390 ,'k172_multa' ,'bool' ,'Informar Mora/Multa' ,'false' ,'Informar Mora/Multa' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Informar Mora/Multa'),       
        array(22391 ,'k172_juros' ,'bool' ,'Informar Juros/Encargos' ,'false' ,'Informar Juros/Encargos' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Informar Juros/Encargos'),       
        array(22392 ,'k172_outrosacrescimos' ,'bool' ,'Informar Outros Acréscimos' ,'false' ,'Informar Outros Acréscimos' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Informar Outros Acréscimos'),       
        array(22393 ,'k172_tituloreduzido' ,'varchar(40)' ,'Titulo Reduzido do recolhimento' ,'' ,'Titulo Reduzido' ,40 ,'true' ,'true' ,'false' ,0 ,'text' ,'Titulo Reduzido'),       
        array(22394 ,'k171_departamento' ,'int4' ,'Departamento' ,'' ,'Departamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Departamento'),       
        array(22405 ,'k172_instrucoes' ,'text' ,'Instruções para a guia de recolhimento' ,'' ,'Instruções' ,1 ,'true' ,'true' ,'false' ,0 ,'text' ,'Instruções'),       
        array(22406 ,'k172_workflow' ,'int4' ,'processo de Workflow para o tipo de recolhimento' ,'null' ,'Workflow' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Workflow'),
        array(22407 ,'k174_processo' ,'int4' ,'Processo gerado para a Guia de Recolhimento' ,'null' ,'Processo' ,1 ,'true' ,'false' ,'false' ,1 ,'text' ,'Processo'),
        array(22408 ,'k174_outrasdeducoes' ,'float8' ,'Outras Deduções' ,'0' ,'Outras Deduções' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Outras Deduções'),
        array( 22409 ,'k172_outrasdeducoes' ,'bool' ,'Informa Outras Deduções' ,'false' ,'Informa Outras Deduções' ,1 ,'false' ,'false' ,'false' ,5 ,'text' ,'Informa Outras Deduções'),
        
      );
      $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // vincula os campos as tabelas
      $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
      $aValues  = array(
        array(4030 ,22363,1 ,1000654),        
        array(4030 ,22364 ,2 ,0),        
        array(4031 ,22365 ,1 ,1000655),        
        array(4031 ,22366 ,2 ,0),        
        array(4032 ,22367 ,1 ,1000656),        
        array(4032 ,22368 ,2 ,0),        
        array(4032 ,22369 ,3 ,0),        
        array(4032 ,22370 ,4 ,0),        
        array(4033 ,22371 ,1 ,1000657),
        array(4033 ,22372 ,2 ,0), 
        array(4033 ,22373 ,3 ,0), 
        array(4033 ,22374 ,4 ,0),
        array(4033 ,22375 ,5 ,0), 
        array(4033 ,22376 ,6 ,0), 
        array(4033 ,22377 ,7 ,0), 
        array(4033 ,22378 ,8 ,0), 
        array(4033 ,22379 ,9 ,0),
        array(4033 ,22380 ,10 ,0),
        array(4033 ,22381 ,11 ,0),
        array(4033 ,22382 ,12 ,0),
        array(4033 ,22383 ,13 ,0),
        array(4033 ,22384 ,14 ,0),        
        array(4031 ,22385 ,3 ,0),        
        array(4031 ,22386 ,4 ,0),        
        array(4031 ,22387 ,5 ,0),        
        array(4031 ,22388 ,6 ,0),        
        array(4031 ,22389 ,7 ,0),        
        array(4031 ,22390 ,8 ,0),        
        array(4031 ,22391 ,9 ,0),        
        array(4031 ,22392 ,10 ,0),        
        array(4031 ,22393 ,11 ,0),        
        array(4030 ,22394 ,3 ,0),        
        array(4031 ,22405 ,12 ,0),        
        array(4031 ,22406 ,13 ,0),        
        array(4033 ,22407 ,15 ,0),        
        array(4033 ,22408 ,16 ,0),        
        array(4031 ,22409 ,14 ,0),        
        
      );
      $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // inclui a sequence
      $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
      $aValues  = array(
        array(1000654, 'unidadegestora_k171_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        array(1000655, 'tiporecolhimento_k172_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        array(1000656, 'unidadegestoratiporecolhimento_k173_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        array(1000657, 'guiarecolhimento_k174_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
      );
      $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // inclui a chave primaria
      $aColumns = array('codarq','codcam','sequen','camiden');
      $aValues  = array(
        array(4030,22363,1,22364),
        array(4031,22365,1,22366),
        array(4032,22367,1,22369),
        array(4033,22371,1,22375),
      );
      $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // inclui a chave estrangeira
      $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
      $aValues  = array(
      array(4032,22368,1,4030,0),
      array(4032,22369,1,4031,0),
      array(4032,22370,1,75,0),  
      array(4033,22372,1,4030,0),
      array(4033,22373,1,4031,0),
      array(4033,22374,1,42,0),  
      array(4030,22394,1,154,0),
      array(4031,22406,1,3155,0),
      array(4033,22407,1,403,0),
      );
      $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // inclui os indices
      $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
      $aValues  = array(
        array(4421,'unidadegestoratiporecolhimento_unidade_in',4032,'0'),
        array(4422,'unidadegestoratiporecolhimento_recolhimento_in',4032,'0'),
        array(4423,'unidadegestoratiporecolhimento_receita_in',4032,'0'),
        array(4424,'unidadegestoratiporecolhimento_unidade_reco_in',4032,'1'),
        array(4425,'guiarecolhimento_unidadegestora_in',4033,'0'),
        array(4426,'guiarecolhimento_tiporecolhimento_in',4033,'0'),
        array(4427,'guiarecolhimento_cgm_in',4033,'0'),
        array(4428,'guiarecolhimento_numpre_in',4033,'0'),        
        array(4429,'unidadegestora_departamento_in',4030,'0'),        
        array(4430,'tiporecolhimento_workflow_in',4031,'0'),        
        array(4431,'guiarecolhimento_processo_in',4033,'0'),        
      );
      $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      // vincula os indices
      $aColumns = array('codind', 'codcam', 'sequen');
      $aValues  = array(
        array(4421,22368,1),
        array(4422,22369,1),
        array(4423,22370,1),
        array(4424,22368,1),
        array(4424,22369,2),
        array(4425,22372,1),
        array(4426,22373,1),
        array(4427,22374,1),
        array(4428,22375,1),     
        array(4429,22394,1),     
        array(4430,22406,1),     
        array(4431,22407,1),     
      );
      
      $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       * valores default db_syscampodef
       */      
      $table    = $this->table('db_syscampodef', array('schema' => 'configuracoes'));
      $aColumns = array('codcam' ,'defcampo' ,'defdescr');
      $aValues  = array(
        array(22385 ,'1' ,'Pessoa Física'),
        array(22385 ,'2' ,'Pessoa Jurídica'),
        array(22385 ,'3' ,'Ambos'),
        array(22388 ,'1' ,'Receita'),
        array(22388 ,'2' ,'DDO'),
        array(22388 ,'3' ,'Estorno de Despesa'),
      );
      $table->insert($aColumns, $aValues);
      $table->saveData();
      
      /**
       * Menus
       */
      $table    = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
      $aColumns = array('id_item', 'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente' );
      $aValues  = array(
        array(10400 ,'Unidade Gestora' ,'Unidade Gestora' ,'cai4_unidadegestora.php' ,'1' ,'1' ,'Cadastro de Unidade de gestora' ,'true'),
        array(10401 ,'Tipo de Recolhimento' ,'Tipos de Recolhimento' ,'cai1_tiporecolhimento001.php' ,'1' ,'1' ,'Tipo de Recolhimento para a GRM' ,'true'),        
        array(10402 ,'GRM' ,'relatórios da GRM' ,'' ,'1' ,'1' ,'GRM' ,'true'),        
        array(10403 ,'Guias Pagas' ,'Guias Pagas' ,'cai2_guiaspagasgrm001.php' ,'1' ,'1' ,'Guias Pagas' ,'true'),        
        array(10404 ,'GRM' ,'GRM' ,'' ,'1' ,'1' ,'Procedimentos da GRM' ,'true'),        
        array(10405 ,'Atividades' ,'Atividades' ,'cai4_atividadesgrm.php' ,'1' ,'1' ,'Atividades manuais das guias ' ,'true'),        
        array(10407 ,'GRM' ,'Cadastros do GRM' ,'' ,'1' ,'1' ,'Cadastros do GRM' ,'true'),        
        array(10408 ,'Atividades' ,'Atividades' ,'' ,'1' ,'1' ,'Workflow' ,'true'),        
        array(10409 ,'Inclusão' ,'Inlcusão de Atividades' ,'hab1_workflow001.php?grupo=4' ,'1' ,'1' ,'Inclusão de workflow' ,'true'),        
        array(10410 ,'Alteração' ,'Alteração' ,'hab1_workflow002.php?grupo=4' ,'1' ,'1' ,'Alteração' ,'true'),        
        array(10411 ,'Exclusão' ,'Exclusão' ,'hab1_workflow003.php?grupo=4' ,'1' ,'1' ,'Exclusão' ,'true'),        
        array(10412 ,'GRM' ,'GRM' ,'' ,'1' ,'1' ,'Consultas de GRM' ,'true'),        
        array(10413 ,'Atividades' ,'Atividades' ,'cai2_atividadesgrm001.php' ,'1' ,'1' ,'Consulta atividades da GRM' ,'true'),        
      );
      $table->insert($aColumns, $aValues);
      $table->saveData();

      $table    = $this->table('db_menu', array('schema' => 'configuracoes'));
      $aColumns = array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
      $aValues  = array(        
        array(29 ,10407 ,276 ,39),
        array(10407 ,10400 ,3 ,39),
        array(10407 ,10401 ,2 ,39),
        array(30 ,10402 ,466 ,39),
        array(10402 ,10403 ,1 ,39),
        array(32 ,10404 ,479 ,39),
        array(10404 ,10405 ,1 ,39),
        array(10407 ,10408 ,1 ,39),
        array(10408 ,10409 ,1 ,39),        
        array(10408 ,10410 ,2 ,39),
        array(10408 ,10411 ,3 ,39),
        array(31 ,10412 ,183 ,39),
        array(10412 ,10413 ,1 ,39),
        
        
      );
      $table->insert($aColumns, $aValues);
      $table->saveData();
    }

  /**
   * Remove dados do dicionario de dados
   */
    private function removerDicionarioDados() 
    {      
      
      $this->execute('delete from configuracoes.db_syscampodef where codcam in(22385, 22388)');
      $this->execute('delete from configuracoes.db_syscadind where codind in(4421,4422,4423,4424,4424,4425,4426,4427,4428,4429,4430, 4431)');
      $this->execute('delete from configuracoes.db_sysindices where codind in(4421,4422,4423,4424,4424,4425,4426,4427,4428,4429, 4430, 4431)');
      $this->execute('delete from configuracoes.db_sysforkey where codarq in(4032, 4033, 4030, 4031)');
      $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000654, 1000655, 1000656,1000657)');
      $this->execute('delete from configuracoes.db_sysprikey where codarq in(4030, 4031, 4032, 4033)');
      $this->execute('delete from configuracoes.db_sysarqcamp where codarq in(4030, 4031, 4032, 4033)');
      $this->execute('delete from configuracoes.db_syscampo where codcam in(22363, 22364,22365,22366,22367,22368,22369,22370,22371,22372,22373,22374,22375,22376,22377,22378,22379,22380,22381,22382,22383,22384,22385,22386,22387,22388,22389,22390,22391,22392, 22393, 22394, 22405, 22406, 22407, 22408, 22409)');
      $this->execute('delete from configuracoes.db_sysarqmod where codarq in(4030, 4031, 4032, 4033)');
      $this->execute('delete from configuracoes.db_sysarquivo where codarq in(4030, 4031, 4032, 4033)');
      $this->execute('delete from configuracoes.db_menu where id_item_filho in (10400, 10401, 10402 ,10403,10404,10405,10407,10408, 10409,10410,10411,10412, 10413)');
      $this->execute('delete from configuracoes.db_itensmenu where id_item in (10400, 10401,10402 ,10403,10404,10405,10407,10408, 10409,10410,10411,10412, 10413)');
    }    
    
    private function criarTabelas() 
    {

      /**
       * Unidade Gestora;
       */
      $this->execute("CREATE SEQUENCE caixa.unidadegestora_k171_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
      $tabela = $this->table('unidadegestora',  array('schema'=>'caixa', 'id'=> false, 'primary_key'=>'k171_sequencial', 'constraint'=>'unidadegestora_k171_sequencial_pk'));
      $tabela->addColumn('k171_sequencial', 'integer')
        ->addColumn('k171_nome', 'string', array('limit' => 100))        
        ->addColumn('k171_departamento', 'integer')
        ->addForeignKey('k171_departamento', 'configuracoes.db_depart', 'coddepto', array('constraint'=>'unidadegestora_departamento_fk'))
        ->addIndex(array('k171_departamento'), array('name' => 'unidadegestora_departamento_in'))
        ->create();
      $this->execute("ALTER TABLE caixa.unidadegestora ALTER COLUMN k171_sequencial SET DEFAULT nextval('caixa.unidadegestora_k171_sequencial_seq')");

      /**
       * tipo recolhimento;
       */
      $this->execute("CREATE SEQUENCE caixa.tiporecolhimento_k172_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
      $tabela = $this->table('tiporecolhimento',  array('schema'=>'caixa', 'id'=> false, 'primary_key'=>'k172_sequencial', 'constraint'=>'tiporecolhimento_k172_sequencial_pk'));
      $tabela->addColumn('k172_sequencial', 'integer')
        ->addColumn('k172_nome', 'string', array('limit' => 100))
        ->addColumn('k172_codigorecolhimento', 'string', array('limit' => 40))
        ->addColumn('k172_tipopessoa', 'integer', array('default' => 3))
        ->addColumn('k172_obriganumeroreferencia', 'boolean', array('default'=>false))
        ->addColumn('k172_especieingresso', 'integer', array('default'=> 1))
        ->addColumn('k172_desconto', 'boolean', array('default'=>false))
        ->addColumn('k172_multa', 'boolean', array('default'=>false))
        ->addColumn('k172_juros', 'boolean', array('default'=>false))
        ->addColumn('k172_outrosacrescimos', 'boolean', array('default'=>false))
        ->addColumn('k172_tituloreduzido', 'string', array('limit' => 40))
        ->addColumn('k172_instrucoes', 'text', array('null' => true))
        ->addColumn('k172_workflow', 'integer', array('null' => true))
        ->addColumn('k172_outrasdeducoes', 'boolean', array('default' => false))
        ->addIndex(array('k172_workflow'), array('name' => 'tiporecolhimento_workflow_in'))
        ->addForeignKey('k172_workflow', 'configuracoes.workflow', 'db112_sequencial', array('constraint' => 'tipo_recolhimento_workflow_fk'))
        ->create();
      $this->execute("ALTER TABLE caixa.tiporecolhimento ALTER COLUMN k172_sequencial SET DEFAULT nextval('caixa.tiporecolhimento_k172_sequencial_seq')");

      /**
       * unidadegestoratiporecolhimento;
       */
      $this->execute("CREATE SEQUENCE caixa.unidadegestoratiporecolhimento_k173_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
      $tabela = $this->table('unidadegestoratiporecolhimento',  array('schema'=>'caixa', 'id'=> false, 'primary_key'=>'k173_sequencial', 'constraint'=>'unidadegestoratiporecolhimento_k173_sequencial_pk'));
      $tabela->addColumn('k173_sequencial', 'integer')
        ->addColumn('k173_unidadegestora', 'integer')
        ->addColumn('k173_tiporecolhimento', 'integer')
        ->addColumn('k173_receita', 'integer')
        ->addForeignKey('k173_unidadegestora', 'caixa.unidadegestora', 'k171_sequencial', array('constraint'=>'unidadegestoratiporecolhimento_unidadegestora_fk'))
        ->addForeignKey('k173_tiporecolhimento', 'caixa.tiporecolhimento', 'k172_sequencial', array('constraint'=>'unidadegestoratiporecolhimento_tiporecolhimento_fk'))
        ->addForeignKey('k173_receita', 'caixa.tabrec', 'k02_codigo', array('constraint'=>'unidadegestoratiporecolhimento_receita_fk'))
        ->addIndex(array('k173_unidadegestora'), array('name' => 'unidadegestoratiporecolhimento_unidadegestora_in'))
        ->addIndex(array('k173_tiporecolhimento'), array('name' => 'unidadegestoratiporecolhimento_tiporecolhimento_in'))
        ->addIndex(array('k173_receita'), array('name' => 'unidadegestoratiporecolhimento_receita_in'))
        ->addIndex(array('k173_unidadegestora', 'k173_tiporecolhimento'), array('unique'=>true, 'name' => 'unidadegestoratiporecolhimento_unidade_reco_in'))
        ->create();
      $this->execute("ALTER TABLE caixa.unidadegestoratiporecolhimento ALTER COLUMN k173_sequencial SET DEFAULT nextval('caixa.unidadegestoratiporecolhimento_k173_sequencial_seq')");

      /**
       * guiarecolhimento
       */
      $this->execute("CREATE SEQUENCE caixa.guiarecolhimento_k174_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
      $tabela = $this->table('guiarecolhimento',  array('schema'=>'caixa', 'id'=> false, 'primary_key'=>'k174_sequencial', 'constraint'=>'guiarecolhimento_k174_sequencial_pk'));
      $tabela->addColumn('k174_sequencial', 'integer')
        ->addColumn('k174_unidadegestora', 'integer')
        ->addColumn('k174_tiporecolhimento', 'integer')
        ->addColumn('k174_cgm', 'integer')
        ->addColumn('k174_numpre', 'integer')
        ->addColumn('k174_numeroreferencia', 'string', array('limit' => 200, 'null' => true))
        ->addColumn('k174_competencia', 'string', array('limit' => 9))
        ->addColumn('k174_datavencimento', 'date')
        ->addColumn('k174_valor', 'decimal', array('precision'=>20, 'scale' => 2))
        ->addColumn('k174_desconto', 'decimal', array('precision'=>20, 'scale' => 2, 'null' => true, 'default' => '0'))
        ->addColumn('k174_multa', 'decimal', array('precision'=>20, 'scale' => 2, 'null' => true, 'default' => '0'))
        ->addColumn('k174_juros', 'decimal', array('precision'=>20, 'scale' => 2, 'null' => true, 'default' => '0'))
        ->addColumn('k174_outrosacrescimos', 'decimal', array('precision'=>20, 'scale' => 2, 'null' => true, 'default' => '0'))
        ->addColumn('k174_valortotal', 'decimal', array('precision'=>20, 'scale' => 2))
        ->addColumn('k174_processo', 'integer', array('null' => true))
        ->addColumn('k174_outrasdeducoes', 'decimal', array('precision'=>20, 'scale' => 2, 'default' => '0', 'null' => true))
        ->addForeignKey('k174_unidadegestora', 'caixa.unidadegestora', 'k171_sequencial', array('constraint'     => 'guiarecolhimento_unidadegestora_fk'))
        ->addForeignKey('k174_tiporecolhimento', 'caixa.tiporecolhimento', 'k172_sequencial', array('constraint' => 'guiarecolhimento_tiporecolhimento_fk'))
        ->addForeignKey('k174_cgm', 'protocolo.cgm', 'z01_numcgm', array('constraint' => 'guiarecolhimento_cgm_fk'))
        ->addForeignKey('k174_processo', 'protocolo.protprocesso', 'p58_codproc', array('constraint' => 'guiarecolhimento_processo_fk'))
        ->addIndex(array('k174_unidadegestora'), array('name' => 'guiarecolhimento_unidadegestora_in'))
        ->addIndex(array('k174_tiporecolhimento'), array('name' => 'guiarecolhimento_tiporecolhimento_in'))
        ->addIndex(array('k174_cgm'), array('name' => 'guiarecolhimento_cgm_in'))
        ->addIndex(array('k174_numpre'), array('name' => 'guiarecolhimento_numpre_in'))
        ->addIndex(array('k174_processo'), array('name' => 'guiarecolhimento_processo_in'))
        ->create();
      $this->execute("ALTER TABLE caixa.guiarecolhimento ALTER COLUMN k174_sequencial SET DEFAULT nextval('caixa.guiarecolhimento_k174_sequencial_seq')");
    }
    
    private function droparTabelas() 
    {
      $this->execute('drop table if exists caixa.unidadegestoratiporecolhimento');
      $this->execute('drop sequence caixa.unidadegestoratiporecolhimento_k173_sequencial_seq');
      $this->execute('drop table if exists caixa.guiarecolhimento');
      $this->execute('drop sequence caixa.guiarecolhimento_k174_sequencial_seq');
      $this->execute('drop table if exists caixa.unidadegestora');
      $this->execute('drop sequence caixa.unidadegestora_k171_sequencial_seq');
      $this->execute('drop table if exists caixa.tiporecolhimento');
      $this->execute('drop sequence caixa.tiporecolhimento_k172_sequencial_seq');
      
    }
}
