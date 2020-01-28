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
 * @author matheus felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.5 $
 */
class RegraArrecadacaoReceita implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $iAno                      = db_getsession("DB_anousu");
    $sChaveRegistryLancamentos = "{iCodigoDocumento}{$iCodigoLancamento}";
    $aDadosTransacao           = array();

    if (!$aDadosTransacao = DBRegistry::get($sChaveRegistryLancamentos)) {

      $oDaoTransacao = db_utils::getDao('contranslr');
      $sWhere        = "     c45_coddoc      = {$iCodigoDocumento}";
      $sWhere       .= " and c45_anousu      = {$iAno}";
      $sWhere       .= " and c46_seqtranslan = {$iCodigoLancamento}";

      $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
      $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);
      if ($oDaoTransacao->numrows > 0) {

        $aDadosTransacao = db_utils::getCollectionByRecord($rsTransacao);
        DBRegistry::add($sChaveRegistryLancamentos, $aDadosTransacao);
      }
    }

    $aTransacoes = array();
    foreach ($aDadosTransacao as $oDadosTransacao) {

      if ($oDadosTransacao->c46_ordem == 1 ) {

        if ($oDadosTransacao->c47_compara == 4 ) {

          if ($oDadosTransacao->c47_ref ==  $oLancamentoAuxiliar->getCodigoContaOrcamento()) {

            $iContaCredito = $oDadosTransacao->c47_credito;
            $iContaDebito  = $oLancamentoAuxiliar->getContaDebito();
            if ($oLancamentoAuxiliar->isEstorno()) {

              $iContaDebito  = $oDadosTransacao->c47_debito;
              $iContaCredito = $oLancamentoAuxiliar->getContaCredito();
            }
            $oRegraLancamentoContabil = new RegraLancamentoContabil();
            $oRegraLancamentoContabil->setContaCredito($iContaCredito);
            $oRegraLancamentoContabil->setContaDebito($iContaDebito);
            $aTransacoes[] = $oRegraLancamentoContabil;
          }
        } else {

          $oRegraLancamentoContabil = new RegraLancamentoContabil();
          $oRegraLancamentoContabil->setContaCredito($oLancamentoAuxiliar->getContaCredito());
          $oRegraLancamentoContabil->setContaDebito($oLancamentoAuxiliar->getContaDebito());
          $aTransacoes[] = $oRegraLancamentoContabil;
        }
      } else {

        $oEventoContabilLancamento = EventoContabilLancamentoRepository::getEventoByCodigo($oDadosTransacao->c46_seqtranslan);
        if (count($oEventoContabilLancamento->getRegrasLancamento()) > 1) {

          $sMensagemException  = "Mais de uma conta débito/crédito configurada para o ";
          $sMensagemException .= "lançamento [{$iCodigoLancamento}] de ordem {$oDadosTransacao->c46_ordem}.";
          throw new BusinessException($sMensagemException);
        }
          $aTransacoes[] = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      }
    }

    if (count($aTransacoes) > 1) {

      $sMensagemException  = "Mais de uma conta débito/crédito Encontradas para ";
      $sMensagemException .= "lançamento [{$iCodigoLancamento}] do documento {$iCodigoDocumento}.";
      throw new BusinessException($sMensagemException);
    }

    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    if (count($aTransacoes) == 0) {
      return false;
    }

    return $aTransacoes[0];
  }

}