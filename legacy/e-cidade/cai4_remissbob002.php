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

include("libs/db_conn.php");
require("libs/db_stdlib.php");
include("libs/db_sql.php");

define('FPDF_FONTPATH','fpdf151/font/');
require('fpdf151/fpdf.php');
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(!isset($DB_login)) {
  parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
}
if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  echo "Erro(12) ao tentar conectar no servidor.";
  exit;
}

//////  REEMISSÃO DA BOBINA
$result = db_query("
select distinct cfa.k11_ipterm,cfa.k11_local,(case when cn.k12_numpre is not null then cn.k12_numpre::bpchar else (case when cl.k12_autent is not null then cl.k12_autent::bpchar else (case when ce.k12_empen is not null then ce.k12_empen::bpchar end) end) end) as codigo,cfa.k11_ident1,cfa.k11_ident2,cfa.k11_ident3,to_char(c.k12_data,'DD-MM-YYYY') as data,c.k12_autent,c.k12_conta,c.k12_valor
from corrente c
left outer join cornump cn
on cn.k12_id = c.k12_id
and cn.k12_data = c.k12_data
and cn.k12_autent = c.k12_autent
left outer join corlanc cl
on cl.k12_id = c.k12_id
and cl.k12_data = c.k12_data
and cl.k12_autent = c.k12_autent
left outer join coremp ce
on ce.k12_id = c.k12_id
and ce.k12_data = c.k12_data
and ce.k12_autent = c.k12_autent
inner join cfautent cfa
on cfa.k11_id = c.k12_id
where c.k12_instit = " . db_getsession('DB_instit') . " and
c.k12_data = '$data'
and c.k12_id = $id
order by c.k12_autent");
$numrows = pg_numrows($result);
if($numrows == 0) {
  echo "<script>window.opener.alert('Nenhuma autenticação encontrada.');window.close();</script>\n";
  exit;
}
/*******
$Tampag = (string)($numrows * 4 + 20);
//$Tampag = 100;
$pag[0] = "70";
$pag[1] = $Tampag;
$pdf = new FPDF("P","mm",$pag);
*********/
$pdf = new FPDF();
$pdf->Open();
$pdf->AddPage();
$pdf->SetDisplayMode(150);
$pdf->SetMargins(5,1,1,1);
$pdf->SetAutoPageBreak(false);
$pdf->SetXY(5,5);
$pdf->SetFont('Arial','',7);
$pdf->Cell(59,13,"","TB",1,"C",0);
$pdf->Text(23,8,"Reemissão da Bobina");
$pdf->Text(23,11,"ID:    ".$id);
$pdf->Text(23,14,"IP:    ".pg_result($result,0,"k11_ipterm"));
$pdf->Text(23,17,"Local: ".pg_result($result,0,"k11_local"));
$pdf->SetXY(5,20);
for($i = 0;$i < $numrows;$i++) {
  $pdf->Cell(5,3,pg_result($result,$i,"k12_autent"),0,0,"L",0);
  $pdf->Cell(3,3,pg_result($result,$i,"k11_ident1"),0,0,"L",0);
  $pdf->Cell(3,3,pg_result($result,$i,"k11_ident2"),0,0,"L",0);
  $pdf->Cell(3,3,pg_result($result,$i,"k11_ident3"),0,0,"L",0);
  $pdf->Cell(15,3,pg_result($result,$i,"data"),0,0,"L",0);
  $pdf->Cell(10,3,pg_result($result,$i,"codigo"),0,0,"L",0);
  $pdf->Cell(5,3,pg_result($result,$i,"k12_conta"),0,0,"L",0);
  $pdf->Cell(15,3,number_format(pg_result($result,$i,"k12_valor"),2,".",","),0,1,"R",0);

}

$pdf->Output();