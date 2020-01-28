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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oGet = db_utils::postMemory($_GET);
if ( !empty($oGet->lLimparHistorico) ) {
  unset($_SESSION['oMensagensMenu']);
}

$iMenuAtual    = (int) db_getsession('DB_itemmenu_acessado');
$sCaminhoMenu  = db_stdClass::getCaminhoMenu($iMenuAtual);
$sJsonArquivos = '{}';

if ( !empty($_SESSION['oMensagensMenu']) ) {

  $oJson = new Services_JSON();
  $oMensagensMenu = $_SESSION['oMensagensMenu'];
  $sJsonArquivos      = $oJson->encode($oMensagensMenu->aArquivos);
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load('estilos.css');
    db_app::load("scripts.js, strings.js, prototype.js,  arrays.js, DBAbas.widget.js");
  ?>
<style>
textarea {
  resize: vertical;
}

#ctnMensagensArquivo {
  text-align: center;
}

fieldset.arquivo {
  position : relative;
  margin   : 15px auto 0 auto;
}

.botaoSalvar {
  margin-top : 10px;
}

#ctnBotoesMensagensCarregadas > input {
  margin-top: 10px;
}

fieldset.mensagem {
  margin         : 0px auto;
  margin-top     : 5px;
  margin-bottom  : 5px;
  border         : 0px;
  border-top     : 2px groove #FFF;
  padding-bottom : 0px;
  position       : relative;
}
</style>
</head>
<body bgcolor="#cccccc">

  <div id="ctnAbaMensagensCarregada">

    <?php if ( empty($_SESSION['oMensagensMenu']) ) : ?>

        <br /><br />
        Nenhuma mensagem carregada para o menu atual.

    <?php else : ?>

      <div id="ctnBotoesMensagensCarregadas">
        <input type="button" onClick="js_limparHistorico();" value="Limpar Histórico" />
        <input type="button" onClick="return js_toggleMensagensNaoCarregadas(this);" ocultarMensagensNaoCarregadas value="Exibir mensagens não usadas" />
      </div>
      <div id="ctnMensagensArquivo"></div>

    <?php endif; ?>
  </div>

  <div id="ctnAbaMensagensPorMenu">
    <iframe frameborder="0" id="iframeMensagensPorMenu" height="100%" width="100%"></iframe>
  </div>

  <div id="ctnAbas"></div>

</body>
</html>

<script type="text/javascript">

var oDBAbas = new DBAbas($('ctnAbas'));
oDBAbas.adicionarAba('Mensagens carregadas', $('ctnAbaMensagensCarregada'));
oDBAbas.adicionarAba('Menu', $('ctnAbaMensagensPorMenu'));

var lAbaMenuCarregada    = false;
var callBackClickAbaMenu = $('Menu').onclick;

$('Menu').onclick = function() {

  callBackClickAbaMenu();

  if ( lAbaMenuCarregada ) {
    return true;
  }

  lAbaMenuCarregada = true;
  $('iframeMensagensPorMenu').src = 'con4_mensagens001.php?lIframe=true';
}

/**
 * Caminho para arquivo de mensagens 
 */
const MENSAGENS = 'configuracao.configuracao.con4_mensagens001.';

/**
 * Arquivo RPC 
 */
const RPC = 'con4_mensagens.RPC.php';

var oLinhaTituloJanela  = parent.document.getElementById('CFdb_iframe_mensagens_sistema');
var oColunaTituloJanela = oLinhaTituloJanela.getElementsByTagName('td')[0]; 
oColunaTituloJanela.innerHTML = '&nbsp;Mensagens: <?php echo $sCaminhoMenu; ?>';

var oArquivos = <?php echo $sJsonArquivos; ?>;

for ( var sArquivo in  oArquivos ) {

  var aMensagens       = oArquivos[sArquivo];
  var oInputSalvar     = document.createElement('input');
  var oFieldsetArquivo = document.createElement('fieldset');
  var oLegendArquivo   = document.createElement('legend');

  oFieldsetArquivo.className = 'arquivo';
  oFieldsetArquivo.id = sArquivo;
  oLegendArquivo.innerHTML = sArquivo;

  oInputSalvar.type      = 'button';
  oInputSalvar.value     = 'Salvar arquivo';
  oInputSalvar.className = 'botaoSalvar';
  oInputSalvar.sArquivo  = sArquivo;
  oInputSalvar.onclick   = js_editarArquivo;
  oInputSalvar.title     = 'Salvar arquivo ' + sArquivo;

  oFieldsetArquivo.appendChild(oLegendArquivo);
  $('ctnMensagensArquivo').appendChild(oFieldsetArquivo);
  $('ctnMensagensArquivo').appendChild(oInputSalvar);

  js_montarMensagensArquivo(sArquivo, aMensagens, oFieldsetArquivo);
}

/**
 * Editar arquivo
 *
 * @access public
 * @return boolean
 */
