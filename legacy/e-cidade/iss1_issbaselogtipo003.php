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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbaselogtipo_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clissbaselogtipo = new cl_issbaselogtipo;
$db_opcao         = 33;
$db_botao         = false;
$lPesquisa        = false;
$lErro            = false;

if (isset($excluir)) {
	
  db_inicio_transacao();

  $db_opcao = 3;
  $clissbaselogtipo->excluir($q103_sequencial);
  if ($clissbaselogtipo->erro_status == '0') {
  	$lErro = true;
  }
  
  db_fim_transacao($lErro);
} else if (isset($chavepesquisa)) {
	
   $result = $clissbaselogtipo->sql_record($clissbaselogtipo->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   
   $db_opcao = 3;
   $db_botao = true;
}

if ($db_opcao == 33) {
  
  $db_opcao  = 3;
  $lPesquisa = true;
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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top"> 
      <?
        include("forms/db_frmissbaselogtipo.php");
      ?>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if (isset($excluir)) {
	
  if ($clissbaselogtipo->erro_status=="0") {
    $clissbaselogtipo->erro(true,false);
  } else {
    $clissbaselogtipo->erro(true,true);
  }
}

if ($lPesquisa == true) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
</html>