<?php

use Classes\PostgresMigration;

class M8449AnexoIvRgf extends PostgresMigration
{
  public function up()
  {
    $this->execute( <<<SQL

    -- anexo IV
      insert into orcamento.orcparamrel values (168, 'ANEXO IV - Demonstrativo das Operações de Crédito', 1, 'Fonte: Sistema E-Cidade, Unidade Responsável [nome_departamento], Data de emissão [data_emissao] e hora de emissão [hora_emissao]\n\n1 Conforme Manual de Instrução de Pleitos - MIP STN/COPEM, essas operações podem ser contratadas mesmo que não haja margem disponível nos limites. No entanto, uma vez contratadas, os fluxos de tais operações terão seus efeitos contabilizados para fins da contratação de outras operações de crédito.');

    -- atualiza colunas
      update configuracoes.orcparamseqcoluna set o115_nomecoluna = 'semestre'         where o115_sequencial = 76;
      update configuracoes.orcparamseqcoluna set o115_nomecoluna = 'ate_semestre'     where o115_sequencial = 77;
      update configuracoes.orcparamseqcoluna set o115_nomecoluna = 'quadrimestre'     where o115_sequencial = 74;
      update configuracoes.orcparamseqcoluna set o115_nomecoluna = 'ate_quadrimestre' where o115_sequencial = 75;

    -- insere colunas
      insert into configuracoes.orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo,  o115_nomecoluna)
          values ( 217, 2017, 'Valor no período'         , 1, 'noperiodo'),
                 ( 218, 2017, 'Valor até o período'      , 1, 'ateperiodo'),
                 ( 219, 2017, 'Valor do primeiro período', 1, 'primeiro_periodo'),
                 ( 220, 2017, 'Valor do segundo período' , 1, 'segundo_periodo'),
                 ( 221, 2017, 'Valor do terceiro período', 1, 'terceiro_periodo'),
                 ( 222, 2017, 'Percentual'               , 1, 'percentual' ),
                 ( 223, 2017, 'Saldo do Periodo Anterior', 1, 'saldo_exercicio_anterior');

    -- periodos
      insert into configuracoes.orcparamrelperiodos
      values (nextval('configuracoes.orcparamrelperiodos_o113_sequencial_seq'), 12, 168),
           (nextval('configuracoes.orcparamrelperiodos_o113_sequencial_seq'), 13, 168),
           (nextval('configuracoes.orcparamrelperiodos_o113_sequencial_seq'), 14, 168),
           (nextval('configuracoes.orcparamrelperiodos_o113_sequencial_seq'), 15, 168),
           (nextval('configuracoes.orcparamrelperiodos_o113_sequencial_seq'), 16, 168);

    -- linhas
      insert into orcparamseq values (168, 1, 'Mobiliária', 1, 1, 1, false, false, false, false, false, 'Mobiliária', false, true, 1, 1, '', false, 0);
      insert into orcparamseq values (168, 2, 'Interna', 1, 0, 1, false, false, false, false, false, 'Interna', true, false, 2, 2, '', false, 3);
      insert into orcparamseq values (168, 3, 'Externa', 1, 0, 1, false, false, false, false, false, 'Externa', true, false, 3, 2, '', false, 3);
      insert into orcparamseq values (168, 4, 'Contratual', 1, 1, 1, false, false, false, false, false, 'Contratual', false, true, 4, 1, '', false, 3);
      insert into orcparamseq values (168, 5, 'Interna', 1, 1, 1, false, false, false, false, false, 'Interna', false, true, 5, 2, '', false, 3);
      insert into orcparamseq values (168, 6, 'Empréstimos', 1, 0, 1, false, false, false, false, false, 'Empréstimos', true, false, 6, 3, '', false, 3);
      insert into orcparamseq values (168, 7, 'Aquisição Financiada de Bens e Arrendamento Mercantil Financ', 1, 0, 1, false, false, false, false, false, 'Aquisição Financiada de Bens e Arrendamento Mercantil Financeiro', true, false, 7, 3, '', false, 3);
      insert into orcparamseq values (168, 8, 'Antecipação de Receita pela Venda a Termo de Bens e Serviços', 1, 0, 1, false, false, false, false, false, 'Antecipação de Receita pela Venda a Termo de Bens e Serviços', true, false, 8, 3, '', false, 3);
      insert into orcparamseq values (168, 9, 'Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 2', 1, 0, 1, false, false, false, false, false, 'Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 29, § 1º)', true, false, 9, 3, '', false, 3);
      insert into orcparamseq values (168, 10, 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/', 1, 0, 1, false, false, false, false, false, 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001¹', true, false, 10, 3, '', false, 3);
      insert into orcparamseq values (168, 11, 'Externa', 1, 1, 1, false, false, false, false, false, 'Externa', false, true, 11, 2, '', false, 3);
      insert into orcparamseq values (168, 12, 'Empréstimos', 1, 0, 1, false, false, false, false, false, 'Empréstimos', true, false, 12, 3, '', false, 3);
      insert into orcparamseq values (168, 13, 'Aquisição Financiada de Bens e Arrendamento Mercantil Financ', 1, 0, 1, false, false, false, false, false, 'Aquisição Financiada de Bens e Arrendamento Mercantil Financeiro', true, false, 13, 3, '', false, 3);
      insert into orcparamseq values (168, 14, 'Antecipações de Receitas pela Venda a Termo de Bens e Serviç', 1, 0, 1, false, false, false, false, false, 'Antecipações de Receitas pela Venda a Termo de Bens e Serviços', true, false, 14, 3, '', false, 3);
      insert into orcparamseq values (168, 15, 'Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 2', 1, 0, 1, false, false, false, false, false, 'Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 29, § 1º)', true, false, 15, 3, '', false, 3);
      insert into orcparamseq values (168, 16, 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/', 1, 0, 1, false, false, false, false, false, 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001¹', true, false, 16, 3, '', false, 3);
      insert into orcparamseq values (168, 17, 'TOTAL (I)', 1, 1, 1, false, false, false, false, false, 'TOTAL (I)', false, true, 17, 1, '', false, 3);
      insert into orcparamseq values (168, 18, 'RECEITA CORRENTE LÍQUIDA - RCL', 1, 1, 1, false, false, false, false, false, 'RECEITA CORRENTE LÍQUIDA - RCL', true, false, 18, 1, '', false, 0);
      insert into orcparamseq values (168, 19, 'OPERAÇÕES VEDADAS (II)', 1, 1, 1, false, false, false, false, false, 'OPERAÇÕES VEDADAS (II)', true, false, 19, 1, '', false, 0);
      insert into orcparamseq values (168, 20, 'TOTAL CONSIDERADO PARA FINS DA APURAÇÃO DO CUMPRIMENTO DO LI', 1, 1, 1, false, false, false, false, false, 'TOTAL CONSIDERADO PARA FINS DA APURAÇÃO DO CUMPRIMENTO DO LIMITE (III)= (Ia + II)', false, false, 20, 1, '', false, 0);
      insert into orcparamseq values (168, 21, 'LIMITE GERAL DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA A', 1, 1, 1, false, false, false, false, false, 'LIMITE GERAL DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO INTERNAS E EXTERNAS', false, false, 21, 1, '', false, 0);
      insert into orcparamseq values (168, 22, 'LIMITE DE ALERTA (inciso III do §1º do art. 59 da LRF) - 14.', 1, 1, 1, false, false, false, false, false, 'LIMITE DE ALERTA (inciso III do §1º do art. 59 da LRF) - 14.4%', false, false, 22, 1, '', false, 0);
      insert into orcparamseq values (168, 23, 'OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA', 1, 1, 1, false, false, false, false, false, 'OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA', false, false, 23, 1, '', false, 0);
      insert into orcparamseq values (168, 24, 'LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPER', 1, 1, 1, false, false, false, false, false, 'LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA', false, false, 24, 1, '', false, 0);
      insert into orcparamseq values (168, 25, 'Parcelamentos de Dívidas', 1, 1, 1, false, false, false, false, false, 'Parcelamentos de Dívidas', false, true, 25, 1, '', false, 3);
      insert into orcparamseq values (168, 26, 'Tributos', 1, 0, 1, false, false, false, false, false, 'Tributos', true, false, 26, 2, '', false, 3);
      insert into orcparamseq values (168, 27, 'Contribuições Previdenciárias', 1, 0, 1, false, false, false, false, false, 'Contribuições Previdenciárias', true, false, 27, 2, '', false, 3);
      insert into orcparamseq values (168, 28, 'FGTS', 1, 0, 1, false, false, false, false, false, 'FGTS', true, false, 28, 2, '', false, 3);
      insert into orcparamseq values (168, 29, 'Operações de reestruturação e recomposição do principal de d', 1, 0, 1, false, false, false, false, false, 'Operações de reestruturação e recomposição do principal de dívidas', true, false, 29, 1, '', false, 3);
    --Fim linhas

    --configuracao
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 2, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="222110100000000" nivel="" exclusao="false" /><conta estrutural="222120100000000" nivel="" exclusao="false" /><conta estrutural="222130100000000" nivel="" exclusao="false" /><conta estrutural="222140100000000" nivel="" exclusao="false" /><conta estrutural="222150100000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 3, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="222210100000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 6, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="222110200000000" nivel="" exclusao="false" /><conta estrutural="222120200000000" nivel="" exclusao="false" /><conta estrutural="222130200000000" nivel="" exclusao="false" /><conta estrutural="222140200000000" nivel="" exclusao="false" /><conta estrutural="222150200000000" nivel="" exclusao="false" /><conta estrutural="222510100000000" nivel="" exclusao="false" /><conta estrutural="222510200000000" nivel="" exclusao="false" /><conta estrutural="222530000000000" nivel="" exclusao="false" /><conta estrutural="222540000000000" nivel="" exclusao="false" /><conta estrutural="222550000000000" nivel="" exclusao="false" /><conta estrutural="222600000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 12, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="222210200000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 26, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="224110200000000" nivel="" exclusao="false" /><conta estrutural="224120200000000" nivel="" exclusao="false" /><conta estrutural="224130200000000" nivel="" exclusao="false" /><conta estrutural="224210100000000" nivel="" exclusao="false" /><conta estrutural="224220100000000" nivel="" exclusao="false" /><conta estrutural="224240100000000" nivel="" exclusao="false" /><conta estrutural="224310100000000" nivel="" exclusao="false" /><conta estrutural="224320100000000" nivel="" exclusao="false" /><conta estrutural="224350100000000" nivel="" exclusao="false" /><conta estrutural="224119900000000" nivel="" exclusao="false" /><conta estrutural="224129900000000" nivel="" exclusao="false" /><conta estrutural="224139900000000" nivel="" exclusao="false" /><conta estrutural="224219900000000" nivel="" exclusao="false" /><conta estrutural="224229900000000" nivel="" exclusao="false" /><conta estrutural="224249900000000" nivel="" exclusao="false" /><conta estrutural="224319900000000" nivel="" exclusao="false" /><conta estrutural="224329900000000" nivel="" exclusao="false" /><conta estrutural="224359900000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 27, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="221410100000000" nivel="" exclusao="false" /><conta estrutural="221430101000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
      insert into orcparamseqfiltropadrao values(nextval('orcparamelementospadrao_o132_sequencial_seq'), 168, 28, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="221410300000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');

    --colunas
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 217, 1, 12, 'L[2]->noperiodo+L[3]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 217, 1, 13, 'L[2]->noperiodo+L[3]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 217, 1, 14, 'L[2]->noperiodo+L[3]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 217, 1, 15, 'L[2]->noperiodo+L[3]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 217, 1, 16, 'L[2]->noperiodo+L[3]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 218, 2, 12, 'L[2]->ateperiodo+L[3]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 218, 2, 13, 'L[2]->ateperiodo+L[3]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 218, 2, 14, 'L[2]->ateperiodo+L[3]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 218, 2, 15, 'L[2]->ateperiodo+L[3]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 168, 218, 2, 16, 'L[2]->ateperiodo+L[3]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 217, 1, 12, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 217, 1, 13, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 217, 1, 14, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 217, 1, 15, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 217, 1, 16, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 218, 2, 12, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 218, 2, 13, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 218, 2, 14, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 218, 2, 15, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 168, 218, 2, 16, 'F[5]+F[11]');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 217, 1, 12, 'L[6]->noperiodo+L[7]->noperiodo+L[8]->noperiodo+L[9]->noperiodo+L[10]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 217, 1, 13, 'L[6]->noperiodo+L[7]->noperiodo+L[8]->noperiodo+L[9]->noperiodo+L[10]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 217, 1, 14, 'L[6]->noperiodo+L[7]->noperiodo+L[8]->noperiodo+L[9]->noperiodo+L[10]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 217, 1, 15, 'L[6]->noperiodo+L[7]->noperiodo+L[8]->noperiodo+L[9]->noperiodo+L[10]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 217, 1, 16, 'L[6]->noperiodo+L[7]->noperiodo+L[8]->noperiodo+L[9]->noperiodo+L[10]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 218, 2, 12, 'L[6]->ateperiodo+L[7]->ateperiodo+L[8]->ateperiodo+L[9]->ateperiodo+L[10]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 218, 2, 13, 'L[6]->ateperiodo+L[7]->ateperiodo+L[8]->ateperiodo+L[9]->ateperiodo+L[10]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 218, 2, 14, 'L[6]->ateperiodo+L[7]->ateperiodo+L[8]->ateperiodo+L[9]->ateperiodo+L[10]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 218, 2, 15, 'L[6]->ateperiodo+L[7]->ateperiodo+L[8]->ateperiodo+L[9]->ateperiodo+L[10]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 168, 218, 2, 16, 'L[6]->ateperiodo+L[7]->ateperiodo+L[8]->ateperiodo+L[9]->ateperiodo+L[10]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 217, 1, 12, 'L[12]->noperiodo+L[13]->noperiodo+L[14]->noperiodo+L[15]->noperiodo+L[16]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 217, 1, 13, 'L[12]->noperiodo+L[13]->noperiodo+L[14]->noperiodo+L[15]->noperiodo+L[16]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 217, 1, 14, 'L[12]->noperiodo+L[13]->noperiodo+L[14]->noperiodo+L[15]->noperiodo+L[16]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 217, 1, 15, 'L[12]->noperiodo+L[13]->noperiodo+L[14]->noperiodo+L[15]->noperiodo+L[16]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 217, 1, 16, 'L[12]->noperiodo+L[13]->noperiodo+L[14]->noperiodo+L[15]->noperiodo+L[16]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 218, 2, 12, 'L[12]->ateperiodo+L[13]->ateperiodo+L[14]->ateperiodo+L[15]->ateperiodo+L[16]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 218, 2, 13, 'L[12]->ateperiodo+L[13]->ateperiodo+L[14]->ateperiodo+L[15]->ateperiodo+L[16]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 218, 2, 14, 'L[12]->ateperiodo+L[13]->ateperiodo+L[14]->ateperiodo+L[15]->ateperiodo+L[16]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 218, 2, 15, 'L[12]->ateperiodo+L[13]->ateperiodo+L[14]->ateperiodo+L[15]->ateperiodo+L[16]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 168, 218, 2, 16, 'L[12]->ateperiodo+L[13]->ateperiodo+L[14]->ateperiodo+L[15]->ateperiodo+L[16]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 168, 218, 2, 16, '#saldo_final');

      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 217, 1, 12, 'L[1]->noperiodo+L[4]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 217, 1, 13, 'L[1]->noperiodo+L[4]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 217, 1, 14, 'L[1]->noperiodo+L[4]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 217, 1, 15, 'L[1]->noperiodo+L[4]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 217, 1, 16, 'L[1]->noperiodo+L[4]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 218, 2, 12, 'L[1]->ateperiodo+L[4]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 218, 2, 13, 'L[1]->ateperiodo+L[4]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 218, 2, 14, 'L[1]->ateperiodo+L[4]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 218, 2, 15, 'L[1]->ateperiodo+L[4]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 168, 218, 2, 16, 'L[1]->ateperiodo+L[4]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 36, 1, 12, 'L[17]->ateperiodo+L[19]->valor');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 36, 1, 13, 'L[17]->ateperiodo+L[19]->valor');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 36, 1, 14, 'L[17]->ateperiodo+L[19]->valor');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 36, 1, 15, 'L[17]->ateperiodo+L[19]->valor');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 36, 1, 16, 'L[17]->ateperiodo+L[19]->valor');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 36, 1, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 36, 1, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 36, 1, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 36, 1, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 36, 1, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 222, 2, 12, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 222, 2, 13, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 222, 2, 14, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 222, 2, 15, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 168, 222, 2, 16, '');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 217, 1, 12, 'L[26]->noperiodo+L[27]->noperiodo+L[28]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 217, 1, 13, 'L[26]->noperiodo+L[27]->noperiodo+L[28]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 217, 1, 14, 'L[26]->noperiodo+L[27]->noperiodo+L[28]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 217, 1, 15, 'L[26]->noperiodo+L[27]->noperiodo+L[28]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 217, 1, 16, 'L[26]->noperiodo+L[27]->noperiodo+L[28]->noperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 218, 2, 12, 'L[26]->ateperiodo+L[27]->ateperiodo+L[28]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 218, 2, 13, 'L[26]->ateperiodo+L[27]->ateperiodo+L[28]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 218, 2, 14, 'L[26]->ateperiodo+L[27]->ateperiodo+L[28]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 218, 2, 15, 'L[26]->ateperiodo+L[27]->ateperiodo+L[28]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 168, 218, 2, 16, 'L[26]->ateperiodo+L[27]->ateperiodo+L[28]->ateperiodo');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 168, 218, 2, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 217, 1, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 217, 1, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 217, 1, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 217, 1, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 217, 1, 16, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 218, 2, 12, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 218, 2, 13, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 218, 2, 14, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 218, 2, 15, '#saldo_final');
      insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 168, 218, 2, 16, '#saldo_final');

