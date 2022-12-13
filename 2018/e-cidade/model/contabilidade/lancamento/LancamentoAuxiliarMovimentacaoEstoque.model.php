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
 * Model que executa os lancamentos auxiliares dos Movimentos do estoque
 * @author Andrio Costa andrio.costa@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.12 $
 */
class LancamentoAuxiliarMovimentacaoEstoque extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;
 /** * Valor total do empenho * @var float
   */
  private $nValorTotal;

   /**
   * Codigo da movimentacao do estoque
   * @var integer
   */
  private $iCodigoMovimentacaoEstoque;

  /**
   * Conta do PCASP - complano
   * @var integer
   */
  private $iContaPCASP;

  
  /**
   * Conta do VPD 
   * @var integer
   */
  private $iContaVPD;
  
  /**
   * Variavel para controle do tipo de transacao.
   * $lEstrono = false -> movimento de inclusao/entrada
   * $lEstrono = true  -> movimento de estorno/saida
   * @var boolean
   */
  private $lSaida = false;

  /**
   * Material do estoque
   * @var MaterialEstoque
   */
  private $oMaterial;

  /**
   * Executa os lançamentos auxiliares dos Movimentos de uma liquidacao
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento - Código do Lancamento (conlancam)
   * @param date    $dtLancamento      - data do lancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);

    parent::salvarVinculoComplemento();
    $this->salvarVinculoMaterialEstoque();

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
   * Seta o codigo da movimentacao do material no estoque com o lancamento
   * @param integer $iCodigoMovimentacaoEstoque
   */
  public function setCodigoMovimentacaoEstoque($iCodigoMovimentacaoEstoque) {
    $this->iCodigoMovimentacaoEstoque = $iCodigoMovimentacaoEstoque;
  }

  /**
   * Retorna o codigo da movimentacao do material no estoque com o lancamento
   * @return integer
   */
  public function getCodigoMovimentacaoEstoque() {
    return $this->iCodigoMovimentacaoEstoque;
  }

  /**
   * Seta o codigo da Conta do PCASP
   * @param integer $iCodigoMovimentacaoEstoque
   */
  public function setContaPcasp($iContaPCASP) {
    $this->iContaPCASP = $iContaPCASP;
  }

  /**
   * Retorna o o codigo da Conta do PCASP
   * @return integer
   */
  public function getContaPcasp() {
    return $this->iContaPCASP;
  }
  
  /**
   * Seta o codigo da Conta VPD
   * @param integer $iCodigoMovimentacaoEstoque
   */
  public function setContaVPD($iContaVpd) {
  	$this->iContaVPD = $iContaVpd;
  }
  
  /**
   * Retorna o o codigo da Conta VPD
   * @return integer
   */
  public function getContaVPD() {
  	return $this->iContaVPD;
  }

  /**
   * Seta flag do tipo de lancamento que esta sendo efetuado
   * @param boolean
   */
  public function setSaida($lEstorno) {
    $this->lSaida = $lEstorno;
  }

  /**
   * Valida flag do tipo de lancamento que esta sendo efetuado
   * @return boolean
   */
  public function isSaida() {
    return $this->lSaida ;
  }

  /**
   * Vinculo da movimentacao do material no estoque com o lancamento
   */
  private function salvarVinculoMaterialEstoque() {

    $oDaoConLanCamEstoqueIniMei = db_utils::getDao('conlancammatestoqueinimei');

    $oDaoConLanCamEstoqueIniMei->c103_sequencial       = null;
    $oDaoConLanCamEstoqueIniMei->c103_conlancam        = $this->iCodigoLancamento;
    $oDaoConLanCamEstoqueIniMei->c103_matestoqueinimei = $this->getCodigoMovimentacaoEstoque();
    $oDaoConLanCamEstoqueIniMei->incluir(null);

    if ($oDaoConLanCamEstoqueIniMei->erro_status == '0') {

      $sErroMsg  = "Não foi possível incluir o vínculo do material do estoque com o lançamento.\n\n";
      $sErroMsg .= "Erro Técnico: {$oDaoConLanCamEstoqueIniMei->erro_msg}";
      throw new BusinessException($sErroMsg);
    }

    return true;
  }

  /**
   * O material esta passando o codcon da conplano e na transacao estamos comparando um reduzido.
   * O que fizemos abaixo eh localizar o reduzido da conta
   * @throws BusinessException
   */
  public function getContaMaterial() {

    $lTemGrupo = !empty($this->iContaPCASP);
    if ($lTemGrupo) {

      $oDaoConplanoReduz = db_utils::getDao("conplanoreduz");
      $sWhereReduzido    = "     c61_codcon = {$this->iContaPCASP} ";
      $sWhereReduzido   .= " and c61_anousu = " .db_getsession("DB_anousu");
      $sWhereReduzido   .= " and c61_instit = " .db_getsession("DB_instit");

      $sSqlReduzido = $oDaoConplanoReduz->sql_query_file(null, null, "c61_reduz", null, $sWhereReduzido);
      $rsReduzido   = $oDaoConplanoReduz->sql_record($sSqlReduzido);
      if ($oDaoConplanoReduz->numrows == 0) {
       $lTemGrupo = false;
      }
    }

    if (!$lTemGrupo) {

      $sMensagemException  = 'Não foi possível localizar a conta reduzida do material. \n';
      $sMensagemException .= 'Verificar o cadastro de grupo/subgrupo.\n';
      $sMensagemException .= 'Menu: Cadastro > Cadastro de Material > Alteracao';
      throw new BusinessException($sMensagemException);
    }
    return db_utils::fieldsMemory($rsReduzido, 0)->c61_reduz;
  }

  /**
   * define o material do estoque que esta sendo realizado a operacao
   * @param MaterialEstoque $oMaterial
   */
  public function setMaterial(MaterialEstoque $oMaterial) {
    $this->oMaterial = $oMaterial;
  }

  /**
   * Retorna o objeto MaterialEstoque
   * @return MaterialEstoque
   */
  public function getMaterial() {
    return $this->oMaterial;
  }

  /**
   * Retorna a conta de despesa do material
   * @throws BusinessException
   * @return ContaOrcamento
   */
  public function getContaDespesa () {

    if (empty($this->oMaterial)) {
      throw new BusinessException("Material para a escrituração contábil não informado.");
    }

    $oContaDespesa = $this->oMaterial->getDesdobramento();
    if (empty($oContaDespesa)) {

      $sMenu      = db_stdClass::getCaminhoMenu('com1_pcmater002.php');
      $sMensagem  = "Material sem vínculo com conta de despesa.\n";
      $sMensagem .= "Para realizar esse vinculo acesse a rotina:\n";
      $sMensagem .= "{$sMenu} e informe algum desdobramento para o item.\n\n";
      $sMensagem .= "Verifique também se o desdobramento do item tem código \n";
      $sMensagem .= "reduzido no plano orçamentário para esta instituição.";
      throw new BusinessException($sMensagem);
    }

    if ($oContaDespesa->getReduzido() == "") {

      $sMensagem  = "Conta {$oContaDespesa->getEstrutural()} - {$oContaDespesa->getDescricao()} ";
      $sMensagem .= "não é uma conta analítica. verifique o desdobramento do Material.";
      throw new BusinessException($sMensagem);
    }
    return $oContaDespesa;
  }
  
  /**
   * Função da classe que constroi uma instância de LancamentoAuxiliarMovimentacaoEstoque, 
   * de acordo com código do lançamento, passado como parâmetro
   * @param  integer $iCodigoLancamento
   * @return LancamentoAuxiliarMovimentacaoEstoque
   */
  public static function getInstance($iCodigoLancamento) {
    
    $oDaoConLanCamEstoqueIniMei = db_utils::getDao('conlancammatestoqueinimei');

    $sCampos     = "c103_sequencial, c70_valor, c70_data, c72_complem, c71_coddoc, m70_codmatmater ";
    $sSql        = $oDaoConLanCamEstoqueIniMei->sql_query_dadoslancamento($iCodigoLancamento, $sCampos);
    $rsResultado = $oDaoConLanCamEstoqueIniMei->sql_record($sSql);
    
    if ($oDaoConLanCamEstoqueIniMei->numrows != 1) {
      throw new BusinessException("Erro técnico: erro ao buscar os dados do lançamento do da movimentação de estoque");
    }

    $oStdLancamentoMovimentacao  = db_utils::fieldsMemory($rsResultado, 0); 
    $iCodigoMovimentacaoEstoque  = $oStdLancamentoMovimentacao->c103_sequencial;
    $nValorTotal                 = $oStdLancamentoMovimentacao->c70_valor;
    $dtLancamento                = $oStdLancamentoMovimentacao->c70_data;
    $sComplemento                = $oStdLancamentoMovimentacao->c72_complem;
    
    /**
     * Seta as propriedades para criar uma instância da classe, de acordo com dados do lançamento
     */
    $oMaterialEstoque = new materialEstoque($oStdLancamentoMovimentacao->m70_codmatmater);

    $oLancamentoAuxiliar = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliar->setCodigoMovimentacaoEstoque($iCodigoMovimentacaoEstoque);
    $oLancamentoAuxiliar->setValorTotal($nValorTotal);
    $oLancamentoAuxiliar->setDataLancamento($dtLancamento);
    $oLancamentoAuxiliar->setObservacaoHistorico($sComplemento);
    $oLancamentoAuxiliar->setMaterial($oMaterialEstoque);
    $oLancamentoAuxiliar->setContaPcasp($oMaterialEstoque->getGrupo()->getConta());
    
    $oLancamentoAuxiliar->setSaida(false);
    
    if(in_array($oStdLancamentoMovimentacao->c71_coddoc, array(400, 404))) {
      $oLancamentoAuxiliar->setSaida(true);
    }

    return $oLancamentoAuxiliar;
  }    

}