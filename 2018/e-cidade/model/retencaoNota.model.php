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
 * @author Iuri Guntchnigg
 * @version $Revision: 1.56 $
 * @package empenho
 */
class retencaoNota {


	/**
	 * Instancia da DAo da classe empnota
	 *
	 * @var object
	 */
	private $instanciaNota;

	/**
	 * Codigo da nota fiscal
	 *
	 * @var integer
	 */
	private $iCodNota ;

	/**
	 * cpf/cnpj do credor
	 *
	 * @var integer
	 */
	private $iCpfCnpj;

	/**
	 * grava os dados em sessao
	 *
	 * @var boolean
	 */
	private $lInSession;

	/**
	 * Codigo da nota de liquidacao (ordem de pagamento)
	 *
	 * @var integer
	 */
	private $iNotaLiquidacao;

	/**
	 * codigo do grupo das autenticações
	 *
	 * @var intger
	 */
	private $iGrupoAutenticacao= 0;

	/**
	 * Codigo da conta a recolher as retencoes
	 *
	 * @var integer
	 */
	private $iConta = null;

	/**
	 * Codigo do movimento a agenda;
	 * refere a tabela empagemov
	 *
	 * @var integer
	 */
	private $iCodMovimento = null;

	/**
	 * Codigo do cgm
	 *
	 * @var integer
	 */
	private $iNumCgm       = null;
	/**
	 * Codifica variaveis do tipo strings com urlencode
	 *
	 * @var boolean
	 */
	private $lEncodeUrl = false;

	/**
	 * data  base do recolhimento
	 *
	 * @var string
	 */
	private $dtDataBase = null;

	/**
	 * lista das Retencoes cadastradas
	 *
	 * @var array
	 */
	private $aRetencoes = array();

	/**
	 * retencaoNota constructor.
	 * @param $iCodNota
   * @throws Exception
	 */
	public function __construct($iCodNota) {

		if (empty($iCodNota)) {
			throw new Exception("Erro [1] - Nota noi Informada");
		}

		$this->iCodNota      = $iCodNota;
		$this->instanciaNota = db_utils::getDao("empnota");
		$this->dtDataBase    = date("Y-m-d",db_getsession("DB_datausu"));
	}

	/**
	 * Adiciona uma retencao a nota, caso a nota exista.
	 *
	 * @param  object  $oRetencao objeto stClass com as seguintes propriedades (iCodRetencao)
	 * @param  boolean $lInSession define se o objeto sera adicionado apenas na sessao, ou sera apenas adicionado a classe.
	 * @param  boolean $isUpdate   define se devera ser modificado ou nao os registros (true para apenas modificar )
	 *                              e false para adicionar novo.
	 * @return boolean
	 */
	function addRetencao($oRetencao, $lInSession = false, $isUpdate = false) {

		/* Algumas regras:
		 *  1 - caso o usuario cadastrou uma retencao de tipo 1, ou 2 (Imposto de Renda)
		*      ele não pode mais cadastrar uma retencao do tipo 3 ou 4 (INSS), pois as
		*      retencoes do tipo 3, 4 deduzem da base de cálculo.
		*  2 - Não Podemos lancar uma retencao duas vezes.
		*  3 - Sempre devemos ver o calculo da retencao por CGM(cnpj/cpf) dentro do mes , nunca por nota.
		*/

		/*
		 * fazemos uma copia das retencoes cadastradas, para fazermos algumas validações
		*/
		$this->lInSession = $lInSession;
		if ($lInSession) {
			if (isset($_SESSION["retencaoNota{$this->iCodNota}"])) {
				$aRetencoes = $_SESSION["retencaoNota{$this->iCodNota}"];
			} else {
				$aRetencoes = array();
			}
		} else {
			$aRetencoes = $this->aRetencoes;
		}

		/*
		 * Validamos a segunda regra
		*/
		if (key_exists($oRetencao->iCodigoRetencao, $aRetencoes) && !$isUpdate ) {
			throw new Exception("Erro [1] - Retenção já cadastrada!");
		}

		/*
		 * selecionamos as informacoes da retencao escolhida pelo usuário
		*/
		$oDaoRetencao = db_utils::getDao("retencaotiporec");
		$sSqlRetencao = $oDaoRetencao->sql_query($oRetencao->iCodigoRetencao,"tabrec.*,retencaotiporec.*");
		$rsRetencao   = $oDaoRetencao->sql_record($sSqlRetencao);
		if ($oDaoRetencao->numrows == 0) {
			throw new Exception("Erro [2] - informações da retenção não encontradas!");
		}

		$oDadosRetencao   = db_utils::fieldsMemory($rsRetencao, 0);

		/*
		 * Validamos a primeira regra;
		*/
		if (($oDadosRetencao->e21_retencaotipocalc == 3 || $oDadosRetencao->e21_retencaotipocalc == 4
				|| $oDadosRetencao->e21_retencaotipocalc == 7) && !$isUpdate ) {

			foreach ($aRetencoes as $oRetencaoAtiva) {

				if ($oRetencaoAtiva->e21_retencaotipocalc == 1
						|| $oRetencaoAtiva->e21_retencaotipocalc == 2) {

					$sMsg  = "Erro [3] - Retenção de INSS não pode ser cadastrada, pois já foi informado ";
					$sMsg .=  "uma retenção de imposto de renda.\n(INSS reduz a base de cálculo do IRRF)";
					throw new Exception($sMsg);

				}
			}
		}

		$oDadosRetencao->e23_valorretencao = $oRetencao->nValorRetencao;
		$oDadosRetencao->e23_deducao       = $oRetencao->nValorDeducao;
		$oDadosRetencao->e23_valor         = $oRetencao->nValorNota;
		$oDadosRetencao->e23_valorbase     = $oRetencao->nValorbase;
		$oDadosRetencao->e23_aliquota      = $oRetencao->nAliquota;
		$oDadosRetencao->aMovimentos       = $oRetencao->aMovimentos;
		if ($lInSession) {
			$_SESSION["retencaoNota{$this->iCodNota}"][$oRetencao->iCodigoRetencao] = $oDadosRetencao;
		} else {
			$this->aRetencoes[$oRetencao->iCodigoRetencao] = $oDadosRetencao;
		}
		return true;
	}
	/**
	 * persiste a retencao na base de dados.
	 *
	 * @param  integer $iNotaLiquidacao Código da nota de liquidacao (e50_codord)
	 * @param  array   $aMovimentosAuxiliares outros movimentos que compoe a base de calculo.
	 * @return boolean
	 */
	function salvar($iNotaLiquidacao, $aMovimentosAuxiliares = null) {

		if (!db_utils::inTransaction()) {
			throw new Exception("Erro [0] - Não Existe transação ativa");
		}

		if (empty($iNotaLiquidacao)) {
			throw new Exception("Erro [1]- Código da nota de Liquidação Informado.\nRetenções não salvas");
		}

		if ($this->getCodigoMovimento() == null) {
			throw  new Exception("Erro [4] - Não foi informado o código do movimento da agenda.\n");
		}

		$aRetencoes = $this->getRetencoes();
		if (count($aRetencoes) > 0) {

			/*
			 * percorremos as retencoes cadastradas, e verificamos para ver se a retencao já foi
			* recolhida ou já exista.
			* Caso já exista,marcamos ela como inativa, e incluimos novamente.
			* Caso ja esteja recolhido dentro do mes, apenas passamos para a próxima retenção
			* caso nenhum dos dois casos,
			* incluimos nas tabelas retencaopagordem,
			* e na retencaoreceitas
			*/
			foreach ($aRetencoes as $oRetencao) {

				$lJaRecolhido        = false;
				$oDaoRetencaoReceita = db_utils::getDao("retencaoreceitas");
				$aDataUsu = explode("-", date("Y-m-d",db_getsession("DB_datausu")));
				list($iAnoUsu, $iMesUsu, $iDiaCalculo) = $aDataUsu;
				$sSqlRetencao        = $oDaoRetencaoReceita->sql_query_notas(null,
						"e23_sequencial,
						e23_ativo,
						e23_dtcalculo,
						e23_recolhido",
						null,
						"e27_empagemov={$this->iCodMovimento}
				and e23_ativo     = true
				and e23_recolhido = false
				and e27_principal = true
				and e23_retencaotiporec = {$oRetencao->e21_sequencial}
				"
				);

				$rsRetencao = $oDaoRetencaoReceita->sql_record($sSqlRetencao);
				$iNumRowsRetencao = $oDaoRetencaoReceita->numrows;
				/*
				 * Percorremos as retenções encontradas, e que nao foram baixas e desativamos
				*/
				if ($iNumRowsRetencao > 0) {

					$aRetencoesAntigas = db_utils::getCollectionByRecord($rsRetencao);
					foreach ($aRetencoesAntigas as $oRetencaoAntiga) {

						$oDaoRetencaoReceita = db_utils::getDao("retencaoreceitas");
						$oDaoRetencaoReceita->e23_sequencial = $oRetencaoAntiga->e23_sequencial;
						$oDaoRetencaoReceita->e23_ativo      = "false";
						$oDaoRetencaoReceita->alterar($oRetencaoAntiga->e23_sequencial);
						unset($oDaoRetencaoReceita);

					}
				}
				//Incluimos na retencaopagordem
				$dtDataUsu                      = date("Y-m-d",db_getsession("DB_datausu"));
				$oDaoRetencaoNota               = db_utils::getDao("retencaopagordem");
				$oDaoRetencaoNota->e20_pagordem = $iNotaLiquidacao;
				$oDaoRetencaoNota->e20_data     = $dtDataUsu;
				$oDaoRetencaoNota->incluir(null);
				if ($oDaoRetencaoNota->erro_status == 0) {

					$sMsg  = "Erro[2] - Não foi possível incluir Retencao {$oRetencao->e21_sequencial}.\n";
					$sMsg .= "Erro Técnico: {$oDaoRetencaoNota->erro_msg}";
					throw new Exception($sMsg);

				}

				/*
				 * Incluimos na retencaoreceitas
				*/
				$oDaoRetencaoReceita = db_utils::getDao("retencaoreceitas");
				$oDaoRetencaoReceita->e23_dtcalculo        = $dtDataUsu;
				$oDaoRetencaoReceita->e23_ativo            = "true";
				$oDaoRetencaoReceita->e23_retencaotiporec  = $oRetencao->e21_sequencial;
				$oDaoRetencaoReceita->e23_retencaopagordem = $oDaoRetencaoNota->e20_sequencial;
				$oDaoRetencaoReceita->e23_valor            = "{$oRetencao->e23_valor}";
				$oDaoRetencaoReceita->e23_deducao          = "{$oRetencao->e23_deducao}";
				$oDaoRetencaoReceita->e23_valorbase        = "{$oRetencao->e23_valorbase}";
				$oDaoRetencaoReceita->e23_valorretencao    = "{$oRetencao->e23_valorretencao}";
				$oDaoRetencaoReceita->e23_aliquota         = "{$oRetencao->e23_aliquota}";
				$oDaoRetencaoReceita->e23_recolhido        = "false";
				$oDaoRetencaoReceita->incluir(null);
				if ($oDaoRetencaoReceita->erro_status == 0) {

					$sMsg  = "Erro[3] - Não foi possível incluir Retencao {$oRetencao->e21_sequencial}.\n";
					$sMsg .= "Erro Técnico: {$oDaoRetencaoReceita->erro_msg}";
					throw new Exception($sMsg);

				}

				/*
				 * Ligamos a retencao ao movimento da agenda;
				*/
				$oDaoRetencaoMov = db_utils::getDao("retencaoempagemov");
				$oDaoRetencaoMov->e27_empagemov        = $this->getCodigoMovimento();
				$oDaoRetencaoMov->e27_retencaoreceitas = $oDaoRetencaoReceita->e23_sequencial;
				$oDaoRetencaoMov->e27_principal        = "true";
				$oDaoRetencaoMov->incluir(null);
				if ($oDaoRetencaoMov->erro_status == 0) {

					$sMsg  = "Erro[5] - Não foi possível incluir Retencao {$oRetencao->e21_sequencial}.\n";
					$sMsg .= "Erro Técnico: {$oDaoRetencaoMov->erro_msg}";
					throw new Exception($sMsg);

				}
				if (is_array($oRetencao->aMovimentos) && count($oRetencao->aMovimentos) > 0) {

					for ($i = 0; $i < count($oRetencao->aMovimentos); $i++) {

						$oDaoRetencaoMov = db_utils::getDao("retencaoempagemov");
						$oDaoRetencaoMov->e27_empagemov        = $oRetencao->aMovimentos[$i];
						$oDaoRetencaoMov->e27_retencaoreceitas = $oDaoRetencaoReceita->e23_sequencial;
						$oDaoRetencaoMov->e27_principal        = "false";
						$oDaoRetencaoMov->incluir(null);
						if ($oDaoRetencaoMov->erro_status == 0) {

							$sMsg  = "Erro[6] - Não foi possível incluir Retencao {$oRetencao->e21_sequencial}.\n";
							$sMsg .= "Erro Técnico: {$oDaoRetencaoMov->erro_msg}";
							throw new Exception($sMsg);

						}
					}
				}
			}
		}
		$this->unsetSession();
		return true;

	}
	/**
	 * Seta  o Codigo do movimento da agenda;
	 *
	 * @param integer $iCodMovimento Código do movimento
	 */
	function setCodigoMovimento($iCodMovimento) {
		if (!empty($iCodMovimento)) {
			$this->iCodMovimento = $iCodMovimento;
		}
	}

