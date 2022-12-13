<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

/**
 * Processa os dados da Escola, Registro 89 do Layout de Exporta��o da Situa��o do Aluno 2016
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class DadosEscola2016 {

  private $iInep;
  private $sCpf;
  private $sNome;
  private $iCargo;
  private $sEmail;

  private $aErros = array();

  public function popular($oDados){

    $this->iInep  = $oDados->codigo_escola_inep;
    $this->sCpf   = $oDados->cpf_gestor;
    $this->sNome  = $oDados->nome_gestor;
    $this->iCargo = $oDados->cargo_gestor;
    $this->sEmail = $oDados->email_gestor;
  }

  /**
   * Transforma os dados da classe em uma stdClass para informar no layout
   * @return stdClass
   */
  public function transformarStdClass(){

    $oValidacao = new \DadosCenso();
    $oDados     = new \stdClass();

    $oDados->tipo_registro      = 89;
    $oDados->codigo_escola_inep = $this->iInep;
    $oDados->cpf_gestor         = $this->sCpf;
    $oDados->nome_gestor        = $oValidacao->removeCaracteres($this->sNome, 1);
    $oDados->cargo_gestor       = $this->iCargo;
    $oDados->email_gestor       = $this->sEmail;

    return $oDados;
  }

  /**
   * Realiza as valida��es do registro 89 (Dados da Escola) de acordo com as regras do layout de 2016
   * @return boolean
   */
  public function validar() {

    $this->validarCampo2();
    $this->validarCampo3();
    $this->validarCampo4();
    $this->validarCampo5();
    $this->validarCampo6();

    return count($this->aErros) == 0;
  }

  /**
   * Realiza as valida��es do campo 2 -  C�digo da escola - INEP
   */
  private function validarCampo2() {

    // campo 2 - regra 1
    if ( empty($this->iInep) ) {
      $this->aErros[] = 'O campo "C�digo de escola - INEP" � uma informa��o obrigat�ria.';
    }

    // campo 2 - regra 2
    if ( strlen($this->iInep) < 8 ) {
      $this->aErros[] = 'O campo "C�digo de escola - INEP" est� com tamanho diferente do especificado.';
    }

    // campo 2 - regra 3
    if ( !\DBString::isSomenteNumero($this->iInep) ) {
      $this->aErros[] = 'O campo "C�digo de escola - INEP" foi preenchido com valor inv�lido.';
    }
  }

  /**
   * Realiza as valida��es do campo 3 - N�mero do CPF do Gestor Escolar
   */
  private function validarCampo3() {

    // campo 3 - regra 1
    if ( empty($this->sCpf) ) {
      $this->aErros[] = 'O campo "N�mero do CPF do Gestor Escolar" � uma informa��o obrigat�ria.';
    }

    // campo 3 - regra 2
    if ( strlen($this->sCpf) != 11 ) {
      $this->aErros[] = 'O campo "N�mero do CPF do Gestor Escolar" est� com tamanho diferente do especificado.';
    }

    // campo 3 - regra 3
    if ( !\DBString::isSomenteNumero($this->sCpf) ) {
      $this->aErros[] = 'O campo "N�mero do CPF do Gestor Escolar" foi preenchido com valor inv�lido.';
    }

    // campo 3 - regra 4
    if ( in_array($this->sCpf, array('00000000191', '00000000000')) ) {
      $this->aErros[] = 'O campo "N�mero do CPF do Gestor Escolar" foi preenchido com valor inv�lido.';
    }
  }

  /**
   *  Realiza as valida��es do campo 4 - Nome do Gestor Escolar
   */
  private function validarCampo4() {

    //campo 4 - regra 1
    if ( empty($this->sNome) ) {
      $this->aErros[] = 'O campo "Nome do Gestor Escolar" � uma informa��o obrigat�ria.';
    }

    //campo 4 - regra 2
    if ( !\DBString::validarTamanhoMaximo($this->sNome, 100)  ) {
      $this->aErros[] = 'O campo "Nome do Gestor Escolar" est� maior que o especificado.';
    }
  }

  /**
   * Realiza as valida��es do campo 5 - Cargo do Gestor Escolar
   */
  private function validarCampo5(){

    // campo 5 - regra 1
    if ( empty($this->iCargo) ) {
      $this->aErros[] = 'O campo "Cargo do Gestor Escolar" � uma informa��o obrigat�ria.';
    }

    // campo 5 - regra 2
    if ( !in_array($this->iCargo, array(1,2)) ) {
      $this->aErros[] = 'Somente aceita os seguintes caracteres entre par�ntesis: (1 2).';
    }
  }

  /**
   * Realiza as valida��es do campo 6 - Endere�o eletr�nico (e-mail) do Gestor Escolar
   */
  private function validarCampo6(){

    // campo 6 - regra 1
    if ( empty($this->sEmail) ){
      $this->aErros[] = 'O campo "Endere�o eletr�nico (e-mail) do Gestor Escolar" � uma informa��o obrigat�ria.';
    }

    // campo 6 - regra 2
    if ( !\DBString::validarTamanhoMaximo($this->sEmail, 50) ){
      $this->aErros[] = 'O campo "Endere�o eletr�nico (e-mail) do Gestor Escolar" est� maior que o especificado.';
    }

    // campo 6 - regra 3
    if ( !\DBString::isEmail($this->sEmail) ){
      $this->aErros[] = 'O campo "Endere�o eletr�nico (e-mail) do Gestor Escolar" est� maior que o especificado.';
    }
  }

  /**
   * Retorna os erros encotrados ao validar os dados
   * @return array
   */
  public function getErros(){
    return $this->aErros;
  }

  /**
   * Retorna o c�digo INEP da escola
   * @return integer
   */
  public function getCodigoINEP() {
    return $this->iInep;
  }
}