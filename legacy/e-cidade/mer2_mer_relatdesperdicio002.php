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
include("classes/db_mer_desper_und_classe.php");
include("classes/db_mer_desperdicio_classe.php");
$clmer_desper_und  = new cl_mer_desper_und;
$clmer_desperdicio = new cl_mer_desperdicio;
$escola            = db_getsession("DB_coddepto");
$sCampos           = "me22_i_codigo, me01_c_nome,me01_f_versao,me12_d_data,me03_c_tipo";
$sWhere            = " me32_i_escola = $escola  AND me12_d_data BETWEEN '$inicio' AND '$fim' ";
$result            = $clmer_desperdicio->sql_record(
                                                     $clmer_desperdicio->sql_query("",
                                                                                   $sCampos,
                                                                                   "me12_d_data desc,me03_i_orden",
                                                                                    $sWhere
                                                                      )
                                                    );
                                                
if ($clmer_desperdicio->numrows==0) {?>

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
$head2 = "Período: ".$dat." à ".$dat2;
$pdf->ln(5);
$troca = 1;
$total = 0;
for ($c=0;$c<$clmer_desperdicio->numrows;$c++) {
	
  db_fieldsmemory($result,$c); 
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
  	
    $pdf->addpage("L");
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',8);
    $pdf->cell(10,4,"Cod.",1,0,"C",1);
    $pdf->cell(50,4,"Nome do Cardapio",1,0,"C",1);
    $pdf->cell(15,4,"Versão",1,0,"C",1);
    $pdf->cell(20,4,"Data",1,0,"C",1);
    $pdf->cell(35,4,"Tipo de Refeição",1,0,"C",1);
    $pdf->cell(10,4,"Qtde.",1,0,"C",1);
    $pdf->cell(30,4,"Unidade",1,0,"C",1);
    $pdf->cell(110,4,"Observação",1,1,"C",1);
    $troca=0;
    
  }
  $pdf->setfillcolor(255);
  $pdf->setfont('arial','',8);
  $pdf->cell(10,4,$me22_i_codigo,1,0,"C",1);
  $pdf->cell(50,4,$me01_c_nome,1,0,"C",1);
  $pdf->cell(15,4,"V:".$me01_f_versao,1,0,"C",1);
  $pdf->cell(20,4,db_formatar($me12_d_data,'d'),1,0,"C",1);
  $pdf->cell(35,4,$me03_c_tipo,1,0,"C",1);
  $result2 = $clmer_desper_und->sql_record($clmer_desper_und->sql_query("",
                                                                        "me23_f_quant,me23_t_obs,m61_descr",
                                                                        "",
                                                                        " me23_i_desperdicio = $me22_i_codigo"
                                                                       ));
  for ($x=0;$x<$clmer_desper_und->numrows;$x++) {
  	
    db_fieldsmemory($result2,$x);
    if ($x>0) {
    	
      $pdf->setfillcolor(235);
      $pdf->cell(130,4,"",1,0,"C",1);
      
    }
    $pdf->setfillcolor(255);
    $pdf->cell(10,4,"$me23_f_quant",1,0,"C",1);
    $pdf->cell(30,4,"$m61_descr",1,0,"C",1);
    $pdf->multicell(110,4,$me23_t_obs,1,"J",0,0);
    
  }
 $total = $total+$me23_f_quant;
 
}
$pdf->Output();
?>