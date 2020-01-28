<?php

use Classes\PostgresMigration;

class M9624LicitaConEventoLic extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES (292, 'TCE/RS - LICITACON - EVENTOS_LIC 1.4', 0, '', 6);
            
            INSERT INTO db_layoutlinha VALUES 
              (945, 292, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
              (946, 292, 'REGISTRO ', 3, 0, 0, 0, '', '|', TRUE);
              
            INSERT INTO db_layoutcampos VALUES
              (16214, 945, 'CNPJ', 'CNPJ', 1,   1, '',  14, FALSE, TRUE, 'd', '', 0),  
              (16215, 945, 'DATA_INICIAL', 'DATA_INICIAL', 1,  15, '',  10, FALSE, TRUE, 'd', '', 0),  
              (16216, 945, 'DATA_FINAL', 'DATA_FINAL', 1,  25, '',  10, FALSE, TRUE, 'd', '', 0),  
              (16229, 945, 'DATA_GERACAO', 'DATA_GERACAO', 1,  35, '',  10, FALSE, TRUE, 'd', '', 0),  
              (16230, 945, 'NOME_SETOR', 'NOME_SETOR', 1,  45, '', 150, FALSE, TRUE, 'd', '', 0),  
              (16231, 945, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '',  15, FALSE, TRUE, 'd', '', 0),
              (16234, 946, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),    
              (16235, 946, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'e', '', 0),    
              (16236, 946, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1,  25, '', 3, FALSE, TRUE, 'd', '', 0),    
              (16237, 946, 'SQ_EVENTO', 'SQ_EVENTO', 1,  28, '',  10, FALSE, TRUE, 'd', '', 0),    
              (16238, 946, 'CD_TIPO_FASE', 'CD_TIPO_FASE', 1, 38, '', 3, FALSE, TRUE, 'd', '', 0),    
              (16239, 946, 'CD_TIPO_EVENTO', 'CD_TIPO_EVENTO', 1, 41, '', 3, FALSE, TRUE, 'd', '', 0),    
              (16240, 946, 'DT_EVENTO', 'DT_EVENTO', 1, 44, '', 10, FALSE, TRUE, 'd', '', 0),    
              (16241, 946, 'TP_VEICULO_PUBLICACAO', 'TP_VEICULO_PUBLICACAO',  1,  54, '', 1, FALSE, TRUE, 'd', '', 0),    
              (16242, 946, 'DS_PUBLICACAO', 'DS_PUBLICACAO', 1, 55, '', 100, FALSE, TRUE, 'd', '', 0),    
              (16243, 946, 'TP_DOCUMENTO_AUTOR', 'TP_DOCUMENTO_AUTOR', 1, 155, '', 1, FALSE, TRUE, 'd', '', 0),    
              (16244, 946, 'NR_DOCUMENTO_AUTOR', 'NR_DOCUMENTO_AUTOR', 1, 156, '', 14, FALSE, TRUE, 'd', '', 0),    
              (16245, 946, 'DT_JULGAMENTO', 'DT_JULGAMENTO', 1, 170, '',  10, FALSE, TRUE, 'd', '', 0),    
              (16246, 946, 'TP_RESULTADO', 'TP_RESULTADO', 1, 180, '', 1, FALSE, TRUE, 'd', '', 0),
              (16247, 946, 'NR_LOTE', 'NR_LOTE', 1, 181, '', 10, FALSE, TRUE, 'd', '', 0),
              (16248, 946, 'NR_ITEM', 'NR_ITEM', 1, 191, '', 10, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (945, 946);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 292;
            DELETE FROM db_layouttxt WHERE db50_codigo = 292;
        ");
    }
}
