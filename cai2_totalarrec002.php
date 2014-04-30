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


include("fpdf151/pdf.php");
db_postmemory($HTTP_POST_VARS);
include("libs/db_sql.php");
db_postmemory($HTTP_SERVER_VARS);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head3 = "RELATÓRIO DO TOTAL DAS ARRECADAÇÕES";
$head5 = "PERÍODO DE ".db_formatar($datai,'d')." A ".db_formatar($dataf,'d');
$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','B',11);
if ( $simnao != 't' ){
   $data = 'dtarquivo, ';
}else{
   $data = '';
}
/////// pagto issqn fixo
 $sql = "
select a.codret,
       a.k15_codbco,
       a.k15_codage,
       dtarquivo,
       coalesce(matricula,0) as matricula ,
       coalesce(inscricao,0) as inscricao,
       coalesce(cgm,0)-coalesce(matricula,0)-coalesce(inscricao,0) as somcgm,
       (coalesce(cgm,0)-coalesce(matricula,0)-coalesce(inscricao,0)) + coalesce(matricula,0) + coalesce(inscricao,0) as total
from (select k15_codbco,k15_codage,codret,count(*) as total
      from disbanco
      group by k15_codbco,k15_codage,codret) as a
     inner join disarq on a.codret = disarq.codret
     left outer join
                    (select codret,count(distinct b.k00_numpre||k00_numpar) as matricula
                     from disbanco a
                          inner join arrematric b on a.k00_numpre = b.k00_numpre
                     group by codret) as matr
          on a.codret = matr.codret
     left outer join
                    (select codret,count(distinct c.k00_numpre||k00_numpar) as inscricao
                     from disbanco a
                          inner join arreinscr c on a.k00_numpre = c.k00_numpre
                     group by codret) as inscr
          on a.codret = inscr.codret
     left outer join
                    (select d.codret, 
		    	    count(distinct d.k00_numpre||k00_numpar) as cgm
                     from (
			  select codret, max(k00_numcgm) from disbanco a
			      inner join arrenumcgm d on a.k00_numpre = d.k00_numpre
			      group by codret
			    ) as x
		     inner join disbanco d on d.codret = x.codret
                     group by d.codret) as cgm
          on a.codret = cgm.codret
     left outer join
                    (select codret,count(distinct k00_numpre||k00_numpar) as geral
                     from disbanco a
                     group by codret) as geral
          on a.codret = geral.codret
where dtarquivo between '$datai' and '$dataf'
order by $data a.k15_codbco,k15_codage,codret";
//die($sql);
$result = pg_exec($sql);
$num = pg_numrows($result);
if ( $num == 0 )
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe movimento para o período '.db_formatar($datai,'d').' a '.db_formatar($dataf,'d'));

$pdf->SetFont('Arial','B',9);
//$pdf->ln(2);
$imposto = "ALVARÁ";
//$pdf->Cell(125,4,"PAGAMENTOS DE ALVARÁ","T",1,"L",0);
//$pdf->ln(2);
$linha     = 60;
$pre 	   = 0;
$tsomcgm   = 0;
$tiptu     = 0;
$tissqn    = 0;
$ttotal    = 0;
$ttsomcgm  = 0;
$ttiptu    = 0;
$ttissqn   = 0;
$tttotal   = 0;
$pagina    = 0;
$data 	   = pg_result($result,0,"dtarquivo");
$banco     = pg_result($result,0,"k15_codbco");
$pdf->SetFont('Arial','B',7);
$pdf->Cell(15,6,"COD.ARQ",1,0,"C",1);
$pdf->Cell(15,6,"BANCO",1,0,"C",1);
$pdf->Cell(15,6,"AGENCIA",1,0,"C",1);
$pdf->Cell(20,6,"DATA",1,0,"C",1);
$pdf->Cell(15,6,"CGM",1,0,"C",1);
$pdf->Cell(15,6,"IPTU",1,0,"C",1);
$pdf->Cell(15,6,"ISSQN",1,0,"C",1);
$pdf->Cell(15,6,"TOTAL",1,1,"C",1);
for($i=0;$i<$num;$i++) {
   db_fieldsmemory($result,$i);
   if ( ( $banco != $k15_codbco ) ){//:w&& ( $data != $dtarquivo ) ){
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(65,4,"TOTAL DO BANCO : ",0,0,"L",1);
      $pdf->Cell(15,4,$tsomcgm,0,0,"R",1);
      $pdf->Cell(15,4,$tiptu,0,0,"R",1);
      $pdf->Cell(15,4,$tissqn,0,0,"R",1);
      $pdf->Cell(15,4,$ttotal,0,1,"R",1);
      $pdf->ln(5);
      $tsomcgm   = 0;
      $tiptu  = 0;
      $tissqn = 0;
      $ttotal = 0;
   }
   if($pdf->GetY() > ( $pdf->h - 30 )){
//   if($linha++>45){
      $linha = 0;
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(15,6,"COD.ARQ",1,0,"C",1);
      $pdf->Cell(15,6,"BANCO",1,0,"C",1);
      $pdf->Cell(15,6,"AGENCIA",1,0,"C",1);
      $pdf->Cell(20,6,"DATA",1,0,"C",1);
      $pdf->Cell(15,6,"CGM",1,0,"C",1);
      $pdf->Cell(15,6,"IPTU",1,0,"C",1);
      $pdf->Cell(15,6,"ISSQN",1,0,"C",1);
      $pdf->Cell(15,6,"TOTAL",1,1,"C",1);
      $pagina = $pdf->PageNo();
   }
//   if($linha % 2 == 0){
//     $pre = 0;
//   }else {
//     $pre = 1;
//   }
   $pdf->SetFont('Arial','',7);
   $pdf->cell(15,4,$codret,0,0,"L",$pre);
   $pdf->cell(15,4,$k15_codbco,0,0,"R",$pre);
   $pdf->cell(15,4,$k15_codage,0,0,"L",$pre);
   $pdf->cell(20,4,db_formatar($dtarquivo,'d'),0,0,"R",$pre);
   $pdf->cell(15,4,$somcgm,0,0,"R",$pre);
   $pdf->cell(15,4,$matricula,0,0,"R",$pre);
   $pdf->cell(15,4,$inscricao,0,0,"R",$pre);
   $pdf->cell(15,4,$total,0,1,"R",$pre);
   $tsomcgm   	+= $somcgm;
   $tiptu  	+= $matricula;
   $tissqn 	+= $inscricao;
   $ttotal 	+= $total;
   $ttiptu  	+= $matricula;
   $ttsomcgm 	+= $somcgm;
   $ttissqn 	+= $inscricao;
   $tttotal 	+= $total;
   $banco 	= $k15_codbco; 
}
$pdf->SetFont('Arial','B',7);
$pdf->Cell(65,4,"TOTAL DO BANCO : ",0,0,"L",1);
$pdf->Cell(15,4,$tsomcgm,0,0,"R",1);
$pdf->Cell(15,4,$tiptu,0,0,"R",1);
$pdf->Cell(15,4,$tissqn,0,0,"R",1);
$pdf->Cell(15,4,$ttotal,0,1,"R",1);
$pdf->ln(5);
$pdf->Cell(65,4,"TOTAL : ",1,0,"L",1);
$pdf->Cell(15,4,$ttsomcgm,1,0,"R",1);
$pdf->Cell(15,4,$ttiptu,1,0,"R",1);
$pdf->Cell(15,4,$ttissqn,1,0,"R",1);
$pdf->Cell(15,4,$tttotal,1,1,"R",1);

$pdf->Output();

?>