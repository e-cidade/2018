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

class LocalizadorGastos {

  /**
   * @var integer
   */
  private $iSequencial;

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sDescricao;

  public function __construct($iSequencial = null) {

    if (!empty($iSequencial)) {

      $oDaoLocalizador = new cl_ppasubtitulolocalizadorgasto();
      $sSqlLocalizador = $oDaoLocalizador->sql_query_file($iSequencial);
      $rsLocalizador   = $oDaoLocalizador->sql_record( $sSqlLocalizador );

      if (empty($rsLocalizador) || $oDaoLocalizador->numrows == 0) {
        throw new Exception("Localizador de gastos não encontrado.");
      }

      $oDados = db_utils::fieldsMemory($rsLocalizador, 0);
      $this->iSequencial = $iSequencial;
      $this->iCodigo     = $oDados->o11_codigo;
      $this->sDescricao  = $oDados->o11_descricao;
    }
  }

  /**
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * @param integer $iSequencial
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
}