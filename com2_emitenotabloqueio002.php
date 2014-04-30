<?php
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
 
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_depart_classe.php");
require_once("model/itemSolicitacao.model.php");
require_once("libs/db_liborcamento.php");
require_once("model/Dotacao.model.php");
require_once("libs/db_app.utils.php");
require_once("fpdf151/assinatura.php");
require_once("model/configuracao/UsuarioSistema.model.php");
db_app::import("empenho.AutorizacaoEmpenho");
db_app::import("CgmFactory");
$oGet = db_utils::postMemory($_GET);

$oUsuarioSistema = new UsuarioSistema(db_getsession("DB_id_usuario"));

$iAnoUsuSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");

$iSolicitaInicio    = $oGet->iSolicitaInicio;
$iSolicitaFim       = $oGet->iSolicitaFim;

$aWhereSolicitacao = array();
if (!empty($iSolicitaInicio)) {
  $aWhereSolicitacao[] = "pc10_numero >= {$iSolicitaInicio}";
}
if (!empty($iSolicitaFim)) {
  $aWhereSolicitacao[] = "pc10_numero <= {$iSolicitaFim}";
}
$sWhereSolicitacao = implode(" and ", $aWhereSolicitacao);

$oDaoSolicita      = db_utils::getDao('solicita');
$sSqlBuscaSolicita = $oDaoSolicita->sql_query_solicita(null, "distinct *", null, $sWhereSolicitacao);
$rsBuscaSolicita   = $oDaoSolicita->sql_record($sSqlBuscaSolicita);
$iTotalSolicitacao = $oDaoSolicita->numrows;


if ($iTotalSolicitacao == 0) {
  //db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para o filtro selecionado.");exit;
}

