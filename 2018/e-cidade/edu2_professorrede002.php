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
$sDisciplina      = "Todas";


/**
 * Valida os filtros passados pela interface
 */
$aFiltros = array();
if (!empty($oGet->periodoInicial)) {
  
  $oData            = new DBDate($oGet->periodoInicial);
  $aFiltros[]       = "ed75_d_ingresso >= '" . $oData->getDate(DBDate::DATA_EN) . "'";
  $dtPeriodoInicial = $oData->getDate(DBDate::DATA_PTBR);
  unset($oData);
}

if (!empty($oGet->periodoFinal)) {
  
  $oData          = new DBDate($oGet->periodoFinal);
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

if (!empty($oGet->iDisciplina) && $oGet->iDisciplina != 0) {
  
  $aFiltros[]  = "ed232_i_codigo = {$oGet->iDisciplina} ";
  $sDisciplina = $oGet->sDisciplina; 
}


/**
 * Constrói a clausula where 
 */
$sWhere = "";
if (count($aFiltros) > 0) {
  
  $sWhere = "where ";
  $sWhere .= implode(" and ", $aFiltros);
}

$sSql  = " select ed18_i_codigo        as codigo_escola,                                                              ";
$sSql .= "        trim(ed18_c_nome)    as escola,                                                                     ";
$sSql .= "        ed10_i_codigo        as codigo_ensino,                                                              ";
$sSql .= "        trim(ed10_c_descr)   as ensino,                                                                     ";
$sSql .= "        ed12_i_codigo        as codigo_disciplina,                                                          ";
$sSql .= "        trim(ed232_c_descr)  as disciplina,                                                                 ";
$sSql .= "        ed75_i_rechumano     as matricula,                                                                  ";
$sSql .= "        ed75_d_ingresso      as dt_ingresso,                                                                ";
$sSql .= "        (select cgm.z01_numcgm as cgm                                                                       ";
$sSql .= "           from rechumano                                                                                   ";
$sSql .= "          inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo             ";
$sSql .= "          inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal  ";
$sSql .= "          inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm               ";
$sSql .= "          where rechumano.ed20_i_codigo = ed75_i_rechumano                                                  ";
$sSql .= "         union all                                                                                          ";
$sSql .= "         select cgm.z01_numcgm                                                                              ";
$sSql .= "           from rechumano                                                                                   ";
$sSql .= "          inner join rechumanocgm  on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo          ";
$sSql .= "          inner join cgm           on cgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm            ";
$sSql .= "          where rechumano.ed20_i_codigo = ed75_i_rechumano                                                  ";
$sSql .= "        ) as cgm,                                                                                           ";
$sSql .= "        (select array_to_string(array_accum(ed11_c_descr), ', ')                                            ";
$sSql .= "           from (select distinct ed11_i_ensino, ed11_i_sequencia, trim(ed11_c_descr) as ed11_c_descr        ";
$sSql .= "                   from regencia                                                                            ";
$sSql .= "                      inner join turma           on turma.ed57_i_codigo              = regencia.ed59_i_turma ";
$sSql .= "                      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo        ";
$sSql .= "                      inner join seriecensoetapa on (seriecensoetapa.ed133_censoetapa = turmacensoetapa.ed132_censoetapa  ";
$sSql .= "                                                      and seriecensoetapa.ed133_ano = turmacensoetapa.ed132_ano) ";
$sSql .= "                      inner join serie on serie.ed11_i_codigo = seriecensoetapa.ed133_serie                 ";
$sSql .= "                      where ed59_i_disciplina = ed12_i_codigo                                               ";
$sSql .= "                        and ed57_i_escola     = ed18_i_codigo                                               ";
$sSql .= "                        and exists (select 1                                                                ";
$sSql .= "                                      from regenciahorario                                                  ";
$sSql .= "                                     where regenciahorario.ed58_i_regencia  = regencia.ed59_i_codigo        ";
$sSql .= "                                       and regenciahorario.ed58_i_rechumano = ed75_i_rechumano)             ";
$sSql .= "                     order by ed11_i_ensino, ed11_i_sequencia                                               ";
$sSql .= "                ) as x                                                                                      ";
$sSql .= "        ) as etapas                                                                                         ";
$sSql .= "    from rechumanoescola                                                                                    ";
$sSql .= "         inner join relacaotrabalho on relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo ";
$sSql .= "         inner join areatrabalho    on areatrabalho.ed25_i_codigo             = relacaotrabalho.ed23_i_areatrabalho ";
$sSql .= "         inner join ensino          on ensino.ed10_i_codigo                   = areatrabalho.ed25_i_ensino    ";
$sSql .= "         inner join escola          on escola.ed18_i_codigo                   = rechumanoescola.ed75_i_escola ";
$sSql .= "         inner join disciplina      on disciplina.ed12_i_codigo               = relacaotrabalho.ed23_i_disciplina ";
$sSql .= "                                   and disciplina.ed12_i_ensino               = areatrabalho.ed25_i_ensino   ";
$sSql .= "         inner join caddisciplina   on disciplina.ed12_i_caddisciplina        = caddisciplina.ed232_i_codigo ";
$sSql .= " {$sWhere}                                                                                                   ";
$sSql .= "order by  ed18_i_codigo, ed10_i_codigo, ed12_i_codigo";

$rsProfessorRede = db_query($sSql);
$iLinhas         = pg_num_rows($rsProfessorRede);

if ($iLinhas == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
}

$aProfessorRede = array();


/**
 * Montamos a estrutura dos dados indexando um array mult-dimensional da seguinte forma:
 * Escola->iCodigo
 * Escola->sEscola
 * Escola->aEnsino[]
 *    aEnsino->iCodigo
 *    aEnsino->sEnsino
 *    aEnsino->aDisciplinas[]
 *       aDisciplina->iCodigo     
 *       aDisciplina->sDisciplina 
 *       aDisciplina->aProfessores[]
 *          aProfessores->iMatricula               
 *          aProfessores->dtIngresso               
 *          aProfessores->sNome                    
 *          aProfessores->sEtapas                  
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
    
    $aProfessorRede[$iEscola] = $oEscola; 
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
    $oDisciplina->aProfessores = array();
    
    $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino]->aDisciplinas[$iChaveDisciplina] = $oDisciplina;
  }
  
  $oCgm = CgmFactory::getInstanceByCgm($oDadosProfessor->cgm);
  
  $oProfessor = new stdClass();
  $oProfessor->iMatricula = $oDadosProfessor->matricula;
  $oProfessor->iCgm       = $oDadosProfessor->cgm;
  $oProfessor->dtIngresso = db_formatar($oDadosProfessor->dt_ingresso, 'd');
  $oProfessor->sNome      = $oCgm->getNome();
  $oProfessor->sEtapas    = $oDadosProfessor->etapas;
  
  unset($oCgm);
  $aProfessorRede[$iEscola]->aEnsino[$iChaveEnsino]->aDisciplinas[$iChaveDisciplina]->aProfessores[] = $oProfessor;
  
  $aTotalProfessorEscola[$iEscola][$oDadosProfessor->cgm] = $oDadosProfessor->cgm;
  $aTotalProfessorRede[$oDadosProfessor->cgm]             = $oDadosProfessor->cgm;
}

/**
 * Ordena os professores por ordem alfabética
 */
foreach ($aProfessorRede as $oEscola) {
  
  foreach ($oEscola->aEnsino as $oEnsino) {
    
    foreach ($oEnsino->aDisciplinas as $oDisciplina) {
      uasort($oDisciplina->aProfessores, "ordernarProfessores");
    }
  }
}

function ordernarProfessores($aArrayAtual, $aProximoArray) {
  return strcasecmp($aArrayAtual->sNome, $aProximoArray->sNome);
}


$head1 = "Professores da Rede";
$head2 = "Tipo: Analítico";
$head3 = "Período de: {$dtPeriodoInicial} até: {$dtPeriodoFinal}";
$head4 = "Escola: {$sEscola}";
$head5 = "Ensino: {$sEnsino}";
$head6 = "Disciplina: {$sDisciplina}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(225, 225, 225);
$oPdf->AddPage("L");

$iHeight = 4;

/**
 * Itera sobre os dados filtrados e organizados construindo a estrutura do relatório
 */
foreach ($aProfessorRede as $oEscola) {
  
  validaQuebraPagina($oPdf);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20, $iHeight, "Escola: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(255, $iHeight, $oEscola->sEscola, 0, 1, "L");
  
  foreach ($oEscola->aEnsino as $oEnsino) {

    validaQuebraPagina($oPdf);
    
    $oPdf->SetFont('arial', 'b', 8);
    $oPdf->Cell(20, $iHeight, "Ensino: ", 0, 0, "L");
    $oPdf->SetFont('arial', '', 7);
    $oPdf->Cell(255, $iHeight, $oEnsino->sEnsino, 0, 1, "L");
    
    foreach ($oEnsino->aDisciplinas as $oDisciplina) {

      validaQuebraPagina($oPdf);
      
      $oPdf->SetFont('arial', 'b', 8);
      $oPdf->Cell(20, $iHeight, "Disciplina: ", 0, 0, "L");
      $oPdf->SetFont('arial', '', 7);
      $oPdf->Cell(255, $iHeight, $oDisciplina->sDisciplina, 0, 1, "L");
      
      $iContProfessores  = 0;
      $lImprimeCabecalho = true;
      
      foreach ($oDisciplina->aProfessores as $oProfessor) {
        
        $iContProfessores++;
          
        if ($lImprimeCabecalho) {
          
          $lImprimeCabecalho = false;
          imprimeCabecalho($oPdf, $iHeight);
        }
        validaQuebraPagina($oPdf);
        
        $sRegencia = "Não";
        if (!empty($oProfessor->sEtapas)) {
          $sRegencia = "Sim";
        }
        
        $oPdf->SetFont('arial', '', 7);
        
        $iAlturaInicial = $oPdf->GetY();
        
        $oPdf->SetX(145);
        $oPdf->MultiCell(100, $iHeight, $oProfessor->sEtapas, 1, "L", 0, 0);
        $iAlturaFinal = $oPdf->GetY();
        
        $oPdf->SetX(10);
        $oPdf->SetY($iAlturaInicial);
        
        $oPdf->Cell(20,  ($iAlturaFinal - $iAlturaInicial), $oProfessor->iMatricula, "BTR", 0, "C"); 
        $oPdf->Cell(115, ($iAlturaFinal - $iAlturaInicial), $oProfessor->sNome, 1, 0, "L");
        $oPdf->SetX(245);
        $oPdf->Cell(25,  ($iAlturaFinal - $iAlturaInicial), $sRegencia, 1, 0, "C");
        $oPdf->Cell(20,  ($iAlturaFinal - $iAlturaInicial), $oProfessor->dtIngresso, "BTL", 0, "C");
        $oPdf->Ln();
        
      }
      validaQuebraPagina($oPdf);
      $oPdf->SetFont('arial', 'b', 8);
      $oPdf->Cell(260, $iHeight, "Total de Professores em {$oDisciplina->sDisciplina}", "BTR", 0, "R");
      $oPdf->Cell(20,  $iHeight, "{$iContProfessores}", "BTL", 1, "R");
      $oPdf->Ln();
    }
  }
  
  validaQuebraPagina($oPdf);
  $oPdf->SetFont('arial', 'b', 8);
  
  $sStringTotalizador = "Total de Professores na Escola: $oEscola->sEscola";
  if($oPdf->GetStringWidth($sStringTotalizador) > 260) {
    $oPdf->SetFont('arial', 'b', 7);
  }
  validaQuebraPagina($oPdf);
  $oPdf->Cell(260, $iHeight, $sStringTotalizador, "TBR", 0, "R");
  $oPdf->Cell(20,  $iHeight, count($aTotalProfessorEscola[$oEscola->iCodigo]), "BTL", 1, "R");
  $oPdf->Ln();
}

/**
 * Só imprime total da rede quanto imprimir todas escolas
 */
if ($oGet->iEscola == 0) {
  
  validaQuebraPagina($oPdf);
  $oPdf->Cell(260, $iHeight, "Total geral de Professores na Rede", "BTR", 0, "R");
  $oPdf->Cell(20,  $iHeight, count($aTotalProfessorRede), "BTL", 1, "R");
}

/**
 * Imprime cabeçalho
 * @param FPDF $oPdf
 * @param integer $iHeight
 */
function imprimeCabecalho(FPDF $oPdf, $iHeight) {
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20,  $iHeight, "Matrícula", 1, 0, "C", 1);
  $oPdf->Cell(115, $iHeight, "Professor", 1, 0, "C", 1);
  $oPdf->Cell(100, $iHeight, "Etapa", 1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeight, "Em Regência", 1, 0, "C", 1);
  $oPdf->Cell(20,  $iHeight, "Data Ingresso", 1, 1, "C", 1);
}

/**
 * Valida se deve ser quebrado pagina
 * @param FPDF $oPdf
 */
function validaQuebraPagina(FPDF $oPdf) {

  if ($oPdf->GetY() > $oPdf->h - 20) {
    $oPdf->AddPage("L");
  }
}

$oPdf->Output();