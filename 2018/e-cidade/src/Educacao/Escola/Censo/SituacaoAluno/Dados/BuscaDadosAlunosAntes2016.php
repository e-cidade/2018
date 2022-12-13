<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;
use ECidade\Educacao\Escola\Censo\Censo;
class BuscaDadosAlunosAntes2016 extends BuscaDadosAlunos2016 {

  private $aCodigoAlunosAntes = array();

  public function __construct(Censo $oCenso, $oEscola ) {

    $aCondicoes   = array();
    $aCondicoes[] = " matricula.ed60_d_datamatricula <= '" . $oCenso->getDataCenso()->getDate(). "'";
    $aCondicoes[] = " ( matricula.ed60_d_datasaida > '" . $oCenso->getDataCenso()->getDate(). "' or ed60_c_situacao = 'MATRICULADO')";

    $this->buscarAlunos($oCenso, $oEscola, $aCondicoes);
    $this->processar($oCenso);
  }

  /**
   * Altera os dados do aluno quando o mesmo possui uma matrícula posterior no mesmo ano na mesma escola.
   * Casos como alunos que foram transferidos e retornaram para a escola no mesmo ano ou que foram avançados.
   * @param  Censo $oCenso
   */
  private function processar(Censo $oCenso) {

    foreach ($this->aDados as $oDadosAluno) {

      $this->aCodigoAlunosAntes[] = $oDadosAluno->codigo_aluno_escola;

      $oMatricula       = \MatriculaRepository::getMatriculaByCodigo( $oDadosAluno->codigo_matricula );
      $oUltimaMatricula = \MatriculaRepository::getUltimaMatriculaAluno( $oMatricula->getAluno(), $oCenso->getAno() );

      if ( $oMatricula->getCodigo() != $oUltimaMatricula->getCodigo() ) {

        $oEscola       = $oMatricula->getTurma()->getEscola();
        $oUltimaEscola = $oUltimaMatricula->getTurma()->getEscola();

        if ( $oEscola->getCodigo() == $oUltimaEscola->getCodigo() ) {

          $oDadosAluno->codigo_turma_escola   = $oUltimaMatricula->getTurma()->getCodigo();
          $oDadosAluno->codigo_turma_inep     = $oUltimaMatricula->getTurma()->getCodigoInep();
          $oMatriculaCenso                    = new \AlunoMatriculaCenso(  $oUltimaMatricula->getAluno(), $oCenso->getAno() );
          $oDadosAluno->codigo_matricula_inep = $oMatriculaCenso->getMatriculaCenso();
          $oDadosAluno->etapa                 = $oUltimaMatricula->getEtapaDeOrigem()->getEtapaCenso();
          $oDadosAluno->codigo_matricula      = $oUltimaMatricula->getCodigo();
          $oDadosAluno->etapa_turma           = $oUltimaMatricula->getTurma()->getEtapaCenso();
        }
      }
    }
  }

  /**
   * Retorna os dados dos alunos com matrículas antes da data do CENSO.
   * @return stdClass[]
   */
  public function getDados() {
    return $this->aDados ;
  }

  /**
   * Retorna os códigos dos alunos
   * @return array
   */
  public function getCodigoAlunos() {
    return $this->aCodigoAlunosAntes;
  }
}
