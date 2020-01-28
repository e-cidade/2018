<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
//include("libs/db_stdlib.php");
include("classes/db_aidof_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_notasiss_classe.php");
include("classes/db_aidofautenticidade_classe.php");

$claidof = new cl_aidof;
$clcgm = new cl_cgm;
$clissbase = new cl_issbase;
$clnotasiss = new cl_notasiss;
$cl_aidofautenticidade = new cl_aidofautenticidade;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
if (pg_numrows($resultpref)!=0){
  db_fieldsmemory($resultpref,0);
}


$result_aidof = $claidof->sql_record($claidof->sql_query_file($codaidof));
if ($claidof->numrows!=0){
  db_fieldsmemory($result_aidof,0);
}

$result_notasiss=$clnotasiss->sql_record($clnotasiss->sql_query_file(null,"q09_descr as especie",null,"q09_codigo=$y08_nota"));
if ($clnotasiss->numrows!=0){
  db_fieldsmemory($result_notasiss,0);
}

$result_graf=$clcgm->sql_record($clcgm->sql_query_file($y08_numcgm,"z01_nome as nome_graf,z01_ender as ender_graf,z01_numero as num_graf,z01_cgccpf as cgccpf_graf,z01_bairro as bairro_graf,z01_munic as munic_graf"));
if ($clcgm->numrows!=0){
  db_fieldsmemory($result_graf,0);
}

$result_graf_inscr=$clissbase->sql_record($clissbase->sql_query_file(null,"q02_inscr as inscr_graf",null,"q02_numcgm=$y08_numcgm"));
if ($clissbase->numrows!=0){
  db_fieldsmemory($result_graf_inscr,0);
}

$sql_empr="select q02_inscr as inscr_usu,z01_nome as nome_usu,z01_ender as ender_usu,z01_numero as num_usu,z01_cgccpf as cgccpf_usu,z01_bairro as bairro_usu,z01_munic as munic_usu from empresa where q02_inscr=$y08_inscr";
$result_empresa=db_query($sql_empr);
if (pg_numrows($result_empresa)!=0){
  db_fieldsmemory($result_empresa,0);
}


//***************************

	$mes = date("m");
	$ano = date("Y");
	$dia = date("d");
	$codigo =$y08_codigo;
	$ins= $inscr_usu;
	$cnpj = $cgccpf_usu;
	$nros = $ano . $mes . $dia . $codigo . $ins . $cnpj ;
	$t1 = strrev($nros);
	$cod= md5($t1);
	$sql =  "select translate(('$cod'), 'abcdefghijklmnopqrstuvwxyz', '12345678901234567890123456') as codigobarra"; 
//	die($sql);
	$result = db_query($sql);
	$codigobarra = pg_result($result,0,0);
	global $codigobarra;
	
	$sqlaut="select * from aidofautenticidade where y01_aidof = $codigo";
	$resultaut=db_query($sqlaut);
	$linhasaut=pg_num_rows($resultaut);
	
	if($linhasaut == 0){//die("xxxxxxxxxxx");
		$cl_aidofautenticidade->y01_aidof  = $codigo;
	  	$cl_aidofautenticidade->y01_codautenticidade  =$codigobarra ;
	   	$cl_aidofautenticidade->incluir(null);
	   	
	   	if($cl_aidofautenticidade->erro_status=="0"){
   			@$cl_aidofautenticidade->erro();
  		}
	}
	
//********************************




$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'14');
//$pdf1->modelo = 14;
$pdf1->objpdf->SetTextColor(0,0,0);

for($i = 0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,0);

  $pdf1->enderpref  = $ender;
  $pdf1->prefeitura = $nomeinst;
  $pdf1->municpref  = $munic;
  $pdf1->uf         = $uf;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;

  $pdf1->codaidof   = @$y08_codigo;
  $pdf1->ano        = db_getsession('DB_anousu');
  $pdf1->nome_graf  = @$nome_graf;
  $pdf1->ender_graf = @$ender_graf.".".$num_graf."-".@$bairro_graf."/".@$munic_graf;
  $pdf1->inscr_graf = @$inscr_graf;
  $pdf1->cnpj_graf = @$cgccpf_graf;
  $pdf1->nome_usu  = @$nome_usu;
  $pdf1->ender_usu = @$ender_usu.".".@$num_usu."-".@$bairro_usu."/".@$munic_usu;
  $pdf1->inscr_usu = @$inscr_usu;
  $pdf1->cnpj_usu = @$cgccpf_usu;
  $pdf1->notaini  = @$y08_notain;
  $pdf1->notafin  = @$y08_notafi;
  $pdf1->quant    = @$y08_quantlib;
  $pdf1->especie  = @$especie;
  $pdf1->obs      = @$y08_obs;
  $pdf1->codigo     = @$codigobarra;
  $pdf1->imprime();
  
  //$pdf->MultiCell(180, 3, "Código de Autenticidade da Certidão", 0, "R", 0);
   
   
}
//die();
$pdf1->objpdf->Output();
?>