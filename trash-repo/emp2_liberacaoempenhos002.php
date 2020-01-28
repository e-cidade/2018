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
//include("libs/db_stdlib.php");
include("libs/db_utils.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empempenholiberado_classe.php");

$oGet  = db_utils::postMemory($_GET,0);

$iInstit       = db_getsession('DB_instit');
$sHeadEmpemhos = "Todos";
$sHeadDtLib    = "";
$sWhere        = "";
$sAnd          = "";

/*
 * Filtra por DATA DE LIBERAÇÃO
 */

if (isset($oGet->dtliberacaoini) && isset($oGet->dtliberacaofim)) {
      
  if (!empty($oGet->dtliberacaoini)) {
    $dtDataIni = split("/", $oGet->dtliberacaoini);
    $dtDataIni = $dtDataIni[2]."-".$dtDataIni[1]."-".$dtDataIni[0];
  }

  if (!empty($oGet->dtliberacaofim)) {
    $dtDataFim = split("/", $oGet->dtliberacaofim);
    $dtDataFim = $dtDataFim[2]."-".$dtDataFim[1]."-".$dtDataFim[0];
  }
       
  if (!empty($dtDataIni) && !empty($dtDataFim)) {
  	
    $sHeadDtLib = $oGet->dtliberacaoini." até ".$oGet->dtliberacaofim;
    $sWhere .= "{$sAnd} e60_emiss between '{$dtDataIni}' and '{$dtDataFim}'";
    $sAnd    = " and ";
  } else if (!empty($dtDataIni)) {

  	$sHeadDtLib = $oGet->dtliberacaoini;
    $sWhere .= "{$sAnd} e60_emiss = '{$dtDataIni}'";
    $sAnd    = " and ";     
  } else if (!empty($dtDataFim)) {
    
  	$sHeadDtLib = " até ".$oGet->dtliberacaofim;
    $sWhere .= "{$sAnd} e60_emiss <= '{$dtDataFim}'";
    $sAnd    = " and ";           
  }
}

/*
 * Filtra por EMPENHOS - t=Todos, l=Liberados, n=Não Liberados  
 */

if (isset($oGet->empenhos) && !empty($oGet->empenhos)) {
  
  if ($oGet->empenhos == 'l') {
    
  	$sHeadEmpemhos = "Liberados";
    $sWhere .= "{$sAnd} e22_numemp is not null";
    $sAnd    = " and "; 
  } else if ($oGet->empenhos == 'n') {
    
  	$sHeadEmpemhos = "Não Liberados";
    $sWhere .= "{$sAnd} e22_numemp is null";
    $sAnd    = " and ";
     
  }
}

$sWhere .= " {$sAnd} e60_instit = {$iInstit}                                                                          ";
$sWhere .= "    and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0                                 ";
$sWhere .= "     and not exists (select 1                                                                             ";
$sWhere .= "                        from matordemitem                                                                 ";
$sWhere .= "                             left join matordemanu on m53_codordem = m52_codordem                         ";
$sWhere .= "                       where m52_numemp = e60_numemp                                                      ";
$sWhere .= "                         and m53_codordem is null)                                                        ";

if (!empty($sWhere)) {
	$sWhere = "where {$sWhere}";
}

$sSql  = "    select e60_numemp,                                                                                      "; 
$sSql .= "           e60_codemp,                                                                                      ";
$sSql .= "           e60_anousu,                                                                                      ";
$sSql .= "           e60_vlremp,                                                                                      ";
$sSql .= "           e60_vlrliq,                                                                                      ";
$sSql .= "           e60_vlranu,                                                                                      ";
$sSql .= "           z01_cgccpf,                                                                                      ";
$sSql .= "           e22_sequencial,                                                                                  ";
$sSql .= "           z01_nome,                                                                                        ";
$sSql .= "           e60_emiss,                                                                                       ";
$sSql .= "           (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) as saldo,                          ";        
$sSql .= "           exists (select 1                                                                                 ";
$sSql .= "                     from matordemitem                                                                      ";
$sSql .= "                          left join matordemanu on m53_codordem = m52_codordem                              "; 
$sSql .= "                    where m52_numemp = e60_numemp                                                           ";
$sSql .= "                      and m53_codordem is null) as temordemdecompra,                                        ";
$sSql .= "           (select ridepto||'-'||descrdepto                                                                 ";
$sSql .= "              from fc_origem_empenho(e60_numemp)                                                            ";
$sSql .= "                   inner join db_depart on ridepto = coddepto limit 1) as origem                            "; 
$sSql .= "      from empempenho                                                                                       ";
$sSql .= "           left  join empempenholiberado on e22_numemp = e60_numemp                                         "; 
$sSql .= "           inner join cgm                on z01_numcgm = e60_numcgm                                         ";
$sSql .= " {$sWhere}                                                                                                  ";
$sSql .= "  order by e60_numemp                                                                                       ";

