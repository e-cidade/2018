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
 * Tipo de movimentacao do estque
 *
 * @package estoque
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class TipoMovimentacaoEstoque {

  const ENTRADA          = 1;
  const SAIDA            = 2;
  const EM_TRANSFERENCIA = 4;
  const CONTROLE         = 5;

  const CODIGO_ENTRADA_ORDEM_COMPRA = 12;
  const CODIGO_ANULACAO_ENTRADA_ORDEM_COMPRA = 19;

  /**
   * Código do tipo de movimentação
   *
   * @var mixed
   * @access private
   */
  private $iCodigo;

  /**
   * Descricao do tipo de movimentacao
   *
   * @var mixed
   * @access private
   */
  private $sDescricao;

  /**
   * Classificao do tipo
   * entrada, saida ou em transferencia
   *
   * @var integer
   * @access private
   */
  private $iClassificacao;

  /**
   * Constrói o objeto com os dados
   * @param integer $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    /**
     * Código do tipo de movimentação não informado
     */
    if (empty($iCodigo)) {
      return false;
    }

    $oDaoMatestoquetipo   = new cl_matestoquetipo();
    $sSqlTipoMovimentacao = $oDaoMatestoquetipo->sql_query_file($iCodigo);
    $rsTipoMovimentacao   = $oDaoMatestoquetipo->sql_record($sSqlTipoMovimentacao);

    if ($oDaoMatestoquetipo->erro_status == '0') {
      throw new Exception($oDaoMatestoquetipo->erro_msg);
    }

    $oDadosTipoMovimentacao = db_utils::fieldsMemory($rsTipoMovimentacao, 0);

    $this->iCodigo        = (int) $iCodigo;
    $this->sDescricao     = $oDadosTipoMovimentacao->m81_descr;
    $this->iClassificacao = (int) $oDadosTipoMovimentacao->m81_tipo;
  }

  /**
   * Retorna o codigo do tipo de movimentação *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao do tipo de movimentação
   *
   * @access public
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna a classificação do tipo de movimentação
   * entrada, saida ou em tranferencia
   *
   * @access public
   * @return integer
   */
  public function getClassificacao() {
    return $this->iClassificacao;
  }

}