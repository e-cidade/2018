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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sDataInicial    = implode("-", array_reverse(explode("/",$sDataA)));
$sDataFinal      = implode("-", array_reverse(explode("/",$sDataB)));
$sOrdem          = $sOrdem;
$sQuebra         = $sQuebra;
$iProgramas      = $iProgramas;
$iCandidatos     = $iCandidatos;
$sWhereProgs     = "";
$sWhereCands     = "";
$sWhereSituacao  = "";
$sWhereDatas     = "";
$iTotalInscr     = 0;
$sOrderAdicional = "";
$sSituacao       = "";
$sSeparadorData  = "";
$sWhereInscricao = "";
$iTotalGeral     = 0;

if ($iInscricao != null || $iInscricao != '') {
	$sWhereInscricao = " and habitinscricao.ht15_sequencial = $iInscricao ";
	
} else {
	
	$sWhereInscricao = " ";
}

if ($sDataA == null || $sDataB == null || $sDataA == '' || $sDataB == '') {
	
	$sWhereDatas    = "habitinscricao.ht15_datalancamento between '1900-01-01' and '".db_getsession('DB_anousu')."-12-31' ";
	$sSeparadorData = "Todas";
} else {
	
	$sSeparadorData = "  à  ";
	$sWhereDatas    = " habitinscricao.ht15_datalancamento between '$sDataInicial' and '$sDataFinal' ";
}

/*
 * definições para clausula where das consultas
 */
if ($iProgramas != null && $iProgramas != "") {   //definimos where dos programas se ele não vier vazio
  $sWhereProgs .= "  and habitcandidatointeresseprograma.ht13_habitprograma in ($iProgramas) ";
}
if ($iCandidatos != null && $iCandidatos != "") { // definimos where dos candidatos se ele nao vier vazio
  $sWhereCands .= "  and habitcandidatointeresse.ht20_habitcandidato in ($iCandidatos) ";
}

if ($sQuebra == 2) { // se aquebra de linha por data estiver selecionada definimos o segundo order by
	$sOrderAdicional = " habitinscricao.ht15_datalancamento, ";
}

if ($sQuebra == 3) { 
  $sOrderAdicional = " cgm.z01_nome, ";
}

$sSqlInscricoes  = "select  habitcandidato.ht10_numcgm   as cgm_candidato, "; 
$sSqlInscricoes .= "  cgm.z01_nome                       as nome_candidato, ";
$sSqlInscricoes .= "  habitinscricao.ht15_sequencial     as inscricao, "; 
$sSqlInscricoes .= "  habitprograma.ht01_sequencial      as codigo_programa, ";                 
$sSqlInscricoes .= "  habitprograma.ht01_descricao       as descricao_programa, ";               
$sSqlInscricoes .= "  habitinscricao.ht15_datalancamento as data_lancamento_inscricao ";                 
$sSqlInscricoes .= "from habitcandidato "; 
$sSqlInscricoes .= "    inner join habitcandidatointeresse         on habitcandidatointeresse.ht20_habitcandidato                  = habitcandidato.ht10_sequencial ";
$sSqlInscricoes .= "    inner join habitcandidatointeresseprograma on habitcandidatointeresseprograma.ht13_habitcandidatointeresse = habitcandidatointeresse.ht20_sequencial ";
$sSqlInscricoes .= "    inner join habitinscricao                  on habitinscricao.ht15_habitcandidatointeresseprograma          = habitcandidatointeresseprograma.ht13_sequencial ";
$sSqlInscricoes .= "    inner join habitprograma                   on habitprograma.ht01_sequencial                                = habitcandidatointeresseprograma.ht13_habitprograma";
$sSqlInscricoes .= "    inner join cgm                             on cgm.z01_numcgm                                               = habitcandidato.ht10_numcgm ";
$sSqlInscricoes .= "where  ";
$sSqlInscricoes .= "  $sWhereDatas ";
$sSqlInscricoes .=    $sWhereProgs;
$sSqlInscricoes .=    $sWhereCands;
$sSqlInscricoes .=    $sWhereInscricao;
$sSqlInscricoes .= "order by $sOrderAdicional $sOrdem ;";

