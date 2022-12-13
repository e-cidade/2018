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
require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/censo/DadosCenso.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->dados    = new stdClass();
$oRetorno->status   = 1;

$iEscola = db_getsession("DB_coddepto");
if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
  $iEscola = $oParam->iEscola;
}
$iModuloEscola = 1100747;
try {


  switch ($oParam->exec) {

    case 'pesquisaEscola':

      $aFiltros = array();

      if (db_getsession("DB_modulo") == $iModuloEscola) {
        $aFiltros[] = " ed18_i_codigo in ($iEscola) ";
      }

      $sWhere = "";
      if (count($aFiltros) > 0) {
        $sWhere = implode(" and ", $aFiltros);
      }

      $oDaoEscola     = new cl_escola();
      $sCamposEscola  = "ed18_i_codigo as codigo_escola, ed18_c_nome as nome_escola";
      $sSqlEscola     = $oDaoEscola->sql_query_file("", $sCamposEscola, "ed18_c_nome", $sWhere);
      $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);

      $oRetorno->dados = db_utils::getCollectionByRecord($rsResultEscola, false, false, true);

      break;

    /**
     * Pesquisa todos calendários que possuem turma vinculádas
     */
    case "pesquisaCalendario":

      $oRetorno->dados    = array();
      $aFiltros           = array();
      $sCalendarioPassivo = "ed52_c_passivo = 'N'";
      if (isset($oParam->apenas_ativos) && !$oParam->apenas_ativos) {
        $sCalendarioPassivo = "ed52_c_passivo in('N', 'S')";
      }
      $aFiltros[] = $sCalendarioPassivo;

      $sEscola    = "ed38_i_escola in ({$iEscola})";

      if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
        $sEscola    = "ed38_i_escola in ({$oParam->iEscola})";
      }

      if (isset($oParam->iEscola) && $oParam->iEscola == 0) {
        $sEscola = "";
      }


      if (!empty($sEscola)) {
        $aFiltros[] = $sEscola;
      }

      if (isset($oParam->turmas_encerradas) && $oParam->turmas_encerradas === true) {
        $aFiltros[] = " EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo) ";
      }

      if( isset( $oParam->lSomenteTurmasComProgressaoEncerrada ) && $oParam->lSomenteTurmasComProgressaoEncerrada ) {

        $sWhereProgressao  = " EXISTS( select 1                                                                                                          \n";
        $sWhereProgressao .= "           from progressaoparcialaluno                                                                                     \n";
        $sWhereProgressao .= "                inner join progressaoparcialalunomatricula     on ed150_progressaoparcialaluno          = ed114_sequencial \n";
        $sWhereProgressao .= "                inner join progressaoparcialalunoturmaregencia on ed115_progressaoparcialalunomatricula = ed150_sequencial \n";
        $sWhereProgressao .= "                inner join regencia                            on ed59_i_codigo                         = ed115_regencia   \n";
        $sWhereProgressao .= "                inner join turma                               on ed57_i_codigo                         = ed59_i_turma     \n";
        $sWhereProgressao .= "          where ed52_i_codigo = ed57_i_calendario                                                                          \n";
        $sWhereProgressao .= "            and ed150_encerrado is true )";

        $aFiltros[] = $sWhereProgressao;
      }

      $sCampos = " ed52_i_codigo, ed52_c_descr, ed52_i_ano";
      $sWhere  = implode(" and ", $aFiltros);

      $oDaoCalendario = db_utils::getdao('calendario');

      $sSqlCalendario  = " select distinct                                                                 \n";
      $sSqlCalendario .= "        ed52_i_codigo, ed52_c_descr, ed52_i_ano                                  \n";
      $sSqlCalendario .= "   from turma                                                                    \n";
      $sSqlCalendario .= "        inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo          \n";
      $sSqlCalendario .= "        inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat     \n";
      $sSqlCalendario .= "        inner join serie on ed11_i_codigo = ed223_i_serie                        \n";
      $sSqlCalendario .= "        inner join calendarioescola on ed38_i_calendario = ed57_i_calendario     \n";
      $sSqlCalendario .= "        inner join calendario       on ed52_i_codigo     = ed57_i_calendario     \n";
      $sSqlCalendario .= "  where {$sWhere}                                                                \n";
      $sSqlCalendario .= "  order by ed52_i_ano desc                                                       \n";

      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);

      if ($oDaoCalendario->numrows > 0) {

        $oRetorno->iEscola  = $iEscola;
        $oRetorno->dados    = db_utils::getCollectionByRecord($rsCalendario, false, false, true);

      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar um Calendário para a escola selecionada!");
      }

      break;

    /**
     * Pesquisa todos calendários vinculádos a escola
     */
    case "pesquisaCalendarioEscola":

      $oRetorno->dados    = array();
      $aFiltros           = array();
      $sCalendarioPassivo = "ed52_c_passivo = 'N'";
      if (isset($oParam->apenas_ativos) && !$oParam->apenas_ativos) {
        $sCalendarioPassivo = "ed52_c_passivo in('N', 'S')";
      }
      $aFiltros[] = $sCalendarioPassivo;

      $sEscola    = "ed38_i_escola in ({$iEscola})";

      if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
        $sEscola    = "ed38_i_escola in ({$oParam->iEscola})";
      }

      if (isset($oParam->iEscola) && $oParam->iEscola == 0) {
        $sEscola = "";
      }

      if (!empty($sEscola)) {
        $aFiltros[] = $sEscola;
      }

      $sCampos    = " ed52_i_codigo, ed52_c_descr, ed52_i_ano";
      $sWhere     = implode(" and ", $aFiltros);

      $oDaoCalendario = new cl_calendario();

      $sSqlCalendario  = " select distinct                                                                 \n";
      $sSqlCalendario .= "        ed52_i_codigo, ed52_c_descr, ed52_i_ano                                  \n";
      $sSqlCalendario .= "   from calendario                                                               \n";
      $sSqlCalendario .= "        inner join calendarioescola on calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo \n";
      $sSqlCalendario .= "  where {$sWhere}                                                                \n";
      $sSqlCalendario .= "  order by ed52_i_ano desc                                                       \n";

      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);

      if ($oDaoCalendario->numrows > 0) {

        $oRetorno->iEscola  = $iEscola;
        $oRetorno->dados    = db_utils::getCollectionByRecord($rsCalendario, false, false, true);

      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar um Calendário para a escola selecionada!");
      }

      break;

    case 'pesquisaEtapa':  // Etapa = Série

      $aFiltros   = array();
      $aFiltros[] = " ed57_i_escola in ($iEscola) ";

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
      }

      if (isset($oParam->iCurso) && !empty($oParam->iCurso)) {
        $aFiltros[] = " ed11_i_ensino = {$oParam->iCurso} ";
      }

      if ( isset($oParam->lFiltarMatriculaConcluida) ) {
        $aFiltros[] = " ed60_c_concluida = 'S' ";
      }

      $sCamposEtapa = " DISTINCT ed11_i_codigo, ed11_c_descr, ed11_i_ensino, ed11_i_sequencia ";
      $sOrder       = "ed11_i_ensino, ed11_i_sequencia";
      $sWhere       = implode(" and ", $aFiltros);
      $oDaoEtapa    = new cl_turma();
      $sSqlEtapa    = $oDaoEtapa->sql_query_relatorio(null, $sCamposEtapa, $sOrder, $sWhere);
      $rsEtapa      = $oDaoEtapa->sql_record($sSqlEtapa);

      if ($oDaoEtapa->numrows > 0) {
        $oRetorno->dados = db_utils::getCollectionByRecord($rsEtapa, false, false, true);
      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar as etapas.");
      }

      break;

    case 'pesquisaTurma':

      $sEscola  = "";
      $aFiltros = array();
      /**
       * Sempre que estiver no módulo escola, deve buscar os dados da escola logada
       */
      if (db_getsession("DB_modulo") == $iModuloEscola) {
        $sEscola = " ed57_i_escola in ($iEscola) ";
      } else if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
        $sEscola = " ed57_i_escola in ($iEscola) ";
      }

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
      }

      if (isset($oParam->iEtapa) && !empty($oParam->iEtapa)) {
        $aFiltros[] = " ed11_i_codigo in ({$oParam->iEtapa} )";
      }

      if (isset($oParam->lEncerrada) && trim($oParam->lEncerrada) == "true") {
        $aFiltros[] = " EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo)";
      }

      if (!empty($sEscola)) {
        $aFiltros[] = $sEscola;
      }

      $aFiltros[] = " ed221_c_origem = 'S'";

      $sWhere = implode(" and ", $aFiltros);
      $sCampo = "DISTINCT ed57_i_codigo, trim(ed57_c_descr) as ed57_c_descr";
      $sOrder = "ed57_c_descr";

      $oDaoTurma  = new cl_turma();
      $sSqlTurma  = $oDaoTurma->sql_query_relatorio(null, $sCampo, $sOrder, $sWhere);

      $rsTurma    = $oDaoTurma->sql_record($sSqlTurma);

      if ($oDaoTurma->numrows > 0) {

        $oRetorno->dados = db_utils::getCollectionByRecord($rsTurma, false, false, true);
      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar as turmas solicitadas!");
      }
      break;

    case 'buscaPeriodosAvaliacaoEscola':

      $aFiltros = array();

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed53_i_calendario in ({$oParam->iCalendario}) ";
      }
      if (isset($oParam->lFiltraEscolaLogada) && $oParam->lFiltraEscolaLogada) {
        $aFiltros[] = " ed38_i_escola in ({$iEscola}) ";
      }
      $sWhere = implode(" and ", $aFiltros);

      $oDaoPeriodoCalendario = new cl_periodocalendario();
      $sCampos               = " distinct ed09_i_codigo as codigo_periodo, ed09_c_descr as descricao_periodo";
      $sSqlPeriodos          = $oDaoPeriodoCalendario->sql_query_escola(null, $sCampos, "ed09_i_codigo", $sWhere);
      $rsPeriodos            = $oDaoPeriodoCalendario->sql_record($sSqlPeriodos);
      $oRetorno->dados       = db_utils::getCollectionByRecord($rsPeriodos, false, false, true);

      break;

    case 'buscaAlunosPorTurma':

      try {

        if (isset($oParam->iTurma) && empty($oParam->iTurma)) {
          throw new ParameterException("É preciso selecionar uma turma.");
        }

        $oTurma              = new Turma($oParam->iTurma);
        $aAlunosMatriculados = $oTurma->getAlunosMatriculados();
        $aAlunosTurma        = array();

        foreach ($aAlunosMatriculados as $oAlunosMatriculados) {

          if (isset($oParam->sSituacao) && $oParam->sSituacao != "") {
            if (trim($oAlunosMatriculados->getSituacao()) <> $oParam->sSituacao) {
              continue;
            }
          }
          $oAluno          = new stdClass();
          $oAluno->iCodigo = $oAlunosMatriculados->getAluno()->getCodigoAluno();
          $oAluno->sNome   = urlencode($oAlunosMatriculados->getAluno()->getNome());
          $aAlunosTurma[]  = $oAluno;
          unset($oAluno);
        }

        $oRetorno->dados = $aAlunosTurma;

      } catch (BusinessException $oErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($oErro->getMessage());
      } catch (ParameterException $oErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($oErro->getMessage());
      }

      break;
    case 'getTurmasProgressaoParcial' :

      $aFiltros   = array();
      $aFiltros[] = " ed57_i_calendario = {$oParam->iCalendario}";
      $aFiltros[] = " ed57_i_escola     = {$iEscola}";

      $sWhere     = implode(" and ", $aFiltros);
      $sCampos    = " distinct ed57_i_codigo, ed57_c_descr ";
      $oDaoTurma  = new cl_turma();
      $sSqlTurma  = $oDaoTurma->sql_query_turma_progressao_parcial(null, $sCampos, "ed57_c_descr", $sWhere);
      $rsTurma    = $oDaoTurma->sql_record($sSqlTurma);
      $iRegistros = $oDaoTurma->numrows;

      $aTurmas    = array();

      if ($iRegistros > 0) {

        for ($i = 0; $i < $iRegistros; $i++) {

          $aTurmas[] = db_utils::fieldsMemory($rsTurma, $i, false, false, true);
        }
      }

      $oRetorno->aTurmas = $aTurmas;
      break;

    case 'getDisciplinaTurma':

      $oTurma       = TurmaRepository::getTurmaByCodigo($oParam->iCodigoTurma);
      $aDisciplinas = array();

      foreach ($oTurma->getDisciplinas() as $oRegencia) {

        $oDisciplina                       = new stdClass();
        $oDisciplina->iRegencia            = $oRegencia->getCodigo();
        $oDisciplina->iCodigoDisciplina    = $oRegencia->getDisciplina()->getCodigoDisciplina();
        $oDisciplina->sDescricaoDisciplina = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());

        $aDisciplinas[] = $oDisciplina;
      }

      $oRetorno->aDisciplinas = $aDisciplinas;
      break;

    case 'getPeriodosDeAvaliacaoTurma' :

      $oTurma         = new Turma($oParam->iTurma);
      $aPeriodos      = array();
      $aEtapas        = $oTurma->getEtapas();
      $aPeriodosTurma = array();
      foreach ($aEtapas as $oEtapaTurma) {

        $oProcedimento          = $oEtapaTurma->getProcedimentoAvaliacao();
        $aPeriodosProcedimentos = $oProcedimento->getElementos();
        foreach ($aPeriodosProcedimentos as $oPeriodo) {

          if (!$oPeriodo->isResultado()) {

            $iCodigoPeriodo =  $oPeriodo->getPeriodoAvaliacao()->getCodigo();
            if (!isset($aPeriodosTurma[$iCodigoPeriodo])) {
              $aPeriodosTurma[$iCodigoPeriodo] = $oPeriodo->getPeriodoAvaliacao();
            }
          }
        }
      }

      /**
       * Caso tenha sido passado o parâmetro lCriterioAvaliacao, busca os períodos da escola que estão vinculados a um
       * critério de avaliação, e compara com os períodos da turma, retornando somente os que tem vínculo
       */
      $lValidaPeriodoAvaliacao = false;
      $aPeriodosCriterio       = array();
      if( isset( $oParam->lCriterioAvaliacao ) && $oParam->lCriterioAvaliacao ) {

        $lValidaPeriodoAvaliacao = true;
        $aPeriodoAvaliacao       = PeriodoAvaliacaoRepository::getPeriodosCriteriosAvaliacaoPorEscola( new Escola( $iEscola ) );

        foreach( $aPeriodoAvaliacao as $oPeriodoAvaliacao ) {
          $aPeriodosCriterio[] = $oPeriodoAvaliacao->getCodigo();
        }
      }

      foreach ($aPeriodosTurma as $oPeriodo) {

        /**
         * Verifica se é necessário validar se o período de avaliação possui critério vinculado
         */
        if( $lValidaPeriodoAvaliacao && !in_array( $oPeriodo->getCodigo(), $aPeriodosCriterio ) ) {
          continue;
        }

        $oStdPeriodo             = new stdClass();
        $oStdPeriodo->iCodigo    = $oPeriodo->getCodigo();
        $oStdPeriodo->sDescricao = urlencode($oPeriodo->getDescricao());
        $aPeriodos[]             = $oStdPeriodo;
      }

      $oRetorno->aPeriodos = $aPeriodos;

      break;

    case 'getTurmasEspecialEComplementar':
      /**
       * Busca turmas de atividade complementar e AEE
       */
      $aFiltros   = array();
      $aFiltros[] = " ed268_i_calendario = {$oParam->iCalendario}";
      if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
        $aFiltros[] = " ed268_i_escola     = {$oParam->iEscola}";
      }

      $sWhere     = implode(" and ", $aFiltros);
      $sCampos    = " ed268_i_codigo, ed268_c_descr ";
      $oDaoTurma  = new cl_turmaac();
      $sSqlTurma  = $oDaoTurma->sql_query_file(null, $sCampos, "ed268_c_descr", $sWhere);
      $rsTurma    = $oDaoTurma->sql_record($sSqlTurma);
      $iRegistros = $oDaoTurma->numrows;

      $aTurmas    = array();

      if ($iRegistros > 0) {

        for ($i = 0; $i < $iRegistros; $i++) {

          $aTurmas[] = db_utils::fieldsMemory($rsTurma, $i, false, false, true);
        }
      }

      $oRetorno->aTurmas = $aTurmas;
      break;

    case 'buscaAnosDeTurmasDeProgressaoParcial' :

      $aFiltros     = array();

      if ($oParam->iEscola != 0) {
        $aFiltros[]   = " ed114_escola = {$oParam->iEscola}";
      }
      $sWhere       = implode(" and ", $aFiltros);
      $sCampos      = " distinct ed114_ano ";
      $oDaoTurma    = new cl_progressaoparcialaluno();
      $sSqlTurma    = $oDaoTurma->sql_query_aluno_escola( null, $sCampos, null, $sWhere );
      $rsCalendario = $oDaoTurma->sql_record( $sSqlTurma );
      $iRegistros   = $oDaoTurma->numrows;
      $aAnos        = array();

      try {

        if ($iRegistros == 0) {
          throw new BusinessException("Nenhum ano disponível para o calendário");
        }

        for ($i = 0; $i < $iRegistros; $i++) {

          $aAnos[] = db_utils::fieldsMemory($rsCalendario, $i);
        }

        $oRetorno->aAnos = $aAnos;

      } catch (BusinessException $eErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }

      break;
    case 'pesquisaEscolaComProgressaoParcial':

        $aFiltros = array();

        if (isset($oParam->filtraModulo) && !empty($oParam->filtraModulo)) {
          $aFiltros[] = " ed18_i_codigo in ($iEscola) ";
        }

        $oRetorno->iEscolaAtual      = "";
        $oRetorno->lPossuiProgressao = false;
        if (db_getsession("DB_modulo") == $iModuloEscola) {

          $aFiltros[]             = " ed18_i_codigo in ($iEscola) ";
          $oRetorno->iEscolaAtual = $iEscola;
        }
        if (isset($oParam->lEscolasComAlunosEmProgressao) && $oParam->lEscolasComAlunosEmProgressao == 1) {

          $sSqlAlunoProgressao  = " exists(select 1 ";
          $sSqlAlunoProgressao .= "          from progressaoparcialaluno ";
          $sSqlAlunoProgressao .= "         where ed114_escola = ed18_i_codigo)";
          $aFiltros[]           = $sSqlAlunoProgressao;
        }
        $aFiltros[] = "ed112_habilitado is true";
        $sWhere = implode(" and ", $aFiltros);

        $oDaoEscola     = new cl_parametroprogressaoparcial();
        $sCamposEscola  = "ed18_i_codigo as codigo_escola, ed18_c_nome as nome_escola";
        $sSqlEscola     = $oDaoEscola->sql_query("", $sCamposEscola, "ed18_c_nome", $sWhere);
        $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);

        $oRetorno->dados  = db_utils::getCollectionByRecord($rsResultEscola, false, false, true);
        if ($oDaoEscola->numrows > 0) {
          $oRetorno->lPossuiProgressao = true;
        }

        break;

    /**
     * Retorna as turmas e etapas vinculadas
     */
    case 'pesquisaTurmaEtapa':

      $sEscola  = "";
      $aFiltros = array();
      $sQuery   = "sql_query_relatorio";

      /**
       * Sempre que estiver no módulo escola, deve buscar os dados da escola logada
      */
      if (db_getsession("DB_modulo") == $iModuloEscola) {
        $sEscola = " ed57_i_escola in ($iEscola) ";
      } else if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
        $sEscola = " ed57_i_escola in ($iEscola) ";
      }

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
      }

      if (isset($oParam->iEtapa) && !empty($oParam->iEtapa)) {
        $aFiltros[] = " ed11_i_codigo in ({$oParam->iEtapa} )";
      }

      $lTurmaEncerrada  = false;
      if (isset($oParam->lEncerrada) && trim($oParam->lEncerrada) == "true") {

        $lTurmaEncerrada = true;
        $aFiltros[] = " EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo)";
      }

      if ( isset( $oParam->lSomenteComCriterioAvaliacao ) && $oParam->lSomenteComCriterioAvaliacao ) {

        $sQuery     = "sql_query_turma";
        $aFiltros[] = " EXISTS( select 1 from criterioavaliacaoturma where ed341_turma = ed57_i_codigo )";
      }

      if (!empty($sEscola)) {
        $aFiltros[] = $sEscola;
      }

      if (    !isset( $oParam->lSomenteComCriterioAvaliacao )
           || isset( $oParam->lSomenteComCriterioAvaliacao ) && !$oParam->lSomenteComCriterioAvaliacao ) {
        $aFiltros[] = " ed221_c_origem = 'S'";
      }

      $sWhere = implode(" and ", $aFiltros);
      $sCampo = "DISTINCT ed57_i_codigo, trim(ed57_c_descr) as ed57_c_descr, ed11_i_codigo, ed11_c_descr";
      $sOrder = "ed57_c_descr, ed11_c_descr";

      $oDaoTurma    = new cl_turma();
      $sSqlTurma    = $oDaoTurma->$sQuery(null, $sCampo, $sOrder, $sWhere);
      $rsTurma      = $oDaoTurma->sql_record($sSqlTurma);
      $iTotalLinhas = $oDaoTurma->numrows;

      if ($oDaoTurma->numrows > 0) {

        $oRetorno->dados = array();
        for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

          $oRetornoTurma = new stdClass();
          $oDadosTurma   = db_utils::fieldsMemory($rsTurma, $iContador);
          $oTurma        = TurmaRepository::getTurmaByCodigo($oDadosTurma->ed57_i_codigo);
          $oEtapaTurma   = EtapaRepository::getEtapaByCodigo($oDadosTurma->ed11_i_codigo);

          if (isset($oParam->lComAlunosMatriculados) && $oParam->lComAlunosMatriculados) {

            if ( count($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapaTurma)) == 0) {
              continue;
            }
          }
          $oRetornoTurma                 = new stdClass();
          $oRetornoTurma->ed57_i_codigo  = $oTurma->getCodigo();
          $oRetornoTurma->ed57_c_descr   = urlencode($oTurma->getDescricao()." - ".$oEtapaTurma->getNome());
          $oRetornoTurma->codigo_etapa   = $oEtapaTurma->getCodigo();

          if ($lTurmaEncerrada && $oTurma->encerradaNaEtapa($oEtapaTurma)) {
            continue;
          }
          $oRetorno->dados[]            = $oRetornoTurma;
          unset($oRetornoTurma);
        }
      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar as turmas solicitadas!");
      }
      break;

    case 'pesquisaDiretores':

      $oEscola         = new Escola($iEscola);
      $oRetorno->dados = array();
      foreach ($oEscola->getDiretor() as $oDiretor) {

        $oDadosDiretor            = new stdClass();
        $oDadosDiretor->iCodigo   = $oDiretor->iCodigo  ;
        $oDadosDiretor->sNome     = urlencode($oDiretor->sNome);
        $oDadosDiretor->sAtoLegal = urlencode($oDiretor->sAtoLegal);
        $oDadosDiretor->iNumero   = $oDiretor->iNumero  ;

        $oRetorno->dados[] = $oDadosDiretor;
      }
      break;

    case 'pesquisaTurmaTipoGradeHorario':

    	$sEscola    = " ed57_i_escola in ($iEscola) ";
    	if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
    		$sEscola    = "ed57_i_escola in ({$oParam->iEscola})";
    	}

    	$aFiltros   = array();
    	$aFiltros[] = $sEscola;
    	$aFiltros[] = " ed58_tipovinculo = {$oParam->tipoVinculo}";

    	if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
    		$aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
    	}

    	if (isset($oParam->lEncerrada) && trim($oParam->lEncerrada) == "true") {

    		$aFiltros[] = " EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo)";
    	}
    	$aFiltros[] = " ed221_c_origem = 'S' ";

    	$sWhere = implode(" and ", $aFiltros);
    	$sCampo = "DISTINCT ed220_i_codigo, trim(ed57_c_descr) as ed57_c_descr, trim(ed11_c_descr) as ed11_c_descr";
    	$sOrder = "ed57_c_descr";


    	$sSqlTurma = " SELECT {$sCampo} ";
    	$sSqlTurma .= "  FROM turma                                                               ";
    	$sSqlTurma .= " inner join matricula           on ed60_i_turma    = ed57_i_codigo         ";
    	$sSqlTurma .= " inner join turmaserieregimemat on ed220_i_turma   = ed57_i_codigo         ";
    	$sSqlTurma .= " inner join serieregimemat      on ed223_i_codigo  = ed220_i_serieregimemat";
    	$sSqlTurma .= " inner join serie               on ed11_i_codigo   = ed223_i_serie          ";
    	$sSqlTurma .= " inner join regencia            on ed59_i_turma    = ed57_i_codigo          ";
    	$sSqlTurma .= " inner join regenciahorario     on ed58_i_regencia = ed59_i_codigo        ";
    	$sSqlTurma .= " inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo      ";
    	$sSqlTurma .= "                               and ed221_i_serie = ed223_i_serie          ";
    	$sSqlTurma .= " WHERE {$sWhere}                                                          ";
    	$sSqlTurma .= " ORDER BY ed57_c_descr                                                    ";

    	$oDaoTurma  = new cl_regenciahorario();
    	$rsTurma    = $oDaoTurma->sql_record($sSqlTurma);

    	if ($oDaoTurma->numrows > 0) {

    		$oRetorno->dados = db_utils::getCollectionByRecord($rsTurma, false, false, true);
    	} else {

    		$oRetorno->status  = 2;
    		$oRetorno->message = urlencode("Não foi possível localizar as turmas solicitadas!");
    	}
    	break;

    case 'buscaProfessoresTurma':

    	$sEscola    = " ed57_i_escola in ($iEscola) ";

    	$aFiltros   = array();
    	$aFiltros[] = $sEscola;
    	$aFiltros[] = " ed220_i_codigo = {$oParam->iTurmaSerieRegimeMat}";

    	$sWhere   = implode(" and ", $aFiltros);
    	$sWhere  .= " AND ed58_ativo is true";
    	$sCampos  = " DISTINCT ed20_i_codigo, z01_nome, z01_numcgm ";
    	$sOrder   = " z01_nome ";

    	$oDaoTurma       = new cl_regenciahorario();
    	$sSqlProfessores = $oDaoTurma->sql_query_rechumano_regimemat(null, $sCampos, $sOrder, $sWhere);
    	$rsProfessores   = $oDaoTurma->sql_record($sSqlProfessores);

    	if ($oDaoTurma->numrows > 0) {

    		$oRetorno->dados = db_utils::getCollectionByRecord($rsProfessores, false, false, true);
    	} else {

    		$oRetorno->status  = 2;
    		$oRetorno->message = urlencode("Não foi possível localizar as turmas solicitadas!");
    	}

    	break;

    case 'buscaAtividadesServidor':

      if (isset($oParam->iNumCgm) && !empty($oParam->iNumCgm)) {
        $sCampos = " distinct ed01_i_codigo, ed01_c_descr";
        $sWhere  = " cgmrh.z01_numcgm = {$oParam->iNumCgm} or cgmcgm.z01_numcgm = {$oParam->iNumCgm}";

        $oDaoRecAtividade = new cl_rechumanoativ();
        $sSqlAtividades   = $oDaoRecAtividade->sql_query(null, $sCampos, "ed01_c_descr", $sWhere);
        $rsAtividades     = $oDaoRecAtividade->sql_record($sSqlAtividades);
        $iLinhas          = $oDaoRecAtividade->numrows;

        if ($iLinhas > 0) {

          for ($i = 0; $i < $iLinhas; $i++) {

            $oDados                  = db_utils::fieldsMemory($rsAtividades, $i);
            $oAtividade              = new stdClass();
            $oAtividade->iCodigo     = $oDados->ed01_i_codigo;
            $oAtividade->sDescricao  = urlencode($oDados->ed01_c_descr);
            $oRetorno->aAtividades[] = $oAtividade;
          }
        }

      }

      break;

    /**
     * Retorna os cursos de uma escola. Caso iEscola passe 0, retornamos todos os cursos de todas as escolas
     * @param integer $oParam->iEscola
     * @return array $oRetorno->aCursos
     */
    case 'pesquisaCursos':

      if (isset($oParam->iEscola)) {
        $oRetorno->aCursos = buscaCursos($oParam->iEscola);
      }
      break;

    /**
     * Retorna um array de disciplinas. Caso tenha sido informado um codigo de escola e/ou curso, filtramos as disciplinas
     * de acordo com estes codigos
     * @param integer $oParam->iEscola
     * @param integer $oParam->iCurso
     * @return array $oRetorno->aDisciplinas
     */
    case 'pesquisaDisciplinas':


      $oRetorno->aDisciplinas = array();
      $aWhereDisciplina       = array();

      if (isset($oParam->iEscola) && (!empty($oParam->iEscola) || $oParam->iEscola != 0)) {
        $aWhereDisciplina[] = "ed71_i_escola = {$oParam->iEscola}";
      }

      if (isset($oParam->iCurso) && (!empty($oParam->iCurso) || $oParam->iCurso != 0)) {
        $aWhereDisciplina[] = "ed29_i_codigo = {$oParam->iCurso}";
      }

      if (isset($oParam->iEnsino) && (!empty($oParam->iEnsino) || $oParam->iEnsino != 0)) {
        $aWhereDisciplina[] = " ed10_i_codigo = {$oParam->iEnsino} ";
      }

      $sWhereDisciplina  = implode(" and ", $aWhereDisciplina);
      $sCamposDisciplina = "distinct ed232_i_codigo, trim(ed232_c_descr) as ed232_c_descr";
      $oDaoDisciplina    = new cl_caddisciplina();
      $sSqlDisciplina    = $oDaoDisciplina->sql_query_disciplinas_na_escola(null,
                                                                            $sCamposDisciplina,
                                                                            "ed232_c_descr",
                                                                            $sWhereDisciplina
                                                                           );
      $rsDisciplina      = $oDaoDisciplina->sql_record($sSqlDisciplina);
      $iTotalDisciplina  = $oDaoDisciplina->numrows;

      if ($iTotalDisciplina > 0) {

        for ($iContador = 0; $iContador < $iTotalDisciplina; $iContador++) {

          $oDadosSqlDisciplina          = db_utils::fieldsMemory($rsDisciplina, $iContador);
          $oDadosDisciplina             = new stdClass();
          $oDadosDisciplina->iCodigo    = $oDadosSqlDisciplina->ed232_i_codigo;
          $oDadosDisciplina->sDescricao = urlencode($oDadosSqlDisciplina->ed232_c_descr);
          $oRetorno->aDisciplinas[]     = $oDadosDisciplina;
        }
      }
      break;
    case 'pesquisaEnsino':

      $aWhere = array();
      if (isset($oParam->iEscola) && ($oParam->iEscola != 0 || !empty($oParam->iEscola)) ) {
        $aWhere[] = "ed71_i_escola = {$oParam->iEscola}";
      }

      $sWhere = implode(" and ", $aWhere);
      require_once modification("classes/db_cursoedu_classe.php");
      $oDaoCursoEdu = new cl_curso();

      $sCampos    = " distinct ed10_i_codigo, trim(ed10_c_descr) as ed10_c_descr ";
      $sSqlEnsino = $oDaoCursoEdu->sql_query_cursoescola(null, $sCampos, "ed10_c_descr", $sWhere);
      $rsEnsino   = $oDaoCursoEdu->sql_record($sSqlEnsino);
      $iLinhas    = $oDaoCursoEdu->numrows;

      $oRetorno->aEnsino = array();
      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $oDados              = db_utils::fieldsMemory($rsEnsino, $i);
          $oEnsino             = new stdClass();
          $oEnsino->iCodigo    = $oDados->ed10_i_codigo;
          $oEnsino->sDescricao = urlencode($oDados->ed10_c_descr);
          $oRetorno->aEnsino[] = $oEnsino;
        }
      }

      break;

    case "pesquisaAnoLetivoEscola":

      $aFiltros   = array();
      $aFiltros[] = "ed52_c_passivo = 'N'";

      if ( isset($oParam->iEscola) && (!empty($oParam->iEscola)) ) {
        $aFiltros[] = "ed38_i_escola in ({$oParam->iEscola})";
      }

      $sCampos    = " distinct ed52_i_ano";
      $sWhere     = implode(" and ", $aFiltros);

      $oDaoCalendario = new cl_calendarioescola();
      $sSqlCalendario = $oDaoCalendario->sql_query(null, $sCampos, "ed52_i_ano desc", $sWhere);
      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);

      if ($oDaoCalendario->numrows > 0) {
        $oRetorno->aAno  = db_utils::getCollectionByRecord($rsCalendario, false, false, true);

      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Não foi possível localizar um Calendário para a escola selecionada!");
      }
      break;

    case 'pesquisaEtapaAno':

      $aFiltros   = array();

      if ( isset($oParam->iEscola) && (!empty($oParam->iEscola)) ) {
        $aFiltros[] = "ed57_i_escola in ({$oParam->iEscola})";
      }

      if ( isset($oParam->iAno) && !empty($oParam->iAno) ) {

        $aFiltros[] = "ed52_i_ano = {$oParam->iAno}";
        $sWhere     = implode(" and ", $aFiltros);

        $sCampos   = "DISTINCT ed11_i_codigo, ed11_c_descr, ed11_i_ensino, ed11_i_sequencia";
        $sOrdem    = "ed11_i_ensino,ed11_i_sequencia";
        $oDaoTurma = new cl_turma;
        $sSqlEtapa = $oDaoTurma->sql_query_relatorio(null, $sCampos, $sOrdem, $sWhere);
        $rsEtapa   = $oDaoTurma->sql_record($sSqlEtapa);

        if ($oDaoTurma->numrows > 0) {
          $oRetorno->aEtapaAno = db_utils::getCollectionByRecord($rsEtapa, false, false, true);
        } else {

          $oRetorno->status  = 2;
          $oRetorno->message = urlencode("Não foi possível localizar as etapas solicitadas!");
        }
      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Ano não informado!");
      }

    case 'buscaEmissor' :

      if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {

        $oDaoEscolaDiretor  = new cl_escoladiretor;
        $sCamposDiretor     = " 'DIRETOR' as funcao,                                                                 \n";
        $sCamposDiretor    .= " ed254_i_codigo as codigo,                                                            \n";
        $sCamposDiretor    .= " ed20_i_codigo as rechumano,                                                          \n";
        $sCamposDiretor    .= "         case when ed20_i_tiposervidor = 1 then                                       \n";
        $sCamposDiretor    .= "                 cgmrh.z01_nome                                                       \n";
        $sCamposDiretor    .= "              else cgmcgm.z01_nome                                                    \n";
        $sCamposDiretor    .= "         end as nome,                                                                 \n";
        $sCamposDiretor    .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo               \n";
        $sWhereDiretor      = " ed254_i_escola = {$oParam->iEscola} AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2\n";
        $sSqlDiretor        = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);

        $oDaoRechumanoAtiv  = new cl_rechumanoativ();
        $sCamposSec         = " DISTINCT ed01_c_descr as funcao,                                                     \n";
        $sCamposSec        .= " ed254_i_codigo as codigo,                                                            \n";
        $sCamposSec        .= " ed20_i_codigo as rechumano,                                                          \n";
        $sCamposSec        .= "         case when ed20_i_tiposervidor = 1 then                                       \n";
        $sCamposSec        .= "                 cgmrh.z01_nome                                                       \n";
        $sCamposSec        .= "              else cgmcgm.z01_nome                                                    \n";
        $sCamposSec        .= "         end as nome,                                                                 \n";
        $sCamposSec        .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo               \n";
        $sWhereSec          = " ed75_i_escola = {$oParam->iEscola} AND ed01_i_funcaoadmin = 3                        \n";
        $sSqlSec            = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);

        $sSqlUnion          = $sSqlDiretor;
        $sSqlUnion         .= " UNION ";
        $sSqlUnion         .= $sSqlSec;

        $rsAssinatura       = db_query($sSqlUnion);

        $oRetorno->dados = array();

        if ( !$rsAssinatura ) {

          $oRetorno->status  = 2;
          $oRetorno->message = urlencode("Erro ao buscar emissor!");
        } else {

          $oDados            = db_utils::getCollectionByRecord($rsAssinatura, false, false, true);
          $oRetorno->dados   = $oDados;
        }
      }
      break;

    case 'getPeriodosAvaliacao' :

      $oDaoPeriodo = new cl_periodoavaliacao();
      $sSqlPeriodo = $oDaoPeriodo->sql_query_file(null, "*", " ed09_i_sequencia ");
      $rsPeriodos  = db_query($sSqlPeriodo);

      $aPeriodosAvaliacao = array();
      if ( $rsPeriodos && pg_num_rows($rsPeriodos) > 0 ) {

        $iLinhas = pg_num_rows($rsPeriodos);
        for ( $i = 0; $i < $iLinhas; $i ++ ) {

          $oDado                       = db_utils::fieldsMemory($rsPeriodos, $i);
          $oPeriodo                    = new stdClass();
          $oPeriodo->iPeriodoAvaliacao = $oDado->ed09_i_codigo;
          $oPeriodo->sPeriodoAvaliacao = utf8_encode( $oDado->ed09_c_descr );
          $oPeriodo->sPeriodoAbrev     = utf8_encode( $oDado->ed09_c_abrev );
          $aPeriodosAvaliacao[]        = $oPeriodo;
        }
      }
      $oRetorno->aPeriodosAvaliacao = $aPeriodosAvaliacao;

      break;

    case 'getAreasTrabalho':

      $iEscola = db_getsession('DB_coddepto');
      if ( isset($oParam->iEscola) && $oParam->iEscola != '') {
        $iEscola = $oParam->iEscola;
      } elseif (isset($oParam->iEscola) && $oParam->iEscola == 0) {
        $iEscola = null;
      }

      $sWhere = "";
      if ( !empty($iEscola) ) {
        $sWhere = " ed75_i_escola = {$iEscola} ";
      }

      $sCampos = " DISTINCT ed25_i_codigo, ed25_c_descr";
      $sOrder  = " ed25_c_descr ";

      $oDaoRelacaoTrabalho = new cl_relacaotrabalho();
      $sSqlAreaTrabalho    = $oDaoRelacaoTrabalho->sql_query_area_trabalho(null, $sCampos, $sOrder, $sWhere);
      $rsAreaTrabalho      = $oDaoRelacaoTrabalho->sql_record($sSqlAreaTrabalho);


      $iLinhas = $oDaoRelacaoTrabalho->numrows;

      $oRetorno->aAreaTrabalho = array();
      for ($i = 0; $i < $iLinhas; $i++) {
        $oRetorno->aAreaTrabalho[] = db_utils::fieldsMemory($rsAreaTrabalho, $i, true, false, true);
      }

      break;

    /**
     * Retorna o código e a descrição de todos os Procedimentos de Avaliação que a Escola possui
     */
    case 'getProcedimentosAvaliacao':

      $oEscola     = new Escola( $oParam->iEscola );
      $oCalendario = CalendarioRepository::getCalendarioByCodigo( $oParam->iCalendario );
      $oRetorno->aProcedimentosAvaliacao = array();


      foreach ($oEscola->getProcedimentosAvaliacao( $oCalendario ) as $oProcedimentoAvaliacao ) {

        $oStdProcedimento                = new stdClass();
        $oStdProcedimento->iProcedimento = $oProcedimentoAvaliacao->getCodigo();
        $oStdProcedimento->sProcedimento = urlencode($oProcedimentoAvaliacao->getDescricao());

        $oRetorno->aProcedimentosAvaliacao[] = $oStdProcedimento;
      }

      break;

    case 'pesquisaEnsinoCalendario':

      try {

        if ( empty($oParam->iCalendario ) ) {
          throw new ParameterException("Calendário não informado. Filtro obrigatório");
        }

        $sWhere     = " ed57_i_calendario = {$oParam->iCalendario} ";
        $sCampos    = " distinct ed10_i_codigo, trim(ed10_c_descr) as descricao, ed10_ordem ";
        $oDaoTurma  = new cl_turma();
        $sSqlEnsino = $oDaoTurma->sql_query_turma_ensino(null, $sCampos, 'ed10_ordem', $sWhere);


        $rsEnsino = db_query($sSqlEnsino);

        if( !$rsEnsino ) {
          throw new DBException("Falha ao buscar os ensinos.");
        }

        if ( pg_num_rows($rsEnsino) == 0) {

          $sMsgErro = "Não é possível buscar os Ensinos para o Calendário selecionado, pois não há turmas para o calendário.";
          throw new BusinessException($sMsgErro);
        }

        $oRetorno->aEnsinos = db_utils::getCollectionByRecord($rsEnsino, false, false, true);

      } catch (Exception $e) {
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($e->getMessage());
      }

      break;

    case 'buscarCursosEscola':
      $oRetorno->aCursos =  buscaCursos($iEscola);
      break;
  }
} catch ( Exception $e) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($e->getMessage());
}
$oRetorno->erro = $oRetorno->status == 2;
echo $oJson->encode($oRetorno);

