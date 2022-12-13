<?php 

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("rh161_regist");
$clrotulo->label("z01_nome");
?>

<fieldset>
  <legend>Dados do assentamento de substituição</legend>
  <table class="form-container">
    <tr>
      <td><a href="javascript:void(0);" id="ancora_servidor_substituido">Servidor a substituir:</a>
      <td>
        <?php db_input('rh161_regist',      8, $Irh161_regist, true, 'text', $db_opcao, " data='rh01_regist' "); ?>
        <?php db_input('nome_substituido', 40, $Iz01_nome,     true, 'text', 3,         " data='z01_nome' "); ?>
      </td>
    </tr>
  </table>
</fieldset>
