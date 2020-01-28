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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define( 'MENSAGENS_CRITERIO_AVALIACAO_RPC', 'educacao.escola.edu4_criterioavaliacao_RPC.' );

try {

  db_inicio_transacao();

  switch ( $oParam->sExecucao ) {

    /**
     * ***************************************************************************************************
     * Retorna as disciplinas e ensinos vinculadas a um critério de avaliação
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     * @return array $oRetorno->aDisciplinas
     *         ..... stdClass
     *         .............. integer iDisciplina        - Código da disciplina
     *         .............. string  sDisciplina        - Nome da disciplina
     *         .............. string  sEnsino            - Nome do ensino que a disciplina está vinculada
     *         .............. string  sAbreviaturaEnsino - Abreviatura do ensino
     * ***************************************************************************************************
     */
    case 'getDisciplinas':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oRetorno->aDisciplinas = array();
      $oCriterioAvaliacao     = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );

      foreach( $oCriterioAvaliacao->getDisciplinas() as $oDisciplina ) {

        $oDadosDisciplinas                     = new stdClass();
        $oDadosDisciplinas->iDisciplina        = $oDisciplina->getCodigoDisciplina();
        $oDadosDisciplinas->sDisciplina        = urlencode( $oDisciplina->getNomeDisciplina() );
        $oDadosDisciplinas->sEnsino            = urlencode( $oDisciplina->getEnsino()->getNome() );
        $oDadosDisciplinas->sAbreviaturaEnsino = urlencode( $oDisciplina->getEnsino()->getAbreviatura() );
        $oRetorno->aDisciplinas[]              = $oDadosDisciplinas;
      }

      break;

    /**
     * ****************************************************************************
     * Retorna os períodos de avaliação vinculados a um critério
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     * @return array $oRetorno->aPeriodosAvaliacao
     *         ..... stdClass
     *         .............. integer iPeriodo     - Código do período
     *         .............. string  sPeriodo     - Descrição do período
     *         .............. string  sAbreviatura - Descrição abreviada do período
     * ****************************************************************************
     */
    case 'getPeriodosAvaliacao':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oRetorno->aPeriodosAvaliacao = array();
      $oCriterioAvaliacao           = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );

      foreach( $oCriterioAvaliacao->getPeriodos() as $oPeriodoAvaliacao ) {

        $oDadosPeriodo                  = new stdClass();
        $oDadosPeriodo->iPeriodo        = $oPeriodoAvaliacao->getCodigo();
        $oDadosPeriodo->sPeriodo        = urlencode( $oPeriodoAvaliacao->getDescricao() );
        $oDadosPeriodo->sAbreviatura    = urlencode( $oPeriodoAvaliacao->getDescricaoAbreviada() );
        $oRetorno->aPeriodosAvaliacao[] = $oDadosPeriodo;
      }

      break;

    /**
     * ***************************************************************************************************************
     * Salvo os dados de um critério de avaliação, seja este novo ou já existente
     * @param integer $oParam->iCriterioAvaliacao ( não obrigatório ) - Código do critério de avaliação, caso exista
     *        string  $oParam->sDescricao   - Descrição do critério de avaliação
     *        string  $oParam->sAbreviatura - Descrição abreviada do critério de avaliação
     *        array   $oParam->aDisciplinas - Código das disciplinas vinculadas ao critério
     *        array   $oParam->aPeriodos    - Código dos períodos vinculados ao critério
     * ***************************************************************************************************************
     */
    case 'salvar':
 
      if ( !isset( $oParam->sDescricao ) || empty( $oParam->sDescricao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'descricao_nao_informada' ) );
      }

      if ( !isset( $oParam->sAbreviatura ) || empty( $oParam->sAbreviatura ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'abreviatura_nao_informada' ) );
      }

      $oCriterioAvaliacao = new CriterioAvaliacao();

      if ( isset( $oParam->iCriterioAvaliacao ) && !empty( $oParam->iCriterioAvaliacao ) ) {
        $oCriterioAvaliacao = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );
      }

      $oCriterioAvaliacao->setEscola( new Escola( db_getsession( "DB_coddepto" ) ) );
      $oCriterioAvaliacao->setDescricao( mb_strtoupper( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDescricao ) ) );
      $oCriterioAvaliacao->setAbreviatura( mb_strtoupper( db_stdClass::normalizeStringJsonEscapeString( $oParam->sAbreviatura ) ) );

      foreach( $oParam->aDisciplinas as $iDisciplina ) {
        $oCriterioAvaliacao->addDisciplinas( DisciplinaRepository::getDisciplinaByCodigo( $iDisciplina ) );
      }

      foreach( $oParam->aPeriodos as $iPeriodo ) {
        $oCriterioAvaliacao->addPeriodos( new PeriodoAvaliacao( $iPeriodo ) );
      }

      $oCriterioAvaliacao->salvar();
      
      $oRetorno->iCriterioAvaliacao = $oCriterioAvaliacao->getCodigo();
      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_salvo' ) );

      break;

    /**
     * ****************************************************************************
     * Exclui um critério de avaliação
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     * ****************************************************************************
     */
    case 'excluir':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oCriterioAvaliacao = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );
      $oCriterioAvaliacao->remover();

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_excluido' ) );

      break;

    /**
     * ***************************************************************************************************
     * Retorna os dados de um critério de avaliação
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     * @return integer iCriterio    - Código do critério de avaliação
     *         string  sDescricao   - Decrição do critério
     *         string  sAbreviatura - Descrição abreviada do critério
     *         array   aDisciplinas - Disciplinas que utilizam o critério
     *         ....... stdClass
     *         .............. integer iDisciplina        - Código da disciplina
     *         .............. string  sDisciplina        - Nome da disciplina
     *         .............. string  sEnsino            - Nome do ensino que a disciplina está vinculada
     *         .............. string  sAbreviaturaEnsino - Abreviatura do ensino
     *         array aPeriodos - Períodos de avaliação que utilizam o critério
     *         ..... stdClass
     *         .............. integer iPeriodo     - Código do período
     *         .............. string  sPeriodo     - Descrição do período
     *         .............. string  sAbreviatura - Descrição abreviada do período
     * ***************************************************************************************************
     */
    case 'getDadosCriterio':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oCriterioAvaliacao        = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );
      $oRetorno->iCriterio       = $oCriterioAvaliacao->getCodigo();
      $oRetorno->sDescricao      = urlencode( $oCriterioAvaliacao->getDescricao() );
      $oRetorno->sAbreviatura    = urlencode( $oCriterioAvaliacao->getAbreviatura() );
      $oRetorno->aDisciplinas    = array();
      $oRetorno->aPeriodos       = array();

      foreach( $oCriterioAvaliacao->getDisciplinas() as $oDisciplina ) {

        $oDadosDisciplinas                     = new stdClass();
        $oDadosDisciplinas->iDisciplina        = $oDisciplina->getCodigoDisciplina();
        $oDadosDisciplinas->sDisciplina        = urlencode( $oDisciplina->getNomeDisciplina() );
        $oDadosDisciplinas->sEnsino            = urlencode( $oDisciplina->getEnsino()->getNome() );
        $oDadosDisciplinas->sAbreviaturaEnsino = urlencode( $oDisciplina->getEnsino()->getAbreviatura() );
        $oDadosDisciplinas->lVinculadaTurma    = false;
        
        $aTurmasVinculadas = $oCriterioAvaliacao->getTurmasVinculadasDisciplina( $oDisciplina );
        
        if ( count( $aTurmasVinculadas ) > 0 ) {
          
          $aTurmas = array();
          foreach ($aTurmasVinculadas as $oTurmaDisciplina) {
            $aTurmas[] = $oTurmaDisciplina->getDescricao();
          }
          $oDadosDisciplinas->lVinculadaTurma   = true;
          $oDadosDisciplinas->sTurmasVinculadas = implode(", ", $aTurmas);
          $oDadosDisciplinas->sTurmasVinculadas = utf8_encode($oDadosDisciplinas->sTurmasVinculadas);
        }
        
        $oRetorno->aDisciplinas[]              = $oDadosDisciplinas;
      }

      foreach( $oCriterioAvaliacao->getPeriodos() as $oPeriodoAvaliacao ) {

        $oDadosPeriodo               = new stdClass();
        $oDadosPeriodo->iPeriodo     = $oPeriodoAvaliacao->getCodigo();
        $oDadosPeriodo->sPeriodo     = urlencode( $oPeriodoAvaliacao->getDescricao() );
        $oDadosPeriodo->sAbreviatura = urlencode( $oPeriodoAvaliacao->getDescricaoAbreviada() );
        $oRetorno->aPeriodos[]       = $oDadosPeriodo;
      }

      break;

    /**
     * *********************************************************************************
     * Vincula uma ou mais turmas a um critério de avaliação
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     *        array   $oParam->aTurmas            - Código das turmas a serem vinculadas
     * *********************************************************************************
     */
    case 'vincularTurmas':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oCriterioAvaliacao = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );

      if ( !isset( $oParam->aTurmas ) || count( $oParam->aTurmas ) == 0 ) {
        $oCriterioAvaliacao->removerVinculosTurmas();
      } else {

        foreach( $oParam->aTurmas as $iTurma ) {
          $oCriterioAvaliacao->addTurma( TurmaRepository::getTurmaByCodigo( $iTurma ) );
        }
        $oCriterioAvaliacao->vincularTurmas();
      }

      $oRetorno->iCriterioAvaliacao = $oParam->iCriterioAvaliacao;
      $oRetorno->sMensagem          = urlencode( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'vinculo_alterado_sucesso' ) );
      break;

    /**
     * ****************************************************************************
     * Retorna um array com as turmas vinculadas a um critério de avaliação
     * @param integer $oParam->iCriterioAvaliacao - Código do critério de avaliação
     * @return array aTurmasVinculadas - Turmas vinculadas ao critério de avaliação
     *         ..... stdClass
     *         .............. integer iTurma - Código da turma
     *         .............. string  sTurma - Nome da turma
     * ****************************************************************************
     */
    case 'getTurmasVinculadas':

      if ( !isset( $oParam->iCriterioAvaliacao ) || empty( $oParam->iCriterioAvaliacao ) ) {
        throw new ParameterException( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'criterio_nao_informado' ) );
      }

      $oRetorno->aTurmasVinculadas = array();
      $oCriterioAvaliacao          = new CriterioAvaliacao( $oParam->iCriterioAvaliacao );

      foreach( $oCriterioAvaliacao->getTurmasVinculadas() as $oTurma ) {

        $oDadosTurma                   = new stdClass();
        $oDadosTurma->iTurma           = $oTurma->getCodigo();
        $oDadosTurma->sTurma           = urlencode( $oTurma->getDescricao() );
        $oRetorno->aTurmasVinculadas[] = $oDadosTurma;
      }

      break;

    /**
     * Busca todos os critérios de avaliação que existem cadastrados
     */
    case 'getCriteriosAvaliacao':

      $oRetorno->aCriterios = array();

      $iEscola = db_getsession( "DB_coddepto" );
      $oDaoCriterio   = new cl_criterioavaliacao();
      $sSqlCriterio   = $oDaoCriterio->sql_query_file ( null, "*", 'ed338_ordem', "ed338_escola = {$iEscola}" );
      $rsCriterio     = $oDaoCriterio->sql_record($sSqlCriterio);
      $iLinhasCritero = $oDaoCriterio->numrows;

      for ( $iContador = 0; $iContador < $iLinhasCritero; $iContador++ ) {

        if ( $rsCriterio && $iLinhasCritero > 0 ) {

          $oCriterio = db_utils::fieldsMemory($rsCriterio, $iContador);
          $oRetorno->aCriterios[] = $oCriterio;
        }
      }

      break;

    /**
     * Reordena os Critérios de Avaliação conforme retornado da tela
     * @param CriterioAvaliacao[] $oParam->aNovoCriterioAvaliacao
     */
    case 'salvarReordenacaoCriterio':

      if (is_array($oParam->aNovoCriterioAvaliacao)) {

        foreach ($oParam->aNovoCriterioAvaliacao as $oCriterioAvalicaoOrdenado) {

          $oCriterioAvaliacao = new CriterioAvaliacao($oCriterioAvalicaoOrdenado->iCodigo);
          $oCriterioAvaliacao->setOrdem($oCriterioAvalicaoOrdenado->iOrdem);
          $oCriterioAvaliacao->getDisciplinas();
          $oCriterioAvaliacao->getPeriodos();
          $oCriterioAvaliacao->getTurmasVinculadas();
          $oCriterioAvaliacao->salvar();
        }
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_CRITERIO_AVALIACAO_RPC . 'reordenado_criterio_sucesso' ));
      break;
  }

  db_fim_transacao();
} catch ( Exception $eException ) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $eException->getMessage() );
  db_fim_transacao( true );
}
echo $oJson->encode( $oRetorno );