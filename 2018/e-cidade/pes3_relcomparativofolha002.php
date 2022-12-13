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
include("libs/db_libpessoal.php");
include("libs/db_utils.php");
include("classes/db_rhpessoal_classe.php");
  
$oGet            = db_utils::postMemory($_GET);
$cl_rhpessoal    = new cl_rhpessoal();
$aTipoFolha      = array();
$sFiltro         = $oGet->sFiltro == 'diferentes' ? "Valores Diferentes": "Todos";
$sFiltroRubricas = $oGet->sFiltroRubrica == 'selecionadas' ? $oGet->rubricas_selecionadas_text : "Proventos";
$sRubricas       = $oGet->rubricas_selecionadas_text;

/*
* Tipo de rubrica selecionada:
* Provento = 1
* Desconto = 2
* Base     = 3
*/
$iTipoRubrica     = $oGet->iFiltroTipoRubrica; 

 if(isset($sRubricas) && $sRubricas != '') {
    $aRubricas     = explode(',',$sRubricas);
    $sRubricas     = "'".implode("','",$aRubricas)."'";
 }

/*
* Variável que testa se há dados para ser exibidos no relatório.
*/
$lErro = true;

foreach (array_reverse($oGet->objeto2) as $sTipoFolha){

	switch ($sTipoFolha){ 
	  case 'gerfsal':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'SALÁRIO';
	     $aTitulo[0]                         = 'Salário';          
	     $sSigla                             = 'r14';
	  break;
	  case 'gerfs13':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = '13o SALÁRIO';
       $aTitulo[2]                         = '13o Salário';          
	     $sSigla                             = 'r35';
	  break;
	  case 'gerfcom':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'COMPLEMENTAR';
       $aTitulo[3]                         = 'Complementar';          
	     $sSigla                             = 'r48';
	  break;
    case 'gerfadi':
       $aTipoFolha[$sTipoFolha]['sTitulo'] = 'ADIANTAMENTO';
       $aTitulo[3]                         = 'Adiantamento';          
       $sSigla                             = 'r22';
    break;
	  
  }

  if (isset($sRubricas) && $sRubricas != ''){

    $sSql  = "select distinct                                                                                         ";
    $sSql .= "rh02_regist, rh01_admiss,                                                                               ";
    $sSql .= "z01_nome,                                                                                               ";
    $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                                                         ";
    $sSql .= "      where  {$sSigla}_pd     = {$iTipoRubrica}                                                                       ";
    $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoBase}                                                       ";
    $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesBase}                                                       ";
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                                                ";
    $sSql .= "        and  {$sSigla}_rubric in ({$sRubricas})                                                           ";
    $sSql .= ") as vlr_base,                                                                                          ";
    $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                                                         ";
    $sSql .= "      where  {$sSigla}_pd     = {$iTipoRubrica}                                                                       ";
    $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoCompara}                                                    ";
    $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesCompara}                                                    ";
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                                                ";
    $sSql .= "        and  {$sSigla}_rubric in ({$sRubricas})                                                         ";
    $sSql .= ") as vlr_compara                                                                                        ";
    $sSql .= "from rhpessoalmov                                                                                       ";
    $sSql .= "    inner join rhpessoal      on rh01_regist  = rh02_regist                                             ";
    $sSql .= "    inner join cgm            on rh01_numcgm  = z01_numcgm                                              ";
    $sSql .= "where rh02_instit  = ".db_getsession('DB_instit')."                                                     ";
    $sSql .= "  and  (                                                                                                ";
    $sSql .= "          (rh02_anousu = {$oGet->iAnoBase}     and rh02_mesusu = {$oGet->iMesBase})                     ";
    $sSql .= "        or                                                                                              ";
    $sSql .= "          (rh02_anousu = {$oGet->iAnoCompara}  and rh02_mesusu = {$oGet->iMesCompara})                  ";
    $sSql .= "       )                                                                                                ";  
    $sSql .= "  and rh02_regist in                                                                                    ";
    $sSql .= "(select {$sSigla}_regist from {$sTipoFolha}                                                             ";
    $sSql .= "      where  {$sSigla}_pd     = {$iTipoRubrica}                                                                       ";
    $sSql .= "        and  (                                                                                          ";
    $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoBase}  and {$sSigla}_mesusu = {$oGet->iMesBase})        ";
    $sSql .= "              or                                                                                        ";
    $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoCompara}  and {$sSigla}_mesusu = {$oGet->iMesCompara})  ";
    $sSql .= "             )                                                                                          "; 
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                                                ";
    $sSql .= "        and  {$sSigla}_rubric in ({$sRubricas}))                                                          ";
    $sSql .= "order by z01_nome                                                                                       ";
    $rsSql               = $cl_rhpessoal->sql_record($sSql);
    $iNumRows            = $cl_rhpessoal->numrows;

    if($iNumRows > 0) {
      $lErro = false;
    }
  }
  if ($sRubricas == ""){

    $sSql  = "select distinct                                                                 					              ";
    $sSql .= "rh02_regist, rh01_admiss,                                                										            ";
    $sSql .= "z01_nome,                                                                										            ";
    $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                          										            ";
    $sSql .= "      where  {$sSigla}_pd     = 1                                      										            ";
    $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoBase}                        										            ";
    $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesBase}                        										            ";
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										            ";
    $sSql .= "        and  {$sSigla}_rubric < 'R950'                                   										            ";
    $sSql .= ") as vlr_base,                                                           										            ";
    $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                          										            ";
    $sSql .= "      where  {$sSigla}_pd     = 1                                        										            ";
    $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoCompara}                     										            ";
    $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesCompara}                     										            ";
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										            ";
    $sSql .= "        and  {$sSigla}_rubric < 'R950'                                   										            ";
    $sSql .= ") as vlr_compara                                                         										            ";
    $sSql .= "from rhpessoalmov                                                        										            ";
    $sSql .= "    inner join rhpessoal      on rh01_regist  = rh02_regist              										            ";
    $sSql .= "    inner join cgm            on rh01_numcgm  = z01_numcgm               										            ";
    $sSql .= "where rh02_instit  = ".db_getsession('DB_instit')."                										                  ";
    $sSql .= "  and  ( 																																							                  ";
    $sSql .= "          (rh02_anousu = {$oGet->iAnoBase}     and rh02_mesusu = {$oGet->iMesBase})                     ";
    $sSql .= "        or 																																						                  ";
    $sSql .= "          (rh02_anousu = {$oGet->iAnoCompara}  and rh02_mesusu = {$oGet->iMesCompara})                  ";
    $sSql .= "       )                                        																			                  ";  
    $sSql .= "  and rh02_regist in                                               										                  ";
    $sSql .= "(select {$sSigla}_regist from {$sTipoFolha}                              										            ";
    $sSql .= "      where  {$sSigla}_pd     = 1                                                                      ";
    $sSql .= "        and  ( 																																							            ";
    $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoBase}  and {$sSigla}_mesusu = {$oGet->iMesBase})        ";
    $sSql .= "              or             																																						";
    $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoCompara}  and {$sSigla}_mesusu = {$oGet->iMesCompara})	";
    $sSql .= "             )                                                    																			"; 
    $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										            ";
    $sSql .= "        and  {$sSigla}_rubric < 'R950')                                  									              ";
    $sSql .= "order by z01_nome                                                        										            ";
    $rsSql               = $cl_rhpessoal->sql_record($sSql);
  	$iNumRows            = $cl_rhpessoal->numrows;


    if($iNumRows > 0) {
       $lErro = false;
    }
  }

  if($lErro) {
     db_redireciona('db_erros?fechar=true&db_erro=Nenhum resultado encontrado.');
  }

	if($iNumRows > 0 ) {
		/**
		 * Cria array de Objetos com os registros de Cada Tipo de Folha selecionda.
		 */
		$aTipoFolha[$sTipoFolha]['aDados'] = db_utils::getCollectionByRecord($rsSql,true);  
	}
}
$pdf    = new PDF(); 
$head1  = "RELATÓRIO COMPARATIVO ENTRE FOLHAS";
$head3  = "Tipos de Folha: ".implode(", ",$aTitulo);
$head4  = "Ano/Mês Base: ".$oGet->iAnoBase."/".$oGet->iMesBase;
$head5  = "Ano/Mês a Comparar: ".$oGet->iAnoCompara."/".$oGet->iMesCompara;
if (strlen($sFiltroRubricas) >= 30) {
  $sFiltroRubricas = substr($sFiltroRubricas, 0, 30).'...';
}
$head6  = "Rubricas: ".$sFiltroRubricas;
$head7  = "Servidores: ".$sFiltro;
$pdf   -> Open(); 
$pdf   -> AddPage();
$pdf   -> AliasNbPages(); 
$pdf   -> setfillcolor(235);
$iI = 0;

