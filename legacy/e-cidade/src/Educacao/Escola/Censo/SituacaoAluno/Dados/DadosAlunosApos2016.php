<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

/**
 * Processa os dados do Aluno que entrou após a data do censo.
 * Registro 91 do Layout de Exportação da Situação do Aluno 2016
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.4 $
 */
class DadosAlunosApos2016 extends DadosAluno2016 {

  private $iMediacaoDidaticoPedagogico;
  private $iModalidade;
  private $iEtapaArquivo; // etapa que vai no arquivo do censo.

  private $iEtapaMatricula;
  private $iEtapaTurma;


  public function popular($oDados) {

    parent::popular($oDados);
    $this->iMediacaoDidaticoPedagogico = $oDados->mediacao_didatico_pedagogico;
    $this->iModalidade                 = $oDados->modalidade;
    $this->iEtapaMatricula             = $oDados->etapa;
    $this->iEtapaTurma                 = $oDados->etapa_turma;
    $this->iEtapaArquivo               = $oDados->etapa_turma;

    if ( empty($this->iTurmaInep) ) {
      $this->iEtapaArquivo = $this->iEtapaMatricula;
    }

    if ( !empty($this->iTurmaInep) ) {

      //campo 8 - regra 2
      $this->iMediacaoDidaticoPedagogico = '';
      //campo 9 - regra 2
      $this->iModalidade = '';
    }

    // campo 10 - regra 2
    $aMultiEtapaCenso = array(3, 12, 13, 22, 23, 24, 56, 64, 72);
    if ( in_array($this->iEtapaTurma, $aMultiEtapaCenso) ) {
      $this->iEtapaArquivo = $this->iEtapaMatricula;
    }
    // campo 10 - regra 3
    if ( !empty($this->iTurmaInep) && !in_array($this->iEtapaTurma, $aMultiEtapaCenso) ) {
      $this->iEtapaArquivo = '';
    }
  }

  /**
   * Transforma os dados da classe em uma stdClass para informar no layout
   * @return stdClass
   */
  public function transformarStdClass() {

    $oDados = new \stdClass();

    $oDados->tipo_registro                = 91;
    $oDados->codigo_escola_inep           = $this->iEscolaInep;
    $oDados->codigo_turma_escola          = $this->iTurmaEscola;
    $oDados->codigo_turma_inep            = $this->iTurmaInep;
    $oDados->codigo_aluno_inep            = $this->iAlunoInep;
    $oDados->codigo_aluno_escola          = $this->iAlunoEscola;
    $oDados->codigo_matricula_inep        = $this->iMatriculaInep;
    $oDados->mediacao_didatico_pedagogico = $this->iMediacaoDidaticoPedagogico;
    $oDados->modalidade                   = $this->iModalidade;
    $oDados->etapa                        = $this->iEtapaArquivo;
    $oDados->situacao_aluno               = $this->iSituacaoAluno;

    return $oDados;
  }

  public function validar($iInepEscola) {

    $this->validarINEPEscola( $iInepEscola );
    $this->validarINEPTurma();
    $this->validarINEPAluno();
    $this->validarINEPMatricula();
    $this->validarSituacao();

    $this->validarMediacaoDidaticoPedagogica();
    $this->validarModalidade();
    $this->validarEtapa();

    return count($this->aErros) == 0;
  }

  /**
   * Realiza as validações do campo 2 Código da Escola - INEP
   */
  protected function validarINEPTurma() {

    // campo 4 - regra 1
    if ( !empty($this->iTurmaInep) && !\DBString::validarTamanhoMaximo($this->iTurmaInep, 10) ) {
      $this->addErro('O campo "Código da turma - INEP" está maior que o especificado.');
    }
    // campo 4 - regra 2
    if ( !empty($this->iTurmaInep) && !\DBString::isSomenteNumero($this->iTurmaInep) ) {
      $this->addErro('O campo "Código da turma - INEP" foi preenchido com valor inválido.');
    }
  }

  /**
   * Realiza as validações do campo Matrícula (INEP)
   */
  protected function validarINEPMatricula() {

    //Regra 1
    if ( !empty($this->iMatriculaInep) ) {
      $this->addErro('O campo "Código da matrícula" não pode ser preenchido.');
    }
  }

  /**
   * Realiza as validações do campo Tipo de mediação didático pedagógico
   */
  protected function validarMediacaoDidaticoPedagogica() {

    // campo 8 - regra 1
    if ( empty($this->iTurmaInep) && empty($this->iMediacaoDidaticoPedagogico) ) {
      $this->addErro('O campo "Tipo de mediação didático pedagógico" deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.');
    }

    // campo 8 - regra 3
    if ( !empty($this->iMediacaoDidaticoPedagogico) && !in_array($this->iMediacaoDidaticoPedagogico, array(1,2,3))) {
      $this->addErro('O campo "Tipo de mediação didático pedagógico" foi preenchido com valor inválido.');
    }
  }

