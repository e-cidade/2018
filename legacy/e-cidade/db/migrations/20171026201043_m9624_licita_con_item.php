<?php

use Classes\PostgresMigration;

class M9624LicitaConItem extends PostgresMigration
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
        $this->execute(
<<<SQL
            insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 286 ,6 ,'TCE/RS - LICITACON - ITEM 1.4' ,0 ,'' );

            insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 933 ,286 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'|', TRUE );
            insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 934 ,286 ,'REGISTRO' ,3 ,0 ,0 ,0 ,'' ,'|', TRUE );

            insert into db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
                 values (16116, 933, 'CNPJ'           , 'CNPJ'           , 1, 1  , '', 14 , 'f', 't', 'd', '', 0)
                       ,(16117, 933, 'DATA_INICIAL'   , 'DATA_INICIAL'   , 1, 15 , '', 10 , 'f', 't', 'd', '', 0)
                       ,(16118, 933, 'DATA_FINAL'     , 'DATA_FINAL'     , 1, 25 , '', 10 , 'f', 't', 'd', '', 0)
                       ,(16119, 933, 'DATA_GERACAO'   , 'DATA_GERACAO'   , 1, 35 , '', 10 , 'f', 't', 'd', '', 0)
                       ,(16120, 933, 'NOME_SETOR'     , 'NOME_SETOR'     , 1, 45 , '', 150, 'f', 't', 'd', '', 0)
                       ,(16121, 933, 'TOTAL_REGISTROS', 'TOTAL_REGISTROS', 1, 195, '', 15 , 'f', 't', 'd', '', 0);

            insert into db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
                 values  (16122, 934, 'NR_LICITACAO'                   , 'NR_LICITACAO'                   ,  1,    1 , '', 20 , 'f' , 't', 'd', '', 0)
                        ,(16123, 934, 'ANO_LICITACAO'                  , 'ANO_LICITACAO'                  ,  1,   21 , '',  4 , 'f' , 't', 'd', '', 0)
                        ,(16124, 934, 'CD_TIPO_MODALIDADE'             , 'CD_TIPO_MODALIDADE'             ,  1,   25 , '',  3 , 'f' , 't', 'd', '', 0)
                        ,(16125, 934, 'NR_LOTE'                        , 'NR_LOTE'                        ,  1,   28 , '', 10 , 'f' , 't', 'd', '', 0)
                        ,(16126, 934, 'NR_ITEM'                        , 'NR_ITEM'                        ,  1,   38 , '', 10 , 'f' , 't', 'd', '', 0)
                        ,(16127, 934, 'NR_ITEM_ORIGINAL'               , 'NR_ITEM_ORIGINAL'               ,  1,   48 , '', 20 , 'f' , 't', 'd', '', 0)
                        ,(16128, 934, 'DS_ITEM'                        , 'DS_ITEM'                        , 13,   68 , '',500 , 'f' , 't', 'd', '', 0)
                        ,(16129, 934, 'QT_ITENS'                       , 'QT_ITENS'                       ,  1,  568 , '', 12 , 'f' , 't', 'd', '', 0)
                        ,(16130, 934, 'SG_UNIDADE_MEDIDA'              , 'SG_UNIDADE_MEDIDA'              ,  1,  580 , '',  5 , 'f' , 't', 'd', '', 0)
                        ,(16131, 934, 'VL_UNITARIO_ESTIMADO'           , 'VL_UNITARIO_ESTIMADO'           ,  1,  585 , '', 16 , 'f' , 't', 'd', '', 0)
                        ,(16132, 934, 'VL_TOTAL_ESTIMADO'              , 'VL_TOTAL_ESTIMADO'              ,  1,  601 , '', 16 , 'f' , 't', 'd', '', 0)
                        ,(16133, 934, 'DT_REF_VALOR_ESTIMADO'          , 'DT_REF_VALOR_ESTIMADO'          ,  1,  617 , '', 10 , 'f' , 't', 'd', '', 0)
                        ,(16134, 934, 'PC_BDI_ESTIMADO'                , 'PC_BDI_ESTIMADO'                ,  1,  627 , '',  6 , 'f' , 't', 'd', '', 0)
                        ,(16135, 934, 'PC_ENCARGOS_SOCIAIS_ESTIMADO'   , 'PC_ENCARGOS_SOCIAIS_ESTIMADO'   ,  1,  633 , '',  7 , 'f' , 't', 'd', '', 0)
                        ,(16136, 934, 'CD_FONTE_REFERENCIA'            , 'CD_FONTE_REFERENCIA'            ,  1,  640 , '', 20 , 'f' , 't', 'd', '', 0)
                        ,(16137, 934, 'DS_FONTE_REFERENCIA'            , 'DS_FONTE_REFERENCIA'            , 13,  660 , '', 60 , 'f' , 't', 'd', '', 0)
                        ,(16138, 934, 'TP_RESULTADO_ITEM'              , 'TP_RESULTADO_ITEM'              ,  1,  720 , '',  1 , 'f' , 't', 'd', '', 0)
                        ,(16139, 934, 'VL_UNITARIO_HOMOLOGADO'         , 'VL_UNITARIO_HOMOLOGADO'         ,  1,  721 , '', 16 , 'f' , 't', 'd', '', 0)
                        ,(16140, 934, 'VL_TOTAL_HOMOLOGADO'            , 'VL_TOTAL_HOMOLOGADO'            ,  1,  737 , '', 16 , 'f' , 't', 'd', '', 0)
                        ,(16141, 934, 'PC_BDI_HOMOLOGADO'              , 'PC_BDI_HOMOLOGADO'              ,  1,  753 , '',  6 , 'f' , 't', 'd', '', 0)
                        ,(16142, 934, 'PC_ENCARGOS_SOCIAIS_HOMOLOGADO' , 'PC_ENCARGOS_SOCIAIS_HOMOLOGADO' ,  1,  759 , '',  6 , 'f' , 't', 'd', '', 0)
                        ,(16143, 934, 'TP_ORCAMENTO'                   , 'TP_ORCAMENTO'                   ,  1,  765 , '',  1 , 'f' , 't', 'd', '', 0)
                        ,(16144, 934, 'CD_TIPO_FAMILIA'                , 'CD_TIPO_FAMILIA'                ,  1,  766 , '',  3 , 'f' , 't', 'd', '', 0)
                        ,(16145, 934, 'CD_TIPO_SUBFAMILIA'             , 'CD_TIPO_SUBFAMILIA'             ,  1,  769 , '',  3 , 'f' , 't', 'd', '', 0)
                        ,(16146, 934, 'TP_DOCUMENTO_VENCEDOR'          , 'TP_DOCUMENTO_VENCEDOR'          ,  1,  772 , '',  1 , 'f' , 't', 'd', '', 0)
                        ,(16147, 934, 'NR_DOCUMENTO_VENCEDOR'          , 'NR_DOCUMENTO_VENCEDOR'          ,  1,  773 , '', 14 , 'f' , 't', 'd', '', 0)
                        ,(16148, 934, 'TP_DOCUMENTO_FORNECEDOR'        , 'TP_DOCUMENTO_FORNECEDOR'        ,  1,  787 , '',  1 , 'f' , 't', 'd', '', 0)
                        ,(16149, 934, 'NR_DOCUMENTO_FORNECEDOR'        , 'NR_DOCUMENTO_FORNECEDOR'        ,  1,  788 , '', 14 , 'f' , 't', 'd', '', 0)
                        ,(16350, 934 ,'TP_BENEFICIO_MICRO_EPP'         , 'TP_BENEFICIO_MICRO_EPP'         ,  1,  812 , '',  1 , 'f' , 't' ,'d' ,'' ,0)
                        ,(16206, 934, 'PC_TX_ESTIMADA'                 , 'PC_TX_ESTIMADA'                 ,  1,  813 , '', 10 , 'f' , 't', 'd', '', 0)
                        ,(16207, 934, 'PC_TX_HOMOLOGADA'               , 'PC_TX_HOMOLOGADA'               ,  1,  823 , '', 10 , 'f' , 't', 'd', '', 0);

SQL
        );
    }

    private function downDados()
    {
        $this->execute(
<<<SQL
            delete from db_layoutcampos where db52_layoutlinha in (933, 934);
            delete from db_layoutlinha where db51_layouttxt = 286;
            delete from db_layouttxt where db50_codigo = 286;
SQL
        );
    }
}
