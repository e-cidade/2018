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

require_once(modification( "fpdf151/pdf.php" ));

/**
 * Classe base para os relat�rios de Di�rio de classe
 *
 * @package   educacao
 * @subpackge relatorio
 * @author    Andrio Costa <andrio.costa@dbseller.com.br>
 * @version   $Revision: 1.26 $
 */
class RelatorioDiarioClasseBase extends PDF {

  /**
   * Inst�ncia de Turma
   * @var Turma
   */
  protected $oTurma;

  /**
   * Inst�ncia de Etapa
   * @var Etapa
   */
  protected $oEtapa;

  /**
   * Inst�ncia de uma AvaliacaoPeriodica
   * @var AvaliacaoPeriodica
   */
  protected $oAvaliacaoPeriodica;

  /**
   * Cole��o das reg�ncias que ser�o impressas
   * @var Regencia[]
   */
  protected $aRegencias;

  /**
   * Controla se devemos exibir a coluna de faltas
   * @var bool
   */
  protected $lExibirFaltas = false;

  /**
   * Controla se devemos exibir alunos que trocaram de turma no relat�rio
   * @var bool
   */
  protected $lExibirTrocaTurma     = false;

  /**
   * Controla se devemos exibir a coluna de avalia��o
   * @var bool
   */
  protected $lExibirAvaliacao      = false;

  /**
   * Se false n�o exibe a descri��o do per�odo de avalia��o no subcabe�alho
   * @var bool
   */
  protected $lExibirLinhaDataPeriodo = true;

  /**
   * Controla se devemos exibir a data do per�odo de avalia��o informado.
   * Se false exibe o nome do per�odo de avalia��o seguido de __/__/____
   * @var bool
   */
  protected $lExibirDataPeriodo    = true;

  /**
   * Controla se devemos exibir os pontos na grade
   * @var bool
   */
  protected $lExibirPontos         = true;

  /**
   * Controla a exibi��o da idade do aluno ao lado da coluna nome
   * @var bool
   */
  protected $lExibirIdade = false;

  /**
   * Controla a exibi��o da etapa em que o aluno esta matriculado aluno ao lado da coluna nome
   * @var bool
   */
  protected $lExibirEtapa = false;

  /**
   * Controla se devemos exibir somente alunos matriculados.
   * Se false traz todas outras situa��es, exceto Troca de Turma, que � controlada pelo par�metro $lExibirTrocaTurma
   * @var bool
   */
  protected $lSomenteMatriculados  = true;

  /**
   * Controla como devemos buscar as informa��es da grade.
   * @exemple true  - imprime a grade em branco, sem se preocupar com as faltas do aluno
   *          false - valida se o aluno teve falta em cada dia do periodo de avalia��o, imprimindo um "f" na grade.
   *                  imprimir somente os dias de aula da disciplina selecionada para cada per�odo de aula
   * @var bool
   */
  protected $lRegistroManual  = true;

  /**
   * Sempre quando $lRegistroManual for true, devemos considerar como foi informado o par�metro $lInformarDiasLetivos
   * @exemple true  - calcula o n�mero de colunas de acordo com os dias de aula do per�odo de avalia��o
   *          false - calcula o n�mero de colunas de acordo com o par�metro $iDiasLetivos
   * @var bool
   */
  protected $lInformarDiasLetivos  = true;

  /**
   * Define se devemos tratar o dados como turma globalizada
   * @var bool
   */
  protected $lTurmaGlobalizada = false;

  /**
   * Usada no cabe�alho do relat�rio, se true ir� apresentar ambas etapas
   * @var bool
   */
  protected $lTurmaMultEtapa = false;

  /**
   * Quando selecionado Turma globalizada, temos que imprimir na grade as disciplinas que controlam avalia��o.
   * @var Regencia[]
   */
  private $aRegenciasGlobalizadasQueControlamAvaliacao = array();

  /**
   * Regencia Atual na que a turma se encontra
   * @var Regencia
   */
  protected $oRegenciaAtual;

  /**
   * Usado para calcular o n�mero de colunas da grade sempre que:
   *   $lRegistroManual = true e $lInformarDiasLetivos = false
   * @var int
   */
  protected $iDiasLetivos;

  /**
   * Lista dos alunos organizados pela disciplina e p�gina
   * aAlunosOrganizados[codigoRegencia][pagina][] alunos{}
   *
   * @var array
   */
  protected $aAlunosOrganizados = array();

  /**
   * Identifica se o per�odo selecionado � uma Recupera��o
   * @var boolean
   */
  protected $lPeriodoDeRecuperacao = false;

  /** **************************************** *****************************
   * As vari�veis abaixo s�o pr� configura��es para impress�o do relat�rio *
   ** *************************************** **************************** */

  protected $sTituloColunaNome        = "Nome  do Aluno";
  protected $iLarguraColunaNumero     = 5;
  protected $iLarguraColunaPadrao     = 5;
  protected $iLarguraColunaNome       = 60;
  protected $iNumeroColunasAvaliacao  = 4;
  protected $iLarguraPagina           = 281;
  protected $iNumeroAlunosPagina      = 35;
  protected $iTamanhoFonteGrade       = 6;
  protected $iNumeroMinimoColunaFalta = 30;

  protected $iLarguraColunaGrade;

  /**
   * Situa��es de Transferencia
   * @var array
   */
  protected $aSituacaoTransferido = array('TRANSFERIDO FORA', 'TRANSFERIDO REDE');
  /**
   * Matriculas de Alunos na turma
   * @var Matricula[]
   */
  protected $aMatriculas = array();


  /**
   * Identifica, em uma turma de turno INTEGRAL, a poss�bilidade de matricular alunos no turno de referencia
   * @var boolean
   */
  protected $lPossuiMatriculaPorTurnoReferencia = false;

  /**
   * Cont�m uma estrutura organizada dos cabe�alho
   * @var array
   */
  protected $aEstruturaCabecalho =array();

