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

//$ano = 2007;
//$mes = 5;
if($mes == 1){
  $anoant = $ano - 1;
  $mesant = 12;
}else{
  $anoant = $ano;
  $mesant = $mes -1;
}

$head2 = "PERIODO : ".$mes." / ".$ano;
$head3 = "IMPACTO REF. ALTERACAO DE PADRAO/NIVEL";


$sql = "

select rh01_regist,
       z01_nome,
       b.rh03_padrao as padrao_atu,
       e.rh03_padrao as padrao_ant,
       sum(g.r53_valor) as valor_atu,
       sum(h.r53_valor) as valor_ant,
       round(sum(g.r53_valor) - sum(h.r53_valor),2) as diferenca
from rhpessoal 
     inner join cgm on rh01_numcgm = z01_numcgm
     left join rhpessoalmov a  on a.rh02_regist = rh01_regist 
                              and a.rh02_anousu = $ano 
                              and a.rh02_mesusu = $mes
                              and a.rh02_instit = ".db_getsession('DB_instit')."
     inner join rhpespadrao  b on a.rh02_seqpes = b.rh03_seqpes 
     left join rhpesrescisao c on c.rh05_seqpes = a.rh02_seqpes 
     left join rhpessoalmov  d on d.rh02_regist = rh01_regist 
                              and d.rh02_anousu = $anoant 
                              and d.rh02_mesusu = $mesant
                              and d.rh02_instit = ".db_getsession('DB_instit')."
     inner join rhpespadrao  e on d.rh02_seqpes = e.rh03_seqpes 
     left join rhpesrescisao f on f.rh05_seqpes = d.rh02_seqpes
     inner join (select r53_regist,sum(r53_valor) as r53_valor
                 from gerffx  
		 where r53_anousu  = $ano         
		   and r53_mesusu  = $mes
		   and r53_instit  = ".db_getsession('DB_instit')."
                   and r53_pd = 1
                   and r53_rubric not in ('0050','0065','0107','0108','0110','0111','0112','0113','0114','0115','0116','0117','0118','0119','0152','0162','0221',
                                          '0222','0223','0224','0225','0226','0227','0228','0229','0230','0231','0232','0233','0234','0235','0246','0247','0248',
                                          '0290','0291','0292','0421','0422','0423','0424','0425','0426','0427','0428','0429','0430','0431','0432','0500',
					  '0032','0097','0055','0084','0060','0169','0170','0160')
		   group by r53_regist) as g on g.r53_regist  = rh01_regist
     inner join (select r53_regist,sum(r53_valor) as r53_valor
                 from gerffx  
		 where r53_anousu  = $anoant         
		   and r53_mesusu  = $mesant
		   and r53_instit  = ".db_getsession('DB_instit')."
                   and r53_pd = 1
                   and r53_rubric not in ('0050','0065','0107','0108','0110','0111','0112','0113','0114','0115','0116','0117','0118','0119','0152','0162','0221',
                                          '0222','0223','0224','0225','0226','0227','0228','0229','0230','0231','0232','0233','0234','0235','0246','0247','0248',
                                          '0290','0291','0292','0421','0422','0423','0424','0425','0426','0427','0428','0429','0430','0431','0432','0500',
					  '0032','0097','0055','0084','0060','0169','0170','0160')
		   group by r53_regist) as h on h.r53_regist  = rh01_regist
where c.rh05_seqpes is null 
  and b.rh03_padrao <> e.rh03_padrao
group by rh01_regist,z01_nome,b.rh03_padrao,e.rh03_padrao
order by z01_nome
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
$pdf->setfont('arial','b',8);
$troca     = 1;
$total     = 0;
$total_ant = 0;
$total_atu = 0;
$total_dif = 0;
$alt       = 4;
$xsec      = '';
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(70,$alt,'NOME',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR ANT',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR ATU',1,0,"C",1);
      $pdf->cell(25,$alt,'DIFERENCA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($valor_ant,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($valor_atu,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($diferenca,'f'),0,1,"R",$pre);
   $total++;
   $total_ant += $valor_ant;
   $total_atu += $valor_atu;
   $total_dif += $diferenca;
}
$pdf->setfont('arial','b',8);
$pdf->cell(85,$alt,'TOTAL DE FUNCIONARIOS  :  '.$total,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($total_ant,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_atu,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_dif,'f'),"T",1,"R",0);
$pdf->Output();
?>