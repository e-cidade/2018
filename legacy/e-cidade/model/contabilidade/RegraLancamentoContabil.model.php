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

define('MENSAGENS_REGRA_LANCAMENTO_CONTABIL', 'financeiro.contabilidade.RegraLancamentoContabil.');

/**
 * RegraLancamentoContabil
 * Model para controle de uma Regra de Lançamentos
 * @author  matheus.felini@dbseller.com.br
 * @version $Revision: 1.13 $
 * @package contabilidade
 */
class RegraLancamentoContabil {

	/**
   * Código sequencial da Regra
   * @var integer
	 */
	protected $iSequencialRegra;

	/**
	 * Código sequencial do Lancamento a que a regra pertence
	 * @var integer
	 */
	protected $iSequencialLancamento;

	/**
	 * Conta Débito
	 * @var integer
	 */
	protected $iContaDebito;

	/**
	 * Conta Crédito
	 * @var integer
	 */
	protected $iContaCredito;

	/**
	 * Observacao da Regra
	 * @var string
	 */
	protected $sObservacao;

	/**
   * Referencia
   * @var integer
	 */
	protected $iReferencia;

	/**
	 * Ano Cadastro para a regra
	 * @var integer
	 */
	protected $iAnoUso;

	/**
	 * Codigo da Instituição
	 * @var integer
	 */
	protected $iInstituicao;

	/**
   * Tipo de Comparação
   * @var integer
	 */
	protected $iCompara;

	/**
	 * Compara Débito
	 * @var integer
	 */
	const COMPARA_DEBITO  = 1;

	/**
	 * Compara Crédito
	 * @var integer
	 */
	const COMPARA_CREDITO = 2;

  /**
   * Constante para comparação do tipo prestação de contas
   * @var integer
   */
  const COMPARA_PRESTACAO_CONTA = 6;

	/**
	 * @var integer
	 */
	const COMPARA_DEBITO_ELEMENTO = 10;

	/**
	 * @var integer
	 */
	const COMPARA_CREDITO_ELEMENTO = 11;

  /**
   * @var integer
   */
  const COMPARA_CP_IGUAL = 12;

  /**
   * @var integer
   */
  const COMPARA_CP_DIFERENTE = 13;



	/**
	 * Tipo de Resto
	 * @var integer
	 */
	protected $iTipoResto;

	/**
	 * O construtor setará valores para as propriedades existentes caso seja passado o seguencial por parâmetro
	 * @param integer $iSequencialRegra
	 */
	public function __construct($iSequencialRegra = null) {

		if (!empty($iSequencialRegra)) {

			$oDaoRegraLancamento      = db_utils::getDao('contranslr');
			$sSqlBuscaRegraLancamento = $oDaoRegraLancamento->sql_query_file($iSequencialRegra);
			$rsBuscaRegraLancamento   = $oDaoRegraLancamento->sql_record($sSqlBuscaRegraLancamento);

			if ($oDaoRegraLancamento->numrows > 0) {

				$oDadoRegra = db_utils::fieldsMemory($rsBuscaRegraLancamento, 0);
				$this->setAnoUso($oDadoRegra->c47_anousu);
				$this->setCompara($oDadoRegra->c47_compara);
				$this->setContaCredito($oDadoRegra->c47_credito);
				$this->setContaDebito($oDadoRegra->c47_debito);
				$this->setInstituicao($oDadoRegra->c47_instit);
				$this->setObservacao($oDadoRegra->c47_obs);
				$this->setReferencia($oDadoRegra->c47_ref);
				$this->setSequencialLancamento($oDadoRegra->c47_seqtranslan);
				$this->setSequencialRegra($oDadoRegra->c47_seqtranslr);
				$this->setTipoResto($oDadoRegra->c47_tiporesto);
				unset($oDadoRegra);
			}
		}
		return true;
	}

	/**
	 * Clona o objeto limpando sequencial da regra para incluir e não alterar.
	 */
	public function __clone() {
		$this->setSequencialRegra('');
	}

