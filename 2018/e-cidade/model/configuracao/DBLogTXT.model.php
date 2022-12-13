<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("interfaces/iLog.interface.php"));
/**
 * Classe para escrita de logs em TXT
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbigor.cemim $
 * @version $Revision: 1.9 $
 */
class DBLogTXT implements iLog {

  private $sCaminhoArquivo;

  private $pArquivo;

  private $lMostraDataHora = true;

  private $lMostraStatus = true;

  /**
   * Construtor da Classe
   * @param integer $sCaminhoArquivo
   */
  public function __construct($sCaminhoArquivo) {
    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->pArquivo = fopen($sCaminhoArquivo, 'w');
  }

  /**
   * Escreve Log
   * @see iLog::log()
   */
  public function log($sTextoLog, $iTipoLog = DBLog::LOG_INFO) {

    $oDataHora = (object)getdate();

    switch ( $iTipoLog ) {

      case DBLog::LOG_INFO:
        $sTipo = "INFO ";
        break;
      case DBLog::LOG_NOTICE:
        $sTipo = "AVISO";
        break;
      case DBLog::LOG_ERROR:
        $sTipo = "ERRO ";
        break;
    }

    $sMensagem = sprintf("[ %s ] %s", $sTipo, $sTextoLog."\n");

    if ($this->lMostraDataHora) {

     $sMensagem = sprintf("[ %s - %02d/%02d/%04d - %02d:%02d:%02d] %s", $sTipo,
                                                                        $oDataHora->mday,
                                                                        $oDataHora->mon,
                                                                        $oDataHora->year,
                                                                        $oDataHora->hours,
                                                                        $oDataHora->minutes,
                                                                        $oDataHora->seconds,
                                                                        $sTextoLog."\n");
    }

    if ( !$this->lMostraStatus ) {
      $sMensagem = sprintf($sTextoLog. "\n");
    }

    return fputs($this->pArquivo, $sMensagem);
  }

  public function finalizarLog() {
    fclose($this->pArquivo);
  }

  public function __destruct() {
    $this->finalizarLog();
  }

  /**
   * Retorna o conteudo salvo no arquivo.
   * @param  string $sCaminhoArquivo
   * @return string
   */
  public function getConteudo($sCaminhoArquivo){
    return file_get_contents($sCaminhoArquivo);
  }

  public function setMostraDataHora($lMostra) {
    $this->lMostraDataHora = $lMostra;
  }

  public function setMostraStatus($lMostra) {
    $this->lMostraStatus = $lMostra;
  }

  public function getArquivo() {
    return $this->sCaminhoArquivo;
  }
}
