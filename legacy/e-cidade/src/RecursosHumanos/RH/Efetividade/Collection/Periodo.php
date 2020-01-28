<?php
/**
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

namespace ECidade\RecursosHumanos\RH\Efetividade\Collection;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo as PeriodoModel;

/**
 * Class Periodo
 * @package ECidade\RecursosHumanos\RH\Efetividade\Collection
 */
class Periodo {

  /**
   * @var PeriodoModel[]
   */
  private $aPeriodos = array();

  /**
   * @param array $aPeriodos
   * @return Periodo
   * @throws \BusinessException
   */
  public static function makeCollectionFromArray(array $aPeriodos) {

    $oCollection = new Periodo();

    foreach($aPeriodos as $oDadosPeriodo) {

      $oDadosPeriodo->rh186_datainicioefetividade     = trim($oDadosPeriodo->rh186_datainicioefetividade);
      $oDadosPeriodo->rh186_datafechamentoefetividade = trim($oDadosPeriodo->rh186_datafechamentoefetividade);

      if(empty($oDadosPeriodo->rh186_datainicioefetividade) || empty($oDadosPeriodo->rh186_datafechamentoefetividade)) {

        $sMensagem  = "Período de efetividade não configurado para o Exercício/Competência {$oDadosPeriodo->rh186_exercicio}";
        $sMensagem .= "/{$oDadosPeriodo->rh186_competencia}. Para configurá-lo, acesse:\n";
        $sMensagem .= " - RH > Procedimentos > Efetividade > Parâmetros > Períodos de Efetividade";

        throw new \BusinessException($sMensagem);
      }

      $oPeriodoModel = new PeriodoModel();
      $oPeriodoModel->setExercicio($oDadosPeriodo->rh186_exercicio);
      $oPeriodoModel->setCompetencia($oDadosPeriodo->rh186_competencia);
      $oPeriodoModel->setDataInicio(new \DBDate($oDadosPeriodo->rh186_datainicioefetividade));
      $oPeriodoModel->setDataFim(new \DBDate($oDadosPeriodo->rh186_datafechamentoefetividade));
      $oPeriodoModel->setInstituicao(\InstituicaoRepository::getInstituicaoByCodigo($oDadosPeriodo->rh186_instituicao));

      $oCollection->add($oPeriodoModel);
    }

    return $oCollection;
  }

  /**
   * @param PeriodoModel $oPeriodoModel
   */
  private function add(PeriodoModel $oPeriodoModel) {
    $this->aPeriodos[] = $oPeriodoModel;
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo[]
   */
  public function getPeriodos() {
    return $this->aPeriodos;
  }

  /**
   * @return PeriodoModel
   */
  public function getPrimeiroPeriodo() {
    return $this->aPeriodos[0];
  }

  /**
   * @return PeriodoModel
   */
  public function getUltimoPeriodo() {
    return end($this->aPeriodos);
  }
}