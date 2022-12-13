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

require_once(modification('model/pessoal/ferias/PeriodoAquisitivoFerias.model.php'));

/**
 * Model para cadastro dos periodos de ferias
 * @author  Renan Melo <renan@dbseller.com.bt>
 */
class PeriodoGozoFerias {

  /**
   * caminho do arquivo JSON das mensagens do model 
   */
  const MENSAGENS = 'recursoshumanos.pessoal.PeriodoGozoFerias.'; 
  
  /**
   * pagamento no ponto salario
   */
  const PAGAMENTO_PONTO_SALARIO = 1;

  /**
   * pagamento no ponto complementar
   */
  const PAGAMENTO_PONTO_COMPLEMENTAR = 2;

  /**
   * Periodo do gozo foi cadastrado, agendado
   */
  const SITUACAO_AGENDADO = 0;

  /**
   * Periodo foi processado, gerou ponto 
   */
  const SITUACAO_GERADO_PONTO = 1;

  /**
   * Periodo de gozo já calculado
   */
  const SITUACAO_CALCULADO = 2;

  const SITUACAO_CALCULADO_PREVIDENCIA = 3;

  /**
   * processamento do pagamento de 1/3 do salario
   */
  const PROCESSAMENTO_PAGAMENTO_13 = 1;

  /**
   * Processamento do periodo de Gozo
   */
  const PROCESSAMENTO_GOZO = 2;

  /**
   * Código do periodo de Gozo
   * @var integer
   */
  public $iCodigoPeriodo;

  /**
   * Código do Periodo Aquisitivo
   * @var integer
   */
  public $iCodigoFerias;

  /**
   * Dias que o servidor vai gozar as Férias
   * @var integer
   */
  public $iDiasGozo;
  
  /**
   * Periodo inicial de gozo
   * @var DBDAte
   */
  public $oPeriodoInicial;

  /**
   * Periodo final de gozo
   * @var DBDate
   */
  public $oPeriodoFinal;

  /**
   * Observação
   * @var string
   */
  public $sObservacao;

  /**
   * Ano de pagamento
   * @var integer
   */
  public $iAnoPagamento;

  /**
   * Mês de pagamento
   * @var integer
   */
  public $iMesPagamento;

  /**
   * Dia de abono
   * @var integer
   */
  public $iDiasAbono;

  /**
   * Paga 1/3 do salário
   * @var boolean
   */
  public $lPagaTerco;

  /**
   * Situação atual do respectivo periodo.
   * @var integer
   */
  public $iSituacao;
  
  /**
   * Tipo de pagamento das férias
   * 1 - Salário
   * 2 - Complementar
   * @var integer
   */
  public $iTipoPonto;

  /**
   * O periodo é considerado o primeiro periodo de gozo
   * @var bool
   */
  private $lPrimeiroPeriodo = false;


  /**
   * Matricula do servidor
   * @var integer
   */
  private $iMatricula;
 

  /**
   * Construtor
   *
   * @param integer $iCodigoPeriodo
   * @throws BusinessException
   */
  public function __construct($iCodigoPeriodo = null) {

    $oDaoFeriasPeriodo = new cl_rhferiasperiodo;

    if ( isset($iCodigoPeriodo) ) {

      $sConsultaPrimeiroPeriodo = "(select min(rh110_sequencial) from rhferiasperiodo as m where m.rh110_rhferias = rhferiasperiodo. rh110_rhferias) as primeiro_periodo";
      $sSqlFeriasPeriodo = $oDaoFeriasPeriodo->sql_query_file($iCodigoPeriodo, "*, {$sConsultaPrimeiroPeriodo}");
      $rsFeriasPeriodo   = $oDaoFeriasPeriodo->sql_record($sSqlFeriasPeriodo);

      if ($oDaoFeriasPeriodo->numrows == "0") {

        throw new BusinessException(_M(
          PeriodoGozoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
          (object) array('sErroBanco' => $oDaoFeriasPeriodo->erro_banco)
        ));
      }

      $this->setCodigoPeriodo($iCodigoPeriodo);

      $oFeriasPeriodo = db_utils::fieldsMemory($rsFeriasPeriodo, 0, true);

      $this->setCodigoFerias   ( $oFeriasPeriodo->rh110_rhferias );
      $this->setDiasGozo       ( $oFeriasPeriodo->rh110_dias );
      if (!empty($oFeriasPeriodo->rh110_datainicial)) {
        $this->setPeriodoInicial(new DBDate($oFeriasPeriodo->rh110_datainicial));
      }
      if (!empty($oFeriasPeriodo->rh110_datafinal)) {
        $this->setPeriodoFinal(new DBDate($oFeriasPeriodo->rh110_datafinal));
      }
      $this->setObservacao     ( $oFeriasPeriodo->rh110_observacao );
      $this->setAnoPagamento   ( $oFeriasPeriodo->rh110_anopagamento );
      $this->setMesPagamento   ( $oFeriasPeriodo->rh110_mespagamento );
      $this->setDiasAbono      ( $oFeriasPeriodo->rh110_diasabono );
      $this->setPagaTerco      ( $oFeriasPeriodo->rh110_pagaterco == 't');
      $this->setTipoPonto      ( $oFeriasPeriodo->rh110_tipoponto );
      $this->setSituacao( $oFeriasPeriodo->rh110_situacao );
      $this->lPrimeiroPeriodo = $oFeriasPeriodo->primeiro_periodo == $oFeriasPeriodo->rh110_sequencial;
    }
  }

