<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

  require_once(modification("fpdf151/pdf.php"));
  require_once(modification("fpdf151/assinatura.php"));
  require_once(modification("libs/db_sql.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_libcontabilidade.php"));
  require_once(modification("libs/db_liborcamento.php"));
  require_once(modification("libs/db_libtxt.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_orcparamrel_classe.php"));
  require_once(modification("classes/db_conrelinfo_classe.php"));
  require_once(modification("model/linhaRelatorioContabil.model.php"));
  require_once(modification("model/relatorioContabil.model.php"));

  $classinatura = new cl_assinatura;
  $orcparamrel = new cl_orcparamrel;
  $clconrelinfo = new cl_conrelinfo;

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

  //-----------------------------------
  $tipo_emissao='periodo';

  $anousu      = db_getsession("DB_anousu");
  $anousu_ant  = db_getsession("DB_anousu")-1;
  $oDaoPeriodo     = db_utils::getDao("periodo");
  $iCodigoPeriodo  = $periodo;
  $anousu = db_getsession("DB_anousu");
  $instit = db_getsession("DB_instit");
  $sSqlPeriodo   = $oDaoPeriodo->sql_query($periodo);
  $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
  $dt       = data_periodo($anousu,$sSiglaPeriodo);
  $periodo = $sSiglaPeriodo;
  $dt = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
  $dt_ini= $anousu.'-01-01'; // data inicial do perodo
  $dt_fin= $dt[1]; // data final do perodo
  $texto = $dt['texto'];
  $txtper = $dt['periodo'];

}   // end !include
// verifica periodo anterior ( bimestre anterior )
$per = substr($periodo,0,1);
if ($per >1 ){
  $periodo_ant= ($per -1).'B';
} else {
  $periodo_ant= '1B';
}
$iCodigoRelatorio = 88;
$anousu_ant  = db_getsession("DB_anousu");

/**
 * Se está sendo utilizado pelo AnexoXVIIIResumido o comportamento é diferente
 */
if (isset($arqinclude)) {

  $oDaoPeriodo   = new cl_periodo();
  $sSqlPeriodo   = $oDaoPeriodo->sql_query($iCodigoPeriodo);
  $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
  $dt            = data_periodo($anousu,$sSiglaPeriodo);
} else {
  $dt = data_periodo($anousu_ant,$periodo_ant); // no dbforms/db_funcoes.php
}

$dt_ini_ant = $dt[0]; // data inicial do perodo
$dt_fin_ant = $dt[1]; // data final do perodo


$META_NOMINAL = 0;
$db_selinstit_sem_rpps = '';
$n1 = 5;
$oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
$resultinst = db_query("select codigo,munic,db21_tipoinstit from db_config");
$descr_inst = '';
$xvirgi     = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  $oInstituicao = db_utils::fieldsMemory($resultinst,$xins);

  if($oInstituicao->db21_tipoinstit != 5 && $oInstituicao->db21_tipoinstit != 6 ){
    $db_selinstit_sem_rpps .= $xvirgi.$oInstituicao->codigo;
    $xvirgi       = ', ';
  }
}
for ($i = 1; $i <= 14; $i++) {

  $oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, $i);
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);

  $aValoresColunasLinhas            = $oLinhaRelatorio->getValoresSomadosColunas($db_selinstit_sem_rpps, $anousu);
  $aLinhaRelatorio[$i]->oParametros = $oLinhaRelatorio->getParametros($anousu);
  foreach ($aValoresColunasLinhas as $oValorColuna) {

    if ($i == 7) {

      $aLinhaRelatorio[7]->valor += $oValorColuna->colunas[1]->o117_valor;
    } else {

      $aLinhaRelatorio[$i]->valorBimestreAnterior += $oValorColuna->colunas[2]->o117_valor;
      $aLinhaRelatorio[$i]->valorAnterior         += $oValorColuna->colunas[1]->o117_valor;
      $aLinhaRelatorio[$i]->valorNoBimestre       += $oValorColuna->colunas[3]->o117_valor;

    }
  }
}


