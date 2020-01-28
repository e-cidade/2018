<style>
  .container-foto {
  
    vertical-align: top;
    display   : inline-block;
  }

  .container-foto img {

    width:   95;
    height: 120;
  }

  .container-biometria {
    vertical-align: top;
    margin-top: 10px;
    text-align: center;
  }
</style>

<div class="container-biometria">

  <div class="container-foto">
    <fieldset>
      <legend>Nova Foto</legend>
      <input id="biometria_foto_nova" name="biometria_foto_nova" />
    </fieldset>

    <input value="Limpar Foto" type="button" id="limpar_foto" disabled /> 

  </div>

  <div class="container-foto">
    <fieldset>
      <legend>Foto Atual</legend>
      <input id="biometria_foto_atual" name="biometria_foto_atual" disabled />
    </fieldset>
    <input id="remover_foto" value="Remover" type="button" />
  </div>
</div>

<script type="text/javascript">

/**
 * Quando a tela for carregada preencherá os dados na tela
 */
callbackCarregamento.biometria = function(dados_biometricos, dados_padrao) {
  
  if(!dados_biometricos) {
    return;
  }

  biometria.foto_atual.setValue(dados_biometricos.foto_oid);
}

var biometria = {
  'foto_atual': new DBInputFoto($('biometria_foto_atual')),
  'foto_nova':  new DBInputFoto($('biometria_foto_nova'))
};

document.addEventListener("DOMContentLoaded", function(event) {
  
  /**
   * Foto nova
   */
  $('limpar_foto').disabled = !biometria.foto_nova.getValue();

  $('biometria_foto_nova').observe("change", function(){
    $('limpar_foto').disabled = !biometria.foto_nova.getValue();
  });

  $('limpar_foto').observe('click', function() {
    biometria.foto_nova.setValue('');
  })


  $('remover_foto').observe('click', function() {
    biometria.foto_atual.setValue('');
  });


  validacoes.push(function(){
    return true;
  });
  
});

/**
 * Seta os atributos dos campos a serem enviados para salvar
 * @param oParametros
 */
function setValoresBiometria( oParametros ) {

  oParametros.biometria = {
    'foto_nova_caminho'  : biometria.foto_nova.getValue(),
    'foto_atual_caminho' : biometria.foto_atual.getValue()
  }
}
</script>