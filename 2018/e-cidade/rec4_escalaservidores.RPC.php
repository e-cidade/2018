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
require_once(modification("classes/db_jornada_classe.php"));
require_once(modification("classes/db_jornadahoras_classe.php"));

$oJson             = new services_json(0,true);
$oParametros       = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = true;
$oRetorno->erro    = false;
$oRetorno->message = '';
$iInstituicao      = db_getsession("DB_instit");

try {

  switch ($oParametros->exec) {

    case 'incluir':

      db_inicio_transacao();

      if (empty($oParametros->iMatricula)) {
        throw new Exception('Matrícula do servidor não informada.');
      }

      if (empty($oParametros->iCodigoEscala)) {
        throw new Exception('Escala de trabalho não informada.');
      }

      if (empty($oParametros->dDataEscala)) {
        throw new Exception('Data da escala de trabalho não informada.');
      }

      $oDataEscala = new DBDate($oParametros->dDataEscala);
      $dDataEscala = $oDataEscala->getDate();

      /**
       * Valida se a data da escala não irá ser incluída antes do cadastro da grade de horário
       */
      $oDaoGradesHorarios = new cl_gradeshorarios;
      $sSqlGradesHorarios = $oDaoGradesHorarios->sql_query_file(
        null,
        "*",
        null,
        "rh190_sequencial = {$oParametros->iCodigoEscala} and rh190_database > '$dDataEscala'"
      );
      $rsGradesHorarios   = db_query($sSqlGradesHorarios);

      if (pg_num_rows($rsGradesHorarios) > 0) {
        throw new Exception('Erro ao salvar dados. ERRO: Data de início da escala do servidor menor que a data base da escala.');
      }

      /**
       * Valida se a inclusão da escala não será feita dentro de um período de efetividade já registrado
       */
      $oDaoConfiguracoesDataEfetividade    = new cl_configuracoesdatasefetividade();
      $sWhereConfiguracoesDataEfetividade  = "     '{$dDataEscala}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade";
      $sWhereConfiguracoesDataEfetividade .= " and rh186_processado is true";
      $sWhereConfiguracoesDataEfetividade .= " and rh186_instituicao = {$iInstituicao}";
      $sSqlConfiguracoesDataEfetividade    = $oDaoConfiguracoesDataEfetividade->sql_query(
        null,
        null,
        "*",
        null,
        $sWhereConfiguracoesDataEfetividade
      );

      $rsConfiguracoesDataEfetividade     = db_query($sSqlConfiguracoesDataEfetividade);

      if (pg_num_rows($rsConfiguracoesDataEfetividade) > 0) {
        throw new Exception('Erro ao incluir escala de funcionário. O período de efetividade já está encerrado para essa data');
      }

      /**
       * Verifica se já não existe uma escala registrada para o servidor na mesma data
       */
      $oDaoEscalaServidor    = new cl_escalaservidor;
      $sWhereEscalaServidor  = "     rh192_regist = {$oParametros->iMatricula}";
      $sWhereEscalaServidor .= " and rh192_dataescala = '{$dDataEscala}'";
      $sWhereEscalaServidor .= " and rh192_instit = " . db_getsession('DB_instit');
      $sSqlEscalaServidor    = $oDaoEscalaServidor->sql_query_file(null, "*", null, $sWhereEscalaServidor);
      $rsEscalaServidor      = db_query($sSqlEscalaServidor);

      if (pg_num_rows($rsEscalaServidor) > 0) {
        throw new Exception('Já existe escala registrada para o servidor na data informada.');
      }

      $oDaoEscalaServidor->rh192_sequencial     = '';
      $oDaoEscalaServidor->rh192_gradeshorarios = $oParametros->iCodigoEscala;
      $oDaoEscalaServidor->rh192_regist         = $oParametros->iMatricula;
      $oDaoEscalaServidor->rh192_dataescala     = $oParametros->dDataEscala;
      $oDaoEscalaServidor->rh192_instit         = db_getsession('DB_instit');

      if (empty($iCodigo)) {
        $oDaoEscalaServidor->incluir(null);
      } else {
        $oDaoEscalaServidor->alterar($iCodigo);
      }

      if ($oDaoEscalaServidor->erro_status == "0") {
        throw new Exception('Erro ao salvar dados. ERRO: ' . $oDaoEscalaServidor->erro_msg);
      }

      $oRetorno->iCodigo = $oDaoEscalaServidor->rh192_sequencial;
      $oRetorno->message = "Salvo com sucesso.";

      db_fim_transacao();

      break;

    case 'carregarEscalas':

      $sCamposEscalaServidor  = "  escalaservidor.rh192_sequencial as sequencial";
      $sCamposEscalaServidor .= ", gradeshorarios.rh190_descricao  as descricao";
      $sCamposEscalaServidor .= ", escalaservidor.rh192_dataescala as dataescala";

      $sSqlEscalasServidor = "select {$sCamposEscalaServidor}
                                from escalaservidor
                                     inner join gradeshorarios on gradeshorarios.rh190_sequencial = escalaservidor.rh192_gradeshorarios
                               where escalaservidor.rh192_regist = {$oParametros->iMatricula}
                               order by escalaservidor.rh192_dataescala desc";

      $rsEscalasServidor   = db_query($sSqlEscalasServidor);
      $aEscalasServidor    = db_utils::getCollectionByRecord($rsEscalasServidor);

      $aRetornoEscalas     = array();

      foreach ($aEscalasServidor as $oEscalaServidor) {

        $oEscala              = new stdClass();
        $oEscala->iCodigo     = $oEscalaServidor->sequencial;
        $oEscala->sDescricao  = urlencode($oEscalaServidor->descricao);
        $oEscala->dDataEscala = $oEscalaServidor->dataescala;

        $aRetornoEscalas[]    = $oEscala;

      }

      $oRetorno->aRetornoEscalas = $aRetornoEscalas;

      break;

    case 'excluir':

      db_inicio_transacao();

      $oDaoEscalaServidor                = new cl_escalaservidor();
      $sSqlEscalasServidor               = $oDaoEscalaServidor->sql_query_file(null, "*", null, "rh192_sequencial = {$oParametros->iCodigoEscala}");
      $rsEscalaServidor                  = db_query($sSqlEscalasServidor);
      $oEscalaServidor                   = db_utils::fieldsmemory($rsEscalaServidor, 0);

      $oDaoConfiguracoesDataEfetividade    = new cl_configuracoesdatasefetividade();
      $sWhereConfiguracoesDataEfetividade  = "     '{$oEscalaServidor->rh192_dataescala}' between rh186_datainicioefetividade and rh186_datafechamentoefetividade";
      $sWhereConfiguracoesDataEfetividade .= " and rh186_processado is true";
      $sWhereConfiguracoesDataEfetividade .= " and rh186_instituicao = {$iInstituicao}";
      $sSqlConfiguracoesDataEfetividade    = $oDaoConfiguracoesDataEfetividade->sql_query(
        null,
        null,
        "*",
        null,
        $sWhereConfiguracoesDataEfetividade
      );
      $rsConfiguracoesDataEfetividade    = db_query($sSqlConfiguracoesDataEfetividade);

      if (pg_num_rows($rsConfiguracoesDataEfetividade)) {
        throw new Exception('Erro ao excluir escala de funcionário. O período de efetividade já está encerrado para essa data');
      }

      $oDaoEscalaServidor->excluir(null, "rh192_sequencial = {$oParametros->iCodigoEscala}");

      if ($oDaoEscalaServidor->erro_status == "0") {
        throw new Exception("ERRO ao excluir escala do servidor. ERRO: {$oDaoEscalaServidor->erro_msg}");
      }

      $oRetorno->message  = urlencode('Registro excluído com sucesso.');

      db_fim_transacao();

      break;
  }
} catch (Exception $eException) {

  $oRetorno->erro    = true;
  $oRetorno->message = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);