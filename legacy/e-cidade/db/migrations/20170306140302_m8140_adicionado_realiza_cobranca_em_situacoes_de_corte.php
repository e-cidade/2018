
<?php

use Classes\PostgresMigration;

class M8140AdicionadoRealizaCobrancaEmSituacoesDeCorte extends PostgresMigration
{
  public function up() {

    $oSysCampoTable = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $aSysCampoData = array(
      'codcam'       => 22399,
      'nomecam'      => 'x43_realizacobranca',
      'conteudo'     => 'bool',
      'descricao'    => 'Realiza Cobrança de Tarifas',
      'valorinicial' => '1',
      'rotulo'       => 'Realiza Cobrança',
      'tamanho'      => 1,
      'nulo'         => 'true',
      'maiusculo'    => 'false',
      'autocompl'    => 'false',
      'aceitatipo'   => 5,
      'tipoobj'      => 'text',
      'rotulorel'     => 'Realiza Cobrança',
    );
    $oSysCampoTable->insert(array_keys($aSysCampoData), array(array_values($aSysCampoData)));
    $oSysCampoTable->save();

    $oSysArqCampTable = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $aSysArqCampData = array(
      'codarq'       => 1457,
      'codcam'       => 22399,
      'seqarq'       => 4,
      'codsequencia' => 0,
    );
    $oSysArqCampTable->insert(array_keys($aSysArqCampData), array(array_values($aSysArqCampData)));
    $oSysArqCampTable->save();

    $oAguaCorteSituacao = $this->table('aguacortesituacao', array('schema' => 'agua'));
    $oAguaCorteSituacao->addColumn('x43_realizacobranca', 'boolean', array(
      'null' => true,
      'default' => true,
    ));
    $oAguaCorteSituacao->save();
  }

  public function down() {

    $this->execute('delete from configuracoes.db_sysarqcamp where codarq = 1457 and codcam = 22399');
    $this->execute('delete from configuracoes.db_syscampo   where codcam = 22399');

    $oAguaCorteSituacao = $this->table('aguacortesituacao', array('schema' => 'agua'));
    $oAguaCorteSituacao->removeColumn('x43_realizacobranca');
    $oAguaCorteSituacao->save();
  }
}
