<?php

use Classes\PostgresMigration;

class M7689ConveniocobrancaAddResponsavelnossonumero extends PostgresMigration
{

    public function up()
    {

        $this->table('conveniocobranca',    array('schema'=>'arrecadacao'))
                ->addColumn('ar13_responsavelnossonumero', 'boolean', array('null' => false, 'default' => 't'))
                ->save();

        $this->table('db_syscampo', array('schema' => 'configuracoes'))
                ->insert(array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel'),
                             array(array(22251,'ar13_responsavelnossonumero','bool','Identifica se a institui��o � respons�vel pela gera��o do nosso n�mero nos recibos.',
                                               'true', 'Respons�vel pela numera��o',1,'f','f','f',5,'text','Respons�vel pela numera��o'))
                             )
                ->saveData();

        $this->table('db_sysarqcamp', array('schema' => 'configuracoes'))
                ->insert(array('codarq', 'codcam', 'seqarq', 'codsequencia'),
                             array(array(2186,22251,12,0))
                         )
                ->saveData();

    }

    public function down()
    {

        $this->execute('DELETE FROM db_sysarqcamp WHERE codarq = 2186 AND codcam = 22251');

        $this->execute('DELETE FROM db_syscampo WHERE codcam = 22251');

        $this->table('conveniocobranca', array('schema' => 'arrecadacao'))
                ->removeColumn('ar13_responsavelnossonumero')
                ->save();
    }
}
