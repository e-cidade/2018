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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("classes/db_acordo_classe.php");
require_once("model/Acordo.model.php");
require_once("model/AcordoComissao.model.php");
require_once("model/AcordoItem.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/AcordoRescisao.model.php");
require_once("model/AcordoMovimentacao.model.php");
require_once("model/AcordoComissaoMembro.model.php");
require_once("model/AcordoGarantia.model.php");
require_once("model/AcordoHomologacao.model.php");
require_once("model/MaterialCompras.model.php");
require_once("model/CgmFactory.model.php");

$oPost               = db_utils::postMemory($_POST);
$clacordo            = new cl_acordo;

$sWhere              = '';
$sAnd                = '';
$sOrder              = 'acordo.ac16_dataassinatura';

$sAcordo             = 'Todos';
$sDataInicio         = ' / / ';
$sDataFim            = ' / / ';

$sOrdemDescricao     = '';

if (isset($oPost->ordemdescricao)) {
  $sOrdemDescricao = $oPost->ordemdescricao;
}

if (isset($oPost->ac16_sequencial)) {
  
  if (!empty($oPost->ac16_sequencial)) {
    
    $sAcordo = $oPost->ac16_sequencial.' - '.substr($oPost->ac16_resumoobjeto, 0, 40);
    $sWhere .= "{$sAnd} acordo.ac16_sequencial = {$oPost->ac16_sequencial}";
    $sAnd    = " and ";   
  }
}

if (isset($oPost->sDepartamentos) && !empty($oPost->sDepartamentos)) {
	
	$sWhere .= "{$sAnd} (ac16_coddepto in ({$oPost->sDepartamentos}) or ac16_deptoresponsavel in ({$oPost->sDepartamentos}))";
	$sAnd    = " and ";
}

if (isset($oPost->ac16_datainicio) && isset($oPost->ac16_datafim)) {
	
  if (!empty($oPost->ac16_datainicio) && !empty($oPost->ac16_datafim)) {
  	
  	$sDataInicio = $oPost->ac16_datainicio;
  	$sDataFim    = $oPost->ac16_datafim;
  	$dtIni       = implode("-",array_reverse(explode("/",$sDataInicio)));
  	$dtFim       = implode("-",array_reverse(explode("/",$sDataFim)));
  	
    $sWhere .= "{$sAnd} acordo.ac16_datafim between '{$dtIni}' and '{$dtFim}' ";
    $sAnd    = " and "; 
  }
} else {
	db_redireciona("db_erros.php?fechar=true&db_erro=Informe a data de inicio e data de fim da vigencia!");
}

if (isset($oPost->listaacordogrupo)) {
  
  if (!empty($oPost->listaacordogrupo)) {
    
    $sWhere .= "{$sAnd} acordogrupo.ac02_sequencial in({$oPost->listaacordogrupo})";
    $sAnd    = " and ";  
  }  
}

if (!empty($oPost->listacontratado)) {
  
    $sWhere .= "{$sAnd} contratado.z01_numcgm in({$oPost->listacontratado})";
    $sAnd    = " and "; 
}

if (!empty($oPost->ordem)) {
    
    if (trim($oPost->ordem) == 1) {
      $sOrder = 'acordo.ac16_datafim';
    } else if (trim($oPost->ordem) == 2) {
      $sOrder = 'acordo.ac16_contratado';
    }
}

if(!empty($oPost->ac50_sequencial)) {
  
  $sWhere .= "{$sAnd} acordo.ac16_acordocategoria = {$oPost->ac50_sequencial} ";
  $sAnd    = " and ";
  $head7   = "CATEGORIA: {$oPost->ac50_descricao}";
}

/*$sWhere     .= "{$sAnd} ( ac16_coddepto = ".db_getsession("DB_coddepto");
$sWhere     .= "          or ac16_deptoresponsavel = ".db_getsession("DB_coddepto")." ) ";*/

$sSqlAcordo  = $clacordo->sql_query_completo(null, "acordo.ac16_sequencial, acordotipo.ac04_descricao", $sOrder, $sWhere);
$rsSqlAcordo = $clacordo->sql_record($sSqlAcordo);
if ( $clacordo->numrows == 0  ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}
 
