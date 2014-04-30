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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");

db_postmemory($HTTP_GET_VARS);

$oGet = db_utils::postMemory($_GET);

$sql_prev = "select trim(r33_nome) as descr_prev 
               from inssirf 
              where r33_anousu = $anofolha 
                and r33_mesusu = $mesfolha 
                and r33_instit = ".db_getsession('DB_instit')." 
                and r33_codtab = $prev + 2 limit 1";
$res_prev = pg_query($sql_prev);

db_fieldsmemory($res_prev, 0);

if(isset($listaSel)){
	$listaSel = explode(",", $listaSel);
	$listaSeleciona = implode("','",$listaSel);
}

switch ($ordem){
	case "a":
		if($tipo_res == "m") {
			$orderby   = "z01_nome, anoinicial, mesinicial";             
			$cab_ordem = "Ordenado por Nome"; 
		}else if ($tipo_res == "l"){ 
			$orderby   = "r70_descr, z01_nome, anoinicial, mesinicial";             
			$cab_ordem = "Ordenado por Descrição de Lotação"; 
		}else if ($tipo_res == "t"){
			$orderby   = "rh55_descr, z01_nome, anoinicial, mesinicial";                  
			$cab_ordem = "Ordenado por Descrição de Local de trabalho";
		}else{
		 $orderby   = "z01_nome";                  
		 $cab_ordem = "";
		}
	break;
	
	case "n":
		if($tipo_res == "m") {
			$orderby   = "r14_regist, anoinicial, mesinicial";             
			$cab_ordem = "Ordenado por Matrícula"; 
		}else if($tipo_res == "l"){
			$orderby   = "r70_codigo, z01_nome, anoinicial, mesinicial";             
			$cab_ordem = "Ordenado por Código Lotação"; 
		}else if($tipo_res == "t"){
			$orderby   = "rh55_estrut, z01_nome, anoinicial, mesinicial";                  
			$cab_ordem = "Ordenado por Código de Local de trabalho";
		}else{
			$orderby   = "r14_regist, anoinicial, mesinicial";                  
			$cab_ordem = "";
		}
	break;
}


$aTabelas = array();

$oTabela = new stdClass();
$oTabela->sTabela = 'gerfsal'; 
$oTabela->sSigla  = 'r14';
$aTabelas[] = $oTabela;

$oTabela = new stdClass();
$oTabela->sTabela = 'gerfres'; 
$oTabela->sSigla  = 'r20';
$aTabelas[] = $oTabela;  

$oTabela = new stdClass();
$oTabela->sTabela = 'gerfs13'; 
$oTabela->sSigla  = 'r35';
$aTabelas[] = $oTabela;

$oTabela = new stdClass();
$oTabela->sTabela = 'gerfcom'; 
$oTabela->sSigla  = 'r48';
$aTabelas[] = $oTabela;

$sSql  = "";  
	
