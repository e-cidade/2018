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

require_once(modification("fpdf151/FpdfMultiCellBorder.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/exceptions/DBException.php"));

$oGet       = db_utils::postMemory($_GET);
$oFiltros   = new stdClass();
$oGet->obs1 = base64_decode($oGet->obs1);

/**
 * Forma de apresentacao dos pareceres padronizados
 * 'C' => Concatenar pareceres lado a lado
 * 'L' => Listar pareceres um abaixo do outro
 * @var string
 */
$oFiltros->sPadrao = $oGet->padraotipo;

/**
 * Informa se eh um parecer unico 'PU'
 * 'yes' | 'no'
 * @var boolean
 */
$oFiltros->lParecerUnico = $oGet->punico == 'yes' ? true : false;

/**
 * Tipo de avaliacao concatenado com o codigo do periodo
 * Ex.: A|219
 *      A => Avaliacao
 *      R => Resultado
 * @var string
 */
$sPeriodo             = explode("|", $oGet->periodo);
$oFiltros->sAvaliacao = $sPeriodo[0];
$oFiltros->iPeriodo   = $sPeriodo[1];


/**
 * Array com o codigo da regencia da disciplina
 * @var array
 */
$aDisciplinas = explode(",", $oGet->disciplinas);

/**
 * Informa se deve ser impresso o nome do professor conselheiro
 * 'S' | 'N'
 * @var string
 */
$oFiltros->lAssinaturaConselheiro = $oGet->assinaturaregente == 'S' ? true : false;

/**
 * Codigo dos alunos selecionados
 * Ex.: 49740,49741,49742,49743
 * @var string
 */
$sAlunos = $oGet->alunos;

/**
 * Observacao a ser impressa no boletim
 * @var text
 */
$oFiltros->sObservacao = $oGet->obs1;

/**
 * Altura padrao das linhas
 */
$oFiltros->iAlturaLinhaPadrao = 4;

/**
 * Largura padrao das linhas
 */
$oFiltros->iLarguraLinha = 190;

/**
 * Array com os alunos selecionados para impressao
 */
$aAlunos = explode(",", $sAlunos);

/**
 * Instancia de turma, pelo codigo passado como parametro (turmaserieregimemat)
 */
$oFiltros->oTurma = TurmaRepository::getTurmaByCodigoTurmaSerieRegimeMat($oGet->turma);

/**
 * Instancia de etapa, pelo codigo passado como parametro (turmaserieregimemat)
 */
$oFiltros->oEtapa = EtapaRepository::getEtapaByCodigoTurmaSerieRegimeMat($oGet->turma);

/**
 * Variavel que recebe o elemento de uma avaliacao
 */
$oFiltros->oElementoAvaliacao = null;

$oFiltros->iLarguraRetangulo = 200;

/**
 * Professor conselheiro da Turma
 */
$oFiltros->sProfessorConselheiro = "";
if ($oFiltros->oTurma->getProfessorConselheiro() && $oFiltros->oTurma->getProfessorConselheiro()->getNome() != '') {
  $oFiltros->sProfessorConselheiro = "Professor Conselheiro  ".$oFiltros->oTurma->getProfessorConselheiro()->getNome();
}

/**
 * Contém os elementos de avaliação
 */
$aAvaliacoes = array();

if ($oFiltros->sAvaliacao == 'A') {

  $oFiltros->oElementoAvaliacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oFiltros->iPeriodo);
  $aAvaliacoes[] = $oFiltros->oElementoAvaliacao;
} else {

  $oProcedimentoAvalicao        = $oFiltros->oTurma->getProcedimentoDeAvaliacaoDaEtapa($oFiltros->oEtapa);
  $oFiltros->oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oFiltros->iPeriodo);

  /**
   * Procurar todas as avaliacoes em que a ordem da avaliacao é menor que o resultado
   */
  $aAvaliacoesProcedimento = $oProcedimentoAvalicao->getAvaliacoes();
  $iOrdemSequencia         = $oFiltros->oElementoAvaliacao->getOrdemSequencia();

  foreach ($aAvaliacoesProcedimento as $oAvaliacao) {

    if ($oAvaliacao->getOrdemSequencia() < $iOrdemSequencia) {
      $aAvaliacoes[] = $oAvaliacao;
    }
  }
}


/**
 * Controla o total de faltas dentro de um periodo (por aluno)
 */
