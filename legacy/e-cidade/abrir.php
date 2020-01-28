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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/configuracao/Encriptacao.model.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));
require_once(modification("model/configuracao/Preferencia.model.php"));
require_once(modification("model/configuracao/PreferenciaCliente.model.php"));

Modification::find();

$stdClass = new db_stdClass();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if (isset($sAuth)) {
  parse_str( base64_decode($sAuth) );
}

define( 'MENSAGEM', 'configuracao.configuracao.abrir.' );

if ( isset($servidor) &&
     $servidor != ""  &&
     isset($base)     &&
     $base     != ""     ){

    $DB_SERVIDOR = $servidor;
    $DB_BASE     = $base;
    $DB_PORTA    = $port;
    $DB_USUARIO  = base64_decode($user);
    $DB_SENHA    = base64_decode($stdClass->db_stripTagsJson($senha));
}

$oParametrosMsg         = new stdClass();
$oParametrosMsg->sCampo = $DB_login;

if (strlen($DB_login)==0) {

  $sMsg     = _M( MENSAGEM . "login_invalido" );
  $sMsgLogs = _M( MENSAGEM . "logs_login_invalido", $oParametrosMsg );
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"error\">{$sMsg}</div>';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}

if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

  $oParametrosMsg              = new stdClass();
  $oParametrosMsg->sParametros = "(host={$DB_SERVIDOR} dbname={$DB_BASE} port={$DB_PORTA} user={$DB_USUARIO} password={$DB_SENHA}) - Login: {$DB_login}";
  $sMsg                        = _M( MENSAGEM . "conexao_invalida" );
  $sMsgLogs                    = _M( MENSAGEM . "logs_conexao_invalida", $oParametrosMsg );
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"error\">{$sMsg}</div>';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
  exit;
}

@db_query($conn, "select fc_startsession()");

db_logsmanual_demais( _M( MENSAGEM . "abrindo_sistema", $oParametrosMsg ) );

/**
 * Valida Tentativas de login do usuário
 *
 * Buscamos o parametro de configuração do número de
 * tentativas de acesso ao portal
 */
$oPreferenciaCliente     = new PreferenciaCliente();
$iTentativasLoginCliente = $oPreferenciaCliente->getTentativasLogin();

$oTentativasAcesso       = new stdClass();

$sLogin                  = $DB_login;
$sSenha                  = $DB_senha;

session_start();

/**
 * Verificamos se existe outra sessao ja registrada e caso exista
 * efetua o unset e o destroy da mesma
 */
if( !empty($_SESSION['DB_id_usuario'] ) ){

  session_unset();
  session_destroy();
  session_start();

  $DB_login = $sLogin;
  $DB_senha = $sSenha;
}

/**
 * Verificamos se existe a variavel de tentativ de acesso na sessao
 */
if ( !empty($_SESSION['DB_tentativasAcesso'] ) ) {
  $oTentativasAcesso = DB_getsession('DB_tentativasAcesso');
}

$iTotalTentativas = array_sum((array) $oTentativasAcesso);

/**
 * Verifica se a variável foi setada
 */
$lUtilizaCaptcha = (isset($lUtilizaCaptcha) && $lUtilizaCaptcha);

if ($lUtilizaCaptcha && $iTotalTentativas >= 3) {

  require_once(modification('securimage/securimage.php'));
  $oImagem = new Securimage();

  ?>
    <script>

      window.opener.document.getElementById('ct_captcha').value = '';

      var oCaptcha = window.opener.document.getElementById('captcha');

      if (oCaptcha) {

        oCaptcha.classList.remove("container-captcha-hide");
        window.opener.reloadCaptcha();
      }
    </script>
  <?php

  if ($iTotalTentativas > 3 && !$oImagem->check($conteudoCaptcha)) {

    $sMsg = _M( MENSAGEM . "codigo_seguranca_invalido" );

    ?>
      <script>

        window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"error\"><?php echo $sMsg; ?></div>';
        window.close();

      </script>
    <?php

    exit;
  }
}

