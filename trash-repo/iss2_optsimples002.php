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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

require_once("classes/db_isscadsimples_classe.php");
$clIssCadSimples = new cl_isscadsimples();

$oGet = db_utils::postMemory($_GET);

$aListaHead    = Array();
$aWhereSimples = Array(); 

$sDataIncSimplesIni   = implode("-",array_reverse(explode("/",$oGet->dataincini))); 
$sDataIncSimplesFin   = implode("-",array_reverse(explode("/",$oGet->dataincfin)));

$sDataBaixaSimplesIni = implode("-",array_reverse(explode("/",$oGet->databaixaini))); 
$sDataBaixaSimplesFin = implode("-",array_reverse(explode("/",$oGet->databaixafin)));



if ( trim($sDataIncSimplesIni) != '' ) {
	$aWhereSimples[] = " isscadsimples.q38_dtinicial >= '{$sDataIncSimplesIni}' ";
}

if ( trim($sDataIncSimplesFin) != '' ) {
  $aWhereSimples[] = " isscadsimples.q38_dtinicial <= '{$sDataIncSimplesFin}' ";
}

if ( trim($sDataBaixaSimplesIni) != '' ) {
  $aWhereSimples[] = " isscadsimplesbaixa.q39_dtbaixa >= '{$sDataBaixaSimplesIni}' ";
}

if ( trim($sDataBaixaSimplesFin) != '' ) {
  $aWhereSimples[] = " isscadsimplesbaixa.q39_dtbaixa <= '{$sDataBaixaSimplesFin}' ";
}

if ( trim($oGet->situacao) == '1' ) {
  $aWhereSimples[] = " issbase.q02_dtbaix is null ";
  $aListaHead[]    = " Somente Inscri��es Ativas";
} else if ( trim($oGet->situacao) == '2' ) {
	$aWhereSimples[] = " issbase.q02_dtbaix is not null ";
	$aListaHead[]    = " Somente Inscri��es Baixadas";
}

if ( trim($oGet->categoria) != '0' ) {
  $aWhereSimples[] = " isscadsimples.q38_categoria = {$oGet->categoria} ";
}

$sWhereSimples = implode(" and ",$aWhereSimples);


if ( $oGet->ordem == '0' ) {
	$sOrdemSimples = 'issbase.q02_inscr';
	$aListaHead[]  = " Ordenado por Inscri��o ";
} else if ( $oGet->ordem == '1' ) {
	$sOrdemSimples = 'cgm.z01_nome';
	$aListaHead[]  = " Ordenado por Nome ";
} else {
  $sOrdemSimples = 'ativid.q03_descr';
  $aListaHead[]  = " Ordenado por Atividade";
}

$sCamposSimples   = " issbase.q02_inscr as inscricao,                                         ";
$sCamposSimples  .= " cgm.z01_cgccpf    as cgccpf,                                            ";
$sCamposSimples  .= " cgm.z01_numcgm    as numcgm,                                            ";
$sCamposSimples  .= " cgm.z01_nome      as nome,                                              ";
$sCamposSimples  .= " ativid.q03_ativ||' - '||ativid.q03_descr  as descricao_atividade,       ";
$sCamposSimples  .= " case                                                                    ";
$sCamposSimples  .= "    when isscadsimples.q38_categoria = 1 then 'Micro Empresa'            ";       
$sCamposSimples  .= "    when isscadsimples.q38_categoria = 2 then 'Empresa de Pequeno Porte' ";
$sCamposSimples  .= "    else 'MEI'                                                           ";
$sCamposSimples  .= " end               as categoria,                                         ";
$sCamposSimples  .= " q38_dtinicial     as data_inclusao_simples,                             ";
$sCamposSimples  .= " q39_dtbaixa       as data_baixa_simples,                                ";
$sCamposSimples  .= " q02_dtbaix        as data_baixa_inscricao                               ";


$sSqlDadosSimples = $clIssCadSimples->sql_query_dadosinscr(null,
		                                                       $sCamposSimples,
		                                                       $sOrdemSimples,
		                                                       $sWhereSimples);


$rsDadosSimples   = $clIssCadSimples->sql_record($sSqlDadosSimples);
$iLinhasSimples   = $clIssCadSimples->numrows;                                                       

if ($iLinhasSimples == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}


switch ($oGet->categoria) {
  case '1':
   $aListaHead[] = " Somente Micro Empresa";
  break;
  case '2':
   $aListaHead[] = " Somente Empresas de Pequeno Porte";
  break;
  case '3':
   $aListaHead[] = " Somente MEI";
  break;      
}

