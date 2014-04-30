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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conrelinfo_classe.php");
$classinatura = new cl_assinatura;
$orcparamrel = new cl_orcparamrel;
$clconrelinfo = new cl_conrelinfo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

// variaveis 
$anousu = db_getsession("DB_anousu");
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
if ($bimestre == 1) {
 $dt_ini = "-01-01";
 $dt_fin = "-02-28";
} elseif ($bimestre == 2) {
 $dt_ini = "-03-01";  
 $dt_fin = "-04-30";
} elseif ($bimestre == 3) {
 $dt_ini = "-05-01";  
 $dt_fin = "-06-30";
} elseif ($bimestre == 4) {
 $dt_ini = "-07-01";  
 $dt_fin = "-08-31";
} elseif ($bimestre == 5) {
 $dt_ini = "-09-01";  
 $dt_fin = "-10-31";
} elseif ($bimestre == 6) {
 $dt_ini = "-11-01";  
 $dt_fin = "-12-31";
}
$dataini = ($anousu - 1).''.$dt_ini;
$datafin = $anousu.''.$dt_fin; 
$dt_ini = $dataini;
$dt_fin = $datafin;
// -------------------------------------dt_fin
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
    }else{
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}
$dt = datas_bimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
$dt_ini= $dt[0]; // data inicial do período
$dt_fin= $dt[1]; // data final do período

$head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTARIA";
$head3 = "DEMONSTRATIVO DA RECEITA CORRENTE LIQUIDA";
$head4 = "ORÇAMENTO FICAL E DA SEGURIDADE SOCIAL ";
$dt1d = array();
$dt2d = array();
$dtd1 = split('-',$dt_ini);
$dtd2 = split('-',$dt_fin);
$dt1 = "$dtd1[2]/$dtd1[1]/$dtd1[0]";
$dt2 = "$dtd2[2]/$dtd2[1]/$dtd2[0]";
$txt = strtoupper(db_mes('01'));
$dt  = split("-",$dt_fin);
$txt.= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";;
$dt  = split("-",$dt_ini);
$txt.= strtoupper(db_mes($dt[1]))."-";
$dt  = split("-",$dt_fin);
$txt.= strtoupper(db_mes($dt[1]));
$head5 = "$txt";

//$head5 = "PERÍODO DE REFERÊNCIA:  $dt1 à  $dt2  ";

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head6 = "INSTITUIÇÕES : ".$descr_inst;
// -------------------------------

$rec_corr[0] = $orcparamrel->sql_parametro('5','0');
$rec_corr[1] = $orcparamrel->sql_parametro('5','1');
$rec_corr[2] = $orcparamrel->sql_parametro('5','2');
$rec_corr[3] = $orcparamrel->sql_parametro('5','3');
$rec_corr[4] = $orcparamrel->sql_parametro('5','4');
$rec_corr[5] = $orcparamrel->sql_parametro('5','5');
$rec_corr[6] = $orcparamrel->sql_parametro('5','6');
$rec_corr[7] = $orcparamrel->sql_parametro('5','7');

$deucoes[0] = $orcparamrel->sql_parametro('5','8');
$deucoes[1] = $orcparamrel->sql_parametro('5','9');
$deucoes[2] = $orcparamrel->sql_parametro('5','10');
$deucoes[3] = $orcparamrel->sql_parametro('5','12');
$deucoes[4] = $orcparamrel->sql_parametro('5','13');
$deucoes[5] = $orcparamrel->sql_parametro('5','14');
$deucoes[6] = $orcparamrel->sql_parametro('5','15');
$deucoes[7] = $orcparamrel->sql_parametro('5','16');
$deucoes[8] = $orcparamrel->sql_parametro('5','17');
$deucoes[9] = $orcparamrel->sql_parametro('5','18');

