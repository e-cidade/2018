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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, grid.style.css, estilos.css");
  db_app::load("DBViewAlvaraDocumentos.js");
?>
</head>

<body class="body-default">
<div class="container">
  <form name="form1" id="form1" methos="post" action="" >
    <? db_input('q120_issalvara',8,'',true,'hidden', 3,"","") ?>
      <fieldset style="width:400px;">
        <legend>Renovação de Alvará</legend>
        <table width="100%">
          <tr>
            <td><? db_ancora("<strong>Inscrição:</strong>", "js_pesquisaAlvara(true,'');", 1); ?></td>
            <td><? db_input('q123_inscr',8,'',true,'text', 1,"onchange = js_pesquisaAlvara(false,this.value);","") ?></td>
            <td colspan="2"><? db_input('z01_nome',40,'',true,'text', 3,"","") ?></td>
          </tr>
          <tr>
            <td><strong>Data da renovação:</strong></td><!-- Mostra a data atual para o usuário -->
            <td>
              <?
                $q120_dtmov = date("d/m/Y",db_getsession("DB_datausu"));
                db_input('q120_dtmov',8,'',true,'text', 3)
              ?>
          </td>
            <td coslpan="2"></td>
          </tr>
        <tr>
            <td><strong>Validade do alvará (dias):</strong></td>
            <td><? db_input('q120_validadealvara', 8, "", true, 'text', 1); ?></td>
            <td coslpan="2"></td>
          </tr>
          <tr>
            <td><? db_ancora("<strong>Processo de protocolo:</strong>", "js_pesquisaProtocolo(true,'');", 1); ?></td>
            <td><? db_input('p58_codproc',8,'',true,'text', 1,"onchange = js_pesquisaProtocolo(false,this.value);") ?></td>
            <td colspan="2"><? db_input('p58_nomeprocesso', 40, '',true, 'text', 3, "", ""); ?></td>
          </tr>
          <tr>
            <td colspan="4">
              <fieldset>
                <legend><strong>Resumo:</strong></legend>
                <? db_textarea('q120_obs', 10, 67, '', true, 'text', 1); ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <br>
      <div id="ctnDocumentos" style="width:560px;"></div> <!-- div que vai receber o grid de documentos -->
      <br>
      <input type="button" name="btnSalvar" id="btnSalvar" value="Renovar" disabled="disabled">&nbsp;
      <input type="button" name="btnLimpar" id="btnLimpar" value="Limpar campos">&nbsp;
  </form>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit") );
?>
</body>
</html>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */
?>
<script language="JavaScript" type="text/javascript">

  /* instância do objeto que monta a grid de documentos no formulário */
  var oDocumentos = new DBViewAlvaraDocumentos('oDocumentos', 'ctnDocumentos');
      oDocumentos.show();

  function js_pesquisaAlvara(mostra, iChave) {

    var sUrl = '';

    if (iChave == "") {
      sUrl = 'func_issalvararenovacao.php?funcao_js=parent.js_mostraAlvara|q123_inscr|z01_nome|q123_sequencial';
    } else {
      sUrl = 'func_issalvararenovacao.php?pesquisa_chave='+iChave+'&funcao_js=parent.js_mostraAlvara2';
    }
    js_OpenJanelaIframe('top.corpo' ,'db_iframe_alvara', sUrl, 'Pesquisa Alvarás',mostra);
  }

  function js_mostraAlvara(sAlvara, sDetalhe, iCodigoAlvara) {

    $('q123_inscr').value     = sAlvara;
    $('z01_nome').value       = sDetalhe;
    $('q120_issalvara').value = iCodigoAlvara;
    oDocumentos.setCodigoAlvara(iCodigoAlvara);
    oDocumentos.carregaDados();
    db_iframe_alvara.hide();
    $('btnSalvar').disabled   = false;
  }

  function js_mostraAlvara2(iAlvara,lErro,iInscr, sNome) {

    if (lErro == true) {

      $('q123_inscr').value     = '';
      $('z01_nome').value       = iAlvara;
      $('q120_issalvara').value = '';
      $('btnSalvar').disabled   = true;
    } else {

      $('q123_inscr').value = iInscr;
      $('z01_nome').value   = sNome;
      $('q120_issalvara').value = iAlvara;
      oDocumentos.setCodigoAlvara(iAlvara);
      oDocumentos.carregaDados();

      $('btnSalvar').disabled   = false;
    }
  }

  /* código referente ao protocolo */

  function js_pesquisaProtocolo(mostra, iChave) {

    var sUrl = '';

    if (iChave == "") {
      sUrl = 'func_protprocesso.php?funcao_js=parent.js_mostraProtocolo|p58_codproc|z01_nome';
    } else {
      sUrl = 'func_protprocesso.php?pesquisa_chave='+iChave+'&funcao_js=parent.js_mostraProtocolo';
    }
    js_OpenJanelaIframe('top.corpo' ,'db_iframe_protocolo' ,sUrl, 'Pesquisa protocolo' ,mostra);
  }

  function js_mostraProtocolo(sProtocolo, sDetalhe) {

    $('p58_codproc').value      = sProtocolo;
    $('p58_nomeprocesso').value = sDetalhe;
    db_iframe_protocolo.hide();
  }

  /* envio dos dados para o RPC */

  $("btnSalvar").observe("click", function() {

    if ($F('q123_inscr') == '') {
      return;
    }
    if ($('q120_validadealvara').value == '') {

      alert ("A nova validade do alvará deve ser informada.");
      return false;
    }
    var oParam                 = new Object();
    oParam.exec                = "renovarAlvara";
    oParam.q120_issalvara      = $F('q120_issalvara');
    oParam.q120_dtmov          = $F('q120_dtmov');
    oParam.q120_validadealvara = $F('q120_validadealvara');
    oParam.p58_codproc         = $F('p58_codproc');
    oParam.q120_obs            = $F('q120_obs');
    oParam.aDocumentos         = oDocumentos.getDocumentosSelecionados();
    js_divCarregando("Aguarde, processando...", "msgBox");
    var oAjax = new Ajax.Request("iss4_renovacaoalvara.RPC.php",
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {

                                                js_removeObj("msgBox");
                                                var aRetorno = eval("("+oAjax.responseText+")");
                                                alert(aRetorno.message.urlDecode());
                                                if (aRetorno.status == 1) {
                                                  window.location = 'iss4_renovacaoalvara001.php';
                                                }
                                              }
                                 });
  });

  /* limpeza dos campos */

  $("btnLimpar").observe("click", function() {
    js_limparCampos();
  });

  function js_limparCampos() {

    $('q123_inscr').value          = '';
    $('z01_nome').value            = '';
    $('q120_validadealvara').value = '';
    $('p58_codproc').value         = '';
    $('p58_nomeprocesso').value    = '';
    $('q120_issalvara').value      = '';
    $('q120_obs').value            = '';
  }
  js_limparCampos();
</script>