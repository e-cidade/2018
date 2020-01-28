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


/**
 * @author Bruno
 *
 */
class InscricaoPassivoOrcamento {

  /**
   * Código da Inscrição
   *
   * @var integer
   */
  private $iSequencial;

  /**
   * Conjunto de itens da Inscricao
   *
   * @var array
   */
  private $aItens;

  /**
   * Agregação com um Favorecido (CGM)
   *
   * @var Favorecido
   */
  private $oFavorecido;

  /**
   * Usuário do portal
   *
   * @var object Usuario
   */
  private $oUsuarioInscricao;

  /**
   * Usuário do portal
   *
   * @var object Usuario
   */
  private $oUsuarioInscricaoAnulacao;

  /**
   * Data que foi criada a inscrição
   * @var date
   */
  private $dtDataInscricao;
  /**
   * Data da Anulação
   * @var date
   */
  private $dtDataAnulacao;

  /**
   * Descrição da Observação
   *
   * @var string
   */
  private $sObservacaoAnulacao;

  /**
   * Instituição
   *
   * @var object Instituicao
   */
  private $oInstituicao;

  /**
   * Desdobramento do Elemento (codele)
   * @var integer
   */
  private $iCodigoElemento;

  /**
   * Ano do desdobramento do Elemento
   * @var integer
   */
  private $iAnoDesdobramento;

  /**
   * Código do Histórico
   * @var integer
   **/
  private $iCodigoHistorico;

  /**
   * Observação do Histórico
   */
  private $sObservacaoHistorico;

  /**
   * descrição do elemento
   * @var string
   */
  private $sDescricaoElemento;

  /**
   * estrutural do elemento
   * @var integer
   */
  private $iElemento;

  /**
   * Valor total da inscricao
   * @var float
   */
  private $nValorTotalInscricao;

  /**
   * Carrega as informações de uma inscrição passiva de orçamento
   * @param  integer $iSequencial
   * @return boolean
   */
  function __construct($iSequencial = null) {

  	$this->iSequencial = $iSequencial;
    if($iSequencial != null) {

      $oDAOInscricaoPassivoOrcamento = db_utils::getDao("inscricaopassivo");
      $sSqlInscricaoPassivoOrcamento = $oDAOInscricaoPassivoOrcamento->sql_query_informacoes_inscricao($iSequencial);
      $rsInscricaoPassivoOrcamento   =  $oDAOInscricaoPassivoOrcamento->sql_record($sSqlInscricaoPassivoOrcamento);
      if ($oDAOInscricaoPassivoOrcamento->numrows > 0) {

        $oDAOInscricaoPassivoOrcamento = db_utils::fieldsMemory($rsInscricaoPassivoOrcamento, 0);
        $this->iSequencial             = $oDAOInscricaoPassivoOrcamento->c36_sequencial;
        $this->oFavorecido             = CgmFactory::getInstanceByCgm($oDAOInscricaoPassivoOrcamento->c36_cgm);
        $this->oInstituicao            = new Instituicao($oDAOInscricaoPassivoOrcamento->c36_instit);
        $this->oUsuarioInscricao       = new UsuarioSistema($oDAOInscricaoPassivoOrcamento->c36_db_usuarios);
        $this->dtDataInscricao         = $oDAOInscricaoPassivoOrcamento->c36_data;
        $this->iCodigoElemento         = $oDAOInscricaoPassivoOrcamento->c36_codele;
        $this->iAnoElemento            = $oDAOInscricaoPassivoOrcamento->c36_anousu;
        $this->iCodigoHistorico        = $oDAOInscricaoPassivoOrcamento->c36_conhist;
        $this->sObservacaoHistorico    = $oDAOInscricaoPassivoOrcamento->c36_observacaoconhist;
        $this->sObservacaoAnulacao     = $oDAOInscricaoPassivoOrcamento->c39_observacao;
        $this->dtDataAnulacao          = $oDAOInscricaoPassivoOrcamento->c39_data;
        $this->sDescricaoElemento      = $oDAOInscricaoPassivoOrcamento->o56_descr;
        $this->iElemento               = substr($oDAOInscricaoPassivoOrcamento->o56_elemento,0,7);

        if (!empty($oDAOInscricaoPassivoOrcamento->c39_data)) {
          $this->oUsuarioInscricaoAnulacao = new UsuarioSistema($oDAOInscricaoPassivoOrcamento->c39_db_usuarios);
        }
      }
    }
    return true;
  }