$somador_I_ant =0;
$somador_I_antbim =0;
$somador_I_bim =0;
$somador_II_ant =0;
$somador_II_antbim =0;
$somador_II_bim =0;
$somador_III_ant =0;
$somador_III_antbim =0;
$somador_III_bim =0;
$somador_IV_ant =0;
$somador_IV_antbim =0;
$somador_IV_bim =0;
$somador_V_ant =0;
$somador_V_antbim =0;
$somador_V_bim =0;

// RPPS
// DIVIDA CONSOLIDADA PREVIDENCIARIA
$somador_VI_ant      = 0;
$somador_VI_antbim   = 0;
$somador_VI_bim      = 0;
// DEDUCOES
$somador_VII_ant     = 0;
$somador_VII_antbim  = 0;
$somador_VII_bim     = 0;
// DIVIDA CONSOLIDADA LIQUIDA PREVIDENCIARIA
$somador_VIII_ant    = 0;
$somador_VIII_antbim = 0;
$somador_VIII_bim    = 0;
// PASSIVOS RECONHECIDOS
$somador_IX_ant      = 0;
$somador_IX_antbim   = 0;
$somador_IX_bim      = 0;

//----------------------------------- // -------------------------------

db_fieldsmemory($resultinst,0);

$descr_inst = "{$munic} - {$oPrefeitura->getUf()}";

if (!isset($arqinclude)){

  ///////////////////////////////
  $head2  = "MUNICÍPIO DE ".$descr_inst;
  $head3  = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4  = "DEMONSTRATIVO DO RESULTADO NOMINAL";
  $head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

  $dados  = data_periodo($anousu,$periodo);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);

  $txtper = strtoupper($dados["periodo"]);
  $mesini = strtoupper(db_mes($perini[1]));
  $mesfin = strtoupper(db_mes($perfin[1]));

  $head6 = "JANEIRO A ".$mesfin."/".$anousu." - ".$txtper." ".$mesini."-".$mesfin;
}  // end !include
//////////////////////////

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit_sem_rpps).")  ";

// echo(db_getsession("DB_anousu") . " - " . $dt_ini_ant . " - " . $dt_fin_ant . $where)."\n\n";
// echo(db_getsession("DB_anousu") . " - " . $dt_ini     . " - " . $dt_fin     . $where);exit;

if (!isset($lInResumido)) {

  $result_peranterior = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini_ant,$dt_fin_ant,false,$where);
  db_query("drop table if exists work_pl");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrutmae");
}


$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fin,false,$where);
db_query("drop table if exists work_pl");
db_query("drop table if exists work_pl_estrut");
db_query("drop table if exists work_pl_estrut");
db_query("drop table if exists work_pl_estrutmae");
//////////////////////////
for ($i = 0; $i < pg_numrows($result);$i++) {

  $oResultado      = db_utils::fieldsmemory($result,$i);
  for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

    if ($iLinha == 7) {
      continue;
    }
    $oParametro      = $aLinhaRelatorio[$iLinha]->oParametros;
    foreach ($oParametro->contas as $oEstrutural) {

      $oVerificacao    = $oLinhaRelatorio->match($oEstrutural ,$oParametro->orcamento, $oResultado, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $aLinhaRelatorio[$iLinha]->valorAnterior   -= $oResultado->saldo_anterior;
          $aLinhaRelatorio[$iLinha]->valorNoBimestre -= $oResultado->saldo_final;

        } else {

          $aLinhaRelatorio[$iLinha]->valorAnterior    += $oResultado->saldo_anterior;
          $aLinhaRelatorio[$iLinha]->valorNoBimestre  += $oResultado->saldo_final;
        }
      }
    }
  }
}
if (!isset($lInResumido)) {

  for ($i = 0; $i < pg_numrows($result);$i++) {

    $oResultado      = db_utils::fieldsmemory($result_peranterior, $i);
    for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

      if ($iLinha == 7) {
        continue;
      }
      $oParametro      = $aLinhaRelatorio[$iLinha]->oParametros;
      foreach ($oParametro->contas as $oEstrutural) {

        $oVerificacao    = $oLinhaRelatorio->match($oEstrutural ,$oParametro->orcamento, $oResultado, 3);
        if ($oVerificacao->match) {

          if ($oVerificacao->exclusao) {
            $aLinhaRelatorio[$iLinha]->valorBimestreAnterior   -= $oResultado->saldo_final;
          } else {
            $aLinhaRelatorio[$iLinha]->valorBimestreAnterior   += $oResultado->saldo_final;
          }
        }
      }
    }
  }
}
if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $alt            = 4;
  $pagina         = 1;

  $pdf->addpage();
  $pdf->setfont('arial','',8);

  $pdf->ln();
  $pdf->cell(70,$alt,"RREO - ANEXO 5 (LRF, art. 53, inciso III)",'0',0,"L",0);
  $pdf->cell(120,$alt,"R$ 1,00",0,"R",0);

  $pdf->ln();
  $pdf->cell(70,($alt*2),"DÍVIDA FISCAL LÍQUIDA",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"SALDO",'TB',1,"C",0);      // br
  $pdf->setX(80);
  $pdf->cell(40,$alt,"Em 31/Dez/".($anousu_ant-1)." (a)",'1',0,"C",0);
  $dt = split("-",$dt_fin_ant);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (b)",'1',0,"C",0);

  $dt = split("-",$dt_fin);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (c)",'TB',0,"C",0);
  $pdf->Ln();

} // end !include

