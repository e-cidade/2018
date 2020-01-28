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

define("URL_MENSAGEM_MATRICULA", "educacao.escola.Matricula.");
/**
 * Matriculas dos alunos
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.28 $
 */
class Matricula {

  /**
   * Numero sequencial.
   * Identifica a matricula do aluno
   * @var integer
   */
  private $iCodigo;

  /**
   * Ordem do aluno na chamada
   * @var integer
   */
  private $iNumeroOrdemAluno;

  /**
   * Situacao da matricula.
   * Pode assumir os valores:
   * AVANÇADO, CANCELADO, EVADIDO, FALECIDO,  MATRICULA INDEVIDA, MATRICULA TRANCADA,
   * MATRICULADO, TRANSFERIDO FORA, TRANSFERIDO REDE, TROCA DE MODALIDADE, TROCA DE TURMA
   * @var string
   */
  private $sSituacao;

  /**
   * Identifica se a matricula esta concluida.
   * 'S' = true
   * 'N' = false
   * @var boolean
   */
  private $lConcluida;

  /**
   * Resultado Final anterior (A-aprovado  R-reprovado)
   * @var string
   */
  private $sResultadoFinalAnterior;

  /**
   * Utilizado nos casos de o aluno ter mais de uma matricula na mesma turma,
   * desativando as mais antigas e ativando a mais nova
   * 'S' = true
   * 'N' = false
   * @var boolean
   */
  private $lAtiva;

  /**
   * Tipo de Matrícula
   * 'N' = Nova Matrícula
   * 'R' = Rematricula
   * @var unknown_type
   */
  private $sTipo;

  /**
   * Data que foi realizada a matricula
   * @var DBDate
   */
  private $oDataMatricula;

  /**
   * Data de Encerramento da matricula
   * @var DBDate
   */
  private $oDataEncerramento;

  /**
   * Instancia de Aluno
   * @var Aluno
   */
  private $oAluno;

  /**
   * Instancia da Turma
   * @var Turma
   */
  private $oTurma;

  /**
   * Codigo da turma
   * @var integer
   */
  private $iCodigoTurma;

  /**
   * Etapa de origem da matricula
   * @var Etapa
   */
  private $oEtapaOrigem;

  private $aSituacaoMatricula = array('AVANÇADO', 'CANCELADO', 'EVADIDO', 'FALECIDO', 'MATRICULA INDEVIDA',
                                      'MATRICULA TRANCADA','MATRICULADO', 'TRANSFERIDO FORA', 'TRANSFERIDO REDE',
                                      'TROCA DE MODALIDADE','TROCA DE TURMA', 'RECLASSIFICADO',"CLASSIFICADO");

  private $oDiarioClasse;

  /**
   * Codigo do aluno
   * @var integer
   */
  private $iCodigoAluno = null;

  /**
   * Matricula do cadastro inicial do aluno
   * @var integer
   */
  private $iMatricula;

  /**
   * Turma anterior do Aluno
   * @var Turma
   */
  private $oTurmaAnterior;

  /**
   * Codigo da turma anterior
   * @var integer
   */
  private $iTurmaAnterior;

  /**
   * Observação da matricula
   * @var string
   */
  private $sObservacao;

  /**
   * Aluno é avaliado por paracer
   * @var boolean
   */
  private $lAvaliacaoParecer = false;

  /**
   * Código do tipo de ingresso
   * @var integer
   */
  private $iTipoIngresso;

  /**
   * Array com os tipos de ingresso
   * @var array
   */
  public static $aTiposIngresso = array();


  private static $aSituacoesMatriculaMov = array("ALTERAÇÃO DE DATA DA MATRÍCULA E/OU OBSERVAÇÕES",
                                                 "ALTERAR SITUAÇÂO DA MATRÍCULA",
                                                 "CANCELAR ENCERRAMENTO DE AVALIAÇÕES",
                                                 "ENCERRAR AVALIAÇÕES",
                                                 "MATRICULAR ALUNO",
                                                 "MATRICULAR ALUNOS TRANSFERIDOS",
                                                 "PROGRESSÃO DE ALUNO -> AVANÇO",
                                                 "PROGRESSÃO DE ALUNO -> CLASSIFICAÇÃO",
                                                 "PROGRESSÃO DE ALUNO -> RECLASSIFICAÇÃO",
                                                 "REMATRICULAR ALUNO",
                                                 "TRANSFERÊNCIA ENTRE ESCOLAS DA REDE",
                                                 "TRANSFERÊNCIA PARA OUTRA ESCOLA",
                                                 "TROCAR ALUNO DE MODALIDADE",
                                                 "TROCAR ALUNO DE TURMA");


