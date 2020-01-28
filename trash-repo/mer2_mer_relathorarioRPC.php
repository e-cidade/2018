<?php
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
require("libs/db_stdlibwebseller.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapioescola_classe.php");
include("dbforms/db_funcoes.php");
$clmer_cardapioescola  = new cl_mer_cardapioescola;
$nutricionista = VerNutricionista(db_getsession("DB_id_usuario"));
$escola = db_getsession("DB_coddepto");
if ($nutricionista!="") {
 $condicao = "";	
} else {
 $condicao = " AND me32_i_escola = $escola";
}

$oPost                 = db_utils::postMemory($_POST);
if ($oPost->sAction == 'PesquisaEscola') {
    
  $result = $clmer_cardapioescola->sql_record(
             $clmer_cardapioescola->sql_query("",
                                              "me32_i_codigo,ed18_c_nome",
                                              "ed18_c_nome",
                                              "me32_i_tipocardapio = {$oPost->cardapio}
                                               $condicao
                                              " 
                                             )
                                             );
  $aResult = db_utils::getColectionByRecord($result, false, false, true);
  $oJson   = new services_json();
  echo $oJson->encode($aResult);
  
}
?>