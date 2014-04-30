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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensmater_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clbensimoveis = new cl_bensimoveis;
$clbensmater = new cl_bensmater;

if(isset($incluir)){
  $sqlerro = false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbensimoveis->incluir($t54_codbem,$t54_idbql);
    if($clbensimoveis->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbensimoveis->erro_msg;
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  $sqlerro = false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbensimoveis->alterar($t54_codbem,$t54_idbql);
    if($clbensimoveis->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbensimoveis->erro_msg;
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  $sqlerro=false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbensimoveis->excluir($t54_codbem,$t54_idbql);
    $erro_msg = $clbensimoveis->erro_msg;
    if($clbensimoveis->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}

// die($clbensmater->sql_query_file(null,"*","","t54_codbem = ".$t54_codbem));
$result = $clbensmater->sql_record($clbensmater->sql_query_file(null,"*","","t53_codbem = ".$t54_codbem));
if($clbensmater->numrows==0){
  $result1 = $clbensimoveis->sql_record($clbensimoveis->sql_query_file(null,null,"*","","t54_codbem = ".$t54_codbem));
  if($clbensimoveis->numrows>0){
    db_fieldsmemory($result1, 0);
  }
}

if(isset($desabilita) && $desabilita==true || $clbensmater->numrows>0){
  echo "<center><b><h3> Bem cadastrado como Material. <br> Cadastro de bens imóveis desabilitado.</h3></b></center>";
  $db_opcao = 22;
  $db_botao = false;
}else if(isset($db_opcaoal) && $db_opcaoal == 33){
  $db_opcao = 33;
  $db_botao = false;
}else if($clbensimoveis->numrows > 0){
  $db_botao = true;
  $db_opcao = 2;   
}else{
  $db_botao = true;
  $db_opcao = 1;
}
if(isset($excluir)){
  $t54_idbql = "";
  $t54_obs = "";
}

if (isset($importar) && $importar == true){
     $result = $clbensimoveis->sql_record($clbensimoveis->sql_query_file(null,null,"t54_idbql,t54_obs","","t54_codbem = ".$codbem));
     if ($clbensimoveis->numrows > 0){
          db_fieldsmemory($result,0);
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
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmbensimoveis.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clbensimoveis->erro_campo!=""){
        echo "<script> document.form1.".$clbensimoveis->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clbensimoveis->erro_campo.".focus();</script>";
    }
}
?>