  /**
   * cria uma Instancia de Matricula
   * Caso seja informado o codigo da matricula.
   * @param string $iCodigoMatricula Codigo da matricula do aluno
   */
  public function __construct($iCodigoMatricula = null) {


    if (!empty($iCodigoMatricula)) {

      $oDaoAluno = db_utils::getDao('matricula');
      $sSqlAluno = $oDaoAluno->sql_query_file($iCodigoMatricula);
      $rsAluno   = $oDaoAluno->sql_record($sSqlAluno);

      if ($oDaoAluno->numrows > 0) {

        $oAluno = db_utils::fieldsMemory($rsAluno, 0);

        $oAluno->ed60_d_datasaida == "";

        $this->iCodigo                 = $oAluno->ed60_i_codigo;
        $this->iNumeroOrdemAluno       = $oAluno->ed60_i_numaluno;
        $this->sSituacao               = $oAluno->ed60_c_situacao;
        $this->lConcluida              = $oAluno->ed60_c_concluida == 'S'? true : false;
        $this->sResultadoFinalAnterior = $oAluno->ed60_c_rfanterior;
        $this->lAtiva                  = $oAluno->ed60_c_ativa == 'S' ? true : false;
        $this->sTipo                   = $oAluno->ed60_c_tipo;
        $this->iMatricula              = $oAluno->ed60_matricula;
        $this->oDataMatricula          = new DBDate($oAluno->ed60_d_datamatricula);
        $this->iTurmaAnterior          = $oAluno->ed60_i_turmaant;
        $this->sObservacao             = $oAluno->ed60_t_obs;
        $this->lAvaliacaoParecer       = $oAluno->ed60_c_parecer == 'S' ? true : false;
        $this->iTipoIngresso           = $oAluno->ed60_tipoingresso;

        if (!empty($oAluno->ed60_d_datasaida)) {
          $this->oDataEncerramento    = new DBDate($oAluno->ed60_d_datasaida);
        }
        $this->iCodigoAluno = $oAluno->ed60_i_aluno;
        $this->iCodigoTurma = $oAluno->ed60_i_turma;
      }
    }
  }

  /**
   * Retorna o codigo sequencial da Matricula
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * Atribui o numero da ordem do aluno na lista de chamada
   * @param integer $iNumeroOrdemAluno
   */
  public function setNumeroOrdemAluno($iNumeroOrdemAluno) {

    $this->iNumeroOrdemAluno = $iNumeroOrdemAluno;
  }

  /**
   * Retorna o numero da ordem do aluno na lista de chamada
   * @return integer
   */
  public function getNumeroOrdemAluno() {

    return $this->iNumeroOrdemAluno;
  }

  /**
   * Atribui uma situacao na matricula.
   * @param string $sSituacao
   */
  public function setSituacao ($sSituacao) {

    if ($this->validarSituacaoMatricula($sSituacao)) {
      $this->sSituacao = trim($sSituacao);
    }
  }

  /**
   * Retorna uma situacao da matricula.
   * @param string $sSituacao
   */
  public function getSituacao() {

    return $this->sSituacao;
  }

  /**
   * Aluno é avaliado por Parecer
   * @return boolean
   */
  public function isAvaliadoPorParecer() {

    return $this->lAvaliacaoParecer;
  }

  /**
   * Seta se a matricula esta concluida.
   * 'S' = true
   * 'N' = false
   * @param boolean $lConcluida
   * @throws ParameterException quando parâmetro nao for um booleam
   */
  public function setConcluida ($lConcluida) {

    if (!is_bool($lConcluida)) {

      throw new ParameterException('Parâmetro lConcluida deve ser um Boolean.');
    }
    $this->lConcluida = $lConcluida;
  }

  /**
   * Verifica o status de conclusao da matricula
   * 'S' = true
   * 'N' = false
   *  @return boolean
   */
  public function isConcluida() {

    return $this->lConcluida;
  }

  /**
   * Seta um resultado final anterior (A-aprovado  R-reprovado)
   * @param string $sResultadoFinalAnterior
   */
  public function setResultadoFinalAnterior($sResultadoFinalAnterior) {

    $this->sResultadoFinalAnterior = $sResultadoFinalAnterior;
  }

