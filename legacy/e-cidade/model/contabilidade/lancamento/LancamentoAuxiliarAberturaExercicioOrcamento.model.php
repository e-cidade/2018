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
 
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

/**
 * Model que executa os lancamentos auxiliares para Abertura de Exercicio Orcamento
 * @author     Bruno Silva
 * @package    contabilidade
 * @subpackage lancamento
 * @version    1.0 $
 */
class LancamentoAuxiliarAberturaExercicioOrcamento extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  const URL_MENSAGENS = "financeiro.contabilidade.LancamentoAuxiliarAberturaExercicioOrcamento.";

  /**
   * chave aberturaexercicioorcamento
   * @var integer
   */
  private $iAberturaExercicioOrcamento;
  
  /**
   * Código do Historico
   * @var integer
   */
  private $iHistorico;
  
  /**
   * Valor Total do Lançamento
   * @var double
   */
  private $nValorTotal;
  
  /**
   * Conta Credito
   * @var integer
   */
  private $iContaCredito;
  
  /**
   * Conta Debito
   * @var integer
   */
  private $iContaDebito;

  /**
   * @var Dotacao
   */
  private $oDotacao;

  /**
   * Cria o vínculo entre o lançamento e a dotação.
   * @throws DBException
   * @throws ParameterException
   */
  protected function vinculaLancamentoDotacao() {

    if (empty($this->iCodigoLancamento)) {
      throw new ParameterException(_M(self::URL_MENSAGENS . 'codigo_lancamento_obrigatorio'));
    }

    if (empty($this->dtLancamento)) {
      throw new ParameterException(_M(self::URL_MENSAGENS . 'data_lancamento_obrigatorio'));
    }

    $oDotacao = $this->getDotacao();
    if (empty($oDotacao)) {
      return;
    }

    $oDaoConLancamDot = new cl_conlancamdot();
    $oDaoConLancamDot->c73_codlan = $this->iCodigoLancamento;
    $oDaoConLancamDot->c73_anousu = $oDotacao->getAno();
    $oDaoConLancamDot->c73_coddot = $oDotacao->getCodigo();
    $oDaoConLancamDot->c73_data   = $this->dtLancamento;
    if (!$oDaoConLancamDot->incluir($this->iCodigoLancamento)) {
      throw  new DBException(_M(self::URL_MENSAGENS . 'erro_lancamento_dotacao'));
    }
  }

  /**
   * metodo que executao lancamento
   * lançar registros em: 
   *   - conlancamaberturaexercicioorcamento
       - conlancamcompl
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {
    
    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoAberturaExercicioOrcamento();

    /* [Extensao] - AberturaOrcamentoDespesa */

    return true;
  }
  
  /**
   * Salva vinculo da abertua do exercicio com orcamento
   * @throws DBException - erro de sql na incluir
   */
  public function salvarVinculoAberturaExercicioOrcamento() {

    $oDaoConLancamAberturaExercicio = db_utils::getDao('conlancamaberturaexercicioorcamento');
    $oDaoConLancamAberturaExercicio->c105_aberturaexercicioorcamento = $this->getAberturaExercicioOrcamento();
    $oDaoConLancamAberturaExercicio->c105_codlan                     = $this->iCodigoLancamento;
    $oDaoConLancamAberturaExercicio->c105_sequencial = null;
    $oDaoConLancamAberturaExercicio->incluir(null);
    
    if ($oDaoConLancamAberturaExercicio->erro_status == "0") {
      throw new DBException("Não foi possível salvar o vínculo da abertura de exercicio com o lançamento.");
    }
    
    return true;
  }

  /**
   * Seta o codigo chave aberturaexercicioorcamento
   * @param integer $iAberturaExercicioOrcamento
   */
  public function setAberturaExercicioOrcamento($iAberturaExercicioOrcamento) {
    $this->iAberturaExercicioOrcamento = $iAberturaExercicioOrcamento;
  }
  
  /**
   * Retorna o codigo do  chave aberturaexercicioorcamento
   * @return integer $iAberturaExercicioOrcamento
   */
  public function getAberturaExercicioOrcamento() {
    return $this->iAberturaExercicioOrcamento;
  }
  
  /**
   * Define o valor total
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;
  }
  
  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal(){
    return $this->nValorTotal;
  }
  
  /**
   * Retorna o histórico da operação
   */
  public function getHistorico(){
    return $this->iHistorico;
  }
  
  /**
   * Seta o histórico da operação
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico){
    $this->iHistorico = $iHistorico;    
  }

  /**
   * Seta valor para a conta Debito
   *
   * @param $iContaDebito
   */
  public function setContaDebito($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }
  
  /**
   * Seta valor para a conta credito
   * @param integer $iContaCredito
   */
  public function setContaCredito($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * retorna valor para a conta debito
   * @return integer $iContaDebito
   */
  public function getContaDebito() {
    return $this->iContaDebito;
  }
  
  /**
   * retorna valor para a conta debito
   * @return integer $iContaCredito
   */
  public function getContaCredito() {
    return $this->iContaCredito;
  }

  /**
   * @return Dotacao
   */
  private function getDotacao() {
    return $this->oDotacao;
  }

  /**
   * @param Dotacao $oDotacao
   */
  public function setDotacao(Dotacao $oDotacao) {
    $this->oDotacao = $oDotacao;
  }

}