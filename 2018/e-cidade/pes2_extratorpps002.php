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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

$head3 = "CADASTRO DE CÓDIGOS";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$anousu = db_anofolha();
$mesusu = db_mesfolha();

if($registro != ''){
  $xreg = ' and r14_regist = '.$registro;
}else{
  $xreg = '';
}

$sql = "
select r14_regist,z01_nome, z01_cgccpf, rh02_lota,
       max(total_01) as total_01,
       max(base_01)  as base_01, 
       max(desc_01)  as desc_01,
       max(total_02) as total_02,
       max(base_02)  as base_02, 
       max(desc_02)  as desc_02,
       max(total_03) as total_03,
       max(base_03)  as base_03, 
       max(desc_03)  as desc_03,
       max(total_04) as total_04,
       max(base_04)  as base_04, 
       max(desc_04)  as desc_04,
       max(total_05) as total_05,
       max(base_05)  as base_05, 

       max(desc_05)  as desc_05,
       max(total_06) as total_06,
       max(base_06)  as base_06, 
       max(desc_06)  as desc_06,
       max(total_07) as total_07,
       max(base_07)  as base_07, 
       max(desc_07)  as desc_07,
       max(total_08) as total_08,
       max(base_08)  as base_08, 
       max(desc_08)  as desc_08,
       max(total_09) as total_09,
       max(base_09)  as base_09, 
       max(desc_09)  as desc_09,
       max(total_10) as total_10,
       max(base_10)  as base_10, 
       max(desc_10)  as desc_10,
       max(total_11) as total_11,
       max(base_11)  as base_11, 
       max(desc_11)  as desc_11,
       max(total_12) as total_12,
       max(base_12)  as base_12, 
       max(desc_12)  as desc_12
from
(select r14_regist,
       case when r14_mesusu = 1  and r14_rubric = 'R981' then r14_valor end as total_01,
       case when r14_mesusu = 2  and r14_rubric = 'R981' then r14_valor end as total_02,
       case when r14_mesusu = 3  and r14_rubric = 'R981' then r14_valor end as total_03,
       case when r14_mesusu = 4  and r14_rubric = 'R981' then r14_valor end as total_04,
       case when r14_mesusu = 5  and r14_rubric = 'R981' then r14_valor end as total_05,
       case when r14_mesusu = 6  and r14_rubric = 'R981' then r14_valor end as total_06,
       case when r14_mesusu = 7  and r14_rubric = 'R981' then r14_valor end as total_07,
       case when r14_mesusu = 8  and r14_rubric = 'R981' then r14_valor end as total_08,
       case when r14_mesusu = 9  and r14_rubric = 'R981' then r14_valor end as total_09,
       case when r14_mesusu = 10 and r14_rubric = 'R981' then r14_valor end as total_10,
       case when r14_mesusu = 11 and r14_rubric = 'R981' then r14_valor end as total_11,
       case when r14_mesusu = 12 and r14_rubric = 'R981' then r14_valor end as total_12,
       case when r14_mesusu = 1  and r14_rubric = 'R985' then r14_valor end as base_01,
       case when r14_mesusu = 2  and r14_rubric = 'R985' then r14_valor end as base_02,
       case when r14_mesusu = 3  and r14_rubric = 'R985' then r14_valor end as base_03,
       case when r14_mesusu = 4  and r14_rubric = 'R985' then r14_valor end as base_04,
       case when r14_mesusu = 5  and r14_rubric = 'R985' then r14_valor end as base_05,
       case when r14_mesusu = 6  and r14_rubric = 'R985' then r14_valor end as base_06,
       case when r14_mesusu = 7  and r14_rubric = 'R985' then r14_valor end as base_07,
       case when r14_mesusu = 8  and r14_rubric = 'R985' then r14_valor end as base_08,
       case when r14_mesusu = 9  and r14_rubric = 'R985' then r14_valor end as base_09,
       case when r14_mesusu = 10 and r14_rubric = 'R985' then r14_valor end as base_10,
       case when r14_mesusu = 11 and r14_rubric = 'R985' then r14_valor end as base_11,
       case when r14_mesusu = 12 and r14_rubric = 'R985' then r14_valor end as base_12,
       case when r14_mesusu = 01 and r14_rubric = 'R904' then r14_valor end as desc_01,
       case when r14_mesusu = 02 and r14_rubric = 'R904' then r14_valor end as desc_02,
       case when r14_mesusu = 03 and r14_rubric = 'R904' then r14_valor end as desc_03,
       case when r14_mesusu = 04 and r14_rubric = 'R904' then r14_valor end as desc_04,
       case when r14_mesusu = 05 and r14_rubric = 'R904' then r14_valor end as desc_05,
       case when r14_mesusu = 06 and r14_rubric = 'R904' then r14_valor end as desc_06,
       case when r14_mesusu = 07 and r14_rubric = 'R904' then r14_valor end as desc_07,
       case when r14_mesusu = 08 and r14_rubric = 'R904' then r14_valor end as desc_08,
       case when r14_mesusu = 09 and r14_rubric = 'R904' then r14_valor end as desc_09,
       case when r14_mesusu = 10 and r14_rubric = 'R904' then r14_valor end as desc_10,
       case when r14_mesusu = 11 and r14_rubric = 'R904' then r14_valor end as desc_11,
       case when r14_mesusu = 12 and r14_rubric = 'R904' then r14_valor end as desc_12
       
from gerfsal 
where r14_anousu = $ano 
  and r14_rubric in ('R985','R981','R904') 
	and r14_instit = ".db_getsession("DB_instit")."
  $xreg ) as x
  inner join rhpessoalmov on rh02_anousu = $anousu 
	                       and rh02_mesusu = $mesusu 
												 and rh02_regist = r14_regist
												 and rh02_instit = r14_instit
  inner join rhpessoal    on rh01_regist = r14_regist
  inner join cgm  on r01_numcgm = z01_numcgm
