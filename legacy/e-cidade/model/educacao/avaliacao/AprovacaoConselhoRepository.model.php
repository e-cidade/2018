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

/**
 * Classe repository para AprovacaoConselho
 *
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 * @subpackage avaliacao
 */
class AprovacaoConselhoRepository {

  /**
   * Array com as Aprovações de Conselho
   * @var array
   */
  private $aAprovacaoConselho = array();

  /**
   * Instância da classe
   * @var AprovacaoConselhoRepository
   */
  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna uma instância da classe
   * @return AprovacaoConselhoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AprovacaoConselhoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna AvaliacaoResultadoFinal de acordo com o código informado
   * @param  AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal
   * @return AprovacaoConselho
   */
  public static function getByAvaliacaoResultadoFinal( AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal ) {

    $iDiario = $oAvaliacaoResultadoFinal->getCodigoDiario();

    if (!array_key_exists($iDiario, AprovacaoConselhoRepository::getInstance()->aAprovacaoConselho)) {
      AprovacaoConselhoRepository::getInstance()->aAprovacaoConselho[$iDiario] = new AprovacaoConselho( $oAvaliacaoResultadoFinal );
    }

    return AprovacaoConselhoRepository::getInstance()->aAprovacaoConselho[$iDiario];
  }
}