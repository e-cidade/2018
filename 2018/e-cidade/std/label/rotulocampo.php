<?php 

/**
 * rotulo
 * 
 * @package 
 * @version $id$
 * @author <> 
 */
class rotulocampo {

  private $oInstance;
  /**
   * Construtor da classe 
   *
   * @access public
   * @return void
   */

  public function label ($sNomeTabelaCampo = "" ) {


    $aNomeTabelaCampo = explode(".", $sNomeTabelaCampo);

    if ( count($aNomeTabelaCampo) == 2 ) {

      $sNomeTabela      = $aNomeTabelaCampo[0];
      $sNomeCampo       = $aNomeTabelaCampo[1];

      if ( file_exists( "dd/tabelas/{$sNomeTabela}.dd.xml") ) {

        $this->oInstance = new RotuloXML($sNomeTabela);
        $this->oInstance->label($sNomeCampo);
        return;
      }
    }

    $sNomeCampo      = $sNomeTabelaCampo;
    $this->oInstance = new RotuloCampoDB();
    $this->oInstance->label($sNomeCampo);

    return;
  }

}
