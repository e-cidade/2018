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
include("classes/db_inssirf_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$clinssirf = new cl_inssirf;

if($previdencia < 4){
  $res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,
                                     db_getsession('DB_instit'),"r33_ppatro,r33_nome,r33_rubmat",
                                     "r33_nome limit 1","r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = $previdencia + 2 and r33_instit = ".db_getsession('DB_instit')));
  db_fieldsmemory($res_prev,0);
  $perc = $r33_ppatro;
}else{
  $perc= 8;
}
if($salario == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14';
  $descr_arq = 'SALÁRIO';
}elseif($salario == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48';
  $descr_arq = 'COMPLEMENTAR';
}elseif($salario == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35';
  $descr_arq = '13o. SALÁRIO';
}


if($previdencia == 1){
  $rubric = 'R992';
  $prev = ' and rh02_tbprev = 1 ' ;
  $head2 = "EMPENHOS PREVIDENCIA MUNICIPAL " ;
}elseif($previdencia == 2){
  $rubric = 'R992';
  $prev = ' and rh02_tbprev = 2 ' ;
  $head2 = "EMPENHOS INSS " ;
}elseif($previdencia == 3){
  $rubric = 'R992';
  $prev = ' and rh02_tbprev = 3 ' ;
  $head2 = "EMPENHOS IPE " ;
}else{
  $rubric = 'R991';
  $prev = ' ' ;
  $head2 = "EMPENHOS FGTS " ;
}

$head3 = "PERIODO : ".$mes.'/'.$ano;
$head4 = "PERC. PATRONAL : ".$perc."%";
$head5 = "ARQUIVO : ".$descr_arq;


$sql = "
select ".$sigla."_lotac as r14_lotac,
       r70_estrut,
       r70_descr as r13_descr,
       rh42_proati  as r13_proati,
       round(sum(".$sigla."_valor),2) as base ,
       round(sum(".$sigla."_valor)/100*$perc,2) as valor
from ".$arquivo."
     inner join rhpessoalmov on rh02_anousu = ".$sigla."_anousu
                            and rh02_mesusu = ".$sigla."_mesusu
                            and rh02_regist = ".$sigla."_regist
						     					  and rh02_instit = ".$sigla."_instit
                           $prev 
     inner join rhlota  on r70_codigo =rh02_lota
		                   and r70_instit = rhpessoalmov.rh02_instit 
     left join rhfolhaemp on rh42_anousu = ".$sigla."_anousu
                         and rh42_mesusu = ".$sigla."_mesusu
where ".$sigla."_anousu = $ano
  and ".$sigla."_mesusu = $mes
	and ".$sigla."_instit = ".db_getsession("DB_instit")." 
  and ".$sigla."_rubric = '$rubric'
group by ".$sigla."_lotac,
         r70_estrut,
         r70_descr,
         rh42_proati
order by r70_estrut

";
//echo $sql;exit;
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$xsec = 0;
$tot_base  = 0;
$tot_valor = 0;
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'LOTA',1,0,"C",1);
      $pdf->cell(75,$alt,'DESCRICAO',1,0,"C",1);
      $pdf->cell(10,$alt,'ATIV.',1,0,"C",1);
      $pdf->cell(25,$alt,'BASE BRUTA',1,0,"C",1);
      $pdf->cell(15,$alt,'PERC',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r70_estrut,0,0,"C",$pre);
   $pdf->cell(75,$alt,$r13_descr,0,0,"L",$pre);
   $pdf->cell(10,$alt,$r13_proati,0,0,"C",$pre);
   $pdf->cell(25,$alt,$base,0,0,"R",$pre);
   $pdf->cell(15,$alt,$perc,0,0,"R",$pre);
   $pdf->cell(25,$alt,$valor,0,1,"R",$pre);
   $total++;
   $tot_base  += $base;
   $tot_valor += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL   : ',"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_base,'f'),"T",0,"R",0);
$pdf->cell(15,$alt,'',"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_valor,'f'),"T",0,"R",0);
$pdf->Output();
?>