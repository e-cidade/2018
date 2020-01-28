<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
// db_postmemory($HTTP_GET_VARS,2);

$head3 = "FUNCIONÁRIOS POR LOCAL DE TRABALHO - ".$mesfolha." / ".$anofolha;

$where = "";
$andwhere = " where ";
if(isset($locali) && trim($locali) != "" && isset($localf) && trim($localf) != ""){
  $head5 = "INTERVALO: ".$locali." até ".$localf;
  $where = $andwhere." rh55_estrut between '$locali'  and '$localf'";
}else if(isset($locali) && trim($locali) != ""){
  $head5 = "LOCAIS MAIORES OU IGUAL A ".$locali;
  $where = $andwhere." rh55_estrut >= '$locali'";
}else if(isset($localf) && trim($localf) != ""){
  $head5 = "LOCAIS MENORES OU IGUAL A ".$localf;
  $where = $andwhere." rh55_estrut <= '$localf'";
}else if(isset($selloc) && trim($selloc) != ""){
  $head5 = "LOCAIS: ".$selloc;
  $where = $andwhere." rh55_estrut in ('".str_replace(",","','",$selloc)."')";
}

$whereregime = "";
if(isset($atinpen)){
  if($atinpen == "a"){
    $whereregime = " and rh30_vinculo = 'A' ";
  }else if($atinpen == "i"){
    $whereregime = " and rh30_vinculo = 'I' ";
  }else if($atinpen == "p"){
    $whereregime = " and rh30_vinculo = 'P' ";
  }else if($atinpen == "ip"){
    $whereregime = " and (rh30_vinculo = 'I' or rh30_vinculo = 'P') ";
  }
}

if(trim($anofolha) == "" || trim($mesfolha) == ""){
  $anofolha = db_anofolha();
  $mesfolha = db_mesfolha();
}
if(isset($selecao) && $selecao != ""){
      $result_sel = db_query("select r44_where from selecao where r44_selec = {$selecao} and r44_instit = " . db_getsession("DB_instit"));
            if(pg_numrows($result_sel) > 0){
                          db_fieldsmemory($result_sel, 0, 1);
                                          $whereregime .= " and ".$r44_where;
                                                            }
}

$sql = "
select * from 
(
select rh01_regist,
       z01_nome,
       case when rh55_estrut is null then '0' else rh55_estrut end as rh55_estrut,
       case when rh55_estrut is null then 'SEM LOCAL CADASTRADO' else rh55_descr end as rh55_descr,
       r70_estrut,
       r70_descr
from rhpessoal
     inner join rhpessoalmov on rh02_anousu = $anofolha
                            and rh02_mesusu = $mesfolha
                            and rh02_regist = rh01_regist
                            and rh02_instit = ".db_getsession("DB_instit")." 
     inner join cgm on rh01_numcgm = z01_numcgm
     inner join rhlota on r70_codigo = rh02_lota and r70_instit = ".db_getsession("DB_instit")." 
     left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes and rh56_princ = 't'
     left join rhlocaltrab    on rh55_codigo = rh56_localtrab and rh55_instit = ".db_getsession("DB_instit")."
     inner join rhregime on rh30_codreg = rh02_codreg
		                    and rh30_instit = rh02_instit
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes
where rh05_seqpes is null
$whereregime
) as x
$where
order by rh55_estrut,z01_nome     
";

//echo $sql ; exit;
$result = db_query($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no intervalo para o período de '.$mesfolha.' / '.$anofolha);
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
$troca_loc = 's';

for($x = 0; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x);
  if($orgao != $rh55_estrut){
    if($x != 0){
      $pdf->setfont('arial','',8);
      $pdf->cell(152,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);
    }
    $total = 0;
    if($qbrapag == 's'){
      $troca = 1;
    }else{
      $pdf->setfont('arial','b',8);
      $pdf->ln(4);
      $pdf->cell(0,$alt,'LOCAL : '.$rh55_estrut.' - '.$rh55_descr,0,1,"L",0);
    }

    $orgao = $rh55_estrut;
  }
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $alt = 4;
    $pdf->cell(0,$alt,'LOCAL : '.$rh55_estrut.' - '.$rh55_descr,0,1,"L",0);
    $pdf->ln(4);
    $pdf->cell(12,$alt,'MATRIC',1,0,"C",1);
    $pdf->cell(60,$alt,'NOME DO FUNCIONARIO',1,0,"C",1);
    $pdf->cell(80,$alt,'LOTACAO',1,1,"C",1);
    $pre = 1; 
    $troca = 0;
  }
  $alt = 4;
  if($pre == 1 )
    $pre = 0;
  else
    $pre = 1;

  $pdf->setfont('arial','',7);
  $pdf->cell(12,$alt,$rh01_regist,0,0,"C",$pre);
  $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
  $pdf->cell(80,$alt,$r70_estrut.'-'.$r70_descr,0,1,"L",$pre);

  $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(152,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",1);

$pdf->Output();
   
?>