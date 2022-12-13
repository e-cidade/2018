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
 * Retorna a regra cadastrada para a arrecadação de receita
 * @author     Raphael lopes
 * @package    contabilidade
 * @subpackage lancamento
 * @version    1.0 $
 */
class RegraLancamentoContaDepreciacao implements IRegraLancamentoContabil {
  
  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);  

    if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {
      return false;
    }         

    $aRegrasDoLancamento = $oLancamentoEventoContabil->getRegrasLancamento();    
    $iConta              = $oLancamentoAuxiliar->getCodigoConta();
    $aContasEncontradas  = array();

    foreach ($aRegrasDoLancamento as  $oRegraLancamentoContabil ) {

      /**
       * Ordem 1
       * Primeiro lançamento
       */
      if ( $oLancamentoEventoContabil->getOrdem() == 1 ) {

        /**
         * Inclusao
         * - credita a "Conta Depreciação" da classificação do bem (debita a contrapartida da transação)
         */
        if ( $iCodigoDocumento == 604 ) {
          $oRegraLancamentoContabil->setContaCredito($iConta);
        }

        /**
         * Estorno
         * - debita a "Conta Depreciação" da classificação do bem (credita a contrapartida da transação)
         */
        if ( $iCodigoDocumento == 605 ) {
          $oRegraLancamentoContabil->setContaDebito($iConta); 
        }

        $aContasEncontradas[] = $oRegraLancamentoContabil;
        continue;
      } 

      /** 
       * Ordem > 1 
       * - demais lancamentos
       */
      $aContasEncontradas[] = $oRegraLancamentoContabil;
    }

    /**
     * Nenhuma regra de lancamento encontrada para o documento 
     */
    if ( count($aContasEncontradas) == 0 ) {
      return false;
    }

    /**
     * Erro - encontrou mais de uma regra para o lancamento 
     */
    if ( count($aContasEncontradas) > 1 ) {

      $oStdDadosMensagem = new stdClass();
      $oStdDadosMensagem->iDocumento       = $iCodigoDocumento . ' - ' . $oEventoContabil->getDescricaoDocumento();
      $oStdDadosMensagem->iOrdemLancamento = $oLancamentoEventoContabil->getOrdem();

      throw new BusinessException(_M('financeiro.contabilidade.RegraLancamentoReavaliacaoBem.mais_de_uma_regra_encontrada', $oStdDadosMensagem));
    }

    $oRegraLancamentoContabil = $aContasEncontradas[0];
    return $oRegraLancamentoContabil;
  }

}