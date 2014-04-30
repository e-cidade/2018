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


if (!isset($arqinclude)){

	include("fpdf151/pdf.php");
	include("fpdf151/assinatura.php");
	include("libs/db_sql.php");
	include("libs/db_libcontabilidade.php");
	include("libs/db_liborcamento.php");
	include("classes/db_orcparamrel_classe.php");
	include("classes/db_conrelinfo_classe.php");
  include("classes/db_db_config_classe.php");
	include("dbforms/db_funcoes.php");

	$classinatura = new cl_assinatura;
	$orcparamrel  = new cl_orcparamrel;
	$clconrelinfo   = new cl_conrelinfo;
  $cldb_config  = new cl_db_config;

	parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
	db_postmemory($HTTP_SERVER_VARS);

}

$limite = 0;
$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(8,str_replace('-',',',$db_selinstit)));
if ($clconrelinfo->numrows > 0 ){
  for ($x=0;$x < $clconrelinfo->numrows;$x++){
    db_fieldsmemory($res,$x);
    if ($c83_codigo ==272 ){
      $limite  = $c83_informacao;
    }
  }
	
	$anousu = db_getsession("DB_anousu");

}

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
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
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head2 = "INSTITUIÇÕES : ".$descr_inst;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DA DIVIDA CONSOLIDADA LIQUIDA";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

// verifica se foi informada datas iniciais e finais
$usa_datas = false;
if (strlen($dtini)>5 && strlen($dtfin)>5){
  $usa_datas = true;
}

$dt     = data_periodo($anousu,$periodo);
$dt_ini = split("-",$dt[0]);
$dt_fin = split("-",$dt[1]);

$period = "PERIODO: ".strtoupper(db_mes("01"))." A ".strtoupper(db_mes($dt_fin[1]))." DE ".$anousu;

/*
$period = '';
if ($periodo == "1Q"){
  $period = '1º QUADRIMESTRE';
}elseif($periodo == "2Q"){  
  $period = '2º QUADRIMESTRE'; 
}elseif($periodo == "3Q"){  
  $period = '3º QUADRIMESTRE'; 
}elseif ($periodo == "1S"){
  $period = '1º SEMESTRE';
}elseif ($periodo == "2S"){
  $period = '2º SEMESTRE'; 
}
*/
if ($usa_datas == false ){
  $head6 = $period;
} else {  
  $head6 = "PERIODO : ".db_formatar($dtini,'d')." à ".db_formatar($dtfin,'d');
}  

// datas fixas para os quadrimestres
//$anousu = db_getsession("DB_anousu");
$anousu_ant = ($anousu)-1;
$dtini_ant = '';
$dtfin_ant = $anousu_ant."-12-31";  

if ($periodo == "1S" || $periodo == "2S"){
  $dtini_01 = $anousu.'-01-01';
  $dtfin_01 = $anousu.'-06-30';
  $dtini_02 = $anousu.'-06-01';
  $dtfin_02 = $anousu.'-12-31';
} else {
  $dtini_01 = $anousu.'-01-01';
  $dtfin_01 = $anousu.'-04-30';
  $dtini_02 = $anousu.'-09-01';
  $dtfin_02 = $anousu.'-12-31';
}

if ($usa_datas == true){
  $dtini_01 = $dtini;
  $dtfin_01 = $dtfin;
}

/*
if ($periodo=='1Q'){
  $dtini_ant1 = $anousu_ant.'-05-01';
  $dtfin_ant1 = $anousu_ant.'-12-31'; 
}elseif ($periodo=='2Q'){
  $dtini_ant2 = $anousu_ant.'-09-01';
  $dtfin_ant2 = $anousu_ant.'-12-31'; 
}elseif ($periodo=='3Q'){
  $dtini_ant3 = $anousu.'-01-01';// virou o exercicio
  $dtfin_ant3 = $anousu_ant.'-12-31'; 
}elseif ($periodo=="1S"){
  $dtini_ant1s = $anousu_ant."01-01";
  $dtfin_ant1s = $anousu_ant."-06-30";
}elseif ($periodo=="2S"){
  $dtini_ant2s = $anousu_ant."06-01";
  $dtfin_ant2s = $anousu_ant."-12-31";
}
*/

