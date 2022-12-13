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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_mer_nutricionista_classe.php");
require_once("classes/db_mer_nutricionistaescola_classe.php");
require_once("classes/db_escola_classe.php");
require_once("libs/JSON.php");

$clmer_nutricionista        = new cl_mer_nutricionista();
$clmer_nutricionistaescola  = new cl_mer_nutricionistaescola();
$clescola                   = new cl_escola();
$oJson                      = new services_json();
$oRetorno                   = new stdClass(); 
$oParam                     = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->erro             = 0;
$oRetorno->aItensDptos      = array();
$oRetorno->aItensDptosSel   = array();
$dtDataUsu                  = date('Y-m-d',db_getsession("DB_datausu"));
$iEscola                    = db_getsession("DB_coddepto");

switch ($oParam->exec) {

  case "listarEscola":
  	  	
  	$sCampos     = " escola.ed18_i_codigo,escola.ed18_c_nome , ";
    $sCampos    .= " (select array(select distinct z01_nome as z01_nome ";
    $sCampos    .= "               from mer_nutricionistaescola ";    
    $sCampos    .= "                inner join mer_nutricionista on mer_nutricionista.me02_i_codigo = mer_nutricionistaescola.me31_i_nutricionista ";
    $sCampos    .= "                inner join cgm on cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
    $sCampos    .= "               where me02_c_nutriativo = '1' and me31_i_escola = ed18_i_codigo order by z01_nome ))  as nutricionistas";    
  	$sWhere      = " (limite is null or limite >= '{$dtDataUsu}')  "; 

  	
   $sSqlEscola    = $clescola->sql_query_deptousuario(null,$sCampos,"ed18_c_nome",$sWhere,$oParam->codnutricionista); 
 
  	$rsEscola     = $clescola->sql_record($sSqlEscola);

  	$sCampos        = "distinct escola.ed18_i_codigo, escola.ed18_c_nome,mer_nutricionistaescola.me31_i_ordem ";
  	$sWhere        = " mer_nutricionistaescola.me31_i_nutricionista = {$oParam->codnutricionista}  ";
  	
    $sSqlDeptoSel   =  $clmer_nutricionistaescola->sql_query(null,$sCampos,"me31_i_ordem",$sWhere); 

    $rsSqlDeptoSel  = $clmer_nutricionistaescola->sql_record($sSqlDeptoSel);  	
  	
  	$oRetorno->aItensDptos    = db_utils::getColectionByRecord($rsEscola,false,false,true);
  	$oRetorno->aItensDptosSel = db_utils::getColectionByRecord($rsSqlDeptoSel,false,false,true); 	
  	
    break; 
    
  case "atualizarEscola":

	  $sqlerro = false;
	  
	  db_inicio_transacao();
	  
	 if ( $sqlerro == false ) {
	
	  	$sCampos  = " distinct escola.ed18_i_codigo, mer_nutricionistaescola.me31_i_ordem,    ";
	  	$sCampos .= " mer_nutricionistaescola.me31_i_codigo ";
	  	$sWhere   = "  mer_nutricionistaescola.me31_i_nutricionista = {$oParam->codnutricionista}     ";
	  	
	    $sSqlDeptoInstitUsu  =  $clmer_nutricionistaescola->sql_query(null,$sCampos,null,$sWhere);

	    $rsDeptoInstitUsu    = $clmer_nutricionistaescola->sql_record($sSqlDeptoInstitUsu);

	    if ($clmer_nutricionistaescola->numrows > 0) {                                         
	      
	      $iNumRowsDepto = $clmer_nutricionistaescola->numrows;

	      for ($i = 0; $i < $iNumRowsDepto; $i++) {
	      	
	        $oNutricionistaDeptoInstit = db_utils::fieldsMemory($rsDeptoInstitUsu, $i);	   	             
	        $clmer_nutricionistaescola->me31_i_escola = $oNutricionistaDeptoInstit->ed18_i_codigo;
            $clmer_nutricionistaescola->me31_i_nutricionista = $oParam->codnutricionista;         
            $clmer_nutricionistaescola->me31_i_codigo = $oNutricionistaDeptoInstit->me31_i_codigo;    
            $clmer_nutricionistaescola->excluir($oNutricionistaDeptoInstit->me31_i_codigo);	
            
            $erro_msg = $clmer_nutricionistaescola->erro_msg;   
                            
	        if ( $clmer_nutricionistaescola->erro_status == 0 ) {
	        	
	          $sqlerro        = true;
	          $oRetorno->erro = 1;
	          
	        }
	      }
	    }
	  }
     
    foreach ($oParam->aDptoSel as $oDptoSel) {
            
      if ( $sqlerro == false ) {
      
        $clmer_nutricionistaescola->me31_i_escola = $oDptoSel->iDptoSel;
        $clmer_nutricionistaescola->me31_i_nutricionista = $oParam->codnutricionista;
      	$clmer_nutricionistaescola->me31_i_ordem = $oDptoSel->iOrdem;      	     	
        $clmer_nutricionistaescola->incluir(null);

        if ( $clmer_nutricionistaescola->erro_status == 0 ) {
   
          $sqlerro        = true;
          $oRetorno->erro = 1;
          break;
          
        }  
      }    	
    } 
	  db_fim_transacao($sqlerro);
    break;    
}

echo $oJson->encode($oRetorno);
?>