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

require_once(modification("model/configuracao/UsuarioSistema.model.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/db_conn.php"));
require_once("libs/db_autoload.php");
require_once(modification("libs/db_stdlib.php"));
require_once(modification("model/configuracao/Encriptacao.model.php"));
$rsConectaBase   = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
$rsStartSession  = db_query("select fc_startsession()");

function autenticarUsuario($sUsuario, $sSenha) {

  $oUsuarioSistema = new UsuarioSistema();
  $oUsuarioSistema->getUsuarioByLogin($sUsuario);
  if (!$oUsuarioSistema->autenticar($sSenha)) {
    return false;
  }
  $oUsuarioSistema->setSenha(utf8_encode($oUsuarioSistema->getSenha()));
  $oUsuarioSistema->setNome(urlencode(utf8_encode($oUsuarioSistema->getNome())));
  return $oUsuarioSistema;
}
$sURI = 'http://localhost/dbportal_prj/webservices/';
$oSoapServer = new SoapServer(null, array('uri' => $sURI));
$oSoapServer->addFunction("autenticarUsuario");
$oSoapServer->handle();
?>
