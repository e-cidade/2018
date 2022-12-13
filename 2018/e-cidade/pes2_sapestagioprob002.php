<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('rh01_admiss');
$clrotulo->label('rh37_descr');
$clrotulo->label('r70_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "FUNCIONÁRIOS ADMITIDOS";
$head5 = "PERÍODO : ".db_formatar($datai,'d')." até ".db_formatar($dataf,'d');

$ano = db_anofolha();
$mes = db_mesfolha();

if ($ordem == 'a'){
   $xordem = ' order by z01_nome ';
   $head   = 'ORDEM : ALFABÉTICA';
}elseif ($ordem == 'n'){
   $xordem = ' order by rh01_regist ';
   $head   = 'ORDEM : MATRÍCULA';
}else{
   $xordem = ' order by rh01_admiss ';
   $head   = 'ORDEM : ADMISSÃO';
}

$sql = "select * from
        (
        SELECT rh01_regist,
	       z01_nome,
	       case when rh01_progres is not null then rh01_progres else rh01_admiss end as rh01_admiss,
	       r70_descr,
	       rh37_descr
	FROM   rhpessoal
	       inner join rhpessoalmov on rh02_regist = rh01_regist
				                        and rh02_anousu = $ano
																and rh02_mesusu = $mes
				                        and rh02_instit = ".db_getsession("DB_instit")."
  	     inner join cgm          on rh01_numcgm = z01_numcgm
	       inner join rhfuncao     on rh37_funcao = rh01_funcao
				                        and rh37_instit = rh02_instit
	       inner join rhlota       on r70_codigo  = rh02_lota
				                        and r70_instit  = rh02_instit
	       left join rhpesrescisao on rh05_seqpes = rh02_seqpes
	WHERE  rh05_recis is null
	  and  rh02_codreg in (1,6,2,3,8,4,9,5,10,11,7,4,9,5,10,12,13)
	) as x
	where
   	     rh01_admiss between '$datai' and '$dataf'
	$xordem
       ";

$result = db_query($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários admitidos no período de '.db_formatar($datai).' e '.db_formatar($dataf));
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$pre = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,$RLrh01_admiss,1,0,"C",1);
      $pdf->cell(60,$alt,$RLrh37_descr,1,0,"C",1);
      $pdf->cell(60,$alt,$RLr70_descr,1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }

   if ($pre == 1){
      $pre = 0;
   }else{
      $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",$pre);
   $pdf->cell(60,$alt,$rh37_descr,0,0,"L",$pre);
   $pdf->cell(60,$alt,$r70_descr,0,1,"L",$pre);
   $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(215,$alt,'TOTAL :  '.$total.'  ADMITIDOS',"T",0,"C",0);

$pdf->Output();