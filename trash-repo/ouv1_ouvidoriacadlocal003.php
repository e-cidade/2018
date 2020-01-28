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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_ouvidoriacadlocal_classe.php");
require_once("classes/db_ouvidoriacadlocalgeral_classe.php");
require_once("classes/db_ouvidoriacadlocalender_classe.php");
require_once("classes/db_ouvidoriacadlocaldepart_classe.php");

$oGet = db_utils::postMemory($_GET);

$clouvidoriacadlocal       = new cl_ouvidoriacadlocal();
$clouvidoriacadlocalender  = new cl_ouvidoriacadlocalender();
$clouvidoriacadlocalgeral  = new cl_ouvidoriacadlocalgeral();
$clouvidoriacadlocaldepart = new cl_ouvidoriacadlocaldepart();

$db_opcao = 33;
$db_botao = true;

if ( isset($oGet->chavepesquisa) && trim($oGet->chavepesquisa) != '' ) {
	
	$rsConsultaDadosLocal = $clouvidoriacadlocal->sql_record($clouvidoriacadlocal->sql_query_tipo($oGet->chavepesquisa));
	db_fieldsmemory($rsConsultaDadosLocal,0);
	$db_opcao = 3;
	
	if ( trim($ov28_sequencial) != '' ) {
		$tipoLocal = 'g';
	} else if ( trim($ov26_sequencial) != '' ) {
		$tipoLocal = 'e';
	} else {
		$tipoLocal = 'd';
	}
	
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_validaTipo();" >
<table align="center" style="padding-top:20px;"> 
  <tr> 
    <td> 
      <center>
		  	<?
			    include("forms/db_frmouvidoriacadlocal.php");
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
 <?
    if ( $db_opcao == 33 ) {
    	echo "js_pesquisa();";
    }
 ?> 
</script>