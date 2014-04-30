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

$oLayoutTxt = db_utils::getDao('db_layouttxt');

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

if ($oParam->exec == "getDadosArquivos") {
   
  $sCampos    = "db50_codigo, db50_descr, db56_descr, db50_layouttxtgrupo";
  $rsArquivos = $oLayoutTxt->sql_record($oLayoutTxt->sql_query(null,$sCampos,"db50_codigo","db56_layouttxtgrupotipo = 2"));

  if ($rsArquivos) {

    $aArquivos = db_utils::getColectionByRecord($rsArquivos,false,false,true);

  } else {

   $sMensagem = "Arquivos nao encontrados";
   $iStatus   = 2;
   $aArquivos = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
    
  } 

  echo $oJson->encode($aArquivos);


}
?>