$oFiltros->iFaltasPeriodo = 0;

$pdf = new FpdfMultiCellBorder();
$pdf->Open();
$pdf->AliasNbPages();

$oFiltros->iAlturaDeQuebraPagina = $pdf->h - 15;

$aAlunosImpressao = array();

$clobsboletim    = new cl_obsboletim;
$iEscola = $oFiltros->oTurma->getEscola()->getCodigo();
$sSqlObsBoletim = $clobsboletim->sql_query( "", "ed252_t_mensagem", "", "ed252_i_escola = {$iEscola}" );
$resultobs      = $clobsboletim->sql_record( $sSqlObsBoletim );

if( $clobsboletim->numrows > 0 ) {

	$oDadosObs = db_utils::fieldsMemory($resultobs, 0);
	$oFiltros->sObservacao = $oDadosObs->ed252_t_mensagem;
}




foreach ($aAlunos as $iMatricula) {

  $oMatricula = MatriculaRepository::getMatriculaByCodigo($iMatricula);

  $oDadosAluno                  = new stdClass();
  $oDadosAluno->iCodigo         = $oMatricula->getAluno()->getCodigoAluno();
  $oDadosAluno->sNome           = $oMatricula->getAluno()->getNome();
  $oDadosAluno->iMatricula      = $oMatricula->getCodigo();
  $oDadosAluno->sResultadoFinal = "EM ANDAMENTO";

  $oDadosAluno->aDisciplinas = array();

  foreach ($aDisciplinas as $iDisciplina) {

    $oRegencia        = RegenciaRepository::getRegenciaByCodigo($iDisciplina);
    $oDadosDisciplina = new stdClass();

    $oDadosDisciplina->iRegencia    = $oRegencia->getCodigo();
    $oDadosDisciplina->sAbreviatura = $oRegencia->getDisciplina()->getAbreviatura();
    $oDadosDisciplina->sDisciplina  = $oRegencia->getDisciplina()->getNomeDisciplina();
    $oDadosDisciplina->iTotalFaltas = 0;
    $oDadosDisciplina->iTotalAulas  = 0;

    db_inicio_transacao();
    $oDiarioDeClasse   = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);
    db_fim_transacao();

    /**
     * Percorremos a(s) avaliação(ões) de acordo com o período selecionado
     */
    foreach ($aAvaliacoes as $oAvaliacao) {

      $oPeriodoAvaliacao = $oAvaliacao->getPeriodoAvaliacao();

      /**
       * Verificamos se trata-se de parecer unico, calculando o total de dias letivos e faltas. Caso contrario, o calculo
       * sera de acordo com a regencia
       */
      if ($oFiltros->lParecerUnico) {

        foreach ($oDiarioDeClasse->getDisciplinas() as $oDisciplinaDiario) {

          $oDadosDisciplina->iTotalFaltas += $oDisciplinaDiario->getTotalFaltasPorPeriodo($oPeriodoAvaliacao);
          $oDadosDisciplina->iTotalAulas  += $oDisciplinaDiario->getRegencia()->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
        }
      } else {
        $oDadosDisciplina->iTotalFaltas = $oDiarioDisciplina->getTotalFaltasPorPeriodo($oPeriodoAvaliacao);
        $oDadosDisciplina->iTotalAulas  = $oRegencia->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
      }
    }


    /**
     * Caso a matricula do aluno esteja concluida, buscamos o resultado final de acordo com o termo configurado para o
     * ensino no ano de execucao do calendario, imprimindo a descricao do Termo
     * Ex.: Aprovado / Reprovado
     *
     * Caso nao esteja concluida, imprime como EM ANDAMENTO
     * @todo Testar
     */
    if ($oMatricula->isConcluida()) {

      $sTermoResultadoFinal = $oDiarioDeClasse->getResultadoFinal();
      $oTermoEnsino         = DBEducacaoTermo::getTermoEncerramento($oFiltros->oTurma->getBaseCurricular()->getCurso()->getEnsino(),
                                                                    $sTermoResultadoFinal,
                                                                    $oFiltros->oTurma->getCalendario()->getAnoExecucao()
                                                                    );
      $oDadosAluno->sResultadoFinal = $oTermoEnsino[0]->sDescricao;
    }


    /**
     * Buscamos os pareceres vinculados ao aluno para regência
     */
    $oDadosDisciplina->oParecer = LancamentoAvaliacaoAluno::getParecer($oMatricula, $oRegencia,
                                                                       $oFiltros->oElementoAvaliacao->getOrdemSequencia()
                                                                      );

    $oDadosAluno->aDisciplinas[] = $oDadosDisciplina;

  }
  $aAlunosImpressao[] = $oDadosAluno;
}