//die($sSqlInscricoes);

$rsDados         = db_query($sSqlInscricoes); 
$aListaDados     = db_utils::getColectionByRecord($rsDados);
$aDadosComQuebra = array();
$aDadosSemQuebra = array();

/*
 * caso seja a quebra por datas de inscrição seja selecionada, criamos o obj agrupando pelas datas
 * sQuebra = 2;
 */
// define a variavel referente ao tipo de quebra que sera exibida no header
switch ($sQuebra) {
	
  case 1 :
  	
    $tipoQuebra = "Nenhum";
	  foreach ($aListaDados as $oIndiceDados => $oValorDados) {
	    
	    $oDados            = new stdClass();
	    $oDados->cgm       = $oValorDados->cgm_candidato;
	    $oDados->nome      = $oValorDados->nome_candidato;
	    $oDados->codprog   = $oValorDados->codigo_programa;
	    $oDados->programa  = $oValorDados->descricao_programa;
	    $oDados->data      = $oValorDados->data_lancamento_inscricao;
	    $oDados->inscricao = $oValorDados->inscricao;  
	    
	    $aDadosSemQuebra[] = $oDados;
	  }    
  break;  
  
  case 2 :
  	
    $tipoQuebra = "por Data de Lançamento da Inscrição"; 
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
        
    	  $iTotalGeral++;
        $oData            = new stdClass();
        $oDados           = new stdClass();
        $oData->dados     = array();
        $oDados->cgm      = $oValorDados->cgm_candidato;
        $oDados->nome     = $oValorDados->nome_candidato;
        $oDados->codprog  = $oValorDados->codigo_programa;
        $oDados->nomeprog = $oValorDados->descricao_programa;
        $oDados->data     = $oValorDados->data_lancamento_inscricao;
        $oDados->inscricao = $oValorDados->inscricao;
        
        $aDadosComQuebra[$oValorDados->data_lancamento_inscricao]->dados[] = $oDados;
        // trazemos junto no obj a propriedade com totalizador por datas
        $aDadosComQuebra[$oValorDados->data_lancamento_inscricao]->total   = count($aDadosComQuebra[$oValorDados->
                                                                                     data_lancamento_inscricao]->dados);
    }
  break;  

  case 3 :
    
  	
    $tipoQuebra = "por Nome do Candidato"; 
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
        
        $iTotalGeral++;
    	  $oData            = new stdClass();
        $oDados           = new stdClass();
        $oData->dados     = array();
        $oDados->cgm      = $oValorDados->cgm_candidato;
        $oDados->nome     = $oValorDados->nome_candidato;
        $oDados->codprog  = $oValorDados->codigo_programa;
        $oDados->nomeprog = $oValorDados->descricao_programa;
        $oDados->data     = $oValorDados->data_lancamento_inscricao;
        $oDados->inscricao = $oValorDados->inscricao;
        
        $aDadosComQuebra[$oValorDados->nome_candidato]->dados[] = $oDados;
        
        $aDadosComQuebra[$oValorDados->nome_candidato]->total   = count($aDadosComQuebra[$oValorDados->
                                                                                     nome_candidato]->dados);
    }     
  break;

  case 4 :
    
    $tipoQuebra = "por Programa"; 
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
        
    	  $iTotalGeral++;
        $oData            = new stdClass();
        $oDados           = new stdClass();
        $oData->dados     = array();
        $oDados->cgm      = $oValorDados->cgm_candidato;
        $oDados->nome     = $oValorDados->nome_candidato;
        $oDados->codprog  = $oValorDados->codigo_programa;
        $oDados->nomeprog = $oValorDados->descricao_programa;
        $oDados->data     = $oValorDados->data_lancamento_inscricao;
        $oDados->inscricao = $oValorDados->inscricao;
        
        $aDadosComQuebra[$oValorDados->descricao_programa]->dados[] = $oDados;
        
        $aDadosComQuebra[$oValorDados->descricao_programa]->total   = count($aDadosComQuebra[$oValorDados->
                                                                                     descricao_programa]->dados);
        $aDadosComQuebra[$oValorDados->descricao_programa]->codigo   = $oValorDados->codigo_programa;                                                                                     
    }     
  break;  
  
}

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 6);
$troca = 1;
$iAlturalinha = 4;
$iFonte       = 6;


