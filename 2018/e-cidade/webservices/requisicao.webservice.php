<?php
/**
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

session_start();

/**
 * Leva a intancia da Execução do Programa para a pasta RAIZ do e-Cidade.
 */
global $HTTP_SESSION_VARS;
global $HTTP_SERVER_VARS;
global $HTTP_POST_VARS;
global $HTTP_GET_VARS;

$HTTP_POST_VARS            = array();
$HTTP_GET_VARS             = array();

ini_set("soap.wsdl_cache_enabled", "0");

require_once(modification("libs/db_conn.php"));

$HTTP_SESSION_VARS['DB_id_usuario'       ] = '1';
$HTTP_SESSION_VARS['DB_login'            ] = 'dbseller';
$HTTP_SESSION_VARS['DB_administrador'    ] = '1';
$HTTP_SESSION_VARS['DB_ip'               ] = '127.0.0.1';
$HTTP_SESSION_VARS['REQUEST_URI'         ] = '';
$HTTP_SESSION_VARS['DB_configuracao_ok'  ] = '';
$HTTP_SESSION_VARS['DB_acessado'         ] = '1325613';
$HTTP_SESSION_VARS['DB_base'             ] = $DB_BASE;
$HTTP_SESSION_VARS['DB_servidor'         ] = $DB_SERVIDOR;
$HTTP_SESSION_VARS['DB_porta'            ] = $DB_PORTA;
$HTTP_SESSION_VARS['DB_user'             ] = $DB_USUARIO;
$HTTP_SESSION_VARS['DB_senha'            ] = $DB_SENHA;
$HTTP_SESSION_VARS['DB_uol_hora'         ] = time();
$HTTP_SESSION_VARS['DB_totalmodulos'     ] = '55';
$HTTP_SESSION_VARS['DB_use_pcasp'        ] = 'f';
$HTTP_SESSION_VARS['DB_Area'             ] = '1';
$HTTP_SESSION_VARS['DB_modulo'           ] = '578';
$HTTP_SESSION_VARS['DB_nome_modulo'      ] = 'Configurações';
$HTTP_SESSION_VARS['DB_anousu'           ] =  date('Y', time());
$HTTP_SESSION_VARS['DB_datausu'          ] = time();//
$HTTP_SESSION_VARS['DB_coddepto'         ] = '1';
$HTTP_SESSION_VARS['DB_nomedepto'        ] = 'COINF';
$HTTP_SESSION_VARS['DB_itemmenu_acessado'] = '1576';
$HTTP_SERVER_VARS['SERVER_NAME']           = $DB_SERVIDOR;
$HTTP_SERVER_VARS['SERVER_ADDR']           = '127.0.0.1';
$HTTP_SERVER_VARS['SERVER_PORT']           = '80';
$HTTP_SERVER_VARS['REMOTE_ADDR']           = $_SERVER['REMOTE_ADDR'];//'127.0.0.1';
$HTTP_SERVER_VARS['DOCUMENT_ROOT']         = '/var/www';
$HTTP_SERVER_VARS['SERVER_ADMIN']          = 'webmaster@localhost';
$HTTP_SERVER_VARS['SCRIPT_FILENAME']       = '/var/www/dbportal_prj/webservices/requisicao.webservice.php';
$HTTP_SERVER_VARS['SCRIPT_NAME']           = '/var/www/dbportal_prj/webservices/requisicao.webservice.php';
$HTTP_SERVER_VARS['PHP_SELF']              = 'dbportal_prj/webservices/requisicao.webservice.php';
$HTTP_SERVER_VARS['REQUEST_URI']           = '';
$HTTP_SERVER_VARS['HTTP_HOST']             = 'localhost';

/**
 * @todo: movido as requisicoes pois precisam das variaveis setadas da sessao
 */
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once("libs/db_autoload.php");

$conn           = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
$rsStartSession = pg_query("select fc_startsession()");

$iInstituicao    = 1;
/**
 * Define DB_instit para Instituição prefeitura
 * @todo  verificar possibilidade de utilizar via db_conecta
 */
$sSqlInstituicao = "select codigo from db_config where prefeitura is true";
if($conn){

  $rsInstituicao   = pg_query($sSqlInstituicao);
  if($rsInstituicao){
    $iInstituicao  = pg_fetch_result($rsInstituicao, 0, 'codigo');
  }
}
/**
 *
 */
$HTTP_SESSION_VARS['DB_instit'] = $iInstituicao;

$_SESSION                  = $HTTP_SESSION_VARS;
$_SERVER                   = $HTTP_SERVER_VARS;
$_POST                     = $HTTP_POST_VARS;
$_GET                      = $HTTP_GET_VARS;

require_once(modification("model/webservices/DBWebService.model.php"));

require_once(modification("libs/db_conecta.php"));
$oSoapServer = new SoapServer(null,
                              array('uri' => 'http://localhost/dbportal_prj'));
$oSoapServer->setClass("DBWebService");
$oSoapServer->handle();
