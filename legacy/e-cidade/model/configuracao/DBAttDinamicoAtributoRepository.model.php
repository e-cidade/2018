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

class DBAttDinamicoAtributoRepository {

  /**
   * Collection de DBAttDinamicoAtributo
   * @var DBAttDinamicoAtributo[]
   */
  private $aDBAttDinamicoAtributo = array();

  /**
   * Instancia da classe
   * @var DBAttDinamicoAtributoRepository
   */
  private static $oInstance;

  /**
   * Construtor privado para não ser possível instanciar a classe
   */
  private function __construct() {}

  private function __clone() {}

  /**
   * Retorno uma instancia da DBAttDinamicoAtributo pelo Codigo
   *
   * @param integer $iCodigo
   * @return DBAttDinamicoAtributo
   */
  public static function getDBAttDinamicoAtributoPorCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, DBAttDinamicoAtributoRepository::getInstance()->aDBAttDinamicoAtributo)) {
      DBAttDinamicoAtributoRepository::getInstance()->aDBAttDinamicoAtributo[$iCodigo] = new DBAttDinamicoAtributo($iCodigo);
    }

    return DBAttDinamicoAtributoRepository::getInstance()->aDBAttDinamicoAtributo[$iCodigo];
  }

  /**
   * @return DBAttDinamicoAtributoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new DBAttDinamicoAtributoRepository();
    }

    return self::$oInstance;
  }
}