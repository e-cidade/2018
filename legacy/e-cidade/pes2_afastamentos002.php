<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdfnovo.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");

$oJson = new services_json();
$oGet  = $oJson->decode( str_replace( "\\", "", $_GET["json"] ) );

$oDaoAfasta = db_utils::getDao("afasta");

$iAfastadosInicio  = $oGet->iAfastadosInicio;
$iAfastadosFim     = $oGet->iAfastadosFim;
$iRetornadosInicio = $oGet->iRetornadosInicio;
$iRetornadosFim    = $oGet->iRetornadosFim;
$iLancadosInicio   = $oGet->iLancadosInicio;
$iLancadosFim      = $oGet->iLancadosFim;
$lQuebra           = $oGet->iQuebra;

$sWhere = "";

$oPdf = new PDFNovo();
$oPdf->addHeader( "RELATÓRIO DE AFASTADOS" );
$oPdf->addHeader( "" );

if ( $iAfastadosInicio != '' && $iAfastadosFim != '') {

  $oPdf->addHeader( "Afastados entre: {$iAfastadosInicio} - {$iAfastadosFim}" );
  $sWhere .= " and r45_dtafas >= '$iAfastadosInicio' and r45_dtafas <= '$iAfastadosFim'";
} else if( $iAfastadosInicio != '') {
  $sWhere .= " and r45_dtafas >= '$iAfastadosInicio'";
} else if( $iAfastadosFim != '') {
  $sWhere .= " and r45_dtafas <= '$iAfastadosFim'";
}

if ( $iRetornadosInicio != '' &&  $iRetornadosFim != '') {

  $oPdf->addHeader( "Retornados entre: {$iRetornadosInicio} - {$iRetornadosFim}" );
  $sWhere .= " and r45_dtreto >= '$iRetornadosInicio' and r45_dtreto <= '$iRetornadosFim'";
} else if( $iRetornadosInicio != '') {
  $sWhere .= " and r45_dtreto >= '$iRetornadosInicio'";
} else if( $iRetornadosFim != '') {
  $sWhere .= " and r45_dtreto <= '$iRetornadosFim'";
}

if ( $iLancadosInicio != '' &&  $iLancadosFim != '') {

  $oPdf->addHeader( "Lançados entre: {$iLancadosInicio} - {$iLancadosFim}" );
  $sWhere .= " and r45_dtlanc >= '$iLancadosInicio' and r45_dtlanc <= '$iLancadosFim'";
} else if( $iLancadosInicio != ''){
  $sWhere .= " and r45_dtlanc >= '$iLancadosInicio'";
} else if( $iLancadosFim != '') {
  $sWhere .= " and r45_dtlanc <= '$iLancadosFim'";
}

if ($oGet->iAfastamentos != "0") {
  $sWhere .= " and r45_situac = $oGet->iAfastamentos ";
}

/**
 * Caso "Emite Retornados" estiver selecionado;
 * Coloca a data de retorno igual a data final dos afastados ou a data atual.
 */
if ($oGet->iEmiteRetornados == "0") {

  if ($iAfastadosFim == '') {
    $iAfastadosFim = date('d-m-Y') ;
  }

  $iAnoRetorno = substr( $iAfastadosFim, 6, 4);
  $iMesRetorno = substr( $iAfastadosFim, 3, 2);

  /**
   * Pega todos que tenho data de retorno até o último dia do mês da data selecionada acima,
   * Ou todos que não possuam data de retorno
   */
  $sWhere .= " and (r45_dtreto is null ) ";
  //$sWhere .= " and (r45_dtreto is null ) /*or r45_dtreto >= ( '{$iAnoRetorno}' || '-' || {$iMesRetorno} || '-' || ndias({$iAnoRetorno}, {$iMesRetorno}) )::date )*/";

}

$sCondicao = "";
$sOrdem = " order by ";
$aCamposOrdem = array();
$sCampos = "r45_dtafas, z01_nome, r45_dtlanc, r45_situac, r45_regist, r45_dtreto";

/**
 * Utilizado o campo correto para os tipos de filtro
 */
switch ($oGet->iTipoRelatorio) {
  case 1:
    $sCampo         = "o40_orgao ";
    $aCamposOrdem[] = $sCampo;
    $sCampos       .= ", " . $sCampo . ", o40_descr";

    $aCamposDescricaoQuebra = array("o40_orgao", "o40_descr");
    $oPdf->addHeader( "Tipo de Resumo: ÓRGÃO" );
  break;
  case 2:
    $sCampo         = "r70_codigo ";
    $aCamposOrdem[] = $sCampo;
    $sCampos       .= ", " . $sCampo . ", r70_descr";

    $aCamposDescricaoQuebra = array("r70_codigo", "r70_descr");
    $oPdf->addHeader( "Tipo de Resumo: LOTAÇÃO" );
  break;
  case 3:
    $sCampo         = "rh01_regist ";
    $aCamposOrdem[] = $sCampo;
    $sCampos       .= ", " . $sCampo . ", z01_nome";

    $aCamposDescricaoQuebra = array("rh01_regist", "z01_nome");
    $oPdf->addHeader( "Tipo de Resumo: MATRÍCULA" );
  break;
  case 5:
    $sCampo         = "rh37_funcao ";
    $aCamposOrdem[] = $sCampo;
    $sCampos       .= ", ".$sCampo . ", rh37_descr";

    $aCamposDescricaoQuebra = array("rh37_funcao", "rh37_descr");
    $oPdf->addHeader( "Tipo de Resumo: CARGO" );
  break;
  default:
    $sCampo = "";
    $aCamposDescricaoQuebra = array();

    if ($lQuebra) {
      $sCampo         = "rh01_regist ";
      $sCampos       .= ", " . $sCampo . ", z01_nome";

      $aCamposDescricaoQuebra = array("rh01_regist", "z01_nome");
      $oPdf->addHeader( "Tipo de Resumo: GERAL" );
    }
  break;
}

