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
include("classes/db_localacervo_classe.php");
include("classes/db_localexemplar_classe.php");
include("classes/db_exemplar_classe.php");
include("classes/db_localizacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllocalacervo = new cl_localacervo;
$cllocalexemplar = new cl_localexemplar;
$cllocalizacao = new cl_localizacao;
$clexemplar = new cl_exemplar;
$db_botao = true;
$result= $clexemplar->sql_record($clexemplar->sql_query("","bi23_codigo as codexemp","","bi23_acervo=$bi20_acervo AND bi23_situacao = 'S'"));
if($clexemplar->numrows==1){
 db_fieldsmemory($result,0);
 $sql = "UPDATE localexemplar SET bi27_letra = '' where bi27_exemplar = $codexemp";
 $result = pg_query($sql);
}
if(isset($incluir)){
 $db_opcao = 1;
 db_inicio_transacao();
 $cllocalacervo->incluir($bi20_codigo);
 $bi27_localacervo = $cllocalacervo->bi20_codigo;
 if($clexemplar->numrows>1){
  for($r=0;$r<count($bi_letra);$r++){
   $cllocalexemplar->bi27_localacervo = $bi27_localacervo;
   $cllocalexemplar->bi27_exemplar = $bi_exemplar[$r];
   $cllocalexemplar->bi27_letra = strtoupper($bi_letra[$r]);
   $cllocalexemplar->incluir(null);
  }
 }else{
  db_fieldsmemory($result,0);
  $cllocalexemplar->bi27_localacervo = $bi27_localacervo;
  $cllocalexemplar->bi27_exemplar = $codexemp;
  $cllocalexemplar->bi27_letra = "";
  $cllocalexemplar->incluir(null);
 }
 db_fim_transacao();
}elseif(isset($alterar)){
 $db_opcao=2;
 db_inicio_transacao();
 $cllocalacervo->alterar($bi20_codigo);
 if($clexemplar->numrows>1){
  for($r=0;$r<count($bi_letra);$r++){
   if($bi_codigo[$r]!="" && $bi_letra[$r]!=""){
    $cllocalexemplar->bi27_letra = strtoupper($bi_letra[$r]);
    $cllocalexemplar->bi27_exemplar = $bi_exemplar[$r];
    $cllocalexemplar->bi27_codigo = $bi_codigo[$r];
    $cllocalexemplar->alterar($bi_codigo[$r]);
   }elseif($bi_codigo[$r]=="" && $bi_letra[$r]!=""){
    $cllocalexemplar->bi27_localacervo = $bi20_codigo;
    $cllocalexemplar->bi27_exemplar = $bi_exemplar[$r];
    $cllocalexemplar->bi27_letra = strtoupper($bi_letra[$r]);
    $cllocalexemplar->incluir(null);
   }
  }
 }else{
  $result_ct = $cllocalexemplar->sql_record($cllocalexemplar->sql_query("","bi27_codigo as cod_lcexemp","","bi27_exemplar = $codexemp"));
  if($cllocalexemplar->numrows==0){
   $cllocalexemplar->bi27_localacervo = $bi20_codigo;
   $cllocalexemplar->bi27_exemplar = $codexemp;
   $cllocalexemplar->bi27_letra = "";
   $cllocalexemplar->incluir(null);
  }
 }
 db_fim_transacao();
}elseif(isset($excluir)){
 db_inicio_transacao();
 $db_opcao=3;
 $cllocalacervo->excluir($bi20_codigo);
 db_fim_transacao();
}else{
 $result = $cllocalacervo->sql_record($cllocalacervo->sql_query("","*","","bi20_acervo=$bi20_acervo"));
 if($cllocalacervo->numrows>0){
  $db_opcao = 2;
  db_fieldsmemory($result,0);
  if(!isset($chavepesquisa)){
   $chavepesquisa = $bi20_localizacao;
  }else{
   $result1= $cllocalacervo->sql_record($cllocalacervo->sql_query("","max(bi20_sequencia) as maior","","bi20_localizacao = $chavepesquisa"));
   db_fieldsmemory($result1,0);
   $maior = $maior==""?1:$maior+1;
   $bi20_sequencia = $bi20_localizacao==$chavepesquisa?$bi20_sequencia:$maior;
   $result2= $cllocalizacao->sql_record($cllocalizacao->sql_query($chavepesquisa));
   db_fieldsmemory($result2,0);
   $bi20_localizacao = $bi09_codigo;
  }
 }else{
  if(isset($chavepesquisa)){
   $result2= $cllocalizacao->sql_record($cllocalizacao->sql_query($chavepesquisa));
   db_fieldsmemory($result2,0);
   $bi20_localizacao = $bi09_codigo;
   $result1= $cllocalacervo->sql_record($cllocalacervo->sql_query("","max(bi20_sequencia) as maior","","bi20_localizacao = $bi20_localizacao"));
   db_fieldsmemory($result1,0);
   $maior = $maior==""?1:$maior+1;
   $bi20_sequencia = $maior;
  }
  $db_opcao = 1;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <?if($clexemplar->numrows==0){
    $sql = "DELETE FROM localacervo WHERE bi20_acervo = $bi20_acervo";
    $result = pg_query($sql);
    ?>
    <fieldset style='width:95%'><legend><b>Cadastro de Localização de Acervo</b></legend>
     Nenhum exemplar cadastrado para este acervo.
    </fieldset>
   <?}else{?>
    <fieldset style="width:95%"><legend><b>Cadastro de Localização de Acervo</b></legend>
     <?include("forms/db_frmlocalacervo.php");?>
    </fieldset>
   <?}?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","bi20_sequencia",true,1,"bi20_sequencia",true);
</script>
<?
if(isset($incluir)){
 if($cllocalacervo->erro_status=="0"){
  $cllocalacervo->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllocalacervo->erro_campo!=""){
   echo "<script> document.form1.".$cllocalacervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllocalacervo->erro_campo.".focus();</script>";
  }
 }else{
  if(!isset($nova_letra)){
   $cllocalacervo->erro(true,false);
  }
  db_redireciona("bib1_localacervo001.php?bi20_acervo=$bi20_acervo&bi06_titulo=$bi06_titulo");
 }
}
if(isset($alterar)){
 if($cllocalacervo->erro_status=="0"){
  $cllocalacervo->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllocalacervo->erro_campo!=""){
   echo "<script> document.form1.".$cllocalacervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllocalacervo->erro_campo.".focus();</script>";
  }
 }else{
  if(!isset($nova_letra)){
   $cllocalacervo->erro(true,false);
  }
  db_redireciona("bib1_localacervo001.php?bi20_acervo=$bi20_acervo&bi06_titulo=$bi06_titulo");
 }
}
if(isset($excluir)){
 if($cllocalacervo->erro_status=="0"){
  $cllocalacervo->erro(true,false);
 }else{
  if(!isset($nova_letra)){
   $cllocalacervo->erro(true,false);
  }
  db_redireciona("bib1_localacervo001.php?bi20_acervo=$bi20_acervo&bi06_titulo=$bi06_titulo");
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$cllocalacervo->pagina_retorno."'</script>";
}
?>