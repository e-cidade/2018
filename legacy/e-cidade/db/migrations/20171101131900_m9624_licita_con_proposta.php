<?php

use Classes\PostgresMigration;

class M9624LicitaConProposta extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES
                (296, 'TCE/RS - LICITACON - PROPOSTAS 1.4', 0, '' , 6);
            
            INSERT INTO db_layoutlinha VALUES
                (955, 296, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (956, 296, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);
            
            INSERT INTO db_layoutcampos VALUES
                (16333, 955, 'CNPJ', 'CNPJ', 1, 1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16334, 955, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16335, 955, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16336, 955, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, FALSE, TRUE, 'd', '', 0),
                (16337, 955, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16338, 955, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16339, 956, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16340, 956, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16341, 956, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16342, 956, 'TP_DOCUMENTO_LICITANTE', 'TP_DOCUMENTO_LICITANTE', 1, 28, '', 1, FALSE, TRUE, 'd', '', 0),
                (16343, 956, 'NR_DOCUMENTO_LICITANTE', 'NR_DOCUMENTO_LICITANTE', 1, 29, '', 14, FALSE, TRUE, 'd', '', 0),
                (16344, 956, 'DT_PROPOSTA', 'DT_PROPOSTA', 1, 43, '', 10, FALSE, TRUE, 'd', '', 0),
                (16345, 956, 'TP_RESULTADO_PROPOSTA', 'TP_RESULTADO_PROPOSTA', 1, 53, '', 1, FALSE, TRUE, 'd', '', 0),
                (16346, 956, 'VL_TOTAL_PROPOSTA', 'VL_TOTAL_PROPOSTA', 1, 54, '', 16, FALSE, TRUE, 'd', '', 0),
                (16347, 956, 'PC_DESCONTO', 'PC_DESCONTO', 1, 70, '', 10, FALSE, TRUE, 'd', '', 0),
                (16348, 956, 'VL_NOTA_TECNICA', 'VL_NOTA_TECNICA', 1, 80, '', 8, FALSE, TRUE, 'd', '', 0),
                (16349, 956, 'DT_HOMOLOGACAO', 'DT_HOMOLOGACAO', 1, 88, '', 10, FALSE, TRUE, 'd', '', 0),
                (16352, 956, 'PC_TX', 'PC_TX', 1, 98, '', 10, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (955, 956);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 296;
            DELETE FROM db_layouttxt WHERE db50_codigo = 296;
        ");
    }
}
