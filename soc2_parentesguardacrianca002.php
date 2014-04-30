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
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("std/DBDate.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");

$oGet          = db_utils::postMemory($_GET);

/**
 * Buscamos os dados de cidadaofamilia de quem tiver menos que 14 anos
 */
$oDaoCidadaoFamilia    = db_utils::getDao('cidadaofamilia');
$sCamposCidadaoFamilia = "DISTINCT as04_sequencial, ov02_sequencial, ov02_nome";
$sWhereCidadaoFamilia  = "date_part('year', age(current_date,  ov02_datanascimento)) <= 14";
$sWhereCidadaoFamilia .= " and as03_tipofamiliar = 14";
$sSqlCidadaoFamilia    = $oDaoCidadaoFamilia->sql_query_completa(null, 
                                                                 $sCamposCidadaoFamilia, 
                                                                 "ov02_nome",
                                                                 $sWhereCidadaoFamilia
                                                                );
$rsCidadaoFamilia      = $oDaoCidadaoFamilia->sql_record($sSqlCidadaoFamilia);
$iTotalCidadaoFamilia  = $oDaoCidadaoFamilia->numrows;

if ($iTotalCidadaoFamilia == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma família encontrada.");
}

$iTotalRegistros = 0;
$iHeigth         = 4;
$lPrimeiroLaco   = true;

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Relatório: Parentes que detem a guarda da criança";


if ($iTotalCidadaoFamilia > 0) {

  for($iContador = 0; $iContador < $iTotalCidadaoFamilia; $iContador++) {

    $oDadosFamilia = db_utils::fieldsMemory($rsCidadaoFamilia, $iContador);
  
    /**
     * Instanciamos Familia da criança
     */
    $oFamilia = new Familia($oDadosFamilia->as04_sequencial);
    
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
    
    /**
     * Imprimimos os dados no relatorio
     */
    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(20,  $iHeigth, "{$sNIS}",                                "TBR",  0);
    $oPdf->Cell(20,  $iHeigth, "{$iCodigoFamilia}",                      "TBRL", 0);
    $oPdf->Cell(60,  $iHeigth, "{$sResponsavel}",                        "TBRL", 0);
    $oPdf->Cell(60,  $iHeigth, substr($sEndereco, 0, 60),                "LTB",  0);
    $oPdf->Cell(30,  $iHeigth, substr($sBairro, 0, 30) ,                 "TBRL", 0);
    $oPdf->Cell(70,  $iHeigth, substr($oDadosFamilia->ov02_nome, 0, 50), "TBRL", 0);
    $oPdf->Cell(20,  $iHeigth, "Outros",                                 "TBRL", 1);
    $iTotalRegistros ++;
  }
  
  /**
   * Somamos o total de registros retornados
   */
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(260, $iHeigth, "Total de Registros:", "TBR",  0, "R");
  $oPdf->Cell(20,  $iHeigth, $iTotalRegistros,      "LTB",  1);
}

/**
 * Cabecalho das colunas
 */
function setHeader($oPdf, $iHeigth) {

  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20,  $iHeigth, "NIS",             1, 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Cód. Familiar",   1, 0, "C", 1);
  $oPdf->Cell(60,  $iHeigth, "Responsável",     1, 0, "C", 1);
  $oPdf->Cell(60,  $iHeigth, "Endereço",        1, 0, "C", 1);
  $oPdf->Cell(30,  $iHeigth, "Bairro",          1, 0, "C", 1);
  $oPdf->Cell(70,  $iHeigth, "Nome da Criança", 1, 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Parentesco",      1, 1, "C", 1);
}

$oPdf->Output();