$aDadosImprimir = array();
for ($iRowSolicita = 0; $iRowSolicita < $iTotalSolicitacao; $iRowSolicita++) {
  
  $oDadoSolicita = db_utils::fieldsMemory($rsBuscaSolicita, $iRowSolicita);
  $oStdDadosSolicita = new stdClass();
  $oStdDadosSolicita->iCodigoSolicitacao = $oDadoSolicita->pc10_numero;
  $oStdDadosSolicita->sResumoSolicitacao = $oDadoSolicita->pc10_resumo;
  $oStdDadosSolicita->aDotacao           = array();
  
  $oDaoSolicitem  = db_utils::getDao('solicitem');
  $sSqlBuscaItens = $oDaoSolicitem->sql_query_file(null, "*", null, "pc11_numero = {$oDadoSolicita->pc10_numero}");
  $rsBuscaItens   = $oDaoSolicitem->sql_record($sSqlBuscaItens);
  $iTotalItens    = $oDaoSolicitem->numrows;
  
  if ($iTotalItens > 0) {

    for ($iRowItens = 0; $iRowItens < $iTotalItens; $iRowItens++) {
      
      $oDadoItem            = db_utils::fieldsMemory($rsBuscaItens, $iRowItens);
                           
      $sCamposDotacao       = "orcorgao.o40_orgao, orcorgao.o40_descr, ";
      $sCamposDotacao      .= "orcunidade.o41_unidade, orcunidade.o41_descr, ";
      $sCamposDotacao      .= "orcfuncao.o52_funcao, orcfuncao.o52_descr, ";
      $sCamposDotacao      .= "orcsubfuncao.o53_subfuncao, orcsubfuncao.o53_descr, ";
      $sCamposDotacao      .= "orcprograma.o54_programa, orcprograma.o54_descr, ";
      $sCamposDotacao      .= "orcprojativ.o55_projativ, orcprojativ.o55_descr, ";
      $sCamposDotacao      .= "orcelemento.o56_elemento, orcelemento.o56_descr, ";
      $sCamposDotacao      .= "orctiporec.o15_codigo,orctiporec.o15_descr, ";
      $sCamposDotacao      .= "orcdotacao.o58_coddot, orcreserva.o80_valor, orcreserva.o80_dtlanc,";
      $sCamposDotacao      .= "pcdotac.pc13_anousu";

      $sWhereItem           = "pcdotac.pc13_codigo = {$oDadoItem->pc11_codigo}";
      $oDaoProcessoCompra   = db_utils::getDao('pcdotac');
      $sSqlDotacaoPorItem   = $oDaoProcessoCompra->sql_query_dotacao(null, null, null, $sCamposDotacao, null, $sWhereItem);
      $rsDotacaoPorItem     = $oDaoProcessoCompra->sql_record($sSqlDotacaoPorItem);
      $iTotalDotacaoPorItem = $oDaoProcessoCompra->numrows;
      
      if ($iTotalDotacaoPorItem > 0) {
        
          
        for ($iRowDotacao = 0; $iRowDotacao < $iTotalDotacaoPorItem; $iRowDotacao++) {
            
          $oDadosDotacao = db_utils::fieldsMemory($rsDotacaoPorItem, $iRowDotacao);
          $oDotacao      = new Dotacao($oDadosDotacao->o58_coddot, $oDadosDotacao->pc13_anousu);
          
          if (!array_key_exists($oDadosDotacao->o58_coddot, $oStdDadosSolicita->aDotacao)) {
          
            $oStdDotacao   = new stdClass();
            $oStdDotacao->iCodigoOrgao               = $oDadosDotacao->o40_orgao;
            $oStdDotacao->sDescricaoOrgao            = $oDadosDotacao->o40_descr;
            $oStdDotacao->iCodigoUnidade             = $oDadosDotacao->o41_unidade;
            $oStdDotacao->sDescricaoUnidade          = $oDadosDotacao->o41_descr;
            $oStdDotacao->iCodigoFuncao              = $oDadosDotacao->o52_funcao;
            $oStdDotacao->sDescricaoFuncao           = $oDadosDotacao->o52_descr;
            $oStdDotacao->iCodigoSubFuncao           = $oDadosDotacao->o53_subfuncao;
            $oStdDotacao->sDescricaoSubFuncao        = $oDadosDotacao->o53_descr;
            $oStdDotacao->iCodigoPrograma            = $oDadosDotacao->o54_programa;
            $oStdDotacao->sDescricaoPrograma         = $oDadosDotacao->o54_descr;
            $oStdDotacao->iCodigoProjetoAtividade    = $oDadosDotacao->o55_projativ;
            $oStdDotacao->sDescricaoProjetoAtividade = $oDadosDotacao->o55_descr;
            $oStdDotacao->iCodigoElemento            = $oDadosDotacao->o56_elemento;
            $oStdDotacao->sDescricaoElemento         = $oDadosDotacao->o56_descr;
            $oStdDotacao->iCodigoRecurso             = $oDadosDotacao->o15_codigo;
            $oStdDotacao->sDescricaoRecurso          = $oDadosDotacao->o15_descr;
            $oStdDotacao->iCodigoDotacao             = $oDadosDotacao->o58_coddot;
            $oStdDotacao->aDadosTabela               = array();
            
            $oStdDadosTabela                          = new stdClass();
            $oStdDadosTabela->nValorReserva           = $oDadosDotacao->o80_valor;
            $oStdDadosTabela->dtReserva               = $oDadosDotacao->o80_dtlanc;
            $oStdDadosTabela->sProcessoAdministrativo = $oDadoSolicita->pc90_numeroprocesso;
            $oStdDadosTabela->nSaldoDotacaoAntes      = $oDotacao->getSaldoAtual();
            $oStdDadosTabela->nSaldoDotacaoAtual      = $oDotacao->getSaldoAtualMenosReservado();
            
            $oStdDotacao->aDadosTabela[$oDadosDotacao->o80_dtlanc]   = $oStdDadosTabela;
            $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot] = $oStdDotacao;
            
            
          } else {
            
            if (array_key_exists($oDadosDotacao->o80_dtlanc, $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]->aDadosTabela)) {
              $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]->aDadosTabela[$oDadosDotacao->o80_dtlanc]->nValorReserva += $oDadosDotacao->o80_valor;
            } else {
              
              $oStdDadosTabela                          = new stdClass();
              $oStdDadosTabela->nValorReserva           = $oDadosDotacao->o80_valor;
              $oStdDadosTabela->dtReserva               = $oDadosDotacao->o80_dtlanc;
              $oStdDadosTabela->sProcessoAdministrativo = $oDadoSolicita->pc90_numeroprocesso;
              $oStdDadosTabela->nSaldoDotacaoAntes      = $oDotacao->getSaldoAtual();
              $oStdDadosTabela->nSaldoDotacaoAtual      = $oDotacao->getSaldoAtualMenosReservado();
              $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]->aDadosTabela[$oDadosDotacao->o80_dtlanc] = $oStdDadosTabela;
            }
          }
        }
      }
    }
    $aDadosImprimir[] = $oStdDadosSolicita;
  }
}

$head2 = "Nota de Bloqueio";
$head3 = "Exercício de {$iAnoUsuSessao}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->SetFont('arial',  'b',  6);

