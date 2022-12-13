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
$resultinst = pg_exec("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$lTem1Quad = false;
$lTem2Quad = false;
$lTem3Quad = false;
$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}

db_fieldsmemory($resultinst,0);

$head2 = "MUNICÍPIO DE ".strtoupper($munic);
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DAS GARANTIAS E CONTRA GARANTIAS DE VALORES";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

$anousu       = db_getsession("DB_anousu");
$anousu_ant   = $anousu-1;
$dt           = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini       = $dt[0]; // data inicial do período
$dt_fim       = $dt[1]; // data final do período
$dDataFim1Q   = "{$anousu}-04-30";
$dDataFim2Q   = "{$anousu}-08-31";
$dDataFim3Q   = "{$anousu}-12-21";
$dt_ini_ant   = $anousu_ant."-01-01";
$dt_fim_ant   = $anousu_ant."-12-31";
$mes_ini      = "JANEIRO";
$dt           = split("-",$dt_fim);
$mes_fim      = db_mes($dt[1],1);
$head6        = $mes_ini." A ".$mes_fim." DE ".$anousu;
//******************************************************************
$instituicao  = str_replace("-",",",$db_selinstit);
/*
 * definimos os valores por perido. 
 */
if ($periodo == "1Q"){
  
  $lTem1Quad = true;
  $lTem2Quad = false;
  $lTem3Quad = false;
  
}else if ($periodo == "2Q"){
  
  $lTem1Quad = true;
  $lTem2Quad = true;
  $lTem3Quad = false;
  
}else if ($periodo == "3Q"){
  
  $lTem1Quad = true;
  $lTem2Quad = true;
  $lTem3Quad = true;
  
}else if ($periodo == "1S"){
  
  $lTem1Quad   = true;
  $lTem2Quad   = false;
  $lTem3Quad   = false;
  $dDataFim1Q  = "{$anousu}-06-30";
}else if ($periodo == "2S"){
  
  $lTem1Quad   = true;
  $lTem2Quad   = true;
  $lTem3Quad   = false;
  $dDataFim1Q  = "{$anousu}-06-30";
  $dDataFim2Q  = "{$anousu}-12-31";
}
       
$parametro[0] = '';
$parametro[1] = $orcparamrel->sql_parametro_instit('7','0',"f",$instituicao,$anousu);
$parametro[2] = $orcparamrel->sql_parametro_instit('7','1',"f",$instituicao,$anousu);
$parametro[3] = '';
$parametro[4] = $orcparamrel->sql_parametro_instit('7','2',"f",$instituicao,$anousu);
$parametro[5] = $orcparamrel->sql_parametro_instit('7','3',"f",$instituicao,$anousu);

$parametro[6] = '';
$parametro[7] = '';
$parametro[8] = '';
$parametro[9] = '';

$parametro[10] = '';
$parametro[11] = $orcparamrel->sql_parametro_instit('7','4',"f",$instituicao,$anousu);
$parametro[12] = $orcparamrel->sql_parametro_instit('7','5',"f",$instituicao,$anousu);
$parametro[13] = '';
$parametro[14] = $orcparamrel->sql_parametro_instit('7','6',"f",$instituicao,$anousu);
$parametro[15] = $orcparamrel->sql_parametro_instit('7','7',"f",$instituicao,$anousu);
$parametro[16] = '';
$parametro[17] = '';

$aOrcParametro = array_merge(
  $parametro[1],
  $parametro[2],
  $parametro[4],
  $parametro[5],
  $parametro[11],
  $parametro[12],
  $parametro[14],
  $parametro[15]
);
$where  = "  c61_instit in (".str_replace('-',', ',$db_selinstit).") "; 
$result_ant = db_planocontassaldo_matriz($anousu_ant,$dt_ini_ant,$dt_fim_ant,false,$where,"","true","true","",$aOrcParametro);
@pg_exec("drop table work_pl");

//--------- // ------------- // ------------- // ---------------
$texto[0]  = 'EXTERNAS(I)';
$texto[1]  = '  Aval ou fiança em operações de crédito';
$texto[2]  = '  Outras garantias nos Termos da LRF';
$texto[3]  = 'INTERNAS(II)';
$texto[4]  = '  Aval ou fiança em operações de crédito';
$texto[5]  = '   Outras garantias nos Termos da LRF';
$texto[6]  = 'TOTAL GARANTIAS CONCEDIDAS(III)=(I+II)';
$texto[7]  = 'RECEITA CORRENTE LIQUIDA - RCL(IV)';
$texto[8]  = '% do  TOTAL DAS GARANTIAS sobre a RCL';
$texto[9]  = 'LIMITE DEF. POR RESOLUÇÃO DO SENADO FEDERAL';

