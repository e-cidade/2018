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
   * Código do boleto
   * @var integer
   */
  protected $iCodigoBoleto;
  
  /**
   * Código de barras do boleto
   * @var integer
   */
  protected $sCodigoBarras;
  
  /**
   * Regra de emissão do boleto
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
   * Código do convênio
   * @var integer
   */
  protected $iConvenio;
  
  /**
   * Hora da geração do boleto
   * @var string
   */
  protected $sHora;
  
  /**
   * Data da geração do boleto
   * @var date
   */
  protected $dData;
  
  /**
   * Código do usuário da geração do boleto
   * @var integer
   */
  protected $iUsuario;
  
  /**
   * Código nosso número
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
   * Retorna o código do boleto
   * @return integer
   */
  public function getCodigoBoleto() {
    return $this->iCodigoBoleto;
  }

  /**
   * Define o código do boleto
   * @param integer $iCodigoBoleto
   */
  public function setCodigoBoleto($iCodigoBoleto) {
    $this->iCodigoBoleto = $iCodigoBoleto;
  }

  /**
   * Retorna o código de barras do boleto
   * @return string
   */
  public function getCodigoBarras() {
    return $this->sCodigoBarras;
  }

  /**
   * Define o código de barras do boleto
   * @param string $sCodigoBarras
   */
  public function setCodigoBarras($sCodigoBarras) {
    $this->sCodigoBarras = $sCodigoBarras;
  }

  /**
   * Retorna o código da regra de emissão
   * @return integer
   */
  public function getRegraEmissao() {
    return $this->iRegraEmissao;
  }

  /**
   * Define o código da regra de emissão
   * @param intenger $iRegraEmissao
   */
  public function setRegraEmissao($iRegraEmissao) {
    $this->iRegraEmissao = $iRegraEmissao;
  }

  /**
   * Retorna o código do tipo de boleto 1 - Carne, 2 - Recibo
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
   * Retorna o convênio do boleto 
   * @return integer
   */
  public function getConvenio() {    
    return $this->iConvenio;
  }

  /**
   * Define o código do convênio do boleto
   * @param integer $iConvenio
   */
  public function setConvenio($iConvenio) {
    $this->iConvenio = $iConvenio;
  }

  /**
   * Retorna a hora da geração do boleto 
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Define a hora da geração do boleto
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna data geração do boleto
   * @return date 
   */
  public function getData()  {
    return $this->dData;
  }

  /**
   * Define a data de geração do boleto
   * @param date $dData
   */
  public function setData($dData) {
    $this->dData = $dData;
  }

  /**
   * Retorna usuário do sistema da geração do boleto
   * @return integer
   */
  public function getUsuario()  {
    return $this->iUsuario;
  }

  /**
   * Define o usuário do sistema da geração do boleto
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Define código nosso número
   * @param integer $iNossoNumero
   */
  public function setNossoNumero($iNossoNumero) {
  	$this->iNossoNumero = $iNossoNumero;
  }
  
  /**
   * Retorna código nosso número
   * @return integer
   */
  public function getNossoNumero() {
  	return $this->iNossoNumero;
  }

  /**
   * Retorna instancia do model Boleto passando numnov
   * @param integer $iNumnov
   * @throws Exception Recibocodbar não encontrado ou inválido
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
  		throw new Exception('Recibocodbar não encontrado ou inválido');
  	}
  	  	
  	return new Boleto(db_utils::fieldsMemory($rsRecibocodbar, 0)->k00_codbar);
  
  } 
  
}