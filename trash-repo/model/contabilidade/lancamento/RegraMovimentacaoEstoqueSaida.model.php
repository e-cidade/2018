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

require_once("interfaces/IRegraLancamentoContabil.interface.php");

/**
 * Retorna a regra cadastrada para a Movimentacao do estoque de saida
 * @author Raphael Lopes
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 */
class RegraMovimentacaoEstoqueSaida implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

  	$oGrupo = $oLancamentoAuxiliar->getMaterial()->getGrupo();
  	  	
  	if(!isset($oGrupo)){
  		$sMsgErro  = "Grupo n�o configurado para material - ";
  		$sMsgErro .= "{$oLancamentoAuxiliar->getMaterial()->getcodMater()}. ";
  		throw new BusinessException($sMsgErro);
  	}
  	
    $oPlanoContaVPD = $oLancamentoAuxiliar->getMaterial()->getGrupo()->getContaVPD();
    $iContaCredito  = $oPlanoContaVPD->getReduzido();
    $iContaDebito   = $oLancamentoAuxiliar->getMaterial()->getGrupo()->getContaAtivo()->getReduzido();
    $iEstruturalVPD = substr($oPlanoContaVPD->getEstrutural(), 0, 1);

    if (empty($iContaCredito) ||
        $iContaCredito == $iContaDebito ||
        $iEstruturalVPD <> 3) {

      $sMsgErro  = "Conta VPD (Varia��o Patrimonial Diminutiva) n�o configurada para o grupo ";
      $sMsgErro .= "{$oLancamentoAuxiliar->getMaterial()->getGrupo()->getCodigo()} - ";
      $sMsgErro .= "{$oLancamentoAuxiliar->getMaterial()->getGrupo()->getDescricao()}.";
    	throw new BusinessException($sMsgErro);
    }

    $oRegraLancamentoContabil = new RegraLancamentoContabil();
    $oRegraLancamentoContabil->setContaCredito($iContaCredito);
    $oRegraLancamentoContabil->setContaDebito($iContaDebito);

    if ($oLancamentoAuxiliar->isSaida()) {
    	
    	$oRegraLancamentoContabil->setContaCredito($iContaDebito);
    	$oRegraLancamentoContabil->setContaDebito($iContaCredito);
    }

    return $oRegraLancamentoContabil;
  }
}