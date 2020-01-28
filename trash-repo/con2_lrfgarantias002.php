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


if (!isset($arqinclude)) {
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_db_config_classe.php");
  
  $classinatura  = new cl_assinatura;
  $cldb_config   = new cl_db_config;
  $orcparamrel   = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");

$clconrelinfo  = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
//******************************************************************
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else{
    $descr_inst .= $xvirg.$nomeinst;
  }
  
  $xvirg = ', ';
}

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}

if ($flag_abrev == false){
  if (strlen($descr_inst) > 42){
    $descr_inst = substr($descr_inst,0,100);
  }
}

$head2 = "INSTITUIÇÕES : ".$descr_inst;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DAS GARANTIAS E CONTRA GARANTIAS DE VALORES";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

$anousu     = db_getsession("DB_anousu");
$anousu_ant = $anousu-1;
$dt         = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini     = $dt[0]; // data inicial do período
$dt_fim     = $dt[1]; // data final do período

$period = '';
if($periodo == "1Q"){        // Quadrimestral
  $period = '1º QUADRIMESTRE';
  $dt_ini_rcl = $anousu_ant.'-05-01';
}elseif($periodo == "2Q"){  
  $period = '2º QUADRIMESTRE'; 
  $dt_ini_rcl = $anousu_ant.'-09-01';
}elseif($periodo == "3Q"){  
  $period = '3º QUADRIMESTRE'; 
  $dt_ini_rcl = $anousu.'-01-01';
}elseif($periodo == "1S"){   // Semestral
  $period = '1º SEMESTRE'; 
  $dt_ini_rcl = $anousu_ant.'-07-01';
}elseif($periodo == "2S"){   
  $period = '2º SEMESTRE'; 
  $dt_ini_rcl = $anousu.'-01-01';
} 


$head6 = "PERIODO : $period DE ".db_getsession("DB_anousu");
//******************************************************************

$where      = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";

$where      = "  c61_instit in (".str_replace('-',', ',$db_selinstit).") "; 
$result     = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fim,false,$where);
@pg_exec("drop table work_pl");

//--------- // ------------- // ------------- // ---------------
// recupera elementos da configuração dos relatorios
//--------- // ------------- // ------------- // ---------------

$instituicao = str_replace("-",",",$db_selinstit);

