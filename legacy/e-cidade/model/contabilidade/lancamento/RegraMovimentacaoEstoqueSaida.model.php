<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
  		$sMsgErro  = "Grupo não configurado para material - ";
  		$sMsgErro .= "{$oLancamentoAuxiliar->getMaterial()->getcodMater()}. ";
  		throw new BusinessException($sMsgErro);
  	}
  	
    $oPlanoContaVPD = $oLancamentoAuxiliar->getMaterial()->getGrupo()->getContaVPD();
    if (empty($oPlanoContaVPD)) {
      throw new BusinessException('Conta contábil VPD não configurada para o grupo  $oLancamentoAuxiliar->getMaterial()->getGrupo()->getDescricao().');
    }
    
    $iContaCredito  = $oPlanoContaVPD->getReduzido();
    $iContaDebito   = $oLancamentoAuxiliar->getMaterial()->getGrupo()->getContaAtivo()->getReduzido();
    $iEstruturalVPD = substr($oPlanoContaVPD->getEstrutural(), 0, 1);

    if (empty($iContaCredito) ||
        $iContaCredito == $iContaDebito ||
        $iEstruturalVPD <> 3) {

      $sMsgErro  = "Conta VPD (Variação Patrimonial Diminutiva) não configurada para o grupo ";
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