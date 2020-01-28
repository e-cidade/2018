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

require_once("libs/db_utils.php");
require_once("fpdf151/pdfwebseller.php");
$oGet = db_utils::postMemory($_GET);
/**
 * Parametros para configuracao do relat�rio
 */
$oParametros                    = new stdClass();
$oParametros->iEscola           = $oGet->iEscola;
$oParametros->aListaCalendarios = explode(",", $oGet->aCalendarios);
$oParametros->iEtapa            = $oGet->iEtapa;

$oDaoSerie     = db_utils::getdao('serie');
$oDaoMatricula = db_utils::getdao('matricula');


/**
 * Altura padr�o das linhas
 */
$oParametros->iAlturaLinha = 4;

/**
 * For�a escrever o cabe�alho
 */
$oParametros->lEscreverCabecalho = true;

/**
 * Fonte padrao
 */
$oParametros->sFonte  = 'Arial';

$sWhere      = '';
$aFiltros    = array("ed47_i_transpublico = 1");
$sNomeEtapa  = "TODAS";
$sNomeEscola = "TODAS";
if (!empty($oParametros->iEscola)) {
  
  $aFiltros[]  = " ed57_i_escola = {$oParametros->iEscola}";
  $oEscola     = EscolaRepository::getEscolaByCodigo($oParametros->iEscola);
  $sNomeEscola = $oEscola->getNome();
} else {
  
}

if (count($oParametros->aListaCalendarios) > 0) {
  $aFiltros[] = " ed57_i_calendario in(".implode(',', $oParametros->aListaCalendarios).")";
}

if (!empty($oParametros->iEtapa)) {
  
  $aFiltros[] = "ed11_i_codigo = {$oParametros->iEtapa}";
  $oEtapa     = EtapaRepository::getEtapaByCodigo($oParametros->iEtapa);
  $sNomeEtapa = $oEtapa->getNome()." - ". $oEtapa->getEnsino()->getNome();
}

$aFiltros[] = "ed60_c_situacao = 'MATRICULADO'";

$sWhere = implode(' and ', $aFiltros);

$sCampos  = " ed47_v_nome,ed47_i_codigo,ed47_v_ender,ed47_c_numero,ed47_v_bairro,ed47_c_transporte, ";
$sCampos .= " ed47_c_zona,ed11_i_codigo,ed11_c_descr,ed52_c_descr,ed11_i_sequencia,ed11_i_ensino, ";
$sCampos .= " ed57_c_descr,ed57_i_codigo, ed18_c_nome,ed57_i_escola,";
$sCampos .= " (select array_to_string(array_accum(ed312_descricao), '/') ";
$sCampos .= "    from alunocensotipotransporte ";
$sCampos .= "         inner join censotipotransporte on ed311_censotipotransporte = ed312_sequencial ";
$sCampos .= "   where ed311_aluno = ed47_i_codigo) as transportes_utilizados";

$sOrder = " ed18_c_nome, ed11_i_ensino,ed11_i_sequencia,ed57_c_descr, ed47_v_nome ";

