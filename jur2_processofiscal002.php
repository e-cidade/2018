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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("classes/db_parjuridico_classe.php");
require_once("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost           = db_utils::postMemory($_POST);
$oGet            = db_utils::postMemory($_GET);
$clparjuridico   = new cl_parjuridico;
$clcgm           = new cl_cgm;

// filtros pela remessa
$sArquivoRemessa = $oGet->sNomeRemessa;
$dDataRemessa    = implode("-", array_reverse(explode("/",$oGet->sDataRemessa)));

// filtros pelo retorno
$sArquivoRetorno = $oGet->sNomeRetorno;
$dDataRetorno    = implode("-", array_reverse(explode("/",$oGet->sDataRetorno)));
$iSeqRetorno     = $oGet->iSeqRetorno;

// filtros registro
$iCdaIni         = $oGet->iCdaIni;
$iCdaFim         = $oGet->iCdaFim;
$iInicialIni     = $oGet->iInicialIni;
$iInicialFim     = $oGet->iInicialFim;

$iTotalInscr     = 0;
$iTotalGeral     = 0;
$sVirgula        = ""; 
$sListaExerc     = "";
$aDadosGeral     = array();
$aListaIniciais  = array();
$aExercDivida    = array();
$aDadosAgrupados = array();
$aExercDivida    = array();
$aExercParc      = array();
$aExercReparc    = array();


/**
 * função para retiras as { e } do array que vem na lista de exercicios
 * @method limpaChaves()
 * @param  aLista array
 * @return sLista string de exercicios
 */
function limpaChaves($aLista){
	
	$sLista = str_replace("{","",str_replace("}","",$aLista));
	return $sLista;
}

$rsParJuridico = $clparjuridico->sql_record($clparjuridico->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit')));
$oParJuridico  = db_utils::fieldsMemory($rsParJuridico,0);
if ($oParJuridico->v19_envolprinciptu == "t") {
  $lPrincipal = "true";
} else {
  $lPrincipal = "false";
}
$sCamposRelatorio  = "processoforo.v70_codforo,             ";
$sCamposRelatorio .= "processoforo.v70_sequencial,          "; 
$sCamposRelatorio .= "processoforoinicial.v71_processoforo, ";
$sCamposRelatorio .= "inicial.v50_inicial,                  ";
$sCamposRelatorio .= "inicialcert.v51_certidao,             ";
$sCamposRelatorio .= "listacda.v81_lista,                   ";
$sCamposRelatorio .= "certid.v13_certid                     ";

$sSqlRelatorio  = " select {$sCamposRelatorio}                                                                                        ";
$sSqlRelatorio .= "   from certidarqretorno                                                                                           ";
$sSqlRelatorio .= "    inner join certidarqremessa    on certidarqretorno.v84_certidarqremessa = certidarqremessa.v83_sequencial      ";
$sSqlRelatorio .= "    inner join listacda            on certidarqremessa.v83_lista            = listacda.v81_lista                   ";
$sSqlRelatorio .= "    inner join certid              on certid.v13_certid                     = listacda.v81_certid                  ";
$sSqlRelatorio .= "    inner join inicialcert         on inicialcert.v51_certidao              = listacda.v81_certid                  ";
$sSqlRelatorio .= "    inner join inicial             on inicial.v50_inicial                   = inicialcert.v51_inicial              ";
$sSqlRelatorio .= "    inner join processoforoinicial on processoforoinicial.v71_inicial       = inicialcert.v51_inicial              ";
$sSqlRelatorio .= "    inner join processoforo        on processoforo.v70_sequencial           = processoforoinicial.v71_processoforo ";
$sSqlRelatorio .= "   where certidarqretorno.v84_sequencial = {$iSeqRetorno} order by v70_codforo ";
$rsDadosRelatorio = db_query($sSqlRelatorio);
$aDadosRelatorio  = db_utils::getColectionByRecord($rsDadosRelatorio);

foreach ($aDadosRelatorio as $oDadosRelatorio) {
 	
  $oDadosGeral = new stdClass();
 	/**
 	 * Buscamos os exercícios dos débitos da certidão
 	 * Primeiramente para as dividas e após para os parcelamentos
 	 */
  	$sSqlExercDivida   = "select {$oDadosRelatorio->v50_inicial} as inicial,                             "; 
  	$sSqlExercDivida  .= "       z01_numcgm,                                                             "; 
		$sSqlExercDivida  .= "       z01_nome,                                                               ";
		$sSqlExercDivida  .= "       array_accum(distinct divida.v01_exerc) as exerc                         ";
		$sSqlExercDivida  .= "  from divida                                                                  "; 
		$sSqlExercDivida  .= "       inner join certdiv     on divida.v01_coddiv        = certdiv.v14_coddiv ";
    $sSqlExercDivida  .= "       inner join inicialcert on inicialcert.v51_certidao = certdiv.v14_certid "; 
    $sSqlExercDivida  .= "       inner join cgm on v01_numcgm                       = z01_numcgm         ";   
		$sSqlExercDivida  .= " where inicialcert.v51_inicial = {$oDadosRelatorio->v50_inicial}               ";
		$sSqlExercDivida  .= " group by z01_numcgm, z01_nome                                                 ";
		$rsExercDivida     =  db_query($sSqlExercDivida);
    if ( pg_num_rows($rsExercDivida) > 0) {
      $aExercDivida    = db_utils::fieldsMemory($rsExercDivida, 0);
    }		
	  // parcelamentos
    $sSqlExercParcel  = "select {$oDadosRelatorio->v50_inicial} as inicial,                             "; 
    $sSqlExercParcel .= "       z01_numcgm,                                                             ";
	  $sSqlExercParcel .= "       z01_nome,                                                               ";
	  $sSqlExercParcel .= "       array_accum(distinct divida.v01_exerc) as exerc                         ";
	  $sSqlExercParcel .= "  from termodiv                                                                ";
	  $sSqlExercParcel .= "       inner join termo       on termo.v07_parcel         = termodiv.parcel    ";   
	  $sSqlExercParcel .= "       inner join certter     on certter.v14_parcel       = termodiv.parcel    ";
	  $sSqlExercParcel .= "       inner join inicialcert on inicialcert.v51_certidao = certter.v14_certid "; 
	  $sSqlExercParcel .= "       inner join divida      on divida.v01_coddiv        = termodiv.coddiv    ";
	  $sSqlExercParcel .= "       inner join cgm         on v07_numcgm               = z01_numcgm         ";                
	  $sSqlExercParcel .= " where inicialcert.v51_inicial = {$oDadosRelatorio->v50_inicial}               ";
	  $sSqlExercParcel .= " group by z01_numcgm, z01_nome                                                 ";
	  $rsExercParcel = db_query($sSqlExercParcel);
	  if ( pg_num_rows($rsExercParcel) > 0) {
	    $aExercParc    = db_utils::fieldsMemory($rsExercParcel, 0);
	  }
	  if ( count($aExercParc) == 0 ) {
	  	$sSqlExercReparc  = "select {$oDadosRelatorio->v50_inicial} as inicial,                                                        "; 
      $sSqlExercReparc .= "       z01_numcgm,                                                                                        ";
	  	$sSqlExercReparc .= "       z01_nome,                                                                                          ";
	  	$sSqlExercReparc .= "       array_accum(distinct divida.v01_exerc) as exerc,                                                   ";
	  	$sSqlExercReparc .= "       riseq                                                                                              ";
		  $sSqlExercReparc .= "  from fc_origemparcelamento( ( select v07_numpre                                                         "; 
		  $sSqlExercReparc .= "                                  from termo                                                              ";
		  $sSqlExercReparc .= "                                 inner join certter     on certter.v14_parcel       = termo.v07_parcel    "; 
      $sSqlExercReparc .= "                                 inner join inicialcert on inicialcert.v51_certidao = certter.v14_certid  ";
		  $sSqlExercReparc .= "                                 where inicialcert.v51_inicial = {$oDadosRelatorio->v50_inicial} ) ) as x ";
		  $sSqlExercReparc .= " inner join termodiv   on termodiv.parcel   = x.riparcel                                                  ";
		  $sSqlExercReparc .= " inner join termo      on termodiv.parcel   = termo.v07_parcel                                            ";
		  $sSqlExercReparc .= " inner join divida     on divida.v01_coddiv = termodiv.coddiv                                             "; 
	    $sSqlExercReparc .= " inner join cgm        on cgm.z01_numcgm    = termo.v07_numcgm 	                                         ";
		  $sSqlExercReparc .= " group by z01_numcgm, z01_nome, riseq                                                                     ";
		  $sSqlExercReparc .= " order by z01_nome                                                                                        ";
		  $rsExercReparc = db_query($sSqlExercReparc);
		  if ( pg_num_rows($rsExercReparc) > 0) {
	      $aExercReparc  = db_utils::fieldsMemory($rsExercReparc, 0);
		  }
	  }
 	/**
 	 * Buscamos os dados do imóvel se existir e do proprietário 
 	 */
   $sSqlProprietario  = "select distinct j01_matric, j34_setor, j34_quadra, j34_lote                  "; 
	 $sSqlProprietario .= "  from proprietario                                                          ";
	 $sSqlProprietario .= " where j01_matric in ( select distinct k00_matric                            ";
   $sSqlProprietario .= "                          from inicialnumpre                                 ";
   $sSqlProprietario .= "                        inner join arrematric on k00_numpre = v59_numpre     ";
   $sSqlProprietario .= "                        where v59_inicial = {$oDadosRelatorio->v50_inicial}) ";
	 $rsProprietario     = db_query($sSqlProprietario);
	 if ( pg_num_rows($rsProprietario) > 0) {
		 $aDadosProprietario = db_utils::fieldsMemory($rsProprietario, 0);
	 }          

	 if ( !in_array($oDadosRelatorio->v70_codforo."-".$oDadosRelatorio->v50_inicial, $aListaIniciais)) {
  	
  	if (count($aExercDivida) > 0) {
  		
  		$aExercDivida->exerc = limpaChaves($aExercDivida->exerc);
  	 	$sListaExerc = $sVirgula.$aExercDivida->exerc;
  	 	$sVirgula = ",";
  	}
  	
  	if (count($aExercParc) > 0) {
  		
  		$aExercParc->exerc = limpaChaves($aExercParc->exerc);
  		$sListaExerc = $sVirgula.$aExercParc->exerc;
      $sVirgula = ",";
  	}
  	
  	if (count($aExercReparc) > 0) {
  		
  		$aExercReparc->exerc = limpaChaves($aExercReparc->exerc);
      $sListaExerc = $sVirgula.$aExercReparc->exerc;
      $sVirgula = ",";  		
  	}
  	/**
  	 * definimos o nome do devedor principal 
  	 * divida, ou parcelamento ou reparcelamento
  	 */
  	if (count($aExercDivida) > 0) {
  	  $sNomeCgm = $aExercDivida->z01_nome;
  	} else if(count($aExercParc) > 0) {
  		$sNomeCgm = $aExercParc->z01_nome;
  	} else if(count($aExercReparc) > 0) {
  		$sNomeCgm = $aExercReparc->z01_nome;
  	}
  	
  	$oDadosGeral->processoinicial = $oDadosRelatorio->v70_codforo;
	  $oDadosGeral->nome            = $sNomeCgm;
	  $oDadosGeral->exercicios      = $sListaExerc;
	  $oDadosGeral->matricula       = $aDadosProprietario->j01_matric;
	  if(count($aDadosProprietario) > 0 ) {
		  $oDadosGeral->setor           = $aDadosProprietario->j34_setor;
		  $oDadosGeral->quadra          = $aDadosProprietario->j34_quadra;
		  $oDadosGeral->lote            = $aDadosProprietario->j34_lote;
	  } else {
	  	$oDadosGeral->setor           = "";
	    $oDadosGeral->quadra          = "";
	    $oDadosGeral->lote            = "";
	  }
	  $aDadosGeral[]    = $oDadosGeral;
	  $aListaIniciais[] = $oDadosRelatorio->v70_codforo."-".$oDadosRelatorio->v50_inicial;
	  unset($aListaExercDivida);
    unset($oEnvolvidos);
    unset($aDadosEnvol);
  }
  $sListaExerc = "";
  $sVirgula    = "";
}

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 6);
$iAlturalinha = 4;
$iFonte       = 6;
$head2        = "Emissão de Certidões para Cobrança Judicial";
$head3        = "Relatório de controle dos processos em Execução";
$pdf->AddPage("L");

imprimirCabecalho($pdf, $iAlturalinha, true);

foreach ($aDadosGeral as $oIndiceDados => $oValorDados) {
    
	$pdf->setfont('arial','',$iFonte);
	
	$pdf->cell(20, $iAlturalinha, $oValorDados->processoinicial , "TBR",  0, "R", 0);
	$pdf->cell(90, $iAlturalinha, $oValorDados->nome,             "LTBR", 0, "L", 0);
	$pdf->cell(90, $iAlturalinha, $oValorDados->exercicios,       "LTBR", 0, "L", 0);
	$pdf->cell(20, $iAlturalinha, $oValorDados->matricula ,       "LTBR", 0, "R", 0);
	$pdf->cell(20, $iAlturalinha, $oValorDados->setor,            "LTBR", 0, "R", 0);
	$pdf->cell(20, $iAlturalinha, $oValorDados->quadra,           "LTBR", 0, "R", 0);
	$pdf->cell(20, $iAlturalinha, $oValorDados->lote,             "LTB",  1, "R", 0);
	 
	$iTotalInscr ++;        
	imprimirCabecalho($pdf, $iAlturalinha, false);
}	

//totalizador
$pdf->setfont('arial','B',$iFonte);
$pdf->cell(260,  $iAlturalinha, "TOTAL DE REGISTROS :", "TBR",  0, "R", 0);
$pdf->cell(20,  $iAlturalinha, "$iTotalInscr",          "TBL",  1, "L", 0);
$iTotalInscr = 0;	

$pdf->output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 8);
    if ( !$lImprime ) {
      $oPdf->AddPage("L");
    }
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20,  $iAlturalinha, "Proc. Judicial",       "TBR",  0, "C", 1);
      $oPdf->cell(90,  $iAlturalinha, "Nome do Contribuinte", "LTBR", 0, "C", 1);
      $oPdf->cell(90,  $iAlturalinha, "Exercícios",           "LTBR", 0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "C. Imóvel",            "LTBR", 0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "PLAN",                 "LTBR", 0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "QUAD",                 "LTBR", 0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "LOTE",                 "LTB",  1, "C", 1);
  }
}
?>