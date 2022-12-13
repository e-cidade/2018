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
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");

$oFiltros = db_utils::postMemory($_GET);

/**
 * Array para apresentar no cabecalho padrao do PDF os filtros selecionados 
 */
$aCabecalho = array();
$aWhere     = array();
$sWhere     = "";

/**
 * Verificamos a origem da chamado ao relatorio para montar o where
 * Caso 'relatorio': monta o where e o relatorio de acordo com os filtros preenchidos
 * Caso 'procedimento': monta o where e o relatorio apenas com os dados da inclusao/alteracao
 */
if ($oFiltros->sOrigem == 'relatorio') {
  
  if (!empty($oFiltros->sDataInicial)) {
    
    $oDataInicial = new DBDate($oFiltros->sDataInicial);
    $dtInicial    = $oDataInicial->convertTo(DBDate::DATA_EN);
    
    $aWhere[]     = " as05_datavisita >= '{$dtInicial}' ";
    $aCabecalho[] = "  - Data Inicial: {$oFiltros->sDataInicial}";
  }
  
  if (!empty($oFiltros->sDataFinal)) {
  
    $oDataFinal = new DBDate($oFiltros->sDataFinal);
    $dtFinal    = $oDataFinal->convertTo(DBDate::DATA_EN);
  
    $aWhere[]     = "as05_datavisita <= '{$dtFinal}'";
    $aCabecalho[] = "  - Data Final: {$oFiltros->sDataFinal}";
  }
  
  if (!empty($oFiltros->iCodigoCidadao)) {
    
    $aWhere[]     = " as03_cidadao = {$oFiltros->iCodigoCidadao} ";
    $aCabecalho[] = "  - Código Cidadão: {$oFiltros->iCodigoCidadao}";
  }
  
  if (isset($oFiltros->iFamilia) && !empty($oFiltros->iFamilia)) {
    
    $aWhere[]     = " as05_cidadaofamilia = {$oFiltros->iFamilia} ";
    $aCabecalho[] = "  - Código da Família: {$oFiltros->iFamilia}";
  }
} else {
  
  if (!empty($oFiltros->iCodigoVisita)) {
    
    $aWhere[]     = " as05_sequencial = {$oFiltros->iCodigoVisita} ";
    $aCabecalho[] = "  - Código da Visita: {$oFiltros->iCodigoVisita}";
  }
}

$sWhere     = implode(" and ", $aWhere);
$sCabecalho = implode("\n", $aCabecalho);

if (empty($sCabecalho)) {
  $sCabecalho = "  - Nenhum filtro selecionado.";
}

/**
 * Buscamos os registros encontrados de acordo com os filtros preenchidos. Caso não tenha sido preenchido nenhum,
 * buscamos todos os dados
 */
$oDaoCidadaoFamiliaVisita     = db_utils::getDao("cidadaofamiliavisita");
$sCamposCidadaoFamiliaVisita  = "distinct as05_sequencial, as05_cidadaofamilia, as05_datavisita, as05_horavisita, z01_nome";
$sCamposCidadaoFamiliaVisita .= ", as10_data, as05_observacao";
$sSqlCidadaoFamiliaVisita     = $oDaoCidadaoFamiliaVisita->sql_query_visita_contato(null, 
                                                                                    $sCamposCidadaoFamiliaVisita, 
                                                                                    "as05_sequencial", 
                                                                                    $sWhere
                                                                                   );
$rsCidadaoFamiliaVisita      = $oDaoCidadaoFamiliaVisita->sql_record($sSqlCidadaoFamiliaVisita);
$iLinhasCidadaoFamiliaVisita = $oDaoCidadaoFamiliaVisita->numrows;

/**
 * Validamos se retornou algum resultado
 */
if ($iLinhasCidadaoFamiliaVisita == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}

/**
 * Instanciamos PDF
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);

$iAltura = 5;

/**
 * Montamos os dados do cabecalho do PDF
 */
$head1 = "Relatório de Visita";
$head2 = "Filtros: ";
$head3 = $sCabecalho;

/**
 * Percorrremos o total de registros encontrados para montar o relatorio. Sera apresentado um registro por pagina
 */
for ($iContador = 0; $iContador < $iLinhasCidadaoFamiliaVisita; $iContador++) {
  
  $oDadosRelatorio = db_utils::fieldsMemory($rsCidadaoFamiliaVisita, $iContador);
  
  /**
   * Verificamos se foi gravada a hora da visita, para concatenar data e hora
   */
  $sSeparadorDataHora = "";
  if (!empty($oDadosRelatorio->as05_horavisita)) {
    $sSeparadorDataHora = "-";
  }
  
  /**
   * Convertemos o formato da data para o padrao PTBR
   */
  $oData  = new DBDate($oDadosRelatorio->as05_datavisita);
  $dtData = $oData->convertTo(DBDate::DATA_PTBR);
  $sDataHora = "{$dtData} {$sSeparadorDataHora} {$oDadosRelatorio->as05_horavisita}";
  
  /**
   * Convertemos o formato da data do contato para o padrao PTBR
   */
  $dtContato = 'Não houve contato por telefone.';
  if (!empty($oDadosRelatorio->as10_data)) {
    
    $oDataContato = new DBDate($oDadosRelatorio->as10_data);
    $dtContato    = $oDataContato->convertTo(DBDate::DATA_PTBR);
  }
  
  /**
   * Buscamos o nome do responsavel
   */
  $oFamilia         = new Familia($oDadosRelatorio->as05_cidadaofamilia);
  $sNomeResponsavel = $oFamilia->getResponsavel()->getNome();
  
  /**
   * Imprimimos os dados do relatorio
   */
  $oPdf->AddPage();
  
  $oPdf->SetFillColor(215);
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(40, $iAltura, "Código do Responsável: ", "LTB", 0, "L", 1);
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(14, $iAltura, $oDadosRelatorio->as05_cidadaofamilia, "TBR", 0, "C", 1);
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(36, $iAltura, "Nome do Responsável: ", "LTB", 0, "L", 1);
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(102, $iAltura, $sNomeResponsavel, "TBR", 1, "L", 1);
  
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(40, $iAltura, "Código da Visita: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(14, $iAltura, $oDadosRelatorio->as05_sequencial, 0, 0, "C");
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(18, $iAltura, "Data/Hora: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(50, $iAltura, $sDataHora, 0, 1, "L");
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(20, $iAltura, "Profissional: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(100, $iAltura, $oDadosRelatorio->z01_nome, 0, 1, "L");
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(40, $iAltura, "Contato por Telefone: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(100, $iAltura, $dtContato, 0, 1, "L");
  $oPdf->SetFont('arial', 'b', 9);
  $oPdf->Cell(18, $iAltura, "Histórico: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 8);
  $oPdf->Cell(100, $iAltura, $oDadosRelatorio->as05_observacao, 0, 1, "L");
}

$oPdf->Output();
?>