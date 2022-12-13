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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_cfpatri_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="scripts/scripts.js"></script>
	<script type="text/javascript" src="scripts/strings.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC; >
<div class="container">
	<fieldset>
		<legend>Desprocessamento da Depreciação</legend>
		<table class="form-container">
			<tr>
				<td>Ano:</td>
				<td>
					<?php
					  db_input("iAnoSessao", 10, false, true, "text", 3);
					?>
				</td>
			</tr>
			<tr>
			  <td>
			    Tipo do Processamento:
			  </td>
			  <td>
			  <?
          $aTiposProcessamento = array(0 => "Selecione",
                                       1 => "Automático",
                                       2 => "Manual"
                                       );
          db_select("tipoprocessamento", $aTiposProcessamento, true, 1, "onchange='js_getProximoMesPorTipo()'");
			  ?>
			  </td>
			</tr>
			<tr>
				<td>Mês:</td>
				<td>
					<select id="iMes" name="iMes">
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="button" name="btnProcessa" id="btnProcessa" value="Desprocessar">
</div>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

	var sUrlRPC = "pat4_processamentodepreciacao.RPC.php";
  var aMeses = [];
  aMeses[0]  = "Janeiro";
  aMeses[1]  = "Fevereiro";
  aMeses[2]  = "Março";
  aMeses[3]  = "Abril";
  aMeses[4]  = "Maio";
  aMeses[5]  = "Junho";
  aMeses[6]  = "Julho";
  aMeses[7]  = "Agosto";
  aMeses[8]  = "Setembro";
  aMeses[9]  = "Outubro";
  aMeses[10] = "Novembro";
  aMeses[11] = "Dezembro";

  /**
   * Função utilizada para criar o combobox com os meses disponíveis.
   */
  function criarComboBoxMeses() {

    var oComboMes = $('iMes');
    oComboMes.options.length = 0;

    var oOptionSelecione = new Option("Selecione", 0);
    oComboMes.appendChild(oOptionSelecione);
    aMeses.each(
      function (sMes, iMes) {

        var iMesCorrente = iMes + 1;
        var oOption      = new Option(sMes, iMesCorrente);
        oOption.disabled = true;
        oOption.value    = iMesCorrente;
        oComboMes.appendChild(oOption);
      }
    );
  }
  criarComboBoxMeses();

	/*
	 * Função que será executada para buscar os meses que o usuário pode depreciar
	 */
	function js_getProximoMesPorTipo() {

    if ($F('tipoprocessamento') == "0") {
      criarComboBoxMeses();
      return;
    }

	  var oParam  							= {};
	  oParam.exec 							= "getMesesDepreciadosParaCancelamento";
	  oParam.iTipoProcessamento = $F('tipoprocessamento');

	  js_divCarregando(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.carregando_meses'), "msgBox");

		var oAjax = new Ajax.Request(sUrlRPC,
                                {method: 'post',
                                 asynchronous: false,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheMesesDepreciados
                                }
                               );
	}

	/**
	 * Preenche o combobox com os meses que o usuário pode depreciar.
	 */
	function js_preencheMesesDepreciados(Ajax) {

	  js_removeObj("msgBox");
	  var oRetorno = eval("("+Ajax.responseText+")");
	  /*
	   * Verifica se o mes disponível é 0. Caso seja as depreciações já foram realizadas para o ano.
	   */
	  if (oRetorno.iMesDisponivel == 0) {
			alert(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.depreciacao_ja_cancelada'));
	  }
	  if (oRetorno.message != "") {
			alert(oRetorno.message.urlDecode());
	  }

	  /**
	   * Percorre o array de meses bloqueando os meses que não poderam ser
	   * processados pois já estão processados.
	   */
	  sMesSelecionado      = "";
	  var aMeses           = $('iMes').options;
	  var iTotalMeses      = aMeses.length;
	  for (var iMes = 0; iMes < iTotalMeses; iMes++) {

      oOption = aMeses[iMes];
			if (oOption.value != oRetorno.iMesDisponivel && oOption.value != 0) {
				oOption.disabled = true;
			} else {

				oOption.selected = true;
				oOption.disabled = false;
			}
		}
	}

	/**
	 *  Valida os dados do formulário e o tipo de processamento que o usuário está acessando.
   *  Direciona o programa para a função JS responsável.
	 */
  $("btnProcessa").observe('click', function() {

    var iMes = $F("iMes");
    if ($F('tipoprocessamento') == 0) {

      alert(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.informe_tipo'));
      return false;
    }
		if (iMes == "0") {

			alert(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.informe_mes'));
			return false;
		}
	  iMes                   = new Number(iMes);
		var sTipoProcessamento = $('tipoprocessamento').options[$('tipoprocessamento').selectedIndex].innerHTML;
		var sMsgCancelamento   = 'Confirma o cancelamento do cálculo da depreciação do mês '+(iMes)+" - "+aMeses[iMes - 1];
		    sMsgCancelamento  += ' do tipo '+$F('tipoprocessamento')+" - "+sTipoProcessamento+"?";
    if (!confirm(sMsgCancelamento)) {
      return false;
    }

    js_divCarregando(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.cancelando_processamento'), "msgBox");
    var oParam                = new Object();
    oParam.exec               = "cancelarProcessamento";
    oParam.iMesDesprocessar   = $F('iMes');
    oParam.iTipoProcessamento = $F('tipoprocessamento');
    var oAjax = new Ajax.Request(sUrlRPC,
                                {method: 'post',
                                 asynchronous: false,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoCancelaProcessamento
                                }
                               );

  });


  function js_retornoCancelaProcessamento(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
    } else {

      alert(_M('patrimonial.patrimonio.pat4_desprocessadepreciacao001.cancelada_com_sucesso'));
      js_getProximoMesPorTipo();
    }
  }


</script>
<script>

$("iAnoSessao").addClassName("field-size2");

</script>