<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Strategy para  escrita de logs de rotina no sistema
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.5 $
 */
class DBLog {

  /**
   * Informa que a Mensagem de Log é apenas informativa
   * @var integer
   */
  const LOG_INFO   = 0;

  /**
   * Notifica no Log é apenas informativa
   * @var integer
   */
  const LOG_NOTICE = 1;

  /**
   * Grava log mostrando que houver erro
   * @var integer
   */
  const LOG_ERROR  = 2;

  /**
   * Objeto de Log qua vai ser implementado.
   * @var mixed:Object
   */
  private $oInstanciaLog = null;

  /**
   * Construtor da Classe
   * @param string $sTipoLog - Tipo de Log a Ser escrito
   * @param string $sDestino - Destino do Log. Ex.: Caminho do Arquivo de Log, Tabela do Banco...
   */
  function __construct( $sTipoLog = null, $sDestino = "" ) {

    switch ( $sTipoLog ) {

    case "JSON":
      db_app::import('configuracao.DBLogJSON');
      $this->oInstanciaLog = new DBLogJSON( $sDestino.".json" );
    break;

    case "TXT":
      db_app::import('configuracao.DBLogTXT');
      $this->oInstanciaLog = new DBLogTXT( $sDestino.".txt" );
    break;

    case "XML":
    default   :
      db_app::import('configuracao.DBLogXML');
      $this->oInstanciaLog = new DBLogXML( $sDestino.".xml" );
    break;

    }  
  }

  /**
   * Escreve log no destino
   * @param string $sLog
   * @param integer $iTipo
   */
  public function escreverLog($sLog = "", $iTipo = DBLog::LOG_INFO) {
    return $this->oInstanciaLog->log($sLog,  $iTipo);
  }
}