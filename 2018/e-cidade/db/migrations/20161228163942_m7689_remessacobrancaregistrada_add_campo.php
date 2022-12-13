<?php

use Classes\PostgresMigration;

class M7689RemessacobrancaregistradaAddCampo extends PostgresMigration
{
    public function up()
    {
        /* Cria campo para salvar os arquivos da remessa */
        $this->execute('ALTER TABLE caixa.remessacobrancaregistrada ADD COLUMN k147_arquivoremessa OID');

        $this->table('db_syscampo', array('schema' => 'configuracoes'))
                ->insert(array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel'),
                             array(array(22306,'k147_arquivoremessa','oid','Arquivo da Remessa',
                                               '', 'Arquivo da Remessa',1,'t','f','f',0,'text','Arquivo da Remessa'))
                             )
                ->saveData();

        $this->table('db_sysarqcamp', array('schema' => 'configuracoes'))
                ->insert(array('codarq', 'codcam', 'seqarq', 'codsequencia'),
                             array(array(3981,22306,7,0))
                         )
                ->saveData();


    }

    public function down()
    {

        $this->execute('DELETE FROM db_sysarqcamp WHERE codarq = 3981 AND codcam = 22306');

        $this->execute('DELETE FROM db_syscampo WHERE codcam = 22306');

        $this->table('remessacobrancaregistrada', array('schema' => 'caixa'))
                ->removeColumn('k147_arquivoremessa')
                ->save();
    }
}
