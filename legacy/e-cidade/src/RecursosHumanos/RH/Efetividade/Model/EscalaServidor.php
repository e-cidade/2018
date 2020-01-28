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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaTrabalho;

/**
 * Classe referente a regra de escala por servidor
 *
 * Class EscalaServidor
 * @package ECidade\RecursosHumanos\RH\Efetividade\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class EscalaServidor {

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * Instância de Servidor
   * @var \Servidor
   */
  private $oServidor;

  /**
   * Instância de EscalaTrabalho
   * @var EscalaTrabalho
   */
  private $oEscalaTrabalho;

  /**
   * Data de início do servidor na escala
   * @var \DBDate
   */
  private $oDataEscala;

  /**
   * @var null|EscalaServidor
   */
  private $oEscalaPosterior = null;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o servidor
   * @return \Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * Retorna a escala de trabalho
   * @return EscalaTrabalho
   */
  public function getEscalaTrabalho() {
    return $this->oEscalaTrabalho;
  }

  /**
   * Retorna da data de início do servidor na escala
   * @return \DBDate
   */
  public function getDataEscala() {
    return $this->oDataEscala;
  }

  /**
   * @return null|EscalaServidor
   */
  public function getEscalaPosterior() {
    return $this->oEscalaPosterior;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param \Servidor $oServidor
   */
  public function setServidor(\Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * @param EscalaTrabalho $oEscalaTrabalho
   */
  public function setEscalaTrabalho(EscalaTrabalho $oEscalaTrabalho) {
    $this->oEscalaTrabalho = $oEscalaTrabalho;
  }

  /**
   * @param \DBDate $oDataEscala
   */
  public function setDataEscala(\DBDate $oDataEscala) {
    $this->oDataEscala = $oDataEscala;
  }

  /**
   * @param EscalaServidor $oEscalaPosterior
   */
  public function setEscalaPosterior(EscalaServidor $oEscalaPosterior) {
    $this->oEscalaPosterior = $oEscalaPosterior;
  }
}