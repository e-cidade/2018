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

$head3 = "EFETIVIDADE REFERENTE A ".db_formatar($perinicial,'d')." / ".db_formatar($perfinal,'d');

$where = '';
if($tipos != ''){
  $where = " and r70_codigo in ($tipos)";
}
$ano = db_anofolha();
$mes = db_mesfolha();


$sql = "
select rh02_regist,
       z01_nome,
       rh37_descr,
       r70_estrut,
       r70_descr,
       rh05_seqpes
from rhpessoalmov
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on rh01_numcgm = z01_numcgm
     inner join rhlota       on r70_codigo  = rh02_lota
		                        and r70_instit  = rh02_instit
     inner join rhfuncao     on rh37_funcao = rh01_funcao  
                            and rh37_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
		                        and rh30_instit = rh02_instit
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes

where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_seqpes is null 
  and rh30_vinculo = 'A'
  $where
order by r70_estrut , z01_nome
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

$tam_rub = 12;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($orgao != $r70_estrut){
   	  if($x != 0){
        $pdf->setfont('arial','b',8);
        $pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,"T",1,"L",0);
        $pdf->ln(2);
        $pdf->cell(110,6,'NOMEACOES',1,0,'C',0);
        $pdf->cell(32, 6,'DATA',1,0,'C',0);
        $pdf->cell(110,6,'EXONERACOES',1,0,'C',0);
        $pdf->cell(32, 6,'DATA',1,1,'C',0);
        
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
          $pdf->addpage('L');
        }

        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
          $pdf->addpage('L');
        }

        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
        $pdf->cell(110,8,'','LTB',0,'C',0);
        $pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

   	  }
   	  $total = 0;
   	  $troca = 1;
   	  $orgao = $r70_estrut;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $alt = 5;
   	  $pdf->cell(0,$alt,'LOTACAO : '.$r70_estrut.' - '.$r70_descr,0,1,"L",0);
   	  $pdf->ln(4);
      $pdf->cell(10,$alt,'MATR.','LRT',0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO','LRT',0,"C",1);
   	  $pdf->cell(10,$alt,'EFET.','LRT',0,"C",1);
   	  $pdf->cell($tam_rub*2,$alt,'0500','LRT',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'0007','LRT',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'0008','LRT',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'0009','LRT',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Atestados','LRT',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Licencas/','LRT',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Atrasos','LRT',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Atestado','LRT',1,"C",1);
//   	  $pdf->cell($tam_rub  ,$alt,'0286','LRT',0,"C",1);
//  	  $pdf->cell($tam_rub  ,$alt,'0310','LRT',1,"C",1);
      $pdf->setfont('arial','b',6);
      $pdf->cell(10,$alt,'','L',0,"C",1);
      $pdf->cell(60,$alt,'','L',0,"C",1);
   	  $pdf->cell(10,$alt,'','L',0,"C",1);
   	  $pdf->cell($tam_rub*2,$alt,'Faltas','L',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'Adic.Not','L',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'H.E. 50%','L',0,"C",1);
   	  $pdf->cell($tam_rub  ,$alt,'H.E. 100%','L',0,"C",1);
      $pdf->setfont('arial','b',8);
   	  $pdf->cell($tam_rub*3,$alt,'Proprios','L',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Concessoes','L',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'','L',0,"C",1);
   	  $pdf->cell($tam_rub*3,$alt,'Familiar','LR',1,"C",1);
//   	  $pdf->cell($tam_rub  ,$alt,'Sal.Conserv.','L',0,"C",1);
// 	    $pdf->cell($tam_rub  ,$alt,'Sal.Subst.','LR',1,"C",1);
   	  
      $troca = 0;
   }
   $alt = 6;
   $pdf->setfont('arial','',7);
   $pdf->cell(10,$alt,$rh02_regist,'TLR',0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,'TLR',0,"L",0);
   $pdf->cell(10,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub*2,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','TLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','TLR',1,"L",0);
//   $pdf->cell($tam_rub  ,$alt,'','TLR',0,"L",0);
//   $pdf->cell($tam_rub  ,$alt,'','TLR',1,"L",0);

   $alt = 5;
   $pdf->cell(10,$alt,'','BLR',0,"C",0);
   $pdf->cell(60,$alt,$rh37_descr,'BLR',0,"L",0);
   $pdf->cell(10,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub*2,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub  ,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','BLR',0,"L",0);
   $pdf->cell($tam_rub*3,$alt,'','BLR',1,"L",0);
//   $pdf->cell($tam_rub  ,$alt,'','BLR',0,"L",0);
//   $pdf->cell($tam_rub  ,$alt,'','BLR',1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,"T",1,"L",0);

$pdf->ln(2);
$pdf->cell(110,6,'NOMEACOES',1,0,'C',0);
$pdf->cell(32, 6,'DATA',1,0,'C',0);
$pdf->cell(110,6,'EXONERACOES',1,0,'C',0);
$pdf->cell(32, 6,'DATA',1,1,'C',0);

if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('L');
}

$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('L');
}

$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,1,'C',0);

$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,0,'C',0);
$pdf->cell(110,8,'','LTB',0,'C',0);
$pdf->cell(32, 8,'____/____/_______',1,1,'C',0);





$pdf->Output();
   
?>