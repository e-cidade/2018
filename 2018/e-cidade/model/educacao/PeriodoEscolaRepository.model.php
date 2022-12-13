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
 * Classe repository para classes PeriodoEscola
 * @author  Andrio Costa - andrio.costa@dbseller.com.br
 * @package Educacao
 * @version $Revision: 1.1 $
 */
class PeriodoEscolaRepository {

  /**
   * Collection de PeriodoEscola
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   * @var PeriodoEscolaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna uma instancia do PeriodoEscola pelo Codigo
   * @param integer $iCodigo Codigo do PeriodoEscola
   * @return PeriodoEscola
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, PeriodoEscolaRepository::getInstance()->aItens)) {
      PeriodoEscolaRepository::getInstance()->aItens[$iCodigo] = new PeriodoEscola($iCodigo);
    }
    return PeriodoEscolaRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   * @return PeriodoEscolaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new PeriodoEscolaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de PeriodoEscola ao repositorio
   * @param PeriodoEscola $oPeriodoEscola Instancia de PeriodoEscola
   * @return boolean
   */
  public static function adicionarPeriodoEscola(PeriodoEscola $oPeriodoEscola) {

    if (!array_key_exists($oPeriodoEscola->getCodigo(), PeriodoEscolaRepository::getInstance()->aItens)) {
      PeriodoEscolaRepository::getInstance()->aItens[$oPeriodoEscola->getCodigo()] = $oPeriodoEscola;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   * @param PeriodoEscola $oPeriodoEscola
   * @return boolean
   */
  public static function remover(PeriodoEscola $oPeriodoEscola) {
     /**
      *
      */
    if (array_key_exists($oPeriodoEscola->getCodigo(), PeriodoEscolaRepository::getInstance()->aItens)) {
      unset(PeriodoEscolaRepository::getInstance()->aItens[$oPeriodoEscola->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   * @return integer;
   */
  public static function getTotalPeriodoEscola() {
    return count(PeriodoEscolaRepository::getInstance()->aItens);
  }

}