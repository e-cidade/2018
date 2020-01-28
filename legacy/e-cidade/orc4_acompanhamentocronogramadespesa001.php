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

/**
 *
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.7 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
unset($_SESSION["cronogramabases"]);
unset($_SESSION["cronogramabasestotal"]);
unset($_SESSION["cronogramabasespages"]);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, strings.js, prototype.js, widgets/windowAux.widget.js, widgets/messageboard.widget.js");
  db_app::load("datagrid.widget.js, AjaxRequest.js, widgets/DBHint.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
  <style>
    .incorreto {
      background-color: #CD4A4A;
      color:white
    }
    .incorreto input {
      color:white
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <form name="form1" method="post">
    <table>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Acompanhamento da Despesa do Cronograma de Desembolso</b>
            </Legend>
            <table>
              <tr>
                <td>
                  <?
                  db_ancora("<b>Perspectiva:</b>","js_pesquisao125_cronogramaperspectiva(true);",$db_opcao);
                  ?>
                </td>
                <td>
                  <?
                  db_input('o124_sequencial',10,$Io124_sequencial,true,'text',
                    $db_opcao," onchange='js_pesquisao125_cronogramaperspectiva(false);'");
                  db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'')
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Ano:</b>
                </td>
                <td>
                  <?
                  db_input('ano',10,0,true,'text',3,'');

                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center">
          <input type="button" value='Edição' id='visualizabase' onclick="js_abreBases()">
        </td>
      </tr>
    </table>
  </form>
</center>
<div style="position:absolute; top:0px; display:none;" id='modal'>
</div>
<div id='divWndDadosBase' style='display: none'>
  <fieldset>
    <legend>
      <b>Filtros</b>
    </legend>
    <table>
      <tr>
        <td>
          <b>Nível:</b>
        </td>
        <td>
          <?
          $aNiveis = array(
            1 => "Orgão",
            2 => "Unidade",
            3 => "Função",
            4 => "Subfunção",
            5 => "Programa",
            6 => "Projeto/Atividade",
            7 => "Elemento",
            8 => "Recurso",
          );
          $nivel = 8;
          /* [Extensao] Cronograma de Desembolso */
          db_select("nivel", $aNiveis, true, 1, null);
          ?>
        </td>
        <td>
          <input type='button' value='Pesquisar' onclick="js_getDadosBase(1, true)">
        </td>
    </table>
  </fieldset>
  <fieldset>
    <table cellpadding="0"  id='tbpai' style='border:2px inset white;background: white' cellspacing="0" width="">
      <tr>
        <td width="20%" ID='LABEL' border=0 valign="top">
          <table id='table'  border=0  cellpadding="0" style="table-layout: fixed; width: 300px" cellspacing="0">
            <thead>
            <tr>
              <th width="200"  class='table_header' id='teste0' style='text-align:right;border-right:0px' colspan="1">
                Despesa
              </th>
              <th width="100"  style='text-align:left;' class='table_header' id='teste0' colspan="1">
                &nbsp;
              </th>
            </tr>
            <tr>
              <th class='table_header' style='width:200px' id='HeaderNivel' nowrap="nowrap">
                Descrição
              </th>
              <th class='table_header' style='width:100px' id='teste2s' nowrap="nowrap">
                Total Reestimado
              </th>
            </tr>
            </thead>
            <tbody id='tbodyDescr' style="height: 400px; overflow: hidden; overflow-x:hidden">
            <tr style='height:auto'>
              <td>&nbsp;</td>
            </tr>
            </tbody>
          </table>
        </td>
        <td  valign="top" width="100%">
          <div style='overflow: scroll; overflow-y:hidden; border:1px solid width:100%' id='tableRol'>
            <table border=0 cellpadding="0" cellspacing="0" width="3000">
              <tr>
                <td class='table_header' style="width: 250px"  colspan="2">Jan</td>
                <td class='table_header' style="width: 250px"  colspan="2">Fev</td>
                <td class='table_header' style="width: 250px"  colspan="2">Mar</td>
                <td class='table_header' style="width: 250px"  colspan="2">Abr</td>
                <td class='table_header' style="width: 250px"  colspan="2">Mai</td>
                <td class='table_header' style="width: 250px"  colspan="2">Jun</td>
                <td class='table_header' style="width: 250px"  colspan="2">Jul</td>
                <td class='table_header' style="width: 250px"  colspan="2">Ago</td>
                <td class='table_header' style="width: 250px" colspan="2">Set</td>
                <td class='table_header' style="width: 250px" colspan="2">Out</td>
                <td class='table_header' style="width: 250px" colspan="2">Nov</td>
                <td class='table_header' style="width: 250px" colspan="2">Dez</td>
                <td class='table_header' style="width: 17px" width="17px">&nbsp;</td>
              </tr>
              <tr>
                <?
                for ($i = 1; $i <=12; $i++) {
                  echo " <td class='table_header' style='width:50px'><b>%</b></td> ";
                  echo " <td class='table_header' style='width:200px'>Valor Reestimado</td> ";
                }
                ?>
              </tr>
              <tbody style="height: 400px; overflow: scroll; overflow-x:hidden" id='tBodyFields' onscroll="scrollTable(event)">
              </tbody>
            </table>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="3" style='text-align:left; padding: 0px' class='table_footer' valign="top">
          <input type='button' value='Início'   id='btninicio'>
          <input type='button' value='Anterior' id='btnanterior'>
         <span style='font-weight: bold; border:1px  solid #999999;padding:1px; background-color:white';>
           <input size='1'  type='text'
                  style='border:0px;height:16px;text-align:right;font-weight: bold;'
                  readonly value='' id='paginaAtiva'>/
           <input size='1'  type='text' style='border:0px;height:16px;text-align:left;font-weight: bold;'
                  readonly value='' id='totalDePaginas'>
         </span>
          <input type='button' value='Próximo'  id='btnproximo'>
          <input type='button' value='Último'   id='btnultimo'>
        </td>
      </tr>
    </table>
  </fieldset>
  <center>
    <input type="button" value="Salvar" id='salvar' onclick="js_salvarAcompanhamento()">
  </center>
