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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

define( 'MSG_EDU4_HORARIOAULA_RPC', 'educacao.escola.edu4_horarioaulaRPC.' );

$iEscola = db_getsession("DB_coddepto");
$oJson   = new Services_JSON();
$oParam  = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->iStatus = 1;

try {

  switch ($oParam->exec) {
    case 'getTurnos':
      /**
       * Retorna os turnos cadastrados na Secretaria da Educacao
       */

      $oRetorno->aTurnos = array();
      foreach( TurnoRepository::getTurnosCadastrados() as $oTurno) {

        $oDado               = new stdClass();
        $oDado->iCodigo      = $oTurno->getCodigoTurno();
        $oDado->sDescricao   = urlencode($oTurno->getDescricao());
        $oDado->aTurnosReferente = $oTurno->getTurnoReferente();
        $oRetorno->aTurnos[] = $oDado;
      }

      break;

    case 'getPeriodosEscola':
      /**
       * Retorna os períodos de aula cadastrados na Secretaria da Educacao
       */
      $oDaoPeriodoAula = new cl_periodoaula();
      $sSqlPeriodo     = $oDaoPeriodoAula->sql_query_file(null, "*", "ed08_i_sequencia");
      $rsPeriodo       = $oDaoPeriodoAula->sql_record( $sSqlPeriodo );
      $oRetorno->aPeriodos = array();
      if ($rsPeriodo && $oDaoPeriodoAula->numrows > 0)  {

        $iLinhas = $oDaoPeriodoAula->numrows;
        for ($i = 0; $i < $iLinhas; $i++) {

          $oDados               = db_utils::fieldsMemory($rsPeriodo, $i);
          $oPeriodo             = new stdClass();
          $oPeriodo->iCodigo    = $oDados->ed08_i_codigo;
          $oPeriodo->sDescricao = urlencode($oDados->ed08_c_descr);
          $oPeriodo->iOrdem     = $oDados->ed08_i_sequencia;

          $oRetorno->aPeriodos[] = $oPeriodo;
        }
      }

      break;

    case 'salvarPeriodoAula':

      $oEscola = EscolaRepository::getEscolaByCodigo($iEscola);
      $oTurno  = TurnoRepository::getTurnoByCodigo($oParam->iTurno);

      db_inicio_transacao();

      $sWhere           = "     ed85_i_turno  = {$oParam->iTurno} ";
      $sWhere          .= " and ed85_i_escola = {$iEscola}        ";
      $oDaoCursoTurno   = new cl_cursoturno();
      $sSqlVinculoCurso = $oDaoCursoTurno->sql_query_file(null, "1", null, $sWhere);
      $rsVinculoCurso   = db_query($sSqlVinculoCurso);

      /**
       * Se o turno do período selecionado possuir vinculo com curso, não podemos excluir todos períodos
       * --> Note que $oParam->aPeriodos possui os periodos para inclusão ou alteração.
       */
      if ( ($rsVinculoCurso && pg_num_rows($rsVinculoCurso) > 0) && (count($oParam->aPeriodos) == 0) ) {
        throw new Exception( _M(MSG_EDU4_HORARIOAULA_RPC . "impossivel_excluir_periodos") );
      }

      if ( count($oParam->aPeriodosExcluidos) > 0) {

        foreach ($oParam->aPeriodosExcluidos as $iCodigoPeriodoEscola) {

          $oPeriodoEscola = new PeriodoEscola($iCodigoPeriodoEscola);
          $oPeriodoEscola->remover();
        }
      }


      foreach ($oParam->aPeriodos as $oPeriodo) {

        $oPeriodoEscola = new PeriodoEscola();

        if($oPeriodo->aTurnoReferentePeriodo[0] == "null") {
          throw new Exception(_M(MSG_EDU4_HORARIOAULA_RPC . "erro_periodo_sem_referencia"));
        }

        if ( !empty($oPeriodo->iCodigoVinculo) ) {
          $oPeriodoEscola = new PeriodoEscola($oPeriodo->iCodigoVinculo);
        }
        $oPeriodoEscola->setTurno( $oTurno );
        $oPeriodoEscola->setEscola( $oEscola );
        $oPeriodoEscola->setPeriodoAula($oPeriodo->iPeriodo);
        $oPeriodoEscola->setHoraInicio($oPeriodo->sHoraInicio);
        $oPeriodoEscola->setHoraFim($oPeriodo->sHoraFim);
        $oPeriodoEscola->setDuracao($oPeriodo->sDuracao);
        $oPeriodoEscola->setTurnoReferentePeriodo( $oPeriodo->aTurnoReferentePeriodo );
        $oPeriodoEscola->salvar();

      }

      $oRetorno->sMessage = urlencode( _M(MSG_EDU4_HORARIOAULA_RPC . "periodos_salvos") );

      db_fim_transacao();

      break;

    case 'removerPeriodoAula':

      db_inicio_transacao();

      $sWhere           = "     ed85_i_turno  = {$oParam->iTurno} ";
      $sWhere          .= " and ed85_i_escola = {$iEscola}        ";
      $oDaoCursoTurno   = new cl_cursoturno();
      $sSqlVinculoCurso = $oDaoCursoTurno->sql_query_file(null, "1", null, $sWhere);
      $rsVinculoCurso   = db_query($sSqlVinculoCurso);

      /**
       * Se o turno do período selecionado possuir vinculo com curso, não podemos excluir todos períodos
       */
      if ( $rsVinculoCurso && pg_num_rows($rsVinculoCurso) > 0) {
        throw new Exception(_M(MSG_EDU4_HORARIOAULA_RPC . "impossivel_excluir_periodos") );
      }

      foreach ($oParam->aPeriodos as $iCodigoPeriodoEscola) {

        $oPeriodoEscola = new PeriodoEscola($iCodigoPeriodoEscola);
        $oPeriodoEscola->remover();
      }
      db_fim_transacao();
      $oRetorno->sMessage = urlencode( _M(MSG_EDU4_HORARIOAULA_RPC . "periodos_excluido") );

      break;

    case 'getPeriodosVinculados':
      /**
       * Retorna os períodos de aula da escola organizado pelo turno
       */
      $aPeriodosEscola = array();
      $oEscola         = EscolaRepository::getEscolaByCodigo($iEscola);

      /**
       * Busca todos períodos da escola
       */
      foreach ($oEscola->getPeriodosEscola() as $oPeriodoEscola) {

        $iTurno = $oPeriodoEscola->getTurno()->getCodigoTurno();
        $iOrdem = $oPeriodoEscola->getTurno()->getOrdem();
        $sTurno = urlencode( $oPeriodoEscola->getTurno()->getDescricao() );

        $sHash = "{$iOrdem}#{$iTurno}";

        if ( !array_key_exists($sHash, $aPeriodosEscola) ) {

          $oDadosTurno              = new stdClass();
          $oDadosTurno->iTurno      = $iTurno;
          $oDadosTurno->sTurno      = $sTurno;
          $oDadosTurno->sHoraInicio = "";
          $oDadosTurno->sHoraFim    = "";
          $oDadosTurno->aPeriodos   = array();
          $aPeriodosEscola[$sHash] = $oDadosTurno;
        }

        $oPeriodo                           = new stdClass();
        $oPeriodo->iTurno                   = $iTurno;
        $oPeriodo->sTurno                   = $sTurno;
        $oPeriodo->iCodigoVinculo           = $oPeriodoEscola->getCodigo();
        $oPeriodo->iCodigoPeriodo           = $oPeriodoEscola->getPeriodoAula();
        $oPeriodo->sDescricaoPeriodo        = urlencode( $oPeriodoEscola->getDescricao() );
        $oPeriodo->iOrdem                   = $oPeriodoEscola->getOrdem();
        $oPeriodo->sHoraInicio              = $oPeriodoEscola->getHoraInicio();
        $oPeriodo->sHoraFim                 = $oPeriodoEscola->getHoraFim();
        $oPeriodo->sDuracao                 = $oPeriodoEscola->getDuracao();
        $oPeriodo->aTurnosReferentesPeriodo = $oPeriodoEscola->getTurnoReferentePeriodo();

        $aPeriodosEscola[$sHash]->aPeriodos[] = $oPeriodo;
      }


      /**
       * Percorre os períodos de cada turno para verificar o horário inicial e final de cada turno
       */
      foreach ($aPeriodosEscola as $sHash => $oPeriodosTurno) {

        $sHoraInicio  = "";
        $sHoraFim     = "";
        $iOrdemInicio = 999;
        $iOrdemFim    = 0;
        foreach ($oPeriodosTurno->aPeriodos as $oPeriodo) {

          if ( $oPeriodo->iOrdem < $iOrdemInicio ) {

            $iOrdemInicio = $oPeriodo->iOrdem;
            $sHoraInicio  = $oPeriodo->sHoraInicio;
          }
          if ( $oPeriodo->iOrdem > $iOrdemFim   ) {

            $iOrdemFim = $oPeriodo->iOrdem;
            $sHoraFim  = $oPeriodo->sHoraFim;
          }

        }
        $oPeriodosTurno->sHoraInicio = $sHoraInicio;
        $oPeriodosTurno->sHoraFim    = $sHoraFim;
      }

      $oRetorno->aPeriodosEscola = $aPeriodosEscola;

      break;

  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