  /**
   * Retorna o resultado final anterior (A-aprovado  R-reprovado)
   * @return string
   */
  public function getResultadoFinalAnterior() {

    return $this->sResultadoFinalAnterior;
  }

  /**
   * Atribui identificacao de qual matricula eh ativa na turma
   * 'S' = true
   * 'N' = false
   * @param boolean $lAtiva
   * @throws ParameterException quando parâmetro nao for um booleam
   */
  public function setAtiva($lAtiva) {

    if (!is_bool($lAtiva)) {
      throw new ParameterException('Parâmetro lAtiva deve ser um Boolean.');
    }
    $this->lAtiva = $lAtiva;
  }

  /**
   * Verifica se a matricula esta ativa
   * 'S' = true
   * 'N' = false
   * @return boolean
   */
  public function isAtiva() {

    return $this->lAtiva;
  }

  /**
   * Atribui um tipo ah matricula
   * 'N' = Nova Matrícula
   * 'R' = Rematricula
   * @param unknown_type $sTipo
   */
  public function setTipo($sTipo) {

    $this->sTipo = $sTipo;
  }

  /**
   * Retorna o tipo da matricula
   * 'N' = Nova Matrícula
   * 'R' = Rematricula
   * @return string
   */
  public function getTipo() {

    return $this->sTipo;
  }

  /**
   * Seta uma instancia de DBDate com a data de matricula
   * @param DBDate $oDataMatricula
   */
  public function setDataMatricula(DBDate $oDataMatricula) {

    $this->oDataMatricula= $oDataMatricula;
  }

  /**
   * Retorna uma instancia de DBDate com a data de matricula
   * @return DBDate
   */
  public function getDataMatricula() {

    return $this->oDataMatricula;
  }

  /**
   * Atribui uma data de encerramento a matricula.
   * Pode ser um valor nulo
   * @param DBDate $oDataEncerramento
   */
  public function setDataEncerramento($oDataEncerramento = null) {

    $this->oDataEncerramento = $oDataEncerramento;
  }

  /**
   * Retorna a data de encerramento a matricula.
   * Pode retornar um valor nulo
   * @return NULL || DBDate
   */
  public function getDataEncerramento() {

    return $this->oDataEncerramento;
  }

  /**
   * Seta uma instancia do Aluno
   * @param Aluno $oAluno
   */
  public function setAluno(Aluno $oAluno) {

    $this->oAluno = $oAluno;
  }

  /**
   * Retorna uma instancia de Aluno
   * @return Aluno
   */
  public function getAluno() {

    if (empty($this->oAluno)) {
      $this->oAluno = AlunoRepository::getAlunoByCodigo($this->iCodigoAluno);
    }
    return $this->oAluno;
  }

  /**
   * Seta uma instancia da Turma
   * @param Turma $oTurma
   */
  public function setTurma(Turma $oTurma) {

    $this->oTurma = $oTurma;
  }

  /**
   * Retorna uma instancia da Turma
   * @return Turma
   */
  public function getTurma() {

    if (empty($this->oTurma)) {
      $this->oTurma = TurmaRepository::getTurmaByCodigo($this->iCodigoTurma);
    }
    return $this->oTurma;
  }

  /**
   * Valida se a situacao da matricula eh valida
   * return boolean
   */
  protected function validarSituacaoMatricula($sSituacao) {

    if (in_array(trim($sSituacao), $this->aSituacaoMatricula)) {
      return true;
    }
    return false;
  }

  /**
   * Retorna o diario de classe do aluno
   * @return DiarioClasse
   */
  public function getDiarioDeClasse() {

    if ($this->oDiarioClasse == "") {
      $this->oDiarioClasse = new DiarioClasse($this);
    }
    return $this->oDiarioClasse;
  }

