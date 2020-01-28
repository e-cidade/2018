<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

namespace ECidade\Configuracao\Cadastro\Collection;

use \ECidade\Configuracao\Cadastro\Model\Feriado as FeriadoModel;

/**
 * Classe que representa a coleção de Feriado
 * Class Feriado
 * @package ECidade\Configuracao\Cadastro\Collection
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Feriado {

  /**
   * @var \ECidade\Configuracao\Cadastro\Model\Feriado[]
   */
  private $aFeriados = array();

  /**
   * Monta a collection de Feriado. Recebe um array dos dados com os campos baseados na tabela feriadosgerais
   * @param array $aFeriados
   * @return Feriado
   */
  public static function makeCollectionFromArray(array $aFeriados) {

    $oCollectionFeriado = new Feriado();

    foreach($aFeriados as $oDadosFeriado) {

      $oFeriadoModel = new FeriadoModel();
      $oFeriadoModel->setCodigo($oDadosFeriado->rh53_calend);
      $oFeriadoModel->setDescricao($oDadosFeriado->rh53_descr);
      $oFeriadoModel->setData(new \DBDate($oDadosFeriado->r62_data));

      $oCollectionFeriado->add($oFeriadoModel);
    }

    return $oCollectionFeriado;
  }

  /**
   * @param FeriadoModel $oFeriadoModel
   */
  private function add(FeriadoModel $oFeriadoModel) {
    $this->aFeriados[] = $oFeriadoModel;
  }

  /**
   * @return FeriadoModel[]
   */
  public function getFeriados() {
    return $this->aFeriados;
  }

  /**
   * @param \DBDate $oData
   * @return FeriadoModel
   */
  public function getFeriadoNaData(\DBDate $oData) {

    foreach($this->aFeriados as $oFeriado) {

      if(\DBDate::getIntervaloEntreDatas($oFeriado->getData(), $oData)->days == 0) {
        return $oFeriado;
      }
    }
  }
}