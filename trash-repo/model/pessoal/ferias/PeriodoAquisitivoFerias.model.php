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


require_once('std/db_stdClass.php');

/**
 * Classe que manipula os per�odos aquisitivos de f�rias de um servidor 
 *
 * @author Alberto Ferri Neto <alberto@dbseller.com.br>
 * @package pessoal
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.19 $
 */
class PeriodoAquisitivoFerias {
  
  /**
   * caminho do arquivo JSON das mensagens do model 
   */
  const MENSAGENS = 'recursoshumanos.pessoal.PeriodoAquisitivoFerias.';

	/**
	 * C�digo sequencial do per�odo aquisitivo
	 * @var integer
	 */
	private $iCodigo;
	
	/**
	 * Inst�ncia do objeto Servidor
	 * @var Servidor
	 */
	private $oServidor;
	
	/**
	 * Inst�ncia do objeto DBDate com a data inicial do per�odo aquisitivo
	 * @var DBDate
	 */
	private $oDataInicial;
	
	/**
	 * Inst�ncia do objeto DBDate com a data final do per�odo aquisitivo
	 * @var DBDate
	 */
	private $oDataFinal;
	
	/**
	 * Quantidade de dias de direito a f�rias
	 * @var integer
	 */
	private $iDiasDireito = 0;
	
	/**
	 * Quantidade de faltas durante o per�odo aquisitivo
	 * @var integer
	 */
	private $iFaltasPeriodoAquisitivo = 0;

  /**
   * Observi��o do registro de f�rias
   * @var string
   */
  private $sObservacao;
	