/** ***************************************************************************************************************** *
 ** ************************************** IMPRESSÃO DOS DADOS ****************************************************** *
 ** ***************************************************************************************************************** */
$lAdicionaPagina    = true;
$lImprimeMeioPagina = false;

foreach ($aAlunosImpressao as $oAluno) {

  $lImprimeCabecalho = true;

  if ($lAdicionaPagina || $pdf->GetY() > 150) {

    $pdf->AddPage();
    $lAdicionaPagina = false;
  }
  $iPaginaInicial = $pdf->PageNo();
  cabecalhoRelatorio($pdf, $oFiltros, $oAluno, $lImprimeMeioPagina);
  $iYInicialImpressaoAluno = $pdf->GetY();

  $pdf->Line($pdf->GetX(), $iYInicialImpressaoAluno, $oFiltros->iLarguraRetangulo, $pdf->GetY());
  $pdf->Line($pdf->GetX(), $iYInicialImpressaoAluno, $pdf->GetX(), $pdf->GetY() + $oFiltros->iAlturaLinhaPadrao);
  $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicialImpressaoAluno, $oFiltros->iLarguraRetangulo, $pdf->GetY() + $oFiltros->iAlturaLinhaPadrao);
  /**
   * Imprime o valor do Resultado Final de acordo com a situacao da matricula
   */
  $pdf->SetX(20);
  $sMensagemResultadoFinal = "Resultado Final em {$oFiltros->oEtapa->getNome()}: {$oAluno->sResultadoFinal} ";
  $pdf->cell($oFiltros->iLarguraLinha, $oFiltros->iAlturaLinhaPadrao, $sMensagemResultadoFinal, 0, 1, "L");

  $iLarguraLinha = $oFiltros->iLarguraLinha - 8;
  foreach ($oAluno->aDisciplinas as $oDisciplina) {

    /**
     * Calculo para saber se devemos quebrar página
     */
    $iLinhasParecerPadronizado = 0;
    if (!empty($oDisciplina->oParecer->sParecerPadronizado)) {

      if ($oFiltros->sPadrao == 'C') {
        $iLinhasParecerPadronizado = $pdf->NbLines($iLarguraLinha, $oDisciplina->oParecer->sParecerPadronizado);
      } else {
        $iLinhasParecerPadronizado = count(explode("**", $oDadosDisciplina->oParecer->sParecerPadronizado));
      }
      $iLinhasParecerPadronizado += 2;  //linhas de header do parecer padronizado
    }
    $iLinhasParecer = 0;
    if (!empty($oDisciplina->oParecer->sParecer)) {
      $iLinhasParecer = $pdf->NbLines($iLarguraLinha, $oDisciplina->oParecer->sParecer);
    } else {
      $iLinhasParecer = 5;
    }

    $iLinhasAproximado       = $iLinhasParecer + $iLinhasParecerPadronizado;
    $iAlturaAproximadaQuadro = $pdf->GetY() + 25 + ($iLinhasAproximado * $oFiltros->iAlturaLinhaPadrao);

    $lPaginaNova = false;
    if (($iAlturaAproximadaQuadro > $oFiltros->iAlturaDeQuebraPagina) || ($pdf->GetY() > 250)) {

      $pdf->Line($pdf->GetX(), $oFiltros->iAlturaDeQuebraPagina ,  $oFiltros->iLarguraRetangulo, $oFiltros->iAlturaDeQuebraPagina);
      $pdf->Line($pdf->GetX(), $pdf->GetY() , $pdf->GetX(), $oFiltros->iAlturaDeQuebraPagina);
      $pdf->Line($oFiltros->iLarguraRetangulo, $pdf->GetY() , $oFiltros->iLarguraRetangulo, $oFiltros->iAlturaDeQuebraPagina);
    }
    /**
     * Imprime Informações Disciplina e Parecer
     */
    informacoesAlunoDisciplina($pdf, $oFiltros, $oDisciplina, $lPaginaNova);
    imprimePareceres($pdf, $oFiltros, $oDisciplina);
  }

  /**
   * Calculo para saber se as observações caberão na página atual
   */
  $iLinhasObservacao     = $pdf->NbLines($iLarguraLinha, $oFiltros->sObservacao);
  $iAlturaAproximadaObs  = $pdf->GetY() + ($iLinhasObservacao * $oFiltros->iAlturaLinhaPadrao);
  $iAlturaAproximadaObs += 20; //Altura da assinatura se houver

  $lPaginaNova = false;
  if ($iAlturaAproximadaObs > $oFiltros->iAlturaDeQuebraPagina) {

    $lPaginaNova = true;
    $pdf->Line($pdf->GetX(), $pdf->GetY() + 2 , $oFiltros->iLarguraRetangulo, $pdf->GetY() + 2);
    $pdf->AddPage();
  }
  imprimeObservacoes($pdf, $oFiltros, $lPaginaNova);

  $iYAposImprimirDados = $pdf->GetY();
  $iPaginaFinal = $pdf->PageNo();
  if ($iPaginaFinal - $iPaginaInicial == 0) {
    $pdf->Line($pdf->GetX(), $iYAposImprimirDados + 2 , $oFiltros->iLarguraRetangulo, $iYAposImprimirDados + 2);
  }


  $lAdicionaPagina    = true;
  $lImprimeMeioPagina = false;
  if ($iYAposImprimirDados < 160) {

    $lAdicionaPagina    = false;
    $lImprimeMeioPagina = true;
  }
  if ($iPaginaFinal - $iPaginaInicial > 0) {

    $lAdicionaPagina    = true;
    $lImprimeMeioPagina = false;
    $pdf->Rect(10, 8, 190, $oFiltros->iAlturaDeQuebraPagina);
  }
}



