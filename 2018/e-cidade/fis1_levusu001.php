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
include("classes/db_levusu_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllevusu = new cl_levusu;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cllevusu->incluir($y61_codlev,$y61_id_usuario);
  if($cllevusu->erro_status==0){
    $sqlerro=true;
  }  
  db_fim_transacao();
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $cllevusu->alterar($y61_codlev,$y61_id_usuario);
  if($cllevusu->erro_status==0){
    $sqlerro=true;
  }  
  db_fim_transacao();
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cllevusu->excluir($y61_codlev,$y61_id_usuario);
  if($cllevusu->erro_status==0){
    $sqlerro=true;
  }  
  db_fim_transacao($sqlerro);
}else if(isset($opcao)){
   $result = $cllevusu->sql_record($cllevusu->sql_query($y61_codlev,$y61_id_usuario)); 
   db_fieldsmemory($result,0);
}
if(isset($db_opcaoal)){
    $db_opcao=3;
      $db_botao=false;
}else{
  $db_botao=true;
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{  
    $db_opcao = 1;
    $db_botao=true;
} 
  
  if((isset($sqlerro) && $sqlerro==false) || (isset($novo) && $novo=="ok")){
    $y61_id_usuario='';
    $y61_obs='';
    $nome='';
    unset($novo);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	  <?
	  include("forms/db_frmlevusu.php");
	  ?>
      </center>
	  </td>
    </tr>
  </table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($cllevusu->erro_status=="0"){
    $cllevusu->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllevusu->erro_campo!=""){
      echo "<script> document.form1.".$cllevusu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllevusu->erro_campo.".focus();</script>";
    };
  }else{
    $cllevusu->erro(true,false);
  };
};
?>