$sSqlMatricula    = $oDaoMatricula->sql_query_bolsafamilia("", $sCampos, $sOrder, $sWhere);
$rsMatricula      = $oDaoMatricula->sql_record($sSqlMatricula);
$iLinhasMatricula = $oDaoMatricula->numrows;
$aEscolas         = array();
for ($i = 0; $i < $iLinhasMatricula; $i++) {
  
  $oDadosMatricula = db_utils::fieldsMemory($rsMatricula, $i);
  
  if (!isset($aEscolas[$oDadosMatricula->ed57_i_escola])) {
    
    $oEscola                = new stdClass();
    $oEscola->iCodigoEscola = $oDadosMatricula->ed57_i_escola;
    $oEscola->sNomeEscola   = $oDadosMatricula->ed18_c_nome;
    $oEscola->aEtapas       = array();
    $aEscolas[$oEscola->iCodigoEscola] = $oEscola;
  }
  
  $oEscola = $aEscolas[$oDadosMatricula->ed57_i_escola];
  if (!isset($oEscola->aEtapas[$oDadosMatricula->ed11_i_codigo])) {
    
    $oEtapa               = new stdClass();
    $oEtapa->iCodigoEtapa = $oDadosMatricula->ed11_i_codigo;
    $oEtapa->iOrdemEtapa  = $oDadosMatricula->ed11_i_sequencia;
    $oEtapa->sNomeEtapa   = $oDadosMatricula->ed11_c_descr;
    $oEtapa->aAlunos      = array();
    $oEscola->aEtapas[$oDadosMatricula->ed11_i_codigo] = $oEtapa;
  }
  
  $oEtapa                       = $aEscolas[$oEscola->iCodigoEscola]->aEtapas[$oDadosMatricula->ed11_i_codigo];
  $oAluno                       =  new stdClass();
  $oAluno->sNome                = $oDadosMatricula->ed47_v_nome;
  $oAluno->sTurma               = $oDadosMatricula->ed57_c_descr;
  $oAluno->iCodigo              = $oDadosMatricula->ed47_i_codigo;
  $oAluno->sTipoTransporte      = '';
  $oAluno->sEndereco            = $oDadosMatricula->ed47_v_ender.", ".$oDadosMatricula->ed47_c_numero;
  $oAluno->sEndereco           .= " B: {$oDadosMatricula->ed47_v_bairro}";
  $oAluno->sTransporteUtilizado = $oDadosMatricula->transportes_utilizados;
  switch ($oDadosMatricula->ed47_c_transporte) {
    
    case '1':
      
      $oAluno->sTipoTransporte = "ESTADUAL";
      break;
    case '2':
    
      $oAluno->sTipoTransporte = "MUNICIPAL";
      break;
  }
  $oEtapa->aAlunos[] = $oAluno;
}
if ($iLinhasMatricula == 0) {
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$oPdf = new PDF();
$oPdf->AliasNbPages();
$oPdf->Open();
$oPdf->SetAutoPageBreak(false, 20);
$head1 = "RELAT�RIO DE ALUNOS COM TRANSPORTE ESCOLAR";
$head2 = "Escola: {$sNomeEscola}";
$head3 = "Etapa: {$sNomeEtapa}";
$head4 = "Ano: {$oGet->iAno}";
$oPdf->SetFillColor(240);
$iTotalGeral  = 0;
foreach ($aEscolas as $oEscola) {
  
  $oPdf->AddPage();
  $oPdf->SetFont($oParametros->sFonte, 'b', '7');
  $oPdf->Cell(192, $oParametros->iAlturaLinha, $oEscola->sNomeEscola, 1, 1, "L", 1);
  $iTotalEscola = 0;
  foreach ($oEscola->aEtapas as $oEtapa) {
    
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    
    if ($oPdf->GetY() + $oParametros->iAlturaLinha * 3 > $oPdf->h - 15) {
      $oPdf->AddPage();
    }
    $oPdf->Cell(192, $oParametros->iAlturaLinha, $oEtapa->sNomeEtapa, 1, 1, 'L', 1);
    $oParametros->lEscreverCabecalho = true;
    montaCabecalho($oPdf, $oParametros);
    foreach ($oEtapa->aAlunos as $oAluno) {
      
      $lAlturaExtra = $oAluno->sTransporteUtilizado != "" ? $oParametros->iAlturaLinha : 0;
      montaCabecalho($oPdf, $oParametros, $lAlturaExtra);
      $sBordaAluno = 'B';
      if ($oAluno->sTransporteUtilizado != "") {
        $sBordaAluno = '';
      }
      $oPdf->SetFont($oParametros->sFonte, '', '6');
      $oPdf->Cell(62, $oParametros->iAlturaLinha, $oAluno->sNome, "{$sBordaAluno}");
      $oPdf->Cell(20, $oParametros->iAlturaLinha, $oAluno->sTurma, "{$sBordaAluno}");
      $oPdf->Cell(90, $oParametros->iAlturaLinha, substr($oAluno->sEndereco, 0, 70), "{$sBordaAluno}");
      $oPdf->Cell(20, $oParametros->iAlturaLinha, $oAluno->sTipoTransporte,  "{$sBordaAluno}", 1);
      if ($oAluno->sTransporteUtilizado != "") {
        $oPdf->MultiCell(192, $oParametros->iAlturaLinha, "     ".$oAluno->sTransporteUtilizado, "B", 'L');
      }
    }
    
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    $iTotalEscola += count($oEtapa->aAlunos);
    $oPdf->cell(192, $oParametros->iAlturaLinha, "Total da Etapa: ".count($oEtapa->aAlunos), 1, 1, "L", 1);
    $oPdf->ln();
  }
  $iTotalGeral += $iTotalEscola;
  $oPdf->SetFont($oParametros->sFonte, 'b', '7');
  $oPdf->cell(192, $oParametros->iAlturaLinha, "Total da Escola: {$iTotalEscola}", 1, 1, "L", 1);
}
$oPdf->SetFont($oParametros->sFonte, 'b', '7');
$oPdf->cell(192, $oParametros->iAlturaLinha, "Total Geral: {$iTotalGeral}", 1, 1, "L", 1);
$oPdf->Output();

/**
 * monta o cabecalho do relatorio
 * @param PDF $oPdf instancia da fpdf
 * @param stdclass $oParametros parametros de configuracao
 * @param integer $iAlturaExtra altura extra a ser considerada na quebra de p�gina
 */
function montaCabecalho(PDF $oPdf, $oParametros, $iAlturaExtra = 0) {
  
  $lQuebrarPagina = $oPdf->GetY() + $iAlturaExtra > $oPdf->h - 15;
  if ($lQuebrarPagina || $oParametros->lEscreverCabecalho == true) {

    if ($lQuebrarPagina) {
      $oPdf->AddPage();
    }
    $oPdf->SetFillColor(240);
    $oPdf->SetFont($oParametros->sFonte, 'b', '7');
    $oPdf->cell(62,  $oParametros->iAlturaLinha, "Aluno", 1, 0, "L", 1);
    $oPdf->cell(15,  $oParametros->iAlturaLinha, "Turma", 1, 0, "C", 1);
    $oPdf->cell(90,  $oParametros->iAlturaLinha, "Endere�o", 1, 0, "C", 1);
    $oPdf->cell(25,  $oParametros->iAlturaLinha, "Respons�vel", 1, 1, "C", 1);
    
    $oParametros->lEscreverCabecalho = false;
  }
}
?>