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
 * Calendario escolar
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.18 $
 */
class Calendario {
  
  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Nome do Calendario
   * @var string
   */
  private $sDescricao;
  
  /**
   * Ano de Execucao
   * @var integer
   */
  private $iAnoExecucao;
  
  /**
   * Periodos do calendario
   * @var array
   */
  private $aPeriodos = array();
  
  /**
   * Feriados do Calendario
   * @var array
   */
  private $aEventos = array();
  
  /**
   * Calendario está como passivio
   */
  private $lPassivo = false;
  
  /**
   * Total de dias letivos do calendario
   * @var integer
   */
  private $iDiasLetivos;
  
  /**
   * Periodicidade do Calendario
   *    Anula ou Semestral
   * FK de duracaocal
   * @var integer
   */
  private $iPeriodicidade;
  
  /**
   *
   * @var integer
   */
  private $iPeriodo;
  
  /**
   * Data de inicio do calendario
   * @var DBDate
   */
  private $oDtInicio;
  
  /**
   * Data final do calendario
   * @var DBDate
   */
  private $oDtFim;
  
  /**
   * Data do resultado final das avaliacoes do calendario
   * @var DBDate
   */
  private $oDtResultado;
  
  /**
   * Se o calendario tem aulas nos sabados
   * @var boolean
   */
  private $lAulaSabado;
  
  /**
   * Numero de semanas letivas
   * @var integer
   */
  private $iSemanasLetivas;
  
