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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotuloContrans = new rotulo("contrans");
$oRotuloContrans->label();
$oRotuloContransLan = new rotulo("contranslan");
$oRotuloContransLan->label();
$oRotuloConhist = new rotulo("conhistdoc");
$oRotuloConhist->label();

$dtd = date("d",db_getsession("DB_datausu"));
$dtm = date("m",db_getsession("DB_datausu"));
$dta = date("Y",db_getsession("DB_datausu"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
  db_app::load("estilos.css, grid.style.css");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("classes/infoLancamentoContabil.classe.js");
?>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>
  <center>
    <div style="margin-top: 25px; width: 500px;">
      <form action="" method="get" name='form1' >
        <fieldset>
          <legend><b>Consulta Geral da Contabilidade</b></legend>
          <table>

            <tr>
              <td nowrap><? db_ancora("Código do Lançamento:",'js_pesquisaLancamento();',1); ?> </td>
              <td><?  db_input("c70_codlan",10,"",true,'text',1);   ?> </td>
            </tr>

            <!-- Documento //conhistdoc -->
            <tr>
              <td nowrap="nowrap" id="tdDocumento">
                <b><? db_ancora($Lc45_coddoc, "js_pesquisaDocumento(true);", 1);?></b>
              </td>
              <td nowrap="nowrap" colspan="3">
                <?
                  db_input('c45_coddoc', 10, $Ic45_coddoc, true, 'text', 1, "onchange='js_pesquisaDocumento(false);'");
                  db_input('c53_descr', 40, $Ic53_descr, true, 'text',3);
                ?>
              </td>
            </tr>
            <tr>
						  <td nowrap="nowrap">
							  <b>Data Inicial:</b>
						  </td>
						  <td nowrap="nowrap">
							  <?
							 	  db_inputdata("dataInicio", "$dtd", "$dtm", "$dta", true, "text", 2, null, null, null, "parent.js_validaCamposLiberaBotao();");
							  ?>
						  </td>
						  <td nowrap="nowrap">
							  <b>Data Final:</b>
						  </td>
						  <td nowrap="nowrap">
							  <?
							 	  db_inputdata("dataFim", "$dtd", "$dtm", "$dta", true,"text", 2, null, null, null, "parent.js_validaCamposLiberaBotao();");
							  ?>
						  </td>
						</tr>
						<tr>
						  <td nowrap="nowrap">
							  <b>Valor:</b>
						  </td>
						  <td nowrap="nowrap">
							  <?
							 	  db_input('valorInicio', 10, "", true, 'text', 1, "onkeypress='return js_mask(event, \"0-9|.\");'");
							  ?>
						  </td>
						  <td nowrap="nowrap">
							  <b>Até:</b>
						  </td>
						  <td nowrap="nowrap">
							  <?
							    db_input('valorFim', 10, "", true, 'text', 1, "onkeypress='return js_mask(event, \"0-9|.\");'");
							  ?>
						  </td>
						</tr>
          </table>
        </fieldset><br />
        <input type="button" value="Consultar" id="btnConsultar" name="btnConsultar" onclick="js_consultar();" disabled="disabled"/>
      </form>
    </div>
  </center>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

</body>
</html>
<script type="text/javascript">

// Variavel do RPC
var sURL = 'con3_consultacontabilidade.RPC.php';
// Cria a instancia da grid de Lancamentos

/**
* Crio a grid de lancamentos
*/
var oGridLancamentos = new DBGrid('gridLancamentos');
oGridLancamentos.nameInstance = "oGridLancamentos";
oGridLancamentos.setCellWidth(new Array('15%', "15%", '50%', "20%"));
oGridLancamentos.setCellAlign(new Array("rigth", "center", "left", "right"));
oGridLancamentos.setHeader(new Array("Lançamento", "Data", "Documento","Valor"));
oGridLancamentos.hasTotalizador = true;
/*
 * lokup para lancamentos contabeis
 */
function js_pesquisaLancamento(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conlancamlan','func_conlancamlan.php?funcao_js=parent.js_preencheLancamento|c70_codlan','Pesquisa Lançamentos',true);
}
function js_preencheLancamento(chave){

  $('c70_codlan').value = chave;
  js_validaCamposLiberaBotao();
  db_iframe_conlancamlan.hide();
}

$('c70_codlan').observe('change', function(){

  js_validaCamposLiberaBotao();
});

/* Funções de pesquisa do Documento */
function js_pesquisaDocumento(lMostra) {

  var sUrlDocumento = "";
  if (lMostra) {
    sUrlDocumento = "func_conhistdoc.php?funcao_js=parent.js_preencheDocumento|c53_coddoc|c53_descr";
  } else {
    sUrlDocumento = "func_conhistdoc.php?pesquisa_chave="+$F("c45_coddoc")+"&funcao_js=parent.js_completaDocumento";
  }
  js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlDocumento, "Pesquisa Documento", lMostra);
}

function js_preencheDocumento(iCodigoDocumento, sDescricaoDocumento) {

  $("c45_coddoc").value = iCodigoDocumento;
  $("c53_descr").value = sDescricaoDocumento;
  js_validaCamposLiberaBotao();
  db_iframe_conhistdoc.hide();
}

function js_completaDocumento(sDescricao, lErro) {

  $("c53_descr").value = sDescricao;
  if (lErro) {
    $("c45_coddoc").value = "";
    js_validaCamposLiberaBotao();
  }
}
/**
 * Valida a consulta
 */
function js_consultar() {

  var iCodLancamento = $F('c70_codlan');

  if ( iCodLancamento == '' ) {

    if ($F('c45_coddoc') == '') {

      alert('Você tem que selecionar ao menos um documento.');
      return false;
    }

    var nValorInicial = new Number($F('valorInicio'));
    var nValorFinal   = new Number($F('valorFim'));
    if (nValorInicial > nValorFinal) {

      alert("Valor inicial superior ao valor final.");
      return false;
    }

    if ($F('dataInicio') == "" || $F('dataFim') == "") {

      alert("Informe uma data inicial e final.");
      return false;
    }

    if (js_comparadata($F('dataInicio'), $F('dataFim'), ">")) {

      alert("Data inicial superior a data final.");
      return false;
    }
  }

  retornaWindowLancamentos();
  js_buscaDadosLancamentos();
}

/**
 * Renderiza a Windown da consulta dos Lancamentos
 */
function retornaWindowLancamentos() {

  var sContainer  = "<html>";
      sContainer += "<body>";
      sContainer += "<center>";
      sContainer += "  <fieldset >";
      sContainer += "    <legend><b>Lançamentos</b></legend>";
      sContainer += "    <div id='dataGridLancamentos'>";
      sContainer += "    </div>";
      sContainer += "  </fieldset>";
      sContainer += "</center>";
      sContainer += "</body>";
      sContainer += "</html>";

  // Criamos a instancia da WindowAux
  oWindowAux = new windowAux("windowLancamentos", "Consulta de Lançamento", 700, 400);
  oWindowAux.setContent(sContainer);

  var sHelpMsgBoard = "Dê dois cliques sob a linha para visualizar o lançamento.";
  var oMessageBoard = new DBMessageBoard('msg_boardLancamento',
                                         "Consulta de Lançamento Contábil",
                                         sHelpMsgBoard,
                                         oWindowAux.getContentContainer()
                                        );
  oWindowAux.setShutDownFunction(function(){
    oWindowAux.destroy();
  });
  oWindowAux.show();
  oMessageBoard.show();

  oGridLancamentos.show($('dataGridLancamentos'));
  oGridLancamentos.clearAll(true);
}

/**
 * Funcao responsavel pela chamada AJAX para buscar os dados da grid
 */
function js_buscaDadosLancamentos() {

  var oObject            = new Object();
  oObject.exec           = "getDadosLancamentosFiltrados";
  oObject.iDocumento     = $F('c45_coddoc');
  oObject.dtInicio       = '';
  oObject.dtFim          = '';
  oObject.nValorInicio   = '';
  oObject.nValorFim      = '';
  oObject.iCodLancamento = $F('c70_codlan');

  if ($F('dataInicio') != "") {
    oObject.dtInicio = $F("dataInicio_ano") + "-" + $F("dataInicio_mes") + "-" + $F("dataInicio_dia");
  }
  if ($F('dataFim') != "") {
    oObject.dtFim = $F("dataFim_ano") + "-" + $F("dataFim_mes") + "-" + $F("dataFim_dia");
  }
  if ($F('valorInicio') != "") {
    oObject.nValorInicio = $F('valorInicio');
  }
  if ($F('valorFim') != "") {
    oObject.nValorFim = $F('valorFim');
  }

  js_divCarregando('Aguarde, buscando lançamentos...','msgBox');
  var objAjax   = new Ajax.Request (sURL,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoLancamentos
                                        }
                                   );

}

