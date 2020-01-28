<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


include ("fpdf151/pdf.php");
include ("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_admiss');

db_postmemory($HTTP_GET_VARS);

$head3 = "FUNCIONÁRIOS DEMITIDOS";
$head5 = "PERÍODO : ".db_formatar($datai, 'd')." até ".db_formatar($dataf, 'd');

$ano = db_anofolha();
$mes = db_mesfolha();

if ($ordem == 'a') {
  $xordem = ' order by z01_nome ';
  $head = 'ORDEM : ALFABÉTICA';
}elseif ($ordem == 'n') {
  $xordem = ' order by r01_regist ';
  $head = 'ORDEM : MATRÍCULA';
} else {
  $xordem = ' order by r01_admiss ';
  $head = 'ORDEM : ADMISSÃO';
}

$sql = "
select rh01_regist,
       z01_nome,
       rh05_recis,
       rh05_causa,
       r59_descr
from rhpesrescisao

       inner join rhpessoalmov  on rh02_anousu = $ano
                               and rh02_mesusu = $mes
                               and rh02_seqpes = rh05_seqpes
                               and rh02_instit = ".db_getsession("DB_instit")."

       inner join rhpessoal     on rh01_regist = rh02_regist

       inner join cgm           on z01_numcgm  = rh01_numcgm

       inner join rhregime      on rh30_codreg = rh02_codreg
                               and rh30_instit = rh02_instit

       inner join rescisao      on r59_anousu  = rh02_anousu
                               and r59_mesusu  = rh02_mesusu
                               and r59_regime  = rh30_regime
                               and r59_causa   = rh05_causa
                               and r59_caub    = rh05_caub::char(2)
                               and r59_instit  = rh02_instit
                               and case when (rhpesrescisao.rh05_recis - rhpessoal.rh01_admiss) > 365
                                        then 'N'
                                   else 'S'
                               end  = rescisao.r59_menos1
where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis between '$datai' and '$dataf' 
$xordem
";

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários demitidos no período de '.db_formatar($datai,"d").' e '.db_formatar($dataf,"d"));

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
$alt = 4;
$pre = 0;
for ($x = 0; $x < pg_numrows($result); $x ++) {
	db_fieldsmemory($result, $x);
	if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
		$pdf->addpage();
		$pdf->setfont('arial', 'b', 8);
		$pdf->cell(15, $alt, 'MATRIC', 1, 0, "C", 1);
		$pdf->cell(60, $alt, 'NOME DO FUNCIONÁRIO', 1, 0, "C", 1);
		$pdf->cell(20, $alt, 'DEMISSÃO', 1, 0, "C", 1);
		$pdf->cell(0, $alt, 'CAUSA', 1, 1, "C", 1);
		$troca = 0;
		$pre = 1;
	}
	if ($pre == 1)
		$pre = 0;
	else
		$pre = 1;
	$pdf->setfont('arial', '', 7);
	$pdf->cell(15, $alt, $rh01_regist, 0, 0, "C", $pre);
	$pdf->cell(60, $alt, $z01_nome, 0, 0, "L", $pre);
	$pdf->cell(20, $alt, db_formatar($rh05_recis, 'd'), 0, 0, "C", $pre);
	$pdf->cell(0, $alt, $rh05_causa." - ".$r59_descr, 0, 1, "L", $pre);
	$total += 1;
}
$pdf->setfont('arial', 'b', 8);
$pdf->cell(0, $alt, 'TOTAL :  '.$total.'  DEMITIDOS', "T", 0, "L", 0);

$pdf->Output();
?>