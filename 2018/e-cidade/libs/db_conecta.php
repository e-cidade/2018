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

$sPath = 'libs/';

if (!isset($_SESSION)) {
  session_start();
}

if (!session_is_registered("DB_login") || !session_is_registered("DB_id_usuario")) {

  session_destroy();
  echo "Sessão Inválida!(12)<br>Feche seu navegador e faça login novamente.\n";
  exit;
}

require_once(modification("{$sPath}db_conn.php"));

if( session_is_registered("DB_servidor") &&
    session_is_registered("DB_base")     &&
    session_is_registered("DB_user")     &&
    session_is_registered("DB_porta")    &&
    session_is_registered("DB_senha") ){

  $DB_SERVIDOR = db_getsession("DB_servidor");
  $DB_BASE     = db_getsession("DB_base");
  $DB_PORTA    = db_getsession("DB_porta");
  $DB_USUARIO  = db_getsession("DB_user");
  $DB_SENHA    = db_getsession("DB_senha");
}

/**
 * Nome do programa atual
 */
$sProgramaAtual = basename($_SERVER["SCRIPT_NAME"]);

if (session_is_registered("DB_NBASE")) {
  $DB_BASE = $_SESSION["DB_NBASE"];
}

if (session_is_registered("DB_servidor")) {
  $DB_SERVIDOR = $_SESSION["DB_servidor"];
}

if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

  echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
  session_destroy();
  exit;
}

/**
 * Verifica configuracoes customizadas do PostgreSQL para o sProgramaAtual
 */
$sPgVersion = pg_result(pg_query($conn, "select substr(current_setting('server_version_num'),1,3)"), 0, 0);

if (isset($DB_CUSTOM_PG_CONF[$sPgVersion][$sProgramaAtual])) {

  $sSqlSetConfig = "";
  foreach ($DB_CUSTOM_PG_CONF[$sPgVersion][$sProgramaAtual] as $sSetting => $sValue) {

    if (substr($sSetting, 0, 3) <> "SQL") {
      $sSqlSetConfig .= "SET $sSetting TO $sValue;";
    } else {
      $sSqlSetConfig .= $sValue;
    }
  }

  pg_query($conn, $sSqlSetConfig);
}

/**
 * Verifica o PCASP
 */
if (isset($_SESSION["DB_instit"])) {

  $lUsarPcasp           = false;

  $sSqlConParametro     = " select c90_usapcasp               \n";
  $sSqlConParametro    .= "   from contabilidade.conparametro \n";
  $rsConParametro       = db_query($sSqlConParametro);

  $lParametroUsaPCASP   = false;

  if ( $rsConParametro && pg_num_rows($rsConParametro) > 0) {
    $lParametroUsaPCASP   = pg_result($rsConParametro, 0, 0);
  }

  if ($lParametroUsaPCASP == 't') {

    $sSqlTipoInstituicao  = " select db21_tipoinstit                   \n";
    $sSqlTipoInstituicao .= "   from configuracoes.db_config           \n";
    $sSqlTipoInstituicao .= "  where codigo = {$_SESSION["DB_instit"]} \n";

    $rsTipoInstituicao    = pg_query($conn, $sSqlTipoInstituicao);

    if (pg_num_rows($rsTipoInstituicao) == 1 && isset($_SESSION["DB_anousu"])) {

      $iTipoInstituicao = pg_result($rsTipoInstituicao, 0, "db21_tipoinstit");
      if ($iTipoInstituicao == 101) {

        if ($_SESSION["DB_anousu"] >= 2012) {
          $lUsarPcasp = true;
        }
      } else {

      	/**
      	 * alteração para validar a existencia de um arquivo de configuração de ano para implantacao do pcasp
      	 * ano inicial será 2013 caso o arquivo nao exista
      	 * no arquivo config/pcasp.txt.dist  sera renomeado para pcasp.txt e adicionado o ano desejado
      	 */
      	$iAnoPcasp = 2013;
      	if ( file_exists("config/pcasp.txt") ) {

      		$aArquivo  = file("config/pcasp.txt");
      		if ($aArquivo[0] != '' && $aArquivo[0] > 2013) {
      			$iAnoPcasp = $aArquivo[0];
      		}
      	}

      	$_SESSION["DB_ano_pcasp"] = $iAnoPcasp;

        if ( intval($_SESSION["DB_anousu"]) >= intval($iAnoPcasp) ) {
          $lUsarPcasp = true;
        }
      }
    }
  }

  if ( ! defined('USE_PCASP') ) {
    define("USE_PCASP", $lUsarPcasp);
  }
  $_SESSION["DB_use_pcasp"] = USE_PCASP ? 't' : 'f';
}

