<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("libs/db_sql.php");
include("fpdf151/pdf.php");
$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

//db_postmemory($HTTP_SERVER_VARS,2);exit;
//db_postmemory($HTTP_POST_VARS,2);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//if($parcelas == '')
//   $parcelas = 0;

$head2 = 'RELATÓRIO DA POSIÇÃO DOS LOTEAMENTOS';
$head6 = '';

$sql        = "select * from procdiver where dv09_procdiver = ".$procdiver;
$result     = db_query($sql);
$intNumrows = pg_numrows($result);
if( $intNumrows == 0 ){
  
  $sMsg = _M('tributario.diversos.dv02_loteamentos002.nao_existem_registros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

db_fieldsmemory($result,0);
$head4 = $dv09_descr;
$loteamento = $procdiver==221?45:38;

//die("fhakshkldsa");

/*
$sql = "
select k00_matric,z01_numcgm,z01_nome,k00_numpre 
from loteam a 
     inner join loteloteam b on a.j34_loteam=b.j34_loteam 
     inner join iptubase on j01_idbql=b.j34_idbql 
     left outer join
     select k00_matric,
            k00_numpre,
            numtot,
            sum(case when k00_dtvenc <  current_date then 1 else 0 end) as vencidas,
            sum(case when k00_dtvenc >= current_date then 1 else 0 end) as emdia
     from (select distinct k00_matric,
     		      d.z01_numcgm,
		      d.z01_nome,
                      a.k00_numpre,
                      k00_numpar,
                      k00_dtvenc,
                      dv05_numtot as numtot
           from arrecad a
                inner join diversos on a.k00_numpre = dv05_numpre
                inner join arrematric b on b.k00_numpre = dv05_numpre
	        inner join proprietario_nome d on j01_matric = k00_matric
           where dv05_procdiver = $procdiver ) x
           group by k00_matric, k00_numpre, numtot
     
where j01_baixa is null and a.j34_loteam = $loteamento
order by k00_matric, z01_numcgm, z01_nome, k00_numpre  
";


$sql = "select  k00_matric,z01_numcgm,z01_nome,k00_numpre,numtot,vencidas,emdia,
                substr(fc_debitos_numpre, 0,16)::float8 as vlrhis,
                substr(fc_debitos_numpre,17,16)::float8 as vlrcor,
                substr(fc_debitos_numpre,33,16)::float8 as vlrjuros,
                substr(fc_debitos_numpre,49,16)::float8 as vlrmulta,
                substr(fc_debitos_numpre,65,16)::float8 as vlrdesconto,
                substr(fc_debitos_numpre,81,16)::float8 as total from 
(select y.*,fc_debitos_numpre(k00_numpre,0,current_date, to_char(current_date(),'YYYY'),'t','f') from 
(select k00_matric,
       z01_numcgm,
       z01_nome,
       k00_numpre,
       numtot,
       sum(case when k00_dtvenc <  current_date then 1 else 0 end) as vencidas,
       sum(case when k00_dtvenc >= current_date then 1 else 0 end) as emdia
from (select distinct k00_matric,
  		      d.z01_numcgm,
		      d.z01_nome,
                      a.k00_numpre,
                      k00_numpar,
                      k00_dtvenc,
                      dv05_numtot as numtot
      from arrecad a
           inner join diversos on a.k00_numpre = dv05_numpre
           inner join arrematric b on b.k00_numpre = dv05_numpre
	   inner join proprietario_nome d on j01_matric = k00_matric
      where dv05_procdiver = $procdiver ) x
group by k00_matric, z01_numcgm, z01_nome, k00_numpre, numtot
order by k00_matric, z01_numcgm, z01_nome, k00_numpre limit 30) y) w
";
*/
$sql = "
select j01_matric,
       z01_numcgm,
       z01_nome,
       k00_numpre,
       coalesce(vencidas) as vencidas,
       coalesce(numtot) as numtot,
       coalesce(emdia) as emdia
from loteam a
     inner join loteloteam b on a.j34_loteam=b.j34_loteam
     inner join proprietario_nome d  on b.j34_idbql = d.j01_idbql
     left outer join (select k00_matric,
                             k00_numpre,
                             numtot,
                             sum(case when k00_dtvenc < current_date then 1 else 0 end) as vencidas,
                             sum(case when k00_dtvenc >= current_date then 1 else 0 end) as emdia
                      from (select distinct k00_matric,
                                            a.k00_numpre,
                                            k00_numpar,
                                            k00_dtvenc,
                                            dv05_numtot as numtot
                            from arrecad a
                                 inner join diversos on a.k00_numpre = dv05_numpre and dv05_instit = ".db_getsession('DB_instit')."
                                 inner join arrematric b on b.k00_numpre = dv05_numpre
                            where dv05_procdiver = $procdiver) x
                      group by k00_matric,
                               k00_numpre,
                               numtot ) y on y.k00_matric = j01_matric
where j01_baixa is null
  and a.j34_loteam = $loteamento
order by j01_matric,
         z01_numcgm,
         z01_nome,
         k00_numpre ";

//echo $sql;exit;

$result = db_query($sql);
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','B',7);
//$xmatric = 'RL'.trim($matric);
$pag            = 1;

$ttotvalor       = 0;
$ttotvlrhis      = 0;
$ttotvlrcor      = 0;
$ttotvlrjuros    = 0;
$ttotvlrmulta    = 0;
$ttotvlrdesconto = 0;
$ttotvlrvencidas = 0;
$ttotvlrpagas    = 0;

$totparcelas     = 0;
$totvencidas     = 0;
$totemdia        = 0;
$totvalor        = 0;
$totvlrhis       = 0;
$totvlrcor       = 0;
$totvlrjuros     = 0;
$totvlrmulta     = 0;
$totvlrdesconto  = 0;
$totvlrvencidas  = 0;
$totvlrpagas     = 0;

$totreg          = 0;
$prenc           = 1;

db_fieldsmemory($result,0);
$matricula = $j01_matric;

//$totvalor    = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,'k00_numpre','');
//echo pg_numrows($totvalor);
//db_fieldsmemory($totvalor,0,true,true);exit;

for ($x = 0 ; $x < pg_numrows($result);$x++){
//for ($x = 0 ; $x < 20;$x++){
  db_fieldsmemory($result,$x);
  if (($pdf->gety() > $pdf->h - 30) || $pag == 1){
     $pdf->SetFont('Arial','B',7);
     $pdf->addpage("L");
     $pdf->Cell(12,5,'Matrícula',1,0,"C",1);
     $pdf->Cell(60,5,$RLz01_nome,1,0,"C",1);
     $pdf->Cell(18,5,'Tot.Parcelas',1,0,"C",1);
     $pdf->cell(15,5,'Corrigido',1,0,"C",1);
     $pdf->cell(15,5,'Juros',1,0,"C",1);
     $pdf->cell(15,5,'Multa',1,0,"C",1);
     $pdf->cell(15,5,'Desconto',1,0,"C",1);
     $pdf->cell(15,5,'Total',1,0,"C",1);
     $pdf->Cell(12,5,'Vencidas',1,0,"C",1);
     $pdf->Cell(20,5,'Valor vencidas',1,0,"C",1);
     $pdf->Cell(10,5,'Pagas',1,0,"C",1);
     $pdf->Cell(20,5,'Valor pagas',1,1,"C",1);
     $pag = 0;
  }
  if ($matricula != $j01_matric){
    
      if ($prenc == 1)
	 $prenc = 0;
      else
	 $prenc = 1;
	 
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(12,5,$matricula,0,0,"C",$prenc);
      $pdf->Cell(60,5,$nome,0,0,"L",$prenc);
      $pdf->Cell(18,5,$totparcelas,0,0,"R",$prenc);
      $pdf->cell(15,5,db_formatar($totvlrcor,'f'),0,0,"R",$prenc);
      $pdf->cell(15,5,db_formatar($totvlrjuros,'f'),0,0,"R",$prenc);
      $pdf->cell(15,5,db_formatar($totvlrmulta,'f'),0,0,"R",$prenc);
      $pdf->cell(15,5,db_formatar($totvlrdesconto,'f'),0,0,"R",$prenc);
      $pdf->cell(15,5,db_formatar($totvalor,'f'),0,0,"R",$prenc);
      $pdf->Cell(12,5,db_formatar($totvencidas,'s'),0,0,"R",$prenc);
      $pdf->cell(20,5,db_formatar($totvlrvencidas,'f'),0,0,"R",$prenc);
      $pdf->Cell(10,5,db_formatar($totparcelas-$totvencidas-$totemdia,'s'),0,0,"R",$prenc);
      $pdf->cell(20,5,db_formatar($totvlrpagas,'f'),0,1,"R",$prenc);

      $totparcelas    = 0;
      $totvencidas    = 0;
      $totemdia       = 0;
      $totvalor       = 0;
      $totvlrvencidas = 0;
      $totvlrpagas    = 0;
      $totvlrhis      = 0;
      $totvlrcor      = 0;
      $totvlrjuros    = 0;
      $totvlrmulta    = 0;
      $totvlrdesconto = 0;
      $totvalor       = 0;
      $matricula      = $j01_matric;
      $totreg        += 1;
			      
  }else{
  }
  if ($k00_numpre != ''){
     $xtotvalor       = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,'k00_numpre','');
     db_fieldsmemory($xtotvalor,0);
     $sqlvencidas = "select case when trim(substr(vencidos,82,15))='' then '0' else substr(vencidos,82,15)::float8 end as total_vencidos from (select fc_debitos_numpre($k00_numpre,0,current_date," . db_getsession("DB_anousu") . ",'t','t') as vencidos) as x";
     $result_vencidas = db_query($sqlvencidas);
		 if ($result_vencidas==false){
			 die($sqlvencidas);
		 }
		 /*
		 if (pg_numrows($result_vencidas)==0){
			 continue;
		 }
		 */
     db_fieldsmemory($result_vencidas,0);
     $sqlpagas = "select sum(k00_valor) as total_pagas from arrepaga where k00_numpre = $k00_numpre";
     $result_pagas = db_query($sqlpagas);
     db_fieldsmemory($result_pagas,0);

     $ttotvlrhis      += $vlrhis;
     $ttotvlrcor      += $vlrcor;
     $ttotvlrjuros    += $vlrjuros;
     $ttotvlrmulta    += $vlrmulta;
     $ttotvlrdesconto += $vlrdesconto;
     $ttotvalor       += $total;
     $ttotvlrvencidas += $total_vencidos;
     $ttotvlrpagas    += $total_pagas;

     $totvlrhis       += $vlrhis;
     $totvlrcor       += $vlrcor;
     $totvlrjuros     += $vlrjuros;
     $totvlrmulta     += $vlrmulta;
     $totvlrdesconto  += $vlrdesconto;
     $totvalor        += $total;
     $totvlrvencidas  += $total_vencidos;
     $totvlrpagas     += $total_pagas;
     $totparcelas     += $numtot;
     $totvencidas     += $vencidas;
     $totemdia        += $emdia;
  }
//  $matricula        = $j01_matric;
  $nome             = $z01_nome;
  
}	

if ($prenc == 1)
   $prenc = 0;
else
   $prenc = 1;

$pdf->SetFont('Arial','',7);
$pdf->Cell(12,5,$matricula,0,0,"C",$prenc);
$pdf->Cell(60,5,$nome,0,0,"L",$prenc);
$pdf->Cell(18,5,$totparcelas,0,0,"R",$prenc);
$pdf->cell(15,5,db_formatar($totvlrcor,'f'),0,0,"R",$prenc);
$pdf->cell(15,5,db_formatar($totvlrjuros,'f'),0,0,"R",$prenc);
$pdf->cell(15,5,db_formatar($totvlrmulta,'f'),0,0,"R",$prenc);
$pdf->cell(15,5,db_formatar($totvlrdesconto,'f'),0,0,"R",$prenc);
$pdf->cell(15,5,db_formatar($totvalor,'f'),0,0,"R",$prenc);
$pdf->Cell(12,5,db_formatar($totvencidas,'s'),0,0,"R",$prenc);
$pdf->cell(20,5,db_formatar($totvlrvencidas,'f'),0,0,"R",$prenc);
$pdf->Cell(10,5,db_formatar($totparcelas-$totvencidas-$totemdia,'s'),0,0,"R",$prenc);
$pdf->cell(20,5,db_formatar($totvlrpagas,'f'),0,1,"R",$prenc);
$totreg += 1;

$pdf->SetFont('Arial','B',7);
$pdf->Cell(90,5,'TOTAL DE REGISTROS:'.$totreg,"T",0,"C",0);
$pdf->cell(15,5,db_formatar($ttotvlrcor,'f'),"T",0,"R",0);
$pdf->cell(15,5,db_formatar($ttotvlrjuros,'f'),"T",0,"R",0);
$pdf->cell(15,5,db_formatar($ttotvlrmulta,'f'),"T",0,"R",0);
$pdf->cell(15,5,db_formatar($ttotvlrdesconto,'f'),"T",0,"R",0);
$pdf->cell(15,5,db_formatar($ttotvalor,'f'),"T",0,"R",0);
$pdf->cell(12,5,"","T",0,"R",0);
$pdf->cell(20,5,db_formatar($ttotvlrvencidas,'f'),"T",0,"R",0);
$pdf->cell(10,5,"","T",0,"R",0);
$pdf->cell(20,5,db_formatar($ttotvlrpagas,'f'),"T",1,"R",0);

$pdf->Output();

?>