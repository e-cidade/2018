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

require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/FpdfMultiCellBorder.php"));


$oDadosRelatorio = new stdClass();
$oDadosRelatorio->oData   = new DBDate(date("Y-m-d"));
$oDadosRelatorio->sData   = $oDadosRelatorio->oData->dataPorExtenso();
$oDadosRelatorio->aAlunos = array();
try {

  if ( empty($_GET['iTransferencia']) ) {
    throw new Exception("Não foi informado a transferência.");
  }
  $iMatricula = null;
  if ( !empty($_GET['iMatricula']) ) {
    $iMatricula = $_GET['iMatricula'];
  }

  $iTransferencia = $_GET['iTransferencia'];
  $oTransferencia = new TransferenciaLote($iTransferencia);
  $aMatriculas    = $oTransferencia->getMatriculas();

  $sTexto  = "Atesto que [sAluno] natural de [sNaturalidade], no estado de [sUf], nascido(a) no dia [sDataNascimento], ";
  $sTexto .= "filho(a) de [sFiliacao], cursou até [sDataResultadoFinal], a(o) [sEtapa] do(a) [sEnsino] nesta escola, ";
  $sTexto .= "estando apto a continuar seus estudos em qualquer instituição de ensino, conforme legislação vigente.";

  $aVariaveis = array('sAluno', 'sNaturalidade', 'sUf', 'sDataNascimento', 'sFiliacao',
                      'sDataResultadoFinal', 'sEtapa', 'sEnsino');

  foreach ($aMatriculas as $oMatricula) {

    $oDados = new stdClass();
    if ( !is_null($iMatricula) && $iMatricula != $oMatricula->getCodigo()) {
      continue;
    }

    $oAluno                = $oMatricula->getAluno();
    $oDados->sAluno        = $oAluno->getNome();
    $oDados->sNaturalidade = ".................................................................";
    $oDados->sUf           = ".........";
    if ( !is_null($oAluno->getNaturalidade()->getCodigo()) ) {

      $oDados->sNaturalidade = $oAluno->getNaturalidade()->getNome();
      $oDados->sUf           = $oAluno->getNaturalidade()->getUF()->getUF();
    }

    $oDataNascinmento        = new DBDate($oAluno->getDataNascimento());
    $oDados->sDataNascimento = $oDataNascinmento->dataPorExtenso();
    $oDados->sNomeMae        = $oAluno->getNomeMae();
    $oDados->sNomePai        = $oAluno->getNomePai();
    $oDados->sResponsavel    = $oAluno->getNomeResponsavelLegal();
    $oDados->sEtapa          = $oMatricula->getEtapaDeOrigem()->getNome();
    $oDados->sEtapaAbreviado = $oMatricula->getEtapaDeOrigem()->getNomeAbreviado();
    $oDados->sEnsino         = $oMatricula->getEtapaDeOrigem()->getEnsino()->getNome();

    $aFiliacao = array();
    if ( $oAluno->getNomeMae() != '') {
      $aFiliacao[] = $oAluno->getNomeMae();
    }
    if ( $oAluno->getNomePai() != '') {
      $aFiliacao[] = $oAluno->getNomePai();
    }

    $oDados->sFiliacao = implode(' e ', $aFiliacao);
    $oCalendario       = $oMatricula->getTurma()->getCalendario();

    $oDados->oDataResultadoFinal = $oCalendario->getDataResultadoFinal();
    $oDados->sDataResultadoFinal = $oDados->oDataResultadoFinal->dataPorExtenso();

    /**
     * Monta o texto de transferencia
     */
    $oDados->sMensagem = $sTexto;
    foreach ($aVariaveis as $sVariavel) {
      $oDados->sMensagem = str_replace("[{$sVariavel}]", $oDados->$sVariavel, $oDados->sMensagem);
    }

    /**
     * Busca as progressões parciais ATIVAS que o aluno possui.
     */
    $aProgressoes             =  ProgressaoParcialAlunoRepository::getProgressoesAtivas($oAluno);
    $oDados->aProgressoes     = array();
    $aDisciplinasEmProgressao = array();
    foreach ( $aProgressoes as $oProgressao ) {

      $iAno   = $oProgressao->getAno();
      $sEtapa = $oProgressao->getEtapa()->getNome();
      $sIndex = "{$iAno}#{$sEtapa}";

      $aDisciplinasEmProgressao[$sIndex][] = trim($oProgressao->getDisciplina()->getNomeDisciplina());
    }

    foreach ($aDisciplinasEmProgressao as $sIndice => $aDisciplina) {

      $aIndice     = explode("#", $sIndice);
      $sDisciplina = implode(", ", $aDisciplina);

      $sObsProgressao  = "O aluno possui progressão na etapa {$aIndice[1]} no ano {$aIndice[0]}";
      $sObsProgressao .= " na(s) disciplina(s) {$sDisciplina}.";
      $oDados->aProgressoes[] = $sObsProgressao;
    }

    /**
     * Observacao fixa
     */
    $sDataInicio    = $oCalendario->getDataInicio()->getDate(DBDate::DATA_PTBR);
    $sDataFim       = $oCalendario->getDataFinal()->getDate(DBDate::DATA_PTBR);
    $sEscolaDestino = $oTransferencia->getEscolaDestino()->getNome();
    $sTipoEscola    = "fora da rede";
    if ( $oTransferencia->isEscolaDestinoRede() ) {
      $sTipoEscola = "na rede";
    }

    $oDados->sObservacaoFixa  = "Período Letivo: {$sDataInicio} até {$sDataFim}. Escola de Destino {$sTipoEscola}: ";
    $oDados->sObservacaoFixa .= $sEscolaDestino;

    $oDadosRelatorio->aAlunos[] = $oDados;
  }

  $sAssinatura = str_repeat('.', '95');
  $sFuncao     = '';

  if ( isset($_GET['sEmissor'])) {

    $sAto = base64_decode($_GET['sAtoLegal']);
    $sAssinatura = base64_decode($_GET['sEmissor']);
    $sFuncao     = base64_decode($_GET['sFuncao']);
    if ( !empty($sAto) ) {
      $sFuncao .= " ( {$sAto})";
    }
  }

} catch(Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}


