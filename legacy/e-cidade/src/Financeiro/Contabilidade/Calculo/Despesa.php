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
namespace ECidade\Financeiro\Contabilidade\Calculo;

use ECidade\Financeiro\Contabilidade\LancamentoContabil\TipoDocumento;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;

/**
 * Class Despesa
 * @package Ecidade\Financeiro\Contabilidade\Calculo
 */
class Despesa {

  const MENSAGEM = "financeiro.contabilidade.CalculoDespesa.";

  /**
   * @var \DBDate
   */
  private $dataInicial = null;

  /**
   * @var \DBDate
   */
  private $dataFinal = null;

  /**
   * @var \Instituicao[]
   */
  private $instituicoes = array();

  /**
   * @var array
   */
  private $codigoInstituicoes = array();

  /**
   * @param array   $instituicoes
   * @throws \ParameterException
   */
  public function __construct(array $instituicoes) {

    if (empty($instituicoes)) {
      throw new \ParameterException( _M(self::MENSAGEM . "parametro_instituicao") );
    }

    $this->instituicoes = $instituicoes;
    foreach ($this->instituicoes as $instituicao) {
      $this->codigoInstituicoes[] = $instituicao->getCodigo();
    }
  }

  /**
   * @param \DBDate $dataInicial
   */
  public function setDataInicial(\DBDate $dataInicial) {
    $this->dataInicial = $dataInicial;
  }

  /**
   * Retorna os valores liquidados para a conta informada
   * EX ( Estrutural('333903000000000'), array('not in' => array(30,40), 'in' => (3,33)) )
   * @param Estrutural $estrutural
   * @param array      $documentos
   * @return Valor
   * @throws \ParameterException
   */
  public function getValorLiquidadoPorElementoDoOrcamento(Estrutural $estrutural, array $documentos = array()) {

    $filtroCombinado = array_merge(
      $this->processarFiltroDeElemento($estrutural),
      $this->processarFiltroDeDocumento($documentos)
    );
    return $this->processar(array(TipoDocumento::LIQUIDACAO, TipoDocumento::ESTORNO_LIQUIDACAO), $filtroCombinado);
  }

  /**
   * Retorna o valor anulado por elemento do orçamento
   * EX ( Estrutural('333903000000000'), array('not in' => array(30,40), 'in' => (3,33)) )
   * @param Estrutural $estrutural
   * @param array $documentos
   * @return Valor
   */
  public function getValorAnuladoPorElementoDoOrcamento(Estrutural $estrutural, array $documentos = array()) {

    $filtrosCombinados = array_merge(
      $this->processarFiltroDeElemento($estrutural),
      $this->processarFiltroDeDocumento($documentos)
    );
    return $this->processar(array(TipoDocumento::EMPENHO, TipoDocumento::ESTORNO_EMPENHO), $filtrosCombinados);
  }


  /**
   * Retorna os valores Inscritos em Restos a Pagar Não Processados
   * @param Estrutural $estrutural
   * @param array $documentos
   * @return Valor
   */
  public function getValorInscritoEmRestosAPagarNaoProcessados(Estrutural $estrutural, array $documentos = array()) {

    $filtrosCombinados = array_merge(
      $this->processarFiltroDeElemento($estrutural),
      $this->processarFiltroDeDocumento($documentos)
    );

    return $this->processar(array(TipoDocumento::ENCERRAMENTO_EXERCICIO), $filtrosCombinados);

  }

  /**
   * Retorna o filtro estrutural para buscas referentes ao orçamento
   * @param Estrutural $estrutural
   * @return array
   */
  private function processarFiltroDeElemento(Estrutural $estrutural) {

    $totalCaracteres = strlen($estrutural->getEstruturalAteNivel());
    $filtroCombinado = array(
      "(substr(orcelemento.o56_elemento, 1, {$totalCaracteres}) = '{$estrutural->getEstruturalAteNivel()}'",
      "substr(orcelemento.o56_elemento, 1, {$totalCaracteres}) >= '{$estrutural->getEstruturalAteNivel()}')",
    );
    return $filtroCombinado;
  }

