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

define('URL_MENSAGEM_PROCESSOPROTOCOLO', "patrimonial.protocolo.processoProtocolo.");

require_once modification("model/licitacao.model.php");
require_once modification("model/protocolo/ProcessoDocumento.model.php");

/**
 * Controle de processos de protocolo
 * @package protocolo
 */
class processoProtocolo {

  /**
   * Código do processo
   *
   * @var integer
   */
  private $iCodProcesso;

  /**
   * tipo do processo
   *
   * @var integer
   */
  private $iTipoProcesso;

  /**
   * CGM do processo
   *
   * @var integer
   */
  private $sCgm;

  /**
   * Requerente do processo
   *
   * @var string
   */
  private $sRequerente;

  /**
   * Observacao do processo
   *
   * @var string
   */
  private $sObservacao;

  /**
   * Despacho
   * @var string
   */
  private $sDespacho;

  /**
   * Processo interno
   * - define se o processo é interno
   * @var boolean
   */
  private $lInterno;

  /**
   * Processo Público
   * - define se o processo será público
   * @var boolean
   */
  private $lPublico;

  /**
   * data do processo
   * @var string (date)
   */
  private $dtProcesso;

  /**
   * Processos apensados (adicionados) ao processo
   * @var array processoProtocolo
   */
  protected $aProcessosApensados = array();

  /**
   * Número do Processo
   * - normalmente utilizado em conjunto com o iAnoProcesso
   * @var string
   */
  protected $sNumeroProcesso;

  /**
   * Ano do Processo
   * @var integer
   */
  protected $iAnoProcesso;

  /**
   * Documentos anexados ao processo
   * @var array ProcessoDocumento
   */
  protected $aDocumentosAnexados = array();

  /**
   * Departamento atual do processo
   * @var DBDepartamento
   */
  private $oDepartamentoAtual;

  /**
   * Método Construtor
   *
   * Retorna os dados de um processo caso seja passado o código do mesmo
   * @param integer $iCodProcesso
   */
  function __construct($iCodProcesso = null) {

    $this->iCodProcesso = $iCodProcesso;
    if (!empty($this->iCodProcesso)) {

      $oDaoProtProcesso = db_utils::getDao('protprocesso');
      $sSqlProtProcesso = $oDaoProtProcesso->sql_query_file($iCodProcesso);
      $rsProtProcesso   = $oDaoProtProcesso->sql_record($sSqlProtProcesso);

      if ($oDaoProtProcesso->numrows > 0) {

        $oDadosProtProcesso   = db_utils::fieldsMemory($rsProtProcesso,0);

        $this->setCodProcesso   ($iCodProcesso);
        $this->setTipoProcesso  ($oDadosProtProcesso->p58_codigo);
        $this->setCgm           ($oDadosProtProcesso->p58_numcgm);
        $this->setRequerente    ($oDadosProtProcesso->p58_requer);
        $this->setObservacao    ($oDadosProtProcesso->p58_obs);
        $this->setDespacho      ($oDadosProtProcesso->p58_despacho);
        $this->setInterno       ($oDadosProtProcesso->p58_interno);
        $this->setPublico       ($oDadosProtProcesso->p58_publico);
        $this->setDataProcesso  ($oDadosProtProcesso->p58_dtproc);
        $this->setNumeroProcesso($oDadosProtProcesso->p58_numero);
        $this->setAnoProcesso   ($oDadosProtProcesso->p58_ano);
        unset($oDadosProtProcesso);
      }
    }
  }

  /**
   * Retorna o Tipo do Processo
   * @return integer
   */
  public function getTipoProcesso() {
    return $this->iTipoProcesso;
  }

  /**
   * Retorna o código sequencial do processo
   * @return integer
   */
  public function getCodProcesso() {
    return $this->iCodProcesso;
  }

  /**
   * Retorna a informação do processo se é interno ou não
   * @return boolean
   */
  public function getInterno() {
    return $this->lInterno;
  }

  /**
   * Retorna se o processo é público
   * @return boolean
   */
  public function getPublico() {
    return $this->lPublico;
  }

  /**
   * Retorna o código sequencial do CGM
   * @return integer
   */
  public function getCgm() {
    return $this->sCgm;
  }

  /**
   * Retorna a descrição do despacho
   * @return string
   */
  public function getDespacho() {
    return $this->sDespacho;
  }

  /**
   * Retorna a observação do processo
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Retorna o código sequencial do requerente
   * @return integer
   */
  public function getRequerente() {
    return $this->sRequerente;
  }

