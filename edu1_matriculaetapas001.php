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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_matriculaserie_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_aluno_classe.php");
include("classes/db_base_classe.php");
include("classes/db_serie_classe.php");
include("classes/db_serieequiv_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_alunopossib_classe.php");
include("classes/db_historicomps_classe.php");
include("classes/db_edu_parametros_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
if(!isset($ed60_d_datamatricula_dia)){
 $ed60_d_datamatricula_dia = substr($datamat,0,2);
 $ed60_d_datamatricula_mes = substr($datamat,3,2);
 $ed60_d_datamatricula_ano = substr($datamat,6,4);
}
db_postmemory($HTTP_POST_VARS);
$clmatricula = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$cledu_parametros = new cl_edu_parametros;
$clmatriculaserie = new cl_matriculaserie;
$clcalendario = new cl_calendario;
$clturma = new cl_turma;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$claluno = new cl_aluno;
$clbase = new cl_base;
$clserie = new cl_serie;
$clserieequiv = new cl_serieequiv;
$clalunopossib = new cl_alunopossib;
$clalunocurso = new cl_alunocurso;
$clhistoricomps = new cl_historicomps;
$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("ed60_d_datamatricula");
$escola = db_getsession("DB_coddepto");
$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("","*","","ed233_i_escola = $escola"));
if($cledu_parametros->numrows>0){
 db_fieldsmemory($result_parametros,0);	
}else{
 echo "Erro! Parâmetros não informados";
 exit;	
}
if(isset($incluir)){
 $msg_mat = "";
 db_inicio_transacao();
 for($i=0;$i<count($codigoaluno);$i++){
  $result_tur = $clturma->sql_record($clturma->sql_query("","ed57_i_escola,ed57_i_base,ed57_i_calendario,ed57_i_turno,ed57_c_descr,fc_codetapaturma(ed57_i_codigo) as etapasturma",""," ed57_i_codigo = $turma"));
  db_fieldsmemory($result_tur,0);
  $erro_mat = false;
  $result_verif = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo as jatem,ed47_v_nome as nometem,turma.ed57_c_descr as turmatem,calendario.ed52_c_descr as caltem",""," ed60_i_aluno = $codigoaluno[$i] AND turma.ed57_i_calendario = $ed57_i_calendario"));
  if($clmatricula->numrows>0){
   db_fieldsmemory($result_verif,0);
   $msg_mat .= "ATENÇÃO:\\n\\nAluno(a) $nometem já está matriculado(a) na turma $turmatem no calendário $caltem!\\n\\n";
   $erro_mat = true;
  }elseif(VerUltimoRegHistorico($codigoaluno[$i],$etapaorigem[$i],$etapasturma)==true && $ed233_c_consistirmat=='S'){
   $msg_mat .= $msgequiv;// $msgequiv -> variável global da função VerUltimoRegHistorico
   $erro_mat = true;
   unset($msgequiv);
  }
  if($erro_mat==false){
   $result1 = $clalunopossib->sql_record($clalunopossib->sql_query("","ed56_i_codigo,ed79_i_codigo,ed79_c_resulant,ed79_i_turmaant",""," ed56_i_aluno = $codigoaluno[$i]"));
   db_fieldsmemory($result1,0);
   
   $ed79_i_turmaant = $ed79_i_turmaant=="0"?"":$ed79_i_turmaant;

   $result2 = $clmatricula->sql_record($clmatricula->sql_query_file("","max(ed60_i_numaluno)",""," ed60_i_turma = $turma"));
   db_fieldsmemory($result2,0);
   
   $max = $max==""?"":($max+1);
   $result3 = $clalunocurso->sql_record($clalunocurso->sql_query_file("","ed56_c_situacao as sitanterior",""," ed56_i_aluno = $codigoaluno[$i]"));
   
   $sitanterior = pg_result($result3,0,0);
   $sitmatricula = trim($sitanterior)=="CANDIDATO"?"MATRICULAR":"REMATRICULAR";
   $sitmatricula1 = trim($sitanterior)=="CANDIDATO"?"MATRICULADO":"REMATRICULADO";
   $tipomatricula = trim($sitanterior)=="CANDIDATO"?"N":"R";
   $ed79_i_turmaant = $ed79_i_turmaant==""?"null":$ed79_i_turmaant;
   $clmatricula->ed60_i_numaluno = $max;
   $clmatricula->ed60_i_aluno = $codigoaluno[$i];
   $clmatricula->ed60_i_turma = $turma;
   $clmatricula->ed60_c_situacao = "MATRICULADO";
   $clmatricula->ed60_c_concluida = "N";
   $clmatricula->ed60_t_obs = "";
   $clmatricula->ed60_i_turmaant = $ed79_i_turmaant;
   $clmatricula->ed60_c_rfanterior = $ed79_c_resulant;
   $clmatricula->ed60_d_datamodif = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
   $clmatricula->ed60_d_datamodifant = null;
   $clmatricula->ed60_d_datasaida = "null";
   $clmatricula->ed60_c_ativa = "S";
   $clmatricula->ed60_c_tipo = $tipomatricula;
   $clmatricula->ed60_c_parecer = "N";
   $clmatricula->ed60_matricula = null;
   $clmatricula->incluir(null);
   
   $ultima = $clmatricula->ed60_i_codigo;
   $clmatriculamov->ed229_i_matricula = $ultima;
   $clmatriculamov->ed229_i_usuario = db_getsession("DB_id_usuario");
   $clmatriculamov->ed229_c_procedimento = "$sitmatricula ALUNO";
   $clmatriculamov->ed229_t_descr = "ALUNO $sitmatricula1 NA TURMA $ed57_c_descr. SITUAÇÂO ANTERIOR: ".trim($sitanterior);
   $clmatriculamov->ed229_d_dataevento = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
   $clmatriculamov->ed229_c_horaevento = date("H:i");
   $clmatriculamov->ed229_d_data = date("Y-m-d",db_getsession("DB_datausu"));
   $clmatriculamov->incluir(null);
   $result_etapa = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie as codetapaturma",""," ed220_i_turma = $turma"));
   for($r=0;$r<$clturmaserieregimemat->numrows;$r++){
    db_fieldsmemory($result_etapa,$r);
    if($codetapaturma==$etapaorigem[$i]){
     $origem = "S";
    }else{
     $origem = "N";
    }
    $clmatriculaserie->ed221_i_matricula = $ultima;
    $clmatriculaserie->ed221_i_serie = $codetapaturma;
    $clmatriculaserie->ed221_c_origem = $origem;
    $clmatriculaserie->incluir(null);
   }
   $clalunocurso->ed56_c_situacao = "MATRICULADO";
   $clalunocurso->ed56_i_calendario = $ed57_i_calendario;
   $clalunocurso->ed56_i_base = $ed57_i_base;
   $clalunocurso->ed56_i_escola = $ed57_i_escola;
   $clalunocurso->ed56_i_codigo = $ed56_i_codigo;
   $clalunocurso->alterar($ed56_i_codigo);
   $clalunopossib->ed79_i_serie = $etapaorigem[$i];
   $clalunopossib->ed79_i_turno = $ed57_i_turno;
   $clalunopossib->ed79_i_codigo = $ed79_i_codigo;
   $clalunopossib->alterar($ed79_i_codigo);
   $sql2 = "UPDATE historico SET
             ed61_i_escola = $ed57_i_escola
            WHERE ed61_i_aluno = $codigoaluno[$i]
           ";
   $query2 = db_query($sql2);
  }
 }
 $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $turma AND ed60_c_situacao = 'MATRICULADO'"));
 db_fieldsmemory($result_qtd,0);
 $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
 $sql1 = "UPDATE turma SET
           ed57_i_nummatr = $qtdmatricula
          WHERE ed57_i_codigo = $turma
          ";
 $query1 = db_query($sql1);
 db_fim_transacao();
 if($msg_mat!=""){
  db_msgbox($msg_mat);
 }else{
  if($clmatricula->erro_status!="0"){
   $clmatricula->erro(true,false);
  }
 }
 ?>
 <script>
  parent.location.href = "edu1_matricula001.php?chavepesquisa=<?=$turma?>";
  parent.db_iframe_matric.hide();
 </script>
 <?
 exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Matricular Alunos</b></legend>
    <b>Informe a etapa de origem para cada aluno:</b><br>
    <table border="0" cellspacing="0" cellpadding="0">
    <?
    $escola = db_getsession("DB_coddepto");
    $result = $clturma->sql_record($clturma->sql_query("","ed52_d_inicio,ed52_d_fim",""," ed57_i_codigo = $turma"));
    db_fieldsmemory($result,0);
    $data = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;
    $inicio = $ed52_d_inicio;
    $fim = $ed52_d_fim;
    $result_etp = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie,ed11_c_descr as descretapa","ed223_i_ordenacao"," ed220_i_turma = $turma"));
    db_fieldsmemory($result_etp,0);
    $campos_sql = "DISTINCT ed47_i_codigo,ed47_v_nome,ed56_c_situacao,ed11_i_codigo,ed11_c_descr,ed10_c_abrev";
    $result = $claluno->sql_record($claluno->sql_query_matricula("",$campos_sql,"ed47_v_nome"," ed56_i_aluno in ($codalunos) AND ed56_i_escola = $escola"));
    $linhas_aluno = $claluno->numrows;
    for($t=0;$t<$claluno->numrows;$t++){
     db_fieldsmemory($result,$t);
     if($ed56_c_situacao=="APROVADO"){
      $sitdescr = "APROVADO (PARA $ed11_c_descr - $ed10_c_abrev)";
     }elseif($ed56_c_situacao=="REPETENTE"){
      $sitdescr = "REPETENTE (NA $ed11_c_descr - $ed10_c_abrev)";
     }elseif($ed56_c_situacao=="CANDIDATO"){
      $sitdescr = "CANDIDATO (NA $ed11_c_descr - $ed10_c_abrev)";
     }elseif($ed56_c_situacao=="APROVADO PARCIAL"){
       $sitdescr = "APROVADO PARCIALMENTE (NA $ed11_c_descr - $ed10_c_abrev)";
     }
     ?>
     <tr>
      <td>
       <b><?=$ed47_i_codigo." - ".$ed47_v_nome?></b>
      </td>
      <td>
       <b><?="&nbsp;&nbsp;&nbsp;---> ".$sitdescr?></b>
      </td>
      <td>
       &nbsp;&nbsp;&nbsp;--->
       <select name="etapaorigem[]" id="etapaorigem">
        <option value=""></option>
        <?
        $temequiv = false;
        $result_equiv = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $ed11_i_codigo"));
        for($r=0;$r<$clturmaserieregimemat->numrows;$r++){
         db_fieldsmemory($result_etp,$r);
         $selected = "";
         $disabled = "disabled";
         if($clserieequiv->numrows>0){
          for($w=0;$w<$clserieequiv->numrows;$w++){
           db_fieldsmemory($result_equiv,$w);
           if($ed234_i_serieequiv==$ed223_i_serie){
            $selected = "selected";
            $disabled = "";
            break;
           }
          }
         }
         if($ed11_i_codigo==$ed223_i_serie){
          $selected = "selected";
          $disabled = "";
         }
         if($disabled==""){
          $temequiv = true;
         }
         ?>
         <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
         <?
        }
        ?>
       </select>
       <?=$temequiv==false?"Etapa $ed11_c_descr não tem registros de etapas equivalentes":""?>
       <input name="codigoaluno[]" type="hidden" value="<?=$ed47_i_codigo?>">
      </td>
     </tr>
     <?
    }
    ?>
    <tr>
     <td colspan="3">
      <?=@$Led60_d_datamatricula?>
      <?db_inputdata('ed60_d_datamatricula',@$ed60_d_datamatricula_dia,@$ed60_d_datamatricula_mes,@$ed60_d_datamatricula_ano,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td colspan="3">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterarnada":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1?"onclick=\"return js_selecionar('$data','$inicio','$fim',$linhas_aluno)\"":"")?>  >
      <input name="turma" type="hidden" value="<?=$turma?>">
     </td>
    </tr> 
    </table>
   </fieldset>
   </form>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clmatricula->erro_status=="0"){
  $clmatricula->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clmatricula->erro_campo!=""){
   echo "<script> document.form1.".$clmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clmatricula->erro_campo.".focus();</script>";
  }
 }
}
?>
<script>
function js_selecionar(data,inicio,fim,linhasaluno){
 if(document.form1.ed60_d_datamatricula.value==""){
  alert("Informe a data para matricular o aluno!");
  document.form1.ed60_d_datamatricula.focus();
  document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
  return false;
 }else{
  datamat = document.form1.ed60_d_datamatricula_ano.value+"-"+document.form1.ed60_d_datamatricula_mes.value+"-"+document.form1.ed60_d_datamatricula_dia.value;
  dataini = inicio;
  datafim = fim;
  check = js_validata(datamat,dataini,datafim);
  if(check==false){
   data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
   data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
   alert("Data da matrícula fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
   document.form1.ed60_d_datamatricula.focus();
   document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
   return false;
  }
 }
 selec = false;
 if(linhasaluno==1){
  if(document.form1.etapaorigem.value==""){
   selec = true;
  }
 }else{
  for(i=0;i<linhasaluno;i++){
   if(document.form1.etapaorigem[i].value==""){
    selec = true;
    break;
   }
  }
 }
 if(selec==true){
  alert("Informe a etapa de origem para todos os alunos!");
  return false;
 }
 return true;
}
</script>