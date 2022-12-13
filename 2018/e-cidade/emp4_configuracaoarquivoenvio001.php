<?
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

set_time_limit(0);
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));


require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_termo_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("libs/db_app.utils.php"));


$iInstit = db_getsession("DB_instit");
$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("k17_slip");

?>


<html>
<head>
  <style type="">

    .valor {
      width: 100px;
    }
    #iTipoTransmissao{
      width: 100px;
    }

    .configurada {
      background-color: #d1f07c;
    }
    .pagfor {
      background-color: #76C7FC;
    }
    .ComMov {
      background-color: rgb(222, 184, 135);
    }
    .naOPAuxiliar {
      background-color: #ffff99;
    }
    .configuradamarcado {
      background-color: #EFEFEF;
    }
    .ComMovmarcado {
      background-color: #EFEFEF;
    }
    .naOPAuxiliarmarcado {
      background-color: #EFEFEF;
    }
    .normalmarcado{ background-color:#EFEFEF}
  </style>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("DBLancador.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("widgets/DBAncora.widget.js");
  db_app::load("dbtextFieldData.widget.js");
  db_app::load("DBCodigoBarra.widget.js");
  ?>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

<center>
  <form name="form1" method="post">

    <fieldset style="margin-top: 50px; width: 600px;">
      <legend><strong>Filtros de Pesquisa</strong></legend>
      <table border="0" align='left' >


        <tr>
          <td colspan="1">
            <strong><label for="iFomaconsulta">Forma de Consulta:</label></strong>
          </td>
          <td nowrap>
            <?
            $aformaConsulta = array("0" => "Empenho" , "1" => "Slip");
            db_select('iFomaconsulta', $aformaConsulta, true, 1, "onChange = 'js_formaConsulta();'");
            ?>
          </td>
        </tr>



        <tr>
          <td>
            <strong><label for="datainicial">Data Inicial:</label></strong>
          </td>

          <td nowrap>
            <?
            db_inputdata("datainicial",null,null,null,true,"text", 1);
            ?>
          </td>

          <td>
            <strong><label for="datafinal">Data Final:</label></strong>
          </td>

          <td nowrap align="">
            <?
            db_inputdata("datafinal",null,null,null,true,"text", 1);
            ?>
          </td>

        </tr>

        <tr>
          <td nowrap title="<?=@$Te82_codord?>">
            <label for="e82_codord">
              <?db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",1);  ?>
            </label>
          </td>
          <td nowrap>
            <?
            db_input('e82_codord',10,$Ie82_codord,true,'text',1," onchange='js_pesquisae82_codord(false);'");
            ?>
          </td>
          <td>
            <label for="e82_codord2">
              <?
              db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",1);
              ?>
            </label>
          </td>
          <td nowrap align="left">
            <?
            db_input('e82_codord2',10,$Ie82_codord,true,'text',1,
                     "onchange='js_pesquisae82_codord02(false);'","e82_codord02");
            ?>
          </td>
        </tr>


        <tr id='ctnSlip' style="display:none;">
          <td nowrap title="<?=@$Tk17_slip?>">
            <label for="k17_slip">
              <? db_ancora("<b>Slip</b>","js_pesquisak17_slip(true);",1);  ?>
            </label>
          </td>
          <td nowrap>
            <? db_input('k17_slip',10,$Ie82_codord,true,'text',1, "onchange='js_pesquisak17_slip(false);'")?>
          </td>
          <td>
            <label for="k17_slip02">
              <? db_ancora("<b>até:</b>","js_pesquisak17_slip02(true);",1);  ?>
            </label>
          </td>
          <td nowrap align="left">
            <? db_input('k17_slip02',10,$Ie82_codord,true,'text',1,
                        "onchange='js_pesquisak17_slip02(false);'")?>
          </td>
        </tr>


        <tr id='ctnEmpenho' style='display:none;'>
          <td  nowrap title="<?=$Te60_numemp?>" colspan="1">
            <label for="e60_codemp">
              <?
              db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1);
              ?>
            </label>
          </td>
          <td nowrap>
            <input name="e60_codemp" id='e60_codemp' title='<?=$Te60_codemp?>' size="10" type='text'  />
          </td>
        </tr>


        <tr>
          <td nowrap title="<?=@$Tz01_numcgm?>">
            <label for="z01_numcgm">
              <?
              db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",1);
              ?>
            </label>
          </td>
          <td  colspan='4' nowrap>
            <?
            db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>


        <tr nowrap>
          <td nowrap title="<?=@$To15_codigo?>">
            <label for="o15_codigo">
              <? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",1); ?>
            </label>
          </td>
          <td colspan=3 nowrap>
            <?
            db_input('o15_codigo',10,$Io15_codigo,true,'text',1," onchange='js_pesquisac62_codrec(false);'");
            db_input('o15_descr',40,$Io15_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

      </table>
    </fieldset>
    <div style="margin-top: 10px;">
      <input type="button" id="pesquisar"  value="Pesquisar" onclick="js_pesquisar();">
      <input type="button" id="btnGerarArquivoTXT"  value="Emitir Arquivo Texto" />
    </div>

    <fieldset style="margin-top: 10px; width: 900px">
      <legend>
        <strong>
          Movimentos Encontrados
        </strong>
      </legend>
      <table border="0">
        <tr>
          <td>
            <div id='ctnGridConfiguracao'></div>
          </td>
        </tr>
      </table>
      <div style="margin-top: 10px; width: 100%;">
        <fieldset style="border-left: none; border-right: none; border-bottom: none;" >
          <legend><strong>Legenda</strong></legend>
          <label for="pagfor" style='padding:1px;border: 1px solid black; background-color:#76C7FC; float:left; '>
            <strong>Atualizados Bradesco - PagFor</strong>
          </label>
          <label for="configuradas" style='margin-left: 10px; padding:1px;border: 1px solid black; background-color:#d1f07c; float:left; '>
            <strong>Atualizados OBN</strong>
          </label>
          <label for="normais" style='margin-left: 10px; padding:1px;border: 1px solid black;background-color:white; float:left; '>
            <strong>Atualizados CNAB240</strong>
          </label>
        </fieldset>
      </div>
    </fieldset>


  </form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

  var oGet = js_urlToObject();
  var movimentosOrigem = oGet.movimentos !== undefined ? oGet.movimentos : '';



  const PAGAMENTO_CNAB   = "1";
  const PAGAMENTO_OBN    = "2";
  const PAGAMENTO_PAGFOR = "3";

  var sUrlRPC 						 = "emp4_configuracaoarquivoenvio.RPC.php";
  var sArquivoMensagens    = "financeiro.caixa.emp4_configuracaoarquivoenvio001";

  var iCodigoRecursoFundeb = null;
  var oDBCodigoBarra 			 = null;

  function js_criaCodigoBarra(){

    oDBCodigoBarra = new DBCodigoBarra("txtCodigoBarra", "oDBCodigoBarra");
    oDBCodigoBarra.setLabelCodigoBarra("Código de Barras:");
    oDBCodigoBarra.setMensagemLeitura('Aguardando leitura.');
    oDBCodigoBarra.criaComponentes();
    oDBCodigoBarra.setCallBackInicioLeitura(function() {
      oTxtValor.setValue('');
      oTxtData.setValue('');
      $('iTipoFatura').value = 1;
    });

    oDBCodigoBarra.setCallBackAposLeitura(function (oDados) {

      if (oDados == false) {

        $('iTipoFatura').value = '';
        $('txtValor').value    = '';
        return false;
      }

      oTxtValor.setValue(oDados.valor);
      if (oDados.data_pagamento != '') {

        var aData = oDados.data_pagamento.split('-');
        oTxtData.setData(aData[2], aData[1], aData[0]);
      }

      $('iTipoFatura').value = oDados.tipo;
      $('txtValor').focus();
      if (oDados.preencher_linha) {
        $('txtLinhaDigitavel').value = oDados.linha;
      }
    });

    oDBCodigoBarra.show('codigodebarras', 'linhadigitavel');
  }

  $('btnGerarArquivoTXT').observe('click', function() {
    window.location = 'emp4_empageconfgera001.php';
  });

  //================== setamos os tipos de fatura inicial ==================

  function js_tipoFatura(iTipoFatura){

    switch (iTipoFatura) {

      case "1" :

        $("iTipoFatura").options.length = 0;
        $("iTipoFatura").options[0]     = new Option("Fatura"  , "1");
        $("iTipoFatura").options[1]     = new Option("Convênio", "2");

        break;

      case "2" :

        $("iTipoFatura").options.length = 0;
        $("iTipoFatura").options[0]     = new Option("Convênio", "2");
        $("iTipoFatura").options[1]     = new Option("Fatura"  , "1");

        break;

      default :

        $("iTipoFatura").options.length = 0;
        $("iTipoFatura").options[0]     = new Option("Fatura"  , "1");
        $("iTipoFatura").options[1]     = new Option("Convênio", "2");
        break;


    }

  }


  //================== Retorna Tipo de transmissao para o movimento selecionado ============//

  function js_getTipoTransmissao(iMovimento, iCodigoRecurso) {

    var msgDiv      = "Buscando Dados <br>Aguarde ...";
    var oParametros = new Object();

    js_divCarregando(_M(sArquivoMensagens + ".buscando_tipo_transmissao"),'msgBox');

    oParametros.exec       = "getTipoTransmissao";
    oParametros.iMovimento = iMovimento;
    oParametros.iCodigoRecurso = iCodigoRecurso;

    new Ajax.Request(sUrlRPC,
      {method: "post",
        parameters:'json='+Object.toJSON(oParametros),
        onComplete: js_retornoTipoTransmissao
      });
  }
  function js_retornoTipoTransmissao(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == '2') {
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }

    $("iTipoTransmissao").options.length = 0;

    oRetorno.aDados.each(function (oDado, iInd) {

      if (iInd == 0) {
        js_exibeCamposObn(oDado.e57_sequencial);
      }

      var oOption = new Option(oDado.e57_descricao.urlDecode(), oDado.e57_sequencial);
      if (oDado.selecionado) {

        oOption.selected = true;
        if (oDado.e57_sequencial != PAGAMENTO_CNAB) {
          js_exibeCamposObn(oDado.e57_sequencial);
        }
      }
      $("iTipoTransmissao").appendChild(oOption);

    });

  }

  //=========== define stilos para a forma de consulta...empenho, slip

  function js_formaConsulta() {

    var iFomaconsulta = $F('iFomaconsulta');

    switch (iFomaconsulta) {

      case '0' :

        $('ctnEmpenho').style.display = "table-row";
        $('ctnSlip')   .style.display = "none";
        break;

      case '1' :

        $('ctnEmpenho').style.display = "none";
        $('ctnSlip')   .style.display = "table-row";
        break;
    }
  }
  js_formaConsulta();

  //================== Pesquisar Registros ============//

  function js_pesquisar(){

    var sRpcPesquisa   = "emp4_manutencaoPagamentoRPC.php";

    var dtInicial      = $F("datainicial");
    var dtFinal        = $F("datafinal");
    var iOrdemInicial  = $F('e82_codord');
    var iOrdemFinal    = $F('e82_codord02');
    var iSlipInicial   = $F('k17_slip');
    var iSlipFinal     = $F('k17_slip02');
    var iEmpenho       = $F('e60_codemp');
    var iCredor        = $F('z01_numcgm');
    var iRecurso       = $F('o15_codigo');
    var iFormaConsulta = $F('iFomaconsulta');

    // var msgDiv               = "Pesquisando Registros <br> Aguarde ...";

    var oParam               = new Object();
    oParam.lObn          = true;
    oParam.dtDataIni     = dtInicial;
    oParam.dtDataFim     = dtFinal;
    oParam.iNumCgm       = iCredor;
    oParam.iRecurso      = iRecurso;
    switch (iFormaConsulta) {

      case '0' :  // Empenho

        var sExec            = 'getMovimentos';
        oParam.iOrdemIni     = iOrdemInicial;
        oParam.iOrdemFim     = iOrdemFinal;
        oParam.iCodEmp       = iEmpenho;
        oParam.iOPauxiliar   = '';
        oParam.iAutorizadas  = '';
        oParam.iOPManutencao = '';
        oParam.orderBy       = '';
        oParam.lVinculadas   = false;
        break;

      case '1' :  // Slip

        var sExec        = 'getMovimentosSlip';
        oParam.iOrdemIni     = iSlipInicial;
        oParam.iOrdemFim     = iSlipFinal;
        break;
    }

    oParam.movimentosSelecionados = movimentosOrigem;

    //js_divCarregando(msgDiv,'msgBox');

    js_divCarregando(_M(sArquivoMensagens + '.pesquisando_registro') , 'msgBox');

    oParam.lTratarMovimentosConfigurados = true;
    var sParam  = js_objectToJson(oParam);
    url         = 'emp4_manutencaoPagamentoRPC.php';
    var sJson   = '{"exec":"'+sExec+'","params":['+sParam+']}';
    var oAjax   = new Ajax.Request(
      url,
      {
        method    : 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoPesquisa
      }
    );
  }

  var aValoresMovimentos = [];

  function js_retornoPesquisa(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridConfiguracao.clearAll(true);
    var iFormaPesquisa = $F('iFomaconsulta');

    switch (iFormaPesquisa) {

      case "0" : // Empenho

        if (oRetorno.aNotasLiquidacao.length > 0) {

          oRetorno.aNotasLiquidacao.each(function (oDado, iInd) {

            var aRow   = new Array();
            /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte0] */
            /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte0] */
            aRow[0] = oDado.e81_codmov; // movimento
            aRow[1] = oDado.e60_codemp + "/" + oDado.e60_anousu; // empenho
            aRow[2] = oDado.o15_codigo ; // recurso

            aValoresMovimentos[oDado.e81_codmov] = oDado.e81_valor;

            aRow[3] = oDado.conta_pagadora.codigo + " - " + oDado.conta_pagadora.descricao ; // conta pagadora
            aRow[4] = oDado.z01_numcgm  + " - " + oDado.z01_nome.urlDecode(); // credor
            aRow[5] = js_formatar(oDado.e81_valor, "f"); // valor
            /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */
            aRow[6] = "<input type='button' value='Editar' onclick='js_criaJanelaDetalhes("+oDado.e81_codmov+", "+oDado.o15_codigo+");'  ";
            /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */
            oGridConfiguracao.addRow(aRow);
            if (oDado.e25_empagetipotransmissao == PAGAMENTO_OBN) {
              oGridConfiguracao.aRows[iInd].setClassName('configurada');
            }
            if (oDado.e25_empagetipotransmissao == PAGAMENTO_PAGFOR) {
              oGridConfiguracao.aRows[iInd].setClassName('pagfor');
            }
          });
          oGridConfiguracao.renderRows();
        }

        break;


      case "1" : // Slip

        if (oRetorno.aSlips.length > 0) {

          oRetorno.aSlips.each(function (oDado, iInd) {

            var aRow   = new Array();

            aRow[0] = oDado.e81_codmov;
            aRow[1] = oDado.k17_codigo ;
            aRow[2] = oDado.c61_codigo ;
            aRow[3] = oDado.k17_credito + " - " + oDado.e83_descr.urlDecode() ;
            aRow[4] = oDado.z01_numcgm  + " - " + oDado.z01_nome.urlDecode();
            aRow[5] = js_formatar(oDado.k17_valor, "f");
            /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */
            aRow[6] = "<input type='button' value='Editar' onclick='js_criaJanelaDetalhes("+oDado.e81_codmov+", "+oDado.c61_codigo+");'  ";
            /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */
            oGridConfiguracao.addRow(aRow);
            if (oDado.e25_empagetipotransmissao == PAGAMENTO_OBN) {
              oGridConfiguracao.aRows[iInd].setClassName('configurada');
            }
            if (oDado.e25_empagetipotransmissao == PAGAMENTO_PAGFOR) {
              oGridConfiguracao.aRows[iInd].setClassName('pagfor');
            }
          });

          oGridConfiguracao.renderRows();

        }

        break;

    }
  }


  //================== Retorna Detalhes configurados para o movimento selecionado ============//

  function js_getDetalhes(iMovimento) {

    // var msgDiv      = "Buscando Registros <br> Aguarde ...";
    var oParametros = new Object();
    $('TotalForCol2').innerHTML = '0,00';

    js_divCarregando(_M("financeiro.caixa.emp4_configuracaoarquivoenvio001.buscando_detalhes"),'msgBox');


    oParametros.exec       = "getDetalhes";
    oParametros.iMovimento = iMovimento;
    new Ajax.Request(sUrlRPC,
      {method: "post",
        parameters:'json='+Object.toJSON(oParametros),
        onComplete: js_retornoGetDetalhes
      });
  }

  function js_retornoGetDetalhes(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    oGridConfiguracaoDetalhe.clearAll(true);
    var nValorTotal = 0;
    if (oRetorno.aDados.length > 0) {
      oRetorno.aDados.each(function (oDado, iInd) {

        var aRow    = [];
        aRow[0] = oDado.e74_codigodebarra ;
        aRow[1] = js_formatar(oDado.e74_valornominal, "f")  ;
        aRow[2] = js_formatar(oDado.e74_valorjuros, "f")    ;
        aRow[3] = js_formatar(oDado.e74_valordesconto, "f") ;
        aRow[4] = js_formatar(oDado.e74_datavencimento,'d');
        aRow[5] = oDado.sFatura.urlDecode();
        aRow[6] = oDado.e74_linhadigitavel;
        aRow[7] = oDado.e74_finalidade;
        oGridConfiguracaoDetalhe.addRow(aRow);
        nValorTotal += new Number(oDado.e74_valornominal).valueOf();
        document.getElementById('finalidade').value = oDado.e74_finalidade;

      });
      oGridConfiguracaoDetalhe.renderRows();
      $('TotalForCol2').innerHTML = js_formatar(nValorTotal, 'f');

      document.getElementById('finalidade').disabled = true;
    }


  }

  //================== Persiste os Detalhes no Banco ============//

  function js_salvarDetalhes(iMovimento){

    var nTotalMovimentos = Number(aValoresMovimentos[iMovimento]);
    var nTotalLancamentos = 0;

    var iTipoTransmissao             = $F('iTipoTransmissao');
    //var msgDiv                       = "Salvando Registros <br> Aguarde ...";
    var oParametros                  = new Object();
    oParametros.exec             = 'salvarDetalhes';
    oParametros.origem           = $F('iFomaconsulta');
    oParametros.iMovimento       = iMovimento;
    oParametros.iTipoTransmissao = iTipoTransmissao;
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */
    oParametros.aDetalhes        = new Array();

    if (iTipoTransmissao == "") {

      alert('Selecione um tipo de Transmissão.');
      return false;
    }

    if (iTipoTransmissao == "1") {
      oGridConfiguracaoDetalhe.clearAll(true);
    }

    oGridConfiguracaoDetalhe.aRows.each(function (oRow, iIndice) {

      var oDetalhes             = new Object();
      oDetalhes.iCodigoBarras   = oRow.aCells[1].getValue().trim() === "" ? "" : oRow.aCells[1].getValue();
      oDetalhes.nValor          = oRow.aCells[2].getValue();
      oDetalhes.nJuros          = oRow.aCells[3].getValue();
      oDetalhes.nDesconto       = oRow.aCells[4].getValue();
      oDetalhes.dtData          = oRow.aCells[5].getValue().trim() === "" ? "" : js_formatar(oRow.aCells[5].getValue(), 'd');
      oDetalhes.iFatura         = oRow.aCells[6].getValue();
      oDetalhes.iLinhaDigitavel = oRow.aCells[7].getValue().trim() === "" ? "" : oRow.aCells[1].getValue();
      oDetalhes.codigoFinalidade = oRow.aCells[8].getValue();
      oParametros.aDetalhes.push(oDetalhes);
      nTotalLancamentos += Number(js_strToFloat(oRow.aCells[2].getValue()));
    });

    /**
     * Movimento do tipo OBN
     *  - valida valor total lancado, deve ser igual ao do movimento
     */
    if (iTipoTransmissao == 2 && nTotalLancamentos > 0 && nTotalLancamentos != nTotalMovimentos) {
      return alert('Valor total dos lançamentos deve ser igual ao do movimento: ' + js_formatar(nTotalMovimentos, 'f'));
    }

    //js_divCarregando(msgDiv,'msgBox');
    js_divCarregando(_M(sArquivoMensagens + ".salvando_detalhes"), 'msgBox');

    new Ajax.Request(sUrlRPC,
      {method: "post",
        parameters:'json='+Object.toJSON(oParametros),
        onComplete: js_retornoSalvarDetalhes
      });
  }

  function js_retornoSalvarDetalhes(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    js_getDetalhes(oRetorno.iMovimento);

    alert(oRetorno.sMessage.urlDecode());
  }


  //================== Adicionar linha de detalhe NA GRID - NAO SALVA NADA NO BANCO AQUI ============//

  function js_incluirDetalhe(iMovimento){

    var aRow         = [];
    var oDetalhes    = {};

    var iCodigoBarra    = $F('txtCodigoBarra');
    var iLinhaDigitavel = $F('txtLinhaDigitavel').replace(/[^0-9]/g, '');
    var nValor          = $F('txtValor');
    var nJuros          = $F('txtJuros');
    var nDesconto       = $F('txtDesconto');
    var dtData          = $F('txtData');
    var iFatura         = $F('iTipoFatura');
    var sFatura         = "Fatura";


    if (iFatura == '2') {
      sFatura = "Convênio";
    }

    if ( $F('finalidade') === '000' || document.getElementById('finalidade').disabled) {


      if (iCodigoBarra == '') {

        alert('Obrigatório preenchimento do código de barras.');
        //$('txtCodigoBarra').focus();
        return false;
      }

      if (iCodigoBarra.length > 44) {

        alert('O código de barras deve ser no padrão de 44 posições');
        return false;
      }

      if (nValor == '') {

        alert('Obrigatório preenchimento do valor.');
        return false;
      }

      if (nJuros == '') {
        nJuros = "0";
      }

      if (nDesconto == '') {
        nDesconto = "0";
      }

      if (dtData == '') {

        alert('Obrigatório preenchimento da data.');
        return false;
      }
    }

    var lCodigoBarraDuplicado = false;
    oGridConfiguracaoDetalhe.aRows.each(function(oRows) {

      if (iCodigoBarra == oRows.aCells[1].getValue()) {

        lCodigoBarraDuplicado = true;
        return false;
      }
    });

    if (lCodigoBarraDuplicado) {

      alert ('O código de barras "'+iCodigoBarra+'" já foi lançado.');
      return false;
    }

    aRow[0] = iCodigoBarra;
    aRow[1] = js_formatar(nValor, "f");
    aRow[2] = js_formatar(nJuros, "f");
    aRow[3] = js_formatar(nDesconto, "f");
    aRow[4] = dtData;
    aRow[5] = sFatura;
    aRow[6] = iLinhaDigitavel;
    aRow[7] = $F('finalidade');

    oGridConfiguracaoDetalhe.addRow(aRow);
    oGridConfiguracaoDetalhe.renderRows();
    $('TotalForCol2').innerHTML = js_formatar(js_strToFloat($('TotalForCol2').innerHTML) + Number(nValor), 'f');

    $('txtCodigoBarra').value    = '';
    $('txtValor').value          = '';
    $('txtJuros').value          = '';
    $('txtDesconto').value       = '';
    $('txtData').value           = '';
    $('txtLinhaDigitavel').value = '';
    $('txtCodigoBarra').focus();

    document.getElementById('finalidade').disabled = true;
  }

  //================== Excluir Detalhes ============//
  function js_removerDetalhes(iMovimento){

    var aListaCheckbox         = oGridConfiguracaoDetalhe.getSelection('object');

    if (aListaCheckbox.length == 0) {
      alert('Selecione um registro para excluir.');
      return false;
    }

    var nValorParaSubtrair = 0;
    var aLinhasRemover = [];
    aListaCheckbox.each(
      function ( oRow, iSeq ) {
        nValorParaSubtrair += js_strToFloat(oRow.aCells[2].getValue());
        aLinhasRemover.push(oRow.getRowNumber());
      }
    );

    var sMensagemExclusao = 'Excluir selecionados?';
    sMensagemExclusao += '\nPara confirmar operação, é necessário clicar no botão "Salvar".';

    if (!confirm(sMensagemExclusao)) {
      return false;
    }
    oGridConfiguracaoDetalhe.removeRow(aLinhasRemover);
    oGridConfiguracaoDetalhe.renderizar();
    $('TotalForCol2').innerHTML = js_formatar(oGridConfiguracaoDetalhe.sum(2, false), 'f');

    if (oGridConfiguracaoDetalhe.aRows.length == 0) {
      document.getElementById('finalidade').disabled = false;
    }
  }

  function js_retornoExcluirDetalhes(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    js_getDetalhes(oRetorno.iMovimento);
    alert(oRetorno.sMessage.urlDecode());
  }

  document.onkeydown =  function(Event) {

    /**
     * F6
     */
    if (Event.which == 117) {

      if ($('windowDetalhes')) {
        oDBCodigoBarra.liberarCodigoDeBarra();
      }
      Event.preventDefault();
      Event.stopPropagation();
    }
  };

  //===================== Grid que conterá os movimentos a serem configurados ==========//

  function js_criaGridConfiguracao() {

    oGridConfiguracao = new DBGrid('oGridConfiguracao');
    oGridConfiguracao.nameInstance = 'oGridConfiguracao';
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */
    oGridConfiguracao.setCellWidth(['100px' ,
      '100px',
      '100px',
      '200px',
      '200px',
      '100px',
      '80px'
    ]);
    oGridConfiguracao.setCellAlign([ 'left'  ,
      'left'  ,
      'left',
      'left',
      'left',
      'right',
      'center'
    ]);
    oGridConfiguracao.setHeader(['Cód.Mov',
      'Emp. / Slip',
      'Recurso',
      'Cta. Pagadora',
      'Nome',
      'Valor',
      'Ação']);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */
    oGridConfiguracao.setHeight(150);
    oGridConfiguracao.show($('ctnGridConfiguracao'));
    oGridConfiguracao.clearAll(true);
  }


  js_criaGridConfiguracao();

  //===================== chamada de funçoes da janela de detalhes ==========//
  function js_criaJanelaDetalhes(iCodMov, iCodigoRecurso) {

    var tituloWindow = $('sTituloWindow');

    if ( tituloWindow && tituloWindow.innerHTML !== '' ) {
      tituloWindow.innerHTML = '';
    }

    js_viewConfiguracao(iCodMov);
    js_criaGridDetalhes();
    js_getDetalhes(iCodMov);
    js_getTipoTransmissao(iCodMov, iCodigoRecurso);
    js_criaCodigoBarra();

  }

  //===================== Grid que conterá os detalhes do movimento ==========//

  function js_criaGridDetalhes() {

    oGridConfiguracaoDetalhe = new DBGrid('oGridConfiguracaoDetalhe');
    oGridConfiguracaoDetalhe.nameInstance = 'oGridConfiguracaoDetalhe';
    oGridConfiguracaoDetalhe.setCheckbox(0);
    oGridConfiguracaoDetalhe.setCellWidth(['260px',
      ' 90px',
      ' 60px',
      ' 60px',
      ' 60px',
      ' 70px',
      '0px',
      '50px'
    ]);
    oGridConfiguracaoDetalhe.setCellAlign(['left',
      'right'  ,
      'right',
      'right',
      'center',
      'left',
      'center',
      'center'
    ]);
    oGridConfiguracaoDetalhe.setHeader(['Código de Barras',
      'Valor',
      'Juros',
      'Desconto',
      'Data',
      'Tipo de Fatura',
      'Linha Digitável',
      'Finalidade'
    ]);
    oGridConfiguracaoDetalhe.hasTotalizador = true;
    oGridConfiguracaoDetalhe.setHeight(150);
    oGridConfiguracaoDetalhe.aHeaders[7].lDisplayed = false;
//    oGridConfiguracaoDetalhe.aHeaders[8].lDisplayed = false;
    oGridConfiguracaoDetalhe.show($('ctnGridConfiguracaoDetalhes'));
    oGridConfiguracaoDetalhe.clearAll(true);
  }

  //================== Janela para Configurar detalhes ============//
  function js_viewConfiguracao (iCodMov) {

    var iMovimento     = iCodMov;
    var iLarguraJanela = 1024;
    var iAlturaJanela  = 750;

    if (typeof(windowDetalhes) != 'undefined' && windowDetalhes instanceof windowAux) {
      windowDetalhes.destroy();
    }

    windowDetalhes   = new windowAux( 'windowDetalhes',
      'Configuração do Movimento',
      iLarguraJanela,
      iAlturaJanela
    );

    var sConteudoDetalhes  = "<div>";
    sConteudoDetalhes += "<div id='sTituloWindow'></div> "; // container do message box

    sConteudoDetalhes += "  <center>  <br>";
    sConteudoDetalhes += "  <fieldset style='width: 95%;'><legend><strong> Configuração </strong></legend>";
    sConteudoDetalhes += "     <table border = 0 align='left'>  ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td style='width:130px'>   ";
    sConteudoDetalhes += "         <strong>Código do Movimento: </strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "         <span>" + iMovimento + "</span>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    /* FINALIDADE */
    sConteudoDetalhes += "      <tr>";
    sConteudoDetalhes += "        <td class='bold'><label for='finalidade'>Finalidade:</label></td>";
    sConteudoDetalhes += "        <td>";
    sConteudoDetalhes += "          <select id='finalidade'>";
    sConteudoDetalhes += "            <option value='000'>Nenhuma</option>";
    sConteudoDetalhes += "            <option value='100'>100 - Transferência de Tributos Retidos</option>";
    sConteudoDetalhes += "            <option value='101'>101 - Transferência para Municípios sem Gestão Plena da Saúde</option>";
    sConteudoDetalhes += "            <option value='102'>102 - FOPAG</option>";
    sConteudoDetalhes += "        </td>";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td>   ";
    sConteudoDetalhes += "         <strong><label for='iTipoTransmissao'>Tipo de Transmissão:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "         <label id='ctnTipoTransmissao' style='float:left;'>";
    sConteudoDetalhes += "           <select id='iTipoTransmissao' name='iTipoTransmissao' onChange='js_exibeCamposObn(this.value)' style='width:130px;'>";
    sConteudoDetalhes += "           </select>";
    sConteudoDetalhes += "         </label>";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td >   ";
    sConteudoDetalhes += "         <strong><label for='iTipoFatura'>Tipo de Fatura:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "           <select id='iTipoFatura' name='iTipoFatura' style='width:130px;' >";
    sConteudoDetalhes += "             <option value='1'>Fatura</option>           ";
    sConteudoDetalhes += "             <option value='2'>Convênio</option>         ";
    sConteudoDetalhes += "           </select>";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap id='linhadigitavel'>     ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap id='codigodebarras'>     ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td >   ";
    sConteudoDetalhes += "         <strong><label for='txtValor'>Valor:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "          <label id='ctnValor' style='float:left;'></label>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td >   ";
    sConteudoDetalhes += "         <strong><label for='txtJuros'>Juros:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "          <label id='ctnJuros' style='float:left;'></label>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td >   ";
    sConteudoDetalhes += "         <strong><label for='txtDesconto'>Desconto:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "          <label id='ctnDesconto' style='float:left;'></label>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    sConteudoDetalhes += "      <tr nowrap>     ";
    sConteudoDetalhes += "        <td >   ";
    sConteudoDetalhes += "         <strong><label for='txtData'>Data:</label></strong>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "        <td align='left'>   ";
    sConteudoDetalhes += "          <label id='ctnData' style='float:left;'></label>  ";
    sConteudoDetalhes += "        </td>  ";
    sConteudoDetalhes += "      </tr>    ";

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */

    sConteudoDetalhes += " </table> </fieldset>";

    sConteudoDetalhes += "<div style='margin-top:10px;'>";
    sConteudoDetalhes += " <input type='button' value='Adicionar' id = 'incluir' onclick='js_incluirDetalhe(" + iMovimento + ")'/>";
    sConteudoDetalhes += "</div>"  ;

    sConteudoDetalhes += "<fieldset style='width:95%;'>";
    sConteudoDetalhes += "<legend><strong>Dados Configurados para o Movimento</strong></legend>";
    sConteudoDetalhes += "  <div style='margin-top:10px;'>";
    sConteudoDetalhes += "    <div id='ctnGridConfiguracaoDetalhes'> </div>";
    sConteudoDetalhes += "  </div>"  ;
    sConteudoDetalhes += "</fieldset>"  ;

    sConteudoDetalhes += "<div style='margin-top:10px;'>";
    sConteudoDetalhes += " <input type='button' value='Salvar' id = 'salvar' onclick='js_salvarDetalhes(" + iMovimento + ");' />";
    sConteudoDetalhes += " <input type='button' value='Excluir Selecionados' id = 'excluir' onclick='js_removerDetalhes("+iMovimento+");' />";
    sConteudoDetalhes += "</div>"  ;

    sConteudoDetalhes += "  </center> ";

    sConteudoDetalhes += "</div>";

    windowDetalhes.setContent(sConteudoDetalhes);
    windowDetalhes.allowCloseWithEsc(false);


    //============  MESAGE BORD PARA TITULO da JANELA
    var sTextoMessageBoard   = 'Detalhes do movimento a serem enviados no arquivo. Para cancelar a leitura do código de barras, pressione <b>ESC</b>.';
    sTextoMessageBoard  += '<br />Pressione F6 para ativar leitura do código de barras.';
    messageBoard         = new DBMessageBoard('msgboard1', 'Características do Movimento.', sTextoMessageBoard, $('sTituloWindow'));

    //funcao para corrigir a exibição do window aux, apos fechar a primeira vez

    windowDetalhes.setShutDownFunction(function () {

      windowDetalhes.destroy();
      js_pesquisar();
      delete windowDetalhes;
    });

    windowDetalhes.show();
    messageBoard.show();


    oTxtValor        = new DBTextField('txtValor','oTxtValor', null, 10);
    oTxtData         = new DBTextFieldData('txtData','oTxtData', null);
    oTxtJuros        = new DBTextField('txtJuros','oTxtJuros', null, 10);
    oTxtDesconto     = new DBTextField('txtDesconto','oTxtDesconto', null ,10);

    oTxtValor      .addEvent("onKeyPress", "return js_teclas(event,this)");
    oTxtJuros      .addEvent("onKeyPress", "return js_teclas(event,this)");
    oTxtDesconto   .addEvent("onKeyPress", "return js_teclas(event,this)");

    oTxtValor      .show($('ctnValor'));
    oTxtData       .show($('ctnData'));
    oTxtJuros      .show($('ctnJuros'));
    oTxtDesconto   .show($('ctnDesconto'));

  }



  //  --------==========  PESQUISAS DOS FILTROS =======================

  //--------FUNCAO PESQUISA DE ORDEM---------------------------------------------------
  function js_pesquisae82_codord(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_pagordem',
        'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord',
        'Pesquisa Ordens de Pagamento',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30
      );
    }else{
      ord01 = new Number(document.form1.e82_codord.value);
      ord02 = new Number(document.form1.e82_codord02.value);
      if(ord01 > ord02 && ord01 != "" && ord02 != ""){
        alert("Selecione uma ordem menor que a segunda!");
        document.form1.e82_codord.focus();
        document.form1.e82_codord.value = '';
      }
    }
  }
  function js_mostrapagordem1(chave1){
    document.form1.e82_codord.value = chave1;
    db_iframe_pagordem.hide();
  }
  //-----------------------------------------------------------
  //---ordem 02
  function js_pesquisae82_codord02(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_pagordem',
        'func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord',
        'Pesquisa Ordens de Pagamento',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30
      );
    }else{
      ord01 = new Number(document.form1.e82_codord.value);
      ord02 = new Number(document.form1.e82_codord02.value);
      if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
        alert("Selecione uma ordem maior que a primeira");
        document.form1.e82_codord02.focus();
        document.form1.e82_codord02.value = '';
      }
    }
  }
  function js_mostrapagordem102(chave1,chave2){
    document.form1.e82_codord02.value = chave1;
    db_iframe_pagordem.hide();
  }
  //======================  PESQISA EMPENHO ============================
  function js_pesquisae60_codemp(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_empempenho',
        'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp',
        'Pesquisar Empenhos',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }
  }
  function js_mostraempempenho2(chave1){
    document.form1.e60_codemp.value = chave1;
    db_iframe_empempenho.hide();
  }

  //===================  PESQUISA DE RECURSOS ============================

  function js_pesquisac62_codrec(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_orctiporec',
        'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
        'Pesquisar Recursos',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }else{
      if(document.form1.o15_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
          'db_iframe_orctiporec',
          'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+
          '&funcao_js=parent.js_mostraorctiporec',
          'Pesquisar Recursos',
          false,
          22,
          0,
          document.body.getWidth() - 12,
          document.body.scrollHeight - 30);
      }else{
        document.form1.o15_descr.value = '';
      }
    }
  }
  function js_mostraorctiporec(chave,erro){
    document.form1.o15_descr.value = chave;
    if(erro==true){
      document.form1.o15_codigo.focus();
      document.form1.o15_codigo.value = '';
    }
  }

  function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
  }

  //=============  PESQUISA DE CGM ============================================

  function js_pesquisaz01_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
        'func_nome',
        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
        'Pesquisar CGM',
        true,
        22,
        0,
        document.body.getWidth() - 12,
        document.body.scrollHeight - 30);
    }else{
      if(document.form1.z01_numcgm.value != ''){

        js_OpenJanelaIframe('',
          'func_nome',
          'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
          '&funcao_js=parent.js_mostracgm',
          'Pesquisar CGM',
          false,
          22,
          0,
          document.width-12,
          document.body.scrollHeight-30);
      }else{
        document.form1.z01_nome.value = '';
      }
    }
  }
  function js_mostracgm(erro,chave){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.z01_numcgm.focus();
      document.form1.z01_numcgm.value = '';
    }
  }

  function js_mostracgm1(chave1,chave2){

    document.form1.z01_numcgm.value = chave1;
    document.form1.z01_nome.value   = chave2;
    func_nome.hide();

  }

  //==================   PESQUISA SLIPS
  //-----------------------------------------------------------
  function js_pesquisak17_slip(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
    }else{
      ord01 = new Number(document.form1.k17_slip.value);
      ord02 = new Number(document.form1.k17_slip02.value);
      if(ord01 > ord02 && ord01 != "" && ord02 != ""){
        alert("Selecione um slip menor que o segundo!");
        document.form1.k17_slip.focus();
        document.form1.k17_slip.value = '';
      }
    }
  }
  function js_mostraslip1(chave1){
    document.form1.k17_slip.value = chave1;
    db_iframe_slip.hide();
  }
  //-----------------------------------------------------------
  function js_pesquisak17_slip02(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip2','func_slip.php?funcao_js=parent.js_mostraslip102|k17_codigo','Pesquisa',true);
    }else{
      ord01 = new Number(document.form1.k17_slip.value);
      ord02 = new Number(document.form1.k17_slip02.value);
      if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
        alert("Selecione um slip maior que o primeiro");
        document.form1.k17_slip02.focus();
        document.form1.k17_slip02.value = '';
      }
    }
  }
  function js_mostraslip102(chave1,chave2){
    document.form1.k17_slip02.value = chave1;
    db_iframe_slip2.hide();
  }

  function js_exibeCamposObn(iTipoTransmissao){

    switch (iTipoTransmissao) {

      case PAGAMENTO_CNAB :

        $('btnCodigoBarra').disabled    = true;
        $('txtValor').readOnly          = true;
        $('txtLinhaDigitavel').readOnly = true;
        $('txtJuros').readOnly          = true;
        $('txtDesconto').readOnly       = true;
        $('txtData').readOnly           = true;
        $('iTipoFatura').disabled       = true;
        $('txtValor').value             = '';
        $('txtLinhaDigitavel').value    = '';
        $('txtCodigoBarra').value       = '';
        $('txtJuros').value             = '';
        $('txtDesconto').value          = '';
        $('txtData').value              = '';

        /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte6] */
        /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte6] */

        $('txtValor').style.backgroundColor          = '#DEB887';
        $('txtLinhaDigitavel').style.backgroundColor = '#DEB887';
        $('txtJuros').style.backgroundColor          = '#DEB887';
        $('txtDesconto').style.backgroundColor       = '#DEB887';
        $('txtData').style.backgroundColor           = '#DEB887';
        $('iTipoFatura').style.backgroundColor       = '#DEB887';

        $('dtjs_txtData').style.display = 'none';
        $('incluir').disabled = true;
        break;

      case PAGAMENTO_OBN :
      case PAGAMENTO_PAGFOR :

        $('btnCodigoBarra').disabled    = false;
        $('txtValor'). readOnly         = false;
        $('txtLinhaDigitavel').readOnly = false;
        $('txtJuros'). readOnly         = false;
        $('txtDesconto'). readOnly      = false;
        $('txtData'). readOnly          = false;
        $('iTipoFatura'). disabled      = false;

        /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte7] */
        /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte7] */

        $('txtValor').style.backgroundColor          = '';
        $('txtLinhaDigitavel').style.backgroundColor = '';
        $('txtJuros').style.backgroundColor          = '';
        $('txtDesconto').style.backgroundColor       = '';
        $('txtData').style.backgroundColor           = '';
        $('iTipoFatura').style.backgroundColor       = '';
        $('dtjs_txtData').style.display              = '';
        $('incluir').disabled                        = false;
        break;
    }
  }

  /**
   * Verifica o recurso configurado com FUNDEB
   */
  function js_recursoParametroFundeb() {

    js_divCarregando(_M(sArquivoMensagens + ".verificando_recurso_fundeb"), 'msgBox');

    var oParam  = new Object();
    oParam.exec = "getRecursoFundeb";

    new Ajax.Request(sUrlRPC,
      {method: 'post',
        parameters: "json="+Object.toJSON(oParam),
        onComplete: function (oAjax){

          js_removeObj("msgBox");
          var oRetorno = eval("("+oAjax.responseText+")");
          iCodigoRecursoFundeb = oRetorno.iCodigoRecurso;

        }
      });
  }
  js_recursoParametroFundeb();


  function start() {

    if (oGet.origem !== undefined) {

      var tipoEmpenho = '0';
      if (oGet.origem === 'slip') {
        tipoEmpenho = '1';
      }
      document.getElementById('iFomaconsulta').value = tipoEmpenho ;
      js_formaConsulta();
      js_pesquisar();
    }
  }
  start();
</script>