  /**
   * Retorna array com colecao de Itens gravados na tabela inscricaopassivoitens
   * Cada Item e ligado ao Sequencial de uma Inscricao
   * @return array objects
   **/
  public function getItens() {

  	if (count($this->aItens) == 0) {

  		$oDaoInscricaoPassivoOrcamentoItem = db_utils::getDao("inscricaopassivoitem");
	    $sWhereItens                       = "	c38_inscricaopassivo = {$this->iSequencial}";
	    $sSqlInscricaoPassivoOrcamentoItem = $oDaoInscricaoPassivoOrcamentoItem->sql_query_file(null, "*", null, $sWhereItens);
	    $rsInscricaoPassivoOrcamentoItem   = $oDaoInscricaoPassivoOrcamentoItem->sql_record($sSqlInscricaoPassivoOrcamentoItem);
	    $iTotalItemPassivoOrcamento        = $oDaoInscricaoPassivoOrcamentoItem->numrows;
	    if ($iTotalItemPassivoOrcamento > 0) {

	      /*
	       * Percorre Resultset, criando um array de objetos com todas ocorrencias encontradas
	       */
  	    $nValorTotalInscricao = 0;
	      for ($iRowItem = 0; $iRowItem < $iTotalItemPassivoOrcamento; $iRowItem++) {

	        $oDaoItem              = db_utils::fieldsmemory($rsInscricaoPassivoOrcamentoItem, $iRowItem);
	        $iCodigoItem           = $oDaoItem->c38_sequencial;
	        $oItem                 = new InscricaoPassivoOrcamentoItem($iCodigoItem);
	        $nValorTotalInscricao += $oItem->getValorTotal();
	        $this->aItens[]        = $oItem;
	      }
	      $this->setValorTotalInscricao($nValorTotalInscricao);
	    }
  	}
  	return $this->aItens;
  }

  /**
   * Método que salva a inscricao
   * Salva cada um dos itens que a compoem
   **/
  public function salvar() {

  	$oDaoInscricaoPassivoOrcamento = db_utils::getDao("inscricaopassivo");
  	$oDaoInscricaoPassivoOrcamento->c36_sequencial        = $this->getSequencial();
  	$oDaoInscricaoPassivoOrcamento->c36_cgm               = $this->getFavorecido()->getCodigo();
  	$oDaoInscricaoPassivoOrcamento->c36_db_usuarios       = $this->getUsuarioInscricao()->getIdUsuario();
  	$oDaoInscricaoPassivoOrcamento->c36_instit            = $this->getInstituicao()->getSequencial();
  	$oDaoInscricaoPassivoOrcamento->c36_data              = $this->getDataInscricao();
  	$oDaoInscricaoPassivoOrcamento->c36_codele            = $this->getCodigoElemento();
  	$oDaoInscricaoPassivoOrcamento->c36_anousu            = $this->getAnoElemento();
  	$oDaoInscricaoPassivoOrcamento->c36_conhist           = $this->getCodigoHistorico();
  	$oDaoInscricaoPassivoOrcamento->c36_observacaoconhist = $this->getObservacaoHistorico();

  	if ($this->iSequencial == "") {

  		$oDaoInscricaoPassivoOrcamento->incluir(null);
  		$this->setSequencial($oDaoInscricaoPassivoOrcamento->c36_sequencial);
  	} else {
  		$oDaoInscricaoPassivoOrcamento->alterar($this->iSequencial);
  	}

  	if ($oDaoInscricaoPassivoOrcamento->erro_status == 0) {

  		$sErro = "Não foi possivel salvar os dados da inscrição. \n\nErro técnico : {$oDaoInscricaoPassivoOrcamento->erro_msg} ";
  		throw new BusinessException($sErro);
  	}

  	/**
  	 * salvando coleçao de objetos Item presentes na Inscricao
  	 **/
  	foreach ($this->aItens as $oItem) {

  		$oItem->setInscricaoPassivo($oDaoInscricaoPassivoOrcamento->c36_sequencial);
  		$oItem->salvar();
  	}

  	return true;
  }

