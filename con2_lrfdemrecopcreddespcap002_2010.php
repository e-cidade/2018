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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once('libs/db_libcontabilidade.php');
require_once('libs/db_liborcamento.php');
require_once('classes/db_db_config_classe.php');
require_once("dbforms/db_funcoes.php");

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXIRREO");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil(105, false);
$oAnexoXI          = new AnexoXIRREO($iAnoUsu, 105, $oGet->periodo);
$oAnexoXI->setInstituicoes($sInstituicoes);

$aDadosAnexoXI = $oAnexoXI->getDados();

$iNumRows = count($aDadosAnexoXI);
if ($iNumRows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$sWhere        = "prefeitura is true";
$sSqlDbConfig  = $cldb_config->sql_query_file(null, "munic, codigo, nomeinst, nomeinstabrev", null, $sWhere);
$rsSqlDbConfig = $cldb_config->sql_record($sSqlDbConfig); 
$oMunicipio    = db_utils::fieldsMemory($rsSqlDbConfig, 0);

$head2         = "MUNICÍPIO DE {$oMunicipio->munic}";
$head3         = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4         = "DEMONSTRATIVO DAS RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL";
$head5         = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head6         = "JANEIRO A DEZEMBRO DE ".db_getsession('DB_anousu');

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage("P");
$oPdf->SetFillColor(235);

$iTamFonte = 5;
$iAltCell  = 3;

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(130, $iAltCell, 'RREO - ANEXO XI (LRF, art.53, § 1o, inciso I)', 'B', 0, "L", 0);
$oPdf->Cell(60, $iAltCell, 'R$ 1,00',                                   'B', 1, "R", 0);
    
$oPdf->Cell(64, $iAltCell+9, 'RECEITAS',                   'TBR', 0, "C", 0);
$oPdf->Cell(42, $iAltCell+9, 'PREVISÃO ATUALIZADA (a)',    'TBRL', 0, "C", 0);
$oPdf->Cell(49, $iAltCell+9, 'RECEITAS REALIZADAS (b)',    'TBRL', 0, "C", 0);
$oPdf->text(134,47.7,"Até o Bimestre");
$oPdf->Cell(35, $iAltCell+9, 'SALDO NÃO REALIZADO (c)=(a-b)', 'TBL', 1, "C", 0);
foreach ($aDadosAnexoXI->quadroreceitas as $oQuadroReceita) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(64, $iAltCell, substr($oQuadroReceita->descricaolinha, 0, 70),        'R', 0, "L", 0);
  $oPdf->Cell(42, $iAltCell, db_formatar($oQuadroReceita->previsaoatualizada, 'f'), 'RL', 0, "R", 0);
  $oPdf->Cell(49, $iAltCell, db_formatar($oQuadroReceita->receitaatualizada, 'f'),  'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar($oQuadroReceita->saldoaarealizar, 'f'),    'L', 1, "R", 0);
}

$oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);
    
$iPosicaoX = $oPdf->GetX();
$iPosicaoY = $oPdf->GetY();

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(64, $iAltCell+12, 'DESPESAS',                          'TBR', 0, "C", 0);
$oPdf->Cell(42, $iAltCell+12, 'DOTAÇÃO ATUALIZADA (d)',            'TBRL', 0, "C", 0);
$oPdf->Cell(49, $iAltCell+12, '',                                  'TBRL', 0, "C", 0);
$oPdf->Cell(35, $iAltCell+12, 'SALDO NÃO EXECUTADO (g)=(d-(e+f))', 'TBL', 1, "C", 0);

$oPdf->SetXY($iPosicaoX+106, $iPosicaoY);
$oPdf->Cell(49, $iAltCell, 'DESPESAS EXECUTADAS', 0, 0, "C", 0);

$oPdf->SetXY($iPosicaoX+106, $iPosicaoY+3);
$oPdf->Cell(49, $iAltCell, 'Até o Bimestre', 'B', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+106, $iPosicaoY+6);
$oPdf->Cell(24.5, $iAltCell, 'LIQUIDADAS', 'R', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+106, $iPosicaoY+9);
$oPdf->Cell(24.5, $iAltCell, '', 'R', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+106, $iPosicaoY+12);
$oPdf->Cell(24.5, $iAltCell, '(e)', 'R', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+6);
$oPdf->Cell(24.5, $iAltCell, 'INSCRITAS EM', 'L', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+9);
$oPdf->Cell(24.5, $iAltCell, 'RESTOS A PAGAR', 'L', 0, "C", 0);

$oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+12);
$oPdf->Cell(24.5, $iAltCell, 'NÃO PROCESSADOS (f)', 'L', 1, "C", 0);

foreach ($aDadosAnexoXI->quadrodespesas as $oQuadroDespesas) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(64, $iAltCell, substr($oQuadroDespesas->descricaolinha, 0, 70),        'R', 0, "L", 0);
  $oPdf->Cell(42, $iAltCell, db_formatar($oQuadroDespesas->dotacaoatualizada, 'f'),  'RL', 0, "R", 0);
  $oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->despesaliquidada, 'f'), 'R', 0, "R", 0);
  $oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->inscritasemrp, 'f'),    'L', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar($oQuadroDespesas->saldoaexecutar, 'f'),     'L', 1, "R", 0);
}

$oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(64, $iAltCell+9, 'RESULTADO PARA APURAÇÃO DA REGRA DE OURO (III)=(I-II)', 'TBR', 0, "C", 0);
$oPdf->Cell(42, $iAltCell+9, "(a-d)",                                                 'TBRL', 0, "C", 0);
$oPdf->Cell(49, $iAltCell+9, "(b)-(e+f)",                                             'TBRL', 0, "C", 0);
$oPdf->Cell(35, $iAltCell+9, "(c-g)",                                                 'TBL', 1, "C", 0);

$nValorAtualizado = $aDadosAnexoXI->resultadoregraouro->valoratualizado;
$nValorRealizado  = $aDadosAnexoXI->resultadoregraouro->valorrealizado;
$nSaldoaRealizar  = $aDadosAnexoXI->resultadoregraouro->saldoarealizar;

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(64, $iAltCell, '',                                  'TBR', 0, "R", 0);
$oPdf->Cell(42, $iAltCell, db_formatar($nValorAtualizado, 'f'), 'TBRL', 0, "R", 0);
$oPdf->Cell(49, $iAltCell, db_formatar($nValorRealizado, 'f'),  'TBRL', 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nSaldoaRealizar, 'f'),  'TBL', 1, "R", 0);

$oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

$oReltorioContabil->getNotaExplicativa($oPdf, $oGet->periodo);

$oReltorioContabil->assinatura($oPdf, 'LRF');

$oPdf->Output();
?>