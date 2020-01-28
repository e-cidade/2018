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

include("fpdf151/pdfwebseller.php");
$sql    = "select me22_i_codigo,me22_i_cardapio,me01_c_nome,me01_f_versao,me22_d_data,me22_t_obs from mer_desperdicio "; 
$sql   .= "       inner join mer_cardapio on me22_i_cardapio=me01_i_codigo ";
$sql   .= "       where me22_d_data between '".$inicio."' and '".$fim."'";
$result = pg_query($sql);
$linhas = pg_num_rows($result);
if ($linhas == 0) {?>

 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
 
}
$pdf   = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO DE DESPERDÍCIO";
$dat   = substr($inicio,8,2)."/".substr($inicio,5,2)."/".substr($inicio,0,4);
$dat2  = substr($fim,8,2)."/".substr($fim,5,2)."/".substr($fim,0,4);
$head2 = "Priodo: ".$dat." à ".$dat2;
$pdf->ln(5);
$troca = 1;
$total = 0;
for ($c=0; $c<$linhas; $c++) {
	
  db_fieldsmemory($result,$c); 
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
  	
    $pdf->addpage();
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',8);
    $pdf->cell(10,4,"cod",1,0,"C",1);
    $pdf->cell(50,4,"Nome do Cardapio",1,0,"C",1);
    $pdf->cell(15,4,"Versão",1,0,"C",1);
    $pdf->cell(25,4,"Data",1,0,"C",1);
    $pdf->cell(90,4,"Observação",1,1,"C",1);
    $pdf->cell(50,4,"Quantidade",1,0,"C",1);
    $pdf->cell(50,4,"Unidade",1,0,"C",1);
    $pdf->cell(90,4,"Observação",1,1,"C",1);     
    $troca=0;
    
  } 
  $pdf->setfillcolor(255);
  $pdf->setfont('arial','',8);
  $pdf->cell(10,4,$me22_i_cardapio,1,0,"C",1);
  $pdf->cell(50,4,$me01_c_nome,1,0,"C",1);
  $pdf->cell(15,4,"V:".$me01_f_versao,1,0,"C",1);
  $dat=substr($me22_d_data,8,2)."/".substr($me22_d_data,5,2)."/".substr($me22_d_data,0,4);
  $pdf->cell(25,4,$dat,1,0,"C",1);
  $pdf->cell(90,4,$me22_t_obs,1,1,"C",1);
  $sql2    = " select me23_f_quant,me23_t_obs,m61_descr from mer_desper_und ";
  $sql2   .= "       inner join matunid on me23_i_unidade=m61_codmatunid where me23_i_desperdicio=$me22_i_codigo";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  for ($x=0; $x<$linhas2; $x++) {
  	
   	db_fieldsmemory($result2,$x);
   	$pdf->cell(50,4,"$me23_f_quant",1,0,"C",1);
    $pdf->cell(50,4,"$m61_descr",1,0,"C",1);
    $pdf->cell(90,4,"$me23_t_obs",1,1,"C",1);
    
  }
  $total = $total+$quant;
}
$pdf->Output();
?>