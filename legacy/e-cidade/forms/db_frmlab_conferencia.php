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
$cllab_conferencia->rotulo->label();
$clrotulo = new rotulocampo ( );

//procedimentos
$clrotulo->label( "sd63_c_procedimento" );
$clrotulo->label( "sd63_c_nome" );
$clrotulo->label( "sd70_c_cid" );
$clrotulo->label( "sd70_c_nome" );
$clrotulo->label( "la52_diagnostico" );
$clrotulo->label( "la22_i_codigo" );
$clrotulo->label( "z01_v_nome" );

?>

<form name="form1" method="post" action="">
  <div class="container">
    <fieldset style="width: 800px" >
      <legend>Conferência de Exames</legend>
      <table class="form-container">
        <tr>
          <td nowrap="nowrap" title="<?=$Tla22_i_codigo?>" class="field-size3">
            <?php db_ancora( '<b>Requisição</b>', "js_pesquisaRequisicao(true);", "" );?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('la22_i_codigo', 10, $Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisaRequisicao(false);'" );
              db_input('z01_v_nome', 75, $Iz01_v_nome, true, 'text', 3, '' )
            ?>
          </td>
        </tr>
      </table>

    </fieldset>
  </div>

  <div class="subcontainer">

    <fieldset style="width: 1000px">

      <legend>Exames Realizados</legend>
      <div id="ctnGridExame" > </div>

    </fieldset>
    <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='lab4_confresult001.php';" />
  </div>
</form>
<script>
const MSG_FRMLABCONFERENCIA = 'saude.laboratorio.db_frmlab_conferencia.'

var oLaboratorio      = null;
var aExamesRequisicao = [];

var oGridExames          = new DBGrid("dbGridExames");
oGridExames.nameInstance = "oGridExames";
oGridExames.setCellWidth(["35%", "10%", "45%", "8%"]);
oGridExames.setCellAlign(["left", "center", "left"]);
oGridExames.setHeader(["Exame", "Procedimento", "CID", "Situação", "codigo_exame", "codigo_procedimento"]);
oGridExames.setHeight(200);
oGridExames.aHeaders[4].lDisplayed = false;
oGridExames.aHeaders[5].lDisplayed = false;
oGridExames.show($("ctnGridExame"));


( function () {

  if ( !Laboratorio.departamentoIsLaboratorio() && !Laboratorio.usuarioIsTecnicoLaboratorio() ) {
    alert( _M(MSG_FRMLABCONFERENCIA+"departamento_usuario_invalido") );
  } else {
    oLaboratorio = Laboratorio.getLaboratorioByDepartamento();
  }
})();


/**
 * Pesquisa a requisição
 */
function js_pesquisaRequisicao( lMostra ) {

  var sUrl  = "func_lab_requisicao.php?iLaboratorio=" +oLaboratorio.iLaboratorio;

  if ( lMostra ) {

    sUrl += "&funcao_js=parent.js_mostraRequisicao|la22_i_codigo|z01_v_nome";
    js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisições', true);
  } else if ( !lMostra && $F('la22_i_codigo') != '') {

    sUrl += "&pesquisa_chave=" + $F('la22_i_codigo') + "&funcao_js=parent.js_mostraRequisicao";
    js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisições', false);
  } else {
    $('z01_v_nome').value = '';
  }
}
/**
 * Mostra o retorno da pesquisa pela requisição
 */
function js_mostraRequisicao() {

  /**
   * Quando valor de arguments[1] for um boolean, significa que o código foi digitado
   */
  if ( typeof arguments[1] == "boolean" ) {

    $('z01_v_nome').value = arguments[0];
    if ( arguments[1] ) {
      $('la22_i_codigo').value = '';
    }

  } else {

    $('la22_i_codigo').value = arguments[0];
    $('z01_v_nome').value    = arguments[1];
    db_iframe_lab_requisicao.hide();
  }

  if ( $F('la22_i_codigo') != '' ) {
    js_buscaExames();
  }
}

/**
 * Busca os exames da requisição selecionada
 */
function js_buscaExames() {

  var oParametro      = {};
  oParametro.exec     = 'getExamesRequisicao';
  oParametro.iCodigo  = $F('la22_i_codigo');

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = js_retornoBuscaExames;
  oRequest.asynchronous = false;

  js_divCarregando( _M(MSG_FRMLABCONFERENCIA+"aguarde_busca_exames"), "msgBoxA");
  new Ajax.Request( 'lab4_conferencia.RPC.php' , oRequest );

}

function js_retornoBuscaExames( oAjax ) {

  js_removeObj("msgBoxA");
  oGridExames.clearAll(true);
  aExamesRequisicao = [];
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if ( parseInt(oRetorno.iStatus) == 2 ) {

    alert( _M(MSG_FRMLABCONFERENCIA + "erro_buscar_exames") );
    return;
  }
  if ( oRetorno.aExames.length == 0 ) {

    alert( _M(MSG_FRMLABCONFERENCIA + "requisicao_sem_exames") );
    return;
  }

  aExamesRequisicao = oRetorno.aExames;
  popularGrid();
};


function js_validaCampos() {

  if ( oGridExames.getSelection('array').length == 0) {

    alert(_M(MSG_FRMLABCONFERENCIA+"selecione_exame"));
    return false
  }
  return true;
}

var oResultadoExame = null;

function js_consultaResultados(iItemExame, iIndex) {

  oResultadoExame = new LancamentoExameLaboratorio('oResultadoExame');
  oResultadoExame.mostraCampoObservacao( true );
  oResultadoExame.setCIDs( aExamesRequisicao[iIndex].aCID );
  oResultadoExame.setProcedimento( aExamesRequisicao[iIndex].iProcedimento );
  oResultadoExame.setCodigoCIDConferido( aExamesRequisicao[iIndex].iCidConferido );
  oResultadoExame.abrirComoJanela(iItemExame);
  oResultadoExame.setCallbackConferir( function( oCID ) {

    if ( oCID != null ) {

      aExamesRequisicao[iIndex].sEstruturalCidConferido = oCID.sEstruturalCidConferido;
      aExamesRequisicao[iIndex].sNomeCidConferido       = oCID.sNomeCidConferido;
    }

    aExamesRequisicao[iIndex].lConferido = true;
    popularGrid();
    oResultadoExame.oBtnFechar.click();
  });

}

function popularGrid() {

  oGridExames.clearAll(true);
  aExamesRequisicao.each( function (oExame, iLinha) {

    var sSituacao  = "2 - Lançado";
    var oLinkExame = new Element( 'a', {'href':'#', 'onclick': 'js_consultaResultados('+oExame.iExame+', '+iLinha+')'} )
                                .update( oExame.sExame.urlDecode() );
    var aLinha = [];
    aLinha.push(oLinkExame.outerHTML);
    if ( oExame.lConferido ) {

      sSituacao = "7 - Conferido";
      aLinha[0] = oExame.sExame.urlDecode();
    }

    aLinha.push(oExame.sProcedimentoEstrutural);

    var sCID = '';

    if ( oExame.sNomeCidConferido != '' ) {
      sCID = oExame.sEstruturalCidConferido.urlDecode() + ' - ' + oExame.sNomeCidConferido.urlDecode();
    }

    aLinha.push(sCID);
    aLinha.push(sSituacao);
    aLinha.push(oExame.iExame);
    aLinha.push(oExame.iProcedimento);

    oGridExames.addRow( aLinha);
  });

  oGridExames.renderRows();

  aExamesRequisicao.each( function (oExame, iLinha) {

    oGridExames.aRows[iLinha].aCells[2].addClassName('elipse');

    if( oExame.sNomeCidConferido != '' ) {

      var sHint = oExame.sEstruturalCidConferido.urlDecode() + ' - ' + oExame.sNomeCidConferido.urlDecode();
      oGridExames.setHint( iLinha, 2, sHint );
    }

  });
}
</script>