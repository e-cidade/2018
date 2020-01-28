<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


define("URL_MENSAGEM_EVENTOCONTABIL", "financeiro.contabilidade.EventoContabil.");

/**
 * EventoContabil
 * Model para controle de transa��o
 * @author  matheus.felini@dbseller.com.br
 * @version $Revision: 1.22 $
 * @package contabilidade
 */
class EventoContabil {

	/**
   * C�digo Sequencial da Transa��o
   * @var integer
	 */
	protected $iSequencialTransacao;

	/**
   * Ano da Transa��o
   * @var integer
	 */
	protected $iAnoUso;

	/**
   * C�digo do Documento
   * @var integer
	 */
	protected $iCodigoDocumento;

	/**
	 * C�digo da Institui��o
	 * @var integer
	 */
	protected $iInstituicao;

	/**
	 * Descricao do documento
	 * @var string
	 */
	protected $sDescricaoDocumento;

	/**
	 * Cole��o de Lan�amentos de um EventoContabil
	 * @var array
	 */
	protected $aEventoContabilLancamento;

	/**
	 * C�digo do Lancamento Executado
	 * @var integer
	 */
	protected $iCodigoLancamento;

	/**
	 * Evento contabil de inclusao
	 * @var boolean
	 */
	protected $lEventoInclusao;

	/**
	 * Total de instancias
	 * @var integer
	 */
	static $iTotalInstancias = 0;

	/**
	 * O construtor buscar� a transa��o informada na assinatura e todos os lan�amentos desta transa��o
	 * @param integer $iCodigoDocumento c45_coddoc
	 * @param integer $iAno c45_anousu
	 * @throws Exception
	 */
	public function __construct($iCodigoDocumento = null, $iAno = null) {

	  EventoContabil::$iTotalInstancias ++;
		if (!empty($iCodigoDocumento) && !empty($iAno)) {

			$oDaoContrans      = db_utils::getDao('contrans');
			$sWhereContrans    = "c45_coddoc = {$iCodigoDocumento} and c45_anousu = {$iAno} and c45_instit = ".db_getsession("DB_instit");
			$sSqlBuscaContrans = $oDaoContrans->sql_query_vinculo(null, "*", null, $sWhereContrans);
			$rsBuscaContrans   = $oDaoContrans->sql_record($sSqlBuscaContrans);

			if ($oDaoContrans->numrows == 0) {
				throw new Exception("Documento {$iCodigoDocumento} n�o encontrado para o ano {$iAno}.");
			}

			/*
			 * Setamos as propriedades da transa��o
			 */
			$oDadoLancamento = db_utils::fieldsMemory($rsBuscaContrans, 0);

			/**
			 * Evento de inclusao
			 */
			if ( !empty($oDadoLancamento->c115_conhistdocinclusao) && $oDadoLancamento->c115_conhistdocinclusao == $iCodigoDocumento ) {
				$this->lEventoInclusao = true;
			}

			/**
			 * Evento de estorno
			 */
			if ( !empty($oDadoLancamento->c115_conhistdocestorno) && $oDadoLancamento->c115_conhistdocestorno == $iCodigoDocumento  ) {
				$this->lEventoInclusao = false ;
			}

			$this->setAnoUso($iAno);
			$this->setCodigoDocumento($iCodigoDocumento);
			$this->setInstituicao($oDadoLancamento->c45_instit);
			$this->setSequencialTransacao($oDadoLancamento->c45_seqtrans);
			$this->setDescricaoDocumento($oDadoLancamento->c53_descr);
		}		
	}

