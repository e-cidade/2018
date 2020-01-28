<?php

use Classes\PostgresMigration;

class M7676PrevidenciaComplementar extends PostgresMigration
{
   public function up() 
   {
      $this->table('rhrubricas', array('schema'=>'pessoal'))
           ->addColumn('rh27_previdenciacomplementar', 'integer', array('null' => true))
           ->save();

      $aSyscampo = array(
        'codcam'       => 22305,
        'nomecam'      => 'rh27_previdenciacomplementar',
        'conteudo'     => 'int4',
        'descricao'    => 'Número do CGM que corresponde a previdência complementar.',
        'valorinicial' => '0',
        'rotulo'       => 'Previdência Complementar',
        'tamanho'      => 10,
        'nulo'         => 'f',
        'maiusculo'    => 'f',
        'autocompl'    => 'f',
        'aceitatipo'   => 1,
        'tipoobj'      => 'text',
        'rotulorel'    => 'Previdência Complementar'
      );

      $this->table('db_syscampo', array('schema'=>'configuracoes'))
           ->insert(array_keys($aSyscampo), array(array_values($aSyscampo)))
           ->saveData();

      $aSysarqcamp = array (
        'codarq'       => 1177,
        'codcam'       => 22305,
        'seqarq'       => 31,
        'codsequencia' => 0
      );
 
      $this->table('db_sysarqcamp', array('schema'=>'configuracoes'))
           ->insert(array_keys($aSysarqcamp), array(array_values($aSysarqcamp)))
           ->saveData();
   }

   public function down() 
   {
      $this->table('rhrubricas', array('schema'=>'pessoal'))
           ->removeColumn('rh27_previdenciacomplementar')
           ->save();

      $this->execute('delete from db_sysarqcamp where codcam = 22305');
      $this->execute('delete from db_syscampo where codcam = 22305');
   }
}
