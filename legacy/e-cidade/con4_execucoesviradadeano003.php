<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("fpdf151/pdf.php");
include("libs/db_utils.php");

$oGet  = db_utils::postMemory($_GET,0);

$iDbInstit  = db_getsession('DB_instit');
$dtIni      = "";
$dtFim      = "";
$sLetra     = "arial";
$sWhere     = "";
$sOrder     = " order by c30_usuario ";
$sAnd       = "";

// situação: 0=todos (default) 1=processado 2=cancelado

if (isset($oGet->dtini) && isset($oGet->dtfim)) {
  if ($oGet->dtini != "--" && $oGet->dtfim != "--") {
    if ($oGet->dtini != "" && $oGet->dtfim != "") {
    	$dtIni = $oGet->dtini;
    	$dtFim = $oGet->dtfim;
      $sWhere .= " {$sAnd} c30_data between '{$dtIni}' and '{$dtFim}' ";
      $sAnd    = " and ";
    }
  }
}

if (isset($oGet->usuario) && $oGet->usuario != '') {
  $sWhere .= " {$sAnd} c30_usuario = {$oGet->usuario} ";
  $sAnd    = " and ";	
}

if (isset($oGet->situacao) && $oGet->situacao != '') {
	if ($oGet->situacao == 0) {
		$sSituacao = "Todos";
	} else if ($oGet->situacao == 1) {
		$sSituacao = "Processado";
    $sWhere .= " {$sAnd} c30_situacao = {$oGet->situacao} ";
    $sAnd    = " and ";
	} else if ($oGet->situacao == 2) {
		$sSituacao = "Cancelado";
    $sWhere .= " {$sAnd} c30_situacao = {$oGet->situacao} ";
    $sAnd    = " and ";
	}
}

if (!empty($sWhere)) {
	$sWhere = " where {$sWhere} ";
}

//die($sWhere);

$sSql  = " select c30_sequencial,                                                                                     ";
$sSql .= "        c30_anoorigem,                                                                                      ";
$sSql .= "        c30_anodestino,                                                                                     ";
$sSql .= "        c30_usuario,                                                                                        ";
$sSql .= "        login,                                                                                              ";
$sSql .= "        c30_data,                                                                                           ";
$sSql .= "        c30_hora,                                                                                           ";
$sSql .= "        c30_situacao,                                                                                       ";
$sSql .= "        case                                                                                                ";
$sSql .= "           when c30_situacao = 1 then 'Processado'                                                          ";
$sSql .= "           when c30_situacao = 2 then 'Cancelado'                                                           ";
$sSql .= "        end as c30_sitdescr,                                                                                ";
$sSql .= "        c31_db_viradacaditem,                                                                               ";
$sSql .= "        c33_descricao,                                                                                      ";
$sSql .= "        c31_situacao,                                                                                       ";
$sSql .= "        case                                                                                                ";
$sSql .= "           when c31_situacao = 1 then 'Processado'                                                          ";
$sSql .= "           when c31_situacao = 2 then 'Cancelado'                                                           ";
$sSql .= "        end as c31_sitdescr                                                                                 ";
$sSql .= "   from db_virada                                                                                           ";
$sSql .= "        inner join db_usuarios      on db_usuarios.id_usuario          = db_virada.c30_usuario              ";
$sSql .= "        inner join db_viradaitem    on db_viradaitem.c31_db_virada     = db_virada.c30_sequencial           ";
$sSql .= "        inner join db_viradacaditem on db_viradacaditem.c33_sequencial = db_viradaitem.c31_db_viradacaditem ";
$sSql .= "        {$sWhere} {$sOrder}                                                                                 ";

//die($sSql);
        
$rsSql        = pg_query($sSql);
$iNumRownsSql = pg_num_rows($rsSql);