	/**
	 * Inclui ou Altera uma transa��o
	 * @throws Exception
	 * @return boolean true
	 */
	public function salvar() {

		if ($this->getCodigoDocumento() == "") {
			throw new Exception("� necess�rio informar o c�digo do documento.");
		}
		if ($this->getInstituicao() == "") {
			throw new Exception("� necess�rio informar o c�digo da institui��o.");
		}
		if ($this->getAnoUso() == "") {
			throw new Exception("� necess�rio informar ano.");
		}

		$oDaoContrans 						  = db_utils::getDao('contrans');
		$oDaoContrans->c45_seqtrans = $this->getSequencialTransacao();
		$oDaoContrans->c45_anousu   = $this->getAnoUso();
		$oDaoContrans->c45_coddoc   = $this->getCodigoDocumento();
		$oDaoContrans->c45_instit   = $this->getInstituicao();

		if ($this->getSequencialTransacao() == "") {

		  $oDaoContransVerifica	= db_utils::getDao('contrans');
		  $sWhere               = "    c45_anousu = ".db_getsession("DB_anousu");
		  $sWhere              .= "and c45_coddoc = ".$this->getCodigoDocumento();
		  $sWhere              .= "and c45_instit = ".db_getsession("DB_instit");
		  $sSQL                 = $oDaoContransVerifica->sql_query_file(null,"1",null,$sWhere);
		  $rsContrasVerifica    = $oDaoContransVerifica->sql_record($sSQL);

		  if ($oDaoContransVerifica->numrows > 0) {

		    $sMensagem   = "N�o � poss�vel cadastrar novo documento.\n";
		    $sMensagem  .= "O Documento {$this->getCodigoDocumento()} j� existe para o ano ".db_getsession("DB_anousu");
		    throw new Exception ($sMensagem);

		  }

			$oDaoContrans->incluir(null);
			$this->setSequencialTransacao($oDaoContrans->c45_seqtrans);
		} else {
			$oDaoContrans->alterar($this->getSequencialTransacao());
		}

		if ($oDaoContrans->erro_status == 0) {
			throw new Exception("N�o foi poss�vel salvar os dados da transa��o.\n\n{$oDaoContrans->erro_msg}");
		}
		return true;
	}

	/**
	 * Exclui todos os dados de um evento cont�bil
	 * @throws Exception
	 */
	public function excluir() {

		$oDaoContrans            = db_utils::getDao("contrans");
		$oDaoContrans->excluir($this->getSequencialTransacao());

		if ($oDaoContrans->erro_status == 0) {
			throw new Exception("N�o foi poss�vel excluir a transa��o que mantinha o lan�amento exclu�do.\n\n{$oDaoContrans->erro_msg}");
		}
		return true;
	}

	/**
	 * Retorna o c�digo do lan�amento
	 * @return integer
	 */
	public function getCodigoLancamento() {
	  return $this->iCodigoLancamento;
	}

	/**
	 * Seta o c�digo do documento
	 * @param integer $iCodigoDocumento
	 */
	public function setCodigoDocumento($iCodigoDocumento) {
		$this->iCodigoDocumento = $iCodigoDocumento;
	}

	/**
	 * Retorna o c�digo do documento
	 * @return integer
	 */
	public function getCodigoDocumento() {
		return $this->iCodigoDocumento;
	}

	/**
	 * Seta valor para o ano uso
	 * @param integer $iAnoUso
	 */
	public function setAnoUso($iAnoUso) {
		$this->iAnoUso = $iAnoUso;
	}

	/**
	 * Retorna o ano uso da transa��o
	 * @return integer
	 */
	public function getAnoUso() {
		return $this->iAnoUso;
	}

	/**
	 * Seta valor para a institui��o
	 * @param integer $iInstituicao
	 */
	public function setInstituicao($iInstituicao) {
		$this->iInstituicao = $iInstituicao;
	}

	/**
	 * Retorna institui��o da transa��o
	 * @return integer
	 */
	public function getInstituicao() {
		return $this->iInstituicao;
	}

	/**
	 * Retorna o sequencial da transa��o
	 * @return integer
	 */
	public function getSequencialTransacao() {
		return $this->iSequencialTransacao;
	}

