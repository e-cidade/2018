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
include("classes/db_prontuarios_classe.php");
include("classes/db_unidades_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

 $data1 = str_replace("X","-",$data1);
 $data2 = str_replace("X","-",$data2);
$result  = $clprontuarios->sql_record($clprontuarios->sql_query("","sd24_i_usuario,nome,count(*)","","sd24_d_data BETWEEN '$data1' and '$data2' GROUP BY sd24_i_usuario, db_usuarios.nome"));
$result2 = $clprontuarios->sql_record($clprontuarios->sql_query("","count(*) as total","","sd24_d_data BETWEEN '$data1' and '$data2' GROUP BY sd24_i_usuario, db_usuarios.nome"));
if($clprontuarios->numrows == 0)
{
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
db_fieldsmemory($result2,0);
$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Relatório dos Digitadores de Ficha de Atendimento";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri = true;
  //todos os registros para o percentual
  $Soma = pg_query("SELECT count(*) from prontuarios");
  $Dados = pg_fetch_row($Soma);

 for ($i = 0;$i < $clprontuarios->numrows;$i++){
   db_fieldsmemory($result,$i);
   if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);
      $pdf->cell(17,4,"Código",1,0,"C",1);
      $pdf->cell(90,4,"Nome",1,0,"C",1);
      $pdf->cell(35,4,"Fichas",1,0,"C",1);
      $pdf->cell(15,4,"%",1,1,"C",1);

      $Qtd1 = 0;
      $Total1 = 0;

  }
  $pdf->setfont('arial','',7);
  $pdf->cell(17,4,$sd24_i_usuario,1,0,"C",0);
  $pdf->cell(90,4,$nome,1,0,"C",0);
  $pdf->cell(35,4,$count,1,0,"C",0);
  $pdf->cell(15,4,str_pad(number_format(($count*100)/$total,2,',',''),6,0,str_pad_left),1,0,"C",0);
}
$pdf->Output();
?>