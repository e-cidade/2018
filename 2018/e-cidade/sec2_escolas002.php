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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_benshistoricocalculo_classe.php");

$oDaoEscola = db_utils::getDao('escola');
$iAnoUsu    = db_getsession("DB_anousu");

$sCampos    = " ed18_i_codigo, ";
$sCampos   .= " ed18_c_nome, ";
$sCampos   .= " (SELECT Count(*) ";
$sCampos   .= "    FROM   matricula ";
$sCampos   .= "   INNER JOIN turma       ON ed57_i_codigo = ed60_i_turma ";
$sCampos   .= "   INNER JOIN calendario  ON ed52_i_codigo = ed57_i_calendario ";
$sCampos   .= "   WHERE  ed60_c_situacao = 'MATRICULADO' ";
$sCampos   .= "     AND ed57_i_escola = ed18_i_codigo ";
$sCampos   .= "     AND ed52_i_ano    = {$iAnoUsu}) AS total_alunos ";

$sSqlEscolas = $oDaoEscola->sql_query_file(null, $sCampos, "ed18_c_nome");
$rsEscolas   = $oDaoEscola->sql_record($sSqlEscolas);

if ($oDaoEscola->numrows == 0) {
  
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma escola cadastrada");
  exit;
}

$aEscolas      = db_utils::getColectionByRecord($rsEscolas);
$lPrimeiroLaco = true;
$iAltura       = 4;
$iTotalEscolas = 0;
$head2         = "Relatório de Escolas";
$head3         = "Ano de referência: {$iAnoUsu}";
$oPdf          = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);

/**
 * Iteramos sobre as escolas retornadas imprimindo os resultados
 */
foreach($aEscolas as $oEscola) {

  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
    $oPdf->addPage();
    imprimeCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }
  $oPdf->setfont("arial", "", 8);
  $oPdf->cell(30,  $iAltura, $oEscola->ed18_i_codigo, 0, 0, "R");
  $oPdf->cell(130, $iAltura, $oEscola->ed18_c_nome,   0, 0, "L");
  $oPdf->cell(33,  $iAltura, $oEscola->total_alunos,  0, 1, "R");
  $iTotalEscolas++;
}
$oPdf->setfont("arial", "B", 8);
$oPdf->cell(160, $iAltura, "Total de escolas cadastradas",  "BTR", 0, "R");
$oPdf->cell(33,  $iAltura, $iTotalEscolas,                  "BTL", 1, "C");

$oPdf->Output();

/**
 * Função para imprimir o cabecalho
 * @param FPDF $oPdf
 * @param integer $iAltura
*/
function imprimeCabecalho($oPdf, $iAltura) {

  $oPdf->setfont("arial", "b", 8);
  $oPdf->cell(30,  $iAltura, "Código",     "BRT", 0, "C", 1);
  $oPdf->cell(130, $iAltura, "Escola",         1, 0, "C", 1);
  $oPdf->cell(33,  $iAltura, "Matriculas", "BTL", 1, "C", 1);
}