//---------------
$tot_ant  = 0;
$tot_biant= 0;
$tot_bi   = 0;


//echo "Aqui"; exit;

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA CONSOLIDADA (I)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[1]->valorAnterior, 'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[1]->valorBimestreAnterior ,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[1]->valorNoBimestre ,'f'),'0',0,"R",0);
  $pdf->Ln();

}

$somador_I_ant    += $aLinhaRelatorio[1]->valorAnterior;
$somador_I_antbim += $aLinhaRelatorio[1]->valorBimestreAnterior;
$somador_I_bim    += $aLinhaRelatorio[1]->valorNoBimestre;

// -- dedudoes
if (!isset($arqinclude)){

  $pos_deducao = $pdf->getY();
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DEDUÇÕES (II)",'R',0,"L",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','R',0,"R",0);
  $pdf->cell(40,$alt,'','0',0,"R",0);
  $pdf->Ln();

} // end !include

// -----------

if (!isset($arqinclude)) {

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Disponibilidade de Caixa Bruta",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[2]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[2]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[2]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();

}

$somador_II_ant    += $aLinhaRelatorio[2]->valorAnterior;
$somador_II_antbim += $aLinhaRelatorio[2]->valorBimestreAnterior;
$somador_II_bim    += $aLinhaRelatorio[2]->valorNoBimestre;

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Demais Haveres Financeiros",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[3]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[3]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[3]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
}

$somador_II_ant    += $aLinhaRelatorio[3]->valorAnterior;
$somador_II_antbim += $aLinhaRelatorio[3]->valorBimestreAnterior;
$somador_II_bim    += $aLinhaRelatorio[3]->valorNoBimestre;


if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."(-) Restos a Pagar Processados (Exceto Precatórios)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[4]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[4]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[4]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$somador_II_ant    -= $aLinhaRelatorio[4]->valorAnterior;
$somador_II_antbim -= $aLinhaRelatorio[4]->valorBimestreAnterior;
$somador_II_bim    -= $aLinhaRelatorio[4]->valorNoBimestre;
//-----------
// imprime la em cima o total das deducoes

if (!isset($arqinclude)){

  $pos_atu = $pdf->y; // posio atual
  // sobe, escreve e desce
  $pdf->setY($pos_deducao);
  $pdf->setX(80);

  $pdf->cell(40,$alt,($somador_II_ant < 0    ?"-":db_formatar($somador_II_ant,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_II_antbim < 0?"-":db_formatar($somador_II_antbim,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_II_bim < 0?   "-":db_formatar($somador_II_bim,'f')),'0',0,"R",0);

  $pdf->setY($pos_atu); // desce novamente at aki

}

if ($somador_II_ant < 0) {
  $somador_II_ant = 0;
}

if ($somador_II_antbim < 0) {
  $somador_II_antbim = 0;
}

if ($somador_II_bim < 0) {
  $somador_II_bim = 0;
}


//------------
$somador_III_ant    = ($somador_I_ant - $somador_II_ant);
$somador_III_antbim = ($somador_I_antbim - $somador_II_antbim);
$somador_III_bim    = ($somador_I_bim - $somador_II_bim);

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DIVIDA CONSOLIDADA LIQUIDA (III) = (I-II)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_antbim,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_III_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include
//---------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"RECEITA DE PRIVATIZAÇÕES (IV)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[5]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[5]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[5]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include

$somador_IV_ant    += $aLinhaRelatorio[5]->valorAnterior;
$somador_IV_antbim += $aLinhaRelatorio[5]->valorBimestreAnterior;
$somador_IV_bim    += $aLinhaRelatorio[5]->valorNoBimestre;


if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"PASSIVOS RECONHECIDOS (V)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[6]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[6]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[6]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
} // end !include

$somador_V_ant    += $aLinhaRelatorio[6]->valorAnterior;
$somador_V_antbim += $aLinhaRelatorio[6]->valorBimestreAnterior;
$somador_V_bim    += $aLinhaRelatorio[6]->valorNoBimestre;


//------------
$tot_ant   = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);
$tot_biant = (($somador_III_antbim + $somador_IV_antbim) - $somador_V_antbim);
$tot_bi    = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );

