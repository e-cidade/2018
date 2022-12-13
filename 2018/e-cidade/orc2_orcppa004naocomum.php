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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_orcppa_classe.php");
include("classes/db_orcppaval_classe.php");
include("classes/db_orcppatiporec_classe.php");

$clorcppa    = new cl_orcppa;
$clorcppaval = new cl_orcppaval;
$clorcppatiporec = new cl_orcppatiporec;

$clorcppa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere="";
$and="";

if(isset($orgao) && $orgao !='' && $orgao !=0){
  $dbwhere = " o23_orgao =$orgao ";
$head3 = "Orgao:$orgao";
}


$campos = "o23_codppa,o23_orgao,o40_descr,o23_unidade,o41_descr,o23_funcao,o52_descr,o23_subfuncao,o53_descr,o23_programa,o54_descr,o23_programatxt,o23_acao,o55_descr,o23_acaotxt,
           o23_produto,o22_descrprod,o23_unimed ";
//$sql = $clorcppa->sql_query_compl(null,$campos,"","$dbwhere");
$sql = $clorcppa->sql_query_compl(null,$campos,"o23_orgao,o23_unidade,o23_funcao,o23_subfuncao,
           o23_programa,o23_programatxt,o23_acao,o23_acaotxt,o23_produto","$dbwhere");



$result01  = $clorcppa->sql_record($sql);
$numrows01 = $clorcppa->numrows; 


// db_criatabela($result01);exit;

$alt="4";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "PLURIANUAL";
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',10);
//echo $numrows01;

$pdf->SetWidths(array(140,140));
$pdf->SetAligns(array('J','J'));

$tottlorgao = Array();
$tottoorgao = Array();
$tottquanto = Array();
$intanos    = "";

$orgao_antigo = "";

for($x=0; $x<$numrows01; $x++){
  db_fieldsmemory($result01,$x); 
  if($o23_orgao!=$orgao_antigo){
    if($orgao_antigo!=""){
      db_fieldsmemory($result01,($x-1));
      $pdf->ln();
      $pdf->setfont('arial','b',10);
      $pdf->multicell(280,6,"Totalização por órgão",1,"L",1);
      $pdf->cell(280,7,"$RLo23_orgao:$o23_orgao - $o40_descr",1,1,"L",1);

      $pdf->cell(110,$alt,'',0,0,"R",0);
      $pdf->cell(25,$alt,"Ano",1,0,"C",0);
      $pdf->cell(25,$alt,"Quant.Fisíca",1,0,"C",0);
      $pdf->cell(40,$alt,"Próprios",1,0,"C",0);
      $pdf->cell(40,$alt,"Outros",1,0,"C",0);
      $pdf->cell(40,$alt,"Total",1,1,"C",0);

      $result = $clorcppa->sql_record($clorcppa->sql_query_compl($o23_codppa,"o23_codleippa,o21_anoini,o21_anofim"));
      db_fieldsmemory($result,0);
      $arr_ano_livre = array();
      $arr_ano_outro = array();
      $arr_qua = array();
      for($s=$o21_anoini; $s<= $o21_anofim; $s++){
	 $arr_ano_livre[$s]='0.00';
	 $arr_ano_outro[$s]='0.00';
	 $arr_qua[$s]=0;
      }   
      
      $sql = $clorcppaval->sql_query_dad(null,"o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor",
					    "","o23_orgao=$orgao_antigo group by o24_exercicio,o24_quantmed,o26_codigo");
      $result04  = $clorcppaval->sql_record($sql);  
      $numrows04 = $clorcppaval->numrows; 
      for($t=0; $t<$numrows04; $t++){    
	db_fieldsmemory($result04,$t);
	if($o26_codigo==1){
	  $arr_ano_livre[$o24_exercicio] = $arr_ano_livre[$o24_exercicio] + $o24_valor;
	}else{
	  $arr_ano_outro[$o24_exercicio] = $arr_ano_outro[$o24_exercicio] + $o24_valor;
	}  
	$arr_qua[$o24_exercicio] = $arr_qua[$o24_exercicio] + $o24_quantmed;
      }

      $tot_livre = 0;
      $tot_outro = 0;
      for($s=$o21_anoini; $s<= $o21_anofim; $s++){
	$pdf->cell(110,$alt,'',0,0,"R",0);    
	
	$pdf->cell(25,$alt,$s,1,0,"C",0);
	$pdf->cell(25,$alt,$arr_qua[$s],1,0,"C",0);
	$pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s],'f'),1,0,"R",0);
	$pdf->cell(40,$alt,db_formatar($arr_ano_outro[$s],"f"),1,0,"R",0);
	$pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s]+$arr_ano_outro[$s],'f'),1,1,"R",0);

	$tot_livre +=  $arr_ano_livre[$s];
	$tot_outro +=  $arr_ano_outro[$s];
      }
      $pdf->cell(110,$alt,'',0,0,"R",0);
      $pdf->cell(50,$alt,"Total",1,0,"R",0);
      $pdf->cell(40,$alt,db_formatar($tot_livre,'f'),1,0,"R",0);
      $pdf->cell(40,$alt,db_formatar($tot_outro,'f'),1,0,"R",0);
      $pdf->cell(40,$alt,db_formatar($tot_livre+$tot_outro,'f'),1,1,"R",0);    
      db_fieldsmemory($result01,$x); 
      $pdf->AddPage("L");
    }
    $orgao_antigo = $o23_orgao;
  }

  $pdf->ln();
  $pdf->setfont('arial','b',10);
  $pdf->cell(280,7,"$RLo23_orgao:$o23_orgao - $o40_descr  $RLo23_unidade:$o23_unidade - $o41_descr ",1,1,"L",1);
  $pdf->cell(280,7,"$RLo23_funcao:$o23_funcao - $o52_descr  $RLo23_subfuncao:$o23_subfuncao - $o53_descr  ",1,1,"L",1);
  $pdf->cell(280,7,"$RLo23_programa:$o23_programa - $o54_descr ",1,1,"L",1);
  
  $pdf->setfont('arial','',10);
  $pdf->multicell(280,$alt,$o23_programatxt,1,"L",1);
  $pdf->ln(6);

  $pdf->multicell(280,6,"Ação: $o23_acao-$o55_descr  Descr: $o23_acaotxt   ",1,"L",1);


  $pdf->setfont('arial','b',9);
  $pdf->cell(110,6,"$RLo23_produto: $o22_descrprod  $RLo23_unidade: $o23_unidade ",1,0,"L",0);
  $pdf->cell(50,6,"Meta",1,0,"C",0);
  $pdf->cell(120,6,"Custos Previsto p/ o exercício",1,1,"C",0);
  
  $pdf->cell(110,$alt,'',0,0,"R",0);
  $pdf->cell(25,$alt,"Ano",1,0,"C",0);
  $pdf->cell(25,$alt,"Quant.Fisíca",1,0,"C",0);
  $pdf->cell(40,$alt,"Próprios",1,0,"C",0);
  $pdf->cell(40,$alt,"Outros",1,0,"C",0);
  $pdf->cell(40,$alt,"Total",1,1,"C",0);



  $result = $clorcppa->sql_record($clorcppa->sql_query_compl($o23_codppa,"o23_codleippa,o21_anoini,o21_anofim"));
  db_fieldsmemory($result,0);
  $arr_ano_livre = array();
  $arr_ano_outro = array();
  $arr_qua = array();  
  $intanos    = $o21_anoini."-".$o21_anofim;
  for($s=$o21_anoini; $s<= $o21_anofim; $s++){
     $arr_ano_livre[$s]='0.00';
     $arr_ano_outro[$s]='0.00';
     $arr_qua[$s]=0;
  }   
  
  $sql = $clorcppaval->sql_query_dad(null,"o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor",
					"","o23_codppa=$o23_codppa group by o24_exercicio,o24_quantmed,o26_codigo");
  $result04  = $clorcppaval->sql_record($sql);  
  $numrows04 = $clorcppaval->numrows; 
  for($t=0; $t<$numrows04; $t++){
    db_fieldsmemory($result04,$t);
    if($o26_codigo==1){
      $arr_ano_livre[$o24_exercicio] = $arr_ano_livre[$o24_exercicio] + $o24_valor;
    }else{
      $arr_ano_outro[$o24_exercicio] = $arr_ano_outro[$o24_exercicio] + $o24_valor;
    }  
    $arr_qua[$o24_exercicio] = $arr_qua[$o24_exercicio] + $o24_quantmed;
  }

  $tot_livre = 0;
  $tot_outro = 0;
  for($s=$o21_anoini; $s<= $o21_anofim; $s++){
    $pdf->cell(110,$alt,'',0,0,"R",0);    
    
    $pdf->cell(25,$alt,$s,1,0,"C",0);
    $pdf->cell(25,$alt,$arr_qua[$s],1,0,"C",0);
    $pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s],'f'),1,0,"R",0);
    $pdf->cell(40,$alt,db_formatar($arr_ano_outro[$s],"f"),1,0,"R",0);
    $pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s]+$arr_ano_outro[$s],'f'),1,1,"R",0);
    

    if(isset($tottlorgao[$s])){
      $tottlorgao[$s] += $arr_ano_livre[$s];
      $tottoorgao[$s] += $arr_ano_outro[$s];
      $tottquanto[$s] += $arr_qua[$s];
    }else{
      $tottlorgao[$s] = $arr_ano_livre[$s];
      $tottoorgao[$s] = $arr_ano_outro[$s];
      $tottquanto[$s] = $arr_qua[$s];
    }

    $tot_livre +=  $arr_ano_livre[$s];
    $tot_outro +=  $arr_ano_outro[$s];
  }
  $pdf->cell(110,$alt,'',0,0,"R",0);
  $pdf->cell(50,$alt,"Total",1,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_livre,'f'),1,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_outro,'f'),1,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_livre+$tot_outro,'f'),1,1,"R",0);    
  
  $pdf->setfont('arial','',8);
  $pdf->ln();
}

