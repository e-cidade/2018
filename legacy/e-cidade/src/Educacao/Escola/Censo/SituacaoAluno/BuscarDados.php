<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno;
use ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados as Dados;


class BuscarDados {

  private $oCenso;
  private $oEscola;
  private $aCodigoAlunoAntes = array();

  /**
   * @param ECidade\Educacao\Escola\Censo\Censo
   */
  function __construct($oCenso, $oEscola)  {

    $this->oCenso  = $oCenso;
    $this->oEscola = $oEscola;
  }


  public function registro89()  {

    switch ($this->oCenso->getAno()) {
      case 2016:

        $oDados =  new Dados\BuscaDadosEscola2016( $this->oCenso, $this->oEscola );
        return $oDados->getDados();
        break;

      default:
        throw new \BusinessException("Não foi possível buscar os dados da Escola.");
        break;
    }
  }

  public function registro90() {

    switch ($this->oCenso->getAno()) {
      case 2016:

        $oDados =  new Dados\BuscaDadosAlunosAntes2016( $this->oCenso, $this->oEscola );
        $aDados = $oDados->getDados();

        $aDadosAlunosAntes = array();
        foreach ($aDados as $oDadosAluno) {

          $oValidacaoAluno = new Dados\DadosAlunoAntes2016();
          $oValidacaoAluno->popular( $oDadosAluno );
          $aDadosAlunosAntes[] = $oValidacaoAluno;
        }

        if ( empty($aDadosAlunosAntes)) {
          throw new \BusinessException("Nenhum aluno encontrado para a escola informada.");
        }
        $this->aCodigoAlunoAntes = $oDados->getCodigoAlunos();
        return $aDadosAlunosAntes;
        break;

      default:
        throw new \BusinessException("Não foi possível buscar os dados do aluno.");
        break;
    }
  }
  public function registro91() {

    switch ($this->oCenso->getAno()) {
      case 2016:

        $oDados =  new Dados\BuscaDadosAlunosApos2016( $this->oCenso, $this->oEscola, $this->aCodigoAlunoAntes );
        $aDados = $oDados->getDados();

        $aDadosAluno = array();
        foreach ($aDados as $oDadosAluno) {

          $oValidacaoAluno = new Dados\DadosAlunosApos2016();
          $oValidacaoAluno->popular( $oDadosAluno );
          $aDadosAluno[] = $oValidacaoAluno;
        }

        return $aDadosAluno;
        break;

      default:
        throw new \BusinessException("Não foi possível buscar os dados da Escola.");
        break;
    }
  }


}
