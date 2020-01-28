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
include("classes/db_pcfornecon_classe.php");
include("classes/db_pcforne_classe.php");
$clpcfornecon = new cl_pcfornecon;
$clpcforne = new cl_pcforne;

$clpcfornecon->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($bloqueado == "t") {
  $txt_where = "1=1";
}else if ($bloqueado == "s"){
  $txt_where = "pc60_bloqueado='t'";
}else{
  $txt_where = "pc60_bloqueado='f'";
}

if ($ordem=="a"){
  $order_by="z01_nome";
}else $order_by=" pc60_numcgm";

if($forne == "t") {
  $txt_where .= "";
  $info="Todos";
}else if ($forne == "c"){
  $txt_where .= "and pc63_numcgm is not null";
  $info="Com conta";
}else{
  $txt_where .= "and pc63_numcgm is null";
  $info="Sem conta";
}

$head3 = "RELATÓRIO DE FORNECEDORES";
$head5 = "$info";


//echo $clpcforne->sql_query_conta(null,"pc60_numcgm,z01_nome,pc63_banco as banco,pc63_dataconf as conferido,pc63_conta as conta,pc63_conta_dig as condig,pc63_agencia as agencia,pc63_agencia_dig as dig,z01_cgccpf as cc,pc60_bloqueado ",$order_by,"$txt_where");exit;
$result_forne = $clpcforne->sql_record($clpcforne->sql_query_conta(null,"pc60_numcgm,z01_nome,pc63_banco as banco,pc63_dataconf as conferido,pc63_conta as conta,pc63_conta_dig as condig,pc63_agencia as agencia,pc63_agencia_dig as dig,z01_cgccpf as cc,pc60_bloqueado ",$order_by,"$txt_where"));

if ($clpcforne->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 4;
$total = 0;

for($x = 0; $x < $clpcforne->numrows;$x++){
   db_fieldsmemory($result_forne,$x);
   $impagencia = $agencia;
   if(trim($dig)!=""){
     $impagencia .= " / ".$dig;   
   }
   $impconta = $conta;
   if(trim($condig)!=""){
     $impconta .= " / ".$condig;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Cgm",1,0,"C",1);
      $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(10,$alt,$RLpc63_banco,1,0,"C",1);
      $pdf->cell(20,$alt,$RLpc63_agencia,1,0,"C",1);
      $pdf->cell(40,$alt,$RLpc63_conta,1,0,"C",1);
      $pdf->cell(25,$alt,$RLpc63_cnpjcpf,1,0,"C",1); 
      $pdf->cell(15,$alt,$RLpc63_dataconf,1,1,"C",1); 
      $p=0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,@$pc60_numcgm,0,0,"C",$p);
   $pdf->cell(65,$alt,@$z01_nome,0,0,"L",$p);
   $pdf->cell(10,$alt,@$banco,0,0,"C",$p);
   $pdf->cell(20,$alt,@$impagencia,0,0,"L",$p);
   $pdf->cell(40,$alt,@$impconta,0,0,"L",$p);
   $pdf->cell(25,$alt,@$cc,0,0,"C",$p);
   $pdf->cell(15,$alt,db_formatar(@$conferido,"d"),0,1,"C",$p);
   $total++;
   if ($p==0){
   	$p=1;
   }else $p=0;
}

$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>