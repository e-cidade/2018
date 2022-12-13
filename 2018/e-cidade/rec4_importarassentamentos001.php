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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($_POST);

$db_opcao   = 1;
$cltipoasse = new cl_tipoasse;
$cltipoasse->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h12_assent");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(
    "scripts.js, 
    strings.js, 
    prototype.js, 
    AjaxRequest.js, 
    datagrid.widget.js, 
    estilos.css, 
    widgets/windowAux.widget.js, 
    widgets/DBHint.widget.js"
  );
  ?>
  <style type="text/css">
    fieldset#pesquisa-assentamentos {
      max-width: 220px;
      margin: 0 auto 15px auto;
    }
  </style>
</head>
<body style="background-color: #ccc; margin-top: 30px">

<form id="form1" name="form1" action="" method="POST" onsubmit="return js_validacampos()" class="container">
  <fieldset id="pesquisa-assentamentos">
    <legend>Buscar Assentamentos</legend>
    <table class="container-form">

      <tr>
        <td nowrap title="Data de Início">
          <label id="lbl_datainicio" for="datainicio"><b>Data de Início: </b></label>
        </td>
        <td>
          <?php
          db_inputdata('datainicio', @$datainicio_dia = "", @$datainicio_mes = "", @$datainicio_ano = "", true, 'text', $db_opcao, "", "", "style='background-color:#E6E4F1'");
          ?>
        </td>
      </tr>

      <tr style="display: none">
        <td nowrap title="<?php echo $Th12_assent; ?>">
          <label id="h12_assent" for="h12_assent"><?php echo $Lh12_assent; ?></label>
        </td>
        <td>
          <?php
          db_input('iTipoAssentamento', 10, $Ih12_codigo, true, "text", 3);
          ?>
        </td>
      </tr>

    </table>
  </fieldset>
  <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_carregarAssentamentosEfetividade(event)" />
  <input type="button" id="voltar" name="voltar" value="Voltar" onclick="location.href='rec4_importarassentamentos001.php'" />
  <div id="container" class="container">
    <fieldset>
      <legend>Assentamentos de Efetividade</legend>
      <div id="grid_assentamentos_efetividade"></div>
    </fieldset>
  </div>
  <input type="button" id="Processar" name="Processar" value="Processar" onclick="js_processarAssentamentos()" />
</form>

