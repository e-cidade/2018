<?php

use Classes\PostgresMigration;

class M8251LicitacaoLicitacon extends PostgresMigration
{
    public function up()
    {

      $this->execute(
<<<STRING
  delete from configuracoes.db_layoutcampos where db52_layoutlinha = 875;
  
  insert into configuracoes.db_layoutcampos values (15011, 875, 'TP_NATUREZA', 'TP_NATUREZA', 1, 89, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (14997, 875, 'NR_LICITACAO', 'NR_LICITACAO', 1, 1, '', 20, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (14998, 875, 'ANO_LICITACAO', 'ANO_LICITACAO', 1, 21, '', 4, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (14999, 875, 'CD_TIPO_MODALIDADE', 'CD_TIPO_MODALIDADE', 1, 25, '', 3, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15000, 875, 'NR_COMISSAO', 'NR_COMISSAO', 1, 28, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15001, 875, 'ANO_COMISSAO', 'ANO_COMISSAO', 1, 38, '', 4, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15002, 875, 'TP_COMISSAO', 'TP_COMISSAO', 1, 42, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15003, 875, 'NR_PROCESSO', 'NR_PROCESSO', 1, 43, '', 20, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15004, 875, 'ANO_PROCESSO', 'ANO_PROCESSO', 1, 63, '', 4, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15005, 875, 'TP_OBJETO', 'TP_OBJETO', 1, 67, '', 3, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15006, 875, 'CD_TIPO_FASE_ATUAL', 'CD_TIPO_FASE_ATUAL', 1, 70, '', 3, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15007, 875, 'TP_LICITACAO', 'TP_LICITACAO', 1, 73, '', 3, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15008, 875, 'TP_NIVEL_JULGAMENTO', 'TP_NIVEL_JULGAMENTO', 1, 76, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15009, 875, 'DT_AUTORIZACAO_ADESAO', 'DT_AUTORIZACAO_ADESAO', 1, 77, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15010, 875, 'TP_CARACTERISTICA_OBJETO', 'TP_CARACTERISTICA_OBJETO', 1, 87, '', 2, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15012, 875, 'TP_REGIME_EXECUCAO', 'TP_REGIME_EXECUCAO', 1, 90, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15013, 875, 'BL_PERMITE_SUBCONTRATACAO', 'BL_PERMITE_SUBCONTRATACAO', 1, 91, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15014, 875, 'TP_BENEFICIO_MICRO_EPP', 'TP_BENEFICIO_MICRO_EPP', 1, 92, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15015, 875, 'TP_FORNECIMENTO', 'TP_FORNECIMENTO', 1, 93, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15016, 875, 'TP_ATUACAO_REGISTRO', 'TP_ATUACAO_REGISTRO', 1, 94, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15017, 875, 'NR_LICITACAO_ORIGINAL', 'NR_LICITACAO_ORIGINAL', 1, 95, '', 20, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15018, 875, 'ANO_LICITACAO_ORIGINAL', 'ANO_LICITACAO_ORIGINAL', 1, 115, '', 4, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15019, 875, 'NR_ATA_REGISTRO_PRECO', 'NR_ATA_REGISTRO_PRECO', 1, 119, '', 20, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15020, 875, 'DT_ATA_REGISTRO_PRECO', 'DT_ATA_REGISTRO_PRECO', 1, 139, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15021, 875, 'PC_TAXA_RISCO', 'PC_TAXA_RISCO', 1, 149, '', 6, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15022, 875, 'TP_EXECUCAO', 'TP_EXECUCAO', 1, 155, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15023, 875, 'TP_DISPUTA', 'TP_DISPUTA', 1, 156, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15024, 875, 'TP_PREQUALIFICACAO', 'TP_PREQUALIFICACAO', 1, 157, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15025, 875, 'BL_INVERSAO_FASES', 'BL_INVERSAO_FASES', 1, 158, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15026, 875, 'TP_RESULTADO_GLOBAL', 'TP_RESULTADO_GLOBAL', 1, 159, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15028, 875, 'CNPJ_ORGAO_GERENCIADOR', 'CNPJ_ORGAO_GERENCIADOR', 1, 159, '', 14, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15029, 875, 'NM_ORGAO_GERENCIADOR', 'NM_ORGAO_GERENCIADOR', 1, 171, '', 60, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15030, 875, 'DS_OBJETO', 'DS_OBJETO', 1, 231, '', 500, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15031, 875, 'CD_TIPO_FUNDAMENTACAO', 'CD_TIPO_FUNDAMENTACAO', 1, 731, '', 8, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15032, 875, 'NR_ARTIGO', 'NR_ARTIGO', 1, 739, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15033, 875, 'DS_INCISO', 'DS_INCISO', 1, 749, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15034, 875, 'DS_LEI', 'DS_LEI', 1, 759, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15035, 875, 'DT_INICIO_INSCR_CRED', 'DT_INICIO_INSCR_CRED', 1, 769, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15036, 875, 'DT_FIM_INSCR_CRED', 'DT_FIM_INSCR_CRED', 1, 779, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15037, 875, 'DT_INICIO_VIGEN_CRED', 'DT_INICIO_VIGEN_CRED', 1, 789, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15038, 875, 'DT_FIM_VIGEN_CRED', 'DT_FIM_VIGEN_CRED', 1, 799, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15039, 875, 'VL_LICITACAO', 'VL_LICITACAO', 1, 809, '', 16, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15040, 875, 'BL_ORCAMENTO_SIGILOSO', 'BL_ORCAMENTO_SIGILOSO', 1, 825, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15041, 875, 'BL_RECEBE_INSCRICAO_PER_VIG', 'BL_RECEBE_INSCRICAO_PER_VIG', 1, 826, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15042, 875, 'BL_PERMITE_CONSORCIO', 'BL_PERMITE_CONSORCIO', 1, 827, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15043, 875, 'DT_ABERTURA', 'DT_ABERTURA', 1, 828, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15051, 875, 'VL_HOMOLOGADO', 'VL_HOMOLOGADO', 1, 889, '', 16, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15044, 875, 'DT_HOMOLOGACAO', 'DT_HOMOLOGACAO', 1, 838, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15045, 875, 'DT_ADJUDICACAO', 'DT_ADJUDICACAO', 1, 848, '', 10, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15046, 875, 'BL_LICIT_PROPRIA_ORGAO', 'BL_LICIT_PROPRIA_ORGAO', 1, 858, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15047, 875, 'TP_DOCUMENTO_FORNECEDOR', 'TP_DOCUMENTO_FORNECEDOR', 1, 859, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15048, 875, 'NR_DOCUMENTO_FORNECEDOR', 'NR_DOCUMENTO_FORNECEDOR', 1, 860, '', 14, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15049, 875, 'TP_DOCUMENTO_VENCEDOR', 'TP_DOCUMENTO_VENCEDOR', 1, 874, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15050, 875, 'NR_DOCUMENTO_VENCEDOR', 'NR_DOCUMENTO_VENCEDOR', 1, 875, '', 14, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15052, 875, 'BL_GERA_DESPESA', 'BL_GERA_DESPESA', 1, 905, '', 1, false, true, 'd', '', 0);
  insert into configuracoes.db_layoutcampos values (15053, 875, 'DS_OBSERVACAO', 'DS_OBSERVACAO', 1, 906, '', 500, false, true, 'd', '', 0);
STRING
      );
    }

    public function down()
    {

    }

}
