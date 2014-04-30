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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_matriculamov_classe.php");
require_once("classes/db_matriculaserie_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_turmaserieregimemat_classe.php");
require_once("classes/db_calendario_classe.php");
require_once("classes/db_aluno_classe.php");
require_once("classes/db_base_classe.php");
require_once("classes/db_serie_classe.php");
require_once("classes/db_alunocurso_classe.php");
require_once("classes/db_alunopossib_classe.php");
require_once("classes/db_historicomps_classe.php");
require_once("classes/db_edu_parametros_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");
require_once("libs/db_utils.php");
if(!isset($ed60_d_datamatricula_dia)){
 $ed60_d_datamatricula_dia = date("d",db_getsession("DB_datausu"));
 $ed60_d_datamatricula_mes = date("m",db_getsession("DB_datausu"));
 $ed60_d_datamatricula_ano = date("Y",db_getsession("DB_datausu"));
}
db_postmemory($HTTP_POST_VARS);
$clmatricula = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$clmatriculaserie = new cl_matriculaserie;
$clcalendario = new cl_calendario;
$clturma = new cl_turma;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$claluno = new cl_aluno;
$clbase = new cl_base;
$clserie = new cl_serie;
$clalunopossib = new cl_alunopossib;
$clalunocurso = new cl_alunocurso;
$clhistoricomps = new cl_historicomps;
$cledu_parametros = new cl_edu_parametros;
$oDaoMatricula = db_utils::getdao('matricula');
$db_opcao = 1;
$db_botao = false;
$escola = db_getsession("DB_coddepto");
$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("","*","","ed233_i_escola = $escola"));
if($cledu_parametros->numrows>0){
 db_fieldsmemory($result_parametros,0);	
}else{
 echo "Erro! Parâmetros não informados";
 exit;	
}
if(isset($incluir)){
 $tam = sizeof($alunos);
 if($tam>$restantes){
  db_msgbox("Número de alunos selecionados é maior que as vagas disponíveis");
  db_redireciona("edu1_matricula001.php?chavepesquisa=$ed60_i_turma");
 }else{
  db_inicio_transacao();
  $msg_mat = "";
  for($i=0;$i<$tam;$i++){
   $erro_mat = false;  
   $sSqlMatricula2 = "select fc_codetapaturma($ed60_i_turma) as etapasturma";
   $rsMatricula2   = $oDaoMatricula->sql_record($sSqlMatricula2);
   db_fieldsmemory($rsMatricula2, 0);
   $result_verif = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo as jatem,ed47_v_nome as nometem,turma.ed57_c_descr as turmatem,calendario.ed52_c_descr as caltem",""," ed60_i_aluno = $alunos[$i] AND turma.ed57_i_calendario = $ed57_i_calendario AND ed60_c_situacao != 'AVANÇADO' AND ed60_c_situacao != 'CLASSIFICADO'"));
   if($clmatricula->numrows>0){
    db_fieldsmemory($result_verif,0);
    $msg_mat .= "ATENÇÃO:\\n\\nAluno(a) $nometem já está matriculado(a) na turma $turmatem no calendário $caltem!\\n\\n";
    $erro_mat = true;
   }elseif(VerUltimoRegHistorico($alunos[$i],$codetapa,$etapasturma)==true && $ed233_c_consistirmat=='S'){
    $msg_mat .= $msgequiv;// $msgequiv -> variável global da função VerUltimoRegHistorico
    $erro_mat = true;
    unset($msgequiv);
   }
   if($erro_mat==false){
    $result1 = $clalunopossib->sql_record($clalunopossib->sql_query("","ed56_i_codigo,ed79_i_codigo,ed79_c_resulant,ed79_i_turmaant",""," ed56_i_aluno = $alunos[$i]"));
    db_fieldsmemory($result1,0);
    $ed79_i_turmaant = $ed79_i_turmaant=="0"?"":$ed79_i_turmaant;
    $result2 = $clmatricula->sql_record($clmatricula->sql_query_file("","max(ed60_i_numaluno)",""," ed60_i_turma = $ed60_i_turma"));
    db_fieldsmemory($result2,0);
    $max = $max==""?"":($max+1);
    $result3 = $clalunocurso->sql_record($clalunocurso->sql_query_file("","ed56_c_situacao as sitanterior",""," ed56_i_aluno = $alunos[$i]"));
    $sitanterior = pg_result($result3,0,0);
    $sitmatricula = trim($sitanterior)=="CANDIDATO"?"MATRICULAR":"REMATRICULAR";
    $sitmatricula1 = trim($sitanterior)=="CANDIDATO"?"MATRICULADO":"REMATRICULADO";
    $tipomatricula = trim($sitanterior)=="CANDIDATO"?"N":"R";
    $ed79_i_turmaant = $ed79_i_turmaant==""?"null":$ed79_i_turmaant;
    $clmatricula->ed60_i_numaluno = $max;
    $clmatricula->ed60_i_aluno = $alunos[$i];
    $clmatricula->ed60_c_situacao = "MATRICULADO";
    $clmatricula->ed60_c_concluida = "N";
    $clmatricula->ed60_t_obs = "";
    $clmatricula->ed60_i_turmaant = $ed79_i_turmaant;
    $clmatricula->ed60_c_rfanterior = $ed79_c_resulant;
    $clmatricula->ed60_d_datamodif = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
    $clmatricula->ed60_d_datamodifant = null;
    $clmatricula->ed60_d_datasaida = null;
    $clmatricula->ed60_c_ativa = "S";
    $clmatricula->ed60_c_tipo = $tipomatricula;
    $clmatricula->ed60_c_parecer = "N";
    $clmatricula->ed60_matricula = null;
    $clmatricula->ed60_i_codigo  = null;
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
    $clmatriculaserie->ed221_i_matricula = $ultima;
    $clmatriculaserie->ed221_i_serie = $codetapa;
    $clmatriculaserie->ed221_c_origem = "S";
    $clmatriculaserie->incluir(null);
    $clalunocurso->ed56_c_situacao = "MATRICULADO";
    $clalunocurso->ed56_i_calendario = $ed57_i_calendario;
    $clalunocurso->ed56_i_base = $ed57_i_base;
    $clalunocurso->ed56_i_escola = $ed57_i_escola;
    $clalunocurso->ed56_i_codigo = $ed56_i_codigo;
    $clalunocurso->alterar($ed56_i_codigo);
    $clalunopossib->ed79_i_serie = $codetapa;
    $clalunopossib->ed79_i_turno = $ed57_i_turno;
    $clalunopossib->ed79_i_codigo = $ed79_i_codigo;
    $clalunopossib->alterar($ed79_i_codigo);
    $sql2 = "UPDATE historico SET
              ed61_i_escola = $ed57_i_escola
             WHERE ed61_i_aluno = $alunos[$i]
            ";
    $query2 = pg_query($sql2);
   }
  }
  $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turma SET
            ed57_i_nummatr = $qtdmatricula
           WHERE ed57_i_codigo = $ed60_i_turma
           ";
  $query1 = pg_query($sql1);
  db_fim_transacao();
  if($msg_mat!=""){
   db_msgbox($msg_mat);
  }else{
   if($clmatricula->erro_status!="0"){
    $clmatricula->erro(true,false);
   }
  }
  db_redireciona("edu1_matricula001.php?chavepesquisa=$ed60_i_turma");
  exit;
 }
}elseif(isset($chavepesquisa)){
 $db_botao = false;
 $camp = "turma.*,
          calendario.*,
          base.*,
          cursoedu.*,
          turno.*,
          fc_nomeetapaturma(ed57_i_codigo) as nometapa,
          fc_codetapaturma(ed57_i_codigo) as codetapa
         ";
 $result = $clturma->sql_record($clturma->sql_query("",$camp,""," ed57_i_codigo = $chavepesquisa"));
 db_fieldsmemory($result,0);
 $ed60_i_turma = $ed57_i_codigo;
 $result1 = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) ",""," ed60_i_turma = $ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'"));
 db_fieldsmemory($result1,0);
 $ed57_i_nummatr = $count;
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a2.style.color = "black";
  top.corpo.iframe_a2.location.href='edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
 </script>
 <?
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
   <fieldset style="width:95%"><legend><b>Matricular Aluno</b></legend>
    <?include("forms/db_frmmatricula.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed60_i_turma",true,1,"ed60_i_turma",true);
</script>
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