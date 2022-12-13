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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("classes/db_bens_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_db_depart_classe.php");

$oDaoBens        = db_utils::getDao("bens");
$oDaoInstit      = db_utils::getDao("db_config");//new cl_db_config;
$oDaoDepart      = db_utils::getDao("db_depart");//new cl_db_depart;

$oGet            = db_utils::postMemory($_GET);

$iAlturalinha    = 4;
$iFonte          = 6;
$dDataUsu        = date("d/m/Y", db_getsession("DB_datausu"));
$iInstituicao    = db_getsession("DB_instit");
$nTotalAcumulado = 0;

$sCamposInstit   = "nomeinst, ";
$sCamposInstit  .= "munic||' - '||uf as municipio ";
$sSqlInstituicao = $oDaoInstit->sql_query(null, $sCamposInstit, null, "codigo = {$iInstituicao}");

$rsInstit        = $oDaoInstit->sql_record($sSqlInstituicao);
$sInstituicao    = db_utils::fieldsMemory($rsInstit, 0)->nomeinst;
$sMunicipio      = db_utils::fieldsMemory($rsInstit, 0)->municipio;

$sSqlDepart      = $oDaoDepart->sql_query_file(db_getsession("DB_coddepto"));
$rsDepart        = $oDaoDepart->sql_record($sSqlDepart);
$sDepartamento   = db_utils::fieldsMemory($rsDepart, 0)->descrdepto;

$sOrgao           = $sInstituicao;
$sUnidadeControle = $sDepartamento;
$dDataInicial     = $oGet->dDataInicial;
$dDataFinal       = $oGet->dDataFinal;
$iDepartamento    = db_getsession("DB_coddepto");
$iRodape          = 0;

/*
 * Campos do SQL
 */
$sBensCampos  = "DISTINCT t52_ident,";
$sBensCampos .= "t52_bem,    ";
$sBensCampos .= "t52_codcla, ";
$sBensCampos .= "t52_descr,  ";
$sBensCampos .= "t52_valaqu, ";
$sBensCampos .= "t52_obs    ";

$aWhereBens = array();
if ($dDataInicial != "") {
  $aWhereBens[]  = "t52_dtaqu >= '{$dDataInicial}'";
}
if ($dDataFinal != "") {
  $aWhereBens[]  = "t52_dtaqu <= '{$dDataFinal}'";
}

$aWhereBens[] = "db_depart.instit = {$iInstituicao}";
$aWhereBens[] = "(t55_baixa is null or t55_baixa > '{$dDataFinal}')";

if (!empty($oGet->iTipoBem) && $oGet->iTipoBem != 0) {
  $aWhereBens[] = "clabens.t64_bemtipos = {$oGet->iTipoBem}";
}

$sWhereBens   = implode(" and ", $aWhereBens);

$sOrderBens   = "t52_codcla";
$sSqlBens = $oDaoBens->sql_query_class(null, $sBensCampos, $sOrderBens, $sWhereBens);


$rsBens   = $oDaoBens->sql_record($sSqlBens);