$oPdf = new FpdfMultiCellBorder('P');
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setExibeBrasao(true);
$oPdf->exibeHeader(true);
$oPdf->SetAutoPageBreak(false, 10);
$oPdf->SetFillColor(225);
$oPdf->SetMargins(10, 10);
$oPdf->mostrarRodape(true);
$oPdf->mostrarEmissor(true);
$oPdf->mostrarTotalDePaginas(false);

$head1 = "GUIA DE TRANSFERÊNCIA";

foreach ($oDadosRelatorio->aAlunos as $oAluno) {

  $oPdf->AddPage();

  $oPdf->SetY( $oPdf->GetY() + 10 );

  $oPdf->SetFont('Arial', 'B', 10);
  $oPdf->Cell( 192, 4, "Guia de Transferência", 0, 1, 'C');
  $oPdf->ln();
  $oPdf->SetFont('Arial', '', 8);
  $oPdf->SetX(20);
  $oPdf->MultiCell( 173, 4, $oAluno->sMensagem);
  $oPdf->SetY( 100 );

  $oPdf->SetFont('Arial', 'B', 9);
  $oPdf->Cell( 192, 4, 'Observações', 1, 1, 'C', 1);
  $oPdf->Ln();
  $oPdf->SetFont('Arial', '', 8);

  foreach ($oAluno->aProgressoes as $sMsg ) {

    $oPdf->SetX(20);
    $oPdf->MultiCell( 173, 4, $sMsg);
  }
  $oPdf->SetX(20);
  $oPdf->MultiCell( 173, 4, $oAluno->sObservacaoFixa);

  $oPdf->SetY( 240 );

  $sMunicipio = $oTransferencia->getEscola()->getMunicipio();
  $oPdf->Cell( 192, 4, "{$sMunicipio}, {$oDadosRelatorio->sData}", 0, 1, 'C');
  $oPdf->ln(12);
  $oPdf->Line(60, $oPdf->GetY(), 160, $oPdf->GetY() );

  $oPdf->ln();

  $oPdf->Cell( 192, 4, $sAssinatura, 0, 1, 'C');
  if ( !empty($sFuncao) ) {
    $oPdf->Cell( 192, 4, $sFuncao, 0, 1, 'C');
  }


  $iYFinal = $oPdf->GetY() + 10;
  $oPdf->Line(10, 40, 202, 40 );
  $oPdf->Line(10, 40, 10, $iYFinal );
  $oPdf->Line(10, $iYFinal, 202, $iYFinal );
  $oPdf->Line(202, 40, 202, $iYFinal );
}

$oPdf->output();