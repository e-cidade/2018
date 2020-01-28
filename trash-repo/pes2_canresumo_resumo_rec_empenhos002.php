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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ponto == 's'){
  $sigla = 'r14';
}elseif($ponto == 'c'){
  $sigla = 'r48';
}elseif($ponto == 'd'){
  $sigla = 'r35';
}elseif($ponto == 'r'){
  $sigla = 'r20';
}elseif($ponto == 'a'){
  $sigla = 'r22';
}
$head2 = "RESUMO DOS EMPENHOS";
$head4 = "PERIODO : ".$mes." / ".$ano;
$head6 = "TIPO    : SALÁRIO";


$sql = "

select rubric,
        tipo,
        descricao,
        recurso,
        descr_recurso,
        round(sum(case 
           when pd = 1 then valor 
           else 0
        end),2) as provento,
        round(sum(case 
           when pd = 2 then valor 
           else 0
        end),2) as desconto
   from ( select rh73_rubric as rubric,
                 case
                   when rh78_sequencial is null then 'e' 
                   else case 
                          when e21_retencaotiporecgrupo = 3 then 'p'
                          when e21_retencaotiporecgrupo = 4 then 'd'
                          when e21_retencaotiporecgrupo = 2 then 'r'
                          else ''
                        end
                 end as tipo,
                 rh27_descr   as descricao,
                 rh72_recurso as recurso,
                 o15_descr    as descr_recurso,
                 rh73_pd      as pd,
                 rh73_valor   as valor
            from rhempenhofolha
                 inner join rhempenhofolharhemprubrica    on rh81_rhempenhofolha        = rhempenhofolha.rh72_sequencial
                 inner join rhempenhofolharubrica         on rh73_sequencial            = rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica
                 inner join rhrubricas                    on rh27_rubric                = rhempenhofolharubrica.rh73_rubric
                                                         and rh27_instit                = rhempenhofolharubrica.rh73_instit
                 left  join rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial
                 left  join retencaotiporec               on e21_sequencial             = rhempenhofolharubricaretencao.rh78_retencaotiporec
                 left  join orctiporec                    on o15_codigo                 = rhempenhofolha.rh72_recurso
           where rh72_anousu   = $ano
             and rh72_mesusu   = $mes
             and rh72_siglaarq = '$sigla'
             and rh27_pd != 3

        union all

          select rh73_rubric as rubric,
                 case
                   when rh78_sequencial is null then 'Slip' 
                   else case 
                          when e21_retencaotiporecgrupo = 3 then 'p'
                          when e21_retencaotiporecgrupo = 4 then 'd'
                          when e21_retencaotiporecgrupo = 2 then 'r'
                          else ''
                        end
                 end as tipo,
                 rh27_descr   as descricao,
                 rh79_recurso as recurso,
                 o15_descr    as descr_recurso,
                 rh73_pd      as pd,
                 rh73_valor   as valor
            from rhslipfolha
                 inner join rhslipfolharhemprubrica       on rhslipfolharhemprubrica.rh80_rhslipfolha                 = rhslipfolha.rh79_sequencial
                 inner join rhempenhofolharubrica         on rhempenhofolharubrica.rh73_sequencial                    = rhslipfolharhemprubrica.rh80_rhempenhofolharubrica
                 inner join rhrubricas                    on rhrubricas.rh27_rubric                                   = rhempenhofolharubrica.rh73_rubric
                                                         and rhrubricas.rh27_instit                                   = rhempenhofolharubrica.rh73_instit
                 left  join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial
                 left  join retencaotiporec               on retencaotiporec.e21_sequencial                           = rhempenhofolharubricaretencao.rh78_retencaotiporec
                 left  join orctiporec                    on orctiporec.o15_codigo                                    = rhslipfolha.rh79_recurso
           where rh79_anousu   = $ano
             and rh79_mesusu   = $mes
             and rh79_siglaarq = '$sigla'
             and rh27_pd !=  3";
if($ponto == 's'){
$sql .= "
          union all

          select rh27_rubric, 
                 '',
                 rh27_descr,
                 rh25_recurso,
                 o15_descr,
                 r14_pd,
                 r14_valor                 
            from gerfsal 
                 inner join rhrubricas                    on r14_rubric     = rh27_rubric 
                                                         and r14_instit     = rh27_instit  
                 left  join rhrubretencao                 on rh27_rubric    = rh75_rubric 
                                                         and rh27_instit    = rh75_instit 
                 left  join rhrubelemento                 on rh27_rubric    = rh23_rubric 
                                                         and rh27_instit    = rh23_instit 
                 left  join rhlotavinc                    on r14_lotac::int = rh25_codigo 
                                                         and r14_anousu     = rh25_anousu 
                 left  join orctiporec                    on o15_codigo     = rh25_recurso
           where rh27_pd != 3 
             and rh27_instit = ".db_getsession('DB_instit')."
             and rh75_rubric is null 
             and rh23_rubric is null 
             and r14_anousu = $ano 
             and r14_mesusu = $mes ";
}             
$sql .= "
             ) as x
    group by rubric,
             tipo,
             descricao,
             recurso,
             descr_recurso
    order by recurso,rubric;