if ($oDaoBens->numrows == 0) {

  $sMsg = _M('patrimonial.patrimonio.pat2_relatoriolegalanexoonze002.nenhum_registro_encontrado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}

$aBens    = db_utils::getCollectionByRecord($rsBens);

$dtInicial = db_formatar($oGet->dDataInicial, "d");
$dtFinal   = db_formatar($oGet->dDataFinal  , "d");

$sPeriodo = " ATÉ {$dtFinal} ";
if (!empty($dtInicial) && $dtInicial != '') {
	$sPeriodo = " DE {$dtInicial} ATÉ {$dtFinal}";
}


$oPdf     = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);
$head1  = "               RELATÓRIO LEGAL MODELO 11";
$head2  = "BENS PATRIMONIAIS - ARROLAMENTO DAS EXISTÊNCIAS $sPeriodo";
$head3  = "\nOrgão / Entidade      : {$sOrgao}";
$head4  = "Município                  : {$sMunicipio}";
$head5  = "Unidade de Controle : {$sUnidadeControle}";
$oPdf->AddPage("L");
$oPdf->setfont('arial','b',$iFonte);
//==============================  CABEÇALHO ==============================================
imprimirCabecalho($oPdf, $iAlturalinha, true);
//===========================================================================================

foreach ($aBens as $iIndiceBens => $oValorBens) {

  $oPdf->setfont('arial','',$iFonte);

  $nValorUnitario   = db_formatar($oValorBens->t52_valaqu, "f");
  $nTotalAcumulado += $oValorBens->t52_valaqu;

  $oPdf->cell(25 ,  $iAlturalinha,  $oValorBens->t52_codcla              , 1,  0, "R", 0);
  $oPdf->cell(25 ,  $iAlturalinha,  $oValorBens->t52_ident               , 1,  0, "C", 0);
  $oPdf->cell(80 ,  $iAlturalinha,  substr($oValorBens->t52_descr, 0, 70), 1,  0, "L", 0);   //Características de Identificaçã
  $oPdf->cell(25 ,  $iAlturalinha,  "Unidade"                            , 1,  0, "L", 0);
  $oPdf->cell(25 ,  $iAlturalinha,  "1"                                  , 1,  0, "R", 0);   //Quantidade
  $oPdf->cell(20 ,  $iAlturalinha,  $nValorUnitario                      , 1,  0, "R", 0);
  $oPdf->cell(20 ,  $iAlturalinha,  $nValorUnitario                      , 1,  0, "R", 0);
  $oPdf->cell(60 ,  $iAlturalinha,  substr($oValorBens->t52_obs, 0, 45)  , 1,  1, "L", 0);   //Observações


  if ($iRodape == 35) {

    imprimirRodape($oPdf, $iAlturalinha, $nTotalAcumulado);
    $iRodape = 0;
  }

  imprimirCabecalho($oPdf, $iAlturalinha, false);
  $iRodape ++;
}

imprimirRodape($oPdf, $iAlturalinha, $nTotalAcumulado);


//========  RODAPE FINAL COM ASSINATURAS:
$oPdf->setfont('arial','b', 6);
$oPdf->Ln();
$oPdf->cell(90,  $iAlturalinha, "Elaborado por" , "LBTR",  0, "C", 0);
$oPdf->cell(90,  $iAlturalinha, "Conferido por" , "LBTR",  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, "Visto"         , "LBTR",  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, "Data"          , "LBTR",  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Nome", "LR",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""    , "R" ,  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Matrícula", "LR",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""         , "R" ,  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Assinatura", "LRB",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""          , "RB" ,  1, "C", 0);
$oPdf->cell(280,  $iAlturalinha, "Correspondente ao modelo IGF/65" , "",  0, "R", 0);
$oPdf->output();

//=========  RODAPÉ COM TOTAL POR PAGINA
function imprimirRodape($oPdf, $iAlturalinha, $nTotalAcumulado ) {

  $oPdf->setfont('arial','b', 6);
  $oPdf->cell(130,  $iAlturalinha, ""                      , "TR"  , 0, "C", 0);
  $oPdf->cell(50 ,  $iAlturalinha, "A TRANSPORTAR / TOTAL" , "TBR" , 0, "C", 0);
  $oPdf->cell(20 ,  $iAlturalinha, db_formatar($nTotalAcumulado, "f")            , "LBT", 0, "L", 0);
  $oPdf->cell(80 ,  $iAlturalinha, ""                      , "TBR" , 1, "C", 0);
}

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {

	if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

		$oPdf->SetFont('arial', 'b', 6);

		if ( !$lImprime ) {
			$oPdf->AddPage("L");
		}

		$oPdf->setfont('arial','b',6);
    $oPdf->cell(25 ,  $iAlturalinha, "Código de"                        , "LTR" ,  0, "C", 1);
    $oPdf->cell(25 ,  $iAlturalinha, "Número de "                       , "LTR" ,  0, "C", 1);
    $oPdf->cell(80 ,  $iAlturalinha, "Características de Identificação" , "LTR" ,  0, "C", 1);
    $oPdf->cell(25 ,  $iAlturalinha, "Unidade de "                      , "LTR" ,  0, "C", 1);
    $oPdf->cell(25 ,  $iAlturalinha, "Quantidade"                       , "LTR" ,  0, "C", 1);
    $oPdf->cell(40 ,  $iAlturalinha, "Valor R$"                         , "LTR" ,  0, "C", 1);
    $oPdf->cell(60 ,  $iAlturalinha, "Observações"                      , "TLR",  1, "C", 1);
    // segunda linha do cabeçalho
    $oPdf->cell(25 ,  $iAlturalinha, "Classificação" , "LBR" ,  0, "C", 1);
    $oPdf->cell(25 ,  $iAlturalinha, "Inventariação" , "LBR" ,  0, "C", 1);
    $oPdf->cell(80 ,  $iAlturalinha, ""              , "LBR" ,  0, "C", 1);   //Características de Identificaçã
    $oPdf->cell(25 ,  $iAlturalinha, "Medida"        , "LBR" ,  0, "C", 1);
    $oPdf->cell(25 ,  $iAlturalinha, ""              , "LBR" ,  0, "C", 1);   //Quantidade
    $oPdf->cell(20 ,  $iAlturalinha, "Unitário"      , "TLBR",  0, "C", 1);
    $oPdf->cell(20 ,  $iAlturalinha, "Global"        , "TLBR",  0, "C", 1);
    $oPdf->cell(60 ,  $iAlturalinha, ""              , "LBR" ,  1, "C", 1);   //Observações
	}
}
?>