<?php

class ProgressBar {

  private $sProgressBar;

  private $iTotalRegistros;

  private $iProgressoAtual;

  private $iProgressoAuxiliar;

  /**
   * @param string sProgressBar Nome da instância do componente de tela.
   */
  public function __construct($sProgressBar) {
    $this->sProgressBar = $sProgressBar;
  }

  public function updateMaxProgress($iTotalRegistros) {

    $this->iTotalRegistros = $iTotalRegistros;
    echo "<script type=\"text/javascript\">{$this->sProgressBar}.getBar().max = '$iTotalRegistros';</script>\n";
    $this->flush();
  }

  public function updatePercentual($iRegistroAtual) {

    $this->iProgressoAtual = (int) (($iRegistroAtual * 100) / $this->iTotalRegistros);

    if ($this->iProgressoAtual != $this->iProgressoAuxiliar) {

      echo "<script type=\"text/javascript\">{$this->sProgressBar}.updateProgress('$iRegistroAtual');</script>\n";
      $this->iProgressoAuxiliar = $this->iProgressoAtual;
      $this->flush();
    }
  }

  public function setMessageLog($sMessage) {

    echo "<script type=\"text/javascript\">{$this->sProgressBar}.logMessage('$sMessage');</script>\n";
    $this->flush();
  }

  public function flush() {

    echo str_repeat(' ', 1024 * 64);
    flush();
  }

  public function getProgressoAtual() {
    return $this->iProgressoAtual;
  }

  public function getTotalRegistros() {
    return $this->iTotalRegistros;
  }
}