where rh02_lota between '$lotaini' and '$lotafin'
group by r14_regist, z01_nome, z01_cgccpf,rh02_lota
order by rh02_lota, z01_nome;

       ";
//echo $sql ; exit;
//$conect = pg_connect("host='192.168.1.1' dbname=sam30 user=postgres");
$result = pg_exec($sql);
//db_criatabela($result);exit;
include("libs/db_conecta.php");
$xxnum = pg_numrows($result);
//if ($xxnum == 0){
//   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);
//}

db_fieldsmemory($result,0);

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'19');
//$pdf1->modelo = 19;
//$pdf1->nvias= $e30_nroviaaut;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->prefeitura 	= $nomeinst;
$pdf1->enderpref  	= $ender;
$pdf1->municpref  	= $munic;
$pdf1->telefpref  	= $telef;
$pdf1->cgcpref    	= $cgc;
$pdf1->emailpref  	= $email;
$pdf1->logo  	  	= $logo;
if($ano == 2005){
  $patr = 14;
  $func =  8;
}elseif($ano == 2006){
  $patr = 16;
  $func =  8;
}else{
  $patr = 14;
  $func =  8;
}
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   $pdf1->nome     = $z01_nome;
   $pdf1->cnpj     = $z01_cgccpf;
   $pdf1->matricula= $r14_regist;
   $pdf1->ano	   = $ano;
   $pdf1->lotacao  = $r01_lotac;
   
   $pdf1->patr     =$patr     ;
   $pdf1->func     =$func     ;
   $pdf1->total_01 =$total_01 ;
   $pdf1->base_01  =$base_01  ;
   $pdf1->desc_01  =$desc_01  ;
   $pdf1->total_02 =$total_02 ;
   $pdf1->base_02  =$base_02  ;
   $pdf1->desc_02  =$desc_02  ;
   $pdf1->total_03 =$total_03 ;
   $pdf1->base_03  =$base_03  ;
   $pdf1->desc_03  =$desc_03  ;
   $pdf1->total_04 =$total_04 ;
   $pdf1->base_04  =$base_04  ;
   $pdf1->desc_04  =$desc_04  ;
   $pdf1->total_05 =$total_05 ;
   $pdf1->base_05  =$base_05  ;
   $pdf1->desc_05  =$desc_05  ;
   $pdf1->total_06 =$total_06 ;
   $pdf1->base_06  =$base_06  ;
   $pdf1->desc_06  =$desc_06  ;
   $pdf1->total_07 =$total_07 ;
   $pdf1->base_07  =$base_07  ;
   $pdf1->desc_07  =$desc_07  ;
   $pdf1->total_08 =$total_08 ;
   $pdf1->base_08  =$base_08  ;
   $pdf1->desc_08  =$desc_08  ;
   $pdf1->total_09 =$total_09 ;
   $pdf1->base_09  =$base_09  ;
   $pdf1->desc_09  =$desc_09  ;
   $pdf1->total_10 =$total_10 ;
   $pdf1->base_10  =$base_10  ;
   $pdf1->desc_10  =$desc_10  ;
   $pdf1->total_11 =$total_11 ;
   $pdf1->base_11  =$base_11  ;
   $pdf1->desc_11  =$desc_11  ;
   $pdf1->total_12 =$total_12 ;
   $pdf1->base_12  =$base_12  ;
   $pdf1->desc_12  =$desc_12  ;

   $pdf1->imprime();

}



$pdf1->objpdf->Output();
   
?>