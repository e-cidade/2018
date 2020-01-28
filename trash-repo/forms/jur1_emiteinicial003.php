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
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_advog_classe.php");
include ("classes/db_inicial_classe.php");
include ("classes/db_inicialcodforo_classe.php");
include ("classes/db_inicialnomes_classe.php");
include ("classes/db_inicialnumpre_classe.php");
include ("classes/db_inicialcert_classe.php");
include ("classes/db_inicialmov_classe.php");
include ("classes/db_termoini_classe.php");
include ("classes/db_arrecad_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao = 3;
$botao		= 3;
$db_opcao = 3;
$verificachave = true;

$cltermoini				= new cl_termoini;
$clinicial				= new cl_inicial;
$clinicialcodforo = new cl_inicialcodforo;
$clinicialnomes		= new cl_inicialnomes;
$clinicialnumpre	= new cl_inicialnumpre;
$clinicialcert		= new cl_inicialcert;
$clinicialmov			= new cl_inicialmov;
$clarrecad				= new cl_arrecad;
$cladvog					= new cl_advog;
$clcgm						= new cl_cgm;
$clrotulo					= new rotulocampo;

$cladvog->rotulo->label();
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");

$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v51_certidao");

if (!isset ($excluir)) {
	if (isset ($inicialini)) {
		$botao = 1;
		$v50_inicialini = $inicialini;
		$v50_inicialfim = $inicialfim;
		$InicialParc = '';
    $vir = "";
		
		$rsTermoini = $cltermoini->sql_record($cltermoini->sql_query(null, null, "distinct inicial", null, " inicial between $v50_inicialini and $v50_inicialfim"));
		if($cltermoini->numrows != 0){
			$db_opcao = 3;
			$db_botao = 3;
			$botao    = 3;
			for($i=0; $i< $cltermoini->numrows; $i++){	
		    $oTermoini = db_utils::fieldsMemory($rsTermoini,$i);
				$InicialParc .= $vir.$oTermoini->inicial;
        $vir = ",";

			}
			if($cltermoini->numrows == 1){
				db_msgbox("Não é possível exlcuir pois existe um parcelamento para esta inicial!");
		  }else{
				db_msgbox("Não é possível excluir pois existe um parcelamento para as inciais $InicialParc");
			}
		
		}else{
			$result_foro = $clinicialcodforo->sql_record($clinicialcodforo->sql_query_file("","*", "", " v55_inicial between $v50_inicialini and $v50_inicialfim"));
			if ($clinicialcodforo->numrows != 0) {
				$db_opcao = 3;
				$db_botao = 3;
				$botao    = 3;
				db_msgbox("Existe um processo no forum para esta inicial!");
		  }
	  }
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
<style type="text/css">
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
</style>
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
<table height="430" width="" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr>
		<td>&nbsp;</td>	
	</tr>
	<tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
    <?
    include ("forms/db_frmemiteinicialexc.php"); 
    ?>   
  </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset ($excluir)) {
	db_inicio_transacao();

	$sqlPardiv = "select v04_tipocertidao as tipocertidao from pardiv where v04_instit  = ".db_getsession('DB_instit') ;
  $rsPardiv  = pg_query($sqlPardiv);
	if (pg_num_rows($rsPardiv) > 0 ) {
		db_fieldsmemory($rsPardiv,0);		
	}else{
    db_msgbox("Configure o parametro para o tipo de debito de certidao do foro ");
	}
	
	for ($inicial = $v50_inicialini; $inicial <= $v50_inicialfim; $inicial ++) {
		echo "<script>termo($inicial,$v50_inicialfim);</script>";
		flush();
		$v50_inicial = $inicial;
		$sqlerro = false;
		
		if ($sqlerro == false) {
			$clinicialcert->v51_inicial = $v50_inicial;
			$clinicialcert->excluir($v50_inicial);
			if ($clinicialcert->erro_status == 0) {
				$erro_msg = $clinicialcert->erro_msg;
				$sqlerro = true;
				break;
			}
		}
		

		$result_numpre = $clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null, "v59_numpre as numpre", null, "v59_inicial=$v50_inicial"));
		if ($clinicialnumpre->numrows > 0) {
			for ($w = 0; $w < $clinicialnumpre->numrows; $w ++) {
				db_fieldsmemory($result_numpre, $w);
				if ($sqlerro == false) {
					$clarrecad->k00_tipo = $tipocertidao;
					$clarrecad->k00_numpre = $numpre;
					$clarrecad->alterar_arrecad("k00_numpre=$numpre");
					if ($clarrecad->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clarrecad->erro_msg;
						break;
					}
				}
			}
		}
		if ($sqlerro == false) {
			$clinicialnumpre->excluir(null, "v59_inicial=$v50_inicial");
			if ($clinicialnumpre->erro_status == 0) {
				$erro_msg = $clinicialnumpre->erro_msg;
				$sqlerro = true;
			}
		}
		if ($sqlerro == false) {
			$clinicialnomes->v58_inicial = $v50_inicial;
			$clinicialnomes->excluir($v50_inicial);
			if ($clinicialnomes->erro_status == 0) {
				$erro_msg = $clinicialnomes->erro_msg;
				$sqlerro = true;
			}
		}
		if ($sqlerro == false) {
			$resul = $clinicialmov->sql_record($clinicialmov->sql_query_file(null, "v56_codmov", null, "v56_inicial=$v50_inicial"));
			
			if ($clinicialmov->numrows > 0) {
				$numrowsmov = $clinicialmov->numrows;
				for ($w = 0; $w < $numrowsmov; $w ++) {
					db_fieldsmemory($resul, $w);
					$clinicialmov->v56_codmov = $v56_codmov;
					$clinicialmov->excluir($v56_codmov);
					if ($clinicialmov->erro_status == 0) {
						$erro_msg = $clinicialmov->erro_msg;
						$sqlerro = true;
						break;
					}
				}
			}
		}
		if ($sqlerro == false) {
			$clinicial->excluir($v50_inicial);
			if ($clinicial->erro_status == 0) {
				$erro_msg = $clinicial->erro_msg;
				$sqlerro = true;
			}

		}
		if ($sqlerro == false) {
			$erro_msg = $clinicial->erro_msg;
		}
	}
	db_fim_transacao($sqlerro);
}
$func_iframe = new janela('db_iframe', '');
$func_iframe->posX = 1;
$func_iframe->posY = 20;
$func_iframe->largura = 780;
$func_iframe->altura = 430;
$func_iframe->titulo = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if (isset ($excluir)) {
	
	db_msgbox($erro_msg);
	
	if ($clinicial->erro_status == "0") {
		//$clinicial->erro(true, false);
		if ($clinicial->erro_campo != "") {
			echo "<script> document.form1.".$clinicial->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clinicial->erro_campo.".focus();</script>";
		}
	} else {
		//$clinicial->erro(true, false);
		  db_redireciona("jur1_emiteinicial003.php");
	  }
	
}
?>