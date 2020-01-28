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
require_once(modification("libs/db_app.utils.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));


db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$oGet = db_utils::postMemory($_GET);

try {

  $oOrdemCompra = new OrdemDeCompra($oGet->m51_codordem);
  if ($oOrdemCompra->getEmpenhoFinanceiro()->getInstituicao()->getCodigo() != db_getsession('DB_instit')) {
    throw new Exception("Esta ordem de compra pertence a outro departamento. Procedimento abortado.");
  }
} catch (Exception $e) {

  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
  exit;
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/infoLancamentoContabil.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">

  <style type="text/css">
    .negrito {
      font-weight: bolder;
    }
    .dados{
      background-color: #FFF;
      padding: 1px;
      padding-left: 3px;
    }
  </style>
</head>
<body>
  <fieldset>
    <legend class='negrito'>Dados da Ordem de Compra</legend>

    <table width="100%" border="0" >

      <tr>
        <td class='negrito' width="150" >
          C�digo:
        </td>
        <td class='dados' colspan="3">
          <label id='iCodigo'></label>
        </td>
        <td rowspan="7" id="tdObservacoes" width="800" valign="top">
        </td>
      </tr>

      <tr>
        <td class='negrito'>
          Fornecedor:
        </td>
        <td class='dados' colspan="3">
          <label id='sFornecedor'></label>
        </td>
      </tr>

      <tr>
        <td class='negrito' width="150">
          Data de Emiss�o:
        </td>
        <td class='dados' nowrap="nowrap" >
          <label id='dtEmissao'></label>
        </td>

        <td class='negrito'width="150">
          Data de Anula��o:
        </td>
        <td class='dados' >
          <label id='dtAnulacao'></label>
        </td>
      </tr>

       <tr>
        <td class='negrito' width="150">
          Departamento:
        </td>
        <td class='dados' >
          <label id='sDepartamento'></label>
        </td>

        <td class='negrito'width="150">
          Tipo de Compra:
        </td>
        <td class='dados' >
          <label id='sTipoCompra'></label>
        </td>
      </tr>

      <tr>
        <td class='negrito'>
          Total da Ordem:
        </td>
        <td class='dados' colspan="3">
          <label id='nTotalOrdem'></label>
        </td>
      </tr>

      <tr>
        <td class='negrito'>
          Valor Lan�ado:
        </td>
        <td class='dados' colspan="3">
          <label id='nValorLancado'></label>
        </td>
      </tr>

      <tr>
        <td class='negrito'>
          A Lan�ar:
        </td>
        <td class='dados' colspan="3">
          <label id='nValorLancar'></label>
        </td>
      </tr>

      <tr>
        <td class='negrito'>
          Valor Anulado:
        </td>
        <td class='dados' colspan="3">
          <label id='nValorAnulado'></label>
        </td>
      </tr>



      <tr id="trObservacaoInicial">
        <td class='negrito'>
          Observa��es:
        </td>
        <td class='dados' colspan="3">
          <textarea id="textAreaObs" style="resize:none;overflow:auto; width: 100%; height: 90px; border: none;" readonly="readonly">
          </textarea>
        </td>
      </tr>

    </table>
  </fieldset>


  <fieldset style='padding-left:0px'>
    <legend><b>Detalhamento</b></legend>
    <?php
    $oTabDetalhes = new verticalTab("detalhesOrdemCompra",300);

    $oTabDetalhes->add("itens", "Itens","com3_itemordemdecompra002.php?m51_codordem=".$oGet->m51_codordem);

    $oTabDetalhes->add("entradaestoque", "Movimenta��es no Estoque","com3_entradaestoque002.php?m51_codordem=".$oGet->m51_codordem);

    $oTabDetalhes->show();
    ?>
  </fieldset>
</body>
</html>


<script>

var oGet    = js_urlToObject();
var sUrlRPC = 'com4_ordemdecompra001.RPC.php';
function js_getDadosOrdem() {

  var oParametros              = new Object();
      oParametros.exec         = 'getDadosOrdem';
      oParametros.iOrdemCompra = oGet.m51_codordem;

  var msgDiv = "Pesquisando dados da ordem de compra, aguarde...";

  js_divCarregando(msgDiv,'msgBox');

  new Ajax.Request(sUrlRPC,
                  {method: "post",
                   parameters:'json='+Object.toJSON(oParametros),
                   onComplete: js_retornoDadosOrdem
                  });
}

function js_retornoDadosOrdem(oAjax){

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");

  var iTipoCompra = oRetorno.oDadosOrdem.iTipoCompra;
  var sTipoCompra = "";

  switch (iTipoCompra) {

    case "1" :
      sTipoCompra = "Normal";
    break;

    case "2" :
      sTipoCompra = "Virtual";
    break;
  }

  $("iCodigo").innerHTML       = oRetorno.oDadosOrdem.iCodigoOrdem;
  $("dtEmissao").innerHTML     = oRetorno.oDadosOrdem.dEmissao;
  $("sDepartamento").innerHTML = oRetorno.oDadosOrdem.iDepto + " - " + oRetorno.oDadosOrdem.sDepto.urlDecode();
  $("sFornecedor").innerHTML   = oRetorno.oDadosOrdem.iCgm   + " - " + oRetorno.oDadosOrdem.sCgm.urlDecode();
  $("dtAnulacao").innerHTML    = oRetorno.oDadosOrdem.dAnulacao;
  $("sTipoCompra").innerHTML   = sTipoCompra.urlDecode();
  $("nTotalOrdem").innerHTML   = oRetorno.oDadosOrdem.nTotalOrdem;
  $("nValorLancado").innerHTML = oRetorno.oDadosOrdem.nValorLancado;
  $("nValorLancar").innerHTML  = oRetorno.oDadosOrdem.nValorLancar;
  $("nValorAnulado").innerHTML = oRetorno.oDadosOrdem.nValorAnulado;
  $("textAreaObs").value       = oRetorno.oDadosOrdem.sObservacao.urlDecode();


}
js_getDadosOrdem();

/**
 * Fun��o criada para mover o campo observa��o de acordo com a
 * resolu��o do usu�rio.
 */
function js_verificarResolucaoUsuario() {

  var iClientWidth = new Number(document.body.clientWidth);

  if (iClientWidth > 800) {

    /**
     * Criamos um fieldset
     */
    var oFieldset          = document.createElement('fieldset');
    oFieldset.id           = 'fieldsetObservacao';
    oFieldset.style.width  = '97%';
    oFieldset.style.marginTop = '0';

    /**
     * Criamos a legenda e adicionamos ela ao fieldset
     */
    var oLegend       = document.createElement('legend');
    oLegend.id        = 'legendObservacao';
    oLegend.innerHTML = "<b>Observa��es</b>";
    oFieldset.appendChild(oLegend);

    /**
     * Setamos o estilo no textarea e adicionamos ele ao fieldset
     */
    $('textAreaObs').style.width  = '100%';
    $('textAreaObs').rows   = '7';
    oFieldset.appendChild($('textAreaObs'));
    $("tdObservacoes").appendChild(oFieldset);
    $("tdObservacoes").vAlign = 'top';

    /**
     * Removemos a TR que armazenava o campo observa��o inicialmente.
     */
    $('trObservacaoInicial').remove();
  }
}

function consultaLicitacao(iCodigoLicitacao) {

  if (iCodigoLicitacao == 0) {

    alert("Este processo n�o possui licita��o vinculada.");
    return false;
  }
  var sURLLicitacao = "lic3_licitacao002.php?l20_codigo="+iCodigoLicitacao
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_licitacao'+iCodigoLicitacao, sURLLicitacao, 'Consulta de Licita��o', true);
}

js_verificarResolucaoUsuario();
</script>