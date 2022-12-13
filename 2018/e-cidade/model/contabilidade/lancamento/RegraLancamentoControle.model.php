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
/**
 * Class RegraLancamentoControle
 */
require_once "interfaces/IRegraLancamentoContabil.interface.php";

class RegraLancamentoControle implements IRegraLancamentoContabil {


  /**
   * Deve retornar qual uma instancia da RegraLancamento contendo as contas para efetuar o lançamento
   *
   * @param integer             $iCodigoDocumento
   * @param integer             $iCodigoLancamento
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   *
   * @throws Exception
   * @return RegraLancamentoContabil
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession('DB_anousu'), db_getsession('DB_instit'));
    $oLancamentos    = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);

    if (!$oLancamentos || count($oLancamentos->getRegrasLancamento()) == 0) {
      return false;
    }

    if (count($oLancamentos->getRegrasLancamento()) > 1) {
      throw new Exception("Mais de uma regra cadastrada para o documento {$iCodigoDocumento} - {$oEventoContabil->getDescricaoDocumento()} de ordem {$oLancamentos->getOrdem()}.");
    }

    $oRegra = $oLancamentos->getRegrasLancamento();
    return $oRegra[0];
  }
}