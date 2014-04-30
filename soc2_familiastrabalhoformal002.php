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
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("std/DBDate.php");

db_app::import("configuracao.avaliacao.AvaliacaoRepository");
db_app::import("configuracao.avaliacao.FiltroAvaliacao");
db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");

$oGet               = db_utils::postMemory($_GET);
$aTipoTrabalho      = explode(",", $oGet->sTipoTrabalho);
$aIdentificador     = array();
foreach ($aTipoTrabalho as $iTipoTrabalho) {
  
  if ($iTipoTrabalho == 0) {
    $aIdentificador[] = 'EmpregadoComCarteiraTrabalhoAssinada';
  }
  
  if ($iTipoTrabalho == 1) {
    $aIdentificador[] = 'MilitarServidorPublico';
  }
  
  if ($iTipoTrabalho == 2) {
    $aIdentificador[] = 'DomesticoComCarteiraAssinada';
  }
}
$aAvaliacoes        = array();
$aFamilias          = array();
$aRespostaAvaliacao = array();

$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoCadastroUnicoCidadao')
                                     ->doGrupo('TrabalhoRemuneracao')
                                     ->comPergunta('FuncaoTrabalhoPrincipal')
                                     ->comRespostas($aIdentificador)
                                     ->retornarAvaliacoes();

foreach ($aAvaliacoes as $oAvaliacao) {
  
  $oDaoCidadaoAvaliacao    = db_utils::getDao('cidadaoavaliacao');
  $sCamposCidadaoAvaliacao = "ov02_sequencial, ov02_seq, as02_sequencial";
  $sWhereCidadaoAvaliacao  = "     db107_sequencial  = {$oAvaliacao->getAvaliacaoGrupo()} ";
  $sWhereCidadaoAvaliacao .= " and as03_tipofamiliar = 0";
  $sSqlCidadaoAvaliacao    = $oDaoCidadaoAvaliacao->sql_query_cadastrounico(null, 
                                                                            $sCamposCidadaoAvaliacao, 
                                                                            null, 
                                                                            $sWhereCidadaoAvaliacao
                                                                           );
  $rsCidadaoAvaliacao      = $oDaoCidadaoAvaliacao->sql_record($sSqlCidadaoAvaliacao);
  if ($oDaoCidadaoAvaliacao->numrows > 0) {
    
    $oDadosCidadaoAvaliacao = db_utils::fieldsMemory($rsCidadaoAvaliacao, 0);
    
    /**
     * Buscamos os codigos sequenciais das familias
     */
    $oDaoFamilia    = db_utils::getDao('cidadaofamilia');
    $sCamposFamilia = "DISTINCT as04_sequencial";
    $sWhereFamilia  = "     ov02_sequencial = {$oDadosCidadaoAvaliacao->ov02_sequencial}";
    $sWhereFamilia .= " and ov02_seq = {$oDadosCidadaoAvaliacao->ov02_seq}";
    $sSqlFamilia    = $oDaoFamilia->sql_query_completa(null, $sCamposFamilia, null, $sWhereFamilia);
    $rsFamilia      = $oDaoFamilia->sql_record($sSqlFamilia);
    $iTotalFamilia  = $oDaoFamilia->numrows;
    
    if ($iTotalFamilia == 0) {
      db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum responsável foi encontrado com trabalho formal.");
    }
    
    /**
     * Criamos o Objeto da Familia
     */
    for ($iContador = 0; $iContador < $iTotalFamilia; $iContador++) {
    
      $iCodigoFamilia                      = db_utils::fieldsMemory($rsFamilia, $iContador)->as04_sequencial;
      $aFamilias[]                         = new Familia($iCodigoFamilia);
      $aRespostaAvaliacao[$iCodigoFamilia] = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('FuncaoTrabalhoPrincipal');
    }
  }
}

$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Famílias com Pessoas com Trabalho Formal";

$iTotalRegistros = 0;

/**
 * Percorremos as Familias imprimindo os valores solicitados no relatorio
 */
foreach ($aFamilias as $oFamilia) {

  if ($oFamilia->getResponsavel() == null) {
    continue;
  }
  $sNIS           = $oFamilia->getResponsavel()->getNis();
  $iCodigoFamilia = $oFamilia->getCodigoFamiliarCadastroUnico();
  $sResponsavel   = $oFamilia->getResponsavel()->getNome();
  $sBairro        = $oFamilia->getResponsavel()->getBairro();
  $sEndereco      = $oFamilia->getResponsavel()->getEndereco() . ", ". $oFamilia->getResponsavel()->getNumero();
  if ($oPdf->gety() > $oPdf->h - 15 || $lPrimeiroLaco ) {

    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }

  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(20,  $iHeigth, "{$sNIS}",                                        "TBR",  0);
  $oPdf->Cell(20,  $iHeigth, "{$iCodigoFamilia}",                              "TBRL", 0);
  $oPdf->Cell(70,  $iHeigth, substr($sResponsavel, 0, 70),                     "TBRL", 0);
  $oPdf->Cell(60,  $iHeigth, substr($sEndereco, 0, 60),                        "LTB",  0);
  $oPdf->Cell(30,  $iHeigth, substr($sBairro, 0, 30),                          "TBRL", 0);
  $oPdf->Cell(25,  $iHeigth, db_formatar($oFamilia->getRendaPerCapita(), 'f'), "TBRL", 0, "R");
  foreach ($aRespostaAvaliacao[$oFamilia->getCodigoSequencial()] as $sResposta) {
    $oPdf->Cell(55,  $iHeigth, substr(urldecode($sResposta->descricaoresposta), 4), "TBRL", 1);
  }

  $iTotalRegistros ++;
}

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(225, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(55,  $iHeigth, $iTotalRegistros,      "LTB",  1);

/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {
  
  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20,  $iHeigth, "NIS",                1, 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Cód. Familiar",      1, 0, "C", 1);
  $oPdf->Cell(70,  $iHeigth, "Responsável",        1, 0, "C", 1);
  $oPdf->Cell(60,  $iHeigth, "Endereço",           1, 0, "C", 1);
  $oPdf->Cell(30,  $iHeigth, "Bairro",             1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Renda Per Capita",   1, 0, "C", 1);
  $oPdf->Cell(55,  $iHeigth, "Trabalho Principal", 1, 1, "C", 1);
  
}
$oPdf->Output();