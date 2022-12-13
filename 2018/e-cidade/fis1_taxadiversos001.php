<?php
/**
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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("y119_natureza");
$clrotulo->label("y119_formula");
$clrotulo->label("y119_unidade");
$clrotulo->label("y119_tipo_periodo");
$clrotulo->label("y119_tipo_calculo");

$aTiposPeriodo = array(
	'A'=>'Anual',
	'M'=>'Mensal',
	'D'=>'Diária'
);

$aTiposCalculo = array(
	'0'=>'Selecione',
	'U'=>'Único',  // Fechado
	'G'=>'Geral'   // Aberto
);
?>
<html>
<head>
	<meta http-equiv="Expires" CONTENT="0">
	<?php
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("strings.js");
	db_app::load("AjaxRequest.js");
	db_app::load("widgets/DBLookUp.widget.js");
	db_app::load("datagrid.widget.js");
	db_app::load("widgets/Collection.widget.js");
	db_app::load("widgets/DatagridCollection.widget.js");
	db_app::load("widgets/FormCollection.widget.js");
  db_app::load("widgets/DBHint.widget.js");
  db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
	db_app::load("estilos.css,grid.style.css");
	?>
</head>
<body>
<div class="container">
	<form method="POST" id="formTaxa">
		<fieldset>
			<Legend>Taxa</Legend>
			<table class="form-container">
				<tr>
					<td>
						<label for="grupotaxadiversos" id="lbl_grupotaxadiversos"><a href="javascript:void(0)">Grupo:</a></label>
					</td>
					<td>
						<input type="text"   name="grupotaxadiversos" id="grupotaxadiversos" size="10" maxlength="10" data="y118_sequencial" />
						<input type="text"   name="y118_descricao" id="y118_descricao" maxlength="50" class="field-size9" />
						<input type="hidden" name="codigo" id="codigo">
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Ty119_natureza; ?>">
						<label id="lbl_natureza" for="natureza"><?php echo $Ly119_natureza; ?></label>
					</td>
					<td>
						<?php db_textarea('natureza', 5, 60, $Iy119_natureza, true, "text", 1); ?>
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Ty119_formula; ?>">
						<label id="lbl_formula" for="formula"><a href="javascript:void(0)"><?php echo $Ly119_formula; ?></a></label>
					</td>
					<td>
						<input type="text" name="formula" id="formula" size="10" data="db148_sequencial" />
						<input type="text" id="formula_descricao" data="db148_nome" class="readonly field-size9" readonly="readonly" />
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Ty119_unidade; ?>">
						<label id="lbl_unidade" for="unidade"><?php echo $Ly119_unidade; ?></label>
					</td>
					<td>
						<?php db_select('unidade', array(), false, 1); ?>
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Ty119_tipo_periodo; ?>">
						<label id="lbl_tipo_periodo" for="tipo_periodo">Tipo de Período:</label>
					</td>
					<td>
						<?php db_select('tipo_periodo', $aTiposPeriodo, false, 1); ?>
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Ty119_tipo_calculo; ?>">
						<label id="lbl_tipo_calculo" for="tipo_calculo">Tipo de Cálculo:</label>
					</td>
					<td>
						<?php db_select('tipo_calculo', $aTiposCalculo, false, 1); ?>
					</td>
				</tr>
			</table>
		</fieldset>
		<input type="button" value="Salvar"  id="btnSalvar" onclick="salvar()" >
		<input type="button" value="Excluir" id="btnExcluir" disabled>
		<input type="button" value="Novo"    id="btnNovo" disabled>
	</form>
</div>
<div class="container">
	<fieldset>
		<legend>Taxas</legend>
		<div id="gridTaxas" style="width:900px"></div>
	</fieldset>
</div>
<?php db_menu(); ?>
</body>
</html>
<script type="text/javascript">

	var oCollectionTaxas = Collection.create().setId('codigo');
	var oGridTaxas       = montarGrid(oCollectionTaxas, 'gridTaxas');
	var oFormCollection  = FormCollection.create(oGridTaxas, $('formTaxa'));
	oFormCollection.makeBehavior($('btnExcluir'), 'delete', excluir);
	oFormCollection.onAfterSelectRow(function(acao){

		oAncoraGrupoTaxa.habilitar();
		oAncoraFormula.habilitar();

		if(acao == 'E') {

			oAncoraGrupoTaxa.desabilitar();
			oAncoraFormula.desabilitar();
		}
	});

	var fnClickNovo = oFormCollection.events.onClickCancel.bind(oFormCollection);
	oFormCollection.events.onClickCancel = function () {

		fnClickNovo();
		oAncoraGrupoTaxa.habilitar();
		oAncoraFormula.habilitar();
	};

	oFormCollection.makeBehavior($('btnNovo'), 'cancel');

	var oAncoraGrupoTaxa = new DBLookUp($('lbl_grupotaxadiversos'),
		$('grupotaxadiversos'),
		$('y118_descricao'),
		{
			sArquivo : 'func_grupotaxadiversos.php',
			fCallBack: function() {
				getTaxas();
			},
			sObjetoLookUp : 'db_iframe_grupotaxadiversos',
			sLabel : 'Pesquisar Grupo'
		});

	var oAncoraFormula = new DBLookUp($('lbl_formula'),
		$('formula'),
		$('formula_descricao'),
		{
			sArquivo : 'func_db_formulas.php',
			sObjetoLookUp : 'db_iframe_db_formulas',
			sLabel : 'Pesquisar Fórmula'
		});

	oAncoraGrupoTaxa.callBackChange = function(id, erro, descricao) {

		this.oInputDescricao.value = descricao;
		this.oCallback.onChange(erro, arguments);

		if (this.oParametros.fCallBack) {
			this.oParametros.fCallBack();
		}
	}.bind(oAncoraGrupoTaxa);

	oAncoraFormula.callBackChange = function(id, erro, descricao) {

		this.oInputDescricao.value = descricao;
		this.oCallback.onChange(erro, arguments);

		if (this.oParametros.fCallBack) {
			this.oParametros.fCallBack();
		}
	}.bind(oAncoraFormula);

	carregarComboUnidades();

	function carregarComboUnidades () {

		AjaxRequest.create(	'fis1_taxadiversos.RPC.php',
			{exec : 'getUnidades'},
			function (oRetorno) {

				$('unidade').length = 0;

				for (var i = 0; i < oRetorno.aUnidades.length; i++) {

					var oOption           = document.createElement('option');
					oOption.value     = i;
					oOption.innerHTML = oRetorno.aUnidades[i];

					$('unidade').appendChild(oOption);
				}
			}).setMessage('Buscando unidade...').execute();
	}

	function montarGrid(oCollectionTaxas, sIdGrid) {

		var oGrid = new DatagridCollection(oCollectionTaxas, sIdGrid);

		oGrid.configure({'height':'350', 'width':'900', 'update':true, 'delete':true});

		oGrid.addColumn('codigo')
			.setOption('width', '70px')
			.setOption('align', 'center')
			.setOption('label', 'ID');

		oGrid.addColumn('natureza')
			.setOption('width', '230px')
			.setOption('align', 'left')
			.setOption('label', 'Natureza')
			.transform(function (natureza) {

			  if(natureza.length > 40) {
          return natureza.substring(0, 40) + '...';
        }

				return natureza;
			});

		oGrid.addColumn('grupotaxadiversos')
			.setOption('width', '120px')
			.setOption('align', 'left')
			.setOption('label', 'Grupo')
			.transform(function (grupotaxadiversos, itemCollection) {

			  if(itemCollection.grupotaxadiversos_descricao.length > 12) {
          return itemCollection.grupotaxadiversos_descricao.substring(0, 12) + '...';
        }

        return itemCollection.grupotaxadiversos_descricao;
			});

		oGrid.addColumn('formula')
			.setOption('width', '120px')
			.setOption('align', 'left')
			.setOption('label', 'Fórmula')
			.transform(function (formula, itemCollection) {

			  if(itemCollection.formula_descricao.length > 12) {
          return itemCollection.formula_descricao.substring(0, 12) + '...';
        }

        return itemCollection.formula_descricao;
			});

		oGrid.addColumn('unidade')
			.setOption('width', '75px')
			.setOption('align', 'center')
			.setOption('label', 'Unidade')
			.transform(function (unidade, itemCollection) {
				return itemCollection.unidade_descricao;
			});

		oGrid.addColumn('tipo_periodo')
			.setOption('width', '75px')
			.setOption('align', 'center')
			.setOption('label', 'Período')
			.transform(function(tipoPeriodo, itemCollection){

				switch(tipoPeriodo) {
					case 'A':
						return 'Anual';
					case 'M':
						return 'Mensal';
					default:
						return 'Diária';
				}
			});

		oGrid.addColumn('tipo_calculo')
			.setOption('width', '75px')
			.setOption('align', 'center')
			.setOption('label', 'Cálculo')
			.transform(function(tipoCalculo, itemCollection){

				switch(tipoCalculo) {
					case 'U':  //Fechado
						return 'Único';
					case 'G':  //Aberto
						return 'Geral';
				}
			});

		oGrid.show($(sIdGrid));

		return oGrid;
	}

	function getTaxas () {

		var iGrupo = $F('grupotaxadiversos');

		oGridTaxas.collection.clear();
		oGridTaxas.reload();

		AjaxRequest.create('fis1_taxadiversos.RPC.php',
			{
				exec   : 'getTaxas',
				iGrupo : iGrupo
			},
			function(oRetorno) {

				if(!empty(oRetorno.sMessage)) {
					alert(oRetorno.sMessage);
				}

				if (oRetorno.lErro) {
					return false;
				}

				oRetorno.aTaxas.forEach(function(oTaxa) {
					oGridTaxas.collection.add(oTaxa);
				});

				oGridTaxas.reload();

        oGridTaxas.collection.get().forEach(function(oTaxa, iLinha) {

          if(oTaxa.natureza.length > 40) {
            oGridTaxas.grid.setHint(iLinha, 1, oTaxa.natureza);
          }

          if(oTaxa.grupotaxadiversos_descricao.length > 12) {
            oGridTaxas.grid.setHint(iLinha, 2, oTaxa.grupotaxadiversos_descricao);
          }

          if(oTaxa.formula_descricao.length > 12) {
            oGridTaxas.grid.setHint(iLinha, 3, oTaxa.formula_descricao);
          }
        });
			}).setMessage('Buscando taxas...').execute();
	}

	function salvar () {

		var codigo            = $F('codigo');
		var grupotaxadiversos = $F('grupotaxadiversos');
		var natureza          = $F('natureza');
		var formula           = $F('formula');
		var unidade           = $F('unidade');
		var tipo_periodo      = $F('tipo_periodo');
		var tipo_calculo      = $F('tipo_calculo');

		if(grupotaxadiversos == '' || grupotaxadiversos == 0) {
			alert('Informe o grupo da taxa.');
			return;
		}

		if(natureza == '' || natureza == 0) {
			alert('Informe a natureza.');
			return;
		}

		if(formula == '' || formula == 0) {
			alert('Informe a fórmula.');
			return;
		}

		if(tipo_periodo == '' || tipo_periodo == 0) {
			alert('Informe o tipo de período.');
			return;
		}
		
		if(tipo_calculo == '' || tipo_calculo == 0) {
			alert('Informe o tipo de cálculo.'); // tipo de período
			return;
		}

		if(unidade == '' || unidade == 0) {

			if(!confirm('Não há Unidade selecionada, deseja confirmar a inclusão/alteração?')) {
				return;
			}
		}

		var item = {
			exec 							: 'salvar',
			codigo            : codigo,
			grupotaxadiversos : grupotaxadiversos,
			natureza          : natureza,
			formula           : formula,
			unidade           : unidade,
			tipo_periodo      : tipo_periodo,
			tipo_calculo      : tipo_calculo
		}

		AjaxRequest.create(	'fis1_taxadiversos.RPC.php',
			item,
			function(oRetorno) {

				alert(oRetorno.sMessage);

				if (oRetorno.lErro) {
					return false;
				}

				$('btnNovo').disabled = false;
				getTaxas();
				limparFormulario();

			}).setMessage('Salvando taxa...').execute();
	}

	function excluir(item) {

		if (!confirm('Confirma a exclusão da taxa?')) {
			return false;
		}

		AjaxRequest.create(	'fis1_taxadiversos.RPC.php',
			{exec : 'excluir', codigo : $F('codigo')},
			function(oRetorno) {

				if(!empty(oRetorno.sMessage)) {
					alert(oRetorno.sMessage);
				}

				if (oRetorno.lErro) {

					location.reload();
					return false;
				}

				oGridTaxas.collection.remove($F('codigo'));
				oGridTaxas.reload();
				$('btnNovo').disabled = false;
				limparFormulario();
				oAncoraGrupoTaxa.habilitar();
				oAncoraFormula.habilitar();

			}).setMessage('Excluindo cadastro da taxa...').execute();

	}

	function limparFormulario () {

		$('codigo').value            = '';
		$('natureza').value          = '';
		$('formula').value           = '';
		$('formula_descricao').value = '';
		$('unidade').value           = '0';
		$('tipo_periodo').value      = '0';
		$('tipo_calculo').value      = '0';
	}

	$('btnNovo').observe('click', function() {
		location.reload();
	});

	$('grupotaxadiversos').observe('change', function() {

		if($F('grupotaxadiversos').trim() == '') {

			limparFormulario();
			oGridTaxas.clear();
			$('btnNovo').setAttribute('disabled', 'disabled');
		}
	});
</script>