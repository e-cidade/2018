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

if (!isset($arqinclude)) {
  // se este arquivo não esta incluido por outro
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrel_classe.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("libs/db_utils.php");
  include("classes/db_conrelinfo_classe.php");
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $clconrelinfo = new cl_conrelinfo;
  
  
  $tipo_emissao='periodo';
  
  $anousu  = db_getsession("DB_anousu");
  $dt = data_periodo($anousu,$periodo);
  // no dbforms/db_funcoes.php
  $dt_ini= $dt[0];
  // data inicial do período
  $dt_fin= $dt[1];
  // data final do período
  $texto = $dt['texto'];
  $txtper = $dt['periodo'];
  
  
  $anousu_ant  = db_getsession("DB_anousu")-1;
  $dt = data_periodo($anousu_ant,$periodo);
  // no dbforms/db_funcoes.php
  $dt_ini_ant= $dt[0];
  // data inicial do período
  $dt_fin_ant= $dt[1];
  // data final do período
  
  // caso tenha datas manuais selecionada , sobrescrevo as variaveis acima
  if ($dtini !='' && $dtfin!='') {
    $tipo_emissao='datas';
    
    $dt_ini = $dtini;
    $dt_fin = $dtfin;
    
    $dt = split('-',$dt_ini);
    $dt_ini_ant = ($anousu-1).'-'.$dt[1].'-'.$dt[2];
    $dt1 = $dt[2]."/".$dt[1]."/".$dt[0];

    $dt = split('-',$dt_fin);

    // Caso a Data Fim seja 31/12
    // seta $ultimo_periodo como true, caso contrario false
    $ultimo_periodo = ($dt[1]=="12" && $dt[2]=="31");

    $dt2 = $dt[2]."/".$dt[1]."/".$dt[0];
    $dt2_ant = $dt[2]."/".$dt[1]."/".($anousu-1);
    $dt_fin_ant = ($anousu-1).'-'.$dt[1].'-'.$dt[2];

    
  } else {
 
    // Caso o Periodo Seja 6B (Sexto Bimestre) ou 3Q (Terceiro Quadrimestre) ou 2S (Segundo Simestre)
    // seta $ultimo_periodo como true, caso contrario false
    $ultimo_periodo = ($periodo=="6B") || ($periodo=="3Q") || ($periodo=="2S");

  }

}
$ultimo_periodo = ($periodo=="6B") || ($periodo=="3Q") || ($periodo=="2S");
// end !include
$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(17,str_replace('-',',',$db_selinstit)));
$META_PRIMARIA     = 0;
$nSaldoAnoAtual    = 0;
$nSaldoAnoAnterior = 0;
if ($clconrelinfo->numrows > 0 ) {
  
  for ($i = 0; $i < $clconrelinfo->numrows; $i++) {
    
    db_fieldsmemory($res, $i);
    if ($c83_codigo == 268) {
      $META_PRIMARIA  = $c83_informacao;
    }
    if ($c83_codigo == 366) {
      $nSaldoAnoAtual = $c83_informacao;
    }
    if ($c83_codigo == 367) {
      $nSaldoAnoAnterior = $c83_informacao;
    }
    
  }
}

if (!isset($arqinclude)) {
  function espaco($n)
  {
    if ($n==1) {
      return ' ';
    }
    if ($n==2) {
      return '    ';
    }
    if ($n==3) {
      return '       ';
    }
  }
  $n1 = 5;
  $n2 = 10;
  
  $classinatura = new cl_assinatura;
  
  // seleciona matriz com estruturais selecionados pelo usuario
  $orcparamrel = new cl_orcparamrel;
  
}
// end !include

$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_desp = db_dotacaosaldo(7, 1, 4, true, $sele_work, $anousu, $dt_ini, $dt_fin);

$m_desp=array();
for ($x=1; $x<=10; $x++) {
  $m_desp[$x][1] = 0 ;
  $m_desp[$x][2] = 0 ;
  $m_desp[$x][3] = 0 ;
  $m_desp[$x][4] = 0 ;
  $m_desp[$x][5] = 0 ;
  $m_desp[$x][6] = 0 ;
}

//db_criatabela($result_desp); die();


