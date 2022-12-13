<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

class DadosAluno2016 {

  /**
   * Código INEP da Escola
   * @var integer
   */
  protected $iEscolaInep;

  /**
   * Código do vínculo da Turma com a Escola
   * @var integer
   */
  protected $iTurmaEscola;

  /**
   * Código INEP da Turma
   * @var integer
   */
  protected $iTurmaInep;

  /**
   * Código INEP do Aluno
   * @var integer
   */
  protected $iAlunoInep;

  /**
   * Código do vínculo do Aluno com a Escola
   * @var integer
   */
  protected $iAlunoEscola;

  /**
   * Código INEP da Matrícula
   * @var integer
   */
  protected $iMatriculaInep;

  /**
   * Código da Situação do Aluno
   *   1 - Transferido
   *   2 - Deixou de frequentar
   *   3 - Falecido
   *   4 - Reprovado
   *   5 - Aprovado
   *   6 - Aprovado concluinte
   *   7 - Em andamento/Sem movimentação
   *
   * @var integer
   */
  protected $iSituacaoAluno;

  /**
   * Matrícula do aluno
   * @var Matricula
   */
  protected $oMatricula;

  /**
   * Informações do aluno adicionadas as mensagens de erro.
   * @var string
   */
  protected $sInformacaoAluno;

  /**
   * De/Para das situações existentes no e-cidade com as do CENSO
   * @var array
   */
  protected $aSituacoesMatricula = array(
                                        'TRANSFERIDO REDE'                              => 1,
                                        'TRANSFERIDO FORA'                              => 1,
                                        'EVADIDO'                                       => 2,
                                        'MATRICULA TRANCADA'                            => 2,
                                        'CANCELADO'                                     => 2,
                                        'FALECIDO'                                      => 3,
                                        'REPROVADO'                                     => 4,
                                        'APROVADO'                                      => 5,
                                        'CLASSIFICADO'                                  => 5,
                                        'AVANÇADO'                                      => 5,
                                        'RECLASSIFICADO'                                => 5,
                                        'APROVADO COM PROGRESSAO PARCIAL / DEPENDÊNCIA' => 5,
                                        'EM ANDAMENTO'                                  => 7,
                                        'EM RECUPERAÇÃO'                                => 7,
                                        );

  /**
   * Erros ocorrido durante a execução
   * @var array
   */
  protected $aErros = array();

  /**
   * Seta os atributos da classe de acordo com os dados recebidos.
   * @param stdClass $oDados
   */
  public function popular($oDados) {

    $this->iEscolaInep    = $oDados->codigo_escola_inep;
    $this->iTurmaEscola   = $oDados->codigo_turma_escola;
    $this->iTurmaInep     = $oDados->codigo_turma_inep;
    $this->iAlunoInep     = $oDados->codigo_aluno_inep;
    $this->iAlunoEscola   = $oDados->codigo_aluno_escola;
    $this->iMatriculaInep = $oDados->codigo_matricula_inep;
    $this->iSituacaoAluno = null;

    $this->oMatricula = new \Matricula( $oDados->codigo_matricula );

    if ( array_key_exists($this->oMatricula->retornaAndamentoDaMatricula(), $this->aSituacoesMatricula) ) {
      $this->iSituacaoAluno = $this->aSituacoesMatricula[$this->oMatricula->retornaAndamentoDaMatricula()];
    }

    $oEtapaOrigem       = $this->oMatricula->getEtapaDeOrigem();
    $aEtapasConcluintes = array(11, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 65, 67, 68, 70, 71, 73, 74);

    if ( $this->iSituacaoAluno == 5 && in_array($oEtapaOrigem->getEtapaCenso(), $aEtapasConcluintes) ) {
      $this->iSituacaoAluno = 6;
    }

    // Campo Situação do Aluno - Regra 4
    if ( in_array($this->iSituacaoAluno, array(4,5,6)) &&  in_array($oEtapaOrigem->getEtapaCenso(), array(1,2,3)) ) {
      $this->iSituacaoAluno = 7;
    }

    $this->sInformacaoAluno  = "Aluno: {$this->oMatricula->getAluno()->getCodigoAluno()} - ";
    $this->sInformacaoAluno .= "{$this->oMatricula->getAluno()->getNome()}";
    $this->sInformacaoAluno .= " matrículado na turma {$this->oMatricula->getTurma()->getDescricao()} \n";
  }