  /**
   * Retorna as faltas no dia
   * @param DBDate $dtFalta data da falta
   * @return array;
   */
  public function getFaltasNoDia(DBDate $dtFalta) {

    $aFaltasNoDia    = array();
    $oDaoAlunoFalta  = db_utils::getDao("diarioclassealunofalta");
    $sWhere          = "ed301_aluno              = {$this->getAluno()->getCodigoAluno()}";
    $sWhere         .= "and ed300_datalancamento = '".$dtFalta->convertTo(DBDate::DATA_EN)."'";
    $sCampos         = "ed59_i_disciplina, ed58_i_codigo, ed301_sequencial";
    $sSqlFaltas      = $oDaoAlunoFalta->sql_query_falta_regencia(null, $sCampos, "ed59_i_ordenacao", $sWhere);
    $rsFaltas        = $oDaoAlunoFalta->sql_record($sSqlFaltas);
    $aFaltasBase     = db_utils::getCollectionByRecord($rsFaltas);
    foreach ($aFaltasBase as $oFaltaBase) {

      $oFalta = new Falta($oFaltaBase->ed301_sequencial);
      $oFalta->setMatricula($this);
      $oFalta->setDisciplina(DisciplinaRepository::getDisciplinaByCodigo($oFaltaBase->ed59_i_disciplina));
      $oFalta->setPeriodo($oFaltaBase->ed58_i_codigo);
      $aFaltasNoDia[] = $oFalta;
    }
    unset($aFaltasBase);
    unset($oFaltaBase);
    return $aFaltasNoDia;
  }

  /**
   * Retorna a Etapa de Origem da matricula
   * @return Etapa
   */
  public function getEtapaDeOrigem() {

    if ($this->oEtapaOrigem == null) {

      $oDaoMatriculaSerie = db_utils::getDao("matriculaserie");
      $sWhere             = "ed221_i_matricula = {$this->getCodigo()} ";
      $sWhere            .= " and ed221_c_origem  = 'S' ";
      $sSqlSerieOrigem    = $oDaoMatriculaSerie->sql_query_file(null, "ed221_i_serie", null, $sWhere);
      $rsSerieOrigem      = $oDaoMatriculaSerie->sql_record($sSqlSerieOrigem);
      if ($oDaoMatriculaSerie->numrows == 1) {

        $iCodigoEtapa       = db_utils::fieldsMemory($rsSerieOrigem, 0)->ed221_i_serie;
        $this->oEtapaOrigem = EtapaRepository::getEtapaByCodigo($iCodigoEtapa);
      }
    }
    return $this->oEtapaOrigem;
  }

  /**
   * Retorna a matricula do cadastro inicial do aluno
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Retorna a Turma anterior do Aluno
   * @return Turma
   */
  public function getTurmaAnterior() {

    if (empty($this->oTurmaAnterior) && !empty($this->iTurmaAnterior)) {

      $this->oTurmaAnterior = TurmaRepository::getTurmaByCodigo($this->iTurmaAnterior);
    }
    return $this->oTurmaAnterior;
  }

  /**
   * Seta uma instancia de turma
   * @param Turma $oTurma
   */
  public function setTurmaAnterior(Turma $oTurma) {

    $this->oTurmaAnterior = $oTurma;
    $this->iTurmaAnterior = $oTurma->getCodigo();
  }

