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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
require ("libs/db_app.utils.php");
require ("libs/db_utils.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_matestoque_classe.php");
include ("classes/db_matestoquetransf_classe.php");
include ("classes/db_matestoqueitem_classe.php");
include ("classes/db_matestoqueitemlote_classe.php");
include ("classes/db_matestoqueini_classe.php");
include ("classes/db_matestoqueinil_classe.php");
include ("classes/db_matestoqueinill_classe.php");
include ("classes/db_matestoqueinimei_classe.php");
include ("classes/db_db_depart_classe.php");
include ("classes/db_db_usuarios_classe.php");
require_once ("classes/db_db_almox_classe.php");
db_postmemory($HTTP_POST_VARS);
$clmatestoque       = new cl_matestoque;
$clmatestoquetransf = new cl_matestoquetransf;
$clmatestoqueitem   = new cl_matestoqueitem;
$clmatestoqueini    = new cl_matestoqueini;
$clmatestoqueinil   = new cl_matestoqueinil;
$clmatestoqueinill  = new cl_matestoqueinill;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_depart        = new cl_db_depart;
$cldb_usuarios      = new cl_db_usuarios;
$oDaoAlmox          = new cl_db_almox();
$db_opcao = 33;
db_app::import("estoque.Almoxarifado");
$db_botao = true;

$erro_msg = 'Msg Vazia';
if (isset ($cancelar)) {
  $sqlerro = false;
	$sSqlAlmoxarifado = $oDaoAlmox->sql_query_file(null, "*", null, "m91_depto = ".db_getsession('DB_coddepto'));
  $rsAlmoxarifado   = $oDaoAlmox->sql_record($sSqlAlmoxarifado);
	if (isset ($confirma)) {
		if ($confirma == "true") {
			
		  try {
		    
		    db_inicio_transacao();
		    $oAlmoxarifado = new Almoxarifado(db_utils::fieldsMemory($rsAlmoxarifado, 0)->m91_codigo);
		    $oAlmoxarifado->receberTransferencia($m80_codigo);
		    $erro_msg      = "Transferência efetuada com sucesso";

		    db_fim_transacao(false);

		  } catch (Exception $eErro) {
		    
		    db_fim_transacao(true);
		    $sqlerro  = true;
		    $msgaviso = str_replace("\n", "\\n", $eErro->getMessage());
		    $erro_msg = $msgaviso;
		  }
		} 
	} else {
      
    try {
      
      db_inicio_transacao();
      $oAlmoxarifado    = new Almoxarifado(db_utils::fieldsMemory($rsAlmoxarifado, 0)->m91_codigo);
      $oAlmoxarifado->cancelarTransferencia($m80_codigo);
      $erro_msg    = "Cancelamento efetuado com sucesso.";
      db_fim_transacao(false);
      
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $sqlerro  = true;
      $msgaviso = str_replace("\n", "\\n", $eErro->getMessage());
      $erro_msg = $msgaviso;
    }
  }
} else
	if (isset ($chavepesquisa)) {
	  $sCampos = "distinct 
	              m83_coddepto as departamentodestino,
	              a.descrdepto as descrdepartamentodestino,
	              db_depart.coddepto as departamentoorigem,
	              db_depart.descrdepto as descrdepartamentoorigem,
	              matestoqueini.m80_codigo, 
	              matestoqueini.m80_obs";
		$result_dadostransf = $clmatestoquetransf->sql_record($clmatestoquetransf->sql_query_inill($chavepesquisa, $sCampos));
		if ($clmatestoquetransf->numrows > 0) {
			db_fieldsmemory($result_dadostransf, 0);
			$db_opcao = 3;
			$db_botao = false;
		} else {
			$msgaviso = "Transferência não encontrada.";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?


include ("forms/db_frmmattransfexcdepto.php");
?>
    </center>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


$qry = "";
if (isset ($confirma)) {
	$qry = "?confirma=$confirma";
}
if (isset ($cancelar)) {
	db_msgbox($erro_msg);
	
	if ($sqlerro == false) {
		echo "<script>location.href = 'mat1_mattransfdepto003.php$qry'</script>";
	}
}
if (isset ($msgaviso)) {
	db_msgbox($msgaviso);
	echo "<script>location.href = 'mat1_mattransfdepto003.php$qry'</script>";
}
if ($db_opcao == 33) {
	echo "
	  <script>document.form1.pesquisar.click();</script>
	  ";
}
?>