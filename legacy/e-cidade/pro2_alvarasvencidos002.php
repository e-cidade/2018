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
require_once("classes/db_obrasalvara_classe.php");

$oGet = db_utils::postMemory($_GET);

$clObrasAlvara = new cl_obrasalvara();
$oPdf          = new PDF();

$aWhere   = array();
$sFiltros = '';
$lHabite  = null;

$sCampos  = "ob04_codobra,                                                                    ";
$sCampos .= "ob04_alvara,                                                                     ";
$sCampos .= "ob24_iptubase,                                                                   ";
$sCampos .= "j34_setor || '-' || j30_descr || ' / ' ||j34_quadra || ' / ' || j34_lote as sql, ";     
$sCampos .= "z01_nome,                                                                        ";
$sCampos .= "ob08_area || ' m2' as ob08_area,                                                 ";
$sCampos .= "ob04_data,                                                                       ";
$sCampos .= "ob04_dtvalidade                                                                  ";

if (isset($oGet->data_inicial) and ($oGet->data_inicial != '') and
    isset($oGet->data_final)   and ($oGet->data_final != '')) {

	$aWhere[] = "ob04_dtvalidade between '{$oGet->data_inicial}' and '{$oGet->data_final}'";

	$sFiltros = "\nData: {$oGet->data_inicial} até {$oGet->data_final}";

} elseif (isset($oGet->data_inicial) and ($oGet->data_inicial != '')) {
	
	$aWhere[] = "ob04_dtvalidade >= '{$oGet->data_inicial}'";
	
	$sFiltros = "\nDe: {$oGet->data_inicial}";
	
} elseif (isset($oGet->data_final) and ($oGet->data_final != '')) {

	$aWhere[] = "ob04_dtvalidade <= '{$oGet->data_final}'";
	
	$sFiltros = "\nAté: {$oGet->data_final}";
	
} else {
	
	$dDataAtual = date('d/m/Y', db_getsession('DB_datausu'));
	
	$aWhere[]   = "ob04_dtvalidade <= '{$dDataAtual}'";
	
	$sFiltros   = "\nAté: {$dDataAtual}";
	
}

if (isset($oGet->habite) and ($oGet->habite != '')) {
	
	if ($oGet->habite == '1') {
	  
		$aWhere[]  = "ob09_codhab is not null";
		$sFiltros .= "\nHabite-se: Com Habite-se";
		$lHabite   = true;
		
	} else {
		
		$aWhere[]  = "ob09_codhab is null";
		$sFiltros .= "\nHabite-se: Sem Habite-se";
		$lHabite   = false;
		
	}
	
}

if (isset($oGet->logradouros) and ($oGet->logradouros != '')) {
	
	$aWhere[]  = "ob07_lograd in ({$oGet->logradouros})"; 	
	$sFiltros .= "\nLogradouros: {$oGet->logradouros}";
	
}

if (isset($oGet->bairros) and ($oGet->bairros != '')) {

	$aWhere[] = "ob07_bairro in ({$oGet->bairros})";
	$sFiltros .= "\nBairros: {$oGet->bairros}";

}

$aWhere[]        = "ob04_dtvalidade is not null";

$sOrderBy        = "ob04_dtvalidade";

$sSqlObrasAlvara = $clObrasAlvara->sql_query_obrasalvaras_relatorio($sCampos, implode(' and ', $aWhere), $lHabite, $sOrderBy);

$rsObrasAlvara   = $clObrasAlvara->sql_record($sSqlObrasAlvara);

if($clObrasAlvara->numrows == 0) {
  
  $sMsg = _M('tributario.projetos.pro2_alvarasvencidos002.nenhum_registro_encontrado');
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
	exit;	
}

$aObrasAlvaraVencidos = db_utils::getCollectionByRecord($rsObrasAlvara, true);


$head3 = 'Relatório de Vencimento de Alvarás';

$head4 = 'Filtros Selecionados:' . $sFiltros;

$oPdf->Open();

$oPdf->AliasNbPages();

$oPdf->setfillcolor(235);
$oPdf->setfont('arial', '', 7);

$iCorFundo = 0;
$iTroca    = 1;
$iAlt      = 4;
$iTotal    = 0;

foreach ($aObrasAlvaraVencidos as $oObraAlvaraVencido) {
	
	
	if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0 ){
		$oPdf->setfont("arial", "b", 7);
	
		$oPdf->addpage();
		$oPdf->cell(15, $iAlt, 'Obra'          , 1, 0, "C", 1);
		$oPdf->cell(15, $iAlt, 'Alvará'        , 1, 0, "C", 1);
		$oPdf->cell(15, $iAlt, 'Matrícula'     , 1, 0, "C", 1);
		$oPdf->cell(45, $iAlt, 'S/Q/L'         , 1, 0, "C", 1);
		$oPdf->cell(47, $iAlt, 'Proprietário'  , 1, 0, "C", 1);
		$oPdf->cell(15, $iAlt, 'Área'          , 1, 0, "C", 1);
		$oPdf->cell(20, $iAlt, 'Data Liberação', 1, 0, "C", 1);
		$oPdf->cell(20, $iAlt, 'Data Validade' , 1, 1, "C", 1);
		
		$iCorFundo = 1;
		$iTroca    = 0;
			
	}	
	
	$iCorFundo = $iCorFundo == 0 ? 1 : 0;
	
	$oPdf->setfont("arial", "", 6);
	$oPdf->cell(15, $iAlt, $oObraAlvaraVencido->ob04_codobra   , 0, 0, "C", $iCorFundo);
	$oPdf->cell(15, $iAlt, $oObraAlvaraVencido->ob04_alvara    , 0, 0, "C", $iCorFundo);
	$oPdf->cell(15, $iAlt, $oObraAlvaraVencido->ob24_iptubase  , 0, 0, "C", $iCorFundo);
	$oPdf->cell(45, $iAlt, $oObraAlvaraVencido->sql					   , 0, 0, "L", $iCorFundo);
	$oPdf->cell(47, $iAlt, $oObraAlvaraVencido->z01_nome       , 0, 0, "L", $iCorFundo);
	$oPdf->cell(15, $iAlt, $oObraAlvaraVencido->ob08_area      , 0, 0, "C", $iCorFundo);
	$oPdf->cell(20, $iAlt, $oObraAlvaraVencido->ob04_data      , 0, 0, "C", $iCorFundo);
	$oPdf->cell(20, $iAlt, $oObraAlvaraVencido->ob04_dtvalidade, 0, 1, "C", $iCorFundo);
	
	$iTotal++;
	
}

$oPdf->setfont("arial", "b", 8);
$oPdf->cell(192, $iAlt, "TOTAL DE REGISTROS  :  ".$iTotal, "T", 0, "L", 0);

$oPdf->Output();