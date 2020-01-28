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
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.5 $
 */
class RegraLancamentoLiquidacaoEmpenho implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $aDocumentosLiquidacaoRP = array(33, 34);
    $iAnoSessao              = db_getsession("DB_anousu");
    $oDaoTransacao           = db_utils::getDao('contranslr');
    $sWhere                  = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere                 .= " and c45_anousu      = {$iAnoSessao}";
    $sWhere                 .= " and c46_seqtranslan = {$iCodigoLancamento}";

    if (in_array($iCodigoDocumento, $aDocumentosLiquidacaoRP)) {
      $sWhere       .= " and c47_anousu = {$oLancamentoAuxiliar->getEmpenhoFinanceiro()->getAnoUso()}";
    }
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);

    if ($oDaoTransacao->erro_status == '0') {
      return false;
    }
    
    $iNumeroRegistros = $oDaoTransacao->numrows;
    if ($iNumeroRegistros == 1) {

      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
      $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      return $oRegraLancamentoContabil;
    }
    
    /**
     * Caso o lancamento tenha mais de uma conta configurada, devemos descobrir qual a conta que efetuaremos os 
     * lancamentos. Para isso comparamos as contas com base no COMPARA (c47_compara) da regra 
     */
    for ($i = 0; $i < $iNumeroRegistros; $i++) {
      
      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $i);
      
       //* Compara = 1  Debito
       //* Compara = 2  Crédito
        
      switch ($oDadosTransacao->c47_compara) {

        /**
         * criado case 0 para ser usado em ordem acima de um onde o reduzido do elenento nao precisa ser igual ao da conta
         * configurado na regra.
         */
        
        case 0:
          
          if ( $oDadosTransacao->c46_ordem > 1) {
            
            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            return $oRegraLancamentoContabil;
          }
          
        break;  
        
        
        case 1:
          
          if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_debito && $oDadosTransacao->c46_ordem == 1) {

            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            return $oRegraLancamentoContabil;
          }
          
          break;

        case 2:
          
          if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_credito)  {
          
            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            return $oRegraLancamentoContabil;
          }
          break;
      }

    }
    
    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    return false;
  }

}