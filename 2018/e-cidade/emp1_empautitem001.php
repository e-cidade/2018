<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_pcmater_classe.php"));
require_once(modification("classes/db_pcmaterele_classe.php"));
require_once(modification("classes/db_orcparametro_classe.php"));
require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clempautitem = new cl_empautitem;
$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clorcparametro = new cl_orcparametro;
$clorcelemento = new cl_orcelemento;
$db_opcao = 1;
$db_botao = true;

//
// se a opção abaixo for 'false' não é permitido incluir ítens de desdobramentos diferentes
//
$libera_desdobramento = false;

if (isset ($consultando) || isset ($incluir) || isset ($alterar)) {

	$sqlerro = false;
	//rotina que verifica se o item é válido
	$pc01_servico = null;
	$result = $clpcmater->sql_record($clpcmater->sql_query_elemento($e55_item, "o56_elemento as elemento03, pc01_servico"));
	if ($clpcmater->numrows == 0) {
		$sqlerro = true;
		$erro_msg = "Item não válido!";
	} else {
		$pc01_servico = pg_result($result, 0, "pc01_servico");
	}
	//
	// o codigo abaixo pega o primeiro ítem que foi incluido na autorização
	//
	if ($sqlerro == false) {
		$result = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori, null, "e55_item as item, e55_sequen as seq, e55_codele as desdobramento", "e55_sequen"));
	}
	if ($sqlerro == false && $clempautitem->numrows > 0) {
		db_fieldsmemory($result, 0);
		// se entramos nesta condição  é porque ja temos ítens incluidos na autorização
		//  este teste verifica a existencia de itens com desdobramentos diferentes
		//  para prefeituras com orçamento no desdobramento isto não é permitido de fato
		//  para prefeituras com orçamento no elemento isto é facultativo, porém o TCERS não aconselha

		// o codigo abaixo procura o codele do item que esta sendo incluido
		$result = $clpcmater->sql_record($clpcmater->sql_query_elemento($e55_item, "o56_codele as desdobramento02"));
		db_fieldsmemory($result, 0); //$desdobramento2 é o desdobramento a ser incluido

		/** aqui esta o código que libera ou não ítens de desdobramentos diferentes */
		$liberado = true;
		if ($desdobramento != $pc07_codele   &&  isset($incluir)  && $incluir=='Incluir' ){
			$liberado = false;
		}
		if ($liberado == false) {
			$sqlerro = true;
			$clempautitem->erro_status = "0";
			$erro_msg = "Desdobramento do item diferente !";
		} // endif
	} // endif

}

if (empty ($result_elemento) && isset($e55_item) && !empty($e55_item)) {
	$result_elemento = $clpcmaterele->sql_record($clpcmaterele->sql_query(null, null, "pc07_codele,o56_descr", "", "pc07_codmater=$e55_item "));
}

if (!isset($lControlaQuantidade)) {
	$lControlaQuantidade = "false";
}

