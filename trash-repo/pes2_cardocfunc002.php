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

$head1 = "RELATÓRIO DE CADASTRO";
$head3 = "PERÍODO : ".$mes." / ".$ano;

$xwhere = '';
if($demit == 'n'){
  $xwhere = " and rh05_recis is null";
}

if($regime != 0){
  $res_reg = pg_query('select * from rhregime where rh30_instit = '.db_getsession("DB_instit").' and rh30_codreg = '.$regime) ;
  db_fieldsmemory($res_reg,0);
  $head7 = 'REGIME : '.$rh30_descr.'('.$rh30_regime.', '.$rh30_vinculo.')' ;
  $xwhere .= ' and rh30_codreg = '.$regime;
}

if($ordem == 'n'){
  $head5 = 'ORDEM : NUMERICA';
  $xordem = ' order by rh01_regist';
}else{
  $head5 = 'ORDEM : ALFABETICA';
  $xordem = ' order by z01_nome';
}


$sql = "
select rh01_regist,
       z01_nome,
       rh30_regime,
       z01_ident,
       z01_cgccpf,
       rh16_pis,
       rh01_admiss,
       rh01_progres,
       rh37_funcao,
       rh37_descr,
       rh37_cbo
from rhpessoal
     inner join cgm on rh01_numcgm = z01_numcgm
     inner join rhpessoalmov on rh02_anousu = $ano
                            and rh02_mesusu = $mes
                            and rh02_regist = rh01_regist
	                    and rh02_instit = ".db_getsession("DB_instit")." 
     inner join rhregime on rh02_codreg = rh30_codreg
		        and rh02_instit = rh30_instit 
     inner join rhfuncao on rh01_funcao = rh37_funcao
		        and rh02_instit = rh37_instit 
     inner join rhpesdoc on rh16_regist = rh01_regist
     left  join rhpesrescisao on rh02_seqpes = rh05_seqpes
where 1 = 1 
$xwhere 
$xordem
       ";
//echo $sql ; exit;

//db_criatabela($result);exit;
//$xxnum = pg_numrows($result);




$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionarios no periodo: '.$mes.' / '.$ano);

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
$troca = 1;
$alt = 4;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     if($cargo == 's'){
      $pdf->addpage('L');
     }else{
      $pdf->addpage('');
     }
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(10,$alt,'REG.',1,0,"C",1);
      $pdf->cell(20,$alt,'IDENTIDADE',1,0,"C",1);
      $pdf->cell(25,$alt,'CPF',1,0,"C",1);
      $pdf->cell(20,$alt,'PIS',1,0,"C",1);
      $pdf->cell(20,$alt,'ADMISSAO',1,0,"C",1);
     if($cargo == 's'){
      $pdf->cell(20,$alt,'NOMEACAO',1,0,"C",1);
      $pdf->cell(60,$alt,'CARGO',1,0,"C",1);
      $pdf->cell(20,$alt,'CBO',1,1,"C",1);
     }else{
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'NOMEACAO',1,1,"C",1);
     }
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1)
      $pre = 0;
   else
      $pre = 1;
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(10,$alt,$rh30_regime,0,0,"L",$pre);
   $pdf->cell(20,$alt,$z01_ident,0,0,"L",$pre);
   $pdf->cell(25,$alt,$z01_cgccpf,0,0,"L",$pre);
   $pdf->cell(20,$alt,$rh16_pis,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",$pre);
   if($cargo == 's'){
      $pdf->cell(20,$alt,db_formatar($rh01_progres,'d'),0,0,"L",$pre);
      $pdf->cell(60,$alt,$rh37_descr,0,0,"L",$pre);
      $pdf->cell(20,$alt,$rh37_cbo,0,1,"L",$pre);
   }else{
      $pdf->cell(20,$alt,db_formatar($rh01_progres,'d'),0,1,"L",$pre);
   }
   $func   += 1;
}

$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func.'  FUNCIONARIOS',0,0,"L",0);

$pdf->Output();
   
?>