$rsSql   = db_query($sSql);
$iRsSql  = pg_num_rows($rsSql);

if ($iRsSql == 0){
	
  if (!empty($dtDataIni) && !empty($dtDataFim)) {
  	if ($dtDataFim < $dtDataIni) {
  		db_msgbox('Datas inválidas. Verifique!');
  	}
  }
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

/*
 * Monta os cabecalho do relatorio
 */

$head2 = "RELATÓRIO DE LIBERAÇÃO DE EMPENHOS";
$head4 = "Empenhos: ".$sHeadEmpemhos;

if (!empty($sHeadDtLib)) {
  $head6 = "Data de Liberação: ".$sHeadDtLib;	
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$prenc = 0;
$alt   = 4;

/*
 * Se retornar registros monta relatorio
 */

for ( $iInd = 0; $iInd  < $iRsSql; $iInd++ ) {
	 
	  $oEmpenhos = db_utils::fieldsMemory($rsSql,$iInd);

    if ( $pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    
      $alt = 6;
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Número"                                                                           ,1,0,"C",1);
      $pdf->cell(20,$alt,"Código"                                                                           ,1,0,"C",1);
      $pdf->cell(20,$alt,"Valor"                                                                            ,1,0,"C",1);
      $pdf->cell(20,$alt,"Saldo"                                                                            ,1,0,"C",1);
      $pdf->cell(30,$alt,"CNPJ/CPF"                                                                         ,1,0,"C",1);
      $pdf->cell(70,$alt,"Credor"                                                                           ,1,0,"C",1);
      $pdf->cell(25,$alt,"Data Emissão"                                                                     ,1,0,"C",1);
      $pdf->cell(60,$alt,"Depto Origem"                                                                     ,1,0,"C",1);
      $pdf->cell(15,$alt,"Liberado"                                                                         ,1,1,"C",1);

      $troca               = 0;
      $prenc               = 1;
      $nTotalGeralVlr      = 0;
      $nTotalGeralVlrSaldo = 0;
    }
    
    if ( $prenc == 0 ) {
      $prenc = 1;
    } else {
      $prenc = 0;
    }
    
    if (!empty($oEmpenhos->e22_sequencial)) {
    	$sLiberado = "sim";
    } else {
    	$sLiberado = "não";
    }
    
    if (!empty($oEmpenhos->z01_cgccpf)) {
    	$iTam = strlen($oEmpenhos->z01_cgccpf);
    	if ($iTam == 11) {
    		$sCpfCnpj = db_formatar($oEmpenhos->z01_cgccpf,'cpf');
    	} else {
    		$sCpfCnpj = db_formatar($oEmpenhos->z01_cgccpf,'cnpj');
    	}
    } else {
    	$sCpfCnpj = '';
    }
    
    $pdf->setfont('arial','',7);
    $pdf->cell(20,$alt,$oEmpenhos->e60_numemp                                                          ,0,0,"C",$prenc);
    $pdf->cell(20,$alt,$oEmpenhos->e60_codemp."/".$oEmpenhos->e60_anousu                               ,0,0,"R",$prenc);
    $pdf->cell(20,$alt,db_formatar($oEmpenhos->e60_vlremp,'f')                                         ,0,0,"R",$prenc);
    $pdf->cell(20,$alt,db_formatar($oEmpenhos->saldo,'f')                                              ,0,0,"R",$prenc);
    $pdf->cell(30,$alt,$sCpfCnpj                                                                       ,0,0,"C",$prenc);
    $pdf->cell(70,$alt,$oEmpenhos->z01_nome                                                            ,0,0,"L",$prenc);
    $pdf->cell(25,$alt,db_formatar($oEmpenhos->e60_emiss,'d')                                          ,0,0,"C",$prenc);
    $pdf->cell(60,$alt,$oEmpenhos->origem                                                              ,0,0,"L",$prenc);
    $pdf->cell(15,$alt,$sLiberado                                                                      ,0,1,"C",$prenc);
    
    $nTotalGeralVlr      += $oEmpenhos->e60_vlremp;
    $nTotalGeralVlrSaldo += $oEmpenhos->saldo;
	
}

/*
 * Total de registros retornados
 */

$pdf->setfont('arial','b',8);
$pdf->cell(280,2,''                                                                                           ,0,1,0,0);
$pdf->cell(280,0,''                                                                                       ,"T",1,"L",0);
$pdf->cell(40,6,'TOTAL GERAL:  '                                                                            ,0,0,"R",0);
$pdf->cell(20,6,db_formatar($nTotalGeralVlr,'f')                                                            ,0,0,"R",0);
$pdf->cell(20,6,db_formatar($nTotalGeralVlrSaldo,'f')                                                       ,0,1,"R",0);
$pdf->cell(40,6,'TOTAL DE REGISTROS:  '                                                                     ,0,0,"R",0);
$pdf->cell(20,6,$iRsSql                                                                                     ,0,0,"R",0);
 
$pdf->Output();
?>