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


if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_empresto_classe.php");
  include("classes/db_db_config_classe.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_conrelinfo_classe.php");
  include("dbforms/db_funcoes.php");
  
  $classinatura = new cl_assinatura;
  $clempresto   = new cl_empresto;
  $cldb_config  = new cl_db_config;
  $orcparamrel = new cl_orcparamrel;
  $clconrelinfo = new cl_conrelinfo;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $anousu  = db_getsession("DB_anousu");
  
}

$tipo_emissao='periodo';

$dt = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini= $anousu.'-01-01'; // data inicial do período
$dt_fin= $dt[1]; // data final do período
$textodt = $dt['texto'];

// calcula periodo do exercicio anterior para fechar os 12 meses
$anousu_ant  = db_getsession("DB_anousu")-1;
// se o ano atual é bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
$dt = split('-',$dt_fin);  // mktime -- (mes,dia,ano)
//$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]-365,$dt[0]));
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]-11,"01",$dt[0]));
$dt_fin_ant = $anousu_ant.'-12-31';

////////////////////////////////////////////////////////////////////

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev,db21_tipoinstit from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';

$temprefa   = false;
$temcamara  = false;
$temadmind  = false;
$flag_abrev = false;

for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
  if ($db21_tipoinstit == 1) {
    $temprefa=true;
  } elseif ($db21_tipoinstit == 2) {
    $temcamara=true;
  } elseif ($db21_tipoinstit == 5 or $db21_tipoinstit == 7) {
    $temadmind=true;
  }
}

$imprimirrps=false;

if (!isset($arqinclude)){
  
  if ($periodo == "6B" or $periodo == "3Q" or $periodo == "2S") {
    $imprimirrps=true;
    $tamanho=30;
  } else {
    $tamanho=60;
  }
  
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head2 = "INSTITUIÇÕES: ".$descr_inst;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

$dt1 = split('-',$dt_ini);
$dt2 = split('-',$dt_fin); 
if ($tipo_emissao=='periodo'){
  $textodt = strtoupper(db_mes($dt1[1]))." A ".strtoupper(db_mes($dt2[1]))." DE ";
  $head6   = $textodt.db_getsession("DB_anousu");
}else{
  $head6 = 'PERÍODO :'.$dt1[2].'/'.$dt1[1].'/'.$dt1[0].' à '.$dt2[2].'/'.$dt2[1].'/'.$dt2[0];
}  

////////////////////////////////////////////////////////////////////////////////////////////

$limite_maximo             = 0;
$limite_prudencial         = 0;
$pessoal_ativo_adicional   = 0;
$pessoal_inativo_adicional = 0;
$repasses_adicional        = 0;
$inativos_pensionistas     = 0;

$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(4,str_replace('-',',',$db_selinstit)));
if ($clconrelinfo->numrows > 0 ){
  for ($x=0;$x < $clconrelinfo->numrows;$x++){
    db_fieldsmemory($res,$x);
    if ($c83_codigo ==269 ){
      $limite_maximo  = $c83_informacao;
    } else if ($c83_codigo ==270){
      $limite_prudencial  = $c83_informacao;
    } else if ($c83_codigo ==289){
      $pessoal_ativo_adicional = $c83_informacao;
    } else if ($c83_codigo ==290){
      $pessoal_inativo_adicional = $c83_informacao;
    } else if ($c83_codigo ==291){
      $repasses_adicional = $c83_informacao;
    } else if ($c83_codigo ==292){
      $inativos_pensionistas = $c83_informacao;
    }
    
  }
  
}

if ($temprefa==true and	$temcamara==true) {
  $limite_maximo=60;
} elseif ($temprefa==true and	$temcamara==false) {
  $limite_maximo=54;
} elseif ($temprefa==false and	$temcamara==true) {
  $limite_maximo=6;
}

$limite_prudencial=$limite_maximo*95/100;

// calculo do exercicio atual
$sele_work  = 'c61_instit in ('.str_replace('-',', ',$db_selinstit).') ';
//$dt_ini = '2007-01-01';
//$dt_fin = '2007-01-05';


$instituicao = str_replace("-",",",$db_selinstit);

