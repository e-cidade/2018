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
define( 'URL_MSG_AVALIACAOALTERNATIVA', 'educacao.escola.AvaliacaoAlternativa.' );

/**
 * Representa uma avaliação alternativa para um Resultado.
 * Só é valido para Resultados onde a forma de obtenção for igual a SOMA
 *
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.4 $
 */
class AvaliacaoAlternativa {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * Resultado a qual a regra pertence
   * @var ResultadoAvaliacao
   */
  private $oResultadoAvaliacao;

  /**
   * Ordem da alternativa
   * @var integer
   */
  private $iAlternativa;

  /**
   * Como a Avaliacao Alternativa esta configurada para cada período de avaliação
   * @todo  documentar tag @var
   * @var array
   */
  private $aConfiguracao = array();

  function __construct( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return $this;
    }

    $oDaoAlternativa = new cl_procavalalternativa();
    $sSqlAlternativa = $oDaoAlternativa->sql_query_file($iCodigo);
    $rsAlternativa   = db_query($sSqlAlternativa);

    $oMsgErro = new stdClass();
    if ( !$rsAlternativa ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(URL_MSG_AVALIACAOALTERNATIVA . "erro_executar_query", $oMsgErro) );
    }

    if ( pg_num_rows($rsAlternativa) == 0 ) {

      $oMsgErro->iCodigo = $iCodigo;
      throw new BusinessException(_M(URL_MSG_AVALIACAOALTERNATIVA . "sem_registro_para_codigo", $oMsgErro) );
    }

    $oDados = db_utils::fieldsMemory($rsAlternativa, 0);

    $this->iCodigo      = $oDados->ed281_i_codigo;
    $this->iAlternativa = $oDados->ed281_i_alternativa;
    $this->oResultado   = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDados->ed281_i_procresultado);
  }

  /**
   * Getter código da avaliacao alternativa
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Setter ordem da alternativa
   * @param integer
   */
  public function setAlternativa($iAlternativa) {
    $this->iAlternativa = $iAlternativa;
  }

  /**
   * Getter ordem da alternativa
   * @return integer
   */
  public function getAlternativa() {
    return $this->iAlternativa;
  }

  /**
   * Setter ResultadoAvaliacao
   * @param ResultadoAvaliacao
   */
  public function setResultadoAvaliacao( ResultadoAvaliacao $oResultadoAvaliacao ) {
    $this->oResultadoAvaliacao = $oResultadoAvaliacao;
  }

  /**
   * Getter ResultadoAvaliacao
   * @return ResultadoAvaliacao
   */
  public function getResultadoAvaliacao() {
    return $this->oResultadoAvaliacao;
  }

  /**
   * Retorna as regras configuradas da avaliação alternativa para cada período de avaliacao
   * @return array
   * @throws DBException
   */
  public function getConfiguracao() {

    if ( count($this->aConfiguracao) == 0 ) {

      $sCampos  = " ed41_i_codigo, ed41_i_sequencia, trim(ed09_c_descr) as ed09_c_descr, trim(ed37_c_descr) as ed37_c_descr, ";
      $sCampos .= " ed37_i_menorvalor, ed37_i_maiorvalor, ed37_i_variacao, ed37_c_minimoaprov ";

      $sWhere     = " ed282_i_procavalalternativa = {$this->iCodigo} ";
      $oDaoRegras = new cl_procavalalternativaregra();
      $sSqlRegras = $oDaoRegras->sql_query(null, $sCampos, " ed41_i_sequencia ", $sWhere);
      $rsRegra    = db_query($sSqlRegras);

      $oMsgErro = new stdClass();
      if ( !$rsRegra ) {

        $oMsgErro->sErro = pg_last_error();
        throw new DBException( _M(URL_MSG_AVALIACAOALTERNATIVA . "erro_buscar_configuracao", $oMsgErro) );
      }

      $iLinhas = pg_num_rows($rsRegra);
      for ( $i = 0; $i < $iLinhas; $i++ ) {

        $oDados = db_utils::fieldsMemory($rsRegra, $i);

        $oConfiguracao = new stdClass();

        $oConfiguracao->iCodigoPeriodo   = $oDados->ed41_i_codigo;
        $oConfiguracao->iOrdemPeriodo    = $oDados->ed41_i_sequencia;
        $oConfiguracao->sPeriodo         = $oDados->ed09_c_descr;
        $oConfiguracao->sFormaAvaliacao  = $oDados->ed37_c_descr;
        $oConfiguracao->iMenorValor      = $oDados->ed37_i_menorvalor;
        $oConfiguracao->iMaiorValor      = $oDados->ed37_i_maiorvalor;
        $oConfiguracao->nVariacao        = $oDados->ed37_i_variacao;
        $oConfiguracao->iMinimoAprovacao = $oDados->ed37_c_minimoaprov;
        $this->aConfiguracao[]           = $oConfiguracao;
      }

    }
    return $this->aConfiguracao;
  }

  /**
   * Retorna a regra configurada para o período da ordem informada
   * @param  integer $iOrdemPeriodo
   * @return stdClass|null
   */
  public function getConfiguracaoPorOrdem($iOrdemPeriodo) {

    $this->getConfiguracao();
    foreach ($this->aConfiguracao as $oRegra) {

      if ( $oRegra->iOrdemPeriodo == $iOrdemPeriodo ) {
        return $oRegra;
      }
    }
    return null;
  }

}