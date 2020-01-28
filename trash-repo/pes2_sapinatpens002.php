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

$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_funcao');
$clrotulo->label('r37_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$ano = 2005;
//$mes = 10;


if ($folha == 'r14'){
     $xarquivo = 'DE SALÁRIO';
     $arquivo = 'gerfsal';
}elseif ($folha == 'r35'){
     $xarquivo = 'DE 13o SALÁRIO';
     $arquivo = 'gerfs13';
}
$head3 = "RELATÓRIO DE INATIVOS E PENSIONISTAS";
$head5 = "PERÍODO : ".$mes." / ".$ano;
$head7 = "ARQUIVO: ".$xarquivo;
$sql = "
select rh01_regist as r01_regist,
       z01_nome,
       case when RH30_VINCULO = 'P' THEN 'PENSIONISTA' ELSE 'INATIVO' end as vinculo,
       sum(case when ".$folha."_rubric in ('R918','R919','R920','R921') then ".$folha."_valor else 0 end ) as salfam,
       sum(case when ".$folha."_rubric not in ('R918','R919','R920','R921') then ".$folha."_valor else 0 end ) as prov
from ".$arquivo." 
     inner join rhpessoalmov  on rh02_regist = ".$folha."_regist 
                             and rh02_anousu = ".$folha."_anousu 
            		     and rh02_mesusu = ".$folha."_mesusu 
			     and rh02_instit = ".$folha."_instit
     inner join rhpessoal     on rh01_regist = ".$folha."_regist                        
     left join rhpesrescisao on rh05_seqpes  = rhpessoalmov.rh02_seqpes
     left join rhregime      on rh30_codreg  = rhpessoalmov.rh02_codreg
                            and rh30_instit  = rhpessoalmov.rh02_instit
     inner join cgm      on rh01_numcgm = z01_numcgm 
where ".$folha."_anousu = $ano 
  and ".$folha."_mesusu = $mes
  and ".$folha."_instit = ".db_getsession("DB_instit")."
  and rh02_codreg in (2,3,10)
  and rh05_recis is null
  and ".$folha."_pd != 3
  and ".$folha."_pd = 1
group by rh01_regist,z01_nome,vinculo
order by z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_c  = 0;
$total  = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca   = 1;
$alt     = 4;
$tsalfam = 0;
$tprov   = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(25,$alt,'VINCULO',1,0,"C",1);
      $pdf->cell(20,$alt,'SAL. FAM.',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,1,"C",1);
      $funcao = '';
      $troca = 0;
   }
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(25,$alt,$vinculo,0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($salfam,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($prov,'f'),0,1,"R",0);
   $func    += 1;
   $func_c  += 1;
   $tsalfam += $salfam;
   $tprov   += $prov;
}
$pdf->ln(1);
$pdf->cell(100,$alt,'Total  :  '.$func_c."   Funcionários",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($tsalfam,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($tprov,'f'),0,1,"R",0);


$pdf->Output();
   
?>