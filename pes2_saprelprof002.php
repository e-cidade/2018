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

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//$ano = 2005;
//$mes = 10;

if($ordem == 'n'){
    $ordem = ' order by rh02_regist ';
}else{
    $ordem = ' order by z01_nome ';
}


$head3 = "RELATÓRIO DE PROFESSORES";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select rh02_regist as r01_regist,
       z01_nome,
       rh01_admiss as r01_admiss,
       rh02_codreg as r01_clas1,
       rh03_padrao as r01_padrao,
       r70_descr
from rhpessoalmov
     inner join rhpessoal  on rh01_regist = rh02_regist
     left join rhpespadrao on rh03_seqpes = rh02_seqpes
     inner join cgm        on z01_numcgm  = rh01_numcgm 
     inner join rhlota     on rh02_lota   = r70_codigo
		                      and r70_instit  = rh02_instit
     left join rhpesrescisao on rh05_seqpes  = rh02_seqpes                     
     
where rh02_anousu = $ano
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null 
  and rh01_funcao in (7601,7702,7802) 
$ordem
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$orgao = '';
$unidade = '';

$pre          = 0;
$val_fgts     = 0;
$pat1         = 0;
$tot_func     = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','B',8);
      $pdf->cell(15,$alt,'MATRIC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'VINCULO',1,0,"C",1);
      $pdf->cell(20,$alt,'ADMISSAO',1,0,"C",1);
      $pdf->cell(15,$alt,'PADRAO',1,0,"C",1);
      $pdf->cell(60,$alt,'LOTACAO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 0)
     $pre = 1;
   else
     $pre = 0;
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,$r01_clas1,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($r01_admiss,'d'),0,0,"R",$pre);
   $pdf->cell(15,$alt,$r01_padrao,0,0,"L",$pre);
   $pdf->cell(60,$alt,$r70_descr,0,1,"L",$pre);
   $tot_func     += 1;
  
}
   $pdf->setfont('arial','B',8);
   $pdf->cell(170,$alt,'TOTAL :  '.$tot_func.'   FUNCIONARIOS',"T",1,"L",0);

$pdf->Output();
   
?>