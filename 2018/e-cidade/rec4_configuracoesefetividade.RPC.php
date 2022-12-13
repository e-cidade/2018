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

$oJson                = new services_json(0,true);
$oParametros          = $oJson->decode(str_replace("\\","",$_POST["json"]));
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
        "rh186_exercicio = {$oParametros->iExercicio} AND rh186_instituicao = {$iInstituicao}"
      );
      $rsConfiguracoesDatasEfetividade   = db_query($sSqlConfiguracoesDatasEfetividade);

      $aConfiguracoes = array();
      for ($iRegistro = 0; $iRegistro < pg_num_rows($rsConfiguracoesDatasEfetividade); $iRegistro++) {

        $oRegistro      = db_utils::fieldsmemory($rsConfiguracoesDatasEfetividade, $iRegistro);

        $oConfiguracoes                             = new stdClass();
        $oConfiguracoes->sCompetencia               = $oRegistro->rh186_competencia;
        $oConfiguracoes->dDataInicioEfetividade     = implode('/', array_reverse(explode('-', $oRegistro->rh186_datainicioefetividade)));
        $oConfiguracoes->dDataFechamentoEfetividade = implode('/', array_reverse(explode('-', $oRegistro->rh186_datafechamentoefetividade)));
        $oConfiguracoes->dDataEntregaEfetividade    = implode('/', array_reverse(explode('-', $oRegistro->rh186_dataentregaefetividade)));
        $oConfiguracoes->lProcessado                = $oRegistro->rh186_processado == 't';
        $aConfiguracoes[]                           = $oConfiguracoes;
      }

      $oRetorno->aConfiguracoes = $aConfiguracoes;

      break;

    case 'salvar':

      db_inicio_transacao();

      $iExercicio = $oParametros->iExercicio;

      foreach ($oParametros->aSelecionados as $oSelecionado) {

        $oDaoConfiguracoesDatasEfetividade = new cl_configuracoesdatasefetividade;

        $sCompetencia = str_pad($oSelecionado->iCompetencia, 2, '0', STR_PAD_LEFT);

        $sWhere  = "     rh186_exercicio   = {$iExercicio} ";
        $sWhere .= " and rh186_competencia = '{$sCompetencia}' ";
        $sWhere .= " and rh186_instituicao = {$iInstituicao}";

        $sSqlConfiguracao = $oDaoConfiguracoesDatasEfetividade->sql_query_file(null, "*", null, $sWhere);
        $rsConfiguracao   = db_query($sSqlConfiguracao);

        $oDaoConfiguracoesDatasEfetividade->rh186_exercicio                 = $iExercicio;
        $oDaoConfiguracoesDatasEfetividade->rh186_competencia               = $sCompetencia;
        $oDaoConfiguracoesDatasEfetividade->rh186_datainicioefetividade     = $oSelecionado->dDataInicioEfetividade;
        $oDaoConfiguracoesDatasEfetividade->rh186_datafechamentoefetividade = $oSelecionado->dDataFechamentoEfetividade;
        $oDaoConfiguracoesDatasEfetividade->rh186_dataentregaefetividade    = $oSelecionado->dDataEntregaEfetividade;
        $oDaoConfiguracoesDatasEfetividade->rh186_instituicao               = $iInstituicao;
        $oDaoConfiguracoesDatasEfetividade->rh186_processado                = 'false';

        if ( $rsConfiguracao && pg_num_rows($rsConfiguracao) == 1 ) {
          $oDaoConfiguracoesDatasEfetividade->alterar2($iExercicio, $sCompetencia, $iInstituicao);
        } else {
          $oDaoConfiguracoesDatasEfetividade->incluir($iExercicio);
        }

        if ( $oDaoConfiguracoesDatasEfetividade->erro_status == 0 ) {
          throw new Exception("Não foi possível salvar a configuração.\n". $oDaoConfiguracoesDatasEfetividade->erro_msg);
        }
      }

      $oRetorno->message = 'Processamento executado com sucesso.';
      db_fim_transacao();
      break;
  }
} catch (Exception $eException) {
  $oRetorno->message = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);