$head2  = "RELATÓRIO DE INSCRIÇÕES";
$head4  = "Período da Consulta : ".$sDataA. $sSeparadorData .$sDataB;
$head6  = "Tipo de Quebra : ".$tipoQuebra;


$pdf->AddPage("L");

if ($sQuebra == 1) {

	imprimirCabecalho($pdf, $iAlturalinha, true);

	foreach ($aDadosSemQuebra as $oIndiceDados => $oValorDados) {
		
	      $pdf->setfont('arial','',$iFonte);
	      $pdf->cell(20,  $iAlturalinha, $oValorDados->inscricao ,            "TBR",  0, "C", 0);
	      $pdf->cell(20,  $iAlturalinha, $oValorDados->cgm ,                  "TBR",  0, "C", 0);
	      $pdf->cell(90,  $iAlturalinha, $oValorDados->nome,                  "TBL",  0, "L", 0);
	      $pdf->cell(30,  $iAlturalinha, $oValorDados->codprog,               "TBL",  0, "C", 0);
	      $pdf->cell(90,  $iAlturalinha, $oValorDados->programa,              "TBLR", 0, "L", 0);
	      $pdf->cell(30,  $iAlturalinha, db_formatar($oValorDados->data,"d"), "TB",   1, "C", 0);
	      $iTotalInscr ++;
	      
	      imprimirCabecalho($pdf, $iAlturalinha, false);
	}
//totalizador
      $pdf->setfont('arial','B',$iFonte);
      $pdf->cell(250,  $iAlturalinha, "TOTAL DE REGISTROS :", "TBR",  0, "R", 0);
      $pdf->cell(30,  $iAlturalinha, "$iTotalInscr",          "TBL",  1, "L", 0);
      $iTotalInscr = 0;	
}
/*
 * com opção de quebra por data de inscrição selecionada
 */
  
if ($sQuebra == 2) {
	
	foreach ($aDadosComQuebra as $oIndiceDados => $oValorDados) {

    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(265, $iAlturalinha, "Data de Inscrição : ".db_formatar($oIndiceDados,"d"),  "TB",  1, "L", 1);
  
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(20,  $iAlturalinha, "INSCRICÃO",       "TBR",  0, "C", 1);
    $pdf->cell(20,  $iAlturalinha, "CGM",             "LTBR", 0, "C", 1);
    $pdf->cell(100,  $iAlturalinha, "NOME CANDIDATO", "TBL",  0, "C", 1);
    $pdf->cell(25,  $iAlturalinha, "CÓD. PROGRAMA",   "TBL",  0, "C", 1);
    $pdf->cell(100,  $iAlturalinha, "NOME PROGRAMA",  "TBL",  1, "C", 1);
    
    foreach ($oValorDados->dados as $DadosInscricao) {

    	$pdf->setfont('arial','',$iFonte);
      $pdf->cell(20,  $iAlturalinha,  "$DadosInscricao->inscricao", "TBR", 0, "C", 0);
    	$pdf->cell(20,  $iAlturalinha,  "$DadosInscricao->cgm",       "TBR", 0, "C", 0);
      $pdf->cell(100,  $iAlturalinha, "$DadosInscricao->nome ",     "TBL", 0, "L", 0);
      $pdf->cell(25,  $iAlturalinha,  "$DadosInscricao->codprog",   "TBL", 0, "C", 0);
      $pdf->cell(100,  $iAlturalinha, "$DadosInscricao->nomeprog",  "TBL", 1, "L", 0);
      if ( $pdf->GetY() > $pdf->h - 25  ) {
    
		      $pdf->AddPage("L");
			    $pdf->setfont('arial','b',$iFonte);
			    $pdf->cell(20,  $iAlturalinha, "INSCRICÃO",      "TBR",  0, "C", 1);
			    $pdf->cell(20,  $iAlturalinha, "CGM",            "LTBR", 0, "C", 1);
			    $pdf->cell(100, $iAlturalinha, "NOME CANDIDATO", "TBL",  0, "C", 1);
			    $pdf->cell(25,  $iAlturalinha, "CÓD. PROGRAMA",  "TBL",  0, "C", 1);
			    $pdf->cell(100, $iAlturalinha, "NOME PROGRAMA",  "TBL",  1, "C", 1);
		  }
      
    }
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(165,  $iAlturalinha, "TOTAL DE REGISTROS :", "TBR",  0, "R", 0);
    $pdf->cell(100,  $iAlturalinha, "$oValorDados->total",  "TBL",  1, "L", 0);    
    $pdf->Ln(4); 
		imprimirCabecalhoComquebraData($pdf, $iAlturalinha, false);
	}
	//total geral
	$pdf->Ln(4);
  $pdf->setfont('arial','b',$iFonte);
  $pdf->cell(165,  $iAlturalinha, "TOTAL GERAL DE REGISTROS :", "TBR",  0, "R", 1);
  $pdf->cell(100,  $iAlturalinha, $iTotalGeral,  "TBL",  1, "L", 1);    
   	
	
}

