<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

class DadosAlunoAntes2016 extends DadosAluno2016 {

  /**
   * Transforma os dados do model em um stdClass para escrever o arquivo de exportação
   * @return stdClass
   */
  public function transformarStdClass(){

    $oDados = new \stdClass();
    $oDados->tipo_registro         = 90;
    $oDados->codigo_escola_inep    = $this->iEscolaInep;
    $oDados->codigo_turma_escola   = $this->iTurmaEscola;
    $oDados->codigo_turma_inep     = $this->iTurmaInep;
    $oDados->codigo_aluno_inep     = $this->iAlunoInep;
    $oDados->codigo_aluno_escola   = $this->iAlunoEscola;
    $oDados->codigo_matricula_inep = $this->iMatriculaInep;
    $oDados->situacao_aluno        = $this->iSituacaoAluno;

    return $oDados;
  }

  /**
   * Consiste os dados de acordo com o layout do CENSO
   * @param integer $iCodigoEscolaINEP
   * @return boolean
   */
  public function validar( $iCodigoEscolaINEP ) {

    $this->validarINEPEscola( $iCodigoEscolaINEP );
    $this->validarINEPTurma();
    $this->validarINEPAluno();
    $this->validarINEPMatricula();
    $this->validarSituacao();

    return count($this->aErros) == 0;
  }

}