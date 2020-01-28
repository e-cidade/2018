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

require_once(modification("model/empenho/EmpenhoFinanceiro.model.php"));
/**
 * Model restos a pagar
 * @author  Bruno Silva      <bruno.silva@dbseller.com.br>
 * @author  Jeferson Belmiro <jeferon.belmiro@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.15 $
 */
class RestosAPagar extends EmpenhoFinanceiro {

  /**
   * Constantes com os tipos de Restos a Pagar.
   */
  const TIPO_RP_NAO_PROCESSADO = 1;
  const TIPO_RP_PROCESSADO     = 2;

  /**
   * Buscar o valor dos respostos a pagar de acordo com os parâmetros.
   * @param integer    $iTipo                 Tipo de restos a pagar, de acordo com as constantes.
   * @param integer    $iAno                  Ano dos restos a pagar.
   * @param integer    $iInstituicao          Instituição dos restos a pagar.
   * @param bool|false $lExerciciosAnteriores Se deve buscar o restos a pagar para os exercícios anteriores.
   *
   * @return float Valor total dos restos a pagar.
   * @throws Exception
   * @throws ParameterException
   */
  public static function getValor($iTipo, $iAno, $iInstituicao, $lExerciciosAnteriores = false) {

    if (!isset($iTipo) || !is_numeric($iTipo)) {
      throw new ParameterException("Tipo de Restos a Pagar não informado ou inválido.");
    }

    if (!isset($iAno) || !is_numeric($iAno)) {
      throw new ParameterException("Exercício não informado ou inválido.");
    }

    if (!isset($iInstituicao) || !is_numeric($iInstituicao)) {
      throw new ParameterException("Instituição não informada ou inválida.");
    }

    if (!isset($lExerciciosAnteriores) || !is_bool($lExerciciosAnteriores)) {
      $lExerciciosAnteriores = false;
    }

    switch ($iTipo) {

      case RestosAPagar::TIPO_RP_NAO_PROCESSADO:
        return RestosAPagar::getValorNaoProcessadoAno($iAno, $iInstituicao, $lExerciciosAnteriores);
        break;

      case RestosAPagar::TIPO_RP_PROCESSADO:
        return RestosAPagar::getValorProcessado($iAno, $iInstituicao, $lExerciciosAnteriores);
      break;

      default:
        throw new ParameterException("Tipo de Restos a Pagar inválido.");
        break;
    }
  }

  /**
   * @param $iTipo int - tipo de Restos a pagar (processado ou não processado)
   * @param $lExercicioAnterior bool
   *
   * @return int
   */
  private static function getDocumentoEstorno($iTipo, $lExercicioAnterior) {

    $iCodigoDocumento = null;
    switch ($iTipo) {

      case self::TIPO_RP_NAO_PROCESSADO:

        $iCodigoDocumento = 2006;
        if ($lExercicioAnterior) {
          $iCodigoDocumento = 2008;
        }
        break;

      case self::TIPO_RP_PROCESSADO:

        $iCodigoDocumento = 2010;
        if ($lExercicioAnterior) {
          $iCodigoDocumento = 2012;
        }
        break;
    }

    return $iCodigoDocumento;
  }

  /**
   * @param $iTipo int
   * @param $lExerciciosAnteriores boolean
   *
   * @return int|null
   */
  private static function getDocumentoInscricao($iTipo, $lExerciciosAnteriores) {

    $iCodigoDocumento = null;
    switch ($iTipo) {

      case self::TIPO_RP_NAO_PROCESSADO:

        $iCodigoDocumento = 2005;
        if ($lExerciciosAnteriores) {
          $iCodigoDocumento = 2007;
        }
        break;

      case self::TIPO_RP_PROCESSADO:

        $iCodigoDocumento = 2009;
        if ($lExerciciosAnteriores) {
          $iCodigoDocumento = 2011;
        }
        break;
    }

    return $iCodigoDocumento;

  }

  /**
   * @param            $iTipo int
   * @param bool|false $lExerciciosAnteriores
   * @param bool|false $lEstorno
   *
   * @return int|null
   */
  public static function getDocumento($iTipo, $lExerciciosAnteriores = false, $lEstorno = false) {

    if($lEstorno) {
      return self::getDocumentoEstorno($iTipo, $lExerciciosAnteriores);
    }
    return self::getDocumentoInscricao($iTipo, $lExerciciosAnteriores);
  }

  /**
   * Retorna os restos a pagar processados.
   * @param            $iAno
   * @param            $iInstituicao
   * @param bool|false $lExerciciosAnteriores
   *
   * @return float
   * @throws Exception
   */
  public function getValorProcessado($iAno, $iInstituicao, $lExerciciosAnteriores = false) {

    $oDaoEmpresto = new cl_empresto;

    $WhereEmpresto = " e60_anousu = " . ($iAno - 1);
    if ($lExerciciosAnteriores) {
      $WhereEmpresto = " e60_anousu < " . ($iAno - 1);
    }
    $WhereEmpresto .= " and e91_anousu = {$iAno} and e60_instit = {$iInstituicao}";

    $sCampos       = "coalesce(sum(e91_vlrliq - e91_vlrpag), 0) as valor";
    $sSqlEmpresto  = $oDaoEmpresto->sql_query_empenho(null, null, $sCampos, null, $WhereEmpresto);
    $rsSqlEmpresto = $oDaoEmpresto->sql_record($sSqlEmpresto);

    if ($rsSqlEmpresto == false || $oDaoEmpresto->numrows == 0) {
      throw new Exception('Erro técnico: erro ao buscar valor de restos a pagar.');
    }

    $nValor = db_utils::fieldsMemory($rsSqlEmpresto, 0)->valor;
    return (float) $nValor;
  }

  /**
   * Retorna valor acumulado nao processado do ano
   *
   * @param integer $iAno
   * @param integer $iInstituicao
   * @param boolean $lExerciciosAnteriores
   *
   * @return float
   * @throws Exception
   */
  public static function getValorNaoProcessadoAno($iAno, $iInstituicao, $lExerciciosAnteriores = false) {

    $oDaoEmpresto = new cl_empresto;

    $WhereEmpresto = " e60_anousu = " . ($iAno - 1);
    if ($lExerciciosAnteriores) {
      $WhereEmpresto = " e60_anousu < " . ($iAno - 1);
    }
    $WhereEmpresto .= " and e91_anousu = {$iAno}  and e60_instit = {$iInstituicao}";

    $sCampos       = "coalesce(sum(e91_vlremp - e91_vlranu - e91_vlrliq), 0) as valor";
    $sSqlEmpresto  = $oDaoEmpresto->sql_query_empenho(null, null, $sCampos, null, $WhereEmpresto);
    $rsSqlEmpresto = $oDaoEmpresto->sql_record($sSqlEmpresto);

    if ($rsSqlEmpresto == false || $oDaoEmpresto->numrows == 0) {
      throw new Exception('Erro técnico: erro ao buscar valor de restos a pagar.');
    }

    $nValor = db_utils::fieldsMemory($rsSqlEmpresto, 0)->valor;
    return (float) $nValor;
  }

}