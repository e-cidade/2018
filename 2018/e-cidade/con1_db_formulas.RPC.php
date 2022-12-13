<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSelller Servicos de Informatica
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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

$oJson                           = new services_json(0,true);
$oParam                          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno                        = new stdClass();
$oRetorno->status                = true;
$oRetorno->erro                  = false;
$oRetorno->message               = '';

define('MENSAGENS', 'configuracao.configuracao.con1_db_formulas.');

try {

  db_inicio_transacao();//Begin
  switch ($oParam->exec) {

  case "verificaNomeVariavel":

    $oVarErros            = new stdClass();
    $oParam->sNomeVariavel= trim($oParam->sNomeVariavel);
    $oDaoDbformulas       = new cl_db_formulas;
    $sWhereDaoDbformulas  = " db148_nome ilike '{$oParam->sNomeVariavel}' ";
    if ( !empty($oParam->db148_sequencial) ) {
      $sWhereDaoDbformulas .= " and db148_sequencial <> {$oParam->db148_sequencial} ";
    }
    $sSqlDaoDbformulas    = $oDaoDbformulas->sql_query(null, "*", null, $sWhereDaoDbformulas);
    $rsDaoDbformulas      = db_query($sSqlDaoDbformulas);

    if(!$rsDaoDbformulas) {
      throw new DBException(_M(MENSAGENS ."erro_buscar_formula"));
    }

    if(pg_num_rows($rsDaoDbformulas) > 0) {
      
      $oRetorno->erro      = true;
      $oVarErros->sNome    = $oParam->sNomeVariavel;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."variavel_existente", $oVarErros));
    }

    break;
  }

  db_fim_transacao();//Commit
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->erro  = true;
  $oRetorno->message = urlencode($oErro->getMessage());
}

header('Content-Type: application/json');
echo $oJson->encode($oRetorno);
