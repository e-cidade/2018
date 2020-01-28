<?php 

class RegistroHistoricoCalculo extends EventoFinanceiroFolha {
  
  private $oFolhaPagamento;
  private $iCodigo;
  
  public function __construct( $iCodigo = null ) {

    $this->iCodigo = $iCodigo;
  }
  
  public function setFolhaPagamento( FolhaPagamento $oFolhaPagamento) {
     $this->oFolhaPagamento = $oFolhaPagamento;
  }
  
  public function getFolhaPagamento() {
    return $this->oFolhaPagamento;
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }

  public function salvar() {

    $oDaoHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoHistoricoCalculo->rh143_folhapagamento = $this->getFolhaPagamento()->getSequencial();
    $oDaoHistoricoCalculo->rh143_rubrica        = $this->getRubrica()->getCodigo();
    $oDaoHistoricoCalculo->rh143_tipoevento     = $this->getNatureza();
    $oDaoHistoricoCalculo->rh143_valor          = $this->getValor();
    $oDaoHistoricoCalculo->rh143_quantidade     = $this->getQuantidade();
    $oDaoHistoricoCalculo->rh143_regist         = $this->getServidor()->getMatricula();

    if ( !empty($this->iCodigo) ) {

      $oDaoHistoricoCalculo->rh143_sequencial = $this->iCodigo;
      $oDaoHistoricoCalculo->alterar($this->iCodigo);
    } else {
      $oDaoHistoricoCalculo->incluir(null);
      $this->iCodigo = $oDaoHistoricoCalculo->rh143_sequencial;
    }

    if ( $oDaoHistoricoCalculo->erro_status == "0") {
      throw new DBException("Erro ao salvar o registro do Historico.");
    }

    return true;
  }

  public function excluir() {

    $oDaoHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoHistoricoCalculo->excluir($this->getCodigo());

    if ( $oDaoHistoricoCalculo->erro_status == "0") {
      throw new DBException("Erro ao excluir o registro do Historico.");
    }
    
  }
}