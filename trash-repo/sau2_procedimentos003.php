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
$procedimento = @$procedimento;
if($procedimento!=""){
 $sql_proc = " AND sau_procedimento.sd63_c_procedimento = '$procedimento'";
}else{
 $sql_proc = "";
}
$sql = "SELECT  count(*) as quantidade,
                sd63_c_procedimento,
                sd63_c_nome
        FROM prontproced
         inner join sau_procedimento on prontproced.sd29_i_procedimento = sau_procedimento.sd63_i_codigo
         inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
         left  join db_depart on db_depart.coddepto = prontuarios.sd24_i_unidade
        WHERE prontproced.sd29_d_data BETWEEN '$data1' and '$data2' AND prontuarios.sd24_i_unidade in($unidade)
        $sql_proc
        GROUP BY sd63_c_procedimento,sd63_c_nome
        ORDER BY sd63_c_nome
        ";
//echo "<BR> $sql";        
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
$head1 = "Relatório dos Procedimentos";
$head2 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
//$head3 = "Unidade(s): $unidade";
if($procedimento!=""){
 $h_proc = $procedimento;
}else{
 $h_proc = "TODOS";
}
$head4 = "Procedimento: $h_proc";

$pdf->addpage();
$pdf->setfont('arial','b',8);
$pdf->setfillcolor(230);
$vet=explode(",",$unidade);
$pdf->cell(100,4,"Unidades","1",1,"L",1);
for ($i=0;$i<count($vet);$i++){
    $result_unidade=pg_query("select descrdepto from db_depart where coddepto=".$vet[$i]);
    db_fieldsmemory($result_unidade,0);
    $pdf->cell(15,4,$vet[$i],1,0,"L",0);
    $pdf->cell(85,4,$descrdepto,1,1,"L",0);
}

$pri = true;
$s_total = 0;
$cor1 = 0;
$cor2 = 1;
$cor = "";
for ($i=0;$i<$linhas;$i++){
    db_fieldsmemory($result,$i);    
    if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
          $pdf->addpage();
          $pri = false;
          $pdf->setfont('arial','b',8);
          $pdf->setfillcolor(230);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(5,4,"",0,0,"L",0);
    $pdf->cell(160,4,"Procedimento: ".$sd63_c_procedimento." - ".substr($sd63_c_nome,0,70),"BTL",0,"L",0);
    $pdf->cell(20,4,"Total: $quantidade","BTR",0,"L",1);
    $pdf->cell(5,4,"",0,1,"L",0);
    $s_total += $quantidade;
    $cont++;
}
$pdf->setfont('arial','b',9);
$pdf->cell(190,6,"Total Geral de Procedimentos: $s_total",1,1,"R",0);
$pdf->Output();

?>