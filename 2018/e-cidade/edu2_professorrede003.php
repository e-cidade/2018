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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBLargeObject.php");
require_once("std/DBDate.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("classes/db_cursoedu_classe.php");

$oGet = db_utils::postMemory($_GET);

$dtPeriodoInicial = "";
$dtPeriodoFinal   = "";
$sEscola          = "Todas";
$sEnsino          = "Todos";

/**
 * Valida os filtros passados pela interface
 */
$aFiltros = array();

$sDataFinalFiltroSaida = date('Y-m-d', db_getsession("DB_datausu"));

if (!empty($oGet->periodoInicial)) {

  $oData            = new DBDate($oGet->periodoInicial);
  $aFiltros[]       = "ed75_d_ingresso >= '" . $oData->getDate(DBDate::DATA_EN) . "'";
  $dtPeriodoInicial = $oData->getDate(DBDate::DATA_PTBR);
  unset($oData);
}

if (!empty($oGet->periodoFinal)) {

  $oData          = new DBDate($oGet->periodoFinal);
  $sDataFinalFiltroSaida = $oData->getDate(DBDate::DATA_EN);
  $aFiltros[]     = "ed75_d_ingresso <= '" . $oData->getDate(DBDate::DATA_EN) . "'";
  $dtPeriodoFinal = $oData->getDate(DBDate::DATA_PTBR);
  unset($oData);
}

if (!empty($oGet->iEscola) && $oGet->iEscola != 0) {

  $aFiltros[] = " ed18_i_codigo = {$oGet->iEscola}";
  $oEscola    = EscolaRepository::getEscolaByCodigo($oGet->iEscola);
  $sEscola    = $oEscola->getCodigo() . " - " . $oEscola->getNome();
  unset($oEscola);
}

if (!empty($oGet->iEnsino) && $oGet->iEnsino != 0) {

  $aFiltros[] = " ed10_i_codigo = ($oGet->iEnsino) ";
  $oEnsino    = new Ensino($oGet->iEnsino);
  $sEnsino    = $oEnsino->getNome();
  unset($oEnsino);
}

/*
 * Filtrando recursos humanos pela data de saida, solicitado pelo Tiago Silva
 * -- listar somente ativos do periodo selecionado.
 * -- caso nao informar o periodo, listar ate a data de hoje
 */
$aFiltros[] = " ( ed75_i_saidaescola > '{$sDataFinalFiltroSaida}' or ed75_i_saidaescola is null )";

/**
 * Constrói a clausula where
 */
$sWhere = "";
if (count($aFiltros) > 0) {

  $sWhere = "where ";
  $sWhere .= implode(" and ", $aFiltros);
}

/**
 * 
 */
$sSql  = " select distinct                                                                                            ";
$sSql .= "        ed18_i_codigo        as codigo_escola,                                                              ";
$sSql .= "        trim(ed18_c_nome)    as escola,                                                                     ";
$sSql .= "        ed10_i_codigo        as codigo_ensino,                                                              ";
$sSql .= "        trim(ed10_c_descr)   as ensino,                                                                     ";
$sSql .= "        ed12_i_codigo        as codigo_disciplina,                                                          ";
$sSql .= "        trim(ed232_c_descr)  as disciplina,                                                                 ";
$sSql .= "        ed75_i_rechumano     as matricula,                                                                  ";
$sSql .= "         (select cgm.z01_numcgm as cgm                                                                      ";
$sSql .= "            from rechumano                                                                                  ";
$sSql .= "           inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo      ";
$sSql .= "           inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
$sSql .= "           inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm        ";
$sSql .= "           where rechumano.ed20_i_codigo = ed75_i_rechumano                                                 ";
$sSql .= "         union all                                                                                          ";
$sSql .= "          select cgm.z01_numcgm                                                                             ";
$sSql .= "            from rechumano                                                                                  ";
$sSql .= "           inner join rechumanocgm  on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo         ";
$sSql .= "           inner join cgm           on cgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm           ";
$sSql .= "           where rechumano.ed20_i_codigo = ed75_i_rechumano                                                 ";
$sSql .= "         ) as cgm                                                                                           ";
$sSql .= "    from rechumanoescola                                                                                    ";
$sSql .= "         inner join relacaotrabalho on relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo ";
$sSql .= "         inner join areatrabalho    on areatrabalho.ed25_i_codigo             = relacaotrabalho.ed23_i_areatrabalho ";
$sSql .= "         inner join ensino          on ensino.ed10_i_codigo                   = areatrabalho.ed25_i_ensino    ";
$sSql .= "         inner join escola          on escola.ed18_i_codigo                   = rechumanoescola.ed75_i_escola ";
$sSql .= "         inner join disciplina      on disciplina.ed12_i_codigo               = relacaotrabalho.ed23_i_disciplina ";
$sSql .= "                                   and disciplina.ed12_i_ensino               = areatrabalho.ed25_i_ensino   ";
$sSql .= "         inner join caddisciplina   on disciplina.ed12_i_caddisciplina        = caddisciplina.ed232_i_codigo ";
$sSql .= "  {$sWhere}                                                                                                 ";
$sSql .= "   order by  ed18_i_codigo, ed10_i_codigo, ed12_i_codigo                                                     ";

$rsProfessorRede = db_query($sSql);
$iLinhas         = pg_num_rows($rsProfessorRede);

if ($iLinhas == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
}

$aProfessorRede = array();

/**
 * Montamos a estrutura dos dados indexando um arrayt mult-dimensional da seguinte forma:
 * Escola->iCodigo
 * Escola->sEscola
 * Escola->aEnsino[]
 *    aEnsino->iCodigo
 *    aEnsino->sEnsino
 *    aEnsino->aDisciplinas[]
 *       aDisciplina->iCodigo
 *       aDisciplina->sDisciplina
 *       aDisciplina->iTotal
 */

$aTotalProfessorRede   = array();
$aTotalProfessorEscola = array();
for ($i = 0; $i < $iLinhas; $i++) {

  $oDadosProfessor = db_utils::fieldsMemory($rsProfessorRede, $i);

  /**
   * Abaixo só reduzo o nome das variáveis para ficar melhor de trabalhar e não estourar o limite de colunas
  */
  $iEscola     = $oDadosProfessor->codigo_escola;
  $iEnsino     = $oDadosProfessor->codigo_ensino;
  $iDisciplina = $oDadosProfessor->codigo_disciplina;

  if ( !array_key_exists($iEscola, $aProfessorRede) ) {

    $oEscola          = new stdClass();
    $oEscola->iCodigo = $oDadosProfessor->codigo_escola;
    $oEscola->sEscola = $oDadosProfessor->escola;
    $oEscola->aEnsino = array();

    $aProfessorRede[$oDadosProfessor->codigo_escola] = $oEscola;
  }


  $iChaveEnsino = "{$iEscola}#{$iEnsino}";
  if ( !array_key_exists($iChaveEnsino, $aProfessorRede[$iEscola]->aEnsino) ) {

    $oEnsino               = new stdClass();
    $oEnsino->iCodigo      = $oDadosProfessor->codigo_ensino;
    $oEnsino->sEnsino      = $oDadosProfessor->ensino;
    $oEnsino->aDisciplinas = array();

    $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino] = $oEnsino;
  }


  $iChaveDisciplina = "{$iEscola}#{$iEnsino}#{$iDisciplina}";
  if ( !array_key_exists($iChaveDisciplina, $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino]->aDisciplinas) ) {

    $oDisciplina               = new stdClass();
    $oDisciplina->iCodigo      = $oDadosProfessor->codigo_disciplina;
    $oDisciplina->sDisciplina  = $oDadosProfessor->disciplina;
    $oDisciplina->iTotal       = 0;
    
    $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino]->aDisciplinas[$iChaveDisciplina] = $oDisciplina;
  }
  
  /**
   * Incrementa quantas matriculas de professores estão lecionando a disciplina 
   */
  $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino]->aDisciplinas[$iChaveDisciplina]->iTotal ++; 

  /**
   * Incrementa os arrays indexando sempre o cgm para que seja possivel contar os professores sem repetilos.
   * Um professor (igual um cgm) pode lecionar mais de uma disciplina, porem ao contar quantos professores há na escola
   * devemos levar em consideração somente a pessoa.
   */
  $aTotalProfessorEscola[$iEscola][$oDadosProfessor->cgm] = $oDadosProfessor->cgm;
  $aTotalProfessorRede[$oDadosProfessor->cgm]             = $oDadosProfessor->cgm;
}