  /**
   * Método que anula uma inscrição passiva
   * @throws BusinessException
   * @return boolean true
   */
  public function anular() {

  	$oDaoInscricaoAnulada = db_utils::getDao('inscricaopassivaanulada');
  	$oDaoInscricaoAnulada->c39_sequencial       = null;
  	$oDaoInscricaoAnulada->c39_inscricaopassivo = $this->getSequencial();
  	$oDaoInscricaoAnulada->c39_db_usuarios      = $this->getUsuarioInscricaoAnulacao()->getIdUsuario();
  	$oDaoInscricaoAnulada->c39_data             = $this->getDataAnulacao();
  	$oDaoInscricaoAnulada->c39_observacao       = $this->getObservacaoAnulacao();
  	$oDaoInscricaoAnulada->incluir(null);

  	if ($oDaoInscricaoAnulada->erro_status == 0) {

  		$sMensagemErro  = "Não foi possível anular a inscrição.\n\n";
  		$sMensagemErro .= "Erro Técnico: {$oDaoInscricaoAnulada->erro_msg}";
  		throw new BusinessException($sMensagemErro);
  	}
  	return true;
  }

  /**
   * Verifica se a inscricao passiva já passui empenho
   * @return boolean
   */
  public function hasEmpenho() {

    $oDaoInscricaoEmpenhada = db_utils::getDao('empautorizainscricaopassivo');
    $sSqlBuscaAutorizacao   = $oDaoInscricaoEmpenhada->sql_query_file(null, "*", null, "e16_inscricaopassivo = {$this->getSequencial()}");
    $rsBuscaAutorizacao     = $oDaoInscricaoEmpenhada->sql_record($sSqlBuscaAutorizacao);

    if ($oDaoInscricaoEmpenhada->numrows > 0) {
      return true;
    }
    return false;
  }


  /**
   * Adiciona InscricaoPassivoOrcamentoItem no array
   **/
  public function adicionarItem(InscricaoPassivoOrcamentoItem $oItem) {
    $this->aItens[] = $oItem;
  }

  /**
   * Retorna Sequencial da Inscricao
   * @return integer
   */
  public function getSequencial() {
  	return $this->iSequencial;
  }

  /**
   * Retorna o Favorecido
   * @return object Favorecido
   */
  public function getFavorecido() {
  	return $this->oFavorecido;
  }

  /**
   * Retorna o Usuario da Inscricao
   * @return object Usuario
   */
  public function getUsuarioInscricao() {
  	return $this->oUsuarioInscricao;
  }

  /**
   * Retorna o Usuario da Inscricao Anulacao
   * @return object Usuario
   */
  public function getUsuarioInscricaoAnulacao() {
  	return $this->oUsuarioInscricaoAnulacao;
  }

  /**
   * Retorna a data da Anulacao
   * @return date
   */
  public function getDataAnulacao() {
  	return $this->dtDataAnulacao;
  }

  /**
   * Retorna a Observacao
   * @return string
   */
  public function getObservacaoAnulacao() {
  	return $this->sObservacaoAnulacao;
  }

  /**
   * Retorna objeto Instituicao
   * @return Instituicao $oInstituica
   */
  public function getInstituicao() {
  	return $this->oInstituicao;
  }

  /**
   * Retorna a data de criação da inscrição
   */
  public function getDataInscricao() {
    return $this->dtDataInscricao;
  }

  /**
   * Retorna o código do elemento
   * @return integer
   */
  public function getCodigoElemento() {
    return $this->iCodigoElemento;
  }

