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

namespace ECidade\Tributario\Agua\EmissaoCarnes;

use AguaEmissao;
use AguaContrato;
use regraEmissao;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use ECidade\Tributario\Agua\DebitoConta\DebitoContaStatus;

class Parcial {

  /**
   * @var int
   */
  private $iCodigoContrato;

  /**
   * @var AguaContrato
   */
  private $oContrato;

  /**
   * @var int
   */
  private $iMesInicial;

  /**
   * @var int
   */
  private $iMesFinal;

  /**
   * @var int
   */
  private $iAno;

  /**
   * @var AguaEmissao
   */
  private $oAguaEmissao;

  /**
   * @var regraEmissao
   */
  private $oRegraEmissao;

  /**
   * @var int
   */
  private $iCodigoInstituicao;

  /**
   * @var int
   */
  private $iCodigoTipoArrecadacao;

  /**
   * @var int
   */
  private $iContador;

  /**
   * @var int
   */
  private $iContadorLogradouro;

  /**
   * @var \DateTime
   */
  private $oDataEmissao;

  /**
   * @var resource
   */
  private $oInformacoesEmissao;

  /**
   * @return \DateTime
   */
  public function getDataEmissao() {
    return $this->oDataEmissao;
  }

  /**
   * @param \DateTime $oDataEmissao
   */
  public function setDataEmissao(\DateTime $oDataEmissao) {
    $this->oDataEmissao = $oDataEmissao;
  }

  /**
   * @return int
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param int $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return int
   */
  public function getCodigoContrato() {
    return $this->iCodigoContrato;
  }

  /**
   * @param int $iCodigoContrato
   */
  public function setCodigoContrato($iCodigoContrato) {
    $this->iCodigoContrato = $iCodigoContrato;
  }

  /**
   * @return int
   */
  public function getMesInicial() {
    return $this->iMesInicial;
  }

  /**
   * @param int $iMesInicial
   */
  public function setMesInicial($iMesInicial) {
    $this->iMesInicial = $iMesInicial;
  }

  /**
   * @return int
   */
  public function getMesFinal() {
    return $this->iMesFinal;
  }

