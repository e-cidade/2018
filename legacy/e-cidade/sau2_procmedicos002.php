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
$unidade = @$unidade;
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$procedimento = @$procedimento;
$medico = @$medico;
if($procedimento!=""){
 $sql_proc = " AND prontproced.sd29_i_procedimento = $procedimento";
}else{
 $sql_proc = "";
}
if($medico!=""){
 $sql_med = " AND cgmmedico.z01_numcgm = $medico";
}else{
 $sql_med = "";
}
$sql = "SELECT  count(*) as quantidade,
                z01_numcgm,
                z01_nome,
                db_depart.coddepto,
                db_depart.descrdepto
        FROM prontproced
         inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
         inner join db_depart on db_depart.coddepto = sd24_i_unidade
         inner join especmedico on especmedico.sd27_i_codigo = sd29_i_especmed
         inner join medicos on medicos.sd03_i_codigo = sd27_i_medico
         inner join cgm as cgmmedico on cgmmedico.z01_numcgm = sd03_i_codigo
        WHERE sd29_d_data BETWEEN '$data1' and '$data2' AND sd24_i_unidade in($unidade)
        $sql_proc
        $sql_med
        GROUP BY z01_nome,z01_numcgm,coddepto,descrdepto
        ORDER BY z01_nome
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
$head1 = "Relatório dos Procedimentos Por Médico";
$head2 = "Periodo: ".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
if($medico!=""){
 $h_med = $medico." - ".pg_result($result,0,'z01_nome');
}else{
 $h_med = "TODOS";
}
if($procedimento!=""){
 $h_proc = $procedimento;
}else{
 $h_proc = "TODOS";
}
$head3 = "Unidade: - ".pg_result($result,0,'descrdepto');;
$head4 = "Médico: $h_med";
$head5 = "Procedimento: $h_proc";
$pdf->addpage();
$pri = true;
$s_total = 0;
$unid = "";
for ($i=0;$i<$linhas;$i++){
 $cont = 0;
 db_fieldsmemory($result,$i);
 if($unid!=$coddepto){
  $pdf->setfillcolor(180);
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,4,"Unidade: $coddepto - $descrdepto",1,1,"L",1);
  $pdf->cell(190,1,"",0,1,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->setfillcolor(230);
  $unid = $coddepto;
 }
 $pdf->setfont('arial','b',8);
 $pdf->cell(5,4,"",0,0,"L",0);
 $pdf->cell(135,4,"Médico: $z01_numcgm - $z01_nome","BTL",0,"L",1);
 $pdf->cell(45,4,"Total de Procedimentos: $quantidade","BTR",0,"L",1);
 $pdf->cell(5,4,"",0,1,"L",0);
 if(($pdf->gety() > $pdf->h -30)){
  if($cont==0){
   $pdf->setfillcolor(255);
   $pdf->rect(10,$pdf->getY()-5,190,10,'F');
   $pdf->setfillcolor(200);
  }
  $pdf->addpage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(5,4,"",0,0,"L",0);
  $pdf->cell(135,4,"Médico: $z01_numcgm - $z01_nome","BTL",0,"L",1);
  $pdf->cell(45,4,"Total de Procedimentos: $quantidade","BTR",0,"L",1);
  $pdf->cell(5,4,"",0,1,"L",0);
 }
 $pdf->setfont('arial','',7);
 $pdf->cell(5,4,"",0,0,"L",0);
 $pdf->cell(25,4,"Código:",0,0,"L",0);
 $pdf->cell(100,4,"Procedimento:",0,0,"L",0);
 $pdf->cell(55,4,"Quantidade:",0,0,"L",0);
 $pdf->cell(5,4,"",0,1,"L",0);
 $s_total += $quantidade;
 $sql1 = "SELECT  count(*) as quantidade2,
                  sd09_i_codigo,
                  sd09_c_descr
          FROM prontproced
           inner join procedimentos on prontproced.sd29_i_procedimento = procedimentos.sd09_i_codigo
           inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
           inner join especmedico on especmedico.sd27_i_codigo = sd29_i_especmed
           inner join medicos on medicos.sd03_i_codigo = sd27_i_medico
           inner join cgm as cgmmedico on cgmmedico.z01_numcgm = sd03_i_codigo
          WHERE sd29_d_data BETWEEN '$data1' and '$data2' AND sd24_i_unidade in($unidade)
          AND cgmmedico.z01_numcgm = $z01_numcgm
          $sql_proc
          $sql_med
          GROUP BY sd09_i_codigo,sd09_c_descr
          ORDER BY sd09_c_descr
        ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 $cont = 0;
 $cor1 = "1";
 $cor2 = "0";
 $cor  = "";
 for ($x=0;$x<$linhas1;$x++){
  db_fieldsmemory($result1,$x);
  if($cor==$cor1){
   $cor = $cor2;
  }else{
   $cor = $cor1;
  }
  if($quantidade==1) $cor = "0";
  if(($pdf->gety() > $pdf->h -30)){
   if($cont==0){
    $pdf->setfillcolor(255);
    $pdf->rect(10,$pdf->getY()-8,190,10,'F');
    $pdf->setfillcolor(200);
   }
   $pdf->addpage();
   $pdf->setfont('arial','b',8);
   $pdf->cell(5,4,"",0,0,"L",0);
   $pdf->cell(135,4,"Médico: $z01_numcgm - $z01_nome","BTL",0,"L",1);
   $pdf->cell(45,4,"Total de Procedimentos: $quantidade","BTR",0,"L",1);
   $pdf->cell(5,4,"",0,1,"L",0);
   $pdf->setfont('arial','',7);
   $pdf->cell(5,4,"",0,0,"L",0);
   $pdf->cell(25,4,"Código:",0,0,"L",0);
   $pdf->cell(100,4,"Procedimento:",0,0,"L",0);
   $pdf->cell(55,4,"Quantidade:",0,0,"L",0);
   $pdf->cell(5,4,"",0,1,"L",0);
  }
  $pdf->cell(5,4,"",0,0,"L",0);
  $pdf->cell(25,4,$sd09_i_codigo,0,0,"L",$cor);
  $pdf->cell(100,4,$sd09_c_descr,0,0,"L",$cor);
  $pdf->cell(55,4,$quantidade2,0,0,"L",$cor);
  $pdf->cell(5,4,"",0,1,"L",0);
  $cont++;
 }
}
$pdf->setfont('arial','b',9);
$pdf->cell(190,6,"Total Geral de Procedimentos: $s_total",1,1,"R",0);
$pdf->Output();
?>