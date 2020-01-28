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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once('dbagata/classes/core/AgataAPI.class');
require_once("model/documentoTemplate.model.php");

ini_set("error_reporting","E_ALL & ~NOTICE");

$oGet = db_utils::postMemory($_GET);

$and              = "and";
$where            = " t55_codbem is null ";
$where_t52_instit = " t52_instit = ".db_getsession("DB_instit");
$where           .= $and.$where_t52_instit;

$sAnoUsu          = db_getsession("DB_anousu");

$sCamposFrom  = " orgao,   ";
$sCamposFrom .= " unidade, ";
$sCamposFrom .= " depto,   ";
$sCamposFrom .= " divisao, ";


$sSqlGroupBy  = " orgao,   ";
$sSqlGroupBy .= " unidade, ";
$sSqlGroupBy .= " depto,   ";
$sSqlGroupBy .= " divisao  ";

if (isset($depto) && $depto  != ""){
    
  $where     .= " $and coddepto = $depto";
	
  $sCamposFrom  = " orgao,          ";
	$sCamposFrom .= " unidade,        ";
	$sCamposFrom .= " depto,          ";
	$sCamposFrom .= " '' as divisao,  ";
	
  $sSqlGroupBy  = " orgao,   ";
	$sSqlGroupBy .= " unidade, ";
	$sSqlGroupBy .= " depto    ";
	  
} 

if (isset($div) && $div  != ""){
    
  $where .= " $and t33_divisao = $div";
  $sCamposFrom  = " orgao,   ";
	$sCamposFrom .= " unidade, ";
	$sCamposFrom .= " depto,   ";
	$sCamposFrom .= " divisao, ";
	
	$sSqlGroupBy  = " orgao,   ";
	$sSqlGroupBy .= " unidade, ";
	$sSqlGroupBy .= " depto,   ";
	$sSqlGroupBy .= " divisao  ";
  

}

if (isset($filtro_bens) && $filtro_bens != ""){
     if ($filtro_bens == "I"){
          $where .= " $and t52_bem between $t52_bem_ini and $t52_bem_fim ";
     }
     if ($filtro_bens == "S"){
          $where .= " $and t52_bem in ($listabens) ";
     }
}


if (isset($unidades) && $unidades != "" && isset($orgaos) && $orgaos != ""){
     
  $where .= " $and o41_unidade in ".$unidades." and o41_anousu = ".$sAnoUsu." and o41_orgao in ".$orgaos;
  $sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " unidade,       ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
  
  $sSqlGroupBy  = " orgao,  ";
  $sSqlGroupBy .= " unidade ";

  

} else if (isset($unidades) && $unidades != ""){
     
  $where .= " $and o41_unidade in ".$unidades." and o41_anousu = ".$sAnoUsu;
  $sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " unidade,       ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
  
  $sSqlGroupBy  = " orgao,  ";
  $sSqlGroupBy .= " unidade ";

  
} else if (isset($orgaos) && $orgaos != "") {
  
  $where .= " $and o40_orgao in ".$orgaos." and o40_anousu = ".$sAnoUsu;
  $sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " '' as unidade, ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
  
  $sSqlGroupBy  = " orgao  ";
  
}

if (isset($departamentos) && $departamentos != "") {
  
  $where .= " $and coddepto in ".$departamentos;
  $sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " unidade,       ";
  $sCamposFrom .= " depto,         ";
  $sCamposFrom .= " '' as divisao, ";
  
  $sSqlGroupBy  = " orgao,   ";
  $sSqlGroupBy .= " unidade, ";
  $sSqlGroupBy .= " depto    ";

}

if (isset($divisoes) && $divisoes != "") {
  
  $where .= " $and t30_codigo in ".$divisoes;

  $sCamposFrom  = " orgao,   ";  
  $sCamposFrom .= " unidade, ";
  $sCamposFrom .= " depto,   ";
  $sCamposFrom .= " divisao, ";

  $sSqlGroupBy  = " orgao,   ";
  $sSqlGroupBy .= " unidade, ";
  $sSqlGroupBy .= " depto,   ";
  $sSqlGroupBy .= " divisao  ";
  
}

