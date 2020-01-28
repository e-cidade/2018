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

define("URL_MENSAGEM_PROGRESSAOPARCIALALUNO", "educacao.escola.ProgressaoParcialAluno.");

 /**
  * Progressao parcial de uma Aluno
  * @author Iuri Guntchnigg iuri@dbseller.com.br
  * @package educacao
  * @subpackage progressaoparcial
  *
  */
final class ProgressaoParcialAluno {

  /**
   * Aluno da progressao parcial
   * @var Aluno
   */
  private $oAluno;

  /**
   * Codigo da progressao parcial
   * @var integer
   */
  private $iCodigoProgressaoParcial;

  /**
   * Vinculo com a regencia
   * @var ProgressaoParcialVinculoDisciplina
   */
  private $oProgressaoParcialVinculoDisciplina = null;

  /**
   * Disciplina em que o aluno está em progressao parcial
   * @var Disciplina
   */
  private $oDisciplina;

  /**
   * Etapa emq ue o aluno fico em progressao parcial
   * @var Etapa
   */
  private $oEtapa;
  /**
   * Código do diario final
   * @var integer
   */
  private $iCodigoDiarioFinal = null;

  /**
   * Resultado final da progressao
   * @var ProgressaoParcialAlunoResultadoFinal
   */
  private $oResultadoFinal;

  /**
   * Verifica se a progressao já esta encerrada
   * @var boolean
   */
  private $lConcluida = false;

  /**
   * progressao ativa
   * @var boolean
   */
  private $lAtiva = false;

  /**
   * Tipo de conclusao da progressao parcial
   * 1 - Concluido pela progressao
   * 2 - Concluido pela etapa regular
   * @var integer
   */
  private $iTipoConclusao;

  /**
   * Ano em que foi gerada a progressão parcial
   * @var integer
   */
  private $iAno;

  /**
   * Instância da escola de vínculo da progressão
   * @var Escola
   */
  private $oEscola = null;

  /**
   * Situação da progressao
   * @var SituacaoEducacao
   */
  private $oSituacaoProgressaoParcial;

  /**
   * Constantes para as situações
   */
  const ATIVA     = 1;
  const INATIVA   = 2;
  const CONCLUIDA = 3;

  /**
   * Método construtor da classe
   * @param  integer|null $iCodigoProgressao
   * @throws DBException
   */
  public function __construct($iCodigoProgressao = null) {

    if (!empty($iCodigoProgressao)) {

      $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
      $sSqlProgressaoAluno        = $oDaoProgressaoParcialAluno->sql_query_aluno_em_progressao($iCodigoProgressao);
      $rsProgressoAluno           = db_query($sSqlProgressaoAluno);

      if( !$rsProgressoAluno ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_buscar_progressao', $oErro ) );
      }

      if (pg_num_rows( $rsProgressoAluno ) == 1) {

        $oDadosProgressao               = db_utils::fieldsMemory($rsProgressoAluno, 0);

        $this->oAluno                     = AlunoRepository::getAlunoByCodigo($oDadosProgressao->ed114_aluno);
        $this->oDisciplina                = DisciplinaRepository::getDisciplinaByCodigo($oDadosProgressao->ed114_disciplina);
        $this->oEtapa                     = EtapaRepository::getEtapaByCodigo($oDadosProgressao->ed114_serie);
        $this->iCodigoDiarioFinal         = empty($oDadosProgressao->ed107_diariofinal) ? null : $oDadosProgressao->ed107_diariofinal;
        $this->iCodigoProgressaoParcial   = $oDadosProgressao->ed114_sequencial;
        $this->lConcluida                 = $oDadosProgressao->ed114_situacaoeducacao == self::CONCLUIDA;
        $this->lAtiva                     = $oDadosProgressao->ed114_situacaoeducacao == self::ATIVA;
        $this->oSituacaoProgressaoParcial = SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo($oDadosProgressao->ed114_situacaoeducacao);
        $this->iTipoConclusao             = $oDadosProgressao->ed114_tipoconclusao;
        $this->iAno                       = $oDadosProgressao->ed114_ano;
        $this->oEscola                    = EscolaRepository::getEscolaByCodigo( $oDadosProgressao->ed114_escola );
        unset($oDadosProgressao);
      }
    }
  }

  /**
   * Define o Aluno que ficou em dependencia
   * @param Aluno $oAluno
   */
  public function setAluno(Aluno $oAluno) {
    $this->oAluno = $oAluno;
  }

  /**
   * Retorna o aluno da dependencia
   * @return Aluno
   */
  public function getAluno() {
    return $this->oAluno;
  }

