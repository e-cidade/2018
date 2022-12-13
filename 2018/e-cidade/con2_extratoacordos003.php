<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('std/db_stdClass.php');
require_once('libs/db_libsys.php');
require_once('dbagata/classes/core/AgataAPI.class');
require_once('model/documentoTemplate.model.php');

$oGet      = db_utils::postMemory($_GET);
$iAcordo   = $oGet->iAcordo;
$iModelo   = $oGet->iModelo;
$dtGeracao = date('YmdHis');
$iUsuario  = db_getsession("DB_id_usuario");

ini_set("error_reporting","E_ALL & ~NOTICE");

$clagata            = new cl_dbagata("acordo/acordo_extrato.agt");
$oApi               = $clagata->api;
$sCaminhoSalvoSxw   = "tmp/extrato_acordo_{$iAcordo}_{$dtGeracao}_{$iUsuario}_.sxw";
$oDocumentoTemplate = new documentoTemplate(45, $iModelo);

$oApi->setOutputPath($sCaminhoSalvoSxw);
$oApi->setParameter('$iAcordo', $iAcordo);

try {

  if ( $oApi->parseOpenOffice ( $oDocumentoTemplate->getArquivoTemplate() ) ) {
  
  	if ($oApi->getRowNum() == 0){

  	  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
  	}
  
  	$sNomeRelatorio   =  "tmp/extrato_acordo_{$iAcordo}_{$dtGeracao}_{$iUsuario}_.pdf";
  	$sComandoConverte = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);
  
  	if (!$sComandoConverte) {
  
  		db_redireciona("db_erros.php?fechar=true&db_erro=Erro Gerar Documento.");
  
  	} else {
  
  	  db_redireciona($sNomeRelatorio);
  	}
  }
  
} catch (Exception $eException){
  
  $sErroMsg  = $eException->getMessage();
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}
