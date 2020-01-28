<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  require_once("libs/db_app.utils.php");
  require_once("libs/db_utils.php");
  require_once("std/DBDate.php");
  require_once("dbforms/db_funcoes.php");

  $oPost = db_utils::postMemory($_POST);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <div class="container" style="width:700px !important;">

    <form method="post" name="form1">

      <fieldset style="padding-bottom: 30px;">

        <legend>Geração Arquivos do Simples Nacional</legend>

        <table class="form-container">

          <tr>
            <td width="80px">CNAE:</td>
            <td>
              <input type="hidden" value="<?php echo $oPost->q64_datalimitevencimentos; ?>" id="dtLimite">
              <input type="hidden" value="<?php echo $oPost->lReprocessamento; ?>" id="lReprocessamento">
              <input type="hidden" value="<?php echo $oPost->q64_sequencial; ?>" id="q64_arquivo"/>
              <?php
                db_select('q142_cnae', array(), '', 1, " onchange='js_getEmpresas(this);'");
              ?>
            </td>
          </tr>

        </table>

        <div id="gridCnae" style="margin-top: 10px;"></div>

      </fieldset>

     <input type="button" name="enviar" value="Gerar" onclick="js_processar();"/>
     <input type="button" name="voltar" value="Voltar" onclick="history.back();"/>

    </form>

  </div>

    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>