  /**
   * Retorna o código do periodo
   * @return integer
   */
  public function getCodigoPeriodo() {
    return $this->iCodigoPeriodo;
  }

  /**
   * Define o código do periodo
   * @param integer $iCodigoPeriodo
   */
  public function setCodigoPeriodo($iCodigoPeriodo) {
    $this->iCodigoPeriodo = $iCodigoPeriodo;
  }

  /**
   * Retorna o código da tabela rhferias
   * @return integer
   */
  public function getCodigoFerias() {
    return $this->iCodigoFerias;
  }

  /**
   * Define o código da tabela rhferias
   * @param integer $iCodigoFerias
   */
  public function setCodigoFerias($iCodigoFerias) {
    $this->iCodigoFerias = $iCodigoFerias;
  }

  /**
   * Retorna quantidade de dias de gozo
   * @return integer
   */
  public function getDiasGozo() {
    return $this->iDiasGozo;
  }

  /**
   * Define quantidade de dias de gozo
   * @param integer $iDiasGozo
   */
  public function setDiasGozo($iDiasGozo) {
    $this->iDiasGozo = $iDiasGozo;
  }

  /**
   * Retorna o periodo iniciali
   * @return DBDate
   */
  public function getPeriodoInicial() {
    return $this->oPeriodoInicial;
  }

  /**
   * Define o periodo inicial
   * @param DBDate $dPeriodoInicial
   */
  public function setPeriodoInicial($oPeriodoInicial) {
    $this->oPeriodoInicial = $oPeriodoInicial;
  }

  /**
   * Retorna o periodo final
   * @return DBDate
   */
  public function getPeriodoFinal() {
    return $this->oPeriodoFinal;
  }

  /**
   * Define o periodo final
   * @param DBDate $oPeriodoFinal
   */
  public function setPeriodoFinal($oPeriodoFinal) {
    $this->oPeriodoFinal = $oPeriodoFinal;
  }

  /**
   * Retorna a observação
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observação
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o ano de pagamento
   * @return integer
   */
  public function getAnoPagamento() {
    return $this->iAnoPagamento;
  }

  /**
   * Define o ano de pagemento
   * @param integer $iAnoPagamento
   */
  public function setAnoPagamento($iAnoPagamento) {
    $this->iAnoPagamento = $iAnoPagamento;
  }

  /**
   * Retorna o mês de pagamento
   * @return integer
   */
  public function getMesPagamento() {
    return $this->iMesPagamento;
  }

  /**
   * Define o mês de pagamento
   * @param integer $iMesPagamento
   */
  public function setMesPagamento($iMesPagamento) {
    $this->iMesPagamento = $iMesPagamento;
  }

  /**
   * Retorna os dias de abono do gozo
   * @return integer
   */
  public function getDiasAbono() {
    return $this->iDiasAbono;
  }

  /**
   * Define os dias de abono do gozo
   *
   * @param $iDiasAbono
   * @internal param int $iDiasAbonorh110_observacao
   */
  public function setDiasAbono($iDiasAbono) {
    $this->iDiasAbono = $iDiasAbono;
  }

  /**
   * Retorna verdadeiro caso seja pago 1/3 do salário
   * @return boolean
   */
  public function isPagaTerco() {
    return $this->lPagaTerco;
  }

  /**
   * Retorna verdadeiro caso seja pago 1/3 do salário
   * @return boolean
   */
  public function tercoFeriasJaPago() {
    return $this->lPagaTerco;
  }
  
  /**
   * Define se será pago 1/3 do salário
   * @param boolean $lPagaTerco
   */
  public function setPagaTerco($lPagaTerco) {
    $this->lPagaTerco = $lPagaTerco;  
  }
  
  /**
   * Retorna o tipo de pagamento do ponto
   * 1 - Salário
   * 2 - Complementar
   * @return integer 
   */
  public function getTipoPonto() {
    return $this->iTipoPonto;
  }

  /**
   * Define a situação do periodo.
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna a situação
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }
  
  /**
  * Define o tipo de pagamento do ponto
  * 1 - Salário
  * 2 - Complementar
  * @param integer $iTipoPonto
  */
  public function setTipoPonto($iTipoPonto) {
    $this->iTipoPonto = $iTipoPonto;
  }
  