if ( !empty($oTentativasAcesso->$DB_login) ) {

  $oTentativasAcesso->$DB_login = $oTentativasAcesso->$DB_login + 1;

  /**
   * Validamos se o numero de tentativas excedeu o numero
   * limite configurado
   */
  if( $oTentativasAcesso->$DB_login > $iTentativasLoginCliente ){

    /**
     * Bloqueamos o usuário
     */
    $sSqlBloqueiaUsuario  = "update db_usuarios                  ";
    $sSqlBloqueiaUsuario .= "   set usuarioativo  = 2            ";
    $sSqlBloqueiaUsuario .= " where login         = '{$DB_login}'";
    $sSqlBloqueiaUsuario .= "   and usuarioativo  = 1            ";
    $sSqlBloqueiaUsuario .= "   and administrador = 0            ";
    $rsBloqueiaUsuario    = db_query( $conn, $sSqlBloqueiaUsuario );
    $sHtmlRetorno         = '';

    if( $rsBloqueiaUsuario ){

      $sMsg         = _M( MENSAGEM . "excedeu_tentativas_acesso" );
      $sHtmlRetorno = "window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"error\">{$sMsg}</div>';";
    }

    echo "<script>{$sHtmlRetorno}window.opener.document.form1.usu_login.focus();window.close()</script>\n";
    exit;
  }
}else{

  session_register("DB_tentativasAcesso");
  $oTentativasAcesso->$DB_login = 1;
}
db_putsession( "DB_tentativasAcesso", $oTentativasAcesso );

/**
 * Habilita acesso apenas para usuarios do e-cidade usuext = 0 negando para:
 * 1 - Usuário Externo
 * 2 - Perfil
 */
$sSql  = "select *                     \n";
$sSql .= "  from db_usuarios           \n";
$sSql .= " where usuarioativo <> '0'   \n";
$sSql .= "   and usuext not in (1,2)   \n";
$sSql .= "   and login = '{$DB_login}' \n";
$result = db_query($conn, $sSql);

if ($DB_login != 'dbseller' && pg_num_rows($result) > 0 && pg_result($result,0,"administrador") != 1 ) {

  $result1 = db_query($conn,"select db21_ativo from db_config where prefeitura = true") or die("Erro ao verificar se sistema está liberado! Contate suporte!");
  $ativo   = pg_result($result1,0,0);

  if ($ativo == 3) {

    $sMsg = _M( MENSAGEM . "sistema_desativado" );
    db_logsmanual_demais( $sMsg );
    echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"warning\">{$sMsg}</div>';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
    exit;
  }else if ($ativo == 2) {

    $sMsg     = _M( MENSAGEM . "acesso_negado" );
    $sMsgLogs = _M( MENSAGEM . "logs_acesso_negado", $oParametrosMsg );
    db_logsmanual_demais( $sMsgLogs );
    echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '<div class=\"warning\">{$sMsg}</div>';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
    exit;
  }
}

$sSql    = "select * from db_depusu";
$result1 = db_query( $conn, $sSql ) or die($sSql);

