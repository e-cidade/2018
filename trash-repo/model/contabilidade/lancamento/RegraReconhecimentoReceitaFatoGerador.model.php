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

/**
 * Model que descobre as contas deverão ser debitados e creditados os valores.
 * @author matheus.felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.6 $
 */
class RegraReconhecimentoReceitaFatoGerador implements IRegraLancamentoContabil {
	
  public function __construct() {}
  
  /**
   * Retorna a regra (contas credito/debito) do lancamento
   * @see IRegraLancamentoContabil::getRegraLancamento()
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {
    
    $oDaoTransacao = db_utils::getDao('contranslr');
    $sWhere        = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere       .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere       .= " and c46_seqtranslan = {$iCodigoLancamento}";
    
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);
    $iLinhasLancamentoTransacao = $oDaoTransacao->numrows;
    
    if ($iLinhasLancamentoTransacao == 0) {
      throw new BusinessException("Nenhuma conta cadastrada para o documento {$iCodigoDocumento} lancamento: {$iCodigoLancamento}");
    }
    
    for ($iRowLancamento = 0; $iRowLancamento < $iLinhasLancamentoTransacao; $iRowLancamento++) {
    
      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $iRowLancamento);
      
      if ($oDadosTransacao->c46_ordem == 1) {
        
        $oRegraLancamento = new RegraLancamentoContabil();
        $oRegraLancamento->setContaCredito($oLancamentoAuxiliar->getCodigoContaCredito());
        $oRegraLancamento->setContaDebito($oLancamentoAuxiliar->getCodigoContaDebito());
        return $oRegraLancamento;
        
      } else {
      
        if ($oDadosTransacao->c47_compara == 0 && ($oDadosTransacao->c47_debito == 0 || $oDadosTransacao->c47_credito == 0)) {
      	  
      	  $sMensagemErro  = "A comparação, contas crédito e/ou débito do lançamento {$iCodigoLancamento} ";
      	  $sMensagemErro .= "estão definidas como 0 (zero). Ajuste as configurações deste lançamento.";
          throw new BusinessException($sMensagemErro);
        }
      
        if ($oDadosTransacao->c47_compara == 0) {
          return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
        } 
      }
    }
    
    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    return false;
  }

}