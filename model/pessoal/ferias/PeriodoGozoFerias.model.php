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

require_once('model/pessoal/ferias/PeriodoAquisitivoFerias.model.php');

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
   * Periodo especifico inicial para realização do calculo do periodo aquisitivo
   * @var DBDate
   */
  public $oPeriodoEspecificoInicial;

  /**
   * Periodo especifico final para realização do calculo do perido aquisitivo
   * @var DBdate
   */
  public $oPeriodoEspecificoFinal;

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
   * Construtor
   * @param integer $iCodigoPeriodo
   * @return void
   */
  public function __construct($iCodigoPeriodo = null) {

    $oDaoFeriasPeriodo = db_utils::getDao('rhferiasperiodo');

    if ( isset($iCodigoPeriodo) ) {

      $sSqlFeriasPeriodo = $oDaoFeriasPeriodo->sql_query_file($iCodigoPeriodo);
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
      $this->setPeriodoInicial ( new DBDate($oFeriasPeriodo->rh110_datainicial) ) ;
      $this->setPeriodoFinal   ( new DBDate($oFeriasPeriodo->rh110_datafinal) );
      $this->setObservacao     ( $oFeriasPeriodo->rh110_observacao );
      $this->setAnoPagamento   ( $oFeriasPeriodo->rh110_anopagamento );
      $this->setMesPagamento   ( $oFeriasPeriodo->rh110_mespagamento );
      $this->setDiasAbono      ( $oFeriasPeriodo->rh110_diasabono );
      $this->setPagaTerco      ( $oFeriasPeriodo->rh110_pagaterco == 't');
      $this->setTipoPonto      ( $oFeriasPeriodo->rh110_tipoponto );

      if ( $oFeriasPeriodo->rh110_periodoespecificoinicial ) {
        $this->setPeriodoEspecificoInicial ( new DBDate($oFeriasPeriodo->rh110_periodoespecificoinicial) );
      }

      if ( $oFeriasPeriodo->rh110_periodoespecificofinal ) {
        $this->setPeriodoEspecificoFinal( new DBDate($oFeriasPeriodo->rh110_periodoespecificofinal) );
      }

      $this->setSituacao( $oFeriasPeriodo->rh110_situacao );
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
   * @param integer $iDiasAbonorh110_observacao
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
   * Define o periodo especifico inicial
   * @param DBDate $oPeriodoEspecificoInicial
   */
  public function setPeriodoEspecificoInicial(DBDate $oPeriodoEspecificoInicial) {
    $this->oPeriodoEspecificoInicial = $oPeriodoEspecificoInicial;
  }

  /**
   * Retorna o periodo especifico Inicial
   * @return DBDate $oPeriodoEspecificoInicial
   */
  public function getPeriodoEspecificoInicial() {
    return $this->oPeriodoEspecificoInicial;
  }

  /**
   * Define o periodo especifico final
   * @param DBDate $oPeriodoEspecificoFinal
   */
  public function setPeriodoEspecificoFinal(DBDate $oPeriodoEspecificoFinal) {
    $this->oPeriodoEspecificoFinal = $oPeriodoEspecificoFinal;
  }

  /**
   * Retorna o periodo especifico final
   * @return DBDate $oPeriodoEspecificoFinal
   */
  public function getPeriodoEspecificoFinal() {
    return $this->oPeriodoEspecificoFinal;
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
    $oDaoFeriasPeriodo = db_utils::getDao('rhferiasperiodo');

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

    if ( !empty($this->oPeriodoEspecificoInicial) ) {
      $oDaoFeriasPeriodo->rh110_periodoespecificoinicial = $this->getPeriodoEspecificoInicial()->getDate();
    }

    if ( !empty($this->oPeriodoEspecificoFinal) ) {
      $oDaoFeriasPeriodo->rh110_periodoespecificofinal = $this->getPeriodoEspecificoFinal()->getDate();
    }

    $oDaoFeriasPeriodo->rh110_situacao = "{$this->getSituacao()}";

    /**
     * Salvar
     * não está definido o código então inclui
     */
    if ( !isset($this->iCodigoPeriodo) ) {

      /**
       * Verifica se existe outro periodo intercalando  
       */
      if (PeriodoGozoFerias::existePeriodoGozo($this->getCodigoFerias(), $this->getPeriodoInicial(), $this->getPeriodoFinal())) {
        throw new BusinessException( _M(PeriodoGozoFerias::MENSAGENS . 'periodo_gozo_existente') );
      }
      
      $oDaoFeriasPeriodo->incluir(null);

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
   * @return boolean
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
   * @return void
   */
  public function getPeriodoAquisitivo() {

    if ( !empty( $this->iCodigoFerias ) ) {
      return new PeriodoAquisitivoFerias($this->iCodigoFerias);
    }

    return false;
  }

  /**
   * Retorna o Ultimo periodo de gozo cadastrado para o servidor informado como parametro.
   * @param  Servidor $oServidor Instância de Servidor que se deseja obter o ultimo periodo de gozo
   * @return PeriodoGozoFerias Instância de PeriodoGozoFerias
   */
  public static function getUltimoPeriodoGozo( Servidor $oServidor) {

    $oDaoRhFeriasPeriodo = db_utils::getDao('rhferiasperiodo');
    $sSqlRhFeriasPeriodo = $oDaoRhFeriasPeriodo->sql_query(
                                                          null, 
                                                          'rh110_sequencial', 
                                                          'rh110_sequencial DESC', 
                                                          "rh109_regist = {$oServidor->getMatricula()}"
                                                        );

    $rsRhFeriasPeriodo = db_query($sSqlRhFeriasPeriodo);

    if (pg_num_rows($rsRhFeriasPeriodo) == "0") {
        throw new BusinessException(_M(
          PeriodoGozoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
          (object) array('sErroBanco' => $oDaoRhFeriasPeriodo->erro_banco)
        ));
    }

    $oDadosPeriodoGozo = db_utils::fieldsMemory($rsRhFeriasPeriodo, 0);

    /**
     * Cria uma instância de PeriodoAquisitivoFerias para o periodo aquisitivo disponível
     */
    $oPeriodoGozoFerias = new PeriodoGozoFerias($oDadosPeriodoGozo->rh110_sequencial);

    return $oPeriodoGozoFerias;
  }

  /**
  * Verifica se existe algum período de gozo que intersecta com as datas informadas.
  * @param integer $iPeriodoAquisitivo id do periodo aquisitivo
  * @param string $sDataInicial data inicial
  * @param string $sDataFinal data final
  */
  public static function existePeriodoGozo($iPeriodoAquisitivo, DBDate $oDataInicial, DBDate $oDataFinal) {

    $oDaoRhFerias = db_utils::getDao('rhferias');

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
     * No caso de o periodo especifico de cálculo seja informado.
     * 
     * @TODO - Verificar se o "periodo espeficico" realmente pertence a classe  "PeriodoGozoFerias", pois pode se encaixar como 
     *         atributo da classe "PeriodoAquisitivoFerias"
     */
    if ( $this->getPeriodoEspecificoInicial() != null && $this->getPeriodoEspecificoFinal() != null ) {
    
      $oDataInicial = $this->getPeriodoEspecificoInicial();
      $oDataFinal   = $this->getPeriodoEspecificoFinal();
    }
    
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
      $oFolhaSalario        = $oServidorCompetencia->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      
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
}