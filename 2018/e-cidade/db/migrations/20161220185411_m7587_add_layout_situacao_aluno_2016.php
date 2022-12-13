<?php

use Classes\PostgresMigration;

class M7587AddLayoutSituacaoAluno2016 extends PostgresMigration
{
    public function up()
    {
        // Cadastro do layout
        $aColumns = array('db50_codigo', 'db50_layouttxtgrupo', 'db50_descr', 'db50_quantlinhas', 'db50_obs');
        $aVaues   = array(array(277 ,2 ,'SITUAÇÃO DO ALUNO 2016' ,0 ,'Layout para importação e exportação da situação do aluno de 2016'));

        $table = $this->table('db_layouttxt', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aVaues);
        $table->saveData();

        // linhas do layout
        $aColumns = array('db51_codigo', 'db51_layouttxt', 'db51_descr', 'db51_tipolinha', 'db51_tamlinha', 'db51_linhasantes', 'db51_linhasdepois', 'db51_obs', 'db51_separador', 'db51_compacta');
        $aVaues   = array(
            array(890, 277, 'REGISTRO 89', 3, 172, 0, 0, '', '|', '0'),
            array(891, 277, 'REGISTRO 90', 3, 85,  0, 0, '', '|', '0'),
            array(892, 277, 'REGISTRO 91', 3, 89,  0, 0, '', '|', '0')
        );

        $table = $this->table('db_layoutlinha', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aVaues);
        $table->saveData();

        // Campos das linhas do layout
        $aColumns = array('db52_codigo', 'db52_layoutlinha', 'db52_nome', 'db52_descr', 'db52_layoutformat', 'db52_posicao', 'db52_default', 'db52_tamanho', 'db52_ident', 'db52_imprimir', 'db52_alinha', 'db52_obs', 'db52_quebraapos');
        $aVaues   = array(
            array(15343, 890, 'tipo_registro',      'TIPO DE REGISTRO',        14 ,1 ,'89' ,2 ,'t' ,'t' ,'e' ,'identifica o registro' ,0),
            array(15344, 890, 'codigo_escola_inep', 'CÓDIGO DA ESCOLA INEP',   14 ,3 ,''   ,8 ,'f' ,'t' ,'d' ,'' ,0),
            array(15345, 890, 'cpf_gestor',         'CPF DO GESTOR DA ESCOLA', 14 ,11 ,'' ,11 ,'f' ,'t' ,'d' ,'' ,0),
            array(15346, 890, 'nome_gestor',        'NOME DO GESTOR',          14 ,22 ,'' ,100 ,'f' ,'t' ,'d' ,'' ,0),
            array(15347, 890, 'cargo_gestor',       'CARGO DO GESTOR ESCOLAR', 14 ,122 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0),
            array(15348, 890, 'email_gestor',       'EMAIL DO GESTOR',         14 ,123 ,'' ,50 ,'f' ,'t' ,'d' ,'' ,0),
            array(15349, 891, 'tipo_registro',        'TIPO DE REGISTRO',          14 ,1 ,'90' ,2 ,'t' ,'t' ,'e' ,'identifica o registro' ,0 ),
            array(15350, 891, 'codigo_escola_inep',   'CÓDIGO DA ESCOLA INEP',     14 ,3 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15351, 891, 'codigo_turma_escola',  'CÓDIGO DA TURMA NA ESCOLA', 14 ,11 ,'' ,20 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15352, 891, 'codigo_turma_inep',    'CÓDIGO DA TURMA - INEP',    14 ,31 ,'' ,10 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15353, 891, 'codigo_aluno_inep',    'CÓDIGO DO ALUNO NO INEP',   14 ,41 ,'' ,12 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15354, 891, 'codigo_aluno_escola',  'CÓDIGO DO ALUNO NA ESCOLA', 14 ,53 ,'' ,20 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15355, 891, 'codigo_matricula_inep','CÓDIGO DA MATRÍCULA',       14 ,73 ,'' ,12 ,'f' ,'t' ,'d' ,'matrícula do INEP' ,0 ),
            array(15356, 891, 'situacao_aluno',       'SITUAÇÃO DO ALUNO',         14 ,85 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15357, 892, 'tipo_registro',                'TIPO DE REGISTRO' ,14 ,1 ,'91' ,2 ,'t' ,'t' ,'e' ,'identifica o registro' ,0 ),
            array(15358, 892, 'codigo_escola_inep',           'CÓDIGO DA ESCOLA INEP' ,14 ,3 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15359, 892, 'codigo_turma_escola',          'CÓDIGO DA TURMA NA ESCOLA' ,14 ,11 ,'' ,20 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15360, 892, 'codigo_turma_inep',            'CÓDIGO DA TURMA - INEP' ,14 ,31 ,'' ,10 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15361, 892, 'codigo_aluno_inep',            'CÓDIGO DO ALUNO NO INEP' ,14 ,41 ,'' ,12 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15362, 892, 'codigo_aluno_escola',          'CÓDIGO DO ALUNO NA ESCOLA' ,14 ,53 ,'' ,20 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15363, 892, 'codigo_matricula_inep',        'CÓDIGO DA MATRÍCULA' ,14 ,73 ,'' ,12 ,'f' ,'t' ,'d' ,'matrícula do INEP' ,0 ),
            array(15364, 892, 'mediacao_didatico_pedagogico', 'TIPO DE MEDIAÇÃO DIDÁTICO PEDAGÓGICO' ,14 ,85 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15365, 892, 'modalidade',                   'MODALIDADE' ,14 ,86 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15366, 892, 'etapa',                        'ETAPA' ,14 ,87 ,'' ,2 ,'f' ,'t' ,'d' ,'' ,0 ),
            array(15367, 892, 'situacao_aluno',               'SITUAÇÃO DO ALUNO' ,14 ,89 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 ),
        );
        $table = $this->table('db_layoutcampos', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aVaues);
        $table->saveData();
    }

    public function down()
    {
        $this->execute('delete from configuracoes.db_layoutcampos where db52_layoutlinha in (890, 891, 892)');
        $this->execute('delete from configuracoes.db_layoutlinha where db51_layouttxt = 277');
        $this->execute('delete from configuracoes.db_layouttxt where db50_codigo = 277');
    }
}