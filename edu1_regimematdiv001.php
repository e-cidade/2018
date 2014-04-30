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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_regimematdiv_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clregimematdiv = new cl_regimematdiv;
$db_opcao = 1;
$db_botao = true;
$clregimematdiv->pagina_retorno = "edu1_regimematdiv001.php?ed219_i_regimemat=$ed219_i_regimemat&ed218_c_nome=$ed218_c_nome";
if(isset($incluir)){
 $result1 = $clregimematdiv->sql_record($clregimematdiv->sql_query("","max(ed219_i_ordenacao)",""," ed219_i_regimemat = $ed219_i_regimemat"));
 $proxima = trim(pg_result($result1,0,0))==""?1:pg_result($result1,0,0)+1;
 db_inicio_transacao();
 $clregimematdiv->ed219_i_ordenacao = $proxima;
 $clregimematdiv->incluir($ed219_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
 $db_opcao = 2;
 db_inicio_transacao();
 $clregimematdiv->alterar($ed219_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clregimematdiv->excluir($ed219_i_codigo);
 db_fim_transacao();
}
if(isset($atualizar)){
 $tam = sizeof($campos);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE regimematdiv SET
           ed219_i_ordenacao = ".($i+1)."
          WHERE ed219_i_codigo = $campos[$i]
         ";
  $query = pg_query($sql);
 }
 echo "<script>location.href='".$clregimematdiv->pagina_retorno."'</script>";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Divisões do Regime de Matrícula</b></legend>
    <?include("forms/db_frmregimematdiv.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed219_c_nome",true,1,"ed219_c_nome",true);
</script>
<?
if(isset($incluir)){
 if($clregimematdiv->erro_status=="0"){
  $clregimematdiv->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clregimematdiv->erro_campo!=""){
   echo "<script> document.form1.".$clregimematdiv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clregimematdiv->erro_campo.".focus();</script>";
  }
 }else{
  $clregimematdiv->erro(true,true);
 }
}
if(isset($alterar)){
 if($clregimematdiv->erro_status=="0"){
  $clregimematdiv->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clregimematdiv->erro_campo!=""){
   echo "<script> document.form1.".$clregimematdiv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clregimematdiv->erro_campo.".focus();</script>";
  }
 }else{
  $clregimematdiv->erro(true,true);
 }
}
if(isset($excluir)){
 if($clregimematdiv->erro_status=="0"){
  $clregimematdiv->erro(true,false);
 }else{
  $clregimematdiv->erro(true,true);
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clregimematdiv->pagina_retorno."'</script>";
}
?>