$TOTAL_ANTERIOR = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA FISCAL LÍQUIDA(VI) = (III+IV-V)",'TBR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'TBR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'TBR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'TB',0,"R",0);
  $pdf->Ln();

  //-------------- // ----------- // --------------
  // imprime resultado nominal
  $pdf->Ln(4);
  $pdf->setfont('arial','',7);
  $pdf->cell(70,($alt*2),"RESULTADO NOMINAL",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"PERÍODO DE REFERÊNCIA",'TB',1,"C",0); // br
  $pdf->setX(80);
  $pdf->cell(60,$alt,"No Bimestre (c-b)",'1',0,"C",0);
  $pdf->cell(60,$alt,"Até o Bimestre (c-a)",'TB',0,"C",0);
  $pdf->Ln();

  //
  $pdf->cell(70,$alt,"VALOR",'TBR',0,"L",0);
  if ($tot_bi < 0 && $tot_biant <0){
    // subtrai e mantem o sinal do maior
    $pdf->cell(60,$alt,db_formatar(((abs($tot_bi)-abs($tot_biant))*-1),'f'),'TBR',0,"R",0);
  } else {
    $pdf->cell(60,$alt,db_formatar(($tot_bi-$tot_biant),'f'),'TBR',0,"R",0);
  }
  if ($tot_bi <0 && $tot_ant  <0){
    // subtrai e fica o sinal do maior
    $pdf->cell(60,$alt,db_formatar(((abs($tot_bi)-abs($tot_ant))*-1),'f'),'TB',0,"R",0);
  }else {
    $pdf->cell(60,$alt,db_formatar(($tot_bi-$tot_ant),'f'),'TB',0,"R",0);
  }
  $pdf->ln(4);

  // imprime meta fiscal
  $pdf->ln(4);
  $pdf->cell(130,$alt,"DISCRIMINAÇÃO DA META FISCAL",'TBR',0,"C",0);
  $pdf->cell(60,$alt,"VALOR CORRENTE",'TB',1,"C",0);

  $pdf->cell(130,$alt,"META DE RESULTADO NOMINAL FIXADA NO ANEXO DE METAS FISCAIS",'R',0,"L",0);
  $pdf->cell(60,$alt,db_formatar($aLinhaRelatorio[7]->valor,'f'),'0',0,"R",0);

  $pdf->Ln();
  $pdf->cell(130,$alt,"DA LDO P/ O EXERCÍCIO DE REFERÊNCIA",'BR',0,"L",0);
  $pdf->cell(60,$alt,'','TB',1,"R",0);

  //echo "Antes RPPS"; exit;

}

// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// seleciona instituio do RPPS
$sql    = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
$resultinst = db_query($sql);
$instit ='0';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){

  $oInstituicao = db_utils::fieldsMemory($resultinst,$xins);
  $instit      .= $xvirg.$oInstituicao->codigo; // salva insituio
  $xvirg       = ', ';
}
if (!isset($lInResumido)) {

  $where = " c61_instit in (".$instit.") ";
  $result_peranterior = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini_ant,$dt_fin_ant,false,$where);
  db_query("drop table if exists work_pl");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrutmae");

  $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fin,false,$where);
  db_query("drop table if exists work_pl");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrut");
  db_query("drop table if exists work_pl_estrutmae");
  ///////////////////////////////////////////////////////////////////////////////////////////////////////
  for ($i = 0; $i < pg_numrows($result);$i++) {

    $oResultado      = db_utils::fieldsmemory($result,$i);
    for ($iLinha = 8; $iLinha <= 14; $iLinha++) {

      $oParametro      = $aLinhaRelatorio[$iLinha]->oParametros;
      foreach ($oParametro->contas as $oEstrutural) {

        $oVerificacao    = $oLinhaRelatorio->match($oEstrutural ,$oParametro->orcamento, $oResultado, 3);
        if ($oVerificacao->match) {

          if ($oVerificacao->exclusao) {

            $aLinhaRelatorio[$iLinha]->valorAnterior   -= $oResultado->saldo_anterior;
            $aLinhaRelatorio[$iLinha]->valorNoBimestre -= $oResultado->saldo_final;

          } else {

            $aLinhaRelatorio[$iLinha]->valorAnterior    += $oResultado->saldo_anterior;
            $aLinhaRelatorio[$iLinha]->valorNoBimestre  += $oResultado->saldo_final;
          }
        }
      }
    }
  }
  if (!isset($lInResumido)) {

    for ($i = 0; $i < pg_numrows($result);$i++) {

      $oResultado      = db_utils::fieldsmemory($result_peranterior, $i);
      for ($iLinha = 8; $iLinha <= 14; $iLinha++) {

       $oParametro      = $aLinhaRelatorio[$iLinha]->oParametros;
        foreach ($oParametro->contas as $oEstrutural) {

          $oVerificacao    = $oLinhaRelatorio->match($oEstrutural ,$oParametro->orcamento, $oResultado, 3);
          if ($oVerificacao->match) {

            if ($oVerificacao->exclusao) {
              $aLinhaRelatorio[$iLinha]->valorBimestreAnterior   -= $oResultado->saldo_final;
            } else {
              $aLinhaRelatorio[$iLinha]->valorBimestreAnterior   += $oResultado->saldo_final;
            }
          }
        }
      }
    }
  }
}
//echo "Depois RPPS"; exit;
if (!isset($arqinclude)){

  $pdf->ln();
  $pdf->cell(190,$alt,"REGIME PREVIDENCIÁRIO",'TB',1,"C",0);
  $pdf->cell(70,($alt*2),"DÍVIDA FISCAL LÍQUIDA PREVIDENCIÁRIA",'TBR',0,"C",0);
  $pdf->cell(120,($alt),"SALDO",'TB',1,"C",0);      // br
  $pdf->setX(80);
  $pdf->cell(40,$alt,"Em 31/Dez/".($anousu_ant-1)." (a)",'1',0,"C",0);
  $dt = split("-",$dt_fin_ant);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (b)",'1',0,"C",0);

  $dt = split("-",$dt_fin);
  $dt = $dt[2]."/".db_mes($dt[1])."/".$dt[0];
  $pdf->cell(40,$alt,"Em $dt (c)",'TB',0,"C",0);
  $pdf->Ln();


}

//---------------
$tot_ant =0;
$tot_biant=0;
$tot_bi =0;

$somador_VI_ant    += $aLinhaRelatorio[8]->valorAnterior+$aLinhaRelatorio[9]->valorAnterior;
$somador_VI_antbim += $aLinhaRelatorio[8]->valorBimestreAnterior+$aLinhaRelatorio[9]->valorBimestreAnterior;
$somador_VI_bim    += $aLinhaRelatorio[8]->valorNoBimestre+$aLinhaRelatorio[9]->valorNoBimestre;

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DIVIDA CONSOLIDADA PREVIDENCIÁRIA (VII)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador_VI_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VI_antbim,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VI_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
}
if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Passivo Atuarial",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[8]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[8]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[8]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();

}

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Demais Dívidas",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[9]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[9]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[9]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();

}

// -- dedudoes

$somador_VII_ant    +=   ($aLinhaRelatorio[10]->valorAnterior + $aLinhaRelatorio[11]->valorAnterior
                        + $aLinhaRelatorio[12]->valorAnterior) - $aLinhaRelatorio[13]->valorAnterior;

