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
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_app.utils.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_empresto_classe.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelinfo_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_config_classe.php");
include("classes/db_orcparamelemento_classe.php");  
require_once("model/linhaRelatorioContabil.model.php");
require_once("model/relatorioContabil.model.php");
require_once("std/db_stdClass.php");
  
$classinatura       = new cl_assinatura;
$clempresto         = new cl_empresto;
$orcparamrel        = new cl_orcparamrel;
$clconrelinfo       = new cl_conrelinfo;
$cldb_config        = new cl_db_config;
$clorcparamelemento = new cl_orcparamelemento();

/*
 * Instancia a classe para retornar do objeto, a
 * propriedade munic, que tras o nome do municipio
 */
$oMunicipio         = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
$sMunicipio = $oMunicipio->munic;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$iAnoUsu  = db_getsession("DB_anousu");
db_app::import("contabilidade.relatorios.AnexoXIIILRF");
$iCodigoRelatorio   = 106;
$oDaoPeriodo        = db_utils::getDao("periodo");
$iCodigoPeriodo     = $periodo;
$sSqlPeriodo        = $oDaoPeriodo->sql_query($periodo);
$oPeriodo           = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0); 
$sSiglaPeriodo      = $oPeriodo->o114_sigla;
$oRelatorioContabil = new relatorioContabil($iCodigoRelatorio, false);

$oAnexoXIII = new AnexoXIIILRF($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
$oAnexoXIII->setInstituicoes(str_replace("-", ",", $db_selinstit));
$aDadosRelatorio = $oAnexoXIII->getDados();


/*
 * Reinicia o array para pegar o ano de inicio
 * e o ano final do exercicio
 */
reset($aDadosRelatorio);
$iAnoInicio = current($aDadosRelatorio);
$iAnoFinal = end($aDadosRelatorio); 

//$iAnoInicio->ano - $iAnoFinal->ano;

$head2 = "MUNICÍPIO DE {$sMunicipio}";
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "DEMONSTRATIVO DA PROJEÇÃO ATUARIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES";
$head5 = "ORÇAMENTO DA SEGURIDADE SOCIAL";
$head6 = "PERÍODO DE REFERÊNCIA : {$iAnoInicio->ano} - {$iAnoFinal->ano}";


$sFonte = "Arial";
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage();
$oPdf->SetFont($sFonte, "", 6);

/*
$oPdf->SetFillColor("777");
$oPdf->Cell(100, 5, "PREO ANEXO XIII (LRF, art 53, § 1º inciso II )", "", 0, "L", 0);
$oPdf->Cell(85, 5, "R$ 1,99", "", 1, "R", 0);
*/
$iAlturalinha = 4;
$iNumRows = 100;

imprimirCabecalho($oPdf, $iAlturalinha, true);

foreach ($aDadosRelatorio as $oLinhaRelatorio) {

  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(23, $iAlturalinha,             $oLinhaRelatorio->ano,                          "TRB", 0, "C", 0);
  $oPdf->Cell(40, $iAlturalinha, db_formatar($oLinhaRelatorio->receitasprevidenciarias,"f"), "TRB", 0, "R", 0);
  $oPdf->Cell(40, $iAlturalinha, db_formatar($oLinhaRelatorio->despesasprevidenciarias,"f"), "TRB", 0, "R", 0);
  $oPdf->Cell(40, $iAlturalinha, db_formatar($oLinhaRelatorio->resultadoprevidenciario,"f"), "TRB", 0, "R", 0);
  $oPdf->Cell(48, $iAlturalinha, db_formatar($oLinhaRelatorio->saldofinanceiro,"f"),         "TLB", 1, "R", 0); 
  
  imprimirCabecalho($oPdf, $iAlturalinha, false);
  imprimeInfoProxPagina($oPdf, $iAlturalinha, false);
  
}

$oPdf->ln();
$oRelatorioContabil->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->Output();

/**
 * Impime cabecalho do relatorio
 *
 * @param Object  type $oPdf
 * @param Integer type $iAlt
 * @param Boolean type $lImprime
 */
function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    if ( !$lImprime ) {
      
      $oPdf->AddPage("P");
      imprimeInfoProxPagina($oPdf, $iAlturalinha, true);
    } else {
      
    $oPdf->SetFillColor("777");
    $oPdf->Cell(100, 5, "RREO ANEXO XIII (LRF, art 53, § 1º inciso II )", "", 0, "L", 0);
    $oPdf->Cell(85, 5, "R$ 1,00",                                         "", 1, "R", 0);
    }
/*
 * Cabeçalho a ser Repetido nas paginas
 */  
    $oPdf->Cell(23, $iAlturalinha, "",                                 "TR",  0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "RECEITAS",                         "TR",  0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "DESPESAS",                         "TR",  0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "RESULTADO",                        "TLR", 0, "C", 0);
    $oPdf->Cell(48, $iAlturalinha, "SALDO FINANCEIRO",                 "TL",  1, "C", 0);
    
    $oPdf->Cell(23, $iAlturalinha, "EXERCÍCIO",                        "R",   0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "PREVIDENCIÁRIO",                   "LR",  0, "C", 0);
    $oPdf->Cell(48, $iAlturalinha, "DO EXERCÍCIO",                     "L",   1, "C", 0);
    
    $oPdf->Cell(23, $iAlturalinha, "",                                 "R",   0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "(a)",                              "L",   0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "(b)",                              "L",   0, "C", 0);
    $oPdf->Cell(40, $iAlturalinha, "(c)=(a-b)",                        "L",   0, "C", 0);
    $oPdf->Cell(48, $iAlturalinha, "(d)=('d' exercício anterior)+(c)", "L",   1, "C", 0);    
       
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
  
  if ( $oPdf->GetY() > $oPdf->h - 31 || $lImprime ) {
    
    $oPdf->SetFont('arial', '', 6);
    if ( $lImprime ) {
      $oPdf->Cell(190, ($iAlturalinha*2), 'Continuação '.($oPdf->PageNo())."/{nb}",          'T', 1, "R", 0);
    } else {
      //die('aqui');
      $oPdf->Cell(190, ($iAlturalinha*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}", 'T', 1, "R", 0);
      imprimirCabecalho($oPdf, $iAlturalinha, false,'');
    }
  }
} 
?>