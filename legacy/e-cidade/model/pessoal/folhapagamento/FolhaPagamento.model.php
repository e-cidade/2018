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
 * Classe representa uma folha de pagamento
 * @author $Author: dbrenan $
 * @version $Revision: 1.76 $
 */
abstract class FolhaPagamento { 
    
  /**
   * Tipos de Folha de Pagamento
   */
  const TIPO_FOLHA_SALARIO      = 1;
  const TIPO_FOLHA_RESCISAO     = 2;
  const TIPO_FOLHA_COMPLEMENTAR = 3;
  const TIPO_FOLHA_ADIANTAMENTO = 4;
  const TIPO_FOLHA_13o_SALARIO  = 5;
  const TIPO_FOLHA_SUPLEMENTAR  = 6;

  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamento.';
    
  /**
   * Código unico de Cada folha de pagamento.
   *
   * @var Integer
   */
  private $iSequencial;
  
  /**
   * Código de Controle da folha de pagamento, 
   * para que exista mais de uma na mesma competencia
   * 
   * @var Integer
   */
  public $iNumero;
  
  /**
   * Competência atual da folha 
   * 
   * @var DBCompetencia
   * @access private
   */
  private $oCompetenciaFolha;

  /**
   * Competência referência da folha
   * 
   * @var DBCompetencia
   * @access private
   */
  private $oCompetenciaReferencia;
  
  /**
   * A instutição da folha
   * 
   * @var Instituicao
   * @access private
   */
  private $oInstituicao;
  
  /**
   * A descrição sobre a folha criada
   * 
   * @var String
   * @access private
   */
  private $sDescricao;
  
  /**
   * Tipo de Folha de Pagamento
   *
   * @var integer
   * @access protected
   */
  protected $iTipoFolha;
  
  /**
   * Situação da folha de pagamento, validando se ela esta aberta ou fechada
   * 
   * @var boolean
   * @access private
   */
  private $lAberto;
  
  /**
   * Construtor da Classe
   * 
   * @param integer $iSequencial
   * @param integer $iTipoFolha
   */
  function __construct( $iSequencial, $iTipoFolha ) {
    
    $this->iSequencial = $iSequencial;
    $this->setTipoFolha($iTipoFolha);
    $this->setCompetenciaFolha( DBPessoal::getCompetenciaFolha() );

    $oDadosMensagem          = new stdClass();

    if (!empty($iSequencial) ) {

      $oDaoRHFolhaPagamento    = new cl_rhfolhapagamento();
      $sSql                    = $oDaoRHFolhaPagamento->sql_query_file($iSequencial);
      $rsSql                   = db_query($sSql);
      if ( !$rsSql ) {
        throw new DBException(_M(self::MENSAGENS . "erro_instanciar_objeto") );
      }

      if ( pg_num_rows($rsSql) == 0 ) {
       
        $oDadosMensagem->iCodigo = $iSequencial;
        throw new DBException(_M(self::MENSAGENS . "codigo_folha_incorreto", $oDadosMensagem) );
      }

      $oDadosFolhaPagamento = db_utils::fieldsMemory($rsSql, 0);

      if ( $oDadosFolhaPagamento->rh141_tipofolha <> $iTipoFolha ) {

        $aDescricaoFolha = array(
          FolhaPagamento::TIPO_FOLHA_SALARIO      => "Salário",
          FolhaPagamento::TIPO_FOLHA_RESCISAO     => "Rescisão",
          FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR => "Complementar",
          FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO => "Adiantamento",
          FolhaPagamento::TIPO_FOLHA_13o_SALARIO  => "13º Salário",
          FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR  => "Suplementar"
        );

        $oDadosMensagem->sTipoInformado = $aDescricaoFolha[$iTipoFolha];
        $oDadosMensagem->sTipoEsperado  = $aDescricaoFolha[$oDadosFolhaPagamento->rh141_tipofolha];
        throw new BusinessException(_M(self::MENSAGENS . "inconsistencia_tipo", $oDadosMensagem) );
      }

      $this->setNumero($oDadosFolhaPagamento->rh141_codigo);
      $this->setCompetenciaReferencia(new DBCompetencia($oDadosFolhaPagamento->rh141_anoref, $oDadosFolhaPagamento->rh141_mesref) );
      $this->setCompetenciaFolha( new DBCompetencia($oDadosFolhaPagamento->rh141_anousu, $oDadosFolhaPagamento->rh141_mesusu) );
      $this->setInstituicao( InstituicaoRepository::getInstituicaoByCodigo($oDadosFolhaPagamento->rh141_instit) );

      if ( $oDadosFolhaPagamento->rh141_aberto == 't') {
        $this->setFolhaAberta();
      } else {
        $this->setFolhaFechada();
      }

      $this->setDescricao($oDadosFolhaPagamento->rh141_descricao);
      return;
    }

    $this->setFolhaAberta();
    return;
  }

  /**
   * Retorna o número sequencial da folha
   * 
   * @return Integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }
   
  /**
   * Seta o número Sequencial da folha
   *
   * @return Integer
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial; 
  }

  /**
   * Retorna o número da folha de pagamento. OBS.: Não é sequencial
   * 
   * @return Integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Seta o número da folha pagamento
   * 
   * @param Integer $iNumero
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }
  
  /**
   * Retorna a competência da folha
   * 
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetenciaFolha;
  }

  /**
   * Retorna a competência da referência
   * 
   * @return DBCompetencia
   */
  public function getCompetenciaReferencia() {
    return $this->oCompetenciaReferencia;
  }

  /**
   * Retorna a instituição da folha de pagamento
   * 
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna a descrição da folha
   * 
   * @return String
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Verifica se a folha esta aberta ou fechada
   * 
   * @return boolean
   */
  public function isAberto() {
    return $this->lAberto;
  }

  /**
   * Seta a competência da folha
   * 
   * @param DBCompetencia $oCompetenciaFolha
   */
  public function setCompetenciaFolha(DBCompetencia $oCompetenciaFolha) {
    $this->oCompetenciaFolha = $oCompetenciaFolha;
  }

  /**
   * Seta a competência referência da folha
   * 
   * @param DBCompetencia $oCompetenciaReferencia
   */
  public function setCompetenciaReferencia(DBCompetencia $oCompetenciaReferencia) {
    $this->oCompetenciaReferencia = $oCompetenciaReferencia;
  }

  /**
   * Seta a instuição da folha
   * 
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta a descrição da folha
   * 
   * @param String $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Seta a folha como aberta
   */
  public function setFolhaAberta() {
    $this->lAberto = true;
  }

  /**
   * Seta a folha como fechada
   */
  public function setFolhaFechada() {
    $this->lAberto = false;
  }
    
  /**
   * Retorna o tipo da folha
   * 
   * @return Integer
   */
  public function getTipoFolha() {
    return $this->iTipoFolha;
  }

  /**
   * Seta o tipo da folha
   * 
   * @param Integer $iTipoFolha
   */
  public function setTipoFolha($iTipoFolha) {
    $this->iTipoFolha = $iTipoFolha;
  }

