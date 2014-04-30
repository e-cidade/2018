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
 * Classe para controle das informacoes e acoes referentes a cadenderpais
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage endereco
 */
class PaisEndereco {
  
  /**
   * Sequencial de cadenderpais
   * @var integer
   */
  private $iSequencial;
  
  /**
   * Descricao do pais
   * @var string
   */
  private $sDescricao;
  
  /**
   * Sigla referente ao pais
   * @var string
   */
  private $sSigla;
  
  /**
   * Array com os estados vinculados ao Pais
   * @var array
   */
  private $aEstados = array(); 
  
  /**
   * Construtor da classe. Recebe como parametro, o sequencial da tabela cadenderpais.
   * @param integer $iSequencial
   */
  public function __construct($iSequencial = null) {
    
    if (!empty($iSequencial)) {
      
      $oDaoCadEnderPais = new cl_cadenderpais();
      $sSqlCadEnderPais = $oDaoCadEnderPais->sql_query_file($iSequencial);
      $rsCadEnderPais   = $oDaoCadEnderPais->sql_record($sSqlCadEnderPais);
      
      if ($oDaoCadEnderPais->numrows == 0) {
        throw new ParameterException("País não encontrado pelo sequencial informado.");
      }
      
      $oDadosCadEnderPais = db_utils::fieldsMemory($rsCadEnderPais, 0);
      $this->iSequencial  = $oDadosCadEnderPais->db70_sequencial;
      $this->sDescricao   = $oDadosCadEnderPais->db70_descricao;
      $this->sSigla       = $oDadosCadEnderPais->db70_sigla;
    }
  }

  /**
   * Retorna o sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Seta o sequencial
   * @param integer $iSequencial
   */
  public function setSequencial($iSequencial) {
      $this->iSequencial = $iSequencial;
  }

  /**
   * Retorna a descricao do Pais
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao do Pais
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a sigla referente ao Pais
   * @return string
   */
  public function getSigla() {
    return $this->sSigla;
  }

  /**
   * Seta a sigla do Pais
   * @param string $sSigla
   */
  public function setSigla($sSigla) {
    $this->sSigla = $sSigla;
  }
  
  /**
   * Retorna um array com a instancia de Estado, dos estados vinculados ao Pais
   * @return array Estado
   */
  public function getEstadosVinculados() {
    
    $oDaoCadEnderEstado   = new cl_cadenderestado();
    $sWhereCadEnderEstado = "db71_cadenderpais = {$this->getSequencial()}";
    $sSqlCadEnderEstado   = $oDaoCadEnderEstado->sql_query(null, "db71_sequencial", "db71_sequencial", $sWhereCadEnderEstado);
    $rsCadEnderEstado     = $oDaoCadEnderEstado->sql_record($sSqlCadEnderEstado);
    $iTotalCadEnderEstado = $oDaoCadEnderEstado->numrows;
    
    if ($iTotalCadEnderEstado > 0) {
      
      for ($iContador = 0; $iContador < $iTotalCadEnderEstado; $iContador++) {
        
        $iCadEnderEstado  = db_utils::fieldsMemory($rsCadEnderEstado, $iContador)->db71_sequencial;
        $oEstado          = new Estado($iCadEnderEstado);
        $this->aEstados[] = $oEstado;
      }
    }
    
    return $this->aEstados;
  }
}
?>