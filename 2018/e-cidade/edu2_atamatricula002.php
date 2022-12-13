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

define ('MSG_ATAMATRICULA002', "educacao.escola.edu2_atamatricula002.");

$oGet    = db_utils::postmemory($_GET);
$iEscola = db_getsession('DB_coddepto');
$oEscola = EscolaRepository::getEscolaByCodigo($iEscola);

$oDataBase         = new DBDate($oGet->dtBase);
$oCalendario       = null;
$aEnsinosRelatorio = array();
$aDadosRelatorio   = array();

try {

  if ( empty($oGet->iCalendario) ) {
    throw new Exception( _M(MSG_ATAMATRICULA002 . "calendario_nao_informado") );
  }
  if ( empty($oGet->iEnsino) ) {
    throw new Exception( _M(MSG_ATAMATRICULA002 . "ensino_nao_informado") );
  }
  if ( empty($oGet->iModelo) ) {
    throw new Exception( _M(MSG_ATAMATRICULA002 . "modelo_nao_informado") );
  }

  $oCalendario = CalendarioRepository::getCalendarioByCodigo($oGet->iCalendario);

  $sCampos  = " distinct ed10_i_codigo, trim(ed10_c_descr) as ensino, ed10_ordem, ";
  $sCampos .= " ed11_i_codigo, trim(ed11_c_descr) as etapa, ed11_i_sequencia, ";
  $sCampos .= " ed57_i_codigo ";

  $sOrdem = " ed10_ordem, ed11_i_sequencia ";
  $aWhere = array(" ed57_i_calendario = {$oGet->iCalendario} ");
  if ( $oGet->iEnsino != 'T' ) {
    $aWhere[] = " ed10_i_codigo = {$oGet->iEnsino} ";
  }

  /**
   * Busca todas turmas obedecendo a ordem dos ensinos e etapas
   */
  $oDaoTurma  = new cl_turma();
  $sSqlTurmas = $oDaoTurma->sql_query_turma(null, $sCampos, $sOrdem, implode('and', $aWhere));
  $rsTurmas   = db_query($sSqlTurmas);

  $oMsgErro = new stdClass();
  if ( !$rsTurmas ) {

    $oMsgErro->sErro = pg_last_error();
    throw new DBException( _M(MSG_ATAMATRICULA002 . "erro_buscar_turmas" ) );
  }

  if (pg_num_rows($rsTurmas) == 0 ) {
    throw new BusinessException( _M(MSG_ATAMATRICULA002 . "sem_turmas" ) );
  }

  $iLinhas = pg_num_rows($rsTurmas);

  for ($i = 0; $i < $iLinhas; $i++) {

    $oDados  = db_utils::fieldsMemory($rsTurmas, $i);
    $iEnsino = $oDados->ed10_i_codigo;
    $iEtapa  = $oDados->ed11_i_codigo;

    if ( !array_key_exists($iEnsino, $aDadosRelatorio) ) {

      $oEnsino                   = new stdClass();
      $oEnsino->sEnsino          = $oDados->ensino;
      $oEnsino->lTemAlunos       = false;
      $oEnsino->iTotalAlunos     = 0;
      $oEnsino->aEtapas          = array();
      $aDadosRelatorio[$iEnsino] = $oEnsino;
    }

    if ( !array_key_exists($iEtapa, $aDadosRelatorio[$iEnsino]->aEtapas) ) {

      $oEtapa               = new stdClass();
      $oEtapa->sEtapa       = $oDados->etapa;
      $oEtapa->iTotalAlunos = 0;
      $oEtapa->lTemAlunos   = false;
      $oEtapa->aTurmas      = array();

      $aDadosRelatorio[$iEnsino]->aEtapas[$iEtapa] = $oEtapa;
    }

    $oTurma = TurmaRepository::getTurmaByCodigo($oDados->ed57_i_codigo);
    $oEtapa = EtapaRepository::getEtapaByCodigo($iEtapa);

    $oDadosTurma          = new stdClass();
    $oDadosTurma->sTurma  = substr( $oTurma->getDescricao(), 0, 30);
    $oDadosTurma->sTurno  = $oTurma->getTurno()->getDescricao();
    $oDadosTurma->aAlunos = buscaAlunoParaModelo($oGet->iModelo, $oGet->dtBase, $oTurma, $oEtapa);

    $aDadosRelatorio[$iEnsino]->aEtapas[$iEtapa]->aTurmas[]     = $oDadosTurma;
    $aDadosRelatorio[$iEnsino]->aEtapas[$iEtapa]->iTotalAlunos += count($oDadosTurma->aAlunos);
    $aDadosRelatorio[$iEnsino]->iTotalAlunos                   += count($oDadosTurma->aAlunos);

    if ( $aDadosRelatorio[$iEnsino]->aEtapas[$iEtapa]->iTotalAlunos > 0 ) {
      $aDadosRelatorio[$iEnsino]->aEtapas[$iEtapa]->lTemAlunos = true;
    }

    if ( $aDadosRelatorio[$iEnsino]->iTotalAlunos > 0 ) {

      $aDadosRelatorio[$iEnsino]->lTemAlunos = true;
    }
  }

  TurmaRepository::removeAll();

  $lTemAlunos = false;
  foreach ($aDadosRelatorio as $oDadosEnsino) {

    if ($oDadosEnsino->lTemAlunos) {

      $lTemAlunos = true;
      $aEnsinosRelatorio[] = $oDadosEnsino->sEnsino;
    }
  }

  if ( !$lTemAlunos ) {
    throw new BusinessException( _M(MSG_ATAMATRICULA002 . "sem_alunos_matriculados" ) );
  }

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $e->getMessage());
}

