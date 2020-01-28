<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

define('EDU4_RECHUMANOATIVIDADERPC', 'educacao.escola.edu4_rechumanoatividadeRPC.');

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscaTipoHoraPorAtividade":

      $oAtividade = new AtividadeEscolar($oParam->iAtividade);

      $oRetorno->aTiposHora = array();
      foreach (TipoHoraTrabalhoRepository::getByAtividade($oAtividade) as $oTipoHora) {

        if ( !$oTipoHora->isAtivo() ) {
          continue;
        }

        $oDados               = new stdClass();
        $oDados->iCodigo      = $oTipoHora->getCodigo();
        $oDados->iEfetividade = $oTipoHora->getEfetividade();
        $oDados->sDescricao   = urlencode( $oTipoHora->getDescricao() );

        $oRetorno->aTiposHora[] = $oDados;
      }

      break;
    case 'buscaAtividadesProfissional':

      $oRetorno->aAtividades = array();
      $oProfissional         = ProfissionalEscolaRepository::getByCodigo($oParam->iVinculoEscola);

      foreach ($oProfissional->getAtividades() as $oAtividade) {

        if ( !$oAtividade->isAtivo() ) {
          continue;
        }

        $oDadosAtividade = new stdClass();
        $oDadosAtividade->iCodigo          = $oAtividade->getCodigo();
        $oDadosAtividade->iCodigoAtividade = $oAtividade->getAtividadeEscolar()->getCodigo();
        $oDadosAtividade->sDescricao       = utf8_encode( $oAtividade->getAtividadeEscolar()->getDescricao() );

        $oDadosAtividade->lPermiteVincularEnsino = $oAtividade->getAtividadeEscolar()->permiteInformarRelacaoTrabalho();
        $oDadosAtividade->lAtividadeSemRegencia  = $oAtividade->getAtividadeEscolar()->getAtividadeEscolar();

        $oAtoLegal                      = $oAtividade->getAtoLegal();
        $oDadosAtividade->iCodigoAto    = "";
        $oDadosAtividade->sDescricaoAto = "";
        if ( !is_null($oAtoLegal) ) {

          $oDadosAtividade->iCodigoAto    = $oAtividade->getAtoLegal()->getCodigoAtoLegal();
          $oDadosAtividade->sDescricaoAto = utf8_encode( $oAtividade->getAtoLegal()->getFinalidade() );
        }

        $oDadosAtividade->aResumoTurno = array();
        $oDadosAtividade->aAgendas     = array();

        foreach ( $oAtividade->getAgenda() as $oAgenda ) {

          $oDadosAgenda              = new stdClass();
          $oDadosAgenda->iCodigo     = $oAgenda->getCodigo();
          $oDadosAgenda->iDiaSemana  = $oAgenda->getDiaSemana();
          $oDadosAgenda->sDiaSemana  = utf8_encode( $oAgenda->getNomeDiaSemana() );
          $oDadosAgenda->iTurno      = $oAgenda->getTurnoReferente();
          $oDadosAgenda->sTurno      = utf8_encode( $oAgenda->getDescricaoTurno() );
          $oDadosAgenda->sHoraInicio = $oAgenda->getHoraInicio();
          $oDadosAgenda->sHoraFim    = $oAgenda->getHoraFim();

          $oDadosAgenda->iTipoHoraTrabalho = $oAgenda->getTipoHoraTrabalho()->getCodigo();
          $oDadosAgenda->sTipoHoraTrabalho = utf8_encode( $oAgenda->getTipoHoraTrabalho()->getDescricao() );

          $oDadosAtividade->aAgendas[]     = $oDadosAgenda;

          /**
           * Cria um resumo da agenda contendo o turno e o tipo de hora de trabalho
           */
          $sHash   = $oDadosAgenda->iTurno . "#" . $oDadosAgenda->iTipoHoraTrabalho;
          $oResumo = new stdClass();

          $oResumo->sTurno            = $oDadosAgenda->sTurno;
          $oResumo->sTipoHoraTrabalho = $oDadosAgenda->sTipoHoraTrabalho;
          $oResumo->iTurno            = $oDadosAgenda->iTurno;
          $oResumo->iTipoHoraTrabalho = $oDadosAgenda->iTipoHoraTrabalho;

          if ( !array_key_exists($sHash, $oDadosAtividade->aResumoTurno) ) {
            $oDadosAtividade->aResumoTurno[$sHash] = $oResumo;
          }
        }

        $oRetorno->aAtividades[] = $oDadosAtividade;

      }

      break;

    case 'excluirAtividade':

      $oAtividadeProfissional = AtividadeProfissionalEscolaRepository::getByCodigo($oParam->iCodigo);

      if ( $oAtividadeProfissional->possuiRelacaoTrabalhoVinculado() ) {
        throw new BusinessException( _M( EDU4_RECHUMANOATIVIDADERPC . "vinculo_relacao_trabalho" ) );
      }
      
      foreach ($oParam->aAgendas as $oDadosAgendaExcluir) {

        $oAgendaProfissional = AgendaAtividadeProfissionalRepository::getByCodigo($oDadosAgendaExcluir->iCodigoAgenda);
        $oAgendaProfissional->excluir();
      }

      if ( count( $oAtividadeProfissional->getAgenda()) == 0 ) {
        $oAtividadeProfissional->excluir();
      }

      $oRetorno->sMessage = urlencode(_M( EDU4_RECHUMANOATIVIDADERPC . "atividade_excluida_sucesso" ));

      break;

    case 'salvarAtividade':

      $oProfisonal = ProfissionalEscolaRepository::getByCodigo($oParam->iVinculoEscola);
      $aConflitos  = array();

      $iEscolaProfissional = $oProfisonal->getEscola()->getCodigo();
      $aTodasAgendas       = buscaTodasAgendasProfissional($oParam->iVinculoEscola);

      // data qualquer para poder converter a hora em timestamp
      $sData = date(DBDate::DATA_EN);

      /**
       * Valida conflito entre de agenda OUTRAS ESCOLAS
       */
      if ( count($aTodasAgendas) > 0 ) {

        foreach ($oParam->aSalvarAgenda as $oDadosSalvar) {

          foreach ($aTodasAgendas as $oTodasAgendas) {

            if ( ($iEscolaProfissional == $oTodasAgendas->escola) || ($oDadosSalvar->iDiaSemana != $oTodasAgendas->codigo_dia) ) {
              continue;
            }

            if ( !empty($oTodasAgendas->data_saida) ) {

              if ( strtotime($sData) <= strtotime($oTodasAgendas->data_saida) ) {

                $oEscolaConflito = EscolaRepository::getEscolaByCodigo($oTodasAgendas->escola);
                $sEscolaConflito = $oEscolaConflito->getNome();

                $oMsgErro = new stdClass();
                $oMsgErro->sHoraInicioSalvar = $oDadosSalvar->sHoraInicio;
                $oMsgErro->sHoraFimSalvar    = $oDadosSalvar->sHoraFim;
                $oMsgErro->sDiaSemana        = $oTodasAgendas->dia;
                $oMsgErro->sAtividade        = $oTodasAgendas->atividade;
                $oMsgErro->sEscola           = $sEscolaConflito;
                $oMsgErro->sHoraInicioAgenda = $oTodasAgendas->hora_inicio;
                $oMsgErro->sHoraFimAgenda    = $oTodasAgendas->hora_fim;
                $aConflitos[]                = $oMsgErro;
              }
            } else {

              $iTimeHoraInicioSalvar = strtotime("{$sData} {$oDadosSalvar->sHoraInicio}");
              $iTimeHoraFimSalvar    = strtotime("{$sData} {$oDadosSalvar->sHoraFim}");
              $iTimeHoraInicioAgenda = strtotime("{$sData} {$oTodasAgendas->hora_inicio}" );
              $iTimeHoraFimAgenda    = strtotime("{$sData} {$oTodasAgendas->hora_fim}" );

              if ( $iTimeHoraInicioSalvar < $iTimeHoraFimAgenda && $iTimeHoraFimSalvar >= $iTimeHoraInicioAgenda ) {

                $oEscolaConflito = EscolaRepository::getEscolaByCodigo($oTodasAgendas->escola);
                $sEscolaConflito = $oEscolaConflito->getNome();

                $oMsgErro = new stdClass();
                $oMsgErro->sHoraInicioSalvar = $oDadosSalvar->sHoraInicio;
                $oMsgErro->sHoraFimSalvar    = $oDadosSalvar->sHoraFim;
                $oMsgErro->sDiaSemana        = $oTodasAgendas->dia;
                $oMsgErro->sAtividade        = $oTodasAgendas->atividade;
                $oMsgErro->sEscola           = $sEscolaConflito;
                $oMsgErro->sHoraInicioAgenda = $oTodasAgendas->hora_inicio;
                $oMsgErro->sHoraFimAgenda    = $oTodasAgendas->hora_fim;
                $aConflitos[]                = $oMsgErro;
              }
            }

          }
        }
      } // Valida conflito entre de agenda OUTRAS ESCOLAS

      /**
       * Monta a mensagens de conflito em outra escola.
       */
      if (count($aConflitos) > 0) {

        $aMsg     = array();
        $sMsgErro = _M( EDU4_RECHUMANOATIVIDADERPC . "msg_conflito_salvar" ) ."\n";
        foreach ($aConflitos as $oDadosErro) {
           $aMsg[] = _M( EDU4_RECHUMANOATIVIDADERPC . "dados_conflito_escola", $oDadosErro);
        }
        $sMsgErro .= implode("\n", $aMsg);

        throw new Exception($sMsgErro);
      }


      /**
       * Valida conflito entre a agenda do profissional NA ESCOLA
       */
      foreach ($oProfisonal->getAtividades() as $oAtividade) {

        // percorremos as agendas que serão salva e validamos com as agendas já inclusas
        foreach ($oParam->aSalvarAgenda as $iIndex => $oDados) {

          // prercorremos as agendas inclusas
          foreach ($oAtividade->getAgenda() as $oAgenda) {

            if ( $oDados->iDiaSemana != $oAgenda->getDiaSemana() || $oParam->iTurno != $oAgenda->getTurnoReferente()) {
              continue;
            }

            if ( $oParam->iTipoHora != $oAgenda->getTipoHoraTrabalho()->getCodigo() ) {


              $iTimeHoraInicioSalvar = strtotime("{$sData} {$oDados->sHoraInicio}");
              $iTimeHoraFimSalvar    = strtotime("{$sData} {$oDados->sHoraFim}");

              $iTimeHoraInicioAgenda = strtotime("{$sData} " . $oAgenda->getHoraInicio() );
              $iTimeHoraFimAgenda    = strtotime("{$sData} " . $oAgenda->getHoraFim() );

              // Valida sobreposição dos horários
              if ( $iTimeHoraInicioSalvar < $iTimeHoraFimAgenda && $iTimeHoraFimSalvar >= $iTimeHoraInicioAgenda ) {

                $oMsgErro = new stdClass();
                $oMsgErro->sHoraInicioSalvar = $oDados->sHoraInicio;
                $oMsgErro->sHoraFimSalvar    = $oDados->sHoraFim;
                $oMsgErro->sDiaSemana        = $oAgenda->getNomeDiaSemana();
                $oMsgErro->sTurno            = $oAgenda->getDescricaoTurno();
                $oMsgErro->sTipoHoraAgenda   = $oAgenda->getTipoHoraTrabalho()->getDescricao();
                $oMsgErro->sHoraInicioAgenda = $oAgenda->getHoraInicio();
                $oMsgErro->sHoraFimAgenda    = $oAgenda->getHoraFim();
                $aConflitos[$iIndex] = $oMsgErro;

                continue 2;
              }
            }
          }
        }
      } // fim validação conflito dentro da agenda

      /**
       * Monta a mensagens de conflito dentro da escola
       */
      if (count($aConflitos) > 0) {

        $aMsg     = array();
        $sMsgErro = _M( EDU4_RECHUMANOATIVIDADERPC . "msg_conflito_salvar" ) ."\n";
        foreach ($aConflitos as $oDadosErro) {
           $aMsg[] = _M( EDU4_RECHUMANOATIVIDADERPC . "dados_conflito_dia", $oDadosErro);
        }
        $sMsgErro .= implode("\n", $aMsg);

        throw new Exception($sMsgErro);
      }


      $oTipoHoraTrabalho = TipoHoraTrabalhoRepository::getByCodigo($oParam->iTipoHora);
      $oAtividadeEscolar = AtividadeEscolarRepository::getByCodigo($oParam->iAtividade);

      $oAtividadeProfissional = new AtividadeProfissionalEscola();
      // código da agenda do profissional
      if ( !empty($oParam->iCodigo) ) {
        $oAtividadeProfissional = AtividadeProfissionalEscolaRepository::getByCodigo($oParam->iCodigo);
      }
      //define o ato legal caso tenha
      if ( !empty( $oParam->iAtoLegal ) ) {
        $oAtividadeProfissional->setAtoLegal(AtoLegalRepository::getAtoLegalByCodigo($oParam->iAtoLegal));
      }
      $oAtividadeProfissional->setAtivo(true);
      $oAtividadeProfissional->setProfissionalEscola($oProfisonal);
      $oAtividadeProfissional->setAtividadeEscolar($oAtividadeEscolar);

      foreach ($oParam->aSalvarAgenda as $iIndex => $oDados) {

        /**
         * @todo quando for alterar, temos que validar conflito com os Horários de Regencia (rechumanohoradisp)
         */
        $oSalvarAgenda = new AgendaAtividadeProfissional();
        if ( !empty($oDados->iCodigoAgenda) ) {
          $oSalvarAgenda = AgendaAtividadeProfissionalRepository::getByCodigo($oDados->iCodigoAgenda);
        }
        $oSalvarAgenda->setTipoHoraTrabalho($oTipoHoraTrabalho);
        $oSalvarAgenda->setDiaSemana($oDados->iDiaSemana);
        $oSalvarAgenda->setTurnoReferente($oParam->iTurno);
        $oSalvarAgenda->setHoraInicio($oDados->sHoraInicio);
        $oSalvarAgenda->setHoraFim($oDados->sHoraFim);


        $oAtividadeProfissional->addAgenda($oSalvarAgenda);
      }

      $oProfisonal->addAtividade($oAtividadeProfissional);
      $oProfisonal->salvar();


      foreach ($oParam->aExcluirAgenda as $oDados) {

        $oSalvarAgenda = AgendaAtividadeProfissionalRepository::getByCodigo($oDados->iCodigoAgenda);
        $oSalvarAgenda->excluir();
      }

      $oRetorno->sMessage = urlencode(_M( EDU4_RECHUMANOATIVIDADERPC . "atividade_salva_sucesso" ));

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

