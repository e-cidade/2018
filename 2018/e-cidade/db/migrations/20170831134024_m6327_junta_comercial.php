<?php

use Classes\PostgresMigration;

class M6327JuntaComercial extends PostgresMigration
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
      array (1010222, 'juntacomercialprotocolo', 'Dados recebidos por requisição SOAP da Junta comercial para alterar dados de empresas.', 'q147', '2017-08-31', 'Dados recebidos pela Junta comercial', 0, 'f', 'f', 'f', 'f' )
    );
    $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula modulo
    $aColumns = array('codmod', 'codarq' );
    $aValues  = array(
      /**
      *lista de campos 
      */
      array(3,1010222)
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009409,'q147_sequencial','int4','Sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'),
      array(1009410,'q147_servico','varchar(20)','Identifica o serviço a ser utilizado para processar os dados.','', 'Serviço',20,'f','f','f',0,'text','Serviço'),
      array(1009411,'q147_funcao','int4','Um serviço tem uma ou mais funções de processamento e esse parâmetro identifica o tipo de processo enviado.','0', 'Função',3,'f','f','f',1,'text','Função'),
      array(1009412,'q147_protocolo','varchar(20)','Identificador único do processo.','', 'Protocólo',20,'f','f','f',0,'text','Protocólo'),
      array(1009413,'q147_xml','oid','Dados formatados no padrão XML, contendo as informações do processo.','', 'XML',1,'f','f','f',0,'text','XML'),
      array(1009414,'q147_data','date','Data','null', 'Data',10,'f','f','f',0,'text','Data'),
      array(1009415,'q147_cnpjenvia','varchar(14)','CNPJ da Instituição que envia o processo.','', 'CNPJ Instituição Envia',14,'f','f','f',0,'text','CNPJ Instituição Envia'),
      array(1009416,'q147_cnpjrecebe','varchar(14)','CNPJ da Instituição que recebe o processo.','', 'CNPJ Instituição Recebe',14,'f','f','f',0,'text','CNPJ Instituição Recebe'),
      array(1009417,'q147_cpfcnpjprocesso','varchar(14)','CPF/CNPJ Pessoa Processo','', 'CPF/CNPJ Pessoa Processo',14,'t','f','f',0,'text','CPF/CNPJ Pessoa Processo')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(1010222,1009409,1,0),
      array(1010222,1009410,2,0),
      array(1010222,1009411,3,0),
      array(1010222,1009412,4,0),
      array(1010222,1009413,5,0),
      array(1010222,1009414,6,0),
      array(1010222,1009415,7,0),
      array(1010222,1009416,8,0),
      array(1010222,1009417,9,0),
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();


    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000686, 'juntacomercialprotocolo_q147_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010222,1009409,1,1009409),
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008222,'juntacomercialprotocolo_sequencial_in',1010222,'1')
    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008222,1009409,1)
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000686 where codarq = 1010222 and codcam = 1009409;");
  }
  
  public function criarTabelas() {
    $this->execute("DROP TABLE IF EXISTS juntacomercialprotocolo CASCADE;");
    $this->execute("DROP SEQUENCE IF EXISTS issqn.juntacomercialprotocolo_q147_sequencial_seq;");
    $this->execute("
      CREATE SEQUENCE issqn.juntacomercialprotocolo_q147_sequencial_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;"
    );

    $this->execute("
      CREATE TABLE issqn.juntacomercialprotocolo(
      q147_sequencial		int4 NOT NULL default 0,
      q147_servico		varchar(20) NOT NULL ,
      q147_funcao		int4 NOT NULL default 0,
      q147_protocolo		varchar(20) NOT NULL ,
      q147_xml		oid NOT NULL ,
      q147_data		date NOT NULL default null,
      q147_cnpjenvia		varchar(14) NOT NULL ,
      q147_cnpjrecebe		varchar(14) NOT NULL ,
      q147_cpfcnpjprocesso		varchar(14) ,
      CONSTRAINT juntacomercialprotocolo_sequ_pk PRIMARY KEY (q147_sequencial));
    ");

    $this->execute("CREATE UNIQUE INDEX juntacomercialprotocolo_sequencial_in ON juntacomercialprotocolo(q147_sequencial);");
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {

    $this->execute('delete from configuracoes.db_syscampodef where codcam in(1009409, 1009410, 1009411, 1009412, 1009413, 1009414, 1009415, 1009416, 1009417)');
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008222)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008222)');
    $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009409, 1009410, 1009411, 1009412, 1009413, 1009414, 1009415, 1009416, 1009417)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000686)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010222)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009409, 1009410, 1009411, 1009412, 1009413, 1009414, 1009415, 1009416, 1009417)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009409, 1009410, 1009411, 1009412, 1009413, 1009414, 1009415, 1009416, 1009417)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010222)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010222)');
  }
  
  private function droparDML() {
    $this->execute('DROP TABLE IF EXISTS issqn.juntacomercialprotocolo CASCADE;');
    $this->execute('DROP SEQUENCE IF EXISTS issqn.juntacomercialprotocolo_q147_sequencial_seq;');
  }

}
