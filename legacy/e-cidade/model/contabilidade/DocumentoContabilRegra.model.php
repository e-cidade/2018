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
 * Regra para descobrir o tipo de documento para ser executado
 * @author Andrio Costa
 * @version $Revision: 1.9 $ 
 */
class DocumentoContabilRegra {
  
  /**
   * Código da Regra (conhistdocregra)
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Ano da Regra (conhistdoctipo)
   * @var integer
   */
  private $iAnoUsu;
  
  /**
   * Código do Documento (conhistdoc)
   * @var integer
   */
  private $iCodigoDocumento;
  
  /**
   * String sql contendo a regra 
   * @var string
   */
  private $sRegra;          
  
  /**
   * Descrição da Regra
   * @var string
   */
  private $sDescricao;
  
  /**
   * Contrutor da Classe
   * Se o parâmetro estivér setado, cria uma instância da Regra
   * @param integer $iCodigoRegra
   */
  public function __construct($iCodigoRegra = null) {
    
  	$this->iCodigo = $iCodigoRegra;
    if (isset($iCodigoRegra) && !empty($iCodigoRegra)) {
      
      $oDaoRegra = db_utils::getDao('conhistdocregra');
      $sSqlRegra = $oDaoRegra->sql_query_file($iCodigoRegra);
      $rsRegra   = $oDaoRegra->sql_record($sSqlRegra);
      
      if ($oDaoRegra->numrows == 0) {
        throw new Exception("Não foi localizada uma regra para o código: {$iCodigoRegra}");
      }
      $oRegra                 = db_utils::fieldsMemory($rsRegra, 0);
      $this->iCodigo          = $oRegra->c92_sequencial;
      $this->iAnoUsu          = $oRegra->c92_anousu; 
      $this->iCodigoDocumento = $oRegra->c92_conhistdoc; 
      $this->sDescricao       = $oRegra->c92_descricao; 
      $this->sRegra           = $oRegra->c92_regra;
    }
  }

  /**
   * Executa a regra com base no conjunto de variáveis recebidas no parâmetro 
   * @param array $aVariavel
   * @return boolean
   */
  public function validaRegra($aVariavel) {
    
    $sQuery    = strtr($this->sRegra, $aVariavel);
    $oDaoRegra = db_utils::getDao('conhistdocregra');
    $rsRegra   = $oDaoRegra->sql_record($sQuery);
    if ($oDaoRegra->numrows > 0) {
      return true;
    }
    return false;
    
  }
  
  /**
   * Salva uma regra 
   * @return boolean
   * @throws Exception
   */
  public function salvar() {
    
    $oDaoRegra                     = db_utils::getDao('conhistdocregra');
    $oDaoRegra->c92_anousu         = $this->iAnoUsu;
    $oDaoRegra->c92_conhistdoc     = $this->iCodigoDocumento;
    $oDaoRegra->c92_descricao      = $this->sDescricao;
    $oDaoRegra->c92_regra          = $this->sRegra;
    $oDaoRegra->c92_sequencial     = $this->iCodigo;

    if (isset($this->iCodigo) && !empty($this->iCodigo)) {
      $oDaoRegra->alterar($this->iCodigo);
    } else {
      $oDaoRegra->incluir($this->iCodigo);
    }
    $this->iCodigo = $oDaoRegra->c92_sequencial;
    if ($oDaoRegra->erro_status == 0) {
      throw new Exception("Não foi possível salvar a regra.\n\nErro Técnico: {$oDaoRegra->erro_msg}");
    }
    return true;
  }
  
  /**
   * Exclui a Regra
   * @return boolean
   * @throws Exception
   */
  public function excluir() {
    
    $oDaoRegra = db_utils::getDao('conhistdocregra');
    $oDaoRegra->excluir($this->iCodigo);
    if ($oDaoRegra->erro_status == 0) {
      throw new Exception("Não foi possível excluir a Regra.");
    }
    return true;
  }
  
  /**
   * Seta o código da regra
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    
    $this->iCodigo = $iCodigo;
  } 
  
  /**
   * Retorna o código da regra
   * @return integer 
   */
  public function getCodigo() {
  
    return $this->iCodigo;
  }
  
  /**
   * Seta o ano da regra
   * @param integer $iAnoUsu
   */
  public function setAno($iAnoUsu) {
    $this->iAnoUsu = $iAnoUsu;
  }
  
  /**
   * Retorna o ano da regra
   * @return integer 
   */
  public function getAno() {
    return $this->iAnoUsu;
  }
  
  /**
   * Seta o documento
   * @param integer $iCodigoDocumento
   */
  public function setCodigoDocumento($iCodigoDocumento) {
    $this->iCodigoDocumento = $iCodigoDocumento;
  }
  
  /**
   * Retorna o documento
   * @return integer
   */
  public function getCodigoDocumento() {
    return $this->iCodigoDocumento;
  }
  
  /**
   * Seta a descricão da regra
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a descricão da regra
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Recebe uma string SQL
   * @param string $sSql
   */
  public function setRegra($sSql) {
  	$this->sRegra = $sSql;
  }
  
  /**
   * Retorna uma string
   * @return string $sSql
   */
  public function getRegra() {
  	return $this->sRegra;
  }
}