";
//echo $sql;exit;
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$empenhos    = 0;
$pagametos   = 0;
$retencoes   = 0;
$devolucoes  = 0;
$outros      = 0;
$prov        = 0;
$desc        = 0;
$liq         = 0;
$t_empenhos  = 0;
$t_pagametos = 0;
$t_retencoes = 0;
$t_devolucoes= 0;
$t_outros    = 0;
$t_prov      = 0;
$t_desc      = 0;
$t_liqu      = 0;
$xsec = '';
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($xsec != $recurso){
	 //echo "<br> xsec --> $xsec   tipo --> $tipo ";
   //  $pdf->addpage();
//   $pdf->setfont('arial','b',8);
//   $pdf->cell(15,$alt,$recurso.' - '.$descr_recurso,0,1,"L",0);
     $troca = 1;
     $xsec = $recurso;
		 if($x != 0 ){
       $pdf->setfont('arial','b',8);
       $pdf->ln(3);
       $pdf->cell(85,6,' ',"T",0,"L",0);
       $pdf->cell(30,6,db_formatar($prov,'f'),"T",0,"R",0);
       $pdf->cell(30,6,db_formatar($desc,'f'),"T",1,"R",0);
       $pdf->cell(115,4,'TOTAL DE EMPENHOS  :    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar($empenhos,'f'),0,1,"R",0);
       $pdf->cell(115,4,'TOTAL DE PAG.EXTRA :    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar($pagamentos,'f'),0,1,"R",0);
       $pdf->cell(115,4,'TOTAL DE RETENCOES :    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar($retencoes,'f'),0,1,"R",0);
       $pdf->cell(115,4,'TOTAL DE DEVOLUCOES:    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar($devolucoes,'f'),0,1,"R",0);
       $pdf->cell(115,4,'TOTAL DE OUTROS :    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar($outros,'f'),0,1,"R",0);
       $pdf->cell(115,4,'LÍQUIDO :    ',0,0,"R",0);
       $pdf->cell(30,4,db_formatar( ( $empenhos + $pagamentos - $retencoes + $outros + $devolucoes ) ,'f'),0,1,"R",0);
       $total = 0;
       $empenhos   = 0;
       $pagamentos = 0;
       $retencoes  = 0;
       $devolucoes = 0;
       $outros     = 0;
       $prov       = 0;
       $desc       = 0;
       $liqu       = 0;
		 }
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(70,$alt,'DESCRICAO',1,0,"C",1);
      $pdf->cell(30,$alt,'PROVENTO',1,0,"C",1);
      $pdf->cell(30,$alt,'DESCONTO',1,1,"C",1);
			$pdf->ln(3);
      $pdf->cell(0,$alt,$recurso.' - '.$descr_recurso,0,1,"L",0);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$tipo.'-'.$rubric,0,0,"C",$pre);
   $pdf->cell(70,$alt,$descricao,0,0,"L",$pre);
   $pdf->cell(30,$alt,db_formatar($provento,'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($desconto,'f'),0,1,"R",$pre);
   if($tipo == 'e'){
     if($provento > 0){
       $empenhos   += $provento;
       $t_empenhos += $provento;
     }else{
       $empenhos   -= $desconto;
       $t_empenhos -= $desconto;
     }
   }elseif($tipo == 'r'){
     if($provento > 0){
       $retencoes   -= $provento;
       $t_retencoes -= $provento;
     }else{
       $retencoes   += $desconto;
       $t_retencoes += $desconto;
     }
   }elseif($tipo == 'p'){
     if($provento > 0){
       $pagamentos   += $provento;
       $t_pagamentos += $provento;
     }else{
       $pagamentos   -= $desconto;
       $t_pagamentos -= $desconto;
     }
   }elseif($tipo == ''){
     if($provento > 0){
       $outros       += $provento;
       $t_outros     += $provento;
     }else{
       $outros       -= $desconto;
       $t_outros     -= $desconto;
     }
   }elseif($tipo == 'd'){
     if($provento > 0){
       $devolucoes   += $provento;
       $t_devolucoes += $provento;
     }else{
       $devolucoes   -= $desconto;
       $t_devolucoes -= $desconto;
     }
   }   
   $prov       += $provento;
   $desc       += $desconto;
}
$pdf->setfont('arial','b',8);
$pdf->ln(3);
$pdf->cell(85,6,' ',"T",0,"L",0);
$pdf->cell(30,6,db_formatar($prov,'f'),"T",0,"R",0);
$pdf->cell(30,6,db_formatar($desc,'f'),"T",1,"R",0);
$pdf->cell(115,4,'TOTAL DE EMPENHOS  :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($empenhos,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL DE PAG.EXTRA :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($pagamentos,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL DE RETENCOES :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($retencoes,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL DE DEVOLUCOES:    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($devolucoes,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL DE OUTROS:    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($outros,'f'),0,1,"R",0);
$pdf->cell(115,4,'LÍQUIDO :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar( ( $empenhos + $pagamentos - $retencoes + $outros + $devolucoes ) ,'f'),0,1,"R",0);



$pdf->setfont('arial','b',8);
$pdf->ln(3);
$pdf->cell(145,6,' ',"T",1,"L",0);
$pdf->cell(115,4,'TOTAL GERAL DE EMPENHOS  :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($t_empenhos,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL GERAL DE PAG.EXTRA :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($t_pagamentos,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL GERAL DE RETENCOES :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($t_retencoes,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL GERAL DE DEVOLUCOES:    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($t_devolucoes,'f'),0,1,"R",0);
$pdf->cell(115,4,'TOTAL GERAL DE OUTROS :   ',0,0,"R",0);
$pdf->cell(30,4,db_formatar($t_outros,'f'),0,1,"R",0);
$pdf->cell(115,4,'LÍQUIDO :    ',0,0,"R",0);
$pdf->cell(30,4,db_formatar( ( $t_empenhos + $t_pagamentos - $t_retencoes + $t_outros + $t_devolucoes ) ,'f'),0,1,"R",0);

$pdf->Output();
?>