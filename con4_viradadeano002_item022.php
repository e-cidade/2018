<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

$oDaoIptuTabelasConfig = db_utils::getDao("iptutabelasconfig");
require_once('model/ViradaIPTUFactory.model.php');

db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);

if ($sqlerro == false) {

  try {
	
  	$sSqlTabelasConfig   = $oDaoIptuTabelasConfig->sql_query(null, "db_sysarquivo.nomearq", null, "");
  	$rsTablasConfig      = $oDaoIptuTabelasConfig->sql_record($sSqlTabelasConfig);
  	$aListaTabelasConfig = array();
  	if ($oDaoIptuTabelasConfig->numrows == 0) {
  		
      $sMensagem = "ERRO: Não existe tabela de iptu configurada!";
      throw new Exception($sMensagem);
  	}
  	
    $aListaTabelasConfig = db_utils::getColectionByRecord($rsTablasConfig);
    foreach ($aListaTabelasConfig as $oTabelaConfig) {
        
      $oViradaIPTU = ViradaIPTUFactory::getInstance($oTabelaConfig->nomearq);
      $oViradaIPTU->vira();
    }
  	
    $sqlerro = false;
  } catch (Exception $eErro) {
  
    $sqlerro = true;
    db_msgbox($eErro->getMessage());
  }
}

db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
?>