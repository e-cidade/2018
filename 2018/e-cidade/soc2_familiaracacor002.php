<?php
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
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");

$oGet     = db_utils::postMemory($_GET);
$aCorRaca = explode(",", $oGet->sCorRacas);

$oDaoCidadaoFamilia = new cl_cidadaofamilia();
$sSqlListaCidadaos  = $oDaoCidadaoFamilia->sql_query_responsavel_por_resposta_avaliacao($aCorRaca);
$rsListaCidadaos    = $oDaoCidadaoFamilia->sql_record($sSqlListaCidadaos);
$iLinhas            = $oDaoCidadaoFamilia->numrows;

if ($iLinhas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$aCidadaos = organizaDados($rsListaCidadaos);


/**
 * Realizamos a ordenação dos dados.
 */
function ordernarDados($aArrayAtual, $aProximoArray){
  return strcasecmp($aArrayAtual->nome, $aProximoArray->nome);
}
uasort($aCidadaos, "ordernarDados");

$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Famílias por Raca/Cor";

$iTotalRegistros = 0;
if (count($aCidadaos) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma Cidadão com deficiência encontrada.");
}
/**
 * Percorremos as Familias imprimindo os valores solicidado no relatorio
 */
foreach ($aCidadaos as $oCidadao) {
  
  if ($oPdf->gety() > $oPdf->h - 15 || $lPrimeiroLaco ) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  } 
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(25, $iHeigth, $oCidadao->nis, "TBR", 0, 'L');
  $oPdf->Cell(25, $iHeigth, $oCidadao->codigo_familia, "TBRL",0, "L");
  $oPdf->Cell(90, $iHeigth, $oCidadao->nome, "TBRL",0, 'L');
  $oPdf->Cell(50, $iHeigth, $oCidadao->endereco, "TBRL",0, 'L');
  $oPdf->Cell(40, $iHeigth, $oCidadao->bairro, "TBRL",0, 'L');
  $oPdf->Cell(20, $iHeigth, $oCidadao->cor_raca, "TBRL",0, 'L');
  $oPdf->Cell(30, $iHeigth, $oCidadao->renda_familiar, "TBL", 1,'R');
    $iTotalRegistros ++;
}
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(230, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(50,  $iHeigth, $iTotalRegistros,      "LTB",  1);
$oPdf->Output();

/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {
  
  $oPdf->setfillcolor(235);
  $oPdf->AddPage();
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25, $iHeigth, "NIS",           1, 0, "C", 1);
  $oPdf->Cell(25, $iHeigth, "Cód. Familiar", 1, 0, "C", 1);
  $oPdf->Cell(90, $iHeigth, "Responsável",   1, 0, "C", 1);
  $oPdf->Cell(50, $iHeigth, "Localidade",           1, 0, "C", 1);
  $oPdf->Cell(40, $iHeigth, "Bairro",           1, 0, "C", 1);
  $oPdf->Cell(20, $iHeigth, "Cor/Raça", 1, 0, "C", 1);
  $oPdf->Cell(30, $iHeigth, "Renda Per Capita",   1, 1, "C", 1);
}

function ajustaResposta($sResposta) {
  
  $sResposta = urldecode($sResposta);
  $iInicio   = strpos($sResposta, "-");
  $iInicio   = $iInicio === false ? 0 : $iInicio + 1;
  $sResposta = trim(substr($sResposta, $iInicio));
  return $sResposta;
}

function organizaDados($rsResource) {
  
  $aCidadaos = array();
  $iLinhas   = pg_num_rows($rsResource);
  
  for ($i = 0; $i < $iLinhas; $i++) {
  
    $oCidadao   = new CadastroUnico(db_utils::fieldsMemory($rsResource, $i)->as02_sequencial);
    $oAvaliacao = $oCidadao->getAvaliacao();
  
    $oCidadaoRelatorio                  = new stdClass();
    $aCorRaca                           = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('CorOuRaca');
    $oCidadaoRelatorio->cor_raca        = '';
    if (isset($aCorRaca[0])) {
      $oCidadaoRelatorio->cor_raca = ajustaResposta($aCorRaca[0]->descricaoresposta);
    }
    $oCidadaoRelatorio->nome            = $oCidadao->getNome();
    $oCidadaoRelatorio->nis             = $oCidadao->getNis();
    $oFamilia                           = $oCidadao->getFamilia();
    $oCidadaoRelatorio->codigo_familia  = $oFamilia->getCodigoFamiliarCadastroUnico();
    $oCidadaoRelatorio->renda_familiar  = db_formatar($oFamilia->getRendaPerCapita(), "f");
    $oCidadaoRelatorio->endereco        = $oCidadao->getEndereco();
    if (trim($oCidadaoRelatorio->endereco) != "") {
      $oCidadaoRelatorio->endereco .= ", {$oCidadao->getNumero()}";
    }
    $oCidadaoRelatorio->bairro          = $oCidadao->getBairro();
    $aCidadaos[] = $oCidadaoRelatorio;
  }
  
  return $aCidadaos;
}