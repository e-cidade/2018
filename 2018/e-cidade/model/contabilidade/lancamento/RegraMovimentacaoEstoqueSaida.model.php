<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("interfaces/IRegraLancamentoContabil.interface.php");

/**
 * Retorna a regra cadastrada para a Movimentacao do estoque de saida
 * @author Raphael Lopes
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.10 $
 */
class RegraMovimentacaoEstoqueSaida implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return RegraLancamentoContabil
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

  	$oGrupo = $oLancamentoAuxiliar->getMaterial()->getGrupo();
  	  	
  	if(!isset($oGrupo)){
  		$sMsgErro  = "Grupo n�o configurado para material - ";
  		$sMsgErro .= "{$oLancamentoAuxiliar->getMaterial()->getcodMater()}. ";
  		throw new BusinessException($sMsgErro);
  	}
  	
    $oPlanoContaVPD = $oLancamentoAuxiliar->getMaterial()->getGrupo()->getContaVPD();
    if (empty($oPlanoContaVPD)) {
      throw new BusinessException('Conta cont�bil VPD n�o configurada para o grupo  $oLancamentoAuxiliar->getMaterial()->getGrupo()->getDescricao().');
    }
    
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