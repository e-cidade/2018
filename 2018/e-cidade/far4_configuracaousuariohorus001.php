<?php 
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016 DBselller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oDao = new cl_horususuario();
$sSql = $oDao->sql_query_file(
    null, 
    "*", 
    null, 
    "fa66_unidade = " . db_getsession("DB_coddepto")
  );

$rsSql = db_query($sSql);

$sErro = '';
$iSequencial  = null;
$fa66_senha   = null;
$fa66_usuario = null;

if (!$rsSql) {
  $sErro = "Erro ao buscar os dados do hórus do departamento atual.";
}

if ($objeto = db_utils::fieldsMemory($rsSql, 0) ) {
  $iSequencial  = $objeto->fa66_sequencial;
  $fa66_senha   = $objeto->fa66_senha  ;
  $fa66_usuario = $objeto->fa66_usuario;
}

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      /**
       * Default
       */
      $aLibs   = array("scripts.js");
      $aLibs[] = "prototype.js";
      $aLibs[] = "AjaxRequest.js";
      $aLibs[] = "strings.js";
      $aLibs[] = "estilos.css";
      db_app::load(implode(",", $aLibs));
    ?>
  </head>
  <body>
  <?php 

    try {
      new UnidadeProntoSocorro(db_getsession("DB_coddepto"));
    } catch(\Exception $e) {
      die(
        "<div class='container'><h2>{$e->getMessage()}</h2></div>"
      );
    }
  ?>

  <form class="container" id="formularioPassagens" method="post">
   
    <fieldset>

      <legend>
        Dados de Acesso ao Hórus
      </legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="fa66_usuario">
              Usuário:
            </label>
          </td>
          <td>
          <input id="fa66_usuario" name="fa66_usuario" maxlength="100" class="field-size5" value="<?= $fa66_usuario; ?>"/>
          <td>
        </tr>

        <tr>
          <td>
            <label for="fa66_senha">
              Senha:
            </label>
          </td>
          <td>
            <input id="fa66_senha" name="fa66_senha" maxlength="40" class="field-size5" value="<?= $fa66_senha; ?>"/>
          <td>
        </tr>

      </table>  
    </fieldset>
    <input type="submit" value="Salvar"   id="salvar" />    
    <input type="button" value="Limpar"   id="limpar" />
  </form>
    <?php
     db_menu();
    ?>
  </body>
  <script>
    $('limpar').observe('click', function(){
      $("fa66_usuario").setValue('');
      $("fa66_senha").setValue('');
    });
    var validacao = function(event){

      var regex = /^([0-9a-z\.\-_])+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
      
      if (!regex.test($F('fa66_usuario'))) {
        alert('O campo Usuário deve ser um endereço de email válido.')
        $('fa66_usuario').focus();
        event.preventDefault();
        event.stopImmediatePropagation();
        return;
      }
      return;
    }; 
   document.forms[0].observe('submit', validacao);
   $('fa66_usuario').observe('change', validacao);

  </script>
</html>
<?php 

  if(count($_POST) > 0) {
      
    $oPost = db_utils::postMemory($_POST);
    $oDao  = new cl_horususuario();
    $oDao->fa66_usuario  = $oPost->fa66_usuario;
    $oDao->fa66_senha    = $oPost->fa66_senha;
    $oDao->fa66_unidade  = db_getsession('DB_coddepto') ;
    
    if ($iSequencial) {
      $oDao->fa66_sequencial = $iSequencial;
      $oDao->alterar($iSequencial);
    } else {
      $oDao->incluir(null);
    }
   
    if ($oDao->erro_status == "0") {
      db_msgbox("Erro ao salvar os dados do usuário");
    } else {
      db_redireciona('');
    }
  }
