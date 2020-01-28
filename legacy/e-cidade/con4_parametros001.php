<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");

/**
 * Busca todos os arquivos disponiveis
 */
$oSkin  = new SkinService();
$aSkins = $oSkin->getSkins();

/**
 * Busca Preferencia Default Cliente
 */
$oPreferenciaCliente = new PreferenciaCliente();
$sSkinDefault        = $oPreferenciaCliente->getSkinDefault();
$tentativasLogin     = $oPreferenciaCliente->getTentativasLogin();
$diasExpiracaoToken  = $oPreferenciaCliente->getDiasExpiraToken();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css, scripts.js, strings.js, prototype.js, widgets/DBToogle.widget.js");
    ?>
  </head>
  <body class="body-default">
    <div class="container" style="width:700px !important;">
      <form name="form1" method="post" action="" enctype="multipart/form-data">

        <fieldset>

          <legend>Manutenção de Parâmetros</legend>

          <fieldset>
            <legend>Temas</legend>

            <table>
              <tr>
                <td nowrap title="Skin Padrão" style="width:137px;">
                  <label class="bold" for="sSkinDefault" id="lbl_sSkinDefault">Tema Padrão:</label>
                </td>
                <td>
                  <?php db_select('sSkinDefault', $aSkins, true, 1,"style='width:172px;'") ?>
                </td>
              </tr>
            </table>

            <fieldset id="parametrosSkinUsuario">
              <legend>Alterar Tema dos usuários</legend>

              <table>
              <tr>
                <td nowrap title="Skin Padrão"class="field-size3">
                  <label class="bold" for="sSkinDefaultUsuario" id="lbl_sSkinDefaultUsuario">Tema:</label>
                </td>
                <td class="field-size5">
                  <?php db_select('sSkinDefaultUsuario', $aSkins, true, 1,"style='width:172px;'") ?>
                </td>
                <td>
                  <input type="button" name="btnAplicarTodos" id="btnAplicarTodos" value="Aplicar a Todos" onclick="return js_aplicarTodos();">
                </td>
              </tr>
              </table>
            </fieldset>

          </fieldset>

          <fieldset>
            <legend>Segurança</legend>

            <table>
              <tr>
               <td nowrap title="Tentativas de Login" class="field-size3">
                 <label class="bold" for="tentativasLogin" id="lbl_tentativasLogin">Tentativas de Login:</label>
               </td>
               <td>
                 <?php db_input('tentativasLogin', 5, 4, true, 'text', 1, ''); ?>
               </td>
              </tr>

              <tr>
               <td nowrap title="Dias para expirar link de ativação" class="field-size3">
                 <label class="bold" for="diasExpiracaoToken" id="lbl_diasExpiracaoToken">Dias para expirar link de ativação:</label>
               </td>
               <td>
                 <?php db_input('diasExpiracaoToken', 5, 4, true, 'text', 1, ''); ?>
               </td>
              </tr>
            </table>

          </fieldset>

        </fieldset>

        <input name="incluir" type="submit" id="db_opcao" value="Salvar" onclick="return js_processar();">

      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script type="text/javascript">

    /**
     * Toogle do Skin do Usuário
     */
    var oSkinUsuario     = new DBToogle('parametrosSkinUsuario',false);

    var sUrlRPC          = 'con4_parametros.RPC.php';
    var sCaminhoMensagem = 'configuracao.configuracao.con4_parametros001.'

    /**
     * Salvamos os parametros
     */
    function js_processar() {
       
      if ( $F('tentativasLogin') == '') {

        alert( _M( sCaminhoMensagem + 'preenchimento_tentativasLogin_obrigatorio') );
        return false;
      }
      
      if ( $F('tentativasLogin') == 0) {

        alert( _M( sCaminhoMensagem + 'valida_zero', {'sCampo' : 'Tentativas de Login'}));
        return false;
      }

      if ( $F('diasExpiracaoToken') == '' ) {

        alert( _M( sCaminhoMensagem + 'preenchimento_diasExpiracaoToken_obrigatorio') );
        return false;
      }

      if ( $F('diasExpiracaoToken') == 0) {
        
        alert( _M( sCaminhoMensagem + 'valida_zero', {'sCampo' : 'Dias para expirar link de ativação'}));
        return false;
      }

      var oParametros               = new Object();
      oParametros.exec              = 'salvar';
      oParametros.sSkinDefault      = $('sSkinDefault').value;
      oParametros.iTentativasLogin  = $F('tentativasLogin');
      oParametros.iDiasExpiraToken  = $F('diasExpiracaoToken');

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax){

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {

          alert( oRetorno.sMessage.urlDecode() );
          return;
        }

        alert( oRetorno.sMessage.urlDecode() );
        return;
      }

      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
    }

    /**
     * Aplicar Skin a TODOS os Usuários
     */
    function js_aplicarTodos() {

       if( !confirm( _M( sCaminhoMensagem + 'deseja_aplicar_a_todos') ) ) {
         return false;
       }

      var oParametros               = new Object();
      oParametros.exec              = 'aplicarSkinDefault';
      oParametros.sSkinDefault      = $('sSkinDefaultUsuario').value;

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax){

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {

          alert( oRetorno.sMessage.urlDecode() );
          return;
        }

        alert( oRetorno.sMessage.urlDecode() );
        return;
      }

      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
    }
  </script>
</html>