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
 * Classe repository para classe Dotacao
 */
class DotacaoRepository {

  /**
   * Collection de Dotacoes
   * @var Dotacao[]
   */
  private $aDotacao = array();

  /**
   * Instancia da classe
   * @var DotacaoRepository
   */
  private static $oInstance;

  /**
   * Construtor privado para não ser possível instanciar a classe
   */
  private function __construct() {}

  private function __clone() {}

  /**
   * Retorno uma instancia da Dotacao pelo Codigo e Ano
   *
   * @param integer $iCodigo
   * @param integer $iAno
   * @return Dotacao
   */
  public static function getDotacaoPorCodigoAno($iCodigo, $iAno) {

    if (!array_key_exists("{$iCodigo}, {$iAno}", DotacaoRepository::getInstance()->aDotacao)) {
      DotacaoRepository::getInstance()->aDotacao["{$iCodigo}, {$iAno}"] = new Dotacao($iCodigo, $iAno);
    }

    return DotacaoRepository::getInstance()->aDotacao["{$iCodigo}, {$iAno}"];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return DotacaoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new DotacaoRepository();
    }

    return self::$oInstance;
  }
}