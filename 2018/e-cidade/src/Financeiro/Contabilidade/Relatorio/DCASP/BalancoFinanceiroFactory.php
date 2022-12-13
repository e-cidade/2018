<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP;

use BalancoFinanceiroDCASP2015;
use BalancoFinanceiroDCASP2017;

require_once(modification('libs/db_sessoes.php'));

class BalancoFinanceiroFactory
{
    private $processador;
    private $codigoRelatorio;
    private $balancoOrcamentario;
    private $ano;
    private $periodo;

    public function __construct($periodo = null)
    {
        $this->periodo = $periodo;
        $this->ano = db_getsession('DB_anousu');
        $this->processador = 'con2_relatorio_dcasp_balanco_financeiro.php';
        $this->configurar();
    }

    private function configurar()
    {
        $this->ano < 2017
            ? $this->configurar2015()
            : $this->configurar2017();
    }

    private function configurar2015()
    {
        $this->codigoRelatorio = BalancoFinanceiroDCASP2015::CODIGO_RELATORIO;

        if ($this->periodo) {
            $this->balancoOrcamentario = new BalancoFinanceiroDCASP2015($this->ano, $this->codigoRelatorio, $this->periodo);
        }
    }

    private function configurar2017()
    {
        $this->codigoRelatorio = BalancoFinanceiroDCASP2017::CODIGO_RELATORIO;

        if ($this->periodo) {
            $this->balancoOrcamentario = new BalancoFinanceiroDCASP2017($this->ano, $this->codigoRelatorio, $this->periodo);
        }
    }

    public function obterProcessador()
    {
        return $this->processador;
    }

    public function obterCodigoRelatorio()
    {
        return $this->codigoRelatorio;
    }

    public function obterFluxoCaixa()
    {
        return $this->balancoOrcamentario;
    }

    public function adicionarParametro($nome, $valor)
    {
        $this->processador .= '?' . $nome . '=' . $valor;
    }
}
