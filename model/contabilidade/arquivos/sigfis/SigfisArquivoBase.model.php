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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");

/**
 * 
 * Classe Basica para geração de arquivo SIGFIS
 * @author andrio costa
 *
 */
abstract class SigfisArquivoBase {
  
  protected $iAnoUso;
  protected $dtDataInicial;
  protected $dtDataFinal;
  protected $sNomeArquivo;
  protected $iCodigoLayout;
  protected $sCodigoTribunal;
  protected $aDados = array();
  
  protected $rsLogger;
  function __construct() {
    
   $this->iAnoUso = db_getsession("DB_anousu");
  }
  
  /**
   * 
   * Retorna um array de com os dados do Arquivo
   * @return array 
   */
  public function getDados() {
    
    return $this->aDados;
  }
  
  /**
   * 
   * Seta a data Inicial
   * @param String $sDataInicial
   */
  public function setDataInicial($sDataInicial) {
    
    $this->dtDataInicial = $sDataInicial;
  }
  
  /**
   * 
   * Seta a data Final
   * @param String $sDataFinal
   */
  public function setDataFinal($sDataFinal) {
    
    $this->dtDataFinal = $sDataFinal;
  }
  
  /**
  * Retorna o Código do Nome do Arquivo
  */
  public function getNomeArquivo() {

    return $this->sNomeArquivo;
  }

  /**
   * Retorna o Código do Layout
   */
  public function setCodigoLayout($iCodigoLayout) {
    
    $this->iCodigoLayout = $iCodigoLayout;
  }
  
  /**
   * Retorna o Código do Layout
   */
  public function getCodigoLayout() {
   
    return $this->iCodigoLayout;
  }
  
  public function setTXTLogger($fp) {
    
    $this->rsLogger  = $fp; 
  }
  
  /**
  * Seta o código do tribunal
  * @param String 
  */
  public function setCodigoTribunal($sCodigoProcesso) {
    
    $this->sCodigoTribunal = $sCodigoProcesso;
  } 
  
  /**
   * Retorna o Código do tribunal
   * @return string
   */
  public function getCodigoTribunal() {
    
    return $this->sCodigoTribunal; 
  }
  
  /**
   * Adiciona um registro de log no arquivo
   * @param string $sLog
   */
  public function addLog($sLog) {
    
    fputs($this->rsLogger, $sLog);
  }
  /**
   * 
   * Formata valor para o formato esperado no sigfis
   * @param numeric $nValor
   * @return string
   */
  protected function formataValor($nValor) {
  
    $nValor = number_format($nValor, 2, "", "");
    return $nValor;
  }
  
  /**
   * 
   * Formata uma data para o padrao do sigfis
   * @param date $dtData
   * @param String $sParam1
   * @param String $sParam2
   */
  protected function formataData($dtData, $sParam1 = '', $sParam2 = '-') {
  
    $sDataFormatada = implode($sParam1, array_reverse(explode($sParam2, $dtData)));
    return $sDataFormatada;
  }
}