// INATIVOS E PENSIONISTAS COM RECURSOS VINCULADOS
$m_despesa[7]["estrut"]        = $orcparamrel->sql_parametro("4",7,"f",$instituicao,$anousu);
$m_despesa[7]["nivel"]         = $orcparamrel->sql_nivel("4",7,$anousu);
$m_despesa[7]["nivelexclusao"] = $orcparamrel->sql_nivelexclusao("4",7,"f",$instituicao,$anousu);
$m_despesa[7]["funcao"]        = $orcparamrel->sql_funcao("4",7,"f",$instituicao,$anousu);  
$m_despesa[7]["subfunc"]       = $orcparamrel->sql_subfunc("4",7,"f",$instituicao,$anousu);
$m_despesa[7]["recurso"]       = $orcparamrel->sql_recurso("4",7,"f",$instituicao,$anousu);

// Ate o bimestre
$m_despesa[7]["exercicio"]     = 0;

//print_r($m_despesa); exit;

$sele_work = 'o58_instit in ('.$instituicao.')   ';
$result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

for ($x = 0; $x < pg_numrows($result_despesa); $x++){
  db_fieldsmemory($result_despesa,$x);

  $nivel        = $m_despesa[7]["nivel"];
  $estrutural   = $o58_elemento."00";
  $estrutural   = substr($estrutural,0,$nivel);
  $v_estrutural = str_pad($estrutural,15,"0",STR_PAD_RIGHT);
  $v_funcao     = $o58_funcao;
  $v_subfuncao  = $o58_subfuncao;
  $v_recurso    = $o58_codigo;
    
  if (in_array($v_estrutural, $m_despesa[7]["estrut"])) {
    if (count($m_despesa[7]["funcao"])      == 0 || in_array($v_funcao, $m_despesa[7]["funcao"])) {
      if (count($m_despesa[7]["subfunc"])   == 0 || in_array($v_subfuncao, $m_despesa[7]["subfunc"])) {
        if (count($m_despesa[7]["recurso"]) == 0 || in_array($v_recurso, $m_despesa[7]["recurso"])) {
          $m_despesa[7]["exercicio"] += $liquidado_acumulado;
        }
      }
    }
  }
}

//echo $m_despesa[7]["exercicio"]; exit;
///////////////////////////////////////////////////////////////////////////////////