$texto[10] = 'EXTERNAS(V)';
$texto[11] = '  Aval ou fiança em operações de crédito';
$texto[12] = '  Outras garantias nos Termos da LRF';
$texto[13] = 'INTERNAS(VI)';
$texto[14] = '  Aval ou fiança em operações de crédito';
$texto[15] = '  Outras garantias nos Termos da LRF';
$texto[16] = 'TOTAL CONTRAGARANTIAS RECEBIDAS(VII)=(V+VI)';
$texto[17] = '';

for ($i=0; $i<20;$i++) {
  $valor_ant[$i] = 0;
  $valor1[$i] = 0;
  $valor2[$i] = 0;
  $valor3[$i] = 0;
}
if ($lTem1Quad){
  
  $cont = 1;
  $p = 0;   
  $result1Q = db_planocontassaldo_matriz($anousu,"{$anousu}-01-01",$dDataFim1Q,false,$where,"","true","false","",$aOrcParametro);
  @pg_exec("drop table work_pl");
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
     
     for($i=0;$i< pg_numrows($result1Q);$i++) {
      
      db_fieldsmemory($result1Q,$i);
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$parametro[$cont])) {
          $flag_contar = true;
        }
      } else {
        for($xx = 0; $xx < count($parametro[$cont]); $xx++){
           if ($estrutural == $parametro[$cont][$xx][0]) {
             $flag_contar = true;
             break;
           }
        }
      }
  
       if ($flag_contar == true){
         $valor1[$cont] += $saldo_final;
       }
      } 
    }
    $cont++;
  }
}
if ($lTem2Quad){
  
  $cont = 1;
  $p = 0;
  $result2Q = db_planocontassaldo_matriz($anousu,"{$anousu}-01-01",$dDataFim2Q,false,$where,"","true","false","",$aOrcParametro);
  @pg_exec("drop table work_pl");
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
    for($i=0;$i< pg_numrows($result2Q);$i++) {
      db_fieldsmemory($result2Q,$i);

      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);
  
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$parametro[$cont])) {
          $flag_contar = true;
        }
      } else {
        for($xx = 0; $xx < count($parametro[$cont]); $xx++){
           if ($estrutural == $parametro[$cont][$xx][0]) {
             $flag_contar = true;
             break;
           }
        }
      }
  
      if ($flag_contar == true){
        $valor2[$cont] += $saldo_final;
       }
      }
    }
    $cont++; 
  }
}
if ($lTem3Quad){
  
  $cont = 1;
  $p = 0;
  $result3Q = db_planocontassaldo_matriz($anousu,"{$anousu}-01-01",$dDataFim3Q,false,$where,"","true","false","",$aOrcParametro);
  @pg_exec("drop table work_pl");
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
     for($i=0;$i< pg_numrows($result3Q);$i++) {
      db_fieldsmemory($result3Q,$i);

      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);
  
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$parametro[$cont])) {
          $flag_contar = true;
        }
      } else {
        for($xx = 0; $xx < count($parametro[$cont]); $xx++){
           if ($estrutural == $parametro[$cont][$xx][0]) {
             $flag_contar = true;
             break;
           }
        }
      }
  
      if ($flag_contar == true){
        $valor3[$cont] += $saldo_final;
      }
      }
    }
    $cont++; 
  }
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
  
    for($i=0;$i< pg_numrows($result_ant);$i++) {
      db_fieldsmemory($result_ant,$i);

      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);
  
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$parametro[$cont])) {
          $flag_contar = true;
        }
      } else {
        for($xx = 0; $xx < count($parametro[$cont]); $xx++){
           if ($estrutural == $parametro[$cont][$xx][0]) {
             $flag_contar = true;
             break;
           }
        }
      }
  
      if ($flag_contar == true){
        $valor_ant[$cont] += $saldo_final;
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

///////////////////////////////////////////////////////////////////////////////////////////
//                                 receita corrente liquida.
//////////////////////////////////////////////////////////////////////////////////////////
$dt = split("-",$dt_ini);
$dt_ini_ant = $anousu_ant."-01-01";
$dt_fim_ant = $anousu_ant."-12-31";

// se o ano atual é bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
$dt = split('-',$dt_fim);  // mktime -- (mes,dia,ano)
$dt_ini_ant2 = $anousu_ant."-01-01";
$dt_fim_ant2 = $anousu_ant."-12-31";  

// receita 
$dt3 = split("-",$dt_ini);
$mes = $dt3[1];
if ($mes == 12){
  $mes  = 11;
} else {
  $mes += 1;
}

$data = $anousu_ant.$mes."-01";
if ($anousu_ant > 2007){
  $valor_ant[7] = calcula_rcl2($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit,false,27,$data);
} else {
  $valor_ant[7] = calcula_rcl($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit,false);
}
//$valor_ant[7] = calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fim_ant,$todasinstit,false,5,$data);
$matriz_rcl   = calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fim_ant,$todasinstit,true, 27,$data);

