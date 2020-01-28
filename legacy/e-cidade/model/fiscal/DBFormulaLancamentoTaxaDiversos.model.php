<?php 

class DBFormulaLancamentoTaxaDiversos extends DBFormula {

  public function __construct( LancamentoTaxaDiversos $oLancamento ) {

    $nPeriodo = $oLancamento->getPeriodo();
    
    if($oLancamento->getNaturezaTaxa()->getTipoPeriodo() == 'A') {
      $nPeriodo = $nPeriodo / 12;
    }

    $this->adicionar("CODIGO_LANCAMENTO__TAXA_DIVERSOS",  $oLancamento->getCodigo());
    $this->adicionar("UNIDADE_TAXA_DIVERSOS",             $oLancamento->getUnidade());
    $this->adicionar("PERIODO_TAXA_DIVERSOS",             $nPeriodo);
    $this->adicionar("TIPO_PERIODO_TAXA_DIVERSOS",        $oLancamento->getNaturezaTaxa()->getTipoPeriodo());
    
    $dataInicio = "null";
    $dataFim    = "null";

    if($oLancamento->getDataInicio() instanceof DBDate) {
      $dataInicio = $oLancamento->getDataInicio()->getDate();
    }
    
    if($oLancamento->getDataFim() instanceof DBDate) {
      $dataFim = $oLancamento->getDataFim()->getDate();
    }

    $this->adicionar("DATA_INICIO_TAXA_DIVERSOS", $dataInicio);
    $this->adicionar("DATA_FIM_TAXA_DIVERSOS",    $dataFim);
  }
}

