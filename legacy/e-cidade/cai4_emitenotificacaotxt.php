<?php
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

require_once("libs/db_sql.php");
require_once("libs/db_libtributario.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_layoutcampos_classe.php");
require_once("classes/db_parnotificacao_classe.php");
require_once("model/regraEmissao.model.php");
require_once("model/convenio.model.php");
require_once("model/recibo.model.php");
require_once("dbforms/db_layouttxt.php");
require_once("fpdf151/pdf.php");
require_once("fpdf151/impcarne.php");
require_once("dbforms/db_funcoes.php");

define('DB_BIBLIOT',true);

require_once("fpdf151/scpdf.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("k60_codigo");
$clrotulo->label("k60_descr");
$clrotulo->label("db03_docum");
$clrotulo->label("db03_descr");

$abil      = false;
$ordem     = 'a';
$fim       = 0;
$cgm_noti  = '';
$matricula = '';
$inscricao = '';

$clparnotificacao = new cl_parnotificacao();

$rsConsultaParametro = $clparnotificacao->sql_record($clparnotificacao->sql_query_file(db_getsession('DB_anousu'),"k102_tipoemissao"));

if ( $clparnotificacao->numrows > 0   ) {
  $oParam = db_utils::fieldsMemory($rsConsultaParametro,0);  	
} else {
  
  $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.verificar_parametros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;	
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor='#cccccc'>
<?php
if (isset($_GET["lista"])){

$lista = $_GET["lista"];
function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros,$dt_venc) {
  
  // desconto
  global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc;
  
  $cadtipoparc = 0;
  
  $sqlvenc = "select k00_dtvenc 
  from arrecad 
  where k00_numpre = $numpre and 
  k00_numpar = $numpar";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  if (pg_numrows($resultvenc) == 0) {
    return 0;
  }
  db_fieldsmemory($resultvenc, 0);
  
  $sqltipoparc = "select k40_codigo, k40_todasmarc, cadtipoparc
  from tipoparc 
  inner join cadtipoparc on cadtipoparc = k40_codigo
  inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
  where maxparc = 1 and '$dt_venc' >= k40_dtini and 
  '$dt_venc' <= k40_dtfim and
  k41_arretipo = $tipo $whereloteador and 
  '$k00_dtvenc' >= k41_vencini and 
  '$k00_dtvenc' <= k41_vencfim ";
  $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
  if (pg_numrows($resulttipoparc) > 0) {
    db_fieldsmemory($resulttipoparc,0);
  } else {
    $sqltipoparc = "select k40_codigo, k40_todasmarc, cadtipoparc
    from tipoparc 
    inner join cadtipoparc on cadtipoparc = k40_codigo
    inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
    where maxparc = 1 and 
    k41_arretipo = $tipo and '$dt_venc' >= k40_dtini and 
    '$dt_venc' <= k40_dtfim $whereloteador and
    '$k00_dtvenc' >= k41_vencini and 
    '$k00_dtvenc' <= k41_vencfim ";
    $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
    if (pg_numrows($resulttipoparc) == 1) {
      db_fieldsmemory($resulttipoparc,0);
    } else {
      
      $k40_todasmarc = false;
    }
  }
  
  $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
  $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
  $passar = false;
  if (pg_numrows($resulttipoparcdeb) == 0) {
    $passar = true;
  } else {
    $sqltipoparcdeb = "select k40_codigo, k40_todasmarc
    from cadtipoparcdeb 
    inner join cadtipoparc on k40_codigo = k41_cadtipoparc
    where k41_cadtipoparc = $cadtipoparc and 
    k41_arretipo = $tipo_debito $whereloteador and
    '$k00_dtvenc' >= k41_vencini and 
    '$k00_dtvenc' <= k41_vencfim ";
    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    if (pg_numrows($resulttipoparcdeb) > 0) {
      $passar = true;
    }
  }
  
  if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
    $desconto = 0;
  } else {
    $desconto = $k40_codigo;
  }
  
  return $desconto;
  
}

$sql = "select * from lista where k60_codigo = $lista";
$result = db_query($sql);
@db_fieldsmemory($result,0);
$sqllistatipo    =  " select listatipos.*,";
$sqllistatipo   .= "       arretipo.k03_tipo,";
$sqllistatipo   .= " 				k03_descr"; 
$sqllistatipo   .= " from listatipos"; 
$sqllistatipo   .= "   inner join arretipo on k00_tipo = k62_tipodeb"; 
$sqllistatipo   .= "		inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo"; 
$sqllistatipo   .= " where k62_lista = $lista";
$resultlistatipo = db_query($sqllistatipo);
$data_geracao = date("Ymd");

$complementa_nome_arquivo = "_" . ( $tipo == "f"?"producao":"teste" ) . "_base_" . db_getsession("DB_NBASE");

$nomearqdados  = "tmp/notificacao_dados_$data_geracao$complementa_nome_arquivo.txt";
$nomearqlayout = "tmp/notificacao_layout_$data_geracao$complementa_nome_arquivo.txt";
$cldb_layouttxt  = new db_layouttxt(26, $nomearqdados, "");

if (!isset($k60_datavenc) && $k60_datavenc == '') {
  $sqlvenc = "select current_date + '30 days'::interval as db_datausu";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  db_fieldsmemory($resultvenc, 0);
} else {
  $aDataVenc =  explode("/",$k60_datavenc);
  $db_datausu = $aDataVenc[2]."-".$aDataVenc[1]."-".$aDataVenc[0];
}

$DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));


$virgula = '';
$tipos = '';
$descrtipo = '';
$somenteparc = true;
$somenteiptu = true;

