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
 * Classe repository para o Abono de Faltas
 */

define("URL_MENSAGEM_ABONOFALTA", "educacao.escola.AbonoFalta.");

/**
 * Cole��o de AbonoFalta
 * @package educacao
 * @author  Gilnei Freitas <gilnei@dbseller.com.br>
 * @author  Andrio Costa   <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.2 $
 *
 */
class AbonoFaltaRepository {

  /**
   * Array com os Abonos de Faltas
   * @var array
   */
  private $aAbonoFalta = array();

  /**
   * Inst�ncia da classe
   * @var AbonoFaltaRepository
   */
  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna uma inst�ncia da classe
   * @return AbonoFaltaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AbonoFaltaRepository();
    }
    return self::$oInstance;
  }

  public static function getByCodigo( $iCodigo ) {

    if ( !array_key_exists($iCodigo, self::getInstance()->aAbonoFalta) ) {
      self::getInstance()->aAbonoFalta[$iCodigo] = new AbonoFalta($iCodigo);
    }
    return self::getInstance()->aAbonoFalta[$iCodigo];
  }
}