  /**
   * Realiza as validações do campo Código da Modalidade
   */
  protected function validarModalidade() {

    //campo 9 - regra 1
    if ( empty($this->iTurmaInep) && empty($this->iModalidade) ) {
      $this->addErro('O campo "Código da modalidade" deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.');
    }
    //campo 9 - regra 3
    if ( !empty($this->iModalidade) && !in_array($this->iModalidade, array(1,2,3,4)) ) {
      $this->addErro('O campo "Código da modalidade" foi preenchido com valor inválido.');
    }
    //campo 9 - regra 4
    if ( $this->iMediacaoDidaticoPedagogico == 3 && !in_array($this->iModalidade, array(1,3,4) )) {
      $this->addErro('O campo "Código da modalidade" deve ser preenchido com 1, 3 ou 4 quando o campo "Mediação didático-pedagógica" for igual a 3 (Educação a Distância).');
    }

    //campo 9 - regra 5
    if ( !empty($this->iModalidade) && $this->iModalidade == 2 ) {

      $aDeficiencias = $this->oMatricula->getAluno()->getNecessidadesEspeciais();
      if ( count($aDeficiencias) == 0 ) {
        $this->addErro('Aluno(a) sem deficiência, transtorno global do desenvolvimento ou altas habilidades/superdotação não pode ser admitido após em uma turma de educação especial.');
      } else {

        $lPossueDeficiencia = false;
        foreach ($aDeficiencias as $oDeficiencia) {

          if ( in_array($oDeficiencia->iCodigo, array(109, 110, 111, 112, 113)) ) {
            $lPossueDeficiencia = true;
          }
        }

        if ( !$lPossueDeficiencia ) {
          $this->addErro('Aluno(a) sem deficiência, transtorno global do desenvolvimento ou altas habilidades/superdotação não pode ser admitido após em uma turma de educação especial.');
        }
      }
    }
  }

  /**
   * Realiza as validações do campo Código da Etapa
   */
  protected function validarEtapa() {

    // campo 10 - regra 1
    if ( empty($this->iTurmaInep) && empty($this->iEtapaArquivo) ) {
      $this->addErro('O campo "Código da etapa" deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.');
    }
    // campo 10 - regra 5
    if ( in_array($this->iEtapaArquivo, array(3, 12, 13, 22, 23, 24, 72, 56, 64, 68)) ) {
      $this->addErro('campo "Código da etapa" foi preenchido com valor não permitido.');
    }
    // campo 10 - regra 6
    if ( $this->iMediacaoDidaticoPedagogico == 2 && !in_array($this->iEtapaArquivo, array(69, 70, 71)) ) {
      $this->addErro('O campo "Etapa de Ensino" deve ser preenchido com 69, 70 ou 71 quando o campo "Mediação didático-pedagógica" for igual a 2 (Semipresencial).');
    }
    // campo 10 - regra 7
    if ( $this->iMediacaoDidaticoPedagogico == 3 &&
         !in_array($this->iEtapaArquivo, array(30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74, 67)) ) {
      $this->addErro('O campo "Etapa de Ensino" deve ser preenchido com 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74 ou 67 quando o campo "Mediação didático-pedagógica" for igual a 3 (Educação a Distância).');
    }

    $sMsg = 'O campo "Código da etapa" foi preenchido com valor incompatível com a turma informada no campo "Código da turma - INEP".';
    // campo 10 - regra 9
    if ( $this->iEtapaTurma == 3 && !in_array($this->iEtapaArquivo, array(1, 2)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 10
    if ( in_array($this->iEtapaTurma, array(12, 13)) && !in_array($this->iEtapaArquivo, array(4, 5, 6, 7, 8, 9, 10, 11)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 11
    if ( in_array($this->iEtapaTurma, array(22, 23)) && !in_array($this->iEtapaArquivo, array(14, 15, 16, 17, 18, 19, 20, 21, 41)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 12
    if ( $this->iEtapaTurma == 72 && !in_array($this->iEtapaArquivo, array(4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21, 41)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 13
    if ( $this->iEtapaTurma == 56 && !in_array($this->iEtapaArquivo, array(69,70)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 14
    if ( $this->iEtapaTurma == 56 && !in_array($this->iEtapaArquivo, array(1,2,4,5,6,7,8,9,10,11,14,15,16,17,18,19,20,21,41)) ) {
      $this->addErro($sMsg);
    }
    // campo 10 - regra 15
    if ( $this->iEtapaTurma == 64 && !in_array($this->iEtapaArquivo, array(39,40)) ) {
      $this->addErro($sMsg);
    }
  }
}
