<?php

use Classes\PostgresMigration;

class M8766CorrecaoLayoutSisobra extends PostgresMigration
{
    public function up()
    {
        $nomeCampo      = 'obra_tipo_ocupacao_demolicao';
        $descricaoCampo = 'TIPO DE OCUPAÇÃO NA DEMOLIÇÃO';

        $codigoCampo = null;
        $campos      = $this->fetchAll("SELECT 
                                           db50_codigo,      --db51_layouttxt
                                           db51_codigo,      --db52_layoutlinha
                                           db_layoutcampos.* --db52_codigo
                                         FROM
                                           db_layouttxt
                                         INNER JOIN
                                           db_layoutlinha ON db51_layouttxt = db50_codigo
                                         INNER JOIN
                                           db_layoutcampos ON db52_layoutlinha = db51_codigo
                                         WHERE
                                               db50_descr ilike '%SISOBRA%'
                                           AND db52_nome ilike 'obra_tipo_ocupacao_construcao'
        ;");
    
        foreach ($campos as $campo) {

            if((int)$campo['db52_posicao'] > 732) {
                $codigoCampo = $campo['db52_codigo'];
            }
        }

        if(empty($campos) || empty($codigoCampo)) {
            throw new Exception("Não foram encontrados campos do layout do SISOBRA para atualizar.");
        }

        $retornoAtualizacao = $this->execute("UPDATE db_layoutcampos SET db52_nome = '{$nomeCampo}', db52_descr = '{$descricaoCampo}' WHERE db52_codigo = {$codigoCampo}");

        if($retornoAtualizacao != 1) {
            throw new Exception("Verifique a query de atualização de campos do layout, deve atualizar apenas um campo.");
        }
    }

    public function down()
    {
    }
}