	/**
	 * Seta valor para o sequencial da transa��o
	 * @param integer $iSequencialTransacao
	 */
	public function setSequencialTransacao($iSequencialTransacao)	{
		$this->iSequencialTransacao = $iSequencialTransacao;
	}

	/**
	 * Retorna uma cole��o de lan�amentos cont�beis
	 * @return array
	 */
	public function getEventoContabilLancamento() {

	  if (count($this->aEventoContabilLancamento) == 0) {

	    /**
	     *  Criamos um array contendo a cole��o de lancamentos da transacao
	     */
	    $oDaoContranslan     = db_utils::getDao('contranslan');
	    $sWhereLancamento    = "c46_seqtrans = {$this->getSequencialTransacao()}";
	    $sSqlBuscaLancamento = $oDaoContranslan->sql_query_file(null, "c46_seqtranslan", "c46_ordem", $sWhereLancamento);
	    $rsBuscaLancamento   = $oDaoContranslan->sql_record($sSqlBuscaLancamento);
	    if ($oDaoContranslan->numrows > 0) {

	    	for ($iRowLancamento = 0; $iRowLancamento < $oDaoContranslan->numrows; $iRowLancamento++) {

	    		$iCodigoLancamento         = db_utils::fieldsMemory($rsBuscaLancamento, $iRowLancamento)->c46_seqtranslan;
	    		$oEventoContabilLancamento = EventoContabilLancamentoRepository::getEventoByCodigo($iCodigoLancamento);
	    		$this->adicionarEventoContabilLancamento($oEventoContabilLancamento);
	    	}
	    }
	  }

		return $this->aEventoContabilLancamento;
	}

	/**
	 * Adiciona um Lan�amento a cole��o de lan�amentos
	 * @param EventoContabilLancamento $oLancamento
	 */
	public function adicionarEventoContabilLancamento(EventoContabilLancamento $oLancamento) {
		$this->aEventoContabilLancamento[] = $oLancamento;
	}

	/**
	 * Retorna um determinado lan�amento de acordo com o c�digo sequencial do lan�amento passado via par�metro
	 * @param integer $iCodigoSequencialLancamento
	 * @return mixed EventoContabilLancamento boolean
	 */
	public function getEventoContabilLancamentoPorCodigo($iCodigoSequencialLancamento) {

	  if (count($this->aEventoContabilLancamento) == 0) {
	    $this->getEventoContabilLancamento();
	  }

	  foreach ($this->aEventoContabilLancamento as $oLancamentoEventoContabil) {

	    if ($oLancamentoEventoContabil->getSequencialLancamento() == $iCodigoSequencialLancamento) {
        return $oLancamentoEventoContabil;
	    }
	  }
	  return false;
	}


	/**
	 * Reprocessa o Lancamento cont�bil do documento
	 * @param ILancamentoAuxiliar $oLancamentoAuxiliar
	 */

