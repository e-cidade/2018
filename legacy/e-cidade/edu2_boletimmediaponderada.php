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

require_once ("libs/db_stdlibwebseller.php");
require_once ("fpdf151/scpdf.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_libdocumento.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libparagrafo.php");

$clmatricula       = new cl_matricula;
$clturma           = new cl_turma;
$clregenteconselho = new cl_regenteconselho;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocavaliacao   = new cl_procavaliacao;
$clpareceraval     = new cl_pareceraval;
$claprovconselho   = new cl_aprovconselho;
$clDBConfig        = new cl_db_config();
$clEscola          = new cl_escola();
$oDadosGrade       = null;

$escola   = db_getsession("DB_coddepto");

$sSqlTurma = $clturma->sql_query_turmaserie( "", "ed57_i_codigo as turma", "", " ed220_i_codigo = {$turma}" );
$result00  = $clturma->sql_record( $sSqlTurma );
db_fieldsmemory( $result00, 0 );
$sOrdenacaoMatricula = "ed60_i_numaluno, to_ascii(ed47_v_nome)";
$sWhereMatricula     = " ed60_i_codigo in ({$alunos}) AND ed60_i_turma = {$turma}";
$sSqlMatricula       = $clmatricula->sql_query( "", "*", $sOrdenacaoMatricula, $sWhereMatricula );
$result              = $clmatricula->sql_record( $sSqlMatricula );
if ($clmatricula->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma matrícula para a turma selecionada<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
 <?
  exit;
}

$sCamposProcAvaliacao = "ed09_c_descr as periodoselecionado, ed41_i_sequencia as seqatual";
$sSqlProcAvaliacao    = $clprocavaliacao->sql_query( "", $sCamposProcAvaliacao, "", "ed41_i_codigo = {$periodo}" );
$result_per           = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );

db_fieldsmemory($result_per,0);
/**
 * Dados Instituição
 */
$sCamposInstit   = "nomeinst as nome, ender, munic, uf, telef, email, url, logo";
$sSqlDadosInstit = $clDBConfig->sql_query_file( db_getsession('DB_instit'), $sCamposInstit );
$rsDadosInstit   = db_query($sSqlDadosInstit);
$oDadosInstit    = db_utils::fieldsMemory( $rsDadosInstit, 0 );
$url             = $oDadosInstit->url;
$nome            = $oDadosInstit->nome;
$sLogoInstit     = $oDadosInstit->logo;
$munic           = $oDadosInstit->munic;

/**
 * Dados Escola
 */
