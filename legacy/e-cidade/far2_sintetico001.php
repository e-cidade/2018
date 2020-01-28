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
//include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_far_retirada_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clfar_retirada = new cl_far_retirada;
$fa04_i_cgsund = $fa04_i_cgsund;
$z01_v_nome = $z01_v_nome;

if(isset($data1) && $data2!=""){ 
@$d1= substr(@$data1,6,4)."/".substr(@$data1,3,2)."/".substr(@$data1,0,2);
@$d2= substr(@$data2,6,4)."/".substr(@$data2,3,2)."/".substr(@$data2,0,2);
$result = $clfar_retirada->sql_record($clfar_retirada->sql_query(null,"fa04_i_codigo,fa04_d_data,fa04_c_numeroreceita,fa04_d_dtvalidade,fa03_c_descr,fa04_i_profissional,z01_nome,fa04_i_unidades,descrdepto","fa04_i_codigo desc","fa04_i_cgsund=$fa04_i_cgsund and fa04_d_data BETWEEN '$d1' AND '$d2'"));
}else{
$result = $clfar_retirada->sql_record($clfar_retirada->sql_query(null,"fa04_i_codigo,fa04_d_data,fa04_c_numeroreceita,fa04_d_dtvalidade,fa03_c_descr,fa04_i_profissional,z01_nome,fa04_i_unidades,descrdepto","fa04_i_codigo desc","fa04_i_cgsund=$fa04_i_cgsund"));
}

if($clfar_retirada->numrows == 0){
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
   exit;
   }
db_fieldsmemory($result,0);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "CONSULTA DE RETIRADA";
$head2 = "CGS:  ".$fa04_i_cgsund.  "-"  .$z01_v_nome;
if(isset($data1) && $data2!=""){ 
$head3 = "DATA:".$data1. " " .$data2;
}else{
 $head3 = "DATA:";	
}

$pdf->ln(5);
$pdf->addpage('L');
$total=0;
$cont=0;
 $pdf->setfont('arial','b',10);
 $pdf->cell(20,4,"Retirada ",0,0,"L",0);
 $pdf->cell(30,4,"Data ",0,0,"L",0);
 $pdf->cell(35,4,"Receita ",0,0,"L",0);
 $pdf->cell(20,4,"Validade ",0,0,"L",0);
 $pdf->cell(40,4,"Tipo ",0,0,"L",0);
 $pdf->cell(56,4,"Profissional ",0,0,"L",0);
 $pdf->cell(55,4,"UPS ",0,1,"L",0);
      
   for($s=0; $s < $clfar_retirada->numrows; $s++){
   	 db_fieldsmemory($result,$s);
	  if($cont==35){
	   $pdf->ln(5);
       $pdf->addpage('L');
	   $pdf->setfont('arial','b',10);
       $pdf->cell(20,4,"Retirada ",0,0,"L",0);
       $pdf->cell(30,4,"Data ",0,0,"L",0);
       $pdf->cell(35,4,"Receita ",0,0,"L",0);
       $pdf->cell(20,4,"Validade ",0,0,"L",0);
       $pdf->cell(40,4,"Tipo ",0,0,"L",0);
       $pdf->cell(56,4,"Profissional ",0,0,"L",0);
       $pdf->cell(55,4,"UPS ",0,1,"L",0);	
	   $cont=0;
	  } 
	$pdf->setfont('arial','',8);
    $pdf->cell(20,4,"$fa04_i_codigo",0,0,"L",0);
    $pdf->cell(30,4,db_formatar($fa04_d_data,'d'),0,0,"L",0);
    $pdf->cell(35,4,substr($fa04_c_numeroreceita,0,10),0,0,"L",0);
    $pdf->cell(20,4,db_formatar($fa04_d_dtvalidade,'d'),0,0,"L",0);
    $pdf->cell(40,4,"$fa03_c_descr",0,0,"L",0);
    $pdf->cell(56,4,substr($fa04_i_profissional. "-". $z01_nome,0,32),0,0,"L",0);
    $pdf->cell(55,4,substr($fa04_i_unidades."-" .$descrdepto,0,40),0,1,"L",0); 
    $total +=1;
	$cont++;
   } 
  
 $pdf->cell(70,4,"",0,0,"C",0); 
 $pdf->line(10,$pdf->getY(),285,$pdf->getY());
 $pdf->cell(320,4,"TOTAL DE REGISTROS :" .$total,0,1,"C",0);
 
$pdf->Output();
?>