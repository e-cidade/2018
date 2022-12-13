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

set_time_limit(0);
include("fpdf151/pdf.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
 $unidade = str_replace("X",",",$unidades);
 $data1   = str_replace("X","-",$data1);
 $data2   = str_replace("X","-",$data2);
$SQL= "  SELECT sd02_i_codigo,
                sd02_c_razao,
                sd23_i_medico,
                z01_nome,
                sd23_c_situacao,
                sd23_d_consulta
           FROM agendamentos
          inner join unidades        on sd23_i_unidade  =  sd02_i_codigo
          inner join medicos         on sd23_i_medico   =  sd03_i_id
          inner join unidademedicos  on sd03_i_id       =  sd04_i_medico
          inner join cgm             on z01_numcgm      =  sd03_i_codigo
          WHERE sd23_i_unidade in ($unidade)
            AND sd23_d_data BETWEEN '$data1' and '$data2'
       ORDER BY sd02_i_codigo, sd23_i_medico
      ";

$Query = @pg_query($SQL);
$Linhas = @pg_num_rows($Query);
if($Linhas == 0){
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
$head2 = "Relatório da Situação dos Agendamentos";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri=true;
$int_atendido  = 0;
$int_cancelado = 0;
$int_naocomp   = 0;
 $Array = pg_fetch_row($Query,0);
 $int_unidade = 0;
 $int_medico  = $Array[2];
 $str_unidade = $Array[1];
 $str_medico  = $Array[3];
 $pdf->addpage();
 $pdf->setfillcolor(235);
 $pdf->setfont('arial','b',7);
 for ($i = 0;$i < $Linhas; $i++){
  $Array = pg_fetch_row($Query,$i);
  if( $int_unidade != $Array[0] ){
      $int_atendido  = '0';
      $int_naocomp   = '0';
      $int_cancelado = '0';
      $int_unidade = $Array[0];
      $str_unidade = $Array[1];
      $pdf->cell(190,4,$int_unidade." - ".$str_unidade,1,1,"L",1);
  }
 if( $int_medico == $Array[2] ){
  if( trim($Array[4]) == "ATENDIDO" ){
   $int_atendido  = $int_atendido + 1;
  }
  if( trim($Array[4]) == "AGENDADO" && $Array[5] < date("Y-m-d")){
   $int_naocomp   = $int_naocomp+1;
  }
  if( trim($Array[4]) == "CANCELADO"){
   $int_cancelado = $int_cancelado+1;
  }
 }else if( $int_medico != $Array[2] || $i == ($Linhas-1)){
      $pdf->cell(190,4,$int_medico." - ".$str_medico,1,1,"L",1);
      $pdf->cell(40,4,"Não Compareceu",1,0,"L",0);
      $pdf->cell(150,4,$int_naocomp,1,1,"L",0);
      $pdf->cell(40,4,"Atendido",1,0,"L",0);
      $pdf->cell(150,4,$int_atendido,1,1,"L",0);
      $pdf->cell(40,4,"Cancelado",1,0,"L",0);
      $pdf->cell(150,4,$int_cancelado,1,1,"L",0);
       $int_unidade = $Array[0];
       $int_medico  = $Array[2];
       $str_unidade = $Array[1];
       $str_medico  = $Array[3];
      $int_atendido  = '0';
      $int_naocomp   = '0';
      $int_cancelado = '0';
 }
}
$pdf->Output();
?>