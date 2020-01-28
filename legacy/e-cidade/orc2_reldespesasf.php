<?php
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

require_once(modification("libs/db_liborcamento.php"));

$tipo_mesini = 1;
$tipo_mesfim = 1;

//$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
//$tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
//$tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento
// 6 = recurso
$tipo_agrupa = 3;
$tipo_nivel = 6;

$qorgao = 0;
$qunidade = 0;

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));

db_postmemory($HTTP_POST_VARS);

$anousu  = db_getsession("DB_anousu");

$dataini = $data_ini_ano.'-'.$data_ini_mes.'-'.$data_ini_dia;
$datafin = $data_fin_ano.'-'.$data_fin_mes.'-'.$data_fin_dia;

$data_ini_exibida = $data_ini_dia.'/'.$data_ini_mes.'/'.$data_ini_ano;
$data_fin_exibida = $data_fin_dia.'/'.$data_fin_mes.'/'.$data_fin_ano;

//---------------------------------------------------------------
$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

if (!isset($instits) && trim(@$instits)==""){
     $instits = "(".db_getsession("DB_instit").")";
}

//@ recupera as informações fornecidas para gerar os dados
//---------------------------------------------------------------

$head1 = "DEMONSTRATIVO DA DESPESA - PREVISÃO FOLHA DE PAGAMENTO";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$resultinst = db_query("select codigo,nomeinst from db_config where codigo in $instits");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "Período : ".$data_ini_exibida."   à  ".$data_fin_exibida;

$sele_work = $clselorcdotacao->getDados()." and w.o58_instit in $instits ";

