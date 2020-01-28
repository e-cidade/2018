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
$head5 = "INTERVALO: ".$secini." até ".$secfin;


$sql = "
select rh02_regist,
       z01_nome,
       rh37_descr,
       case rh30_regime
            when 1 then 'Estatutário'
            when 2 then 'CLT'
            else 'Extra-Quadro'
       end as rh30_regime,
       r70_estrut,
       o40_orgao,
       o40_descr,
       rh05_seqpes
from rhpessoalmov
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on rh01_numcgm = z01_numcgm
     inner join rhlota       on r70_codigo  = rh02_lota
		                        and r70_instit  = rh02_instit
     inner join rhfuncao     on rh37_funcao = rh01_funcao  
                            and rh37_instit = rh02_instit
     left  join rhlotaexe    on rh26_codigo = r70_codigo
                            and rh26_anousu = $ano
     left join orcorgao      on o40_orgao   = rh26_orgao
                            and o40_anousu  = $ano
														and o40_instit  = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
		                        and rh30_instit = rh02_instit
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and o40_orgao between $secini and $secfin
  and rh05_seqpes is null 
  and rh30_vinculo = 'A'
order by o40_orgao, z01_nome
       ";
//	echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no intervalo para o período de '.$mes.' / '.$ano);

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
   if ($orgao != $o40_orgao){
   	  if($x != 0){
        $pdf->setfont('arial','b',8);
        $pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);
   	  }
   	  $total = 0;
   	  $troca = 1;
   	  $orgao = $o40_orgao;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $alt = 5;
   	  $pdf->cell(0,$alt,'SECRETARIA : '.$o40_orgao.' - '.$o40_descr,0,1,"L",0);
   	  $pdf->ln(4);
      $pdf->cell(20,$alt,'MATRIC.','LRT',0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO','LRT',0,"C",1);
   	  $pdf->cell(28,$alt,'EFETIVIDADE','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0512','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0256','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0087','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0088','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0089','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0090','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0130','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0526','LRT',0,"C",1);
   	  $pdf->cell(18,$alt,'0286','LRT',0,"C",1);
  	  $pdf->cell(18,$alt,'0310','LRT',1,"C",1);
      $pdf->setfont('arial','b',6);
      $pdf->cell(20,$alt,'','L',0,"C",1);
      $pdf->cell(60,$alt,'','L',0,"C",1);
   	  $pdf->cell(28,$alt,'','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Faltas','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Ajuda de Custo','L',0,"C",1);
   	  $pdf->cell(18,$alt,'H.Extra 50%','L',0,"C",1);
   	  $pdf->cell(18,$alt,'H.Extra 100%','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Dif.H.Ext.50%','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Dif.H.Ext.100%','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Adic.Not.Var.','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Vale Aliment.','L',0,"C",1);
   	  $pdf->cell(18,$alt,'Sal.Conserv.','L',0,"C",1);
 	  $pdf->cell(18,$alt,'Sal.Subst.','LR',1,"C",1);
   	  
      $troca = 0;
   }
   $alt = 6;
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$rh02_regist,'TLR',0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,'TLR',0,"L",0);
   $pdf->cell(28,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',0,"L",0);
   $pdf->cell(18,$alt,'','TLR',1,"L",0);

   $alt = 5;
   $pdf->cell(20,$alt,'','BLR',0,"C",0);
   $pdf->cell(60,$alt,substr($rh37_descr,0,25).'-'.$rh30_regime,'BLR',0,"L",0);
   $pdf->cell(28,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',0,"L",0);
   $pdf->cell(18,$alt,'','BLR',1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);

$pdf->Output();
   
?>