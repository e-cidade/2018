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
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_atendrequiitemmei_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueinimeiari_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
include("dbforms/db_funcoes.php");
include("classes/requisicaoMaterial.model.php");

require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

db_postmemory($HTTP_POST_VARS);
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$clatendrequiitem = new cl_atendrequiitem;
$clatendrequiitemmei = new cl_atendrequiitemmei;
$clatendrequi = new cl_atendrequi;
$clmatestoque = new cl_matestoque;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatestoqueinimeiari = new cl_matestoqueinimeiari;
$clmatestoqueitem = new cl_matestoqueitem;
$cldb_almoxdepto = new cl_db_almoxdepto;
$cldb_departorg = new cl_db_departorg;
$clmatparam = new cl_matparam;
$clmatrequi->rotulo->label();
$db_opcao = 1;
$db_botao = true;
$pesq     = false;
$aParamKeys = array(
                    db_getsession("DB_anousu")
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 

if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
include ("forms/db_frmpedidorequisicao.php");
?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset ($incluir)) {
	if ($clatendrequi->erro_status == "0") {
		$clatendrequi->erro(true, false);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if ($clatendrequi->erro_campo != "") {
			echo "<script> document.form1.".$clatendrequi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clatendrequi->erro_campo.".focus();</script>";
		}
	} else {
		$clatendrequi->erro(true, true);
	}
}
if ($pesq == true) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>