<?php

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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
     * A conta sempre é modificada, nao podemos manter os dados no repositorio
     */
    EventoContabilRepository::removerEventoContabil($oEventoContabil);
    EventoContabilLancamentoRepository::removerEventoContabilLancamento($oLancamentoEventoContabil);
    return $oRegraLancamento;
  }

}