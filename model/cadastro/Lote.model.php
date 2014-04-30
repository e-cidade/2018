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

/**
* Classe para manipuação de lotes
*
* @author   Alberto Ferri Neto alberto@dbseller.com.br
* @package  Cadastro
* @revision $Author: dbalberto $
* @version  $Revision: 1.7 $
*/
class Lote {
  
  /**
   * Código do lote
   * @var integer
   */
  private $iCodigoLote;
  
  /**
   * Código do setor do lote
   * @var integer
   */
  private $iCodigoSetor;

  /**
   * Descrição do setor do lote
   * @var string
   */
  private $sSetor;

  /**
   * Código da Quadra do lote
   * @var string
   */
  private $sQuadra;

  /**
   *
   * Código do lote
   * @var string
   */
  private $sLote;

  /**
   * Área do lote em metros quadrados
   * Enter description here ...
   * @var unknown_type
   */
  private $nAreaLote;

  /**
   * Código do bairro
   * @var integer
   */
  private $iCodigoBairro;

  /**
   * Nome do bairro
   * @var string
   */
  private $sBairro;

  /**
   * Área medida do lote
   * @var number
   */
  private $nAreaMedida;

  /**
   * Total construído do lote
   * @var number
   */
  private $nTotalConstruido;

  /**
   * Zona do lote
   * @var integer
   */
  private $iZona;

  /**
   * Quantidade de matrículas do lote
   * @var integer
   */
  private $iQuantidadeMatriculas;

  /**
   * Área preservada do lote
   * @var number
   */
  private $nAreaPreservada;

  /**
   * Código do logradouro
   * @var integer
   */
  private $iCodigoLogradouro;

  /**
   * Descrição do logradouro
   * @var string
   */
  private $sLogradouro;
  
  
  private $nValorTestadaLote;
  
  private $iCodigoTipoLogradouro;
  
  private $sSiglaTipoLogradouro;
  
  private $iCodigoLoteamento;

  private $sDescricaoLoteamento;
  
  private $iCep;

  public function __construct($iCodigoLote = null) {
    
    if (empty($iCodigoLote)) {
      return;
    }
    
    $oDaoLote = db_utils::getDao('lote');
     
    $rsLote   = $oDaoLote->sql_record($oDaoLote->sql_query_lote($iCodigoLote));
    
    if ($rsLote || $oDaoLote->numrows > 0) {
         
      $oLote = db_utils::fieldsMemory($rsLote, 0);
    
      $this->setCodigoSetor          ($oLote->j34_setor);
      $this->setSetor                ($oLote->j30_descr);
      $this->setQuadra               ($oLote->j34_quadra);
      $this->setLote                 ($oLote->j34_lote);
      $this->setAreaLote             ($oLote->j34_area);
      $this->setCodigoBairro         ($oLote->j34_bairro);
      $this->setBairro               ($oLote->j13_descr);
      $this->setAreaMedida           ($oLote->j34_areal);
      $this->setTotalConstruido      ($oLote->j34_totcon);
      $this->setZona                 ($oLote->j34_zona);
      $this->setQuantidadeMatriculas ($oLote->j34_quamat);
      $this->setAreaPreservada       ($oLote->j34_areapreservada);
      $this->setCodigoLote           ($oLote->j34_idbql);
      $this->setCodigoLogradouro     ($oLote->j14_codigo);
      $this->setLogradouro           ($oLote->j14_nome);
      $this->setCep                  ($oLote->j29_cep);    
      $this->setValorTestadaLote     ($oLote->j36_testad);
      $this->setCodigoLoteamento     ($oLote->j34_loteam);
      $this->setDescricaoLoteamento  ($oLote->j34_descr);
      $this->setCodigoTipoLogradouro ($oLote->j88_codigo);
      $this->setSiglaTipoLogradouro  ($oLote->j88_sigla);
    
    }
    
  }
  
  /**
   * Define o codigo do lote
   * @param integer $iCodigoLote
   */
  public function setCodigoLote($iCodigoLote) {
    $this->iCodigoLote = $iCodigoLote;
  }
  
  /**
   * Retorna o codigo do lote
   * @return integer
   */
  public function getCodigoLote() {
    return $this->iCodigoLote;
  }

  /**
   * Retorna o código do setor
   * @return integer
   */
  public function getCodigoSetor() {
    return $this->iCodigoSetor;
  }

  /**
   * Define o código do setor
   * @param integer $iCodigoSetor
   */
  public function setCodigoSetor($iCodigoSetor) {
    $this->iCodigoSetor = $iCodigoSetor;
  }

  /**
   * Retorna a descrição do setor
   * @return string
   */
  public function getSetor() {
    return $this->sSetor;
  }