  /**
   * Realiza as validações do campo Código da escola -INEP
   * @param integer $iCodigoEscolaINEP código inep do registro 89 para comparação
   */
  protected function validarINEPEscola( $iCodigoEscolaINEP ) {

    //Regra 1
    if ( empty($this->iEscolaInep) ) {
      $this->addErro('O campo "Código de escola - INEP" é uma informação obrigatória.');
    }

    //Regra 2
    if ( $this->iEscolaInep != $iCodigoEscolaINEP ) {
      $this->addErro('O campo "Código de escola - INEP" está diferente do registro 89 antecendente.');
    }
  }

  /**
   * Realiza as validações do campo Código da turma - INEP
   */
  protected function validarINEPTurma() {

    //Regra 1
    if ( empty($this->iTurmaInep) ) {
      $this->addErro('O campo "Código da turma - INEP" é uma informação obrigatória.');
    }

    //Regra 2
    if ( strlen($this->iTurmaInep) > 10 ) {
      $this->addErro('O campo "Código da turma - INEP" está maior que o especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iTurmaInep) ) {
      $this->addErro('O campo "Código da turma - INEP" foi preenchido com valor inválido.');
    }
  }

  /**
   * Realiza as validações do campo Código de identificação única do aluno - INEP
   */
  protected function validarINEPAluno() {

    //Regra 1
    if ( empty($this->iAlunoInep) ) {
      $this->addErro('O campo "Código de identificação única do aluno - INEP" é uma informação obrigatória.');
    }

    //Regra 2
    if ( strlen($this->iAlunoInep) != 12 ) {
      $this->addErro('O campo "Código de identificação única do aluno - INEP" está com o tamanho diferente do especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iAlunoInep) ) {
      $this->addErro('O campo "Código de identificação única do aluno - INEP" foi preenchido com valor inválido.');
    }
  }

  /**
   * Realiza as validações do campo Matrícula (INEP)
   */
  protected function validarINEPMatricula() {

    //Regra 1
    if ( empty($this->iMatriculaInep) ) {
      $this->addErro('O campo "Código da matrícula" é uma informação obrigatória.');
    }

    //Regra 2
    if ( strlen($this->iMatriculaInep) > 12 ) {
      $this->addErro('O campo "Código da matríula" está maior do que o especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iMatriculaInep) ) {
      $this->addErro('O campo "Código da matrícula" foi preenchido com valor inválido.');
    }
  }

  /**
   * Realiza as validações do campo Situação do Aluno
   */
  protected function validarSituacao() {

    //Regra 1
    if ( empty($this->iSituacaoAluno) ) {
      $this->addErro('O campo "Situação do aluno" é uma informação obrigatória.');
    }

    //Regra 2
    if ( !in_array($this->iSituacaoAluno, array(1,2,3,4,5,6,7)) ) {
      $this->addErro('O campo "Situação do aluno" foi preenchido com valor inválido');
    }

    //Regra 5
    $iEtapaCenso = $this->oMatricula->getEtapaDeOrigem()->getEtapaCenso();
    $aEtapas     = array(11, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 65, 67, 68, 70, 71, 73, 74);

    if ( $this->iSituacaoAluno == 6 && !in_array($iEtapaCenso, $aEtapas) ) {

      $sErro  = 'O campo "Situação do aluno" não pode ser preenchido com 6 (Aprovado concluinte) quando a etapa da';
      $sErro .= ' matrícula for diferente de 11, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 65, 67, 68, 70, 71, 73 e 74.';
      $this->addErro($sErro);
    }

    //Regra 6
    $aEtapas = array(1, 2, 39, 40, 65, 67, 68, 69, 70, 71, 73, 74);
    if ( $this->iSituacaoAluno == 7 && !in_array($iEtapaCenso, $aEtapas) ) {

      $sErro  = 'O campo "Situação do aluno" não pode ser preenchido com 7 (Em andamento) quando a etapa da';
      $sErro .= ' matrícula for diferente de 1, 2, 39, 40, 65, 67, 68, 69, 70, 71, 73 e 74.';
      $this->addErro($sErro);
    }
  }

  /**
   * Retorna os erros
   * @return array
   */
  public function getErros(){
    return $this->aErros;
  }

  /**
   * Adiciona os erros/inconsistências encontradas no array de erros
   * @param string $sMsgErro
   */
  protected function addErro($sMsgErro) {
    $this->aErros[] = $this->sInformacaoAluno . $sMsgErro;
  }

}