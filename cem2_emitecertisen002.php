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
require ("model/documentoTemplate.model.php");
require_once("std/db_stdClass.php");

$oGet = db_utils::postMemory($_GET);

ini_set("error_reporting","E_ALL & ~NOTICE");

$sAgt             = "cemiterio/cem2_emitecertisen002.agt";
$sCaminhoSalvoSxw = "tmp/docSalvoSxw".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
$sNomeRelatorio   = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".pdf";

$aParam                   = array();
$aParam['$codigoisencao'] = $oGet->codigoisencao;
$aParam['$dia_atual']     = date('d',db_getsession('DB_datausu'));
$aParam['$mes_atual']     = db_mes(date('m',db_getsession('DB_datausu')),2);
$aParam['$ano_atual']     = date('Y',db_getsession('DB_datausu')); 

db_stdClass::oo2pdf(3, null, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);

?>