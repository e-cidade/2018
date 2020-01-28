<?php

use Classes\PostgresMigration;

class M7962AddColunaInativarCurso extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->ddl();
    }

    private function dicionario()
    {
        // campos
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(
            array(22347,'ed29_ativo','bool','Ativa/Inativa o curso','t', 'Situação',1,'f','f','f',5,'text','Situação')
        );
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os campos as tabelas
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1010048,22347,6,0)
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    public function ddl()
    {
        // altera tabela
        $this->table('cursoedu', array('schema'=>'escola'))
                ->addColumn('ed29_ativo', 'boolean', array('default' => true))
                ->save();
    }

    public function down()
    {
        $this->execute('delete from configuracoes.db_sysarqcamp where codcam in (22347)');
        $this->execute('delete from configuracoes.db_syscampo   where codcam in (22347)');

        $this->table('cursoedu', array('schema'=>'escola'))
             ->removeColumn('ed29_ativo')
             ->save();
    }
}
