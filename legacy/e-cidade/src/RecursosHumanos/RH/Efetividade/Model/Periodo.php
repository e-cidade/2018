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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

/**
 * Class Periodo
 * @package ECidade\RecursosHumanos\RH\Efetividade\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Periodo {

  /**
   * @var int
   */
  private $iExercicio;

  /**
   * @var int
   */
  private $iCompetencia;

  /**
   * @var \DBDate
   */
  private $oDataInicio;

  /**
   * @var \DBDate
   */
  private $oDataFim;

  /**
   * @var int
   */
  private $iCodigoArquivo;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @return int
   */
  public function getExercicio() {
    return $this->iExercicio;
  }

  /**
   * @return int
   */
  public function getCompetencia() {
    return $this->iCompetencia;
  }

  /**
   * @return \DBDate
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * @return \DBDate
   */
  public function getDataFim() {
    return $this->oDataFim;
  }

  /**
   * Retorna o código do arquivo
   *
   * @return Integer
   */
  public function getCodigoArquivo() {
    return $this->iCodigoArquivo;
  }

  /**
   * @return \Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * @param int $iExercicio
   */
  public function setExercicio($iExercicio) {
    $this->iExercicio = $iExercicio;
  }

  /**
   * @param int $iCompetencia
   */
  public function setCompetencia($iCompetencia) {
    $this->iCompetencia = $iCompetencia;
  }

  /**
   * @param \DBDate $oDataInicio
   */
  public function setDataInicio(\DBDate $oDataInicio) {
    $this->oDataInicio = $oDataInicio;
  }

  /**
   * @param \DBDate $oDataFim
   */
  public function setDataFim(\DBDate $oDataFim) {
    $this->oDataFim = $oDataFim;
  }

  /**
   * Define o código do arquivo
   *
   * @param Integer
   */
  public function setCodigoArquivo($iCodigoArquivo) {
    $this->iCodigoArquivo = $iCodigoArquivo;
  }

  /**
   * @param \Instituicao $oInstituicao
   */
  public function setInstituicao(\Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }
}
