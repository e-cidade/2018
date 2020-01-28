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
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_empage_classe.php"));
require_once(modification("classes/db_empagemovconta_classe.php"));

parse_str($_SERVER["QUERY_STRING"]);

$oGet         = db_utils::postMemory($_GET);

$iInstituicao = db_getsession('DB_instit');

$clempage         = new cl_empage;
$clempagemovconta = new cl_empagemovconta;

$aCodigosAgendas = array();

$lErro					 = false;


if ($oGet->iCodigoAgenda == '' && $oGet->dPeriodoInicial == '' && $oGet->dPeriodoFinal == '') {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum filtro informado para geração do relatório.");
}

$sCodigosAgendas = null;
$sWhere          = null;

if ($oGet->iCodigoAgenda != '') {

	$sCodigosAgendas = $oGet->iCodigoAgenda;

} else if ($oGet->dPeriodoInicial != '' || $oGet->dPeriodoFinal != '') {

	$sWhere = " e80_instit = {$iInstituicao} and ";

	if ($oGet->dPeriodoInicial != '' && $oGet->dPeriodoFinal != '') {

		$sWhere .= "e80_data between '{$oGet->dPeriodoInicial}' and '{$oGet->dPeriodoFinal}'";

	} else if ($oGet->dPeriodoInicial != '') {

		$sWhere .= "e80_data >= '{$oGet->dPeriodoInicial}'";

	} else if ($oGet->dPeriodoFinal != '') {

		$sWhere .= "e80_data <= '{$oGet->dPeriodoFinal}'";

	}

}

$sSqlAgendas = $clempage->sql_query_file($sCodigosAgendas, "*", "e80_codage", $sWhere);

$rsAgendas   = $clempage->sql_record($sSqlAgendas);

if ($clempage->numrows > 0) {

  for ($iIndice = 0; $iIndice < $clempage->numrows; $iIndice++) {

    $oAgenda           = db_utils::fieldsMemory($rsAgendas, $iIndice);
    $aCodigosAgendas[] = $oAgenda->e80_codage;
  }

  $sCodigosAgendas = implode($aCodigosAgendas, ",");

} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado no períodio informado.");
}

$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e80_data");
$clrotulo->label("e82_codord");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("e81_valor");

$clrotulo->label("e81_codmov");
$clrotulo->label("e81_numemp");
$clrotulo->label("pc63_banco");
$clrotulo->label("pc63_agencia");
$clrotulo->label("pc63_conta");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("o58_codigo");

$clrotulo->label('k17_codigo');
$clrotulo->label('k17_data');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_dtaut');
$clrotulo->label('k17_autent');
$clrotulo->label('c60_descr');


if($oGet->tipo == 'c'){
	$head5 = 'DADOS DA CONTA';
	$ordem = 'order by e83_codtipo,z01_nome';
}else{
	$head5 = 'DADOS DO EMPENHO';
	$ordem = 'order by z01_nome';
}

if($oGet->form=="t"){
	$head6 = 'IMPRESSÃO POR CONTA PAGADORA';
}else{
	$head6 = 'IMPRESSÃO POR RECURSO';
	$ordem = 'order by o15_codigo,z01_nome';
}


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);


