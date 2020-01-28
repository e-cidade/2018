<?php

use Classes\PostgresMigration;

class M5489AddColunasAcervo extends PostgresMigration
{

    public function up()
    {
        // campos
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(
            array(22345,'bi06_titulooriginal','varchar(100)','Título Original da obra','', 'Título Original', 100,'t','t','f',0,'text','Título Original'),
            array(22346,'bi06_subtitulo',     'varchar(100)','Subtítulo da obra',      '', 'Subtítulo',       100,'t','t','f',0,'text','Subtítulo')
        );
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os campos as tabelas
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1008014,22346,16,0),
            array(1008014,22345,17,0)
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();



        // altera tabela

        $this->table('acervo',    array('schema'=>'biblioteca'))
                ->addColumn('bi06_titulooriginal', 'string', array('null' => true, 'limit' => '100'))
                ->addColumn('bi06_subtitulo',      'string', array('null' => true, 'limit' => '100'))
                ->save();

    }

    public function down()
    {
        $this->execute('delete from configuracoes.db_sysarqcamp where codcam in (22345, 22346)');
        $this->execute('delete from configuracoes.db_syscampo   where codcam in (22345, 22346)');

        $this->table('acervo', array('schema' => 'biblioteca'))
             ->removeColumn('bi06_titulooriginal')
             ->removeColumn('bi06_subtitulo')
             ->save();
    }
}