if (isset ($autori_importa)) {
	$sqlerro = false;
	db_inicio_transacao();
	//rotina para importar da tabela empautitem
	if ($sqlerro == false) {
		$atual_autori = $e55_autori;
		$result = $clempautitem->sql_record($clempautitem->sql_query_file($autori_importa));
		$numrows = $clempautitem->numrows;

		//rotina que pega a sequencia
		$result02 = $clempautitem->sql_record($clempautitem->sql_query_file($atual_autori, null, "max(e55_sequen)+1 as e55_seq"));
		db_fieldsmemory($result02, 0);
		if ($e55_seq == '') {
			$e55_seq = 1;
		}
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i ++) {
				db_fieldsmemory($result, $i);
				//rotina para testar se os elementos são iguais
				$liberado = true;
				if (isset ($elemento01)) {
					$result02 = $clpcmater->sql_record($clpcmater->sql_query_elemento($e55_item, "o56_codele as elemento02"));
					db_fieldsmemory($result02, 0); //$codele é o primeiro elemento incluido

					//verifica nos parametros
					$result02 = $clorcparametro->sql_record($clorcparametro->sql_query_file(null, "o50_subelem"));
					db_fieldsmemory($result02, 0);
					if ($o50_subelem == 't') {
						if ($elemento01 != $elemento02) {
							$liberado = false;
						}
					} else {
						if (substr($elemento01, 0, 7) != substr($elemento02, 0, 7)) {
							$liberado = false;
						}
					}
				}
				//final da rotina

				if ($liberado == true) {



					//$valorunitarioautitem = db_formatar($e55_vltot/$e55_quant,"vdec"," ",4);
					$clempautitem->e55_codele = $codele;
					$clempautitem->e55_autori = $atual_autori;
					$clempautitem->e55_sequen = $e55_seq;
					$clempautitem->e55_item = $e55_item;
					$clempautitem->e55_quant = $e55_quant;
					$clempautitem->e55_vltot = $e55_vltot;
					$clempautitem->e55_vlrun = $e55_vluni;
					$clempautitem->e55_descr = $e55_descr;
					$clempautitem->e55_servicoquantidade = $lControlaQuantidade;
					$clempautitem->incluir($atual_autori, $e55_seq);
					if ($clempautitem->erro_status == "0") {
						$erro_msg = $clempautitem->erro_msg;
						$sqlerro = true;
					}
					$e55_seq ++;
				} else {
					$sqlerro = true;
					$clempautitem->erro_status = "0";
					$clempautitem->erro_msg = "Os itens da autorização $autori_importa possui elementos diferentes dos itens já cadastrados nesta autorização!";
				}
			}
		}
		$e55_autori = $atual_autori;
	}
	//final
	//$sqlerro=true;
	db_fim_transacao($sqlerro);
} else
	if (isset ($incluir)) {
		db_inicio_transacao();

		if ($sqlerro == false) {
			$clempautitem->e55_autori = $e55_autori;
			$result = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori, null, "max(e55_sequen)+1 as e55_sequen"));
			db_fieldsmemory($result, 0);
			if ($e55_sequen == '') {
				$e55_sequen = 1;
			}
			//rotina para pegar o codele da tabela pcmaterele
			$codele = @ $pc07_codele;
			//	final

			$clempautitem->e55_codele = $codele;
			$clempautitem->e55_sequen = $e55_sequen;
			$clempautitem->e55_vlrun = $e55_vluni;



			$clempautitem->e55_servicoquantidade = $lControlaQuantidade;
			$clempautitem->incluir($e55_autori, $e55_sequen);
			$erro_msg = $clempautitem->erro_msg;
			if ($clempautitem->erro_status == "0") {
				$sqlerro = true;
			} else {
				$e55_sequen = $clempautitem->e55_sequen;
				$e55_autori = $clempautitem->e55_autori;
			}
			$sSqlValor  = "update empautoriza ";
			$sSqlValor .= "   set e54_valor = (select sum(e55_vltot) from empautitem where e55_autori = {$e55_autori} )";
			$sSqlValor .= " where e54_autori = {$e55_autori}";
			$rs = db_query($sSqlValor);
		}
		// $sqlerro=true;
		db_fim_transacao($sqlerro);
	} else {
    if (isset ($alterar)) {
      db_inicio_transacao();
      if ($sqlerro == false) {

        $clempautitem->e55_autori            = $e55_autori;
        $clempautitem->e55_sequen            = $e55_sequen;
        $clempautitem->e55_codele            = $pc07_codele;
        $clempautitem->e55_vlrun             = $e55_vluni;
        $clempautitem->e55_servicoquantidade = $lControlaQuantidade;
        if ($lControlaQuantidade == 'false') {
          $clempautitem->e55_matunid = !empty($e55_matunid) ? $e55_matunid : $e55_matuniddefault;
        }
        $clempautitem->alterar($e55_autori, $e55_sequen);
        $erro_msg = $clempautitem->erro_msg;
        if ($clempautitem->erro_status == "0") {
          $sqlerro = true;
        }
        $sSqlValor = "update empautoriza ";
        $sSqlValor .= "   set e54_valor = (select sum(e55_vltot) from empautitem where e55_autori = {$e55_autori} )";
        $sSqlValor .= " where e54_autori = {$e55_autori}";
        $rs = db_query($sSqlValor);
      }
      db_fim_transacao($sqlerro);
    } else {
      if (isset ($excluir)) {
        $sqlerro = false;
        db_inicio_transacao();
        $clempautitem->e55_autori = $e55_autori;
        $clempautitem->e55_sequen = $e55_sequen;
        $clempautitem->excluir($e55_autori, $e55_sequen);
        $erro_msg = $clempautitem->erro_msg;
        if ($clempautitem->erro_status == "0") {
          $sqlerro = true;
        }
        $sSqlValor = "update empautoriza ";
        $sSqlValor .= "   set e54_valor = (select sum(e55_vltot) from empautitem where e55_autori = {$e55_autori} )";
        $sSqlValor .= " where e54_autori = {$e55_autori}";
        $rs = db_query($sSqlValor);
        db_fim_transacao($sqlerro);
      } else {
        if (isset ($opcao) && empty ($consultando)) {

          $result = $clempautitem->sql_record($clempautitem->sql_query($e55_autori, $e55_sequen));

          db_fieldsmemory($result, 0);
          //echo "<BR><BR>".($clpcmaterele->sql_query(null,null,"pc07_codele,o56_descr","","pc07_codmater=$e55_item and o56_elemento = '$o56_elemento'" ));
          $result_elemento = $clpcmaterele->sql_record($clpcmaterele->sql_query(null, null, "pc07_codele,o56_descr", "", "pc07_codmater=$e55_item and o56_elemento = '$o56_elemento'"));
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
			<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
				<center>
					<?


					include(modification("forms/db_frmempautitem.php"));
					?>
				</center>
			</td>
		</tr>
	</table>
	</body>
	</html>
<?


if (isset ($incluir) || isset ($alterar) || isset ($excluir) || isset ($autori_importa)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if ($clempautitem->erro_campo != "") {
			echo "<script> document.form1.".$clempautitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clempautitem->erro_campo.".focus();</script>";
		}
	} else {
		// variavel $tot_valor é gerada no formulário
		echo "
		            <script> 
				(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_empautidot.js_calc('$tot_valor');\n
		            </script> 
		   ";

		//db_msgbox($erro_msg);
	}
} else
	if (isset ($liberado)) {
		if ($liberado == false) {
			db_msgbox("Elemento do item diferente!");
			echo "
					      <script>
						 document.form1.e55_item.value='';
						 document.form1.pc01_descrmater.value='';
						 document.form1.submit();
					      </script>
					   ";
		} else {
			echo "
					      <script>
						document.form1.e55_quant.focus();
					      </script>
					   ";
		}
	}
if (isset ($consultando)) {
	if(isset($pc01_servico) and $pc01_servico=='t') {
		echo "<script>document.form1.e55_vluni.focus();</script>";
	} else {
		echo "<script>document.form1.e55_quant.focus();</script>";
	}
} else {
	echo "<script>document.form1.e55_item.focus();</script>";
}
?>