  /**
  * Adiciona matricula do servidor
  * @param integer $iMatricula
  */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Retorna a Matricula
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Salvar
   *
   * @throws Exception 1 sem transação ativa
   * @throws Exception 2/3 Erro de sql incluir()/alterar()
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(PeriodoGozoFerias::MENSAGENS . 'nenhuma_transacao_banco'));
    }
  
    /**
     * Nova instância de rhferiasperidos
     */
    $oDaoFeriasPeriodo = new cl_rhferiasperiodo;

    /**
     * Define as propriedades necessárias para realizar inclusão/alteração na tabela rhferiasperiodo
     */
    $oDaoFeriasPeriodo->rh110_rhferias     = $this->getCodigoFerias();
    $oDaoFeriasPeriodo->rh110_dias         = $this->getDiasGozo();
    $oDaoFeriasPeriodo->rh110_datainicial  = $this->getPeriodoInicial()->getDate();
    $oDaoFeriasPeriodo->rh110_datafinal    = $this->getPeriodoFinal()->getDate();
    $oDaoFeriasPeriodo->rh110_observacao   = $this->getObservacao();
    $oDaoFeriasPeriodo->rh110_anopagamento = $this->getAnoPagamento();
    $oDaoFeriasPeriodo->rh110_mespagamento = $this->getMesPagamento();
    $oDaoFeriasPeriodo->rh110_diasabono    = $this->getDiasAbono();
    $oDaoFeriasPeriodo->rh110_pagaterco    = $this->isPagaTerco() ? 'true' : 'false';
    $oDaoFeriasPeriodo->rh110_tipoponto    = $this->getTipoPonto();

    $oDaoFeriasPeriodo->rh110_situacao = "{$this->getSituacao()}";


    /**
     * Salvar
     * não está definido o código então inclui
     */
    if (!isset($this->iCodigoPeriodo)) {

      /**
       * Verifica se existe outro periodo intercalando  
       */

      $existPeriod = PeriodoGozoFerias::existePeriodoGozo($this->getCodigoFerias(),
                                        $this->getPeriodoInicial(),
                                        $this-> getPeriodoFinal());
      if ($existPeriod) {
        throw new BusinessException( _M(PeriodoGozoFerias::MENSAGENS . 'periodo_gozo_existente') );
      }

      $existIqualEnjoymentForDiffPeriod = PeriodoGozoFerias::existEnjoymentForDistinctPeriodAcquisitive(
                                                $this->getMatricula(),
                                                $this->getPeriodoInicial(),
                                                $this->getPeriodoFinal());
        
      if ($existIqualEnjoymentForDiffPeriod) {
         throw new BusinessException( _M(PeriodoGozoFerias::MENSAGENS . 'periodo_gozo_igual_outro_periodo_aquisitivo',
          (object) array('sErroBanco' => "(". $this->getPeriodoInicial() . " - " . $this->getPeriodoFinal() . ")" )) );
      }

      
      $missVocation = self::isValidPeriod($this->getCodigoFerias());

      if ($missVocation) {
         throw new BusinessException( _M(PeriodoGozoFerias::MENSAGENS . 'perdeu_periodo_aquisitivo_ferias'), 30);    
      }
    
      $oDaoFeriasPeriodo->incluir(null);
      $this->setCodigoPeriodo($oDaoFeriasPeriodo->rh110_sequencial);
      if ($oDaoFeriasPeriodo->erro_status == "0") {

        throw new DBException(
          _M(PeriodoGozoFerias::MENSAGENS . 'erro_incluir_periodo', 
          (object) array('sErroBanco' => $oDaoFeriasPeriodo->erro_msg))
        );
      }

      return $oDaoFeriasPeriodo->erro_msg;

    } else {

      /**
       * Está definido o código então altera
       */
      $oDaoFeriasPeriodo->rh110_sequencial = $this->getCodigoPeriodo();

      $oDaoFeriasPeriodo->alterar( $this->getCodigoFerias() );

      if ($oDaoFeriasPeriodo->erro_status == "0") {
        throw new DBException(
          _M(PeriodoGozoFerias::MENSAGENS . 'erro_alterar_periodo',
          (object) array('sErroBanco' => $oDaoFeriasPeriodo->erro_msg))
        );
      }
    }

    return true;
  }

  /**
   * Gera o cálculo para o ponto de férias
   * @return void
   */
  public function gerar() {

    $oPeriodoAquisitivo = new PeriodoAquisitivoFerias($this->getCodigoFerias());
    $oPontoFerias       = new PontoFerias($oPeriodoAquisitivo->getServidor());

    $oPontoFerias->setPeriodoAquisitivoFerias($oPeriodoAquisitivo);
    $oPontoFerias->setPeriodoGozoFerias($this);
    $oPontoFerias->gerar(); 

    $this->setSituacao(PeriodoGozoFerias::SITUACAO_GERADO_PONTO);
    $this->salvar();
  }

  /**
   * Cancela o ponto de um periodo de férias
   * - remove composicao do ponto(rhferiasperiodopontofe)
   * - altera situacao do periodo para cadastrado
   *
   * @return void
   */
  public function cancelar() {

    $this->getPeriodoAquisitivo()
         ->getServidor()
         ->getPonto(Ponto::FERIAS)
         ->getComposicao()
         ->excluir( $this );
    $this->setSituacao(PeriodoGozoFerias::SITUACAO_AGENDADO);
    $this->salvar();
  }

  /**
   * Realiza a exclusão de um periodo
   *
   * @return bool
   * @throws DBException
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(PeriodoGozoFerias::MENSAGENS . 'nenhuma_transacao_banco'));
    }

    db_utils::getDao('rhferiasperiodo', 1);
    $oDaoFeriasPeriodo = new cl_rhferiasperiodo();
    $oDaoFeriasPeriodo->excluir($this->getCodigoPeriodo());

    if ($oDaoFeriasPeriodo->erro_status == "0") {

      $oMensagemErro = (object) array('sErroBanco' => $oDaoFeriasPeriodo->erro_banco);
      throw new DBException(_M(PeriodoGozoFerias::MENSAGENS . 'erro_excluir_periodo', $oMensagemErro));
    }

    return true;
  }

  /**
   * Retorna o periodo aquisitivo referente ao periodo de gozo
   *
   * @access public
   * @return PeriodoAquisitivoFerias|bool
   */
  public function getPeriodoAquisitivo() {

    if ( !empty( $this->iCodigoFerias ) ) {
      return new PeriodoAquisitivoFerias($this->iCodigoFerias);
    }

    return false;
  }

  /**
   * Verifica se nao teve perda de ferias durante a  inclusao
   *
   * @param $iCodigoFerias
   * @return boolean
   */
  public  static function isValidPeriod($iCodigoFerias) 
  { 
    $sSql  = "SELECT  1 FROM rhferias ";
    $sSql .= "WHERE  rh109_perdeudireitoferias = 'f' AND rh109_sequencial = $iCodigoFerias ;";
 
    $rsRhFerias = db_query($sSql);

    return pg_num_rows($rsRhFerias) == "0";
  } 


  /**
   * Retorna o Ultimo periodo de gozo cadastrado para o servidor informado como parametro.
   *
   * @param  Servidor $oServidor Instância de Servidor que se deseja obter o ultimo periodo de gozo
   * @return PeriodoGozoFerias Instância de PeriodoGozoFerias
   * @throws BusinessException
   */
  public static function getUltimoPeriodoGozo( Servidor $oServidor) {

    $oDaoRhFeriasPeriodo = db_utils::getDao('rhferiasperiodo');
    

    $sSqlRhFeriasPeriodo = $oDaoRhFeriasPeriodo->sql_query(
                                                          null, 
                                                          'rh110_sequencial', 
                                                          'rh110_sequencial DESC', 
                                                          "rh109_regist = {$oServidor->getMatricula()} ".
                                                          " AND rh109_perdeudireitoferias <> 't' "
                                                        );

    $rsRhFeriasPeriodo = db_query($sSqlRhFeriasPeriodo);

    if (!$rsRhFeriasPeriodo) {
        throw new BusinessException(_M(
          PeriodoGozoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
          (object) array('sErroBanco' => $oDaoRhFeriasPeriodo->erro_banco)
        ));
    }

    if (pg_num_rows($rsRhFeriasPeriodo) == "0") {
      throw new BusinessException("Servidor sem escala de férias cadastrada.");
    }

    $oDadosPeriodoGozo = db_utils::fieldsMemory($rsRhFeriasPeriodo, 0);

    /**
     * Cria uma instância de PeriodoAquisitivoFerias para o periodo aquisitivo disponível
     */
    $oPeriodoGozoFerias = new PeriodoGozoFerias($oDadosPeriodoGozo->rh110_sequencial);

    return $oPeriodoGozoFerias;
  }

  /**
   * Verifica se existe no período aquisitivo informado, um gozo que intersecta com as datas informadas.
   *
   * @param integer $iPeriodoAquisitivo id do periodo aquisitivo
   * @param DBDate  $oDataInicial data inicial
   * @param DBDate  $oDataFinal data final
   * @return bool
   * @throws DBException
   */
  public static function existePeriodoGozo($iPeriodoAquisitivo, DBDate $oDataInicial, DBDate $oDataFinal) {

    $sSql  = "select 1                                    ";
    $sSql .= "  from rhferiasperiodo                      ";
    $sSql .= " where ('".$oDataInicial->getDate()."'::date, '".$oDataFinal->getDate()."'::date) overlaps (rh110_datainicial, rh110_datafinal)";
    $sSql .= "   and rh110_rhferias = $iPeriodoAquisitivo ;";

    $rsRhFeriasPeriodo = db_query($sSql);

    if ( !$rsRhFeriasPeriodo ) {
      throw new DBException( _M(
        PeriodoGozoFerias::MENSAGENS . 'erro_executar_query_validacao_periodo_aquisitivo',
        (object) array( 'sErroBanco' => pg_last_error() )
      ));
    }
    
    if (pg_num_rows($rsRhFeriasPeriodo) == "0") {
      return false;
    }

    return true;
  }

  /**
   * Verifica se existe algum período de gozo em diferentes periodos aquisitivos que intersecta com as datas   informadas.
   *
   * @param integer $iRegistry  matricula do servidor
   * @param DBDate  $oDataInicial data inicial
   * @param DBDate  $oDataFinal data final
   * @return bool
   * @throws DBException
   */

  public static function existEnjoymentForDistinctPeriodAcquisitive($iRegistry, DBDate $oDateStart, DBDate $oDateEnd)
  {    
     
        $sDateStart = $oDateStart->getDate();  
        $sDateEnd   = $oDateEnd->getDate();  

        $sSql  = "SELECT 1";         
        $sSql .= " FROM rhferias";          
        $sSql .= " INNER JOIN  rhferiasperiodo";
        $sSql .= " ON rhferias.rh109_sequencial = rhferiasperiodo.rh110_rhferias" ;
        $sSql .= " WHERE  ('$sDateStart'::date, '$sDateEnd'::date)";
        $sSql .= " overlaps (rhferiasperiodo.rh110_datainicial, rhferiasperiodo.rh110_datafinal)";
        $sSql .= " AND rhferias.rh109_regist = $iRegistry";
        
      
        $rsRhFeriasPeriodo = db_query($sSql);

        if (!$rsRhFeriasPeriodo ) {
            throw new DBException( _M(
                PeriodoGozoFerias::MENSAGENS . 'erro_executar_query_validacao_periodo_aquisitivo',
                (object) array( 'sErroBanco' => pg_last_error() )
            ));
        }

        return (pg_num_rows($rsRhFeriasPeriodo) != "0");  
  }

  /**
   * Retorna Composição de gozo cadastrado para o servidor.
   * @access public
   * @return ComposicaoPontoFerias Instância de ComposicaoPontoFerias
   */
  public function getComposicao (){
  	
  	$oServidor = ServidorRepository::getInstanciaByCodigo( $this->getPeriodoAquisitivo()->getServidor()->getMatricula(), 
  			                                                   $this->getAnoPagamento(),
                                                           $this->getMesPagamento());

  	return new ComposicaoPontoFerias($oServidor);	
  } 
  
  /**
   * Busca as Rubricas dentro do "Periodo Base de Cálculo"¹
   * 
   * @return array
   */
  public function calcularMediaRubricas() {

    /**
     * Servidor vinculado ao periodo de Gozo, logo ao periodo aquisitivo.  
     */
    
    $oServidorPeriodo      = $this->getPeriodoAquisitivo()->getServidor();

    /**
     * Coleção de Rubricas que deverão ser calculadas
     */
    $aRubricasCalculo      = array();
    
    /**
     * Coleção de Cálculos das Rubricas
     */ 
    $aCalculoMediaRubricas = array();
    
    /**
     * Datas Base para Cálculo da Média das Rubricas
     */
    $oDataInicial          = $this->getPeriodoAquisitivo()->getDataInicial();
    $oDataFinal            = $this->getPeriodoAquisitivo()->getDataFinal();
    
    /**
     * Percorre as competencias dentro do periodo aquisitivo, buscando todas as Rubricas para que sejam calculadas.
     */
    foreach ( DBPessoal::getCompetenciasIntervalo( $oDataInicial, $oDataFinal ) as $oCompetencia ) {

      try {

        $oServidorCompetencia = ServidorRepository::getInstanciaByCodigo(
          $oServidorPeriodo->getMatricula(), 
          $oCompetencia->getAno(), 
          $oCompetencia->getMes()
        );
      } catch ( BusinessException $eErro ) {
        //caso não exitsta servidor na competencia.
        continue;
      }

      /**
       * Retorna as Rubricas encontradas no Cálculo de Salário
       */
      $oFolhaSalario   = $oServidorCompetencia->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      
      foreach ( $oFolhaSalario->getRubricas() as $oRubrica ) {
        $aRubricasCalculo[$oRubrica->getCodigo()] = $oRubrica;
      }
      
      
      /**
       * Retorna as Rubricas encontradas no Cálculo de Folhas Complementares
       */
      $oFolhaComplementar    = $oServidorCompetencia->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR);
      
      foreach ( $oFolhaComplementar->getRubricas() as $oRubrica ) {
        $aRubricasCalculo[$oRubrica->getCodigo()] = $oRubrica;
      } 
    }

    /**
     * Percorre as Rubricas encontradas nos cálculos de Salário e Complementar
     */
    foreach ( $aRubricasCalculo as $oRubrica ) {
      
      $oCalculo = new CalculoMediaRubrica( $oServidorPeriodo,
                                           $oRubrica, 
                                           $oDataInicial, 
                                           $oDataFinal, 
                                           CalculoMediaRubrica::TIPO_CALCULO_FERIAS );
      
      /**
       * Rubrica sem media
       */
      if ( $oRubrica->getMediaFerias() == CalculoMediaRubrica::SEM_MEDIA ) {
        continue;
      }
      
      /**
       * Caso não possa calcular 
       */
      if ( !$oCalculo->calcular() ) {
        continue;
      }
      
      $aCalculoMediaRubricas[] = $oCalculo;
    }

    return $aCalculoMediaRubricas;
  }
  
  /**
   * Retorna a quantidade de dias que devem ser pagos adiantamento
   */
  public function getDiasAdiantamento () {
    
    $iDiasGozo      = $this->getDiasGozo();
    $oDataFinal     = $this->getPeriodoFinal();
    
    /** 
     * Caso o gozo de férias seja dentro de um mes 
     * não haverá nenhum dia adiantado
     */
    if ( $this->getPeriodoInicial()->getMes() == $this->getPeriodoFinal()->getMes() ) {
      return 0;
    }
    
    /**
     * Caso o Mes inicial de gozo for dirferente do Mes de Pagamento, tudo será adiantado
     */
    if ( $this->getPeriodoInicial()->getMes() > $this->getMesPagamento() ) {
      return $this->getDiasGozo();
    }

    $oInicioMesAdiantado = new DBDate('01/'.$oDataFinal->getMes() .'/'. $oDataFinal->getAno() );//Primeiro dia de mes adiantado.
    $oFimMesAdiantado    = clone $oDataFinal;
    $oFimMesAdiantado->modificarIntervalo("+1 day");//Soma 1 dia no intervalo pois deve considerar o dia final
    $iDiasAdiantados     = DBDate::calculaIntervaloEntreDatas($oFimMesAdiantado, $oInicioMesAdiantado, "d");
    
  	return $iDiasAdiantados;
  }

  /**
   * @param Servidor $oServidor
   * @param DBDate   $oPeriodoInicial
   * @param DBDate   $oPeriodoFinal
   * @return PeriodoGozoFerias[]
   */
  public function getPeriodosGozo(Servidor $oServidor = null, DBDate $oPeriodoInicial = null, DBDate $oPeriodoFinal = null, $lFeriasLiberadasRH = false) {

    $aWhere = array();

    if ($oServidor) {
      $aWhere[] = "rh109_regist = {$oServidor->getMatricula()}";
    }
    
    if ($oPeriodoInicial) {
      $aWhere[] = "rh110_datainicial >= '{$oPeriodoInicial->getDate()}'";
    }

    if ($oPeriodoFinal) {
      $aWhere[] = "rh110_datainicial <= '{$oPeriodoFinal->getDate()}'";      
    }

    $sTipoValidacao = "";
    if (!$lFeriasLiberadasRH) {
      $sTipoValidacao = " not ";
    }
    $aWhere[] = "rh110_sequencial {$sTipoValidacao} in (select rh169_rhferiasperiodo from rhferiasperiodoassentamento)";
    $sWhere = implode(' and ', $aWhere);

    $oDaoRhFeriasPeriodo = db_utils::getDao('rhferiasperiodo');
    $sSqlRhFeriasPeriodo = $oDaoRhFeriasPeriodo->sql_query (null, 'rh110_sequencial', null, $sWhere);
    $rsRhFeriasPeriodo   = db_query($sSqlRhFeriasPeriodo);

    if (!$rsRhFeriasPeriodo) {
      new DBException('Ocorreu um erro ao buscar os períodos aquisitivos.');
    }

    $aPeriodos = array();

    for ($iPeriodo = 0; $iPeriodo < pg_num_rows($rsRhFeriasPeriodo); $iPeriodo++) {

      $oDadosPeriodoGozo = db_utils::fieldsMemory($rsRhFeriasPeriodo, $iPeriodo);
      $aPeriodos[]       = new PeriodoGozoFerias($oDadosPeriodoGozo->rh110_sequencial);
    }

    return $aPeriodos;
  }

  public function isPrimeiroPeriodo() {
    return $this->lPrimeiroPeriodo;
  }

  /**
   * Realizar o processameno dos dados Financeiros das férias
   *
   * @param $iTipoProcessamento
   * @throws BusinessException
   * @throws DBException
   * @throws Exception
   */
  public function  processarDadosFinanceiros($iTipoProcessamento) {

    if (!db_utils::inTransaction()) {
      throw new DBException("sem transação com o banco de dados");
    }
    switch ($iTipoProcessamento) {

      case self::PROCESSAMENTO_PAGAMENTO_13:
        $this->processarDadosFinanceirosUmTerco();
        break;

      case self::PROCESSAMENTO_GOZO:
        $this->processarGozoFerias();
        break;
    }
  }

  /**
   * Realizar a inclusão dos dados Financeiros
   */
  private function processarDadosFinanceirosUmTerco() {

    /**
     * @TODO Incluir cadferias, com o periodo aquisitivo de 30 dias, para pagamento na competencia atual,
     *       com pagamento na folha de salário, ajustando também os dias de abono
     */
    $oDataGozoPeriodoInicial = clone $this->getPeriodoInicial();
    // $oDataGozoPeriodoFinal   = clone $this->getPeriodoFinal();

    $oDataGozoPeriodoInicial = new DBDate("01/{$oDataGozoPeriodoInicial->getMes()}/{$oDataGozoPeriodoInicial->getAno()}");
    $oDataGozoPeriodoFinal   = clone $oDataGozoPeriodoInicial;
    $oDataGozoPeriodoFinal->modificarIntervalo('+29 days');

    $oDataGozoPeriodoInicial->modificarIntervalo('-1 month');
    $oDataGozoPeriodoFinal->modificarIntervalo('-1 month');

    $oPeriodoAquisitivo                     = $this->getPeriodoAquisitivo();
    $oServidor                              = $oPeriodoAquisitivo->getServidor();
    $oDaoCadFerias                          = new cl_cadferia();
    $oDaoCadFerias->r30_paga13              = true;
    $oDaoCadFerias->r30_abono               = $this->getDiasAbono();
    $oDaoCadFerias->r30_anousu              = DBPessoal::getAnoFolha();
    $oDaoCadFerias->r30_mesusu              = DBPessoal::getMesFolha();
    $oDaoCadFerias->r30_ndias               = 30;
    $oDaoCadFerias->r30_perai               = $oPeriodoAquisitivo->getDataInicial()->getDate();
    $oDaoCadFerias->r30_peraf               = $oPeriodoAquisitivo->getDataFinal()->getDate();
    $oDaoCadFerias->r30_periodolivreinicial = $oPeriodoAquisitivo->getDataInicial()->getDate();
    $oDaoCadFerias->r30_periodolivrefinal   = $oPeriodoAquisitivo->getDataFinal()->getDate();
    $oDaoCadFerias->r30_per1i               = $oDataGozoPeriodoInicial->getDate();
    $oDaoCadFerias->r30_per1f               = $oDataGozoPeriodoFinal->getDate();
    $oDaoCadFerias->r30_dias1               = 30;
    $oDaoCadFerias->r30_dias2               = "0";
    $oDaoCadFerias->r30_faltas              = "0";
    $oDaoCadFerias->r30_per2i               = "null";
    $oDaoCadFerias->r30_per2f               = "null";
    $oDaoCadFerias->r30_dias2               = "0";
    $oDaoCadFerias->r30_faltas              = "0";
    $oDaoCadFerias->r30_regist              = $oServidor->getMatricula();
    $oDaoCadFerias->r30_numcgm              = $oServidor->getCgm()->getCodigo();
    $oDaoCadFerias->r30_ponto               = "S";
    $oDaoCadFerias->r30_proc1               = DBPessoal::getAnoFolha()."/".DBPessoal::getMesFolha();
    $oDaoCadFerias->r30_proc2               = "";
    $oDaoCadFerias->r30_proc1d              = "0";
    $oDaoCadFerias->r30_proc2d              = "";
    $oDaoCadFerias->r30_vliq2d              = "0";
    $oDaoCadFerias->r30_paga13              = "true";
    $oDaoCadFerias->r30_psal1               = "true";
    $oDaoCadFerias->r30_tip1                = "01";
    $oDaoCadFerias->r30_tipoapuracaomedia   = 1;
    $oDaoCadFerias->incluir();

    if ($oDaoCadFerias->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados das ferias\n{$oDaoCadFerias->erro_msg}");
    }

    $nDiasGozo              = $this->getDiasGozo();
    $this->setDiasGozo(30);
    $oPontoFerias           = $oServidor->getPonto(Ponto::FERIAS);
    $oComposicaoPontoFerias = $oPontoFerias->getComposicao();
    $oComposicaoPontoFerias->adicionarPeriodoGozo($this);

    /**
     * Gera registros composicao do ponto(rhferiasperiodopontofe)
     */
    $oComposicaoPontoFerias->gerarRegistrosPonto();

    /**
     * Retorna a soma da composicao do ponto(rhferiasperiodopontofe)
     */
    $aRegistrosPonto = $oComposicaoPontoFerias->getRegistros();

    

    /**
     * Adiciona o total da composicao nos registros do ponto
     */
    foreach ($aRegistrosPonto as $oRegistroPontoFerias ) {
      $oPontoFerias->adicionarRegistro($oRegistroPontoFerias);
    }
    $oPontoFerias->gerar();


    $this->setSituacao(self::SITUACAO_GERADO_PONTO);
    $this->setAnoPagamento(DBPessoal::getAnoFolha());
    $this->setMesPagamento(DBPessoal::getMesFolha());
    $this->setPagaTerco(true);
    $this->setDiasGozo($nDiasGozo);
    $this->salvar();

  }

  /**
   * Inclui a rubrica com valor de previdencia para incrementar a base de previdencia no mes de gozo
   */
  private function processarGozoFerias() {


    $oPeriodoAquisitivo     = $this->getPeriodoAquisitivo();
    $oCompetenciaPagamento  = $oPeriodoAquisitivo->getCompetenciaPagamentoTerco();

    if (empty($oCompetenciaPagamento)) {
      /**
       * @TODO validar mensagens
       */
      throw new BusinessException("Terço de férias não processadas");
    }

    $this->setSituacao(self::SITUACAO_CALCULADO_PREVIDENCIA);
    $this->salvar();

    $oServidorCompetenciaPagamento = ServidorRepository::getInstanciaByCodigo($oPeriodoAquisitivo->getServidor()->getMatricula(),
                                                                              $oCompetenciaPagamento->getAno(),
                                                                              $oCompetenciaPagamento->getMes());
    $oCalculoCompetenciaPagamento  = $oServidorCompetenciaPagamento->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);

    /**
     * Validar a rubrica do 1/3 configurada no 1/3 Férias
     */
    $oParametrosFolha     = ParametrosPessoalRepository::getParametros($oCompetenciaPagamento, InstituicaoRepository::getInstituicaoSessao());

    if(! $oParametrosFolha->getRubricaEscalaFerias() instanceof Rubrica) {
      throw new BusinessException("Rubrica com o valor do 1/3 da escala de férias não configurada.");
    }

    if (!$oRubricaTercoFerias  = $oParametrosFolha->getRubricaTercoFerias()) {
      throw new BusinessException("Rubrica com o valor do 1/3 de férias não configurada.");
    }

    $aValorRubricaUmTerco = $oCalculoCompetenciaPagamento->getEventosFinanceiros(null, array($oRubricaTercoFerias->getCodigo()));

    if (count($aValorRubricaUmTerco) == 0) {
      return ;
    }

    $oRubrica             = $oParametrosFolha->getRubricaEscalaFerias();

    if(! $oRubrica instanceof Rubrica) {
      throw new BusinessException("Rubrica para informar o desconto de previdencia de férias não configurada.");
    }

    $nValorPrevidencia = round(($aValorRubricaUmTerco[0]->getValor() / 30) * $this->getDiasGozo(), 2);
    $oRegistroPonto    = new RegistroPonto();
    $oRegistroPonto->setServidor($oPeriodoAquisitivo->getServidor());
    $oRegistroPonto->setValor($nValorPrevidencia);
    $oRegistroPonto->setQuantidade($this->getDiasGozo());
    $oRegistroPonto->setRubrica($oRubrica);

    $oPonto = new PontoSalario($oPeriodoAquisitivo->getServidor());
    $oPonto->carregarRegistros();
    $oPonto->limpar();
    $oPonto->adicionarRegistro($oRegistroPonto);
    $oPonto->salvar();

  }

  /**
   * Verifica se existe Periodos de gozo ainda não processados na competência informada por parâmetro
   *
   * @param  DBCompetencia $oCompetencia
   * @return bool -true existe periodos não processados
   *                   -false não existem periiodos não processados
   * @throws DBException
   */
  public static function hasPeriodoNaoProcessado(DBCompetencia $oCompetencia){

    $oDaoRhFeriasPeriodo    = new cl_rhferiasperiodo();

    $sWhereRhFeriasPeriodo  = "    extract(year from rh110_datainicial) = {$oCompetencia->getAno()}  "; 
    $sWhereRhFeriasPeriodo .= "and extract(month from rh110_datainicial) = {$oCompetencia->getMes()} ";
    $sWhereRhFeriasPeriodo .= "and rh110_situacao = 0  or (rh110_situacao = 1 and rh110_pagaterco is true)  ";

    $sSqlRhFeriasPeriodo    = $oDaoRhFeriasPeriodo->sql_query_file(null, 'rh110_sequencial',null, $sWhereRhFeriasPeriodo);
    $rsRhFeriasPeriodo      = db_query($sSqlRhFeriasPeriodo);

    if (!$rsRhFeriasPeriodo) {
      throw new DBException("Ocorreu um erro ao buscar os periodos");
    }
    
    if (pg_num_rows($rsRhFeriasPeriodo) > 0) {  
      return true;
    }

    return false;
  }
}
