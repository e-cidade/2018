<?php

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Classe responsavel por criar a regra de lancamento de inscricao dos Restos a pagar
 * @author Iuri Guntchnigg
 * @package Contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.3 $
 * Class RegraLancamentoEncerramentoRP
 */
class RegraLancamentoEncerramentoVariacoesPatrimoniais implements IRegraLancamentoContabil {

  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);

    if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {
      return false;
    }

    $aRegrasDoLancamento = $oLancamentoEventoContabil->getRegrasLancamento();
    if (count($aRegrasDoLancamento) == 0) {
      return false;
    }

    $oRegraLancamento = $aRegrasDoLancamento[0];
    $oMovimentoConta  = $oLancamentoAuxiliar->getMovimentacaoContabil();
    $iContaEvento     = $oRegraLancamento->getContaDebito();
    if ($oLancamentoAuxiliar->getContaReferencia() != "") {
      $iContaEvento = $oLancamentoAuxiliar->getContaReferencia();
    }
    switch ($oMovimentoConta->getTipoSaldo()) {

      case 'D':

        $oRegraLancamento->setContaCredito($oMovimentoConta->getConta());
        $oRegraLancamento->setContaDebito($iContaEvento);
        break;
      case 'C':

        $oRegraLancamento->setContaCredito($iContaEvento);
        $oRegraLancamento->setContaDebito($oMovimentoConta->getConta());
        break;
    }

    /**
     * A conta sempre � modificada, nao podemos manter os dados no repositorio
     */
    EventoContabilRepository::removerEventoContabil($oEventoContabil);
    EventoContabilLancamentoRepository::removerEventoContabilLancamento($oLancamentoEventoContabil);
    return $oRegraLancamento;
  }

}