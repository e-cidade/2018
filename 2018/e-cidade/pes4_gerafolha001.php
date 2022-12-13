<?

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
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>

#tabela_principal  tr > td:first-child  {
  text-align: left;
  width     : 100px;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<form name="form1" method="post" class="container" id="filtroCalculoFolha" >
<input type="hidden" name="db_debug" value="false">
<fieldset>
<legend><b>Cálculo Financeiro</b></legend>

<table class="form-container" id="tabela_principal">
  <tr>
    <td>
      <b>Tipo de folha:</b>
    </td>
    <td>
      <select id="opcao_geral" name="opcao_geral"></select>
    </td>
  </tr>
<?
if(!isset($opcao_gml)){
  $opcao_gml = "m";
}
if(!isset($opcao_filtro)){
  $opcao_filtro = "s";
}

include(modification("dbforms/db_classesgenericas.php"));
$geraform = new cl_formulario_rel_pes;

$geraform->manomes = false;                     // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

$geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
$geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES

$geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATRÍCULA INICIAL
$geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATRÍCULA FINAL

$geraform->lo1nome = "r110_lotaci";             // NOME DO CAMPO DA LOTAÇÃO INICIAL
$geraform->lo2nome = "r110_lotacf";             // NOME DO CAMPO DA LOTAÇÃO FINAL

$geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
$geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

$geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADRÃO
$geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADRÃO

$geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
$geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS

$geraform->strngtipores = "gml";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
//                                       m - Matrícula,
//                                       r - Resumo
$geraform->onchpad      = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
$geraform->gera_form(null,null);
?>
  </table>
</fieldset>

<input type="hidden" name="hidTipoFolha" id="hidTipoFolha" value="<?php echo (isset($opcao_geral)) ? $opcao_geral : '1'; ?>" />
<input type="button" name="processar" value="Processar" onclick="return js_validarCalculo();">
<? if (db_getsession("DB_login") == "dbseller") {
echo "<input type=\"button\" value=\"Processar com Debug\" onclick=\"js_enviar_dados(2);\">";
}
?>
<input type="button" value ='Limpar' onclick="location.href='pes4_gerafolha001.php'" id='limpar' />

  </table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>

var iMatricula = '';

function js_enviar_dados(tp){


  if ($('IFdb_calculo')) {

    $('IFdb_calculo').parentNode.removeChild($('IFdb_calculo'));
    $('Jandb_calculo').parentNode.removeChild($('Jandb_calculo'));
  }

  if (document.form1.selregist) {

    valores = '';
    virgula = '';

    for(i = 0; i < document.form1.selregist.length; i++){

      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';

      if (i == 0) {
        iMatricula = 1;
      } else {
        iMatricula = 2;
      }
    }

    document.form1.faixa_regis.value  = valores;
    document.form1.selregist.selected = 0;
    if (valores==""){
      alert('Selecione uma matrícula para processar!!');
      return false;
    }
  } else if (document.form1.sellotac) {

    valores = '';
    virgula = '';

    for (i=0; i < document.form1.sellotac.length; i++) {
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }

    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;

    if (valores=="") {
      alert('Selecione uma lotação para processar!!');
      return false;
    }
  }

  if (document.form1.r110_regisi) {

    if (document.form1.r110_regisi.value=="" && document.form1.r110_regisf.value==""){
      alert('Informe uma matrícula para processar!!');
      return false;

    }
  } else if (document.form1.r110_lotaci){

    if (document.form1.r110_lotaci.value=="" && document.form1.r110_lotacf.value==""){
      alert('Informe uma lotação para processar!!');
      return false;
    }
  }

  if (tp == 2) {
    document.form1.db_debug.value = "true";
  }

  // if (document.form1.opcao_gml.value != 'g') {
    js_buscaMatriculas();
  // } else {
  // abreProcessamentoCalculo();
  // }


}

