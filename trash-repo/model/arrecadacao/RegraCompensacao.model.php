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
 * Model R�gra de Compensa��o
 * 
 * Classe que define as regras de compensa��o de um cr�dito
 * 
 * @author alberto <alberto@dbseller.com.br>
 * @author robson.silva <robson.silva@dbseller.com.br>
 * 
 * @version $
 * @package Arrecadacao 
 *
 */
class RegraCompensacao {
  /**
   * C�digo da regra de compensa��o
   * @var integer
   */
  private $iCodigoRegraCompensacao;
  
  /**
   * C�digo do tipo de regra de compensa��o
   * @var integer
   */
  private $iTipoRegraCompensacao;
  
  /**
   * Descri��o da regra de compensa��o
   * @var string
   */
  private $sDescricao;
  
  /**
   * C�digo do tipo de d�bito de origem
   * @var integer
   */
  private $iCodigoTipoDebitoOrigem;
  
  /**
   * C�digo do tipo de d�bito de destino
   * @var unknown
   */
  private $iCodigoTipoDebitoDestino;
  
  /**
   * Percentual de uso
   * @var float
   */
  private $nPercentualUso;
  
  /**
   * Tempo de validade que a regra dar� ao cr�dito em dias
   * @var integer
   */
  private $iTempoValidade;
  
  /**
   * Data de validade que a regra dar� ao cr�dito
   * @var DBDate
   */
  private $oDataValidade;
  
  /**
   * Definir� se o cr�dito ser� compensado automaticamente
   * @var boolean
   */
  private $lCompensacaoAutomatica;
  
  /**
   * Definir� se o cr�dito poder� ser transferido para outro CGM
   * @var boolean
   */
  private $lPermiteTransferencia; 
  
  /**
   * C�digo da institui��o
   * @var integer
   */
  private $iInstituicao;
  
  /**
   * C�digo da receita do recibo
   * @var integer
   */
  private $iCodigoReceitaRecibo;
  
  /**
   * Data em que foi lan�ado o cr�dito
   * @var DBDate
   */
  private $oDataLancamento;
  
  const DESCONTO_MANUAL          = 1;
  
  const DESCONTO_POR_REGRA       = 2;
  
  const DESCONTO_QUOTA_UNICA     = 3;
  
  const INCENTIVOS_FISCAIS       = 4;
  
  const ISENCAO_PARCIAL          = 5;
  
  const ISENCAO_TOTAL            = 6;
  
  const LANCAMENTO_MANUAL        = 7;
  
  const PAGAMENTO_A_MAIOR        = 8;
  
  const PAGAMENTO_EM_DUPLICIDADE = 9;
  
  const REABERTURA_DE_DEBITO     = 10;
  
  const TRANSFERENCIA            = 11;
  

