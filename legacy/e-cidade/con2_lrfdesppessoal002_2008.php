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

$dt_ini_ant = $anousu_ant."-01-01";
$dt_fin_ant = $anousu_ant."-12-31";

////////////////////////////////////////////////////////////////////

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select munic,nomeinst,nomeinstabrev,db21_tipoinstit from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
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
  
  if ($periodo == "3Q" || $periodo == "2S") {
    $imprimirrps=true;
    $tamanho=30;
  } else {
    $tamanho=60;
  }
  
}

$imprimirrps=true;
$tamanho=30;
if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

//var_dump($temcamara);
//var_dump($temprefa);
//var_dump($temadmind);

if ($temcamara == true && ($temprefa == true || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER LEGISLATIVO";
}

if ($temprefa == true && $temcamara == false && ($temadmind == false || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head3 = $descr_inst;
  $head4 = "RELATÓRIO DE GESTÃO FISCAL";
  $head5 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
} else {
  $head3 = "RELATÓRIO DE GESTÃO FISCAL";
  $head4 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
  $head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
}

$dt1 = split('-',$dt_ini);
$dt2 = split('-',$dt_fin); 
if ($tipo_emissao=='periodo'){
  $textodt = strtoupper(db_mes($dt1[1]))." A ".strtoupper(db_mes($dt2[1]))." DE ";
  
  if ($temcamara == true && $temprefa == false && $temadmind == false){
    $head7 = $textodt.db_getsession("DB_anousu");
  } else {
    $head6 = $textodt.db_getsession("DB_anousu");
  }
}else{
  if ($temcamara == true && $temprefa == false && $temadmind == false){
    $head7 = 'PERÍODO :'.$dt1[2].'/'.$dt1[1].'/'.$dt1[0].' à '.$dt2[2].'/'.$dt2[1].'/'.$dt2[0];
  } else {
    $head6 = 'PERÍODO :'.$dt1[2].'/'.$dt1[1].'/'.$dt1[0].' à '.$dt2[2].'/'.$dt2[1].'/'.$dt2[0];
  }
}  

////////////////////////////////////////////////////////////////////////////////////////////

$limite_maximo             = 0;
$limite_prudencial         = 0;
$pessoal_ativo_adicional   = 0;
$pessoal_inativo_adicional = 0;
$outras_despesas           = 0;
$indenizacoes              = 0;
$decorrentes               = 0;
$despesas_anteriores       = 0;
$inativos_vinculados       = 0;

$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(26,str_replace('-',',',$db_selinstit)));
if ($clconrelinfo->numrows > 0 ){
  for ($x=0;$x < $clconrelinfo->numrows;$x++){
    db_fieldsmemory($res,$x);
    
    if ($c83_codigo == 358 ){
      $limite_maximo = $c83_informacao;
    } else if ($c83_codigo == 359){
      $limite_prudencial  = $c83_informacao;
    } else if ($c83_codigo == 351){
      $pessoal_ativo_adicional = $c83_informacao;
    } else if ($c83_codigo == 352){
      $pessoal_inativo_adicional = $c83_informacao;
    } else if ($c83_codigo == 353){
      $outras_despesas = $c83_informacao;
    } else if ($c83_codigo == 354){
      $indenizacoes = $c83_informacao;
    } else if ($c83_codigo == 355){
      $decorrentes  = $c83_informacao;
    } else if ($c83_codigo == 356){
      $despesas_anteriores = $c83_informacao;
    } else if ($c83_codigo == 357){
      $inativos_vinculados = $c83_informacao;
    }
  }
  
}

if ($temprefa==true and	$temcamara==true) {
  if ($limite_maximo == 0){
    $limite_maximo=60;
  }
} elseif ($temprefa==true and	$temcamara==false) {
  if ($limite_maximo == 0){
    $limite_maximo=54;
  }
} elseif ($temprefa==false and	$temcamara==true) {
  if ($limite_maximo == 0){
    $limite_maximo=6;
  }
}

if ($limite_prudencial == 0){
  $limite_prudencial=$limite_maximo*95/100;
}

$instituicao = str_replace("-",",",$db_selinstit);

// INATIVOS E PENSIONISTAS COM RECURSOS VINCULADOS
$m_despesa[7]["estrut"]        = $orcparamrel->sql_parametro("26",7,"f",$instituicao,$anousu);
$m_despesa[7]["nivel"]         = $orcparamrel->sql_nivel("26",7,$anousu);
$m_despesa[7]["nivelexclusao"] = $orcparamrel->sql_nivelexclusao("26",7,"f",$instituicao,$anousu);
$m_despesa[7]["funcao"]        = $orcparamrel->sql_funcao("26",7,"f",$instituicao,$anousu);  
$m_despesa[7]["subfunc"]       = $orcparamrel->sql_subfunc("26",7,"f",$instituicao,$anousu);
$m_despesa[7]["recurso"]       = $orcparamrel->sql_recurso("26",7,"f",$instituicao,$anousu);

// Ate o bimestre
$m_despesa[7]["exercicio"]     = 0;

//print_r($m_despesa); exit;

$sele_work = 'o58_instit in ('.$instituicao.')   ';
$result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

$dt3 = split("-",$dt_fin);
if ($dt3[1] == "12"){
  $dt3[1] = 11;
}

//db_criatabela($result_despesa);
///db_criatabela($result_despesa_ant); exit;

// Exercicio Atual
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
//          echo "Atual ".db_formatar($m_despesa[7]["exercicio"],"f")." => ".$v_recurso." => ".db_formatar($liquidado_acumulado,"f")."<br>";
          $m_despesa[7]["exercicio"] += abs($liquidado_acumulado);
        }
      }
    }
  }
}
if ($periodo != "3Q" && $periodo != "2S") {
  
  $dt_ini_ant = $anousu_ant."-".($dt3[1]+1)."-01";
  $result_despesa_ant = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu_ant,$dt_ini_ant,$dt_fin_ant);
  
  // Exercicio Anterior
  for ($x = 0; $x < pg_numrows($result_despesa_ant); $x++){
    db_fieldsmemory($result_despesa_ant,$x);
  
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
  //          echo db_formatar($m_despesa[7]["exercicio"],"f")." => ".$v_recurso." => ".db_formatar($liquidado_acumulado,"f")."<br>";
            $m_despesa[7]["exercicio"] += abs($liquidado);
          }
        }
      }
    }
  }
}
//echo $m_despesa[7]["exercicio"]; exit;
///////////////////////////////////////////////////////////////////////////////////

