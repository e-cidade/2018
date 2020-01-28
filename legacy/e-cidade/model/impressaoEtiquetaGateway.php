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


class impressaoEtiquetaGateway {

	public   $oModelo;

	function __construct() {

    $this->oModelo = null;

  }

  function getModelo() {

    $oImpressao = null;

    $sSqlModelo   = " select t71_strxml,t71_sequencial";
    $sSqlModelo  .= "   from bensmodeloetiqueta ";
    $sSqlModelo  .= "        inner join cfpatri on cfpatri.t06_bensmodeloetiqueta = bensmodeloetiqueta.t71_sequencial";

    $rsSqlModelo  = db_query($sSqlModelo);

    if ($rsSqlModelo == false || pg_num_rows($rsSqlModelo) == 0) {
    	throw new Exception("Modelo de impressão não encontrado!!! Verifique os parâmetros !!!");
    }
    $oModelo      = db_utils::fieldsMemory($rsSqlModelo,0);

    $oDocXml = new DOMDocument();
    $oDocXml->loadXML($oModelo->t71_strxml);
    $sModelo = $oDocXml->getElementsByTagName('modelo_etiqueta')->item(0)->getAttribute('modelo');

    switch($sModelo) {

    	case 'OS-214':
    		require  'model/modelo.4CM.php' ;
    		$oImpressao = new modelo4CM($oModelo->t71_sequencial);
    		break;

  		case 'OS-214-Plus':
  		  require  'model/modelo.4CM.Plus.php' ;
  		  $oImpressao = new modelo4CMPlus($oModelo->t71_sequencial);
  		  break;

    	default:

    }

    $this->oModelo = $oImpressao;

    return $this->oModelo;

  }

}

?>