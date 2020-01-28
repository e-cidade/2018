<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\Configuracao\Cadastro\Model\Feriado;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPontoSaida;

/**
 * Classe responsável pelo cálculo das horas falta de um servidor em um dia de trabalho
 * Class Falta
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Falta extends BaseHora implements Horas
{

    /**
     * Horas trabalhadas
     * @var string
     */
    private $sStringData;

    private $oDiaFalta;

    private $aMarcacoes;

    /**
     * Construtor da classe
     */
    public function __construct(DiaTrabalho $oDiaTrabalho)
    {

        $this->setDiaTrabalho($oDiaTrabalho);
        $this->setTipoHora(BaseHora::HORAS_FALTA);
        parent::__construct();
    }

    /**
     * Calcula o número de horas falta em determinado dia
     * @return \DateTime
     */
    public function calcular()
    {
        $this->sStringData = $this->getDiaTrabalho()->getData()->getDate();
        $this->oDiaFalta   = $this->getHoraZerada();
        $aHorasJornada     = $this->getDiaTrabalho()->getJornada()->getHoras();
        $this->aMarcacoes  = $this->getDiaTrabalho()->getMarcacoes();

        /**
         * Se não tem jornada no dia não tem hora falta
         */
        if (empty($aHorasJornada)) {
            return $this->oDiaFalta;
        }

        /**
         * Se é um feriado, não gera falta
         */
        if ($this->getDiaTrabalho()->getFeriado() instanceof Feriado) {
            $lRevezamento=$this->getDiaTrabalho()->getServidor()->getEscalas($this->getDiaTrabalho()->getData())->getEscalaTrabalho()->getRevezamento();
            if (!$lRevezamento) {
                if ($this->getDiaTrabalho()->getFeriado()->getData()->getDate() == $this->getDiaTrabalho()->getData()->getDate()) {
                    return $this->getHoraZerada();
                }
            }
        }

        if (!$this->aMarcacoes->isEmpty()) {
            $lAbonoTotal = true;
            foreach ($this->aMarcacoes->getMarcacoes() as $oMarcacao) {
                if ($oMarcacao->getJustificativa() == null) {
                    $lAbonoTotal = false;
                    /**
                      * Caso tenha justificativa em alguma marcação
                      * substitui o horário da marcação pelo horário
                      * da jornada para posteriormente calcular as faltas
                      */
                } else {
                    if (isset($aHorasJornada[$oMarcacao->getTipo()-1])) {
                        $oMarcacao->setMarcacao(clone $aHorasJornada[$oMarcacao->getTipo()-1]->oHora);
                    }

                    if ($oMarcacao instanceof MarcacaoPontoSaida) {
                        if (isset($aHorasJornada[$oMarcacao->getTipo()-2])) {
                            $oMarcacao->setMarcacaoEntrada(clone $aHorasJornada[$oMarcacao->getTipo()-2]->oHora);
                        }
                    }
                }
            }

            /**
              * Se houver justificaticas em todas as marcações
              * significa que tem um abono total, logo não tem faltas
              */
            if ($lAbonoTotal) {
                return $this->oDiaFalta;
            }
        }

        /**
         * Caso não tenha marcações, retorna por padrão o total de horas da jornada como falta execeto
         */
        if ($this->aMarcacoes->isEmpty()) {
            if ($this->getDiaTrabalho()->isAfastado()) {
                return $this->getHoraZerada();
            }

            return $this->totalHorasJornada(false);
        }

        if (!$this->getDiaTrabalho()->getJornada()->isDiaTrabalhado()) {
            return $this->getHoraZerada();
        }

        foreach ($aHorasJornada as $chaveHorasJornada => $oHoraJornada) {
            switch ($oHoraJornada->iTipoRegistro) {
                case MarcacaoPonto::ENTRADA_1:
                    $this->calculaFaltaEntradaPadrao($oHoraJornada, $this->aMarcacoes->getMarcacaoEntrada1());
                    break;
                case MarcacaoPonto::SAIDA_1:
                    /**
                     * Quando o primeiro período não possui nenhuma marcação lançada, mantem somente o cálculo da entrada, evitando
                     * duplicação no cálculo
                     */
                    if ($this->aMarcacoes->getMarcacaoEntrada1()->getMarcacao() == null
                        && $this->aMarcacoes->getMarcacaoSaida1()->getMarcacao() == null) {
                        break;
                    }

                    if ($this->aMarcacoes->getMarcacaoEntrada2() == null
                       ||  $this->aMarcacoes->getMarcacaoEntrada2()->getJustificativa() != null
                       || ($this->aMarcacoes->getMarcacaoEntrada2()->getMarcacao() == null && count($aHorasJornada) == 2)
                       ) {
                        $this->calculaFaltaSaidaPadrao($oHoraJornada, $this->aMarcacoes->getMarcacaoSaida1());
                        
                        if ($this->aMarcacoes->getMarcacaoSaida1() == null) {
                            $this->oDiaFalta->add($this->aMarcacoes->getMarcacaoEntrada1()->getMarcacao()->diff($oHoraJornada->oHora));
                        }
                    }

                    if ($this->aMarcacoes->getMarcacaoSaida1() != null && $this->aMarcacoes->getMarcacaoSaida1()->getJustificativa() == null) {
                        if ($this->aMarcacoes->getMarcacaoEntrada2() && $this->aMarcacoes->getMarcacaoEntrada2()->getMarcacao() != null && $this->aMarcacoes->getMarcacaoEntrada2()->getJustificativa() == null) {
                            $oPeriodoIntervalo = $this->calcularHoraIntervalo(
                                $this->aMarcacoes->getMarcacaoSaida1()->getMarcacao(),
                                $this->aMarcacoes->getMarcacaoEntrada2()->getMarcacao()
                            );

                            if (!empty($oPeriodoIntervalo)) {
                                $this->oDiaFalta->add($oPeriodoIntervalo);
                            }
                        }
                    }

                    break;

                case MarcacaoPonto::ENTRADA_2:
                    if ($this->aMarcacoes->getMarcacaoSaida1() != null && $this->aMarcacoes->getMarcacaoEntrada2() != null) {
                        if ($this->aMarcacoes->getMarcacaoSaida1()->getJustificativa() != null && $this->aMarcacoes->getMarcacaoEntrada2()->getJustificativa() != null) {
                            if ($this->calcularHoraIntervalo($this->aMarcacoes->getMarcacaoSaida1()->getMarcacao(), $this->aMarcacoes->getMarcacaoEntrada2()->getMarcacao()) != null) {
                                break;
                            }
                        }
                    }

                    break;

                case MarcacaoPonto::SAIDA_2:
                    if ($this->aMarcacoes->getMarcacaoEntrada2() == null && $this->aMarcacoes->getMarcacaoSaida2() == null) {
                        break;
                    }

                    /**
                     * Quando o primeiro período não possui nenhuma marcação lançada, mantem somente o cálculo da entrada, evitando
                     * duplicação no cálculo
                     */
                    if ($this->aMarcacoes->getMarcacaoEntrada2()->getMarcacao() == null
                        && $this->aMarcacoes->getMarcacaoSaida2()->getMarcacao() == null) {
                        $intervaloDiferencaEntradaSaida = $this->getDiferencaHoras($aHorasJornada[2]->oHora, $aHorasJornada[3]->oHora);

                        $this->oDiaFalta->add($intervaloDiferencaEntradaSaida);
                        break;
                    }

                    $this->calculaFaltaSaidaPadrao($oHoraJornada, $this->aMarcacoes->getMarcacaoSaida2());
                    break;

                case MarcacaoPonto::ENTRADA_3:
                    $this->calculaFaltaEntradaPadrao($oHoraJornada, $this->aMarcacoes->getMarcacaoEntrada3());
                    break;

                case MarcacaoPonto::SAIDA_3:
                    /**
                     * Quando o primeiro período não possui nenhuma marcação lançada, mantem somente o cálculo da entrada, evitando
                     * duplicação no cálculo
                     */
                    if ($this->aMarcacoes->getMarcacaoEntrada3()->getMarcacao() == null
                        && $this->aMarcacoes->getMarcacaoSaida3()->getMarcacao() == null) {
                        break;
                    }

                    $this->calculaFaltaSaidaPadrao($oHoraJornada, $this->aMarcacoes->getMarcacaoSaida3());
                    break;
            }
        }

        return $this->oDiaFalta;
    }

    /**
     * @param \DateTime|null $oSaida
     * @param \DateTime|null $oEntrada
     * @return bool|\DateInterval|null
     */
    protected function calcularHoraIntervalo(\DateTime $oSaida = null, \DateTime $oEntrada = null)
    {
        /**
         * Caso em que há marcação Saída1, porém sem a Entrada2
         * Deve retornar as faltas entre a hora de Saída1 da jornada com a marcação, quando a marcação for menor
         */

        if (!empty($oSaida) && empty($oEntrada)) {
            $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

            if ($aHorasJornada[1]->oHora->getTimeStamp() >= $oSaida->getTimestamp()) {
                return $this->getDiferencaHoras($oSaida, $aHorasJornada[1]->oHora);
            }
        }

        if (empty($oSaida) && empty($oEntrada)) {
            return null;
        }

        if (!$this->getDiaTrabalho()->getJornada()->temIntervalo()) {
            return null;
        }

        $oHoraDeIntervalo  = new \DateTime($this->sStringData." ".$this->getDiferencaHoras($oSaida, $oEntrada)->format('%H:%I'));
        $oIntervaloJornada = new \DateTime($this->sStringData." ".$this->getHorasIntervalo()->format('%H:%I'));

        if ($oHoraDeIntervalo > $oIntervaloJornada) {
            return $oIntervaloJornada->diff($oHoraDeIntervalo);
        }

        return null;
    }

    /**
     * @param $oHoraJornada
     * @param $oMarcacao
     */
    protected function calculaFaltaEntradaPadrao($oHoraJornada, $oMarcacao)
    {

        if ($oHoraJornada != null && $oMarcacao != null) {
            if ($oMarcacao->getMarcacao() != null && $oMarcacao->getMarcacao() > $oHoraJornada->oHora) {
                $oDiferenca        = $oHoraJornada->oHora->diff($oMarcacao->getMarcacao());
                $iMinutosFalta     = (int) $oDiferenca->format('%I');
                $iToleranciaPadrao = 5;

                /**
                 * Realizamos o ajuste de tolerância.
                 */
                if ((int) $oDiferenca->format('%H') > 0 || $iMinutosFalta > $iToleranciaPadrao) {
                    $this->oDiaFalta->add($oDiferenca);
                }
            }
            if ($oMarcacao->getMarcacao() == null) {
                $horas = $this->getDiaTrabalho()->getJornada()->getHoras();
                $tipoRegistro = $oHoraJornada->iTipoRegistro;
            
                if (empty($horas[$tipoRegistro - 1])) {
                    return;
                }

                if ($horas) {
                    $horaSaidaPadrao = $horas[$tipoRegistro - 1]->oHora;
                    $horaEntradaPadrao = $horas[$tipoRegistro]->oHora;
                    $this->oDiaFalta->add($horaSaidaPadrao->diff($horaEntradaPadrao));
                }
            }
        }
    }

    /**
     * @param $oHoraJornada
     * @param $oMarcacao
     */
    protected function calculaFaltaSaidaPadrao($oHoraJornada, $oMarcacao)
    {
        if ($oHoraJornada != null && $oMarcacao != null) {
            if ($oMarcacao->getMarcacao() != null && $oMarcacao->getMarcacao() < $oHoraJornada->oHora) {
                $sDataAtual = $this->getDiaTrabalho()->getData()->getDate();
    
                $oDiferenca             = $oMarcacao->getMarcacao()->diff($oHoraJornada->oHora);
                $iMinutosFalta          = (int) $oDiferenca->format('%I');
                $oToleranciaPadrao      = new \DateTime($sDataAtual . ' 0:05');
                $oToleranciaConfigurada = new \DateTime($sDataAtual . ' 0:' . $this->getDiaTrabalho()->getTolerancia());
    
                $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();
                $oHoraJornadaPrimeiraEntrada = current($aHorasJornada);
    
                $oIntervaloDiferencaEntrada = new \DateInterval("PT0H0M0S");
                if ($this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao() != null
                    && $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao()->diff($oHoraJornadaPrimeiraEntrada->oHora)->invert) {
                    $oIntervaloDiferencaEntrada = $oHoraJornadaPrimeiraEntrada->oHora->diff($this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao());
                }

                $oDiferencaTotal = new \DateTime($sDataAtual . ' ' . $oDiferenca->format('%H:%I'));
                $oDiferencaTotal->add($oIntervaloDiferencaEntrada);

                /**
                 * Verifica a se a tolerância configurada foi ultrapassada,
                 * caso sim significa que a diferença de falta da entrada
                 * deve ser adicionada como falta
                 */
                if ($oDiferencaTotal->diff($oToleranciaConfigurada)->invert) {
                    $this->oDiaFalta->add($oDiferenca);

                    $oDiferencaEntrada = \DateTime::createFromFormat('Y-m-d H:i', $sDataAtual . " " . $oIntervaloDiferencaEntrada->format("%H:%I"));

                    if ($oToleranciaPadrao->diff($oDiferencaEntrada)->invert || $oToleranciaPadrao->format('H:i') == $oDiferencaEntrada->format('H:i')) {
                        $this->oDiaFalta->add($oIntervaloDiferencaEntrada);
                    }
                } else {
                    /**
                     * Realizamos o ajuste de tolerância na saida
                     */
                    if ($oDiferenca->format('%H') > 0 || $iMinutosFalta > $oToleranciaPadrao->format('i')) {
                        $this->oDiaFalta->add($oDiferenca);
                    }
                }
            }

            /**
             * Não tem marcação no periodo, e periodo existe na jornada
             */
            if ($oMarcacao->getMarcacao() == null) {
                $horas        = $this->getDiaTrabalho()->getJornada()->getHoras();
                $tipoRegistro = $oHoraJornada->iTipoRegistro;
                if (empty($horas[$tipoRegistro - 1])) {
                    return;
                }

                if ($horas) {
                    $horaSaidaPadrao = $horas[$tipoRegistro - 1]->oHora;
                    $horaSaidaEntrada = $horas[$tipoRegistro - 2]->oHora;
                    $this->oDiaFalta->add($horaSaidaEntrada->diff($horaSaidaPadrao));
                }
            }
        }
    }
}
