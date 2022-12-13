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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_escola_sequencias_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clescola_sequencias = new cl_escola_sequencias;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 if($ed129_i_numinicio=="" || $ed129_i_numfinal==""){
  $clescola_sequencias->erro_msg = "Informe início e final da sequencia!";
  $clescola_sequencias->erro_status = "0";
  $clescola_sequencias->erro_campo = "ed129_i_numinicio";
 }elseif($ed129_i_numinicio>=$ed129_i_numfinal){
   $clescola_sequencias->erro_msg = "Inicio da sequência deve ser menor que o final!";
   $clescola_sequencias->erro_status = "0";
   $clescola_sequencias->erro_campo = "ed129_i_numinicio";
 }else{
  $sql = "SELECT * FROM escola_sequencias
          WHERE ($ed129_i_numinicio BETWEEN ed129_i_numinicio AND ed129_i_numfinal)
          OR ($ed129_i_numfinal BETWEEN ed129_i_numinicio AND ed129_i_numfinal)
         ";
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  if($linhas>0){
   $clescola_sequencias->erro_msg = "Inicio ou final da sequência digitada já pertence a outra escola!";
   $clescola_sequencias->erro_status = "0";
   $clescola_sequencias->erro_campo = "ed129_i_numinicio";
  }else{
   db_inicio_transacao();
   $clescola_sequencias->ed129_i_ultatualizse = time();
   $clescola_sequencias->ed129_i_ultatualizes = time();
   $clescola_sequencias->ed129_c_ulttransacao = "SE";
   $clescola_sequencias->ed129_i_inicio = $ed129_i_escola.$ed129_i_numinicio;
   $clescola_sequencias->ed129_i_final = $ed129_i_escola.$ed129_i_numfinal;
   $clescola_sequencias->incluir($ed129_i_codigo);
   db_fim_transacao();
  }
 }
}
if(isset($alterar)){
 if($ed129_i_numinicio=="" || $ed129_i_numfinal==""){
  $clescola_sequencias->erro_msg = "Informe início e final da sequencia!";
  $clescola_sequencias->erro_status = "0";
  $clescola_sequencias->erro_campo = "ed129_i_numinicio";
  $opcao = "alterar";
 }elseif($ed129_i_numinicio>=$ed129_i_numfinal){
  $clescola_sequencias->erro_msg = "Inicio da sequência deve ser menor que o final!";
  $clescola_sequencias->erro_status = "0";
  $clescola_sequencias->erro_campo = "ed129_i_numinicio";
  $opcao = "alterar";
 }else{
  $sql = "SELECT * FROM escola_sequencias
          WHERE (($ed129_i_numinicio BETWEEN ed129_i_numinicio AND ed129_i_numfinal)
          OR ($ed129_i_numfinal BETWEEN ed129_i_numinicio AND ed129_i_numfinal))
          AND ed129_i_escola != $ed129_i_escola
         ";
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  if($linhas>0){
   $clescola_sequencias->erro_msg = "Inicio ou final da sequência digitada já pertence a outra escola!";
   $clescola_sequencias->erro_status = "0";
   $clescola_sequencias->erro_campo = "ed129_i_numinicio";
   $opcao = "alterar";
  }else{
   db_inicio_transacao();
   $db_opcao = 2;
   $clescola_sequencias->ed129_i_inicio = $ed129_i_escola.$ed129_i_numinicio;
   $clescola_sequencias->ed129_i_final = $ed129_i_escola.$ed129_i_numfinal;
   $clescola_sequencias->alterar($ed129_i_codigo);
   db_fim_transacao();
  }
 }
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clescola_sequencias->excluir($ed129_i_codigo);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Cadastro de Sequências das Escolas Locais</b></legend>
    <?include("forms/db_frmescola_sequencias.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed129_i_escola",true,1,"ed129_i_escola",true);
</script>
<?
if(isset($incluir)){
 if($clescola_sequencias->erro_status=="0"){
  $clescola_sequencias->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>";
  if($clescola_sequencias->erro_campo!=""){
   echo "<script> document.form1.".$clescola_sequencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescola_sequencias->erro_campo.".focus();</script>";
  }
 }else{
  $clescola_sequencias->erro(true,true);
 }
}
if(isset($alterar)){
 if($clescola_sequencias->erro_status=="0"){
  $clescola_sequencias->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clescola_sequencias->erro_campo!=""){
   echo "<script> document.form1.".$clescola_sequencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescola_sequencias->erro_campo.".focus();</script>";
  }
 }else{
  $clescola_sequencias->erro(true,true);
 }
}
if(isset($excluir)){
 if($clescola_sequencias->erro_status=="0"){
  $clescola_sequencias->erro(true,false);
 }else{
  $clescola_sequencias->erro(true,true);
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clescola_sequencias->pagina_retorno."'</script>";
}

?>