<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orcparamrel_classe.php"));
include(modification("classes/db_orcparamseq_classe.php"));

$orcparamrel  = new cl_orcparamrel;
$orcparamseq  = new cl_orcparamseq;
$classinatura = new cl_assinatura;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
//db_criatabela($resultinst);exit;

$descr_inst = '';
$xvirg      = '';
$flag_abrev = false;
$descr_receita = "DEDUÇÕES DA RECEITA CORRENTE";
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  
  if (strlen(trim($nomeinstabrev)) > 0) {
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }
  
  $xvirg = ', ';
}

$anousu  = db_getsession("DB_anousu");

$head3   = "DEMONSTRATIVO DAS VARIAÇÕES PATRIMONIAIS - ANEXO 15";
$head4   = "EXERCÍCIO ".$anousu;

if ($flag_abrev == false) {
  if (strlen($descr_inst) > 2) {
    $descr_inst = substr($descr_inst,0,100);
  }
}

$head5   = "INSTITUIÇÕES : ".$descr_inst;
$head6   = "ANEXO 15 - PERÍODO : ".strtoupper(db_mes($mesini))." A ".strtoupper(db_mes($mesfin));

$dataini = db_getsession("DB_anousu").'-'.$mesini.'-01';
$datafin = db_getsession("DB_anousu").'-'.$mesfin.'-'.date('t',mktime(0,0,0,$mesfin,'01',db_getsession("DB_anousu")));

$resultante_ativa   = 0;
$resultante_passiva = 0;
$total_variacoes_ativa   = 0;
$total_variacoes_passiva = 0;

$receita_orcam      = 0;
$receita_corrente   = 0;
$receita_capital    = 0;
$deducoes           = 0;

$despesa_orcam      = 0;
$despesa_corrente   = 0;
$despesa_capital    = 0;

// RECEITAS
// balancete de receita
$db_filtro = ' o70_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dataini,$datafin);
//db_criatabela($result_rec);exit;

// variaveis de receita corrente
$receita_tributaria        = 0;
$receita_contribuicoes     = 0;
$receita_patrimonial       = 0;
$receita_agropecuaria      = 0;
$receita_servicos          = 0;
$transf_correntes          = 0;
$outras_receitas_correntes = 0;
$receitas_correntes_intra  = 0;

// variaveis de receita capital
$operacoes_credito         = 0;
$alienacao_bens            = 0;
$amortizacao_emprestimos   = 0;
$transf_capital            = 0;
$outras_receitas_capital   = 0;

// deducoes
$deducao                   = 0;


//db_criatabela($result_rec);exit;

