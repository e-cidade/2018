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
 * Classe referente a troca de turma de um aluno
 * @package educacao
 * @author Fabio Esteves <fabio.esteves@dbseller.com.br>
 *
 */
class TrocaTurma {

  /**
   * Instancia de Matricula
   * @var Matricula
   */
  protected $oMatricula;

  /**
   * Instancia de Turma
   * @var Turma
   */
  protected $oTurma;

  /**
   * Turnos para vínculo da matrícula
   * @var string
   */
  protected $sTurno;

  /**
   * Construtor da classe. Recebe uma instancia da Matricula e da turma de destino
   * @param Matricula $oMatricula
   * @param Turma $oTurma
   * @param string $sTurno - Turnos a serem vinculadas a matrícula do aluno
   */
  public function __construct(Matricula $oMatricula, Turma $oTurma, $sTurno = null) {

    $this->oMatricula = $oMatricula;
    $this->oTurma     = $oTurma;
    $this->sTurno     = $sTurno;
  }

  /**
   * Retorna uma instancia de matricula
   * @return Matricula
   */
  public function getMatricula() {
    return $this->oMatricula;
  }

  /**
   * Retorna uma instancia da turma de destino
   * @return Turma
   */
  public function getTurma() {
    return $this->oTurma;
  }

  /**
   * Retorna uma instancia de matricula, com os dados da matricula anterior, com situacao de TROCA DE TURMA
   * @return Matricula
   */
  public function getMatriculaAnterior() {

    $iMatricula               = $this->getMatricula()->getMatricula();
    $oDaoMatriculaAnterior    = db_utils::getDao("matricula");
    $sCamposMatriculaAnterior = "ed60_i_codigo, ed60_i_turma";
    $sWhereMatriculaAnterior  = "ed60_c_situacao = 'TROCA DE TURMA' AND ed60_matricula = {$iMatricula}";
    $sSqlMatriculaAnterior    = $oDaoMatriculaAnterior->sql_query_file(null,
                                                                       $sCamposMatriculaAnterior,
                                                                       "ed60_i_codigo desc",
                                                                       $sWhereMatriculaAnterior
                                                                      );

    $rsMatriculaAnterior      = $oDaoMatriculaAnterior->sql_record($sSqlMatriculaAnterior);
    $iLinhasMatriculaAnterior = $oDaoMatriculaAnterior->numrows;

    if ($iLinhasMatriculaAnterior > 0) {

      for ($iContador = 0; $iContador < $iLinhasMatriculaAnterior; $iContador++) {

        $oDadosMatriculaAnterior = db_utils::fieldsMemory($rsMatriculaAnterior, $iContador);

        if (   !empty($oDadosMatriculaAnterior->ed60_i_turma)
            && $oDadosMatriculaAnterior->ed60_i_turma == $this->getMatricula()->getTurmaAnterior()->getCodigo()) {

          $oMatriculaAnterior = MatriculaRepository::getMatriculaByCodigo($oDadosMatriculaAnterior->ed60_i_codigo);
          break;
        }
      }
    }

    return $oMatriculaAnterior;
  }

  /**
   * Atualiza o diario de uma matricula anterior, com base no diario da matricula atual
   */
  public function atualizarDiario(Matricula $oMatriculaAnterior) {

    $oDiarioClasseAtual = $this->getMatricula()->getDiarioDeClasse();
    $oMatriculaAnterior->getDiarioDeClasse()->atualizar($oDiarioClasseAtual);
  }

  /**
   * Cancela uma troca de turma de um aluno
   */
  public function cancelar() {

    $oMatriculaAnterior = $this->getMatriculaAnterior();
    $oMatriculaAtual    = $this->getMatricula();

    /**
     * Alteramos a situacao da matricula anterior para MATRICULADO
     */
    $oMatriculaAnterior->setSituacao("MATRICULADO");
    $oMatriculaAnterior->setAtiva(true);

    /**
     * Atualizamos o diario anterior (com situacao TROCA DE TURMA), para receber os dados do diario da matricula
     * atual (com situacao MATRICULADO)
     */
    $this->atualizarDiario($oMatriculaAnterior);

    /**
     * Pegamos o diario da matricula atual, e removemos por completo
     */
    $oMatriculaAtual->getDiarioDeClasse()->remover();

    /**
     * Removemos a matricula atual
     */
    $oMatriculaAtual->remover($oMatriculaAnterior);

    /**
     * Salvamos os dados da matricula anterior
     */
    $oMatriculaAnterior->setDataEncerramento( null );
    $oMatriculaAnterior->salvar();
  }

