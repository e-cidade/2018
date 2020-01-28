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
   * Periodo de gozo j� calculado
   */
  const SITUACAO_CALCULADO = 2;

  /**
   * C�digo do periodo de Gozo
   * @var integer
   */
  public $iCodigoPeriodo;

  /**
   * C�digo do Periodo Aquisitivo
   * @var integer
   */
  public $iCodigoFerias;

  /**
   * Dias que o servidor vai gozar as F�rias
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
   * Observa��o
   * @var string
   */
  public $sObservacao;

  /**
   * Ano de pagamento
   * @var integer
   */
  public $iAnoPagamento;

  /**
   * M�s de pagamento
   * @var integer
   */
  public $iMesPagamento;

  /**
   * Dia de abono
   * @var integer
   */
  public $iDiasAbono;

  /**
   * Paga 1/3 do sal�rio
   * @var boolean
   */
  public $lPagaTerco;

  /**
   * Periodo especifico inicial para realiza��o do calculo do periodo aquisitivo
   * @var DBDate
   */
  public $oPeriodoEspecificoInicial;

  /**
   * Periodo especifico final para realiza��o do calculo do perido aquisitivo
   * @var DBdate
   */
  public $oPeriodoEspecificoFinal;

  /**
   * Situa��o atual do respectivo periodo.
   * @var integer
   */
  public $iSituacao;
  
  /**
   * Tipo de pagamento das f�rias
   * 1 - Sal�rio
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
   * Retorna o c�digo do periodo
   * @return integer
   */
  public function getCodigoPeriodo() {
    return $this->iCodigoPeriodo;
  }

  /**
   * Define o c�digo do periodo
   * @param integer $iCodigoPeriodo
   */
  public function setCodigoPeriodo($iCodigoPeriodo) {
    $this->iCodigoPeriodo = $iCodigoPeriodo;
  }

  /**
   * Retorna o c�digo da tabela rhferias
   * @return integer
   */
  public function getCodigoFerias() {
    return $this->iCodigoFerias;
  }

  /**
   * Define o c�digo da tabela rhferias
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
   * Retorna a observa��o
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observa��o
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
   * Retorna o m�s de pagamento
   * @return integer
   */
  public function getMesPagamento() {
    return $this->iMesPagamento;
  }

  /**
   * Define o m�s de pagamento
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
   * Retorna verdadeiro caso seja pago 1/3 do sal�rio
   * @return boolean
   */
  public function isPagaTerco() {
    return $this->lPagaTerco;
  }
  
  /**
   * Define se ser� pago 1/3 do sal�rio
   * @param boolean $lPagaTerco
   */
  public function setPagaTerco($lPagaTerco) {
    $this->lPagaTerco = $lPagaTerco;  
  }
  
  /**
   * Retorna o tipo de pagamento do ponto
   * 1 - Sal�rio
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
   * Define a situa��o do periodo.
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna a situa��o
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }
  
  /**
  * Define o tipo de pagamento do ponto
  * 1 - Sal�rio
  * 2 - Complementar
  * @param integer $iTipoPonto
  */
  public function setTipoPonto($iTipoPonto) {
    $this->iTipoPonto = $iTipoPonto;
  }
  
  /**
   * Salvar
   *
   * @throws Exception 1 sem transa��o ativa
   * @throws Exception 2/3 Erro de sql incluir()/alterar()
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(PeriodoGozoFerias::MENSAGENS . 'nenhuma_transacao_banco'));
    }
  
    /**
     * Nova inst�ncia de rhferiasperidos
     */
    $oDaoFeriasPeriodo = db_utils::getDao('rhferiasperiodo');

    /**
     * Define as propriedades necess�rias para realizar inclus�o/altera��o na tabela rhferiasperiodo
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
     * n�o est� definido o c�digo ent�o inclui
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
       * Est� definido o c�digo ent�o altera
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
   * Gera o c�lculo para o ponto de f�rias
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
   * Cancela o ponto de um periodo de f�rias
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
   * Realiza a exclus�o de um periodo
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
   * @param  Servidor $oServidor Inst�ncia de Servidor que se deseja obter o ultimo periodo de gozo
   * @return PeriodoGozoFerias Inst�ncia de PeriodoGozoFerias
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
     * Cria uma inst�ncia de PeriodoAquisitivoFerias para o periodo aquisitivo dispon�vel
     */
    $oPeriodoGozoFerias = new PeriodoGozoFerias($oDadosPeriodoGozo->rh110_sequencial);

    return $oPeriodoGozoFerias;
  }

  /**
  * Verifica se existe algum per�odo de gozo que intersecta com as datas informadas.
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
   * Retorna Composi��o de gozo cadastrado para o servidor.
   * @access public
   * @return ComposicaoPontoFerias Inst�ncia de ComposicaoPontoFerias
   */
  public function getComposicao (){
  	
  	$oServidor = ServidorRepository::getInstanciaByCodigo( $this->getPeriodoAquisitivo()->getServidor()->getMatricula(), 
  			                                                   $this->getAnoPagamento(),
                                                           $this->getMesPagamento());
  	return new ComposicaoPontoFerias($oServidor);	
  } 
  
  /**
   * Busca as Rubricas dentro do "Periodo Base de C�lculo"�
   * 
   * @return array
   */
  public function calcularMediaRubricas() {

    /**
     * Servidor vinculado ao periodo de Gozo, logo ao periodo aquisitivo.  
     */
    
    $oServidorPeriodo      = $this->getPeriodoAquisitivo()->getServidor();

    /**
     * Cole��o de Rubricas que dever�o ser calculadas
     */
    $aRubricasCalculo      = array();
    
    /**
     * Cole��o de C�lculos das Rubricas
     */ 
    $aCalculoMediaRubricas = array();
    
    /**
     * Datas Base para C�lculo da M�dia das Rubricas
     */
    $oDataInicial          = $this->getPeriodoAquisitivo()->getDataInicial();
    $oDataFinal            = $this->getPeriodoAquisitivo()->getDataFinal();
    
    /**
     * No caso de o periodo especifico de c�lculo seja informado.
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
        //caso n�o exitsta servidor na competencia.
        continue;
      }

      /**
       * Retorna as Rubricas encontradas no C�lculo de Sal�rio
       */
      $oFolhaSalario        = $oServidorCompetencia->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      
      foreach ( $oFolhaSalario->getRubricas() as $oRubrica ) {
        $aRubricasCalculo[$oRubrica->getCodigo()] = $oRubrica;
      }
      
      
      /**
       * Retorna as Rubricas encontradas no C�lculo de Folhas Complementares
       */
      $oFolhaComplementar    = $oServidorCompetencia->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR);
      
      foreach ( $oFolhaComplementar->getRubricas() as $oRubrica ) {
        $aRubricasCalculo[$oRubrica->getCodigo()] = $oRubrica;
      } 
    }
    
    /**
     * Percorre as Rubricas encontradas nos c�lculos de Sal�rio e Complementar
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
       * Caso n�o possa calcular 
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
     * Caso o gozo de f�rias seja dentro de um mes 
     * n�o haver� nenhum dia adiantado
     */
    if ( $this->getPeriodoInicial()->getMes() == $this->getPeriodoFinal()->getMes() ) {
      return 0;
    }
    
    /**
     * Caso o Mes inicial de gozo for dirferente do Mes de Pagamento, tudo ser� adiantado
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