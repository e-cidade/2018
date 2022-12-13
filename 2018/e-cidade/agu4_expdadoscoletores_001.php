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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
require(modification("libs/db_app.utils.php"));

$comboRotas = new cl_arquivo_auxiliar();

$rotulo = new rotulocampo();
$rotulo->label("x21_exerc");
$rotulo->label("x21_mes");
$rotulo->label("x46_sequencial");
$rotulo->label("x46_descricao");

$rotulo->label("x49_anousu");
$rotulo->label("x49_mesusu");

$rotulo->label("x21_numcgm");
$rotulo->label("z01_nome");

$x49_anousu = db_getsession("DB_anousu");
$x49_mesusu = date ("m");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, AjaxRequest.js, DBDownload.widget.js');
db_app::load('estilos.css, grid.style.css');
?>

<script>
function js_pesquisa(mostra){
	if(mostra == true) {
	 js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguacoletor','func_aguacoletor.php?funcao_js=parent.js_preenchepesquisa|x46_sequencial|x46_descricao','Pesquisa',true);
	}else {
		js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguacoletor','func_aguacoletor.php?pesquisa_chave='+document.form1.x46_sequencial.value+'&funcao_js=parent.js_preenchepesquisa','Pesquisa',false);
	}
}
function js_preenchepesquisa(chave1,chave2){
  db_iframe_aguacoletor.hide();
  document.form1.x46_sequencial.value = chave1;
  document.form1.x46_descricao.value = chave2;
}

function js_pesquisax21_numcgm(mostra){
  if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeiturista','func_agualeituristaalt.php?funcao_js=parent.js_mostraagualeiturista1|x16_numcgm|z01_nome','Pesquisa',true);
    }else{
      if(document.form1.x21_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeiturista','func_agualeituristaalt.php?pesquisa_chave='+document.form1.x21_numcgm.value+'&funcao_js=parent.js_mostraagualeiturista','Pesquisa',false);
      }else{
        document.form1.x16_numcgm.value = '';
      }
    }
  }
  function js_mostraagualeiturista(chave,erro){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.x21_numcgm.focus();
      document.form1.x21_numcgm.value = '';
    }
  }
  function js_mostraagualeiturista1(chave1,chave2){
    document.form1.x21_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_agualeiturista.hide();
  }

function js_processar(){

  var virgula = "";
  var lista   = "";
	var F       = document.form1;

  if((F.x49_mesusu.value == '') || (F.x49_mesusu.value > 12) || (F.x49_mesusu.value <= 0)) {
	  alert('Número do mês está vazio ou é invalido.');
	  return false;
  }

  if(F.x49_anousu.value == '') {
	  alert('O filtro Ano deve ser informado. Ex.: 2010');
	  return false;
  }

  if(F.x46_sequencial.value == '') {
	  alert('Nenhum coletor foi informado.');
	  return false;
  }

  if(F.x21_numcgm.value == '') {
    alert('Nenhum leiturista informado.');
    return false;
  }

  if((F.geraDadosArquivos.checked == false) || (F.geraSituacaoLeitura.checked == false) || (F.geraLeiturista.checked == false) || (F.geraConfiguracoes.checked == false)) {
	  alert('Nenhum arquivo foi selecionado.');
	  return false;
  }

  for(x = 0; x < document.form1.rota.length; x++) {
    lista += virgula+document.form1.rota.options[x].value;
    virgula=",";
    document.form1.listaRotas.value  = lista;
  }

	if(lista == '') {
		alert('Nenhuma rota informada.');
		return false;
	}

	if ($('layout').value == 'tarifa') {

    var oParameters = {
      'exec': 'processarExportacao',
      'iAno': $('x49_anousu').value,
      'iMes': $('x49_mesusu').value,
      'iCodigoColetor': $('x46_sequencial').value,
      'iCodigoLeiturista': $('x21_numcgm').value,
      'sRotas': $('listaRotas').value,
      'sRuas': $('listaRotaRuas').value
    };

    new AjaxRequest('agua_exportacao.RPC.php', oParameters, function (oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());
      if (lErro) {
        return false;
      }

      var oDownload = new DBDownload();
      for (oArquivo of oRetorno.aArquivos) {
        oDownload.addFile(oArquivo.link.urlDecode(), oArquivo.nome.urlDecode());
      }
      oDownload.show();

      oDataGrid.clearAll(true);
      oDataGridSelecao.clearAll(true);
      $('form1').reset();
      $('rota').childElements().each(function (oElement) {
        $(oElement).remove();
      });
    })
      .setMessage('Processando Exportação... Este processo pode levar alguns minutos.')
      .execute();

    return false;
  }

	with(document.form1) {
		method = 'POST';
		action = 'agu4_expdadoscoletores_002.php';
		submit();
	}
}


