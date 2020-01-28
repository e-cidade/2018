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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");

$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

switch ($oParam->exec){
	
	case 'buscaAvaliacao':
	
		$oFamilia          = new Familia($oParam->iFamilia);
		$oAvaliacao        = $oFamilia->getAvaliacao();
		$iCodigoAvaliacao  = Familia::CODIGO_AVALICAO;
		/**
		 * Buscamos o grupo de resposta
		 */
		$oRetorno->iCodigoGrupoRespostas = $oAvaliacao->getAvaliacaoGrupo();
		$oRetorno->iCodigoAvaliacao      = $iCodigoAvaliacao;
		break;
		
	case 'buscaComposicaoFamiliar':
			
		$oFamilia            = new Familia($oParam->iFamilia);
		$aComposicaoFamiliar = $oFamilia->getComposicaoFamiliar();
		
		foreach ($aComposicaoFamiliar as $oMembroFamilia) {
			
			$oMembro       = new stdClass();
			$oMembro->iNis = '';
			
			if ($oFamilia->isCadastroUnico() && $oMembroFamilia instanceof CadastroUnico) {
				$oMembro->iNis = $oMembroFamilia->getNis();
			}
		
			$oMembro->sNome           = urlencode($oMembroFamilia->getNome());
			$oMembro->sGrauParentesco = urlencode($oMembroFamilia->getTipoFamilia());
			$oMembro->iCodigoCidadao  = $oMembroFamilia->getCodigo();
			$oRetorno->dados[]        = $oMembro;
		}
		
		if (count($aComposicaoFamiliar) == 0) {
			
			$oRetorno->status   = 2;
			$oRetorno->message  = urlencode("Não foi encontrado nenhum familiar.");
		}
		
		break;
		
	case 'buscaBeneficiosFamilia':
		
		$oFamilia            = new Familia($oParam->iFamilia);
		$aBeneficiosFamilia  = $oFamilia->getListaBeneficios();
		
		foreach ($aBeneficiosFamilia as $oListaBeneficio) {
				
			$oBeneficio = new stdClass();
				
			$oBeneficio->beneficio   = urlencode($oListaBeneficio->beneficio);
			$oBeneficio->situacao    = urlencode($oListaBeneficio->situacao);
			$oBeneficio->quantidade  = $oListaBeneficio->quantidade;
				
			$oRetorno->dados[]       = $oBeneficio;
		}
		
		if (count($aBeneficiosFamilia) == 0) {
				
			$oRetorno->status   = 2;
			$oRetorno->message  = urlencode("Não foi encontrado nenhum beneficio.");
		}
		break;
		
  /**
   * Retorna as visitas feitas a uma familia
   * @param integer $oParam->iFamilia - Codigo da familia
   * @return array $oRetorno->aVisitas
   *               stdClass dtVisita      - Data da Visita
   *                        sHora         - Hora da visita
   *                        sProfissional - Profissional que realizou a visita
   *                        sObservacao   - Observacao em relacao a visita
   */
	case 'buscaVisitas':
	  
	  if (isset($oParam->iFamilia)) {
	    
	    $oRetorno->aVisitas = array();
	    $oFamilia           = FamiliaRepository::getFamiliaByCodigo($oParam->iFamilia);
	    
	    if (count($oFamilia->getVisitas()) > 0) {
	      
	      foreach ($oFamilia->getVisitas() as $oVisitas) {
	        
	        $oDadosVisita = new stdClass();
	        $oDadosVisita->dtVisita = urlencode($oVisitas->getDataVisita());
	        $oDadosVisita->sHora    = urlencode($oVisitas->getHoraVisita());
	        
	        $oProfissional               = CgmFactory::getInstanceByCgm($oVisitas->getProfissionalVisita());
	        $oDadosVisita->sProfissional = urlencode($oProfissional->getNome());
	        $oDadosVisita->sObservacao   = urlencode($oVisitas->getObservacao());
	        $oRetorno->aVisitas[]        = $oDadosVisita;
	      }
	    }
	  }
	  
	  break;
	  
	case 'buscaCrasCreas':
	  
	  if (isset($oParam->iFamilia)) {
	    
	    $oRetorno->aHistorico = array();
	    $oFamilia             = FamiliaRepository::getFamiliaByCodigo($oParam->iFamilia);
	    
	    if (count($oFamilia->getHistoricoAtendimentos()) > 0) {
	      
	      foreach ($oFamilia->getHistoricoAtendimentos() as $oHistorico) {
	        
	        $oDadosHistorico                 = new stdClass();
	        $oDadosHistorico->sIdentificador = urlencode($oHistorico->getLocalAtendimentoSocial()->getIdentificadorUnico());
	        $oDadosHistorico->sCrasCreas     = urlencode($oHistorico->getLocalAtendimentoSocial()->getDescricao());
	        $oDadosHistorico->dtInicio       = urlencode($oHistorico->getDataVinculo()->getDate(DBDate::DATA_PTBR));
	        $oDadosHistorico->dtFim          = urlencode('');
	        
	        if ($oHistorico->getFimAtendimento() != '') {
	          $oDadosHistorico->dtFim = urlencode($oHistorico->getFimAtendimento()->getDate(DBDate::DATA_PTBR));
	        }
	        
	        $oDadosHistorico->sSituacao = $oHistorico->isAtivo() ? urlencode("Ativo") : urlencode("Inativo");
	        $oRetorno->aHistorico[]     = $oDadosHistorico;
	      }
	    }
	  }
	  break;
}
echo $oJson->encode($oRetorno);