if ($sQuebra == 3) {
  
  foreach ($aDadosComQuebra as $oIndiceDados => $oValorDados) {

    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(195, $iAlturalinha, "Nome do Candidato : ".$oIndiceDados,  "TB",  1, "L", 1);
  
    $pdf->setfont('arial','b',$iFonte);
          $pdf->cell(20,  $iAlturalinha, "INSCRICÃO",     "TBR",  0, "C", 1);
          $pdf->cell(20,  $iAlturalinha, "CGM",           "LTBR", 0, "C", 1);
          $pdf->cell(25,  $iAlturalinha, "CÓD. PROGRAMA", "TBL",  0, "C", 1);
          $pdf->cell(100, $iAlturalinha, "NOME PROGRAMA", "TBLR", 0, "C", 1);
          $pdf->cell(30,  $iAlturalinha, "DT. INSCRIÇÃO", "TBL",  1, "C", 1);
    
    foreach ($oValorDados->dados as $DadosInscricao) {

      $pdf->setfont('arial','',$iFonte);
      $pdf->cell(20,  $iAlturalinha, "$DadosInscricao->inscricao",           "TBR",  0, "C", 0);
      $pdf->cell(20,  $iAlturalinha, "$DadosInscricao->cgm",                 "TBR",  0, "C", 0);
      $pdf->cell(25,  $iAlturalinha, "$DadosInscricao->codprog",             "TBL",  0, "C", 0);
      $pdf->cell(100, $iAlturalinha, "$DadosInscricao->nomeprog",            "TBLR", 0, "L", 0);
      $pdf->cell(30,  $iAlturalinha, db_formatar($DadosInscricao->data,"d"), "TB",   1, "C", 0);
      if ( $pdf->GetY() > $pdf->h - 25  ) {
    
          $pdf->AddPage("L");
          $pdf->setfont('arial','b',$iFonte);
          $pdf->cell(20,  $iAlturalinha, "INSCRICÃO",     "TBR",  0, "C", 1);
          $pdf->cell(20,  $iAlturalinha, "CGM",           "LTBR", 0, "C", 1);
          $pdf->cell(25,  $iAlturalinha, "CÓD. PROGRAMA", "TBL",  0, "C", 1);
          $pdf->cell(100, $iAlturalinha, "NOME PROGRAMA", "TBLR", 0, "C", 1);
          $pdf->cell(30,  $iAlturalinha, "DT. INSCRIÇÃO", "TBL",  0, "C", 1);
      }
      
    }
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(165, $iAlturalinha, "TOTAL DE REGISTROS :", "TBR",  0, "R", 0);
    $pdf->cell(30,  $iAlturalinha, "$oValorDados->total",  "TBL",  1, "L", 0);    
    $pdf->Ln(4); 
    imprimirCabecalhoComquebraData($pdf, $iAlturalinha, false);
  }
    //Total GERAL
    $pdf->Ln(4); 
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(165, $iAlturalinha, "TOTAL GERAL DE REGISTROS :", "TBR",  0, "R", 1);
    $pdf->cell(30,  $iAlturalinha, $iTotalGeral,  "TBL",  1, "L", 1);    
      
  
}