	/**
	 * retorna o codigo do movimento da retencao.
	 *
	 * @return integer
	 */
	function getCodigoMovimento() {
		return $this->iCodMovimento;
	}

	/**
	 * Seta o codigo da nota de liquidacao (pagordem)
	 *
	 * @param integer $iNotaLiquidacao código da nota de liquidação
	 */
	function setINotaLiquidacao($iNotaLiquidacao) {
		$this->iNotaLiquidacao = $iNotaLiquidacao;
	}
	/**
	 * Seta a propriedade lEncodeUrl
	 *
	 * @param boolean $lEncode
	 */
	function setEncodeUrl($lEncode) {
		$this->lEncodeUrl = $lEncode;
	}
	/**
	 * Define a data base das retencoes
	 * Usado para o recolhimento da base
	 *
	 * @param string $dtBase data no formato YYYY-MM-DD
	 */
	function setDataBase($dtBase) {
		$this->dtDataBase = $dtBase;
	}

	function getDataBase() {
		return $this->dtDataBase;
	}
	/**
	 * Retorna uma collection com as retencoes cadastradas
	 *
	 * @return mixed;
	 */
	function getRetencoes() {

		$aRetencoes = array();
		if ($this->lInSession) {

			if (isset($_SESSION["retencaoNota{$this->iCodNota}"])) {

				foreach ($_SESSION["retencaoNota{$this->iCodNota}"] as $iRetencao => $oDados) {

					$aRetencoes[] = $oDados;
				}
			}
			return $aRetencoes;
		} else {

			foreach ($this->aRetencoes as $iRetencao => $oDados ) {
				$aRetencoes[] = $oDados;
			}
		}
		return $this->aRetencoes;
	}

	/**
	 * Seta se o objeto deve ser mantido em sessao.
	 *
	 * @param boolean $lInSession
	 */
	function setInSession($lInSession) {
		$this->lInSession = $lInSession;
	}

	/**
	 * destroi as informacoes das retencoes que estão em sessão.
	 *
	 */
	function unsetSession() {

		if (isset($_SESSION["retencaoNota{$this->iCodNota}"])) {
			unset($_SESSION["retencaoNota{$this->iCodNota}"]);
		}
	}

