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

require_once("model/issqn/AlvaraMovimentacao.model.php");

/**
 * @deprecated
 * @see model/issqn/alvara/RenovacaoAlvara.model.php
 *
 * @fileoverview - Classe de Modelo para movimentaчѕes da Renovaчуo do Alvarс
 * @author    Vinicius Silva  - vinicius.silva@dbseller.com.br	
 * @package   ISSQN
 * @revision  $Author: dbjeferson.belmiro $
 * @version   $Revision: 1.4 $
 */
class AlvaraRenovacao extends AlvaraMovimentacao {
		
	/**
	 * Mщtodo construtor
	 * @param integer $iCodigoAlvara
	 */
  function __construct($iCodigoAlvara) {
  	
  	parent::__construct($iCodigoAlvara);
  }
  
  /**
   * Busca a quantidade de vezes que o alvarс pode ser modificado
   * @return integer quantidade de vezes que pode ser modificado
   */
  function getRenovacoesAlvara() {
  	
  	$aMovimentacoesDeAlteracao = array();
  	$oMovimentacoesAlvara = parent::getMovimentacoesAlvara();
  	foreach ($oMovimentacoesAlvara as $oMovimentacao) {
  		if ($oMovimentacao->q120_isstipomovalvara == 4) {
  			$aMovimentacoesDeAlteracao[] = $oMovimentacao;
  		}
  	}
    return count($aMovimentacoesDeAlteracao);
  }

  /**
   * Valida quantas vezes a renovaчуo foi cancelada
   * @return integer  - Numero de renovaчѕes
   */
  function getCancelamentosRenovacoesAlvara() {
     
    $aMovimentacoesDeCancelamento = array();
    $oMovimentacoesAlvara         = parent::getMovimentacoesAlvara();
    foreach ($oMovimentacoesAlvara as $oMovimentacao) {
    		if ($oMovimentacao->q120_isstipomovalvara == 8) {
    		  $aMovimentacoesDeCancelamento[] = $oMovimentacao;
    		}
    }
    return count($aMovimentacoesDeCancelamento);
  }
  
  /**
   * valida a quantidade de renovaчѕes do alvarс
   * @param integer $iQuantRenovacoesRealizadas quantas vezes o alvarс jс foi modificado
   */
  function validaQuantidadeDeRenovacoesAlvara($iQuantRenovacoesRealizadas) {
  	
  	$oDaoIsstipoalvara                          = db_utils::getDao('isstipoalvara', true);
  	$iAlvara                                    = parent::getCodigoAlvara();
  	$sWhereBuscaQuantidadePermitidaDeRenovacoes = " q123_sequencial = {$iAlvara} ";
  	$sSqlBuscaQuantidadePermitidaDeRenovacoes   = $oDaoIsstipoalvara->sql_query_tipocomalvaravinculado(null, "q98_quantrenovacao", null,
  	                                                                                                   $sWhereBuscaQuantidadePermitidaDeRenovacoes
  	                                                                                                  );
    $rsBuscaQuantidadePermitidaDeRenovacoes     = $oDaoIsstipoalvara->sql_record($sSqlBuscaQuantidadePermitidaDeRenovacoes);
    $iQuantidadeRenovacoesPermitidas = db_utils::fieldsMemory($rsBuscaQuantidadePermitidaDeRenovacoes, 0)->q98_quantrenovacao;
    if ($iQuantRenovacoesRealizadas >= $iQuantidadeRenovacoesPermitidas) {
  	  throw new ErrorException("O alvarс jс alcanчou o limite de renovaчѕes permitido.");
    }
  }
  
  /**
   * salva a renovaчуo do alvarс usando o mщtodo da classe abstrata
   */
  function renovaAlvara() {
  	parent::salvar();
  }
}

?>