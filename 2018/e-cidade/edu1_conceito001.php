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
include("classes/db_conceito_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clconceito = new cl_conceito;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 $result = $clconceito->sql_record($clconceito->sql_query("","ed39_c_conceito as concad",""," ed39_i_formaavaliacao = $ed39_i_formaavaliacao AND ed39_c_conceito = '$ed39_c_conceito'"));
 if($clconceito->numrows>0){
  db_msgbox("Nível já existe!");
 }else{
  db_inicio_transacao();
  $result = $clconceito->sql_record($clconceito->sql_query("","max(ed39_i_sequencia)",""," ed39_i_formaavaliacao = $ed39_i_formaavaliacao"));
  db_fieldsmemory($result,0);
  if($max==""){
   $max = 0;
  }
  $clconceito->ed39_i_sequencia = ($max+1);
  $clconceito->incluir($ed39_i_codigo);
  db_fim_transacao();
 }
}
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $clconceito->alterar($ed39_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clconceito->excluir($ed39_i_codigo);
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
<body bgcolor="#CCCCCC" style="margin-top: 25px">
  <center>
    <div style="display: table;" id='divConceito'>
      <fieldset><legend><b>Cadastro de Níveis</b></legend>
       <?include("forms/db_frmconceito.php");?>
      </fieldset>
    </div>
  </center>
</body>
</html>
<?
if(isset($incluir)){
 if($clconceito->erro_status=="0"){
  $clconceito->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clconceito->erro_campo!=""){
   echo "<script> document.form1.".$clconceito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clconceito->erro_campo.".focus();</script>";
  };
 }else{
  ?><script>parent.location.href="edu1_formaavaliacao002.php?chavepesquisa=<?=$ed39_i_formaavaliacao?>";</script><?
 };
};
if(isset($alterar)){
 if($clconceito->erro_status=="0"){
  $clconceito->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clconceito->erro_campo!=""){
   echo "<script> document.form1.".$clconceito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clconceito->erro_campo.".focus();</script>";
  };
 }else{
  ?><script>parent.location.href="edu1_formaavaliacao002.php?chavepesquisa=<?=$ed39_i_formaavaliacao?>";</script><?
 };
};
if(isset($excluir)){
 if($clconceito->erro_status=="0"){
  $clconceito->erro(true,false);
 }else{
  ?><script>parent.location.href="edu1_formaavaliacao002.php?chavepesquisa=<?=$ed39_i_formaavaliacao?>";</script><?
 };
};
if(isset($cancelar)){
 ?><script>parent.location.href="edu1_formaavaliacao002.php?chavepesquisa=<?=$ed39_i_formaavaliacao?>";</script><?
}

?>