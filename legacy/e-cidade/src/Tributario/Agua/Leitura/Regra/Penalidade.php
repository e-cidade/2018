<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
namespace ECidade\Tributario\Agua\Leitura\Regra;

use ParameterException;
use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Leitura\ResumoMensal;

class Penalidade implements RegraInterface {

  private $aLeituras;

  private $aLeiturasMensuradas;

  private $iMetragemCubicaEfetivamenteConsumida;

  private $iMetragemCubicaCobrada;

  public function __construct(array $aLeituras) {

    $this->aLeituras = $aLeituras;
  }


  public function calcular() {

    if (!$this->aLeituras) {
      throw new ParameterException('Nenhuma leitura informada para cálculo de penalidade.');
    }

    $aRegrasLeiturasReais = array(
      Situacao::REGRA_NORMAL,
      Situacao::REGRA_MEDIA_PENALIDADE
    );


    $oUltimaLeitura = array_shift($this->aLeituras);

    foreach ($this->aLeituras as $oLeitura) {

      if (!$oLeitura instanceof ResumoMensal) {
        throw new ParameterException('Lista de Leituras é inválida.');
      }

      $iRegraLeitura = $oLeitura->getRegra();

      if (!in_array($iRegraLeitura, $aRegrasLeiturasReais)) {

        /* Separa os consumos por media */
        $this->addLeituraMensurada($oLeitura);
        continue;

      } else if (in_array($iRegraLeitura, $aRegrasLeiturasReais)) {

        $oPrimeiraLeitura = $oLeitura;
        break;
      }
    }

    $this->metragemCubicaEfetivamenteConsumida($oPrimeiraLeitura, $oUltimaLeitura);

    $this->metragemCubicaCobrada();

    $iMetragemCubicaAberto = $this->getMetragemCubicaAberto();

    if ($iMetragemCubicaAberto > 0) {
      return $iMetragemCubicaAberto;
    }

    $iMetragemCubicaCobrar = $this->getMetragemCubicaCobrar();

    return $iMetragemCubicaCobrar;
  }


  /**
   * Obtem a metragem que foi consumida efetivamente
   * @param  ResumoMensal $oPrimeiraLeitura Leitura mais antiga
   * @param  ResumoMensal $oUltimaLeitura   Ultima Leitura/ Atual
   */
  private function metragemCubicaEfetivamenteConsumida($oPrimeiraLeitura, $oUltimaLeitura) {

    $iMetragemCubicaEfetivamenteConsumida = ($oUltimaLeitura->getLeitura() - $oPrimeiraLeitura->getLeitura());

    $this->iMetragemCubicaEfetivamenteConsumida = $iMetragemCubicaEfetivamenteConsumida;
  }

  /**
   * Adiciona os consumos obtidos por media
   * @param ResumoMensal $oLeitura
   */
  private function addLeituraMensurada($oLeitura) {
    $this->aLeiturasMensuradas[] = $oLeitura->getConsumo();
  }

  /**
   * Metragem cubica ja cobrada - MJC
   * Soma das medias ja cobradas entre as leituras normais
   */
  private function metragemCubicaCobrada() {

    $iConsumoCobrado = 0;
    foreach ($this->aLeiturasMensuradas as $iConsumo) {
      $iConsumoCobrado += $iConsumo;
    }

    $this->iMetragemCubicaCobrada = $iConsumoCobrado;
  }

  /**
   * Retorna a diferenca entre a metragem consumida e a metragem ja cobrada
   * @return integer $iMetragemCubicaAberto
   */
  private function getMetragemCubicaAberto() {

    $iMetragemCubicaAberto = ($this->iMetragemCubicaEfetivamenteConsumida - $this->iMetragemCubicaCobrada);

    return $iMetragemCubicaAberto;
  }

  /**
   * Retorna a metragem a cobrar baseada na formula de penalidade
   * @return integer $iMetragemCubicaCobrar
   */
  private function getMetragemCubicaCobrar() {

    $iMetragemCubicaCobrar = ($this->iMetragemCubicaCobrada / count($this->aLeiturasMensuradas));

    return (int) round($iMetragemCubicaCobrar);
  }
}