SQL
    );
  }

  public function down()
  {
    $this->execute(
      <<<SQL
        delete from configuracoes.orcparamseqorcparamseqcolunavalor where o117_orcparamseqorcparamseqcoluna  in (select o116_sequencial from configuracoes.orcparamseqorcparamseqcoluna where o116_codparamrel =168);
        delete from orcamento.orcparamseqfiltroorcamento            where o133_orcparamrel = 168;
        delete from configuracoes.orcparamseqorcparamseqcoluna      where o116_codparamrel = 168;
        delete from orcamento.orcparamseqfiltropadrao               where o132_orcparamrel = 168;
        delete from orcamento.orcparamseq                           where o69_codparamrel  = 168;
        delete from configuracoes.orcparamrelperiodos               where o113_orcparamrel = 168;
        delete from orcamento.orcparamrelnotaperiodo                where o118_orcparamrelnota in (select o42_sequencial from orcamento.orcparamrelnota where o42_codparrel = 168);
        delete from orcamento.orcparamrelnota                       where o42_codparrel    = 168;
        delete from orcamento.orcparamrel                           where o42_codparrel    = 168;
        delete from configuracoes.orcparamseqcoluna                 where o115_sequencial in (217, 218, 219, 220, 221, 222, 223);
SQL
    );
  }
}
