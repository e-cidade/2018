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

use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPontoSaida;

/**
 * Classe responsável pelo cálculo de horas trabalhadas de um servidor em um dia de trabalho
 * Class Trabalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Trabalho extends BaseHora implements Horas {

  public function __construct(DiaTrabalho $oDiaTrabalho) {

    $this->setDiaTrabalho($oDiaTrabalho);
    $this->setTipoHora(BaseHora::HORAS_TRABALHO);
    parent::__construct();
  }

  /**
   * @return \DateTime
   */
  public function calcular() {

    $oDiaTrabalhado = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 00:00');
    $oMarcacoes     = $this->getMarcacoesReais();

    if($oMarcacoes->getMarcacoes() == null) {
      return $oDiaTrabalhado;
    }

    $aMarcacoesCalcular = $oMarcacoes->getMarcacoes();
    $iTotalMarcacoes    = count($aMarcacoesCalcular);

    /**
     * Caso tenha um número impar de marcações,
     * é preciso saber se há 1 ou 3 marcações,
     * se há apenas 1 não há como calcular, retorna zero,
     * se houver 3 excluo a última
     */
    if($iTotalMarcacoes % 2 != 0) {

      switch ($iTotalMarcacoes) {
        case 1:
          return $oDiaTrabalhado;
          break;

        default:
          array_pop($aMarcacoesCalcular);
          break;
      }
    }

    foreach ($aMarcacoesCalcular as $oMarcacao) {

      if($oMarcacao->getJustificativa() !== null) {

        $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

        if(isset($aHorasJornada[$oMarcacao->getTipo()-1])) {
          $oMarcacao->setMarcacao(clone $aHorasJornada[$oMarcacao->getTipo()-1]->oHora);
        }
      }

      if($oMarcacao instanceof MarcacaoPontoSaida) {

        $oMarcacaoEntrada = $oMarcacao->getMarcacaoEntrada();

        if($oMarcacao->getJustificativa() !== null) {

          $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

          if(isset($aHorasJornada[$oMarcacao->getTipo()-2])) {
            $oMarcacao->setMarcacaoEntrada(clone $aHorasJornada[$oMarcacao->getTipo()-2]->oHora);
          }
        }

        if($oMarcacao->getHorarioTrabalhado() != null) {

          $oIntervaloTrabalhado = $oMarcacao->getHorarioTrabalhado();
          $oDiaTrabalhado->add($oIntervaloTrabalhado);
        }
      }
    }

    return $oDiaTrabalhado;
  }

  /**
   * @param MarcacaoPontoSaida $oMarcacao
   * @return \DateTime
   */
  private function getHorasJornadaPeriodo(MarcacaoPontoSaida $oMarcacao) {

    $oHoraEntrada  = null;
    $oHoraSaida    = null;
    $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

    foreach($aHorasJornada as $iIndice => $oHoraJornada) {

      if($oHoraJornada->iTipoRegistro == $oMarcacao->getTipo()) {

        $oHoraEntrada = $aHorasJornada[$iIndice - 1];
        $oHoraSaida   = $aHorasJornada[$iIndice];
      }
    }

    if(is_null($oHoraEntrada) || is_null($oHoraSaida)) {
      return new \DateTime('00:00');
    }

    return $oHoraEntrada->oHora->diff($oHoraSaida->oHora);
  }
}