/**
 * Busca todas matrículas da turma e retorna de acordo com o modelo informado
 * @param  string $sModelo I = Inicial e E = Especial
 * @param  string $sDtBase Data base para comparação
 * @param  Turma  $oTurma
 * @param  Etapa  $oEtapa
 * @return stdClass[]
 */
function buscaAlunoParaModelo($sModelo, $sDtBase, Turma $oTurma, Etapa $oEtapa) {

  $oDataBase   = new DBDate($sDtBase);
  $aAlunos = array();

  foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa, false) as $oMatricula) {

    /**
     * Visto com Tiago que alunos com troca de turma não podem aparecer no relatorio
     */
    if ($oMatricula->getSituacao() == 'TROCA DE TURMA' ) {
      continue;
    }

    switch ($sModelo) {

      case 'I':
        if ( $oMatricula->getDataMatricula()->getTimeStamp() < $oDataBase->getTimeStamp() ) {
          $aAlunos[] = montaStdClassDadosAluno($oMatricula);
        }
        break;
      case 'E':

        if ( $oMatricula->getDataMatricula()->getTimeStamp() >= $oDataBase->getTimeStamp() )   {
          $aAlunos[] = montaStdClassDadosAluno($oMatricula);
        }
        break;
    }
  }

  MatriculaRepository::removeAll();
  return $aAlunos;
}

/**
 * Monta uma stdClass com os dados do aluno para o relatório
 * @param  Matricula $oMatricula
 * @return stdClass
 */
function montaStdClassDadosAluno(Matricula $oMatricula) {

  $oNaturalidade = $oMatricula->getAluno()->getNaturalidade();
  $oDadosAluno   = new stdClass();
  $oDtNascimento = new DBDate($oMatricula->getAluno()->getDataNascimento());

  $oDadosAluno->sNome              = $oMatricula->getAluno()->getNome();
  $oDadosAluno->sSexo              = $oMatricula->getAluno()->getSexo();
  $oDadosAluno->sDtNascimento      = $oDtNascimento->convertTo(DBDate::DATA_PTBR);
  $oDadosAluno->sNomePai           = $oMatricula->getAluno()->getNomePai();
  $oDadosAluno->sNomeMae           = $oMatricula->getAluno()->getNomeMae();
  $oDadosAluno->sDataMatricula     = $oMatricula->getDataMatricula()->getDate();
  $oDadosAluno->sSituacaoMatricula = $oMatricula->getSituacao();

  if ( $oNaturalidade->getNome() != '' ) {

    $oDadosAluno->sNaturalidade  = $oNaturalidade->getNome();
    $oDadosAluno->sNaturalidade .= "/". $oNaturalidade->getUF()->getUF();
  }

  return $oDadosAluno;
}

