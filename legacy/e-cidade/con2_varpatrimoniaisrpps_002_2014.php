<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_orcparamseq_classe.php");

$oGet          = db_utils::postMemory($_GET);
$orcparamrel   = new cl_orcparamrel;
$classinatura  = new cl_assinatura;
$clempresto    = new cl_empresto;
$clorcparamseq = new cl_orcparamseq;
$iCodigoPeriodo= $oGet->periodo;

define ('LARGURA_PAGINA',190);

$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Instituição RPPS.');
} else {
  $oInstit  = db_utils::fieldsMemory($rsInstit,0);
}

$oVariacaoPatrimonial = new VariacaoPatrimonialRPPS(db_getsession("DB_anousu"), 136, $oGet->periodo);
$aDados               = $oVariacaoPatrimonial->getDados();

$head2 =  $oInstit->nomeinst;
$head3 = "VARIAÇÃO PATRIMONIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($oGet->periodo == 17){
  $head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{

  $oDaoPeriodo = new cl_periodo();
  $sSqlPeriodo = $oDaoPeriodo->sql_query_file($oGet->periodo);
  $rsPeriodo   = $oDaoPeriodo->sql_record($sSqlPeriodo);
  if (!$rsPeriodo) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Período informado não cadastrado no sistema.');
  }
  $oPeriodo =db_utils::fieldsMemory($rsPeriodo, 0);
  $head4 = "JANEIRO A ".strtoupper($oPeriodo->o114_descricao." DE ".db_getsession("DB_anousu"));
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',7);
$oPdf->addpage();

$oPdf->setfont('arial', '', 6);

cabecalho($oPdf);

$iPosicaoLinha = $oPdf->GetY();
$lDireita      = false;

foreach($aDados as $iIndice => $oDados) {

  if ($iIndice == 12 ) {
    $oPdf->line($oPdf->lMargin, $oPdf->getY() + 3, LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY() + 3);
  }

  if ($iIndice == 23 || $iIndice == 42) {

    linha($oPdf, $oDados, true, $lDireita);
    continue;
  }

  if ($iIndice == 24) {

    $oPdf->SetLeftMargin(LARGURA_PAGINA/2 + $oPdf->lMargin);
    $oPdf->setY($iPosicaoLinha + 1);
    $lDireita = true;
  }

  if ($iIndice == 32) {
      
    for ($iIndiceEspaco = 1; $iIndiceEspaco <= 3; $iIndiceEspaco++) {
    
      $oPdf->ln();  
      $oPdf->cell(larguraColuna(30), 3, '', 0, 0, 'L');
      $oPdf->cell(larguraColuna(20), 3, '', 'L', 0, 'R');
    }
  }

  if ($iIndice == 21 || $iIndice == 22 || $iIndice == 41) {

    if ( empty($oDados->vlrexatual) && ($iIndice == 22 || $iIndice == 41) ) {      
      $oDados->vlrexatual = '-';      
    }

    linha($oPdf, $oDados, true, $lDireita);
    continue;
  }


  if ($iIndice == 40) {

    $oPdf->ln();  
    $oPdf->cell(larguraColuna(30), 3, '', 0, 0, 'L');
    $oPdf->cell(larguraColuna(20), 3, '', 'L', 0, 'R');
    linha($oPdf, $oDados, true, $lDireita);
    continue;
  }

  linha($oPdf, $oDados, false, $lDireita);
}

$oPdf->SetLeftMargin(10);

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oVariacaoPatrimonial->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oVariacaoPatrimonial->getRelatorioContabil()->assinatura($oPdf, 'BG');

$oPdf->output();

/**
 * Imprime linha do relatorio
 * 
 * @param  PDF $oPdf    
 * @param  StdClass $oDados 
 * @param  integer $iColuna 
 * @return void          
 */
function linha(PDF $oPdf, StdClass $oDados = null, $lTotal = false, $lDireita = false) {

  $oPdf->setfont('arial', '', 6);
  
  $sDescricao  = null;
  $nValorAtual = null;

  $oPdf->ln();

  if ( !empty($oDados) ) {

    if ( isset($oDados->vlrexatual) ) {         

      $nValorAtual =  $oDados->vlrexatual;      

      if ( $oDados->vlrexatual !== '-') {            
        $nValorAtual = trim(db_formatar($oDados->vlrexatual, 'f'));
      }
    }
    
    if ( isset($oDados->totalizar) && $oDados->totalizar ) {
      $oPdf->setfont('arial', 'b', 6);    
    }
    
    $sDescricao = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  }

  if ($lTotal && $lDireita) {

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'TLB', 0, 'L');
    $oPdf->cell(larguraColuna(20), 3, $nValorAtual, 'TLB', 0, 'R');
    return;
  }

  if ($lTotal) {   

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'TRB', 0, 'L');
    $oPdf->cell(larguraColuna(20), 3, $nValorAtual, 'TRB', 0, 'R');
    return;
  }

  if ($lDireita) {

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 0, 0, 'L');
    $oPdf->cell(larguraColuna(20), 3, $nValorAtual, 'L', 0, 'R');
    return;    
  }

  $oPdf->cell(larguraColuna(30), 3, $sDescricao, 0, 0, 'L');
  $oPdf->cell(larguraColuna(20), 3, $nValorAtual, 'LR', 0, 'R');
}

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(100),4,"ART. 103 DA LEI 4.320/1964.", "T", 1, "L");

  $oPdf->cell(larguraColuna(30), 4, "VARIAÇÕES ATIVAS", 'TRB', 0, 'C');
  $oPdf->cell(larguraColuna(20), 4, "R$", 1, 0, 'C');    
  $oPdf->cell(larguraColuna(30), 4, "VARIAÇÕES PASSIVAS", 1, 0, 'C');
  $oPdf->cell(larguraColuna(20), 4, "R$", 'TBL', 0, 'C');    

  $oPdf->setfont('arial', '', 6);
}

/**
 * Largura da coluna 
 * 
 * @param string $sTipo 
 * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha   
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {   

  if ( $nPorcentagem == 0 ) {
    return LARGURA_PAGINA;
  }

  return round($nPorcentagem / 100 * LARGURA_PAGINA, 2);
}