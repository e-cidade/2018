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
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_elemen');
$clrotulo->label('rh27_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;



$head2 = 'LISTA DE RECEBIMENTO DOS CONTRACHEQUES';
$head4 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select distinct rh01_regist as regist,
       z01_nome as nome,
       r70_estrut
from rhpessoal  
     inner join rhpessoalmov  on rh02_regist = rh01_regist 
                             and rh02_anousu = $ano 
		                         and rh02_mesusu = $mes 
												     and rh02_instit = ".db_getsession("DB_instit")."
     left join rhpesrescisao  on rh02_seqpes = rh05_seqpes
     inner join cgm           on rh01_numcgm = z01_numcgm 
     inner join rhlota        on r70_codigo = rh02_lota
		                         and r70_instit = rh02_instit 
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = ".$ano.") as rhlotavinc on rh25_codigo = r70_codigo
where rh05_seqpes is null
order by r70_estrut, z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem servidores no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$funcion = 0;
$func_c  = 0;
$tot_c   = 0;
$total   = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 10;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(15,$alt,'LOTACAO',1,0,"C",1);
      $pdf->cell(100,$alt,'ASSINATURA',1,1,"C",1);
      $quebra = '';
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt/2,$regist,'LTR',0,"C",0);
   $pdf->cell(60,$alt/2,$nome,'LTR',0,"L",0);
   $pdf->cell(15,$alt/2,$r70_estrut,'LTR',0,"C",0);
   $pdf->cell(100,$alt/2,'','LTR',1,"R",0);
   $pdf->cell(15,$alt/2,'','LBR',0,"C",0);
   $pdf->cell(60,$alt/2,'','LBR',0,"L",0);
   $pdf->cell(15,$alt/2,'','LBR',0,"L",0);
   $pdf->cell(100,$alt/2,'','LBR',1,"R",0);
   $funcion+= 1;
   $func_c += 1;
}


$pdf->Output();
   
?>