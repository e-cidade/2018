<?php

use Classes\PostgresMigration;

class M9624LicitaConAlteracao extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES
                (290, 'TCE/RS - LICITACON - ALTERACAO 1.4', 0, '' , 6);
            
            INSERT INTO db_layoutlinha VALUES
                (937, 290, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (938, 290, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);
            
            INSERT INTO db_layoutcampos VALUES
                (16150, 937, 'CNPJ', 'CNPJ', 1,  1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16151, 937, 'DATA_INICIAL', 'DATA_INICIAL', 1,  15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16152, 937, 'DATA_FINAL', 'DATA_FINAL', 1,  25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16153, 937, 'DATA_GERACAO', 'DATA_GERACAO', 1,  35,  '', 10, FALSE, TRUE, 'd', '', 0),
                (16154, 937, 'NOME_SETOR', 'NOME_SETOR', 1,  45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16155, 937, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16156, 938, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16157, 938, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16158, 938, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16159, 938, 'NR_CONTRATO', 'NR_CONTRATO', 1, 28, '', 20, FALSE, TRUE, 'd', '', 0),
                (16160, 938, 'ANO_CONTRATO', 'ANO_CONTRATO', 1, 48, '',  4, FALSE, TRUE, 'd', '', 0),
                (16161, 938, 'TP_INSTRUMENTO', 'TP_INSTRUMENTO', 1, 52, '', 1, FALSE, TRUE, 'd', '', 0),
                (16162, 938, 'SQ_EVENTO', 'SQ_EVENTO', 1,  53, '', 10, FALSE, TRUE, 'd', '', 0),
                (16163, 938, 'CD_TIPO_OPERACAO', 'CD_TIPO_OPERACAO', 1, 63, '', 3, FALSE, TRUE, 'd', '', 0),
                (16164, 938, 'DS_OUTRA_OPERACAO', 'DS_OUTRA_OPERACAO', 1, 66, '', 60, FALSE, TRUE, 'd', '', 0),
                (16165, 938, 'NR_DIAS_NOVO_PRAZO', 'NR_DIAS_NOVO_PRAZO', 1, 126, '', 5, FALSE, TRUE, 'd', '', 0),
                (16166, 938, 'VL_ACRESCIMO', 'VL_ACRESCIMO', 1, 131, '', 16, FALSE, TRUE, 'd', '', 0),
                (16167, 938, 'VL_REDUCAO', 'VL_REDUCAO', 1, 147, '', 16, FALSE, TRUE, 'd', '', 0),
                (16168, 938, 'PC_ACRESCIMO', 'PC_ACRESCIMO', 1, 163, '', 8, FALSE, TRUE, 'd', '', 0),
                (16169, 938, 'PC_REDUCAO', 'PC_REDUCAO', 1, 171, '', 8, FALSE, TRUE, 'd', '', 0),
                (16170, 938, 'TP_REGIME_EXECUCAO_NOVO', 'TP_REGIME_EXECUCAO_NOVO', 1, 179, '', 1, FALSE, TRUE, 'd', '', 0),
                (16171, 938, 'TP_FORNECIMENTO_NOVO', 'TP_FORNECIMENTO_NOVO', 1, 180, '', 1, FALSE, TRUE, 'd', '', 0),
                (16172, 938, 'DS_JUSTIFICATIVA', 'DS_JUSTIFICATIVA', 1, 181, '', 200, FALSE, TRUE, 'd', '', 0),
                (16173, 938, 'TP_DOCUMENTO_ANTERIOR', 'TP_DOCUMENTO_ANTERIOR', 1, 381, '', 1, FALSE, TRUE, 'd', '', 0),
                (16174, 938, 'NR_DOCUMENTO_ANTERIOR', 'NR_DOCUMENTO_ANTERIOR', 1, 382, '', 14, FALSE, TRUE, 'd', '', 0),
                (16175, 938, 'TP_DOCUMENTO_NOVO', 'TP_DOCUMENTO_NOVO', 1, 396, '', 1, FALSE, TRUE, 'd', '', 0),
                (16176, 938, 'NR_DOCUMENTO_NOVO', 'NR_DOCUMENTO_NOVO', 1, 397, '', 14, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (937, 938);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 290;
            DELETE FROM db_layouttxt WHERE db50_codigo = 290;
        ");
    }
}
