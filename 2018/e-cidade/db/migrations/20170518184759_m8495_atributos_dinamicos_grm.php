<?php

use Classes\PostgresMigration;

class M8495AtributosDinamicosGrm extends PostgresMigration
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
  }
  
  public function down() {
    
    $this->removerDicionarioDados();
    $this->droparDML();
  }
  
  public function addDicionarioDados()
  {

    /**
     * Cria tabelas
     */
    $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
    $aValues  = array(
      array(1010197, 'tiporecolhimentoatributosdinamicos', 'Atributos dinamicos da guia', 'k176', '2017-05-18', 'Atributos dinamicos da guia', 0, 'f', 'f', 'f', 'f'),      
    );
    $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula modulo
    $aColumns = array('codmod', 'codarq' );
    $aValues  = array(
      array(5,1010197)      
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009284 ,'k176_sequencial' ,'int4' ,'Atributos dinamicos da guia' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial'),     
      array(1009285 ,'k176_tiporecolhimento' ,'int4' ,'Atributos dinamicos da guia' ,'' ,'Tipo do Recolhimento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo do Recolhimento'),     
      array(1009286 ,'k176_db_cadattdinamico' ,'int4' ,'Atributo Dinamico' ,'' ,'Atributo Dinamico' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Atributo Dinamico'),     
      array(1009287,'db109_obrigatorio','bool','Preenchimento Obrigatório','false', 'Preenchimento Obrigatório',1,'t','f','f',5,'text','Preenchimento Obrigatório'),     
      array(1009288 ,'k174_atributodinamicovalor' ,'int4' ,'Grupo de valores Dinamicos' ,'null' ,'Grupo de valores Dinamicos' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Grupo de valores Dinamicos'),     

    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(1010197, 1009284 ,1 ,0),      
      array(1010197, 1009285 ,2 ,0),      
      array(1010197, 1009286 ,3 ,0),     
      array(3163,    1009287,8,0),
      array(4033,    1009288 ,17 ,0),     
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();


    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000663, 'tiporecolhimentoatributosdinamicos_k176_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),      
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010197,1009284,1,1009285),      
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave estrangeira
    $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
    $aValues  = array(
      array(1010197,1009285,1,4031,0),
      array(1010197,1009286,1,3162,0),
      array(4033,1009288,1,3165,0),      
    );
    $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008191,'tiporecolhimentoatributosdinamicos_in',1010197,'0'),
      array(1008192,'tiporecolhimentoatributosdinamicos_db_cadattdinamico_in',1010197,'0'),
      array(1008193,'guiarecolhimento_atributovalorgrupo_in',4033,'0'),
      
    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008191,1009285,1),      
      array(1008192,1009286,1),      
      array(1008193,1009288,1),      
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000663 where codarq = 1010197 and codcam = 1009284");
  }
  
  public function criarTabelas() {


    $this->execute("CREATE SEQUENCE caixa.tiporecolhimentoatributosdinamicos_k176_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
    $tabela = $this->table('tiporecolhimentoatributosdinamicos',  array('schema'=>'caixa', 'id'=> false, 'primary_key'=>'k176_sequencial', 'constraint'=>'tiporecolhimentoatributosdinamicos_k176_sequencial_pk'));
    $tabela->addColumn('k176_sequencial', 'integer')
          ->addColumn('k176_tiporecolhimento', 'integer')
          ->addColumn('k176_db_cadattdinamico', 'integer')
          ->addForeignKey('k176_tiporecolhimento', 'caixa.tiporecolhimento', 'k172_sequencial', array('constraint'=>'tiporecolhimentoatributosdinamicos_tiporecolhimento_fk'))
          ->addForeignKey('k176_db_cadattdinamico', 'configuracoes.db_cadattdinamico', 'db118_sequencial', array('constraint'=>'tiporecolhimentoatributosdinamicos_cadattdinamico_fk'))
          ->addIndex(array('k176_tiporecolhimento'), array('name' => 'tiporecolhimentoatributosdinamicos_in'))
          ->addIndex(array('k176_db_cadattdinamico'), array('name' => 'tiporecolhimentoatributosdinamicos_db_cadattdinamico_in'))
          ->create();
    $this->execute("ALTER TABLE caixa.tiporecolhimentoatributosdinamicos ALTER COLUMN k176_sequencial SET DEFAULT nextval('caixa.tiporecolhimentoatributosdinamicos_k176_sequencial_seq')");     
    $this->execute('alter table caixa.guiarecolhimento add k174_atributodinamicovalor integer');
    $this->execute('create index guiarecolhimento_atributovalorgrupo_in on caixa.guiarecolhimento(k174_atributodinamicovalor)');
    $this->execute("alter table caixa.guiarecolhimento add constraint guiarecolhimento_atributodinamicovalor_fk FOREIGN KEY (k174_atributodinamicovalor) references db_cadattdinamicovalorgrupo(db120_sequencial)");
    $this->execute('alter table configuracoes.db_cadattdinamicoatributos add  db109_obrigatorio BOOLEAN default false');
    
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {

    $this->execute('delete from configuracoes.db_syscampodef where codcam in(1009284,1009285,1009286,1009287,1009288)');
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008191,1008192,1008193)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008191,1008192,1008193)');
    $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009284,1009285,1009286,1009287,1009288)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000663)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010197)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009284,1009285,1009286,1009287,1009288)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009284,1009285,1009286,1009287,1009288)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010197)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010197)');    
  }
  
  private function droparDML() {

    $this->execute('drop table if exists caixa.tiporecolhimentoatributosdinamicos');
    $this->execute('drop sequence caixa.tiporecolhimentoatributosdinamicos_k176_sequencial_seq');
    $this->execute('alter table configuracoes.db_cadattdinamicoatributos drop db109_obrigatorio');
    $this->execute('alter table  caixa.guiarecolhimento drop k174_atributodinamicovalor');
    
  }

}
