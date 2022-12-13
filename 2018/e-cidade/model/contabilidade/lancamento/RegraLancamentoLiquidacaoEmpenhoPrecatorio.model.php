<?php
/*
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

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));

/**
 * Retorna a regra cadastrada para lançacamento de empenho de precatorios
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.1 $
 */
class RegraLancamentoLiquidacaoEmpenhoPrecatorio implements IRegraLancamentoContabil {


  private $iCodigoDocumento;
  /**
   * @param int                  $iCodigoDocumento
   * @param int                  $iCodigoLancamento
   * @param LancamentoAuxiliarEmpenhoLiquidacao|\ILancamentoAuxiliar $oLancamentoAuxiliar
   *
   * @return bool|\RegraLancamentoContabil
   * @throws \Exception
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $this->iCodigoDocumento  = $iCodigoDocumento;
    $iAnoSessao              = db_getsession("DB_anousu");

    $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($this->iCodigoDocumento, $iAnoSessao, db_getsession('DB_instit'));
    foreach ($oEventoContabil->getEventoContabilLancamento() as $oLancamento) {

      $aRegras = $oLancamento->getRegrasLancamento();
      if (count($aRegras) > 1) {
        throw new Exception("Foram encontradas mais de uma conta crédito/débito para a execução do lançamento contábil {$oEventoContabil->getDescricaoDocumento()}.");
      }
      return $aRegras[0];
    }
    return false;
  }
}