$sCamposEscola     = "ed18_i_codigo, ed18_c_nome, j14_nome, ed18_i_numero, j13_descr, ed261_c_nome, ed260_c_sigla, ";
$sCamposEscola    .= "ed18_c_email, ed18_c_logo, ed18_codigoreferencia";
$sSqlDadosEscola   = $clEscola->sql_query_dados( db_getsession("DB_coddepto"), $sCamposEscola );
$rsDadosEscola     = db_query( $sSqlDadosEscola );
$oDadosEscola      = db_utils::fieldsMemory( $rsDadosEscola, 0 );
$sNomeEscola       = $oDadosEscola->ed18_c_nome;
$sLogoEscola       = $oDadosEscola->ed18_c_logo;
$iCodigoEscola     = $oDadosEscola->ed18_i_codigo;
$ruaescola         = $oDadosEscola->j14_nome;
$numescola         = $oDadosEscola->ed18_i_numero;
$bairroescola      = $oDadosEscola->j13_descr;
$cidadeescola      = $oDadosEscola->ed261_c_nome;
$estadoescola      = $oDadosEscola->ed260_c_sigla;
$emailescola       = $oDadosEscola->ed18_c_email;
$iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->ln(5);
$pdf->AddPage();
$iTamanhoFonte      = 6;
$iAlturaLinha       = 4;
$iBoletinsImpressos = 1;
$pdf->SetFont('arial', '', $iTamanhoFonte);
for ($x = 0; $x < $clmatricula->numrows; $x++) {


  $oDadosMatricula = db_utils::fieldsmemory($result, $x );
  if ($iBoletinsImpressos > 3) {

    $pdf->AddPage();
    $iBoletinsImpressos = 1;
  }

  $oMatricula = MatriculaRepository::getMatriculaByCodigo($oDadosMatricula->ed60_i_codigo);
  $sCurso     = $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getNome();
  $sBase      = $oMatricula->getTurma()->getBaseCurricular()->getDescricao();
  $iInicio    = $pdf->GetY();
  $pdf->Cell(150, $iAlturaLinha, $oDadosInstit->nome, 0, 0);
  $pdf->Cell(20, $iAlturaLinha, "EMITIDO EM:", 0, 0, 'R');
  $pdf->Cell(20, $iAlturaLinha, DATE("d/m/y", db_getsession("DB_datausu")), 0, 1);

  $pdf->Cell(150, $iAlturaLinha, "SECRETARIA MUNICIPAL DA EDUCAÇÃO", 0, 0);
  $pdf->Cell(20, $iAlturaLinha, "ANO:", 0, 0, 'R');
  $pdf->Cell(20, $iAlturaLinha, $oMatricula->getTurma()->getCalendario()->getAnoExecucao(), 0, 1, 'L');

  $pdf->Cell(190, $iAlturaLinha, $sNomeEscola, 0, 1);
  $pdf->Cell(190, $iAlturaLinha, "{$ruaescola}, Nº {$numescola}", 0, 1);
  $pdf->Cell(190, $iAlturaLinha, "{$bairroescola} - {$cidadeescola} / {$estadoescola}", 0, 1);
  $pdf->Cell(190, $iAlturaLinha, "BOLETIM DE DESEMPENHO DO ALUNO - {$sCurso} - {$sBase}", 0, 1, "C");

  $pdf->Cell(32, $iAlturaLinha, "ALUNO(A):", 0, 0, "L");
  $pdf->Cell(120, $iAlturaLinha, $oMatricula->getAluno()->getNome(), 0, 1, "L");

  $pdf->Cell(32, $iAlturaLinha, "TURMA:", 0, 0, "L");
  $pdf->Cell(50, $iAlturaLinha, $oMatricula->getTurma()->getDescricao(), 0, 0, "L");
  $pdf->Cell(20, $iAlturaLinha,"Nº", 0, 0, "L");
  $pdf->Cell(50, $iAlturaLinha, $oMatricula->getNumeroOrdemAluno(), 0, 0, "L");
  $pdf->Cell(30, $iAlturaLinha, "_______________________________________", 0, 1, "C");

  $pdf->Cell(32, $iAlturaLinha, "SITUAÇÃO DO ALUNO(A):", 0, 0, "L");
  $pdf->Cell(50, $iAlturaLinha, $oMatricula->getSituacao(), 0, 0, "L");
  $pdf->Cell(20, $iAlturaLinha,"COD. ALUNO:", 0, 0, "L");
  $pdf->Cell(50, $iAlturaLinha, $oMatricula->getAluno()->getCodigoAluno(), 0, 0, "L");
  $pdf->Cell(30, $iAlturaLinha, "ASSINATURA DO RESPONSÁVEL", 0, 1, "C");

  $iFim            = $pdf->GetY();
  $pdf->Rect(10, $iInicio, 191, $iFim - $iInicio);
  $oGradeAvaliacao = new GradeAvaliacaoMediaPonderada($oMatricula, $pdf);

  $pdf->Ln(5);
  $pdf->SetDash(true, true);
  $pdf->line(10, $pdf->getY(), 200, $pdf->GetY());
  $pdf->SetDash(false, false);
  $pdf->Ln();
  $iBoletinsImpressos ++;
}
$pdf->Output();