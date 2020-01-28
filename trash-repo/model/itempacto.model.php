<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * 
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.2 $
 */
class itemPacto {
  
  /**
   * Cуdigo do item 
   *
   * @var integer
   */
  private $codigo = null;
  
  /**
   * Atividade
   *
   * @var integer
   */
  private $atividade = null;
  
  /**
   * Programa
   *
   * @var integer
   */
  private $programa = null;
  
  /**
   * projeto
   *
   * @var integer
   */
  private $projeto = null;
  
  /**
   * plano
   *
   * @var integer
   */
  private $plano = null;
  
  /**
   * Item
   *
   * @var integer
   */
  private $item = null;
  
  /**
   * Categoria
   *
   * @var integer
   */
  private $categoria = null;
  
  /**
   * acгo 
   *
   * @var integer
   */
  private $acao = null;
  
  /**
   * quantidade do item 
   *
   * @var float
   */
  private $quantidade  = 0;
  
  /**
   * valor aproximado
   *
   * @var float
   */
  private $valoraproximado = 0;
  
  
  /**
   * 
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {
      
      $oDaoPactoValor = db_utils::getDao("pactovalor");
      $sSqlItem       = $oDaoPactoValor->sql_query($iCodigo);
      $rsItens        = $oDaoPactoValor->sql_record($sSqlItem);
      if ($oDaoPactoValor->numrows > 0) {
         
        $oItem   = db_utils::fieldsMemory($rsItens, 0);
        $this->setCategoria($oItem->o87_categoriapacto);
        $this->setAcao($oItem->o87_pactoacoes);
        $this->setAtividade($oItem->o87_pactoatividade);
        $this->setItem($oItem->o87_pactoitem);
        $this->setPlano($oItem->o87_pactoplano);
        $this->setPrograma($oItem->o87_pactoprograma);
        $this->setProjeto($oItem->o87_orcprojativativprojeto);
        $this->setQuantidade($oItem->o87_quantidade);
        $this->setValoraproximado($oItem->o87_vlraproximado);
        $this->codigo = $iCodigo;
        
      }
    }
  }
  /**
   * @return integer
   */
  public function getAcao() {

    return $this->acao;
  }
  
  /**
   * @param integer $acao
   */
  public function setAcao($acao) {

    $this->acao = $acao;
  }
  
  /**
   * @return integer
   */
  public function getAtividade() {

    return $this->atividade;
  }
  
  /**
   * @param integer $atividade
   */
  public function setAtividade($atividade) {

    $this->atividade = $atividade;
  }
  
  /**
   * @return integer
   */
  public function getCategoria() {

    return $this->categoria;
  }
  
  /**
   * @param integer $categoria
   */
  public function setCategoria($categoria) {

    $this->categoria = $categoria;
  }
  
  /**
   * @return integer
   */
  public function getItem() {

    return $this->item;
  }
  
  /**
   * @param integer $item
   */
  public function setItem($item) {

    $this->item = $item;
  }
  
  /**
   * @return integer
   */
  public function getPlano() {

    return $this->plano;
  }
  
  /**
   * @param integer $plano
   */
  public function setPlano($plano) {

    $this->plano = $plano;
  }
  
  /**
   * @return integer
   */
  public function getPrograma() {

    return $this->programa;
  }
  
  /**
   * @param integer $programa
   */
  public function setPrograma($programa) {

    $this->programa = $programa;
  }
  
  /**
   * @return integer
   */
  public function getProjeto() {

    return $this->projeto;
  }
  
  /**
   * @param integer $projeto
   */
  public function setProjeto($projeto) {

    $this->projeto = $projeto;
  }
  
  /**
   * @return float
   */
  public function getQuantidade() {

    return $this->quantidade;
  }
  
  /**
   * @param float $quantidade
   */
  public function setQuantidade($quantidade) {

    $this->quantidade = $quantidade;
  }
  
  /**
   * @return float
   */
  public function getValoraproximado() {

    return $this->valoraproximado;
  }
  
  /**
   * @param float $valoraproximado
   */
  public function setValoraproximado($valoraproximado) {

    $this->valoraproximado = $valoraproximado;
  }

