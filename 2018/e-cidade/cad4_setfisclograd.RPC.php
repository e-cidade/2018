<?php
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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

require_once("classes/db_face_classe.php");
require_once("classes/db_lote_classe.php");
require_once("classes/db_testada_classe.php");
require_once("classes/db_lotesetorfiscal_classe.php");
require_once("classes/db_ruasbairro_classe.php");




$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

$oFace            = new cl_face;
$oLote            = new cl_lote;
$oTestada         = new cl_testada;
$oLotesetorFiscal = new cl_lotesetorfiscal;
$oRuasBairro      = new cl_ruasbairro;

switch($oParam->exec) {

  case 'Face' :
  	
  	$aDadosFace  = array();
  	$aNovoSetor  = array();
  	$aNovaZona   = array();
  	$aBairros    = array();

  	$sCampos     = " j37_face,                \n";
  	$sCampos    .= " j13_codi,               \n";
  	$sCampos    .= " j90_codigo,              \n";
  	$sCampos    .= " j37_setor,               \n";
  	$sCampos    .= " j37_quadra,              \n";
  	$sCampos    .= " case                     \n";
  	$sCampos    .= "   when                   \n";
  	$sCampos    .= "     j13_descr is null    \n";
  	$sCampos    .= "   then 'Indefinido' else \n";
  	$sCampos    .= "     j13_descr            \n";
  	$sCampos    .= " end as j13_descr ,       \n";
  	$sCampos    .= "j90_descr                 \n";
  	
  	$sSqlFace    = "select distinct {$sCampos}                                                      \n";
  	$sSqlFace   .= "       from face                                                       \n";
  	$sSqlFace   .= " left join ruas           on  ruas.j14_codigo = face.j37_codigo        \n";
  	$sSqlFace   .= " left join setor          on  setor.j30_codi  = face.j37_setor         \n";
  	$sSqlFace   .= " left join testada         on  face.j37_face   = testada.j36_face      \n";
  	$sSqlFace   .= " left join lote          on  testada.j36_idbql = lote.j34_idbql        \n";
  	$sSqlFace   .= " left join bairro          on  lote.j34_bairro = bairro.j13_codi       \n";
  	$sSqlFace   .= " left join lotesetorfiscal on j91_idbql        = j36_idbql             \n";
  	$sSqlFace   .= " left join setorfiscal     on j90_codigo       = j91_codigo            \n";
  	$sSqlFace   .= "where j37_codigo = {$oParam->iCodigo}                                  \n";
  	$rsFace      = $oFace->sql_record($sSqlFace);
  	$aListaFace  = db_utils::getColectionByRecord($rsFace, false, false, true);
  	
  	//echo $sSqlFace; die();
  	/*
  	 * Novos setores fiscais
  	 */
    $sSqlNovoSetorFiscal  = " select 0 as j90_codigo, 'Nenhum...' as j90_descr   \n";
    $sSqlNovoSetorFiscal .= "  union all                                         \n";
    $sSqlNovoSetorFiscal .= " select j90_codigo,j90_descr from setorfiscal       \n";  
    $rsNovoSetorFiscal    = db_query($sSqlNovoSetorFiscal);  
    $aListaNovoSetor      = db_utils::getColectionByRecord($rsNovoSetorFiscal, false, false, false);
    
    /*
     * Novas Zonas Para Lotes de Face
     */
    
    $sSqlNovaZona  = "select 0 as j50_zona, 'Nenhum...' as j50_descr ";
    $sSqlNovaZona .= " union all ";
    $sSqlNovaZona .= "select j50_zona,j50_descr from zonas ";
    $rsNovaZona    = db_query($sSqlNovaZona);
    $aNovaZona     = db_utils::getColectionByRecord($rsNovaZona, false, false, false);
    
    /*
     * Lista de bairros
     * 
     */
    $sSqlBairros  = "Select j13_codi,   \n";
    $sSqlBairros .= "       j13_descr    \n";
    $sSqlBairros .= "  from bairro order by j13_descr   \n\n";
    
    $rsBairros    = db_query($sSqlBairros);
    $aBairros     = db_utils::getColectionByRecord($rsBairros, false, false, false);
    
    foreach ($aListaFace as $oIndiceFace => $oValorFace) {
      
      $oDados               = new stdClass(); 
      
      $oDados->face         = $oValorFace->j37_face;
      $oDados->iBairro      = $oValorFace->j13_codi;
      $oDados->iSetor       = $oValorFace->j90_codigo;
      $oDados->setor        = $oValorFace->j37_setor;
      $oDados->quadra       = $oValorFace->j37_quadra;
      $oDados->setor_fiscal = $oValorFace->j90_descr;
      $oDados->novo_setor   = $aListaNovoSetor;
      $oDados->nova_zona    = $aNovaZona;
      $oDados->bairro       = $oValorFace->j13_descr;
      $oDados->novo_bairro  = $aBairros;
       
      $aDadosFace[]       = $oDados;
    }
    $oRetorno->dados      = $aDadosFace;  

    /*
  	echo "<pre>";
  	print_r($oRetorno);
  	echo "</pre>";
    die();
    */     

  break; 

  
  case 'Atualizar' :

  	
  	$aDadosAtualizar = array();
    $aDadosAtualizar = $oParam->aValores;
    
    /*
     * j91 -> lotesetorfiscal
     * j37 -> face ligada com rua (j37_codigo) e j37_face = face 
     * 
     * 
     * zona         = update lote set j34_zona = {valor_da_zona} where j34_idbql in (select j36_idbql from testada where j36_face = {valor_da_face});
     * setor fiscal = update lotesetorfiscal set j91_codigo = {novo_setor_Fiscal} where j91_idbql = (select j36_idbql from testada where j36_face = {valor_da_face});
     * bairro       = update lote set j34_bairro = 8002 where j34_idbql in (select j36_idbql from testada where j36_face = 322);
     */
    
    try {
    	
    	// laço principal para o total de registros selecionados
	    foreach ($aDadosAtualizar as $iIndiceAtualizar => $oValorAtualizar) {
	    	
	    	
	    	// seguindo logica antiga , consultamos os registros para alterar o bairro e a zona
	    	
	    	$sSqlTestada = $oTestada->sql_query(null,null,"distinct j36_idbql",null,"j36_face in ({$oValorAtualizar->face})");
	    	$rsTestada = $oTestada->sql_record($sSqlTestada);
	    	$aTestada  = db_utils::getColectionByRecord($rsTestada, false, false, false);

	    		//atualiza o bairro
	    	if ($oValorAtualizar->n_bairro != null || $oValorAtualizar->n_bairro != "" ) {
	    		
	    		foreach ($aTestada as $iIndiceTestada => $oValorTestada){
	    			
	    			$oLote->j34_bairro = $oValorAtualizar->n_bairro;
	    			$oLote->j34_idbql  = $oValorTestada->j36_idbql;
	    			$oLote->alterar($oLote->j34_idbql);
	    			if($oLote->erro_status == 0){
	    				
	    				throw new Exception($oLote->erro_msg);
	    			}
	    		}
	    	}
	    	
	    	// Atualizamos a zona
	    	if ($oValorAtualizar->n_zona != null &&  $oValorAtualizar->n_zona != 0 ) {
	    		
	    	 foreach ($aTestada as $iIndiceTestada => $oValorTestada){
            
            $oLote->j34_zona = $oValorAtualizar->n_zona;
            $oLote->j34_idbql  = $oValorTestada->j36_idbql;
            $oLote->alterar($oLote->j34_idbql);
            if($oLote->erro_status == 0){
              
              throw new Exception($oLote->erro_msg);
            }
          }
	    	}
	    	
	    	// atualizamos o lotesetorfiscal
	    	if ($oValorAtualizar->n_fiscal != null &&  $oValorAtualizar->n_fiscal != 0 ) {
	    		
	    		//echo "lotesetor fiscal = " . $oValorAtualizar->n_fiscal;
	    		//die();
	    		
	    	  foreach ($aTestada as $iIndiceTestada => $oValorTestada){
	    	  	
	    	      $result_exist = $oLotesetorFiscal->sql_record($oLotesetorFiscal->sql_query_file(null,"*",null,"j91_idbql = $oValorTestada->j36_idbql"));
			        if ($oLotesetorFiscal->numrows>0){
			          $oLotesetorFiscal->excluir(null,"j91_idbql = $oValorTestada->j36_idbql");
			          if($oLotesetorFiscal->erro_status == 0){
			          	throw new Exception($oLotesetorFiscal->erro_msg);
			          }
			        }	    	  	  
	    	      $oLotesetorFiscal->j91_idbql  = $oValorTestada->j36_idbql;
			        $oLotesetorFiscal->j91_codigo = $oValorAtualizar->n_fiscal;
			        $oLotesetorFiscal->incluir($oValorTestada->j36_idbql);
			        if($oLotesetorFiscal->erro_status == 0){
			        
			        	throw new Exception($oLotesetorFiscal->erro_msg);
			        }            
          }
	    	}
	    	
	    	
	    }
    
      db_fim_transacao(false);
      
    }catch (ErrorException $erro) {
    	
        db_fim_transacao(true);
        $oRetorno->status      = 0;
        $oRetorno->message     = urlencode($eException->getMessage());    	
    }
    
    
  break;

  
}
  
echo $oJson->encode($oRetorno);   

?>