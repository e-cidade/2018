<?php

use Classes\PostgresMigration;

class M9624LicitaConLoteProp extends PostgresMigration
{

    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES (289, 'TCE/RS - LICITACON - LOTE PROPOSTAS 1.4', 0, NULL, 6);
            
            INSERT INTO db_layoutlinha VALUES
                (935, 289, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (936, 289, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);
        
            INSERT INTO db_layoutcampos VALUES 
                (16096, 935, 'CNPJ', 'CNPJ', 1, 1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16097, 935, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16098, 935, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16099, 935, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, FALSE, TRUE, 'd', '', 0),
                (16100, 935, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16101, 935, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16102, 936, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16103, 936, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16104, 936, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16105, 936, 'TP_DOCUMENTO_LICITANTE', 'TP_DOCUMENTO_LICITANTE', 1, 28, '', 1, FALSE, TRUE, 'd', '', 0),
                (16106, 936, 'NR_DOCUMENTO_LICITANTE', 'NR_DOCUMENTO_LICITANTE', 1, 29, '', 14, FALSE, TRUE, 'd', '', 0),
                (16107, 936, 'NR_LOTE', 'NR_LOTE', 1, 43, '', 10, FALSE, TRUE, 'd', '', 0),
                (16108, 936, 'PC_DESCONTO', 'PC_DESCONTO', 1, 53, '', 10, FALSE, TRUE, 'd', '', 0),
                (16109, 936, 'VL_TOTAL_LOTE', 'VL_TOTAL_LOTE', 1, 63, '', 16, FALSE, TRUE, 'd', '', 0),
                (16110, 936, 'VL_NOTA_TECNICA', 'VL_NOTA_TECNICA', 1, 79, '', 16, FALSE, TRUE, 'd', '', 0),
                (16111, 936, 'DT_HOMOLOGACAO', 'DT_HOMOLOGACAO', 1, 95, '', 10, FALSE, TRUE, 'd', '', 0),
                (16112, 936, 'TP_RESULTADO_PROPOSTA', 'TP_RESULTADO_PROPOSTA', 1, 105, '', 1, FALSE, TRUE, 'd', '', 0),
                (16113, 936, 'PC_TX', 'PC_TX', 1, 106, '', 10, FALSE, TRUE, 'd', '', 0),
                (16114, 936, 'TP_RESULTADO_HABILITACAO', 'TP_RESULTADO_HABILITACAO', 1, 116, '', 1, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (935, 936);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 289;
            DELETE FROM db_layouttxt WHERE db50_codigo = 289;
        ");
    }

}
