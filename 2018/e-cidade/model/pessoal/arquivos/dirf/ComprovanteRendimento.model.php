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
 * Model para os dados do comprovante de rendimentos da Dirf
 * Class ComprovanteRendimento
 */
class ComprovanteRendimento {

  /**
   * @var CgmFisico|CgmJuridico
   */
  protected $cgm;

  /**
   * @var Servidor[]
   */
  protected $matriculas = array();

  /**
   * Fonte pagadora
   * @var string
   */
  protected $fontePagadora = '';

  /**
   * Codigo da Lotacao
   * @var integer
   */
  protected $lotacao = '';

  /**
   * Total de rendimentos
   * @var float
   */
  protected $valorTotalRendimentos = 0;

  /**
   * Total de rendimentos no 13
   * @var float
   */
  protected $valorTotalRendimentoDecimoTerceiro = 0;

  /**
   * Valor total da previdencia oficial
   * @var float
   */
  protected $valorPrevidenciaOficial = 0;

  /**
   * valor da previdencia no 13
   * @var float
   */
  protected $valorPrevidenciaOficialDecimoTerceiro = 0;

  /**
   * valor da previdencia privada
   * @var float
   */
  protected $valorPrevidenciaPrivada = 0;

  /**
   * valor da previdencia privada no 13
   * @var float
   */
  protected $valorPrevidenciaPrivadaDecimoTerceiro = 0;

  /**
   * valor deduzido por dependentes
   * @var float
   */
  protected $valorDependentes = 0;

  /**
   * valor deduzido por dependentes
   * @var float
   */
  protected $valorDependentesDecimoTerceiro = 0;

  /**
   * Valor pago total de pensao
   * @var float
   */
  protected $valorPagoEmPensao = 0;

  /**
   * Valor total pagao de pensao sobre 13
   * @var float
   */
  protected $valorPagoEmPensaoDecimoTerceiro = 0;

  /**
   * Valor pago de IRRF
   * @var float
   */
  protected $valorPagoIRRF = 0;

  /**
   * Valor pago de IRRF
   * @var float
   */
  protected $valorPagoIRRFDecimoTerceiro = 0;

  /**
   * Valor de desconto no IR para inativos maior de 65 anos
   * @var float
   */
  protected $valorDescontoAposentado = 0;

  /**
   * Valor de desconto no IR para inativos maior de 65 anos sobre 13 salário
   * @var float
   */
  protected $valorDescontoAposentadoDecimoTerceiro = 0;

  /**
   * Valor recebido em diarias
   * @var float
   */
  protected $valorDiarias = 0;

  /**
   * Valor recebido de rescisao
   * @var float
   */
  protected $valorIndenizacaoRescisao = 0;

  /**
   * Valor recebido de abono
   * @var float
   */
  protected $valorAbono = 0;

  /**
   * Valor recebido de outros rendimentos
   * @var int
   */
  protected $valorOutrosRendimentos = 0;

  /**
   * VAlor descontado para aposentados com molestia grave
   * @var float
   */
  protected $valorDescontoMolestiaGraveInativos = 0;

  /**
   * VAlor descontado para aposentados com molestia grave sobre decimo terceiro
   * @var float
   */
  protected $valorDescontoMolestiaGraveInativosDecimoTerceiro = 0;

  /**
   * Valor descontado para aposentados com molestia grave
   * @var float
   */
  protected $valorMolestiaGraveAtivos = 0;

  /**
   * Valor descontado para aposentados com molestia grave
   * @var float
   */
  protected $valorMolestiaGraveAtivosDecimoTerceiro = 0;

  /**
   * Valor gasto em plano de saude
   * @var float
   */
  protected $valorPlanoSaude = 0;

  /**
   * Valor Rendimentos tributaveis sobre RRA
   * @var float
   */
  protected $valorRendimentosTributaveisSobreRRA = 0;

  /**
   * Valor previdencia sobre RRA
   * @var float
   */
  protected $valorPrevidenciaSobreRRA = 0;

  /**
   * Valor de pensao sobre RRA
   * @var float
   */
  protected $valorPensaoSobreRRA = 0;

  /**
   * Valor do IRRF sobre RRA
   * @var float
   */
  protected $valorIRRFSobreRRA = 0;

  /**
   * Valor gasto com ações
   * @var float
   */
  protected $valorDespesaDaAcao = 0;

  /**
   * Quantidade de meses do RRA
   * @var float
   */
  protected $quantidadeDeMeses = 0;

  /**
   * Quantidade de meses do RRA
   * @var float
   */
  protected $valorInsencaoSbreRRA = 0;

  /**
   * Outras informaçoes
   * @var null
   */
  protected $outras_informacoes = null;

  /**
   * Nome da fonte pagadora
   * @var string
   */
  protected $nomeFontePagadora = null;