$paramentro[0] = '';
$paramentro[1] = $orcparamrel->sql_parametro('7','0',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[2] = $orcparamrel->sql_parametro('7','1',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[3] = '';
$paramentro[4] = $orcparamrel->sql_parametro('7','2',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[5] = $orcparamrel->sql_parametro('7','3',"f",$instituicao,db_getsession("DB_anousu"));

$paramentro[6] = '';
$paramentro[7] = '';
$paramentro[8] = '';
$paramentro[9] = '';

$paramentro[10] = '';
$paramentro[11] = $orcparamrel->sql_parametro('7','4',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[12] = $orcparamrel->sql_parametro('7','5',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[13] = '';
$paramentro[14] = $orcparamrel->sql_parametro('7','6',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[15] = $orcparamrel->sql_parametro('7','7',"f",$instituicao,db_getsession("DB_anousu"));
$paramentro[16] = '';
$paramentro[17] = '';

//--------- // ------------- // ------------- // ---------------
$texto[0]  = 'EXTERNAS(I)';
$texto[1]  = '  Aval ou fianças em operações de crédito';
$texto[2]  = '  Outras garantias';
$texto[3]  = 'INTERNAS(II)';
$texto[4]  = '  Aval ou fianças em operações de crédito';
$texto[5]  = '   Outras garantias';
$texto[6]  = 'TOTAL (I +II)';
$texto[7]  = 'RECEITA CORRENTE LIQUIDA - RCL';
$texto[8]  = '% do  TOTAL DAS GARANTIAS sobre a RCL';
$texto[9]  = 'LIMITES DEF. POR RESOLUÇÃO DO SENADO FEDERAL';

$texto[10] = 'GARANTIAS EXTERNAS(I)';
$texto[11] = '  Aval ou fianças em operações de crédito';
$texto[12] = '  Outras garantias';
$texto[13] = 'GARANTIAS INTERNAS(II)';
$texto[14] = '  Aval ou fianças em operações de crédito';
$texto[15] = '  Outras garantias';
$texto[16] = 'TOTAL CONTRAGARANTIAS (I +II)';
$texto[17] = '';

for ($i=0; $i<20;$i++) {
  $valor_ant[$i] = 0;
  $valor1[$i] = 0;
  $valor2[$i] = 0;
  $valor3[$i] = 0;
}
$cont = 1;
$p = 0;
for ($x=0; $x < 17;$x++) {
  if ($cont==3){
    $cont = 4;
  }
  if ($cont==10){
    $cont = 11;
  }
  if ($cont==13){
    $cont = 14;
  }
  
  if ($cont == 1 or $cont == 2 or $cont == 4 or $cont == 5 or $cont == 11 or $cont == 12 or $cont == 14 or $cont == 15) {
    for($i=0;$i< pg_numrows($result);$i++) {
      db_fieldsmemory($result,$i);
      if (in_array($estrutural,$paramentro[$cont])){
        $valor_ant[$cont] += $saldo_anterior;
        $valor1[$cont]    += $saldo_final;
      }
    } 
  }
  
  $cont++;
  
}

$valor_ant[0] = $valor_ant[1] + $valor_ant[2];
$valor1[0]    = $valor1[1] + $valor1[2];
$valor2[0]    = $valor2[1] + $valor2[2];
$valor3[0]    = $valor3[1] + $valor3[2];

$valor_ant[3] = $valor_ant[4] + $valor_ant[5];
$valor1[3]    = $valor1[4] + $valor1[5];
$valor2[3]    = $valor2[4] + $valor2[5];
$valor3[3]    = $valor3[4] + $valor3[5];

$valor_ant[6] = $valor_ant[0] + $valor_ant[3];
$valor1[6]    = $valor1[0] + $valor1[3];
$valor2[6]    = $valor2[0] + $valor2[3];
$valor3[6]    = $valor3[0] + $valor3[3];

// receita corrente liquida
// busca os estruturias que o usuário selecionou nos parametros
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

///////////////////////////////////////////////////////////////////////////////////////////
//                                 receita corrente liquida.
//////////////////////////////////////////////////////////////////////////////////////////
//$dt_ini_ant = $anousu_ant."-01-01";
//var_dump($dt); exit;
$dt = split("-",$dt_ini);
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]-11,"01",$dt[0]));
$dt_fim_ant = $anousu_ant."-12-31";

// se o ano atual é bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
$dt = split('-',$dt_fim);  // mktime -- (mes,dia,ano)
//$dt_ini_ant2 = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]-365,$dt[0]));
$dt_ini_ant2 = date('Y-m-d',mktime(0,0,0,$dt[1]-11,"01",$dt[0]));
$dt_fim_ant2 = $anousu_ant.'-12-31';  

// receita 
$cl_res = new cl_receita_saldo_mes;
$cl_res->anousu= $anousu;
$cl_res->dtini = $anousu.'-01-01';
$cl_res->dtfim = $anousu.'-12-31';
$cl_res->usa_datas = 'sim';
$cl_res->instit = "".str_replace('-',', ',$db_selinstit)." ";
$cl_res->sql_record();
$result_rec_rcl = $cl_res->result;
@pg_exec("drop table work_plano");

// exercicio atual
for ($p=1;$p<=18;$p++){   // 18 é a quantidade de parametros ou linhas existentes nos parametros
  for ($i=0;$i<pg_numrows($result_rec_rcl);$i++){
    db_fieldsmemory($result_rec_rcl,$i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural,$param[$p])) {
      if ($p <=15 ) {
        if($periodo=="1Q" || $periodo=="2Q" || $periodo=="3Q") {
          $valor1[7] += $janeiro+$fevereiro+$marco+$abril;
          $valor2[7] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto;
          $valor3[7] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
        } else if($periodo=="1S"||$periodo=="2S") {
          $valor1[7] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho;
          $valor2[7] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
        }
      } else {
        if (db_conplano_grupo($anousu,substr($estrutural,0,3)."%",9001) == true){  // 497
          if($periodo=="1Q"||$periodo=="2Q" || $periodo=="3Q") {
            $valor1[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril);
            $valor2[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto);
            $valor3[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
          } else if($periodo=="1Q"||$periodo=="2S") {
            $valor1[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho);
            $valor2[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
          }
        } else {
          if($periodo=="1Q"||$periodo=="2Q" || $periodo=="3Q") {
            $valor1[7] -= $janeiro+$fevereiro+$marco+$abril;
            $valor2[7] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto;
            $valor3[7] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
          } else if($periodo=="1Q"||$periodo=="2S") {
            $valor1[7] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho;
            $valor2[7] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
          }
        }
      }	
    } 
  }
}

$cl_res = new cl_receita_saldo_mes;
$cl_res->anousu= $anousu_ant;
$cl_res->dtini = $dt_ini_ant;
$cl_res->dtfim = $dt_fim_ant;
$cl_res->usa_datas = 'sim';
$cl_res->instit = "".str_replace('-',', ',$db_selinstit)." ";
$cl_res->sql_record();
$result_rec_ant = $cl_res->result;
@pg_exec("drop table work_plano");

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

$valor_ant[7] = calcula_rcl($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit);

$matriz_rcl = calcula_rcl($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit,true);

if (substr($periodo,1,1) == "Q") {
	
	$total_rcl	= calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-04-30',$todasinstit);
	$valor1[7]	= $total_rcl + $matriz_rcl['maio']+$matriz_rcl['junho']+$matriz_rcl['julho']+$matriz_rcl['agosto']+$matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

	$total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-08-31',$todasinstit);
	$valor2[7] = $total_rcl + $matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

	$total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-12-31',$todasinstit);
	$valor3[7] = $total_rcl;

} else {
	
	$total_rcl	= calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-06-30',$todasinstit);
	$valor1[7]	= $total_rcl + $matriz_rcl['julho']+$matriz_rcl['agosto']+$matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

	$total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-12-31',$todasinstit);
	$valor2[7] = $total_rcl;

}

// exercicio anterior

/*
for ($p=1;$p<=18;$p++) {
  for ($i=0;$i<pg_numrows($result_rec_ant);$i++){
    db_fieldsmemory($result_rec_ant,$i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural,$param[$p])){       
      if ($p <=15 ){
        $valor_ant[7] += $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
        
        if($periodo=="1Q"||$periodo=="2Q"||$periodo=="3Q") {
          $valor1[7] += $maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
          $valor2[7] += $setembro+$outubro+$novembro+$dezembro;
          $valor3[7] += 0;
        } else if($periodo=="1S" || $periodo=="2S") {
          $valor1[7] += $julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
          $valor2[7] += 0;
        }  
      } else {
        if (substr($estrutural,0,3) == "497"){
          $valor_ant[7] -= abs($janeiro)+abs($fevereiro)+abs($marco)+abs($abril)+abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
          if($periodo=="1Q"||$periodo=="2Q"||$periodo=="3Q") {
            $valor1[7] -= abs($maio)+abs($junho)+abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
            $valor2[7] -= abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
            $valor3[7] -= 0;
          } else if($periodo=="1S" || $periodo=="2S") {
            $valor1[7] -= abs($julho)+abs($agosto)+abs($setembro)+abs($outubro)+abs($novembro)+abs($dezembro);
            $valor2[7] -= 0;
          }
        } else {
          $valor_ant[7] -= $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
          if($periodo=="1Q"||$periodo=="2Q"||$periodo=="3Q") {
            $valor1[7] -= $maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
            $valor2[7] -= $setembro+$outubro+$novembro+$dezembro;
            $valor3[7] -= 0;
          } else if($periodo=="1S" || $periodo=="2S") {
            $valor1[7] -= $julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
            $valor2[7] -= 0;
          }
        }
      }	
    } 
  }
}
*/

@$valor_ant[8] = ($valor_ant[6] / $valor_ant[7]) * 100;
@$valor1[8] = ($valor1[6] / $valor1[7]) * 100;
@$valor2[8] = ($valor2[6] / $valor2[7]) * 100;
@$valor3[8] = ($valor3[6] / $valor3[7]) * 100;

$valor_ant[9] = $valor_ant[7] * 0.32;
@$valor1[9] = $valor1[7] * 0.32;
@$valor2[9] = $valor2[7] * 0.32;
@$valor3[9] = $valor3[7] * 0.32;

$valor_ant[10] = $valor_ant[11] + $valor_ant[12];
$valor1[10] = $valor1[11] + $valor1[12];
$valor2[10] = $valor2[11] + $valor2[12];
$valor3[10] = $valor3[11] + $valor3[12];

$valor_ant[13] = $valor_ant[14] + $valor_ant[15];
$valor1[13] = $valor1[14] + $valor1[15];
$valor2[13] = $valor2[14] + $valor2[15];
$valor3[13] = $valor3[14] + $valor3[15];

$valor_ant[16] = $valor_ant[10] + $valor_ant[13];
$valor1[16] = $valor1[10] + $valor1[13];
$valor2[16] = $valor2[10] + $valor2[13];
$valor3[16] = $valor3[10] + $valor3[13];

if ($periodo=='1Q' or $periodo == '1S'){
  for ($i=0; $i<20;$i++) {
    $valor2[$i] = 0;
    $valor3[$i] = 0;
  }
} elseif ($periodo == '2Q' or $periodo == '2S'){
  for ($i=0; $i<20;$i++) {
    $valor3[$i] = 0;
  }
}

if (!isset($arqinclude)){
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt = 4;
  $pdf->addpage();
  $pdf->ln();
  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt,'LRF, Art. 55, Inciso I, Alínea "c" e art. 40,§ 1º - Anexo III ','B',0,"L",0);
  $pdf->cell(75,$alt,'R$ Unidades','B',1,"R",0);
  
  $pdf->cell(73,$alt,"",'R',0,"C",0);
  $pdf->cell(28,$alt,"SALDO DO",'R',0,"C",0);
  $pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'B',1,"C",0);
  
  $pdf->cell(73,$alt,"GARANTIAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"ATÉ 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"ATÉ 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"ATÉ 3º Quadrimestre",'B',1,"C",0);
  }
  else {
    $pdf->cell(42,$alt,"ATÉ 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"ATÉ 2º Semestre",'B',1,"C",0);
  }
  
  $pdf->setfont('arial','',7);
  for ($i=0; $i < 6;$i++) {
    $pdf->cell(73,$alt,$texto[$i],'R',0,"L",0);
    $pdf->cell(28,$alt,db_formatar($valor_ant[$i],'f'),'R',0,"R",0);
    
    if($periodo!="1S"&&$periodo!="2S") {
      $pdf->cell(28,$alt,db_formatar($valor1[$i],'f'),'R',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor2[$i],'f'),'R',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor3[$i],'f'),'',1,"R",0); 
    } else {
      $pdf->cell(42,$alt,db_formatar($valor1[$i],'f'),'R',0,"R",0);
      $pdf->cell(42,$alt,db_formatar($valor2[$i],'f'),'',1,"R",0);
    }
  }
	
  for ($i=6; $i < 10;$i++) {
    $pdf->cell(73,$alt,$texto[$i],'TBR',0,"L",0);
    $pdf->cell(28,$alt,db_formatar($valor_ant[$i],'f'),'TBR',0,"R",0);
    
    if($periodo!="1S"&&$periodo!="2S") {
      $pdf->cell(28,$alt,db_formatar($valor1[$i],'f'),'TBR',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor2[$i],'f'),'TBR',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor3[$i],'f'),'TB',1,"R",0); 
    } else {
      $pdf->cell(42,$alt,db_formatar($valor1[$i],'f'),'TBR',0,"R",0);
      $pdf->cell(42,$alt,db_formatar($valor2[$i],'f'),'TB',1,"R",0);
    }
  }
  
  $pdf->cell(185,$alt,"",'',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(73,$alt,"",'TR',0,"C",0);
  $pdf->cell(28,$alt,"SALDO DO",'TR',0,"C",0);
  $pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'TB',1,"C",0);
  
  $pdf->cell(73,$alt,"CONTRAGARANTIAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"ATÉ 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"ATÉ 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"ATÉ 3º Quadrimestre",'B',1,"C",0);
  }
  else {
    $pdf->cell(42,$alt,"ATÉ 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"ATÉ 2º Semestre",'B',1,"C",0);
  }
  
  $pdf->setfont('arial','',7);
  for ($i=10; $i< 16;$i++) {
    $pdf->cell(73,$alt,$texto[$i],'R',0,"L",0);
    $pdf->cell(28,$alt,db_formatar($valor_ant[$i],'f'),'R',0,"R",0);
    
    if($periodo!="1S"&&$periodo!="2S") {
      $pdf->cell(28,$alt,db_formatar($valor1[$i],'f'),'R',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor2[$i],'f'),'R',0,"R",0);
      $pdf->cell(28,$alt,db_formatar($valor3[$i],'f'),'',1,"R",0); 
    }
    else {
      $pdf->cell(42,$alt,db_formatar($valor1[$i],'f'),'R',0,"R",0);
      $pdf->cell(42,$alt,db_formatar($valor2[$i],'f'),'',1,"R",0);
    }
  }  
  $pdf->cell(73,$alt,$texto[$i],'TBR',0,"L",0);
  $pdf->cell(28,$alt,db_formatar($valor_ant[16],'f'),'TBR',0,"R",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,db_formatar($valor1[16],'f'),'TBR',0,"R",0);
    $pdf->cell(28,$alt,db_formatar($valor2[16],'f'),'TBR',0,"R",0);
    $pdf->cell(28,$alt,db_formatar($valor3[16],'f'),'TB',1,"R",0); 
  }	
  else {
    $pdf->cell(42,$alt,db_formatar($valor1[16],'f'),'TBR',0,"R",0);
    $pdf->cell(42,$alt,db_formatar($valor2[16],'f'),'TB',1,"R",0);
  }
  
  $pdf->cell(185,$alt,'Fonte: Contabilidade','',1,"L",0); 
  
  // assinaturas
  $pdf->Ln(25);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$garantiascondedidas = $valor1[6];