db_criatermometro('termometro', 'Concluido...', 'blue', 1);
flush();
for($yy = 0;$yy < pg_numrows($resultlistatipo);$yy++ ) {
  
  db_fieldsmemory($resultlistatipo,$yy);
  $tipos .= $virgula.$k62_tipodeb;
  $descrtipo .= $virgula.trim($k03_descr);
  $virgula = ' , ';  
  if ($k03_tipo != 6 and $k03_tipo != 13 and $k03_tipo != 16) {
    $somenteparc = false;
  }
  if ($k03_tipo != 1) {
    $somenteiptu = false;
  }
}
if ($k60_tipo == 'M') {  

  $xtipo    = 'Matrícula';
  $xcodigo  = 'k22_matric';
  $xcodigo1 = 'j01_matric';
  $xxcodigo1 = 'k55_matric';
  $xcampos = ' substr(fc_proprietario_nome,1,7) as z01_numcgm, substr(fc_proprietario_nome,8,40) as z01_nome ';
  $xxmatric = ' inner join notimatric on k22_matric = k55_matric ';
  $xxmatric2 = '';
  $xxcodigo = 'k55_notifica';
  if (isset($campo)){
    if ($tipo == 2){
      $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
    }elseif ($tipo == 3){
      $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
    }
  }else{
    $contr = '';
  }
  
}elseif($k60_tipo == 'I') {
  
  $xtipo    = 'Inscrição';
  $xcodigo  = 'k22_inscr';
  $xcodigo1 = 'q02_inscr';
  $xxcodigo1 = 'k56_inscr';
  $xxmatric = ' inner join notiinscr on k22_inscr = k56_inscr ';
  $xxmatric2 = ' inner join issbase on q02_inscr = k22_inscr inner join cgm on z01_numcgm = q02_numcgm';
  $xxcodigo = 'k56_notifica';
  $xcampos = ' z01_numcgm, z01_nome ';
  if (isset($campo)){
    if ($tipo == 2){
      $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
    }elseif ($tipo == 3){
      $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
    }
  }else{
    $contr = '';
  }
  
}elseif($k60_tipo == 'N' || $k60_tipo == 'C'){
  $xtipo    = 'Numcgm';
  $xcodigo  = 'k22_numcgm';
  $xcodigo1 = 'j01_numcgm';
  $xxcodigo1 = 'k57_numcgm';
  $xxmatric = ' inner join notinumcgm on k22_numcgm = k57_numcgm ';
  $xxmatric2 = ' inner join cgm on k22_numcgm = z01_numcgm ';
  $xxcodigo = 'k57_notifica';
  $xcampos = ' z01_numcgm, z01_nome ';
  if (isset($campo)){
    if ($tipo == 2){
      $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
    }elseif ($tipo == 3){
      $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
    }
  }else{
    $contr = '';
  }
}

if($ordem == 'a'){
  $xxordem = ' order by z01_nome ';
  $xxxordem = ' order by substr(fc_proprietario_nome,8,40)';
}elseif($ordem == 't'){
  $xxordem = ' order by '.$xxcodigo;
  $xxxordem = ' order by notifica';
}else{
  $xxordem = ' order by '.$xxcodigo1;
  $xxxordem = ' order by '.$xcodigo1;
}

if($fim > 0 && $intervalo == 'n'){
  $limite = 'and '.$xxcodigo.' >= '.$inicio.' and '.$xxcodigo.' <= '.$fim;
}else{
  $limite = '';
}


if ($k60_tipo != 'M'){
  
  $sql  = " select $xxcodigo as notifica,";
  $sql .= "       $xxcodigo1 as $xcodigo1,";
  $sql .= "   		$xcampos,";
  $sql .= " 			sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as xvalor";
  $sql .= " from lista";
  $sql .= "     inner join listanotifica  on k63_codigo = k60_codigo";
  $sql .= "     inner join debitos        on k22_data   = '$k60_datadeb' and k22_numpre = k63_numpre";
  $sql .= "            inner join arrecad on k22_data   = '$k60_datadeb'
									               and arrecad.k00_numpre = debitos.k22_numpre
									               and arrecad.k00_numpar = debitos.k22_numpar
									               and arrecad.k00_receit = debitos.k22_receit
									               and arrecad.k00_tipo   = debitos.k22_tipo   ";
  $sql .= "     $xxmatric and $xxcodigo = k63_notifica $xxmatric2 $limite $contr";
  $sql .= " where k60_codigo = $lista and k22_dtvenc < '" . date("Y-m-d", db_getsession("DB_datausu")) . "'";
  $sql .= " group by $xxcodigo,";
  $sql .= "         $xxcodigo1,";
  $sql .= "         z01_numcgm,";
  $sql .= "         z01_nome";
  $sql .= " $xxordem";
  
} else {
  
  $sql  = " select  notifica,";
  $sql .= "        $xcodigo1,";
  $sql .= "        xvalor,";
  $sql .= "        substr(fc_proprietario_nome,1,7) as z01_numcgm,";
  $sql .= "        substr(fc_proprietario_nome,8,40) as z01_nome";
  $sql .= " from (";
  $sql .= "      select 	$xxcodigo as notifica,";
  $sql .= "               $xxcodigo1 as $xcodigo1,";
  $sql .= "               fc_proprietario_nome($xxcodigo1),";
  $sql .= "               sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as xvalor";
  $sql .= "       from lista";
  $sql .= "            inner join listanotifica on k63_codigo = k60_codigo";
  $sql .= "            inner join debitos         on k22_data = '$k60_datadeb' and k22_numpre = k63_numpre";
	$sql .= "            inner join arrecad         on k22_data = '$k60_datadeb'
						                           and arrecad.k00_numpre = debitos.k22_numpre
						                           and arrecad.k00_numpar = debitos.k22_numpar
						                           and arrecad.k00_receit = debitos.k22_receit
						                           and arrecad.k00_tipo   = debitos.k22_tipo   ";
  $sql .= "            $xxmatric  and $xxcodigo = k63_notifica $xxmatric2 $limite $contr";
  $sql .= "            where k60_codigo = $lista and k22_dtvenc < '" . date("Y-m-d",db_getsession("DB_datausu")) . "'";
  $sql .= "       group by $xxcodigo,";
  $sql .= "                $xxcodigo1";
  $sql .= "      ) as x ";
  $sql .= " $xxxordem";
  
}
if ($_GET["quantidade"] != null || $_GET["quantidade"] != 0){
   $sql .= " limit " . $_GET["quantidade"];
}
$rsNotifica = db_query($sql) or die($sql);

if (pg_numrows($result) == 0){
  
  $oParms = new stdClass();
  $oParms->sLista = $lista;
  $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.nenhuma_notificacao_gerada', $oParms);
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit; 
} 

if($fim > 0 && $intervalo != 'n'){
  if($inicio > 0){
    $lim1 = $inicio - 1;
  }else{
    $lim = 0;
  }
  if($fim > pg_numrows($rsNotifica)){
    $lim2 = pg_numrows($rsNotifica);
  }else{
    $lim2 = $fim;
  }
}else{
  $lim1 = 0;
  $lim2 = pg_numrows($rsNotifica);
}


$numrows =  pg_num_rows($rsNotifica);

