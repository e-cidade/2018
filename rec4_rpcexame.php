<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require "libs/db_stdlib.php";
require "libs/db_conecta.php";
include "libs/db_sessoes.php";
require "libs/db_utils.php";
require "libs/JSON.php";
require "classes/estagioAvaliacoes.classe.php";
require "dbforms/db_funcoes.php";
$post     = db_utils::postmemory($_POST);
$json     = new services_json();
$objJson  = $json->decode(str_replace("\\","",$_POST["json"]));
$objExame = new estagioAvaliacao($objJson->iCodExame);
$method   = $objJson->method;
if ($method == "getDadosExame"){
  
  if (isset($objJson->refresh)) {
    unset($_SESSION["avaliacao"]);
    $objExame->gravarSessao();
  }
  echo $objExame->getDadosExame($objJson->iCodQuesito);
  
} else if($method == "getQuesitosExame") {
  
  echo $objExame->getQuesitosExame(null);
  $objExame->cancelarExame();
    
} else if ($method == "salvarResposta") {
  
  $objExame->salvarResposta($objJson->iCodQuestao,$objJson->iResposta, $objJson->iTipo,$objJson->sObsRecomendacao,$objJson->sObsPergunta);
  print_r( $_SESSION["avaliacao"]);
  
} else if ($method == "salvarExame") {
  
  $array = array(
                 "h56_rhestagiocomissao" => $objJson->h56_estagiocomissao,
                 "h56_avaliador"         => $objJson->h56_avaliador
                );
  echo  $objExame->salvarExame($array);
  
}
?>