	/**
	 * Salva ou altera uma regra de um lançamento contábil
	 * @throws Exception
	 * @return boolean true
	 */
	public function salvar() {

		$oDaoConTransLr                  = db_utils::getDao('contranslr');
		$oDaoConTransLr->c47_seqtranslr  = $this->getSequencialRegra();
		$oDaoConTransLr->c47_seqtranslan = $this->getSequencialLancamento();
		$oDaoConTransLr->c47_debito      = $this->getContaDebito();
		$oDaoConTransLr->c47_credito     = $this->getContaCredito();
		$oDaoConTransLr->c47_obs         = $this->getObservacao();
		$oDaoConTransLr->c47_ref         = $this->getReferencia();
		$oDaoConTransLr->c47_anousu      = $this->getAnoUso();
		$oDaoConTransLr->c47_instit      = $this->getInstituicao();
		$oDaoConTransLr->c47_compara     = $this->getCompara();
		$oDaoConTransLr->c47_tiporesto   = $this->getTipoResto();

		if ($this->getSequencialRegra() == "") {

			$oDaoConTransLr->incluir(null);
			$this->setSequencialRegra($oDaoConTransLr->c47_seqtranslr);

		} else {
			$oDaoConTransLr->alterar($this->getSequencialRegra());
		}

		if ($oDaoConTransLr->erro_status == 0) {
			throw new Exception("Não foi possível salvar as regras do lançamento");
		}

		return true;
	}

	/**
	 * Remove uma regra de um lançamento contábil
	 * - remove regra do evento inverso
	 *
	 * @throws Exception
	 * @return boolean true
	 */
	public function excluir() {

		$oDaoRegraEventoContabil     = db_utils::getDao('contranslr');
		$sSqlBuscaLancamentoContabil = $oDaoRegraEventoContabil->sql_query_lancamento_contabil($this->getSequencialRegra());
		$rsBuscaLancamentoContabil   = $oDaoRegraEventoContabil->sql_record($sSqlBuscaLancamentoContabil);

		if ($oDaoRegraEventoContabil->numrows > 0) {
			throw new Exception("A regra {$this->getSequencialRegra()} possui lançamentos contábeis.\n\nExclusão abordada.");
		}

		$this->excluirElemento();
		$oDaoRegraEventoContabil->excluir($this->getSequencialRegra());

		if ($oDaoRegraEventoContabil->erro_status == 0) {
			throw new Exception ("Não foi possível remover a regra do lançamento. Esta regra tem lançamento contábil.\n\n {$oDaoRegraEventoContabil->erro_msg}");
		}

		return true;
	}

	/**
	 * Retorna o sequencial da regra
	 * @return integer
	 */
	public function getSequencialRegra() {
    return $this->iSequencialRegra;
	}

	/**
	 * Seta o sequencial da regra
	 * @param integer $iSequencialRegra
	 */
	public function setSequencialRegra($iSequencialRegra)	{
    $this->iSequencialRegra = $iSequencialRegra;
	}

	/**
	 * Retorna o sequencial do lançamento
	 * @return integer
	 */
	public function getSequencialLancamento()	{
    return $this->iSequencialLancamento;
	}

	/**
	 * Seta valor para o sequencial do lancamento
	 * @param integer $iSequencialLancamento
	 */
	public function setSequencialLancamento($iSequencialLancamento)	{
    $this->iSequencialLancamento = $iSequencialLancamento;
	}

	/**
	 * Retorna o a Conta Debito
	 * @return integer
	 */
	public function getContaDebito() {
    return $this->iContaDebito;
	}

	/**
	 * Seta a conta débito
	 * @param integer $iContaDebito
	 */
	public function setContaDebito($iContaDebito)	{
    $this->iContaDebito = $iContaDebito;
	}

	/**
	 * Retorna o a Conta Credito
	 * @return integer
	 */
	public function getContaCredito()	{
    return $this->iContaCredito;
	}

	/**
	 * Seta a conta credito
	 * @param integer $iContaCredito
	 */
	public function setContaCredito($iContaCredito)	{
    $this->iContaCredito = $iContaCredito;
	}

	/**
	 * Retorna a observação da Regra
	 * @return string
	 */
	public function getObservacao()	{
    return $this->sObservacao;
	}

	/**
	 * Seta a observação da regra
	 * @param string $sObservacao
	 */
	public function setObservacao($sObservacao)	{
    $this->sObservacao = $sObservacao;
	}

	/**
	 * Retorna a regerencia
	 * @return integer
	 */
	public function getReferencia()	{
    return $this->iReferencia;
	}