function buscaTodasAgendasProfissional($iVinculoEscola) {

  $sCampos  = " ed75_i_escola as escola, ed129_horainicio as hora_inicio,  ed129_horafim as hora_fim, ";
  $sCampos .= " ed129_diasemana as codigo_dia, trim(ed32_c_descr) as dia, ";
  $sCampos .= " ed22_i_atividade as codigo_atividade, trim(ed01_c_descr) as atividade, ed75_i_saidaescola as data_saida " ;

  $sSqlFiltro  = " select ed75_i_rechumano from rechumanoescola ";
  $sSqlFiltro .= " where ed75_i_codigo = {$iVinculoEscola} and ed75_i_saidaescola is null ";
  $sWhere      = " ed75_i_rechumano in ({$sSqlFiltro}) ";
  $oDao        = new cl_rechumanoativ();

  $sSqlValidaConflito = $oDao->sql_query_agenda_atividade(null, $sCampos, " ed22_i_rechumanoescola ", $sWhere);
  $rsValidaConflito   = db_query($sSqlValidaConflito);

  $oMsgErro = new stdClass();
  if ( !$rsValidaConflito ) {

    $oMsgErro->sErro = pg_last_error();
    throw new Exception(_M( EDU4_RECHUMANOATIVIDADERPC . "erro_buscar_agendas_profissional", $oMsgErro));
  }

  $aAgendaAtividade = array();
  $iLinhas          = pg_num_rows($rsValidaConflito);
  for ($i = 0; $i < $iLinhas; $i++) {
    $aAgendaAtividade[] = db_utils::fieldsMemory($rsValidaConflito, $i);
  }

  return $aAgendaAtividade;
}

$oRetorno->erro = $oRetorno->iStatus == 2;

echo $oJson->encode($oRetorno);