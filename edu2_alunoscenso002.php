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
$clmatricula = new cl_matricula;
$escola = db_getsession("DB_coddepto");
$where = "";
if($ensino!=""){
 $where .= " AND ensino.ed10_i_codigo = $ensino";
}
if($serie!=""){
 $where .= " AND serie.ed11_i_codigo = $serie";
}
$campos = str_replace(chr(92),"",$campos);
$data_matricula = substr($data_censo,6,4)."-".substr($data_censo,3,2)."-".substr($data_censo,0,2);
$cond = "calendario.ed52_i_ano = $ano_censo
         AND turma.ed57_i_escola = $escola $where
         AND (
         (ed60_d_datamatricula <= '$data_matricula' AND ed60_c_situacao = 'MATRICULADO')
          OR
         (ed60_d_datamatricula <= '$data_matricula' AND ed60_d_datasaida > '$data_matricula' AND ed60_c_situacao != 'MATRICULADO')
         )";
$result = $clmatricula->sql_record($clmatricula->sql_query("",$campos.",turma.ed57_i_codigo,ensino.ed10_i_codigo,ensino.ed10_c_descr,serie.ed11_i_codigo,serie.ed11_c_descr,ed60_c_situacao","ensino.ed10_i_tipoensino,ensino.ed10_c_descr,serie.ed11_i_sequencia,turma.ed57_c_descr,ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa"," $cond "));
$linhas = $clmatricula->numrows;
if($linhas==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
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
$head1 = "CENSO $ano_censo";
$head2 = "Alunos ativos em: $data_censo";
$head3 = "Filtro por Ensino: ".($ensino==""?"TODOS":trim(pg_result($result,0,'ed10_c_descr')));
$head4 = "Filtro por Etapa: ".($serie==""?"TODOS":trim(pg_result($result,0,'ed11_c_descr')));
$head5 = "Data: ".date("d/m/Y");
$cabecalho_campos = explode("|",$cabecalho);
$largura_campos = explode("|",$colunas);
$somacampos = 5;
for($t=0;$t<count($cabecalho_campos);$t++){
 $somacampos += $largura_campos[$t];
}
$cor1 = "0";
$cor2 = "1";
$cor = "";
$limite = $orientacao=="P"?60:38;
$cont = 0;
$alinhamento_campos = explode("|",$alinhamento);
$pri_ensino = "";
$pri_serie = "";
$pri_turma = "";
$pdf->addpage($orientacao);
$pdf->setfont('arial','b',6);
$pdf->cell($somacampos,4,"T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação",0,1,"L",0);
$cont++;
$pdf->setfont('arial','b',7);
for($x=0;$x<$linhas;$x++){
 db_fieldsmemory($result,$x);
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 if($pri_turma!=$ed57_i_codigo){
  $camposeq = 1;
  $pri_turma = $ed57_i_codigo;
 }
 $pdf->setfont('arial','b',7);
 if($pri_ensino!=$ed10_i_codigo && $titulo_ensino=="yes"){
  $pdf->setfillcolor(210);
  $pdf->cell($somacampos,4,$ed10_c_descr,1,1,"L",1);
  $cont++;
  $pri_ensino = $ed10_i_codigo;
  if($limite==$cont){
   $cont = 0;
   $pdf->cell($somacampos,0,"",1,1,"C",0);
   $pdf->addpage($orientacao);
   $pdf->setfont('arial','b',6);
   $pdf->cell($somacampos,4,"T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação",0,1,"L",0);
   $pdf->setfont('arial','b',7);
   $cont++;
   $pdf->setfillcolor(210);
   $pdf->cell($somacampos,4,$ed10_c_descr,1,1,"L",1);
   $cont++;
  }
 }
 if($pri_serie!=$ed11_i_codigo && $titulo_serie=="yes"){
  $pdf->setfillcolor(225);
  $pdf->cell($somacampos,4,$ed11_c_descr,1,1,"L",1);
  $cont++;
  if($limite==$cont){
   $cont = 0;
   $pdf->cell($somacampos,0,"",1,1,"C",0);
   $pdf->addpage($orientacao);
   $pdf->setfont('arial','b',6);
   $pdf->cell($somacampos,4,"T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação",0,1,"L",0);
   $cont++;
   $pdf->setfont('arial','b',7);
   if($titulo_ensino=="yes"){
    $pdf->setfillcolor(210);
    $pdf->cell($somacampos,4,$ed10_c_descr,1,1,"L",1);
    $cont++;
   }
   $pdf->setfillcolor(225);
   $pdf->cell($somacampos,4,$ed11_c_descr,1,1,"L",1);
   $cont++;
  }
  $pri_serie = $ed11_i_codigo;
  $cor = "0";
 }
 if($cont<2 || ($cont<3 && $titulo_serie=="yes") || $x==0){
  //cabeçalho
  for($t=0;$t<count($cabecalho_campos);$t++){
   if($t==0){
    $pdf->cell(5,4,"Seq",1,0,"C",0);
   }
   if($t==(count($cabecalho_campos)-1)){
    $next = 1;
   }else{
    $next = 0;
   }
   $pdf->cell($largura_campos[$t],4,$cabecalho_campos[$t],1,$next,"C",0);
  }
  $cont++;
 }
 if($limite==$cont){
  $cont = 0;
  $pdf->cell($somacampos,0,"",1,1,"C",0);
  $pdf->addpage($orientacao);
  $pdf->setfont('arial','b',6);
  $pdf->cell($somacampos,4,"T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação",0,1,"L",0);
  $cont++;
  $pdf->setfont('arial','b',7);
  if($titulo_ensino=="yes"){
   $pdf->setfillcolor(210);
   $pdf->cell($somacampos,4,$ed10_c_descr,1,1,"L",1);
   $cont++;
  }
  if($titulo_serie=="yes"){
   $pdf->setfillcolor(225);
   $pdf->cell($somacampos,4,$ed11_c_descr,1,1,"L",1);
   $cont++;
  }
  //cabeçalho
  for($t=0;$t<count($cabecalho_campos);$t++){
   if($t==0){
    $pdf->cell(5,4,"Seq",1,0,"C",0);
   }
   if($t==(count($cabecalho_campos)-1)){
    $next = 1;
   }else{
    $next = 0;
   }
   $pdf->cell($largura_campos[$t],4,$cabecalho_campos[$t],1,$next,"C",0);
  }
  $cont++;
  $cor = "0";
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',$tamfonte);
 //dados
 for($t=0;$t<count($cabecalho_campos);$t++){
  if($t==0){
   $pdf->cell(5,4,$camposeq,"LR",0,"C",$cor);
  }
  if($t==(count($cabecalho_campos)-1)){
   $next = 1;
  }else{
   $next = 0;
  }
  $pdf->cell($largura_campos[$t],4,(pg_field_type($result,$t)=="date"?db_formatar(pg_result($result,$x,$t),'d'):pg_result($result,$x,$t)),"LR",$next,$alinhamento_campos[$t],$cor);
 }
 $cont++;
 if($limite==$cont){
  $cont = 0;
  $pdf->cell($somacampos,0,"",1,1,"C",0);
  $pdf->addpage($orientacao);
  $pdf->setfont('arial','b',6);
  $pdf->cell($somacampos,4,"T=Transporte Escolar Z=Zona(R=RURAL U=URBANA)  NE=Necessidades Especiais  R=Rendimento Anterior(A=APROV R=REPROV S=SEM INFORMAÇÕES)  Sx=Sexo  St= Situação",0,1,"L",0);
  $cont++;
  $pdf->setfont('arial','b',7);
  if($titulo_ensino=="yes"){
   $pdf->setfillcolor(210);
   $pdf->cell($somacampos,4,$ed10_c_descr,1,1,"L",1);
   $cont++;
  }
  if($titulo_serie=="yes"){
   $pdf->setfillcolor(225);
   $pdf->cell($somacampos,4,$ed11_c_descr,1,1,"L",1);
   $cont++;
  }
  $pdf->setfont('arial','b',7);
  for($t=0;$t<count($cabecalho_campos);$t++){
   if($t==0){
    $pdf->cell(5,4,"Seq",1,0,"C",0);
   }
   if($t==(count($cabecalho_campos)-1)){
    $next = 1;
   }else{
    $next = 0;
   }
   $pdf->cell($largura_campos[$t],4,$cabecalho_campos[$t],1,$next,"C",0);
  }
  $cor = "1";
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',$tamfonte);
  $cont++;
 }
 $camposeq++;
}
$pdf->line(10,$pdf->getY(),$somacampos+10,$pdf->getY());
$pdf->setfont('arial','b',7);
$pdf->setfillcolor(210);
$pdf->cell($somacampos,4,"Totalizadores",1,1,"L",1);
if($tt_ensino=="yes" || $tt_serie=="yes"){
 $campos = "count(*) as totalserie,ensino.ed10_i_codigo,ensino.ed10_c_abrev,ensino.ed10_c_descr,serie.ed11_i_codigo,serie.ed11_c_descr,serie.ed11_i_sequencia";
 $result = $clmatricula->sql_record($clmatricula->sql_query("",$campos,"ensino.ed10_i_tipoensino,ensino.ed10_c_descr,serie.ed11_i_sequencia"," calendario.ed52_i_ano = $ano_censo AND turma.ed57_i_escola = $escola $where AND ((ed60_d_datamatricula <= '$data_matricula' AND ed60_c_situacao = 'MATRICULADO') OR ( ed60_d_datamatricula <= '$data_matricula' AND ed60_d_datasaida > '$data_matricula' AND ed60_c_situacao != 'MATRICULADO')) GROUP BY ensino.ed10_i_codigo,ensino.ed10_c_abrev,ensino.ed10_c_descr,serie.ed11_i_codigo,serie.ed11_c_descr,serie.ed11_i_sequencia,ensino.ed10_i_tipoensino"));
 $pri_ensino = pg_result($result,0,'ed10_i_codigo');
 $pri_ensinodescr = pg_result($result,0,'ed10_c_descr');
 $pri_serie = "";
 $soma_ensino = 0;
 $pdf->setfillcolor(235);
 for($x=0;$x<$clmatricula->numrows;$x++){
  db_fieldsmemory($result,$x);
  if($pri_ensino!=$ed10_i_codigo && $tt_ensino=="yes"){
   $pdf->cell(70,4,$pri_ensinodescr,1,0,"L",1);
   $pdf->setfont('arial','b',10);
   $pdf->cell(15,4,$soma_ensino,"LBT",0,"R",0);
   $pdf->cell($somacampos-85,4,"","RBT",1,"L",0);
   $pdf->cell($somacampos,2,"",1,1,"L",1);
   $pdf->setfont('arial','b',7);
   $soma_ensino = 0;
   $pri_ensino = $ed10_i_codigo;
   $pri_ensinodescr = $ed10_c_descr;
  }
  if($tt_serie=="yes"){
   $pdf->cell(10,4,"",1,0,"L",0);
   $pdf->cell(60,4,$ed11_c_descr,1,0,"L",1);
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,4,$totalserie,"LBT",0,"R",0);
   $pdf->cell($somacampos-85,4,"","RBT",1,"L",0);
   $pdf->setfont('arial','b',7);
  }
  $soma_ensino += $totalserie;
 }
 if($tt_ensino=="yes"){
  $pdf->cell(70,4,$pri_ensinodescr,1,0,"L",1);
  $pdf->setfont('arial','b',10);
  $pdf->cell(15,4,$soma_ensino,"LBT",0,"R",0);
  $pdf->cell($somacampos-85,4,"","RBT",1,"L",0);
  $pdf->cell($somacampos,2,"",1,1,"L",1);
 }
}
$pdf->setfont('arial','b',7);
$pdf->setfillcolor(210);
$pdf->cell(70,4,"TOTAL GERAL",1,0,"L",1);
$pdf->setfont('arial','b',10);
$pdf->cell(15,4,$linhas,"LTB",0,"R",1);
$pdf->cell($somacampos-85,4,"","RTB",1,"L",1);
$pdf->line(10,$pdf->getY(),$somacampos+10,$pdf->getY());
$pdf->Output();
?>