	/**
	 * retorna as retencoes cadastradass para a op;
	 *
	 *
	 * @param integer $iNotaLiquidacao código da ordem de pagamento
	 * @param boolean $lInSession define se os dados sera retornados na sessao (true), ou num array (false)
	 * @param integer $iTipo define o tipo da pesquisa 0 - retornar as retencoes recolhidas ou nao. 1 somente Recolhidas.
	 *                                                 2 - nao recolhidas
	 * @param boolean $lPrincipal retorna somente as retenções calculadas como principais
	 * @return boolean| array retorna m boolean caso $lInSession = true ou um array de objetos caso false;
	 */
	function getRetencoesFromDB($iNotaLiquidacao, $lInSession = true, $iTipo = 2,$iMes = "", $iAno = "", $lPrincipal = false) {

		$aRetencoes = array();
		if (!isset($_SESSION["retencaoNota{$this->iCodNota}"]) || $lInSession == false) {

			$sWhere = "";
			if ($this->getCodigoMovimento() != "") {

				$sWhere .= " and e27_empagemov = ".$this->getCodigoMovimento();
				$sWhere .= " and e27_principal is true";

			}
			if ($iMes != "" && $iAno != "") {

				$sWhere .= " and extract(month from e23_dtcalculo) = {$iMes} ";
				$sWhere .= " and extract(year  from e23_dtcalculo) = {$iAno} ";

			}
			if ($lPrincipal == true) {
	  	$sWhere .= " and e27_principal is true ";
	  }

	  $sRecolhida = "";
	  if ($iTipo == 1) {
	  	$sRecolhida = " and e23_recolhido is true ";
	  } else if ($iTipo == 2) {
	  	$sRecolhida = " and e23_recolhido is false";
	  }

	  $oDaoRetencaoEmpAgeMov = db_utils::getDao("retencaoempagemov");
	  $oDaoRetencaoReceitas  = db_utils::getDao("retencaoreceitas");
	  $sSqlRetencaoReceitas  = $oDaoRetencaoReceitas->sql_query_notas(null,
	  		"tabrec.*,retencaotiporec.*,
	  		retencaoreceitas.*,e27_empagemov,e27_principal",
	  		"e21_sequencial",
	  		"e20_pagordem     = {$iNotaLiquidacao}
	  and e23_ativo     = true
	  and e71_anulado   = false
	  {$sRecolhida}
	  {$sWhere}"
	  );
	  $rsRetencoes         = $oDaoRetencaoReceitas->sql_record($sSqlRetencaoReceitas);
	  if ($oDaoRetencaoReceitas->numrows > 0) {

	  	for ($iInd = 0; $iInd < $oDaoRetencaoReceitas->numrows; $iInd++) {

	  		$oRetencao = db_utils::fieldsMemory($rsRetencoes, $iInd,false, false, $this->lEncodeUrl);
	  		/**
	  		 * Buscamos todos os movimentos que foram usados para fazer parte do calculo.
	  		 */
	  		$sSqlMovimentos = $oDaoRetencaoEmpAgeMov->sql_query_file(null,"*" ,
	  				null,
	  				"e27_retencaoreceitas = {$oRetencao->e23_sequencial}
	  		and e27_principal is false"
	  		);
	  		$rsMovimentos = $oDaoRetencaoEmpAgeMov->sql_record($sSqlMovimentos);
	  		$oRetencao->aMovimentos = array();
	  		if ($oDaoRetencaoEmpAgeMov->numrows > 0){

	  			for ($i = 0; $i < $oDaoRetencaoEmpAgeMov->numrows; $i++ ) {
	  				$oRetencao->aMovimentos[] = $aMovimentos = db_utils::fieldsMemory($rsMovimentos, $i)->e27_empagemov;
	  			}
	  		}
	  		if ($lInSession) {
	  			$_SESSION["retencaoNota{$this->iCodNota}"][$oRetencao->e21_sequencial] = $oRetencao;
	  		} else {
	  			$aRetencoes[] = $oRetencao;
	  		}
	  	}
	  }
		}
		if ($lInSession) {
			return true;
		} else {
			return $aRetencoes;
		}
	}
	/**
	 * Desativa o calculo da retencao
	 *
	 * @param integer $iCodigoRetencao Codigo da retencao
	 * @param integer [$iNotaLiquidacao]
	 */
	function desativarRetencao($iCodigoRetencao, $iNotaLiquidacao=null) {

		if (!db_utils::inTransaction()) {
			throw new Exception("Erro [0] - Não Existe transação ativa");
		}
		if (!empty($iNotaLiquidacao)) {

			$oDaoRetencaoReceitas  = db_utils::getDao("retencaoreceitas");
			$sSqlRetencaoRecieitas = $oDaoRetencaoReceitas->sql_query_notas(null,
					"*",
					null,
					"e20_pagordem = {$iNotaLiquidacao}
			and e23_recolhido = false
			and e23_retencaotiporec = {$iCodigoRetencao}
			and e23_ativo     = true
			and e71_anulado   = false"
			);
			$rsRetencoes          = $oDaoRetencaoReceitas->sql_record($sSqlRetencaoRecieitas);
			if ($oDaoRetencaoReceitas->numrows > 0) {

				$oRetencaoAtiva                       = db_utils::fieldsMemory($rsRetencoes, 0);
				$oDaoRetencaoReceitas->e23_ativo      = "false";
				$oDaoRetencaoReceitas->e23_sequencial = $oRetencaoAtiva->e23_sequencial;
				$oDaoRetencaoReceitas->alterar($oRetencaoAtiva->e23_sequencial);
				if ($oDaoRetencaoReceitas->erro_status == 0) {

					throw new Exception("Erro [1] - Não foi possivel desativar retenção\nErro: {$oDaoRetencaoReceitas->erro_status}");
				}
			}
		}
		unset($_SESSION["retencaoNota{$this->iCodNota}"][$iCodigoRetencao]);
	}

	/**
	 * retorna o valor total das retencoes da nota de liquidacao
	 * @param integer $iNotaLiquidacao Código da nota de liquidacao
	 * @return Float
	 */
	function getValorRetencao($iNotaLiquidacao) {

		if (empty($iNotaLiquidacao)){
			throw new Exception("Erro [1] - Nota de liquidação nao informado");
		}
		$sSqlTotalRetencao = "select fc_valorretencaonota({$iNotaLiquidacao}) as valortotal";
		return db_utils::fieldsMemory(db_query($sSqlTotalRetencao), 0)->valortotal;

	}

	/**
	 * Retorna o valor total das retencoes calculadas para o movimento
	 *
	 * @param integer $iCodMov Código do movimento
	 * @param boolean $lrecolhido se soma somente as recolhidas
	 * @return float
	 */
	function getValorRetencaoMovimento($iCodMov, $lrecolhido = false, $database = null) {

		if (empty($iCodMov)){
			throw new Exception("Erro [1] - movimento da Agenda não informado");
		}

		$database = $database == null?"null":"'{$database}'";
		$sRecolhido = $lrecolhido?"true":"false";
		$sSqlTotalRetencao = "select fc_valorretencaomov({$iCodMov}, {$sRecolhido}, $database) as valortotal";
		return db_utils::fieldsMemory(db_query($sSqlTotalRetencao), 0)->valortotal;

	}

