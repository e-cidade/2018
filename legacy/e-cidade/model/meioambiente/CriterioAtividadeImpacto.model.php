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
 * Procedimentos que definem o Critério da Atividade
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class CriterioAtividadeImpacto {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Descrição do Critério da Atividade de Impacto
   * @var string
   */
  private $sDescricao = null;


  public function __construct( $iSequencial = null, $sDescricao = null ) {

    $oDaoCriterioAtividadeImpacto = db_utils::getDao("criterioatividadeimpacto");
    $rsCriterioAtividadeImpacto   = null;

    if (!is_null($iSequencial)) {

      $sSql          = $oDaoCriterioAtividadeImpacto->sql_query($iSequencial);
      $rsTipoLicenca = $oDaoCriterioAtividadeImpacto->sql_record($sSql);
    }

    if (!empty($sDescricao)) {

      $sSql                       = $oDaoCriterioAtividadeImpacto->sql_query(null, "*", null, " am01_descricao = {$sDescricao} ");
      $rsCriterioAtividadeImpacto = $oDaoCriterioAtividadeImpacto->sql_record($sSql);
    }

    if (!is_null($rsCriterioAtividadeImpacto)) {

      $oDados = db_utils::fieldsMemory($rsCriterioAtividadeImpacto, 0);

      $this->iSequencial = $oDados->am01_sequencial;
      $this->sDescricao  = $oDados->am01_descricao;
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
}