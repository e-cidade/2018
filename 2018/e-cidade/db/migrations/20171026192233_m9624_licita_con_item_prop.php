<?php

use Classes\PostgresMigration;

class M9624LicitaConItemProp extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES (291, 'TCE/RS - LICITACON - ITEM PROPOSTAS 1.4', 0 , '', 6); 
        
            INSERT INTO db_layoutlinha VALUES 
                (943, 291, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (944, 291, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);
        
            INSERT INTO db_layoutcampos VALUES 
                (16179, 943, 'CNPJ', 'CNPJ', 1, 1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16180, 943, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16181, 943, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16182, 943, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, FALSE, TRUE, 'd', '', 0),
                (16183, 943, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16184, 943, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16185, 944, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16186, 944, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16187, 944, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16188, 944, 'TP_DOCUMENTO_LICITANTE', 'TP_DOCUMENTO_LICITANTE', 1, 28, '', 1, FALSE, TRUE, 'd', '', 0),
                (16189, 944, 'NR_DOCUMENTO_LICITANTE', 'NR_DOCUMENTO_LICITANTE', 1, 29, '', 14, FALSE, TRUE, 'd', '', 0),
                (16190, 944, 'NR_LOTE', 'NR_LOTE', 1, 43, '', 10, FALSE, TRUE, 'd', '', 0),
                (16191, 944, 'NR_ITEM', 'NR_ITEM', 1, 53, '', 10, FALSE, TRUE, 'd', '', 0),
                (16192, 944, 'PC_BDI', 'PC_BDI', 1, 63, '', 6, FALSE, TRUE, 'd', '', 0),
                (16198, 944, 'PC_DESCONTO', 'PC_DESCONTO', 1, 69, '', 10, FALSE, TRUE, 'd', '', 0),
                (16193, 944, 'PC_ENCARGOS_SOCIAIS', 'PC_ENCARGOS_SOCIAIS', 1, 79, '', 6, FALSE, TRUE, 'd', '', 0),
                (16194, 944, 'VL_UNITARIO', 'VL_UNITARIO', 1, 85, '', 16, FALSE, TRUE, 'd', '', 0),
                (16195, 944, 'VL_TOTAL_ITEM', 'VL_TOTAL_ITEM', 1, 101, '', 16, FALSE, TRUE, 'd', '', 0),
                (16199, 944, 'VL_NOTA_TECNICA', 'VL_NOTA_TECNICA', 1, 117, '', 16, FALSE, TRUE, 'd', '', 0),
                (16196, 944, 'DT_HOMOLOGACAO', 'DT_HOMOLOGACAO', 1, 133, '', 10, FALSE, TRUE, 'd', '', 0),
                (16197, 944, 'TP_RESULTADO_PROPOSTA', 'TP_RESULTADO_PROPOSTA', 1, 143, '', 1, FALSE, TRUE, 'd', '', 0),
                (16200, 944, 'PC_TX', 'PC_TX', 1, 144, '', 10, FALSE, TRUE, 'd', '', 0),
                (16201, 944, 'TP_RESULTADO_HABILITACAO', 'TP_RESULTADO_HABILITACAO', 1, 154, '', 1, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (943, 944);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 291;
            DELETE FROM db_layouttxt WHERE db50_codigo = 291;
        ");
    }
}
