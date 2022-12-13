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
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>e-Cidade</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <link href="imagens/ecidade/favicon.png" rel="icon"  type="image/png" />
    <link href="estilos/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="estilos/login.css" rel="stylesheet" type="text/css"/>

    <script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/jquery-2.1.1.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/jquery-ui-1.10.4.custom.min.js"></script>
  </head>

  <body class="<?php echo $sClassAtiva;?>">

    <div class="container">

      <a href="http://www.softwarepublico.gov.br/ver-comunidade?community_id=15315976" title="Entre na comunidade e-Cidade no Portal do Software Público." target="_blank"><img class="logo-ecidade" src="imagens/ecidade/login/logotipo_ecidade.png"/></a>

      <form method="post" name="form1">

        <div class="access-fields">

          <?php if (isset($DB_CONEXAO)) { ?>

             <input id="servidor" name="servidor"  type="hidden" value="<?=@$servidor?>"/>
             <input id="port"     name="port"      type="hidden" value="<?=@$port?>"/>
             <input id="user"     name="user"      type="hidden" value="<?=@$user?>"/>
             <input id="senh"     name="senh"      type="hidden" value="<?=@$senh?>"/>
             <input id="base"     name="base"      type="hidden" value="<?=@$base?>"/>

            <label>Host:</label>

            <select name="serv" id="serv">
              <option name="condicaoservidor" value="">Selecione um servidor</option>

              <?php for( $iInd = 0; $iInd < count( $DB_CONEXAO ); $iInd++) { ?>
                      <option name="condicaoservidor" value="<?=$iInd?>"><?=$DB_CONEXAO[$iInd]["SERVIDOR"].":".$DB_CONEXAO[$iInd]["PORTA"] ?></option>
              <?php } ?>
            </select>

            <label>Base:</label>

            <div class="input" style="display:block;">
              <input type="text"   name="basename"   id="basename" onclick="this.value=''"/>
              <input type="hidden" name="idbasename" id="idbasename"/>
            </div>

          <?php } ?>

          <label>Login:</label>
          <input name="login" id="usu_login" type="text" placeholder="Informe seu login"/>

          <label>Senha:</label>
          <input name="senha" id="usu_senha" type="password" placeholder="Informe sua senha"/>

          <div id="captcha" class="container-captcha <?php echo ($lCaptcha ? '' : 'container-captcha-hide'); ?>">
            <?php include('captcha.php'); ?>
          </div>

          <input name="btnlogar" id="btnlogar" type="button" value="Entrar"/>
        </div>

        <div class="link-acesso">
          <?php echo ($lMostraLinkPrimeiroAcesso ? '<a href="primeiroAcesso.php">Primeiro acesso</a>' : ''); ?>
        </div>

        <span id="testaLogin"></span>

        <img class="logo-db" src="imagens/ecidade/login/logotipo_dbseller.png">

        <div class="social-midia">
          <p><a href="http://www.dbseller.com.br">www.dbseller.com.br</a><br/>Porto Alegre RS/Brasil</p>
          <a href="http://twitter.com/#!/DBSeller" target="_blank" title="Siga-nos no Twitter" class="icon-twitter"><img src="imagens/ecidade/login/icon_twitter.png"></a>
          <a href="http://www.facebook.com/pages/DBSeller-Sistemas-Integrados/168429383219644" target="_blank" title="Conheça nossa página no Facebook"><img src="imagens/ecidade/login/icon_facebook.png"></a>
        </div>

      </form>

    </div>
  </body>
  <script type="text/javascript">

  $( "#basename" ).autocomplete({
    source: function( request, response ) {

      $.ajax({
        url: "BuscaBase.RPC.php",
        data: {
          string   : $("#basename").val(),
          servidor : $("#serv").val()
        },
        type: "post",
        dataType: "json",
        success: function( data ) {
          response( $.map( data, function( item ) {
            return {
              label: decodeURIComponent( item.label ),
              value: decodeURIComponent( item.label ),
              codigo: decodeURIComponent( item.cod )
            }
          }));
        }
      });
    },
    minLength: 3,
    select: function( event, ui ) {

      if (ui.item.value == 0) {

        $('#basename').val('');
        return false;
      }

      aDadosConexao = decodeURIComponent( ui.item.codigo ).split(':');

      $('#servidor').val( aDadosConexao[0] );
      $('#port').val( aDadosConexao[1] );
      $('#user').val( aDadosConexao[2] );
      $('#senh').val( tagString(aDadosConexao[3]) );
      $('#base').val( aDadosConexao[4] );

      /**
       * Coloca o valor do label no campo
       */
      ui.item.value = ui.item.label;
    }
  });

  function js_acessar_dbportal() {

    /**
     * Limpa status de retorno
     */
    $('#testaLogin').html('');

    var sLogin = $('#usu_login').val();
    var sSenha = calcMD5( $('#usu_senha').val() );
    var wname  = 'wname' + Math.floor(Math.random() * 10000);
    var sQuery = "";

    if ( $('#servidor').length && $('#servidor').val() != "" ){

      sQuery += "&servidor=" + $('#servidor').val();
      sQuery += "&base="     + $('#base').val();
      sQuery += "&user="     + $('#user').val();
      sQuery += "&port="     + $('#port').val();
      sQuery += "&senha="    + $('#senh').val();
    }

    var oCaptcha = $('#captcha');
    var sAuth = btoa("DB_login="+sLogin+"&DB_senha="+sSenha).urlEncode();
    sUrl  = "abrir.php?sAuth=" + sAuth
            + ((oCaptcha) ?  '&conteudoCaptcha=' + $('#ct_captcha').val() : '')
            + sQuery;

    $('#usu_senha').val('')

    var jan  = window.open(sUrl,wname,'width=1,height=1');
  }

  $(document).ready(function() {

    $('#btnlogar').on('click', function(event) {
      js_acessar_dbportal();
    });

    $('#usu_senha').on('keyup', function(event){

      if (event.keyCode == 13) {
        js_acessar_dbportal();
      }
    });

    $('#ct_captcha').on('keyup', function(event){

      if (event.keyCode == 13) {
        js_acessar_dbportal();
      }
    });

    $("#usu_login").focus();
    js_verifica_cookie();
  });

  $(window).load(function() {

    /**
     * Ajusta e posiciona o container do formulario
     */
    var iHeightDocumento = $(window).innerHeight(),
        iHeightContainer = $('.container').innerHeight();

    if ((iHeightDocumento - iHeightContainer) > 0) {

      $('.container').css({
        'top' : parseInt((iHeightDocumento - iHeightContainer)/2) + 'px',
        'margin-top' : 0
      });
    }
  })

  </script>
</html>
