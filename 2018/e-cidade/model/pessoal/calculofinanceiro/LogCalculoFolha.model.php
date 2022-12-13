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
 * LogCalculoFolha
 * 
 * @abstract
 * @package Pessoal
 * @subpackage Calculo Financeiro
 * @author Rafael Serpa Nery  <rafael.nery@dbseller.com.br> 
 */
abstract class LogCalculoFolha {

  const STR                  = 1;
  const ARR                  = 2;
  const HTML                 = 2;
  static private $iIndiceLog = 0;
  const ESPACADOR            = "_";

  /**
   * Guarda os Dados do log do Cálculo 
   */
  private static $aLog = array();
  /**
   * Recupera LOG armazenado
   *
   * @param string $sType
   * @static
   * @access public
   * @return array|string
   */
  static public function getLog( $sType = LogCalculoFolha::ARR ) {

    if ( $sType == LogCalculoFolha::STR ) {

      $sRetorno = '';

      foreach(LogCalculoFolha::$aLog as $aEtapaLog) {
        $sRetorno .= "\n" . implode("\n", $aEtapaLog);
      }
      return $sRetorno;

    } else if ( $sType == LogCalculoFolha::HTML ) {

      $sRetorno = '';

      foreach(LogCalculoFolha::$aLog as $aEtapaLog) {
        $sRetorno .= "<pre>" . implode("\n", $aEtapaLog) . "</pre>";
      }
      return $sRetorno;
    }
    return LogCalculoFolha::$aLog;
  }

  /**
   * Escreve log
   *
   * @static
   * @access public
   * @return void
   */
  static public function write( $sLog = '' ) {

    global $db_debug;

    if ( empty($db_debug) ) {
      return;
    }
    $iIndiceLog = LogCalculoFolha::$iIndiceLog++;
    $aPartes    = explode("\n", $sLog);
    $aRastros   = debug_backtrace();
    $iLinha     = $aRastros[0]['line'];
    $sArquivo   = explode("/",$aRastros[0]['file']);
    $sArquivo   = $sArquivo[count($sArquivo) - 1];
    $aFunction  = $aRastros[1];
    $aRastros   = $aRastros[0];
    $sInfo      = str_pad($sArquivo, 40,".", STR_PAD_RIGHT);
    $sInfo     .= str_pad($iLinha,    6,".", STR_PAD_RIGHT) . "|";
    $sInfo     .= str_pad("{$aFunction['function']}", 30,".", STR_PAD_RIGHT);
    $sCola      = "[$sInfo]";  
    $iEspacos   = (count(debug_backtrace()) - 2)  * 2;
    $sEspacos   = str_pad("",$iEspacos,LogCalculoFolha::ESPACADOR, STR_PAD_BOTH);
    foreach($aPartes as $sParte ) {
      LogCalculoFolha::$aLog[$iIndiceLog][] = "{$sCola} {$sEspacos} {$sParte}";
    }
    return;
  }
}
