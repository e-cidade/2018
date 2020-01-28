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
include("libs/db_liborcamento.php");
include("classes/db_empautidot_classe.php");
include("classes/db_orcsuplemval_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcreserva_classe.php");
include("classes/db_orcreservaaut_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_empautitem_classe.php");
$clempautitem = new cl_empautitem;


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clorcdotacao = new cl_orcdotacao;
$clempautidot = new cl_empautidot;
$clorcsuplemval = new cl_orcsuplemval;
$clorcreserva = new cl_orcreserva;
$clorcreservaaut = new cl_orcreservaaut;
$db_opcao = 3;
$db_botao = false;
if(isset($e56_autori)){
  $result = $clempautidot->sql_record($clempautidot->sql_query_file($e56_autori)); 
  if($clempautidot->numrows>0){
    //só passará se nao tiver sido clicado em processar
    if(empty($pesquisa_dot)){
      db_fieldsmemory($result,0);
      $o47_coddot=$e56_coddot;
    }  
  }else{

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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmempempdot.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>