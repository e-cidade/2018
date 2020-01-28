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
/**
 * Model que executa os lancamentos auxiliares do movimento em liquidacao de material permanente
 * @author Matheus Felini matheus.felini@dbseller.com.br
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.7 $
 */
class LancamentoAuxiliarEmLiquidacaoMaterialPermanente extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

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
   * Sequencial da ordem de pagamento
   * @var integer
   */
  private $iCodigoOrdemPagameanto;

 /**
  * Classificacao do bem
  * @var integer
  */
  private $oClassificacao;

  /**
   * Executa os lançamentos auxiliares dos Movimentos de uma liquidacao
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento - Código do Lancamento (conlancam)
   * @param date    $dtLancamento      - data do lancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    parent::salvarVinculoCgm();
    parent::salvarVinculoElemento();
    parent::salvarVinculoEmpenho();
    parent::salvarVinculoDotacao();
    $this->salvarVinculoNotaDeLiquidacao();
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
   * Define a classificação
   * A classificacao definida é necessária para a escolha das contas crédito/débito do segundo lançamento
   * @param BemClassificacao $oClassificacao Classificação
   */
  public function setClassificacao(BemClassificacao $oClassificacao) {
    $this->oClassificacao = $oClassificacao;
  }

  /**
   * Retorna a classificao do bem
   * @return BemClassificacao classificacao do bem
   */
  public function getClassificacao() {
    return $this->oClassificacao;
  }

  /**
   * Função da classe que constroi uma instância de LancamentoAuxiliarEmLiquidacaoMaterialPermanente, 
   * de acordo com código do lançamento, passado como parâmetro
   * @param  integer $iCodigoLancamento
   * @return LancamentoAuxiliarEmLiquidacaoMaterialPermanente
   */
  public static function getInstance($iCodigoLancamento) {

    $oDaoConlancam  = db_utils::getDao("conlancam");
    $sWhere = "c70_codlan = $iCodigoLancamento";
    $sSqlLancamento = $oDaoConlancam->sql_query_empenho_lancamento("c70_valor, c75_numemp", null, $sWhere);
    $rsLancamento   = $oDaoConlancam->sql_record($sSqlLancamento);

    if ($oDaoConlancam->numrows != 1) {
      throw new BusinessException("Erro ao buscar os dados do lançamento.");
    }

    $oLancamento = db_utils::fieldsMemory($rsLancamento, 0);
    
    $oDaoBensNota = db_utils::getDao("bensempnotaitem");
    $sSqlBuscaBem = $oDaoBensNota->sql_query_bens_nota( null, 
                                                        "distinct t52_bem,                     \n"
                                                        . " (select t43_codlote                \n"
                                                        . "    from benslote                   \n"
                                                        . "   where t43_bem = t52_bem) as lote \n", 
                                                        " t52_bem limit 1", 
                                                        "e69_numemp = {$oLancamento->c75_numemp}" );
    $rsBuscaBem = $oDaoBensNota->sql_record($sSqlBuscaBem);

    if ($oDaoBensNota->numrows == 0) {
      throw new Exception("Bem não encontrado para o empenho {$oLancamento->c75_numemp}");
    }

    $oDadosBem    = db_utils::fieldsMemory($rsBuscaBem, 0);
    $oDaoBensLote = db_utils::getDao("benslote");

    if (!empty($oDadosBem->lote)) {
      
      $sSqlBensLoteAltIndividual = $oDaoBensLote->sql_query(null, "distinct t52_codcla", null, "t43_codlote = {$oDadosBem->lote}");
      $rsBensLoteAltIndividual   = $oDaoBensLote->sql_record($sSqlBensLoteAltIndividual);
      
      if ($oDaoBensLote->numrows > 1) {

        $oErro        = new Exception("Alguns bens do lote {$oDadosBem->lote} foram alterados individualmente. Procedimento abortado.", 208);
        $oErro->iLote = $oDadosBem->lote;
        throw $oErro;
      }
    }

    $oBem = new Bem($oDadosBem->t52_bem);

    $oLancamentoAuxiliar = new LancamentoAuxiliarEmLiquidacaoMaterialPermanente();
    $oLancamentoAuxiliar->setValorTotal($oLancamento->c70_valor);
    $oLancamentoAuxiliar->setClassificacao($oBem->getClassificacao());

    /**
     * Dados para conta corrente credor e despesa
     */
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oLancamento->c75_numemp);
    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
    $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
    $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    return $oLancamentoAuxiliar;
  }
}