  /**
   * Retorna a ultima folha aberta do tipo passado por parametro
   *
   * @param Integer        $iTipoFolha Código do tipo da folha
   * @param null           $lAberta
   * @param \DBCompetencia $oCompetencia
   * @return int Sequencial da folha aberta
   * @throws \DBException
   * @example FolhaPagamento::getFolhaAberta(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)
   * @access public
   */
  public static function getCodigoFolha($iTipoFolha, $lAberta = null, DBCompetencia $oCompetencia = null) {


    if ( is_null($oCompetencia)) {

      $iMesFolha            = DBPessoal::getMesFolha();
      $iAnoFolha            = DBPessoal::getAnoFolha();
    } else {

      $iMesFolha            = $oCompetencia->getMes();
      $iAnoFolha            = $oCompetencia->getAno();
    }
    $iInstituicao         = db_getsession("DB_instit");

    $iChave = $iTipoFolha."#".$iInstituicao.'#'.$iMesFolha.'#'.$iAnoFolha.'#'.$lAberta;
    if (DBRegistry::has($iChave)) {
      return DBRegistry::get($iChave);
    }
    if ( is_null($lAberta) ) {
      $sCondicaoAberta = 'true ';
    } else if( $lAberta === true ) {
      $sCondicaoAberta    = "rh141_aberto        = true            ";   
    } else {
      $sCondicaoAberta    = "rh141_aberto        = false           ";
    }

    $sWhere               = $sCondicaoAberta;
    $sWhere              .= "and rh141_tipofolha = {$iTipoFolha}   ";
    $sWhere              .= "and rh141_anousu    = {$iAnoFolha}    ";
    $sWhere              .= "and rh141_mesusu    = {$iMesFolha}    ";
    $sWhere              .= "and rh141_instit    = {$iInstituicao} ";

    $oDaoFolhaPagamento   = new cl_rhfolhapagamento();
    $sSql                 = $oDaoFolhaPagamento->sql_query_file(null, "rh141_sequencial", " rh141_anousu desc, rh141_mesusu desc, rh141_codigo desc limit 1", $sWhere);
    $rsRegistros          = db_query($sSql);

    if ( !$rsRegistros ) {
      throw new DBException(_M(self::MENSAGENS . "erro_buscar_dados_folha_aberta")); 
    }
   
    if ( pg_num_rows($rsRegistros) == 0 ) {
      DBRegistry::add($iChave, '');
      return false;
    }
    
    $oDadosFolhaPagamento = db_utils::fieldsMemory($rsRegistros, 0);
    DBRegistry::add($iChave, $oDadosFolhaPagamento->rh141_sequencial);
    return $oDadosFolhaPagamento->rh141_sequencial;
  }

  /**
   * Retorna se há uma folha aberta
   * 
   * @param int $iTipoFolha Código do tipo da folha
   * @example FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)
   * 
   * @return boolean
   */
  public static function hasFolhaAberta($iTipoFolha, DBCompetencia $oCompetencia = null) {

    $iInstituicao = db_getsession("DB_instit");

    $sWhere  = "rh141_aberto        = true           ";
  
    if ( !is_null($oCompetencia) ) {

      $sWhere .= " and rh141_anousu = {$oCompetencia->getAno()} ";
      $sWhere .= " and rh141_mesusu = {$oCompetencia->getMes()} ";
    }

    $sWhere   .= "and rh141_tipofolha = {$iTipoFolha}  ";
    $sWhere   .= "and rh141_instit    = {$iInstituicao}";

    $oDaoFolhaPagamento = new cl_rhfolhapagamento();
    $sSql               = $oDaoFolhaPagamento->sql_query_file(null, "rh141_sequencial", null, $sWhere);

    $rsRegistros = db_query($sSql);
    if (!$rsRegistros) {
      throw new DBException(_M(self::MENSAGENS . "erro_procurar_folha_aberta")); // arquivos message
    }
    
    return ( pg_num_rows($rsRegistros) != 0 );
  }

  /**
   * Verifica se existe algum registro pelo tipo passado e/ou competência
   * 
   * @param Integer $iTipoFolha Tipo da folha a ser pesquisada
   * @param DBCompetencia $oCompetencia Competencia a ser filtrada
   * @param Boolean 
   * @return Boolean
   */
  public static function hasFolhaTipo($iTipoFolha, DBCompetencia $oCompetencia = null, $lEstado = null) {

    $iInstituicao = db_getsession("DB_instit");

    $sWhere  = "     rh141_tipofolha = {$iTipoFolha}  ";
    $sWhere .= " AND rh141_instit    = {$iInstituicao}";

    if ( !is_null($lEstado) ) {

      $sEstado = $lEstado ? "true" : "false";
      $sWhere .= " and rh141_aberto is $sEstado ";
    }

    /**
     * Verifica se foi passado competencia por parametros
     */
    if ($oCompetencia) {
      $sWhere .= " AND rh141_anousu = {$oCompetencia->getAno()}";
      $sWhere .= " AND rh141_mesusu = {$oCompetencia->getMes()}";
    }

    $oDaoFolhaPagamento = new cl_rhfolhapagamento();
    $sSql               = $oDaoFolhaPagamento->sql_query_file(null, "rh141_sequencial", null, $sWhere);

    $rsRegistros = db_query($sSql);
    if (!$rsRegistros) {
      throw new DBException(_M(self::MENSAGENS . "erro_procurar_registro"));
    }
    
    return (boolean) pg_num_rows($rsRegistros);
  }

  /**
   * Retorna o ultimo nÃºmero unico da folha pagamento, conforme o tipo passado.
   * 
   * @access protected
   * @param Integer $iTipoFolha Código do tipo da folha
   * @example FolhaPagamento:getNextValue(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)
   * 
   * @return Integer Valor do (maior|último) registro da folha pagamento
   */
  protected static function getProximoNumero($iTipoFolha) {

    $iMesFolha            = DBPessoal::getMesFolha();
    $iAnoFolha            = DBPessoal::getAnoFolha();
    $iInstituicao         = db_getsession("DB_instit");

    $oDaoFolhaPagamento = new cl_rhfolhapagamento();

    $sField  = " max(rh141_codigo) as rh141_codigo";
    $sWhere  = "     rh141_tipofolha = {$iTipoFolha}";
    $sWhere .= " AND rh141_instit    = {$iInstituicao}";
    $sWhere .= " AND rh141_mesusu    = {$iMesFolha}";
    $sWhere .= " AND rh141_anousu    = {$iAnoFolha}";

    $sSqlFolhaPagamento = $oDaoFolhaPagamento->sql_query_file(null, $sField, null, $sWhere);
    $rsFolhaPagamento   = $oDaoFolhaPagamento->sql_record($sSqlFolhaPagamento);

    if (!$rsFolhaPagamento) {
      throw new DBException(_M(self::MENSAGENS . "error_retornar_proximo_numero"));
    }
    
    $oFolhaPagamento = db_utils::fieldsMemory($rsFolhaPagamento, 0);
        
    return $oFolhaPagamento->rh141_codigo + 1;
  }

