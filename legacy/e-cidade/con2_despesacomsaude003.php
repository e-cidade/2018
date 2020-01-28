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
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("classes/db_bens_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_app.utils.php");
require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");
require_once ("model/relatorioContabil.model.php");
require_once ("model/configuracao/Instituicao.model.php");
db_app::import('contabilidade.relatorios.sigfis.*');

$oGet = db_utils::postmemory($_GET);

$sInstituicao = str_replace("-", ",", $oGet->sInstituicao);
$iAnoUsu = db_getsession("DB_anousu");


/**
 * buscamos os parametros relacionados ao periodo
 * data inicial e final
 *
 */

$oDaoPeriodo   = db_utils::getDao('periodo');
$sSqlPeriodo   = $oDaoPeriodo->sql_query($oGet->sPeriodo);
$rsPeriodo     = $oDaoPeriodo->sql_record($sSqlPeriodo);
$oDadosPeriodo = db_utils::fieldsMemory($rsPeriodo, 0);

$oDadosPeriodo->o114_diainicial = str_pad($oDadosPeriodo->o114_diainicial, 2, '0', STR_PAD_LEFT);
$oDadosPeriodo->o114_mesinicial = str_pad($oDadosPeriodo->o114_mesinicial, 2, '0', STR_PAD_LEFT);
$oDadosPeriodo->o114_diafinal   = str_pad($oDadosPeriodo->o114_diafinal, 2, '0', STR_PAD_LEFT);
$oDadosPeriodo->o114_mesfinal   = str_pad($oDadosPeriodo->o114_mesfinal, 2, '0', STR_PAD_LEFT);


$dDataInicial = $oDadosPeriodo->o114_diainicial . "/" .$oDadosPeriodo->o114_mesinicial. "/" . $iAnoUsu;
$dDataFinal   = $oDadosPeriodo->o114_diafinal   . "/" .$oDadosPeriodo->o114_mesfinal  . "/" . $iAnoUsu;


$aInstituicoes = explode(",", $sInstituicao);
$aDescricaoInstituicao = array();;
foreach ($aInstituicoes as $iCodigoInstituicao) {
  
  $oInstituicao = new Instituicao($iCodigoInstituicao);
  $aDescricaoInstituicao[] = $oInstituicao->getDescricao();
  unset($oInstituicao);
}

$oDemonstrativoSaude = new DemonstrativoDespesaSaude($iAnoUsu, 122, $oGet->sPeriodo);
$oDemonstrativoSaude->setInstituicao($sInstituicao);

$aDadosDemonstrativoSaude = $oDemonstrativoSaude->getDados();

if (count($aDadosDemonstrativoSaude) == 0) {
  
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para o filtro selecionado.");
  exit;
}

$iAlturalinha = 4;
$iFonte       = 6;
$oPdf         = new PDF("L");

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$head1  = "Demonstrativo de Despesas com Saњde";
$head2  = " ";
$head3  = "Perэodo: {$dDataInicial} р {$dDataFinal}  ";
$head4  = "Instituiчѕes: ".implode(" - ", $aDescricaoInstituicao);

$oPdf->AddPage("L");
$oPdf->setfont('arial','b',$iFonte);


//==============================  CABEЧALHO ==============================================
imprimirCabecalho($oPdf, $iAlturalinha, true);
//========================================================================================

foreach ($aDadosDemonstrativoSaude as $oValorDemonstrativo){
  
  $sEspacos = str_repeat(" ", $oValorDemonstrativo->nivellinha);
  
  /*
   * valores :
   */
  $nPrevisaoInicial    = db_formatar($oValorDemonstrativo->previni,   "f");
  $nPrevisaoAtualizada = db_formatar($oValorDemonstrativo->prevatu,   "f");
  $nValorEmpenhado     = db_formatar($oValorDemonstrativo->empenhado, "f");
  $nValorLiquidado     = db_formatar($oValorDemonstrativo->liquidado, "f");
  $nValorPago          = db_formatar($oValorDemonstrativo->pago,      "f");
  
  $oPdf->cell(130, $iAlturalinha, $sEspacos . $oValorDemonstrativo->descricao , "TB" ,  0, "L", 0);
  $oPdf->cell(30 , $iAlturalinha,             $nPrevisaoInicial               , "TB" ,  0, "R", 0);
  $oPdf->cell(30 , $iAlturalinha,             $nPrevisaoAtualizada            , "TB" ,  0, "R", 0);
  $oPdf->cell(30 , $iAlturalinha,             $nValorEmpenhado                , "TB" ,  0, "R", 0);
  $oPdf->cell(30 , $iAlturalinha,             $nValorLiquidado                , "TB" ,  0, "R", 0);
  $oPdf->cell(30 , $iAlturalinha,             $nValorPago                     , "TB" ,  1, "R", 0);  
  
  imprimirCabecalho($oPdf, $iAlturalinha, false);
}

$oPdf->output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {

	if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

		$oPdf->SetFont('arial', 'b', 6);

		if ( !$lImprime ) {
			$oPdf->AddPage("L");
		}

		$oPdf->setfont('arial','b', 6);
    $oPdf->cell(130 , $iAlturalinha, "Descriчуo das Linhas"  , "TBLR" ,  0, "C", 1);
    $oPdf->cell(30 ,  $iAlturalinha, "Previsуo Inicial "     , "TBLR" ,  0, "C", 1);
    $oPdf->cell(30 ,  $iAlturalinha, "Previsуo Atualizada"   , "TBLR" ,  0, "C", 1);
    $oPdf->cell(30 ,  $iAlturalinha, "Valor Empenhado "      , "TBLR" ,  0, "C", 1);
    $oPdf->cell(30 ,  $iAlturalinha, "Valor Liquidado"       , "TBLR" ,  0, "C", 1);
    $oPdf->cell(30 ,  $iAlturalinha, "Valor Pago"            , "TBLR" ,  1, "C", 1);
    
	}
}
?>