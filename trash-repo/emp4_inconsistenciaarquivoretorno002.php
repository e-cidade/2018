<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once('libs/db_utils.php');
require_once("classes/db_empagedadosret_classe.php");

$oGet               = db_utils::postMemory($_GET);
$oDaoEmpAgeDadosRet = new cl_empagedadosret();

/*
 * Configura as variáveis de data para serem utilizadas no banco de dados
 */
$sDataInicial = implode("-", array_reverse(explode("/", $oGet->sDataInicial)));
$sDataFinal   = implode("-", array_reverse(explode("/", $oGet->sDataFinal)));

/*
 * Configuração do SQL que será executado
 */
$sSqlCampos        = " e75_codret as arquivo_retorno,                   ";
$sSqlCampos       .= " e76_codmov as movimento,													";
$sSqlCampos       .= " case when 																				";
$sSqlCampos       .= "        k17_codigo is null 												";
$sSqlCampos       .= "      then e50_codord 														";
$sSqlCampos       .= "      else k17_codigo 														";
$sSqlCampos       .= "  end as op_slip, 																";
$sSqlCampos       .= " e60_codemp  ||'/'||e60_anousu    as empenho,   ";
$sSqlCampos       .= " e92_coderro ||' - '||e92_descrerro as erro_banco ";

/**
 * Configura as Datas caso alguma venha vazia
 */
$sDataAtual = date("d/m/Y");
if (empty($sDataInicial) && empty($sDataFinal)) {
  
  $sDataWhere   = "";
  $sHeadPeriodo = "Período: Nenhum período selecionado";
  
} else if (!empty($sDataInicial) && empty($sDataFinal)) {
  
  $sDataWhere   = " and e76_dataefet >= '{$sDataInicial}' ";
  $sHeadPeriodo = "Período: {$sDataInicial} à {$sDataAtual}";
  
} else if (empty($sDataInicial) && !empty($sDataFinal)) {
  
  $sDataWhere   = " and e76_dataefet <= '{$sDataFinal}' ";
  $sHeadPeriodo = "Período: até {$sDataFinal}";
  
} else {
  $sDataWhere   = " and e76_dataefet between '{$sDataInicial}' and '{$sDataFinal}' ";
  $sHeadPeriodo = "Período: {$sDataInicial} à {$sDataFinal}";
}
$sSqlWhere         = "     e75_codret in ({$oGet->iArquivoRetorno}) ";
$sSqlWhere        .= " and e92_processa is false {$sDataWhere}   ";
$sSqlWhere        .= " and e92_sequencia <> 35   "; // o coderro BD não sera apresentado no relatório
$sSqlDadosRetorno  = $oDaoEmpAgeDadosRet->sql_query_erro_processamento(null, $sSqlCampos, null, $sSqlWhere);
$rsDadosRetorno    = $oDaoEmpAgeDadosRet->sql_record($sSqlDadosRetorno);
$iTotalLinhas      = $oDaoEmpAgeDadosRet->numrows;

if ($iTotalLinhas == 0) {
  db_redireciona("db_erros.php?fechar&db_erro=Nenhum registro encontrado.");
}

$head3 = "Relatório de Inconsistencia do Arquivo do Retorno do Banco";
$head4 = $sHeadPeriodo; 


$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(244);

$iAltura       = 4;
$iFonte        = 8;
$iPreenche     = 1;
$lPrimeiroLaco = true;

/*
 * Percorre o result set imprimindo os dados não processados de um arquivo 
 */
for ($iRow = 0; $iRow < $iTotalLinhas; $iRow++) {
  
  $oDadoRet = db_utils::fieldsMemory($rsDadosRetorno, $iRow);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
  
    imprimeCabecalho($oPdf, $iAltura, $iFonte);
    $lPrimeiroLaco = false;
  }
  
  if ($iPreenche == 1) {
    $iPreenche = 0;
  } else {
    $iPreenche = 1;
  }
  
  $oPdf->Cell(20 , $iAltura, $oDadoRet->arquivo_retorno , 0, 0, 'C', $iPreenche);
  $oPdf->Cell(20 , $iAltura, $oDadoRet->movimento       , 0, 0, 'C', $iPreenche);
  $oPdf->Cell(30 , $iAltura, $oDadoRet->op_slip         , 0, 0, 'C', $iPreenche);
  $oPdf->Cell(30 , $iAltura, $oDadoRet->empenho         , 0, 0, 'C', $iPreenche);
  $oPdf->Cell(90 , $iAltura, $oDadoRet->erro_banco      , 0, 1, 'L', $iPreenche);
}

$oPdf->Output();

/**
 * imprimeCabecalho
 * Imprime o cabeçalho do relatório quando necessário.
 * @param object  $oPdf
 * @param integer $iAlt
 * @param integer $iFonte
 */
function imprimeCabecalho($oPdf, $iAltura, $iFonte) {
  
	$oPdf->AddPage("P");
	$oPdf->SetFont('Arial','b',$iFonte);
  $oPdf->Cell(20 , $iAltura, "Retorno"    , 1, 0, 'C',1);
  $oPdf->Cell(20 , $iAltura, "Movimento"  , 1, 0, 'C',1);
  $oPdf->Cell(30 , $iAltura, "OP / SLIP"  , 1, 0, 'C',1);
  $oPdf->Cell(30 , $iAltura, "Empenho"    , 1, 0, 'C',1);
  $oPdf->Cell(90 , $iAltura, "Erro Banco" , 1, 1, 'C',1);
	$oPdf->SetFont('Arial','',$iFonte);
}
?>