if (substr($nivel,1,1) == 'A'){
  $completo = false;
  $nivela = substr($nivel,0,1);
  if($nivela=="9"){
    $completo = true;
    $nivela = "8";
  }

  $result = db_dotacaosaldo($nivela,1,2,true,$sele_work,$anousu,$dataini,$datafin);

  db_query("commit");

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $troca         = 1;
  $alt           = 4;
  $qualou        = 0;
  $totproj       = 0;
  $totativ       = 0;
  $pagina        = 1;
  $xorgao        = 0;
  $xunidade      = 0;
  $xfuncao       = 0;
  $xsubfuncao    = 0;
  $xprograma     = 0;
  $xprojativ     = 0;
  $xelemento     = 0;

  $totorgaoini   = 0;
  $totorgaosup   = 0;
  $totorgaoesp   = 0;
  $totorgaored   = 0;
  $totorgaoemp   = 0;
  $totorgaoliq   = 0;
  $totorgaopag   = 0;

  $totorgliquidado      = 0;
  $totorgliqmetade      = 0;
  $totorgliqposterior   = 0;
  $totorgliqferias      = 0;
  $totorgtotprev        = 0;
  $totorgorgaoatual     = 0;
  $totorgdiferenca      = 0;

  $totorgaoanter = 0;
  $totorgaoreser = 0;
  $totorgaoatual = 0;

  $totunidaini   = 0;
  $totunidasup   = 0;
  $totunidaesp   = 0;
  $totunidared   = 0;
  $totunidaemp   = 0;
  $totunidaliq   = 0;
  $totunidapag   = 0;

  $totunidaanter = 0;
  $totunidareser = 0;
  $totunidaatual = 0;


  $pagina        = 1;

  for($i=0;$i<pg_numrows($result);$i++){

    db_fieldsmemory($result,$i);

    if($xorgao.$xunidade != $o58_orgao.$o58_unidade && $quebra_unidade == 'S' && $pagina != 1 && $totunidaanter != 0){
      $pdf->setfont('arial','b',7);
      $pagina = 1;
      $pdf->ln(3);

      if($completo==false){
	$pdf->setfont('arial','b',7);
	$pdf->ln(3);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(85,$alt,'TOTAL DA UNIDADE ',0,0,"L",0,'.');
	$pdf->cell(25,$alt,db_formatar($totunidaini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaanter,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidareser,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
      }else{
	$pdf->cell(85,$alt,'TOTAL DA UNIDADE',0,1,"L",0,'.');
	$pdf->cell(25,$alt,db_formatar($totunidaini,'f'),1,0,"R",0);

	$pdf->cell(25,$alt,db_formatar($totunidasup,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaesp,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidared,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaini+$totunidasup+$totunidaesp-$totunidared,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaemp,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaliq,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidapag,'f'),1,0,"R",0);

	$pdf->cell(25,$alt,db_formatar($totunidaanter,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidareser,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totunidaatual,'f'),1,1,"R",0);
      }
      $pdf->setfont('arial','',7);
      $totunidaini   = 0;
      $totunidaanter = 0;
      $totunidareser = 0;
      $totunidaatual = 0;
      $totunidasup   = 0;
      $totunidaesp   = 0;
      $totunidared   = 0;
      $totunidaemp   = 0;
      $totunidaliq   = 0;
      $totunidapag   = 0;

    }

    if($xorgao != $o58_orgao && $quebra_orgao =='S' ){
      $pdf->setfont('arial','b',7);
      $pagina = 1;
      $pdf->ln(3);

      if($completo==false){
	$pdf->cell(90,$alt,'',0,0,"L",0,'.');
	$pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');

	$pdf->cell(25,$alt,db_formatar($totorgaoini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);
      }else{
	$pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,1,"L",0,'.');
	$pdf->cell(25,$alt,db_formatar($totorgaoini,'f'),1,0,"R",0);

	$pdf->cell(25,$alt,db_formatar($totorgaosup,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoesp,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaored,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoini+$totorgaosup+$totorgaoesp-$totorgaored,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoemp,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoliq,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaopag,'f'),1,0,"R",0);

	$pdf->cell(25,$alt,db_formatar($totorgaoanter,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoreser,'f'),1,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($totorgaoatual,'f'),1,1,"R",0);
      }


      $pdf->setfont('arial','',7);

      $totorgaoini   = 0;
      $totorgaoanter = 0;
      $totorgaoreser = 0;
      $totorgaoatual = 0;
      $totorgaosup   = 0;
      $totorgaoesp   = 0;
      $totorgaored   = 0;
      $totorgaoemp   = 0;
      $totorgaoliq   = 0;
      $totorgaopag   = 0;

      $totorgliquidado      = 0;
      $totorgliqmetade      = 0;
      $totorgliqposterior   = 0;
      $totorgliqferias      = 0;
      $totorgtotprev        = 0;
      $totorgorgaoatual     = 0;
      $totorgdiferenca      = 0;

    }
    if($pdf->gety()>$pdf->h-30 || $pagina == 1){
      $pagina = 0;
      $qualou = $o58_orgao.$o58_unidade;
      $pdf->addpage("L");
      $pdf->setfont('arial','b',7);
      $pdf->ln(2);

      $pdf->cell(100,$alt,"DOTAÇÃO",0,0,"L",0);
      $pdf->cell(55,$alt,"RECURSO",0,0,"L",0);
      $pdf->cell(15,$alt,"REDUZ",0,1,"R",0);
      if($completo==false){
        $pdf->cell(25,$alt,"SALDO INICIAL",0,0,"C",0);
        $pdf->cell(25,$alt,"SALDO ANTERIOR",0,0,"C",0);
        $pdf->cell(25,$alt,"RESERVADO",0,0,"R",0);
        $pdf->cell(25,$alt,"SALDO ATUAL",0,1,"R",0);
      }else{

        $pdf->cell(30,$alt,"",0,0,"R",0);
        $pdf->cell(100,$alt,"----------------------------------------------P R E V I S Ã O-----------------------------------------------",0,1,"L",0);
        $pdf->cell(30,$alt,"LIQ. ".strtoupper(db_mes($data_fin_mes)),0,0,"L",0);
        $pdf->cell(25,$alt,"PREVISAO ".strtoupper(db_mes($data_fin_mes+1))."-DEZ",0,0,"R",0);
        $pdf->cell(20,$alt,"13 SALARIO",0,0,"R",0);
        $pdf->cell(23,$alt,"FERIAS",0,"R",0);
        $pdf->cell(27,$alt,"TOTAL",0,"R",0);
        $pdf->cell(27,$alt,"SALDO DOTAÇÃO",0,"R",0);
        $pdf->cell(25,$alt,"DIFERENÇA",0,1,"R",0);
      }
      $pdf->cell(0,$alt,'',"T",1,"C",0);

      $pdf->setfont('arial','',7);
    }
    if($xorgao != $o58_orgao && $o58_orgao != 0){
      $xorgao = $o58_orgao;
      if($nivela == 1){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }else{
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o40_descr,0,1,"L",0);
	$xunidade = 0;
      }
    }
    if("$o58_orgao.$o58_unidade" != "$xorgao.$xunidade" && $o58_unidade != 0){
      $xunidade = "$o58_unidade";
      if($nivela == 2){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o41_descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }else{
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o41_descr,0,1,"L",0);
      }
    }

    if("$o58_orgao.$o58_unidade.$o58_funcao" != "$xfuncao" && $o58_funcao != 0 ){
      $xfuncao = "$o58_orgao.$o58_unidade.$o58_funcao";
      $descr = $o52_descr;
      if($nivela == 3){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }else{
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,1,"L",0);
      }
    }
    if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao" != "$xsubfuncao" && $o58_subfuncao != 0){
      $xsubfuncao = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao";
      $descr = $o53_descr;
      if($nivela == 4){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }else{
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,1,"L",0);
      }
    }
    if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa" != "$xprograma" && $o58_programa != 0){
      $xprograma = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa";
      $descr = $o54_descr;
      if($nivela == 5){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }else{
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,1,"L",0);
      }
    }
    if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ" != "$xprojativ" && $o58_projativ != 0){
      $xprojativ = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ";
      $descr = $o55_descr;
      if($nivela == 6){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);

	if($nivela != 7){
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
	}
      }else{
	$pdf->setfont('arial','b',7);
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	if($completo==false){
  	  $pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	  $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	  $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	  $pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	}else{
	  $pdf->cell(25,$alt,'',0,1,"R",0);
	}
	$pdf->setfont('arial','',7);

      }
    }
    if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento" != "$xelemento" && $o58_elemento  != 0){
      $xelemento = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento";
      $descr = $o56_descr;
      if($nivela == 7){
	$pdf->cell(25,$alt,db_formatar($o58_elemento,'elemento'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
    }
    if($o58_codigo > 0){
      $descr = $o56_descr;
      $pdf->cell(20,$alt,$o58_elemento,0,0,"L",0);
      $pdf->cell(80,$alt,$descr,0,0,"L",0);
      $pdf->cell(10,$alt,db_formatar($o58_codigo,'s','0',4,'e'),0,0,"L",0);
      $pdf->cell(50,$alt,$o15_descr,0,0,"L",0);
      $pdf->cell(15,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),0,0,"R",0);

      if($completo==false){
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);

	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
        $totorgaoini   += $dot_ini;
        $totorgaoanter += $atual;
        $totorgaoreser += $reservado;
        $totorgaoatual += $atual_menos_reservado;

        $totunidaini   += $dot_ini;
        $totunidaanter += $atual;
        $totunidareser += $reservado;
        $totunidaatual += $atual_menos_reservado;

      }else{
        $pdf->cell(25,$alt,'',0,1,"R",0);

        $liqmetade    = ($liquidado/2);
        $liqposterior = ($liquidado*(12-($data_fin_mes)));
        $liqferias    = (($liquidado * 33.33)/100);
      	$totprev      = ($liqposterior+$liqmetade+$liqferias);
      	$diferenca    = ($atual_menos_reservado - $totprev);
        $pdf->cell(25,$alt,db_formatar($liquidado,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($liqposterior,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($liqmetade,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($liqferias,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totprev,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($diferenca,'f'),1,1,"R",0);

        $totorgliquidado      += $liquidado;
        $totorgliqmetade      += $liqmetade;
        $totorgliqposterior   += $liqposterior;
        $totorgliqferias      += $liqferias;
        $totorgtotprev        += $totprev;
        $totorgorgaoatual     += $atual_menos_reservado;
        $totorgdiferenca      += $diferenca;

        $totuniliquidado      += $liquidado;
        $totuniliqmetade      += $liqmetade;
        $totuniliqposterior   += $liqposterior;
        $totuniliqferias      += $liqferias;
        $totunitotprev        += $totprev;
        $totuniorgaoatual     += $atual_menos_reservado;
        $totunidiferenca      += $diferenca;

      }

      if($lista_subeleme=='S'){

	$sql = "select *
		from orcelemento
		where substr(o56_elemento,1,7) = '".str_replace('.','',substr($o58_elemento,0,7))."' and
		      substr(o56_elemento,8,5) != '00000' and o56_anousu = ".db_getsession("DB_anousu")." and
		      o56_orcado is true";
	$res = db_query($sql);
	for($ne=0;$ne<pg_numrows($res);$ne++){
	  db_fieldsmemory($res,$ne);
	  $pdf->cell(20,$alt,$o56_elemento,0,0,"L",0);
	  $pdf->cell(80,$alt,$o56_descr,0,0,"L",0);
	  $pdf->cell(125,$alt,$o56_finali,0,1,"L",0);
	}

      }

    }
  }
  if($quebra_unidade == 'S'){
    if($completo==false){
      $pdf->setfont('arial','b',7);
      $pdf->ln(3);
      $pdf->cell(90,$alt,'',0,0,"L",0);
      $pdf->cell(85,$alt,'TOTAL DA UNIDADE ',0,0,"L",0,'.');
      $pdf->cell(25,$alt,db_formatar($totunidaini,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totunidaanter,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totunidareser,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
    }else{
      $pdf->cell(85,$alt,'TOTAL DA UNIDADE',0,1,"L",0,'.');
        $pdf->cell(25,$alt,db_formatar($totuniliquidado,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totuniliqposterior,'f'),1,0,"R",0);
        //$pdf->cell(25,$alt,db_formatar($totuniliquidado,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totuniliqmetade,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totuniliqferias,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totunitotprev,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totuniorgaoatual,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totunidiferenca,'f'),1,1,"R",0);
    }
  }
  $pdf->ln(3);
  if($completo==false){
    $pdf->cell(90,$alt,'',0,0,"L",0,'.');
    $pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');

    $pdf->cell(25,$alt,db_formatar($totorgaoini,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
    $pdf->cell(25,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);
  }else{
    $pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,1,"L",0,'.');
        $pdf->cell(25,$alt,db_formatar($totorgliquidado,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgliqposterior,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgliqmetade,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgliqferias,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgtotprev,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgorgaoatual,'f'),1,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($totorgdiferenca,'f'),1,1,"R",0);
  }

}else{

  $nivela = substr($nivel,0,1);

  $anousu  = db_getsession("DB_anousu");
  $dataini = db_getsession("DB_anousu")."-01-01";
  $datafin = date("Y-m-d",db_getsession("DB_datausu"));
  $result = db_dotacaosaldo($nivela,3,2,true,$sele_work,$anousu,$dataini,$datafin);

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $troca         = 1;
  $alt           = 4;
  $qualou        = 0;
  $totproj       = 0;
  $totativ       = 0;
  $pagina        = 1;
  $xorgao        = 0;
  $xunidade      = 0;
  $xfuncao       = 0;
  $xsubfuncao    = 0;
  $xprograma     = 0;
  $xprojativ     = 0;
  $xelemento     = 0;
  $totorgaoanter = 0;
  $totorgaoreser = 0;
  $totorgaoini   = 0;
  $totorgaoatual = 0;
  $totunidaanter = 0;
  $totunidareser = 0;
  $totunidaini   = 0;
  $totunidaatual = 0;
  $pagina = 1;

  for($k=0;$k<pg_numrows($result);$k++){

    db_fieldsmemory($result,$k);
    if($pdf->gety()>$pdf->h-30 || $pagina == 1){
      $pagina = 0;
      $qualou = $o58_orgao.$o58_unidade;
      $pdf->addpage("L");
      $pdf->setfont('arial','b',7);
      $pdf->ln(2);
      $pdf->cell(25,$alt,"DOTAÇÃO",0,0,"L",0);
      if($nivela == 1)
	$pdf->cell(100,$alt,"ÓRGÃO",0,0,"L",0);
      if($nivela == 2)
	$pdf->cell(100,$alt,"UNIDADE",0,0,"L",0);
      if($nivela == 3)
	$pdf->cell(100,$alt,"FUNÇÃO",0,0,"L",0);
      if($nivela == 4)
	$pdf->cell(100,$alt,"SUBFUNÇÃO",0,0,"L",0);
      if($nivela == 5)
	$pdf->cell(100,$alt,"PROGRAMA",0,0,"L",0);
      if($nivela == 6)
	$pdf->cell(100,$alt,"PROJ/ATIV",0,0,"L",0);
      if($nivela == 7)
	$pdf->cell(100,$alt,"ELEMENTO",0,0,"L",0);
      if($nivela == 8)
	$pdf->cell(100,$alt,"RECURSO",0,0,"L",0);
      $pdf->cell(50,$alt,"",0,0,"R",0);
      $pdf->cell(25,$alt,"SALDO INICIAL",0,0,"C",0);
      $pdf->cell(25,$alt,"SALDO ANTERIOR",0,0,"C",0);
      $pdf->cell(25,$alt,"RESERVADO",0,0,"R",0);
      $pdf->cell(25,$alt,"SALDO ATUAL",0,1,"R",0);
      $pdf->cell(0,$alt,'',"T",1,"C",0);
      $pdf->setfont('arial','',7);
    }
      if($nivela == 1){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      if($nivela == 2){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$o41_descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      $descr = $o52_descr;
      if($nivela == 3){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      $descr = $o53_descr;
      if($nivela == 4){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      $descr = $o54_descr;
      if($nivela == 5){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      $descr = $o55_descr;
      if($nivela == 6){
	$pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      $descr = $o56_descr;
      if($nivela == 7){
	$pdf->cell(25,$alt,$o58_elemento,0,0,"L",0);
	$pdf->cell(60,$alt,$descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }
      if($nivela == 8){
	$pdf->cell(25,$alt,$o58_codigo,0,0,"L",0);
	$pdf->cell(60,$alt,$o15_descr,0,0,"L",0);
	$pdf->cell(90,$alt,'',0,0,"L",0);
	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
	$pdf->cell(25,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
	$totorgaoini   += $dot_ini;
	$totorgaoanter += $atual;
	$totorgaoreser += $reservado;
	$totorgaoatual += $atual_menos_reservado;
	$totunidaini   += $dot_ini;
	$totunidaanter += $atual;
	$totunidareser += $reservado;
	$totunidaatual += $atual_menos_reservado;
      }

  }

  $pdf->ln(3);
  $pdf->cell(90,$alt,'',0,0,"L",0);
  $pdf->cell(85,$alt,'TOTAL ',0,0,"L",0,'.');
  $pdf->cell(25,$alt,db_formatar($totorgaoini,  'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);

}

db_query("commit");

$pdf->Output();