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
include("fpdf151/assinatura.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
include("libs/db_app.utils.php");


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);


$iAno     = $_GET['ano'];
$sOrdem   = $_GET['ordem'];
$sWhere   = "";
$sCgmAte  = "";
$sCgmDe   = "";
$iCgms    = "";
$sCgm     = "";
$sCpfCnpj = "";
$iPago      = 0;
$iEstornado = 0;
$iTotalPago = 0;
$iTotalEstornado = 0;

if ($sOrdem == 'a') {
	$sOrdem = "z01_numcgm";
} elseif ($sOrdem == 'n') {
	$sOrdem = "z01_nome";
}

if (isset($_GET['de'])) {
	$sCgmDe = $_GET['de'];
}

if (isset($_GET['ate'])) {
	$sCgmAte = $_GET['ate'];
}

if (isset($_GET['cgms'])) {
	$iCgms = $_GET['cgms'];
}

if (isset($_GET['cgm'])) {
	$sCgm = "geral";
}

if ($sCgmDe != null && $sCgmAte != null) {
	$sWhere = "and z01_numcgm between {$sCgmDe} and {$sCgmAte}";
} 
if ($iCgms != null) {
	$sWhere = "and z01_numcgm in ({$iCgms})";
} 
if ($sCgm == "geral") {
	$sWhere = "";
}

/*
echo $sWhere." ORDER BY ".$sOrdem;
die();
*/

$sSql = " SELECT z01_numcgm,
			        trim(z01_cgccpf) as z01_cgccpf,
			        trim(z01_nome)   as z01_nome,
			        coalesce(sum(case when c53_tipo = 30 then c70_valor else 0 end),0) as valor_pago,
			        coalesce(sum(case when c53_tipo = 31 then c70_valor else 0 end),0) as valor_estornado,
			        extract(month from c70_data) as mes,
			        coalesce( ( select e30_codigo            
			                      from retencaopagordem                
			                           inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial                                           
			                                                      and e23_recolhido is true                
			                           inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial                                           
			                                                      and e21_retencaotipocalc in(1,2)                                           
			                                                      and e21_retencaotiporecgrupo = 1                
			                           inner join retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial                
			                           inner join retencaonatureza        on e31_retencaonatureza = e30_sequencial                                                   
			                                                             and e31_retencaonatureza is not null          
			                     where e20_pagordem = c80_codord limit 1       ),'0') as tipo,
			        c80_codord,
			        o58_codele
			   from conlancam        
			        inner join conlancamdoc on c70_codlan  = c71_codlan        
			        inner join conlancamord on c70_codlan  = c80_codlan        
			        inner join conlancamemp on c75_codlan  = c70_Codlan        
			        inner join conlancamele on c70_codlan  = c67_codlan
			        inner join empempenho   on e60_numemp  = c75_numemp        
			        inner join cgm          on z01_numcgm  = e60_numcgm        
			        inner join conhistdoc   on c71_coddoc  = c53_coddoc        
			        inner join orcdotacao   on e60_coddot  = o58_coddot                                     
			                               and e60_anousu  = o58_anousu         
			        inner join orcunidade   on o41_unidade = o58_unidade                                     
			                               and o41_anousu  = o58_anousu                                     
			                               and o41_orgao   = o58_orgao  
			
				 where c70_data between '{$iAno}-01-01' and '{$iAno}-12-31' 
				    {$sWhere}  
				 group by z01_numcgm,
				          z01_cgccpf,
				          z01_nome,
				          6,
				          7,
				          c80_codord   ,
				          o58_codele
				order by {$sOrdem} ;";
			

$rsDados      = db_query($sSql); 
$aListaDados  = db_utils::getColectionByRecord($rsDados);
$aDados       = array();

foreach ($aListaDados as $oIndiceDados => $oValorDados) {
	
		
		
		$oCgm = new stdClass();
		$oCgm->cgm         = $oValorDados->z01_numcgm;
		$oCgm->nome        = $oValorDados->z01_nome;
		$oCgm->cpf         = $oValorDados->z01_cgccpf;
		$oCgm->ordens      = array();
		
		if (!isset($aDados[$oValorDados->z01_numcgm])) {
		  $aDados[$oValorDados->z01_numcgm] = $oCgm;
		}
		if ($oValorDados->valor_pago > 0 || $oValorDados->valor_estornado > 0) {		
		  
		$oOrdem             = new stdClass();
		$oOrdem->ordem      = $oValorDados->c80_codord;
		$oOrdem->mes        = $oValorDados->mes;
		$oOrdem->pago       = $oValorDados->valor_pago;
		$oOrdem->estornado  = $oValorDados->valor_estornado;
		
		$aDados[$oValorDados->z01_numcgm]->ordens[] = $oOrdem;
	}	
}

/*
echo "<pre>";
print_r($aDados);
echo "</pre>";
die();

*/


