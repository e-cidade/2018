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
 * Eventos e Feriados presentes no calendario
 * 
 * @package model
 * @subpackage educacao
 * @author andrio.costa <andrio.costa>
 */
class CalendarioEvento {

  /**
   * Codigo do Evento
   * @var integer
   */
  private $iCodigoEvento;
  
  /**
   * Descricao do Evento
   * @var string
   */
  private $sDescricao;

  /**
   * Dia da Semana
   * OBS.: Nome do dia. Ex SEGUNDA
   * @var string
   */
  private $sDiaSemana;

  /**
   * Data do evento
   * @var DBDate
   */
  private $oDtEvento;

  /**
   * Se o feriado e Letivo
   * @var boolean
   */
  private $lDiaLetivo; 
  
  /**
   * Tipo do Evento
   *  1 - FERIADO            
   *  2 - SABADO LETIVO      
   *  3 - RECESSO ESCOLAR    
   * @var integer
   */
  private $iTipoEvento;
  
  public function __construct(){
    
  }
  
  /**
   * Seta o codigo do evento
   * @param integer $iCodigoEvento
   */
  public function setCodigoEvento($iCodigoEvento) {
    
    $this->iCodigoEvento;
  }
  
  /**
   * retorna o codigo do evento
   * @return integer
   */
  public function getCodigoEvento() {
    
    return $this->iCodigoEvento;
  }
  
  /**
   * Seta a descricao do evento
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
  
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a descricao do evento
   * @return string
   */
  public function getDescricao() {
  
    return $this->sDescricao;
  }
  
  /**
   * Seta o dia da semana (nome)
   * @param string $sDiaSemana
   */
  public function setDiaSemana($sDiaSemana) {
  
    $this->sDiaSemana = $sDiaSemana;
  }
  
  /**
   * Retorna o dia da semana (nome)
   * @return string
   */
  public function getDiaSemana() {
  
    return $this->sDiaSemana;
  }
  
  /**
   * Seta uma data para o evento
   * @param DBDate $oDtEvento
   */
  public function setDataEvento(DBDate $oDtEvento) {
  
    $this->oDtEvento = $oDtEvento;
  }
  
  /**
   * Retorna a data do evento
   * @return DBDate
   */
  public function getDataEvento() {
  
    return $this->oDtEvento;
  }
  
  /**
   * Seta o dia o evento eh em dia letivo
   * @param boolean $lDiaLetivo
   */
  public function setDiaLetivo($lDiaLetivo) {
  
    $this->lDiaLetivo = $lDiaLetivo;
  }
  
  /**
   * Retorna se o evento eh em dia letivo
   * @return boolean
   */
  public function isDiaLetivo() {
  
    return $this->lDiaLetivo;
  }
  
  /**
   * Seta o tipo de evento
   * @param integer $TipoEvento
   */
  public function setTipoEvento($iTipoEvento){
  
    $this->iTipoEvento = $iTipoEvento;
  }
  
  /**
   * Retorna o tipo do evento
   * @return integer
   */
  public function getTipoEvento() {
  
    return $this->iTipoEvento;
  }
  
  /**
   * Salva o evento do calendario
   * @throws DBException
   * @throws ParameterException
   * @param  Calendario $oCalendario
   * @return boolean
   */
  public function salvar(Calendario $oCalendario) {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transaçao ativa com o banco de dados");
    }
    
    if (empty($oCalendario)) {
      throw new ParameterException("Não foi definido o calendario.");
    }
    
    $oDaoFeriado                    = db_utils::getDao("feriado");
    $oDaoFeriado->ed54_c_descr      = $this->sDescricao;
    $oDaoFeriado->ed54_c_diasemana  = $this->sDiaSemana;
    $oDaoFeriado->ed54_d_data       = $this->oDtEvento->getDate(DBDate::DATA_EN);
    $oDaoFeriado->ed54_c_dialetivo  = $this->lDiaLetivo ? "S" : "N";
    $oDaoFeriado->ed54_i_evento     = $this->iTipoEvento;
    
    if (empty($this->iCodigoEvento)) {

      $oDaoFeriado->ed54_i_codigo     = null;
      $oDaoFeriado->ed54_i_calendario = $oCalendario->getCodigo();
      $oDaoFeriado->incluir(null);
      $this->iCodigoEvento = $oDaoFeriado->ed54_i_codigo;
      
    } else {
      
      $oDaoFeriado->ed54_i_codigo = $this->iCodigoEvento;
      $oDaoFeriado->alterar($this->iCodigoEvento);
    }
    
    if ($oDaoFeriado->erro_status == 0) {
    
      $sErroMsg  = "Erro ao incluir/alterar o evento ao calendario.";
      throw new BusinessException($sErroMsg);
    }
    
    return true;
  }
}