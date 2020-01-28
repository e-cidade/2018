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
 * Classe para os termos da Educacao
 * @author  Fabio Esteves <fabio.esteves@dbseller.com.br>
 * @package educacao
 */
class DBEducacaoTermo {

  /**
   * Instancia de DBEducacaoTermo
   * @var DBEducacaoTermo
   */
  private static $oInstance;

  /**
   * Colecao de termos
   * @var array
   */
  private $aTermos = array();

  private function __construct() {

    $oDaoTermo    = db_utils::getDao('termoresultadofinal');
    $sSqlTermo    = $oDaoTermo->sql_query_file(null, "*", "ed110_sequencial");
    $rsTermo      = $oDaoTermo->sql_record($sSqlTermo);
    $iTotalTermo  = $oDaoTermo->numrows;

    if($iTotalTermo > 0) {

      for ($iContador = 0; $iContador < $iTotalTermo; $iContador++) {

        $oDadosTermo               = new stdClass();
        $oDadosTermo->iCodigo      = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_sequencial;
        $oDadosTermo->iEnsino      = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_ensino;
        $oDadosTermo->sAbreviatura = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_abreviatura;
        $oDadosTermo->sDescricao   = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_descricao;
        $oDadosTermo->sReferencia  = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_referencia;
        $oDadosTermo->sAno         = db_utils::fieldsMemory($rsTermo, $iContador)->ed110_ano;
        $this->aTermos[]           = $oDadosTermo;
        unset($oDadosTermo);
      }
    }
  }

  /**
   * Retorna a instancia da classe
   * @return DBEducacaoTermo
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new DBEducacaoTermo();
    }

    return self::$oInstance;
  }

  /**
   * Retorna a abreviatura e descricao de um termo de encerramento
   * @param integer $iCodigoEnsino
   * @param string $sReferencia
   * @return array
   */
  public static function getTermoEncerramento($iCodigoEnsino, $sReferencia, $sAno = null) {

    if (empty($sAno)) {
      $sAno = db_getsession("DB_anousu");
    }

    $aTermosEnsino = array();
    foreach (DBEducacaoTermo::getInstance()->aTermos as $oTermo) {

      if ($oTermo->iEnsino == $iCodigoEnsino && $oTermo->sReferencia == $sReferencia && $oTermo->sAno == $sAno) {
        $aTermosEnsino[] = $oTermo;
      }
    }

    if (count($aTermosEnsino) == 0) {
      $aTermosEnsino = DBEducacaoTermo::getInstance()->getTermosPadrao($sReferencia);
    }
    return $aTermosEnsino;
  }

  /**
   * Retorna os termos de encerramento de um ensino
   * @param integer $iCodigoEnsino
   * @return array
   */
  public static function getTermoEncerramentoDoEnsino($iCodigoEnsino, $sAno = null) {

    if (empty($sAno)) {
      $sAno = db_getsession("DB_anousu");
    }

    $aTermosEnsino = array();
    foreach (DBEducacaoTermo::getInstance()->aTermos as $oTermos) {

      if ($oTermos->iEnsino == $iCodigoEnsino && $oTermos->sAno == $sAno) {
        $aTermosEnsino[] = $oTermos;
      }
    }

    if (count($aTermosEnsino) == 0) {
      $aTermosEnsino = DBEducacaoTermo::getInstance()->getTermosPadrao(null);
    }

    return $aTermosEnsino;
  }

  /**
   * Retorna um termo padrao para o resultado final, A, P e R quando nao hover cadastro
   * @param string $sReferencia
   * @return multitype
   */
  public function getTermosPadrao($sReferencia = null) {

    $oAprovado               = new stdClass();
    $oAprovado->iCodigo      = "";
    $oAprovado->iEnsino      = "";
    $oAprovado->sAbreviatura = "Apr";
    $oAprovado->sDescricao   = "APROVADO";
    $oAprovado->sReferencia  = "A";
    $oAprovado->sAno         = db_getsession("DB_anousu");

    $oReprovado               = new stdClass();
    $oReprovado->iCodigo      = "";
    $oReprovado->iEnsino      = "";
    $oReprovado->sAbreviatura = "Rep";
    $oReprovado->sDescricao   = "REPROVADO";
    $oReprovado->sReferencia  = "R";
    $oReprovado->sAno         = db_getsession("DB_anousu");

    $oParcial               = new stdClass();
    $oParcial->iCodigo      = "";
    $oParcial->iEnsino      = "";
    $oParcial->sAbreviatura = "Par";
    $oParcial->sDescricao   = "APROVADO PARCIALMENTE";
    $oParcial->sReferencia  = "P";
    $oParcial->sAno         = db_getsession("DB_anousu");

    $aTermoPadrao = array();

    switch ($sReferencia) {

      case "A":

        $aTermoPadrao[] = $oAprovado;
        break;
      case "P":

        $aTermoPadrao[] = $oParcial;
        break;
      case "R":

        $aTermoPadrao[] = $oReprovado;
        break;
      default:

        $aTermoPadrao[] = $oAprovado;
        $aTermoPadrao[] = $oParcial;
        $aTermoPadrao[] = $oReprovado;
        break;

    }
    return $aTermoPadrao;
  }

  /**
   * Retorna um array de stdClass com os termos de encerramento para o calendário da turma
   * @param  Turma  $oTurma
   * @return stdClass[]
   */
  public static function getTermoEncerramentoDoEnsinoToJSON (Turma $oTurma) {

    $iCodigoEnsino  = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $iAnoCalendario = $oTurma->getCalendario()->getAnoExecucao();
    $aTermos        = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iCodigoEnsino, $iAnoCalendario);

    $aTermos = array();

    $iContadorTermos        = 1;
    $oStdTermo              = new stdClass();
    $oStdTermo->sReferencia = '';
    $oStdTermo->sDescricao  = '';
    $oStdTermo->sSigla      = '';
    if (count($aTermos) > 0) {

      $aTermos[0] = clone($oStdTermo);
      foreach ($aTermos as $oTermo) {

        $oAuxTermo              = clone($oStdTermo);
        $oAuxTermo->sReferencia = urlencode($oTermo->sReferencia);
        $oAuxTermo->sDescricao  = urlencode($oTermo->sDescricao);
        $oAuxTermo->sSigla      = urlencode($oTermo->sAbreviatura);

        $aTermos[$iContadorTermos] = $oAuxTermo;
        $iContadorTermos++;
      }
    } else {

      $aTermos[0] = clone($oStdTermo);
      $aTermos[1] = clone($oStdTermo);
      $aTermos[2] = clone($oStdTermo);
      $aTermos[1]->sReferencia = urlencode('A');
      $aTermos[1]->sDescricao  = urlencode('Aprovado');
      $aTermos[1]->sSigla      = urlencode('Apr');
      $aTermos[2]->sReferencia = urlencode('R');
      $aTermos[2]->sDescricao  = urlencode('Reprovado');
      $aTermos[2]->sSigla      = urlencode('Rep');
    }

    return $aTermos;
  }

  private function __clone() {}
}
?>