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
 * Executa os lancamentos auxiliares da provisao de ferias
 * @author     Matheus Felini <matheus.felini@dbseller.com.br>
 * @package    contabilidade
 * @subpackage lancamento
 * @version    $Revision: 1.3 $
 */
class LancamentoAuxiliarProvisaoFerias extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

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
    $this->salvarVinculoProvisaoFerias();
    return true;
  }

  /**
   * Vincula a a provisao de ferias a um lancamento
   * @throws BusinessException
   * @return boolean true
   */
  protected function salvarVinculoProvisaoFerias() {

    $oDaoLancamentoProvisaoFerias = db_utils::getDao('conlancamprovisaoferias');

    $oDaoLancamentoProvisaoFerias->c101_sequencial        = null;
    $oDaoLancamentoProvisaoFerias->c101_codlan            = $this->iCodigoLancamento;
    $oDaoLancamentoProvisaoFerias->c101_instit            = db_getsession("DB_instit");
    $oDaoLancamentoProvisaoFerias->c101_mes               = $this->iMes;
    $oDaoLancamentoProvisaoFerias->c101_ano               = $this->iAno;
    $oDaoLancamentoProvisaoFerias->c101_escrituraprovisao = $this->iEscrituraProvisao;
    $oDaoLancamentoProvisaoFerias->incluir(null);

    if ($oDaoLancamentoProvisaoFerias->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel vincular a provis�o de f�rias ao lan�amento.");
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
   * Retorna o hist�rico da opera��o
   */
  public function getHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Seta o hist�rico da opera��o
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iCodigoHistorico = $iHistorico;
  }

  /**
   * Retorna a observa��o do hist�rico da opera��o
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }

  /**
   * Seta a observa��o do hist�rico da opera��o
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