<script type="text/javascript">

  var sUrlRPC    = 'iss1_processararquivosimplesnacional.RPC.php';
  var MENSAGENS  = 'tributario.issqn.iss1_processararquivosimplesnacional.';
  var oToggle;

  (function(){

    $('q142_cnae').disabled = true;

    js_divCarregando('Aguarde, Validando Arquivo.', 'oCarregando');

		/**
     * Requisição para fazer a validação automática
     */
    var oParametros               = new Object();
    var oDadosRequisicao          = new Object();

    oParametros.sExecucao         = 'validacaoAutomatica';
    oParametros.lReprocessamento  = $('lReprocessamento').value;
    oParametros.dtLimite          = $('dtLimite').value;
    oParametros.iArquivo          = $('q64_arquivo').value;

    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
    oDadosRequisicao.onComplete   = function( oAjax ) {

      js_removeObj('oCarregando');

      var oRetorno = JSON.parse( oAjax.responseText );

      if (oRetorno.iStatus == "2") {
        alert( oRetorno.sMensagem.urlDecode() );
        return;
      }
    }

    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );

		/**
     * Carrega os CNAE
     */
    if( $('q64_arquivo').value == '' ){

      alert (  _M( MENSAGENS + 'arquivo_obrigatorio' ) );
      $('q142_cnae').disabled = true;
      return;
    }

    js_divCarregando('Aguarde, Carregando CNAE.', 'oCarregando');

    var oParametros               = new Object();
    var oDadosRequisicao          = new Object();

    oParametros.sExecucao         = 'getCnae';
    oParametros.iArquivo          = $('q64_arquivo').value;

    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
    oDadosRequisicao.onComplete   = function(oAjax) {

      js_removeObj('oCarregando');

      $('q142_cnae').disabled       = false;

      var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

      if (oRetorno.iStatus == "2") {

        alert( oRetorno.sMensagem );
        return;
      }

      for(var iCnae=0; iCnae < oRetorno.aCnaes.length; iCnae++ ){

        var oSelect       = $('q142_cnae');

        if( iCnae == 0 ){
           var oOpcao        = document.createElement("option");
           oOpcao.value      = 0;
           oOpcao.text       = 'SELECIONE';
           oSelect.appendChild(oOpcao);
        }

        var oDadosCnae    = oRetorno.aCnaes[iCnae];
        var oOpcao        = document.createElement("option");
            oOpcao.value  = oDadosCnae.q71_estrutural;
            oOpcao.text   = oDadosCnae.q71_descr.urlDecode();
            oSelect.appendChild(oOpcao);

      }
    }

    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );

    /**
     * Instancia o toggle
     */
    oToggle = new DBToggleList([{sId:'iSequencial', sLabel: 'sequencial', lVisible: false},{sId:'sCnpj', sLabel:'CNPJ'}, {sId: 'sObservacao', sLabel: 'Observação'}]);
    oToggle.setCallback({
      selecao: {
        afterMove: function(oEmpresas) {
          js_Aptos(oEmpresas, true);
        }
      },
      selecionados: {
        afterMove: function(oEmpresas) {
          js_Aptos(oEmpresas, false);
        }
      }
    });

    oToggle.setLabels({selecao: "APTOS", selecionados: "NÃO APTOS"});

    oToggle.show($('gridCnae'));

  })();

  function js_Aptos (oEmpresas, lApto) {

    js_divCarregando('Aguarde, Salvando suas alterações.', 'oCarregando');

    var oParametros      = new Object();
    var oDadosRequisicao = new Object();

    oParametros.sExecucao = 'setAptos';
    oParametros.oEmpresas = oEmpresas;
    oParametros.lApto     = lApto;

    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros).urlEncode();
    oDadosRequisicao.onComplete   = js_retornoAptos;

    new Ajax.Request( sUrlRPC, oDadosRequisicao );
  }

  function js_retornoAptos( oAjax ){
    js_removeObj('oCarregando');
  }

  function js_getEmpresas(oSelect) {

    if (!js_validaSelecionados()) {
      return false;
    }

    js_divCarregando('Aguarde, Carregando Empresas.', 'oCarregando');

    if (oSelect.value == 0) {
      oToggle.clearAll(true)
    }

    var oParametros               = new Object();
    oParametros.sExecucao         = 'getEmpresas';
    oParametros.iArquivo          = $('q64_arquivo').value;
    oParametros.sEstrutural       = oSelect.value;

    var oDadosRequisicao          = new Object();
    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);

    oDadosRequisicao.onComplete   = function(oAjax){

      js_removeObj('oCarregando');

      oRetorno = JSON.parse(oAjax.responseText);

      if (oRetorno.iStatus == 2) {
        alert(oRetorno.sMensagem);
        return false;
      }

      oToggle.clearAll(true);

      oRetorno.aEmpresas.forEach(function(value, key) {

        var oRow = {
          iSequencial  : value.q142_sequencial,
          sCnpj        : value.q142_cnpj,
          sObservacao  : '<input type="text" onkeyup="js_atualizaInputValue(this)" onblur="js_sincroniza(this)" value="'+value.q142_observacao.urlDecode()+'" />'
        }

        if (value.q142_apto == 't') {
          oToggle.addSelect(oRow);
        } else {
          oToggle.addSelected(oRow);
        }

      });

      oToggle.renderRows();
    }

    var oAjax = new Ajax.Request(sUrlRPC, oDadosRequisicao);
  }

  function js_sincroniza(oElemento ) {

    iSequencial = oElemento.parentElement.parentElement.firstChild.innerHTML;

    var oEmpresa = {
      iSequencial: iSequencial,
      sObservacao: oElemento.outerHTML
    }

    oToggle.getElement(iSequencial).sObservacao = oElemento.outerHTML;

    js_Aptos([oEmpresa], null);
  }

  function js_atualizaInputValue( oElemento ) {

    oElemento.setAttribute('value', oElemento.value);
  }

  function js_processar() {

    js_divCarregando('Aguarde, Gerando Arquivos.', 'oCarregando');

    var oParametros               = new Object(),
        oDadosRequisicao          = new Object();

    oParametros.sExecucao         = 'gerar';
    oParametros.iArquivo          = $('q64_arquivo').value;

    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
    oDadosRequisicao.onComplete   = abrirDownload;

    new Ajax.Request( sUrlRPC, oDadosRequisicao );
  }

  /**
   * Abrir Janela para Download do arquivo
   * @param aArquivos - Array de objetos {url: '', nome: ''}
   */
  function abrirDownload( oAjax ) {

    js_removeObj('oCarregando');

    var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

    if (oRetorno.iStatus == "2") {
      alert(oRetorno.sMensagem);
      return;
    }

    var oDownload = new DBDownload();

    // Verifica se já existe o aux aberto, se existir apaga-o
    if ( $('window01') ) {
      $('window01').outerHTML = '';
    }

    if (oRetorno.sArquivo) {

      oDownload.addGroups( 'txt', 'Arquivo Txt' );
      oDownload.addFile( oRetorno.sArquivo, oRetorno.sArquivo.split('/')[1], 'txt' );
    }

    if (oRetorno.sInconsistencias) {

      oDownload.addGroups( 'pdf', 'Relatório de Inconsistências');
      oDownload.addFile( oRetorno.sInconsistencias, oRetorno.sInconsistencias.split('/')[1], 'pdf' );
    }

    if ( oRetorno.sArquivo || oRetorno.sInconsistencias ) {
      oDownload.show();
      return;
    }

    alert (  _M( MENSAGENS + 'todos_os_registros_aptos' ) );
  }

  function js_validaSelecionados() {

    var aSelecionados = oToggle.getSelected();

    for (i = 0; i < aSelecionados.length; i++) {
      var sValor = $('oGridSelected0rowoGridSelected0'+i).children[2].firstChild.value

      if (!sValor) {
        alert(_M(MENSAGENS + "preenchimento_obrigatorio"))
        return false;
      }
    }

    return true;

  }

</script>