  /**
   * Remove uma matricula e as informacoes destas das tabelas que possuem referencia a matricula
   * @param Matricula $oMatriculaAnterior
   */
  public function remover(Matricula $oMatriculaAnterior) {

    if (empty($this->iCodigo)) {
      throw new BusinessException('Nenhuma matrícula informada para exclusão.');
    }

    $oDaoMatriculaSerie       = db_utils::getDao("matriculaserie");
    $oDaoMatriculaMovAnterior = db_utils::getDao("matriculamov");
    $oDaoMatriculaMovAtual    = db_utils::getDao("matriculamov");
    $oDaoAlunoTransfTurma     = db_utils::getDao("alunotransfturma");
    $oDaoMatricula            = db_utils::getDao("matricula");

    /**
     * Excluimos da tabela matriculaserie
     */
    $sWhereMatriculaSerie  = "ed221_i_matricula = {$this->iCodigo}";
    $sSqlMatriculaSerie    = $oDaoMatriculaSerie->sql_query_file(null, "ed221_i_codigo", null, $sWhereMatriculaSerie);
    $rsMatriculaSerie      = $oDaoMatriculaSerie->sql_record($sSqlMatriculaSerie);
    $iLinhasMatriculaSerie = $oDaoMatriculaSerie->numrows;

    if ($iLinhasMatriculaSerie > 0) {

      for ($iContador = 0; $iContador < $iLinhasMatriculaSerie; $iContador++) {

        $iMatriculaSerie = db_utils::fieldsMemory($rsMatriculaSerie, $iContador)->ed221_i_codigo;
        $oDaoMatriculaSerie->excluir($iMatriculaSerie);

        if ($oDaoMatriculaSerie->erro_status == "0") {
          throw new DBException($oDaoMatriculaSerie->erro_msg);
        }
      }

    }

    /**
     * Excluimos da tabela matriculamov as informacoes de troca de turma referente a matricula anterior
     */
    $sWhereMatriculaMovAnterior  = "ed229_i_matricula = {$oMatriculaAnterior->getCodigo()}";
    $sWhereMatriculaMovAnterior .= " AND ed229_c_procedimento = trim('TROCAR ALUNO DE TURMA')";
    $sSqlMatriculaMovAnterior    = $oDaoMatriculaMovAnterior->sql_query_file(null,
                                                                             "ed229_i_codigo",
                                                                             null,
                                                                             $sWhereMatriculaMovAnterior);
    $rsMatriculaMovAnterior      = $oDaoMatriculaMovAnterior->sql_record($sSqlMatriculaMovAnterior);
    $iLinhasMatriculaMovAnterior = $oDaoMatriculaMovAnterior->numrows;

    if ($iLinhasMatriculaMovAnterior > 0) {

      for ($iContadorMovAnterior = 0; $iContadorMovAnterior < $iLinhasMatriculaMovAnterior; $iContadorMovAnterior++) {

        $iMatriculaMovAnterior = db_utils::fieldsMemory($rsMatriculaMovAnterior, $iContadorMovAnterior)->ed229_i_codigo;
        $oDaoMatriculaMovAnterior->excluir($iMatriculaMovAnterior);

        if ($oDaoMatriculaMovAnterior->erro_status == "0") {
          throw new DBException($oDaoMatriculaMovAnterior->erro_msg);
        }
      }
    }

    /**
     * Excluimos da tabela matriculamov as informacoes da matricula atual
     */
    $sWhereMatriculaMovAtual  = "ed229_i_matricula = {$this->getCodigo()}";
    $sSqlMatriculaMovAtual    = $oDaoMatriculaMovAtual->sql_query_file(null,
                                                                       "ed229_i_codigo",
                                                                       null,
                                                                       $sWhereMatriculaMovAtual);
    $rsMatriculaMovAtual      = $oDaoMatriculaMovAtual->sql_record($sSqlMatriculaMovAtual);
    $iLinhasMatriculaMovAtual = $oDaoMatriculaMovAtual->numrows;

    if ($iLinhasMatriculaMovAtual > 0) {

      for ($iContadorMovAtual = 0; $iContadorMovAtual < $iLinhasMatriculaMovAtual; $iContadorMovAtual++) {

        $iMatriculaMovAtual = db_utils::fieldsMemory($rsMatriculaMovAtual, $iContadorMovAtual)->ed229_i_codigo;
        $oDaoMatriculaMovAtual->excluir($iMatriculaMovAtual);

        if ($oDaoMatriculaMovAtual->erro_status == "0") {
          throw new DBException($oDaoMatriculaMovAtual->erro_msg);
        }
      }
    }

    /**
     * Excluimos da tabela alunotransfturma o registro referente a troca de turma da matricula anterior
     */
    $sWhereAlunoTransfTurma  = "ed69_i_matricula = {$oMatriculaAnterior->getCodigo()}";
    $sSqlAlunoTransfTurma    = $oDaoAlunoTransfTurma->sql_query_file(null, "ed69_i_codigo", null, $sWhereAlunoTransfTurma);
    $rsAlunoTransfTurma      = $oDaoAlunoTransfTurma->sql_record($sSqlAlunoTransfTurma);
    $iLinhasAlunoTransfTurma = $oDaoAlunoTransfTurma->numrows;

    if ($iLinhasAlunoTransfTurma > 0) {

      for ($iContadorTransfTurma = 0; $iContadorTransfTurma < $iLinhasAlunoTransfTurma; $iContadorTransfTurma++) {

        $iMatriculaTransfTurma = db_utils::fieldsMemory($rsAlunoTransfTurma, $iContadorTransfTurma)->ed69_i_codigo;
        $oDaoAlunoTransfTurma->excluir($iMatriculaTransfTurma);

        if ($oDaoAlunoTransfTurma->erro_status == "0") {
          throw new DBException($oDaoAlunoTransfTurma->erro_msg);
        }
      }
    }

    /**
     * Excluimos da tabela matricula
     */
    $oDaoMatricula->excluir($this->iCodigo);
    if ($oDaoMatricula->erro_status == "0") {
      throw new DBException($oDaoMatricula->erro_msg);
    }
  }

