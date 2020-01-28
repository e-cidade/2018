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
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new RotuloCampoDB();
$oRotulo->label('sd70_i_codigo');
$oRotulo->label('sd70_c_cid');
$oRotulo->label('sd70_c_nome');

$lPossuiCPF = true;
$lPossuiCNS = true;

if( !empty( $iFaa ) ) {

  $oProntuario = new Prontuario( $iFaa );
  $lPossuiCPF  = trim( $oProntuario->getCGS()->getCpf() ) != "";
  $lPossuiCNS  = count( $oProntuario->getCGS()->getCartaoSus() ) > 0;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/saude/ambulatorial/DBViewEncaminhamento.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form action="" method="post">
      <fieldset>
        <legend>Atestado Médico</legend>

        <table class="form-container">
          <tr>
            <td>
              <label for="nome_paciente">Paciente: </label>
            </td>
            <td colspan="2">
              <input type="text" id='nome_paciente' name='nome_paciente' value='' disabled="disabled" class="readonly" />
            </td>
          </tr>

          <tr>
            <td class="field-size3">
              <label for="tipoAtestado">Tipo de Atestado:</label>
            </td>
            <td>
              <select id="tipoAtestado" onchange="alteraTipoAtestado();">
                <option value="1">PADRÃO</option>
                <option value="2">EM BRANCO</option>
              </select>
            </td>
          </tr>
        </table>

        <div id="atestadoPadrao">
          <fieldset class="separator">
            <legend>Padrão</legend>
            <table class="form-container">
              <tr>
                <td>
                  <label for="cboDocumento">Documento:</label>
                </td>
                <td>
                  <select id='cboDocumento'>
                    <?php
                    if( $lPossuiCNS ) {

                      ?>
                      <option value="1">CNS</option>
                      <?php
                    }

                    if( $lPossuiCPF ) {

                      ?>
                      <option value="2">CPF</option>
                      <?php
                    }
                    ?>

                    <option value="3">NÃO POSSUI</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="CID:">
                    <?php db_ancora( $Lsd70_c_cid, 'pesquisarCID(true);', 1 ); ?>
                  </label>
                </td>
                <td class="field-size3">
                  <?php
                  db_input( 'sd70_i_codigo', 10, $Isd70_i_codigo, true, 'hidden', 3 );
                  db_input( 'sd70_c_cid',    10, $Isd70_c_cid,    true, 'text',   1, 'onchange="pesquisarCID(false);"' );
                  ?>
                </td>
                <td>
                  <?php
                  db_input( 'sd70_c_nome',   50, $Isd70_c_nome,   true, 'text',   3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="dias_afastamento">Dias de Afastamento:</label>
                </td>
                <td class="field-size3">
                  <input type="text" value="" id='dias_afastamento' name='dias_afastamento' maxlength="3"
                         onkeyup="js_ValidaCampos(this, 1, 'Dias de Afastamento', 'f', 'f', event);"
                         onpaste="js_ValidaPaste(this, 1, event);" />
                </td>
              </tr>
            </table>
          </fieldset>
        </div>

        <div id="atestadoEmBranco" style="display: none;">
          <fieldset class="separator">
            <legend>Em Branco</legend>
            <table class="form-container">
              <tr>
                <td>
                  <fieldset>
                    <legend>Conteúdo</legend>
                    <textarea id="conteudoAtestado" cols="80"></textarea>
                  </fieldset>
                </td>
              </tr>
            </table>
          </fieldset>
        </div>

      </fieldset>
      <input type="button" value='Imprimir' id='btnAtestado' name='atestado' onclick="imprimir();" />
    </form>

</body>
<script type="text/javascript">

var MSG_SAU2EMITIRATESTADO = "saude.ambulatorial.sau2_emitiratestado.";

var oGet               = js_urlToObject();
var iSetorAmbulatorial = null;
var iMovimentacao      = null;

(function(){

  $('nome_paciente').addClassName('field-size-max');
  $('cboDocumento').addClassName('field-size3');
  $('dias_afastamento').addClassName('field-size3');
  $('sd70_c_cid').addClassName('field-size3');
  $('nome_paciente').value = oGet.sNome;

  buscaMovimentacao();
})();

$('dias_afastamento').ondrop = function(e){

  e.preventDefault();
  return false;
};

$('sd70_c_cid').ondrop = function(event){

  event.preventDefault();
  return;
}

$('sd70_c_cid').onpaste = function(event){

  event.preventDefault();
  return;
}

/**
 * Pesquisa CID
 */
function pesquisarCID( lMostra ) {

  if( lMostra ) {

    var strParam  = 'func_sau_cid.php';
        strParam += '?funcao_js=parent.retornoCID|sd70_i_codigo|sd70_c_cid|sd70_c_nome';
    js_OpenJanelaIframe('','db_iframe_sau_cid', strParam, 'Pesquisa CID', true);

  } else if ( $F('sd70_c_cid') != '' ) {

    var oParametros = {exec:'getCID', sd70_c_cid: $F('sd70_c_cid'), booValidaCID: false};
    var oAjax = new AjaxRequest('sau1_sau_individualprocedRPC.php', oParametros, function (oRetorno) {

      if ( oRetorno.status == 2 ) {

        limparCID();
        alert(oRetorno.message.urlDecode());
        return;
      }

      $('sd70_i_codigo').value = oRetorno.itens[0].sd70_i_codigo;
      $('sd70_c_cid').value    = oRetorno.itens[0].sd70_c_cid;
      $('sd70_c_nome').value   = oRetorno.itens[0].sd70_c_nome.urlDecode();
    });
    oAjax.setMessage( _M(MSG_SAU2EMITIRATESTADO + "pesquisando_cid") );
    oAjax.execute();
  } else {
    limparCID();
  }
}

function limparCID() {

  $('sd70_i_codigo').value = '';
  $('sd70_c_cid').value    = '';
  $('sd70_c_nome').value   = '';
}

function retornoCID(iCodigo, sCid, sNome) {

  db_iframe_sau_cid.hide();
  $('sd70_i_codigo').value = iCodigo;
  $('sd70_c_cid').value    = sCid;
  $('sd70_c_nome').value   = sNome;
}

function alteraTipoAtestado() {

  $('atestadoPadrao').setStyle({'display': ''});
  $('atestadoEmBranco').setStyle({'display': 'none'});

  if($F('tipoAtestado') == 2) {

    $('atestadoPadrao').setStyle({'display': 'none'});
    $('atestadoEmBranco').setStyle({'display': ''});
  }
}

function buscaMovimentacao() {

  var oParametros             = {};
      oParametros.sExecucao   = "buscaUltimaObservacaoDaMovimentacao";
      oParametros.iProntuario = oGet.iFaa;
      oParametros.iTelaOrigem = DBViewEncaminhamento.CONSULTA_MEDICA;
      oParametros.lAtestado   = true;

  AjaxRequest.create('sau4_fichaatendimento.RPC.php', oParametros, function(oRetorno) {

    iSetorAmbulatorial          = oRetorno.iSetorAmbulatorial;
    iMovimentacao               = oRetorno.iMovimentacao;
    $('conteudoAtestado').value = oRetorno.sObservacao.urlDecode();
  }).setMessage('Aguarde, buscando as movimentações...')
    .execute();
}

function imprimir() {

  if($F('tipoAtestado') == 1) {

    if ( $F('dias_afastamento') == '' ) {

      alert( _M(MSG_SAU2EMITIRATESTADO + "informe_dias_afastamento"));
      return;
    }

    if ( $F('dias_afastamento') == 0 ) {

      alert( _M(MSG_SAU2EMITIRATESTADO + "dias_afastamento_maior_zero"));
      return;
    }
  }

  var oParametros                    = {};
      oParametros.iFaa               = oGet.iFaa;
      oParametros.sNome              = $F('nome_paciente');
      oParametros.sCIDDescricao      = $F('sd70_c_nome');
      oParametros.dtAtendimento      = oGet.dtAtendimento;
      oParametros.sHora              = oGet.sHora;
      oParametros.iDocumento         = $F('cboDocumento');
      oParametros.iDias              = $F('dias_afastamento');
      oParametros.sCID               = $F('sd70_c_cid');
      oParametros.iEspecMedico       = oGet.iEspecMedico;
      oParametros.sConteudo          = $F('conteudoAtestado');
      oParametros.iTipoAtestado      = $F('tipoAtestado');
      oParametros.iSetorAmbulatorial = iSetorAmbulatorial;
      oParametros.iMovimentacao      = iMovimentacao;

  var oEmissaoRelatorio = new EmissaoRelatorio('sau2_emitiratestado002.php', oParametros);
      oEmissaoRelatorio.open();
}
</script>
</html>