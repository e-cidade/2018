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

require_once ("classes/materialestoque.model.php");

/**
 * Classe para lancamentos de empenhos em liquidacao
 * Realiza os lancamentos dos documentos do tipo 200, 201
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 */
class LancamentoEmpenhoEmLiquidacao {

  /**
   * Metodo que efetiva o lançaamento contabil creditando/debitando contas crédito/débito
   * @throws BusinessException
   * @param  LancamentoAuxiliar $oLancamentoAuxiliarEmLiquidacao stdClass com os dados do lancamento
   * @param  integer $iTipoDocumento tipo do documento
   * @return boolean
   */
  protected static function executarLancamentoContabil($oStdDadosLancamento, $iTipoDocumento) {

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iTipoDocumento);
    $oDocumentoContabil->setValorVariavel("[desdobramento]", $oStdDadosLancamento->iCodigoElemento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
    $oEventoContabil = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $aLancamento     = $oEventoContabil->getEventoContabilLancamento();
    if (count($aLancamento) == 0) {

      $sMensagemErro  = "Nenhum lancamento encontrado para o documento ";
      $sMensagemErro .= "{$iCodigoDocumentoExecutar} - {$oEventoContabil->getDescricaoDocumento()}.";
      throw new BusinessException($sMensagemErro);
    }

    $iCodigoHistorico = $aLancamento[0]->getHistorico();
    $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacao();
    if (in_array($iCodigoDocumentoExecutar, array(208, 209))) {

      $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacaoMaterialPermanente();
      $oLancamentoAuxiliarEmLiquidacao->setClassificacao($oStdDadosLancamento->oClassificacao);
    }

    if (in_array($iCodigoDocumentoExecutar, array(210, 211))) {

      $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado();
      $oLancamentoAuxiliarEmLiquidacao->setGrupoMaterial(new MaterialGrupo($oStdDadosLancamento->iCodigoGrupo));
      if ($iCodigoDocumentoExecutar == 211) {
        $oLancamentoAuxiliarEmLiquidacao->setSaida(true);
      }

    }
    $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico($oStdDadosLancamento->sObservacaoHistorico);
    $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oStdDadosLancamento->iFavorecido);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($oStdDadosLancamento->iCodigoElemento);
    $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oStdDadosLancamento->iNumeroEmpenho);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oStdDadosLancamento->iCodigoDotacao);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($oStdDadosLancamento->iCodigoNotaLiquidacao);
    $oLancamentoAuxiliarEmLiquidacao->setValorTotal($oStdDadosLancamento->nValorTotal);
    $oLancamentoAuxiliarEmLiquidacao->setHistorico($iCodigoHistorico);
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao);
    return true;
  }

  /**
   * Metodo responsavel pelo processamento do lancamento contabil na inclusao de uma ordem de compra
   * @param  stdClass $oStdDadosLancamento
   * @return boolean
   */
  public static function processaLancamento($oStdDadosLancamento) {

    self::executarLancamentoContabil($oStdDadosLancamento, 200);
    return true;
  }

  /**
   * Estorna o lancamento de uma entrada da ordem de compra
   * @param  stdClass $oStdDadosLancamento
   * @return boolean
   */
  public static function estornarLancamento($oStdDadosLancamento) {

    self::executarLancamentoContabil($oStdDadosLancamento, 201);
    return true;
  }

}