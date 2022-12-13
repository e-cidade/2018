<?php
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/PDFDocument.php");

// require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));

$clcfpatric 		= new cl_cfpatri;
$cldepartorg 	 	= new cl_db_departorg;
$clbens 				= new cl_bens;
$clbensmotbaixa = new cl_bensmotbaixa;
$clbensmater 		= new cl_bensmater;
$clbensimoveis 	= new cl_bensimoveis;
$clbensbaix 		= new cl_bensbaix;
$cldb_depart 		= new cl_db_depart;
$clrotulo 			= new rotulocampo;
$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();
$clbensbaix->rotulo->label();
$cldb_depart->rotulo->label();
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("t64_descr"); //descrição classificação
$clrotulo->label("descrdepto"); //descrição do depart
$clrotulo->label("t51_descr"); //descrição do motivo da baixa

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$relbaix    = "t52_instit = ".db_getsession("DB_instit");
$msg        = "";
$sHeadPlaca = "";
if(isset($dataINI) && trim($dataINI)!="" || isset($dataFIM) && trim($dataFIM)!=""){
  if(isset($dataINI) && trim($dataINI)!=""&&trim($dataFIM)=="" ){
    $relbaix .= " and t55_baixa >='".$dataINI."' ";
    $msg      = "posterior a ".db_formatar($dataINI,"d");
  }
  if(isset($dataFIM) && trim($dataFIM)!=""){
    if($relbaix!=""){
      $relbaix .= " and t55_baixa between '".$dataINI."' and '".$dataFIM."' ";
      $msg      = "entre ".db_formatar($dataINI,"d")." e ".db_formatar($dataFIM,"d");
    }else{
      $relbaix .= " and t55_baixa<'".$dataFIM."' ";
      $msg      = "anterior a ".db_formatar($dataFIM,"d");
    }
  }
}else{
  $msg = " TODOS OS BENS BAIXADOS";
}

/**
 * Configura Where e Mensagem de Head PLACA
 */
if ( isset($placaInicial) && trim($placaInicial) != "" ) {

  $sHeadPlaca = "Placas de: {$placaInicial}";
  $relbaix .= " and t52_ident >= '{$placaInicial}' ";

  if (isset($placaFinal) && trim($placaFinal) != "") {

    $relbaix .= " and t52_ident <= '{$placaFinal}' ";
    $sHeadPlaca .= " até {$placaFinal}";
  } else {
    $sHeadPlaca = "Placas superiores à {$placaInicial}";
  }

}

$sOrderBy = '';

if (!empty($sOrder)) {

  $sOrderBy = $sOrder;
}

$sSqlBensBaixa = $clbensbaix->sql_query(null,"*", $sOrderBy, $relbaix);
$result_baixa  = $clbensbaix->sql_record($sSqlBensBaixa);

if ($clbensbaix->numrows == 0) {

  $sMsg= _M('patrimonial.patrimonio.pat2_bensbaix002.nenhum_cadastro_bem_baixado');
  db_redireciona("db_erros.php?fechar=true&db_erro=". $sMsg);
}

//Verifica se utiliza pesquisa por orgão sim ou não
$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
	db_fieldsmemory($resPesquisaOrgao,0);
	$lImprimeOrgao = $t06_pesqorgao == 't';
}

$sSelect  = " distinct                                                                    ";
$sSelect .= " bens.t52_bem,                                                               ";
$sSelect .= " bens.t52_instit,                                                            ";
$sSelect .= " bens.t52_descr,                                                             ";
$sSelect .= " bens.t52_ident::int,                                                             ";
$sSelect .= " bens.t52_depart,                                                            ";
$sSelect .= " bens.t52_valaqu,                                                            ";
$sSelect .= " db_depart.descrdepto,                                                       ";
$sSelect .= " bensbaix.t55_baixa,                                                         ";
$sSelect .= " bensbaix.t55_motivo,                                                        ";
$sSelect .= " bensbaix.t55_obs,                                                           ";
$sSelect .= " clabens.t64_class,                                                          ";
$sSelect .= " clabens.t64_descr,                                                          ";
$sSelect .= " (case when t52_bem in                                                       ";
$sSelect .= " (select t53_codbem from bensmater) then 'Material' else                     ";
$sSelect .= " (case when t52_bem in                                                       ";
$sSelect .= "    (select t54_codbem from bensimoveis) then 'Imóvel' else 'Indefinido'     ";
$sSelect .= " end)                                                                        ";
$sSelect .= " end) as definicao                                                           ";