foreach ($aCodigosAgendas as $iCodigoAgenda) {

  $sCamposAgenda  = " e85_codtipo, e83_descr, e60_codemp, e82_codord, e81_valor, e81_codmov, e81_numemp, e81_valor,    ";
  $sCamposAgenda .= " e82_codord, e60_codemp, e60_vlranu, e60_vlrliq, e60_vlremp, e60_vlrpag, e81_valor, e81_valor,    ";
  $sCamposAgenda .= " e60_vlrpag, e60_anousu, e60_coddot, e83_codtipo,                                                 ";
  $sCamposAgenda .= " case when a.z01_numcgm is not null then a.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,      ";
  $sCamposAgenda .= " case when trim(a.z01_nome) is not null then a.z01_nome else cgm.z01_nome end as z01_nome,        ";
  $sCamposAgenda .= " case when trim(a.z01_cgccpf) is not null then a.z01_cgccpf else cgm.z01_cgccpf end as z01_cgccpf ";

  $sWhere         = " e80_instit = " . db_getsession("DB_instit") . " and e80_codage = {$iCodigoAgenda}                ";

  $sSqlEmpAge     = $clempage->sql_query_rel(null, $sCamposAgenda, "", $sWhere . " and e53_vlranu < e53_valor " );

  //////////////////////////////////////////////////////////////////////////////////////
  /* início do select que busca agenda or ordens                                      */
  //////////////////////////////////////////////////////////////////////////////////////

  $sSqlAgendaOrdens  = "select x.*,                                                                                                  ";
  $sSqlAgendaOrdens .= "       orctiporec.*,                                                                                         ";
  $sSqlAgendaOrdens .= "       pcfornecon.*,                                                                                         ";
  $sSqlAgendaOrdens .= "       o58_codigo,                                                                                           ";
  $sSqlAgendaOrdens .= "       pc63_contabanco,                                                                                      ";
  $sSqlAgendaOrdens .= "       pc63_dataconf,                                                                                        ";
  $sSqlAgendaOrdens .= "       case when pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' then z01_cgccpf else pc63_cnpjcpf end as cnpj ";
  $sSqlAgendaOrdens .= "  from ($sSqlEmpAge) as x                                                                                    ";
  $sSqlAgendaOrdens .= "       left  join empagemovconta on empagemovconta.e98_codmov     = x.e81_codmov                             ";
  $sSqlAgendaOrdens .= "       left  join pcfornecon     on x.z01_numcgm                  = pc63_numcgm                              ";
  $sSqlAgendaOrdens .= "                                and empagemovconta.e98_contabanco = pcfornecon.pc63_contabanco               ";
  $sSqlAgendaOrdens .= "       inner join orcdotacao     on e60_anousu                    = o58_anousu                               ";
  $sSqlAgendaOrdens .= "                                and e60_coddot                    = o58_coddot                               ";
  $sSqlAgendaOrdens .= "       inner join orctiporec     on  orctiporec.o15_codigo        = orcdotacao.o58_codigo                    ";
  $sSqlAgendaOrdens .= "       $ordem                                                                                                ";

  $rsAgendaOrdens       = $clempage->sql_record($sSqlAgendaOrdens);
  $iNumrowsAgendaOrdens = $clempage->numrows;

  $sSqlAgendaSlips  = " select slip.k17_codigo,                                                                        ";
  $sSqlAgendaSlips .= "        k17_data,                                                                               ";
  $sSqlAgendaSlips .= "        k17_debito,                                                                             ";
	$sSqlAgendaSlips .= "        c1.c60_descr as debito_descr,                                                           ";
	$sSqlAgendaSlips .= "	       k17_credito,                                                                            ";
	$sSqlAgendaSlips .= "		     c2.c60_descr as credito_descr,                                                          ";
	$sSqlAgendaSlips .= "		     k17_valor,                                                                              ";
	$sSqlAgendaSlips .= "		     k17_hist,                                                                               ";
	$sSqlAgendaSlips .= "		     k17_texto,                                                                              ";
	$sSqlAgendaSlips .= "		     k17_dtaut,                                                                              ";
	$sSqlAgendaSlips .= "		     k17_autent,                                                                             ";
	$sSqlAgendaSlips .= "		     z01_numcgm,                                                                             ";
	$sSqlAgendaSlips .= "		     z01_nome                                                                                ";
  $sSqlAgendaSlips .= "   from slip                                                                                    ";
  $sSqlAgendaSlips .= "        inner join empageslip       on empageslip.e89_codigo = slip.k17_codigo                  ";
  $sSqlAgendaSlips .= "        inner join empagemov        on empagemov.e81_codmov  = empageslip.e89_codmov            ";
	$sSqlAgendaSlips .= "        inner join conplanoreduz r1 on r1.c61_reduz          = k17_debito                       ";
	$sSqlAgendaSlips .= "                                   and r1.c61_anousu         = " . db_getsession("DB_anousu")." ";
	$sSqlAgendaSlips .= "                                   and c61_instit            = " . db_getsession("DB_instit") ."";
	$sSqlAgendaSlips .= "        inner join conplano c1      on c1.c60_codcon         = r1.c61_codcon                    ";
	$sSqlAgendaSlips .= "                                   and c1.c60_anousu         = r1.c61_anousu                    ";
	$sSqlAgendaSlips .= "        left  join conplanoreduz r2 on r2.c61_reduz          = k17_credito                      ";
	$sSqlAgendaSlips .= "                                   and r2.c61_anousu         = ".db_getsession("DB_anousu")."   ";
	$sSqlAgendaSlips .= "	       left  join conplano c2      on c2.c60_codcon         = r2.c61_codcon                    ";
	$sSqlAgendaSlips .= "                                   and c2.c60_anousu         = r2.c61_anousu                    ";
	$sSqlAgendaSlips .= "	       left  join slipnum          on slipnum.k17_codigo    = slip.k17_codigo                  ";
	$sSqlAgendaSlips .= "	       left  join cgm              on cgm.z01_numcgm        = slipnum.k17_numcgm               ";
  $sSqlAgendaSlips .= "  where e81_codage  =  {$iCodigoAgenda}                                                         ";
	$sSqlAgendaSlips .= "	 order by slip.k17_codigo                                                                      ";

	$rsAgendaSlips       = db_query($sSqlAgendaSlips);
	$iNumrowsAgendaSlips = pg_num_rows($rsAgendaSlips);


	//===================================================================================================================
	if ($iNumrowsAgendaOrdens == 0 && $iNumrowsAgendaSlips == 0) {
	  continue;
	}

	$head3 = "AGENDA: ".$iCodigoAgenda;

	if ($oGet->dPeriodoInicial != '' || $oGet->dPeriodoFinal != '') {
    $head8 = "DATA  : ".$oGet->dPeriodoInicial.' até '.$oGet->dPeriodoFinal;

	}

	$head9 = "** - Contas já usadas em arquivos ou conferidas";

	if($oGet->tipo == 'e'){
	  $troca = 1;
	  $alt = 4;
	  $total = 0;

	  $pagina = 1;
	  for($i=0;$i<$iNumrowsAgendaOrdens;$i++){

	    db_fieldsmemory($rsAgendaOrdens,$i,true);

	    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
	      $pagina = 0;
	      $pdf->addpage("L");
	      $pdf->setfont('arial','b',7);

	      $pdf->cell(10,$alt,$RLe82_codord,1,0,"C",1);
	      $pdf->cell(15,$alt,"Empenho",1,0,"C",1);
	      $pdf->cell(15,$alt,'Recurso',1,0,"C",1);
	      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
	      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
	      $pdf->cell(20,$alt,$RLe60_vlremp,1,0,"C",1);
	      $pdf->cell(20,$alt,$RLe60_vlrliq,1,0,"C",1);
	      $pdf->cell(20,$alt,$RLe60_vlrpag,1,0,"C",1);
	      $pdf->cell(20,$alt,$RLe81_valor,1,0,"C",1);
	      $pdf->cell(30,$alt,"Saldo a pagar",1,1,"C",1);
	      $pdf->setfont('arial','',7);
	    }
	    $pdf->cell(10,$alt,$e82_codord,1,0,"C",0);
	    $pdf->cell(15,$alt,$e60_codemp,1,0,"C",0);
	    $pdf->cell(15,$alt,db_formatar($o58_codigo,'recurso'),1,0,"C",0);
	    $pdf->cell(15,$alt,$z01_numcgm,1,0,"L",0);
	    $pdf->cell(70,$alt,$z01_nome,1,0,"L",0);
	    $pdf->cell(20,$alt,db_formatar($e60_vlremp-$e60_vlranu,"f"),1,0,"R",0);
	    $pdf->cell(20,$alt,db_formatar($e60_vlrliq,"f"),1,0,"R",0);
	    $pdf->cell(20,$alt,db_formatar($e60_vlrpag,"f"),1,0,"R",0);
	    $pdf->setfont('arial','b',7);
	    $pdf->cell(20,$alt,db_formatar($e81_valor,"f"),1,0,"R",0);
	    $pdf->setfont('arial','',7);
	    $pdf->cell(30,$alt,db_formatar($e81_valor-$e60_vlrpag,"f"),1,1,"R",0);
	    $total += $e81_valor;
	  }
	  $pdf->cell(185,$alt,"T O T A L",1,0,"L",0);
	  $pdf->setfont('arial','b',7);
	  $pdf->cell(20,$alt,db_formatar($total,"f"),1,0,"L",0);
	  $pdf->cell(30,$alt,'',1,1,"L",0);
	}else{
	  $total = 0;
	  $alt = 4;

	  $xvalor    = 0;
	  $xvaltotal = 0;
	  $xbanco    = '';
	  $ant_codgera = "";
	  $total_geral =0;
	  $pagina =1;
	  $pdf->addpage("L");
	  if($iNumrowsAgendaOrdens>0){
	    for($i=0;$i<$iNumrowsAgendaOrdens;$i++){
	      db_fieldsmemory($rsAgendaOrdens,$i);
	      $pdf->setfont('arial','b',8);
	      if($pdf->gety() > $pdf->h - 30 || $pagina ==1){
	        $pagina = 0;
	        if($pdf->gety() > $pdf->h - 30){
	          $pdf->cell(260,0.1,"","T",1,"L",0);
	          $pdf->addpage("L");
	        }
	        $pdf->cell(20,$alt,"ARQUIVO",1,0,"C",1);
	        $pdf->cell(250,$alt,"DESCRIÇÃO",1,1,"C",1);
	        $pdf->cell(20,$alt,'Nro. Empenho'  ,1,0,"C",0);
	        $pdf->cell(20,$alt,$RLe82_codord  ,1,0,"C",0);
	        $pdf->cell(15,$alt,$RLz01_numcgm  ,1,0,"C",0);
	        $pdf->cell(65,$alt,$RLz01_nome    ,1,0,"C",0);
	        $pdf->cell(30,$alt,$RLz01_cgccpf  ,1,0,"C",0);
	        $pdf->cell(20,$alt,$RLe81_valor   ,1,0,"C",0);
	        $pdf->cell(15,$alt,$RLpc63_banco  ,1,0,"C",0);
	        $pdf->cell(15,$alt,$RLpc63_agencia,1,0,"C",0);
	        $pdf->cell(30,$alt,$RLpc63_conta  ,1,0,"C",0);
	        $pdf->cell(20,$alt,$RLe81_codmov  ,1,0,"C",0);
	        $pdf->cell(20,$alt,$RLe81_numemp  ,1,1,"C",0);
	      }
	      if($oGet->form=="t"){
	        $testa = $e85_codtipo.'-'.$e83_descr;
	      }else{
	        $testa = $o15_codigo.'-'.$o15_descr;
	      }
	      if($ant_codgera!=$testa){
	        if($i !=0){
	          $pdf->cell(150,$alt,'Total do Banco',1,0,"C",1);
	          $pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
	          $pdf->cell(100,$alt,'',1,1,"C",1);
	          $pdf->ln(4);
	        }
	        $pdf->ln(4);
	        if($oGet->form=="t"){
	          $pdf->cell(20,$alt,$e85_codtipo,1,0,"C",1);
	          $pdf->cell(250,$alt,$e83_descr,1,1,"L",1);
	          $ant_codgera=$e85_codtipo.'-'.$e83_descr;
	        }else{
	          $pdf->cell(20,$alt,$o15_codigo,1,0,"C",1);
	          $pdf->cell(250,$alt,$o15_descr,1,1,"L",1);
	          $ant_codgera=$o15_codigo.'-'.$o15_descr;
	        }
	        $xtotal = 0;
	      }
	      $pdf->setfont('arial','',7);
	      $pdf->cell(20,$alt,$e60_codemp  ,1,0,"C",0);
	      $pdf->cell(20,$alt,$e82_codord  ,1,0,"C",0);
	      $pdf->cell(15,$alt,$z01_numcgm  ,1,0,"C",0);

	      $asteriscos = "";

	      if ($pc63_contabanco != '') {
	        $result_asteriscos = $clempagemovconta->sql_record($clempagemovconta->sql_query_conta(null,"pc63_contabanco","","pc63_contabanco=$pc63_contabanco and e90_codmov is not null"));
	        if($clempagemovconta->numrows > 0 || $pc63_dataconf!=""){
	          $asteriscos = "** ";
	        }
	      }

	      $pdf->cell(65,$alt,$asteriscos.$z01_nome,1,0,"L",0);
	      $pdf->cell(30,$alt,$cnpj        ,1,0,"R",0);
	      $pdf->cell(20,$alt,db_formatar($e81_valor,'f'),1,0,"R",0);
	      $pdf->cell(15,$alt,$pc63_banco  ,1,0,"C",0);
	      $pdf->cell(15,$alt,$pc63_agencia.($pc63_agencia_dig!=''?'-'.$pc63_agencia_dig:''),1,0,"R",0);
	      $pdf->cell(30,$alt,$pc63_conta  ,1,0,"R",0);
	      $pdf->cell(20,$alt,$e81_codmov  ,1,0,"C",0);
	      $pdf->cell(20,$alt,$e81_numemp  ,1,1,"C",0);
	      $total++;
	      $xtotal      += $e81_valor;
	      $xvaltotal   += $e81_valor;
	      //  $ant_codgera = $e87_codgera;
	    }
	    $pdf->setfont('arial','b',8);
	    $pdf->cell(150,$alt,'Total do Banco',1,0,"C",1);
	    $pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
	    $pdf->cell(100,$alt,'',1,1,"C",1);

	    $pdf->cell(150,$alt,'Total Geral',1,0,"C",1);
	    $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
	    $pdf->cell(100,$alt,'',1,1,"C",1);
	    //$pdf->cell(260,$alt,"TOTAL DE REGISTROS  : ".$total,"T",1,"L",0);
	  }

	  //separa ordem e slip. Linha em branco.
	  $pdf->cell(268,5,'',0,1,"C",0);

	  if($iNumrowsAgendaSlips>0){
	    $total = 0;
	    $pdf->setfillcolor(235);
	    $pdf->setfont('arial','b',8);
	    $troca = 1;
	    $prenc = 0;
	    $alt = 4;
	    $total = 0;
	    $xtotal = 0;
	    for($x = 0; $x < $iNumrowsAgendaSlips;$x++){
	      db_fieldsmemory($rsAgendaSlips,$x);
	      if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	        if ($pdf->gety() > $pdf->h - 30){
	          $pdf->addpage("L");
	        }
	        $pdf->setfont('arial','b',8);
	        $pdf->cell(30,$alt,$RLk17_codigo,1,0,"C",1);
	        $pdf->cell(30,$alt,$RLk17_data,1,0,"C",1);
	        $pdf->cell(40,$alt,$RLk17_valor,1,0,"C",1);
	        $pdf->cell(30,$alt,'Data Aut.',1,0,"C",1);
	        $pdf->cell(148,$alt,$RLk17_texto,1,1,"C",1);

	        $pdf->cell(15,$alt,"C. Débito",1,0,"C",1);
	        $pdf->cell(90,$alt,$RLc60_descr,1,0,"C",1);
	        $pdf->cell(15,$alt,"C. Crédito",1,0,"C",1);
	        $pdf->cell(90,$alt,$RLc60_descr,1,0,"C",1);
	        $pdf->cell(68,$alt,$RLz01_nome,1,1,"C",1);

	        $troca = 0;
	        $prenc = 1;
	      }
	      if ($prenc == 0){
	        $prenc = 1;
	      }else $prenc = 0;
	      $pdf->setfont('arial','',7);
	      $pdf->cell(30,$alt,$k17_codigo,0,0,"C",$prenc);
	      $pdf->cell(30,$alt,db_formatar($k17_data,'d'),0,0,"C",$prenc);
	      $pdf->cell(40,$alt,db_formatar($k17_valor,'f'),0,0,"R",$prenc);
	      $pdf->cell(30,$alt,db_formatar($k17_dtaut,'d'),0,0,"C",$prenc);
	      $pdf->multicell(148,$alt,$k17_texto,0,"L",$prenc);


	      $pdf->cell(15,$alt,$k17_debito,0,0,"C",$prenc);
	      $pdf->cell(90,$alt,$debito_descr,0,0,"L",$prenc);
	      $pdf->cell(15,$alt,$k17_credito,0,0,"C",$prenc);
	      $pdf->cell(90,$alt,$credito_descr,0,0,"L",$prenc);
	      $pdf->cell(68,$alt,substr($z01_nome,0,35),0,1,"L",$prenc);


	      //     if ($prenc == 0){
	      //        $prenc = 1;
	      //       }else $prenc = 0;
	      $total++;
	      $xtotal      += $k17_valor;

	    }

	    $pdf->setfont('arial','b',8);
	    $pdf->cell(80,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
	    $pdf->cell(20,$alt,db_formatar($xtotal,'f'),"T",0,"R",0);
	    $pdf->cell(180,$alt,"","T",0,"R",0);
	  }
	}


	//===================================================================================================================

}


$pdf->Output();
?>