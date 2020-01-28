<?php
$oInstituicao    = $oImpCarne->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $oImpCarne->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $oImpCarne->oDadosRelatorio->aGruposRubricas; 
$oHelper         = new PDFHelper($oImpCarne->objpdf);

$oHelper->novaPagina();

$oHelper->addTitulo('ANEXO IV', false, false);

$oHelper->novaLinha(2);
$oHelper->addTitulo('Ministério do Trabalho e Emprego', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(1);
$oHelper->addTitulo('SRT - Secretaria de Relações do Trabalho', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(3);
$oHelper->addTitulo('Sistema HomologNet', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(5);
$oHelper->addTitulo('TERMO DE HOMOLOGAÇÃO DE RESCISÃO DO CONTRATO DE TRABALHO', false, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);;

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("TRCT Nº", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoTRCT, 45, 5, false, true);

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("Código de Segurança Nº", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoSeguranca, 45, 5, false, true);

$oHelper->novaLinha(10);

$oHelper->addTitulo('Empregador', 1, false, 'L');
$oHelper->addColuna('CNPJ/CEI'    , $oInstituicao->iCgc , 20);
$oHelper->addColuna('Razão Social', $oInstituicao->sNome, 80);

$oHelper->novaLinha(13);
$oHelper->addTitulo('Trabalhador', 1, false, 'L');
$oHelper->addColuna('PIS/PASEP', $oServidor->sPis,  20);
$oHelper->addColuna('Nome'     , $oServidor->sNome, 80);

$oHelper->novaLinha();
$oHelper->addColuna('Carteira de Trab. (nº, série, UF)', $oServidor->sCtps , 23);
$oHelper->addColuna('CPF'                 , $oServidor->sCpf               , 15);
$oHelper->addColuna('Data de Nascimento'  , $oServidor->dNascimento        , 15);
$oHelper->addColuna('Nome da Mãe'         , $oServidor->sNomeMae           , 47);

$oHelper->novaLinha(13);
$oHelper->addTitulo('Contrato', 1, false, 'L');
$oHelper->addColuna('Causa do Afastamento', $oServidor->sCausaRescisao, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('Data de Admissão'    , $oServidor->dAdmissao           , 20);
$oHelper->addColuna('Data de Aviso Prévio', $oServidor->dAvisoPrevio        , 20);
$oHelper->addColuna('Data de Afastamento' , $oServidor->dRescisao           , 20);
$oHelper->addColuna('Cód. Afastamento'    , $oServidor->sCodigoAfastamento  , 20);
$oHelper->addColuna('Pensão Alim.(%) FGTS', $oServidor->nPensao             , 20);

$oHelper->novaLinha();
$oHelper->addColuna('Categoria do Trabalhador', $oServidor->sCategoria, 100);

$oHelper->novaLinha(13);
$oHelper->addTitulo('Órgão Prestador da Assistência à Homologação', 1, false, 'L');
$oHelper->addColuna('(nome do órgão)', '', 100, 12.5);

$oHelper->novaLinha(16);
$sTexto  = "Foi pretada, gratuitamento, assistência ao trabalhador, nos termos do artigo nº 447, § 1º, da Consolidação ";
$sTexto .= "das Leis do Trabalho (CLT), sendo comprovado neste ato o efetivo pagamento das verbas rescisórias especificadas ";
$sTexto .= "no corpo do TRCT nº {$oServidor->sCodigoTRCT}, o qual faz parte do presente Termo de Homologação.";
$sTexto .= "\nAs partes assistidas do presente ato de homologação foram identificadas como legítimas conforme previsto na instrução Normativa/SRT nº 15/2010.";
$sTexto .= "\nFica ressalvado o direito de o trabalhador pleitear judicialmente as seguintes diferenças salariais rescisórias:";
$oHelper->addTexto($sTexto, 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("{$oInstituicao->sMunicipio} ({$oInstituicao->sUf}), ". db_dataextenso(db_getsession('DB_datausu')), 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("________________________________________________", 100);
$oHelper->addTexto("(assinatura do empregador ou preposto)", 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("________________________________________________", 100);
$oHelper->addTexto("(assinatura do trabalhador) (assinatura do responsável legal do trabalhador)", 100);

$oHelper->novaLinha(10);
$oHelper->addTexto("________________________________________________", 100);
$oHelper->addTexto("(carimbo e assinatura do assistente)", 100);

$oHelper->novaLinha(13);
$oHelper->addTitulo("A ASSISTÊNCIA NO ATO DE RESCISÃO CONTRATUAL É GRATUITA.", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS + 2, 13);;
$oHelper->addTitulo("Pode o trabalhador iniciar ação judicial quanto aos créditos resultantes das relações de trabalho até o limite de dois", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;
$oHelper->addTitulo("anos após a extinção do contrato de trabalho (Inc. XXIX, Art. 7º da Constituição Federal/1988).", false, true, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 9);;

