<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


require("libs/db_stdlib.php");
require("libs/db_conn.php");
require("std/db_stdClass.php");
require("model/configuracao/Encriptacao.model.php");
require("libs/db_utils.php");
require("model/configuracao/UsuarioSistema.model.php");

$stdClass = new db_stdClass();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if (isset($servidor)&&$servidor!=""&&isset($base)&&$base!=""){

		$DB_SERVIDOR = $servidor;
    $DB_BASE     = $base;
		$DB_PORTA    = $port;
		$DB_USUARIO  = base64_decode($user);
		$DB_SENHA    = base64_decode($stdClass->db_stripTagsJson($senha));

}
if(strlen($DB_login)==0){

  db_logsmanual_demais(1,"Login Inválido ao Acessar o Sistema - Login: $DB_login ");
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Login Inválido';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}

if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

  db_logsmanual_demais("Conexão com o Servidor Inválida. Contate com Administrador: (host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA ) - Login: $DB_login ");
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = ' Contate com Administrador do Sistema! (Conexão Inválida.)';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
  exit;
}

@db_query($conn, "select fc_startsession()");

db_logsmanual_demais("Abrindo Acesso ao Sistema - Login: $DB_login ");

/**
 * Habilita acesso apenas para usuarios do e-cidade usuext = 0 negando para:
 * 1 - Usuário Externo
 * 2 - Perfil
 */
$sSql  = "select id_usuario, senha, administrador ";
$sSql .= "  from db_usuarios                      ";
$sSql .= " where usuarioativo = '1'               ";
$sSql .= "   and usuext       not in (1,2)        ";
$sSql .= "   and login        = '".$DB_login."'   ";

$result = db_query( $conn, $sSql );

  if ($DB_login != 'dbseller' && pg_result($result,0,"administrador") != 1 ) {

    $result1 = db_query($conn,"select db21_ativo from db_config where prefeitura = true") or die("erro ao verificar se sistema está liberado! Contate suporte!");
    $ativo = pg_result($result1,0,0);

    if ($ativo == 3) {

      db_logsmanual_demais("Sistema Desativado Pelo Administrador - Login: $DB_login");
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = ' Sistema desativado pelo administrador!';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
      exit;
    }else if ($ativo == 2) {

      db_logsmanual_demais("Acesso Negado Pelo Administrador - Sistema Configurado para não permitir mais acessos - Login: $DB_login");
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = ' Acesso negado! Sistema configurado para não permitir que mais usuários loguem.';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
      exit;
    }
  }

$sql = "select * from db_depusu";
$result1 = db_query($conn,$sql) or die($sql);

if( pg_numrows($result) == 0 or pg_numrows($result1) == 0 ) {

  if( $DB_login == 'dbseller' &&  pg_numrows($result) == 0 ){

    db_logsmanual_demais("Procedendo com o Registro do Sistema - Login: $DB_login");
    include('con4_registrasistema.php');
    exit;
  }else{

    if( pg_numrows($result1) == 0 ){

      db_logsmanual_demais("Login sem Departamento Vinculado - Login: $DB_login");
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Login sem Vínculo com Departamento. Acesso Inválido.';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
    }else{

      db_logsmanual_demais("Login Inválido - Login: $DB_login");
      echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Login Inválido';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
    }
    exit;

  }
} else {

  /**
   * Para alterar as formas de login entre a antiga e a nova
   * descomente a linha abaixo.
   */
  // if( $DB_senha  != MD5( ~pg_result($result,0,"senha") ) ) { //Login via bitwise
   if(Encriptacao::hash( $DB_senha ) != pg_result($result,0,"senha")) { //Login via SHA-1

    db_logsmanual_demais("Senha Inválida - Login: $DB_login ",pg_result($result,0,"id_usuario"));
    echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Senha Inválida';window.opener.document.form1.usu_login.focus();window.close()</script>\n";
    exit;
  }
  session_start();

  echo "<script>if(window.opener==null)location.href='index.php';</script>";
  if(!session_is_registered("DB_acessado")){
     session_register("DB_acessado");
  }

  session_register("DB_login");
  session_register("DB_id_usuario");
  session_register("DB_administrador");
  session_register("DB_preferencias_usuario");
  db_putsession("DB_login",$DB_login);
  db_putsession("DB_id_usuario",pg_result($result,0,"id_usuario"));
  db_putsession("DB_administrador",pg_result($result,0,"administrador"));

  /**
   * Realiza a busca das preferências do usuário.
   */
  $oUsuarioSistema = new UsuarioSistema(pg_result($result,0,"id_usuario"));
  $sPreferencias   = serialize($oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario());
  db_putsession("DB_preferencias_usuario", base64_encode($sPreferencias));

  session_register("DB_ip");
  if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){
    db_putsession("DB_ip",$_SERVER["HTTP_X_FORWARDED_FOR"]);
  }else{
    db_putsession("DB_ip",$HTTP_SERVER_VARS['REMOTE_ADDR']);
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

  db_logsmanual_demais("IP não autorizado à acessar o sistema - Login: $DB_login",pg_result($result,0,"id_usuario"));
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Você não esta autorizado à acessar sistema. Contate Administrador.';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}

include("classes/db_db_versao_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cldb_versao = new cl_db_versao;
$result = $cldb_versao->sql_record($cldb_versao->sql_query(null,"db30_codversao,db30_codrelease","db30_codver desc limit 1"));

if( $cldb_versao->numrows == 0 ){

  $db30_codversao  = "1";
  $db30_codrelease = "1";
}else{
  db_fieldsmemory($result,0);
}

include('libs/db_acessa.php');

if( $db30_codversao != $db_fonte_codversao || $db30_codrelease != $db_fonte_codrelease ){

  db_logsmanual_demais("Versão do banco de dados diferente da versão do aplicativo - Login: $DB_login",pg_result($result,0,"id_usuario"));
  echo "<script>window.opener.document.getElementById('testaLogin').innerHTML = 'Versão do Banco (2.$db30_codversao.$db30_codrelease) e do aplicativo (2.$db_fonte_codversao.$db_fonte_codrelease) não estão sincronizadas. Contate Administrador.';window.opener.document.form1.usu_login.focus();window.close();</script>\n";
  exit;
}


pg_close($conn);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Abrindo DBportal...</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
  wname = 'wname' + Math.floor(Math.random() * 10000);
  var nav = navigator.appName;
  var ver = navigator.appVersion;
  var age = navigator.userAgent;
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

       window.opener.alert('Atualize seu Navegador. Utilize versões acima de 5.5!');
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

        window.opener.alert('Este Navegador não esta autorizado. Utilize Netscape 7.?, Explorer 5.5??, FireBird, Mozilla ??');
        window.close();
      }
    }
  }else{

    window.opener.alert('Este Navegador não esta autorizado. Utilize Netscape 7.?, Explorer 5.5??, FireBird, Mozilla ??');
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
<script>
  window.close();
</script>
<?
}
?>