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
 * Classe que representa um tipo de reconhecimento contábil
 * mapeia a  tabela "reconhecimentocontabiltipo"
 * @author Bruno Silva bruno.silva@dbseller.com.br
 */
class TipoReconhecimentoContabil {
  
  /**
   * Código sequencial do tipo de reconhecimento contábil
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Descrição do tipo de reconhecimento contábil
   * @var integer
   */
  private $sDescricao; 
  
  /**
   * EventoContabil realizado no tipo de reconhecimento contábil instânciado
   * @var EventoContabil
   */
  private $oEventoContabilLancamento;
  
  /**
   * EventoContabil de estorno realizado no tipo de reconhecimento contábil instânciado
   * @var EventoContabil
   */
  private $oEventoContabilEstorno;
  
  /**
   * Método contrutor da classe
   * seta todas as propriedades para null
   */
  public function __construct() {
    
    $this->iCodigo                   = null;
    $this->sDescricao                = null;
    $this->oEventoContabilLancamento = null;
    $this->oEventoContabilEstorno    = null;
  }
  
  /**
   * Retorna o código do tipo de reconhecimento contábil
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * seta o código do tipo de reconhecimento contábil
   * @param integer
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna a descrição do tipo de reconhecimento contábil
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta a descrição do tipo de reconhecimento contábil
   * @param string
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna o evento contábil do reconhecimento contábil
   * @return EventoContabil
   */
  public function getEventoContabilLancamento() {
    return $this->oEventoContabilLancamento;
  }
  
  /**
   * Seta o evento contábil do reconhecimento contábil
   * @param EventoContabil
   */
  public function setEventoContabilLancamento($oEventoContabilLancamento) {
    $this->oEventoContabilLancamento = $oEventoContabilLancamento;
  }
  
  /**
   * Retorna o evento contábil de estorno do reconhecimento contábil
   * @return EventoContabil
   */
  public function getEventoContabilEstorno() {
    return $this->oEventoContabilEstorno;
  }
  
  /**
   * Seta o evento contábil de estorno do reconhecimento contábil
   * @param EventoContabil
   */
  public function setEventoContabilEstorno($oEventoContabilEstorno) {
    $this->oEventoContabilEstorno = $oEventoContabilEstorno;
  }
  
  /**
   * Função responsável por salvar e alterar os dados de um tipo de reconhecimento contábil
   */
  public function salvar() {

    $oDaoReconhecimentocontabiltipo                         = db_utils::getDao("reconhecimentocontabiltipo");
    $oDaoReconhecimentocontabiltipo->c111_sequencial        = $this->iCodigo;
    $oDaoReconhecimentocontabiltipo->c111_descricao         = $this->sDescricao;
    $oDaoReconhecimentocontabiltipo->c111_conhistdoc        = $this->oEventoContabilLancamento->getCodigoDocumento();
    $oDaoReconhecimentocontabiltipo->c111_conhistdocestorno = $this->oEventoContabilEstorno->getCodigoDocumento();
    
    if ( empty($this->iCodigo) ) {
      
      $oDaoReconhecimentocontabiltipo->incluir(null);
    } else {
      $oDaoReconhecimentocontabiltipo->alterar($this->iCodigo);
    }
    
    if ( $oDaoReconhecimentocontabiltipo->erro_status == 0 ) {
      throw new BusinessException("Erro ao salvar o Tipo de Reconhecimento contábil.". $oDaoReconhecimentocontabiltipo->erro_msg);
    }
    
    $this->iCodigo = $oDaoReconhecimentocontabiltipo->c111_sequencial;
  }
  
  /**
   * Função responsável por excluir os dados de um tipo de reconhecimento contábil
   */
  public function excluir() {
    
    $oDaoReconhecimentocontabiltipo = db_utils::getDao("reconhecimentocontabiltipo");
    $oDaoReconhecimentocontabiltipo->excluir($this->iCodigo);
    
    if ( $oDaoReconhecimentocontabiltipo->erro_status == 0 ) {
      throw new BusinessException("Erro ao excluir o Tipo de Reconhecimento contábil. Tipo não existente.");
    }
  }
  
