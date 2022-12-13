<?php

use Classes\PostgresMigration;

class M9624LicitaConResponsavelCon extends PostgresMigration
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
            INSERT INTO db_layouttxt (db50_codigo, db50_layouttxtgrupo, db50_descr, db50_quantlinhas, db50_obs ) VALUES (294, 6, 'TCE/RS - LICITACON - RESPONSAVEL_CON 1.4', 0, '');

            INSERT INTO db_layoutlinha (db51_codigo, db51_layouttxt, db51_descr, db51_tipolinha, db51_tamlinha, db51_linhasantes, db51_linhasdepois, db51_obs, db51_separador, db51_compacta ) VALUES 
                (951, 294, 'CABEÇALHO', 1, 0, 0, 0, '', '|', TRUE),
                (952, 294, 'REGISTRO', 3, 0, 0, 0, '', '|', TRUE);

            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos ) VALUES 
                (16260, 951, 'CNPJ', 'CNPJ', 1, 1, '', 14, FALSE, TRUE, 'd', '', 0),
                (16261, 951, 'DATA_INICIAL', 'DATA_INICIAL', 1, 15, '', 10, FALSE, TRUE, 'd', '', 0),
                (16262, 951, 'DATA_FINAL', 'DATA_FINAL', 1, 25, '', 10, FALSE, TRUE, 'd', '', 0),
                (16263, 951, 'DATA_GERACAO', 'DATA_GERACAO', 1, 35, '', 10, FALSE, TRUE, 'd', '', 0),
                (16264, 951, 'NOME_SETOR', 'NOME_SETOR', 1, 45, '', 150, FALSE, TRUE, 'd', '', 0),
                (16265, 951, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15, FALSE, TRUE, 'd', '', 0),
                (16249, 952, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, FALSE, TRUE, 'd', '', 0),
                (16250, 952, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, FALSE, TRUE, 'd', '', 0),
                (16251, 952, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, FALSE, TRUE, 'd', '', 0),
                (16252, 952, 'NR_CONTRATO', 'NR_CONTRATO', 1, 28, '', 20, FALSE, TRUE, 'd', '', 0),
                (16253, 952, 'ANO_CONTRATO', 'ANO_CONTRATO', 1, 48, '', 4, FALSE, TRUE, 'd', '', 0),
                (16254, 952, 'TP_INSTRUMENTO', 'TP_INSTRUMENTO', 1, 52, '', 1, FALSE, TRUE, 'd', '', 0),
                (16255, 952, 'TP_DOCUMENTO_RESPONSAVEL', 'TP_DOCUMENTO_RESPONSAVEL', 1, 53, '', 1, FALSE, TRUE, 'd', '', 0),
                (16256, 952, 'NR_DOCUMENTO_RESPONSAVEL', 'NR_DOCUMENTO_RESPONSAVEL', 1, 54, '', 14, FALSE, TRUE, 'd', '', 0),
                (16257, 952, 'TP_RESPONSAVEL', 'TP_RESPONSAVEL', 1, 68, '', 1, FALSE, TRUE, 'd', '', 0),
                (16258, 952, 'DT_INICIO_RESP', 'DT_INICIO_RESP', 1, 69, '', 10, FALSE, TRUE, 'd', '', 0),
                (16259, 952, 'DT_FINAL_RESP', 'DT_FINAL_RESP', 1, 79, '', 10, FALSE, TRUE, 'd', '', 0),
                (16266, 952, 'NR_ATO_DESIGNACAO', 'NR_ATO_DESIGNACAO', 1, 89, '', 20, FALSE, TRUE, 'd', '', 0),
                (16267, 952, 'ANO_ATO_DESIGNACAO', 'ANO_ATO_DESIGNACAO', 1, 109, '', 4, FALSE, TRUE, 'd', '', 0),
                (16268, 952, 'NOME_ARQUIVO_DOCUMENTO', 'NOME_ARQUIVO_DOCUMENTO', 1, 113, '', 200, FALSE, TRUE, 'd', '', 0);
        ");
    }

    private function downDados()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE  db52_layoutlinha IN (951, 952);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 294;
            DELETE FROM db_layouttxt WHERE db50_codigo = 294;
        ");
    }
}
