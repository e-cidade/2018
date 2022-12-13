<form name="form2" method="post" action="" class="container">
  <fieldset>
    <legend>Pesquisa de Conta Bancária</legend>

    <table class="form-container">
      <tr> 
        <td title="<?=$Tdb83_sequencial?>">
          <?=$Ldb83_sequencial?>
        </td>
        <td> 
        <?
          db_input("db83_sequencial",10,$Idb83_sequencial,true,"text",4,"","chave_db83_sequencial");
        ?>
        </td>
      </tr>
      <tr> 
        <td title="<?=$Tdb83_descricao?>">
        <?=$Ldb83_descricao?>
        </td>
        <td> 
        <?
          db_input("db83_descricao",40,$Idb83_descricao,true,"text",4,"","chave_db83_descricao");
        ?>
        </td>
      </tr>
      <tr> 
        <td title="<?=$Tdb83_conta?>">
        <?=$Ldb83_conta?>
        </td>
        <td> 
        <?
          db_input("db83_conta",10,$Idb83_conta,true,"text",4,"","chave_db83_conta");
        ?>
        </td>
      </tr>
      <tr> 
        <td>
          Mostrar Contas:
        </td>
        <td> 
        <?php 
          $aSelecoes = array(
            ""  => "Todas",
            "1" => "Não Vinculadas a Servidores/Pensionistas",
            "2" => "Vinculadas a Servidores/Pensionistas",
          );
          db_select("mostra_tipo_conta", $aSelecoes, true, 1, "onChange=\"document.getElementById('chave_tipo_conta').value = this.value;\"", "chave_tipo_conta");
          if ( !isset($mostra_tipo_conta) && isset($chave_tipo_conta) ) {
            $mostra_tipo_conta = $chave_tipo_conta;
          }
          
          db_input("chave_tipo_conta",1,$Idb83_conta,true,"hidden",4);
        ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
  <input name="limpar"    type="button" id="limpar"     value="Limpar" >
  <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_contabancaria.hide();">
<script>
(function(){

  var oBotaoLimpar      = document.getElementById('limpar');
  oBotaoLimpar.onclick  = function() {
     
    var oInputSequencial = document.getElementById('chave_db83_sequencial');
    var oInputDescricao  = document.getElementById('chave_db83_descricao');
    var oInputConta      = document.getElementById('chave_db83_conta');
    var oInputTipoConta  = document.getElementById('chave_tipo_conta');
    var oSelectTipoConta = document.getElementById('mostra_tipo_conta');

    oInputSequencial.value = '';
    oInputDescricao.value  = '';
    oInputConta.value      = '';

    oSelectTipoConta.value = oInputTipoConta.value;
    document.form2.submit();
  }
})();
  </script>
</form>
