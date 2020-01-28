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
include("classes/db_exemplar_classe.php");
include("classes/db_baixabib_classe.php");
include("classes/db_emprestimoacervo_classe.php");
include("classes/db_impexemplaritem_classe.php");
include("classes/db_localexemplar_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clexemplar = new cl_exemplar;
$clbaixa = new cl_baixa;
$clemprestimoacervo = new cl_emprestimoacervo;
$climpexemplaritem = new cl_impexemplaritem;
$cllocalexemplar = new cl_localexemplar;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 $clexemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
 db_inicio_transacao();
 $clexemplar->bi23_situacao = 'S';
 $clexemplar->incluir($bi23_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
 $db_opcao = 2;
 $clexemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
 db_inicio_transacao();
 $clexemplar->alterar($bi23_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 $clexemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
 $result = $clbaixa->sql_record($clbaixa->sql_query_file("","*",""," bi08_exemplar = $bi23_codigo"));
 $result1 = $clemprestimoacervo->sql_record($clemprestimoacervo->sql_query_file("","*",""," bi19_exemplar = $bi23_codigo"));
 if($clbaixa->numrows>0){
  $clexemplar->erro_status = "0";
  $clexemplar->erro_msg = "Exemplar $bi23_codbarras não pode ser excluído, pois contém registro de baixa.";
 }elseif($clemprestimoacervo->numrows>0){
  $clexemplar->erro_status = "0";
  $clexemplar->erro_msg = "Exemplar $bi23_codbarras não pode ser excluído, pois contém registro de empréstimo.";
 }else{
  db_inicio_transacao();
  $clbaixa->excluir("","bi08_exemplar = $bi23_codigo");
  $clemprestimoacervo->excluir("","bi19_exemplar = $bi23_codigo");
  $climpexemplaritem->excluir("","bi25_exemplar = $bi23_codigo");
  $cllocalexemplar->excluir("","bi27_exemplar = $bi23_codigo");
  $clexemplar->excluir($bi23_codigo);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:90%"><legend><b>Exemplares do Acervo</b></legend>
    <?include("forms/db_frmexemplar.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","bi23_dataaquisicao_dia",true,1,"bi23_dataaquisicao_dia",true);
</script>
<?
if(isset($incluir)){
 if($clexemplar->erro_status=="0"){
  $clexemplar->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clexemplar->erro_campo!=""){
   echo "<script> document.form1.".$clexemplar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clexemplar->erro_campo.".focus();</script>";
  }
 }else{
  ?>
  <script>
   top.corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
  </script>
  <?
  $clexemplar->erro(true,true);
 }
}
if(isset($alterar)){
 if($clexemplar->erro_status=="0"){
  $clexemplar->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clexemplar->erro_campo!=""){
   echo "<script> document.form1.".$clexemplar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clexemplar->erro_campo.".focus();</script>";
  }
 }else{
  ?>
  <script>
   top.corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
  </script>
  <?
  $clexemplar->erro(true,true);
 }
}
if(isset($excluir)){
 if($clexemplar->erro_status=="0"){
  $clexemplar->erro(true,false);
 }else{
  ?>
  <script>
   top.corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi23_acervo?>&bi06_titulo=<?=$bi06_titulo?>';
  </script>
  <?
  $clexemplar->erro(true,true);
 }
}
if(isset($cancelar)){
 $clexemplar->pagina_retorno = "bib1_exemplar001.php?bi23_acervo=$bi23_acervo&bi06_titulo=$bi06_titulo";
 echo "<script>location.href='".$clexemplar->pagina_retorno."'</script>";
}
?>