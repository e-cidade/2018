<?php

use Classes\PostgresMigration;

class M7029EmissaoGeralAguaLayoutTarifa extends PostgresMigration {

    public function up() {

        $this->execute("insert into db_layouttxt values (280, 'AGUA - CARNES TARIFA', 0, 'Layout de Emissão de Carnês de Tarifa do Água', 1 )");
        $this->execute("insert into db_layoutlinha values (918, 280, 'REGISTRO', 3, 4615, 0, 0, '', '', false )");
        $this->execute("insert into db_layoutcampos values (15529, 918, 'vencimento', 'DATA DE VENCIMENTO', 13, 1, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15530, 918, 'referencia', 'MES/ANO DE REFERENCIA', 13, 11, '', 14, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15531, 918, 'msg1', 'MENSAGEM LINHA 1', 13, 25, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15532, 918, 'msg2', 'MENSAGEM LINHA 2', 13, 95, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15533, 918, 'proprietario', 'PROPRIETARIO DO IMOVEL', 13, 165, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15534, 918, 'endereco_entrega', 'ENDERECO DE ENTREGA', 13, 215, '', 100, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15535, 918, 'matricula', 'MATRICULA DO IMOVEL', 13, 315, '', 10, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15536, 918, 'logradouro', 'CODIGO DO LOGRADOURO', 13, 325, '', 7, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15562, 918, 'dados_usuario_3', 'DADOS DO USUARIO LINHA 3', 13, 1880, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15563, 918, 'processamento', 'PROCESSAMENTO (NUMPRE)', 13, 1950, '', 20, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15564, 918, 'natureza', 'NATUREZA', 13, 1970, '', 20, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15565, 918, 'area_construida', 'AREA CONSTRUIDA DO IMOVEL', 13, 1990, '', 8, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15566, 918, 'leitura_1', 'LEITURAS ANTERIORES LINHA 1', 13, 1998, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15567, 918, 'leitura_2', 'LEITURAS ANTERIORES LINHA 2', 13, 2048, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15568, 918, 'leitura_3', 'LEITURAS ANTERIORES LINHA 3', 13, 2098, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15537, 918, 'categoria', 'CATEGORIA DE CONSUMO', 13, 332, '', 100, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15538, 918, 'zona', 'ZONA FISCAL', 2, 432, '', 3, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15539, 918, 'quadra', 'QUADRA', 2, 435, '', 2, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15540, 918, 'economias', 'NUMERO DE ECONOMIAS', 2, 437, '', 3, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15541, 918, 'bairro', 'BAIRRO', 13, 440, '', 40, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15542, 918, 'msg3', 'MENSAGEM LINHA 3', 13, 480, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15543, 918, 'msg4', 'MENSAGEM LINHA 4', 13, 550, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15544, 918, 'msg5', 'MENSAGEM LINHA 5', 13, 620, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15545, 918, 'msg6', 'MENSAGEM LINHA 6', 13, 690, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15546, 918, 'msg7', 'MENSAGEM LINHA 7', 13, 760, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15547, 918, 'msg8', 'MENSAGEM LINHA 8', 13, 830, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15548, 918, 'msg9', 'MENSAGEM LINHA 9', 13, 900, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15549, 918, 'msg10', 'MENSAGEM LINHA 10', 13, 970, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15550, 918, 'msg11', 'MENSAGEM LINHA 11', 13, 1040, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15551, 918, 'msg12', 'MENSAGEM LINHA 12', 13, 1110, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15552, 918, 'msg13', 'MENSAGEM LINHA 13', 13, 1180, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15553, 918, 'msg14', 'MENSAGEM LINHA 14', 13, 1250, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15554, 918, 'msg15', 'MENSAGEM LINHA 15', 13, 1320, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15555, 918, 'msg16', 'MENSAGEM LINHA 16', 13, 1390, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15556, 918, 'msg17', 'MENSAGEM LINHA 17', 13, 1460, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15557, 918, 'msg18', 'MENSAGEM LINHA 18', 13, 1530, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15558, 918, 'msg19', 'MENSAGEM LINHA 19', 13, 1600, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15559, 918, 'msg20', 'MENSAGEM LINHA 20', 13, 1670, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15560, 918, 'dados_usuario_1', 'DADOS DO USUARIO LINHA 1', 13, 1740, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15561, 918, 'dados_usuario_2', 'DADOS DO USUARIO LINHA 2', 13, 1810, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15583, 918, 'dt_leitura_atual', 'DATA DA LEITURA ATUAL', 13, 2918, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15584, 918, 'dt_leitura_anterior', 'DATA DA LEITURA ANTERIOR', 13, 2928, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15585, 918, 'consumo', 'CONSUMO ATUAL EM M3', 13, 2938, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15586, 918, 'dias_leitura', 'DIAS ENTRE LEITURA ATUAL E ANTERIOR', 13, 2948, '', 4, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15587, 918, 'media_diaria', 'MEDIA DIARIA DE CONSUMO', 13, 2952, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15588, 918, 'valor_acrescimo', 'VALOR ACRESCIMO', 13, 2962, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15589, 918, 'valor_desconto', 'VALOR DESCONTO', 13, 2972, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15590, 918, 'valor_total', 'VALOR TOTAL A PAGAR', 13, 2982, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15591, 918, 'aviso1', 'AVISO CONSUMIDOR 1', 13, 2992, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15592, 918, 'aviso2', 'AVISO CONSUMIDOR 2', 13, 3062, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15593, 918, 'aviso3', 'AVISO CONSUMIDOR 3', 13, 3132, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15569, 918, 'leitura_4', 'LEITURAS ANTERIORES LINHA 4', 13, 2148, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15570, 918, 'leitura_5', 'LEITURAS ANTERIORES LINHA 5', 13, 2198, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15571, 918, 'leitura_6', 'LEITURAS ANTERIORES LINHA 6', 13, 2248, '', 50, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15572, 918, 'titulo_receita_1', 'TITULO DA TABELA DE RECEITAS 1', 13, 2298, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15573, 918, 'linha_receita_1', 'LINHA DE RECEITA 1', 13, 2358, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15574, 918, 'linha_receita_2', 'LINHA DE RECEITA 2', 13, 2418, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15575, 918, 'linha_receita_3', 'LINHA DE RECEITA 3', 13, 2478, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15576, 918, 'linha_receita_4', 'LINHA DE RECEITA 4', 13, 2538, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15577, 918, 'titulo_receita_2', 'TITULO DA TABELA DE RECEITAS 2', 13, 2598, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15578, 918, 'linha_receita_5', 'LINHA DE RECEITA 5', 13, 2658, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15579, 918, 'linha_receita_6', 'LINHA DE RECEITA 6', 13, 2718, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15580, 918, 'linha_receita_7', 'LINHA DE RECEITA 7', 13, 2778, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15581, 918, 'linha_receita_8', 'LINHA DE RECEITA 8', 13, 2838, '', 60, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15582, 918, 'hidrometro', 'NUMERO DO HIDROMETRO', 13, 2898, '', 20, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15594, 918, 'aviso4', 'AVISO CONSUMIDOR 4', 13, 3202, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15595, 918, 'aviso5', 'AVISO CONSUMIDOR 5', 13, 3272, '', 70, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15596, 918, 'linha_digitavel', 'LINHA DIGITAVEL DO CODIGO DE BARRAS', 13, 3342, '', 56, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15597, 918, 'codigo_barras', 'NUMERO DO CODIGO DE BARRAS', 13, 3393, '', 44, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15598, 918, 'data_emissao', 'DATA DE EMISSAO DO CARNE', 13, 3437, '', 10, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15599, 918, 'contador', 'CONTADOR DOS CARNES', 13, 3447, '', 15, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15600, 918, 'zona_entrega', 'ZONA DE ENTREGA', 13, 3462, '', 100, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15601, 918, 'aviso6', 'AVISO CONSUMIDOR 6', 13, 3562, '', 500, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15602, 918, 'aviso7', 'AVISO CONSUMIDOR 7', 13, 4062, '', 500, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15603, 918, 'msg21', 'MENSAGEM NA LINHA 21', 13, 4562, '', 145, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15604, 918, 'cpfcnpj_proprietario', 'CPF/CNPJ PROPRIETARIO', 13, 4707, '', 14, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15605, 918, 'nosso_numero', 'NOSSO NUMERO', 13, 4721, '', 20, false, true, 'd', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15606, 918, 'codigo_contrato', 'CÓDIGO CONTRATO', 1, 4741, '', 10, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15607, 918, 'agencia_codigo_cedente', 'AGÊNCIA CÓDIGO CEDENTE', 1, 4751, '', 14, false, true, 'e', '', 0 )");
        $this->execute("insert into db_layoutcampos values (15608, 918, 'carteira', 'CARTEIRA', 1, 4765, '', 8, false, true, 'e', '', 0 )");

        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10393 ,'Emissão de Carnês de Tarifa' ,'Emissão de Carnês de Tarifa' ,'' ,'1' ,'1' ,'Emissão de Carnês de Tarifa' ,'true' )");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3332 ,10393 ,27 ,4555 )");

        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10394 ,'Emissão Parcial' ,'Emissão Parcial' ,'agu4_emissaoparcialcarnes.php' ,'1' ,'1' ,'Emissão Parcial' ,'true' )");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10393 ,10394 ,1 ,4555 )");

        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10395 ,'Emissão Geral' ,'Emissão Geral' ,'agu4_emissaogeralcarnes.php' ,'1' ,'1' ,'Emissão Geral' ,'true' )");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10393 ,10395 ,2 ,4555 )");
    }

    public function down() {

      $this->execute("delete from db_layoutcampos where db52_layoutlinha in (918)");
      $this->execute("delete from db_layoutlinha where db51_layouttxt in (280)");
      $this->execute("delete from db_layouttxt where db50_codigo in (280)");

      $this->execute("delete from db_menu where id_item_filho in(10393, 10394, 10395)");
      $this->execute("delete from db_itensmenu where id_item in(10393, 10394, 10395)");
    }
}
