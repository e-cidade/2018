<?
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch ($oParam->exec) {

    /**
     * Validamos se existe regenciahoraria para algum regente/disciplina na turma. Caso exista, verificamos se o regente
     * encontra-se ausente e com substituto cadastrado
     */
    case 'validarRegente':

      $oRetorno->lTemRegenteAusente = false;

      $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
      $sWhereRegenciaHorario  = "ed59_i_turma = {$oParam->iTurma} AND ed59_i_serie = {$oParam->iEtapa}";
      $sWhereRegenciaHorario .= " AND ed58_ativo is true AND ed18_i_codigo = {$iEscola} AND ed57_i_escola = {$iEscola}";
      $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, "ed58_i_rechumano", null, $sWhereRegenciaHorario);
      $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
      $iTotalRegenciaHorario  = $oDaoRegenciaHorario->numrows;

      if ($iTotalRegenciaHorario > 0) {

        for ($iContador = 0; $iContador < $iTotalRegenciaHorario; $iContador++) {

          $iRecHumano              = db_utils::fieldsMemory($rsRegenciaHorario, $iContador)->ed58_i_rechumano;
          $oDaoDocenteSubstituto   = db_utils::getDao("docentesubstituto");
          $sWhereDocenteSubstituto = "ed321_rechumano = {$iRecHumano} AND ed321_escola = {$iEscola}";
          $sSqlDocenteSubstituto   = $oDaoDocenteSubstituto->sql_query(null, "ed321_sequencial", null, $sWhereDocenteSubstituto);
          $rsDocenteSubstituto     = $oDaoDocenteSubstituto->sql_record($sSqlDocenteSubstituto);

          if ($oDaoDocenteSubstituto->numrows > 0) {
            $oRetorno->lTemRegenteAusente = true;
          }
        }
      }
      break;

    /**
     * Buscamos os vinculos existentes entre Regente/Disciplina em uma turma
     */
    case 'buscaVinculosRealizados':

      $oRetorno->aDados        = array();
      $oDaoRegenciaHorario     = db_utils::getDao("regenciahorario");
      $sCamposRegenciaHorario  = "distinct ed58_i_regencia, ed58_i_rechumano, ed17_i_turno, ed59_i_disciplina";
      $sWhereRegenciaHorario   = "ed59_i_turma = {$oParam->iTurma} AND ed59_i_serie = {$oParam->iEtapa}";
      $sWhereRegenciaHorario  .= " AND ed58_ativo is true AND ed57_i_escola = {$iEscola}";
      $sSqlRegenciaHorario     = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
      $rsRegenciaHorario       = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
      $iLinhasRegenciaHorario  = $oDaoRegenciaHorario->numrows;

      if ($iLinhasRegenciaHorario > 0) {

        for ($iContador = 0; $iContador < $iLinhasRegenciaHorario; $iContador++) {

          $oDadosVinculo               = new stdClass();
          $oDadosRegenciaHorario       = db_utils::fieldsMemory($rsRegenciaHorario, $iContador);
          $oDadosVinculo->iRegencia    = $oDadosRegenciaHorario->ed58_i_regencia;
          $oDadosVinculo->iRecHumano   = $oDadosRegenciaHorario->ed58_i_rechumano;
          $oDocente                    = DocenteRepository::getDocenteByCodigoRecursosHumano($oDadosRegenciaHorario->ed58_i_rechumano);

          if ( is_null($oDocente->getCodigoDocente()) ) {
            continue;
          }

          $oDadosVinculo->sRegente     = urlencode($oDocente->getNome());
          $oDisciplina                 = DisciplinaRepository::getDisciplinaByCodigo($oDadosRegenciaHorario->ed59_i_disciplina);
          $oDadosVinculo->sDisciplina  = urlencode($oDisciplina->getNomeDisciplina());
          $oTurno                      = new Turno($oDadosRegenciaHorario->ed17_i_turno);
          $oDadosVinculo->sTurno       = urlencode($oTurno->getDescricao());
          $oDadosVinculo->lConselheiro = false;

          /**
           * Verifica se o regente da disciplina eh o conselheiro da turma
           */
          $oDaoRegenteConselho    = db_utils::getDao("regenteconselho");
          $sWhereRegenteConselho  = "     ed235_i_turma = {$oParam->iTurma}";
          $sWhereRegenteConselho .= " AND ed235_i_rechumano = {$oDadosRegenciaHorario->ed58_i_rechumano}";
          $sSqlRegenteConselho    = $oDaoRegenteConselho->sql_query_file(null, "*", null, $sWhereRegenteConselho);
          $rsRegenteConselho      = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

          if ($oDaoRegenteConselho->numrows > 0) {
            $oDadosVinculo->lConselheiro = true;
          }

          $oRetorno->aDados[] = $oDadosVinculo;
        }
      }
      break;

    /**
     * Salva o regente conselheiro selecionado para a turma. Caso tenha sido selecionado em branco o regente,
     * apenas excluimos o registro de conselheiro existente
     */
    case 'salvarRegenteConselheiro':

      db_inicio_transacao();

      $oDaoRegenteConselho   = db_utils::getDao("regenteconselho");
      $sWhereRegenteConselho = "ed235_i_turma = {$oParam->iTurma}";
      $oDaoRegenteConselho->excluir(null, $sWhereRegenteConselho);

      if ($oDaoRegenteConselho->erro_status == "0") {
        throw new DBException($oDaoRegenteConselho->erro_msg);
      }

      if (!empty($oParam->iRecHumano)) {

        $oDaoRegenteConselho->ed235_i_turma     = $oParam->iTurma;
        $oDaoRegenteConselho->ed235_i_rechumano = $oParam->iRecHumano;
        $oDaoRegenteConselho->incluir(null);

        if ($oDaoRegenteConselho->erro_status == "0") {
          throw new DBException($oDaoRegenteConselho->erro_msg);
        }
      }

      db_fim_transacao(false);
      break;

    /**
     * Pesquisa as disciplinas da turma que estao disponiveis para vinculo
     */
    case 'buscaDisciplinasParaVincularComRegente':

      $oRetorno->aDisciplinas = array();

      $oTurma     = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa     = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $aRegencias = $oTurma->getDisciplinasPorEtapa($oEtapa);

      foreach ($aRegencias as $oRegencia) {

        $oDaoRegenciaHorario        = db_utils::getDao("regenciahorario");
        $sWhereRegenciaHorario      = "ed58_i_regencia = {$oRegencia->getCodigo()} and ed58_ativo is true";
        $sSqlRegenciaHorario        = $oDaoRegenciaHorario->sql_query_file(null, "*", null, $sWhereRegenciaHorario);
        $rsRegenciaHorario          = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);

        if ($oDaoRegenciaHorario->numrows == 0) {

          $oDadosRegencia             = new stdClass();
          $oDadosRegencia->iRegencia  = $oRegencia->getCodigo();
          $oDadosRegencia->sDescricao = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
          $oRetorno->aDisciplinas[]   = $oDadosRegencia;
        }
      }
      break;

    /**
     * Vinculamos um regente a uma ou mais disciplinas
     */
    case 'vincularRegenteDisciplina':

      db_inicio_transacao();

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

      /**
       * Buscamos os periodos da escola
       */
      $oDaoPeriodoEscola    = new cl_periodoescola;
      $sWherePeriodoEscola  = "ed17_i_turno = {$oTurma->getTurno()->getCodigoTurno()} AND ed17_i_escola = {$iEscola}";
      $sSqlPeriodoEscola    = $oDaoPeriodoEscola->sql_query_file(null, "ed17_i_codigo", null, $sWherePeriodoEscola);
      $rsPeriodoEscola      = $oDaoPeriodoEscola->sql_record($sSqlPeriodoEscola);
      $iLinhasPeriodoEscola = $oDaoPeriodoEscola->numrows;

      /**
       * Buscamos os dias letivos da escola
       */
      $oDaoDiaLetivo    = new cl_dialetivo;
      $sWhereDiaLetivo  = "ed04_i_escola = {$iEscola} AND ed04_c_letivo = 'S'";
      $sSqlDiaLetivo    = $oDaoDiaLetivo->sql_query_file(null, "ed04_i_diasemana", null, $sWhereDiaLetivo);
      $rsDiaLetivo      = $oDaoDiaLetivo->sql_record($sSqlDiaLetivo);
      $iLinhasDiaLetivo = $oDaoDiaLetivo->numrows;

      $oTurma      = new \Turma($oParam->iTurma);
      $oCalendario = $oTurma->getCalendario();

      /**
       * => Verificamos se houve retorno tanto para os periodos da escola, quanto para os dias letivos
       * => Percorremos as regencias selecionadas, os periodos da escola e para cada periodo, os dias letivos, salvando
       *    na tabela regenciahorario
       */
      if ($iLinhasPeriodoEscola > 0 && $iLinhasDiaLetivo > 0) {

        foreach ($oParam->aRegencias as $iRegencia) {

          for ($iContadorPeriodoEscola = 0; $iContadorPeriodoEscola < $iLinhasPeriodoEscola; $iContadorPeriodoEscola++) {

            $iPeriodoEscola = db_utils::fieldsMemory($rsPeriodoEscola, $iContadorPeriodoEscola)->ed17_i_codigo;

            for ($iContadorDiaLetivo = 0; $iContadorDiaLetivo < $iLinhasDiaLetivo; $iContadorDiaLetivo++) {

              $iDiaLetivo                            = db_utils::fieldsMemory($rsDiaLetivo, $iContadorDiaLetivo)->ed04_i_diasemana;
              $oDaoRegenciaHorario                   = new cl_regenciahorario;
              $oDaoRegenciaHorario->ed58_i_regencia  = $iRegencia;
              $oDaoRegenciaHorario->ed58_i_diasemana = $iDiaLetivo;
              $oDaoRegenciaHorario->ed58_i_periodo   = $iPeriodoEscola;
              $oDaoRegenciaHorario->ed58_i_rechumano = $oParam->iRecHumano;
              $oDaoRegenciaHorario->ed58_ativo       = 'true';
              $oDaoRegenciaHorario->ed58_tipovinculo = 1;
              $oDaoRegenciaHorario->ed58_datainicio = $oCalendario->getDataInicio()->getDate();
              $oDaoRegenciaHorario->ed58_datafim    = $oCalendario->getDataFinal()->getDate();
              $oDaoRegenciaHorario->incluir(null);

              if ($oDaoRegenciaHorario->erro_status == "0") {
                throw new DBException($oDaoRegenciaHorario->erro_msg);
              }
            }
          }
        }
      } else {

        $sMsg  = "Não foi possível concluir o vínculo pois não foram identificados os períodos da escola e/ou";
        $sMsg .= " dias letivos";
        throw new BusinessException($sMsg);
      }

      db_fim_transacao(false);
      break;

    /**
     * Remove o vinculo de um regente com uma disciplina
     */
    case 'desvincularRegenteDisciplina':

      db_inicio_transacao();

      /**
       * Primeiramente validamos se o rechumano em questão, possui vinculo com apenas 1 regencia na turma. Caso sim,
       * verificamos se ele é o regente conselheiro da turma, excluindo o registro da tabela regenteconselho, para
       * então excluir o vinculo
       */
      $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
      $sCamposRegenciaHorario = "distinct ed58_i_regencia, count(*)";
      $sWhereRegenciaHorario  = "     ed59_i_turma = {$oParam->iTurma} AND ed58_i_rechumano = {$oParam->iRecHumano}";
      $sWhereRegenciaHorario .= " AND ed58_ativo is true AND ed57_i_escola = {$iEscola} ";
      $sWhereRegenciaHorario .= " group by ed58_i_regencia having count(*) > 1";
      $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
      $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);

      if ($oDaoRegenciaHorario->numrows == 1) {

        $oDaoRegenteConselho   = db_utils::getDao("regenteconselho");
        $sWhereRegenteConselho = "ed235_i_turma = {$oParam->iTurma} AND ed235_i_rechumano = {$oParam->iRecHumano}";
        $sSqlRegenteConselho   = $oDaoRegenteConselho->sql_query_file(null, "ed235_i_codigo", null, $sWhereRegenteConselho);
        $rsRegenteConselho     = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

        if ($oDaoRegenteConselho->numrows > 0) {

          $iRegenteConselho              = db_utils::fieldsMemory($rsRegenteConselho, 0)->ed235_i_codigo;
          $sWhereExclusaoRegenteConselho = "ed235_i_codigo = {$iRegenteConselho}";
          $oDaoRegenteConselho->excluir(null, $sWhereExclusaoRegenteConselho);

          if ($oDaoRegenteConselho->erro_status == "0") {
            throw new DBException($oDaoRegenteConselho->erro_msg);
          }
        }
      }


      $sWhereRegenciaHorarioAlteracao  = "ed58_i_regencia = {$oParam->iRegencia}";
      $sSqlRegenciaHorarioAlteracao    = $oDaoRegenciaHorario->sql_query(null,
                                                                          "ed58_i_codigo",
                                                                          null,
                                                                          $sWhereRegenciaHorarioAlteracao
                                                                         );
      $rsRegenciaHorarioAlteracao      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorarioAlteracao);
      $iLinhasRegenciaHorarioAlteracao = $oDaoRegenciaHorario->numrows;

      if ($iLinhasRegenciaHorarioAlteracao > 0) {

        for ($iContador = 0; $iContador < $iLinhasRegenciaHorarioAlteracao; $iContador++) {

          $iRegenciaHorario = db_utils::fieldsMemory($rsRegenciaHorarioAlteracao, $iContador)->ed58_i_codigo;

          $oDaoRegenciaHorarioAlteracao                = db_utils::getDao("regenciahorario");
          $oDaoRegenciaHorarioAlteracao->ed58_ativo    = 'false';
          $oDaoRegenciaHorarioAlteracao->ed58_i_codigo = $iRegenciaHorario;
          $oDaoRegenciaHorarioAlteracao->alterar($iRegenciaHorario);

          if ($oDaoRegenciaHorarioAlteracao->erro_status == "0") {
            throw new DBException($oDaoRegenciaHorarioAlteracao->erro_msg);
          }
        }
      }

      db_fim_transacao(false);
      break;

    /**
     * Validamos se existe algum vinculo ja realizado entre regente e disciplina na turma
     */
    case 'validaTrocaVinculo':

      $oRetorno->lTemVinculos = false;
      $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
      $sWhereRegenciaHorario  = "ed59_i_turma = {$oParam->iTurma} AND ed58_ativo is true AND ed57_i_escola = {$iEscola}";
      $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, "ed58_i_codigo", null, $sWhereRegenciaHorario);
      $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);

      if ($oDaoRegenciaHorario->numrows > 0) {

        $sMsg  = "A turma já possui horários vinculados entre Regente/Disciplina. Ao alterar o tipo de vínculo,\n";
        $sMsg .= " os vínculos existentes serão excluídos. Deseja continuar?";

        $oRetorno->lTemVinculos = true;
        $oRetorno->message      = urlencode($sMsg);
      }

      break;

    /**
     * Excluimos todos os vinculos entre regentes/disciplinas de uma turma, assim como o registro da regenteconselho
     */
    case 'excluiVinculos':

      db_inicio_transacao();

      $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
      $sCamposRegenciaHorario = "ed58_i_codigo, ed58_i_rechumano";
      $sWhereRegenciaHorario  = "ed59_i_turma = {$oParam->iTurma} AND ed58_ativo is true AND ed57_i_escola = {$iEscola}";
      $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
      $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
      $iLinhasRegenciaHorario = $oDaoRegenciaHorario->numrows;

      if ($iLinhasRegenciaHorario > 0) {

        for ($iContador = 0; $iContador < $iLinhasRegenciaHorario; $iContador++) {

          $oDadosRegenciaHorario = db_utils::fieldsMemory($rsRegenciaHorario, $iContador);

          /**
           * Removemos o registro da tabela regenteconselho, caso o rechumano seja o conselheiro
           */
          $oDaoRegenteConselho    = db_utils::getDao("regenteconselho");
          $sWhereRegenteConselho  = "     ed235_i_turma = {$oParam->iTurma}";
          $sWhereRegenteConselho .= " AND ed235_i_rechumano = {$oDadosRegenciaHorario->ed58_i_rechumano}";
          $sSqlRegenteConselho    = $oDaoRegenteConselho->sql_query_file(null, "ed235_i_codigo", null, $sWhereRegenteConselho);
          $rsRegenteConselho      = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

          if ($oDaoRegenteConselho->numrows > 0) {

            $iRegenteConselho              = db_utils::fieldsMemory($rsRegenteConselho, 0)->ed235_i_codigo;
            $sWhereExclusaoRegenteConselho = "ed235_i_codigo = {$iRegenteConselho}";
            $oDaoRegenteConselho->excluir(null, $sWhereExclusaoRegenteConselho);

            if ($oDaoRegenteConselho->erro_status == "0") {
              throw new DBException($oDaoRegenteConselho->erro_msg);
            }
          }

          $oDaoRegenciaHorarioAlteracao                = db_utils::getDao("regenciahorario");
          $oDaoRegenciaHorarioAlteracao->ed58_ativo    = 'false';
          $oDaoRegenciaHorarioAlteracao->ed58_i_codigo = $oDadosRegenciaHorario->ed58_i_codigo;
          $oDaoRegenciaHorarioAlteracao->alterar($oDadosRegenciaHorario->ed58_i_codigo);

          if ($oDaoRegenciaHorarioAlteracao->erro_status == "0") {
            throw new DBException($oDaoRegenciaHorarioAlteracao->erro_msg);
          }
        }
      }

      db_fim_transacao(false);
      break;

    /**
     * Retorna as atividades de um docente em uma escola
     */
    case 'getAtividadesDocente':

      if (isset($oParam->iNumCgm) && !empty($oParam->iNumCgm)) {

        $oRetorno->aAtividades = array();
        $oDocente              = DocenteRepository::getDocenteByCodigo($oParam->iNumCgm);
        $oEscola               = EscolaRepository::getEscolaByCodigo($iEscola);
        $aAtividades           = $oDocente->getAtividades($oEscola);

        $aCodigo = array();

        foreach ($aAtividades as $oDocenteAtividade) {

          if (!in_array($oDocenteAtividade->getAtividade()->getCodigo(), $aCodigo)) {

            $oDadosAtividade             = new stdClass();
            $oDadosAtividade->iCodigo    = $oDocenteAtividade->getAtividade()->getCodigo();
            $oDadosAtividade->sDescricao = urlencode($oDocenteAtividade->getAtividade()->getDescricao());
            $oRetorno->aAtividades[]     = $oDadosAtividade;

            $aCodigo[] = $oDocenteAtividade->getAtividade()->getCodigo();
          }
        }
      }

      break;

    case 'getProfessorEscola' :

      $iEscola  = db_getsession('DB_coddepto');
      if ( isset($oParam->iEscola) && !empty($oParam->iEscola) ) {
        $iEscola = $oParam->iEscola;
      } elseif (isset($oParam->iEscola) && $oParam->iEscola == 0) {
        $iEscola = null;
      }

      $aWhere   = array();
      $aWhere[] = " ed01_c_regencia = 'S' ";

      if ( !empty($iEscola) ) {
        $aWhere[] = " ed75_i_escola = {$iEscola} ";
      }
      if ( isset($oParam->iMatricula) && !empty($oParam->iMatricula) ) {
        $aWhere[] = "ed284_i_rhpessoal = {$oParam->iMatricula} ";
      }

      if ( isset($oParam->iCgm) && !empty($oParam->iCgm) ) {
        $aWhere[] = " (ed285_i_cgm = {$oParam->iCgm} or cgmrh.z01_numcgm = {$oParam->iCgm}) ";
      }

      if ( isset($oParam->sNomeProfessor) && !empty($oParam->sNomeProfessor) ) {

        $sProfessores = db_stdClass::normalizeStringJsonEscapeString($oParam->sNomeProfessor);
        $aWhere[] = " to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end) ilike '{$sProfessores}%' ";
      }

      $sWhere   = implode(" and ", $aWhere);
      $sCampos  = " distinct on (z01_nome) case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome, ";
      $sCampos .= " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as z01_numcgm, ";
      $sCampos .= " case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as z01_cgccpf ";

      $oDaoRecHumano   = new cl_rechumano();
      $sSqlProfessores = $oDaoRecHumano->sql_query_escola(null, $sCampos, "z01_nome", $sWhere);
      $rsProfessores   = $oDaoRecHumano->sql_record($sSqlProfessores);

      $iLinhas = $oDaoRecHumano->numrows;

      $oRetorno->aProfessores = array();
      for ($i = 0; $i < $iLinhas; $i++) {

        $oRetorno->aProfessores[] = db_utils::fieldsMemory($rsProfessores, $i, true, false, true);
      }


      break;

    /**
     * Retorna um array com as movimentações do regente
     */
    case 'getMovimentacoesDoRegente' :

      $oDaoRecHumanoMovimentacao   = new cl_rechumanomovimentacao();
      $sWhereRecHumanoMovimentacao = " ed118_rechumano = {$oParam->iRecHumano}";
      $sOrderRecHumanoMovimentacao = "ed118_data, ed118_hora";
      $sSqlRecHumanoMovimentacao   = $oDaoRecHumanoMovimentacao->sql_query_file('','*','',$sWhereRecHumanoMovimentacao);
      $rsRecHumanoMovimentacao     = pg_query( $sSqlRecHumanoMovimentacao );
      $iLinhas                     = pg_num_rows( $rsRecHumanoMovimentacao );

      $oRetorno->aMovimentacoes = array();
      if ( $iLinhas > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

          $oDados   = db_utils::fieldsMemory( $rsRecHumanoMovimentacao, $iContador, true );
          $oEscola  = new Escola( $oDados->ed118_escola );
          $oUsuario = new UsuarioSistema( $oDados->ed118_usuario );

          $oDados->ed118_escola       = urlencode( $oEscola->getNome() );
          $oDados->ed118_usuario      = urlencode( $oUsuario->getNome() );
          $oDados->ed118_resumo       =  urlencode( $oDados->ed118_resumo );
          $oRetorno->aMovimentacoes[] = $oDados;
        }
      }

      break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>