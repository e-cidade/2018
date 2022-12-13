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

$clrotulo = new rotulocampo;
$clrotulo->label('r13_codigo');
$clrotulo->label('r13_descr');
$clrotulo->label('r13_descro');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "RELAÇÃO DE RPAs";
$head5 = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');

$sql = "
select distinct c75_codlan,
       e60_codemp,
       e60_numemp,
       c75_data,
       c70_valor,
       z01_nome,
       o41_orgao,
       o41_unidade,
       o58_projativ,
       o58_codigo,
       c60_estrut,
       e60_resumo
from empelemento
     inner join empempenho on e60_numemp = e64_numemp
     inner join conplano on c60_codcon = e64_codele and c60_anousu = e60_anousu
     inner join orcdotacao on e60_coddot = o58_coddot and o58_anousu = e60_anousu
     inner join orcunidade on o41_unidade = o58_unidade and o41_orgao = o58_orgao and o41_anousu = e60_anousu
     inner join conlancamemp on e60_numemp = c75_numemp
     inner join conlancam on c70_codlan = c75_codlan
     inner join conlancamdoc on c71_codlan = c75_codlan and c71_coddoc in (5,35)
     inner join cgm on z01_numcgm = e60_numcgm
     inner join pagordem on e60_numemp = e50_numemp
where c60_estrut like '3339036%'
  and e50_obs like '% RPA %'
  and c75_data between '$dataini' and '$datafin'
  and e60_anousu = e60_anousu";

$result = db_query($sql);

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem RPA no período de '.$mes.' / '.$ano);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca         = 1;
$alt           = 4;
$total_liq     = 0;
$total_anuliq  = 0;
$total_pago    = 0;
$total_anupago = 0;

$pdf->addpage();
$pdf->setfont('arial','b',8);
$pdf->cell(15,$alt,'UNIDADE',1,0,"C",1);
$pdf->cell(15,$alt,'PR0J/ATIV',1,0,"C",1);
$pdf->cell(30,$alt,'CONTA',1,0,"C",1);
$pdf->cell(15,$alt,'RECURSO',1,0,"C",1);
$pdf->cell(20,$alt,'EMPENHO',1,0,"C",1);
$pdf->cell(70,$alt,'NOME',1,0,"C",1);
$pdf->cell(30,$alt,'VALOR',1,1,"C",1);
$total = 0;
$troca = 0;
$pdf->ln(2);
for($x=0;$x<pg_numrows($result);$x++){

  db_fieldsmemory($result,$x);
  $pdf->setfont('arial','',8);
  $pdf->cell(15,$alt,db_formatar($o41_orgao,'orgao').db_formatar($o41_unidade,'orgao'),0,0,"C",0);
  $pdf->cell(15,$alt,$o58_projativ,0,0,"C",0);
  $pdf->cell(30,$alt,$c60_estrut,0,0,"C",0);
  $pdf->cell(15,$alt,$o58_codigo,0,0,"C",0);
  $pdf->cell(20,$alt,$e60_codemp,0,0,"C",0);
  $pdf->cell(70,$alt,$z01_nome,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($c70_valor,'f'),0,1,"R",0);
  $pdf->ln(4);
  $total_liq += $c70_valor;
}
$pdf->cell(15,$alt,'TOTAL',0,0,"L",0);
$pdf->cell(150,$alt,'',0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_liq,'f'),0,1,"R",0);

$pdf->Output();