  /**
   * Retorna o ano do desdobramento
   */
  public function getAnoElemento() {
    return $this->iAnoElemento;
  }

  /**
   * Seta Sequencial da Inscricao
   * @param integer
   */
  public function setSequencial($iSequencial) {
  	$this->iSequencial = $iSequencial;
  }

  /**
   * Seta o Favorecido
   * @param object Favorecido
   */
  public function setFavorecido($oFavorecido) {
  	$this->oFavorecido = $oFavorecido;
  }

  /**
   * Seta descrição do elemento
   * @param string
   */
  public function setDescricaoElemento($sDescricaoElemento) {
    $this->sDescricaoElemento = $sDescricaoElemento;
  }

  /**
   *  Retorna a descrição do elemento
   *  @return string
   **/
  public function getDescricaoElemento() {
    return $this->sDescricaoElemento;
  }

  /**
   * Seta o Usuario da Inscricao
   * @param UsuarioSistema Usuario
   */
  public function setUsuarioInscricao(UsuarioSistema $oUsuarioInscricao) {
  	$this->oUsuarioInscricao = $oUsuarioInscricao;
  }

  /**
   * Seta o Usuario da Inscricao Anulacao
   * @param UsuarioSistema Usuario
   */
  public function setUsuarioInscricaoAnulacao(UsuarioSistema $oUsuarioInscricaoAnulacao) {
  	$this->oUsuarioInscricaoAnulacao = $oUsuarioInscricaoAnulacao;
  }

  /**
   * Seta a data da Anulacao
   * @param date
   */
  public function setDataAnulacao($dtDataAnulacao) {
  	$this->dtDataAnulacao = $dtDataAnulacao;
  }

  /**
   * Seta a Observacao
   * @param string
   */
  public function setObservacaoAnulacao($sObservacaoAnulacao) {
  	$this->sObservacaoAnulacao = $sObservacaoAnulacao;
  }

  /**
   * Seta objeto Instituicao
   * @param object Instituicao
   */
  public function setInstituicao($oInstituicao) {
  	$this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta a data de criação da inscrição
   */
  public function setDataInscricao($dtDataInscricao) {
    $this->dtDataInscricao = $dtDataInscricao;
  }

  /**
   * Seta  o desdobramento
   * @param integer $iDesdobramento
   */
  public function setCodigoElemento($iCodigoElemento) {
    $this->iCodigoElemento = $iCodigoElemento;
  }

  /**
   * Seta o ano do desdobramento
   * @param integer $iAnoDesdobramento
   */
  public function setAnoElemento($iAnoElemento) {
    $this->iAnoElemento = $iAnoElemento;
  }

  /**
   *  Retorna o Histórico
   *  @return integer
   **/
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   *  Seta o Histórico
   *  @param integer
   **/
  public function setCodigoHistorico($iCodigoHistorico) {
    $this->iCodigoHistorico = $iCodigoHistorico;
  }

  /**
   *  Retorna a Observacao do Histórico
   *  @return string
   **/
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }

  /**
   *  Seta a Observacao do Histórico
   *  @param string
   **/
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }

  /**
   * Seta desdobramento do elemento
   * @param integer
   */
  public function setDesdobramentoElemento($iElemento) {
    $this->iElemento = $iElemento;
  }

  /**
   *  Retorna o desdobramento do elemento
   *  @return integer
   **/
  public function getDesdobramentoElemento() {
    return $this->iElemento;
  }

  /**
   * Seta o valor total da inscrição.
   * @param float $nValorTotalInscricao
   */
  public function setValorTotalInscricao($nValorTotalInscricao) {
    $this->nValorTotalInscricao = $nValorTotalInscricao;
  }

  /**
   * Este método só vai retornar valor após o usuário executar o método getItens()
   * @return float $nValorTotalInscricao
   */
  public function getValorTotalInscricao() {

    $this->getItens();
  	return $this->nValorTotalInscricao;
  }
}
?>