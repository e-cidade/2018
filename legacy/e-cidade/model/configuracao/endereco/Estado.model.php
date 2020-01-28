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
 * Classe para controle das informacoes e acoes referentes a cadenderestado
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage endereco
 */
class Estado {
  
  /**
   * Sequencial de cadenderestado
   * @var integer
   */
  private $iSequencial;
  
  /**
   * Instancia de PaisEndereco
   * @var PaisEndereco
   */
  private $oPaisEndereco;
  
  /**
   * Descricao do estado
   * @var string
   */
  private $sDescricao;

  /**
   * Sigla do estado
   * @var string
   */
  private $sSigla;
  
  /**
   * Municipios vinculados ao estado
   * @var array
   */
  private $aMunicipios = array();
  
  /**
   * Construtor da classe. Recebe como parametro o sequencial da tabela cadenderestado
   * @param integer $iSequencial
   */
  public function __construct($iSequencial = null) {
    
    if (!empty($iSequencial)) {
      
      $oDaoCadEnderEstado = new cl_cadenderestado();
      $sSqlCadEnderEstado = $oDaoCadEnderEstado->sql_query_file($iSequencial);
      $rsCadEnderEstado   = $oDaoCadEnderEstado->sql_record($sSqlCadEnderEstado);
      
      if ($oDaoCadEnderEstado->numrows == 0) {
        throw new ParameterException("Estado não encontrado pelo sequencial informado.");
      }
      
      $oDadosCadEnderEstado = db_utils::fieldsMemory($rsCadEnderEstado, 0);
      $this->iSequencial    = $oDadosCadEnderEstado->db71_sequencial;
      $this->oPaisEndereco  = new PaisEndereco($oDadosCadEnderEstado->db71_cadenderpais);
      $this->sDescricao     = $oDadosCadEnderEstado->db71_descricao;
      $this->sSigla         = $oDadosCadEnderEstado->db71_sigla;
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
   * Retorna uma instancia de PaisEndereco
   * @return PaisEndereco
   */
  public function getPaisEndereco() {
    return $this->oPaisEndereco;
  }

  /**
   * Seta uma instancia de PaisEndereco
   * @param PaisEndereco $oPaisEndereco
   */
  public function setPaisEndereco(PaisEndereco $oPaisEndereco) {
    $this->oPaisEndereco = $oPaisEndereco;
  }

  /**
   * Retorna a descricao do estado
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  
  }

  /**
   * Seta a descricao do estado
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a sigla do estado
   * @return string 
   */
  public function getSigla() {
    return $this->sSigla;
  }

  /**
   * Seta a sigla do estado
   * @param string $sSigla
   */
  public function setSigla($sSigla) {
    $this->sSigla = $sSigla;
  }
  
  /**
   * Retorna um array com a instancia de Municipio, dos municípios vinculados ao estado
   * @return array Municipio
   */
  public function getMunicipiosVinculados() {
    
    $oDaoCadEnderMunicipio   = new cl_cadendermunicipio();
    $sWhereCadEnderMunicipio = "db72_cadenderestado = {$this->getSequencial()}";
    $sSqlCadEnderMunicipio   = $oDaoCadEnderMunicipio->sql_query(
                                                                  null, 
                                                                  "db72_sequencial", 
                                                                  "db72_sequencial", 
                                                                  $sWhereCadEnderMunicipio
                                                                );
    $rsCadEnderMunicipio     = $oDaoCadEnderMunicipio->sql_record($sSqlCadEnderMunicipio);
    $iTotalCadEnderMunicipio = $oDaoCadEnderMunicipio->numrows;
    
    if ($iTotalCadEnderMunicipio > 0) {
    
      for ($iContador = 0; $iContador < $iTotalCadEnderMunicipio; $iContador++) {
    
        $iCadEnderMunicipio  = db_utils::fieldsMemory($rsCadEnderMunicipio, $iContador)->db72_sequencial;
        $oMunicipio          = new Municipio($iCadEnderMunicipio);
        $this->aMunicipios[] = $oMunicipio;
      }
    }
    
    return $this->aMunicipios;
  }
}