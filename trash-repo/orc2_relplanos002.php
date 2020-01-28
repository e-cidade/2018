<?
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libsys.php");
include_once 'dbagata/classes/core/AgataAPI.class';

ini_set("error_reporting","E_ALL & ~NOTICE");

$clagata = new cl_dbagata("orcamento/orc2_relplanos002.agt");

$api = $clagata->api;

$api->setParameter('$head1', "Relatório: Pacto Planos");
$api->setParameter('$head2', "DATA EMISSÃO: ".date('d/m/Y',db_getsession('DB_datausu')));
$api->setParameter('$head3', "");
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");

$ok = $api->generateReport();

if (!$ok){
	echo $api->getError();
}else{
	db_redireciona($clagata->arquivo);
}
?>