$aDadosAcordo = array();
for ($iInd = 0; $iInd < $clacordo->numrows; $iInd++) {

  $oAcordoCompleto = db_utils::fieldsMemory($rsSqlAcordo, $iInd);
  $oAcordo         = new Acordo($oAcordoCompleto->ac16_sequencial);

  /*
   * Monta Novo Objeto StdClass
   */
  $oDadosAcordo                   = new stdClass();
  $oDadosAcordo->getCodigo        = $oAcordo->getCodigoAcordo();
  $oDadosAcordo->getTipoAcordo    = $oAcordoCompleto->ac04_descricao;
  $oDadosAcordo->getContratado    = $oAcordo->getContratado()->getCodigo()." - ".$oAcordo->getContratado()->getNome();
  $oDadosAcordo->getAssinatura    = $oAcordo->getDataAssinatura();
  $oDadosAcordo->getVigencia      = $oAcordo->getDataInicial().' a '.$oAcordo->getDataFinal();
  $oDadosAcordo->getValorTotal    = $oAcordo->getValoresItens()->valoratual;
  $oDadosAcordo->getResumoObjeto  = $oAcordo->getResumoObjeto();
  $aDadosAcordo[] = $oDadosAcordo;
}

$head2 = "Acordo: {$sAcordo}";
$head4 = "Data de Início: {$sDataInicio}  Data de Fim: {$sDataFim}";
$head6 = "Ordem: {$sOrdemDescricao}";

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFillColor(220);
$oPdf->SetAutoPageBreak(false);

$iFonte     = 9;
$iAlt       = 5 ;
$lImprime   = true;
$lPreencher = false;

foreach ($aDadosAcordo as $oDadoAcordo) {
	
  imprimirCabecalhoAcordos($oPdf, $iFonte, $iAlt, $lImprime);
  $lImprime = false;
  
  if ($lPreencher == true) {
                  
    $lPreencher   = false;
    $iCorFundo    = 1;    
    $oPdf->SetFillColor(240);
  } else {
            
    $lPreencher   = true;
    $iCorFundo    = 0;
    $oPdf->SetFillColor(220);
  }
  
  $oPdf->SetFont('Arial','',$iFonte-1);
  $oPdf->Cell(40 ,$iAlt,$oDadoAcordo->getCodigo                                                ,'TBR',0,'C',$iCorFundo);
  $oPdf->Cell(40 ,$iAlt,$oDadoAcordo->getTipoAcordo                                                ,1,0,'L',$iCorFundo);
  $oPdf->Cell(78 ,$iAlt,substr($oDadoAcordo->getContratado, 0, 46)                                 ,1,0,'L',$iCorFundo);
  $oPdf->Cell(40 ,$iAlt,$oDadoAcordo->getAssinatura                                                ,1,0,'C',$iCorFundo);
  $oPdf->Cell(40 ,$iAlt,$oDadoAcordo->getVigencia                                                  ,1,0,'C',$iCorFundo);
  $oPdf->Cell(40 ,$iAlt,trim(db_formatar($oDadoAcordo->getValorTotal, 'f'))                    ,'TBL',1,'R',$iCorFundo);
  $oPdf->MultiCell(278,$iAlt,urldecode($oDadoAcordo->getResumoObjeto)                             ,'TB','L',$iCorFundo);
  
}
  
$oPdf->SetFont('Arial','B',$iFonte);
$oPdf->Cell(278 ,$iAlt-3,''                                                                                 ,0,1,'C',0);
$oPdf->Cell(30 ,$iAlt,'Total de Registros:'                                                                 ,0,0,'L',0);
$oPdf->Cell(30 ,$iAlt,''.count($aDadosAcordo).''                                                            ,0,0,'L',0);

$oPdf->Output();

/*
 * Monta Cabecalho dos Arcordos
 */
function imprimirCabecalhoAcordos($oPdf, $iFonte, $iAlt, $lImprime) {
  
	if ($oPdf->GetY() > ($oPdf->h - 30) || $lImprime) {
		
		$oPdf->AddPage('L');
    $oPdf->SetFont('Arial','B',$iFonte);
    $oPdf->SetFillColor(220);
    $oPdf->Cell(40 ,$iAlt,'Código'                                                                          ,1,0,'C',1);
    $oPdf->Cell(40 ,$iAlt,'Tipo de Acordo'                                                                  ,1,0,'C',1);
    $oPdf->Cell(78 ,$iAlt,'Contratado'                                                                      ,1,0,'C',1);
    $oPdf->Cell(40 ,$iAlt,'Assinatura'                                                                      ,1,0,'C',1);
    $oPdf->Cell(40 ,$iAlt,'Vigência'                                                                        ,1,0,'C',1);
    $oPdf->Cell(40 ,$iAlt,'Valor Total'                                                                     ,1,1,'C',1);
    $oPdf->Cell(278 ,$iAlt,'Resumo'                                                                         ,1,1,'L',1);
	}
}
?>