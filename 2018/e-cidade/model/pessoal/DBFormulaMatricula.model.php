<?php 

class DBFormulaMatricula extends DBFormula {

	/**
	 * Objeto de servidor
	 */
	private $oServidor;

	/**
	 * Construtor da classe
	 */
  public function __construct( Servidor $oServidor ) {
  	$this->setServidor($oServidor);
  }


  /**
   * Define o Servidor
   * @param Servidor
   */
  public function setServidor ($oServidor) {
    $this->oServidor = $oServidor;
  }
  
  /**
   * Retorna o Servidor
   * @return Servidor
   */
  public function getServidor () {
    return $this->oServidor; 
  }

  
  /**
   * Adiciona a variável de servidor
   * @param String
   */
  public function adicionarVariavelServidor($sNomeVariavel) {

  	if(empty($sNomeVariavel)) {
  		return false;
  	}

  	$this->adicionar($sNomeVariavel, $this->oServidor->getMatricula());
  }
}
