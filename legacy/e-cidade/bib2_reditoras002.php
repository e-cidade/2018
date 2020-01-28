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
include("classes/db_editora_classe.php");
$cleditora = new cl_editora;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if($ordem == "a") {
 $desc_ordem = "ALFABÉTICA";
 $order_by = "bi02_nome";
}else{
 $desc_ordem = "NUMÉRICA";
 $order_by = "bi02_codigo";
}
$result = $cleditora->sql_record($cleditora->sql_query("","*",$order_by,""));
if($cleditora->numrows==0){
 db_redireciona('db_erros.php?fechar=true&db_erro=Não existe editora cadastrada.');
}
$head1 = "RELATÓRIO DE EDITORAS";
$head2 = "Ordem: $desc_ordem";
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$troca = 1;
$alt = 4;
$total = 0;
$cor1 = "0";
$cor2 = "1";
$cor = "";
for($x=0;$x<$cleditora->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfillcolor(215);
 if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(5,$alt,"",1,0,"C",1);
  $pdf->cell(20,$alt,"Código",1,0,"C",1);
  $pdf->cell(165,$alt,"Nome",1,1,"L",1);
  $troca = 0;
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $pdf->cell(5,$alt,$x+1,0,0,"C",$cor);
 $pdf->cell(20,$alt,$bi02_codigo,0,0,"C",$cor);
 $pdf->cell(165,$alt,$bi02_nome,0,1,"L",$cor);
 $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE EDITORAS: '.$total,"T",0,"L",0);
$pdf->Output();
?>