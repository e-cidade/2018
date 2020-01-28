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
* controle de Documentos de um Acordo
* @package Contratos
*/
class AcordoDocumento {
  
  protected $iCodigo;
  protected $iCodigoAcordo;
  protected $sDescricao;
  protected $sArquivo;
  protected $sNomeArquivo;
  

  /**
   * 
   * Construtor, se passado par�metro seta todas vari�veis
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoAcordoDocumento = db_utils::getDao("acordodocumento");
      $sSQL                = $oDaoAcordoDocumento->sql_query_file($iCodigo);
      $rsAcordoDocumento   = $oDaoAcordoDocumento->sql_record($sSQL);
      
      if ($oDaoAcordoDocumento->numrows > 0) {

        $oAcordoDocumento = db_utils::fieldsMemory($rsAcordoDocumento, 0);
        
        $this->setCodigo($iCodigo);
        $this->setArquivo($oAcordoDocumento->ac40_arquivo);
        $this->setNomeArquivo($oAcordoDocumento->ac40_nomearquivo);
        $this->setDescricao($oAcordoDocumento->ac40_descricao);
        $this->setCodigoAcordo($oAcordoDocumento->ac40_acordo);
        unset($oAcordoDocumento);
      }
    }
  }
  
  /**
   * Chama persistirDados() se estiver setado o c�digo do documento
   */
  public function salvar() {
    
    if (empty($this->iCodigo)) {
      
     //Salva dados Novos 
     $this->persistirDados(); 
    }     
  }
  
  /**
   * 
   * Pega os dados setados e persiste no BD
   * Salva o binario do Arquivo passado
   * @throws Exception
   */
  private function persistirDados() {

    global $conn;
    if (!file_exists($this->getArquivo())) {
      throw new Exception("Arquivo do Documento n�o Encontrado.");
    }
    
    db_inicio_transacao();
    try {
      
      /**
       * Abre um arquivo em formato binario somente leitura
       */
      $rDocumento      = fopen($this->getArquivo(), "rb");
      /**
			 * Pega todo o conte�do do arquivo e coloca no resource
       */
      $rDadosDocumento = fread($rDocumento, filesize($this->getArquivo()));
      $oOidBanco       = pg_lo_create();
      fclose($rDocumento);
      
      $oDaoAcordoDocumento = db_utils::getDao("acordodocumento");
      
      $oDaoAcordoDocumento->ac40_arquivo     = $oOidBanco;
      $oDaoAcordoDocumento->ac40_descricao   = $this->getDescricao();
      $oDaoAcordoDocumento->ac40_acordo      = $this->getCodigoAcordo();
      $oDaoAcordoDocumento->ac40_nomearquivo = $this->getNomeArquivo();
      $this->iCodigo = $oDaoAcordoDocumento->ac40_sequencial;
      $oDaoAcordoDocumento->incluir(null);
      
      if ($oDaoAcordoDocumento->erro_status == '0') {
        throw new Exception($oDaoAcordoDocumento->erro_msg);
      }
      
      $oObjetoBanco = pg_lo_open($conn, $oOidBanco, "w");
      pg_lo_write($oObjetoBanco, $rDadosDocumento);
      pg_lo_close($oObjetoBanco);
      db_fim_transacao();
      
    } catch (Exception $oErro) {
      
      db_fim_transacao(true);
    }
  }
  
  /**
   * 
   * Busca todos documentos de um Acordo
   * @param integer
   * @return array
   */
  public function getDocumeto($iAcordo) {
    
    $aDocumentos         = array();
    $oDaoAcordoDocumento = db_utils::getDao("acordodocumento");
    $sCampos             = "ac40_sequencial, ac40_acordo, ac40_descricao, ac40_arquivo";
    $sWhere              = " ac40_acordo = {$iAcordo}";
    
    $sSqlDocumentoAcordo = $oDaoAcordoDocumento->sql_query_file(null, $sCampos, "ac40_sequencial", $sWhere);
    $rsDocumentos        = $oDaoAcordoDocumento->sqlrecord($sSqlDocumentoAcordo);
    
    if ($oDaoAcordoDocumento->numrows > 0) {
    
      for ($i=0; $i < $oDaoAcordoDocumento->numrows; $i++) {
    
        $oAcordoDocumento = db_utils::fieldsMemory($oDaoAcordoDocumento, $i);
        $ostdAcordoDocumento = new stdClass();
        
        $oAcordoDocumento->ac40_sequencial = $oAcordoDocumento->ac40_sequencial; 
        $oAcordoDocumento->ac40_acordo     = $oAcordoDocumento->ac40_acordo; 
        $oAcordoDocumento->ac40_descricao  = $oAcordoDocumento->ac40_descricao;
        $oAcordoDocumento->ac40_arquivo    = $oAcordoDocumento->ac40_arquivo;
        
        $aDocumentos[] = $ostdAcordoDocumento;
      }
      return $aDocumentos;
    }
    return null;
  } 
  
  /**
   * 
   * Remove do Banco de Dados um documento de um determinado Acordo (Contrato)
   * @throws Exception
   */
  public function remover() {
    
    $oDaoAcordoDocumento = db_utils::getDao("acordodocumento");
    $oDaoAcordoDocumento->excluir($this->getCodigo());

    if ($oDaoAcordoDocumento->erro_status == "0") {
      throw new Exception($oDaoAcordoDocumento->erro_msg);
    }
  }

  /**
   * 
   * Retorna o c�digo do documento
   * @return integer
   */
  public function getCodigo() {
      return $this->iCodigo;
  }
  
  /**
  *
  * Seta o c�digo do documento
  */
  private function setCodigo($iCodigo) {
      $this->iCodigo = $iCodigo;
  }

  /**
  *
  * Retorna o c�digo do acordo
  * @return integer
  */
  public function getCodigoAcordo() {
      return $this->iCodigoAcordo;
  }

  /**
  *
  * Seta o c�digo do acordo
  */
  public function setCodigoAcordo($iCodigoAcordo) {
      $this->iCodigoAcordo = $iCodigoAcordo;
  }
  
  /**
  *
  * Retorna a descricao do documento
  * @return string
  */
  public function getDescricao() {
      return $this->sDescricao;
  }

  /**
  *
  * Seta a descricao do documento
  */
  public function setDescricao($sDescricao) {
      $this->sDescricao = $sDescricao;
  }

  /**
  *
  * Retorna o Oid do arquivo salvo
  * @return Oid
  */
  public function getArquivo() {
      return $this->sArquivo;
  }

  /**
  *
  * Seta uma String com caminho/nome do arquivo
  */
  public function setArquivo($sArquivo) {
      $this->sArquivo = $sArquivo;
  }
  
  /**
  *
  * Seta o Nome do arquivo com sua extens�o
  */
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }
  
  /**
  *
  * Retorna o Nome do arquivo com sua extens�o
  * @return String
  */
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }
}