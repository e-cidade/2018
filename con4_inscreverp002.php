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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conencerramento_classe.php");
include("classes/db_conencerramentolancam_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_empelemento_classe.php");
include("libs/db_libcontabilidade.php");

$clconencerramento  = new cl_conencerramento();
$post               = db_utils::postmemory($_POST);

$json               = new services_json();
$objJson = $json->decode(str_replace("\\","",$_POST["json"]));
$method = $objJson->method;
if ($objJson->method =="consultarRPAjax"){
   echo $clconencerramento->$method(db_getsession("DB_instit"),db_getsession("DB_anousu"),$objJson->datalanc, $objJson->pars);
}
if ($objJson->method =="inscreverRPAjax"){
   echo $clconencerramento->$method(db_getsession("DB_instit"),db_getsession("DB_anousu"),$objJson->pars,$objJson->datalanc);
}