  /**
   * Retorna a lista de Regências que estao presentes em ambas as turmas
   * @return array
   */
  public function getRegenciasTrocaTurmaSemConflito() {

    $aListaRegenciasCompativeis = array();
    $oEtapaOrigem               = $this->getMatricula()->getEtapaDeOrigem();
    $aRegenciasTurmaOrigem      = $this->getMatricula()->getTurma()->getDisciplinasPorEtapa($oEtapaOrigem);
    $aRegenciasTurmaDestino     = $this->getTurma()->getDisciplinasPorEtapa($oEtapaOrigem);
    foreach ($aRegenciasTurmaOrigem as $oRegenciaOrigem) {

      foreach ($aRegenciasTurmaDestino as $oRegenciaDestino) {

        if ($oRegenciaDestino->getDisciplina()->getCodigoDisciplina() ==
            $oRegenciaOrigem->getDisciplina()->getCodigoDisciplina()) {

          $oEtapaCorrespondente          = new stdClass();
          $oEtapaCorrespondente->origem  = $oRegenciaOrigem;
          $oEtapaCorrespondente->destino = $oRegenciaDestino;
          $aListaRegenciasCompativeis[]  = $oEtapaCorrespondente;
        }
      }
    }
    return $aListaRegenciasCompativeis;
  }

  /**
   * Retorna as Regências que nao estão presentes na turma de destino.
   * @return multitype:Ambigous <Regencia, multitype:unknown >
   */
  public function getRegenciasTrocaTurmaInconsistentes() {

    $aListaRegenciasInconsistente = array();
    $oEtapaOrigem                 = $this->getMatricula()->getEtapaDeOrigem();
    $aRegenciasTurmaOrigem        = $this->getMatricula()->getTurma()->getDisciplinasPorEtapa($oEtapaOrigem);
    $aRegenciasTurmaDestino       = $this->getTurma()->getDisciplinasPorEtapa($oEtapaOrigem);
    foreach ($aRegenciasTurmaOrigem as $oRegenciaOrigem) {

      $lEncontrouRegencia = false;
      foreach ($aRegenciasTurmaDestino as $oRegenciaDestino) {
        if ($oRegenciaDestino->getDisciplina()->getCodigoDisciplina() ==
            $oRegenciaOrigem->getDisciplina()->getCodigoDisciplina()) {

          $lEncontrouRegencia = true;
          break;
        }
      }

      if (!$lEncontrouRegencia) {
        $aListaRegenciasInconsistente[] = $oRegenciaOrigem;
      }
    }
    return $aListaRegenciasInconsistente;
  }

  /**
   * Retorna todas as Regencias da turma de destino, que nao possuem vinculo com as disciplinas da turma de origem.
   * @return multitype:Ambigous <Regencia, multitype:unknown >
   */
  public function getDisciplinasTurmaDestinoSemVinculo() {

    $aRegenciasNaoVinculadas = array();
    $oEtapaOrigem            = $this->getMatricula()->getEtapaDeOrigem();
    $aRegenciasVinculadas    = $this->getRegenciasTrocaTurmaSemConflito();
    $aRegenciasTurmaDestino  = $this->getTurma()->getDisciplinasPorEtapa($oEtapaOrigem);
    foreach ($aRegenciasTurmaDestino as $oRegenciaDestino) {

      $lRegenciaVinculada = false;
      foreach ($aRegenciasVinculadas as $oRegenciaVinculada) {

        if ($oRegenciaDestino->getCodigo() == $oRegenciaVinculada->destino->getCodigo()) {

          $lRegenciaVinculada = true;
          break;
        }
      }

      if (!$lRegenciaVinculada) {
        $aRegenciasNaoVinculadas[] = $oRegenciaDestino;
      }
    }
    return $aRegenciasNaoVinculadas;
  }

