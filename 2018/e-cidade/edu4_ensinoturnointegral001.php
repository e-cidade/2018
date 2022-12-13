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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("estilos.css");
      db_app::load("widgets/DBToggleList.widget.js");
    ?>
  </head>
  <body>
    <div class="container">
      <form id="frmEnsinoIntegral" method="post" class="form-container">
        <fieldset style="width: 800px;">
          <legend>Ensinos</legend>
          <div id="ctnToogleEnsinos"></div>
        </fieldset>
        <input id="btnSalvar" type="button" value="Salvar" />
      </form>
    </div>
  </body>
</html>
<script>
/**
 * RPC
 */
var   sUrlRpc                                 = 'edu4_ensino.RPC.php';
const CAMINHO_MENSAGENS_ENSINO_TURNO_INTEGRAL = 'educacao.secretariaeducacao.edu4_ensinoturnointegral001.';

/**
 * Cria o toogleList para manipular os ensinos considerados infantis
 */
var oToogleEnsinos = new DBToggleList( [{ sId: 'sEnsino', sLabel: 'Ensino' }] );
    oToogleEnsinos.closeOrderButtons();
    oToogleEnsinos.show( $('ctnToogleEnsinos') );

$('btnSalvar').onclick = function() {
  salvarVinculoEnsinos();
};

/**
 * Busca os ensinos cadastrados
 */
function buscarEnsinos() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'buscaEnsinos';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoBuscarEnsinos;

  js_divCarregando( _M( CAMINHO_MENSAGENS_ENSINO_TURNO_INTEGRAL + "buscando_ensinos" ), "msgBox" );
  new Ajax.Request( sUrlRpc, oDadosRequisicao );
}

/**
 * Retorno dos ensinos. Preenche o toogleList de acordo com o retornando, separando ensinos que são infantil
 */
function retornoBuscarEnsinos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  oToogleEnsinos.clearAll();
  oRetorno.aEnsinos.each(function( oEnsino, iSeq ) {

    var oDadosEnsino = new Object();
        oDadosEnsino.iEnsino = oEnsino.iEnsino;
        oDadosEnsino.sEnsino = oEnsino.sEnsino.urlDecode();

    if ( oEnsino.lInfantil === true ) {
      oToogleEnsinos.addSelected( oDadosEnsino );
    } else {
      oToogleEnsinos.addSelect( oDadosEnsino );
    }
  });

  oToogleEnsinos.show( $('ctnToogleEnsinos') );
}

/**
 * Salva os ensinos selecionados como infantil
 */
function salvarVinculoEnsinos() {

  var aEnsinosInfantil = new Array();
  
  /**
   * Percorre as linhas selecionadas e adiciona o código do ensino ao array a ser enviado para o RPC.
   */
  oToogleEnsinos.getSelected().each(function( oEnsino, iSeq ) {
    aEnsinosInfantil.push( oEnsino.iEnsino );
  });
   
  var oParametro                  = new Object();
      oParametro.sExecucao        = 'salvarVinculoEnsinosInfantil';
      oParametro.aEnsinosInfantil = aEnsinosInfantil;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoSalvarVinculoEnsinos;

  js_divCarregando( _M( CAMINHO_MENSAGENS_ENSINO_TURNO_INTEGRAL + "salvando_vinculos" ), "msgBox" );
  new Ajax.Request( sUrlRpc, oDadosRequisicao );
}

/**
 * Retorno do salvar os vínculos de ensino infantil
 */
function retornoSalvarVinculoEnsinos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  alert( oRetorno.sMensagem.urlDecode() );
  buscarEnsinos();
}

buscarEnsinos();
</script>