/**
 * Imprime as informacoes do cabecalho
 * @param PDF $pdf
 * @param stdClass $oFiltros
 */
function cabecalhoRelatorio(FpdfMultiCellBorder $pdf, $oFiltros, $oAluno, $lImprimeMeioPagina) {


  /**
   * Variaveis de controle para impressão do cabecalho padrao, utilizado em libdocumento
   */
  $d   = 0;
  $y1  = 9;
  $y2  = 14;
  $y3  = 18;
  $y4  = 22;
  $y5  = 26;
  $y6  = 30;
  $y7  = 6;
  $y8  = 5;
  $y9  = 35;
  $y10 = 33;
  $y11 = 63;
  $y12 = 3;
  $y13 = 12;
  $y14 = 43;
  $m0  = 9;  // altura que será escrito o nome do departamento
  $m1  = 14; // altura que será escrito o nome da escola
  $m2  = 18; // altura que será escrito o endereço
  $m3  = 22; // altura que será escrito a cidade
  $m4  = 26; // altura que será escrito o telefone da escola
  $m5  = 30; // altura que será escrito o email + site da escola
  $m6  = 35; // altura da linha do cabeçalho
  $m7  = 3;  // altura da linha dentro do quadro das legendas
  $m8  = 7;  // altura que começa a desenhar o quadro  das legendas
  $a   = 5;
  $f   = 190;
  $r   = 32;
  $margemesquerda  = $pdf->lMargin;

  /**
   * Verificamos se estamos imprimindo o se estamos imprimindo o 2º aluno da pagina e se neste caso
   */
  if ($lImprimeMeioPagina) {

  	$m0  = 9  + 150; // altura que será escrito o nome do departamento
    $m1  = 14 + 150; // altura que será escrito o nome da escola
    $m2  = 18 + 150; // altura que será escrito o endereço
    $m3  = 22 + 150; // altura que será escrito a cidade
    $m4  = 26 + 150; // altura que será escrito o telefone da escola
    $m5  = 30 + 150; // altura que será escrito o email + site da escola
    $m6  = 35 + 150; // altura da linha do cabeçalho
    $m7  = 3;  // altura da linha dentro do quadro das legendas
    $m8  = 7 + 150 ;  // altura que começa a desenhar o quadro  das legendas

    $pdf->SetY(158);
  }



  /**
   * *************************************
   * DADOS A SEREM IMPRESSOS NO CABECALHO
   * *************************************
   */
  $oCabecalho          = new stdClass();
  $oCabecalho->sDDD    = "";
  $oCabecalho->iNumero = "";
  $sLogoInstit         = $oFiltros->oTurma->getEscola()->getLogo();
  $sLogoEscola         = $oFiltros->oTurma->getEscola()->getLogoEscola();
  $nome                = $oFiltros->oTurma->getEscola()->getDepartamento()->getNomeDepartamento()." - ".$oFiltros->oTurma->getEscola()->getUf();
  $ed52_i_ano          = $oFiltros->oTurma->getCalendario()->getAnoExecucao();
  $ed57_c_descr        = $oFiltros->oTurma->getDescricao();
  $ed11_c_descr        = $oFiltros->oEtapa->getNome();
  $ed15_c_nome         = $oFiltros->oTurma->getTurno()->getDescricao();
  $ed10_c_abrev        = $oFiltros->oTurma->getBaseCurricular()->getCurso()->getEnsino()->getAbreviatura();
  $ed29_i_codigo       = $oFiltros->oTurma->getBaseCurricular()->getCurso()->getCodigo();
  $ed29_c_descr        = $oFiltros->oTurma->getBaseCurricular()->getCurso()->getNome();
  $ed52_c_descr        = $oFiltros->oTurma->getCalendario()->getDescricao();
  $ruaescola           = $oFiltros->oTurma->getEscola()->getEndereco();
  $numescola           = $oFiltros->oTurma->getEscola()->getNumeroEndereco();
  $bairroescola        = $oFiltros->oTurma->getEscola()->getBairro();
  $cidadeescola        = $oFiltros->oTurma->getEscola()->getMunicipio();
  $estadoescola        = $oFiltros->oTurma->getEscola()->getUf();
  $emailescola         = $oFiltros->oTurma->getEscola()->getEmail();
  $sNomeEscola         = $oFiltros->oTurma->getEscola()->getNome();
  $url                 = $oFiltros->oTurma->getEscola()->getUrl();

  /**
   * Buscamos o primeiro registro de telefone cadastrado para a escola, caso exista
  */
  $aTelefones = $oFiltros->oTurma->getEscola()->getTelefones();
  if (count($aTelefones) > 0) {

    $oCabecalho->sDDD    = !empty($aTelefones[0]->iDDD) ? " ({$aTelefones[0]->iDDD}) " : "";
    $oCabecalho->iNumero = !empty($aTelefones[0]->iNumero) ? "{$aTelefones[0]->iNumero}" : "";
  }
  $DadosCabecalho  = $oFiltros->oTurma->getEscola()->getNome().$oCabecalho->sDDD.$oCabecalho->iNumero;
  $iTelefoneEscola = 1;

  /**
   * Setamos o periodo a ser apresentado no cabecalho, nome do aluno e condigo do mesmo
  */
  $periodoselecionado = $oFiltros->oElementoAvaliacao->getDescricao();
  $ed47_v_nome        = $oAluno->sNome;
  $ed47_i_codigo      = $oAluno->iCodigo;

  /**
   * Dados do cabeçalho padrão, quando não houver cabeçalho configurado
   */
  $head1 = "BOLETIM POR PARECER DESCRITIVO $periodoselecionado";
  $head2 = "Aluno: $ed47_v_nome - $oAluno->sResultadoFinal";
  $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4 = "Calendário: $ed52_c_descr";
  $head5 = "Etapa: $ed11_c_descr          " . "Turma: $ed57_c_descr"   ;
  $head6 = "Matrícula: {$oAluno->iMatricula}";

  $pdf->setfillcolor(225);
  $pdf->SetFont('arial','b',7);

  /**
   * Cria a instancia do documento que imprimira os dados do cabecalho de acordo com as variaveis setadas
   */
  $oLibDocumento = new libdocumento(5001,null);

  if ( $oLibDocumento->lErro ) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
  }

  $aParagrafo = $oLibDocumento->getDocParagrafos();
  foreach ($aParagrafo as $oParagrafo) {
    eval($oParagrafo->oParag->db02_texto);
  }
}

