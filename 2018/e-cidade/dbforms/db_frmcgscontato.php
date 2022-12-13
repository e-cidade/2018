<fieldset class="separator">

  <legend>
    Correio eletrônico  
  </legend>
  <table class="form-container">
    <tr>
      <td>
        <label for="contato_email">E-mail:</label>
      </td>
      <td>
        <input id="contato_email" name="contato_email" class="field-size-max"/>
      </td>
    </tr>
  </table>

</fieldset>

<fieldset class="separator">

  <legend>
    Telefones  
  </legend>
  <table class="form-container">
    <tr>
      <td>
        <label for="contato_telefone_fixo">Telefone Fixo:</label>
      </td>
      <td>
        <input id="contato_telefone_fixo" name="contato_telefone_fixo" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="contato_telefone_celular">Telefone Celular:</label>
      </td>
      <td>
        <input id="contato_telefone_celular" name="contato_telefone_celular" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="contato_fax">Fax:</label>
      </td>
      <td>
        <input id="contato_fax" name="contato_fax" />
      </td>
    </tr>
  </table>
</fieldset>

<fieldset class="separator">

  <legend>Endereço</legend>
  <table class="form-container">
    <tr>
      <td>
        <label>Endereço Principal:</label>
      </td>
      <td>
        <input name="contato_endereco_principal" id="contato_endereco_principal" />
      </td>
    </tr>
  </table>
</fieldset>

<script>

  var contato = {
    'telefone_fixo'     : new DBInputTelefone($('contato_telefone_fixo')),
    'telefone_celular'  : new DBInputTelefone($('contato_telefone_celular')),
    'fax'               : new DBInputTelefone($('contato_fax')),
    'email'             : new DBInputEmail($('contato_email')),
    'endereco_principal': new DBInputEndereco($('contato_endereco_principal'), true)
  };

  /**
   * Quando a tela for carregada preencherá os dados na tela
   */
  callbackCarregamento.contatos = function(dados_contato, dados_padrao) {
    
    if(!dados_contato) {
      return;
    }
    
    contato.email.setValue(dados_contato.email);    

    contato.telefone_fixo.setValue(dados_contato.telefone_fixo);
    contato.telefone_celular.setValue(dados_contato.telefone_celular);
    contato.fax.setValue(dados_contato.fax);

    contato.endereco_principal.setValue(dados_contato.endereco);
  }

  document.addEventListener("DOMContentLoaded", function(event) {

    validacoes.push(function(){

      if(!contato.telefone_fixo.getValue() && !contato.telefone_celular.getValue()) {

        alert( _M( MENSAGENS_MANUTENCAO_CGS + 'contato_telefone_vazio' ) );
        
        oDBAba.mostraFilho(oAbaContatos);
        contato.telefone_fixo.inputElement.focus();
        return false;
      }

      if(contato.email.getValue() && !contato.email.isValid()) {

        alert( _M( MENSAGENS_MANUTENCAO_CGS + 'contato_email_invalido' ) );

        oDBAba.mostraFilho(oAbaContatos);
        contato.telefone_fixo.inputElement.focus();
        return false;
      }

      if(!contato.endereco_principal.getValue()) {

        alert( _M( MENSAGENS_MANUTENCAO_CGS + 'contato_endereco_deve_ser_informado' ) );

        oDBAba.mostraFilho(oAbaContatos);
        contato.endereco_principal.botaoLancar.focus();
        return false;
      }

      return true;
    });
  });

  /**
   * Seta os atributos dos contatos a serem salvos
   * @param oParametros
   */
  function setValoresContatos( oParametros ) {

    oParametros.contato = {
      'telefone_fixo'     : contato.telefone_fixo.getValue(),
      'telefone_celular'  : contato.telefone_celular.getValue(),
      'fax'               : contato.fax.getValue(),
      'email'             : contato.email.getValue(),
      'endereco_principal': contato.endereco_principal.getValue()
    };
  }

</script>