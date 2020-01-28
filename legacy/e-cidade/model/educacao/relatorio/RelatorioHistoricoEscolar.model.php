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

/**
 * Classe para montar a estrutura do relatorio de hist�rico/certificado escolar
 *
 * @package educacao
 * @subpackage relatorio
 * @author andrio.costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.44 $
 */
class RelatorioHistoricoEscolar {

  const MENSAGEM                 = "educacao.escola.RelatorioHistoricoEscolar.";
  const CAMINHO_BRASAO_REPUBLICA = "imagens/brasaohistoricoescolar.jpeg";
  const TIPO_BRASAO_REPUBLICA    = 1;
  const TIPO_BRASAO_MUNICIPIO    = 2;

  /**
   * Define se devemos incluir as etapas reprovadas na estrutura dos dados
   * A  => Etapas APROVADAS
   * AR => Etapas APROVADAS e REPROVADAS
   * U  => Listar �ltimo Registro
   * @var string
   */
  protected $sTipoRegistro = 'A';

  /**
   * Estrutura com os dados necessarios para impressao do hist�rico
   * @var array
   */
  protected $aDadosOrganizados = array();

  /**
   * Indica que o aluno teve ao menos uma disciplina com aprova��o parcial
   * @var boolean
   */
  protected $lAlunoTeveAprovacaoComProgressao = false;

  /**
   * Par�metros de configura��o do relat�rio
   * @var stdClass
   */
  protected $oParametros;

  /**
   * Armazena as observa��es do hist�rico do aluno, indexando o array pelo curso
   * @var array
   */
  protected $aObservacaoHistorico = array();

  /**
   * Instancia de Aluno
   * @var Aluno
   */
  protected $oAluno;

  /**
   * Inst�ncia de Escola
   * @var Escola
   */
  protected $oEscola;

  /**
   * Valida se etapas reclassificadas devem ser exibidas no relat�rio
   * @var boolean
   */
  protected $lExibirReclassificacao = true;

  /**
   * Controla se deve ser apresentados todos os cursos ou somente os conclu�dos
   * @var boolean
   */
  protected $lExibirSomenteCursosConcluidos = false;

  /**
   * Curso que ser� apresentado no relat�rio.
   * @var Curso
   */
  protected $oCurso = null;

  /**
   * Carrega informa��es sobre as etapas posteriores
   * @var array
   */
  protected $aEtapasPosterior = array();

  /*PLUGIN DIARIO PROGRESSAO - INICIALIZANDO ARRAY COM AS OBSERVA��ES - N�O APAGAR*/


  /**
   * Construtor da classe
   *
   * @param Aluno   $oAluno
   * @param Escola  $oEscola
   * @param integer $iTipoRelatorio
   * @param boolean $lExibirReclassificacao
   */
  public function __construct(Aluno $oAluno, Escola $oEscola, $iTipoRelatorio, $lExibirReclassificacao) {

    $this->oAluno  = $oAluno;
    $this->oEscola = $oEscola;
    $this->parametrosRelatorio($iTipoRelatorio);
    $this->lExibirReclassificacao         = $lExibirReclassificacao;
    $this->lExibirSomenteCursosConcluidos = false;
  }

  /**
   * Define o tipo de registro
   *
   * @param string $sTipoRegistro
   */
  public function setTipoRegistro($sTipoRegistro) {
    $this->sTipoRegistro = $sTipoRegistro;
  }