/**
 * Popula os dados na grid
 */
function js_retornoLancamentos(oJson) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oJson.responseText+")");
  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMensagem.urlDecode());
    return false;
  }

  if (oRetorno.aLancamentos.length == 0) {

    alert("Nenhum registro encontrado para o filtro selecionado.");
    oWindowAux.destroy();
    return false;
  }

  oGridLancamentos.clearAll(true);
  var nValorTotal = Number(0);
  oRetorno.aLancamentos.each( function(oDado, id) {

    nValorTotal = (nValorTotal + Number(oDado.nValor));
    console.log(nValorTotal);

    var aRow = new Array();
    aRow[0]  = oDado.iLancamento;
    aRow[1]  = oDado.dtData;
    aRow[2]  = oDado.sDocumento.urlDecode();
    aRow[3]  = js_formatar(oDado.nValor, 'f');
    oGridLancamentos.addRow(aRow);
    oGridLancamentos.aRows[id].sEvents = "ondblclick = js_abreViewLancamentos("+oDado.iLancamento+")";
  });

  oGridLancamentos.renderRows();
  $('TotalForCol3').innerHTML = js_formatar(nValorTotal, 'f');
}

$('c45_coddoc').observe("change", js_validaCamposLiberaBotao);
$('dataInicio').observe("change", js_validaCamposLiberaBotao);
$('dataFim').observe("change", js_validaCamposLiberaBotao);

function js_validaCamposLiberaBotao() {

  $('btnConsultar').disabled = true;
  if ( $F('c45_coddoc') != "" && $F('dataInicio') != "" && $F('dataFim') != "" || $F('c70_codlan') != "") {
    $('btnConsultar').disabled = false;
  }
}

function js_abreViewLancamentos(iLancamento) {
  var oViewLancamento = new infoLancamentoContabil(iLancamento);
}
</script>