js_insSelectselregist = function(){

  var texto = document.form1.z01_nome.value;
  var valor = document.form1.rh01_regist.value;

  if (texto != "" && valor != "") {

    var F = document.getElementById("selregist");
    var valor_default_novo_option = F.length;
    var testa = false;

    for (var x = 0; x < F.length; x++) {

      if (F.options[x].value == valor) {

        testa = true;
        break;
      }
    }
    if (testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);
      for (i=0;i<F.length;i++) {
        F.options[i].selected = false;
      }

      js_trocacordeselect();
    }
  }

  texto = document.form1.z01_nome.value    = "";
  valor = document.form1.rh01_regist.value = "";
  document.form1.rh01_regist.focus();
}

/**
 * Valida as informções do cálculo antes de processar
 */
function js_validarCalculo() {

  var sTipoResumo = $$('#opcao_gml option').find(function(ele){return !!ele.selected;}).value;

  switch (sTipoResumo) {

    case "g":

      var iTipoFolha = $$('#opcao_geral option').find(function(ele){return !!ele.selected;}).value;
      js_verificarFolhaDBPref(iTipoFolha);
      break;
    default:
      js_enviar_dados(1);
      break;
  }
}

/**
 * Busca as matriculas dos servidores a partir dos filtros informados, e passa as mesmas
 * para o fonte pes4_gerafolha002
 * @return Void
 */
function js_buscaMatriculas(){

  var sUrlRPC = "pes4_rhgeracaofolha.RPC.php";


  /**
   * Trata os dados para quando for escolhido o filtro
   * por matricula para pode ser utilizado o serialize
   */
  if ($('selregist')) {

    $('selregist').name = 'selregist';

    for (var iMatriculas = 0; iMatriculas < $('selregist').length; iMatriculas++) {
      $('selregist').options[iMatriculas].selected = true;
    }
  }

  /**
   * Trata os dados para quando for escolhido o filtro
   * por lotação para pode ser utilizado o serialize
   */
  if ($('sellotac')) {

    $('sellotac').name = 'sellotac';

    for (var iMatriculas = 0; iMatriculas < $('sellotac').length; iMatriculas++) {
      $('sellotac').options[iMatriculas].selected = true;
    }
  }

  // var oParam              = new Object();
  // oParam.exec             = 'buscaMatriculas';
  // oParam.oDadosFormulario = $('filtroCalculoFolha').serialize(true);
  var oDadosFormulario = $('filtroCalculoFolha').serialize(true);
  var oAjaxRequest = new AjaxRequest('pes4_rhgeracaofolha.RPC.php', {exec: 'buscaMatriculas', oDadosFormulario: oDadosFormulario}, retornoMatriculas);
      oAjaxRequest.setMessage('Buscando matriculas...');
      oAjaxRequest.execute();
}

function retornoMatriculas (oRetorno) {

  if ( $F('opcao_geral') == 3 && !js_validaComparativoFerias(oRetorno.aServidores)) {
    return false;
  }

  document.form1.faixa_regis.value  = oRetorno.aServidores.join();
  document.form1.opcao_gml.value    = 'm';

  if (!document.form1.opcao_filtro) {

    var oOpcaoFiltro      = document.createElement('input');
    oOpcaoFiltro.type = 'hidden';
    oOpcaoFiltro.name = 'opcao_filtro';
    document.form1.appendChild(oOpcaoFiltro);
  } 

  document.form1.opcao_filtro.value = 's';

  if ($('selregist')) {
    for (var iMatriculas = 0; iMatriculas < $('selregist').length; iMatriculas++) {
      $('selregist').options[iMatriculas].selected = false;
    }
  }

  abreProcessamentoCalculo();
}

/**
 * Realiza a validação para o comparativo d férias, verificando se existe
 * o cálculo de salário quando estiver sendo cálculado o ponto de férias
 * @param  {Array} aMatriculas
 * @return {boolean}
 */
function js_validaComparativoFerias(aMatriculas) {

  var sUrlRPC = "pes4_rhgeracaofolha.RPC.php";

  var oParam            = new Object();
  oParam.exec       = 'validaComparativoFerias';
  oParam.aMatriculas = aMatriculas;

  var lRetorno = null;

  var oAjax = new Ajax.Request(sUrlRPC, {
  method      : 'post',
    parameters  : 'json='+Object.toJSON(oParam),
      asynchronous: false,
      onComplete: function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");

        if (oRetorno.status == 2) {

          alert(oRetorno.message);
          lRetorno =  false;
        } else {
          lRetorno =   true;
        }
      }
  });

  return lRetorno;
}