	public function reprocessaLancamentos($iCodLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar, $sDataLancamento = null) {

	  $dtDataUsu = date("Y-m-d", db_getsession('DB_datausu'));
	  if (!empty($sDataLancamento)) {
	    $dtDataUsu = $sDataLancamento;
	  }

	  $nValorTotal = $oLancamentoAuxiliar->getValorTotal();
	  $iAnoUsu     = db_getsession("DB_anousu");

	  //Seta o c�digo do lan�amento
	  $this->iCodigoLancamento = $iCodLancamento;
		$this->excluirRegistrosAnteriores();
	  
	  /**
	   * Efetua o lan�amentos nas contas do Evento
	   */
	  foreach ($this->getEventoContabilLancamento() as $oLancamentoContabil) {

	    $oLancamentoAuxiliar->setHistorico($oLancamentoContabil->getHistorico());

	    $oLancamentoContabil->executa($iCodLancamento,
	                                  $this->iCodigoDocumento,
	                                  $oLancamentoAuxiliar,
	                                  $dtDataUsu
	    );
	  }
	  return $iCodLancamento;
	}
	
	
	private function excluirRegistrosAnteriores() {
		
		/**
		 * Excluimos os lan�amentos cont�beis contacorrentedetalheconlancamval para ent�o incluirmos nas contas corretas
		 */
		$oDaoConlancamval =  db_utils::getDao("conlancamval");
		$sSqlConlancamval = $oDaoConlancamval->sql_query_file(null, "c69_sequen", null,  "c69_codlan = $this->iCodigoLancamento");
		$rsConlancamval   = $oDaoConlancamval->sql_record($sSqlConlancamval);

		for($iConlancamval = 0 ; $iConlancamval < $oDaoConlancamval->numrows; $iConlancamval++) {
			
			$c69_sequen = db_utils::fieldsMemory($rsConlancamval, $iConlancamval)->c69_sequen;
			$oDaoExcluirDetalheConlancamVal = db_utils::getDao("contacorrentedetalheconlancamval");
			$oDaoExcluirDetalheConlancamVal->excluir(null, "c28_conlancamval = {$c69_sequen} ");

			if ($oDaoExcluirDetalheConlancamVal->erro_status == "0") {
				throw new Exception("Erro excluindo contacorrentedetalheconlancamval. \\n".$oDaoExcluirDetalheConlancamVal->erro_msg);
			}
			 
			$oDaoConlancamValExclusao = db_utils::getDao("conlancamval");
			/**
			 * Excluimos os lan�amentos cont�beis para ent�o incluirmos nas contas corretas
			*/
			$oDaoConlancamValExclusao->excluir(null,"c69_sequen = {$c69_sequen}");
			if ($oDaoConlancamValExclusao->erro_status == "0") {
				throw new Exception("Erro excluindo conlancamval. \\n".$oDaoConlancamValExclusao->erro_msg);
			}
		}
	}

	/**
	 * Executa o Lancamento cont�bil do documento
	 * @param ILancamentoAuxiliar $oLancamentoAuxiliar
	 */
	public function executaLancamento(ILancamentoAuxiliar $oLancamentoAuxiliar, $sDataLancamento = null) {

 	  $dtDataUsu = date("Y-m-d", db_getsession('DB_datausu'));
		if (!empty($sDataLancamento)) {
			$dtDataUsu = $sDataLancamento;
		}

		$nValorTotal = $oLancamentoAuxiliar->getValorTotal();
	  $iAnoUsu     = db_getsession("DB_anousu");
	  /**
	   * Come�amos a efetuar os lan�amentos contabeis
	   */
	  $oDaoLancamento             = db_utils::getDao("conlancam");
	  $oDaoLancamento->c70_anousu = $iAnoUsu;
	  $oDaoLancamento->c70_data   = $dtDataUsu;
	  $oDaoLancamento->c70_valor  = $nValorTotal;
	  $oDaoLancamento->c70_codlan = null;
	  $oDaoLancamento->incluir(null);


	  if ($oDaoLancamento->erro_status == 0) {

	    $sErroMsg = "N�o foi Poss�vel incluir lancamento\nErro T�cnico:{$oDaoLancamento->erro_msg}";
	    throw new BusinessException($sErroMsg);
	  }

	  //Seta o c�digo do lan�amento
	  $this->iCodigoLancamento = $oDaoLancamento->c70_codlan;

	  /**
	   * Efetua o lan�amentos nas contas do Evento
	   */
	  foreach ($this->getEventoContabilLancamento() as $oLancamentoContabil) {

	    $oLancamentoContabil->executa($oDaoLancamento->c70_codlan,
	                                  $this->iCodigoDocumento,
	                                  $oLancamentoAuxiliar,
	                                  $dtDataUsu
	                                 );
	  }

	  /**
	   * Incluimos o Documento do Lan�amento
	   */
	  $oDaoConLancamDoc             = db_utils::getDao("conlancamdoc");
	  $oDaoConLancamDoc->c71_codlan = $oDaoLancamento->c70_codlan;
	  $oDaoConLancamDoc->c71_data   = $oDaoLancamento->c70_data;
	  $oDaoConLancamDoc->c71_coddoc = $this->iCodigoDocumento;

	  $oDaoConLancamDoc->incluir($oDaoLancamento->c70_codlan);
	  if ($oDaoConLancamDoc->erro_status == 0) {

	    $sErroMsg  = "N�o foi poss�vel salvar documento contabeis\n";
	    $sErroMsg .= "Erro T�cnico: {$oDaoConLancamDoc->erro_msg}";
	    throw new BusinessException($sErroMsg);
	  }

	  $oLancamentoAuxiliar->executaLancamentoAuxiliar($oDaoLancamento->c70_codlan, $oDaoLancamento->c70_data);
	  //return true; modificado para retornar o codigo do lan�amento
	  return $oDaoLancamento->c70_codlan;
	}

