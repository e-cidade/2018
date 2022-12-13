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

require_once modification("model/empenho/AutorizacaoEmpenho.model.php");

/**
 * Class ListaClassificacaoCredorRepository
 */
class ListaClassificacaoCredorRepository {

  /**
   * @var ListaClassificacaoCredor[]
   */
  private $aListaClassificacaoCredor = array();

  /**
   * @var ListaClassificacaoCredorRepository
   */
  private static $oInstancia;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna instância da classe.
   * @return ListaClassificacaoCredorRepository
   */
  protected static function getInstancia() {

    if (self::$oInstancia == null) {

      self::$oInstancia = new ListaClassificacaoCredorRepository();
      self::$oInstancia->getTodos();
    }
    return self::$oInstancia;
  }

  /**
   * Busca todas as listas de classificação de credor e preenche a instância com o resultado.
   * @throws BusinessException
   * @throws DBException
   */
  protected static function getTodos() {

    $sCampos = "cc30_codigo";
    $sOrder  = "cc30_ordem";
    $sWhere  = "";

    $oDaoListasClassificacao = new cl_classificacaocredores();
    $sSqlListasClassificacao = $oDaoListasClassificacao->sql_query(null, $sCampos, $sOrder, $sWhere);
    $rsListasClassificacao   = db_query($sSqlListasClassificacao);

    if (!$rsListasClassificacao) {
      throw new DBException("Houve um erro ao buscar as Listas de Classificação de Credores.");
    }

    $iTotalRegistros = pg_num_rows($rsListasClassificacao);
    if ($iTotalRegistros == 0) {
      throw new BusinessException("Nenhuma Lista de Classificação de Credores foi encontrada.");
    }

    for ($iIndice = 0; $iIndice < $iTotalRegistros; $iIndice++) {

      $oStdLista = db_utils::fieldsMemory($rsListasClassificacao, $iIndice);
      self::getInstancia()->aListaClassificacaoCredor[$oStdLista->cc30_codigo] = self::getPorCodigo($oStdLista->cc30_codigo);
    }
  }

  /**
   * Retorna uma instância de ClassificacaoCredor
   * @param integer $iCodigo
   * @return ListaClassificacaoCredor
   * @throws BusinessException
   */
  public static function getPorCodigo($iCodigo) {

    if (empty($iCodigo)) {
      return new ListaClassificacaoCredor();
    }

    if (array_key_exists($iCodigo, self::getInstancia()->aListaClassificacaoCredor)) {
      return self::getInstancia()->aListaClassificacaoCredor[$iCodigo];
    }

    $oDaoClassificacao      = new cl_classificacaocredores();
    $sSqlBuscaClassificacao = $oDaoClassificacao->sql_query_file($iCodigo);
    $rsBuscaClassificacao   = db_query($sSqlBuscaClassificacao);

    if (!$rsBuscaClassificacao || pg_num_rows($rsBuscaClassificacao) == 0) {
      throw new BusinessException("Classificação de Credor com código {$iCodigo} não localizado.");
    }

    $oListaClassificacao = new ListaClassificacaoCredor();
    $oStdClassificacao = db_utils::fieldsMemory($rsBuscaClassificacao, 0);
    $oListaClassificacao->setCodigo($iCodigo);
    $oListaClassificacao->setDescricao($oStdClassificacao->cc30_descricao);
    $oListaClassificacao->setDiasVencimento($oStdClassificacao->cc30_diasvencimento);
    $oListaClassificacao->setContagemDias($oStdClassificacao->cc30_contagemdias);
    $oListaClassificacao->setValorInicial($oStdClassificacao->cc30_valorinicial);
    $oListaClassificacao->setValorFinal($oStdClassificacao->cc30_valorfinal);
    $oListaClassificacao->setDispensa($oStdClassificacao->cc30_dispensa == "t");
    $oListaClassificacao->setOrdem($oStdClassificacao->cc30_ordem);

    self::getInstancia()->aListaClassificacaoCredor[$iCodigo] = $oListaClassificacao;
    return $oListaClassificacao;
  }

  /**
   * Retorna a Lista de Classificação de Credores para os atributos do empenho informados.
   * @param AtributosEmpenho $oAtributosEmpenho
   *
   * @return ListaClassificacaoCredor
   * @throws BusinessException
   * @throws Exception
   */
  protected static function classificaAtributosEmpenho(AtributosEmpenho $oAtributosEmpenho) {

    $aRegras = array();

    $oRegraValores           = new RegraClassificacaoCredorValores();
    $oRegraPrestacaoContas   = new RegraClassificacaoCredorPrestacaoContas();
    $oRegraRecursos          = new RegraClassificacaoCredorRecursos();
    $oRegraTiposCompra       = new RegraClassificacaoCredorTiposCompra();
    $oRegraElementosExclusao = new RegraClassificacaoCredorElementosExclusao();
    $oRegraElementos         = new RegraClassificacaoCredorElementos();

    $oRegraValores->setProximo($oRegraPrestacaoContas);
    $oRegraPrestacaoContas->setProximo($oRegraRecursos);
    $oRegraRecursos->setProximo($oRegraTiposCompra);
    $oRegraTiposCompra->setProximo($oRegraElementosExclusao);
    $oRegraElementosExclusao->setProximo($oRegraElementos);

    $aRegras[] = $oRegraValores;
    $aRegras[] = $oRegraPrestacaoContas;
    $aRegras[] = $oRegraRecursos;
    $aRegras[] = $oRegraTiposCompra;
    $aRegras[] = $oRegraElementosExclusao;
    $aRegras[] = $oRegraElementos;

    foreach (self::getInstancia()->aListaClassificacaoCredor as $oListaClassificacaoCredor) {

      foreach ($aRegras as $oRegra) {

        $oRegra->setAtributoEmpenho($oAtributosEmpenho);
        $oRegra->setListaClassificador($oListaClassificacaoCredor);
      }

      if ($oRegraValores->aplicarRegras()) {
        return $oListaClassificacaoCredor;
      }
    }

    return null;
  }

