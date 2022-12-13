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
include(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
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
$tipo_nivel  = 6;

$qorgao   = 0;
$qunidade = 0;

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
db_postmemory($HTTP_POST_VARS);

$anousu  = db_getsession("DB_anousu");
$dataini = "{$data_ini_ano}-{$data_ini_mes}-{$data_ini_dia}";
$datafin = "{$data_fin_ano}-{$data_fin_mes}-{$data_fin_dia}";

$data_ini_exibida = "{$data_ini_dia}/{$data_ini_mes}/{$data_ini_ano}";
$data_fin_exibida = "{$data_fin_dia}/{$data_fin_mes}/{$data_fin_ano}";

//---------------------------------------------------------------  
$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

if (trim(@$instits) == "") {
  $instits = db_getsession("DB_instit");
}

//@ recupera as informações fornecidas para gerar os dados
//---------------------------------------------------------------  
$head1 = "DEMONSTRATIVO DA DESPESA";
$head2 = "EXERCÍCIO: " . db_getsession("DB_anousu");

$resultinst = db_query("select codigo, nomeinst from db_config where codigo in ({$instits})");
$descr_inst = '';
$sVirgula   = '';
for($iInstituicao = 0; $iInstituicao < pg_numrows($resultinst); $iInstituicao++) {

  db_fieldsmemory($resultinst, $iInstituicao);
  $descr_inst .= $sVirgula . $nomeinst ;
  $sVirgula    = ', ';
}
$head3 = "INSTITUIÇÕES : {$descr_inst}";
$head5 = "Período : {$data_ini_exibida} à {$data_fin_exibida}";

$sele_work = $clselorcdotacao->getDados()." and w.o58_instit in ({$instits})";

//Filtro abaixo é incluido com o sql dinamico para as colunas comprometido e automatico.
$filtro = str_replace("1=1", "", $sele_work);
$filtro = " ".str_replace("w.", "", $filtro);

if (substr($nivel, 1, 1) == 'A') {

  $completo = false;
  $nivela   = substr($nivel, 0, 1);
  if ($nivela == "9") {

    $completo = true;
    $nivela   = "8";
  }
  $result = db_dotacaosaldo($nivela, 1, 2, true, $sele_work, $anousu, $dataini, $datafin);

  db_query("commit");

  $pdf = new PDF();
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $troca       = 1;
  $alt         = 4;
  $qualou      = 0;
  $totproj     = 0;
  $totativ     = 0;
  $pagina      = 1;
  $xorgao      = 0;
  $xunidade    = 0;
  $xfuncao     = 0;
  $xsubfuncao  = 0;
  $xprograma   = 0;
  $xprojativ   = 0;
  $xelemento   = 0;
  $totorgaoini = 0;
  $totorgaosup = 0;
  $totorgaoesp = 0;
  $totorgaored = 0;
  $totorgaoemp = 0;
  $totorgaoliq = 0;
  $totorgaopag = 0;

  $totorgaoanter   = 0;
  $totorgaoreser   = 0;
  $totorgaoatual   = 0;
  $totorgaocomp    = 0;
  $totorgaoresauto = 0;
  
  $totunidaini = 0;
  $totunidasup = 0;
  $totunidaesp = 0;
  $totunidared = 0;
  $totunidaemp = 0;
  $totunidaliq = 0;
  $totunidapag = 0;
 
  $totunidaanter   = 0;
  $totunidareser   = 0;
  $totunidaatual   = 0;
  $totunidacomp    = 0;
  $totunidaresauto = 0;

  $nGeralTotOrgaoini     = 0;
  $nGeralTotOrgaosup     = 0;
  $nGeralTotOrgaoesp     = 0;
  $nGeralTotOrgaored     = 0;
  $nGeralTotOrgaoemp     = 0;
  $nGeralTotOrgaoliq     = 0;
  $nGeralTotOrgaopag     = 0;
  $nGeralTotOrgaoanter   = 0;
  $nGeralTotOrgaoreser   = 0;
  $nGeralTotOrgaocomp    = 0;
  $nGeralTotOrgaoresauto = 0;
  $nGeralTotOrgaoatual   = 0;
          
  $pagina = 1;

  for ($i = 0; $i < pg_numrows($result); $i++) {

    $automatico = 0;
    db_fieldsmemory($result, $i);

    //Sobreescreve valores referente aosm reservados por reservados até a data informada (data final).
    $reservado             = $reservado_ate_data;
    $nResevaAutomatica     = $reservado_automatico_ate_data;
    $nComprometido         = $reservado_manual_ate_data;
    $atual_menos_reservado = $atual - $reservado;

    if ($xorgao . $xunidade != $o58_orgao . $o58_unidade && $quebra_unidade == 'S' && $pagina != 1 && $totunidaanter != 0) {

      $pdf->setfont('arial', 'b', 7);
      $pagina = 1;
      $pdf->ln(3);

      if ($completo == false) {

				$pdf->setfont('arial', 'b', 7);
				$pdf->ln(3);
				$pdf->cell(50,$alt,'', "TB" ,0, "L", 1);
				$pdf->cell(85, $alt, 'TOTAL DA UNIDADE ' ,"TB", 0, "L", 1);
				$pdf->cell(25, $alt, db_formatar($totunidaini,'f'), "TBL", 0, "R", 1);
				$pdf->cell(25, $alt, db_formatar($totunidaanter,'f'), "TBL", 0, "R", 1);
				$pdf->cell(25, $alt, db_formatar($totunidacomp,'f'), "TBL", 0, "R", 1);
				$pdf->cell(25, $alt, db_formatar($totunidaresauto,'f'), "TBL", 0, "R", 1);
				$pdf->cell(25, $alt, db_formatar($totunidareser,'f'), "TBL", 0, "R", 1);
				$pdf->cell(20, $alt, db_formatar($totunidaatual,'f'), "TBL", 1, "R", 1);
			} else {

				$pdf->setfont('arial', 'b', 7);
				$pdf->cell(105, $alt, 'TOTAL DA UNIDADE - SALDOS', "T", 0, "C", 1);
	      $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaini     ,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaini + $totunidasup + $totunidaesp - $totunidared,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidacomp, 'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaresauto, 'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidareser   ,'f'), 1, 0, "R", 1);
	      $pdf->cell(20, 2 * $alt, db_formatar($totunidaatual ,'f'), "TLB", 1, "R", 1);
	      $y = $pdf->GetY();
	      $pdf->SetY($y - $alt);
	      $pdf->cell(105, $alt, 'TOTAIS DA UNIDADE EXECUÇÃO', "TB", 0, "C", 1);
	      $pdf->cell(30, $alt, db_formatar($totunidasup,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaesp,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidared,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaemp,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidaliq,'f'), 1, 0, "R", 1);
	      $pdf->cell(25, $alt, db_formatar($totunidapag,'f'), 1, 0, "R", 1);
	      $pdf->ln(2);
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
    
    if ($xorgao != $o58_orgao && $quebra_orgao == 'S') {

      $pdf->setfont('arial', 'b', 7);
      $pagina = 1;
      $pdf->ln(3);
      if ($completo == false) {

        $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
      	$pdf->cell(85, $alt, 'TOTAL DO ORGÃO ', "TB", 0, "L", 1);
      	$pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), "TBL", 0, "R", 1);
      	$pdf->cell(25, $alt, db_formatar($totorgaoanter, 'f'), "TBL", 0, "R", 1);
      	$pdf->cell(25, $alt, db_formatar($totorgaocomp, 'f'), "TBL", 0, "R", 1);
      	$pdf->cell(25, $alt, db_formatar($totorgaoresauto, 'f'), "TBL", 0, "R", 1);
      	$pdf->cell(25, $alt, db_formatar($totorgaoreser, 'f'), "TBL", 0, "R", 1);
      	$pdf->cell(20, $alt, db_formatar($totorgaoatual, 'f'), "TBL", 1, "R", 1);
      } else {

        if($pdf->gety() > $pdf->h-30){
      		$pdf->addpage("L");
      	}
        $pdf->setfont('arial', 'b', 7);
      	$pdf->cell(105, $alt, 'TOTAL DO ORGÃO - SALDOS', "T", 0, "C", 1);
        $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoini + $totorgaosup + $totorgaoesp - $totorgaored, 'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaocomp, 'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoresauto, 'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoreser, 'f'), 1, 0, "R", 1);
        $pdf->cell(20, 2 * $alt,db_formatar($totorgaoatual,'f'), "TLB", 1, "R", 1);
        $y = $pdf->GetY();
        $pdf->SetY($y - $alt);
        $pdf->cell(105, $alt, 'TOTAIS DO ORGÃO EXECUÇÃO', "TB", 0, "C", 1);
        $pdf->cell(30, $alt, db_formatar($totorgaosup,'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoesp,'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaored,'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoemp,'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaoliq,'f'), 1, 0, "R", 1);
        $pdf->cell(25, $alt, db_formatar($totorgaopag,'f'), 1, 0, "R", 1);
      }
      $pdf->setfont('arial','',7);
            
      $nGeralTotOrgaoini   += $totorgaoini;
      $nGeralTotOrgaosup   += $totorgaosup;
      $nGeralTotOrgaoesp   += $totorgaoesp;
      $nGeralTotOrgaored   += $totorgaored;
      $nGeralTotOrgaoemp   += $totorgaoemp;
      $nGeralTotOrgaoliq   += $totorgaoliq;
      $nGeralTotOrgaopag   += $totorgaopag;
      $nGeralTotOrgaoanter += $totorgaoanter;
      $nGeralTotOrgaoreser += $totorgaoreser;
      $nGeralTotOrgaoatual += $totorgaoatual;
      
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
    }
    
    if ($pdf->gety() > $pdf->h-30 || $pagina == 1) {

      //Novo cabeçalho
      $pagina = 0;
      $qualou = $o58_orgao.$o58_unidade;
      $pdf->addpage("L");
      $pdf->setfont('arial', 'b', 7);
      $pdf->ln(2);

      if ($completo == false) {
      	
	      $pdf->cell(120, 10, "DADOS DA DESPESA", "TBR", 0, "C", 1);
	      $pdf->cell(15, 10, "REDUZ", "TLBR", 0, "C", 1);
	      $x = $pdf->GetX();
	      $y = $pdf->GetY();
	      $pdf->cell(50, 5, "SALDO ORÇAMENTÁRIO", "TLBR", 0, "C", 1);
	      $pdf->cell(75, 5, "SALDO RESERVADO", "TLBR", 0, "C", 1);
	      $pdf->cell(20, 10, "SALDO ATUAL", "TLB", 0, "C", 1);
        $pdf->SetXY($x, $y + 5);
        $pdf->cell(25, 5, "INICIAL", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "DISPONÍVEL", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "COMPROMETIDO", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "AUTOMÁTICO", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "TOTAL", "TLBR", 1, "C", 1);

      } else {
      	
      	$pdf->cell(90, 10, "DADOS DA DESPESA", "TBR", 0, "C", 1);
        $pdf->cell(15, 15, "RECURSO", "TLBR",0 , "C", 1);
	      $pdf->cell(30, 10, "REDUZ", "TLBR", 0, "C", 1);
	      $x = $pdf->GetX();
	      $y = $pdf->GetY();
	      $pdf->cell(50, 5, "SALDO ORÇAMENTÁRIO", "TLBR", 0, "C", 1);
	      $pdf->cell(75, 5, "SALDO RESERVADO", "TLBR", 0, "C", 1);
	      $pdf->cell(20, 15, "SALDO ATUAL", "TLB", 0, "C", 1);
      	
        $pdf->SetXY($x, $y + 5);
                
        $pdf->cell(25, 5, "INICIAL", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "DISPONÍVEL", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "COMPROMETIDO", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "AUTOMÁTICO", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "TOTAL", "TLBR", 0, "C", 1);
        
        $pdf->SetXY($x - 135, $y + 10);
        
        $pdf->cell(90, 5, "DETALHAMENTO DA EXECUÇÃO DA DESPESA", "BTR", 0, "C", 1);
        $pdf->SetX($pdf->GetX() + 15);
        $pdf->cell(30, 5, "CRED. SUPLEM.", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "CRED. ESPECIAL", "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "REDUÇÕES"      , "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "EMPENHADO"     , "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "LIQUIDADO"     , "TLBR", 0, "C", 1);
        $pdf->cell(25, 5, "PAGO"          , "TLBR", 1, "C", 1);
      }
      //Fim do novo cabeçalho
            
      $pdf->cell(0, $alt, '', "T", 1, "C", 0);
      $pdf->setfont('arial', '', 7);
    }

    if ($xorgao != $o58_orgao && $o58_orgao != 0) {

      $xorgao = $o58_orgao;
      if ($nivela == 1) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($o40_descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
				$totorgaoini     += $dot_ini;
				$totorgaoanter   += $atual;
				$totorgaocomp    += $nComprometido;
				$totorgaoresauto += $nResevaAutomatica;
				$totorgaoreser   += $reservado;
				$totorgaoatual   += $atual_menos_reservado;

				//Totalizador da unidade
				$totunidaini     += $dot_ini;
				$totunidaanter   += $atual;
				$totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
				$totunidaatual   += $atual_menos_reservado;
								
      } else {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($o40_descr, 0,33), 0, 1, "L", 0);
				$xunidade = 0;
      }
    }

    if ("$o58_orgao.$o58_unidade" != "$xorgao.$xunidade" && $o58_unidade != 0) {

      $xunidade = "$o58_unidade";
      if ($nivela == 2) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 0, 0, "L", 0);
				$pdf->cell(60, $alt,substr($o41_descr,0,33),0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

				//Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      } else {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($o41_descr, 0, 33), 0, 1, "L", 0);
      }
    }
    
    if ("$o58_orgao.$o58_unidade.$o58_funcao" != "$xfuncao" && $o58_funcao != 0) {

      $xfuncao = "$o58_orgao.$o58_unidade.$o58_funcao";
      $descr   = $o52_descr;

      if ($nivela == 3) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

				//Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      } else {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 1, "L", 0);
      }
    }
    if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao" != "$xsubfuncao" && $o58_subfuncao != 0) {

      $xsubfuncao = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao";
      $descr = $o53_descr;
      if ($nivela == 4) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao').".".db_formatar($o58_subfuncao, 'subfuncao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

				//Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      } else {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'orgao') . db_formatar($o58_funcao, 'unidade') . "." . db_formatar($o58_subfuncao, 'subfuncao'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 1, "L", 0);
      }
    }

    if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa" != "$xprograma" && (($nivela == 8 && $o54_descr != "") || $o58_programa != 0)) {

      $xprograma = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa";
      $descr = $o54_descr;

      if ($nivela == 5) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

				//Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      } else {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'). "." . db_formatar($o58_subfuncao, 'subfuncao') . "." . db_formatar($o58_programa, 'programa'));
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 1, "L", 0);
      }
    }

    if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ" != "$xprojativ" && $o58_projativ != 0) {

      $xprojativ = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ";
      $descr = $o55_descr;

      if ($nivela == 6) {

        $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);
				if ($nivela != 7) {

				  //Totalizador do orgao
				  $totorgaoini     += $dot_ini;
          $totorgaoanter   += $atual;
          $totorgaocomp    += $nComprometido;
          $totorgaoresauto += $nResevaAutomatica;
          $totorgaoreser   += $reservado;
          $totorgaoatual   += $atual_menos_reservado;

          //Totalizador da unidade
          $totunidaini     += $dot_ini;
          $totunidaanter   += $atual;
          $totunidacomp    += $nComprometido;
          $totunidaresauto += $nResevaAutomatica;
          $totunidareser   += $reservado;
          $totunidaatual   += $atual_menos_reservado;
        }
      } else {

        $pdf->setfont('arial', 'b', 7);
				$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 'subfuncao') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				if ($completo == false) {

          $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0,"R", 0);
				  $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				  $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
				  $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
				  $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
				  $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);
				} else {
				  $pdf->cell(25, $alt, '', 0, 1, "R", 0);
				}
				$pdf->setfont('arial', '', 7);
      }
    }
    if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento" != "$xelemento" && $o58_elemento  != 0) {

      $xelemento = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento";
      $descr = $o56_descr;
      
      if ($nivela == 7) {
      	
				$pdf->cell(27, $alt, db_formatar($o58_elemento, 'elemento'), 0, 0, "L", 0);
				$pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
				$pdf->cell(48, $alt, '', 0, 0, "L", 0);
				$pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0,"R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

				//Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
			}
    }
    if ($o58_codigo > 0) {

      $descr = $o56_descr;
      if ($completo == false) {

        $pdf->cell(27, $alt, $o58_elemento, 0, 0, "L", 0);
        $pdf->cell(53, $alt, substr($descr, 0, 30),0, 0, "L", 0);
        $pdf->cell(10, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 0, 0, "C", 0);
	      if ($nivela == 8) {
	       $pdf->cell(30, $alt, substr($o15_descr, 0, 20), 0, 0, "L", 0);
		    } else {
	        $pdf->cell(30, $alt, substr($o15_descr, 0, 20), 0, 0, "L", 0);
		    }
		    $pdf->cell(15, $alt, $o58_coddot . "-" . db_CalculaDV($o58_coddot), 0, 0, "R", 0);
      }
      
      if ($completo == false) {

        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
				$pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;

        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      } else {

        $pdf->setfont('arial', 'b', 7);
      	$pdf->cell(27, $alt, $o58_elemento, 0, 0, "L", 0);
        $pdf->cell(68, $alt, substr($descr, 0, 30), 0, 0, "L", 0);
        $pdf->cell(10, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 0, 0, "C", 0);
        $pdf->cell(30, $alt, $o58_coddot . "-" . db_CalculaDV($o58_coddot), 0, 0, "C", 0);
        //Inicial
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        //Disponivel
        $pdf->cell(25, $alt, db_formatar($dot_ini + $suplemen_acumulado + $especial_acumulado - $reduzido_acumulado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);
        
        $pdf->setfont('arial', '', 6);
        $pdf->SetX($pdf->GetX() + 110);
        //cred suplemetar
        $pdf->cell(25, $alt, db_formatar($suplemen_acumulado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($especial_acumulado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reduzido_acumulado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($empenhado - $anulado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($liquidado, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($pago, 'f'), 0, 1, "R", 0);

        $totorgaoini     += $dot_ini;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaosup     += $suplemen_acumulado;
        $totorgaoesp     += $especial_acumulado;
        $totorgaored     += $reduzido_acumulado;
        $totorgaoemp     += $empenhado-$anulado;
        $totorgaoliq     += $liquidado;
        $totorgaopag     += $pago;                         
        $totorgaoanter   += $atual;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
      
        $totunidaini     += $dot_ini;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidasup     += $suplemen_acumulado;
        $totunidaesp     += $especial_acumulado;
        $totunidared     += $reduzido_acumulado;
        $totunidaemp     += $empenhado-$anulado;
        $totunidaliq     += $liquidado;
        $totunidapag     += $pago;
        $totunidaanter   += $atual;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }

      if ($lista_subeleme == 'S') {

        $sql = "select *
					from orcelemento 
					where substr(o56_elemento,1,7) = '" . str_replace('.', '', substr($o58_elemento, 0, 7)) . "' and
					      substr(o56_elemento,8,5) != '00000' and o56_anousu = " . db_getsession("DB_anousu") . " and
					      o56_orcado is true";
				$res = db_query($sql);
				for ($ne = 0; $ne < pg_numrows($res); $ne++) {

          db_fieldsmemory($res,$ne);
				  $pdf->cell(20, $alt, $o56_elemento, 0, 0, "L", 0);
				  $pdf->cell(80, $alt, $o56_descr, 0, 0, "L", 0);
				  $pdf->cell(125, $alt, $o56_finali, 0, 1, "L", 0);
        }
      }
    }
  }

  $nGeralTotOrgaoini     += $totorgaoini;
  $nGeralTotOrgaosup     += $totorgaosup;
  $nGeralTotOrgaoesp     += $totorgaoesp;
  $nGeralTotOrgaored     += $totorgaored;
  $nGeralTotOrgaoemp     += $totorgaoemp;
  $nGeralTotOrgaoliq     += $totorgaoliq;
  $nGeralTotOrgaopag     += $totorgaopag;
  $nGeralTotOrgaoanter   += $totorgaoanter;
  $nGeralTotOrgaocomp    += $totorgaocomp;
  $nGeralTotOrgaoresauto += $totorgaoresauto;
  $nGeralTotOrgaoreser   += $totorgaoreser;
  $nGeralTotOrgaoatual   += $totorgaoatual;
  
  if ($quebra_unidade == 'S') {

    if ($completo == false) {

      $pdf->setfont('arial', 'b', 7);
      $pdf->ln(3);
      $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
      $pdf->cell(85, $alt, 'TOTAL DA UNIDADE ', "TB", 0, "L", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaini, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaanter, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidacomp, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaresauto, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidareser, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(20, $alt, db_formatar($totunidaatual, 'f'), "TBL", 1, "R", 1);
      
    } else {

      $pdf->setfont('arial', 'b', 7);
      $pdf->cell(105, $alt, 'TOTAL DA UNIDADE - SALDOS', "T", 0, "C", 1);
      $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaini, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaini + $totunidasup + $totunidaesp-$totunidared, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidacomp, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaresauto, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidareser, 'f'), 1, 0, "R", 1);
      $pdf->cell(20, 2 * $alt, db_formatar($totunidaatual, 'f'), "TLB", 1, "R", 1);
      $y = $pdf->GetY();
      $pdf->SetY($y - $alt);
      $pdf->cell(105, $alt, 'TOTAIS DA UNIDADE EXECUÇÃO', "TB", 0, "C", 1);
      $pdf->cell(30, $alt, db_formatar($totunidasup, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaesp, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidared, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaemp, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidaliq, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totunidapag, 'f'), 1, 0, "R", 1);
      $pdf->ln(2);
    }
  }

  $pdf->ln(3);

  if ($completo == false) {

    if ($quebra_orgao == "S" || $quebra_unidade == "S") {

      $pdf->setfont('arial', 'b', 7);
      $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
      $pdf->cell(85, $alt, 'TOTAL DO ORGÃO ', "TB", 0, "L", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoanter, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaocomp, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoresauto, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoreser, 'f'), "TBL", 0, "R", 1);
      $pdf->cell(20, $alt, db_formatar($totorgaoatual, 'f'), "TBL", 1, "R", 1);
  	}
  	$pdf->setfont('arial', 'b', 7);
    $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
    $pdf->cell(85, $alt, 'TOTAL GERAL ', "TB", 0, "L", 1);
      
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoanter, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaocomp, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoresauto, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoreser, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(20, $alt, db_formatar($nGeralTotOrgaoatual, 'f'), "TBL", 1, "R", 1);

  } else {

    if ($quebra_orgao == "S" || $quebra_unidade == "S") {

      $pdf->setfont('arial', 'b', 7);
  		$pdf->cell(105, $alt, 'TOTAL DO ORGÃO - SALDOS', "T", 0, "C", 1);
      $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoanter, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaocomp, 'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoresauto ,'f'), 1, 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoreser, 'f'), 1, 0, "R", 1);
      $pdf->cell(20,2*$alt,db_formatar($totorgaoatual, 'f'), "TLB", 1, "R", 1);
      $y = $pdf->GetY();
      $pdf->SetY($y - $alt);
      $pdf->cell(105, $alt, 'TOTAIS DO ORGÃO EXECUÇÃO', "TB", 0, "C", 1);
      $pdf->cell(30, $alt, db_formatar($totorgaosup, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoesp, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaored, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoemp, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoliq, 'f'), 1,0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaopag, 'f'), 1,1, "R", 1);
    }
    $pdf->setfont('arial', 'b', 7);
    $pdf->cell(105, $alt, 'TOTAL GERAL - SALDOS', "T", 0, "C", 1);
    $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "TL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoanter, 'f'), "TL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaocomp, 'f'), "TL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoresauto,'f'), "TL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoreser, 'f'), "TL", 0, "R", 1);
    $pdf->cell(20, 2 * $alt, db_formatar($nGeralTotOrgaoatual, 'f'), "TLB", 1, "R", 1);
    $y = $pdf->GetY();
    $pdf->SetY($y - $alt);
    $pdf->cell(105, $alt, 'TOTAIS DA EXECUÇÃO', "TB", 0, "C", 1);
    $pdf->cell(30, $alt, db_formatar($nGeralTotOrgaosup, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoesp, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaored, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoemp, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoliq, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaopag, 'f'), "TBRL", 0, "R", 1);
  }

} else {

  $nivela = substr($nivel, 0, 1);
  $anousu = db_getsession("DB_anousu");
  $result = db_dotacaosaldo($nivela, 3, 2, true, $sele_work, $anousu, $dataini, $datafin);

  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 7);
  $troca           = 1;
  $alt             = 4;
  $qualou          = 0;
  $totproj         = 0;
  $totativ         = 0;
  $pagina          = 1;
  $xorgao          = 0;
  $xunidade        = 0;
  $xfuncao         = 0;
  $xsubfuncao      = 0;
  $xprograma       = 0;
  $xprojativ       = 0;
  $xelemento       = 0;
  $totorgaoanter   = 0;
  $totorgaoreser   = 0;
  $totorgaocomp    = 0;
  $totorgaoresauto = 0;
  $totorgaoini     = 0;
  $totorgaoatual   = 0;
  $totunidaanter   = 0;
  $totunidareser   = 0;
  $totunidacomp    = 0;
  $totunidaresauto = 0;
  $totunidaini     = 0;
  $totunidaatual   = 0;
  $nGeralTotOrgaoanter   = 0;
  $nGeralTotOrgaoatual   = 0;
  $nGeralTotOrgaocomp    = 0;
  $nGeralTotOrgaoresauto = 0;
  $nGeralTotOrgaoreser   = 0;
  $nGeralTotOrgaoini     = 0;
  $pagina = 1;

  for ($iLinha = 0; $iLinha < pg_numrows($result); $iLinha++) {

    db_fieldsmemory($result, $iLinha);

    //Sobreescreve valores referente aosm reservados por reservados até a data informada (data final).
    $reservado             = $reservado_ate_data;
    $nResevaAutomatica     = $reservado_automatico_ate_data;
    $nComprometido         = $reservado_manual_ate_data;
    $atual_menos_reservado = $atual - $reservado;

    $k = $iLinha;
    if ($pdf->gety() > $pdf->h-30 || $pagina == 1) {

      $pagina = 0;
      $qualou = $o58_orgao . $o58_unidade;
      $pdf->addpage("L");
      $pdf->setfont('arial', 'b', 7);
      $pdf->ln(2);
      $pdf->cell(120, 10, "DADOS DA DESPESA", "TBR", 0, "C", 1);
      $pdf->cell(15, 10, "REDUZ", "TLBR", 0, "C", 1);
      $x = $pdf->GetX();
      $y = $pdf->GetY();
      $pdf->cell(50, 5, "SALDO ORÇAMENTÁRIO", "TLBR", 0, "C", 1);
      $pdf->cell(75, 5, "SALDO RESERVADO", "TLBR", 0, "C", 1);
      $pdf->cell(20, 10, "SALDO ATUAL", "TLB", 0, "C", 1);
      $pdf->SetXY($x, $y + 5);
      $pdf->cell(25, 5, "INICIAL", "TLBR", 0, "C", 1);
      $pdf->cell(25, 5, "DISPONÍVEL", "TLBR", 0, "C", 1);
      $pdf->cell(25, 5, "COMPROMETIDO", "TLBR", 0, "C", 1);
      $pdf->cell(25, 5, "AUTOMÁTICO", "TLBR", 0, "C", 1);
      $pdf->cell(25, 5, "TOTAL", "TLBR", 1, "C", 1);
      $pdf->setfont('arial', '', 7);
    }
    if ($nivela == 1) {

      $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 0, 0, "L", 0);
      $pdf->cell(60, $alt, substr($o40_descr, 0, 33), 0, 0, "L", 0);
      $pdf->cell(48, $alt, '', 0, 0, "L", 0);
      $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
      $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
      $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
      $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
      $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
      $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

      //Totalizador do orgao
      $totorgaoini     += $dot_ini;
      $totorgaoanter   += $atual;
      $totorgaocomp    += $nComprometido;
      $totorgaoresauto += $nResevaAutomatica;
      $totorgaoreser   += $reservado;
      $totorgaoatual   += $atual_menos_reservado;

      //Totalizador da unidade
      $totunidaini     += $dot_ini;
      $totunidaanter   += $atual;
      $totunidacomp    += $nComprometido;
      $totunidaresauto += $nResevaAutomatica;
      $totunidareser   += $reservado;
      $totunidaatual   += $atual_menos_reservado;
    }
    
    if ($nivela == 2) {

      	$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($o41_descr, 0, 33), 0, 0, "L", 0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }
      $descr = $o52_descr;
      
      if ($nivela == 3) {

      	$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }
      
      $descr = $o53_descr;
      if ($nivela == 4) {

      	$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 'subfuncao'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($descr,0,33),0,0,"L",0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
			}
			
      $descr = $o54_descr;
      if ($nivela == 5) {

      	$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }
      $descr = $o55_descr;
      
      if ($nivela == 6) {

      	$pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }
      $descr = $o56_descr;
      
      if ($nivela == 7) {

				$pdf->cell(27, $alt, db_formatar($o58_elemento, 'elemento'), 0, 0, "L", 0);
        $pdf->cell(60, $alt, substr($descr, 0, 33), 0, 0, "L", 0);
        $pdf->cell(48, $alt, '', 0, 0, "L", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade        
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }
     
      if ($nivela == 8) {
          
      	$descr = $o56_descr;	
      
        $pdf->cell(10, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 0, 0, "C", 0);
        $pdf->cell(110, $alt, substr($o15_descr, 0, 25), 0, 0, "L", 0);
        $pdf->cell(15, $alt, $o58_coddot . "-" . db_CalculaDV($o58_coddot), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($atual, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nComprometido, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($nResevaAutomatica, 'f'), 0, 0, "R", 0);
        $pdf->cell(25, $alt, db_formatar($reservado, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($atual_menos_reservado, 'f'), 0, 1, "R", 0);

        //Totalizador do orgao
        $totorgaoini     += $dot_ini;
        $totorgaoanter   += $atual;
        $totorgaocomp    += $nComprometido;
        $totorgaoresauto += $nResevaAutomatica;
        $totorgaoreser   += $reservado;
        $totorgaoatual   += $atual_menos_reservado;
        //Totalizador da unidade
        $totunidaini     += $dot_ini;
        $totunidaanter   += $atual;
        $totunidacomp    += $nComprometido;
        $totunidaresauto += $nResevaAutomatica;
        $totunidareser   += $reservado;
        $totunidaatual   += $atual_menos_reservado;
      }      
  }

  $nGeralTotOrgaoanter   += $totorgaoanter;
  $nGeralTotOrgaoatual   += $totorgaoatual;
  $nGeralTotOrgaocomp    += $totorgaocomp;
  $nGeralTotOrgaoresauto += $totorgaoresauto;
  $nGeralTotOrgaoreser   += $totorgaoreser;
  $nGeralTotOrgaoini     += $totorgaoini;
  
  $pdf->ln(3);
  $pdf->setfont('arial', 'b', 7);
  $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
  $pdf->cell(85, $alt, 'TOTAL GERAL ', "TB", 0, "L", 1);
      
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoanter, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaocomp, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoresauto, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoreser, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(20, $alt, db_formatar($nGeralTotOrgaoatual, 'f'), "TBL", 1, "R", 1);
}
$pdf->Output();

db_query("commit");

/**
 * Função para retornar um sql dinamico
 * conforme os nivel em execução
 *
 * @param object  $oData
 * @param integer $iNivel
 * @param integer $iAnousu
 *
 * @return string
 */
function retornaSqlReservado($oData, $iNivel, $iAnousu, $dataini, $datafin) {
	
	$sSqlWhere  = " and o58_orgao = " . $oData->o58_orgao;
	
	if (!empty($oData->o58_unidade)) {
    $sSqlWhere .= " and o58_unidade = " . $oData->o58_unidade;
	}
	if (!empty($oData->o58_funcao)) {
    $sSqlWhere .= " and o58_funcao = " . $oData->o58_funcao;
	}
  if (!empty($oData->o58_subfuncao)) {
	  $sSqlWhere .= " and o58_subfuncao = " . $oData->o58_subfuncao;
  }
  if (!empty($oData->o58_programa)) {
	  $sSqlWhere .= " and o58_programa = " . $oData->o58_programa;
  }
  if (!empty($oData->o58_projativ)) {
    $sSqlWhere .= " and o58_projativ = " . $oData->o58_projativ;
  }
  if (!empty($oData->o58_elemento)) {
    $sSqlWhere .= " and o56_elemento = '" . $oData->o58_elemento . "'";
  }
  if (!empty($oData->o58_codigo)) {
	  $sSqlWhere .= " and o58_codigo = " . $oData->o58_codigo;
  }

	$sSql = "select coalesce(sum(o80_valor),0) as total
	           from orcreservager 
	                inner join orcreserva on o84_codres = o80_codres 
	                inner join orcdotacao on o58_coddot = o80_coddot
	                                     and o80_anousu = o58_anousu
	                inner join orcelemento on o58_codele = o56_codele
	                                      and o58_anousu = o56_anousu 
	             
	          where o80_anousu = {$iAnousu}
	            and o84_data between '{$dataini}' and '{$datafin}'
	             ";
  	          
	$sSql .= $sSqlWhere;
	return $sSql;
}

/**
 * Função para retornar um sql dinamico
 * conforme os nivel em execução
 *
 * @param object  $oData
 * @param integer $iNivel
 * @param integer $iAnousu
 *
 * @return string
 */
function retornaSqlReservadoSoNivel($oData, $iNivel, $iAnousu, $dataini, $datafin) {
  
  switch ($iNivel) {
    case 1: $sSqlWhere  = " and o58_orgao = " . $oData->o58_orgao;
      break;
    case 2: $sSqlWhere  = " and o58_orgao = " . $oData->o58_orgao;
            $sSqlWhere .= " and o58_unidade = " . $oData->o58_unidade;
      break;
    case 3: $sSqlWhere  = " and o58_funcao = " . $oData->o58_funcao;
      break;
    case 4: $sSqlWhere  = " and o58_subfuncao = " . $oData->o58_subfuncao;
      break;
    case 5: $sSqlWhere = " and o58_programa = " . $oData->o58_programa;
      break;
    case 6: $sSqlWhere = " and o58_projativ = " . $oData->o58_projativ;
      break;
    case 7: $sSqlWhere = " and o56_elemento = '" . $oData->o58_elemento . "'";
      break;
     case 8:$sSqlWhere = " and o58_codigo = " . $oData->o58_codigo;
      break;
      default: $sSqlWhere = "";
  }
  
  $sSql = "select coalesce(sum(o80_valor),0) as total
             from orcreservager 
                  inner join orcreserva on o84_codres = o80_codres 
                  inner join orcdotacao on o58_coddot = o80_coddot
                                       and o80_anousu = o58_anousu
                  inner join orcelemento on o58_codele = o56_codele
                                        and o58_anousu = o56_anousu 
               
            where o80_anousu = {$iAnousu}
              and o84_data between '{$dataini}' and '{$datafin}'
               ";
              
  $sSql .= $sSqlWhere;
  return $sSql;
}