  /**
   * Este método "salva" e "altera" a folha de pagamento
   *
   * @return Integer
   */
  public function salvar() {

    $oDAOFolhaPagamento = new cl_rhfolhapagamento();
    $oDAOFolhaPagamento->rh141_codigo = $this->getNumero();
    $oDAOFolhaPagamento->rh141_anoref = $this->getCompetenciaReferencia()->getAno();
    $oDAOFolhaPagamento->rh141_mesref = $this->getCompetenciaReferencia()->getMes();
    $oDAOFolhaPagamento->rh141_anousu = $this->getCompetencia()->getAno();
    $oDAOFolhaPagamento->rh141_mesusu = $this->getCompetencia()->getMes();
    $oDAOFolhaPagamento->rh141_instit = $this->getInstituicao()->getCodigo();
    $oDAOFolhaPagamento->rh141_aberto = $this->isAberto() ? 't' : 'f';
    $oDAOFolhaPagamento->rh141_descricao = $this->getDescricao();

    if (empty($this->iSequencial)) {

      $oDAOFolhaPagamento->rh141_tipofolha = $this->getTipoFolha();
      $oDAOFolhaPagamento->incluir(null);
      $this->iSequencial = $oDAOFolhaPagamento->rh141_sequencial;

    } else {
      $oDAOFolhaPagamento->rh141_sequencial = $this->getSequencial();
      $oDAOFolhaPagamento->alterar($this->getSequencial());
    }

    if ( $oDAOFolhaPagamento->erro_status == "0" ) {
      throw new DBException($oDAOFolhaPagamento->erro_msg);
    }

    return $this->getSequencial();
  }
  
  /**
   * Exclui a Folha atual (iSequencial) da tabela rhfolhapagamento
   * @return boolean
   */
  public function excluir() {

    $oDAOCalculo = db_utils::getDao($this->getTabelaCalculo());
    $oDAOCalculo->excluir($this->getCompetencia()->getAno(), $this->getCompetencia()->getMes());
    
    $oDaoRhHistoricoPonto = new cl_rhhistoricoponto();
    $oDaoRhHistoricoPonto->excluir(null, "rh144_folhapagamento = {$this->iSequencial}");

    if ($oDaoRhHistoricoPonto->erro_status == "0") {
      throw new DBException( _M(self::MENSAGENS . "erro_excluir_historico_ponto"));
    }

    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$this->iSequencial}");

    if ($oDaoRhHistoricoCalculo->erro_status == "0") {
      throw new DBException( _M(self::MENSAGENS . "erro_excluir_historico_calculo"));
    }

    $oDaoRhHistoricoPensao = new cl_rhhistoricopensao();
    $oDaoRhHistoricoPensao->excluir(null, "rh145_rhfolhapagamento = {$this->iSequencial}");

    if ($oDaoRhHistoricoPensao->erro_status == "0") {
      throw new DBException( _M(self::MENSAGENS . "erro_excluir_historicopensao"));
    }

    $oDaoFolhaPagamentoGeracao = new cl_folhapagamentogeracao;
    $oDaoFolhaPagamentoGeracao->excluir(null, "rh146_folhapagamento = {$this->iSequencial}");

    if ($oDaoFolhaPagamentoGeracao->erro_status = "0") {
      throw new DBException( _M(self::MENSAGENS . "erro_excluir_folhapagamentogeracao"));
    }


    $oDaoFolhaPagamento = new cl_rhfolhapagamento;
    $oDaoFolhaPagamento->excluir($this->iSequencial);

    if ($oDaoFolhaPagamento->erro_status == "0"){
      throw new DBException( _M(self::MENSAGENS . "erro_excluir"));
    }


    return true;
  }

  /**
   * Função Abstrata para fechamento da folha.
   * @return boolean
   */
  public abstract function fechar();

  /**
   * Função abstrata para cancelamento da abertura da folha.
   */
  public abstract function cancelarAbertura();
  
  /**
   * Realiza o cancelamento do fechamento da Folha, as seguintes 
   * regras devem ser respeitadas:
   * - Folha informada estar fechada
   * - Não pode existir outra folha do mesmo tipo em aberto
   * - A folha informada não pode estar empenhada
   * @return boolean
   */
  public function cancelarFechamento() {
    
    $oDaoFolhaPagamento = new cl_rhfolhapagamento;

    /**
     * Verifica se a folha informada esta fechada.
     */
    if ($this->lAberto) {
      throw new BusinessException(_M(self::MENSAGENS . "folha_informada_aberta"));
    }
    
    /**
     * Verifica se não existe nenhuma folha aberta,
     * se existir não pode ser cancelado o  fechamento
     */
    $sWhereFolhaPagamento  = "    rh141_aberto is true ";
    $sWhereFolhaPagamento .= "and rh141_tipofolha = {$this->iTipoFolha} ";
    $sWhereFolhaPagamento .= "and rh141_instit = " . db_getsession('DB_instit');

    $sSqlFolhaPagamento   = $oDaoFolhaPagamento->sql_query_file($this->iSequencial, 'rh141_sequencial, rh141_codigo as icodigo', null, $sWhereFolhaPagamento);
    $rsFolhaPagamento     = db_query($sSqlFolhaPagamento);

    if (pg_num_rows($rsFolhaPagamento) != 0) {

      $oFolhaPagamento = db_utils::fieldsMemory($rsFolhaPagamento, 0);
      throw new BusinessException(_M(self::MENSAGENS . "existe_folha_aberta", $oFolhaPagamento));
    }
   
   /**
    * Verifica se a folha informada não posusi empenho,
    * se possuir não pode ser cancelado o fechamento.
    */
    $this->verificarEmpenho();

    /**
     * Cancela o fechamento da folha, alterando o campo rh141_aberto de 
     * false para true.
     */
    $this->setFolhaAberta();
    $this->salvar();
    
   
    /**
     * Esta função retorna os dados dos históricos do ponto (rhhistoricoponto) para o ponto (pontocom)  
     */
    $this->retornarPonto();

    /**
     * Exlui o histórico do ponto
     * 
     */
    $oDaoRhHistoricoPonto   = new cl_rhhistoricoponto();
    $oDaoRhHistoricoPonto->excluir(null, "rh144_folhapagamento = {$this->iSequencial}");
    
    if( $oDaoRhHistoricoPonto->erro_status == 0){
      throw new DBException(_M(self::MENSAGENS . "erro_excluir_historico_ponto"));       
    }

    /**
     * Exclui o histórico do cálculo
     */
    $oDaoRhHistoricoCalculo   = new cl_rhhistoricocalculo();
    $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$this->iSequencial}");
    
    if( $oDaoRhHistoricoCalculo->erro_status == 0){
      throw new DBException(_M(self::MENSAGENS . "erro_excluir_historico_calculo"));       
    }    

    return true;
  }

  /**
   * Altera o a Folha de Pagamento para fechado.
   * @return boolean
   */
  public function fecharFolha(){
   
    $this->lAberto = false;
    $this->salvar();
  }