$garantiasrcl        = $valor1[8];


// Variaveis do relatorio
$res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo,c83_variavel","c83_codigo","c83_codigo = 296 and c83_codrel = 7 and c83_anousu = ".db_getsession("DB_anousu")));
if ($clconrelinfo->numrows > 0){
  $codigo = 296;
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$periodo' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
    if ($clconrelvalor->numrows > 0){
      db_fieldsmemory($res_valor,0);
      $valor = $c83_informacao;  
    } else {
      $valor = 0;
    }
  }
} else {
  $valor = 0;
}
// Fim das Variaveis
$perc_limite_senado = $valor;

if ($periodo!="1S"&&$periodo!="2S") {
  if ($valor > 0){
    $limite_senado1 = $valor1[9];
    $limite_senado2 = $valor2[9];
    $limite_senado3 = $valor3[9];
  } else {
    $limite_senado1 = 0;
    $limite_senado2 = 0;
    $limite_senado3 = 0;
  }
} else {
  if ($valor > 0){
    $limite_senado1 = $valor1[9];
    $limite_senado2 = $valor2[9];
  } else {
    $limite_senado1 = 0;
    $limite_senado2 = 0;
  }
}

//echo $limite_senado1." => ".$limite_senado2." => ".$limite_senado3; exit;

?>