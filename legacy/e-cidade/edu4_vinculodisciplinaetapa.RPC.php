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

define("URLMSG_VINCULODISCIPLINAETAPA", "educacao.escola.edu4_vinculodisciplinaetapa_RPC.");

$iEscola            = db_getsession("DB_coddepto");
$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->sStatus  = 1;
$oRetorno->sMessage = '';

try {

  switch ($oParam->exec) {

    case 'getEtapasBase':

      $sFiltraEscola = " ed77_i_escola = {$iEscola} ";
      if ( isset($oParam->iEscola)) {
        $sFiltraEscola = " ed77_i_escola = {$oParam->iEscola} ";
      }
      if ( empty($oParam->lFiltarEscola) && !$oParam->lFiltarEscola ) {
        $sFiltraEscola = "";
      }

      $aWhere = array();
      if ( !empty($sFiltraEscola) ) {
        $aWhere[] = $sFiltraEscola;
      }
      $aWhere[] = " ed31_i_codigo = {$oParam->iBase} ";
      $oDaoBaseSerie = new cl_baseserie();

      $sCampos       = "si.ed11_i_sequencia as inicial, sf.ed11_i_sequencia as final, si.ed11_i_ensino as ensino";
      $sSqlBaseSerie = $oDaoBaseSerie->sql_query_etapa_base(null, $sCampos, null, implode(" and ", $aWhere));
      $rsBaseSerie   = db_query($sSqlBaseSerie);

      $oMsgErro = new stdClass();
      if (!$rsBaseSerie || pg_num_rows($rsBaseSerie) == 0) {

        $oMsgErro->sErroSql = pg_last_error();
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_buscar_series_base", $oMsgErro) );
      }

      $oEtapasBase   = db_utils::fieldsMemory($rsBaseSerie, 0);
      $sWhereEtapas  = "     ed11_i_sequencia between {$oEtapasBase->inicial} and {$oEtapasBase->final} ";
      $sWhereEtapas .= " and ed11_i_ensino = {$oEtapasBase->ensino} ";
      $sOrdem        = " ed11_i_sequencia ";

      $oDaoSerie = new cl_serie();
      $sSqlSerie = $oDaoSerie->sql_query_file(null, "*", $sOrdem, $sWhereEtapas);
      $rsSeries  = db_query($sSqlSerie);

      if (!$rsSeries) {

        $oMsgErro->sErroSql = pg_last_error();
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_buscar_series", $oMsgErro));
      }
      $iLinhas = 0;
      if ( pg_num_rows($rsSeries) == 0) {
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "nenhuma_serie_encontrada", $oMsgErro));
      }

      $iLinhas = pg_num_rows($rsSeries);
      $oRetorno->aEtapas = array();

      for ($i = 0; $i < $iLinhas; $i++) {

        $oDado                = db_utils::fieldsMemory($rsSeries, $i);
        $oSerie               = new stdClass();
        $oSerie->iCodigo      = $oDado->ed11_i_codigo;
        $oSerie->iEnsino      = $oDado->ed11_i_ensino;
        $oSerie->sDescricao   = urlencode($oDado->ed11_c_descr);
        $oSerie->sAbreviatura = urlencode($oDado->ed11_c_abrev);
        $oSerie->iOrdem       = $oDado->ed11_i_sequencia;
        $oSerie->iCodigoCenso = $oDado->ed11_i_codcenso;

        $oRetorno->aEtapas[]  = $oSerie;
      }
      break;

    case 'getEtapasTurma':

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

      $oRetorno->aEtapas = array();
      foreach ($oTurma->getEtapas() as $oEtapaTurma) {

        $oSerie               = new stdClass();
        $oSerie->iCodigo      = $oEtapaTurma->getEtapa()->getCodigo();
        $oSerie->iEnsino      = $oEtapaTurma->getEtapa()->getEnsino()->getCodigo();
        $oSerie->sDescricao   = urlencode($oEtapaTurma->getEtapa()->getNome());
        $oSerie->sAbreviatura = urlencode($oEtapaTurma->getEtapa()->getNomeAbreviado());
        $oSerie->iOrdem       = $oEtapaTurma->getEtapa()->getOrdem();
        $oSerie->iCodigoCenso = $oEtapaTurma->getEtapa()->getEtapaCenso();
        $oRetorno->aEtapas[]  = $oSerie;
      }

      break;

    case 'getDisciplinasVinculadasEtapaBase':

      $sCampos  = " ed34_i_codigo, ed34_i_disciplina, ed232_c_descr, ed34_i_qtdperiodo, ed34_c_condicao, ed34_i_ordenacao, ";
      $sCampos .= " ed34_lancarhistorico, ed34_disiciplinaglobalizada, ed34_caracterreprobatorio, ed34_basecomum ";
      $sOrdem   = " ed34_basecomum desc, ed34_i_ordenacao, ed232_c_descr ";
      $sWhere   = "     ed34_i_base  = {$oParam->iBase} ";
      $sWhere  .= " and ed34_i_serie = {$oParam->iEtapa} ";

      $oDaoBaseMps = new cl_basemps();
      $sSqlBaseMps = $oDaoBaseMps->sql_query(null, $sCampos, $sOrdem, $sWhere);
      $rsBaseMps   = db_query($sSqlBaseMps);

      $oMsgErro = new stdClass();
      if ( !$rsBaseMps ) {

        $oMsgErro->sErroSql = pg_last_error();
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_buscar_disciplinas", $oMsgErro));
      }

      $oRetorno->lTemDisciplinaGlobalizada = false;

      $iLinhas      = pg_num_rows($rsBaseMps);
      $aDisciplinas = array();
      for ($i = 0; $i < $iLinhas; $i++) {

        $oDados      = db_utils::fieldsMemory($rsBaseMps, $i);
        $oDisciplina = new stdClass();

        $oDisciplina->iCodigo                 = $oDados->ed34_i_codigo;
        $oDisciplina->iDisicplina             = $oDados->ed34_i_disciplina;
        $oDisciplina->sDisciplina             = urlencode( $oDados->ed232_c_descr );
        $oDisciplina->iQtdPeriodo             = $oDados->ed34_i_qtdperiodo;
        $oDisciplina->lObrigatoria            = $oDados->ed34_c_condicao == 'OB';
        $oDisciplina->iOrdem                  = $oDados->ed34_i_ordenacao;
        $oDisciplina->lLancarDocumentacao     = $oDados->ed34_lancarhistorico        == 't';
        $oDisciplina->lGlobalizada            = $oDados->ed34_disiciplinaglobalizada == 't';
        $oDisciplina->lCaracterReprobatorio   = $oDados->ed34_caracterreprobatorio   == 't';
        $oDisciplina->lBaseComum              = $oDados->ed34_basecomum              == 't';
        $oDisciplina->lEncerrada              = false; //Variável para manter compatibilidade com os dados da regência
        $oDisciplina->sTipoControleFrequencia = 'A';   //Variável para manter compatibilidade com os dados da regência
        $aDisciplinas[]                       = $oDisciplina;

        if ($oDisciplina->lGlobalizada) {
          $oRetorno->lTemDisciplinaGlobalizada  = $oDisciplina->lGlobalizada;
        }
      }

      $oRetorno->aDisciplinas = $aDisciplinas;

      break;

    case 'getDisciplinasVinculadasEtapaTurma':

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);

      $oRetorno->aDisciplinas = array();

      foreach ( $oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

        $aTipoFrequenciaGlobal = array('F', 'FA');

        $oDisciplina                            = new stdClass();
        $oDisciplina->iCodigo                   = $oRegencia->getCodigo();
        $oDisciplina->iDisicplina               = $oRegencia->getDisciplina()->getCodigoDisciplina();
        $oDisciplina->sDisciplina               = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
        $oDisciplina->iQtdPeriodo               = $oRegencia->getHorasAula();
        $oDisciplina->lObrigatoria              = $oRegencia->isObrigatoria();
        $oDisciplina->iOrdem                    = $oRegencia->getOrdem();
        $oDisciplina->lLancarDocumentacao       = $oRegencia->isLancadaNoHistorico();
        $oDisciplina->lGlobalizada              = in_array($oRegencia->getFrequenciaGlobal(), $aTipoFrequenciaGlobal) ;
        $oDisciplina->lCaracterReprobatorio     = $oRegencia->possuiCaracterReprobatorio();
        $oDisciplina->lBaseComum                = $oRegencia->isBaseComum();
        $oDisciplina->lEncerrada                = $oRegencia->isEncerrada() || $oRegencia->parcialmenteEncerrada();
        $oDisciplina->sTipoControleFrequencia   = $oRegencia->getFrequenciaGlobal();
        $oDisciplina->lTemDisciplinaGlobalizada = $oDisciplina->lGlobalizada;
        $oDisciplina->sProcedimentoAvalicao     = urlencode($oRegencia->getProcedimentoAvaliacao()->getDescricao());

        $oRetorno->aDisciplinas[] = $oDisciplina;
      }

      break;

    case 'excluirDisciplinaBase':

      if (empty($oParam->iCodigoVinculo)) {
        throw new ParameterException(_M(URLMSG_VINCULODISCIPLINAETAPA . "erro_codigo_vinculo"));
      }

      db_inicio_transacao();
      $oDaoBaseMps = new cl_basemps();
      $oDaoBaseMps->excluir($oParam->iCodigoVinculo);

      $oMsgErro = new stdClass();
      if ($oDaoBaseMps->erro_status == 0) {

        $oMsgErro->sErroSql = pg_last_error();
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_excluir_vinculo_base", $oMsgErro));
      }
      $oRetorno->sMessage = urlencode( _M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_removida") );

      db_fim_transacao();
      break;

    case 'excluirDisciplinaTurma':

      db_inicio_transacao();
      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iCodigoVinculo);
      $oRegencia->excluir();
      db_fim_transacao();
      $oRetorno->sMessage = urlencode( _M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_removida") );

      break;

    case 'salvarDisciplinaBase' :

      db_inicio_transacao();
      salvarDisciplinaBase($oParam);
      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_salvar"));

      break;

    case 'salvarDisciplinaTurma':

      db_inicio_transacao();

      if (salvarDisciplinaTurma($oParam) ) {
        $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_salvar"));
      }
      db_fim_transacao();

      break;

    case 'replicarDisciplinasBase':

      $oDaoBaseMps = new cl_basemps();
      db_inicio_transacao();

      foreach( $oParam->aEtapas as $iIndice => $iEtapa ) {

        $oParam->iEtapa = $iEtapa;

        $sWhere  = "     ed34_i_base       = {$oParam->iBase}";
        $sWhere .= " and ed34_i_serie      = {$oParam->iEtapa}";


        if ($oParam->lGlobalizada) {

          $sWhereValidaGlobalizada  = $sWhere;
          $sWhereValidaGlobalizada .= " and ed34_disiciplinaglobalizada is true";
          $sSqlTemGlobalizada       = $oDaoBaseMps->sql_query_file(null, " 1 ", null, $sWhereValidaGlobalizada);
          $rsTemGlobalizada         = db_query($sSqlTemGlobalizada);

          if ( pg_num_rows($rsTemGlobalizada) > 0 ) {
            continue;
          }
        }

        $sWhereDisciplina = " and ed34_i_disciplina = {$oParam->iDisicplina}";

        $sSqlBaseMps = $oDaoBaseMps->sql_query_file(null, " ed34_i_codigo ", null, $sWhere.$sWhereDisciplina);
        $rsBaseMps   = db_query($sSqlBaseMps);

        $oParam->iEtapa         = $iEtapa;
        $oParam->iCodigoVinculo = '';
        if ($rsBaseMps && pg_num_rows($rsBaseMps) > 0) {
          $oParam->iCodigoVinculo = db_utils::fieldsMemory($rsBaseMps, 0)->ed34_i_codigo;
        }

        salvarDisciplinaBase($oParam);
      }

      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_salvar"));

      break;

    case 'replicarDisciplinasTurma':

      $oTurma      = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oDisciplina = DisciplinaRepository::getDisciplinaByCodigo($oParam->iDisicplina);

      db_inicio_transacao();
      foreach( $oParam->aEtapas as $iIndice => $iEtapa ) {

        $oParam->iEtapa         = $iEtapa;
        $oParam->iCodigoVinculo = '';

        $oEtapa    = EtapaRepository::getEtapaByCodigo($iEtapa);
        $oRegencia = RegenciaRepository::getRegenciaByTurmaEtapaDisciplina($oTurma, $oEtapa, $oDisciplina);
        if (!empty($oRegencia)) {
          $oParam->iCodigoVinculo = $oRegencia->getCodigo();
        }

        salvarDisciplinaTurma($oParam);
      }

      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_salvar"));
      break;

    case 'atualizarBase':

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);

      $sCampos  = " ed34_i_codigo, ed34_i_disciplina, ed232_c_descr, ed34_i_qtdperiodo, ed34_c_condicao, ed34_i_ordenacao, ";
      $sCampos .= " ed34_lancarhistorico, ed34_disiciplinaglobalizada, ed34_caracterreprobatorio, ed34_basecomum, ed31_c_contrfreq ";
      $sOrdem   = " ed34_basecomum, ed34_i_ordenacao, ed232_c_descr ";
      $sWhere   = "     ed34_i_base  = {$oParam->iBase} ";
      $sWhere  .= " and ed34_i_serie = {$oParam->iEtapa} ";

      $oDaoBaseMps = new cl_basemps();
      $sSqlBaseMps = $oDaoBaseMps->sql_query(null, $sCampos, $sOrdem, $sWhere);
      $rsBaseMps   = db_query($sSqlBaseMps);

      $oMsgErro = new stdClass();
      if ( !$rsBaseMps ) {

        $oMsgErro->sErroSql = pg_last_error();
        throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_buscar_disciplinas", $oMsgErro));
      }

      /**
       * Armazena os dados das disciplinas inclusas para base curricular
       */
      $iLinhas      = pg_num_rows($rsBaseMps);
      $aDisciplinasBase   = array();
      $aCodigoDisciplinas = array();
      for ($i = 0; $i < $iLinhas; $i++) {

        $aDisciplinasBase[]   = db_utils::fieldsMemory($rsBaseMps, $i);
        $aCodigoDisciplinas[] = db_utils::fieldsMemory($rsBaseMps, $i)->ed34_i_disciplina;$oRegencia;
      }

      /**
       * Busca e Remove as regencias de disciplinas que não contenham na base
       */
      $sWhere  = "     ed59_i_turma = {$oParam->iTurma} ";
      $sWhere .= " and ed59_i_serie = {$oParam->iEtapa} ";
      $sWhere .= " and ed59_i_disciplina not in (".implode(',', $aCodigoDisciplinas).") ";

      $oDaoRegencia = new cl_regencia();
      $sSqlRegencia = $oDaoRegencia->sql_query_file(null, "ed59_i_codigo", null, $sWhere);
      $rsRegencia   = $oDaoRegencia->sql_record($sSqlRegencia);

      db_inicio_transacao();
      $oMsgErro = new stdClass();
      if ($oDaoRegencia->numrows > 0) {

        $iLinhasRegencia = $oDaoRegencia->numrows;
        for ( $i = 0; $i < $iLinhasRegencia; $i++ ) {

          $oRegencia = RegenciaRepository::getRegenciaByCodigo(db_utils::fieldsMemory($rsRegencia, $i)->ed59_i_codigo);

          if ( $oRegencia->isEncerrada() || $oRegencia->parcialmenteEncerrada() ) {
            throw new BusinessException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_disciplina_encerrada") );
          }

          $oRegencia->excluir();
        }
      }

      /**
       * Buca os dados das regencias e atualiza conforme a base curricular
       */
      $sWhere  = "     ed59_i_turma = {$oParam->iTurma} ";
      $sWhere .= " and ed59_i_serie = {$oParam->iEtapa} ";
      $sWhere .= " and ed59_i_disciplina in (".implode(',', $aCodigoDisciplinas).") ";

      $oDaoRegencia = new cl_regencia();
      $sSqlRegencia = $oDaoRegencia->sql_query_file(null, "ed59_i_codigo, ed59_i_disciplina", null, $sWhere);
      $rsRegencia   = $oDaoRegencia->sql_record($sSqlRegencia);

      $oMsgErro = new stdClass();

      /**
       * Atualiza as disciplinas que encontrar na tabela de regencia.
       * As que não encontrar serão inclusas
       */
      foreach ($aDisciplinasBase as $oDadosDisciplinaBase) {

        $lEncontrouDisciplina = false;

        if ($oDaoRegencia->numrows > 0) {

          $iLinhasRegencia = $oDaoRegencia->numrows;

          // Atualiza regências
          for ( $i = 0; $i < $iLinhasRegencia; $i++ ) {

            $oDadosRegencia = db_utils::fieldsMemory($rsRegencia, $i);
            if ( $oDadosRegencia->ed59_i_disciplina != $oDadosDisciplinaBase->ed34_i_disciplina) {
              continue;
            }

            $oRegencia = RegenciaRepository::getRegenciaByCodigo($oDadosRegencia->ed59_i_codigo);

            if ( $oRegencia->isEncerrada() || $oRegencia->parcialmenteEncerrada() ) {
              throw new BusinessException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_disciplina_encerrada") );
            }

            $oRegencia->setHorasAula($oDadosDisciplinaBase->ed34_i_qtdperiodo);
            $oRegencia->setCondicao($oDadosDisciplinaBase->ed34_c_condicao);
            $oRegencia->setLancadaDocumentacao($oDadosDisciplinaBase->ed34_lancarhistorico == 't');
            $oRegencia->setOrdem($oDadosDisciplinaBase->ed34_i_ordenacao);
            $oRegencia->setCaracterReprobatorio($oDadosDisciplinaBase->ed34_caracterreprobatorio == 't');
            $oRegencia->setBaseComum($oDadosDisciplinaBase->ed34_basecomum == 't');

            /**
             * - Por default, assumimos que a base está configurada com controle de frequência individual, por isso
             * definimos o controle de frequência da disciplina como INDIVIDUAL;
             *
             * - Quando a base possuir controle globalizado, assumimos o default como TRATADA e somente alteramos
             * para GLOBALIZADA (FA) quando encontramos a disciplina informada como globalizada = true
             */
            $oRegencia->setControleFrequencia('I');
            if ( $oDadosDisciplinaBase->ed31_c_contrfreq == 'G') {

              $oRegencia->setControleFrequencia('A');
              if ($oDadosDisciplinaBase->ed34_disiciplinaglobalizada == 't') {
                $oRegencia->setControleFrequencia('FA');
              }
            }

            $oProcedimentoAvalicao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
            if( $oRegencia->getProcedimentoAvaliacao()->getCodigo() != $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa )->getCodigo() ) {
              $oProcedimentoAvalicao = $oRegencia->getProcedimentoAvaliacao();
            }

            $oRegencia->setProcedimentoAvaliacao( $oProcedimentoAvalicao );
            $oRegencia->setDataAtualizacao(new DBDate(date(DBDate::DATA_EN)));
            $oRegencia->salvar();

            $lEncontrouDisciplina = true;
          }
        }

        // Insere nova regencia
        if (!$lEncontrouDisciplina) {

          $iOrdem        = RegenciaRepository::getMaiorOrdemRegencia($oTurma, $oEtapa);
          $oNovaRegencia = new Regencia();

          $oNovaRegencia->setTurma($oTurma);
          $oNovaRegencia->setEtapa($oEtapa);
          $oNovaRegencia->setDisciplina( DisciplinaRepository::getDisciplinaByCodigo($oDadosDisciplinaBase->ed34_i_disciplina));
          $oNovaRegencia->setHorasAula($oDadosDisciplinaBase->ed34_i_qtdperiodo);
          $oNovaRegencia->setCondicao($oDadosDisciplinaBase->ed34_c_condicao);
          $oNovaRegencia->setLancadaDocumentacao($oDadosDisciplinaBase->ed34_lancarhistorico == 't');
          $oNovaRegencia->setCaracterReprobatorio($oDadosDisciplinaBase->ed34_caracterreprobatorio == 't');
          $oNovaRegencia->setBaseComum($oDadosDisciplinaBase->ed34_basecomum == 't');
          $oNovaRegencia->setOrdem($oDadosDisciplinaBase->ed34_i_ordenacao);
          $oNovaRegencia->setDataAtualizacao(new DBDate(date(DBDate::DATA_EN)));
          $oNovaRegencia->setProcedimentoAvaliacao( $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa ) );

          $oNovaRegencia->setControleFrequencia('I');
          if ( $oDadosDisciplinaBase->ed31_c_contrfreq == 'G') {

            $oNovaRegencia->setControleFrequencia('A');
            if ($oDadosDisciplinaBase->ed34_disiciplinaglobalizada == 't') {
              $oNovaRegencia->setControleFrequencia('FA');
            }
          }
          $oNovaRegencia->salvar();
        }
      }

      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplina_atualizadas_base"));
      break;

    case 'reordenarDisciplinasTurma':

      db_inicio_transacao();
      $oDaoRegencia = new cl_regencia();
      foreach ($oParam->aDisciplinasComumOrdenada as $oOrdem) {

        unset($GLOBALS["HTTP_POST_VARS"]["ed59_lancarhistorico"]) ;
        unset($GLOBALS["HTTP_POST_VARS"]["ed59_caracterreprobatorio"]);

        $oDaoRegencia->ed59_i_codigo             = $oOrdem->iCodigo;
        $oDaoRegencia->ed59_i_ordenacao          = $oOrdem->iOrdem;
        $oDaoRegencia->ed59_lancarhistorico      = "";
        $oDaoRegencia->ed59_caracterreprobatorio = "";
        $oDaoRegencia->alterar($oOrdem->iCodigo);

        if ($oDaoRegencia->erro_status == 0) {
          throw new Exception(_M(URLMSG_VINCULODISCIPLINAETAPA . "erro_alterar_ordem") );
        }
      }

      foreach ($oParam->aDisciplinasDiversificadaOrdenada as $oOrdem) {

        unset($GLOBALS["HTTP_POST_VARS"]["ed59_lancarhistorico"]) ;
        unset($GLOBALS["HTTP_POST_VARS"]["ed59_caracterreprobatorio"]);

        $oDaoRegencia->ed59_i_codigo             = $oOrdem->iCodigo;
        $oDaoRegencia->ed59_i_ordenacao          = $oOrdem->iOrdem;
        $oDaoRegencia->ed59_lancarhistorico      = "";
        $oDaoRegencia->ed59_caracterreprobatorio = "";
        $oDaoRegencia->alterar($oOrdem->iCodigo);

        if ($oDaoRegencia->erro_status == 0) {
          throw new Exception(_M(URLMSG_VINCULODISCIPLINAETAPA . "erro_alterar_ordem"));
        }
      }

      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplinas_reordenadas"));

      break;

    case 'reordenarDisciplinasBase':

      db_inicio_transacao();

      $oDaoBaseMps = new cl_basemps();
      foreach ($oParam->aDisciplinasComumOrdenada as $oOrdem) {

        unset($GLOBALS["HTTP_POST_VARS"]["ed34_caracterreprobatorio"]);
        unset($GLOBALS["HTTP_POST_VARS"]["ed34_lancarhistorico"]);

        $oDaoBaseMps->ed34_caracterreprobatorio = "";
        $oDaoBaseMps->ed34_lancarhistorico      = "";
        $oDaoBaseMps->ed34_i_codigo             = $oOrdem->iCodigo;
        $oDaoBaseMps->ed34_i_ordenacao          = $oOrdem->iOrdem;
        $oDaoBaseMps->ed34_basecomum            = 'true';
        $oDaoBaseMps->alterar($oOrdem->iCodigo);

        if ($oDaoBaseMps->erro_status == 0) {
          throw new Exception(_M(URLMSG_VINCULODISCIPLINAETAPA . "erro_alterar_ordem" ));
        }
      }

      foreach ($oParam->aDisciplinasDiversificadaOrdenada as $oOrdem) {

        unset($GLOBALS["HTTP_POST_VARS"]["ed34_caracterreprobatorio"]);
        unset($GLOBALS["HTTP_POST_VARS"]["ed34_lancarhistorico"]);
        $oDaoBaseMps->ed34_caracterreprobatorio = "";
        $oDaoBaseMps->ed34_lancarhistorico      = "";
        $oDaoBaseMps->ed34_i_codigo             = $oOrdem->iCodigo;
        $oDaoBaseMps->ed34_i_ordenacao          = $oOrdem->iOrdem;
        $oDaoBaseMps->ed34_basecomum            = 'false';
        $oDaoBaseMps->alterar($oOrdem->iCodigo);

        if ($oDaoBaseMps->erro_status == 0) {
          throw new Exception(_M(URLMSG_VINCULODISCIPLINAETAPA . "erro_alterar_ordem" ) );
        }
      }

      db_fim_transacao();
      $oRetorno->sMessage = urlencode(_M(URLMSG_VINCULODISCIPLINAETAPA . "disciplinas_reordenadas"));

      break;
  }
} catch (Exception $oErro) {
  db_fim_transacao(true);
  $oRetorno->sStatus  = 2;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);

