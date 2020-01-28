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
include("classes/db_docaluno_classe.php");
$cldocaluno = new cl_docaluno;
$escola = db_getsession("DB_coddepto");
$sql = "SELECT DISTINCT
               ed47_i_codigo,
               ed47_v_nome,
               ed57_c_descr,
               ed11_c_descr,
               ed52_c_descr
        FROM aluno
         inner join matricula on ed60_i_aluno = ed47_i_codigo
         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
         inner join turma on ed57_i_codigo = ed60_i_turma
         inner join serie on ed11_i_codigo = ed221_i_serie
         inner join calendario on ed52_i_codigo = ed57_i_calendario
         inner join docaluno on ed49_i_aluno = ed47_i_codigo
        WHERE ed57_i_calendario = $calendario
        AND ed57_i_escola = $escola
        AND ed49_i_escola = $escola
        AND ed221_c_origem = 'S'
        ORDER BY ed47_v_nome
       ";
$result = pg_query($sql);
//db_criatabela($result);
//exit;
$linhas = pg_num_rows($result);
if($linhas==0){?>
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
$head1 = "RELATÓRIO DE DOCUMENTOS PENDENTES";
$head2 = "Calendário: ".pg_result($result,0,'ed52_c_descr');
$pdf->ln(5);
$troca = 1;
$cor1 = "0";
$cor2 = "1";
$cor = "";
for($c=0;$c<$linhas;$c++){
 db_fieldsmemory($result,$c);
 if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('P');
  $pdf->setfillcolor(215);
  $pdf->setfont('arial','b',8);
  $pdf->cell(20,4,"Código",1,0,"C",1);
  $pdf->cell(110,4,"Nome",1,0,"L",1);
  $pdf->cell(30,4,"Etapa",1,0,"C",1);
  $pdf->cell(30,4,"Turma",1,1,"C",1);
  $troca = 0;
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $pdf->cell(20,4,$ed47_i_codigo,"T",0,"C",$cor);
 $pdf->cell(110,4,$ed47_v_nome,"T",0,"L",$cor);
 $pdf->cell(30,4,$ed11_c_descr,"T",0,"C",$cor);
 $pdf->cell(30,4,$ed57_c_descr,"T",1,"C",$cor);
 $pdf->cell(20,4,"",0,0,"C",$cor);
 $pdf->cell(170,4,"Documentos pendentes:",0,1,"L",$cor);
 $result1 = $cldocaluno->sql_record($cldocaluno->sql_query("","ed02_c_descr,ed49_t_obs",""," ed49_i_aluno = $ed47_i_codigo"));
 for($x=0;$x<$cldocaluno->numrows;$x++){
  db_fieldsmemory($result1,$x);
  $pdf->cell(20,4,"",0,0,"C",$cor);
  $pdf->cell(40,4,"* ".$ed02_c_descr,0,0,"L",$cor);
  $pdf->cell(130,4,$ed49_t_obs,0,1,"L",$cor);
 }
}
$pdf->Output();
?>