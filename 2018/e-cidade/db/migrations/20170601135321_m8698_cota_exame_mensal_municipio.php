<?php

/**
*
*  Migration M8698CotaExameMensalMunicipio
*  da tabela grupomunicipio
*/

use Classes\PostgresMigration;

class M8698CotaExameMensalMunicipio extends PostgresMigration
{
    /**
     * Migrate Up
     *
     */
    public function up()
    {
      $this->criarMenu();
      $this->addDicionarioDados();
      $this->criarTabela();
    }
    /**
     * Migrate Down
     *
     */
    public function down()
    {
      $this->removeritensMenu();
      $this->removerDicionarioDados();
      $this->removerTabela();
    }

    /**
    *  Criar menu Cota de Exames Municípais
    */
    public function criarMenu()
    {


      $aColumns   =  array('id_item' ,'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente');
      $aValues    =  array(
          array(10424 ,'Cota de Exames Municipais' ,'Cota de Exames a serem realizados pelo município' ,'age4_municipiocotamensalexame001.php' ,'1' ,'1' ,'Cota de Exames a serem realizados pelo município' ,'true')
      );
      $table      = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       * Vincular itensMenu ao pai db_menu
       */
      $aColumns   =    array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
      $aValues    =    array(
          array(32 ,10424 ,480 ,6952)
      );
      $table      =  $this->table('db_menu', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *  Update tabela db_itensmenu
       */
      $this->execute("update db_itensmenu set id_item = 10424 , descricao = 'Cota de Exames Municípais' , help = 'Cota de Exames a serem realizados pelo município' , funcao = 'age4_municipiocotamensalexame001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Cota de Exames a serem realizados pelo município' , libcliente = 'true' where id_item = 10424;");
    }

    public function addDicionarioDados()
    {
     /**
       *
       * Cria campos db_syscampo
       *
       */
      $aColumns  = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
      $aValues   = array(
        array(1009319,'age04_sequencial','int4','Código sequencial da tabela grupomunicipio','0', 'Código sequencial',10,'f','f','f',1,'text','Código sequencial'),
        array(1009320,'age04_grupoexame','int4','Código sequencial da tabela grupoexame','0', 'Código do grupoexame',10,'f','f','f',1,'text','Código do grupoexame'),
        array(1009321,'age04_procedimento','int4','Código procedimento da tabela sau_procedimento','0', 'Código procedimento',10,'f','f','f',1,'text','Código procedimento')
      );
      $table     = $this->table('db_syscampo', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       * Update db_syscampo
       */
      $this->execute("update db_syscampo set nomecam = 'age01_quantidade', conteudo = 'int4', descricao = 'Campo de quantidade de exames', valorinicial = '0', rotulo = 'Quantidade', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Quantidade' where codcam = 1009269");

      /**
       * Campos db_sysarquivo
       */
      $aColumns  = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
      $aValues   = array(
        array(1010204, 'grupomunicipio', 'Cota de exame mensal por município', 'age04', '2017-06-01', 'Cota do Município', 0, 'f', 'f', 'f', 'f')
      );
      $table     = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       * Campos db_sysarqmod
       */
      $aColumns  =  array('codmod', 'codarq');
      $aValues   =  array(
        array(30,1010204)
        );
      $table     =  $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *
       * Campos db_sysarqcamp
       *
       */
      $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
      $aValues  = array(
        array(1010204,1009319,1,0),
        array(1010204,1009320,2,0),
        array(1010204,1009321,3,0)
      );
      $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *
       * Campos db_sysprikey
       *
       */
      $aColumns = array('codarq', 'codcam','sequen', 'camiden');
      $aValues  = array(
        array(1010204,1009319,1,1009319)
      );
      $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();


      /**
       *
       *  Campos  db_sysforkey
       *
       */
      $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
      $aValues  = array(
      array(1010204,1009320,1,1010204,0),
      array(1010204,1009321,2,1010204,0),
      array(1010204,1009321,1,1988,0),
      array(1010204,1009320,1,1010195,0)
      );
      $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *
       * Campos db_sysindices
       *
       */
      $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
      $aValues  = array(
        array(1008200,'grupomunicipio_sequencial_in',1010204,'1')
      );
      $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *
       * Campos db_syscadind
       *
       */
        $aColumns   = array('codind', 'codcam', 'sequen');
        $aValues    = array(
          array(1008200,1009318,1)
        );
        $table      =  $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

      /**
       *
       * Campos db_syssequencia
       *
       */
      $aColumns   = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
      $aValues    = array(
        array(1000670, 'grupomunicipio_age04_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
      );
      $table      =  $this->table('db_syssequencia', array('schema' => 'configuracoes'));
      $table->insert($aColumns, $aValues);
      $table->saveData();

      /**
       *  Update
       */
      $this->execute("update configuracoes.db_sysarqcamp set codsequencia = 1000670 where codarq = 1010204 and codcam = 1009319");
    }

    /**
     * Criar tabelas
     */
    public function criarTabela()
    {

      $this->execute("CREATE SEQUENCE agendamento.grupomunicipio_age04_sequencial_seq");
      $grupomunicipio = $this->table('grupomunicipio', array('schema' => 'agendamento', 'id' => false, 'primary_key' => 'age04_sequencial', 'constraint' => 'agendamento.age04_sequencial_pk'));
      $grupomunicipio->addColumn('age04_sequencial',   'integer',   array('null'      => false))
                     ->addColumn('age04_grupoexame',   'integer',   array('null'      => false))
                     ->addColumn('age04_procedimento', 'integer',   array('default'   => 0))
                     ->addForeignKey('age04_grupoexame', 'agendamento.grupoexame', 'age02_sequencial', array('constraint'=>'grupomunicipio_grupoexame_fk'))
                     ->addForeignKey('age04_procedimento', 'ambulatorial.sau_procedimento', 'sd63_i_codigo', array('constraint'=>'grupomunicipio_procedimento_fk'))
                     ->addIndex(array('age04_sequencial'), array('name' => 'grupomunicipio_sequencial_in'))
                     ->create();

      $this->execute("ALTER TABLE agendamento.grupomunicipio ALTER COLUMN age04_sequencial SET DEFAULT nextval('agendamento.grupomunicipio_age04_sequencial_seq')");
    }

    /**
     *  Remover Itens Menu
     */
    public function removeritensMenu()
    {
      $this->execute("delete from configuracoes.db_itensmenu where id_item = 10424");
      $this->execute("delete from configuracoes.db_menu where id_item_filho = 10424 AND modulo = 6952;");
    }

   /**
   * Remove dados do dicionario de dados
   */
    public function removerDicionarioDados()
    {

      $this->execute("delete from configuracoes.db_sysarqcamp where codcam in (1009319,1009320,1009321)");
      $this->execute('delete from configuracoes.db_syscadind where codind in(1008200)');
      $this->execute('delete from configuracoes.db_sysindices where codind in(1008200)');
      $this->execute('delete from configuracoes.db_sysforkey where  codarq = 1010204 ');
      $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000670)');
      $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010204)');
      $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010204)');
      $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010204)');
      $this->execute("delete from configuracoes.db_syscampo where codcam in (1009319,1009320,1009321)");
    }

    /**
     * Remover tabela grupomunicipio
     */
    public function removerTabela()
    {

      $this->execute("DROP TABLE IF EXISTS grupomunicipio CASCADE;");
      $this->execute("DROP SEQUENCE IF EXISTS grupomunicipio_age04_sequencial_seq;");
    }
}
