<?php


require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("dbforms/db_funcoes.php");


// Controle de sessao fake
if ( !empty($_POST) ) {

  db_logsmanual_demais('Atualização de cadastro pelo usuário na inicial', db_getsession('DB_id_usuario'), 0, 5292);


  $rsAccount = db_query('select currval(\'db_logsacessa_codsequen_seq\')');
  $idAccount = db_utils::fieldsMemory($rsAccount, 0)->currval;

  // seta o id do menu de alteração de usuarios
  db_putsession("DB_itemmenu_acessado", 5292);
  // seta o id do logsacessa para poder gerar account pelas classes
  db_putsession("DB_acessado", $idAccount);
  db_putsession("DB_datausu", time());
}


$cldb_usuarios = new cl_db_usuarios;
$cldb_usuarios->rotulo->label();

$oUsuario = new UsuarioSistema(db_getsession('DB_id_usuario'));

$oPost = db_utils::postMemory($_POST);

if ( isset($oPost->salvar) && !empty($oPost->email) ) {
  $oUsuario->setEmail($oPost->email);
  $oUsuario->salvar();

  db_putsession('DB_atualiza_cadastro', false);
  db_redireciona('instit.php');
}

if ( isset($oPost->cancelar) ) {      
  db_putsession('DB_atualiza_cadastro', false);
  db_redireciona('instit.php');
  exit;
}

$sEmail = $oUsuario->getEmail();
$email = $sEmail;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js, prototype.js, estilos.css");
  ?>
</head>

<body class="body-default">

  <div class="container">

    <div style="text-align: left; background-color: #fcf8e3;border: 1px solid #fcc888;padding: 10px">
      Seu cadastro está desatualizado!<br />Favor informe os dados para completar o cadastro.
    </div>

    <br />

    <form method="post" name="form1">

      <fieldset>
        <legend>Atualizar Cadastro</legend>

        <table>

          <tr>
            <td nowrap title="<?php echo $Temail; ?>">
              <label id="lbl_email" for="email"><?php echo $Lemail; ?></label>
            </td>
            <td><?php db_input('email', 50, $Iemail, true, "text", 1); ?></td>
          </tr>

        </table>

      </fieldset>

      <input type="submit" value="Salvar" name="salvar" onclick="return validaEmail(document.form1.email.value)" />
      <input type="submit" value="Cancelar" name="cancelar" />

    </form>

  </div>

</body>

</html>
<script type="text/javascript">



</script>