/**
 * Exclui rubrica específica do histórico do ponto.
 * @param  Integer $iMatricula Matricula (regist) do servidor
 * @param  String  $sRubrica   Código da rubrica que será excluída
 * @return boolean
 */
  public function excluirRubricaHistoricoPonto( $iMatricula, $sRubrica )  {

    $oDaoRhHistoricoPonto = new cl_rhhistoricoponto();
    $oDaoRhHistoricoPonto->excluir(null,"rh144_folhapagamento = {$this->iSequencial} and rh144_regist = {$iMatricula} and rh144_rubrica = '{$sRubrica}'");

    if ($oDaoRhHistoricoPonto->erro_status == 0){
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_historico_ponto'));
    }

    return true;
  }
  
  /**
   * Excluí rubrica específica do histórico do cálculo.
   * 
   * @access public
   * @param Integer $iMatricula
   * @param String $sRubrica
   * @return Boolean
   * @throws DBException
   */
  public function excluirRubricaHistoricoCalculo($iMatricula, $sRubrica)  {

    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoRhHistoricoCalculo->excluir(null,"rh143_folhapagamento = {$this->iSequencial} and rh143_regist = {$iMatricula} and rh143_rubrica = '{$sRubrica}'");

    if ($oDaoRhHistoricoCalculo->erro_status == 0){
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_historico_calculo'));
    }

    return true;
  }

  public function salvarHistoricoPonto ($aServidores) {

    if ( count($aServidores) == 0 ) {
      return ;
    }

    $oDaoRhHistoricoPonto = new cl_rhhistoricoponto();
    $oDaoRhHistoricoPonto->rh144_sequencial     = null;
    $oDaoRhHistoricoPonto->rh144_folhapagamento = $this->iSequencial;

    /**
     * Percorre os servidores, buscando os registros financeiros 
     * e salvando na tabela rhhistoricopontos.
     */
    $sMatriculaServidores = implode(',', array_keys($aServidores));
    $oDaoRhHistoricoPonto->excluir(null, "rh144_folhapagamento = {$this->iSequencial} and rh144_regist in ({$sMatriculaServidores})"); 

    if ($oDaoRhHistoricoPonto->erro_status == 0){
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_historico_ponto'));
    }

    $sSigla = $this->getSiglaPonto();

    /**
     * Realizamos a cópia dos dados do ponto para a tabela
     * de historicoponto.
     */
    $sSqlRhHistoricoPonto  = "insert into rhhistoricoponto ";
    $sSqlRhHistoricoPonto .= "select";
    $sSqlRhHistoricoPonto .= "   nextval('rhhistoricoponto_rh144_sequencial_seq'),";
    $sSqlRhHistoricoPonto .= "   {$sSigla}_regist,";
    $sSqlRhHistoricoPonto .= "   {$this->iSequencial},";
    $sSqlRhHistoricoPonto .= "   {$sSigla}_rubric,";
    $sSqlRhHistoricoPonto .= "   {$sSigla}_quant,";
    $sSqlRhHistoricoPonto .= "   {$sSigla}_valor ";
    $sSqlRhHistoricoPonto .= "from ";
    $sSqlRhHistoricoPonto .= "{$this->getTabelaPonto()} ";
    $sSqlRhHistoricoPonto .= "where";
    $sSqlRhHistoricoPonto .= "  {$sSigla}_anousu = " . DBPessoal::getAnoFolha();
    $sSqlRhHistoricoPonto .= "  and {$sSigla}_mesusu = " . DBPessoal::getMesFolha();

    $rsRhhistoricoPonto = db_query($sSqlRhHistoricoPonto);

    if (!$rsRhhistoricoPonto) {
      throw new DBException(_M(self::MENSAGENS . 'erro_salvar_historico_ponto'));
    }

    return true;
  }

  /**
   * Salva o hitorico do calculo. Remove os dados das tabelas 
   * de calculo e insere na tabela rhhistoricocalculo.
   * @param  array $aServidores
   * @return boolean
   */
  public function salvarHistoricoCalculo($aServidores){

    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoRhHistoricoCalculo->rh143_sequencial     = null;
    $oDaoRhHistoricoCalculo->rh143_folhapagamento = $this->iSequencial;

    /**
     * Percorre os servidores, buscando os registros financeiros 
     * e salvando na tabela rhhistoricocalculo.
     */
    foreach ($aServidores as $oServidor) {
      
      $oDaoRhHistoricoCalculo->rh143_regist = $oServidor->getMatricula();
      
      $oCalculoFinanceiro  = $oServidor->getCalculoFinanceiro($this->getTabelaCalculo());
      $aEventosFinanceiros = $oCalculoFinanceiro->getEventosFinanceiros();

      foreach ($aEventosFinanceiros as $oRegistro) {

        $oDaoRhHistoricoCalculo->rh143_rubrica    = $oRegistro->getRubrica()->getCodigo();
        $oDaoRhHistoricoCalculo->rh143_quantidade = $oRegistro->getQuantidade();
        $oDaoRhHistoricoCalculo->rh143_valor      = $oRegistro->getValor();
        $oDaoRhHistoricoCalculo->rh143_tipoevento = $oRegistro->getNatureza();
        $oDaoRhHistoricoCalculo->incluir(null);
      }

      if ($oDaoRhHistoricoCalculo->erro_status == "0") {
        throw new DBException($oDaoRhHistoricoCalculo->erro_msg);        
      }
    }
    
    return true;
  }

  /**
   * Esta função retorna os dados dos históricos do ponto (rhhistoricoponto) para o ponto (pontocom)  
   *
   * @return @boolean
   */
  public function retornarPonto($aServidoresCalcular = null){

    $oDaoRhHistoricoPonto   = new cl_rhhistoricoponto();
    $sWhereRhHistoricoPonto = "rh144_folhapagamento = {$this->iSequencial}";

    if(!empty($aServidoresCalcular) && count($aServidoresCalcular) > 0) {
      $sWhereMatriculasCalcular  = implode(",", $aServidoresCalcular);
      $sWhereRhHistoricoPonto   .= " and rh144_regist in ({$sWhereMatriculasCalcular})";
    }

    $sSqlRhHistoricoPonto   = $oDaoRhHistoricoPonto->sql_query_file(null, "distinct rh144_regist", null, $sWhereRhHistoricoPonto);

    $rsRhHistoricoPonto     = db_query($sSqlRhHistoricoPonto);

    if (!$rsRhHistoricoPonto) {
      throw new DBException(_M(self::MENSAGENS .  "erro_consulta_historico_ponto"));
    }

    $aRegistrosRestaurar  =array();
       
    for ($iCodigoHistorico = 0; $iCodigoHistorico < pg_num_rows($rsRhHistoricoPonto); $iCodigoHistorico++) {

      $oHistorico     = db_utils::fieldsMemory($rsRhHistoricoPonto, $iCodigoHistorico);
      $oServidor      = ServidorRepository::getInstanciaByCodigo(
        $oHistorico->rh144_regist, 
        $this->getCompetencia()->getAno(), 
        $this->getCompetencia()->getMes(), 
        $this->getInstituicao()->getSequencial()
      );
      
      $oServidor->getPonto($this->getTabelaPonto())->limpar();

      /**
       * Aqui remove os dados do ponto do servidor vinculado a matricula, 
       * essa ação é necessária para que o ponto não fique duplicado
       */
      if ( $oServidor->hasServidorVinculado() ) {
        $oServidor->getServidorVinculado()->getPonto($this->getTabelaPonto())->limpar();
      }

      $aRegistrosRestaurar[$oServidor->getMatricula()] = $this->getHistoricoRegistrosPonto($oServidor);
    }

    while ( list($iMatricula, $aRegistros) = each($aRegistrosRestaurar) ) {

      $oPonto = $oServidor->getPonto($this->getTabelaPonto());

      for( $iRegistro = 0; $iRegistro < count($aRegistros); $iRegistro++) {
        $oPonto->adicionarRegistro($aRegistros[$iRegistro]);
      }
      $oPonto->salvar();
    }
    return true;
  }

  public function retornarCalculo() {

    $oDaoRhHistoricoCalculo   = new cl_rhhistoricocalculo();
    $sWhereRhHistoricoCalculo = "rh143_folhapagamento = {$this->iSequencial}";
    $sSqlRhHistoricoCalculo   = $oDaoRhHistoricoCalculo->sql_query_file(null, 'distinct rh143_regist', null, $sWhereRhHistoricoCalculo);
    $rsRhHistoricoCalculo     = db_query($sSqlRhHistoricoCalculo);

    if (!$rsRhHistoricoCalculo) {
      throw new DBException(_M(self::MENSAGENS .  "erro_consulta_historico_calculo"));
    }

    for ($iCodigoHistorico = 0; $iCodigoHistorico < pg_num_rows($rsRhHistoricoCalculo); $iCodigoHistorico++) {

      $oHistorico       = db_utils::fieldsMemory($rsRhHistoricoCalculo, $iCodigoHistorico);
      $oServidor        = ServidorRepository::getInstanciaByCodigo($oHistorico->rh143_regist, $this->getCompetencia()->getAno(), $this->getCompetencia()->getMes(), $this->getInstituicao()->getSequencial());
      $oCalculoServidor = $oServidor->getCalculoFinanceiro($this->getTabelaCalculo());
      $oCalculoServidor->limpar();

      foreach ( $this->getHistoricoEventosFinanceiros($oServidor) as $oEventoFinanceiro ) {
        $oCalculoServidor->adicionarEvento($oEventoFinanceiro);
      }

      $oCalculoServidor->salvar();
    }

    return true;
  }

  /**
   * Verifica se a folha informada não possui empenho,
   * se possuir não pode ser cancelado o fechamento.
   * @return boolean
   */
  public function verificarEmpenho(){

    $oDaoRhEmpenhoFolha  = new cl_rhempenhofolha;
    
    $sSigla = $this->getSiglaFolhaPagamento();
    if($this->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR) {
      $sSigla = 'sup';
    } 
    
    $sSqlEmpenhoFolha = $oDaoRhEmpenhoFolha->sql_query_empenhado( $this->getCompetencia()->getAno(), $this->getCompetencia()->getMes(), $sSigla, 'rh72_sequencial', $this->getNumero());
    $rsEmpenhoFolha   = db_query($sSqlEmpenhoFolha);
    
    if (pg_num_rows($rsEmpenhoFolha) != 0) {
      throw new BusinessException(_M(self::MENSAGENS . "existe_empenho"));
    }

    return true;
  }

  public static function getFolhasFechadasCompetencia(DBCompetencia $oCompetencia, $iTipoFolha = null) {

    $oDaoRhFolhaPagamento    = new cl_rhfolhapagamento();
    $iInstituicao            = db_getsession("DB_instit");

    $sWhereRhFolhaPagamento  = "    rh141_anousu    = {$oCompetencia->getAno()} ";
    $sWhereRhFolhaPagamento .= "and rh141_mesusu    = {$oCompetencia->getMes()} ";
    $sWhereRhFolhaPagamento .= "and rh141_instit    = {$iInstituicao}"; 
    
    if ( !is_null($iTipoFolha) ) {
      $sWhereRhFolhaPagamento .= "and rh141_tipofolha = {$iTipoFolha}";
    }

    $sWhereRhFolhaPagamento .= "and rh141_aberto is false";
    $sSqlRhFolhaPagamento    = $oDaoRhFolhaPagamento->sql_query_file(null, 'rh141_tipofolha, rh141_sequencial', null, $sWhereRhFolhaPagamento);
    $rsRhFolhaPagamento      = db_query($sSqlRhFolhaPagamento);
    $aFolhaPagamento         = array();

    for ( $iCodigoFolha = 0; $iCodigoFolha < pg_num_rows( $rsRhFolhaPagamento ); $iCodigoFolha++ ) { 

      $oDadosFolha     = db_utils::fieldsMemory( $rsRhFolhaPagamento, $iCodigoFolha );

      switch ($oDadosFolha->rh141_tipofolha) {

        case  self::TIPO_FOLHA_SALARIO:
          $sClasse = "FolhaPagamentoSalario";
          break;

        case  self::TIPO_FOLHA_SUPLEMENTAR:
          $sClasse = "FolhaPagamentoSuplementar";
          break;
        
        case self::TIPO_FOLHA_COMPLEMENTAR:
          $sClasse = "FolhaPagamentoComplementar";
          break;
      }
      
      $aFolhaPagamento[] = new $sClasse($oDadosFolha->rh141_sequencial);
    }
    
    return $aFolhaPagamento; 
  }
  

  /**
   * Retorna sigla da tabela de acordo com 
   * o iTipoFolha informado para a classe.
   * 
   * @return string sigla da respectiva tabela
   */
  private function getSiglaFolhaPagamento(){

    switch ($this->iTipoFolha) {
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        return 'r14';
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        return 'r20';
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        return 'r48';
      break;  
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        return 'r22';
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        return 'r35';
      break;
      default:
        return false;
      break;
    }
  }

  /**
   * Retorna o nome da tabela do cálculo de 
   * acordo com o tipo folha informado
   * 
   * @return String nome do cálculo
   */
  public function getTabelaCalculo(){

    switch ($this->iTipoFolha) {
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        return CalculoFolha::CALCULO_SALARIO;
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        return CalculoFolha::CALCULO_RESCISAO;
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        return CalculoFolha::CALCULO_COMPLEMENTAR;
      break;
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        return CalculoFolha::CALCULO_ADIANTAMENTO;
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        return CalculoFolha::CALCULO_13o;
      break;
      default:
        return false;
      break;
    }
  }

   /**
   * Retorna a sigla da tabela do cálculo de 
   * acordo com o tipo folha informado
   * 
   * @return String sigla do cálculo
   */
  public function getSiglaCalculo(){

    switch ($this->iTipoFolha) {
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        return 'r14';
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        return 'r20';
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        return 'r48';
      break;
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        return 'r22';
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        return 'r35';
      break;
      default:
        return false;
      break;
    }
  }

  /**
   * Retorna o nome da tabela do ponto de acordo com o tipo folha informado
   * 
   * @return String nome da tabela ponto
   */
  public function getTabelaPonto(){
    
    switch ($this->iTipoFolha) {
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        return Ponto::SALARIO;
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        return Ponto::RESCISAO;
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        return Ponto::COMPLEMENTAR;
      break;
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        return Ponto::ADIANTAMENTO;
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        return Ponto::PONTO_13o;
      break;
      default:
        return false;
      break;
    }
  }

  public function getHistoricoRegistrosPonto( Servidor $oServidor ) {

    $sWhere                   = "    rh144_folhapagamento = {$this->getSequencial()}";
    $sWhere                  .= "and rh144_regist         = {$oServidor->getMatricula()}";
    $oDaoHistoricoCalculo     =  new cl_rhhistoricoponto();
    $sSqlEventos              = $oDaoHistoricoCalculo->sql_query_file(null,"rh144_regist, rh144_rubrica, rh144_quantidade, rh144_valor",null, $sWhere);
    $rsEventos                = db_query($sSqlEventos);
    if ( !$rsEventos ) {
      throw new DBException(pg_last_error());
    }
    
    $aDadosEventosFinanceiros = db_utils::getCollectionByRecord($rsEventos);
    $aEventosFinanceiros      = array();

    foreach ($aDadosEventosFinanceiros as $oHistorico ) {
      
      $oEventoFinanceiro = new RegistroPonto();//@todo Com férias muda o tratamento no futuro
      $oEventoFinanceiro->setServidor($oServidor);
      $oEventoFinanceiro->setRubrica(RubricaRepository::getInstanciaByCodigo($oHistorico->rh144_rubrica));
      $oEventoFinanceiro->setValor($oHistorico->rh144_valor);
      $oEventoFinanceiro->setQuantidade($oHistorico->rh144_quantidade);
      $aEventosFinanceiros[] = $oEventoFinanceiro;
    }
    return $aEventosFinanceiros;
  }

  public function getHistoricoEventosFinanceiros( Servidor $oServidor, $mRubrica = null ) {

    $sWhere                   = "    rh143_folhapagamento = {$this->getSequencial()} ";
    $sWhere                  .= "and rh143_regist         = {$oServidor->getMatricula()} ";

    if(!empty($mRubrica)) {

      if(is_array($mRubrica) && !empty($mRubrica)) {

        if($mRubrica[0] instanceof Rubrica) {

          $aRubricas = array();
          
          for ($iIndRubricas = 0; $iIndRubricas < count($mRubrica); $iIndRubricas++) { 
            $oRubrica    = $mRubrica[$iIndRubricas];
            $aRubricas[] = $oRubrica->getCodigo();
          }

          $sRubricas = implode("', '", $aRubricas);

        } else {
          $sRubricas = implode("', '", $mRubrica);
        }
      } else {
        $sRubricas = $mRubrica;
      }

      $sRubricas  = "'". $sRubricas ."'";
      $sWhere    .= "and rh143_rubrica in (".$sRubricas.")";
    }
    
    $oDaoHistoricoCalculo     =  new cl_rhhistoricocalculo();
    $sSqlEventos              = $oDaoHistoricoCalculo->sql_query_file(null,"rh143_rubrica, rh143_quantidade, rh143_valor, rh143_tipoevento",null, $sWhere);
    $rsEventos                = db_query($sSqlEventos);

    if ( !$rsEventos ) {
      throw new DBException(_M(self::MENSAGENS . "erro_buscar_dados_eventos_folha"));
    }
    
    $aDadosEventosFinanceiros = db_utils::getCollectionByRecord($rsEventos);
    $aEventosFinanceiros      = array();

    foreach ($aDadosEventosFinanceiros as $oHistorico ) {
      
      $oEventoFinanceiro     = new EventoFinanceiroFolha();
      $oEventoFinanceiro->setServidor($oServidor);
      $oEventoFinanceiro->setRubrica(RubricaRepository::getInstanciaByCodigo($oHistorico->rh143_rubrica));
      $oEventoFinanceiro->setValor($oHistorico->rh143_valor);
      $oEventoFinanceiro->setQuantidade($oHistorico->rh143_quantidade);
      $oEventoFinanceiro->setNatureza($oHistorico->rh143_tipoevento);
      $aEventosFinanceiros[] = $oEventoFinanceiro;
    }

    return $aEventosFinanceiros;
  }

  /**
   * Retorna a Folha de Pagamento a partir dos dados informados.
   * @param  DBCompetencia $oCompetencia
   * @param  Integer       $iTipoFolha  
   * @param  Integer       $iCodigo     
   * @return FolhaPagamento[]                    
   */
  public static function getFolhaCompetenciaTipo(DBCompetencia $oCompetencia, $iTipoFolha,  $iCodigo = null) {

    $iInstituicao = db_getsession("DB_instit");

    $oDaoFolhaPagamento = new cl_rhfolhapagamento();
    $sWhere             = "     rh141_anousu    = {$oCompetencia->getAno()}";
    $sWhere            .= " and rh141_mesusu    = {$oCompetencia->getMes()}"; 
    $sWhere            .= " and rh141_tipofolha = {$iTipoFolha}"; 
    $sWhere            .= " and rh141_instit    = {$iInstituicao}"; 
   
    if ( !is_null($iCodigo) && $iCodigo != 0 ) {
      $sWhere .= " and rh141_codigo = {$iCodigo} ";
    }

    $sSqlFolhaPagamento = $oDaoFolhaPagamento->sql_query_file(null, "rh141_sequencial", null, $sWhere);
    $rsFolhaPagamento   = db_query($sSqlFolhaPagamento);
    
     if ($oDaoFolhaPagamento->erro_status == "0") {
       throw new DBException(_M(self::MENSAGENS . "erro_buscar_folhapagamento"));
     }

     if (pg_num_rows($rsFolhaPagamento) == 0) {
       return false;
     }

     $aFolhasPagamento = array();

     for ($iFolhaPagamento = 0; $iFolhaPagamento < pg_num_rows($rsFolhaPagamento); $iFolhaPagamento++){

       $iSequencialFolha = db_utils::fieldsMemory($rsFolhaPagamento, $iFolhaPagamento)->rh141_sequencial;
       /**
        * @TODO Modificar para utilização de factory FolhaPagamentoFactory::construirPeloCodigo
        */
       switch ($iTipoFolha) {

        case FolhaPagamento::TIPO_FOLHA_SALARIO:
          $aFolhasPagamento[] =  new FolhaPagamentoSalario($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
          $aFolhasPagamento[] =  new FolhaPagamentoComplementar($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
          $aFolhasPagamento[] =  new FolhaPagamentoSuplementar($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
          $aFolhasPagamento[] =  new FolhaPagamentoAdiantamento($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_RESCISAO:
          $aFolhasPagamento[] =  new FolhaPagamentoRescisao($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
          $aFolhasPagamento[] =  new FolhaPagamento13o($iSequencialFolha);
          break;
       } 
     }

     return $aFolhasPagamento;
  }
  
  /**
   * Retorna as folhas de pagamento do servidor
   * 
   * @param Servidor $oServidor
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @param Integer $iTipoFolha
   * @param Integer $lAberta
   * @return FolhaPagamento[]
   * @throws DBException
   */
  public static function getFolhaServidor(Servidor $oServidor, 
                                          DBCompetencia $oCompetencia = null,
                                          Instituicao $oInstituicao = null,
                                          $iTipoFolha = null,
                                          $lAberta = null) {
    
    $sCampos = "distinct rh141_sequencial, rh141_tipofolha"; 
    $sWhere  = "rh143_regist = {$oServidor->getMatricula()} ";
    
    if (!empty($oCompetencia)) {
      
      $sWhere .= "and rh141_anousu = {$oCompetencia->getAno()} ";
      $sWhere .= "and rh141_mesusu = {$oCompetencia->getMes()} ";
    }
    
    if (!empty($oInstituicao)) {   
      $sWhere .= "and rh141_instit = {$oInstituicao->getCodigo()} ";
    }
    
    if (!empty($iTipoFolha)) {
      $sWhere .= "and rh142_sequencial = {$iTipoFolha} ";
    }
    
    if (!is_null($lAberta)) {
      
      $sWhere .= "and rh141_aberto = ";
      $sWhere .= $lAberta ? 'true' : 'false';
    }

    $oDaoHistoricoCalculo  = new cl_rhhistoricocalculo();
    $sSqlFolhasEncontradas = $oDaoHistoricoCalculo->sql_query(null, $sCampos, null, $sWhere);
    $rsFolhasEncontradas   = $oDaoHistoricoCalculo->sql_record($sSqlFolhasEncontradas);

    if (!empty($oDaoHistoricoCalculo->erro_banco)) {
      throw new DBException(_M(self::MENSAGENS . "erro_retornar_folha_servidor"));
    }
    
    $aFolhasPagamento = array();
    for ($i = 0; $i < $oDaoHistoricoCalculo->numrows; $i++){

      $oDadosFolha      = db_utils::fieldsMemory($rsFolhasEncontradas, $i);    
      $iSequencialFolha = $oDadosFolha->rh141_sequencial;
      $iTipoFolha       = $oDadosFolha->rh141_tipofolha;
       /**
        * @TODO Modificar para utilização de factory FolhaPagamentoFactory::construirPeloCodigo
        */
      switch ($iTipoFolha) {

        case FolhaPagamento::TIPO_FOLHA_SALARIO:
          $aFolhasPagamento[] =  new FolhaPagamentoSalario($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
          $aFolhasPagamento[] =  new FolhaPagamentoComplementar($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
          $aFolhasPagamento[] =  new FolhaPagamentoSuplementar($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
          $aFolhasPagamento[] =  new FolhaPagamentoAdiantamento($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_RESCISAO:
          $aFolhasPagamento[] =  new FolhaPagamentoRescisao($iSequencialFolha);
          break;
        case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
          $aFolhasPagamento[] =  new FolhaPagamento13o($iSequencialFolha);
          break;
      }
    }
    
    return $aFolhasPagamento;
  }
  
  /**
   * Verifica se existe alguma rubrica no cálculo da folha de pagamento
   * 
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @param String[] $aRubricas
   * @return Boolean
   * @throws BusinessException
   */
  public static function hasRubricasFolha(FolhaPagamento $oFolhaPagamento, $aRubricas) {
    
    $iSequencial = $oFolhaPagamento->getSequencial();
    if (empty($iSequencial)) {
      throw new BusinessException(_M(self::MENSAGENS . "erro_instanciar_objeto"));
    }
    
    $sConjuntoRubricas = implode("','", $aRubricas);
        
    $sWhere  = "rh141_sequencial = {$iSequencial} ";
    $sWhere .= "and rh141_instit = {$oFolhaPagamento->getInstituicao()->getCodigo()} ";
    $sWhere .= "and rh141_anousu = {$oFolhaPagamento->getCompetencia()->getAno()} ";
    $sWhere .= "and rh141_mesusu = {$oFolhaPagamento->getCompetencia()->getMes()} ";
    $sWhere .= "and rh143_rubrica in ('{$sConjuntoRubricas}') ";
    
    $oDaoHistoricoCalculo    = new cl_rhhistoricocalculo();
    $sSqlRubricasEncontradas = $oDaoHistoricoCalculo->sql_query(null, "rh143_sequencial", null, $sWhere);
    $rsRubricasEncontradas   = $oDaoHistoricoCalculo->sql_record($sSqlRubricasEncontradas);
    
    if (!$rsRubricasEncontradas || $oDaoHistoricoCalculo->numrows == "0") {
      return false;
    }
    
    return true;
  }
  
  /**
   * Retorna a sigla do tipo da folha.
   * 
   * @access public
   * @return String
   */
  public function getSigla() {
    
    $sSigla = "";
    
    switch ($this->iTipoFolha) {
      
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
        $sSigla = 'r14';
      break;
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        $sSigla = 'sup';
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        $sSigla = 'r20';
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        $sSigla = 'r48';
      break;
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        $sSigla = 'r22';
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        $sSigla = 'r35';
      break;
    }
    
    return $sSigla;
  }

  public function getSiglaPonto() {
    
    $sSigla = "";
    
    switch ($this->iTipoFolha) {
      
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
      case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
        $sSigla = 'r10';
      break;
      case FolhaPagamento::TIPO_FOLHA_RESCISAO:
        $sSigla = 'r19';
      break;
      case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
        $sSigla = 'r47';
      break;
      case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
        $sSigla = 'r21';
      break;
      case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
        $sSigla = 'r34';
      break;
    }
    
    return $sSigla;
  }
  
  
  /**
   * Retorna o tipo de folha conforme a sigla.
   * 
   * @static
   * @access public
   * @param String $sSigla
   * @return Integer
   */
  public static function getTipoFolhaBySigla($sSigla) {
    
    $iTipoFolha = "";
    
    switch ($sSigla) {
      
      case 'r14':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SALARIO;
        break;
      
      case 'r48':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
        break;
      
      case 'r35':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_13o_SALARIO;
        break;
      
      case 'r20':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_RESCISAO;
        break;
      
      case 'r22':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO;
        break;
      
      case 'sup':
        $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
        break;
    }
    
    return $iTipoFolha;
  }

  /**
   * Migra estrutura de tabelas para nova estrutura com a suplementar
   *
   * @static
   * @access public
   * @param Integer $iInstituicao
   * @return String
   */
  public static function migraEstruturaSuplementar($iInstituicao){

    $oDaoGerfSal       = new cl_gerfsal; 
    $oDaoGerfRes       = new cl_gerfres;
    $oDaoGerfCom       = new cl_gerfcom;
    $oDaoGerfS13       = new cl_gerfs13;
    $oDaoGerfAdi       = new cl_gerfadi;

    $aSqlMigraEstrutura[self::TIPO_FOLHA_SALARIO]      = $oDaoGerfSal->migraGerfSal($iInstituicao);
    $aSqlMigraEstrutura[self::TIPO_FOLHA_RESCISAO]     = $oDaoGerfRes->migraGerfRes($iInstituicao);
    $aSqlMigraEstrutura[self::TIPO_FOLHA_13o_SALARIO]  = $oDaoGerfS13->migraGerfS13($iInstituicao);
    $aSqlMigraEstrutura[self::TIPO_FOLHA_ADIANTAMENTO] = $oDaoGerfAdi->migraGerfAdi($iInstituicao);
    $aSqlMigraEstrutura[self::TIPO_FOLHA_COMPLEMENTAR] = $oDaoGerfCom->migraGerfCom($iInstituicao);

    $sSqlEstruturaTemporaria  = "DROP TABLE IF EXISTS w_migracao_rhfolhapagamento CASCADE;     ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_ultimafolhadecadacompetencia CASCADE;  ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_migracao_rhfolhapagamento_salario;     ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_ultimafolhadecadacompetencia_salario;  ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_migracao_adiantamento;                 ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_migracao_rescisao;                     ";
    $sSqlEstruturaTemporaria .= "DROP TABLE IF EXISTS w_migracao_13salario;                    ";
    
    $sSqlEstruturaTemporaria .= "DELETE from pontofs where r10_rubric = '';                    ";

    $iContadorMigracaoTipoFolha = 0;
    db_atutermometro(0,count($aSqlMigraEstrutura)+2,'termometro_migracao',1);
    $mRetornoMigraEstruturaTemporaria = FolhaPagamento::migraEstruturaSuplementarExecutaSql($sSqlEstruturaTemporaria);

    foreach ($aSqlMigraEstrutura as $iTipoFolha => $sSql) {

      $iContadorMigracaoTipoFolha++; 
      db_atutermometro($iContadorMigracaoTipoFolha,count($aSqlMigraEstrutura)+2,'termometro_migracao',1);
      $mRetornoMigraSuplementarTipoFolha = FolhaPagamento::migraEstruturaSuplementarExecutaSql($sSql);

      switch ($iTipoFolha) {
        case self::TIPO_FOLHA_SALARIO:
          $mRetornoMigraSalario      = $mRetornoMigraSuplementarTipoFolha;
          break;
        case self::TIPO_FOLHA_RESCISAO:
          $mRetornoMigraRescisao     = $mRetornoMigraSuplementarTipoFolha;
          break;
        case self::TIPO_FOLHA_13o_SALARIO:
          $mRetornoMigra13Salario    = $mRetornoMigraSuplementarTipoFolha;
          break;
        case self::TIPO_FOLHA_ADIANTAMENTO:
          $mRetornoMigraAdiantamento = $mRetornoMigraSuplementarTipoFolha;
          break;
        case self::TIPO_FOLHA_COMPLEMENTAR:
          $mRetornoMigraComplementar = $mRetornoMigraSuplementarTipoFolha;
          break;
      }
    }

    $sSqlEstruturaTemporaria .= "update rhfolhapagamento set rh141_aberto     = true                                                                                          ";
    $sSqlEstruturaTemporaria .= "                      where rh141_sequencial = (select max(rh141_sequencial) from rhfolhapagamento                                           "; 
    $sSqlEstruturaTemporaria .= "                                                where rh141_tipofolha = 1 and rh141_instit = {$iInstituicao});                              ";
    $sSqlEstruturaTemporaria .= "UPDATE db_itensmenu SET libcliente = 'true' WHERE id_item IN (9958, 9959, 9960, 9961, 9962, 9963, 9964, 9965, 9972, 9973, 9974, 9975, 9976);";    

    db_atutermometro(count($aSqlMigraEstrutura)+1,count($aSqlMigraEstrutura)+2,'termometro_migracao',1);
    $mRetornoMigraUpdateFinal = FolhaPagamento::migraEstruturaSuplementarExecutaSql($sSqlEstruturaTemporaria);

    if ( !$mRetornoMigraEstruturaTemporaria ){

      return 'erro_migracao_estrutura_temporaria';

    } elseif ( !$mRetornoMigraSalario ){
      
      return 'erro_migracao_estrutura_salario';

    } elseif ( !$mRetornoMigraRescisao ) {
      
      return 'erro_migracao_estrutura_rescisao';

    } elseif ( !$mRetornoMigra13Salario ) {
      
      return 'erro_migracao_estrutura_13salario';

    } elseif ( !$mRetornoMigraAdiantamento ) {
      
      return 'erro_migracao_estrutura_adiantamento';

    } elseif ( !$mRetornoMigraComplementar ) {
      
      return 'erro_migracao_estrutura_complementar';

    } elseif ( !$mRetornoMigraUpdateFinal ) {
      
      return 'erro_migracao_estrutura_update_final';
    }

    return 'sucesso_migracao';
  }

  /**
   * Migra estrutura das tabelas de *** para nova estrutura com a suplementar
   *
   * @static
   * @access public
   * @param String  $sqlMigraEstruturaSuplementarTipo SQL a ser executado para a migração
   * @return Boolean
   */
  public static function migraEstruturaSuplementarExecutaSql($sSqlMigraEstruturaSuplementarTipo){

    $sSqlMigraEstruturaSuplementarTipo = str_replace("'", "\'", $sSqlMigraEstruturaSuplementarTipo);
    $rsMigraEstrutura                  = db_query("select fc_executa_ddl('". $sSqlMigraEstruturaSuplementarTipo ."');");
    $sResult                           = db_utils::fieldsMemory($rsMigraEstrutura, 0)->fc_executa_ddl;

    if ( $sResult === false || $sResult === 'f' || $sResult === 0 || $sResult === '0' ) {
      return false;
    }

    return true;
  }
  
  /**
   * Verifica se deve fazer a migração da estrutura da folha de pagamento com a suplementar.
   * 
   * @static
   * @access public
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @return Boolean
   * @throws DBException
   */
  public static function fazerMigracao(Instituicao $oInstituicao, DBCompetencia $oCompetencia) {
    
    $oDaoCfPess      = new cl_cfpess();
    $sSqlSuplementar = $oDaoCfPess->sql_query_suplementar($oInstituicao, $oCompetencia);
    $rsSuplementar   = db_query($sSqlSuplementar);
    
    $oDaoRhFolhaPagamento = new cl_rhfolhapagamento();
    $sSqlPesquisaGerfs    = $oDaoRhFolhaPagamento->sql_query_gerfs($oInstituicao, $oCompetencia);
    $rsPesquisaGerfs      = db_query($sSqlPesquisaGerfs);

    if (!$rsSuplementar) {
      throw new DBException();
    }
    if (!$rsPesquisaGerfs) {
      throw new DBException();
    }
    
    $oDadosSuplementar = db_utils::fieldsMemory($rsSuplementar, 0);
    $lSuplementar      = (bool)$oDadosSuplementar->r11_suplementar;
    $iFolhapagamento   = $oDadosSuplementar->rhfolhapagamento;
    
    if ($lSuplementar && $iFolhapagamento == 0 && pg_num_rows($rsPesquisaGerfs) == 0) {
      return true;
    } 
    
    return false;
  }

}

