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
include("dbforms/db_funcoes.php");
include("classes/db_bens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("classes/db_histbem_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_benstransfconf_classe.php");
include("classes/db_cfpatriplaca_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_cfpatri_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$clcfpatric 		= new cl_cfpatri;
$clbens             = new cl_bens;
$clbensmater        = new cl_bensmater;
$clbensimoveis      = new cl_bensimoveis;
$clbensbaix         = new cl_bensbaix;
$clhistbem          = new cl_histbem;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clbenstransfconf   = new cl_benstransfconf;
$clcfpatriplaca     = new cl_cfpatriplaca;
$cldepartorg 	 	= new cl_db_departorg;

$clrotulo = new rotulocampo;

$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();
$clhistbem->rotulo->label();
$clrotulo->label("t64_descr"); //fornecedor
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("t70_descr"); //descr situação
$clrotulo->label("descrdepto");//departamento

//Verifica se utiliza pesquisa por orgão sim ou não
$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
	db_fieldsmemory($resPesquisaOrgao,0);
	$lImprimeOrgao = $t06_pesqorgao;
}

if(isset($t52_bem) && $t52_bem!=""){
  $res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
  if ($clcfpatriplaca->numrows > 0){
       db_fieldsmemory($res_cfpatriplaca,0);
  }
  
 	$sCampos = "distinct 
  	 t52_bem,t52_codcla,t52_numcgm,t52_valaqu,t52_dtaqu,t52_ident,t52_descr,t52_obs,t52_depart,t52_instit,t52_bensmarca,
  	 t52_bensmodelo,t52_bensmedida,t30_codigo,t30_descr,descrdepto,t64_class,t64_descr"; 

  $result = $clbens->sql_record($clbens->sql_query_class(null,$sCampos,null,"t52_bem in $t52_bem and t52_instit = ".db_getsession("DB_instit")));
  if($clbens->numrows>0){
    //db_fieldsmemory($result,0);
  }else{
    
    $oParms = new stdClass();
    $oParms->sBem = $t52_bem;
    $sMsg = _M('patrimonial.patrimonio.pat2_histbem002.bem_nao_encontrado', $oParms);
    
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$alt = 4;  
  
$iNumRows = $clbens->numrows;
//die($iNumRows);
for($iInd = 0; $iInd < $iNumRows; $iInd++){
		db_fieldsmemory($result,$iInd);  
  
	$resOrgaoUnidade = $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,db_getsession('DB_anousu'),"o40_orgao,o40_descr,o41_unidade,o41_descr"));
  if($cldepartorg->numrows > 0){
  	db_fieldsmemory($resOrgaoUnidade,0);
  }
  
  $clbensmater->sql_record($clbensmater->sql_query_file($t52_bem));
  if($clbensmater->numrows>0){
    $definicao = "MATERIAL";
  }else{
    $clbensimoveis->sql_record($clbensimoveis->sql_query_file($t52_bem));
    if($clbensimoveis->numrows>0){
      $definicao = "IMÓVEL";
    }else{
      $definicao = "MATERIAL";
    }
  }
  $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
  if($clbensbaix->numrows>0){
    $baix = "BAIXADO";
  }else{
    $baix = "NÃO BAIXADO";
  }

  $depto_origem="";

  $head2= "HISTÓRICO DO BEM";
  $head4 = "CÓDIGO: $t52_bem";
  $head5 = "DESCRIÇÃO: $t52_descr";
  $head7 = $definicao." - ".$baix;
  $pdf->addpage("L");
  $pdf->setfont('arial','b',8);
  $pdf->cell(90,$alt,"DADOS DO BEM",0,1,"L",0);

  if($lImprimeOrgao == 't') {
	  $pdf->setfont('arial','b',7);
	  $pdf->cell(30,$alt,'Orgão',0,0,"L",0);
	  $pdf->setfont('arial','',7);
	  $pdf->cell(0,$alt,$o40_orgao." ".$o40_descr,0,1,"L",0);
	  
	  $pdf->setfont('arial','b',7);
	  $pdf->cell(30,$alt,'Unidade',0,0,"L",0);
	  $pdf->setfont('arial','',7);
	  $pdf->cell(0,$alt,$o41_unidade." ".$o41_descr,0,1,"L",0);
  }  
  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_depart,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t52_depart." ".$descrdepto,0,1,"L",0);
/*
  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLdescrdepto,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$descrdepto,0,1,"L",0);
*/
  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt64_class,0,0,"L",0);    
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t64_class." - ".$t64_descr,0,1,"L",0);

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_dtaqu,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,db_formatar($t52_dtaqu,"d"),0,1,"L",0);

  if (strlen(trim($t52_ident)) > 0){
       if ($t07_confplaca == 4){
            $t52_ident = db_formatar($t52_ident,"s","0",$t07_digseqplaca,"e",0);
       }
  }        

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_ident,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t52_ident,0,1,"L",0);
  
  if ($opcao_obs == "S"){
       if (trim($t52_obs)!=""){
            $pdf->setfont('arial','b',7);
            $pdf->cell(50,$alt,"Características adicionais do bem",0,0,"L",0);
            $pdf->setfont('arial','',7);
            $pdf->multicell(195,$alt,$t52_obs,0,"L",0);
       }
  }

  $pdf->ln(10);
  $pdf->setfont('arial','b',8);
  $result_histbem = $clhistbem->sql_record($clhistbem->sql_query(null,"t56_data,db_depart.descrdepto,t70_descr,t56_histor","t56_histbem"," t56_codbem =$t52_bem "));
  $numrows = $clhistbem->numrows;	   
  for($i=0;$i<$numrows;$i++){
    db_fieldsmemory($result_histbem,$i);
      if($pdf->gety() > $pdf->h - 30 || $troca!=0){
	if($pdf->gety() > $pdf->h - 30){
	  $pdf->addpage("L");
	}
	$pdf->cell(277,$alt,'HISTÓRICO DO BEM',0,1,"L",0);
	$pdf->setfont('arial','b',8);
	$pdf->cell(15,$alt,$RLt56_data,1,0,"C",1);
	$pdf->cell(80,$alt,"Departamento origem",1,0,"C",1);
	$pdf->cell(80,$alt,"Departamento destino",1,0,"C",1);
	$pdf->cell(32,$alt,$RLt70_descr,1,0,"C",1);
	$pdf->cell(70,$alt,$RLt56_histor,1,1,"C",1);
	$troca = 0;
      }
      $pdf->setfont('arial','',6);     
      $pdf->cell(15,$alt,db_formatar($t56_data,"d"),"T",0,"C",0);
      if(isset($i) && $i==0){
	$pdf->cell(80,$alt,$t56_histor,"T",0,"L",0);
      }else{
	$pdf->cell(80,$alt,$depto_origem,"T",0,"L",0);
      }    
      $pdf->cell(80,$alt,$descrdepto,"T",0,"L",0);
      $depto_origem = $descrdepto;
      
      $pdf->cell(32,$alt,$t70_descr,"T",0,"L",0);
      $pdf->multicell(70,$alt,$t56_histor,"T","J",0);
  //    $pdf->cell(35,$alt_histor,"",1,1,"L",1);
//      $pdf->cell(35,$alt_histor,$numrows,0,0,"L",0);
  }
      $pdf->cell(277,0.1,'',"T",0,"L",0);
//$alt = 30;
}
}


$pdf->Output();
?>