  /**
   * Seta o tipo do processo
   * @param integer $iTipoProcesso
   */
  public function setTipoProcesso($iTipoProcesso) {
    $this->iTipoProcesso = $iTipoProcesso;
  }

  /**
   * Seta o código do processo
   * @param integer $iCodProcesso
   */
  private function setCodProcesso($iCodProcesso) {
    $this->iCodProcesso = $iCodProcesso;
  }

  /**
   * Seta se o processo será interno ou não
   * @param boolean
   */
  public function setInterno($lInterno) {
    $this->lInterno = $lInterno;
  }

  /**
   * Seta se o processo será público ou não
   * @param boolean
   */
  public function setPublico($lPublico) {
    $this->lPublico = $lPublico;
  }

  /**
   * Seta o código sequencial do CGM
   * @param integer
   */
  public function setCgm($sCgm) {
    $this->sCgm = $sCgm;
  }

  /**
   * Seta a descrição do despacho dado ao processo
   * @param string
   */
  public function setDespacho($sDespacho) {
    $this->sDespacho = $sDespacho;
  }

  /**
   * Seta a observação do processo
   * @param string
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Seta o requerente do processo
   * @param integer
   */
  public function setRequerente($sRequerente) {
    $this->sRequerente = $sRequerente;
  }

  /**
   * retorna a data do processo
   * @return string $dtProceso
   */
  public function getDataProcesso() {
  	return $this->dtProcesso;
  }

  /**
   * define a data do processo
   * @param string $dtProcesso
   */
  public function setDataProcesso($dtProcesso) {
  	$this->dtProcesso = $dtProcesso;
  }



  /**
   * Método Transferir
   *
   * Transfere o processo entre departamentos
   *
   * @param integer $iCodDeptoRec
   * @param integer $iIdUsuarioRec
   * @param integer $iCodDepto
   * @param integer $iIdUsuario
   * @return integer - código do processo
   */
  public function transferir($iCodDeptoRec='',$iIdUsuarioRec='',$iCodDepto='',$iIdUsuario=''){

  	$sMsgErro = 'Transferência de processo abortada';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

  	if ( trim($this->iCodProcesso) == '' ) {
  		throw new Exception("{$sMsgErro}, nenhum processo informado!");
  	}

    if ( trim($iCodDeptoRec) == '' ) {
      throw new Exception("{$sMsgErro}, departamento de recebimento não informado!");
    }

    if ( trim($iIdUsuarioRec) == '' ) {
      $iIdUsuarioRec = 0;
    }

    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto = db_getsession('DB_coddepto');
    }

  	$clProcTransfer     = db_utils::getDao('proctransfer');
  	$clProcTransferProc = db_utils::getDao('proctransferproc');

	  $clProcTransfer->p62_hora        = db_hora();
	  $clProcTransfer->p62_dttran      = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcTransfer->p62_id_usuario  = $iIdUsuario;
	  $clProcTransfer->p62_coddepto    = $iCodDepto;
	  $clProcTransfer->p62_id_usorec   = $iIdUsuarioRec;
	  $clProcTransfer->p62_coddeptorec = $iCodDeptoRec;
	  $clProcTransfer->incluir(null);

