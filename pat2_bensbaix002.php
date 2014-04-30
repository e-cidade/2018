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
include("classes/db_bensmotbaixa_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_cfpatri_classe.php");
$clcfpatric 		= new cl_cfpatri;
$cldepartorg 	 	= new cl_db_departorg;
$clbens 				= new cl_bens;
$clbensmotbaixa = new cl_bensmotbaixa;
$clbensmater 		= new cl_bensmater;
$clbensimoveis 	= new cl_bensimoveis;
$clbensbaix 		= new cl_bensbaix;
$cldb_depart 		= new cl_db_depart;
$clrotulo 			= new rotulocampo;
$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();
$clbensbaix->rotulo->label();
$cldb_depart->rotulo->label();
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("t64_descr"); //descrição classificação
$clrotulo->label("descrdepto"); //descrição do depart
$clrotulo->label("t51_descr"); //descrição do motivo da baixa

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$numrows    = 0;
$relbaix    = "t52_instit = ".db_getsession("DB_instit");
$msg        = "";
$sHeadPlaca = "";
if(isset($dataINI) && trim($dataINI)!="" || isset($dataFIM) && trim($dataFIM)!=""){
  if(isset($dataINI) && trim($dataINI)!=""&&trim($dataFIM)=="" ){
    $relbaix .= " and t55_baixa >='".$dataINI."' ";
    $msg      = "posterior a ".db_formatar($dataINI,"d");
  }
  if(isset($dataFIM) && trim($dataFIM)!=""){
    if($relbaix!=""){
      $relbaix .= " and t55_baixa between '".$dataINI."' and '".$dataFIM."' ";
      $msg      = "entre ".db_formatar($dataINI,"d")." e ".db_formatar($dataFIM,"d");
    }else{
      $relbaix .= " and t55_baixa<'".$dataFIM."' ";
      $msg      = "anterior a ".db_formatar($dataFIM,"d");
    }
  }
}else{
  $msg = " TODOS OS BENS BAIXADOS";
}

/**
 * Configura Where e Mensagem de Head PLACA
 */
if ( isset($placaInicial) && trim($placaInicial) != "" ) {
  
  $sHeadPlaca = "Placas de: {$placaInicial}";
  $relbaix .= " and t52_ident >= '{$placaInicial}' ";
  
  if (isset($placaFinal) && trim($placaFinal) != "") {
    
    $relbaix .= " and t52_ident <= '{$placaFinal}' ";
    $sHeadPlaca .= " até {$placaFinal}";
  } else {
    $sHeadPlaca = "Placas superiors à {$placaInicial}";
  }
  
}

$sOrderBy = '';

if (!empty($sOrder)) {
  
  $sOrderBy = $sOrder;
}

$sSqlBensBaixa = $clbensbaix->sql_query(null,"*", $sOrderBy, $relbaix);
$result_baixa  = $clbensbaix->sql_record($sSqlBensBaixa);
//echo($clbensbaix->sql_query(null,"*","","$relbaix")); exit;
if ($clbensbaix->numrows == 0) {
  
    $sMsg= _M('patrimonial.patrimonio.pat2_bensbaix002.nenhum_cadastro_bem_baixado');
    db_redireciona("db_erros.php?fechar=true&db_erro=". $sMsg);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$p = 1;
$alt = 4;

//Verifica se utiliza pesquisa por orgão sim ou não
$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
	db_fieldsmemory($resPesquisaOrgao,0);
	$lImprimeOrgao = $t06_pesqorgao;
}