  /**
   * Busca a lista de classificação de credores de acordo com os atributos do empenho.
   *
   * @param AtributosEmpenho $oAtributosEmpenho
   *
   * @return ListaClassificacaoCredor
   * @throws BusinessException
   * @throws DBException
   * @throws Exception
   * @throws ParameterException
   */
  public static function getPorAtributos(AtributosEmpenho $oAtributosEmpenho) {

    $nValor           = $oAtributosEmpenho->getValor();
    $oRecurso         = $oAtributosEmpenho->getRecurso();
    $oTipoCompra      = $oAtributosEmpenho->getTipoCompra();
    $oContaOrcamento  = $oAtributosEmpenho->getContaOrcamento();
    $oTipoPrestaConta = $oAtributosEmpenho->getTipoPrestacaoConta();
    $iElemento        = $oAtributosEmpenho->getElemento();

    if (empty($nValor)) {
      throw new ParameterException('Atributo valor não informado.');
    }

    if (empty($oRecurso)) {
      throw new ParameterException('Atributo Recurso não informado.');
    }

    if (empty($oTipoCompra)) {
      throw new ParameterException('Atributo Tipo de Compra não informado.');
    }

    if (empty($oContaOrcamento)) {
      throw new ParameterException('Atributo Conta Orçamentária não informado.');
    }

    if (empty($iElemento)) {
      throw new ParameterException('Atributo Elemento não informado.');
    }

    if (empty($oTipoPrestaConta)) {
      throw new ParameterException('Atributo Tipo de Prestação de Conta não informado.');
    }

    return self::classificaAtributosEmpenho($oAtributosEmpenho);
  }

  /**
   * Retorna os atributos do empenho a partir da autorização
   *
   * @param AutorizacaoEmpenho $oAutorizacaoEmpenho
   *
   * @return AtributosEmpenho
   * @throws DBException
   * @throws ParameterException
   */
  public static function getAtributosAutorizacao(AutorizacaoEmpenho $oAutorizacaoEmpenho) {

    $iDotacao    = $oAutorizacaoEmpenho->getDotacao();
    $iAnoDotacao = $oAutorizacaoEmpenho->getAno();
    $iTipoCompra = $oAutorizacaoEmpenho->getTipoCompra();

    if (empty($iDotacao)) {
      throw new ParameterException("A autorização não existe ou não possui dotação vinculada.");
    }

    if (empty($iTipoCompra)) {
      throw new ParameterException("O Tipo de Compra da Autorização de Empenho deve ser informada.");
    }

    $oDotacao             = DotacaoRepository::getDotacaoPorCodigoAno($iDotacao, $iAnoDotacao);
    $oRecurso             = RecursoRepository::getRecursoPorCodigo($oDotacao->getRecurso());
    $oTipoCompra          = new TipoCompra($iTipoCompra);
    $oContaOrcamento      = $oDotacao->getContaOrcamentaria();
    $oTipoPrestacaoContas = $oAutorizacaoEmpenho->getTipoPrestacaoConta();

    if (empty($oContaOrcamento)) {
      throw new ParameterException("A Conta Orçamentária da Dotação da Autorização de Empenho deve ser informada.");
    }

    if (empty($oTipoPrestacaoContas)) {
      throw new ParameterException("O Tipo de Prestação de Contas da Autorização de Empenho deve ser informado.");
    }

    $oAtributosEmpenho = new AtributosEmpenho();
    $oAtributosEmpenho->setContaOrcamento($oContaOrcamento);
    $oAtributosEmpenho->setRecurso($oRecurso);
    $oAtributosEmpenho->setTipoCompra($oTipoCompra);
    $oAtributosEmpenho->setTipoPrestacaoConta($oTipoPrestacaoContas);
    $oAtributosEmpenho->setValor($oAutorizacaoEmpenho->getValor());

    return $oAtributosEmpenho;
  }

  /**
   * @param EmpenhoFinanceiro $oEmpenho
   *
   * @return ListaClassificacaoCredor
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public static function getPorEmpenho(EmpenhoFinanceiro $oEmpenho) {

    $oDotacao = $oEmpenho->getDotacao();
    $oContaOrcamento = $oEmpenho->getContaOrcamento();
    if (empty($oDotacao)) {
      throw new BusinessException("Dotação não localizada para o empenho.");
    }
    $iCodigoRecurso = $oDotacao->getRecurso();
    if (empty($iCodigoRecurso)) {
      throw new BusinessException("Recurso não localizado para o empenho.");
    }

    $oAtributosEmpenho = new AtributosEmpenho();
    $oAtributosEmpenho->setContaOrcamento($oContaOrcamento);
    $oAtributosEmpenho->setRecurso(new Recurso($iCodigoRecurso));
    $oAtributosEmpenho->setTipoCompra($oEmpenho->getTipoDeCompra());
    $oAtributosEmpenho->setTipoPrestacaoConta($oEmpenho->getTipoPrestacaoConta());
    $oAtributosEmpenho->setValor($oEmpenho->getValorEmpenho());
    $oAtributosEmpenho->setElemento($oContaOrcamento->getEstrutural());
    return self::getPorAtributos($oAtributosEmpenho);
  }
}