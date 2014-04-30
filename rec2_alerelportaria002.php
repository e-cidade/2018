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

include("fpdf151/impcarne.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//// nome das previdencias
$sql_nome = "select * from tipoasse where h12_assent = '$tipo'; 
	  ";
$res_nome = pg_query($sql_nome);
//db_criatabela($res_nome);
$virg_nome = '';
$descr_nome = '';
for($inome=0;$inome<pg_numrows($res_nome);$inome++){
 db_fieldsmemory($res_nome,$inome);
 $descr_nome .= $virg_nome.$h12_descr;
 $virg_nome   = ', '; 
}

if($dataFim == ''){
  $dataFim = date("Y-m-d");
}

$head2 = 'RLATÓRIO DE PORTARIAS';
$head4 = 'TIPOS   : '.$tipo.'-'.$descr_nome;
$head6 = 'PERÍODO : '.db_formatar($dataIni,'d').' a  '.db_formatar($dataFim,'d');

//echo ' data fim : '.$dataFim;exit;

$sql = "
   select h16_regist,r70_descr,z01_nome, h31_numero,h16_dtconc,h16_dtterm,h16_histor||h16_hist2 as descr 
   from portaria 
        inner join portariaassenta on h31_sequencial = h33_portaria 
        inner join assenta on h16_codigo = h33_assenta 
        inner join rhpessoal on rh01_regist = h16_regist 
        inner join cgm on rh01_numcgm = z01_numcgm 
        left join rhpessoalmov   on rh02_regist = rh01_regist 
                                and rh02_anousu = ".db_anofolha()." 
                                and rh02_mesusu = ".db_mesfolha()." 
        left join rhlota         on r70_codigo  = rh02_lota
                                and r70_instit  = rh02_instit
   where h31_dtportaria between '$dataIni' and '$dataFim'
     and h31_portariatipo = $tipo
   order by z01_nome
";

$result = pg_exec($sql);
//  echo $sql;exit;
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem este tipo de portaria no ano de '.$mes);
}
    
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$tsoma1     = 0;
$tbase1     = 0;
$tded1      = 0;
$tdesco1    = 0;
$tpatronal1 = 0;
$ttotal     = 0;

for($x = 0; $x < $xxnum;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',7);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(15,$alt,'No. ATO',1,0,"C",1);
      $pdf->cell(15,$alt,'INÍCIO',1,0,"C",1);
      $pdf->cell(15,$alt,'FIM',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCR. PORTARIA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$h16_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$r70_descr,0,0,"L",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(15,$alt,$h31_numero,0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($h16_dtconc,'d'),0,0,"R",$pre);
   $pdf->cell(15,$alt,db_formatar($h16_dtterm,'d'),0,0,"R",$pre);
   $pdf->multicell(0,$alt,$descr,0,"L",$pre);
   $ttotal     += 1;
}
if($pre == 1){
  $pre = 0;
}else{
  $pre = 1;
}
$pdf->setfont('arial','B',7);
$pdf->cell(0,$alt,'TOTAL : '.$ttotal.' PORTARIAS',1,0,"L",$pre);

$pdf->Output();
?>