function js_editarArquivo() {

  var aMensagens = $(this.sArquivo).getElementsByTagName('textarea');
  var oMensagem  = new Object();
  var iMensagens = aMensagens.length;

  for ( var iIndice = 0; iIndice < iMensagens; iIndice++ ) {

    var oElemento = aMensagens[iIndice];
    var sMetodo   = oElemento.id;
    var sMensagem = encodeURIComponent(tagString(oElemento.value));

    if ( empty(sMetodo) ) {

      alert(_M(MENSAGENS + 'erro_mensagem_sem_id'));
      return;
    }

    if ( empty(sMensagem) ) {

      alert(_M(MENSAGENS + 'erro_mensagem_sem_conteudo'));
      return;
    }

    oMensagem[sMetodo] = sMensagem;
  }

  js_divCarregando(_M(MENSAGENS + 'mensagem_processando_salvar_arquivo'), 'msgbox');
  var oParametro  = new Object();
  oParametro.exec = 'editarMensagensArquivo';
  oParametro.sArquivo = this.sArquivo;
  oParametro.oMensagem = oMensagem;

  new Ajax.Request(
    RPC, {
      method:'post',
      parameters:'json='+Object.toJSON(oParametro),

      /**
       * Retorno do RPC 
       */
      onComplete: function(oAjax) {

        js_removeObj("msgbox");
        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sMensagem = oRetorno.mensagem.urlDecode();
        alert(sMensagem);
      }
  });
}

/**
 * Monta as mensagens carregadas de um arquivo
 *
 * @param string sArquivo
 * @param Array aMensagens
 * @param <fieldset> oFieldsetArquivo
 * @access public
 * @return void
 */
function js_montarMensagensArquivo(sArquivo, aMensagens, oFieldsetArquivo) {

  var oParametro  = new Object();
  oParametro.exec = 'getMensagensArquivo';
  oParametro.sArquivo = sArquivo;
  
  new Ajax.Request(RPC, 
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParametro),

      /**
       * Retorno do RPC 
       */
      onComplete: function(oAjax) {
        
        var oRetorno = eval("(" + oAjax.responseText + ")");

        /**
         * Erro RPC 
         */
        if ( oRetorno.status > 1 ) {

          alert(oRetorno.mensagem.urlDecode());
          return false;
        }

        /**
         * Percorre objeto com mensagens, criando um fieldset para cada propriedade do objeto 
         */
        for ( sMetodo in oRetorno.oMensagens ) {

          var sMensagem = oRetorno.oMensagens[sMetodo].urlDecode();
          
          var oFieldset = document.createElement('fieldset');
          oFieldset.style.cssText = 'position:relative;';
          oFieldset.className = 'mensagem';

          var oLegend = js_createLegend(sMetodo);

          var oTextarea = document.createElement('textarea');
          oTextarea.innerHTML = sMensagem;
          oTextarea.id = sMetodo;
          oTextarea.className = 'field-size-max';

          oFieldset.appendChild(oLegend);
          oFieldset.appendChild(oTextarea);
          oFieldset.mensagemCarregada = 'true';

          /**
           * Mensagem nao carregada, nao exibe 
           */
          if ( !aMensagens.in_array(sMetodo) ) {

            oFieldset.style.display = 'none';
            oFieldset.mensagemCarregada = '';
          }

          oFieldsetArquivo.appendChild(oFieldset);
        }

      }
  });

}

/**
 * Cria um elemento no dom do tipo LEGEND
 *
 * @param string $sTitulo
 * @access public
 * @return <legend>
 */
function js_createLegend(sTitulo) {

  var oLegend = document.createElement('legend');

  if ( !empty(sTitulo) ) {
    oLegend.innerHTML = sTitulo;
  }

  return oLegend;
}

/**
 * Exibe/oculta mensagens nao carregadas
 *
 * @param <input> oBotao
 * @access public
 * @return void
 */
function js_toggleMensagensNaoCarregadas(oBotao) {
  
  var sDisplay     = 'block';
  var sLabelBoatao = 'Exibir mensagens não usadas';

  if ( oBotao.hasAttribute('ocultarMensagensNaoCarregadas') ) {

    sDisplay     = 'block';
    sLabelBoatao = 'Ocultar mensagens não usadas';
    oBotao.removeAttribute("ocultarMensagensNaoCarregadas");
  } else {

    oBotao.setAttribute("ocultarMensagensNaoCarregadas", "true");
    sDisplay = 'none';
  }

  var aFieldsetArquivos = $('ctnMensagensArquivo').getElementsByTagName('fieldset');
  var iFieldsetArquivos = aFieldsetArquivos.length;

  for ( var iIndiceArquivo = 0; iIndiceArquivo < iFieldsetArquivos; iIndiceArquivo++ ) {

    var oFieldsetArquivo = aFieldsetArquivos[iIndiceArquivo];
    var aFieldsetMensagens = oFieldsetArquivo.getElementsByTagName('fieldset');
    var iFieldsetMensagens = aFieldsetMensagens.length;

    for ( var iIndiceMensagem = 0; iIndiceMensagem < iFieldsetMensagens; iIndiceMensagem++ ) {

      var oFieldsetMensagem = aFieldsetMensagens[iIndiceMensagem];

      if ( !empty(oFieldsetMensagem.mensagemCarregada) ) {
        continue;
      }

      oFieldsetMensagem.style.display = sDisplay;
    }
  }

  oBotao.value = sLabelBoatao;
}

/**
 * Limpa historico
 * - recarrega tela passando por parametro variavel lLimparHistorico
 *
 * @access public
 * @return void
 */
function js_limparHistorico() {
  document.location.href = 'con4_mensagens002.php?lLimparHistorico=true';
}
</script>