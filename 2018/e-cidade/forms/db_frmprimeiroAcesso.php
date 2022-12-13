  <label id="lbl_cpf" for="cpf">CPF:</label>
  <input  maxlength="11" autocomplete="off" size="14" id="cpf" name="cpf"
          onkeyup="return js_onlyNumbers(this);" onblur="return js_onlyNumbers(this);" />

  <label id="lbl_data_nascimento" for="data_nascimento">Data de Nascimento:</label>
  <input autocomplete="off" size="8" maxlength="10" id="data_nascimento" name="data_nascimento" onblur="return js_data(this);"/>

  <label id="lbl_email" for="email">E-mail:</label>
  <input autocomplete="off" size="35" id="email" name="email" />

  <input name="acesso" type="button" id="formAcesso" value="Enviar" />

  <script type="text/javascript">

    $('#formAcesso').on('click', function(event) {

      if ($('#data_nascimento').val().length < 10) {

        alert("Data de Nascimento inválida.")
        return false
      }

      var oDados = {
          exec           : "validaParametros",
          cpf            : $('#cpf').val(),
          datanascimento : $('#data_nascimento').val().replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$2-$1"),
          email          : $('#email').val()
        };

      $.ajax({
        url: "primeiroAcesso.RPC.php",
        data: {
          json :  JSON.stringify( oDados )
        },
        type: "post",
        dataType: "json",
        success: function( oResponse ) {

          var oJson = oResponse;

          if (oJson.iStatus == 2) {
            alert( oJson.sMessage.urlDecode() );
            return false;
          }

          window.location = "primeiroAcesso.php?_=" + oJson.dDataToken
        }
      });
    });

    function js_onlyNumbers(oElemento) {

      oElemento.value = oElemento.value.replace(/[^0-9]/g, '')
    }

    $('#data_nascimento').on('keyup', function(event) {

      if (event.keyCode != 8 && event.keyCode != 46) {
        js_data(this)
      }
    })

    function js_data(oElemento) {
      oElemento.value = oElemento.value.replace(/[^0-9]/g, '').replace(/(\d{2})(\d{0,2})(\d{0,4})/, "$1/$2/$3")
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

    <?php if (isset($sErro)): ?>
      alert("<? echo $sErro; ?>")
    <?php endif; ?>
  </script>