if(isset($orgao_antigo)){
  if($orgao_antigo!=""){
    $pdf->ln(6);
    $pdf->setfont('arial','b',10);
    $pdf->multicell(280,7,"Totalização por órgão",1,"L",1);
    $pdf->cell(280,7,"$RLo23_orgao:$o23_orgao - $o40_descr",1,1,"L",1);

    $pdf->cell(110,$alt,'',0,0,"R",0);
    $pdf->cell(25,$alt,"Ano",1,0,"C",0);
    $pdf->cell(25,$alt,"Quant.Fisíca",1,0,"C",0);
    $pdf->cell(40,$alt,"Próprios",1,0,"C",0);
    $pdf->cell(40,$alt,"Outros",1,0,"C",0);
    $pdf->cell(40,$alt,"Total",1,1,"C",0);

    $result = $clorcppa->sql_record($clorcppa->sql_query_compl($o23_codppa,"o23_codleippa,o21_anoini,o21_anofim"));
    db_fieldsmemory($result,0);
    $arr_ano_livre = array();
    $arr_ano_outro = array();
    $arr_qua = array();
    for($s=$o21_anoini; $s<= $o21_anofim; $s++){
       $arr_ano_livre[$s]='0.00';
       $arr_ano_outro[$s]='0.00';
       $arr_qua[$s]=0;
    }   
    
    $sql = $clorcppaval->sql_query_dad(null,"o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor",
					  "","o23_orgao=$orgao_antigo group by o24_exercicio,o24_quantmed,o26_codigo");
    $result04  = $clorcppaval->sql_record($sql);  
    $numrows04 = $clorcppaval->numrows; 
    for($t=0; $t<$numrows04; $t++){    
      db_fieldsmemory($result04,$t);
      if($o26_codigo==1){
	$arr_ano_livre[$o24_exercicio] = $arr_ano_livre[$o24_exercicio] + $o24_valor;
      }else{
	$arr_ano_outro[$o24_exercicio] = $arr_ano_outro[$o24_exercicio] + $o24_valor;
      }  
      $arr_qua[$o24_exercicio] = $arr_qua[$o24_exercicio] + $o24_quantmed;
    }

    $tot_livre = 0;
    $tot_outro = 0;
    for($s=$o21_anoini; $s<= $o21_anofim; $s++){
      $pdf->cell(110,$alt,'',0,0,"R",0);    
      
      $pdf->cell(25,$alt,$s,1,0,"C",0);
      $pdf->cell(25,$alt,$arr_qua[$s],1,0,"C",0);
      $pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s],'f'),1,0,"R",0);
      $pdf->cell(40,$alt,db_formatar($arr_ano_outro[$s],"f"),1,0,"R",0);
      $pdf->cell(40,$alt,db_formatar($arr_ano_livre[$s]+$arr_ano_outro[$s],'f'),1,1,"R",0);

      $tot_livre +=  $arr_ano_livre[$s];
      $tot_outro +=  $arr_ano_outro[$s];
    }
    $pdf->cell(110,$alt,'',0,0,"R",0);
    $pdf->cell(50,$alt,"Total",1,0,"R",0);
    $pdf->cell(40,$alt,db_formatar($tot_livre,'f'),1,0,"R",0);
    $pdf->cell(40,$alt,db_formatar($tot_outro,'f'),1,0,"R",0);
    $pdf->cell(40,$alt,db_formatar($tot_livre+$tot_outro,'f'),1,1,"R",0);    
  }
}

