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
include(modification("libs/db_sql.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_levanta_classe.php"));
include(modification("classes/db_levantanotas_classe.php"));
include(modification("classes/db_levvalor_classe.php"));
include(modification("classes/db_levinscr_classe.php"));
include(modification("classes/db_ativprinc_classe.php"));
include(modification("classes/db_parissqn_classe.php"));
include(modification("classes/db_levusu_classe.php"));
include(modification("classes/db_parfiscal_classe.php"));
$cllevanta = new cl_levanta;
$clparfiscal = new cl_parfiscal;
$clparissqn = new cl_parissqn;
$cllevvalor = new cl_levvalor;
$cllevantanotas = new cl_levantanotas;
$cllevinscr = new cl_levinscr;
$clativprinc = new cl_ativprinc;
$cllevusu = new cl_levusu;
//y63_historico

$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("y63_aliquota");
$cllevantanotas->rotulo->label();
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$mostraativ = 'f';

$total = 0;
$alt = 4;

$vtot_bruto = 0;
$vtot_imposto = 0;
$vtot_pago = 0;
$vtot_saldo = 0; //valor a pagar
$vtot_correcao = 0;
$vtot_multa = 0;
$vtot_juro = 0;
$vtot_total = 0;

//die($cllevanta->sql_query_file($codlev));
$result = $cllevanta->sql_record($cllevanta->sql_query_file($codlev, "*", null, ""));
//die($cllevanta->sql_query_file($codlev));
$numrows = $cllevanta->numrows;
if ($numrows > 0) {
    db_fieldsmemory($result, 0, true);

    $head1 = " Auto de lançamento ";
    $head2 = "";
    $head3 = "";
    $head4 = "";
    $head5 = "Levantamento fiscal: $codlev Data:$y60_data";
    $head6 = "Período: $y60_dtini a $y60_dtfim";


    if ($y60_espontaneo == 'f') {
        $rsParfiscal = $clparfiscal->sql_record($clparfiscal->sql_query_file(null, "y32_receit", null, ""));
        $numrowspar = $clparfiscal->numrows;
        if ($numrowspar > 0) {
            db_fieldsmemory($rsParfiscal, 0);
            $receitatual = $y32_receit;
        } else {
            db_redireciona("db_erros.php?fechar=true&db_erro= Configure os parâmetros de receita no modulo fiscal ! ");
        }
    } elseif ($y60_espontaneo == 't') {
        $rsParfiscal = $clparfiscal->sql_record($clparfiscal->sql_query_file(null, "y32_receitexp", null, ""));
        $numrowspar = $clparfiscal->numrows;
        if ($numrowspar > 0) {
            db_fieldsmemory($rsParfiscal, 0);
            $receitatual = $y32_receitexp;
        } else {
            //db_msgbox("entrou");
            db_redireciona("db_erros.php?fechar=true&db_erro= Configure os parâmetros de receita expontanea no modulo fiscal ! ");
        }
    }
}
if ($numrows == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->addpage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 12);
$largpag = $pdf->w - $pdf->lMargin - $pdf->rMargin;
/*
$head1 = " Auto de lançamento ";
$head2 = "";
$head3 = "";
$head4 = "";
$head5 = "Levantamento fiscal: $codlev Data:$y60_data";
$head6 = "Período: $y60_dtini a $y60_dtfim";
*/
//die($cllevinscr->sql_query($codlev, "q02_inscr,z01_nome,z01_ender"));
$result = $cllevinscr->sql_record($cllevinscr->sql_query($codlev, "q02_inscr,z01_nome,z01_ender"));
$numrowsinscr = $cllevinscr->numrows;
if ($numrowsinscr > 0) {
    db_fieldsmemory($result, 0);
    $clativprinc->sql_record($clativprinc->sql_query_compl($q02_inscr, "q03_descr"));
    $mostraativ = 't';
}
$sqllevanta = "select case when levinscr.y62_codlev is not null then
                               q02_inscr
                            else cgm.z01_numcgm
                        end as origem,
                        case when levinscr.y62_codlev is not null then
                               'INSCR.'
                           else 'CGM'
                        end as tipo,
				       cgm.*,
				       issbase.*,
                       levcgm.*,
                       tabativ.*,
                       ativid.*,
                       levinscr.*,
		       c.z01_nome as nomeinscr,
		       c.z01_ender as enderinscr
			     from levanta
				       left join levcgm   on levcgm.y93_codlev   = levanta.y60_codlev
				       left join cgm      on cgm.z01_numcgm      = levcgm.y93_numcgm
				       left join levinscr on levinscr.y62_codlev = levanta.y60_codlev
				       left join issbase  on issbase.q02_inscr   = levinscr.y62_inscr
				       left join cgm c on c.z01_numcgm = issbase.q02_numcgm
				       left join tabativ  on tabativ.q07_inscr   = levinscr.y62_inscr
				       inner join ativprinc on ativprinc.q88_inscr = tabativ.q07_inscr
				        and ativprinc.q88_seq = tabativ.q07_seq
				       left join ativid  on ativid.q03_ativ   = tabativ.q07_ativ
         			where levanta.y60_codlev = $codlev 
                ";