  public function __construct( Turma $oTurma, Etapa $oEtapa, AvaliacaoPeriodica $oAvaliacaoPeriodica ) {

    parent::fpdf('L');
    $this->oTurma = $oTurma;

    $lEnsinoInfantil = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->isInfantil();
    if ( $lEnsinoInfantil && $oTurma->getTurno()->isIntegral() ) {
      $this->lPossuiMatriculaPorTurnoReferencia = true;
    }

    $this->oEtapa              = $oEtapa;
    $this->oAvaliacaoPeriodica = $oAvaliacaoPeriodica;
    $this->aMatriculas         = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $this->SetMargins(8, 10);
    $this->SetAutoPageBreak(true, 10);

    if ( $this->oAvaliacaoPeriodica->isRecuperacao() ){
      $this->lPeriodoDeRecuperacao = true;
    }
  }

  /**
   * Adiciona uma reg�ncia para impress�o
   * @param Regencia $oRegencia
   */
  public function adicionarRegencias( Regencia $oRegencia ) {
    $this->aRegencias[] = $oRegencia;
  }

  /**
   * Define n�mero de colunas da grade
   * @param int $iDiasLetivos
   */
  public function setDiasLetivos($iDiasLetivos) {
    $this->iDiasLetivos = $iDiasLetivos;
  }

  /**
   * Define se devemos exibir a data do per�odo de avalia��o informado.
   * @param boolean $lExibirDataPeriodo
   */
  public function setExibirDataPeriodo($lExibirDataPeriodo) {
    $this->lExibirDataPeriodo = $lExibirDataPeriodo;
  }

  /**
   * Define se devemos exibir os pontos na grade
   * @param boolean $lExibirPontos
   */
  public function setExibirPontos($lExibirPontos) {
    $this->lExibirPontos = $lExibirPontos;
  }

  /**
   * Define se devemos exibir alunos que trocaram de turma no relat�rio
   * @param boolean $lExibirTrocaTurma
   */
  public function setExibirTrocaTurma($lExibirTrocaTurma) {
    $this->lExibirTrocaTurma = $lExibirTrocaTurma;
  }

  /**
   * Define como ser� calculado as colunas da grade.
   * S� � considerada se $lRegistroManual for TRUE
   * @param boolean $lInformarDiasLetivos
   */
  public function setInformarDiasLetivos($lInformarDiasLetivos) {
    $this->lInformarDiasLetivos = $lInformarDiasLetivos;
  }

  /**
   * Define se devemos buscar as informa��es do Lan�amento da Frequ�ncia/Conte�do ou se ser� manual
   * @param boolean $lRegistroManual
   */
  public function setRegistroManual($lRegistroManual) {
    $this->lRegistroManual = $lRegistroManual;
  }

  /**
   * Define se ser� listado apenas alunos com situa��o MATRICULADO
   * @param boolean $lSomenteMatriculados
   */
  public function setSomenteMatriculados($lSomenteMatriculados) {
    $this->lSomenteMatriculados = $lSomenteMatriculados;
  }

  /**
   * Define a AvaliacaoPeriodica
   * @param AvaliacaoPeriodica $oAvaliacaoPeriodica
   */
  public function setAvaliacaoPeriodica(AvaliacaoPeriodica $oAvaliacaoPeriodica) {
    $this->oAvaliacaoPeriodica = $oAvaliacaoPeriodica;
  }

  /**
   * Define a etapa
   * @param Etapa $oEtapa
   */
  public function setEtapa(Etapa $oEtapa) {
    $this->oEtapa = $oEtapa;
  }

  /**
   * Define a turma
   * @param Turma $oTurma
   */
  public function setTurma(Turma $oTurma) {
    $this->oTurma = $oTurma;
  }

  /**
   * Atribui a Regencia atual da turma a propriedade oRegenciaAtual
   * @param Regencia $oRegencia
   */
  public function setRegenciaAtual( Regencia $oRegencia ) {
    $this->oRegenciaAtual = $oRegencia;
  }

  /**
   * Diz para o Diario de Classe que devemos calcular o modelo como disciplina globalizada
   * @param $lTurmaGlobalizada
   */
  public function setTurmaGlobalizada( $lTurmaGlobalizada ) {
    $this->lTurmaGlobalizada= $lTurmaGlobalizada;
  }

  /**
   * Define se devemos exibir a descri��o do per�odo de avalia��o no subcabe�alho
   * @param $lExibirLinhaDataPeriodo
   */
  public function setExibirLinhaDataPeriodo( $lExibirLinhaDataPeriodo ) {
    $this->lExibirLinhaDataPeriodo = $lExibirLinhaDataPeriodo;
  }

  /**
   * Define se devemos exibir a idade do aluno ao lado do nome do aluno
   * @param bool $lExibirIdade
   */
  protected function setExibirIdade( $lExibirIdade ) {
    $this->lExibirIdade = $lExibirIdade;
  }