$pdf->ln(6);
$pdf->setfont('arial','b',10);
$pdf->multicell(280,7,"Totalização geral (TODOS OS ÓRGÃOS)",1,"L",1);

$pdf->cell(110,$alt,'',0,0,"R",0);
$pdf->cell(25,$alt,"Ano",1,0,"C",0);
$pdf->cell(25,$alt,"Quant.Fisíca",1,0,"C",0);
$pdf->cell(40,$alt,"Próprios",1,0,"C",0);
$pdf->cell(40,$alt,"Outros",1,0,"C",0);
$pdf->cell(40,$alt,"Total",1,1,"C",0);

$setano = split("-",$intanos);
$anoseti = $setano[0];
$anosetf = $setano[1];
$tot_livre = 0;
$tot_outro = 0;
for($s=$anoseti; $s<= $anosetf; $s++){
  $pdf->cell(110,$alt,'',0,0,"R",0);    
  $pdf->cell(25,$alt,$s,1,0,"C",0);
  $pdf->cell(25,$alt,$tottquanto[$s],1,0,"C",0);
  $pdf->cell(40,$alt,db_formatar($tottlorgao[$s],'f'),1,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tottoorgao[$s],"f"),1,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tottlorgao[$s]+$tottoorgao[$s],'f'),1,1,"R",0);

  $tot_livre +=  $tottlorgao[$s];
  $tot_outro +=  $tottoorgao[$s];
}
$pdf->cell(110,$alt,'',0,0,"R",0);
$pdf->cell(50,$alt,"Total",1,0,"R",0);
$pdf->cell(40,$alt,db_formatar($tot_livre,'f'),1,0,"R",0);
$pdf->cell(40,$alt,db_formatar($tot_outro,'f'),1,0,"R",0);
$pdf->cell(40,$alt,db_formatar($tot_livre+$tot_outro,'f'),1,1,"R",0);    

$pdf->Output();
?>