/**
 * Retorna o Parágrafo de Abertura e encerramento
 * @param  string $sTipoModelo       I -> Matrícula Inicial - E -> Matrícula Especial
 * @param  string $sTipo             'A' - para abertura do documento
 * @param  DBDate $oData             Data
 * @param  Escola $oEscola           Escola que esta emitindo documento
 * @param  array  $aEnsinosRelatorio Lista dos ensinos presente na ata
 * @return string                    Parágrafo configurado
 */
function getParagrafo($sTipoModelo, $sTipo, DBDate $oData, Escola $oEscola, $aEnsinosRelatorio) {

  $sMes    = strtolower( DBDate::getMesExtenso($oData->getMes()) );
  $iAno    = $oData->getAno();
  $sModelo = $sTipoModelo == 'I' ? "iniciais" : "especiais";
  $sStatus = $sTipo == 'A' ? 'abertas' : 'encerradas';

  $sMensagem  = "Ao(s) [dia] dia(s) do mês de [mes] do ano de [ano], foram [status] as matrículas [modelo] para o(s)";
  $sMensagem .= " ensino(s) [ensinos] na escola [escola] para o ano letivo de [ano_letivo].";

  $sMensagem = str_replace('[dia]', $oData->getDia(), $sMensagem);
  $sMensagem = str_replace('[mes]', $sMes, $sMensagem);
  $sMensagem = str_replace('[ano]', $iAno, $sMensagem);
  $sMensagem = str_replace('[ensinos]', implode(', ', $aEnsinosRelatorio), $sMensagem);
  $sMensagem = str_replace('[modelo]', $sModelo, $sMensagem);
  $sMensagem = str_replace('[status]', $sStatus, $sMensagem);
  $sMensagem = str_replace('[escola]', $oEscola->getNome(), $sMensagem);
  $sMensagem = str_replace('[ano_letivo]', $iAno, $sMensagem);

  return $sMensagem;

}

/** ******************************************************************************************************* *
 ** ************************************** INICIO ESCRITA DO PDF ****************************************** *
 ********************************************************************************************************** */

$oPdf = new FpdfMultiCellBorder('L');
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false, 10);
$oPdf->SetFillColor(225);
$oPdf->SetMargins(8, 10);
$oPdf->mostrarRodape(true);
$oPdf->mostrarTotalDePaginas(true);

$oDataAbertura   = $oCalendario->getDataInicio();
$oDataFechamento = $oDataBase;
$sTitulo = 'Ata de Matrículas Iniciais';

if ( $oGet->iModelo == 'E' ) {

  $sTitulo = 'Ata de Matrículas Especiais';
  $oDataAbertura   = $oDataBase;
  $oDataFechamento = $oCalendario->getDataFinal();
}

$lPrimeiraPagina     = true;
$lQuebraPaginaEnsino = true;
$iContadorAluno      = 1;

/**
 * Imprime os alunos
 */
