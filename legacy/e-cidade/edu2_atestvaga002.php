<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_atestvaga_classe.php");
$escola = db_getsession("DB_coddepto");
$clatestvaga = new cl_atestvaga;
$sql = $clatestvaga->sql_query("","*","ed47_v_nome"," ed102_i_codigo in ($alunos)");
$result = $clatestvaga->sql_record($sql);

if($clatestvaga->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
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
for($x=0;$x<$clatestvaga->numrows;$x++){
 db_fieldsmemory($result,$x);
 $head1 = "ATESTADO DE VAGA";
 $head2 = "Código: $ed102_i_codigo";
 $head3 = "Usuário Emissor: $nome";
 $pdf->setfillcolor(223);
 $pdf->addpage();
 $pdf->ln(5);
 $texto = "             Atesto, para os devidos fins, que há vaga na etapa $ed11_c_descr, ensino $ed10_c_descr, turno $ed15_c_nome, no ano letivo de $ed52_i_ano, para o aluno(a) $ed47_v_nome neste Estabelecimento de Ensino.";
 $data = substr($ed102_d_data,8,2)." de ".db_mes(substr($ed102_d_data,5,2),1)." de ".substr($ed102_d_data,0,4).".";
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,20,"ATESTADO DE VAGA",0,1,"C",0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->setfont('arial','b',9);
 $pdf->multicell(190,4,$texto,0,"J",0,0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->cell(190,6,$data,0,1,"C",0);
 $pdf->cell(190,60,"",0,1,"C",0);
 $pdf->cell(190,6,"___________________________________________________",0,1,"C",0);
 $pdf->cell(190,6,"Assinatura da direção",0,1,"C",0);
 $pdf->cell(190,30,"",0,1,"C",0);
 if($ed102_t_obs!=""){
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"Observações:",0,1,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->multicell(190,4,$ed102_t_obs,0,"J",0,5);
 }
}
$pdf->addpage();
 $pdf->ln(5);
 $texto = "             Atesto, para os devidos fins, que há vaga na etapa $ed11_c_descr, ensino $ed10_c_descr, turno $ed15_c_nome, no ano letivo de $ed52_i_ano, para o aluno(a) $ed47_v_nome neste Estabelecimento de Ensino.";
 $data = substr($ed102_d_data,8,2)." de ".db_mes(substr($ed102_d_data,5,2),1)." de ".substr($ed102_d_data,0,4).".";
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,20,"ATESTADO DE VAGA",0,1,"C",0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->setfont('arial','b',9);
 $pdf->multicell(190,4,$texto,0,"J",0,0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->cell(190,6,$data,0,1,"C",0);
 $pdf->cell(190,60,"",0,1,"C",0);
 $pdf->cell(190,6,"___________________________________________________",0,1,"C",0);
 $pdf->cell(190,6,"Assinatura da direção",0,1,"C",0);
 $pdf->cell(190,30,"",0,1,"C",0);
 if($ed102_t_obs!=""){
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"Observações:",0,1,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->multicell(190,4,$ed102_t_obs,0,"J",0,5);
 }

$pdf->Output();
?>