if(isset($departamentos) && trim($departamentos) == "" && isset($divisoes) && trim($divisoes) == "" && 
   isset($orgaos) && trim($orgaos) == "" && isset($unidades) && trim($unidades) == "" ) {

   	$sSqlGroupBy = "";
   	$sCamposFrom = "";
}

if (isset($dtini) && $dtini != "" && isset($dtfim) && $dtfim != "") {
  
  $where .= " $and t52_dtaqu between '".$dtini."' and '".$dtfim."'";
} else if (isset($dtini) && $dtini != "" ) {
  $where .= " $and t52_dtaqu >= '".$dtini."'";
} else if (isset($dtfim) && $dtfim != "") {
  $where .= " $and t52_dtaqu <= '".$dtfim."'";
}

$sDateTime = date("dmYHi");

$clagata = new cl_dbagata("patrimonio/pat2_bensata001.agt");

$api = $clagata->api;

$sCaminhoSalvoSxw = "tmp/AtaDeInventarioDeBens_$sDateTime.sxw";

if ( isset($cboAgrupar) && trim($cboAgrupar) == 1 && isset($oGet->orgaos) && trim($oGet->orgaos) != "" && 
	     isset($oGet->unidades) && trim($oGet->unidades) != "" && isset($oGet->departamentos) && trim($oGet->departamentos) != "" &&
	     isset($oGet->divisoes) && trim($oGet->divisoes) != "") {

	$sCamposFrom  = " '' as orgao,   ";  
  $sCamposFrom .= " '' as unidade, ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
   
	$sSqlGroupBy  = "";
	
} else if (isset($cboAgrupar) && trim($cboAgrupar) == 2 || (isset($oGet->orgaos) && trim($oGet->orgaos) != "")){
	
	$sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " '' as unidade, ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
   
	
} else if (isset($cboAgrupar) && trim($cboAgrupar) == 3 || (isset($oGet->unidades) && trim($oGet->unidades) != "")){
  
  $sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " unidade,       ";
  $sCamposFrom .= " '' as depto,   ";
  $sCamposFrom .= " '' as divisao, ";
   
  
  $sSqlGroupBy  = " orgao,         ";
  $sSqlGroupBy .= " unidade        ";
	
} else if (isset($cboAgrupar) && trim($cboAgrupar) == 4 || (isset($oGet->departamentos) && trim($oGet->departamentos) != "")){
  
	$sCamposFrom  = " orgao,         ";  
  $sCamposFrom .= " unidade,       ";
  $sCamposFrom .= " depto,         ";
  $sCamposFrom .= " '' as divisao, ";
   
  $sSqlGroupBy  = " orgao,         ";
  $sSqlGroupBy .= " unidade,       ";
  $sSqlGroupBy .= " depto          ";
	
} else if (isset($cboAgrupar) && trim($cboAgrupar) == 5 || (isset($oGet->divisoes) && trim($oGet->divisoes) != "")){

	 $sCamposFrom  = " orgao,        ";  
	 $sCamposFrom .= " unidade,      ";
	 $sCamposFrom .= " depto,        ";
	 $sCamposFrom .= " divisao,      ";
	  
	 $sSqlGroupBy  = " orgao,        ";
	 $sSqlGroupBy .= " unidade,      ";
	 $sSqlGroupBy .= " depto,        ";
	 $sSqlGroupBy .= " divisao       ";
}
/*
$sCamposFrom  = " orgao,                                      ";
$sCamposFrom .= " unidade,                                    ";
$sCamposFrom .= " '' as depto,                                ";
$sCamposFrom .= " '' as divisao,                              ";
*/

if (trim($sSqlGroupBy) == "" && trim($sCamposFrom) != "") {
  $sSqlGroupBy = "1,2,3,4"; 
}