$somador_VII_antbim += ($aLinhaRelatorio[10]->valorBimestreAnterior + $aLinhaRelatorio[11]->valorBimestreAnterior +
                        $aLinhaRelatorio[12]->valorBimestreAnterior) - $aLinhaRelatorio[13]->valorBimestreAnterior;

$somador_VII_bim    += ($aLinhaRelatorio[10]->valorNoBimestre + $aLinhaRelatorio[11]->valorNoBimestre +
                       $aLinhaRelatorio[12]->valorNoBimestre) -$aLinhaRelatorio[13]->valorNoBimestre ;

if (!isset($arqinclude)) {

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DEDUÇÕES (VIII)",'R',0,"L",0);
  $pdf->cell(40,$alt,($somador_VII_ant    < 0?"-":db_formatar($somador_VII_ant,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_VII_antbim < 0?"-":db_formatar($somador_VII_antbim,'f')),'R',0,"R",0);
  $pdf->cell(40,$alt,($somador_VII_bim    < 0?"-":db_formatar($somador_VII_bim,'f')),'0',0,"R",0);
  $pdf->Ln();

}

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Disponibilidade de Caixa Bruta",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[10]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[10]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[10]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();

}

if (!isset($arqinclude)) {

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."Investimentos",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[11]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[11]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[11]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
}

// -----

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)." Demais Haveres Financeiros",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[12]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[12]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[12]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();
}

if (!isset($arqinclude)){

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,espaco($n1)."(-) Restos a Pagar Processados",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[13]->valorAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[13]->valorBimestreAnterior,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($aLinhaRelatorio[13]->valorNoBimestre,'f'),'0',0,"R",0);
  $pdf->Ln();

}

//-----------
// imprime la em cima o total das deducoes

if (!isset($arqinclude)){
  $pos_atu = $pdf->y; // posio atual
  // sobe, escreve e desce
  $pdf->setY($pos_deducao);
  $pdf->setX(80);


}
if ($somador_VII_ant < 0) {
  $somador_VII_ant = 0;
}

if ($somador_VII_antbim < 0) {
  $somador_VII_antbim = 0;
}

if ($somador_VII_bim < 0) {
  $somador_VII_bim = 0;
}

if (!isset($arqinclude)){
  $pdf->setY($pos_atu); // desce novamente at aki
}

//------------
$somador_VIII_ant    = ($somador_VI_ant    - $somador_VII_ant);
$somador_VIII_antbim = ($somador_VI_antbim - $somador_VII_antbim);
$somador_VIII_bim    = ($somador_VI_bim    - $somador_VII_bim);
$somador_IX_ant      = $aLinhaRelatorio[14]->valorAnterior;
$somador_IX_antbim   = $aLinhaRelatorio[14]->valorBimestreAnterior;
$somador_IX_bim      = $aLinhaRelatorio[14]->valorNoBimestre;
if (!isset($arqinclude)){
  $pdf->setfont('arial','',6);
  $pdf->cell(70,$alt,"DíVIDA CONSOLIDADA LíQUIDA PREVIDENCIÁRIA(IX) = (VII-VIII)",'R',0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_ant,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_antbim,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_VIII_bim,'f'),'0',0,"R",0);
  $pdf->Ln();

  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"PASSIVOS RECONHECIDOS(X)",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_ant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_antbim,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($somador_IX_bim,'f'),'0',0,"R",0);
  $pdf->Ln();
}
$tot_ant   = ($somador_VIII_ant    - $somador_IX_ant);
$tot_biant = ($somador_VIII_antbim - $somador_IX_antbim);
$tot_bi    = ($somador_VIII_bim    - $somador_IX_bim);

if (!isset($arqinclude)){
  $pdf->setfont('arial','',7);
  $pdf->cell(70,$alt,"DÍVIDA FISCAL LÍQUIDA PREVIDENCIÁRIA(XI) = (IX-X)",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($tot_ant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_biant,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($tot_bi,'f'),'TB',0,"R",0);

  ///////////////////////////////////////////////////////////////////////////////
  $pdf->Ln();
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo);
  $pdf->Ln(24);

  assinaturas($pdf,$classinatura,'LRF');

  $pdf->Output();

}  // end !include

?>