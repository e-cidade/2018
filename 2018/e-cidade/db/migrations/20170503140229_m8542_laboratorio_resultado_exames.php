<?php

use Classes\PostgresMigration;

class M8542LaboratorioResultadoExames extends PostgresMigration
{
    public function up()
    {
        $this->criarDiscionario();
        $this->estrutura();
    }

    private function estrutura()
    {
        $table = $this->table('lab_resultadoitem', array('schema'=>'laboratorio'));
        $table->addColumn('la39_titulacao', 'text',  array('null'=> true ))
              ->update();
    }

    private function criarDiscionario() {

        $this->execute("update db_syscampo set maiusculo = 'f' where codcam = 16493;");

        // campo
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(
            array(1009270,'la39_titulacao','text','Titulação lançada no atributo do exame.','', 'Titulação',1,'t','f','f',0,'text','Titulação'),
        );
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula o campo a tabela
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(2897, 1009270, 4, 0)
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    public function down()
    {
        $this->execute('delete from configuracoes.db_sysarqcamp where codcam in (1009270) ');
        $this->execute('delete from configuracoes.db_syscampo   where codcam in (1009270) ');
        $this->execute("update db_syscampo set maiusculo = 't' where codcam = 16493;");

        $table = $this->table('lab_resultadoitem', array('schema'=>'laboratorio'));
        $table->removeColumn('la39_titulacao')
              ->save();
    }
}
