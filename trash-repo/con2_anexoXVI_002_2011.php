<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once('classes/db_db_config_classe.php');

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXVIBalancoGeral");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);

$iAnousu           = db_getsession("DB_anousu");
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);
$iCodigoRelatorio  = 110;
$iCodigoPeriodo    = $oGet->periodo;

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil($iCodigoRelatorio, false);

$oAnexoXVI         = new AnexoXVIBalancoGeral($iAnousu, $iCodigoRelatorio, $iCodigoPeriodo);
$oAnexoXVI->setInstituicoes($sInstituicoes);

$aNotas            = $oAnexoXVI->getDados();
$iNumRows          = count($aNotas);
if ($iNumRows == 0){
	/**
	 * Comentado, para permitir que o usuario possa emitir o relatorio com valores zerados
	 *   tarefa 74188
	 */
  //db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

/**
 * Adiciona nome abreviado das instituições selecionadas
 */
$sWhere           = "codigo in({$sInstituicoes})";
$sSqlDbConfig     = $cldb_config->sql_query_file(null, "nomeinst, nomeinstabrev", 'codigo', $sWhere);
$rsSqlDbConfig    = $cldb_config->sql_record($sSqlDbConfig); 
$iNumRowsDbConfig = $cldb_config->numrows; 
if ($iNumRowsDbConfig == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Instituição não informada.');
}

$sNomeInstAbrev = "";
$sVirgula       = "";
$lFlagInstit    = false;
for ($iInd = 0; $iInd < $iNumRowsDbConfig; $iInd++) {
  
  $oMunicipio = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);  
  if (strlen(trim($oMunicipio->nomeinstabrev)) > 0) {

    $sNomeInstAbrev .= $sVirgula.$oMunicipio->nomeinstabrev;
    $lFlagInstit     = true;
  } else {
    $sNomeInstAbrev .= $sVirgula.$oMunicipio->nomeinst;
  }
  
  $sVirgula        = ", ";
}

if ($lFlagInstit == false) {
  
  if (strlen($sNomeInstAbrev) > 42) {
    $sNomeInstAbrev = substr($sNomeInstAbrev, 0, 150);
  }
}

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oReltorioContabil->getPeriodos();
foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $oGet->periodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head2  = "DEMONSTRATIVO DA DÍVIDA FUNDADA INTERNA";
$head3  = "EXERCÍCIO {$iAnousu}";
$head5  = "INSTITUIÇÕES: {$sNomeInstAbrev}";
$head6  = "ANEXO 16 - PERÍODO: {$sDescricaoPeriodo}";

$oPdf = new PDF("L"); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', '', 9);

$iAlturalinha    = 4;

imprimirCabecalho($oPdf, $iAlturalinha, true);

$iTotValoremissao          = 0;
$iTotSaldoanterior         = 0;
$iTotCorrecaomonetaria     = 0;
$iTotResgate               = 0;
$iTotValorproximoexercicio = 0;
$iRegistro                 = 0;


foreach ($aNotas as $oLinhaNota) {
  
  $iTotValoremissao          = $iTotValoremissao          + $aNotas[$iRegistro]->valoremissao;
  $iTotSaldoanterior         = $iTotSaldoanterior         + $aNotas[$iRegistro]->saldoanterior;
  $iTotCorrecaomonetaria     = $iTotCorrecaomonetaria     + $aNotas[$iRegistro]->correcaomonetaria;
  $iTotResgate               = $iTotResgate               + $aNotas[$iRegistro]->resgate;
  $iTotValorproximoexercicio = $iTotValorproximoexercicio + $aNotas[$iRegistro]->valorproximoexercicio; 
  
  $oPdf->setfont('arial','',8);
  $oPdf->cell(70, $iAlturalinha, $oLinhaNota->lei,                                          "TBR", 0,"L", 0);
  $oPdf->cell(40, $iAlturalinha, $oLinhaNota->quantidadedata,                               "TBR", 0,"C", 0);
  $oPdf->cell(30, $iAlturalinha, db_formatar($oLinhaNota->valoremissao, "f"),               "TBR", 0,"R", 0);
  $oPdf->cell(30, $iAlturalinha, db_formatar($oLinhaNota->saldoanterior, "f"),              "TBR", 0,"R", 0);
  $oPdf->cell(30, $iAlturalinha, db_formatar($oLinhaNota->correcaomonetaria, "f"),          "TBR", 0,"R", 0);
  $oPdf->cell(30, $iAlturalinha, db_formatar($oLinhaNota->resgate, "f"),                    "TRB", 0,"R", 0);
  $oPdf->cell(10, $iAlturalinha, db_formatar($oLinhaNota->quantidadeproximoexercicio, "f"), "TBR", 0,"R", 0);
  $oPdf->cell(40, $iAlturalinha, db_formatar($oLinhaNota->valorproximoexercicio, "f"),      "TB" , 1,"R", 0);
  imprimirCabecalho($oPdf, $iAlturalinha, false);
  imprimeInfoProxPagina($oPdf, $iAlturalinha, false);
  $iRegistro++;
}

