<?php

use Classes\PostgresMigration;

class M7326LicitacaoSemDespesa extends PostgresMigration
{
    public function up()
    {
        $this->criarDiscionario();
        $this->criarDml();
        $this->adicionaColuna();
    }


    private function criarDiscionario()
    {
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(array(22362,'l20_tipo','int4','Tipo da licitação: - 1 gera despesa - 2 não gera despesa','1', 'Tipo',10,'f','f','f',1,'text','Tipo'));
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array( array(1260,22362,27,0) );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    private function criarDml()
    {
        $aColumns = array('pc52_sequencial', 'pc52_descricao');
        $aValues  = array( array(8, 'Automática') );
        $table    = $this->table('solicitacaotipo', array('schema' => 'compras'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    public function adicionaColuna()
    {
        $table = $this->table('liclicita', array('schema' => 'licitacao'));
        $table->addColumn('l20_tipo', 'integer', array('default' => 1))
              ->update();
    }

    public function down()
    {

        $this->execute('delete from configuracoes.db_sysarqcamp where codcam in (22362) ');
        $this->execute('delete from configuracoes.db_syscampo   where codcam in (22362) ');

        $this->execute('delete from compras.solicitacaotipo where pc52_sequencial = 8 ');

        $table = $this->table('liclicita', array('schema' => 'licitacao'));
        $table->removeColumn('l20_tipo')
              ->save();
    }
}