<script type="text/javascript">

  <?php if(!isset($iTipoAssentamento) || empty($iTipoAssentamento)) : ?>

  var oJanela = js_OpenJanelaIframe(
    "",
    "db_func_iframe_tipoasse",
    "func_tipoasse.php?funcao_js=parent.redirecionar|h12_codigo",
    "Importar Assentamentos",
    true
  );
  oJanela.setModal(true);

  <?php endif; ?>

  var MENSAGEM = 'recursoshumanos.rh.rec4_importarassentamentos001.';

  function redirecionar( iTipoAssentamento) {
    location.href = 'rec4_importarassentamentos001.php?iTipoAssentamento='+iTipoAssentamento;
  }


  (function(oWindow){

    require_once("scripts/classes/pessoal/assentamentovidafuncional/DBViewManutencaoAssentamentoFuncional.js");

    oWindow.oView                                      = null;
    oWindow.oGridAssentamentosEfetividade              = new DBGrid("assentamentosEfetividade");
    oWindow.oGridAssentamentosEfetividade.nameInstance = "window.oGridAssentamentosEfetividade";

    oWindow.oGridAssentamentosEfetividade.setCheckbox(0);
    oWindow.oGridAssentamentosEfetividade.setHeader([   "Código",   "Matrícula", "Servidor",  "Tipo",   "Data Início", "Data Término", "Opção"]);
    oWindow.oGridAssentamentosEfetividade.setCellWidth(["50px",     "80px",      "350px",     "50px",   "70px",        "90px",         "90px"]);
    oWindow.oGridAssentamentosEfetividade.setCellAlign(["center",   "center",    "left",      "center", "center",      "center",       "center"]);
    oWindow.oGridAssentamentosEfetividade.setHeight("450");
    oWindow.oGridAssentamentosEfetividade.show( $('grid_assentamentos_efetividade') );

  })(window);

  function js_carregarGridAssentamentosEfetividade (aAssentamentosEfetividade) {

    window.oGridAssentamentosEfetividade.clearAll(true);

    aAssentamentosEfetividade.forEach( function(oAssentamento, iLinha) {

      var sOpcao        = "<input type=\"button\" id=\"lancar"+ oAssentamento.iCodigo +"\"";
      sOpcao           += "name=\"lancar\"";
      sOpcao           += "value=\"Lançar\"";
      sOpcao           += "onclick=\"js_lancarAssentamentos(" + oAssentamento.iCodigo + ", " + oAssentamento.iMatricula + ")\"/>";

      var aDadosAssentamento = [
        oAssentamento.iCodigo,
        oAssentamento.iMatricula,
        oAssentamento.sNome,
        oAssentamento.sTipo,
        oAssentamento.sDataInicio,
        oAssentamento.sDataFim,
        sOpcao
      ];

      window.oGridAssentamentosEfetividade.addRow(aDadosAssentamento);
    });

    window.oGridAssentamentosEfetividade.renderRows();

    aAssentamentosEfetividade.forEach( function(oAssentamento, iLinha) {

      /**
       * Criado DBHint para o historico do assentamento
       */
      var oHintHistorico  = window["oDBHint_"+iLinha+"_1"] =  new DBHint("oDBHint_"+iLinha+"_1");
      var sDescricao      = "<strong>Histórico: </strong>" + oAssentamento.sHistorico;
      oHintHistorico.setWidth(300);
      oHintHistorico.setText(sDescricao);
      oHintHistorico.setShowEvents(["onmouseover"]);
      oHintHistorico.setHideEvents(["onmouseout"]);
      oHintHistorico.setScrollElement($('body-container-assentamentosEfetividade'));
      oHintHistorico.setPosition('B', 'L');
      oHintHistorico.make($(window.oGridAssentamentosEfetividade.aRows[iLinha].aCells[1].sId));

      /**
       * Criado DBHint para o nome do tipo de assentamento.
       */
      var oHintTipoAssentamento = window["oDBHint2_"+iLinha+"_1"] =  new DBHint("oDBHint2_"+iLinha+"_1");
      sDescricao            = "<strong>Assentamento: </strong>" + oAssentamento.sDescricaoTipo;
      oHintTipoAssentamento.setText(sDescricao);
      oHintTipoAssentamento.setShowEvents(["onmouseover"]);
      oHintTipoAssentamento.setHideEvents(["onmouseout"]);
      oHintTipoAssentamento.setScrollElement($('body-container-assentamentosEfetividade'));
      oHintTipoAssentamento.setPosition('B', 'L');
      oHintTipoAssentamento.make($(window.oGridAssentamentosEfetividade.aRows[iLinha].aCells[4].sId));
    });

    document.form1.Processar.disabled = false;
  }

  function js_carregarAssentamentosEfetividade (e) {

    var sMessagemConfirmacao = _M(MENSAGEM +"confirmacao_data_vazia");
    var sDataInicio          = null;

    if(document.form1.iTipoAssentamento.value.trim() == '') {
      alert(_M(MENSAGEM +"tipo_nao_informado"));
      return;
    }

    if(document.form1.datainicio.value != '') {
      sDataInicio            = $F('datainicio');
    }

    if(sDataInicio == null) {
      if(e != null && e.type.toLowerCase() == 'click' && !confirm(sMessagemConfirmacao)) {
        return;
      }
    }

    var oParametros = {
      'exec'              : 'getAssentamentosEfetividade',
      'iTipoAssentamento' : $F('iTipoAssentamento'),
      'sDataInicio'       : sDataInicio
    };

    var oAjaxRequest = new AjaxRequest(
      'rec4_assentamentosefetividade.RPC.php',
      oParametros,
      function (oAjax, lErro) {

        if(lErro) {
          alert(oAjax.message.urlDecode());
        } else {
          if(oAjax.aAssentamentosEfetividade.length == 0) {
            alert("Nenhum registro encontrado.\n\nVerifique as lotações configuradas para o usuário.");
          }
          js_carregarGridAssentamentosEfetividade(oAjax.aAssentamentosEfetividade);
        }
      }
    );

    oAjaxRequest.setMessage('Buscando Assentamentos de efetividade...');
    oAjaxRequest.execute();
  }

  function js_lancarAssentamentos (iCodigoAssentamentoEfetividade, iMatricula) {

    window.oView = new DBViewManutencaoAssentamentoFuncional();
    window.oView.setCodigoAssentamentoEfetividade(iCodigoAssentamentoEfetividade);
    window.oView.setMatricula(iMatricula);
    window.oView.show();
  }

  function retornaAssentamentosFuncionais(iCodigoAssentamento) {
    window.oView.loadAssentamentosFuncionais();
  }

  function js_processarAssentamentos () {

    var aListaAssentamentos = window.oGridAssentamentosEfetividade.getSelection();
    var aAssentamentos      = Array();

    aListaAssentamentos.each(function(aItemAssentamento, iIndice) {
      aAssentamentos.push(aItemAssentamento[0]);
    });

    var oParametros = {
      'exec'           : 'clonarAssentamentoParaVidaFuncional',
      'aAssentamentos' : aAssentamentos
    }

    var oAjaxRequest = new AjaxRequest(
      'rec4_assentamentosefetividade.RPC.php',
      oParametros,
      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode());

        if(!lErro) {
          js_carregarAssentamentosEfetividade(null);
        }
      }
    );

    document.form1.Processar.disabled = true;

    oAjaxRequest.setMessage('Processando assentamentos selecionados...');
    oAjaxRequest.execute();
  }

</script>
<?php db_menu() ?>
</body>
</html>