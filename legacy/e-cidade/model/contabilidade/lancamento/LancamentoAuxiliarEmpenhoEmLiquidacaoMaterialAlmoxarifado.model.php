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

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoLiquidacao.model.php");

/**
 * Model que executa os lancamentos auxiliares do movimento em liquidacao de material de almoxarifado
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.9 $
 */
class LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado
      extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Colecao dos itens de um empenho
   * @var EmpenhoFinanceiroItem
   */
  protected $aItens = array();

  /**
   * Grupo do material do estoque
   * @var MaterialGrupo
   */
  protected $oMaterialGrupo;

  /**
   * Propriedade que controla se o lançamento é lançamento de saída
   * @var boolean
   */
  protected $lSaida = false;

  /**
   * Codigo do historico
   * 
   * @var integer
   * @access protected
   */
  protected $iHistorico;

  /**
   * Retorna os itens do empenho
   * @return EmpenhoFinanceiroItem
   */
  public function getItensEmpenho() {
    return $this->aItens;
  }

  /**
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    $this->salvarVinculoComplemento();
    $this->salvarVinculoCgm();
    $this->salvarVinculoElemento();
    $this->salvarVinculoEmpenho();
    $this->salvarVinculoDotacao();
    $this->salvarVinculoNotaDeLiquidacao();
    return true;
  }

  /**
   * Define os itens do empenho
   * @param array $aItens
   */
  public function setItensEmpenho(Array $aItens) {
    $this->aItens = $aItens;
  }

  /**
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * @see ILancamentoAuxiliar::getValorTotal()
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Seta o codigo da ordem de pagamento
   * @param integer $iCodigoOrdemPagameanto
   */
  public function setCodigoOrdemPagamento($iCodigoOrdemPagameanto){
    $this->iCodigoOrdemPagameanto = $iCodigoOrdemPagameanto;
  }

  /**
   * Retorna o codigo da ordem de pagamento
   * @return integer
   */
  public function getCodigoOrdemPagamento() {
    return $this->iCodigoOrdemPagameanto;
  }

  /**
   * Seta o grupo do material
   * @param MaterialGrupo $oMaterialGrupo
   */
  public function setGrupoMaterial(MaterialGrupo $oMaterialGrupo) {
    $this->oMaterialGrupo = $oMaterialGrupo;
  }

  /**
   * Retorna um grupo do material
   * @return MaterialGrupo
   */
  public function getGrupoMaterial() {
    return $this->oMaterialGrupo;
  }

  /**
   * Seta se o lançamento é de saída
   * @param boolean $lSaida
   */
  public function setSaida($lSaida) {
    $this->lSaida = $lSaida;
  }

  /**
   * Retorna se o lançamento é de saida
   * @return boolean
   */
  public function isSaida() {
    return $this->lSaida;
  }
  
  public static function getInstance($iCodigoLancamento) {

    $sCamposSql  = "c75_numemp                ";
    $sCamposSql .= ",m72_codnota              "; 
    $sCamposSql .= ",m66_materialestoquegrupo ";
    $sCamposSql .= ",c75_numemp               ";
    $sCamposSql .= ",c70_valor                ";
    $sCamposSql .= ",m66_codcon               ";
    $sCamposSql .= ",m72_codnota              ";
    $sCamposSql .= ",c72_complem , c71_coddoc ";

    $oDaoConlancam = db_utils::getDao("conlancam");
    $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, "distinct $sCamposSql ", null, "c70_codlan = {$iCodigoLancamento} ");
    $rsConlanCam   = $oDaoConlancam->sql_record($sSqlConlancam);

    if ($oDaoConlancam->numrows == 0) {
      throw new BusinessException("ERRO[0] - Dados do lancamneto {$iCodigoLancamento} não encontrado.");
    }

    $oDadosLancamento   = db_utils::fieldsMemory($rsConlanCam, 0);
    $lEstorno           = false;
    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosLancamento->c75_numemp);
    $oNota              = new NotaLiquidacao($oDadosLancamento->m72_codnota);
    $aItensNota         = $oNota->getItens();
    if ($oDadosLancamento->c71_coddoc == 211) {
      $lEstorno = true;
    }
    $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado();
    $oLancamentoAuxiliarEmLiquidacao->setFavorecido          ($oEmpenhoFinanceiro->getCgm()->getCodigo());
    $oLancamentoAuxiliarEmLiquidacao->setGrupoMaterial       (new MaterialGrupo($oDadosLancamento->m66_materialestoquegrupo));
    $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho       ($oDadosLancamento->c75_numemp);
    $oLancamentoAuxiliarEmLiquidacao->setValorTotal          ($oDadosLancamento->c70_valor);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento      ($oDadosLancamento->m66_codcon);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($oDadosLancamento->m72_codnota);
    $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico ($oDadosLancamento->c72_complem);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao       ($oEmpenhoFinanceiro->getDotacao()->getCodigo());
    $oLancamentoAuxiliarEmLiquidacao->setItensEmpenho        ($aItensNota);
    $oLancamentoAuxiliarEmLiquidacao->setSaida($lEstorno);

    /**
     * Dados para conta corrente credor e despesa
     */
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
    $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
    $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
    $oLancamentoAuxiliarEmLiquidacao->setContaCorrenteDetalhe($oContaCorrenteDetalhe); 

    return $oLancamentoAuxiliarEmLiquidacao;
  }

}
