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

/**
 * Define o Item para a solicitacao
 * @package Compras
 */

class itemSolicitacao {
  
  /**
   * Codigo Sequencial do Item na Solicita��o
   *
   * @var integer
   */
  protected $iCodigoItemSolicitacao;
  
  /**
   * Codigo do item do material
   *
   * @var integer
   */
  protected $iCodigoMaterial;
  
  /**
   * Desdobramento do item 
   *
   * @var integer
   */
  protected $iDesdobramento;
  
  /**
   * quantidade do Item
   *
   * @var float
   */
  protected $nQuantidade    = 0;
  
  /**
   * Valor Unitario
   *
   * @var float
   */
  protected $nValorUnitario = 0;
  
  /**
   * Valor total do item, 
   *
   * @var float
   */
  protected $nValorTotal    = 0;
  
  /**
   * Ordem do item na solicita��o
   *
   * @var integer
   */
  protected $iOrdem         = 0;
  
  /**
   * Descri��o dos prazos de entrega do item 
   *
   * @var string
   */
  protected $sPrazos        = "";
  
  /**
   * Informa��es sobre o pagamento
   *
   * @var string
   */
  protected $sPagamento     = "";
  
  /**
   * Resumo da Aquisi��o do item
   *
   * @var string
   */
  protected $sResumo        = "";
  
  /**
   * Justificativa da compra do item 
   *
   * @var string
   */
  protected $sJustificativa = "";
  
  /**
   * Dota��es do item
   *
   * @var integer
   */
  protected $aDotacoes    = array();
  
  /**
   * Codigo do item da Solicita��o 
   *
   * @var integer
   */
  protected $iSolicitacao       = '';
  
  /**
   * descricao do material
   *
   * @var varchar
   */
  protected $sDescricaoMaterial = "";
  
  
  /**
   * Codigo da Unidade de medida
   *
   * @var integer
   */
  protected $iUnidade = "";
  
  /**
   * quantidade da unidade
   *
   * @var  integer
   */
  protected $iQuantidadeUnidade = 1;
  
  /**
   * Define se o item foi lan�ando automaticamente
   *
   * @var unknown_type
   */
  protected $isAutomatico = false;
  
  /**
   * Define o Item pai que o item pode estar vinculado
   *
   * @var itemSolicitacao
   */
  protected $oVinculo = null;
  
  /**
   * Define o c�digo do item da solicitacao de origem 
   *
   * @var integer
   */
  protected $iOrigem = null;
  

  
  /**
   * Define o codres 
   *
   * @var integer
   */
  protected $iCodRes = null; 

  /**
   * Define o coddot 
   *
   * @var integer
   */
  protected $iCodDot = null; 
  
  /**
   * Define o anousu 
   *
   * @var integer
   */
  protected $iAnousu = null;
  
  
  /**
   * Servi�o controla quantidade
   * @var booleam
   */
  protected $lServicoControlaQuantidade = false; 

  
  
  /**
   * Item para a uma solicita��o de compras 
   *
   * @param integer $iItemSolicitacao Codigo do item na solicita��o de Compras
   * @param integer $iMaterial Codifgo do material (pcmater.pc01_codmater)
   * 
   * @return itemSolicitacao
   */
  function __construct($iItemSolicitacao = null, $iMaterial = null) {
  
    if (!empty($iItemSolicitacao)) {
      
      $this->iCodigoItemSolicitacao = $iItemSolicitacao;
      $oDaoSolicitem = db_utils::getDao("solicitem");
      $sSqlItens     = $oDaoSolicitem->sql_query_vinculo($iItemSolicitacao);
      $rsItens       = $oDaoSolicitem->sql_record($sSqlItens);
      if ($oDaoSolicitem->numrows > 0) {

        $oItem = db_utils::fieldsMemory($rsItens, 0, false, false, true);
        $this->iCodigoItemSolicitacao = $oItem->pc11_codigo;
        $this->iCodigoMaterial        = $oItem->pc16_codmater;
        $this->sDescricaoMaterial     = $oItem->pc01_descrmater;
        $this->sJustificativa         = $oItem->pc11_just;
        $this->sResumo                = $oItem->pc11_resum;
        $this->sPagamento             = $oItem->pc11_pgto;
        $this->iOrigem                = $oItem->pc55_solicitempai;
        $this->setOrdem($oItem->pc11_seq);
        $this->setUnidade($oItem->pc17_unid);            
        $this->setQuantidadeUnidade($oItem->pc17_quant);            
        $this->setPrazos($oItem->pc11_prazo);            
        $this->nQuantidade   = $oItem->pc11_quant;            
        $this->setValorUnitario($oItem->pc11_vlrun);            
        $this->iSolicitacao  = $oItem->pc11_numero;
        $this->lServicoControlaQuantidade = $oItem->pc11_servicoquantidade;
        
        $sSqlQuery = $oDaoSolicitem->sql_query_desdobramento($iItemSolicitacao,"o56_elemento", null, null);
        $rsDesdobramento = $oDaoSolicitem->sql_record($sSqlQuery);
        
        if ($oDaoSolicitem->numrows != 0) {
          $this->setDesdobramento(db_utils::fieldsMemory($rsDesdobramento, 0)->o56_elemento);
        }
        
        
        //echo $this->getDesdobramento(); die();
        
        unset($oItem);
      }    
    } else {
      
      if (!empty($iMaterial)) {
        
        $this->iCodigoMaterial = $iMaterial;
        $oDaoPcMater           = db_utils::getDao("pcmater");
        $sSqlDescricaoMaterial = $oDaoPcMater->sql_query_file($iMaterial);
        $rsMaterial            = $oDaoPcMater->sql_record($sSqlDescricaoMaterial);
        $this->sDescricaoMaterial = urlencode(db_utils::fieldsMemory($rsMaterial, 0)->pc01_descrmater);
        
      }
    }
    return $this;
  }
 
  
  /**
   * @return integer
   * @return itemSolicitacao
   */
  public function getCodigoItemSolicitacao() {
    return $this->iCodigoItemSolicitacao;
  }
  
  /**
   * @return integer
   */
  public function getCodigoMaterial() {
    return $this->iCodigoMaterial;
  }
  