  /**
   * O metodo Vincula um item da Solicitaзгo ao 
   *
   * @param float   $nQuantidade       Quantidade de Itens
   * @param float   $nValor            valor Total
   * @param integer $iItemSolicitacao  Codigo do item da solicitacao
   */
  public function baixarSaldoSolicitacao($nQuantidade, $nValor, $iItemSolicitacao) {
    
    $oDaoPactoValorMov         = db_utils::getDao("pactovalormov");
    $oDaoPactoValorMovSolicita = db_utils::getDao("pactovalormovsolicitem");
    if (empty($this->codigo)) {
      throw new Exception('Informe o cуdigo do item do pacto', 1);
    }
    /**
     * Verificamos se nao existe um movimento para o item dessa solicitaзгo
     * caso exista apenas redefinimos a quantidade e valor da movimentacao do item
     */
    $sSqlItem = $oDaoPactoValorMovSolicita->sql_query_file(null,
                                                           "o101_sequencial,o101_pactovalormov",
                                                           null,
                                                           "o101_solicitem = {$iItemSolicitacao}"
                                                           );
                                                           
    $rsItem   = $oDaoPactoValorMovSolicita->sql_record($sSqlItem);
    if ($oDaoPactoValorMovSolicita->numrows > 0 ) {
     
      $oDaoPactoValorMovSolicita->excluir(db_utils::fieldsMemory($rsItem, 0)->o101_sequencial);
      $oDaoPactoValorMov->o88_sequencial = db_utils::fieldsMemory($rsItem, 0)->o101_pactovalormov;
      $oDaoPactoValorMov->excluir($oDaoPactoValorMov->o88_sequencial);
      if ($oDaoPactoValorMov->erro_status == 0) {
        throw new Exception('Erro ao Incluir movimentacгo para o pacto.\nOperacao Abortada', 2);
      }     
    } 
      
    $oDaoPactoValorMov->o88_pactovalor = $this->codigo;  
    $oDaoPactoValorMov->o88_quantidade = $nQuantidade;
    $oDaoPactoValorMov->o88_valor      = $nValor;
    $oDaoPactoValorMov->incluir(null);
    if ($oDaoPactoValorMov->erro_status == 0) {
      throw new Exception($oDaoPactoValorMov->erro_msg, 2);
    }
      
    $oDaoPactoValorMovSolicita->o101_pactovalormov = $oDaoPactoValorMov->o88_sequencial;
    $oDaoPactoValorMovSolicita->o101_solicitem     = $iItemSolicitacao;
    $oDaoPactoValorMovSolicita->incluir(null);
    if ($oDaoPactoValorMovSolicita->erro_status == 0) {
      throw new Exception('Erro ao Incluir movimentacгo para o pacto.\nOperacao Abortada', 3);
    }
    
    $oDaoSolicitemPcMater =  db_utils::getDao("solicitempcmater");
    $sSqlPCmater          = $oDaoSolicitemPcMater->sql_query_file(null,$iItemSolicitacao);
    $rsPcMater            = $oDaoSolicitemPcMater->sql_record($sSqlPCmater);
    $iItemPcMater         = null;;
    if ($oDaoSolicitemPcMater->numrows > 0) {
       $iItemPcMater = db_utils::fieldsMemory($rsPcMater, 0)->pc16_codmater;
    }
    
    /**
     * Verificamos se o item do compras jб estб vinculado ao item do pacto 
     */
    if ($iItemPcMater != null) {

      $oDaoPactoItemPcmater = db_utils::getDao("pactoitempcmater")  ;
      $sSqlVinculo          = $oDaoPactoItemPcmater->sql_query_file(null,
                                                                    "*",
                                                                    null,
                                                                    "o89_pactoitem=".$this->getItem()."
                                                                    and o89_pcmater = {$iItemPcMater}");

      $rsVinculo           = $oDaoPactoItemPcmater->sql_record($sSqlVinculo);
      if ($oDaoPactoItemPcmater->numrows == 0) {

        $oDaoPactoItemPcmater->o89_pactoitem = $this->getItem();
        $oDaoPactoItemPcmater->o89_pcmater   = $iItemPcMater;
        $oDaoPactoItemPcmater->incluir(null);
        
      }
    }
  }
  
  public function excluirSaldoSolicitacao($iItemSolicitacao) {
    
    $oDaoPactoValorMov         = db_utils::getDao("pactovalormov");
    $oDaoPactoValorMovSolicita = db_utils::getDao("pactovalormovsolicitem");
    if (empty($this->codigo)) {
      throw new Exception('Informe o cуdigo do item do pacto', 1);
    }
    $sSqlItem = $oDaoPactoValorMovSolicita->sql_query_file(null,
                                                           "o101_sequencial,o101_pactovalormov",
                                                           null,
                                                           "o101_solicitem = {$iItemSolicitacao}"
                                                           );
                                                           
    $rsItem   = $oDaoPactoValorMovSolicita->sql_record($sSqlItem);
    if ($oDaoPactoValorMovSolicita->numrows > 0 ) {
     
      $oDaoPactoValorMovSolicita->excluir(db_utils::fieldsMemory($rsItem, 0)->o101_sequencial);
      $oDaoPactoValorMov->o88_sequencial = db_utils::fieldsMemory($rsItem, 0)->o101_pactovalormov;
      $oDaoPactoValorMov->excluir($oDaoPactoValorMov->o88_sequencial);
      if ($oDaoPactoValorMov->erro_status == 0) {
        throw new Exception('Erro ao cancelar movimentacгo para o pacto.\nOperacao Abortada', 2);
      }     
    }
  }
  
/**
   * O metodo Vincula um item da Solicitaзгo ao 
   *
   * @param float   $nQuantidade       Quantidade de Itens
   * @param float   $nValor            valor Total
   * @param integer $iItemSolicitacao  Codigo do item da solicitacao
   */
  public function baixarSaldoEmpenho($nQuantidade, $nValor, $iItemEmpenho) {
    
    $oDaoPactoValorMov         = db_utils::getDao("pactovalormov");
    $oDaoPactoValorMovEmpenho  = db_utils::getDao("pactovalormovempempitem");
    if (empty($this->codigo)) {
      throw new Exception('Informe o cуdigo do item do pacto', 1);
    }
          
    $oDaoPactoValorMov->o88_pactovalor = $this->codigo;  
    $oDaoPactoValorMov->o88_quantidade = "$nQuantidade";
    $oDaoPactoValorMov->o88_valor      = $nValor;
    $oDaoPactoValorMov->incluir(null);
    if ($oDaoPactoValorMov->erro_status == 0) {
      throw new Exception('Erro ao Incluir movimentacгo para o pacto.\nOperacao Abortada', 3);
    }
      
    $oDaoPactoValorMovEmpenho->o105_pactovalormov = $oDaoPactoValorMov->o88_sequencial;
    $oDaoPactoValorMovEmpenho->o105_empempitem    = $iItemEmpenho;
    $oDaoPactoValorMovEmpenho->incluir(null);
    if ($oDaoPactoValorMovEmpenho->erro_status == 0) {
      throw new Exception('Erro ao Incluir movimentacгo para o pacto.\nOperacao Abortada', 4);
    }
    
  }
  
  public function atualizaSaldoRealizado($nValor, $iConta, $iOrdem = null) {
    
    $oDaoPactoSaldo =db_utils::getDao("pactovalorsaldo"); 
    $oDaoConvenio  = db_utils::getDao("pactoplano");
    $sSqlConvenio  = $oDaoConvenio->sql_query($this->getPlano(), "*");
    $rsConvenio    = $oDaoConvenio->sql_record($sSqlConvenio);
    $oConvenio     = db_utils::fieldsMemory($rsConvenio, 0);
    $iMes          = date("m", db_getsession("DB_datausu"));
    $iAno          = date("Y", db_getsession("DB_datausu"));
    if ($nValor != 0) {
      
      $oDaoPactoSaldo->o103_anousu              = $iAno;
      $oDaoPactoSaldo->o103_mesusu              = $iMes;
      $oDaoPactoSaldo->o103_pactovalor          = $this->codigo;
      $oDaoPactoSaldo->o103_pactovalorsaldotipo = 2;
      $oDaoPactoSaldo->o103_contrapartida       = $iConta == $oConvenio->o16_saltes?"false":"true";
      $oDaoPactoSaldo->o103_valor               = $nValor;
      $oDaoPactoSaldo->incluir(null);
      if ($oDaoPactoSaldo->erro_status == 0) {
        
        $sErroMsg = "Nгo foi possнvel atualizar o saldo do item do pacto.";
        throw new Exception($sErroMsg, 1);
                  
      }
      if (!empty($iOrdem)) {
        
        $oDaoPactItemOrdem = db_utils::getDao("pactovalorsaldopagordem");
        $oDaoPactItemOrdem->o110_pactovalorsaldo = $oDaoPactoSaldo->o103_sequencial;
        $oDaoPactItemOrdem->o110_pagordem        = $iOrdem;
        $oDaoPactItemOrdem->incluir(null);
        
      }
    }
  }
  /**
   * Retorna  os saldos do item 
   *
   * @return object
   */
  public function getSaldos() {
    
    $sSqlSaldo  = "select rnqtdecomprometida   as quantidade_comprometida,";
    $sSqlSaldo .= "       rnvalorcomprometido  as valor_comprometido,";
    $sSqlSaldo .= "       rnqtderealizada      as quantidade_realizada,";
    $sSqlSaldo .= "       rnvalorrealizado     as valor_realizado,";
    $sSqlSaldo .= "       rnquantidade         as quantidade,";
    $sSqlSaldo .= "       rnvalor              as valor,";
    $sSqlSaldo .= "       rnqtdesaldo          as saldo_quantidade,";
    $sSqlSaldo .= "       rnvalorsaldo         as valor_saldo";
    $sSqlSaldo .= "  from fc_saldoitempacto({$this->codigo})";
    $rsSaldo    = db_query($sSqlSaldo);
    $oSaldo     = db_utils::fieldsMemory($rsSaldo, 0);
    return $oSaldo;
    
  }
  /**
   * 
   */
  function __destruct() {

    
  }
}

?>