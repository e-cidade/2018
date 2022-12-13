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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/FileException.php");
require_once ("model/configuracao/mensagem/DBMensagem.model.php");

$oJson  = new Services_JSON();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

if ($oParam->exec == 'getFile') {

  $iCodigoMenuAcessado = db_getsession("DB_itemmenu_acessado", false);
  if (db_getsession("DB_login") === "dbseller" && !empty($iCodigoMenuAcessado)) {
    $lValidarVinculo = DBMensagem::associarArquivo($oParam->file, db_getsession("DB_itemmenu_acessado"));
  }

   echo urlencode(DBMensagem::getFile($oParam->file));
}

/**
 * Guarder historico das mensagens carregada no menu atual 
 */
if ($oParam->exec == 'guardarHistorico') {
  DBMensagem::guardarHistorico($oParam->sArquivo, $oParam->sNomeMensagem);
}