  /**
   * Retorna um estrutura organizada com todo o hist�rico acad�mico do aluno
   * @return multitype:
   */
  public function montarEstruturaDeDados() {

    /**
     * Evita o sobrecarregamento dos dados
     */
    if (count($this->aDadosOrganizados) > 0) {
    	return $this->aDadosOrganizados;
    }

    $iUltimoAnoCursado = $this->getUltimoAnoCursado();

    $aHistoricosAluno  = HistoricoAlunoRepository::getHistoricosPorAluno($this->oAluno);
    $aMatriculas       = MatriculaRepository::getTodasMatriculasAluno( $this->oAluno );

    $oUltimaEtapaHistoricoCursada = null;
    $aCursosConcluidosImpressao   = $this->getCursosConcluidos();


    foreach ($aHistoricosAluno as $oHistoricoCurso) {

      if ( !empty($this->oCurso) && !$this->validaCursos( $oHistoricoCurso->getCurso() ) ) {
        continue;
      }

      if ( $this->lExibirSomenteCursosConcluidos && $oHistoricoCurso->getAnoConclusao() == ''
           &&  !in_array($oHistoricoCurso->getCurso(), $aCursosConcluidosImpressao) ) {
        continue;
      }

      /**
       * Armazena as observa��es do historicos do aluno
       */
      if ($oHistoricoCurso->getObservacoes() != "") {
        $this->aObservacaoHistorico[$oHistoricoCurso->getCurso()] = $oHistoricoCurso->getObservacoes();
      }

      foreach ($oHistoricoCurso->getEtapas() as $oEtapaCursada) {

        if ( !$this->lExibirReclassificacao && $oEtapaCursada->getSituacaoEtapa() == "RECLASSIFICADO" ) {
          continue;
        }

        if ( $this->sTipoRegistro == "A" && $oEtapaCursada->getResultadoAno() == 'R' ) {
          continue;
        }

        /**
         * A Etapa do ultimo ano cursado sempre deve ser mostrada.
         */
        if ( ($this->sTipoRegistro == "U") &&
             ($oEtapaCursada->getAnoCurso() < $iUltimoAnoCursado) &&
             ($oEtapaCursada->getResultadoAno() == 'R')  ) {
          continue;
        }

        $oUltimaEtapaHistoricoCursada = $oEtapaCursada;

        $iEnsino = $oEtapaCursada->getEtapa()->getEnsino()->getCodigo();
        $aTermos = null;

        if ( $oEtapaCursada->isLancamentoAutomatico() ) {
          $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iEnsino, $oEtapaCursada->getAnoCurso());
        }

        $oDadoEtapa                = new stdClass();
        $oDadoEtapa->iEtapa        = $oEtapaCursada->getEtapa()->getCodigo();
        $oDadoEtapa->sEtapa        = $oEtapaCursada->getEtapa()->getNome();
        $oDadoEtapa->iAno          = $oEtapaCursada->getAnoCurso();
        $oDadoEtapa->iDiasLetivos  = $oEtapaCursada->getDiasLetivos();
        $oDadoEtapa->iCargaHoraria = $oEtapaCursada->getCargaHoraria();
        $oDadoEtapa->sResultado    = $this->termoResultadoFinalEtapa($oEtapaCursada->getResultadoAno(),
                                                                     $oEtapaCursada->getSituacaoEtapa(),
                                                                     $oEtapaCursada->getTermoFinal(),
                                                                     $aTermos);
        $oDadoEtapa->sTurma       = $oEtapaCursada->getTurma();
        $oDadoEtapa->iEscola      = $oEtapaCursada->getEscola()->getCodigo();
        $oDadoEtapa->sEscola      = $oEtapaCursada->getEscola()->getNome();
        $oDadoEtapa->sMunicipio   = $oEtapaCursada->getEscola()->getMunicipio();
        $oDadoEtapa->sUF          = $oEtapaCursada->getEscola()->getUf();

        $oDadoEtapa->nPercentualFalta = "";
        if ( $oEtapaCursada instanceof HistoricoEtapaRede ) {
          $oDadoEtapa->nPercentualFalta = $oEtapaCursada->getPercentualFrequencia();
        }

       /**
         * Quando a etapa que est� sendo percorrida � igual h� alguma etapa das matr�culas do aluno:
         * Verifica qual � a forma de calculo da frequ�ncia (Por Disciplina/ Carga Hor�ria Total) e se existe ao menos
         * alguma disciplina do di�rio que possui Reclassifaca��o por Baixa Frequ�ncia e substitui o percentual de
         * frequ�ncia por '--'.
         */
        if ( $oEtapaCursada->isLancamentoAutomatico() ) {

          foreach ( $aMatriculas as $oMatricula ) {

            if(    $oMatricula->getEtapaDeOrigem()->getCodigo() == $oDadoEtapa->iEtapa
                && $oMatricula->getTurma()->getCalendario()->getAnoExecucao() == $oDadoEtapa->iAno
              ) {

              db_inicio_transacao();
              $oDiarioClasse = $oMatricula->getDiarioDeClasse();
              db_fim_transacao();

              $iFormaCalculo                  = $oDiarioClasse->getProcedimentoDeAvaliacao()->getFormaCalculoFrequencia();
              $lReclassificadoBaixaFrequencia = $oDiarioClasse->reclassificadoPorBaixaFrequencia();

              if ( $iFormaCalculo == 2 && $lReclassificadoBaixaFrequencia ) {
                $oDadoEtapa->nPercentualFalta = '--';
              }

              /*PLUGIN DIARIO PROGRESSAO - OBSERVACAO HISTORICO ALUNO EVADIDO - N�O APAGAR*/

            }
          }
        }

        $oDadoEtapa->mMinimoAprovacao  = $oEtapaCursada->getMininoParaAprovacao();
        $oDadoEtapa->aDisicplinasEtapa = array();

        foreach ($oEtapaCursada->getDisciplinas() as $oDisciplinaCursada) {

          $oDisciplina                   = new stdClass();
          $oDisciplina->iCadDisciplina   = $oDisciplinaCursada->getDisciplina()->getCodigoDisciplinaGeral();
          $oDisciplina->sDisciplina      = $oDisciplinaCursada->getDisciplina()->getNomeDisciplina();
          $oDisciplina->sAbrevDisciplina = $oDisciplinaCursada->getDisciplina()->getAbreviatura();
          $oDisciplina->sEtapa           = $oEtapaCursada->getEtapa()->getNomeAbreviado();
          $oDisciplina->mAvaliacao       = $oDisciplinaCursada->getResultadoObtido();
          $oDisciplina->iCargaHoraria    = $oDisciplinaCursada->getCargaHoraria();
          $oDisciplina->sResultado       = $this->termoResultadoFinalDisciplina($oDisciplinaCursada->getResultadoFinal(),
                                                                                 $oDisciplinaCursada->getSituacaoDisciplina(),
                                                                                 $oDisciplinaCursada->getTermoFinal());

          if ($oDisciplina->sResultado == 'APR*') {
            $oDisciplina->mAvaliacao .= "*";
          }

          $oDisciplina->iAno             = $oEtapaCursada->getAnoCurso();
          $oDisciplina->iEscola          = $oEtapaCursada->getEscola()->getCodigo();
          $oDisciplina->lBaseComum       = $oDisciplinaCursada->isBaseComum();
          $oDisciplina->sSituacao        = $oDisciplinaCursada->getSituacaoDisciplina();

          $oDadoEtapa->aDisicplinasEtapa[$oDisciplina->iCadDisciplina] = $oDisciplina;
        }
        $sIndex  = "{$oEtapaCursada->getAnoCurso()}#{$oEtapaCursada->getEtapa()->getOrdem()}";
        $sIndex .= "#{$oEtapaCursada->getEtapa()->getCodigo()}";

        $this->aDadosOrganizados[$sIndex] = $oDadoEtapa;
      }
    }
    $this->buscaEtapasPosteriores( $oUltimaEtapaHistoricoCursada );