  /**
   * Sobreescreve o m�todo Header do model PDF para que monte um cabe�alho para os novos diarios de classe
   */
  public function Header() {

    $sDocente = "";

    $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
    $sImagem      = $oInstituicao->getImagemLogo();

    $this->Image("imagens/files/{$sImagem}",11,9,13);
    foreach ( $this->oRegenciaAtual->getDocentes() as $oDocente) {

      $sDocente = $oDocente->getNome();
      break;
    }

    $iPosicaoX     = 25;
    $iAlturaLinha  = 4;
    $iTamanhoLinha = 120;

    $this->SetFont('Arial', 'B', '7');
    $this->SetXY($iPosicaoX,8);

    $sNomeEscola       = $this->oTurma->getEscola()->getNome();
    $iCodigoReferencia = $this->oTurma->getEscola()->getCodigoReferencia();

    if ( $iCodigoReferencia != null ) {
      $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
    }

    $sDepartamento = $this->oTurma->getEscola()->getDepartamento()->getInstituicao()->getDescricao();
    $this->Cell($iTamanhoLinha, $iAlturaLinha, $sDepartamento, 0, 1, 'L');
    $this->SetX($iPosicaoX);
    $this->Cell($iTamanhoLinha, $iAlturaLinha, $sNomeEscola, 0, 1, 'L');

    $this->SetFont('Arial', '', '7');
    $this->SetXY($iPosicaoX, 24);
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Cidade: {$this->oTurma->getEscola()->getMunicipio()}", 0, 0, 'L');

    $iPosicaoX     = 140;
    $iTamanhoLinha = 90;

    $this->SetXY($iPosicaoX,8);

    $sCurso = $this->oTurma->getBaseCurricular()->getCurso()->getNome();
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Curso: {$sCurso}", 0, 0, "L");
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Calend�rio: {$this->oTurma->getCalendario()->getDescricao()}", 0, 1, "L");

    $this->SetX($iPosicaoX);
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Turma: {$this->oTurma->getDescricao()}", 0, 0, "L");

    $sEtapa = $this->oEtapa->getNome();
    if ( $this->lTurmaMultEtapa ) {

      $aEtapaTurma = array();
      foreach ($this->oTurma->getEtapas() as $oEtapaTurma ) {
        $aEtapaTurma[] = $oEtapaTurma->getEtapa()->getNome();
      }

      $sEtapa = implode(" / ", $aEtapaTurma);
    }

    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Etapa: {$sEtapa}", 0, 1, "L");

    $this->SetX($iPosicaoX);

    $sPeriodo = $this->oAvaliacaoPeriodica->getPeriodoAvaliacao()->getDescricao();
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Per�odo: {$sPeriodo}", 0, 0, "L");

    $iTotalAulas = $this->oRegenciaAtual->getTotalDeAulasNoPeriodo( $this->oAvaliacaoPeriodica->getPeriodoAvaliacao() );
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Aulas Dadas: {$iTotalAulas}", 0, 1, "L");

    $this->SetX($iPosicaoX);

    $sDisciplina = $this->oRegenciaAtual->getDisciplina()->getNomeDisciplina();
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Disciplina: {$sDisciplina}", 0, 1, "L");
    $this->SetX($iPosicaoX);
    $this->Cell($iTamanhoLinha, $iAlturaLinha, "Regente: {$sDocente}", 0, 1, "L");

    $this->roundedrect( 8, 8, 280, 20, 2, '', '1234' );
  }


  /**
   * Retorna uma estrutura com os dados padr�o do cabe�alho:
   * -> Colunas :
   *    N� | Nome Aluno | Dias (separado pelos meses)
   *
   * @return array
   */
  protected function estruturaSubCabecalho() {

    if ( count($this->aEstruturaCabecalho) > 0) {
      return $this->aEstruturaCabecalho ;
    }

    $aTipoFrquenciaTurmaGlobalizadas = array('F', 'FA');

    $this->aSubCabecalho = array();
    $aEstrutura          = array();
    foreach ( $this->aRegencias as $oRegencia ) {

      if ( $this->lTurmaGlobalizada  && !in_array($oRegencia->getFrequenciaGlobal(), $aTipoFrquenciaTurmaGlobalizadas) ) {
        continue;
      }

      $oDadosEstrutura = $this->getDadosPadraoSubCabecalho();
      if ( $this->lRegistroManual ) {
        $aEstrutura[$oRegencia->getCodigo()] = $this->calcularColunaGradeRegistroManual($oDadosEstrutura);
      } else {

        $oEstruturaCalculada = $this->calcularColunaGradeRegistroControleFrequencia($oDadosEstrutura, $oRegencia );

        if ( empty($oEstruturaCalculada) ) {
          continue;
        }
        $aEstrutura[$oRegencia->getCodigo()] = $oEstruturaCalculada;
      }
    }

    $this->aEstruturaCabecalho = $aEstrutura;

    return $this->aEstruturaCabecalho ;
  }

  /**
   * StdClass com os dados padr�o de todos modelos de relat�rio
   * Definimos a largura das colunas por p�gina
   * @return stdClass
   */
  private function getDadosPadraoSubCabecalho() {

    $oDadosBasicos                       = new stdClass();
    $oDadosBasicos->iTamanhoGrade        = $this->getTamanhoGrade();
    $oDadosBasicos->iLarguraColunaNumero = $this->iLarguraColunaNumero;
    $oDadosBasicos->iLarguraColunaNome   = $this->iLarguraColunaNome;
    $oDadosBasicos->iNumeroColunasVazias = 0;
    $oDadosBasicos->aMeses               = array();

    return $oDadosBasicos;
  }

  /**
   * Calcula a estrutura do SubCab�alho quando informado para controlar por frequ�ncia
   * @param          $oDadosEstrutura
   * @param Regencia $oRegencia
   * @return stdClass
   */
  private function calcularColunaGradeRegistroControleFrequencia( $oDadosEstrutura, Regencia $oRegencia ) {

    $oPeriodoAvaliacao = $this->oAvaliacaoPeriodica->getPeriodoAvaliacao();
    $oGradeHorario     = new GradeHorario($this->oTurma, $this->oEtapa);
    $aDatasLetiva      = $oGradeHorario->getDiasDeAulaDaDisciplinaNoPeriodoDeAvaliacao($oRegencia->getDisciplina(), $oPeriodoAvaliacao);

    $aDiasOrganizados = array();

    foreach ($aDatasLetiva as $oDataLetiva) {
      // Para cada periodo temos que repetir o dia letivo
      foreach ($oDataLetiva->aPeriodoAula as $oPeriodoAula) {

        $oDataPeriodo           = new stdClass();
        $oDataPeriodo->oData    = $oDataLetiva->oData;
        $oDataPeriodo->iPeriodo = $oPeriodoAula->getPeriodoEscola()->getCodigo();
        $aDiasOrganizados[]     = $oDataPeriodo;
      }
    }

    /**
     * Se disciplina n�o tem Grade de hor�rio configurada, retorna null;
     */
    if (count($aDiasOrganizados) == 0) {
      return null;
    }

    $oDadosEstrutura->iNumeroColunas      = count($aDiasOrganizados);
    $oDadosEstrutura->iLarguraCelulaGrade = $oDadosEstrutura->iTamanhoGrade / $oDadosEstrutura->iNumeroColunas;

    if ($oDadosEstrutura->iNumeroColunas < $this->iNumeroMinimoColunaFalta ) {

      $oDadosEstrutura->iLarguraCelulaGrade  = $oDadosEstrutura->iTamanhoGrade / $this->iNumeroMinimoColunaFalta;
      $oDadosEstrutura->iNumeroColunasVazias = $this->iNumeroMinimoColunaFalta - $oDadosEstrutura->iNumeroColunas;
    }

    if ($oDadosEstrutura->iLarguraCelulaGrade > 5) {
      $oDadosEstrutura = $this->recalculaLarguraCelulaGrade( $oDadosEstrutura );
    }

    $oDadosEstrutura = $this->organizaDatasSubCabecalho( $aDiasOrganizados, $oDadosEstrutura );

    return $oDadosEstrutura;
  }