// 13-12-2005
//adicionei leftjoin com tabativ e com ativid para buscar a atividades quand  lancament eh por issqn
//die($sqllevanta);
$resultlevanta = db_query($sqllevanta);
//$clativprinc->numrows;
$numrows = pg_num_rows($resultlevanta);
if ($numrows > 0) {
    db_fieldsmemory($resultlevanta, 0);
}

//=====================================================================================================================================================================

$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

$pdf->roundedrect(52, 40, 180, 35, 2, '1234');

$pdf->setx(55);
$pdf->setfont('arial', 'b', 12);
$pdf->cell(47, $alt + 1, "Origem : ", 0, 0, "L", 0);
$pdf->setfont('arial', '', 12);
$pdf->cell(43, $alt + 1, $tipo . "/" . $origem, 0, 0, "L", 0);
$pdf->setfont('arial', 'b', 12);
$pdf->cell(60, $alt + 1, "Levantamento Fiscal : ", 0, 0, "L", 0);
$pdf->setfont('arial', '', 12);
$pdf->cell(30, $alt + 1, $codlev, 0, 1, "L", 0);

$pdf->setx(55);
$pdf->setfont('arial', 'b', 12);
$pdf->cell(47, $alt + 1, "Nome/Razão Social : ", 0, 0, "L", 0);
$pdf->setfont('arial', '', 12);
$pdf->cell(110, $alt + 1, ($z01_nome == '' ? $nomeinscr : $z01_nome), 0, 1, "L", 0);
$pdf->setfont('arial', 'b', 12);

$pdf->setx(55);
$pdf->cell(47, $alt + 1, "Endereço : ", 0, 0, "L", 0);
$pdf->setfont('arial', '', 12);
$pdf->cell(110, $alt + 1, ($z01_ender == '' ? $enderinscr : $z01_ender), 0, 1, "L", 0);

if (isset($mostraativ) && $mostraativ == 't') {
    $pdf->setx(55);
    $pdf->setfont('arial', 'b', 12);
    $pdf->cell(47, $alt + 1, "Atividade Principal : ", 0, 0, "L", 0);
    $pdf->setfont('arial', '', 12);

    if (strlen($q03_descr) > 40) {
        $q03_descr = substr($q03_descr,0, 40) . '...';
    }
    
    $pdf->cell(110, $alt + 1, $q03_descr, 0, 1, "L", 0); //
    $pdf->setfont('arial', 'b', 12);
}

$pdf->setx(55);
$pdf->setfont('arial', 'b', 12);
$pdf->cell(47, $alt + 1, "Data : ", 0, 0, "L", 0);
$pdf->setfont('arial', '', 12);
$pdf->cell(110, $alt + 1, $y60_data, 0, 1, "L", 0);

//$pdf->roundedrect(10,67,150,30,2,'1234'); 

$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

//=====================================================================================================================================================================
/*
$head1 = " Auto de lançamento ";
$head2 = "";
$head3 = "";
$head4 = "";
$head5 = "Levantamento fiscal: $codlev Data:$y60_data";
$head6 = "Período: $y60_dtini a $y60_dtfim";
*/
//==================================================================================================================================================================

$sqlparag = "select *
		from db_documento 
		inner join db_docparag on db03_docum = db04_docum
		inner join db_tipodoc on db08_codigo  = db03_tipodoc
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_tipodoc = 1014 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";
$resparag = db_query($sqlparag);
//db_criatabela($resparag);exit;
if (pg_numrows($resparag) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do Auto de lancamento !!! ');
    exit;
}
$numrows = pg_numrows($resparag);
//$pdf1->inicia     = $db02_inicia;
for ($i = 0; $i < $numrows; $i++) {
    db_fieldsmemory($resparag, $i);
    if ($db04_ordem == '1') {
        $paragrafo1 = $db02_texto;
    }
    if ($db04_ordem == '2') {
        $paragrafo2 = $db02_texto;
    }
    if ($db04_ordem == '3') {
        $paragrafo3 = $db02_texto;
    }
    if ($db04_ordem == '4') {
        $paragrafo4 = $db02_texto;
    }
}

