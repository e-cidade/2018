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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_censouf_classe.php");
include("classes/db_aluno_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censocartorio_classe.php");
include("dbforms/db_funcoes.php");
$clcensouf       = new cl_censouf;
$claluno         = new cl_aluno;
$clcensomunic    = new cl_censomunic;
$clcensocartorio = new cl_censocartorio;
$oPost           = db_utils::postMemory($_POST);

if ($oPost->sAction == 'PesquisaMunicipio') {
	$rsResultMunic = $clcensomunic->sql_record(
	                                           $clcensomunic->sql_query_file("",
	                                                                         "ed261_i_codigo,ed261_c_nome",
	                                                                         "ed261_c_nome",
                                                                             "ed261_i_censouf = {$oPost->uf}"
	                                                                        )
	                                          );    
    $aResult = db_utils::getColectionByRecord($rsResultMunic, false, false, true);   
    $oJson   = new services_json();
    echo $oJson->encode($aResult);
  
}

if ($oPost->sAction == 'PesquisaCartorio') {
    
  if ($oPost->municipio == "") {

    $oJson    = new services_json();
    echo $oJson->encode(array(array(),array()));
    return false;
    
  } 
  $rsResultCartorio = $clcensocartorio->sql_record(
                                                   $clcensocartorio->sql_query_file("",
                                                                                    "ed291_i_codigo,ed291_c_nome",
                                                                                    "ed291_c_nome",
                                                                             "ed291_i_censomunic = {$oPost->municipio}"
                                                                                   )
                                                  );     
  $aResult1 = db_utils::getColectionByRecord($rsResultCartorio, false, false, true);
  $oJson    = new services_json();
  echo $oJson->encode($aResult1);
 
}
?>