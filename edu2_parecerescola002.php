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
include("classes/db_parecerturma_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_escola_classe.php");
$clescola = new cl_escola;
$clparecerturma = new cl_parecerturma;
$clturma = new cl_turma;
$escola = db_getsession("DB_coddepto");
$result1 = $clturma->sql_record($clturma->sql_query("","*","ed57_c_descr","ed57_i_codigo in ($turmas)"));
if($clturma->numrows==0){?>
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
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
/////////////////////////////////////////////
$pdf->setfont('arial','b',7);
$pdf->cell(190,4,"DADOS PARECERES ESCOLA",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(5,24,"","L",0,"C",0);
$pdf->cell(35,4,"Código:","R",0,"L",0);
$pdf->cell(155,4,"Descrição:","R",1,"L",0);
$pdf->setfont('arial','b',7);
for($x=0;$x<$clturma->numrows;$x++){
 db_fieldsmemory($result1,$x);
 $head1 = "RELATÓRIO DE PARECERES POR TURMA";
 $head2 = "Escola:  $ed18_i_codigo - $ed18_c_nome";
 $head3 = "Turma: $ed57_c_descr";
 $pdf->addpage('P');
 $pdf->ln(5);
 $pdf->setfillcolor(223);
 $pdf->setfont('arial','b',7);
 $pdf->cell(20,4,"Sequencial",1,0,"C",1);
 $pdf->cell(170,4,"Descrição",1,1,"L",1);
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $core1 = "0";
 $core2 = "1";
 $core = "";
 $result2 = $clparecerturma->sql_record($clparecerturma->sql_query("","*","ed92_i_sequencial","ed105_i_turma = $ed57_i_codigo"));
 for($y=0;$y<$clparecerturma->numrows;$y++){
  db_fieldsmemory($result2,$y);
  if($core==$core1){
   $core = $core2;
  }else{
   $core = $core1;
  }
  $pdf->cell(20,4,$ed92_i_sequencial,"L",0,"C",$core);
  $pdf->cell(170,4,$ed92_c_descr,"R",1,"L",$core);
 }
 $pdf->cell(190,0,"","LRB",1,"C",0);
}
$pdf->Output();
?>