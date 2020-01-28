<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlibwebseller.php");
require_once("model/webservices/ControleAcessoAluno.model.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
db_app::import("educacao.*");
db_app::import("educacao.censo.DisciplinaCenso");
db_app::import("exceptions.*");
db_app::import("educacao.avaliacao.*");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";


$iEscola        = db_getsession("DB_coddepto");
$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {

  case 'getDisciplinasRegenteEscola':

    $sDtLogin = date("Y-m-d", db_getsession('DB_datausu'));

    $oRetorno->codigo_regente = 0;
    $oRetorno->nome_regente   = '';
    $oDaoDBUsusario   = db_utils::getDao("db_usuacgm");
    $iCodigoUsuario   = db_getsession("DB_id_usuario");
    $sSqlDadosRegente = $oDaoDBUsusario->sql_query($iCodigoUsuario, "z01_numcgm, z01_nome");
    $rsDadosRegente   = $oDaoDBUsusario->sql_record($sSqlDadosRegente);

    if ($oDaoDBUsusario->numrows > 0) {

      $oDadosRegente            = db_utils::fieldsMemory($rsDadosRegente, 0);
      $oRetorno->codigo_regente =  $oDadosRegente->z01_numcgm;
      $oRetorno->nome_regente   =  $oDadosRegente->z01_nome;
    }
    if (isset($_SESSION["DIAS_LETIVOS_ESCOLA"])) {
       unset($_SESSION["DIAS_LETIVOS_ESCOLA"]);
    }

    $sWhereFuncionario  = "     rhpessoal.rh01_numcgm         = {$oDadosRegente->z01_numcgm} ";
    $sWhereFuncionario .= " and rechumanoescola.ed75_i_escola = {$iEscola}                   ";

    $sWhereNaoFuncionario  = "     rechumanocgm.ed285_i_cgm      = {$oDadosRegente->z01_numcgm} ";
    $sWhereNaoFuncionario .= " and rechumanoescola.ed75_i_escola = {$iEscola}                   ";

    $sWhereRegenciaHorario  = "    ed58_ativo is true      ";
    $sWhereRegenciaHorario .= " and ed59_c_freqglob <> 'A' ";

    $sWhereSubstituto  = " {$sWhereRegenciaHorario}";
    $sWhereSubstituto .= " and '{$sDtLogin}' >= ed322_periodoinicial ";
    $sWhereSubstituto .= " and ((ed322_periodofinal is null and  '{$sDtLogin}' <= ed52_d_fim) ";
    $sWhereSubstituto .= "       or  '{$sDtLogin}' <= ed322_periodofinal) ";


    $sCampos  = " distinct ed232_i_codigo as codigo_disciplina, ";
    $sCampos .= " ed232_c_descr as descricao_disciplina";

    $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
    $sSqlDisciplinasRegente = $oDaoRegenciaHorario->sql_query_disciplinas_cgm($sCampos,
                                                                              "descricao_disciplina",
                                                                              $sWhereFuncionario,
                                                                              $sWhereNaoFuncionario,
                                                                              $sWhereRegenciaHorario,
                                                                              $sWhereSubstituto);

     $rsDisciplinasRegente = $oDaoRegenciaHorario->sql_record($sSqlDisciplinasRegente);
     $oRetorno->itens      = db_utils::getCollectionByRecord($rsDisciplinasRegente, false, false, true);
     break;

  case 'getDatasProfessorDisciplina':

    $oDaoRecHumano       = db_utils::getDao('rechumano');
    $oDaoRegenciaHorario = db_utils::getDao("regenciahorario");

    if (isset($_SESSION["DIAS_LETIVOS_ESCOLA"])) {
      unset($_SESSION["DIAS_LETIVOS_ESCOLA"]);
    }
    $aDiasSemana = array (0 => "DOMINGO",
                          1 => "SEGUNDA",
                          2 => "TER�A",
                          3 => "QUARTA",
                          4 => "QUINTA",
                          5 => "SEXTA",
                          6 => "S�BADO"
                         );
    $aDiasLetivos = array();

    $sCampoRegente  = " distinct ed20_i_codigo ";
    $sWhereRegente  = "     (rh01_numcgm  = {$oParam->iRegente} or ed285_i_cgm = {$oParam->iRegente})";
    $sWhereRegente .= " and ed75_i_escola = {$iEscola}";
    $sWhereRegente .= " and ed75_i_saidaescola is null ";


    $sSqlRegente   = $oDaoRecHumano->sql_query_rechumano_cgm(null, $sCampoRegente, null, $sWhereRegente);
    $rsRegente     = $oDaoRecHumano->sql_record($sSqlRegente);

    if ($oDaoRecHumano->numrows == 0) {
      throw new Exception('N�o foi poss�vel localizar o regente.');
    }

    $aCodigoRecHumano = db_utils::getCollectionByRecord( $rsRegente );
    $aCodigos         = array();
    foreach ($aCodigoRecHumano as $oCodigoRecHumano ) {
      $aCodigos[] = $oCodigoRecHumano->ed20_i_codigo;
    }

    $sCodigoRecHumano = implode( ", ", $aCodigos );
    $sWhere  = " ed57_i_escola    = {$iEscola} ";
    $sWhere .= " and ed52_i_ano   = ".db_getsession("DB_anousu");
    $sWhere .= " and ed58_ativo is true  ";
    $sWhere .= " and ed232_i_codigo = {$oParam->iCodigoDisciplina}";
    $sWhere .= " and ed58_i_rechumano in ({$sCodigoRecHumano}) ";


    $sWhereRegencia      = " {$sWhere} ";
    $sWhereRegencia     .= " and ed58_i_rechumano in ({$sCodigoRecHumano}) ";

    $sWhereSubstituicao  = " {$sWhere} ";
    $sWhereSubstituicao .= " and ed322_rechumano in ({$sCodigoRecHumano})";

    $sCampos                 = " distinct ed58_i_diasemana, ed59_i_serie as serie, ";
    $sCampos                .= " trim(ed11_c_descr) as nome_etapa, ed57_i_codigo as codigo_turma, ";
    $sCampos                .= " ed57_c_descr as descricao_turma ";

    $sSqlDiasDaSemanaComAula = $oDaoRegenciaHorario->sql_query_diario_classe_periodo_substituicao($sCampos, null,
                                                                                                  $sWhereRegencia,
                                                                                                  $sWhereSubstituicao
                                                                                                 );
    $rsDiasDaSemanaComAula     = $oDaoRegenciaHorario->sql_record($sSqlDiasDaSemanaComAula);


    $aDiasDeAula               = array();
    $iTotalDiasDaSemanaComAula = $oDaoRegenciaHorario->numrows;
    for ($iAula = 0; $iAula < $iTotalDiasDaSemanaComAula; $iAula++) {

      /**
       * Diminuimos 1 do dia da semana, pois o dia do semana para o php, inicia em 0 para domingo,
       * e termina em 6 (s�bado.), na tabela diasemana, o inicio � 1 para domingo, e 7 para S�bado;
       */
      $oDadosDiaSemana = db_utils::fieldsMemory($rsDiasDaSemanaComAula, $iAula);
      $iDiaNaSemana    = $oDadosDiaSemana->ed58_i_diasemana -1;
      if (!isset($aDiasDeAula[$iDiaNaSemana])) {
        $aDiasDeAula[$iDiaNaSemana] = new stdClass();
      }

      $oTurma = new stdClass();
      $oTurma->codigo_turma     = $oDadosDiaSemana->codigo_turma."_".$oDadosDiaSemana->serie;
      $oTurma->codigo_serie     = $oDadosDiaSemana->serie;
      $oTurma->descricao_turma  = urlencode("{$oDadosDiaSemana->descricao_turma} ({$oDadosDiaSemana->nome_etapa})");
      $aDiasDeAula[$iDiaNaSemana]->turmas[] = $oTurma;
    }

    /**
     * Carregamos as datas do calendario.
     */
    $sCampos             = "min(ed53_d_inicio) as inicio, max(ed53_d_fim) as maximo, ";
    $sCampos            .= "array_to_string(array_accum(distinct ed52_i_codigo), ', ') as calendarios,";
    $sCampos            .= "max(ed53_d_fim) - min(ed53_d_inicio) as numero_dias_aula";
    $sSqlDatasCalendario = $oDaoRegenciaHorario->sql_query_diario_classe_periodo_avaliacao(null,
                                                                                           $sCampos,
                                                                                           null,
                                                                                           $sWhere
                                                                                          );
    $rsDatasCalendario   = $oDaoRegenciaHorario->sql_record($sSqlDatasCalendario);
    try {

      if ($oDaoRegenciaHorario->numrows == 0) {
        throw new Exception("Periodo sem calendario informado.\n{$oDaoRegenciaHorario->erro_msg}");
      }
      $oDadosAnoLetivo = db_utils::fieldsMemory($rsDatasCalendario, 0);

      /**
       * Criamos uma collection de com os feriados cadastrados para o(s) Calendarios que o Professor est� vinculado.
       */
      $oDaoFeriado = db_utils::getDao("feriado");
      $sSqlFeriado = $oDaoFeriado->sql_query_file(null,
                                                  "ed54_d_data as data, ed54_c_dialetivo as dia_letivo",
                                                  "ed54_d_data",
                                                  "ed54_i_calendario in({$oDadosAnoLetivo->calendarios})"
                                                 );

      $rsFeriado  = $oDaoFeriado->sql_record($sSqlFeriado);
      $aFeriados  = db_utils::getCollectionByRecord($rsFeriado);

      list($iAnoInicial, $iMesInicial, $iDiaInicial) = explode("-", $oDadosAnoLetivo->inicio);
      for ($iDia = 0; $iDia <= $oDadosAnoLetivo->numero_dias_aula; $iDia++) {

        $sDataDiaTimeStamp = mktime(0, 0, 0, $iMesInicial, $iDiaInicial+$iDia, $iAnoInicial);
        $sDataFormatada    = date("Y-m-d", $sDataDiaTimeStamp);
        $iDiaSemana        = date("w" , $sDataDiaTimeStamp);

        /**
         * Carregamos apenas as datas iguais  ou anteriores ao dia da sess�o.
         */
        if (db_strtotime($sDataFormatada) > db_strtotime(date("Y-m-d", db_getsession("DB_datausu")))) {
          continue;
        }

        /**
         * caso nao seja um dia de aula (dia da semana) do professor, passamos para o proximo dia
         */
        if (!array_key_exists($iDiaSemana, $aDiasDeAula)) {
          continue;
        }

        $oDiaLetivo        = new stdClass();
        /**
         * verificamos o dia � um feriado. caso seja passamos para o proximo dia.
         */
        if ($iFeriado = db_stdClass::inCollection("data", $sDataFormatada, $aFeriados)) {

          $oFeriado = $aFeriados[$iFeriado];
          if ($oFeriado->dia_letivo == 'N') {
             continue;
          }
        }

        /**
         * Criamos a Estrutura com os Dados do dia Letivo
         */
        $oDiaLetivo            = new stdClass();
        $oDiaLetivo->data      = $sDataFormatada;
        $oDiaLetivo->diasemana = urlencode($aDiasSemana[$iDiaSemana]);
        $oDiaLetivo->turmas    = $aDiasDeAula[$iDiaSemana]->turmas;
        $aDiasLetivos[]        = $oDiaLetivo;
      }

      $_SESSION["DIAS_LETIVOS_ESCOLA"] = $aDiasLetivos;
      $oRetorno->aDiasLetivos          = $aDiasLetivos;
      $oRetorno->dataatual             = date("Y-m-d", db_getsession("DB_datausu"));
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;


  case 'getTurmasNoDia' :

    $aTurmas = array();
    if (isset($oParam->dtAula) && isset($oParam->dtAula)) {

      if (isset($_SESSION["DIAS_LETIVOS_ESCOLA"])) {

        foreach ($_SESSION["DIAS_LETIVOS_ESCOLA"] as $oData) {

          if ($oData->data == $oParam->dtAula) {
            $aTurmas = $oData->turmas;
            break;
          }
        }
      }
    }
    $oRetorno->aTurmas = $aTurmas;
    break;


  case 'getAlunosTurma' :

    $oDaoMatricula                   = db_utils::getDao("matricula");
    $oDaoRecHumano                   = db_utils::getDao('rechumano');
    $oDaoRegenciaHorario             = db_utils::getDao("regenciahorario");
    $oDaoDiarioClasseAlunoFalta      = db_utils::getDao("diarioclassealunofalta");
    $oDaoDiarioClasseRegenciaHorario = db_utils::getDao("diarioclasseregenciahorario");
    $oDaoControleAcessoAluno         = db_utils::getDao("controleacessoalunoregistro");
    $oDaoControleAcesso              = db_utils::getDao("controleacessoaluno");

    $aPartesTurma  = explode("_", $oParam->iCodigoTurma);
    $iCodigoSerie  = '';
    if (isset($aPartesTurma[1])) {
     $iCodigoSerie  = $aPartesTurma[1];
    }
    $oParam->iCodigoTurma  = $aPartesTurma[0];
    $sListaCampos  = " aluno.ed47_v_nome as  nome, ";
    $sListaCampos .= " aluno.ed47_i_codigo as codigo, ";
    $sListaCampos .= " matricula.ed60_i_numaluno as ordem_turma, ";
    $sListaCampos .= " matricula.ed60_c_situacao as situacao, ";
    $sListaCampos .= " matricula.ed60_d_datamatricula as data_matricula, ";
    $sListaCampos .= " matricula.ed60_d_datasaida data_saida, ";
    $sListaCampos .= " matricula.ed60_i_codigo as codigo_matricula ";
    $sSqlAlunosMatriculadosNaTurma  = $oDaoMatricula->sql_query("",
                                                             $sListaCampos,
                                                             "matricula.ed60_i_numaluno, to_ascii(ed47_v_nome)",
                                                             "ed60_i_turma = {$oParam->iCodigoTurma}
                                                             and ed221_i_serie = {$iCodigoSerie}"
                                                            );


    $aPeriodosDeAulaDoDia        = array();
    $iDiaDaSemana                = date('w', db_strtotime($oParam->dtAula)) + 1;


    /**
     * Busca codigo do rechumano
     */
    $sCampoRegente  = " distinct ed20_i_codigo ";
    $sWhereRegente  = "     (rh01_numcgm  = {$oParam->iRegente} or ed285_i_cgm = {$oParam->iRegente})";
    $sWhereRegente .= " and ed75_i_escola = {$iEscola}";
    $sWhereRegente .= " and ed75_i_saidaescola is null ";

    $sSqlRegente    = $oDaoRecHumano->sql_query_rechumano_cgm(null, $sCampoRegente, null, $sWhereRegente);
    $rsRegente      = $oDaoRecHumano->sql_record($sSqlRegente);

    if ($oDaoRecHumano->numrows == 0) {
      throw new Exception('N�o foi poss�vel localizar o regente.');
    }
    $aRecursosHumanos = db_utils::getCollectionByRecord($rsRegente);
    $aCodigoRecursosHumanos = array();
    foreach ($aRecursosHumanos as $oRecursoHumano) {
      $aCodigoRecursosHumanos[] =  $oRecursoHumano->ed20_i_codigo;
    }
    $iCodigoRecHumano = implode(", ", $aCodigoRecursosHumanos);



    /**
     * Carregamos os periodos do dia.
     *
     */
    $sWhereHorarioAula  = " ed57_i_escola  = ".db_getsession("DB_coddepto");
    $sWhereHorarioAula .= " and ed57_i_codigo  = {$oParam->iCodigoTurma}";

    $sSqlHorarioAula = $oDaoControleAcesso->sql_query_horario_aula_turma(null,
                                                                         "min(ed17_h_inicio) as horainicio,
                                                                          max(ed17_h_fim) as horatermino",
                                                                         null,
                                                                         $sWhereHorarioAula
                                                                        );
    $rsHorarioAula    = $oDaoControleAcesso->sql_record($sSqlHorarioAula);
    if ($oDaoControleAcesso->numrows == 0) {
      throw new Exception("Turma sem per�odos de aula cadastrados.");
    }
    $sHoraInicioAula   = db_utils::fieldsMemory($rsHorarioAula, 0)->horainicio;
    $sHoraTerminoAula  = db_utils::fieldsMemory($rsHorarioAula, 0)->horatermino;


    /**
     * Busca os periodos de alua
     */
    $sWhere  = " ed57_i_escola    = {$iEscola} ";
    $sWhere .= " and ed52_i_ano   = ".db_getsession("DB_anousu");
    $sWhere .= " and ed58_ativo is true  ";
    $sWhere .= " and ed232_i_codigo = {$oParam->iCodigoDisciplina}";
    $sWhere .= " and ed58_i_diasemana = {$iDiaDaSemana}";
    $sWhere .= " and ed59_i_turma     = {$oParam->iCodigoTurma}";

    if ($iCodigoSerie != "") {
      $sWhere .= " and ed59_i_serie     = {$iCodigoSerie}";
    }

    $sWhereRegencia      = " {$sWhere} ";
    $sWhereRegencia     .= " and ed58_i_rechumano in ({$iCodigoRecHumano}) ";

    $sWhereSubstituicao  = " {$sWhere} ";
    $sWhereSubstituicao .= " and ed322_rechumano in({$iCodigoRecHumano})";

    $sCampos           = " ed58_i_codigo    as sequencial, ";
    $sCampos          .= " ed08_c_descr     as descricao_periodo, ";
    $sCampos          .= " ed58_i_codigo    as codigo_regencia_periodo ";

    $sSqlPeriodosAula = $oDaoRegenciaHorario->sql_query_diario_classe_periodo_substituicao($sCampos, null,
                                                                                           $sWhereRegencia,
                                                                                           $sWhereSubstituicao
                                                                                          );

    $rsPeriodosAula  = $oDaoRegenciaHorario->sql_record($sSqlPeriodosAula);

    $oRetorno->aPeriodosAulaDia  = db_utils::getCollectionByRecord($rsPeriodosAula, false, false, true);
    $aPeriodosAula               = array();
    foreach ($oRetorno->aPeriodosAulaDia as $oPeriodo) {
      $aPeriodosAula[] = $oPeriodo->codigo_regencia_periodo;
    }
    /**
     * Pegamos os dados de aula do aluno
     */
    $rsAlunosMatriculadosNaTurma = $oDaoMatricula->sql_record($sSqlAlunosMatriculadosNaTurma);
    $aAlunosMatriculados         = array();
    for ($iAluno = 0; $iAluno < $oDaoMatricula->numrows; $iAluno++) {

      $oAluno                = db_utils::fieldsMemory($rsAlunosMatriculadosNaTurma, $iAluno, false, false, true);

      $sWhereAcesso           = "ed101_dataleitura = '{$oParam->dtAula}' ";
      $sWhereAcesso          .= " and ed101_entrada is true ";
      $sWhereAcesso          .= " and ed303_aluno = {$oAluno->codigo}";
      $sWhereAcesso          .= " and not exists(select 1 ";
      $sWhereAcesso          .= "                  From controleacessoalunoregistro as x ";
      $sWhereAcesso          .= "                 where (x.ed101_dataleitura = '{$oParam->dtAula}' ";
      $sWhereAcesso          .= "                   and x.ed101_horaleitura > controleacessoalunoregistro.ed101_horaleitura)";
      $sWhereAcesso          .= "                   and ed101_entrada is false ";
      $sWhereAcesso          .= "                   and ed303_aluno = {$oAluno->codigo})";

      $oAluno->acessoescola = ControleAcessoAluno::alunoEstaNaEscola($oAluno->codigo,
                                                                     $oParam->dtAula,
                                                                     $sHoraInicioAula,
                                                                     $sHoraTerminoAula
                                                                    );

      /**
       * Verificamos se existe falta para o Aluno em algum periodo no dia
       */
      $sWhereFaltas = "ed302_regenciahorario in (".implode(",", $aPeriodosAula).") ";
      $sWhereFaltas .= " and ed300_datalancamento = '{$oParam->dtAula}' ";
      $sWhereFaltas .= " and ed301_aluno          = {$oAluno->codigo} ";
      $sSqlAlunoFaltas = $oDaoDiarioClasseAlunoFalta->sql_query_aluno_falta(null,
                                                                             "ed302_regenciahorario as periodo",
                                                                              null,
                                                                              $sWhereFaltas
                                                                             );
      $aFaltas               = array();
      $rsAlunoFaltas         = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlAlunoFaltas);
      $aAlunoFaltas          = db_utils::getCollectionByRecord($rsAlunoFaltas);
      foreach ($aAlunoFaltas as $oFalta) {
        $aFaltas[] = $oFalta->periodo;
      }

      $oAluno->lBloqueioFalta = false;
      $oDataDia               = new DBDate($oParam->dtAula);
      $oDataMatricula         = new DBDate($oAluno->data_matricula);

      if ($oDataMatricula->getTimeStamp() > $oDataDia->getTimeStamp()) {
        $oAluno->lBloqueioFalta = true;
      }
      if (!empty($oAluno->data_saida)) {

        $oDataSaida = new DBDate($oAluno->data_saida);
        if ($oDataSaida->getTimeStamp() <  $oDataDia->getTimeStamp()) {
          $oAluno->lBloqueioFalta = true;
        }
      }
      unset($aAlunoFaltas);
      $oAluno->faltas        = $aFaltas;
      $aAlunosMatriculados[] = $oAluno;
    }
    $oRetorno->aAlunos   = $aAlunosMatriculados;
    $oRetorno->sAulaData = '';
    /**
     * Retornamos os dados do diario de classe
     */
    $sWhereDiarioClasse  = "ed302_regenciahorario in (".implode(",", $aPeriodosAula).") ";
    $sWhereDiarioClasse .= " and ed300_datalancamento = '{$oParam->dtAula}' ";
    $sSqlDiarioClasse    = $oDaoDiarioClasseRegenciaHorario->sql_query(null,
                                                                       "distinct ed300_auladesenvolvida",
                                                                        null,
                                                                        $sWhereDiarioClasse
                                                                      );
    $rsDiarioClasse      = $oDaoDiarioClasseRegenciaHorario->sql_record($sSqlDiarioClasse);
    if ($oDaoDiarioClasseRegenciaHorario->numrows > 0) {
      $oRetorno->sAulaData = urlencode(db_utils::fieldsMemory($rsDiarioClasse, 0)->ed300_auladesenvolvida);
    }
    break;

  case 'salvarDiarioClasse' :

    /**
     * Pesquisamos quais os registros devemos excluir e lan�ar novamente.
     */
    try {


      db_inicio_transacao();
      $oDaoDiarioClasseAlunoFalta      = db_utils::getDao("diarioclassealunofalta");
      $oDaoDiarioClasse                = db_utils::getDao("diarioclasse");
      $oDaoDiarioClasseRegenciaHorario = db_utils::getDao("diarioclasseregenciahorario");
      $oDaoRegenciaHorario             = db_utils::getDao("regenciahorario");
      $sCodigoRegencias                = implode(",", $oParam->aRegencias);

      /**
       * Buscamos a turma pelo codigo das Regencias;
       */
      $sWhereTurma = "ed58_i_codigo in({$sCodigoRegencias})";
      $sSqlTurma   = $oDaoRegenciaHorario->sql_query(null, "distinct ed57_i_codigo, ed58_i_regencia, ed59_i_serie",
                                                     null,
                                                     $sWhereTurma);
      $rsTurma     = $oDaoRegenciaHorario->sql_record($sSqlTurma);
      if ($oDaoRegenciaHorario->numrows != 1) {

        throw new Exception("Turma com regencias configuradas incorretamente!");
      }
      $oDadosRegencia    = db_utils::fieldsMemory($rsTurma, 0);
      $iCodigoDaTurma    = $oDadosRegencia->ed57_i_codigo;
      $oRegencia         = new Regencia($oDadosRegencia->ed58_i_regencia);
      $oTurma            = new Turma($iCodigoDaTurma);
      $oEtapaOrigem      = new Etapa($oDadosRegencia->ed59_i_serie);
      $aPeriodoAvalicao  = $oTurma->getCalendario()->getPeriodoPorData(new DBDate($oParam->dtAula));
      if (count($aPeriodoAvalicao) > 1 || count($aPeriodoAvalicao) == 0) {

        throw new Exception("Existem periodos com datas inconsistentes para o Calendario {$oTurma->getCalendario()->getDescricao()}!");
      }
      $sWhere            = "ed300_datalancamento = '{$oParam->dtAula}' ";
      $sWhere           .= "and ed302_regenciahorario in({$sCodigoRegencias})";
      $sSqlDiarioClasse  = $oDaoDiarioClasse->sql_query_faltas(null,
                                                               "distinct ed300_sequencial,
                                                                ed302_sequencial,
                                                                ed302_regenciahorario
                                                                ",
                                                                null,
                                                                $sWhere
                                                              );
      /**
       * @todo Deletar apenas as faltas dos alunos
       */
       $iCodigoDiario  = '';
       $rsDiarioClasse = $oDaoDiarioClasse->sql_record($sSqlDiarioClasse);
       $aDiarios       = db_utils::getCollectionByRecord($rsDiarioClasse);
       foreach ($aDiarios as $oDiario) {

         $iCodigoDiario                                     = $oDiario->ed300_sequencial;
         $aCodigosRegencia[$oDiario->ed302_regenciahorario] = $oDiario->ed302_sequencial;
       }

      /**
       * Incluimos os dados da Falta
       */

      $oDaoDiarioClasse->ed300_datalancamento   = $oParam->dtAula;
      $oDaoDiarioClasse->ed300_auladesenvolvida = db_stdClass::normalizeStringJson($oParam->sAulaDesenvolvida);
      $oDaoDiarioClasse->ed300_hora             = db_hora();
      $oDaoDiarioClasse->ed300_id_usuario       = db_getsession("DB_id_usuario");
      if ($iCodigoDiario == "") {

        $oDaoDiarioClasse->incluir(null);
      } else {

        $oDaoDiarioClasse->ed300_sequencial = $iCodigoDiario;
        $oDaoDiarioClasse->alterar($iCodigoDiario);
      }
      if ($oDaoDiarioClasse->erro_status == 0) {

        $sMsg  = "Erro ao salvar dados do Di�rio de classe.\n";
        $sMsg .= "Erro Sistema: {$oDaoDiarioClasse->erro_msg}";
        throw new Exception($sMsg);
      }

      /**
       * Persistimos os dados da periodo da aula
       */
      if ($iCodigoDiario == "" ) {

        $aCodigosRegencia = array();
        foreach ($oParam->aRegencias as $iRegencia) {

          $oDaoDiarioClasseRegenciaHorario->ed302_diarioclasse    = $oDaoDiarioClasse->ed300_sequencial;
          $oDaoDiarioClasseRegenciaHorario->ed302_regenciahorario = $iRegencia;
          $oDaoDiarioClasseRegenciaHorario->incluir(null);
          if ($oDaoDiarioClasseRegenciaHorario->erro_status == 0) {

            $sMsg  = "Erro ao salvar dados do Di�rio de classe.\n";
            $sMsg .= "Erro Sistema: {$oDaoDiarioClasseRegenciaHorario->erro_msg}";
            throw new Exception($sMsg);
          }
          $aCodigosRegencia[$iRegencia] = $oDaoDiarioClasseRegenciaHorario->ed302_sequencial;
        }
      }

      /*
       * Persistimos os dados da Falta;
       */
      foreach ($oParam->aAlunos as $oAlunoFalta) {

        /**
         * Verificamos se existe alguma falta lan�ada no dia
         */
        $sWhere          = "ed301_aluno = {$oAlunoFalta->iCodigo}";
        $sWhere         .= " and ed300_datalancamento = '{$oParam->dtAula}'";
        $sWhere         .= " and ed302_regenciahorario in(".implode(",", $oParam->aRegencias).")";
        $sSqlFaltasNoDia = $oDaoDiarioClasseAlunoFalta->sql_query_aluno_falta(null,
                                                                              "ed58_i_codigo,
                                                                              ed301_sequencial",
                                                                              null,
                                                                              $sWhere
                                                                              );

        $rsFaltasNoDia  = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlFaltasNoDia);
        $aFaltasNoDia   = db_utils::getCollectionByRecord($rsFaltasNoDia);
        foreach ($aFaltasNoDia as $oFalta) {

          /**
           * Verificamos se a falta existe no dia.
           */
          if (in_array($oFalta->ed58_i_codigo, $oAlunoFalta->faltas)) {

            /**
             * falta j� lan�ada, n�o incluimos novamente
             */
            array_splice($oAlunoFalta->faltas, array_search($oFalta->ed58_i_codigo, $oAlunoFalta->faltas), 1);
          } else {

            /**
             * a falta foi dada como presenca.devemos excluir a mesma
             */
            $oDaoOcorrenciaFalta = db_utils::getDao("ocorrenciafalta");
            $oDaoOcorrenciaFalta->excluir(null, "ed104_diarioclassealunofalta = {$oFalta->ed301_sequencial}");
            if ($oDaoOcorrenciaFalta->erro_status == 0) {

              $sMensagemErro   = "Falta n�o exclu�da.\\n ";
              $sMensagemErro  .= "Erro T�cnico : {$oDaoOcorrenciaFalta->erro_msg}";
              throw new BusinessException($sMensagemErro);
            }

            $oDaoDiarioClasseAlunoFalta->excluir($oFalta->ed301_sequencial);
            if ($oDaoDiarioClasse->erro_status == 0) {

              $sMensagemErro   = "Erro ao excluir faltas do aluno.\\n ";
              $sMensagemErro  .= "Erro T�cnico : {$oDaoDiarioClasse->erro_msg}";
              throw new BusinessException($sMensagemErro);
            }
          }
        }

        foreach ($oAlunoFalta->faltas as $iPeriodoFalta) {

          $oDaoDiarioClasseAlunoFalta->ed301_aluno                       = $oAlunoFalta->iCodigo;
          $oDaoDiarioClasseAlunoFalta->ed301_diarioclasseregenciahorario = $aCodigosRegencia[$iPeriodoFalta];
          $oDaoDiarioClasseAlunoFalta->incluir(null);
          if ($oDaoDiarioClasseAlunoFalta->erro_status == 0) {

            $sMsg  = "Erro ao salvar dados do aluno.\n";
            $sMsg .= "Erro Sistema: {$oDaoDiarioClasseAlunoFalta->erro_msg}";
            throw new Exception($sMsg);
          }
        }
      }

      /**
       * Atualizamos o total de faltas no periodo
       */
      $aMatriculas            = $oTurma->getAlunosMatriculadosNaturmaPorSerie($oEtapaOrigem);
      $oPeriodoAvaliacaoNoDia = $aPeriodoAvalicao[0];
      foreach ($aMatriculas as $oMatricula) {

        $oDiarioClasse  = $oMatricula->getDiarioDeClasse();
        $oDisciplina    = $oDiarioClasse->getDisciplinasPorRegencia($oRegencia);
        $iTotalDeFaltas = $oDisciplina->getTotalDeFaltasPorPeriodoDeAula($oPeriodoAvaliacaoNoDia->getPeriodoAvaliacao());
        foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {
          if (!$oAvaliacao->getElementoAvaliacao()->isResultado()) {

            $oPeriodoAvaliacao = $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao();
            if ($oPeriodoAvaliacao->getCodigo() == $oPeriodoAvaliacaoNoDia->getPeriodoAvaliacao()->getCodigo()) {

              $oAvaliacao->setNumeroFaltas($iTotalDeFaltas);
              $oDisciplina->salvar();
              unset($oAvaliacao);
              unset($oPeriodoAvaliacao);
              break;
            }
          }
        }

        unset($oDisciplina);
        unset($oDiarioClasse);
        unset($oMatricula);
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = $eErro->getMessage();
    }
    break;
}
echo $oJson->encode($oRetorno);