  /**
   * Calcula a estrutura do SubCab�alho quando informado para controlar manualmente
   * @param $oDadosEstrutura
   * @return stdClass
   */
  private function calcularColunaGradeRegistroManual( $oDadosEstrutura ) {

    $iTamanhoGrade = $oDadosEstrutura->iTamanhoGrade;
    $oDadosEstrutura->iLarguraCelulaGrade = 5;
    $oDadosEstrutura->aMeses = array();

    if ( $this->lInformarDiasLetivos ) {

      $oPeriodoAvaliacao = $this->oAvaliacaoPeriodica->getPeriodoAvaliacao();
      $aDatasCalendario  = $this->oTurma->getCalendario()->getDatasLetivoNoPeriodo( $oPeriodoAvaliacao );

      $aDatasLetivas = array();
      foreach ($aDatasCalendario as $oDataCalendario) {

        $oDataPeriodo           = new stdClass();
        $oDataPeriodo->oData    = $oDataCalendario;
        $oDataPeriodo->iPeriodo = null;
        $aDatasLetivas[]        = $oDataPeriodo;
      }

      $oDadosEstrutura->iNumeroColunas      = count($aDatasLetivas);
      $oDadosEstrutura->iLarguraCelulaGrade = $iTamanhoGrade / $oDadosEstrutura->iNumeroColunas;

      if ($oDadosEstrutura->iLarguraCelulaGrade > 5) {
        $oDadosEstrutura = $this->recalculaLarguraCelulaGrade( $oDadosEstrutura, count($aDatasLetivas) );
      }

      $oDadosEstrutura = $this->organizaDatasSubCabecalho( $aDatasLetivas, $oDadosEstrutura );

    } else {

      $oDadosEstrutura->iNumeroColunas      = $this->iDiasLetivos;
      $oDadosEstrutura->iLarguraCelulaGrade = $iTamanhoGrade / $this->iDiasLetivos;
      $oDadosEstrutura->aMeses              = array();

      if ($oDadosEstrutura->iLarguraCelulaGrade > 5) {
        $oDadosEstrutura = $this->recalculaLarguraCelulaGrade( $oDadosEstrutura );
      }
    }

    return $oDadosEstrutura;
  }

  /**
   * Organiza as Datas do per�odo de avalia��o, separando os meses e os dias
   * @param $aDatas           datas do per�odo de avaliacao
   * @param $oDadosEstrutura  estrutura das colunas da p�gina atual
   * @return stdClass
   */
  private function organizaDatasSubCabecalho( $aDatas, $oDadosEstrutura ) {

    foreach ( $aDatas as $oDataPeriodo ) {

      $oDia           = new stdClass();
      $oDia->iDia     = $oDataPeriodo->oData->getDia();
      $oDia->iPeriodo = $oDataPeriodo->iPeriodo;
      if ( !array_key_exists( $oDataPeriodo->oData->getMes(), $oDadosEstrutura->aMeses ) ) {

        $oDias          = new stdClass();
        $oDias->aDias   = array();
        $oDias->sMes    = $oDataPeriodo->oData->getMesExtenso($oDataPeriodo->oData->getMes());
        $oDias->aDias[] = $oDia;
        $oDadosEstrutura->aMeses[$oDataPeriodo->oData->getMes()] = $oDias;
        continue;
      }
      $oDadosEstrutura->aMeses[$oDataPeriodo->oData->getMes()]->aDias[] = $oDia;
    }

    return $oDadosEstrutura;
  }

  /**
   * Recalcula o tamanho da largura das celulas da grade para um tamanho m�ximo de 5 pt.
   * O excedente � colocado na coluna nome
   * @param $oDadosEstrutura
   * @return stdClass
   */
  protected function recalculaLarguraCelulaGrade( $oDadosEstrutura ) {

    $iSobra = $oDadosEstrutura->iLarguraCelulaGrade - 5;

    $oDadosEstrutura->iLarguraCelulaGrade = 5;
    $oDadosEstrutura->iLarguraColunaNome += $iSobra * ($oDadosEstrutura->iNumeroColunas + $oDadosEstrutura->iNumeroColunasVazias);
    $oDadosEstrutura->iTamanhoGrade      -= $iSobra * ($oDadosEstrutura->iNumeroColunas + $oDadosEstrutura->iNumeroColunasVazias);

    return $oDadosEstrutura;
  }

