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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_alunopossib_classe.php");
include("classes/db_escola_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clalunocurso = new cl_alunocurso;
$clalunopossib = new cl_alunopossib;
$clescola = new cl_escola;
$db_opcao = 1;
$db_botao = true;
$ed56_i_escola = db_getsession("DB_coddepto");
$result = $clescola->sql_record($clescola->sql_query_file("","ed18_c_nome",""," ed18_i_codigo = $ed56_i_escola"));
db_fieldsmemory($result,0);
if(isset($incluir)){
 db_inicio_transacao();
 $clalunocurso->ed56_c_situacao = "CANDIDATO";
 $clalunocurso->incluir($ed56_i_codigo);
 $result = @pg_query("select last_value from alunocurso_ed56_i_codigo_seq");
 $max = pg_result($result,0,0);
 if(trim($matricula)=="SÉRIE"){
  $clalunopossib->ed79_i_alunocurso = $max;
  $clalunopossib->ed79_c_situacao = "A";
  $clalunopossib->incluir(@$ed79_i_codigo);
 }
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clalunocurso->ed56_c_situacao = "CANDIDATO";
  $clalunocurso->alterar($ed56_i_codigo);
  $clalunopossib->ed79_i_alunocurso = $ed56_i_codigo;
  $clalunopossib->ed79_c_situacao = "A";
  if($ed79_i_codigo==""){
   $clalunopossib->incluir(@$ed79_i_codigo);
  }else{
   $clalunopossib->alterar(@$ed79_i_codigo);
  }
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clalunopossib->excluir(""," ed79_i_alunocurso = $ed56_i_codigo");
  $clalunocurso->excluir($ed56_i_codigo);
  db_fim_transacao();
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
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Cursos do Aluno <?=@$ed47_v_nome?></b></legend>
    <?include("forms/db_frmalunocurso.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 $temerro = false;
 if($clalunocurso->erro_status=="0"){
  $clalunocurso->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunocurso->erro_campo!=""){
   echo "<script> document.form1.".$clalunocurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunocurso->erro_campo.".focus();</script>";
  };
  $temerro = true;
  if(trim($matricula)=="DISCIPLINA"){
   ?>
   <script>
    document.getElementById('Serie').style.visibility = "hidden";
    document.getElementById('Turno').style.visibility = "hidden";
    document.form1.matricula.value = '<?=$matricula?>';
   </script>
   <?
  }else{
   echo "<script> document.form1.matricula.value = '".$matricula."';</script>";
  }
 }
 if($clalunopossib->erro_status=="0"){
  $clalunopossib->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunopossib->erro_campo!=""){
   echo "<script> document.form1.".$clalunopossib->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunopossib->erro_campo.".focus();</script>";
  };
  $temerro = true;
  if(trim($matricula)=="SÉRIE"){
   echo "<script> document.form1.matricula.value = '".$matricula."';</script>";
  }
 }
 if($temerro==true){
  db_fim_transacao($temerro);
 }else{
  db_fim_transacao();
  ?>
  <script>
  top.corpo.iframe_a1.location.href='edu1_alunodados002.php?chavepesquisa=<?=$ed56_i_aluno?>';
  top.corpo.iframe_a2.location.href='edu1_aluno002.php?chavepesquisa=<?=$ed56_i_aluno?>';
  top.corpo.iframe_a4.location.href='edu1_docaluno001.php?ed49_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a5.location.href='edu1_alunonecessidade001.php?ed214_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a6.location.href='edu1_historico000.php?ed61_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
  </script>
  <?
  $clalunocurso->erro(true,true);
 }
};
if(isset($alterar)){
 $temerro = false;
 if($clalunocurso->erro_status=="0"){
  $clalunocurso->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunocurso->erro_campo!=""){
   echo "<script> document.form1.".$clalunocurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunocurso->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if($clalunopossib->erro_status=="0"){
  $clalunopossib->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunopossib->erro_campo!=""){
   echo "<script> document.form1.".$clalunopossib->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunopossib->erro_campo.".focus();</script>";
  };
  $temerro = true;
 }
 if($temerro==true){
  db_fim_transacao($temerro);
 }else{
  db_fim_transacao();
  $clalunocurso->erro(true,true);
 }
};
if(isset($excluir)){
  if($clalunocurso->erro_status=="0"){
    $clalunocurso->erro(true,false);
  }else{
    $clalunocurso->erro(true,true);
  };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clalunocurso->pagina_retorno."'</script>";
}
?>