$sCamposFrom .= " sum(vlrmovelproprio)   as vlrmovelproprio,  ";
$sCamposFrom .= " sum(vlrmovelconvenio)  as vlrmovelconvenio, ";
$sCamposFrom .= " sum(vlrimovelproprio)  as vlrimovelproprio, ";
$sCamposFrom .= " sum(vlrimovelconvenio) as vlrimovelconvenio,";
$sCamposFrom .= " sum(vlrimovelconvenio+vlrmovelproprio+vlrmovelconvenio+vlrimovelproprio) as vlrtotal ";

$sWhere = " where ".$where;

$sSqlFrom     = " ( select t52_bem,
						               t52_valaqu,
						               t64_bemtipos,
						               case 
						                 when t09_bem is null then 'P' 
						                 else 'C' 
						               end as convenio,
						               case
						                 when t64_bemtipos = 1 then  
						                   case 
						                     when t09_bem is null then t52_valaqu 
						                     else 0 
						                   end
						                 else 0
						               end as vlrmovelproprio,
						               case
						                 when t64_bemtipos = 1 then  
						                   case 
						                     when t09_bem is null then 0 
						                     else t52_valaqu
						                   end
						                 else 0
						               end as vlrmovelconvenio,
						               case
						                 when t64_bemtipos = 2 then  
						                   case 
						                     when t09_bem is null then t52_valaqu 
						                     else 0 
						                   end
						                 else 0
						               end as vlrimovelproprio,
						               case
						                 when t64_bemtipos = 2 then  
						                   case 
						                     when t09_bem is null then 0 
						                     else t52_valaqu
						                   end
						                 else 0
						               end as vlrimovelconvenio,
						               o40_descr  as orgao,
						               o41_descr  as unidade,
						               descrdepto as depto, 
						               t30_descr  as divisao
						          from bens 
						               inner join clabens      on t64_codcla                  = t52_codcla  
						               left  join benscedente  on t09_bem                     = t52_bem 
						               inner join db_depart    on db_depart.coddepto          = bens.t52_depart
						               left  join bensdiv      on bensdiv.t33_bem             = bens.t52_bem
						               left  join departdiv    on departdiv.t30_codigo        = bensdiv.t33_divisao 
						                                      and departdiv.t30_depto         = bens.t52_depart
						               left join bensbaix    on bensbaix.t55_codbem           = bens.t52_bem 
						               inner join db_departorg on db_departorg.db01_coddepto  = bens.t52_depart 
						                                      and db_departorg.db01_anousu    = ".db_getsession('DB_anousu')."
						               inner join orcorgao     on orcorgao.o40_orgao          = db_departorg.db01_orgao 
						                                      and orcorgao.o40_anousu         = db_departorg.db01_anousu 
						               inner join orcunidade   on orcunidade.o41_unidade      = db_departorg.db01_unidade 
						                                      and orcunidade.o41_orgao        = db_departorg.db01_orgao
						                                      and orcunidade.o41_anousu       = db_departorg.db01_anousu
						         $sWhere ) as x ";  


$aXml = $api->getReport();
$aXml["Report"]["DataSet"]["Query"]["Select"]   = $sCamposFrom;
$aXml["Report"]["DataSet"]["Query"]["From"]     = $sSqlFrom;
$aXml["Report"]["DataSet"]["Query"]["GroupBy"]  = $sSqlGroupBy;
$api->setReport($aXml);


$api->setOutputPath($sCaminhoSalvoSxw);

try {
	$oDocumentoTemplate = new documentoTemplate(7,$atamodelo); 
} catch (Exception $eException){
	$sErroMsg  = $eException->getMessage();
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}

$lProcessado = $api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());

if( $lProcessado ){
  db_redireciona($sCaminhoSalvoSxw);
} else {
	
	echo " SELECT ".$sCamposFrom."<br>FROM ".$sSqlFrom."<br> GROUP BY".$sSqlGroupBy;
	die();
	db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gera relatório !!!");
}

?>