  /**
   * Calcula o disponivel para o calculo da grade
   * @return int
   */
  private function getTamanhoGrade() {

    $iTamanhoDisponivelGrade = $this->iLarguraPagina - $this->iLarguraColunaNome - $this->iLarguraColunaNumero;

    if ( $this->lExibirAvaliacao ) {
      $iTamanhoDisponivelGrade -= ($this->iNumeroColunasAvaliacao * $this->iLarguraColunaPadrao);
    }

    if ( $this->lExibirFaltas ) {
      $iTamanhoDisponivelGrade -= $this->iLarguraColunaPadrao;
    }

    if ( $this->lExibirAvaliacao || $this->lExibirFaltas ) {
      $iTamanhoDisponivelGrade -= $this->iLarguraColunaNumero;
    }

    if ($this->lTurmaGlobalizada ) {
      $iTamanhoDisponivelGrade -= (count( $this->getRegenciasQueControlamAvaliacao() ) * $this->iLarguraColunaPadrao);
    }

    return $iTamanhoDisponivelGrade;
  }

  /**
   * Retorna as disciplinas da turma na etapa selecionada que controlam avalia��o
   * @return Regencia[]
   */
  private function getRegenciasQueControlamAvaliacao() {

    if ( count( $this->aRegenciasGlobalizadasQueControlamAvaliacao) == 0) {

      foreach ( $this->oTurma->getDisciplinasPorEtapa($this->oEtapa) as $oRegencia ) {

        if ( $oRegencia->getFrequenciaGlobal() == 'F' ) {
          continue;
        }
        $this->aRegenciasGlobalizadasQueControlamAvaliacao[] = $oRegencia;
      }
    }
    return $this->aRegenciasGlobalizadasQueControlamAvaliacao;
  }

  /**
   * Escreve o subCabe�alho
   * @param $oEstrutura
   */
  protected function escreverSubCabecalho( $oEstrutura ) {

    $this->AddPage();
    $this->SetFont("arial", 'B', 8);
    $this->AliasNbPages();
    $this->ln(0.3);
    if ( $this->lExibirLinhaDataPeriodo ) {
      $this->imprimeLinhaDescricaoPeriodo();
    }

    $this->SetFont("arial", 'B', 7);
    $this->imprimeLinhaMeses($oEstrutura);

    $this->Cell($oEstrutura->iLarguraColunaNumero, 4, "N�", 1, 0, "C");
    $this->Cell($oEstrutura->iLarguraColunaNome - 10, 4, $this->sTituloColunaNome, 1, 0, "C");
    $this->Cell(10, 4, "Dia >", 1, 0, "C");

    if ( count( $oEstrutura->aMeses ) == 0 ) {

      for ($i = 0; $i < $oEstrutura->iNumeroColunas; $i++) {
        $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, "", 1);
      }
    } else {

      $this->SetFont("arial", '', 6);
      foreach ($oEstrutura->aMeses as $oMes ) {

        foreach ($oMes->aDias as $oDia) {
          $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, $oDia->iDia, 1, 0, "C");
        }
      }

