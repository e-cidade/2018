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
 * Class ListaClassificacaoCredor
 */
class ListaClassificacaoCredor {

  const DISPENSA_MANUAL = 4;

  const DIAS_UTEIS = 1;
  const DIAS_CORRIDOS = 2;
  const MENSAGENS = 'financeiro.empenho.ClassificacaoCredor.';

  /**
   * Código
   * @var int
   */
  private $iCodigo;

  /**
   * Descrição
   * @var string
   */
  private $sDescricao;

  /**
   * Máximo de Dias permitidos para vencimento a partir da data de recebimento
   * @var int
   */
  private $iDiasVencimento;

  /**
   * Tipo de Contagem de Dias - Uteis / Corridos
   * @var int
   */
  private $iContagemDias;

  /**
   * Valor Inicial
   * @var number
   */
  private $nValorInicial;

  /**
   * Valor Final
   * @var number
   */
  private $nValorFinal;

  /**
   * Dispensa ordem de pagamento
   * @var boolean
   */
  private $lDispensa;

  /**
   * Ordem de Prioridade
   * @var int
   */
  private $iOrdem;

  /**
   * @var ClassificacaoCredorConta[]
   */
  private $aContas = array();

  /**
   * Coleção de Recurso do Orçamento
   * @var Recurso[]
   */
  private $aRecurso = array();

  /**
   * Tipos de compras do empenho
   * @var TipoCompra[]
   */
  private $aTipoCompra = array();

  /**
   * Tipos de Eventos do empenho
   * @var TipoPrestacaoConta[]
   */
  private $aEvento = array();

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return int
   */
  public function getDiasVencimento() {
    return $this->iDiasVencimento;
  }

  /**
   * @param int $iDiasVencimento
   */
  public function setDiasVencimento($iDiasVencimento) {
    $this->iDiasVencimento = $iDiasVencimento;
  }

  /**
   * @return number
   */
  public function getValorInicial() {
    return $this->nValorInicial;
  }

  /**
   * @param number $nValorInicial
   */
  public function setValorInicial($nValorInicial) {
    $this->nValorInicial = $nValorInicial;
  }

  /**
   * @return number
   */
  public function getValorFinal() {
    return $this->nValorFinal;
  }

  /**
   * @param number $nValorFinal
   */
  public function setValorFinal($nValorFinal) {
    $this->nValorFinal = $nValorFinal;
  }

  /**
   * @return boolean
   */
  public function dispensa() {
    return $this->lDispensa;
  }

  /**
   * @param boolean $lDispensa
   */
  public function setDispensa($lDispensa) {
    $this->lDispensa = $lDispensa;
  }

  /**
   * @return ClassificacaoCredorConta[]
   * @throws Exception
   */
  public function getContas() {

    if (!empty($this->iCodigo) && empty($this->aContas)) {

      $oDaoConta      = new cl_classificacaocredoreselemento();
      $sSqlBuscaConta = $oDaoConta->sql_query_file(null, "*", null, "cc32_classificacaocredores = {$this->iCodigo}");
      $rsBuscaConta   = db_query($sSqlBuscaConta);
      if (!$rsBuscaConta) {
        throw new Exception("Ocorreu um erro ao buscar as contas vinculadas a lista de classificação.");
      }
      for ($iRow = 0; $iRow < pg_num_rows($rsBuscaConta); $iRow++) {

        $oStdConta = db_utils::fieldsMemory($rsBuscaConta, $iRow);
        $oContaOrcamento = ContaOrcamentoRepository::getContaByCodigo($oStdConta->cc32_codcon, $oStdConta->cc32_anousu);
        $oContaClassificacao = new ClassificacaoCredorConta();
        $oContaClassificacao->setContaExclusao($oStdConta->cc32_exclusao == 't');
        $oContaClassificacao->setContaOrcamento($oContaOrcamento);
        $this->adicionarConta($oContaClassificacao);
      }
    }
    return $this->aContas;
  }