/**
 * Percorre Array com os Tipos de Cada folha selecionada
 */
foreach ($aTipoFolha as $aTipo) {
  /*
   * Se existirem dados no array imprime cabeçalho e Dados
   */
	if (isset($aTipo['aDados'])) {
		
	  if ($iI > 0) {
	    $pdf->AddPage();
	  }
	  cabecalhoPagina01($pdf,$aTipo['sTitulo']);
	  cabecalhoPagina02($pdf);
	  $nTotalBase    = 0;
    $nTotalCompara = 0;
    /**
     * Percorre dados Referente a cada Tipo de Folha qual foi selecionado de Relatório
     */
		foreach ($aTipo['aDados'] as $oTipoFolha) {
			
      if ( ($oGet->sFiltro == 'diferentes' && ($oTipoFolha->vlr_base - $oTipoFolha->vlr_compara) <> 0) || $oGet->sFiltro == 'todos') {
      	
				if ($pdf->GetY() > $pdf->h - 25) {
			
			    $pdf->AddPage();
			    cabecalhoPagina02($pdf);
			  }   
			  if($oTipoFolha->vlr_base > $oTipoFolha->vlr_compara){
			    $nVlrDiferenca = $oTipoFolha->vlr_base - $oTipoFolha->vlr_compara;
			  } else {
			    $nVlrDiferenca = $oTipoFolha->vlr_compara - $oTipoFolha->vlr_base;
			  }
			  $sData = implode("/",explode("-",$oTipoFolha->rh01_admiss));
			  $pdf->Cell(15, 5, $oTipoFolha->rh02_regist,1,0,'C',0,"","");
			  $pdf->Cell(82, 5, $oTipoFolha->z01_nome,   1,0,'L',0,"","");
			  $pdf->Cell(20, 5, $sData,1,0,'C',0,"","");
			  $pdf->Cell(25, 5, db_formatar($oTipoFolha->vlr_base,"f"),1,0,'R',0,"","");
			  $pdf->Cell(25, 5, db_formatar($oTipoFolha->vlr_compara,"f"),1,0,'R',0,"","");
			  $pdf->Cell(25, 5, db_formatar($nVlrDiferenca,"f"),1,1,'R',0,"","");
			  $nTotalBase    += $oTipoFolha->vlr_base;
        $nTotalCompara += $oTipoFolha->vlr_compara;
      }		
    }
    /**
     * Imprime Rodapé com o Total dos valores de cada coluna
     */
    $pdf->SetFont('','B');
    $pdf->Cell(117, 5, "TOTAL   ",1,0,'R',1,"","");
    $pdf->Cell( 25, 5, db_formatar($nTotalBase,"f"),1,0,'R',1,"","");
    $pdf->Cell( 25, 5, db_formatar($nTotalCompara,"f"),1,0,'R',1,"","");
    $pdf->Cell( 25, 5, db_formatar($nTotalBase - $nTotalCompara, "f"),1,1,'R',1,"","");
    $pdf->SetFont('','');
    $iI++;
	}
}
$pdf->Output();

/**
 * Imprime Cabeçalho que contem o titulo referente ao tipo de Folha selecionado...
 *
 * @param object $pdf
 * @param string $sTitulo
 */
function cabecalhoPagina01(&$pdf,$sTitulo) {
  
  $pdf->SetFont('','B');
  $pdf->Cell(192, 4, "COMPARATIVO FOLHA {$sTitulo} ",1,1,'C',1,"","");
  $pdf->SetFont('','');
}

/**
 * Imprime cabeçalhos da Tabela com as Label das celulas da tabela.
 *
 * @param Object $pdf
 */
function cabecalhoPagina02(&$pdf) {
  
  $pdf->SetFont('','B');
  $pdf->Cell(15,  4, "Matrícula",1,0,'C',1,"","");
  $pdf->Cell(82,  4, "Nome",1,0,'C',1,"","");
  $pdf->Cell(20 , 4, "Data Admiss.",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Valor Base",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Valor a Comparar",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Diferença",1,1,'C',1,"","");
  $pdf->SetFont('','');
}
?>