foreach ($aDadosRelatorio as $oDadosEnsino) {

  if ( !$oDadosEnsino->lTemAlunos ) {
    continue;
  }

  if ( $lQuebraPaginaEnsino || ($oPdf->GetY() >= $oPdf->h - 30) ) {

    imprimeCabecalho($oPdf, $oEscola);
    if ($lPrimeiraPagina) {

      $oPdf->ln();
      $oPdf->Cell(281, 4, $sTitulo, 0, 1, 'C');
      $sMensagem = getParagrafo($oGet->iModelo, 'A', $oDataAbertura, $oEscola, $aEnsinosRelatorio);
      $oPdf->SetFont('Arial', '', 8);
      $oPdf->ln();
      $oPdf->setX(78);
      $oPdf->MultiCell(143, 4, $sMensagem, 0, "C");
      $lPrimeiraPagina = false;
    }
  }

  $lQuebraPaginaEtapa = false;
  foreach ($oDadosEnsino->aEtapas as $oDadosEtapa) {

    if ( !$oDadosEtapa->lTemAlunos ) {
      continue;
    }
    if ( $lQuebraPaginaEtapa ) {
      imprimeCabecalho($oPdf, $oEscola);
    }

    $lQuebraPaginaTurma = false;
    foreach ($oDadosEtapa->aTurmas as $oDadosTurma) {

      if ( count($oDadosTurma->aAlunos) == 0) {
        continue;
      }
      if ( $lQuebraPaginaTurma || ($oPdf->GetY() >= $oPdf->h - 30)) {
        imprimeCabecalho($oPdf, $oEscola);
      }

      $oPdf->ln();
      $oPdf->SetFont('Arial', 'B', 8);
      $oPdf->Cell( 15, 4, 'Ensino: ',             0, 0, 'L');
      $oPdf->Cell( 80, 4, $oDadosEnsino->sEnsino, 0, 0);
      $oPdf->Cell( 20, 4, 'Etapa: ',              0, 0, 'R');
      $oPdf->Cell( 41, 4, $oDadosEtapa->sEtapa,   0, 0);
      $oPdf->Cell( 20, 4, "Turma: ",              0, 0, 'R');
      $oPdf->Cell( 50, 4, $oDadosTurma->sTurma,   0, 0);
      $oPdf->Cell( 20, 4, 'Turno: ',              0, 0, 'R');
      $oPdf->Cell( 30, 4, $oDadosTurma->sTurno,   0, 1);
      $oPdf->ln(2);

      $oPdf->SetFont('Arial', '', 7);

      $lImprimeCabecalhoAluno = true;
      foreach ($oDadosTurma->aAlunos as $oAluno) {

        if ( $lImprimeCabecalhoAluno || $oPdf->GetY() >= ($oPdf->h - 20) ) {

          $lQuebraPagina = $oPdf->GetY() >= ($oPdf->h - 20);

          // Imprime o Cabeçalho da página e continua listando os alunos matriculados
          if ( $lQuebraPagina ) {

            imprimeCabecalho($oPdf, $oEscola );
            $oPdf->ln();
          }

          imprimeCabecalhoAluno($oPdf, false);
          $lImprimeCabecalhoAluno = false;
        }

        $aFiliacao = array();
        if ( !empty($oAluno->sNomeMae) ) {
          $aFiliacao[] = $oAluno->sNomeMae;
        }

        if ( !empty($oAluno->sNomePai) ) {
          $aFiliacao[] = $oAluno->sNomePai;
        }

        $oPdf->SetFont('Arial', '', 7);

        $sFiliacao    = implode(' / ', $aFiliacao);
        $iAlturaLinha = 4;

        $iYInicial      = $oPdf->GetY();
        $iXNomeAluno    = 108; // posição final do eixo X de cada coluna
        $iXFiliacao     = 229; // posição final do eixo X de cada coluna
        $iXNaturalidade = 259; // posição final do eixo X de cada coluna

        $oPdf->Line(8, $iYInicial, 289, $iYInicial);

        $aPosicaoYAposCadaMultCell = array();
        $oPdf->Cell( 10, $iAlturaLinha, $iContadorAluno, 0, 0, 'C');
        $oPdf->MultiCell( 90, 4, $oAluno->sNome, 0, 'L');
        $aPosicaoYAposCadaMultCell[] = $oPdf->GetY();
        $oPdf->SetXY($iXNomeAluno, $iYInicial);
        $oPdf->Cell( 15, $iAlturaLinha, $oAluno->sDtNascimento, 0, 0, 'C');
        $oPdf->Cell( 10, $iAlturaLinha, $oAluno->sSexo,         0, 0, 'C');
        $oPdf->MultiCell(96, 4, $sFiliacao, 0, 'L' );
        $aPosicaoYAposCadaMultCell[] = $oPdf->GetY();
        $oPdf->SetXY($iXFiliacao, $iYInicial);
        $oPdf->MultiCell( 30, 4, $oAluno->sNaturalidade,0, 'L');
        $aPosicaoYAposCadaMultCell[] = $oPdf->GetY();
        $oPdf->SetXY($iXNaturalidade, $iYInicial);
        $oPdf->MultiCell( 30, 4, $oAluno->sSituacaoMatricula, 0, 'L');
        $aPosicaoYAposCadaMultCell[] = $oPdf->GetY();

        $iMaiorYDefinido = array_reduce($aPosicaoYAposCadaMultCell, "maior");

        $oPdf->Line(8,   $iMaiorYDefinido, 289, $iMaiorYDefinido); // borda de baixo da linha
        $oPdf->Line(8,   $iYInicial,         8, $iMaiorYDefinido); // borda inicial vertical
        $oPdf->Line(18,  $iYInicial,        18, $iMaiorYDefinido); // fecha numero
        $oPdf->Line(108, $iYInicial,       108, $iMaiorYDefinido); // fecha nome aluno
        $oPdf->Line(123, $iYInicial,       123, $iMaiorYDefinido); // fecha data nascimento
        $oPdf->Line(133, $iYInicial,       133, $iMaiorYDefinido); // fecha sexo
        $oPdf->Line(229, $iYInicial,       229, $iMaiorYDefinido); // fecha filiacao
        $oPdf->Line(259, $iYInicial,       259, $iMaiorYDefinido); // fecha Naturalidade
        $oPdf->Line(289, $iYInicial,       289, $iMaiorYDefinido); // borda final vertical

        $iContadorAluno ++;
        $oPdf->SetY($iMaiorYDefinido);
      }
      $lQuebraPaginaTurma = true;
    }
    $lQuebraPaginaEtapa = true;
  }
  $lQuebraPaginaEnsino = true;
}

