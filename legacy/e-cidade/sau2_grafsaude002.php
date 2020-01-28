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
include("classes/db_vacinasaplicadas_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clprontuarios = new cl_prontuarios;
 $data1 = str_replace("X","-",$data1);
 $data2 = str_replace("X","-",$data2);
 $bairro = strtoupper($bairro);
 $extra = strtoupper($extra);
//tipo
//exit;
$sql1 = "SELECT count(*) from prontuarios
          INNER JOIN unidades on sd24_i_unidade = sd02_i_codigo
          INNER JOIN cids     on sd24_c_cid    = sd22_c_codigo
          WHERE sd24_d_data BETWEEN '$data1' AND '$data2'";
  if($extra != ""){
   $sql1 .= "AND sd24_c_cid = '$extra'";
  }elseif($bairro != ""){
   $sql1 .= "AND sd02_c_bairro = '$bairro'";
  }

  $Sql = "SELECT sd24_c_cid,
               sd22_c_descr,
               sd02_c_bairro,
               count(*)
          FROM prontuarios
         INNER JOIN unidades on sd24_i_unidade = sd02_i_codigo
         INNER JOIN cids     on sd24_c_cid     = sd22_c_codigo
         WHERE sd24_d_data BETWEEN '$data1' AND '$data2'";
  if($extra != ""){
   $Sql .= "AND sd24_c_cid = '$extra'";
  }elseif($bairro != ""){
   $Sql .= "AND sd02_c_bairro = '$bairro'";
  }
$Sql .= "GROUP BY sd24_c_cid,
                  sd22_c_descr,
                  sd02_c_bairro";
$Query1 = pg_query($sql1);
$Query = pg_query($Sql);
$Linhas = pg_num_rows($Query);
 if($Linhas == 0) {
  echo "<table width='100%'>
         <tr>
           <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Gráfico<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
         </tr>
        </table>";
  exit;
 }

$extra_array = pg_fetch_row($Query,0);
$Array1 = pg_fetch_row($Query1,0);
$tot = $Array1[0];

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Gráfico Saúde";
if($tp == "cid"){
 $head3 = "CID";
 $head4 = "Faixa Etária:".$id1." Aos ".$id2;
}else{
 $head3 = "Vacinas Aplicadas";
}
$head5 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pdf->addpage();
  $pdf->setfont('arial','b',12);
  $pdf->cell(120,8,"Gráfico dos CIDS",0,1,"L",0);

 if($extra != ""){
  $pdf->setfont('arial','b',8);
  $pdf->cell(120,8,$extra_array[0]."- ".$extra_array[1],0,1,"L",0);
 }elseif($bairro != ""){
  $pdf->setfont('arial','b',8);
  $pdf->cell(120,8,$bairro,0,1,"L",0);
 }

//gera o gráfico
$b = "Z";
for($x=0; $x < $Linhas; $x++){
 $Array = pg_fetch_array($Query);
 $percent = number_format( ($Array[3]*100)/$tot,2,',','' ) ;

 if($b != $Array[2] && $bairro == ""){
  $pdf->setfont('arial','b',8);
  $pdf->cell(40,3,$Array[2],0,0,"L",0);
 }
 
  if($extra == ""){
   $pdf->setfont('arial','',7);
   $pdf->cell(70,4,trim($Array[0])."- ".trim($Array[1]),0,0,"L",0);
  }

  $pdf->setfillcolor(rand(0,255),rand(0,255),rand(0,255));
  $pdf->cell(10,4,$Array[3],0,0,"L",0);
  $pdf->cell($percent+1,3,"",1,0,"L",1);
  $pdf->cell(15,4,$percent."%",0,1,"L",0);
  $pdf->setfont('arial','',7);

$b = $Array[2];
}
$pdf->Output();
?>