// recupera elementos da configuração dos relatorios
$m_p[1][1] = $orcparamrel->sql_parametro_instit('26','1',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[2][1] = $orcparamrel->sql_parametro_instit('26','2',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[3][1] = $orcparamrel->sql_parametro_instit('26','3',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[4][1] = $orcparamrel->sql_parametro_instit('26','4',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[5][1] = $orcparamrel->sql_parametro_instit('26','5',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[6][1] = $orcparamrel->sql_parametro_instit('26','6',"f",$instituicao,db_getsession("DB_anousu"));
$m_p[8][1] = $orcparamrel->sql_parametro_instit('26','8',"f",$instituicao,db_getsession("DB_anousu"));

$aOrcParametro = array_merge(
  $m_p[1][1],
  $m_p[2][1],
  $m_p[3][1],
  $m_p[4][1],
  $m_p[5][1],
  $m_p[6][1],
  $m_p[8][1]
);

/*
echo "<pre>";
echo count($m_p[1][1]);
print_r($m_p[1][1]);
echo "</pre>";
exit;
*/

// calculo do exercicio atual
$sele_work  = 'c61_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_bal = db_planocontassaldo_completo($anousu,$dt_ini,$dt_fin,false,$sele_work,$aOrcParametro);
//db_criatabela($result_bal); exit;

// calculo do periodo anterior ao exercicio
@pg_exec("drop table work_pl");
$dt3 = split("-",$dt_fin);

if ($dt3[1] == "12"){
  $dt3[1] = 11;
}


//db_criatabela($result_bal_ant); exit;

// zera a coluna de valores
for ($x=1;$x<=8;$x++){
  if ($x == 7){
    continue;
  }

  $m_p[$x][2]=0; 
  $m_p[$x][3]=0; 
}

/**
m_p[linha][1:estruturais]
--------- [2:saldo exe]
--------- [3:saldo ant]
*/

for($x=0;$x< pg_numrows($result_bal);$x++) {
  db_fieldsmemory($result_bal,$x);
  
  for ($aa=1;$aa<=8;$aa++){
     if ($aa == 7){
       continue;
     }

     $instit      = $c61_instit;
     $v_elementos = array($estrutural,$instit);
  
     $flag_contar = false;
     if ($instit != 0) {
       if (in_array($v_elementos,$m_p[$aa][1])) {
         $flag_contar = true;
       }
    } else {
       for($xx = 0; $xx < count($m_p[$aa][1]); $xx++){
         if ($estrutural == $m_p[$aa][1][$xx][0]) {
           $flag_contar = true;
           break;
         }
       }
    }
  
    if ($flag_contar == true) {
//    if (in_array($estrutural,$m_p[$aa][1])) {
      if (isset($m_p[$aa][2])){
        $m_p[$aa][2]+= $saldo_anterior_debito-$saldo_anterior_credito;
      } else {
        //        echo "<br><br> zerando ".$mp_[$aa][2];
        $m_p[$aa][2] = $saldo_anterior_debito-$saldo_anterior_credito;
      }  
    }// end if
  }// endfor
  
}

// echo "<br> ".$m_p[1][2];
// exit;
if ($periodo != "3Q" && $periodo != "2S") {
  
  $dt_ini_ant = $anousu_ant."-".($dt3[1]+1)."-01";
  $result_bal_ant =  db_planocontassaldo_completo($anousu_ant,$dt_ini_ant,$dt_fin_ant,false,$sele_work,$aOrcParametro);
  @pg_exec("drop table work_pl");
  for($x=0;$x< pg_numrows($result_bal_ant);$x++) {
    db_fieldsmemory($result_bal_ant,$x);
  
    for ($aa=1;$aa<=8;$aa++){
       if ($aa == 7){
         continue;
       }
  
       $instit      = $c61_instit;
       $v_elementos = array($estrutural,$instit);
    
       $flag_contar = false;
       if ($instit != 0) {
         if (in_array($v_elementos,$m_p[$aa][1])) {
           $flag_contar = true;
         }
      } else {
         for($xx = 0; $xx < count($m_p[$aa][1]); $xx++){
           if ($estrutural == $m_p[$aa][1][$xx][0]) {
             $flag_contar = true;
             break;
           }
         }
      }
    
      if ($flag_contar == true) {
  //    if (in_array($estrutural,$m_p[$aa][1])){
        if (isset($m_p[$aa][2])){
          $m_p[$aa][2]+= $saldo_anterior_debito-$saldo_anterior_credito;
        } else {
          $m_p[$aa][2] = $saldo_anterior_debito-$saldo_anterior_credito;
        }  
      }// end if
    }
  }
}

for ($aa=1;$aa<=8;$aa++) {
 
  if ($aa == 7){
    continue;
  }

  for ($aaa=0;$aaa<sizeof($m_p[$aa][1]);$aaa++) {
    
    $pontoz=14;
    for ($tz=13; $tz >= 0; $tz--) {
      if (substr($m_p[$aa][1][$aaa][0],$tz,1) > 0) {
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
    where o56_elemento like '" . substr($m_p[$aa][1][$aaa][0],0,$pontoz+1) . "%'
    and c71_coddoc in (1,2,3,4,5,6)
    and c70_data between '$dt_ini' and '$dt_fin'
    group by o56_codele,o56_elemento,o56_descr";
    //echo $sql; exit;
    $result_ele = pg_exec($sql) or die($sql);
    
    //		echo "sql - $aa - $aaa - $sql<br>";
    
    if (pg_numrows($result_ele) > 0) {
      for ($ele=0; $ele < pg_numrows($result_ele); $ele++) {
        db_fieldsmemory($result_ele, $ele);
        //$m_p[$aa][3] = abs($emp - $anu_emp - $liq);
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

$tx[13][2] = 0; // receita corrente liquida

$total_rcl  = 0;

//echo $dt_ini." => ".$dt_fin." => ".$dt_ini_ant." => ".$dt_fin_ant; exit;

$total_rcl += calcula_rcl2($anousu,$dt_ini,$dt_fin,$todasinstit,false,27);
$total_rcl += calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fin_ant,$todasinstit,false,27,$dt_fin);

$tx[13][2]  = $total_rcl;

$tx[0][1]  = 'DESPESA BRUTA COM PESSOAL (I)';
$tx[1][1]  = '   Pessoal Ativo';
$tx[2][1]  = '   Pessoal Inativo e Pensionistas';
$tx[3][1]  = "   Outras despesas de pessoal decorrentes de contratos de terceirização (§ 1º do art. 18 da LRF)";
$tx[4][1]  = "DESPESAS NÃO COMPUTADAS (§ 1º do art. 19 da LRF) (II)";
$tx[5][1]  = "   Indenizações por Demissão e Incentivos à Demissão Voluntária";
$tx[6][1]  = "   Decorrentes de Decisão Judicial";
$tx[7][1]  = "   Despesas de Exercícios Anteriores";
$tx[8][1]  = "   Inativos e Pensionistas com Recursos Vinculados";
$tx[9][1]  = "REPASSE PREVIDENCIÁRIO AO REGIME PRÓPRIO DE PREVIDENCIA SOCIAL(III)";
$tx[10][1] = "   Contribuições Patronais";
$tx[11][1] = "DESPESA LÍQUIDA COM PESSOAL (III) = (I - II)";
$tx[12][1] = "DESPESA TOTAL COM PESSOAL - DTP(IV) = (IIIa + IIIb)";
$tx[13][1] = "RECEITA CORRENTE LÍQUIDA - RCL (V)";
$tx[14][1] = "% do DESPESA TOTAL COM PESSOAL - DTP sobre a RCL (VI)=(IV/V)*100";
$tx[15][1] = "LIMITE MÁXIMO (incisos I, II e III, art. 20 da LRF) $limite_maximo%";
$tx[16][1] = "LIMITE PRUDENCIAL (parágrafo único, art. 22 da LRF) $limite_prudencial%";

// adiciona valor das variaveis indicada pelo usuario 
$m_p[1][2] = $m_p[1][2] + $pessoal_ativo_adicional;
$m_p[2][2] = $m_p[2][2] + $pessoal_inativo_adicional;
$m_p[8][2] = $m_p[8][2];
$m_p[7][2] = $m_despesa[7]["exercicio"] + $inativos_vinculados;

//$m_p[7][2] = $m_p[7][2] + $inativos_pensionistas;

$tx[1][2] = $m_p[1][2];
$tx[2][2] = $m_p[2][2];
$tx[3][2] = $m_p[3][2] + $outras_despesas;

$tx[5][2] = $m_p[4][2] + $indenizacoes;
$tx[6][2] = $m_p[5][2] + $decorrentes;
$tx[7][2] = $m_p[6][2] + $despesas_anteriores;
$tx[8][2] = $m_p[7][2];

$tx[9][2] = $m_p[8][2];
$tx[10][2]= $m_p[8][2];

// calculos
$tx[0][2]  = $tx[1][2] + $tx[2][2] + $tx[3][2];
$tx[4][2]  = $tx[5][2] + $tx[6][2] + $tx[7][2] + $tx[8][2];
$tx[11][2] = $tx[0][2] - $tx[4][2];
$tx[12][2] = $tx[11][2];

// restos a pagar

$tx[1][3]  = $m_p[1][3];
$tx[2][3]  = $m_p[2][3];
$tx[3][3]  = $m_p[3][3];

$tx[5][3]  = $m_p[4][3];
$tx[6][3]  = $m_p[5][3];
$tx[7][3]  = $m_p[6][3];
//$tx[8][3]  = $m_despesa[7]["exercicio"]; 
$tx[8][3]  = $m_p[8][3]; 

$tx[9][3]  = 0;
$tx[10][3] = 0;

$tx[11][3] = 0;  // DESPESA LIQUIDA COM PESSOAL
$tx[12][3] = 0;  // DESPESA TOTAL COM PESSOAL - DTP

$tx[13][3] = 0;  // RCL
$tx[14][3] = 0;
$tx[15][3] = 0;
$tx[16][3] = 0;

$tx[0][3]  = $tx[1][3] + $tx[2][3] + $tx[3][3];
$tx[4][3]  = $tx[5][3] + $tx[6][3] + $tx[7][3] + $tx[8][3];
$tx[11][3] = $tx[0][3] - $tx[4][3];
$tx[12][3] = $tx[11][3];

// totalizacoes

if ($tx[13][2] > 0) {
  //$tx[13][2] = (($tx[11][2] + $tx[11][3]) / $tx[12][2])*100;
  $tx[14][2] = ($tx[11][2]/$tx[13][2])*100;  // TDP/RCL * 100
} else {
  $tx[14][2] = 0;
}

$tx[15][2] = (($tx[13][2] + $tx[13][3]) * $limite_maximo) /100;
$tx[16][2] = (($tx[13][2] + $tx[13][3]) * $limite_prudencial)/100;

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
  $pdf->cell(135,$alt,'RGF - ANEXO I(LRF, art. 55, inciso I, alínea "a")','B',0,"L",0);
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
  $pdf->cell($tamanho,$alt,"LIQUIDADAS(a)",'',0,"C",0);
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
    $pdf->cell(30,$alt,"PROCESSADOS(b)",'L',0,"C",0);
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
      $pdf->cell($tamanho,$alt,db_formatar(abs($tx[$i][2]),'f'),'',0,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'',0,"R",0);
    }

    if ($imprimirrps == true) {
      $valorimprimir = $tx[$i][3];
      if ($valorimprimir <= 0){
        $valorimprimir *= -1;
      }
      if ( $periodo == "3Q" || $periodo == "2S" ) {
        $pdf->cell(30,$alt,db_formatar($valorimprimir,'f'),'L',0,"R",0);
      } else {
        $pdf->cell(30,$alt,db_formatar(0,'f'),'L',0,"R",0);
      }
    }

    $pdf->ln();
  }


 /*
  #
  #  Retirada está parte do relatório por não constar no manual. Ver tarefa 25652.
  #
  #
  if ($temcamara == true){
    $pdf->cell(135,$alt,"   Convocação Extraordinária(inciso II do § 6º do at. 57 da CF)","R",0,"L",0);
    if (isset($tx[8][2])){
      $pdf->cell($tamanho,$alt,db_formatar(abs($m_p[8][2]),"f"),"",0,"R",0);
    } else {
      $pdf->cell($tamanho,$alt,db_formatar(0,"f"),"",0,"R",0);
    }

    if ($imprimirrps == true) {
      if (isset($tx[8][3]) && ($periodo == "3Q" || $periodo == "2S")){

        $pdf->cell($tamanho,$alt,db_formatar(abs($m_p[8][3]),"f"),"L",0,"R",0);
      } else {
        $pdf->cell($tamanho,$alt,db_formatar(0,"f"),"L",0,"R",0);
      }
    }

    $pdf->ln();
  }
  */
  
  if (1==2) {
    $i = 9;
    $pdf->cell(135,$alt,$tx[$i][1],'TR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell($tamanho,$alt,db_formatar(abs($tx[$i][2]),'f'),'T',1,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'T',1,"R",0);
    }
    $i = 10;
    $pdf->cell(125,$alt,$tx[$i][1],'BR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell($tamanho,$alt,db_formatar(abs($tx[$i][2]),'f'),'B',1,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'B',1,"R",0);
    }
  }
  
  for($i=11;$i<=16;$i++) {
    if ($i == 12) {
      if ($imprimirrps == false) {
        continue;
      }
    }

    if ($i == 13){
      $tamanho = 60;
      $pdf->cell(135,$alt,"APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL","R",0,"C",0);
      $pdf->cell( 60,$alt,"VALOR","TB",1,"C",0);
    }
    if ($i == 12 ){

      $pdf->cell(135,$alt,$tx[$i][1],'TBR',0,"L",0);
      if (isset($tx[$i][2])){
        if ($periodo == "2S" || $periodo == "3Q") {
          $pdf->cell(60,$alt,db_formatar(abs($tx[11][2])+abs($tx[11][3]),'f'),'TB',1,"R",0);
        } else {
          $pdf->cell(60,$alt,db_formatar(abs($tx[$i][2]),'f'),'TB',1,"R",0);
        }
      }else{
       $pdf->cell(60,$alt,db_formatar(0,'f'),'TB',1,"R",0);
      }
     continue;
    }
    $pdf->cell(135,$alt,$tx[$i][1],'TBR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell($tamanho,$alt,db_formatar(abs($tx[$i][2]),'f'),'TB',0,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'TB',1,"R",0);
    }

    if ($i < 13){
      if ($imprimirrps == true) {
        $valorimprimir = $tx[$i][3];
        if ($valorimprimir <= 0){
          $valorimprimir *= -1;
        }
       
        if ($periodo == "3Q" || $periodo == "2S") {
          $pdf->cell(30,$alt,db_formatar($valorimprimir,'f'),'TBL',0,"R",0);
        } else {
          $pdf->cell(30,$alt,db_formatar(0,'f'),'TBL',0,"R",0);
        }
      }
    }
    
    $pdf->ln();
  }
 
  notasExplicativas(&$pdf, 26, "{$periodo}",195);
  
  $pdf->Ln(5);
  
  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$total_despesa_pessoal_limites = $tx[11][2];
$total_rcl_limites             = $tx[14][2];

if ($periodo == "3Q" || $periodo == "2S"){
  
  $total_despesa_pessoal_limites = $tx[12][2] + $tx[12][3];
  //$total_rcl_limites            *= 2;
}

$limite_maximo_valor		 = $tx[15][2];
$limite_prudencial_valor = $tx[16][2];

?>