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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_db_usuarios_classe.php"));
require_once (modification("classes/db_db_usuacgm_classe.php"));
require_once (modification("classes/db_db_config_classe.php"));

$cldb_usuarios = new cl_db_usuarios;
$cldb_usuacgm  = new cl_db_usuacgm;
$cldb_config   = new cl_db_config;

db_postmemory($_POST);

$db_opcao = 22;
$db_botao = true;

if (isset($alterar)) {

  $sqlerro = false;
  db_inicio_transacao();

  if ( !empty( $senha ) ) {
    $cldb_usuarios->senha = Encriptacao::encriptaSenha( $senha );
  }
  $cldb_usuarios->nome   = "$z01_nome";
  $cldb_usuarios->usuext = "1";
  $cldb_usuarios->alterar($id_usuario);
  if($cldb_usuarios->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_usuarios->erro_msg;

  if ($sqlerro==false) {

    $cldb_usuacgm->id_usuario = $id_usuario;
    $cldb_usuacgm->cgmlogin   = $login;
    $cldb_usuacgm->alterar($id_usuario);
    if ($cldb_usuacgm->erro_status==0) {
      $sqlerro=true;
      $erro_msg = $cldb_usuacgm->erro_msg;
    }
  }

  if ($sqlerro==false) {

    $enviar = false;
    if (isset($enviaemail) && $enviaemail == "sim"){
      $enviar = true;
    }

    $res_instit = $cldb_config->sql_record($cldb_config->sql_query_file(null,"nomeinst,url,email as email_prefeitura", null, "prefeitura is true"));
    if ($cldb_config->numrows > 0){
      db_fieldsmemory($res_instit,0);
    }

    if (strlen(trim($url)) == 0){
      $url = null;
    }

    if ( !empty($email) ) {

      $enviado = $cldb_usuarios->enviar_senha($id_usuario,$email,$z01_nome,$login,$senha,$nomeinst,$url,$enviar, $email_prefeitura);
      if ($enviado == false){
        $erro_msg = "Erro ao enviar e-mail para usuario ".$z01_nome;
      }
    }
  }
  db_fim_transacao($sqlerro);

  $db_opcao = 2;
  $db_botao = true;
} elseif (isset($chavepesquisa) && trim(@$chavepesquisa)!="") {

  $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($chavepesquisa));

  if ($cldb_usuarios->numrows > 0){
       db_fieldsmemory($result,0);
       $z01_numcgm    = $login;
       $z01_nome      = $nome;
       $senha         = '';
       //$verificasenha = $senha;

       $db_opcao = 2;
       $db_botao = true;
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>

    <?php
      include(modification("forms/db_frmdb_usuariosext.php"));
      db_menu();
    ?>
  </body>
</html>
<?php

if (isset($alterar)) {

  if (trim(@$erro_msg)!="") {
       db_msgbox($erro_msg);
  }
  if ($sqlerro==true) {

    if ($cldb_usuarios->erro_campo!="") {

      echo "<script> document.form1.".$cldb_usuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_usuarios->erro_campo.".focus();</script>";
    }
  } else {
    $db_opcao = 22;
  }
}

if ($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