if ($iNumRownsSql == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$head2 = "RELATÓRIO DE EXECUÇÕES DA VIRADA";

if (isset($dtIni) && $dtIni != '' && isset($dtFim) && $dtFim != '') {
  $head4 = "PERÍODO: ".db_formatar($dtIni,'d')." à ".db_formatar($dtFim,'d');	
}

$head5 = "USUÁRIO: ";
$head6 = "SITUAÇÃO: ".$sSituacao;



$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$lImprime       = true;
$aDadosvirada   = array();
$aDadosExecucao = array();

for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {
          
  $oDadosExecVirada = db_utils::fieldsMemory($rsSql,$iInd);

  $oDadosPrincipal = new stdClass();
  $oDadosPrincipal->AnoOrigem      = $oDadosExecVirada->c30_anoorigem;
  $oDadosPrincipal->AnoDestino     = $oDadosExecVirada->c30_anodestino;
  $oDadosPrincipal->sUsuario       = $oDadosExecVirada->login;
  $oDadosPrincipal->dtData         = $oDadosExecVirada->c30_data;
  $oDadosPrincipal->hrHora         = $oDadosExecVirada->c30_hora;
  $oDadosPrincipal->iSituacao      = $oDadosExecVirada->c30_situacao;

  $oDadosItens = new stdClass();
  $oDadosItens->iCodItem           = $oDadosExecVirada->c31_db_viradacaditem;
  $oDadosItens->sDescricao         = $oDadosExecVirada->c33_descricao;
  $oDadosItens->sSituacao          = $oDadosExecVirada->c30_sitdescr;
                       
  if ( !isset($aDadosvirada[$oDadosExecVirada->c30_sequencial]) ) {
      $aDadosvirada[$oDadosExecVirada->c30_sequencial]['oDadosExecVir']   = $oDadosPrincipal;
      $aDadosvirada[$oDadosExecVirada->c30_sequencial]['aListaItens'][]   = $oDadosItens;
  } else {
      $aDadosvirada[$oDadosExecVirada->c30_sequencial]['aListaItens'][]   = $oDadosItens; 
  }
                                       
}

foreach ( $aDadosvirada as $iInd => $aDadosExecucao ) {
	
	$sNome = substr($aDadosExecucao['oDadosExecVir']->sUsuario,0,35);
	
  if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){
      
    $lImprime = false;
    $pdf->addpage();

    $pdf->ln(0);
    $pdf->SetFont($sLetra,'B',6);
    $pdf->Cell(25,4,"Ano Origem"                                        ,1,0,"C",1);
    $pdf->Cell(25,4,"Ano Destino"                                       ,1,0,"C",1);
    $pdf->Cell(67,4,"Usuário"                                           ,1,0,"C",1);
    $pdf->Cell(25,4,"Data"                                              ,1,0,"C",1);
    $pdf->Cell(25,4,"Hora"                                              ,1,0,"C",1);
    $pdf->Cell(25,4,"Situação"                                          ,1,1,"C",1);

    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,"Item (código)"                                     ,1,0,"C",1);
    $pdf->Cell(67,4,"Item (descrição)"                                  ,1,0,"C",1);
    $pdf->Cell(25,4,"Situação (descricao)"                              ,1,1,"C",1);
    
    }
    
  $pdf->SetFont($sLetra,'B',5);
  $pdf->Cell(25,4,$aDadosExecucao['oDadosExecVir']->AnoOrigem           ,0,0,"C",0);
  $pdf->Cell(25,4,$aDadosExecucao['oDadosExecVir']->AnoDestino          ,0,0,"C",0);
  $pdf->Cell(67,4,$sNome                                                ,0,0,"L",0);
  $pdf->Cell(25,4,$aDadosExecucao['oDadosExecVir']->dtData              ,0,0,"C",0);
  $pdf->Cell(25,4,$aDadosExecucao['oDadosExecVir']->hrHora              ,0,0,"C",0);
  $pdf->Cell(25,4,$aDadosExecucao['oDadosExecVir']->iSituacao           ,0,1,"C",0);

  $nTotalItens = 0;
  
  foreach ( $aDadosExecucao['aListaItens'] as $iInd => $oDadosItens ) {

  	$pdf->SetFont($sLetra,'',5);
    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,""                                                  ,0,0,"C",0);
    $pdf->Cell(25,4,$oDadosItens->iCodItem                              ,0,0,"C",0);
    $pdf->Cell(67,4,$oDadosItens->sDescricao                            ,0,0,"L",0);
    $pdf->Cell(25,4,$oDadosItens->sSituacao                             ,0,1,"C",0);

    $nTotalItens++; 
  }

  $pdf->Cell(192,2,""                                                   ,0,1,0,0);
  $pdf->Cell(192,0,""                                                   ,"T",1,0,0);
  $pdf->ln(0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->cell(175,3,'Total de Itens --->  '                              ,0,0,"R",0);
  $pdf->cell(17,3,$nTotalItens                                          ,0,1,"C",0);
  
}

  $pdf->Cell(192,2,""                                                   ,0,1,0,0);
  $pdf->Cell(192,0,""                                                   ,"T",1,0,0);
  $pdf->ln(0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->cell(175,3,'Total Geral --->  '                                 ,0,0,"R",0);
  $pdf->cell(17,3,$iNumRownsSql                                         ,0,1,"C",0);

$pdf->output();
?>