  /**
   * @param int $iMesFinal
   */
  public function setMesFinal($iMesFinal) {
    $this->iMesFinal = $iMesFinal;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @param regraEmissao $oRegraEmissao
   */
  public function setRegraEmissao(\regraEmissao $oRegraEmissao) {
    $this->oRegraEmissao = $oRegraEmissao;
  }

  /**
   * @return regraEmissao
   */
  public function getRegraEmissao() {
    return $this->oRegraEmissao;
  }

  /**
   * @return AguaEmissao
   */
  public function getAguaEmissao() {
    return $this->oAguaEmissao;
  }

  /**
   * @param AguaEmissao $oAguaEmissao
   */
  public function setAguaEmissao(AguaEmissao $oAguaEmissao) {
    $this->oAguaEmissao = $oAguaEmissao;
  }

  /**
   * @return AguaContrato
   */
  public function getContrato() {

    if ($this->iCodigoContrato && !$this->oContrato) {
      $this->oContrato = new AguaContrato($this->iCodigoContrato);
    }

    return $this->oContrato;
  }

  /**
   * @param AguaContrato $oContrato
   */
  public function setContrato(AguaContrato $oContrato) {

    $this->oContrato = $oContrato;
    $this->iCodigoContrato = $oContrato->getCodigo();
  }

  /**
   * @param $iCodigoTipoArrecadacao
   */
  public function setCodigoTipoArrecadacao($iCodigoTipoArrecadacao) {
    $this->iCodigoTipoArrecadacao = $iCodigoTipoArrecadacao;
  }

  /**
   * @return int
   */
  public function getCodigoTipoArrecadacao() {
    return $this->iCodigoTipoArrecadacao;
  }

  /**
   * @return int
   */
  public function getContador() {
    return $this->iContador;
  }

  /**
   * @param int $iContador
   */
  public function setContador($iContador) {
    $this->iContador = $iContador;
  }

  /**
   * @return int
   */
  public function getContadorLogradouro() {
    return $this->iContadorLogradouro;
  }

  /**
   * @param int $iContadorLogradouro
   */
  public function setContadorLogradouro($iContadorLogradouro) {
    $this->iContadorLogradouro = $iContadorLogradouro;
  }

  /**
   * @param $oInformacoes
   */
  public function setInformacoesEmissao($oInformacoes) {
    $this->oInformacoesEmissao = $oInformacoes;
  }

  /**
   * @return resource
   */
  public function getInformacoesEmissao() {
    return $this->oInformacoesEmissao;
  }

  /**
   * @throws \ParameterException
   */
  private function validarParametros() {

    if (!$this->getAno()) {
      throw new \ParameterException('Ano não informado.');
    }

    if (!$this->getMesInicial() || !$this->getMesFinal()) {
      throw new \ParameterException('Mês Inicial/Final não informados.');
    }

    if ($this->getMesInicial() > $this->getMesFinal()) {
      throw new \ParameterException('Mês Inicial não pode ser maior que Mês Final.');
    }

    if (!$this->getAguaEmissao()) {
      throw new \ParameterException('Model de emissão não informado.');
    }

    if (!$this->getRegraEmissao()) {
      throw new \ParameterException('Regra de emissão não informada.');
    }

    if (!$this->getInformacoesEmissao()) {
      throw new \ParameterException('Informações de Emissão não informados.');
    }

    if (!$this->getCodigoInstituicao()) {
      throw new \ParameterException('Código da instituição não informado.');
    }

    if (!$this->getCodigoTipoArrecadacao()) {
      throw new \ParameterException('Código de Tipo de Arrecadação não informado.');
    }

    if (!$this->getCodigoContrato()) {
      throw new \ParameterException('Código do contrato não informado.');
    }

    if (!$this->getDataEmissao()) {
      throw new \ParameterException('Data de Emissão não informada.');
    }
  }

  /**
   * @param \stdClass $oInformacoesContrato
   * @return string
   */
  private function formatarEnderecoEntrega(\stdClass $oInformacoesContrato) {

    return sprintf(
      '%s %s %s %s %s %s %s',
      $oInformacoesContrato->entrega_tipo_logradouro,
      trim($oInformacoesContrato->entrega_nome_logradouro) . ',',
      'Nro ' . $oInformacoesContrato->entrega_numero,
      trim($oInformacoesContrato->entrega_orientacao),
      trim($oInformacoesContrato->entrega_complemento),
      '- ' . trim($oInformacoesContrato->entrega_bairro),
      '/ Bagé - RS'
    );
  }

  /**
   * @param $nValorTotal
   * @return string
   */
  private function formatarCodigoBarras($nValorTotal) {
    return db_formatar(
      str_replace('.', '', str_pad(
        number_format($nValorTotal, 2, "", "."), 11, "0", STR_PAD_LEFT))
      , 's', '0', 11, 'e'
    );
  }

  /**
   * @param \stdClass $oInformacoesContrato
   * @return string
   */
  private function formatarZonaEntrega(\stdClass $oInformacoesContrato) {

    return sprintf(
      'ENTREGA: %s - %s/%s',
      str_pad($oInformacoesContrato->entrega_zona, 4, '0', STR_PAD_LEFT),
      trim($oInformacoesContrato->denominacao),
      trim($oInformacoesContrato->localizacao)
    );
  }

  /**
   * @param \stdClass $oInformacoesContrato
   * @return \stdClass
   * @throws \BusinessException
   */
  private function processarInformacoes(\stdClass $oInformacoesContrato) {

    $this->validarParametros();

    $this->oAguaEmissao->setCodigoInstituicao($this->iCodigoInstituicao);

    $oContrato = $this->getContrato();

    $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca(
      $this->oRegraEmissao->getConvenio()
    );

    /**
     * Informações de Entrega
     */
    if ($oInformacoesContrato->orientacao == "0" || $oInformacoesContrato->orientacao == '-') {
      $oInformacoesContrato->orientacao = '';
    }

    /**
     * Endereço de Entrega
     */
    $oInformacoesContrato->endereco_entrega = $this->formatarEnderecoEntrega($oInformacoesContrato);

    /**
     * Zona de Entrega
     */
    $oInformacoesContrato->zona_entrega = $this->formatarZonaEntrega($oInformacoesContrato);
    $oInformacoesContrato->natureza = 'Água e Esgoto';

    if ($oInformacoesContrato->entrega_codigo) {

      $oInformacoesContrato->codigo_logradouro = $oInformacoesContrato->entrega_codigo_logradouro;
      $oInformacoesContrato->bairro = $oInformacoesContrato->entrega_bairro;
    }

    $oHidrometro = $oContrato->getHidrometro();

    $oRegistro = $oInformacoesContrato;
    $oRegistro->codigo_hidrometro = $oHidrometro->getNumero();
    $oRegistro->meses             = array();
    $oRegistro->has_debito_conta  = false;

    $oArrecadacao = $this->oAguaEmissao->getTipoArrecadacao(
      $this->iCodigoTipoArrecadacao,
      $this->iCodigoInstituicao
    );

    $this->oAguaEmissao->preencherTabelaTemporaria(
      $oRegistro->codigo_responsavel,
      $this->iCodigoInstituicao
    );

    for ($iMes = $this->iMesInicial; $iMes <= $this->iMesFinal; $iMes++) {

      $oMes = (object) array(
        'mes'                        => $iMes,
        'referencia'                 => null,
        'vencimento'                 => null,
        'recibo'                     => (object) array(),
        'codigo_cobranca'            => null,
        'debitos'                    => array(),
        'leituras'                   => array(),
        'leitura_atual'              => null,
        'leitura_anterior'           => null,
        'consumo'                    => 0,
        'dias_entre_leituras'        => 30,
        'codigo_declaracao_quitacao' => null,
        'data_inicial_declaracao'    => null,
        'data_final_declaracao'      => null,
        'mensagem_1'                 => null,
        'mensagem_2'                 => null,
        'mensagem_3'                 => null,
        'mensagem_debito_conta_1'    => null,
        'mensagem_debito_conta_2'    => null,
        'mensagem_debitos'           => null,
        'contador'                   => null,
        'data_leitura_atual'         => null,
        'data_leitura_anterior'      => null,
      );

      $oMes->contador = $this->getContadorFormatado();
      $this->iContador++;
      $this->iContadorLogradouro++;

      $aDebitosVencidos = $this->getDebitosVencidos($this->oDataEmissao->format('Y-m-d'));
      $oMes->mensagem_debitos = $this->getMensagemCarne($aDebitosVencidos);

      $sDataVencimento = $oContrato->getDataVencimento($this->iAno, $iMes);

      /**
       * Obtem Débitos/Receitas para o Mês e Exercício de Emissão
       */
      $oMes->debitos = $this->oAguaEmissao->getDebitos($oRegistro, $iMes, $this->iAno, $this->iCodigoTipoArrecadacao, $sDataVencimento);

      /**
       * Valor Total do Recibo
       */
      $nValorTotal = array_reduce($oMes->debitos, function ($nValor, $aDebito) {
        $nValor += $aDebito['valor'];
        return $nValor;
      }, 0);

      if (!$oMes->debitos) {
        throw new \BusinessException('Nenhum débito deste contrato foi encontrado.');
      }

      if ($nValorTotal <= 0) {
        throw new \BusinessException('Valor Total zerado para a Emissão do Carnê.');
      }

      /**
       * Gera Recibo
       */
      $oRecibo = new \recibo(\recibo::TIPOEMISSAO_RECIBO_CGF, null, 29); // todo: verificar o que é 29

      $sCodigoCobranca = null;
      foreach ($oMes->debitos as $aDebito) {

        if ($sCodigoCobranca != $aDebito['codigo_cobranca']) {

          $oRecibo->addNumpre($aDebito['codigo_cobranca'], $aDebito['parcela']);
          $oMes->mensagem_parcelamento = $this->getMensagemParcelamento($aDebito['codigo_cobranca'], $aDebito['parcela']);
        }
        $sCodigoCobranca = $aDebito['codigo_cobranca'];
      }

      /**
       * Emissão do Recibo
       */
      $oRecibo->setDataRecibo($sDataVencimento);
      $oRecibo->setDataVencimentoRecibo($sDataVencimento);
      $oRecibo->emiteRecibo($lConvenioCobrancaValido);

      if ($lConvenioCobrancaValido) {
        CobrancaRegistrada::adicionarRecibo($oRecibo, $this->oRegraEmissao->getConvenio());
      }

      $oMes->codigo_cobranca = $oRecibo->getNumpreRecibo();
      $oDataReferencia = new \DateTime("{$this->iAno}-{$iMes}-01");
      $oMes->leituras = $this->oAguaEmissao->getUltimasLeituras($this->iCodigoContrato, $oDataReferencia);

      /**
       * Declaração de Quitação
       */
      $oMes->codigo_declaracao_quitacao = $this->registrarEnvioDeclaracaoQuitacao(
        $oRegistro->codigo_matricula, $oMes->codigo_cobranca, $iMes
      );
      $oMes->data_inicial_declaracao = "01/01/{$this->iAno}";
      $oMes->data_final_declaracao = "31/12/{$this->iAno}";

      $oDataAtual = null;
      if (count($oMes->leituras)) {

        $aLeituraAtual = current($oMes->leituras);
        $oMes->leitura_atual = $aLeituraAtual;
        $oDataAtual = new \DateTime($oMes->leitura_atual['data']);
        $oMes->data_leitura_atual = $oDataAtual->format('d/m/Y');
        $oMes->consumo = $aLeituraAtual['consumo'];
      }

      $oDataAnterior = null;
      if (count($oMes->leituras) > 1) {

        $oMes->leitura_anterior = next($oMes->leituras);
        $oDataAnterior = new \DateTime($oMes->leitura_anterior['data']);
        $oMes->data_leitura_anterior = $oDataAnterior->format('d/m/Y');
      }

      if ($oMes->leitura_atual && $oMes->leitura_anterior) {

        $oDataIntervalo = $oDataAtual->diff($oDataAnterior);
        $oMes->dias_entre_leituras = $oDataIntervalo->format('%a');
      }

      $oMes->media_consumo_diario = 0;
      if ($oMes->consumo && $oMes->dias_entre_leituras) {
        $oMes->media_consumo_diario = round($oMes->consumo / $oMes->dias_entre_leituras, 2);
      }

      $oMes->recibo = $oRecibo;
      $oMes->valor_codigo_barra = $this->formatarCodigoBarras($nValorTotal);
      $oMes->referencia = \DBDate::getMesExtenso($iMes) . '/' . $this->getAno();
      $oDataVencimento = new \DateTime($oRecibo->getDataVencimento());
      $oMes->vencimento = $oDataVencimento->format(\DBDate::DATA_PTBR);

      /**
       * Dados do Convencio
       */
      $oConvenio = new \convenio(
        $this->oRegraEmissao->getConvenio(),
        $oMes->codigo_cobranca,
        '000',
        $nValorTotal,
        $oMes->valor_codigo_barra,
        $oMes->vencimento,
        $oArrecadacao->k00_tercdigrecnormal
      );

      /**
       * Informações do Carnê
       */
      $oMes->codigo_barras          = $oConvenio->getCodigoBarra();
      $oMes->linha_digitavel        = $oConvenio->getLinhaDigitavel();
      $oMes->nosso_numero           = $oConvenio->getNossoNumero();
      $oMes->agencia_codigo_cedente = $oConvenio->getAgenciaCedente();
      $oMes->carteira               = $oConvenio->getCarteira();
      $oMes->valor_acrescimo        = 0;
      $oMes->valor_desconto         = 0;
      $oMes->valor                  = $nValorTotal;
      $oMes->codigo_cobranca        = $oMes->codigo_cobranca . '000';

      /**
       * Mensagens do Carnê
       */
      $oMes->mensagem_1 = $oArrecadacao->k00_hist1;
      $oMes->mensagem_2 = $oArrecadacao->k00_hist2;
      $oMes->mensagem_3 = $oArrecadacao->k00_hist3;

      if ($this->hasDebitoConta($oContrato, $oRegistro->codigo_economia)) {

        $oRegistro->has_debito_conta = true;
        $oMes->mensagem_debito_conta_1 = $oArrecadacao->k00_hist7;
        $oMes->mensagem_debito_conta_2 = $oArrecadacao->k00_hist8;
        $oMes->codigo_barras = null;
        $oMes->linha_digitavel = null;
      }

      $oRegistro->meses[$iMes] = $oMes;
    }

    return $oRegistro;
  }

  /**
   * @return \stdClass
   * @throws \BusinessException
   */
  public function emitir() {
    return $this->processarInformacoes($this->oInformacoesEmissao);
  }

  /**
   * @param $iCodigoMatricula
   * @return bool
   * @throws \DBException
   */
  private function hasDebitoConta(AguaContrato $oContrato, $iEconomia = null) {

    $aWhere = array(
      "d63_status = ". DebitoContaStatus::ATIVO,
      "d63_instit = {$this->iCodigoInstituicao}",
      "d66_arretipo = {$this->iCodigoTipoArrecadacao}",
    );

    if ($oContrato->isPagamentoEconomia()) {
      $aWhere[] = "d82_economia = {$iEconomia}";
    } else {
      $aWhere[] = "d81_contrato = {$oContrato->getCodigo()}";
    }

    $sWhere = implode(' and ', $aWhere);
    $sSql  = "select d63_codigo ";
    $sSql .= "  from debcontapedido ";
    $sSql .= "       inner join debcontapedidotipo                on d66_codigo = d63_codigo ";
    $sSql .= "       left join debcontapedidoaguacontrato         on d81_codigo = d63_codigo ";
    $sSql .= "       left join debcontapedidoaguacontratoeconomia on d82_codigo = d63_codigo ";
    $sSql .= " where {$sWhere} ";
    $sSql .= " limit 1 ";

    $rsResultado = db_query($sSql);
    if (!$rsResultado) {
      throw new \DBException('Não foi possível verificar débito em conta.');
    }

    return pg_num_rows($rsResultado) !== 0;
  }

  /**
   * @param $iCodigoMatricula
   * @param $iCodigoCobranca
   * @param $iParcela
   * @return bool
   * @throws \DBException
   */
  public function registrarEnvioDeclaracaoQuitacao($iCodigoMatricula, $iCodigoCobranca, $iParcela) {

    $oDaoDeclaracao = new \cl_declaracaoquitacaocarneagua;
    $sSqlDeclaracoes = $oDaoDeclaracao->sql_declaracao_debito_carne(
      $iCodigoMatricula,
      'ar30_sequencial, ar30_exercicio',
      'ar30_exercicio asc limit 1'
    );

    $rsDeclaracoes = db_query($sSqlDeclaracoes);
    if (!$rsDeclaracoes) {
      throw new \DBException('Não foi possível encontrar as informações de Declaração de Quitação.');
    }

    if (pg_num_rows($rsDeclaracoes)) {

      $oDeclaracao = pg_fetch_object($rsDeclaracoes);

      $oDaoDeclaracao->ar41_declaracaoquitacao = $oDeclaracao->ar30_sequencial;
      $oDaoDeclaracao->ar41_numpre = $iCodigoCobranca;
      $oDaoDeclaracao->ar41_numpar = 1;
      $oDaoDeclaracao->ar41_anoemissao = $this->iAno;
      $oDaoDeclaracao->ar41_mesemissao = $iParcela;
      $oDaoDeclaracao->incluir(null);

      if ($oDaoDeclaracao->erro_status == '0') {
        throw new \DBException('Não foi possível incluir registro de envio de declaração de quitação.');
      }

      return $oDaoDeclaracao->ar41_sequencial;
    }

    return null;
  }

  /**
   * @param $iCodigoCobranca
   * @param $iParcela
   * @return null|string
   * @throws \DBException
   */
  private function getMensagemParcelamento($iCodigoCobranca, $iParcela) {

    $sSql = "
      select
        v07_numpre
      from arrecad
        inner join termo on termo.v07_numpre = arrecad.k00_numpre
      where
        arrecad.k00_numpre = {$iCodigoCobranca}
        and termo.v07_desconto = 22
        and (termo.v07_totpar - 1) = {$iParcela}
      limit 1
    ";

    $rsParcelamento = db_query($sSql);
    if (!$rsParcelamento) {
      throw new \DBException('Não foi possível encontras as inforamções de parcelamento.');
    }

    $sMensagem = 'Solicitamos seu comparecimento no Setor de Cadatro e Atendimento para reparcelamento de débitos pendentes.';
    if (!pg_num_rows($rsParcelamento)) {
      $sMensagem = null;
    }

    return $sMensagem;
  }

  /**
   * @param  array $aDebidosVencidos
   * @return string
   */
  private function getMensagemCarne($aDebidosVencidos) {

    $sMensagem = '';

    if (!$aDebidosVencidos) {
      return 'Obrigado pela pontualidade!';
    }

    foreach ($aDebidosVencidos as $aDebito) {

      if ($aDebito['total'] >= 2) {

        $sMensagem = "AVISO DE SUSPENSÃO DO FORNECIMENTO DE ÁGUA: Fica o usuário avisado que a não regularização dos".
          " débitos do imóvel no prazo de 30 (trinta) dias, a contar do vencimento da segunda parcela em ".
          "atraso, acarretará na suspensão do fornecimento de água (art. 40, V, §2º da Lei n.º 11.445/07).";
        break;
      }

      /**
       * Debitos de Dívida Ativa
       */
      if (in_array($aDebito['k03_tipo'], array(5, 18))) {

        if (!$sMensagem) {
          $sMensagem = 'Imóvel possui Dívida Ativa';
        } else {
          $sMensagem .= " / Dívida Ativa";
        }

        continue;
      }

      /**
       * Debitos de Parcelamento
       */
      if (in_array($aDebito['k03_tipo'], array(6, 13))) {

        if (!$sMensagem) {
          $sMensagem = 'Imóvel possui Parcelamento em Atraso';
        } else {
          $sMensagem .= " / Parecelamento em Atraso";
        }

        continue;
      }

      /**
       * Debitos do Exercício
       */
      if ($aDebito['k03_tipo'] == 20) {

        if (!$sMensagem) {
          $sMensagem = 'Imóvel possui Débito no Exercício';
        } else {
          $sMensagem .= " / Débito no Exercicio";
        }

        continue;
      }

      if (!$sMensagem) {
        $sMensagem = 'Imóvel possui Débitos em Atraso';
      } else {
        $sMensagem .= " / Outros Débitos em Atraso";
      }

      continue;
    }

    return $sMensagem;
  }

  /**
   * @param  string $sDataAtual
   * @return array
   * @throws \DBException
   */
  private function getDebitosVencidos($sDataAtual) {

    $sCampos = implode(',', array(
      'arrecad.k00_tipo',
      'arretipo.k03_tipo',
      'count(distinct arrecad.k00_numpar) as total',
    ));

    $sJoin = implode(' ', array(
      'inner join arretipo   on arretipo.k00_tipo  = arrecad.k00_tipo',
    ));

    $sWhere = implode(' and ', array(
      "arrecad.k00_dtvenc < '{$sDataAtual}'",
    ));

    $sGroupBy = implode(', ', array(
      "arrecad.k00_tipo",
      "arretipo.k03_tipo",
    ));

    $sSql = "select {$sCampos} from tmp_arrecad_emissao as arrecad {$sJoin} where {$sWhere} group by {$sGroupBy} order by total desc";

    $rsDebitosVencidos = db_query($sSql);
    if (!$rsDebitosVencidos) {
      throw new \DBException('Não foi possível encontrar as inforamções de débitos vencidos.');
    }

    return pg_fetch_all($rsDebitosVencidos);
  }

  /**
   * @return string
   */
  private function getContadorFormatado() {

    return sprintf(
      'L%s/G%s',
      str_pad($this->iContadorLogradouro, 5, "0", STR_PAD_LEFT),
      str_pad($this->iContador, 5, "0", STR_PAD_LEFT)
    );
  }
}