for($indx=0;$indx < $numrows; $indx++) {
    
    $totvlrhis    = 0;
    $totvlrcor    = 0;
    $totjuros     = 0;
    $totmulta     = 0;
    $totdesconto  = 0;
    $tottotal     = 0;
    $total_recibo = 0;
    $descranos    = '';
    
    db_fieldsmemory($rsNotifica,$indx);

    if ( $oParam->k102_tipoemissao == 3 ) {
        	
      $sSqlConsultaPaga  = " select distinct    															";
      $sSqlConsultaPaga .= "  	    case   																	";
      $sSqlConsultaPaga .= "  	      when arrematric.k00_matric is not null then 'M-'||k00_matric  		";
      $sSqlConsultaPaga .= "  	      else case  															";
      $sSqlConsultaPaga .= "  	   			 when arreinscr.k00_inscr is not null then 'I-'||k00_inscr		";
      $sSqlConsultaPaga .= "  	   			 else 'C'||arrenumcgm.k00_numcgm							    ";
      $sSqlConsultaPaga .= "  	   		   end 																";
      $sSqlConsultaPaga .= "  	    end as origem,															";
      $sSqlConsultaPaga .= "  	    z01_nome																";
      $sSqlConsultaPaga .= "   from arrepaga																";
      $sSqlConsultaPaga .= "   		inner join notidebitos on notidebitos.k53_numpre = arrepaga.k00_numpre  ";
      $sSqlConsultaPaga .= "   						   	  and notidebitos.k53_numpar = arrepaga.k00_numpar  ";
      $sSqlConsultaPaga .= "   		left  join arrematric  on arrematric.k00_numpre  = arrepaga.k00_numpre  ";
      $sSqlConsultaPaga .= "   		left  join arreinscr   on arreinscr.k00_numpre   = arrepaga.k00_numpre  ";
      $sSqlConsultaPaga .= "   		left  join arrenumcgm  on arrenumcgm.k00_numpre  = arrepaga.k00_numpre  ";
      $sSqlConsultaPaga .= "   		inner join cgm 		   on cgm.z01_numcgm		 = arrepaga.k00_numcgm  ";
      $sSqlConsultaPaga .= "  where notidebitos.k53_notifica = {$notifica} 								    ";
      
      $rsConsultaPaga    = db_query($sSqlConsultaPaga);
      $iLinhasPaga		 = pg_num_rows($rsConsultaPaga);
        
      if ( $iLinhasPaga > 0 ) {
      	
       	for ($iInd=0; $iInd < $iLinhasPaga; $iInd++ ) {
       		
       	  $oPaga = db_utils::fieldsMemory($rsConsultaPaga,$iInd);

		  $aListaNotifica[$notifica]['Origem'] 	= $oPaga->origem;
		  $aListaNotifica[$notifica]['Nome'] 	= $oPaga->z01_nome;
       	  
       	}
       	
     	continue;
     	
      }
      
    }
    
    
    
    
    $sqljapagou = "	select *
    				  from notidebitos
						   inner join debitos  on debitos.k22_numpre = notidebitos.k53_numpre 
						   					  and debitos.k22_numpar = notidebitos.k53_numpar 
						   					  and debitos.k22_data   = '$k60_datadeb'
						   inner join arretipo on arretipo.k00_tipo  = debitos.k22_tipo
						   inner join arrecad  on arrecad.k00_numpre = notidebitos.k53_numpre 
						   					  and arrecad.k00_numpar = notidebitos.k53_numpar
						    where notidebitos.k53_notifica = $notifica
    						limit 1 ";
    						
    $resultjapagou = db_query($sqljapagou);
    
    if ($resultjapagou == false) {
      
      $oParms             = new stdClass();
      $oParms->sqlJaPagou = $sqljapagou;
      $sMsg               = _M('tributario.notificacoes.cai4_emitenotificacaotxt.problemas_verificar_se_pagou', $oParms);
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      exit;
    }
    if (pg_numrows($resultjapagou) == 0) {
      continue;
    }
    $numcgm = @$j01_numcgm;
    $matric = @$j01_matric;
    $inscr  = @$q02_inscr;
    
    if($matric != ''){
      
      $xmatinsc = " k22_matric = ".$matric." and ";
      $xmatinsc22 = " k22_matric = ".$matric." and ";
      $xmatinsc00 = " k00_matric = ".$matric." and ";
      $matinsc = "sua matrícula n".chr(176)." ".$matric;
    }else if($inscr != ''){
      $xmatinsc = " k22_inscr = ".$inscr." and ";
      $xmatinsc22 = " k22_inscr = ".$inscr." and ";
      $xmatinsc00 = " k00_inscr = ".$inscr." and ";
      $matinsc = "sua inscrição n".chr(176)." ".$inscr;
    }else{
      $xmatinsc = " k22_numcgm = ".$numcgm." and ";
      $xmatinsc22 = " k22_numcgm = ".$numcgm." and ";
      $xmatinsc00 = " k00_numcgm = ".$numcgm." and ";
      $matinsc = "V.Sa.";
    }
    $matricula = $matric;
    $inscricao = $inscr;
    $jtipos = '';
    $num2 = 0;
    $k00_descr = ''; 
    if ($tipos != ''){
      $jtipos = ' k22_tipo in ('.$tipos.') and ';
      $sql2 = "select k22_tipo,k00_descr,sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor 
      from debitos 
      inner join arretipo on k00_tipo = k22_tipo 
      where $xmatinsc k22_tipo not in ($tipos) and k22_data = '$k60_datadeb' and k22_dtvenc < '" . date("Y-m-d",db_getsession("DB_datausu")) . "' group by k22_tipo,k00_descr";
      $result2 = db_query($sql2);
      $num2 = pg_numrows($result2);
    }
    
    
    $sqlproced = "
							    select distinct proced.v03_codigo, proced.v03_dcomp
							      from notidebitos
							           inner join divida on v01_numpre = k53_numpre and v01_numpar = k53_numpar
							           inner join proced on v03_codigo = v01_proced
							     where k53_notifica = $notifica";
    $resultaproced = db_query($sqlproced);
    $virgula = '';
    global $procedencias;
    $procedencias = '';
    for($i = 0;$i < pg_numrows($resultaproced);$i++){
      db_fieldsmemory($resultaproced,$i);
      $procedencias .= $virgula.$v03_dcomp;
      $virgula = ', ';
    }
    
    $cgm = $z01_numcgm;
    $sql1 = "select k22_tipo,k00_descr,sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor 
               from debitos  
                    inner join arretipo on k00_tipo = k22_tipo 
              where $xmatinsc $jtipos k22_data = '$k60_datadeb' and k22_dtvenc < '" .  date("Y-m-d",db_getsession("DB_datausu")) . "' 
              group by k22_tipo,k00_descr";
    $result1 = db_query($sql1);
    
    if ($k60_tipo == 'M'){
      $cgm_noti  = '';
      $inscricao = '';
      
      $sqlpropri = "select z01_nome, z01_cgccpf,z01_uf,z01_cep,z01_munic,codpri, nomepri, j39_numero, j39_compl, 
                           j34_setor, j34_quadra, j34_lote, j34_zona, j34_area, j01_tipoimp, j13_descr, 
                           z01_cgmpri as z01_numcgm 
                      from proprietario 
                     where j01_matric = $matric";
      
      $resultpropri = db_query($sqlpropri);
      if (pg_numrows($resultpropri) > 0) {
        db_fieldsmemory($resultpropri,0);
        $nomepri        = ucwords(strtolower($nomepri));
        $j13_descr      = ucwords(strtolower($j13_descr));
        $z01compl  = $j39_compl;
        $z01ender  = $nomepri;
        $z01numero = $j39_numero;
      }
      
      $sqlconfig= "select munic,uf,cep from db_config where prefeitura is true"; 
      
      $resultconfig=db_query($sqlconfig);
      if (pg_numrows($resultconfig)>0){
         db_fieldsmemory($resultconfig,0);
         $z01uf    = $uf;
         $z01cep   = $cep;
         $z01munic = $munic;
      }
      
      $sqlender = "select fc_iptuender($matric)";
      $resultender = db_query($sqlender);
      db_fieldsmemory($resultender,0);
      
      $endereco = split("#",$fc_iptuender);

      if (sizeof($endereco) < 7) {
        $z01_ender    = "";
        $z01_numero   = 0;
        $z01_compl    = "";
        $z01_bairro   = "";
        $z01_munic    = "";
        $z01_uf       = "";
        $z01_cep      = "";
        $z01_cxpostal = "";
      } else {
        $z01_ender    = $endereco[0];
        $z01_numero   = $endereco[1];
        $z01_compl    = $endereco[2];
        $z01_bairro   = $endereco[3];
        $z01_munic    = $endereco[4];
        $z01_uf       = $endereco[5];
        $z01_cep      = $endereco[6];
        $z01_cxpostal = $endereco[7];
        
      }
      
    } elseif ($k60_tipo == 'I') {
      
      $imprime    = "INSCRICAO: $q02_inscr";
      $cgm_noti   = '';
      $inscricao  = $q02_inscr;
      $matricula  = '';
      $sqlempresa = "select * from empresa where q02_inscr = $q02_inscr";
      $resultempresa = db_query($sqlempresa);
      if (pg_numrows($resultempresa) > 0) {
        db_fieldsmemory($resultempresa,0);
      
         $z01compl  = $z01_compl;
         $z01ender  = $z01_ender;
         $z01numero = $z01_numero;
         $z01uf     = $z01_uf;
         $z01cep    = $z01_cep;
         $z01munic  = $z01_munic;
      }
    } else {
      $cgm_noti  = $cgm;
      $inscricao = '';
      $matricula = '';
      $sqlender  = "select z01_ender,z01_cgccpf, z01_numero, z01_compl, z01_bairro, z01_munic, z01_uf, z01_cep, z01_cxpostal from cgm where z01_numcgm = $cgm";
      $resultender = db_query($sqlender);
        if (pg_numrows($resultender) > 0) {
            db_fieldsmemory($resultender,0,true);
            
              $z01compl  = $z01_compl;
              $z01ender  = $z01_ender;
              $z01numero = $z01_numero;
              $z01uf     = $z01_uf;
              $z01cep    = $z01_cep;
              $z01munic  = $z01_munic;
           }

    }
    
    $impostos = '';
    $virgula  = '';
    $xvalor   = 0;
    
    
    $impostos  = 'DÍVIDA ATIVA' ;
    $impostos2 = '';
    $virgula2  = '';
    $xvalor2   = 0;
    $sql = "select nomeinst,bairro,cgc,cep,ender,upper(munic) as munic,uf,numero from db_config where codigo = ".db_getsession("DB_instit");
    $result = db_query($sql);
   //echo $sql;
    db_fieldsmemory($result,0);
    $dia = date('d',strtotime($k60_datadeb));
    $mes = db_mes(date('m',strtotime($k60_datadeb)));
    $ano = date('Y',strtotime($k60_datadeb));
    $data_ext = strtolower($munic).", ".$dia." de ".$mes." de ".$ano;
    $cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
    $cldb_layouttxt->limpaCampos();
    $cldb_layouttxt->setCampo("cod_lista",      $lista);
    $cldb_layouttxt->setCampo("notificacao",    $notifica);
    $cldb_layouttxt->setCampo("matricula",      $matricula);
    $cldb_layouttxt->setCampo("cgm_noti",       $cgm_noti);
    $cldb_layouttxt->setCampo("inscricao",      $inscricao);
    $cldb_layouttxt->setCampo("data_ext",       $data_ext);
    $cldb_layouttxt->setCampo("nome_sacado",    $z01_nome);
    $cldb_layouttxt->setCampo("cgccpf",         $z01_cgccpf);
    $cldb_layouttxt->setCampo("cgm_sacado",     $z01_numcgm);
    $cldb_layouttxt->setCampo("munic_sacado",   $z01_munic);
    $cldb_layouttxt->setCampo("bairro_sacado",  $z01_bairro);
    $cldb_layouttxt->setCampo("compl_sacado",   $z01_compl);
    $cldb_layouttxt->setCampo("lograd_corresp", $z01_ender);
    $cldb_layouttxt->setCampo("num_ender",      $z01_numero);
    $cldb_layouttxt->setCampo("uf_sacado",      $z01_uf);
    $cldb_layouttxt->setCampo("cep_sacado",     $z01_cep);
    $cldb_layouttxt->setCampo("munic_imovel",   $z01munic);
    $cldb_layouttxt->setCampo("compl_imovel",   $z01compl);
    $cldb_layouttxt->setCampo("log_imovel",     $z01ender);
    $cldb_layouttxt->setCampo("num_imovel",     $z01numero);
    $cldb_layouttxt->setCampo("uf_imovel",      $z01uf);
    $cldb_layouttxt->setCampo("cep_imovel",     $z01cep);
    $cldb_layouttxt->setCampo("munic_cedente",  $munic);
    $cldb_layouttxt->setCampo("bairro_cedente", $bairro);
    $cldb_layouttxt->setCampo("compl_cedente",  '');
    $cldb_layouttxt->setCampo("uf_cedente",     $uf);
    $cldb_layouttxt->setCampo("cep_cedente",    $cep);
    $cldb_layouttxt->setCampo("num_cedente",    $numero);
    $cldb_layouttxt->setCampo("lograd_cedente", $ender);

    $sqltipodebito = "	select distinct k22_tipo as tipo_debito
                          from notidebitos
                               inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
                               inner join arretipo on k00_tipo = k22_tipo
                         where k53_notifica = $notifica
                         order by k22_tipo";
     $resulttipodebito = db_query($sqltipodebito) or die($sqltipodebito);
     if ($resulttipodebito == false) {
       
        $oParms                = new stdClass();
        $oParms->sqlTipoDebito = $sqltipodebito;
        $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.problemas_gerar_select_recibo', $oParms);
        db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
       exit;
    }
    db_fieldsmemory($resulttipodebito, 0);
    $sqlrecibo  = " select k53_numpre, k53_numpar, k22_tipo, sum(k22_juros) as k22_juros, sum(k22_multa) as k22_multa, fc_calcula ";
    $sqlrecibo .= "   from ( ";
    $sqlrecibo .= "     select distinct k53_numpre, k53_numpar,k22_tipo,k22_juros,k22_multa, ";
    $sqlrecibo .= "            fc_calcula(k53_numpre, k53_numpar, 0, '$db_datausu', '$db_datausu',".db_getsession("DB_anousu").")";
    $sqlrecibo .= "       from notidebitos ";
    $sqlrecibo .= "            inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'";
    $sqlrecibo .= "            inner join arretipo on k00_tipo = k22_tipo ";
    $sqlrecibo .= "      where k53_notifica = $notifica";
    $sqlrecibo .= "        and exists ( select 1                                                ";
    $sqlrecibo .= "                       from arrecad                                          ";
    $sqlrecibo .= "                      where arrecad.k00_numpre = debitos.k22_numpre          ";
    $sqlrecibo .= "                        and arrecad.k00_numpar = debitos.k22_numpar limit 1) ";
    $sqlrecibo .= ") as x group by k53_numpre, k53_numpar, k22_tipo, fc_calcula";
    $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);
   
    $cadtipoparc = 0;
    
    $sqltipoparc = "select 	* 
    from tipoparc 
    inner join cadtipoparc 
    on cadtipoparc = k40_codigo
    where maxparc = 1 and '"
    . date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini and 
    '" . date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim";

    $resulttipoparc = db_query($sqltipoparc);
    if (pg_numrows($resulttipoparc) > 0) {
      db_fieldsmemory($resulttipoparc,0);
    } else {
      $k40_todasmarc = false;
    }
    
    $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
    $resulttipoparcdeb = db_query($sqltipoparcdeb);
    $passar = false;
    if (pg_numrows($resulttipoparcdeb) == 0) {
      $passar = true;
    } else {
    	
      $sqltipoparcdeb = "select * from cadtipoparcdeb where k41_cadtipoparc = $cadtipoparc and k41_arretipo = $tipo_debito";
      $resulttipoparcdeb = db_query($sqltipoparcdeb);

      if (pg_numrows($resulttipoparcdeb) > 0) {
        $passar = true;
      }
    }
    
    // TOTALPORANO
    $sqlanos = "select case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end as k22_ano,
									     sum(k22_vlrcor) as k22_vlrcor, 
									     sum(k22_juros) as k22_juros, 
									     sum(k22_multa) as k22_multa, 
									     sum(k22_vlrcor+k22_juros+k22_multa) as k22_total 
							    from notidebitos
									     inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
									     inner join arretipo on k00_tipo = k22_tipo
							   where k53_notifica = $notifica
							   group by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end";
							   
    $resultcompara1 = db_query($sqlanos) or die($sqlanos);
    if($matric != ''){
      
      $sqlanostipos = "select extract (year from k00_dtoper) as k22_ano, sum(k00_valor) as k00_valor 
									       from arrematric 
												      inner join arrecad on arrematric.k00_numpre = arrecad.k00_numpre 
												      inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo 
									      where k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ."
									        and k00_matric = $matric 
									      group by extract (year from k00_dtoper) ";
      
    }else if($inscr != ''){
      
      $sqlanostipos = "	
      select extract (year from k00_dtoper) as k22_ano, sum(k00_valor) as k00_valor 
      from arreinscr 
      inner join arrecad on arreinscr.k00_numpre = arrecad.k00_numpre 
      inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo 
      where k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ."
      and k00_inscr = $inscr 
      group by extract (year from k00_dtoper)
      ";
      
      
    }else{
      
      $sqlanostipos = "	 select extract (year from k00_dtoper) as k22_ano, sum(k00_valor) as k00_valor 
						   from arrenumcgm 
					        	inner join arrecad  on arrenumcgm.k00_numpre = arrecad.k00_numpre 
					      		inner join arretipo on arretipo.k00_tipo	 = arrecad.k00_tipo 
					      where k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ."
					        and arrenumcgm.k00_numcgm = $numcgm 
					   group by extract (year from k00_dtoper)";
      
      
    }
    
    
    $resultcompara2 = db_query($sqlanostipos) or die($sqlanostipos);
    
    if (pg_numrows($resulttipoparc) == 0 or $passar == false or ($k40_todasmarc == 't'?pg_numrows($resultcompara1) <> pg_numrows($resultcompara2):false)) {
      $desconto   = 0;
    } else {
      $desconto   = $k40_codigo;
      $rsDesconto = db_query(" select coalesce(descmul,1), coalesce(descjur,1) from tipoparc where cadtipoparc = $desconto ");
      if (pg_numrows($rsDesconto) > 0) {
        db_fieldsmemory($rsDesconto,0);						
      }
    }
    
    $k00_codbco 	    = 0;
    $k00_codage 	    = "";
    $tipo_arrecadacao = 0;
    $tipo_cobranca    = 0;
    $dt_venc          = substr($db_datausu,0,10);
	  $tot_desc 	      = 0 ;
    
		try {
		  $oRecibo = new recibo(2, null, 23);
		} catch ( Exception $eException ) {
		  db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
		  exit;
		}	  
	  
    for ($regrecibo = 0; $regrecibo < pg_numrows($resultrecibo); $regrecibo++) {
      db_fieldsmemory($resultrecibo, $regrecibo);
      
      if (isset($passou_por_aqui)&&$passou_por_aqui==true){
        $desconto =  recibodesconto($k53_numpre,$k53_numpar,$k22_tipo,$k22_tipo, "", pg_numrows($resultrecibo), pg_numrows($resultrecibo),$dt_venc);            
	    if ($desconto !=0){
	    	
          $result_desc  = db_query("select * from tipoparc where cadtipoparc = $desconto");
          $numrows_desc = pg_numrows($result_desc);
        
          if ($numrows_desc>0){
            db_fieldsmemory($result_desc,0);
            if (isset($descmul)&&isset($descjur)){
              $juros 	 = substr($fc_calcula,28,13);
              $multa 	 = substr($fc_calcula,41,13);
              $nTotalMulta += $multa;
              $nTotalJuros += $juros;
              $tot_desc += (($juros/100)*$descjur);
              $tot_desc += (($multa/100)*$descmul);
            }
          }
        }
	  }

      try {
        $oRegraEmissao = new regraEmissao($k22_tipo,6,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
      } catch (Exception $eExeption){
      	db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
      	exit;      	
      }

      try {       
                         
        $oRecibo->addNumpre($k53_numpre,$k53_numpar);
        $oRecibo->setDescontoReciboWeb($k53_numpre,$k53_numpar,$desconto);
      } catch ( Exception $eException ) {
        db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
        exit;
      }        
      
      if ($oRegraEmissao->isArrecadacao()) {
      	$tipo_arrecadacao++;
      } else {
        $tipo_cobranca++;      	
      }
      
    }
    
    if ($tipo_arrecadacao>0&&$tipo_cobranca>0) {
      
      $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.layout_tipos_debitos_diferentes');
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      exit;
    }

    db_inicio_transacao();
    
    try {      
                 
      $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
      $oRecibo->setDataRecibo($db_datausu);
      $oRecibo->setDataVencimentoRecibo($db_datausu);
      $oRecibo->emiteRecibo();
      $k03_numpre = $oRecibo->getNumpreRecibo();
    } catch ( Exception $eException ) {
      db_fim_transacao(true);
    	db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
      exit;
    }    
    
    db_fim_transacao();
    
    $sql = "select r.k00_numcgm,
                   r.k00_receit,
                   r.k00_dtpaga,
                   case when taborc.k02_codigo is null 
                        then tabplan.k02_reduz 
                        else taborc.k02_codrec 
                   end as codreduz,
                   t.k02_descr,
                   t.k02_drecei,
                 /*  r.k00_dtoper as k00_dtoper,*/
                   sum(r.k00_valor) as valor
              from recibopaga r
                   inner join      tabrec t on t.k02_codigo       = r.k00_receit 
                   inner join      tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                   left outer join taborc   on t.k02_codigo       = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu")."
                   left outer join tabplan  on t.k02_codigo       = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
             where r.k00_numnov = ".$k03_numpre."
          group by /*r.k00_dtoper,*/
                   r.k00_dtpaga,
                   r.k00_receit,
                   t.k02_descr,
                   t.k02_drecei,
                   r.k00_numcgm,
                   codreduz";
    $DadosPagamento = db_query($sql) or die($sql);
    $datavencimento = pg_result($DadosPagamento,0,"k00_dtpaga");
    $total_recibo = 0;
    for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
      $total_recibo += pg_result($DadosPagamento,$i,"valor");
    }
    pg_result_seek($resultcompara1,0);
    for ($itotal = 0; $itotal < pg_num_rows($resultcompara1);$itotal++){
   
      db_fieldsmemory($resultcompara1,$itotal);
      $totvlrcor   += $k22_vlrcor;
      $totjuros    += $k22_juros;
      $totmulta    += $k22_multa;
      $tottotal    += $k22_total;
    }
    $valordesconto = $tottotal - $total_recibo;
    $sqlinstit     = "select nomeinst,ender,numero,munic,email,telef,uf,logo,to_char(tx_banc,'9.99') as tx_banc from db_config where codigo = ".db_getsession("DB_instit");
    $DadosInstit   = db_query($sqlinstit) or die($sqlinstit);

    //cria codigo de barras e linha digitável
    
    $taxabancaria = pg_result($DadosInstit,0,"tx_banc");
    $src		  = pg_result($DadosInstit,0,'logo');
    $db_nomeinst  = pg_result($DadosInstit,0,'nomeinst');
    $db_ender     = pg_result($DadosInstit,0,'ender');
    $db_munic     = pg_result($DadosInstit,0,'munic');
    $db_uf        = pg_result($DadosInstit,0,'uf');
    $db_telef     = pg_result($DadosInstit,0,'telef');
    $db_email     = pg_result($DadosInstit,0,'email');
    
    $total_recibo += $taxabancaria;
    
    if ( $total_recibo == 0 ){
      
      $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.recebido_com_valor_zerado');
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    }
    
    $valor_parm = $total_recibo; 

    //dados ficha compensacao 
    
    if ($somenteparc == false and $somenteiptu == false) {
         $historico		= $descrtipo . " - " . $descranos;
    } elseif ($somenteparc == false and $somenteiptu == true) {
        $historico		= $descranos;
    } else {
        $historico		= $descranos;
    }       
        
   $db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
   
   $numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
   $numpre = $numpre . db_CalculaDV($numpre,11);        
   
   $sqlvalor = "select k00_tercdigrecnormal, 
   					   k00_msgrecibo 
   				  from arretipo 
   					   inner join listatipos on k00_tipo = k62_tipodeb
   				 where k62_lista = $lista
   				 limit 1";

   $resultvalor = db_query($sqlvalor) or die($sqlvalor);
   
   db_fieldsmemory($resultvalor,0);
   if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){
     
     $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.configure_terceiro_digito');
     db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   }

   try {
     $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);
   } catch ( Exception $eExeption ){
     db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");   	
   }
   
   $codigobarras   = $oConvenio->getCodigoBarra();
   $linhadigitavel = $oConvenio->getLinhaDigitavel();   
   
   if ( $tipo_arrecadacao > 0 ) { //Se for arrecadação
     
     $agen            = '';
     $conta           = '';
     $agencia_cedente = '';
     $carteira        = '';
     $sequencia       = '';
     $codigo_cedente  = ''; 
     $digito_cedente  = ''; 
   } elseif ($tipo_cobranca > 0 ) {
   	
     $k00_descr           = "";
     $agencia_cedente   	= $oConvenio->getAgenciaCedente();
     $carteira          	= $oConvenio->getCarteira();
     $sequencia         	= "";
     $codigo_cedente      = $oConvenio->getCedente();
     $digito_cedente      = $oConvenio->getDigitoCedente();
    
  }
     
    $ident_tipo_ii = 'Imóvel';

    $numpre   = db_sqlformatar($k03_numpre,8,'0').'000999';
    $numpre   = $numpre . db_CalculaDV($numpre,11);        
    $especie  = "R$";
    $tottotal = 0;
    
    if ( $oParam->k102_tipoemissao == 2 ) {
      $sInnerArrecad = " inner join arrecad  on arrecad.k00_numpre = notidebitos.k53_numpre 
					   	    		  	 	                and arrecad.k00_numpar = notidebitos.k53_numpar ";
    } else {
      $sInnerArrecad = "";	
    }
    
    
    if ($somenteparc == false and $somenteiptu == false) {
          
     $sqlanos = "	select case 
                             when k22_exerc is null then extract (year from k22_dtoper) 
                             else k22_exerc 
                           end as k22_ano,
          				       k22_numpar,arretipo.k00_descr
          			   from notidebitos
          				      inner join debitos on k22_numpre = k53_numpre 
          				   		                	and k22_numpar = k53_numpar 
          				   					            and k22_data   = '$k60_datadeb'
          				      inner join arretipo on k00_tipo   = k22_tipo
          				      {$sInnerArrecad}
          			  where k53_notifica = $notifica
         		      group by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end,
          				      k22_numpar,arretipo.k00_descr
          		    order by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end ";
          		  
          $resultanos = db_query($sqlanos) or die($sqlanos);
          if ($resultanos == false) {
            
            $oParms = new stdClass();
            $oParms->sqlAnos = $sqlanos;
            $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.problemas_gerar_totais_ano', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }
  
          
          $descranos = "";
          $descricao = "";
          $relanos 	 = 0;
          $hifen     = "";
          
          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {
          	
            db_fieldsmemory($resultanos,$totano);
            
           if ($descricao != $k00_descr) {
              $descranos .= ($descranos == ""?"":" / ") . $k00_descr . '-';
              $descricao = $k00_descr;
            }
            
            if ($relanos != $k22_ano) {
              $descranos .= ' '.$k22_ano.'- ';
              $relanos = $k22_ano;
              $hifen = "";
            }
            
            $descranos .= $hifen.$k22_numpar;
            $hifen = ",";
            
            
            
          }
          
        } elseif ($somenteparc == false and $somenteiptu == true) {
          
          $sqlanos = "	select case 
          						 when k22_exerc is null then extract (year from k22_dtoper) 
          						 else k22_exerc 
          					   end as k22_ano,
          					   k22_numpar,
          					   arretipo.k00_descr,
          					   count(*)
          				  from notidebitos
          					   inner join debitos  on k22_numpre = k53_numpre 
          					   				      and k22_numpar = k53_numpar 
          					   				      and k22_data   = '$k60_datadeb'
          					   inner join arretipo on k00_tipo   = k22_tipo
        					   {$sInnerArrecad}
        					   
          				 where k53_notifica = $notifica
          			  group by case 
          			  			 when k22_exerc is null then extract (year from k22_dtoper) 
          			  			 else k22_exerc 
          			  		   end,
          					   k22_numpar,
          					   arretipo.k00_descr";
          					   
          $resultanos = db_query($sqlanos) or die($sqlanos);
          
          if ($resultanos == false) {
            
            $oParms = new stdClass();
            $oParms->sqlAnos = $sqlanos;
            $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.problemas_gerar_totais_ano', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }
          
          $descranos = "";
          $descricao = "";
          $relanos 	 = 0;
          $hifen 	 = "";
          
          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {
          	
            db_fieldsmemory($resultanos,$totano);
            
            if ($descricao != $k00_descr) {
              $descranos .= ($descranos == ""?"":" / ") . $k00_descr . '- ';
              $descricao = $k00_descr;
            }
            
            if ($relanos != $k22_ano) {
              $descranos .= ' '.$k22_ano.'- ';
              $relanos = $k22_ano;
              $hifen = "";
            }
            
            $descranos .= $hifen.$k22_numpar;
            $hifen = ",";
            
          }
          
        } else {
          
          $sqlanos = "select  distinct arretipo.k00_descr,
					          v07_parcel,
					          k22_numpar,
					          count(*)
          				 from notidebitos
          					  inner join debitos  on k22_numpre		  = k53_numpre 
          					  					 and k22_numpar		  = k53_numpar 
          					  					 and k22_data		  = '$k60_datadeb'
          					  inner join arretipo on k00_tipo   	  = k22_tipo
          					  inner join termo	  on termo.v07_numpre = debitos.k22_numpre
                			            					  
          				where k53_notifica = $notifica
          				and exists ( select 1 
                                 from arrecad 
                                where k00_numpre = k22_numpre 
                                  and k00_numpar = k22_numpar limit 1)
          			 group by arretipo.k00_descr,
					          v07_parcel,
					          k22_numpar
          			 order by arretipo.k00_descr,
					          v07_parcel,
					          k22_numpar ";
		  			          
          $resultanos = db_query($sqlanos) or die($sqlanos);
          
          if ($resultanos == false) {
            
            $oParms = new stdClass();
            $oParms->sqlAnos = $sqlanos;
            $sMsg = _M('tributario.notificacoes.cai4_emitenotificacaotxt.problemas_gerar_totais_ano', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }
          
          $descranos = "";
          $descricao = "";
          $parcel = 0;
          
          $hifen = "";
          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {
            db_fieldsmemory($resultanos,$totano);
            
            if ($descricao != $k00_descr) {
              $descranos .= ($descranos == ""?"":" / ") . $k00_descr . ': ';
              $descricao = $k00_descr;
            }
            
            if ($parcel != $v07_parcel) {
              $descranos .= ' '.$v07_parcel.'- ';
              $parcel = $v07_parcel;
              $hifen = "";
            }
            
            $descranos .= $hifen.$k22_numpar;
            $hifen = ",";
            
          }
          
        }
    
    //Dividos o historico em 5 partes.
    
    $historico1  = substr($descranos,0,60);
    $historico2  = substr($descranos,60,60);
    $historico3  = substr($descranos,120,60);
    $historico4  = substr($descranos,180,60);
    $historico5  = substr($descranos,240,60);
    $historico6  = substr($descranos,300,60);
    $historico7  = substr($descranos,360,60);
    $historico8  = substr($descranos,420,60);
    $historico9  = substr($descranos,480,60);
    $historico10 = substr($descranos,540,60);
    $historico11 = substr($descranos,600,60);
    $historico12 = substr($descranos,660,60);
    $historico13 = substr($descranos,720,60);
    $historico14 = substr($descranos,780,60);
    $historico15 = substr($descranos,840,60);
    

    $nTotalMulta = 0;   
    $nTotalDesconto = 0;
    $sSqlValoresRecibo  = "SELECT k00_hist, ";
    $sSqlValoresRecibo .= "       sum(k00_valor) as valor";
    $sSqlValoresRecibo .= "  from recibopaga ";
    $sSqlValoresRecibo .= " where k00_numnov = {$k03_numpre}";
    $sSqlValoresRecibo .= " group by k00_hist";
    $rsValoresRecibo    = db_query($sSqlValoresRecibo);
    $iTotalLinhasRecibo = pg_num_rows($rsValoresRecibo); 
    if ($iTotalLinhasRecibo > 0) {
      for ($iHist = 0; $iHist < $iTotalLinhasRecibo; $iHist++) {
        
        $oLinhaRecibo = db_utils::fieldsMemory($rsValoresRecibo, $iHist);
        switch ($oLinhaRecibo->k00_hist) {
          
          case 918:
            
            $nTotalDesconto += abs($oLinhaRecibo->valor);
            break;

          case 400:
            
            $nTotalMulta += abs($oLinhaRecibo->valor);;
            break;
        }
      }
    }
    if ($digito_cedente != "") {
      $codigo_cedente .= "-{$digito_cedente}";
    }
    $nValorBruto = $total_recibo+$nTotalMulta;
    $cldb_layouttxt->setCampo("linha_digitavel",$linhadigitavel);
    $cldb_layouttxt->setCampo("cod_barras",$codigobarras);
    $cldb_layouttxt->setCampo("data_processamento",date("d/m/Y",db_getsession("DB_datausu")));
    $cldb_layouttxt->setCampo("vencimento",db_formatar($datavencimento,"d"));
    $cldb_layouttxt->setCampo("data_documento",date("d/m/Y",db_getsession("DB_datausu")));
    $cldb_layouttxt->setCampo("valor_documento",number_format($total_recibo,2,",","."));
    $cldb_layouttxt->setCampo("valor_cobrado",number_format($total_recibo,2,",","."));
    $cldb_layouttxt->setCampo("valor_desconto", number_format($nTotalDesconto ,2,",","."));
    $cldb_layouttxt->setCampo("valor_multa", number_format($nTotalMulta,2,",","."));
    $cldb_layouttxt->setCampo("agencia_cedente",$agencia_cedente);
    $cldb_layouttxt->setCampo("cedente",$nomeinst);
    $cldb_layouttxt->setCampo("cod_cedente", $codigo_cedente);
    $cldb_layouttxt->setCampo("carteira",$carteira);
    $cldb_layouttxt->setCampo("sequencia",$sequencia);
    $cldb_layouttxt->setCampo("nosso_numero",str_pad(@$numpre,17,"0",STR_PAD_LEFT));
    $cldb_layouttxt->setCampo("inst_linha1" ,$historico1);
    $cldb_layouttxt->setCampo("inst_linha2" ,$historico2);
    $cldb_layouttxt->setCampo("inst_linha3" ,$historico3);
    $cldb_layouttxt->setCampo("inst_linha4" ,$historico4);
    $cldb_layouttxt->setCampo("inst_linha5" ,$historico5);
    $cldb_layouttxt->setCampo("inst_linha6" ,$historico6);
    $cldb_layouttxt->setCampo("inst_linha7" ,$historico7);
    $cldb_layouttxt->setCampo("inst_linha8" ,$historico8);
    $cldb_layouttxt->setCampo("inst_linha9" ,$historico9);
    $cldb_layouttxt->setCampo("inst_linha10",$historico10);
    $cldb_layouttxt->setCampo("inst_linha11",$historico11);
    $cldb_layouttxt->setCampo("inst_linha12",$historico12);
    $cldb_layouttxt->setCampo("inst_linha13",$historico13);
    $cldb_layouttxt->setCampo("inst_linha14",$historico14);
    $cldb_layouttxt->setCampo("inst_linha15",$historico15);
    $cldb_layouttxt->setCampo("inst_linha16",$k00_msgrecibo);
    $cldb_layouttxt->setCampo("valor_bruto",$nValorBruto);
    
    $cldb_layouttxt->geraDadosLinha();
    db_atutermometro($indx, $numrows, 'termometro');
    flush();
  }
  unset($cldb_layouttxt);
  
  $cldb_layouttxt 	 = new db_layouttxt(12, $nomearqlayout, "");
  $cldb_layoutcampos = new cl_db_layoutcampos;

  $dbwhere = " db52_layoutlinha = 115";
  $sql = $cldb_layoutcampos->sql_query(null,"
    db52_nome,
    db52_descr,
    db52_tamanho,
    db52_layoutformat,
    db52_posicao as db52_posicao_inicial,
    db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end) as db52_posicao_final",
    "db52_posicao",$dbwhere);

  $result = $cldb_layoutcampos->sql_record($sql);
  
  $cldb_layouttxt->setCampoTipoLinha(3); // 3-Registro
  for ($x=0; $x<$cldb_layoutcampos->numrows; $x++) {
    db_fieldsmemory($result, $x);
    $cldb_layouttxt->setCampo("posicao_inicial", $db52_posicao_inicial);
    $cldb_layouttxt->setCampo("posicao_final",   $db52_posicao_final);
    $cldb_layouttxt->setCampo("nome_campo",      $db52_nome);
    $cldb_layouttxt->setCampo("tamanho",         $db52_tamanho);
    $cldb_layouttxt->setCampo("descricao",       $db52_descr);
    $cldb_layouttxt->geraDadosLinha();

  }
 
  
  if ( $oParam->k102_tipoemissao == 3 && !empty($aListaNotifica) ) {
  
    $head1 = "Relatório Notificações não Emitidas "; 
    $head2 = "Lista nº {$lista}					  "; 
  
    $pdf1 = new PDF();
    $pdf1->Open(); 
    $pdf1->AliasNbPages(); 
    $pdf1->setfillcolor(235);
    $pdf1->AddPage();
    $iAlt = 5;
  
  
    $pdf1->SetFont("Arial","B",7);
    $pdf1->Cell(35 ,$iAlt,"Notificações"		,1,0,"C",1);
    $pdf1->Cell(35 ,$iAlt,"Origem"			    ,1,0,"C",1);
    $pdf1->Cell(120,$iAlt,"Nome do Contribuinte",1,1,"C",1);
    $pdf1->SetFont("Arial","",7);

    foreach ( $aListaNotifica as $iNotifica => $aDadosNotif ){
  	
  	  $pdf1->Cell(35 ,$iAlt,$iNotifica			   ,0,0,"C",0);
      $pdf1->Cell(35 ,$iAlt,$aDadosNotif['Origem'] ,0,0,"C",0);
      $pdf1->Cell(120,$iAlt,$aDadosNotif['Nome']   ,0,1,"L",0);
    
    }
  
    $sNotiNaoEmitida = "tmp/notificacoesdif_".date('His').".pdf";
    $pdf1->Output($sNotiNaoEmitida,false,true);
  	
  }
  
  
  echo "<script>";
  echo "  listagem  = '$nomearqdados#Download arquivo TXT (dados das notificações)|';	 ";
  echo "  listagem += '$nomearqlayout#Download arquivo TXT (layout das notificações)';	 ";
  
  if ( isset($sNotiNaoEmitida) && trim($sNotiNaoEmitida) != "") {
    echo "  listagem += '|$sNotiNaoEmitida#Download Relatório de Notificações não Emitidas';";
  }
  
  echo "  parent.js_montarlista(listagem,'form1');";
  echo "</script>";
}