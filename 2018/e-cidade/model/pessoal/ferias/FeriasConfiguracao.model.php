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
 * Class FeriasConfiguracao
 */
class FeriasConfiguracao {


  protected static $instance;

  protected $assentamentoFerias;

  protected $assentamentoAbono;

  protected $ultimoPeriodoAquisitivo = false;


  private function __construct() {

    $oDaoConfiguracao = new cl_rhferiasconfiguracao();
    $sSqlConfiguracao = $oDaoConfiguracao->sql_query_file();
    $rsConfiguracao = db_query($sSqlConfiguracao);
    $iTotalLinhas   = pg_num_rows($rsConfiguracao);
    if (!$rsConfiguracao) {
      throw new DBException("Erro ao pesquisar os dados de configuração das férias");
    }

    if ($iTotalLinhas > 0) {

      $oDados  = db_utils::fieldsMemory($rsConfiguracao, 0);
      $this->setAssentamentoAbono(TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh168_tipoassentamentoabono));
      $this->setAssentamentoFerias(TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh168_tipoassentamentoferias));
      $this->ultimoPeriodoAquisitivo = $oDados->rh168_ultimoperiodoaquisitivo == 't' ? true : false;
    }
  }

  /**
   * @return \FeriasConfiguracao
   */
  public static function getInstance() {

    if (empty(FeriasConfiguracao::$instance)) {
      self::$instance = new FeriasConfiguracao();
    }
    return self::$instance;
  }

  /**
   * @return mixed
   */
  public static function getAssentamentoFerias() {

    return self::getInstance()->assentamentoFerias;
  }

  /**
   * @param mixed $assentamentoFerias
   */
  public function setAssentamentoFerias($assentamentoFerias) {

    $this->assentamentoFerias = $assentamentoFerias;
  }

  /**
   * @return mixed
   */
  public static function getAssentamentoAbono() {

    return self::getInstance()->assentamentoAbono;
  }

  /**
   * @param mixed $assentamentoAbono
   */
  public function setAssentamentoAbono($assentamentoAbono) {
    $this->assentamentoAbono = $assentamentoAbono;
  }

  /**
   * @return boolean
   */
  public static function isUltimoPeriodoAquisitivo() {
    return self::getInstance()->ultimoPeriodoAquisitivo;
  }



}