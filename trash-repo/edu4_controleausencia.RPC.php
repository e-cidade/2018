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
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/censo/DadosCenso.model.php");
require_once("classes/db_cursoedu_classe.php");
require_once("model/CgmFactory.model.php");


db_app::import("exceptions.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.censo.*");
db_app::import("educacao.ausencia.*");
db_app::import("educacao.*");
db_app::import("configuracao.UsuarioSistema");


$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;
$oRetorno->message  = '';

$iEscola            = db_getsession("DB_coddepto");
if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
	$iEscola = $oParam->iEscola;
}

$oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));

$iModuloEscola      = 1100747;

try {
	
	switch ($oParam->exec) {
	
		case 'pesquisaTiposAusencia':
			
			$oDaoAusencia = db_utils::getDao('tipoausencia');
			$sSqlAusencia = $oDaoAusencia->sql_query_file();
			$rsAusencia   = $oDaoAusencia->sql_record($sSqlAusencia);
			$iRegistros   = $oDaoAusencia->numrows;
			
			if ($iRegistros == 0) {

				$sMsg  = "Não há tipos de ausência cadastrada.";
				$sMsg .= "Cadastre no Menu: Secretaria da Educação > Cadastros > Tipo de Ausência > Inclusão";
				
				$oRetorno->message = $sMsg;
				$oRetorno->status  = 2;
			}
			
			for($i = 0; $i < $iRegistros; $i++) {
				
				$oDados               = db_utils::fieldsMemory($rsAusencia, $i);
				$oAusencia            = new stdClass();
				$oAusencia->codigo    = $oDados->ed320_sequencial;
				$oAusencia->descricao = urlencode($oDados->ed320_descricao);
				$oRetorno->dados[]    = $oAusencia;
			}
			
			break;
		case 'salvarDadosAusencia':
			
			$oDaoRecHumano = db_utils::getDao('rechumano');
			$sSqlRecHumano = $oDaoRecHumano->sql_query_rechumano($oParam->iRecHumano);
			$rsRecHumano   = $oDaoRecHumano->sql_record($sSqlRecHumano, "z01_numcgm");
			
			$iCgmRecHumano = db_utils::fieldsMemory($rsRecHumano, 0)->z01_numcgm;
			
			$sObservacao   = '';
			$sMsgSucesso   = "Ausência salva com sucesso.";
			
			if (!empty($oParam->sObservacao)) {
				$sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
			}
			
			db_inicio_transacao();
			
			$oAusencia = new AusenciaDocente();
			
			if (!empty($oParam->iCodigo)) {
				
				$oAusencia = new AusenciaDocente($oParam->iCodigo);
				$sMsgSucesso = "Ausência alterada com sucesso.";
			}
			
			$oAusencia->setDataInicial(new DBDate($oParam->dtInicio));
			
			$oDataFinal = null;
			if (!empty($oParam->dtFinal)) {
			  $oDataFinal = new DBDate($oParam->dtFinal);
			}
			$oAusencia->setDataFinal($oDataFinal);
			$oAusencia->setTipoAusencia(new TipoAusencia($oParam->iAusencia));
			$oAusencia->setObservacao($sObservacao);
			$oAusencia->setDocente(new ProfessorVO($oParam->iRecHumano, $iCgmRecHumano));
			$oAusencia->setUsuario($oUsuario);
			$oAusencia->setEscola(new Escola($iEscola));
			
			$oAusencia->salvar();
			
			db_fim_transacao();
			
			$oRetorno->message = $sMsgSucesso;
			$oRetorno->iCodigo = $oAusencia->getCodigo();
			
			unset($oAusencia);
			
			break;
			
		case 'carregaDadosAusencia':
			
			if (empty($oParam->iCodigo)) {
				
				throw new BusinessException('Houve um erro no carregamento do código da ausencia.\n Contate o Suporte.');
			}
			
			$oAusencia = new AusenciaDocente($oParam->iCodigo);
			
			$oDadosAusencia              = new stdClass();
			$oDadosAusencia->iCodigo     = $oAusencia->getCodigo();
			$oDadosAusencia->iRecHumano  = $oAusencia->getDocente()->getMatricula();
			$oDadosAusencia->sNome       = urlencode($oAusencia->getDocente()->getProfessor()->getNome());
			$oDadosAusencia->iCgm        = urlencode($oAusencia->getDocente()->getProfessor()->getCodigo());
			$oDadosAusencia->iAusencia   = $oAusencia->getTipoAusencia()->getCodigo();
			$oDadosAusencia->dtInicio    = urlencode($oAusencia->getDataInicial()->getDate(DBDate::DATA_PTBR));
			$oDadosAusencia->dtFinal     = null;
			$oDadosAusencia->sObservacao = urlencode($oAusencia->getObservacao());
			 
			$dtFinal = $oAusencia->getDataFinal();
			if (!empty($dtFinal)) {
				$oDadosAusencia->dtFinal = urlencode($dtFinal->getDate(DBDate::DATA_PTBR));
			}
			
			$oRetorno->oAusencia = $oDadosAusencia;
			
			unset($oAusencia);
			
			break;
		case 'excluirDadosAusencia':
			
			db_inicio_transacao();
			
			$oAusencia = new AusenciaDocente($oParam->iCodigo);
			$oAusencia->excluir();
			
			$oRetorno->message = "Ausência Excluída com sucesso.";
			db_fim_transacao();
			
			break;
		
		case 'turmasDocente':
			
			$oDaoRegencia = db_utils::getDao('regenciahorario');
			$sWhere       = "     ed58_i_rechumano = {$oParam->iRecHumano}";
			$sWhere      .= " and ed57_i_escola    = {$iEscola}";
			$sWhere      .= " and ed52_i_ano       = ". db_getsession("DB_anousu");
			
			$sCampos      = "distinct ed57_i_codigo, trim(ed57_c_descr) as ed57_c_descr ";
			
			$sSqlTurmas = $oDaoRegencia->sql_query_diario_classe_periodo(null, $sCampos, null, $sWhere);
			$rsTurma    = $oDaoRegencia->sql_record($sSqlTurmas);
			$iRegistros = $oDaoRegencia->numrows;
			
			if ($iRegistros == 0) {
				
				$oRetorno->status  = 2;
				$oRetorno->message = "Nenhuma turma encontrada para professor ausente.";
			}
			
			for($i = 0; $i < $iRegistros; $i++) {

				$oDadosTurma       = db_utils::fieldsMemory($rsTurma, $i);
				$oTurma            = new stdClass();
				$oTurma->codigo    = $oDadosTurma->ed57_i_codigo;
				$oTurma->descricao = urlencode($oDadosTurma->ed57_c_descr);
				
				$oRetorno->dados[] = $oTurma;
			}
			
			break;
			
		case 'disciplinasTurma':
			
			$oTurma               = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
			$oDocenteAusente      = new AusenciaDocente($oParam->iCodigo);
			$aRegenciasLecionadas = $oDocenteAusente->getDisciplinasLecionadaTurma($oTurma);
			
			if (count($aRegenciasLecionadas) > 0) {
			
				foreach ($aRegenciasLecionadas as $oRegencia) {
					
					$aSubstitutoRegencia    = $oDocenteAusente->getDocenteSubstitutoPorRegencia($oRegencia);
					$oDisciplina            = new stdClass();
					$lNaoIncluirDisciplina  = false;
					
					if (count($aSubstitutoRegencia) > 0) {
					
						foreach ($aSubstitutoRegencia as $oSubstituto) {
								
							if ($oSubstituto->getRegencia()->getCodigo() == $oRegencia->getCodigo()
									&& $oSubstituto->getTipoVinculo() == 2) {
					
								$lNaoIncluirDisciplina = true;
								break;
							}
						}
					}
						
					if ($lNaoIncluirDisciplina) {
						continue;
					}
						
					$oDisciplina->regencia  = $oRegencia->getCodigo();
					$oDisciplina->descricao = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
					
					$oRetorno->dados[]      = $oDisciplina;
				}
			}
			
			break;
		case 'buscaTipoVinculo':
			
			$oDaoVinculo = db_utils::getDao('tipovinculo');
			$sSqlVinculo = $oDaoVinculo->sql_query_file();
			$rsVinculo   = $oDaoVinculo->sql_record($sSqlVinculo);
			$iRegistros  = $oDaoVinculo->numrows;
			
			for($i = 0; $i < $iRegistros; $i++) {
				
				$oDados              = db_utils::fieldsMemory($rsVinculo, $i);
				$oVinculo            = new stdClass();
				$oVinculo->codigo    = $oDados->ed324_sequencial;
				$oVinculo->descricao = urlencode($oDados->ed324_descricao);
				
				$oRetorno->dados[]   =  $oVinculo;
			}
			
			break;
			
		case 'buscaDocentesSubstitutos':
			
			/**
			 * Utilizado para preencher a Grid com os Docentes Substitutos
			 */
			$oDocenteAusente = new AusenciaDocente($oParam->iCodigo);
			$aSubstitutos    = $oDocenteAusente->getDocentesSubstitutos();

			if (count($aSubstitutos) > 0) {
				
				foreach ($aSubstitutos as $oSubstituto) {
					
					$oDadosSubstituto = new stdClass();
					$oDadosSubstituto->iSubstituto = $oSubstituto->getCodigo();
					$oDadosSubstituto->iRecHUmano  = $oSubstituto->getProfessorSubstituto()->getMatricula();
					$oDadosSubstituto->sNome       = urlencode($oSubstituto->getProfessorSubstituto()->getProfessor()->getNome());
					$oDadosSubstituto->iTurma      = $oSubstituto->getRegencia()->getTurma()->getCodigo();
					$oDadosSubstituto->sTurma      = urlencode($oSubstituto->getRegencia()->getTurma()->getDescricao());
					$oDadosSubstituto->iRegencia   = $oSubstituto->getRegencia()->getCodigo();
					$oDadosSubstituto->sRegencia   = urlencode($oSubstituto->getRegencia()->getDisciplina()->getNomeDisciplina());
					$oDadosSubstituto->iTipo       = $oSubstituto->getTipoVinculo();
					$oDadosSubstituto->sTipo       = urlencode($oSubstituto->getTipoVinculo() == 1 ? "TEMPORÁRIO" : "PERMANENTE");
					$oDadosSubstituto->dtInicio    = urlencode($oSubstituto->getPeriodoInicial()->getDate(DBDate::DATA_PTBR));
					$oDadosSubstituto->dtFinal     = '';
					
					$oDtFinal = $oSubstituto->getPeriodoFinal();
					if (!empty($oDtFinal)) {
						$oDadosSubstituto->dtFinal    = urlencode($oDtFinal->getDate(DBDate::DATA_PTBR));
					}
	
					$oRetorno->dados[] = $oDadosSubstituto;
				}
			}
			unset($aSubstitutos);
			unset($oDocenteAusente);
			break;
			
		case 'vincularDocenteSubstituto':

			/**
       * Realiza o vínculo de um docente substituto a um Docente Ausente
 			 */
			$oDocenteAusente   = new AusenciaDocente($oParam->iAusente);

			$iCodigoSubstituto = !empty($oParam->iSubstituto) ? $oParam->iSubstituto : null;
			$oSubstituto       = new DocenteSubstituto($iCodigoSubstituto);
			
			$oSubstituto->setProfessorSubstituto(new ProfessorVO($oParam->iRecHumanoSubstituto, $oParam->iCgmSubstituto));
			$oSubstituto->setRegencia(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia));
			
			$iTipoVinculo = $oParam->iTipoVinculo == 1 ? DocenteSubstituto::TEMPORARIO : DocenteSubstituto::PERMANENTE;
			
			$oSubstituto->setTipovinculo($iTipoVinculo);
			$oSubstituto->setPeriodoInicial(new DBDate($oParam->dtInicial));
			
			$oSubstituto->removePeriodoFinal();
			if (!empty($oParam->dtFinal)) {
				$oSubstituto->setPeriodoFinal(new DBDate($oParam->dtFinal));
			}
			
			$oSubstituto->setUsuario($oUsuario);
			
			db_inicio_transacao();
			$oDocenteAusente->vincularSubstituto($oSubstituto);
			
			$sMsg  = "Professor substituto cadastrado com sucesso!";
			
			$oRetorno->message = $sMsg;
			
			db_fim_transacao();
			
			break;
			
		case 'carregaDadosDocenteSubstituto':
			
			$oDocenteSubstituto                = new DocenteSubstituto($oParam->iSubstituto);
			
			$oRetorno->iSubstituto          = $oDocenteSubstituto->getCodigo();
			$oRetorno->iRecHumanoSubstituto = $oDocenteSubstituto->getProfessorSubstituto()->getMatricula();
			$oRetorno->iCgmSubstituto       = $oDocenteSubstituto->getProfessorSubstituto()->getProfessor()->getCodigo();
			$oRetorno->sNome                = urlencode($oDocenteSubstituto->getProfessorSubstituto()->getProfessor()->getNome());
			$oRetorno->iTurma               = $oDocenteSubstituto->getRegencia()->getTurma()->getCodigo();
			$oRetorno->iRegencia            = $oDocenteSubstituto->getRegencia()->getCodigo();
			$oRetorno->sRegencia            = $oDocenteSubstituto->getRegencia()->getDisciplina()->getNomeDisciplina();
			$oRetorno->iTipoVinculo         = $oDocenteSubstituto->getTipoVinculo();
			$oRetorno->dtInicial            = urlencode($oDocenteSubstituto->getPeriodoInicial()->getDate(DBDate::DATA_PTBR));
			
			
			$oRetorno->dtFinal = '';
			$oDtFinal          = $oDocenteSubstituto->getPeriodoFinal();
			if (!empty($oDtFinal)) {
				$oRetorno->dtFinal = urlencode($oDocenteSubstituto->getPeriodoFinal()->getDate(DBDate::DATA_PTBR));
			}
			
			break;

		case 'removerVinculoDocenteSubstituto':
			
			$oDocenteAusente    = new AusenciaDocente($oParam->iAusente);
			$oDocenteSubstituto = new DocenteSubstituto($oParam->iSubstituto);
			
			db_inicio_transacao();
			
			$oDocenteAusente->desvincularSubstituto($oDocenteSubstituto);
			$oRetorno->message = " Excluido Substituição do Regente. ";

			db_fim_transacao();
			
			unset($oDocenteAusente);
			
			break;
	}
	
} catch (DBException $oErro) {
	
	$oRetorno->message = $oErro->getMessage();
	$oRetorno->status  = 2;
	db_fim_transacao(true);
} catch (BusinessException $oErro) {
	
	$oRetorno->message = $oErro->getMessage();
	$oRetorno->status  = 2;
	db_fim_transacao(true);
} catch (ParameterException $oErro) {
	
	$oRetorno->message = $oErro->getMessage();
	$oRetorno->status  = 2;
	db_fim_transacao(true);
} catch (Exception $oErro) {
	
	$oRetorno->message = $oErro->getMessage();
	$oRetorno->status  = 2;
	db_fim_transacao(true);
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);