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
 * Classe boleto
 * @author Aberto Ferri Neto  <alberto@dbseller.com.br>
 *          Jeferson Belmiro   <jeferson.belmiro@dbseller.com.br>
 * @version  $
 * @revision $
 */
class Boleto {
  
  /**
   * C�digo do boleto
   * @var integer
   */
  protected $iCodigoBoleto;
  
  /**
   * C�digo de barras do boleto
   * @var integer
   */
  protected $sCodigoBarras;
  
  /**
   * Regra de emiss�o do boleto
   * @var integer
   */
  protected $iRegraEmissao;
  
  /**
   * Tipo de boleto 
   * 1 - Carne, 2 - Recibo
   * @var integer
   */
  protected $iTipoBoleto;
  
  /**
   * C�digo do conv�nio
   * @var integer
   */
  protected $iConvenio;
  
  /**
   * Hora da gera��o do boleto
   * @var string
   */
  protected $sHora;
  
  /**
   * Data da gera��o do boleto
   * @var date
   */
  protected $dData;
  
  /**
   * C�digo do usu�rio da gera��o do boleto
   * @var integer
   */
  protected $iUsuario;
  
  /**
   * C�digo nosso n�mero
   * @var integer
   */
  protected $iNossoNumero;
  
  /**
   * Construtor da classe
   */
  public function __construct($sCodigoBarras = null) {    
    
    if(!empty($sCodigoBarras)) {
      
      $oDaoBoleto       = db_utils::getDao('boleto');
                        
      $sSqlBoleto       = $oDaoBoleto->sql_query_file(null, "*", null, "k139_codigobarras = {$sCodigoBarras}");
			$rsBoleto         = $oDaoBoleto->sql_record($sSqlBoleto);
      
      if(!$rsBoleto || $oDaoBoleto->numrows == 0) {
        throw new Exception("[1] Erro ao consultar o registro. ERRO: {$oDaoBoleto->erro_msg}");        
      }
      
      $oBoleto = db_utils::fieldsMemory($rsBoleto, 0);
  
      $this->setCodigoBoleto($oBoleto->k139_sequencial);
      $this->setData        ($oBoleto->k139_data);
      $this->setHora        ($oBoleto->k139_hora);
      $this->setUsuario     ($oBoleto->k139_usuario);
      $this->setConvenio    ($oBoleto->k139_conveniocobranca);
      $this->setRegraEmissao($oBoleto->k139_regraemissao);
      $this->setCodigoBarras($oBoleto->k139_codigobarras);
      $this->setNossoNumero (0); //@todo implementar metodo      
    }
    
  }
  
  /**
   * Retorna o c�digo do boleto
   * @return integer
   */
  public function getCodigoBoleto() {
    return $this->iCodigoBoleto;
  }

  /**
   * Define o c�digo do boleto
   * @param integer $iCodigoBoleto
   */
  public function setCodigoBoleto($iCodigoBoleto) {
    $this->iCodigoBoleto = $iCodigoBoleto;
  }

  /**
   * Retorna o c�digo de barras do boleto
   * @return string
   */
  public function getCodigoBarras() {
    return $this->sCodigoBarras;
  }

  /**
   * Define o c�digo de barras do boleto
   * @param string $sCodigoBarras
   */
  public function setCodigoBarras($sCodigoBarras) {
    $this->sCodigoBarras = $sCodigoBarras;
  }

  /**
   * Retorna o c�digo da regra de emiss�o
   * @return integer
   */
  public function getRegraEmissao() {
    return $this->iRegraEmissao;
  }

  /**
   * Define o c�digo da regra de emiss�o
   * @param intenger $iRegraEmissao
   */
  public function setRegraEmissao($iRegraEmissao) {
    $this->iRegraEmissao = $iRegraEmissao;
  }

  /**
   * Retorna o c�digo do tipo de boleto 1 - Carne, 2 - Recibo
   * @return integer
   */
  public function getTipoBoleto() {
    return $this->iTipoBoleto;
  }

  /**
   * Define o tipo de boleto
   * @param integer $iTipoBoleto
   */
  public function setTipoBoleto($iTipoBoleto) {
    $this->iTipoBoleto = $iTipoBoleto;
  }

  /**
   * Retorna o conv�nio do boleto 
   * @return integer
   */
  public function getConvenio() {    
    return $this->iConvenio;
  }

  /**
   * Define o c�digo do conv�nio do boleto
   * @param integer $iConvenio
   */
  public function setConvenio($iConvenio) {
    $this->iConvenio = $iConvenio;
  }

  /**
   * Retorna a hora da gera��o do boleto 
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Define a hora da gera��o do boleto
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna data gera��o do boleto
   * @return date 
   */
  public function getData()  {
    return $this->dData;
  }

  /**
   * Define a data de gera��o do boleto
   * @param date $dData
   */
  public function setData($dData) {
    $this->dData = $dData;
  }

  /**
   * Retorna usu�rio do sistema da gera��o do boleto
   * @return integer
   */
  public function getUsuario()  {
    return $this->iUsuario;
  }

  /**
   * Define o usu�rio do sistema da gera��o do boleto
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Define c�digo nosso n�mero
   * @param integer $iNossoNumero
   */
  public function setNossoNumero($iNossoNumero) {
  	$this->iNossoNumero = $iNossoNumero;
  }
  
  /**
   * Retorna c�digo nosso n�mero
   * @return integer
   */
  public function getNossoNumero() {
  	return $this->iNossoNumero;
  }

  /**
   * Retorna instancia do model Boleto passando numnov
   * @param integer $iNumnov
   * @throws Exception Recibocodbar n�o encontrado ou inv�lido
   * @return Boleto
   */
  public static function getInstanceOfBoletoByNumnov($iNumnov) {
  	
  	$oDaoRecibocodbar = db_utils::getDao('recibocodbar');
  	
  	$sSqlRecibocodbar = $oDaoRecibocodbar->sql_query_file(null, 
  																												"*", 
  																										    null, 
  																											  "k00_numpre = {$iNumnov}");
  	
  	$rsRecibocodbar   = $oDaoRecibocodbar->sql_record($sSqlRecibocodbar);  	
  	
  	if (!$rsRecibocodbar || $oDaoRecibocodbar->numrows == 0) {
  		throw new Exception('Recibocodbar n�o encontrado ou inv�lido');
  	}
  	  	
  	return new Boleto(db_utils::fieldsMemory($rsRecibocodbar, 0)->k00_codbar);
  
  } 
  
}