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
* Classe para manipuação de imóveis
*
* @author   Alberto Ferri Neto alberto@dbseller.com.br
* @package  Cadastro
* @revision $Author: dbalberto $
* @version  $Revision: 1.2 $
*/
class ImovelEndereco {
  
  /**
   * Matricula do imóvel
   * @var integer
   */
  private $iMatricula;
  
  /**
   * Endereco do imóvel
   * @var string
   */
  private $sEndereco;
  
  /**
   * Número do imóvel
   * @var integer
   */
  private $iNumero;
  
  /**
   * Complemento do imóvel
   * @var string
   */
  private $sComplemento;
  
  /**
   * Bairro do imóvel
   * @var string
   */
  private $sBairro;
  
  /**
   * Munícipio do imóvel
   * @var string
   */
  private $sMunicipio;
  
  /**
   *
   * @var Uf do imóvel
   */
  private $sUf;
  
  /**
   * Cep do imóvel
   * @var integer
   */
  private $iCep;
  
  /**
   * Caixa postal do imóvel
   * @var integer
   */
  private $sCaixaPostal;
  
  public function __construct($iMatricula) {
    
    if (empty($iMatricula)) {
      throw new Exception('Matrícula não informada.');
    }
    
    $this->setMatricula($iMatricula);
    
    $rsImovelEndereco = pg_query("select fc_iptuender({$this->iMatricula}) as endereco_imovel");
    
    
    if (pg_num_rows($rsImovelEndereco) > 0) {
      
      $oDadosEndereco = db_utils::fieldsMemory($rsImovelEndereco, 0);
      
      $this->setEndereco    (trim(substr($oDadosEndereco->endereco_imovel, 0  , 40)));
      $this->setNumero      (trim(substr($oDadosEndereco->endereco_imovel, 41 , 10)));
      $this->setComplemento (trim(substr($oDadosEndereco->endereco_imovel, 52 , 20)));
      $this->setBairro      (trim(substr($oDadosEndereco->endereco_imovel, 73 , 40)));
      $this->setMunicipio   (trim(substr($oDadosEndereco->endereco_imovel, 114, 40)));
      $this->setUf          (trim(substr($oDadosEndereco->endereco_imovel, 155,  2)));
      $this->setCep         (trim(substr($oDadosEndereco->endereco_imovel, 158,  8)));
      $this->setCaixaPostal (trim(substr($oDadosEndereco->endereco_imovel, 167, 20)));      
      
    }
    
  }
  

  /**
   * Retorna a matricula do endereço
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define a matricula do endereço
   * @param $iMatricula 
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Retorna o endereco do imovel
   * @return string
   */
  public function getEndereco() {
    return $this->sEndereco;
  }

  /**
   * Define o endereço do imóvel
   * @param $sEndereco
   */
  public function setEndereco($sEndereco) {
    $this->sEndereco = $sEndereco;
  }

  /**
   * Retorna o número do imóvel
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define o número do imóvel
   * @param $iNumero integer
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o complemento do endereço
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * Define o complemento do endereço
   * @param $sComplemento
   */
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * Retorna o bairro do imóvel
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Define o bairro do imóvel
   * @param $sBairro
   */
  public function setBairro($sBairro) {
    $this->sBairro = $sBairro;
  }

  /**
   * Retorna o município
   * @return string
   */
  public function getMunicipio() {
    return $this->sMunicipio;
  }

  /** 
   * Define o município do endereço
   * @param $sMunicipio
   */
  public function setMunicipio($sMunicipio) {
    $this->sMunicipio = $sMunicipio;
  }

  /**
   * Retorna a uf do imóvel
   * @return string
   */
  public function getUf() {
    return $this->sUf;
  }

  /**
   * Define a uf do imóvel
   * @param $sUf
   */
  public function setUf($sUf) {
    $this->sUf = $sUf;
  }

  /**
   * retorna o cep do imóvel
   * @return integer
   */
  public function getCep() {
    return $this->iCep;
  }

  /**
   * Define o cep do imóvel
   * @param $iCep
   */
  public function setCep($iCep) {
    $this->iCep = $iCep;
  }

  /**
   * retorna a caixa postal do imóvel
   * @return string
   */
  public function getCaixaPostal() {
    return $this->sCaixaPostal;
  }

  /**
   * Define a caixa postal do imóvel
   * @param $sCaixaPostal
   */
  public function setCaixaPostal($sCaixaPostal) {
    $this->sCaixaPostal = $sCaixaPostal;
  }
}

?>