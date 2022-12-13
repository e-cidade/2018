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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_pcproc_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_emptipo_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_cflicita_classe.php"));
$clpcproc       = new cl_pcproc;
$clcflicita     = new cl_cflicita;
$clpcparam      = new cl_pcparam;
$clpctipocompra = new cl_pctipocompra;
$clsolicita     = new cl_solicita;
$clemptipo      = new cl_emptipo;
$clempautoriza  = new cl_empautoriza;
$clempautoriza->rotulo->label();
$clpcproc->rotulo->label();
$clrotulo       = new rotulocampo;
$clrotulo->label("pc12_tipo");
$clrotulo->label("e54_codtipo");
$clrotulo->label("e54_autori");
$clrotulo->label("e54_destin");
$clrotulo->label("e54_numerl");
$clrotulo->label("e54_tipol");
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_resumo");
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
  db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js");
  db_app::load("estilos.css, grid.style.css, AjaxRequest.js");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<br>
<br>
<center>
  <fieldset style="width: 75%;">
    <legend><b>Gerar Autorizações</b></legend>
    <table style='width: 100%' border='0'>
      <tr>
        <td width="100%">
          <table width="100%">
            <tr style="text-align: center;">
              <td title="<?php echo $Tac16_sequencial ; ?>">
                <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);", 1); ?>
                <span id='ctnTxtCodigoAcordo'></span>
                <span id='ctnTxtDescricaoAcordo'></span>
              </td>
            </tr>
            <tr>
              <td colspan="3" style="text-align: center">
                <input type="button" value='Pesquisar' id='btnPesquisarPosicoes' disabled="disabled" />
              </td>
            </tr>
            <tr>
              <td colspan='3'>
                <fieldset>
                  <div id='ctnGridPosicoes'>
                  </div>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan='3'>
                <fieldset>
                  <div id='ctnGridItens'>
                  </div>
                </fieldset>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="text-align: center">
        </td>
      </tr>
    </table>
  </fieldset>
  <input type='button' value='Visualizar Autorizações' onclick="js_buscarInformacoesAutorizacao();" style="margin-top: 10px;">
