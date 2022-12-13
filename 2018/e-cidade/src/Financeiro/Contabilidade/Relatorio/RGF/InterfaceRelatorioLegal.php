<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF;

interface InterfaceRelatorioLegal {

  public function emitir();

  public function emitirDadosSimplificado();
}