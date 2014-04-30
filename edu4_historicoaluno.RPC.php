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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
db_app::import("educacao.*");
db_app::import("exceptions.*");
db_app::import("educacao.censo.DisciplinaCenso");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {
  
  case 'getDisciplinasHistorico' :
    
    try {
      if (!isset($oParam->iCodigoHistoricoAno)) {
        throw new ParameterException('Histórico não informado.');
      }
      if (empty($oParam->iCodigoHistoricoAno)) {
        throw new ParameterException('Código do Historico informado é invalido');
      }
      if (!isset($oParam->iTipoHistorico)) {
        throw new Exception('Tipo do Histórico não informado');
      }
      switch ($oParam->iTipoHistorico) {
        
        case 1 :
          
          $oDaoHistoricoDisciplina  = db_utils::getDao("histmpsdisc");
          $sCampos                  = "ed65_i_codigo          as codigo,"; 
          $sCampos                 .= "ed65_i_disciplina      as codigo_disciplina,";
          $sCampos                 .= "ed232_c_descr          as descricao_disciplina,";
          $sCampos                 .= "ed232_c_abrev          as abreviatura_disciplina,";
          $sCampos                 .= "ed65_c_resultadofinal  as resultado_final,";
          $sCampos                 .= "ed65_c_situacao        as situacao,"; 
          $sCampos                 .= "ed65_t_resultobtido    as resultado_obtido,";
          $sCampos                 .= "ed65_i_qtdch           as carga_horaria,";
          $sCampos                 .= "ed65_i_justificativa as justificativa,";
          $sCampos                 .= "justificativa.ed06_c_descr           as descricao_justificativa"; 
          $sWhere                   = "ed65_i_historicomps = {$oParam->iCodigoHistoricoAno}";
          $sOrdem                   = "ed65_i_ordenacao";
          break;
          
        case 2 :

          $oDaoHistoricoDisciplina  = db_utils::getDao("histmpsdiscfora");
          $sCampos                  = "ed100_i_codigo             as codigo,"; 
          $sCampos                 .= "ed100_i_disciplina         as codigo_disciplina,";
          $sCampos                 .= "ed232_c_descr              as descricao_disciplina,";
          $sCampos                 .= "ed232_c_abrev              as abreviatura_disciplina,";
          $sCampos                 .= "ed100_c_resultadofinal     as resultado_final,";
          $sCampos                 .= "ed100_c_situacao           as situacao,"; 
          $sCampos                 .= "ed100_t_resultobtido       as resultado_obtido,";
          $sCampos                 .= "ed100_i_qtdch              as carga_horaria,";
          $sCampos                 .= "ed100_i_justificativa      as justificativa,";
          $sCampos                 .= "justificativa.ed06_c_descr as descricao_justificativa"; 
          $sWhere                   = "ed100_i_historicompsfora = {$oParam->iCodigoHistoricoAno}";
          $sOrdem                   = "ed100_i_ordenacao";
          break;  
        default:
          
          throw new Exception("Tipo de histórico ({$oParam->iTipoHistorico}) não existe.");
          break;
      }
      
      $sSqlDisciplinas = $oDaoHistoricoDisciplina->sql_query(null, 
                                                             $sCampos, 
                                                             $sOrdem,
                                                             $sWhere  
                                                            );
                                                            
      $rsDisciplinas = $oDaoHistoricoDisciplina->sql_record($sSqlDisciplinas);
      $oRetorno->disciplinas = db_utils::getCollectionByRecord($rsDisciplinas, false, false, true);                                                            
    } catch (Exception $eErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
    
  case 'incluirDisciplinaHistorico':
    
    db_inicio_transacao();
    try {
      
      $oAluno     = new Aluno($oParam->iCodigoAluno);
      $oHistorico = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
      $oEtapa     = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);
      switch ($oParam->iTipoHistorico) {
        
        case 1:
          
          if (empty($oParam->iCodigoLancamento)) {
            $oDisciplina = new DisciplinaHistoricoRede();
          } else {
            $oDisciplina = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigoLancamento);
          }
          break;
          
        case 2:

          if (empty($oParam->iCodigoLancamento)) {
            $oDisciplina = new DisciplinaHistoricoForaRede();
          } else {
            $oDisciplina = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigoLancamento);
          }
          break;
      }
      $oDisciplina->setDisciplina(new Disciplina($oParam->iCodigoDisciplina));
      $oDisciplina->setJustificativa($oParam->iJustificativa);
      
      if (!empty($oParam->iCargaHoraria) && strpos($oParam->iCargaHoraria, ",")) {
        $oParam->iCargaHoraria = str_replace(",", ".", $oParam->iCargaHoraria);
      }
      
      $oDisciplina->setCargaHoraria($oParam->iCargaHoraria);
      $oDisciplina->setResultadoFinal($oParam->iResultado);
      $oDisciplina->setResultadoObtido($oParam->iAproveitamento);
      $oDisciplina->setSituacaoDisciplina(db_stdClass::normalizeStringJson($oParam->iSituacao));
      $oDisciplina->setTipoResultado($oParam->sTipoResultado);
      $oDisciplina->setOrdem($oParam->iOrdenacao);
      $oDisciplina->setTermoFinal($oParam->sTermoFinal);
      $oDisciplina->setLancamentoAutomatico(false);
            
      if (empty($oParam->iCodigoLancamento)) {
        $oEtapa->adicionarDisciplina($oDisciplina);
      }
      
      $oEtapa->salvar();
      $oRetorno->message = urlencode('Disciplina salva com sucesso.');
        
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    } catch (ParameterException $eParameterException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eParameterException->getMessage());
      
    } catch (BussinesException $eBussinessException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eBussinessException->getMessage());  
     
    } catch (DBException $eDBException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eDBException->getMessage());
    }
    
    db_fim_transacao(false);
    break;
    
  case 'excluirDisciplinaHistorico':

    try {
      
      db_inicio_transacao();
      $oAluno     = new Aluno($oParam->iCodigoAluno);
      $oHistorico = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
      $oEtapa     = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);
      $oEtapa->removerDisciplina($oParam->iDisciplina);
      $oRetorno->message = urlencode('Disciplina removida com sucesso.');
      db_fim_transacao(false);    
    } catch (ParameterException $eParameterException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eParameterException->getMessage());
      
    } catch (BussinesException $eBussinessException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eBussinessException->getMessage());  
     
    } catch (DBException $eDBException) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eDBException->getMessage());
    }
    break;
    
  case 'carregaDadosDisciplina':
    
    try {
      
      $oAluno             = new Aluno($oParam->iCodigoAluno);
      $oHistorico         = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
      $oEtapa             = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);
      $oDisciplina        = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigo);
      $oDisciplinaRetorno = new stdClass();
      
      $oDisciplinaRetorno->iCodigoDisciplina    = $oDisciplina->getDisciplina()->getCodigoDisciplina();
      $oDisciplinaRetorno->sDescricaoDisciplina = urlencode($oDisciplina->getDisciplina()->getNomeDisciplina());
      $oDisciplinaRetorno->sSituacao            = urlencode($oDisciplina->getSituacaoDisciplina());
      $oDisciplinaRetorno->iCargaHoraria        = $oDisciplina->getCargaHoraria();
      $oDisciplinaRetorno->sResultado           = urlencode($oDisciplina->getResultadoFinal());
      $oDisciplinaRetorno->nAproveitamento      = $oDisciplina->getResultadoObtido(); 
      $oDisciplinaRetorno->iCodigoLancamento    = $oDisciplina->getCodigo();
      $oDisciplinaRetorno->iJustificativa       = $oDisciplina->getJustificativa();
      $oDisciplinaRetorno->sTermoFinal          = $oDisciplina->getTermoFinal();

      $oRetorno->oDisciplina = $oDisciplinaRetorno;
      
    } catch (Exception $eErro) {
      
      $oRetorno->status  = 2 ;
      $oRetorno->message = urlencode($eDBException->getMessage());
    }
    break;
    
  case 'pesquisaTermos': 

    $oDaoHistorico   = new cl_histmpsdisc();
    $sWhereHistorico = "ed62_i_codigo = {$oParam->iCodigoHistoricoAno}";
    $sSqlHistorico   = $oDaoHistorico->sql_query(null, "ed62_i_anoref", null, $sWhereHistorico);
    $rsHistorico     = $oDaoHistorico->sql_record($sSqlHistorico);
    
    $oRetorno->aTermos = array();
    $iContadorTermos   = 0;
    if ($oDaoHistorico->numrows > 0) {
      $sAno              = db_utils::fieldsMemory($rsHistorico, 0)->ed62_i_anoref;
    } else {
      $sAno              = $oParam->iAnoReferencia;
    }
    $aTermos           = DBEducacaoTermo::getTermoEncerramentoDoEnsino($oParam->iEnsino, $sAno);
    foreach ($aTermos as $oTermo) {
      
      $oRetorno->aTermos[$iContadorTermos]->sReferencia = urlencode($oTermo->sReferencia);
      $oRetorno->aTermos[$iContadorTermos]->sDescricao  = urlencode($oTermo->sDescricao);
      $iContadorTermos++;
    }
    break;
    
    case 'validaEmissaoCertificado':
       
    	
    	/**
    	 * Valida se o aluno não possui matricula, se não possui deverá permitir impressão
    	 */
      $oDaoMatricula = db_utils::getDao('matricula');
      $sWhere        = " ed60_i_aluno = {$oParam->iAluno}";
      $sSqlMatricula = $oDaoMatricula->sql_query_file(null, "1", null, $sWhere);
      $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
       
      $oRetorno->lPermiteImpressao = true;
      if ($oDaoMatricula->numrows > 0) {
      	$oRetorno->lPermiteImpressao = false;
      }
      
      /**
			 * Valida se o aluno esta com o curso concluido
       */
      $oDaoAlunoCurso  = db_utils::getDao('alunocurso');
      $sWhereSituacao  = "     ed56_c_situacao = 'CONCLUÍDO'";
      $sWhereSituacao .= " and ed56_i_aluno = {$oParam->iAluno}";
      $sSqlSituacao    = $oDaoAlunoCurso->sql_query_file(null, "1", null, $sWhereSituacao);
      $rsSituacao      = $oDaoAlunoCurso->sql_record($sSqlSituacao);
      
      if ($oDaoAlunoCurso->numrows > 0) {
      	$oRetorno->lPermiteImpressao = true;
      }
      break;
      
    case 'alunoTemHistorico' :

    	$oDaoHistorico = db_utils::getDao('historico');
    	$sWhere        = "ed61_i_aluno = {$oParam->iAluno} ";
    	$rsHistorico   = $oDaoHistorico->sql_record($oDaoHistorico->sql_query_file(null, '1', null, $sWhere));

    	$oRetorno->lTemHistorico = false;
    	if ($oDaoHistorico->numrows > 0) {
    		$oRetorno->lTemHistorico = true;
    	}
    	
   break;

   case 'getDadosEtapa':

      $iCodigoEtapa     = $oParam->iCodigoEtapa;
      $sTipoEtapa       = $oParam->sTipoEtapa;
      $oEtapa           = HistoricoEtapa::getInstanciaPeloTipo( $sTipoEtapa, $iCodigoEtapa );
      $oRetorno->oEtapa = new stdClass();
      $oRetorno->oEtapa->sSituacao  = urlencode($oEtapa->getSituacaoEtapa());
      $oRetorno->oEtapa->sResultado = $oEtapa->getResultadoAno();
      
   break;
}
echo $oJson->encode($oRetorno);
?>