  /**
   * @param ClassificacaoCredorConta $oConta
   */
  public function adicionarConta(ClassificacaoCredorConta $oConta) {
    $this->aContas[] = $oConta;
  }

  /**
   * @return Recurso[]
   * @throws Exception
   */
  public function getRecurso() {

    if (!empty($this->iCodigo) && empty($this->aRecurso)) {

      $oDaoRecurso    = new cl_classificacaocredoresrecurso();
      $sSqlRecurso    = $oDaoRecurso->sql_query_file(null, "*", null, "cc33_classificacaocredores = {$this->iCodigo}");
      $rsBuscaRecurso = db_query($sSqlRecurso);
      if (!$rsBuscaRecurso) {
        throw new Exception("Ocorreu um erro ao buscar os recursos vinculados a lista de classificação.");
      }
      for ($iRow = 0; $iRow < pg_num_rows($rsBuscaRecurso); $iRow++) {
        $this->adicionarRecurso(new Recurso(db_utils::fieldsMemory($rsBuscaRecurso, $iRow)->cc33_orctiporec));
      }
    }
    return $this->aRecurso;
  }

  /**
   * @param Recurso $oRecurso
   */
  public function adicionarRecurso(Recurso $oRecurso) {
    $this->aRecurso[$oRecurso->getCodigo()] = $oRecurso;
  }

  /**
   * @return TipoCompra[]
   * @throws Exception
   */
  public function getTipoCompra() {

    if (!empty($this->iCodigo) && empty($this->aTipoCompra)) {

      $oDaoTipoCompra  = new cl_classificacaocredorestipocompra();
      $sSqlTipoCompra  = $oDaoTipoCompra->sql_query_file(null, "*", null, "cc34_classificacaocredores = {$this->iCodigo}");
      $rsBuscaTipoCompra = db_query($sSqlTipoCompra);
      if (!$rsBuscaTipoCompra) {
        throw new Exception("Ocorreu um erro ao buscar os tipos de compras vinculados a lista de classificação.");
      }
      for ($iRow = 0; $iRow < pg_num_rows($rsBuscaTipoCompra); $iRow++) {
        $this->adicionarTipoCompra(new TipoCompra(db_utils::fieldsMemory($rsBuscaTipoCompra, $iRow)->cc34_pctipocompra));
      }
    }
    return $this->aTipoCompra;
  }

  /**
   * @param TipoCompra $oTipoCompra
   */
  public function adicionarTipoCompra(TipoCompra $oTipoCompra) {
    $this->aTipoCompra[$oTipoCompra->getCodigo()] = $oTipoCompra;
  }

  /**
   * @return TipoPrestacaoConta[]
   * @throws Exception
   */
  public function getEvento() {

    if (!empty($this->iCodigo) && empty($this->aEvento)) {

      $oDaoEvento      = new cl_classificacaocredoresevento();
      $sSqlBuscaEvento = $oDaoEvento->sql_query_file(null, "*", null, "cc35_classificacaocredores = {$this->iCodigo}");
      $rsBuscaEvento   = db_query($sSqlBuscaEvento);
      if (!$rsBuscaEvento) {
        throw new Exception("Ocorreu um erro ao buscar os eventos vinculados a lista de classificação.");
      }
      for ($iRow = 0; $iRow < pg_num_rows($rsBuscaEvento); $iRow++) {
        $this->adicionarEvento(new TipoPrestacaoConta(db_utils::fieldsMemory($rsBuscaEvento, $iRow)->cc35_empprestatip));
      }
    }
    return $this->aEvento;
  }

  /**
   * @param TipoPrestacaoConta $oEvento
   */
  public function adicionarEvento(TipoPrestacaoConta $oEvento) {
    $this->aEvento[$oEvento->getCodigo()] = $oEvento;
  }

  /**
   * @return int
   */
  public function getContagemDias() {
    return $this->iContagemDias;
  }

