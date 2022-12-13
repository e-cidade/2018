<?php 

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("h83_valor");
$clrotulo->label("h83_meses");
$clrotulo->label("h83_encargos");

if(!isset($db_opcao) || empty($db_opcao)) {
  $db_opcao = 1;
}
?>

<fieldset>
  <legend>Dados do Assentamento de RRA</legend>
  <table class="form-container" style="max-width:100px">
    <tr>
      <td>
        <label id="Lh83_valor" for="h83_valor">Valor Total Devido:</label>
      </td>
      <td>
        <?php db_input('h83_valor',  10, $Ih83_valor, true, 'text', $db_opcao,  " data='h83_valor' "); ?>
      </td>
    </tr>
    <tr>
      <td>
        <label id="Lh83_meses" for="h83_meses">Número de Meses:</label>
      </td>
      <td>
        <?php db_input('h83_meses', 10, $Ih83_meses, true, 'text', $db_opcao,   " data='h83_meses' "); ?>
      </td>
    </tr>
    <tr>
      <td>
        <label id="Lh83_encargos" for="h83_encargos">Encargos Judiciais:</label>
      </td>
      <td>
        <?php db_input('h83_encargos', 10, $Ih83_encargos, true, 'text', $db_opcao," data='h83_encargos' "); ?>
      </td>
    </tr>
  </table>
</fieldset>
