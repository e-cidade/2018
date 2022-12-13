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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once("libs/db_app.utils.php");
require_once("libs/smtp.class.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

include("libs/JSON.php");

db_app::import("educacao.*");
db_app::import("educacao.ocorrencia.*");
db_app::import("configuracao.notificacao.*");
db_app::import("exceptions.*");
db_app::import("webservices.ControleAcessoAluno");
db_app::import("educacao.avaliacao.*");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {
  
  case 'getControleAcessoFrequencia':

    $sDataDia = date("Y-m-d", db_getsession("DB_datausu"));
    if ($oParam->datadia != "") {
      $sDataDia = implode("-", array_reverse(explode("/", $oParam->datadia)));
    }
    $oRetorno->datadia       = db_formatar($sDataDia, "d");
    $oDaoRegenciaHorario     = db_utils::getDao("regenciahorario");
    $iEscola                 = db_getsession("DB_coddepto");
    $iAno                    = db_getsession("DB_anousu");
    $sCampos                 = "ed57_i_codigo,  ed57_c_descr,";
    if ($oParam->mostrar == 'turma') {
      
      $sCampos .= " case when cgmpessoal.z01_numcgm is not null then cgmpessoal.z01_nome ";
      $sCampos .= " else cgm.z01_nome end  as professor, ";
      $sCampos .= " ed232_c_descr";
      
      $sGroupBy         = " group by ed57_i_codigo,  ed57_c_descr,ed232_c_descr, ";
      $sGroupBy        .= "  cgm.z01_nome,cgmpessoal.z01_numcgm,cgmpessoal.z01_nome";
      $sMetodoExecutar  = "sql_query_diario_classe_periodo"; 
    } else if ($oParam->mostrar == 'aluno') {
      
      $sCampos .= "ed47_v_nome  as aluno, ";
      $sCampos .= "ed47_c_nomeresp as resplegal, ";
      $sCampos .= "ed47_i_codigo, ";
      $sCampos .= "ed47_v_mae as mae, ";
      $sCampos .= "ed47_v_pai as pai, ";
      $sCampos .= "ed47_v_telcel as celular, ";
      $sCampos .= "ed47_v_telef as telefoneresidencial";

      $sGroupBy  = " group by ed47_v_nome, ";
      $sGroupBy .= "          ed47_c_nomeresp, ";
      $sGroupBy .= "          ed47_i_codigo, ";
      $sGroupBy .= "          ed47_v_mae, ";
      $sGroupBy .= "          ed47_v_pai, ";
      $sGroupBy .= "          ed47_v_telcel, ";
      $sGroupBy .= "          ed47_v_telef,";
      $sGroupBy .= "          ed57_i_codigo,";
      $sGroupBy .= "          ed57_c_descr";
      
      $sMetodoExecutar  = "sql_query_diario_classe_matricula";
    }
    $sWhere                  = "ed58_i_diasemana  = extract (dow from cast('{$sDataDia}' as date))+1 and ed58_ativo is true  ";
    $sWhere                 .= "and ed57_i_escola = {$iEscola} and ed52_i_ano = {$iAno} and ed58_ativo is true  ";
    if ($oParam->mostrar == 'aluno') {
      
      $sListaTurma  = implode(",", $oParam->turmas); 
      $sWhere      .= " and ed57_i_codigo in ({$sListaTurma})";  
    }
    $sCamposPadrao           = " ,array_to_string(array_accum(ed58_i_codigo),',') as horarios";
    $sSqlTurma               = $oDaoRegenciaHorario->$sMetodoExecutar(null, 
                                                                      $sCampos.$sCamposPadrao, 
                                                                      "ed57_c_descr", 
                                                                       $sWhere.$sGroupBy
                                                                      );
                                                                                     
    $aTurmas            = array();                                                                                     
    $rsTurmasNoDia      = $oDaoRegenciaHorario->sql_record($sSqlTurma);
    $iTotalLinhas       = $oDaoRegenciaHorario->numrows;
    $oDaoControleAcesso = db_utils::getDao("controleacessoalunoregistrovalido");
    for ($iTurma = 0; $iTurma < $iTotalLinhas; $iTurma++) {

      $oTurma        = db_utils::fieldsMemory($rsTurmasNoDia, $iTurma, false, false, true);
      $oTurma->comLeiuturaFalta    = "0";
      $oTurma->semLeiuturaPresente = "0";
      $oTurma->chamadafechada      = false;
      $oTurma->horarios            = urldecode($oTurma->horarios);
      $sWhereFaltas  =  "ed101_dataleitura = cast('{$sDataDia}' as date) ";
      $sWhereFaltas .=  " and ed60_i_turma = {$oTurma->ed57_i_codigo} "; 
      if ($oParam->mostrar == 'aluno') {
        $sWhereFaltas .= " and ed60_i_aluno = {$oTurma->ed47_i_codigo} ";      
      }
      $sWhereFaltas .=  " and exists (select 1 ";
      $sWhereFaltas .=  "              from diarioclassealunofalta ";
      $sWhereFaltas .=  "                   inner join diarioclasseregenciahorario on ed301_diarioclasseregenciahorario  = ed302_sequencial ";
      $sWhereFaltas .=  "                   inner join diarioclasse on ed302_diarioclasse = ed300_sequencial ";
      $sWhereFaltas .=  "             where ed300_datalancamento  = cast('{$sDataDia}' as date) ";
      $sWhereFaltas .=  "               and ed302_regenciahorario in({$oTurma->horarios}) ";
      $sWhereFaltas .=  "               and ed301_aluno = ed303_aluno)";
                       
      $sSqlTotalLeituraeFalta = $oDaoControleAcesso->sql_query_controle_acesso(null, 
                                                                               "count(*) as total", 
                                                                               null, 
                                                                               $sWhereFaltas);
                                                                               
      $rsTotalLeituraeFalta   = $oDaoControleAcesso->sql_record($sSqlTotalLeituraeFalta);
      if ($oDaoControleAcesso->numrows > 0) {
        $oTurma->comLeiuturaFalta  = db_utils::fieldsMemory($rsTotalLeituraeFalta, 0)->total;
      }
      
      /**
       * Calculamos o total de alunos que estao presentes na turma
       */
      $sWherePresentes  = " ed58_i_codigo in({$oTurma->horarios})";
      if ($oParam->mostrar == 'aluno') {
        $sWherePresentes .= " and ed60_i_aluno = {$oTurma->ed47_i_codigo}";      
      }
      $sWherePresentes .= " and  not exists (select 1  ";
      $sWherePresentes .= "                   from  controleacessoalunoregistrovalido ";
      $sWherePresentes .= "                      inner join controleacessoalunoregistro on ed303_controleacessoalunoregistro = ed101_sequencial ";
      $sWherePresentes .= "                where ed101_dataleitura = cast('{$sDataDia}' as date)  ";
      $sWherePresentes .= "                  and ed303_aluno = ed60_i_aluno)";
      
      $sWherePresentes .= "and ed58_ativo is true  and not exists "; 
      $sWherePresentes .= "   (select 1 ";
      $sWherePresentes .= "      from diarioclassealunofalta ";
      $sWherePresentes .= "           inner join diarioclasseregenciahorario on ed301_diarioclasseregenciahorario  = ed302_sequencial ";
      $sWherePresentes .= "           inner join diarioclasse on ed302_diarioclasse = ed300_sequencial ";
      $sWherePresentes .= "     where ed300_datalancamento  = cast('{$sDataDia}' as date) ";
      $sWherePresentes .= "       and ed302_regenciahorario in({$oTurma->horarios}) ";
      $sWherePresentes .= "       and ed301_aluno           = ed60_i_aluno) ";
      $sSqlQueryPresentesSemLeitura = $oDaoRegenciaHorario->sql_query_regencia_horario_matricula(null, 
                                                                                                 "count(distinct ed60_matricula) as total", 
                                                                                                 null, 
                                                                                                 $sWherePresentes
                                                                                                 );
      $rsTotalPresentesSemLeitura = $oDaoRegenciaHorario->sql_record($sSqlQueryPresentesSemLeitura);                                                                                                 
      if ($oDaoRegenciaHorario->numrows > 0) {
        $oTurma->semLeiuturaPresente = db_utils::fieldsMemory($rsTotalPresentesSemLeitura, 0)->total;
      }
      
      /**
       * Verificamos se a chamada está fechada
       */
      $oDaoDiarioclasseHorario = db_utils::getDao("diarioclasseregenciahorario");
      $sWhereChamada           = " ed300_datalancamento = cast('{$sDataDia}' as date) ";
      $sWhereChamada          .= " and ed302_regenciahorario in({$oTurma->horarios}) ";
      $sSqlChamadaFechada      = $oDaoDiarioclasseHorario->sql_query_diario_classe(null, "1", null, $sWhereChamada);
      $rsChamadaFechada        = $oDaoDiarioclasseHorario->sql_record($sSqlChamadaFechada);
      if ($oDaoDiarioclasseHorario->numrows > 0) {
        $oTurma->chamadafechada = true;
      }
      if (!$oTurma->chamadafechada) {
        $oTurma->semLeiuturaPresente = '0';
      }
      
      unset($oTurma->horarios);
      $aTurmas[] = $oTurma;
    }
    $oRetorno->linhas = $aTurmas;
    Break;
  
  case 'getTurmas':
    
    $oRetorno->aTurmas = array();
    $oDaoTurma         = db_utils::getDao("turma");
    $sWhereTurma       = "     turma.ed57_i_escola   = {$iEscola} ";
    $sWhereTurma      .= " and calendario.ed52_i_ano = ".db_getsession("DB_anousu");
    $sCamposTurma      = " ed57_i_codigo, ed57_c_descr";
    $sOrderTurma       = " ed57_i_codigo";
    $sSqlTurma         = $oDaoTurma->sql_query(null, $sCamposTurma, $sOrderTurma, $sWhereTurma);
    $rsTurma           = $oDaoTurma->sql_record($sSqlTurma);
    $iTotalTurma       = $oDaoTurma->numrows;
    
    if ($iTotalTurma > 0) {
      
      for ($iContadorTurma = 0; $iContadorTurma < $iTotalTurma; $iContadorTurma++) {
        
        $oDadosTurma          = db_utils::fieldsMemory($rsTurma, $iContadorTurma);
        $oTurma               = new stdClass();
        $oTurma->iCodigoTurma = $oDadosTurma->ed57_i_codigo;
        $oTurma->sDescricao   = urlencode($oDadosTurma->ed57_c_descr);
        $oRetorno->aTurmas[]  = $oTurma;
      }
    }
    
    break;
    
  case 'getAlunos':
    
    $sDataDia = date("Y-m-d", db_getsession("DB_datausu"));
    if ($oParam->dataDia != "") {
      $sDataDia = implode("-", array_reverse(explode("/", $oParam->dataDia)));
    }
    $oRetorno->dataDia = db_formatar($sDataDia, "d");
    
    $oRetorno->aAlunos          = array();
    $oDaoMatricula              = db_utils::getDao("matricula");
    $oDaoDiarioClasseAlunoFalta = db_utils::getDao("diarioclassealunofalta");
    $oDadosConfiguracao         = ControleAcessoAluno::getMensagemNotificacao();
    /**
     * Buscamos pelos alunos da escola, no calendario atual e que estejam com situacao 'MATRICULADO' 
     */
    $sWhere                     = "     turma.ed57_i_escola   = {$iEscola} ";
    $sWhere                    .= " and ed60_c_situacao       = 'MATRICULADO' ";
    $sWhere                    .= " and calendario.ed52_i_ano = ".db_getsession("DB_anousu");
    if ($oParam->iTurma != 0) {
      $sWhere .= " and turma.ed57_i_codigo = {$oParam->iTurma} ";
    }
    $sCamposMatricula           = " ed60_i_codigo, ed60_matricula";
    $sOrderMatricula            = " ed60_matricula";
    $sSqlMatricula              = $oDaoMatricula->sql_query(null, $sCamposMatricula, $sOrderMatricula, $sWhere);
    $rsMatricula                = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalMatricula            = $oDaoMatricula->numrows;
    
    if ($iTotalMatricula > 0) {
      
      for ($iContadorMatricula = 0; $iContadorMatricula < $iTotalMatricula; $iContadorMatricula++) {
        
        $oDadosMatricula         = db_utils::fieldsMemory($rsMatricula, $iContadorMatricula);
        
        $oDadosAluno             = new stdClass();
        $oDadosAluno->lAmarelo   = false;
        $oMatricula              = new Matricula($oDadosMatricula->ed60_i_codigo);
        $aTags                   = array("#aluno#", "#datafalta#");
        $aPartesNomeAluno        = explode(" ", trim($oMatricula->getAluno()->getNome())); 
        $aValores                = array($aPartesNomeAluno[0] , $oRetorno->dataDia); 
        
        /**
         * Verificamos se o aluno possui leitura no RFID na data
         */
        $lTemLeitura = ControleAcessoAluno::alunosTemLeituraNaData($oMatricula->getAluno(), 
                                                                               new DBDate($sDataDia));
        $oDadosAluno->iMatricula        = $oMatricula->getCodigo();
        $oDadosAluno->iCodigoAluno      = $oMatricula->getAluno()->getCodigoAluno();
        $oDadosAluno->sDescricao        = urlencode($oMatricula->getAluno()->getNome());
        $oDadosAluno->sTurma            = urlencode($oMatricula->getTurma()->getDescricao());
        $oDadosAluno->sSala             = urlencode($oMatricula->getTurma()->getSala()->getDescricao());
        $oDadosAluno->dtPesquisa        = $sDataDia;
        $oDadosAluno->sMensagemAmarela  = urlencode(str_replace($aTags, $aValores, $oDadosConfiguracao->sMensagemAmarela));
        $oDadosAluno->sMensagemVermelha = urlencode(str_replace($aTags, $aValores, $oDadosConfiguracao->sMensagemVermelha));
        
        /**
         * Verificamos se o aluno possui falta na data
         */
        $sWhereFaltas     = "     ed300_datalancamento = '{$sDataDia}'";
        $sWhereFaltas    .= " and ed301_aluno          = {$oMatricula->getAluno()->getCodigoAluno()} ";
        $sSqlAlunoFaltas  = $oDaoDiarioClasseAlunoFalta->sql_query_aluno_falta(null,
                                                                               "count (*) as periodo",
                                                                               null,
                                                                               $sWhereFaltas
                                                                              );
                                                                              
        $rsAlunoFaltas = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlAlunoFaltas);
        $iTotalFaltas  = db_utils::fieldsMemory($rsAlunoFaltas, 0)->periodo;
        if ($iTotalFaltas > 0 || !$lTemLeitura) {
          
          if ($lTemLeitura) {
            
            $oDadosAluno->lAmarelo = true;
          }
          
          if ($iTotalFaltas == 0) {
            continue;
          }
          
          /**
           * Verificamos os filtros que foi selecionado
           */
          if ($oParam->iSituacao == 1 && $oDadosAluno->lAmarelo) {
            continue;
          } else if ($oParam->iSituacao == 2 && !$oDadosAluno->lAmarelo) {
            continue;
          }
          $oRetorno->aAlunos[] = $oDadosAluno;
        }
      }
      unset($oDadosAluno);
      unset($oMatricula);
    }
    break;
  
  case 'getPeriodos':
    
    $oDaoMatricula              = db_utils::getDao("matricula");
    $oDaoDiarioClasseAlunoFalta = db_utils::getDao("diarioclassealunofalta");
    $oDaoControleAcesso         = db_utils::getDao("controleacessoaluno");
    $oDaoRegenciaHorario        = db_utils::getDao("regenciahorario");

    $oRetorno->aPeriodos    = array();
    $oRetorno->aPeriodosDia = array();
    
    $sDataDia = date("Y-m-d", db_getsession("DB_datausu"));
    if ($oParam->dtPesquisa != "") {
      $sDataDia = implode("-", array_reverse(explode("/", $oParam->dtPesquisa)));
    }
    
    /**
     * Buscamos os dados do aluno atraves da matriculada passada como parametro
     * Utilizamos o codigo sequencial como base para esta verificacao
     */
    $sWhereMatricula            = " ed60_i_codigo = {$oParam->iMatricula} and ed60_c_ativa = 'S'";
    $sSqlMatricula              = $oDaoMatricula->sql_query(null, 'ed60_i_codigo', null, $sWhereMatricula);
    $rsMatricula                = $oDaoMatricula->sql_record($sSqlMatricula);
    
    if ($oDaoMatricula->numrows > 0) {
      
      $oDadosMatricula  = db_utils::fieldsMemory($rsMatricula, 0);
      $oMatricula       = new Matricula($oDadosMatricula->ed60_i_codigo);
      
      /**
       * Buscamos os periodos da turma no dia 
       */
      $iDiaDaSemana      = date('w', db_strtotime($sDataDia)) + 1;
      $sCampos           = " ed58_i_codigo as sequencial, ";
      $sCampos          .= " ed08_c_descr  as descricao_periodo, ";
      $sCampos          .= " ed232_c_descr as disciplina ";
      $sWherePeriodos    = "     ed57_i_escola    = {$iEscola} ";
      $sWherePeriodos   .= " and ed52_i_ano       = ".db_getsession("DB_anousu");
      $sWherePeriodos   .= " and ed58_i_diasemana = {$iDiaDaSemana}";
      $sWherePeriodos   .= " and ed58_ativo is true  ";
      $sWherePeriodos   .= " and ed59_i_turma     = {$oMatricula->getTurma()->getCodigo()}";
      $sSqlPeriodosAula  = $oDaoRegenciaHorario->sql_query_diario_classe_periodo(null,
                                                                                $sCampos,
                                                                                'ed08_i_sequencia',
                                                                                $sWherePeriodos
                                                                               );
      $rsPeriodosAula = $oDaoRegenciaHorario->sql_record($sSqlPeriodosAula);
      $iTotalPeriodos = $oDaoRegenciaHorario->numrows;
      
      if ($iTotalPeriodos > 0) {
        
        for ($iContadorPeriodos = 0; $iContadorPeriodos < $iTotalPeriodos; $iContadorPeriodos++) {
          
          $oDadosPeriodo                  = db_utils::fieldsMemory($rsPeriodosAula, $iContadorPeriodos);
          $oPeriodoDia                    = new stdClass();
          $oPeriodoDia->lFaltou           = false;
          $oPeriodoDia->iCodigo           = $oDadosPeriodo->sequencial;
          $oPeriodoDia->sDescricao        = urlencode($oDadosPeriodo->descricao_periodo);
          $oPeriodoDia->sDisciplina       = urlencode($oDadosPeriodo->disciplina);
          $oPeriodoDia->iCodigoFalta      = '';
          $oPeriodoDia->iOcorrencia       = '';
          $oPeriodoDia->sMensagemRetorno  = '';
          
          /**
           * Buscamos os periodos que o aluno possui falta no dia
           */
          $sWhereFaltas     = "     ed300_datalancamento = '{$sDataDia}'";
          $sWhereFaltas    .= " and ed301_aluno          = {$oMatricula->getAluno()->getCodigoAluno()} ";
          $sSqlAlunoFaltas  = $oDaoDiarioClasseAlunoFalta->sql_query_falta_notificada(null,
                                                                                 "ed302_regenciahorario as periodo,
                                                                                 ed301_sequencial,
                                                                                 db134_mensagemretorno,
                                                                                 ed103_sequencial",
                                                                                 null,
                                                                                 $sWhereFaltas
                                                                                );
          $rsAlunoFaltas = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlAlunoFaltas);
          $iTotalFaltas  = $oDaoDiarioClasseAlunoFalta->numrows;
          
          if ($iTotalFaltas > 0) {
            
            for ($iContadorFaltas = 0; $iContadorFaltas < $iTotalFaltas; $iContadorFaltas++) {
              
              $oDadosFaltas = db_utils::fieldsMemory($rsAlunoFaltas, $iContadorFaltas);
              if ($oDadosFaltas->periodo == $oDadosPeriodo->sequencial) {
                
                if ($oDadosFaltas->ed103_sequencial != "" && trim($oDadosFaltas->db134_mensagemretorno) == "") {
                  $oDadosFaltas->db134_mensagemretorno = 'Notificação enviada';
                }
                $oPeriodoDia->lFaltou      = true;
                $oPeriodoDia->iCodigoFalta     = $oDadosFaltas->ed301_sequencial;
                $oPeriodoDia->iOcorrencia      = $oDadosFaltas->ed103_sequencial;
                $oPeriodoDia->sMensagemRetorno = urlencode($oDadosFaltas->db134_mensagemretorno);
              }
            }
          }
          $oRetorno->aPeriodosDia[] = $oPeriodoDia;
        }
      }
    }
    break;
    
  case 'enviarNotificacao':
    
    try {
      
      db_inicio_transacao();
      $oDadosEnvio = ControleAcessoAluno::getMensagemNotificacao();
      foreach($oParam->aAlunos as $oAluno) {
        
        /**
         * Cria a ocorrencia da falta
         */
        $oOcorrenciaFalta = new OcorrenciaFalta();
        $oMatriculaAluno  = new Matricula($oAluno->iMatricula);
        $sTelefone        = $oMatriculaAluno->getAluno()->getCelularResponsavel();
        $sEmail           = $oMatriculaAluno->getAluno()->getEmailResponsavel();
        $oOcorrenciaFalta->setMatricula($oMatriculaAluno);
        $oOcorrenciaFalta->setDtOcorrencia(new DBDate(date("Y-m-d", db_getsession("DB_datausu"))));
        $oOcorrenciaFalta->setTexto(db_stdClass::normalizeStringJson($oAluno->sMensagem));
        
        /**
         * Criamos a notificacao para os pais
         */
        $oMensagem = new NotificacaoMensagem();
        $oMensagem->setAssunto("Ocorrencia aluno {$oMatriculaAluno->getAluno()->getNome()}.");
        $oMensagem->setMensagem(db_stdClass::normalizeStringJson($oAluno->sMensagem));
        $oMensagem->setResumo((db_stdClass::normalizeStringJson($oAluno->sMensagem)));
        $oMensagem->setTelefone($sTelefone);
        $oMensagem->setEmailDestino($sEmail);
        $oMensagem->setOperadora($oDadosEnvio->sOperadora);
        
        /**
         * Carrega o email para envio da escola (origem do email a ser enviado)
         */
        $sEmail          = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->email;
        $oDaoEscola      = db_utils::getDao("escola");
        $sSqlEmailEscola = $oDaoEscola->sql_query_file(db_getsession("DB_coddepto"), "ed18_c_email");
        $rsEmailEscola   = $oDaoEscola->sql_record($sSqlEmailEscola);
        $oDadosEscola    = db_utils::fieldsMemory($rsEmailEscola, 0);
        
        if (trim($oDadosEscola->ed18_c_email) != "") {
          
          $oMensagem->setEmailOrigem($oDadosEscola->ed18_c_email);
        }
        
        /**
         * as Notificacoes sao enviadas e anexadas a ocorrencia
         */
        
        /**
         * Marcamos quais faltas foram na ocorrencia
         */
        foreach ($oAluno->aFaltas as $iCodigoFalta) {
          
          $oOcorrenciaFalta->adicionarFalta(new Falta($iCodigoFalta));
        }
        $oOcorrenciaFalta->salvar();
        $aNotificacoes = NotificacaoBuilder::getNotificacoesPorMensagem($oMensagem);
        
        foreach ($aNotificacoes as $oNotificacao) {
          $oOcorrenciaFalta->adicionarNotificacao($oNotificacao);
        }
        $oOcorrenciaFalta->salvar();
      }
      
      db_fim_transacao(false);
    } catch (FileException $eFileException) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eFileException->getMessage()); 
      db_fim_transacao(true);
    } catch (BusinessException $eBusiness) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage()); 
    } catch (ParameterException $eParameter) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eParameter->getMessage());
    } catch (Exception $eParameter) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eParameter->getMessage());
    }
    
    break;
  
  case 'getFaltasAluno':
    
    $oRetorno->aFaltas              = array();
    $aFaltasAluno                   = array();
    $oDaoDiarioClasseAlunoFalta     = db_utils::getDao("diarioclassealunofalta");
    $sCamposDiarioClasseAlunoFalta  = " count(*) as total_faltas, ed300_datalancamento";
    $sCamposDiarioClasseAlunoFalta .= ", array_to_string(array_accum(distinct ed232_c_descr), ', ') as disciplina";
    $sWhereDiarioClasseAlunoFalta   = " ed301_aluno = {$oParam->iCodigoAluno} group by ed300_datalancamento ";
    $sWhereDiarioClasseAlunoFalta  .= "             order by ed300_datalancamento DESC";
    $sSqlDiarioClasseAlunoFalta     = $oDaoDiarioClasseAlunoFalta->sql_query_falta_regencia(null, 
                                                                                            $sCamposDiarioClasseAlunoFalta,
                                                                                            null,
                                                                                            $sWhereDiarioClasseAlunoFalta
                                                                                           );
    $rsDiarioClasseAlunoFalta      = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlDiarioClasseAlunoFalta);
    $iTotalDiarioClasseAlunoFalta  = $oDaoDiarioClasseAlunoFalta->numrows;
    
    if ($iTotalDiarioClasseAlunoFalta > 0) {
      
      for ($iContadorFalta = 0; $iContadorFalta < $iTotalDiarioClasseAlunoFalta; $iContadorFalta++) {
        
        $oFalta                    = db_utils::fieldsMemory($rsDiarioClasseAlunoFalta, $iContadorFalta);
        $oDadosFalta               = new stdClass();
        $dtFaltaAluno              = new DBDate($oFalta->ed300_datalancamento);
        $oDadosFalta->dtFalta      = $dtFaltaAluno->getDate(DBDate::DATA_PTBR);
        $oDadosFalta->iTotalFaltas = $oFalta->total_faltas;
        $oDadosFalta->sDisciplinas = urlencode($oFalta->disciplina);
        $oRetorno->aFaltas[]       = $oDadosFalta;
      }
      unset($oDadosFalta);
      unset($dtFaltaAluno);
    }
    break;
}
echo $oJson->encode($oRetorno);
?>