  /**
   * @param int $iContagemDias
   */
  public function setContagemDias($iContagemDias) {
    $this->iContagemDias = $iContagemDias;
  }

  /**
   * @param $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * @return int
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * @return bool
   */
  public function diasUteis() {
    return $this->iContagemDias == self::DIAS_UTEIS;
  }

  /**
   * @return bool
   */
  public function diasCorridos() {
    return $this->iContagemDias == self::DIAS_CORRIDOS;
  }


  public function salvar($lSalvarVinculos = true) {

    $this->verificaObrigaDispensa();

    $oDaoClassificacaoCredor = new cl_classificacaocredores();
    $oDaoClassificacaoCredor->cc30_codigo         = $this->iCodigo;
    $oDaoClassificacaoCredor->cc30_descricao      = pg_escape_string($this->sDescricao);
    $oDaoClassificacaoCredor->cc30_diasvencimento = $this->iDiasVencimento;

    if (!empty($this->iContagemDias)) {
      $oDaoClassificacaoCredor->cc30_contagemdias = $this->iContagemDias;
    }
    if (!empty($this->nValorInicial)) {
      $oDaoClassificacaoCredor->cc30_valorinicial = $this->nValorInicial;
    }
    if (!empty($this->nValorFinal)) {
      $oDaoClassificacaoCredor->cc30_valorfinal = $this->nValorFinal;
    }

    $oDaoClassificacaoCredor->cc30_dispensa        = $this->dispensa() ? 'true' : 'false';
    $oDaoClassificacaoCredor->cc30_ordem           = $this->iOrdem;

    if (empty($oDaoClassificacaoCredor->cc30_codigo)) {

      $oDaoClassificacaoCredor->cc30_ordem = $this->getProximaOrdem();
      $oDaoClassificacaoCredor->incluir($oDaoClassificacaoCredor->cc30_codigo);
      $this->iCodigo = $oDaoClassificacaoCredor->cc30_codigo;
    } else {
      $oDaoClassificacaoCredor->alterar($oDaoClassificacaoCredor->cc30_codigo);
    }
    if ($oDaoClassificacaoCredor->erro_status == "0") {
      throw new Exception("Ocorreu um erro ao salvar os dados da Classificação de Credor.");
    }

    if($lSalvarVinculos) {

      $this->excluiRelacionamentos();
      $this->vincularElemento();
      $this->vincularRecurso();
      $this->vincularTipoCompra();
      $this->vincularEventos();
    }
  }

  private function excluiRelacionamentos() {

    $oDaoElemento = new cl_classificacaocredoreselemento();
    $oDaoElemento->excluir(null, "cc32_classificacaocredores = {$this->iCodigo}");
    if ($oDaoElemento->erro_status == '0') {
      throw new DBException('Não foi possível excluir o Elemento vinculado a lista.');
    }
    $oDaoRecurso = new cl_classificacaocredoresrecurso();
    $oDaoRecurso->excluir(null, "cc33_classificacaocredores = {$this->iCodigo}");
    if ($oDaoRecurso->erro_status == '0') {
      throw new DBException('Não foi possível excluir o Recurso vinculado a lista.');
    }
    $oDaoTipoCompra = new cl_classificacaocredorestipocompra();
    $oDaoTipoCompra->excluir(null, "cc34_classificacaocredores = {$this->iCodigo}");
    if ($oDaoTipoCompra->erro_status == '0') {
      throw new DBException('Não foi possível excluir o Tipo de Compra vinculado a lista.');
    }
    $oDaoEvento = new cl_classificacaocredoresevento();
    $oDaoEvento->excluir(null, "cc35_classificacaocredores = {$this->iCodigo}");
    if ($oDaoEvento->erro_status == '0') {
      throw new DBException('Não foi possível excluir o Evento vinculado a lista.');
    }
  }