/**
 * Imprime as informacoes de dia letivo, faltas e disciplina
 * @param FpdfMultiCellBorder $pdf
 * @param stdClass $oFiltros
 */
function informacoesAlunoDisciplina(FpdfMultiCellBorder $pdf, $oFiltros, $oDadosDisciplina, $lPaginaNova) {

  $iYInicial = $pdf->GetY();

   if ($lPaginaNova) {
     $pdf->Line($pdf->GetX(), $iYInicial, $oFiltros->iLarguraRetangulo, $iYInicial);
   }
  /**
   * Imprime uma linha em branco com fundo cinza
   */
  $pdf->setfillcolor(225);
  $pdf->SetFont('times','',8);

  /**
   * Imprime o valor das aulas dadas. Valida a forma de calculo da carga horaria da Turma, podendo ser impresso:
   * Quando 1: Aulas Dadas
   * Quando 2: Dias Letivos
  */
  $pdf->SetXY(20, $pdf->GetY() + 2);
  $pdf->SetFont('arial', 'b', 10);
  $sMensagemAulas = "Aulas Dadas: {$oDadosDisciplina->iTotalAulas}";
  if ($oFiltros->oTurma->getFormaCalculoCargaHoraria() == 2) {
    $sMensagemAulas = "Dias Letivos: {$oDadosDisciplina->iTotalAulas}";
  }
  $pdf->cell($oFiltros->iLarguraLinha / 2, $oFiltros->iAlturaLinhaPadrao, $sMensagemAulas, 0, 0, "L");

  /**
   * Imprime a linha com o numero de faltas total
  */
//   $pdf->SetX(20);
  $sNumeroFaltas = "Nº de Faltas: {$oDadosDisciplina->iTotalFaltas}";
  $pdf->cell($oFiltros->iLarguraLinha / 2, $oFiltros->iAlturaLinhaPadrao, $sNumeroFaltas, 0, 1, "L");

  /**
   * Imprime a linha com o nome da disciplina selecionada. Caso seja parecer unico ($oFiltros->lParecerUnico is true),
   * mostra PARECER UNICO
  */
  $pdf->SetXY(14, $pdf->GetY() + 2);
  $sDisciplina = $oFiltros->lParecerUnico ? 'PARECER ÚNICO' : $oDadosDisciplina->sDisciplina;
  $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, $sDisciplina, 1, 1, "L", 1);

  $iYFinal = $pdf->GetY();
  $pdf->Line($pdf->GetX(), $iYInicial, $pdf->GetX(), $iYFinal);
  $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicial, $oFiltros->iLarguraRetangulo, $iYFinal);
}

