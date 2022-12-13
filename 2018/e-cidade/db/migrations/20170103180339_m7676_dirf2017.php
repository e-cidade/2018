<?php

use Classes\PostgresMigration;

class M7676Dirf2017 extends PostgresMigration
{
   public function up()
   {
      
      $aLayout = array(
        'db50_codigo' => 279, 
        'db50_descr' => 'LAYOUT DIRF 2017', 
        'db50_quantlinhas' => 0, 
        'db50_obs' => '', 
        'db50_layouttxtgrupo' => 1
      );

      $this->execute("insert into pessoal.rhdirfparametros select nextval('pessoal.rhdirfparametros_rh132_sequencial_seq'), 2016, 28123.91, 'P49VS72' from  pessoal.rhdirfparametros where not exists(select 1 from  pessoal.rhdirfparametros where rh132_anobase = 2016) limit 1");
      $this->table('db_layouttxt', array('schema' => 'configuracoes'))
           ->insert(array_keys($aLayout), array(array_values($aLayout)))
           ->saveData();

      $aCampos = array('db51_codigo','db51_layouttxt','db51_descr','db51_tipolinha','db51_tamlinha','db51_linhasantes','db51_linhasdepois','db51_obs','db51_separador','db51_compacta');

      $aLayoutLinhas   = array();
      $aLayoutLinhas[] = array(899, 279, 'RRA', 3, 192, 0, 0, 'Registros de rendimentos recebidos acumuladamente.', '|', 'true');
      $aLayoutLinhas[] = array(900, 279, 'BPFRRA', 3, 135, 0, 0, 'Regras de validação do registro:\n- Deve estar classificado em ordem crescente por:\n- CPF;\n- Natureza do RRA;\n- Deve estar associado ao registro do tipo IDREC.', '|', 'true');
      $aLayoutLinhas[] = array(901, 279, 'QTMESES', 3, 55, 0, 0, 'Regras de validação do registro:\n- Deve ocorrer apenas um registro de cada identificador para o mesmo beneficiário;\n- Deve estar associado ao registro do tipo BPFRRA.', '|', 'true');
      $aLayoutLinhas[] = array(902, 279, 'VALORES MENSAIS - RTRT', 3, 200, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(903, 279, 'RESPO', 3, 86, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(904, 279, 'HEADER', 1, 32, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(905, 279, 'DECPJ', 3, 195, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(906, 279, 'IDREC', 3, 9, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(907, 279, 'BPFDEC', 3, 85, 0, 0, '', '|', 'false');
      $aLayoutLinhas[] = array(908, 279, 'BPJDEC', 3, 170, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(909, 279, 'IDENTIFICADOR_PSE', 3, 3, 0, 0, '', '|', 'false');
      $aLayoutLinhas[] = array(910, 279, 'INF', 3, 214, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(911, 279, 'FIMDIRF', 4, 7, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(912, 279, 'RIO', 3, 78, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(913, 279, 'PLANO_SAUDE', 3, 91, 0, 0, '', '|', 'false');
      $aLayoutLinhas[] = array(914, 279, 'operadora_PSE', 3, 176, 0, 0, 'OPERADOR DO PLANO DE SAUDE', '|', 'false');
      $aLayoutLinhas[] = array(915, 279, 'INFPC', 3, 200, 0, 0, '', '|', 'true');
      $aLayoutLinhas[] = array(916, 279, 'INFPA', 3, 86, 0, 0, '', '|', 'true');

      $this->table('db_layoutlinha', array('schema' => 'configuracoes'))
           ->insert($aCampos, $aLayoutLinhas)
           ->saveData();

      $aCampos = array('db52_codigo','db52_layoutlinha','db52_nome','db52_descr','db52_layoutformat','db52_posicao','db52_default','db52_tamanho','db52_ident','db52_imprimir','db52_alinha','db52_obs','db52_quebraapos');
      $aLayoutCampos   = array();
      $aLayoutCampos[] = array(15412, 903, 'telefone', 'TELEFONE', 1, 79, '', 9, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15413, 902, 'julho', 'JULHO', 1, 96, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15414, 902, 'junho', 'JUNHO', 1, 81, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15415, 905, 'cnpj', 'CNPJ', 1, 6, '', 14, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15416, 905, 'nome_empresarial', 'NOME EMPRESARIAL', 1, 20, '', 150, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15417, 905, 'natureza_declarante', 'NATUREZA DO DECLARANTE', 2, 170, '2', 1, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15418, 905, 'responsavel_perante_cnpj', 'CPF RESPONSÁVEL PERANTE O CNPJ', 2, 171, '', 11, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15419, 912, 'valor_anual', 'VALOR ANUAL', 1, 4, '', 13, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15420, 912, 'descricao_rend_isentos', 'DESCRIÇÃO DOS RENDIMENTOS ISENTOS - OUTR', 1, 17, 'OUTROS', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15421, 912, 'Pipe', 'PIPE', 13, 77, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15422, 909, 'identificador_registro', 'identificador_registro', 1, 1, 'PSE', 3, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15423, 909, 'pipe', 'PIPE', 1, 4, '', 0, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15424, 904, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'Dirf', 4, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15425, 902, 'idetificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RTRT', 5, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15426, 914, 'identificador_registro', 'identificador_registro', 1, 1, 'OPSE', 4, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15427, 914, 'cnpj', 'CNPJ OPERADOR', 1, 5, '', 14, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15428, 902, 'janeiro', 'JANEIRO', 1, 6, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15429, 913, 'identificador_registro', 'identificador_registro', 1, 1, 'TPSE', 4, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15430, 913, 'cpf', 'cpf', 1, 5, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15431, 913, 'nome', 'nome', 1, 16, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15432, 914, 'pipe', 'PIPE', 13, 175, '', 0, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15433, 905, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'DECPJ', 5, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15434, 906, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'IDREC', 5, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15435, 907, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'BPFDEC', 6, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15436, 912, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RIO', 3, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15437, 914, 'nome', 'nome', 13, 19, '', 150, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15438, 914, 'registro_ans', 'registro_ans', 1, 169, '', 6, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15439, 908, 'cnpj', 'CNPJ', 1, 7, '', 14, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15440, 908, 'nome', 'NOME', 1, 21, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15441, 908, 'identificador_registro', 'IDETIFICADOR DE REGISTRO', 1, 1, 'BPJDEC', 6, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15442, 907, 'data_laudo', 'DATA ATRIBUÍDA PELO LAUDO DA MOLÉSTIA GR', 1, 78, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15443, 905, 'administradora_fund_invest', 'O DECLARANTE É INSTITUIÇÃO ADMINISTRADOR', 1, 184, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15444, 907, 'cpf', 'CPF', 1, 7, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15445, 907, 'nome', 'NOME', 1, 18, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15446, 906, 'codigo_receita', 'CÓDIGO DE RECEITA', 2, 6, '', 4, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15447, 902, 'fevereiro', 'FEVEREIRO', 1, 21, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15448, 902, 'marco', 'MARÇO', 1, 36, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15449, 902, 'abril', 'ABRIL', 1, 51, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15450, 902, 'maio', 'MAIO', 1, 66, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15451, 905, 'socio_ostensivo', 'O DECLARANTE É SÓCIO OSTENSIVO RESPONSÁV', 1, 182, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15452, 905, 'depositario_credito_dec_judicial', 'O DECLARANTE É DEPOSITÁRIO DE CRÉDITO', 1, 183, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15453, 910, 'cpf', 'CPF', 1, 4, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15454, 913, 'valor_ano', 'valor_ano', 1, 76, '', 13, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15455, 904, 'ano_referencia', 'ANO REFERÊNCIA', 1, 5, '', 4, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15456, 904, 'ano_calendario', 'ANO CALENDÁRIO', 1, 9, '', 4, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15457, 904, 'idetificador_retificadora', 'IDENTIFICADOR DE RETIFICADORA', 1, 13, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15458, 904, 'numero_recibo', 'NÚMERO DO RECIBO', 1, 14, '', 12, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15459, 913, 'pipe', 'PIPE', 13, 89, '', 0, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15460, 903, 'cpf', 'CPF', 1, 6, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15461, 903, 'nome', 'NOME', 1, 17, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15462, 903, 'ddd', 'DDD', 1, 77, '', 2, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15463, 910, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INF', 3, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15464, 907, 'Pipe', 'PIPE', 13, 79, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15465, 906, 'Pipe', 'PIPE', 13, 10, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15466, 911, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'FIMDirf', 7, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15467, 911, 'Pipe', 'PIPE', 13, 8, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15468, 908, 'Pipe', 'PIPE', 13, 81, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15469, 910, 'Pipe', 'PIPE', 13, 215, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15470, 902, 'Pipe', 'PIPE', 13, 201, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15471, 902, 'agosto', 'AGOSTO', 1, 111, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15472, 902, 'setembro', 'SETEMBRO', 1, 126, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15473, 902, 'outubro', 'OUTUBRO', 1, 141, '', 15, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15474, 902, 'novembro', 'NOVEMBRO', 1, 156, '', 15, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15475, 902, 'dezembro', 'DEZEMBRO', 1, 171, '', 15, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15476, 902, 'decimo_terceiro', 'DÉCIMO TERCEIRO', 1, 186, '', 15, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15477, 905, 'rendimentos_residentes_exterior', 'O DECLARANTE PAGOU RENDIMENTOS A RESIDEN', 1, 185, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15478, 904, 'identificador_estrutura_layout', 'IDENTIFICADOR DE ESTRUTURA DE LAYOUT', 1, 26, '7C2DE7J', 7, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15479, 904, 'Pipe', 'PIPE', 13, 33, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15480, 905, 'plano_privado_assistencia', 'INDICADOR DE PLANO PRIVADO DE ASSISTÊNCI', 1, 186, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15481, 903, 'ramal', 'RAMAL', 1, 88, '', 6, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15482, 903, 'fax', 'FAX', 1, 94, '', 9, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15483, 903, 'correio_eletronico', 'CORREIO ELETRÔNICO', 1, 103, '', 50, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15484, 903, 'Pipe', 'PIPE', 13, 153, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15485, 905, 'indicador_pagto_copa', 'INDICADOR DE PAGAMENTO PARA A COPA', 1, 187, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15486, 899, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RRA', 3, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15487, 899, 'identificador_rendimento_recebido', 'IDENTIFICADOR DE RENDIMENTO RECEBIDO', 1, 3, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15488, 899, 'numero_processo', 'NÚMERO DO PROCESSO/REQUERIMENTO', 1, 4, '', 20, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15489, 899, 'nome_advogado', 'NOME DO ADVOGADO', 1, 39, '', 150, 'false', 'true', 'd', 'Nome do advogado/Nome empresarial do escritório de advocacia', 0);
      $aLayoutCampos[] = array(15490, 899, 'tipo_advogado', 'INDICADOR DE TIPO DE ADVOGADO/ESCRITÓRI', 1, 24, '', 1, 'false', 'true', 'e', 'Indicador de tipo de advogado/escritório de advocacia', 0);
      $aLayoutCampos[] = array(15491, 899, 'documento_advogado', 'CPF DO ADVOGADO/CNPJ DO ESCRITÓRIO DE A', 1, 25, '', 14, 'false', 'true', 'e', 'CPF do advogado/CNPJ do escritório de advocacia', 0);
      $aLayoutCampos[] = array(15492, 899, 'pipe', 'PIPE', 1, 189, '', 0, 'false', 'true', 'd', 'PIPE DE ENCERRAMENTO DE LINHA', 0);
      $aLayoutCampos[] = array(15493, 900, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'BPFRRA', 6, 'true', 'true', 'd', 'Identificador de registro', 0);
      $aLayoutCampos[] = array(15494, 900, 'cpf', 'CPF', 1, 7, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15495, 900, 'nome', 'NOME', 1, 18, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15496, 900, 'natureza', 'NATUREZA DO RRA', 1, 78, '', 50, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15497, 900, 'data_molestia', 'DATA MOLESTIA GRAVE', 4, 128, '', 8, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15498, 900, 'pipe', 'PIPE', 1, 136, '', 0, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15499, 901, 'janeiro', 'JANEIRO', 3, 8, '', 4, 'false', 'true', 'e', 'Janeiro', 0);
      $aLayoutCampos[] = array(15500, 901, 'fevereiro', 'FEVEREIRO', 3, 12, '', 4, 'false', 'true', 'e', 'Fevereiro', 0);
      $aLayoutCampos[] = array(15501, 901, 'identificador', 'IDENTIFICADOR', 1, 1, 'QTMESES', 7, 'false', 'true', 'd', 'QTMESES', 0);
      $aLayoutCampos[] = array(15502, 901, 'março', 'MARÇO', 3, 16, '', 4, 'false', 'true', 'e', 'Março', 0);
      $aLayoutCampos[] = array(15503, 901, 'abril', 'ABRIL', 3, 20, '', 4, 'false', 'true', 'e', 'Abril', 0);
      $aLayoutCampos[] = array(15504, 901, 'maio', 'MAIO', 3, 24, '', 4, 'false', 'true', 'e', 'Maio', 0);
      $aLayoutCampos[] = array(15505, 901, 'junho', 'JUNHO', 3, 28, '', 4, 'false', 'true', 'e', 'Junho', 0);
      $aLayoutCampos[] = array(15506, 901, 'agosto', 'AGOSTO', 3, 36, '', 4, 'false', 'true', 'e', 'Agosto', 0);
      $aLayoutCampos[] = array(15507, 901, 'outubro', 'OUTUBRO', 3, 44, '', 4, 'false', 'true', 'e', 'Outubro', 0);
      $aLayoutCampos[] = array(15508, 901, 'dezembro', 'DEZEMBRO', 3, 52, '', 4, 'false', 'true', 'e', 'Dezembro', 0);
      $aLayoutCampos[] = array(15509, 901, 'julho', 'JULHO', 3, 32, '', 4, 'false', 'true', 'e', 'Julho', 0);
      $aLayoutCampos[] = array(15510, 901, 'setembro', 'SETEMBRO', 3, 40, '', 4, 'false', 'true', 'e', 'Setembro', 0);
      $aLayoutCampos[] = array(15511, 901, 'novembro', 'NOVEMBRO', 3, 48, '', 4, 'false', 'true', 'e', 'Novembro', 0);
      $aLayoutCampos[] = array(15512, 901, 'pipe', 'PIPE', 1, 56, '', 0, 'false', 'true', 'd', '', 0);

      $aLayoutCampos[] = array(15513, 903, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RESPO', 5, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15514, 910, 'informacao_complementar', 'INFORMAÇÃO COMLEMENTAR', 1, 15, '', 500, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15515, 905, 'situacao_especial', 'A DECLARAÇÃO É SITUAÇÃO ESPECIAL', 1, 189, 'N', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15516, 905, 'data_evento', 'DATA DE EVENTO', 1, 190, '', 8, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15517, 905, 'Pipe', 'PIPE', 13, 198, '', 1, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15518, 905, 'indicador_pagto_olimpiada', 'INDICADOR DE PAGAMENTO PARA A OLIMPÍADA', 1, 188, 'N', 1, 'false', 'true', 'd', 'Indicador de pagamentos aos jogos olímpicos e paraolímpicos de 2016. S para existência e N para não existência.', 0);
      $aLayoutCampos[] = array(15519, 915, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INFPC', 5, 'true', 'true', 'd', '', 0);


      $aLayoutCampos[] = array(15520, 915, 'cnpj', 'CNPJ', 1, 6, '', 14, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15521, 915, 'nome_empresarial', 'NOME EMPRESARIAL', 1, 20, '', 150, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15528 ,915 ,'pipe' ,'PIPE' ,1 ,170 ,'' ,1 ,'f' ,'t' ,'d' ,'', 0);
      $aLayoutCampos[] = array(15522, 916, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INFPA', 5, 'true', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15523, 916, 'cpf_alimentando', 'CPF DO ALIMENTADO', 1, 6, '', 11, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15524, 916, 'data_nascimento', 'DATA DE NASCIMENTO', 1, 17, '', 8, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15525, 916, 'nome', 'NOME', 1, 25, '', 60, 'false', 'true', 'd', '', 0);
      $aLayoutCampos[] = array(15526, 916, 'relacao_dependencia', 'RELAÇÃO DE DEPENDÊNCIA', 2, 85, '', 2, 'false', 'true', 'e', '', 0);
      $aLayoutCampos[] = array(15527 ,916 ,'pipe' ,'PIPE' ,1 ,87 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 );

      $this->table('db_layoutcampos', array('schema' => 'configuracoes'))
           ->insert($aCampos, $aLayoutCampos)
           ->saveData();
   }

   public function down() 
   {
      $this->execute('delete from configuracoes.db_layoutcampos where db52_layoutlinha in (select db51_codigo from configuracoes.db_layoutlinha where db51_layouttxt = 279)');
      $this->execute('delete from configuracoes.db_layoutlinha where db51_layouttxt = 279');
      $this->execute('delete from configuracoes.db_layouttxt where db50_codigo = 279');
   }
}
