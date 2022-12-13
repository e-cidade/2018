<?php
session_start();
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("model/configuracao/Encriptacao.model.php"));

$oJson    = new services_json();
if (isset($_POST['json'])) {
  $oParam = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
} else {
  $oParam = db_utils::postMemory($_POST);
}
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {

  case "autenticaUsuario":

    try {

      $aOptionsClient = array('location' => 'http://{url-cliente}/webservices/DBAcessoSistema.webservice.php',
                              'uri'      => 'http://{url-cliente}/webservices/',
                              'trace'    => 1);

      $oSoapClient = new SoapClient(null, $aOptionsClient);
      $rsExecuta   = $oSoapClient->autenticarUsuario($oParam->sLoginUsuario, $oParam->sSenhaUsuario);
      if (!$rsExecuta) {
        throw new Exception("Erro na autenticacao.");
      }

      $rsConectaBase        = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
      $rsStartSession       = db_query("select fc_startsession()");
      $oDaoInstituicao      = db_utils::getDao('db_config');
      $sSqlBuscaInstituicao = $oDaoInstituicao->sql_query_file(null, "db_config.codigo", null, "prefeitura is true");
      $rsBuscaInstituicao   = db_query($sSqlBuscaInstituicao);
      $iCodigoInstituicao   = db_utils::fieldsMemory($rsBuscaInstituicao, 0)->codigo;

      session_register("DB_login");
      session_register("DB_id_usuario");
      session_register("DB_administrador");
      session_register("DB_modulo");
      session_register("DB_nome_modulo");
      session_register("DB_anousu");
      session_register("DB_instit");
      session_register("DB_base");
      session_register("DB_servidor");
      session_register("DB_porta");
      session_register("DB_user");
      session_register("DB_senha");
      session_register("DB_uol_hora");
      session_register("DB_datausu");
      session_register("DB_acessado");

      $_SESSION['DB_servidor']      = $DB_SERVIDOR;
      $_SESSION['DB_base']          = $DB_BASE;
      $_SESSION['DB_porta']         = $DB_PORTA;
      $_SESSION['DB_user']          = $DB_USUARIO;
      $_SESSION['DB_senha']         = $DB_SENHA;
      $_SESSION["DB_login"]         = $oParam->sLoginUsuario;
      $_SESSION["DB_id_usuario"]    = $rsExecuta->iIdUsuario;
      $_SESSION["DB_administrador"] = $rsExecuta->lAdministrador;
      $_SESSION["DB_modulo"]        = 1100747;
      $_SESSION["DB_nome_modulo"]   = "Escola";
      $_SESSION["DB_anousu"]        = date('Y');
      $_SESSION["DB_instit"]        = $iCodigoInstituicao;
      $_SESSION["DB_uol_hora"]      = "".db_hora()."";
      $_SESSION["DB_datausu"]       = time();
      $_SESSION["DB_acessado"]      = 1;

    } catch (SoapFault $eSoapFault) {

      $oRetorno->message = $eSoapFault->getMessage();
      $oRetorno->status  = 2;
    } catch (Exception $eException) {

      $oRetorno->message = $eException->getMessage();
      $oRetorno->status = 2;
    } catch (DBException $eDBException) {

      $oRetorno->message = $eDBException->getMessage();
      $oRetorno->status = 2;
    }

    break;

  case "getDepartamentos":

    $rsConectaBase   = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
    $rsStartSession  = db_query("select fc_startsession()");

    $iCodigoUsuario          = db_getsession("DB_id_usuario");
    $iCodigoInstituicao      = db_getsession("DB_instit");
    $dtDataUsuario           = date("Y-m-d", db_getsession("DB_datausu"));

    $sSqlBuscaEscola  = " select db_depart.coddepto, db_depart.descrdepto";
    $sSqlBuscaEscola .= "   from db_depart";
    $sSqlBuscaEscola .= "        inner join escola          on escola.ed18_i_codigo   = db_depart.coddepto";
    $sSqlBuscaEscola .= "        inner join db_usuarios     on db_usuarios.id_usuario = {$iCodigoUsuario}";
    $sSqlBuscaEscola .= "        inner join db_depusu       on db_depusu.id_usuario   = {$iCodigoUsuario}";
    $sSqlBuscaEscola .= "                                  and db_depusu.coddepto     = db_depart.coddepto";
    $sSqlBuscaEscola .= "  where db_usuarios.id_usuario = {$iCodigoUsuario}";
    $sSqlBuscaEscola .= "    and db_depart.instit = {$iCodigoInstituicao}";
    $sSqlBuscaEscola .= "    and (db_depart.limite is null or db_depart.limite >= '{$dtDataUsuario}')";

    $rsBuscaDepartamento = db_query($sSqlBuscaEscola);

    $aDepartamentos           = array();
    if (pg_num_rows($rsBuscaDepartamento) > 0) {
      $aDepartamentos = db_utils::getCollectionByRecord($rsBuscaDepartamento);
    }
    $oRetorno->aDepartamentos = $aDepartamentos;
    break;

  case "setSessionDepartamento":

    session_register("DB_coddepto");
    $_SESSION["DB_coddepto"] = $oParam->iCodigoDepartamento;
    break;
}
echo $oJson->encode($oRetorno);