  /**
   * @param integer $iCodigoMaterial
   * @return itemSolicitacao
   */
  public function setCodigoMaterial($iCodigoMaterial) {
    $this->iCodigoMaterial = $iCodigoMaterial;
  }
  
  /**
   * @return integer
   */
  public function getDesdobramento() {

    return $this->iDesdobramento;
  }
  
  /**
   * @param integer $iDesdobramento
   * @return itemSolicitacao
   */
  public function setDesdobramento($iDesdobramento) {

    $this->iDesdobramento = $iDesdobramento;
  }
  
  /**
   * @return integer
   */
  public function getOrdem() {

    return $this->iOrdem;
  }
  
  /**
   * @param integer $iOrdem
   * @return itemSolicitacao
   */
  public function setOrdem($iOrdem) {

    $this->iOrdem = $iOrdem;
  }
  
  /**
   * @return float
   */
  public function getQuantidade() {

    return $this->nQuantidade;
  }
  
  /**
   * @param float $nQuantidade
   * @return itemSolicitacao
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }
  
  /**
   * @return float
   */
  public function getValorTotal() {

    return $this->nValorTotal;
  }
  
  /**
   * @param float $nValorTotal
   * @return itemSolicitacao
   */
  public function setValorTotal($nValorTotal) {

    $this->nValorTotal = $nValorTotal;
  }
  
  /**
   * @return float
   */
  public function getValorUnitario() {

    return $this->nValorUnitario;
  }
  
  /**
   * @param float $nValorUnitario
   * @return itemSolicitacao
   */
  public function setValorUnitario($nValorUnitario) {
    $this->nValorUnitario = $nValorUnitario;
  }
  
  /**
   * @return string
   */
  public function getJustificativa() {

    return $this->sJustificativa;
  }
  
  /**
   * @param string $sJustificativa
   * @return itemSolicitacao
   */
  public function setJustificativa($sJustificativa) {

    $this->sJustificativa = $sJustificativa;
  }
  
  /**
   * @return string
   */
  public function getPagamento() {

    return $this->sPagamento;
  }
  
  /**
   * @param string $sPagamento
   * @return itemSolicitacao
   */
  public function setPagamento($sPagamento) {

    $this->sPagamento = $sPagamento;
  }
  
  /**
   * @return string
   */
  public function getPrazos() {
    return $this->sPrazos;
  }
  
  /**
   * @param string $sPrazos
   * @return itemSolicitacao
   */
  public function setPrazos($sPrazos) {

    $this->sPrazos = $sPrazos;
    
  }
  
  /**
   * @return string
   */
  public function getResumo() {

    return $this->sResumo;
  }
  
  /**
   * @param string $sResumo
   * @return itemSolicitacao
   */
  public function setResumo($sResumo) {

    $this->sResumo = $sResumo;
  }
  /**
  
  /**
   * @return integer
   */
  public function getQuantidadeUnidade() {
    return $this->iQuantidadeUnidade;
  }
  
