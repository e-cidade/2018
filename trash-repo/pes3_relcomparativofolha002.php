<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$oGet          = db_utils::postMemory($_GET);
$cl_rhpessoal  = new cl_rhpessoal();
$aTipoFolha    = array();
$sFiltro       = $oGet->sFiltro == 'diferentes' ? "Valores Diferentes": "Todos";
foreach (array_reverse($oGet->objeto2) as $sTipoFolha){

	switch ($sTipoFolha){ 
	  case 'gerfsal':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'SAL�RIO';
	     $aTitulo[0]                         = 'Sal�rio';          
	     $sSigla                             = 'r14';
	  break;
	  case 'gerfres':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'RESCIS�O';
       $aTitulo[1]                         = 'Rescis�o';          
	     $sSigla                             = 'r20';
	  break;
	  case 'gerfs13':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = '13o SAL�RIO';
       $aTitulo[2]                         = '13o Sal�rio';          
	     $sSigla                             = 'r35';
	  break;
	  case 'gerfcom':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'COMPLEMENTAR';
       $aTitulo[3]                         = 'Complementar';          
	     $sSigla                             = 'r48';
	  break;
	  case 'gerffer':
	     $aTipoFolha[$sTipoFolha]['sTitulo'] = 'F�RIAS';
       $aTitulo[4]                         = 'F�rias';          
	     $sSigla                             = 'r31';
	  break;
	}

  $sSql  = "select distinct                                                                 										";
  $sSql .= "rh02_regist, rh01_admiss,                                                										";
  $sSql .= "z01_nome,                                                                										";
  $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                          										";
  $sSql .= "      where  {$sSigla}_pd     = 1                                        										";
  $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoBase}                        										";
  $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesBase}                        										";
  $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										";
  $sSql .= "        and  {$sSigla}_rubric < 'R950'                                   										";
  $sSql .= ") as vlr_base,                                                           										";
  $sSql .= "(select sum({$sSigla}_valor) from {$sTipoFolha}                          										";
  $sSql .= "      where  {$sSigla}_pd     = 1                                        										";
  $sSql .= "        and  {$sSigla}_anousu = {$oGet->iAnoCompara}                     										";
  $sSql .= "        and  {$sSigla}_mesusu = {$oGet->iMesCompara}                     										";
  $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										";
  $sSql .= "        and  {$sSigla}_rubric < 'R950'                                   										";
  $sSql .= ") as vlr_compara                                                         										";
  $sSql .= "from rhpessoalmov                                                        										";
  $sSql .= "    inner join rhpessoal      on rh01_regist  = rh02_regist              										";
  $sSql .= "    inner join cgm            on rh01_numcgm  = z01_numcgm               										";
  $sSql .= "where rh02_instit  = ".db_getsession('DB_instit')."                										      ";
  $sSql .= "  and  ( 																																							      ";
  $sSql .= "          (rh02_anousu = {$oGet->iAnoBase}     and rh02_mesusu = {$oGet->iMesBase})         ";
  $sSql .= "        or 																																						      ";
  $sSql .= "          (rh02_anousu = {$oGet->iAnoCompara}  and rh02_mesusu = {$oGet->iMesCompara})      ";
  $sSql .= "       )                                        																			      ";  
  $sSql .= "  and rh02_regist in                                               										      ";
  $sSql .= "(select {$sSigla}_regist from {$sTipoFolha}                              										";
  $sSql .= "      where  {$sSigla}_pd     = 1                                                           ";
  $sSql .= "        and  ( 																																							";
  $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoBase}  and {$sSigla}_mesusu = {$oGet->iMesBase})        ";
  $sSql .= "              or 																																						";
  $sSql .= "                ({$sSigla}_anousu = {$oGet->iAnoCompara}  and {$sSigla}_mesusu = {$oGet->iMesCompara})	";
  $sSql .= "             )                                        																			"; 
  $sSql .= "        and  {$sSigla}_regist = rhpessoalmov.rh02_regist                 										";
  $sSql .= "        and  {$sSigla}_rubric < 'R950')                                  									  ";
  $sSql .= "order by z01_nome                                                        										";
  $rsSql               = $cl_rhpessoal->sql_record($sSql);
	$iNumRows            = $cl_rhpessoal->numrows;
	if($iNumRows > 0 ) {
		/**
		 * Cria array de Objetos com os registros de Cada Tipo de Folha selecionda.
		 */
		$aTipoFolha[$sTipoFolha]['aDados'] = db_utils::getColectionByRecord($rsSql,true);  
	}
}
$pdf    = new PDF(); 
$head1  = "RELAT�RIO COMPARATIVO ENTRE FOLHAS";
$head3  = "Tipos de Folha: ".implode(", ",$aTitulo);
$head5  = "Ano/M�s Base: ".$oGet->iAnoBase."/".$oGet->iMesBase;
$head6  = "Ano/M�s a Comparar: ".$oGet->iAnoCompara."/".$oGet->iMesCompara;
$head8  = "Filtro: ".$sFiltro;
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
   * Se existirem dados no array imprime cabe�alho e Dados
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
     * Percorre dados Referente a cada Tipo de Folha qual foi selecionado de Relat�rio
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
     * Imprime Rodap� com o Total dos valores de cada coluna
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
 * Imprime Cabe�alho que contem o titulo referente ao tipo de Folha selecionado...
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
 * Imprime cabe�alhos da Tabela com as Label das celulas da tabela.
 *
 * @param Object $pdf
 */
function cabecalhoPagina02(&$pdf) {
  
  $pdf->SetFont('','B');
  $pdf->Cell(15,  4, "Matr�cula",1,0,'C',1,"","");
  $pdf->Cell(82,  4, "Nome",1,0,'C',1,"","");
  $pdf->Cell(20 , 4, "Data Admiss.",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Valor Base",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Valor a Comparar",1,0,'C',1,"","");
  $pdf->Cell(25,  4, "Diferen�a",1,1,'C',1,"","");
  $pdf->SetFont('','');
}
?>