foreach ( $aTabelas as $iInd => $oTabela ) {
			
	if ( $oGet->sTipoEmissao == 'p' ) {
		$sWherePessoalMov = " on rh02_anousu = {$oTabela->sSigla}_anousu and rh02_mesusu = {$oTabela->sSigla}_mesusu";
	} else {
		$sWherePessoalMov = " on rh02_anousu = {$oGet->anofolha} and rh02_mesusu = {$oGet->mesfolha}";
	}
	
	if ( $iInd != 0 ) {
		$sSql .= " union all ";		
	}
		
	$sSql .= "   select {$oTabela->sSigla}_anousu,                                                                    ";  
    $sSql .= "          {$oTabela->sSigla}_mesusu,                                                                    ";
    $sSql .= "          {$oTabela->sSigla}_regist,                                                                    ";
    $sSql .= "          rh01_admiss,                                                                                  ";
    $sSql .= "          r33_ppatro,                                                                                   ";
    $sSql .= "          z01_nome,                                                                                     ";
    $sSql .= "          z01_ender,                                                                                    ";
    $sSql .= "          z01_compl,                                                                                    ";
    $sSql .= "          z01_numero,                                                                                   ";
    $sSql .= "          z01_cep,                                                                                      ";
    $sSql .= "          z01_uf,                                                                                       ";
    $sSql .= "          z01_munic,                                                                                    ";
    $sSql .= "          rh37_descr,                                                                                   ";
    $sSql .= "          {$oTabela->sSigla}_instit ,                                                                   ";
    $sSql .= "          r70_estrut,                                                                                   ";
    $sSql .= "          r70_descr,                                                                                    ";
    $sSql .= "          rh55_estrut,                                                                                  ";
    $sSql .= "          rh55_descr,                                                                                   ";
    $sSql .= "          case                                                                                          ";
    $sSql .= "            when {$oTabela->sSigla}_rubric = 'R992'                                                     ";
    $sSql .= "              then {$oTabela->sSigla}_valor                                                             ";
    $sSql .= "            else 0                                                                                      ";
    $sSql .= "          end as base,                                                                                  ";
    $sSql .= "          case                                                                                          ";
    $sSql .= "            when {$oTabela->sSigla}_rubric between 'R901' and 'R912'                                    ";
    $sSql .= "              then {$oTabela->sSigla}_valor                                                             ";
    $sSql .= "            else 0                                                                                      ";
    $sSql .= "          end as desconto                                                                               ";
    $sSql .= "     from {$oTabela->sTabela} as {$oTabela->sSigla}                                                     ";
    $sSql .= "		  inner join rhpessoalmov {$sWherePessoalMov}                                                     ";
    $sSql .= "		                            and rh02_regist = {$oTabela->sSigla}_regist                           ";
    $sSql .= "                                  and rh02_instit = {$oTabela->sSigla}_instit                           ";
    $sSql .= "		  inner join rhpessoal       on rh01_regist = rh02_regist                                         ";
    $sSql .= "		  inner join cgm             on z01_numcgm  = rh01_numcgm                                         ";
    $sSql .= "		  inner join rhfuncao        on rh37_funcao = rh01_funcao                                         ";
    $sSql .= "                                  and rh37_instit = rh02_instit                                         ";
    $sSql .= "		  inner join rhlota          on r70_codigo  = rh02_lota                                           ";
    $sSql .= "		                            and r70_instit  = rh02_instit                                         ";
    $sSql .= "		   left join rhpeslocaltrab  on rh56_seqpes = rh02_seqpes                                         ";
    $sSql .= "							        and rh56_princ  = 't'                                                 ";
    $sSql .= "	       left join rhlocaltrab     on rh55_codigo = rh56_localtrab                                      ";
    $sSql .= "								    and rh55_instit = rh02_instit                                         ";
    $sSql .= "		  inner join (select distinct                                                                     ";
    $sSql .= "		                     r33_anousu,                                                                  ";
    $sSql .= "			                 r33_mesusu,                                                                  ";
    $sSql .= "			 				 r33_codtab,                                                                  ";
    $sSql .= "			 				 r33_ppatro,                                                                  ";
    $sSql .= "			 				 r33_instit                                                                   ";
    $sSql .= "					   from inssirf ) as inssirf on r33_anousu = {$oTabela->sSigla}_anousu                ";
    $sSql .= "											    and r33_mesusu = {$oTabela->sSigla}_mesusu                ";
    $sSql .= "											    and r33_codtab = rh02_tbprev+2                            ";
    $sSql .= "											    and r33_instit = rh02_instit                              ";
    $sSql .= "  where rh02_tbprev = $prev                                                                             ";
    $sSql .= " and rh02_instit = ".db_getsession('DB_instit');                                                       
    $sSql .= " and (    {$oTabela->sSigla}_rubric = 'R992'                                                            ";
    $sSql .= "			 or {$oTabela->sSigla}_rubric between 'R901' and 'R912' )                                     ";
        
	if ($anoini == $anofin) {   
	   $sSql .= " and {$oTabela->sSigla}_anousu between $anoini and $anofin                                           ";
	   $sSql .= " and {$oTabela->sSigla}_mesusu between $mesini and $mesfin                                           ";
	} else {  
	   $sSql .= " and case                                                                                            ";
	   $sSql .= "       when {$oTabela->sSigla}_anousu = $anoini                                                      ";
	   $sSql .= "         then {$oTabela->sSigla}_mesusu >= $mesini                                                   ";
	   $sSql .= "		when {$oTabela->sSigla}_anousu = $anofin                                                      ";
	   $sSql .= "         then {$oTabela->sSigla}_mesusu <= $mesfin                                                   ";
	   $sSql .= "		when {$oTabela->sSigla}_anousu > $anoini and {$oTabela->sSigla}_anousu < $anofin              ";
	   $sSql .= "         then {$oTabela->sSigla}_mesusu > 0                                                          ";
	   $sSql .= "     end                                                                                             ";
	}                                                                                                                  

	 switch ($tipo_res){
		 case "m":
			 if($tipo_fil == "i"){
				 $sSql .= " and {$oTabela->sSigla}_regist between $campini and $campfin ";
			 }else if($tipo_fil == "s"){
				 $sSql .= " and {$oTabela->sSigla}_regist in ('$listaSeleciona' ) ";
			 }
		 break;
		 case "l":
			 if($tipo_fil == "i"){                          
				 $sSql .= " and r70_codigo between $campini and $campfin "; 
			 }else if($tipo_fil == "s"){
				 $sSql .= " and r70_codigo in ( '$listaSeleciona' ) ";
			 }  
		 break;
		 case "t":
			 if($tipo_fil == "i"){                          
				 $sSql .= " and rh55_estrut between $campini and $campfin "; 
			 }else if($tipo_fil == "s"){
				 $sSql .= " and rh55_estrut in ( '$listaSeleciona' ) ";
			 }  
		 break;
	 
	 }
}
$subSelect = " ({$sSql}) as x ";

# Include AgataAPI class
include_once 'dbagata/classes/core/AgataAPI.class';

ini_set("error_reporting","E_ALL & ~NOTICE");

# Instantiate AgataAPI
$clagata = new cl_dbagata("pessoal/pes2_relextratorpps002.agt");
$api = $clagata->api;

$api->setParameter('$head1', "EXTRATO À PREVIDÊNCIA");
$api->setParameter('$head2', "");
$api->setParameter('$head3', "$descr_prev");
$api->setParameter('$head3', "");
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");

//Modifica Order By
$xml = $api->getReport();
$xml["Report"]["DataSet"]["Query"]["OrderBy"] = "$orderby";
$xml["Report"]["DataSet"]["Query"]["From"] = "$subSelect";
$xml["Report"]["Merge"]["Details"]["Detail1"]["DataSet"]["Query"]["From"] = "$subSelect";
$api->setReport($xml);

$ok = $api->generateDocument();

if (!$ok)
{
    echo $api->getError();
}
else
{ 
    db_redireciona($clagata->arquivo);
}
?>