/**
 * Salva/Altera a regência em uma turma conforme paramentros informados
 * @param stdClass $oParam
 *
 * @return bool
 */
function salvarDisciplinaTurma($oParam) {

  if (!db_utils::inTransaction()) {
    throw new BusinessException( _M(URLMSG_VINCULODISCIPLINAETAPA . "sem_transacao_ativa") );
  }

  $oTurma    = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
  $oEtapa    = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
  $iOrdem    = RegenciaRepository::getMaiorOrdemRegencia($oTurma, $oEtapa);

  if ($oTurma->getTipoDaTurma() == 6 && empty($oParam->iCodigoVinculo) && count($oTurma->getDisciplinasPorEtapa( $oEtapa) ) > 0 ) {
    throw new BusinessException("Turmas de progressão parcial só podem ter uma disciplina.");
  }

  $oRegencia = new Regencia();

  if ( empty($oParam->iCodigoVinculo) ) {

    $oRegencia->setTurma($oTurma);
    $oRegencia->setEtapa($oEtapa);
    $oRegencia->setOrdem($iOrdem + 1);
    $oRegencia->setProcedimentoAvaliacao( $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa ) );
  } else {
    $oRegencia = new Regencia($oParam->iCodigoVinculo);
  }

  $oRegencia->setDisciplina( DisciplinaRepository::getDisciplinaByCodigo($oParam->iDisicplina));
  $oRegencia->setHorasAula($oParam->iQtdPeriodo);
  $oRegencia->setCondicao($oParam->lObrigatoria ? 'OB' : 'OP');
  $oRegencia->setLancadaDocumentacao($oParam->lLancarDocumentacao);
  $oRegencia->setCaracterReprobatorio($oParam->lCaracterReprobatorio);
  $oRegencia->setBaseComum($oParam->lBaseComum);
  $oRegencia->setControleFrequencia($oParam->sTipoControleFrequencia);
  $oRegencia->setDataAtualizacao(new DBDate(date(DBDate::DATA_EN)));

  $oRegencia->salvar();
  return true;
}

