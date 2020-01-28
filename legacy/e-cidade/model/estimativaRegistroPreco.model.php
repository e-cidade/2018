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

require_once ('classes/solicitacaocompras.model.php');
require_once ("model/itemSolicitacao.model.php");
require_once ("model/ItemEstimativa.model.php");

/**
 * Cria um nova Abertura para um registro de Preço 
 * @package Compras
 */
class estimativaRegistroPreco extends solicitacaoCompra {


  /**
   * Itens da Estimativa
   * @var ItemEstimativa[]
   */
  protected $aItens = array();
  
  protected $iCodigoSolicitacao;
  
  protected $dtDataSolicitacao;
  
  protected $iAberturaRegistroPreco;
  
  protected $sResumo;
  
  private $iTipoSolicitacao = 4;
  
  protected $lLiberado  = false;
  
  protected $iCodigoDepartamento;
  
  protected $sDescricaoDepartamento;
  
  protected $sDataAnulacao;
  
  protected $oParametroRegistroPreco;

  protected $iTotalItens  = 0;
  
  /**
   * estimativa Alterada automaticamente.
   */
  protected $lAlterado = true;

  /**
   *
   * @param null $iEstimativaRegistro
   */
  public function __construct($iEstimativaRegistro = null) {
     
    if (!empty ($iEstimativaRegistro)) {

      parent::__construct($iEstimativaRegistro);
      $oDaoRegistroPreco = db_utils::getDao("solicita");
      $sCampos = "*, (select count(*) from solicitem where pc11_numero = pc10_numero) as totalitens";
      $sSqlDadosRegistro = $oDaoRegistroPreco->sql_query_estimativa($iEstimativaRegistro, $sCampos);
      $rsDadosRegistro   = $oDaoRegistroPreco->sql_record($sSqlDadosRegistro);
      if ($oDaoRegistroPreco->numrows) {

        $oDadosRegistro               = db_utils::fieldsMemory($rsDadosRegistro, 0);
        $this->iCodigoSolicitacao     = $oDadosRegistro->pc10_numero;
        $this->sResumo                = $oDadosRegistro->pc10_resumo;
        $this->dtDataSolicitacao      = $oDadosRegistro->pc10_data;
        $this->iAberturaRegistroPreco = $oDadosRegistro->pc53_solicitapai;
        $this->iCodigoDepartamento    = $oDadosRegistro->coddepto;
        $this->sDescricaoDepartamento = $oDadosRegistro->descrdepto;
        $this->sDataAnulacao          = $oDadosRegistro->pc67_data;
        $this->iTotalItens            = $oDadosRegistro->totalitens;
        $this->lAlterado              = $oDadosRegistro->pc10_correto=='t'?true:false;
        
      } else {
        
      }
      
    }
  }
  
 
  /**
   * Retorna o Código do departamento
   * @return integer
   */  
  public function getCodigoDepartamento() {
    
    return $this->iCodigoDepartamento;
  }
  /**
   * Retorna o Código do departamento
   * @return string
   */  
  public function getDescricaoDepartamento() {
    
    return $this->sDescricaoDepartamento;
  }
  /**
   * Retorna o Código do departamento
   * @return string
   */  
  public function getDataAnulacao() {
    
    return $this->sDataAnulacao;
  }


  /**
   * Adiciona um item ao Registro de Compras
   *
   * @param itemEstimativa $oItem
   * @return aberturaRegistroPreco
   */
  public function addItem(itemEstimativa $oItem) {

    $this->iTotalItens++;
    $oItem->setOrdem($this->iTotalItens);
    $this->aItens[] = $oItem;
    return $this;
    
  }
  /**
   * Retorna os itens cadastrados na solicitacao
   *
   * @return ItemEstimativa[]
   */
  public function getItens() {
    
    $sCampoOrdem = 'pc11_codigo';
    switch (ParametroRegistroPreco::getOrdenacaoEstimativa()) {
      
      case 1:
        
        $sCampoOrdem = 'pc11_codigo';
        break;
      case 2 :
        
        $sCampoOrdem = 'pc01_descrmater';
        break;  
    }
    if ($this->iCodigoSolicitacao != "" && count($this->aItens) == 0) {
      
      $oDaoSolicitem = db_utils::getDao("solicitem");
      $sSqlItens     = $oDaoSolicitem->sql_query_vinculo(null,"*", $sCampoOrdem, "pc11_numero={$this->iCodigoSolicitacao}");
      $rsItens       = $oDaoSolicitem->sql_record($sSqlItens);
      if ($oDaoSolicitem->numrows > 0) {
       
        for ($iItem = 0; $iItem < $oDaoSolicitem->numrows; $iItem++) {

          $oItem = db_utils::fieldsMemory($rsItens, $iItem, false, false, true);
          $oItemSolicitacao = new itemEstimativa($oItem->pc11_codigo);
          $oItemSolicitacao->setAutimatico($oItem->pc55_solicitemfilho!=""?true:false);
          $this->aItens[]   = $oItemSolicitacao;
          unset($oItem);
          
        }
      }
    }
    
    return $this->aItens;
  }


