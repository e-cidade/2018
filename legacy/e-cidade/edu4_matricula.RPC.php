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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));


define("URL_MENSAGEM_MATRICULA_RPC", "educacao.escola.edu4_matricula_RPC.");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Retorna um array com os dados de turmaturnoreferente de cada turno que a matrícula está vinculada.
     * Deve ser informado o Código do Aluno ou o Código da Matrícula
     * @param integer $iAluno
     * @param integer $iMatricula
     * @return array
     */
    case "getTurnoVinculado":

      if ( isset( $oParam->iAluno ) ) {

        $oAluno     = AlunoRepository::getAlunoByCodigo( $oParam->iAluno );
        $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno( $oAluno );

        $oRetorno->aTurnos = $oMatricula->getTurnosVinculados();
      }

      if ( isset( $oParam->iMatricula ) ) {

        $oMatricula        = MatriculaRepository::getMatriculaByCodigo( $oParam->iMatricula );
        $oRetorno->aTurnos = $oMatricula->getTurnosVinculados();
      }

      break;

    /**
     * Remove vinculo existente com matriculaturnoreferente e salva novo vínculo.
     * Adiciona na tabela matriculamov o novo status de modificação contendo a mensagem de qual turno a matrícula
     * pertencia e para qual turno ela foi alterada.
     * @param integer $iAluno
     * @param integer $iMatricula
     * @param array   $aTurmaTurnoReferente
     */
    case "alterarTurnoMatricula":

      $oMatricula = null;
      $aMapaTurnoReferente = array(1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE');

      if ( isset( $oParam->iAluno ) ) {

        $oAluno     = AlunoRepository::getAlunoByCodigo( $oParam->iAluno );
        $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno( $oAluno );
      }

      if ( isset( $oParam->iMatricula ) ) {
        $oMatricula = MatriculaRepository::getMatriculaByCodigo( $oParam->iMatricula );
      }

      $aTurnoReferenteAntigo = array();
      $aTurnoReferenteNovo   = array();

      foreach ($oMatricula->getTurnosVinculados() as $oTurmaTurnoReferenteAntigo ) {
        $aTurnoReferenteAntigo[] = $aMapaTurnoReferente[$oTurmaTurnoReferenteAntigo->ed336_turnoreferente];
      }

      // $sTurnosReferentes = implode(", ", $oParam->aTurmaTurnoReferente);
      $sWhere  = "     ed336_turma = {$oMatricula->getTurma()->getCodigo()} ";
      $sWhere .= " and ed336_turnoreferente in (" . implode(", ", $oParam->aTurmaTurnoReferente) . ")";
      $sCampos = " ed336_codigo, ed336_turnoreferente ";

      $oDaoTurmaTurnoReferente = new cl_turmaturnoreferente();
      $sSqlTurmaTurnoReferente = $oDaoTurmaTurnoReferente->sql_query_file(null, $sCampos, null, $sWhere);
      $rsTurmaTurnoReferente   = db_query($sSqlTurmaTurnoReferente);

      $iLinhas = 0;
      if ($rsTurmaTurnoReferente && pg_num_rows($rsTurmaTurnoReferente) > 0) {
        $iLinhas = pg_num_rows($rsTurmaTurnoReferente);
      }

      $sWhereExcluiVinculos        = " ed337_matricula = ". $oMatricula->getCodigo();
      $oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
      if ( $oDaoMatriculaTurnoReferente->excluir( null, $sWhereExcluiVinculos ) ) {

        for ($i = 0; $i  < $iLinhas; $i++) {

          $oDadosTurmaTurnoReferente = db_utils::fieldsMemory($rsTurmaTurnoReferente, $i);

          $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $oDadosTurmaTurnoReferente->ed336_codigo;
          $oDaoMatriculaTurnoReferente->ed337_matricula           = $oMatricula->getCodigo();
          $oDaoMatriculaTurnoReferente->incluir( null );

          $aTurnoReferenteNovo[] = $aMapaTurnoReferente[$oDadosTurmaTurnoReferente->ed336_turnoreferente];
        }

      }

      $sTipoProcedimento  = "TROCA TURNO ED. INFANTIL";
      $sTipoMovimentacao  = "Turno referente alterado de: ". implode("/", $aTurnoReferenteAntigo);
      $sTipoMovimentacao .= " para " . implode("/", $aTurnoReferenteNovo);
      $oData              =  new DBDate( date('Y-m-d', db_getsession("DB_datausu") ) );
      $oMatricula->atualizarMovimentacao($sTipoMovimentacao, $sTipoProcedimento, $oData);

      $oRetorno->sMessage = "Turno(s) alterado(s) com sucesso!";
      break;

    case 'permitirProporcionalidadeAluno':

      $oMatricula    = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
      $oDiarioClasse = $oMatricula->getDiarioDeClasse();
      $aDiarios      = array();

      $iAno = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();

      /**
       * Percorre os diarios do aluno e valida se possui direito a proporcionalidade
       */
      foreach ($oDiarioClasse->getDisciplinas() as $oDiarioAvaliacaoDisciplina) {


        $lTemDireitoProporcionalidade = false;

        foreach ($oDiarioAvaliacaoDisciplina->getPeriodosAvaliacao() as $oElementoAvaliacao) {

          if (    $oElementoAvaliacao instanceof ResultadoAvaliacao
               && $oElementoAvaliacao->getFormaDeObtencao() == 'SO'
               && $oElementoAvaliacao->utilizaProporcionalidade()) {
            $lTemDireitoProporcionalidade = true;
          }
        }

        if ($lTemDireitoProporcionalidade) {
          $aDiarios[] = $oDiarioAvaliacaoDisciplina->getCodigoDiario();
        }
      }

      /**
       * Se tem direito a proporcionalidade, vincula o diario para os elementos de avaliacao
       */
      if ( count($aDiarios) > 0) {

        $oDaoDiarioRegra       = new cl_diarioregracalculo();
        $sWhereExcluiVinculos  = "ed125_diario in (". implode(", ", $aDiarios) . ")" ;
        $sWhereExcluiVinculos .= " and ed125_regracalculo = 1 ";
        $oDaoDiarioRegra->excluir(null, $sWhereExcluiVinculos);

        foreach ($aDiarios as $iDiario) {

          foreach ($oParam->aOrdemPeriodos as $iOrdemPeriodo) {

            $oDaoDiarioRegra->ed125_codigo       = null;
            $oDaoDiarioRegra->ed125_ordemperiodo = $iOrdemPeriodo;
            $oDaoDiarioRegra->ed125_diario       = $iDiario;
            $oDaoDiarioRegra->ed125_regracalculo = 1; // CALCULAR PROPORCIONALIDADE
            $oDaoDiarioRegra->incluir(null);

            if ($oDaoDiarioRegra->erro_status == 0) {
              throw new DBException( _M(URL_MENSAGEM_MATRICULA_RPC . "erro_vincular_proporcionalidade") );
            }
          }
        }
      }

      /**
       * Limpa os períodos onde aplica a proporcionalidade e os carrega novamente, garantindo que os dados em sessão estão atualizados
       */
      foreach ($oDiarioClasse->getDisciplinas() as $oDiarioAvaliacaoDisciplina) {

        $oDiarioAvaliacaoDisciplina->limpaPeriodosAplicaProporcionalidade();
        $oDiarioAvaliacaoDisciplina->getOrdemPeriodosAplicaProporcionalidade();

        // //recalcula os resultados
        foreach ($oDiarioAvaliacaoDisciplina->getResultados() as $oResultado) {
          $oResultado->setValorAproveitamento($oResultado->getElementoAvaliacao()->getResultado( $oDiarioAvaliacaoDisciplina->getAvaliacoes(), true, $iAno) );
        }
      }

      EducacaoSessionManager::registrarTurma($oMatricula->getTurma());
      $oRetorno->sMessage = urlencode( _M( URL_MENSAGEM_MATRICULA_RPC . "periodos_salvos" ) );

      break;

    case 'periodosProporcionalidadeAluno':

      if ( !isset($oParam->iMatricula) || empty($oParam->iMatricula) ) {
        throw new ParameterException( _M( URL_MENSAGEM_TURMA_RPC . 'matricula_nao_informada' ) );
      }

      $oMatricula                = MatriculaRepository::getMatriculaByCodigo( $oParam->iMatricula );
      $oRetorno->aOrdensPeriodos = $oMatricula->getDiarioDeClasse()->periodosCalculoResultadoFinal();

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
