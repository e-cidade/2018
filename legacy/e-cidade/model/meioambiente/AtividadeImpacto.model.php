<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

/**
 * Procedimentos que definem a Atividade
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class AtividadeImpacto {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Descrição da Atividade
   * @var string
   */
  private $sDescricao = null;

  /**
   * Potencial poluidor da Atividade
   * @var string
   */
  private $sPotencialPoluidor = null;

  /**
   * Ramo da Atividade
   * @var string
   */
  private $sRamo = null;

  /**
   * Descrição da Atividade
   * @var CriterioAtividadeImpacto
   */
  private $oCriterioAtividadeImpacto = null;


  public function __construct( $iSequencial = null ) {

    $oDaoAtividadeImpacto = new cl_atividadeimpacto;
    $rsAtividadeImpacto   = null;

    if ( !empty($iSequencial) ) {

      $sSql               = $oDaoAtividadeImpacto->sql_query($iSequencial);
      $rsAtividadeImpacto = $oDaoAtividadeImpacto->sql_record($sSql);
    }

    if ( !empty($rsAtividadeImpacto) ) {

      $oDados = db_utils::fieldsMemory($rsAtividadeImpacto, 0);

      $this->iSequencial               = $oDados->am03_sequencial;
      $this->sDescricao                = $oDados->am03_descricao;
      $this->sRamo                     = $oDados->am03_ramo;
      $this->sPotencialPoluidor        = $oDados->am03_potencialpoluidor;
      $this->oCriterioAtividadeImpacto = new CriterioAtividadeImpacto($oDados->am03_criterioatividadeimpacto);
    }
  }

  public function getSequencial() {
    return $this->iSequencial;
  }

  public function getDescricao() {
    return $this->sDescricao;
  }

  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  public function getRamo() {
    return $this->sRamo;
  }

  public function setRamo($sRamo) {
    $this->sRamo = $sRamo;
  }

  public function getPotencialPoluidor() {
    return $this->sPotencialPoluidor;
  }

  public function setPotencialPoluidor($sPotencialPoluidor) {
    $this->sPotencialPoluidor = $sPotencialPoluidor;
  }

  public function getCriterioAtividadeImpacto() {
    return $this->oCriterioAtividadeImpacto;
  }

  public function setCriterioAtividadeImpacto(CriterioAtividadeImpacto $oCriterioAtividadeImpacto) {
    $this->oCriterioAtividadeImpacto = $oCriterioAtividadeImpacto;
  }

}