<?php

use Classes\PostgresMigration;

class M9624LicitaConLote extends PostgresMigration
{
    public function up()
    {
        $this->upDados();
    }

    public function down()
    {
        $this->downDados();
    }

    private function upDados()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES 
                (293, 'TCE/RS - LICITACON - LOTE V1.4', 0, '',  6);

            INSERT INTO db_layoutlinha VALUES 
                (947, 293, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (948, 293, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES 
                (16208, 947, 'CNPJ', 'CNPJ', 1, 1, '', 14, 'false', 'true', 'd', '', 0),
                (16209, 947, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, 'false', 'true', 'd', '', 0),
                (16210, 947, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, 'false', 'true', 'd', '', 0),
                (16211, 947, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, 'false', 'true', 'd', '', 0),
                (16212, 947, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, 'false', 'true', 'd', '', 0),
                (16213, 947, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, 'false', 'true', 'd', '', 0),
                (16217, 948, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, 'false', 'true', 'd', '', 0),
                (16218, 948, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, 'false', 'true', 'd', '', 0),
                (16219, 948, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, 'false', 'true', 'd', '', 0),
                (16220, 948, 'NR_LOTE', 'NR_LOTE', 1, 28, '', 10, 'false', 'true', 'd', '', 0),
                (16221, 948, 'DS_LOTE', 'DS_LOTE', 13, 38, '', 500, 'false', 'true', 'd', '', 0),
                (16222, 948, 'VL_ESTIMADO', 'VL_ESTIMADO', 1, 538, '', 16, 'false', 'true', 'd', '', 0),
                (16223, 948, 'VL_HOMOLOGADO', 'VL_HOMOLOGADO', 1, 554, '', 16, 'false', 'true', 'd', '', 0),
                (16224, 948, 'TP_RESULTADO_LOTE', 'TP_RESULTADO_LOTE', 1, 570, '', 1, 'false', 'true', 'd', '', 0),
                (16225, 948, 'TP_DOCUMENTO_VENCEDOR', 'TP_DOCUMENTO_VENCEDOR', 1, 571, '', 1, 'false', 'true', 'd', '', 0),
                (16226, 948, 'NR_DOCUMENTO_VENCEDOR', 'NR_DOCUMENTO_VENCEDOR', 1, 572, '', 14, 'false', 'true', 'd', '', 0),
                (16227, 948, 'TP_DOCUMENTO_FORNECEDOR', 'TP_DOCUMENTO_FORNECEDOR', 1, 586, '', 1, 'false', 'true', 'd', '', 0),
                (16228, 948, 'NR_DOCUMENTO_FORNECEDOR', 'NR_DOCUMENTO_FORNECEDOR', 1, 587, '', 14, 'false', 'true', 'd', '', 0),
                (16351, 948, 'TP_BENEFICIO_MICRO_EPP', 'TP_BENEFICIO_MICRO_EPP', 1, 601, '', 1, 'false', 'true', 'd', '', 0),
                (16232, 948, 'PC_TX_ESTIMADA', 'PC_TX_ESTIMADA', 1, 602, '', 5, 'f', 't', 'd', '', 0),
                (16233, 948, 'PC_TX_HOMOLOGADA', 'PC_TX_HOMOLOGADA', 1, 607, '', 5, 'f', 't', 'd', '', 0);
        ");
    }

    private function downDados()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (947, 948);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 293;
            DELETE FROM db_layouttxt WHERE db50_codigo = 293;
        ");
    }
}
