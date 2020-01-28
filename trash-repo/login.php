<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

error_reporting(null);
require_once("libs/db_conn.php");
require_once("libs/db_utils.php");

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
    $user       = $DB_CONEXAO[$serv]["USUARIO"];
    $port       = $DB_CONEXAO[$serv]["PORTA"];
    $senh       = $DB_CONEXAO[$serv]["SENHA"];
    $base_pesq  = $DB_CONEXAO[$serv]["BASE"];

    if (!($conn1 = pg_connect("host=$servidor dbname=template1 user=$user port=$port password=$senh ")) ) {
    	
      echo "<script>location.href='index.php';</script>";
      exit;
    }

    $sql_bases     = "select * from pg_database where datname ilike '$base_pesq%' order by datname;";
    $result_bases  = pg_query($sql_bases);    
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

if ( $lValidaLogin ) {
	include($sScriptLogin);
}
?>
<script>
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

  var sUrl  = 'abrir.php?estenaoserveparanada=1&DB_login='+sLogin+'&DB_senha='+sSenha+sQuery;
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
</script>