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

require_once ("fpdf151/pdf.php");
require_once ("std/DBDate.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("libs/exceptions/DBException.php");
/*
relatorio dos cursos sociais
 filtros 
  - periodo
  - tipo de curso
  - curso 
 
 relatório
  - quebra por curso, colocando no inicio de cada pagina a descrição do curso , tipo e o periodo.
    Ex: Oficina de Bonecos - Tipo: Oficina - Periodo: de 21/05/2013 a 31/05/2013
  - listar abaixo o nome de todas as pessoas que participaram do curso.
  - totalizar ao final de cada curso
*/

$oFiltros = db_utils::postMemory($_GET);

$sPeriodoInicial = "";
$sPeriodoFinal   = "";
$sTipoCurso      = "";
$lCurso          = false;

$aWhere = array();
if (!empty($oFiltros->sDataInicial)) {
  
  $oDataInicial    = new DBDate($oFiltros->sDataInicial);
  $aWhere[]        = " as19_inicio >= '" . $oDataInicial->convertTo(DBDate::DATA_EN) . "'";
  $sPeriodoInicial = $oDataInicial->convertTo(DBDate::DATA_PTBR);
  unset($oDataInicial);
}

if (!empty($oFiltros->sDataFinal)) {
  
  $oDataFinal    = new DBDate($oFiltros->sDataFinal);
  $aWhere[]      = " as19_fim <= '" . $oDataFinal->convertTo(DBDate::DATA_EN) . "'";
  $sPeriodoFinal = $oDataFinal->convertTo(DBDate::DATA_PTBR);
  unset($oDataFinal);
}

if (!empty($oFiltros->iTipoCurso)) {
  
  $aWhere[]         = "as19_tabcurritipo = {$oFiltros->iTipoCurso}";
  $oDaoTabCurriTipo = new cl_tabcurritipo();
  $rsTabCurriTipo   = $oDaoTabCurriTipo->sql_record($oDaoTabCurriTipo->sql_query_file($oFiltros->iTipoCurso, "h02_descr"));
  $sTipoCurso       = db_utils::fieldsMemory($rsTabCurriTipo, 0)->h02_descr;
}

if (!empty($oFiltros->iCurso)) {
  
  $aWhere[] = "as19_sequencial = {$oFiltros->iCurso}";
  $lCurso   = true;
}

$sWhere     = implode(" and ", $aWhere);
$oDaoCurso  = new cl_cursosocial();
$sSqlCursos = $oDaoCurso->sql_query_file(null, "as19_sequencial", null, $sWhere);
$rsCursos   = $oDaoCurso->sql_record($sSqlCursos);
$iLinhas    = $oDaoCurso->numrows;

if ($iLinhas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para os filtros selecionados.');
}

$aCursos = array();
for ($i = 0; $i < $iLinhas; $i++) {
  
  $oCursoSocial = new CursoSocial(db_utils::fieldsMemory($rsCursos, $i)->as19_sequencial);
  $aCursos[]    = $oCursoSocial;  
}


/**
 * Instanciamos PDF
 */
$oPdf = new PDF("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(215);
$iHeight = 4;

/**
 * Montamos os dados do cabecalho do PDF
 */
$head1 = "Relatório de Cursos / Oficina";
$head2 = "Filtros: ";
$head3 = "Período de: - ";

if (!empty($sPeriodoInicial)) {
  $head3 = "Período de: $sPeriodoInicial";
}

if (!empty($sPeriodoFinal)) {
  $head3 .= " até: $sPeriodoFinal";
} 
$head4 = "Tipo: ";
if (!empty($sTipoCurso)) {
  $head4 .= " {$sTipoCurso}"; 
}


foreach ($aCursos as $oCursoSocial) {

  setHeader($oPdf, $iHeight, $oCursoSocial);
  
  $iTotalCidadaoMatriculado = "0";
  $lPrimeiraPagina          = true;
  foreach ($oCursoSocial->getCidadaosMatriculados() as $oCidadaoMatriculado) {
    
     if ($oPdf->gety() > $oPdf->h - 15) {
       
      setHeader($oPdf, $iHeight, $oCursoSocial);
      $lPrimeiraPagina = true;
    }
    if ($lPrimeiraPagina) {
      
      $oPdf->SetFont('arial', 'b', 8);
      $oPdf->cell(192, $iHeight, "Nome Cidadão ", "TB", 1, "C", 1);
      $lPrimeiraPagina = false;
    }
    
    $oPdf->SetFont('arial', '', 7);
    $oPdf->cell(192, $iHeight, $oCidadaoMatriculado->getCidadao()->getNome(), "TB", 1, "L");
    $iTotalCidadaoMatriculado ++;
  }
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->cell(172, $iHeight, "Total de Cidadãos Matriculados: ", "TBR", 0, "R");
  $oPdf->cell(20,  $iHeight, $iTotalCidadaoMatriculado, "TLB", 1, "R");

}

/**
 * Adiciona o cabeçalho
 * @param FPDF $oPdf
 * @param integer $iHeight
 * @param CursoSocial $oCursoSocial
 */
function setHeader(PDF $oPdf, $iHeight, CursoSocial $oCursoSocial) {
  
  $oPdf->AddPage();
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->cell(20, $iHeight, "Curso: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->cell(160, $iHeight, $oCursoSocial->getNome(), 0, 1, "L");
  
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->cell(20, $iHeight, "Tipo: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->cell(160, $iHeight, $oCursoSocial->getCategoria()->getDescricao(), 0, 1, "L");
  
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->cell(20, $iHeight, "Período: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $sPeriodo  = $oCursoSocial->getDataInicio()->convertTo(DBDate::DATA_PTBR) . " até ";
  $sPeriodo .= $oCursoSocial->getDataFim()->convertTo(DBDate::DATA_PTBR);
  $oPdf->cell(160, $iHeight, $sPeriodo, 0, 1, "L");
  
}

$oPdf->Output();