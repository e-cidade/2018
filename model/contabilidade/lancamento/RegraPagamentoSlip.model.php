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

require_once ("interfaces/IRegraLancamentoContabil.interface.php");

class RegraPagamentoSlip {

  public function __construct(){
  }

  /**
   * Deve retornar qual uma instancia da RegraLancamento, contendo as contas para efetuar o lançamento
   * @param  integer             $iCodigoDocumento
   * @param  integer             $iCodigoLancamento
   * @param  ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return RegraLancamentoContabil
   **/
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oDaoTransacao = db_utils::getDao('contranslr');
    $sWhere        = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere       .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere       .= " and c46_seqtranslan = {$iCodigoLancamento}";
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);

    /**
     * Verificamos se existe somente uma conta cadastrada para o lancamento. Caso exista mais de uma conta
     * abortamos o procedimento
     */
    $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);

    if ($oDadosTransacao->c46_ordem == 1) {

      if (method_exists($oLancamentoAuxiliar, "getCodigoSlip")) {
        
        $oDaoSlip           = db_utils::getDao('slip');
        $sSqlBuscaDadosSlip = $oDaoSlip->sql_query_file($oLancamentoAuxiliar->getCodigoSlip());
        $rsBuscaDadosSlip   = $oDaoSlip->sql_record($sSqlBuscaDadosSlip);
        if ($oDaoSlip->numrows == 0) {
          throw new BusinessException("Slip {$oLancamentoAuxiliar->getCodigoSlip()} não encontrado.");
        }
  
        $oDadoSlip                = db_utils::fieldsMemory($rsBuscaDadosSlip, 0);
        $oRegraLancamentoContabil = new RegraLancamentoContabil();
        $oRegraLancamentoContabil->setContaCredito($oDadoSlip->k17_credito);
        $oRegraLancamentoContabil->setContaDebito($oDadoSlip->k17_debito);
        return $oRegraLancamentoContabil;
        
      } else {
        
        $iCodigoContaCredito = $oLancamentoAuxiliar->getContaCredito();
        $iCodigoContaDebito  = $oLancamentoAuxiliar->getContaDebito();
        if ($oLancamentoAuxiliar->isEstorno()) {
          
          $iCodigoContaCredito = $oLancamentoAuxiliar->getContaDebito();
          $iCodigoContaDebito  = $oLancamentoAuxiliar->getContaCredito();
        }
        
        $oRegraLancamentoContabil = new RegraLancamentoContabil();
        $oRegraLancamentoContabil->setContaCredito($iCodigoContaCredito);
        $oRegraLancamentoContabil->setContaDebito($iCodigoContaDebito);
        return $oRegraLancamentoContabil;
      }

    }
      
    /**
     * Ordem do lancamento > 1 e apenas uma regra encontrada 
     */
    if ($oDaoTransacao->numrows == 1 && $oDadosTransacao->c46_ordem > 1) {
      return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
    }

    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    return false;
  }

}