	/**
	 * Efetua a baixa (recolhimento) das retencoes.
	 * emite os recibos avulsos, e planilhas de retencao.
	 *
	 * @param array $aRetencoes array com as retencoes a sere baixadas
	 * @return void
	 */
	function baixarRetencoes($aRetencoes) {

		if (!is_array($aRetencoes)) {
			throw new Exception("Erro [1] - aRetenções deve ser um Array");
		}

		/**
		 * Buscamos qual instituição está logado o usuário.
		 * caso o usuário esta logado com uma instituição que nao seja a prefeitura,
		 * todos os recibos seram avulsos.
		 * Apenas para a prefeitura que devemos emitir planilha de retencao e
		 * recibo de debito ;
		 */

		require_once(modification('std/db_stdClass.php'));
		require_once(modification("model/recibo.model.php"));
		$oInstit = db_stdClass::getDadosInstit();
		/*
		 * Conta original do pagamento do empenho
		* apenas usamos essa variavel quando temos uma retencao que possua uma conta extra-orcamentária
		* cadastrada para fazer a baixa e  uma receita extra.
		*/
		$iContaOriginal = $this->getConta();
		foreach ($aRetencoes as $oRetencao) {

			if (empty($oRetencao->e23_sequencial)){
				throw new Exception("Erro [2] - Retenção não Informada");
			}
			/*
			 * Caso a conta foi modificado por uma retencao com receita extra, setamos a conta que
			* foi realizado a baixa do empenho.
			*/
			$this->setConta($iContaOriginal);

			$oDaoRetencaoReceitas                 = db_utils::getDao("retencaoreceitas");
			$oDaoRetencaoReceitas->e23_sequencial = $oRetencao->e23_sequencial;
			$oDaoRetencaoReceitas->e23_recolhido  = "true";
			$aDataCalculo   = explode("-", $oRetencao->e23_dtcalculo);
			$aDataPagamento = explode("-", $this->getDataBase());
			if ($aDataCalculo[1] != $aDataPagamento[1]) {
				$oDaoRetencaoReceitas->e23_dtcalculo = $this->getDataBase();
			}
			$oDaoRetencaoReceitas->alterar($oRetencao->e23_sequencial);
			if ($oDaoRetencaoReceitas->erro_status == 0) {

				$sErroMsg  = "Erro [2] - Não Foi possível recolher a retenção ({$iCodRetencao})";
				throw new Exception($sErroMsg);
			}

			if ($oRetencao->e23_valorretencao > 0) {

				/*
				 * Verificamos se a receita da retencao não e uma receita-extra(receita encontra-se na tabela tabplan)
				*/
				if ($this->getTipoReceita($oRetencao->k02_codigo) == 2) {

					/*
					 * Buscamos na tabela saltesextra para verificar se foi cadastrado uma conta
					* para realizar o recolhimento de receita.
					*/

					$oDaoSaltesExtra = db_utils::getDao("saltesextra");
					$sSqlContaExtra  = $oDaoSaltesExtra->sql_query_file(null, "*",null,"k109_saltes = {$iContaOriginal}");
					$rsContaExtra    = $oDaoSaltesExtra->sql_record($sSqlContaExtra);
					if ($oDaoSaltesExtra->numrows == 1) {

						$iContaExtra = db_utils::fieldsMemory($rsContaExtra, 0)->k109_contaextra;
						if (!empty($iContaExtra)) {

							$this->setConta($iContaExtra);
							/*
							 * Pesquisamos se foi incluido valores para fazer transferencia bancaria.
							* caso exista transferencia, devemos deduzir o valor da retencao do valor a transferir
							*/
							if ($oRetencao->e27_empagemov != "") {

								$oDaoEmpageMovSlips = db_utils::getDao("empagemovslips");
								$sSqlSlipMovimento  = $oDaoEmpageMovSlips->sql_query_file(null,"*",null,"k107_empagemov = {$oRetencao->e27_empagemov}");
								$rsMovimento        = $oDaoEmpageMovSlips->sql_record($sSqlSlipMovimento);
								if ($oDaoEmpageMovSlips->numrows > 0) {

									$oMovimentoSlip = db_utils::fieldsMemory($rsMovimento,0);
									$nValorRetencao                      = round(($oMovimentoSlip->k107_valor - $oRetencao->e23_valorretencao),2);
									$oDaoEmpageMovSlips->k107_valor      = "$nValorRetencao";
									$oDaoEmpageMovSlips->k107_sequencial = $oMovimentoSlip->k107_sequencial;
									$oDaoEmpageMovSlips->alterar($oMovimentoSlip->k107_sequencial);
									if ($oDaoEmpageMovSlips->erro_status == 0) {

										$sErroMsg  = "Erro [10] - Não Foi possível recolher a retenção ({$iCodRetencao})";
										throw new Exception($sErroMsg);

									}
								}
							}
						}
					}

				}

				require_once(modification("model/Dotacao.model.php"));
				require_once(modification('classes/ordemPagamento.model.php'));
				$oNotaLiquidacao = new ordemPagamento($this->iNotaLiquidacao);
				$oNotaLiquidacao->getDadosOrdem();

				$oDotacao        = new Dotacao($oNotaLiquidacao->oDadosOrdem->e60_coddot,
				                               $oNotaLiquidacao->oDadosOrdem->e60_anousu
				                              );

				$sHistoricoRecibo  = "Em contrapartida a este recibo foi lançado um pagamento ";
				$sHistoricoRecibo .= "para o empenho {$oNotaLiquidacao->oDadosOrdem->e60_codemp}/{$oNotaLiquidacao->oDadosOrdem->e60_anousu} ";
				$sHistoricoRecibo .= "no valor de R$ ".trim(db_formatar($oRetencao->e23_valorretencao,"f"));
				$sHistoricoRecibo .= " pela Ordem de Pagamento n° {$oNotaLiquidacao->oDadosOrdem->e50_codord}";
				$sHistoricoRecibo .= " correspondente a Nota Fiscal n° {$oNotaLiquidacao->oDadosOrdem->e69_numero} ";
				$sHistoricoRecibo .= "de ".db_formatar($oNotaLiquidacao->oDadosOrdem->e69_dtnota,"d");
				$sHistoricoRecibo .= " CGM: ".$oNotaLiquidacao->oDadosOrdem->z01_numcgm." - ".str_replace("'","",$oNotaLiquidacao->oDadosOrdem->z01_nome);
				if ($oRetencao->e21_retencaotipocalc != 5 || $oInstit->prefeitura == "f") {
					// echo ('primeiro'); die();
					$oReciboAvulso = new recibo(1, $this->getNumCgm());
					$oReciboAvulso->setConta($this->getConta());
					$oReciboAvulso->adicionarRecurso($oDotacao->getRecurso());
					$oReciboAvulso->setDataRecibo($this->getDataBase());
					$oReciboAvulso->setDataVencimentoRecibo($this->getDataBase());
					$oReciboAvulso->setGrupoAutenticacao($this->getGrupoAutenticacao());
					$oReciboAvulso->setHistorico($sHistoricoRecibo);
					$oReciboAvulso->adicionarReceita($oRetencao->k02_codigo, $oRetencao->e23_valorretencao, 0, '000');
					if (isset($oReciboAvulso)) {

						/*
						 *Pesquisamos o recurso do recibo. que deve ser o mesmo da conta pagadora.
						*/
						$oDaoSaltes = new cl_saltes;
						$oDaoDotacao = db_utils::getDao("orcdotacao");
						$sSqlDotacao = $oDaoDotacao->sql_query_file(
								$oNotaLiquidacao->oDadosOrdem->e60_anousu,
								$oNotaLiquidacao->oDadosOrdem->e60_coddot,
								"o58_codigo");
						$rsRecurso  = $oDaoDotacao->sql_record($sSqlDotacao);
						if ($oDaoDotacao->numrows == 1) {
							$oReciboAvulso->adicionarRecurso(db_utils::fieldsMemory($rsRecurso, 0)->o58_codigo);
						}
						$oReciboAvulso->emiteRecibo();
						$oReciboAvulso->autenticarRecibo($this->getDataBase(), $oNotaLiquidacao->oDadosOrdem->e60_concarpeculiar, $oNotaLiquidacao->oDadosOrdem->e50_numemp);
					}

					$nValorRecibo = $oReciboAvulso->getTotalRecibo();
					if ($nValorRecibo != $oRetencao->e23_valorretencao) {

						$sMenuAcessado      = db_stdClass::getCaminhoMenu((int)db_getsession("DB_itemmenu_acessado"));
						$sMsgValorRetencao  = "A retenção {$oRetencao->k02_codigo} com valor {$oRetencao->e23_valorretencao} é ";
						$sMsgValorRetencao .= "diferente do valor total do recibo {$nValorRecibo}.\n\n{$sMenuAcessado}";
						throw new Exception($sMsgValorRetencao);
					}

				} else if ($oRetencao->e21_retencaotipocalc == 5 ) {

					$sSqlCgm = "select cgc, numcgm from db_config where codigo = ".db_getsession("DB_instit");
					$rsCgm   = db_query($sSqlCgm);
					$oCgm    = db_utils::fieldsMemory($rsCgm, 0);
					/*
					 * Consultamos o cnpj do credor da ordem de pagamento
					*/
					$sSqlCnpjCredor = "select z01_cgccpf from cgm where z01_numcgm = {$oNotaLiquidacao->oDadosOrdem->z01_numcgm}";
					$rsCnpjCredor   = db_query($sSqlCnpjCredor);
					$oCgmCredor     = db_utils::fieldsMemory($rsCnpjCredor, 0);
					if ($oCgmCredor->z01_cgccpf == "") {
						throw new Exception("Não Foi possível efetuar a baixa da Retenção. Credor com CPF/CNPJ nulo ou inválido.");
					}
					require_once(modification('model/planilhaRetencao.model.php'));
					require_once(modification('model/recibo.model.php'));
					//Incluimos uma nova planilha de retencao

					$oPlanilha     = new planilhaRetencao(null, $oCgm->numcgm);
					$oNotaPlanilha->sCnpj               = $oNotaLiquidacao->oDadosOrdem->z01_cgccpf;
					$oNotaPlanilha->dtNota              = $oNotaLiquidacao->oDadosOrdem->e69_dtnota;
					$oNotaPlanilha->sNumeroNota         = $oNotaLiquidacao->oDadosOrdem->e69_numero;
					$oNotaPlanilha->nValor              = $oNotaLiquidacao->oDadosOrdem->e53_valor;
					$oNotaPlanilha->sNome               = str_replace("'","\'",$oNotaLiquidacao->oDadosOrdem->z01_nome);
					$oNotaPlanilha->nValorTotalRetencao = $oRetencao->e23_valorretencao;
					$oNotaPlanilha->nValorBase          = $oRetencao->e23_valorbase;
					$oNotaPlanilha->nValorDeducao       = $oRetencao->e23_deducao;
					$oNotaPlanilha->nAliquota           = $oRetencao->e23_aliquota;
					$oNotaPlanilha->iNotaLiquidacao     = $this->iNotaLiquidacao;
					$oPlanilha->adicionaNota($oNotaPlanilha);
					$oPlanilha->setDatausu($this->getDataBase());
					$oPlanilha->gerarDebito($sHistoricoRecibo);
					$iNumpre = $oPlanilha->getNumpre();

					//Incluimos o recibo e o autenticamos.
					$oReciboDebito = new recibo(2, $oCgm->numcgm, 25);
					$oReciboDebito->addNumpre($iNumpre, 1);
					$oDaoDotacao = db_utils::getDao("orcdotacao");
					$rsRecurso  = $oDaoDotacao->sql_record($oDaoDotacao->sql_query_file(
							$oNotaLiquidacao->oDadosOrdem->e60_anousu,
							$oNotaLiquidacao->oDadosOrdem->e60_coddot,
							"o58_codigo"));
					if ($oDaoDotacao->numrows == 1) {
						$oReciboDebito->adicionarRecurso(db_utils::fieldsMemory($rsRecurso, 0)->o58_codigo);
					}
					$oReciboDebito->setConta($this->getConta());
					$oReciboDebito->adicionarRecurso($oDotacao->getRecurso());
					$oReciboDebito->setDataRecibo($this->getDataBase());
					$oReciboDebito->setDataVencimentoRecibo($this->getDataBase());
					$oReciboDebito->setGrupoAutenticacao($this->getGrupoAutenticacao());
					$oReciboDebito->setHistorico(str_replace("'","",$sHistoricoRecibo));
					$oReciboDebito->emiteRecibo();
					$oReciboDebito->autenticarRecibo($this->getDataBase(), $oNotaLiquidacao->oDadosOrdem->e60_concarpeculiar);

					$nValorRecibo = $oReciboDebito->getTotalRecibo();
					if (($nValorRecibo == 0) || $nValorRecibo != $oRetencao->e23_valorretencao) {

						$sMenuAcessado      = db_stdClass::getCaminhoMenu((int)db_getsession("DB_itemmenu_acessado"));
						$sMsgValorRetencao  = "A retenção {$oRetencao->k02_codigo} com valor {$oRetencao->e23_valorretencao} é ";
						$sMsgValorRetencao .= "diferente do valor total do recibo {$nValorRecibo}.\n\n{$sMenuAcessado}";
						throw new Exception($sMsgValorRetencao);
					}
				}
				/**
				 * Vinculamos a retencao ao grupo do lancamento
				 */
				$oDaoCorrenteGrupo = db_utils::getDao("corgrupocorrente");
				$sSqlCorgrupo      = $oDaoCorrenteGrupo->sql_query_file(null,
                                                    						"*",
                                                    						"k105_autent desc limit 1",
                                                    						"k105_corgrupo = ".$this->getGrupoAutenticacao());
				$rsCorGrupo        = $oDaoCorrenteGrupo->sql_record($sSqlCorgrupo);
				if ($oDaoCorrenteGrupo->numrows == 0) {

					throw new Exception("Não Foi possivel encontrar grupo de lancamentos.");
				}
				$oDaoRetencaoCorrente  = db_utils::getDao("retencaocorgrupocorrente");
				$oDaoRetencaoCorrente->e47_corgrupocorrente = db_utils::fieldsMemory($rsCorGrupo,0)->k105_sequencial;
				$oDaoRetencaoCorrente->e47_retencaoreceita  = $oRetencao->e23_sequencial;
				$oDaoRetencaoCorrente->incluir(null);
				if ($oDaoRetencaoCorrente->erro_status == 0) {
					throw new Exception("Não Foi possivel vincular a retencao a autenticação.\n{$oDaoRetencaoCorrente->erro_msg}");
				}
			}
		}
		return true;
	}