  /**
   * Salva os dados da Solicitaçao na base de dados
   *
   * @throws Exception
   * @return aberturaRegistroPreco
   */
  public function save() {
    
    $oDaoSolicitacao = db_utils::getDao("solicita");
    $oDaoSolicitacao->pc10_correto         = $this->isAlterado()?"true":"false";
    $oDaoSolicitacao->pc10_data            = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoSolicitacao->pc10_resumo          = $this->getResumo();
    $oDaoSolicitacao->pc10_solicitacaotipo = $this->iTipoSolicitacao;
    if ($this->getCodigoSolicitacao() == null) {
      
      $oDaoSolicitacao->pc10_depto           = db_getsession("DB_coddepto");
      $oDaoSolicitacao->pc10_instit          = db_getsession("DB_instit");
      $oDaoSolicitacao->pc10_login           = db_getsession("DB_id_usuario");
      $oDaoSolicitacao->incluir(null);
      $this->iCodigoSolicitacao   = $oDaoSolicitacao->pc10_numero;
      
    } else {
      
      $oDaoSolicitacao->pc10_numero = $this->getCodigoSolicitacao();
      $oDaoSolicitacao->alterar($this->getCodigoSolicitacao());
      
    }
    if ($oDaoSolicitacao->erro_status == 0) {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoSolicitacao->erro_msg}");
    }
    
    /**
     * salvamos os dados da Abertura
     */    
    $oDaoAberturaPreco = db_utils::getDao("solicitavinculo");
    $oDaoAberturaPreco->pc53_solicitapai   = $this->iAberturaRegistroPreco;
    $oDaoAberturaPreco->pc53_solicitafilho = $this->getCodigoSolicitacao();
    $sSqlVerificaVinculo  = $oDaoAberturaPreco->sql_query_file(null,"*", 
                                                               null,
                                                               "pc53_solicitafilho={$this->getCodigoSolicitacao()}"
                                                               );
                                                               
    $rsVerificaVinculo  = $oDaoAberturaPreco->sql_record($sSqlVerificaVinculo);
                                                                    
