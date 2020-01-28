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
 * Model dos assentamentos de Servidor
 *
 * @package pessoal
 * @author Alberto <alberto@dbseller.com.br>
 */
class Assentamento {

  const NATUREZA_PADRAO               = 1;
  const NATUREZA_SUBSTITUICAO         = 2;
  const NATUREZA_RRA                  = 3;
  const NATUREZA_PONTO_ELETRONICO     = 4;
  const NATUREZA_JUSTIFICATIVA        = 5;
  const NATUREZA_DIA_EXTRA            = 6;
  const NATUREZA_AUTORIZA_HORA_EXTRA  = 7;
  const NATUREZA_HE_MANUAL            = 8;

  /**
   * Código assentamento
   * @var integer
   */
  private $iCodigo;

  /**
   * Matrícula servidor
   * @var integer
   */
  private $iMatricula;

  /**
   * Código do tipo de assentamento
   * @var integer
   */
  private $iTipoAssentamento;

  /**
   * @var TipoAssentamento
   */
  private $oTipoAssentamento = null;

  /**
   * Instância do objeto DBDate com a data da concessão do afastamento ou assentamento
   * @var DBDate
   */
  private $oDataConcessao;

  /**
   * Histórico do assentamento
   * @var string
   */
  private $sHistorico;

  /**
   * Código da portaria emitida
   * @var string
   */
  private $sCodigoPortaria;

  /**
   * Descrição do ato oficial
   * @var string
   */
  private $sDescricaoAto;

  /**
   * Quantidade de dias concedidos
   * @var integer
   */
  private $iDias;

  /**
   * Percentual concedido
   * @var number
   */
  private $nPercentual;

  /**
   * Instância do objeto DBDate com a data do termino do afastamento/assentamento
   * @var DBDate
   */
  private $oDataTermino;

  /**
   * Segundo Histórico do assentamento
   * @var string
   */
  private $sSegundoHistorico;

  /**
   * Login do usuário que registrou o asssentamento/afastamento
   * @var string
   */
  private $sLoginUsuario;

  /**
   * Instância do objeto DBDate com a data de lançamento do afastamento ou assentamento
   * @var DBDate
   */
  private $oDataLancamento;

  /**
   * Se registro foi convertido
   * @var boolean
   */
  private $lConvertido;

  /**
   * Ano da portaria do registro
   * @var integer
   */
  private $iAnoPortaria;

  /**
   * Obejeto contendo o valor e a quantidade para ser lancado na rubrica para pagamento
   * @var stdClass
   */
  private $oValorQuantidade;

  private $lControleDiasNoMes = false;

  /**
   * Guarda mensagem de erro na execução de métodos
   * @var String
   */
  private $sErro = null;

  /**
   * Atributo com o objeto de servidor
   * @var Servidor
   */
  private $oServidor = null;

  protected $aValoresAtributosDinamicos = array();

  /**
   * Atributo que guarda a quantidade de horas do assentamento
   * @var String
   */
  private $sHora = null;

