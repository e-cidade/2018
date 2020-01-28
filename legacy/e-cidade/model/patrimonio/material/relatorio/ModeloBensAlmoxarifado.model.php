<?php
/**
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
 * Classe é responsável por trazer as informações do relatório do modelo 21 do bens almoxarifado
 *
 * @author $Author: dbigor.cemim $
 * @version $Revision: 1.4 $
 */
class ModeloBensAlmoxarifado {

  const CAMINHO_MENSAGEM = 'patrimonial.material.ModeloBensAlmoxarifado.';

  /**
   * Representa o almoxarifado
   *
   * @var Almoxarifado
   */
  private $oAlmoxarifado;

  /**
   * Representa a competência
   *
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Representa o tipo do grupo dos materiais
   *
   * @var int
   */
  private $iTipoEmissao;

  /**
   * Saldo do mês anterior
   *
   * @var float
   */
  private $fSaldoAnterior = 0;

  /**
   * Entrada do estoque - Valor da compra
   *
   * @var float
   */
  private $fValorCompra = 0;

  /**
   * Entrada do estoque - Valor da doação
   *
   * @var float
   */
  private $fValorDoacao = 0;

  /**
   * Entrada do estoque - Valor da transferência
   *
   * @var float
   */
  private $fValorTransferencia = 0;

  /**
   * Entrada do estoque - Valor da devolução
   *
   * @var float
   */
  private $fValorDevolucao = 0;

  /**
   *
   * Saída do estoque - Valor da requisição
   * @var float
   */
  private $fValorRequisicao = 0;

  /**
   * Saída do estoque - Valor da baixa
   *
   * @var float
   */
  private $fValorBaixa = 0;

  /**
   * Construtor da classe
   *
   * @param Almoxarifado $oAlmoxarifado
   * @param DBCompetencia $oCompetencia
   * @param int $iTipoEmissao
   */
  function __construct(Almoxarifado $oAlmoxarifado, DBCompetencia $oCompetencia, $iTipoEmissao) {

    $this->setAlmoxarifado($oAlmoxarifado);
    $this->setCompetencia($oCompetencia);
    $this->setTipoEmissao($iTipoEmissao);
    $this->processar();
  }

  /**
   * Retorna o almoxarifado informada
   *
   * @access public
   * @return Almoxarifado
   */
  public function getAlmoxarifado() {
    return $this->oAlmoxarifado;
  }

  /**
   * Retorna a competência informada
   *
   * @access public
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna o tipo do grupo dos materiais
   *
   * @access public
   * @return int
   */
  public function getTipoEmissao() {
    return $this->iTipoEmissao;
  }

  /**
   * Retorna o saldo do mês anterior
   *
   * @access public
   * @return float
   */
  public function getSaldoAnterior() {
    return $this->fSaldoAnterior;
  }

  /**
   * Retorna o valor da compra
   *
   * @access public
   * @return float
   */
  public function getValorCompra() {
    return $this->fValorCompra;
  }

  /**
   * Retorna o valor da doação
   *
   * @access public
   * @return float
   */
  public function getValorDoacao() {
    return $this->fValorDoacao;
  }

  /**
   * Retorna o valor da transferência
   *
   * @access public
   * @return float
   */
  public function getValorTransferencia() {
    return $this->fValorTransferencia;
  }

  /**
   * Retorna o valor da devolução
   *
   * @access public
   * @return float
   */
  public function getValorDevolucao() {
    return $this->fValorDevolucao;
  }

  /**
   * Retorna o valor da requisição
   *
   * @access public
   * @return float
   */
  public function getValorRequisicao() {
    return $this->fValorRequisicao;
  }

  /**
   * Retorna o valor da baixa
   *
   * @access public
   * @return float
   */
  public function getValorBaixa() {
    return $this->fValorBaixa;
  }

  /**
   * Seta o almoxarifado
   *
   * @access private
   * @param Almoxarifado $oAlmoxarifado
   */
  private function setAlmoxarifado(Almoxarifado $oAlmoxarifado) {
    $this->oAlmoxarifado = $oAlmoxarifado;
  }