/**
 * Imprime os pareceres
 * @param FpdfMultiCellBorder $pdf
 * @param stdClass $oFiltros
 */
function imprimePareceres(FpdfMultiCellBorder $pdf, $oFiltros, $oDadosDisciplina) {

  $iYInicial = $pdf->GetY();

  /**
   * Caso exista parecer padronizado setado, imprimimos cada parecer de acordo com a forma desejada ($sPadrao)
   * Senao existir, nao apresenta as linhas nem titulo de parecer padronizado
   */
  if (!empty($oDadosDisciplina->oParecer->sParecerPadronizado)) {

    $pdf->SetX(14);
    $pdf->setfillcolor(245);
    $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, "Parecer Padronizado:", 1, 1, "L", 1);
    $pdf->SetX(14);
    $pdf->SetFont('arial', '', 10);

    if ($oFiltros->sPadrao == 'C') {
      $pdf->MultiCell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, $oDadosDisciplina->oParecer->sParecerPadronizado, 1, "L");
    } else {

      $pdf->SetFont('arial', 'b', 10);
      $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, "Seq - Parecer => Legenda", 1, 1, "L");
      $aPareceres = explode("**", $oDadosDisciplina->oParecer->sParecerPadronizado);

      $pdf->SetFont('arial', '', 10);
      foreach ($aPareceres as $sParecer) {

        $pdf->SetX(14);
        $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, trim($sParecer), 1, 1, "L");
      }
    }
  }

  /**
   Fecha os lados do parecer padronizado
   */
  $iYFinal = $pdf->GetY();
  $pdf->Line($pdf->GetX(), $iYInicial, $pdf->GetX(), $iYFinal + 2);
  $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicial, $oFiltros->iLarguraRetangulo, $iYFinal + 2);

  $pdf->SetX(14);
  $pdf->SetFont('arial', 'b', 10);
  $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, "Parecer Descritivo:", 1, 1, "L", 1);

  /**
   * Calculo para fechar os lados do parecer descritivos
   */
  $iLinhasParecer      = $pdf->NbLines($oFiltros->iLarguraLinha - 8, $oDadosDisciplina->oParecer->sParecer);
  $iAlturaTotal        = $oFiltros->iAlturaLinhaPadrao * $iLinhasParecer;
  $iAlturaQuebraPagina = $oFiltros->iAlturaDeQuebraPagina;
  $lQuebrouPagina      = false;
  if ($iAlturaTotal > $iAlturaQuebraPagina) {

    $lQuebrouPagina = true;
    $pdf->Line($pdf->GetX(), $iYInicial, $pdf->GetX(), $iAlturaQuebraPagina);
    $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicial, $oFiltros->iLarguraRetangulo, $iAlturaQuebraPagina);
  }

  /**
   * Caso exista parecer descritivo setado, imprimimos o que foi informado. Caso contrario, imprimimos apenas linhas sem
   * valores
   */
  if (!empty($oDadosDisciplina->oParecer->sParecer)) {

    $pdf->SetX(14);
    $pdf->SetFont('arial', '', 10);
    $pdf->MultiCell($oFiltros->iLarguraLinha - 8,
                    $oFiltros->iAlturaLinhaPadrao,
                    "  ".$oDadosDisciplina->oParecer->sParecer,
                    1,
                    "L");

  } else {

    /**
     * Quando nao houver um parecer descritivo, pegamos a posicao do Y para calcular a area do retangulo a ser impresso
     */
    $iYParecerVazio = $pdf->GetY();
    for ($iContador = 0; $iContador < 4; $iContador++) {

      $pdf->SetX(14);
      $pdf->SetFont('arial', '', 10);
      $sLinhas = "";
      $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, str_pad($sLinhas, 90, "_"), 0, 1, "C");
    }
    $pdf->Rect(14, $iYParecerVazio, 182, 18);
    $pdf->setY($pdf->GetY() + 2);
  }

  $iYFinal = $pdf->GetY();
  if ($lQuebrouPagina) {
    $iYInicial = 10;
  }
  $pdf->Line($pdf->GetX(), $iYInicial, $pdf->GetX(), $iYFinal + 2);
  $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicial, $oFiltros->iLarguraRetangulo, $iYFinal + 2);
}

