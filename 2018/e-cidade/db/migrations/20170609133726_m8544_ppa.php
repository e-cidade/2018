<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
use Classes\PostgresMigration;

class M8544Ppa extends PostgresMigration
{

  public function up()
  {

    $this->addDicionarioDados();
    $this->alterarTabelasUp();
    $this->atualizadaAnoFinalIniciativa();

    $this->execute("update orcamento.orciniciativa set o147_anofinal = o147_ano;");
  }

  public function down() {

    $this->removerDicionarioDados();
    $this->alterarTabelasDown();
  }

  public function addDicionarioDados()
  {

    /**
     * Cria tabelas
     */
    $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
    $aValues  = array(
      array(1010207, 'orcmetaindices', 'Tabela que contem os índices por ano relacionados a uma meta.', 'o154', '2017-06-09', 'orcmetaindices', 0, 'f', 'f', 'f', 'f'),
    );
    $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula modulo
    $aColumns = array('codmod', 'codarq' );
    $aValues  = array(
      array(35,1010207)
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009322,'o147_anofinal','int4','Ano final da iniciativa.','0', 'Ano Final',10,'t','f','f',1,'text','Ano Final'),
      array(1009330,'o154_sequencial','int8','Código do Índice no ano referente a uma Meta.','0', 'Código do Índice',10,'f','f','f',1,'text','Código do Índice'),
      array(1009331,'o154_orcmeta','int8','Código da Meta','0', 'Código da Meta',10,'f','f','f',1,'text','Código da Meta'),
      array(1009332,'o154_ano','int4','Ano do índice relacionado a meta.','0', 'Ano',10,'f','f','f',1,'text','Ano'),
      array(1009333,'o154_indice','int4','Índice relacionado a Meta.','0', 'Índice',10,'f','f','f',1,'text','Índice'),
      array(1009334,'o154_unidademedida','varchar(20)','Unidade de Medida do índice relacionado a Meta.','', 'Unidade de Medida',20,'t','t','f',0,'text','Unidade de Medida')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(3562,1009322,6,0),
      array(1010207,1009330,1,0),
      array(1010207,1009331,2,0),
      array(1010207,1009332,3,0),
      array(1010207,1009333,4,0),
      array(1010207,1009334,5,0)
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000672, 'orcmetaindices_o154_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010207,1009330,1,1009331),
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave estrangeira
    $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
    $aValues  = array(
      array(1010207,1009331,1,3560,0),
    );
    $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008201,'orcmetaindices_orcmeta_in',1010207,'0'),
    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008201, 1009331, 0),
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000672 where codarq = 1010207 and codcam = 1009330");
  }

  public function alterarTabelasUp() {

    $tableOrcIniciativa = $this->table('orciniciativa', array('schema' => 'orcamento'));
    $tableOrcIniciativa->addColumn('o147_anofinal', 'integer', array('null' => true))
                       ->save();

    $aParametrosOrcMetaIndices = array(
      'schema'      => 'orcamento',
      'id'          => false,
      'primary_key' => array('o154_sequencial'),
      'constraint'  =>'orcmetaindices_sequencial_pk'
    );

    $this->execute("CREATE SEQUENCE orcamento.orcmetaindices_o154_sequencial_seq");
    $tableOrcMetaIndices = $this->table('orcmetaindices', $aParametrosOrcMetaIndices);
    $tableOrcMetaIndices->addColumn('o154_sequencial',    'integer', array('null' => false))
                        ->addColumn('o154_orcmeta',       'integer', array('null' => false))
                        ->addColumn('o154_ano',           'integer', array('null' => false))
                        ->addColumn('o154_indice',        'integer', array('null' => false))
                        ->addColumn('o154_unidademedida', 'string', array('null' => true, 'limit' => 20))
                        ->addForeignKey('o154_orcmeta', 'orcamento.orcmeta', 'o145_sequencial', array('constraint' => 'orcmetaindices_orcmeta_fk'))
                        ->addIndex(
                          array('o154_orcmeta'),
                          array('name' => 'orcmetaindices_orcmeta_in'))
                        ->create();

    $this->execute("ALTER TABLE orcmetaindices ALTER COLUMN o154_sequencial SET DEFAULT nextval('orcmetaindices_o154_sequencial_seq')");
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008201)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008201)');
    $this->execute('delete from configuracoes.db_sysforkey where codarq = 1010207 and codcam in(1009331)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000672)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010207)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009322, 1009330, 1009331, 1009332, 1009333, 1009334)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009322, 1009330, 1009331, 1009332, 1009333, 1009334)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010207)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010207)');
  }

  private function alterarTabelasDown() {

    $tableOrcIniciativa = $this->table('orciniciativa', array('schema' => 'orcamento'));
    $tableOrcIniciativa->removeColumn('o147_anofinal')
                       ->save();

    $tableOrcMetaIndices = $this->table('orcmetaindices', array('schema' => 'orcamento'));
    $tableOrcMetaIndices->drop();

    $this->execute('drop sequence orcmetaindices_o154_sequencial_seq');
  }

  private function atualizadaAnoFinalIniciativa() {
    $this->execute("update orciniciativa set o147_anofinal = o147_ano where o147_anofinal is null;");
  }
}
