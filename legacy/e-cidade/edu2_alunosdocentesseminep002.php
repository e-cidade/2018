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
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$aListaAlunos  = array();
$aListaDocente = array();
$iAno          = $oGet->ano;
$sEscolas      = $oGet->sEscola;
/**
 * Todos os tipos, ou somente alunos
 */
if ($oGet->tipo == 1 || $oGet->tipo == 3) {
  
  $oDaoAluno = db_utils::getDao("aluno");
  $sCampos        = " ed47_i_codigo as codigo,";
  $sCampos       .= " to_char(ed47_d_nasc, 'dd/mm/yyyy') as datanascimento,";
  $sCampos       .= " ed47_v_nome as nomealuno,";
  $sCampos       .= " ed47_v_pai as nomepaialuno, ";
  $sCampos       .= " ed47_v_mae as nomemaealuno,";
  $sCampos       .= " ed47_i_codigo as codigoalunoinep, ";
  $sCampos       .= " ed261_c_nome as municipionascimento,";
  $sCampos       .= " ed260_c_sigla as ufnascimento,";
  $sCampos       .= " ed47_c_codigoinep as idalunoinep";
  $sWhere         = "     ed47_c_codigoinep = '' ";
  $sWhere        .= " and ed18_i_codigo in({$sEscolas})";
  $sWhere        .= " and ed52_i_ano    = {$iAno}";
  $sWhere        .= " and ed60_c_situacao ='MATRICULADO' ";
  $sSqlDadosAluno = $oDaoAluno->sql_query_censo_inep(null, $sCampos, "ed47_i_codigo", $sWhere); 
  $rsDadosAluno   = $oDaoAluno->sql_record($sSqlDadosAluno);
  $iLinhas        = $oDaoAluno->numrows;
  for ($iContador = 0; $iContador < $iLinhas; $iContador++) {
    $aListaAlunos[] = db_utils::fieldsMemory($rsDadosAluno, $iContador); 
  }
}
/**
 * Todos os tipos, ou somente Docentes
 */
