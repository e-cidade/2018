<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_stdlibwebseller.php");
include("classes/db_turmaacmatricula_classe.php");
include("classes/db_alunonecessidade_classe.php");
include("classes/db_regenteconselho_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_censoativcompl_classe.php");
include("classes/db_turmaacativ_classe.php");
include("classes/db_turmaachorario_classe.php");
include("classes/db_edu_parametros_classe.php");
$clturmaacmatricula = new cl_turmaacmatricula;
$clcensoativcompl = new cl_censoativcompl;
$clturmaacativ = new cl_turmaacativ;
$clturmaachorario = new cl_turmaachorario;
$clregenteconselho = new cl_regenteconselho;
$clturma = new cl_turma;
$clturmaac = new cl_turmaac;
$clescola = new cl_escola;
$cledu_parametros = new cl_edu_parametros;
$clalunonecessidade = new cl_alunonecessidade;
$escola = db_getsession("DB_coddepto");
$result = $clturmaac->sql_record($clturmaac->sql_query("","*","ed268_c_descr"," ed268_i_codigo in ($turmas)"));
if($clturmaac->numrows==0){?>
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
$ano_calendario = pg_result($result,0,'ed52_i_ano');
$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("","ed233_c_database,ed233_c_limitemov",""," ed233_i_escola = $escola"));
if($cledu_parametros->numrows>0){
 db_fieldsmemory($result_parametros,0);
 if(!strstr($ed233_c_database,"/") || !strstr($ed233_c_limitemov,"/")){
  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Par�metros Dia/M�s Limite da Movimenta��o e Data Base para C�lculo da Idade (Procedimentos->Par�metros)<br>
         devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
         Valor atual do par�metro Dia/M�s Limite da Movimenta��o: <?=trim($ed233_c_limitemov)==""?"N�o informado":$ed233_c_limitemov?><br>
         Valor atual do par�metro Data Base para C�lculo da Idade: <?=trim($ed233_c_database)==""?"N�o informado":$ed233_c_database?><br><br></b>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
 }
 $database  = explode("/",$ed233_c_database);
 $limitemov  = explode("/",$ed233_c_limitemov);
 $dia_database = $database[0];
 $mes_database = $database[1];
 $dia_limitemov = $limitemov[0];
 $mes_limitemov = $limitemov[1];
 if(@!checkdate($mes_database,$dia_database,$ano_calendario) || @!checkdate($mes_limitemov,$dia_limitemov,$ano_calendario)){
  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Par�metros Dia/M�s Limite da Movimenta��o e Data Base para C�lculo da Idade (Procedimentos->Par�metros)<br>
         devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e devem ser uma data v�lida.<br><br>
         Valor atual do par�metro Dia/M�s Limite da Movimenta��o: <?=trim($ed233_c_limitemov)==""?"N�o informado":$ed233_c_limitemov?><br>
         Valor atual do par�metro Data Base para C�lculo da Idade: <?=trim($ed233_c_database)==""?"N�o informado":$ed233_c_database?><br><br>
         Data Limite da Movimenta��o: <?=$dia_limitemov."/".$mes_limitemov."/".$ano_calendario?> <?=@!checkdate($mes_limitemov,$dia_limitemov,$ano_calendario)?"(Data Inv�lida)":"(Data V�lida)"?><br>
         Data Base para C�lculo Idade: <?=$dia_database."/".$mes_database."/".$ano_calendario?> <?=@!checkdate($mes_database,$dia_database,$ano_calendario)?"(Data Inv�lida)":"(Data V�lida)"?><br><br></b>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
 }
 $databasecalc = $ano_calendario."-".(strlen($mes_database)==1?"0".$mes_database:$mes_database)."-".(strlen($dia_database)==1?"0".$dia_database:$dia_database);
 $datalimitemov = $ano_calendario."-".(strlen($mes_limitemov)==1?"0".$mes_limitemov:$mes_limitemov)."-".(strlen($dia_limitemov)==1?"0".$dia_limitemov:$dia_limitemov);
}else{
 $databasecalc = $ano_calendario."-12-31";
 $datalimitemov = $ano_calendario."-01-01";
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
for($x=0;$x<$clturmaac->numrows;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfillcolor(223);
 $head1 = "LISTA TURMAS ATIVIDADE COMPLEMENTAR/AEE";
 $head2 = "Turma: $ed268_c_descr";
 $head3 = "C�digo do INEP: $ed268_i_codigoinep";
 $head4 = "Turno: $ed15_c_nome";

 switch ($ed268_i_ativqtd) {

   case 1:

     $descr="UMA VEZ POR SEMANA";
     break;
   case 2:

     $descr="DUAS VEZES POR SEMANA";
     break;

   case 3:

     $descr="TR�S VEZES POR SEMANA";
     break;


   case 4:

     $descr="QUATRO VEZES POR SEMANA";
     break;

   case 5:

     $descr="CINCO VEZES POR SEMANA";
     break;
 }

 $head5 = "Quantidade: $descr";
 if($ed268_i_tipoatend==4){
 	$ed268_i_tipoatend="ATIVIDADE COMPLEMENTAR";
 }else{
 	$ed268_i_tipoatend="ATENDIMENTO EDUCACIONAL ESPECIAL - AEE";
 }
 $head6 = "Tipo de Atendimento: $ed268_i_tipoatend";
 $pdf->addpage();
 $pdf->setfont('arial','b',8);
 $limite = 55;
 $somacampos = 0;
 $cont=0;
 $total=0;
 $total2=0;
 $d=0;
 $campodtsaida = "ed47_i_codigo, ed47_v_nome, ed47_d_nasc,ed47_v_sexo, ed269_d_data, fc_idade(ed47_d_nasc,'$databasecalc'::date) as idadealuno";
 $sql2 = $clturmaacmatricula->sql_query_turma("",$campodtsaida,"to_ascii(ed47_v_nome)"," ed269_i_turmaac = $ed268_i_codigo");
 $result2 = $clturmaacmatricula->sql_record($sql2);
 $pdf->setfont('arial','b',8);
 $pdf->cell(15,4,"Cod",1,0,"C",0);
 $pdf->cell(20,4,"Dt.Entrada",1,0,"C",0);
 $pdf->cell(5,4,"E",1,0,"C",0);
 $pdf->cell(20,4,"Dt.Saida",1,0,"C",0);
 $pdf->cell(5,4,"S",1,0,"C",0);
 $pdf->cell(10,4,"Sexo",1,0,"C",0);
 $pdf->cell(85,4,"Alunos",1,0,"C",0);
 $pdf->cell(10,4,"ID",1,0,"C",0);
 $pdf->cell(20,4,"Dt.Nasc",1,0,"C",0);
 $pdf->cell(5,4,"I",1,1,"C",0);
 $pdf->setfont('arial','',8);
 for($p=0;$p<$clturmaacmatricula->numrows;$p++){

  db_fieldsmemory($result2,$p);
  $datasaida = '';
  if ($cont == $limite) {

   $pdf->addpage('P');
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,4,"Cod",1,0,"C",0);
   $pdf->cell(20,4,"Dt.Entrada",1,0,"C",0);
   $pdf->cell(5,4,"E",1,0,"C",0);
   $pdf->cell(20,4,"Dt.Saida",1,0,"C",0);
   $pdf->cell(5,4,"S",1,0,"C",0);
   $pdf->cell(10,4,"Sexo",1,0,"C",0);
   $pdf->cell(85,4,"Alunos",1,0,"C",0);
   $pdf->cell(10,4,"ID",1,0,"C",0);
   $pdf->cell(20,4,"Dt.Nasc",1,0,"C",0);
   $pdf->cell(5,4,"I",1,1,"C",0);
   $pdf->setfont('arial','',8);
   $cont=0;
  }
  if($d!=$datasaida){
   $total2 +=1;
  }
  $pdf->setfont('arial','',8);
  if ($datasaida == "" || ($datasaida !="" && $datasaida > $datalimitemov)) {

   $pdf->cell(15, 4, $ed47_i_codigo, 1, 0, "C", 0);
   $pdf->cell(20, 4, db_formatar($ed269_d_data, 'd'), 1, 0, "C", 0);
   $pdf->cell(5, 4, "", 1, 0, "C", 0);
   $pdf->cell(20,4, $datasaida, 1, 0, "C", 0);
   $pdf->cell(5, 4, " ", 1, 0, "C", 0);
   $pdf->cell(10, 4, $ed47_v_sexo, 1, 0, "C", 0);
   $pdf->cell(85, 4, $ed47_v_nome, 1, 0, "L", 0);
   $pdf->cell(10,4,$idadealuno,1,0,"C",0);
   $pdf->cell(20, 4, db_formatar($ed47_d_nasc,'d'), 1, 0, "C", 0);
   $sSqlAlunoComNecessidade = $clalunonecessidade->sql_query("", "ed214_i_codigo",
                                                             "",
                                                             "ed214_i_aluno = $ed47_i_codigo"
                                                            );
   $rsAlunoComNecessidades = $clalunonecessidade->sql_record($sSqlAlunoComNecessidade);
   $inclusao               = $clalunonecessidade->numrows > 0 ? "*" : "";
   $pdf->setfont('arial', 'b', 10);
   $pdf->cell(5, 4, $inclusao, 1, 1, "C", 0);
   $pdf->setfont('arial', '', 8);
   $cont++;
   $total +=1;
  }
 }
 $final = ($total - $total2);
 for($p=$total;$p<$limite;$p++){
  $pdf->cell(15,4,"",1,0,"C",0);
  $pdf->cell(20,4,"",1,0,"C",0);
  $pdf->cell(5,4,"",1,0,"C",0);
  $pdf->cell(20,4,"",1,0,"C",0);
  $pdf->cell(5,4,"",1,0,"C",0);
  $pdf->cell(10,4,"",1,0,"C",0);
  $pdf->cell(85,4,"",1,0,"C",0);
  $pdf->cell(10,4,"",1,0,"C",0);
  $pdf->cell(20,4,"",1,0,"C",0);
  $pdf->cell(5,4,"",1,1,"C",0);
 }
 $pdf->setfont('arial','',7);
 $pdf->cell(95,4,"ALUNOS ATIVOS: " .$final,0,0,"L",0);
 $pdf->cell(95,4,"ID = Idade no ano  I = Aluno de Inclus�o",0,1,"R",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(190,4,"",0,1,"L",0);
 $pdf->addpage();
 $pdf->setfont('arial','b',8);
 //aqui verificar o tipo de atendimento
 $pdf->cell(20,4,"Matr�cula/CGM",1,0,"C",1);
 $pdf->cell(170,4,"Docentes",1,1,"C",1);
 $resultturmaac = $clturmaachorario->sql_record($clturmaachorario->sql_query("","distinct case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao, case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome","","ed268_i_codigo=$ed268_i_codigo"));
 if($clturmaachorario->numrows>0){
 for($w=0;$w<$clturmaachorario->numrows;$w++){
   db_fieldsmemory($resultturmaac,$w);
   $pdf->cell(20,4,$identificacao,1,0,"C",0);
   $pdf->cell(170,4,$z01_nome,1,1,"C",0);
 }
 }else{
 	$pdf->cell(190,4,"NEHUM PROFISSIONAL/MONITOR INFORMADO.",1,1,"C",0);
 }
 if($ed268_i_tipoatend==4){
 	$pdf->cell(190,4,"",0,0,"L",0);
 	$pdf->cell(190,4,"",0,1,"L",0);
 	$pdf->cell(190,4,"Atividades/Atendimento",1,1,"C",1);
 	$resultturmaacativ = $clturmaacativ->sql_record($clturmaacativ->sql_query("","*","","ed267_i_turmaac=$ed268_i_codigo"));
    if($clturmaacativ->numrows>0){
 	for($k=0;$k<$clturmaacativ->numrows;$k++){
 	  db_fieldsmemory($resultturmaacativ,$k);
 	  $pdf->cell(190,4,$ed133_c_descr,1,1,"C",0);
 	}
    }else{
    	$pdf->cell(190,4,"NEHUMA ATIVIDADE COMPLEMENTAR INFORMADA.",1,1,"C",0);
    }
 }else{
 	$pdf->setfillcolor(215);
 	$pdf->cell(190,4,"",0,0,"L",0);
 	$pdf->cell(190,4,"",0,1,"L",0);
 	$pdf->cell(190,4,"Atividades/Atendimento",1,1,"C",1);
 	$outro=array('0'=>'Sistema Braile',
 	             '1'=>'Atividades da vida aut�noma',
 	             '2'=>'Recursos para alunos com baixa vis�o',
 	             '3'=>'Desenvolvimento de processos mentais',
 	             '4'=>'Orienta��o e mobilidade',
 	             '5'=>'L�ngua Brasileira de Sinais',
 	             '6'=>'Comunica��o alternativa e aumentativa',
 	             '7'=>'Atividades de enriquecimento curricular',
 	             '8'=>'Soroban',
 	             '9'=>'Inform�tica acess�vel',
 	             '10'=>'L�ngua Portuguesa na modalidade escrita');
    for($r=0;$r<11;$r++){
 	   if(substr($ed268_c_aee,$r,1)==1){
 	    $pdf->cell(190,4,$outro[$r],1,1,"C",0);
 	   }
    }
 }
 $rest= ($ed268_i_numvagas - $final);
 $pdf->cell(60,4,"Vagas restantes: ".$rest ,0,1,"L",0);
 $pdf->cell(60,4,"Total de vagas: ". $ed268_i_numvagas,0,0,"L",0);
}
$pdf->Output();
?>