  /**
   * @return CgmFisico|CgmJuridico
   */
  public function getCgm() {

    return $this->cgm;
  }

  /**
   * @param \cgm $cgm
   */
  public function setCgm($cgm) {

    $this->cgm = $cgm;
  }

  /**
   * @return \Servidor[]
   */
  public function getMatriculas() {

    return $this->matriculas;
  }

  /**
   * @param \Servidor[] $matriculas
   */
  public function setMatriculas($matriculas) {

    $this->matriculas = $matriculas;
  }

  /**
   * @return string
   */
  public function getFontePagadora() {

    return $this->fontePagadora;
  }

  /**
   * @param string $fontePagadora
   */
  public function setFontePagadora($fontePagadora) {

    $this->fontePagadora = $fontePagadora;
  }

  /**
   * @return int
   */
  public function getLotacao() {

    return $this->lotacao;
  }

  /**
   * @param int $lotacao
   */
  public function setLotacao($lotacao) {

    $this->lotacao = $lotacao;
  }

  /**
   * @return float
   */
  public function getValorTotalRendimentos() {

    return $this->valorTotalRendimentos;
  }

  /**
   * @param float $valorTotalRendimentos
   */
  public function setValorTotalRendimentos($valorTotalRendimentos) {

    $this->valorTotalRendimentos = $valorTotalRendimentos;
  }

  /**
   * @return float
   */
  public function getValorTotalRendimentoDecimoTerceiro() {

    return $this->valorTotalRendimentoDecimoTerceiro;
  }

