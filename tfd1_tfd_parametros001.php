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
include("classes/db_tfd_parametros_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);
$cltfd_parametros = new cl_tfd_parametros;
$db_opcao         = 1;
$db_botao         = true;

if (isset($incluir)) {

  db_inicio_transacao();
  $cltfd_parametros->incluir($tf11_i_codigo);
  db_fim_transacao();

}

if (isset($alterar)) {

  db_inicio_transacao();
  $cltfd_parametros->alterar($tf11_i_codigo);
  db_fim_transacao();
}
$sSql = $cltfd_parametros->sql_query_geral("", "tfd_parametros.*,rh70_estrutural,rh70_descr,sd02_i_codigo,descrdepto,".
                                           "sd03_i_codigo,z01_nome", "", ""
                                          );
$rs   = $cltfd_parametros->sql_record($sSql);
if ($cltfd_parametros->numrows == 0) {
  $db_opcao = 1;
} else {

  $db_opcao = 2;
  db_fieldsmemory($rs, 0);

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load(" grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style='width: 75%;'> <legend><b>Parâmetros</b></legend>
	<?include("forms/db_frmtfd_parametros.php");?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf11_i_utilizagradehorario",true,1,"tf11_i_utilizagradehorario",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
  if($cltfd_parametros->erro_status=="0"){
    $cltfd_parametros->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltfd_parametros->erro_campo!=""){
      echo "<script> document.form1.".$cltfd_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltfd_parametros->erro_campo.".focus();</script>";
    }
  }else{
    $cltfd_parametros->erro(true,true);
  }
}
?>