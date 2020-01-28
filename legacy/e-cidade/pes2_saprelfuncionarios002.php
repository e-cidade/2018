<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
$gerasql = new cl_gera_sql_folha;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r70_estrut');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "CADASTRO DE FUNCIONÁRIOS COMPLETO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$dbwhere = " rh05_seqpes is null ";
if(isset($selec) && trim($selec) != ""){
  $dbwhere .= " and rh02_codreg in (".$selec.")";
  $gerasql->usar_atv = true;
}

if(isset($secret) && trim($secret) != "00"){
  $dbwhere .= " and substr(r70_estrut,2,2) = '".$secret."' ";
}
if($ordem == 'a'){
  $dbordem = " z01_nome ";
  $head6  = "Ordem Alfabética";
}else{
  $dbordem = " rh01_regist ";
  $head6  = "Ordem Numérica";
}

$gerasql->usar_lot = true;
$gerasql->usar_cgm = true;
$gerasql->usar_fun = true;
$gerasql->usar_res = true;
$sql_dados = $gerasql->gerador_sql(null, $ano, $mes, null, null, 
                                   "r70_estrut,
                                    rh01_regist, 
                                    z01_nome, 
                                    rh01_admiss, 
                                    rh01_clas1, 
                                    rh37_descr,
				    rh02_codreg,
                                    substr(db_fxxx(rh01_regist, " . $ano . ", " . $mes . ", ".db_getsession("DB_instit")."), 221, 12) as padrao,
                                    r70_descr",
                                   $dbordem, $dbwhere);
//echo $sql_dados;exit;
$result_dados = db_query($sql_dados);
$numrows_dados = pg_numrows($result_dados);
if($numrows_dados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$gerasql->inicio_rh = false;
$gerasql->usar_pes = false;
$gerasql->usar_lot = false;
$gerasql->usar_cgm = false;
$gerasql->usar_fun = false;
$gerasql->usar_atv = false;
$gerasql->usar_res = false;
$gerasql->usar_org = false;
$gerasql->usar_rub = true;
for($i = 0; $i < pg_numrows($result_dados);$i++){
  db_fieldsmemory($result_dados,$i);
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage('L');
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,'MATRÍCULA',1,0,"C",1);
    $pdf->cell(65,$alt,'NOME',1,0,"C",1);
    $pdf->cell(15,$alt,'ADMISSÃO',1,0,"C",1);
    $pdf->cell(8,$alt,'REG.',1,0,"C",1);
    $pdf->cell(52,$alt,'CARGO',1,0,"C",1);
    $pdf->cell(23,$alt,'PADRÃO',1,0,"C",1);
    $pdf->cell(72,$alt,'LOCALIZAÇÃO',1,0,"C",1);
    $pdf->cell(20,$alt,'FIXO',1,1,"C",1);
    $troca = 0;
    $pre = 1;
  }
  if($pre == 1){
    $pre = 0;
  }else{
    $pre = 1;
  }
  $sql_dados_ger = $gerasql->gerador_sql("r53", $ano, $mes, $rh01_regist, null, "sum(#s#_valor) as valor", "", "rh27_tipo = 1 and rh27_pd = 1 ");
  $result_dados_ger = db_query($sql_dados_ger);
  db_fieldsmemory($result_dados_ger,0);
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
  $pdf->cell(65,$alt,$z01_nome,0,0,"L",$pre);
  $pdf->cell(15,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",$pre);
  $pdf->cell(8,$alt,$rh02_codreg,0,0,"C",$pre);
  $pdf->cell(52,$alt,$rh37_descr,0,0,"L",$pre);
  $pdf->cell(23,$alt,$padrao,0,0,"L",$pre);
  $pdf->cell(72,$alt,$r70_descr,0,0,"L",$pre);
  $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",$pre);

  $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'TOTAL  :  '.$total.'   FUNCIONÁRIOS',"T",1,"C",0);

$pdf->Output();
?>