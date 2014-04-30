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
include("classes/db_procescola_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocescola = new cl_procescola;
$clescola = new cl_escola;
$clturma = new cl_turma;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 $result = $clprocescola->sql_record($clprocescola->sql_query("","ed18_c_nome",""," ed86_i_procedimento = $ed86_i_procedimento AND ed86_i_escola = $ed86_i_escola"));
 if($clprocescola->numrows>0){
  db_msgbox(" Escola $ed18_c_nome já está vinculada ao procedimento $ed40_c_descr!");
  echo "<script>location.href='".$clprocescola->pagina_retorno."'</script>";
 }else{
  db_inicio_transacao();
  $clprocescola->incluir($ed86_i_codigo);
  db_fim_transacao();
 }
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clprocescola->alterar($ed86_i_codigo);
  db_fim_transacao();
}
if(isset($excluir)){
 $result = $clturma->sql_record($clturma->sql_query_turmaserie("","*",""," ed220_i_procedimento = $ed86_i_procedimento"));
 if($clturma->numrows>0){
  $clprocescola->erro_status = "0";
  $clprocescola->erro_msg = "Já existem turmas vinculadas a este Procedimento de Avaliação. Exclusão não permitida!\\n";
 }else{
  db_inicio_transacao();
  $db_opcao = 3;
  $clprocescola->excluir($ed86_i_codigo);
  db_fim_transacao();
 }
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
   <fieldset style="width:95%"><legend><b>Escolas vinculadas ao Procedimento de Avaliação <?=@$ed40_c_descr?></b></legend>
    <?include("forms/db_frmprocescola.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clprocescola->erro_status=="0"){
  $clprocescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocescola->erro_campo!=""){
   echo "<script> document.form1.".$clprocescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocescola->erro_campo.".focus();</script>";
  }
 }else{
  $clprocescola->erro(true,true);
 }
}
if(isset($alterar)){
 if($clprocescola->erro_status=="0"){
  $clprocescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocescola->erro_campo!=""){
   echo "<script> document.form1.".$clprocescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocescola->erro_campo.".focus();</script>";
  }
 }else{
  $clprocescola->erro(true,true);
 }
}
if(isset($excluir)){
 if($clprocescola->erro_status=="0"){
  $clprocescola->erro(true,false);
 }else{
  $clprocescola->erro(true,false);
  ?>
  <script>
   parent.location.href='edu1_procedimentoabas002.php';
  </script>
  <?
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clprocescola->pagina_retorno."'</script>";
}
?>