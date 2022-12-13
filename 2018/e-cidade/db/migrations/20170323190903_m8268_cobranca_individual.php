<?php

use Classes\PostgresMigration;

class M8268CobrancaIndividual extends PostgresMigration
{
  public function up() {

    $oSysCampoTable = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $aSysCampoData = array(
      'codcam'       => 22419,
      'nomecam'      => 'x54_responsavelpagamento',
      'conteudo'     => 'int4',
      'descricao'    => 'Responsável pelo pagamento',
      'valorinicial' => 'null',
      'rotulo'       => 'Responsável pelo pagamento',
      'tamanho'      => 2,
      'nulo'         => 'true',
      'maiusculo'    => 'false',
      'autocompl'    => 'false',
      'aceitatipo'   => 1,
      'tipoobj'      => 'text',
      'rotulorel'     => 'Responsável pelo pagamento',
    );
    $oSysCampoTable->insert(array_keys($aSysCampoData), array(array_values($aSysCampoData)));
    $oSysCampoTable->save();

    $oSysArqCampTable = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $aSysArqCampData = array(
      'codarq'       => 3966,
      'codcam'       => 22419,
      'seqarq'       => 12,
      'codsequencia' => 0,
    );
    $oSysArqCampTable->insert(array_keys($aSysArqCampData), array(array_values($aSysArqCampData)));
    $oSysArqCampTable->save();

    $oAguaContrato = $this->table('aguacontrato', array('schema' => 'agua'));
    $oAguaContrato->addColumn('x54_responsavelpagamento', 'integer', array(
      'null' => true,
      'default' => 0,
    ));
    $oAguaContrato->save();
  }

  public function down() {

    $this->execute('delete from configuracoes.db_sysarqcamp where codarq = 3966 and codcam = 22419');
    $this->execute('delete from configuracoes.db_syscampo where codcam = 22419');

    $oAguaContrato = $this->table('aguacontrato', array('schema' => 'agua'));
    $oAguaContrato->removeColumn('x54_responsavelpagamento');
    $oAguaContrato->save();
  }
}
