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
require_once(modification("classes/db_gradeshorariosjornada_classe.php"));

$oJson                = new services_json(0,true);
$oParametros          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

try {

  switch ($oParametros->exec) {

    case 'salvarJornada':

      db_inicio_transacao();

      $iCodigo                     = $oParametros->iCodigoJornada;
      $sDescricao                  = db_stdClass::normalizeStringJsonEscapeString($oParametros->sDescricao);
      $sTipo                       = db_stdClass::normalizeStringJsonEscapeString($oParametros->sTipo);
      $oDaoJornada                 = new cl_jornada;

      $oDaoJornada->rh188_sequencial = $iCodigo;
      $oDaoJornada->rh188_descricao  = $sDescricao;
      $oDaoJornada->rh188_fixo       = 'false';
      $oDaoJornada->rh188_tipo       = $sTipo;

      if (empty($iCodigo)) {
        $oDaoJornada->incluir(null);
      } else {
        $oDaoJornada->alterar($iCodigo);
      }

      if ($oDaoJornada->erro_status == "0") {
        throw new Exception('Erro ao salvar dados. ERRO: ' . $oDaoJornada->erro_msg);
      }

      $oRetorno->iCodigoJornada = $oDaoJornada->rh188_sequencial;
      $oRetorno->message        = "Salvo com sucesso.";

      db_fim_transacao();

      break;

    case 'excluirJornada':

      db_inicio_transacao();

      $sSqlGradesHorariosJornada = "select * from gradeshorariosjornada where gradeshorariosjornada.rh191_jornada = {$oParametros->iCodigoJornada}";
      $rsGradesHorariosJornada   = db_query($sSqlGradesHorariosJornada);
      if (pg_num_rows($rsGradesHorariosJornada) > 0) {
        throw new Exception('Erro ao excluir dados da tabela jornada. Ela está sendo referenciada por uma ou mais grades de horário.');
      }

      $sSqlJornadaHoras = "delete from jornadahoras where rh189_jornada = {$oParametros->iCodigoJornada}";
      if (!db_query($sSqlJornadaHoras)) {
        throw new Exception('Erro ao excluir jornada. ERRO: ' . pg_last_error());
      }

      $sSqlJornada      = "delete from jornada where rh188_sequencial = {$oParametros->iCodigoJornada}";
      if (!db_query($sSqlJornada)) {
        throw new Exception('Erro ao excluir jornada. ERRO: ' . pg_last_error());
      }

      $oRetorno->message = DBString::urlencode_all("Dados excluídos com sucesso.");

      db_fim_transacao();

      break;

    case 'carregarJornadas':

      $sCamposJornadaHoras  = "  jornadahoras.rh189_hora as hora";
      $sCamposJornadaHoras .= ", tiporegistro.rh187_sequencial as sequencial";
      $sCamposJornadaHoras .= ", tiporegistro.rh187_descricao as descricao";

      $sSqlJornadaHoras = "select {$sCamposJornadaHoras}
                             from jornadahoras 
                                  inner join tiporegistro on tiporegistro.rh187_sequencial = jornadahoras.rh189_tiporegistro
                            where jornadahoras.rh189_jornada = {$oParametros->iCodigoJornada} 
                            order by tiporegistro.rh187_sequencial";

      $rsJornadasHoras  = db_query($sSqlJornadaHoras);
      $aJornadaHoras    = db_utils::getCollectionByRecord($rsJornadasHoras);

      $aRetornoJornadas = array();

      foreach ($aJornadaHoras as $oJornadaHora) {

        $oJornada = new stdClass();
        $oJornada->iCodigoTipo    = $oJornadaHora->sequencial;
        $oJornada->sDescricaoTipo = $oJornadaHora->descricao;
        $oJornada->sHora          = $oJornadaHora->hora;

        $aRetornoJornadas[]       = $oJornada;
      }

      $oRetorno->aRetornoJornadas = $aRetornoJornadas;

      break;

    case 'salvarHorarios':

      $oDaoJornadaHoras = new cl_jornadahoras;
      $oDaoJornadaHoras->excluir(null, "rh189_jornada = $oParametros->iCodigoJornada");

      if ($oDaoJornadaHoras->erro_status == '0') {
        throw new Exception('Erro ao excluir dados de jornadahoras. ERRO: ' . $oDaoJornadaHoras->erro_msg);
      }

      foreach ($oParametros->aDados as $oJornadaHorario) {

        $oDaoJornadaHoras->rh189_sequencial   = '';
        $oDaoJornadaHoras->rh189_jornada      = $oParametros->iCodigoJornada;
        $oDaoJornadaHoras->rh189_tiporegistro = $oJornadaHorario->iCodigoTipoRegistro;
        $oDaoJornadaHoras->rh189_hora         = $oJornadaHorario->sHora;
        $oDaoJornadaHoras->incluir(null);

        if ($oDaoJornadaHoras->erro_status == '0') {
          throw new Exception('Erro ao incluir dados em jornadahoras. ERRO: ' . $oDaoJornadaHoras->erro_msg);
        }
      }

      $oRetorno->message = "Salvo com sucesso.";

      break;

  }
} catch (Exception $eException) {
  $oRetorno->message = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);