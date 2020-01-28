<?
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
 * Classe Taxa
 *
 * @require  {db_utils}
 * @author   
 * @package  
 * @revision $
 * @version  $
 *
 */
class Taxa {

  /**
   * Cуdigo da taxa
   * @var integer
   */
  private $iCodigoTaxa;
  
  /**
   * Grupo das taxas
   * @var integer
   */
  private $iGrupoTaxas;
  
  /**
   * Receita da taxa
   * @var integer
   */
  private $iReceita;
  
  /**
   * Descriзгo da taxa
   * @var string
   */
  private $sDescricao;
  
  /**
   * Percentual da taxa
   * @var float
   */
  private $nPercentual;
  
  /**
   * Valor da taxa
   * @var float
   */
  private $nValor;
  
  /**
   * Valor minimo da taxa
   * @var float
   */
  private $nValorMinimo;
  
  /**
   * Valor maximo da taxa
   * @var float
   */
  private $nValorMaximo;

  /**
   * Construtor do model taxa
   * 
   */
  public function __construct($iCodigoTaxa = null) {
  
    if (!empty($iCodigoTaxa)) {
      
      $oDaoTaxa = db_utils::getDao('taxa');
      
      $rsTaxa   = $oDaoTaxa->sql_record($oDaoTaxa->sql_query_file($iCodigoTaxa));
    
      if (!$rsTaxa || $oDaoTaxa->numrows == 0) {
        throw new Exception("[1]Erro ao consultar o registro. ERRO: {$oDaoTaxa->erro_msg}");
      }   
      
      $this->setTaxas($oDaoTaxa->ar36_sequencial);
      $this->setGrupoTaxas ($oDaoTaxa->ar36_grupotaxa);
      $this->setReceita    ($oDaoTaxa->ar36_receita);
      $this->setDescricao  ($oDaoTaxa->ar36_descricao);
      $this->setPercentual ($oDaoTaxa->ar36_perc);
      $this->setValor       ($oDaoTaxa->ar36_valor);
      $this->setValorMinimo($oDaoTaxa->ar36_valormin);
      $this->setValorMaximo($oDaoTaxa->ar36_valormax);
    
    }
    
  }
  
  /**
  * Retorna o cуdigo da taxas ...
  * @return integer
  */
  public function getCodigoTaxa() {
    return $this->iCodigoTaxa;
  }
  
  /**
   * Define o cуdigo do grupo da taxa
   * @param integer $iCodigoTaxas
   */
  public function setTaxas($iCodigoTaxa) {
    $this->iCodigoTaxa = $iCodigoTaxa;
  }
  
  /**
   * Retorna o cуdigo do grupo das taxas ...
   * @return integer
   */
  public function getGrupoTaxas() {
    return $this->iGrupoTaxas;
  }

  /**
   * Define o cуdigo do grupo da taxa
   * @param integer $iGrupoTaxas
   */
  public function setGrupoTaxas($iGrupoTaxas) {
    $this->iGrupoTaxas = $iGrupoTaxas;
  }

  /**
   * Retorna o cуdigo da receita
   * @return integer
   */
  public function getReceita() {
    return $this->iReceita;
  }

  /**
   * Define o cуdigo da receita
   * @param integer $iReceita
   */
  public function setReceita($iReceita) {
    $this->iReceita = $iReceita;
  }

  /**
   * Retorna a descriзгo da taxa
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descriзгo da taxa 
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o percentual
   * @return float
   */
  public function getPercentual() {
    return $this->nPercentual;
  }

  /**
   * Define o percentual da taxa
   * @param float $nPercentual
   */
  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }

  /**
   * Retorna o valor da taxa
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o valor da taxa
   * @param float $nValor
   */
  public function setValor($nValor)  {
    $this->nValor = $nValor;
  }

  /**
   * Retorna o valor mнnimo da taxa
   * @return float
   */
  public function getValorMinimo() {
    return $this->nValorMinimo;
  }

  /**
   * Define o valor mнnimo da taxa
   * @param float $nValorMinimo
   */
  public function setValorMinimo($nValorMinimo)  {
    $this->nValorMinimo = $nValorMinimo;
  }

  /**
   * Retorna o valor mбximo da taxa
   * @return float
   */
  public function getValorMaximo() {
    return $this->nValorMaximo;
  }

  /**
   * Define o valor mбximo da taxa
   * @param float $nValorMaximo
   */
  public function setValorMaximo($nValorMaximo)  {
    $this->nValorMaximo = $nValorMaximo;
  }

}


?>