$param[0] = $orcparamrel->sql_parametro('5','0');
$param[1] = $orcparamrel->sql_parametro('5','1');
$param[2] = $orcparamrel->sql_parametro('5','2');
$param[3] = $orcparamrel->sql_parametro('5','3');
$param[4] = $orcparamrel->sql_parametro('5','4');
$param[5] = $orcparamrel->sql_parametro('5','5');
$param[6] = $orcparamrel->sql_parametro('5','6');
$param[7] = $orcparamrel->sql_parametro('5','7');
$param[8] = $orcparamrel->sql_parametro('5','8');
$param[9] = $orcparamrel->sql_parametro('5','9');
$param[10] = $orcparamrel->sql_parametro('5','10');
$param[11] = $orcparamrel->sql_parametro('5','12');
$param[12] = $orcparamrel->sql_parametro('5','13');
$param[13] = $orcparamrel->sql_parametro('5','14');
$param[14] = $orcparamrel->sql_parametro('5','15');
$param[15] = $orcparamrel->sql_parametro('5','16');
$param[16] = $orcparamrel->sql_parametro('5','17');
$param[17] = $orcparamrel->sql_parametro('5','18');

$rec[0]  = "RECEITA CORRENTE(I)";
$rec[1]  = "  Receitas Tributárias";
$rec[2]  = "  Receitas de Contribuições";
$rec[3]  = "  Receitas Patrimonial";
$rec[4]  = "  Receitas Agropecuária";
$rec[5]  = "  Receita Industrial";
$rec[6]  = "  Receita de Serviços";
$rec[7]  = "  Transferências Correntes";
$rec[8]  = "  Outras Receitas Correntes";
$rec[9] = "DEDUÇÕES (II)";
$rec[10] = "  Transferencia Continucionais e Legais";
$rec[11] = "  Contrib Empregadores e Trab. p/ Seg. Social";
$rec[12] = "  Contrib Plano Seg. Social Servidor";
$rec[13] = "    Servidor";
$rec[14] = "  Contrib p/ Custeio Pensões Militares";
$rec[15] = "  Compesações Financ entre Regimes Previd.";
$rec[16] = "  Deduções de Recitas para Formação do FUNDEF";
$rec[17] = "  Contribuições p/ PIS/PASEP";
$rec[18] = "    PIS";
$rec[19] = "    PASEP";
$rec[20] = "RECEITA CORRENTE LIQUIDA";
// Levantamento de Variaveis
$contmes = (18 * ($dtd2[1] + 1)) - 18;

$mes = 0; //$dtd2[1];
for ($x=0;$x<18;$x++){
 for ($y=0;$y<12;$y++){
   $valor_l[$x][$y] = 0;
   $previd[$x] = 0;
   $igor[$x] =0;
 }
} 

$w_instit = str_replace('-',', ',$db_selinstit);
$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores('5',$w_instit));
// db_criatabela($res);
// exit;
//echo($c83_codigo.' -- '.$c83_variavel.'-- '.$c83_informacao);exit;
$c = 0;
//print($contmes.'--'.$c83_codigo.'--'.$c83_variavel);
if ($contmes < 215 and $dtd2[1] != 12) {
  db_fieldsmemory($res,$contmes);
}  
for ($c=$contmes;$c<$clconrelinfo->numrows;$c++){
 for ($x=0;$x<18;$x++) {
  $valor_l[$x][$mes] = $c83_informacao;
  $contmes = $contmes + 1;
  if ($contmes < 216){
   db_fieldsmemory($res,$contmes);
  }elseif ($contmes > 216) {
    break;
  }	
  $c++;
 }

 
 $mes++;
 if ($mes == (12 - $dtd2[1])) {
   break;
 }  
 $c = $c - 1; 
}

//print_r($valor_l[2]);exit;
$mes = $mes - 1;
if ($dtd2[1] == 12){
  $mes = -1;
}  
// gera matris com todos os estruturais selecionados nas configurações do relatorio
$m_todos = $orcparamrel->sql_parametro('5');
$virgula='';
$lista = '(';
$tt = sizeof($m_todos);
for ($x=0; $x <sizeof($m_todos);$x++){
  $lista .= $virgula."'".$m_todos[$x]."'";
  if ($x == $tt-1)  	
  $virgula ='';
  else $virgula =',';   	  
}

