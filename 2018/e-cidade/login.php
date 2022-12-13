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

require_once(modification("model/configuracao/SkinService.service.php"));
require_once(modification("libs/smtp.class.php"));

$oSkin = new SkinService();
$oSkin->setCookie();

/**
 * Busca preferencias para verificar qual class
 * de imagem de fundo será utilizada
 */
$oPreferenciaEcidade = new PreferenciaEcidade();

if (!property_exists($oPreferenciaEcidade->getPreferenciaTelaLogin(), "sClassAtiva")) {

  $sClassAtiva = "imagem01";
  $oPreferenciaEcidade->setPreferenciaTelaLogin(time(), $sClassAtiva);
  $oPreferenciaEcidade->salvarPreferencias();
}

$oPreferenciaTelaLogin = $oPreferenciaEcidade->getPreferenciaTelaLogin();
$iDataUltimaSemana     = $oPreferenciaTelaLogin->iDataAlteracao + (7 * 24 * 60 * 60);
$sClassAtiva           = $oPreferenciaTelaLogin->sClassAtiva;

if (time() >= $iDataUltimaSemana) {

  switch ($sClassAtiva) {

    case 'imagem01':
      $sClassAtiva = "imagem02";
      break;

    case 'imagem02':
      $sClassAtiva = "imagem03";
      break;

    default:
      $sClassAtiva = "imagem01";
      break;
  }

  $oPreferenciaEcidade->setPreferenciaTelaLogin(time(), $sClassAtiva);
  $oPreferenciaEcidade->salvarPreferencias();
}

/**
 * Valida se existe um servidor de e-mail configurado
 */
try {

  $oSmtp                     = new Smtp();
  $lMostraLinkPrimeiroAcesso = false;
} catch(Exception $e) {

  $lMostraLinkPrimeiroAcesso = true;
}

require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));

$sDiretorio         = "config/require_extensions.xml";
$lValidaLogin       = false;

if ( file_exists($sDiretorio) ) {

  // Abre o arquivo XML e transforma em um objeto
  $oXmlEst      = simplexml_load_file($sDiretorio);
  $lValidaLogin = true;
} else {

  // Se não existir o arquivo retorna para a index.php
  session_start();
  session_destroy();
  header("Location: index.php");
}

if ( isset($DB_VALIDA_REQUISITOS) && $DB_VALIDA_REQUISITOS == true ) {

  if(!session_id()){
    session_start();
  }

  if ( !session_is_registered("DB_configuracao_ok") ) {

    session_destroy();
    header("Location: index.php");
  }
}

if ( isset($serv) && $serv != "" ) {

  $servidor   = $DB_CONEXAO[$serv]["SERVIDOR"];
  $user       = base64_encode($DB_CONEXAO[$serv]["USUARIO"]);
  $port       = $DB_CONEXAO[$serv]["PORTA"];
  $senh       = base64_encode($DB_CONEXAO[$serv]["SENHA"]);
  $base_pesq  = $DB_CONEXAO[$serv]["BASE"];

  if (!($conn1 = pg_connect("host=$servidor dbname=template1 user=".$DB_CONEXAO[$serv]["USUARIO"]." port=$port password=".$DB_CONEXAO[$serv]["SENHA"])) ) {

    echo "<script>location.href='index.php';</script>";
    exit;
  }

  $sql_bases     = "select * from pg_database where datname ilike '$base_pesq%' order by datname;";
  $result_bases  = db_query($sql_bases);
  $numrows_bases = pg_numrows($result_bases);
  pg_close($conn1);

} else {

  $servidor = "";
  $user     = "";
  $port     = "";
  $senh     = "";
}

foreach ($oXmlEst->template_login as $aSrc) {
  $sScriptLogin = $aSrc['src'];
}

if(!session_id()){
  session_start();
}

$oTentativasAcesso = new StdClass();

if ( !empty($_SESSION['DB_tentativasAcesso'] ) ) {
  $oTentativasAcesso = DB_getsession('DB_tentativasAcesso');
}

