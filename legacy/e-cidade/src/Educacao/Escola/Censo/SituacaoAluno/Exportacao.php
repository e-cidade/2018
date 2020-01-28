<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno;

use ECidade\Educacao\Escola\Censo\Censo as CensoEscolar;
use ECidade\Educacao\Escola\Censo\SituacaoAluno\Layout;


/**
 * Classe responsável pela geração do arquivo do censo de Situação do Aluno
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author André Mello  <andre.mello@dbseller.com.br>
 * @version $Revision: 1.5 $
 */
class Exportacao {

  /**
   * @var db_layouttxt
   */
  private $oLayout = null;

  /**
   * @var Escola
   */
  private $oEscola = null;

  private $oDadosEscola      = null;
  private $aDadosAlunoAntes  = array();
  private $aDadosAlunoDepois = array();

  /**
   *
   * @var Censo
   */
  private $oCenso = null;


  public function __construct( CensoEscolar $oCenso, \Escola $oEscola )  {

    $this->oCenso  = $oCenso;
    $this->oEscola = $oEscola;
  }

  /**
   * Busca o layout do censo de acordo com o ano que estamos gerando o aquivo
   * @throws BusinessException
   * @return Layout
   */
  protected function buscarLayout() {

    switch ($this->oCenso->getAno()) {
      case 2016:


        $this->oLayout = new Layout\Layout2016();
        break;

      default:

        throw new \BusinessException("Não há layout cadastrado para o ano de {$this->oCenso->getAno()}.");
        break;
    }

    return $this->oLayout;
  }

  /**
   * Busca os dados do censo de acordo com o ano
   */
  protected function buscarDados() {

    $oDados = new BuscarDados($this->oCenso, $this->oEscola);

    $this->oDadosEscola      = $oDados->registro89();
    $this->aDadosAlunoAntes  = $oDados->registro90();
    $this->aDadosAlunoDepois = $oDados->registro91();
  }

  /**
   * Carrega os dados e gera o arquivo
   * @return boolean false se teve inconsistencias
   */
  public function gerarArquivo() {

    $this->buscarDados();
    $this->buscarLayout();

    if ( !$this->validar() ) {
      return false;
    }

    $this->escreverRegistros();
    return true;
  }

  /**
   * Escreve os dados no arquivo
   */
  private function escreverRegistros() {

    $this->oLayout->escreverLinha($this->oDadosEscola->transformarStdClass(), 3, 89);
    foreach ($this->aDadosAlunoAntes as $oDadosAluno) {
      $this->oLayout->escreverLinha($oDadosAluno->transformarStdClass(), 3, 90);
    }
    foreach ($this->aDadosAlunoDepois as $oDadosAluno) {
      $this->oLayout->escreverLinha($oDadosAluno->transformarStdClass(), 3, 91);
    }
  }

  /**
   * Realiza as validações de todos os registros e havendo inconsistencias registra as mesmas em uma arquivo de log
   * @return boolea true se validou sem inconsistencias
   */
  private function validar() {

    $aValidacoes   = array();
    $aValidacoes[] = $this->oDadosEscola->validar();

    $this->registrarErros($this->oDadosEscola->getErros(), 89);

    foreach ($this->aDadosAlunoAntes as $oDadosAluno) {

      $aValidacoes[] = $oDadosAluno->validar( $this->oDadosEscola->getCodigoINEP() );
      $this->registrarErros($oDadosAluno->getErros(), 90);
    }

    foreach ($this->aDadosAlunoDepois as $oDadosAluno) {

      $aValidacoes[] = $oDadosAluno->validar( $this->oDadosEscola->getCodigoINEP() );
      $this->registrarErros($oDadosAluno->getErros(), 91);
    }

    return !in_array(false, $aValidacoes);
  }

  /**
   * Registra os erros/inconsistencias no arquivo de log
   * @param  array   $aErros
   * @param  integer $iRegistro
   */
  private function registrarErros($aErros, $iRegistro) {

    foreach ($aErros as $sMsg) {
      LogErro::log($sMsg, $iRegistro);
    }
  }

  /**
   * Retorna o nome (com o path) do arquivo de log
   * @return string
   */
  public function getNomeAquivoLog() {

    return LogErro::fileName();
  }

  /**
   * Retorna o aqruivo do censo
   * @return string
   */
  public function getNomeArquivo() {
    return $this->oLayout->getNomeArquivo();
  }
}
