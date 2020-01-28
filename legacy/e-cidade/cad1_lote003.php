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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_lotedist_classe.php");
include ("classes/db_testada_classe.php");
include ("classes/db_testpri_classe.php");
include ("classes/db_lote_classe.php");
include ("classes/db_loteloc_classe.php");
include ("classes/db_loteloteam_classe.php");
include ("classes/db_loteam_classe.php");
include ("classes/db_carlote_classe.php");
include ("classes/db_face_classe.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_lotesetorfiscal_classe.php");
include ("classes/db_cfiptu_classe.php");

include ("classes/db_testadanumero_classe.php");
include ("classes/db_iptubase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cllote = new cl_lote;
$clloteloc = new cl_loteloc;
$clloteam = new cl_loteam;
$clloteloteam = new cl_loteloteam;
$clcarlote = new cl_carlote;
$cllotedist = new cl_lotedist;
$cltestada = new cl_testada;
$cltestpri = new cl_testpri;
$clface = new cl_face;
$cllotesetorfiscal = new cl_lotesetorfiscal;
$clcfiptu = new cl_cfiptu;

$cliptubase = new cl_iptubase;
$cltestadanumero = new cl_testadanumero;

//die($clcfiptu->sql_query_file(db_getsession('DB_anousu'),'j18_utilizasetfisc',"",""));
$rsResultmostra = ($clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'), '*', "", "")));
if ($clcfiptu->numrows > 0) {
	db_fieldsmemory($rsResultmostra, 0);
	$mostrasetfiscal = $j18_utilizasetfisc;
	$numerotestada = $j18_testadanumero;
}
$db_opcao = 3;
$db_botao = false;
if (isset ($excluir)) {
	$sqlerro = false;
	db_inicio_transacao();
	$result = $clloteloteam->sql_record($clloteloteam->sql_query_file("", "", "loteloteam.j34_loteam as loteam", "", "loteloteam.j34_idbql=$j34_idbql"));
	$numrows = $clloteloteam->numrows;
	if ($numrows > 0) {
		db_fieldsmemory($result, 0);
		$clloteloteam->j34_idbql = $j34_idbql;
		$clloteloteam->j34_loteam = $loteam;
		$clloteloteam->excluir($j34_idbql);
		if ($clloteloteam->erro_status == 0) {
			$erro_msg = $clloteloteam->erro_msg;
			$sqlerro = true;
		}
	}

	//  EXCLUSAO  NA TABELA LOTESETORFISCAL
	if (isset ($mostrasetfiscal) && $mostrasetfiscal == "t") {
		$cllotesetorfiscal->excluir("", " j91_idbql = $j34_idbql ");
	}
	// =====================================
       
        //EXCLUSAO NA TABELA LOTELOC
	$clloteloc->excluir($j34_idbql);

	$cltestpri->j49_idbql = $j34_idbql;
	$cltestpri->excluir($j34_idbql);
	if ($cltestpri->erro_status == 0) {
		$erro_msg = $cltestpri->erro_msg;
		$sqlerro = true;
	}
	if ($sqlerro == false) {
		$result = $clcarlote->sql_record($clcarlote->sql_query_file($j34_idbql));
		$xx = $clcarlote->numrows;
		for ($i = 0; $i < $xx; $i ++) {
			db_fieldsmemory($result, $i);
			$clcarlote->j35_idbql = $j35_idbql;
			$clcarlote->j35_caract = $j35_caract;
			$clcarlote->excluir($j35_idbql, $j35_caract);
			if ($clcarlote->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $clcarlote->erro_msg;
				break;
			}
		}

	}

	if ($sqlerro == false) {
		$result = $cltestada->sql_record($cltestada->sql_query($j34_idbql));
		$xx = $cltestada->numrows;
		for ($i = 0; $i < $xx; $i ++) {
			db_fieldsmemory($result, $i);
			//===================================================================================================================================================
			if(isset($mostrasetfiscal) && $mostrasetfiscal=='t'){
				if ($sqlerro == false) {
					$cltestadanumero->j15_idbql = $j36_idbql;
					$cltestadanumero->j15_face = $j36_face;
					$cltestadanumero->excluir("", " j15_idbql = $j36_idbql and j15_face = $j36_face ");
					if ($cltestadanumero->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $cltestadanumero->erro_msg;
						break;
					}
				}
			}
			//===================================================================================================================================================

			if ($sqlerro == false) {
				$cltestada->j36_idbql = $j36_idbql;
				$cltestada->j36_face = $j36_face;
				$cltestada->excluir($j36_idbql, $j36_face);
				if ($cltestada->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $cltestada->erro_msg;
					break;
				}
			}
		}
	}
	if ($sqlerro == false) {
		if ($j54_codigo != "") {
			$cllotedist->j54_idbql = $j34_idbql;
			$cllotedist->excluir($j34_idbql);
			if ($cllotedist->erro_status == 0) {
				$erro_msg = $cllotedist->erro_msg;
				$sqlerro = true;
			}
		}
	}
	/**/
	$result_base = $cliptubase->sql_record($cliptubase->sql_query_file(null, "*", null, "j01_idbql=$j34_idbql"));
	$numrowst = $cliptubase->numrows;
	/**/

	if ($sqlerro == false) {
		$cllote->excluir($j34_idbql);
		if (!isset ($erro_msg) || $erro_msg == "") {
			$erro_msg = $cllote->erro_msg;
		}

		if ($cllote->erro_status == 0) {
			if ($numrowst > 0) {
				db_msgbox(" Impossível excluir. Existe matrícula cadastrada para este lote. ");
				$erro_msg = $cllote->erro_msg;
				break;
			} else {
				$erro_msg = $cllote->erro_msg;
				$sqlerro = true;
			}
		}
	}
	//exit;
	db_fim_transacao();

} else
	if (isset ($chavepesquisa)) {
		$result = $cllote->sql_record($cllote->sql_query($chavepesquisa));
		db_fieldsmemory($result, 0);

		$rsResultsetfis = $cllotesetorfiscal->sql_record($cllotesetorfiscal->sql_query_file("", "j91_codigo", "", " j91_idbql = $chavepesquisa"));
		if ($cllotesetorfiscal->numrows != 0) {
			db_fieldsmemory($rsResultsetfis, 0);
		}

		$result = $cllotedist->sql_record($cllotedist->sql_query($chavepesquisa));
		if ($cllotedist->numrows != 0) {
			db_fieldsmemory($result, 0);
		}
		$result = $clloteloteam->sql_record($clloteloteam->sql_query("", "", "loteloteam.j34_loteam,loteam.j34_descr", "", "loteloteam.j34_idbql=$chavepesquisa"));
		$numrows = $clloteloteam->numrows;
		if ($result > 0) {
			db_fieldsmemory($result, 0);
		}

		$result = $clcarlote->sql_record($clcarlote->sql_query($chavepesquisa));
		$caracteristica = null;
		$car = "";

		for ($i = 0; $i < $clcarlote->numrows; $i ++) {
			db_fieldsmemory($result, $i);
			$caracteristica .= $car.$j35_caract;
			$car = "X";

		}

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
<script>
function js_load_lote(){
  <?


if (!isset ($chavepesquisa)) {
	echo "js_pesquisa();";
}
?>
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_load_lote();" >
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


include ("forms/db_frmlote.php");
?>
    </center>
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($cllote->erro_status == "0") {
	$cllote->erro(true, false);
} else {
	$cllote->erro(true, true);
};
?>