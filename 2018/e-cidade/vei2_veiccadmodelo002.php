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
include("classes/db_veiccadmodelo_classe.php");
$clveiccadmodelo = new cl_veiccadmodelo;
$clveiccadmodelo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if($ordem == "a") {
	$desc_ordem = "Alfabética";
	$order_by = "ve22_descr";
}else {
	$desc_ordem = "Numérica";
	$order_by = "ve22_codigo";
}
$head3 = "CADASTRO DE MODELOS ";
$head5 = "ORDEM $desc_ordem";
$result = $clveiccadmodelo->sql_record($clveiccadmodelo->sql_query(null,"*",$order_by));
if ($clveiccadmodelo->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem modelos cadastradas.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;
for($x = 0; $x < $clveiccadmodelo->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,$alt,$RLve22_codigo,1,0,"C",1);
      $pdf->cell(0,$alt,$RLve22_descr,1,1,"L",1); 
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(40,$alt,$ve22_codigo,0,0,"C",$p);
   $pdf->cell(0,$alt,$ve22_descr,0,1,"L",$p);
   if ($p==0){
   	$p=1;
   }else{
   	$p=0;
   }   
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"R",0);
$pdf->Output();
?>