  /**
   * define a etapa em que o aluno ficou em dependencia
   * @param Etapa $oEtapa
   */
  public function setEtapa(Etapa $oEtapa) {
    $this->oEtapa = $oEtapa;
  }

  /**
   * Retorna a etapa em que o aluno ficou em dependencia
   * @return Etapa
   */
  public function getEtapa() {
    return $this->oEtapa;
  }

  /**
   *  Código gerado da progressao parcial
   * @return integer
   */
  public function getCodigoProgressaoParcial() {
    return $this->iCodigoProgressaoParcial;
  }

  /**
   * retorna qual disciplina o aluno esta em dependencia
   * @return Disciplina Disciplina em dependencia
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * Define a disciplina em que o aluno está em dependencia
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina(Disciplina $oDisciplina) {
    $this->oDisciplina = $oDisciplina;
  }

  /**
   * Retorna o codigo do diario final
   * Retorna o codigo final que gerou a dependencia do aluno
   * @return integer codigo do diario final
   */
  public function getCodigoDiarioFinal() {
    return $this->iCodigoDiarioFinal;
  }

  /**
   * Define o codigo do diario final
   * Define o codigo do diario de classe que gerou a dependencia
   * @param  integer $iCodigoDiarioFinal Codigo do diario final
   */
  public function setCodigoDiarioFinal($iCodigoDiarioFinal) {
    $this->iCodigoDiarioFinal = $iCodigoDiarioFinal;
  }

  /**
   * Retorna o Vinculo da progressao parcial com uma regencia
   * @param  integer $iCodigoRegencia
   * @return ProgressaoParcialVinculoDisciplina Dados do Vinculo
   * @throws DBException
   */
  public function getVinculoRegencia($iCodigoRegencia = null) {

    if (empty($this->oProgressaoParcialVinculoDisciplina) && !empty($this->iCodigoProgressaoParcial)) {

      $oDaoProgressaoParcialVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");

      $sWhere  = "ed150_progressaoparcialaluno = {$this->getCodigoProgressaoParcial()} ";

      if ( !empty($iCodigoRegencia) ) {
        $sWhere .= " and ed115_regencia = {$iCodigoRegencia} ";
      }

      $sSqlProgressaoParcialVinculo = $oDaoProgressaoParcialVinculo->sql_query_matricula(null,
                                                                                         "ed115_sequencial,
                                                                                          ed150_encerrado",
                                                                                          "ed115_sequencial desc limit 1",
                                                                                          $sWhere
                                                                                         );
      $rsProgressaoParcialVinculo   = db_query($sSqlProgressaoParcialVinculo);

      if( !$rsProgressaoParcialVinculo ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_buscar_vinculo_regencia', $oErro ) );
      }

      if (pg_num_rows( $rsProgressaoParcialVinculo ) > 0) {

        $iCodigoVinculo = db_utils::fieldsMemory($rsProgressaoParcialVinculo, 0)->ed115_sequencial;
        $this->oProgressaoParcialVinculoDisciplina = new ProgressaoParcialVinculoDisciplina($iCodigoVinculo);
      }
    }
    return $this->oProgressaoParcialVinculoDisciplina;
  }

  /**
   * Retorna o Vinculo da progressao parcial com uma regencia
   * @param Turma $oTurma
   * @return ProgressaoParcialVinculoDisciplina Dados do Vinculo
   * @throws DBException
   */
  public function getVinculoRegenciaNaTurma(Turma $oTurma) {

    if (empty($this->oProgressaoParcialVinculoDisciplina) && !empty($this->iCodigoProgressaoParcial)) {

      $oDaoProgressaoParcialVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");

      $sWhere  = "ed150_progressaoparcialaluno = {$this->getCodigoProgressaoParcial()} ";
      $sWhere .= " and ed59_i_turma = {$oTurma->getCodigo()}";
      $sSqlProgressaoParcialVinculo = $oDaoProgressaoParcialVinculo->sql_query(null,
                                                                              "ed115_sequencial,
                                                                               ed150_encerrado",
                                                                              "ed115_sequencial desc limit 1",
                                                                              $sWhere
      );

      $rsProgressaoParcialVinculo   = db_query($sSqlProgressaoParcialVinculo);

      if( !$rsProgressaoParcialVinculo ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_buscar_vinculo_regencia_turma', $oErro ) );
      }

      if (pg_num_rows( $rsProgressaoParcialVinculo ) > 0) {

        $iCodigoVinculo = db_utils::fieldsMemory($rsProgressaoParcialVinculo, 0)->ed115_sequencial;
        $this->oProgressaoParcialVinculoDisciplina = new ProgressaoParcialVinculoDisciplina($iCodigoVinculo);
      }
    }

    return $this->oProgressaoParcialVinculoDisciplina;
  }

