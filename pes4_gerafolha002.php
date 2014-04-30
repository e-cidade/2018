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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");
require_once("pes4_gerafolha003.php");
require_once("pes4_gerafolha004.php");

$oGet = db_utils::postMemory($_GET);

global $r110_lotaci, $r110_lotacf, $r110_regisi, $r110_regisf,$opcao_gml,$opcao_geral,$faixa_lotac,$faixa_regis;
global $lotacao_faixa;
global $cfpess,$subpes,$d08_carnes,$anousu, $mesusu,$DB_instit, $db21_codcli;

if(isset($_GET['lAutomatico'])){
	
	$opcao_geral  = $_GET['iPonto'];
	$faixa_regis  = $_GET['iMatricula'];
	$opcao_gml    = "m";
	$opcao_filtro = "s";
	$selregist    = array($_GET['iMatricula']);
	$db_debug     = "false";
	
}




$subpes    = db_anofolha().'/'.db_mesfolha();
$anousu    = db_anofolha();
$mesusu    = db_mesfolha();
$DB_instit = DB_getsession("DB_instit");

/**
 * Validamos a regra dos Custos
 * caso o custo esteje sendo utilizado, e já existe uma planilha encerrada para o mes/ano, nao podemos
 * permitir a liquidacao do empenho  
 */
    
require_once("std/db_stdClass.php");
$aParamKeys = array(
                   db_getsession("DB_anousu")
                  );
                  
                  
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0;
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
if ($iTipoControleCustos > 1) {
    
  require_once('model/custoPlanilha.model.php');
  $oPlanilha = new custoPlanilha($mesusu, $anousu);
  if ($oPlanilha->getSituacao() == 2) {
        
    $sMsgErro  = "Erro (0) - Não é  possível gerar calculo da folha.\\nPlanilha de custos já processada "; 
    $sMsgErro .= "para competência {$mesusu}/{$anousu}";
    db_msgbox($sMsgErro);
    db_redireciona("pes4_gerafolha001.php"); 
  }
}  

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

db_inicio_transacao();



db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS);

if(!isset($r110_lotaci)){
  $r110_lotaci = '    '; 
}

if(!isset($r110_lotacf)){
  $r110_lotacf = '    ';
}

if ($opcao_gml == 'm') {
  $where = " ";
  if((isset($r110_regisi) && $r110_regisi != "" ) && (isset($r110_regisf) && $r110_regisf != "")){
     $where .= " and rh01_regist between '$r110_regisi' and '$r110_regisf' ";
  }else if(isset($r110_regisi) && $r110_regisi != ""){
     $where .= " and rh01_regist >= '$r110_regisi' ";
  }else if(isset($r110_regisf) && $r110_regisf != ""){
     $where .= " and rh01_regist <= '$r110_regisf' ";
  }else if(isset($faixa_regis) && $faixa_regis != "") {
     $where .= " and rh01_regist in ($faixa_regis) ";
  }
  
  if ($where != "") {
  	
    if ($opcao_geral == 4) {
       $where1 = " and rh05_recis is not null";
    } else {
       $where1 = " and rh05_recis is null";
    }
    
    global $pessoal;
    $sql = "select rh01_regist
              from rhpessoalmov
                   left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                   inner join rhpessoal     on rh01_regist               = rh02_regist
             where rh02_anousu = ".db_anofolha()."
               and rh02_mesusu = ".db_mesfolha()."
               and rh02_instit = ".db_getsession("DB_instit")."
               $where1
               and rh01_numcgm in (select distinct rh01_numcgm
                                     from rhpessoalmov
                                          left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                                          inner join rhpessoal     on rh01_regist               = rh02_regist
                                    where rh02_anousu = ".db_anofolha()."
                                      and rh02_mesusu = ".db_mesfolha()."
                                      and rh02_instit = ".db_getsession("DB_instit")."
                                      $where
                                      $where1)
             order by rh01_numcgm,rh01_regist ";
    if (db_selectmax("pessoal",$sql)) {
      $faixa_regis = "";
      $separa = " "; 
      for($Ipessoal=0;$Ipessoal < count($pessoal);$Ipessoal++){
          $faixa_regis .= $separa.$pessoal[$Ipessoal]["rh01_regist"];
          $separa = ",";
      }
    }
  }
}

