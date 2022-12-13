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
 * Retorna a regra cadastrada para a Movimentacao do estoque
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.3 $
 */
class RegraLancamentoEntradaEstoque implements IRegraLancamentoContabil {

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
    $iRegistros    = $oDaoTransacao->numrows;

    /**
     * Busco o codigo reduzido do material
     */
    $iContaMaterial = $oLancamentoAuxiliar->getContaMaterial();

    /**
     * Percorro todas as transacoes configuradas comparando as contas com a do material.
     */
    $aRegrasEncontradas = array();

    for ($i = 0; $i < $iRegistros; $i++) {

      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $i);
      
      /*
      if ($iContaMaterial == $oDadosTransacao->c47_debito) {

        $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
        $aRegrasEncontradas[]     = $oRegraLancamentoContabil;
      }
      */
     	$oRegraLancamento = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
     	
     	if ($oDadosTransacao->c46_ordem == 1) {
     		
	      if (!$oLancamentoAuxiliar->isSaida()) {
	      	
	      	$oRegraLancamento->setContaDebito($iContaMaterial);
	      	$aRegrasEncontradas[] = $oRegraLancamento;
	      }
	      
	      if ($oLancamentoAuxiliar->isSaida()) {
	      	
	      	$oRegraLancamento->setContaCredito($iContaMaterial);
	      	$aRegrasEncontradas[] = $oRegraLancamento;
	      }
	      
     	} else {
     		
     		$aRegrasEncontradas[] = $oRegraLancamento;
     	}
      
      
    }

    if (count($aRegrasEncontradas) > 1) {

      $sMensagemException  = "mais de uma  conta débito/crédito configurada para o ";
      $sMensagemException .= "lançamento [{$iCodigoLancamento}] de ordem {$oDadosTransacao->c46_ordem}.";
      throw new BusinessException($sMensagemException);
    }

    /**
     * Nao encontrou nenhuma regra de lancamento para o documento 
     */
    if (count($aRegrasEncontradas) == 0) {
      return false;
    }

    return $aRegrasEncontradas[0];
  }

}