</center>
<div id='frmDadosAutorizacao' style='display: none'>
  <form name='form1'>
    <center>
      <table>
        <tr>
          <td>
            <fieldset><legend><b>Dados Complementares</b></legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Te54_destin?>">
                    <?=$Le54_destin?>
                  </td>
                  <td>
                    <?
                    db_input("e54_destin",40,$Ie54_destin,true,"text",1);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tpc12_tipo?>">
                    <?=@$Lpc12_tipo?>
                  </td>
                  <td>
                    <?
                    $parampesquisa = true;
                    if(isset($tipodecompra)){
                      $e54_codcom = $tipodecompra;
                    }
                    $instit = db_getsession("DB_instit");
                    if((isset($pc12_tipo) && $pc12_tipo=='' || !isset($pc12_tipo)) && !isset($tipodecompra)){
                      $somadata = $clpcparam->sql_record($clpcparam->sql_query_file($instit,"pc30_tipcom as e54_codcom"));
                      if($clpcparam->numrows>0){
                        db_fieldsmemory($somadata,0);
                      }
                    }
                    $result_tipocompra = $clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom,pc50_descr"));
                    $aTipoCompra = array();
                    for ($iRowCompra = 0; $iRowCompra < $clpctipocompra->numrows; $iRowCompra++) {

                      $oStdCompra = db_utils::fieldsMemory($result_tipocompra, $iRowCompra);
                      $aTipoCompra[$oStdCompra->pc50_codcom] = $oStdCompra->pc50_descr;
                    }
                    db_select('e54_codcom', $aTipoCompra, true, 1, "onchange='pesquisarTipoLicitacao()'");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Te54_tipol?>">
                    <?=@$Le54_tipol?>
                  </td>
                  <td style="width: 100%">
                    <?
                    if (isset($tipodecompra) || isset($e54_codcom)) {

                      if(isset($e54_codcom) && empty($tipodecompra)) {
                        $tipodecompra=$e54_codcom;
                      }
                      $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",
                                                                                  '',"l03_codcom=$tipodecompra"));
                      if($clcflicita->numrows>0){
                        $aTipoLicitacao = array('0' => 'Selecione o Tipo de Compra');
                        db_select('e54_tipol', $aTipoLicitacao, true, 1);
                        $dop=1;
                      }else{
                        $e54_tipol='';
                        $e54_numerl='';
                        db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
                        $dop=3;
                      }
                    }else{
                      $dop=3;
                      $e54_tipol='';
                      $e54_numerl='';
                      db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><?=@$Le54_numerl?></td>
                  <td>
                    <?php
                    db_input('e54_numerl', 8, $Ie54_numerl, true, 'text', $dop);
                    ?>
                  </td>
                </tr>

                <tr>
                  <td nowrap title="<?=@$Te54_codtipo?>">
                    <?=$Le54_codtipo?>
                  </td>
                  <td>
                    <?
                    $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
                    db_selectrecord("e54_codtipo",$result,true,1);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="Característica Peculiar">
                    <?php
                    db_ancora("<b>Característica Peculiar:</b>", "js_pesquisaCaracteristicaPeculiar(true);", 1);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('iSequenciaCaracteristica', 5, '', true, 'text', 2, "onchange='js_pesquisaCaracteristicaPeculiar(false);'");
                    db_input('sDescricaoCaracteristica', 31, '', true, 'text', 3);
                    ?>
                  </td>
                </tr>
                <?
                $db_opcao=1;
                ?>

                <tr>
                  <td nowrap title="<?=@$Te54_praent?>">
                    <?=@$Le54_praent?>
                  </td>
                  <td>
                    <?
                    db_input('e54_praent',30,$Ie54_praent,true,'text',$db_opcao,"")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Te54_conpag?>">
                    <?=@$Le54_conpag?>
                  </td>
                  <td>
                    <?
                    db_input('e54_conpag',30,$Ie54_conpag,true,'text',$db_opcao,"")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Te54_conpag?>" colspan="3">
                    <fieldset>
                      <legend><b>Observacoes</b></legend>

                      <?
                      db_textarea('e54_resumo', 3, 54, 'e54_resumo', true, 'text', $db_opcao,"")
                      ?>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td style="text-align: center">
            <input type='button' value='Incluir Autorizações' onclick="js_processarAutorizacoes(false)">
          </td>
        </tr>
      </table>
    </center>
  </form>
</div>
</body>
</html>
<script>

  var sUrlRpc = 'con4_contratosmovimentacoesfinanceiras.RPC.php';
  var oTipoLicitacao = $('e54_tipol');
  var $oTipoCompra   = $('e54_codcom');
  oTipoLicitacao.style.width = '100%';
  $oTipoCompra.style.width = '100%';


  function pesquisarTipoLicitacao() {

    var oTipoLicitacao = $('e54_tipol');

    new AjaxRequest(
      'lic4_geraAutorizacoes.RPC.php',
      { exec : 'getTipoLicitacao', iTipoCompra : $F('e54_codcom') },
      function (oRetorno, lErro) {

        if (oRetorno.aTiposLicitacao.length == 0) {
          return;
        }

        oTipoLicitacao.options.length = 0;
        oRetorno.aTiposLicitacao.each(
          function (oTipo) {

            var oDocumento = document.createElement('option');
            oDocumento.value = oTipo.l03_tipo;
            oDocumento.innerHTML = oTipo.l03_descr + " - " + oTipo.l03_tipo;
            oTipoLicitacao.appendChild(oDocumento);
          }
        );

      }
    ).setMessage('Aguarde, carregando tipo de licitação...').execute();
  }
  pesquisarTipoLicitacao();

  /**
   * Pesquisa acordos
   */
  var iPosicaoAtual = 0;

  function js_pesquisaCaracteristicaPeculiar(lMostra) {

    if (lMostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?funcao_js=parent.js_preencheCaracteristicaPeculiar|c58_sequencial|c58_descr&filtro=receita', 'Pesquisa Característica Peculiar', true);
      $('Jandb_iframe_concarpeculiar').style.zIndex = 100;
    } else {
      if ($("iSequenciaCaracteristica").value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?pesquisa_chave=' + $("iSequenciaCaracteristica").value + '&funcao_js=parent.js_mostraCaracteristicaPeculiar&filtro=receita', 'Pesquisa Característica Peculiar', false);
      } else {
        document.form1.sDescricaoCaracteristica.value = '';
      }
    }
  }

  function js_preencheCaracteristicaPeculiar(iCodigoCaracteristica, sDescricaoCaracteristica) {

    $("iSequenciaCaracteristica").value = iCodigoCaracteristica;
    $("sDescricaoCaracteristica").value = sDescricaoCaracteristica;
    db_iframe_concarpeculiar.hide();
  }

  function js_mostraCaracteristicaPeculiar(sDescricao, lErro) {

    if (lErro) {

      $("iSequenciaCaracteristica").value = "";
      $("sDescricaoCaracteristica").value = sDescricao;
      return false;
    }

    $("iSequenciaCaracteristica").focus();
    $("sDescricaoCaracteristica").value = sDescricao;
  }

  function js_pesquisaac16_sequencial(lMostrar) {

    var sUrl = 'func_acordo.php?lDepartamento=1&iTipoFiltro=4&lGeraAutorizacao=true&lNaoTrazerLicitacaoNaoGeraDespesa';
    if (lMostrar == true) {

      sUrl += '&funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto';
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_acordo', sUrl, 'Pesquisar Acordo', true);
    } else {

      if (oTxtCodigoAcordo.getValue() != '') {

        sUrl += '&descricao=true&pesquisa_chave='+oTxtCodigoAcordo.getValue()+'&funcao_js=parent.js_mostraacordo';
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_acordo', sUrl, 'Pesquisar Acordo', false);
      } else {

        oTxtCodigoAcordo.setValue('');
        oTxtDescricaoAcordo.setValue('');
        $('btnPesquisarPosicoes').setAttribute('disabled', 'disabled');
      }
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo(chave1,chave2,erro) {

    if (erro == true) {

      oTxtCodigoAcordo.setValue('');
      oTxtDescricaoAcordo.setValue('');
      $('oTxtDescricaoAcordo').focus();
      $('btnPesquisarPosicoes').setAttribute('disabled', 'disabled');
    } else {

      oTxtCodigoAcordo.setValue(chave1);
      oTxtDescricaoAcordo.setValue(chave2);
     $('btnPesquisarPosicoes').removeAttribute('disabled');
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo1(chave1,chave2) {

    oTxtCodigoAcordo.setValue(chave1);
    oTxtDescricaoAcordo.setValue(chave2);
    db_iframe_acordo.hide();
    $('btnPesquisarPosicoes').removeAttribute('disabled');
  }

  function js_main() {

    oTxtCodigoAcordo = new DBTextField('oTxtCodigoAcordo', 'oTxtCodigoAcordo','', 10);
    oTxtCodigoAcordo.addEvent("onChange",";js_pesquisaac16_sequencial(false);");
    oTxtCodigoAcordo.show($('ctnTxtCodigoAcordo'));

    oTxtDescricaoAcordo = new DBTextField('oTxtDescricaoAcordo', 'oTxtDescricaoAcordo','', 50);
    oTxtDescricaoAcordo.show($('ctnTxtDescricaoAcordo'));
    oTxtDescricaoAcordo.setReadOnly(true);

    oGridPosicoes = new DBGrid('oGridPosicoes');
    oGridPosicoes.setHeader(new Array('Código', 'Número', 'Tipo', "Data", "Emergencial"));
    oGridPosicoes.setHeight(100);
    oGridPosicoes.show($('ctnGridPosicoes'));

    oGridItens = new DBGrid('oGridItens');
    oGridItens.nameInstance = "oGridItens";
    oGridItens.setCheckbox(0);
    oGridItens.setCellWidth(new Array('10%', '40%',  "20%", "20%","20%", "20%", "20%"));
    oGridItens.setHeader(new Array("Código", "Material", "Quantidade", "Val. Unitário",
      "Valor Total", "Qtde Autorizar", "Valor Autorizar", "Dotacoes", "iSeq"));
    oGridItens.aHeaders[4].lDisplayed = false;
    oGridItens.aHeaders[9].lDisplayed = false;
    oGridItens.aHeaders[8].lDisplayed = false;
    oGridItens.setHeight(160);
    oGridItens.show($('ctnGridItens'));
    $('btnPesquisarPosicoes').onclick = js_pesquisarPosicoesContrato;
    iTipoAcordo = 0;
  }

  function js_pesquisarPosicoesContrato() {

    if (oTxtCodigoAcordo.getValue() == "") {

      alert('Informe um acordo!');
      return false;
    }
    js_divCarregando('Aguarde, pesquisando dados do acordo', 'msgbox');
    oGridItens.clearAll(true);
    oGridPosicoes.clearAll(true);
    var oParam                 = new Object();
    oParam.exec                = 'getPosicoesAcordo';
    oParam.lGeracaoAutorizacao = true;
    oParam.iAcordo = oTxtCodigoAcordo.getValue();
    var oAjax      = new Ajax.Request(sUrlRpc,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoGetPosicoesAcordo
      }
    )
  }

  function js_retornoGetPosicoesAcordo(oAjax) {

    js_removeObj('msgbox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridPosicoes.clearAll(true);
    iTipoAcordo = oRetorno.tipocontrato;
    if (oRetorno.status == 1) {

      oRetorno.posicoes.each(function (oPosicao, iLinha) {

        var aLinha = new Array();
        aLinha[0]  = oPosicao.codigo;
        aLinha[1]  = oPosicao.numero;
        aLinha[2]  = oPosicao.tipo+' - '+oPosicao.descricaotipo.urlDecode();
        aLinha[3]  = oPosicao.data;
        aLinha[4]  = oPosicao.emergencial.urlDecode();
        oGridPosicoes.addRow(aLinha);
        oGridPosicoes.aRows[iLinha].sEvents='ondblclick="js_getItensPosicao('+oPosicao.codigo+','+iLinha+')"';
      });
      oGridPosicoes.renderRows();
    }
  }

  function js_getItensPosicao(iCodigo, iLinha) {

    oGridPosicoes.aRows.each(function(oLinha, id) {
      oLinha.select(false);
    });
    oGridPosicoes.aRows[iLinha].select(true);
    js_divCarregando('Aguarde, pesquisando itens do acordo', 'msgbox');
    var oParam      = new Object();
    oParam.exec     = 'getPosicaoItens';
    oParam.iPosicao = iCodigo;
    iPosicaoAtual   = iCodigo;
    var oAjax       = new Ajax.Request(sUrlRpc,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoGetItensPosicao
      }
    )
  }

  function js_retornoGetItensPosicao(oAjax) {

    js_removeObj("msgbox");
    var oRetorno  = eval("("+oAjax.responseText+")");
    aItensPosicao = oRetorno.itens;
    oGridItens.clearAll(true);
    aItensPosicao.each(function (oItem, iSeq) {

      var nQtdeAut  = oItem.saldos.quantidadeexecutar;
      var nValorAut = js_formatar(oItem.saldos.valorautorizar, "f");

      var iQtFormatDecimal = 2;

      if (oItem.quantidade.getDecimalsLength() > iQtFormatDecimal) {
        iQtFormatDecimal = oItem.quantidade.getDecimalsLength();
      }

      aLinha    = new Array();
      aLinha[0] = oItem.codigomaterial;
      aLinha[1] = oItem.material.urlDecode();
      aLinha[2] = js_formatar(oItem.quantidade, 'f', iQtFormatDecimal);
      aLinha[3] = oItem.valorunitario;
      aLinha[4] = js_formatar(oItem.valortotal, 'f');
      /**
       * Caso for serviço e o mesmo não for controlado por quantidade, setamos a sua quantidade para 1
       */
      if (oItem.servico && (oItem.lControlaQuantidade == "" || oItem.lControlaQuantidade == "f")) {
        nQtdeAut = 1;
      }
      aLinha[5] = eval("qtditem"+iSeq+" = new DBTextField('qtditem"+iSeq+"','qtditem"+iSeq+"','"+nQtdeAut+"')");
      aLinha[5].addStyle("text-align","right");
      aLinha[5].addStyle("height","100%");
      aLinha[5].addStyle("width","100px");
      aLinha[5].addStyle("border","1px solid transparent;");
      aLinha[5].addEvent("onBlur","js_bloqueiaDigitacao(this, false);");
      aLinha[5].addEvent("onBlur","qtditem"+iSeq+".sValue=this.value;");
      aLinha[5].addEvent("onBlur","js_calculaValor(this,"+iSeq+", false);");
      aLinha[5].addEvent("onFocus","js_liberaDigitacao(this, false);");
      aLinha[5].addEvent("onKeyPress","return js_teclas(event,this);");
      aLinha[5].addEvent("onKeyDown","return js_verifica(this,event,false)");
      if (oItem.servico && (oItem.lControlaQuantidade == "" || oItem.lControlaQuantidade == "f")) {
        aLinha[5].setReadOnly(true);
        aLinha[5].addEvent("onFocus","js_bloqueiaDigitacao(this, true);");
      }
      aLinha[6] = eval("valoritem"+iSeq+" = new DBTextField('valoritem"+iSeq+"','valoritem"+iSeq+"','"+nValorAut+"')");
      aLinha[6].addStyle("text-align","right");
      aLinha[6].addStyle("height","100%");
      aLinha[6].addStyle("width","100px");
      aLinha[6].addStyle("border","1px solid transparent;");
      aLinha[6].addEvent("onBlur","js_bloqueiaDigitacao(this, true);");
      aLinha[6].addEvent("onBlur","valoritem"+iSeq+".sValue=this.value;");
      aLinha[6].addEvent("onFocus","js_liberaDigitacao(this, true);");
      aLinha[6].addEvent("onKeyPress","return js_teclas(event,this);");
      aLinha[6].addEvent("onBlur","js_salvarInfoDotacoes("+iSeq+");");
      aLinha[6].addEvent("onKeyDown","return js_verifica(this,event,true);");
      if (!oItem.servico || (oItem.servico && oItem.lControlaQuantidade == "t")) {

        aLinha[6].setReadOnly(true);
        aLinha[6].addEvent("onFocus","js_bloqueiaDigitacao(this, true);");
      }

      aLinha[7] = "<input type='button' id='dotacoes"+iSeq+"'  onclick='js_ajusteDotacao("+iSeq+")' value='Dotações'>";
      aLinha[8] = new String(iSeq).valueOf();

      lDesativaLinha = false;
      if (nQtdeAut == 0 || nValorAut == '0,00') {
        lDesativaLinha = true;
      }

      oGridItens.addRow(aLinha, null, lDesativaLinha);

    });
    oGridItens.renderRows();
    aItensPosicao.each(function (oItem, iLinha){
      js_salvarInfoDotacoes(iLinha);
    });

  }

  /**
   * bloqueia  o input passado como parametro para a digitacao.
   * É colocado  a mascara do valor e bloqueado para Edição
   */
  function js_bloqueiaDigitacao(object, lFormata) {

    object.readOnly         = true;
    object.style.border     ='1px';
    object.style.fontWeight = "normal";
    if (lFormata) {
      object.value            = js_formatar(object.value,'f');
    }

  }
  /**
   * Libera  o input passado como parametro para a digitacao.
   * é Retirado a mascara do valor e liberado para Edição
   * é Colocado a Variavel nValorObjeto no escopo GLOBAL
   */
  function js_liberaDigitacao(object, lFormata) {

    nValorObjeto        = object.value;
    object.value        = object.value;
    if (lFormata) {
      object.value        = js_strToFloat(object.value).valueOf();
    }
    object.style.border = '1px solid black';
    object.readOnly     = false;
    object.style.fontWeight = "bold";
    object.select();

  }
  /**
   * Verifica se  o usuário cancelou a digitação dos valores.
   * Caso foi cancelado, voltamos ao valor do objeto, e
   * bloqueamos a digitação
   */
  function js_verifica(object, event, lFormata) {

    var teclaPressionada = event.which;
    if (teclaPressionada == 27) {
      object.value = nValorObjeto;
      js_bloqueiaDigitacao(object, lFormata);
    }
  }

  function js_calculaValor(obj, iLinha) {

    var aLinha = oGridItens.aRows[iLinha];

    if (aLinha.aCells[6].getValue() > aItensPosicao[iLinha].saldos.quantidadeexecutar || aLinha.aCells[6].getValue() == 0) {

      aLinha.aCells[6].content.setValue(aItensPosicao[iLinha].saldos.quantidadeexecutar);
      obj.value = aItensPosicao[iLinha].saldos.quantidadeexecutar;
      aLinha.aCells[7].content.setValue(aLinha.aCells[5].getValue());
    } else {

      var iQtFormatDecimal = 2;
      var sValorTotal = new String(aLinha.aCells[6].getValue() * aLinha.aCells[4].getValue());

      if (sValorTotal.getDecimalsLength() > iQtFormatDecimal) {
        iQtFormatDecimal = sValorTotal.getDecimalsLength();
      }

      sValorTotal = js_formatar(sValorTotal, "f", iQtFormatDecimal);

      aLinha.aCells[7].content.setValue(sValorTotal);
      $("valoritem" + iLinha).value = sValorTotal;
    }

    js_salvarInfoDotacoes(iLinha);
  }

  function js_ajusteDotacao(iLinha) {

    if ($('wndDotacoesItem')) {
      return false;
    }
    oDadosItem  =  oGridItens.aRows[iLinha];
    var iHeight = js_round((screen.availHeight/1.3), 0);
    var iWidth  = screen.availWidth/2;
    windowDotacaoItem = new windowAux('wndDotacoesItem',
      'Dotações Item '+oDadosItem.aCells[2].getValue(),
      iWidth,
      iHeight
    );
    var sContent  = "<div>";
    sContent     += "<fieldset>";
    sContent     += "  <div id='cntgridDotacoes'>";
    sContent     += "  </div>";
    sContent     += "</fieldset>";
    sContent     += "<center>";
    sContent     += "<input type='button' id='btnSalvarInfoDot' value='Salvar' onclick=''>";
    sContent     += "</center>";
    windowDotacaoItem.setContent(sContent);
    oMessageBoard = new DBMessageBoard('msgboard1',
      'Adicionar Dotacoes',
      'Dotações Item '+oDadosItem.aCells[1].getValue()+" (valor A Autorizar: <b>"+
      oDadosItem.aCells[7].getValue()+"</b>)",
      $('windowwndDotacoesItem_content')
    );
    windowDotacaoItem.setShutDownFunction(function() {
      windowDotacaoItem.destroy();
    });

    $('btnSalvarInfoDot').observe("click", function() {

      var nTotalDotacoes = oGridDotacoes.sum(3, false);
      if (nTotalDotacoes != js_strToFloat(oDadosItem.aCells[7].getValue())) {

        alert('o Valor Total das Dotações não conferem com o total que está sendo autorizado no item!');
        return false;
      }
      aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {

        var nValue = js_strToFloat(oGridDotacoes.aRows[iDot].aCells[3].getValue());
        oDotacao.valorexecutar = nValue;
      });
      oGridItens.aRows[iLinha].select(true);
      windowDotacaoItem.destroy();
    });
    oMessageBoard.show();
    oGridDotacoes              = new DBGrid('gridDotacoes');
    oGridDotacoes.nameInstance = 'oGridDotacoes';
    oGridDotacoes.setCellWidth(new Array('30%', '30%', '30%', '10%'));
    oGridDotacoes.setHeader(new Array("Dotação", "Saldo", "Valor Aut.", "valor"));
    oGridDotacoes.setHeight(iHeight/3);
    oGridDotacoes.setCellAlign(new Array("center", "right", "right", "Center"));
    oGridDotacoes.show($('cntgridDotacoes'));
    oGridDotacoes.clearAll(true);
    var nValor          =  js_strToFloat(oDadosItem.aCells[7].getValue());
    var nValorTotalItem = js_strToFloat(oDadosItem.aCells[5].getValue());
    var nValorTotal     = nValor;
    aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {

      nValorDotacao = js_formatar(oDotacao.valorexecutar, "f");
      aLinha    = new Array();
      aLinha[0] = "<a href='#' onclick='js_mostraSaldo("+oDotacao.dotacao+");return false'>"+oDotacao.dotacao+"</a>";
      aLinha[1] = js_formatar(oDotacao.saldodotacao, "f");
      aLinha[2] = oDotacao.valor;
      aLinha[3] = eval("valordot"+iDot+" = new DBTextField('valordot"+iDot+"','valordot"+iDot+"','"+nValorDotacao+"')");
      aLinha[3].addStyle("text-align","right");
      aLinha[3].addStyle("height","100%");
      aLinha[3].addStyle("width","100px");
      aLinha[3].addStyle("border","1px solid transparent;");
      aLinha[3].addEvent("onBlur","valordot"+iDot+".sValue=this.value;");
      aLinha[3].addEvent("onBlur","js_ajustaValorDot(this,"+iDot+");");
      aLinha[3].addEvent("onBlur","js_bloqueiaDigitacao(this, true);");
      aLinha[3].addEvent("onFocus","js_liberaDigitacao(this, true);");
      aLinha[3].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");
      aLinha[3].addEvent("onKeyDown","return js_verifica(this,event,true)")
      oGridDotacoes.addRow(aLinha);
    });
    windowDotacaoItem.show();
    oGridDotacoes.renderRows();

  }

  function js_salvarInfoDotacoes(iLinha) {

    var oDadosItem = oGridItens.aRows[iLinha];
    var nValor = js_strToFloat(js_formatar(oDadosItem.aCells[7].getValue(), 'f'));
    var nValorTotalItem = 0;

    /**
     * Obtém o valor total original do item da solicitação, por meio da soma
     * dos valores das dotações vinculadas.
     */
    aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {
      nValorTotalItem += new Number(oDotacao.valor);
    });

    var nValorTotal = nValor;

    aItensPosicao[iLinha].dotacoes.each(function (oDotacao, iDot) {

      var nPercentual    = (new Number(oDotacao.valor) * 100) / nValorTotalItem;
      var nValorDotacao  = js_round((nValor * nPercentual) / 100, 2);

      nValorTotal -= nValorDotacao;
      if (iDot == aItensPosicao[iLinha].dotacoes.length - 1) {

        if (nValorTotal != nValor) {
          nValorDotacao += nValorTotal;
        }
      }
      aItensPosicao[iLinha].dotacoes[iDot].valorexecutar = js_round(nValorDotacao, 2);
    });
  }

  function js_ajustaValorDot(Obj, iDot) {

    var nValor         = new Number(Obj.value);
    var nTotalDotacoes = oGridDotacoes.sum(3, false);
    var nValorAut      = js_strToFloat(oDadosItem.aCells[7].getValue());
    if (nValor > nValorAut) {
      oGridDotacoes.aRows[iDot].aCells[3].content.setValue(nValorObjeto);
    } else if (nTotalDotacoes > nValorAut) {
      oGridDotacoes.aRows[iDot].aCells[3].content.setValue(nValorObjeto);
    }
  }
  /**
   * Abre uma loopkup com a pesquisa dos saldos da Dotacao do Ano corrente
   */
  function js_mostraSaldo(chave){

    arq = 'func_saldoorcdotacao.php?o58_coddot='+chave
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_saldos',arq,'Saldo da dotação',true);
    $('Jandb_iframe_saldos').style.zIndex='1500000';
  }

  function js_retornoProcessarAutorizacoes (oAjax) {

    js_removeObj('msgbox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {

      var sListaAutori = '';
      var sVirgula     = "";
      var iAutIni      = '0';
      var iAutFim      = '0';
      oRetorno.itens.each(function(iAutori, id) {

        if (id == 0) {
          iAutIni = iAutori;
        }
        iAutFim       = iAutori;
        sListaAutori += sVirgula+" "+iAutori;
        sVirgula = ", ";
      });
      if (confirm("Foram geradas as autorizacoes "+sListaAutori+".\nclique [ok] para Deseja Visualiza-las.")) {

        var sUrl = 'emp2_emiteautori002.php?e54_autori_ini='+iAutIni+'&e54_autori_fim='+iAutFim;
        window.open(sUrl,'', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        location.href = 'ac04_geraautorizacao001.php';

      } else {
        location.href = 'ac04_geraautorizacao001.php';
      }
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }


  function js_visualizarAutorizacoes(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    if (oRetorno.status == '2') {

      alert(oRetorno.message.urlDecode());
      return false;
    }

    if ($('wndDotacoesItem')) {
      return false;
    }
    if ($('wndAutorizacoes')) {
      return false;
    }
    var iHeight = js_round((screen.availHeight/1.8), 0);
    var iWidth  = screen.availWidth/2;
    windowAutorizacaoItem = new windowAux('wndAutorizacoes',
      'Autorizações De Empenho',
      iWidth,
      iHeight
    );
    var sContent  = "<div>";
    sContent     += "<fieldset>";
    sContent     += "  <div id='cntgridAutorizacoes'>";
    sContent     += "  </div>";
    sContent     += "</fieldset>";
    sContent     += "<center>";
    sContent     += "<input type='button' id='btnSalvarAutorizacoes' value='Gerar Autorizações' onclick='js_processarAutorizacoes(true)'>";
    sContent     += "</center>";
    windowAutorizacaoItem.setContent(sContent);
    oMessageBoardAut = new DBMessageBoard('msgboard1',
      'Gerar Autorizacões ',
      'Prévia de autorizações que serão geradas conforme seleção de itens/dotações',
      $('windowwndAutorizacoes_content')
    );
    windowAutorizacaoItem.setShutDownFunction(function() {
      windowAutorizacaoItem.destroy();
    });

    oMessageBoardAut.show();
    oGridAutorizacoes              = new DBGrid('gridAutorizacoes');
    oGridAutorizacoes.nameInstance = 'oGridAutorizacoes';
    oGridAutorizacoes.setCellWidth(new Array('10%', '70%', '10%', '10%', "10%"));
    oGridAutorizacoes.setHeader(new Array("Codigo", "Item", "Qtde", "Valor Unit", "Valor Total"));
    oGridAutorizacoes.setCellAlign(new Array("center", "left", "right", "right", "right"));
    oGridAutorizacoes.aHeaders[0].lDisplayed=false;
    oGridAutorizacoes.show($('cntgridAutorizacoes'));
    oGridAutorizacoes.clearAll(true);
    var iLinha = 0;
    var iAut   = 1;
    for (oDot in oRetorno.itens) {

      with (oRetorno.itens[oDot]) {

        aLinha     = new Array();
        aLinha[0]  = '';
        aLinha[1]  = iAut+'ª Autorização - Dotação (<a href="#" ';
        aLinha[1] += "onclick='js_mostraSaldo("+dotacao+");return false'>"+dotacao+"</a>)";
        aLinha[2]  = '';
        aLinha[3]  = '';
        aLinha[4]  = '';
        oGridAutorizacoes.addRow(aLinha);
        oGridAutorizacoes.aRows[iLinha].sStyle ='background-color:#eeeee2;';
        oGridAutorizacoes.aRows[iLinha].aCells.each(function(oCell, id) {
          oCell.sStyle +=';border-right: 1px solid #eeeee2;';
        });
        oGridAutorizacoes.aRows[iLinha].aCells[1].sStyle  = 'border-right: 1px solid #eeeee2;1px solid #eeeee2;';
        oGridAutorizacoes.aRows[iLinha].aCells[1].sStyle += 'text-align:left;font-weight:bold';
        iLinha++;
        aItens.each(function(oItem, id) {

          if (id == aItens.length-1) {
            var sImg  = "<img src='imagens/tree/join2.gif'>";
          } else {
            var sImg   = "<img src='imagens/tree/joinbottom2.gif'>";
          }
          aLinha    = new Array();
          aLinha[0] = oItem.codigo;
          aLinha[1] = sImg+oItem.descricao.urlDecode();
          aLinha[2] = js_formatar(oItem.quantidade, "f");
          aLinha[3] = js_formatar(oItem.valorunitario, "f");
          aLinha[4] = js_formatar(oItem.valor, "f");
          oGridAutorizacoes.addRow(aLinha);
          iLinha++;
        });
        iAut++;
      }

    }

    windowAutorizacaoItem.show();
    oGridAutorizacoes.renderRows();
    oGridAutorizacoes.setNumRows(iAut - 1);
  }

  function js_processarAutorizacoes(lProcessar) {

    var aItens = oGridItens.getSelection("object");
    if (aItens.length == 0) {

      alert('Nenhum item Selecionado');
      return false;
    }

    var funcaoRetorno = js_retornoProcessarAutorizacoes;

    if (!lProcessar) {
      funcaoRetorno = js_visualizarAutorizacoes;
    }

    js_divCarregando('Aguarde, processando.....', 'msgbox');
    var oParam        = new Object();
    oParam.exec       = "processarAutorizacoes";
    oParam.lProcessar = lProcessar;
    oParam.aItens     = new Array();
    oParam.dados      = new Object();
    if (lProcessar) {

      oParam.dados.destino                 = encodeURIComponent(tagString( $F('e54_destin')));
      oParam.dados.tipolicitacao           = $F('e54_tipol');
      oParam.dados.tipocompra              = $F('e54_codcom');
      oParam.dados.licitacao               = $F('e54_numerl');
      oParam.dados.pagamento               = encodeURIComponent(tagString($F('e54_conpag')));
      oParam.dados.resumo                  = encodeURIComponent(tagString($F('e54_resumo')));
      oParam.dados.iCaracteristicaPeculiar = $F("iSequenciaCaracteristica");
      oParam.dados.tipoempenho             = $F('e54_codtipo');
    }

    for (var i = 0; i < aItens.length; i++) {

      with (aItens[i]) {

        var oItem        = new Object();
        var oDadosItem   = aItensPosicao[aCells[9].getValue()];
        oItem.codigo     = oDadosItem.codigo;
        oItem.quantidade = aCells[6].getValue();
        oItem.valor      = aCells[7].getValue();
        var nTotal       = oItem.valor;
        oItem.posicao    = iPosicaoAtual;
        /**
         * Validamos o total do item com as dotacoes.
         * caso o valor seja diferetntes , devemos cancelar a operação e avisar o usuário
         */
        var nValorDotacao = 0;

        oDadosItem.dotacoes.each(function(oDotacao, id) {

          nValorDotacao += oDotacao.valorexecutar;
        });

        oItem.valor   =  js_formatar(oItem.valor , 'f');
        nValorDotacao =  js_formatar(nValorDotacao, 'f' );
        nTotal        =  js_formatar(nTotal, 'f' );

        if (nTotal.getNumber() > nValorDotacao.getNumber()  ) {

          alert('Valor da (s) dotação(ões) diferente do valor do item.\nCorrija o valor das dotações.');
          js_removeObj('msgbox');
          return false;
        }
        oItem.valor    = js_strToFloat(oItem.valor);
        oItem.dotacoes = oDadosItem.dotacoes;
        oParam.aItens.push(oItem);
      }
    }
    var oAjax  = new Ajax.Request(sUrlRpc,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: funcaoRetorno
      }
    )
  }

  function js_buscarInformacoesAutorizacao() {

    js_divCarregando('Aguarde, pesquisando dados do acordo', 'msgbox');
    var oParam           = new Object();
    oParam.exec          = 'getDadosAcordo';
    oParam.iCodigoAcordo = oTxtCodigoAcordo.getValue();

    var oAjax  = new Ajax.Request(sUrlRpc,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoBuscarInformacoesAutorizacao
      });

  }

  function js_retornoBuscarInformacoesAutorizacao(oAjax) {

    js_removeObj('msgbox');
    var oRetorno = eval("("+oAjax.responseText+")");
    var sMensagem = oRetorno.message.urlDecode();

    if ( oRetorno.status > 1 ) {

      alert(sMensagem);
      return false;
    }

    $('e54_resumo').value = oRetorno.sResumoAcordo.urlDecode();
    setInformacoesAutorizacao();
  }

  function setInformacoesAutorizacao() {

    if ($('wndDadosAutorizacoes')) {
      windowDadosAutorizacao.show();
    } else {

      var iWidth  = screen.availWidth/2;
      var iHeight = js_round( screen.availHeight/1.8, 0);
      windowDadosAutorizacao = new windowAux('wndDadosAutorizacoes',
        'Dados da(s) Autorização(ões) de Empenho',
        iWidth,
        iHeight
      );
      windowDadosAutorizacao.setObjectForContent($('frmDadosAutorizacao'));
      oMessageBoardDadosAut = new DBMessageBoard('msgboardDados',
        'Gerar Autorizacões ',
        'Informe dos dados complementares da Autorização',
        $('frmDadosAutorizacao')
      );
      windowDadosAutorizacao.show();
      windowDadosAutorizacao.setShutDownFunction(function() {
        windowDadosAutorizacao.hide();
      });
    }
  }

  js_main();
  $('e54_resumo').style.width='100%';
</script>
<?php
db_menu();
