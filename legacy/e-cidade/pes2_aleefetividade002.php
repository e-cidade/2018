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
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "EFETIVIDADE REFERENTE A ".$mes." / ".$ano;
$head5 = "INTERVALO: ".$secini." at ".$secfin;


$sql = "

select rh01_regist,
       z01_nome,
       case when rh55_estrut is null then '0' else rh55_estrut end  as rh55_estrut ,
       case when rh55_descr  is null then 'SEM LOCAL CADASTRADO' else rh55_descr end  as rh55_descr,
       case when r45_dtafas  is null then '' else 'AFASTADO' end as afastado,
       rh37_descr,
       rh30_regime
from rhpessoalmov 
     inner join rhpessoal on rh01_regist = rh02_regist 
     inner join cgm            on rh01_numcgm = z01_numcgm
     left  join rhpesrescisao  on rh05_seqpes = rh02_seqpes
     inner join rhregime       on rh30_codreg = rh02_codreg
                              and rh30_vinculo= 'A' 
															and rh30_instit = rh02_instit
     inner join rhfuncao       on rh37_funcao = rh01_funcao 
		                          and rh37_instit = rh02_instit
     left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
     left join  rhlocaltrab    on rh55_codigo = rh56_localtrab
		                          and rh55_instit = rh02_instit
     left join  afasta         on r45_regist = rh01_regist
                              and r45_anousu = rh02_anousu
                              and r45_mesusu = rh02_mesusu
                              and (r45_dtreto > '$ano-$mes-01' or r45_dtreto is null)

where rh02_anousu = $ano 
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and rh56_princ = 't'
  and rh05_recis is null
  and rh55_estrut between '$secini' and '$secfin'
order by rh55_estrut,z01_nome     

       ";
//	echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=No existem funcionarios cadastrados no intervalo para o perodo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 9;
$pdf->setleftmargin(5);

$orgao 	= 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($orgao != $rh55_estrut){
      if($x != 0){
        $pdf->setfont('arial','b',8);
        $pdf->cell(154,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);
      }
      $total = 0;
      $troca = 1;
      $orgao = $rh55_estrut;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $alt = 5;
      $pdf->cell(0,$alt,'LOCAL : '.$rh55_estrut.' - '.$rh55_descr,0,1,"L",0);
      $pdf->ln(4);
      $pdf->cell(12,$alt,'MATRIC','LRT',0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONRIO','LRT',0,"C",1);
      $pdf->cell(18,$alt,'0006','LRT',0,"C",1);
      $pdf->cell(18,$alt,'0005','LRT',0,"C",1);
      $pdf->cell(18,$alt,'0025','LRT',0,"C",1);
//      $pdf->cell(18,$alt,'0184','LRT',0,"C",1);
//      $pdf->cell(18,$alt,'0185','LRT',0,"C",1);
      $pdf->cell(18,$alt,'0079','LRT',0,"C",1);
      $pdf->cell(18,$alt,'0096','LRT',0,"C",1);
      $pdf->cell(18,$alt,'FALTAS','LRT',0,"C",1);
      $pdf->cell(18,$alt,'AT.MDICOS','LRT',0,"C",1);
      $pdf->cell(18,$alt,'LICENCAS','LRT',0,"C",1);
      $pdf->cell(18,$alt,'EXERCCIO','LRT',0,"C",1);
      $pdf->cell(18,$alt,'OBS','LRT',1,"C",1);
	  
      $pdf->setfont('arial','b',6);
      $pdf->cell(12,$alt,'','L',0,"C",1);
      $pdf->cell(60,$alt,'FUNO','L',0,"C",1);
      $pdf->cell(18,$alt,'H.Ext.50%','L',0,"C",1);
      $pdf->cell(18,$alt,'H.Ext.100%','L',0,"C",1);
      $pdf->cell(18,$alt,'Adic.Noturno','L',0,"C",1);
//      $pdf->cell(18,$alt,'Diria 10%','L',0,"C",1);
//      $pdf->cell(18,$alt,'Diria 5%','L',0,"C",1);
      $pdf->cell(18,$alt,'H.Ext.Not.100%','L',0,"C",1);
      $pdf->cell(18,$alt,'H.Ext.Not.50%','L',0,"C",1);
      $pdf->cell(18,$alt,'','L',0,"C",1);
      $pdf->cell(18,$alt,'','L',0,"C",1);
      $pdf->cell(18,$alt,'','L',0,"C",1);
      $pdf->cell(18,$alt,'','L',0,"C",1);
      $pdf->cell(18,$alt,'','LR',1,"C",1);
   	  
      $troca = 0;
   }
   $alt = 5;

   $pdf->setfont('arial','',7);
   $pdf->cell(12,$alt,$rh01_regist,'LRT',0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,'LRT',0,"L",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
//   $pdf->cell(18,$alt,'','LRT',0,"C",0);
//   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,$afastado,'LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',0,"C",0);
   $pdf->cell(18,$alt,'','LRT',1,"C",0);
       
   $pdf->setfont('arial','b',6);
   $pdf->cell(12,$alt,'','LB',0,"C",0);
   $pdf->cell(60,$alt,$rh37_descr,'LB',0,"L",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
//   $pdf->cell(18,$alt,'','LB',0,"C",0);
//   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LB',0,"C",0);
   $pdf->cell(18,$alt,'','LBR',1,"C",0);

   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(154,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);

$pdf->Output();
   
?>