  /**
   * Construtor da classe
   *
   * @param Integer $iCodigo
   * @throws \BusinessException
   */
  public function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return;
    }

    $oDaoAssentamento   = db_utils::getDao('assenta');
    $sQueryControleDias = " exists (select true from tipoassecontrolediasmes where h16_assent = rh170_tipoasse) as controle_dias";

    $sSqlAssentamento = $oDaoAssentamento->sql_query_file($iCodigo, "*, {$sQueryControleDias}");
    $rsAssentamento   = $oDaoAssentamento->sql_record($sSqlAssentamento);

    if (!$rsAssentamento || pg_num_rows($rsAssentamento) == 0) {
      throw new BusinessException('Nenhum assentamento encontrado.');
    }

    $oAssentamento = db_utils::fieldsMemory($rsAssentamento, 0);

    $this->setCodigo          ($oAssentamento->h16_codigo);
    $this->setMatricula       ($oAssentamento->h16_regist);
    $this->setTipoAssentamento($oAssentamento->h16_assent);
    $this->setHistorico       ($oAssentamento->h16_histor);
    $this->setCodigoPortaria  ($oAssentamento->h16_nrport);
    $this->setDescricaoAto    ($oAssentamento->h16_atofic);
    $this->setDias            ($oAssentamento->h16_quant);
    $this->setPercentual      ($oAssentamento->h16_perc);
    $this->setSegundoHistorico($oAssentamento->h16_hist2);
    $this->setLoginUsuario    ($oAssentamento->h16_login);
    $this->setDataLancamento  ($oAssentamento->h16_dtlanc);
    $this->setConvertido      ($oAssentamento->h16_conver);
    $this->setAnoPortaria     ($oAssentamento->h16_anoato);
    $this->setHora            ($oAssentamento->h16_hora);

    $this->lControleDiasNoMes = $oAssentamento->controle_dias == 't';

    if( !empty($oAssentamento->h16_dtconc) ){
      $oDataConcessao = new DBDate($oAssentamento->h16_dtconc);
      $this->setDataConcessao ($oDataConcessao);
    }

    if( !empty($oAssentamento->h16_dtterm) ){
      $oDataTermino = new DBDate($oAssentamento->h16_dtterm);
      $this->setDataTermino   ($oDataTermino);
    }

  }

  /**
   * Retorna o código do assentamento
   * @return number
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o código do assentamento
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a matrícula do servidor do assentamento
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define a matrícula do servidor do assentamento
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Define o tipo de assentamento
   * @return integer
   */
  public function getTipoAssentamento() {
    return $this->iTipoAssentamento;
  }

  /**
   * Define o tipo de assentamneto
   * @param integer $iTipoAssentamento
   */
  public function setTipoAssentamento($iTipoAssentamento) {
    $this->iTipoAssentamento = $iTipoAssentamento;
    $this->oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($iTipoAssentamento);
  }

  /**
   * @return \TipoAssentamento
   */
  public function getInstanciaTipoAssentamento() {
	  
    if (empty($this->oTipoAssentamento)) {
      $this->oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($this->iTipoAssentamento);
    }
    return $this->oTipoAssentamento;
  }

  /**
   * Retorna a data de concessão do afastamento
   * @return DBDate
   */
  public function getDataConcessao() {
    return $this->oDataConcessao;
  }

  /**
   * Define a data de concessão do afastamento
   * @param DBDate $oDataConcessao
   */
  public function setDataConcessao(DBDate $oDataConcessao) {
    $this->oDataConcessao = $oDataConcessao;
  }

  /**
   * Define o histórico do afastamento
   * @return string
   */
  public function getHistorico() {
    return $this->sHistorico;
  }

  /**
   * Define o histório do afastamento
   * @param string $sHistorico
   */
  public function setHistorico($sHistorico) {
    $this->sHistorico = $sHistorico;
  }

  /**
   * Retorna o código da portaria
   * @return string
   */
  public function getCodigoPortaria() {
    return $this->sCodigoPortaria;
  }

  /**
   * Define o código da portaria
   * @param integer $sCodigoPortaria
   */
  public function setCodigoPortaria($sCodigoPortaria) {
    $this->sCodigoPortaria = $sCodigoPortaria;
  }

  /**
   * Retorna a descrição do ato
   * @return string
   */
  public function getDescricaoAto() {
    return $this->sDescricaoAto;
  }

  /**
   * Define a descrição do ato
   * @param string $sDescricaoAto
   */
  public function setDescricaoAto($sDescricaoAto) {
    $this->sDescricaoAto = $sDescricaoAto;
  }

  /**
   * Retorna o número de dias do afastametno
   * @return number
   */
  public function getDias() {
    return $this->iDias;
  }

  /**
   * Define o número de dias do afastamento
   * @param integer $iDias
   */
  public function setDias($iDias) {
    $this->iDias = $iDias;
  }

  /**
   * Retorna o percentual do afastamento
   * @return number
   */
  public function getPercentual() {
    return $this->nPercentual;
  }

  /**
   * Define o percentual do afastamento
   * @param number $nPercentual
   */
  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }

  /**
   * Retorna uma instância do objeto DBDate com a data do afastamento
   * @return DBDate
   */
  public function getDataTermino() {
    return $this->oDataTermino;
  }

  /**
   * Define uma instância do objeto DBDate com a  data de termino do afastamento/assentamento
   * @paramDBDate $oDataTermino
   */
  public function setDataTermino($oDataTermino) {
    $this->oDataTermino = $oDataTermino;
  }

  /**
   * Retorna o segundo histórico do afastamento
   * @return string
   */
  public function getSegundoHistorico() {
    return $this->sSegundoHistorico;
  }

  /**
   * Define o segundo histórico do afastamento
   * @param string $sSegundoHistorico
   */
  public function setSegundoHistorico($sSegundoHistorico) {
    $this->sSegundoHistorico = $sSegundoHistorico;
  }

  /**
   * Retorna o login do usuário
   * @return string
   */
  public function getLoginUsuario() {
    return $this->sLoginUsuario;
  }

  /**
   * Define o login do usuário
   * @param string $sLoginUsuario
   */
  public function setLoginUsuario($sLoginUsuario) {
    $this->sLoginUsuario = $sLoginUsuario;
  }

  /**
   * Retorna uma instância do objeto DBDate com a data de lançamento do afastamento / assentamento
   * @return DBDate
   */
  public function getDataLancamento() {
    return $this->oDataLancamento;
  }

  /**
   * Define uma instância do objeto DBDate com a data de lançamento do afastamento / assentamento
   * @param string|DBDate $oDataLancamento
   */
  public function setDataLancamento($oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;
  }

  /**
   * Retorna se o afastamento foi convertido
   * @return boolean
   */
  public function isConvertido() {
    return $this->lConvertido;
  }

  /**
   * Define se o registro foi convertido
   * @param boolean $lConvertido
   */
  public function setConvertido($lConvertido) {
    $this->lConvertido = $lConvertido;
  }

  /**
   * Retorna o ano da portaria
   * @return number
   */
  public function getAnoPortaria() {
    return $this->iAnoPortaria;
  }

  /**
   * Define o ano da portaria
   * @param integer $iAnoPortaria
   */
  public function setAnoPortaria($iAnoPortaria) {
    $this->iAnoPortaria = $iAnoPortaria;
  }

  /**
   * Retorna a mensagem de erro
   * @return String
   */
  public function getErro() {
    return $this->sErro;
  }

  /**
   * Define o Servidor do assentamento
   * @param Servidor
   */
  public function setServidor ($oServidor) {
    $this->oServidor = $oServidor;
    return $this;
  }

  /**
   * Retorna o Servidor do assentamento
   * @return Servidor
   */
  public function getServidor () {

    if(empty($this->oServidor)) {
      $this->oServidor = ServidorRepository::getInstanciaByCodigo($this->iMatricula);
    }

    return $this->oServidor;
  }


  /**
   * Define a quantidade de horas do assentamento
   * @param String
   */
  public function setHora ($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna a quantidade de horas do assentamento
   * @return String
   */
  public function getHora () {
    return $this->sHora;
  }

  /**
   * Persist na base um assentamento
   * @return mixed true | String mensagem de erro
   */
  public function persist() {

    $classenta       = new cl_assenta();

    $classenta->h16_regist  = $this->getMatricula();
    $classenta->h16_assent  = $this->getTipoAssentamento();
    $classenta->h16_dtconc  = ($this->getDataConcessao() instanceof DBDate ? $this->getDataConcessao()->getDate() : $this->getDataConcessao());
    $classenta->h16_histor  = $this->getHistorico();
    $classenta->h16_nrport  = $this->getCodigoPortaria();
    $classenta->h16_atofic  = $this->getDescricaoAto();
    $classenta->h16_quant   = $this->getDias();
    $classenta->h16_perc    = ($this->getPercentual()) ? $this->getPercentual() : '0';
    $classenta->h16_dtterm  = ($this->getDataTermino() instanceof DBDate ? $this->getDataTermino()->getDate() : $this->getDataTermino());
    $classenta->h16_hist2   = $this->getSegundoHistorico();
    $classenta->h16_login   = ($this->getLoginUsuario()) ? $this->getLoginUsuario() : '1';
    $classenta->h16_dtlanc  = ($this->getDataLancamento() instanceof DBDate ? $this->getDataLancamento()->getDate() : $this->getDataLancamento());
    $classenta->h16_conver  = ((bool)(int)$this->isConvertido()) == false ? 'false' : 'true';
    $classenta->h16_anoato  = $this->getAnoPortaria();
    $classenta->h16_hora    = $this->getHora();

    if(empty($this->iCodigo)) {

      $classenta->incluir(null);

      if ($classenta->erro_status == "0") {
        return $classenta->erro_msg;
      }

      $this->setCodigo($classenta->h16_codigo);
    } else {

      $classenta->h16_codigo = $this->getCodigo();
      $classenta->alterar($this->getCodigo());

      if ($classenta->erro_status == "0") {
        return $classenta->erro_msg;
      }
    }

    return $this;
  }

  /**
   * Transforma o objeto em um formato JSON
   * @return string
   */
  public function toJSON() {

    $oServidor             = ServidorRepository::getInstanciaByCodigo(
      $this->getMatricula(),
      DBPessoal::getAnoFolha(),
      DBPessoal::getMesFolha()
    );

    $aRetorno = array();
    $aRetorno["codigo"]            = $this->getCodigo();
    $aRetorno["tipo"]              = $this->getTipoAssentamento();
    $aRetorno["natureza"]          = "padrao";
    $aRetorno["cgm_servidor"]      = $oServidor->getCgm()->getCodigo();
    $aRetorno["nome_servidor"]     = utf8_encode($oServidor->getCgm()->getNome());

    $aRetorno["matricula"]         = $this->getMatricula();
    $aRetorno["dataConcessao"]     = ($this->getDataConcessao() instanceof DBDate ? $this->getDataConcessao()->getDate(DBDate::DATA_PTBR) : $this->getDataConcessao());
    $aRetorno["historico"]         = $this->getHistorico();
    $aRetorno["codigoPortaria"]    = $this->getCodigoPortaria();
    $aRetorno["descricaoAto"]      = $this->getDescricaoAto();
    $aRetorno["dias"]              = $this->getDias();
    $aRetorno["percentual"]        = $this->getPercentual();
    $aRetorno["dataTermino"]       = ($this->getDataTermino() instanceof DBDate ? $this->getDataTermino()->getDate(DBDate::DATA_PTBR) : $this->getDataTermino());
    $aRetorno["segundoHistorico"]  = $this->getSegundoHistorico();
    $aRetorno["loginUsuario"]      = $this->getLoginUsuario();
    $aRetorno["dataLancamento"]    = ($this->getDataLancamento() instanceof DBDate ? $this->getDataLancamento()->getDate(DBDate::DATA_PTBR) : $this->getDataLancamento());
    $aRetorno["convertido"]        = (int)$this->isConvertido();
    $aRetorno["anoPortaria"]       = $this->getAnoPortaria();
    $aRetorno["hora"]              = $this->getHora();

    return json_encode((object)$aRetorno);
  }

  /**
   * Seta o valor/quantidae para a formula executada
   *
   * @param  String $sFormula
   *
   * @return  void
   */
  private function setValorQuantidadePorFormula($sFormula = null) {

    $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($this->getTipoAssentamento());
    $oDbformula        = new DBFormulaAssentamento($this);

    if(empty($sFormula)) {
      $sFormula        = $oTipoAssentamento->getVariavelTipoAssentamentoFinanceiro();
    }

    $oStdValorQtde               = new stdClass();
    $oStdValorQtde->valor        = 0;
    $oStdValorQtde->quantidade   = 0;
    $this->oValorQuantidade      = $oStdValorQtde;

    try {

      if($sFormula === false) {
        throw new BusinessException("Não foi possível recuperar a fórmula.");
      }

      $sSqlValorQuantidade = $oDbformula->parse("SELECT [". $sFormula ."]");
      $rsValorQuantidade   = db_query($sSqlValorQuantidade);

      if(!$rsValorQuantidade) {
        throw new DBException("Ocorreu um erro ao recuperar o valor/quantidade da formula.\n".pg_last_error());
      }

      if(pg_num_rows($rsValorQuantidade) > 0) {
        $sFieldName       = pg_field_name($rsValorQuantidade, 0);
        $nValorQuantidade = pg_result($rsValorQuantidade, 0, $sFieldName);
      }

      if($oTipoAssentamento->getTipoLancamentoTipoAssentamentoFinanceiro() != 1 && $oTipoAssentamento->getTipoLancamentoTipoAssentamentoFinanceiro() != 2) {
        throw new BusinessException("Não foi possível obter o tipo de lancamento da rubrica configurada para o assentamento.");
      }

      if($oTipoAssentamento->getTipoLancamentoTipoAssentamentoFinanceiro() == 1) {
        $oStdValorQtde->valor      = $nValorQuantidade;
      }

      if($oTipoAssentamento->getTipoLancamentoTipoAssentamentoFinanceiro() == 2) {
        $oStdValorQtde->quantidade = $nValorQuantidade;
      }

      $this->oValorQuantidade      = $oStdValorQtde;

    } catch (Exception $oErro) {
      $this->sErro = $oErro->getMessage();
    }
  }

  /**
   * Retorna o valor para formula executada a ser lançado no ponto do servidor
   *
   * @return Number
   */
  public function getValorPorFormula() {

    if(empty($this->oValorQuantidade)) {
      $this->setValorQuantidadePorFormula();
    }

    return $this->oValorQuantidade->valor;
  }

  /**
   * Retorna a quantidade para formula executada a ser lançada no ponto do servidor
   *
   * @return Number
   */
  public function getQuantidadePorFormula() {

    if(empty($this->oValorQuantidade)) {
      $this->setValorQuantidadePorFormula();
    }

    return $this->oValorQuantidade->quantidade;
  }

  /**
   * Retorna uma intância de LoteRegistroPonto caso o Assentamento esteja vinculado a um Lote.
   *
   * @return LoteRegistroPonto Lote no qual o Assentamento esta vinculado
   */
  public function getLote(){

    $oDaoAssentaloteregistroponto = new cl_assentaloteregistroponto();
    $sSqlAssentaloteregistroponto = $oDaoAssentaloteregistroponto->sql_query_file(null, "rh160_loteregistroponto", null, "rh160_assentamento = {$this->getCodigo()}");
    $rsAssentaloteregistroponto   = db_query($sSqlAssentaloteregistroponto);

    if (!$rsAssentaloteregistroponto) {
      throw new DBException("Erro ao verificar se o assentamento possui lote");
    }

    if (pg_num_rows($rsAssentaloteregistroponto) > 0) {

      $iLoteRegistroPonto = db_utils::fieldsMemory($rsAssentaloteregistroponto, 0)->rh160_loteregistroponto;
      return LoteRegistrosPontoRepository::getInstanceByCodigo($iLoteRegistroPonto);
    }

    return false;
  }

  /**
   * Retorna os atributos dinâmicos do assentamento
   * @return array
   * @throws \DBException
   */
  public function getAtributosDinamicos() {

    $aAtributosDinamicos = array();

    $sSqlBuscaAtributos  = 'select db109_sequencial, 
  	        	                     db109_descricao, 
  	        	                     db110_valor
 	                            from db_cadattdinamico                                 
 	                      inner join db_cadattdinamicoatributos         on db109_db_cadattdinamico          = db118_sequencial        
 	                      inner join db_cadattdinamicoatributosvalor    on db110_db_cadattdinamicoatributos = db109_sequencial
 	                      inner join db_cadattdinamicovalorgrupo        on db120_sequencial                 = db110_cadattdinamicovalorgrupo 
 	                      inner join assentadb_cadattdinamicovalorgrupo on h80_db_cadattdinamicovalorgrupo  = db120_sequencial
 	                           where h80_assenta = '. $this->getCodigo();

    $sIndice = "AtributosDinamicosAssentamento:{$this->getCodigo()}";

    if ( DBRegistry::get($sIndice) ) {
      return DBRegistry::get($sIndice);
    }

    $rsBuscaAtributos = db_query($sSqlBuscaAtributos);

    if(!$rsBuscaAtributos) {
      throw new DBException("Ocorreu um erro ao buscar os atributos dinâmicos para o assentamento.");
    }

    if(pg_num_rows($rsBuscaAtributos) > 0) {

      for ($iIndAtributos = 0; $iIndAtributos < pg_num_rows($rsBuscaAtributos); $iIndAtributos++) {

        $oAtributoDinamico   = new stdClass;
        $oAtributoDinamico->nomeAtributo  = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_descricao;
        $oAtributoDinamico->valorAtributo = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db110_valor;

        $iCodigoAtributo = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_sequencial;
        $aAtributosDinamicos[$iCodigoAtributo] = $oAtributoDinamico;
      }
    }

    DBRegistry::add($sIndice, $aAtributosDinamicos);
    return $aAtributosDinamicos;
  }

  /**
   * Retorna caso o assentamento deve ser caculado pelo dia dos meses.
   * @return bool
   */
  public function possuiCalculoPorDiaDoMes() {
    return $this->lControleDiasNoMes;
  }

  /**
   * Define o valor do atributo dinamico pelo seu nome
   * @param $sNomeAtributo
   * @param $sValor
   * @throws \BusinessException
   */
  public function setValorAtributo($sNomeAtributo, $sValor) {

    $oAtributo = $this->oTipoAssentamento->getAtributoDinamicoPorNome($sNomeAtributo);
    if (empty($oAtributo)) {
      throw new BusinessException("Atributo {$sNomeAtributo} não encontrato para o tipo de assentamento {$this->oTipoAssentamento->getCodigo()}");
    }
    $this->aValoresAtributosDinamicos[$oAtributo->codigoAtributo] = $sValor;
  }

  public function salvarAtributos() {

    $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
    $oDaoAssentaAttr->excluir(null, null, "h80_assenta = {$this->getCodigo()}");
    $aAtributos = $this->oTipoAssentamento->getAtributosDinamicos();

    if (empty($aAtributos)) {
      return;
    }
    $oDaoGrupoValores = new cl_db_cadattdinamicovalorgrupo();
    $oDaoGrupoValores->incluir(null);
    if ($oDaoGrupoValores->erro_status == 0) {
      throw new BusinessException("Erro ao gerar grupo de valores para o atributo Dinâmico.");
    }
    if (empty($this->aValoresAtributosDinamicos)) {
      return;
    }
    $iCodigoGrupo =  $oDaoGrupoValores->db120_sequencial;
    $oDaoAssentaAttr->h80_assenta                     = $this->getCodigo();
    $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $iCodigoGrupo;
    $oDaoAssentaAttr->incluir($this->getCodigo(), $iCodigoGrupo);
    if ($oDaoAssentaAttr->erro_status == 0) {
      throw new BusinessException("Erro ao gerar grupo de valores para o atributo Dinâmico.");
    }
    $oDaoAtributoValor = new cl_db_cadattdinamicoatributosvalor();
    foreach ($this->aValoresAtributosDinamicos as $iCodigoAtributo => $valor) {

      $oDaoAtributoValor->db110_cadattdinamicovalorgrupo   = $iCodigoGrupo;
      $oDaoAtributoValor->db110_db_cadattdinamicoatributos = $iCodigoAtributo;
      $oDaoAtributoValor->db110_valor                      = $valor;

      $oDaoAtributoValor->incluir(null);
      if ($oDaoAtributoValor->erro_status == 0) {
        throw new Exception($oDaoAtributoValor->erro_msg);
      }
    }
  }

  /**
   * Retorna um array com todas as horas extras lançadas manualmente para o assentamento
   * @return array
   * @throws DBException
   */
  public function getHorasManuais() {

    /* inicializa um array com todas horas permitidas */
    $horasPermitidas = \ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento::$horasExtrasPermitidas;
    $horasRetorno = array();
    foreach ($horasPermitidas as $codigoHora) {
      $horasRetorno[$codigoHora] = '00:00';
    }

    /* caso a natureza não seja do tipo manual */
    if ((int)$this->getInstanciaTipoAssentamento()->getNatureza() !== Assentamento::NATUREZA_HE_MANUAL) {
      return $horasRetorno;
    }

    $daoHoras = new cl_assentamentohoraextra();
    $resBuscaHorasExtras = db_query($daoHoras->sql_query_file(null, "*", 'h17_sequencial', "h17_assenta = {$this->iCodigo}"));
    if (!$resBuscaHorasExtras) {
      throw new DBException("Ocorreu um erro ao consultar as horas extras manuais.");
    }

    /* caso não exista horas lançadas para o assentamento de natureza HE Manual */
    $totalRegistrosLancados = pg_num_rows($resBuscaHorasExtras);
    if ($totalRegistrosLancados === 0) {
      return $horasRetorno;
    }

    for ($rowHoras = 0; $rowHoras < $totalRegistrosLancados; $rowHoras++) {

      $stdHorasLancadas = db_utils::fieldsMemory($resBuscaHorasExtras, $rowHoras);
      $horasRetorno[$stdHorasLancadas->h17_tipo] = $stdHorasLancadas->h17_hora;
    }
    return $horasRetorno;
  }
}