	/**
	 * Retorna as retencoes do movimento
	 *
	 * @param integer $iCodMovimento Código do movimennto da Agenda
	 * @param integer $iTipoRetencao tipo do calculo da retencao
	 */
	function getRetencoesByMovimento($iCodMovimento, $iTipoRetencao='', $lRecolhida = false, $lPrincipal = false) {

		$sRecolhida = $lRecolhida?"true":"false";
		$oDaoRetencaoReceitas = db_utils::getDao("retencaoreceitas");
		$sWhere               = "";
		if ($iTipoRetencao != '') {
			$sWhere .= " and e23_retencaotiporec = {$iTipoRetencao}";
		}
		if ($lPrincipal) {
			$sWhere .= " and e27_principal is true ";
		}
		$sSqlRetencaoReceitas = $oDaoRetencaoReceitas->sql_query_notas(null,
				"distinct tabrec.*,retencaotiporec.*,
				retencaoreceitas.*,e27_principal,k108_slip,null as k17_slip ",
				"e21_sequencial
				",
				"e27_empagemov = {$iCodMovimento}
		and e23_recolhido = {$sRecolhida}
		and e23_ativo     = true
		and e71_anulado   = false {$sWhere}"
		);

		$rsRetencoes = $oDaoRetencaoReceitas->sql_record($sSqlRetencaoReceitas);
		$aRetencoes  = false;
		if ($oDaoRetencaoReceitas->numrows > 0) {

			if ($iTipoRetencao != "") {

				$aRetencoes = db_utils::fieldsMemory($rsRetencoes, 0, null,null, $this->lEncodeUrl);
			} else {
				$aRetencoes = db_utils::getCollectionByRecord($rsRetencoes, null,null, $this->lEncodeUrl);
			}
		}
		return $aRetencoes;
	}

