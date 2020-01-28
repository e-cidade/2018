<?php

use Classes\PostgresMigration;

class M7028 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

select fc_executa_ddl($$

  create sequence agua.aguacoletorexportadadoscontrato_x57_sequencial_seq
  increment 1
  minvalue  1
  maxvalue  9223372036854775807
  start     1
  cache     1;

  create table agua.aguacoletorexportadadoscontrato(
  x57_sequencial               int4 not null  default nextval('aguacoletorexportadadoscontrato_x57_sequencial_seq'),
  x57_cgm                      int4 not null,
  x57_aguacontrato             int4 not null,
  x57_aguacategoriaconsumo     int4,
  x57_aguaisencaocgm           int4,
  x57_aguacoletorexportadados  int4 not null,
  constraint aguacoletorexportadadoscontrato_sequ_pk primary key (x57_sequencial));

  alter table agua.aguacoletorexportadadoscontrato
  add constraint aguacoletorexportadadoscontrato_cgm_fk foreign key (x57_cgm)
  references cgm;

  alter table agua.aguacoletorexportadadoscontrato
  add constraint aguacoletorexportadadoscontrato_aguaisencaocgm_fk foreign key (x57_aguaisencaocgm)
  references aguaisencaocgm;

  alter table agua.aguacoletorexportadadoscontrato
  add constraint aguacoletorexportadadoscontrato_aguacoletorexportadados_fk foreign key (x57_aguacoletorexportadados)
  references aguacoletorexportadados;

  alter table agua.aguacoletorexportadadoscontrato
  add constraint aguacoletorexportadadoscontrato_aguacontrato_fk foreign key (x57_aguacontrato)
  references aguacontrato;

  alter table agua.aguacoletorexportadadoscontrato
  add constraint aguacoletorexportadadoscontrato_aguacategoriaconsumo_fk foreign key (x57_aguacategoriaconsumo)
  references aguacategoriaconsumo;

  alter table agua.aguacoletorexporta add column x49_db_layouttxt int4;

  alter table agua.aguacoletorexporta
  add constraint aguacoletorexporta_db_layouttxt_fk foreign key (x49_db_layouttxt)
  references db_layouttxt;

  insert into db_layouttxtgrupo values (7, 1, 'AGUA TARIFA - EXPORT./IMPORT. COLETORES');

  insert into db_layouttxt values (265, 'SITUACOES DE LEITURA', 0, '', 7 );
  insert into db_layoutlinha values (876, 265, 'REGISTRO', 3, 50, 0, 0, '', '', false );
  insert into db_layoutcampos values (15054, 876, 'codigo', 'CODIGO DA SITUACAO', 1, 1, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15056, 876, 'descricao', 'DESCRICAO DA SITUACAO', 1, 6, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15057, 876, 'regra', 'REGRA DA SITUACAO', 1, 46, '', 5, false, true, 'e', '', 0 );

  insert into db_layouttxt values (266, 'CATEGORIA DE CONSUMO', 9, 'Informações das estruturas tarifárias que formam as categorias de consumo.', 7 );
  insert into db_layoutlinha values (877, 266, 'REGISTRO', 3, 176, 0, 0, '', '', false );
  insert into db_layoutcampos values (15055, 877, 'codigo', 'CÓDIGO', 2, 1, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15058, 877, 'descricao', 'DESCRIÇÃO', 1, 11, '', 100, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15059, 877, 'exercicio', 'EXERCÍCIO', 2, 111, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15060, 877, 'faixa_inicial', 'FAIXA INICIAL', 2, 115, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15061, 877, 'faixa_final', 'FAIXA FINAL', 2, 125, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15062, 877, 'valor', 'VALOR DA FAIXA', 1, 135, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15063, 877, 'valor_tarifa_agua', 'TARIFA BÁSICA DE ÁGUA', 1, 147, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15064, 877, 'valor_tarifa_esgoto', 'TARIFA BÁSICA DE ESGOTO', 1, 159, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15065, 877, 'percentual_esgoto', 'PERCENTUAL DE ESGOTO (CONSUMO)', 2, 171, '', 6, false, true, 'e', '', 0 );

  insert into db_layouttxt values (267, 'LEITURISTAS', 0, '', 7 );
  insert into db_layoutlinha values (878, 267, 'REGISTRO', 3, 140, 0, 0, '', '', false );
  insert into db_layoutcampos values (15066, 878, 'codigo', 'CODIGO DO LEITURISTA', 1, 1, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15067, 878, 'nome', 'NOME DO LEITURISTA', 1, 11, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15068, 878, 'senha', 'SENHA DO LEITURISTA', 1, 51, '', 40, false, true, 'd', '', 0 );

  insert into db_layouttxt values (268, 'ECONOMIAS', 1, '', 7 );
  insert into db_layoutlinha values (879, 268, 'REGISTRO', 3, 34, 0, 0, '', '', false );
  insert into db_layoutcampos values (15069, 879, 'codigo_contrato', 'CÓDIGO DO CONTRATO', 1, 11, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15071, 879, 'codigo_matricula', 'CÓDIGO DA MATRÍCULA', 1, 1, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15072, 879, 'codigo_categoria_consumo', 'CATEGORIA DE CONSUMO', 1, 21, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15073, 879, 'quantidade_economias', 'QUANTIDADE DE ECONOMIAS', 1, 31, '', 4, false, true, 'e', '', 0 );

  insert into db_layouttxt values (269, 'ISENÇÕES', 0, '', 7 );
  insert into db_layoutlinha values (880, 269, 'REGISTRO', 3, 146, 0, 0, '', '', false );
  insert into db_layoutcampos values (15074, 880, 'codigo', 'CÓDIGO', 1, 1, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15075, 880, 'descricao', 'DESCRIÇÃO', 1, 11, '', 100, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15076, 880, 'tipo_isencao', 'TIPO', 1, 111, '', 10, false, true, 'e', '', 0 );

  insert into db_layouttxt values (272, 'ROTAS E LEITURAS', 0, '', 7 );
  insert into db_layoutlinha values (883, 272, 'REGISTROS', 3, 2582, 0, 0, '', '', false );
  insert into db_layoutcampos values (15101, 883, 'ano', 'ANO DE REFERENCIA', 1, 31, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15102, 883, 'mes', 'MES DE REFERENCIA', 1, 35, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15103, 883, 'codigo_contrato', 'CODIGO DO CONTRATO', 1, 37, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15104, 883, 'codigo_cobranca', 'CODIGO DE COBRANCA (NUMNOV)', 1, 47, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15105, 883, 'codigo_matricula', 'CODIGO DA MATRICULA', 1, 57, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15106, 883, 'nome_responsavel', 'NOME RESPONSAVEL NO CONTRATO', 1, 67, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15107, 883, 'documento_responsavel', 'DOCUMENTO DO RESPONSAVEL NO CONTRATO', 1, 137, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15108, 883, 'codigo_logradouro', 'CODIGO DO LOGRADOURO', 1, 151, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15109, 883, 'tipo_logradouro', 'CODIGO DO TIPO DE LOGRADOURO', 1, 161, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15110, 883, 'nome_logradouro', 'NOME DO LOGRADOURO', 1, 165, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15111, 883, 'numero', 'NUMERO DO IMOVEL', 1, 205, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15112, 883, 'letra', 'LETRA DO IMOVEL', 1, 210, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15113, 883, 'complemento', 'COMPLEMENTO DO IMOVEL', 1, 211, '', 30, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15114, 883, 'bairro', 'BAIRRO DO IMOVEL', 1, 241, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15115, 883, 'cidade', 'CIDADE DO IMOVEL', 1, 281, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15116, 883, 'estado', 'ESTADO DO IMOVEL', 1, 321, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15117, 883, 'zona', 'ZONA FISCAL DO IMOVEL', 1, 323, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15118, 883, 'quadra', 'QUADRA DO IMOVEL', 1, 326, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15119, 883, 'economias', 'NUMERO DE ECONOMIAS', 1, 330, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15120, 883, 'codigo_categoria_consumo', 'CODIGO DA CATEGORIA DE CONSUMO', 1, 333, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15121, 883, 'descricao_categoria_consumo', 'DESCRICAO DA CATEGORIA DE CONSUMO', 1, 343, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15122, 883, 'codigo_hidrometro', 'CODIGO DO HIDROMETRO', 1, 383, '', 20, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15287, 883, 'codigo_tipo_isencao', 'CODIGO DO TIPO DE ISENCAO', 1, 403, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15123, 883, 'dt_leitura_atual', 'DATA DA LEITURA ATUAL', 1, 413, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15124, 883, 'dt_leitura_anterior', 'DATA DA LEITURA ANTERIOR', 1, 423, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15125, 883, 'consumo', 'ULTIMO CONSUMO REGISTRADO', 1, 433, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15126, 883, 'dias_leitura', 'DIAS ENTRE A ULTIMA LEITURA', 1, 441, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15288, 883, 'dt_vencimento', 'DATA DE VENCIMENTO', 1, 445, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15127, 883, 'valor_acrescimo', 'VALOR DE ACRECIMO', 1, 455, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15128, 883, 'valor_desconto', 'VALOR DE DESCONTO', 1, 467, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15129, 883, 'valor_total', 'VALOR TOTAL DAS TARIFAS', 1, 479, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15162, 883, 'leitura_1_ano', 'ANO DE REFERENCIA', 1, 491, '', 4, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15163, 883, 'leitura_1_mes', 'MES DE REFERENCIA', 1, 495, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15165, 883, 'leitura_1_situacao', 'SITUACAO DA LEITURA', 1, 497, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15166, 883, 'leitura_1_leitura', 'LEITURA', 1, 500, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15167, 883, 'leitura_1_consumo', 'CONSUMO', 1, 507, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15169, 883, 'leitura_1_dias', 'DIAS ENTRE LEITURAS', 1, 514, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15170, 883, 'leitura_2_ano', 'ANO DE REFERENCIA', 1, 518, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15171, 883, 'leitura_2_mes', 'MES DE REFERENCIA', 1, 522, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15172, 883, 'leitura_2_situacao', 'SITUACAO DA LEITURA', 1, 524, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15173, 883, 'leitura_2_leitura', 'LEITURA', 1, 527, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15174, 883, 'leitura_2_consumo', 'CONSUMO', 1, 534, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15175, 883, 'leitura_2_dias', 'DIAS ENTRE LEITURAS', 1, 541, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15176, 883, 'leitura_3_ano', 'ANO DE REFERENCIA', 1, 545, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15177, 883, 'leitura_3_mes', 'MES DE REFERENCIA', 1, 549, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15178, 883, 'leitura_3_situacao', 'SITUACAO DA LEITURA', 1, 551, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15179, 883, 'leitura_3_leitura', 'LEITURA', 1, 554, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15180, 883, 'leitura_3_consumo', 'CONSUMO', 1, 561, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15181, 883, 'leitura_3_dias', 'DIAS ENTRE LEITURAS', 1, 568, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15182, 883, 'leitura_4_ano', 'ANO DE REFERENCIA', 1, 572, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15183, 883, 'leitura_4_mes', 'MES DE REFERENCIA', 1, 576, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15184, 883, 'leitura_4_situacao', 'SITUACAO DA LEITURA', 1, 578, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15185, 883, 'leitura_4_leitura', 'LEITURA', 1, 581, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15186, 883, 'leitura_4_consumo', 'CONSUMO', 1, 588, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15187, 883, 'leitura_4_dias', 'DIAS ENTRE LEITURAS', 1, 595, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15188, 883, 'leitura_5_ano', 'ANO DE REFERENCIA', 1, 599, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15189, 883, 'leitura_5_mes', 'MES DE REFERENCIA', 1, 603, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15190, 883, 'leitura_5_situacao', 'SITUACAO DA LEITURA', 1, 605, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15191, 883, 'leitura_5_leitura', 'LEITURA', 1, 608, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15192, 883, 'leitura_5_consumo', 'CONSUMO', 1, 615, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15193, 883, 'leitura_5_dias', 'DIAS ENTRE LEITURAS', 1, 622, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15281, 883, 'leitura_6_ano', 'ANO DE REFERENCIA', 1, 626, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15282, 883, 'leitura_6_mes', 'MES DE REFERENCIA', 1, 630, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15283, 883, 'leitura_6_situacao', 'SITUACAO DA LEITURA', 1, 632, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15284, 883, 'leitura_6_leitura', 'LEITURA', 1, 635, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15285, 883, 'leitura_6_consumo', 'CONSUMO', 1, 642, '', 7, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15286, 883, 'leitura_6_dias', 'DIAS ENTRE LEITURAS', 1, 649, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15194, 883, 'titulo_receita_1', 'TITULO DA TABELA DE RECEITA', 1, 653, '', 60, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15195, 883, 'receita_1_codigo', 'CODIGO DA RECEITA', 1, 713, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15196, 883, 'receita_1_descricao', 'DESCRICAO DA RECEITA', 1, 721, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15197, 883, 'receita_1_parcela', 'PARCELA DA RECEITA', 1, 738, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15198, 883, 'receita_1_valor', 'VALOR DO DEBITO', 1, 747, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15199, 883, 'receita_1_numpre', 'NUMPRE RECEITA', 1, 759, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15200, 883, 'receita_2_codigo', 'CODIGO DA RECEITA', 1, 773, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15201, 883, 'receita_2_descricao', 'DESCRICAO DA RECEITA', 1, 781, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15202, 883, 'receita_2_parcela', 'PARCELA DA RECEITA', 1, 798, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15203, 883, 'receita_2_valor', 'VALOR DO DEBITO', 1, 807, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15204, 883, 'receita_2_numpre', 'NUMPRE DA RECEITA', 1, 819, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15205, 883, 'receita_3_codigo', 'CODIGO DA RECEITA', 1, 833, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15206, 883, 'receita_3_descricao', 'DESCRICAO DA RECEITA', 1, 841, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15207, 883, 'receita_3_parcela', 'PARCELA DA RECEITA', 1, 858, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15208, 883, 'receita_3_valor', 'VALOR DO DEBITO', 1, 867, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15209, 883, 'receita_3_numpre', 'NUMPRE DA RECEITA', 1, 879, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15210, 883, 'receita_4_codigo', 'CODIGO DA RECEITA', 1, 893, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15211, 883, 'receita_4_descricao', 'DESCRICAO DA RECEITA', 1, 901, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15212, 883, 'receita_4_parcela', 'PARCELA DA RECEITA', 1, 918, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15213, 883, 'receita_4_valor', 'VALOR DO DEBITO', 1, 927, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15214, 883, 'receita_4_numpre', 'NUMPRE DA RECEITA', 1, 939, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15215, 883, 'titulo_receita_2', 'TITULO DA TABELA DE RECEITA', 1, 953, '', 60, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15216, 883, 'receita_5_codigo', 'CODIGO DA RECEITA', 1, 1013, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15217, 883, 'receita_5_descricao', 'DESCRICAO DA RECEITA', 1, 1021, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15218, 883, 'receita_5_parcela', 'PARCELA DA RECEITA', 1, 1038, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15219, 883, 'receita_5_valor', 'VALOR DO DEBITO', 1, 1047, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15220, 883, 'receita_5_numpre', 'NUMPRE DA RECEITA', 1, 1059, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15221, 883, 'receita_6_codigo', 'CODIGO DA RECEITA', 1, 1073, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15222, 883, 'receita_6_descricao', 'DESCRICAO DA RECEITA', 1, 1081, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15223, 883, 'receita_6_parcela', 'PARCELA DA RECEITA', 1, 1098, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15224, 883, 'receita_6_valor', 'VALOR DO DEBITO', 1, 1107, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15225, 883, 'receita_6_numpre', 'NUMPRE DA RECEITA', 1, 1119, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15226, 883, 'receita_7_codigo', 'CODIGO DA RECEITA', 1, 1133, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15227, 883, 'receita_7_descricao', 'DESCRICAO DA RECEITA', 1, 1141, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15228, 883, 'receita_7_parcela', 'PARCELA DA RECEITA', 1, 1158, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15229, 883, 'receita_7_valor', 'VALOR DO DEBITO', 1, 1167, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15230, 883, 'receita_7_numpre', 'NUMPRE DA RECEITA', 1, 1179, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15231, 883, 'receita_8_codigo', 'CODIGO DA RECEITA', 1, 1193, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15232, 883, 'receita_8_descricao', 'DESCRICAO DA RECEITA', 1, 1201, '', 17, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15233, 883, 'receita_8_parcela', 'PARCELA DA RECEITA', 1, 1218, '', 9, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15234, 883, 'receita_8_valor', 'VALOR DO DEBITO', 1, 1227, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15235, 883, 'receita_8_numpre', 'NUMPRE DA RECEITA', 1, 1239, '', 14, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15236, 883, 'aviso1', 'AVISO', 1, 1253, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15237, 883, 'aviso2', 'AVISO', 1, 1323, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15238, 883, 'aviso3', 'AVISO', 1, 1393, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15239, 883, 'aviso4', 'AVISO', 1, 1463, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15240, 883, 'aviso5', 'AVISO', 1, 1533, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15241, 883, 'aviso6', 'AVISO', 1, 1603, '', 453, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15242, 883, 'msg1', 'MENSAGEM', 1, 2056, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15243, 883, 'msg2', 'MENSAGEM', 1, 2126, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15244, 883, 'msg3', 'MENSAGEM', 1, 2196, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15245, 883, 'msg4', 'MENSAGEM', 1, 2266, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15246, 883, 'msg5', 'MENSAGEM', 1, 2336, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15247, 883, 'msg6', 'MENSAGEM', 1, 2406, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15248, 883, 'imprime_conta', 'IMPRIMIR CONTA', 1, 2476, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15249, 883, 'codigo_coletor', 'CODIGO DO COLETOR', 1, 2478, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15250, 883, 'aviso_leiturista', 'AVISO LEITURISTA', 1, 2481, '', 200, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15098, 883, 'codigo', 'CODIGO DA LEITURA', 1, 1, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15099, 883, 'codigo_leiturista', 'CODIGO DO LEITURISTA', 1, 11, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15100, 883, 'codigo_rota', 'CODIGO DA ROTA', 1, 21, '', 10, false, true, 'e', '', 0 );

  -- aguacoletorexportadadoscontrato
  insert into db_sysarquivo values (3992, 'aguacoletorexportadadoscontrato', 'Dados do contrato.', 'x57', '2016-11-22', 'Dados do Contrato Exportação Coletores', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (43,3992);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22171 ,'x57_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22171 ,1 ,0 );

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22173 ,'x57_aguacontrato' ,'int4' ,'Contrato' ,'' ,'Contrato' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Contrato' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22173 ,3 ,0 );

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22174 ,'x57_aguacategoriaconsumo' ,'int4' ,'Categoria de Consumo' ,'' ,'Categoria de Consumo' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Categoria de Consumo' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22174 ,4 ,0 );

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22175 ,'x57_aguaisencaocgm' ,'int4' ,'Isenção' ,'' ,'Isenção' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Isenção' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22175 ,5 ,0 );

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22176 ,'x57_aguacoletorexportadados' ,'int4' ,'Matrícula Exportada' ,'' ,'Matrícula Exportada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Matrícula Exportada' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22176 ,6 ,0 );

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22211 ,'x57_cgm' ,'int4' ,'CGM' ,'' ,'Nome/Razão Social' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Nome/Razão Social' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3992 ,22211 ,6 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3992,22171,1,22171);

  insert into db_sysforkey values(3992,22173,1,3966,0);
  insert into db_sysforkey values(3992,22175,1,3977,0);
  insert into db_sysforkey values(3992,22174,1,3969,0);
  insert into db_sysforkey values(3992,22176,1,2703,0);
  insert into db_sysforkey values(3992,22211,1,42,0);

  insert into db_syssequencia values(1000621, 'aguacoletorexportadadoscontrato_x57_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000621 where codarq = 3992 and codcam = 22171;

  -- aguacoletorexporta
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22178 ,'x49_db_layouttxt' ,'int4' ,'Layout do arquivo.' ,'' ,'Layout' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Layout' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2701 ,22178 ,7 ,0 );
  insert into db_sysforkey values(2701,22178,1,1553,0);

  -- Layout de importação
  insert into db_layouttxt values (276, 'IMPORTAÇÃO', 1, '', 7 );
  insert into db_layoutlinha values (888, 276, 'REGISTRO', 3, 454, 0, 0, '', '', false );
  insert into db_layoutcampos values (15289, 888, 'codigo_exportacao', 'CÓDIGO DA LEITURA', 1, 1, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15290, 888, 'codigo_rota', 'CÓDIGO DA ROTA', 1, 9, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15291, 888, 'codigo_leiturista', 'CÓDIGO DO LEITURISTA', 1, 19, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15292, 888, 'codigo_contrato', 'CÓDIGO DO CONTRATO', 1, 29, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15293, 888, 'codigo_matricula', 'CÓDIGO DA MATRÍCULA', 1, 39, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15294, 888, 'numero_hidrometro', 'NÚMERO DO HIDRÔMETRO', 1, 49, '', 20, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15295, 888, 'data_leitura_atual', 'DATA DA LEITURAL ATUAL', 1, 69, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15296, 888, 'data_leitura_anterior', 'DATA DA LEITURA ANTERIOR', 1, 79, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15297, 888, 'consumo_atual', 'CONSUMO ATUAL', 1, 89, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15298, 888, 'dias_entre_leituras', 'DIAS ENTRE LEITURAS', 1, 97, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15299, 888, 'media_consumo_dia', 'MÉDIA DE CONSUMO AO DIA', 1, 101, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15300, 888, 'data_vencimento', 'DATA DE VENCIMENTO', 1, 111, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15301, 888, 'valor_desconto', 'VALOR DE DESCONTO', 1, 121, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15302, 888, 'valor_total', 'VALOR TOTAL', 1, 133, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15303, 888, 'valor_agua', 'VALOR DE ÁGUA', 1, 145, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15304, 888, 'valor_esgoto', 'VALOR DE ESGOTO', 1, 157, '', 12, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15305, 888, 'mes_leitura', 'MÊS DA LEITURA', 1, 169, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15306, 888, 'situacao_leitura', 'SITUAÇÃO DA LEITURA', 1, 171, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15307, 888, 'leitura', 'LEITURA', 1, 174, '', 10, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15308, 888, 'consumo', 'CONSUMO', 1, 184, '', 8, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15309, 888, 'ultimo_dia_leitura', 'DIA DA ÚLTIMA LEITURA', 1, 192, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15310, 888, 'conta_emitida', 'CONTA EMITIDA?', 1, 202, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15311, 888, 'observacao_leiturista', 'OBSERVAÇÃO DO LEITURISTA', 1, 203, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15312, 888, 'linha_digitavel', 'LINHA DIGITÁVEL', 1, 273, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15314, 888, 'codigo_barras', 'CÓDIGO DE BARRAS', 1, 343, '', 70, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15315, 888, 'leitura_coletada', 'LEITURA COLETADA', 1, 413, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15316, 888, 'hidrometro_virou', 'HIDRÔMETRO VIROU', 1, 414, '', 1, false, true, 'd', '', 0 );
$$);

-- fc_agua_atualizaultimaleitura
create or replace function fc_agua_atualizaultimaleitura_beforedelete() returns trigger as
$$
begin

  -- Apaga antiga leitura
  delete
    from aguahidromatricultimaleitura
   where x09_codleitura    = old.x21_codleitura
     and x09_codhidrometro = old.x21_codhidrometro;

  return old;
end;
$$
language 'plpgsql';

create or replace function fc_agua_atualizaultimaleitura() returns trigger as
$$
declare
  iMatric          integer;
  sOperacao        text    := '';
  iCodLeitura      integer;
  iCodHidrometro   integer;
  iCodigo          integer;
begin

  if (fc_getsession('DB_desativar_agua_atualizaultimaleitura') = 'true') then
    return new;
  end if;

  sOperacao := upper(TG_OP);

  -- Verifica Operacao da trigger para pegar codigo do hidrometro
  if (sOperacao = 'DELETE') then
    iCodHidrometro := old.x21_codhidrometro;
    iCodLeitura    := old.x21_codleitura;
  else
    iCodHidrometro := new.x21_codhidrometro;
  end if;

  -- Busca Matricula do Hidrometro
  select x04_matric
    into iMatric
    from aguahidromatric
   where x04_codhidrometro = iCodHidrometro;

  -- Busca Codigo da Ultima Leitura dessa Matricula
  iCodLeitura := fc_agua_ultimaleituracodigo(iMatric);

  -- Busca Codigo do Hidrometro da Ultima Leitura
  select x21_codhidrometro
    into iCodHidrometro
    from agualeitura
   where x21_codleitura = iCodLeitura;

  --raise notice 'CodLeitura (%) CodHidrometro (%)', iCodLeitura, iCodHidrometro;

  -- Verifica se ja gerou registro na tabela aguahidromatricultimaleitura
  select x09_codigo
    into iCodigo
    from aguahidromatricultimaleitura
   where x09_codhidrometro = iCodHidrometro;

  -- Se nao Gerou...
  if not found then
    -- ... insere registro na tabela que guarda a ultima leitura do hidrometro
    insert
      into aguahidromatricultimaleitura (
             x09_codigo,
             x09_codleitura,
             x09_codhidrometro )
    values ( nextval('aguahidromatricultimaleitura_x09_codigo_seq'),
             iCodLeitura,
             iCodHidrometro );
  -- Caso contrario ...
  else
    -- ... atualiza ultima leitura na tabela aguahidromatricultimaleitura
    update aguahidromatricultimaleitura
       set x09_codleitura    = iCodLeitura,
           x09_codhidrometro = iCodHidrometro
     where x09_codigo = iCodigo;
    --
  end if;

  if (sOperacao = 'DELETE') then
    return old;
  end if;

  return new;
end;
$$
language 'plpgsql';

drop trigger tr_agua_atualizaultimaleitura on agualeitura;

create trigger tr_agua_atualizaultimaleitura
after insert or update or delete
on agualeitura for each row
execute procedure fc_agua_atualizaultimaleitura();


drop trigger tr_agua_atualizaultimaleitura_beforedelete on agualeitura;

create trigger tr_agua_atualizaultimaleitura_beforedelete
before delete
on agualeitura for each row
execute procedure fc_agua_atualizaultimaleitura_beforedelete();


-- fc_agua_calculasaldoposterior
SET check_function_bodies TO off;
create or replace function fc_agua_calculasaldoposterior() returns trigger as $$
declare

iMatric        integer;
iAno           integer;
iMes           integer;
iCodHidrometro integer;
iCodLeitura    integer;
iLeitura       integer;
sData          text;
dData          date;
bAtualiza      boolean := true;
sOperacao      text;
crLeitura      refcursor;
rLeituras      record;
sControleLoop  text;
iLeituraAtual  integer;
begin

  if (fc_getsession('DB_desativar_agua_calculasaldoposterior') = 'true') then

    if (sOperacao = 'DELETE') then
      return old;
    else
      return new;
    end if;

  end if;

  sOperacao     := upper(TG_OP);

  if sOperacao = 'DELETE' then

    iAno           := old.x21_exerc;
    iMes           := old.x21_mes;
    iCodHidrometro := old.x21_codhidrometro;
    iCodLeitura    := old.x21_codleitura;
    iLeitura       := old.x21_leitura;
  else

    iAno           := new.x21_exerc;
    iMes           := new.x21_mes;
    iCodHidrometro := new.x21_codhidrometro;
    iCodLeitura    := new.x21_codleitura;
    iLeitura       := new.x21_leitura;
  end if;

  sData          := iAno||'-'||iMes||'-01';
  dData          := to_date(sData, 'yyyy-mm-dd');

  select x04_matric
    into iMatric
    from aguahidromatric
   where x04_codhidrometro = iCodHidrometro;

  update agualeitura
    set x21_codleitura    = subquery_leituras.x21_codleitura,
        x21_codhidrometro = subquery_leituras.x21_codhidrometro,
        x21_exerc         = subquery_leituras.x21_exerc,
        x21_mes           = subquery_leituras.x21_mes,
        x21_situacao      = subquery_leituras.x21_situacao,
        x21_numcgm        = subquery_leituras.x21_numcgm,
        x21_dtleitura     = subquery_leituras.x21_dtleitura,
        x21_leitura       = subquery_leituras.x21_leitura,
        x21_consumo       = subquery_leituras.x21_consumo,
        x21_excesso       = subquery_leituras.x21_excesso,
        x21_virou         = subquery_leituras.x21_virou,
        x21_tipo          = subquery_leituras.x21_tipo,
        x21_status        = subquery_leituras.x21_status,
        x21_saldo         = subquery_leituras.x21_saldo
    from (select * from (select agualeitura.*
                           from agualeitura
                          inner join aguahidromatric on x04_codhidrometro = x21_codhidrometro
                          where x04_matric = iMatric
                            and x21_status = 1
                            and (   (    x21_exerc = 2011
                                     and x21_mes  > 3)
                                 or (    x21_exerc > 2011
                                     and x21_mes > 0)
                                )
                            and (x21_exerc, x21_mes) in (select extract(year from data)  as anousu,
                                                                extract(month from data) as mesusu
                                                           from (select cast(dData + cast(cast(mes as text) || cast('month' as text) as interval) as date) as data
                                                                   from generate_series(0, 12) as mes) as x)
                         group by x21_codleitura,
                                   x21_codhidrometro,
                                   x21_exerc,
                                   x21_mes,
                                   x21_situacao,
                                   x21_numcgm,
                                   x21_dtleitura,
                                   x21_usuario,
                                   x21_dtinc,
                                   x21_leitura,
                                   x21_consumo,
                                   x21_excesso,
                                   x21_virou,
                                   x21_tipo,
                                   x21_status,
                                   x21_saldo
                          order by x21_exerc,
                                   x21_mes,
                                   x21_codleitura) AS y
                   where    y.x21_codleitura > iCodLeitura
                   order by y.x21_exerc asc,
                            y.x21_mes   asc
                      limit 1) as subquery_leituras
   where agualeitura.x21_codleitura = subquery_leituras.x21_codleitura;

  if upper(TG_OP) = 'DELETE' then
    return old;
  else
    return new;
  end if;

end;
$$ language 'plpgsql';

drop trigger if exists tr_agua_calculasaldoposterior on agualeitura;

create trigger tr_agua_calculasaldoposterior
after insert or update or delete on agualeitura for each row
execute procedure fc_agua_calculasaldoposterior();


-- fc_agua_calculaconsumo
SET check_function_bodies TO off;
create or replace function fc_agua_calculaconsumo() returns trigger as
$$
declare
  iAno             integer;
  iMes             integer;
  iMatric          integer;
  iRegraSituacao   integer := 0;
  iMeses           integer := 0;
  nLeitura         float8  := 0;
  nConsumo         float8  := 0;
  nConsumoTotal    float8  := 0;
  nConsumoAnterior float8  := 0;
  nUltimaLeitura   float8  := 0;
  nConsumoMaximo   float8  := 0;
  nConsumoPadrao   float8  := 0;
  nExcesso         float8  := 0;
  nSaldo           float8  := 0;
  sOperacao        text    := '';
  iIdOcor          integer;
  iIdOcorMatric    integer;
  iCodLeitura      integer;
  lVirouHidrometro boolean;
  iQtdDigitosHidro integer;
  sDigitosHidro    text;
  iResidencial     integer;
begin
  sOperacao := upper(TG_OP);

  if (sOperacao = 'DELETE') then
    --raise exception 'Nao é permitido executar % na tabela %', sOperacao, TG_RELNAME;
    return old;
  end if;

  if (fc_getsession('DB_desativar_agua_calculaconsumo') = 'true') then
    return new;
  end if;

  -- Trata UPDATE
  if sOperacao = 'UPDATE' then
    if (new.x21_leitura = old.x21_leitura) and
       (new.x21_consumo <> old.x21_consumo or
        new.x21_excesso <> old.x21_excesso or
        new.x21_saldo   <> old.x21_saldo) then

      return new;
    end if;
    iCodLeitura := old.x21_codleitura;
  end if;

  -- Se estiver incluindo uma Leitura que nao for manual
  -- ou que seja inativa entao nao processa o calculo do
  -- consumo/excesso
  if new.x21_status <> 1 or new.x21_tipo = 2 then
    return new;
  end if;

  iAno := new.x21_exerc;
  iMes := new.x21_mes;

  select x04_matric,
         x04_qtddigito
    into iMatric,
         iQtdDigitosHidro
    from aguahidromatric
   where x04_codhidrometro = new.x21_codhidrometro;

  lVirouHidrometro := new.x21_virou;

  if fc_agua_hidrometroativo(new.x21_codhidrometro) = 'f' then
    raise exception 'Hidrometro % da matricula % nao esta ativo', new.x21_codhidrometro, iMatric;
  end if;

  nLeitura := new.x21_leitura;

  -- Verifica Consumo Maximo para a Matricula especificada
  nConsumoPadrao := coalesce(fc_agua_consumomaximo(iAno, iMes, iMatric), 0);

  -- Verifica a nao existencia de leituras anteriores (regra 1 de aguasitleitura)
  iMeses := fc_agua_mesesultimaleitura(iAno, iMes, iMatric, iCodLeitura);

  if iMeses > 0 then
    nConsumoMaximo := nConsumoPadrao * iMeses;
  else
    nConsumoMaximo := nConsumoPadrao;
  end if;

  -- Busca regra para calculo do consumo
  select x17_regra
    into iRegraSituacao
    from aguasitleitura
   where x17_codigo = new.x21_situacao;

  if sOperacao = 'UPDATE' then
    -- Acumula consumo anterior, caso ja exista alguma leitura no mes
    nConsumoAnterior := fc_agua_consumo(iAno, iMes, iMatric, old.x21_codleitura);
    nUltimaLeitura := coalesce(fc_agua_leituraanterior(iMatric, old.x21_codleitura), 0);
  else
    -- Acumula consumo anterior, caso ja exista alguma leitura no mes
    nConsumoAnterior := fc_agua_consumo(iAno, iMes, iMatric);
    nUltimaLeitura := coalesce(fc_agua_ultimaleitura(iMatric, iAno, iMes), 0);
  end if;

  -- Verifica se leitura atual eh menor que anterior, e se hidrometro nao virou
  -- observando se a regra da situacao nao eh a 2=CANCELADA (caso de troca de hidrometro)

  if (nLeitura < nUltimaLeitura) and (iRegraSituacao <> 2) and (new.x21_tipo <> 3) then

    if lVirouHidrometro is false then
      raise exception 'Leitura atual (%) menor que anterior (%)', nLeitura, nUltimaLeitura;
    end if;
  end if;

  if (nLeitura < nUltimaLeitura) and (new.x21_tipo = 3) then

    lVirouHidrometro := true;
  end if;

  iResidencial = fc_agua_existecaract(iMatric, 5001);
  --
  -- Regra = 0  LEITURA NORMAL Sem Virar Relógio do Hidrômetro
  -- . Efetua Procedimentos de Calculo de Consumo e Excesso
  --
  if iRegraSituacao = 0 and lVirouHidrometro is false then
    nConsumo      := nLeitura - nUltimaLeitura;
    nConsumoTotal := nConsumo + nConsumoAnterior;

    nExcesso := nConsumoTotal - nConsumoMaximo;

    if nConsumoTotal > nConsumoMaximo then

      nConsumo := nConsumo - nExcesso;

    elsif nConsumoTotal < nConsumoMaximo and iResidencial is not null then

      nSaldo   := nConsumoMaximo - nConsumo;

    end if;

  --
  -- Regra = 0  LEITURA NORMAL Virando Relógio do Hidrômetro
  -- . Efetua Procedimentos de Calculo de Consumo e Excesso
  --
  elsif iRegraSituacao = 0 and lVirouHidrometro is true then

    if iQtdDigitosHidro is null or iQtdDigitosHidro = 0 then
      raise exception 'Hidrometro % da matricula % nao esta com o campo DIGITOS devidamente configurado', new.x21_codhidrometro, iMatric;
    end if;

    sDigitosHidro := repeat('9', abs(iQtdDigitosHidro));
    nConsumo      := cast(sDigitosHidro as float8) - nUltimaLeitura + nLeitura;
    nConsumoTotal := nConsumo + nConsumoAnterior;

    nExcesso := nConsumoTotal - nConsumoMaximo;

    if nConsumoTotal > nConsumoMaximo then

      nConsumo := nConsumo - nExcesso;

    elsif nConsumoTotal < nConsumoMaximo and iResidencial is not null then

      nSaldo   := nConsumoMaximo - nConsumo;

    end if;

  --
  -- Regra = 1  SEM LEITURA
  -- . Nao Efetua Procedimentos de Calculo de Consumo e Excesso,
  --   repetindo a leitura anterior e atribuindo consumo padrao e Excesso = 0
  --
  elsif iRegraSituacao = 1 then

    nConsumo        := nConsumoPadrao;
    new.x21_leitura := nUltimaLeitura;
    nExcesso        := 0;
  elsif iRegraSituacao = 3 then

    nConsumo        := nConsumoPadrao;
    new.x21_leitura := nUltimaLeitura;
    nExcesso        := 0;

    if iResidencial is not null then
      nSaldo        := nConsumoPadrao;
    end if;

  end if;

  --Sistema deve dar saldo apartir de 01/04/2011
  if (nSaldo < 0) or (new.x21_exerc = 2011 and new.x21_mes < 4) or (new.x21_exerc < 2011 and new.x21_mes > 0) then
    nSaldo := 0;
  end if;

  raise info 'aaaaa';
  perform fc_agua_calculasaldo(iAno, iMes, iMatric, new.x21_codleitura, nExcesso, true);

  new.x21_consumo := nConsumo;
  new.x21_excesso := nExcesso;
  new.x21_saldo   := nSaldo;

  if sOperacao = 'UPDATE' then

    if ((new.x21_leitura <> old.x21_leitura) and new.x21_tipo <> 3) then

      iIdOcor       := nextval('histocorrencia_ar23_sequencial_seq');
      iIdOcorMatric := nextval('histocorrenciamatric_ar25_sequencial_seq');

      insert into histocorrencia
      (ar23_sequencial, ar23_id_usuario, ar23_instit, ar23_modulo, ar23_id_itensmenu, ar23_data , ar23_hora, ar23_tipo, ar23_descricao, ar23_ocorrencia)
      values
      (iIdOcor, cast(fc_getsession('DB_id_usuario') as integer), cast(fc_getsession('DB_instit') as integer), cast(fc_getsession('DB_modulo') as integer), cast(fc_getsession('DB_itemmenu_acessado') as integer), TO_DATE(fc_getsession('DB_datausu'), 'YYYY-MM-DD'), TO_CHAR(CURRENT_TIMESTAMP, 'HH24:MI'), 2, 'ALTERAÇAÕ DE LEITURA', 'Alterado a leitura do mês '||iMes||'/'||iAno||' de '||old.x21_leitura||' para '||new.x21_leitura||', sendo recalculado o excesso e a compensação.');

      insert into histocorrenciamatric
      (ar25_sequencial, ar25_matric, ar25_histocorrencia)
      values
      (iIdOcorMatric, iMatric, iIdOcor);

    end if;
  end if;

  return new;
end;
$$
language 'plpgsql';


drop trigger tr_agua_calculaconsumo on agualeitura;

create trigger tr_agua_calculaconsumo
before insert or update or delete
on agualeitura for each row
execute procedure fc_agua_calculaconsumo();

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}