  /**
   * Realiza a troca de turma sem gravar dados de movimentacao
   * @param array $aRegenciasVinculadas array com dados das regenvcias que sao diferentes entre as turmas,
   * mas equivalentes
   * @throws DBException
   * @throws ParameterException
   * @throws BusinessException
   */
  public function trocarTurmaSemRegistro(array $aRegenciasVinculadas = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException('Sem transação com o banco de dados ativa.');
    }

    if (is_array($aRegenciasVinculadas)) {
      foreach ($aRegenciasVinculadas as $oRegencia) {

        if (!isset($oRegencia->origem) || !isset($oRegencia->destino)) {
          throw new ParameterException('Os elementos passados no array $aRegenciasVinculadas não são válidos.');
        }

        $sMensagemExcecao = 'Os elementos passados no array aRegenciasVinculadas não é uma instância de Regência.';
        if ($oRegencia->origem != '' && !$oRegencia->origem instanceof Regencia) {
          throw new ParameterException($sMensagemExcecao);
        }

        if ($oRegencia->destino != '' && !$oRegencia->destino instanceof Regencia) {
          throw new ParameterException($sMensagemExcecao);
        }
      }
    }

    if (!$this->getMatricula()->isAtiva() || $this->getMatricula()->isConcluida()) {

      $sNomeAluno       = trim($this->getMatricula()->getAluno()->getNome());
      $sNomeTurma       = $this->getTurma()->getDescricao();
      $sMensagemExcecao = "A matrícula do aluno {$sNomeAluno} na turma {$sNomeTurma} já está concluída ou está inativa";
      throw new BusinessException($sMensagemExcecao);
    }

    $oTurmaOrigem = $this->getMatricula()->getTurma();
    $oEtapaAluno  = $this->getMatricula()->getEtapaDeOrigem();
    if ($oTurmaOrigem->getCodigo() == $this->getTurma()->getCodigo()) {
      throw new BusinessException('o Aluno não pode ser trocado para a mesma turma.');
    }

    /**
     * permitir apenas trocas de turma entre calendarios de mesmoa ano
     */
    if ($oTurmaOrigem->getCalendario()->getAnoExecucao() != $this->getTurma()->getCalendario()->getAnoExecucao()) {

      $sMensagemExcecao  = "Turmas com calendários de anos diferentes.\n";
      $sMensagemExcecao .= "Troca de turma permitida apenas para turmas calendários de mesmo ano.";
      throw new BusinessException($sMensagemExcecao);
    }

    if ($this->getTurma()->encerradaNaEtapa($oEtapaAluno)) {

      $sNomeTurma        = $this->getTurma()->getDescricao();
      $sMensagemExcecao  = "Turma {$sNomeTurma} já encerrada.\n ";
      $sMensagemExcecao .= "Troca de turma permitida apenas para turmas de destino não encerradas.";
      throw new BusinessException($sMensagemExcecao);
    }