  /**
   * Salva os dados da matricula
   */
  public function salvar() {

    $oDaoMatricula                    = db_utils::getDao("matricula");
    $oDaoMatricula->ed60_c_situacao   = $this->sSituacao;
    $oDaoMatricula->ed60_c_ativa      = $this->lAtiva == true ? 'S' : 'N';
    $oDaoMatricula->ed60_c_concluida  = $this->lConcluida ? 'S' : 'N';
    $oDaoMatricula->ed60_i_turma      = $this->getTurma()->getCodigo();
    $oDaoMatricula->ed60_i_turmaant   = $this->iTurmaAnterior;
    $oDaoMatricula->ed60_i_numaluno   = "{$this->getNumeroOrdemAluno()}";
    $oDaoMatricula->ed60_tipoingresso = $this->getTipoIngresso();
    if ($this->getNumeroOrdemAluno() == '') {
      $oDaoMatricula->ed60_i_numaluno = 'null';
    }
    $oDaoMatricula->ed60_d_datasaida = null;

    if (!isset($this->iCodigo)) {

      $oDaoMatricula->incluir(null);
      $this->iCodigo = $oDaoMatricula->ed60_i_codigo;
    } else {

      $oDaoMatricula->ed60_i_codigo = $this->iCodigo;
      $oDaoMatricula->alterar($oDaoMatricula->ed60_i_codigo);
    }

    if ($oDaoMatricula->erro_status == "0") {
      throw new DBException($oDaoMatricula->erro_msg);
    }
  }

  /**
   * Retorna a Observação da matricula
   * @return string
   */
  public function getObservacao() {

    return $this->sObservacao;
  }

  /**
   * Define o tipo de ingresso
   * @param integer $iTipoIngresso
   */
  public function setTipoIngresso($iTipoIngresso) {
    $this->iTipoIngresso = $iTipoIngresso;
  }

  /**
   * Retorna o tipo de ingresso
   * @return integer
   */
  public function getTipoIngresso() {
    return $this->iTipoIngresso;
  }

  /**
   * Busca os tipos de ingresso do aluno.
   * O índice do array é o código e valor é a descrição do tipo.
   * @param integer $iTipoIngresso
   * @return array
   */
  static function getTiposIngresso($iTipoIngresso) {

    if (!array_key_exists($iTipoIngresso, self::$aTiposIngresso)) {

      $oDaoTipoIngresso = db_utils::getDao('tipoingresso');
      $sSqlTipoIngresso = $oDaoTipoIngresso->sql_query(null, '*', 'ed334_sequencial', '');
      $rsTipoIngresso   = $oDaoTipoIngresso->sql_record($sSqlTipoIngresso);

      if ($oDaoTipoIngresso->numrows > 0) {

        for ($iContador = 0; $iContador < $oDaoTipoIngresso->numrows; $iContador++) {

          $oTipoIngresso                                          = db_utils::fieldsMemory($rsTipoIngresso, $iContador);
          self::$aTiposIngresso[$oTipoIngresso->ed334_sequencial] = $oTipoIngresso->ed334_tipo;
        }
      }
    }
    return self::$aTiposIngresso[$iTipoIngresso];
  }

  /**
   * Encerra a matrícula
   * @var string $sSituacao
   * @throws ParameterException
   */
  public function encerrar($sSituacao) {

    if (!in_array($sSituacao, $this->aSituacaoMatricula)) {
    	throw new ParameterException(URL_MENSAGEM_MATRICULA."situacao_matricula_invalida");
    }
    $oDiario = $this->getDiarioDeClasse();
    $oDiario->encerrar();

    $this->setSituacao($sSituacao);
    $this->setAtiva(false);
    $this->setConcluida(true);
    $this->salvar();
    
    /**
     * Atualiza alunos matriculados da turma
     */
    $this->oTurma->salvar();
  }

  /**
   * Atualiza movimentação do aluno.
   * @param string $sTipoMovimentacao
   * @param string $sTipoProcedimento
   * @param DBDate $oData
   * @throws ParameterException
   * @throws DBException
   */
  public function atualizarMovimentacao($sTipoMovimentacao, $sTipoProcedimento, DBDate $oData) {

    if (!in_array($sTipoProcedimento, Matricula::$aSituacoesMatriculaMov)) {
      throw new ParameterException(URL_MENSAGEM_MATRICULA."situacao_matriculamov_invalida");
    }

    $oDaoMatriculaMov                       = db_utils::getDao("matriculamov");
    $oDaoMatriculaMov->ed229_i_matricula    = $this->getCodigo();
    $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $oDaoMatriculaMov->ed229_c_procedimento = $sTipoProcedimento;
    $oDaoMatriculaMov->ed229_t_descr        = $sTipoMovimentacao;
    $oDaoMatriculaMov->ed229_d_dataevento   = $oData->getDate();
    $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
    $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoMatriculaMov->incluir(null);

    if ($oDaoMatriculaMov->erro_status == "0") {
      throw new DBException($oDaoMatriculaMov->erro_msg);
    }
  }