//========================================================================================================================================================

//die($cllevvalor->sql_query_file(null, "distinct y63_mes,y63_ano", "y63_ano", "y63_codlev=$codlev"));
$result01 = $cllevvalor->sql_record($cllevvalor->sql_query_file(null, "distinct y63_mes,y63_ano", "y63_ano asc, y63_mes asc ", "y63_codlev=$codlev"));
$numrows01 = $cllevvalor->numrows;

$pdf->setfont('arial', 'b', 16);
//paragrafo 1
$pdf->cell($largpag, $alt + 1, $paragrafo1, 0, 1, "C", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

//paragrafo 2
$pdf->setfont('arial', '', 12);
$pdf->multicell($largpag, $alt + 1, "          " . $paragrafo2, 0, 1, "J", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);


for ($x = 0; $x < $numrows01; $x++) {
    db_fieldsmemory($result01, $x);
    $pdf->cell(50, $alt, "Competência: $y63_mes/$y63_ano", 1, 1, "C", 1);
    $sql = $cllevvalor->sql_query_notas(null, "y79_documento,y79_codigo,y79_data,y79_valor,levvalor.*", "", "y63_mes=$y63_mes and y63_ano=$y63_ano and y63_codlev =$codlev");
    $result = $cllevvalor->sql_record($sql);
    $numrows = $cllevvalor->numrows;
    if ($numrows < 1) {
        continue;
    }
    $tot_valor = 0;
    $tot_imposto = 0;
    for ($i = 0; $i < $numrows; $i++) {
        db_fieldsmemory($result, $i);
        if ($y79_codigo == "") {
            $y79_valor = $y63_bruto;
        }

        $imposto = ($y63_aliquota * $y79_valor) / 100;
        $pdf->setfont('arial', 'b', 8);
        if ($pdf->gety() > $pdf->h - 30 || $i == 0) {
            if ($pdf->gety() > $pdf->h - 30) {
                $pdf->addpage("L");
            }
            //$pdf->cell(20,$alt,,1,0,"C",1);
            $pdf->cell(20, $alt, "$RLy79_documento", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "$RLy79_data", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "Valor bruto", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "$RLy63_aliquota", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "Imposto", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "Vencimento", "T", 0, "L", 0);
            $pdf->cell(20, $alt, "Valor pago", "T", 0, "C", 0);
            $pdf->cell(20, $alt, "Valor à pagar", "T", 0, "C", 0);
            $pdf->cell(25, $alt, "Valor corrigido", "T", 0, "C", 0);
            $pdf->cell(20, $alt, "Multa", "T", 0, "C", 0);
            $pdf->cell(20, $alt, "Juros", "T", 0, "C", 0);
            $pdf->cell(20, $alt, "Valor total", "T", 1, "C", 0);
        }
        $pdf->setfont('arial', '', 7);
        $pdf->cell(20, $alt, "$y79_documento", "T", 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($y79_data, "d"), "T", 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($y79_valor, "f"), "T", 0, "L", 0);
        $pdf->cell(20, $alt, "$y63_aliquota", "T", 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($imposto, "p"), "T", 1, "L", 0);

        $tot_imposto += $imposto;
        $tot_valor += $y79_valor;
    }

    //correções
    if ($numrows > 0) {

        $result66 = $clparfiscal->sql_record($clparfiscal->sql_query_file(db_getsession('DB_instit'), "*"));
        db_fieldsmemory($result66, 0);


        $dtoper = date("Y-m-d", db_getsession("DB_datausu"));

        $aData = split("-", $y63_dtvenc);
        $dDtOperData = $aData[0] . "-" . $aData[1] . "-01";

        $result = db_query("select round(fc_corre(" . ($y60_espontaneo == 't' ? $y32_receitexp : $y32_receit) . ",'" . $y63_dtvenc . "'," . $y63_saldo . ",'" . $dtoper . "'," . db_getsession("DB_anousu") . ",'$y63_dtvenc'),2) as correcao");
        db_fieldsmemory($result, 0);

        $result = db_query("select fc_juros(" . ($y60_espontaneo == 't' ? $y32_receitexp : $y32_receit) . ",'" . $y63_dtvenc . "','" . $dtoper . "','" . $dDtOperData . "','f'," . db_getsession("DB_anousu") . ") as juro");
        db_fieldsmemory($result, 0);
        $juro = round($correcao * $juro, 2);

        $result = db_query("select fc_multa(" . ($y60_espontaneo == 't' ? $y32_receitexp : $y32_receit) . ",'" . $y63_dtvenc . "','" . $dtoper . "','" . $dDtOperData . "'," . db_getsession("DB_anousu") . ") as multa");
        db_fieldsmemory($result, 0);

        $multa = round($correcao * $multa, 2);

        $total = round($correcao + $juro + $multa, 2);

        //$correcao = $correcao - $y63_saldo;
        //--------------------------------------------

    } else {
        $multa = '0.00';
        $correcao = '0.00';
        $juro = '0.00';
        $total = '0.00';
        $y63_saldo = '0.00';
    }

    //$pdf->cell(260,$alt,"TOTAL DE REGISTROS  : ".$total,"T",1,"L",0);
    $pdf->setfont('arial', '', 7);
    $pdf->cell(20, $alt, "", "T", 0, "C", 0);
    $pdf->cell(20, $alt, "", "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($tot_valor, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, "", "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($tot_imposto, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, $y63_dtvenc, "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($y63_pago, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, $y63_saldo, "T", 0, "C", 0);
    $pdf->cell(25, $alt, db_formatar($correcao, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($multa, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($juro, "p"), "T", 0, "C", 0);
    $pdf->cell(20, $alt, db_formatar($total, "p"), "T", 1, "C", 0);

    $vtot_bruto += $tot_valor;
    $vtot_imposto += $tot_imposto;
    $vtot_pago += $y63_pago;
    $vtot_saldo += $y63_saldo; //valor a pagar
    $vtot_correcao += $correcao;
    $vtot_multa += $multa;
    $vtot_juro += $juro;
    $vtot_total += $total;

}

//=====================================================================================================================================================================

$pdf->Ln(5);
// imprime o total geral
$pdf->setfont('arial', 'b', 8);
$pdf->cell(20, $alt, "TOTAL GERAL", "T", 0, "C", 0);
$pdf->cell(20, $alt, "", "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_bruto, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, "", "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_imposto, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, "", "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_pago, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_saldo, "p"), "T", 0, "C", 0);
$pdf->cell(25, $alt, db_formatar($vtot_correcao, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_multa, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_juro, "p"), "T", 0, "C", 0);
$pdf->cell(20, $alt, db_formatar($vtot_total, "p"), "T", 1, "C", 0);

$pdf->ln(3);

$pdf->setfont('arial', '', 12);
//paragrafo 3
$resultusu = $cllevusu->sql_record($cllevusu->sql_query($codlev, null, "nome"));
$numrowsusu = $cllevusu->numrows;
if ($numrowsusu > 0) {
    for ($i = 0; $i < $numrowsusu; $i++) {
        db_fieldsmemory($resultusu, $i);
        if ($numrowsusu > 1) {
            $vir = ", ";
        }
//		$fiscaislev .= $nome.$vir;
    }
}
$paragrafo3 = db_geratexto($paragrafo3);
$pdf->multicell($largpag, $alt + 1, "          " . $paragrafo3, 10, 1, "C", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

$sqlmunic = "select munic from db_config where codigo = " . db_getsession("DB_instit");
$resultmunic = db_query($sqlmunic);
//db_criatabela($result);exit;
db_fieldsmemory($resultmunic, 0);
$pdf->setfont('arial', 'b', 10);
$pdf->cell($largpag, $alt + 1, $munic . ", " . date('d') . " DE " . strtoupper(db_mes(date('m'))) . " DE " . date('Y') . ".", 0, 1, "R", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

$result11 = $cllevusu->sql_record($cllevusu->sql_query($codlev, null, "nome"));
$numrows11 = $cllevusu->numrows;
if ($numrows11 > 0) {
    $pdf->setfont('arial', 'b', 8);
    //$pdf->cell(100, $alt, "FISCAIS:", 1, 1, "L", 1);
    $pdf->setfont('arial', '', 8);
    $passa = 1;
    for ($i = 0; $i < $numrows11; $i++) {
        db_fieldsmemory($result11, $i);
        if ($passa % 2 == 0) {
            $pdf->cell(100, $alt + 2, $nome, "T", $passa, "C", 0);
            $passa--;
        } else {
            $pdf->cell(100, $alt + 2, $nome, "T", $passa, "C", 0);
            $passa++;
        }
    }
}
//paragrafo 4 
$pdf->setfont('arial', '', 12);
$paragrafo4 = db_geratexto($paragrafo4);
$pdf->multicell($largpag, $alt + 1, "          " . $paragrafo4, 10, 1, "C", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);
$pdf->cell(70, $alt + 1, "", 0, 1, "L", 0);

$pdf->Output();
?>