  /**
   * Define a descrição do setor
   * @param string $sSetor
   */
  public function setSetor($sSetor) {
    $this->sSetor = $sSetor;
  }

  /**
   * Retorna a quadra do lote
   * @return string
   */
  public function getQuadra() {
    return $this->sQuadra;
  }

  /**
   * Define a quadra do lota
   * @param string $sQuadra
   */
  public function setQuadra($sQuadra) {
    $this->sQuadra = $sQuadra;
  }

  /**
   * Retorna o numero lote
   * @return string
   */
  public function getLote() {
    return $this->sLote;
  }

  /**
   * Define o numero do lote
   * @param string $sLote
   */
  public function setLote($sLote) {
    $this->sLote = $sLote;
  }

  /**
   * Retorna a área do lote
   * @return number
   */
  public function getAreaLote() {
    return $this->nAreaLote;
  }

  /**
   * Define a área do lote
   * @param number $nAreaLote
   */
  public function setAreaLote($nAreaLote) {
    $this->nAreaLote = $nAreaLote;
  }

  /**
   * Define o código do bairro
   * @return integer
   */
  public function getCodigoBairro() {
    return $this->iCodigoBairro;
  }

  /**
   * Define o codigo do bairro
   * @param integer $iCodigoBairro
   */
  public function setCodigoBairro($iCodigoBairro) {
    $this->iCodigoBairro = $iCodigoBairro;
  }

  /**
   * Retorna a descrição do bairro
   * @return string;
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Define a descrição do bairro
   * @param string $sBairro
   */
  public function setBairro($sBairro) {
    $this->sBairro = $sBairro;
  }

  /**
   * Retorna a área medida
   * @return string
   */
  public function getAreaMedida() {
    return $this->nAreaMedida;
  }

  /**
   * Define a área medida
   * @param number $nAreaMedida
   */
  public function setAreaMedida($nAreaMedida) {
    $this->nAreaMedida = $nAreaMedida;
  }

  /**
   * Retorna o total construído
   * @return number
   */
  public function getTotalConstruido() {
    return $this->nTotalConstruido;
  }

  /**
   * Define o total construído
   * @param number $nTotalConstruido
   */
  public function setTotalConstruido($nTotalConstruido) {
    $this->nTotalConstruido = $nTotalConstruido;
  }

  /**
   * Retorna o código da zona
   * @return integer
   */
  public function getZona() {
    return $this->iZona;
  }

  /**
   * Define o código da zona
   * @param integer $iZona
   */
  public function setZona($iZona) {
    $this->iZona = $iZona;
  }

  /**
   * Retorna a quantidade de matriculas do lote
   * @return integer
   */
  public function getQuantidadeMatriculas() {
    return $this->iQuantidadeMatriculas;
  }

  /**
   * Define a quantidade de matriculas do lote
   * @param integer $iQuantidadeMatriculas
   */
  public function setQuantidadeMatriculas($iQuantidadeMatriculas) {
    $this->iQuantidadeMatriculas = $iQuantidadeMatriculas;
  }

  /**
   * Retorna a área preservada
   * @return number
   */
  public function getAreaPreservada() {
    return $this->nAreaPreservada;
  }

  /**
   * Define a área preservada
   * @param number $nAreaPreservada
   */
  public function setAreaPreservada($nAreaPreservada) {
    $this->nAreaPreservada = $nAreaPreservada;
  }
  
  /**
   * Retorna o codigo do logradouro do lote
   * @return integer
   */
  public function getCodigoLogradouro() {
    return $this->iCodigoLogradouro;
  }
  
  /**
   * Define o código do logradouro
   * @param integer $iCodigoLogradouro
   */
  public function setCodigoLogradouro ($iCodigoLogradouro) {
    $this->iCodigoLogradouro = $iCodigoLogradouro;
  }
  
  /**
   * Retorna a descrição do logradouro
   * @return string
   */
  public function getLogradouro() {
    return $this->sLogradouro;
  }
  
  /**
   * Define a descrição do logradouro
   * @param string $sLogradouro
   */
  public function setLogradouro ($sLogradouro) {
    $this->sLogradouro = $sLogradouro;
  }
    
  /**
   * Define o cep
   * @param integer $iCep
   */
  public function setCep($iCep) {
    $this->iCep = $iCep;
  }
  
  /**
   * Retorna o cep
   * @return integer 
   */
  public function getCep() {
    return $this->iCep;
  }
  
  public function getValorTestadaLote() {
    return $this->nValorTestadaLote;
  }
  
  public function setValorTestadaLote($nValorTestadaLote) {
    $this->nValorTestadaLote = $nValorTestadaLote;
  }
  
  public function setCodigoTipoLogradouro($iCodigoTipoLogradouro) {
    $this->iCodigoTipoLogradouro = $iCodigoTipoLogradouro;
  }
  
