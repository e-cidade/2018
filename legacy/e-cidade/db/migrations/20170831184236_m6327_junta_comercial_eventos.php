<?php

use Classes\PostgresMigration;

class M6327JuntaComercialEventos extends PostgresMigration
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
      array(1010223, 'juntacomercialprotocoloeventos', 'Eventos recebidos pela Junta comercial', 'q148', '2017-08-31', 'Eventos recebidos pela Junta comercial', 0, 'f', 'f', 'f', 'f'),
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
      array(3,1010223)
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009418,'q148_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
      array(1009419,'q148_protocolo ','int4','Protocolo','', 'Protocolo',20,'f','f','f',1,'text','Protocolo'),
      array(1009420,'q148_codevento','int4','Evento','0', 'Evento',3,'f','f','f',1,'text','Evento'),
      array(1009421,'q148_evento','varchar(255)','Descrição','', 'Descrição',255,'f','f','f',0,'text','Descrição')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(1010223,1009418,1,0),
      array(1010223,1009419,2,0),
      array(1010223,1009420,3,0),
      array(1010223,1009421,4,0)
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();


    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000687, 'juntacomercialprotocoloeventos_q148_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010223,1009418,1,1009418),
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave estrangeira
    $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
    $aValues  = array(
      array(1010223,1009419,1,1010222,0)
    );
    $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008223,'juntacomercialprotocoloeventos_protocolo_in',1010223,'0')
      
    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008223,1009419,1)
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000687 where codarq = 1010223 and codcam = 1009418;");
  }
  
  public function criarTabelas() {


    $this->execute("
      CREATE SEQUENCE issqn.juntacomercialprotocoloeventos_q148_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
    ");


    $this->execute("
      CREATE TABLE issqn.juntacomercialprotocoloeventos(
        q148_sequencial		int4 NOT NULL default 0,
        q148_protocolo		int4 NOT NULL default 0,
        q148_codevento		int4 NOT NULL default 0,
        q148_evento		varchar(255) ,
        CONSTRAINT juntacomercialprotocoloeventos_sequ_pk PRIMARY KEY (q148_sequencial));
    ");

    $this->execute("
      ALTER TABLE issqn.juntacomercialprotocoloeventos
      ADD CONSTRAINT juntacomercialprotocoloeventos_protocolo_fk FOREIGN KEY (q148_protocolo)
      REFERENCES issqn.juntacomercialprotocolo;
    ");


    $this->execute("CREATE  INDEX juntacomercialprotocoloeventos_protocolo_in ON issqn.juntacomercialprotocoloeventos(q148_protocolo);");
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {

    $this->execute('delete from configuracoes.db_syscampodef where codcam in(1009418,1009419,1009420,1009421)');
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008223)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008223)');
    $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009418,1009419,1009420,1009421)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000687)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010223)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009418,1009419,1009420,1009421)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009418,1009419,1009420,1009421)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010223)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010223)');    
  }
  
  private function droparDML() {

    $this->execute('DROP TABLE IF EXISTS issqn.juntacomercialprotocoloeventos CASCADE;');
    $this->execute('DROP SEQUENCE IF EXISTS issqn.juntacomercialprotocoloeventos_q148_sequencial_seq;');
  }

}