if ($sQuebra == 4) {

  foreach ($aDadosComQuebra as $oIndiceDados => $oValorDados) {

    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(225, $iAlturalinha, "Programa : ".$oIndiceDados. "    CÓDIGO : ".$oValorDados->codigo ,  "TB",  1, "L", 1);
  
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(20,  $iAlturalinha, "INSCRIÇÃO",      "TBR",  0, "C", 1);
    $pdf->cell(25,  $iAlturalinha, "CGM",            "LTBR", 0, "C", 1);
    $pdf->cell(150, $iAlturalinha, "NOME CANDIDATO", "TBL",  0, "C", 1);
    $pdf->cell(30,  $iAlturalinha, "DT. INSCRIÇÃO",  "LTB",  1, "C", 1);  
    
    foreach ($oValorDados->dados as $DadosInscricao) {

      $pdf->setfont('arial','',$iFonte);
      $pdf->cell(20,  $iAlturalinha, "$DadosInscricao->inscricao",           "TBR",  0, "C", 0);
      $pdf->cell(25,  $iAlturalinha, "$DadosInscricao->cgm",                 "LTBR", 0, "C", 0);
      $pdf->cell(150, $iAlturalinha, "$DadosInscricao->nome ",               "TBL",  0, "L", 0);
      $pdf->cell(30,  $iAlturalinha, db_formatar($DadosInscricao->data,"d"), "LTB",  1, "C", 0);
      if ( $pdf->GetY() > $pdf->h - 25  ) {
    
          $pdf->AddPage("L");
          $pdf->setfont('arial','b',$iFonte);
          $pdf->cell(20,  $iAlturalinha, "INSCRIÇÃO",      "TBR",  0, "C", 1);
          $pdf->cell(25,  $iAlturalinha, "CGM",            "LTBR", 0, "C", 1);
          $pdf->cell(150, $iAlturalinha, "NOME CANDIDATO", "TBL",  0, "C", 1);
          $pdf->cell(30,  $iAlturalinha, "DT. INSCRIÇÃO",  "LTB",  1, "C", 1);          
      }
      
    }
    $pdf->setfont('arial','b',$iFonte);
    $pdf->cell(195, $iAlturalinha, "TOTAL DE REGISTROS :", "TBR",  0, "R", 0);
    $pdf->cell(30,  $iAlturalinha, "$oValorDados->total",  "TBL",  1, "L", 0);    
    $pdf->Ln(4); 
    imprimirCabecalhoComquebraData($pdf, $iAlturalinha, false);
  }
  
  // total geral
  $pdf->Ln(4);
  $pdf->setfont('arial','b',$iFonte);
  $pdf->cell(195, $iAlturalinha, "TOTAL GERAL DE REGISTROS :", "TBR",  0, "R", 1);
  $pdf->cell(30,  $iAlturalinha, $iTotalGeral,  "TBL",  1, "L", 1);    
}

$pdf->output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    
    if ( !$lImprime ) {
    	
      $oPdf->AddPage("L");
    }

      $oPdf->setfont('arial','b',6);
      $oPdf->cell(20,  $iAlturalinha, "INSCRIÇÃO",      "TBR",  0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "CGM",            "LTBR",  0, "C", 1);
      $oPdf->cell(90,  $iAlturalinha, "NOME CANDIDATO", "TBL",  0, "C", 1);
      $oPdf->cell(30,  $iAlturalinha, "CÓD. PROGRAMA",  "TBL",  0, "C", 1);
      $oPdf->cell(90,  $iAlturalinha, "NOME PROGRAMA",  "TBLR", 0, "C", 1);
      $oPdf->cell(30,  $iAlturalinha, "DT. INSCRIÇÃO",  "LTB",  1, "C", 1);
   
  }
}
/*
 * responsavel por adicionar paginas no relatorio 
 * quando for escolhido alguma quebra
 */
function imprimirCabecalhoComquebraData($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    
    if ( !$lImprime ) {
      
      $oPdf->AddPage("L");
    }
  }
}

?>