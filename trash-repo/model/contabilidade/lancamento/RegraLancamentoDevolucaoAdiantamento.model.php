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
 * Retorna a regra cadastrada para a arrecada��o de receita
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.18 $ 
 */
class RegraLancamentoDevolucaoAdiantamento implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oDaoTransacao = db_utils::getDao('contranslr');
    $sWhere        = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere       .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere       .= " and c46_seqtranslan = {$iCodigoLancamento}";
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);

    if ($oDaoTransacao->erro_status == "0") {
      return false;
    }    

    /**
     * @todo
     * refatorar
     * descobrir quais lugares utilizam essa regra e ent�o fazer com que o lan�amento auxiliar passa o objeto EmpenhoFinanceiro
     */
    if (method_exists($oLancamentoAuxiliar, "getNumeroEmpenho") && $oLancamentoAuxiliar->getNumeroEmpenho() != '' ) {
      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oLancamentoAuxiliar->getNumeroEmpenho());
    } else if (method_exists($oLancamentoAuxiliar, "getEmpenhoFinanceiro") && $oLancamentoAuxiliar->getEmpenhoFinanceiro() != '' ) {
      $oEmpenhoFinanceiro = $oLancamentoAuxiliar->getEmpenhoFinanceiro();
    } else {
      throw new Exception("N�o encontrou objeto EmpenhoFinanceiro.");
    }

    $oStdPrestacaoContas = $oEmpenhoFinanceiro->getDadosPrestacaoContas();
    if ( ! $oStdPrestacaoContas) {

      $sCodigoEmpenho = "{$oEmpenhoFinanceiro->getCodigo()}/{$oEmpenhoFinanceiro->getAnoUso()}";
      $sMsgErro       = "O empenho {$sCodigoEmpenho} n�o esta configurado como tipo presta��o de contas.";
      throw new BusinessException($sMsgErro);
    }
    
    $iCodigoPrestacaoContas = $oStdPrestacaoContas->e45_tipo;

    /**
     * Documentos de estorno que s�o tratados por esse programa
     */
    $aDocumentosEstorno = array(91, 415, 417);

    for ($iRowTransacao = 0; $iRowTransacao < $oDaoTransacao->numrows; $iRowTransacao++) {

      $oDadosTransacao =  db_utils::fieldsMemory($rsTransacao, $iRowTransacao);

      if ($oDadosTransacao->c46_ordem == 1) {

        $oRegraLancamento = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      	
      	$iCodigoReduzido = $oEmpenhoFinanceiro->getContaOrcamento()->getPlanoContaPCASP()->getReduzido();
      	$sCampoCompara   = $oDadosTransacao->c47_debito;

        /**
         * Ajustado para verificar o c�digo do documento para sabermos se � estorno ou n�o
         */
      	if ( in_array($iCodigoDocumento, $aDocumentosEstorno) ) {
      		
          $oRegraLancamento->setContaCredito($iCodigoReduzido); 

          if ($iCodigoDocumento === 417 && $oLancamentoAuxiliar->arrecadacaoEmpenhoPrestacaoContas()) {
            $oRegraLancamento->setContaCredito($oLancamentoAuxiliar->getCodigoContaBancoArrecadacaoCorrente());
          }

      	} else {

          $oRegraLancamento->setContaDebito($iCodigoReduzido); 
          if ($iCodigoDocumento === 416 && $oLancamentoAuxiliar->arrecadacaoEmpenhoPrestacaoContas()) {
            $oRegraLancamento->setContaDebito($oLancamentoAuxiliar->getCodigoContaBancoArrecadacaoCorrente());
          }

        }     	

      	if ( ($oDadosTransacao->c47_compara == 6) && ($oDadosTransacao->c47_ref == $iCodigoPrestacaoContas) ) {
          return $oRegraLancamento;
        }
        
      } else {
        return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      }
    }


    /**
     * N�o encontrou regra de lancamento para o documento
     */
    return false;
  }

}