$oPdf->ln();
$oPdf->ln();
$iAlturaLinha = 4;
/**
 * Imprime totalizadores
 */
foreach ($aDadosRelatorio as $oDadosEnsino) {

  if ( !$oDadosEnsino->lTemAlunos ) {
    continue;
  }

  $iNumeroEtapas = count($oDadosEnsino->aEtapas);

  $iAlturaQuadrTotalizador = ($iNumeroEtapas + 4) * $iAlturaLinha;
  if ( $oPdf->GetY() >= $oPdf->h - $iAlturaQuadrTotalizador) {
    $oPdf->AddPage();
  }
  imprimeCabecalhoTotalizado($oPdf, $oDadosEnsino->sEnsino, $oDadosEnsino->iTotalAlunos);

  foreach ($oDadosEnsino->aEtapas as $oDadosEtapa) {

    if ( !$oDadosEtapa->lTemAlunos ) {
      continue;
    }
    $oPdf->SetFont('Arial', '', 7);
    $oPdf->Cell(200, 4, $oDadosEtapa->sEtapa, 1, 0, 'L');
    $oPdf->Cell( 81, 4, $oDadosEtapa->iTotalAlunos, 1, 1, 'R' );
  }
  $oPdf->ln();
}
$oPdf->ln();
$oPdf->ln();

$sParagafoFinal   = getParagrafo($oGet->iModelo, 'F', $oDataFechamento, $oEscola, $aEnsinosRelatorio);


$oPdf->SetFont('Arial', '', 8);
$iLinhasParagrafo = $oPdf->NbLines(143, $sParagafoFinal);

