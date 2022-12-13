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
include("classes/db_cgmsaida_classe.php");
include("classes/db_prontsaida_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clprontsaida = new cl_prontsaida;
$clcgmsaida = new cl_cgmsaida;

 $data1 = str_replace("X","-",$data1);
 $data2 = str_replace("X","-",$data2);

 if($cgm != ""){
  $result = $clcgmsaida->sql_record($clcgmsaida->sql_query("","*","","sd28_i_cgm = $cgm and sd28_d_data BETWEEN '$data1' and '$data2'"));
  $linhas = $clcgmsaida->numrows;
 }else{
//    die($clprontsaida->sql_query("","*","","sd27_i_prontuario = $pront and sd27_d_data BETWEEN '$data1' and '$data2'"));
  $result = $clprontsaida->sql_record($clprontsaida->sql_query("","*","","sd27_i_prontuario = $pront and sd27_d_data BETWEEN '$data1' and '$data2'"));
  $linhas = $clprontsaida->numrows;
 }
if($linhas == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Relatório das Saídas de Medicamentos e Materiais";
if($cgm != ""){
 $head3 = "CGM:".$cgm;
}else{
 $head3 = "PRONTUARIO:".$pront;
}
$head4 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);

$pri = true;
 for ($i = 0;$i < $linhas;$i++){
 db_fieldsmemory($result,$i);
   if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);
      $pdf->cell(10,4,"Código",1,0,"C",1);
      if($cgm != ""){
      $pdf->cell(15,4,"Cgm",1,0,"C",1);
      }else{
      $pdf->cell(15,4,"Prontuario",1,0,"C",1);
      }
      $pdf->cell(60,4,"Nome",1,0,"C",1);
      $pdf->cell(60,4,"Departamento",1,0,"C",1);
      $pdf->cell(13,4,"Material",1,0,"C",1);
      $pdf->cell(60,4,"Descrição",1,0,"C",1);
      $pdf->cell(35,4,"Quant.",1,0,"C",1);
      $pdf->cell(16,4,"Data",1,0,"C",1);
      $pdf->cell(15,4,"Login",1,1,"C",1);
      $pri = false;
  }
  $pdf->setfont('arial','',7);

  if($cgm != ""){
  $pdf->cell(10,4,$sd28_i_codigo,1,0,"L",0);
  $pdf->cell(15,4,$sd28_i_cgm,1,0,"L",0);
  $pdf->cell(60,4,$z01_nome,1,0,"L",0);
  $pdf->cell(60,4,$descrdepto,1,0,"L",0);
  $pdf->cell(13,4,$sd28_i_material,1,0,"L",0);
  $pdf->cell(60,4,$m60_descr,1,0,"L",0);
  $pdf->cell(35,4,$sd28_i_quantidade,1,0,"L",0);
  $pdf->cell(16,4,$sd28_d_data,1,0,"L",0);
  $pdf->cell(15,4,$sd28_i_usuario,1,1,"L",0);
  }else{
  $pdf->cell(10,4,$sd27_i_codigo,1,0,"L",0);
  $pdf->cell(15,4,$sd27_i_prontuario,1,0,"L",0);
  $pdf->cell(60,4,$z01_nome,1,0,"L",0);
  $pdf->cell(60,4,$descrdepto,1,0,"L",0);
  $pdf->cell(13,4,$sd27_i_material,1,0,"L",0);
  $pdf->cell(60,4,$m60_descr,1,0,"L",0);
  $pdf->cell(35,4,$sd27_i_quantidade,1,0,"L",0);
  $pdf->cell(16,4,$sd27_d_data,1,0,"L",0);
  $pdf->cell(15,4,$sd27_i_usuario,1,1,"L",0);
  }
 }
$pdf->Output();
?>