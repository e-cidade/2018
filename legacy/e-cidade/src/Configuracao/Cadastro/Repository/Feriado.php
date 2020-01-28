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

namespace ECidade\Configuracao\Cadastro\Repository;

use ECidade\Configuracao\Cadastro\Collection\Feriado as FeriadoCollection;
use ECidade\Configuracao\Cadastro\Model\Feriado as FeriadoModel;

/**
 * Repository da classe Feriado
 * Class Feriado
 * @package ECidade\Configuracao\Cadastro\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Feriado {

  /**
   * @var \cl_feriadosgerais
   */
  private $oDao;

  /**
   * @var \ECidade\Configuracao\Cadastro\Collection\Feriado
   */
  private $oCollectionFeriados;

  /**
   * @var \ECidade\Configuracao\Cadastro\Model\Feriado
   */
  private $oFeriado;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var int
   */
  private $iLotacao;

  /**
   * Feriado constructor.
   * @param \Instituicao $oInstituicao
   * @param null|int $iLotacao
   */
  public function __construct(\Instituicao $oInstituicao, $iLotacao = null) {

    $this->oDao         = new \cl_rhcadcalend();
    $this->oInstituicao = $oInstituicao;
    $this->iLotacao     = $iLotacao;
  }

  /**
   * @return FeriadoModel
   */
  public function getFeriado() {
    return $this->oFeriado;
  }

  /**
   * Alimenta a variável com a coleção de Feriado
   * @throws \DBException
   */
  public function getCollectionFeriados() {

    $sCamposFeriados = "rh53_calend, rh53_descr, r62_data";
    $sWhereFeriados  = "rh53_instit = {$this->oInstituicao->getCodigo()}";

    if(!empty($this->iLotacao)) {
      $sWhereFeriados .= " AND rh64_lota = {$this->iLotacao}";
    }

    $sSqlFeriados = $this->oDao->sqlCalendarioLotacao(null, $sCamposFeriados, 'rh53_calend', $sWhereFeriados);
    $rsFeriados   = db_query($sSqlFeriados);

    if(!$rsFeriados) {
      throw new \DBException('Erro ao buscar os feriados.');
    }

    $this->oCollectionFeriados = FeriadoCollection::makeCollectionFromArray(\db_utils::getCollectionByRecord($rsFeriados));
  }

  /**
   * Retorna todos os feriados
   * @return FeriadoModel[]
   */
  public function getTodosFeriados() {

    $this->getCollectionFeriados();
    return $this->oCollectionFeriados->getFeriados();
  }

  /**
   * @param \DBDate $oData
   * @return FeriadoModel
   */
  public function getFeriadoNaData(\DBDate $oData) {

    $this->getCollectionFeriados();
    return $this->oCollectionFeriados->getFeriadoNaData($oData);
  }
}