  /**
   * Efetua a matrícula de um aluno
   * @param Etapa $oEtapa
   * @throws BusinessException
   */
  public function matricular(Etapa $oEtapa) {

    if ($this->oTurma->getVagasDisponiveis () <= 0) {
    	throw new BusinessException(_M(URL_MENSAGEM_MATRICULA."turma_sem_vagas"));
    }

    $oDaoMatricula = new cl_matricula();

    /**
     * Valida se existe matricula para o calendário da turma de destino
     */
    $sCamposSqlMatricula  = " ed60_i_codigo as matricula_atual, ed47_v_nome as aluno, turma.ed57_c_descr as turma, ";
    $sCamposSqlMatricula .= " calendario.ed52_c_descr as calendario, ed60_c_situacao ";
    $aWhereSqlMatricula   = array();
    $aWhereSqlMatricula[] = "ed60_i_aluno            = {$this->oAluno->getCodigoAluno()}";
    $aWhereSqlMatricula[] = "turma.ed57_i_calendario = {$this->oTurma->getCalendario()->getCodigo()}";
    $aWhereSqlMatricula[] = "ed60_c_situacao != 'AVANÇADO'";
    $aWhereSqlMatricula[] = "ed60_c_situacao != 'CLASSIFICADO'";
    $aWhereSqlMatricula[] = "ed60_c_situacao != 'RECLASSIFICADO'";

    $sWhereSqlMatricula = implode(" and ", $aWhereSqlMatricula);

    $sSqlMatricula = $oDaoMatricula->sql_query("", $sCamposSqlMatricula,"", $sWhereSqlMatricula);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    // Carrega os parâmetros
    $oEduParametros = loadConfig('edu_parametros', " ed233_i_escola = {$this->oTurma->getEscola()->getCodigo()}");

    $lConsiteMatricula          = false;
    $lReclassificaEtapaAnterior = false;
    if (!empty($oEduParametros)) {

      $lConsiteMatricula          = $oEduParametros->ed233_c_consistirmat == 'S';
      $lReclassificaEtapaAnterior = $oEduParametros->ed233_reclassificaetapaanterior == 't';
    }

    $oMsgErro = new stdClass();

    if ($oDaoMatricula->numrows > 0 && $this->sSituacao != "RECLASSIFICADO") {//@TODO Adicionar Classificação

      $oDadosMatricula      = db_utils::fieldsMemory($rsMatricula, 0);
      $oMsgErro->aluno      = trim($oDadosMatricula->aluno);
      $oMsgErro->turma      = trim($oDadosMatricula->turma);
      $oMsgErro->calendario = trim($oDadosMatricula->matricula_atual);
      $oMsgErro->situacao   = $oDadosMatricula->ed60_c_situacao;
      $oMsgErro->menu       = "Procedimentos -> Matrículas -> Alterar Situação da Matrícula";

      if ($oDadosMatricula->ed60_c_situacao == "TRANSFERIDO FORA") {
        $oMsgErro->menu = "Procedimentos -> Transferências -> Matricular Alunos Transferidos (FORA)";
      }
      throw new BusinessException(_M(URL_MENSAGEM_MATRICULA."erro_matricula_existente", $oMsgErro));
    }

    $aCodigoEtapasTurma = array();
    foreach ($this->oTurma->getEtapas() as $oEtapaTurma) {
      $aCodigoEtapasTurma[] = $oEtapaTurma->getEtapa()->getCodigo();
    }

    /**
     * @todo verificar PARÂMETRO
     */
    HistoricoEtapa::verificaUltimoRegistroHistorico($this->oAluno, $oEtapa, $aCodigoEtapasTurma);

    $oSituacaoAluno = $this->oAluno->getSituacao();
    
    /**
     * @todo Setar estas propriedades na classe também
     */
    $oDaoMatricula->ed60_i_numaluno      = $this->oTurma->getUltimoNumeroClassificado() + 1;
    $oDaoMatricula->ed60_i_aluno         = $this->oAluno->getCodigoAluno();
    $oDaoMatricula->ed60_c_situacao      = "MATRICULADO";
    $oDaoMatricula->ed60_c_concluida     = "N";
    $oDaoMatricula->ed60_t_obs           = "";
    $oDaoMatricula->ed60_i_turma         = $this->oTurma->getCodigo();
    
    /**
     * Ao realizar uma nova matrícula, setar a turma anterior como vazia para não quebrar ao salvar
     */
    $oDaoMatricula->ed60_i_turmaant      = $this->getTurmaAnterior()->getCodigo();
    $oDaoMatricula->ed60_c_rfanterior    = $this->getResultadoFinalAnterior();
    $oDaoMatricula->ed60_d_datamatricula = $this->oDataMatricula->getDate();
    $oDaoMatricula->ed60_d_datamodif     = $this->oDataMatricula->getDate();
    $oDaoMatricula->ed60_d_datamodifant  = null;
    $oDaoMatricula->ed60_d_datasaida     = null;
    $oDaoMatricula->ed60_c_ativa         = "S";
    $oDaoMatricula->ed60_c_tipo          = $oSituacaoAluno->getSituacaoAnterior() == "CANDIDATO" ? "N" : "R";
    $oDaoMatricula->ed60_c_parecer       = "N";
    $oDaoMatricula->ed60_matricula       = null;
    $oDaoMatricula->ed60_i_codigo        = null;
    $oDaoMatricula->incluir(null);

    if ($oDaoMatricula->erro_status == 0) {
    	throw new BusinessException(_M(URL_MENSAGEM_MATRICULA."erro_incluir_matricula") . $oDaoMatricula->erro_msg);
    }
    
    $this->iCodigo = $oDaoMatricula->ed60_i_codigo;

    $sSituacaoAnterior      = $oSituacaoAluno->getSituacaoAnterior() == "CANDIDATO" ? "MATRICULAR"  : "REMATRICULAR";
    $sSituacaoAnteriorMov   = $oSituacaoAluno->getSituacaoAnterior() == "CANDIDATO" ? "MATRICULADO" : "REMATRICULADO";
    $sSituacaoProcedimento  = "{$sSituacaoAnterior} ALUNO";
    $sMensagemMovimento     = "ALUNO $sSituacaoAnteriorMov NA TURMA {$this->oTurma->getDescricao()}. SITUAÇÂO ANTERIOR: {$sSituacaoAnterior}";

    $this->atualizarMovimentacao($sMensagemMovimento, $sSituacaoProcedimento, $this->oDataMatricula);
    
    
    if ($this->oTurma->getVagasDisponiveis() == 0) {
    	throw new BusinessException( _M(URL_MENSAGEM_MATRICULA."erro_turma_sem_vagas") );
    }
    /**
     * Atualiza número de vagas da turma
     */
    $this->oTurma->salvar();
    
    $oDaoMatriculaSerie = new cl_matriculaserie();
    $oDaoMatriculaSerie->ed221_i_matricula = $this->iCodigo;
    $oDaoMatriculaSerie->ed221_i_serie     = $oEtapa->getCodigo();
    $oDaoMatriculaSerie->ed221_c_origem    = "S";
    $oDaoMatriculaSerie->incluir(null);

    if ($oDaoMatriculaSerie->erro_status == 0) {
      throw new BusinessException( _M(URL_MENSAGEM_MATRICULA."erro_incluir_matriculaserie") );
    }

    $oSituacaoAluno->setSituacaoAlunoCurso("MATRICULADO");
    $oSituacaoAluno->setCalendario($this->oTurma->getCalendario());
    $oSituacaoAluno->setBase($this->oTurma->getBaseCurricular());
    $oSituacaoAluno->setEscola($this->oTurma->getEscola());
    $oSituacaoAluno->setEtapa($oEtapa);
    $oSituacaoAluno->setTurno($this->oTurma->getTurno());
    $oSituacaoAluno->salvar();

    $oHistorico = HistoricoAlunoRepository::getHistoricoAlunoByCurso($this->oAluno, $this->oTurma->getBaseCurricular()->getCurso());
    if (empty($oHistorico)) {
      $oHistorico = new HistoricoAluno();
    }
    $oHistorico->setEscola($this->oTurma->getEscola());
    $oHistorico->setCurso($this->oTurma->getBaseCurricular()->getCurso()->getCodigo());
    $oHistorico->setAluno($this->oAluno);
    $oHistorico->salvar();

  }
}?>