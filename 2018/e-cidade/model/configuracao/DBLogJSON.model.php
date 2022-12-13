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

/**
 * Classe para escrita de logs em TXT
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbigor.cemim $
 * @version $Revision: 1.8 $
 */
require_once(modification("interfaces/iLog.interface.php"));

class DBLogJSON implements iLog {

  private $sCaminhoArquivo = null;
  private $pArquivo;
  private $lMostraDataHora = false;
  private $oServiceJson    = null;
  private $oLog            = null;
  /**
   * Construtor da Classe
   * @param integer $sCaminhoArquivo
   */
  public function __construct($sCaminhoArquivo, $lMostrarHora = false) {

    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->lMostraDataHora = $lMostrarHora;
    $this->oServiceJson    = new Services_JSON();
    $this->pArquivo        = fopen($sCaminhoArquivo, 'w');
    $this->oLog            = new stdClass();
  }

  /**
   * Escreve Log
   * @see iLog::log()
   */
  public function log($oObject, $iTipoLog = DBLog::LOG_INFO) {

    $oDataHora = (object) getdate();

    switch ( $iTipoLog ) {

      case DBLog::LOG_INFO:
        $sTipo = "INFO";
        break;
      case DBLog::LOG_NOTICE:
        $sTipo = "AVISO";
        break;
      case DBLog::LOG_ERROR:
        $sTipo = "ERRO";
        break;
    }

    $oObject->tipo = $sTipo;
    if ($this->lMostraDataHora) {

     $oObject->data = sprintf("%02d/%02d/%04d", $oDataHora->mday, $oDataHora->mon, $oDataHora->year);
     $oObject->hora = sprintf("%02d:%02d:%02d", $oDataHora->hours, $oDataHora->minutes, $oDataHora->seconds);

    }
    $this->oLog->aLogs[] = clone $oObject;
    unset($oObject);
  }

  public function finalizarLog() {

    fputs($this->pArquivo, $this->oServiceJson->encode($this->oLog));
    fclose($this->pArquivo);
  }

  /**
   * Retorna o conteudo salvo no arquivo.
   * @param  string $sCaminhoArquivo
   * @return string
   */
  public function getConteudo( $sCaminhoArquivo = null ){

    if (is_null($sCaminhoArquivo)) {
      $sCaminhoArquivo = $this->sCaminhoArquivo;
    }
    $sArquivo = file_get_contents($sCaminhoArquivo);
    return $sArquivo;
  }

  public function __destruct() {

    if (is_resource($this->pArquivo)) {
      $this->finalizarLog();
    }
  }

  public function getArquivo() {
    return $this->sCaminhoArquivo;
  }
}