if($lImprimeOrgao == 't'){
	
  $relbaix      .= " and db01_anousu = ".db_getsession('DB_anousu');
	
	$sSelect       = "  distinct                                                                         ";
  $sSelect      .= "  bens.t52_bem,                                                                    ";
  $sSelect      .= "  bens.t52_descr,                                                                  ";
  $sSelect      .= "  bens.t52_ident,                                                                  ";
  $sSelect      .= "  bens.t52_depart,                                                                 ";
  $sSelect      .= "  db_depart.descrdepto,                                                            ";
  $sSelect      .= "  bensbaix.t55_baixa,                                                              ";
  $sSelect      .= "  bensbaix.t55_motivo,                                                             ";
  $sSelect      .= "  bensbaix.t55_obs,                                                                ";
  $sSelect      .= "  clabens.t64_class,                                                               ";
  $sSelect      .= "  clabens.t64_descr,                                                               ";
  $sSelect      .= "  orcunidade.o41_unidade,                                                          ";
  $sSelect      .= "  orcunidade.o41_descr,                                                            ";
  $sSelect      .= "  orcorgao.o40_orgao,                                                              ";
  $sSelect      .= "  orcorgao.o40_descr,                                                              ";
  $sSelect      .= "  (case when t52_bem in                                                            ";
  $sSelect      .= "  (select t53_codbem from bensmater) then 'Material' else                          ";
  $sSelect      .= "  (case when t52_bem in                                                            ";
  $sSelect      .= "       (select t54_codbem from bensimoveis) then 'Imóvel' else 'Indefinido'        ";
  $sSelect      .= "  end)                                                                             ";
  $sSelect      .= "  end) as definicao                                                                ";
  
  $sSqlBaixaBens = $clbensbaix->sql_query_relatorio(null, $sSelect, "$sOrderBy, o40_orgao,o41_unidade", "$relbaix");
	$result_bens   = $clbensbaix->sql_record($sSqlBaixaBens);
	
} else {
	
	$sSelect       = " distinct                                                                    ";
	$sSelect      .= " bens.t52_bem,                                                               ";
  $sSelect      .= " bens.t52_descr,                                                             ";
  $sSelect      .= " bens.t52_ident,                                                             ";
  $sSelect      .= " bens.t52_depart,                                                            ";
  $sSelect      .= " db_depart.descrdepto,                                                       ";
  $sSelect      .= " bensbaix.t55_baixa,                                                         ";
  $sSelect      .= " bensbaix.t55_motivo,                                                        ";
  $sSelect      .= " bensbaix.t55_obs,                                                           ";
  $sSelect      .= " clabens.t64_class,                                                          ";
  $sSelect      .= " clabens.t64_descr,                                                          ";
  $sSelect      .= " (case when t52_bem in                                                       ";
  $sSelect      .= " (select t53_codbem from bensmater) then 'Material' else                     ";
  $sSelect      .= " (case when t52_bem in                                                       ";
  $sSelect      .= "    (select t54_codbem from bensimoveis) then 'Imóvel' else 'Indefinido'     ";
  $sSelect      .= " end)                                                                        ";
  $sSelect      .= " end) as definicao                                                           ";
  $sSqlBaixaBens = $clbensbaix->sql_query(null, $sSelect, "$sOrderBy", "$relbaix");
	$result_bens   = $clbensbaix->sql_record($sSqlBaixaBens);
}

$iOrgao = 0;
$iUnidade = 0;

$numrows=$clbensbaix->numrows;
for($cont=0;$cont<$numrows;$cont++){
  db_fieldsmemory($result_bens,$cont);
  if($p==1){
    $p=0;   
  }else{
    $p=1;
  }
  $head3 = "BENS BAIXADOS";
  $head6 = $sHeadPlaca;
  if(trim($relbaix)==""){
    $head7 = "$msg" ;
  }else{
    $head7 = "Período $msg " ;
  }
  $result_descrmotbaixa = $clbensmotbaixa->sql_record($clbensmotbaixa->sql_query_file($t55_motivo));
  db_fieldsmemory($result_descrmotbaixa,0);
  
	$resOrgaoUnidade = $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,null,"o40_orgao,o40_descr,o41_unidade,o41_descr"));
  if($cldepartorg->numrows > 0){
  	db_fieldsmemory($resOrgaoUnidade,0);
  }
  
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',10);
    
    if($lImprimeOrgao == 't' && $o40_orgao != $iOrgao){
    	$pdf->cell(20,$alt,"Órgão",0,0,"L",0);
    	$pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);
    	$iOrgao = $o40_orgao;
    }
    if($lImprimeOrgao == 't' && $o41_unidade != $iUnidade){
    	$pdf->cell(20,$alt,"Unidade",0,0,"L",0);
    	$pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
    	$iUnidade = $o41_unidade;
    	$pdf->Ln(3);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(15,$alt,"Baixa",1,0,"C",1);
    $pdf->cell(15,$alt,"Código",1,0,"C",1);
    $pdf->cell(15,$alt,"Placa",1,0,"C",1);
    $pdf->cell(85,$alt,$RLt52_descr,1,0,"C",1);
    $pdf->cell(20,$alt,$RLt64_class,1,0,"C",1);
    $pdf->cell(60,$alt,$RLt64_descr,1,0,"C",1);
    $pdf->cell(14,$alt,"Definição",1,0,"C",1);
    $pdf->cell(60,$alt,$RLdescrdepto,1,1,"C",1);
    $pdf->cell(284,$alt,$RLt51_descr,1,1,"L",1);
    $pdf->cell(284,$alt,$RLt55_obs,1,1,"L",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(15,$alt,db_formatar($t55_baixa,"d"),"T",0,"C",0);
  $pdf->cell(15,$alt,$t52_bem,"T",0,"C",0);
  $pdf->cell(15,$alt,$t52_ident,"T",0,"C",0);
  
  if (strlen($t52_descr) > 67) {
    $pdf->cell(85,$alt,substr($t52_descr,0,67)."...","T",0,"L",0);	
  } else {
  	$pdf->cell(85,$alt,$t52_descr,"T",0,"L",0);
  }
  
  $pdf->cell(20,$alt,$t64_class,"T",0,"C",0);
  $pdf->cell(60,$alt,$t64_descr,"T",0,"L",0);
  $pdf->cell(14,$alt,$definicao,"T",0,"L",0);
  $pdf->multicell(60,$alt,$descrdepto,"T",1,"L",0);
  $pdf->cell(284,$alt,$t51_descr,"T",1,"L",0);  
  $pdf->multicell(284,$alt,$t55_obs,"T",1,"L",0);
  $total++;
}
//$pdf->cell(278,1,"","T",1,"L",0);    
$pdf->setfont('arial','b',8);
$pdf->cell(284,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>