<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once('dbagata/classes/core/AgataAPI.class');
require_once("model/documentoTemplate.model.php");

ini_set("error_reporting","E_ALL & ~NOTICE");

$oGet    = db_utils::postMemory($_GET);

$sDepartamento    = db_getsession("DB_nomedepto");
$clagata          = new cl_dbagata("vacinas/vacinas.agt");
$api              = $clagata->api;
$sCaminhoSalvoSxw = "tmp/vacinas_{$oGet->iCgs}.sxw";

$api->setOutputPath($sCaminhoSalvoSxw);
$api->setParameter('$iCgs',$oGet->iCgs);
$api->setParameter('$iDepartamento',$oGet->iDepartamento);
$api->setParameter('$sDepartamento',$sDepartamento);
$api->setParameter('$iEnfermeiro',$oGet->iEnfermeiro);


try {
   $oDocumentoTemplate = new documentoTemplate(10,$oGet->iCodDocumento); 
} catch (Exception $eException){
  $sErroMsg  = $eException->getMessage();
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}

$lProcessado = $api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());

if( $lProcessado ) {
  db_redireciona($sCaminhoSalvoSxw);
} else {
	db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gera relatório !!!");
}

?>