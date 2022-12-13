<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("interfaces/IRegraLancamentoContabil.interface.php");
/**
 * Class RegraLancamentoSuprimentoDeFundos
 */
class RegraLancamentoSuprimentoDeFundos implements IRegraLancamentoContabil {

  /**
   * @param int $iCodigoDocumento
   * @param int $iCodigoLancamento
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return RegraLancamentoContabil|void
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $aLancamentosContabeis = $oEventoContabil->getEventoContabilLancamento();

    /**
     * Percorremos os lan�amentos encontrados para o documento
     */
    foreach ($aLancamentosContabeis as $oLancamentoContabil) {

      /**
       * Buscamos as regras encontradas para o lan�amento (conta cr�dito / d�bito)
       */
      $aRegrasContabeis = $oLancamentoContabil->getRegrasLancamento();

      if ($oLancamentoContabil->getOrdem() == 1) {

        /**
         * Caso seja ordem um, percorremos as regras cadastradas para retornar ao usu�rio uma que tenha o
         * mesmo tipo de compara��o e tipo de presta��o de contas do empenho
         */
        foreach ($aRegrasContabeis as $oRegraContabil) {

          $oStdDadosPrestacaoContas = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getDadosPrestacaoContas();
          if ($oRegraContabil->getCompara() == RegraLancamentoContabil::COMPARA_PRESTACAO_CONTA &&
              $oStdDadosPrestacaoContas && $oStdDadosPrestacaoContas->e45_tipo == $oRegraContabil->getReferencia() ) {

            return $oRegraContabil;
          }
        }
        return false;
      }
      return $aRegrasContabeis[0];
    }

    return false;
  }
}