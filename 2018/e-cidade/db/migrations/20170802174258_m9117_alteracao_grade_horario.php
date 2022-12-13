<?php

use Classes\PostgresMigration;

class M9117AlteracaoGradeHorario extends PostgresMigration
{

    public function up()
    {
        $this->criaDicionario();

        $regenciaHorario = $this->table('regenciahorario', array('schema' => 'escola'));
        $regenciaHorario->addColumn('ed58_datainicio', 'date', array('null' => true))
                        ->addColumn('ed58_datafim', 'date', array('null' => true))
                        ->save();

        $this->migracao();


    }

    public function down()
    {
        $this->excluiDicionario();

        $regenciaHorario = $this->table('regenciahorario', array('schema' => 'escola'));
        $regenciaHorario->removeColumn('ed58_datainicio')
                        ->removeColumn('ed58_datafim')
                        ->save();



    }

    private function criaDicionario()
    {
        // campos
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(
            array(1009380,'ed58_datainicio','date','Data em que o professor foi vinculado na grade de horários da turma','null', 'Data de Início',10,'t','f','f',1,'text','Data de Início'),
            array(1009381,'ed58_datafim','date','Data em que o professor foi desvinculado na grade de horários da turma','null', 'Data de Fim',10,'t','f','f',1,'text','Data de Fim'),
        );
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os campos as tabelas
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1010099,1009380,8,0),
            array(1010099,1009381,9,0),
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

    }

    private function migracao()
    {
        $this->execute('update escola.regenciahorario
                           set ed58_datainicio = ed52_d_inicio, ed58_datafim = ed52_d_fim
                          from regencia
                          join turma      on ed57_i_codigo = ed59_i_turma
                          join calendario on ed52_i_codigo = ed57_i_calendario
                         where ed58_i_regencia = ed59_i_codigo
                           and ed58_ativo is true;');
    }

    private function excluiDicionario()
    {
        $this->execute('delete from configuracoes.db_sysarqcamp   where codarq = 1010099 and codcam in (1009380, 1009381)');
        $this->execute('delete from configuracoes.db_syscampo   where codcam in (1009380, 1009381) ');
    }
}