</script>
<style type="text/css">
.ruaBloqueada {
  background-color: #fb4d4d;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" id="form1" method="POST" action="">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br />

<table align="center" width="1100">
  <tr>
    <td width="49%" valign="top">
    <fieldset style="height: 240px"><legend><b>Filtros:</b></legend>

    <table width="90%" align="center">
      <tr>
        <td nowrap title="<?=@$Tx49_anousu?>" align="right">
          <label for="x49_anousu" class="bold">Ano Exportação:</label>
        </td>

        <td>
        <?php
        $vAno = array ($x49_anousu => $x49_anousu, ($x49_anousu + 1) => ($x49_anousu + 1) );
        db_select ( "x49_anousu", $vAno, true, 1, "style=\"width: 90px\";" );
        ?>
      </td>

        <td nowrap title="<?=@$Tx49_mesusu?>" align="right">
          <label for="x49_mesusu" class="bold">Mês Exportação:</label>
        </td>

        <td>
        <?php
        $result = array ("1" => "Janeiro", "2" => "Fevereiro", "3" => "Março", "4" => "Abril", "5" => "Maio", "6" => "Junho", "7" => "Julho", "8" => "Agosto", "9" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro" );
        db_select ( "x49_mesusu", $result, true, 1, "style=\"width: 90px\"" );

        ?>
      </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tx46_sequencial?>" align="right">
        <?php
        db_ancora ( @$Lx46_sequencial, "js_pesquisa(true);", 1 );
        ?>
      </td>
        <td colspan="3">
        <?php
        db_input ( 'x46_sequencial', 10, $Ix46_sequencial, true, 'text', 1, "onchange=\"js_pesquisa(false);\"" )?>

        <?php
        db_input ( 'x46_descricao', 30, $Ix46_descricao, true, 'text', 3, "" )?>
      </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tx21_numcgm?>" align="right">
        <?php
          db_ancora ( @$Lx21_numcgm, "js_pesquisax21_numcgm(true);", 1 );
        ?>
        </td>
        <td nowrap colspan="5">
        <?php
          db_input ( 'x21_numcgm', 10, $Ix21_numcgm, true, 'text', 1, "onchange='js_pesquisax21_numcgm(false);'", "" );
        ?>
        <?php
          db_input ( 'z01_nome', 30, $Iz01_nome, true, 'text', 3, "", "" );
        ?>
        </td>
      </tr>

    </table>
    <table width="100%">
    <?php
      $comboRotas->cabecalho      = "<strong>Rotas</strong>";
      $comboRotas->codigo         = "x06_codrota"; //chave de retorno da func
      $comboRotas->descr          = "x06_descr"; //chave de retorno
      $comboRotas->nomeobjeto     = 'rota';
      $comboRotas->funcao_js      = "js_mostra";
      $comboRotas->funcao_js_hide = "js_mostra1";
      $comboRotas->func_arquivo   = "func_aguarota.php"; //func a executar
      $comboRotas->nomeiframe     = "db_iframe_aguarota";
      $comboRotas->onclick        = "mostraRuas();";
      $comboRotas->db_opcao       = 2;
      $comboRotas->tipo           = 2;
      $comboRotas->top            = 0;
      $comboRotas->linhas         = 3;
      $comboRotas->vwidth         = 350;
      $comboRotas->funcao_gera_formulario ();
    ?>
    </table>
    <table width="100%">
      <tr>
        <td align="center">
          <a href="agu4_expdadoscoletores_001.php" onclick="if(!confirm('Todos os valores selecionados serão apagados, deseja continuar?')) return false; ">Nova Pesquisa</a>
        </td>
      </tr>
    </table>
    </fieldset>
    </td>

    <td width="2%">&nbsp;</td>

    <td width="49%" valign="top">
	    <fieldset style="height: 240px"><legend><b>Arquivos:</b></legend><br />
		    <br />
		    <input type="checkbox" name="geraDadosArquivos" value="t" checked="checked" /> Arquivo 01 - Rotas e Leituras.<br />
		    <br />
		    <input type="checkbox" name="geraSituacaoLeitura" value="t" checked="checked" /> Arquivo 02 - Situa&ccedil;&otilde;es de Leitura.<br />
		    <br />
		    <input type="checkbox" name="geraLeiturista" value="t" checked="checked" /> Arquivo 03 - Leituristas.<br />
		    <br />
		    <input type="checkbox" name="geraConfiguracoes" value="t" checked="checked" /> Arquivo 04 - Configurações.<br />
		    <br />
	    </fieldset>
    </td>
  </tr>


  <tr>
    <td>
	    <fieldset style="height: 230px"><legend><b>Selecionar Logradouros:</b></legend>
	    <div id="grid"></div>
	    <br />
	    <span class="ruaBloqueada">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;<strong>Logradouros Pendentes. (Aguardando a Importação ou Cancelamento)</strong></fieldset>
    </td>

    <td>
      <input type="button" value=">" onclick="addRuas();" title="Selecionar Logradouro para Exportação">
    </td>

    <td>
      <fieldset style="height: 230px"><legend><b>Logradouros Selecionados:</b></legend>
      <div id="gridSelecao"></div>
      <br />
      <strong>Total de matriculas selecionadas:</strong> <span id="totalMatriculas">0</span></fieldset>
    </td>
  </tr>

  <tr>
    <td align="center" colspan="3"><br />
    <input type="hidden" name="listaRotas" id="listaRotas">
    <input type="hidden" name="listaRotaRuas" id="listaRotaRuas">
    <input type="hidden" name="x21_exerc" id="x21_exerc">
    <input type="hidden" name="x21_mes" id="x21_mes">
    <label for="layout" class="bold">Layout:</label>
    <select name="layout" id="layout">
      <option value="tarifa">Tarifa</option>
      <option value="taxa">Taxa</option>
    </select>
    <input name="processar" id="processar" type="button" value="Processar" onclick="js_processar();"></td>
  </tr>
</table>
<?php
  db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</form>

<script>

function mostraRuas() {

	var oParam = new Object();
	oParam.exec = 'getRuas';
	oParam.codRota = $F('x06_codrota');

	document.getElementById("x21_exerc").value     = $F("x49_anousu");
	document.getElementById("x49_anousu").disabled = true;
	document.getElementById("x21_mes").value       = $F("x49_mesusu");
    document.getElementById("x49_mesusu").disabled = true;

	js_divCarregando('Aguarde, pesquisando ruas.', 'msgbox');
	var oAjax = new Ajax.Request(
															 'agua_rota_rua.RPC.php',
															 {
																method: 'POST',
																parameters: 'json='+Object.toJSON(oParam),
																onComplete: js_retorno_pesquisa_ruas
															 });
}

function js_retorno_pesquisa_ruas(oAjax) {

	var oRetorno = eval("("+oAjax.responseText+")");

	if (oRetorno.status == 1) {

		if (oRetorno.aRuas.length > 0) {

			oDataGrid.clearAll(true);
			for (var i = 0; i < oRetorno.aRuas.length; i++) {
				with(oRetorno.aRuas[i]) {
          var aLinha = new Array();

          oParam            = new Object();
          oParam.exec       = 'vericaRotaRuaSituacao';
          oParam.anousu     = $F('x21_exerc');
          oParam.mesusu     = $F('x21_mes');
          oParam.rota       = x07_codrota;
          oParam.logradouro = x07_codrua;

          obj = new Ajax.Request(
                  'agua_exportacao.RPC.php',
                  {
                   method: 'POST',
                   asynchronous: false,
                   parameters: 'json='+Object.toJSON(oParam),
                   onSuccess:
                   function(objAjax) {
                      var oRetornoAjax = eval("("+objAjax.responseText+")");

                      exporta       = oRetornoAjax.count;
                      iQtdeLeituras = oRetornoAjax.iQteLeiturasLog;

                   }
                  });


          if(exporta > 0) {
        	  disabledCheck = "disabled=\"disabled\"";
        	  check = "";
          }else {
        	  disabledCheck = "";
        	  check         = "checked=\"checked\"";
          }

          aLinha[0] =  "<input type='checkbox' style='margin:0;' name='checkBoxRua' "+disabledCheck+" "+check+" value='"+x07_codrotarua+'_'+x07_codrota+'_'+x07_codrua+'_'+j14_nome+'_'+x07_nroini+'_'+x07_nrofim+'_'+iQtdeLeituras+'_'+x99_quantidade+"' >";
          aLinha[1] = x07_codrota;
          aLinha[2] = x07_codrua;
          aLinha[3] = j14_nome;
          aLinha[4] = x07_nroini;
          aLinha[5] = x07_nrofim;
          aLinha[6] = iQtdeLeituras + '/' + x99_quantidade;
					oDataGrid.addRow(aLinha);

					if(exporta > 0) {
						oDataGrid.aRows[i].setClassName('ruaBloqueada');
					}

				}
			}
    	oDataGrid.renderRows();
		}
	}
	js_removeObj('msgbox');
}


function js_init_table() {

  oDataGrid = new DBGrid('gridRuas');
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'left', 'center', 'center', 'center'));
  oDataGrid.setCellWidth(new Array("5%","10%","15%",'35%', '12%', '13%', '10%'));
  oDataGrid.setHeader(new Array('<input type="checkbox" style="margin:0;" name="seleciona" checked="checked" onclick="marca()" title="Inverter Selecionados">', 'Rota', 'Código', 'Logradouro', 'Nro Inicial', 'Nro Final', 'Qtd'));
  oDataGrid.setHeight(150);
  oDataGrid.show($('grid'));

}

