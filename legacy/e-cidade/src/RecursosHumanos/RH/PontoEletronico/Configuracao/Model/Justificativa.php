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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model;

/**
 * Classe que representa a entidade Justificativa
 * Class Justificativa
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Justificativa {

  const TOTAL   = 'T';
  const PARCIAL = 'P';

  /**
   * Código sequencial da justificativa
   * @var int
   */
  private $iCodigo;

  /**
   * Descrição da justificativa
   * @var string
   */
  private $sDescricao;

  /**
   * Abreviação(sigla) da justificativa
   * @var string
   */
  private $sAbreviacao;

  /**
   * Flag para identificar que se a justificativa 
   * abona as horas de falta ou não
   * @var boolean
   */
  private $lAbono = true;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @return string
   */
  public function getAbreviacao() {
    return $this->sAbreviacao;
  }

  /**
   * Informa se é um abono ou não
   * @return boolean
   */
  public function isAbono() {
    return $this->lAbono;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @param string $sAbreviacao
   */
  public function setAbreviacao($sAbreviacao) {
    $this->sAbreviacao = $sAbreviacao;
  }

  /**
   * @param boolean $lAbono
   */
  public function setAbono($lAbono) {
    $this->lAbono = $lAbono;
  }
}