  /**
   * Seta a competência
   *
   * @access private
   * @param DBCompetencia $oCompetencia
   */
  private function setCompetencia(DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Seta o tipo do grpo dos materiais
   *
   * @access private
   * @param int $iTipoEmissao
   */
  private function setTipoEmissao($iTipoEmissao) {
    $this->iTipoEmissao = $iTipoEmissao;
  }

  /**
   * Seta o saldo do mês anterior do mês que foi informado
   *
   * @access private
   * @param float $fSaldoAnterior
   */
  private function setSaldoAnterior($fSaldoAnterior) {
    $this->fSaldoAnterior = $fSaldoAnterior;
  }

  /**
   * Seta o valor da compra
   *
   * @access private
   * @param float $fValorCompra
   */
  private function setValorCompra($fValorCompra) {
    $this->fValorCompra = $fValorCompra;
  }

  /**
   * Seta o valor da doação
   *
   * @access private
   * @param float $fValorDoacao
   */
  private function setValorDoacao($fValorDoacao) {
    $this->fValorDoacao = $fValorDoacao;
  }

  /**
   * Seta o valor da tranferência
   *
   * @access private
   * @param float $fValorTransferencia
   */
  private function setValorTransferencia($fValorTransferencia) {
    $this->fValorTransferencia = $fValorTransferencia;
  }

  /**
   * Seta o valor da devolução
   *
   * @access private
   * @param float $fValorDevolucao
   */
  private function setValorDevolucao($fValorDevolucao) {
    $this->fValorDevolucao = $fValorDevolucao;
  }

  /**
   * Seta o valor da requisição
   *
   * @access private
   * @param float $fValorRequisicao
   */
  private function setValorRequisicao($fValorRequisicao) {
    $this->fValorRequisicao = $fValorRequisicao;
  }

  /**
   * Seta o valor da baixa
   *
   * @access private
   * @param float $fValorBaixa
   */
  private function setValorBaixa($fValorBaixa) {
    $this->fValorBaixa = $fValorBaixa;
  }

  /**
   * Popula a classe com os dados do relatório
   *
   * @access private
   * @throws BusinessException
   */
  private function processar() {

    $oDaoMovimentacaoEstoque = new cl_matestoqueini();

    $iAno = $this->getCompetencia()->getAno();
    $iMes = $this->getCompetencia()->getMes();
    $iDia = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);

    $sDataInicialMes  = "{$iAno}-{$iMes}-01";
    $sDataFinalMes    = "{$iAno}-{$iMes}-{$iDia}";

    $sCampo  = "coalesce(sum(case when m81_codtipo in (1, 3, 12) and m80_data >= '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro else 0 end), 0) as compras, ";
    $sCampo .= "coalesce(sum(case when m81_codtipo in (8) and m80_data >= '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro else 0 end), 0) as transferencias, ";
    $sCampo .= "coalesce(sum(case when m81_codtipo in (18) and m80_data >= '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro else 0 end), 0) as devolucoes, ";
    $sCampo .= "coalesce(sum(case when m81_codtipo in (17, 21) and m80_data >= '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro else 0 end), 0) as requisicoes, ";
    $sCampo .= "coalesce(sum(case when m81_codtipo in (5) and m80_data >= '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro else 0 end), 0) as baixas, ";
    $sCampo .= "coalesce(sum(case when m81_tipo = 1 and m80_data < '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro ";
    $sCampo .= "         when m81_tipo = 2 and m80_data < '{$sDataInicialMes}' ";
    $sCampo .= "           then m89_valorfinanceiro * -1 else 0 end), 0) as saldo_anterior ";

    $sWhere  = "      m80_coddepto = {$this->getAlmoxarifado()->getCodigo()}";
    $sWhere .= "  and m80_data <= '{$sDataFinalMes}'";
    $sWhere .= "  and m71_servico is false";
    $sWhere .= "  and m04_materialtipogrupo = {$this->getTipoEmissao()}";
    $sWhere .= "  and matestoqueinil.m86_codigo is null";


    $sSqlDadosMovimentacao =  $oDaoMovimentacaoEstoque->sql_query_movimentacoes_por_tipo_grupo(null,
                                                                                               $sCampo,
                                                                                               null,
                                                                                               $sWhere
                                                                                              );
    $rsDadosMovimentacao   = db_query($sSqlDadosMovimentacao);

    if (!$rsDadosMovimentacao) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM . "erro_consultar_movimentos_estoque"));
    }

    if (pg_num_rows($rsDadosMovimentacao) > 0) {

      $oDadosEstoque = db_utils::fieldsMemory($rsDadosMovimentacao, 0);
      $this->setSaldoAnterior($oDadosEstoque->saldo_anterior);
      $this->setValorBaixa($oDadosEstoque->baixas);
      $this->setValorCompra($oDadosEstoque->compras);
      $this->setValorTransferencia($oDadosEstoque->transferencias);
      $this->setValorDevolucao($oDadosEstoque->devolucoes);
      $this->setValorRequisicao($oDadosEstoque->requisicoes);
      $this->setValorDoacao(0);
    }
  }
}