  public function excluir() {

    if (!$this->iCodigo) {
      throw new ParameterException('A Lista não foi carregada.');
    }

    /*
     * Verifica se a lista está vinculada a um empenho
     */
    $oDaoClassificacaoCredorEmpenho = new cl_classificacaocredoresempenho();
    $sSqlBuscaEmpenho = $oDaoClassificacaoCredorEmpenho->sql_query(null, "*", null, "cc31_classificacaocredores = {$this->iCodigo}");
    $rsBuscaEmpenho   = db_query($sSqlBuscaEmpenho);
    if (!$rsBuscaEmpenho) {
      throw new Exception("Ocorreu um erro ao buscar os empenhos da lista de classificação.");
    }

    if(pg_num_rows($rsBuscaEmpenho) != 0){
      throw new Exception("Não é possível excluir esta lista, pois a mesma está vinculada a um empenho.");
    }

    /*
     * Exclui a lista e todos os seus relacionamentos
     */
    $this->excluiRelacionamentos();
    $oDaoClassificacaoCredor = new cl_classificacaocredores();
    $oDaoClassificacaoCredor->excluir($this->iCodigo);
    if ($oDaoClassificacaoCredor->erro_status == '0') {
      throw new DBException('Não foi possível excluir a Lista.');
    }

    return true;
  }

  /**
   * @return bool
   * @throws Exception
   */
  private function vincularElemento() {

    foreach ($this->aContas as $oContaContabil) {

      $oDaoElemento = new cl_classificacaocredoreselemento();
      $oDaoElemento->cc32_sequencial = null;
      $oDaoElemento->cc32_classificacaocredores = $this->iCodigo;
      $oDaoElemento->cc32_codcon     = $oContaContabil->getContaOrcamento()->getCodigo();
      $oDaoElemento->cc32_anousu     = $oContaContabil->getContaOrcamento()->getAno();
      $oDaoElemento->cc32_exclusao   = $oContaContabil->contaExclusao() ? 'true' : 'false';
      $oDaoElemento->incluir(null);
      if ($oDaoElemento->erro_status == "0") {
        throw new Exception("Não foi possível vincular as contas contábeis na lista de classificação de credor.");
      }
    }
    return true;
  }

  /**
   * @return bool
   * @throws Exception
   */
  private function vincularRecurso() {

    foreach ($this->aRecurso as $oRecurso) {

      $oDaoRecurso = new cl_classificacaocredoresrecurso();
      $oDaoRecurso->cc33_sequencial            = null;
      $oDaoRecurso->cc33_classificacaocredores = $this->iCodigo;
      $oDaoRecurso->cc33_orctiporec            = $oRecurso->getCodigo();
      $oDaoRecurso->incluir(null);
      if ($oDaoRecurso->erro_status == "0") {
        throw new Exception("Não foi possível vincular os recursos na lista de classificação de credor.");
      }
    }
    return true;
  }

  /**
   * @return bool
   * @throws Exception
   */
  private function vincularTipoCompra() {

    foreach ($this->aTipoCompra as $oTipoCompra) {

      $oDaoTipoCompra = new cl_classificacaocredorestipocompra();
      $oDaoTipoCompra->cc34_sequencial            = null;
      $oDaoTipoCompra->cc34_classificacaocredores = $this->iCodigo;
      $oDaoTipoCompra->cc34_pctipocompra          = $oTipoCompra->getCodigo();
      $oDaoTipoCompra->incluir(null);
      if ($oDaoTipoCompra->erro_status == "0") {
        throw new Exception("Não foi possível vincular os tipos de compra na lista de classificação de credor.");
      }
    }
    return true;
  }

  /**
   * @return bool
   * @throws Exception
   */
  private function vincularEventos() {

    foreach ($this->aEvento as $oEvento) {

      $oDaoEvento = new cl_classificacaocredoresevento();
      $oDaoEvento->cc35_sequencial            = null;
      $oDaoEvento->cc35_classificacaocredores = $this->iCodigo;
      $oDaoEvento->cc35_empprestatip          = $oEvento->getCodigo();
      $oDaoEvento->incluir(null);
      if ($oDaoEvento->erro_status == "0") {
        throw new Exception("Não foi possível vincular os eventos na lista de classificação de credor.");
      }
    }
    return true;
  }

