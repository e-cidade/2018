<input type="hidden" id="usuario" value="<?php echo $oUsuarioSistema->getIdUsuario(); ?>" />

<label>Login:</label>
<input size="15" type="text" readonly="readonly" value="<?php echo $oUsuarioSistema->getLogin(); ?>"/>

<label id="lbl_senha" for="senha">Senha:</label>
<input size="10" type="password" id="senha" name="senha" onKeyUp="return js_forcaDaSenha();"/>


<label id="lbl_confirmasenha" for="confirmasenha">Confirmar Senha:</label>
<input size="10" type="password" id="confirmasenha" name="confirmasenha"/>
<label>Força da senha:&nbsp;</label><label class="text-left"><span id="forcaSenha"></span></label>

<input name="acesso" type="button" id="formAcesso" value="Salvar" />

<script type="text/javascript">

  $('#formAcesso').on('click', function(event) {

    if ($('#senha').val().length < 6) {

      alert( "Campo Senha deve conter no mínimo 6 caracteres." )
      $('#senha').focus()
      return false
    }

    if ($('#senha').val() != $('#confirmasenha').val()) {

      alert( "Senha não confere." )
      $('#senha').focus()
      return false
    }


    var oDados = {
          exec : "alteraSenha",
          senha : calcMD5( $('#senha').val() ),
          id_usuario : $('#usuario').val()
        };

    $.ajax({
      url : "primeiroAcesso.RPC.php",
      data : {
        json : JSON.stringify( oDados )
      },
      type : "POST",
      dataType : "JSON",
      success : function( oResponse ) {

        var oJson = oResponse;

        alert( oJson.sMessage.urlDecode() )

        if (oJson.iStatus == 2) {
          return false;
        }

        window.location = "login.php";
      }
    })

  });

  /**
   * Verifica força de senha do campo senha
   * @return void
   */
  function js_forcaDaSenha() {

    var oCampoForca       = $("#forcaSenha");
    var oCampoSenha       = $("#senha");

    var oForte            = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var oMedio            = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var oPoucosCaracteres = new RegExp("(?=.{6,}).*", "g");

    if (false == oPoucosCaracteres.test( oCampoSenha.val() ) ) {

      oCampoForca.html( "<span style='color:red;'>Poucos Caracteres</span>" )
    } else if ( oForte.test( oCampoSenha.val() ) ) {

      oCampoForca.html( "<span style='color:blue;'>Forte</span>" )
    } else if ( oMedio.test( oCampoSenha.val() ) ) {

      oCampoForca.html( "<span style='color:orange;'>Média</span>" )
    } else {

      oCampoForca.html( "<span style='color:red;'>Fraca</span>" )
    }
  }

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