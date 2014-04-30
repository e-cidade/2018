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
include("classes/db_ensino_classe.php");
include("classes/db_disciplina_classe.php");
include("classes/db_caddisciplina_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clensino = new cl_ensino;
$cldisciplina = new cl_disciplina;
$clcaddisciplina = new cl_caddisciplina;
$db_opcao = 1;
$db_botao = true;
$result0 = $clensino->sql_record($clensino->sql_query_file("","ed10_c_descr",""," ed10_i_codigo = $ed12_i_ensino"));
db_fieldsmemory($result0,0);
if(isset($incluir)){
 $msg_erro = "";
 $tamanho = explode("-",$registros);
 for($x=0;$x<count($tamanho);$x++){
  $array_registro = explode("|",$tamanho[$x]);
  $result2 = $cldisciplina->sql_record($cldisciplina->sql_query("","ed232_c_descr as caddescr",""," ed12_i_ensino = $ed12_i_ensino AND ed12_i_caddisciplina = $array_registro[1]"));
  $linhas2 = $cldisciplina->numrows;
  if(trim($array_registro[0])=="false"){
   if($linhas2>0){
    db_fieldsmemory($result2,0);
    db_inicio_transacao();
    $cldisciplina->excluir(""," ed12_i_ensino = $ed12_i_ensino AND ed12_i_caddisciplina = $array_registro[1]");
    db_fim_transacao();
    if($cldisciplina->erro_status=="0"){
     $msg_erro .= " -> $caddescr\\n";
     $erro = true;
    }
   }
  }else{
   if($linhas2==0){
    db_inicio_transacao();
    $cldisciplina->ed12_i_ensino = $ed12_i_ensino;
    $cldisciplina->ed12_i_caddisciplina = $array_registro[1];
    $cldisciplina->incluir(null);
    db_fim_transacao();
   }else{
    db_inicio_transacao();
    $cldisciplina->ed12_i_ensino = $ed12_i_ensino;
    $cldisciplina->ed12_i_caddisciplina = $array_registro[1];
    $cldisciplina->ed12_i_codigo = $array_registro[0];
    $cldisciplina->alterar($array_registro[0]);
    db_fim_transacao();
   }
  }
 }
 if($msg_erro!=""){
  db_msgbox("ATENÇÃO!!\\nDisciplina(s) não podem ser excluídas deste ensino:\\n\\n".$msg_erro."\\nDisciplina(s) vinculada(s) a alguma base curricular,turma ou histórico!");
  db_msgbox("Demais Alterações efetuadas com Sucesso!");
 }else{
  db_msgbox("Alterações efetuadas com Sucesso!");
 }
 db_redireciona("edu1_disciplina001.php?ed12_i_ensino=$ed12_i_ensino");
 //exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 10;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 11;
}
.aluno1{
 color: #000000;
 font-family : Verdana;
 font-size: 12;
 font-weight :bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Disciplinas de <?=$ed10_c_descr?></b></legend>
   <?
    include("forms/db_frmdisciplina.php");
   ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($cldisciplina->erro_status=="0"){
  $cldisciplina->erro(true,false);
  $db_botao=true;
  //echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cldisciplina->erro_campo!=""){
   echo "<script> document.form1.".$cldisciplina->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cldisciplina->erro_campo.".focus();</script>";
  };
 }else{
  $cldisciplina->erro(true,true);
 }
}
?>