  /**
   * Limpa a propriedade aEvento
   */
  public function limparEvento() {
    $this->aEvento = array();
  }

  /**
   * Limpa a propriedade aTipoCompra
   */
  public function limparTipoCompra() {
    $this->aTipoCompra = array();
  }

  /**
   * Limpa a propriedade aConta
   */
  public function limparElemento() {
    $this->aContas = array();
  }

  /**
   * Limpa a propriedade aRecurso
   */
  public function limparRecurso() {
    $this->aRecurso = array();
  }

  /**
   *
   * @param  DBDate $oData
   * @return DBDate
   */
  public function getDataVencimentoPorData(DBDate $oData) {

    if ($this->iContagemDias == self::DIAS_UTEIS) {
      /**
       * Soma 1 pois o método getIntervaloDiasUteis considera a própria data passada no cálculo
       */
      $sDataVencimento = db_stdClass::getIntervaloDiasUteis($oData->getTimeStamp(), $this->iDiasVencimento + 1);
    } else {

      $oDataReferencia = clone $oData;
      return $oDataReferencia->adiantarPeriodo($this->iDiasVencimento, 'd');
    }

    return new DBDate(date('Y-m-d', $sDataVencimento));
  }

  /**
   * Método que realiza a validação das datas necessárias para a nota do Empenho.
   *
   * @param DBDate $oDataNota        Data da nota.
   * @param DBDate $oDataRecebimento Data de recebimento da nota.
   * @param DBDate $oDataVencimento  Data de vencimento da nota.
   * @param string $sMensagem        Caminho da mensagem de validação da data de vencimento.
   *
   * @throws BusinessException caso alguma data seja inválida para o empenho informado.
   */
  public function validarDatas($oDataNota, $oDataRecebimento, $oDataVencimento = null, $sMensagem = null) {

    if ($oDataRecebimento->getTimeStamp() < $oDataNota->getTimeStamp()) {
      throw new BusinessException(_M(self::MENSAGENS . 'data_recebimento_menor'));
    }
    if ($oDataVencimento &&  $oDataVencimento->getTimeStamp() < $oDataRecebimento->getTimeStamp()) {
      throw new BusinessException(_M(self::MENSAGENS . 'data_vencimento_menor'));
    }

    $oDataLimite = null;
    if (!$this->dispensa()) {
      $oDataLimite = $this->getDataVencimentoPorData($oDataRecebimento);
    }

    $lTemDatas     = $oDataVencimento && $oDataLimite;
    $lDataInvalida = $lTemDatas ? $oDataVencimento->getTimeStamp() > $oDataLimite->getTimeStamp() : false;
    if ($lTemDatas && $lDataInvalida) {

      $sContagemDias = $this->iContagemDias == 1 ? 'úteis' : 'corridos'; 
      $aParametrosMensagem = array(
        'sClassificacao'  => $this->sDescricao,
        'iQuantidadeDias' => $this->iDiasVencimento,
        'sContagemDias'   => $sContagemDias
      );
      if ($sMensagem === null) {
        $sMensagem = 'data_vencimento_invalida';
      }
      throw new BusinessException(_M(self::MENSAGENS . $sMensagem, (object) $aParametrosMensagem));
    }
  }