    if ($oDaoAberturaPreco->numrows > 0) {
      
      $oVinculoSolicitacao = db_utils::fieldsMemory($rsVerificaVinculo, 0);
      $oDaoAberturaPreco->pc53_sequencial = $oVinculoSolicitacao->pc53_sequencial;
      $oDaoAberturaPreco->alterar($oVinculoSolicitacao->pc53_sequencial);
      
    } else {
      $oDaoAberturaPreco->incluir(null);
    }
    if ($oDaoAberturaPreco->erro_status == 0) {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoSolicitacao->erro_msg}");
    }
    
    unset($oDaoAberturaPreco);
    unset($oDaoSolicitacao);
    if (count($this->getItens()) == 0) {
      
      /**
       * incluimos os itens da abertura na solicitacao 
       */
      require_once('model/aberturaRegistroPreco.model.php');
      $oAbertura = new aberturaRegistroPreco($this->getCodigoAbertura());
      $aItens    = $oAbertura->getItens();
      foreach ($aItens as $oItem) {
        
        $iCodigoOrigem = $oItem->getCodigoItemSolicitacao();

        $oItemNovo     =  new ItemEstimativa(null, $oItem->getCodigoMaterial()); 
        $oItemNovo->setCodigoOrigem($iCodigoOrigem);
        $oItemNovo->setUnidade($oItem->getUnidade());
        $oItemNovo->setQuantidadeUnidade($oItem->getQuantidadeUnidade());
        $oItemNovo->setOrdem($oItem->getOrdem());
        $oItemNovo->setJustificativa($oItem->getJustificativa());
        $oItemNovo->setResumo($oItem->getResumo());
        $oItemNovo->setPrazos($oItem->getPrazos());
        $oItemNovo->setPagamento($oItem->getPagamento());
        $oItemNovo->setAutimatico(true);
        $oItemNovo->setQuantidade($oItem->getQuantidade());
        $this->addItem($oItemNovo);
      }
    }
    $iSeq = 1;
    foreach ($this->aItens as $oItem) {
      
      $oItem->setOrdem($iSeq);
      if ($oItem->getVinculo() != "") {
        $oItem->setCodigoOrigem($oItem->getVinculo()->getCodigoItemSolicitacao());
      }
      $oItem->save($this->iCodigoSolicitacao);
      $iSeq++;
    }
    return $this;
  }
  /**
   * @return string
   */
  public function getDataInicio() {

    return $this->dtDataInicio;
  }
  
  /**
   * @param string $dtDataInicio
   */
  public function setDataInicio($dtDataInicio) {

    $this->dtDataInicio = $dtDataInicio;
  }
  
  /**
   * Retorna a data da inclusão da solicitação
   * @return string
   */
  public function getDataSolicitacao() {
    return $this->dtDataSolicitacao;
  }
  
  /**
   * Retorna o Codigo da Abertura de Preço
   * @return  integer
   */
  public function getCodigoAbertura() {
    return $this->iAberturaRegistroPreco;
  }
  
  public function setCodigoAbertura($iCodigoAbertura) {
    $this->iAberturaRegistroPreco = $iCodigoAbertura;
  }
  
  /**
   * Retorna o codigo da solicitação de Compras Criadas para o registro de compra
   * @return integer
   */
  public function getCodigoSolicitacao() {
    return $this->iCodigoSolicitacao;
  }
  
  /**
   * retorno a resumo da Abertura
   * @return string
   */
  public function getResumo() {
    return $this->sResumo;
  }
  
  /**
   * 
   * Define o resumo da Abertura
   * @param string $sResumo Resumo 
   * @return aberturaRegistroPreco 
   */
  public function setResumo($sResumo) {

    $this->sResumo = $sResumo;
    return $this;
    
  }

  /**
   * Retorna o tipo da solicitação Criada 
   *
   * @return integer
   */
  public function getTipoSolicitacao() {
    return $this->iTipoSolicitacao;
  }
  
  /**
   * 
   * Item verificado
   * @return boolean
   */
  public function isLiberado() {
    return  $this->lLiberado;
  }
  /**
   * Define se o item está liberado ou nao 
   *
   * @param boolean $lLiberado
   */
  public function setLiberado($lLiberado) {
   $this->lLiberado = $lLiberado;    
  }
  
  /**
   * define se a estimativa foi alterada ao não.
   *
   * @param boolean $lAlterado 
   */
  public function setAlterado($lAlterado) {
    
    $this->lAlterado = $lAlterado;
  }
  
  
  public function isAlterado() {
    return $this->lAlterado;
  }

  /**
   * Remove o Item informado da solicitacao;
   *
   * @param  integer $iSeq item a ser removido
   * @throws Exception
   * @return aberturaRegistroPreco
   */
  public function removerItem($iSeq) {

    if ($iSeq >= 0) {
      
      $aItens = $this->getItens();
      
      if (isset($aItens[$iSeq])) { 
        
        $aItens[$iSeq]->remover();
        unset($this->aItens[$iSeq]);
      }
    }
    return $this;   
  }

  /**
   * Anula
   *
   * @param $sMotivo
   * @throws Exception
   * @return void
   */
  
  public function anular($sMotivo) {
    
    $lSolicitaAnulada = $this->isAnulada();
    
    if (!$lSolicitaAnulada) {
      
      $oDaoSolicitaAnulada                = db_utils::getDao("solicitaanulada");
      $oDaoSolicitaAnulada->pc67_usuario  = db_getsession("DB_id_usuario");
      $oDaoSolicitaAnulada->pc67_data     = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoSolicitaAnulada->pc67_hora     = date("H:m",db_getsession("DB_datausu"));    
      $oDaoSolicitaAnulada->pc67_solicita = $this->getCodigoSolicitacao();
      $oDaoSolicitaAnulada->pc67_motivo   = $sMotivo; 
      $oDaoSolicitaAnulada->incluir(null);

      if ($oDaoSolicitaAnulada->erro_status == "0") {
        throw new Exception("Erro ao anular Estimativa de Registro de Preço!\n\n{$oDaoSolicitaAnulada->erro_msg}");
      }
    } 
    
  }
  
  /**
   * Verifica se a compilação está anulada 
   * 
   * @return boolean
   */
  
  public function isAnulada() {
    
    $oDaoSolicitaAnulada = db_utils::getDao("solicitaanulada");
    $sWhere   = "pc67_solicita = ".$this->getCodigoSolicitacao();
    $sCampos  = "*";
    
    $sSqlSolicitaAnulada  = $oDaoSolicitaAnulada->sql_query_file(null,$sCampos,null,$sWhere);
    $oDaoSolicitaAnulada->sql_record($sSqlSolicitaAnulada);
    
    if ($oDaoSolicitaAnulada->numrows > 0) {

      return true;    
    } else {
      
      return false;
    }
  }
  
 /**
  * retorna o item da estimativa por codigo de inclusão 
  *
  * @param integer $iCodigo Codigo do item 
  * @return itemSolicitacao
  */
  public function getItemByCodigo($iCodigo) {
    
    if (count($this->getItens()) == 0) {
      $this->getItens();
    }
    $oItemRetorno = null;
    foreach ($this->aItens as $oItem) {

      
      if ($oItem->getCodigoItemSolicitacao() == $iCodigo) {
        
        $oItemRetorno = $oItem;
        break;
      }
    }
    return $oItemRetorno;
  }
  