if($lImprimeOrgao){

  $relbaix .= " and db01_anousu = ".db_getsession('DB_anousu');

  $sSelect .= ",  orcunidade.o41_unidade,                                                         ";
  $sSelect .= "  orcunidade.o41_descr,                                                            ";
  $sSelect .= "  orcorgao.o40_orgao,                                                              ";
  $sSelect .= "  orcorgao.o40_descr                                                             ";

  $sOrdem = " o40_orgao asc , o41_unidade asc ";
  if (!empty($sOrderBy)) {
    $sOrdem = "{$sOrdem} , {$sOrderBy}";
  }
  $sSqlBaixaBens = $clbensbaix->sql_query_relatorio(null, $sSelect, $sOrdem, "$relbaix");

} else {
  $sSqlBaixaBens = $clbensbaix->sql_query(null, $sSelect, "$sOrderBy", "$relbaix");
}

$result_bens   = $clbensbaix->sql_record($sSqlBaixaBens);

$oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setAutoPageBreak(false, 12);
$alt = 4;

$lQuebraOrgaoUnidade = false;

$iOrgao   = null;
$iUnidade = null;

$head3 = "BENS BAIXADOS";
$head6 = $sHeadPlaca;
if(trim($relbaix)==""){
  $head7 = "$msg" ;
}else{
  $head7 = "Período $msg " ;
}

$oPdf->addHeaderDescription($head3);
$oPdf->addHeaderDescription($head6);
$oPdf->addHeaderDescription($head7);
$oPdf->addPage();

