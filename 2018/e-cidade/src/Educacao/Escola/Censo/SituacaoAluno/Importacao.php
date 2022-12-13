<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno;

use ECidade\Educacao\Escola\Censo\Censo as CensoEscolar;
use ECidade\Educacao\Escola\Censo\SituacaoAluno\Layout;

/**
 * Classe respons�vel importa��o dos c�digos INEPs dos alunos
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.5 $
 */
class Importacao {

  /**
   * @var db_layouttxt
   */
  private $oLayout = null;

  /**
   * @var Escola
   */
  private $oEscola = null;

  /**
   * @var CensoEscolar
   */
  private $oCenso = null;

  private $aLinhas = array();

  private $lPossuiInconsistencia = false;

  public function __construct(CensoEscolar $oCenso, \Escola $oEscola ) {

    $this->oCenso  = $oCenso;
    $this->oEscola = $oEscola;
  }

  /**
   * L� os dados do arquivo retornando um array com o conte�do
   * @param  string $sFilePath
   * @return \DBLayoutLinha[]
   */
  private function lerArquivo($sFilePath) {

    switch ($this->oCenso->getAno()) {
      case 2016:

        $this->aLinhas = Layout\Layout2016::lerArquivo($sFilePath);
        break;

      default:

        throw new \BusinessException("N�o h� layout cadastrado para o ano de {$this->oCenso->getAno()}.");
        break;
    }

    $this->validarConteudoArquivo();
    unset($this->aLinhas[0]); // n�o precisa do registro 89

    return $this->aLinhas;
  }

  /**
   * Para garantir que o arquivo cont�m dados a ser importado e que se trata de um arquivo de Situa��o do Aluno
   * @return boolean
   */
  private function validarConteudoArquivo() {

    if ( count($this->aLinhas) == 0 ) {
      throw new \Exception("N�o foram encontrados registros para importa��o.");
    }

    foreach ($this->aLinhas as $iIndex => $oLinha) {

      if (!($oLinha instanceof \DBLayoutLinha)) {
        throw new \BusinessException( 'Arquivo inv�lido.' );
      }

      $iLinha = $iIndex + 1;
      if ( !in_array( $oLinha->tipo_registro, array( 89, 90, 91 )) ) {

        $sMsg  = "Importa��o abortada. Registro inv�lido encontrado no arquivo.\n";
        $sMsg .= "  - Registro: {$oLinha->tipo_registro}\n  - Linha: {$iLinha}";
        throw new \Exception($sMsg);
      }

      if ( $oLinha->codigo_escola_inep != $this->oEscola->getCodigoInep() ) {

        $sMsg  = "Importa��o abortada. C�digo INEP da escola diferente da escola atual.\n";
        $sMsg .= "  - Registro: {$oLinha->tipo_registro}\n  - Linha: {$iLinha}\n";
        $sMsg .= "  - INEP no arquivo: {$oLinha->codigo_escola_inep}\n  - INEP da escola: " . $this->oEscola->getCodigoInep();
        throw new \Exception($sMsg);
      }
    }
    return true;
  }

  /**
   * Importa os c�digos INEP atualizando os alunos
   * @param  string $sFilePath
   * @return boolean
   */
  public function importar($sFilePath) {

    $this->lerArquivo($sFilePath);
    foreach ($this->aLinhas as $iIndex => $oLinha) {
      $this->atualizarAlunos($oLinha, $iIndex+1);
    }

    return true;
  }

  /**
   * Valida se os dados de importa��o est�o corretos
   * @param  \DBLayoutLinha $oLinha
   * @param  integer $iLinha
   * @return boolean
   */
  private function validarDadosLinha($oLinha, $iLinha) {

    $lValido      = true;
    $sComplemento = "n�o possui valor ou o valor informado est� inv�lido.";
    if ( empty($oLinha->codigo_turma_inep) || strlen($oLinha->codigo_turma_inep) > 10) {

      $sMsg = "Linha [{$iLinha}] campo \"C�digo da turma - INEP\" {$sComplemento}";
      LogErro::logSituacao($sMsg);
      $lValido = false;
    }
    if ( empty($oLinha->codigo_aluno_inep) || strlen($oLinha->codigo_aluno_inep) != 12 ) {
      $sMsg = "Linha [{$iLinha}] campo \"C�digo de identifica��o �nica do aluno - INEP\" {$sComplemento}";
      LogErro::logSituacao($sMsg);
      $lValido = false;
    }

    if ( empty($oLinha->codigo_matricula_inep) || strlen($oLinha->codigo_matricula_inep) > 12 ) {
      $sMsg = "Linha [{$iLinha}] campo \"C�digo da Matr�cula\" {$sComplemento}";
      LogErro::logSituacao($sMsg);
      $lValido = false;
    }

    if ( empty($oLinha->codigo_aluno_escola) ) {

      $sMsg = "Linha [{$iLinha}] campo \"C�digo do Aluno\" esta vazio. C�digo INEP do aluno no arquivo: {$oLinha->codigo_matricula_inep}";
      LogErro::logSituacao($sMsg);
      $lValido = false;
    }

    return $lValido;
  }

  /**
   * Atualiza os alunos
   * @param  \DBLayoutLinha $oLinha
   * @param  integer $iLinha
   */
  private function atualizarAlunos($oLinha, $iLinha) {

    if ( !$this->validarDadosLinha($oLinha, $iLinha) ) {

      $this->lPossuiInconsistencia = true;
      return;
    }

    $oAluno = new \Aluno($oLinha->codigo_aluno_escola);
    $oAluno->setCodigoInep($oLinha->codigo_aluno_inep);
    $oAluno->salvar();

    $oAlunoMatriculaCenso = new \AlunoMatriculaCenso($oAluno, $this->oCenso->getAno());
    $oAlunoMatriculaCenso->setTurmaCenso($oLinha->codigo_turma_inep);
    $oAlunoMatriculaCenso->setMatriculaCenso($oLinha->codigo_matricula_inep);
    $oAlunoMatriculaCenso->salvar();


    $oTurma = \TurmaRepository::getTurmaByCodigo( $oLinha->codigo_turma_escola );
    $oTurma->setCodigoInep( $oLinha->codigo_turma_inep );
    $oTurma->salvar();

    $sMensagem = "Linha [{$iLinha}]. Aluno {$oAluno->getCodigoAluno()} - {$oAluno->getNome()} importado com sucesso.";
    LogErro::logSituacao($sMensagem, \DBLog::LOG_INFO);
  }

  /**
   * se tem inconsistencia
   * @return boolean
   */
  public function temInconsitencia() {
    return $this->lPossuiInconsistencia;
  }

  /**
   * Retorna o nome (com o path) do arquivo de log
   * @return string
   */
  public function getNomeAquivoLog() {

    return LogErro::fileName();
  }
}