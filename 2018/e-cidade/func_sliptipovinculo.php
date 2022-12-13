<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_slip_classe.php"));
/** [Extensão] - [AutorizacaoRepasse] - Parte 1 */
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$oGet = db_utils::postMemory($_GET);

$clslip   = new cl_slip;
$clrotulo = new rotulocampo;

$clrotulo->label("k17_codigo");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("k17_data");
$clrotulo->label("k17_valor");


$iCodigoMenuAcessado = db_getsession('DB_itemmenu_acessado', false);
$aMenusBloquearSlipNaoAutenticado = array();
$aMenusBloquearSlipNaoAutenticado[] = 9380; // Transf. Financeira - Concessão - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9393; // Transf. Financeira - Recebimento - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9431; // Transf. Bancaria - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9388; // Caucao - Recebimento - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9391; // Caucao - Devoluçao - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9400; // DDO - Pagamento - Estorno
$aMenusBloquearSlipNaoAutenticado[] = 9397; // DDO - Recebimento - Estorno
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="" >
	<center>
		<fieldset style="width:300px;">
			<legend><b>Filtros</b></legend>
			<table border=0>
				<tr>
					<td align="left" nowrap title="<?=$Tk17_data?>"> <? db_ancora(@$Lk17_data,"",3);?>  </td>
					<td align="left" nowrap><? db_inputdata("k17_data",@$k17_data_dia,@$k17_data_mes,db_getsession("DB_anousu"),true,'text',1);    ?></td>
				</tr>
				<tr>
					<td align="left" nowrap title="<?=$Tk17_debito?>"> <? db_ancora(@$Lk17_debito,"",3);?>  </td>
					<td align="left" nowrap><?    db_input("k17_debito",8,$Ik17_debito,true,"text",4,"","chave_k17_debito");	 ?></td>
				</tr>
				<tr>
					<td align="left" nowrap title="<?=$Tk17_credito ?>"> <? db_ancora(@$Lk17_credito,"",3);?>  </td>
					<td align="left" nowrap><?    db_input("k17_credito",8,$Ik17_credito,true,"text",4,"","chave_k17_credito");	 ?></td>
				</tr>
				<tr>
					<td align="left" nowrap title="<?=$Tk17_valor ?>"> <? db_ancora(@$Lk17_valor,"",3);?>  </td>
					<td align="left" nowrap><?    db_input("k17_valor",8,$Ik17_credito,true,"text",4,"onKeyDown=this.value=this.value.replace(',','.')","chave_k17_valor");	 ?></td>
				</tr>
				<tr>
					<td><b>Slip:</b></td>
					<td align="left" nowrap><?    db_input("k17_codigo",8,$Ik17_codigo,true,"text",4,"","chave_k17_codigo");	 ?></td>
				</tr>
			</table>
		</fieldset>
		<input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
		<input name="limpar" type="reset" id="limpar" value="Limpar" >
		<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_slip.hide();">
</form>
<div id="resultadoLovRot">

	<?

	$lRotinasComSituacaoBloqueada = false;
	if (!empty($iCodigoMenuAcessado) && in_array($iCodigoMenuAcessado, $aMenusBloquearSlipNaoAutenticado)) {
		$lRotinasComSituacaoBloqueada = true;
	}

	$campos = "k17_codigo,k17_data,k17_debito,k17_credito,k17_valor,k17_hist,k17_texto, k17_situacao";
	$wh  = null;
	$wh2 = null;
	$where_instit = " and k17_instit = ".db_getsession("DB_instit");
	if (isset($valida)){


		$wh  = " and k17_situacao  $valida";
		$wh2 = " k17_situacao  $valida";

	}else{
		if (isset($chave_k17_codigo)){
			$wh2=" k17_codigo=$chave_k17_codigo";
		}
	}

	$sTipoOperacao = "";
	if (isset($iTipoOperacao)) {
		$sTipoOperacao = " and k153_slipoperacaotipo = {$iTipoOperacao}";
		/** [Extensão] - [AutorizacaoRepasse] - Parte 2 */
	}

	/* [Extensão] - Filtro da Despesa - parte 1 */

	$campos = " distinct ".$campos;

	if (!isset ($pesquisa_chave)) {
		if (isset ($chave_k17_codigo) && trim($chave_k17_codigo) != "") {
			$wh2="$wh2 and k17_codigo=$chave_k17_codigo $where_instit";
			$sql = $clslip->sql_query_tipo_vinculo($chave_k17_codigo, $campos,"k17_data","$wh2 {$sTipoOperacao}");
		} else {
			/*
       *
       */
			$data = "";
			if (isset ($k17_data_dia) && $k17_data_dia != "") {
				$data = "$k17_data_ano-$k17_data_mes-$k17_data_dia";
			}
			if (isset ($chave_k17_debito) && trim($chave_k17_debito) != "") {
				if ($data == "") {
					$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_debito = $chave_k17_debito $wh and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
				} else {
					$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_debito = $chave_k17_debito $wh and k17_data='" . $data . "' and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
				}
			} else
				if (isset ($chave_k17_credito) && trim($chave_k17_credito) != "") {
					if ($data == "") {
						$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_debito = $chave_k17_credito $wh and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
					}else {
						$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_debito = $chave_k17_credito $wh and k17_data='" . $data . "' and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
					}
				} else
					if (isset ($chave_k17_valor) && trim($chave_k17_valor) != "") {
						if ($data == "") {
							$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_valor = $chave_k17_valor $wh  and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
						} else {
							$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " k17_valor = $chave_k17_valor $wh  and k17_data='" . $data . "' and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
						}
					} else {
						if ($data == "") {
							$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, " to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $wh $where_instit {$sTipoOperacao}");
						} else {
							$sql = $clslip->sql_query_tipo_vinculo(null, $campos, null, "  k17_data='" . $data . "' $wh and to_char(k17_data,'YYYY') = '" . db_getsession("DB_anousu") . "' $where_instit {$sTipoOperacao}");
						}
					}
		}


		if ($lRotinasComSituacaoBloqueada) {
			$sql = "select * from ({$sql}) as x where k17_situacao <> ".Transferencia::SITUACAO_NAO_AUTENTICADO;
		}

		/* [Extensão] - Filtro da Despesa - parte 2 */

		db_lovrot($sql,15,"()","",$funcao_js);

	} else {
		if ($pesquisa_chave != null && $pesquisa_chave != "") {

			$sSql = $clslip->sql_query_tipo_vinculo(null,"*",null,"k17_codigo = $pesquisa_chave $wh  and to_char(k17_data,'YYYY') = '".db_getsession("DB_anousu")."' $where_instit {$sTipoOperacao} {$sSituacaoBloqueadas}");

			/* [Extensão] - Filtro da Despesa - parte 3 */

			$result = $clslip->sql_record($sSql);

			if ($clslip->numrows != 0) {
				db_fieldsmemory($result, 0);
				echo "<script>".$funcao_js."('$k17_codigo',false);</script>";
			} else {
				echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
			}
		} else {
			echo "<script>".$funcao_js."('',false);</script>";
		}
	}
	?>

</div>
</center>
</body>
</html>