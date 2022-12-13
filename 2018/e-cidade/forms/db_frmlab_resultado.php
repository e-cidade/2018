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

//MODULO: Laboratório
$cllab_resultado->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("la21_i_requisicao");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
?>
<div class="container">
<fieldset>
  <legend>Digitação de Resultados</legend>
  <form name="form1" method="post" action="">
    <?php
    db_input( 'la52_i_codigo', 10, $Ila52_i_codigo, true, 'hidden', $db_opcao );
    ?>
    <fieldset class='separator'>
      <legend>Exames</legend>
      <table>
        <tr>
          <td title="<?=$Tla22_i_codigo?>">
            <label for="la22_i_codigo">
              <?php
                db_ancora( $Lla21_i_requisicao, "js_pesquisaRequisicao(true);", $db_opcao );
              ?>
            </label>
          </td>
          <td>
            <?php
              db_input( 'la22_i_codigo', 10, $Ila22_i_codigo, true, 'text', $db_opcao, " onchange='js_pesquisaRequisicao(false);'" );
              db_input( 'z01_v_nome'   , 50, $Iz01_v_nome,    true, 'text',         3, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </form>
  <div>
    <fieldset class='separator' style="width:300px; height:500px; float:left; display:block; text-align: left; overflow:hidden;">
      <legend>Exames da requisição</legend>
      <div rel="ignore-css" id="ctnGridExames" style='overflow:hidden;overflow-y:none;max-width:298px;height:100%'> </div>
    </fieldset>
    <div style="width:5px; float:left;"> &nbsp;</div>
    <div id="ctnGridAtributos"style="width:800px; float:left;"></div>
  <div>
</fieldset>
</div>
<script>

var oGet      =  js_urlToObject();
var oTreeView = new DBTreeView('Exames');
oTreeView.allowFind(true);
oTreeView.setFindOptions('matchedonly');
oTreeView.show($('ctnGridExames'));
oNoRaiz = oTreeView.addNode("0", "Setor");

var oLancamento = new LancamentoExameLaboratorio('oLancamento');
oLancamento.mostraCampoObservacao(true);
oLancamento.show($('ctnGridAtributos'));

/**
 * Executado ao salvar com sucesso os atributos do exame.
 * Marca o exame selecionado na treeView e move para o próximo exame do setor
 */
oLancamento.setCallbackSalvar(function(oRetorno) {

  if (oRetorno.status == 1) {

    var oNo = oUltimoExame.element.parentNode.nextSibling;
    oUltimoExame.element.lastChild.style.color = 'green';
    if (oNo != null) {
      oNo.firstChild.lastChild.click();
    }
  }
});

function js_pesquisaRequisicao( lMostra ) {

  var sUrl = "func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>";

	if (lMostra) {

    sUrl += '&funcao_js=parent.js_mostraRequisicao|la22_i_codigo|z01_v_nome';
	  js_OpenJanelaIframe( '', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisição ', true );
	} else if ( document.form1.la22_i_codigo.value != '' ) {

    sUrl += '&pesquisa_chave='+$F('la22_i_codigo');
    sUrl += '&funcao_js=parent.js_mostraRequisicao';
	  js_OpenJanelaIframe( '', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisição ', false );
  } else {

    $('la22_i_codigo').value = '';
    $('z01_v_nome').value    = '';
    resetaTreeView();
	}
}

function resetaTreeView() {

  if (oNoRaiz) {
    oNoRaiz.remove();
  }
  oNoRaiz = oTreeView.addNode("0", "Setor");

  if (oGridAtributosExame) {
    oLancamento.clear();
  }
}

function js_mostraRequisicao( chave, erro ) {

  if ( typeof arguments[1] == 'boolean' ) {

    $('z01_v_nome').value = arguments[0];
    if ( arguments[1] ) {

      $('la22_i_codigo').focus();
      $('la22_i_codigo').value = '';
      resetaTreeView();
      return false;
    }
    js_BuscaExames();
    return true;
  }

  $('la22_i_codigo').value = arguments[0];
  $('z01_v_nome').value = arguments[1];
  db_iframe_lab_requisicao.hide();
  js_BuscaExames();

  return true;
}


function js_BuscaExames() {

  var oParamentros = {exec: 'buscarExames', iRequisicao : $F('la22_i_codigo')};

  var oAjaxRequest = new AjaxRequest('lab4_requisicao.RPC.php', oParamentros, retornoExames);
  oAjaxRequest.setMessage('Buscando Exames...');
  oAjaxRequest.asynchronous(false);
  oAjaxRequest.execute();
}

/**
 * Monta a arvore de exames com os exames da requição selecionada
 */
function retornoExames (oRetorno, lErro) {

  if ( lErro ) {

    alert(oRetorno.sMessage.urlDecode());
    return;
  }
  /**
   * Ao pesquisar nova requisição, zera a treeView (remove os nós abaixo)
   */
  resetaTreeView();
  oRetorno.aExamesRequisicao.each( function (oSetor, iSetor) {

    var sIdSetor   = oSetor.iCodigo;
    var oNodeSetor = oTreeView.addNode(sIdSetor,
                                       oSetor.sNome.urlDecode(),
                                       '0',
                                       '',
                                       '',
                                       false,
                                       null,
                                       {'lProcessa':false}
                                      );

    oSetor.aExames.each(function (oExame, iIndexExame) {

      var oNode = oTreeView.addNode(oExame.iCodigo,
                                    oExame.sNome.urlDecode(),
                                    sIdSetor,
                                    '',
                                    '',
                                    true,
                                    function(oNo, Evento) {
                                      clicouExame(oNo, Evento);
                                    },
                                    {'lProcessa':true,'iCodigo':oExame.iCodigo,
                                     'sNome':oExame.sNome, lPinta : oExame.lDigitado
                                    }
                                   );

      if (oNode.lPinta) {
        oNode.element.lastChild.style.color = 'green';
      }
      oNodeSetor.expand(); // abre os nós filhos do setor
    });

  oNoRaiz.expand(null, true);
  });

}

/**
 * Sempre que clica sobe um exame, marca o exame como selecionado e envia o código do exame (item da requisição) para
 * o componente LancamentoExameLaboratorio
 */
var oUltimoExame = '';
function clicouExame (oNo, Evento) {

  if (oUltimoExame != '') {
    oUltimoExame.select(false);
  }
  oUltimoExame = oNo;
  oNo.select(true);
  oLancamento.setRequisicao(oNo.iCodigo);
}

if (oGet.la08_i_codigo) {

  js_BuscaExames();

  for (var i in oTreeView.aNodes ) {

    if ( oTreeView.aNodes[i].value == oGet.la47_i_requiitem) {

      oUltimoExame = oTreeView.aNodes[i];
      oTreeView.aNodes[i].select(true);
      oLancamento.setRequisicao(oGet.la47_i_requiitem);
    }
  }
}

</script>