	/**
	 * Realiza o estorno da retencao, anulando seus recibos, e planilhas
	 * , caso houver
	 *
	 * @param  integer $oRetencao Objeto com informaçõs da retencao
	 * @return void
	 */
	function estornarRetencoes($oRetencao) {

		if (!is_object($oRetencao)) {
			throw new Exception("Erro [1] - aRetenções deve ser um Array");
		}

		$oRetencao     = $this->getRetencaoByCodigo($oRetencao->iRetencao, true);
		$iContaOriginal = $this->getConta();
		require_once(modification("model/recibo.model.php"));
		require_once(modification("model/Dotacao.model.php"));
		require_once(modification('classes/ordemPagamento.model.php'));

		$oDaoRetencaoReceitas                 = db_utils::getDao("retencaoreceitas");
		$oDaoRetencaoReceitas->e23_sequencial = $oRetencao->e23_sequencial;
		$oDaoRetencaoReceitas->e23_recolhido  = "true";
		$oDaoRetencaoReceitas->e23_ativo      = "false";
		$oDaoRetencaoReceitas->alterar($oRetencao->e23_sequencial);
		if ($oDaoRetencaoReceitas->erro_status == 0) {

			$sErroMsg  = "Erro [2] - Não Foi possível recolher a retenção ({$oRetencao->e23_sequencial})";
			throw new Exception($sErroMsg);
		}
		$oNotaLiquidacao = new ordemPagamento($this->iNotaLiquidacao);
		$oNotaLiquidacao->getDadosOrdem();

		$oDotacao        = new Dotacao($oNotaLiquidacao->oDadosOrdem->e60_coddot,
		$oNotaLiquidacao->oDadosOrdem->e60_anousu
		);
		/*
		 * Verificamos se a receita da retencao não e uma receita-extra(receita encontra-se na tabela tabplan)
		*/
		if ($this->getTipoReceita($oRetencao->k02_codigo) == 2) {

			/*
			 * Buscamos na tabela saltesextra para verificar se foi cadastrado uma conta
			* para realizar o recolhimento de receita.
			*/

			$oDaoSaltesExtra = db_utils::getDao("saltesextra");
			$sSqlContaExtra  = $oDaoSaltesExtra->sql_query_file(null, "*",null,"k109_saltes = {$iContaOriginal}");
			$rsContaExtra    = $oDaoSaltesExtra->sql_record($sSqlContaExtra);
			if ($oDaoSaltesExtra->numrows == 1) {

				$iContaExtra = db_utils::fieldsMemory($rsContaExtra, 0)->k109_contaextra;
				if (!empty($iContaExtra)) {

					$this->setConta($iContaExtra);
					/*
					 * Pesquisamos se foi incluido valores para fazer transferencia bancaria.
					* caso exista transferencia, devemos deduzir o valor da retencao do valor a transferir
					*/
					if ($oRetencao->e27_empagemov != "") {

						$oDaoEmpageMovSlips = db_utils::getDao("empagemovslips");
						$sSqlSlipMovimento  = $oDaoEmpageMovSlips->sql_query_file(null,"*",null,"k107_empagemov = {$oRetencao->e27_empagemov}");
						$rsMovimento        = $oDaoEmpageMovSlips->sql_record($sSqlSlipMovimento);
						if ($oDaoEmpageMovSlips->numrows > 0) {

							$oMovimentoSlip = db_utils::fieldsMemory($rsMovimento,0);
							$nValorRetencao                      = round(($oMovimentoSlip->k107_valor + $oRetencao->e23_valorretencao),2);
							$oDaoEmpageMovSlips->k107_valor      = "$nValorRetencao";
							$oDaoEmpageMovSlips->k107_sequencial = $oMovimentoSlip->k107_sequencial;
							$oDaoEmpageMovSlips->alterar($oMovimentoSlip->k107_sequencial);

							if ($oDaoEmpageMovSlips->erro_status == 0) {

								$sErroMsg  = "Erro [10] - Não Foi possível estornar o recolhimento a retenção ({$iCodRetencao})";
								throw new Exception($sErroMsg);

							}
						}
					}
				}
			}
		}

		require_once(modification('std/db_stdClass.php'));
		$oInstit = db_stdClass::getDadosInstit();
		if ($oRetencao->e23_valorretencao > 0) {

			if ($oRetencao->e21_retencaotipocalc != 5 || $oInstit->prefeitura == "f") {
				// echo 'seg'; die();
				$oReciboAvulso = new recibo(1, $this->getNumCgm());
				$oReciboAvulso->setConta($this->getConta());
				$oReciboAvulso->setGrupoAutenticacao($this->getGrupoAutenticacao());
				$oReciboAvulso->adicionarRecurso($oDotacao->getRecurso());
				if (isset($oReciboAvulso)) {

					$iNumpre = $this->getNumpreRetencao($oRetencao->e23_sequencial);
					$sSqlBuscaCaracteristica = "select empempenho.e60_concarpeculiar
					                              from retencaoreceitas
					                                   inner join retencaoempagemov on retencaoempagemov.e27_retencaoreceitas = retencaoreceitas.e23_sequencial
					                                   inner join empagemov         on empagemov.e81_codmov = retencaoempagemov.e27_empagemov
					                                   inner join empempenho        on empempenho.e60_numemp = empagemov.e81_numemp
					                             where retencaoreceitas.e23_sequencial = {$oRetencao->e23_sequencial}";
					$sCaracteristicaPeculiar = db_utils::fieldsMemory(db_query($sSqlBuscaCaracteristica), 0)->e60_concarpeculiar;

					$oReciboAvulso->estornarRecibo($iNumpre, $sCaracteristicaPeculiar);

				}
			} else {

				require_once(modification('model/planilhaRetencao.model.php'));
				$oReciboDebito = new  recibo(2, null,25);
				$oReciboDebito->setConta($this->getConta());
				$oReciboDebito->setGrupoAutenticacao($this->getGrupoAutenticacao());
				$iNumpre = $this->getNumpreRetencao($oRetencao->e23_sequencial);
				$oReciboDebito->adicionarRecurso($oDotacao->getRecurso());

				$sSqlBuscaCaracteristica = "select empempenho.e60_concarpeculiar
                              				from retencaoreceitas
                                  		 		inner join retencaoempagemov on retencaoempagemov.e27_retencaoreceitas = retencaoreceitas.e23_sequencial
                                  			 	inner join empagemov         on empagemov.e81_codmov = retencaoempagemov.e27_empagemov
                                  				inner join empempenho        on empempenho.e60_numemp = empagemov.e81_numemp
                              				where retencaoreceitas.e23_sequencial = {$oRetencao->e23_sequencial}";
				$sCaracteristicaPeculiar = db_utils::fieldsMemory(db_query($sSqlBuscaCaracteristica), 0)->e60_concarpeculiar;

				$oReciboDebito->estornarRecibo($iNumpre, $sCaracteristicaPeculiar);
				$oPlanilha = $this->getPlanilhaRetencao($oRetencao->e23_sequencial);
				if (!$oPlanilha) {
					throw new Exception("Erro [3] - Não foi possivel encontrar planilha de retencao.");
				} else {

					$oPlanilhaRetencao  = new planilhaRetencao($oPlanilha->q20_planilha);
					$oPlanilhaRetencao->anularPlanilha("Estorno de recolhimento de Retenção");

				}

			}
			/**
			 * Vinculamos a retencao ao grupo do lancamento
			 */
			$oDaoCorrenteGrupo = db_utils::getDao("corgrupocorrente");
			$sSqlCorgrupo      = $oDaoCorrenteGrupo->sql_query_file(null,
					"*",
					"k105_autent desc limit 1",
					"k105_corgrupo = ".$this->getGrupoAutenticacao());
			$rsCorGrupo        = $oDaoCorrenteGrupo->sql_record($sSqlCorgrupo);
			if ($oDaoCorrenteGrupo->numrows == 0) {

				throw new Exception("Não Foi possivel encontrar grupo de lancamentos.");
			}
			$oDaoRetencaoCorrente  = db_utils::getDao("retencaocorgrupocorrente");
			$oDaoRetencaoCorrente->e47_corgrupocorrente = db_utils::fieldsMemory($rsCorGrupo,0)->k105_sequencial;
			$oDaoRetencaoCorrente->e47_retencaoreceita  = $oRetencao->e23_sequencial;
			$oDaoRetencaoCorrente->incluir(null);
			if ($oDaoRetencaoCorrente->erro_status == 0) {
				throw new Exception("Não Foi possivel vincular a retencao a autenticação.\n{$oDaoRetencaoCorrente->erro_msg}");
			}
		}
		return true;
	}

