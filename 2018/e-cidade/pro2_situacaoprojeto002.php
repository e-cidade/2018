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
require_once("classes/db_obrassituacaolog_classe.php");

$oGet     = db_utils::postMemory($_GET);

$clObrasSituacaoLog = new cl_obrassituacaolog();
$oPdf               = new PDF();

$sCampos  = 'ob24_iptubase,     ';
$sCampos .= 'j34_setor,         ';
$sCampos .= 'j30_descr,         ';
$sCampos .= 'j34_quadra,        ';
$sCampos .= 'j34_lote,          ';
$sCampos .= 'ob01_codobra,      ';
$sCampos .= 'ob01_nomeobra,     ';
$sCampos .= 'j14_nome,          ';
$sCampos .= 'ob07_numero,       ';
$sCampos .= 'z01_nome,          ';
$sCampos .= 'ob28_descricao,    ';
$sCampos .= 'ob29_data,         ';
$sCampos .= 'ob29_sequencial,   ';
$sCampos .= 'ob29_obs           ';

$sOrderBy = '';

$aWhere   = array();

$sFiltros  = '';

if (isset($oGet->data_inicial) and ($oGet->data_inicial != '') and 
    isset($oGet->data_final)   and ($oGet->data_final != '')) {
	
	$aWhere[] = "ob29_data between '{$oGet->data_inicial}' and '{$oGet->data_final}'";
	
	$sFiltros = "\nData: {$oGet->data_inicial} até {$oGet->data_final}"; 

} elseif (isset($oGet->data_inicial) and ($oGet->data_inicial != '')) {
	
	$aWhere[] = "ob29_data >= '{$oGet->data_inicial}'";
	
	$sFiltros = "\nDe: {$oGet->data_inicial}";
	
} elseif (isset($oGet->data_final) and ($oGet->data_final != '')) {

	$aWhere[] = "ob29_data <= '{$oGet->data_final}'";
	
	$sFiltros = "\nAté: {$oGet->data_final}";
	
} 

if (isset($oGet->situacao) and ($oGet->situacao != '')) {
	
	$aWhere[]  = "ob29_obrassituacao in ({$oGet->situacao})";
	
	$sFiltros .= "\nCódigo da(s) Situação(ões): {$oGet->situacao}";
	
	
}

$sSqlObrasSituacaoLog = $clObrasSituacaoLog->sql_query_obras_situacoes(null, 	
																																		   $sCampos,
																																		   'ob29_obras',
																																		   implode(' and ', $aWhere));

$rsObrasSituacaoLog   = $clObrasSituacaoLog->sql_record($sSqlObrasSituacaoLog);

if ($clObrasSituacaoLog->numrows == 0) {
  
  $sMsg = _M('tributario.projetos.pro2_situacaoprojeto002.nenhum_registro_encontrado');
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
	exit;
}


$aObrasSituacao       = db_utils::getCollectionByRecord($rsObrasSituacaoLog, true);

$aObrasRelatorio      = array();
$aSituacoes           = array();

foreach ( $aObrasSituacao as $oObras ) {
  
	$oDadosRelatorio                        = new stdClass();
	$oSituacao                              = new stdClass();
	
	$oDadosRelatorio->ob24_iptubase         = $oObras->ob24_iptubase;
	$oDadosRelatorio->j34_setor             = $oObras->j34_setor;
	$oDadosRelatorio->j30_descr             = $oObras->j30_descr;
	$oDadosRelatorio->j34_quadra            = $oObras->j34_quadra;
	$oDadosRelatorio->j34_lote              = $oObras->j34_lote;
	$oDadosRelatorio->ob01_codobra          = $oObras->ob01_codobra;
	$oDadosRelatorio->ob01_nomeobra         = $oObras->ob01_nomeobra;
	$oDadosRelatorio->j14_nome              = $oObras->j14_nome;
	$oDadosRelatorio->ob07_numero           = $oObras->ob07_numero;
	$oDadosRelatorio->z01_nome              = $oObras->z01_nome;
	
  $oSituacao->ob29_data                   = $oObras->ob29_data;                                        
	$oSituacao->ob28_descricao              = $oObras->ob28_descricao;
	$oSituacao->ob29_obs                    = $oObras->ob29_obs;
	
  $aObrasRelatorio[$oObras->ob01_codobra] = $oDadosRelatorio; 
  
  $aSituacoes[$oObras->ob01_codobra][]    = $oSituacao;
  
}

