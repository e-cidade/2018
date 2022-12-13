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
 * Class MovimentacaoFichaAtendimentoRepository
 */
class MovimentacaoFichaAtendimentoRepository {

  /**
   * @var array
   */
  private $aMovimentacaoFichaAtendimento = array();

  /**
   * @var MovimentacaoFichaAtendimentoRepository
   */
  private static $oInstancia;


  /**
   * @return MovimentacaoFichaAtendimentoRepository
   */
  private static function getInstancia() {

    if (self::$oInstancia == null) {
      self::$oInstancia = new MovimentacaoFichaAtendimentoRepository();
    }
    return self::$oInstancia;
  }

  /**
   * @param $iCodigo
   * @return MovimentacaoFichaAtendimento
   */
  public static function getPorCodigo($iCodigo) {

    if ( !array_key_exists($iCodigo, MovimentacaoFichaAtendimentoRepository::getInstancia()->aMovimentacaoFichaAtendimento)) {
      MovimentacaoFichaAtendimentoRepository::getInstancia()->aMovimentacaoFichaAtendimento[$iCodigo] = new MovimentacaoFichaAtendimento($iCodigo);
    }
    return MovimentacaoFichaAtendimentoRepository::getInstancia()->aMovimentacaoFichaAtendimento[$iCodigo];
  }


  /**
   * Busca a ultima movimentação da ficha de atendimento
   * @param $iCodigoFichaAtendimento código da FAA
   * @return MovimentacaoFichaAtendimento | null
   */
  public static function getUltimaMovimentacaoFAA( $iCodigoFichaAtendimento ) {

    $oDaoMovimentacao = new cl_movimentacaoprontuario();
    $sWhere           = " sd102_prontuarios = {$iCodigoFichaAtendimento} ";
    $sOrder           = " 1 desc  limit 1 ";
    $sSqlMovimentacao = $oDaoMovimentacao->sql_query_file(null, "sd102_codigo", $sOrder, $sWhere);
    $rsMovimentacao   = db_query($sSqlMovimentacao);

    $oErro = new stdClass();
    if ( !$rsMovimentacao ) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M(MovimentacaoFichaAtendimento::MENSAGEM."erro_buscar_movimentacao", $oErro) );
    }
    if ( pg_num_rows($rsMovimentacao) > 0 ) {
      return MovimentacaoFichaAtendimentoRepository::getPorCodigo( db_utils::fieldsMemory($rsMovimentacao, 0)->sd102_codigo);
    }
    return null;
  }

  /**
   * Busca todas as movimentações realizadas na ficha de atendimento
   * @param  integer $iProntuario Código da FAA
   * @return MovimentacaoFichaAtendimento[]
   */
  public static function getMovimentacoesPorProntuario( $iProntuario ) {

    $oDaoMovimentacao    = new cl_movimentacaoprontuario();
    $sWhereMovimentacoes = " sd102_prontuarios = {$iProntuario} ";
    $sOrdemMovimentacoes = " sd102_codigo ";
    $sSqlMovimentacoes   = $oDaoMovimentacao->sql_query_file( null, "sd102_codigo", $sOrdemMovimentacoes, $sWhereMovimentacoes );
    $rsMovimentacoes     = db_query( $sSqlMovimentacoes );

    if ( !$rsMovimentacoes ) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M(MovimentacaoFichaAtendimento::MENSAGEM."erro_buscar_movimentacao", $oErro) );
    }

    $iLinhas        = pg_num_rows($rsMovimentacoes);
    $aMovimentacoes = array();

    if ( $iLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {
        $aMovimentacoes[] = MovimentacaoFichaAtendimentoRepository::getPorCodigo( db_utils::fieldsMemory($rsMovimentacoes, $iContador)->sd102_codigo);
      }
    }

    return $aMovimentacoes;
  }

  /**
   * Impossibilita instancia
   */
  private function __construct() {}
  private function __clone() {}
}
?>
