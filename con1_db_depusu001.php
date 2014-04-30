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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_db_depusu_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_depart_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldb_depusu   = new cl_db_depusu;
$cldb_usuarios = new cl_db_usuarios;
$cldb_depart   = new cl_db_depart;

$iCodUsuario = $oGet->id_usuario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
.marcaEnvia, .marcaRetira 
 { 
   border-colappse  : collapse;
   border-right     : 1px inset black;
   border-bottom    : 1px inset black;
   cursor           : normal;
   font-family      : Arial, Helvetica, sans-serif;
   font-size        : 12px;
   background-color : #CCCDDD
 }
 
.marcaSel
{
   border-colappse  : collapse;
   border-right     : 1px inset black;
   border-bottom    : 1px inset black;
   cursor           : normal;
   font-family      : Arial, Helvetica, sans-serif;
   font-size        : 12px;
   background-color : #d1f07c
}

td.linhagrid 
{
  -moz-user-select: none;
  text-align: left;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisaDepartamentos(<?=$iCodUsuario?>)" 
      bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
  <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td>
			  <?
			    include("forms/db_frmdb_depusu.php");
			  ?>     
     </td>
   </tr>   
  </table>
</form>
</center>
</body>
</html>