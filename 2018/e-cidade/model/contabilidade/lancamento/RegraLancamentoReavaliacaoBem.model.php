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

/**
 * Retorna as contas de lancamento de reavaliacao dos bens
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version $Revision: 1.3 $
 * @package contabilidade
 * @subpackage lancamentos
 */
require_once ("interfaces/IRegraLancamentoContabil.interface.php");

class RegraLancamentoReavaliacaoBem implements IRegraLancamentoContabil {

  /**
   * Deve retornar qual uma instancia da RegraLancamento, contendo as contas para efetuar o lançamento
   * @param  integer             $iCodigoDocumento
   * @param  integer             $iCodigoLancamento
   * @param  ILancamentoAuxiliar $oLancamentoAuxiliar
   * @throws BusinessException
   * @return RegraLancamentoContabil
   **/
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);  

    if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {
      return false;
    }         

    $aRegrasDoLancamento = $oLancamentoEventoContabil->getRegrasLancamento();    
    $iConta              = $oLancamentoAuxiliar->getContaCredito();
    $aContasEncontradas  = array();

    foreach ($aRegrasDoLancamento as  $oRegraLancamentoContabil ) {

      if ( $oLancamentoEventoContabil->getOrdem() == 1 ) {

        switch ( $iCodigoDocumento ) {

          case 600 :
            $oRegraLancamentoContabil->setContaDebito($iConta);
          break;

          /**
           * Estorno 
           */
          case 601:
            $oRegraLancamentoContabil->setContaCredito($iConta);
          break;

          case 602:
            $oRegraLancamentoContabil->setContaCredito($iConta);
          break;

          /**
           * Estorno 
           */
          case 603;
            $oRegraLancamentoContabil->setContaDebito($iConta);
          break;
        }

        $aContasEncontradas[] = $oRegraLancamentoContabil;
      } else {
        $aContasEncontradas[] = $oRegraLancamentoContabil;
      }
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