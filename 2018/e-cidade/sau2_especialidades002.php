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
include("classes/db_prontproced_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprontproced = new cl_prontproced;
$clprontproced->rotulo->label();

$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);

$sql = "SELECT  count(*) as quantidade,
                procedimentos.sd09_i_codigo,
                procedimentos.sd09_c_descr,
                especialidades.sd05_i_codigo,
                especialidades.sd05_c_descr
        FROM prontproced
         inner join procedimentos  on prontproced.sd29_i_procedimento = procedimentos.sd09_i_codigo
         inner join prontuarios    on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
         inner join especmedico    on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
         inner join especialidades on especialidades.sd05_i_codigo = especmedico.sd27_i_rhcbo
        WHERE prontproced.sd29_d_data BETWEEN '$data1' and '$data2'
        GROUP BY procedimentos.sd09_i_codigo,procedimentos.sd09_c_descr, especialidades.sd05_i_codigo, especialidades.sd05_c_descr
        ORDER BY especialidades.sd05_i_codigo, especialidades.sd05_c_descr
        ";
        
$result = pg_query($sql);
$linhas = pg_num_rows($result);
//db_criatabela($result);
//exit;
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
$head1 = "Relatório de Especialidades";
$head2 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);

$pdf->addpage();
$pri = true;
$g_total = 0;
$s_total = 0;
$unid = "";
$cor1 = 0;
$cor2 = 1;
$cor = "";
for ($i=0;$i<$linhas;$i++){
 db_fieldsmemory($result,$i);
 if($unid!=$sd05_i_codigo){
  if ( $unid != "" ){
     $pdf->cell(190,4,"Total da Especilidade: $g_total",1,1,"R",0);
     $g_total = 0;
  }
  $pdf->setfillcolor(180);
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,4,"Especialidade: $sd05_i_codigo - $sd05_c_descr",1,1,"L",1);
  $pdf->cell(190,1,"",0,1,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->setfillcolor(240);
  $unid = $sd05_i_codigo;
 }
 $pdf->cell(5,4,"",0,0,"L",0);
 $pdf->cell(160,4,"Procedimento: $sd09_i_codigo - $sd09_c_descr","BTL",0,"L",1);
 $pdf->cell(20,4,"Total: $quantidade","BTR",0,"L",1);
 $pdf->cell(5,4,"",0,1,"L",0);
 $s_total += $quantidade;
 $g_total += $quantidade;

}
$pdf->cell(190,4,"Total da Especilidade: $g_total",1,1,"R",0);
$pdf->setfont('arial','b',9);
$pdf->cell(190,6,"Total Geral das Especialidades: $s_total",1,1,"R",0);
$pdf->Output();
?>