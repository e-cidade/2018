<?php

/**
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

/**
 * Classe responsável pela pesquisa de endereços e adição de CGMs do Portal de Doações
 *
 * @author Fabio Egidio <fabio.egidio@dbseller.com.br>
 */
class LocalizaEndereco {

  /**
   * cep do endereço
   * @var string
   */
  protected $sCep;
  protected $sLogradouro;
  protected $sMunicipio;
  protected $sUf;

  protected $iCodUf;

  protected $oEndereco;

  /**
   * Metodo Construtor da Classe
   */
  public function __construct() {
    // db_app::import('endereco');
  }

  /**
   * @return string
   */
  public function getCep() {

    return $this->sCep;
  }

  /**
   * @param integer $iCodUf
   */
  public function setCodUf($iCodUf) {

    $this->iCodUf = $iCodUf;
  }

  /**
   * @param string $sCep
   */
  public function setCep($sCep) {

    $this->sCep = $sCep;
  }

  /**
   * @param string $sLogradouro
   */
  public function setLogradouro($sLogradouro) {

    $this->sLogradouro = $sLogradouro;
  }

  /**
   * @param string $sMunicipio
   */
  public function setMunicipio($sMunicipio) {

    $this->sMunicipio = $sMunicipio;
  }

  /**
   * @param string $sUf
   */
  public function setUf($sUf) {

    $this->sUf = $sUf;
  }

  /**
   * @return object
   */
  public function getEndereco() {

    return $this->oEndereco;
  }

  /**
   * @param object $oEndereco
   */
  public function setEndereco($oEndereco) {

    $this->oEndereco = $oEndereco;
  }

  public function buscaEnderecoByCep() {    
    $result = endereco::findCep($this->sCep);      
    return $result;
  }

  public function buscaEnderecoByLogradouro() {    
    $aRetorno = array();
  
    $aMunicipios = false;

    $oDaoMunicipio  = db_utils::getDao('cadendermunicipio');
    $sWhere         = " db72_descricao ilike '%".utf8_decode(trim($this->sMunicipio))."%' ";
    $sCampos        = "db72_sequencial, db72_descricao, db72_cadenderestado";
   
    $sQueryMunicipio  = $oDaoMunicipio->sql_query(null, '*', "db72_descricao", $sWhere);
    $rsQueryMunicipio = $oDaoMunicipio->sql_record($sQueryMunicipio);
    
    if ($rsQueryMunicipio !== false) {
      
      $aMunicipios  = db_utils::getCollectionByRecord($rsQueryMunicipio, false, false, true);
       
    }
    foreach ($aMunicipios as $oMunicipio) {
      $oDaoRua  = db_utils::getDao('cadenderrua');
      $sWhere         = " to_ascii(db74_descricao) ilike to_ascii('%".utf8_decode(str_replace(' ', '%', trim($this->sLogradouro)))."%') ";
      $sWhere        .= " and db74_cadendermunicipio = ".$oMunicipio->db72_sequencial;
      
      $sOrder         = null;
      $sCampos        = "distinct db76_cep, db86_cep, db74_cep, db71_sigla, db72_descricao, db73_descricao, db74_descricao";
      $sQueryRua      = $oDaoRua->sql_query_left_full(null, $sCampos, $sOrder, $sWhere);
      $rsQueryRua     = $oDaoRua->sql_record($sQueryRua);
      
      if ($rsQueryRua !== false) {
        $temp = db_utils::getCollectionByRecord($rsQueryRua, false, false, true);
        $aRetorno = array_merge($aRetorno, $temp);
      }
    }
   
    if(sizeof($aRetorno) == 0){
      return false;
    }

    return $aRetorno;

  }

  public function buscaDadosMunicipio() {     
    $municipio = $this->findMunicipioByName(utf8_decode($this->sMunicipio), $this->iCodUf);  
   
    $oDaoMunicipio = db_utils::getDao('cadendermunicipiosistema');
    
    $sWhere  = "db125_cadendermunicipio = '".$municipio[0]->db72_sequencial."'";
    $sWhere .= " AND  db125_db_sistemaexterno = 4";
    
    $sSqlMunicipio        = $oDaoMunicipio->sql_query(null, 'db125_codigosistema', null, $sWhere);
  
    $rsDescricaoMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);

    
    if ($oDaoMunicipio->numrows == 0) {
      throw new BusinessException("Cidade com o código do IBGE {$municipio[0]->db72_sequencial} não encontrada no sistema.");
    }
    
    return db_utils::fieldsMemory($rsDescricaoMunicipio, 0);

  }

  public function findMunicipioByName($sName, $iCodigoEstado, $sAlias=false) {
    
    $aRetorno = false;
    
    if (!empty($sName) && !empty($iCodigoEstado)) {
    
      $oDaoMunicipio  = db_utils::getDao('cadendermunicipio');
      $sWhere         = " db72_descricao ='".$sName."' ";
      $sWhere        .= " and db72_cadenderestado = ".$iCodigoEstado;
      $sCampos        = "db72_sequencial, db72_descricao";
      if($sAlias){
        $sCampos     = "db72_sequencial as cod, db72_descricao as label"; 
      }
      $sQueryMunicipio  = $oDaoMunicipio->sql_query(null, $sCampos, "db72_descricao", $sWhere);
      $rsQueryMunicipio = $oDaoMunicipio->sql_record($sQueryMunicipio);
      
      if ($rsQueryMunicipio !== false) {
        
        $aRetorno  = db_utils::getCollectionByRecord($rsQueryMunicipio, false, false, true);
         
      }
    }
    
    return $aRetorno;
    
  }
}