<?php

use Classes\PostgresMigration;

class M8268AdicionadoCampoEconomiaEmAguaCalculo extends PostgresMigration
{
  public function up() {

    $oSysCampoTable = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $aSysCampoData = array(
      'codcam'       => 22423,
      'nomecam'      => 'x22_aguacontratoeconomia',
      'conteudo'     => 'int4',
      'descricao'    => 'Economia',
      'valorinicial' => 'null',
      'rotulo'       => 'Economia',
      'tamanho'      => 10,
      'nulo'         => 'true',
      'maiusculo'    => 'false',
      'autocompl'    => 'false',
      'aceitatipo'   => 1,
      'tipoobj'      => 'text',
      'rotulorel'     => 'Economia',
    );
    $oSysCampoTable->insert(array_keys($aSysCampoData), array(array_values($aSysCampoData)));
    $oSysCampoTable->save();

    $oSysArqCampTable = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $aSysArqCampData = array(
      'codarq'       => 1443,
      'codcam'       => 22423,
      'seqarq'       => 14,
      'codsequencia' => 0,
    );
    $oSysArqCampTable->insert(array_keys($aSysArqCampData), array(array_values($aSysArqCampData)));
    $oSysArqCampTable->save();

    $oSysForKey = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $aSysForKey = array(
      'codarq'     => 1443,
      'codcam'     => 22423,
      'sequen'     => 1,
      'referen'    => 3983,
      'tipoobjrel' => 0,
    );
    $oSysForKey->insert(array_keys($aSysForKey), array(array_values($aSysForKey)));
    $oSysForKey->save();

    $oAguaCalculo = $this->table('aguacalc', array('schema' => 'agua'));
    $oAguaCalculo->addColumn('x22_aguacontratoeconomia', 'integer', array(
      'null'    => true,
      'default' => null,
    ));
    $oAguaCalculo->addForeignKey('x22_aguacontratoeconomia', 'agua.aguacontratoeconomia', 'x38_sequencial', array(
      'delete'     => 'NO_ACTION',
      'update'     => 'NO_ACTION',
      'constraint' => 'aguacalc_aguacontratoeconomia_fk'
    ));
    $oAguaCalculo->save();
  }

  public function down() {

    $this->execute('delete from configuracoes.db_sysforkey where codarq = 1443 and codcam = 22423');
    $this->execute('delete from configuracoes.db_sysarqcamp where codarq = 1443 and codcam = 22423');
    $this->execute('delete from configuracoes.db_syscampo where codcam = 22423');

    $oAguaCalculo = $this->table('aguacalc', array('schema' => 'agua'));
    $oAguaCalculo->removeColumn('x22_aguacontratoeconomia');
    $oAguaCalculo->save();
  }
}
