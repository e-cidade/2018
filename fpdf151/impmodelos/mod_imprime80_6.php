<?php
$oInstituicao    = $oImpCarne->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $oImpCarne->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $oImpCarne->oDadosRelatorio->aGruposRubricas; 
$oHelper         = new PDFHelper($oImpCarne->objpdf);

$oHelper->novaPagina();

$oHelper->addTitulo('ANEXO VI', false, false);
$oHelper->novaLinha(2);
$oHelper->addTitulo('TERMO DE QUITAÇÃO DE RESCISÃO DO CONTRATO DE TRABALHO', 1, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);;

$oHelper->novaLinha(4);

$oHelper->addTitulo('EMPREGADOR', 1, true, 'L');
$oHelper->addColuna('01 CNPJ/CEI'    , $oInstituicao->iCgc , 20);
$oHelper->addColuna('02 Razão Social', $oInstituicao->sNome, 80);

$oHelper->novaLinha();
$oHelper->addTitulo('TRABALHADOR', 1, true, 'L');
$oHelper->addColuna('10 PIS/PASEP', $oServidor->sPis,  20);
$oHelper->addColuna('11 Nome'     , $oServidor->sNome, 80);

$oHelper->novaLinha();
$oHelper->addColuna('17 CTPS (nº, série, UF)'   , $oServidor->sCtps       , 20);
$oHelper->addColuna('18 CPF'                    , $oServidor->sCpf        , 15);
$oHelper->addColuna('19 Data de Nascimento'     , $oServidor->dNascimento , 17);
$oHelper->addColuna('20 Nome da Mãe'            , $oServidor->sNomeMae    , 48);

$oHelper->novaLinha();
$oHelper->addTitulo('CONTRATO', 1, true, 'L');
$oHelper->addColuna('22 Causa do Afastamento', $oServidor->sCausaRescisao, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('24 Data de Admissão'    , $oServidor->dAdmissao           , 20);
$oHelper->addColuna('25 Data de Aviso Prévio', $oServidor->dAvisoPrevio        , 20);
$oHelper->addColuna('26 Data de Afastamento' , $oServidor->dRescisao           , 20);
$oHelper->addColuna('27 Cód. Afastamento'    , $oServidor->sCodigoAfastamento  , 20);
$oHelper->addColuna('29 Pensão Alim.(%) FGTS', $oServidor->nPensao             , 20);

$oHelper->novaLinha();
$oHelper->addColuna('30 Categoria do Trabalhador', $oServidor->sCategoria, 100);

$oHelper->novaLinha(16);
$sTexto  = "Foi realizada a rescisão do contrato de trabalho do trabalhador acima qualificado, nos termos ";
$sTexto .= "nº 477 da Consolidação das Leis do Trabalho (CLT). A assistência à rescisão prevista no artigo § 1º, do art. 477 da CLT não é";
$sTexto .= " devida, tendo em visata a duração do contrato de trabalho não ser superior a um ano de serviço e não existir previsão de assistência ";
$sTexto .= " à rescisão contratual em Acordo ou Convenção Coletiva de Trabalho da categoria a qual pertence o trabalhador.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(5);
$sTexto  = "No dia {$oServidor->dRescisao} foi realizado, nos termos do art. 23 da Instrução Normativa/SRT nº 15/2010, o efetivo pagamento ";
$sTexto .= "das verbas rescisórias especificadas no corpo do TRCT, no valor líquido de R$ ".PDFHelper::$nTotalLiquido.", o qual devidamente rubricado ";
$sTexto .= "pelas partes, é parte integrante do presente Termo de Quitação.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(10);
$oHelper->addTexto(str_repeat(' ', 40).'/           ,             de '. str_repeat(' ', 60) . ' de               .', 100);

$oHelper->novaLinha(20);
$oHelper->addTexto("__________________________________________", 100);
$oHelper->addTexto("150 Assinatura do Empregador ou Preposto", 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("__________________________________________", 50);
$oHelper->addTexto("__________________________________________", 50);
$oHelper->addTexto("151 Assinatura do Trabalhador", 50);
$oHelper->addTexto("152 Assinatura do Responsável Legal do Trabalhador", 50);

$oHelper->novaLinha(55);
$oHelper->addCelula('156 Informações à CAIXA:', 100, PDFHelper::ALTURA_LINHA, false, 3);
$oHelper->novaLinha(8.1);
$oHelper->addTitulo("A ASSISTÊNCIA NO ATO DE RESCISÃO CONTRATUAL É GRATUITA.", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS + 3, 13);;
$oHelper->addTitulo("Pode o trabalhador iniciar ação judicial quanto aos créditos resultantes das relações de trabalho até o limite de dois", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
$oHelper->addTitulo("anos após a extinção do contrato de trabalho (Inc. XXIX, Art. 7º da Constituição Federal/1988).", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