if ($usa_datas==true) {
  $dt = split('-',$dtfin);  // mktime -- (mes,dia,ano)
  $dtini_ant = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]-364,$dt[0]));
}

$sele_work   = ' c61_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_01 = db_planocontassaldo_matriz($anousu,$dtini_01,$dtfin_01,false,$sele_work); 
@pg_exec("drop table work_pl"); 

$sele_work   = ' c61_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_02 = db_planocontassaldo_matriz($anousu,$dtini_02,$dtfin_02,false,$sele_work,"",false,"true"); 
//$result_02 = db_planocontassaldo_matriz($anousu,$dtini_02,$dtfin_02,false,$sele_work); 
@pg_exec("drop table work_pl"); 

//db_criatabela($result_02); exit;

//************************
// paramentos do relatório
//************************
$instituicao = str_replace("-",",",$db_selinstit);

$parametro[1]['estrut']  = $orcparamrel->sql_parametro('8','1',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[2]['estrut']  = $orcparamrel->sql_parametro('8','2',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[3]['estrut']  = $orcparamrel->sql_parametro('8','3',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[4]['estrut']  = $orcparamrel->sql_parametro('8','4',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[5]['estrut']  = $orcparamrel->sql_parametro('8','5',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[6]['estrut']  = $orcparamrel->sql_parametro('8','6',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[7]['estrut']  = $orcparamrel->sql_parametro('8','7',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[8]['estrut']  = $orcparamrel->sql_parametro('8','8',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[9]['estrut']  = $orcparamrel->sql_parametro('8','9',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[10]['estrut'] = $orcparamrel->sql_parametro('8','10',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[11]['estrut'] = $orcparamrel->sql_parametro('8','11',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[12]['estrut'] = $orcparamrel->sql_parametro('8','12',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[13]['estrut'] = $orcparamrel->sql_parametro('8','13',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[14]['estrut'] = $orcparamrel->sql_parametro('8','14',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[15]['estrut'] = $orcparamrel->sql_parametro('8','15',"f",$instituicao,db_getsession("DB_anousu"));
$parametro[16]['estrut'] = $orcparamrel->sql_parametro('8','16',"f",$instituicao,db_getsession("DB_anousu"));

for ($linha=1;$linha<=16;$linha++){
  $parametro[$linha]['ano']   = 0;	
  $parametro[$linha]['quad1'] = 0;	
  $parametro[$linha]['quad2'] = 0;	
  $parametro[$linha]['quad3'] = 0;	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//************************
//definição de variaveis
//**********************-*
$texto[1]['txt']  = "DIVÍDA CONSOLIDADA - DC (I)";
$texto[2]['txt']  = "  Dívida Mobiliária";
$texto[3]['txt']  = "  Dívida Contratual";
$texto[4]['txt']  = "  Precatórios Posteriores a 5.5.2000 (inclusive)";
$texto[5]['txt']  = "  Operações de Crédito Inferiores a 12 meses";
$texto[6]['txt']  = "  Parcelamento de Dividas";
$texto[7]['txt']  = "    De Tributos";
$texto[8]['txt']  = "    De Contribuições Sociais";
$texto[9] ['txt'] = "      Previdenciárias";
$texto[10]['txt'] = "      Demais Contribuições Sociais";
$texto[11]['txt'] = "    Do FGTS";
$texto[12]['txt'] = "  Provisões de PPP's";
$texto[13]['txt'] = "  Outras Dívidas";
$texto[14]['txt'] = "DEDUÇÕES(II)";
$texto[15]['txt'] = "  Ativo Disponivel";
$texto[16]['txt'] = "  Haveres Financeiros";
$texto[17]['txt'] = "  (-) Restos a Pagar Processados";
$texto[18]['txt'] = "OBRIGAÇÕES NÃO INTEGRANTES DA DC";
$texto[19]['txt'] = "  Precatórios anteriores a 5.5.2000";
$texto[20]['txt'] = "  Insuficiência Financeira";
$texto[21]['txt'] = "  Outras Obrigações"; 
$texto[22]['txt'] = "DÍVIDA CONSOLIDADA LÍQUIDA (DCL)=(I - II)";
$texto[23]['txt'] = "RECEITA CORRENTE LÍQUIDA - RCL";
$texto[24]['txt'] = "% da DC sobre a RCL";
$texto[25]['txt'] = "% da DCL sobre a RCL";
$texto[26]['txt'] = "LIMITE DEFIN. RESOL. DO SENADO FEDERAL";

for ($linha=1;$linha<=26;$linha++){
  
  $texto[$linha]['ano']		= 0;
  $texto[$linha]['quad1']	= 0;
  $texto[$linha]['quad2']	= 0;
  $texto[$linha]['quad3']	= 0;
  
}  

// exercico + primeiro quadrimestre ou seleção por periodo informado
for($i=0;$i< pg_numrows($result_01);$i++) {
  db_fieldsmemory($result_01,$i);
  
  for ($linha=1;$linha<=16;$linha++){
    
    if (in_array($estrutural,$parametro[$linha]['estrut'] )){  	
      $parametro[$linha]['ano']   += $saldo_anterior;
      $parametro[$linha]['quad1'] += $saldo_final;
    }
    
  } 
	
}

// segundo quadrimestre e terceiro quadrimestre
for($i=0;$i< pg_numrows($result_02);$i++) {
  db_fieldsmemory($result_02,$i);
  
  for ($linha=1;$linha<=16;$linha++){
    
    if (in_array($estrutural,$parametro[$linha]['estrut'] )){  	
      $parametro[$linha]['quad2'] += $saldo_anterior;
      $parametro[$linha]['quad3'] += $saldo_final;
    }
    
  }  
}

/////////// atribuição de valores
for ($i=1;$i<=4;$i++) {
  $pp = array('1'=>'ano','2'=>'quad1','3'=>'quad2','4'=>'quad3');
  
  // atribuições diretas
  $texto[2]  [$pp[$i]] =  $parametro[1][$pp[$i]];//div mobiliaria
  $texto[3]  [$pp[$i]] =  $parametro[2][$pp[$i]];//div contratual
  $texto[4]  [$pp[$i]] =  $parametro[3][$pp[$i]];//precatorios
  $texto[5]  [$pp[$i]] =  $parametro[4][$pp[$i]];//operações
  $texto[7]  [$pp[$i]] =  $parametro[5][$pp[$i]];//tributos
  $texto[9]  [$pp[$i]] =  $parametro[6][$pp[$i]];
  $texto[10] [$pp[$i]] =  $parametro[7][$pp[$i]];
  $texto[11] [$pp[$i]] =  $parametro[8][$pp[$i]];//fgts
  $texto[12] [$pp[$i]] =  $parametro[9] [$pp[$i]];//rpps
  $texto[13] [$pp[$i]] =  $parametro[10][$pp[$i]];//outras dividas
  $texto[15] [$pp[$i]] =  $parametro[11][$pp[$i]];//ativos
  $texto[16] [$pp[$i]] =  $parametro[12][$pp[$i]];//haveres
  $texto[17] [$pp[$i]] =  $parametro[13][$pp[$i]];//rp
  $texto[19] [$pp[$i]] =  $parametro[14][$pp[$i]];//precatorios
  $texto[20] [$pp[$i]] =  $parametro[15][$pp[$i]];//insuf
  $texto[21] [$pp[$i]] =  $parametro[16][$pp[$i]];//outras obrig
  
  // totalizadores
  $texto[8] [$pp[$i]] =  $texto[9][$pp[$i]] + $texto[10][$pp[$i]] ;
  $texto[6] [$pp[$i]] =  $texto[7][$pp[$i]] + $texto[8][$pp[$i]] + $texto[11][$pp[$i]];
  $texto[1] [$pp[$i]] =  $texto[2][$pp[$i]] + $texto[3][$pp[$i]] + $texto[4][$pp[$i]] + $texto[5][$pp[$i]] + $texto[6][$pp[$i]] + $texto[12][$pp[$i]] + $texto[13][$pp[$i]];
  
  $texto[14][$pp[$i]] =  $texto[15][$pp[$i]] + $texto[16][$pp[$i]] - $texto[17][$pp[$i]];
  $texto[18][$pp[$i]] =  $texto[19][$pp[$i]] + $texto[20][$pp[$i]] + $texto[21][$pp[$i]];
  
  // totalizador
  $texto[22][$pp[$i]] =  $texto[1][$pp[$i]] - $texto[14][$pp[$i]];
  
}

// receita corrente liquida

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}

// saldo do exercicio anterior
$total_rcl_ant = calcula_rcl($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit);
$texto[23]['ano'] = $total_rcl_ant;

if ($usa_datas == false) {
	
  $matriz = true; // retorna matriz dos meses
  $matriz_rcl = calcula_rcl($anousu_ant,$anousu_ant.'-01-01',$anousu_ant.'-12-31',$todasinstit,$matriz);
  
  // primeiro quadrimestre
  $total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-04-30',$todasinstit);
//	echo "1: $total_rcl<br>";
  $texto[23]['quad1'] = $total_rcl + $matriz_rcl['maio']+$matriz_rcl['junho']+$matriz_rcl['julho']+$matriz_rcl['agosto']+$matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];
//	echo "2: maio: " . $matriz_rcl['maio'] . " - junho: " . $matriz_rcl['junho'] . " - julho: " . $matriz_rcl['julho'] . " - agosto: " . $matriz_rcl['agosto'] . " - setembro: " . $matriz_rcl['setembro'] . " - outubro: " . $matriz_rcl['outubro'] . " - novembro: " . $matriz_rcl['novembro'] . " - dezembro: " . $matriz_rcl['dezembro'] . "<br>";
//	exit;
  
  // segundo quadrimestre
  $total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-08-31',$todasinstit);
  $texto[23]['quad2'] = $total_rcl + $matriz_rcl['setembro']+$matriz_rcl['outubro']+$matriz_rcl['novembro']+$matriz_rcl['dezembro'];
  
  // terceiro quadrimestre
  $total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$anousu.'-12-31',$todasinstit);
  $texto[23]['quad3'] = $total_rcl;
  
} else {
  
  $total_rcl = calcula_rcl($anousu_ant,$dtini_ant,$dtfin_ant,$todasinstit);
  $texto[23]['quad1'] = $total_rcl;
  
  $total_rcl = calcula_rcl($anousu,$anousu.'-01-01',$dtfin,$todasinstit);
  $texto[23]['quad1'] += $total_rcl;
  
}

// calculo dos limites
@$texto[24]['ano'] = $texto[1]['ano'] * 100 / $texto[23]['ano'];
@$texto[25]['ano'] = $texto[22]['ano']* 100 / $texto[23]['ano'];

@$texto[24]['quad1'] = $texto[1]['quad1'] * 100 / $texto[23]['quad1'];
@$texto[25]['quad1'] = $texto[22]['quad1']* 100 / $texto[23]['quad1'];

@$texto[24]['quad2'] = $texto[1]['quad2'] * 100 / $texto[23]['quad2'];
@$texto[25]['quad2'] = $texto[22]['quad2']* 100 / $texto[23]['quad2'];

@$texto[24]['quad3'] = $texto[1]['quad3'] * 100 / $texto[23]['quad3'];
@$texto[25]['quad3'] = $texto[22]['quad3']* 100 / $texto[23]['quad3'];

// limite
@$texto[26]['ano']   =   $texto[23]['ano']   * $limite / 100 ;
@$texto[26]['quad1'] =   $texto[23]['quad1'] * $limite / 100 ;
@$texto[26]['quad2'] =   $texto[23]['quad2'] * $limite / 100 ;
@$texto[26]['quad3'] =   $texto[23]['quad3'] * $limite / 100 ;

// se o perido é primeiro quadrimestre, os outros quadrimestre são zerados
if ($usa_datas == false ){
  if ($periodo=='1Q'){
    for ($linha=1;$linha<=26;$linha++){
      $texto[$linha]['quad2']= 0;
      $texto[$linha]['quad3']= 0;  
    }  
  } elseif ($periodo == '2Q'){
    for ($linha=1;$linha<=26;$linha++){
      $texto[$linha]['quad3']= 0;  
    }  
  }  
  
}  

// --

$pcol = array(1=>'ano',2=>'quad1',3=>'quad2',4=>'quad3');

if (!isset($arqinclude)){

	$pdf = new PDF(); 
	$pdf->Open(); 
	$pdf->AliasNbPages(); 
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','',7);
	$alt    = 4;
	$pagina = 1;

	$pdf->addpage();
	$pdf->ln();

	$pdf->cell(110,$alt,'LRF, Art. 55, Inciso I, Alínea "b" - Anexo II','B',0,"L",0);
	$pdf->cell(75,$alt,'R$ Unidades','B',1,"R",0);

	$pdf->cell(73,$alt,"",'R',0,"C",0);
	$pdf->cell(28,$alt,"SALDO DO",'R',0,"C",0);
	if ($usa_datas==false){
		$pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'B',1,"C",0);
	} else {
		$pdf->cell(84,$alt,"SALDO DO PERIODO ",'B',1,"C",0);
	}  
	$pdf->cell(73,$alt,"ESPECIFICAÇÃO",'BR',0,"C",0);
	$pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);

	if ($usa_datas==true){
		$pdf->cell(28*3,$alt,"PERIODO ".db_formatar($dtini,'d')." à ".db_formatar($dtfin,'d'),'B',1,"C",0);
	} else {  
    if ($periodo == "1S" || $periodo == "2S"){
		  $pdf->cell(42,$alt,"ATÉ 1º Semestre",'BR',0,"C",0);
		  $pdf->cell(42,$alt,"ATÉ 2º Semestre",'B',1,"C",0);
    } else {
  		$pdf->cell(28,$alt,"ATÉ 1º Quadrimestre",'BR',0,"C",0);
	  	$pdf->cell(28,$alt,"ATÉ 2º Quadrimestre",'BR',0,"C",0);
		  $pdf->cell(28,$alt,"ATÉ 3º Quadrimestre",'B',1,"C",0);
    }
	}

	// testar se deducoes (14) foram negativas, jogar para insuficiencia...

	for ($linha=1; $linha <= 21;$linha++) {
		$pdf->cell(73,$alt,$texto[$linha]['txt'],'R',0,"L",0);
		$pdf->cell(28,$alt,db_formatar($texto[$linha]['ano'],'f'),'R',0,"R",0);
		
		if ($usa_datas == true ){
			$pdf->cell(28*3,$alt,db_formatar($texto[$linha]['quad1'],'f'),'' ,0,"R",0); 
		} else {  
      if ($periodo == "1S" || $periodo == "2S"){
			  $pdf->cell(42,$alt,db_formatar($texto[$linha]['quad2'],'f'),'R',0,"R",0);
        if ($periodo == "2S"){
  			  $pdf->cell(42,$alt,db_formatar($texto[$linha]['quad3'],'f'),'' ,0,"R",0); 
        } else {
  			  $pdf->cell(42,$alt,db_formatar("0.00",'f'),'' ,0,"R",0); 
        }
      } else {
			  $pdf->cell(28,$alt,db_formatar($texto[$linha]['quad1'],'f'),'R',0,"R",0);
			  $pdf->cell(28,$alt,db_formatar($texto[$linha]['quad2'],'f'),'R',0,"R",0);
			  $pdf->cell(28,$alt,db_formatar($texto[$linha]['quad3'],'f'),'' ,0,"R",0); 
      }
		}   
		
		$pdf->Ln();
		
	}

	// inprime bloco abaixo
	for ($linha=22; $linha <= 26;$linha++) {
		$pdf->cell(73,$alt,$texto[$linha]['txt'],'TBR',0,"L",0);
		$pdf->cell(28,$alt,db_formatar($texto[$linha]['ano'],'f'),'TBR',0,"R",0);
		
		if ($usa_datas == true ){
			$pdf->cell(28*3,$alt,db_formatar($texto[$linha]['quad1'],'f'),'TB' ,0,"R",0); 
		} else {  
      if ($periodo == "1S" || $periodo == "2S"){
			  $pdf->cell(42,$alt,db_formatar($texto[$linha]['quad2'],'f'),'TBR',0,"R",0);
        if ($periodo == "2S"){
  			  $pdf->cell(42,$alt,db_formatar($texto[$linha]['quad3'],'f'),'TB' ,0,"R",0); 
        } else {
  			  $pdf->cell(42,$alt,db_formatar("0.00",'f'),'TB' ,0,"R",0); 
        }
      } else {
  			$pdf->cell(28,$alt,db_formatar($texto[$linha]['quad1'],'f'),'TBR',0,"R",0);
	  		$pdf->cell(28,$alt,db_formatar($texto[$linha]['quad2'],'f'),'TBR',0,"R",0);
		  	$pdf->cell(28,$alt,db_formatar($texto[$linha]['quad3'],'f'),'TB' ,0,"R",0); 
      }
		}
		$pdf->Ln();
	}

	$pdf->setfont('arial','b',6);
	$pdf->cell(185,$alt,'Fonte: Contabilidade','',1,"L",0); 

	$linhas = 20;

	if($trajetoria=="S"){
		$pdf->setfont('arial','',6);
		$pdf->cell(185,$alt,"TRAJETÓRIA DE AJUSTE DA DÍVIDA CONSOLIDADA LÍQUIDA EM CADA EXERCÍCIO FINANCEIRO","TB",1,"C",0);

    for($linha = 1; $linha <= 16; $linha++){
      $var_info[$linha][$pcol[1]] = 0;
      $var_info[$linha][$pcol[2]] = 0;
      $var_info[$linha][$pcol[3]] = 0;
      $var_info[$linha][$pcol[4]] = 0;
    }

		$pdf->cell(39,($alt*3),"Exercício Financeiro",'BR',0,"C",0);
		for($linha = 1; $linha <= 4; $linha++){
       if ($linha == 4){
	  	   $pdf->cell(29,$alt,$var_info[$linha][$pcol[1]],'B',1,"C",0);   // ano
  		 } else {
  		   $pdf->cell(39,$alt,$var_info[$linha][$pcol[1]],'BR',0,"C",0);  // ano
  	   }
		}  
		
		$pdf->setX(49);
		
		for($linha = 1; $linha <= 4; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($var_info[$linha][$pcol[1]]!=2001){
	  			if ($linha == 4){
  					$pdf->cell(29,$alt,"Semestre",'B',1,"C",0);
  				}    
  				else{
  					$pdf->cell(39,$alt,"Semestre",'BR',0,"C",0);
  				}
  			} else{
  				$pdf->cell(39,$alt,"Semestre",'BR',0,"C",0);
  			}
      } else {
  			if ($var_info[$linha][$pcol[1]]!=2001){
	  			if ($linha == 4){
  					$pdf->cell(29,$alt,"Quadrimestre",'B',1,"C",0);
  				}    
  				else{
  					$pdf->cell(39,$alt,"Quadrimestre",'BR',0,"C",0);
  				}
  			} else{
  				$pdf->cell(39,$alt,"3º Quadrimestre",'BR',0,"C",0);
  			}
	  	}
    }
		
		$pdf->setX(49);
		
		for($linha = 1; $linha <= 4; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 4){
	  			$pdf->cell(15,$alt,"1º" ,'BR',0,"C",0);
  				$pdf->cell(14,$alt,"2º",'B',1,"C",0);
  			} else {
  				if($var_info[$linha][$pcol[1]]!=2001){
  					$pdf->cell(20,$alt,"1º",'BR',0,"C",0);
  					$pdf->cell(19,$alt,"2º",'BR',0,"C",0);
  				}
	  			else {
		  			$pdf->cell(20,$alt,"DCL",'BR',0,"C",0);
  					$pdf->cell(19,$alt,"Excedente",'B',0,"C",0);
  				}
	  		}
      } else {
  			if ($linha == 4){
	  			$pdf->cell(10,$alt,"1º" ,'BR',0,"C",0);
  				$pdf->cell(10,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell( 9,$alt,"3º",'B',1,"C",0);
  			} else {
  				if($var_info[$linha][$pcol[1]]!=2001){
  					$pdf->cell(13,$alt,"1º",'BR',0,"C",0);
  					$pdf->cell(13,$alt,"2º",'BR',0,"C",0);
  					$pdf->cell(13,$alt,"3º",'BR',0,"C",0);
  				}
	  			else {
		  			$pdf->cell(13,$alt,"DCL",'BR',0,"C",0);
  					$pdf->cell(13,$alt,"Excedente",'BR',0,"C",0);
  					$pdf->cell(13,$alt,"Redutor",'BR',0,"C",0);
  				}
	  		}
      }
		}
		
		$pdf->cell(39,$alt,"% da DCL sobre a RCL",'BR',0,"C",0);
		
		for($linha = 1; $linha <= 4; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 4){
	  			$pdf->cell(15,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(14,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(20,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(19,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      } else {
  			if ($linha == 4){
	  			$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0); // quad1
  				$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(9, $alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% Limite de Endividamento",'BR',0,"C",0);
		for($linha = 1; $linha <= 4; $linha++){
       if ($linha == 4){
	  	   $pdf->cell(29,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'B',1,"R",0);  
  		 } else{
  		   $pdf->cell(39,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
  		 }
		}
		
		$pdf->cell(185,$alt,"","TB",1,"C",0);
		
		// 2005 a 2008
		$pdf->cell(39,($alt*3),"Exercício Financeiro",'BR',0,"C",0);
		for($linha = 5; $linha <= 8; $linha++){
			if ($linha == 8){
				$pdf->cell(29,$alt,$var_info[$linha][$pcol[1]],'B',1,"C",0);   // ano
			} else {
				$pdf->cell(39,$alt,$var_info[$linha][$pcol[1]],'BR',0,"C",0);  // ano
			}
		}  
		
		$pdf->setX(49);
		
		for($linha = 5; $linha <= 8; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 8){
	  			$pdf->cell(29,$alt,"Semestre",'B',1,"C",0);
  			}    
	  		else{
  				$pdf->cell(39,$alt,"Semestre",'BR',0,"C",0);
  			}
      } else {
  			if ($linha == 8){
	  			$pdf->cell(29,$alt,"Quadrimestre",'B',1,"C",0);
  			}    
	  		else{
  				$pdf->cell(39,$alt,"Quadrimestre",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->setX(49);
		
		for($linha = 5; $linha <= 8; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 8){
	  			$pdf->cell(15,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(14,$alt,"2º",'B', 1,"C",0);
  			} else {
  				$pdf->cell(20,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(19,$alt,"2º",'BR',0,"C",0);
  			}
      } else {
  			if ($linha == 8){
	  			$pdf->cell(10,$alt,"1º" ,'BR',0,"C",0);
  				$pdf->cell(10,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell( 9,$alt,"3º",'B',1,"C",0);
  			} else {
  				$pdf->cell(13,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"3º",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% da DCL sobre a RCL",'BR',0,"C",0);
		
		for($linha = 5; $linha <= 8; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 8){
	  			$pdf->cell(15,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(14,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(20,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(19,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      } else {
  			if ($linha == 8){
	  			$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0); // quad1
  				$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(9, $alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% Limite de Endividamento",'BR',0,"C",0);
		for($linha = 5; $linha <= 8; $linha++){
			if ($linha == 8){
				$pdf->cell(29,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'B',1,"R",0);  
			} else{
				$pdf->cell(39,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
			}
		}
		
		$pdf->cell(185,$alt,"","TB",1,"C",0);
		
		// 2009 a 2012
		$pdf->cell(39,($alt*3),"Exercício Financeiro",'BR',0,"C",0);
		for($linha = 9; $linha <= 12; $linha++){
			if ($linha == 12){
				$pdf->cell(29,$alt,$var_info[$linha][$pcol[1]],'B',1,"C",0);   // ano
			} else {
				$pdf->cell(39,$alt,$var_info[$linha][$pcol[1]],'BR',0,"C",0);  // ano
			}
		}  
		
		$pdf->setX(49);
		
		for($linha = 9; $linha <= 12; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 12){
  				$pdf->cell(29,$alt,"Semestre",'B',1,"C",0);
  			}    
  			else{
  				$pdf->cell(39,$alt,"Semestre",'BR',0,"C",0);
  			}
      } else {
  			if ($linha == 12){
  				$pdf->cell(29,$alt,"Quadrimestre",'B',1,"C",0);
  			}    
  			else{
  				$pdf->cell(39,$alt,"Quadrimestre",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->setX(49);
		
		for($linha = 9; $linha <= 12; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
			  if ($linha == 12){
  				$pdf->cell(15,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(14,$alt,"2º",'B',1,"C",0);
  			} else {
  				$pdf->cell(20,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(19,$alt,"2º",'BR',0,"C",0);
  			}
      } else {
			  if ($linha == 12){
  				$pdf->cell(10,$alt,"1º" ,'BR',0,"C",0);
  				$pdf->cell(10,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell( 9,$alt,"3º",'B',1,"C",0);
  			} else {
  				$pdf->cell(13,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"3º",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% da DCL sobre a RCL",'BR',0,"C",0);
		
		for($linha = 9; $linha <= 12; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 12){
  				$pdf->cell(15,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(14,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(20,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(19,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      } else {
  			if ($linha == 12){
  				$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0); // quad1
  				$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
  				$pdf->cell(9, $alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% Limite de Endividamento",'BR',0,"C",0);
		for($linha = 9; $linha <= 12; $linha++){
			if ($linha == 12){
				$pdf->cell(29,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'B',1,"R",0);  
			} else{
				$pdf->cell(39,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
			}
		}
		
		$pdf->cell(185,$alt,"","TB",1,"C",0);
		
		// 2013 a 2016
		$pdf->cell(39,($alt*3),"Exercício Financeiro",'BR',0,"C",0);
		for($linha = 13; $linha <= 16; $linha++){
			if ($linha == 16){
				$pdf->cell(29,$alt,$var_info[$linha][$pcol[1]],'B',1,"C",0);   // ano
			} else {
				$pdf->cell(39,$alt,$var_info[$linha][$pcol[1]],'BR',0,"C",0);  // ano
			}
		}  
		
		$pdf->setX(49);
		
		for($linha = 13; $linha <= 16; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 16){
  				$pdf->cell(29,$alt,"Semestre",'B',1,"C",0);
  			}    
  			else{
  				$pdf->cell(39,$alt,"Semestre",'BR',0,"C",0);
  			}
      } else {
  			if ($linha == 16){
  				$pdf->cell(29,$alt,"Quadrimestre",'B',1,"C",0);
  			}    
  			else{
  				$pdf->cell(39,$alt,"Quadrimestre",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->setX(49);
		
		for($linha = 13; $linha <= 16; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 16){
	  			$pdf->cell(15,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(14,$alt,"2º",'B',1,"C",0);
  			} else {
  				$pdf->cell(20,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(19,$alt,"2º",'BR',0,"C",0);
  			}
      } else {
  			if ($linha == 16){
	  			$pdf->cell(10,$alt,"1º" ,'BR',0,"C",0);
  				$pdf->cell(10,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell( 9,$alt,"3º",'B',1,"C",0);
  			} else {
  				$pdf->cell(13,$alt,"1º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"2º",'BR',0,"C",0);
  				$pdf->cell(13,$alt,"3º",'BR',0,"C",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% da DCL sobre a RCL",'BR',0,"C",0);
		
		for($linha = 13; $linha <= 16; $linha++){
      if ($periodo == "1S" || $periodo == "2S"){
  			if ($linha == 16){
  				$pdf->cell(15,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
	  			$pdf->cell(14,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(20,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(19,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      } else {
  			if ($linha == 16){
  				$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0); // quad1
	  			$pdf->cell(10,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0); // quad2
		  		$pdf->cell(9, $alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'B',1,"R",0);  // quad3
  			} else{
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[3]],"f"),'BR',0,"R",0);
  				$pdf->cell(13,$alt,db_formatar($var_info[$linha][$pcol[4]],"f"),'BR',0,"R",0);
  			}
      }
		}
		
		$pdf->cell(39,$alt,"% Limite de Endividamento",'BR',0,"C",0);
		for($linha = 13; $linha <= 16; $linha++){
			if ($linha == 16){
				$pdf->cell(29,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'B',1,"R",0);  
			} else{
				$pdf->cell(39,$alt,db_formatar($var_info[$linha][$pcol[2]],"f"),'BR',0,"R",0);
			}
		}
		
		$linhas = 10;
	} 

	$pdf->ln($linhas);
	// assinaturas

	assinaturas(&$pdf,&$classinatura,'GF');

	$pdf->Output();

}

if ($periodo == "1Q") {
  $total_divida			= $texto[22]['quad1'];
  $percdclsobrercl  = $texto[25]['quad1'];
	$limite_divida		= $texto[26]['quad1'];
} elseif ($periodo == "2Q" || $periodo == "1S") {
  $total_divida			= $texto[22]['quad2'];
  $percdclsobrercl  = $texto[25]['quad2'];
	$limite_divida		= $texto[26]['quad2'];
} elseif ($periodo == "3Q" || $periodo == "2S") {
  $total_divida			= $texto[22]['quad3'];
  $percdclsobrercl  = $texto[25]['quad3'];
	$limite_divida		= $texto[26]['quad3'];
}

?>