for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento;
  if (substr($estrutural,0,3)=='331') {
    $m_desp[1][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[1][2] += $liquidado;
    $m_desp[1][3] += $liquidado_acumulado;
    $m_desp[1][4] += 0 ;
    $m_desp[1][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='332') {
    $m_desp[2][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[2][2] += $liquidado;
    $m_desp[2][3] += $liquidado_acumulado;
    $m_desp[2][4] += 0 ;
    $m_desp[2][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='333') {
    $m_desp[3][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[3][2] += $liquidado;
    $m_desp[3][3] += $liquidado_acumulado;
    $m_desp[3][4] += 0 ;
    $m_desp[3][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='344') {
    $m_desp[4][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[4][2] += $liquidado;
    $m_desp[4][3] += $liquidado_acumulado;
    $m_desp[4][4] += 0 ;
    $m_desp[4][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // inversoes financeiras : Concessao de emprestimos
  if (substr($estrutural,0,7)=='3459066') {
    $m_desp[5][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[5][2] += $liquidado;
    $m_desp[5][3] += $liquidado_acumulado;
    $m_desp[5][4] += 0 ;
    $m_desp[5][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,7)=='3459064') {
    $m_desp[6][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[6][2] += $liquidado;
    $m_desp[6][3] += $liquidado_acumulado;
    $m_desp[6][4] += 0 ;
    $m_desp[6][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='345' && substr($estrutural,0,7)!='3459064' &&  substr($estrutural,0,7)!='3459066') {
    $m_desp[7][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[7][2] += $liquidado;
    $m_desp[7][3] += $liquidado_acumulado;
    $m_desp[7][4] += 0 ;
    $m_desp[7][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // amortização da divida
  if (substr($estrutural,0,3)=='346') {
    $m_desp[8][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[8][2] += $liquidado;
    $m_desp[8][3] += $liquidado_acumulado;
    $m_desp[8][4] += 0 ;
    $m_desp[8][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // reserva
  if (substr($estrutural,0,3)=='399') {
    $m_desp[9][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[9][2] += $liquidado;
    $m_desp[9][3] += $liquidado_acumulado;
    $m_desp[9][4] += 0 ;
    $m_desp[9][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='377') {
    $m_desp[10][1] += $dot_ini + $suplementado_acumulado - $reduzido_acumulado;
    $m_desp[10][2] += $liquidado;
    $m_desp[10][3] += $liquidado_acumulado;
    $m_desp[10][4] += 0 ;
    $m_desp[10][5] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
}
// monta dados da despesa do exercicio anterior
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_desp = db_dotacaosaldo(7,1,4,true,$sele_work,($anousu-1),$dt_ini_ant,$dt_fin_ant);

for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento;
  if (substr($estrutural,0,3)=='331') {
    $m_desp[1][4] += $liquidado_acumulado;
    $m_desp[1][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='332') {
    $m_desp[2][4] += $liquidado_acumulado;
    $m_desp[2][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='333') {
    $m_desp[3][4] += $liquidado_acumulado;
    $m_desp[3][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='344') {
    $m_desp[4][4] += $liquidado_acumulado;
    $m_desp[4][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // inversoes financeiras : Concessao de emprestimos
  if (substr($estrutural,0,4)=='3459') {
    $m_desp[5][4] += $liquidado_acumulado;
    $m_desp[5][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='345' && substr($estrutural,0,7)!='3459064' &&  substr($estrutural,0,7)!='3459066') {
    $m_desp[7][4] += $liquidado_acumulado ;
    $m_desp[7][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // amortização da divida
  if (substr($estrutural,0,3)=='346') {
    $m_desp[8][4] += $liquidado_acumulado;
    $m_desp[8][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  // reserva
  if (substr($estrutural,0,3)=='399') {
    $m_desp[9][4] += $liquidado_acumulado;
    $m_desp[9][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
  if (substr($estrutural,0,3)=='377') {
    $m_desp[10][4] += $liquidado_acumulado;
    $m_desp[10][6] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
  }
}

//////////////////////////////////////////////////////////////////////////////////////////

$instituicao = str_replace("-",",",$db_selinstit);

// carrega matriz c/ parametros
$m_rec     = array();
$m_rec_ant = array();
// vetor de receitas de exercicio anterior devido as deducoes mudarem de 497(2007) p/ 917(2008)
for ($x=1; $x<=21; $x++) {
  $m_rec[$x][0]     = $orcparamrel->sql_parametro('17',$x,"f",$instituicao,db_getsession("DB_anousu"));
  
  $m_rec[$x][1]     = 0 ;
  // previsão atualizada
  $m_rec[$x][2]     = 0 ;
  // arrecadado no bimestre
  $m_rec[$x][3]     = 0 ;
  // arrecadado ate o bimestre
  $m_rec[$x][4]     = 0 ;
  // reservado para período no exercicio anterior
  
  $m_rec_ant[$x][0] = $orcparamrel->sql_parametro('17',$x,"f",$instituicao,(db_getsession("DB_anousu")-1));
  
  $m_rec_ant[$x][1] = 0;
  $m_rec_ant[$x][2] = 0;
  $m_rec_ant[$x][3] = 0;
  $m_rec_ant[$x][4] = 0;
}

// ---------------------------------------------------------------------------------

$db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin,false);
@pg_exec("drop table work_receita");

for ($x=0; $x< pg_numrows($result_rec); $x++) {
  db_fieldsmemory($result_rec,$x);
  $elemento = $o57_fonte;
  for ($aa=1; $aa<=21; $aa++) {
    if (in_array($elemento,$m_rec[$aa][0])) {
      $m_rec[$aa][1]+= $saldo_inicial_prevadic;
      // previsão atualizada
      $m_rec[$aa][2]+= $saldo_arrecadado;
      // arrecadado no bimestre
      $m_rec[$aa][3]+= $saldo_arrecadado_acumulado;
      // arrecadado ate o bimestre
      $m_rec[$aa][4]+= 0;
      // reservado para período no exercicio anterior
    }
  }
}
// ------------

$db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu_ant,$dt_ini_ant,$dt_fin_ant,false);
@pg_exec("drop table work_receita");
for ($x=0; $x< pg_numrows($result_rec); $x++) {
  $oReceitaSaldo = db_utils::fieldsmemory($result_rec,$x);
  $elemento      = $oReceitaSaldo->o57_fonte;
  for ($aa=1; $aa<=21; $aa++) {
    if (in_array($elemento,$m_rec[$aa][0])) {
      $m_rec[$aa][4]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
      // arrecadado ate o bimestre
    }
    
  }
}
/**
 * Variaveis para relatorio simplificado.
 */


// print_r($m_receitas);
// db_criatabela($result_rec);
// exit;

if (!isset($arqinclude)) {
  
  $xinstit = split("-",$db_selinstit);
  $resultinst = pg_exec("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
  db_fieldsmemory($resultinst,0);
  $descr_inst = $munic;
  
  $head2 = "MUNICÍPIO DE ".$descr_inst;
  $head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4 = "DEMONSTRATIVO DO RESULTADO PRIMÁRIO";
  $head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  
  $dados  = data_periodo($anousu,$periodo);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);
  
  $txtper = strtoupper($dados["periodo"]);
  $mesini = strtoupper(db_mes($perini[1]));
  $mesfin = strtoupper(db_mes($perfin[1]));
  
  $head6 = "JANEIRO A ".$mesfin."/".$anousu." - ".$txtper." ".$mesini."-".$mesfin;
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  
  $pdf->addpage();
  $alt=3.3;
  
}
// end !include

// totalizadores do relatorio

$receitas_de_capital[1] = $m_rec[16][1]+$m_rec[17][1]+$m_rec[18][1]+$m_rec[19][1]+$m_rec[20][1]+$m_rec[21][1];
// previsão atualizada
$receitas_de_capital[2] = $m_rec[16][2]+$m_rec[17][2]+$m_rec[18][2]+$m_rec[19][2]+$m_rec[20][2]+$m_rec[21][2];
// arrecadado no bimestre
$receitas_de_capital[3] = $m_rec[16][3]+$m_rec[17][3]+$m_rec[18][3]+$m_rec[19][3]+$m_rec[20][3]+$m_rec[21][3];
// arrecadado ate o bimestre
$receitas_de_capital[4] = $m_rec[16][4]+$m_rec[17][4]+$m_rec[18][4]+$m_rec[19][4]+$m_rec[20][4]+$m_rec[21][4];
// reservado para período no exercicio anterior

$receita_primaria_total[1]=0;
$receita_primaria_total[2]=0;
$receita_primaria_total[3]=0;
$receita_primaria_total[4]=0;

$receita_primaria[1]=0;
$receita_primaria[2]=0;
$receita_primaria[3]=0;
$receita_primaria[4]=0;


for ($x=1; $x<=15; $x++) {
  if ($x==9) {
    continue;
    // temos que subtrair a linha 9
  }
  
  $receita_primaria_total[1] += $m_rec[$x][1];
  // previsão atualizada
  $receita_primaria_total[2] += $m_rec[$x][2];
  // arrecadado no bimestre
  $receita_primaria_total[3] += $m_rec[$x][3];
  // arrecadado ate o bimestre
  $receita_primaria_total[4] += $m_rec[$x][4];
  // reservado para período no exercicio anterior
  
  $receita_primaria[1] += $m_rec[$x][1];
  // previsão atualizada
  $receita_primaria[2] += $m_rec[$x][2];
  // arrecadado no bimestre
  $receita_primaria[3] += $m_rec[$x][3];
  // arrecadado ate o bimestre
  $receita_primaria[4] += $m_rec[$x][4];
  // reservado para período no exercicio anterior
  
}
$receita_primaria_total[1] -= $m_rec[9][1];
// previsão atualizada
$receita_primaria_total[2] -= $m_rec[9][2];
// arrecadado no bimestre
$receita_primaria_total[3] -= $m_rec[9][3];
// arrecadado ate o bimestre
$receita_primaria_total[4] -= $m_rec[9][4];
// reservado para período no exercicio anterior

$receita_primaria[1] -= $m_rec[9][1];
// previsão atualizada
$receita_primaria[2] -= $m_rec[9][2];
// arrecadado no bimestre
$receita_primaria[3] -= $m_rec[9][3];
// arrecadado ate o bimestre
$receita_primaria[4] -= $m_rec[9][4];
// reservado para período no exercicio anterior

$receita_primaria_total[1] += $m_rec[19][1]+$m_rec[20][1]+$m_rec[21][1];
// previsão atualizada
$receita_primaria_total[2] += $m_rec[19][2]+$m_rec[20][2]+$m_rec[21][2];
// arrecadado no bimestre
$receita_primaria_total[3] += $m_rec[19][3]+$m_rec[20][3]+$m_rec[21][3];
// arrecadado ate o bimestre
$receita_primaria_total[4] += $m_rec[19][4]+$m_rec[20][4]+$m_rec[21][4];
// reservado para período no exercicio anterior

//
$receitas_primarias_de_capital[1] = $m_rec[19][1]+$m_rec[20][1]+$m_rec[21][1];
$receitas_primarias_de_capital[2] = $m_rec[19][2]+$m_rec[20][2]+$m_rec[21][2];
$receitas_primarias_de_capital[3] = $m_rec[19][3]+$m_rec[20][3]+$m_rec[21][3];
$receitas_primarias_de_capital[4] = $m_rec[19][4]+$m_rec[20][4]+$m_rec[21][4];

// end

//echo "<pre>";
//print_r($m_desp);
//echo "</pre>";
//die();

if (!isset($arqinclude)) {
  $pdf->ln();
  $pdf->setfont('arial','',6);
  $pdf->cell(98,$alt,"RREO - ANEXO VII(LRF, art. 53, inciso III)",'0',0,"L",0);
  $pdf->cell(99,$alt,"R$ 1,00",'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,($alt*2),"RECEITAS PRIMÁRIAS",'TB',0,"C",0);
  $pdf->cell(33,($alt*2),"Previsão Atualizada",'1',0,"C",0);
  $pdf->cell(99,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
  //br
  $pdf->setX(108);
  if ($tipo_emissao=='periodo') {
    $pdf->cell(33,$alt,"No ".$txtper,'1',0,"C",0);
    $pdf->cell(33,$alt,"Até o ".$txtper." (".$anousu.")",'1',0,"C",0);
    $pdf->cell(33,$alt,"Até o ".$txtper." (".($anousu -1).")",'TB',0,"C",0);
  } else {
    $pdf->cell(33,$alt,"$dt1 à $dt2",'1',0,"C",0);
    $pdf->cell(33,$alt,"Até $dt2 ",'1',0,"C",0);
    $pdf->cell(33,$alt,"Até $dt2_ant ",'TB',0,"C",0);
  }
  $pdf->ln();
  
  //--------------
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(1)."RECEITAS PRIMÁRIAS CORRENTES (I)",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria[1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria[2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria[3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria[4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Receitas Tributárias",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][1]+$m_rec[2][1]+$m_rec[3][1]+$m_rec[4][1]+$m_rec[5][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][2]+$m_rec[2][2]+$m_rec[3][2]+$m_rec[4][2]+$m_rec[5][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][3]+$m_rec[2][3]+$m_rec[3][3]+$m_rec[4][3]+$m_rec[5][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][4]+$m_rec[2][4]+$m_rec[3][4]+$m_rec[4][4]+$m_rec[5][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."IPTU",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[1][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."ISS",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[2][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[2][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[2][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[2][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."ITBI",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[3][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[3][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[3][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[3][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."IRRF",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[4][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[4][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[4][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[4][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Outras Receitas Tributárias",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[5][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[5][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[5][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[5][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Receitas de Contribuições",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][1]+$m_rec[7][1] ,'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][2]+$m_rec[7][2] ,'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][3]+$m_rec[7][3] ,'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][4]+$m_rec[7][4] ,'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Receitas Previdenciárias",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[6][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Outras Receitas de Contribuições",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[7][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[7][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[7][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[7][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Receita Patrimonial Líquida",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][1]-$m_rec[9][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][2]-$m_rec[9][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][3]-$m_rec[9][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][4]-$m_rec[9][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Receita Patrimonial",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[8][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."(-) Aplicações Financeiras",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[9][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[9][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[9][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[9][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Transferências Correntes",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][1]+$m_rec[11][1]+$m_rec[12][1]+$m_rec[13][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][2]+$m_rec[11][2]+$m_rec[12][2]+$m_rec[13][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][3]+$m_rec[11][3]+$m_rec[12][3]+$m_rec[13][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][4]+$m_rec[11][4]+$m_rec[12][4]+$m_rec[13][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."FPM",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[10][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."ICMS",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[11][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[11][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[11][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[11][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Convênios",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[12][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[12][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[12][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[12][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Outras Transferências Correntes",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[13][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[13][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[13][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[13][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Demais Receitas Correntes",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][1]+$m_rec[15][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][2]+$m_rec[15][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][3]+$m_rec[15][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][4]+$m_rec[15][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Divida Ativa",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[14][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Diversas Receitas Correntes",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[15][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[15][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[15][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[15][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(1)."RECEITAS DE CAPITAL (II)",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($receitas_de_capital[1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_de_capital[2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_de_capital[3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_de_capital[4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Operações de Credito  (III)",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[16][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[16][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[16][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[16][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Amortização de Empréstimos (IV)",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[17][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[17][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[17][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[17][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Alienação de Bens (V)",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[18][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[18][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[18][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[18][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Transferências de Capital",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][1]+$m_rec[20][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][2]+$m_rec[20][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][3]+$m_rec[20][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][4]+$m_rec[20][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Convênios",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[19][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(3)."Outras Transferências de Capital",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[20][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[20][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[20][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[20][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(2)."Outras Receitas de Capital",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[21][1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[21][2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[21][3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($m_rec[21][4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(1)."RECEITAS PRIMARIAS DE CAPITAL (VI)=(II-III-IV-V) ",'R',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($receitas_primarias_de_capital[1],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_primarias_de_capital[2],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_primarias_de_capital[3],'f'),'R',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receitas_primarias_de_capital[4],'f'),'0',1,"R",0);
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell(65,$alt,espaco(1)."RECEITA PRIMARIA TOTAL (VII)=(I+VI) ",'TBR',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria_total[1],'f'),'TBR',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria_total[2],'f'),'TBR',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria_total[3],'f'),'TBR',0,"R",0);
  $pdf->cell(33,$alt,db_formatar($receita_primaria_total[4],'f'),'TB',1,"R",0);
  //br
  
  //$pdf->setfont('arial','',6);
  //$pdf->cell(65,$alt,'','TBR',0,"L",0);
  //$pdf->cell(33,$alt,'','TBR',0,"R",0);
  //$pdf->cell(33,$alt,'','TBR',0,"R",0);
  //$pdf->cell(33,$alt,'','TBR',0,"R",0);
  //$pdf->cell(33,$alt,'','TB',1,"R",0);
  //br

  // Controle tamanho Colunas e linhas
  $tam_lin  = ($ultimo_periodo)? 04: 02;
  $tam_col1 = ($ultimo_periodo)? 65: 65;
  $tam_col2 = ($ultimo_periodo)? 27: 33;
  $tam_col3 = ($ultimo_periodo)? 21: 33;
  $tam_col4 = ($ultimo_periodo)? 21: 33;
  $tam_col5 = ($ultimo_periodo)? 21: 33;
  $tam_col6 = ($ultimo_periodo)? 21: 33; // Inscr RP Nao Proc
  $tam_col7 = ($ultimo_periodo)? 21: 33; // Inscr RP Nao Proc Anterior
  $tam_desp = ($ultimo_periodo)?105: 99; // tamanho colunas 3+4+5 (e +6+7 qdo $ultimo_periodo==true)

  $pdf->cell($tam_desp,$alt,'','0',1,"R",0);

  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, ($alt * $tam_lin), "DESPESAS PRIMÁRIAS",'TB',0,"C",0);
  $pdf->cell($tam_col2, ($alt * $tam_lin), "DOTAÇÃO ATUALIZADA",'1',0,"C",0);

  $txtdespliq = ($ultimo_periodo)?"DESPESAS EXECUTADAS":"DESPESAS LIQUIDADAS";
  $pdf->cell($tam_desp, $alt, $txtdespliq,'TB',1,"C",0);

  if($ultimo_periodo) {
    $pdf->setX(102);
    $pdf->cell($tam_col3+$tam_col4+$tam_col6, $alt, "Em ".$anousu,     '1',  0, "C", 0);
    $pdf->cell($tam_col5+$tam_col7,           $alt, "Em ".($anousu-1), 'TB', 0, "C", 0);
    $pdf->ln();
    
    $pdf->setX(102);

    $pdf->cell($tam_col3+$tam_col4, $alt,     "LIQUIDADAS",         '1',  0, "C", 0);
    $posY = $pdf->getY()+$alt;

    $pdf->cell($tam_col6,           ($alt*2), "Inscritas em RP NP", '1',  0, "C", 0);

    $pdf->cell($tam_col5,           $alt,     "LIQUIDADAS",         '1',  0, "C", 0);
    $pdf->cell($tam_col7,           ($alt*2), "Inscritas em RP NP", 'TB', 0, "C", 0);
    $pdf->ln();
    $posY2 = $pdf->getY();


    $pdf->setY($posY);
    $pdf->setX(102);

    if ($tipo_emissao=='periodo') {
      $txtper = ucfirst(strtolower($txtper));
      $pdf->cell($tam_col3, $alt, "No ".$txtper,    '1',  0, "C", 0);
      $pdf->cell($tam_col4, $alt, "Até o ".$txtper, '1',  0, "C", 0);

      $pdf->setX(102+$tam_col3+$tam_col4+$tam_col6);
      $pdf->cell($tam_col4, $alt, "Até o ".$txtper, '1',  0, "C", 0);
    } else {
      $pdf->cell($tam_col3, $alt, substr($dt1,0,5)." à ".substr($dt2,0,5), '1',  0, "C", 0);
      $pdf->cell($tam_col4, $alt, "Até ".substr($dt2,0,5),   '1',  0, "C", 0);

      $pdf->setX(102+$tam_col3+$tam_col4+$tam_col6);
      $pdf->cell($tam_col4, $alt, "Até ".substr($dt2_ant,0,5), '1',  0, "C", 0);
    }



  } else {
    //br
    $pdf->setX(108);
    if ($tipo_emissao=='periodo') {
      $pdf->cell($tam_col3, $alt, "No ".$txtper,                          '1',  0, "C", 0);
      $pdf->cell($tam_col4, $alt, "Até o ".$txtper." (".$anousu.")",      '1',  0, "C", 0);
      $pdf->cell($tam_col5, $alt, "Até o ".$txtper." (".($anousu -1).")", 'TB', 0, "C", 0);
    } else {
      $pdf->cell($tam_col3, $alt, "$dt1 à $dt2",   '1',  0, "C", 0);
      $pdf->cell($tam_col3, $alt, "Até $dt2 ",     '1',  0, "C", 0);
      $pdf->cell($tam_col3, $alt, "Até $dt2_ant ", 'TB', 0, "C", 0);
    }
  }
  $pdf->ln();
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."DESPESAS CORRENTES(VIII)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[1][1]+ $m_desp[2][1]+ $m_desp[3][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[1][2]+ $m_desp[2][2]+ $m_desp[3][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[1][3]+ $m_desp[2][3]+ $m_desp[3][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[1][5]+ $m_desp[2][5]+ $m_desp[3][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4]+ $m_desp[2][4]+ $m_desp[3][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[1][6]+ $m_desp[2][6]+ $m_desp[3][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4]+ $m_desp[2][4]+ $m_desp[3][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Pessoal e Encargos Sociais",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[1][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[1][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[1][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[1][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[1][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Juros e Encargos da Dívida(IX) ",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[2][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[2][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[2][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[2][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[2][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[2][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[2][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Outras Despesas Correntes",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[3][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[3][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[3][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[3][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[3][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[3][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[3][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."DESPESAS PRIMARIAS CORRENTES(X)=(VIII - IX)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[1][1] + $m_desp[3][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[1][2] + $m_desp[3][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[1][3] + $m_desp[3][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[1][5] + $m_desp[3][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4] + $m_desp[3][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[1][6] + $m_desp[3][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4] + $m_desp[3][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."DESPESAS DE CAPITAL (XI)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[4][1]+ $m_desp[5][1]+ $m_desp[6][1]+$m_desp[7][1]+$m_desp[8][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[4][2]+ $m_desp[5][2]+ $m_desp[6][2]+$m_desp[7][2]+$m_desp[8][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[4][3]+ $m_desp[5][3]+ $m_desp[6][3]+$m_desp[7][3]+$m_desp[8][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[4][5]+ $m_desp[5][5]+ $m_desp[6][5]+$m_desp[7][5]+$m_desp[8][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4]+ $m_desp[5][4]+ $m_desp[6][4]+$m_desp[7][4]+$m_desp[8][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[4][6]+ $m_desp[5][6]+ $m_desp[6][6]+$m_desp[7][6]+$m_desp[8][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4]+ $m_desp[5][4]+ $m_desp[6][4]+$m_desp[7][4]+$m_desp[8][4],'f'),'0',1,"R",0);
  }

  //br
  
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Investimentos",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[4][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[4][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[4][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[4][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[4][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Inversões Financeiras",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[5][1]+ $m_desp[6][1]+ $m_desp[7][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[5][2]+ $m_desp[6][2]+ $m_desp[7][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[5][3]+ $m_desp[6][3]+ $m_desp[7][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[5][5] + $m_desp[6][5] + $m_desp[7][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[5][4] + $m_desp[6][4] + $m_desp[7][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[5][6] + $m_desp[6][6] + $m_desp[7][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[5][4]+ $m_desp[6][4]+ $m_desp[7][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(3)."Concessão de Emprestimos (XII)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[5][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[5][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[5][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[5][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[5][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[5][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[5][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(3)."Aquisição de Título de Capital já Integralizado (XIII)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[6][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[6][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[6][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[6][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[6][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[6][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[6][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(3)."Demais Inversões Financeiras",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[7][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[7][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[7][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[7][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[7][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[7][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[7][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(2)."Amortização da Dívida (XIV)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[8][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[8][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[8][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[8][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[8][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[8][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[8][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."DESPESAS PRIMARIAS DE CAPITAL (XV) = (XI-XII-XIII-XIV)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[4][1]+ $m_desp[7][1] ,'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[4][2]+ $m_desp[7][2] ,'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[4][3]+ $m_desp[7][3] ,'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[4][5]+ $m_desp[7][5] ,'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4]+ $m_desp[7][4] ,'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[4][6]+ $m_desp[7][6] ,'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[4][4]+ $m_desp[7][4] ,'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."RESERVA DE CONTINGENCIA (XVI)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[9][1],'f'),'R',0,"R",0);
/*  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[9][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[9][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[9][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[9][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[9][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[9][4],'f'),'0',1,"R",0);
  }*/
  
  $pdf->cell($tam_col3, $alt,' - ','R',0,"R",0);
  $pdf->cell($tam_col4, $alt,' - ','R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt,' - ','R',0,"R",0);
    $pdf->cell($tam_col5, $alt,' - ','R',0,"R",0);
    $pdf->cell($tam_col7, $alt,' - ','0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt,' - ','0',1,"R",0);
  }  
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."RESERVA DO RPPS (XVII)",'R',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[10][1],'f'),'R',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[10][2],'f'),'R',0,"R",0);
  $pdf->cell($tam_col4, $alt, db_formatar($m_desp[10][3],'f'),'R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col6, $alt, db_formatar($m_desp[10][5],'f'),'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[10][4],'f'),'R',0,"R",0);
    $pdf->cell($tam_col7, $alt, db_formatar($m_desp[10][6],'f'),'0',1,"R",0);
  } else {
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[10][4],'f'),'0',1,"R",0);
  }
  //br
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."DESPESA PRIMARIA TOTAL (XVIII)=(X+XV+XVI+XVII)",'TBR',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar($m_desp[1][1]+$m_desp[3][1]+ $m_desp[4][1]+$m_desp[7][1]+$m_desp[9][1]+$m_desp[10][1],'f'),'TBR',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar($m_desp[1][2]+$m_desp[3][2]+ $m_desp[4][2]+$m_desp[7][2]+$m_desp[9][2]+$m_desp[10][2],'f'),'TBR',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col4+$tam_col6, $alt, db_formatar($m_desp[1][3]+$m_desp[3][3]+ $m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]+
                                                      $m_desp[1][5]+$m_desp[3][5]+ $m_desp[4][5]+$m_desp[7][5]+$m_desp[9][5]+$m_desp[10][5],'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($m_desp[1][4]+$m_desp[3][4]+ $m_desp[4][4]+$m_desp[7][4]+$m_desp[9][4]+$m_desp[10][4]+
                                                      $m_desp[1][6]+$m_desp[3][6]+ $m_desp[4][6]+$m_desp[7][6]+$m_desp[9][6]+$m_desp[10][6],'f'),'TB',1,"R",0);
  } else {
    $pdf->cell($tam_col4, $alt, db_formatar($m_desp[1][3]+$m_desp[3][3]+ $m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3],'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($m_desp[1][4]+$m_desp[3][4]+ $m_desp[4][4]+$m_desp[7][4]+$m_desp[9][4]+$m_desp[10][4],'f'),'TB',1,"R",0);
  }
  
  $alt=2;
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, '','R',0,"L",0);
  $pdf->cell($tam_col2, $alt, '','R',0,"R",0);
  $pdf->cell($tam_col3, $alt, '','R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col4+$tam_col6, $alt, '','R',0,"R",0);
    $pdf->cell($tam_col5+$tam_col6, $alt, '','0',1,"R",0);
  } else {
    $pdf->cell($tam_col4, $alt, '','R',0,"R",0);
    $pdf->cell($tam_col5, $alt, '','0',1,"R",0);
  }
  //br
  $alt=4;
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."RESULTADO PRIMARIO (XIX) = (VII - XVIII)",'TBR',0,"L",0);
  $pdf->cell($tam_col2, $alt, db_formatar(($receita_primaria_total[1]) - ($m_desp[1][1]+$m_desp[3][1]+$m_desp[4][1]+$m_desp[7][1]+$m_desp[9][1]+$m_desp[10][1]),'f'),'TBR',0,"R",0);
  $pdf->cell($tam_col3, $alt, db_formatar(($receita_primaria_total[2]) - ($m_desp[1][2]+$m_desp[3][2]+$m_desp[4][2]+$m_desp[7][2]+$m_desp[9][2]+$m_desp[10][2]),'f'),'TBR',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col4+$tam_col6, $alt, db_formatar(($receita_primaria_total[3]) - 
                                                      ($m_desp[1][3]+$m_desp[3][3]+$m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]+
                                                       $m_desp[1][5]+$m_desp[3][5]+$m_desp[4][5]+$m_desp[7][5]+$m_desp[9][5]+$m_desp[10][5]),'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar(($receita_primaria_total[4]) - 
                                                      ($m_desp[1][4]+$m_desp[3][4]+$m_desp[4][4]+$m_desp[7][4]+$m_desp[9][4]+$m_desp[10][4]+
                                                       $m_desp[1][6]+$m_desp[3][6]+$m_desp[4][6]+$m_desp[7][6]+$m_desp[9][6]+$m_desp[10][6]),'f'),'TB',1,"R",0);
  } else {
    $pdf->cell($tam_col4, $alt, db_formatar(($receita_primaria_total[3]) - ($m_desp[1][3]+$m_desp[3][3]+$m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]),'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar(($receita_primaria_total[4]) - ($m_desp[1][4]+$m_desp[3][4]+$m_desp[4][4]+$m_desp[7][4]+$m_desp[9][4]+$m_desp[10][4]),'f'),'TB',1,"R",0);
  }
  //br
  
  $alt=2;
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, '','R',0,"L",0);
  $pdf->cell($tam_col2, $alt, '','R',0,"R",0);
  $pdf->cell($tam_col3, $alt, '','R',0,"R",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col4+$tam_col6, $alt, '','R',0,"R",0);
    $pdf->cell($tam_col5+$tam_col6, $alt, '','0',1,"R",0);
  } else {
    $pdf->cell($tam_col4, $alt, '','R',0,"R",0);
    $pdf->cell($tam_col5, $alt, '','0',1,"R",0);
  }
  //br
  $alt=4;
  
  $pdf->setfont('arial','',6);
  $pdf->cell($tam_col1, $alt, espaco(1)."SALDOS DE EXERCICIOS ANTERIORES",'TBR',0,"L",0);
  $pdf->cell($tam_col2, $alt, '-','TBR',0,"C",0);
  $pdf->cell($tam_col3, $alt, '-','TBR',0,"C",0);
  if($ultimo_periodo) {
    $pdf->cell($tam_col4+$tam_col6, $alt, db_formatar($nSaldoAnoAtual,'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($nSaldoAnoAnterior, 'f'),'TB',1,"R",0);
  } else {
    $pdf->cell($tam_col4, $alt, db_formatar($nSaldoAnoAtual,'f'),'TBR',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($nSaldoAnoAnterior, 'f'),'TB',1,"R",0);
  } 
  //br
  
  
  
  // -------------------------
  $pdf->setfont('arial','',6);
  $pdf->cell(131,$alt,"DISCRIMINAÇÃO DA META FISCAL",'TBR',0,"C",0);
  $pdf->cell(66,$alt,'VALOR CORRENTE','TB',1,"C",0);
  //br
  $pdf->cell(131,$alt,"META DE RESULTADO PRIMÁRIO FIXADA NO ANEXO DE METAS FISCAIS DA LDO P/ O ",'TR',0,"L",0);
  $pdf->cell(66,$alt,'-','T',1,"C",0);
  //br
  $pdf->cell(131,$alt,"EXERCÍCIO DE REFERÊNCIA",'BR',0,"L",0);
  $pdf->cell(66,$alt,db_formatar($META_PRIMARIA,'f'),'B',0,"C",0);
  //br
  
  $pdf->Ln();
  
  notasExplicativas(&$pdf,17,"{$periodo}",190);
  
  $pdf->Ln(14);
  
  assinaturas(&$pdf,&$classinatura,'LRF');
  
  $pdf->Output();
  
}

?>