	/**
	 * Seta a referencia
	 * @param integer $iReferencia
	 */
	public function setReferencia($iReferencia)	{
    $this->iReferencia = $iReferencia;
	}

	/**
	 * Retorna o ano uso
	 * @return integer
	 */
	public function getAnoUso()	{
    return $this->iAnoUso;
	}

	/**
	 * Seta valor para o ano uso
	 * @param integer $iAnoUso
	 */
	public function setAnoUso($iAnoUso)	{
    $this->iAnoUso = $iAnoUso;
	}

	/**
	 * Retorna a instituicao
	 * @return integer
	 */
	public function getInstituicao() {
    return $this->iInstituicao;
	}

	/**
	 * Seta valor para instituicao
	 * @param integer $iInstituicao
	 */
	public function setInstituicao($iInstituicao)	{
    $this->iInstituicao = $iInstituicao;
	}

	/**
	 * Retorna o tipo de comparacao
	 * @return integer
	 */
	public function getCompara() {
    return $this->iCompara;
	}

	/**
	 * Seta tipo para comparacao
	 * @param integer $iInstituicao
	 */
	public function setCompara($iCompara)	{
    $this->iCompara = $iCompara;
	}

	/**
	 * Retorna o tipo de resto
	 * @return integer
	 */
	public function getTipoResto() {
    return $this->iTipoResto;
	}

	/**
	 * Seta valor para o tipo de resto
	 * @param integer $iInstituicao
	 */
	public function setTipoResto($iTipoResto)	{
    $this->iTipoResto = $iTipoResto;
	}

	/**
	 * vincula regra atual com regra do lancamento do documento inverso(inclusao/estorno)
	 *
	 * @param integer $iRegraLancamentoEventoInverso
	 * @param boolean $lEventoInclusao
	 */
	public function salvarVinculoEventoInverso($iRegraLancamentoEventoInverso, $lEventoInclusao) {

		/**
		 * Evento de estorno
		 */
		$iRegraInclusao = $iRegraLancamentoEventoInverso;
		$iRegraEstorno  = $this->getSequencialRegra();

		/**
		 * Evento de inclusao
		 */
		if ( $lEventoInclusao ) {

			$iRegraInclusao = $this->getSequencialRegra();
			$iRegraEstorno  = $iRegraLancamentoEventoInverso;
		}

		/**
		 * Inclui vinculo entre as regras com documentos inversos(inclusao/estorno)
		 */
		$oDaoContranslrvinculo = db_utils::getDao("contranslrvinculo");
		$oDaoContranslrvinculo->c116_contranslrinclusao = $iRegraInclusao;
 		$oDaoContranslrvinculo->c116_contranslrestorno  = $iRegraEstorno;
 		$oDaoContranslrvinculo->incluir(null);

		if ( $oDaoContranslrvinculo->erro_status == "0" ) {
			throw new Exception(_M( MENSAGENS_REGRA_LANCAMENTO_CONTABIL . "erro_incluir_regras_vincular"));
		}
	}

	/**
	 * Exclui regras do documento inverso
	 *
	 * @return boolean
	 */
	public function excluirRegraEventoInverso() {

		$mPossuiVinculoComOutraRegra = $this->possuiVinculoComOutraRegra();
		if ( ! $mPossuiVinculoComOutraRegra ) {
			return false;
		}

		$this->excluirVinculoRegra();

		/**
		 * Regra atual é de inclusao
		 * - deleta regra inversa, de estorno
		 */
		if ( !empty($mPossuiVinculoComOutraRegra->c116_contranslrinclusao) &&
					$mPossuiVinculoComOutraRegra->c116_contranslrinclusao == $this->getSequencialRegra() ) {

			$oRegraLancamentoEstorno = new RegraLancamentoContabil($mPossuiVinculoComOutraRegra->c116_contranslrestorno);
			$oRegraLancamentoEstorno->excluir();
		}

		/**
		 * Regra atual é de estorno
		 * - deleta regra inversa, de inclusao
		 */
		if ( !empty($mPossuiVinculoComOutraRegra->c116_contranslrestorno) &&
					$mPossuiVinculoComOutraRegra->c116_contranslrestorno == $this->getSequencialRegra() ) {

			$oRegraLancamentoInclusao = new RegraLancamentoContabil($mPossuiVinculoComOutraRegra->c116_contranslrinclusao);
			$oRegraLancamentoInclusao->excluir();
		}

		return true;
	}

