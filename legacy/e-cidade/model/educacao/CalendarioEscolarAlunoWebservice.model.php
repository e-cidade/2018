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


class CalendarioEscolarAlunoWebservice {

  
  protected $oMatricula;
  
  public function __construct($iCodigoMatricula) {
    
    if (!empty($iCodigoMatricula)) {
      $this->oMatricula = MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula);
    }
  }
  
  /**
   * Retorna os dados dos calendario
   */
  public function getDados() {
    
    if ($this->oMatricula->getTurma() == "") {
      throw new Exception('Matricula inexistente.');
    }
    
    $oCalendarioTurma   = $this->oMatricula->getTurma()->getCalendario();
    $iAno               = $oCalendarioTurma->getAnoExecucao();
    
    $oCalendario                   = new stdClass;
    $oCalendario->periodos_letivos = array();
    $oCalendario->ano              = $iAno;
    $oCalendario->data_inicio      = $oCalendarioTurma->getDataInicio()->convertTo(DBDate::DATA_EN);
    $oCalendario->data_fim         = $oCalendarioTurma->getDataFinal()->convertTo(DBDate::DATA_EN);;
    $oCalendario->meses            = array();
    $aPeriodosAula                 = $oCalendarioTurma->getPeriodos();
    foreach ($aPeriodosAula as $oPeriodosLetivos) {
      
      $oPeriodo                        = new stdClass();
      $oPeriodo->nome                  = utf8_encode($oPeriodosLetivos->getPeriodoAvaliacao()->getDescricaoAbreviada());
      $oPeriodo->data_inicio           = $oPeriodosLetivos->getDataInicio()->convertTo(DBDate::DATA_EN);
      $oPeriodo->data_termino          = $oPeriodosLetivos->getDataTermino()->convertTo(DBDate::DATA_EN);
      $oCalendario->periodos_letivos[] = $oPeriodo;
    }
    for ($iMes = 1; $iMes <= 12; $iMes++) {
      
      $oMes                 = new stdClass();
      $sDataFinal           = "{$iAno}-$iMes-".cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
      $oMes->nome           = utf8_encode(ucfirst(db_mes($iMes)));
      $oMes->dias           = array();
      $aDiasNoMes           = DBDate::getDatasNoIntervalo(new DBDate("{$iAno}-$iMes-01"), new DBDate($sDataFinal));
      foreach ($aDiasNoMes as $oDiaNoMes) {
         
        $oDiaLetivo       = $this->getDiaLetivo($oCalendarioTurma, $oDiaNoMes);
        $oDia             = new stdClass();
        $oDia->data       = $oDiaNoMes->convertTo(DBDate::DATA_EN);
        $oDia->eventos    = $this->getEventosDia($oCalendarioTurma, $oDiaNoMes);
        $oDia->dia_letivo = $this->getDiaLetivo($oCalendarioTurma, $oDiaNoMes, $oDia->eventos);
        $oMes->dias[]     = $oDia;
      }
      $oCalendario->meses[] = $oMes;
    }
    return $oCalendario;
  }
  
  protected function getEventosDia(Calendario $oCalendario, DBDate $oDia) {
    
    $aEventos = array();
    foreach ($oCalendario->getEventos() as $oEvento) {
      
      if ($oEvento->getDataEvento() == $oDia) {
        
        $oEventoRetorno         = new stdClass();
        $oEventoRetorno->nome   = utf8_encode($oEvento->getDescricao());
        $oEventoRetorno->letivo = $oEvento->isDiaLetivo();
        $aEventos[]             = $oEventoRetorno;
      }
    }
    return $aEventos;
  }
  
  /**
   * Retorna se a data é letiva
   * @return stdClass
   */
  protected function getDiaLetivo (Calendario $oCalendarioEscolar, DBDate $oData, $aEventos) {
    
    $oDiaLetivo             = new stdClass();
    $oDiaLetivo->dia_letivo = false;
    $oDiaLetivo->periodo    = '';
    $aPeriodosCalendario    = $oCalendarioEscolar->getPeriodos();
    if (in_array($oData->getDiaSemana(), array(0, 6))) {
      
      $lDiaLetivo = false;
      foreach ($aEventos as $oEvento) {
        if ($oEvento->letivo) {
          $lDiaLetivo = true;
        }
      }

      if (!$lDiaLetivo) {
        return $oDiaLetivo;
      }
    }
    
    foreach ($aPeriodosCalendario as $oPeriodoCalendario) {
      
      $oDataInicio = $oPeriodoCalendario->getDataInicio();
      $oDataFinal  = $oPeriodoCalendario->getDataTermino();
      if (DBDate::dataEstaNoIntervalo($oData, $oDataInicio, $oDataFinal)) {
        
        $oDiaLetivo->dia_letivo = true;
        $oDiaLetivo->periodo    = utf8_encode($oPeriodoCalendario->getPeriodoAvaliacao()->getDescricaoAbreviada());
      }
    }
    return $oDiaLetivo;
  }
}