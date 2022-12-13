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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$iOpcaoProcesso = 1;
$lExibirMenus   = true;

$oGet = db_utils::postMemory($_GET);

/**
 * Codigo do precesso informado por GET
 * - Pesquisa numero e ano do processo
 */
if ( !empty($oGet->iCodigoProcesso) ) {

  $iOpcaoProcesso = 3;
  $lExibirMenus   = false;

  $oDaoProtprocesso = db_utils::getDao('protprocesso');
  $sSqlNumeroProcesso = $oDaoProtprocesso->sql_query_file($oGet->iCodigoProcesso, 'p58_numero, p58_ano');
  $rsNumeroProcesso = $oDaoProtprocesso->sql_record($sSqlNumeroProcesso);

  if ( $oDaoProtprocesso->numrows > 0 ) {

    $oDaoProcesso = db_utils::fieldsMemory($rsNumeroProcesso, 0);
    $p58_numero = $oDaoProcesso->p58_numero . '/' . $oDaoProcesso->p58_ano;
  }

}

$oRotulo  = new rotulocampo;
$oDaoProtprocessodocumento = db_utils::getDao('protprocessodocumento');
$oDaoProtprocessodocumento->rotulo->label();

$oRotulo->label("p58_numero");
$oRotulo->label("z01_nome");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  db_app::load("estilos.css, grid.style.css");
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <div class="container" style="width:650px;">

    <fieldset>
      <legend>Anexar Documentos aos Processos</legend>
      <form name="form" id="form" method="post" action="" enctype="multipart/form-data">


        <?php db_input("namefile", 30, 0, true, "hidden", 1); ?>
        <?php db_input("p01_sequencial", 30, 0, true, "hidden", 1); ?>
        <?php db_input("p58_codproc", 30, 0, true, "hidden", 1); ?>

        <table class="form-container">

          <tr>
            <td nowrap title="<?php echo $Tp01_protprocesso; ?>" >
              <?php db_ancora($Lp58_numero, "js_pesquisarProcesso(true);", $iOpcaoProcesso); ?>
            </td>
            <td>
              <?php
                db_input('p58_numero', 12, $Ip58_numero, true, 'text', $iOpcaoProcesso, " onChange='js_pesquisarProcesso(false);'");
                db_input('z01_nome', 60, $Iz01_nome,true,'text',3,"");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tp01_documento; ?>" >
              <?php echo $Lp01_documento; ?>
            </td>
            <td>
              <?php db_input("uploadfile", 53, 0, true, "file", 1); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tp01_descricao; ?>" >
              <?php echo $Lp01_descricao; ?>
            </td>
            <td>
              <?php db_input("p01_descricao", 50, 0, true, "text", 1, "class='field-size-max'"); ?>
            </td>
          </tr>

        </table>
      </form>
    </fieldset>

    <input type="button" id="btnSalvar" value="Salvar" onClick="js_salvar();" />
    <input type="hidden" id="idusuario" value="<?= db_getsession('DB_id_usuario') ?>"  />

    <fieldset style="margin-top:15px;">
      <legend>Documentos Anexados</legend>
      <div id="ctnDbGridDocumentos"></div>
    </fieldset>

    <input type="button" id="btnExcluir" value="Excluir Selecionados" onClick="js_excluirSelecionados();" />

  </div>

  <?php if ( $lExibirMenus ) : ?>
    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
  <?php endif; ?>

  <div id="teste" style="display:none;"></div>
</body>
</html>
<script type="text/javascript">

/**
 * Pesquisa processo do protocolo e depois os documentos anexados
 */
if ( !empty($('p58_numero').value) ) {
  js_pesquisarProcesso(false);
}

/**
 * Mensagens do programa
 * @type constant
 */
const MENSAGENS = 'patrimonial.protocolo.prot4_processodocumento001.';

var sUrlRpc = 'prot4_processodocumento.RPC.php';

var oGridDocumentos = new DBGrid('gridDocumentos');

