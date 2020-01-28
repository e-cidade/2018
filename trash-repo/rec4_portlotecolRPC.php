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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("libs/db_utils.php");
include("classes/db_selecao_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_portaria_classe.php");

$oPost = db_utils::postMemory($_POST);

$clselecao   = new cl_selecao;
$clrhpessoal = new cl_rhpessoal;
$clportaria  = new cl_portaria;
$oJson       = new services_json();

  if ( $oPost->tipo == "selecao" ) {


  	$rsConsultaSelecao = $clselecao->sql_record($clselecao->sql_query_file($oPost->valor));
  	
  	$oSelecao = db_utils::fieldsMemory($rsConsultaSelecao,0);
  	
    $sSqlConsultaFuncionarios  =  " select rh01_regist, 													    	   ";
    $sSqlConsultaFuncionarios .=  "  	   z01_nome      													  		   ";
    $sSqlConsultaFuncionarios .=  "   from rhpessoal         												           ";   
    $sSqlConsultaFuncionarios .=  "   	   inner join cgm            on rh01_numcgm 	  = z01_numcgm  	           "; 
    $sSqlConsultaFuncionarios .=  "        left  join rhpessoalmov   on rh02_regist 	  = rh01_regist       		   ";
    $sSqlConsultaFuncionarios .=  "                                 and rh02_anousu 	  = ".db_anofolha()   		    ;
    $sSqlConsultaFuncionarios .=  "	            	   		 	    and rh02_mesusu 	  = ".db_mesfolha() 		    ;    
    $sSqlConsultaFuncionarios .=  "                                 and rh02_instit       = ".db_getsession('DB_instit');
    $sSqlConsultaFuncionarios .=  "		   left join rhpesrescisao   on rh05_seqpes		  = rh02_seqpes				   ";
    $sSqlConsultaFuncionarios .=  " 	   inner join rhfuncao       on rh37_funcao       = rh01_funcao      		   "; 
    $sSqlConsultaFuncionarios .=  "                                 and rh37_instit 	  = rh02_instit       		   ";
    $sSqlConsultaFuncionarios .=  "        left  join rhregime       on rh30_codreg       = rh02_codreg       		   ";
    $sSqlConsultaFuncionarios .=  "                                 and rh30_instit       = rh02_instit       		   ";
    $sSqlConsultaFuncionarios .=  "        left  join rhpeslocaltrab on rh56_seqpes       = rh02_seqpes       		   ";	
    $sSqlConsultaFuncionarios .=  "                            	 	and rh56_princ  	  = 't'    	          	  	   ";
    $sSqlConsultaFuncionarios .=  "    	   left  join rhlocaltrab    on rh55_codigo       = rh56_localtrab    		   ";
    $sSqlConsultaFuncionarios .=  "        left  join rhpespadrao    on rh03_seqpes       = rh02_seqpes  	  		   ";
    $sSqlConsultaFuncionarios .=  "        left  join padroes        on r02_anousu        = rh02_anousu       		   ";
    $sSqlConsultaFuncionarios .=  "                             	 and r02_mesusu       = rh02_mesusu       		   ";
    $sSqlConsultaFuncionarios .=  "		   					         and r02_regime       = rh30_regime       		   ";
    $sSqlConsultaFuncionarios .=  "		   					     	 and trim(r02_codigo) = trim(rh03_padrao) 		   ";
    $sSqlConsultaFuncionarios .=  "		   					     	 and r02_instit       = rh02_instit  	  	       ";
    
    if ( isset($oSelecao->r44_where) && trim($oSelecao->r44_where) != "" ){
      $sSqlConsultaFuncionarios .=  "  where {$oSelecao->r44_where}											  		   ";
    }
    
    $rsConsultaFuncionarios = @pg_query($sSqlConsultaFuncionarios);
    
    if ( $rsConsultaFuncionarios == false) {
      echo $oJson->encode(array("iStatus"=>2, "sMensagem"=>urlencode("Funcionários não Encontrados")));
      exit;
    } else {  
      $iNroLinhas = pg_num_rows($rsConsultaFuncionarios);
    }  
    
  } else if ($oPost->tipo == "funcionario") {
  	
  	
  	$rsConsultaFuncionarios = $clrhpessoal->sql_record($clrhpessoal->sql_query($oPost->valor,"rh01_regist,z01_nome",null,""));
  	$iNroLinhas 		    = $clrhpessoal->numrows;
  	
  	
  } else if ($oPost->tipo == "portaria") {
  	
  	$rsConsultaFuncionarios = $clportaria->sql_record($clportaria->sql_query_asse_func($oPost->valor,"rh01_regist,z01_nome",null,""));
  	$iNroLinhas 		    = $clportaria->numrows;
  	
  }
  
  if ( $iNroLinhas > 0 ) {
  	
    $aRetornaCampos = db_utils::getColectionByRecord($rsConsultaFuncionarios,false,false,true);
  
  } else {
  	
    $sMensagem 	    = "Funcionários não Encontrados";
    $iStatus   	    = 2;
    $aRetornaCampos = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
    
  } 
    
  echo $oJson->encode($aRetornaCampos);
  
?>