if( pg_numrows($result) == 0 or pg_numrows($result1) == 0 ) {

  if( $DB_login == 'dbseller' &&  pg_numrows($result) == 0 ){

    db_logsmanual_demais( _M( MENSAGEM . "logs_registro_sistema", $oParametrosMsg ) );
    include(modification('con4_registrasistema.php'));
    exit;
  }else{

    if( pg_numrows($result1) == 0 ){

      $sMsg     = _M( MENSAGEM . "login_sem_departamento" );
      $sMsgLogs = _M( MENSAGEM . "logs_login_sem_departamento", $oParametrosMsg );
      db_logsmanual_demais( $sMsgLogs );
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '{$sMsg}';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
    }else{

      $sMsg = _M( MENSAGEM . "login_invalido" );
      db_logsmanual_demais( $sMsg );
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '{$sMsg}';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
    }

    exit;
  }

} else {

  $oUsuario = db_utils::fieldsMemory($result, 0);

  // valida data limite para login
  if (!empty($oUsuario->dataexpira) && strtotime($oUsuario->dataexpira) < strtotime(date('Y-m-d'))) {

    db_logsmanual_demais( _M( MENSAGEM . 'logs_data_expira', $oParametrosMsg ), $oUsuario->id_usuario );
    $sMsg = _M( MENSAGEM . 'data_expira' );
    ?>
      <script type="text/javascript">

        window.opener.document.getElementById('testaLogin').innerHTML = '<?=$sMsg?>';
        window.opener.document.form1.usu_login.focus();
        window.opener.document.form1.usu_senha.value = '';
        window.close();
      </script>
    <?php

    exit;
  }

  if (Encriptacao::hash( $DB_senha ) != $oUsuario->senha) {

    db_logsmanual_demais( _M( MENSAGEM . 'logs_senha_invalida', $oParametrosMsg ), $oUsuario->id_usuario );
    $sMsg = _M( MENSAGEM . 'senha_invalida' );
    ?>
      <script type="text/javascript">

        window.opener.document.getElementById('testaLogin').innerHTML = '<?=$sMsg?>';
        window.opener.document.form1.usu_login.focus();
        window.opener.document.form1.usu_senha.value = '';
        window.close();
      </script>
    <?php

    exit;
  }



  if ($oUsuario->usuarioativo != 1) {

    $sMsg = _M( MENSAGEM . 'usuario_bloqueado' );
    ?>
      <script type="text/javascript">
        window.opener.document.getElementById('testaLogin').innerHTML = '<?=$sMsg?>';
        window.opener.document.form1.usu_login.focus();
        window.close();
      </script>
    <?php

    exit;
  }

  /**
   * Desregistramos a variavel que controla as tentativas de acesso
   */
  session_unregister("DB_tentativasAcesso");

  echo "<script>window.opener.document.getElementById('captcha').classList.add('container-captcha-hide');</script>";
  echo "<script>if(window.opener==null)location.href='index.php';</script>";

  if(!session_is_registered("DB_acessado")){
     session_register("DB_acessado");
  }

  session_register("DB_login");
  session_register("DB_id_usuario");
  session_register("DB_administrador");
  session_register("DB_preferencias_usuario");

  db_putsession( "DB_login",         $DB_login );
  db_putsession( "DB_id_usuario",    pg_result($result,0,"id_usuario") );
  db_putsession( "DB_administrador", pg_result($result,0,"administrador") );

  /**
   * Realiza a busca das preferências do usuário.
   */
  $oUsuarioSistema = new UsuarioSistema( pg_result( $result, 0, "id_usuario" ) );
  $sPreferencias   = serialize($oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario());
  db_putsession("DB_preferencias_usuario", base64_encode($sPreferencias));

  session_register("DB_ip");
  if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){

    /** [Extensao] Tratamento IP */
    db_putsession("DB_ip",$_SERVER["HTTP_X_FORWARDED_FOR"]);

  }else{
    db_putsession("DB_ip",$HTTP_SERVER_VARS["REMOTE_ADDR"]);
  }

  session_register("DB_base");
  session_register("DB_NBASE");
  session_register("DB_servidor");
  session_register("DB_porta");
  session_register("DB_user");
  session_register("DB_senha");

  db_putsession("DB_base",     $DB_BASE);
  db_putsession("DB_NBASE",    $DB_BASE);
  db_putsession("DB_servidor", $DB_SERVIDOR);
  db_putsession("DB_porta",    $DB_PORTA);
  db_putsession("DB_senha",    $DB_SENHA);
  db_putsession("DB_user",     $DB_USUARIO);

if( db_verifica_ip_banco() != '1' ){

  $sMsg = _M( MENSAGEM . 'ip_nao_autorizado' );
  db_logsmanual_demais( _M( MENSAGEM . 'logs_ip_nao_autorizado', $oParametrosMsg ), pg_result($result,0,"id_usuario") );
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '{$sMsg}';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}