oGridDocumentos.nameInstance = "oGridDocumentos";
oGridDocumentos.setCheckbox(0);
oGridDocumentos.setCellAlign(new Array("center", "left", "center"));
oGridDocumentos.setCellWidth(["10%", "65%", "25%"]);
oGridDocumentos.setHeader(new Array("Código", "Descrição", "Ação"));
oGridDocumentos.allowSelectColumns(false);
oGridDocumentos.show($('ctnDbGridDocumentos'));

/**
 * Buscar documentos do processo
 * @return boolean
 */
function js_buscarDocumentos() {

  var iCodigoProcesso = $('p58_codproc').value;

  if ( empty(iCodigoProcesso) ) {
    return false;
  }

  js_divCarregando( _M( MENSAGENS + 'mensagem_buscando_documentos' ), 'msgbox');

  var oParametros = new Object();

  oParametros.exec            = 'carregarDocumentos';
  oParametros.iCodigoProcesso = iCodigoProcesso;

  var oAjax = new Ajax.Request(
    sUrlRpc, {
      parameters   : 'json='+Object.toJSON(oParametros),
      method       : 'post',
      asynchronous : false,

      /**
       * Retorno do RPC
       */
      onComplete   : function(oAjax) {

        js_removeObj("msgbox");
        var oRetorno  = eval('('+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        oGridDocumentos.clearAll(true);
        var iDocumentos = oRetorno.aDocumentosVinculados.length;

        for ( var iIndice = 0; iIndice < iDocumentos; iIndice++ ) {

          var oDocumento = oRetorno.aDocumentosVinculados[iIndice];
          var sDescricaoDocumento = oDocumento.sDescricaoDocumento;
          
          var disabled = "";
          var idusuario = $F('idusuario');     

          if (oDocumento.iIdUsuario != null && oDocumento.iIdUsuario != idusuario) {

             disabled = "disabled";
          }


          var sHTMLBotoes  = '<input '+ disabled +' type="button" value="Alterar" onClick="js_alterarDocumento('+ oDocumento.iCodigoDocumento +', \'' + sDescricaoDocumento + '\');" />  ';
              sHTMLBotoes += '<input type="button" value="Download" onClick="js_downloadDocumento('+ oDocumento.iCodigoDocumento +');" />  ';
          
          var aLinha       = [oDocumento.iCodigoDocumento, sDescricaoDocumento.urlDecode(), sHTMLBotoes];
          
          oGridDocumentos.addRow(aLinha, false, disabled);

        }

        oGridDocumentos.renderRows();
      }
    }
  );

}

/**
 * Exclui documentos selecionados
 * @return boolean
 */
function js_excluirSelecionados() {

  var aSelecionados   = oGridDocumentos.getSelection("object");
  var iSelecionados   = aSelecionados.length;
  var iCodigoProcesso = $('p58_codproc').value;
  var aDocumentos     = [];

  if ( iSelecionados == 0 ) {

    alert(_M( MENSAGENS + 'erro_nenhum_documento_selecionado_exclusao' ));
    return false;
  }

  if ( empty(iCodigoProcesso) ) {

    alert(_M( MENSAGENS + 'erro_processo_nao_informado' ));
    return false;
  }

  for( var iIndice = 0; iIndice < iSelecionados; iIndice++ ) {

    var iDocumento = aSelecionados[iIndice].aCells[0].getValue();
    aDocumentos.push(iDocumento);
  }

  js_divCarregando(_M( MENSAGENS + 'mensagem_excluindo_documentos' ), 'msgbox');

  var oParametros = new Object();

  oParametros.exec                = 'excluirDocumento';
  oParametros.iCodigoProcesso     = iCodigoProcesso;
  oParametros.aDocumentosExclusao = aDocumentos;

  var oAjax = new Ajax.Request(
    sUrlRpc, {
      parameters   : 'json='+Object.toJSON(oParametros),
      method       : 'post',
      asynchronous : false,

      /**
       * Retorno do RPC
       */
      onComplete   : function(oAjax) {

        js_removeObj("msgbox");
        var oRetorno  = eval('('+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        alert(sMensagem);
        js_buscarDocumentos();
     }
  });

}

/**
 * Altera descricao de um documento
 * @param integer iCodigoDocumento
 * @param string sDescricaoDocumento
 * @return void
 */
function js_alterarDocumento(iCodigoDocumento, sDescricaoDocumento) {

  $('namefile').value      = '';
  $('uploadfile').value    = '';
  $('uploadfile').disabled = true;
  $('p01_descricao').value = sDescricaoDocumento.urlDecode();

  /**
   * Altera acao do botao salvar
   * @return void
   */
  $('btnSalvar').onclick = function() {

    var iCodigoProcesso     = $('p58_codproc').value;
    var sDescricaoDocumento = encodeURIComponent(tagString($('p01_descricao').value));
    var oParametros         = new Object();

    if ( empty(iCodigoProcesso) ) {

      alert(_M( MENSAGENS + 'erro_processo_nao_informado' ));
      return false;
    }

    if ( empty(sDescricaoDocumento) )  {

     alert(_M( MENSAGENS + 'erro_descricao_nao_informada' ));
     return false;
    }

    js_divCarregando(_M( MENSAGENS + 'mensagem_salvando_documento' ), 'msgbox');

    oParametros.exec                = 'salvarDocumento';
    oParametros.iCodigoDocumento    = iCodigoDocumento;
    oParametros.iCodigoProcesso     = iCodigoProcesso;
    oParametros.sDescricaoDocumento = sDescricaoDocumento;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters   : 'json='+Object.toJSON(oParametros),
        method       : 'post',
        asynchronous : false,
        onComplete   : function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno  = eval('('+oAjax.responseText+")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if ( oRetorno.iStatus > 1 ) {

            alert(sMensagem);
            return false;
          }

          $('btnSalvar').onclick   = js_salvar;
          $('namefile').value      = '';
          $('uploadfile').value    = '';
          $('uploadfile').disabled = false;
          $('p01_descricao').value = '';

          alert(sMensagem);
          js_buscarDocumentos();
        }
    });

  }
}