$iAlturaLinha       = 4;
$lPrimeiroLaco      = true;
$iCodigoSolicitacao = $aDadosImprimir[0]->iCodigoSolicitacao;
foreach ($aDadosImprimir as $iIndice => $oSolicitacao) {

  if($oPdf->gety() > $oPdf->h-35 || $lPrimeiroLaco || $iCodigoSolicitacao != $oSolicitacao->iCodigoSolicitacao) {

    imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
	  $lPrimeiroLaco = false;
  }
	
	foreach ($oSolicitacao->aDotacao as $oDadosDotacao) {
	  
	  if($oPdf->gety() > $oPdf->h-10 || $lPrimeiroLaco) {
	    
	    $oPdf->AddPage();
	    imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
	  }
	  imprimeDetalhamento ($oPdf, $oDadosDotacao, $iAlturaLinha, $oSolicitacao) ;
	}
	
  $oDaoAssinatura = new cl_assinatura();
  $sAssinatura    = $oDaoAssinatura->assinatura(1900);
  $oPdf->ln(10);
  $oPdf->cell(95, $iAlturaLinha, "{$oUsuarioSistema->getNome()}"       , "", 0, "C", 0);
  $oPdf->cell(95, $iAlturaLinha, "{$sAssinatura}"                      , "", 1, "C", 0);
  $oPdf->cell(95, $iAlturaLinha, "Emitente"                            , "", 0, "C", 0);
  $oPdf->cell(95, $iAlturaLinha, "Secretário Municipal do Planejamento", "", 1, "C", 0);
}

$oPdf->Output();


function imprimeDetalhamentoCabecalho($oPdf, $oDado, $iAlturaLinha) {

	$oPdf->AddPage();
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell(200, $iAlturaLinha, "Solicitação de Compras nº: {$oDado->iCodigoSolicitacao}", 0, 1, "L", 0);
	$oPdf->multicell(200, $iAlturaLinha, "Histórico : {$oDado->sResumoSolicitacao}");
	$oPdf->cell(190, $iAlturaLinha, "Informamos que o saldo da dotação encontra-se suficiente e já foi bloqueado, conforme descrito abaixo:", 0, 1, "L", 0);
}

function imprimeDetalhamento ($oPdf, $oDado, $iAlturaLinha, $oSolicitacao) {

  if($oPdf->gety() > $oPdf->h-50) {
    
    $oDado->iCodigoSolicitacao =  
    imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
   }
	$iColuna1 = 20;
	$iColuna2 = 30;
	$iColuna3 = 40;
	$oPdf->ln();
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Órgão:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoOrgao}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoOrgao}", "", 1, "L", 0);

	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Unidade:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoOrgao}{$oDado->iCodigoUnidade}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoUnidade}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Função:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoFuncao}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoFuncao}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Subfunção:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoSubFuncao}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoSubFuncao}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Programa:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoPrograma}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoPrograma}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Projeto/Atividade:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoProjetoAtividade}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoProjetoAtividade}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Elemento:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoElemento}"  , "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoElemento}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Recurso:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoRecurso}", "", 0, "L", 0);
	$oPdf->cell($iColuna3 , $iAlturaLinha, "{$oDado->sDescricaoRecurso}", "", 1, "L", 0);
	
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell($iColuna1 , $iAlturaLinha, "Código Reduzido:", "", 0, "L", 0);
	$oPdf->setfont('Arial', '', 6);
	$oPdf->cell($iColuna2 , $iAlturaLinha, "{$oDado->iCodigoDotacao} ", "", 1, "L", 0);

	$oPdf->ln();
	$oPdf->setfont('Arial', 'B', 6);
	$oPdf->cell(30, $iAlturaLinha, "Data Bloqueio"          , 1, 0, "C", 0);
	$oPdf->cell(40, $iAlturaLinha, "Processo Administrativo", 1, 0, "C", 0);
	$oPdf->cell(40, $iAlturaLinha, "Saldo da Dotação"       , 1, 0, "C", 0);
	$oPdf->cell(40, $iAlturaLinha, "Valor Bloqueado"        , 1, 0, "C", 0);
	$oPdf->cell(40, $iAlturaLinha, "Saldo Atual"            , 1, 1, "C", 0);
  
 	foreach ($oDado->aDadosTabela as $oDadoTabela) {
 	  
  	$oPdf->setfont('Arial', '', 6);
  	$oPdf->cell(30, $iAlturaLinha, db_formatar($oDadoTabela->dtReserva, 'd')         , 1, 0, "C", 0);
  	$oPdf->cell(40, $iAlturaLinha, $oDadoTabela->sProcessoAdministrativo             , 1, 0, "C", 0);
  	$oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nSaldoDotacaoAntes, 'f'), 1, 0, "R", 0);
  	$oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nValorReserva, 'f')     , 1, 0, "R", 0);
  	$oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nSaldoDotacaoAtual, 'f'), 1, 1, "R", 0);
 	}
}
?>