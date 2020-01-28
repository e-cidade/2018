<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Executa os lancamentos auxiliares da provisao de decimo terceiro
 * @author     Matheus Felini <matheus.felini@dbseller.com.br>
 * @package    contabilidade
 * @subpackage lancamento
 * @version    $Revision: 1.3 $
 */
class LancamentoAuxiliarProvisaoDecimoTerceiro extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Valor total do Lancamento
   * @var float
   */
  private $nValorTotal;

  /**
   * Codigo do historico
   * @var integer
   */
  private $iCodigoHistorico;

  /**
   * Observacao / Complemento
   * @var string
   */
  private $sObservacaoHistorico;

  /**
   * Mes da Provisao Ferias
   * @var integer
   */
  private $iMes;

  /**
   * Ano da Provisao Ferias
   * @var integer
   */
  private $iAno;
  
  /**
   * Codigo do cabecalho do lancamentos de provisao
   *
   * @var integer
   * @access private
   */
  private $iEscrituraProvisao;
  
  /**
   * Executa o Lancamento auxiliar
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoProvisaoDecimoTerceiro();
    return true;
  }

  /**
   * Vincula a a provisao de ferias a um lancamento
   * @throws BusinessException
   * @return boolean true
   */
  protected function salvarVinculoProvisaoDecimoTerceiro() {

    $oDaoLancamentoProvisaoDecimoTerceiro = db_utils::getDao('conlancamprovisaodecimoterceiro');
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_sequencial = null;
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_codlan     = $this->iCodigoLancamento;
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_instit     = db_getsession("DB_instit");
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_mes        = $this->iMes;
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_ano        = $this->iAno;
    $oDaoLancamentoProvisaoDecimoTerceiro->c100_escrituraprovisao = $this->iEscrituraProvisao;
    $oDaoLancamentoProvisaoDecimoTerceiro->incluir(null);
    if ($oDaoLancamentoProvisaoDecimoTerceiro->erro_status == "0") {
      throw new BusinessException("Não foi possível vincular a provisão de décimo terceiro.");
    }
    return true;
  }

  /**
   * Seta o mes da provisao de ferias
   * @param integer $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * Seta o ano da provisao de ferias
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Seta o valor total do evento
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Retorna o histórico da operação
   */
  public function getHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Seta o histórico da operação
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iCodigoHistorico = $iHistorico;
  }

  /**
   * Retorna a observação do histórico da operação
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }

  /**
   * Seta a observação do histórico da operação
   * @param string $sObservacaoHistorico
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }
  
  public function setCodigoEscrituraProvisao($iEscrituraProvisao) {
  	$this->iEscrituraProvisao = $iEscrituraProvisao;
  }
  
  public function getCodigoEscrituraProvisao() {
  	return $this->iEscrituraProvisao;
  }
  
}