$iTotalTentativas = array_sum((array) $oTentativasAcesso);

$lCaptcha = (isset($lUtilizaCaptcha) && $lUtilizaCaptcha && $iTotalTentativas >= 3);

if ( $lValidaLogin ) {
  include(modification($sScriptLogin));
}

?>
<script type="text/javascript">
  function js_logaComTeclaEnter(evt) {

    var evt = (evt) ? evt : (window.event) ? window.event : "";
    if (evt.keyCode == 13) {
      js_acessar();
    }
  }

  function js_acessar() {

    $('testaLogin').innerHTML = '';

    var sLogin                = $F('usu_login');
    var sSenha                = calcMD5($F('usu_senha'));
    var wname                 = 'wname' + Math.floor(Math.random() * 10000);
    var sQuery                = "";

    $('usu_senha').value      = "";
    $('usu_login').value      = "";

    if ($('serv')){

      sQuery += "&servidor="+$F('servidor');
      sQuery += "&base="+$F('base');
      sQuery += "&user="+$F('user');
      sQuery += "&port="+$F('port');
      sQuery += "&senha="+$F('senh');
    }

    var oCaptcha = document.getElementById('captcha');
    var sAuth    = btoa("DB_login="+sLogin+"&DB_senha="+sSenha).urlEncode();
        sUrl     = 'abrir.php?sAuth=' + sAuth
                   + ((oCaptcha) ?  '&conteudoCaptcha=' + $F('ct_captcha') : '')
                   + sQuery;

    $('usu_senha').value = '';

    var jan   = window.open(sUrl,wname,'width=1,height=1');
  }

  function js_mostrarelatorio(){

    var sUrl = "fpdf151/mostrarelatorio.php?arquivo="+$F('arquivo');
    var jan  = window.open(sUrl,'','location=0');

    $('arquivo').select();
    $('arquivo').focus();

  }

  function js_verifica_cookie(){

    // Esta funcao testa se os cookies sao aceitos
    // Tenta escrever um cookie.
    document.cookie = 'aceita_cookie=sim;path=/;';
    // Checa se conseguiu
    if(document.cookie == '') {

      window.location.href='cookie.html';
      return (false);
    } else {

      // Apaga o cookie.
      document.cookie = 'aceita_cookie=sim; expires=Fri, 13-Apr-1970 00:00:00 GMT';
      return (true);
    }
  }

  function js_addevent() {

    if ($('serv')) {

      $('serv').observe('change', function(event){
        $('form1').submit();
      });
    }

    if ($('gerar')) {

      $('gerar').observe('click', function(event){
        js_mostrarelatorio();
      });
    }

    $('btnlogar').observe('click', function(event){
      js_acessar();
    });

    $('usu_senha').observe('keyup', function(event){
      js_logaComTeclaEnter(event);
    });

    document.form1.usu_login.focus();
    js_verifica_cookie();
  }


/**
 * Valida se a versão do Firefox utilizada pelo usuário é válida.
 * Este bloco de código, deve ser removido em 2018.
 */
(function(){

  var sAgent                 = navigator.userAgent;
  var iVersaoCompletaFirefox = sAgent.substring(sAgent.indexOf("Firefox") + 8);
  var iVersaoFirefox         = parseInt(''+iVersaoCompletaFirefox,10);
  var dtAtual                = new Date();

  if (isNaN(iVersaoFirefox)) {
    iVersaoFirefox = parseInt(navigator.appVersion,10);
  }

  if (iVersaoFirefox < 42 && dtAtual.getFullYear() == 2017 ) {
    
    var sMensagem  = "Identificamos que você está utilizando uma versão desatualizada do Firefox.";
        sMensagem += "\nA partir de 2018, a versão mínima suportada pelo e-cidade será a 42, sendo a versão 52 a máxima homologada.";
    alert(sMensagem);
  }

})();
</script>