  /**
   * Persiste os dados da dependencia do aluno
   * @throws BusinessException
   */
  public function salvar() {

    $oMsgErro = new stdClass();
    if (!db_utils::inTransaction()) {
      throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'nao_existe_transacao_ativa' ) );
    }

    $oDaoAlunoProgressao                         = db_utils::getDao("progressaoparcialaluno");
    $oDaoAlunoProgressao->ed114_disciplina       = $this->getDisciplina()->getCodigoDisciplina();
    $oDaoAlunoProgressao->ed114_serie            = $this->getEtapa()->getCodigo();
    $oDaoAlunoProgressao->ed114_aluno            = $this->getAluno()->getCodigoAluno();
    $oDaoAlunoProgressao->ed114_situacaoeducacao = $this->oSituacaoProgressaoParcial->getCodigo();
    $oDaoAlunoProgressao->ed114_tipoconclusao    = $this->iTipoConclusao;
    $oDaoAlunoProgressao->ed114_ano              = $this->iAno;
    $oDaoAlunoProgressao->ed114_escola           = $this->oEscola->getCodigo();

    if (empty($this->iCodigoProgressaoParcial)) {

      $oDaoAlunoProgressao->incluir(null);
      $this->iCodigoProgressaoParcial = $oDaoAlunoProgressao->ed114_sequencial;

      /**
       * persistimos os dados da matricula
       */
    } else {

      $oDaoAlunoProgressao->ed114_sequencial = $this->iCodigoProgressaoParcial;
      $oDaoAlunoProgressao->alterar($oDaoAlunoProgressao->ed114_sequencial);
    }

    $oMsgErro->aluno      = $this->getAluno()->getNome();
    $oMsgErro->disciplina = $this->getDisciplina()->getNomeDisciplina();

    if ($oDaoAlunoProgressao->erro_status == 0) {

      $sErroMsg  = "Erro ao incluir progressão parcial/dependência para o aluno {$this->getAluno()->getNome()} ";
      $sErroMsg .= "na disciplina {$this->getDisciplina()->getNomeDisciplina()}.\n{$oDaoAlunoProgressao->erro_msg}";
      throw new BusinessException($sErroMsg);
    }

    /**
     * verifica se foi informado diário final e se foi gera um vínculo da progressão com o diário final
     */
    if ( !empty($this->iCodigoDiarioFinal) ) {

      $oProgressaoDiarioOrigem = new cl_progressaoparcialalunodiariofinalorigem();
      /**
       * Validamos se o diário final já tem vínculo na progressão
       */
      $sWhereDiarioOrigem      = " ed107_diariofinal = {$this->iCodigoDiarioFinal} ";
      $sSqlDiarioOrigem        = $oProgressaoDiarioOrigem->sql_query_file(null, "1", null, $sWhereDiarioOrigem);
      $rsDiarioOrigem          = db_query($sSqlDiarioOrigem);

      if ( !$rsDiarioOrigem ) {
      	throw new DBException( _M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."erro_ao_verificar_origem") );
      }

      if ( pg_num_rows($rsDiarioOrigem) == 0 ) {

        $oProgressaoDiarioOrigem->ed107_diariofinal            = $this->iCodigoDiarioFinal;
        $oProgressaoDiarioOrigem->ed107_progressaoparcialaluno = $this->iCodigoProgressaoParcial;
        $oProgressaoDiarioOrigem->ed107_sequencial             = null;
        $oProgressaoDiarioOrigem->incluir(null);

        if ($oProgressaoDiarioOrigem->erro_status == 0) {
          throw new BusinessException( _M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."erro_ao_vincular_origem", $oMsgErro) . $oProgressaoDiarioOrigem->erro_msg);
        }
      }
    }

    /**
     * Verificamos se existe vinculo da dependencia com alguma disciplina
     */
    $oDadosVinculo = $this->getVinculoRegencia();
    if ($oDadosVinculo instanceof ProgressaoParcialVinculoDisciplina) {
      $oDadosVinculo->salvar($this);
    }

    $oResultadoFinal = $this->getResultadoFinal();
    if ($oResultadoFinal instanceof ProgressaoParcialAlunoResultadoFinal) {
      $oResultadoFinal->salvar();
    }
  }

  /**
   * Verifica se a progressao já está vinculaddo a alguma regencia
   * @return boolean true se está vinculado false caso nao esteja vinculado
   * @throws DBException
   */
  public function isVinculadoRegencia() {

    if (    !is_null($this->oProgressaoParcialVinculoDisciplina)
         && $this->oProgressaoParcialVinculoDisciplina instanceof ProgressaoParcialVinculoDisciplina) {
      return true;
    }

    if ( !empty($this->iCodigoProgressaoParcial) ) {

      $oDaoProgressaoParcialVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");

      $sWhere  = "ed150_progressaoparcialaluno = {$this->getCodigoProgressaoParcial()} ";
      $sWhere .= " and ed150_encerrado is false";

      $sSqlProgressaoParcialVinculo = $oDaoProgressaoParcialVinculo->sql_query_matricula(null,
                                                                                         "ed115_sequencial,
                                                                                          ed150_encerrado",
                                                                                          "ed115_sequencial desc limit 1",
                                                                                          $sWhere
                                                                                         );

      $rsProgressaoParcialVinculo   = db_query($sSqlProgressaoParcialVinculo);

      if( !$rsProgressaoParcialVinculo ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_validar_vinculo_regencia', $oErro ) );
      }

      if (pg_num_rows( $rsProgressaoParcialVinculo ) > 0) {
        return true;
      }
    }
    return false;
  }

  /**
   * Vincula a Progressao/Dependencia a uma regencia
   * Vincula a progressao parcial a uma regencia de turma, indicando que o aluno está cursando a dependencia
   * na turma de vinculo.
   * @param Regencia $oRegencia Regência onde sera cursado a dependência
   * @param DBDate $dtVinculo Data de vinculo
   * @return ProgressaoParcialVinculoDisciplina retorna o vinculo criado.
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function vincularComRegencia(Regencia $oRegencia, DBDate $dtVinculo) {

    if ($this->isVinculadoRegencia()) {

      $sMensagem  = "A Progressão parcial do Aluno {$this->getAluno()->getNome()}, ";
      $sMensagem .= "na disciplina {$this->getDisciplina()->getNomeDisciplina()} ";
      $sMensagem .= "da Etapa {$this->getEtapa()->getNome()} já está vinculada a uma turma.";
      throw new BusinessException($sMensagem);
    }

    $oDaoProgressaoParcialAlunoMatricula = new cl_progressaoparcialalunomatricula();

    $sWhereProgressaoParcialAlunoMatricula  = "     ed114_aluno      = {$this->getAluno()->getCodigoAluno()}                      ";
    $sWhereProgressaoParcialAlunoMatricula .= " and ed114_disciplina = {$oRegencia->getDisciplina()->getCodigoDisciplina()}       ";
    $sWhereProgressaoParcialAlunoMatricula .= " and ed114_serie      = {$oRegencia->getEtapa()->getCodigo()}                      ";
    $sWhereProgressaoParcialAlunoMatricula .= " and ed150_ano        = {$oRegencia->getTurma()->getCalendario()->getAnoExecucao()}";

    $sSqlProgressaoParcialAlunoMatricula = $oDaoProgressaoParcialAlunoMatricula->sql_query(null,
                                                                                           '*',
                                                                                           null,
                                                                                           $sWhereProgressaoParcialAlunoMatricula);
    $rsProgressaoParcialAlunoMatricula = db_query( $sSqlProgressaoParcialAlunoMatricula );

    if( !$rsProgressaoParcialAlunoMatricula ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_validar_vinculo_matricula', $oErro ) );
    }

    /**
     * Validação implementada, para os casos em que o aluno já cursou a progressão e reprovou, e vai ser vinculado
     * a outra turma de calendário diferente, porém dentro do mesmo ano( calendários de EJA semestral, por exemplo )
     */
    $oVinculoProgressaoReprovada = $this->getVinculoDisciplinaReprovada();
    $lPermiteVincular            = true;

    if( !empty( $oVinculoProgressaoReprovada ) ) {

      $oDisciplinaReprovada = $oVinculoProgressaoReprovada->getRegencia()->getDisciplina();
      $oDisciplinaVincular  = $oRegencia->getDisciplina();

      $oCalendarioReprovada = $oVinculoProgressaoReprovada->getRegencia()->getTurma()->getCalendario();
      $oCalendarioVincular  = $oRegencia->getTurma()->getCalendario();

      $oIntervaloDatas    = DBDate::getIntervaloEntreDatas( $oCalendarioReprovada->getDataFinal(), $oCalendarioVincular->getDataInicio() );
      $lIntervaloNegativo = ( bool ) $oIntervaloDatas->invert;

      if(    $oDisciplinaReprovada->getCodigoDisciplinaGeral() == $oDisciplinaVincular->getCodigoDisciplinaGeral()
          && $lIntervaloNegativo
        ) {
        $lPermiteVincular = false;
      }
    }

    if(    ( empty( $oVinculoProgressaoReprovada )  && pg_num_rows( $rsProgressaoParcialAlunoMatricula ) > 0 )
        || ( !empty( $oVinculoProgressaoReprovada ) && !$lPermiteVincular )
      ) {

      $oMensagem              = new stdClass();
      $oMensagem->sNomeAluno  = $this->getAluno()->getNome();
      $oMensagem->sDisciplina = $this->getDisciplina()->getNomeDisciplina();
      $oMensagem->sEtapa      = $this->getEtapa()->getNome();

      throw new BusinessException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'vinculo_nao_permitido', $oMensagem ) );
    }

    if (empty($oRegencia)) {
      throw new ParameterException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'regencia_nao_informada' ) );
    }

    if (empty($dtVinculo)) {
      throw new ParameterException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'data_vinculo_nao_informada' ) );
    }

    $this->oProgressaoParcialVinculoDisciplina = new ProgressaoParcialVinculoDisciplina();
    $this->oProgressaoParcialVinculoDisciplina->setDataVinculo($dtVinculo);
    $this->oProgressaoParcialVinculoDisciplina->setRegencia($oRegencia);
    $this->oProgressaoParcialVinculoDisciplina->setAno($oRegencia->getTurma()->getCalendario()->getAnoExecucao());

    return $this->oProgressaoParcialVinculoDisciplina;
  }

  /**
   * Remove o vinculo da progressao parcial com a turma
   * Remove o vinculo da progressao parcial com a turma
   */
  public function removerVinculo() {

    if ($this->isVinculadoRegencia()) {

      $this->getVinculoRegencia()->remover();
      $this->oProgressaoParcialVinculoDisciplina = null;
    }
  }

  /**
   * Remove a progressao parcial do aluno
   */
  public function remover() {

    if ($this->getCodigoProgressaoParcial() != "") {

      if ($this->getResultadoFinal() != "") {
        $this->getResultadoFinal()->remover();
      }

      /**
       * Objeto para envio de atributos para mensagem
       */
      $oMensagem = new stdClass();

      /**
       * Exclui primeiramente o registro da tabela progressaoparcialalunodiariofinalorigem pelo código da progressão
       */
      $oDaoAlunoDiarioFinalOrigem = new cl_progressaoparcialalunodiariofinalorigem();
      $oDaoAlunoDiarioFinalOrigem->excluir( null, "ed107_progressaoparcialaluno = {$this->getCodigoProgressaoParcial()}" );
      if ( $oDaoAlunoDiarioFinalOrigem->erro_status == 0 ) {

        $oMensagem->sErroBanco = $oDaoAlunoDiarioFinalOrigem->erro_msg;
        throw new Exception( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO."erro_excluir_diario_final_origem", $oMensagem ) );
      }

      /**
       * Exclui a progressão parcial em si
       */
      $oDaoAlunoProgressao = new cl_progressaoparcialaluno();
      $oDaoAlunoProgressao->excluir( $this->getCodigoProgressaoParcial() );
      if ( $oDaoAlunoProgressao->erro_status == 0 ) {

        $oMensagem->sErroBanco = $oDaoAlunoProgressao->erro_msg;
        throw new Exception( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO."erro_excluir_progressao_parcial", $oMensagem ) );
      }
    }
  }

  /**
   * Define os dados do resultado final da progressao final do aluno
   * @param string  $sNota
   * @param integer $iTotalFaltas
   * @param string $sResultadoFinal
   * @see ProgressaoParcialVinculoDisciplina::setResultadoFinal
   */
  public function setResultadoFinal($sNota, $iTotalFaltas, $sResultadoFinal) {

    $this->getVinculoRegencia()->setResultadoFinal($sNota, $iTotalFaltas, $sResultadoFinal);
    $this->oResultadoFinal = $this->getVinculoRegencia()->getResultadoFinal();
  }

  /**
   * Retorna os dados do resultado final da progressao
   * @return ProgressaoParcialAlunoResultadoFinal
   */
  public function getResultadoFinal() {

    if (empty($this->oResultadoFinal) &&  $this->getVinculoRegencia() != null) {
      $this->oResultadoFinal = $this->getVinculoRegencia()->getResultadoFinal();
    }
    return $this->oResultadoFinal;
  }

  /**
   * Verifica se a progressao do aluno já está encerrada
   * @return boolean
   */
  public function isConcluida() {
    return $this->lConcluida;
  }

  /**
   * Verifica se a progressao do aluno já está ativa
   * @return boolean
   */
  public function isAtiva() {
    return $this->lAtiva;
  }

  /**
   * Encerra os dados da progressao parcial
   * @throws BusinessException
   */
  public function encerrar() {

    if ($this->isConcluida()) {
      throw new BusinessException('Progressão parcial já foi concluída.');
    }

    if ($this->getResultadoFinal()->getResultado() == "") {
      throw new BusinessException('Progressão parcial sem resultado final informado');
    }

    $oResultadoFinal  = $this->getResultadoFinal();
    if ($oResultadoFinal->getResultado() == "A") {

      $this->lConcluida                 = true;
      $this->lAtiva                     = false;
      $this->iTipoConclusao             = 1;
      $this->oSituacaoProgressaoParcial = SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(self::CONCLUIDA);
    }

    $this->getVinculoRegencia()->setEncerrado(true);
    $this->salvar();
  }

  /**
   * Encerra a progressao do Aluno pela aprovacao na disciplina na turma regular
   * @param AvaliacaoResultadoFinal $oResultadoFinal
   * @throws BusinessException
   */
  public function encerrarPorAprovacaoNaDisciplina(AvaliacaoResultadoFinal $oResultadoFinal) {

    if ($oResultadoFinal->getResultadoFinal() != "A") {
      throw new BusinessException("Para o aluno ser Aprovado na Progressao o Resultado deverá ser Aprovado.");
    }

    $this->lConcluida                 = true;
    $this->lAtiva                     = false;
    $this->iTipoConclusao             = 2;
    $this->oSituacaoProgressaoParcial = SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(self::CONCLUIDA);

    $oVinculoRegenciaReprovada = $this->getVinculoDisciplinaReprovada();
    if (!empty($oVinculoRegenciaReprovada)) {

      $oVinculoRegenciaReprovada->setEncerrado(true);
      $oVinculoRegenciaReprovada->salvar($this);
    }
    $this->salvar();

    $oDaoProgressaoDiario = new cl_progressaoparcialalunoencerradodiario;

    $oDaoProgressaoDiario->ed151_diariofinal            = $oResultadoFinal->getCodigoResultadoFinal();
    $oDaoProgressaoDiario->ed151_progressaoparcialaluno = $this->getCodigoProgressaoParcial();
    $oDaoProgressaoDiario->incluir(null);

    if ($oDaoProgressaoDiario->erro_status == 0) {

      $sErroMensagem  = "Erro ao Encerrar Progressão do do aluno {$this->getAluno()->getNome()}";
      $sErroMensagem .= " Na disciplina {$this->getDisciplina()->getNomeDisciplina()}.";
      throw new BusinessException($sErroMensagem);
    }
  }
  /**
   * cancela os encerramento dos dados da progressao parcial
   * @throws BusinessException
   */
  public function reativar() {

    if (!$this->getVinculoRegencia()->isEncerrado()) {
      throw new BusinessException('Progressão parcial não está encerrada.');
    }

    $this->lConcluida = false;
    $this->lAtiva     = true;
    $this->getVinculoRegencia()->setEncerrado(false);
    $this->salvar();
  }

  /**
   * Retorna o vinculo do aluno na turma
   * @param Turma $oTurma
   * @param Etapa $oEtapa
   * @return array
   * @throws DBException
   */
  public function getVinculosNaTurma(Turma $oTurma, Etapa $oEtapa) {

    $aVinculos  = array();
    $aRegencias = array();

    foreach ( $oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia ) {
      $aRegencias[] = $oRegencia->getCodigo();
    }
    $sRegenciasTurma = implode(", ", $aRegencias);

    $oDaoProgressaoParcialVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");
    $sWhere                       = "ed150_progressaoparcialaluno = {$this->getCodigoProgressaoParcial()} ";
    $sWhere                      .= "and ed59_i_turma = {$oTurma->getCodigo()} ";
    $sWhere                      .= " and ed115_regencia in ({$sRegenciasTurma})";
    $sSqlProgressaoParcialVinculo = $oDaoProgressaoParcialVinculo->sql_query(null,
                                                                            "ed115_sequencial",
                                                                             null,
                                                                             $sWhere
                                                                            );

    $rsVinculos   = db_query($sSqlProgressaoParcialVinculo);

    if( !$rsVinculos ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_buscar_vinculos_turma', $oErro ) );
    }

    $iLinhasVinculos = pg_num_rows( $rsVinculos );

    for ($iVinculos = 0; $iVinculos < $iLinhasVinculos; $iVinculos++) {

      $iCodigoVinculo = db_utils::fieldsMemory($rsVinculos, $iVinculos)->ed115_sequencial;
      $aVinculos[]    = new ProgressaoParcialVinculoDisciplina($iCodigoVinculo);
    }
    return $aVinculos;
  }

  /**
   * Retorna o vinculo com a progressao Reprovada
   * @return ProgressaoParcialVinculoDisciplina|null
   * @throws DBException
   */
  protected function getVinculoDisciplinaReprovada() {

    $oDaoProgressaoParcialAluno  = new cl_progressaoparcialaluno;
    $sWhereProgressao            = " ed114_sequencial = {$this->getCodigoProgressaoParcial()} ";
    $sWhereProgressao           .= " and ed121_resultadofinal = 'R'";
    $sWhereProgressao           .= " and ed114_situacaoeducacao  = " . ProgressaoParcialAluno::ATIVA;
    $sWhereProgressao           .= " and ed150_encerrado is true";
    $sSqlProgressao              = $oDaoProgressaoParcialAluno->sql_query_resultado_regencia(null,
                                                                                          "ed115_sequencial,
                                                                                          ed121_sequencial",
                                                                                          null,
                                                                                          $sWhereProgressao
    );

    $rsProgressao = db_query($sSqlProgressao);

    if( !$rsProgressao ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( URL_MENSAGEM_PROGRESSAOPARCIALALUNO . 'erro_buscar_vinculos_disciplina_reprovada', $oErro ) );
    }

    if (pg_num_rows( $rsProgressao ) > 0) {

      $iCodigoVinculo = db_utils::fieldsMemory($rsProgressao, 0)->ed115_sequencial;
      return new ProgressaoParcialVinculoDisciplina($iCodigoVinculo);
    }

    return null;
  }
  /**
   * Retorna o tipo da conclusao da progressao
   * @return number
   */
  public function getTipoConclusao() {
    return $this->iTipoConclusao;
  }

  /**
   * Define o Ano em que a progressão é gerada
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o ano da progressão
   * @return integer
   */
  public function getAno() {
  	return $this->iAno;
  }

  /**
   * Retorna uma instância da escola de vínculo da progressão
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Seta uma instância da escola de vínculo da progressão
   * @param Escola $oEscola
   */
  public function setEscola( Escola $oEscola ) {
    $this->oEscola = $oEscola;
  }

  /**
   * Seta Situação da Progressão Parcial
   * @param SituacaoEducacao $oSituacaoEducacao
   */
  public function setSituacaoProgressao ( SituacaoEducacao $oSituacaoEducacao ) {
    $this->oSituacaoProgressaoParcial = $oSituacaoEducacao;
  }

  /**
   * Retorna uma instancia de SituacaoEducacao da progressão
   * @return SituacaoEducacao
   */
  public function getSituacaoProgressao() {
    return $this->oSituacaoProgressaoParcial;
  }

  /**
   * Retorna se aluno tem matrícula ativa
   * @return boolean
   */
  public function temMatriculaAtiva() {

    $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno($this->oAluno);

    if (!empty($oMatricula)) {
      return true;
    }

    return false;
  }

  /**
   * Retorna se aluno é rematriculado
   * @return boolean
   */
  public function isAlunoRematriculado() {

    $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno($this->oAluno);

    if (!empty($oMatricula) && $oMatricula->getTipo() == 'R') {
      return true;
    }

    return false;
  }

  /**
   * Altera a situação da progressão parcial de um aluno
   * @param bool $lAtiva
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function alterarSituacao($lAtiva = true) {

    if ( !db_utils::inTransaction() ) {
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."nao_existe_transacao_ativa"));
    }

    if ( $this->isConcluida() ) {
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."progressao_nao_pode_alterar_situacao"));
    }

    if ( $lAtiva && $this->isAtiva() ) {
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."progressao_ja_ativa"));
    }

    if ( !$lAtiva && !$this->isAtiva() ) {
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."progressao_ja_inativa"));
    }

    /**
     * Só altera a situacão de uma matricula ativa
     */
    if (!$this->temMatriculaAtiva()) {
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."aluno_sem_matricula_ativa"));
    }

    /**
     * Se a matricula do aluno for uma rematrícula não podemos alterar a progressão parcial
     */
    if ($this->isAlunoRematriculado()) {

      $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno($this->oAluno);
      $oDadosRematricula = new stdClass();
      $oDadosRematricula->sDescricaoTurma = $oMatricula->getTurma()->getDescricao();
      $oDadosRematricula->sDescricaoEtapa = $oMatricula->getEtapaDeOrigem()->getNome();

      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."aluno_rematriculado_nao_altera_situacao", $oDadosRematricula));
    }

    /**
     * A escola só pode remover vinculos de alunos matriculados nela
     * e somente quando form uma matrícula nova: Tipo N
     */
    $oMatricula = MatriculaRepository::getMatriculaAtivaPorAluno( $this->oAluno );
    if ( $oMatricula->getTurma()->getEscola()->getCodigo() != db_getsession("DB_coddepto") ) {

      $oMensagem          = new stdClass();
      $oMensagem->sEscola = $oMatricula->getTurma()->getEscola()->getNome();
      throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."nao_pode_remover_vinculo", $oMensagem));
    }

    /**
     * Somente quando a progressão foi gerada pelo sistema que devemos alterar a situação no histórico escolar do aluno
     * e removemos os vinculos
     */
    if ( !empty( $this->iCodigoDiarioFinal ) ) {

      $this->removerVinculo();

      $aHistoricos = HistoricoAlunoRepository::getHistoricosPorAluno($this->oAluno);

      foreach ($aHistoricos as $oHistorico) {

        $oHistoricoEtapa = $oHistorico->getEtapaDoAno($this->getEtapa(), $this->getAno());

        /**
         * Caso não exista uma etapa lançada para determinado histórico, segue o laço
         */
        if ( $oHistoricoEtapa == null ) {
          continue;
        }

        /**
         * Se estivermos alterando a situação da progressão parcial para ATIVO
         *   devemos: alterar o resultado do histório de R (reprovado) para D (aprovado com dependencia)
         * Se estivermos alterando a situação da progressão parcial para INATIVO
         *   devemos: alterar o resultado do histório de D (aprovado com dependencia) para R (reprovado)
        */
        $sResultadoFinalAlterado = $lAtiva ? 'D' : 'R';
        foreach ($oHistoricoEtapa->getDisciplinas() as $oDisciplina) {

          /**
           * Compara a disciplina lançada até encontrar a disciplina da progressão
           */
          if ($oDisciplina->getDisciplina()->getCodigoDisciplina() == $this->getDisciplina()->getCodigoDisciplina() ) {
            $oDisciplina->setResultadoFinal($sResultadoFinalAlterado);
          }
        }

        $oHistoricoEtapa->setResultadoFinal($sResultadoFinalAlterado);
        $oHistoricoEtapa->setResultadoAno($sResultadoFinalAlterado);
        $oHistoricoEtapa->salvar();

      }
      /**
       * Modificamos  a situacao da disciplina do Diario do Aluno;
       */
      $sResultadoFinalDaDisciplina = $lAtiva ? "A" : "R";

      $oDaoDiarioFinal = new cl_diariofinal();
      $oDaoDiarioFinal->ed74_c_resultadofinal = "{$sResultadoFinalDaDisciplina}";
      $oDaoDiarioFinal->ed74_i_codigo = $this->iCodigoDiarioFinal;
      $oDaoDiarioFinal->alterar($this->iCodigoDiarioFinal);
      if ($oDaoDiarioFinal->erro_status == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_PROGRESSAOPARCIALALUNO."erro_alterar_resultado_final"));
      }

    }

    $this->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::INATIVA));
    if ( $lAtiva ) {
      $this->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::ATIVA));
    }

    $this->salvar();
    return true;
  }

  /**
   * Retorna todos os vínculos já realizados para uma progressão
   * @return ProgressaoParcialVinculoDisciplina[]
   */
  public function getVinculosProgressao() {

    $aProgressaoParcialVinculoDisciplina = array();

    $oDaoVinculosProgressao   = new cl_progressaoparcialalunoturmaregencia();
    $sWhereVinculosProgressao = "ed150_progressaoparcialaluno = {$this->iCodigoProgressaoParcial}";
    $sSqlVinculosProgressao   = $oDaoVinculosProgressao->sql_query_matricula(
                                                                              null,
                                                                              "ed115_sequencial",
                                                                              "ed150_ano",
                                                                              $sWhereVinculosProgressao
                                                                            );

    $rsVinculosProgressao = db_query( $sSqlVinculosProgressao );

    if( $rsVinculosProgressao && pg_num_rows( $rsVinculosProgressao ) > 0 ) {

      for( $iContador = 0; $iContador < pg_num_rows( $rsVinculosProgressao ); $iContador++ ) {

        $iSequencial                           = db_utils::fieldsMemory( $rsVinculosProgressao, $iContador )->ed115_sequencial;
        $oProgressaoParcialVinculoDisciplina   = new ProgressaoParcialVinculoDisciplina( $iSequencial );
        $aProgressaoParcialVinculoDisciplina[] = $oProgressaoParcialVinculoDisciplina;
      }
    }

    return $aProgressaoParcialVinculoDisciplina;
  }

  /**
   * Verifica se a progressão já teve ou tem matrícula (vinculo com uma regência)
   * @return bool
   */
  public function verificaProgressaoTeveMatricula() {

    $sWhere = " ed150_progressaoparcialaluno = {$this->iCodigoProgressaoParcial} ";

    $oDao = new cl_progressaoparcialalunomatricula();
    $sSql = $oDao->sql_query_file( null, 1, null, $sWhere );
    $rs   = db_query($sSql);

    if ($rs && pg_num_rows($rs) > 0) {
      return true;
    }

    return false;
  }

  public function getVinculoPorRegencia(Regencia $oRegencia) {
    return $this->getVinculoRegencia($oRegencia->getCodigo());
  }
}