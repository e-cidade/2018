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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_bens_classe.php");
require("libs/db_utils.php");

$clbens         = new cl_bens;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet = db_utils::postMemory($_GET);

//echo "<pre>";
//echo var_dump($_GET);
//echo "</pre>";
//exit();

//  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Parâmetros de Placa para esta instituição.');

$head3  = "RELATÓRIO ETIQUETAS EMITIDAS";

  //db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
$sWhere = " 1=1 ";


if(isset($oGet->departamentos) && trim($oGet->departamentos) != ''){
	$sWhere .=  $sWhere == "" ? " t73_coddepto in $oGet->departamentos " : " and t73_coddepto in $oGet->departamentos ";
}
if(isset($oGet->divisoes) && trim($oGet->divisoes) != ''){
  $sWhere .=  $sWhere == "" ? " t73_departdiv in $oGet->divisoes " : " and t73_departdiv in $oGet->divisoes ";
}
if(isset($oGet->bens) && trim($oGet->bens) != ''){
  $sWhere .=  $sWhere == "" ? " t52_bem in $oGet->bens " : " and t52_bem in $oGet->bens ";
}
if(isset($oGet->clabens) && trim($oGet->clabens) != ''){
  $sWhere .=  $sWhere == "" ? " t52_codcla in $oGet->clabens " : " and t52_codcla in $oGet->clabens ";
}
//$oGet->icodigoetiqueta = 2;
if(isset($oGet->icodigoetiqueta) && trim($oGet->icodigoetiqueta) != ''){
  $sWhere .=  $sWhere == "" ? " t74_sequencial = $oGet->icodigoetiqueta " : " and t74_sequencial = $oGet->icodigoetiqueta ";
}
if(isset($oGet->tipo) && trim($oGet->tipo) != ''){
	if($oGet->tipo != "T" && $oGet->tipo == "L"){
    $sWhere .=  $sWhere == "" ? " t73_tipoloteindividual is true " : " and t73_tipoloteindividual is true ";
	}else if($oGet->tipo != "T" && $oGet->tipo == "I"){
		$sWhere .=  $sWhere == "" ? " t73_tipoloteindividual is false " : " and t73_tipoloteindividual is false ";
	}
}
if(isset($oGet->dtinicial) && isset($oGet->dtfinal)){
	
	$sWhere .=  $sWhere == "" ? " t74_data between '$oGet->dtinicial' and '$oGet->dtfinal' " : 
	                       " and  t74_data between '$oGet->dtinicial' and '$oGet->dtfinal' ";
}else if(isset($oGet->dtinicial)){
	$sWhere .=  $sWhere == "" ?  "t74_data >= '$oGet->dtinicial' " : "and  t74_data >= '$oGet->dtinicial' ";
}

$ordem = "order by t52_bem asc";
$head4 = "Ordenado por: ";
if(isset($oGet->ordenar) && trim($oGet->ordenar) != "" ){
	
	if($oGet->ordenar == 1){
		$ordem = " order by t52_bem asc";
		$head4 .= " Bem ";
	}else if($oGet->ordenar == 2){
		$ordem = " order by t52_ident asc";
		$head4 .= " Placa ";
	}else if ($oGet->ordenar == 3){
		$ordem = " order by t74_data asc";
		$head4 .= " Data ";
	}
	
}


$sQuery  = "select "; 
$sQuery .= "       t52_bem,";
$sQuery .= "       t52_descr,"; 
$sQuery .= "       t52_ident,"; 
$sQuery .= "       t73_coddepto,"; 
$sQuery .= "       t73_departdiv,";
$sQuery .= "       t73_tipoloteindividual,"; 
$sQuery .= "       t30_descr,"; 
$sQuery .= "       t64_class,"; 
$sQuery .= "       t64_descr,"; 
$sQuery .= "       t74_usuario,"; 
$sQuery .= "       login,"; 
$sQuery .= "       t74_data,"; 
$sQuery .= "       descrdepto,"; 
$sQuery .= "       t74_hora, ";
$sQuery .= "       (select count(*) 
                      from bensplaca 
                           inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
                     where t52_bem = t41_bem
                     group by t41_bem ) as total"; 