if (substr($periodo,1,1) == "Q") {
 	$total_rcl = calcula_rcl2($anousu,$anousu.'-01-01',$anousu.'-04-30',$todasinstit,false,27);
	$valor1[7] = $total_rcl + $matriz_rcl['maio']+$matriz_rcl['junho']+$matriz_rcl['julho']+$matriz_rcl['agosto']+$matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

	$total_rcl = calcula_rcl2($anousu,$anousu.'-01-01',$anousu.'-08-31',$todasinstit,false,27);
	$valor2[7] = $total_rcl + $matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

  $total_rcl = calcula_rcl2($anousu,$anousu.'-01-01',$anousu.'-12-31',$todasinstit,false,27);
	$valor3[7] = $total_rcl;
} else {
  $total_rcl = calcula_rcl2($anousu,$anousu.'-01-01',$anousu.'-06-30',$todasinstit,false,27);
	$valor1[7] = $total_rcl + $matriz_rcl['julho']+$matriz_rcl['agosto']+$matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];

  $total_rcl = calcula_rcl2($anousu,$anousu.'-01-01',$anousu.'-12-31',$todasinstit,false,27);
	$valor2[7] = $total_rcl;
}

// Variaveis do relatorio
$valor         = 0;
$res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(7,str_replace('-',',',$db_selinstit),$periodo));
if ($clconrelinfo->numrows > 0){
  for($i = 0; $i < $clconrelinfo->numrows; $i++){
    db_fieldsmemory($res_variaveis,$i);

    if ($c83_codigo == 296){
      $valor += $c83_informacao;
    }
  }
} else {
  $valor = 0;
}
// Fim das Variaveis
$perc_limite_senado = $valor;

@$valor_ant[8] = ($valor_ant[6] / $valor_ant[7]) * 100;
@$valor1[8] = ($valor1[6] / $valor1[7]) * 100;
@$valor2[8] = ($valor2[6] / $valor2[7]) * 100;
@$valor3[8] = ($valor3[6] / $valor3[7]) * 100;

if ($perc_limite_senado <= 0){
  $valor_perc = 32;
} else {
  $valor_perc = $perc_limite_senado;
}

$valor_ant[9] = $valor_ant[7] * ($valor_perc/100);
@$valor1[9] = $valor1[7] * ($valor_perc/100);
@$valor2[9] = $valor2[7] * ($valor_perc/100);
@$valor3[9] = $valor3[7] * ($valor_perc/100);

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
  $pdf->cell(110,$alt," RGF - ANEXO III (LRF, art. 55, inciso I, alínea \"c\" e art. 40, § 1º)",'B',0,"L",0);
  $pdf->cell(75,$alt,'R$ 1,00','B',1,"R",0);
  
  $pdf->cell(73,$alt,"",'R',0,"C",0);
  $pdf->cell(28,$alt,"SALDO DO",'R',0,"C",0);
  $pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'B',1,"C",0);
  
  $pdf->cell(73,$alt,"GARANTIAS CONCEDIDAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"Até 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 3º Quadrimestre",'B',1,"C",0);
  }
  else {
    $pdf->cell(42,$alt,"Até 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"Até 2º Semestre",'B',1,"C",0);
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
    if ($i == 9){
      if ($perc_limite_senado > 0){
        $texto[$i] .= "-".$perc_limite_senado."%";
      } else {
        $texto[$i] .= "-<%>";
      }
    }

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
  
  $pdf->cell(73,$alt,"CONTRAGARANTIAS RECEBIDAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"Até 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 3º Quadrimestre",'B',1,"C",0);
  }
  else {
    $pdf->cell(42,$alt,"Até 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"Até 2º Semestre",'B',1,"C",0);
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
  
  $pdf->Ln();
  notasExplicativas(&$pdf, 7, "{$periodo}", 185);
  
  // assinaturas
  $pdf->Ln(25);
  
  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();
  
}

$garantiascondedidas = $valor1[6];
$garantiasrcl        = $valor1[8];

if ($periodo!="1S"&&$periodo!="2S") {
  $limite_senado1 = $valor1[9];
  $limite_senado2 = $valor2[9];
  $limite_senado3 = $valor3[9];
} else {
  $limite_senado1 = $valor1[9];
  $limite_senado2 = $valor2[9];
}

//echo $limite_senado1." => ".$limite_senado2." => ".$limite_senado3; exit;

?>