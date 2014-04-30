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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("libs/exceptions/DBException.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

	switch($oParam->exec) {
		
		/**
		 * Retorna os alunos que possuem falta dentro de um periodo, em uma disciplina
		 * @param integer $oParam->iTurma
		 * @param integer $oParam->iEtapa
		 * @param integer $oParam->iRegencia
		 * @param integer $oParam->iPeriodo
		 * @return array  $oRetorno->aAlunos
		 */
		case 'getAlunosComFaltaNoPeriodo':
			
			if (isset($oParam->iTurma) && isset($oParam->iEtapa)) {

				$iDiarioAvaliacao    = null;
				$oRetorno->aAlunos   = array();
				$oTurma              = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
				$oEtapa              = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
				$aAlunosMatriculados = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
				
				db_inicio_transacao();

				foreach ($aAlunosMatriculados as $oMatricula) {
     
				  if ($oMatricula->getSituacao() != "MATRICULADO" || !$oMatricula->isAtiva() || $oMatricula->isConcluida()) {
				    continue;
				  }
				   
					$oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()
					                               ->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia));
					
					foreach ($oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento) {
						
						if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica && 
								 $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() ==  $oParam->iAvaliacao &&
								 !$oAvaliacaoAproveitamento->isAmparado()
							 ) {
							
							$oPeriodoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getPeriodoAvaliacao();
							$iFaltasPeriodo    = $oDiarioAvaliacao->getTotalFaltasPorPeriodo($oPeriodoAvaliacao);
							
							if ($iFaltasPeriodo > 0) {
								
								$iDiarioAvaliacao = $oAvaliacaoAproveitamento->getCodigo();
								$iFaltasAbonadas  = $oAvaliacaoAproveitamento->getFaltasAbonadas();
								
								$oDadosAluno                          = new stdClass();
								$oDadosAluno->iMatricula              = $oMatricula->getCodigo();
								$oDadosAluno->sNome                   = urlencode($oMatricula->getAluno()->getNome());
								$oDadosAluno->iDiarioAvaliacao        = $iDiarioAvaliacao;
								$oDadosAluno->iNumeroFaltas           = $iFaltasPeriodo;
								$oDadosAluno->iFaltasAbonadas         = $iFaltasAbonadas;
								$oDadosAluno->iJustificativa          = '';
								$oDadosAluno->sDescricaoJustificativa = '';
								
								if ( !empty($iFaltasAbonadas) ) {
									
									$oDadosAbonoFalta                     = $oAvaliacaoAproveitamento->getAbono();
									$oJustificativa                       = new Justificativa($oDadosAbonoFalta->iJustificativa);
									$oDadosAluno->iJustificativa          = $oJustificativa->getCodigo();
									$oDadosAluno->sDescricaoJustificativa = urlencode($oJustificativa->getDescricao());
								}
								
								$oRetorno->aAlunos[] = $oDadosAluno;
							}
						}
					}
				}
				
				db_fim_transacao();
				TurmaRepository::removerTurma($oTurma);
			}
			break;
			
	  /**
	   * Salvamos o abono de faltas do aluno na disciplina e periodo selecionados
	   * @param integer $oParam->iDiarioAvaliacao
	   * @param integer $oParam->iMatricula
		 * @param integer $oParam->iRegencia
		 * @param integer $oParam->iPeriodo
		 * @param integer $oParam->iJustificativa
		 * @param integer $oParam->iFaltasAbonadas
	   */
		case 'salvarAbonoFalta':
			
			if (isset($oParam->iDiarioAvaliacao)) {
				
				db_inicio_transacao();
				
				$oMatricula             = MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula);
				$oRegencia              = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
				$oElemento              = null;
				$oJustificativa         = new Justificativa($oParam->iJustificativa);
				$oDiarioAvaliacaoSalvar = null;
				
				foreach( $oMatricula->getDiarioDeClasse()->getDisciplinas() as $oDiarioAvaliacao ) {
					
					if( $oDiarioAvaliacao->getRegencia()->getCodigo() == $oParam->iRegencia ) {
						
						foreach( $oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento ) {
							
							if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica &&
									 $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() == $oParam->iAvaliacao
							   ) {
							  
								$oElemento              = $oAvaliacaoAproveitamento->getElementoAvaliacao();
								$oDiarioAvaliacaoSalvar = $oDiarioAvaliacao;
							}
						}
					}
				}
				
				$oAproveitamento = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegenciaPeriodo($oRegencia, $oElemento);
				$oAproveitamento->salvarAbono($oJustificativa, $oParam->iDiarioAvaliacao, $oParam->iFaltasAbonadas);
				$oDiarioAvaliacaoSalvar->salvar();
				
				MatriculaRepository::removerMatricula($oMatricula);
				RegenciaRepository::removerRegencia($oRegencia);
				unset($oPeriodo);
				unset($oJustificativa);
				
				db_fim_transacao();
			}
			break;
			
		/**
		 * Excluimos um abono de falta de uma aluno na disciplina e periodos selecionados
		 * @param integer $oParam->iDiarioAvaliacao
	   * @param integer $oParam->iMatricula
		 * @param integer $oParam->iRegencia
		 * @param integer $oParam->iPeriodo
		 */
		case 'excluirAbonoFalta':
			
			if (isset($oParam->iDiarioAvaliacao)) {
				
				db_inicio_transacao();
				
				$oMatricula             = MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula);
				$oRegencia              = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
				$oElemento              = null;
				$oDiarioAvaliacaoSalvar = null;
				
				foreach ( $oMatricula->getDiarioDeClasse()->getDisciplinas() as $oDiarioAvaliacao ) {
						
					if ( $oDiarioAvaliacao->getRegencia()->getCodigo() == $oParam->iRegencia ) {
				
						foreach ( $oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento ) {
								
							if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica &&
								   $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() == $oParam->iAvaliacao
							   ) {
							  
								$oElemento              = $oAvaliacaoAproveitamento->getElementoAvaliacao();
								$oDiarioAvaliacaoSalvar = $oDiarioAvaliacao;
							}
						}
					}
				}
				
				$oAproveitamento = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegenciaPeriodo($oRegencia, $oElemento);
				$oAproveitamento->excluirAbono($oParam->iDiarioAvaliacao);
				$oDiarioAvaliacaoSalvar->salvar();
				
				MatriculaRepository::removerMatricula($oMatricula);
				RegenciaRepository::removerRegencia($oRegencia);
				unset($oPeriodo);
				
				db_fim_transacao();
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