for ($i=0; $i<pg_num_rows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $controle=false;
  // receitas correntes
  if ($o57_fonte=='411000000000000' && $o70_codrec == 0 ) {
    $receita_tributaria+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='412000000000000' && $o70_codrec == 0 ) {
    $receita_contribuicoes+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='413000000000000' && $o70_codrec == 0 ) {
    $receita_patrimonial+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='414000000000000' && $o70_codrec == 0 ) {
    $receita_agropecuaria+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='416000000000000' && $o70_codrec == 0 ) {
    $receita_servicos+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='417000000000000' && $o70_codrec == 0 ) {
    $transf_correntes+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  //  if(substr($o57_fonte,0,2)=='41' && $controle==false && $o70_codrec == 0  ) {
  if (( $o57_fonte=='415000000000000' || $o57_fonte=='418000000000000' || $o57_fonte=='419000000000000') && $controle==false && $o70_codrec == 0  ) {
    // se não entrou em outras receitas, sendo rec. corrente
    $outras_receitas_correntes+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  // receitas de capital
  if ($o57_fonte=='421000000000000' && $o70_codrec == 0 ) {
    $operacoes_credito+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='422000000000000' && $o70_codrec == 0 ) {
    $alienacao_bens += $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='423000000000000' && $o70_codrec == 0 ) {
    $amortizacao_emprestimos+= $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  if ($o57_fonte=='424000000000000' && $o70_codrec == 0 ) {
    $transf_capital += $saldo_arrecadado_acumulado;
    $controle=true;
  }
  
  //    if(substr($o57_fonte,0,2)=='42' && $controle==false && $o70_codrec == 0 ) {
  if (( $o57_fonte=='425000000000000' || $o57_fonte=='426000000000000' || $o57_fonte=='427000000000000' || $o57_fonte=='428000000000000' || $o57_fonte=='429000000000000') && $controle==false && $o70_codrec == 0 ) {
    $outras_receitas_capital+= $saldo_arrecadado_acumulado;
  }
  
  //    if(substr($o57_fonte,0,2)=='47' && $controle==false && $o70_codrec == 0 ) {
  if ($o57_fonte == '470000000000000' && $controle==false && $o70_codrec == 0 ) {
    $receitas_correntes_intra += $saldo_arrecadado_acumulado;
  }
  // deduções
  
  $descr_receita = "DEDUÇÕES DA RECEITA CORRENTE";
  //if (db_conplano_grupo($anousu,substr($o57_fonte,0,2)."0000000000000",9001)==true){   // 497
  if (db_conplano_grupo($anousu,$o57_fonte,9000)==true   &&  ( $o70_codrec == 0 || true )  ) {
    // 497
    
    $estrut     = substr($o57_fonte,0,1)."00000000000000";
    if ($anousu < 2008) {
      $estrut     = substr($o57_fonte,0,2)."0000000000000";
    }
    
    $sql_estrut = "select o57_descr from orcfontes where o57_fonte = '$estrut' limit 1";
    $result_estrut = @db_query($sql_estrut);
    if (@pg_numrows($result_estrut) > 0) {
      $descr_receita = pg_result($result_estrut,0,"o57_descr");
    } else {
      $descr_receita = "DEDUÇÕES DA RECEITA CORRENTE";
    }
    if ($estrut == $o57_fonte ) {
      $deducao += $saldo_arrecadado_acumulado;
    }
  }
}
///////////////////////////////////////////////////////////////////////////////

// DESPESAS
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_desp = db_dotacaosaldo(7,3,4,true,$sele_work,$anousu,$dataini,$datafin);
//db_criatabela($result_desp); exit;

// variaveis de despesa corrente
$pes_enc_sociais           = 0;
$jur_enc_div               = 0;
$outras_despesas_correntes = 0;
// variaveis de despesa capital
$investimentos             = 0;
$inv_financeiras           = 0;
$amortizacao_divida        = 0;

for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);
  
  // Despesas Corrente
  if (substr($o58_elemento,0,3)=="331") {
    $pes_enc_sociais += $empenhado_acumulado-$anulado_acumulado;
  }
  
  if (substr($o58_elemento,0,3)=="332") {
    $jur_enc_div     += $empenhado_acumulado-$anulado_acumulado;
  }
  
  if (substr($o58_elemento,0,3)=="333") {
    $outras_despesas_correntes+= $empenhado_acumulado-$anulado_acumulado;
  }
  
  // Despesas de Capital
  if (substr($o58_elemento,0,3)=="344") {
    $investimentos  += $empenhado_acumulado-$anulado_acumulado;
  }
  
  if (substr($o58_elemento,0,3)=="345") {
    $inv_financeiras+= $empenhado_acumulado-$anulado_acumulado;
  }
  
  if (substr($o58_elemento,0,3)=="346") {
    $amortizacao_divida+= $empenhado_acumulado-$anulado_acumulado;
  }
}
///////////////////////////////////////////////////////////////////////////////
// PARAMETROS

$where               = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
//db_criatabela($res_planocontas); exit;

$instituicao                  = str_replace("-",",",$db_selinstit);
$m_interf_ativa               = $orcparamrel->sql_parametro_instit("41","1","f",$instituicao,db_getsession("DB_anousu"));
$m_interf_passiva             = $orcparamrel->sql_parametro_instit("41","2","f",$instituicao,db_getsession("DB_anousu"));
$m_mutacao_ativa              = $orcparamrel->sql_parametro_instit("41","3","f",$instituicao,db_getsession("DB_anousu"));
$m_mutacao_passiva            = $orcparamrel->sql_parametro_instit("41","4","f",$instituicao,db_getsession("DB_anousu"));
$m_indep_exec_orc_ativa       = $orcparamrel->sql_parametro_instit("41","5","f",$instituicao,db_getsession("DB_anousu"));
$m_indep_exec_orc_passiva     = $orcparamrel->sql_parametro_instit("41","6","f",$instituicao,db_getsession("DB_anousu"));

$aOrcParametro = array_merge($m_interf_ativa,
$m_interf_passiva,
$m_mutacao_ativa,
$m_mutacao_passiva,
$m_indep_exec_orc_ativa,
$m_indep_exec_orc_passiva
);
$res_planocontas              = db_planocontassaldo_matriz($anousu,$dataini,$datafin,false,$where,'','true','false','',$aOrcParametro);
$soma_interf_ativa            = 0;
$soma_interf_passiva          = 0;
$soma_mutacao_ativa           = 0;
$soma_mutacao_passiva         = 0;
$soma_indep_exec_orc_ativa    = 0;
$soma_indep_exec_orc_passiva  = 0;

$descr_interf_ativa           = "";
$descr_interf_passiva         = "";
$descr_mutacao_ativa          = "";
$descr_mutacao_passiva        = "";
$descr_indep_exec_orc_ativa   = "";
$descr_indep_exec_orc_passiva = "";

$res_orcparamseq = $orcparamseq->sql_record($orcparamseq->sql_query_file(41,null,"o69_codseq,o69_descr","o69_codparamrel#o69_codseq","o69_codparamrel=41"));
if ($orcparamseq->numrows > 0) {
  $numrows = $orcparamseq->numrows;
  for ($i=0; $i < $numrows; $i++) {
    db_fieldsmemory($res_orcparamseq,$i);
    if ($o69_codseq == 1) {
      $descr_interf_ativa    = $o69_descr;
    }
    if ($o69_codseq == 2) {
      $descr_interf_passiva  = $o69_descr;
    }
    if ($o69_codseq == 3) {
      $descr_mutacao_ativa   = $o69_descr;
    }
    if ($o69_codseq == 4) {
      $descr_mutacao_passiva = $o69_descr;
    }
    if ($o69_codseq == 5) {
      $descr_indep_exec_orc_ativa   = $o69_descr;
    }
    if ($o69_codseq == 6) {
      $descr_indep_exec_orc_passiva = $o69_descr;
    }
  }
}

$v_interf_ativa           = array();
$v_interf_passiva         = array();
$v_mutacao_ativa          = array();
$v_mutacao_passiva        = array();
$v_indep_exec_orc_ativa   = array();
$v_indep_exec_orc_passiva = array();

$ind_interf_ativa            = 0;
$ind_interf_passiva          = 0;
$ind_mutacao_ativa           = 0;
$ind_mutacao_passiva         = 0;
$ind_indep_exec_orc_ativa    = 0;
$ind_indep_exec_orc_passiva  = 0;

for ($i=0; $i< pg_numrows($res_planocontas); $i++) {
  
  db_fieldsmemory($res_planocontas,$i);
  
  $v_elementos = array($estrutural,$c61_instit);
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_interf_ativa)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_interf_ativa); $x++) {
      if ($estrutural == $m_interf_ativa[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  if ($flag_contar) {
    
    $v_interf_ativa[$ind_interf_ativa][1] = $c60_descr;
    $v_interf_ativa[$ind_interf_ativa][2] = $saldo_final;
    $soma_interf_ativa += $saldo_final;
    $ind_interf_ativa++;
  }
  
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_interf_passiva)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_interf_passiva); $x++) {
      if ($estrutural == $m_interf_passiva[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  if ($flag_contar) {
    $v_interf_passiva[$ind_interf_passiva][1] = $c60_descr;
    $v_interf_passiva[$ind_interf_passiva][2] = $saldo_final;
    $soma_interf_passiva += $saldo_final;
    $ind_interf_passiva++;
  }
  
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_mutacao_ativa)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_mutacao_ativa); $x++) {
      if ($estrutural == $m_mutacao_ativa[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  
  if ($flag_contar) {
    $v_mutacao_ativa[$ind_mutacao_ativa][1] = $c60_descr;
    $v_mutacao_ativa[$ind_mutacao_ativa][2] = $saldo_final;
    $soma_mutacao_ativa += $saldo_final;
    $ind_mutacao_ativa++;
  }
  
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_mutacao_passiva)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_mutacao_passiva); $x++) {
      if ($estrutural == $m_mutacao_passiva[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  if ($flag_contar) {
    $v_mutacao_passiva[$ind_mutacao_passiva][1] = $c60_descr;
    $v_mutacao_passiva[$ind_mutacao_passiva][2] = $saldo_final;
    $soma_mutacao_passiva += $saldo_final;
    $ind_mutacao_passiva++;
  }
  
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_indep_exec_orc_ativa)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_indep_exec_orc_ativa); $x++) {
      if ($estrutural == $m_indep_exec_orc_ativa[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  if ($flag_contar) {
    $v_indep_exec_orc_ativa[$ind_indep_exec_orc_ativa][1] = $c60_descr;
    $v_indep_exec_orc_ativa[$ind_indep_exec_orc_ativa][2] = $saldo_final;
    $soma_indep_exec_orc_ativa += $saldo_final;
    $ind_indep_exec_orc_ativa++;
  }
  
  $flag_contar = false;
  if ($c61_instit != 0) {
    if (in_array($v_elementos,$m_indep_exec_orc_passiva)) {
      $flag_contar = true;
    }
  } else {
    for ($x = 0; $x < count($m_indep_exec_orc_passiva); $x++) {
      if ($estrutural == $m_indep_exec_orc_passiva[$x][0]) {
        $flag_contar = true;
        break;
      }
    }
  }
  if ($flag_contar) {
    $v_indep_exec_orc_passiva[$ind_indep_exec_orc_passiva][1] = $c60_descr;
    $v_indep_exec_orc_passiva[$ind_indep_exec_orc_passiva][2] = $saldo_final;
    $soma_indep_exec_orc_passiva += $saldo_final;
    $ind_indep_exec_orc_passiva++;
  }
}
///////////////////////////////////////////////////////////////////////////////
// CALCULOS
$receita_corrente    = $receita_tributaria + $receita_contribuicoes + $receita_patrimonial + $receita_agropecuaria +
$receita_servicos   + $transf_correntes + $outras_receitas_correntes  + $receitas_correntes_intra;
$receita_capital     = $operacoes_credito  + $alienacao_bens   + $amortizacao_emprestimos  + $transf_capital +
$outras_receitas_capital;
$deducoes            = $deducao;

$despesa_corrente    = $pes_enc_sociais + $jur_enc_div + $outras_despesas_correntes;
$despesa_capital     = $investimentos   + $inv_financeiras + $amortizacao_divida;

$receita_orcam       = ($receita_corrente + $receita_capital)-($deducoes*-1);
$despesa_orcam       = $despesa_corrente + $despesa_capital;

$resultante_ativa    = $receita_orcam + $soma_interf_ativa   + $soma_mutacao_ativa;
$resultante_passiva  = $despesa_orcam + $soma_interf_passiva + $soma_mutacao_passiva;

$total_variacoes_ativa   = $resultante_ativa   + $soma_indep_exec_orc_ativa;
$total_variacoes_passiva = $resultante_passiva + $soma_indep_exec_orc_passiva;
//////////////////////////////////////////////////////////////////////////////

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt       = 4;
$pagina    = 1;
$fonte     = 7;
$pdf->addpage();
$pdf->setfont('arial','b',$fonte);

$pdf->cell(90,$alt,"Variações Ativas","TBR",0,"C",0);
$pdf->cell(90,$alt,"Variações Passivas","TB",1,"C",0);

$pdf->cell(70,$alt,"RESULTANTES DA EXECUÇÃO ORÇAMENTÁRIA",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($resultante_ativa,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,"RESULTANTES DA EXECUÇÃO ORÇAMENTÁRIA",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($resultante_passiva,"f"),"L",0,"R",0);
$pdf->ln();

$pdf->setfont('arial','',$fonte);
$pdf->cell(70,$alt,"RECEITA ORÇAMENTÁRIA",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($receita_orcam,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,"DESPESA ORÇAMENTÁRIA",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($despesa_orcam,"f"),"L",0,"R",0);
$pdf->ln();
$pdf->cell(70,$alt,"RECEITAS CORRENTES",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($receita_corrente,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,"DESPESAS CORRENTES",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($despesa_corrente,"f"),"L",0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receita Tributária","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receita_tributaria,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Pessoal e Encargos Sociais","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($pes_enc_sociais,"f"),0,0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receita de Contribuições","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receita_contribuicoes,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Juros e Encargos da Dívida","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($jur_enc_div,"f"),0,0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receita Patrimonial","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receita_patrimonial,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Outras Despesas Correntes","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($outras_despesas_correntes,"f"),0,0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receita Agropecuária","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receita_agropecuaria,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"DESPESAS DE CAPITAL",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($despesa_capital,"f"),"L",0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receita de Serviços","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receita_servicos,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Investimentos","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($investimentos,"f"),0,0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Transferências Correntes","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($transf_correntes,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Inversões Financeiras","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($inv_financeiras,"f"),0,0,"R",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Outras Receitas Correntes","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($outras_receitas_correntes,"f"),"R",0,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Amortização da Dívida","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($amortizacao_divida,"f"),0,0,"R",0);

$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Receitas Correntes Intra-Orçamentárias","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($receitas_correntes_intra,"f"),"R",0,"R",0);

$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->ln();

$pdf->cell(70,$alt,"RECEITAS DE CAPITAL",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($receita_capital,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Operações de Crédito","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($operacoes_credito,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Alienação de Bens","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($alienacao_bens,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Amortização de Empréstimos","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($amortizacao_emprestimos,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Transferências de Capital","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($transf_capital,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Outras Receitas de Capital","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($outras_receitas_capital,"f"),"R",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();

$pdf->cell(70,$alt+1,"DEDUÇÕES DA RECEITA CORRENTE",0,0,"L",0);
$pdf->cell(20,$alt+1,db_formatar($deducoes,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,"","R",0,"L",0);
$pdf->ln();
$pdf->cell(5, $alt+1,"",0,0,"L",0);
$pdf->cell(65,$alt+1,$descr_receita,"R",0,"L",0);
$pdf->cell(5, $alt+1,"",0,0,"L",0);
$pdf->cell(15,$alt+1,db_formatar($deducao,"f"),"R",0,"R",0);
$pdf->cell(70,$alt+1,"","R",1,"L",0);
///////////////////////////////////////////////////////////////////////////////
// INTERFERENCIAS ATIVA e PASSIVA
$pos = $pdf->gety();

$pdf->cell(70,$alt,$descr_interf_ativa,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_interf_ativa,"f"),"LR",0,"R",0);
$pdf->cell(70,$alt,$descr_interf_passiva,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_interf_passiva,"f"),"L",1,"R",0);

if (count($v_interf_ativa) > count($v_interf_passiva) ) {
  $contador = count($v_interf_ativa);
} else {
  $contador = count($v_interf_passiva);
}

for ($i=0; $i < $contador; $i++) {
  
  if (isset($v_interf_ativa[$i][2] ) ) {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_interf_ativa[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_interf_ativa[$i][2],"f"),"R",0,"R",0);
  } else {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,"","R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,"","R",0,"R",0);
  }
  if (isset($v_interf_passiva[$i][2] ) ) {
    $pdf->cell(5,$alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_interf_passiva[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_interf_passiva[$i][2],"f"),0,1,"R",0);
  } else {
    $pdf->cell(5,$alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,"","R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,"",0,1,"R",0);
  }
}
///////////////////////////////////////////////////////////////////////////////
// MUTACOES ATIVA e PASSIVA
//if(sizeof($v_interf_passiva) > sizeof($v_interf_ativa)) {
//  $pos1 = $pdf->gety();
//}
//$pos2 = $pdf->sety($pos1);

if (sizeof($v_mutacao_passiva) > sizeof($v_mutacao_ativa)) {
  $contador = count($v_mutacao_passiva);
} else {
  $contador = count($v_mutacao_ativa);
}


$pdf->cell(70,$alt,$descr_mutacao_ativa,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_mutacao_ativa,"f"),"LR",0,"R",0);

$pdf->cell(70,$alt,$descr_mutacao_passiva,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_mutacao_passiva,"f"),"L",1,"R",0);


for ($i=0; $i < $contador; $i++) {
  if (isset($v_mutacao_ativa[$i][2]) ) {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_mutacao_ativa[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_mutacao_ativa[$i][2],"f"),"R",0,"R",0);
  } else {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,"","R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,"","R",0,"R",0);
  }
  
  if (isset($v_mutacao_passiva[$i][2]) ) {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_mutacao_passiva[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_mutacao_passiva[$i][2],"f"),0,1,0,0);
  } else {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,"","R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,"",0,1,"L",0);
  }
  
}
//$pdf->cell(160,$alt+1,"","R",1,"L",0);
//$pos = $pdf->gety();

///////////////////////////////////////////////////////////////////////////////
// INDEP. EXECUCAO ORCAMENTARIA ATIVA e PASSIVA
/*
if(sizeof($v_mutacao_passiva) > sizeof($v_mutacao_ativa)) {
$pos1 = $pdf->gety();
}
$pos2 = $pdf->sety($pos1);
*/

//$pos2 = $pdf->sety($pos);
//$pos = $pdf->gety(); // guarda algura

$pdf->setfont('arial','b',$fonte);
$pdf->cell(70,$alt,$descr_indep_exec_orc_ativa,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_indep_exec_orc_ativa,"f"),"LR",0,"R",0);

$pdf->cell(70,$alt,$descr_indep_exec_orc_passiva,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_indep_exec_orc_passiva,"f"),"L",1,"R",0);
$pdf->setfont('arial','',$fonte);

$iCount = max( count($v_indep_exec_orc_ativa), count($v_indep_exec_orc_passiva) );
for ($i=0; $i < $iCount; $i++) {
  if (isset($v_indep_exec_orc_ativa[$i][1])) {
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_indep_exec_orc_ativa[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_indep_exec_orc_ativa[$i][2],"f"),"R",0,"R",0);
  } else {
    $pdf->cell(5,  $alt, "",   0, 0, "L", 0);
    $pdf->cell(65, $alt, "", "R", 0, "L", 0);
    $pdf->cell(5,  $alt,   "",   0, 0, "L", 0);
    $pdf->cell(15, $alt, "", "R", 0, "R", 0);
  }


  if (isset($v_indep_exec_orc_passiva[$i][1])) {
    // $pdf->cell(90,$alt,"",0,0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(65,$alt,ucfirst(strtolower($v_indep_exec_orc_passiva[$i][1])),"R",0,"L",0);
    $pdf->cell(5, $alt,"",0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($v_indep_exec_orc_passiva[$i][2],"f"),0,1,0,0);
  } else {
    $pdf->cell(5,  $alt, "",   0, 0, "L", 0);
    $pdf->cell(65, $alt, "", "R", 0, "L", 0);
    $pdf->cell(5,  $alt,   "",   0, 0, "L", 0);
    $pdf->cell(15, $alt, "",   0, 1, "L", 0);

  }
}

/*
//$pos_final = $pdf->gety();
$pdf->sety($pos);
// configura a altura para a anteriormente guardada
$pdf->setfont('arial','b',$fonte);
$pdf->cell(90,$alt,"",0,0,"L",0);
$pdf->cell(70,$alt,$descr_indep_exec_orc_passiva,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_indep_exec_orc_passiva,"f"),"L",1,"R",0);
$pdf->setfont('arial','',$fonte);
for ($i=0; $i < count($v_indep_exec_orc_passiva); $i++) {
  $pdf->cell(90,$alt,"",0,0,"L",0);
  $pdf->cell(5, $alt,"",0,0,"L",0);
  $pdf->cell(65,$alt,ucfirst(strtolower($v_indep_exec_orc_passiva[$i][1])),"R",0,"L",0);
  $pdf->cell(5, $alt,"",0,0,"L",0);
  $pdf->cell(15,$alt,db_formatar($v_indep_exec_orc_passiva[$i][2],"f"),0,1,"R",0);
}
*/
$pos1 = $pdf->gety();

///////////////////////////////////////////////////////////////////////////////
// TOTAL DE VARIACOES ATIVAS
$alt = 3.5;
//$pos = $pdf->sety($pos_final);

for ($i=0; $i < 4; $i++) {
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->cell(20,$alt,"","R",0,"L",0);
  $pdf->ln();
}

$pdf->setfont('arial','b',$fonte);
$pdf->cell(70,$alt,"Total de Variações Ativas","TBR",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_variacoes_ativa,"f"),"TBR",1,"R",0);
// TOTAL DE VARIACOES PASSIVAS
$pos = $pdf->sety($pos1);

for ($i=0; $i < 4; $i++) {
  $pdf->cell(90,$alt,"",0,0,"L",0);
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->ln();
}

$pdf->cell(90,$alt,"","TB",0,"L",0);
$pdf->cell(70,$alt,"Total de Variações Passivas","TBR",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_variacoes_passiva,"f"),"TB",1,"R",0);
// RESULTADO PATRIMONIAL ATIVA
if ($total_variacoes_ativa > $total_variacoes_passiva) {
  $resultado_patrimonial_ativa   = 0;
  $resultado_patrimonial_passiva = $total_variacoes_ativa - $total_variacoes_passiva;
} else {
  $resultado_patrimonial_ativa   = $total_variacoes_passiva - $total_variacoes_ativa;
  $resultado_patrimonial_passiva = 0;
}
$pos1 = $pdf->gety();
$pos  = $pdf->sety($pos1);
for ($i=0; $i < 1; $i++) {
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->cell(20,$alt,"","R",0,"L",0);
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->ln();
}
$pdf->setfont('arial','',$fonte);
$pdf->cell(70,$alt,"RESULTADO PATRIMONIAL",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($resultado_patrimonial_ativa,"f"),"LR",1,"R",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(65,$alt,"Déficit Verificado","R",0,"L",0);
$pdf->cell(5, $alt,"",0,0,"L",0);
$pdf->cell(15,$alt,db_formatar($resultado_patrimonial_ativa,"f"),"R",1,"R",0);
// RESULTADO PATRIMONIAL PASSIVA
$pos = $pdf->sety($pos1);
for ($i=0; $i < 1; $i++) {
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->cell(20,$alt,"","R",0,"L",0);
  $pdf->ln();
}
$pdf->cell(90, $alt,"",0,0,"L",0);
$pdf->cell(70, $alt,"RESULTADO PATRIMONIAL",0,0,"L",0);
$pdf->cell(20, $alt,db_formatar($resultado_patrimonial_passiva,"f"),"L",1,"R",0);
$pdf->cell(95, $alt,"",0,0,"L",0);
$pdf->cell(65, $alt,"Superávit Verificado","R",0,"L",0);
$pdf->cell(5,  $alt,"",0,0,"L",0);
$pdf->cell(15, $alt,db_formatar($resultado_patrimonial_passiva,"f"),0,1,"R",0);
// TOTAL GERAL ATIVA
if ($total_variacoes_ativa > $total_variacoes_passiva) {
  $total_geral_ativa = $total_variacoes_ativa + $resultado_patrimonial_ativa;
} else {
  $total_geral_ativa = $total_variacoes_passiva + $resultado_patrimonial_passiva;
}

$total_geral_passiva = $total_geral_ativa;

$pos1 = $pdf->gety();
$pos  = $pdf->sety($pos1);
//$pdf->ln(3);
for ($i=0; $i < 1; $i++) {
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->cell(20,$alt,"","R",0,"L",0);
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->ln();
}

$pdf->setfont('arial','b',$fonte);
$pdf->cell(70,$alt,"TOTAL GERAL","R",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_geral_ativa,"f"),"R",1,"R",0);
// TOTAL GERAL PASSIVA
$pos = $pdf->sety($pos1);
//$pdf->ln(3);
for ($i=0; $i < 1; $i++) {
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->cell(20,$alt,"","R",0,"L",0);
  $pdf->cell(70,$alt,"","R",0,"L",0);
  $pdf->ln();
}
$pdf->cell(90,$alt,"","TB",0,"L",0);
$pdf->cell(70, $alt,"TOTAL GERAL","TBR",0,"L",0);
$pdf->cell(20, $alt,db_formatar($total_geral_passiva,"f"),"TB",1,"R",0);
///////////////////////////////////////////////////////////////////////////////

$pdf->Ln(2);
$pdf->setfont('arial','',5);
notasExplicativas($pdf,$iCodRel,"2S",190);

// ASSINATURAS
$pdf->ln(25);
assinaturas($pdf, $classinatura,'BG');


$pdf->Output();
?>
