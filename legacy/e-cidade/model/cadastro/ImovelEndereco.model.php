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
* Classe para manipua��o de im�veis
*
* @author   Alberto Ferri Neto alberto@dbseller.com.br
* @package  Cadastro
* @revision $Author: dbalberto $
* @version  $Revision: 1.2 $
*/
class ImovelEndereco {
  
  /**
   * Matricula do im�vel
   * @var integer
   */
  private $iMatricula;
  
  /**
   * Endereco do im�vel
   * @var string
   */
  private $sEndereco;
  
  /**
   * N�mero do im�vel
   * @var integer
   */
  private $iNumero;
  
  /**
   * Complemento do im�vel
   * @var string
   */
  private $sComplemento;
  
  /**
   * Bairro do im�vel
   * @var string
   */
  private $sBairro;
  
  /**
   * Mun�cipio do im�vel
   * @var string
   */
  private $sMunicipio;
  
  /**
   *
   * @var Uf do im�vel
   */
  private $sUf;
  
  /**
   * Cep do im�vel
   * @var integer
   */
  private $iCep;
  
  /**
   * Caixa postal do im�vel
   * @var integer
   */
  private $sCaixaPostal;
  
  public function __construct($iMatricula) {
    
    if (empty($iMatricula)) {
      throw new Exception('Matr�cula n�o informada.');
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
   * Retorna a matricula do endere�o
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define a matricula do endere�o
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
   * Define o endere�o do im�vel
   * @param $sEndereco
   */
  public function setEndereco($sEndereco) {
    $this->sEndereco = $sEndereco;
  }

  /**
   * Retorna o n�mero do im�vel
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define o n�mero do im�vel
   * @param $iNumero integer
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o complemento do endere�o
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * Define o complemento do endere�o
   * @param $sComplemento
   */
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * Retorna o bairro do im�vel
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Define o bairro do im�vel
   * @param $sBairro
   */
  public function setBairro($sBairro) {
    $this->sBairro = $sBairro;
  }

  /**
   * Retorna o munic�pio
   * @return string
   */
  public function getMunicipio() {
    return $this->sMunicipio;
  }

  /** 
   * Define o munic�pio do endere�o
   * @param $sMunicipio
   */
  public function setMunicipio($sMunicipio) {
    $this->sMunicipio = $sMunicipio;
  }

  /**
   * Retorna a uf do im�vel
   * @return string
   */
  public function getUf() {
    return $this->sUf;
  }

  /**
   * Define a uf do im�vel
   * @param $sUf
   */
  public function setUf($sUf) {
    $this->sUf = $sUf;
  }

  /**
   * retorna o cep do im�vel
   * @return integer
   */
  public function getCep() {
    return $this->iCep;
  }

  /**
   * Define o cep do im�vel
   * @param $iCep
   */
  public function setCep($iCep) {
    $this->iCep = $iCep;
  }

  /**
   * retorna a caixa postal do im�vel
   * @return string
   */
  public function getCaixaPostal() {
    return $this->sCaixaPostal;
  }

  /**
   * Define a caixa postal do im�vel
   * @param $sCaixaPostal
   */
  public function setCaixaPostal($sCaixaPostal) {
    $this->sCaixaPostal = $sCaixaPostal;
  }
}

?>