/**
 * Salva/Altera a disciplina em uma base conforme paramentros informados
 * @param $oParam
 * @return bool
 * @throws BusinessException
 * @throws DBException
 */
function salvarDisciplinaBase($oParam) {

  if (!db_utils::inTransaction()) {
    throw new BusinessException( _M(URLMSG_VINCULODISCIPLINAETAPA . "sem_transacao_ativa") );
  }

  $oDaoBaseMps = new cl_basemps();

  $oDaoBaseMps->ed34_i_chtotal              = 0;
  $oDaoBaseMps->ed34_i_ordenacao            = 0;
  $oDaoBaseMps->ed34_i_base                 = $oParam->iBase;
  $oDaoBaseMps->ed34_i_serie                = $oParam->iEtapa;
  $oDaoBaseMps->ed34_i_disciplina           = $oParam->iDisicplina;
  $oDaoBaseMps->ed34_i_qtdperiodo           = $oParam->iQtdPeriodo;
  $oDaoBaseMps->ed34_c_condicao             = $oParam->lObrigatoria          ? 'OB'   : 'OP';
  $oDaoBaseMps->ed34_lancarhistorico        = $oParam->lLancarDocumentacao   ? 'true' : 'false';
  $oDaoBaseMps->ed34_disiciplinaglobalizada = $oParam->lGlobalizada          ? 'true' : 'false';
  $oDaoBaseMps->ed34_caracterreprobatorio   = $oParam->lCaracterReprobatorio ? 'true' : 'false';
  $oDaoBaseMps->ed34_basecomum              = $oParam->lBaseComum            ? 'true' : 'false';

  db_inicio_transacao();
  if (empty($oParam->iCodigoVinculo)) {
    $oDaoBaseMps->incluir(null);
  } else {

    $oDaoBaseMps->ed34_i_codigo = $oParam->iCodigoVinculo;
    $oDaoBaseMps->alterar($oParam->iCodigoVinculo);
  }

  $oMsgErro = new stdClass();
  if ($oDaoBaseMps->erro_status == 0) {

    $oMsgErro->sErroSql = $oDaoBaseMps->erro_sql;
    throw new DBException( _M(URLMSG_VINCULODISCIPLINAETAPA . "erro_salvar_disciplina", $oMsgErro) );
  }

  return true;
}
