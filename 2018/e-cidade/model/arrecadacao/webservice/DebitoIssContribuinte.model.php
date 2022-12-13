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
 * Classe para criacao de Debitos do ISSQN
 *
 * @author dbseller
 */
class DebitoIssContribuinte {

  /**
   * Planilha de Debito
   * @var planilhaRetencao
   */
  protected $oPlanilha;

  /**
   * Data de pagamento do debito
   * @var DBDate
   */
  protected $oDataPagamento;

  /**
   * Tipo de débito variavel ou retido
   * @var string
   */
  protected $sTipoDebito;

  /**
   * Dados da planilha enviados pelo webservice
   * @var
   */
  protected $oDadosPlanilha;

  /**
   * Metodo Construtor da Classe
   */
  public function __construct() {

  }

  /**
   * Define os dados da planilha do debito
   * @param stdclass $oPlanilha std Class com os dados da planilha
   */
  public function setPlanilha(stdclass $oPlanilha) {
    $this->oDadosPlanilha = $oPlanilha;
  }

  /**
   * Define a data de pagamento do debito
   * @param string $oDataPagamento
   */
  public function setDataPagamento($oDataPagamento) {
    $this->oDataPagamento = $oDataPagamento;
  }

  /**
   * @param string $sTipoDebito
   */
  public function setTipoDebito($sTipoDebito) {
    $this->sTipoDebito = $sTipoDebito;
  }

  /**
   * Cria a planilha de Retencao
   * @throws Exception competencia invalida
   */
  protected function criarPlanilha() {

    $oPlanilha = $this->oDadosPlanilha;
    if (empty($oPlanilha->ano_competencia)) {
      throw new Exception('ano de competência não informado');
    }

    if (empty($oPlanilha->mes_competencia)) {
      throw new Exception('Mês de competência não informado');
    }

    $oPlanilhaRetencao = new PlanilhaRetencaoWebService();
    if (isset($oPlanilha->cpf) && !empty($oPlanilha->cpf)) {
      $oPlanilhaRetencao->setCpf($oPlanilha->cpf);
    }

    if (isset($oPlanilha->cnpj) && !empty($oPlanilha->cnpj)) {
      $oPlanilhaRetencao->setCnpj($oPlanilha->cnpj);
    }

    // Verifica se foi informado uma inscrição municipal
    if (!empty($oPlanilha->inscricao_municipal)) {
      $oPlanilhaRetencao->setInscricaoTomador($oPlanilha->inscricao_municipal);
    } else {

      // Verifica se foi informado o número do CGM
      if (!empty($oPlanilha->numcgm)) {
        $oPlanilhaRetencao->setCgmTomador($oPlanilha->numcgm);
      } else {
        throw new Exception('Não foi informado um CGM ou incrição municipal do tomador!');
      }
    }

    $oPlanilhaRetencao->setCompetencia($oPlanilha->mes_competencia, $oPlanilha->ano_competencia);

    $iCodigoPlanilha = $oPlanilhaRetencao->salvar();
    $this->oPlanilha = $oPlanilhaRetencao;

    return $iCodigoPlanilha;
  }

  /**
   * Gera o debito para a planilha de issqn
   * @return stdClass Retorna um objeto com os dados processados
   */
  public function gerarDebito() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    $oDadosPlanilha  = $this->oDadosPlanilha;
    try {

      $iCodigoPlanilha = $this->criarPlanilha();
      $aPlanilhasNotas = array();
      foreach ($oDadosPlanilha->notas as $oNota) {

        $iCodigoNotaPlanilha         = $this->adicionarNotaNaPlanilha($oNota);
        $oNota->codigo_nota_planilha = $iCodigoNotaPlanilha;
        $aPlanilhasNotas[]           = $iCodigoNotaPlanilha;
      }
    } catch (Exception $eErro) {
      throw new Exception($eErro->getMessage());
    }
    $oDadosPlanilha->codigo_planilha = $iCodigoPlanilha;
    $oDadosPlanilha->debito          = $this->gerarReciboDebito($aPlanilhasNotas);
    $oDadosPlanilha->debito_planilha = $oDadosPlanilha->debito->lista_debitos[0]->numpre;

    return $oDadosPlanilha;
  }

  /**
   * Gera o recibo de débito
   *
   * @param $aPlanilhas
   * @return stdClass
   */
  protected function gerarReciboDebito($aPlanilhas) {

    $oDadosPlanilha = $this->oDadosPlanilha;
    $oReciboDebito = new GeracaoGuiaPrestadorWebService();
    $oReciboDebito->adicionarPlanilhasNotas($aPlanilhas);
    $oReciboDebito->setInscricao($oDadosPlanilha->inscricao_municipal);
    $oReciboDebito->setCgm($oDadosPlanilha->numcgm);
    $oReciboDebito->setCompetencia($oDadosPlanilha->mes_competencia, $oDadosPlanilha->ano_competencia);
    $oReciboDebito->setDataPagamento($this->oDataPagamento);
    $oReciboDebito->setTipoDebito($this->sTipoDebito);
    $oReciboDebito->setCodigoGuia($oDadosPlanilha->codigo_guia);

    $oDadosDebito = $oReciboDebito->gerarGuia();

    return $oDadosDebito;
  }


  /**
   * Adiciona uma nota/cupom/dms a planilha de retencao
   * @param stdClass $oDadosNota stdclass
   * @return integer codigo da nota da planilha
   */
  protected function adicionarNotaNaPlanilha(stdClass $oDadosNota) {

    $oPlanilha     = $this->oDadosPlanilha;
    $oNotaRetencao = new NotaPlanilhaRetencaoWebService();
    $oNotaRetencao->setCodPlanilha($this->oPlanilha->getCodigoPlanilha());
    $oNotaRetencao->setCnpjPrestador($oDadosNota->cnpj_prestador);
    $oNotaRetencao->setInscricaoPrestador($oDadosNota->inscricao_prestador);

    $oNotaRetencao->setNomePlanilha($oDadosNota->nome);
    $oNotaRetencao->setNumeroNf($oDadosNota->numero_nota_fiscal);
    $oNotaRetencao->setSerie($oDadosNota->serie_nota_fiscal);
    $oNotaRetencao->setDataNF($oDadosNota->data_nota_fiscal);
    $oNotaRetencao->setServicoPrestado(pg_escape_string($oDadosNota->servico_prestado));
    $oNotaRetencao->setValorServicoPrestado($oDadosNota->valor_servico_prestado);
    $oNotaRetencao->setValorDeducaoNota("{$oDadosNota->valor_deducao}");
    $oNotaRetencao->setValorBaseCalculoNota($oDadosNota->valor_base_calculo);
    $oNotaRetencao->setAliquotaNota($oDadosNota->aliquota);
    $oNotaRetencao->setValorImpostoRetido($oDadosNota->valor_imposto_retido);
    $oNotaRetencao->setCompetencia($oPlanilha->ano_competencia, $oPlanilha->mes_competencia);
    $oNotaRetencao->setDataPagamento($oDadosNota->data_pagamento);
    $oNotaRetencao->setRetido($oDadosNota->retido);
    $oNotaRetencao->setStatus($oDadosNota->status);
    $oNotaRetencao->setSituacao($oDadosNota->situacao);
    $oNotaRetencao->setTipoLancamento($oDadosNota->operacao);
    $oNotaRetencao->validarDados();
    $oNotaRetencao->lancaValorPlanilha();

    return $oNotaRetencao->getCodigoNotaPlanilha();
  }
}