// recupera elementos da configuração dos relatorios
$m_p[1][1]    = $orcparamrel->sql_parametro('4','1',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[1]['1e'] = $orcparamrel->sql_parametro('4','1','t',$instituicao,db_getsession("DB_anousu"));

$m_p[2][1]    = $orcparamrel->sql_parametro('4','2',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[2]['1e'] = $orcparamrel->sql_parametro('4','2','t',$instituicao,db_getsession("DB_anousu"));

$m_p[3][1]    = $orcparamrel->sql_parametro('4','3',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[3]['1e'] = $orcparamrel->sql_parametro('4','3','t',$instituicao,db_getsession("DB_anousu"));

$m_p[4][1]    = $orcparamrel->sql_parametro('4','4',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[4]['1e'] = $orcparamrel->sql_parametro('4','4','t',$instituicao,db_getsession("DB_anousu"));

$m_p[5][1]    = $orcparamrel->sql_parametro('4','5',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[5]['1e'] = $orcparamrel->sql_parametro('4','5','t',$instituicao,db_getsession("DB_anousu"));

$m_p[6][1]    = $orcparamrel->sql_parametro('4','6',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[6]['1e'] = $orcparamrel->sql_parametro('4','6','t',$instituicao,db_getsession("DB_anousu"));

$m_p[7][1]    = $orcparamrel->sql_parametro('4','7',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[7]['1e'] = $orcparamrel->sql_parametro('4','7','t',$instituicao,db_getsession("DB_anousu"));

$m_p[8][1]    = $orcparamrel->sql_parametro('4','8',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[8]['1e'] = $orcparamrel->sql_parametro('4','8','t',$instituicao,db_getsession("DB_anousu"));

// zera a coluna de valores
for ($x=1;$x<=8;$x++){
  $m_p[$x][2]=0; 
  $m_p[$x][3]=0; 
}
$aOrcParametro = array_merge(
  $m_p[1][1],
  $m_p[2][1],
  $m_p[3][1],
  $m_p[4][1],
  $m_p[5][1],
  $m_p[6][1],
  $m_p[8][1]
);
$sele_work  = 'c61_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_bal     =  db_planocontassaldo_completo($anousu,$dt_ini,$dt_fin,false,$sele_work,$aOrcParametro);
// calculo do periodo anterior ao exercicio
@pg_exec("drop table work_pl");
//$dt_ini_ant = '2006-12-20';
//$dt_fin_ant = '2006-12-31';
$result_bal_ant =  db_planocontassaldo_completo($anousu_ant,$dt_ini_ant,$dt_fin_ant, false, $sele_work, $aOrcParametro);
@pg_exec("drop table work_pl");

/**
m_p[linha][1:estruturais]
--------- [2:saldo exe]
--------- [3:saldo ant]
*/

for($x=0;$x< pg_numrows($result_bal);$x++) {
  db_fieldsmemory($result_bal,$x);
  for ($aa=1;$aa<=8;$aa++){
    if (in_array($estrutural,$m_p[$aa][1])) {
      if (isset($m_p[$aa][2])){
        $m_p[$aa][2]+= $saldo_anterior_debito-$saldo_anterior_credito;
      } else {
        //        echo "<br><br> zerando ".$mp_[$aa][2];
        $m_p[$aa][2] = $saldo_anterior_debito-$saldo_anterior_credito;
      }  
    }// end if
    //## exclusao de parametros
    if (in_array($estrutural,$m_p[$aa]['1e'])){
      
      if (isset($m_p[$aa][2])){
        $m_p[$aa][2]-= $saldo_anterior_credito-$saldo_anterior_debito;
      } else {
//        echo "<br><br> zerando ".$mp_[$aa][2];
        $m_p[$aa][2] = ($saldo_anterior_credito-$saldo_anterior_debito)*-1;
      }  
    }// end if
    
  }// endfor
  
}

// echo "<br> ".$m_p[1][2];
// exit;

for($x=0;$x< pg_numrows($result_bal_ant);$x++) {
  db_fieldsmemory($result_bal_ant,$x);
  for ($aa=1;$aa<=8;$aa++){
    if (in_array($estrutural,$m_p[$aa][1])){
      if (isset($m_p[$aa][2])){
        $m_p[$aa][2]+= $saldo_anterior_debito-$saldo_anterior_credito;
      } else {
        $m_p[$aa][2] = $saldo_anterior_debito-$saldo_anterior_credito;
      }  
    }// end if
    //## exclusao de parametros
    if (isset($mp[$aa]['1e']) && in_array($estrutural,$m_p[$aa]['1e'])){
      if (isset($m_p[$aa][2])){
        $m_p[$aa][2]-= $saldo_anterior_credito-$saldo_anterior_debito;
      } else {
        $m_p[$aa][2] = ($saldo_anterior_credito-$saldo_anterior_debito)*-1;
      }  
    }
    
  }
  
}

for ($aa=1;$aa<=8;$aa++) {
  
  for ($aaa=0;$aaa<sizeof($m_p[$aa][1]);$aaa++) {
    
    $pontoz=14;
    for ($tz=13; $tz >= 0; $tz--) {
      if (substr($m_p[$aa][1][$aaa],$tz,1) > 0) {
        $pontoz=$tz;
        break;
      }
    }
    
    $sql = "
    select 
    o56_codele,
    o56_elemento,
    o56_descr,
    sum( case when c71_coddoc = 1 then round(c70_valor,2) else 0 end ) as emp, 
    sum( case when c71_coddoc = 2 then round(c70_valor,2) else 0 end ) as anu_emp, 
    sum( case when c71_coddoc = 3 then round(c70_valor,2) else 0 end ) as liq, 
    sum( case when c71_coddoc = 4 then round(c70_valor,2) else 0 end ) as anu_liq, 
    sum( case when c71_coddoc = 5 then round(c70_valor,2) else 0 end ) as pag, 
    sum( case when c71_coddoc = 6 then round(c70_valor,2) else 0 end ) as anu_pag 
    from conlancamele
    inner join orcelemento  on c67_codele = o56_codele and o56_anousu = $anousu
    inner join conlancam    on c70_codlan = c67_codlan
    inner join conlancamdoc on c70_codlan = c71_codlan
    where o56_elemento like '" . substr($m_p[$aa][1][$aaa],0,$pontoz+1) . "%'
    and c71_coddoc in (1,2,3,4,5,6)
    and c70_data between '$dt_ini' and '$dt_fin'
    group by o56_codele,o56_elemento,o56_descr";
    $result_ele = pg_exec($sql) or die($sql);
    
    //		echo "sql - $aa - $aaa - $sql<br>";
    
    if (pg_numrows($result_ele) > 0) {
      for ($ele=0; $ele < pg_numrows($result_ele); $ele++) {
        db_fieldsmemory($result_ele, $ele);
        $m_p[$aa][3] += $emp - $anu_emp - $liq + $anu_liq;
      }
    }
    
  }
  
}

// exclusão de parametros

// receita corrente liquida
// busca os estruturias que o usuário selecionou nos parametros

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}

//--------- // ------------- // ------------- // ---------------
// receita 
$cl_res = new cl_receita_saldo_mes;
$cl_res->anousu= $anousu_ant;
$cl_res->dtini = $dt_ini_ant;
$cl_res->dtfim = $dt_fin_ant;
$cl_res->usa_datas = 'sim';
//$cl_res->instit = "".str_replace('-',', ',$db_selinstit)." ";
$cl_res->instit = $todasinstit;
$cl_res->sql_record();
$result_rec_ant = $cl_res->result;
@pg_exec("drop table work_plano");

$result_rec  = new cl_receita_saldo_mes;
$result_rec->anousu = $anousu ;
$result_rec->dtini = $dt_ini;
$result_rec->dtfim = $dt_fin;
$result_rec->usa_datas = 'sim';
//$result_rec->instit = "".str_replace('-',', ',$db_selinstit)." ";
$result_rec->instit = $todasinstit;
$result_rec->sql_record();
$result_rec = $result_rec->result;
@pg_exec("drop table work_plano");

//echo("dt_ini_ant: $dt_ini_ant - dt_fin_ant: $dt_fin_ant - dt_ini: $dt_ini - dt_fin: $dt_fin<br>");

$tx[12][2] = 0; // receita corrente liquida

$param[1]  = $orcparamrel->sql_parametro('5','1',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[2]  = $orcparamrel->sql_parametro('5','2',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[3]  = $orcparamrel->sql_parametro('5','3',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[4]  = $orcparamrel->sql_parametro('5','4',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[5]  = $orcparamrel->sql_parametro('5','5',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[6]  = $orcparamrel->sql_parametro('5','6',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[7]  = $orcparamrel->sql_parametro('5','7',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[8]  = $orcparamrel->sql_parametro('5','8',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[9]  = $orcparamrel->sql_parametro('5','9',  'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[10] = $orcparamrel->sql_parametro('5','10', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[11] = $orcparamrel->sql_parametro('5','11', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[12] = $orcparamrel->sql_parametro('5','12', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[13] = $orcparamrel->sql_parametro('5','13', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[14] = $orcparamrel->sql_parametro('5','14', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[15] = $orcparamrel->sql_parametro('5','15', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);

// inicio dedução
$param[16] = $orcparamrel->sql_parametro('5','16', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[17] = $orcparamrel->sql_parametro('5','17', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);
$param[18] = $orcparamrel->sql_parametro('5','18', 'f', str_replace('-', ', ', $db_selinstit), $anousu_ant);


//  quando existir ano anterior processa
if ($dt_ini_ant != $dt_fin_ant) {
  
  for ($p=1;$p<=18;$p++){  
    // 18 é a quantidade de parametros ou linhas existentes nos parametros
    for ($i=0;$i<pg_numrows($result_rec_ant);$i++){
      db_fieldsmemory($result_rec_ant,$i);
      $estrutural = $o57_fonte;
      if (in_array($estrutural,$param[$p])) {
        if ($p <= 15){
          $tx[12][2] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
        } else {
					if (substr($estrutural,0,3) == "497"){
						$tx[12][2] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
					} else {
						$tx[12][2] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
					}
        }	
      }	 
    }
  }
}

//echo("1: " . $tx[12][2] . "<br>");

$param[1]  = $orcparamrel->sql_parametro('5','1',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[2]  = $orcparamrel->sql_parametro('5','2',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[3]  = $orcparamrel->sql_parametro('5','3',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[4]  = $orcparamrel->sql_parametro('5','4',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[5]  = $orcparamrel->sql_parametro('5','5',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[6]  = $orcparamrel->sql_parametro('5','6',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[7]  = $orcparamrel->sql_parametro('5','7',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[8]  = $orcparamrel->sql_parametro('5','8',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[9]  = $orcparamrel->sql_parametro('5','9',  'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[10] = $orcparamrel->sql_parametro('5','10', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[11] = $orcparamrel->sql_parametro('5','11', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[12] = $orcparamrel->sql_parametro('5','12', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[13] = $orcparamrel->sql_parametro('5','13', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[14] = $orcparamrel->sql_parametro('5','14', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[15] = $orcparamrel->sql_parametro('5','15', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

// inicio dedução
$param[16] = $orcparamrel->sql_parametro('5','16', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[17] = $orcparamrel->sql_parametro('5','17', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
$param[18] = $orcparamrel->sql_parametro('5','18', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

///// TESTE O CODIGO ABAIXO E RETORNA SALDO DE 2006 CORRETO
for ($p=1;$p<=18;$p++) {
  // 18 é a quantidade de parametros ou linhas existentes nos parametros
  for ($i=0;$i<pg_numrows($result_rec);$i++){
    db_fieldsmemory($result_rec,$i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural,$param[$p])){       
      if ($p <=15){
        $tx[12][2] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
      } else {
        if (substr($estrutural,0,3) == "497"){
					$tx[12][2] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
				} else {
					$tx[12][2] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
				}
      }	
    } 
  }
}

//echo("2: " . $tx[12][2] . "<br>");
//die("111");

$tx[0][1]  = 'DESPESA BRUTA COM PESSOAL (I)';
$tx[1][1]  = '   Pessoal Ativo';
$tx[2][1]  = '   Pessoal Inativo e Pensionistas';
$tx[3][1]  = "   Outras despesas de pessoal decorrentes de contratos de terceirização (art. 18, § 1º da LRF)";
$tx[4][1]  = "DESPESAS NÃO COMPUTADAS (art. 19, § 1º da LRF) (II)";
$tx[5][1]  = "   Indenizações por Demissão e Incentivos à Demissão Voluntária";
$tx[6][1]  = "   Decorrentes de Decisão Judicial";
$tx[7][1]  = "   Despesas de Exercícios Anteriores";
$tx[8][1]  = "   Inativos e Pensionistas com Recursos Vinculados";
$tx[9][1]  = "REPASSE PREVIDENCIÁRIO AO REGIME PRÓPRIO DE PREVIDENCIA SOCIAL(III)";
$tx[10][1] = "   Contribuições Patronais";
$tx[11][1] = "TOTAL DA DESPESA COM PESSOAL PARA FINS DE APURAÇÃO DO LIMITE - TDP (III) = (I - II)";
$tx[12][1] = "RECEITA CORRENTE LÍQUIDA - RCL (IV)";
$tx[13][1] = "% DO TOTAL DA DESPESA COM PESSOAL PARA FINS DE APURAÇÃO DO LIMITE TDP sobre a RCL (V)=(III/IV)*100";
$tx[14][1] = "LIMITE MÁXIMO (incisos I, II e III, art. 20 da LRF) $limite_maximo%";
$tx[15][1] = "LIMITE PRUDENCIAL (parágrafo único, art. 22 da LRF) $limite_prudencial%";

$tx[16][1] = "Nota: Durante o exercício, somente as despesas liquidadas são consideradas executadas. No encerramento do exercício, as despesas não liquidadas inscritas em restos a pagar não processados são";
$tx[17][1] = "também consideradas executadas. Dessa forma, para maior transparência, as despesas executadas estão segregadas em:";
$tx[18][1] = "a) Despesas liquidadas, consideradas aquelas em que houve a entrega do material ou serviço, nos termos do art. 63 da Lei 4.320/64";
$tx[19][1] = "b) Despesas empenhadas mas não liquidadas, inscritas em Restos a Pagar não processados, consideradas liquidadas no encerramento do exercício, por força do art.35, inciso II da	Lei 4.320/64.";

// carrega valores

// adiciona valor das variaveis indicada pelo usuario 
$m_p[1][2] = $m_p[1][2] + $pessoal_ativo_adicional;
$m_p[2][2] = $m_p[2][2] + $pessoal_inativo_adicional;
$m_p[8][2] = $m_p[8][2] + $repasses_adicional;
$m_p[7][2] = $m_despesa[7]["exercicio"];

//$m_p[7][2] = $m_p[7][2] + $inativos_pensionistas;

$tx[1][2] = $m_p[1][2] + $m_p[8][2]; // pessoal ativo + patronal
$tx[2][2] = $m_p[2][2];
$tx[3][2] = $m_p[3][2];

$tx[5][2] = $m_p[4][2];
$tx[6][2] = $m_p[5][2];
$tx[7][2] = $m_p[6][2];
$tx[8][2] = $m_p[7][2];

$tx[9][2] = $m_p[8][2];
$tx[10][2]= $m_p[8][2];

// calculos
$tx[0][2]  = $tx[1][2] + $tx[2][2] + $tx[3][2];
$tx[4][2]  = $tx[5][2] + $tx[6][2] + $tx[7][2] + $tx[8][2];
$tx[11][2] = $tx[0][2] - $tx[4][2];

// restos a pagar

$tx[1][3]  = $m_p[1][3];
$tx[2][3]  = $m_p[2][3];
$tx[3][3]  = $m_p[3][3];

$tx[5][3]  = $m_p[4][3];
$tx[6][3]  = $m_p[5][3];
$tx[7][3]  = $m_p[6][3];
$tx[8][3]  = $m_p[7][3];

$tx[9][3]  = 0;
$tx[10][3] = 0;

$tx[11][3] = 0;
$tx[12][3] = 0;
$tx[13][3] = 0;
$tx[14][3] = 0;
$tx[15][3] = 0;

$tx[0][3]  = $tx[1][3] + $tx[2][3] + $tx[3][3];
$tx[4][3]  = $tx[5][3] + $tx[6][3] + $tx[7][3] + $tx[8][3];
$tx[11][3] = $tx[0][3] - $tx[4][3];

// totalizacoes

if ($tx[12][2] > 0) {
  //$tx[13][2] = (($tx[11][2] + $tx[11][3]) / $tx[12][2])*100;
  $tx[13][2] = ($tx[11][2]/$tx[12][2])*100;  // TDP/RCL * 100
} else {
  $tx[13][2] = 0;
}

$tx[14][2] = (($tx[12][2] + $tx[12][3]) * $limite_maximo) /100;
$tx[15][2] = (($tx[12][2] + $tx[12][3]) * $limite_prudencial)/100;

//die("$periodo - 1: " . $tx[11][2] . " - 2: " . $tx[13][2]);

if (!isset($arqinclude)) {
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt = 4;
  $pagina = 1;
  
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(135,$alt,'RGF ANEXO I (LRF, art. 55, inciso I, alínea "a")','B',0,"L",0);
  $pdf->cell(60,$alt,'R$ 1,00','B',1,"R",0);
  
  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS EXECUTADAS",'',1,"C",0);
  
  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell(60,$alt,"(Últimos 12 meses)",'LB',1,"C",0);
  
  $pdf->cell(135,$alt,"DESPESA COM PESSOAL",'R',0,"C",0);
  $pdf->cell($tamanho,$alt,"",'',0,"C",0);
  if ($imprimirrps == true) {
    $pdf->cell(30,$alt,"INSCRITAS EM",'L',0,"C",0);
  }
  $pdf->ln();
  
  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell($tamanho,$alt,"LIQUIDADAS",'',0,"C",0);
  if ($imprimirrps == true) {
    $pdf->cell(30,$alt,"RESTOS A PAGAR",'L',0,"C",0);
  }
  $pdf->ln();
  
  if ($imprimirrps == true) {
    $pdf->cell(135,$alt,"",'R',0,"C",0);
    $pdf->cell(30,$alt,"",'',0,"C",0);
    $pdf->cell(30,$alt,"NAO",'L',0,"C",0);
    $pdf->ln();
  }
  
  if ($imprimirrps == true) {
    $pdf->cell(135,$alt,"",'R',0,"C",0);
    $pdf->cell(30,$alt,"",'',0,"C",0);
    $pdf->cell(30,$alt,"PROCESSADOS",'L',0,"C",0);
    $pdf->ln();
  }
  
  $pdf->cell(135,$alt,"",'RB',0,"R",0);
  if ($imprimirrps == true) {
    $pdf->cell(30,$alt,"",'RB',0,"C",0);
    $pdf->cell(30,$alt,"",'B',0,"C",0);
  } else {
    $pdf->cell($tamanho,$alt,"",'B',0,"C",0);
  }
  $pdf->ln();
  
  $pdf->setfont('arial','',7);
  
  for($i=0;$i<=8;$i++) {
    $pdf->cell(135,$alt,$tx[$i][1],'R',0,"L",0);
    
    if (isset($tx[$i][2])){
      $pdf->cell($tamanho,$alt,db_formatar($tx[$i][2],'f'),'',0,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'',0,"R",0);
    }
    if ($imprimirrps == true) {
      $pdf->cell(30,$alt,db_formatar($tx[$i][3],'f'),'L',0,"R",0);
    }
    $pdf->ln();
  }
  
  if (1==2) {
    $i = 9;
    $pdf->cell(135,$alt,$tx[$i][1],'TR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell(30,$alt,db_formatar($tx[$i][2],'f'),'T',1,"R",0);
    }else{
      $pdf->cell(30,$alt,db_formatar(0,'f'),'T',1,"R",0);
    }
    $i = 10;
    $pdf->cell(125,$alt,$tx[$i][1],'BR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell(30,$alt,db_formatar($tx[$i][2],'f'),'B',1,"R",0);
    }else{
      $pdf->cell(30,$alt,db_formatar(0,'f'),'B',1,"R",0);
    }
  }
  
  for($i=11;$i<=15;$i++) {
    $pdf->cell(135,$alt,$tx[$i][1],'TBR',0,"L",0);
    
    if (isset($tx[$i][2])){
//      echo "1) ".$i." => ".db_formatar($tx[$i][2],"f")."<br>";
      $pdf->cell($tamanho,$alt,db_formatar($tx[$i][2],'f'),'TB',0,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'TB',0,"R",0);
    }
   
    $valorimprimir=db_formatar($tx[$i][3],'f');
//    echo $i." => ".$valorimprimir."<br>";
    
    if ($i>11) {
      $valorimprimir="";
    }
    
    if ($imprimirrps == true) {
      $pdf->cell(30,$alt,$valorimprimir,($i==11?'LBT':($i==15?'LB':'L')),0,"R",0);
    }
    
    $pdf->ln();
    
  }
  
  $pdf->cell(195,$alt,"Fonte: Contabilidade",'',1,"L",0);
  $pdf->cell(195,$alt,"Nota: Durante o exercício, somente as despesas liquidadas são consideradas executadas.",'',1,"L",0);
  $pdf->cell(195,$alt,"No encerramento do exercício, as despesas não liquidadas inscritas em restos a pagar não processados são",'',1,"L",0);
  $pdf->cell(195,$alt,"também consideradas executadas. Dessa forma, para maior transparência, as despesas executadas estão segregadas em:",'',1,"L",0);
  $pdf->cell(195,$alt,"     a) Despesas liquidadas, consideradas aquelas em que houve a entrega do material ou serviço, nos termos do art. 63 da Lei 4.320/64;",'',1,"L",0);
  $pdf->cell(195,$alt,"     b) Despesas empenhadas mas não liquidadas, inscritas em Restos a Pagar não processados, consideradas",'',1,"L",0);
  $pdf->cell(195,$alt,"          liquidadas no encerramento do exercício, por força do art.35, inciso II da Lei 4.320/64.",'',1,"L",0);
  
  $pdf->Ln(5);
  
  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$total_despesa_pessoal_limites = $tx[11][2];
$total_rcl_limites						 = $tx[13][2];

$limite_maximo_valor					 = $tx[14][2];
$limite_prudencial_valor			 = $tx[15][2];

?>