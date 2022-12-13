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
   * Classe repository para classes ContaOrcamento
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class ContaOrcamentoRepository {

    /**
     * Collection de ContaOrcamento
     * @var array
     */
    private $aContas = array();

    /**
     * Instancia da classe
     * @var ContaOrcamentoRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * @param integer $iCodigoContaOrcamento
     * @param integer $iAno
     * @param integer $iReduzido
     * @param integer $iInstituicao
     *
     * @return ContaOrcamento
     */
    public static function getContaByCodigo($iCodigoContaOrcamento, $iAno, $iReduzido = null, $iInstituicao = null) {

      $sChave = "{$iCodigoContaOrcamento}{$iAno}";
      if (!array_key_exists($sChave, ContaOrcamentoRepository::getInstance()->aContas)) {
        ContaOrcamentoRepository::getInstance()->aContas[$sChave] = new ContaOrcamento($iCodigoContaOrcamento, $iAno, $iReduzido, $iInstituicao);
      }
      return ContaOrcamentoRepository::getInstance()->aContas[$sChave];
    }

    /**
     * Retorna a instancia da classe
     * @return ContaOrcamentoRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {
        self::$oInstance = new ContaOrcamentoRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um ContaOrcamento dao repositorio
     * @param ContaOrcamento $oContaOrcamento Instancia do ContaOrcamento
     * @return boolean
     */
    public static function adicionarContaOrcamento(ContaOrcamento $oContaOrcamento) {

      $sChave = "{$oContaOrcamento->getCodigo()}{$oContaOrcamento->getAno()}";
      ContaOrcamentoRepository::getInstance()->aContas[$sChave] = $oContaOrcamento;
      return true;
    }

    /**
     * Remove o ContaOrcamento passado como parametro do repository
     * @param ContaOrcamento $oContaOrcamento
     * @return boolean
     */
    public static function removerContaOrcamento(ContaOrcamento $oContaOrcamento) {

      $sChave = "{$oContaOrcamento->getCodigo()}{$oContaOrcamento->getAno()}";
      if (array_key_exists($sChave, ContaOrcamentoRepository::getInstance()->aContas)) {
        unset(ContaOrcamentoRepository::getInstance()->aContas[$sChave]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalContaOrcamento() {
      return count(ContaOrcamentoRepository::getInstance()->aContas);
    }

    /**
     * Retorna uma conta do plano orcamentário através de seu estrutural
     * @param string  $sEstrutural estrutural da conta
     * @param integer $iAno
     * @return ContaOrcamento
     */
    public static function getContaPorEstrutural($sEstrutural, $iAno,  Instituicao $oInstituicao = null) {

      foreach (ContaOrcamentoRepository::getInstance()->aContas as $oConta) {

        if ($oConta->getEstrutural() == $sEstrutural && $oConta->getAno() == $iAno) {
          return $oConta;
        }
      }

      $oDaoPlanoOrcamentario = new cl_conplanoorcamento();

      $sWhere  = "c60_estrut      = '{$sEstrutural}'";
      $sWhere .= " and c60_anousu = {$iAno}";
      if (!empty($oInstituicao)) {
        $sWhere .= " and (c61_instit is null or c61_instit = {$oInstituicao->getSequencial()})";
      }
      $sSqlPlanoOrcamentario = $oDaoPlanoOrcamentario->sql_query_geral(null, null,
                                                                       "c60_codcon, c60_anousu, c61_instit, c61_reduz",
                                                                       'c60_estrut', $sWhere
                                                                      );
      $rsPlanoOrcamentario  = $oDaoPlanoOrcamentario->sql_record($sSqlPlanoOrcamentario);
      if ($oDaoPlanoOrcamentario->numrows > 0) {

        $oPlanoOrcamentario = db_utils::fieldsMemory($rsPlanoOrcamentario, 0);
        return ContaOrcamentoRepository::getContaByCodigo(
                                                          $oPlanoOrcamentario->c60_codcon,
                                                          $oPlanoOrcamentario->c60_anousu
                                                         );
      }

      return false;
    }
  }