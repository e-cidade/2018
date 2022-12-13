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


require_once ('model/educacao/IEscola.interface.php');

class EscolaProcedencia implements IEscola {
  
	/**
	 * Codigo da EscolaProcedencia
	 * @var integer
	 */
  protected $iCodigo;
  
  /**
   * Nome da escola
   * @var string
   */
  protected $sNome;
  
  /**
   * Municipio onde se encontra a escola
   * @var string
   */
  protected $sMunicipio;
  
  /**
   * UF onde se encontra a escola
   * @var string
   */
  protected $sUf;
  
  /**
   * Instancia de Pais
   * @var object Pais
   */
  protected $oPais;
  
  /**
   * Construtor da classe. Recebe o codigo como parametro, e caso seja diferente de null, seta os dados
   * @param integer
   */
  function __construct( $iCodigo = null ) {
    
  	if ( !empty($iCodigo) ) {
  		
  		$oDaoEscolaProc = db_utils::getDao("escolaproc");
  		$sSqlEscolaProc = $oDaoEscolaProc->sql_query($iCodigo);
  		$rsEscolaProc   = $oDaoEscolaProc->sql_record($sSqlEscolaProc);
  		
  		if ( $oDaoEscolaProc->numrows > 0 ) {
  			
  			$oDadosEscolaProc = db_utils::fieldsMemory($rsEscolaProc, 0);
  			$this->iCodigo    = $oDadosEscolaProc->ed82_i_codigo;
  			$this->sNome      = $oDadosEscolaProc->ed82_c_nome;
  			$this->sMunicipio = $oDadosEscolaProc->ed261_c_nome;
  			$this->sUf        = $oDadosEscolaProc->ed260_c_sigla;
  			$this->oPais      = new Pais($oDadosEscolaProc->ed82_pais);
  		}
  	}
  }
  
  /**
   * Retorna o codigo da escola
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;  
  }
  
  /**
   * Retorna o nome da Escola;
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * Define o nome da Escola
   * @param string $sNome nome da escola
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna o nome do municipio;
   * @return string
   */
  public function getMunicipio() {
  	return $this->sMunicipio;
  }
  
  /**
   * Define o nome do Municipio
   * @param string $sMunicipio nome do municipio
   */
  public function setMunicipio($sMunicipio) {
  	$this->sMunicipio = $sMunicipio;
  }
  
  /**
   * Retorna a UF da escola
   * @return string
   */
  public function getUf() {
  	return $this->sUf;
  }
  
  /**
   * Define a UF da escola
   * @param string $sUf UF da escola
   */
  public function setUf($sUf) {
  	$this->sUf = $sUf;
  }
  
  /**
   * Retorna uma instancia de Pais
   * @return Pais
   */
  public function getPais() {
  	return $this->oPais;
  }
  
  /**
   * Seta uma instancia de Pais
   * @param Pais $oPais
   */
  public function setPais(Pais $oPais) {
  	$this->oPais = $oPais;
  }
}

?>