<?php
$oInstituicao    = $this->oDadosRelatorio->oDadosInstituicao;
$oServidor       = $this->oDadosRelatorio->oDadosServidor;
$aGruposRubricas = $this->oDadosRelatorio->aGruposRubricas; 

$oHelper         = new PDFHelper($this->objpdf);
$oHelper->novaPagina();

/**
 * Titulo com numero do anexo  
 */
$oHelper->addTitulo('ANEXO I', false, false, 'C', PDFHelper::ALTURA_LINHA_TITULOS, 13);

$oHelper->novaLinha(2);
$oHelper->addTitulo($oServidor->sTituloRelatorio, 1, true, 'C', PDFHelper::ALTURA_LINHA_TITULO_DOCUMENTO, 13);

$oHelper->novaLinha(5);

$oHelper->addTitulo('IDENTIFICA��O DO EMPREGADOR');
$oHelper->addColuna('1 CNPJ/CEI'    , $oInstituicao->iCgc , 20);
$oHelper->addColuna('2 Raz�o Social', $oInstituicao->sNome, 80);

$oHelper->novaLinha();
$oHelper->addColuna('03 Endere�o (logradouro, n�, andar, apartamento)', $oInstituicao->sEndereco, 70);
$oHelper->addColuna('04 Bairro'                                       , $oInstituicao->sBairro,   30);

$oHelper->novaLinha();
$oHelper->addColuna('05 Munic�pio'            , $oInstituicao->sMunicipio, 30);
$oHelper->addColuna('06 UF'                   , $oInstituicao->sUf       , 10);
$oHelper->addColuna('07 CEP'                  , $oInstituicao->iCep      , 15);
$oHelper->addColuna('08 CNAE'                 , $oInstituicao->iCnae     , 15);
$oHelper->addColuna('09 CNPJ/CEI Tomador/Obra', $oInstituicao->iCgc      , 30);

$oHelper->novaLinha();
$oHelper->addTitulo('IDENTIFICA��O DO TRABALHADOR');

$oHelper->addColuna('10 PIS/PASEP', $oServidor->sPis,  30);
$oHelper->addColuna('11 Nome'     , $oServidor->sNome, 70);

$oHelper->novaLinha();
$oHelper->addColuna('12 Endere�o (logradouro, n�, andar, apartamento)', $oServidor->sEndereco, 70);
$oHelper->addColuna('13 Bairro'                                       , $oServidor->sBairro  , 30);

$oHelper->novaLinha();
$oHelper->addColuna('14 Munic�pio'           , $oServidor->sMunicipio, 30); 
$oHelper->addColuna('15 UF'                  , $oServidor->sUf       , 10);
$oHelper->addColuna('16 CEP'                 , $oServidor->sCep      , 12);
$oHelper->addColuna('17 CTPS (n�, s�rie, UF)', $oServidor->sCtps     , 18);
$oHelper->addColuna('18 CPF'                 , $oServidor->sCpf      , 30);

$oHelper->novaLinha();
$oHelper->addColuna('19 Data de Nascimento', $oServidor->dNascimento, 30);
$oHelper->addColuna('20 Nome da M�e'       , $oServidor->sNomeMae   , 70);

$oHelper->novaLinha();
$oHelper->addTitulo('DADOS DO CONTRATO');

$oHelper->addColuna('21 Tipo de Contrato', $oServidor->sTipoContrato, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('22 Causa do Afastamento', $oServidor->sCausaRescisao, 100, 12.5);

$oHelper->novaLinha(12.5);
$oHelper->addColuna('23 Remunera��o M�s Ant.', $oServidor->nRemuneracaoAnterior, 20);
$oHelper->addColuna('24 Data de Admiss�o'    , $oServidor->dAdmissao           , 20);
$oHelper->addColuna('25 Data de Aviso Pr�vio', $oServidor->dAvisoPrevio        , 20);
$oHelper->addColuna('26 Data de Afastamento' , $oServidor->dRescisao           , 20);
$oHelper->addColuna('27 C�d. Afastamento'    , $oServidor->sCodigoAfastamento  , 20);

$oHelper->novaLinha();
$oHelper->addColuna('28 Pens�o Alim.(%) TRCT'    , $oServidor->nPensao   , 20);
$oHelper->addColuna('29 Pens�o Alim.(%) FGTS'    , $oServidor->nPensao   , 20);
$oHelper->addColuna('30 Categoria do Trabalhador', $oServidor->sCategoria, 60);

$oHelper->novaLinha();
$oHelper->addColuna('31 C�digo Sindical'                         , $oServidor->sCodigoSindical, 20);
$oHelper->addColuna('32 CNPJ e Nome da Entidade Sindical Laboral', $oServidor->sCnpjSindical. ' ' . $oServidor->sNomeSindical, 80);

$oHelper->novaLinha();
$oHelper->addTitulo('DISCRIMINA��O DAS VERBAS RESCIS�RIAS');

$oHelper->verbasRescisoriasAnexoI($aGruposRubricas);

