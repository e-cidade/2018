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



require_once ('interfaces/PadArquivoBase.interface.php');
/**
 * classe abstrata para a geraчуo dos arquivos do pad para SIGAP - RO
 *
 */
abstract class PadArquivoSigap implements iPadArquivoBase {
  
  /**
   * nome do arquivo
   *
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * formato de saida do arquivo (txt/XML/CSV)
   *
   * @var string
   */
  protected $sOutPut = "xml";
  
  /**
   * Coleчуo de objetos com os dados do arquivo
   *
   * @var array
   */
  protected $aDados;
  
  /**
   * data inicial do arquivo
   *
   * @var string
   */
  protected $sDataInicial;
  
  /**
   * data final do arquivo
   *
   * @var string
   */
  protected $sDataFinal;
  
  /**
   * Codigo do TCE
   *
   * @var unknown_type
   */
  protected $iCodigoTCE;
  
  protected  $rsLogger;
  /**
   * 
   * @see iPadArquivoBase::__construct()
   */
  function __construct() {
   
  }
  
  /**
   * 
   * @see iPadArquivoBase::gerarDados()
   */
  public function gerarDados() {

  }
  
  /**
   * 
   * @see iPadArquivoBase::getDados()
   */
  public function getDados() {
     return $this->aDados; 
  }
  
  /**
   * 
   * @see iPadArquivoBase::setDataFinal()
   */
  public function setDataFinal($sDataFinal) {
    
    if (strpos($sDataFinal, "/", 0)) {
      $sDataFinal = implode("-", array_reverse(explode("/", $sDataFinal)));
    }
    $this->sDataFinal = $sDataFinal;
  }
  
  /**
   * 
   * @see iPadArquivoBase::setDataInicial()
   */
  public function setDataInicial($sDataInicial) {
   
  if (strpos($sDataInicial, "/",0)) {
      $sDataInicial = implode("-", array_reverse(explode("/", $sDataInicial)));
    }
    $this->sDataInicial = $sDataInicial;
  }
  
  /**
   * Retorna o nome do arquivo
   *
   * @return string
   */
  function getNomeArquivo() {

    return $this->sNomeArquivo;
  }
  
  /**
   *retorna o Tipo de saida o arquivo 
   */
  function getOutPut() {
    return  $this->sOutPut;
  }
  
  public function setCodigoTCE($iCodigoTCE) {
    
    $this->iCodigoTCE = $iCodigoTCE;
  }
  
  public function setTXTLogger($fp) {
    
    $this->rsLogger  = $fp; 
  }
  
  public function addLog($sLog) {
    fputs($this->rsLogger, $sLog);
  }
}

?>