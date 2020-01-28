<?php

/**
* 
*/
class ReajusteSalarial{

  private $aServidores = array();

  private $iPercentual;

  private $iValor;

  function __construct() {}

  public function adicionaServidor($oServidor) {

    $this->aServidores[]  = $oServidor;
  }

  public function getServidores(){
    return $this->aServidores;
  }

  public function setPercentual($iPercentual){

    if (isset($this->iValor)) {
      throw new BusinessException("Já está setado tipo de reajuste por valor, não pode ser usado porcentagem.");
    }

    $this->iPercentual = $iPercentual;
  }

  public function setValor($iValor){

    if (isset($this->iPercentual)) {
      throw new BusinessException("Já está setado tipo de reajuste por percentual, não pode ser usado valor.");
    }

    $this->iValor = $iValor;
  }

  public function reajustaSalario(){

    foreach ($this->aServidores as $oServidor) {

      $oDaoRhPessoalMov = db_utils::getDao('rhpessoalmov');
      $oDaoRhPessoalMov->rh02_seqpes = $oServidor->getCodigoMovimentacao();
      $oDaoRhPessoalMov->rh02_salari = $this->getNovoValor($oServidor->getSalario());
      $oDaoRhPessoalMov->alterar($oServidor->getCodigoMovimentacao());

      if ($oDaoRhPessoalMov->erro_status == '0'){
        throw new DBException("Ocorreu um erro ao Efetuar o Reajuste");
      }
    }

    return true;    
  }

  private function getNovoValor($sSalarioAtual) {

    if (!isset($this->iValor) && !isset($this->iPercentual)){
      throw new BusinessException("Por favor, informe a forma de reajuste (Valor ou Percentual)");
    }

    /**
     * Se for reajuste por valor, retorna o valor informado.
     */
    if (isset($this->iValor)) {
      return $this->iValor;
    }

    /**
     * Se for reajuste por porcentagem, realiza o calculo de 
     * porcentagem de acordo com o valor informado por parametro.
     */
    $iValor = $sSalarioAtual + ($sSalarioAtual * ($this->iPercentual / 100));

    return $iValor;
  }



}

