<?php
$oInstituicao    = $oImpCarne->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $oImpCarne->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $oImpCarne->oDadosRelatorio->aGruposRubricas; 
$oHelper         = new PDFHelper($oImpCarne->objpdf);

$oHelper->novaPagina();

$oHelper->addTitulo('ANEXO V', false, false);

$oHelper->novaLinha(2);
$oHelper->addTitulo('Minist�rio do Trabalho e Emprego', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(1);
$oHelper->addTitulo('SRT - Secretaria de Rela��es do Trabalho', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(3);
$oHelper->addTitulo('Sistema HomologNet', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(5);
$oHelper->addTitulo('TERMO DE QUITA��O DE RESCIS�O DO CONTRATO DE TRABALHO', false, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);;

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("TRCT N�", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoTRCT, 45, 5, false, true);

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("C�digo de Seguran�a N�", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoSeguranca, 45, 5, false, true);

$oHelper->novaLinha(10);

$oHelper->addTitulo('Empregador', 1, false, 'L');
$oHelper->addColuna('CNPJ/CEI'    , $oInstituicao->iCgc , 20);
$oHelper->addColuna('Raz�o Social', $oInstituicao->sNome, 80);

$oHelper->novaLinha(13);
$oHelper->addTitulo('Trabalhador', 1, false, 'L');
$oHelper->addColuna('PIS/PASEP', $oServidor->sPis,  20);
$oHelper->addColuna('Nome'     , $oServidor->sNome, 80);

$oHelper->novaLinha();
$oHelper->addColuna('Carteira de Trab. (n�, s�rie, UF)', $oServidor->sCtps , 23);
$oHelper->addColuna('CPF'                 , $oServidor->sCpf               , 15);
$oHelper->addColuna('Data de Nascimento'  , $oServidor->dNascimento        , 15);
$oHelper->addColuna('Nome da M�e'         , $oServidor->sNomeMae           , 47);

$oHelper->novaLinha(13);
$oHelper->addTitulo('Contrato', 1, false, 'L');
$oHelper->addColuna('Causa do Afastamento', $oServidor->sCausaRescisao, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('Data de Admiss�o'    , $oServidor->dAdmissao           , 20);
$oHelper->addColuna('Data de Aviso Pr�vio', $oServidor->dAvisoPrevio        , 20);
$oHelper->addColuna('Data de Afastamento' , $oServidor->dRescisao           , 20);
$oHelper->addColuna('C�d. Afastamento'    , $oServidor->sCodigoAfastamento  , 20);
$oHelper->addColuna('Pens�o Alim.(%) FGTS', $oServidor->nPensao             , 20);

$oHelper->novaLinha();
$oHelper->addColuna('Categoria do Trabalhador', $oServidor->sCategoria, 100);

$oHelper->novaLinha(16);
$sTexto  = "Foi realizada a rescis�o do contrato de trabalho do trabalhador acima qualificado, nos termos ";
$sTexto .= "do artigo n� 477 da CLT n�o � devida, tendo em vista a dura��o do contrato de trabalho n�o ser superior a um ano ";
$sTexto .= "de servi�o e n�o existir previs�o de assist�ncia � rescis�o contratual em Acordo ou Conven��o Coletiva de Trabalho ";
$sTexto .= "da categoria a qual pertence o trabalhador.";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(5);
$sTexto  = "No dia {$oServidor->dRescisao} foi realizado, nos termos do art. 23 da Instru��o Normativa/SRT n� 15/2010, o efetivo pagamento ";
$sTexto .= "das verbas rescis�rias especificadas no corpo do TRCT, o qual, devidamente rubricado pelas partes, � parte integrante do presente ";
$sTexto .= "Termo de Quita��o";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("{$oInstituicao->sMunicipio} ({$oInstituicao->sUf}), ". db_dataextenso(db_getsession('DB_datausu')), 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("________________________________________________", 100);
$oHelper->addTexto("(assinatura do empregador ou preposto)", 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("________________________________________________", 100);
$oHelper->addTexto("(assinatura do trabalhador) (assinatura do respons�vel legal do trabalhador)", 100);

$oHelper->novaLinha(35);
$oHelper->addTitulo("A ASSIST�NCIA NO ATO DE RESCIS�O CONTRATUAL � GRATUITA.", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS + 2, 13);;
$oHelper->addTitulo("Pode o trabalhador iniciar a��o judicial quanto aos cr�ditos resultantes das rela��es de trabalho at� o limite de dois", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
$oHelper->addTitulo("anos ap�s a extin��o do contrato de trabalho (Inc. XXIX, Art. 7� da Constitui��o Federal/1988).", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;

