<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_rechumanoescola_classe.php");
include("classes/db_areatrabalho_classe.php");
include("classes/db_relacaotrabalho_classe.php");
include("classes/db_regenciahorario_classe.php");
$clrechumanoescola = new cl_rechumanoescola;
$clareatrabalho = new cl_areatrabalho;
$clrelacaotrabalho = new cl_relacaotrabalho;
$clregenciahorario = new cl_regenciahorario;
$head3 = "";
if($area==0){
 $where = "";
 $head3 = "Área de Trabalho: TODAS";
}else{
 $where = " AND ed25_i_codigo = $area";
 $result1 = $clareatrabalho->sql_record($clareatrabalho->sql_query("","ed25_c_descr","ed25_c_descr"," ed25_i_codigo = $area"));
 $head3 = "Área de Trabalho: ".trim(pg_result($result1,0,'ed25_c_descr'));
}
$sql = "SELECT DISTINCT ed18_c_nome,ed75_i_codigo,ed20_i_codigo,case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed75_d_ingresso
        FROM rechumanoescola
         inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola
         inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano
         left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
         left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
         left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
         left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
         left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
         inner join rechumanoativ  on  rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo
         inner join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade
         inner join relacaotrabalho  on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo
         inner join areatrabalho  on  areatrabalho.ed25_i_codigo = relacaotrabalho.ed23_i_areatrabalho
        WHERE ed75_i_escola = $escola
        AND ed01_c_regencia = 'S'
        $where
        ORDER BY z01_nome
       ";
$result = pg_query($sql);;
$linhas = pg_num_rows($result);
//exit;
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
$head1 = "RELATÓRIO DE PROFESSORES POR ESCOLA";
$head2= "Escola:".trim(pg_result($result,0,'ed18_c_nome'));
$pdf->ln(5);
$troca = 1;
$cor1 = "0";
$cor2 = "1";
$cor = "";
$cont = 0;
for($c=0;$c<$linhas;$c++){
 db_fieldsmemory($result,$c);
 if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('P');
  $pdf->setfillcolor(215);
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,4,"Matrícula/CGM",1,0,"C",1);
  $pdf->cell(70,4,"Nome",1,0,"L",1);
  $pdf->cell(30,4,"Data de Ingresso",1,0,"L",1);
  $pdf->cell(35,4,"Regime de Trabalho",1,0,"L",1);
  $pdf->cell(30,4,"Turnos",1,1,"L",1);
  $troca = 0;
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $pdf->cell(25,4,$identificacao,0,0,"C",$cor);
 $pdf->setfont('arial','b',8);
 $pdf->cell(70,4,$z01_nome,0,0,"L",$cor);
 $pdf->setfont('arial','',7);
 $pdf->cell(30,4,db_formatar($ed75_d_ingresso,"d"),0,0,"L",$cor);
 $result3 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query("","DISTINCT ed24_c_descr",""," ed23_i_rechumanoescola = $ed75_i_codigo"));
 if($clrelacaotrabalho->numrows>0){
  db_fieldsmemory($result3,0);
  $regime = $ed24_c_descr;
 }else{
  $regime = "Sem registros";
 }
 $pdf->cell(35,4,$regime,0,0,"L",$cor);
 $result4 = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed15_c_nome,ed15_i_sequencia","ed15_i_sequencia"," ed58_i_rechumano = $ed20_i_codigo AND ed58_ativo is true  and ed18_i_codigo = $escola AND ed52_i_ano = ".date("Y").""));
 $turnos = "";
 $sep = "";
 if($clregenciahorario->numrows>0){
  for($r=0;$r<$clregenciahorario->numrows;$r++){
   db_fieldsmemory($result4,$r);
   $turnos .= $sep.$ed15_c_nome;
   $sep = " / ";
  }
 }else{
  $turnos = "Sem registros";
 }
 $pdf->cell(30,4,$turnos,0,1,"L",$cor);
 $cont++;
 if($disciplina=="S"){
  $result2 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query("","*","ed25_c_descr"," ed23_i_rechumanoescola = $ed75_i_codigo AND ed23_i_disciplina is not null"));
  echo pg_errormessage();
  if($clrelacaotrabalho->numrows){
   $primeiro = "";
   for($t=0;$t<$clrelacaotrabalho->numrows;$t++){
    db_fieldsmemory($result2,$t);
    if($primeiro!=$ed25_c_descr){
     $pdf->cell(20,4,"",0,0,"L",$cor);
     $pdf->setfont('arial','b',7);
     $pdf->cell(170,4,"Área de Trabalho: ".$ed25_c_descr,0,1,"L",$cor);
     $pdf->setfont('arial','',7);
     $primeiro = $ed25_c_descr;
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(20,4,"",0,0,"L",$cor);
    $pdf->cell(5,4,"",0,0,"L",$cor);
    $pdf->cell(165,4,$ed232_c_descr,0,1,"L",$cor);
   }
  }else{
   $pdf->cell(20,4,"",0,0,"L",$cor);
   $pdf->cell(170,4,"Nenhum registro.",0,1,"L",$cor);
  }
 }
}
$pdf->setfont('arial','b',7);
$pdf->cell(190,5,"Total de Professores: $cont",1,1,"L",0);
$pdf->Output();
?>