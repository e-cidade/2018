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
include("classes/db_matricula_classe.php");
include("classes/db_regenteconselho_classe.php");
include("classes/db_turma_classe.php");
$clmatricula = new cl_matricula;
$clregenteconselho = new cl_regenteconselho;
$clturma = new cl_turma;
$escola = db_getsession("DB_coddepto");
$result = $clturma->sql_record($clturma->sql_query_turmaserie("","*","ed57_c_descr"," ed220_i_codigo in ($codturma)"));
if($clturma->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma turma para o curso selecionado<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
db_fieldsmemory($result,0);
$condicao2 = "";
if($tipocompetencia!=""){
	
 if(isset($tipomes)){
  $condicao2 .= "AND me14_i_mes = $tipomes";	
 }
 if(isset($tipoperiodo)){
  $condicao2 .= "AND me14_i_periodocalendario = $tipoperiodo"; 
 }
}
if($active=="SIM"){
 $condicao=" AND ed60_c_situacao = 'MATRICULADO'";
}else{
 $condicao="";
}
if($tipomodelo=="1"){
 $campos = "ed47_i_codigo,ed47_v_nome,round(me14_f_peso,2),round(me14_f_altura,2),round(me14_f_peso/(me14_f_altura*2),2),to_char(me14_d_data,'DD/MM/YYYY')";	
}else{

  $campos = str_replace(chr(92),"",$campos);	
}

$sql2 = "SELECT $campos
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join aluno on ed47_i_codigo = ed60_i_aluno
          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
          left join mer_infaluno on me14_i_aluno = ed47_i_codigo
         WHERE ed60_i_turma = $ed57_i_codigo 
         AND ed221_i_serie = $ed223_i_serie 
         AND ed60_i_codigo in ($alunos)
         AND ed221_c_origem = 'S'
         $condicao
         $condicao2
         ORDER BY $ordenacao , ed60_c_ativa
        ";
$result2 = pg_query($sql2);
$linhas2 = pg_num_rows($result2);
if($linhas2==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado<br>
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
$array_meses = array("JAN","FEV","MAR","ABR","MAI","JUN","JUL","AGO","SET","OUT","NOV","DEZ");
$cabecalho_campos = explode("|",$cabecalho);
$largura_campos = explode("|",$colunas);
$alinhamento_campos = explode("|",$alinhamento);
$result5 = $clregenteconselho->sql_record($clregenteconselho->sql_query("","case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente",""," ed235_i_turma = $ed57_i_codigo"));
if($clregenteconselho->numrows>0){
 db_fieldsmemory($result5,0);
}else{
 $regente = "";
}
$pdf->setfillcolor(223);
$head1 = $titulorel==""?"QUADRO DE DESENVOLVIMENTO":$titulorel;
$head2 = "Turma: $ed57_c_descr";
$head3 = "Calendário: $ed52_c_descr";
$head4 = "Etapa: $ed11_c_descr";
if($nomeregente=="S"){
 $head5 = "Regente: $regente";
}else{
 $head5 = "";
}
$pdf->addpage($orientacao);
$pdf->ln(5);
$pdf->setfont('arial','b',$tamfonte);
$somacampos = 0;
for($t=0;$t<count($cabecalho_campos);$t++){
 if($t==(count($cabecalho_campos)-1)){
  $next = 1;
 }else{
  $next = 0;
 }
 $pdf->cell($largura_campos[$t],4,$cabecalho_campos[$t],1,$next,"C",0);
 $somacampos += $largura_campos[$t];
}
$limite = $orientacao=="P"?55:34;
$cont = 0;
for($y=0;$y<$linhas2;$y++){
 for($t=0;$t<count($cabecalho_campos);$t++){
  if($t==(count($cabecalho_campos)-1)){
   $next = 1;
  }else{
   $next = 0;
  }
  $pdf->cell($largura_campos[$t],4,(pg_field_type($result2,$t)=="date"?db_formatar(pg_result($result2,$y,$t),'d'):pg_result($result2,$y,$t)),1,$next,$alinhamento_campos[$t],0);
 }
 if($limite==$cont){
  $pdf->line(10,44,$somacampos+10,44);
  $pdf->addpage($orientacao);
  $pdf->ln(5);
  $pdf->setfont('arial','b',$tamfonte);
  for($t=0;$t<count($cabecalho_campos);$t++){
   if($t==(count($cabecalho_campos)-1)){
    $next = 1;
   }else{
    $next = 0;
   }
   $pdf->cell($largura_campos[$t],4,$cabecalho_campos[$t],1,$next,"C",0);
  }
  $cont = -1;
 }
 $cont++;
}
$comeco = $cont-1;
for($y=$comeco;$y<$limite;$y++){
 for($t=0;$t<count($cabecalho_campos);$t++){
  if($t==(count($cabecalho_campos)-1)){
   $next = 1;
  }else{
   $next = 0;
  }
  $pdf->cell($largura_campos[$t],4,"","LR",$next,"C",0);
 }
}
$pdf->line(10,$pdf->getY(),$somacampos+10,$pdf->getY());
$pdf->Output();
?>