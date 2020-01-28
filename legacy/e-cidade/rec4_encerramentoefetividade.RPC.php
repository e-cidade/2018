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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_tipoasse_classe.php"));
require_once(modification("classes/db_tipoassedb_depart_classe.php"));
require_once(modification("std/DBDate.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\ProcessamentoAssentamentoRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;

$oParametros          = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';
$iInstituicao         = db_getsession("DB_instit");

try {

  switch ($oParametros->exec) {

    case 'carregarConfiguracoes':

      $oDaoConfiguracoesDatasEfetividade = new cl_configuracoesdatasefetividade;
      $sSqlConfiguracoesDatasEfetividade = $oDaoConfiguracoesDatasEfetividade->sql_query_file(
        null,
        "*",
        "rh186_competencia::integer",
        "rh186_exercicio = {$oParametros->iExercicio} and rh186_instituicao = {$iInstituicao}"
      );

      $rsConfiguracoesDatasEfetividade   = db_query($sSqlConfiguracoesDatasEfetividade);
      if(!$rsConfiguracoesDatasEfetividade) {
        throw new DBException("Ocorreu um erro ao consultar as configurações de efetividade.\nContate o suporte.");
      }

      $aConfiguracoes = array();
      for ($iRegistro = 0; $iRegistro < pg_num_rows($rsConfiguracoesDatasEfetividade); $iRegistro++) {

        $oRegistro      = db_utils::fieldsmemory($rsConfiguracoesDatasEfetividade, $iRegistro);

        $oConfiguracoes = new stdClass();
        $oConfiguracoes->sCompetencia               = $oRegistro->rh186_competencia;
        $oConfiguracoes->dDataInicioEfetividade     = implode('/', array_reverse(explode('-', $oRegistro->rh186_datainicioefetividade)));
        $oConfiguracoes->dDataFechamentoEfetividade = implode('/', array_reverse(explode('-', $oRegistro->rh186_datafechamentoefetividade)));
        $oConfiguracoes->dDataEntregaEfetividade    = implode('/', array_reverse(explode('-', $oRegistro->rh186_dataentregaefetividade)));
        $oConfiguracoes->lProcessado                = $oRegistro->rh186_processado == 't';
        $aConfiguracoes[]                           = $oConfiguracoes;
      }

      $oRetorno->aConfiguracoes = $aConfiguracoes;

      break;

    case 'reabrirCompetencia':

      if ( empty($oParametros->iExercicio) ) {
        throw new Exception("Exercício não informado.");
      }
      if ( count($oParametros->aCompetencias) == 0 ) {
        throw new Exception("Nenhuma competência informada.");
      }

      db_inicio_transacao();

      $sWhere  = "     rh186_exercicio   = {$oParametros->iExercicio}";
      $sWhere .= " and rh186_competencia in ('".implode("', '", $oParametros->aCompetencias) . "')";
      $sWhere .= " and rh186_instituicao = {$iInstituicao}";

      $rsProcessado = db_query("update configuracoesdatasefetividade set rh186_processado = 'f' where {$sWhere}");
      if (!$rsProcessado) {
        throw new DBException("Erro ao reabrir competência.\n". pg_last_error());
      }

      $oRetorno->message = 'Reabertura processada com sucesso.';

      db_fim_transacao();

      break;
  }
} catch (Exception $eException) {

  $oRetorno->erro    = true;
  $oRetorno->message = $eException->getMessage();
}

echo JSON::create()->stringify($oRetorno);