/**
 * RELATÓRIO
 */

$head3 = 'Relatório de Situações das Obras';

$head4 = 'Filtros Selecionados:' . $sFiltros;

$head5 = '';

$oPdf->Open();

$oPdf->AliasNbPages();

$oPdf->setfillcolor(235);
$oPdf->setfont('arial', '', 7);

$iTroca = 1;
$iPrenc = 0;
$iAlt   = 4;
$iTotal = 0;

foreach ($aObrasRelatorio as $iCodigoObra => $oObraSituacao) {
	
  $oPdf->setfont("arial", "b", 6);
  
	if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0 ){
	
		$oPdf->addpage();
		$oPdf->cell(45, $iAlt, 'Obra'        , 1, 0, "C", 1);
		$oPdf->cell(45, $iAlt, 'Endereço'    , 1, 0, "C", 1);
		$oPdf->cell(55, $iAlt, 'Proprietário', 1, 0, "C", 1);
		$oPdf->cell(15, $iAlt, 'Matrícula'   , 1, 0, "C", 1);
		$oPdf->cell(35, $iAlt, 'S/Q/L'       , 1, 1, "C", 1);
		$oPdf->cell(20, $iAlt, 'Data'        , 1, 0, "C", 1);
		$oPdf->cell(60, $iAlt, 'Situação'    , 1, 0, "C", 1);
		$oPdf->cell(115, $iAlt,'Observações' , 1, 1, "C", 1);
		$iTroca = 0;
			
	}
	
	$sSQL      = "{$oObraSituacao->j34_setor} - {$oObraSituacao->j30_descr} / {$oObraSituacao->j34_quadra} / {$oObraSituacao->j34_lote}";
	if ( empty($oObraSituacao->j34_setor) && empty($oObraSituacao->j34_quadra) && empty($oObraSituacao->j34_lote)) {
	  $sSQL = "";
	}
	$sObra     = "{$oObraSituacao->ob01_codobra} - {$oObraSituacao->ob01_nomeobra}";
	$sEndereco = "{$oObraSituacao->j14_nome}, {$oObraSituacao->ob07_numero}";
	
	$oPdf->cell(45, $iAlt, $sObra                       , 0, 0, "L", 0);
	$oPdf->cell(45, $iAlt, $sEndereco										, 0, 0, "L", 0);
	$oPdf->cell(55, $iAlt, $oObraSituacao->z01_nome			, 0, 0, "L", 0);
	$oPdf->cell(15, $iAlt, $oObraSituacao->ob24_iptubase, 0, 0, "C", 0);
	$oPdf->cell(35, $iAlt, $sSQL												, 0, 1, "C", 0);
	
	foreach ($aSituacoes[$iCodigoObra] as $oSituacao) {
		
		$oPdf->setfont("arial", "", 5);
		$oPdf->cell(20,  2.5, $oSituacao->ob29_data      , 0, 0, "C", 0);
		$oPdf->cell(60,  2.5, $oSituacao->ob28_descricao , 0, 0, "L", 0);
		$oPdf->cell(115, 2.5, $oSituacao->ob29_obs       , 0, 1, "L", 0);
		
	} 
	
	$iTotal++;
}

$oPdf->setfont("arial", "b", 8);
$oPdf->cell(195, $iAlt, "TOTAL DE REGISTROS  :  ".$iTotal, "T", 0, "L", 0);

$oPdf->Output();