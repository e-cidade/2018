<?php 

/**
 * rotulo
 * 
 * @package 
 * @version $id$
 * @author <> 
 */
class rotulo {

  private $oInstance;
  /**
   * Construtor da classe 
   *
   * @param string $sNomeTabela
   * @access public
   * @return void
   */
  public function __construct( $sNomeTabela )  {

    if ( file_exists( "dd/tabelas/{$sNomeTabela}.dd.xml") ) {
      $this->oInstance = new RotuloXML($sNomeTabela);
      return;
    }

    $this->oInstance = new RotuloDB($sNomeTabela);
    return;
  }

  public function label ($sNomeCampo = "" ) {
    return $this->oInstance->label($sNomeCampo);
  }

  public function rlabel ($sNomeCampo = "" ) {
    return $this->oInstance->rlabel($sNomeCampo);
  }

  public function tlabel ($sNomeCampo = "") {
    return $this->oInstance->tlabel($sNomeCampo);
  }
}
