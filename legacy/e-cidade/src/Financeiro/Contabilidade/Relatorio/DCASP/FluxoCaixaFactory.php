<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP;

use FluxoCaixaDCASP2015;
use FluxoCaixaDCASP2017;

require_once(modification('libs/db_sessoes.php'));

class FluxoCaixaFactory
{
    private $processador;
    private $codigoRelatorio;
    private $fluxoCaixa;
    private $ano;
    private $periodo;

    public function __construct($periodo = null)
    {
        $this->periodo = $periodo;
        $this->ano = db_getsession('DB_anousu');
        $this->processador = 'con2_fluxocaixaDCASP002.php';
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
        $this->codigoRelatorio = FluxoCaixaDCASP2015::CODIGO_RELATORIO;

        if ($this->periodo) {
            $this->fluxoCaixa = new FluxoCaixaDCASP2015($this->ano, $this->codigoRelatorio, $this->periodo);
        }
    }

    private function configurar2017()
    {
        $this->codigoRelatorio = FluxoCaixaDCASP2017::CODIGO_RELATORIO;

        if ($this->periodo) {
            $this->fluxoCaixa = new FluxoCaixaDCASP2017($this->ano, $this->codigoRelatorio, $this->periodo);
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
        return $this->fluxoCaixa;
    }
}