/**
 * Ordena a disciplinas por ordem alfabética
 */
foreach ($aProfessorRede as $oEscola) {

  foreach ($oEscola->aEnsino as $oEnsino) {
    uasort($oEnsino->aDisciplinas, "ordernarDisciplinas");
  }
}

function ordernarDisciplinas($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual->sDisciplina, $aProximoArray->sDisciplina);
}

$head1 = "Professores da Rede";
$head2 = "Tipo: Sintético";
$head3 = "Período de: {$dtPeriodoInicial} até: {$dtPeriodoFinal}";
$head4 = "Escola: {$sEscola}";
$head5 = "Ensino: {$sEnsino}";
$head6 = "Disciplina: {$sDisciplina}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(225, 225, 225);
$oPdf->AddPage("P");

$iHeight = 4;

/**
 * Itera sobre os dados filtrados e organizados construindo a estrutura do relatório
 */
foreach ($aProfessorRede as $oEscola) {

  validaQuebraPagina($oPdf);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20, $iHeight, "Escola: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(165, $iHeight, $oEscola->sEscola, 0, 1, "L");

  foreach ($oEscola->aEnsino as $oEnsino) {

    validaQuebraPagina($oPdf);
    
    $oPdf->SetFont('arial', 'b', 8);
    $oPdf->Cell(20, $iHeight, "Ensino: ", 0, 0, "L");
    $oPdf->SetFont('arial', '', 7);
    $oPdf->Cell(165, $iHeight, $oEnsino->sEnsino, 0, 1, "L");

    $iContador = 0;
    $lPrimeiraPagina = true;
    foreach ($oEnsino->aDisciplinas as $oDisciplina) {

      if ($lPrimeiraPagina) {
        
        $lPrimeiraPagina = false;
        imprimeCabecalho($oPdf, $iHeight);
      }
      validaQuebraPagina($oPdf);
      $oPdf->SetFont('arial', '', 8);
      $oPdf->Cell(160, $iHeight, $oDisciplina->sDisciplina, "TBR", 0, "L");
      $oPdf->SetFont('arial', '', 7);
      $oPdf->Cell(30, $iHeight, $oDisciplina->iTotal, "TBL", 1, "R");
      $iContador += $oDisciplina->iTotal;
    }

    validaQuebraPagina($oPdf);
    $oPdf->SetFont('arial', 'b', 8);
    $oPdf->Cell(160, $iHeight, "Total", "TBR", 0, "R");
    $oPdf->Cell(30, $iHeight, $iContador, "TBL", 1, "R");
    $oPdf->Ln();
  }
  validaQuebraPagina($oPdf);
  $oPdf->SetFont('arial', 'b', 8);

  $sStringTotalizador = "Total de Professores na Escola: $oEscola->sEscola";
  if($oPdf->GetStringWidth($sStringTotalizador) > 160) {
    $oPdf->SetFont('arial', 'b', 7);
  }
  
  $oPdf->Cell(160, $iHeight, $sStringTotalizador, "TBR", 0, "R");
  $oPdf->Cell(30, $iHeight, count($aTotalProfessorEscola[$oEscola->iCodigo]), "TBL", 1, "R");
  $oPdf->Ln();
}

/**
 * Só imprime total da rede quanto imprimir todas escolas
 */
if ($oGet->iEscola == 0) {
  
  validaQuebraPagina($oPdf);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(160, $iHeight, "Total de Professores na Rede", "TBR", 0, "R");
  $oPdf->Cell(30, $iHeight, count($aTotalProfessorRede), "TBL", 1, "R");
}

/**
 * 
 * @param FPDF $oPdf
 * @param integer $iHeight
 */
function imprimeCabecalho(FPDF $oPdf, $iHeight) {

  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(160,  $iHeight, "Disciplina", 1, 0, "C", 1);
  $oPdf->Cell(30, $iHeight, "Nº Professores", 1, 1, "C", 1);
}

/**
 * Valida se deve ser quebrado pagina
 * @param FPDF $oPdf
 */
function validaQuebraPagina(FPDF $oPdf) {
  
  if ($oPdf->GetY() > $oPdf->h - 20) {
    $oPdf->AddPage("P");
  }
} 

$oPdf->Output();