$iAlturaParagrafoMaisAssinaturas = ($iLinhasParagrafo * 4) + 50;
if ( $oPdf->GetY() >= $oPdf->h - $iAlturaParagrafoMaisAssinaturas ) {

  imprimeCabecalho($oPdf, $oEscola);
  $oPdf->ln(8);
  $oPdf->SetFont('Arial', '', 8);
}
$oPdf->setX(78);
$oPdf->MultiCell(143, 4, $sParagafoFinal, 0, "C");
$oPdf->ln(10);

$oPdf->SetFont('Arial', 'B', 8);
$sMunicipio     = $oEscola->getDepartamento()->getInstituicao()->getMunicipio();
$oDataImpressao = new DBDate(date('Y-m-d'));
$sDataExtenso   = strtolower($oDataImpressao->dataPorExtenso());
$oPdf->Cell( 200, 4, "$sMunicipio, {$sDataExtenso}." , 0, 1 ) ;
$oPdf->ln(20);
$oPdf->SetFont('Arial', '', 8);

$oPdf->Line(8, $oPdf->GetY(), 120, $oPdf->GetY());
$oPdf->Cell(120, 4, 'Diretor(a)', 0, 0, 'C');
$oPdf->setX(161);
$oPdf->Line(161, $oPdf->GetY(), 291, $oPdf->GetY());
$oPdf->Cell(120, 4, 'Secretário(a)', 0, 0, 'C');


/** ******************************************************************************************************* *
 ** ************************************ FUNÇÕES PARA USO DO PDF ****************************************** *
 ********************************************************************************************************** */

/**
 * Imprime cabeçalho do relatorio
 * @param  FPDF   $oPdf
 * @param  Escola $oEscola
 */
function imprimeCabecalho($oPdf, $oEscola) {

  $sCabecalho  = "{$oEscola->getDepartamento()->getInstituicao()->getDescricao()}\n";
  $sCabecalho .= "{$oEscola->getNome()}\n";
  $sCabecalho .= "{$oEscola->getEndereco()}, {$oEscola->getNumeroEndereco()} - {$oEscola->getBairro()}\n";

  $oPdf->AddPage();
  $oPdf->SetFont('Arial', 'B', 8);
  $oPdf->MultiCell(281, 4, $sCabecalho, 0, "C");
}

/**
 * Imprime cabeçalho dos dados do aluno
 * @param  FPDF    $oPdf
 * @param  boolean $lQuebraPagina
 */
function imprimeCabecalhoAluno($oPdf) {

  $oPdf->SetFont('Arial', 'B', 7);
  $oPdf->Cell( 10, 4, 'Nº',           1, 0, 'C');
  $oPdf->Cell( 90, 4, 'Nome',         1, 0, 'C');
  $oPdf->Cell( 15, 4, 'Nascimento',   1, 0, 'C');
  $oPdf->Cell( 10, 4, 'Sexo',         1, 0, 'C');
  $oPdf->Cell( 96, 4, 'Filiação',     1, 0, 'C');
  $oPdf->Cell( 30, 4, 'Naturalidade', 1, 0, 'C');
  $oPdf->Cell( 30, 4, 'Situação',     1, 1, 'C');

}

/**
 * Imprime cabeçalho do totalizador
 * @param  FPDF    $oPdf
 * @param  string  $sEnsino
 * @param  integer $iTotalAlunos
 */
function imprimeCabecalhoTotalizado($oPdf, $sEnsino, $iTotalAlunos) {

  $oPdf->SetFont('Arial', 'B', 8);
  $oPdf->Cell( 200, 4, $sEnsino,                 1, 0, 'C');
  $oPdf->Cell( 81,  4, "TOTAL: {$iTotalAlunos}", 1, 1, 'L');

}

function maior($iValor1, $iValor2) {

  $iAux = $iValor1;
  if ($iValor1 < $iValor2) {
    $iAux = $iValor2;
  }
  return $iAux;
}
$oPdf->output();