  /**
   * Método que valida os parâmetros necessários para a nota do Empenho.
   *
   * @param string            $sDataNota         Data da nota.
   * @param string            $sDataRecebimento  Data de recebimento data nota.
   * @param string            $sDataVencimento   Data de vencimento da nota.
   * @param string            $sLocalRecebimento Local de recebimento da nota.
   *
   * @throws ParameterException caso algum valor seja inválido para o empenho informado.
   */
  public function validarParametros($sDataNota, $sDataRecebimento, $sDataVencimento, $sLocalRecebimento) {

    /**
     * @todo Verificar se é necessária verificação do código null
     */
    $lObrigaPreenchimento = $this->getCodigo() != null && !$this->dispensa();

    if (empty($sDataNota)) {
      throw new ParameterException('O campo Data da Nota é de preeenchimento obrigatório.');
    }

    if (empty($sDataRecebimento)) {
      throw new ParameterException('O campo Data de Recebimento é de preenchimento obrigatório.');
    }

    if ($lObrigaPreenchimento && empty($sDataVencimento)) {
      throw new ParameterException('O campo Data de Vencimento é de preenchimento obrigatório.');
    }

    if ($lObrigaPreenchimento && empty($sLocalRecebimento)) {
      throw new ParameterException('O campo Local de Recebimento é de preenchimento obrigatório.');
    }
  }

  /**
   * Faz o vínculo entre o Empenho e a Lista de Classificação de Credores, realizando as validações necessárias.
   * @param EmpenhoFinanceiro $oEmpenho
   * @param boolean           $lDispensaManual
   * @param string            $sJustificativa
   *
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function vincularEmpenhoEmClassificacao(EmpenhoFinanceiro $oEmpenho, $lDispensaManual = false, $sJustificativa = null) {

    if ($lDispensaManual && empty($sJustificativa)) {
      throw new BusinessException('O campo Justificativa é de preenchimento obrigatório.');
    }

    if (!$lDispensaManual) {
      $sJustificativa = null;
    }

    $oDaoClassificacaoEmpenho = new cl_classificacaocredoresempenho();
    $oDaoClassificacaoEmpenho->excluir(null, "cc31_empempenho = {$oEmpenho->getNumero()}");

    $oDaoClassificacaoEmpenho->cc31_sequencial            = null;
    $oDaoClassificacaoEmpenho->cc31_justificativa         = $sJustificativa;
    $oDaoClassificacaoEmpenho->cc31_classificacaocredores = $this->getCodigo();
    $oDaoClassificacaoEmpenho->cc31_empempenho            = $oEmpenho->getNumero();
    $oDaoClassificacaoEmpenho->incluir(null);
    if ($oDaoClassificacaoEmpenho->erro_status == "0") {
      throw new DBException("Houve um erro ao tentar incluir o vínculo entre Empenho e a Classificação do Credor.");
    }
    return true;
  }

  /**
   * Busca o número da próxima ordem de acordo com os registros no banco.
   * @return int
   * @throws DBException
   */
  private static function getProximaOrdem() {

    $sCampos = "max(cc30_ordem) + 1 as nova_ordem";

    $oDaoClassificao  = new cl_classificacaocredores();
    $sSqlProximaOrdem = $oDaoClassificao->sql_query_file(null, $sCampos);
    $rsProximaOrdem   = db_query($sSqlProximaOrdem);

    if (!$rsProximaOrdem) {
      throw new DBException("Houve um erro ao buscar a lista de classificação de credores.");
    }

    if (pg_num_rows($rsProximaOrdem) == 0) {
      return 1;
    }
    return db_utils::fieldsMemory($rsProximaOrdem, 0)->nova_ordem;
  }

  /**
   * Verifica se a lista de classificação de credor deveria ser dispensa e foi configurada como dispensa false.
   */
  private function verificaObrigaDispensa() {

    $aListasObrigaDispensa = array(self::DISPENSA_MANUAL);
    if (!$this->dispensa() && in_array($this->getCodigo(), $aListasObrigaDispensa)) {

      $sMensagemErro  = "A Lista de Classificação de Credores " .  $this->getCodigo() ." - ". $this->getDescricao();
      $sMensagemErro .= " deve ser configurada como Tipo Dispensa, pois é a lista padrão do sistema utilizada para dispensa manual.";
      throw new BusinessException($sMensagemErro);
    }
  }

}
