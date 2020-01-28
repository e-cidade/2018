<?php

use Classes\PostgresMigration;

class M5389ObservacaoCalculoParcialIptu extends PostgresMigration
{
  public function up()
  {

    $this->upDDL();
    $this->upDicionarioDados();
  }

  public function down()
  {

    $this->downDicionarioDados();
    $this->downDDL();
  }

  public function upDDL()
  {
    $this->table('iptucalclog',  array('schema'=>'cadastro', 'id'=> false, 'primary_key'=>'j27_codigo', 'constraint'=>'iptucalclog_codi_pk'))->addColumn('j27_observacao', 'text', array('null'=>true))->save();
  }

  public function upDicionarioDados()
  {
    /**
     * Cria campos na tabela db_syscampos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009353,
            'j27_observacao', 'text', 'Campo de observações para cliente informar o que desejar ao fazer recalculo parcial de IPTU. O campo só é mostrado em cálculos parciais. ', '', 'Observação', 1, 't', 't', 'f', 0, 'text', 'Observação')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria vínculos na tabela db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(1320, 1009353, 8, 0)
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();
  }

  public function downDicionarioDados()
  {
    $this->execute("DELETE FROM db_sysarqcamp WHERE codcam = 1009353");
    $this->execute("DELETE FROM db_syscampo WHERE codcam = 1009353");
  }

  public function downDDL()
  {
    $this->table('iptucalclog',  array('schema'=>'cadastro', 'id'=> false, 'primary_key'=>'j27_codigo', 'constraint'=>'iptucalclog_codi_pk'))->removeColumn('j27_observacao')->save();
  }
}
