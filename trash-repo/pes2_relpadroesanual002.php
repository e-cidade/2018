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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once ("libs/JSON.php");

$oGet        = db_utils::postMemory($_GET);
       
$oJson       = new services_json();

$oParametros = $oJson->decode(str_replace("\\", "", $oGet->sParametros));

$iInstituicao = db_getsession("DB_instit");

$sOrdem         = $oParametros->sOrdem;
$iRegime        = $oParametros->iRegime;
$sPadrao  			= $oParametros->sPadrao;
$iAno1          = $oParametros->iAno1;
$iAno2          = $oParametros->iAno2;

$sDescricaoRubrica = '';


$head2  = "RELATÓRIO ANUAL DE PADRÕES";
$head4 = "ANO : ".$iAno1." a ".$iAno2 ;

$swhere = '';
if(isset($sPadrao) && trim($sPadrao) != '' ){
  $head3   = "PADRÃO : ".$sPadrao;
  $swhere .= " and r02_codigo = '{$sPadrao}'";
}else{
  $head3   = "PADRÃO : Todos";
}

$head5 = "REGIME : Todos";
if(isset($iRegime) && trim($iRegime) != 't' ){
  $swhere .= " and r02_regime = {$iRegime}";
  if($iRegime == 1){
    $head5 = "REGIME : Estatutário";
  }elseif($iRegime == 2){
    $head5 = "REGIME : CLT";
  }elseif($iRegime == 3){
    $head5 = "REGIME : Extra Quadro";
  }
}

if(trim($sOrdem) != 'p'){
  $head6 = "ORDEM : Regime";
  $sgroupby = " group by r02_anousu, r02_regime, r02_codigo, r02_descr ";
  $sorderby = " order by r02_anousu, r02_regime, r02_codigo, r02_descr ";
}else{
  $head6 = "ORDEM : Padrão";
  $sgroupby = " group by r02_codigo, r02_descr, r02_regime, r02_anousu ";
  $sorderby = " order by r02_codigo, r02_descr, r02_regime, r02_anousu ";
}


$sql_padrao = 
       "
       select r02_regime, r02_codigo, r02_descr, r02_anousu, max(jan) as jan, max(fev) as fev, max(mar) as mar, max(abr) as abr, max(mai) as mai, max(jun) as jun,
                                     max(jul) as jul, max(ago) as ago, max(set) as set, max(out) as out, max(nov) as nov, max(dez) as dez
       from (
       select r02_regime, 
              r02_codigo,
              r02_descr,
              r02_anousu,
              case when r02_mesusu = 1  then r02_valor else 0 end as jan,
              case when r02_mesusu = 2  then r02_valor else 0 end as fev,
              case when r02_mesusu = 3  then r02_valor else 0 end as mar,
              case when r02_mesusu = 4  then r02_valor else 0 end as abr,
              case when r02_mesusu = 5  then r02_valor else 0 end as mai,
              case when r02_mesusu = 6  then r02_valor else 0 end as jun,
              case when r02_mesusu = 7  then r02_valor else 0 end as jul,
              case when r02_mesusu = 8  then r02_valor else 0 end as ago,
              case when r02_mesusu = 9  then r02_valor else 0 end as set,
              case when r02_mesusu = 10 then r02_valor else 0 end as out,
              case when r02_mesusu = 11 then r02_valor else 0 end as nov,
              case when r02_mesusu = 12 then r02_valor else 0 end as dez
       from padroes
       where r02_anousu between {$iAno1} and {$iAno2}
       $swhere
       ) as x  
       $sgroupby
       $sorderby
       ";

$rsPadrao = db_query($sql_padrao);
// echo $sql_padrao;db_criatabela($rsPadrao);exit;
if (pg_numrows($rsPadrao) == 0 ) {
		db_redireciona('db_erros.php?fechar=true&db_erro=Não existem valores para o padrão '.$sPadrao.', no período de '.$iAno1.' a '.$iAno2);
} 



$pdf = new PDF();

$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$pre   = 0;
$alt   = 4;

for($x = 0; $x < pg_numrows($rsPadrao);$x++){
	
	db_fieldsmemory($rsPadrao,$x);
	if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pre = 1;	
    $troca = 0;
    $pdf->addPage('L');
		$pdf->setfont('arial','B',7);
		$pdf->cell(10,$alt,'ANO',0,0,"C",1);
		$pdf->cell(10,$alt,'REGIME',0,0,"C",1);
		$pdf->cell(15,$alt,'PADRAO',0,0,"C",1);
		$pdf->cell(60,$alt,'DESCRICÃO',0,0,"L",1);
		$pdf->cell(15,$alt,'JANEIRO',0,0,"C",1);
		$pdf->cell(15,$alt,'FEVEREIRO',0,0,"C",1);
		$pdf->cell(15,$alt,'MARÇO',0,0,"C",1);
		$pdf->cell(15,$alt,'ABRIL',0,0,"C",1);
		$pdf->cell(15,$alt,'MAIO',0,0,"C",1);
		$pdf->cell(15,$alt,'JUNHO',0,0,"C",1);
		$pdf->cell(15,$alt,'JULHO',0,0,"C",1);
		$pdf->cell(15,$alt,'AGOSTO',0,0,"C",1);
		$pdf->cell(15,$alt,'SETEMBRO',0,0,"C",1);
		$pdf->cell(15,$alt,'OUTUBRO',0,0,"C",1);
		$pdf->cell(15,$alt,'NOVEMBRO',0,0,"C",1);
		$pdf->cell(15,$alt,'DEZEMBRO',0,1,"C",1);
	}

  if($pre == 1){
  	$pre = 0;
  }elseif($pre == 0){
  	$pre = 1;
  }
		
	$pdf->setfont('arial','',7);
	$pdf->cell(10,$alt,$r02_anousu,0,0,"C",$pre);
	$pdf->cell(10,$alt,$r02_regime,0,0,"C",$pre);
	$pdf->cell(15,$alt,$r02_codigo,0,0,"C",$pre);
	$pdf->cell(60,$alt,$r02_descr,0,0,"L",$pre);
	$pdf->cell(15,$alt,db_formatar($jan,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($fev,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($mar,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($abr,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($mai,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($jun,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($jul,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($ago,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($set,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($out,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($nov,'f'),0,0,"R",$pre);
	$pdf->cell(15,$alt,db_formatar($dez,'f'),0,1,"R",$pre);
		

}

$pdf->Output();

?>