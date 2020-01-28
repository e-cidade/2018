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

/**
 * Classe para encerramento das avaliacoes dos alunos/Turmas
 * Realiza os encerramentos das avaliacoes dos alunos de progressao parcial, e de alunos de turmas Regulares / Eja
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package educacao
 * @subpackage avaliacao
 */
class EncerramentoAvaliacao {

  private $oLogger = null;

  public function __construct(DBLogJSON $oLogger = null) {

    if (!empty($oLogger)) {
      $this->oLogger = $oLogger;
    }
  }

  /**
   * Encerra os dados da progressao parcial do aluno;
   * @param ProgressaoParcialAluno $oProgressaoParcial Progressao parcial que deve ser encerrada
   * @return boolean
   */
  public function encerrarProgressaoParcial(ProgressaoParcialAluno $oProgressaoParcial) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transa��o com o banco de dados ativa.");
    }

    $oMensagem  = new stdClass();
    $oMensagem->disciplina = $oProgressaoParcial->getDisciplina()->getNomeDisciplina();
    $oMensagem->etapa      = $oProgressaoParcial->getEtapa()->getNome();
    $oMensagem->aluno      = $oProgressaoParcial->getAluno()->getNome();

    /**
     * caso a progressao parcial j� esteja encerradal, devemos ignorar essa progress�o.
     */
    if ($oProgressaoParcial->isConcluida()) {

      $sMensagem            = "A progress�o Parcial / Depend�ncia do {$oMensagem->aluno} ";
      $sMensagem           .= "na disciplina {$oMensagem->disciplina} ({$oMensagem->etapa}) j� foi conclu�da.";
      $oMensagem->mensagem  = $sMensagem;
      $this->informarErro($oMensagem);
      return false;

    }

   $oProgressaoVinculoDisciplina = $oProgressaoParcial->getVinculoRegencia();

    if (empty($oProgressaoVinculoDisciplina)) {

      $sMensagem            = "A progress�o Parcial / Depend�ncia do {$oMensagem->aluno} ";
      $sMensagem           .= "na disciplina {$oMensagem->disciplina} ({$oMensagem->etapa}) n�o possui v�nculo com";
      $sMensagem           .= " uma turma. ";
      $oMensagem->mensagem  = $sMensagem;
      $this->informarErro($oMensagem);
      return false;
    }

    if (!empty($oProgressaoVinculoDisciplina) && $oProgressaoVinculoDisciplina->isEncerrado()) {

      $sMensagem            = "A progress�o Parcial / Depend�ncia do {$oMensagem->aluno} ";
      $sMensagem           .= "na disciplina {$oMensagem->disciplina} ({$oMensagem->etapa}) j� est� encerrada.";
      $oMensagem->mensagem  = $sMensagem;
      $this->informarErro($oMensagem);
      return false;
    }

    /**
     * Verificamos se a progressao parcial possui resultado final informado
     */
    $oProgressaoResultadoFinal = $oProgressaoParcial->getResultadoFinal();
    if (!empty($oProgressaoResultadoFinal) && $oProgressaoResultadoFinal->getResultado() == "") {

      $sMensagem             = "A progress�o Parcial / Depend�ncia do {$oMensagem->aluno} ";
      $sMensagem            .= "na disciplina {$oMensagem->disciplina} ({$oMensagem->etapa}) est� sem resultado final.";
      $oMensagem->mensagem   = $sMensagem;
      $this->informarErro($oMensagem);
      return false;
    }

    $oProgressaoParcial->encerrar();
    return true;
  }

  /**
   * Cancela o encerrado da progressao parcial
   * @param ProgressaoParcialAluno $oProgressaoParcial progressao parcial encerrada
   * @return boolean
   */
  public function cancelarEncerramentoProgressaoParcial (ProgressaoParcialAluno $oProgressaoParcial) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transa��o com o banco de dados ativa.");
    }

    $oMensagem  = new stdClass();
    $oMensagem->disciplina = $oProgressaoParcial->getDisciplina()->getNomeDisciplina();
    $oMensagem->etapa      = $oProgressaoParcial->getEtapa()->getNome();
    $oMensagem->aluno      = $oProgressaoParcial->getAluno()->getNome();

    /**
     * caso a progressao parcial n�o esteja encerrada. devemos ignorar essa progress�o.
     */
    if (!$oProgressaoParcial->getVinculoRegencia()->isEncerrado()) {

      $sMensagem            = "A progress�o Parcial / Depend�ncia do {$oMensagem->aluno} ";
      $sMensagem           .= "na disciplina {$oMensagem->disciplina} ({$oMensagem->etapa}) n�o est� encerrada";
      $oMensagem->mensagem  = $sMensagem;
      $this->informarErro($oMensagem);
      return false;
    }
    $oProgressaoParcial->reativar();
    return true;
  }

  /**
   * Gera os logs do processamento, ou lanca uma excessao
   * @param stdClass $oErroMensagem objeto com os dados para ser logado
   * @throws BusinessException
   */
  protected function informarErro(stdClass $oErroMensagem) {

    if ($this->oLogger == null) {
      throw new BusinessException($oErroMensagem->mensagem);
    } else {

      $oErroMensagem->turma      = urlencode(isset($oErroMensagem->turma) ? urldecode($oErroMensagem->turma) : "");
      $oErroMensagem->disciplina = urlencode(isset($oErroMensagem->disciplina) ? urldecode($oErroMensagem->disciplina) : "");
      $oErroMensagem->etapa      = urlencode(isset($oErroMensagem->etapa) ? urldecode($oErroMensagem->etapa) : "");
      $oErroMensagem->aluno      = urlencode(isset($oErroMensagem->aluno) ? urldecode($oErroMensagem->aluno) : "");
      $oErroMensagem->mensagem   = urlencode(isset($oErroMensagem->mensagem) ? urldecode($oErroMensagem->mensagem) : "");
      $this->oLogger->log($oErroMensagem, DBLog::LOG_ERROR);
    }
  }

  /**
   * Encerra a turma
   * Gera hist�rico dos alunos e prepara o aluno para seguir para pr�xima etapa
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @throws BusinessException
   * @throws ParameterException
   * @throws DBException
   * @return boolean
   */
  public function encerrarAvaliacaoTurma(Turma $oTurma, Etapa $oEtapa) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transa��o com o banco de dados ativa.");
    }

    /**
     * Se n�o tem aulas dadas lanca exception
     */
    if ($this->semAulasDadas($oTurma, $oEtapa)) {
      return false;
    }
    $oErroMensagem         = new stdClass();
    $aDisciplinas          = $oTurma->getDisciplinasPorEtapa($oEtapa);
    $aMatriculas           = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $iTotalMatriculas      = count($aMatriculas);
    $iMatriculasEncerradas = 0;

    $oErroMensagem->turma = $oTurma->getCodigo();

    foreach ($aMatriculas as $oMatricula) {

      $sNomeAluno  = "{$oMatricula->getAluno()->getCodigoAluno()} - ";
      $sNomeAluno .= "{$oMatricula->getAluno()->getNome()}.";

      $oErroMensagem->etapa    = $oEtapa->getNome();
      $oErroMensagem->aluno    = $sNomeAluno;


      /**
       * Matriculas concluidas, ou matricula que nao estao ativas, devem contar no total de matriculas encerradas
       * na turma.
       */
      if ($oMatricula->isConcluida() || $oMatricula->getSituacao() != "MATRICULADO") {

        if ($oMatricula->getSituacao() != "MATRICULADO") {
          $this->atualizarConclusaoDaMatricula($oMatricula, "S");
        }

        $iMatriculasEncerradas++;
        MatriculaRepository::removerMatricula($oMatricula);
        continue;
      }

      if ($this->alunoComMatriculaPosterior($oMatricula)) {

        MatriculaRepository::removerMatricula($oMatricula);
        $iMatriculasEncerradas++;
        continue;
      }

      if (!$this->validaMatriculaParaEncerramento($oMatricula, $oErroMensagem)) {

        MatriculaRepository::removerMatricula($oMatricula);
        continue;
      }
      $iMatriculasEncerradas++;

      $sResultadoFinal = "";

      switch ($oTurma->getTipoDaTurma()) {

        case 1:
        case 3:

          $sResultadoFinal = $this->encerraTurmaNormal($oMatricula, $oEtapa);
          break;

        case 2:
        case 7:
          $sResultadoFinal = $this->encerraTurmaEja($oMatricula, $oEtapa);
          break;
      }
      $oDiario = $oMatricula->getDiarioDeClasse();


      /**
       * Encerra Diario do Aluno
       */
      foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {
        $this->encerraDiario($oDiarioDisciplina, $oMatricula->getAluno());
      }

      /**
       * alunocurso, alunopossib
       */
      $this->encerrarDadosMatricula($oMatricula, $oDiario, $sResultadoFinal);
      MatriculaRepository::removerMatricula($oMatricula);
    }

    /**
     * Verifica se todos os alunos foram encerrados e encerra as regencias da turma
     */
    $lTurmaEncerradaCompleta = false;
    if ($iMatriculasEncerradas == $iTotalMatriculas) {

      $this->encerraRegenciasTurma($oTurma, $oEtapa);
      $lTurmaEncerradaCompleta = true;
    }

    return $lTurmaEncerradaCompleta;
  }

  /**
   * Cancela o encerramento da turma
   * @throws BusinessException
   * @throws ParameterException
   * @throws DBException
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return boolean
   */
  public function cancelarEncerramentoTurma(Turma $oTurma, Etapa $oEtapa) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transa��o com o banco de dados ativa.");
    }

    $aMatriculas           = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $iMatriculasCanceladas = 0;
    $iTotalMatriculas      = count($aMatriculas);
    $oErroMensagem         = new stdClass();
    $oErroMensagem->turma = $oTurma->getCodigo();

    /**
     * Percorremos as matriculas da Turma
     */
    foreach ($aMatriculas as $oMatricula) {

      $sNomeAluno  = "{$oMatricula->getAluno()->getCodigoAluno()} - ";
      $sNomeAluno .= "{$oMatricula->getAluno()->getNome()}.";

      $oErroMensagem->etapa = $oEtapa->getNome();
      $oErroMensagem->aluno = $sNomeAluno;

      if ($oMatricula->getSituacao() != "MATRICULADO") {

        $iMatriculasCanceladas++;
        MatriculaRepository::removerMatricula($oMatricula);
      	continue;
      }

      if ($this->alunoComMatriculaPosterior($oMatricula)) {

        $oErroMensagem->mensagem = "Aluno {$sNomeAluno} possui matr�cula posterior a esta ";
        $oErroMensagem->mensagem .= "e n�o poder� ser cancelado o encerramento de suas avalia��es.";
        $this->informarErro($oErroMensagem);
        MatriculaRepository::removerMatricula($oMatricula);
        continue;
      }

      /**
       * Valida se aluno concluiu curso
       * N�o podemos cancelar alunos com curso encerrado
       */
      if ($this->alunoConcluiuCurso($oMatricula)) {

        $oErroMensagem->mensagem = "Aluno {$sNomeAluno} j� concluiu o curso, n�o sendo poss�vel cancelar avalia��es!";
        $this->informarErro($oErroMensagem);
        MatriculaRepository::removerMatricula($oMatricula);
        continue;
      }

      /**
       * Reativa a Matricula do aluno
       */
      $this->reativaMatricula($oMatricula);
      $oDiario = $oMatricula->getDiarioDeClasse();

      /**
       * Cancela o encerramento do Diario do Aluno
       */
      foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {
        $this->cancelaEncerramentoDiario($oDiarioDisciplina, $sNomeAluno);
      }

      switch ($oTurma->getTipoDaTurma()) {

        case 1:
        case 3:

          $sResultadoFinal = $this->cancelaEncerramentoTurmaNormal($oMatricula, $oEtapa);
          break;

        case 2:
        case 7:
          $sResultadoFinal = $this->cancelaEncerramentoTurmaEja($oMatricula, $oEtapa);
          break;
      }

      $this->cancelaMovimentoAlunoNoCurso($oTurma, $oMatricula);
      $iMatriculasCanceladas++;
      MatriculaRepository::removerMatricula($oMatricula);
    }

    /**
     * Reativamos as regencias da turma
     */
    $lEncerramentoTotal = false;
    if ($iMatriculasCanceladas > 0) {
      $this->reabreRegenciasTurma($oTurma, $oEtapa) ;
    }
    if ($iMatriculasCanceladas == $iTotalMatriculas) {
      $lEncerramentoTotal = true;
    }
    return $lEncerramentoTotal;
  }

  /**
   * Cria uma nova etapa no historico do aluno
   * @param HistoricoAluno        $oHistorico
   * @param Turma                 $oTurma
   * @param Etapa                 $oEtapa
   * @param string                $sResultadoFinal
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacao
   * @return HistoricoEtapaRede
   */
  protected function adicionarEtapaHistorico(HistoricoAluno $oHistorico, Turma $oTurma,
                                             Etapa $oEtapa, $sResultadoFinal, $oProcedimentoAvaliacao) {

    $sSituacao     = "CONCLU�DO";
    $iHistoricoMps = null;
    $iCargaHoraria = $oTurma->getCargaHoraria();
    $iDiasLetivos  = $oTurma->getCalendario()->getDiasLetivos();

    /**
     * Verifica se a turma de EJA j� possui hist�rico com APROVADO PARCIAL
     * Se sim temos que alterar o historico existente
     */
    if ($oTurma->getTipoDaTurma() == 2 && EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa)) {

      $oDaoHistoricoMps = db_utils::getDao("historicomps");
      $sWhere  = " ed62_i_historico = {$oHistorico->getCodigoHistorico()}";
      $sWhere .= " and ed62_i_serie = {$oEtapa->getCodigo()}";
      $sWhere .= " and ed62_c_resultadofinal = 'P' ";

      $sSqlHistoricoMps = $oDaoHistoricoMps->sql_query_file(null, "ed62_i_codigo", null, $sWhere);
      $rsHistoricoMps   = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);

      if ($oDaoHistoricoMps->numrows == 1) {
        $iHistoricoMps = db_utils::fieldsMemory($rsHistoricoMps, 0)->ed62_i_codigo;
      }
    }
    $oEtapaHistorico = new HistoricoEtapaRede($iHistoricoMps);
    /**
     * S� vai somar a carga horaria quando se tratar de turma de EJA com APROVADO PARCIAL
     */
    $iCargaHoraria += $oEtapaHistorico->getCargaHoraria();
    $iDiasLetivos  += $oEtapaHistorico->getDiasLetivos();

    $oEtapaHistorico->setAnoCurso($oTurma->getCalendario()->getAnoExecucao());
    $oEtapaHistorico->setEscola(new Escola(db_getsession("DB_coddepto")));
    $oEtapaHistorico->setMininoParaAprovacao($oProcedimentoAvaliacao->getFormaAvaliacao()->getAproveitamentoMinino());
    $oEtapaHistorico->setCargaHoraria($iCargaHoraria);
    $oEtapaHistorico->setDiasLetivos($iDiasLetivos);
    $oEtapaHistorico->setEtapa($oEtapa);
    $oEtapaHistorico->setSituacaoEtapa($sSituacao);
    $oEtapaHistorico->setResultadoAno($sResultadoFinal);
    $oEtapaHistorico->setLancamentoAutomatico(true);
    $oEtapaHistorico->setTurma($oTurma->getDescricao());
    $oEtapaHistorico->salvar($oHistorico->getCodigoHistorico());
    return $oEtapaHistorico;
  }

  /**
   * Adiciona as diciplinas no Historico para uma Etapa cursada
   *
   * @param HistoricoEtapaRede $oEtapaHistorico
   * @param DiarioAvaliacaoDisciplina $oDisciplina
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacao
   * @param string $sResultadoFinal
   */
  protected function adicionarDisciplinaEtapaHistorico(HistoricoEtapaRede $oEtapaHistorico,
                                                       DiarioAvaliacaoDisciplina $oDisciplina,
                                                       ProcedimentoAvaliacao $oProcedimentoAvaliacao,
                                                       $sResultadoFinal
                                                      ) {

    $sSituacaoHistorico   = "CONCLU�DO";
    $oAmparo              = $oDisciplina->getAmparo();
    $iTotalDeAulas        = $oDisciplina->getRegencia()->getTotalDeAulas();
    $sValorAproveitamento = ArredondamentoNota::formatar($oDisciplina->getResultadoFinal()->getValorAprovacao(),
                                                         $oEtapaHistorico->getAnoCurso()
                                                        );
    $oDisciplinaHistorico = new DisciplinaHistoricoRede();

    if (!$oDisciplina->getRegencia()->isObrigatoria()) {

      $sResultadoFinal = 'A';
      if (trim($sValorAproveitamento) == '') {
        $sValorAproveitamento = '-';
      }
    }

    if ($oAmparo != null && $oAmparo->isTotal()) {

      $sSituacaoHistorico = "AMPARADO";
      if (!$oAmparo->isAdicionadoNaCargaHoraria()) {
        $iTotalDeAulas = 0;
      }
      $sValorAproveitamento = "";
      $oDisciplinaHistorico->setJustificativa($oAmparo->getCodigoJustificativa());
    }
    $oDisciplinaHistorico->setCargaHoraria($iTotalDeAulas);
    $oDisciplinaHistorico->setDisciplina($oDisciplina->getDisciplina());
    $oDisciplinaHistorico->setOrdem(1);
    $oDisciplinaHistorico->setResultadoFinal($sResultadoFinal);
    $oDisciplinaHistorico->setResultadoObtido($sValorAproveitamento);
    $oDisciplinaHistorico->setSituacaoDisciplina($sSituacaoHistorico);
    $oDisciplinaHistorico->setTipoResultado($oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo());
    $oDisciplinaHistorico->setLancamentoAutomatico(true);
    $oDisciplinaHistorico->setOpcional(!$oDisciplina->getRegencia()->isObrigatoria());
    $oDisciplinaHistorico->salvar($oEtapaHistorico->getCodigoEtapa());
  }

  /**
   * Verifica se a turma possui as aulas dadas informadas para cada Periodo
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return boolean
   */
  public function semAulasDadas ($oTurma, $oEtapa) {

    $oDaoRegenciaPeriodo = db_utils::getDao("regenciaperiodo");
    /**
     * Verificamos se a turma possui todos as aulas  dos periodos de avaliacao informados
    */
    $sCamposRegPer       = " ed78_i_regencia,ed78_i_procavaliacao, ";
    $sCamposRegPer      .= " ed78_i_aulasdadas, trim(ed09_c_descr) as ed09_c_descr , ed232_c_descr, ed59_i_ordenacao";
    $sWhereRegPer        = " ed59_i_turma = {$oTurma->getCodigo()} ";
    $sWhereRegPer       .= " AND ed59_i_serie = {$oEtapa->getCodigo()} AND ed59_c_freqglob !='A' ";
    $sWhereRegPer       .= " AND ed59_c_condicao = 'OB'";
    $sSqlRegenciaPeriodo = $oDaoRegenciaPeriodo->sql_query_verifica_periodos("",
                                                                             $sCamposRegPer,
                                                                             "ed09_i_sequencia,ed59_i_ordenacao",
                                                                             $sWhereRegPer
                                                                            );

    $rsRegenciaPeriodo   = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);
    $lSemAulas           = false;
    for ($x = 0; $x < $oDaoRegenciaPeriodo->numrows; $x++) {

      $oDadosRegencia = db_utils::fieldsmemory($rsRegenciaPeriodo, $x);
      if ($oDadosRegencia->ed78_i_aulasdadas == "") {

        $sMensagemPeriodo = " nos per�odos de aula ";
        if ($oDadosRegencia->ed09_c_descr != "") {
          $sMensagemPeriodo = "no per�odo {$oDadosRegencia->ed09_c_descr}";
        }
        $sMensagem  = "Falta informar aulas dadas{$sMensagemPeriodo} ";
        if ($this->oLogger == null) {
           $sMensagem .= " na disciplina {$oDadosRegencia->ed232_c_descr}";
        }
        $oErroMensagem->disciplina = $oDadosRegencia->ed232_c_descr;
        $oErroMensagem->turma      = $oTurma->getCodigo();
        $oErroMensagem->mensagem   = "{$sMensagem}.";
        $this->informarErro($oErroMensagem);
        $lSemAulas = true;
      }
    }
    return $lSemAulas;
  }

  /**
   * Valida se o diario do aluno pode ser encerrado
   * @param DiarioClasse $oDiario
   * @return boolean
   */
  protected function validarDiarioAluno($oDiario, $oErroMensagem) {

    $aPendenciasDiario   = $oDiario->getPendenciasEncerramento();
    $lDiarioSemPendencia = true;

    if (count($aPendenciasDiario > 0)) {

      foreach ($aPendenciasDiario as $sPendencia) {

        $sMensagem                 = "Aluno {$oDiario->getMatricula()->getAluno()->getNome()}: ";
        $sMensagem                .= $sPendencia;
        $oErroMensagem->mensagem   = $sMensagem;
        $this->informarErro($oErroMensagem);
        $lDiarioSemPendencia = false;
      }
    }
    return $lDiarioSemPendencia;
  }

  /**
   * Concluir a matricula do aluno
   * @param integer $oMatricula matricula do aluno
   * @param string $sConclusao Conclusao da matricula S para concluida N para nao concluida
   */
  protected function atualizarConclusaoDaMatricula(Matricula $oMatricula, $sConclusao) {

    $oDaoMatricula                   = db_utils::getDao("matricula");
    $oDaoMatricula->ed60_i_codigo    = $oMatricula->getCodigo();
    $oDaoMatricula->ed60_c_concluida = "{$sConclusao}";
    $oDaoMatricula->ed60_d_datamodif = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoMatricula->alterar($oMatricula->getCodigo());
    if ($oDaoMatricula->erro_status == 0) {

      $sErroMensagem  = "N�o foi possivel alterar situacao da matricula {$oMatricula->getCodigo()}. \n";
      $sErroMensagem .= "Aluno {$oMatricula->getAluno()->getCodigoAluno()} - {$oMatricula->getAluno()->getNome()}";
      throw new BusinessException($sErroMensagem);
    }
  }
  /**
   * Encerra os dados da matricula
   * @param Matricula $oMatricula
   * @param DiarioClasse $oDiario
   */
  protected function encerrarDadosMatricula(Matricula $oMatricula, DiarioClasse $oDiario, $sResultadoFinal) {

    $oErroMensagem        = new stdClass();
    $oErroMensagem->etapa = $oMatricula->getEtapaDeOrigem()->getNome();
    $oErroMensagem->aluno = $oMatricula->getAluno()->getCodigoAluno() . " - " .$oMatricula->getAluno()->getNome();

    $iEscola  = db_getsession("DB_coddepto");
    $oDiario->adicionarDisciplinasReprovadasComoProgressaoParcial();
    $sSituacao           = '';
    $sSituacaoCursoAluno = "APROVADO";
    if ($sResultadoFinal == "R") {
      $sSituacaoCursoAluno = "REPETENTE";
    } else if ($sResultadoFinal == "P") {
      $sSituacaoCursoAluno = "APROVADO PARCIAL";
    }


    if ($oDiario->aprovadoComProgressaoParcial()) {

      $sResultadoFinal     = "A";
      $sSituacaoCursoAluno = "APROVADO";
      $sSituacao           = "COM PROGRESS�O PARCIAL/DEPEND�NCIA";
    }

    $iCodigoAluno = $oMatricula->getAluno()->getCodigoAluno();
    $this->atualizarConclusaoDaMatricula($oMatricula, 'S');

    $oDaoBaseMps                            = db_utils::getDao("basemps");
    $oDaoMatriculaMov                       = db_utils::getDao("matriculamov");
    $oDaoMatriculaMov->ed229_i_matricula    = $oMatricula->getCodigo();
    $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $oDaoMatriculaMov->ed229_c_procedimento = "ENCERRAR AVALIA��ES";
    $sDescricao                             = "MATR�CULA ENCERRADA EM ".date("d/m/Y",db_getsession("DB_datausu"));
    $sDescricao                            .= " COM SITUA��O DE {$sSituacaoCursoAluno} {$sSituacao}";
    $oDaoMatriculaMov->ed229_t_descr        = $sDescricao;
    $oDaoMatriculaMov->ed229_d_dataevento   = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
    $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoMatriculaMov->incluir(null);
    if ($oDaoMatriculaMov->erro_status == 0) {

      throw new BusinessException("Erro ao incluir movimenta��o da matr�cula do aluno.");
    }

    $oProximaEtapa = EtapaRepository::getProximaEtapa($oMatricula->getTurma(), $oMatricula->getEtapaDeOrigem());

    /**
     * Caso a ultima etapa seja vazia, devemos utilizar como base a ultima etapa da base curricular.
     */
    if ($oProximaEtapa == null) {
      //$oProximaEtapa = $oMatricula->getTurma()->getBaseCurricular()->getEtapaFinal();
    }
    if ($oProximaEtapa == null || $sResultadoFinal == "R") {
      $oProximaEtapa = $oMatricula->getEtapaDeOrigem();
    }

    $oDaoAlunoPossib    = db_utils::getDao("alunopossib");
    $sCamposAlunoPossib = " alunopossib.*, alunocurso.*";
    $sWhereAlunoPossib  = " ed56_i_escola = {$iEscola} AND ed56_i_aluno = {$iCodigoAluno}";
    $sSqlAlunoPossib    = $oDaoAlunoPossib->sql_query("", $sCamposAlunoPossib, "", $sWhereAlunoPossib);
    $rsResultPossib     = $oDaoAlunoPossib->sql_record($sSqlAlunoPossib);
    $iLinhasAlunoPoss   = $oDaoAlunoPossib->numrows;
    $iBaseAnterior      = "";
    $iCodigoBase        = "";
    $oDadosAlunoCurso   = null;

    if ($iLinhasAlunoPoss > 0) {

      $oDadosAlunoCurso    = db_utils::fieldsMemory($rsResultPossib, 0);
      $iCodigoBase         = $oMatricula->getTurma()->getBaseCurricular()->getCodigoSequencial();
      $iBaseAnterior       = $iCodigoBase;
      $sWhereProximaSerie  = " ed34_i_base = {$oMatricula->getTurma()->getBaseCurricular()->getCodigoSequencial()} ";
      $sWhereProximaSerie .= " AND ed34_i_serie = {$oProximaEtapa->getCodigo()}";
      $sSqlProximaSerie    = $oDaoBaseMps->sql_query("", "ed34_i_codigo", "", $sWhereProximaSerie);

      $rsProximaSerie      = $oDaoBaseMps->sql_record($sSqlProximaSerie);

      if ($oDaoBaseMps->numrows == 0) {

        $oProximaBase = $oMatricula->getTurma()->getBaseCurricular()->getBaseDeContinuacao();

        if ($oProximaBase != null) {
          $iCodigoBase   = $oProximaBase->getCodigoSequencial();
        }
      }

      /**
       * caso a Proxima etapa seja a mesma, nao existe mais turmas para o encerramemtno
       */
      if ($oProximaEtapa->getCodigo() == $oMatricula->getEtapaDeOrigem()->getCodigo()  &&
          $oMatricula->getTurma()->getBaseCurricular()->encerraCurso() && $sResultadoFinal != "R") {
        $sSituacaoCursoAluno  = "ENCERRADO";
      }
    }

    /**
     * So devemos permitir a rematricula para proxima etapa para se o aluno for aprovado
     */
    $iCodigoEtapa = $oMatricula->getEtapaDeOrigem()->getCodigo();
    $iProximaBase = $iBaseAnterior;

    if ($sResultadoFinal == "A") {

      $iProximaBase = $iCodigoBase;
      $iCodigoEtapa = $oProximaEtapa->getCodigo();
    }

    if ($sResultadoFinal == 'P') {
      $sResultadoFinal = 'A';
    }
    $sSqlAlunoPossib     = " UPDATE alunopossib SET ";
    $sSqlAlunoPossib    .= "                    ed79_i_serie    = {$iCodigoEtapa}, ";
    $sSqlAlunoPossib    .= "                    ed79_i_turno    = {$oMatricula->getTurma()->getTurno()->getCodigoTurno()}, ";
    $sSqlAlunoPossib    .= "                    ed79_i_turmaant = {$oMatricula->getTurma()->getCodigo()}, ";
    $sSqlAlunoPossib    .= "                    ed79_c_resulant = '{$sResultadoFinal}', ";
    $sSqlAlunoPossib    .= "                    ed79_c_situacao = '{$sResultadoFinal}' ";
    $sSqlAlunoPossib    .= "        WHERE ed79_i_alunocurso = {$oDadosAlunoCurso->ed56_i_codigo} ";
    $rsResultAlunoPossib = db_query($sSqlAlunoPossib);

    if (!$rsResultAlunoPossib) {

      $sErroMensagem  = "N�o foi poss�vel mover o aluno {$oMatricula->getAluno()->getNome()} ";
      $sErroMensagem .= "para pr�xima etapa. \n Motivo:\n";
      $sErroMensagem .= pg_last_error();
      throw new BusinessException($sErroMensagem);
    }

    $sSqlAlunoCurso     = " UPDATE alunocurso SET ";
    $sSqlAlunoCurso    .= "                   ed56_c_situacao      = '{$sSituacaoCursoAluno}', ";
    $sSqlAlunoCurso    .= "                   ed56_i_escola        = {$iEscola}, ";
    $sSqlAlunoCurso    .= "                   ed56_i_base          = {$iProximaBase}, ";
    $sSqlAlunoCurso    .= "                   ed56_i_calendario    = {$oDadosAlunoCurso->ed56_i_calendario}, ";
    $sSqlAlunoCurso    .= "                   ed56_i_baseant       = {$iBaseAnterior}, ";
    $sSqlAlunoCurso    .= "                   ed56_i_calendarioant = null, ";
    $sSqlAlunoCurso    .= "                   ed56_c_situacaoant   = '{$oMatricula->getSituacao()}' ";
    $sSqlAlunoCurso    .= "        WHERE ed56_i_codigo = {$oDadosAlunoCurso->ed56_i_codigo} ";
    $rsAlunoCurso        = db_query($sSqlAlunoCurso);

    if (!$rsAlunoCurso) {

      $sErroMensagem  = "N�o foi poss�vel encerrar o aluno {$oMatricula->getAluno()->getNome()} ";
      $sErroMensagem .= "e move-lo para pr�xima base. \n Motivo:\n";
      $sErroMensagem .= pg_last_error();

      throw new BusinessException($sErroMensagem);
    }
  }

  /**
   * Verifica se o aluno esta apto a ser Aprovado ou se Aprovou Parcialmente nas disciplinas
   * --> Para ser reprovado (R) o aluno s� precisa reprovar em uma disciplina
   * --> Para ser aprovado parcialmente (P) o Aluno precisa ter concluido com sucesso a metade do curso
   *     (metade das disciplinas da base curricular)
   * --> Para ser aprovado (A) o aluno tera que possuir aprova��o em todas as diciplinas da base curricular
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @return string
   */
  public static function validaDiarioAlunoEja (Matricula $oMatricula, Etapa $oEtapa) {

    if (count($oMatricula->getDiarioDeClasse()->getDisciplinasComReprovacao()) >= 1) {
      return "R";
    }

    $sSql  = " select ed59_i_turma as turma, regencia.ed59_i_disciplina                                       ";
    $sSql .= "  from base                                                                                     ";
    $sSql .= " inner join regimematdiv    on ed219_i_regimemat      = ed31_i_regimemat                        ";
    $sSql .= " inner join serieregimemat  on ed223_i_regimemat      = ed31_i_regimemat                        ";
    $sSql .= "                           and ed223_i_regimematdiv   = ed219_i_codigo                          ";
    $sSql .= " inner join diario          on ed95_i_serie           = ed223_i_serie                           ";
    $sSql .= " inner join diariofinal     on ed74_i_diario          = ed95_i_codigo                           ";
    $sSql .= " inner join regencia        on ed59_i_codigo          = ed95_i_regencia                         ";
    $sSql .= " where ed31_i_codigo = {$oMatricula->getTurma()->getBaseCurricular()->getCodigoSequencial()}    ";
    $sSql .= "   and ed223_i_serie = {$oEtapa->getCodigo()}                                                   ";
    $sSql .= "   and ed95_i_aluno  = {$oMatricula->getAluno()->getCodigoAluno()}                              ";
    $sSql .= "   and NOT EXISTS (select 1                                                                     ";
    $sSql .= "                     from diario as d                                                           ";
    $sSql .= "                    inner join diariofinal as df on ed74_i_diario          = ed95_i_codigo      ";
    $sSql .= "                    inner join regencia as r     on ed59_i_codigo          = ed95_i_regencia    ";
    $sSql .= "                    where r.ed59_i_turma            = regencia.ed59_i_turma                      ";
    $sSql .= "                      and d.ed95_i_aluno            = diario.ed95_i_aluno ";
    $sSql .= "                      and (df.ed74_c_resultadofinal = 'R'  or trim(df.ed74_c_resultadofinal) = '')";
    $sSql .= "                  );";

    $rsValidaDiario = db_query($sSql);
    $iLinhas        = pg_num_rows($rsValidaDiario);

    $aDisciplinasBase = $oMatricula->getTurma()->getBaseCurricular()->getDisciplina($oEtapa);

    $sResultadoFinal  = "A";

    if ($iLinhas < count($aDisciplinasBase)) {
      $sResultadoFinal = "P";
    }

    return $sResultadoFinal;
  }


  /**
   * Verifica se aluno j� concluiu o curso
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  protected function alunoConcluiuCurso (Matricula $oMatricula) {

    $oDaoAlunoCurso = db_utils::getDao('alunocurso');
    $sWhere         = " ed56_i_aluno = " . $oMatricula->getAluno()->getCodigoAluno();
    $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query_file(null, "ed56_c_situacao", null, $sWhere);
    $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

    if ($oDaoAlunoCurso->numrows > 0) {

      if (db_utils::fieldsMemory($rsAlunoCurso, 0)->ed56_c_situacao == "CONCLU�DO") {
        return true;
      }
    }
    return false;
  }

  /**
   * Valida se a Matricula pode ser encerrada
   * @param Matricula $oMatricula
   */
  protected function validaMatriculaParaEncerramento (Matricula $oMatricula, $oErroMensagem) {

    $oDiario = $oMatricula->getDiarioDeClasse();
    if (!$this->validarDiarioAluno($oDiario, $oErroMensagem)) {
      return false;
    }
    return true;
  }

  /**
   * Retorna o Hist�rico do Aluno
   * @param  Matricula $oMatricula
   * @return HistoricoAluno
   */
  protected function getHistoricoAluno(Matricula $oMatricula) {

    $oTurma          = $oMatricula->getTurma();
    $iCodigoCurso    = $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getCodigo();
    $oHistoricoAluno = $oMatricula->getAluno()->getHistoricoEscolar($iCodigoCurso);

    if ($oHistoricoAluno->getCodigoHistorico() == "") {

      $oHistoricoAluno->setCurso($oTurma->getBaseCurricular()->getCurso()->getCodigo());
      $oHistoricoAluno->salvar();
    }

    return $oHistoricoAluno;
  }

  /**
   * Encerra as regencias de uma turma
   * Esse metodo sera chamado apos encerrar todos alunos da turma
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @throws BusinessException
   * @return boolean
   */
  protected function encerraRegenciasTurma (Turma $oTurma, Etapa $oEtapa) {

    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

      /**
       * Encerramos a regencia referente a cada diario encerrado
       */
      $oDaoRegencia                     = db_utils::getDao("regencia");
      $oDaoRegencia->ed59_d_dataatualiz = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoRegencia->ed59_c_encerrada   = "S";
      $oDaoRegencia->ed59_lancarhistorico = $oRegencia->isLancadaNoHistorico() ? 't' : 'f';
      $oDaoRegencia->ed59_i_codigo      = $oRegencia->getCodigo();
      $oDaoRegencia->alterar($oRegencia->getCodigo());

      if ($oDaoRegencia->erro_status == 0) {

        $sErroMensagem  = " Erro ao encerrar Regencia {$oRegencia->getCodigo()}";
        $sErroMensagem .= $oRegencia->getDisciplina()->getNomeDisciplina();
        throw new BusinessException($sErroMensagem);
      }
    }
    return true;
  }

  /**
   * Reabre as regencias da Turma
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return boolean
   */
  protected function reabreRegenciasTurma (Turma $oTurma, Etapa $oEtapa) {

    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

      $oDaoRegencia = db_utils::getDao("regencia");
      $oDaoRegencia->ed59_c_encerrada = 'N';
      $oDaoRegencia->ed59_lancarhistorico = $oRegencia->isLancadaNoHistorico() ? 't' : 'f';
      $oDaoRegencia->ed59_i_codigo    = $oRegencia->getCodigo();
      $oDaoRegencia->alterar($oRegencia->getCodigo());

      if ($oDaoRegencia->erro_status == "0") {

        $oErroMensagem->mensagem = "Erro ao reabrir Regencia da turma.";
        $this->informarErro($oErroMensagem);
        return false;
      }
    }
    return true;
  }

  /**
   * Encerra o Historico de Turmas Normal ou Multi Etapa
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @return string Retorna o resultado que foi encerrado
   */
  private function encerraTurmaNormal(Matricula $oMatricula, Etapa $oEtapa) {

    $oTurma                 = $oMatricula->getTurma();
    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oHistoricoAluno        = $this->getHistoricoAluno($oMatricula);
    $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

    $sResultadoFinal        = $oDiario->getResultadoFinal();

    /**
     * Valida se a turma gera hist�rico
     * Ate o momento somente as Turmas Normais (1) podem n�o gerar historico
     */
    if (($oTurma->getBaseCurricular()->getCurso()->geraHistorico()
        && $oTurma->getTipoDaTurma() == 1) || $oTurma->getTipoDaTurma() == 3) {

      $this->geraDadosHistoricoAluno($oHistoricoAluno, $oTurma, $oEtapa, $oProcedimentoAvaliacao,
                                     $oDiario, $sResultadoFinal);
    }

    return $sResultadoFinal;
  }// Encerra Turma Normal

  /**
   * geracao dos historicos de Turmas de EJA
   *
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @return string Retorna o resultado que foi encerrado
   */
  private function encerraTurmaEja(Matricula $oMatricula, Etapa $oEtapa) {

    $oTurma                 = $oMatricula->getTurma();
    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oHistoricoAluno        = $this->getHistoricoAluno($oMatricula);
    $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

    $sResultadoFinal        = $oDiario->getResultadoFinal();

    /**
     * Verificamos se a turma � uma de EJA e o resultado final do aluno n�o foi reprovado
     */
    if (EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa) && $sResultadoFinal != "R") {
      $sResultadoFinal = EncerramentoAvaliacao::validaDiarioAlunoEja($oMatricula, $oEtapa);
    }
    $aControleEtapasEncerradas = array();

    /**
     * Em turmas de EJA que permitem a situa��o (APROVADO PARCIAL) n�o devemos gerar hist�rico para
     * resultado REPROVADO
     */
    if ($sResultadoFinal != "R") {

      $oProximaEtapa = EtapaRepository::getProximaEtapa($oTurma, $oEtapa);
      $aTurmaEtapa   = $oTurma->getEtapas();

      foreach ($oTurma->getEtapas() as $oEtapaTurma) {

        if ($oEtapaTurma->getEtapa()->getOrdem() >= $oEtapa->getOrdem()) {

          $aControleEtapasEncerradas[] = $oEtapaTurma->getEtapa()->getCodigo();
          $this->geraDadosHistoricoAluno($oHistoricoAluno, $oTurma, $oEtapaTurma->getEtapa(),
                                         $oProcedimentoAvaliacao, $oDiario, $sResultadoFinal);
        }
      }
    } else if (!EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa)) {

      /**
       * Turma de EJA Normal gera hist�rico de REPROVADO
       */
      $this->geraDadosHistoricoAluno($oHistoricoAluno, $oTurma, $oEtapa,
                                     $oProcedimentoAvaliacao, $oDiario, $sResultadoFinal);
    }

    /**
     * Somente para EJA que permitem a situa��o (APROVADO PARCIAL)
     * Temos que alterar as disciplinas da etapa do historico para A (APROVADO) quando o aluno ja concluiu todas
     * as disciplinas e foi considerado APROVADO
     */
    if (EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa) && $sResultadoFinal == 'A') {

      $sWhere  = "     ed62_i_historico = {$oHistoricoAluno->getCodigoHistorico()} ";
      $sWhere .= " and ed62_i_serie    in (" . implode(",", $aControleEtapasEncerradas) . ") ";
      $sWhere .= " and ed62_c_resultadofinal = 'A' ";

      $oDaoHistoricoMps = db_utils::getDao("historicomps");
      $sSqlHistoricoMps = $oDaoHistoricoMps->sql_query_disciplina_etapa(null, " ed65_i_codigo ", null, $sWhere);
      $rsHistoricoMps   = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
      $iLinhas          = $oDaoHistoricoMps->numrows;

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $iDisciplinaHistorico = db_utils::fieldsMemory($rsHistoricoMps, $i)->ed65_i_codigo;
          $oDisciplinaHistorico = new DisciplinaHistoricoRede($iDisciplinaHistorico);
          $oDisciplinaHistorico->setResultadoFinal($sResultadoFinal);
          $oDisciplinaHistorico->setLancamentoAutomatico(true);
          $oDisciplinaHistorico->salvar($iDisciplinaHistorico);
        }
      }
    }

    return $sResultadoFinal;
  }

  private function cancelaEncerramentoTurmaNormal(Matricula $oMatricula, Etapa $oEtapa) {

    $oTurma                 = $oMatricula->getTurma();
    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oHistoricoAluno        = $this->getHistoricoAluno($oMatricula);
    $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

    /**
     * Cancela as progressoes aprovadas automaticamentes
     */
    foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {

      $oDaoEncerramentoProgressaoParcial  = db_utils::getDao("progressaoparcialalunoencerradodiario");

      $sWhereProgressao           = " ed95_i_aluno = {$oMatricula->getAluno()->getCodigoAluno()} ";
      $sWhereProgressao          .= " AND ed95_i_regencia = {$oDiarioDisciplina->getRegencia()->getCodigo()}";
      $sCampoProgressao           = "ed151_sequencial, ed114_sequencial";
      $sSqlEncerramentoProgressao = $oDaoEncerramentoProgressaoParcial->sql_query_diariofinal(null,
                                                                                              $sCampoProgressao,
                                                                                              null,
                                                                                              $sWhereProgressao
                                                                                             );

      $rsProgessao = $oDaoEncerramentoProgressaoParcial->sql_record($sSqlEncerramentoProgressao);
      if ($oDaoEncerramentoProgressaoParcial->numrows > 0) {

        $oDadosProgressao = db_utils::fieldsMemory($rsProgessao, 0);
        $oDaoEncerramentoProgressaoParcial->excluir($oDadosProgressao->ed151_sequencial);
        if ($oDaoEncerramentoProgressaoParcial->erro_status == 0) {

          $sErroMensagem  = "Erro ao cancelar encerramento  dos dados de progress�o parcial do aluno.\n{$sNomeAluno}.";
          throw new BusinessException($sErroMensagem);
        }
        $oDaoProgressaoParcialAluno                          = db_utils::getDao("progressaoparcialaluno");
        $oDaoProgressaoParcialAluno->ed114_situacaoeducacao  = ProgressaoParcialAluno::ATIVA;
        $oDaoProgressaoParcialAluno->ed114_tipoconclusao     = "null";
        $oDaoProgressaoParcialAluno->ed114_sequencial        = $oDadosProgressao->ed114_sequencial;
        $oDaoProgressaoParcialAluno->alterar($oDadosProgressao->ed114_sequencial);
        if ($oDaoProgressaoParcialAluno->erro_status == 0) {

          $sErroMensagem  = "Erro ao cancelar encerramento  dos dados de progress�o parcial do aluno.\n{$sNomeAluno}.";
          throw new BusinessException($sErroMensagem);
        }
      }
    }
    $this->cancelaHistoricoTurma($oTurma, $oMatricula, $oEtapa);

    /**
     * Remove os dados da progressao parcial do aluno.
     */
    try {
      $oMatricula->getDiarioDeClasse()->removerDisciplinasComProgressaParcial();
    } catch (BusinessException $eErro) {

      $oErroMensagem->turma      = $oMatricula->getTurma()->getCodigo();
      $oErroMensagem->aluno      = $oMatricula->getAluno()->getNome();
      $oErroMensagem->mensagem   = $eErro->getMessage();
      $this->informarErro($oErroMensagem);
    }
  }

  /**
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  protected function alunoComMatriculaPosterior(Matricula $oMatricula) {

    return trim(MatriculaPosterior($oMatricula->getTurma()->getCodigo(),
                                   $oMatricula->getAluno()->getCodigoAluno())) == "SIM";
  }

  /**
   * Cancela os movimentos do historico de turmas de EJA
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @return true;
   */
  private function cancelaEncerramentoTurmaEja(Matricula $oMatricula, Etapa $oEtapa) {

    $oTurma                 = $oMatricula->getTurma();
    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oHistoricoAluno        = $this->getHistoricoAluno($oMatricula);
    $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

    if (EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa) || $oTurma->getTipoDaTurma() == 7) {
      $this->cancelaHistoricoTurmaEjaAprovacaoParcial($oTurma, $oMatricula, $oEtapa);
    } else {
      $this->cancelaHistoricoTurma($oTurma, $oMatricula, $oEtapa);
    }
    return true;
  }

  /**
   * Gera o Historico do Aluno e encerra o Diario
   * @param HistoricoAluno        $oHistoricoAluno
   * @param Turma                 $oTurma
   * @param Etapa                 $oEtapa
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacao
   * @param DiarioClasse          $oDiario
   * @param string                $sResultadoFinal
   * @throws BusinessException
   * @return HistoricoEtapaRede
   */
  private function geraDadosHistoricoAluno(HistoricoAluno $oHistoricoAluno, Turma $oTurma, Etapa $oEtapa,
                                           $oProcedimentoAvaliacao, DiarioClasse $oDiario, $sResultadoFinal) {

    $oEtapaHistorico = $this->adicionarEtapaHistorico($oHistoricoAluno,
                                                      $oTurma,
                                                      $oEtapa,
                                                      $sResultadoFinal,
                                                      $oProcedimentoAvaliacao
                                                     );
    foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {

      if ($oDiarioDisciplina->getRegencia()->isLancadaNoHistorico()) {

        if ($oTurma->getTipoDaTurma() == 1 || $oTurma->getTipoDaTurma() == 3) {
          $sResultadoFinal = $oDiarioDisciplina->getResultadoFinal()->getResultadoFinal();
        }
        $this->adicionarDisciplinaEtapaHistorico($oEtapaHistorico, $oDiarioDisciplina,
                                                 $oProcedimentoAvaliacao, $sResultadoFinal);
      }
    }
    return $oEtapaHistorico;
  }

  /**
   * Encerra Diario do Aluno
   * @param DiarioAvaliacaoDisciplina $oDiario
   * @param string                    $sNomeAluno
   * @throws BusinessException
   * @return boolean
   */
  private function encerraDiario(DiarioAvaliacaoDisciplina $oDiarioDisciplina, Aluno $oAluno) {

    $oDaoDiario = db_utils::getDao("diario");
    $oDaoDiario->ed95_c_encerrado = "S";
    $oDaoDiario->ed95_i_codigo    = $oDiarioDisciplina->getCodigoDiario();
    $oDaoDiario->alterar($oDiarioDisciplina->getCodigoDiario());

    if ($oDaoDiario->erro_status == 0) {

      $sErroMensagem  = "Erro ao encerrar Diario do aluno: ";
      $sErroMensagem .= "{$oAluno->getCodigoAluno()} - {$oAluno()->getNome()}.";

      throw new BusinessException($sErroMensagem);
    }

    /**
     * Encerramos a Progressao em que ele est� Reprovado caso o mesmo foi aprovado na disciplina.
     * e o controle de progressao permite aprovacao na progressao, caso o aluno aprove na Disciplina
     * na turma Regular
     */
    $iEscola              = $oDiarioDisciplina->getRegencia()->getTurma()->getEscola()->getCodigo();
    $oParametroProgressao = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($iEscola);
    
    /**
     * Adicionado a condi��o de Resultado final ser aprovado. 
     * N�o podemos eliminar a dependencia se o aluno foi reprovado na disciplina 
     */
    if ( $oParametroProgressao->disciplinaAprovadaEliminaProgressao() && 
         $oDiarioDisciplina->getResultadoFinal()->getResultadoFinal() == "A") {

      $oDisciplina = $oDiarioDisciplina->getDisciplina();
      $oProgressao = ProgressaoParcialAlunoRepository::getProgressaoDoAlunoReprovadaNaDisciplina($oAluno, $oDisciplina);
      if (!empty($oProgressao)) {
        $oProgressao->encerrarPorAprovacaoNaDisciplina($oDiarioDisciplina->getResultadoFinal());
      }
    }
    return true;
  }

  /**
   * Cancela o encerramento do Diario do Aluno
   * @param DiarioAvaliacaoDisciplina $oDiarioDisciplina
   * @param string                    $sNomeAluno
   * @throws BusinessException
   */
  private function cancelaEncerramentoDiario(DiarioAvaliacaoDisciplina $oDiarioDisciplina, $sNomeAluno) {

    $oDaoDiario                   = db_utils::getDao("diario");
    $oDaoDiario->ed95_c_encerrado = 'N';
    $oDaoDiario->ed95_i_codigo    = $oDiarioDisciplina->getCodigoDiario();
    $oDaoDiario->alterar($oDiarioDisciplina->getCodigoDiario());

    if ($oDaoDiario->erro_status == "0") {

      $sErroMensagem  = "Erro ao cancelar encerramento do di�rio do aluno.\n {$sNomeAluno}.";
      throw new BusinessException($sErroMensagem);
    }
  }

  /**
   * Valida se a turma permite gerar APROVADO PARCIAL
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return boolean
   */
  public static function permiteAprovacaoParcial(Turma $oTurma, Etapa $oEtapa) {

    if ($oTurma->getBaseCurricular()->getCurso()->usaAvaliacaoParcial() &&
        count($oTurma->getDisciplinasPorEtapa($oEtapa)) < count($oTurma->getBaseCurricular()->getDisciplina($oEtapa))) {
      return true;
    }
    return false;
  }

  /**
   * Reativa Matricula do Aluno
   *
   * @param Matricula $oMatricula
   * @throws BusinessException
   */
  private function reativaMatricula(Matricula $oMatricula) {

    $this->atualizarConclusaoDaMatricula($oMatricula, 'N');
    /**
     * Gera Historico do movimento da matricula
     */
    $sDescricaoMovimento                    = "ENCERRAMENTO DE AVALIA��ES CANCELADO EM ";
    $sDescricaoMovimento                   .= date("d/m/Y", db_getsession("DB_datausu"));

    $oDaoMatriculaMov                       = db_utils::getDao("matriculamov");
    $oDaoMatriculaMov->ed229_i_matricula    = $oMatricula->getCodigo();
    $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $oDaoMatriculaMov->ed229_c_procedimento = "CANCELAR ENCERRAMENTO DE AVALIA��ES";
    $oDaoMatriculaMov->ed229_t_descr        = $sDescricaoMovimento;
    $oDaoMatriculaMov->ed229_d_dataevento   = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
    $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoMatriculaMov->incluir(null);
    if ($oDaoMatriculaMov->erro_status == 0) {

      $sErroMensagem  = "Erro ao salvar dados da movimenta��o da matr�cula.\n";
      $sErroMensagem .= "Erro T�cnico: {$oDaoMatriculaMov->erro_msg}";
      throw new BusinessException($sErroMensagem);
    }
  }

  /**
   * Exclui todo o hist�rico do aluno para a etapa cursada
   * OBS.: para turma de EJA com Aprovacao Parcial usar metodo cancelaHistoricoTurmaEjaAprovacaoParcial
   *
   * @param Turma $oTurma
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @throws BusinessException
   * @return true
   */
  private function cancelaHistoricoTurma (Turma $oTurma, Matricula $oMatricula, Etapa $oEtapa) {

    $oHistorico       = $this->getHistoricoAluno($oMatricula);
    $oDaoHistMpsDisc  = db_utils::getDao("histmpsdisc");
    $oDaoHistoricoMps = db_utils::getDao("historicomps");

    $sNomeAluno = "{$oMatricula->getAluno()->getCodigoAluno()} - {$oMatricula->getAluno()->getNome()}";

    /**
     * Busca a no historico do aluno, a etapa que esta sendo cancelada.
     */
    $sWhere  = "     ed62_i_serie = {$oEtapa->getCodigo()} ";
    $sWhere .= " and ed62_i_historico = {$oHistorico->getCodigoHistorico()} ";
    $sWhere .= " and ed62_i_anoref    = {$oTurma->getCalendario()->getAnoExecucao()} ";

    $sSqlHistorico = $oDaoHistoricoMps->sql_query_disciplina_etapa(null, "distinct ed62_i_codigo", null, $sWhere);
    $rsHistorico   = $oDaoHistoricoMps->sql_record($sSqlHistorico);


    /**
     * caso nao existir historico na etapa, ignorar a matricula.
     */
    if ($oDaoHistoricoMps->numrows == 0) {
      return true;
    }

    $iCodigoHistoricoMPS   = db_utils::fieldsMemory($rsHistorico, 0)->ed62_i_codigo;

    /**
     * Deleta as disciplinas da etapa do historico
     */
    $sWhereExclusaoMPSDisc = "ed65_i_historicomps = {$iCodigoHistoricoMPS}";
    $oDaoHistMpsDisc->excluir(null, $sWhereExclusaoMPSDisc);

    if ($oDaoHistMpsDisc->erro_status == "0") {

      $sErroMensagem  = "N�o foi poss�vel excluir as disciplinas do hist�rico {$oHistorico->getCodigoHistorico()}.\n";
      $sErroMensagem .= "Aluno: {$sNomeAluno}";
      throw new BusinessException($sErroMensagem);
    }

    /**
     * Exclui etapa do historico
     */
    $oDaoHistoricoMps->excluir($iCodigoHistoricoMPS);

    if ($oDaoHistoricoMps->erro_status == "0") {

      $sErroMensagem  = "N�o foi poss�vel excluir a etapa do hist�rico {$oHistorico->getCodigoHistorico()}.\n";
      $sErroMensagem .= "Aluno: {$sNomeAluno}";
      $sErroMensagem .= "Erro t�cnico: " . str_replace("\n", "\\n", $oDaoHistoricoMps->erro_msg);
      throw new BusinessException($sErroMensagem);
    }
    return true;
  }
 /**
   * Exclui do historico os dados da turma, etapa e disciplinas que est�o sendo cursada
   *
   * @param Turma $oTurma
   * @param Matricula $oMatricula
   * @param Etapa $oEtapa
   * @throws BusinessException
   */
  private function cancelaHistoricoTurmaEjaAprovacaoParcial (Turma $oTurma, Matricula $oMatricula, Etapa $oEtapa) {

    $oHistorico       = $this->getHistoricoAluno($oMatricula);
    $oDaoHistMpsDisc  = db_utils::getDao("histmpsdisc");
    $oDaoHistoricoMps = db_utils::getDao("historicomps");

    $sNomeAluno  = "{$oMatricula->getAluno()->getCodigoAluno()} - ";
    $sNomeAluno .= "{$oMatricula->getAluno()->getNome()}.";

    $iCodigoEtapa  = $oEtapa->getCodigo();
    $aCodigoEtapas = array();

    foreach ($oTurma->getEtapas() as $oEtapaTurma) {

      if ($oEtapaTurma->getEtapa()->getOrdem() >= $oMatricula->getEtapaDeOrigem()->getOrdem()) {
        $aCodigoEtapas[] = $oEtapaTurma->getEtapa()->getCodigo();
      }
    }

    if (count($aCodigoEtapas) > 0) {
      $iCodigoEtapa = implode(", ", $aCodigoEtapas);
    }

    $sWhereHistMps  = " ed62_i_escola         = " . db_getsession("DB_coddepto");
    $sWhereHistMps .= " and ed62_i_historico  = {$oHistorico->getCodigoHistorico()} ";
    $sWhereHistMps .= " and ed62_i_serie      in ({$iCodigoEtapa}) ";


    /**
     * Itera sobre as disciplinas da turma para
     * Deletar do hist�rio os vinculos das disciplinas para a(as) Etapa(as)
     */
    $aHistoricos = array();
    foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

      $sWhereDisciplina  = $sWhereHistMps;
      $sWhereDisciplina .= " and ed65_i_disciplina = {$oRegencia->getDisciplina()->getCodigoDisciplina()} ";

      $sSqlHistMpsDisc = $oDaoHistoricoMps->sql_query_disciplina_etapa("", "ed65_i_codigo, ed65_i_historicomps", "", $sWhereDisciplina);
      $rsHistMpsDisc   = $oDaoHistoricoMps->sql_record($sSqlHistMpsDisc);
      $iLinhas         = $oDaoHistoricoMps->numrows;

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $iCodigoHistMpsDisc             = db_utils::fieldsMemory($rsHistMpsDisc, $i)->ed65_i_codigo;
          $aHistoricos[] = db_utils::fieldsMemory($rsHistMpsDisc, $i)->ed65_i_historicomps;
          $oDaoHistMpsDisc->ed65_i_codigo = $iCodigoHistMpsDisc;
          $oDaoHistMpsDisc->excluir($iCodigoHistMpsDisc);

          if ($oDaoHistMpsDisc->erro_status == 0) {

            $sErroMensagem  = "Erro ao cancelar dados do historico {$oHistorico->getCodigoHistorico()}.\n";
            $sErroMensagem .= "{$sNomeAluno}";
            throw new BusinessException($sErroMensagem);
          }
        }
      }
    }

    /**
     * Quando tratamos de turma de EJA com APROVADO PARCIAL devemos ter o cuidado na hora de excluir o historico
     * Esse tipo de turma <i>N�O tem todas as disciplinas da Base Curricular</i>, por�m o historico � da
     * Etapa e n�o da Turma.
     * Portanto devemos remover apenas as disciplinas vinculadas a Turma para as Etapas em curso.
     * Antes de remover a Etapa do historico, devemos nos assegurar que esta Etapa do Historico (historicomps)
     * n�o tem nenhuma disciplina vinculada. Caso a exista, n�o podemos exclui-las, devemos dar um update no
     * resultado final, tanto das disciplinas (histmpsdisc) quando da etapa (historicomps), para 'P' (APROVADO PARCIAL)
     */

    $sWhereEtapaHistorico  = $sWhereHistMps;
    $sWhereEtapaHistorico .= " and not exists(select 1 ";
    $sWhereEtapaHistorico .= "                  from histmpsdisc where ed65_i_historicomps = ed62_i_codigo)";

    $sSqlHistoricoMps = $oDaoHistoricoMps->sql_query_disciplina_etapa("", " ed62_i_codigo ", "", $sWhereEtapaHistorico);

    $rsHistoricoMps   = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
    $iLinhasMps          = $oDaoHistoricoMps->numrows;
    if ($iLinhasMps > 0) {

      /**
       * Deleta de historicomps o vinculo com a Etapa cursada a se a Etapa n�o tiver mais nenhum vinculo com histmpsdisc
       */
      for ($i = 0; $i < $iLinhasMps; $i++) {

        $iCodigoHistoricoMPS             = db_utils::fieldsMemory($rsHistoricoMps, $i)->ed62_i_codigo;
        $oDaoHistoricoMps->ed62_i_codigo = $iCodigoHistoricoMPS;
        $oDaoHistoricoMps->excluir($iCodigoHistoricoMPS);

        if ($oDaoHistoricoMps->erro_status == "0") {

          $sErroMensagem = "Erro ao cancelar dados das disciplinas do historico.\n{$sNomeAluno}.";
          throw new BusinessException($sErroMensagem);
        }
      }
    } else {

      $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("",
                                                          "ed62_i_codigo, ed62_i_qtdch, ed62_i_diasletivos ",
                                                          "",
                                                          $sWhereHistMps);
      $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
      $iLinhaEtapa         = $oDaoHistoricoMps->numrows;

      if ($iLinhaEtapa > 0) {

        for ($i = 0; $i < $iLinhaEtapa; $i++) {

          $oDadosHistorico     = db_utils::fieldsMemory($rsHistoricoMps, $i);
          $iCodigoHistoricoMPS = $oDadosHistorico->ed62_i_codigo;

          $sSqlUpHistMpsDisc   = " UPDATE histmpsdisc ";
          $sSqlUpHistMpsDisc  .= "    SET ed65_c_resultadofinal = 'P' ";
          $sSqlUpHistMpsDisc  .= "  WHERE ed65_i_historicomps = {$iCodigoHistoricoMPS} ";
          $rsUpHistMps         = db_query($sSqlUpHistMpsDisc);

          if (!$rsUpHistMps) {

            $sErroMensagem = "Erro ao alterar dados  do historico.\n{$sNomeAluno}.";
            throw new BusinessException($sErroMensagem);
          }

          $iDiasLetivosTurmas                      = $oTurma->getCalendario()->getDiasLetivos();
          $oDaoHistoricoMps->ed62_c_resultadofinal = 'P';
          $oDaoHistoricoMps->ed62_i_qtdch          = $oDadosHistorico->ed62_i_qtdch - $oTurma->getCargaHoraria();
          $oDaoHistoricoMps->ed62_i_diasletivos    = $oDadosHistorico->ed62_i_diasletivos - $iDiasLetivosTurmas;
          $oDaoHistoricoMps->ed62_i_codigo         = $iCodigoHistoricoMPS;
          $oDaoHistoricoMps->alterar($iCodigoHistoricoMPS);

          if ($oDaoHistoricoMps->erro_status == "0") {

            $sErroMensagem = "Erro ao cancelar dados das disciplinas do historico.\n{$sNomeAluno}.";
            throw new BusinessException($sErroMensagem);
          }
        }
      }
    }
    return true;
  }

  /**
   * Cancela os movimentos nas tabelas alunocurso e alunopossib
   * @param Turma $oTurma
   * @param Matricula $oMatricula
   * @throws BusinessException
   * @return boolean
   */
  private function cancelaMovimentoAlunoNoCurso(Turma $oTurma, Matricula $oMatricula) {

    $oDaoAlunoCurso = db_utils::getDao("alunocurso");

    $sCamposAlunoCurso  = " ed56_i_codigo , ed47_v_nome, ed56_c_situacao,  ";
    $sCamposAlunoCurso .= " ed56_i_base as base_atual, ed56_i_calendario as calendario_atual";
    $sWhereAlunoCurso   = " ed56_i_aluno = " . $oMatricula->getAluno()->getCodigoAluno();

    $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", $sCamposAlunoCurso, "", $sWhereAlunoCurso);
    $rsAlunoCurso       = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

    if ($oDaoAlunoCurso->numrows > 0) {

      $oDadosAlunoCurso = db_utils::fieldsMemory($rsAlunoCurso, 0);

      if ($oDadosAlunoCurso->calendario_atual == $oTurma->getCalendario()->getCodigo()) {

        $oTurmaAnterior = $oMatricula->getTurmaAnterior();
        $iTurmaAnterior = "null";
        if (!empty($oTurmaAnterior)) {

          $iTurmaAnterior = $oMatricula->getTurmaAnterior()->getCodigo();
        }

        $sSqlUpAlunoCurso  = " UPDATE alunocurso ";
        $sSqlUpAlunoCurso .= "    SET ed56_i_base       = {$oTurma->getBaseCurricular()->getCodigoSequencial()}, ";
        $sSqlUpAlunoCurso .= "        ed56_i_calendario = {$oTurma->getCalendario()->getCodigo()},  ";
        $sSqlUpAlunoCurso .= "        ed56_c_situacao   = '{$oMatricula->getSituacao()}' ";
        $sSqlUpAlunoCurso .= "  WHERE ed56_i_codigo = {$oDadosAlunoCurso->ed56_i_codigo} ";
        $rsUpAlunoCurso    = db_query($sSqlUpAlunoCurso);

        if (!$rsUpAlunoCurso) {

          $sErroMensagem = "Erro ao cancelar dados do curso do aluno.";
          throw new BusinessException($sErroMensagem);
        }
        $sSqlUpdAlunoPossib  = " UPDATE alunopossib                       ";
        $sSqlUpdAlunoPossib .= "    SET ed79_i_serie    = {$oMatricula->getEtapaDeOrigem()->getCodigo()},  ";
        $sSqlUpdAlunoPossib .= "        ed79_i_turno    = {$oTurma->getTurno()->getCodigoTurno()},  ";
        $sSqlUpdAlunoPossib .= "        ed79_i_turmaant = $iTurmaAnterior,  ";
        $sSqlUpdAlunoPossib .= "        ed79_c_resulant = '{$oMatricula->getResultadoFinalAnterior()}' ";
        $sSqlUpdAlunoPossib .= "  WHERE ed79_i_alunocurso = {$oDadosAlunoCurso->ed56_i_codigo} ";
        $rsUpAlunoPossib     = db_query($sSqlUpdAlunoPossib);
        if (!$rsUpAlunoPossib) {

          $sErroMensagem = "Erro ao cancelar dados da pre matricula do aluno";
          throw new BusinessException($sErroMensagem);
        }
      }
    }
    return true;
  }
}