$lista = $lista.')';
$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->dtini = $dataini;
$clreceita_saldo_mes->dtfim = $datafin;
$clreceita_saldo_mes->usa_datas = 'sim';
$clreceita_saldo_mes->instit = "".str_replace('-',', ',$db_selinstit)." ";
$clreceita_saldo_mes->sql_record();
//echo $clreceita_saldo_mes->sql;exit;
//print($dataini."---".$datafin);
//db_criatabela($clreceita_saldo_mes->result);exit;
$voltmes = $mes;
$p = 0;
for ($p=0;$p<17;$p++) {
  for ($i=0;$i<$clreceita_saldo_mes->numrows;$i++){
    db_fieldsmemory($clreceita_saldo_mes->result,$i);
    $estrutural = $o57_fonte;
    $o70_valor = $o70_valor + $adicional;
    if (in_array($estrutural,$param[$p])){
       
       $previd["$p"] += $o70_valor;
       $mes = $voltmes;
        if ($dtd2[1] >= 1) {
         $mes =  $mes + 1;
         $valor_l[$p][$mes] += $janeiro;
        }	
        if ($dtd2[1] >= 2) {	
    	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $fevereiro;
        }
        if  ($dtd2[1] >= 3) {	      	
      	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $marco;
        }	
        if  ($dtd2[1] >= 4) {	
  	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $abril;
        }
        if  ($dtd2[1] >= 5) {	
  	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $maio;
        }
        if  ($dtd2[1] >= 6) {	
	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $junho;
        }
        if  ($dtd2[1] >= 7) {
   	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $julho;
        }
        if  ($dtd2[1] >= 8) {	
 	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $agosto;
        }
        if  ($dtd2[1] >= 9) {	
  	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $setembro;
        }
        if  ($dtd2[1] >= 10) {	
	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $outubro;
        }
        if  ($dtd2[1] >= 11) {	
  	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $novembro;
        }
        if  ($dtd2[1] >= 12) {	
 	  $mes =  $mes + 1;
          $valor_l[$p][$mes] += $dezembro;
        }
    }    
  } 
} 
//print_r($previd[5]);
//print_r($param[5]);
//db_criatabela($clreceita_saldo_mes->result);exit;

//print_r($valor_l[2]);exit;

$desc_mes1 = 'Janeiro';
$desc_mes2 = 'Fevereiro';
$desc_mes3 = 'Março';
$desc_mes4 = 'Abril';
$desc_mes5 = 'Maio';
$desc_mes6 = 'Junho';
$desc_mes7 = 'Julho';
$desc_mes8 = 'Agosto';
$desc_mes9 = 'Setembro';
$desc_mes10 = 'Outubro';
$desc_mes11 = 'Novembro';
$desc_mes12 = 'Dezembro';

$mes_dresc[0] = 'Janeiro';
$mes_dresc[1] = 'Fevereiro';
$mes_dresc[2] = 'Março';
$mes_dresc[3] = 'Abril';
$mes_dresc[4] = 'Maio';
$mes_dresc[5] = 'Junho';
$mes_dresc[6] = 'Julho';
$mes_dresc[7] = 'Agosto';
$mes_dresc[8] = 'Setembro';
$mes_dresc[9] = 'Outubro';
$mes_dresc[10] = 'Novembro';
$mes_dresc[11] = 'Dezembro';


$dtd2[1] = $dtd2[1] + 1;
for ($x=0;$x<12;$x++) {
  if ($dtd2[1] == 1){
    $mes_dresc[$x] = $desc_mes1; 
  }elseif ($dtd2[1] == 2){
    $mes_dresc[$x] = $desc_mes2;
  }elseif ($dtd2[1] == 3){
    $mes_dresc[$x] = $desc_mes3;
  }elseif ($dtd2[1] == 4){
    $mes_dresc[$x] = $desc_mes4;
  }elseif ($dtd2[1] == 5){
    $mes_dresc[$x] = $desc_mes5;
  }elseif ($dtd2[1] == 6){
    $mes_dresc[$x] = $desc_mes6;
  }elseif ($dtd2[1] == 7){
    $mes_dresc[$x] = $desc_mes7;
  }elseif ($dtd2[1] == 8){
    $mes_dresc[$x] = $desc_mes8;
  }elseif ($dtd2[1] == 9){
    $mes_dresc[$x] = $desc_mes9;
  }elseif ($dtd2[1] == 10){
    $mes_dresc[$x] = $desc_mes10;
  }elseif ($dtd2[1] == 11){ 
    $mes_dresc[$x] = $desc_mes11;
  }elseif ($dtd2[1] == 12){
    $mes_dresc[$x] = $desc_mes12;
    $dtd2[1] = 0;
  }  
  $dtd2[1] = $dtd2[1] + 1;
} 
//////////////////////////////////////////////////////  Valores Calculados ////////////////////////////////////////////////////
for ($x=0;$x<21;$x++){
 for ($y=0;$y<12;$y++){
   $valor_rec[$x][$y] = 0;
   $valor_prev[$x] = 0;
 }
} 
$k = 0;
for ($x=1;$x<8;$x++){
 for ($y=0;$y<12;$y++){
   $valor_rec[$x][$y] += $valor_l[($x - 1)][$y];
 }
 $valor_prev[$x] = $previd["$k"];
 $k = $k + 1;
} 