include(modification("classes/db_db_versao_classe.php"));
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_versao = new cl_db_versao;
$rsVersao      = $cldb_versao->sql_record($cldb_versao->sql_query(null,"db30_codversao,db30_codrelease","db30_codver desc limit 1"));

if( $cldb_versao->numrows == 0 ){

  $db30_codversao  = "1";
  $db30_codrelease = "1";
}else{
  db_fieldsmemory($rsVersao,0);
}

include(modification('libs/db_acessa.php'));

if( $db30_codversao != $db_fonte_codversao || $db30_codrelease != $db_fonte_codrelease ){

  $oParametrosMsg               = new stdClass();
  $oParametrosMsg->sVersaoFonte = $db_fonte_codversao.$db_fonte_codrelease;
  $oParametrosMsg->sVersaoBanco = $db30_codversao.$db30_codrelease;
  $sMsg                         = _M( MENSAGEM . 'versao_banco', $oParametrosMsg );
  db_logsmanual_demais( _M( MENSAGEM . 'logs_versao_banco', $oParametrosMsg ), pg_result( $result, 0, "id_usuario" ) );
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = '{$sMsg}';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}

pg_close($conn);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Abrindo E-cidade...</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
  wname      = 'wname' + Math.floor(Math.random() * 10000);
  var nav    = navigator.appName;
  var ver    = navigator.appVersion;
  var age    = navigator.userAgent;
  sizeWidth  = screen.availWidth;
  sizeHeight = screen.availHeight;

  if(age.indexOf("Firefox") != -1) {

    jan = window.open('inicio.php?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight='+sizeHeight,wname,'width='+sizeWidth+',height='+sizeHeight+',fullscreen=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
    jan.moveTo(0,0);
  } else if(nav.indexOf('Microsoft') != -1) {

    ver = navigator.appVersion;
    ver = ver.substr(ver.indexOf("MSIE"));
    ver = ver.substr(0,ver.search(";"));
    ver = ver.substr(5);
    ver = new Number(ver);
    if(ver <= 5.5) {

       window.opener.alert('<?=_M( MENSAGEM . 'navegador_5_5');?>');
       window.close();
    } else {

      jan = window.open('inicio.php?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight=',wname,'width='+sizeWidth+',height='+sizeHeight+',fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
      jan.moveTo(0,0);
    }
  } else if(nav.indexOf('Netscape') != -1) {

    if(parseFloat(navigator.vendorSub) >= 7 && parseFloat(navigator.appVersion) >= 5) {

      jan = window.open('inicio.php?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight=',wname,'width='+sizeWidth+',height='+sizeHeight+',fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
      jan.moveTo(0,0);
    } else if(age.substr(0,7) == "Mozilla"){

      if(isNaN(parseFloat(navigator.vendorSub)) && parseFloat(navigator.appVersion) >= 5){

         jan = window.open('inicio.php?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight=',wname,'width='+sizeWidth+',height='+sizeHeight+',fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
         jan.moveTo(0,0);
      }else if(parseFloat(navigator.vendorSub) >= 1.6 && parseFloat(navigator.appVersion) >= 5) {

         jan = window.open('inicio.php?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight=',wname,'width='+sizeWidth+',height='+sizeHeight+',fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
         jan.moveTo(0,0);
      } else {

        window.opener.alert('<?=_M( MENSAGEM . 'navegador_nao_autorizado');?>');
        window.close();
      }
    }
  }else{

    window.opener.alert('<?=_M( MENSAGEM . 'navegador_nao_autorizado');?>');
    window.close();
  }
  if(navigator.cookieEnabled == false) {
    window.open('cookie.html','_blank');
  }
</script>
</head>
<body bgcolor="#CCCCCC" onLoad="window.blur()">
</body>
</html>
<script type="text/javascript">
  window.close();
</script>
<?php
}
?>
