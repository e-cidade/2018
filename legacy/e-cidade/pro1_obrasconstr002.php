<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obrasalvara_classe.php");
require_once("classes/db_obrasender_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clobrasconstr = new cl_obrasconstr;
$clobrasalvara = new cl_obrasalvara;
$clobrasender  = new cl_obrasender;

$db_opcao = 22;
$db_botao = false;
$sqlerro = false;
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clobrasconstr->alterar($ob08_codconstr);
  if($clobrasconstr->erro_status == "0"){
    $sqlerro = true;
    $erro_msg = $clobrasconstr->erro_msg;
  }else{
    $ok_msg = $clobrasconstr->erro_msg;
  }
  $clobrasender->ob07_codobra = $ob08_codobra;
  $clobrasender->ob07_codconstr = $ob08_codconstr;
  if($sqlerro == false){
    $result = $clobrasender->sql_record($clobrasender->sql_query($ob08_codconstr)); 
    if ($clobrasender->numrows == 0) {
      $clobrasender->incluir($ob08_codconstr);
    } else {
      $clobrasender->alterar($ob08_codconstr);
    }
    if($clobrasender->erro_status == "0"){
       $sqlerro = true;
       $erro_msg = $clobrasender->erro_msg;
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clobrasconstr->sql_record($clobrasconstr->sql_query($chavepesquisa)); 
   if ( pg_numrows($result) > 0){
      db_fieldsmemory($result,0);
   }
   $result = $clobrasender->sql_record($clobrasender->sql_query($chavepesquisa)); 
   // echo $clobrasender->sql_query($chavepesquisa);
   // db_criatabela($result);
   if ($clobrasender->numrows > 0 ) {
     db_fieldsmemory($result,0);
   } else {
   }
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
 db_app::load("scripts.js");
 db_app::load("strings.js");
 db_app::load("estilos.css"); 
 db_app::load("prototype.js");
 db_app::load("datagrid.widget.js");
 db_app::load("dbmessageBoard.widget.js");
 db_app::load("grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
		<?
	  	include("forms/db_frmobrasconstr.php");
		?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clobrasconstr->erro_campo!=""){
      echo "<script> document.form1.".$clobrasconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobrasconstr->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($ok_msg);
    if(isset($func_alvara)){
      echo "<script>location.href='pro1_obrasconstr001.php?func_alvara=1&ob08_codconstr=".$ob08_codconstr."&ob08_codobra=$ob08_codobra&abas=1'</script>";
    }else{
      echo "<script>parent.iframe_constr.location.href='pro1_obrasconstr001.php?ob08_codconstr=".$ob08_codconstr."&ob08_codobra=$ob08_codobra&abas=1'</script>";
    }
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>