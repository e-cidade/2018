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

//MODULO: patrim
$oDataAtual = new DBDate(date("d/m/Y", db_getsession('DB_datausu')));
?>
<form class="container" name="form1" id='form1' method="post" action="">
  <fieldset>
    <legend>Baixa de Bens</legend>
    <table class="form-container">
      <tr>
        <td title="<?=@$Tt55_codbem?>">
          <?=@$Lt55_codbem?>
        </td>
        <td>
          <?
          db_input('t52_bem', 10, $It52_bem, true, 'text', 3, "");
          db_input('t52_descr', 40, $It52_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt55_baixa?>">
          <?=@$Lt55_baixa?>
        </td>
        <td>
          <?
          db_inputdata('t55_baixa', @$t55_baixa_dia, @$t55_baixa_mes, @$t55_baixa_ano, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt55_motivo?>">
          <?
          db_ancora(@$Lt55_motivo, "js_pesquisat55_motivo(true)", $db_opcao);
          ?>
        </td>
        <td>
          <?
          db_input('t55_motivo', 10, $It55_motivo, true, 'text', $db_opcao, "onchange='js_pesquisat55_motivo(false)'");
          db_input('t51_descr', 40, $It51_descr, true, 'text', 3, "js_pesquisat55_motivo(false)");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tt55_obs?>"colspan="2">
          <fieldset class="separator">
            <legend><?=$Lt55_obs?></legend>
            <?
            db_textarea("t55_obs", 5, 50, $It55_obs, true, "text", $db_opcao);
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":"excluir")?>"
         type="button"
         id="db_opcao"
         onclick="js_verificarBensVinculados()"
         value="<?=($db_opcao==1?"Baixar Bem":"Reativar Bem")?>" />
  <input type='button' value='Pesquisar' onclick="js_pesquisa()">
</form>

<script>
  iDbOpcao = <?=$db_opcao?>;
  function js_pesquisat55_motivo(mostra) {

    if(mostra==true) {
      js_OpenJanelaIframe('','db_iframe_bensmotbaixa',
        'func_bensmotbaixa.php?funcao_js=parent.js_mostramotivo1|t51_motivo|t51_descr','Pesquisa',true);
    }else{
      if(document.form1.t55_motivo.value != ''){
        js_OpenJanelaIframe('',
          'db_iframe_bensmotbaixa',
          'func_bensmotbaixa.php?pesquisa_chave='+document.form1.t55_motivo.value+
          '&funcao_js=parent.js_mostramotivo','Pesquisa',false);
      }else{
        document.form1.t51_descr.value = '';
      }
    }
  }
  function js_mostramotivo(chave,erro){
    document.form1.t51_descr.value = chave;
    if(erro==true){
      document.form1.t55_motivo.focus();
      document.form1.t55_motivo.value = '';
    }
  }
  function js_mostramotivo1(chave1,chave2){
    document.form1.t55_motivo.value = chave1;
    document.form1.t51_descr.value = chave2;
    db_iframe_bensmotbaixa.hide();
  }
  function js_pesquisa() {

    var sArquivoBuscaBem = 'func_bens.php';
    if (iDbOpcao == 2) {
      sArquivoBuscaBem = 'func_bensbaix.php';
    }

    var url = sArquivoBuscaBem+"?funcao_js=parent.js_preenchepesquisa|t52_bem";
    js_OpenJanelaIframe('','db_iframe_bens',url,'Pesquisar Bens para a Baixa',true);
  }

  function js_preenchepesquisa(t52_bem) {

    db_iframe_bens.hide();
    js_pesquisaBem(t52_bem);
  }

  /**
   * Função chamada ao iniciar
   */
  var sUrl = 'pat1_bensnovo.RPC.php';
  function js_pesquisaBem(iCodigoBem) {

    var oObject         = new Object();
    oObject.exec        = "buscaBem";
    oObject.dbOpcao     = iDbOpcao;
    oObject.iCodigoBem  = iCodigoBem;
    js_divCarregando(_M('patrimonial.patrimonio.db_frmbembaixa.buscando'),'msgBox');
    var objAjax   = new Ajax.Request (sUrl,{
        method:'post',
        asynchronous:false,
        parameters:'json='+Object.toJSON(oObject),
        onComplete:js_retornoBuscaBem
      }
    );
  }
  /**
   * Retorno do js_carregaDadosForm
   */
  function js_retornoBuscaBem(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oJson.responseText+")");

    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
      return false;
    }

    $("t52_bem").value   = oRetorno.dados.t52_bem;
    $("t52_descr").value = oRetorno.dados.t52_descr.urlDecode();
    if (oRetorno.dados.bembaixado) {

      $('t55_baixa').value  = oRetorno.dados.databaixa;
      $('t55_motivo').value = oRetorno.dados.motivo;
      $('t55_obs').value    = oRetorno.dados.observacao.urlDecode();
      js_pesquisat55_motivo(false);
    }

    var oDataBaixa = $('t55_baixa');
    var oButtonDataBaixa = $('dtjs_t55_baixa');
    oDataBaixa.value    = '<?php echo $oDataAtual->getDate(DBDate::DATA_PTBR); ?>';
    oDataBaixa.disabled = false;
    oDataBaixa.className = '';
    oButtonDataBaixa.disabled = false;
    if (oRetorno.lPossuiIntegracaoPatrimonial) {

      oDataBaixa.value    = '<?php echo $oDataAtual->getDate(DBDate::DATA_PTBR); ?>';
      oDataBaixa.disabled = true;
      oDataBaixa.className = 'readonly';
      oButtonDataBaixa.disabled = true;
    }
  }


  function js_retornoBaixaBem(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
      return false;
    }

    alert(_M('patrimonial.patrimonio.db_frmbembaixa.bem_baixado'));
    $('form1').reset();
    js_pesquisa();
  }

  function baixarBem() {

    var oParam         = new Object();
    oParam.exec        = "baixarBem";
    oParam.aBens       = new Array($F('t52_bem'));
    oParam.dtBaixa     = $F('t55_baixa');
    oParam.iMotivo     = $F('t55_motivo');
    oParam.sObservacao = encodeURIComponent(tagString($F('t55_obs')));

    if (!confirm(_M('patrimonial.patrimonio.db_frmbembaixa.confirma_baixa'))) {
      return false;
    }
    if (oParam.iCodigoBem == '') {

      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_bem'));
      js_pesquisa();
      return false;
    }

    if (oParam.iMotivo == '') {

      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_motivo'));
      js_pesquisat55_motivo(true);
      return false;
    }

    if (oParam.dtBaixa == '') {

      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_data'));
      return false;
    }

    js_divCarregando(_M('patrimonial.patrimonio.db_frmbembaixa.baixando_bem'), 'msgBox');
    var oAjax = new Ajax.Request(sUrl,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoBaixaBem
      });

  }
  /**
   * Reativa o bem
   */
  function js_reativarBem() {

    var oParam         = new Object();
    oParam.exec        = "reativarBem";
    oParam.iCodigoBem  = $F('t52_bem');
    oParam.sObservacao = $F('t55_obs');

    if (!confirm(_M('patrimonial.patrimonio.db_frmbembaixa.confirma_reativacao'))) {
      return false;
    }
    if (oParam.iCodigoBem == '') {

      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_bem_reativado'));
      js_pesquisa();
      return false;
    }
    js_divCarregando(_M('patrimonial.patrimonio.db_frmbembaixa.reativando_bem'), 'msgBox');
    var oAjax = new Ajax.Request(sUrl,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoReativacaoBem
      });

  }

  function js_retornoReativacaoBem(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval('('+oAjax.responseText+')');
    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
    } else {

      alert(_M('patrimonial.patrimonio.db_frmbembaixa.bem_reativado'));
      $('form1').reset();
    }
  }
  if (iDbOpcao == 2) {

    $('db_opcao').onclick='';
    $('db_opcao').observe("click", js_reativarBem);
  }
  $("t55_obs").style.width='100%';
  js_pesquisa();

  /**
   * Verifica se existem outros bens vinculados a nota
   */
  function js_verificarBensVinculados() {

    var oParam        = new Object();
    oParam.exec       = "verificaVinculoBens";
    oParam.iCodigoBem = $F('t52_bem');
    js_divCarregando(_M('patrimonial.patrimonio.db_frmbembaixa.verificando_vinculos'), 'msgBox');
    var oAjax =  new Ajax.Request(sUrl,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoVinculoBens
      }
    );
  }

  function js_retornoVinculoBens (oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval('('+oAjax.responseText+')');

    if (oRetorno.aOutrosBensVinculados.length > 0) {

      var iWidth  = document.body.getWidth() / 2;
      var iHeight = 500;

      oWindowBensVinculados = new windowAux('wndBens', 'Bens Vinculados', iWidth, iHeight);
      oWindowBensVinculados.setShutDownFunction(function() {
        oWindowBensVinculados.destroy();
      });

      var iCodigoNota = oRetorno.oNota.iNumero.urlDecode();

      var oContent = "<div>";
      oContent    += "  <fieldset>";
      oContent    += "    <legend style='font-weight:bold'>";
      oContent    += "      Bens Vinculados a nota "+iCodigoNota;
      oContent    += "    </legend>";
      oContent    += "    <div id='ctnGridBens'>";
      oContent    += "    </div>";
      oContent    += "  </fieldset>";
      oContent    += "  <center>";
      oContent    += "  <input type='button' name='btnProcessarBaixaItens' value='Baixar Bens'";
      oContent    += "   onclick='js_baixarBensNota()' id='btnProcessarBaixaItens'/>";
      oContent    += "  </center>";
      oContent    += "</div>";
      oWindowBensVinculados.setContent(oContent);
      var sMensagemUsuario  = "Os bens abaixo listados estão vinculados a Nota Fiscal "+iCodigoNota;
      sMensagemUsuario     += ", que consta como não liquidada. Para realizar a baixa do bem selecionado, ";
      sMensagemUsuario     += "Deverá ser baixado todos os bens vinculados a nota.";
      oMessageBoard = new DBMessageBoard("DBmessageBoardBens",
        "Bens Vinculados a nota "+iCodigoNota+" do Empenho"+oRetorno.oNota.sEmpenho,
        sMensagemUsuario,
        oWindowBensVinculados.getContentContainer()
      );
      oMessageBoard.show();
      oWindowBensVinculados.show();

      oDBGridBens              = new DBGrid('GridBens');
      oDBGridBens.nameInstance = 'oDBGridBens';
      oDBGridBens.setCellWidth(new Array("10%", '70%', '20%'));
      oDBGridBens.setHeader(new Array("Código", "Bem", "Data Aquisição"));
      oDBGridBens.setHeight(iHeight/1.7);
      oDBGridBens.show($('ctnGridBens'));
      oDBGridBens.clearAll(true);
      oRetorno.aOutrosBensVinculados.each(function(oBem, iSeq) {

        var aLinha = new Array();
        aLinha[0]  = oBem.iCodigo;
        aLinha[1]  = oBem.sDescricao.urlDecode();
        aLinha[2]  = js_formatar(oBem.dtAquisicao, 'd');
        oDBGridBens.addRow(aLinha);
      });
      oDBGridBens.renderRows();
    } else {
      baixarBem();
    }
  }

  function js_baixarBensNota() {

    var oParam         = new Object();
    oParam.exec        = "baixarBem";
    oParam.dtBaixa     = $F('t55_baixa');
    oParam.iMotivo     = $F('t55_motivo');
    oParam.sObservacao = encodeURIComponent(tagString($F('t55_obs')));
    oParam.aBens       = new Array();
    var aItens = oDBGridBens.aRows;
    aItens.each(function(oLinha, iSeq) {
      oParam.aBens.push(oLinha.aCells[0].getValue());
    });
    if (!confirm(_M('patrimonial.patrimonio.db_frmbembaixa.confirma_baixa'))) {

      oWindowBensVinculados.destroy();
      return false;
    }

    if (oParam.iMotivo == '') {

      oWindowBensVinculados.destroy();
      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_motivo'));
      js_pesquisat55_motivo(true);
      return false;
    }

    if (oParam.dtBaixa == '') {

      oWindowBensVinculados.destroy();
      alert(_M('patrimonial.patrimonio.db_frmbembaixa.informe_data'));
      return false;
    }

    js_divCarregando(_M('patrimonial.patrimonio.db_frmbembaixa.baixando_bem'), 'msgBox');
    var oAjax = new Ajax.Request(sUrl,
      {method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete: js_retornoBaixaBem
      });

  }
</script>
<script>

  $("t52_bem").addClassName("field-size2");
  $("t52_descr").addClassName("field-size7");
  $("t55_baixa").addClassName("field-size2");
  $("t55_motivo").addClassName("field-size2");
  $("t51_descr").addClassName("field-size7");

</script>