	/**
	 * Excluimos o vinculo com a regra do evento inverso.
	 * @return true
	 */
	public function excluirVinculoRegra() {

		$sWhere  = "   c116_contranslrinclusao = {$this->getSequencialRegra()}";
		$sWhere .= "or c116_contranslrestorno  = {$this->getSequencialRegra()}";

		/**
		 * Exclui vinculo com regra de evento inverso
		 */
		$oDaoContranslrvinculo = db_utils::getDao("contranslrvinculo");
		$oDaoContranslrvinculo->excluir(null, $sWhere);
		if ( $oDaoContranslrvinculo->erro_status == "0" ) {
			throw new Exception(_M( MENSAGENS_REGRA_LANCAMENTO_CONTABIL . "erro_excluir_regras_vincular"));
		}
		return true;
	}


	/**
	 * Verifica se a regra atual possui um vínculo com alguma regra do documento inverso
	 * @return mixed false || stdClass
	 */
	private function possuiVinculoComOutraRegra() {

		/**
		 * Procura regra de inclusao ou estorno
		 */
		$sWhere  = "   c116_contranslrinclusao = {$this->getSequencialRegra()}";
		$sWhere .= "or c116_contranslrestorno  = {$this->getSequencialRegra()}";

		$oDaoContranslrvinculo = db_utils::getDao('contranslrvinculo');
		$sSqlVinculo = $oDaoContranslrvinculo->sql_query_file(null, '*', null, $sWhere);
		$rsVinculo   = db_query($sSqlVinculo);

		/**
		 * Erro na query
		 */
		if ( !$rsVinculo ) {
			throw new Exception(_M( MENSAGENS_REGRA_LANCAMENTO_CONTABIL . "erro_excluir_regras_vincular"));
		}

		/**
		 * Nao encontrou vinculo para regra atual
		 */
		if ( pg_num_rows($rsVinculo) == 0 ) {
			return false;
		}

		return db_utils::fieldsMemory($rsVinculo, 0);
	}

  /**
   * Retorna o elemento vinculado à regra
   * @return string
   */
	public function getElemento() {

		$oDaoContranslrElemento = db_utils::getDao('contranslrelemento');
		$sWhereElemento = "c114_contranslr = {$this->getSequencialRegra()}";
		$sSqlElemento = $oDaoContranslrElemento->sql_query_file(null, "c114_elemento", null, $sWhereElemento);
		$rsElemento = $oDaoContranslrElemento->sql_record($sSqlElemento);

		if ($oDaoContranslrElemento->numrows == 0) {
			return '';
		}

		return db_utils::fieldsMemory($rsElemento, 0)->c114_elemento;
	}

	/**
	 * Vincula elemento à regra atual
	 * @throws Exception
	 * @return void
	 */
	public function vincularElemento($sElemento) {

		$iSequencialRegra = $this->getSequencialRegra();

    $oDaoContranslrelemento = db_utils::getDao('contranslrelemento');
    $oDaoContranslrelemento->c114_contranslr = $iSequencialRegra;
    $oDaoContranslrelemento->c114_elemento   = str_pad($sElemento, 15, 0);

    $oDaoContranslrelemento->excluir(null, "c114_contranslr = $iSequencialRegra");

    if ($oDaoContranslrelemento->erro_status == "0") {
      throw new Exception("Erro ao excluir elemento da regra.\n\n" . $oDaoContranslrelemento->erro_msg);
    }

    $oDaoContranslrelemento->incluir(null);

    if ($oDaoContranslrelemento->erro_status == "0") {
      throw new Exception("Erro ao incluir elemento da regra.\n\n" . $oDaoContranslrelemento->erro_msg);
    }
	}

	/**
	 * Exclui elemento vinculado à regra atual
	 * @return void
	 */
	public function excluirElemento() {

		$iSequencialRegra = $this->getSequencialRegra();

    $oDaoContranslrelemento = db_utils::getDao('contranslrelemento');
    $oDaoContranslrelemento->excluir(null, "c114_contranslr = $iSequencialRegra");

    if ($oDaoContranslrelemento->erro_status == "0") {
      throw new Exception("Erro ao excluir elemento da regra.\n\n" . $oDaoContranslrelemento->erro_msg);
    }
	}

}