if ( trim($oGet->dataincini) != '' || trim($oGet->dataincfin) != '' ) {
	
	$sHeadPeriodoInscr = " Inscri��es Inclu�das no Simples"; 
	
	if ( trim($oGet->dataincini) == '' || trim($oGet->dataincfin) == '' ) {
	
		if ( trim($oGet->dataincini) != '' ) {
			$sHeadPeriodoInscr .= " a partir de {$oGet->dataincini}";
		} else {
			$sHeadPeriodoInscr .= " at� {$oGet->dataincfin}";
		}
		
	} else {
		$sHeadPeriodoInscr .= " de {$oGet->dataincini} � {$oGet->dataincfin}";
	}
	
	$aListaHead[] = $sHeadPeriodoInscr;
}


if ( trim($oGet->databaixaini) != '' || trim($oGet->databaixafin) != '' ) {
  
  $sHeadPeriodoBaixa = " Inscri��es Baixadas do Simples"; 
  
  if ( trim($oGet->databaixaini) == '' || trim($oGet->databaixafin) == '' ) {
  
    if ( trim($oGet->databaixaini) != '' ) {
      $sHeadPeriodoBaixa .= " a partir de {$oGet->databaixaini}";
    } else {
      $sHeadPeriodoBaixa .= " at� {$oGet->databaixafin}";
    }
    
  } else {
    $sHeadPeriodoBaixa .= " de {$oGet->databaixaini} � {$oGet->databaixafin}";
  }
  
  $aListaHead[] = $sHeadPeriodoBaixa;
}


$head2 = " Relat�rio de Optantes pelo Simples ";

foreach ( $aListaHead as $iInd => $sHead ) {
 	${'head'.( $iInd + 3 )} = $sHead; 
}


$oPdf = new PDF();
$oPdf->Open();

$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);

$iFonte    = 7;
$iAlt      = 5;
$iPreenche = 1;

imprimeCab($oPdf,$iAlt,$iFonte,true);

for ( $iInd=0; $iInd < $iLinhasSimples; $iInd++ ) {
  
  $oDadosSimples = db_utils::fieldsMemory($rsDadosSimples,$iInd);
  
  imprimeCab($oPdf,$iAlt,$iFonte);
  
  if ($iPreenche == 1 ) {
    $iPreenche = 0;
  } else {
    $iPreenche = 1;
  }
  
  $oPdf->Cell(20 ,$iAlt,$oDadosSimples->inscricao                              ,0,0,'C',$iPreenche);
  $oPdf->Cell(15 ,$iAlt,$oDadosSimples->numcgm                                 ,0,0,'C',$iPreenche);
  $oPdf->Cell(70 ,$iAlt,$oDadosSimples->nome                                   ,0,0,'L',$iPreenche);
  $oPdf->Cell(70 ,$iAlt,$oDadosSimples->descricao_atividade                    ,0,0,'L',$iPreenche);
  $oPdf->Cell(45 ,$iAlt,$oDadosSimples->categoria                              ,0,0,'L',$iPreenche);
  $oPdf->Cell(20 ,$iAlt,db_formatar($oDadosSimples->data_inclusao_simples ,'d'),0,0,'C',$iPreenche);
  $oPdf->Cell(20 ,$iAlt,db_formatar($oDadosSimples->data_baixa_simples    ,'d'),0,0,'C',$iPreenche);
  $oPdf->Cell(20 ,$iAlt,db_formatar($oDadosSimples->data_baixa_inscricao  ,'d'),0,1,'C',$iPreenche);
  
  
}

$oPdf->Output();

function imprimeCab($oPdf,$iAlt,$iFonte,$lImprime=false){

  if ($oPdf->gety() > $oPdf->h - 30 || $lImprime ){
    
    $oPdf->AddPage("L");
    $oPdf->SetFont('Arial','b',$iFonte);

    $oPdf->Cell(20 ,$iAlt,"Inscri��o"           ,1,0,'C',1);
    $oPdf->Cell(15 ,$iAlt,"CGM"                 ,1,0,'C',1);
    $oPdf->Cell(70 ,$iAlt,"Nome / Raz�o Social" ,1,0,'C',1);
    $oPdf->Cell(70 ,$iAlt,"Atividade"           ,1,0,'C',1);
    $oPdf->Cell(45 ,$iAlt,"Categoria"           ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Inclus�o"            ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Baixa"               ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Baixa Inscri��o"     ,1,1,'C',1);
    
    $oPdf->SetFont('Arial','',$iFonte);
      
  }
  
}
?>