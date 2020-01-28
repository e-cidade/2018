<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once modification("interfaces/IRegraLancamentoContabil.interface.php");

/**
 * Class RegraLancamentoControle
 */
class RegraLancamentoReconhecimentoCompetencia implements IRegraLancamentoContabil {


  /**
   * Deve retornar qual uma instancia da RegraLancamento contendo as contas para efetuar o lançamento
   *
   * @param integer             $iCodigoDocumento
   * @param integer             $iCodigoLancamento
   * @param ILancamentoAuxiliar|LancamentoAuxiliarReconhecimentoCompetencia $oLancamentoAuxiliar
   *
   * @throws Exception
   * @return RegraLancamentoContabil|boolean
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession('DB_anousu'), db_getsession('DB_instit'));
    $oLancamentos    = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);

    if (!$oLancamentos || count($oLancamentos->getRegrasLancamento()) == 0) {
      return false;
    }

    $aRegrasLancamento = $oLancamentos->getRegrasLancamento();
    if ($oLancamentos->getOrdem() > 1) {
      return $aRegrasLancamento[0];
    }

    switch ($iCodigoDocumento) {

      case 4000: /* inclusao */
      case 4001: /* estorno */

        if ($iCodigoDocumento == 4000) {
          $aRegrasLancamento[0]->setContaDebito($oLancamentoAuxiliar->getContaDebito()->getReduzido());
        } else {
          $aRegrasLancamento[0]->setContaCredito($oLancamentoAuxiliar->getContaDebito()->getReduzido());
        }
        break;

      case 4002: /* inclusao */
      case 4003: /* estorno */

        if ($iCodigoDocumento == 4002) {

          $aRegrasLancamento[0]->setContaDebito($oLancamentoAuxiliar->getContaDebito()->getReduzido());
          $aRegrasLancamento[0]->setContaCredito($oLancamentoAuxiliar->getContaCredito()->getReduzido());
        } else {

          $aRegrasLancamento[0]->setContaDebito($oLancamentoAuxiliar->getContaCredito()->getReduzido());
          $aRegrasLancamento[0]->setContaCredito($oLancamentoAuxiliar->getContaDebito()->getReduzido());
        }
        break;

    }
    return $aRegrasLancamento[0];
  }
}