function js_init_table_selecao() {

  oDataGridSelecao = new DBGrid('gridSelecao');
  oDataGridSelecao.nameInstance = 'oDataGrid';
  oDataGridSelecao.setCellAlign(new Array('center', 'center', 'left', 'center', 'center', 'center', 'center','center'));
  oDataGridSelecao.setCellWidth(new Array("10%","15%","35%",'12%', '13%','10%', '5%', '0%'));
  oDataGridSelecao.setHeader(new Array('Rota', 'Código', 'Logradouro', 'Nro Inicial', 'Nro Final', 'Qtd', 'E', 'x'));
  oDataGridSelecao.setHeight(150);
  oDataGridSelecao.show($('gridSelecao'));

  oDataGridSelecao.showColumn(false, 7);

}

function marca() {
	var checkbox = document.getElementsByName('checkBoxRua');

	for (var i = 0; i < checkbox.length; i++) {
    if(checkbox[i].disabled == false) {

  		if (checkbox[i].checked == true) {

  			checkbox[i].checked = false;

  		} else {

  			checkbox[i].checked = true;

  		}
    }
	}
}

function addRuas(remove) {

  var totalLinhas  = oDataGridSelecao.getNumRows();
  var gridSelecao  = Array();
  var checkBoxRuas = document.getElementsByName('checkBoxRua');
  var totalMatriculas = 0;

	var selecionar        = Array();
	var selecionados      = Array();
	var todosSelecionados = Array();
	var ruas              = 0;
	var selecao           = 0;
	var botaoProcessar    = document.form1.processar;
	var listaRotaRuas     = document.form1.listaRotaRuas;
	var lista             = "";
	var virgula           = "";
	var exporta;
	var ruasNExportadas   = "";

	if(remove != null) {
    if(!confirm('Deseja excluir o logradouro da lista?')) {
  	  remove = null;
  	}
	}

	if(totalLinhas > 0) {
		var checkBoxSelecionados = document.getElementsByName('checkBoxSelecao');

		for(var i = 0; i < checkBoxSelecionados.length; i++) {

			if(checkBoxSelecionados[i].checked == true) {
				selecionados[selecao] = checkBoxSelecionados[i].value;
				selecao++;
			}

		}
	}

	js_divCarregando('Aguarde, processando ruas selecionadas.', 'msgbox');
	for(var z = 0; z < checkBoxRuas.length; z++) {

		if(checkBoxRuas[z].checked == true) {
			parametros      = checkBoxRuas[z].value.split("_");

			oParam            = new Object();
			oParam.exec       = 'vericaRotaRuaSituacao';
			oParam.anousu     = $F('x21_exerc');
			oParam.mesusu     = $F('x21_mes');
			oParam.rota       = parametros[1];
			oParam.logradouro = parametros[2];


			obj = new Ajax.Request(
                    'agua_exportacao.RPC.php',
                    {
                     method: 'POST',
                     asynchronous: false,
                     parameters: 'json='+Object.toJSON(oParam),
                     onSuccess:
                     function(objAjax) {
                    	  var oRetornoAjax = eval("("+objAjax.responseText+")");

                    	  exporta = oRetornoAjax.count;

                     }
                    });

			if(exporta == 0) {
		    selecionar[ruas] = checkBoxRuas[z].value;
		    ruas++;
		    checkBoxRuas[z].checked = false;
			} else {
				ruasNExportadas += virgula+parametros[3];
				virgula  = ", "+"\n";
			}
		}
	}
	js_removeObj('msgbox');

	if(ruasNExportadas != '') {
    alert("O(s) seguinte(s) logradouro(s) já foram exportados para coletor, e não seram processados nessa exportação:\n"+ruasNExportadas+" .");
	}

	oDataGrid.clearAll(true);

	todosSelecionados = removeDuplicated(selecionados.concat(selecionar), false);

	todosSelecionados = todosSelecionados.sort();

	if(todosSelecionados.length > 0) {

  	oDataGridSelecao.clearAll(true);

  	for (var x = 0; x < todosSelecionados.length; x++) {

  		var conteudoLinha = todosSelecionados[x].split("_");

  		if(remove != null) {
  			remover = remove.split("_");
  			if((remover[1] == conteudoLinha[1]) && (remover[2] == conteudoLinha[2])) {
  				continue;
  			}
  		}

  		lista           += virgula+conteudoLinha[0];
	    virgula          = ",";
	    listaRotaRuas.value  = lista;

  		gridSelecao[0] = conteudoLinha[1];
  		gridSelecao[1] = conteudoLinha[2];
  		gridSelecao[2] = "&nbsp;"+conteudoLinha[3];
  		gridSelecao[3] = "&nbsp;"+conteudoLinha[4];
  		gridSelecao[4] = "&nbsp;"+conteudoLinha[5];
      gridSelecao[5] = parseInt(conteudoLinha[7]) - parseInt(conteudoLinha[6]);
  		gridSelecao[6] = "<a href=\"#divGridSelecao\" onclick=\"addRuas('"+todosSelecionados[x]+"');\" title=\"Excluir Rua da Lista.\">E</a>";
  		gridSelecao[7] = "<input type='checkbox' name='checkBoxSelecao' checked='checked' value='"+todosSelecionados[x]+"' >";
      oDataGridSelecao.addRow(gridSelecao);

      totalMatriculas += parseInt(conteudoLinha[6]);

  	}

    document.getElementById("totalMatriculas").innerHTML = totalMatriculas;
  	oDataGridSelecao.renderRows();

	}

	if (oDataGridSelecao.getNumRows() == 0) {
		botaoProcessar.disabled = true;
	}else {
		botaoProcessar.disabled = false;
	}

}

//+ Carlos R. L. Rodrigues
//@ http://jsfromhell.com/array/remove-duplicated [rev. #2]

removeDuplicated = function(a, s){
  var p, i, j;
  if(s) for(i = a.length; i > 1;){
      if(a[--i] === a[i - 1]){
          for(p = i - 1; p-- && a[i] === a[p];);
          i -= a.splice(p + 1, i - p - 1).length;
      }
  }
  else for(i = a.length; i;){
      for(p = --i; p > 0;)
          if(a[i] === a[--p]){
              for(j = p; --p && a[i] === a[p];);
              i -= a.splice(p + 1, j - p).length;
          }
  }
  return a;
};


document.form1.processar.disabled = true;
js_init_table();
js_init_table_selecao();

</script>
</body>
</html>