  /**
   * Processa os filtros dos documentos para serem utilizados nos métodos da classe
   * @param array $documentos
   * @return array
   * @throws \ParameterException
   */
  private function processarFiltroDeDocumento(array $documentos) {

    $filtroCombinado = array();
    if (!empty($documentos)) {

      if (empty($documentos['not in']) && empty($documentos['in'])) {
        throw new \ParameterException( _M(self::MENSAGEM . 'parametro_tipo_documentos') );
      }

      foreach ($documentos as $clausula => $documentosSelecionados) {
        $filtroCombinado[] = "conhistdoc.c53_coddoc {$clausula} (" .implode(',', $documentosSelecionados). ")";
      }
    }
    return $filtroCombinado;
  }

  /**
   * @param \DBDate $dataFinal
   */
  public function setDataFinal(\DBDate $dataFinal) {
    $this->dataFinal = $dataFinal;
  }

  /**
   * @param array $tipoDocumentos Tipos de Documentos a serem consultados
   * @param array $filtros Filtros adicionais informados pelo usuário
   *
   * @return Valor
   * @throws \DBException|\ParameterException
   */
  private function processar(array $tipoDocumentos, array $filtros = array()) {

    if (empty($tipoDocumentos)) {
      throw new \ParameterException( _M(self::MENSAGEM . "parametro_documentos_nao_informado") );
    }

    if (empty($this->dataInicial) || empty($this->dataFinal)) {
      throw new \ParameterException( _M(self::MENSAGEM. "parametro_datas_nao_informado") );
    }

    if ($this->dataInicial->getTimeStamp() > $this->dataFinal->getTimeStamp()) {
      throw new \ParameterException( _M(self::MENSAGEM . "parametro_datas") );
    }

    /* define se o usuario informou somente o documento de inclusao, visto que não é qualquer documento que possui estorno */
    $documentoInclusao = $tipoDocumentos[0];
    $documentoExclusao = null;
    $consultaDocumentoEstorno = "round(0, 2)";
    if (count($tipoDocumentos) == 2) {

      $documentoExclusao = $tipoDocumentos[1];
      $consultaDocumentoEstorno = "round(coalesce(sum(case when c53_tipo = {$documentoExclusao} then c70_valor else 0 end), 0), 2)";
    }

    /* prepara o where de acordo coms filtros desejados */
    $where = array(
      "conlancam.c70_data between cast('{$this->dataInicial->getDate()}' as date) and cast('{$this->dataFinal->getDate()}' as date)",
      "conlancaminstit.c02_instit in (" . implode(',', $this->codigoInstituicoes) . ")"
    );

    $whereDocumentos = "conhistdoc.c53_tipo in ({$documentoInclusao}, {$documentoExclusao})";
    if (empty($documentoExclusao)) {
      $whereDocumentos = "conhistdoc.c53_tipo in ({$documentoInclusao})";
    }
    $where[] = $whereDocumentos;
    $where = array_merge($where, $filtros);

    /* executa a consulta dos dados na contabilidad */
    $campos  = " round(coalesce(sum(case when c53_tipo = {$documentoInclusao} then c70_valor else 0 end), 0), 2) as valor_inclusao";
    $campos .= ",{$consultaDocumentoEstorno} as valor_estorno";
    $daoLancamento = new \cl_conlancam();
    $consulta = db_query($daoLancamento->sql_query_despesa_orcamentaria($campos, implode(' and ', $where)));
    if (!$consulta || pg_num_rows($consulta) == 0) {
      throw new \DBException( _M(self::MENSAGEM . "consulta_lancamentos") );
    }

    $stdValores = \db_utils::fieldsMemory($consulta, 0);
    $valor = new Valor();
    $valor->setValorInclusao($stdValores->valor_inclusao);
    $valor->setValorEstorno($stdValores->valor_estorno);
    return $valor;
  }
}