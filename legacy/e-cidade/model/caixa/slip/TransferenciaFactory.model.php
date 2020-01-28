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

/**
 * Factory para criação de objetos da família Transferência
 * @author dbbruno.silva
 */
class TransferenciaFactory {

  /**
   * @param integer $iTransferenciaTipo
   * @param integer $iCodigoSlip
   *
   * @return Transferencia
   */
  public static function getInstance($iTransferenciaTipo = null, $iCodigoSlip = null) {

  	if (empty($iTransferenciaTipo)) {

  		$oDaoSlipTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');

  		$sSqlSlipTipoOperacaoVinculo = $oDaoSlipTipoOperacaoVinculo->sql_query(null, "k153_slipoperacaotipo", null, "k153_slip = {$iCodigoSlip}");
  		$rsSlipTipoOperacaoVinculo   = $oDaoSlipTipoOperacaoVinculo->sql_record($sSqlSlipTipoOperacaoVinculo);
  		if (!USE_PCASP && !empty($iCodigoSlip)) {
  		  return new TransferenciaBancaria($iCodigoSlip);
  		}
  		$iTransferenciaTipo          = db_utils::fieldsMemory($rsSlipTipoOperacaoVinculo, 0)-> k153_slipoperacaotipo;
  	}


    switch ($iTransferenciaTipo) {

      case 1:
    	case 2:
    	case 3:
    	case 4:

        require_once("model/caixa/slip/TransferenciaFinanceira.model.php");
    	  $oTransferenciaFinanceira = new TransferenciaFinanceira($iCodigoSlip);
    	  $oTransferenciaFinanceira->setTipoOperacao($iTransferenciaTipo);
        return $oTransferenciaFinanceira;
      break;

    	case 5:
    	case 6:

        require_once("model/caixa/slip/TransferenciaBancaria.model.php");
        $oTransferenciaBancaria = new TransferenciaBancaria($iCodigoSlip);
        $oTransferenciaBancaria->setTipoOperacao($iTransferenciaTipo);
        return $oTransferenciaBancaria;

        break;

    	case 7:
    	case 8:
    	case 9:
    	case 10:

        require_once("model/caixa/slip/Caucao.model.php");
        $oTransferenciaCaucao = new Caucao($iCodigoSlip);
        $oTransferenciaCaucao->setTipoOperacao($iTransferenciaTipo);
        return $oTransferenciaCaucao;

        break;

    	case 11:
    	case 12:
    	case 13:
    	case 14:

        require_once("model/caixa/slip/DepositoDiversos.model.php");
        $oDepositoDiversos = new DepositoDiversos($iCodigoSlip);
        $oDepositoDiversos->setTipoOperacao($iTransferenciaTipo);
        return $oDepositoDiversos;
      break;

    	default:

    	  require_once("model/caixa/slip/TransferenciaBancaria.model.php");
    	  $oTransferenciaBancaria = new TransferenciaBancaria($iCodigoSlip);
    	  $oTransferenciaBancaria->setTipoOperacao($iTransferenciaTipo);
    	  return $oTransferenciaBancaria;
    	  break;

    }
  }
}


?>