  /**
   * Função responsável por buscar uma ocorrência no banco de dados, de um tipo de reconhecimento contábil
   * @param integer $iCodigoTipoReconhecimento
   * @param DBDate  $oDBDate
   * @return TipoReconhecimentoContabil
   */
  public static function getInstance($iCodigoTipoReconhecimento, DBDate $oDBDate) {
    
    $oDaoReconhecimentocontabiltipo = db_utils::getDao("reconhecimentocontabiltipo");
    $sSql                           = $oDaoReconhecimentocontabiltipo->sql_queryReconhecimento($iCodigoTipoReconhecimento);
    $rsResultado                    = db_query($sSql);
    
    if (!$rsResultado) {
      throw new DBException("Erro ao executar a busca pelo Tipo de Reconhecimento");
    }
    
    $iNumeroResultadosQuery = pg_num_rows($rsResultado);
    if ($iNumeroResultadosQuery != 1 ) {
      throw new BusinessException("Tipo de reconhecimento contábil não encontrado");
    }
    
    $oTipoReconhecimento            = db_utils::fieldsMemory($rsResultado, 0);
    $iEventoContabilLancamento      = $oTipoReconhecimento->c111_conhistdoc;       
    $iEventoContabilEstorno         = $oTipoReconhecimento->c111_conhistdocestorno;
    $iCodigo                        = $oTipoReconhecimento->c111_sequencial;
    $sDescricao                     = $oTipoReconhecimento->c111_descricao;
    $oEventoContabilLancamento      = new EventoContabil($iEventoContabilLancamento, $oDBDate->getAno());
    $oEventoContabilEstorno         = new EventoContabil($iEventoContabilEstorno, $oDBDate->getAno());
    
    $oTipoReconhecimento = new TipoReconhecimentoContabil();
    $oTipoReconhecimento->setCodigo($iCodigo);
    $oTipoReconhecimento->setDescricao($sDescricao);
    $oTipoReconhecimento->setEventoContabilEstorno($oEventoContabilEstorno);
    $oTipoReconhecimento->setEventoContabilLancamento($oEventoContabilLancamento);
    
    return $oTipoReconhecimento;
  }
  
  /**
   * Função responsável por buscar todas as  ocorrências, no banco de dados, dos tipo de reconhecimento contábil
   * @param integer $iCodigoTipoReconhecimento
   * @param DBDate  $oDBDate
   * @return array<TipoReconhecimentoContabil>
   */
  public static function buscaTodosTiposDeReconhecimento( DBDate $oDBDate ) {
    
    $oDaoReconhecimentocontabiltipo = db_utils::getDao("reconhecimentocontabiltipo");
    $sSql                           = $oDaoReconhecimentocontabiltipo->sql_queryReconhecimento(null, "c111_sequencial");
    $rsResultado                    = db_query($sSql);
    
    if (!$rsResultado) {
      throw new DBException("Erro ao executar a busca pelos Tipos de Reconhecimentos");
    }
    
    $iNumeroResultadosQuery = pg_num_rows($rsResultado);
    $aTiposReconhecimentos  = array();
    
    for ($iTipo = 0 ; $iTipo < $iNumeroResultadosQuery; $iTipo++ ) {
      
      $iCodigoTipoReconhecimento = db_utils::fieldsMemory($rsResultado, $iTipo)->c111_sequencial;
      $oTipoReconhecimento       = self::getInstance($iCodigoTipoReconhecimento, $oDBDate);
      $aTiposReconhecimentos[]   = $oTipoReconhecimento;
    }
    return $aTiposReconhecimentos;
  }

  /**
   * Função responsável por buscar os dados dos tipos de reconhecimento contabil
   * @return array<TipoReconhecimentoContabil>
   */
  public static function buscaDadosTiposDeReconhecimento($sCampos = '*', $sWhere = null) {
    
    $oDaoReconhecimentocontabiltipo = db_utils::getDao("reconhecimentocontabiltipo");
    $sOrdenacao                     = 'c111_descricao';
    $sSql                           = $oDaoReconhecimentocontabiltipo->sql_queryReconhecimento(null, $sCampos, $sOrdenacao, $sWhere);
    $rsResultado                    = db_query($sSql);
    
    if (!$rsResultado) {
      throw new DBException("Erro ao executar a busca pelos Tipos de Reconhecimentos");
    }
    
    $iNumeroResultadosQuery = pg_num_rows($rsResultado);
    $aTiposReconhecimentos  = array();
    
    for ($iTipo = 0 ; $iTipo < $iNumeroResultadosQuery; $iTipo++ ) {
      
      $aTiposReconhecimentos[] = db_utils::fieldsMemory($rsResultado, $iTipo);
    }
    return $aTiposReconhecimentos;
  }
}
?>