/**
 * Verifica os filtros informados como parâmetros.
 * @return Array
 */
function js_montafiltros(){

  var oRetorno = {}

    /**
     * Tratamento do filtro de matricula, monta o objeto retorno de acordo com os
     * filtros selecionados: 'Intervalo' ou 'Selecionados'
     */
    if ($F('opcao_gml') == 'm') {

      oRetorno.sTipoResumo = 'm';

      switch ($F('opcao_filtro')) {

      case 'i':

        oRetorno.sTipoFiltro       = 'i';
        oRetorno.iMatriculaInicial = $F('r110_regisi');
        oRetorno.iMatriculaFinal   = $F('r110_regisf');
        break;
      case 's':

        var aMatriculas = new Array();

        for (var iMatriculas = 0; iMatriculas <= $('selregist').length; iMatriculas++) {

          aMatriculas[iMatriculas] = $('selregist').options[iMatriculas].value;
        }

        oRetorno.sTipoFiltro = 's';
        oRetorno.aMatriculas = aMatriculas;
        break;
default:
  oRetorno.sTipoFiltro = 'g';
  break;
    }
  }

  /**
   * Tratamento do filtro de lotação, monta o objeto retorno de acordo com os
   * filtros selecionados: 'Intervalo' ou 'Selecionados'
   */
  if ( $F('opcao_gml') == 'l'){

    oRetorno.sTipoResumo = 'l';

    switch ($F(opcao_filtro)) {

    case 'i':

      break;

    case 's':

      break;
    default:
      oRetorno.sTipoFiltro = 'g';
      break;
    }
  }

  return false;
}

/**
 * Verifica se as folhas de pagamento de rescisão, adiantamento ou 13º salário estão liberadas no DBPref.
 * OBS.: Não ocorrerá a mensagem de confirmação se a variável DB_COMPLEMENTAR não estiver setada.
 *
 * @param {Integer} iTipoFolha
 */
function js_verificarFolhaDBPref(iTipoFolha) {

  var sUrl             = 'pes4_rhgeracaofolha.RPC.php';

  var oParam           = new Object();
  oParam.exec      = 'verificarFolhaPagamentoDBPref';
  oParam.tipoFolha = iTipoFolha;

  var oAjax = new Ajax.Request(sUrl, {
  method    : 'post',
    parameters: 'json='+Object.toJSON(oParam),
    onComplete: function(oAjax) {

      var oRetorno  = eval("("+oAjax.responseText.urlDecode()+")");
      var sMensagem = oRetorno.message;

      if (oRetorno.status) {

        if(!empty(sMensagem) ) {

          if (!confirm(sMensagem)) {
            return false;
          }
        }

        js_enviar_dados(1);

      } else {

        alert(sMensagem);
        return false;
      }
    }
  });
}

/**
 * Carrega as opções do combobox "Tipo de folha"
 */