	  if ( $clProcTransfer->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransfer->erro_msg}");
	  }

    $clProcTransferProc->p63_codproc = $this->getCodProcesso();
    $clProcTransferProc->p63_codtran = $clProcTransfer->p62_codtran;
    $clProcTransferProc->incluir($clProcTransfer->p62_codtran,$this->getCodProcesso());

    if ( $clProcTransferProc->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransferProc->erro_msg}");
    }

    return $clProcTransfer->p62_codtran;

  }

  /**
   * Método Receber
   *
   * Recebe os o processo no departamento em que foi feita a solicitação de transferência
   *
   * @param integer $iCodTran
   * @param integer $iCodDepto
   * @param integer $iIdUsuario
   * @param string  $sDespacho
   * @param boolean $lAlteraProcesso
   * @return integer - código de andamento do processo
   */
  public function receber($iCodTran='',$iCodDepto='',$iIdUsuario='',$sDespacho='',$lAlteraProcesso=true){

    $sMsgErro = 'Recebimento de processo abortado';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    if ( trim($this->iCodProcesso) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }

    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto = db_getsession('DB_coddepto');
    }


    $clProtProcesso = db_utils::getDao('protprocesso');
    $clProcAndam    = db_utils::getDao('procandam');
    $clProcTransAnd = db_utils::getDao('proctransand');

    if ( trim($iCodTran) == '') {
	    $sWhereProcesso  = "     p58_codproc = {$this->getCodProcesso()} ";
		  $sWhereProcesso .= " and p61_codandam is null                    ";
		  $sWhereProcesso .= " and ((  p62_coddeptorec = {$iCodDepto}      ";
		  $sWhereProcesso .= "       and (    p62_id_usorec = 0            ";
		  $sWhereProcesso .= "            or p62_id_usorec = {$iIdUsuario} ";
		  $sWhereProcesso .= "          )                                  ";
		  $sWhereProcesso .= "     )                                       ";
		  $sWhereProcesso .= "  or p58_codandam = 0   )                    ";
	    $rsDadosProc = $clProtProcesso->sql_record($clProtProcesso->sql_query_despachos($this->getCodProcesso(),"p58_publico,p63_codtran"));

	    if ( $clProtProcesso->numrows > 0 ) {
	    	$oDadosProc = db_utils::fieldsMemory($rsDadosProc,0);
	    	$iCodTran   = $oDadosProc->p63_codtran;
	    } else {
	    	throw new Exception("{$sMsgErro}, Nenhum transferência encontrada!");
	    }

    } else {
    	$rsDadosProc = $clProtProcesso->sql_record($clProtProcesso->sql_query_file($this->getCodProcesso(),"p58_publico"));
		  $oDadosProc  = db_utils::fieldsMemory($rsDadosProc,0);
    }

	  $lPublico    = ($oDadosProc->p58_publico=='f'?"false":"true");

	  $clProcAndam->p61_publico    = $lPublico;
	  $clProcAndam->p61_codproc    = $this->getCodProcesso();
	  $clProcAndam->p61_dtandam    = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcAndam->p61_despacho   = $sDespacho;
	  $clProcAndam->p61_hora       = db_hora();
	  $clProcAndam->p61_id_usuario = $iIdUsuario;
	  $clProcAndam->p61_coddepto   = $iCodDepto;

	  $clProcAndam->incluir(null);

	  if ($clProcAndam->erro_status == 0) {
      throw new Exception("{$sMsgErro}\n{$clProcAndam->erro_msg}");
	  }

    $clProcTransAnd->p64_codtran  = $iCodTran;
	  $clProcTransAnd->p64_codandam = $clProcAndam->p61_codandam;
	  $clProcTransAnd->incluir();

	  if ($clProcTransAnd->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransAnd->erro_msg}");
	  }

	  if ( $lAlteraProcesso ) {
	    $clProtProcesso->p58_codproc  = $this->getCodProcesso();
	    $clProtProcesso->p58_codandam = $clProcAndam->p61_codandam;
	    $clProtProcesso->alterar($this->getCodProcesso());

	    if ( $clProtProcesso->erro_status == 0 ) {
	      throw new Exception("{$sMsgErro}\n{$clProtProcesso->erro_msg}");
	    }
	  }

	  return $clProcAndam->p61_codandam;

  }

  /**
   * Retorna a ultima transferência pendente para o processo
   *
   * @return integer|null
   */
  public function ultimaTransferenciaPendente() {

    $oDaoProtprocesso = new cl_protprocesso();

    $aWhere = array(
        "p58_codproc = {$this->getCodProcesso()}",
        "p61_codandam is null"
      );

    $sSqlTransferencia    = $oDaoProtprocesso->sql_query_despachos(null, "p63_codtran", null, implode(" and ", $aWhere));
    $rsDadosTransferencia = $oDaoProtprocesso->sql_record($sSqlTransferencia);

    if ( $oDaoProtprocesso->numrows > 0 ) {
      return db_utils::fieldsMemory($rsDadosTransferencia, 0)->p63_codtran;
    }

    return null;
  }

  /**
   * Método Arquivar
   *
   * Arquiva um processo
   *
   * @param string $sHistorico
   * @param integer $iIdUsuario
   * @param integer $iCodDepto
   */
  public function arquivar($sHistorico='',$iIdUsuario='',$iCodDepto='' ){

  	$sMsgErro = 'Arquivamento de processo abortado';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    if ( trim($this->iCodProcesso) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }

    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto  = db_getsession('DB_coddepto');
    }

    $clProcArquiv  = db_utils::getDao('procarquiv');
    $clArqAndam    = db_utils::getDao('arqandam');
    $clArqProc     = db_utils::getDao('arqproc');

	  $clProcArquiv->p67_id_usuario = $iIdUsuario;
	  $clProcArquiv->p67_coddepto   = $iCodDepto;
	  $clProcArquiv->p67_codproc    = $this->getCodProcesso();
	  $clProcArquiv->p67_dtarq      = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcArquiv->p67_historico  = $sHistorico;
	  $clProcArquiv->incluir(null);

	  if ( $clProcArquiv->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcArquiv->erro_msg}");
	  }

	  $clArqProc->p68_codarquiv = $clProcArquiv->p67_codarquiv;
	  $clArqProc->p68_codproc   = $this->getCodProcesso();
	  $clArqProc->incluir($clProcArquiv->p67_codarquiv,$this->getCodProcesso());

	  if ( $clArqProc->erro_status == 0 ) {
	    throw new Exception("{$sMsgErro}\n{$clArqProc->erro_msg}");
	  }

	  try {
      $iCodTran  = $this->transferir($iCodDepto,$iIdUsuario,$iCodDepto,$iIdUsuario);
      $iCodAndam = $this->receber($iCodTran,$iCodDepto,$iIdUsuario,$sHistorico,false);
	  } catch (Exception $eException) {
	  	throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }


	  $clArqAndam->p69_codarquiv = $clProcArquiv->p67_codarquiv;
	  $clArqAndam->p69_codandam  = $iCodAndam;
	  $clArqAndam->p69_arquivado = 'true';
	  $clArqAndam->incluir(null);

	  if ( $clArqAndam->erro_status == 0 ){
	    throw new Exception("{$sMsgErro}\n{$clArqAndam->erro_msg}");
	  }
  }

  /**
   * Método Salvar
   *
   * Salva os dados de um processo em protprocesso
   *
   */
  public function salvar() {

    $oDaoProtProcesso = db_utils::getDao('protprocesso');

    $oDaoProtProcesso->p58_codproc    = $this->getCodProcesso();
    $oDaoProtProcesso->p58_codigo     = $this->getTipoProcesso();
    $oDaoProtProcesso->p58_numcgm     = $this->getCgm();
    $oDaoProtProcesso->p58_requer     = $this->getRequerente();
    $oDaoProtProcesso->p58_codandam   = '';
    $oDaoProtProcesso->p58_obs        = $this->getObservacao();
    $oDaoProtProcesso->p58_despacho   = $this->getDespacho();
    $oDaoProtProcesso->p58_interno    = $this->getInterno();
    $oDaoProtProcesso->p58_publico    = $this->getPublico();
    $oDaoProtProcesso->p58_ano        = $this->getAnoProcesso();

    @$GLOBALS["HTTP_POST_VARS"]["p58_interno"] = $this->getInterno();
    @$GLOBALS["HTTP_POST_VARS"]["p58_publico"] = $this->getPublico();

    if ($this->getCodProcesso() == '') {

      $oDaoProtProcesso->p58_coddepto   = db_getsession('DB_coddepto');
      $oDaoProtProcesso->p58_dtproc     = date('Y-m-d',db_getsession('DB_datausu'));
      $oDaoProtProcesso->p58_id_usuario = db_getsession('DB_id_usuario');
      $oDaoProtProcesso->p58_hora       = db_hora();
      $oDaoProtProcesso->p58_instit     = db_getsession('DB_instit');
      $oDaoProtProcesso->p58_numero     = ProcessoProtocoloNumeracao::getProximoNumero();
      $oDaoProtProcesso->incluir(null);

      $this->setCodProcesso($oDaoProtProcesso->p58_codproc);
    } else {

      $oDaoProtProcesso->alterar($this->getCodProcesso());
    }

    if ($oDaoProtProcesso->erro_status == 0) {
      throw new Exception("Erro ao salvar dados do processo!\\n{$oDaoProtProcesso->erro_msg}");
    }
    return true;
  }

  /**
   * Método GetPosicaoAtualAndamentoPadrao
   *
   * Retorna o código de andamento do processo
   * @return int
   * @throws \Exception
   */
  public function getPosicaoAtualAndamentoPadrao(){

    $sMsgErro = 'Consulta da posição atual no andamento abortada';

    if ( trim($this->iCodProcesso) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }

    $clProcTransferProc = db_utils::getDao('proctransferproc');

    $sWhereAndam   = " p63_codproc = {$this->getCodProcesso()} ";

    $rsDadosAndam  = $clProcTransferProc->sql_record($clProcTransferProc->sql_query_andam(null,null,"*",null,$sWhereAndam));
    $iNroRegDepto  = $clProcTransferProc->numrows;

    return $iNroRegDepto;

  }

  /**
   * Método GetProximoDptoAndamentoPadrao
   *
   * Retorna o próximo departamento de andamento padrão de um processo
   * @return mixed - pode retornar um boolean em caso de erro ou integer em caso de sucesso
   * @throws \Exception
   */
  public function getProximoDeptoAndamentoPadrao(){

    $sMsgErro = 'Consulta de departamento abortada';

    if ( trim($this->iCodProcesso) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }

    $oDaoAndamentoPadrao = db_utils::getDao('andpadrao');

    $sWhereAndamentoPadrao  = "     p53_codigo = ".$this->getTipoProcesso();
    $sWhereAndamentoPadrao .= " and p53_ordem  = ".($this->getPosicaoAtualAndamentoPadrao()+1);
    $sSqlAndamentoPadrao    = $oDaoAndamentoPadrao->sql_query_file(null,null,"p53_coddepto",null,$sWhereAndamentoPadrao);
    $rsAndamentoPadrao      = $oDaoAndamentoPadrao->sql_record($sSqlAndamentoPadrao);

    if ($oDaoAndamentoPadrao->numrows > 0) {
      return db_utils::fieldsMemory($rsAndamentoPadrao,0)->p53_coddepto;
    } else {
      return false;
    }
  }


  /**
   * Método TransferirPorAndamentoPadrao
   *
   * @param integer $iIdUsuario
   * @param integer $iCodDepto
   * @return integer - código da transferencia
   */
  public function transferirPorAndamentoPadrao($iIdUsuario='',$iCodDepto=''){

    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto = db_getsession('DB_coddepto');
    }

    $iDeptoRecebimento = $this->getProximoDeptoAndamentoPadrao();
    $iCodTransferencia = $this->transferir($iDeptoRecebimento,'0',$iCodDepto,$iIdUsuario);
    return $iCodTransferencia;

  }

  /**
   * Retorna os processos que estão apensados ao processo
   * @return Array
   */
  public function getProcessosApensados() {

    if (count($this->aProcessosApensados) == 0) {

      $oDaoProtProcessoApensados = new cl_processosapensados;
      $sSqlProcessos             = $oDaoProtProcessoApensados->sql_query_file(null,
                                                                              "p30_procapensado",
                                                                              'p30_procapensado',
                                                                              "p30_procprincipal ={$this->iCodProcesso}");
      $rsProcessosApensados = $oDaoProtProcessoApensados->sql_record($sSqlProcessos);
      for ($iProcesso = 0; $iProcesso < $oDaoProtProcessoApensados->numrows; $iProcesso++) {

        $iCodigoProcesso             = db_utils::fieldsMemory($rsProcessosApensados, $iProcesso)->p30_procapensado;
        $oProcesso                   = new processoProtocolo($iCodigoProcesso);
        $this->aProcessosApensados[] = $oProcesso;
      }
    }
    return $this->aProcessosApensados;
  }

  /**
   * Retorna o número do processo
   * @return string
   */
  public function getNumeroProcesso() {
    return $this->sNumeroProcesso;
  }

  /**
   * Seta o número do processo
   * @param string $sNumeroProcessso
   */
  public function setNumeroProcesso($sNumeroProcessso) {
    $this->sNumeroProcesso = $sNumeroProcessso;
  }

  /**
   * Retorna o ano do processo
   * @return integer
   */
  public function getAnoProcesso() {
    return $this->iAnoProcesso;
  }

  /**
   * Seta o ano do processo
   * @param integer $iAnoProcesso
   */
  public function setAnoProcesso($iAnoProcesso) {
    $this->iAnoProcesso = $iAnoProcesso;
  }

  /**
   * Retorna os documentos anexados ao processo
   * @return ProcessoDocumento[]
   */
  public function getDocumentos() {

    if (count($this->aDocumentosAnexados) == 0) {
      $this->carregarDocumentosAnexados();
    }
    return $this->aDocumentosAnexados;
  }

  /**
   * Método responsável por carregar os documentos anexados a um processo.
   * - No método getDocumentos é validado se a propriedade aDocumentosAnexados está vazia,
   * caso esteja é chamado este método para carregar os documentos.
   * @return boolean true
   */
  private function carregarDocumentosAnexados() {

    $oDaoProcessoDocumento = db_utils::getDao('protprocessodocumento');
    $sSqlBuscaDocumentos   = $oDaoProcessoDocumento->sql_query_file(null,
                                                                    "p01_sequencial",
                                                                    "p01_sequencial",
                                                                    "p01_protprocesso = {$this->iCodProcesso}");
    $rsBuscaProcesso = $oDaoProcessoDocumento->sql_record($sSqlBuscaDocumentos);

    for ($iRowDocumento = 0; $iRowDocumento < $oDaoProcessoDocumento->numrows; $iRowDocumento++) {

      $iCodigoSequencial = db_utils::fieldsMemory($rsBuscaProcesso, $iRowDocumento)->p01_sequencial;
      $this->aDocumentosAnexados[] = new ProcessoDocumento($iCodigoSequencial);
    }
    return true;
  }


  /**
   * Retorna o departamento em que o processo se encontra no momento
   * @return DBDepartamento
   */
  public function getDepartamentoAtual() {

    $oDaoProtProcesso   = db_utils::getDao('protprocesso');
    $sCampo = "case when p61_coddepto is not null then p61_coddepto else p58_coddepto end as departamento";
    $sSqlBuscaAndamento = $oDaoProtProcesso->sql_query_andamento($this->iCodProcesso, $sCampo, "p61_codandam desc limit 1");
    $rsBuscaAndamento   = $oDaoProtProcesso->sql_record($sSqlBuscaAndamento);

    if ($oDaoProtProcesso->erro_status == "0") {

      $oStdErro = (object)array("sNumeroProcesso" => "{$this->getNumeroProcesso()}/{$this->getAnoProcesso()}");
      throw new BusinessException(_M(URL_MENSAGEM_PROCESSOPROTOCOLO."departamento_atual_nao_encontrado", $oStdErro));
    }
    return new DBDepartamento(db_utils::fieldsMemory($rsBuscaAndamento, 0)->departamento);
  }

  /**
   * Retorna a licitação vinculada ao processo, caso haja.
   * Poderá retornar false caso não encontre licitação vinculada, do contrário retorna um objeto
   * do tipo Licitacao
   * @return mixed
   */
  public function getLicitacao() {

    $oDaoProcesso       = db_utils::getDao('liclicitaproc');
    $sSqlBuscaLicitacao = $oDaoProcesso->sql_query_file(null, "l34_liclicita", null, "l34_protprocesso = {$this->iCodProcesso}");
    $rsBuscaLicitacao   = $oDaoProcesso->sql_record($sSqlBuscaLicitacao);
    if ($oDaoProcesso->erro_status == "0") {
      return false;
    }

    return new licitacao(db_utils::fieldsMemory($rsBuscaLicitacao, 0)->l34_liclicita);
  }


  /**
   * Retorna uma instancia do processo de acordo com o ano e número do processo informados no parâmetro
   * @param string $sNumeroProcesso
   * @param integer $iAno
   * @return processoProtocolo || false
   */
  public static function getInstanciaPorNumeroEAno($sNumeroProcesso, $iAno, Instituicao $oInstituicao) {

    $oDaoProtProcesso  = db_utils::getDao("protprocesso");
    $sWhereProcesso    = "     p58_numero = '{$sNumeroProcesso}' ";
    $sWhereProcesso   .= " and p58_ano    = {$iAno} ";
    $sWhereProcesso   .= " and p58_instit = {$oInstituicao->getSequencial()} ";
    $sSqlBuscaProcesso = $oDaoProtProcesso->sql_query_file(null, "p58_codproc", null, $sWhereProcesso);
    $rsBuscaProcesso   = $oDaoProtProcesso->sql_record($sSqlBuscaProcesso);

    if ($oDaoProtProcesso->erro_status == "0") {
      return false;
    }
    return new processoProtocolo(db_utils::fieldsMemory($rsBuscaProcesso, 0)->p58_codproc);
  }

  /**
   * @param                 $despacho
   * @param                 $iIdUsuario
   * @param \DBDepartamento $departamentos
   */
  public function adicionarDespachoDepartamento($despacho, $iIdUsuario, DBDepartamento $departamentos) {
    
    $clProcAndam = new cl_procandam();
    $clProcAndam->p61_publico    = $this->getPublico()?'true':'false';
    $clProcAndam->p61_codproc    = $this->getCodProcesso();
    $clProcAndam->p61_dtandam    = date('Y-m-d',db_getsession('DB_datausu'));
    $clProcAndam->p61_despacho   = $despacho;
    $clProcAndam->p61_hora       = db_hora();
    $clProcAndam->p61_id_usuario = $iIdUsuario;
    $clProcAndam->p61_coddepto   = $departamentos->getCodigo();
    $clProcAndam->incluir(null);
    
  }
}