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
include("classes/db_cancdebitos_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcancdebitos = new cl_cancdebitos;
$clcancdebitos->rotulo->label();
$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$sql ="select sd24_i_ano,
              sd24_i_mes,
              sd24_i_seq,
              case when cgm.z01_numcgm is null then
               cgs_und.z01_i_cgsund
              else
               cgm.z01_numcgm
              end as z01_numcgm,
              sd63_c_procedimento,
              sd63_c_nome,
              case when cgm.z01_numcgm is null then
               cgs_und.z01_v_nome
              else
               cgm.z01_nome
              end as z01_nome,
              cgmmedico.z01_numcgm as cgmmed,
              cgmmedico.z01_nome as nomemed
         from prontproced
       	inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
       	inner join especmedico on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
        inner join unidademedicos on unidademedicos.sd04_i_codigo =especmedico.sd27_i_undmed        		
        inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
        inner join sau_procedimento on sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento
        inner join cgs     on cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs
         left join cgs_cgm on cgs_cgm.z01_i_cgscgm = cgs.z01_i_numcgs
         left join cgm     on cgm.z01_numcgm       = cgs_cgm.z01_i_numcgm
         left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs
        inner join cgm as cgmmedico on cgmmedico.z01_numcgm = medicos.sd03_i_cgm
        where sd24_i_unidade in ($unidade)
          and sd29_d_data between '$data1' and '$data2'
      ";
if($medico != ""){
  $sql .= "and medicos.sd03_i_codigo = $medico";
}
$sql .= "order by nomemed, sd24_i_ano, sd24_i_mes, sd24_i_seq, z01_nome, sd63_c_procedimento";
$result = pg_query($sql);
//db_criatabela($result);
//exit;

if(pg_num_rows($result) == 0){
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
$head1 = "Relatório do Atendimentos";
$head2 = "Período:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
if($medico!=""){
 $h_med = $medico." - ".pg_result($result,0,'nomemed');
}else{
 $h_med = "TODOS";
}
$head3 = "Médico: ".$h_med;
$primed = "";
$cor1 = 0;
$cor2 = 1;
$cor = "";
$pdf->addpage();

for ($i = 0;$i < pg_num_rows($result);$i++){
 db_fieldsmemory($result,$i);
 if($primed!=$cgmmed){
  $pdf->setfillcolor(180);
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,4,"Médico: ".$cgmmed." - ".$nomemed,1,1,"C",1);
  if (  ($pdf->gety() > $pdf->h -30)){
   $pdf->addpage();
   $pdf->setfillcolor(180);
   $pdf->setfont('arial','b',8);
   $pdf->cell(190,4,"Médico: ".$cgmmed." - ".$nomemed,1,1,"C",1);
   $pdf->setfillcolor(235);
  }
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $pdf->cell(5,4,"",0,0,"C",0);
  $pdf->cell(30,4,"N° Atendimento","LBT",0,"L",1);
  $pdf->cell(70,4,"Paciente","BT",0,"L",1);
  $pdf->cell(80,4,"Procedimento","BTR",0,"L",1);
  $pdf->cell(5,4,"",0,1,"C",0);
  $pdf->cell(190,1,"",0,1,"C",0);
  $primed = $cgmmed;
 }
 if (  ($pdf->gety() > $pdf->h -30)){
  $pdf->addpage();
  $pdf->setfillcolor(180);
  $pdf->setfont('arial','b',8);
  $pdf->cell(190,4,"Médico: ".$cgmmed." - ".$nomemed,1,1,"C",1);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $pdf->cell(5,4,"",0,0,"C",0);
  $pdf->cell(30,4,"N° Atendimento","LBT",0,"L",1);
  $pdf->cell(70,4,"Paciente","BT",0,"L",1);
  $pdf->cell(80,4,"Procedimento","BTR",0,"L",1);
  $pdf->cell(5,4,"",0,1,"C",0);
  $pdf->cell(190,1,"",0,1,"C",0);
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfont('arial','',7);
 $pdf->cell(5,4,"",0,0,"C",0);
 $pdf->cell(30,4,$sd24_i_ano."-".str_pad($sd24_i_mes,2,0,"str_pad_left")."-".str_pad($sd24_i_seq,6,0,"str_pad_left"),0,0,"L",$cor);
 $pdf->cell(70,4,$z01_nome,0,0,"L",$cor);
 $pdf->cell(80,4,substr($sd63_c_procedimento." - ".$sd63_c_nome,0,50),0,0,"L",$cor);
 $pdf->cell(5,4,"",0,1,"L",0);
}

$pdf->Output();
?>