	/**
	 * retorna o numpre que a retencao foi autenticada
	 *
	 * @param integer $iRetencao código da retencao (retencaoreceitas.e23_sequencial);
	 * @return integer
	 */
	function getNumpreRetencao($iRetencao) {

		$oDaoRetencaoCorrente = db_utils::getdao("retencaocorgrupocorrente");
		$iNumpre              = 0;
		$sSqlNumpre           = $oDaoRetencaoCorrente->sql_query_numpre(null,
				"k12_numpre",
				null,
				"e23_sequencial = {$iRetencao}
		and k105_corgrupotipo = 3"
		);

		$rsNumpre = $oDaoRetencaoCorrente->sql_record($sSqlNumpre);
		if ($oDaoRetencaoCorrente->numrows == 1) {

			$iNumpre = db_utils::fieldsMemory($rsNumpre, 0)->k12_numpre;
		}
		return $iNumpre;
	}

	/**
	 * Retorna o codigo da planilha de reclhimentos da retencao
	 *
	 * @param integer $iRetencao codigo da retencao (retencaoreceitas.e23_sequencial)
	 * @return integer codigo da planilha
	 */
	function getPlanilhaRetencao($iRetencao) {

		$sSqlPlanilha  = "select * ";
		$sSqlPlanilha .= "  from issplan      ";
		$sSqlPlanilha .= " where q20_numpre = ".$this->getNumpreRetencao($iRetencao);
		$rsPlanilha    = db_query($sSqlPlanilha);
		$oPlaniha      = false;
		if (pg_num_rows($rsPlanilha) > 0) {

			$oPlanilha = db_utils::fieldsMemory($rsPlanilha, 0);
		}
		return $oPlanilha;
	}

	/**
	 * Define o grupo de autenticação
	 *
	 * @param integer $iCorGrupo
	 */
	function setGrupoAutenticacao($iCorGrupo) {
		$this->iGrupoAutenticacao = $iCorGrupo;
	}

	/**
	 * Retorna o grupo de autenticação
	 *
	 * @return unknown
	 */
	function getGrupoAutenticacao() {
		return $this->iGrupoAutenticacao;
	}

	/**
	 * Define a conta pagadora
	 *
	 * @param integer $iConta
	 */
	function setConta($iConta) {
		$this->iConta = $iConta;
	}

	/**
	 * Retorna a conta definida pelo usuario
	 *
	 * @return integer
	 */
	function getConta() {
		return $this->iConta;
	}

	/**
	 * Define o cgm da retencao. é usando para informacao na emissao dos recibos e planilhas
	 *
	 * @param integer $iNumCgm
	 */
	function setNumCgm ($iNumCgm) {
		$this->iNumCgm = $iNumCgm;
	}

	/**
	 * Retorna o cgm informado.
	 *
	 * @return integr
	 */
	function getNumCgm() {
		return $this->iNumCgm;
	}

	/**
	 * Configura o pagamento  das retencoes ativas do movimento
	 * Cria um movimento novo com as informações do movimento original,
	 * e configura o movimento atual com as informações das retenções
	 *
	 */
	function configurarPagamentoRetencoes() {

		if (!db_utils::inTransaction()) {
			throw new Exception("Erro [0 - RetencaoNotas] - Não Existe transação ativa");
		}


		$iCodigoMovimento = $this->getCodigoMovimento();
		if ($iCodigoMovimento == null) {
			throw new Exception("Erro [2] - Movimento não Informado");
		}


		$aRetencoes          = $this->getRetencoesFromDB($this->iNotaLiquidacao,false);
		$nValorTotalRetencao = $this->getValorRetencaoMovimento($iCodigoMovimento,false);
		if ($nValorTotalRetencao == 0) {
			throw new Exception("Erro [2] - Movimento sem Retenções");
		}
    require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
		$oAgendaPagamento    = new agendaPagamento();
		$sJoin  = " left join empagenotasordem on e81_codmov  = e43_empagemov     ";
		$sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
		$oMovimento          =  $oAgendaPagamento->getMovimentosAgenda("e81_codmov = {$iCodigoMovimento}", $sJoin, false,false);
		$oAgendaPagamento->setCodigoAgenda($oMovimento[0]->e80_codage);
		/**
		 * Verificamos se o movimento já está configurado, caso nao esteje, devemos cancelar a operação
		 */
		$sSqlForma = "select e97_codforma from empagemovforma where e97_codmov = {$iCodigoMovimento}";
		$rsForma   = db_query($sSqlForma);
		$iCodForma = db_utils::fieldsMemory($rsForma,0)->e97_codforma;
		if ($iCodForma == '') {
			throw new Exception("Erro [7] - Movimento não configurado");
		}
		$sSqlContaPagadora = "select e85_codtipo from empagepag where e85_codmov = {$iCodigoMovimento}";
		$rsContaPagadora   = db_query($sSqlForma);
		$iContaPagadora    = db_utils::fieldsMemory($rsForma,0)->e97_codforma;
		if ($iContaPagadora == '') {
			throw new Exception("Erro [7] - Movimento sem conta pagadora");
		}
		$oNovoMovimento = new stdClass();
		$oNovoMovimento->iCodTipo = $oMovimento[0]->e85_codtipo;
		$oNovoMovimento->iNumEmp  = $oMovimento[0]->e60_numemp;
		$oNovoMovimento->nValor   = round($oMovimento[0]->e81_valor - $nValorTotalRetencao, 2);
		$oNovoMovimento->iCodNota = $oMovimento[0]->e50_codord;
		$oNovoMovimento->iForma   = $oMovimento[0]->e97_codforma;
		$iCodigoNovoMovimento     = $oAgendaPagamento->addMovimentoAgenda(1, $oNovoMovimento);

		/*
		 * Verificamos se o movimento possui cheque, caso possuir, vinculamos o cheque ao
		* movimento novo . tabelas(empageconf, empageconfgera)
		*
		*/
		if ($oMovimento[0]->e91_codmov != "" ) {

			$sUpdateCheque  = " update empageconfche set e91_codmov = {$iCodigoNovoMovimento} where e91_codmov = {$iCodigoMovimento}";
			$rsUpdateCheque = db_query($sUpdateCheque);
			if (!$rsUpdateCheque) {
				throw new Exception("Erro [3] - Erro ao Configurar retenções. Não Foi Possivel vincular cheque.");
			}
			$sUpdateConfGera  = " update empageconfgera set e90_codmov = {$iCodigoNovoMovimento} where e90_codmov = {$iCodigoMovimento}";
			$rsUpdateConfGera = db_query($sUpdateConfGera);
			if (!$rsUpdateConfGera) {
				throw new Exception("Erro [4] - Erro ao Configurar retenções. Não Foi Possivel vincular cheque.");
			}
		}

		/**
		 * Verificamos se o movimento já esta em algum TXT para bancos ;
		 */
		if ($oMovimento[0]->e90_codmov != "" ) {

			$sUpdateConfGera  = " update empageconfgera set e90_codmov = {$iCodigoNovoMovimento} where e90_codmov = {$iCodigoMovimento}";
			$rsUpdateConfGera = db_query($sUpdateConfGera);
			if (!$rsUpdateConfGera) {
				throw new Exception("Erro [4] - Erro ao Configurar retenções. Não Foi Possivel vincular arquivo txt.");
			}
		}

		$sUpdateConf    = "update empageconf set e86_codmov = {$iCodigoNovoMovimento} where e86_codmov = {$iCodigoMovimento}";
		$rsUpdateConf   = db_query($sUpdateConf);
		if (!$rsUpdateConf) {
			throw new Exception("Erro [4] - Erro ao Configurar retenções.");
		}
		/*
		 * Alteramos o movimento atual com as informações do pagamento a retencao
		*/
		$oDaoEmpageMov             = db_utils::getDao("empagemov");
		$oDaoEmpageMov->e81_codmov = $iCodigoMovimento;
		$oDaoEmpageMov->e81_valor  = "0";
		$oDaoEmpageMov->alterar($iCodigoMovimento);
		if ($oDaoEmpageMov->erro_status == 0) {
			throw new Exception("Erro [5] - Erro ao Configurar retenções. Não Foi Possível configurar movimento.");
		}
		/*
		 * Incluimos empageconf para o movimento novo,
		*/
		$oDaoEmpageConf  = db_utils::getDao("empageconf");
		$oDaoEmpageConf->e86_cheque  = "0";
		$oDaoEmpageConf->e86_data    = $oMovimento[0]->e80_data;
		$oDaoEmpageConf->e86_codmov  = $iCodigoMovimento;
		$oDaoEmpageConf->e86_correto = "true";
		$oDaoEmpageConf->incluir($iCodigoMovimento);
		if ($oDaoEmpageConf->erro_status == 0) {
			throw new Exception("Erro [6:retencaoNota] - Erro ao Configurar retenções. Não Foi Possível configurar movimento.");

		}
		/**
		 * Verificamos se o movimento nao esta em nenhuma ordem de pagamento.
		 */

		if ($oMovimento[0]->e43_sequencial != "") {

			$sUpdateOrdemPag  = " update empagenotasordem set e43_empagemov = {$iCodigoNovoMovimento},";
			$sUpdateOrdemPag .= "        e43_valor = ".round($oMovimento[0]->e81_valor - $nValorTotalRetencao, 2);
			$sUpdateOrdemPag .= " where  e43_empagemov = {$iCodigoMovimento}";
			$rsUpdateOrdemPag = db_query($sUpdateOrdemPag);
			echo pg_last_error();
			if (!$rsUpdateOrdemPag) {
				throw new Exception("Erro [4] - Erro ao Configurar Ordem de pagamento. Não Foi Possivel vincular Ordem de pagamento Auxiliar.");
			}

		}
		/**
		 * Verificamos se o usuario já configurou a forma de pagamento.
		 * Caso já esteja configurado , modificamos para a forma de debito em conta;
		 * senao devemso incluir novamente.
		 */
		$oDaoEmpagemovForma = db_utils::getDao("empagemovforma")  ;
		$oDaoEmpagemovForma->excluir($iCodigoMovimento);
		$oDaoEmpagemovForma->e97_codforma = 4;
		$oDaoEmpagemovForma->incluir($iCodigoMovimento);
		if ($oDaoEmpagemovForma->erro_status == 0) {

			$sErroMsg = "Erro [6] - Erro Ao Definir forma de pagamento  (Movimento {$iCodigoMovimento})";
			throw new Exception($sErroMsg);

		}
		/*
		 * Iteramos sobre as retencoes informadas para vincular o movimento novo as retenções.
		*/
		$oDaoRetencaoEmpageMov = new cl_retencaoempagemov();
		foreach ($aRetencoes as $oRetencao) {

			$oDaoRetencaoEmpageMov->e27_empagemov        =  $iCodigoNovoMovimento;
			$oDaoRetencaoEmpageMov->e27_retencaoreceitas = $oRetencao->e23_sequencial;
			$oDaoRetencaoEmpageMov->e27_principal        = "false";
			$oDaoRetencaoEmpageMov->incluir(null);
			if ($oDaoRetencaoEmpageMov->erro_status == 0) {
				throw new Exception("Erro [7:retencaoNota] - Erro ao vincular retenções ao movimento.\nErro:{$oDaoRetencaoEmpageMov->erro_msg}");
			}
		}
	}