$oPdf->setfont('arial', 'B', 9);
$oPdf->cell(70, $iAlturalinha, "",                                           "TBR", 0, "L", 0);
$oPdf->cell(40, $iAlturalinha, "TOTAIS:",                                    "TBR", 0, "R", 0);
$oPdf->cell(30, $iAlturalinha, db_formatar($iTotValoremissao, 'f'),          "TBR", 0, "R", 0);
$oPdf->cell(30, $iAlturalinha, db_formatar($iTotSaldoanterior, 'f'),         "TBLR",0, "R", 0);
$oPdf->cell(30, $iAlturalinha, db_formatar($iTotCorrecaomonetaria, 'f'),     "TBR", 0, "R", 0);
$oPdf->cell(30, $iAlturalinha, db_formatar($iTotResgate, 'f'),               "TBR", 0, "R", 0);
$oPdf->cell(10, $iAlturalinha, "",                                           "TBR", 0, "R", 0);
$oPdf->cell(40, $iAlturalinha, db_formatar($iTotValorproximoexercicio, 'f'), "TB",  1, "R", 0);

//Notas Explicativas
$oPdf->ln();
$oNotasXVI = new relatorioContabil($iCodigoRelatorio, false);
$oNotasXVI->getNotaExplicativa($oPdf, $iCodigoPeriodo);

//Assinaturas
$oPdf->ln(14);
$oPdf->setfont('arial', '', 9);
$oNotasXVI->assinatura($oPdf, 'BG');

$oPdf->Output();


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    if ( !$lImprime ) {
      
      $oPdf->AddPage("L");
      imprimeInfoProxPagina($oPdf, $iAlturalinha, true);
    }
    /*
     * Cabeçalho a ser Repetido nas paginas
     */  
     $oPdf->setfont('arial','b',8);
     $oPdf->cell(140, $iAlturalinha, "AUTORIZAÇÕES",             "TBR",  0, "C", 0);
     $oPdf->cell(30,  $iAlturalinha, "SALDO ANTERIOR",            "T",   0, "C", 0);
     $oPdf->cell(60,  $iAlturalinha, "MOVIMENTO NO EXERCÍCIO R$", "TBL", 0, "C", 0);
     $oPdf->cell(50,  $iAlturalinha, "SALDO P/ O EXERC SEGUINTE", "TBL", 1, "C", 0);
     $oPdf->cell(70,  $iAlturalinha, "LEIS",                      "TBR", 0, "C", 0);
     $oPdf->cell(40,  $iAlturalinha, "QTDE (Nº E DATA)",          "TBR", 0, "C", 0);
     $oPdf->cell(30,  $iAlturalinha, "VLR DE EMISSÃO R$",         "TBR", 0, "C", 0);
     $oPdf->cell(30,  $iAlturalinha, "EM CIRCULAÇÃO",             "BR",  0, "C", 0);
     $oPdf->cell(30,  $iAlturalinha, "CORR. MONETÁRIA",           "LBR", 0, "C", 0);
     $oPdf->cell(30,  $iAlturalinha, "RESGATE/BAIXA",             "LBR", 0, "C", 0);
     $oPdf->cell(10,  $iAlturalinha, "QTDE",                      "LBR", 0, "C", 0);
     $oPdf->cell(40,  $iAlturalinha, "VALOR",                     "LTB", 1, "C", 0);  
  }
}

/**
 * Impime informacao da proxima pagina no relatorio
 *
 * @param Object type $oPdf
 * @param Integer type $iAlt
 * @param Boolean type $lInicio
 */
function imprimeInfoProxPagina($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 38 || $lImprime ) {
    
    $oPdf->SetFont('arial', '', 6);
    if ( $lImprime ) {
      $oPdf->Cell(280, ($iAlturalinha*2), 'Continuação '.($oPdf->PageNo())."/{nb}",          'T', 1, "R", 0);
    } else {
      
      $oPdf->Cell(280, ($iAlturalinha*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}", 'T', 1, "R", 0);
      imprimirCabecalho($oPdf, $iAlturalinha, false,'');
    }
  }
}
?>