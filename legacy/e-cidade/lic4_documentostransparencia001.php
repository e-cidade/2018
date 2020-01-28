<?php /*
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
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("estilos.css");
      db_app::load("widgets/DBToggleList.widget.js");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class='body-default'>
    <div class='container'>

    </div>
    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
    <div class="container">
        <form id="frmTipoDocumento" method="post" class="form-container">
            <fieldset>
                <legend>Documentos do Portal Transparência</legend>
                <div id="ctnToogleTipoDocumentos"></div>
            </fieldset>
            <input id="btnSalvar" type="button" value="Salvar" />
        </form>
    </div>
</body>
</html>
<script type="text/javascript">
    /**
     * RPC
     */
    var   sUrlRpc                          = 'documentostransparencia.RPC.php';
    const CAMINHO_MENSAGENS_TIPO_DOCUMENTO = 'patrimonial.licitacao.lic4_documentostransparencia001.';

    $('btnSalvar').onclick = function() {
        salvarTipoDocumentos();
    };

    /**
     * Cria o toogleList para manipular os tipos de documentos que serão enviados para o portal transparência
     */
    var headers = [{ sId: 'sTipoDocumento', sLabel: 'Tipo de Documentos', 'sWidth' : '350px' }];
    var oToogleTipoDocumentos = new DBToggleList( headers, {'selecao': 'Documentos a selecionar', 'selecionados': 'Documentos selecionados'} );
        oToogleTipoDocumentos.closeOrderButtons();
        oToogleTipoDocumentos.show( $('ctnToogleTipoDocumentos') );

    /**
    * Busca os tipos de documentos
    */
    function buscarTipoDocumentos() {

        var oParametro       = new Object();
        oParametro.exec      = 'buscaTipoDocumentos';

        var oDadosRequisicao        = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
        oDadosRequisicao.onComplete = retornoBuscarTipoDocumentos;

        js_divCarregando( _M( CAMINHO_MENSAGENS_TIPO_DOCUMENTO + "buscando_tipo_documento" ), "msgBox" );
        new Ajax.Request( sUrlRpc, oDadosRequisicao );
    }

    /**
    * Retorno dos tipos de documentos. Preenche o toogleList de acordo com o retornando,
    * separando tipos de documentos selecionados
    */
    function retornoBuscarTipoDocumentos( oResponse ) {

        js_removeObj( "msgBox" );
        var oRetorno = eval( '(' + oResponse.responseText + ')' );
        if ( oRetorno.iStatus != 1 ) {

            alert( oRetorno.sMessage.urlDecode() );
            return;
        }

        oToogleTipoDocumentos.clearAll();
        oRetorno.aTipoDocumentos.each(function( oTipoDocumento, iSeq ) {
            var oDadosTipoDocumentos = new Object();
            oDadosTipoDocumentos.iTipoDocumento = oTipoDocumento.iTipoDocumento;
            oDadosTipoDocumentos.sTipoDocumento = oTipoDocumento.sTipoDocumento.urlDecode();

            if ( oTipoDocumento.lSelecionado === true ) {
                oToogleTipoDocumentos.addSelected( oDadosTipoDocumentos );
            } else {
                oToogleTipoDocumentos.addSelect( oDadosTipoDocumentos );
            }
        });
        oToogleTipoDocumentos.renderRows();
        // oToogleTipoDocumentos.show( $('ctnToogleTipoDocumentos') );
    }

    /**
     * Salva os tipos de documentos selecionados que serão enviados para o portal transparência
    */
    function salvarTipoDocumentos() {

        var aTipoDocumentos = new Array();

        /**
        * Percorre as linhas selecionadas e adiciona o código do tipo de documento ao array a ser enviado para o RPC.
        */
        oToogleTipoDocumentos.getSelected().each(function( oTipoDocumento, iSeq ) {
            aTipoDocumentos.push( oTipoDocumento.iTipoDocumento );
        });

        var oParametro              = new Object();
        oParametro.exec             = 'salvarTipoDocumentos';
        oParametro.aTipoDocumentos  = aTipoDocumentos;

        var oDadosRequisicao        = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
        oDadosRequisicao.onComplete = retornoSalvarTipoDocumentos;

        js_divCarregando( _M( CAMINHO_MENSAGENS_TIPO_DOCUMENTO + "salvando_tipo_documentos" ), "msgBox" );
        new Ajax.Request( sUrlRpc, oDadosRequisicao );
    }

    /**
     * Retorno do salvar os vínculos de tipo de documentos
     */
    function retornoSalvarTipoDocumentos( oResponse ) {

      js_removeObj( "msgBox" );
      var oRetorno = eval( '(' + oResponse.responseText + ')' );
      alert( oRetorno.sMessage.urlDecode() );
      buscarTipoDocumentos();
    }

    buscarTipoDocumentos();
</script>