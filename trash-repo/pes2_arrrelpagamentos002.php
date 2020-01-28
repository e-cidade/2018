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

$head2 = "PAGAMENTO DA FOLHA EM CONTA CORRENTE";
$head4 = "DATA :  ".db_formatar(date('Y-m-d',db_getsession("DB_datausu")),'d');
//$lotacao = 's';

if($lotacao == 's'){
 $ordem = " r70_estrut , r38_banco, r38_agenc, r38_nome ";
}else{
 $ordem = " r38_banco, r38_agenc, r38_nome ";
}
$where = '';

if($matricula != 0){
  $where = " where r38_regist in ($matricula)";
}
$sql = "
         select r38_banco,
				        db90_descr,
				        r38_agenc,
								r70_estrut,
								r70_descr,
								r38_regist,
								r38_numcgm,
								r38_conta,
								r38_nome,
								r38_liq 
				 from folha 
				      inner join rhlota on to_number(r38_lotac,'9999') = r70_codigo
							                 and r70_instit = ".db_getsession("DB_instit")."
							left  join db_bancos on r38_banco = db90_codban 
				 $where
				 order by $ordem
"; 
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado no periodo de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca     = 1;
$total     = 0;
$alt       = 6;
$xlota     = 0;
$tot_banco = 0;
$tot_age   = 0;
$tot_lota  = 0;
$tot_func  = 0;

$pdf->setfillcolor(235);

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
     if($xlota != $r38_banco.$r38_agenc){
	     $troca = 1;
       $xlota = $r38_banco.$r38_agenc;
       $pdf->cell(125,$alt,'','T',0,"C",0);
       $pdf->cell(15,$alt,'TOTAL DO BANCO','T',0,"R",0);
       $pdf->cell(20,$alt,db_formatar($tot_age,'f'),'T',1,"R",0);
       $pdf->cell(125,$alt,'',0,0,"C",0);
       $pdf->cell(15,$alt,'TOTAL DE FUNC.',0,0,"R",0);
       $pdf->cell(20,$alt,$tot_func,0,1,"R",0);
			 $tot_age = 0;
			 $tot_func= 0;
     }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',10);
			if($lotacao == 's'){
        $pdf->cell(15,$alt,$r70_estrut,0,0,"R",0);
        $pdf->cell(75,$alt,$r70_descr,0,1,"L",0);
		  }
      $pdf->cell(15,$alt,'BANCO',0,0,"R",0);
      $pdf->cell(15,$alt,$r38_banco,0,0,"C",0);
      $pdf->cell(75,$alt,$db90_descr,0,1,"L",0);
      $pdf->cell(15,$alt,'AGENCIA',0,0,"R",0);
      $pdf->cell(15,$alt,$r38_agenc,0,1,"C",0);
		 
      $pdf->cell(25,$alt,'CONTA',1,0,"L",1);
      $pdf->cell(15,$alt,'MATRIC',1,0,"L",1);
      $pdf->cell(15,$alt,'CGM',1,0,"L",1);
      $pdf->cell(85,$alt,'NOME',1,0,"L",1);
      $pdf->cell(20,$alt,'LIQUIDO',1,1,"R",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',10);
   $pdf->cell(25,$alt,$r38_conta,0,0,"L",$pre);
   $pdf->cell(15,$alt,$r38_regist,0,0,"L",$pre);
   $pdf->cell(15,$alt,$r38_numcgm,0,0,"L",$pre);
   $pdf->cell(85,$alt,$r38_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),0,1,"R",$pre);
   $tot_banco += $r38_liq;
   $tot_age   += $r38_liq;
   $tot_lota  += $r38_liq;
   $tot_func  += 1;
}
$pdf->cell(125,$alt,'','T',0,"C",0);
$pdf->cell(15,$alt,'TOTAL DO BANCO','T',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_age,'f'),'T',1,"R",0);
$pdf->cell(125,$alt,'',0,0,"C",0);
$pdf->cell(15,$alt,'TOTAL DE FUNC.',0,0,"R",0);
$pdf->cell(20,$alt,$tot_func,0,1,"R",0);
//$pdf->setfont('arial','b',10);
//$pdf->cell(0,$alt,'TOTAL DE REGISTROS '.$total,"T",1,"R",0);
$pdf->Output();
?>