  /**
   * Construtor da classe.
   * Caso seja informado o c�digo da regra de compensa��o, ser� salvo em mem�ria os dados da regra.
   * @param integer $iCodigoRegraCompensacao
   * @throws Exception Se c�digo informado n�o for encontrado.
   */
  public function __construct($iCodigoRegraCompensacao = null) {
    
    if (!empty($iCodigoRegraCompensacao)) {
      
      $oDaoRegraCompensacao = db_utils::getDao("regracompensacao");
      
      $sCampos  = "regracompensacao.k155_sequencial          ,";
      $sCampos .= "regracompensacao.k155_tiporegracompensacao,";
      $sCampos .= "regracompensacao.k155_descricao           ,";
      $sCampos .= "regracompensacao.k155_arretipoorigem      ,";
      $sCampos .= "regracompensacao.k155_arretipodestino     ,";
      $sCampos .= "regracompensacao.k155_percmaxuso          ,";
      $sCampos .= "regracompensacao.k155_tempovalidade       ,";
      $sCampos .= "regracompensacao.k155_automatica          ,";
      $sCampos .= "regracompensacao.k155_permitetransferencia,";
      $sCampos .= "regracompensacao.k155_instit              ,";
      $sCampos .= "arretipoorigem.k00_receitacredito          ";
      
      $sSqlRegraCompensacao = $oDaoRegraCompensacao->sql_query($iCodigoRegraCompensacao, $sCampos);
      
      $rsRegraCompensacao = $oDaoRegraCompensacao->sql_record($sSqlRegraCompensacao);
      
      if($oDaoRegraCompensacao->numrows == "0"){
        throw new Exception("N�o foi encontrado regra configurada com o c�digo {$iCodigoRegraCompensacao}");
      }
      
      $oRegraCompensacao = db_utils::fieldsMemory($rsRegraCompensacao, 0);
      
      $this->setCodigoRegraCompensacao    ($oRegraCompensacao->k155_sequencial);
      $this->setTipoRegraCompensacao      ($oRegraCompensacao->k155_tiporegracompensacao);
      $this->setDescricao                 ($oRegraCompensacao->k155_descricao);
      $this->setCodigoTipoDebitoOrigem    ($oRegraCompensacao->k155_arretipoorigem);
      $this->setCodigoTipoDebitoDestino   ($oRegraCompensacao->k155_arretipodestino);
      $this->setPercentualUso             ($oRegraCompensacao->k155_percmaxuso);
      $this->setTempoValidade             ($oRegraCompensacao->k155_tempovalidade);
      $this->setCompensacaoAutomatica     ($oRegraCompensacao->k155_automatica);
      $this->setPermiteTransferencia      ($oRegraCompensacao->k155_permitetransferencia);
      $this->setInstituicao               ($oRegraCompensacao->k155_instit);
      $this->setCodigoReceitaRecibo       ($oRegraCompensacao->k00_receitacredito);
      
    }
    
    
  }
  
  
  

  /**
   * Retorna o c�digo da regra de compensa��o
   * @return integer
   */
  public function getCodigoRegraCompensacao() {
    return $this->iCodigoRegraCompensacao;
  }

  /**
   * Define o c�digo da regra de compensa��o
   * @param $iCodigoRegraCompensacao
   */
  public function setCodigoRegraCompensacao($iCodigoRegraCompensacao) {
    $this->iCodigoRegraCompensacao = $iCodigoRegraCompensacao;
  }

  /**
   * Retorna o c�digo do tipo da regra de compensa��o
   * @return integer
   */
  public function getTipoRegraCompensacao() {
    return $this->iTipoRegraCompensacao;
  }

  /**
   * Define o c�digo do tipo de regra de compensa��o
   * @param $iTipoRegraCompensacao
   */
  public function setTipoRegraCompensacao($iTipoRegraCompensacao) {
    $this->iTipoRegraCompensacao = $iTipoRegraCompensacao;
  }

  /**
   * Retorna a descr��o da regra de compensa��o
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descri��o da regra de compensa��o
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o c�digo do tipo de d�bito de origem
   * @return integer
   */
  public function getCodigoTipoDebitoOrigem() {
    return $this->iCodigoTipoDebitoOrigem;
  }

  /**
   * Define o c�digo do tipo de d�bito de origem
   * @param $iCodigoTipoDebitoOrigem
   */
  public function setCodigoTipoDebitoOrigem($iCodigoTipoDebitoOrigem) {
    $this->iCodigoTipoDebitoOrigem = $iCodigoTipoDebitoOrigem;
  }

  /**
   * Retorna o c�digo do tipo de d�bito de destino
   * @return integer
   */
  public function getCodigoTipoDebitoDestino() {
    return $this->iCodigoTipoDebitoDestino;
  }

  /**
   * Define o c�digo do tipo de d�bito de destino
   * @param $iCodigoTipoDebitoDestino
   */
  public function setCodigoTipoDebitoDestino($iCodigoTipoDebitoDestino) {
    $this->iCodigoTipoDebitoDestino = $iCodigoTipoDebitoDestino;
  }

  /**
   * Retorna o percentual de uso
   * @return float
   */
  public function getPercentualUso() {
    return $this->nPercentualUso;
  }