/**
  * retorna o  indice do item da estimativa por codigo de inclusão 
  *
  * @param integer $iCodigoOrigem Codigo do item de origem do item da estimativa 
  * @return itemSolicitacao
  */
  public function getItemByCodigoOrigem($iCodigoOrigem) {
    
    if (count($this->getItens()) == 0) {
      $this->getItens();
    }
    $iIndice = 0;
    $lAchou  = false;
    foreach ($this->aItens as $oItem) {
      if ($oItem->getCodigoOrigem() == $iCodigoOrigem) {

        $lAchou = true;
        break;
      }
      $iIndice++;
    }
    if (!$lAchou) {
      $iIndice  = -1;
    }
    return $iIndice;
  }

  /**
   * Efetua a cedencia de saldo entre departamentos
   *
   * @param  integer $iDeptoDestino Código do departamento que vai receber o saldo
   * @param  array   $aListaItens   Lista de itens que devem ser cedidos para o departamento de destino (array de objetos)
   * @param  string  $sResumo       Resumo da cedencia de saldo
   * @throws Exception
   * @return boolean
   */
  public function cederMaterial($iDeptoDestino, $aListaItens, $sResumo = '') {

  	 if (!db_utils::inTransaction()) {
  	   throw new Exception('Não existe transação com o banco de dados.');
  	 } 
    /*
     * Valida se o departamento de destino está participando do R.P.
     * Se o resultado for positivo armazena a solicitaçãofilho
     */
    $oDaoSolicitaVinculo      = db_utils::getDao("solicitavinculo");
    $sCamposBuscaParticipacao = " estrecebe.*, recebe.pc10_depto ";
    $sWhereBuscaParticipacao  = "     estcedente.pc53_solicitafilho = {$this->getCodigoSolicitacao()} ";
    $sWhereBuscaParticipacao .= " and recebe.pc10_depto             = {$iDeptoDestino}                ";
    $sWhereBuscaParticipacao .= " and recebe.pc10_solicitacaotipo   = 4                               ";
    $sWhereBuscaParticipacao .= " and pc67_solicita                is null                            ";
    $sSqlBuscaParticipacao    = $oDaoSolicitaVinculo->sql_query_aberturaestimativa(null, $sCamposBuscaParticipacao,
                                                                                   null, $sWhereBuscaParticipacao);
    $rsBuscaParticipacao      = $oDaoSolicitaVinculo->sql_record($sSqlBuscaParticipacao);
    
    if ($oDaoSolicitaVinculo->numrows == 0) {
      
      $sMensagem  = "O departamento {$iDeptoDestino} não está incluso na abertura ";
      $sMensagem .= "de registro de preço {$this->getCodigoSolicitacao()}.        ";
      throw new Exception($sMensagem);
    }
    
    $iCodigoEstimativaRecebe = db_utils::fieldsMemory($rsBuscaParticipacao, 0)->pc53_solicitafilho;
    /*
     * Valida item por item se o departamento de destino pediu o item corrente no R.P.
     */
    $oDaoSolicitem = db_utils::getDao("solicitem");
    
    foreach ($aListaItens as $oItem) {
      
      $oItemEstimativa  = new ItemEstimativa($oItem->itemcedente);
      $sMensagem        = "O departamento {$iDeptoDestino} não solicitou o item ";
      $sMensagem       .= "{$oItemEstimativa->getOrdem()} - {$oItemEstimativa->getDescricaoMaterial()} ";
      $sMensagem       .= "na abertura de registro de preço {$this->getCodigoSolicitacao()}.";
      if (empty($oItem->itemcedente)) {
        throw new Exception($sMensagem."\n1asdfasdf");
      }
      $sWhereBuscaSolicitem  = " solicitem.pc11_numero = {$iCodigoEstimativaRecebe} ";
      $sWhereBuscaSolicitem .= " and pc11_codigo       = {$oItem->itemrecebe}";
      $sqlBuscaSolicitemPai  = $oDaoSolicitem->sql_query_file(null, "*", null, $sWhereBuscaSolicitem);
      $rsBuscaSolicitemPai   = $oDaoSolicitem->sql_record($sqlBuscaSolicitemPai);
      if ($oDaoSolicitem->numrows == 0) {
        throw new Exception($sMensagem);
      }
    }
    
    /*
     * Se não houver nenhuma excessão o método incluirá a cedencia e seus respectivos itens
     */
    $oDaoRegistroPrecoCedencia     = db_utils::getDao("registroprecocedencia");
    $oDaoRegistroPrecoCedenciaItem = db_utils::getDao("registroprecocedenciaitem");
      
    $oDaoRegistroPrecoCedencia->pc37_usuario = db_getsession('DB_id_usuario');
    $oDaoRegistroPrecoCedencia->pc37_data    = date('Y-m-d');
    $oDaoRegistroPrecoCedencia->pc37_resumo  = $sResumo;
    $oDaoRegistroPrecoCedencia->incluir(null);
    
    /*
     * Se a cedência foi inclusa, percorremos o array de itens para incluí-los um a um
     */
    
    if ($oDaoRegistroPrecoCedencia->numrows_incluir) {
      
      foreach ($aListaItens as $oItem) {
        
      	/*
      	 * avalia se o saldo disponível possibilita a transação para o item corrente
      	 */
      	$oItemEstimativa  = new ItemEstimativa ($oItem->itemcedente);
      	$oSaldos          = $oItemEstimativa->getMovimentacao();
      	if ($oSaldos->saldo < $oItem->quantidade) {
      		
      		$sMensagem  = "O item {$oItemEstimativa->getOrdem()} - {$oItemEstimativa->getDescricaoMaterial()} ";
          $sMensagem .= "não possui saldo para a realização da cedencia.\n";
          $sMensagem .= "Saldo Restante: {$oSaldos->saldo}";
      		throw new Exception($sMensagem);
      	}
      	
      	/*
      	 * Inclui o item corrente
      	 */
        $oDaoRegistroPrecoCedenciaItem->pc36_solicitemdestino      = $oItem->itemrecebe;
        $oDaoRegistroPrecoCedenciaItem->pc36_solicitemorigem       = $oItem->itemcedente;
        $oDaoRegistroPrecoCedenciaItem->pc36_registroprecocedencia = $oDaoRegistroPrecoCedencia->pc37_sequencial;
        $oDaoRegistroPrecoCedenciaItem->pc36_quantidade            = $oItem->quantidade;
        $oDaoRegistroPrecoCedenciaItem->incluir(null);
        if (!$oDaoRegistroPrecoCedenciaItem->numrows_incluir) {
          throw new Exception("Erro ao incluir o item da cedência: {$oDaoRegistroPrecoCedenciaItem->erro_msg}.");
        }
      }
    } else {
      throw new Exception("Erro ao incluir a cedência: {$oDaoRegistroPrecoCedencia->erro_msg}.");
    }
    return true;
  }
}

?>
