<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_lab_entrega_classe.php");
$cllab_entrega = new cl_lab_entrega;

if(isset($dataini) && $datafim!=""){
  @$d1= substr(@$dataini,6,4)."-".substr(@$dataini,3,2)."-".substr(@$dataini,0,2);
  @$d2= substr(@$datafim,6,4)."-".substr(@$datafim,3,2)."-".substr(@$datafim,0,2);
  $where = " la22_d_data between '$d1' and '$d2'";
  
}
if(isset($laboratorio) && $laboratorio!=""){
  $where .= " and la02_i_codigo=$laboratorio";
  
}
if(isset($labsetor) && $labsetor!=""){
  $where .= " and la24_i_codigo=$labsetor";
  
}
if(isset($exame) && $exame!=""){
  $where .= " and la08_i_codigo=$exame";
  
}
if(isset($requisicao) && $requisicao!=""){
  $where .= " and la22_i_codigo=$requisicao";
}

//die($cllab_entrega->sql_query("","*","",$where));
$result = $cllab_entrega->sql_record($cllab_entrega->sql_query_consulta("","*","",$where));

if($cllab_entrega->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado<br>
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
$head1 = "RELATÓRIO DE ENTREGA DE EXAMES";
if($cllab_entrega->numrows>0){
  db_fieldsmemory($result,0);  
  $head2 = "LABORATÓRIO:  $la02_c_descr";
  $head3 = "SETOR: $la23_c_descr";
  $head4 = "EXAME: $la08_c_descr";
}else{
  $head2 = "LABORATÓRIO:  ";
  $head3 = "SETOR: ";
  $head4 = "EXAME: ";
}
$pdf->ln(5);
$pdf->addpage('L');
$total=0;
$cont=0;
$d=0;
 $pdf->setfont('arial','b',10);
 $pdf->setfillcolor(240);
 $pdf->cell(20,4,"Requisição ",0,0,"L",1);
 $pdf->cell(20,4,"Data ",0,0,"L",1);
 $pdf->cell(60,4,"Paciente ",0,0,"L",1);
 $pdf->cell(60,4,"Retirado Por ",0,0,"L",1);
 $pdf->cell(60,4,"Exame ",0,0,"L",1);
 $pdf->cell(60,4,"Material ",0,1,"L",1);
 

 $pdf->cell(15,5,"",0,0,"L",0);
 $pdf->cell(45,5,"Tipo Doc. ",0,0,"L",0);
 $pdf->cell(40,5,"Documento ",0,0,"L",0);
 $pdf->cell(35,5,"Coleta ",0,0,"L",0);
 $pdf->cell(30,5,"Entrega ",0,0,"L",0);
 $pdf->cell(30,5,"Hora ",0,0,"L",0);
 $pdf->cell(40,5,"Login ",0,1,"L",0);
      
   for($s=0; $s < $cllab_entrega->numrows; $s++){
   	 db_fieldsmemory($result,$s);
	  if($cont==12){
	   $pdf->ln(5);
       $pdf->addpage('L');
	     $pdf->setfont('arial','b',10);
       $pdf->cell(20,4,"Requisição ",0,0,"L",0);
       $pdf->cell(20,4,"Data ",0,0,"L",0);
       $pdf->cell(40,4,"Retirado Por ",0,0,"L",0);
       $pdf->cell(40,4,"Exame ",0,0,"L",0);
       $pdf->cell(40,4,"Material ",0,1,"L",0);
       $pdf->cell(25,5,"",0,0,"L",0);
       $pdf->cell(25,5,"",0,0,"L",0);
       $pdf->cell(25,5,"",0,0,"L",0);
       $pdf->cell(25,5,"Tipo Doc. ",0,0,"L",0);
       $pdf->cell(40,5,"Documento ",0,0,"L",0);
       $pdf->cell(35,5,"Coleta ",0,0,"L",0);
       $pdf->cell(30,5,"Entrega ",0,0,"L",0);
       $pdf->cell(30,5,"Hora ",0,0,"L",0);
       $pdf->cell(40,5,"Login ",0,1,"L",0);
	   $cont=0;
	   $pdf->setfont('arial','',8);
	  } 
	 if($d != $la22_i_codigo){ 	
      $pdf->setfont('arial','',8);
	  $pdf->setfillcolor(240);
      $pdf->cell(20,4,$la22_i_codigo,0,0,"L",1);//requisicao
      $pdf->cell(20,4,db_formatar($la22_d_data,'d'),0,0,"L",1);//data
      $pdf->cell(60,4,substr($z01_v_nome,0,80),0,0,"L",1);//paciente
      $pdf->cell(60,4,substr($la31_retiradopor,0,80),0,0,"L",1);//data retirada
      $pdf->cell(60,4,substr($la08_c_descr,0,50),0,0,"L",1);//descr exame
      $pdf->cell(60,4,substr($la15_c_descr,0,50),0,1,"L",1);//descr material
	
	  $d = $la22_i_codigo; 
	  $total +=1;
     }
	 

    $pdf->cell(15,5,"",0,0,"L",0);
    $pdf->cell(45,6,substr($la33_c_descr,0,40),0,0,"L",0);//tipo doc rg ou cpf
	$pdf->cell(40,6,substr($la31_c_documento,0,12),0,0,"L",0);//num doc 
    $pdf->cell(35,6,db_formatar($la32_d_data,'d'),0,0,"L",0);//data coleta
	$pdf->cell(30,6,db_formatar($la31_d_data,'d'),0,0,"L",0);//data entrega
    $pdf->cell(30,6,$la31_c_hora,0,0,"L",0);// hora da entrega
    $pdf->cell(50,6,substr($nome,0,50),0,1,"L",0);//usuario que entregou
	$cont++;
   }
$pdf->Output();
?>