  /**
   * @param float $valorTotalRendimentoDecimoTerceiro
   */
  public function setValorTotalRendimentoDecimoTerceiro($valorTotalRendimentoDecimoTerceiro) {

    $this->valorTotalRendimentoDecimoTerceiro = $valorTotalRendimentoDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorPrevidenciaOficial() {

    return $this->valorPrevidenciaOficial;
  }

  /**
   * @param float $valorPrevidenciaOficial
   */
  public function setValorPrevidenciaOficial($valorPrevidenciaOficial) {

    $this->valorPrevidenciaOficial = $valorPrevidenciaOficial;
  }

  /**
   * @return float
   */
  public function getValorPrevidenciaOficialDecimoTerceiro() {

    return $this->valorPrevidenciaOficialDecimoTerceiro;
  }

  /**
   * @param float $valorPrevidenciaOficialDecimoTerceiro
   */
  public function setValorPrevidenciaOficialDecimoTerceiro($valorPrevidenciaOficialDecimoTerceiro) {

    $this->valorPrevidenciaOficialDecimoTerceiro = $valorPrevidenciaOficialDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorPrevidenciaPrivada() {

    return $this->valorPrevidenciaPrivada;
  }

  /**
   * @param float $valorPrevidenciaPrivada
   */
  public function setValorPrevidenciaPrivada($valorPrevidenciaPrivada) {

    $this->valorPrevidenciaPrivada = $valorPrevidenciaPrivada;
  }

  /**
   * @return float
   */
  public function getValorPrevidenciaPrivadaDecimoTerceiro() {

    return $this->valorPrevidenciaPrivadaDecimoTerceiro;
  }

  /**
   * @param float $valorPrevidenciaPrivadaDecimoTerceiro
   */
  public function setValorPrevidenciaPrivadaDecimoTerceiro($valorPrevidenciaPrivadaDecimoTerceiro) {

    $this->valorPrevidenciaPrivadaDecimoTerceiro = $valorPrevidenciaPrivadaDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorDependentes() {

    return $this->valorDependentes;
  }

  /**
   * @param float $valorDependentes
   */
  public function setValorDependentes($valorDependentes) {

    $this->valorDependentes = $valorDependentes;
  }

  /**
   * @return float
   */
  public function getValorDependentesDecimoTerceiro() {

    return $this->valorDependentesDecimoTerceiro;
  }

  /**
   * @param float $valorDependentesDecimoTerceiro
   */
  public function setValorDependentesDecimoTerceiro($valorDependentesDecimoTerceiro) {

    $this->valorDependentesDecimoTerceiro = $valorDependentesDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorPagoEmPensao() {

    return $this->valorPagoEmPensao;
  }

  /**
   * @param float $valorPagoEmPensao
   */
  public function setValorPagoEmPensao($valorPagoEmPensao) {

    $this->valorPagoEmPensao = $valorPagoEmPensao;
  }

  /**
   * @return float
   */
  public function getValorPagoEmPensaoDecimoTerceiro() {

    return $this->valorPagoEmPensaoDecimoTerceiro;
  }

  /**
   * @param float $valorPagoEmPensaoDecimoTerceiro
   */
  public function setValorPagoEmPensaoDecimoTerceiro($valorPagoEmPensaoDecimoTerceiro) {

    $this->valorPagoEmPensaoDecimoTerceiro = $valorPagoEmPensaoDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorPagoIRRF() {

    return $this->valorPagoIRRF;
  }

  /**
   * @param float $valorPagoIRRF
   */
  public function setValorPagoIRRF($valorPagoIRRF) {

    $this->valorPagoIRRF = $valorPagoIRRF;
  }

  /**
   * @return float
   */
  public function getValorPagoIRRFDecimoTerceiro() {

    return $this->valorPagoIRRFDecimoTerceiro;
  }

  /**
   * @param float $valorPagoIRRFDecimoTerceiro
   */
  public function setValorPagoIRRFDecimoTerceiro($valorPagoIRRFDecimoTerceiro) {

    $this->valorPagoIRRFDecimoTerceiro = $valorPagoIRRFDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorDescontoAposentado() {

    return $this->valorDescontoAposentado;
  }

  /**
   * @param float $valorDescontoAposentado
   */
  public function setValorDescontoAposentado($valorDescontoAposentado) {

    $this->valorDescontoAposentado = $valorDescontoAposentado;
  }

  /**
   * @return float
   */
  public function getValorDescontoAposentadoDecimoTerceiro() {

    return $this->valorDescontoAposentadoDecimoTerceiro;
  }

  /**
   * @param float $valorDescontoAposentadoDecimoTerceiro
   */
  public function setValorDescontoAposentadoDecimoTerceiro($valorDescontoAposentadoDecimoTerceiro) {

    $this->valorDescontoAposentadoDecimoTerceiro = $valorDescontoAposentadoDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorDiarias() {

    return $this->valorDiarias;
  }

  /**
   * @param float $valorDiarias
   */
  public function setValorDiarias($valorDiarias) {

    $this->valorDiarias = $valorDiarias;
  }

  /**
   * @return float
   */
  public function getValorIndenizacaoRescisao() {

    return $this->valorIndenizacaoRescisao;
  }

  /**
   * @param float $valorIndenizacaoRescisao
   */
  public function setValorIndenizacaoRescisao($valorIndenizacaoRescisao) {

    $this->valorIndenizacaoRescisao = $valorIndenizacaoRescisao;
  }

  /**
   * @return float
   */
  public function getValorAbono() {

    return $this->valorAbono;
  }

  /**
   * @param float $valorAbono
   */
  public function setValorAbono($valorAbono) {

    $this->valorAbono = $valorAbono;
  }

  /**
   * @return int
   */
  public function getValorOutrosRendimentos() {

    return $this->valorOutrosRendimentos;
  }

  /**
   * @param int $valorOutrosRendimentos
   */
  public function setValorOutrosRendimentos($valorOutrosRendimentos) {

    $this->valorOutrosRendimentos = $valorOutrosRendimentos;
  }

  /**
   * @return float
   */
  public function getValorDescontoMolestiaGraveInativos() {

    return $this->valorDescontoMolestiaGraveInativos;
  }

  /**
   * @param float $valorDescontoMolestiaGraveInativos
   */
  public function setValorDescontoMolestiaGraveInativos($valorDescontoMolestiaGraveInativos) {

    $this->valorDescontoMolestiaGraveInativos = $valorDescontoMolestiaGraveInativos;
  }

  /**
   * @return float
   */
  public function getValorDescontoMolestiaGraveInativosDecimoTerceiro() {

    return $this->valorDescontoMolestiaGraveInativosDecimoTerceiro;
  }

  /**
   * @param float $valorDescontoMolestiaGraveInativosDecimoTerceiro
   */
  public function setValorDescontoMolestiaGraveInativosDecimoTerceiro($valorDescontoMolestiaGraveInativosDecimoTerceiro) {

    $this->valorDescontoMolestiaGraveInativosDecimoTerceiro = $valorDescontoMolestiaGraveInativosDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorMolestiaGraveAtivos() {

    return $this->valorMolestiaGraveAtivos;
  }

  /**
   * @param float $valorMolestiaGraveAtivos
   */
  public function setValorMolestiaGraveAtivos($valorMolestiaGraveAtivos) {

    $this->valorMolestiaGraveAtivos = $valorMolestiaGraveAtivos;
  }

  /**
   * @return float
   */
  public function getValorMolestiaGraveAtivosDecimoTerceiro() {

    return $this->valorMolestiaGraveAtivosDecimoTerceiro;
  }

  /**
   * @param float $valorMolestiaGraveAtivosDecimoTerceiro
   */
  public function setValorMolestiaGraveAtivosDecimoTerceiro($valorMolestiaGraveAtivosDecimoTerceiro) {

    $this->valorMolestiaGraveAtivosDecimoTerceiro = $valorMolestiaGraveAtivosDecimoTerceiro;
  }

  /**
   * @return float
   */
  public function getValorPlanoSaude() {

    return $this->valorPlanoSaude;
  }

  /**
   * @param float $valorPlanoSaude
   */
  public function setValorPlanoSaude($valorPlanoSaude) {

    $this->valorPlanoSaude = $valorPlanoSaude;
  }

  /**
   * @return float
   */
  public function getValorRendimentosTributaveisSobreRRA() {

    return $this->valorRendimentosTributaveisSobreRRA;
  }

  /**
   * @param float $valorRendimentosTributaveisSobreRRA
   */
  public function setValorRendimentosTributaveisSobreRRA($valorRendimentosTributaveisSobreRRA) {

    $this->valorRendimentosTributaveisSobreRRA = $valorRendimentosTributaveisSobreRRA;
  }

  /**
   * @return float
   */
  public function getValorPrevidenciaSobreRRA() {

    return $this->valorPrevidenciaSobreRRA;
  }

  /**
   * @param float $valorPrevidenciaSobreRRA
   */
  public function setValorPrevidenciaSobreRRA($valorPrevidenciaSobreRRA) {

    $this->valorPrevidenciaSobreRRA = $valorPrevidenciaSobreRRA;
  }

  /**
   * @return float
   */
  public function getValorPensaoSobreRRA() {

    return $this->valorPensaoSobreRRA;
  }

  /**
   * @param float $valorPensaoSobreRRA
   */
  public function setValorPensaoSobreRRA($valorPensaoSobreRRA) {

    $this->valorPensaoSobreRRA = $valorPensaoSobreRRA;
  }

  /**
   * @return float
   */
  public function getValorIRRFSobreRRA() {

    return $this->valorIRRFSobreRRA;
  }

  /**
   * @param float $valorIRRFSobreRRA
   */
  public function setValorIRRFSobreRRA($valorIRRFSobreRRA) {

    $this->valorIRRFSobreRRA = $valorIRRFSobreRRA;
  }

  /**
   * @return float
   */
  public function getValorDespesaDaAcao() {

    return $this->valorDespesaDaAcao;
  }

  /**
   * @param float $valorDespesaDaAcao
   */
  public function setValorDespesaDaAcao($valorDespesaDaAcao) {

    $this->valorDespesaDaAcao = $valorDespesaDaAcao;
  }

  /**
   * @return float
   */
  public function getQuantidadeDeMeses() {

    return $this->quantidadeDeMeses;
  }

  /**
   * @param float $quantidadeDeMeses
   */
  public function setQuantidadeDeMeses($quantidadeDeMeses) {

    $this->quantidadeDeMeses = $quantidadeDeMeses;
  }

  /**
   * @return float
   */
  public function getValorIsencaoSobreRRA() {

    return $this->valorInsencaoSbreRRA;
  }

  /**
   * @param float $valorInsencaoSbreRRA
   */
  public function setValorIsencaoSobreRRA($valorInsencaoSbreRRA) {

    $this->valorInsencaoSbreRRA = $valorInsencaoSbreRRA;
  }

  /**
   * Retorna o valor calculado do 13 para o comprovante de redinmentos
   * @return float|int
   */
  public function getValorDecimoTerceiroParaComprovante() {

    $n13Salario = ($this->getValorTotalRendimentoDecimoTerceiro()
                   - $this->getValorPrevidenciaOficialDecimoTerceiro()
                   - $this->getValorPrevidenciaPrivadaDecimoTerceiro()
                   - $this->getValorDependentesDecimoTerceiro()
                   - $this->getValorPagoEmPensaoDecimoTerceiro()
                   - $this->getValorPagoIRRFDecimoTerceiro()
    );

    if ($n13Salario < 0) {
      $n13Salario = 0;
    }
    return $n13Salario;
  }

  /**
   * Retorna o valor total da molestia grave
   * @return float
   */
  public function getValorTotalMolestiaGrave() {

    $nValor = $this->getValorMolestiaGraveAtivos()+$this->getValorMolestiaGraveAtivosDecimoTerceiro()
            + $this->getValorDescontoMolestiaGraveInativos() + $this->getValorDescontoMolestiaGraveInativosDecimoTerceiro();

    return $nValor;
  }

  public function setOutrasInformacoes($outrasInformacoes) {
    $this->outras_informacoes = $outrasInformacoes;
  }

  public function getOutrasInformacoes() {
    return $this->outras_informacoes;
  }

  /**
   * @return string
   */
  public function getNomeFontePagadora() {

    return $this->nomeFontePagadora;
  }

  /**
   * @param string $nomeFontePagadora
   */
  public function setNomeFontePagadora($nomeFontePagadora) {

    $this->nomeFontePagadora = $nomeFontePagadora;
  }


}