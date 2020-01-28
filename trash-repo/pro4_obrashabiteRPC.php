<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_obraslote_classe.php");
require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obraspropri_classe.php");
require_once("classes/db_obrashabite_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($HTTP_POST_VARS);

$cliptubase        = new cl_iptubase;
$clobraslote       = new cl_obraslote;
$clobrasconstr     = new cl_obrasconstr;
$clobrashabite     = new cl_obrashabite;
$clobraspropri     = new cl_obraspropri;
$objJSON           = new Services_JSON();

$aRetorno    = array();
$lEmpty      = false;
$sSaidaDebug = '';

if(isset($oPost->codObra) && trim($oPost->codObra)!=""){	
  
	$rsLote = $clobraslote->sql_record($clobraslote->sql_query_file($oPost->codObra,"ob05_idbql"));
	$iNumRowsLote = $clobraslote->numrows;
  	
	if($iNumRowsLote > 0){
		$sSaidaDebug .= 'acho registros no obraslote \n';  
   
        // adiciona uma flag na primeira posicao do array identificando que tem endereco de entrega
        array_unshift($aRetorno,"endereco");
	  
		$oLote = db_utils::fieldsMemory($rsLote,0);
		$rsMatric = $cliptubase->sql_record($cliptubase->sql_query_file(null,"j01_matric",null," j01_idbql = ".$oLote->ob05_idbql." limit 1"));	
    $iNumRowsMatric = $cliptubase->numrows;

		if($iNumRowsMatric > 0 or true ){
			
      if ( $iNumRowsMatric == 0 ) {
 
        $sSqlIptuEnder    = "select substr(fc_iptuender,001,40) as z01_ender,  	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,042,10) as z01_numero, 	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,053,20) as z01_compl,  	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,074,40) as z01_bairro, 	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,115,40) as z01_munic,  	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,156,02) as z01_uf, 		 	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,159,08) as z01_cep, 	 	          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,168,20) as z01_cxpostal,          ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,189,40) as z01_destinatario       ";
        $sSqlIptuEnder   .= "	 from ( select 'IDBQL SEM VINCULACAO COM MATRICULA'::varchar as fc_iptuender) as x ";

     } else {
			  $oMatric = db_utils::fieldsMemory($rsMatric,0);

        $sSqlIptuEnder    = "select substr(fc_iptuender,001,40) as z01_ender,  	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,042,10) as z01_numero, 	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,053,20) as z01_compl,  	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,074,40) as z01_bairro, 	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,115,40) as z01_munic,  	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,156,02) as z01_uf, 		 	          	";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,159,08) as z01_cep, 	 	      	    ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,168,20) as z01_cxpostal,            ";
        $sSqlIptuEnder   .= "       substr(fc_iptuender,189,40) as z01_destinatario         ";
        $sSqlIptuEnder   .= "	 from ( select fc_iptuender(".$oMatric->j01_matric.")) as x   ";
	
     }

	    $rsIptuEnder   = db_query($sSqlIptuEnder) or die($sSqlIptuEnder);
      $iNumRowsEnder = pg_num_rows($rsIptuEnder);

			if($iNumRowsEnder == 1 ){
				$oIptuEnder = db_utils::fieldsMemory($rsIptuEnder,0);	
				
				$sSqlIbge  = "  select ceplocalidades.*, 						        										                    ";
				$sSqlIbge .= "    		 (select db10_codibge 															                          ";
				$sSqlIbge .= "       		  from db_cepmunic 															                            ";
				$sSqlIbge .= "              inner join db_uf on db_cepmunic.db10_uf::char = db_uf.db12_uf::char     ";
				$sSqlIbge .= "    			 where trim(ceplocalidades.cp05_localidades) = trim(db_cepmunic.db10_munic) ";
				$sSqlIbge .= "        		 and ceplocalidades.cp05_sigla = db_uf.db12_uf) as cp05_codibge 			    ";
				$sSqlIbge .= "		from ceplocalidades 																                              ";
				$sSqlIbge .= "	 where cp05_localidades ilike '%".$oIptuEnder->z01_munic."%'							          ";
				$sSqlIbge .= "		 and cp05_sigla = upper('".$oIptuEnder->z01_uf."')									              ";
				
        $rsIbge    = db_query($sSqlIbge) or die($sSqlIbge);
				$iNumRowsIbge	 = pg_num_rows($rsIbge);			
				$oIbge = db_utils::fieldsMemory($rsIbge,0);
				
			  if($iNumRowsIbge == 1 ){
					$aRetorno[] = $oIbge;  // Array 0
				}else{
					$oIbge->cp05_codibge = "";
					$aRetorno[] = $oIbge;  // Array 0
        }
				
				$rsPropri = $clobraspropri->sql_record($clobraspropri->sql_query($oPost->codObra,"z01_nome"));
				$iNumRowsPropri = $clobraspropri->numrows; 
  			$oPropri = db_utils::fieldsMemory($rsPropri,0);		
				
				if($iNumRowsPropri == 1 ){
			  	$aRetorno[] = $oPropri;   // Array 1
				}else{
				  $oPropri->z01_nome = "";
					$aRetorno[] = $oPropri;   // Array 1
				}
			  
				$aRetorno[]  = $oIptuEnder; // Array 2
		    	
        $campoConstr  = " case 										          ";
				$campoConstr .= "		when ob08_ocupacao = 10000 			";
				$campoConstr .= "	  	then 'Residencial'       			";
				$campoConstr .= "		else                       			";
				$campoConstr .= "		case when ob08_ocupacao = 10001 ";
				$campoConstr .= "			 then 'Comercial'             ";
				$campoConstr .= "		else 'Mista'                    ";
				$campoConstr .= "		end                             ";
				$campoConstr .= " end as ob08_ocupacao              ";
				
				$rsConstr = $clobrasconstr->sql_record($clobrasconstr->sql_query_file($oPost->codConstr,$campoConstr));				
			  $oConstr  = db_utils::fieldsMemory($rsConstr,0); 	
			
        $aRetorno[] = $oConstr; // Array 3	
				
		    $sCamposHist  = " ob09_codhab,                 ";
        $sCamposHist .= " ob09_codconstr,              ";
        $sCamposHist .= " ob09_area,                   ";
        $sCamposHist .= " case                         ";
        $sCamposHist .= "   when ob09_parcial is true  ";
        $sCamposHist .= "     then 'Parcial '          ";
        $sCamposHist .= "   else 'Total'               ";
        $sCamposHist .= " end as ob09_parcial          ";

        $rsHabiteHist   = $clobrashabite->sql_record($clobrashabite->sql_query_file(null,$sCamposHist,null," ob09_codconstr = ".$oPost->codConstr));
        $iNumRowsHabite = $clobrashabite->numrows;

        for ($i = 0; $i < $iNumRowsHabite; $i++ ){
          
          $oHabiteHist = db_utils::fieldsMemory($rsHabiteHist,$i);
					$aRetorno[] = $oHabiteHist;           
        }
//				$aRetorno[4] = "2"; // 2 retorna endereço
				echo $objJSON->encode($aRetorno);
			}else{
			 $lEmpty = true;
			}
		}else{
		  $lEmpty = true;
		}

	}else{

   // adiciona uma flag na primeira posicao do array 
   // identificando que nao tem endereco de entrega
   array_unshift($aRetorno,"semendereco");

	 // se não tem registro na obraslote  .. é regular = não... tem que mostrar o nome...
	 $rsPropri = $clobraspropri->sql_record($clobraspropri->sql_query($oPost->codObra,"z01_nome"));
	 $iNumRowsPropri = $clobraspropri->numrows; 
   $oPropri = db_utils::fieldsMemory($rsPropri, 0);		
	 if($iNumRowsPropri == 1 ){
		 $aRetorno[] = $oPropri;   // Array 1
	 }else{
		 $oPropri->z01_nome = "";
		 $aRetorno[] = $oPropri;   // Array 1
	 }
	 echo $objJSON->encode($aRetorno);		
	}
}

//var_dump($aRetorno);
//echo $sSaidaDebug;  

if($lEmpty){
  echo $objJSON->encode("Vazio");	
}

?>