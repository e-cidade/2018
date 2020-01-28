<?php

use Classes\PostgresMigration;

class M7339 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

  select fc_executa_ddl($$
  insert into db_layouttxt values (263, 'CNAB240 FEBRABAN V087', 0, 'Arquivo CNAB240 Padrão versão 087', 1 );
  insert into db_layoutlinha values (868, 263, 'HEADER DE LOTE', 2, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (867, 263, 'HEADER DE ARQUIVO', 1, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (869, 263, 'TRAILER DO LOTE', 4, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (872, 263, 'SEGMENTO Q', 3, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (873, 263, 'SEGMENTO R', 3, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (870, 263, 'TRAILER DO ARQUIVO', 5, 240, 0, 0, '', '', false );
  insert into db_layoutlinha values (871, 263, 'SEGMENTO P', 3, 240, 0, 0, '', '', false );
  insert into db_layoutcampos values (14852, 867, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14853, 867, 'lote', 'LOTE DO SERVIÇO', 2, 4, '0000', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14854, 867, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '0', 1, true, true, 'e', '', 0 );
  insert into db_layoutcampos values (14855, 867, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 9, '', 9, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14856, 867, 'tipo_inscricao', 'TIPO DE INSCRIÇÃO DA EMPRESA', 2, 18, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14857, 867, 'numero_inscricao', 'NÚMERO DE INSCRIÇÃO DA EMPRESA', 2, 19, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14858, 867, 'codigo_convenio_banco', 'CÓDIGO DO CONVENIO NO BANCO', 13, 33, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14859, 867, 'codigo_agencia', 'AGÊNCIA MANTENEDORA DA CONTA', 2, 53, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14860, 867, 'dv_agencia', 'DÍGITO VERIFICADOR DA AGÊNCIA', 2, 58, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14861, 867, 'exclusivo_banco_1', 'CAMPO DE USO DO BANCO', 13, 59, '', 14, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14862, 867, 'nome_empresa', 'NOME DA EMPRESA', 13, 73, '', 30, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14863, 867, 'nome_banco', 'NOME DO BANCO', 13, 103, '', 30, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14864, 867, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 133, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14865, 867, 'codigo_remessa', 'CÓDIGO DA REMESSA / RETORNO', 2, 143, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14866, 867, 'data_geracao', 'DATA DE GERAÇÃO DO ARQUIVO', 13, 144, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14867, 867, 'hora_geracao', 'HORA DE GERAÇÃO DO ARQUIVO', 13, 152, '', 6, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14868, 867, 'numero_sequencial', 'NÚMERO SEQUENCIAL DO ARQUIVO', 2, 158, '', 6, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14869, 867, 'versao_layout', 'NÚMERO DA VERSÃO DO LAYOUT', 2, 164, '087', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14870, 867, 'densidade_arquivo', 'DENSIDADE DE GRAVAÇÃO DO ARQUIVO', 2, 167, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14871, 867, 'uso_reservado_banco', 'USO RESERVADO DO BANCO', 13, 172, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14872, 867, 'uso_reservado_empresa', 'USO RESERVADO DA EMPRESA', 13, 192, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14873, 867, 'exclusivo_febraban_3', 'USO EXCLUSIVO FEBRABAN', 13, 212, '', 29, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14874, 868, 'codigo_banco', 'CÓDIGO DO BANCO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14875, 868, 'lote', 'LOTE DE SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14876, 868, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '1', 1, true, true, 'e', '', 0 );
  insert into db_layoutcampos values (14877, 868, 'tipo_operacao', 'TIPO DE OPERAÇÃO', 13, 9, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14878, 868, 'tipo_servico', 'TIPO DE SERVIÇO', 2, 10, '01', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14879, 868, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 12, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14880, 868, 'versao_layout', 'NÚMERO DA VERSÃO DO LAYOUT', 2, 14, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14881, 868, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 17, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14882, 868, 'tipo_inscricao', 'TIPO DE INSCRIÇÃO DA EMPRESA', 2, 18, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14883, 868, 'numero_inscricao', 'NÚMERO DA INSCRIÇÃO DA EMPRESA', 2, 19, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14884, 868, 'codigo_convenio_banco', 'CÓDIGO DO CONVÊNIO DO BANCO', 13, 34, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14885, 868, 'codigo_agencia', 'AGÊNCIA MANTENEDORA DA CONTA', 2, 54, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14886, 868, 'dv_agencia', 'DÍGITO VERIFICADOR DA AGÊNCIA', 13, 59, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14887, 868, 'exclusivo_banco_1', 'USO EXCLUSIVO DO BANCO', 13, 60, '', 14, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14888, 868, 'nome_empresa', 'NOME DA EMPRESA', 13, 74, '', 30, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14889, 868, 'mensagem1', 'MENSAGEM 1', 13, 104, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14890, 868, 'mensagem2', 'MENSAGEM 2', 13, 144, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14891, 868, 'numero_remessa', 'NÚEMRO DA REMESSA / RETORNO', 2, 184, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14892, 868, 'data_geracao', 'DATA DE GRAVAÇÃO REMESSA / RETORNO', 13, 192, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14893, 868, 'data_credito', 'DATA DO CRÉDITO', 13, 200, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14894, 868, 'exclusivo_febraban_3', 'USO EXCLUSIVO FEBRABAN', 13, 208, '', 33, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14895, 869, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14896, 869, 'lote', 'LOTE DO SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14897, 869, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '5', 1, true, true, 'e', '', 0 );
  insert into db_layoutcampos values (14898, 869, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 9, '', 9, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14899, 869, 'quantidade_registros', 'QUANTIDADE DE REGISTROS', 2, 18, '', 6, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14900, 869, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 24, '', 217, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14901, 870, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14902, 870, 'lote', 'LOTE DE SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14903, 870, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '9', 1, true, true, 'e', '', 0 );
  insert into db_layoutcampos values (14904, 870, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 9, '', 9, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14905, 870, 'quantidade_lotes', 'QUANTIDADE DE LOTES DO ARQUIVO', 2, 18, '', 6, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14906, 870, 'quantidade_registros', 'QUANTIDADE DE REGISTROS DO ARQUIVO', 2, 24, '', 6, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14907, 870, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 30, '', 6, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14908, 870, 'exclusivo_febraban_3', 'USO EXCLUSIVO FEBRABAN', 13, 36, '', 205, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14909, 871, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPOSIÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14910, 871, 'lote', 'LOTE DO SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14911, 871, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '3', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14912, 871, 'sequencial_registro', 'SEQUENCIAL DO REGISTRO NO LOTE', 2, 9, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14913, 871, 'segmento', 'CÓDIGO DO SEGMENTO DO REGISTRO', 1, 14, 'P', 1, true, true, 'd', '', 0 );
  insert into db_layoutcampos values (14914, 871, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 15, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14915, 871, 'codigo_movimento', 'CÓDIGO DO MOVIMENTO', 2, 16, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14916, 871, 'codigo_agencia', 'CÓDIGO DA AGÊNCIA MANTENEDORA DA CONTA', 2, 18, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14917, 871, 'dv_agencia', 'DÍGITO VERIFICADOR DA AGÊNCIA', 13, 23, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14918, 871, 'exclusivo_banco_1', 'USO EXCLUSIVO DO BANCO', 13, 24, '', 14, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14919, 871, 'exclusivo_banco_2', 'USO EXCLUSIVO DO BANCO', 13, 38, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14920, 871, 'codigo_carteira', 'CÓDIGO DA CARTEIRA', 2, 58, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14921, 871, 'forma_cadastramento', 'FORMA DE CADASTRAMENTO DO TÍTULO NO BANC', 2, 59, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14922, 871, 'tipo_documento', 'TIPO DE DOCUMENTO', 1, 60, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14929, 871, 'dv_agencia_cobradora', 'DÍGITO VERIFICADOR DA AGÊNCIA ENCARREGAD', 13, 106, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14949, 872, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14951, 872, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '3', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14953, 872, 'segmento', 'CÓDIGO SEGMENTO DO REGISTRO', 1, 14, 'Q', 1, true, true, 'd', '', 0 );
  insert into db_layoutcampos values (14954, 872, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 15, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14955, 872, 'codigo_movimento', 'CÓDIGO DO MOVIMENTO REMESSA', 2, 16, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14956, 872, 'tipo_inscricao_sacado', 'TIPO DE INSCRIÇÃO DO SACADO', 2, 18, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14957, 872, 'numero_inscricao_sacado', 'NÚMERO DE INCRIÇÃO DO SACADO', 2, 19, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14931, 871, 'aceite_titulo', 'IDENTIFICAÇÃO DE TÍTULO ACEITO/NÃO ACEIT', 1, 109, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14932, 871, 'data_emissao_titulo', 'DATA DE EMISSÃO DO TÍTULO', 1, 110, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14933, 871, 'codigo_juros', 'CÓDIGO DO JUROS DE MORA', 1, 118, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14934, 871, 'data_juros', 'DATA DO JUROS DE MORA', 1, 119, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14935, 871, 'taxa_juros', 'JUROS DE MORA POR DIA / TAXA', 1, 127, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14936, 871, 'codigo_desconto', 'CÓDIGO DO DESCONTO 1', 1, 142, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14937, 871, 'data_desconto', 'DATA DO DESCONTO 1', 1, 143, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14950, 872, 'lote', 'LOTE DE SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14952, 872, 'sequencial_registro', 'NÚMERO SEQUENCIAL DO REGISTRO NO LOTE', 2, 9, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14938, 871, 'valor_desconto', 'DESCONTO 1 VALOR/PERCENTUAL A SER CONCED', 2, 151, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14939, 871, 'valor_iof', 'VALOR DO IOF A SER RECOLHIDO', 2, 166, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14940, 871, 'valor_abatimento', 'VALOR DO ABATIMENTO', 2, 181, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14941, 871, 'uso_empresa', 'IDENTIFICAÇÃO DO TÍTULO NA EMPRESA', 13, 196, '', 25, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14942, 871, 'codigo_protesto', 'CÓDIGO PARA PROTESTO', 2, 221, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14943, 871, 'prazo_protesto', 'NÚMERO DE DIAS PARA PROTESTO', 2, 222, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14944, 871, 'codigo_baixa_devolucao', 'CÓDIGO PARA BAIXA/DEVOLUÇÃO', 2, 224, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14945, 871, 'prazo_baixa_devolucao', 'NÚMERO DE DIAS PARA BAIXA/DEVOLUÇÃO', 2, 225, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14946, 871, 'codigo_moeda', 'CÓDIGO DA MOEDA', 1, 228, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14947, 871, 'exclusivo_banco_3', 'USO EXCLUSIVO DO BANCO', 2, 230, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14948, 871, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 240, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14958, 872, 'nome_sacado', 'NOME DO SACADO', 13, 34, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14960, 872, 'bairro_sacado', 'BAIRRO DO SACADO', 13, 114, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14961, 872, 'cep_sacado', 'CEP DO SACADO', 1, 129, '', 5, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14962, 872, 'sufixo_cep_sacado', 'SUFIXO DO CEP DO SACADO', 1, 134, '', 3, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14963, 872, 'cidade_sacado', 'CIDADE DO SACADO', 13, 137, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14964, 872, 'uf_sacado', 'UF DO SACADO', 1, 152, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14965, 872, 'tipo_inscricao_sacador', 'TIPO DE INSCRIÇÃO DO SACADOR', 1, 154, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14966, 872, 'numero_inscricao_sacador', 'NÚMERO DE INSCRIÇÃO DO SACADOR', 1, 155, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14967, 872, 'nome_sacador', 'NOME DO SACADOR', 13, 170, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14968, 872, 'codigo_banco_correspondente', 'CÓDIGO DO BANCO CORRESPONDENTE NA COMPEN', 2, 210, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14969, 872, 'nosso_numero', 'NOSSO NÚMERO BANCO CORRESPONDENTE', 13, 213, '', 20, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14970, 872, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 233, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14971, 873, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14972, 873, 'lote', 'LOTE DE SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14959, 872, 'endereco_sacado', 'ENDEREÇO DO SACADO', 13, 74, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14924, 871, 'distribuicao_bloqueto', 'IDENTIFICAÇÃO DA DISTRIBUIÇÃO', 1, 62, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14925, 871, 'documento_cobranca', 'NÚMERO DO DOCUMENTO DE COBRANÇA', 13, 63, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14928, 871, 'codigo_agencia_cobradora', 'CÓDIGO DA AGÊNCIA ENCARREGADA DA COBRANÇ', 2, 101, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14923, 871, 'emissao_bloqueto', 'IDENTIFICAÇÃO DA EMISSÃO DO BLOQUETO', 13, 61, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14926, 871, 'vencimento_titulo', 'DATA DE VENCIMENTO DO TÍTULO', 1, 78, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14927, 871, 'valor_titulo', 'VALOR DO TÍTULO', 2, 86, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14930, 871, 'especie_titulo', 'ESPÉCIE DO TÍTULO', 2, 107, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14973, 873, 'tipo_registro', 'TIPO DE REGISTRO', 2, 8, '3', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14975, 873, 'segmento', 'CÓDIGO DO SEGMENTO DO REGISTRO', 1, 14, 'R', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14976, 873, 'exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 13, 15, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14977, 873, 'codigo_movimento', 'CÓDIGO DO MOVIMENTO REMESSA', 2, 16, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14974, 873, 'sequencial_registro', 'NÚMERO SEQUENCIAL DO REGISTRO NO LOTE', 2, 9, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14978, 873, 'codigo_desconto_2', 'CÓDIGO DO DESCONTO 2', 2, 18, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14979, 873, 'data_desconto_2', 'DATA DO DESCONTO 2', 1, 19, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14980, 873, 'valor_desconto_2', 'VALOR / PERCENTUAL A SER CONCEDIDO', 2, 27, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14981, 873, 'codigo_desconto_3', 'CÓDIGO DO DESCONTO 3', 2, 42, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14982, 873, 'data_desconto_3', 'DATA DO DESCONTO 3', 1, 43, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14983, 873, 'valor_desconto_3', 'VALOR / PERCENTUAL A SER CONCEDIDO', 2, 51, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14984, 873, 'codigo_multa', 'CÓDIGO DA MULTA', 2, 66, '', 1, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14985, 873, 'data_multa', 'DATA DA MULTA', 1, 67, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14986, 873, 'valor_multa', 'VALOR / PERCENTUAL A SER APLICADO', 2, 75, '', 15, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (14987, 873, 'informacao_sacado', 'INFORMAÇÃO AO SACADO', 13, 90, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14988, 873, 'mensagem_3', 'MENSAGEM 3', 13, 100, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14989, 873, 'mensagem_4', 'MENSAGEM 4', 13, 140, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (14990, 873, 'exclusivo_febraban_2', 'USO EXCLUSIVO FEBRABAN', 13, 180, '', 61, false, true, 'd', '', 0 );
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo values
    (3979, 'reciboregistra', 'Recibos que devem ser registrados através da cobrança registrada', 'k146', '2016-10-07', 'ReciboRegistra', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod
    values (5,3979);
$$);

select fc_executa_ddl($$
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22092 ,'k146_numpre' ,'int4' ,'Numpre' ,'' ,'Numpre' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Numpre' ),
           ( 22093 ,'k146_convenio' ,'int4' ,'Convenio' ,'' ,'Convenio' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Convenio' );

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3979 ,22092 ,1 ,0 ),
           ( 3979 ,22093 ,2 ,0 );

  insert into db_sysforkey
    values (3979,22093,1,2185,0);

  insert into db_sysindices
    values (4387,'reciboregistra_numpre_in',3979,'0');

  insert into db_syscadind
    values (4387,22092,1);
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3981, 'remessacobrancaregistrada', 'Remessas geradas de Cobrança Registrada', 'k147', '2016-10-07', 'RemessaCobrancaRegistrada', 0, 'f', 'f', 'f', 'f' );

  insert into db_sysarqmod
    values (5,3981);
$$);

select fc_executa_ddl($$
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22100 ,'k147_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22101 ,'k147_instit' ,'int4' ,'Intituição' ,'' ,'Intituição' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Intituição' ),
           ( 22102 ,'k147_convenio' ,'int4' ,'Convênio' ,'' ,'Convênio' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Convênio' ),
           ( 22103 ,'k147_sequencialremessa' ,'int4' ,'Sequencial Remessa' ,'' ,'Sequencial Remessa' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial Remessa' ),
           ( 22104 ,'k147_dataemissao' ,'date' ,'Data de Emissão' ,'' ,'Data de Emissão' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data de Emissão' ),
           ( 22105 ,'k147_horaemissao' ,'char(5)' ,'Hora da Emissão' ,'' ,'Hora da Emissão' ,5 ,'false' ,'true' ,'false' ,0 ,'text' ,'Hora da Emissão' );

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3981 ,22100 ,1 ,0 ),
           ( 3981 ,22101 ,2 ,0 ),
           ( 3981 ,22102 ,3 ,0 ),
           ( 3981 ,22103 ,4 ,0 ),
           ( 3981 ,22104 ,5 ,0 ),
           ( 3981 ,22105 ,6 ,0 );

  insert into db_syssequencia
    values (1000610, 'remessacobrancaregistrada_k147_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  update db_sysarqcamp set codsequencia = 1000610 where codarq = 3981 and codcam = 22100;

  insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values (3981,22100,1,22100);

  insert into db_sysforkey
    values (3981,22102,1,2185,0);
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3982, 'remessacobrancaregistradarecibo', 'Recibo vinculado a remessa gerada para cobrança registrada', 'k148', '2016-10-07', 'RemessaCobrancaRegistradaRecibo', 0, 'f', 'f', 'f', 'f' );

  insert into db_sysarqmod
    values (5,3982);
$$);

select fc_executa_ddl($$
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22107 ,'k148_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22108 ,'k148_remessacobrancaregistrada' ,'int4' ,'Remessa Cobrança Registrada' ,'' ,'Remessa Cobrança Registrada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Remessa Cobrança Registrada' ),
           ( 22109 ,'k148_numpre' ,'int4' ,'Numpre' ,'' ,'Numpre' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Numpre' );

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3982 ,22107 ,1 ,0 ),
           ( 3982 ,22108 ,2 ,0 ),
           ( 3982 ,22109 ,3 ,0 );

  insert into db_syssequencia
    values (1000611, 'remessacobrancaregistradarecibo_k148_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  update db_sysarqcamp set codsequencia = 1000611 where codarq = 3982 and codcam = 22107;

  insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values (3982,22107,1,22107);

  insert into db_sysforkey
    values (3982,22108,1,3981,0);
$$);

select fc_executa_ddl($$
  insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10324 ,'Cobrança Registrada' ,'Cobrança Registrada para Recibos' ,'' ,'1' ,'1' ,'Menu para Recibos emitidos com o convênio de Cobrança Registrada.' ,'true' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10324 ,474 ,1985522 );
  insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10325 ,'Exportação' ,'Exportação de Cobrança Registrada' ,'arr4_cobrancaregistradaexportacao001.php' ,'1' ,'1' ,'Exportação dos dados de Recibos emitidos com o convênio de Cobrança Registrada' ,'false' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10324 ,10325 ,1 ,1985522 );
$$);

select fc_executa_ddl($$
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22121 ,'ar13_contabancaria' ,'int4' ,'Conta Bancária' ,'null' ,'Conta Bancária' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Conta Bancária' );

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 2186 ,22121 ,11 ,0 );

  insert into db_sysforkey
    values (2186, 22121, 1, 2740, 0);
$$);

select fc_executa_ddl($$
create sequence caixa.remessacobrancaregistrada_k147_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;
$$);

select fc_executa_ddl($$
create sequence caixa.remessacobrancaregistradarecibo_k148_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;
$$);

create table if not exists caixa.reciboregistra(
  k146_numpre   integer not null,
  k146_convenio integer not null,
  constraint reciboregistra_convenio_fk foreign key (k146_convenio) references cadconvenio
);

select fc_executa_ddl($$
  create index reciboregistra_numpre_in on caixa.reciboregistra(k146_numpre);
$$);

create table if not exists caixa.remessacobrancaregistrada(
  k147_sequencial   integer not null  default nextval('caixa.remessacobrancaregistrada_k147_sequencial_seq'),
  k147_instit integer not null,
  k147_convenio integer not null,
  k147_sequencialremessa integer not null,
  k147_dataemissao date not null,
  k147_horaemissao char(5),
  constraint remessacobrancaregistrada_sequ_pk primary key (k147_sequencial),
  constraint remessacobrancaregistrada_convenio_fk foreign key (k147_convenio) references cadconvenio
);

create table if not exists caixa.remessacobrancaregistradarecibo(
  k148_sequencial   integer not null  default nextval('caixa.remessacobrancaregistradarecibo_k148_sequencial_seq'),
  k148_remessacobrancaregistrada integer not null,
  k148_numpre   integer,
  constraint remessacobrancaregistradarecibo_sequ_pk primary key (k148_sequencial),
  constraint remessacobrancaregistradarecibo_remessacobrancaregistrada_fk foreign key (k148_remessacobrancaregistrada) references remessacobrancaregistrada
);

select fc_executa_ddl($$
  alter table conveniocobranca add column ar13_contabancaria integer;
  alter table conveniocobranca add CONSTRAINT conveniocobranca_ar13_contabancaria_fk FOREIGN KEY (ar13_contabancaria) REFERENCES contabancaria;
$$);

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}