$sQuery .= "  from bensplacaimpressa"; 
$sQuery .= "       inner join bensplaca            on bensplaca.t41_codigo                = bensplacaimpressa.t73_bensplaca";
$sQuery .= "       inner join bens                 on bens.t52_bem                        = bensplaca.t41_bem";
$sQuery .= "       inner join clabens              on clabens.t64_codcla                  = bens.t52_codcla    ";     
$sQuery .= "       inner join db_depart            on db_depart.coddepto                  = bensplacaimpressa.t73_coddepto";
$sQuery .= "       inner join departdiv            on departdiv.t30_codigo                = bensplacaimpressa.t73_departdiv";
$sQuery .= "       inner join bensetiquetaimpressa on bensetiquetaimpressa.t74_sequencial = bensplacaimpressa.t73_bensetiquetaimpressa";
$sQuery .= "       inner join db_usuarios          on db_usuarios.id_usuario              = bensetiquetaimpressa.t74_usuario";

$sQuery .= " where  ".$sWhere;
$sQuery .= $ordem;

//die($sQuery);

$rsQuery = db_query($sQuery);
$iNumRows = pg_num_rows($rsQuery);

if($iNumRows == 0){
	
  $sMsg = _M('patrimonial.patrimonio.pat2_etiquetasemitidas002.nao_existem_dados');
	db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsg);
	
}

$aRelatorio = db_utils::getColectionByRecord($rsQuery);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->AddPage("L");

$alt = 4;

cabecalhoEtiquetas($pdf,$alt);

$pdf->Ln(3);
$bgcolor = 1;
$pdf->setfont('arial','',8);

foreach ($aRelatorio as $oBem){
	
	if(isset($oGet->etiqueta) && $oGet->etiqueta == "R" && $oBem->total < 1){
		continue;
	}
	
  if ($pdf->GetY() > $pdf->h - 25){
          
    $pdf->AddPage("L");
    $pdf->setfont('arial','b',8);
    cabecalhoEtiquetas($pdf,$alt);
    $pdf->setfont('arial','',8);
  }
	
	
	$bgcolor = $bgcolor == 1 ? 0 : 1;
	$pdf->cell(20,$alt,$oBem->t52_bem,   0,0,"R",$bgcolor);
  $pdf->cell(100,$alt,$oBem->t52_descr, 0,0,"L",$bgcolor);
  $pdf->cell(25,$alt,$oBem->t52_ident ,0,0,"C",$bgcolor);
  $pdf->cell(35,$alt,$oBem->t64_class ,0,0,"C",$bgcolor);
  $pdf->cell(100,$alt,$oBem->t64_descr ,0,1,"L",$bgcolor);

  $sImpressa = $oBem->total > 1 ? "reimpressa" : "impressa";
  
  $pdf->cell(20,$alt,$sImpressa            ,0,0,"C",$bgcolor);
  $pdf->cell(35,$alt,db_formatar($oBem->t74_data,'d')." ".$oBem->t74_hora,0,0,"C",$bgcolor);
  $pdf->cell(45,$alt,$oBem->login     ,0,0,"C",$bgcolor);
  $pdf->cell(80,$alt,$oBem->t73_coddepto." - ".$oBem->descrdepto,0,0,"L",$bgcolor);
  $pdf->cell(80,$alt,$oBem->t73_departdiv." - ".$oBem->t30_descr,0,0,"L",$bgcolor);
  $tipo = $oBem->t73_tipoloteindividual == 'f' ? 'Individual' : 'Lote';
  $pdf->cell(20,$alt,$tipo            ,0,1,"C",$bgcolor);
  
	
}

$pdf->Ln(3);
$pdf->cell(270,$alt,"Total de Registros : "           ,0,0,"R",$bgcolor);
$iNumReg = count($aRelatorio);
$pdf->cell(20,$alt,$iNumReg,0,1,"L",$bgcolor);

$pdf->Output();

function cabecalhoEtiquetas($pdf,$alt){
	
		$pdf->setfont('arial','b',8);
		
    $pdf->cell(20,$alt,"Código"			          ,1,0,"C",1);
    $pdf->cell(100,$alt,"Descrição"	          ,1,0,"C",1);
    $pdf->cell(25,$alt,"Placa"                ,1,0,"C",1);
    $pdf->cell(35,$alt,"Estrutural"	          ,1,0,"C",1);
    $pdf->cell(100,$alt,"Descricao Estrutural.",1,1,"C",1);
    
    $pdf->cell(20,$alt,"Etiqueta"		          ,1,0,"C",1);
    $pdf->cell(35,$alt,"data/hora"	          ,1,0,"C",1);
    $pdf->cell(45,$alt,"Usuário"		          ,1,0,"C",1);
    $pdf->cell(80,$alt,"Departamento"         ,1,0,"C",1);
    $pdf->cell(80,$alt,"Divisão"		          ,1,0,"C",1);
    $pdf->cell(20,$alt,"Tipo"				          ,1,1,"C",1);
	
}
?>