</div>
<div id='console'></div>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            padding:3px;
            background-color: #FFFFCC;z-index:10000;
            display:none;' id='ajudaItem'>

</div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


  var windowBaseCalculo = '';
  lNecessitaSalvar = false;
  function js_pesquisao125_cronogramaperspectiva(mostra) {

    if(mostra==true){
      js_OpenJanelaIframe('top.corpo',
        'db_iframe_cronogramaperspectiva',
        'func_cronogramaperspectiva.php?tipo=2&funcao_js='+
        'parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
        'Perspectivas do Cronograma',true);
    }else{
      if(document.form1.o124_sequencial.value != ''){
        js_OpenJanelaIframe('top.corpo',
          'db_iframe_cronogramaperspectiva',
          'func_cronogramaperspectiva.php?tipo=2&pesquisa_chave='+
          document.form1.o124_sequencial.value+
          '&funcao_js=parent.js_mostracronogramaperspectiva',
          'Perspectivas do Cronograma',
          false);
      }else{

        document.form1.o124_descricao.value = '';
        document.form1.ano.value             = ''

      }
    }
  }

  function js_mostracronogramaperspectiva(chave,erro, ano){
    document.form1.o124_descricao.value = chave;
    document.form1.ano.value = ano;
    if(erro==true){
      document.form1.o124_sequencial.focus();
      document.form1.o124_sequencial.value = '';
      document.form1.ano.value             = '';
    }
  }

  function js_mostracronogramaperspectiva1(chave1,chave2,chave3) {

    document.form1.o124_sequencial.value = chave1;
    document.form1.o124_descricao.value  = chave2;
    document.form1.ano.value             = chave3;
    db_iframe_cronogramaperspectiva.hide();
  }

  var iSizeTable = $('table').getWidth();
  iSize = ((document.body.getWidth()-450)-iSizeTable);
  $('tableRol').style.width = new String(iSize)+"px";
  function scrollTable(event) {

    outroscrollTop = event.target.scrollTop;
    $('tbodyDescr').scrollTop = outroscrollTop

  };

  var sNomeJanela = "Acompanhamento das Despesas";
  function js_abreBases() {

    sNomeJanela = "Acompanhamento das Despesas";
    js_getDadosBase(1, true);
    lNecessitaSalvar = false;
  }

  function js_createJanelaBase() {

    var iSizeTable = $('table').getWidth();
    var iSize = ((document.body.getWidth() - 350) - iSizeTable);
    $('tableRol').style.width = new String(iSize) + "px";
    $('tbpai').style.width = document.body.getWidth() - 50;
    if (windowBaseCalculo == '') {

      windowBaseCalculo = new windowAux('wndBasesCalculo', sNomeJanela, 0, 0);
      windowBaseCalculo.setObjectForContent($('divWndDadosBase'));
      windowBaseCalculo.allowCloseWithEsc(false);
      windowBaseCalculo.setShutDownFunction(function () {

        if (lNecessitaSalvar) {

          if (!confirm('há Informações não Salvas.\nClique OK para sair sem Salvar.')) {
            return false;
          }
        }
        js_deveSalvar(false);
        windowBaseCalculo.hide();
        $('modal').style.display = 'none';
      });
      $('tbodyDescr').style.height = new String((document.body.scrollHeight / 1.8)) + "px";
      $('tBodyFields').style.height = new String((document.body.scrollHeight / 1.8)) + "px";
      $('divWndDadosBase').style.display = '';
    }
  }

  function js_getDadosBase(iPagina, lClearSession) {


    if (lNecessitaSalvar && lClearSession) {


      var sMensagem = 'Existem edições não salvas.\n';
      sMensagem    += 'Para continuar com a operação clique em [Ok]. As Edições existentes serão perdidas.';
      sMensagem    += 'Para Salvar as edições Realizadas, clique em [Cancelar] e salve suas edições.';
      if (!confirm(sMensagem)) {
        return false;
      }
      js_deveSalvar(false);
    }

    aInformacaoDespesa = [];
    if (iPagina == null) {
      iPagina  = 1;
    }
    if ($F('o124_sequencial') == "") {

      alert('Informe a perspectiva!');
      $('o124_sequencial').focus();
      return false;

    }
    if (lClearSession == null) {
      lClearSession = false;
    }


    $('HeaderNivel').innerHTML = $('nivel').options[$('nivel').selectedIndex].innerHTML;
    iAgrupa                    = 1;
    var oParametros            = new Object();
    oParametros.exec           = "getDados";
    oParametros.iPagina        = iPagina;
    oParametros.iRecurso       = "";
    oParametros.sEstrutural    = "";
    oParametros.lClearSession  = lClearSession;
    oParametros.iTipo          = 3;
    oParametros.iAgrupa        = $F('nivel');
    oParametros.iFetch         = iPagina==1?0:iPagina*100;
    oParametros.iPerspectiva   = $F('o124_sequencial');
    js_divCarregando("Aguarde, Pesquisando Metas.","msgBox");
    var oAjax = new Ajax.Request(
      'orc4_cronogramafinanceiro.RPC.php',
      {
        method    : 'post',
        parameters: 'json='+Object.toJSON(oParametros),
        onComplete: js_retornoGetBase
      }
    );

  }

  function js_retornoGetBase(oResponse) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {

      if (oRetorno.perspectiva_bloqueada) {
        $('salvar').style.display = "none";
      }
      /**
       * Prenchemos os dados da base
       */
      var sDescricao = "";
      var sCampos    = "";
      for (var i = 0; i < oRetorno.itens.length; i++) {

        with (oRetorno.itens[i]) {

          sDescricaoDespesa = descricao.urlDecode();
          if (codigo != "") {
            sDescricaoDespesa = codigo+" - "+descricao.urlDecode();
          }
          if ($F('nivel')== 2) {
            sDescricaoDespesa = o58_orgao+"."+codigo+" - "+descricao.urlDecode();
          }
          var sClassName = 'normal';
          aMetas.dados.each(function(aItem, id) {

            if(aItem.valor < 0) {
              sClassName = "incorreto";
            }
          });
          sDescricao += "<tr style='height:1px' class='"+sClassName+"' id='descricaodespesa"+codigo+"'>";
          sDescricao += "<td class='linhagrid' nowrap style='height:20px;text-align:left;'>";
          sDescricao += "<div style='overflow:hidden;with:200px;padding:1px;'>";
          sDescricao += "<span onmouseover='js_setAjuda(this.innerHTML,true)' onmouseout='js_setAjuda(\"\",false)'>";
          sDescricao += sDescricaoDespesa+"</span></div></td>";
          sDescricao += "<td class='linhagrid' style='width:100px;height:20px;text-align:right;font-weight:bold;padding:0px'>";
          sDescricao += js_formatar(valororcado,"f");
          sDescricao += "</td>";
          sDescricao += "</tr>";
          sCampos    += "<tr style='height:1px' class='"+sClassName+"'  id='valoresdespesa"+codigo+"'>";

          aMetas.dados.each(function(aItem, id) {

            var sInputDisabled   = oRetorno.perspectiva_bloqueada ? " readonly " : "";
            var sColorTdDisabled = oRetorno.perspectiva_bloqueada ? "color:black" : '';
            var lbloquearDigitacao = oRetorno.perspectiva_bloqueada;

            sCampos += "<td class='linhagrid' ";
            sCampos += "style='height:20px;text-align:right;padding:1px;"+sColorTdDisabled+"'";
            sCampos += " id='perc_"+codigo+"_"+aItem.mes+"'>";
            sCampos += js_formatar(aItem.percentual, 'f')+"</td>";


            sCampos += "<td class='linhagrid' style='text-align:right'>";
            sCampos += "<input type='text' style='height:99%;width:100%;";
            sCampos += "border:1px solid transparent;text-align:right;";
            sCampos += "background: transparent;"+sColorTdDisabled+"'";
            sCampos += "oninput=\"js_ValidaCampos(this, 4,'Valor Reestimado','f','f',event);\"";
            sCampos += " id='valor_"+codigo+"_"+aItem.mes+"'" + sInputDisabled +" codigodespesa='"+codigo+"'";
            sCampos += " onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
            sCampos += " readonly value='"+js_formatar(aItem.valor,"f")+"' onkeyDown='js_verifica(this,event)'";
            sCampos += " onfocus='js_liberaDigitacao(this, "+lbloquearDigitacao+");getInformacaoDespesa("+index+", "+aItem.mes+", this);' onblur='js_bloqueiaDigitacao(this);";
            sCampos += "js_alterarValorMes(["+aItem.sequencial+"],["+index+"],"+aItem.mes+",this.value)'></td>";

          })
          sCampos    += "</tr>";
        }
      }
      sDescricao += "<tr style='height:auto'><td>&nbsp</td>";
      sCampos    += "<tr style='height:auto'><td>&nbsp</td>";
      $('tbodyDescr').innerHTML   = sDescricao;
      $('tBodyFields').innerHTML  = sCampos;

      if (!$('wndBasesCalculo')) {
        js_createJanelaBase();
      }

      $('modal').style.width             = "99%";
      $('modal').style.height            = "25px";
      $('modal').style.display           = '';
      $('modal').style.backgroundColor   = '';
      $('modal').style.zIndex            = '10000000';
      windowBaseCalculo.show(25,5);
      $('divWndDadosBase').style.display='';
      $('btninicio').disabled   = false;
      $('btnanterior').disabled = false;
      $('btnproximo').disabled  = false;
      $('btnultimo').disabled   = false;
      if (oRetorno.pagina == 1) {

        $('btninicio').disabled   = true;
        $('btnanterior').disabled = true;
        $('btnproximo').disabled  = false;
        $('btnultimo').disabled   = false;

      } else if (oRetorno.pagina == oRetorno.totalPaginas) {
        $('btninicio').disabled  = false;
        $('btnanterior').disabled = false;
        $('btnproximo').disabled = true;
        $('btnultimo').disabled  = true;

      } else {

        $('btninicio').disabled   = false;
        $('btnanterior').disabled = false;
        $('btnproximo').disabled  = false;
        $('btnultimo').disabled   = false;
      }

      if (oRetorno.totalPaginas == 1 || oRetorno.totalPaginas == 1) {

        $('btninicio').disabled   = true;
        $('btnanterior').disabled = true;
        $('btnproximo').disabled  = true;
        $('btnultimo').disabled   = true;

      }
      $('btninicio').onclick   = function(){js_getDadosBase(1)};
      $('btnanterior').onclick = function(){js_getDadosBase(oRetorno.pagina-1)};
      $('btnproximo').onclick  = function(){js_getDadosBase(oRetorno.pagina+1)};
      $('btnultimo').onclick   = function(){js_getDadosBase(oRetorno.totalPaginas)};

      $('paginaAtiva').value    =  oRetorno.pagina;
      $('totalDePaginas').value =  oRetorno.totalPaginas;

    } else {
      alert(oRetorno.message.urlDecode());
    }
  }



  function js_pesquisac62_codrec(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo',
        'db_iframe_orctiporec',
        'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
        'Pesquisar Recursos',
        true,
        22,
        0,
        document.width-12,
        document.body.scrollHeight-30);
    } else {

      if($('o15_codigo').value != ''){
        js_OpenJanelaIframe('top.corpo',
          'db_iframe_orctiporec',
          'func_orctiporec.php?pesquisa_chave='+$('o15_codigo').value+
          '&funcao_js=parent.js_mostraorctiporec',
          'Pesquisar Recursos',
          false,
          22,
          0,
          document.width-12,
          document.body.scrollHeight-30);
      }else{
        $('o15_descr').value = '';
      }
    }
  }

  /**
   * Libera  o input passado como parametro para a digitacao.
   * é Retirado a mascara do valor e liberado para Edição
   * é Colocado a Variavel nValorObjeto no escopo GLOBAL
   */
  function js_liberaDigitacao(object, lbloquearDigitacao) {

    nValorObjeto        = object.value;

    if (lbloquearDigitacao) {
      return;
    }
    object.value        = js_strToFloat(object.value).valueOf();
    object.style.border = '1px solid black';
    object.readOnly     = false;
    object.style.fontWeight = "bold";
    object.select();
    js_marcaDespesa(object.getAttribute("codigodespesa"), true);

  }

  /**
   * bloqueia  o input passado como parametro para a digitacao.
   * É colocado  a mascara do valor e bloqueado para Edição
   */
  function js_bloqueiaDigitacao(object) {

    if (object.readOnly) {
      return;
    }
    object.readOnly         = true;
    object.style.border     ='0px';
    object.style.fontWeight = "normal";
    object.value            = js_formatar(object.value,'f');
    js_marcaDespesa(object.getAttribute("codigodespesa"), false);


  }

  /**
   * Verifica se  o usuário cancelou a digitação dos valores.
   * Caso foi cancelado, voltamos ao valor do objeto, e
   * bloqueamos a digitação
   */
  function js_verifica(object,event) {

    var teclaPressionada = event.which;
    if (teclaPressionada == 27) {
      object.value = nValorObjeto;
      js_bloqueiaDigitacao(object);
      event.preventDefault();
    }
  }

  function js_alterarValorMes(iBase, iIndiceReceita, iIndiceMes, nValor) {

    nValor = js_strToFloat(nValor).valueOf();
    if (nValor == js_strToFloat(nValorObjeto)) {
      return ;
    }
    js_deveSalvar(true);
    var oParametros                = new Object();
    oParametros.exec               = "alterarValorMes";
    oParametros.iPerspectiva       = $F('o124_sequencial');
    oParametros.iIndiceReceita     = iIndiceReceita;
    oParametros.iSender            = iIndiceReceita[0];
    oParametros.iIndiceMes         = iIndiceMes;
    oParametros.iCodigoBaseCalculo = iBase;
    oParametros.iTipo              = 3;
    oParametros.valor              = nValor;
    js_divCarregando("Aguarde, Pesquisando bases.","msgBox");

    var oAjax = new Ajax.Request(
      'orc4_acompanhamentocronograma.RPC.php',
      {
        method    : 'post',
        parameters: 'json='+Object.toJSON(oParametros),
        onComplete: js_retornoAlterarMes
      }
    );


  }

  function js_retornoAlterarMes (oResponse) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 2) {

      $("valor_"+oRetorno.iCodDesp+"_"+oRetorno.iMes).value = nValorObjeto;
      $("valor_"+oRetorno.iCodDesp+"_"+oRetorno.iMes).focus();
      alert(oRetorno.message.urlDecode());
      return false;
    }

    oRetorno.aPercentuais.each(function(nPercentual, iIndice) {
      $("perc_"+oRetorno.iCodDesp+"_"+(iIndice + 1)).innerHTML = js_formatar(nPercentual, "f");
    });
  }

  getElementsByClass = function ( searchClass, domNode, tagName) {

    if (domNode == null) {
      domNode = document;
    }

    if (tagName == null) {
      tagName = '*';
    }

    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
    for (i=0,j=0; i<tags.length; i++) {

      var test = " " + tags[i].className + " ";
      if (test.indexOf(tcl) != -1) {
        el[j++] = tags[i];
      }
    }
    return el;
  }
  /**
   * Controla se as Bases de calculos foram salvos
   */

  function js_deveSalvar(salvar) {

    lNecessitaSalvar = salvar;
    windowBaseCalculo.setTitle(sNomeJanela);
    $('salvar').disabled = true;
    if (lNecessitaSalvar) {

      $('salvar').disabled = false;
      windowBaseCalculo.setTitle(sNomeJanela + "[*]");
    }
  }

  $('salvar').disabled = true;

  function js_setAjuda(sTexto,lShow) {

    if (lShow) {

      el =  $('tbpai');
      var x = 0;
      var y = el.offsetHeight;
      while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

        x += el.offsetLeft;
        y += el.offsetTop;
        el = el.offsetParent;

      }
      x += el.offsetLeft;
      y += el.offsetTop;
      $('ajudaItem').innerHTML     = sTexto;
      $('ajudaItem').style.display = '';
      $('ajudaItem').style.top     = y+10;
      $('ajudaItem').style.left    = x;

    } else {
      $('ajudaItem').style.display = 'none';
    }
  }

  var sOldClassName = '';
  function js_marcaDespesa(iCodDesp, lMarcar) {

    if (lMarcar) {

      sOldClassName = $('descricaodespesa'+iCodDesp).className;
      sClassName = "marcado";

    } else {

      if (sOldClassName == '') {
        sOldClassName = "normal";
      }
      var sClassName    = sOldClassName;
    }

    $('descricaodespesa'+iCodDesp).className = sClassName;
    $('valoresdespesa'+iCodDesp).className   = sClassName;

  }

  function js_salvarAcompanhamento() {

    js_divCarregando("Aguarde, Salvando acompanhamento da despesa.","msgBox");
    var oParametros    = new Object();
    oParametros.iTipo  = 3;
    oParametros.exec   = "salvarReceita";
    var oAjax = new Ajax.Request(
      'orc4_cronogramafinanceiro.RPC.php',
      {
        method    : 'post',
        parameters: 'json='+Object.toJSON(oParametros),
        onComplete: js_retornoSalvarAcompanhamento
      }
    );

  }

  function js_retornoSalvarAcompanhamento(oResponse) {

    js_removeObj("msgBox");
    js_deveSalvar(false);
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {
      alert('Dados Salvos com sucesso!');
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }

  /**
   * Carrega as informações da Despesa
   */
  function getInformacaoDespesa(iIndice, iMes, oElemento) {

    var sIndice = iIndice+"#"+iMes;
    if (aInformacaoDespesa[sIndice] == null || aInformacaoDespesa[sIndice] == undefined) {

      var oParametro = {
        exec: "getInformacoesDespesa",
        iIndice: iIndice,
        iMes: iMes,
        iAno: $F('ano'),
        iNivel: $F('nivel')
      };

      new AjaxRequest(
        "orc4_acompanhamentocronograma.RPC.php",
        oParametro,
        function (oRetorno, lErro) {
          aInformacaoDespesa[sIndice] = oRetorno.valores;
        }
      ).asynchronous(false).execute();
    }

    abrirConsultaInformacao(sIndice, oElemento, iMes);
  }

  function abrirConsultaInformacao(sIndice, oElemento, iMes) {

    var oInformacaoDespesa = aInformacaoDespesa[sIndice];

    var nValorDiferenca = oInformacaoDespesa.nPrevistoMenosPago;
    if (iMes > 1 && !oElemento.readOnly) {
      nValorDiferenca = oInformacaoDespesa.nPago - oElemento.value;
    }
    var sMensagem = "<b>Previsto:</b> "+js_formatar(oInformacaoDespesa.nPrevisto, 'f');
    sMensagem    += "<br/><b>Comprometido:</b> "+js_formatar(oInformacaoDespesa.nCotaMensal, 'f');
    sMensagem    += "<br/><b>Realizado:</b> "+js_formatar(oInformacaoDespesa.nPago, 'f');
    sMensagem    += "<br/><b>Diferença:</b> "+js_formatar(nValorDiferenca, 'f');

    oDBHint = new DBHint('oDBHint');
    oDBHint.setText(sMensagem);
    oDBHint.setPosition("B", "L");
    oDBHint.setScrollElement($('tableRol'));
    oDBHint.make(oElemento);
    oDBHint.show(oElemento.parentNode);
  }
</script>