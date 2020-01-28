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
include ("classes/db_contranslr_classe.php");
include ("classes/db_conplano_classe.php");
include ("classes/db_contranslan_classe.php");
include ("classes/db_pctipocompra_classe.php");
include ("classes/db_emprestotipo_classe.php");
include ("classes/db_db_config_classe.php");
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_config = new cl_db_config;
$clconplano = new cl_conplano;
$clcontranslr = new cl_contranslr;
$clcontranslan = new cl_contranslan;
$clpctipocompra = new cl_pctipocompra;
$clemprestotipo = new cl_emprestotipo;
$db_opcao = 22;
$db_botao = false;
if (isset ($alterar) || isset ($excluir) || isset ($incluir)) {
	$sqlerro = false;
	/*
	$clcontranslr->c47_seqtranslr = $c47_seqtranslr;
	$clcontranslr->c47_seqtranslan = $c47_seqtranslan;
	$clcontranslr->c47_debito = $c47_debito;
	$clcontranslr->c47_credito = $c47_credito;
	$clcontranslr->c47_obs = $c47_obs;
	*/
}
if (isset ($excluir_geral)) {
	$sqlerro = false;
	db_inicio_transacao();
	$clcontranslr->excluir(null, " c47_seqtranslan = $c47_seqtranslan");
	$erro_msg = $clcontranslr->erro_msg;
	if ($clcontranslr->erro_status == 0) {
		$sqlerro = true;
	}
	db_fim_transacao($sqlerro);
}
elseif (isset ($incluir)) {
	if ($sqlerro == false) {
		db_inicio_transacao();

		if (isset ($estrutural) && $estrutural != "") {
			if ($estrutural == '' || $estrutural == 0) {
				$sqlerro = true;
				$erro_msg = "Estrutural não preenchido!";
			}
			if ($sqlerro == false) {
				$result = $clconplano->sql_record($clconplano->sql_query_ele(null, "c61_reduz as reduz", null, "c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut like '$estrutural%'"));
				$numrows = $clconplano->numrows;

				if ($numrows == 0) {
					$erro_msg = "Não foi encontrado reduzidos pelo estrutural fornecido.";
					$sqlerro = true;
				}
				$codz = '';
				$sep = '';
				for ($i = 0; $i < $numrows; $i ++) {
					db_fieldsmemory($result, $i);

					if ($c47_compara == 3) {
						$clcontranslr->sql_record($clcontranslr->sql_query(null, "c47_ref", "", "c47_seqtranslan= $c47_seqtranslan and c47_ref=$reduz"));
						if ($clcontranslr->numrows > 0) {
							$codz .= $sep.$reduz;
							$sep = ', ';
							continue;
						}
						$clcontranslr->c47_ref = $reduz;

					}
					elseif ($c47_compara == 2) {
						$clcontranslr->sql_record($clcontranslr->sql_query(null, "c47_ref", "", "c47_seqtranslan= $c47_seqtranslan and c47_credito =$reduz"));
						if ($clcontranslr->numrows > 0) {
							$codz .= $sep.$reduz;
							$sep = ', ';
							continue;
						}

						$clcontranslr->c47_credito = $reduz;

					}
					elseif ($c47_compara == 1) {
						$clcontranslr->sql_record($clcontranslr->sql_query(null, "c47_ref", "", "c47_seqtranslan= $c47_seqtranslan and c47_debito =$reduz"));
						if ($clcontranslr->numrows > 0) {
							$codz .= $sep.$reduz;
							$sep = ', ';
							continue;
						}
						$clcontranslr->c47_debito = $reduz;
					}

					$clcontranslr->incluir(null);
					$erro_msg = $clcontranslr->erro_msg;
					if ($clcontranslr->erro_status == 0) {
						$sqlerro = true;
						break;
					}
				}
			}
		} else {
			$clcontranslr->incluir($c47_seqtranslr);
			$erro_msg = $clcontranslr->erro_msg;
			if ($clcontranslr->erro_status == 0) {
				$sqlerro = true;
			}
		}
		db_fim_transacao($sqlerro);
	}
}
elseif (isset ($alterar)) {
	if ($sqlerro == false) {
		db_inicio_transacao();
		$clcontranslr->alterar($c47_seqtranslr);
		$erro_msg = $clcontranslr->erro_msg;
		if ($clcontranslr->erro_status == 0) {
			$sqlerro = true;
		}
		db_fim_transacao($sqlerro);
	}
}
elseif (isset ($excluir)) {
	if ($sqlerro == false) {
		db_inicio_transacao();
		$clcontranslr->excluir($c47_seqtranslr);
		$erro_msg = $clcontranslr->erro_msg;
		if ($clcontranslr->erro_status == 0) {
			$sqlerro = true;
		}
		db_fim_transacao($sqlerro);
	}
}
elseif (isset ($opcao)) {
	$result = $clcontranslr->sql_record($clcontranslr->sql_query($c47_seqtranslr));
	if ($result != false && $clcontranslr->numrows > 0) {
		db_fieldsmemory($result, 0);
		if ($c47_debito != '' && $c47_debito != 0) {
			$result = $clconplano->sql_record($clconplano->sql_query_reduz(null, 'c60_descr', '', " c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=$c47_debito"));
			if ($clconplano->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
		}
		if ($c47_credito != '' && $c47_credito != 0) {
			$result = $clconplano->sql_record($clconplano->sql_query_reduz(null, 'c60_descr as c60_descr_credito', '', " c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=$c47_credito"));
			if ($clconplano->numrows > 0) {
				db_fieldsmemory($result, 0);
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?



include ("forms/db_frmcontranslr.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?



if (isset ($alterar) || isset ($excluir) || isset ($incluir) || isset ($excluir_geral)) {
	if (isset ($codz) && $codz != '') {
		db_msgbox("Os seguintes códigos reduzidos estão repetindo, $codz");
	} else {
		db_msgbox($erro_msg);
		if ($clcontranslr->erro_campo != "") {
			echo "<script> document.form1.".$clcontranslr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clcontranslr->erro_campo.".focus();</script>";
		}
	}
}
?>