  /**
   * Construtor da classe
   *
   * @param integer $iCodigo
   * @access public
   * @return boolean
   */
	public function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return;
    }

    /**
     * Define o c�digo do periodo e valida se � integer 
     */
    $this->setCodigo($iCodigo);

    db_utils::getDao('rhferias', true);

    $oDaoRhFerias = new cl_rhferias();
    $sSqlRhFerias = $oDaoRhFerias->sql_query_file($iCodigo);
    $rsRhFerias   = db_query($sSqlRhFerias);

    /**
     * Erro na query de pesquisa 
     */
    if ( !$rsRhFerias ) {
      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
        (object) array('sErroBanco' => pg_last_error())
      ));
    }

    /**
     * Nenhum registro encontrado pelo condigo 
     */
    if ( pg_num_rows($rsRhFerias) == 0 ) {
      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'busca_periodo_aquisitivo_pelo_codigo',
        (object) array('iCodigo' => $iCodigo)
      ));
    }

    $oDadosPeriodoAquisitivo = db_utils::fieldsMemory($rsRhFerias, 0);

    $this->setServidor(new Servidor($oDadosPeriodoAquisitivo->rh109_regist));
    $this->setDataInicial(new DBDate($oDadosPeriodoAquisitivo->rh109_periodoaquisitivoinicial));
    $this->setDataFinal(new DBDate($oDadosPeriodoAquisitivo->rh109_periodoaquisitivofinal));
    $this->setDiasDireito($oDadosPeriodoAquisitivo->rh109_diasdireito);
    $this->setFaltasPeriodoAquisitivo($oDadosPeriodoAquisitivo->rh109_faltasperiodoaquisitivo);
    $this->setObservacao($oDadosPeriodoAquisitivo->rh109_observacao);

    return true;
	}
	
	/**
	 * Retorna o c�digo sequencial do per�odo aquisitivo
	 * @return integer
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Define o c�digo sequencial do per�odo aquisitivo
	 * @param $iCodigo
	 */
	public function setCodigo($iCodigo) {
		
		if ( !DBNumber::isInteger($iCodigo) ) {
			throw new ParameterException('C�digo sequencial do per�odo aquisitivo inv�lido.');			
		}
		
		$this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna uma inst�ncia do objeto Servidor que pertence o per�odo aquisitivo
	 * @return Servidor
	 */
	public function getServidor() {
    return $this->oServidor;
	}

	/**
	 * Define uma inst�ncia do objeto Servidor que pertence o per�odo aquisitivo
	 * @param $oServidor
	 */
	public function setServidor(Servidor $oServidor) {
		$this->oServidor = $oServidor;
	}

	/**
	 * Retorna uma inst�ncia do objeto DBDate com a data inicial do per�odo aquisitivo
	 * @return DBDate
	 */
	public function getDataInicial() {
    return $this->oDataInicial;
	}

	/**
	 * Define uma inst�ncia do objeto DBDate com a data inicial do per�odo aquisitivo
	 * @param $oDataInicial
	 */
	public function setDataInicial(DBDate $oDataInicial = null) {
		$this->oDataInicial = $oDataInicial;
	}

	/**
	 * Retorna uma inst�ncia do objeto DBDate com a data final do per�odo aquisitivo  
	 * @return DBDate                                                                  
	 */
	public function getDataFinal() {
		return $this->oDataFinal;
	}

	/**
	 * Define uma inst�ncia do objeto DBDate com a data final do per�odo aquisitivo 
	 * @param $oDataFinal
	 */
	public function setDataFinal(DBDate $oDataFinal = null) {
		$this->oDataFinal = $oDataFinal;
	}

	/**
	 * Retorna a quantidade de dias de direito de f�rias de um servidor
	 * @return integer
	 */
	public function getDiasDireito() {
		return $this->iDiasDireito;
	}
	
	/**
	 * Retorna o saldo de dias de direito de um servidor
	 * @return integer
	 */
	public function getSaldoDiasDireito (){
				
		$iSaldoDiasDireito = $this->getDiasDireito() - ( $this->getDiasAbonados() + $this->getDiasGozados() );
		return $iSaldoDiasDireito;
	}
	
	/**
	 * Define a quantidade de dias de direito de f�rias de um servidor/
	 * @param $iDiasDireito integer
	 */
	public function setDiasDireito($iDiasDireito) {
		
    if ( !DBNumber::isInteger($iDiasDireito) ) {
      throw new ParameterException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'dias_direito_ferias_invalido'));
    }

    $this->iDiasDireito = $iDiasDireito;
	}

	/**
	 * Retorna a quantidade de dias que o servidor faltou durante o per�odo aquisitivo
	 * @return integer
	 */
	public function getFaltasPeriodoAquisitivo() {
		return $this->iFaltasPeriodoAquisitivo;
	}

	/**
	 * Define a quantidade de dias que o servidor faltou durante o per�odo aquisitivo
	 * @param $iFaltasPeriodoAquisitivo integer
	 */
	public function setFaltasPeriodoAquisitivo($iFaltasPeriodoAquisitivo) {

    if ( !DBNumber::isInteger($iFaltasPeriodoAquisitivo) ) {
      throw new ParameterException('N�mero de faltas no per�odo aquisitivo inv�lido.');
    }

    $this->iFaltasPeriodoAquisitivo = $iFaltasPeriodoAquisitivo;
	}

  /**
   * Define a observa��o do Periodo Aquisitivo
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna a observa��o do periodo aquisitivo
   * @return string sObservacao
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
	
  /**
   * Salvar periodo aquisitivo
   *
   * @access public
   * @return boolean
   */
  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'nenhuma_transacao_banco'));
    }

    if ( empty($this->oServidor) ) {
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'servidor_nao_informado'));
    } 

    if ( empty($this->oDataInicial) ) {
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'periodo_aquisitivo_inicial_nao_informado'));
    }
    
    if ( empty($this->oDataFinal) ) {
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'periodo_aquisitivo_final_nao_informado'));
    }

    $this->setDiasDireito(PeriodoAquisitivoFerias::calculaDiasDireito( $this->getServidor(), $this->getFaltasPeriodoAquisitivo()));
    
    $oDaoRhFerias = new cl_rhferias();
    $oDaoRhFerias->rh109_regist                   = $this->getServidor()->getMatricula();
    $oDaoRhFerias->rh109_periodoaquisitivoinicial = $this->getDataInicial()->getDate();
    $oDaoRhFerias->rh109_periodoaquisitivofinal   = $this->getDataFinal()->getDate();
    $oDaoRhFerias->rh109_diasdireito              = "{$this->getDiasDireito()}";
    $oDaoRhFerias->rh109_faltasperiodoaquisitivo  = "{$this->getFaltasPeriodoAquisitivo()}";
    $oDaoRhFerias->rh109_observacao               = db_stdClass::normalizeStringJsonEscapeString($this->getObservacao());

    /**
     * Incluir periodo aquisitivo 
     */
    if ( empty($this->iCodigo) ) {

      $oDaoRhFerias->rh109_sequencial = null;
      $oDaoRhFerias->incluir(null);

      /**
       * Erro ao incluir periodo aquisitivo 
       */
      if ( $oDaoRhFerias->erro_status == "0" ) {

        $oMensagemErro = (object) array('sErroBanco' => $oDaoRhFerias->erro_banco);
        throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_incluir_periodo_aquisitivo', $oMensagemErro));
      }

      $this->setCodigo($oDaoRhFerias->rh109_sequencial);
      
      return true;
    } 
    
    /**
     * Alterar periodo aquisitivo 
     */
    $oDaoRhFerias->rh109_sequencial = $this->getCodigo();
    $oDaoRhFerias->alterar($this->getCodigo());

    /**
     * Erro ao alterar periodo aquisitivo 
     */
    if ( $oDaoRhFerias->erro_status == "0" ) {

      $oMensagemErro = (object) array('sErroBanco' => $oDaoRhFerias->erro_banco);
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_alterar_periodo_aquisitivo', $oMensagemErro));
    }

    return true;
  }
  
  /**
   * Retorna o Proximo Periodo Aquisitivo disponivel para a matricula informada
   * @param  integer $iMatricula MAtricula do Servidor
   * @return object  PeriodoAquisitivoFerias
   */
  public static function getDisponivel( Servidor $oServidor ) {

    $oDaoRhFerias = new cl_rhferias();
    $sSqlRhferias = $oDaoRhFerias->sql_query_proximo_periodo_aquisitivo($oServidor->getMatricula(), 'rh109_sequencial');
    $rsRhFerias   = db_query($sSqlRhferias);

    /**
     * Erro na query de pesquisa 
     */
    if ( !$rsRhFerias ) {
      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
        (object) array('sErroBanco' => pg_last_error())
      ));
    }

    /**
     * Nenhum registro encontrado pela matricula informada 
     */
    if ( pg_num_rows($rsRhFerias) == 0 ) {
      throw new BusinessException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'busca_periodo_aquisitivo_pela_matricula',
        (object) array('iCodigo' => $oServidor->getMatricula())
      ));
    }

    $oDadosPeriodoAquisitivo = db_utils::fieldsMemory($rsRhFerias, 0);

    /**
     * Cria uma inst�ncia de PeriodoAquisitivoFerias para o periodo aquisitivo dispon�vel
     */
    $oPeriodoAquisitivo = new PeriodoAquisitivoFerias($oDadosPeriodoAquisitivo->rh109_sequencial);

    return $oPeriodoAquisitivo;
  }

  /**
   * Seta o n�mero de dias de direito de acordo com as faltas
   * C�lculo baseado em dias de gozo de 30 dias
   * 
   * @static
   * @param  Servidor $oServidor
   * @param  Integer  $iQuantidadeFaltas
   * @return number
   */
  public static function calculaDiasDireito(Servidor $oServidor, $iQuantidadeFaltas) {
  	
  	$iDias      = 30;
  	$iDesconto  = 0;
  	$iFaltas    = $iQuantidadeFaltas;
  	$iRegime    = $oServidor->getCodigoRegime();
  	$oDaoFaltas = new cl_rhcadregimefaltasperiodoaquisitivo();
    $sSql       = $oDaoFaltas->sql_query_file(null, 
  			                                      "rh125_diasdesconto", 
  			                                      null,
  			                                      "rh125_rhcadregime = {$iRegime} and {$iFaltas} between rh125_faixainicial and rh125_faixafinal");
  	
  	$rsSql      = db_query($sSql);
  	
    if ( !$rsSql ) {
    	
      throw new DBException(_M(
        PeriodoAquisitivoFerias::MENSAGENS . 'erro_ao_buscar_quatidade_faltas',
        (object) array('sErroBanco' => pg_last_error())
      ));
    }
    
    if ( pg_num_rows($rsSql) == 0 ) {
    	return $iDias;
    }
    
    $iDesconto = db_utils::fieldsMemory($rsSql, 0)->rh125_diasdesconto;
    $iDias    -= $iDesconto;
    return $iDias;
  }

  /**
   * Retorna o n�mero de dias gozados do per�odo aquisitivo
   *
   * @access public
   * @return integer
   */
  public function getDiasGozados() {
     
    $iDiasGozados = 0;
  
    foreach($this->getPeriodosGozo() as $oPeriodoGozo) {
      $iDiasGozados += $oPeriodoGozo->getDiasGozo();
    }
     
    return $iDiasGozados;
  }
  
  /**
   * Retorna o n�mero de dias abonadoss do per�odo aquisitivo
   *
   * @access public
   * @return integer
   */
  public function getDiasAbonados() {
     
    $iDiasAbonados = 0;
  
    foreach($this->getPeriodosGozo() as $oPeriodoGozo) {
      $iDiasAbonados += $oPeriodoGozo->getDiasAbono();
    }
     
    return $iDiasAbonados;
  }
   
  /**
   * Retorna os per�odos de gozo do per�odo aquisitivo
   *
   * @access public
   * @return return aPeriodoGozo[]
   */
  public function getPeriodosGozo() {
  
    $oDaoRhFeriasPeriodo     = new cl_rhferiasperiodo();
    $sSqlPeriodosAquisitivos = $oDaoRhFeriasPeriodo->sql_query_file(null,"rh110_sequencial",null,"rh110_rhferias = $this->iCodigo");
    $rsPeriodos              = db_query($sSqlPeriodosAquisitivos);
  
    if ( !$rsPeriodos ) {

      $oMensagemErro = (object) array('sErroBanco' => pg_last_error());
      throw new DBException(_M(PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_gozo', $oMensagemErro));
    }
  
    $aPeriodoGozo = array();
  
    foreach (db_utils::getCollectionByRecord($rsPeriodos) as $oDadoPeriodoGozo) {
      $aPeriodoGozo[] = new PeriodoGozoFerias($oDadoPeriodoGozo->rh110_sequencial);
    }
  
    return $aPeriodoGozo;
  }
  
  /** 
   * Retorna todas os per�odos aquisitivos do servidor
   * @access public
   * @return return getPeriodosPorServidor[]
   */
  static function getPeriodosPorServidor( Servidor $oServidor ) {
  
  	$oDaoRhFerias = new cl_rhferias();
  	$sSqlRhFerias = $oDaoRhFerias->sql_query_file( null, 'rh109_sequencial', 'rh109_periodoaquisitivoinicial', ' rh109_regist = '.$oServidor->getMatricula() );
  	$rsRhFerias   = db_query($sSqlRhFerias);
  	
  	/**
  	 * Erro na query de pesquisa
  	*/
  	if ( !$rsRhFerias ) {
  		throw new BusinessException(_M(
  		  PeriodoAquisitivoFerias::MENSAGENS . 'erro_buscar_periodo_aquisitivo',
  		  (object)array('sErroBanco' => pg_last_error())
  		));
  	}
  
  	$oDadosPeriodo = db_utils::getCollectionByRecord($rsRhFerias);
  
  	$aPeriodoAquisito = array();
  	
  	foreach ( db_utils::getCollectionByRecord($rsRhFerias) as $oDadoPeriodo ) {
  		$aPeriodoAquisito[] = new PeriodoAquisitivoFerias($oDadoPeriodo->rh109_sequencial);
  	}
  	
  	return $aPeriodoAquisito;
  }
  
  /**
   * Retorna se o servidor tem direito a f�rias para o per�odo aquisitivo informado
   * @access public
   * @return return bool
   */
  public function hasDireitoFerias(){
  	return PeriodoAquisitivoFerias::calculaDiasDireito( $this->getServidor(), $this->getFaltasPeriodoAquisitivo()) == 0 ? false : true;
  }
  
}