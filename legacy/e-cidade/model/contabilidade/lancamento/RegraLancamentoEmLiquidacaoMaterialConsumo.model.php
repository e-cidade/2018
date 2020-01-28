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

require_once ("interfaces/IRegraLancamentoContabil.interface.php");
require_once ("model/contabilidade/EventoContabil.model.php");
require_once ("model/contabilidade/EventoContabilLancamento.model.php");

/**
 * Verifica a regra de lançamentos para o movimento em liquidacao de materiais permanentes.
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @author Matheus Felini  matheus.felini@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.11 $
 */
class RegraLancamentoEmLiquidacaoMaterialConsumo implements IRegraLancamentoContabil {

  /**
   * Retorna um objeto RegraLancamentoContabil
   * @see IRegraLancamentoContabil::getRegraLancamento()
   * @param integer $iCodigoDocumento  - Documento contabil
   * @param integer $iCodigoLancamento - Codigo do lancamento contabil
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return RegraLancamentoContabil
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);
    if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {

      $oStdDadosMensagem                      = new stdClass();
      $oStdDadosMensagem->codigo_documento    = $iCodigoDocumento;
      $oStdDadosMensagem->descricao_documento = $oEventoContabil->getDescricaoDocumento();
      throw new BusinessException(_M('financeiro.contabilidade.RegraLancamentoContabil.documento_nao_encontrado',
                                  $oStdDadosMensagem));
    }

    $aRegrasDoLancamento         = $oLancamentoEventoContabil->getRegrasLancamento();

    if ( ! $oLancamentoAuxiliar->getGrupoMaterial()->getContaAtivo() instanceof ContaPlano ) {

      $oStdDadosMensagem                       = new stdClass();
      $oStdDadosMensagem->codigo_documento     = $iCodigoDocumento;
      $oStdDadosMensagem->descricao_documento  = $oEventoContabil->getDescricaoDocumento();
      $oStdDadosMensagem->codigo_lancamento    = $oLancamentoEventoContabil->getSequencialLancamento();;
      $oStdDadosMensagem->descricao_lancamento = $oLancamentoEventoContabil->getDescricao();
      $oStdDadosMensagem->empenho              = $oLancamentoAuxiliar->getNumeroEmpenho();

      $sLocalizacaoMensagemErro  = 'financeiro.contabilidade.RegraLancamentoEmLiquidacaoMaterialConsumo';
      $sLocalizacaoMensagemErro .= '.reduzido_nao_encontrado';

      throw new BusinessException(_M($sLocalizacaoMensagemErro, $oStdDadosMensagem));

    }

    $iContaContabilGrupoMaterial = $oLancamentoAuxiliar->getGrupoMaterial()->getContaAtivo()->getReduzido();

    $aRegrasEncontradas          = array();
    foreach ($aRegrasDoLancamento as $oRegraLancamento) {

      if ($oLancamentoEventoContabil->getOrdem() == 1) {

      	if ($oEventoContabil->inclusao()) {

      		$oRegraLancamento->setContaDebito($iContaContabilGrupoMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}

      	if ($oEventoContabil->estorno()) {

      		$oRegraLancamento->setContaCredito($iContaContabilGrupoMaterial);
      		$aRegrasEncontradas[] = $oRegraLancamento;
      	}

      } else {
        $aRegrasEncontradas[] = $oRegraLancamento;
      }
    }

    $oStdDadosMensagem                       = new stdClass();
    $oStdDadosMensagem->codigo_documento     = $iCodigoDocumento;
    $oStdDadosMensagem->descricao_documento  = $oEventoContabil->getDescricaoDocumento();
    $oStdDadosMensagem->codigo_lancamento    = $oLancamentoEventoContabil->getSequencialLancamento();;
    $oStdDadosMensagem->descricao_lancamento = $oLancamentoEventoContabil->getDescricao();
    $oStdDadosMensagem->codigo_conta         = $iContaContabilGrupoMaterial;
    $oStdDadosMensagem->descricao_conta      = $oLancamentoAuxiliar->getGrupoMaterial()->getContaAtivo()->getDescricao();

    /**
     * Verificamos se não foram identificadas mais de uma conta crédito/débito para o lançamento de ordem 1.
     */
    if (count($aRegrasEncontradas) > 1 && $oLancamentoEventoContabil->getOrdem() == 1) {

      $sLocalizacaoMensagemErro  = 'financeiro.contabilidade.RegraLancamentoEmLiquidacaoMaterialConsumo';
      $sLocalizacaoMensagemErro .= '.multiplas_contas_debito_credito';
      throw new BusinessException(_M($sLocalizacaoMensagemErro, $oStdDadosMensagem));
    }

    /**
     * Não encontrou regra de lancamento para o documento
     */
    if (count($aRegrasEncontradas) == 0) {
      return false;
    }

    return $aRegrasEncontradas[0];
  }
}