$head2  = "CONFERÊNCIA DOS DADOS CONTÁBEIS";
$head3  = "ANO  ".$iAno;


$oPdf = new PDF("P"); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', '', 9);
$iAlturalinha    = 4;

//imprimirCabecalho($oPdf, $iAlturalinha, true);

foreach ($aDados as  $oDados) {
	
	
  if (count($oDados->ordens) == 0) {
    continue;
  }
  
  if (strlen($oDados->cpf == 11)){
    $sCpfCnpj = "cpf";
  } else {
    $sCpfCnpj = "cnpj";
  }	
  $oPdf->setfont('arial','b',7);
  $oPdf->cell(160, $iAlturalinha, "CREDOR  - ".$oDados->cgm." - ".$oDados->nome." - ".
                                                                db_formatar($oDados->cpf,$sCpfCnpj),  "TB",  1, "L", 0);
 
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(40, $iAlturalinha, "",                 "TB",  0, "C", 0);
  $oPdf->cell(30, $iAlturalinha,  "ORDEM",           "TBR",  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "MÊS",             "TBR",   0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "VALOR PAGO",      "TBR",   0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "VALOR ESTORNADO", "TB", 1, "C", 0);   
	foreach ($oDados->ordens as $oValorDados) {
		
		
		$oPdf->setfont('arial','',6);
		$oPdf->cell(40, $iAlturalinha, "",                                        "TB",  0, "C", 0);
		$oPdf->cell(30, $iAlturalinha, "{$oValorDados->ordem}",                   "TBR",  0, "C", 0);
    $oPdf->cell(30,  $iAlturalinha, "{$oValorDados->mes}",                    "TBR",   0, "C", 0);
    $oPdf->cell(30,  $iAlturalinha, db_formatar($oValorDados->pago,"f"),      "TBR",   0, "R", 0);
    $oPdf->cell(30,  $iAlturalinha, db_formatar($oValorDados->estornado,"f"), "TB", 1, "R", 0);
    
    // totais
    $iPago           = $iPago + $oValorDados->pago;
    $iEstornado      = $iEstornado + $oValorDados->estornado;
    $iTotalEstornado = $iEstornado;
    $iTotalPago      = $iPago;
    imprimirCabecalho($oPdf, $iAlturalinha, false,$oDados->cgm,$oDados->nome,$oDados->cpf,$sCpfCnpj);
	}	
  
	//  EXIBE TOTALIZADORES
	$oPdf->setfont('arial','b',6);
  $oPdf->cell(40, $iAlturalinha, "",                                        "TB",  0, "C", 0);
  $oPdf->cell(30, $iAlturalinha, "",                                        "TBR",  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "Total",                          "TBR",   0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, db_formatar($iTotalPago,"f"),      "TBR",   0, "R", 0);
  $oPdf->cell(30,  $iAlturalinha, db_formatar($iTotalEstornado,"f"), "TB", 1, "R", 0);  	
	$oPdf->cell(30,  $iAlturalinha, "", "TB", 1, "R", 0);
	$iPago      = 0;
	$iEstornado = 0;
}



$oPdf->Output();


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime,$cgm,$nome,$cpf,$formato) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    if ( !$lImprime ) {
      
      $oPdf->AddPage("P");
      //imprimeInfoProxPagina($oPdf, $iAlturalinha, true);
    }
		/*
		 * Cabeçalho a ser Repetido nas paginas
		 */  
    
  $oPdf->setfont('arial','b',7);
  $oPdf->cell(160, $iAlturalinha, "CREDOR  - ".$cgm." - ".$nome." - ".db_formatar($cpf,$formato),  "TB",  1, "L", 0);

  $oPdf->setfont('arial','b',6);
  $oPdf->cell(40, $iAlturalinha, "",                 "TB",  0, "C", 0);
  $oPdf->cell(30, $iAlturalinha,  "ORDEM",           "TBR",  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "MÊS",             "TBR",   0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "VALOR PAGO",      "TBR",   0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "VALOR ESTORNADO", "TB", 1, "C", 0);   
     //$oPdf->cell(35,  $iAlturalinha, "TOTAL CREDOR R$", "LTB", 1, "C", 1);

  }
}

/**
 * Impime informacao da proxima pagina no relatorio
 *
 * @param Object type $oPdf
 * @param Integer type $iAlt
 * @param Boolean type $lInicio
 */
function imprimeInfoProxPagina($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 38 || $lImprime ) {
    
    $oPdf->SetFont('arial', '', 6);
    if ( $lImprime ) {
      $oPdf->Cell(280, ($iAlturalinha*2), 'Continuação '.($oPdf->PageNo())."/{nb}",          'T', 1, "R", 0);
    } else {
    	
      $oPdf->Cell(280, ($iAlturalinha*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}", 'T', 1, "R", 0);
      imprimirCabecalho($oPdf, $iAlturalinha, false,'');
    }
  }
} 

?>