  public function getCodigoTipoLogradouro() {
    return $this->iCodigoTipoLogradouro;
  }
  
  public function setSiglaTipoLogradouro($sSiglaTipoLogradouro) {
    $this->sSiglaTipoLogradouro = $sSiglaTipoLogradouro;
  }
  
  public function getSiglaTipoLogradouro() {
    return $this->sSiglaTipoLogradouro;
  }
  
  public function getCodigoLoteamento() {
    return $this->iCodigoLoteamento;
  }
  
  public function setCodigoLoteamento($iCodigoLoteamento) {
    $this->iCodigoLoteamento = $iCodigoLoteamento;
  }
  
  public function getDescricaoLoteamento() {
    return $this->sDescricaoLoteamento;
  }
  
  public function setDescricaoLoteamento($sDescricaoLoteamento) {
    $this->sDescricaoLoteamento = $sDescricaoLoteamento;  
  }
  
  /**
  * $iOpcao = 1; Todas
  * $iOpcao = 2; Ativas
  * $iOpcao = 3; Baixadas
  */
  public function getImoveis ($iOpcao = 1) {
    
    db_app::import('cadastro.Imovel');
    
    if (empty($this->iCodigoLote)) {
      throw new Exception('Codigo do lote não informado para busca de imóveis');
    }
    
    $sBaixa = '';
    if ($iOpcao != 1) {
      if ($iOpcao == 2) {
        $sBaixa = ' and j01_baixa is null ';
      } else {
        $sBaixa = ' and j01_baixa is not null ';
      }
    }
    
    $oDaoIptubase = db_utils::getDao('iptubase');
    
    $rsIptubase   = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_file(null, 
     																																		    "j01_matric",
     																																		    "j01_matric",
     																																		    "j01_idbql = {$this->iCodigoLote}
                                                                             {$sBaixa}"));
    
    if (!$rsIptubase || $oDaoIptubase->numrows == 0) {
      throw new Exception('Erro ao consultar matriculas do lote.');
    }
    
    $aImoveis    = array();
    
    $aMatriculas = db_utils::getCollectionByRecord($rsIptubase);
    
    foreach ($aMatriculas as $oMatricula) {
      
      $aImoveis[] = new Imovel($oMatricula->j01_matric); 
      
    }
    
    return $aImoveis;
  }

  public function getCaracteristicasLote() {
    
    if(empty($this->iCodigoLote)) {
      throw new Exception('Código do lote não informado');
    }
    
    $oDaoCarlote = db_utils::getDao('carlote');
    
    $sSqlCarlote = $oDaoCarlote->sql_query($this->iCodigoLote);
    
    $rsCarlote   = $oDaoCarlote->sql_record($sSqlCarlote);
    
    $aCarlote    = db_utils::getCollectionByRecord($rsCarlote);
    
    $aCaracteristicas = array();
    
    foreach ($aCarlote as $oCarlote) {
      
      $oCaracteristica = new stdClass();
      
      $oCaracteristica->iCodigoCaracteristica = $oCarlote->j31_codigo;
      $oCaracteristica->sCaracteristica       = $oCarlote->j31_descr ;
      $oCaracteristica->iNumeroPontos         = $oCarlote->j31_pontos;
      $oCaracteristica->iCodigoGrupo          = $oCarlote->j32_grupo ;
      $oCaracteristica->sDescricaoGrupo       = $oCarlote->j32_descr ;
      
      $aCaracteristicas[] = $oCaracteristica;
    }
    
    return $aCaracteristicas;
    
  }
  
  public function getCaracteristicasFace() {

    if(empty($this->iCodigoLote)) {
      throw new Exception('Código do lote não informado');
    }
  
    $oDaoCarface = db_utils::getDao('carface');
  
    $sSqlCarface = $oDaoCarface->sql_queryCaracteristicasFace($this->iCodigoLote);
  
    $rsCarface   = $oDaoCarface->sql_record($sSqlCarface);
  
    $aCarface    = db_utils::getCollectionByRecord($rsCarface);
    
    $aCaracteristicas = array();
    
    foreach ($aCarface as $oCarface) {
      
      $oCaracteristica = new stdClass();
      
      $oCaracteristica->iCodigoCaracteristica = $oCarface->j31_codigo;
      $oCaracteristica->sCaracteristica       = $oCarface->j31_descr ;
      $oCaracteristica->iCodigoGrupo          = $oCarface->j32_grupo ;
      $oCaracteristica->sDescricaoGrupo       = $oCarface->j32_descr ;
      $oCaracteristica->iNumeroPontos         = $oCarface->j31_pontos;
              
      $aCaracteristicas[] = $oCaracteristica;
      
    }
    return $aCaracteristicas;
  
  }
  
}