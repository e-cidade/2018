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
include("classes/db_turmaacmatricula_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clturmaacmatricula = new cl_turmaacmatricula;
$clturmaac          = new cl_turmaac;
$clmatricula        = new cl_matricula;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $clturmaacmatricula->ed269_d_data = date("Y-m-d");
 $clturmaacmatricula->incluir($ed269_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clturmaacmatricula->excluir($ed269_i_codigo);
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alunos da Turma <?=@$ed268_c_descr?></b></legend>
    <?include("forms/db_frmturmaacmatricula.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed60_matricula",true,1,"ed60_matricula",true);
</script>
<?
if(isset($incluir)){
 if($clturmaacmatricula->erro_status=="0"){
  $clturmaacmatricula->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clturmaacmatricula->erro_campo!=""){
   echo "<script> document.form1.".$clturmaacmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clturmaacmatricula->erro_campo.".focus();</script>";
  }
 }else{
  $clturmaacmatricula->erro(true,false);
  $result_qtd = $clturmaacmatricula->sql_record($clturmaacmatricula->sql_query_turma(""," count(*) as qtdmatricula",""," ed269_i_turmaac = $ed269_i_turmaac"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turmaac SET
            ed268_i_nummatr = $qtdmatricula
           WHERE ed268_i_codigo = $ed269_i_turmaac
           ";
  $query1 = db_query($sql1);
  ?>
  <script>
   top.corpo.iframe_a1.location.href='edu1_turmaac002.php?chavepesquisa=<?=$ed269_i_turmaac?>';
  </script>
  <?
  db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
 }
}
if(isset($excluir)){
 if($clturmaacmatricula->erro_status=="0"){
  $clturmaacmatricula->erro(true,false);
 }else{
  $clturmaacmatricula->erro(true,false);
  $result_qtd = $clturmaacmatricula->sql_record($clturmaacmatricula->sql_query_turma(""," count(*) as qtdmatricula",""," ed269_i_turmaac = $ed269_i_turmaac"));
  db_fieldsmemory($result_qtd,0);
  $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
  $sql1 = "UPDATE turmaac SET
            ed268_i_nummatr = $qtdmatricula
           WHERE ed268_i_codigo = $ed269_i_turmaac
           ";
  $query1 = db_query($sql1);
  ?>
  <script>
   top.corpo.iframe_a1.location.href='edu1_turmaac002.php?chavepesquisa=<?=$ed269_i_turmaac?>';
  </script>
  <?
  db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed52_c_descr=$ed52_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
}
?>