    $oProcedimentoOrigem = $this->oMatricula->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapaAluno);
    $oDiarioClasseAtual  = $this->getMatricula()->getDiarioDeClasse();
    $aDisciplinas        = $oDiarioClasseAtual->getDisciplinas();
    $this->getMatricula()->setTurma($this->getTurma());

    $sNumeroOrdemAluno = null;

    if ( $this->getTurma()->isClassificada() ) {
      $sNumeroOrdemAluno = $this->getTurma()->getUltimoNumeroClassificado() + 1;
    }

    $this->getMatricula()->setNumeroOrdemAluno( $sNumeroOrdemAluno );
    $this->getMatricula()->salvar();

    $oProcedimentoDestino = $this->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapaAluno);

    /**
     * Apenas migramos o aproveitamento do aluno, quando os procedimentos de avaliacao são compativeis.
     */
    if ($oProcedimentoOrigem->temEquivalencia($oProcedimentoDestino)) {

      $oNovoDiarioClasse = new DiarioClasse($this->getMatricula());
      $oNovoDiarioClasse->atualizar($oDiarioClasseAtual, $aRegenciasVinculadas);
    }
    $oDiarioClasseAtual->remover();

    $oTurmaOrigem->salvar();
    $this->getTurma()->salvar();

    $aMovimentacoesAjustarNomeTurma = array("'MATRICULAR ALUNOS TRANSFERIDOS'",
                                            "'TROCAR ALUNO DE TURMA'",
                                            "'TROCAR ALUNO DE MODALIDADE'",
                                            "'REMATRICULAR ALUNO'",
                                            "'MATRICULAR ALUNO'"
                                           );

    /**
     * Atualizamos os registros na tabela matriculamov para a turma atual do aluno.
     * São atualizados os registros da matricula atual do aluno, dos movimentos listados
     * do array $aMovimentacoesAjustarNomeTurma.
     */

    $oDaoMatriculaMov        = db_utils::getDao("matriculamov");
    $sWhereMatriculaMov      = "ed229_i_matricula              = {$this->getMatricula()->getCodigo()} ";
    $sWhereMatriculaMov     .= "and trim(ed229_c_procedimento) in(".implode(",", $aMovimentacoesAjustarNomeTurma).")";
    $sSqlMatriculaMovAlterar = $oDaoMatriculaMov->sql_query_file(null,
                                                                 "ed229_i_codigo, ed229_t_descr",
                                                                 null,
                                                                 $sWhereMatriculaMov
                                                                );

    $rsMatriculaMovAlterar = $oDaoMatriculaMov->sql_record($sSqlMatriculaMovAlterar);
    $aMatriculasMovAlterar = db_utils::getCollectionByRecord($rsMatriculaMovAlterar);
    foreach ($aMatriculasMovAlterar as $oMatriculaMovAlterar) {

      $oDaoMatriculaMov->ed229_t_descr  = str_replace(trim($oTurmaOrigem->getDescricao()),
                                                      trim($this->getTurma()->getDescricao()),
                                                      $oMatriculaMovAlterar->ed229_t_descr
                                                     );
      $oDaoMatriculaMov->ed229_i_codigo = $oMatriculaMovAlterar->ed229_i_codigo;
      $oDaoMatriculaMov->alterar($oMatriculaMovAlterar->ed229_i_codigo);
      if ($oDaoMatriculaMov->erro_status == 0) {
        throw new BusinessException("Erro ao trocar aluno de turma");
      }
    }

    /**
     * Corrigimos a numeracao da turma Anterior
     */
    if ($oTurmaOrigem->isClassificada()) {
      $oTurmaOrigem->reclassificarNumeracaoDaTurma();
    }

    $this->atualizaMatriculaTurno();
  }

  /**
   * Atualiza as informações da matrícula na tabela matriculaturnoreferente
   * @throws DBException
   */
  public function atualizaMatriculaTurno() {

    $oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();

    $aTurnosReferentes = $this->getTurma()->getTurnoReferente();
    $oDaoMatriculaTurnoReferente->excluir( null, "ed337_matricula = {$this->getMatricula()->getCodigo()}" );

    if ( $oDaoMatriculaTurnoReferente->erro_status == 0 ) {
      throw new DBException( "Erro ao excluir a matrícula de matriculaturnoreferente:\n" . $oDaoMatriculaTurnoReferente->erro_msg );
    }

    if ( empty( $this->sTurno ) ) {
      throw new BusinessException( "Turno(s) não informado(s)." );
    }

    $aTurnos = explode( ",", $this->sTurno );

    foreach( $aTurnos as $iTurnoReferente ) {

      $oDaoMatriculaTurnoReferente->ed337_matricula           = $this->getMatricula()->getCodigo();
      $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $aTurnosReferentes[$iTurnoReferente]->ed336_codigo;
      $oDaoMatriculaTurnoReferente->incluir( null );

      if ( $oDaoMatriculaTurnoReferente->erro_status == 0 ) {
        throw new DBException( "Erro ao incluir a matrícula em matriculaturnoreferente:\n" . $oDaoMatriculaTurnoReferente->erro_msg );
      }
    }
  }
}
?>