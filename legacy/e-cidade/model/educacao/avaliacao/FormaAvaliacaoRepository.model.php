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
 * Classe repository para a Forma de Avaliação
 */
class FormaAvaliacaoRepository {

  /**
   * Array com as Formas de Avaliação
   * @var array
   */
  private $aFormasAvaliacao = array();

  /**
   * Instância da classe
   * @var FormaAvaliacaoRepository
   */
  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna uma instância da classe
   * @return FormaAvaliacaoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new FormaAvaliacaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna a Forma de Avaliação de acordo com o código informado
   * @param  integer $iCodigo Código da Forma de Avaliação
   * @return FormaAvaliacao
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, FormaAvaliacaoRepository::getInstance()->aFormasAvaliacao)) {
      FormaAvaliacaoRepository::getInstance()->aFormasAvaliacao[$iCodigo] = new FormaAvaliacao( $iCodigo );
    }

    return FormaAvaliacaoRepository::getInstance()->aFormasAvaliacao[$iCodigo];
  }

  public static function getFormasDoProcedimento( ProcedimentoAvaliacao $oProcedimento ) {

    $oDao = new cl_procedimento();
    $sSql = $oDao->sql_formasAvaliacaoProcedimento($oProcedimento->getCodigo());
    $rs   = db_query($sSql);

    if ( !$rs ) {
      throw new Exception("Error Processing Request", 1);
    }

    $iLinhas = pg_num_rows($rs);

    $aFormasAvaliacao = array();
    for ($i=0; $i < $iLinhas; $i++) {

      $iCodigo            = db_utils::fieldsMemory($rs, $i)->codigo;
      $aFormasAvaliacao[] = self::getByCodigo($iCodigo);
    }

    return $aFormasAvaliacao;
  }

  /**
   * Clona uma ou mais forma de avaliação vinculando a uma escola.
   * Retorna um array de-para com o código de origem e o novo código
   *
   * @param  FormaAvaliacao[] $aFormasAvaliacao
   * @param  Escola           $oEscola
   * @return array            index é o código de origem, valor é o código da nova forma de avaliação
   */
  public static function clonarFormasAvaliacaoEscola($aFormasAvaliacao, Escola $oEscola) {

    $aDePara = array();

    foreach ($aFormasAvaliacao as $oFormaAvaliacao) {

      $oDaoFormaAvaliacao = new cl_formaavaliacao;
      $oDaoFormaAvaliacao->ed37_i_codigo       = null;
      $oDaoFormaAvaliacao->ed37_c_descr        = $oFormaAvaliacao->getDescricao();
      $oDaoFormaAvaliacao->ed37_c_tipo         = $oFormaAvaliacao->getTipo();
      $oDaoFormaAvaliacao->ed37_i_menorvalor   = $oFormaAvaliacao->getMenorValor();
      $oDaoFormaAvaliacao->ed37_i_maiorvalor   = $oFormaAvaliacao->getMaiorValor();
      $oDaoFormaAvaliacao->ed37_i_variacao     = $oFormaAvaliacao->getVariacao();
      $oDaoFormaAvaliacao->ed37_c_minimoaprov  = $oFormaAvaliacao->getAproveitamentoMinino();
      $oDaoFormaAvaliacao->ed37_c_parecerarmaz = $oFormaAvaliacao->getTipo() == 'PARECER' ? 'S' : '';
      $oDaoFormaAvaliacao->ed37_i_escola       = $oEscola->getCodigo();

      $oDaoFormaAvaliacao->incluir(null);

      if ( $oDaoFormaAvaliacao->erro_status == 0 ) {
        throw new Exception( 'Erro ao clonar forma de avaliação.' );
      }

      if ( $oFormaAvaliacao->getTipo() == 'NIVEL') {

        foreach ($oFormaAvaliacao->getConceitos() as $oConceito) {

          $oDaoConceito                        = new cl_conceito();
          $oDaoConceito->ed39_i_codigo         = null;
          $oDaoConceito->ed39_i_formaavaliacao = $oDaoFormaAvaliacao->ed37_i_codigo;
          $oDaoConceito->ed39_c_conceito       = $oConceito->sConceito;
          $oDaoConceito->ed39_c_conceitodescr  = $oConceito->sDescricao;
          $oDaoConceito->ed39_i_sequencia      = $oConceito->iOrdem;
          $oDaoConceito->ed39_c_nome           = $oConceito->sNome;

          $oDaoConceito->incluir(null);

          if ( $oDaoFormaAvaliacao->erro_status == 0 ) {
            throw new Exception( 'Erro ao clonar forma de avaliação. Não foi possível incluir os conceitos' );
          }
        }
      }

      $aDePara[$oFormaAvaliacao->getCodigo()] = $oDaoFormaAvaliacao->ed37_i_codigo;
    }

    return $aDePara;
  }
}