  /**
   * Define o percentual de uso
   * @param $nPercentualUso
   */
  public function setPercentualUso($nPercentualUso) {
    $this->nPercentualUso = $nPercentualUso;
  }

  /**
   * Retorna o tempo de validade
   * @return integer
   */
  public function getTempoValidade() {
    return $this->iTempoValidade;
  }

  /**
   * Define o tempo de validade
   * @param $iTempoValidade
   */
  public function setTempoValidade($iTempoValidade) {
    $this->iTempoValidade = $iTempoValidade;
  }

  /**
   * Retorna se � compensa��o autom�tica ou n�o
   * @return boolean
   */
  public function getCompensacaoAutomatica() {
    return $this->lCompensacaoAutomatica;
  }

  /**
   * Define se � compensa��o autom�tica ou n�o
   * @param $lCompensacaoAutomatica
   */
  public function setCompensacaoAutomatica($lCompensacaoAutomatica) {
    $this->lCompensacaoAutomatica = $lCompensacaoAutomatica;
  }

  /**
   * Retorna se � permitido transfer�ncia ou n�o
   * @return boolean
   */
  public function getPermiteTransferencia() {
    return $this->lPermiteTransferencia;
  }

  /**
   * Define se permite ou n�o transfer�ncia
   * @param $lPermiteTransferencia
   */
  public function setPermiteTransferencia($lPermiteTransferencia) {
    $this->lPermiteTransferencia = $lPermiteTransferencia;
  }

  /**
   * Retorna o c�digo da institui��o
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Define a institui��o
   * @param $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna o c�digo do recibo
   * @return integer
   */
  public function getCodigoReceitaRecibo() {
    return $this->iCodigoReceitaRecibo;
  }

  /**
   * Define o c�digo do recibo
   * @param $iCodigoReceitaRecibo
   */
  public function setCodigoReceitaRecibo($iCodigoReceitaRecibo) {
    $this->iCodigoReceitaRecibo = $iCodigoReceitaRecibo;
  }
  
  /**
   * Define a data de lan�amento de um credito
   * @param DBDate $oDataLancamento
   */
  public function setDataLancamento(DBDate $oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;    
  }
  
  /**
   * Retorna a data de lan�amento de um cr�dito
   * @return DBDate
   */
  public function getDataLancamento() {
    return $this->oDataLancamento;
  }
  
  /**
   * Retorna a data de validade, somando os dias configurados da regra mais a data do sistema
   * @return DBDate
   */
  public function getDataValidade() {
    
    if($this->getTempoValidade() > 0) {
      
      $dData = db_getsession('DB_datausu');
      
      if ($this->getDataLancamento() != null) {

        $dData = $this->getDataLancamento()->getTimeStamp();
        
      }
      
      return new DBDate(date("Y-m-d", strtotime("+{$this->getTempoValidade()} days", $dData)));
      
    } 
      
    return false;
    
    
  }
  
  static function getRegrasCompensacaoPorTipo($iCodigoTipoRegraCompensacao) {
    
    if (empty($iCodigoTipoRegraCompensacao)) {
      throw new Exception('C�digo da regra de compensa��o n�o informado.');
    }
    
    $oDaoRegraCompensacao = db_utils::getDao('regracompensacao');
    
    $sSqlRegraCompensacao = $oDaoRegraCompensacao->sql_query_file(null, "*", null, "k155_tiporegracompensacao = {$iCodigoTipoRegraCompensacao}");
    
    $rsRegraCompensacao   = $oDaoRegraCompensacao->sql_record($sSqlRegraCompensacao);
    
    $aRegrasCompensacao   = array();
    
    foreach (db_utils::getCollectionByRecord($rsRegraCompensacao) as $oRegraCompensacao) {
      
      $aRegrasCompensacao[] = new RegraCompensacao($oRegraCompensacao->k155_sequencial);
      
    }
    
    return $aRegrasCompensacao;
    
  }
  
}