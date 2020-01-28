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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_utils.php"));

$classinatura = new cl_assinatura;

// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;

$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ;
  $xvirg = ', ';
}


$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
    $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}

$head3 = "RESUMO DA RECEITA ";
$head4 = "ANEXO (2) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

//$sql = "select * from work order by elemento";
//$result = db_query($sql);
$dataini = $perini;
$datafin = $perfin;

$where = " o70_instit in (".str_replace('-',', ',$db_selinstit).") ";

$result = db_receitasaldo(11,1,3,true,$where,db_getsession("DB_anousu"),$dataini,$datafin);

//db_criatabela($result);exit;
//$xresumo = array;

$pagina = 1;
$xx=1;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;
  
  if($origem == "O") {
    $valor = $saldo_inicial;
  } else {
    $valor = $saldo_arrecadado;
  }
	
  $sSqlConplanoGrupo = "select fc_conplano_grupo(".db_getsession("DB_anousu").",'".$o57_fonte."',9004) as lconplanogrupo ";
  $rsConplanoGrupo   = db_query($sSqlConplanoGrupo);
  $oConplanoGrupo    = db_utils::fieldsMemory($rsConplanoGrupo,0);
  if($oConplanoGrupo->lconplanogrupo == 't') {
    continue;
  }
  if($valor==0){
    continue;
  }
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->cell(118,$alt,"ALÍNEAS",0,0,"R",0);
    $pdf->cell(60,$alt,"CATEGORIA",0,1,"R",0);
    $pdf->cell(25,$alt,"CÓDIGO",0,0,"L",0);
    $pdf->cell(75,$alt,"ESPECIFICAÇÃO",0,0,"L",0);
    $pdf->cell(20,$alt,"SUBALÍNEAS",0,0,"R",0);
    $pdf->cell(20,$alt,"RUBRICAS",0,0,"R",0);
    $pdf->cell(20,$alt,"FONTES",0,0,"R",0);
    $pdf->cell(20,$alt,"ECONÔMICA",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->ln(3);
  
  }

  $pdf->setfont('arial','',6);
  $pdf->cell(25,$alt,db_formatar($elemento,'receita'),0,0,"L",0);
  if(substr($elemento,2,3) == "000"){
    $xx = 1;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+11,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
  }elseif(substr($elemento,3,2) == "00"){
    $xx = 3;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+9,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
  }elseif(substr($elemento,4,2) == "00"){
    $xx = 5;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+7,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
  }elseif(substr($elemento,6,2) == "00"){
    $xx = 7;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+5,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
  }elseif(substr($elemento,8,2) == "00"){
    $xx = 9;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+3,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
   }else{
    $xx = 11;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(61+1,$alt,$descr,0,0,"L",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
  }
}

// resumo
$pagina = 1;
$valorm = 0;
$descrm = "";

//db_criatabela($result);exit;


$pagina = 0;
$pdf->addpage();
$pdf->setfont('arial','b',8);
    
$pdf->cell(45,$alt,'',0,0,"L",0);
$pdf->cell(40,$alt,"Resumo",0,1,"L",0);

$rec_cor = 0;
$rec_cap = 0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;

  if($origem == "O")
     $valor = $saldo_inicial;
  else
     $valor = $saldo_arrecadado;

  if(substr($o57_fonte,3,2) == "00" && substr($o57_fonte,2,1) != "0" && substr($o57_fonte,5,8) == "0000000" && $valor != 0 && substr($o57_fonte,1,1) != "2"){
    $xx = 3;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(55+9,$alt,'           '.$o57_descr,0,0,"L",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
    $rec_cor += $valor; 
  }
}

$pdf->cell($xx,$alt,"",0,0,"R",0);
$pdf->cell(55+9,$alt,'Total das Receitas Correntes : ',0,0,"L",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cor,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,"",0,1,"R",0);
$pdf->ln(3);

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;

  if($origem == "O")
     $valor = $saldo_inicial;
  else
     $valor = $saldo_arrecadado;
  
  if(substr($o57_fonte,3,10) == "0000000000" 
      && substr($o57_fonte,2,1) != "0"
      && $valor != 0 
      && substr($o57_fonte,1,1) == "2" ){
    $xx = 3;
    $pdf->cell($xx,$alt,"",0,0,"R",0);
    $pdf->cell(55+9,$alt,'           '.$o57_descr,0,0,"L",0);
    $pdf->cell(20,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,"",0,1,"R",0);
    $rec_cap += $valor; 
  }
}
$pdf->ln(3);
$pdf->cell($xx,$alt,"",0,0,"R",0);
$pdf->cell(55+9,$alt,'Total das Receitas de Capital : ',0,0,"L",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cap,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,"",0,1,"R",0);

$pdf->ln(3);
$pdf->cell($xx,$alt,"",0,0,"R",0);
$pdf->cell(55+9,$alt,'Total das Geral : ',0,0,"L",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->cell(20,$alt,"",0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cap + $rec_cor,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,"",0,1,"R",0);

$pdf->ln(14);

if($origem != "O"){
  assinaturas($pdf, $classinatura,'BG');
}

$pdf->Output();
