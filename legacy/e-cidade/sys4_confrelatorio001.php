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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("libs/db_utils.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clcriaabas = new cl_criaabas();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    
    $clcriaabas->identifica = array("campos"=>"Campos","ordem"=>"Ordem","filtros"=>"Filtros","layout"=>"Layout","variaveis"=>"Variáveis","finalizar"=>"Finalizar");
    $clcriaabas->title      = array("campos"=>"Campos","ordem"=>"Ordem","filtros"=>"Filtros","layout"=>"Layout","variaveis"=>"Variáveis","finalizar"=>"Finalizar");
    
    $clcriaabas->src	      = array("campos"	 =>"sys4_confrelatorio003.php",
																    "ordem"	   =>"sys4_confrelatorio008.php",
								    								"filtros"  =>"sys4_confrelatorio004.php",
								    								"variaveis"=>"sys4_confrelatorio007.php",
								    								"layout"   =>"sys4_confrelatorio005.php",
	   																"finalizar"=>"sys4_confrelatorio006.php");
      
    $clcriaabas->sizecampo  = array("campos"=>"10","filtros"=>"10","variaveis"=>"10","layout"=>"10","finalizar"=>"10");
    
    if ( isset($oGet->lSql) && $oGet->lSql == 'true') {
    	$clcriaabas->identifica["editasql"] = "Editar SQL";
    	$clcriaabas->title     ["editasql"] = "Editar SQL";
    	$clcriaabas->src       ["editasql"] = "sys4_confrelatorio009.php";
    	$clcriaabas->sizecampo ["editasql"] = "10";
    	$clcriaabas->disabled  ["filtros"]  = true;
    }
    
    $clcriaabas->cria_abas();
    
    
    
    ?>
    </center>
  </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var lFiltros      = false;
  var lOrdem        = false;
  var lPropriedades = false;
  var lVariaveis    = false;
  var lFinalizar    = false;
	
	function js_retornoBloqueio(){
		iframe_finalizar.js_sair();
	}
	
	js_bloqueiaMenus(true,js_retornoBloqueio);

</script>