$iNumRows = $clbensbaix->numrows;
for ($iRow = 0; $iRow < $iNumRows; $iRow++) {

  $oBem = db_utils::fieldsMemory($result_bens, $iRow);

  $result_descrmotbaixa = $clbensmotbaixa->sql_record($clbensmotbaixa->sql_query_file($oBem->t55_motivo));
  $oBemMotivoBaixa = db_utils::fieldsMemory($result_descrmotbaixa,0);

  $oPdf->setBold(false);
  $oPdf->setFontSize(6);

  $nWidthDescricao = 65;
  $nWidthClassificacao = 60;
  $nWidthDepartamento = $oPdf->getAvailWidth()-225;
  $nWidthInfo = $oPdf->getAvailWidth()-30;

  $iHeightDescricao     = $oPdf->getMultiCellHeight($nWidthDescricao, $alt, $oBem->t52_descr);
  $iHeightClassificacao = $oPdf->getMultiCellHeight($nWidthClassificacao, $alt, $oBem->t64_descr);
  $iHeightDepartamento  = $oPdf->getMultiCellHeight($nWidthDepartamento, $alt, $oBem->descrdepto);
  $iHeightMotivo        = $oPdf->getMultiCellHeight($nWidthInfo, $alt, $oBemMotivoBaixa->t51_descr);
  $iHeightDados         = $oPdf->getMultiCellHeight($nWidthInfo, $alt, $oBem->t55_obs);

  $iHeightLinha = max(array($iHeightDescricao, $iHeightClassificacao, $iHeightDepartamento));

  if ($lImprimeOrgao) {

    $oPdf->setFontSize(9);
    $oPdf->setBold(true);

    if ($oBem->o40_orgao != $iOrgao || $oBem->o41_unidade != $iUnidade) {

      $lQuebraOrgaoUnidade = true;

      if ($iRow != 0) {
        $oPdf->addPage();
      }

      $oPdf->cell(20,$alt,"Órgão",0,0,"L",0);
      $oPdf->cell(30,$alt,$oBem->o40_orgao." - ".$oBem->o40_descr,0,1,"L",0);
      $iOrgao = $oBem->o40_orgao;

      $oPdf->cell(20,$alt,"Unidade",0,0,"L",0);
      $oPdf->cell(30,$alt,$oBem->o41_unidade." - ".$oBem->o41_descr,0,1,"L",0);
      $iUnidade = $oBem->o41_unidade;
      $oPdf->Ln(1);
    }
  }

  if ($oPdf->getAvailHeight() <= array_sum(array($iHeightLinha, $iHeightMotivo, $iHeightDados)) || $iRow == 0 || $lQuebraOrgaoUnidade) {

    if ($iRow != 0 && !$lQuebraOrgaoUnidade) {
      $oPdf->addPage();
    }

    $lQuebraOrgaoUnidade = false;

    $oPdf->setFontSize(8);
    $oPdf->cell(15, $alt, "Baixa", 1, 0, "C", 1);
    $oPdf->cell(15, $alt, "Código", 1, 0, "C", 1);
    $oPdf->cell(15, $alt, "Placa", 1, 0, "C", 1);
    $oPdf->cell(20, $alt, "Valor Atual", 1, 0, "C", 1);
    $oPdf->cell($nWidthDescricao, $alt, $RLt52_descr, 1, 0, "C", 1);
    $oPdf->cell(20, $alt, $RLt64_class, 1, 0, "C", 1);
    $oPdf->cell($nWidthClassificacao, $alt, $RLt64_descr, 1, 0, "C", 1);
    $oPdf->cell(15, $alt, "Definição" ,1 ,0 ,"C" ,1);
    $oPdf->cell($nWidthDepartamento,$alt,$RLdescrdepto,1,1,"C",1);
  }

  $oBemModel = new Bem();
  $oBemModel->setCodigoBem($oBem->t52_bem);
  $oBemModel->setInstituicao($oBem->t52_instit);

  $oBemDepreciacao = BemDepreciacao::getInstance($oBemModel);
  $nValorDepreciacao = $oBem->t52_valaqu;

  if (!empty($oBemDepreciacao)) {
    $nValorDepreciacao = $oBemDepreciacao->getValorAtual() + $oBemDepreciacao->getValorResidual();
  }

  $oPdf->setAutoNewLineMulticell(false);
  $oPdf->setBold(false);
  $oPdf->setFontSize(6);

  $oPdf->cell(15, $iHeightLinha, db_formatar($oBem->t55_baixa,"d"),"T",0,"C",0);
  $oPdf->cell(15, $iHeightLinha, $oBem->t52_bem,"T",0,"C",0);
  $oPdf->cell(15, $iHeightLinha, $oBem->t52_ident,"T",0,"C",0);
  $oPdf->cell(20, $iHeightLinha, trim(db_formatar($nValorDepreciacao, 'f')), 'T', 0, 'R');
  $oPdf->multiCell($nWidthDescricao, ($iHeightLinha/$iHeightDescricao)*$alt, $oBem->t52_descr, 'T', "L");
  $oPdf->cell(20, $iHeightLinha, $oBem->t64_class, "T", 0, "C");
  $oPdf->multiCell($nWidthClassificacao, ($iHeightLinha/$iHeightClassificacao)*$alt, $oBem->t64_descr, 'T', 'L');
  $oPdf->cell(15, $iHeightLinha, $oBem->definicao, "T", 0, "L");

  $oPdf->setAutoNewLineMulticell(true);
  $oPdf->multicell($nWidthDepartamento, ($iHeightLinha/$iHeightDepartamento)*$alt, $oBem->descrdepto, 'T', 'L');

  $oPdf->setBold(true);
  $oPdf->cell(30, $iHeightMotivo, $RLt51_descr, 1, 0, "L", 1);
  $oPdf->setBold(false);
  $oPdf->multiCell($nWidthInfo, $alt, $oBemMotivoBaixa->t51_descr, 'T', 'L');

  $oPdf->setBold(true);
  $oPdf->cell(30, $iHeightDados, $RLt55_obs, 1, 0, "L", 1);
  $oPdf->setBold(false);
  $oPdf->multiCell($nWidthInfo, $alt, $oBem->t55_obs, 'T:B', 'L');
}

$oPdf->setBold(true);
$oPdf->setFontSize(8);
$oPdf->cell($oPdf->getAvailWidth(), $alt, "TOTAL DE REGISTROS: {$iNumRows}", 'T', "L");
$oPdf->showPDF("bens_baixados" . time());