/**
 * Dowload de um documento
 * - busca arquivo do banco e salva no tmp
 * - exibe janela com link para download
 * @param  integer iCodigoDocumento
 * @return void
 */
function js_downloadDocumento(iCodigoDocumento) {

  js_divCarregando(_M( MENSAGENS + 'mensagem_carregando_documento' ), 'msgbox');

  var oParametros = new Object();

  oParametros.exec             = 'download';
  oParametros.iCodigoDocumento = iCodigoDocumento;

  var oAjax = new Ajax.Request(
    sUrlRpc, {
      parameters   : 'json='+Object.toJSON(oParametros),
      method       : 'post',
      asynchronous : false,

      /**
       * Retorno do RPC
       */
      onComplete   : function(oAjax) {

        js_removeObj("msgbox");
        var oRetorno  = eval('('+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        var sCaminhoDownloadArquivo = oRetorno.sCaminhoDownloadArquivo.urlDecode();
        var sTituloArquivo          = oRetorno.sTituloArquivo.urlDecode();

        window.open("db_download.php?arquivo="+sCaminhoDownloadArquivo);
      }
  });

}

/**
* Pesquisar processo
 *
 * @param boolean lMostra
 * @return boolean
 */
function js_pesquisarProcesso(lMostra) {

  var sArquivo = 'func_protprocesso_protocolo.php?funcao_js=parent.';

  if (lMostra) {
    sArquivo += 'js_mostraProcesso|dl_código_do_processo|p58_numero|dl_nome_ou_razão_social';
  } else {

    var iNumeroProcesso = $('p58_numero').value;

    if ( empty(iNumeroProcesso) ) {
      return false;
    }

    sArquivo += 'js_mostraProcessoHidden&pesquisa_chave=' + iNumeroProcesso + '&sCampoRetorno=p58_codproc';
  }

  js_OpenJanelaIframe('', 'db_iframe_proc', sArquivo, 'Pesquisa de Processos', lMostra);
}

/**
 * Retorno da js_pesquisarProcesso apor clicar em um processo
 * @param  integer iCodigoProcesso
 * @param  integer iNumeroProcesso
 * @param  string sNome
 * @return void
 */
function js_mostraProcesso(iCodigoProcesso, iNumeroProcesso, sNome) {

  $('p58_codproc').value   = iCodigoProcesso;
  $('p58_numero').value    = iNumeroProcesso;
  $('z01_nome').value      = sNome;
  $('p01_descricao').value = '';
  $('uploadfile').disabled = false;
  db_iframe_proc.hide();
  js_buscarDocumentos();
}

/**
 * Retorno da pesquisa js_pesquisarProcesso apos mudar o campo p58_numero
 * @param  integer iCodigoProcesso
 * @param  string sNome
 * @param  boolean lErro
 * @return void
 */
function js_mostraProcessoHidden(iCodigoProcesso, sNome, lErro) {

  /**
   * Nao encontrou processo
   */
  if ( lErro ) {

    $('p58_numero').value    = '';
    $('p58_codproc').value   = '';
    $('p01_descricao').value = '';
    $('uploadfile').disabled = false;
    oGridDocumentos.clearAll(true);
  }

  $('p58_codproc').value = iCodigoProcesso;
  $('z01_nome').value    = sNome;
  js_buscarDocumentos();
}

/**
* Cria um listener para subir a imagem, e criar um preview da mesma
*/
$("uploadfile").onchange = function() {

  startLoading();
  var iFrame = document.createElement("iframe");
  iFrame.src = 'func_uploadfiledocumento.php?clone=form';
  iFrame.id  = 'uploadIframe';
  $('teste').appendChild(iFrame);
}

function startLoading() {
  js_divCarregando(_M( MENSAGENS + 'mensagem_enviando_documento' ),'msgbox');
}

function endLoading() {
  js_removeObj('msgbox');
}

function js_salvar() {

  var iCodigoProcesso     = $('p58_codproc').value;
  var iCodigoDocumento    = $('p01_sequencial').value;
  var sDescricaoDocumento = encodeURIComponent(tagString($('p01_descricao').value));
  var sCaminhoArquivo     = $('namefile').value;

  if ( empty(iCodigoProcesso) ) {

    alert(_M( MENSAGENS + 'erro_processo_nao_informado' ));
    return false;
  }

  if ( empty(sDescricaoDocumento) )  {

   alert(_M( MENSAGENS + 'erro_descricao_nao_informada' ));
   return false;
  }

  if ( empty(sCaminhoArquivo) ) {

    alert(_M( MENSAGENS + 'erro_documento_nao_informado' ));
    return false;
  }

  js_divCarregando(_M( MENSAGENS + 'mensagem_salvando_documento' ), 'msgbox');

  var oParametros = new Object();

  oParametros.exec                = 'salvarDocumento';
  oParametros.iCodigoDocumento    = iCodigoDocumento;
  oParametros.iCodigoProcesso     = iCodigoProcesso;
  oParametros.sDescricaoDocumento = sDescricaoDocumento;
  oParametros.sCaminhoArquivo     = sCaminhoArquivo;

  var oAjax = new Ajax.Request(
    sUrlRpc, {
      parameters   : 'json='+Object.toJSON(oParametros),
      method       : 'post',
      asynchronous : false,
      onComplete   : function(oAjax) {

        js_removeObj("msgbox");
        var oRetorno  = eval('('+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        $('namefile').value      = '';
        $('uploadfile').value    = '';
        $('uploadfile').disabled = false;
        $('p01_descricao').value = '';

        alert(sMensagem);
        js_buscarDocumentos();
      }
  });
}
</script>