function js_carregarFolhasPagamentos() {

  var sUrl        = 'pes4_rhgeracaofolha.RPC.php';

  var oParam      = new Object();
  oParam.exec = 'retornarFolhasAbertas';

  var oAjax = new Ajax.Request(sUrl, {
  method    : 'post',
    parameters: 'json='+Object.toJSON(oParam),
    onComplete: function(oAjax) {

      var oRetorno              = eval("("+oAjax.responseText.urlDecode()+")");
      var aTipoFolha            = oRetorno.aTipoFolha;
      var aFolhasDBpref         = oRetorno.aFolhasDBPref;
      var iTipoFolhaSelecionada = $('hidTipoFolha').value;
      for(var i in aTipoFolha) {

        switch(i) {

        case "1":
          $('opcao_geral').insert(new Element('option', {value: 1}).update(aTipoFolha[i]));
          break;

        case "2":

          var oOption           = document.createElement('option');
          oOption.value     = 2;
          oOption.innerHTML = aTipoFolha[i];

          if (aFolhasDBpref.in_array(2)) {

            oOption.disabled   = true;
            oOption.innerHTML += " - Folha liberada e-cidade online";
            }

            $('opcao_geral').appendChild(oOption);
            break;

        case "3":
          $('opcao_geral').insert(new Element('option', {value: 3}).update(aTipoFolha[i]));
          break;

        case "4":

          var oOption           = document.createElement('option');
          oOption.value     = 4;
          oOption.innerHTML = aTipoFolha[i];

          if (aFolhasDBpref.in_array(4)) {

            oOption.disabled   = true;
            oOption.innerHTML += " - Folha liberada e-cidade online";
                    }

                    $('opcao_geral').appendChild(oOption);
                    break;

        case "5":

          var oOption           = document.createElement('option');
          oOption.value     = 5;
          oOption.innerHTML = aTipoFolha[i];

          if (aFolhasDBpref.in_array(5)) {

            oOption.disabled   = true;
            oOption.innerHTML += " - Folha liberada e-cidade online";
                    }

                    $('opcao_geral').appendChild(oOption);
                    break;

        case "6":
          $('opcao_geral').insert(new Element('option', {value: 8}).update(aTipoFolha[i]));
          break;

        case "7":
          $('opcao_geral').insert(new Element('option', {value: 10}).update(aTipoFolha[i]));
          break;

        case "8":
          $('opcao_geral').insert(new Element('option', {value: 1}).update(aTipoFolha[i]));
          break;
        }
      }

      /**
       * Mantém selecionado a opção do combobox tipo da folha
       */
      var aOptions = $$('select#opcao_geral option');
      for(var i = 0; i < aOptions.length; i++) {

        if(aOptions[i].value == iTipoFolhaSelecionada) {
          aOptions[i].selected = true;
          break;
        }
      }

    }
  });
}

function abreProcessamentoCalculo() {

  document.form1.action             = 'pes4_gerafolha002.php';



  var oJanela = js_OpenJanelaIframe( "",
    "db_calculo",
    "",
    "Cálculo Financeiro",
    true,
    50,
    (document.width  - (document.width))/2,
    document.width,
    document.height);

  oJanela.setAltura("calc(100% - 10px)");
  oJanela.setLargura("calc(100% - 10px)");
  oJanela.hide = function () {

    if ( $('Jandb_calculo') ) {
      $('Jandb_calculo').remove();
    }
    delete(window.db_calculo);

    document.form1.action = '';
    document.form1.target = '';
  }

  document.form1.target = 'IFdb_calculo';
  document.form1.submit();

}

js_carregarFolhasPagamentos();
js_trocacordeselect();

(function(){

  if( !$('selregist') ) {
    return false;
  }

  var oBotao          = document.createElement("input");
  oBotao.type         = "button";
  oBotao.value        = "Consulta Financeira";
  oBotao.style.display = "none";


  $('limpar').parentNode.appendChild(oBotao);

  var fChangeMatricula = $('rh01_regist').onchange;
  $('rh01_regist').onchange = function() {

    oBotao.style.display = "none";
    $('selregist').selectedIndex = -1;
    fChangeMatricula.bind(this)();
    return;
  }

  $('selregist').observe('dblclick', function(){

    oBotao.style.display = "none";
    $('selregist').selectedIndex = -1;
  });

  $('selregist').observe('change', function(){

    oBotao.style.display = "none";

    if ( this.getValue().length != 1 ) {
      return false;
   }
   var iMatricula = this.getValue()[0];

   oBotao.style.display = "";
   oBotao.onclick = function(){

     var oIframe             = document.createElement("iframe");
     oIframe.src             = "pes3_gerfinanc001.php?lReadOnly=true&iMatricula=" + iMatricula;
     oIframe.style.width     = "calc(100% - 10px)";
     oIframe.style.height    = "calc(100% - 10px)";
     oIframe.style.overflowY  = "yes";

     var oWindowAux       = new windowAux("consultaFinanceira", "Consulta Financeira");
     oWindowAux.setContent(oIframe);
     oWindowAux.show();
     oWindowAux.getElement().style.width  = "calc(100% - 20px)";
     oWindowAux.getElement().style.height = "calc(100% - 30px)";
   }
  });
})();

</script>
</html>