/**
 * Salva sessao php, variavel $_SESSION, na base de dados
 */
require_once(modification("{$sPath}db_libsession.php"));

db_savesession($conn, $_SESSION);

db_logs();

/**
 * Registra application_name para ser utilizado no controle de transações
 */
if (isset($conn)) {

  $sIdUsuario  = $_SESSION["DB_id_usuario"];
  $iPidConexao = pg_result(db_query("select pg_backend_pid()"),0,0);
  $sMenu       = 0;

  if (!empty($_SESSION["DB_itemmenu_acessado"])) {
    $sMenu = $_SESSION["DB_itemmenu_acessado"];
  }

  $sNomeApp      = "ecidade_{$sIdUsuario}_{$sMenu}_{$iPidConexao}";
  $sSqlSetConfig = "SET application_name={$sNomeApp};";

  pg_query($conn, $sSqlSetConfig);
}

if (db_getsession("DB_id_usuario") != 1 && db_getsession("DB_administrador") != 1){

  $result1 = pg_exec($conn, "select db21_ativo from db_config where prefeitura = true")
             or die("Erro ao verificar se sistema está liberado! Contate suporte!");

  $ativo   = pg_result($result1,0,0);

  if ($ativo == 3) {

    echo "Sistema desativado pelo administrador!   <br>Sessão terminada, feche seu navegador!\n";
    session_destroy();
    exit;
  }
}

require_once(modification("{$sPath}db_acessa.php"));

if (!defined("DB_VERSION")) {
  define("DB_VERSION", $db_fonte_codrelease);
}

/**
 * Parametro para definir se utiliza cadastro de ferias novo
 * - ao incluir servidor inclui periodo aquisitivo
 * - ao inicialização do ponto, da manutenção do periodo aquisitivo
 */
define('UTILIZAR_NOVO_CADASTRO_FERIAS', true);

/*
 * Adicionada validação de rotinas em processamento
 * No primeiro momento somente a rotina de Emissão Geral de IPTU está sendo validada
 * No array $aItensMenu setamos os itens de menu que deverão ser bloqueados caso já estejam sendo acessados
 * Na query $sSqlStatActivity buscamos as aplicações que estão sendo executadas e após verificamos se o item de menu
 * da aplicação e o item de menu que está sendo acessado entá na lista de itens bloqueados, caso true, redireciona para
 * o corpo.php
 */
$aItensMenu = array(1576, 10336);

if ( isset($_SESSION["DB_itemmenu_acessado"])
     && in_array($_SESSION["DB_itemmenu_acessado"],$aItensMenu)
   ) {

  $sSqlStatActivity = "select application_name
                         from pg_stat_activity
                        where application_name ilike 'ecidade_%'
                          and query not ilike '%application_name%'
                          and pid <> {$iPidConexao}
                          and datname = '{$DB_BASE}'";
  $rsStatActivity = db_query($sSqlStatActivity);

  if ($rsStatActivity) {

    for ($iIndice = 0; $iIndice < pg_num_rows($rsStatActivity); $iIndice++) {

    	$aDadosApplication = explode('_',pg_result($rsStatActivity, $iIndice, 'application_name'));
    	$iItemMenu = $aDadosApplication[2];

    	if ( in_array($iItemMenu,$aItensMenu) ) {

    		$sMsg  = "A Emissão Geral de IPTU já está em execução em outro acesso.\n";
    		$sMsg .= "Você está sendo redirecionado para tela de seleção de módulos.";
    		db_msgbox($sMsg);
    		db_redireciona('corpo.php');
    	}

    }

  }

}
