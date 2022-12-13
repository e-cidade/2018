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
include("classes/db_db_cadhelp_classe.php");
include("classes/db_db_itenshelp_classe.php");
include("classes/db_db_itensmenu_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_cadhelp = new cl_db_cadhelp;
$cldb_itenshelp = new cl_db_itenshelp;
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $erro = false;
  $cldb_cadhelp->alterar($id_help);
  if($cldb_cadhelp->erro_status!="1"){ 
     $erro = true;
  }
  $cldb_itenshelp->excluir(null,$id_help);
  if($cldb_itenshelp->erro_status!="1"){ 
     $erro = true;
  }
  $cldb_itenshelp->incluir($id_item,$id_help);
  if($cldb_itenshelp->erro_status!="1"){ 
     $erro = true;
  }
  db_fim_transacao($erro);
}else if(isset($chavepesquisa) || isset($automatico)){
   if(isset($automatico)){
     $result = $cldb_itenshelp->sql_record($cldb_itenshelp->sql_query($automatico,null,'db_itenshelp.id_help')); 
     if($cldb_itenshelp->numrows>0){
       db_fieldsmemory($result,0);
       $chavepesquisa = $id_help;
     }
   }
   $db_opcao = 2;
   $result = $cldb_cadhelp->sql_record($cldb_cadhelp->sql_query($chavepesquisa,"*")); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<?
if(!isset($automatico)){
?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<?
}
?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_cadhelp.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
if(!isset($automatico)){
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}else{
  echo "<script>
        objauto = document.createElement('input');
	objauto.setAttribute('name','automatico');
	objauto.setAttribute('type','hidden');
	objauto.setAttribute('value','".$automatico."');
	document.form1.appendChild(objauto);
        objauto = document.createElement('input');
	objauto.setAttribute('name','modulo_help');
	objauto.setAttribute('type','hidden');
	objauto.setAttribute('value','".$modulo_help."');
	document.form1.appendChild(objauto);

	</script>";
}
?>
</body>
</html>
<?
if($cldb_cadhelp->erro_status=="0"){
  $cldb_cadhelp->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cldb_cadhelp->erro_campo!=""){
    echo "<script> document.form1.".$cldb_cadhelp->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cldb_cadhelp->erro_campo.".focus();</script>";
  };
}else{
  if(isset($automatico) && isset($HTTP_POST_VARS["db_opcao"])){
    $cldb_cadhelp->erro(true,false);
    echo "<script>
          parent.document.getElementById('menuHelp').click();
	  </script>";
  }else{

    $cldb_cadhelp->erro(true,true);
  }

};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";

}