    ksort($this->aDadosOrganizados);
    return $this->aDadosOrganizados;
  }

  /**
   * Busca as etapas posteriores do aluno apartir do �ltimo registro no hist�rico ou da matr�cula caso o aluno possua
   * matr�cula v�lida (Ativa e N�o Conclu�da) de acordo com a base do curso.
   * @param  HistoricoEtapa $oUltimaEtapaHistoricoCursada �ltima etapa cursada de acordo com o hist�rico
   */
  private function buscaEtapasPosteriores ( $oUltimaEtapaHistoricoCursada ) {

    $lMatriculaValida = false;
    $oUltimaEtapa     = null;

    if ( !empty($oUltimaEtapaHistoricoCursada) ) {
      $oUltimaEtapa = $oUltimaEtapaHistoricoCursada->getEtapa();
    }

    $oUltimaMatricula = MatriculaRepository::getUltimaMatriculaAluno($this->oAluno);
    if (!empty($oUltimaMatricula) && $oUltimaMatricula->isAtiva() && !$oUltimaMatricula->isConcluida() &&
        $oUltimaMatricula->getSituacao() == 'MATRICULADO' ) {

      $lMatriculaValida = true;
      $oUltimaEtapa     = $oUltimaMatricula->getEtapaDeOrigem();
    }
    if ( empty($oUltimaEtapa) ) {
      return;
    }

    $aEtapasEnsino = EtapaRepository::getEtapasEnsino( $oUltimaEtapa->getEnsino() );

    foreach ($aEtapasEnsino as $oEtapaEnsino ) {

      if (    ( $lMatriculaValida && $oEtapaEnsino->getOrdem() < $oUltimaEtapa->getOrdem() )
           || ( !$lMatriculaValida && $oEtapaEnsino->getOrdem() <= $oUltimaEtapa->getOrdem() )
         ) {
        continue;
      }

      $oDadoEtapa                    = new stdClass();
      $oDadoEtapa->iEtapa            = $oEtapaEnsino->getCodigo();
      $oDadoEtapa->sEtapa            = $oEtapaEnsino->getNome();
      $oDadoEtapa->sTurma           = '-';
      $oDadoEtapa->sEscola          = '-';
      $oDadoEtapa->sMunicipio       = '-';
      $oDadoEtapa->iAno             = '-';
      $oDadoEtapa->iDiasLetivos     = '-';
      $oDadoEtapa->iCargaHoraria    = '-';
      $oDadoEtapa->nPercentualFalta = '-';
      $oDadoEtapa->sResultado       = '-';
      $oDadoEtapa->sUF              = '-';

      $this->aEtapasPosterior[$oDadoEtapa->iEtapa] = $oDadoEtapa;
    }
  }

  /**
   * Busca o termo a ser apresentado no Resultado final da etapa
   * @param string $sResultadoFinal
   * @param string $sSituacao
   * @param string $sTermoFinalEtapa
   */
  private function termoResultadoFinalEtapa ($sResultadoFinal, $sSituacao = null, $sTermoFinalEtapa = null, $aTermos) {

    $sTermoFinal = $this->termoFinal($sResultadoFinal, false, $aTermos);
    /**
     * Caso tenha sido informado um termo final, este substituira o resultado final
     */
    if (!empty($sTermoFinalEtapa)) {
      $sTermoFinal = $sTermoFinalEtapa;
    }

    /**
     * Caso o hist�rico tenha sido lancado como transferencia, o resultado recebe TR
     */
    if (!empty($sSituacao) && $sSituacao == 'TRANSFERIDO') {
      $sTermoFinal = 'TR';
    }

    /**
     * Situa��es que devem ser apresentadas no Resultado
     */
    $aSituacoes = array(
                         'AVAN�ADO'  => 'AVAN',
                         'CANCELADO' => 'CANC',
                         'EVADIDO'   => 'EVAD',
                         'FALECIDO'  => 'FALEC',
                       );

    if( array_key_exists( $sSituacao, $aSituacoes ) ) {
      $sTermoFinal = $aSituacoes[ $sSituacao ];
    }

    return $sTermoFinal;
  }

  /**
   * Busca o termo a ser apresentado no Resultado final da disciplina
   * @param string $sResultadoFinal
   * @param string $sSituacao
   * @param string $sTermoFinalDisciplina
   */
  private function termoResultadoFinalDisciplina ($sResultadoFinal, $sSituacao = null, $sTermoFinalDisciplina = null) {

    $sTermoFinal = 'REP';
    if (!empty($sSituacao) && trim($sSituacao) != 'CONCLU�DO') {
  	  $sTermoFinal = 'APR';
    }

    $sTermoFinal = $this->termoFinal($sResultadoFinal, true);

    //Caso a situacao seja 'N�O OPTANTE' ou 'AMPARADO', e nao exista um termo final, nao apresentamos o resultado final (RF)
    if (!empty($sSituacao) &&
        (trim($sSituacao) == 'N�O OPTANTE' || trim($sSituacao) == 'AMPARADO')) {
  	  $sTermoFinal = '';
    }

    if (!empty($sTermoFinalDisciplina)) {
      $sTermoFinal = $sTermoFinalDisciplina;
    }

    return $sTermoFinal;
  }

  /**
   * Retorna o termo final de acordo com o Resultado
   * @param string $sResultadoFinal
   * @return string
   */
  private function termoFinal($sResultadoFinal, $lDisciplina = false, $aTermos = null) {

    $sSituacaoFinal = '';
    switch (trim($sResultadoFinal)) {

    	case 'A': // Aprovado

        $sSituacaoFinal = 'APR';
        if ( !empty($aTermos) ) {
          $sSituacaoFinal =  $this->getTermoByReferencia($aTermos, $sResultadoFinal);
        }
    	  break;

    	case 'D': // Dependencia

    	  $sSituacaoFinal = 'AP/DP';
    	  if ($lDisciplina) {

    	    $sSituacaoFinal = 'APR*';
    	    $this->lAlunoTeveAprovacaoComProgressao = true;
    	  }
    	  break;

    	case 'R': // Reprovado

    	  $sSituacaoFinal = 'REP';
        if ( !empty($aTermos) ) {

          foreach ( $aTermos as $oTermo) {

            if ($oTermo->sReferencia == 'R') {

              $sSituacaoFinal = $this->getTermoByReferencia($aTermos, $sResultadoFinal);;
              break;
            }
          }
        }
    	  break;

    }
    return $sSituacaoFinal;
  }

  private function getTermoByReferencia($aTermos, $sReferencia) {

    foreach ($aTermos as $oTermo) {

      if ( $oTermo->sReferencia === $sReferencia ) {
        return $oTermo->sAbreviatura;
      }
    }
  }

  /**
   * Busca os par�metros para relat�rio impresso
   * @param integer $iTipoRelatorio
   */
  private function parametrosRelatorio($iTipoRelatorio) {

    $sCampos       = " ed217_t_cabecalho        as cabecalho,                  ";
    $sCampos      .= " ed217_t_rodape           as rodape,                     ";
    $sCampos      .= " ed217_t_obs              as observacao,                 ";
    $sCampos      .= " ed217_orientacao         as orientacao,                 ";
    $sCampos      .= " ed217_exibeturma         as exibe_turma,                ";
    $sCampos      .= " ed217_exibecargahoraria  as exibe_percentual_frequencia,";
    $sCampos      .= " CASE                                                    ";
    $sCampos      .= "   WHEN ed217_gradenotas = 1 THEN '6'                    ";
    $sCampos      .= "   WHEN ed217_gradenotas = 2 THEN '8'                    ";
    $sCampos      .= "   WHEN ed217_gradenotas = 3 THEN '10'                   ";
    $sCampos      .= "   WHEN ed217_gradenotas = 4 THEN '12'                   ";
    $sCampos      .= " END AS fonte_grade_nota,                                ";
    $sCampos      .= " CASE                                                    ";
    $sCampos      .= "   WHEN ed217_gradeetapas = 1 THEN '6'                   ";
    $sCampos      .= "   WHEN ed217_gradeetapas = 2 THEN '8'                   ";
    $sCampos      .= "   WHEN ed217_gradeetapas = 3 THEN '10'                  ";
    $sCampos      .= "   WHEN ed217_gradeetapas = 4 THEN '12'                  ";
    $sCampos      .= " END AS fonte_grade_etapa,                               ";
    $sCampos      .= " CASE                                                    ";
    $sCampos      .= "   WHEN ed217_observacao = 1 THEN '6'                    ";
    $sCampos      .= "   WHEN ed217_observacao = 2 THEN '8'                    ";
    $sCampos      .= "   WHEN ed217_observacao = 3 THEN '10'                   ";
    $sCampos      .= "   WHEN ed217_observacao = 4 THEN '12'                   ";
    $sCampos      .= " END AS fonte_observacao,                                ";
    $sCampos      .= " ed217_brasao as brasao                                  ";

    $oDaoRelatorio = new cl_edu_relatmodel();
    $sSqlRelatorio = $oDaoRelatorio->sql_query("", $sCampos, "", "ed217_i_codigo = $iTipoRelatorio");
    $rsRelatorio   = $oDaoRelatorio->sql_record($sSqlRelatorio);

    if ($oDaoRelatorio->numrows == 0) {
      db_redireciona("db_erros.php?fechar=true&db_erro="._M(self::MENSAGEM."parametros_nao_localizado"));
    }

    $this->oParametros = db_utils::fieldsMemory($rsRelatorio, 0);
    $this->oParametros->exibe_turma                 = $this->oParametros->exibe_turma                 == 't';
    $this->oParametros->exibe_percentual_frequencia = $this->oParametros->exibe_percentual_frequencia == 't';
  }

  /**
   * Retorna uma string contendo todas as observa��es lan�adas para o aluno quando este foi aprovado por conselho de
   * classe.
   * @return string
   */
  protected function getObservacaoAprovadoPeloConselho () {

    $sCampos  =" cgmrh.z01_nome,                                 ";
    $sCampos .=" ed253_i_data,                                   ";
    $sCampos .=" ed232_c_descrcompleta as disciplina,            ";
    $sCampos .=" ed253_t_obs,                                    ";
    $sCampos .=" ed47_v_nome,                                    ";
    $sCampos .=" ed11_c_descr as serie_conselho,                 ";
    $sCampos .=" ed59_i_ordenacao,                               ";
    $sCampos .=" ed253_aprovconselhotipo,                        ";
    $sCampos .=" ed52_i_ano,                                     ";
    $sCampos .=" ed253_alterarnotafinal, ed253_avaliacaoconselho ";

    $aHistoricosAluno = HistoricoAlunoRepository::getHistoricosPorAluno($this->oAluno);

    $aCursos = array();
    foreach ($aHistoricosAluno as $oHistoricoCurso) {
      $aCursos[] = $oHistoricoCurso->getCurso();
    }

    $sCursos = implode(", ", $aCursos);

    $sWhere  = "     ed95_i_aluno = {$this->oAluno->getCodigoAluno()} ";
    $sWhere .= " and ed31_i_curso in ({$sCursos})";

    $oDaoConselho      = new cl_aprovconselho();
    $sSqlAprovConselho = $oDaoConselho->sql_query("", $sCampos, "ed59_i_ordenacao", $sWhere);
    $rsAprovConselho   = $oDaoConselho->sql_record($sSqlAprovConselho);
    $iLinhas           = $oDaoConselho->numrows;

    if ($iLinhas == 0) {
    	return "";
    }

    $aObservacao              = array();
    $aAprovadoBaixaFrequencia = array();
    for ($i = 0; $i < $iLinhas; $i++) {

      $oDadosAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $i);

      switch ($oDadosAprovConselho->ed253_aprovconselhotipo) {

        /**
         * Valida se a aprova��o foi por conselho
         */
        case 1:

          $oDocumento                = new libdocumento( 5013 );
          $oDocumento->disciplina    = $oDadosAprovConselho->disciplina;
          $oDocumento->etapa         = $oDadosAprovConselho->serie_conselho;
          $oDocumento->justificativa = $oDadosAprovConselho->ed253_t_obs;
          $oDocumento->nota          = ArredondamentoNota::arredondar($oDadosAprovConselho->ed253_avaliacaoconselho, $oDadosAprovConselho->ed52_i_ano);
          $oDocumento->anomatricula  = $oDadosAprovConselho->ed52_i_ano;

          $oObservacao              = new stdClass();
          $oObservacao->aParagrafos = $oDocumento->getDocParagrafos();

          if( trim( $oObservacao->aParagrafos[1]->oParag->db02_texto ) ) {
            $aObservacao[]            = "- ".$oObservacao->aParagrafos[1]->oParag->db02_texto;
          }

          break;

       /**
        * Valida se a aprova��o n�o foi por baixa frequencia
        */
        case 2:

          $sHashSerieAno = $oDadosAprovConselho->serie_conselho.$oDadosAprovConselho->ed52_i_ano;
          if (!isset($aAprovadoBaixaFrequencia[$sHashSerieAno])) {
            $aAprovadoBaixaFrequencia[$sHashSerieAno] = $oDadosAprovConselho;
          }
          continue;

          break;

        /**
         * Valida se a aprova��o foi por regimento escolar
         */
        case 3:

          $sTipoAprovacao = "foi aprovado pelo regimento escolar.";
          $sObservacao    = "- Disciplina {$oDadosAprovConselho->disciplina} na etapa";
          $sObservacao   .= " {$oDadosAprovConselho->serie_conselho} {$sTipoAprovacao}";
          $sObservacao   .= "Justificativa: {$oDadosAprovConselho->ed253_t_obs}";
          $aObservacao[]  = $sObservacao;
          break;
      }

    }

    $oDocumento = new libdocumento(5005);
    foreach ($aAprovadoBaixaFrequencia as $oAprovadosBaixaFrequencia) {

      $oDocumento->nome_aluno = $this->oAluno->getNome();
      $oDocumento->ano        = $oAprovadosBaixaFrequencia->ed52_i_ano;
      $oDocumento->nome_etapa = $oAprovadosBaixaFrequencia->serie_conselho;
      $aParagrafos            = $oDocumento->getDocParagrafos();
      if (isset($aParagrafos[1])) {
        $aObservacao[] = "- {$aParagrafos[1]->oParag->db02_texto}";
      }
    }

    return implode("\n", $aObservacao);
  }

  /**
   * Verifica se houve troca de s�rie para o aluno
   * Se sim monta uma string, com os dados da troca
   * @return string
   */
  protected function getObservacaoTrocaSerie() {

    $sCampos  = "DISTINCT trocaserie.ed101_i_codigo, ";
    $sCampos .= "serieorig.ed11_c_descr  as origem,  ";
    $sCampos .= "seriedest.ed11_c_descr  as destino, ";
    $sCampos .= "trocaserie.ed101_d_data as data,    ";
    $sCampos .= "trocaserie.ed101_c_tipo as tipo     ";
    $sWhere   = " ed101_i_aluno = {$this->oAluno->getCodigoAluno()} ";
    $sOrdem   = " trocaserie.ed101_d_data ";

    $oDaoTrocaSerie = new cl_trocaserie();
    $sSqlTrocaSerie = $oDaoTrocaSerie->sql_query_certificado_conclusao("", $sCampos, $sOrdem, $sWhere);
    $rsTrocaSerie   = $oDaoTrocaSerie->sql_record($sSqlTrocaSerie);
    $iLinhas        = $oDaoTrocaSerie->numrows;

    if ($iLinhas == 0) {
      return "";
    }

    $aObservacao = array();

    for ($i = 0; $i < $iLinhas; $i++) {

      $oTrocaSerie = db_utils::fieldsmemory($rsTrocaSerie, $i) ;

      $oData = new DBDate($oTrocaSerie->data);

      if ( !$this->lExibirReclassificacao && $oTrocaSerie->tipo == "R" ) {
        continue;
      }

      $sObservacao  = "- ";
      $sObservacao .= $oTrocaSerie->tipo == "A" ? "AVAN�ADO" : "RECLASSIFICADO";
      $sObservacao .= " (A) DA ETAPA ". (trim($oTrocaSerie->origem))." PARA ETAPA ". (trim($oTrocaSerie->destino))." EM ";
      $sObservacao .= $oData->convertTo(DBDate::DATA_PTBR);
      $sObservacao .= ", CONFORME LEI FEDERAL N� 9394/96 - ARTIGO 23, �1� , ";
      $sObservacao .= "PARECER CEED N� 740/99 E REGIMENTO ESCOLAR";

      $aObservacao[] = $sObservacao;
    }

    return implode("\n", $aObservacao);

  }

  /**
   * Busca os atos legais que aparecem no hist�rico do aluno
   * @return array
   */
  protected function getAtosLegais() {

    $aAtosLegaisEscola = array();
    $aAtosLegaisCurso  = array();

    /**
     * Primeiramente separamos os atos legais da escola e os que est�o vinculados a algum curso da escola
    */
    foreach ($this->oEscola->getAtosLegais() as $oAtoLegal) {

      if ($oAtoLegal->existeCursoVinculado()) {
        $aAtosLegaisCurso[] = $oAtoLegal;
      } else {
        $aAtosLegaisEscola[] = $oAtoLegal;
      }
    }

    $aAtosLegais = array();
    foreach ($aAtosLegaisEscola as $oAtoLegal) {

      if (!$oAtoLegal->apareceHistorico()) {
        continue;
      }
      $sAtoLegal  = "{$oAtoLegal->getFinalidade()}  N� {$oAtoLegal->getNumero()} ";
      $sAtoLegal .= "Data {$oAtoLegal->getDataVigor()->convertTo(DBDate::DATA_PTBR)} ";
      $sAtoLegal .= "D.O.: {$oAtoLegal->getDataDePublicacao()->convertTo(DBDate::DATA_PTBR)} ";

      $aAtosLegais[$oAtoLegal->getCodigoAtoLegal()] = $sAtoLegal;
    }

    $aHistoricoAluno = HistoricoAlunoRepository::getHistoricosPorAluno($this->oAluno);
    $aCodigoCursos   = array();
    /**
     * Adicionamos em um array, os c�digos dos cursos que o aluno cursou
    */
    foreach ($aHistoricoAluno as $oHistoricoAluno) {
      $aCodigoCursos[] = $oHistoricoAluno->getCurso();
    }


    /**
     * Filtra os atos legais dos cursos que o aluno estudou
     */
    foreach ($aAtosLegaisCurso as $oAtoLegal) {

      if (!$oAtoLegal->apareceHistorico()) {
        continue;
      }

      foreach ($oAtoLegal->getCursosVinculado() as $oCurso) {

        if (!in_array($oCurso->getCodigo(), $aCodigoCursos))  {
          continue;
        }
      }

      $sAtoLegal  = "{$oAtoLegal->getFinalidade()}  N� {$oAtoLegal->getNumero()} ";
      $sAtoLegal .= "Data {$oAtoLegal->getDataVigor()->convertTo(DBDate::DATA_PTBR)} ";
      $sAtoLegal .= "D.O.: {$oAtoLegal->getDataDePublicacao()->convertTo(DBDate::DATA_PTBR)} ";

      $aAtosLegais[$oAtoLegal->getCodigoAtoLegal()] = $sAtoLegal;
    }

    return $aAtosLegais;
  }

  protected function getUltimoAnoCursado() {

    $sSql = "select max(ano) as ano                                                          \n";
    $sSql.= " from ( select max(ed62_i_anoref) as ano                                        \n";
    $sSql.= "          from historico                                                        \n";
    $sSql.= "               inner join historicomps     on ed62_i_historico  = ed61_i_codigo \n";
    $sSql.= "         where ed61_i_aluno = {$this->oAluno->getCodigoAluno()}                 \n";
    $sSql.= "                                                                                \n";
    $sSql.= "         union                                                                  \n";
    $sSql.= "                                                                                \n";
    $sSql.= "        select max(ed99_i_anoref) as ano                                        \n";
    $sSql.= "          from historico                                                        \n";
    $sSql.= "               inner join historicompsfora on ed99_i_historico  = ed61_i_codigo \n";
    $sSql.= "         where ed61_i_aluno = {$this->oAluno->getCodigoAluno()}                 \n";
    $sSql.= "      ) as x                                                                    \n";
    $sSql.= "where ano is not null;                                                          \n";

    $rsSql = db_query($sSql);

    if (!$rsSql) {
    	throw new DBException( _M(self::MENSAGEM . "erro_query_ultimo_ano_cursado") );
    }

    if (pg_num_rows($rsSql) > 0) {
  	  return db_utils::fieldsMemory($rsSql, 0)->ano;
    }
    return db_getsession("DB_anousu");
  }

  /**
   * Retorna o caminho do bras�o, de acordo com o tipo enviado
   * @param integer     $iTipoBrasao
   * @param Instituicao $oInstituicao
   * @return string Caminho do bras�o
   */
  public static function getBrasao( $iTipoBrasao, Instituicao $oInstituicao ) {

    switch( $iTipoBrasao ) {

      case RelatorioHistoricoEscolar::TIPO_BRASAO_REPUBLICA:

        return RelatorioHistoricoEscolar::CAMINHO_BRASAO_REPUBLICA;
        break;

      case RelatorioHistoricoEscolar::TIPO_BRASAO_MUNICIPIO:

        return "imagens/files/" . $oInstituicao->getImagemLogo();
        break;
    }
  }

  /**
   * Retorna as oberva��es lan�adas para as etapas do hist�rico do aluno
   * @return array
   */
  protected function getObservacaoHistoricoEtapa() {

    $aHistoricosAluno = HistoricoAlunoRepository::getHistoricosPorAluno($this->oAluno);

    $aObservacao = array();

    foreach ($aHistoricosAluno as $oHistoricoAluno ) {

      foreach ($oHistoricoAluno->getEtapas() as $oEtapaHistorico) {

        if ( $oEtapaHistorico->getObservacao() != "" ) {
          $aObservacao[] = $oEtapaHistorico->getEtapa()->getNomeAbreviado() . " - " . $oEtapaHistorico->getObservacao();
        }
      }
    }

    return $aObservacao;
  }

  /**
   * Define se deve ser exibido somente cursos conclu�dos
   * @param boolean $lExibirSomenteCursosConcluidos
   */
  protected function setExibirSomenteCursosEncerrados( $lExibirSomenteCursosConcluidos ) {
    $this->lExibirSomenteCursosConcluidos = $lExibirSomenteCursosConcluidos;
  }

  /**
   * Define um curso para ser apresentado na impress�o do relat�rio
   * @param Curso $oCurso
   */
  public function setCurso(Curso $oCurso) {
    $this->oCurso = $oCurso;
  }

  /**
   * Verifica se o curso percorrido do hist�rico � igual ao curso informado pelo filtro ou suas equival�ncias.
   * @param integer $iCurso C�digo do curso do hist�rico
   * @return boolean
   */
  protected function validaCursos( $iCurso ) {

    $aCursosValidos = array( $this->oCurso->getCodigo() );

    foreach ( $this->oCurso->getCursosEquivalentes() as $oCursoEquivalente ) {
      $aCursosValidos[] = $oCursoEquivalente->getCodigo();
    }

    if ( !in_array($iCurso, $aCursosValidos) ) {
      return false;
    }

    return true;
  }

  /**
   * Retorna todos os c�digos de cursos conclu�dos e suas equival�ncias de um aluno
   * @return array
   */
  private function getCursosConcluidos() {

    $aCursosConcluidosImpressao = array();

    if ( $this->lExibirSomenteCursosConcluidos ) {

      $aCursosConcluidos = CursoRepository::getCursosConcluidosPorAluno($this->oAluno);

      foreach ($aCursosConcluidos as $oCurso) {

        $aCursosConcluidosImpressao[] = $oCurso->getCodigo();

        foreach ($oCurso->getCursosEquivalentes() as $oCursoEquivalente) {
          $aCursosConcluidosImpressao[] = $oCursoEquivalente->getCodigo();
        }

      }
    }
    return $aCursosConcluidosImpressao;
  }


}
