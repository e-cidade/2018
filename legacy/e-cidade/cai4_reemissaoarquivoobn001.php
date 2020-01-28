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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$oGET        = db_utils::postMemory($_GET);
$oEmpagegera = new cl_empagegera;
$oRotulo     = new rotulocampo;
$oEmpagetipo = new cl_empagetipo;
$oEmpagegera->rotulo->label();
$oEmpagetipo->rotulo->label();

$iTipoTransmissao = isset($oGET->tipo_transmissao) ? (int) $oGET->tipo_transmissao : null;
?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" content="0">
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<?php
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("datagrid.widget.js");
	db_app::load("DBLancador.widget.js");
	db_app::load("strings.js");
	db_app::load("grid.style.css");
	db_app::load("estilos.css");
	db_app::load("AjaxRequest.js");
	db_app::load("classes/dbViewAvaliacoes.classe.js");
	db_app::load("widgets/windowAux.widget.js");
	db_app::load("widgets/dbmessageBoard.widget.js");
	db_app::load("widgets/DBDownload.widget.js");
	db_app::load("widgets/DBAncora.widget.js");
	db_app::load("widgets/dbtextField.widget.js");
	db_app::load("dbcomboBox.widget.js");
	?>
</head>

<body class="body-default">

<div class="container">

	<form name="form1" method="post" action="">

		<fieldset style="width: 550px;">

			<legend>
				<strong>
				<?php if ($iTipoTransmissao == 3) : ?>
				Regerar Arquivo PagFor
				<?php else : ?>
				Regerar Arquivo OBN
				<?php endif ?>
				</strong>
			</legend>

			<table border='0'>

				<tr>
					<td title="<?=$Te87_codgera?>">
						<?php db_ancora($Le87_codgera, "js_pesquisa_gera(true);", 1) ?>
					</td>

					<td>
						<?php
						db_input("e87_codgera", 10, $Ie87_codgera, true, "text", 1, "onchange='js_pesquisa_gera(false);'");
						db_input("e87_descgera", 40, $Ie87_descgera, true, "text", 3);
						?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="bold" for="data_geracao">Data de Geração:</label>
					</td>

					<td>
						<?php
						$iGeracaoDia = date('d', db_getsession('DB_datausu'));
						$iGeracaoMes = date('m', db_getsession('DB_datausu'));
						$iGeracaoAno = date('Y', db_getsession('DB_datausu'));
						db_inputdata('data_geracao', $iGeracaoDia, $iGeracaoMes, $iGeracaoAno, true, 'text', 1);
						?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="bold" for="data_autorizacao">Data de Autorização:</label>
					</td>

					<td>
						<?php
						db_inputdata('data_autorizacao', null, null, null, true, 'text', 1);
						?>
					</td>
				</tr>

			</table>

		</fieldset>

		<div style="margin-top: 10px;">
			<input name="regerar" type="button" id="regerar" value="Regerar">
			<input name="tipo_transmissao" id="tipo_transmissao" type="hidden" value="<?php echo $iTipoTransmissao ?>">
		</div>

	</form>

</div>

<?php db_menu(); ?>

<script>
var PAGFOR  = <?php echo ArquivoTransmissao::TRANSMISSAO_PAGFOR ?>;

var oInputCodigo          = $("e87_codgera");
var oInputDescricao       = $("e87_descgera");
var oInputDataGeracao     = $("data_geracao");
var oInputDataAutorizacao = $("data_autorizacao");
var iTipoTransmissao      = $F("tipo_transmissao");

var sUrlRPC = 'cai4_arquivoBanco004.RPC.php';
var sMetodo = 'regerarArquivoObn';
if (iTipoTransmissao == PAGFOR) {

	sUrlRPC = 'cai4_gerararquivoPAGFOR.RPC.php';
	sMetodo = 'regerarArquivo';
}

$('regerar').observe('click', regerarArquivo);

function regerarArquivo() {

	if (oInputCodigo.value == '') {
		return alert('O campo Código é de preenchimento obrigatório.');
	}
	if (oInputDataGeracao.value == '') {
		return alert('O campo Data de Geração é de preenchimento obrigatório.');
	}
	if (oInputDataAutorizacao.value == '') {
		return alert('O campo Data de Autorização é de preenchimento obrigatório.');
	}

	if (js_comparadata(oInputDataGeracao.value, oInputDataAutorizacao.value, '>')) {
		return alert('A Data de Geração não pode ser maior que a Data da Autorização.');
	}

	var oParametros = {
		'exec'       : sMetodo,
		'iCodGera'   : oInputCodigo.value,
		'dtGeracao'  : js_formatar(oInputDataGeracao.value , 'd'),
		'dtAutoriza' : js_formatar(oInputDataAutorizacao.value, 'd')
	};
	new AjaxRequest(sUrlRPC, oParametros, retornoRegerarArquivo)
	  .setMessage('Regerando arquivo. Aguarde...')
	  .execute();
}

function retornoRegerarArquivo(oRetorno, lErro) {

	if (lErro == true) {
		return alert(oRetorno.mensagem.urlDecode());
	}

	var oDownload = new DBDownload();
	oDownload.addFile(oRetorno.sArquivo, oRetorno.sArquivo);
	oDownload.show();
}

function js_pesquisa_gera(mostra) {

	var sUrl = 'func_empagegera.php?';
	var sFiltroPagFor = '&lFiltroOBN=1';
	if (iTipoTransmissao == PAGFOR) {
		sFiltroPagFor = '&lFiltroPagFor=1';
	}

	if (mostra == true) {

		sUrl += 'funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera' + sFiltroPagFor;
		js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empagegera', sUrl, 'Pesquisa de Arquivos Gerados', true);
	} else {

		if (oInputCodigo.value != '') {

			sUrl += 'funcao_js=parent.js_mostragera&pesquisa_chave=' + oInputCodigo.value + sFiltroPagFor;
			js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empagegera', sUrl, 'Pesquisa', false);
		} else {
			oInputDescricao.value = '';
		}
	}
}

function js_mostragera(chave, erro) {

	oInputDescricao.value = chave;
	if (erro == true) {

		oInputCodigo.focus();
		oInputCodigo.value = '';
	}
}

function js_mostragera1(chave1, chave2) {

	oInputCodigo.value  = chave1;
	oInputDescricao.value = chave2;
	db_iframe_empagegera.hide();
}

</script>

</body>
</html>