function buscaCursos($iEscola) {

  $aCursos            = array();
  $sWhereCursoEscola  = '';
  $oDaoCursoEscola    = new cl_cursoescola();
  $sCamposCursoEscola = "distinct ed29_i_codigo, ed29_c_descr, ed29_i_ensino";

  if ($iEscola != 0) {
    $sWhereCursoEscola = "ed71_i_escola = {$iEscola}";
  }

  $sSqlCursoEscola   = $oDaoCursoEscola->sql_query(null, $sCamposCursoEscola, null, $sWhereCursoEscola);
  $rsCursoEscola     = db_query($sSqlCursoEscola);
  if (!$rsCursoEscola) {
    throw new Exception(pg_last_error());
  }

  $iTotalCursoEscola = pg_num_rows($rsCursoEscola);
  for ($iContador = 0; $iContador < $iTotalCursoEscola; $iContador++) {

    $oDadosCursoEscola         = db_utils::fieldsMemory($rsCursoEscola, $iContador);
    $oDadosRetorno             = new stdClass();
    $oDadosRetorno->iCodigo    = $oDadosCursoEscola->ed29_i_codigo;
    $oDadosRetorno->sDescricao = urlencode($oDadosCursoEscola->ed29_c_descr);
    $oDadosRetorno->iEnsino    = $oDadosCursoEscola->ed29_i_ensino;
    $aCursos[]                 = $oDadosRetorno;
  }
  return $aCursos;
}