/**
 * Monta a query quando o filtro for por intervalo do tipo selecionado
 */
if ( !empty($oGet->iIntervaloInicial) || !empty($oGet->iIntervaloFinal) ) {

  $sCondicao = " and {$sCampo} between {$oGet->iIntervaloInicial} and {$oGet->iIntervaloFinal}";
}

/**
 * Monta a query quando o filtro for pela seleção do tipo selecionado
 */
if (!empty($oGet->iRegistros)) {

  $sCondicao = " and {$sCampo} in (" . implode(", ", $oGet->iRegistros) . ")";
}

$sWhere .= $sCondicao;

if ($oGet->sOrdem == 'a') {

  $aCamposOrdem[] = "z01_nome";
  $oPdf->addHeader( "Ordem: Alfabética" );
} elseif ($oGet->sOrdem == 'n') {

  $aCamposOrdem[] = "r45_regist";
  $oPdf->addHeader( "Ordem: Numérica" );
} elseif ($oGet->sOrdem == 'f') {

  $aCamposOrdem[] = "r45_dtafas";
  $oPdf->addHeader( "Ordem: Afastamento" );
} elseif ($oGet->sOrdem == 'r') {

  $aCamposOrdem[] = "r45_dtreto";
  $oPdf->addHeader( "Ordem: Retorno" );
} elseif ($oGet->sOrdem == 'l') {

  $aCamposOrdem[] = "r45_dtlanc";
  $oPdf->addHeader( "Ordem: Lançamento" );
}

/**
 * Define a ordem do relatório
 */
$sOrdem .= implode(",", $aCamposOrdem);

$sSql = $oDaoAfasta->sql_relatorioAfastados($oGet->iTipoRelatorio, $sCampos, $sOrdem, $sWhere);
$rsServidoresAfastados = db_query($sSql);

if ( pg_num_rows($rsServidoresAfastados) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período');
}

$aSituacoes = Array(
  2 => "Afastado sem remuneração",
  3 => "Afastado acidente de trabalho +15 dias",
  4 => "Afastado serviço militar",
  5 => "Afastado licença gestante",
  6 => "Afastado doença +15 dias",
  7 => "Licença sem vencimento, cessão sem ônus",
  8 => "Afastado doença +30 dias"
);

$iAltura = 4;

$oPdf->addTableHeader('MATRÍCULA', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('NOME', 62, $iAltura, 'C', true);
$oPdf->addTableHeader('AFASTADO', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('LANÇADO', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('RETORNO', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('SITUAÇÃO', 50, $iAltura, 'C', true);

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setHeaderMargin(0.2);
$oPdf->setfillcolor(235);

$iTotal    = pg_numrows($rsServidoresAfastados);
$lPreenche = false;
$sQuebra   = '';

for ($iIndice = 0; $iIndice < pg_numrows($rsServidoresAfastados); $iIndice++) {
  $oServidor = db_utils::fieldsMemory($rsServidoresAfastados, $iIndice);

  if (!empty($aCamposDescricaoQuebra)) {

    $aDescricao = array();
    foreach ($aCamposDescricaoQuebra as $sCampoDescricaoQuebra) {
      $aDescricao[] = $oServidor->{$sCampoDescricaoQuebra};
    }

    $oPdf->setQuebra(implode(" - ", $aDescricao), $iAltura, 'L', true);

    if ($sQuebra != $oServidor->{trim($sCampo)}) {
      if ($lQuebra || ($oPdf->GetY() > ($oPdf->h - ($iAltura*4) - $oPdf->bMargin) ) || empty($oPdf->pages)) {
        $oPdf->addPage('p');
      } else {

        $oPdf->Ln($iAltura);
        $oPdf->renderTableHeaders();
      }
    }

    $sQuebra = $oServidor->{trim($sCampo)};
  } elseif (empty($oPdf->pages)) {
    $oPdf->addPage('p');
  }

  $oPdf->setfont('arial','',7);
  $oPdf->cell(20, $iAltura, $oServidor->r45_regist, 0, 0, "C", $lPreenche);
  $oPdf->cell(62, $iAltura, $oServidor->z01_nome, 0, 0, "L", $lPreenche);
  $oPdf->cell(20, $iAltura, db_formatar($oServidor->r45_dtafas, 'd'), 0, 0, "C", $lPreenche);
  $oPdf->cell(20, $iAltura, db_formatar($oServidor->r45_dtlanc, 'd'), 0, 0, "C", $lPreenche);
  $oPdf->cell(20, $iAltura, db_formatar($oServidor->r45_dtreto, 'd'), 0, 0, "C", $lPreenche);
  $oPdf->cell(50, $iAltura, isset($aSituacoes[$oServidor->r45_situac]) ? $aSituacoes[$oServidor->r45_situac] : $oServidor->r45_situac, 0, 1, "L", $lPreenche);

  $lPreenche = !($lPreenche);
}

if (empty($aCamposDescricaoQuebra)) {
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(192, $iAltura, "TOTAL  {$iTotal}  REGISTROS", 'T', 1, "C", 0);
}

$oPdf->Output();