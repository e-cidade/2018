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

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Salva os lan�amentos auxiliares do Acordo (contrato)
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.3 $
 */
class LancamentoAuxiliarAcordo extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;

  /**
   * Valor total do empenho
   * @var float
   */
  private $nValorTotal;

  /**
   * Acordo
   * @var Acordo
   */
  private $oAcordo;

  /**
   * Empenho Financeiro
   * @var EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * Executa os lan�amentos auxiliares dos Movimentos de uma liquidacao
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento - C�digo do Lancamento (conlancam)
   * @param date    $dtLancamento      - data do lancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    $this->setNumeroEmpenho($this->getEmpenho()->getNumero());
    $this->salvarVinculoAcordo();
    $this->salvarVinculoEmpenho();
    if ($this->getCodigoNotaLiquidacao() != "") {
      $this->salvarVinculoNotaDeLiquidacao();
    }
    return true;
  }

  /**
   * Salva o v�nculo do lan�amento com o acordo
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoAcordo() {

    $oDaoConLancamAcordo                 = db_utils::getDao('conlancamacordo');
    $oDaoConLancamAcordo->c87_sequencial = null;
    $oDaoConLancamAcordo->c87_codlan     = $this->iCodigoLancamento;
    $oDaoConLancamAcordo->c87_acordo     = $this->getAcordo()->getCodigoAcordo();
    $oDaoConLancamAcordo->incluir($this->iCodigoLancamento);
    if ($oDaoConLancamAcordo->erro_status == "0") {

      $sMsgErro = "N�o foi poss�vel salvar o v�nculo do acordo {$this->getAcordo()->getCodigoAcordo()} com o contrato.";
      $sMsgErro = $oDaoConLancamAcordo->erro_msg;
      throw new BusinessException($sMsgErro);
    }
    return true;
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
   * Seta o acordo que iremos lan�ar cont�bilmente
   * @param Acordo $oAcordo
   */
  public function setAcordo(Acordo $oAcordo) {
    $this->oAcordo = $oAcordo;
  }

  /**
   * Retorna o objeto Acordo
   * @return Acordo
   */
  public function getAcordo() {
    return $this->oAcordo;
  }

  /**
   * Seta o empenho do do acordo
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenhoFinanceiro = $oEmpenho;
  }

  /**
   * Retorna o empenho financeiro
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho() {
    return $this->oEmpenhoFinanceiro;
  }
  
  /**
   * Fun��o da classe que constroi uma inst�ncia de LancamentoAuxiliarAcordo, 
   * de acordo com c�digo do lan�amento, passado como par�metro
   * @param  integer $iCodigoLancamento
   * @return LancamentoAuxiliarAcordo
   */
  public static function getInstance($iCodigoLancamento) {
    
    $oDaoConlancamacordo = db_utils::getDao("conlancamacordo");
    $sCampos             = "c87_acordo, c75_numemp, c70_valor, c70_data, c66_codnota";
    $sSql                = $oDaoConlancamacordo->sql_query_dadoslancamento($iCodigoLancamento, $sCampos);
    $rsResultado         = $oDaoConlancamacordo->sql_record($sSql);
    
    if ($oDaoConlancamacordo->numrows != 1) {
      throw new BusinessException("Erro t�cnico: erro ao buscar os dados do lan�amento do acordo");
    }
    
    $oStdLancamentoAcordo  = db_utils::fieldsMemory($rsResultado, 0); 
    $iAcordo               = $oStdLancamentoAcordo->c87_acordo;
    $iEmpenho              = $oStdLancamentoAcordo->c75_numemp;
    $nValorTotal           = $oStdLancamentoAcordo->c70_valor;
    $dtLancamento          = $oStdLancamentoAcordo->c70_data;
    $iCodigoNotaLiquidacao = $oStdLancamentoAcordo->c66_codnota;
    
    /**
     * Seta as propriedades para criar uma inst�ncia da classe, de acordo com dados do lan�amento
     */
    $oLancamento  = new LancamentoAuxiliarAcordo();
    $oAcordo      = new Acordo($iAcordo);
    $oEmpenho     = new EmpenhoFinanceiro($iEmpenho);
    $oLancamento->setAcordo($oAcordo);
    $oLancamento->setEmpenho($oEmpenho);
    $oLancamento->setValorTotal($nValorTotal);
    $oLancamento->setCodigoLancamento($iCodigoLancamento);
    $oLancamento->setDataLancamento($dtLancamento);
    
    if (!empty($iCodigoNotaLiquidacao)) {
      $oLancamento->setCodigoNotaLiquidacao($iCodigoNotaLiquidacao);
    }
    return $oLancamento;
  }
}
?>