	/**
	 * retornar descricao do documento
	 * @return string
	 */
	public function getDescricaoDocumento() {
	    return $this->sDescricaoDocumento;
	}

	/**
	 * setar a descricao do documento
	 * @param string $sDescricaoDocumento
	 */
	public function setDescricaoDocumento($sDescricaoDocumento) {
		$this->sDescricaoDocumento = $sDescricaoDocumento;
	}

	/**
	 * Retorna o documento inverso ao instanciado, caso possua	 
	 *  
	 * @return EventoContabil
	 */
	public function getEventoInverso() {

		$sWhere  = "   (c115_conhistdocinclusao = {$this->iCodigoDocumento}";
		$sWhere .= " or c115_conhistdocestorno = {$this->iCodigoDocumento})";

		$oDaoVinculoeventoscontabeis = db_utils::getDao('vinculoeventoscontabeis');
		$sSqlBuscaVinculo            = $oDaoVinculoeventoscontabeis->sql_query(null, "*", null, $sWhere);
		$rsVinculoEventoContabil     = $oDaoVinculoeventoscontabeis->sql_record($sSqlBuscaVinculo);

		if ($oDaoVinculoeventoscontabeis->erro_status == "0") {			
			throw new BusinessException(_M(URL_MENSAGEM_EVENTOCONTABIL . "erro_vinculo_documento_inverso"));
		}

		$oStdVinculo = db_utils::fieldsMemory($rsVinculoEventoContabil, 0);

		if (empty($oStdVinculo->c115_conhistdocinclusao) || empty($oStdVinculo->c115_conhistdocestorno)) {
			return false;
		}

		if ($this->iCodigoDocumento == $oStdVinculo->c115_conhistdocinclusao) {			
			$iEventoRetorno = $oStdVinculo->c115_conhistdocestorno;			
		} else {
			$iEventoRetorno = $oStdVinculo->c115_conhistdocinclusao;			
		}

		return new EventoContabil($iEventoRetorno, $this->iAnoUso);
	}
	
	/**
	 * metodo que retornar� a instancia pelo codigo da transa��o
	 * @param integer $iCodigo transa��o
	 */
	public static function getInstanciaPorCodigo($iCodigo){
		
		$oDaoConTrans = db_utils::getDao("contrans");
		$sSqlContrans = $oDaoConTrans->sql_query_file($iCodigo);
		$rsContrans   = $oDaoConTrans->sql_record($sSqlContrans);
		if ($oDaoConTrans->numrows > 0) {
			
			$oDadosContrans = db_utils::fieldsMemory($rsContrans , 0);
			return new EventoContabil($oDadosContrans->c45_coddoc, $oDadosContrans->c45_anousu);
			
		}
		return false;
	}
	
	/**
	 * Retorna true caso documento for do tipo inclusao
	 * @return boolean
	 */
	public function isEventoInclusao() {
		return $this->lEventoInclusao;
	}

}