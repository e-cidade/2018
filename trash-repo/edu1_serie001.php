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
include("classes/db_serie_classe.php");
include("classes/db_ensino_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clserie = new cl_serie;
$clensino = new cl_ensino;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $result = $clserie->sql_record($clserie->sql_query_file("","max(ed11_i_sequencia)",""," ed11_i_ensino = $ed11_i_ensino"));
 if($clserie->numrows>0){
  db_fieldsmemory($result,0);
  if($max==""){
   $max = 0;
  }
 }else{
  $max = 0;
 }
 $clserie->ed11_i_sequencia = ($max+1);
 $clserie->incluir($ed11_i_codigo);
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Etapa</b></legend>
    <?include("forms/db_frmserie.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
 js_tabulacaoforms("form1","ed11_i_ensino",true,1,"ed11_i_ensino",true);
</script>
<?
if(isset($incluir)){
 if($clserie->erro_status=="0"){
  $clserie->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clserie->erro_campo!=""){
   echo "<script> document.form1.".$clserie->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clserie->erro_campo.".focus();</script>";
  }
 }else{
  $clserie->erro(true,false);
  ?><script>parent.mo_camada("a2");</script><? 
  db_redireciona("edu1_serie002.php?chavepesquisa=".$clserie->ed11_i_codigo);
 }
}
?>