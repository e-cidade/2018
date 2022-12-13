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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_arreprescr_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clarreprescr = new cl_arreprescr;

$debitos = str_replace("X",",",$lista);
$sql = "SELECT k30_numpre,
               k30_numpar,
               to_char(k31_data,'dd/mm/yyyy') as k31_data,
               k30_vlrcorr,
               k30_vlrjuros,
               k30_multa,
               k30_desconto,
               nome
          FROM arreprescr
         INNER JOIN prescricao  on prescricao.k31_codigo  = arreprescr.k30_prescricao and k31_instit = ".db_getsession('DB_instit')."
         INNER JOIN db_usuarios on db_usuarios.id_usuario = prescricao.k31_usuario
         WHERE
          prescricao.k31_data BETWEEN '$dat1' AND '$dat2'
          AND k30_anulado is false ";
if($debitos!= ""){
    if($ver == "sem"){
        $sql.=" AND k00_tipo NOT IN($debitos) ";
    }else{
        $sql.=" AND k00_tipo IN($debitos) ";
    }
}

if($usu != ""){
    $sql.= " AND k23_usuario = $usu ";
}
$sql .= " ORDER BY arreprescr.k30_numpre, arreprescr.k30_numpar ";

$result = $clarreprescr->sql_record($sql);
if($clarreprescr->numrows == 0){
    echo "<table width='100%'>
        <tr>
        <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
        </table>";
    exit;
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Relatório dos Débitos Prescritos";
$head3 = "Periodo:".substr($dat1,8,2)."/".substr($dat1,5,2)."/".substr($dat1,0,4)." A ".substr($dat2,8,2)."/".substr($dat2,5,2)."/".substr($dat2,0,4);
$pri = true;
$p = 0;
$flt_vlrcorr = 0;
$flt_juro    = 0;
$flt_multa   = 0;
$flt_desconto= 0;
for($x=0; $x < $clarreprescr->numrows; $x++){
    db_fieldsmemory($result,$x);

    if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
        $pdf->addpage();
        $pdf->setfillcolor(235);
        $pdf->setfont('arial','b',7);
        $pdf->cell(15,4,"Numpre",1,0,"C",1);
        $pdf->cell(10,4,"Parcela",1,0,"C",1);
        $pdf->cell(60,4,"Usuário",1,0,"C",1);
        $pdf->cell(15,4,"Data",1,0,"C",1);
        $pdf->cell(18,4,"Vlr. Corrigido",1,0,"C",1);
        $pdf->cell(18,4,"Juro",1,0,"C",1);
        $pdf->cell(18,4,"Multa",1,0,"C",1);
        $pdf->cell(18,4,"Desconto",1,0,"C",1);
        $pdf->cell(18,4,"Total",1,1,"C",1);
        $pri = false;
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(15,4,$k30_numpre,0,0,"R",$p);
    $pdf->cell(10,4,$k30_numpar,0,0,"L",$p);
    $pdf->cell(60,4,$nome,0,0,"C",$p);
    $pdf->cell(15,4,$k31_data,0,0,"C",$p);
    $pdf->cell(18,4,number_format( $k30_vlrcorr, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(18,4,number_format( $k30_vlrjuros, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(18,4,number_format( $k30_multa, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(18,4,number_format( $k30_desconto, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(18,4,number_format( $k30_vlrjuros+$k24_multa+$k24_vlrcorr-$k30_desconto, 2, ",", "." ),0,1,"R",$p);
    if($p == 0){
        $p = 1;
    }else{
        $p = 0;
    }
    $flt_vlrcorr += $k30_vlrcorr;
    $flt_juro    += $k30_vlrjuros;
    $flt_multa   += $k30_multa;
    $flt_desconto+= $k30_desconto;
}
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pdf->cell(100,4,"Valor Total: ",1,0,"R",1);
$pdf->cell(18,4,number_format( $flt_vlrcorr, 2, ",", "." ),1,0,"R",$p);
$pdf->cell(18,4,number_format( $flt_juro   , 2, ",", "." ),1,0,"R",$p);
$pdf->cell(18,4,number_format( $flt_multa  , 2, ",", "." ),1,0,"R",$p);
$pdf->cell(18,4,number_format( $flt_desconto, 2, ",", "." ),1,0,"R",$p);
$pdf->cell(18,4,number_format( $flt_juro+$flt_multa+$flt_vlrcorr-$flt_desconto, 2, ",", "." ),1,1,"R",$p);

$pdf->Output();
?>