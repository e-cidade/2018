<?php

use Classes\PostgresMigration;

class M7747 extends PostgresMigration
{

  public function up()
  {

    $this->execute("
      delete from configuracoes.db_layoutcampos where db52_layoutlinha = 885;
      insert into configuracoes.db_layoutcampos values (15136, 885, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15137, 885, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15138, 885, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15139, 885, 'NR_LOTE', 'NR_LOTE', 1, 28, '', 10, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15140, 885, 'NR_ITEM', 'NR_ITEM', 1, 38, '', 10, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15141, 885, 'NR_ITEM_ORIGINAL', 'NR_ITEM_ORIGINAL', 1, 48, '', 20, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15142, 885, 'DS_ITEM', 'DS_ITEM', 13, 68, '', 500, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15143, 885, 'QT_ITENS', 'QT_ITENS', 1, 568, '', 12, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15144, 885, 'SG_UNIDADE_MEDIDA', 'SG_UNIDADE_MEDIDA', 1, 580, '', 5, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15145, 885, 'VL_UNITARIO_ESTIMADO', 'VL_UNITARIO_ESTIMADO', 1, 585, '', 16, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15146, 885, 'VL_TOTAL_ESTIMADO', 'VL_TOTAL_ESTIMADO', 1, 601, '', 16, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15147, 885, 'DT_REF_VALOR_ESTIMADO', 'DT_REF_VALOR_ESTIMADO', 1, 617, '', 10, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15148, 885, 'PC_BDI_ESTIMADO', 'PC_BDI_ESTIMADO', 1, 627, '', 6, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15149, 885, 'PC_ENCARGOS_SOCIAIS_ESTIMADO', 'PC_ENCARGOS_SOCIAIS_ESTIMADO', 1, 633, '', 7, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15150, 885, 'CD_FONTE_REFERENCIA', 'CD_FONTE_REFERENCIA', 1, 640, '', 20, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15151, 885, 'DS_FONTE_REFERENCIA', 'DS_FONTE_REFERENCIA', 13, 660, '', 60, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15152, 885, 'TP_RESULTADO_ITEM', 'TP_RESULTADO_ITEM', 1, 720, '', 1, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15153, 885, 'VL_UNITARIO_HOMOLOGADO', 'VL_UNITARIO_HOMOLOGADO', 1, 721, '', 16, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15154, 885, 'VL_TOTAL_HOMOLOGADO', 'VL_TOTAL_HOMOLOGADO', 1, 737, '', 16, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15155, 885, 'PC_BDI_HOMOLOGADO', 'PC_BDI_HOMOLOGADO', 1, 753, '', 6, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15156, 885, 'PC_ENCARGOS_SOCIAIS_HOMOLOGADO', 'PC_ENCARGOS_SOCIAIS_HOMOLOGADO', 1, 759, '', 6, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15157, 885, 'TP_ORCAMENTO', 'TP_ORCAMENTO', 1, 765, '', 1, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15158, 885, 'CD_TIPO_FAMILIA', 'CD_TIPO_FAMILIA', 1, 766, '', 3, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15159, 885, 'CD_TIPO_SUBFAMILIA', 'CD_TIPO_SUBFAMILIA', 1, 769, '', 3, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15160, 885, 'TP_DOCUMENTO_VENCEDOR', 'TP_DOCUMENTO_VENCEDOR', 1, 772, '', 1, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15161, 885, 'NR_DOCUMENTO_VENCEDOR', 'NR_DOCUMENTO_VENCEDOR', 1, 773, '', 14, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15164, 885, 'TP_DOCUMENTO_FORNECEDOR', 'TP_DOCUMENTO_FORNECEDOR', 1, 787, '', 1, false, true, 'd', '', 0);
      insert into configuracoes.db_layoutcampos values (15168, 885, 'NR_DOCUMENTO_FORNECEDOR', 'NR_DOCUMENTO_FORNECEDOR', 1, 788, '', 14, false, true, 'd', '', 0);
    ");
  }

  public function down()
  {

  }

}