for ($x=0;$x<8;$x++){
 for ($y=0;$y<12;$y++){
   $valor_rec[0][$y] += $valor_l[$x][$y];
 }
 $valor_prev[0] += $previd[$x];
} 
$valor_prev['08'] = abs($previd[7]);
$valor_prev[11] = abs($previd[9]);
$valor_prev[10] = abs($previd[8]);
$valor_prev[12] = abs($previd[11]);
$valor_prev[13] = abs($previd[11]);
$valor_prev[18] = abs($previd[16]);
$valor_prev[19] = abs($previd[16]);

for ($y=0;$y<12;$y++){
 $valor_rec[11][$y] += abs($valor_l[9][$y]);
 $valor_rec['08'][$y] = abs($valor_l['7'][$y]);
 $valor_rec['02'][$y] = abs($valor_l['1'][$y]);
}
for ($y=0;$y<12;$y++){
 $valor_rec[10][$y] += abs($valor_l[8][$y]); 
}

for ($y=0;$y<12;$y++){
 $valor_rec[12][$y] += abs($valor_l[11][$y]); 
}

for ($y=0;$y<12;$y++){
 $valor_rec[13][$y] += abs($valor_l[11][$y]); 
}

for ($y=0;$y<12;$y++){
   $valor_rec[18][$y] += abs($valor_l[16][$y]);
}
for ($y=0;$y<12;$y++){
   $valor_rec[19][$y] += abs($valor_l[17][$y]);
}

for ($x=16;$x<17;$x++){
 for ($y=0;$y<12;$y++){
   $valor_rec[17][$y] += abs($valor_l[$x][$y]);
 }
 $valor_prev[17] += abs($previd[$x]);
}

for ($y=0;$y<12;$y++){
  $valor_rec[14][$y] += abs($valor_l[12][$y]);
}
$valor_prev[14] += abs($previd[12]);
for ($y=0;$y<12;$y++){
  $valor_rec[15][$y] += abs($valor_l[13][$y]);
}
$valor_prev[15] += abs($previd[13]);
for ($y=0;$y<12;$y++){
  $valor_rec[16][$y] += abs($valor_l[14][$y]);
}
$valor_prev[16] += abs($previd[14]);

for ($y=0;$y<12;$y++){
  $valor_rec[9][$y] += abs($valor_l[8][$y]) + abs($valor_l[9][$y]) + 
                       abs($valor_l[11][$y]) + abs($valor_l[12][$y]) + 
		       abs($valor_l[13][$y]) + abs($valor_l[14][$y]) + 
		       abs($valor_l[16][$y]) + abs($valor_l[17][$y]);
}
$valor_prev[9] += abs($previd[8]) + abs($previd[9]) +
                   abs($previd[11]) + abs($previd[12]) +
                   abs($previd[13]) + abs($previd[14]) +
	           abs($previd[16]) + abs($previd[17]); 

for ($y=0;$y<12;$y++){
   $valor_rec[20][$y] +=  abs($valor_rec[0][$y]) - abs($valor_rec[9][$y]);
}

$valor_prev[20] = abs($valor_prev[0]) - abs($valor_prev[9]);

for ($x=0;$x<21;$x++){
  $valor_tot[$x] = 0;
}   
for ($x=0;$x<21;$x++){
 for ($y=0;$y<12;$y++){
   $valor_tot[$x] +=  abs($valor_rec[$x][$y]);
 }
} 

for ($y=0;$y<12;$y++){
 $valor_rec['08'][$y] = abs($valor_l['7'][$y]);
}
$valor_tot['08'] = 0;
for ($y=0;$y<12;$y++){
 $valor_tot['08'] += abs($valor_l['7'][$y]);
}

$valor_prev['20'] = abs($valor_prev[0]) - abs($valor_prev[9]);
//-------------------------------------------------------------------------------------------------
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','',6);
$alt            = 4;
$pagina         = 1;
$cl = 16;  //tamanho da celula
$tp ='B'; // tipo do contorno
$ta ='TBRL';
   
