<?php
$oInstituicao    = $oImpCarne->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $oImpCarne->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $oImpCarne->oDadosRelatorio->aGruposRubricas; 
$oHelper         = new PDFHelper($oImpCarne->objpdf);

$oHelper->novaPagina();

$oHelper->addTitulo('ANEXO VII', false, false);
$oHelper->novaLinha(2);
$oHelper->addTitulo('TERMO DE HOMOLOGA��O DE RESCIS�O DO CONTRATO DE TRABALHO', 1, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);;

$oHelper->novaLinha(4);

$oHelper->addTitulo('EMPREGADOR', 1, true, 'L');
$oHelper->addColuna('01 CNPJ/CEI'    , $oInstituicao->iCgc , 20);
$oHelper->addColuna('02 Raz�o Social', $oInstituicao->sNome, 80);

$oHelper->novaLinha();
$oHelper->addTitulo('TRABALHADOR', 1, true, 'L');
$oHelper->addColuna('10 PIS/PASEP', $oServidor->sPis,  20);
$oHelper->addColuna('11 Nome'     , $oServidor->sNome, 80);

$oHelper->novaLinha();
$oHelper->addColuna('17 CTPS (n�, s�rie, UF)'   , $oServidor->sCtps       , 20);
$oHelper->addColuna('18 CPF'                    , $oServidor->sCpf        , 15);
$oHelper->addColuna('19 Data de Nascimento'     , $oServidor->dNascimento , 17);
$oHelper->addColuna('20 Nome da M�e'            , $oServidor->sNomeMae    , 48);

$oHelper->novaLinha();
$oHelper->addTitulo('CONTRATO', 1, true, 'L');
$oHelper->addColuna('22 Causa do Afastamento', $oServidor->sCausaRescisao, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('24 Data de Admiss�o'    , $oServidor->dAdmissao           , 20);
$oHelper->addColuna('25 Data de Aviso Pr�vio', $oServidor->dAvisoPrevio        , 20);
$oHelper->addColuna('26 Data de Afastamento' , $oServidor->dRescisao           , 20);
$oHelper->addColuna('27 C�d. Afastamento'    , $oServidor->sCodigoAfastamento  , 20);
$oHelper->addColuna('29 Pens�o Alim.(%) FGTS', $oServidor->nPensao             , 20);

$oHelper->novaLinha();
$oHelper->addColuna('30 Categoria do Trabalhador', $oServidor->sCategoria, 100);

$oHelper->novaLinha(16);
$sTexto  = "Foi prestada, gratuitamente, assist�ncia na rescis�o do contrato de trabalho, nos termos do artigo n� 447, � 1�, da Consolida��o  ";
$sTexto .= "das Leis do Trabalho (CLT), sendo comprovado neste ato o efetivo pagamento das verbas rescis�rias especificadas no corpo do TRCT, no valor ";
$sTexto .= " l�quido de R$ ".PDFHelper::$nTotalLiquido.", o qual, devidamente rubricado pelas partes, � parte integrante do presente Termo de Homologa��o.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(2);
$sTexto = "As partes assistidas no presente ato de rescis�o contratual foram identificadas como leg�timas conforme previsto na instru��o Normativa/SRT n� 15/2010.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(2);
$sTexto = "Fica ressalvado o direito de o trabalhador pleitear judicialmente os direitos informados no campo 155, abaixo.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(5);
$oHelper->addTexto(str_repeat(' ', 40).'/           ,             de '. str_repeat(' ', 60) . ' de               .', 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("__________________________________________", 100);
$oHelper->addTexto("150 Assinatura do Empregador ou Preposto"  , 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("__________________________________________"        , 50);
$oHelper->addTexto("__________________________________________"        , 50);
$oHelper->addTexto("151 Assinatura do Trabalhador"                     , 50);
$oHelper->addTexto("152 Assinatura do Respons�vel Legal do Trabalhador", 50);

$oHelper->novaLinha(10);
$oHelper->addTexto("__________________________________________", 50);
$oHelper->addTexto("__________________________________________", 50);
$oHelper->addTexto("153 Carimbo e Assinatura do Assistente"    , 50);
$oHelper->addTexto("154 Nome do �rg�o Homologador"             , 50);

$oHelper->novaLinha(5);
$oHelper->addColuna('155 Ressalvas', null, 100, 59);

$oHelper->novaLinha(59);
$oHelper->addCelula('156 Informa��es � CAIXA:', 100, PDFHelper::ALTURA_LINHA, false, 3);
$oHelper->novaLinha(8.1);
$oHelper->addTitulo("A ASSIST�NCIA NO ATO DE RESCIS�O CONTRATUAL � GRATUITA.", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS + 3, 13);;
$oHelper->addTitulo("Pode o trabalhador iniciar a��o judicial quanto aos cr�ditos resultantes das rela��es de trabalho at� o limite de dois", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
$oHelper->addTitulo("anos ap�s a extin��o do contrato de trabalho (Inc. XXIX, Art. 7� da Constitui��o Federal/1988).", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