	/**
	 * verifica o tipo da receita
	 * retorna 1 para orcamentaria
	 *         2 para extra-orcamentaria
	 *
	 * @param integer $iReceita codigo da receita tabrec.k02_codigo
	 * @return integer
	 */
	function getTipoReceita($iReceita) {


		$sSqlTipoRec  = " SELECT k02_tipo                                                   ";
		$sSqlTipoRec .= "  from tabrec                                                      ";
		$sSqlTipoRec .= "       inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo ";
		$sSqlTipoRec .= " where tabrec.k02_codigo = {$iReceita} and k02_anousu = ".db_getsession("DB_anousu");
		$rsTipoRec   = db_query($sSqlTipoRec);
		if (pg_num_rows($rsTipoRec) == 1) {
			$iTipoReceita = 2;
		} else {
			$iTipoReceita = 1;
		}
		return $iTipoReceita;
	}

	/**
	 * Retorna as retencoes pelo codigo de lancamento
	 *
	 * @param integer $iCodigoRetencao codigo da retencao (retencaoreceitas.e23_sequencial)
	 * @param boolean $lRecolhida retencao recolhida ou nao
	 * @return array collection de retencoes
	 */
	function getRetencaoByCodigo($iCodigoRetencao, $lRecolhida = false) {

		$sRecolhida = $lRecolhida?"true":"false";
		$oDaoRetencaoReceitas = db_utils::getDao("retencaoreceitas");
		$sWhere               = "";
		$sWhere              .= " and e27_principal is true ";
		$sSqlRetencaoReceitas = $oDaoRetencaoReceitas->sql_query_notas(null,
				"distinct tabrec.*,retencaotiporec.*,
				retencaoreceitas.*,e27_principal,k108_slip,e27_empagemov ",
				"e21_sequencial
				",
				"e23_sequencial = {$iCodigoRetencao}
		and e23_recolhido = {$sRecolhida}
		and e23_ativo     = true
		and e71_anulado   = false {$sWhere}"
		);

		$rsRetencoes = $oDaoRetencaoReceitas->sql_record($sSqlRetencaoReceitas);
		$oRetencao   = false;
		if ($oDaoRetencaoReceitas->numrows > 0) {
			$oRetencao = db_utils::fieldsMemory($rsRetencoes, 0, null,null, $this->lEncodeUrl);
		}
		return $oRetencao;
	}

	/**
	 * Retorna se a nota possui retencoes do mes anterior
	 * @return boolean
	 */
	function hasRetencoesMesAnterior() {

		$lRetorno = false;
		if ($this->getCodigoMovimento() != null) {

			$iCodMov = $this->getCodigoMovimento();
			$sSqlTotalRetencao = "select fc_validaretencoesmesanterior({$iCodMov},null) as validar";
			$oRetorno = db_utils::fieldsMemory(db_query($sSqlTotalRetencao), 0);
			$lRetorno = $oRetorno->validar == "t"?true:false;

		}
		return $lRetorno;

	}

	/**
	 * Valida se o valor total de todas as retenções da nota não ultrapassa o valor total da nota.
	 * @param stdClass $oNovaRetencao Objeto contando as informações de uma nova retenção que está sendo adicionada.
	 *
	 * @return bool True caso o valor total da retenção seja compatível com o valor da nota.
	 */
	public function validaValorRetencoes($oNovaRetencao = null) {

		$nValorNota        = 0;
		$nValorRetidoTotal = 0;
		if ($oNovaRetencao != null) {
			$nValorNota = $oNovaRetencao->nValorNota;
		}
		$aRetencoes = $this->getRetencoes();

		//Não há o que validar.
		if ($oNovaRetencao == null && empty($aRetencoes)) {
			return true;
		}

		if (!empty($aRetencoes)) {
			$nValorNota = current($aRetencoes)->e23_valor;
		}

		foreach ($aRetencoes as $oRetencao) {

			//Pula retenção se a "nova" já existe no array, pois esta será alterada pela nova.
			if ($oNovaRetencao != null && $oRetencao->e21_sequencial == $oNovaRetencao->iCodigoRetencao) {
				continue;
			}
			$nValorRetidoTotal += $oRetencao->e23_valorretencao;
		}

		if ($oNovaRetencao != null) {
			$nValorRetidoTotal += $oNovaRetencao->nValorRetencao;
		}

		return round($nValorRetidoTotal, 2) <= round($nValorNota, 2);
	}
}