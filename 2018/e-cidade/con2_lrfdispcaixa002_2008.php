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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));

$classinatura = new cl_assinatura;
## declaracao de  Variaveis
/*
 * lista de contas das obrigacoes financeiras
 */
$aListaObrig = array();
/*
 * lista de contas das Disponibilidades financeiras
 */
$aListaDisp  = array();
/*
 * lista de parametros do relatorio
 */
$aParametros           = array();
$nTotalRPinscritos     = 0;
$nTotalRPinscritosRpps = 0;
$sInstitRPPS           = '';
## Fim declaracao variaveis
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$orcparamrel = new cl_orcparamrel;
$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,
    nomeinstabrev,
    db21_tipoinstit,
    munic
    from db_config
    where codigo in (".str_replace('-',', ',$db_selinstit).") order by db21_tipoinstit");

$descr_inst   = '';
$xvirg        = '';
$yvirg        = '';
$flag_abrev   = false;
$db_selinstit = '';
$temprefa   = false;
$temcamara  = false;
$temadmind  = false;
$flag_abrev = false;

if (!isset($lGerarPDF)){
  $lGerarPDF = true;
}

//******************************************************************
for($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  if ($db21_tipoinstit == 5 or $db21_tipoinstit == 6) {

    $sInstitRPPS .=  "{$yvirg} {$codigo}";
    $yvirg = ', ';

  } else {

    $db_selinstit .= "{$xvirg} {$codigo}";
    $xvirg        = ", ";

  }
  //$xvirg        = '';
  if (strlen(trim($nomeinstabrev)) > 0){
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }

  //$xvirg = ', ';
  if ($db21_tipoinstit == 1) {
    $temprefa=true;
  } elseif ($db21_tipoinstit == 2) {
    $temcamara=true;
  } elseif ($db21_tipoinstit == 5 or $db21_tipoinstit == 7) {
    $temadmind=true;
  }

}
$anousu = db_getsession("DB_anousu");
if ($temcamara == true && ($temprefa == true || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER LEGISLATIVO";
}

if ($temprefa == true && $temcamara == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}
if ($temprefa == true && $temcamara == false && $temadmind == true){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head3 = $descr_inst;
  $head4 = "RELATÓRIO DE GESTÃO FISCAL";
  $head5 = "DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
} else {
  $head3 = "RELATÓRIO DE GESTÃO FISCAL";
  $head4 = "DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA";
  $head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
}


//******************************************************************
$dataini = $anousu.'-01-01';
$datafin = $anousu.'-12-31';
$dt1 = split('-',$dataini);
$dt2 = split('-',$datafin);
$tipo_emissao='periodo';
if ($tipo_emissao=='periodo'){
    //$textodt = strtoupper(db_mes($dt1[1]))." A ".strtoupper(db_mes($dt2[1]))." DE ";
    $textodt = strtoupper("JANEIRO")." A ".strtoupper("DEZEMBRO")." DE ";
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

//$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
$where = " c61_instit in ($db_selinstit)";
$result1 = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
//db_criatabela($result1);

db_query("Drop Table work_pl");
/*
 * Calculamos o total de RPS da instituicoes que nao sejam rpps
 */
$sSqlRPInscritos  = "select coalesce(sum(c70_valor),0) as totalrp";
$sSqlRPInscritos .= "  from conlancam ";
$sSqlRPInscritos .= "        inner join conlancamdoc          on c70_codlan       = c71_codlan";
$sSqlRPInscritos .= "        inner join conencerramentolancam on c44_conlancam    = c70_codlan";
$sSqlRPInscritos .= "        inner join conencerramento       on c44_encerramento = c42_sequencial";
$sSqlRPInscritos .= "  where c71_coddoc = 1007 ";
$sSqlRPInscritos .= "    and c70_anousu = {$anousu} ";
$sSqlRPInscritos .= "    and c42_instit in ({$db_selinstit})";
$rsRPInscritos    = db_query($sSqlRPInscritos);
if ($rsRPInscritos) {

  $oRPinscritos      = db_utils::fieldsMemory($rsRPInscritos,0);
  $nTotalRPinscritos = $oRPinscritos->totalrp;

}
// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

/*
 * array de parametros de disponibilidade financeira
 */
$aTextoDisp[0]["descr"]  = 'DISPONIBILIDADE FINANCEIRA';
$aTextoDisp[1]["descr"]  = '  Caixa';
$aTextoDisp[2]["descr"]  = '  Bancos';
$aTextoDisp[3]["descr"]  = '    Conta Movimento';
$aTextoDisp[4]["descr"]  = '    Contas Vinculadas';
$aTextoDisp[5]["descr"]  = '  Aplicações Financeiras';
$aTextoDisp[6]["descr"]  = '  Outras Disponibilidades Financeiras';

for ($i = 0; $i < count($aTextoDisp); $i++){
  $aTextoDisp[$i]["valor"] = 0;
}
/*
 * array de parametros de Obrigações financeira
 */

$aTextoObrig[0]["descr"] = 'OBRIGAÇÕES FINANCEIRAS';
$aTextoObrig[1]["descr"] = '  Depósitos';
$aTextoObrig[2]["descr"] = '  Restos a Pagar Processados';
$aTextoObrig[3]["descr"] = '    Do Exercício';
$aTextoObrig[4]["descr"] = '    De Exercícios Anteriores';
$aTextoObrig[5]["descr"] = '';
$aTextoObrig[6]["descr"] = '  Outras Obrigações Financeiras';

for ($i = 0; $i < count($aTextoObrig); $i++){
  $aTextoObrig[$i]["valor"] = 0;
}
/*
 * Lista de parametros das disponibilidades
 */

$aParametros[1] = $orcparamrel->sql_parametro('33','1','f',str_replace('-',', ',$db_selinstit));
$aParametros[2] = $orcparamrel->sql_parametro('33','2','f',str_replace('-',', ',$db_selinstit));
$aParametros[3] = $orcparamrel->sql_parametro('33','3','f',str_replace('-',', ',$db_selinstit));
$aParametros[4] = $orcparamrel->sql_parametro('33','4','f',str_replace('-',', ',$db_selinstit));
$aParametros[5] = $orcparamrel->sql_parametro('33','5','f',str_replace('-',', ',$db_selinstit));
/*
 * Lista de parametros das obrigações
 */
$aParametros[6] = $orcparamrel->sql_parametro('33','6','f',str_replace('-',', ',$db_selinstit));
$aParametros[7] = $orcparamrel->sql_parametro('33','7','f',str_replace('-',', ',$db_selinstit));
$aParametros[8] = $orcparamrel->sql_parametro('33','8','f',str_replace('-',', ',$db_selinstit));
$aParametros[9] = $orcparamrel->sql_parametro('33','9','f',str_replace('-',', ',$db_selinstit));

for ($x = 6; $x <= 9; $x++){

  switch($x) {

    case 6:

      $iInd = 1;
      break;

    case 7:
      $iInd = 3;
      break;

    case 8:

      $iInd = 4;
      break;

    case 9:
      $iInd = 6;
      break;
  }

  for ($i=0;$i < pg_numrows($result1); $i++) {

    db_fieldsmemory($result1,$i);
    if (in_array($estrutural,$aParametros[$x])) {
      $aTextoObrig[$iInd]["valor"] += $saldo_final;
    }
  }
}

for ($x = 1; $x <= 5; $x++){

  if ($x > 1){
    $iInd = $x + 1;
  }else{
    $iInd = $x;
  }
  for ($i=0;$i < pg_numrows($result1); $i++) {

    db_fieldsmemory($result1,$i);
    if (in_array($estrutural,$aParametros[$x])) {
      $aTextoDisp[$iInd]["valor"] += $saldo_final;
    }
  }
}


/*
 * Totais
 */
//total Bancos Disponibilidade Financeira
$aTextoDisp[2]["valor"] = ($aTextoDisp[3]["valor"] + $aTextoDisp[4]["valor"]);
//total disponibilidade Financeira
$aTextoDisp[0]["valor"] = ($aTextoDisp[6]["valor"] + $aTextoDisp[5]["valor"]
    + $aTextoDisp[2]["valor"] + $aTextoDisp[1]["valor"]);

/*
 * Totas Obrigações
 */

/*
 * total Bancos
 */
$aTextoObrig[2]["valor"] = ($aTextoObrig[3]["valor"]+$aTextoObrig[4]["valor"]);
/*
 * total Geral
 */
$aTextoObrig[0]["valor"] = ($aTextoObrig[6]["valor"]+$aTextoObrig[5]["valor"]
    +$aTextoObrig[2]["valor"]+$aTextoObrig[1]["valor"]);
$cont = 0;
/*
 * Definimos a suficiencia/insuficiencia do caixa pela
 * diminuicao do total das disponibilidades ($aTextoDisp[0]) com o
 * total das obrigacoes ($aTextoObrig[0])
 * caso o valor for negativo temos insuficiencia, caso positivo temos sufuciencia
 */

$nTotalSuficiencia = (float)($aTextoDisp[0]["valor"] - $aTextoObrig[0]["valor"]);
if ($nTotalSuficiencia > 0){

  $nSuficiencia   = abs($nTotalSuficiencia);
  $nInsuficiencia = 0;

}else{

  $nSuficiencia   = 0;
  $nInsuficiencia = abs($nTotalSuficiencia);

}

$nTotalDisp  = abs(abs($nInsuficiencia) + $aTextoDisp[0]["valor"]);
$nTotalObrig = abs(abs($nSuficiencia) + $aTextoObrig[0]["valor"]);
/*
 * Listamos as contas das outras disponibilidades
 */

for ( $i = 0; $i < pg_num_rows($result1); $i++ ) {

  db_fieldsmemory($result1,$i);
  if ( in_array($estrutural,$aParametros[5]) ) {

    $aListaDisp[$cont]["descr"] = $c60_descr;
    $aListaDisp[$cont]["valor"] = $saldo_final;
    $cont++;

  }
}

$cont   = 0;
for ($i = 0; $i < pg_num_rows($result1); $i++) {

  db_fieldsmemory($result1,$i);
  if ( in_array($estrutural,$aParametros[9]) ) {

    $aListaObrig[$cont]["descr"] = $c60_descr;
    $aListaObrig[$cont]["valor"] = $saldo_final;
    $cont++;
  }
}

/*
 * setamos o total de linhas a ser impresso nas outras disponibilidades/obrigações
 */

if ( count($aListaDisp) > count($aListaObrig) ) {
  $iTotalLinhas = count($aListaDisp);
}else{
  $iTotalLinhas = count($aListaObrig);
}

if ( $lGerarPDF ){

$pdf = new PDF("L","mm","A4");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pdf->addpage();
$alt             = 4;
$pagina          = 1;
$saldo_garantias = 0;
$saldo_contra    = 0;
$pdf->setfont('arial','b',7);
$pdf->cell(200,$alt,'RGF - ANEXO V (LRF, art. 55, Inciso III, alínea "a")','B',0,"L",0);
$pdf->cell(75,$alt,'R$ 1,00','B',1,"R",0);

$pdf->cell(105,$alt,'ATIVO','RB',0,"C",0);
$pdf->cell(33,$alt,'VALOR','RB',0,"C",0);
$pdf->cell(105,$alt,'PASSIVO','RB',0,"C",0);
$pdf->cell(32,$alt,'VALOR','B',1,"C",0);
$pdf->setfont('arial','',7);

for( $i = 0; $i < 7; $i++ ) {

  $pdf->cell(105,$alt,$aTextoDisp[$i]["descr"],'R',0,"L",0);
  if ($aTextoDisp[$i]["valor"] == 0){;
    $pdf->cell(33,$alt,'',"R",0);
  }else{
    $pdf->cell(33,$alt,db_formatar($aTextoDisp[$i]["valor"],'f'),'R',0,"R",0);
  }

  $pdf->cell(105,$alt,$aTextoObrig[$i]["descr"],'R',0,"L",0);
  if ( $aTextoObrig[$i]["valor"] == 0 ) {;
    $pdf->cell(32,$alt,'','',1,"R",0);
  }else{
    $pdf->cell(32,$alt,db_formatar($aTextoObrig[$i]["valor"],'f'),'',1,"R",0);
  }
}

/*
 *  Lista as contas das disponibilidades/obrigacoes
 */

for( $i=0; $i < $iTotalLinhas; $i++ ) {

  if (isset($aListaDisp[$i]) ) {

    $pdf->cell(105,$alt,'    '.substr($aListaDisp[$i]["descr"],0,35),'R',0,"L",0);
    $pdf->cell(33,$alt,db_formatar($aListaDisp[$i]["valor"],'f'),'R',0,"R",0);

  } else {

    $pdf->cell(105,$alt, '','R',0,"L",0);
    $pdf->cell(33,$alt,'','R',0,"R",0);
  }

  if (isset($aListaObrig[$i])){

    $pdf->cell(105,$alt,'    '.substr($aListaObrig[$i]["descr"],0,37),'R',0,"L",0);
    $pdf->cell(32,$alt,db_formatar($aListaObrig[$i]["valor"],'f'),'',1,"R",0);

  } else{

    $pdf->cell(105,$alt,'','R',0,"L",0);
    $pdf->cell(32,$alt,'','',1,"R",0);
  }
}

if ($nInsuficiencia == 0){
    $sInsuficiencia = "-";
}else{
    $sInsuficiencia = db_formatar($nInsuficiencia,'f');
}
if ($nSuficiencia == 0){
  $sSuficiencia = " -";
}else{
  $sSuficiencia = db_formatar($nSuficiencia,'f');
}
$pdf->cell(105,$alt,'INSUFICIÊNCIA ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS(I)','TR',0,"L",0);
$pdf->cell(33,$alt,$sInsuficiencia,'TR',0,"R",0);
$pdf->cell(105,$alt,'SUFICIÊNCIA ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS(II)','TR',0,"L",0);
$pdf->cell(32,$alt,$sSuficiencia,'T',1,"R",0);

$pdf->cell(105,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(33,$alt,db_formatar($nTotalDisp,'f'),'TBR',0,"R",0);
$pdf->cell(105,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($nTotalObrig,'f'),'TB',1,"R",0);

$pdf->cell(243,$alt,'INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS (III)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($nTotalRPinscritos,'f'),'TB',1,"R",0);
$pdf->cell(243,$alt,'SUFICIÊNCIA APÓS A INSCRIÇÃO EM RESTOS APAGAR NÃO PROCESSADOS (IV) = (II - III)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($nSuficiencia - $nTotalRPinscritos, 'f'),'TB',1,"R",0);

if ($sInstitRPPS != ''){

   $pdf->ln();
   $aListaObrig = array();
   $aListaDisp  = array();
/*
 * seleciona instituição do RPPS
 */
   $where       = " c61_instit in (".$sInstitRPPS.") ";
   $result_rpps = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);


  /*
   * array de parametros de disponibilidade financeira
   */
  $aTextoDisp[0]["descr"]  = 'DISPONIBILIDADE FINANCEIRA';
  $aTextoDisp[1]["descr"]  = '  Caixa';
  $aTextoDisp[2]["descr"]  = '  Bancos';
  $aTextoDisp[3]["descr"]  = '    Conta Movimento';
  $aTextoDisp[4]["descr"]  = '    Conta Vinculada';
  $aTextoDisp[5]["descr"]  = '  Aplicações Financeiras';
  $aTextoDisp[6]["descr"]  = '  Outras Disponibilidades Financeiras';

  for ($i = 0; $i < count($aTextoDisp); $i++){
    $aTextoDisp[$i]["valor"] = 0;
  }

  $aTextoObrig[0]["descr"] = 'OBRIGAÇÕES FINANCEIRAS';
  $aTextoObrig[1]["descr"] = '  Depósitos';
  $aTextoObrig[2]["descr"] = '  Restos a Pagar Processados';
  $aTextoObrig[3]["descr"] = '    Do Exercício';
  $aTextoObrig[4]["descr"] = '    De Exercício Anteriores';
  $aTextoObrig[5]["descr"] = '';
  $aTextoObrig[6]["descr"] = '  Outras Obrigações Financeiras';

  for ($i = 0; $i < count($aTextoObrig); $i++){
    $aTextoObrig[$i]["valor"] = 0;
  }
  /*
   * Lista de parametros das disponibilidades
   */

  $aParametros[1] = $orcparamrel->sql_parametro('33','1','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[2] = $orcparamrel->sql_parametro('33','2','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[3] = $orcparamrel->sql_parametro('33','3','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[4] = $orcparamrel->sql_parametro('33','4','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[5] = $orcparamrel->sql_parametro('33','5','f',str_replace('-',', ',$sInstitRPPS));
  /*
   * Lista de parametros das obrigações
   */
  $aParametros[6] = $orcparamrel->sql_parametro('33','6','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[7] = $orcparamrel->sql_parametro('33','7','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[8] = $orcparamrel->sql_parametro('33','8','f',str_replace('-',', ',$sInstitRPPS));
  $aParametros[9] = $orcparamrel->sql_parametro('33','9','f',str_replace('-',', ',$sInstitRPPS));

  for ($x = 6; $x <= 9; $x++){

    switch($x) {

      case 6:

        $iInd = 1;
        break;

      case 7:
        $iInd = 3;
        break;

      case 8:

        $iInd = 4;
        break;

      case 9:
        $iInd = 6;
        break;
    }

    for ($i=0;$i < pg_numrows($result_rpps); $i++) {

      db_fieldsmemory($result_rpps,$i);
      if (in_array($estrutural,$aParametros[$x])) {
        $aTextoObrig[$iInd]["valor"] += $saldo_final;
      }
    }
  }

  for ($x = 1; $x <= 5; $x++){

    if ($x > 1){
      $iInd = $x + 1;
    }else{
      $iInd = $x;
    }
    for ($i=0;$i < pg_numrows($result_rpps); $i++) {

      db_fieldsmemory($result_rpps,$i);
      if (in_array($estrutural,$aParametros[$x])) {
        $aTextoDisp[$iInd]["valor"] += $saldo_final;
      }
    }
  }


  /*
   * Totais
   */
  //total Bancos Disponibilidade Financeira
  $aTextoDisp[2]["valor"] = ($aTextoDisp[3]["valor"] + $aTextoDisp[4]["valor"]);
  //total disponibilidade Financeira
  $aTextoDisp[0]["valor"] = ($aTextoDisp[6]["valor"] + $aTextoDisp[5]["valor"]
      + $aTextoDisp[2]["valor"] + $aTextoDisp[1]["valor"]);

  /*
   * Totas Obrigações
   */

  /*
   * total Bancos
   */
  $aTextoObrig[2]["valor"] = ($aTextoObrig[3]["valor"]+$aTextoObrig[4]["valor"]);
  /*
   * total Geral
   */
  $aTextoObrig[0]["valor"] = ($aTextoObrig[6]["valor"]+$aTextoObrig[5]["valor"]
      +$aTextoObrig[2]["valor"]+$aTextoObrig[1]["valor"]);
  $cont = 0;
  /*
   * Definimos a suficiencia/insuficiencia do caixa pela
   * diminuicao do total das disponibilidades ($aTextoDisp[0]) com o
   * total das obrigacoes ($aTextoObrig[0])
   * caso o valor for negativo temos insuficiencia, caso positivo temos sufuciencia
   */

  $nTotalSuficiencia = (float)($aTextoDisp[0]["valor"] - $aTextoObrig[0]["valor"]);
  if ($nTotalSuficiencia > 0){

    $nSuficiencia   = abs($nTotalSuficiencia);
    $nInsuficiencia = 0;

  }else{

    $nSuficiencia   = 0;
    $nInsuficiencia = abs($nTotalSuficiencia);

  }

  $nTotalDisp  = abs($nInsuficiencia + $aTextoDisp[0]["valor"]);
  $nTotalObrig = abs($nSuficiencia + $aTextoObrig[0]["valor"]);
  /*
   * Listamos as contas das outras disponibilidades
   */

  for ( $i = 0; $i < pg_num_rows($result_rpps); $i++ ) {

    db_fieldsmemory($result_rpps,$i);
    if ( in_array($estrutural,$aParametros[5]) ) {

      $aListaDisp[$cont]["descr"] = $c60_descr;
      $aListaDisp[$cont]["valor"] = $saldo_final;
      $cont++;

    }
  }

  $cont   = 0;
  for ($i = 0; $i < pg_num_rows($result_rpps); $i++) {

    db_fieldsmemory($result_rpps,$i);
    if ( in_array($estrutural,$aParametros[9]) ) {

      $aListaObrig[$cont]["descr"] = $c60_descr;
      $aListaObrig[$cont]["valor"] = $saldo_final;
      $cont++;
    }
  }

  /*
   * setamos o total de linhas a ser impresso nas outras disponibilidades/obrigações
   */

  if ( count($aListaDisp) > count($aListaObrig) ) {
    $iTotalLinhas = count($aListaDisp);
  }else{
    $iTotalLinhas = count($aListaObrig);
  }

  $pdf->setfont('arial','b',7);
  $pdf->cell(275,$alt,'REGIME PREVIDENCIÁRIO','TB',1,"C",0);
  $pdf->cell(105,$alt,'ATIVO','RB',0,"C",0);
  $pdf->cell(33,$alt,'VALOR','RB',0,"C",0);
  $pdf->cell(105,$alt,'PASSIVO','RB',0,"C",0);
  $pdf->cell(32,$alt,'VALOR','B',1,"C",0);
  $pdf->setfont('arial','',7);

  for( $i = 0; $i < 7; $i++ ) {

    $pdf->cell(105,$alt,$aTextoDisp[$i]["descr"],'R',0,"L",0);
    if ($aTextoDisp[$i]["valor"] == 0){;
      $pdf->cell(33,$alt,'','R',0,"R",0);
    }else{
      $pdf->cell(33,$alt,db_formatar($aTextoDisp[$i]["valor"],'f'),'R',0,"R",0);
    }

    $pdf->cell(105,$alt,$aTextoObrig[$i]["descr"],'R',0,"L",0);
    if ( $aTextoObrig[$i]["valor"] == 0 ) {;
      $pdf->cell(32,$alt,'','',1,"R",0);
    }else{
      $pdf->cell(32,$alt,db_formatar($aTextoObrig[$i]["valor"],'f'),'',1,"R",0);
    }
  }

  /*
   *  Lista as contas das disponibilidades/obrigacoes
   */

  for( $i=0; $i < $iTotalLinhas; $i++ ) {

    if (isset($aListaDisp[$i]) ) {

      $pdf->cell(105,$alt,'    '.substr($aListaDisp[$i]["descr"],0,35),'R',0,"L",0);
      $pdf->cell(33,$alt,db_formatar($aListaDisp[$i]["valor"],'f'),'R',0,"R",0);

    } else {

      $pdf->cell(105,$alt, '','R',0,"L",0);
      $pdf->cell(33,$alt,'','R',0,"R",0);
    }

    if (isset($aListaObrig[$i])){

      $pdf->cell(105,$alt,'    '.substr($aListaObrig[$i]["descr"],0,37),'R',0,"L",0);
      $pdf->cell(32,$alt,db_formatar($aListaObrig[$i]["valor"],'f'),'',1,"R",0);

    } else{

      $pdf->cell(105,$alt,'','R',0,"L",0);
      $pdf->cell(32,$alt,'','',1,"R",0);
    }
  }


  $pdf->cell(105,$alt,'INSUFICIÊNCIA ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS','TR',0,"L",0);
  $pdf->cell(33,$alt,'','TR',0,"R",0);
  $pdf->cell(105,$alt,'SUFICIÊNCIA ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS ','TR',0,"L",0);
  $pdf->cell(32,$alt,'','T',1,"R",0);

  if ($nInsuficiencia == 0) {
     $sInsuficiencia = "-";
  } else{
     $sInsuficiencia = db_formatar($nInsuficiencia,'f');
  }
  if ($nSuficiencia == 0) {
    $sSuficiencia = "-";
  } else {
    $sSuficiencia = db_formatar($nSuficiencia,'f');
  }

  $pdf->cell(105,$alt,'DO REGIME PREVIDENCIÁRIO(V)','BR',0,"L",0);
  $pdf->cell(33,$alt,$sInsuficiencia,'BR',0,"R",0);
  $pdf->cell(105,$alt,'DO REGIME PREVIDENCIÁRIO(VI)','BR',0,"L",0);
  $pdf->cell(32,$alt,$sSuficiencia,'B',1,"R",0);

  $pdf->cell(105,$alt,'TOTAL','TBR',0,"L",0);
  $pdf->cell(33,$alt,db_formatar($nTotalDisp,'f'),'TBR',0,"R",0);
  $pdf->cell(105,$alt,'TOTAL','TBR',0,"L",0);
  $pdf->cell(32,$alt,db_formatar($nTotalObrig,'f'),'TB',1,"R",0);

  $pdf->cell(243,$alt,'INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO REGIME PREVIDENCIÁRIO (VII)','TBR',0,"L",0);
  $pdf->cell(32,$alt,db_formatar($nTotalRPinscritos,'f'),'TB',1,"R",0);
  $pdf->cell(243,$alt,'SUFICIÊNCIA APÓS A INSCRIÇÃO EM RESTOS APAGAR NÃO PROCESSADOS DO REGIME PREVIDENCIÁRIO (VIII) = (VI - VII)','TBR',0,"L",0);
  $pdf->cell(32,$alt,db_formatar($nSuficiencia -$nTotalRPinscritos, 'f'),'TB',1,"R",0);
}
notasExplicativas($pdf, 33, "1B",275);
$pdf->Ln(5);
// assinaturas
if ($sInstitRPPS != ''){

  $pdf->addPage();
}
$pdf->setfont('arial','',5);
$pdf->ln(20);

assinaturas($pdf,$classinatura,'GF');

$pdf->Output();
}
?>
