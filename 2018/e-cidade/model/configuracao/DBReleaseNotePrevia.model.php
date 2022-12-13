<?php 

class DBReleaseNotePrevia extends DBReleaseNoteSistema {

  /**
   * @inherited
   */
  public function filtrarVersao($sVersao) {    
    return $sVersao > $this->sVersaoSistema;
  }

  /**
   * @param  integer $usuario 
   * @param  integer $idItem
   * @return string
   */
  public function render() {

    if (empty($this->sNomeArquivo)) {
      return;
    }    

    $aVersoes = $this->getVersoesPorNomeArquivo();    

    if (empty($aVersoes)) {
      return;
    }

    $sHtml  = '<div class="db-release-note-button db-release-note-previa">';
    $sHtml .= '  <a onclick="require_once(\'scripts/classes/configuracao/DBViewReleaseNote.classe.js\'); DBViewReleaseNote.build(null, false, DBViewReleaseNote.TIPO_PREVIA);">O que há de novo?</a>';
    $sHtml .= '</div>';

    return $sHtml;
  }

}