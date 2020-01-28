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
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("classes/db_db_usuarios_classe.php");

$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$cldb_usuarios = new cl_db_usuarios;

$clrotulo = new rotulocampo;
$clrotulo->label('pc01_codmater');
$clrotulo->label('pc01_descrmater');
$clrotulo->label('pc04_codsubgrupo');
$clrotulo->label('pc04_descrsubgrupo');
$clrotulo->label('o56_elemento');
$clrotulo->label('o56_codele');
$clrotulo->label('o56_descr');
$clrotulo->label('pc01_complmater');
$clrotulo->label('pc01_id_usuario');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($grupo == "geral") {
  if($ordem == "a") {
    $desc_ordem = "Alfabética";
    $order_by = "pc01_descrmater,pc01_codmater";
  }else {
	  $desc_ordem = "Numérica";
	  $order_by = "pc01_codmater";
  }
$desc_grupo = "Geral";
}elseif($grupo == "sub_grupo") {
  if($ordem == "a") {
    $desc_ordem = "Alfabética";
    $order_by = "pc04_descrsubgrupo,pc01_descrmater";
  }else {
	  $desc_ordem = "Numérica";
	  $order_by = "pc04_codsubgrupo,pc01_codmater";
  }
$desc_grupo = "Por Sub-Grupo";
}elseif($grupo == "elemento") {
  if($ordem == "a") {
    $desc_ordem = "Alfabética";
    $order_by = "o56_descr,pc01_descrmater";
  }else {
	  $desc_ordem = "Numérica";
	  $order_by = "o56_codele,pc01_codmater";
  } 
$desc_grupo = "Por Elemento";  
}
//echo $grupo;echo "<br>";echo $desc_grupo;echo "<br>";echo $desc_ordem;exit;

$head1 = "RELATÓRIO DE MATERIAIS/SERVIÇOS";
$head3 = "CLASSIFICAÇÃO $desc_grupo";
$head5 = "ORDEM $desc_ordem";

$ativo="pc01_ativo='f' and pc01_conversao is false and o56_anousu = ".db_getsession("DB_anousu");

if (isset($elemento) && $elemento != ""){
     $ativo .= " and o56_elemento = '$elemento' ";
}

$result = $clpcmater->sql_record($clpcmater->sql_query_grupo("","*",$order_by,$ativo));
//die($clpcmater->sql_query_grupo("","*",$order_by,$ativo));
//db_criatabela($result);exit;

if ($clpcmater->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem materiais/serviços cadastrados.');

}

//db_criatabela($result); exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$totg = 0;
$quebra_cod = 0;

$cor = 1;

$codmater = "";
$complmat = "";

