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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

?>
<html>
<head>
	<title>DBSeller Informática Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
	db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js, AjaxRequest.js");
	db_app::load("estilos.css, grid.style.css");
	?>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
	<form>
		<fieldset>
			<legend>Atualização do PCASP</legend>
			<table>
				<tr>
					<td><label class="bold" for="exercicio">Exercício:</label></td>
					<td>
						<select id="exercicio" style="width:100px;">
							<option>Selecione</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label class="bold" for="modelo">Modelo:</label></td>
					<td>
						<select id="modelo" style="width:100px;">
							<option>Selecione</option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>

		<input type="button" id="importar" value="Atualizar" />
	</form>
</div>
<script type="text/javascript" >

  const MENSAGENS = 'financeiro.contabilidade.con1_importacaopcasp.';
	var sRpc = "con1_importacaopcasp.RPC.php";

	var oExercicio   = $('exercicio');
	var oModelo      = $('modelo');
	var oBtnImportar = $('importar');

	oBtnImportar.onclick = function () {

		if (empty(oExercicio.value)) {
			return alert(_M(MENSAGENS + "ano_obrigatorio"));
		}

		if (empty(oModelo.value)) {
      return alert(_M(MENSAGENS + "modelo_obrigatorio"));
		}

		if (confirm(_M(MENSAGENS + 'confirmacao_importacao'))) {

      var oParametros = {
        "exec"      : "importar",
        "exercicio" : oExercicio.value,
        "modelo"    : oModelo.value
      };

      new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {


        if (lErro) {

          return alert(oRetorno.mensagem.urlDecode());
        }

        alert(_M(MENSAGENS + 'sucesso_importacao'));
        buscarExercicios();

      }).setMessage("Atualizando plano de contas, aguarde.").execute();
		}
	};

	oExercicio.onchange = function () {

		resertarModelos();

		if (empty(oExercicio.value)) {
			return false;
		}

		var oParametros = {
			"exec"      : "getModelos",
			"exercicio" : oExercicio.value
		};

		new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {

			if (lErro) {
				return alert(oRetorno.mensagem.urlDecode());
			}

			if (oRetorno.modelos.length == 0) {
        return alert(_M(MENSAGENS + 'sem_modelos_cadastrados'));
			}

			for (var oAno of oRetorno.modelos) {

				var oOption = document.createElement('option');
				oOption.value = oAno.id;
				oOption.innerHTML = oAno.nome;
				oModelo.appendChild(oOption);
			}
		}).setMessage("Carregando modelos para o ano, aguarde...").execute();

	};

	function buscarExercicios() {

		var oParametros = {
			"exec" : "getExercicios"
		};

		new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {

			resetarForm();
			if (lErro) {
				return alert(oRetorno.mensagem.urlDecode());
			}

			if (oRetorno.exercicios.length == 0) {
				return alert(_M(MENSAGENS + 'sem_modelos_cadastrados'));
			}

			for (var oAno of oRetorno.exercicios) {

				oOption = document.createElement('option');
				oOption.value = oAno.ano;
				oOption.innerHTML = oAno.ano;
				oOption.disabled = oAno.importado;
				oExercicio.appendChild(oOption);
			}

		}).setMessage("Buscando modelos para plano de contas, aguarde.").execute();
	}

	function buscarRecurso() {

		var oParametros = {
			"exec" : "getRecurso"
		};

		new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {

			if (lErro) {
				return alert(oRetorno.mensagem.urlDecode());
			}

			if (oRetorno.recurso == 0) {
				return alert(_M(MENSAGENS + 'sem_recurso_cadastrado'));
			} else{
				buscarExercicios();
			}

		}).setMessage("Verificando cadastro de recurso, aguarde.").execute();
	}

	function resetarAnos() {

		oExercicio.options.length = 0;

		var oOption = document.createElement('option');
		oOption.value = 0;
		oOption.innerHTML = "Selecione";

		oExercicio.appendChild(oOption);
	}

	function resertarModelos() {

		oModelo.options.length = 0;

		var oOption = document.createElement('option');
		oOption.value = 0;
		oOption.innerHTML = "Selecione";

		oModelo.appendChild(oOption);
	}

	function resetarForm() {

		resertarModelos();
		resetarAnos();
	}
	(function(){
		buscarRecurso();
	})();
</script>
<?php db_menu(); ?>
</body>
</html>