  /**
   * @param integer $iQuantidadeUnidade
   * @return itemSolicitacao
   */
  public function setQuantidadeUnidade($iQuantidadeUnidade) {

    $this->iQuantidadeUnidade = $iQuantidadeUnidade;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getUnidade() {

    return $this->iUnidade;
  }
  
  /**
   * @param integer $iUnidade
   * @return itemSolicitacao
   */
  public function setUnidade($iUnidade) {

    $this->iUnidade = $iUnidade;
    return $this;

  }
  
  
  
  
  
  
  

   
  
  
  
  /**
   * Salva o item 
   *
   * @param integer $iSolicitacao C�digo da Solicitacao de Compras
   * @return itemSolicitacao
   */
  public function save($iSolicitacao= '' ) {
    
    if ($this->getCodigoMaterial() == "") {
      throw new Exception("Informe o item!");
    }

    /**
     * Incluimos na tabela solicitem 
     */
    $oDaoSolicitem                         = db_utils::getDao("solicitem");
    $oDaoSolicitem->pc11_just              = addslashes(urldecode($this->getJustificativa()));
    $oDaoSolicitem->pc11_liberado          = "true";
    $oDaoSolicitem->pc11_pgto              = addslashes(urldecode($this->getPagamento()));
    $oDaoSolicitem->pc11_prazo             = pg_escape_string(urldecode($this->getPrazos()));
    $oDaoSolicitem->pc11_quant             = addslashes(urldecode($this->getQuantidade()));
    $oDaoSolicitem->pc11_vlrun             = "{$this->getValorUnitario()}";
    $oDaoSolicitem->pc11_seq               = "{$this->getOrdem()}";
    $oDaoSolicitem->pc11_resum             = addslashes(urldecode($this->getResumo()));
    $oDaoSolicitem->pc11_servicoquantidade = $this->lServicoControlaQuantidade;

    if (!empty($iSolicitacao)) {

      $oDaoSolicitem->pc11_numero  = "{$iSolicitacao}";
      $this->iSolicitacao = $iSolicitacao;
            
    } else if (!empty($this->iSolicitacao)) {
      $oDaoSolicitem->pc11_numero  = "{$this->iSolicitacao}";
    } else {
      throw new Exception("N�mero da Solicita��o n�o informado");
    }
    
    if (!empty($this->iCodigoItemSolicitacao)) {
      
      $oDaoSolicitem->pc11_codigo = $this->getCodigoItemSolicitacao();
      $oDaoSolicitem->alterar($this->getCodigoItemSolicitacao());
            
    } else {
      
      $oDaoSolicitem->incluir(null);
      $this->iCodigoItemSolicitacao = $oDaoSolicitem->pc11_codigo;
      
    }
    
    if ($oDaoSolicitem->erro_status == 0) {
      throw new Exception("Erro ao salvar item {$this->getCodigoMaterial()}!\nErro Retornado:{$oDaoSolicitem->erro_msg}");
    }
    
    $oDaoSolicitemVinculo = db_utils::getDao("solicitemvinculo");
    $oDaoSolicitemVinculo->excluir(null,"pc55_solicitemfilho = {$this->iCodigoItemSolicitacao}");
    if (!empty($this->iOrigem)) {

      $oDaoSolicitemVinculo->pc55_solicitemfilho = $this->iCodigoItemSolicitacao;
      $oDaoSolicitemVinculo->pc55_solicitempai   = $this->iOrigem;
      $oDaoSolicitemVinculo->incluir(null);
      if ($oDaoSolicitemVinculo->erro_status == 0) {
        
        $sErroMsg  = "Erro ao salvar item {$this->getCodigoMaterial()}!\n";
        $sErroMsg .= "Erro Retornado:{$oDaoSolicitemVinculo->erro_msg}";
        throw new Exception($sErroMsg);
        
      }  
    }
    /**
     * Excluimos e incluimos novamente na tabela solicitempcmater
     */
    $oDaosolicitemPcMater = db_utils::getDao("solicitempcmater");
    $oDaosolicitemPcMater->excluir($this->getCodigoMaterial(),$this->iCodigoItemSolicitacao);
    $oDaosolicitemPcMater->incluir($this->getCodigoMaterial(),$this->iCodigoItemSolicitacao);
    if ($oDaosolicitemPcMater->erro_status == 0) {
      throw new Exception("Erro ao salvar item {$this->getCodigoMaterial()}!\nErro Retornado:{$oDaosolicitemPcMater->erro_msg}");
    }
    
    /**
     * Salvamos as informacoes da Unidade do material
     */    
    $oDaosolicitemUnid = db_utils::getDao("solicitemunid");
    $oDaosolicitemUnid->excluir($this->iCodigoItemSolicitacao);
    $oDaosolicitemUnid->pc17_codigo = $this->iCodigoItemSolicitacao;
    $oDaosolicitemUnid->pc17_quant = "{$this->getQuantidadeUnidade()}";
    $oDaosolicitemUnid->pc17_unid  = "{$this->getUnidade()}";
    
    $oDaosolicitemUnid->incluir($this->iCodigoItemSolicitacao);
    if ($oDaosolicitemUnid->erro_status == 0) {
      throw new Exception("Erro ao salvar item {$this->getCodigoMaterial()}!\nErro Retornado:{$oDaosolicitemUnid->erro_msg}");
    }
    unset($oDaoSolicitem);
    unset($oDaosolicitemPcMater);
    unset($oDaosolicitemUnid);
    return $this;
  }
  
  /**
   * Retorna a descricao do material Incluido
   *
   * @return string
   */
  public function getDescricaoMaterial() {
    return $this->sDescricaoMaterial;
  }
  
  /** define se o item � automatico nao ou nao 
   *
   * @return string
   */
  public function setAutimatico($lAutomatico) {
    $this->isAutomatico = $lAutomatico;
  }
  
  public function isAutomatico() {
    return $this->isAutomatico;
  }
  
  /**
   * Define o item de Origem do item,  
   *
   * @param integer $iCodigoOrigem codigo do item de solicitacao (pc11_codigo)  de origem
   */
  public function setCodigoOrigem($iCodigoOrigem) {
    $this->iOrigem = $iCodigoOrigem;
  }
  
  /**
   * Retorna o codigo do origem
   *
   * return integer
   */
  public function getCodigoOrigem() {
    return $this->iOrigem;
  }
  
  /**
   * Remove o item 
   *
   */
  public function remover($lRemoverVinculo = false) {
    
    /**
     * Excluimos a vinculacao com  solicitempcmater
     */
    if ($this->iCodigoItemSolicitacao != null) {

      $oDaosolicitemPcMater = db_utils::getDao("solicitempcmater");
      $oDaosolicitemPcMater->excluir($this->getCodigoMaterial(),$this->iCodigoItemSolicitacao);
      
      $oDaosolicitemUnid = db_utils::getDao("solicitemunid");
      $oDaosolicitemUnid->excluir($this->iCodigoItemSolicitacao);
      if ($oDaosolicitemUnid->erro_status == 0) {
        throw new Exception("Erro ao Remover item.\n{$oDaoSolicitemUnid->erro_msg}");
      }
      
      /*
       * Verificamos se o item est� referenciado a alguma estimativa
       * Se a estimativa estiver anulada ser� exclu�da sua vincula��o
       * Nessa situa��o � gerado o item da abertura como pai de um item da estimativa.
       */
      $oDaoSolicitemVinculo = db_utils::getDao("solicitemvinculo");
      
      $sSqlVerificaEstimativa = " select * 
                                    from solicitaanulada 
                                   inner join solicitem        on pc67_solicita       = pc11_numero 
                                   inner join solicitemvinculo on pc55_solicitemfilho = pc11_codigo 
                                   where pc55_solicitempai = {$this->iCodigoItemSolicitacao}";
      $oDaoSolicitemVinculo->sql_record($sSqlVerificaEstimativa);
      if ($oDaoSolicitemVinculo->numrows > 0 ) {
        $oDaoSolicitemVinculo->excluir(null,"pc55_solicitempai = {$this->iCodigoItemSolicitacao}");
        if ($oDaoSolicitemVinculo->erro_status == 0) {
        
          $sErroMsg  = "Erro ao remover item {$this->getCodigoMaterial()}!\n";
          $sErroMsg .= "Erro Retornado:{$oDaoSolicitemVinculo->erro_msg}";
          throw new Exception($sErroMsg);
        
        }                                    
      }
      
      $oDaoSolicitemVinculo->excluir(null,"pc55_solicitemfilho = {$this->iCodigoItemSolicitacao}");
      if ($oDaoSolicitemVinculo->erro_status == 0) {
        
        $sErroMsg  = "Erro ao remover item {$this->getCodigoMaterial()}!\n";
        $sErroMsg .= "Erro Retornado:{$oDaoSolicitemVinculo->erro_msg}";
        throw new Exception($sErroMsg);
        
      }  
      $oDaoSolicitem        = db_utils::getDao("solicitem");
      /**
       * excluimos o item do solicitao
       */
      $oDaoSolicitem->excluir($this->getCodigoItemSolicitacao());
      if ($oDaoSolicitem->erro_status == 0) {
        throw new Exception("Erro ao Remover item.\n{$oDaoSolicitem->erro_msg}");
      }
    }
  }
  /**
   * 
   */
  function __destruct() {

  }
  
  function __clone() {
    $this->iCodigoItemSolicitacao = null;
  }
  
  function getUltimosOrcamentos($iCodigoItem=null, $aUnidades=null, $iFornecedor=null, $dtInicial=null, $dtFinal=null) {
    
    
    $oRetorno      = new stdClass();
    $aParamCompras = db_stdClass::getParametro("pcparam", array(DB_getsession("DB_instit")));
     
     $oParamCompras = $aParamCompras[0];
     unset($aParamCompras[0]);
     $iMaximoDias   = $oParamCompras->pc30_maximodiasorcamento;
     $iQuantidadeOrcamentosSolicitacao = $oParamCompras->pc30_basesolicitacao;
     $iQuantidadeOrcamentosProcesso    = $oParamCompras->pc30_baseprocessocompras;
     $iQuantidadeEmpenhosPagos         = $oParamCompras->pc30_baseempenhos;
     $oRetorno->iDias                  = $iMaximoDias;
     $oRetorno->sFornecedor            = '';
     $oRetorno->sDescricaoItem         = '';
     if ($iFornecedor != null && $iCodigoItem == null) {

       $oDaoCGM = db_utils::getDao("cgm");
       $sSqlCGM = $oDaoCGM->sql_query_file($iFornecedor);
       $oRetorno->sFornecedor = db_utils::fieldsMemory($oDaoCGM->sql_record($sSqlCGM), 0, false, false, true)->z01_nome;
       
     }
     if ($iCodigoItem != null) {
       
       $oDaoPcMater = db_utils::getDao("pcmater");
       $sSqlMaterial = $oDaoPcMater->sql_query_file($iCodigoItem);
       $rsMaterial = $oDaoPcMater->sql_record($sSqlMaterial);
       $oRetorno->sDescricaoItem = db_utils::fieldsMemory($rsMaterial, 0, false, false, true)->pc01_descrmater;
     }  
     /**
      * Consultamos todos os orcamentos de solicitacao
      */
    $oRetorno->solicitacoes      = array();
    $oRetorno->processodecompras = array();
    $oRetorno->empenhos          = array();
    $sWhereDatas  = "between  cast(fc_getsession('db_datausu')as date) - '{$iMaximoDias} days'::interval";
    $sWhereDatas .= " and cast(fc_getsession('db_datausu')as date)";
    if ($dtInicial != null && $dtFinal != null) {
   
       $sWhereDatas  = "between '".implode("-",array_reverse(explode("/", $dtInicial)))."' and ";
       $sWhereDatas .= " '".implode("-",array_reverse(explode("/", $dtFinal)))."' ";
    }
    
    if ($iQuantidadeOrcamentosSolicitacao > 0) {
      
       $sWhere = "";
       
       if ($iCodigoItem != null) {
         $sWhere .= " and pc16_codmater = {$iCodigoItem} ";         
       }
       if (count($aUnidades) > 0) {
         $sWhere .= " and pc17_unid in(".implode(",", $aUnidades).") ";         
       }
       if ($iFornecedor != null) {
         $sWhere .= " and pc21_numcgm = {$iFornecedor}";
       }
       
       $sSqlSolicitacao = "SELECT 1 as origem, "; 
       $sSqlSolicitacao .= "      pc10_data as data,  ";
       $sSqlSolicitacao .= "      pc23_vlrun as valorunitario,  ";
       $sSqlSolicitacao .= "      m61_abrev as descricaounidade,  ";
       $sSqlSolicitacao .= "      pc17_unid as unidade,  ";
       $sSqlSolicitacao .= "      pc17_quant as quantidadeunidade,  ";
       $sSqlSolicitacao .= "      z01_numcgm as codigocgm,  ";
       $sSqlSolicitacao .= "      z01_nome   as nomecgm,  ";
       $sSqlSolicitacao .= "      pc01_descrmater   as descricaomaterial  ";
       $sSqlSolicitacao .= " from pcorcamitem  ";
       $sSqlSolicitacao .= "      inner join pcorcamitemsol on pc29_orcamitem = pc22_orcamitem  ";
       $sSqlSolicitacao .= "      inner join pcorcam        on pc22_codorc    = pc20_codorc   ";
       $sSqlSolicitacao .= "      inner join pcorcamval     on pc22_orcamitem = pc23_orcamitem  ";
       $sSqlSolicitacao .= "      inner join pcorcamjulg    on pc24_orcamitem =  pc23_orcamitem  ";
       $sSqlSolicitacao .= "                               and pc23_orcamforne = pc24_orcamforne ";  
       $sSqlSolicitacao .= "      inner join solicitem      on pc29_solicitem = pc11_codigo  ";
       $sSqlSolicitacao .= "      inner join solicitemunid  on pc11_codigo    = pc17_codigo "; 
       $sSqlSolicitacao .= "      inner join matunid        on pc17_unid      = m61_codmatunid  ";
       $sSqlSolicitacao .= "      inner join pcorcamforne   on pc23_orcamforne = pc21_orcamforne "; 
       $sSqlSolicitacao .= "      inner join cgm            on pc21_numcgm     = z01_numcgm  ";
       $sSqlSolicitacao .= "      inner join solicitempcmater on pc16_solicitem  = pc11_codigo  ";
       $sSqlSolicitacao .= "      inner join pcmater          on pc16_codmater   = pc01_codmater  ";
       $sSqlSolicitacao .= "      inner join solicita         on pc11_numero     = pc10_numero  ";
       $sSqlSolicitacao .= " where pc10_data {$sWhereDatas}"; 
       $sSqlSolicitacao .= "   and pc24_pontuacao = 1  ";
       $sSqlSolicitacao .= "    {$sWhere} ";
       $sSqlSolicitacao .= "   and pc10_solicitacaotipo = 1 ";
       $sSqlSolicitacao .= " limit {$iQuantidadeOrcamentosSolicitacao} ";
       $rsSolicitacoes   = db_query($sSqlSolicitacao);
       $oRetorno->solicitacoes = db_utils::getColectionByRecord($rsSolicitacoes,false,false, true);
    }
    
    if ($iQuantidadeOrcamentosProcesso > 0) {
      
       $sWhere = "";
       
       if ($iCodigoItem != null) {
         $sWhere .= " and pc16_codmater = {$iCodigoItem} ";         
       }
       if (count($aUnidades) > 0) {
         $sWhere .= " and pc17_unid in(".implode(",", $aUnidades).") ";         
       }
       if ($iFornecedor != null) {
         $sWhere .= " and pc21_numcgm = {$iFornecedor}";
       }
       $sSqlProcessos = "SELECT 2 as origem, "; 
       $sSqlProcessos .= "      pc80_data as data,  ";
       $sSqlProcessos .= "      pc23_vlrun as valorunitario,  ";
       $sSqlProcessos .= "      m61_abrev as descricaounidade,  ";
       $sSqlProcessos .= "      pc17_unid as unidade,  ";
       $sSqlProcessos .= "      pc17_quant as Quantidadeunidade,  ";
       $sSqlProcessos .= "      z01_numcgm as codigocgm,  ";
       $sSqlProcessos .= "      z01_nome as nomecgm , ";
       $sSqlProcessos .= "      pc01_descrmater   as descricaomaterial  ";
       $sSqlProcessos .= " from pcorcamitem  ";
       $sSqlProcessos .= "      inner join pcorcamitemproc on pc31_orcamitem   = pc22_orcamitem "; 
       $sSqlProcessos .= "      inner join pcorcam        on pc22_codorc       = pc20_codorc  ";
       $sSqlProcessos .= "      inner join pcorcamval     on pc22_orcamitem    = pc23_orcamitem  ";
       $sSqlProcessos .= "      inner join pcorcamjulg    on pc24_orcamitem    =  pc23_orcamitem  ";
       $sSqlProcessos .= "                               and pc23_orcamforne   = pc24_orcamforne  ";
       $sSqlProcessos .= "      inner join pcprocitem     on pc31_pcprocitem   = pc81_codprocitem "; 
       $sSqlProcessos .= "      inner join pcproc         on pc81_codproc      = pc80_codproc "; 
       $sSqlProcessos .= "      inner join solicitem      on pc81_solicitem    = pc11_codigo  ";
       $sSqlProcessos .= "      inner join solicitemunid  on pc11_codigo       = pc17_codigo  ";
       $sSqlProcessos .= "      inner join matunid        on pc17_unid         = m61_codmatunid  ";
       $sSqlProcessos .= "      inner join pcorcamforne   on pc23_orcamforne   = pc21_orcamforne "; 
       $sSqlProcessos .= "      inner join cgm            on pc21_numcgm       = z01_numcgm  ";
       $sSqlProcessos .= "      inner join solicitempcmater on pc16_solicitem  = pc11_codigo  ";
       $sSqlProcessos .= "      inner join pcmater          on pc16_codmater   = pc01_codmater  ";
       $sSqlProcessos .= "      inner join solicita         on pc11_numero     = pc10_numero  ";
       $sSqlProcessos .= " where pc80_data {$sWhereDatas}"; 
       $sSqlProcessos .= "   and pc24_pontuacao = 1 {$sWhere} ";
       $sSqlProcessos .= "   and pc10_solicitacaotipo = 1 ";
       $sSqlProcessos .= "limit {$iQuantidadeOrcamentosProcesso} ";
       $rsProcessos    = db_query($sSqlProcessos);
       
       $oRetorno->processodecompras = db_utils::getColectionByRecord($rsProcessos,false,false, true);
    }
    
    if ($iQuantidadeEmpenhosPagos > 0) {
      
      $sWhere = "";
      if ($iCodigoItem != null) {
        $sWhere .= " and pc16_codmater = {$iCodigoItem} ";         
      }
      
      if (count($aUnidades) > 0) {
        $sWhere .= " and pc17_unid in(".implode(",", $aUnidades).") ";      
      }
      
      if ($iFornecedor != null) {
         $sWhere .= " and e60_numcgm = {$iFornecedor}";
      }
      
      $sSqlEmpenhos  = "SELECT 3 as origem, "; 
      $sSqlEmpenhos .= "       e60_emiss as data,  ";
      $sSqlEmpenhos .= "       e62_vlrun as valorunitario,  ";
      $sSqlEmpenhos .= "       m61_abrev as descricaounidade,  ";
      $sSqlEmpenhos .= "       pc17_unid as unidade, ";
      $sSqlEmpenhos .= "       pc17_quant as quantidadeunidade,  ";
      $sSqlEmpenhos .= "       z01_numcgm as codigocgm,  ";
      $sSqlEmpenhos .= "       z01_nome as nomecgm,  ";
      $sSqlEmpenhos .= "      pc01_descrmater   as descricaomaterial ";
      $sSqlEmpenhos .= "  from solicitem ";
      $sSqlEmpenhos .= "       inner join solicitemunid    on pc11_codigo      = pc17_codigo  ";
      $sSqlEmpenhos .= "       inner join pcprocitem       on pc11_codigo      = pc81_solicitem  ";
      $sSqlEmpenhos .= "       inner join matunid          on pc17_unid        = m61_codmatunid  ";
      $sSqlEmpenhos .= "       inner join solicitempcmater on pc16_solicitem   = pc11_codigo  ";
      $sSqlEmpenhos .= "       inner join solicita         on pc11_numero      = pc10_numero  ";
      $sSqlEmpenhos .= "       inner join empempitem       on pc81_codprocitem = e62_sequen  ";
      $sSqlEmpenhos .= "       inner join empempenho       on e62_numemp       = e60_numemp  ";
      $sSqlEmpenhos .= "      inner join pcmater          on pc16_codmater   = pc01_codmater  ";
      $sSqlEmpenhos .= "       inner join cgm              on e60_numcgm       = z01_numcgm  ";
      $sSqlEmpenhos .= "  where e60_emiss {$sWhereDatas}"; 
      $sSqlEmpenhos .= "    {$sWhere}";
      $sSqlEmpenhos .= "    and e60_vlrpag  > 0 ";
      $sSqlEmpenhos .= "    and pc10_solicitacaotipo = 1 ";
      $sSqlEmpenhos .= "  limit {$iQuantidadeEmpenhosPagos} ";
      $rsEmpenhos    = db_query($sSqlEmpenhos);
      $oRetorno->empenhos =  db_utils::getColectionByRecord($rsEmpenhos, false,false, true);
    }
    return $oRetorno;
  }
  
  
  public function calculaMediaPrecoOrcamentos($aMediasPrecos) {
    
    /**
     * Calculamos a media das solicita��es
     */
     $nMediaPreco = 0;
     $iDividir    = 0;
     if (count($aMediasPrecos->solicitacoes) > 0) {
        
       $nPrecoSolicitacoes = 0;
       foreach ($aMediasPrecos->solicitacoes as $oSolicitacao) {
         $nPrecoSolicitacoes += $oSolicitacao->valorunitario;
       }
       $nMediaPreco += ($nPrecoSolicitacoes/count($aMediasPrecos->solicitacoes));
       $iDividir++;
     }
    //media dos processo de compras 
    if (count($aMediasPrecos->processodecompras) > 0) {
        
      $nPrecoProcesso = 0;
      foreach ($aMediasPrecos->processodecompras as $oProcesso) {
        $nPrecoProcesso += $oProcesso->valorunitario;
      }
      $nMediaPreco += ($nPrecoProcesso/count($aMediasPrecos->processodecompras));
      $iDividir++;
    }
    /**
     * Empenhos
     */ 
    if (count($aMediasPrecos->empenhos) > 0) {
        
      $nPrecoEmpenho = 0;
      foreach ($aMediasPrecos->empenhos as $oEmpenho) {
        $nPrecoEmpenho += $oEmpenho->valorunitario;
      }
      $nMediaPreco += ($nPrecoEmpenho/count($aMediasPrecos->empenhos));
      $iDividir++;
    }
    if ($iDividir == 0) {
      $nMediaPreco = 0; 
    } else {
      $nMediaPreco = $nMediaPreco/$iDividir;
    }
    
    return round($nMediaPreco, 2);
  }
  
  /**
   * Retorna todas a unidades que foram usadas em solicita��es pelo item.
   *
   * @param integer $iMaterial Codigo do material
   * @return array Unidades vinculadas
   */
  static function getUnidadesMaterial($iMaterial) {
    
    $sSqlUnidades  = "SELECT distinct m61_codmatunid as codigounidade,";
    $sSqlUnidades .= "       m61_abrev as descricaounidade";
    $sSqlUnidades .= "  from solicitemunid";
    $sSqlUnidades .= "       inner join solicitem on pc17_codigo  = pc11_codigo";
    $sSqlUnidades .= "       inner join matunid on m61_codmatunid = pc17_unid";
    $sSqlUnidades .= "       inner join solicitempcmater on pc16_solicitem = pc11_codigo";
    $sSqlUnidades .= " where pc16_codmater = {$iMaterial}";
    $rsUnidades    = db_query($sSqlUnidades);
    $aUnidades     = db_utils::getColectionByRecord($rsUnidades, false, false, true);
    return $aUnidades;
  }
  
  public function setVinculo(itemSolicitacao $oVinculo) {
    $this->oVinculo = $oVinculo;
  }
  
  public function getVinculo() {
    return $this->oVinculo;
  }
  
  /**
   * M�todo que busca o c�digo da reserva, valor reservado e dota��o
   *
   * @param integer $iCodigoDotacao
   * @return array
   */
  public function getReservasSaldoDotacao($iCodigoDotacao) {
    
    $aReservas          = array();
    $oDaoOrcReservaSol  = db_utils::getDao('orcreservasol');
    $sSqlReservaDotacao = $oDaoOrcReservaSol->sql_query_orcreserva(
                                                                   null,
                                                                   null,
                                                                   "o80_codres as codigoreserva,
                                                                   o80_valor as valor,
                                                                   o80_coddot as dotacao", 
                                                                   "", 
                                                                   "o82_pcdotac = {$iCodigoDotacao}");
    $rsReservaDotacao   = $oDaoOrcReservaSol->sql_record($sSqlReservaDotacao);
    
    for ($iRow = 0; $iRow < $oDaoOrcReservaSol->numrows; $iRow++) {
      
      $oDadosReserva = db_utils::fieldsMemory($rsReservaDotacao, $iRow);
      $aReservas[]   = $oDadosReserva;
    }
    
    return $aReservas;
  }
  
  /**
   * Exclui reserva de saldo pelo codigo da reserva
   *
   * @param integer $iCodigoReserva Codigo da reserva
   */
  public function excluiReservaSaldo($iCodigoReserva) {
    
    $oDaorcReserva    = db_utils::getDao("orcreserva");
    $oDaorcReservaSol = db_utils::getDao("orcreservasol");
    
    $oDaorcReservaSol->excluir(null,"o82_codres = {$iCodigoReserva}");
    if ($oDaorcReservaSol->erro_status == 0) {

      $sErro = "Erro ao Excluir dados da Reserva de Solicita��o ! \n";
      $sErro .= $oDaorcReservaSol->erro_msg;
      throw new Exception($sErro);
    }    
    
    $oDaorcReserva->excluir(null, " o80_codres = {$iCodigoReserva}");
    if ($oDaorcReserva->erro_status == 0) {

      $sErro = "Erro ao Excluir dados da reserva de or�amento ! \n";
      $sErro .= $oDaorcReserva->erro_msg;
      throw new Exception($sErro);
    }     
  }

  /**
   * Incluir reserva de saldo pelo codigo da dota�ao
   *
   * @param integer $iCodigoDotacao Codigo da dotacao
   * @param float   $nValorReserva  Valor da reserva
   */  
  public function incluirReservaSaldo(Dotacao $oDotacao, $nValorReserva) {
    
    $oDaorcReserva    = db_utils::getDao("orcreserva");
    $oDaorcReservaSol = db_utils::getDao("orcreservasol");  
    $oPcDotac         = db_utils::getDao("pcdotac");
    $sData            = date("Y-m-d",db_getsession("DB_datausu")); 
    

    $sMensagemReserva = "Solicita��o de Compras: {$this->iSolicitacao} item: {$this->iCodigoItemSolicitacao} - Altera��o Reserva";
    $oDaorcReserva->o80_anousu = db_getsession("DB_anousu");
    $oDaorcReserva->o80_coddot = $oDotacao->getCodigo();
    $oDaorcReserva->o80_dtfim  = db_getsession("DB_anousu").'-12-31';
    $oDaorcReserva->o80_dtini  = $sData;
    $oDaorcReserva->o80_dtlanc = $sData;
    $oDaorcReserva->o80_valor  = $nValorReserva;
    $oDaorcReserva->o80_descr  = $sMensagemReserva;
    $oDaorcReserva->incluir(null);
    
    
    if ($oDaorcReserva->erro_status == 0) {

      $sErro = "Erro ao Incluir dados da reserva de or�amento ! \n";
      $sErro .= $oDaorcReserva->erro_msg;
      throw new Exception($sErro);
    } 
    
    /*
     * consultamos na pcdotaq para descobrir o sequencial para passar o parametro  
     */
    $sSqlPcDotaq = $oPcDotac->sql_query_file($this->iCodigoItemSolicitacao,
                                             db_getsession("DB_anousu"),
                                             $oDotacao->getCodigo(),
                                             "pc13_sequencial",
                                             null,
                                             null
                                        );
    $rsPcDotaq = $oPcDotac->sql_record($sSqlPcDotaq);
    $iPcDotaq  = db_utils::fieldsMemory($rsPcDotaq, 0)->pc13_sequencial;                                   
    
    $oDaorcReservaSol->o82_codres    = $oDaorcReserva->o80_codres;
    $oDaorcReservaSol->o82_solicitem = $this->iCodigoItemSolicitacao;
    $oDaorcReservaSol->o82_pcdotac   = $iPcDotaq;
    $oDaorcReservaSol->incluir(null);
    if ($oDaorcReservaSol->erro_status == 0) {

      $sErro = "Erro ao Incluir dados da Solicita��o da reserva ! \n";
      $sErro .= $oDaorcReservaSol->erro_msg;
      throw new Exception($sErro);
    }     
  
  }
  
  public function getDotacoes() {
  	
  	if (count($this->aDotacoes) == 0) {

	  	$aPcDotac    = array();
	    $oPcDotac    = db_utils::getDao("pcdotac");
	    $sSqlPcDotac = $oPcDotac->sql_query_dotreserva( $this->iCodigoItemSolicitacao, null, null, "*", null, null);
	    $rsPcDotac   = $oPcDotac->sql_record($sSqlPcDotac);
	    $aPcDotac    = db_utils::getColectionByRecord($rsPcDotac, false, false, true);
	    foreach ($aPcDotac as $iIndPcDotac => $oValorPcDotac){
	    	
	    	$oDotacao                     = new stdClass();
	    	$oDotacao->oDotacao           = new Dotacao($oValorPcDotac->o58_coddot, $oValorPcDotac->o58_anousu);
	    	$oDotacao->iCodigoDotacaoItem = $oValorPcDotac->pc13_sequencial;
	    	$oDotacao->iCodigoReserva     = $oValorPcDotac->o80_codres;
	    	$oDotacao->nValorReservado    = $oValorPcDotac->o80_valor;
	    	$oDotacao->nValorDotacao      = $oValorPcDotac->pc13_valor;
	    	$this->aDotacoes[]            = $oDotacao;
	    }
  	}
    return $this->aDotacoes;
  }
  
  /**
   * Realiza a altera��o de uma determinada dota��o por outra.
   * @param integer $iCodigoDotacaoItem C�digo da dota��o do item pcdotac.pc13_sequencial;
   * @param integer $iCodigoDotacao Codigo da Dota��o no ano
   * @param integer $iAnoDotacao Ano da Dota��o;
   */
  public function alterarDotacao($iCodigoDotacaoItem, $iCodigoDotacao, $iAnoDotacao) {
    
    $iAnoSessao = db_getsession("DB_anousu");
    
    if (empty($iCodigoDotacao)) {
      
      throw new Exception("ERRO [ 0 ] - Dota��o n�o Informada.");
     
    }
    
    
    if (empty($iAnoDotacao)) {
      $iAnoDotacao = $iAnoSessao;
    } 
    $sNomeItem  = "{$this->getOrdem()} - {$this->getDescricaoMaterial()}";
    
    if (!db_utils::inTransaction()) {
      throw new Exception('ERRO [ 1 ] - N�o existe transa��o com o banco de dados.');  
    }

    if ($iAnoDotacao != $iAnoSessao) {
      throw new Exception("Dotacao {$iCodigoDotacao}/{$iAnoDotacao} deve ser uma dota��o do Exerc�cio");
    
    }
    
    if (empty($iCodigoDotacaoItem)) {
      
      $oSolicitacao  = new solicitacaoCompra($this->iSolicitacao);
      $oDaoPcDotac   = db_utils::getDao("pcdotac");
      $oDaoSolicitem = db_utils::getDao("solicitem");
      
      /*
       * buscamos dados da solicitem e elemento
       */
      $sSqlSolicitem = $oDaoSolicitem->sql_query_desdobramento(null, "*", null, "pc11_codigo = {$this->iCodigoItemSolicitacao}" );
     // echo $sSqlSolicitem; die();
      
      $rsSolicitem   = $oDaoSolicitem->sql_record($sSqlSolicitem);
      if ($oDaoSolicitem->numrows <= 0) {
        throw new Exception("ERRO [ 2 ] - Altera��o de Dota��o N�o Efetuada - Elemento da Solicita��o n�o Encontrado!! "); 
      }
      
      $iDepartamento   = $oSolicitacao->getDepartamento();
      $oDadosSolicitem = db_utils::fieldsMemory($rsSolicitem, 0);
      $iCodElemento    = $oDadosSolicitem->pc18_codele;
      $iQuantidade     = $oDadosSolicitem->pc11_quant ;   
      $nValor          = $oDadosSolicitem->pc11_vlrun;
      
      $oDaoPcDotac->pc13_anousu     = $iAnoDotacao;
      $oDaoPcDotac->pc13_coddot     = $iCodigoDotacao;
      $oDaoPcDotac->pc13_codigo     = $this->iCodigoItemSolicitacao;
      $oDaoPcDotac->pc13_depto      = $iDepartamento;
      $oDaoPcDotac->pc13_quant      = $iQuantidade;
      $oDaoPcDotac->pc13_valor      = $nValor;
      $oDaoPcDotac->pc13_codele     = $iCodElemento;
      $oDaoPcDotac->incluir(null);
      if ($oDaoPcDotac->erro_status == "0" || $oDaoPcDotac->erro_status == 0) {
        
        throw new Exception("ERRO [ 3 ] - Incluindo Dota��o - " . $oDaoPcDotac->erro_msg);
        
      }
      
      $oDotacao = new Dotacao($iCodigoDotacao, $iAnoSessao);
      $nValor   = ($iQuantidade * $nValor);
      
      if ($oDotacao->getSaldoAtualMenosReservado() < $nValor) {
      
        $sMsgErro  = "Dota��o {$iCodigoDotacao} sem saldo suficiente para a reserva do  item. {$sNomeItem}.\n";
        $sMsgErro .= "Saldo da Dota��o: ".trim(db_formatar($oDotacao->getSaldoAtualMenosReservado(), "f")).".\n";
        $sMsgErro .= "Valor Solicitado Para Reserva: ".trim(db_formatar($nValor, "f"))."";
        throw new Exception($sMsgErro);
      }
      
      $this->incluirReservaSaldo($oDotacao, $nValor);
    } else {
    
      $oPcDotac    = db_utils::getDao("pcdotac");
      $sWhere      = "pc13_sequencial = {$iCodigoDotacaoItem}";
      $sSqlPcDotac = $oPcDotac->sql_query_dotreserva(null, null, null, "*", null, $sWhere);
      $rsPcDotac   = $oPcDotac->sql_record($sSqlPcDotac);
      
      if ($oPcDotac->numrows > 0) {
        
        $oDadosDotacao = db_utils::fieldsMemory($rsPcDotac, 0);
        /**
         * Retiramos o saldo da Reserva do item.
         */
        if (!empty($oDadosDotacao->o80_codres)) {
          $this->excluiReservaSaldo($oDadosDotacao->o80_codres);
        }
        
        $oPcDotac->pc13_coddot     = $iCodigoDotacao;
        $oPcDotac->pc13_anousu     = $iAnoSessao; 
        $oPcDotac->pc13_sequencial = $iCodigoDotacaoItem;
        $oPcDotac->alterar($iCodigoDotacaoItem);
        if ($oPcDotac->erro_status == 0) {
  
          $sMsgErro  = "Erro ao alterar dota��o do item  {$sNomeItem}. ";
          $sMsgErro .= "Foram encontradas inconsistencias nos dados.\n";
          $sMsgErro .= str_replace("\\n", "\n", $oPcDotac->erro_msg);
          throw new Exception($sMsgErro);
        }
        
        $oDotacao = new Dotacao($iCodigoDotacao, $iAnoSessao);
        if ($oDotacao->getSaldoAtualMenosReservado() < $oDadosDotacao->pc13_valor) {
          
          $sMsgErro  = "Dota��o {$iCodigoDotacao} sem saldo suficiente para a reserva do  item. {$sNomeItem}.\n";
          $sMsgErro .= "Saldo da Dota��o: ".trim(db_formatar($oDotacao->getSaldoAtualMenosReservado(), "f")).".\n";
          $sMsgErro .= "Valor Solicitado Para Reserva: ".trim(db_formatar($oDadosDotacao->pc13_valor, "f"))."";
          throw new Exception($sMsgErro);
        }
        $this->incluirReservaSaldo($oDotacao, $oDadosDotacao->pc13_valor);
      }
    
    }
    
  }

  /**
   * Seta se o item de servi�o controla quantidade
   * @param boolean $lServicoControlaQuantidade
   */
  public function setServicoControlaQuantidade($lServicoControlaQuantidade) {
    
    $this->lServicoControlaQuantidade = $lServicoControlaQuantidade;
  }
  
  /**
   * retorna se o item de servi�o 
   * @return booleam
   */
  public function itemServicoControlaQuantidade(){
    
    return $this->lServicoControlaQuantidade;
  }


  public static function getDescricaoUnidade ($iCodigoUnidade) {

    $oDAOSolicitemunid = db_utils::getDao("matunid");
    $sSqlUnidade       = $oDAOSolicitemunid->sql_query_file(null, "*", null, "m61_codmatunid = $iCodigoUnidade");
    $rsUnidade         = $oDAOSolicitemunid->sql_record($sSqlUnidade);

    if ($oDAOSolicitemunid->numrows > 0) {
      return db_utils::fieldsMemory($rsUnidade, 0)->m61_descr;
    }
    return null;
  }
  
  /**
   * metodo para retornar valor orcado na solicita��o
   * @return float $nValorOrcado
   */
  public function getValorOrcadoItemSolicitacao(){

    $oDaoSolicitem = db_utils::getDao("solicitem");
    $nValorOrcado  = 0;
    $sSqlSolicitem =  $oDaoSolicitem->sql_query_JulgamentoOrcamento($this->getCodigoItemSolicitacao(),"pc23_valor");
    $rsSolicitem   =  $oDaoSolicitem->sql_record($sSqlSolicitem);
    if ($oDaoSolicitem->numrows == 0) {
      return $nValorOrcado;
    }
    $nValorOrcado = db_utils::fieldsMemory($rsSolicitem, 0)->pc23_valor;
    return $nValorOrcado;
  }
  
  /**
   * metodo que verifica todos itens de uma determinada solicita��o
   * se um deles nao possuir dota��o vinculada retorna 1
   * @param integer  $iSolicitacao
   * @return integer
   */
  public static function verificaItemSolicitacaoSemDotacao($iSolicitacao){
    
    
    $oDaoSolicita                 = db_utils::getDao("solicita");
    $sSqlVerificaItensSolicitacao = $oDaoSolicita->sql_query_licitacao_dotacao(null, "pc13_coddot", null, "pc10_numero = {$iSolicitacao}");
    $rsSolicita                   = $oDaoSolicita->sql_record($sSqlVerificaItensSolicitacao);
    $ItemSemDotacao               = 0;
    for ($iRegistro = 0; $iRegistro < $oDaoSolicita->numrows; $iRegistro++) {
      
      $ItemSemDotacao = 0;
      $iDotacao       = db_utils::fieldsMemory($rsSolicita, $iRegistro)->pc13_coddot;
      
      if ($iDotacao == "" || $iDotacao == null) {
        
        $ItemSemDotacao = 1;
      }
    }
    
    return $ItemSemDotacao;
  }
}
?>