//echo "<BR> opcao_geral --> $opcao_geral -->  $sql";
if (!isset($r110_regisi)) {
  $r110_regisi = $faixa_regis; 
}

if (!isset($r110_regisf)) {
  $r110_regisf = $faixa_regis;
}

if (!isset($opcao_filtro)) {
  $opcao_filtro = "0";
}

if ($faixa_lotac != " ") {
  $lotacao_faixa = $faixa_lotac;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<center>
<?
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Calculo ...');
//db_criatermometro('calc_folha','Concluido...','blue',1,'Processando Pensoes');
?>
</center>

<? 
if(!isset($_GET['lAutomatico'])){
  //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
<?

global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes, db21_codcli , cgc from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
  $d08_carnes = "daeb";
} else {
  $d08_carnes = $db_config[0]["d08_carnes"];
}

$db21_codcli = $db_config[0]["db21_codcli"];

$db_erro = false;


global $diversos;
db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ));
$separa = "global ";
$quais_diversos = "";

for($Idiversos=0;$Idiversos<count($diversos);$Idiversos++){
  $codigo = $diversos[$Idiversos]["r07_codigo"];
      
	$quais_diversos .= $separa.'$'.$codigo;	   
	$separa = ",";
	 
	global $$codigo;
  eval('$$codigo = '.$diversos[$Idiversos]["r07_valor"].";");
}
      
$quais_diversos .= ';';	   
      
global $ajusta;
      
$ajusta = false ;
      
if ( $opcao_geral == 1 || $opcao_geral == 8 || $opcao_geral == 4 || $opcao_geral == 3 || $opcao_geral == 5   ){
	$ajusta = true ;
}

global $carregarubricas_geral,$carregarubricas;
      
$carregarubricas_geral = array();
      
db_selectmax("carregarubricas","select * from rhrubricas where rh27_instit = $DB_instit order by rh27_rubric" );
      
for($Icarregar=0;$Icarregar<count($carregarubricas);$Icarregar++){
	 
  $r10_pd = db_boolean( $carregarubricas[$Icarregar]["rh27_pd"] );
  $formula = $carregarubricas[$Icarregar]["rh27_form"];
  
  if( db_empty($formula)){
    
    if( $r10_pd == 2 ){
      $r10_form = "-";
    } else {
 	    $r10_form = "+";
 	  }
  } else {
    
    $r10_form = '('.trim($formula).')';
    
    if( $r10_pd == 2 ){
      $r10_form = "-".$r10_form;
    } else {
      $r10_form = "+".$r10_form;
	  }
 	}
 	
  $r10_form = str_replace('D','$D',$r10_form);
  $r10_form = str_replace('F','$F',$r10_form);
  $carregarubricas_geral[$carregarubricas[$Icarregar]["rh27_rubric"]] = $r10_form;
}
      
pes4_geracalculo003();
if (file_exists("fim_calculo.php")) {
  include_once('fim_calculo.php');
}

if ($db_debug) {
  echo " Fim do Calculo com Debug. <br><br>";
  echo " Calculo não foi gravado na base. ";
  db_fim_transacao(true);
  exit;
}  
  			

flush();
db_fim_transacao();
flush();

if(!isset($oGet->lAutomatico)){
	
  
  echo "<script>
           parent.db_calculo.hide();
        </script>";   
  
} elseif(isset($oGet->lAutomatico) && ($oGet->lAutomatico == 2)) {
	
	echo "<script>
	       parent.db_iframe_ponto.hide();
	       
	       setTimeout(parent.document.getElementById('pesquisar').click(), 2000);
	      </script>";  
	
} else {
	
	echo "<script>
	        parent.db_iframe_ponto.hide();
	       
	       setTimeout(parent.document.getElementById('pesquisar').click(), 2000);
	      </script>";  
	
}
?>