<?php
$oInstituicao    = $oImpCarne->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $oImpCarne->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $oImpCarne->oDadosRelatorio->aGruposRubricas; 
$oHelper         = new PDFHelper($oImpCarne->objpdf);

/**
 * ----------------------------------------------------------------------------
 * ANEXO II - PAGINA I
 * ----------------------------------------------------------------------------
 */   
$oHelper->novaPagina();

/**
 * titulo do relatorio
 */
$oHelper->addTitulo('ANEXO II - pág. 1', false, false);

$oHelper->novaLinha(2);
$oHelper->addTitulo('Ministério do Trabalho e Emprego', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(1);
$oHelper->addTitulo('SRT - Secretaria de Relações do Trabalho', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(3);
$oHelper->addTitulo('Sistema HomologNet', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(5);
$oHelper->addTitulo('TERMO DE RESCISÃO DO CONTRATO DE TRABALHO', false, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);;

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("TRCT Nº", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoTRCT,        45, 5, false, true);

$oHelper->novaLinha(5);

$oImpCarne->objpdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("Código de Segurança Nº", 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoSeguranca, 45, 5, false, true);

$oHelper->novaLinha(10);

$oHelper->addTitulo('IDENTIFICAÇÃO DO EMPREGADOR');

$oHelper->addColuna('01 CNPJ/CEI'            , $oInstituicao->iCgc  , 20, 15);
$oHelper->addColuna('02 Razão Social / Nome' , $oInstituicao->sNome , 80, 15);

$oHelper->novaLinha(15);

$oHelper->addColuna('03 Endereço (logradouro, nº, andar, apartamento)', $oInstituicao->sEndereco, 70);
$oHelper->addColuna('04 Bairro'                                       , $oInstituicao->sBairro  , 30);

$oHelper->novaLinha();

$oHelper->addColuna('05 Município'             , $oInstituicao->sMunicipio , 30);
$oHelper->addColuna('06 UF'                    , $oInstituicao->sUf        , 10);
$oHelper->addColuna('07 CEP'                   , $oInstituicao->iCep       , 15);
$oHelper->addColuna('08 CNAE'                  , $oInstituicao->iCnae      , 15);
$oHelper->addColuna('09 CNPJ/CEI Tomador/Obra' , $oInstituicao->iCgc       , 30);

$oHelper->novaLinha(13.5);
                                                                                                                                                                              
$oHelper->addTitulo('IDENTIFICAÇÃO DO TRABALHADOR');
                                                  
$oHelper->addColuna('10 PIS/PASEP' , $oServidor->sPis  , 30);
$oHelper->addColuna('11 Nome'      , $oServidor->sNome , 70);
                                                                                                      
$oHelper->novaLinha();                                                                                
$oHelper->addColuna('12 Endereço (logradouro, nº, andar, apartamento)', $oServidor->sEndereco , 70);
$oHelper->addColuna('13 Bairro'                                       , $oServidor->sBairro   , 30);
                                                                                                      
$oHelper->novaLinha();                                                                                
$oHelper->addColuna('14 Município'                           , $oServidor->sMunicipio, 30);
$oHelper->addColuna('15 UF'                                  , $oServidor->sUf       , 10);
$oHelper->addColuna('16 CEP'                                 , $oServidor->sCep      , 12);
$oHelper->addColuna('17 Carteira de Trabalho (nº, serie, UF)', $oServidor->sCtps     , 48);
                                                                                                      
                                                                                                      
$oHelper->novaLinha();                                                                                
$oHelper->addColuna('18 CPF'               , $oServidor->sCpf       , 20);
$oHelper->addColuna('19 Data de Nascimento', $oServidor->dNascimento, 20);
$oHelper->addColuna('20 Nome da Mãe'       , $oServidor->sNomeMae   , 60);

$oHelper->novaLinha(13.5);

$oHelper->addTitulo('DADOS DO CONTRATO');

$oHelper->addColuna('21 Tipo de Contrato'    , $oServidor->sTipoContrato , 50, 15);

$oHelper->oPdf->SetLeftMargin($oHelper->marginLeft(null, 50));
$oHelper->addCelula("22 Causa do Afastamento", 50, 15, false, 1.3, PDFHelper::FONTE_TITULO_COLUNA);
$oHelper->novaLinha(4);
$oHelper->oPdf->SetLeftMargin($oHelper->marginLeft(null, 50.5));
$oHelper->addTexto($oServidor->sCausaRescisao, 50);

                                                                      
$oHelper->novaLinha(11.0);                                              
$oHelper->addColuna('23 Remuneração Mês Ant.', $oServidor->nRemuneracaoAnterior, 25);
$oHelper->addColuna('24 Data de Admissão'    , $oServidor->dAdmissao           , 25);
$oHelper->addColuna('25 Data de Aviso Prévio', $oServidor->dAvisoPrevio        , 25);
$oHelper->addColuna('26 Data de Afastamento' , $oServidor->dRescisao           , 25);

$oHelper->novaLinha();
$oHelper->addColuna('27 Cód. Afastamento'           , $oServidor->sCodigoAfastamento, 25);
$oHelper->addColuna('28 Pensão Alimentícia (TRCT)'  , $oServidor->nPensao           , 25);
$oHelper->addColuna('29 Pensão Alimentícia (%) FGTS', $oServidor->nPensao           , 25);
$oHelper->addColuna('30 Categoria do Trabalhador'   , $oServidor->sCategoria        , 25);

$oHelper->novaLinha();
$oHelper->addColuna('31 Código Sindical'                         , $oServidor->sCodigoSindical , 20);
$oHelper->addColuna('32 CNPJ e Nome da Entidade Sindical Laboral', $oServidor->sCnpjSindical. ' ' . $oServidor->sNomeSindical , 80);

/**
 * ----------------------------------------------------------------------------
 * ANEXO II - PAGINA II
 * ----------------------------------------------------------------------------
 */   
$oHelper->novaPagina();

$oHelper->addTitulo('ANEXO II - pág. 2', false, false);

$oHelper->novaLinha(2);
$oHelper->addTitulo('TERMO DE RESCISÃO DO CONTRATO DE TRABALHO', false, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);
$oHelper->novaLinha(5);

$oHelper->oPdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("TRCT Nº"              , 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoTRCT, 45, 5, false, true);

$oHelper->novaLinha(5);

$oHelper->oPdf->SetLeftMargin($oHelper->marginLeft(null, 30));
$oHelper->addCelula("Código de Segurança Nº"    , 25, 5, false, true);
$oHelper->addCelula($oServidor->sCodigoSeguranca, 45, 5, false, true);

$oHelper->novaLinha(10);

$oHelper->addTitulo('DISCRIMINAÇÃO DAS VERBAS RESCISÓRIAS');

$oHelper->verbasRescisoriasAnexoII($aGruposRubricas);
