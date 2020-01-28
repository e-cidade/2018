<?php 

class DBReleaseNotePlugin extends DBReleaseNote {

  private $oPlugin;

  public function __construct($idUsuario, $sNomeArquivo = null, $sNomeArquivoAtual = null, $sVersao = null, Plugin $oPlugin = null) {
    
    parent::__construct($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);

    $this->oPlugin = $oPlugin;

    if ($oPlugin === null) {
      $this->oPlugin = PluginService::getPluginAtual( current(explode('_', $sNomeArquivo)) , true);
    }

    if (!$this->oPlugin) {
      throw new BusinessException("Plugin atual não encontrado.");
    }

    $this->setDirBase("plugins/{$this->oPlugin->getNome()}/release_notes");
    $this->oPlugin->getVersao();

  }

  /**
   * @inherited
   */
  public function getMudancasLidas($iSorting = self::SORT_ASC) {

    $aDadosReleaseNotes = static::getDados($this->oPlugin);

    $aReleaseNotes = array();

    if ($iSorting == self::SORT_ASC) {
      ksort($aDadosReleaseNotes);
    } else {
      krsort($aDadosReleaseNotes);
    }

    foreach ($aDadosReleaseNotes as $sVersao => $aDadosMenu) {
      
      foreach ($aDadosMenu as $sMenu => $aDadoUsuario) {

        if ( strpos((string) $sMenu, (string) $this->sNomeArquivo) !== 0 ) {
          continue;
        }

        foreach ($aDadoUsuario as $iUsuario => $lLido) {          

          if ($iUsuario != $this->iUsuario) {
            continue;
          }

          $aReleaseNotes[] = (object) array(
            'iIdUsuario' => $iUsuario,
            'sNomeArquivo' => $sMenu,
            'sVersao' => $sVersao
          );

        }
      }

    }

    return $aReleaseNotes;
  }

  /**
   * @inherited
   */
  public function filtrarVersao($sVersao) {
    return $sVersao <= $this->oPlugin->getVersao();
  }

  /**
   * @inherited
   */
  public function getVersaoFormatada() {

    if (empty($this->sVersao)) {
      $this->sVersao = $this->getPrimeiraVersaoNaoLida();
    }

    return $this->sVersao;
  }

  public function render() { 

    if (empty($this->sNomeArquivo)) {
      return;
    }

    if ($this->check()) {

      $sScriptChangelog  = "<script src=\"scripts/classes/configuracao/DBViewReleaseNote.classe.js\" type=\"text/javascript\"></script>\n";
      $sScriptChangelog .= "<script type=\"text/javascript\">\n";
      $sScriptChangelog .= " DBViewReleaseNote.build(null, true, DBViewReleaseNote.TIPO_PLUGIN); \n";
      $sScriptChangelog .= "</script>";

      return $sScriptChangelog;
    }    

  }

  /**
   * @inherited
   */
  public function marcarComoLido($aArquivosLidos) {

    $aDadosReleaseNotes = static::getDados($this->oPlugin);

    foreach ($aArquivosLidos as $oDadoArquivo) {
      
      if ( !isset($aDadosReleaseNotes[$oDadoArquivo->sVersao]) ) {
        $aDadosReleaseNotes[$oDadoArquivo->sVersao] = array();
      }

      if ( !isset($aDadosReleaseNotes[$oDadoArquivo->sVersao][$oDadoArquivo->sNomeArquivo]) ) {
        $aDadosReleaseNotes[$oDadoArquivo->sVersao][$oDadoArquivo->sNomeArquivo] = array();
      }

      if ( !isset($aDadosReleaseNotes[$oDadoArquivo->sVersao][$oDadoArquivo->sNomeArquivo][$this->iUsuario]) ) {
        $aDadosReleaseNotes[$oDadoArquivo->sVersao][$oDadoArquivo->sNomeArquivo][$this->iUsuario] = true;
      }

    }

    $sDiretorio = "release_notes/plugins/{$this->oPlugin->getNome()}/";

    if (!is_dir($sDiretorio) && !mkdir($sDiretorio, 0775, true)) {
      throw new BusinessException("Erro ao criar diretório: $sDiretorio");              
    }

    $salvou = file_put_contents("{$sDiretorio}release_notes.json", json_encode($aDadosReleaseNotes));
    if (!$salvou) {
      throw new BusinessException("Erro ao salvar arquivo: $sDiretorio . release_notes.json");
    }
  }

  /**
   * @param Plugin $oPlugin [description]
   * @return array
   */
  public static function getDados(Plugin $oPlugin) {

    $sCaminhoDadoReleaseNote = "release_notes/plugins/{$oPlugin->getNome()}/release_notes.json";

    if ( !file_exists($sCaminhoDadoReleaseNote) ) {
      return array();
    }

    $sJson = file_get_contents($sCaminhoDadoReleaseNote);

    $aRetorno = json_decode($sJson, true);

    if (empty($aRetorno)) {
      return array();
    }

    return $aRetorno;
  }

}