      $this->escreverColunasFaltasEmBranco($oEstrutura);

    }

    $this->escreverColunaNumeroAluno();
    $this->escreverColunasAvaliacao(false);
    $this->escreverColunasDisciplinasGlobalizada(false);
    $this->escreverColunaFalta();
    $this->ln();
  }

  /**
   * Imprime a descri��o do per�odo de avalia��o do subCabe�alho
   */
  private function imprimeLinhaDescricaoPeriodo() {

    $sDataInicio = "___/___/_____";
    $sDataFim    = "___/___/_____";

    $oPeriodoAvaliacao = $this->oAvaliacaoPeriodica->getPeriodoAvaliacao();
    if ( $this->lExibirDataPeriodo ) {

      $oPeriodoCalendario = $this->oTurma->getCalendario()->getPeriodoCalendarioPorPeriodoAvaliacao($oPeriodoAvaliacao);
      $sDataInicio        = $oPeriodoCalendario->getDataInicio()->convertTo(DBDate::DATA_PTBR);
      $sDataFim           = $oPeriodoCalendario->getDataTermino()->convertTo(DBDate::DATA_PTBR);
    }

    $sPeriodo  = $oPeriodoAvaliacao->getDescricao();
    $sPeriodo .= " - {$sDataInicio} � {$sDataFim}";

    $this->SetFont("arial", 'B', 8);
    $this->SetFillColor(245);
    $this->ln(0.5);
    $this->Cell($this->iLarguraPagina, 4, $sPeriodo, '', 1, "C", 1 );
  }

  /**
   * Imprime a linha dos meses do subCabe�alho
   * @param $oEstrutura
   */
  private function imprimeLinhaMeses( $oEstrutura ) {

    $iPrimeiraColuna = $oEstrutura->iLarguraColunaNumero + $oEstrutura->iLarguraColunaNome - 10;
    $this->Cell($iPrimeiraColuna, 4, "", 1);
    $this->Cell(10, 4, "M�s >", 1);

    if ( count( $oEstrutura->aMeses ) == 0 ) {
      $this->Cell($oEstrutura->iLarguraCelulaGrade * $oEstrutura->iNumeroColunas, 4, "", 1);
    } else {

      foreach ($oEstrutura->aMeses as $iMes => $oMes ) {

        $iLarguraColunaMes = count($oMes->aDias) * $oEstrutura->iLarguraCelulaGrade;
        $this->Cell($iLarguraColunaMes, 4, $iMes, 1, 0, "C");
      }

      $this->escreverColunasFaltasEmBranco($oEstrutura);

    }

    $this->escreverColunaNumeroAluno("N�");
    $this->escreverColunasAvaliacao(true);
    $this->escreverColunasDisciplinasGlobalizada(true);
    $this->escreverColunaFalta("F");
    $this->Ln();
  }

  private function escreverColunasDisciplinasGlobalizada($lImpimeDescricao) {

    if ( $this->lTurmaGlobalizada ) {

      foreach ($this->getRegenciasQueControlamAvaliacao() as $oRegencia ) {

        $sDescricao = "";
        if ( $lImpimeDescricao ) {
          $sDescricao = $oRegencia->getDisciplina()->getAbreviatura();
        }
        $this->SetFont("arial", '', 6);
        $this->Cell ($this->iLarguraColunaPadrao, 4, $sDescricao, 1, 0, 'C');
        $this->SetFont("arial", 'B', 7);
      }
    }
  }

  /**
   * Imprime a coluna de Avalia��es
   * @param bool $lTitulo true  escreve o Label t�tulo
   *                      false escreve as colunas para lan�amento da avaliacao
   */
  private function escreverColunasAvaliacao ( $lTitulo = true ) {

    if ( $this->lExibirAvaliacao ) {

      if ( $lTitulo ) {

        $iLarguraColunaAvaliacao = $this->iNumeroColunasAvaliacao * $this->iLarguraColunaPadrao;
        $this->Cell($iLarguraColunaAvaliacao, 4, "Avalia��es", 1, 0, "C");
      } else {

        for ( $i = 0; $i < $this->iNumeroColunasAvaliacao; $i++ ) {
          $this->Cell($this->iLarguraColunaPadrao, 4, "", 1, 0, "C");
        }
      }
    }
  }

  /**
   * Escreve a coluna com o numero do aluno quando necessario
   * @param string $sValor pode ser tando o titulo da coluna numero do aluno como o pr�prio n� do aluno
   */
  private function escreverColunaNumeroAluno ( $sValor = '') {

    if ( $this->lExibirAvaliacao || $this->lExibirFaltas ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sValor, 1, 0, "C");
    }
  }

  /**
   * Escreve a coluna para informar o total de faltas
   * @param string $sTitulo titulo da Coluna
   */
  private function escreverColunaFalta($sTitulo = '') {

    if ( $this->lExibirFaltas ) {
      $this->Cell($this->iLarguraColunaPadrao, 4, $sTitulo, 1, 0, "C");
    }
  }

  /**
   * Organiza os alunos de acordo com a por disciplina e quebrando as p�ginas
   * Retorna uma estrutura no seguinte modelo:
   *
   * -> aAlunosOrganizados[codigoRegencia][iPagina][0] = {oAluno, oFaltas}
   *
   * Onde cada p�gina pode ter at� 35 alunos
   *
   * @return array
   */
  private function organizarListaAlunos() {

    if ( count($this->aAlunosOrganizados) > 0 ) {
      return $this->aAlunosOrganizados;
    }

    $aMatriculas = $this->aMatriculas;

    foreach ( $this->estruturaSubCabecalho() as $iRegencia => $oEstrutura ) {

      $oRegencia = RegenciaRepository::getRegenciaByCodigo($iRegencia);
      $iPagina   = 0;

      db_inicio_transacao();

      foreach ($aMatriculas as $iIndice => $oMatricula ) {

        // Usado metodo getDisciplinasPorDisciplina por causa das turmas com mais de uma etapa.
        $oDiarioDiscplina   = $oMatricula->getDiarioDeClasse()->getDisciplinasPorDisciplina($oRegencia->getDisciplina());

        /**
         * Se o per�odo for uma recupera��o, s� deve imprimir os alunos em Recupera��o e que estejam matriculados
         */
        if ( $this->lPeriodoDeRecuperacao ) {

          if ( !$oDiarioDiscplina->emRecuperacao() ) {
            continue;
          }

          if ( $oMatricula->getSituacao() != 'MATRICULADO' ) {
            continue;
          }
        }

        $oPeriodoAvaliacao  = $this->oAvaliacaoPeriodica->getPeriodoAvaliacao();
        $oAluno             = new stdClass();
        $oAluno->oMatricula = $oMatricula;
        $oAluno->aFaltas    = $oDiarioDiscplina->getFaltasPorPeriodoDeAvaliacao($oPeriodoAvaliacao);

        if ($iIndice % ($this->iNumeroAlunosPagina + 1) == 0) {
          $iPagina++;
        }
        $this->aAlunosOrganizados[$iRegencia][$iPagina][] = $oAluno;
      }
      db_fim_transacao();
    }
    return $this->aAlunosOrganizados;

  }

  /**
   * Retorna uma cole��o de alunos de acordo com o codigo da regencia informada
   * @param $iRegencia c�digo da reg�ncia
   * @return array
   */
  protected function getAlunos($iRegencia) {

    $oRegencia = RegenciaRepository::getRegenciaByCodigo($iRegencia);
    $aAlunos   = $this->organizarListaAlunos();

    if ( empty($aAlunos[$iRegencia]) ) {

      $sDisciplina = $oRegencia->getDisciplina()->getNomeDisciplina();

      $sMsgErro  = "N�o existem alunos para serem impressos na disciplina: <b>{$sDisciplina}</b>";
      $sMsgErro .= " no per�odo: <b>" . $this->oAvaliacaoPeriodica->getDescricao();
      $sMsgErro .= "</b>.<br>Remova a disciplina {$sDisciplina} da lista de impress�o.";
      throw new Exception( $sMsgErro );
    }

    return $aAlunos[$iRegencia];
  }

  /**
   * Validamos se o aluno atende a configura��o dos par�metros:
   * $lSomenteMatriculados
   * $lExibirTrocaTurma
   *
   * @param $oDadosAluno stclas com os dados do aluno avaliado
   * @return bool
   */
  protected function validaSituacaoAluno($oDadosAluno) {

    if ( ($oDadosAluno->oMatricula->getSituacao() == 'TROCA DE TURMA')
         && !$this->lExibirTrocaTurma) {
      return false;
    } elseif ( $this->lSomenteMatriculados &&
               !in_array($oDadosAluno->oMatricula->getSituacao(), array('TROCA DE TURMA', 'MATRICULADO'))) {
      return false;
    }

    return true;
  }


  /**
   * Escreve os dados dos alunos no di�rio de classe
   * @param $aAlunos    Lista de alunos de uma reg�ncia
   * @param $oEstrutura Dados da estrutuda de uma reg�ncia
   */
  protected function escreverCorpo( $aAlunos, $oEstrutura ) {

    foreach ( $aAlunos as $aAlunosPagina ) {

      $this->escreverSubCabecalho( $oEstrutura );

      $iAlunosImpressos = 0;
      foreach ( $aAlunosPagina as $oDadosAluno ) {

        if ( !$this->validaSituacaoAluno($oDadosAluno) ) {
          continue;
        }

        $this->SetFont("arial", '', $this->iTamanhoFonteGrade);
        $iClassificacao = $oDadosAluno->oMatricula->getNumeroOrdemAluno();
        $this->Cell($oEstrutura->iLarguraColunaNumero, 4, $iClassificacao, 1, 0, "C");

        $sNomeAluno = $this->getNomeAluno( $oDadosAluno->oMatricula );
        $this->Cell($oEstrutura->iLarguraColunaNome, 4, $sNomeAluno, 1, 0, "L");

        // Escreve a Idade do aluno ao lado do nome do aluno
        if ( $this->lExibirIdade && !$this->lExibirEtapa ) {

          $this->SetX( $this->GetX() - $this->iLarguraColunaPadrao );
          $this->Cell($this->iLarguraColunaPadrao, 4, $oDadosAluno->oMatricula->getAluno()->getIdade(), 1, 0, "C");
        }

        // Escreve a etapa ao lado da coluna do nome do aluno
        if ( !$this->lExibirIdade && $this->lExibirEtapa ) {

          $this->SetX( $this->GetX() - ($this->iLarguraColunaPadrao * 2)  );
          $this->Cell(($this->iLarguraColunaPadrao * 2), 4, $oDadosAluno->oMatricula->getEtapaDeOrigem()->getNomeAbreviado(), "TBR", 0, "C");
        }

        if ( $oDadosAluno->oMatricula->getSituacao() == 'MATRICULADO' ) {

          if ( !$this->validaAlunoAmparado($oDadosAluno->oMatricula, $oEstrutura) ) {
            $this->imprimirGradeFaltasAluno($oDadosAluno->aFaltas, $oEstrutura);
          }
        } else {
          $this->imprimeSituacaoAluno($oDadosAluno->oMatricula->getSituacao(), $oEstrutura->iTamanhoGrade);
        }

        $this->escreverColunaNumeroAluno($iClassificacao);
        $this->escreverColunasAvaliacao(false);
        $this->escreverColunasDisciplinasGlobalizada(false);
        $this->escreverColunaFalta();

        $this->ln();
        $iAlunosImpressos ++;
      }

      if ( $iAlunosImpressos < $this->iNumeroAlunosPagina ) {

        for ( $i = $iAlunosImpressos; $i < $this->iNumeroAlunosPagina; $i++) {

          $this->Cell($oEstrutura->iLarguraColunaNumero, 4, "", 1, 0, "C");
          $this->Cell($oEstrutura->iLarguraColunaNome,   4, "", 1, 0, "L");

          $this->imprimirCelulasGradeFaltaSemAlunos( $oEstrutura );

          $this->escreverColunaNumeroAluno();
          $this->escreverColunasAvaliacao(false);
          $this->escreverColunasDisciplinasGlobalizada(false);
          $this->escreverColunaFalta();

          $this->ln();
        }
      }

      $this->escreverAssinatura();
    }

  }

  /**
   * Imprime a Grade de Falta do aluno
   * @param $aFaltas    array com as faltas do aluno
   * @param $oEstrutura estrutura base do subCabe�alho
   */
  private function imprimirGradeFaltasAluno($aFaltas, $oEstrutura) {

    if ( count($oEstrutura->aMeses) == 0) {

      for ($i = 0; $i < $oEstrutura->iNumeroColunas; $i++) {

        $this->exibePontos($oEstrutura->iLarguraCelulaGrade);
        $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, "", 1);
      }

    } else {

      foreach ($oEstrutura->aMeses as $iMes =>  $oMes ) {

        foreach ($oMes->aDias as $oDia) {

          $sFalta = "";
          if ( !$this->lRegistroManual ) {

            foreach ( $aFaltas as $oFaltaAluno ) {

              if ( ($oFaltaAluno->getData()->getMes() == $iMes) &&
                   ($oFaltaAluno->getData()->getDia() == $oDia->iDia) &&
                   ($oFaltaAluno->getPeriodo() == $oDia->iPeriodo ) ) {

                $sFalta = "F";
                break;
              }
            }
          }

          if ( empty ($sFalta) ) {
            $this->exibePontos($oEstrutura->iLarguraCelulaGrade);
          }
          $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, $sFalta, 1, 0, "C");
        }
      }

      $this->escreverColunasFaltasEmBranco($oEstrutura);

    }

  }

  /**
   * Imprime as linhas no Di�rio
   * @param $oEstrutura
   */
  private function imprimirCelulasGradeFaltaSemAlunos ($oEstrutura) {

    if ( count($oEstrutura->aMeses) == 0) {

      for ($i = 0; $i < $oEstrutura->iNumeroColunas; $i++) {

        $this->exibePontos($oEstrutura->iLarguraCelulaGrade);
        $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, "", 1);
      }
    } else {

      foreach ($oEstrutura->aMeses as $iMes =>  $oMes ) {

        foreach ($oMes->aDias as $oDia) {

          $this->exibePontos($oEstrutura->iLarguraCelulaGrade);
          $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, '', 1, 0, "C");
        }
      }
      $this->escreverColunasFaltasEmBranco($oEstrutura);
    }
  }

  /**
   * Imprime os pontos na grade de faltas
   * @param $iLarguraCelula
   */
  private function exibePontos( $iLarguraCelula ) {

    if ($this->lExibirPontos) {

      $iY = $this->getY();
      $iX = $this->getX();

      $this->Setfont('arial','B',12);
      $this->Text($iX + ($iLarguraCelula * 30 / 95), $iY + 2.5 , ".");
      $this->SetFont("arial", '', 6);
    }

  }

  /**
   * Escreve a assinatura padr�o dos modelos, com exce��o do modelo 3, que sobrescreve o m�todo
   */
  protected function escreverAssinatura() {

    $iTamanhoLinha = 140.5;
    $iAlturaLinha  = 5;

    $sTexto = "Entregue em ____/____/____ POR " . str_repeat("_", 31);
    $this->Cell( $iTamanhoLinha, $iAlturaLinha, $sTexto, 1, 0, "L" );
    $sTexto = "Revisado em ____/____/____ POR " . str_repeat("_", 39);
    $this->Cell( $iTamanhoLinha, $iAlturaLinha, $sTexto, 1, 1, "L" );
    $sTexto = "Processado em ____/____/____ POR " . str_repeat("_", 29);
    $this->Cell( $iTamanhoLinha, $iAlturaLinha, $sTexto, 1, 0, "L" );
    $sTexto = "Assinatura do professor ____/____/____ POR " . str_repeat("_", 29);
    $this->Cell( $iTamanhoLinha, $iAlturaLinha, $sTexto, 1, 1, "L" );
    if ( $this->lPossuiMatriculaPorTurnoReferencia ) {

      $this->SetFont("arial", '', 7);
      $this->Cell( 281, $iAlturaLinha, "Legenda: Alunos matriculados somente em um turno � - Manh� | � - Tarde | � - Noite ", 1, 0, "L" );
    }
    $this->SetFont("arial", '', $this->iTamanhoFonteGrade);
  }

  /**
   * Renderiza o documento
   */
  public function escrever() {

    $this->estruturaSubCabecalho();

    if ( count($this->aEstruturaCabecalho) == 0) {
      throw new Exception ("Nenhuma reg�ncia(s) selecionada(s) possuem grade de hor�rio.");
    }

    $this->Open();
    foreach ( $this->aEstruturaCabecalho as $iRegencia => $oEstrutura ) {

      $this->oRegenciaAtual = RegenciaRepository::getRegenciaByCodigo($iRegencia);
      $this->escreverCorpo( $this->getAlunos($iRegencia), $oEstrutura);
    }

    $this->Output();
  }

  /**
   * Imprime a situa��o do aluno
   * @param $sSituacao     Situa��o do aluno que esta sendo impresso
   * @param $iTamanhoLinha Tamanho da linha
   */
  protected function imprimeSituacaoAluno( $sSituacao, $iTamanhoLinha ) {

    $this->SetFont("arial", "B", 7);
    $sSituacaoImprimir = $sSituacao;
    if ( !$this->lSomenteMatriculados && in_array($sSituacao, $this->aSituacaoTransferido) ) {
      $sSituacaoImprimir = "TRANSFERIDO";
    }

    $this->Cell($iTamanhoLinha , 4, $sSituacaoImprimir, 1, 0, 'C');
    $this->SetFont("arial", "", $this->iTamanhoFonteGrade);
  }


  /**
   * Quando selecionado modelo de impress�o com "Regitro = Frequ�ncia/Conte�do" pode haver um n�mero inferior
   * a 30 dias de avalia��o ( Exemplo uma disciplina com apenas um per�odo de avalia��o)
   * Para a coluna nome n�o ocupar metade folha, foi decido que teria um minimo de 30 quadros para lan�ar faltas
   *
   * Essa fun��o escreve as colunas em branco para lan�amento de faltas
   *
   * @param $oEstrutura
   */
  protected function escreverColunasFaltasEmBranco($oEstrutura) {

    if ($oEstrutura->iNumeroColunasVazias > 0) {

      for ($i = 0; $i < $oEstrutura->iNumeroColunasVazias; $i++) {
        $this->Cell($oEstrutura->iLarguraCelulaGrade, 4, '', 1, 0, "C");
      }
    }
  }


  /**
   * Escreve se o aluno esta amaparado
   * @param Matricula $oMatricula
   * @param $oEstrutura
   * @return bool
   */
  protected function validaAlunoAmparado(Matricula $oMatricula, $oEstrutura) {

    $oAvaliacaoDisciplina = $this->getDiarioAvaliacaoDisciplinaAluno($oMatricula);
    $oAvaliacaoPeriodo    = $oAvaliacaoDisciplina->getAvaliacoesPorOrdem( $this->oAvaliacaoPeriodica->getOrdemSequencia() );

    if ( $oAvaliacaoPeriodo->isAmparado() ) {

      $this->SetFont("arial", "B", 8);
      $this->Cell($oEstrutura->iTamanhoGrade, 4, "AMPARADO", 1, 0, 'C');
      $this->SetFont("arial", "", 7);
      return true;
    }
    return false;
  }

  /**
   * Retorna os dados da avalia��o do aluno para a disciplina
   * @param Matricula $oMatricula
   * @return DiarioAvaliacaoDisciplina
   */
  protected function getDiarioAvaliacaoDisciplinaAluno( Matricula $oMatricula) {

    return $oMatricula->getDiarioDeClasse()->getDisciplinasPorDisciplina($this->oRegenciaAtual->getDisciplina());
  }

  /**
   * Retorna o nome do aluno com uma observa��o se houver
   * @param  Matricula $oMatricula
   * @return string
   */
  protected function getNomeAluno(Matricula $oMatricula) {

    $sNomeAluno = $oMatricula->getAluno()->getNome();
    if ( $this->lPossuiMatriculaPorTurnoReferencia ) {

      $aTurnosVinculo = $oMatricula->getTurnosVinculados();
      if ( count($aTurnosVinculo) == 1) {

        switch ($aTurnosVinculo[0]->ed336_turnoreferente) {
          case Turno::TURNO_REFERENTE_MANHA:

            $sNomeAluno .= " � ";
            break;
          case Turno::TURNO_REFERENTE_TARDE:

            $sNomeAluno .= " � ";
            break;
          case Turno::TURNO_REFERENTE_NOITE:

            $sNomeAluno .= " � ";
            break;
        }
      }
    }

   return $sNomeAluno;
  }
}