if($grupo == "geral") {
for($x = 0; $x < $clpcmater->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(13,$alt,$RLpc01_codmater,1,0,"C",1);
      $pdf->cell(55,$alt,$RLpc01_descrmater,1,0,"C",1);
      $pdf->cell(16,$alt,$RLpc04_codsubgrupo,1,0,"C",1);
      $pdf->cell(55,$alt,$RLpc04_descrsubgrupo,1,0,"C",1);
      $pdf->cell(19,$alt,$RLo56_elemento,1,0,"C",1);
      $pdf->cell(60,$alt,$RLo56_descr,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc01_id_usuario,1,1,"C",1);
      $pdf->cell(278,$alt,$RLpc01_complmater,1,1,"C",1);
      $troca = 0;
   }
   $result_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($pc01_id_usuario,"nome"));
   if($cldb_usuarios->numrows > 0){
     db_fieldsmemory($result_usuario,0);
   }
   
   $pdf->setfont('arial','',7);
   if($codmater != $pc01_codmater){
	   if($codmater != "" && $complmat != ""){
	   	 $pdf->cell(278,$alt,$complmat,0,1,"L",$cor);
	   }
	   if($cor == 1){
	   	$cor = 0;
	   }else{
	   	$cor = 1;
	   }
	   $pdf->cell(13,$alt,$pc01_codmater,0,0,"C",$cor);
	   $pdf->cell(55,$alt,substr($pc01_descrmater,0,40),0,0,"L",$cor);
	   $pdf->cell(16,$alt,$pc04_codsubgrupo,0,0,"C",$cor);
	   $pdf->cell(55,$alt,substr($pc04_descrsubgrupo,0,34),0,0,"L",$cor);
   }else{
	   $pdf->cell(13,$alt,"",0,0,"C",$cor);
	   $pdf->cell(55,$alt,"",0,0,"L",$cor);
	   $pdf->cell(16,$alt,"",0,0,"C",$cor);
	   $pdf->cell(55,$alt,"",0,0,"L",$cor);
   }
   $pdf->cell(19,$alt,$o56_elemento,0,0,"C",$cor);
   $pdf->cell(60,$alt,substr($o56_descr,0,40),0,0,"L",$cor);

   if($codmater != $pc01_codmater){
	   $pdf->cell(60,$alt,substr($nome,0,40),0,1,"L",$cor);
   }else{
	   $pdf->cell(60,$alt,"",0,1,"L",$cor);
   }

   $total++;
   $codmater = $pc01_codmater;
   $complmat = $pc01_complmater;
}
if($pc01_complmater != ""){
  $pdf->cell(278,$alt,$pc01_complmater,0,1,"L",$cor);
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
}else {
for($x = 0; $x < $clpcmater->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(17,$alt,$RLpc01_codmater,1,0,"C",1);
      $pdf->cell(70,$alt,$RLpc01_descrmater,1,0,"C",1);
      if($grupo != "sub_grupo") {
      $pdf->cell(17,$alt,$RLpc04_codsubgrupo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc04_descrsubgrupo,1,1,"C",1);
      }
      if($grupo != "elemento") {
      $pdf->cell(17,$alt,$RLo56_codele,1,0,"C",1);
      $pdf->cell(80,$alt,$RLo56_descr,1,1,"C",1);
      }
      $troca = 0;
   }
   if($quebra_cod != $pc04_codsubgrupo && $grupo == "sub_grupo") {
     if($totg > 0) {
     $pdf->setfont('arial','b',7);
     $pdf->cell(164,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);
     }
     $pdf->setfont('arial','b',8);
     $pdf->ln(3);
     $txt = "$pc03_descrgrupo => $pc04_descrsubgrupo";
     $pdf->cell(17,$alt,$pc04_codsubgrupo,0,0,"C",0);
     $pdf->cell(70,$alt,$txt,0,1,"L",0);
     $quebra_cod = $pc04_codsubgrupo;
     $totg = 0;
   }
   if($quebra_cod != $o56_codele && $grupo == "elemento") {
     if($totg > 0) {
     $pdf->setfont('arial','b',7);
     $pdf->cell(164,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);
     }
     $pdf->setfont('arial','b',8);
     $pdf->ln(3);
     $pdf->cell(17,$alt,$o56_codele,0,0,"C",0);
     $pdf->cell(80,$alt,$o56_elemento.' - '.$o56_descr,0,1,"L",0);
     $quebra_cod = $o56_codele;
     $totg = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(17,$alt,$pc01_codmater,0,0,"C",0);
   $pdf->cell(70,$alt,$pc01_descrmater,0,0,"L",0);
   if($grupo != "sub_grupo") {
     $pdf->cell(17,$alt,$pc04_codsubgrupo,0,0,"C",0);
     $pdf->cell(60,$alt,$pc04_descrsubgrupo,0,1,"L",0);
   }
   if($grupo != "elemento") {
     $pdf->cell(17,$alt,$o56_elemento,0,0,"C",0);
     $pdf->cell(70,$alt,$o56_descr,0,1,"L",0);
   }
   $total++;
   $totg++;
}

$pdf->setfont('arial','b',7);
$pdf->cell(164,$alt,'TOTAL  :  '.$totg,"B",1,"L",0);

$pdf->setfont('arial','b',8);
$pdf->cell(164,$alt,'TOTAL GERAL  :  '.$total,"T",0,"L",0);
}

$pdf->Output();
   
?>