/**
 * Imprime as observacoes
 * @param FpdfMultiCellBorder $pdf
 * @param stdClass $oFiltros
 */
function imprimeObservacoes(FpdfMultiCellBorder $pdf, $oFiltros, $lPaginaNova) {

  $iYInicial = $pdf->GetY();

  if ($lPaginaNova) {
    $pdf->Line($pdf->GetX(), $iYInicial, $oFiltros->iLarguraRetangulo, $iYInicial);
  }

  /**
   * Imprimimos as observacoes de acordo com o que foi informado na tela de impressao
   */
  $pdf->setfillcolor(225);
  $pdf->SetFont('arial', 'b', 10);
  $pdf->SetXY(14, $pdf->GetY() + 4);
  $pdf->cell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, "Observações:", 1, 1, "L", 1);

  $pdf->SetX(14);
  $pdf->SetFont('arial', '', 7);
  $pdf->MultiCell($oFiltros->iLarguraLinha - 8, $oFiltros->iAlturaLinhaPadrao, $oFiltros->sObservacao, 1, "L");

  /**
   * Caso tenha sido marcada a opcao de imprimir a assinatura do conselheiro, acrescentamos essa linha ao final do
     * relatorio
  */
  if ($oFiltros->lAssinaturaConselheiro) {

    $pdf->SetFont('arial', '', 7);
    $pdf->SetY($pdf->GetY() + 4);
    $sProfessorConselheiro = '';

    if ($oFiltros->oTurma->getProfessorConselheiro() && $oFiltros->oTurma->getProfessorConselheiro()->getNome() != '') {
      $sProfessorConselheiro = "Professor ".$oFiltros->oTurma->getProfessorConselheiro()->getNome();
    }
    $sLinhas = "";
    $pdf->cell($oFiltros->iLarguraLinha, $oFiltros->iAlturaLinhaPadrao, str_pad($sLinhas, 50, "_"), 0, 1, "C");
    $pdf->cell($oFiltros->iLarguraLinha, $oFiltros->iAlturaLinhaPadrao, $sProfessorConselheiro, 0, 1, "C");
  }

  $iYFinal = $pdf->GetY();
  $pdf->Line($pdf->GetX(), $iYInicial, $pdf->GetX(), $iYFinal + 2);
  $pdf->Line($oFiltros->iLarguraRetangulo, $iYInicial, $oFiltros->iLarguraRetangulo, $iYFinal + 2);
}

$pdf->Output();
?>