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
 * Classe para escrita de logs em xml
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbigor.cemim $
 * @version $Revision: 1.8 $
 */
require_once(modification("interfaces/iLog.interface.php"));

class DBLogXML implements iLog {

  private $sCaminhoArquivo = null;

  /**
   * Construtor da Classe
   * @param integer $sCaminhoArquivo
   */
  public function __construct($sCaminhoArquivo) {

    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->abrirNovoLog();
  }

  /**
   * Escreve Log
   * @see iLog::log()
   */
  public function log($sTextoLog, $iTipoLog = DBLog::LOG_INFO) {

    $oXML  = new DOMDocument('1.0', 'ISO-8859-1');
    $oXML  ->formatOutput = true;
    $oXML  ->load($this->sCaminhoArquivo);
    $oLogs = $oXML->getElementsByTagName("Logs")->item(0);

    $oLog  = $oXML->createElement("Log");
    $oLog  ->setAttribute( "InstanteLog", time() );
    $oLog  ->setAttribute( "TextoLog"   , urlencode($sTextoLog) );
    $oLog  ->setAttribute( "TipoLog"    , $iTipoLog);
    $oLogs ->appendChild($oLog);
    $oXML  ->save($this->sCaminhoArquivo);
  }

  public function abrirNovoLog() {

    $oXML  = new DOMDocument('1.0', 'ISO-8859-1');
    $oXML->formatOutput = true;
    $oLogs = $oXML->createElement('Logs'); //ROOT
    $oLogs = $oXML->appendChild($oLogs);
    $oXML->save($this->sCaminhoArquivo);
  }

  /**
   * Retorna o conteudo salvo no arquivo.
   * @param  string $sCaminhoArquivo
   * @return string
   */
  public function getConteudo($sCaminhoArquivo){
    return file_get_contents($sCaminhoArquivo);
  }

  public function getArquivo() {
    return $this->sCaminhoArquivo;
  }
}
