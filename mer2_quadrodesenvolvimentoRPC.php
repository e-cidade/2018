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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_periodocalendario_classe.php");
include("dbforms/db_funcoes.php");
$clperiodocalendario = new cl_periodocalendario;
$escola                = db_getsession("DB_coddepto");
$hoje                  = date("Y-m-d",db_getsession("DB_datausu"));
$oPost                 = db_utils::postMemory($_POST);

if ($oPost->sAction == 'PesquisaPeriodo') {
	
  $result1 = $clperiodocalendario->sql_record(
               $clperiodocalendario->sql_query("",
                                               "ed09_i_codigo,ed09_c_descr",
                                               "ed09_i_sequencia",
                                               "ed53_i_calendario = {$oPost->calendario}" 
                                              )
                                              );
  	
  $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
  $oJson    = new services_json();
  echo $oJson->encode($aResult1);
  
}

?>