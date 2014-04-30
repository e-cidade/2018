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
include("classes/db_obrasresp_classe.php");
include("classes/db_obrastiporesp_classe.php");
include("classes/db_obraspropri_classe.php");
include("classes/db_obraslote_classe.php");
include("classes/db_obraslotei_classe.php");
include("classes/db_obrasender_classe.php");
include("classes/db_obrasalvara_classe.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obrasconstr_classe.php");
include("classes/db_obraslayout_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_obras_classe.php");
include("classes/db_db_cepmunic_classe.php");

$clobras = new cl_obras;
$clobraslayout = new cl_obraslayout;
$clobrasresp = new cl_obrasresp;
$clobraspropri = new cl_obraspropri;
$clobrastiporesp = new cl_obrastiporesp;
$clobraslote = new cl_obraslote;
$clobraslotei = new cl_obraslotei;
$clobrasender = new cl_obrasender;
$clobrasalvara = new cl_obrasalvara;
$clobrashabite = new cl_obrashabite;
$clobrasconstr = new cl_obrasconstr;
$cldb_config = new cl_db_config;
$cldb_cepmunic = new cl_db_cepmunic;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clobras->rotulo->label();
$db_opcao = 1;
$db_botao = false;
$linha2 = "";
$espaco = ",";
$inst = "";
$y = 55;
$pula = "";
$contador = 0;
if(isset($gerar)){
  echo "<br><br>";
  $reslayout = $clobraslayout->sql_record($clobraslayout->sql_query("","distinct ob14_codobra,ob14_seq "));
  $geradas = "";
  if($clobraslayout->numrows > 0){
    $vir = "";
    for($i=0;$i<$clobraslayout->numrows;$i++){
      db_fieldsmemory($reslayout,$i);
      $geradas .= $vir.$ob14_codobra; 
      $vir = ",";
    }
  }
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
  umask(74);
  $tmpfile = tempnam("tmp",'obras_').'.txt';
  $fd = fopen($tmpfile,"w");
  $result = $cldb_config->sql_record($cldb_config->sql_query("","cgc,nomeinst,munic,db_cepmunic.db10_codibge","","upper(trim(munic)) like trim(db_cepmunic.db10_munic)"));
  db_fieldsmemory($result,0);
  $depart =  str_pad("SECRETARIA DE PLANEJAMENTO E URBANISMO",55," ",STR_PAD_RIGHT);
  $nomeinst = str_pad($nomeinst,55," ",STR_PAD_RIGHT);
  $munic = str_pad($munic,30," ",STR_PAD_RIGHT);
  $db10_codibge = str_pad($db10_codibge,5,"0",STR_PAD_RIGHT);
  // testa se tem obras no periodo
   if(isset($dtini_dia) && $dtini_dia != ""){
        $dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
   }
   if(isset($dtfim_dia) && $dtfim_dia != ""){
        $dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
   }
   if(isset($dtini_dia) && $dtini_dia != "" && isset($dtfim_dia) && $dtfim_dia != ""){
            $res_tem = $clobrasalvara->sql_record(
            $clobrasalvara->sql_query(null,"*",null,
                                      "ob04_data betwen '$dtini' and  '$dtfim' "));
   } 	    
   if($clobrasalvara->numrows > 0){
      $tem_obras="1";   // 1- tem obras, 2- sem obras
   } else {
      $tem_obras="2";
   }   
  //// 
  $linha1 = "1".substr($db10_codibge,0,5).date("Ymd").date("His").$dtini_ano.str_pad($dtini_mes,2,'0')."$tem_obras".$cgc."44".substr($nomeinst,0,55).substr($depart,0,55).$munic."\n";
  $resobras = $clobras->sql_record($clobras->sql_query("","*","ob01_codobra"));
  fputs($fd,$linha1);
  $linhas = "";
  for($xx=0;$xx<$clobras->numrows;$xx++){
    $linha2="";
    $linha3="";
    db_fieldsmemory($resobras,$xx);
    $resob = $clobrashabite->sql_record($clobrashabite->sql_query("","*",""," ob01_codobra = $ob01_codobra"));
    $numrows = $clobrashabite->numrows;
    if($numrows > 0 && 1 == 2){
      db_inicio_transacao();
      for($i=0;$i<$numrows;$i++){
        $contador++;
	db_fieldsmemory($resob,$i);
	$result = $clobraspropri->sql_record($clobraspropri->sql_query($ob01_codobra));
	if($clobraspropri->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	$z01_cgccpf = trim($z01_cgccpf);
	if(strlen($z01_cgccpf) == 11){
	  $tipoi = "3";
	}elseif(strlen($z01_cgccpf) == 14){
	  $tipoi = "1";
	}elseif(strlen($z01_cgccpf) == 0 && $z01_ident != ""){
	  $tipoi = "2";
	  $z01_cgccpf = $z01_ident;
	}
	$z01_cgccpf = str_pad($z01_cgccpf,14," ",STR_PAD_RIGHT);
	$z01_nome = str_pad($z01_nome,55," ",STR_PAD_RIGHT);
	$z01_ender = str_pad($z01_ender,55," ",STR_PAD_RIGHT);
	$z01_bairro = str_pad($z01_bairro,20," ",STR_PAD_RIGHT);
	$z01_cep = str_pad($z01_cep,8," ",STR_PAD_RIGHT);
	$result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","*",""," trim(db10_munic) = upper(trim('$z01_munic'))"));
	if($cldb_cepmunic->numrows > 0){
	  db_fieldsmemory($result,0);
          $db10_codibge = str_pad($db10_codibge,6,"0",STR_PAD_RIGHT);
	}else{
	  echo "<script>
		  alert('Município sem código do IBGE cadastrado!')
		  location.href='pro4_geralayout.php';
		</script>";
	  exit;
	}
	$z01_telef = str_pad($z01_telef,12," ",STR_PAD_RIGHT);
	$ddd = ""; 
	for($j=0;$j<4;$j++){
	  $ddd .= $espaco;
	}
	$ddd = str_replace(","," ",$ddd);
	$z01_email = str_pad($z01_email,60," ",STR_PAD_RIGHT);
	//$ob02_cod = str_pad($ob02_cod,2,"0",STR_PAD_LEFT);
//	$ob02_cod += 50;
	$ender_bairro_cep_cod = '';
	for($j=0;$j<89;$j++){
	  $ender_bairro_cep_cod .= $espaco;
	}
	$ender_bairro_cep_cod = str_replace(","," ",$ender_bairro_cep_cod);
	$result = $clobrasresp->sql_record($clobrasresp->sql_query("","z01_ident as ident,z01_cgccpf as cgccpf,z01_nome as nome,z01_ender as ender,z01_bairro as bairro,z01_cep as cep,z01_uf as uf,z01_munic as munic",""," ob01_codobra = $ob01_codobra"));
	if($clobrasresp->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	$cgccpf = trim($cgccpf);
	if(strlen($cgccpf) == 11){
	  $tpi = "3";
	}elseif(strlen($cgccpf) == 14){
	  $tpi = "1";
	}elseif(strlen($cgccpf) == 0 && $ident != ""){
	  $tpi = "2";
	  $cgccpf = $ident;
	}
	$cgccpf = str_pad($cgccpf,14," ",STR_PAD_RIGHT);
	$nome = str_pad($nome,55," ",STR_PAD_RIGHT);
	$ender = str_pad($ender,55," ",STR_PAD_RIGHT);
	$bairro = str_pad($bairro,20," ",STR_PAD_RIGHT);
	$cep = str_pad($cep,8," ",STR_PAD_RIGHT);
	$uf = str_pad($uf,2," ",STR_PAD_RIGHT);
	$result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","db10_codibge as codibge",""," trim(db10_munic) = trim('$munic')"));
	if($cldb_cepmunic->numrows > 0){
	  db_fieldsmemory($result,0);
          $db10_codibge = str_pad($db10_codibge,6,"0",STR_PAD_RIGHT);
	}else{
          $db10_codibge = str_pad("",6,"0",STR_PAD_RIGHT);
	}
	//$ob02_cod = str_pad($ob02_cod,2,"0",STR_PAD_LEFT);
	$dadosconstr = '';
	for($j=0;$j<88;$j++){
	  $dadosconstr .= $espaco;
	}

	if (strlen($ob02_cod) == 1) {
	  $ob02_cod = " " . $ob02_cod;
	}

	$dadosconstr = str_replace(","," ",$dadosconstr);
	$linha2 = "2".$tipoi.$z01_cgccpf.$z01_nome.$z01_ender.$z01_bairro.$z01_cep.$z01_uf.$db10_codibge.$ddd.$z01_telef.$ddd.$z01_telef.substr($z01_email,0,60).$ob02_cod.date("Ymd").$ender_bairro_cep_cod.$tpi.$cgccpf.$nome.$ender.$bairro.$cep.$uf.$codibge; 
      /////////////////////////////////////////////////////// 
	$result = $clobrasalvara->sql_record($clobrasalvara->sql_query($ob01_codobra));
	if($clobrasalvara->numrows > 0){
	  db_fieldsmemory($result,0);
	}else{
	  $ob04_alvara = "";
	  $ob04_data = "";
	}
	$result = $clobrasender->sql_record($clobrasender->sql_query("","*",""," ob01_codobra = $ob01_codobra and ob07_codconstr = $ob09_codconstr"));
	if($clobrasender->numrows > 0){
	  db_fieldsmemory($result,0);
	  $j14_nome =  str_pad($j14_nome,55," ",STR_PAD_LEFT);
	  $j13_descr = str_pad($j13_descr,20," ",STR_PAD_LEFT);
	  $ob07_inicio = str_pad(str_replace("-","",$ob07_inicio),8," ",STR_PAD_LEFT);
	  $ob07_fim =    str_pad(str_replace("-","",$ob07_fim),8," ",STR_PAD_LEFT);
	  $ob08_area =  $ob08_area * 100;
          $ob08_area =  str_pad($ob08_area,8,"0",STR_PAD_RIGHT);
	  $ob07_unidades =  str_pad($ob07_unidades,5,"0",STR_PAD_LEFT);
	  $ob07_pavimentos =  str_pad($ob07_pavimentos,5,"0",STR_PAD_LEFT);
	}
	$ob04_alvara =  str_pad($ob04_alvara,15," ",STR_PAD_LEFT);
	$ob01_nomeobra = str_pad($ob01_nomeobra,55," ",STR_PAD_LEFT);
	$ob04_data = str_replace("-","",$ob04_data);
	$linha2 = $linha2.$ob04_alvara.$ob04_data.$ob01_nomeobra.$j14_nome.$j13_descr.$cep.$z01_uf.$codibge.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ob07_inicio.$ob07_fim.$ob08_ocupacao.$ob08_tipoconstr.$ob08_area.$ob07_unidades.$ob07_pavimentos;
	if($ob09_parcial == "t"){
	  $hab = 'P';
	}else{
	  $hab = 'T';
	}
	//$clobraslayout->ob14_codobra = $ob01_codobra;
	//$clobraslayout->ob14_codhab = $ob09_codhab;
	//$clobraslayout->ob14_data = date("Y-m-d");
	//$clobraslayout->incluir("");
	$ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
	$ob09_area =  str_pad(str_replace(" ","",db_formatar($ob09_area,'f')),10," ",STR_PAD_LEFT);
	$linha3 = "\n3".$ob09_habite.str_replace("-","",$ob09_data).$ob09_area.$hab;  
	//fputs($fd,$linha2);
	//fputs($fd,$linha3);
	$linhas .= $pula.$linha2.$linha3;
	$pula = "\n";
      }
      $ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
    }else{
	if(isset($dtini_dia) && $dtini_dia != ""){
	  $dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
	}
	if(isset($dtfim_dia) && $dtfim_dia != ""){
	  $dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
	}
	if(isset($dtini_dia) && $dtini_dia != "" && isset($dtfim_dia) && $dtfim_dia != ""){
	  $where = " and ob04_data between '$dtini' and '$dtfim'";
	}else{
	  $where = "";
	}
	$result = $clobrasalvara->sql_record($clobrasalvara->sql_query("","*",""," ob04_codobra = $ob01_codobra $where"));
	if($clobrasalvara->numrows > 0){
	  db_fieldsmemory($result,0);
	}elseif($where == ""){
	  echo "obra $ob01_codobra sem àlvara<br>";
	  continue;
	}elseif($where != ""){
	  continue;
	}
	$result = $clobraspropri->sql_record($clobraspropri->sql_query($ob01_codobra));
	if($clobraspropri->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	$z01_cgccpf = trim($z01_cgccpf);
	if(strlen($z01_cgccpf) == 11){
	  $tipoi = "3";
	}elseif(strlen($z01_cgccpf) == 14){
	  $tipoi = "1";
	}elseif(strlen($z01_cgccpf) == 0 && $z01_ident != ""){
	  $tipoi = "2";
	  $z01_cgccpf = $z01_ident;
	}
	if($z01_cgccpf == "00000000000000" || trim($z01_cgccpf) == "00000000000" || trim($z01_cgccpf) == ""  || (strlen(trim($z01_cgccpf)) != 11 && strlen(trim($z01_cgccpf)) != 14) ){
	  echo "obra $ob01_codobra - número do CGM: <b><a href='prot1_cadcgm002.php?chavepesquisa=$z01_numcgm'>$z01_numcgm</b></a> - com CPF/CNPJ inválido ou zerado<br>";
	  continue;
	}
	$z01_cgccpf = str_pad($z01_cgccpf,14," ",STR_PAD_RIGHT);
	$z01_nome = str_pad($z01_nome,55," ",STR_PAD_RIGHT);
	$z01_ender = str_pad($z01_ender,55," ",STR_PAD_RIGHT);
	$z01_bairro = str_pad($z01_bairro,20," ",STR_PAD_RIGHT);
	$z01_cep = str_pad($z01_cep,8," ",STR_PAD_RIGHT);
	if(trim($z01_bairro) == "" || trim($z01_ender) == ""){
	  echo "obra $ob01_codobra - número do CGM: <b><a href='prot1_cadcgm002.php?chavepesquisa=$z01_numcgm'>$z01_numcgm</b></a> - com endereço e/ou bairro vazio ou dezatualizado no CGM<br>";
	  continue;
	}
	$result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","*",""," trim(db10_munic) = upper(trim('$z01_munic'))"));
	if($cldb_cepmunic->numrows > 0){
	  db_fieldsmemory($result,0);
          $db10_codibge = str_pad($db10_codibge,6,"0",STR_PAD_RIGHT);
	}else{
          $db10_codibge = str_pad("",6,"0",STR_PAD_RIGHT);
	}
	$z01_telef = str_pad($z01_telef,12," ",STR_PAD_RIGHT);
	$ddd = ""; 
	for($j=0;$j<4;$j++){
	  $ddd .= $espaco;
	}
	$ddd = str_replace(","," ",$ddd);
	$z01_email = str_pad($z01_email,60," ",STR_PAD_RIGHT);
	//$ob02_cod = str_pad($ob02_cod,2,"0",STR_PAD_LEFT);
//	$ob02_cod += 50;
	$ender_bairro_cep_cod = '';
	for($j=0;$j<89;$j++){
	  $ender_bairro_cep_cod .= $espaco;
	}
	$ender_bairro_cep_cod = str_replace(","," ",$ender_bairro_cep_cod);
	$result = $clobrasresp->sql_record($clobrasresp->sql_query("","z01_ident as ident,z01_cgccpf as cgccpf,z01_nome as nome,z01_ender as ender,z01_bairro as bairro,z01_cep as cep,z01_uf as uf,z01_munic as munic",""," ob01_codobra = $ob01_codobra"));
	if($clobrasresp->numrows > 0){
	  db_fieldsmemory($result,0);
	}
	$cgccpf = trim($cgccpf);
	if(strlen($cgccpf) == 11){
	  $tpi = "3";
	}elseif(strlen($cgccpf) == 14){
	  $tpi = "1";
	}elseif(strlen($cgccpf) == 0 && $ident != ""){
	  $tpi = "2";
	  $cgccpf = $ident;
	}
	$cgccpf = str_pad($cgccpf,14," ",STR_PAD_RIGHT);
	$nome = str_pad($nome,55," ",STR_PAD_RIGHT);
	$ender = str_pad($ender,55," ",STR_PAD_RIGHT);
	$bairro = str_pad($bairro,20," ",STR_PAD_RIGHT);
	$cep = str_pad($cep,8," ",STR_PAD_RIGHT);
	$uf = str_pad($uf,2," ",STR_PAD_RIGHT);
	$result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","db10_codibge as codibge",""," upper(trim(db10_munic)) = upper(trim('$munic'))"));
	if($cldb_cepmunic->numrows > 0){
	  db_fieldsmemory($result,0);
          $codibge = str_pad($codibge,6,"0",STR_PAD_RIGHT);
	}else{
          $codibge = str_pad("",6,"0",STR_PAD_RIGHT);
	}

	$dadosconstr = '';
	for($j=0;$j<88;$j++){
	  $dadosconstr .= $espaco;
	}

	if (strlen($ob02_cod) == 1) {
	  $ob02_cod = " " . $ob02_cod;
	}

	$dadosconstr = str_replace(","," ",$dadosconstr);
	$linha2 = "2".$tipoi.$z01_cgccpf.$z01_nome.$z01_ender.$z01_bairro.$z01_cep.$z01_uf.$db10_codibge.$ddd.$z01_telef.$ddd.$z01_telef.substr($z01_email,0,60).$ob02_cod.date("Ymd").$ender_bairro_cep_cod.$tpi.$cgccpf.$nome.$ender.$bairro.$cep.$uf.$codibge; 
      /////////////////////////////////////////////////////// 
	$rescons = $clobrasconstr->sql_record($clobrasconstr->sql_query("","*",""," ob08_codobra = $ob01_codobra"));
	if($clobrasconstr->numrows > 0){
	  db_fieldsmemory($rescons,0);
	  $result = $clobrasender->sql_record($clobrasender->sql_query("","*",""," ob01_codobra = $ob01_codobra and ob07_codconstr = $ob08_codconstr"));
	  if($clobrasender->numrows > 0){
	    db_fieldsmemory($result,0);
	    $j14_nome =  str_pad($j14_nome,55," ",STR_PAD_LEFT);
	    $j13_descr =  str_pad($j13_descr,20," ",STR_PAD_LEFT);
	    $ob07_inicio = str_pad(str_replace("-","",$ob07_inicio),8," ",STR_PAD_LEFT);
	    $ob07_fim =    str_pad(str_replace("-","",$ob07_fim),8," ",STR_PAD_LEFT);
	    $ob08_area =  $ob08_area * 100;
            $ob08_area =  str_pad($ob08_area,8,"0",STR_PAD_LEFT);
	    $ob07_unidades =  str_pad($ob07_unidades,5,"0",STR_PAD_LEFT);
	    $ob07_pavimentos =  str_pad($ob07_pavimentos,5,"0",STR_PAD_LEFT);
	  }else{
	    echo "obra $ob01_codobra sem endereço correto na construção<br>";
	    continue;
	  }
	}else{
	  /*
	  $j14_nome =  str_pad("",55," ",STR_PAD_LEFT);
	  $j13_descr =  str_pad("",20," ",STR_PAD_LEFT);
	  $ob07_inicio = str_pad("",8," ",STR_PAD_LEFT);
	  $ob07_fim =    str_pad("",8," ",STR_PAD_LEFT);
	  $ob08_area =  $ob08_area * 100;
          $ob08_area =  str_pad($ob08_area,8,"0",STR_PAD_RIGHT);
	  $ob07_unidades =  str_pad("",5,"0",STR_PAD_LEFT);
	  $ob07_pavimentos =  str_pad("",5,"0",STR_PAD_LEFT);
	  $ob08_ocupacao = "0";
	  $ob08_tipoconstr = "0";
	  */
	  continue;
	}
	$ob04_alvara =  str_pad($ob04_alvara,15," ",STR_PAD_LEFT);
	$ob01_nomeobra = str_pad($ob01_nomeobra,55," ",STR_PAD_LEFT);
	$ob04_data = str_replace("-","",$ob04_data);
	if($ob08_tipolanc == "30000"){
	  $resto  = substr($ob08_ocupacao,4,1); 
	  $resto .= substr($ob08_tipoconstr,4,1); 
	  $resto .= $ob08_area; 
	  $resto .= str_pad("",38," ",STR_PAD_RIGHT);
	}elseif($ob08_tipolanc == "30001"){
          $resto  = str_pad("",10," ",STR_PAD_LEFT);
          $resto .= substr($ob08_ocupacao,4,1);
          $resto .= substr($ob08_tipoconstr,4,1);
          $resto .= $ob08_area;
          $resto .= str_pad("",10," ",STR_PAD_RIGHT);
	  $ob07_areaatual =  $ob07_areaatual * 100;
          $ob07_areaatual =  str_pad($ob07_areaatual,8,"0",STR_PAD_LEFT);
          $resto .= $ob07_areaatual;
	  $resto .= str_pad("",10," ",STR_PAD_RIGHT);
	  if($ob07_areaatual == "" || $ob07_areaatual <= 0){
	    echo "obra $ob01_codobra sem àrea existente cadastrada<br>";
	    continue;
	  }
	}elseif($ob08_tipolanc == "30002"){
          $resto  = str_pad("",20," ",STR_PAD_LEFT);
          $resto .= substr($ob08_ocupacao,4,1);
          $resto .= substr($ob08_tipoconstr,4,1);
	  $ob07_areaatual =  $ob07_areaatual * 100;
          $ob07_areaatual =  str_pad($ob07_areaatual,8,"0",STR_PAD_LEFT);
          $ob08_area =  str_pad($ob08_area,8,"0",STR_PAD_LEFT);
          $resto .= $ob08_area.$ob07_areaatual;
          $resto .= str_pad("",10," ",STR_PAD_RIGHT);
	  if($ob07_areaatual == "" || $ob07_areaatual <= 0){
	    echo "obra $ob01_codobra sem àrea existente cadastrada<br>";
	    continue;
	  }
	}elseif($ob08_tipolanc == "30003"){
//	  $resto  = str_pad("",30," ",STR_PAD_LEFT);
//        $resto .= substr($ob08_ocupacao,4,1);
//        $resto .= $ob08_area;
//        $resto .= str_pad("",16," ",STR_PAD_RIGHT);

	  $resto  = str_pad("",30," ",STR_PAD_LEFT);
          $ob07_areaatual =  $ob07_areaatual * 100;
          $resto .=  str_pad($ob07_areaatual,8,"0",STR_PAD_LEFT);
          $resto .= substr($ob08_ocupacao,4,1);
          $resto .= substr($ob08_tipoconstr,4,1);
          $resto .= str_pad( $ob08_area,8,"0",STR_PAD_LEFT);
	}
	$j13_descr = substr($j13_descr,0,20);
	$linha2 = $linha2.$ob04_alvara.$ob04_data.$ob01_nomeobra.$j14_nome.$j13_descr.$cep.$z01_uf.$codibge.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ob07_inicio.$ob07_fim.$resto.$ob07_unidades.$ob07_pavimentos;
        $linha3 = "";
	$resob = $clobrashabite->sql_record($clobrashabite->sql_query("","obrashabite.*",""," ob01_codobra = $ob01_codobra"));
        if($clobrashabite->numrows > 0){
          for($tt=0;$tt<$clobrashabite->numrows;$tt++){
	    db_fieldsmemory($resob,$tt);
	    if($ob09_parcial == "t"){
	      $hab = 'P';
	    }else{
	      $hab = 'T';
	    }
	    $ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
	    $ob09_area =  str_pad(($ob09_area * 100),8,"0",STR_PAD_LEFT);
	    $ob09_data = str_replace("-","",$ob09_data);
	    $linha3 .= "\n3".$ob09_habite.$ob09_data.$ob09_area.$hab; 
	    $contador ++;
	  }
	}
	//$clobraslayout->ob14_codobra = $ob01_codobra;
	//$clobraslayout->ob14_codhab = $ob09_codhab;
	//$clobraslayout->ob14_data = date("Y-m-d");
	//$clobraslayout->incluir("");
	//fputs($fd,$linha2);
	//fputs($fd,$linha3);
	$linhas .= $pula.$linha2.$linha3;
	$pula = "\n";
      }
      $contador++;
      //$ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
  }
    fputs($fd,$linhas);
    $linha4 = "\n4".str_pad(($contador + 2),6," ",STR_PAD_LEFT);  
    fputs($fd,$linha4);
   // fwrite($fd,strlen($fd));
    fclose($fd);
  db_fim_transacao();
  ///////////////////////////////////////
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1"  bgcolor="#CCCCCC">
<form name="form1" method="post" action="" target="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr align="center" >
    <center>
  <br><br><br>
        <?
	if(isset($gerar)){
//	  system("cp $tmpfile /srv/www/htdocs/dbportal2/tmp/"); 
//          echo "<br><strong><a style='color:black' href='http://".$DB_SERVIDOR."/dbportal2/tmp/".basename($tmpfile)."'> Arquivo gerado em: ".basename($tmpfile)."\nClique aqui para salvar</a></strong><br><br>";
          echo "<br><strong><a style='color:black' target='_blank' href='$tmpfile'> Arquivo gerado: $tmpfile\nClique aqui para acessar o arquivo</a></strong><br><br>";
	}
	?>
  </tr>
  <tr align="center" height="60">
    <td colspan="2">
      <b>GERADOR DE ARQUIVO PARA INSS</b>
    </td>
  </tr>  
  <tr>
    <td valign='center' nowrap align="center" >
      <strong>Período das Obras:</strong>
       
<?
db_inputdata('dtini',@$dtini_dia,@$dtini_mes,@$dtini_ano,true,'text',1,"")
?>
<strong>&nbsp;À&nbsp;</strong>
<?
db_inputdata('dtfim',@$dtfim_dia,@$dtfim_mes,@$dtfim_ano,true,'text',1,"")
?>
    </td>
  </tr>  
  <tr>
    <td align='center' colspan='2'>
      <input name="gerar" type="submit" value="Gerar" >
    </td>
  </tr>
  </center>
</table>  
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function teste(){
  if(document.form1.ob01_codobra.value == ""){
    alert('Escolha o código da obra antes de gerar o layout!');
    return false
  }else{
    return true;
  }
  return false;
}
function js_pesquisaob04_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obrashabite.php?layout=true&funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob01_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obrashabite.php?layout=true&pesquisa_chave='+document.form1.ob01_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob01_codobra.focus(); 
    document.form1.ob01_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob01_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
</script>