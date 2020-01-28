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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);

  $SQL_rel = "SELECT sd02_i_codigo,
                     sd11_c_codigo,
                     sd11_c_descr,
                     sd11_f_orcamento,
                     count(*),
                     sd02_c_razao
                FROM prontuarios
          inner join unidades     on sd24_i_unidade      = sd02_i_codigo
          inner join prontproced   on sd24_i_id = sd29_i_prontuario
          inner join procedimentos on sd29_i_procedimento = sd09_i_codigo
          inner join grupoproc     on sd11_c_codigo       = sd09_c_grupoproc
               WHERE sd24_i_unidade in ($unidade)
                 AND sd24_d_data BETWEEN '$data1' and '$data2'
            GROUP BY sd02_i_codigo,
                     sd11_c_codigo,
                     sd11_c_descr,
                     sd11_f_orcamento,
                     sd02_c_razao";
//echo $SQL_rel;
  $Query_rel = pg_query($SQL_rel);
  $Linhas_rel = pg_num_rows($Query_rel);
if($Linhas_rel == 0){
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
$head2 = "Programação no Município";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri = true;
$b="X";
 for ($i = 0; $i < $Linhas_rel; $i++){
  $Array = pg_fetch_array($Query_rel);
   if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);
      $pdf->cell(20,4,"Programa",1,0,"L",1);
      $pdf->cell(80,4,"Descrição",1,0,"L",1);
      $pdf->cell(40,4,"Físico",1,0,"L",1);
      $pdf->cell(40,4,"Orçamento",1,1,"L",1);
      $pri = false;
   }
   if($b == "X"){
    $pdf->setfont('arial','b',7);
    $pdf->cell(180,4,$Array[0]." - ".$Array[5],1,1,"L",1);
   }
   if($b != $Array[0] && $b != 'X'){
     $pdf->cell(20,4,"Total",1,0,"L",0);
     $pdf->cell(80,4,"",1,0,"L",0);
     $pdf->cell(40,4,$T." ",1,0,"L",0);
     $pdf->cell(40,4,$T2." ",1,1,"L",0);
     $pdf->setfont('arial','b',7);
     $pdf->cell(180,4,$Array[0]." - ".$Array[5],1,1,"L",1);
     $T  = 0;
     $T2 = 0;
   }
  $pdf->setfont('arial','',7);
  $pdf->cell(20,4,$Array[1],1,0,"L",0);
  $pdf->cell(80,4,$Array[2],1,0,"L",0);
  $pdf->cell(40,4,$Array[4],1,0,"L",0);
  $pdf->cell(40,4,$Array[3],1,1,"L",0);

$T  += $Array[4];
$T2 += $Array[3];
$T3 += $Array[4];
$T4 += $Array[3];
$b = $Array[0];
}
$pdf->cell(20,4,"Total",1,0,"L",0);
$pdf->cell(80,4,"",1,0,"L",0);
$pdf->cell(40,4,$T." ",1,0,"L",0);
$pdf->cell(40,4,$T2." ",1,1,"L",0);
$pdf->cell(20,4,"Total Geral",1,0,"L",0);
$pdf->cell(80,4,"",1,0,"L",0);
$pdf->cell(40,4,$T3." ",1,0,"L",0);
$pdf->cell(40,4,$T4." ",1,1,"L",0);
$pdf->Output();
?>