  /**
   * Calendario anterior
   * @var Calendario
   */
  private $oCalendarioAnterior = null;
  
  
  /**
   * Escola vinculada ao calendario
   * OBS.: Um Calendario Base nao possuira escola vinculada
   * @var Escola
   */
  private $oEscola = null;
  
  
  /**
   * Tipo de Operacao que está sendo realizada para salvar ou importar um calendario
   * Opções: Salvar, Importar
   * @var string
   */
  private $sTipoOperacao = null;
  
  
  /**
   * @param integer $iCodigoCalendario
   */
  public function __construct($iCodigoCalendario = null) {
    
    if (!empty($iCodigoCalendario)) {
      
      $oDaoCalendario = db_utils::getDao('calendario');
      $sSqlCalendario = $oDaoCalendario->sql_query_file($iCodigoCalendario);
      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);
      
      if ($oDaoCalendario->numrows > 0 ) {
        
        $oCalendario               = db_utils::fieldsMemory($rsCalendario, 0);
        $this->iCodigo             = $oCalendario->ed52_i_codigo;
        $this->sDescricao          = $oCalendario->ed52_c_descr;
        $this->iAnoExecucao        = $oCalendario->ed52_i_ano;
        $this->lPassivo            = $oCalendario->ed52_c_passivo=='S'?true:false;
        $this->iDiasLetivos        = $oCalendario->ed52_i_diasletivos;
        $this->iPeriodicidade      = $oCalendario->ed52_i_duracaocal;
        $this->iPeriodo            = $oCalendario->ed52_i_periodo;
        $this->oDtInicio           = new DBDate($oCalendario->ed52_d_inicio);
        $this->oDtFim              = new DBDate($oCalendario->ed52_d_fim);
        $this->oDtResultado        = new DBDate($oCalendario->ed52_d_resultfinal);
        $this->lAulaSabado         = $oCalendario->ed52_c_aulasabado == "S" ? true : false;
        $this->iSemanasLetivas     = $oCalendario->ed52_i_semletivas;
        
      }
    }
  }
  
  /**
   * Retorna o codigo sequencial do calendario
   * @return integer
   */
  public function getCodigo() {
    
    return $this->iCodigo;
  }
  
  /**
   * atribui uma descricao ao calendario
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a descricao do calendario
   * @return string
   */
  public function getDescricao() {
    
    return $this->sDescricao;
  }
  
  /**
   * atribui o ano de execucao do calendario
   * @param integer $iAno
   */
  public function setAnoExecucao($iAno) {
    
    $this->iAnoExecucao = $iAno;
  }
  
  /**
   * Retorna o ano de execucao do calendario
   * @return integer
   */
  public function getAnoExecucao() {
    
    return $this->iAnoExecucao;
  }
  
  /**
   * Retorna todos os periodos de uma Turma
   * @return PeriodoCalendario[] Periodos do calendário
   */
  public function getPeriodos() {
    
    if (count($this->aPeriodos) == 0 && $this->iCodigo != "") {
      
       $oDaoPeriodoCalendario = db_utils::getDao("periodocalendario");
       $sWhere                = "ed53_i_calendario = {$this->getCodigo()}";
       $sCampos               = " ed53_i_periodoavaliacao, ed53_d_inicio, ed53_d_fim, ed53_i_diasletivos, ";
       $sCampos              .= " ed53_i_semletivas";
       $sSqlPeriodos          = $oDaoPeriodoCalendario->sql_query_file(null,
                                                                       $sCampos,
                                                                       "ed53_i_codigo",
                                                                       $sWhere
                                                                      );
      $rsPeriodos = $oDaoPeriodoCalendario->sql_record($sSqlPeriodos);
      $aPeriodos  = db_utils::getCollectionByRecord($rsPeriodos);
      foreach ($aPeriodos as $oPeriodoBase) {
        
        $oPeriodo = new PeriodoCalendario();
        
        $oPeriodo->setDataInicio(new DBDate($oPeriodoBase->ed53_d_inicio));
        $oPeriodo->setDataTermino(new DBDate($oPeriodoBase->ed53_d_fim));
        $oPeriodo->setPeriodoAvaliacao(new PeriodoAvaliacao($oPeriodoBase->ed53_i_periodoavaliacao));
        $oPeriodo->setDiasLetivos($oPeriodoBase->ed53_i_diasletivos);
        $oPeriodo->setSemanasLetivas($oPeriodoBase->ed53_i_semletivas);
        $this->aPeriodos[] = $oPeriodo;
        unset($oPeriodoBase);
      }
      unset($aPeriodos);
    }
    
    return $this->aPeriodos;
  }
  
  /**
   * Adiciona um Periodo ao Calendario
   * @param PeriodoCalendario $oPeriodo
   */
  public function setPeriodos(PeriodoCalendario $oPeriodo) {
    
    $this->aPeriodos[] = $oPeriodo;
  }
  
  /**
   * Retorna o perido na qual a data faz parte;
   * @param DBDate $dtPeriodo
   * @return array de PeriodoCalendario
   */
  public function getPeriodoPorData(DBDate $dtPeriodo) {
    
    $aPeriodosEncontrados = array();
    foreach ($this->getPeriodos() as $oPeriodo) {

      if ($dtPeriodo->getTimeStamp() >= $oPeriodo->getDataInicio()->getTimeStamp() &&
          $dtPeriodo->getTimeStamp() <= $oPeriodo->getDataTermino()->getTimeStamp()) {
          
        $aPeriodosEncontrados[] = $oPeriodo;
      }
    }
    return $aPeriodosEncontrados;
  }
  
  /**
   * Verifica se o calendario está marcado como passivo
   * @return boolean
   */
  public function isPassivo() {
    
    return $this->lPassivo;
  }
  
  /**
   * Retorna o total de dias letivos
   * @return integer
   */
  public function getDiasLetivos() {
    return $this->iDiasLetivos;
  }
  
  /**
   * Seta um evento ao calendario
   * @param CalendarioEvento $oCalendarioEvento
   */
  public function setEventos(CalendarioEvento $oCalendarioEvento) {
    
    $this->aEventos[] = $oCalendarioEvento;
  }
  
  /**
   * Retorna os feriados programados do Calendario
   * @return CalendarioEvento[]
   */
  public function getEventos() {
    
    if (count($this->aEventos) == 0 && !empty($this->iCodigo)) {
      
      $oDaoFeriado = db_utils::getDao("feriado");
      $sWhere      = "ed54_i_calendario = {$this->iCodigo}";
      $sSqlFeriado = $oDaoFeriado->sql_query_file(null, "*", null, $sWhere);
      $rsFeriado   = $oDaoFeriado->sql_record($sSqlFeriado);
      $iLinhas     = $oDaoFeriado->numrows;
      
      if ($iLinhas > 0) {
        
        for ($i = 0; $i < $iLinhas; $i++) {
          
          $oDadosFeriado        = db_utils::fieldsMemory($rsFeriado, $i);

          $lDiaLetivo           = strtoupper($oDadosFeriado->ed54_c_dialetivo) == "S" ? true : false;
          $oCalendarioEvento    =   new CalendarioEvento();
          
          $oCalendarioEvento->setCodigoEvento($oDadosFeriado->ed54_i_codigo);
          $oCalendarioEvento->setDescricao($oDadosFeriado->ed54_c_descr);
          $oCalendarioEvento->setDiaSemana($oDadosFeriado->ed54_c_diasemana);
          $oCalendarioEvento->setDataEvento(new DBDate($oDadosFeriado->ed54_d_data));
          $oCalendarioEvento->setDiaLetivo($lDiaLetivo);
          $oCalendarioEvento->setTipoEvento($oDadosFeriado->ed54_i_evento);
          
          $this->aEventos[]    = $oCalendarioEvento;
        }
      }
    }
    return $this->aEventos;
  }
  
  /**
   * Retorna o Calendario anterior
   * @return Calendario
   */
  public function getCalendarioAnterior () {
    
    if (is_null($this->oCalendarioAnterior) && !empty($this->iCodigo)) {
      
      $oDaoCalendario = db_utils::getDao('calendario');
      $sSqlCalendario = $oDaoCalendario->sql_query_file($this->iCodigo, "ed52_i_calendant");
      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);
      
      if ($oDaoCalendario->numrows > 0) {
        $this->oCalendarioAnterior = new Calendario(db_utils::fieldsMemory($rsCalendario, 0)->ed52_i_calendant);
      }
    }
    return $this->oCalendarioAnterior;
  }
  
  /**
   * Vincula uma escola ao Calendario
   * @param Escola $oEscola
   */
  public function setEscola(Escola $oEscola) {
    
    $this->oEscola = $oEscola;
  }
  
  /**
   * Retorna a escola do calendario
   * @return Escola
   */
  public function getEscola() {
    
    if (is_null($this->oEscola) && !empty($this->iCodigo)) {
    
      $oDaoCalendario = db_utils::getDao('calendarioescola');
      $sWhere         = " ed38_i_calendario = {$this->iCodigo}";
      $sSqlCalendario = $oDaoCalendario->sql_query_file(null, "ed38_i_escola", null, $sWhere);
      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);
      
      if ($oDaoCalendario->numrows > 0) {
        $this->oEscola = new Escola(db_utils::fieldsMemory($rsCalendario, 0)->ed38_i_escola);
      }
    }
    return $this->oEscola;
  }
  
  /**
   * Salva um calendario
   * E efetua vinculo com a escola
   * @throws BusinessException
   * @throws DBException
   * @return boolean
   */
  public function save() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transaçao ativa com o banco de dados");
    }
    
    $oDaoCalendario = db_utils::getDao('calendario');
    $oDaoCalendario->ed52_c_descr       = $this->sDescricao;
    $oDaoCalendario->ed52_i_ano         = $this->iAnoExecucao;
    $oDaoCalendario->ed52_c_passivo     = $this->lPassivo ? "S" : "N";
    $oDaoCalendario->ed52_i_diasletivos = $this->iDiasLetivos;
    $oDaoCalendario->ed52_i_duracaocal  = $this->iPeriodicidade;
    $oDaoCalendario->ed52_i_periodo     = $this->iPeriodo;
    $oDaoCalendario->ed52_d_inicio      = $this->oDtInicio->getDate(DBDate::DATA_EN);
    $oDaoCalendario->ed52_d_fim         = $this->oDtFim->getDate(DBDate::DATA_EN);
    $oDaoCalendario->ed52_d_resultfinal = $this->oDtResultado->getDate(DBDate::DATA_EN);
    $oDaoCalendario->ed52_c_aulasabado  = $this->lAulaSabado ? "S" : "N";
    $oDaoCalendario->ed52_i_semletivas  = $this->iSemanasLetivas;
    $oDaoCalendario->ed52_i_calendant   = $this->getCalendarioAnterior()->getCodigo();
    
    if (empty($this->iCodigo)) {
      
      $oDaoCalendario->ed52_i_codigo = null;
      $oDaoCalendario->incluir(null);
      $this->iCodigo = $oDaoCalendario->ed52_i_codigo;
    } else {
      
      $oDaoCalendario->ed52_i_codigo = $this->iCodigo;
      $oDaoCalendario->alterar($this->iCodigo);
    }
    
    if ($oDaoCalendario->erro_status == 0) {
    
      $sErroMsg  = "Erro ao incluir/alterar o Calendario {$this->sDescricao}.";
      throw new BusinessException($sErroMsg);
    }
    
    /**
     * Garantimos vinculo do calendario com a escola somente:
     *   Quando nao hover o vinculo
     */
    if (!is_null($this->getEscola())) {
      
      $oDaoCalendario                    = db_utils::getDao('calendarioescola');

      $sWhere  = "     ed38_i_escola = " . $this->oEscola->getCodigo();
      $sWhere .= " and ed38_i_calendario = {$this->iCodigo}";
      
      $sSqlCalendario = $oDaoCalendario->sql_query_file(null, "1", null, $sWhere);
      $rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);
      
      if ($oDaoCalendario->numrows == 0) {
      
        $oDaoCalendario->ed38_i_calendario = $this->iCodigo;
        $oDaoCalendario->ed38_i_escola     = $this->oEscola->getCodigo();
        $oDaoCalendario->ed38_i_codigo     = null;
        
        $oDaoCalendario->incluir(null);
        
        if ($oDaoCalendario->erro_status == 0) {
  
          $sErroMsg = "Erro ao vincular o calendário a Escola.";
          throw new BusinessException($sErroMsg);
        }
      }
    }
    
    if ($this->getTipoOperacao() != "Importar") {
      
      /**
       * Salvamos os periodos do calendario
       */
      foreach ($this->getPeriodos() as $oPeriodo) {
        
        $oPeriodo->salvar($this);
      }
      
      /**
       * Salva os eventos do calendario
       */
      foreach ($this->getEventos() as $oEvento) {
        $oEvento->salvar($this);
      }
    }
    
    return true;
  }
  
  /**
   * Carrega os cados do Calendario e limpa os codigos dos objetos
   */
  public function __clone() {
    
    
    foreach ($this->getPeriodos() as $oPeriodo) {
      $oPeriodo->setCodigoPeriodoCalendario(null);
    }
    
    foreach ($this->getEventos() as $oEvento) {
      $oEvento->setCodigoEvento(null);
    }
    $this->getCalendarioAnterior();
    $this->iCodigo = null;
  }
  
  /**
   * Retorna uma instancia da data de inicio
   * @return DBDate
   */
  public function getDataInicio() {
    return $this->oDtInicio;
  }
  
  /**
   * Retorna uma instancia da data final do calendario
   * @return DBDate
   */
  public function getDataFinal() {
  	return $this->oDtFim;
  }
  
  /**
   * Retorna a quantidade de semanas letivas
   * @return integer
   */
  public function getSemanasLetivas() {
    return $this->iSemanasLetivas;
  }

  /**
   * Retorna a data do resultado final
   * @return DBDate
   */
  public function getDataResultadoFinal() {
    return $this->oDtResultado;
  }
  
  
  /**
   * Retorna a periodicidade do calendario
   * @return Integer
   */
  public function getPeriodicidade() {
    return $this->iPeriodicidade;
  }
  
  /**
   * seta a periodicidade
   * @return Integer
   */
  public function setPeriodicidade($iPeriodicidade) {
    $this->iPeriodicidade = $iPeriodicidade;
  }
  
  /**
   * Data do inicio do calendario
   * @param DBDate $dDataInicio
   */
  public function setDataInicio(DBDate $dDataInicio) {

    $this->oDtInicio = $dDataInicio;
  }
  
  /**
   * seta a Data do fim do calendario
   * @param DBDate $dDataFim
   */
  public function setDataFim(DBDate $dDataFim) {

    $this->oDtFim = $dDataFim;
  }
  
  /**
   * seta Data do resultado final das avaliações
   * @param DBDate $dDataResultadoFinal
   */
  public function setDataResultadoFinal(DBDate $dDataResultadoFinal) {
  
    $this->oDtResultado = $dDataResultadoFinal;
  }

  /**
   * seta a quantidade de dias letivos
   * @param Integer
   */
  public function setDiasLetivos($iDiasLetivos) {
    $this->iDiasLetivos = $iDiasLetivos;
  }
  
  /**
   * seta a quantidade de semanas letivas
   * @param Integer
   */
  public function setSemanasLetivos($iSemanasLetivas) {
    $this->iSemanasLetivas = $iSemanasLetivas;
  }
  
  /**
   * seta o calendario anterior
   * @param Calendario $oCaledarioAnterior
   */
  public function setCalendarioAnterior($oCaledarioAnterior) {
    $this->oCalendarioAnterior = $oCaledarioAnterior;
  }
  
  /**
   * Retorna o numero de periodos
   * @return integer
   */
  public function getPeriodo() {
    return $this->iPeriodo;
  }
  
  /**
   * seta o numero de periodos
   * @param integer $iPeriodo
   */
  public function setPeriodo($iPeriodo) {
    $this->iPeriodo = $iPeriodo;
  }


  /**
   * Retorna o tipo de Operacao
   * @return string
   */
  public function getTipoOperacao() {
    return $this->sTipoOperacao;
  }
  
  /**
   * seta o tipo de operacao para realizar (salvar ou importar calendario)
   * @param string $sTipoOperacao
   */
  public function setTipoOperacao($sTipoOperacao) {
    $this->sTipoOperacao = $sTipoOperacao;
  }

  /**
   * Retorna um período do calendário de acordo o Periodo de avaliação informado
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return PeriodoCalendario|null
   */
  public function getPeriodoCalendarioPorPeriodoAvaliacao( PeriodoAvaliacao $oPeriodoAvaliacao ) {

    foreach ( $this->getPeriodos() as $oPeriodoCalendario ) {

      if ($oPeriodoCalendario->getPeriodoAvaliacao()->getCodigo() == $oPeriodoAvaliacao->getCodigo() ) {
        return $oPeriodoCalendario;
      }
    }
    return null;
  }

  /**
   * Retorna as datas letivas de um período de calendário
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return DBDate[]
   */
  public function getDatasLetivoNoPeriodo ( PeriodoAvaliacao $oPeriodoAvaliacao ) {

    $oPeriodoCalendario     = $this->getPeriodoCalendarioPorPeriodoAvaliacao( $oPeriodoAvaliacao );
    $aDatasPeriodoCalendario = DBDate::getDatasNoIntervalo($oPeriodoCalendario->getDataInicio(),
                                                           $oPeriodoCalendario->getDataTermino()
                                                          );
    $aDiasLetivoPeriodo = array();
    $aEventos           = $this->getEventos();
    $aDiasSemanaLetivo  = $this->getEscola()->getDiasLetivos();
    foreach ( $aDatasPeriodoCalendario as $oDataPeriodo ) {

      $lAdicionaData = true;

      if ( !in_array( $oDataPeriodo->getDiaSemana(), $aDiasSemanaLetivo) ) {
        $lAdicionaData = false;
      }

      // Verificamos os eventos/feriados
      foreach ($aEventos as $oEvento ) {

        // Valida se a $oDataPeriodo é um evento marcado como NÃO LETIVO
        if ( !$oEvento->isDiaLetivo() && $oEvento->getDataEvento()->getTimeStamp() == $oDataPeriodo->getTimeStamp() ) {
          $lAdicionaData = false;
          break;
        }

        // Valida se a $oDataPeriodo é um evento marcado como LETIVO
        if ( $oEvento->isDiaLetivo() && $oEvento->getDataEvento()->getTimeStamp() == $oDataPeriodo->getTimeStamp() ) {
          $lAdicionaData = true;
          break;
        }
      }

      if ( $lAdicionaData ) {
        $aDiasLetivoPeriodo[] = $oDataPeriodo;
      }
    }

    return $aDiasLetivoPeriodo;
  }
}