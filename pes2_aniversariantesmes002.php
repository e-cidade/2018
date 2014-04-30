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
// db_postmemory($HTTP_GET_VARS,2);exit;

$head3 = "RELATÓRIO DE ANIVERSARIANTES";
$head5 = "MÊS  : ".$mes_ani." - ".strtoupper(db_mes($mes_ani)) ;

$dbwhereativo = " and (rh30_vinculo = 'A' ";
if(isset($listainativo)){
  $dbwhereativo .= " or rh30_vinculo = 'I' ";
}
if(isset($listapens)){
  $dbwhereativo .= " or rh30_vinculo = 'P' ";
}
$dbwhereativo.= ") ";

$xwhere = "";
if(trim($mes_ani) != ''){
  $xwhere = " and date_part('month',rh01_nasc) = ".$mes_ani;
}
$sql = "
select z01_nome, 
       rh01_regist, 
       rh01_nasc, 
       rh02_lota, 
       rh26_orgao,
       o40_descr,
       o40_orgao,
       rpad(trim(coalesce(z01_ender,''))||' '||coalesce(coalesce(z01_numero,0)::char(4),'')||' '||trim(coalesce(z01_compl,'')),70,' ') as z01_ender,
       rpad(substr(coalesce(z01_bairro,''),1,20),20,' ') as z01_bairro ,
       date_part('month',rh01_nasc) as mes
from rhpessoalmov 
     inner join rhpessoal on rh01_regist = rh02_regist 
     inner join cgm       on z01_numcgm = rh01_numcgm 
     inner join rhregime       on rh30_codreg = rh02_codreg
		                          and rh30_instit = rh02_instit
     left join rhpesrescisao   on rh02_seqpes = rh05_seqpes 
     left join rhlota          on r70_codigo = rh02_lota 
		                          and r70_instit = rh02_instit 
     left join rhlotaexe       on r70_codigo = rh26_codigo
                               and rh26_anousu = rh02_anousu
     left join orcorgao        on rh26_orgao  = o40_orgao
                               and o40_anousu = rh26_anousu
															 and o40_instit = rh02_instit
                               and o40_orgao between $lotaini and $lotafim
where rh02_anousu = $ano
	and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")." 
  and rh05_recis is null 
  $xwhere
  $dbwhereativo
order by o40_orgao,z01_nome;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca      = 1;
$alt        = 4;
$total_fun  = 0;
$xorgao     = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($xorgao != $o40_orgao){
     $troca = 1;
     $xorgao = $o40_orgao;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(70,$alt,'ENDERECO ',1,0,"C",1);
      $pdf->cell(30,$alt,'BAIRRO',1,0,"C",1);
      $pdf->cell(20,$alt,'DT. NASC.',1,1,"C",1);
			if ($o40_orgao  ==  '' || $o40_descr == ''){

			  $pdf->cell(0,7,'SEM LOTAÇÃO DEFINIDA',0,1,"L",0);
			}else {
			  $pdf->cell(0,7,'SECRETARIA :  '.$o40_orgao.' - '.$o40_descr,0,1,"L",0);
			}
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
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(70,$alt,$z01_ender,0,0,"L",$pre);
   $pdf->cell(40,$alt,$z01_bairro,0,0,"L",$pre);
   $pdf->cell(10,$alt,db_formatar($rh01_nasc,'d'),0,1,"R",$pre);
   $total_fun   += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(195,$alt,'TOTAL :  '.$total_fun.' FUNCIONÁRIOS ',"T",0,"C",0);

$pdf->Output();
   
?>