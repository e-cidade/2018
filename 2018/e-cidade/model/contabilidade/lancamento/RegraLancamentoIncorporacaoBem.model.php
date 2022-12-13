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

require_once("interfaces/IRegraLancamentoContabil.interface.php");

/**
 * Model responsável por buscar as transações configuradas para os lançamentos de incorporacao de bens
 * @author Rafael Lopes <rafael.lopes@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.14 $
 */
class RegraLancamentoIncorporacaoBem implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oDaoTransacao = db_utils::getDao('contranslr');
    $sWhere        = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere       .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere       .= " and c46_seqtranslan = {$iCodigoLancamento}";
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);

    if ($oDaoTransacao->numrows == 0) {

      $sMsgErro = "Não há lançamentos configurados para o documento {$iCodigoDocumento}.";
      throw new BusinessException($sMsgErro);
    }

    $aRegrasEncontradas = array();
    
    $iContaMaterial = $oLancamentoAuxiliar->getBem()->getClassificacao()->getContaContabil()->getReduzido();
    
    for ($iLinhaRegra = 0; $iLinhaRegra < $oDaoTransacao->numrows; $iLinhaRegra++){

      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $iLinhaRegra);
      
      /**
       * Tipo de Entrada
       */
      $iTipoAquisicao = $oLancamentoAuxiliar->getBem()->getTipoAquisicao()->getCodigo();
      $oRegraLancamento = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);

      
      if ($oDadosTransacao->c46_ordem == 1) {
         
        /**
         * Regra para tipo de Entrada
         */
      	if ($oDadosTransacao->c47_compara == 7 && $oDadosTransacao->c47_ref == $iTipoAquisicao && !$oLancamentoAuxiliar->isEstorno()) {
      
      		$oRegraLancamento->setContaDebito($iContaMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}
      	if ($oDadosTransacao->c47_compara == 7 && $oDadosTransacao->c47_ref == $iTipoAquisicao && $oLancamentoAuxiliar->isEstorno()) {
      
      		$oRegraLancamento->setContaCredito($iContaMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}

        $lBemBaixado = $oLancamentoAuxiliar->getBem()->isBaixado();

      	/**
         * Regra para tipo de Saída
         */
      	if ($iCodigoDocumento == 701 && $lBemBaixado && $oDadosTransacao->c47_compara == 8 && $oDadosTransacao->c47_ref == $oLancamentoAuxiliar->getBem()->getDadosBaixa()->motivo) {
      	
      		$oRegraLancamento->setContaCredito($iContaMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}

        if ($iCodigoDocumento == 702 && $lBemBaixado && $oDadosTransacao->c47_compara == 8 && $oDadosTransacao->c47_ref == $oLancamentoAuxiliar->getBem()->getDadosBaixa()->motivo) {

          $oRegraLancamento->setContaDebito($iContaMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}     	
      	 
      } else {
      	$aRegrasEncontradas[] = $oRegraLancamento;
      }
      
    }

    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    if (count($aRegrasEncontradas) == 0) {
    	return false;
    }
    
    return $aRegrasEncontradas[0];
  }

}