$pdf->addpage("L");
$pdf->cell(60,$alt,"LRF, Art 53, inciso I, Anexo III","b",0,"L",0);
$pdf->cell(($cl*14),$alt,"R$ Unidades","b",1,"R",0);
$pdf->cell(60,$alt,"ESPECIFICAÇÃO",'RT',0,"C",0);
$pdf->cell(($cl*12),$alt,"EVOLUÇÃO DA RECEITA REALIZADA",'RTB',0,"C",0);
$pdf->cell($cl,$alt,"TOTAL",'RT',0,"C",0);
$pdf->cell($cl,$alt,"PREVISAO",'T',0,"C",0);
$pdf->ln();
$pdf->cell(60,$alt,"",'BR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[0],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[1],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[2],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[3],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[4],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[5],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[6],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[7],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[8],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[9],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[10],'TBR',0,"C",0);
$pdf->cell($cl,$alt,$mes_dresc[11],'TBR',0,"C",0);
$pdf->cell($cl,$alt,"ULT 12MESES",'BR',0,"C",0);
$pdf->cell($cl,$alt,"ATUAL EXERC",'B',0,"C",0);
$pdf->ln();

$pdf->setfont('arial','b',6);
$pdf->cell(60,$alt,$rec[0],'R',0,"L",0);
for($x=0;$x<=11;$x++){
  $pdf->cell($cl,$alt,db_formatar($valor_rec[0][$x],'f'),'R',0,"R",0);	
}
$pdf->cell($cl,$alt,db_formatar($valor_tot[0],'f'),'R',0,"R",0);	
$pdf->cell($cl,$alt,db_formatar($valor_prev[0],'f'),'',1,"R",0);
$pdf->setfont('arial','',6);

for ($y=1;$y<=19;$y++){
 $pdf->cell(60,$alt,$rec[$y],'R',0,"L",0);
 if ($y == 8) {
  for($x=0;$x<=11;$x++){
   $pdf->cell($cl,$alt,db_formatar($valor_rec['08'][$x],'f'),'R',0,"R",0);	
  }
  $pdf->cell($cl,$alt,db_formatar($valor_tot['08'],'f'),'R',0,"R",0);
  $pdf->cell($cl,$alt,db_formatar($valor_prev['08'],'f'),'',1,"R",0);
 }elseif ($y == 2) {
  for($x=0;$x<=11;$x++){
   $pdf->cell($cl,$alt,db_formatar($valor_rec['02'][$x],'f'),'R',0,"R",0);	
  }
  $pdf->cell($cl,$alt,db_formatar($valor_tot[2],'f'),'R',0,"R",0);
  $pdf->cell($cl,$alt,db_formatar($valor_prev[2],'f'),'',1,"R",0);
 
 }else{ 
  for($x=0;$x<=11;$x++){
   $pdf->cell($cl,$alt,db_formatar($valor_rec["$y"]["$x"],'f'),'R',0,"R",0);	
  }
  $pdf->cell($cl,$alt,db_formatar($valor_tot["$y"],'f'),'R',0,"R",0);
  $pdf->cell($cl,$alt,db_formatar($valor_prev["$y"],'f'),'',1,"R",0);
 } 
}
$pdf->setfont('arial','b',6);
$pdf->cell(60,$alt,$rec[20],'TBR',0,"L",0);
for($x=0;$x<=11;$x++){
  $pdf->cell($cl,$alt,db_formatar($valor_rec[20][$x],'f'),'TBR',0,"R",0);	
}
$pdf->cell($cl,$alt,db_formatar($valor_tot[20],'f'),'TBR',0,"R",0);
$pdf->cell($cl,$alt,db_formatar($valor_prev['20'],'f'),'TB',1,"R",0);
$pdf->cell(20,$alt,"Fonte: Contabilidade",'',1,"L",0);
// assinaturas


$pdf->ln(10);
$pdf->setfont('arial','',5);
$controle  =  "______________________________"."\n"."Controle Interno";
$sec  =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_cont = $classinatura->assinatura(1005,$cont);
$ass_controle = $classinatura->assinatura(1009,$controle);
//echo $ass_pref;
if( $pdf->gety() > ( $pdf->h - 30 ) )
  $pdf->addpage();
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_sec,0,"C",0,0);

$pdf->Ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_controle,0,"C",0,0);






$pdf->Output();
   
?>