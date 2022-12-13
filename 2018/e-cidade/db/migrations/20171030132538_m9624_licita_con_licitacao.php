<?php

use Classes\PostgresMigration;

class M9624LicitaConLicitacao extends PostgresMigration
{
    public function up()
    {
        $this->upDados();
    }

    public function down()
    {
        $this->downDados();
    }

    public function upDados()
    {
        $this->execute("
            INSERT INTO db_layouttxt VALUES (295, 'TCE/RS - LICITACON - LICITACAO 1.4', 0, '', 6);

            INSERT INTO db_layoutlinha VALUES 
                (953, 295, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (954, 295, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);

            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES 
                (16269, 953, 'CNPJ', 'CNPJ', 1, 1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16270, 953, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16271, 953, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16272, 953, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, FALSE, TRUE, 'd', '', 0),
                (16273, 953, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16274, 953, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16275, 954, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16276, 954, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16277, 954, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16278, 954, 'NR_COMISSAO', 'NR_COMISSAO', 1, 28, '', 10, FALSE, TRUE, 'd', '', 0),
                (16279, 954, 'ANO_COMISSAO', 'ANO_COMISSAO', 1, 38, '', 4, FALSE, TRUE, 'd', '', 0),
                (16280, 954, 'TP_COMISSAO', 'TP_COMISSAO', 1, 42, '', 1, FALSE, TRUE, 'd', '', 0),
                (16281, 954, 'NR_PROCESSO', 'NR_PROCESSO', 1, 43, '', 20, FALSE, TRUE, 'd', '', 0),
                (16282, 954, 'ANO_PROCESSO', 'ANO_PROCESSO', 1, 63, '', 4, FALSE, TRUE, 'd', '', 0),
                (16283, 954, 'TP_OBJETO', 'TP_OBJETO', 1, 67, '', 3, FALSE, TRUE, 'd', '', 0),
                (16284, 954, 'CD_TIPO_FASE_ATUAL', 'CD_TIPO_FASE_ATUAL', 1, 70, '', 3, FALSE, TRUE, 'd', '', 0),
                (16285, 954, 'TP_LICITACAO', 'TP_LICITACAO', 1, 73, '', 3, FALSE, TRUE, 'd', '', 0),
                (16286, 954, 'TP_NIVEL_JULGAMENTO', 'TP_NIVEL_JULGAMENTO', 1, 76, '', 1, FALSE, TRUE, 'd', '', 0),
                (16287, 954, 'DT_AUTORIZACAO_ADESAO', 'DT_AUTORIZACAO_ADESAO', 1, 77, '', 10, FALSE, TRUE, 'd', '', 0),
                (16288, 954, 'TP_CARACTERISTICA_OBJETO', 'TP_CARACTERISTICA_OBJETO', 1, 87, '', 2, FALSE, TRUE, 'd', '', 0),
                (16289, 954, 'TP_NATUREZA', 'TP_NATUREZA', 1, 89, '', 1, FALSE, TRUE, 'd', '', 0),
                (16290, 954, 'TP_REGIME_EXECUCAO', 'TP_REGIME_EXECUCAO', 1, 90, '', 1, FALSE, TRUE, 'd', '', 0),
                (16291, 954, 'BL_PERMITE_SUBCONTRATACAO', 'BL_PERMITE_SUBCONTRATACAO', 1, 91, '', 1, FALSE, TRUE, 'd', '', 0),
                (16292, 954, 'TP_BENEFICIO_MICRO_EPP', 'TP_BENEFICIO_MICRO_EPP', 1, 92, '', 1, FALSE, TRUE, 'd', '', 0),
                (16293, 954, 'TP_FORNECIMENTO', 'TP_FORNECIMENTO', 1, 93, '', 1, FALSE, TRUE, 'd', '', 0),
                (16294, 954, 'TP_ATUACAO_REGISTRO', 'TP_ATUACAO_REGISTRO', 1, 94, '', 1, FALSE, TRUE, 'd', '', 0),
                (16295, 954, 'NR_LICITACAO_ORIGINAL', 'NR_LICITACAO_ORIGINAL', 1, 95, '', 20, FALSE, TRUE, 'd', '', 0),
                (16296, 954, 'ANO_LICITACAO_ORIGINAL', 'ANO_LICITACAO_ORIGINAL', 1, 115, '', 4, FALSE, TRUE, 'd', '', 0),
                (16297, 954, 'NR_ATA_REGISTRO_PRECO', 'NR_ATA_REGISTRO_PRECO', 1, 119, '', 20, FALSE, TRUE, 'd', '', 0),
                (16298, 954, 'DT_ATA_REGISTRO_PRECO', 'DT_ATA_REGISTRO_PRECO', 1, 139, '', 10, FALSE, TRUE, 'd', '', 0),
                (16299, 954, 'PC_TAXA_RISCO', 'PC_TAXA_RISCO', 1, 149, '', 6, FALSE, TRUE, 'd', '', 0),
                (16300, 954, 'TP_EXECUCAO', 'TP_EXECUCAO', 1, 155, '', 1, FALSE, TRUE, 'd', '', 0),
                (16301, 954, 'TP_DISPUTA', 'TP_DISPUTA', 1, 156, '', 1, FALSE, TRUE, 'd', '', 0),
                (16302, 954, 'TP_PREQUALIFICACAO', 'TP_PREQUALIFICACAO', 1, 157, '', 1, FALSE, TRUE, 'd', '', 0),
                (16303, 954, 'BL_INVERSAO_FASES', 'BL_INVERSAO_FASES', 1, 158, '', 1, FALSE, TRUE, 'd', '', 0),
                (16304, 954, 'TP_RESULTADO_GLOBAL', 'TP_RESULTADO_GLOBAL', 1, 159, '', 1, FALSE, TRUE, 'd', '', 0),
                (16305, 954, 'CNPJ_ORGAO_GERENCIADOR', 'CNPJ_ORGAO_GERENCIADOR', 1, 160, '', 14, FALSE, TRUE, 'd', '', 0),
                (16306, 954, 'NM_ORGAO_GERENCIADOR', 'NM_ORGAO_GERENCIADOR', 1, 174, '', 60, FALSE, TRUE, 'd', '', 0),
                (16307, 954, 'DS_OBJETO', 'DS_OBJETO', 1, 234, '', 1000, FALSE, TRUE, 'd', '', 0),
                (16308, 954, 'CD_TIPO_FUNDAMENTACAO', 'CD_TIPO_FUNDAMENTACAO', 1, 1234, '', 8, FALSE, TRUE, 'd', '', 0),
                (16309, 954, 'NR_ARTIGO', 'NR_ARTIGO', 1, 1242, '', 10, FALSE, TRUE, 'd', '', 0),
                (16310, 954, 'DS_INCISO', 'DS_INCISO', 1, 1252, '', 10, FALSE, TRUE, 'd', '', 0),
                (16311, 954, 'DS_LEI', 'DS_LEI', 1, 1262, '', 10, FALSE, TRUE, 'd', '', 0),
                (16312, 954, 'DT_INICIO_INSCR_CRED', 'DT_INICIO_INSCR_CRED', 1, 1272, '', 10, FALSE, TRUE, 'd', '', 0),
                (16313, 954, 'DT_FIM_INSCR_CRED', 'DT_FIM_INSCR_CRED', 1, 1282, '', 10, FALSE, TRUE, 'd', '', 0),
                (16314, 954, 'DT_INICIO_VIGEN_CRED', 'DT_INICIO_VIGEN_CRED', 1, 1292, '', 10, FALSE, TRUE, 'd', '', 0),
                (16315, 954, 'DT_FIM_VIGEN_CRED', 'DT_FIM_VIGEN_CRED', 1, 1302, '', 10, FALSE, TRUE, 'd', '', 0),
                (16316, 954, 'VL_LICITACAO', 'VL_LICITACAO', 1, 1312, '', 16, FALSE, TRUE, 'd', '', 0),
                (16317, 954, 'BL_ORCAMENTO_SIGILOSO', 'BL_ORCAMENTO_SIGILOSO', 1, 1328, '', 1, FALSE, TRUE, 'd', '', 0),
                (16318, 954, 'BL_RECEBE_INSCRICAO_PER_VIG', 'BL_RECEBE_INSCRICAO_PER_VIG', 1, 1329, '', 1, FALSE, TRUE, 'd', '', 0),
                (16319, 954, 'BL_PERMITE_CONSORCIO', 'BL_PERMITE_CONSORCIO', 1, 1330, '', 1, FALSE, TRUE, 'd', '', 0),
                (16320, 954, 'DT_ABERTURA', 'DT_ABERTURA', 1, 1331, '', 10, FALSE, TRUE, 'd', '', 0),
                (16321, 954, 'DT_HOMOLOGACAO', 'DT_HOMOLOGACAO', 1, 1341, '', 10, FALSE, TRUE, 'd', '', 0),
                (16322, 954, 'DT_ADJUDICACAO', 'DT_ADJUDICACAO', 1, 1351, '', 10, FALSE, TRUE, 'd', '', 0),
                (16323, 954, 'BL_LICIT_PROPRIA_ORGAO', 'BL_LICIT_PROPRIA_ORGAO', 1, 1361, '', 1, FALSE, TRUE, 'd', '', 0),
                (16324, 954, 'TP_DOCUMENTO_FORNECEDOR', 'TP_DOCUMENTO_FORNECEDOR', 1, 1362, '', 1, FALSE, TRUE, 'd', '', 0),
                (16325, 954, 'NR_DOCUMENTO_FORNECEDOR', 'NR_DOCUMENTO_FORNECEDOR', 1, 1363, '', 14, FALSE, TRUE, 'd', '', 0),
                (16326, 954, 'TP_DOCUMENTO_VENCEDOR', 'TP_DOCUMENTO_VENCEDOR', 1, 1377, '', 1, FALSE, TRUE, 'd', '', 0),
                (16327, 954, 'NR_DOCUMENTO_VENCEDOR', 'NR_DOCUMENTO_VENCEDOR', 1, 1378, '', 14, FALSE, TRUE, 'd', '', 0),
                (16328, 954, 'VL_HOMOLOGADO', 'VL_HOMOLOGADO', 1, 1392, '', 16, FALSE, TRUE, 'd', '', 0),
                (16329, 954, 'BL_GERA_DESPESA', 'BL_GERA_DESPESA', 1, 1408, '', 1, FALSE, TRUE, 'd', '', 0),
                (16330, 954, 'DS_OBSERVACAO', 'DS_OBSERVACAO', 1, 1409, '', 1000, FALSE, TRUE, 'd', '', 0),
                (16331, 954, 'PC_TX_ESTIMADA', 'PC_TX_ESTIMADA', 1, 2409, '', 10, FALSE, TRUE, 'd', '', 0),
                (16332, 954, 'PC_TX_HOMOLOGADA', 'PC_TX_HOMOLOGADA', 1, 2419, '', 10, FALSE, TRUE, 'd', '', 0);
        ");
    }

    public function downDados()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (953, 954);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 295;
            DELETE FROM db_layouttxt WHERE db50_codigo = 295;
        ");
    }
}