if ($oGet->tipo == 1 || $oGet->tipo == 2) {

  $oDaoRecHumano    = db_utils::getdao("rechumano");
  $sCampos          = "distinct z01_numcgm as codigo, ";
  $sCampos         .= "to_char(z01_nasc, 'dd/mm/yyyy') as datadenascimento, ";
  $sCampos         .= "z01_mae    as nomemaedocente, ";
  $sCampos         .= "z01_cgccpf as numerocpf, ";
  $sCampos         .= "censomunicender.ed261_c_nome  as municipionascimento, ";
  $sCampos         .= "censoufnat.ed260_c_sigla as ufnascimento,";
  $sCampos         .= "z01_nome as nomedocente, ";
  $sCampos         .= "ed20_i_codigo as codigodocenteescola,";
  $sCampos         .= "ed20_i_codigoinep as idinep";
  
  $sWhere           = " ed20_i_codigoinep is null ";
  $sWhere          .= " and ed52_i_ano = {$iAno} ";
  $sWhere          .= " and ed01_c_regencia = 'S' ";
  $sWhere          .= " and  ed75_i_escola  in({$sEscolas})";
  $sSqlDadosDocente = $oDaoRecHumano->sql_query_solicitaseminep("", $sCampos, "", $sWhere);
  $rsDadosDocente   = $oDaoRecHumano->sql_record($sSqlDadosDocente);
  $iLinhas          = $oDaoRecHumano->numrows;
  
  /**
   * Agrupamos os dados do docente por codigo de CGM.
   */
  for ($iContador = 0; $iContador < $iLinhas; $iContador++) {
    
    $oDadosDocente = db_utils::fieldsMemory($rsDadosDocente, $iContador);
    if (!isset($aListaDocente[$oDadosDocente->codigo])) {
      $aListaDocente[$oDadosDocente->codigo] = $oDadosDocente;
    }
  }
}
$oPdf    = new Pdf("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
if (count($aListaAlunos) == 0 && count($aListaDocente) == 0) {
  
  /**
   * @todo criar mensgaem de erro quando nao existir registros.
   */
  db_redireciona('db_erro.php?erro="Sem dados para gerar relatorio');
}
$lPrimeiraImpressao = true;
$iAlturaLinha       = 4;

foreach ($aListaAlunos as $oAluno) {
  
  if ($oPdf->GetY() > $oPdf->h - 25 || $lPrimeiraImpressao) {
    
     $head1 = 'Listagem de Alunos sem Código INEP';
     $oPdf->AddPage();
     $oPdf->SetFillColor('240');
     $oPdf->setfont('arial', 'b', 7);
     $oPdf->cell(20, $iAlturaLinha, 'Código', "TBR", 0, "C", 1);
     $oPdf->cell(70, $iAlturaLinha, 'Aluno', 1, 0, "C", 1);
     $oPdf->cell(20, $iAlturaLinha, 'Nascimento', 1, 0, "C", 1);
     $oPdf->cell(60, $iAlturaLinha, 'Mãe', 1, 0, "C", 1);
     $oPdf->cell(60, $iAlturaLinha, 'Pai', 1, 0, "C", 1);          
     $oPdf->cell(40, $iAlturaLinha, 'Naturalidade', 1, 0, "C", 1);
     $oPdf->cell(8, $iAlturaLinha, 'UF', "TBL", 1, "C", 1);
     $lPrimeiraImpressao = false;    
  }
  
  $oPdf->setfont('arial', '', 6);  
  $oPdf->cell(20, $iAlturaLinha, $oAluno->codigo, "TBR", 0, "R");
  $oPdf->cell(70, $iAlturaLinha, $oAluno->nomealuno, 1, 0, "L");
  $oPdf->cell(20, $iAlturaLinha, $oAluno->datanascimento, 1, 0, "C");
  $oPdf->cell(60, $iAlturaLinha, $oAluno->nomemaealuno, 1, 0, "L");
  $oPdf->cell(60, $iAlturaLinha, $oAluno->nomepaialuno, 1, 0, "L");    
  $oPdf->cell(40, $iAlturaLinha, $oAluno->municipionascimento, 1, 0, "L");
  $oPdf->cell(8, $iAlturaLinha, $oAluno->ufnascimento, "TBL", 1, "L");
  
}
if (count($aListaAlunos) > 0) {
  
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(230, $iAlturaLinha, "Total de Alunos", "TBR", 0, "R");
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(48, $iAlturaLinha, count($aListaAlunos), "TBL", 0 ,"R");
}
$lPrimeiraImpressao = true;  
foreach ($aListaDocente as $oDocente) {
  
  $head1 = 'Listagem de Docentes sem Código INEP';
  if ($oPdf->GetY() > $oPdf->h - 25 || $lPrimeiraImpressao) {
  
    $oPdf->AddPage();
    $oPdf->SetFillColor('240');
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(20, $iAlturaLinha, 'Código', "TBR", 0, "C", 1);
    $oPdf->cell(90, $iAlturaLinha, 'Docente', 1, 0, "C", 1);
    $oPdf->cell(20, $iAlturaLinha, 'Nascimento', 1, 0, "C", 1);
    $oPdf->cell(90, $iAlturaLinha, 'Mãe', 1, 0, "C", 1);
    $oPdf->cell(50, $iAlturaLinha, 'Naturalidade', 1, 0, "C", 1);
    $oPdf->cell(5, $iAlturaLinha, 'UF', "TBL", 1, "C", 1);
    $lPrimeiraImpressao = false;    
    
  }
  
  $oPdf->setfont('arial', '', 6);  
  $oPdf->cell(20, $iAlturaLinha, $oDocente->codigo, "TBR", 0, "R");
  $oPdf->cell(90, $iAlturaLinha, $oDocente->nomedocente, 1, 0, "L");
  $oPdf->cell(20, $iAlturaLinha, $oDocente->datadenascimento, 1, 0, "C");
  $oPdf->cell(90, $iAlturaLinha, $oDocente->nomemaedocente, 1, 0, "L");
  $oPdf->cell(50, $iAlturaLinha, $oDocente->municipionascimento, 1, 0, "L");
  $oPdf->cell(5, $iAlturaLinha, $oDocente->ufnascimento, "TBL", 1, "L");
  
}
if (count($aListaDocente) > 0) {
  
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(220, $iAlturaLinha, "Total de Docentes", "TBR", 0, "R");
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(55, $iAlturaLinha, count($aListaDocente), "TBL", 0 , "R");
}
$oPdf->Output();
?>