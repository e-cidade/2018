<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

class DadosAluno2016 {

  /**
   * C�digo INEP da Escola
   * @var integer
   */
  protected $iEscolaInep;

  /**
   * C�digo do v�nculo da Turma com a Escola
   * @var integer
   */
  protected $iTurmaEscola;

  /**
   * C�digo INEP da Turma
   * @var integer
   */
  protected $iTurmaInep;

  /**
   * C�digo INEP do Aluno
   * @var integer
   */
  protected $iAlunoInep;

  /**
   * C�digo do v�nculo do Aluno com a Escola
   * @var integer
   */
  protected $iAlunoEscola;

  /**
   * C�digo INEP da Matr�cula
   * @var integer
   */
  protected $iMatriculaInep;

  /**
   * C�digo da Situa��o do Aluno
   *   1 - Transferido
   *   2 - Deixou de frequentar
   *   3 - Falecido
   *   4 - Reprovado
   *   5 - Aprovado
   *   6 - Aprovado concluinte
   *   7 - Em andamento/Sem movimenta��o
   *
   * @var integer
   */
  protected $iSituacaoAluno;

  /**
   * Matr�cula do aluno
   * @var Matricula
   */
  protected $oMatricula;

  /**
   * Informa��es do aluno adicionadas as mensagens de erro.
   * @var string
   */
  protected $sInformacaoAluno;

  /**
   * De/Para das situa��es existentes no e-cidade com as do CENSO
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
                                        'AVAN�ADO'                                      => 5,
                                        'RECLASSIFICADO'                                => 5,
                                        'APROVADO COM PROGRESSAO PARCIAL / DEPEND�NCIA' => 5,
                                        'EM ANDAMENTO'                                  => 7,
                                        'EM RECUPERA��O'                                => 7,
                                        );

  /**
   * Erros ocorrido durante a execu��o
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

    // Campo Situa��o do Aluno - Regra 4
    if ( in_array($this->iSituacaoAluno, array(4,5,6)) &&  in_array($oEtapaOrigem->getEtapaCenso(), array(1,2,3)) ) {
      $this->iSituacaoAluno = 7;
    }

    $this->sInformacaoAluno  = "Aluno: {$this->oMatricula->getAluno()->getCodigoAluno()} - ";
    $this->sInformacaoAluno .= "{$this->oMatricula->getAluno()->getNome()}";
    $this->sInformacaoAluno .= " matr�culado na turma {$this->oMatricula->getTurma()->getDescricao()} \n";
  }

  /**
   * Realiza as valida��es do campo C�digo da escola -INEP
   * @param integer $iCodigoEscolaINEP c�digo inep do registro 89 para compara��o
   */
  protected function validarINEPEscola( $iCodigoEscolaINEP ) {

    //Regra 1
    if ( empty($this->iEscolaInep) ) {
      $this->addErro('O campo "C�digo de escola - INEP" � uma informa��o obrigat�ria.');
    }

    //Regra 2
    if ( $this->iEscolaInep != $iCodigoEscolaINEP ) {
      $this->addErro('O campo "C�digo de escola - INEP" est� diferente do registro 89 antecendente.');
    }
  }

  /**
   * Realiza as valida��es do campo C�digo da turma - INEP
   */
  protected function validarINEPTurma() {

    //Regra 1
    if ( empty($this->iTurmaInep) ) {
      $this->addErro('O campo "C�digo da turma - INEP" � uma informa��o obrigat�ria.');
    }

    //Regra 2
    if ( strlen($this->iTurmaInep) > 10 ) {
      $this->addErro('O campo "C�digo da turma - INEP" est� maior que o especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iTurmaInep) ) {
      $this->addErro('O campo "C�digo da turma - INEP" foi preenchido com valor inv�lido.');
    }
  }

  /**
   * Realiza as valida��es do campo C�digo de identifica��o �nica do aluno - INEP
   */
  protected function validarINEPAluno() {

    //Regra 1
    if ( empty($this->iAlunoInep) ) {
      $this->addErro('O campo "C�digo de identifica��o �nica do aluno - INEP" � uma informa��o obrigat�ria.');
    }

    //Regra 2
    if ( strlen($this->iAlunoInep) != 12 ) {
      $this->addErro('O campo "C�digo de identifica��o �nica do aluno - INEP" est� com o tamanho diferente do especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iAlunoInep) ) {
      $this->addErro('O campo "C�digo de identifica��o �nica do aluno - INEP" foi preenchido com valor inv�lido.');
    }
  }

  /**
   * Realiza as valida��es do campo Matr�cula (INEP)
   */
  protected function validarINEPMatricula() {

    //Regra 1
    if ( empty($this->iMatriculaInep) ) {
      $this->addErro('O campo "C�digo da matr�cula" � uma informa��o obrigat�ria.');
    }

    //Regra 2
    if ( strlen($this->iMatriculaInep) > 12 ) {
      $this->addErro('O campo "C�digo da matr�ula" est� maior do que o especificado.');
    }

    //Regra 3
    if ( !\DBString::isSomenteNumero($this->iMatriculaInep) ) {
      $this->addErro('O campo "C�digo da matr�cula" foi preenchido com valor inv�lido.');
    }
  }

  /**
   * Realiza as valida��es do campo Situa��o do Aluno
   */
  protected function validarSituacao() {

    //Regra 1
    if ( empty($this->iSituacaoAluno) ) {
      $this->addErro('O campo "Situa��o do aluno" � uma informa��o obrigat�ria.');
    }

    //Regra 2
    if ( !in_array($this->iSituacaoAluno, array(1,2,3,4,5,6,7)) ) {
      $this->addErro('O campo "Situa��o do aluno" foi preenchido com valor inv�lido');
    }

    //Regra 5
    $iEtapaCenso = $this->oMatricula->getEtapaDeOrigem()->getEtapaCenso();
    $aEtapas     = array(11, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 65, 67, 68, 70, 71, 73, 74);

    if ( $this->iSituacaoAluno == 6 && !in_array($iEtapaCenso, $aEtapas) ) {

      $sErro  = 'O campo "Situa��o do aluno" n�o pode ser preenchido com 6 (Aprovado concluinte) quando a etapa da';
      $sErro .= ' matr�cula for diferente de 11, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 65, 67, 68, 70, 71, 73 e 74.';
      $this->addErro($sErro);
    }

    //Regra 6
    $aEtapas = array(1, 2, 39, 40, 65, 67, 68, 69, 70, 71, 73, 74);
    if ( $this->iSituacaoAluno == 7 && !in_array($iEtapaCenso, $aEtapas) ) {

      $sErro  = 'O campo "Situa��o do aluno" n�o pode ser preenchido com 7 (Em andamento) quando a etapa da';
      $sErro .= ' matr�cula for diferente de 1, 2, 39, 40, 65, 67, 68, 69, 70, 71, 73 e 74.';
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
   * Adiciona os erros/inconsist�ncias encontradas no array de erros
   * @param string $sMsgErro
   */
  protected function addErro($sMsgErro) {
    $this->aErros[] = $this->sInformacaoAluno . $sMsgErro;
  }

}