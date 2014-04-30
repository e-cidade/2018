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
 * Classe para controle de datas
 * @package std
 * @author Andrio Costa
 * @version $Revision: 1.20 $
 * @revision $Author: dbandrio.costa $
 */
class DBDate {

  /**
   * Data no formato timestamp
   * @var integer
   */
  private $iTimeStamp;

  const DATA_PTBR = "d/m/Y";
  const DATA_EN   = "Y-m-d";

  /**
   * Constantes para o dia da semana
   */
  const DOMINGO = 0;
  const SEGUNDA = 1;
  const TERCA   = 2;
  const QUARTA  = 3;
  const QUINTA  = 4;
  const SEXTA   = 5;
  const SABADO  = 6;
  
  const JANEIRO   = 1;
  const FEVEREIRO = 2;
  const MARCO     = 3;
  const ABRIL     = 4;
  const MAIO      = 5;
  const JUNHO     = 6;
  const JULHO     = 7;
  const AGOSTO    = 8;
  const SETEMBRO  = 9;
  const OUTUBRO   = 10;
  const NOVEMBRO  = 11;
  const DEZEMBRO  = 12;

  /**
   * Construtor da Classe
   * @param  $sData - Data no formato Y-m-d ou d/m/Y
   * @throws ParameterException @see DBDate::validaData()
   */
  public function __construct($sData) {

    $this->iTimeStamp = $this->validaData($sData);
  }

  /**
   * Retorna a data no formato Y-m-d
   * @return string
   */
  public function getDate($sMascaraData = DBDate::DATA_EN) {

    return date($sMascaraData, $this->iTimeStamp);
  }

  /**
   * Recebe um formato para conversão de data.
   * Formatos aceitos: Y-m-d
   *                   d/m/Y
   * @param string $sFormat
   * @throws ParameterException @see DBDate::validaData()
   */
  public function convertTo($sFormat) {

    if (($sFormat != DBDate::DATA_EN) && ($sFormat != DBDate::DATA_PTBR)) {

      $sMsgErro  = "Formato de data inválida.\n";
      $sMsgErro .= "Formatos aceitos: \"Y-m-d\" ou \"d/m/Y\"";
      throw new ParameterException($sMsgErro);
    }
    return date($sFormat, $this->iTimeStamp);
  }

  /**
   * Verifica se uma data eh valida
   * @param  string $sData
   * @throws ParameterException - Quando Formato da Data for Inválido ou Inexistente
   */
  protected function validaData($sData) {

    if (strpos($sData, "/")) {
      list($dia, $mes, $ano) = explode("/", $sData);
    } else if (strpos($sData, "-")) {
      list($ano, $mes, $dia) = explode("-", $sData);
    } else {

      $sMsgErro  = "Data com formato inválido. \n";
      $sMsgErro .= "Formatos aceitos: \"Y-m-d\" ou \"d/m/Y\"";
      throw new ParameterException($sMsgErro);
    }

    if (! checkdate($mes, $dia, $ano)) {
    	$sMsgErro = "Data inexistente. Favor verificar";
    	throw new ParameterException($sMsgErro);
    }
    $sDataValidada = "{$ano}-{$mes}-{$dia}";
    return db_strtotime($sDataValidada);
  }

  /**
   * Retorna TimeStamp do Objeto
   * @return integer
   */
  public function getTimeStamp() {

    return $this->iTimeStamp;
  }
  /**
   * Retorna o Dia da Data da Instancia
   * @return integer
   */

  public function getDia() {

    return date("d", $this->iTimeStamp);
  }

  /**
   * Retorna a Mes da data da Instancia
   * @return integer
   */
  public function getMes() {

    return date("m", $this->iTimeStamp);
  }

  /**
   * Retorna o Ano da Data da Instancia
   * @return integer
   */
  public function getAno() {

    return date("Y", $this->iTimeStamp);
  }
  
  /**
   * Retorna o dia da semana no padrão da ISO ISO-8601
   */
  public function getDiaSemana() {
    
    return date("w", $this->iTimeStamp);
  } 
  
  /**
   * Calcula o intervalo entre duas datas, o intervalo é dado
   * no formato específicado em $sIntervalo que pode ser
   * y - ano
   * m - meses
   * d - dias
   * h - horas
   * n - minutos
   * default segundos
   *
   * @param string $data1
   * @param string $data2
   * @param string $intervalo m, d, h, n,y
   * @return int|string intervalo de horas
   */
  public static function calculaIntervaloEntreDatas(DBDate $oDt1, DBDate $oDt2, $sIntervalo) {
    
    $nIntervalo = 1;
    switch ($sIntervalo) {
      case 'y':
        $nIntervalo = 86400*365.25;
        break; //ano
      case 'm':
        $nIntervalo = 2592000;
        break; //mes
      case 'd':
        $nIntervalo = 86400;
        break; //dia
      case 'h':
        $nIntervalo = 3600;
        break; //hora
      case 'n':
        $nIntervalo = 60;
        break; //minuto
      default:
        $nIntervalo = 1;
        break; //segundo
    }
  
    $nValor = (strtotime($oDt1->getDate(DBDate::DATA_EN)) - strtotime($oDt2->getDate(DBDate::DATA_EN))) / $nIntervalo;
    return floor($nValor);
  }
  
  /**
   * Valida se a Data <i>$oData</i> esta presente em um intervalo de datas 
   * 
   * @example  $oData     = '2013-02-14'
   *           $oDtInicio = '2013-02-01'
   *           $oDtFim    = '2013-02-13'
   *           return true pois conflitou com a data incial $oDtInicio 
   * @param DBDate $oData     Data a ser validada
   * @param DBDate $oDtInicio Data inicial 
   * @param DBDate $oDtFim    Data final 
   * @return boolean
   */
  public static function dataEstaNoIntervalo(DBDate $oData, DBDate $oDtInicio, DBDate $oDtFim) {

  	if ($oDtInicio->getTimeStamp() <= $oData->getTimeStamp() && $oDtFim->getTimeStamp() >= $oData->getTimeStamp()) {
  		return true;
  	}
  	return false;
  }
  
  /**
   * Recebe um código do dia da semana no padrão da ISO-8601
   * @static
   * @param integer $iDiaSemana
   * @throws ParameterException
   * @return Ambigous <string>
   */
  static function getLabelDiaSemana($iDiaSemana) {
    
    if ($iDiaSemana < 0 || $iDiaSemana > 6) {
      
      $sMsgErro  = "Dia da semana informado \"{$iDiaSemana}\" não corresponde a nenhum dia da semana previsto no "; 
      $sMsgErro .= "padrão da ISO-8601";
      throw new ParameterException ($sMsgErro);
    }
    
    $aDiasSemana = array();
    $aDiasSemana[0] = "Domingo";
    $aDiasSemana[1] = "Segunda";
    $aDiasSemana[2] = "Terça";
    $aDiasSemana[3] = "Quarta";
    $aDiasSemana[4] = "Quinta";
    $aDiasSemana[5] = "Sexta";
    $aDiasSemana[6] = "Sábado";
    
    return $aDiasSemana[$iDiaSemana];
    
  }
  
  /**
   * Retorna uma coleção de DBDate entre um intervalo de duas Datas (inclusive os extremos)
   * @static
   * @example Quai os dias do mês que tem entre a $oDtInicio e $oDtFim e que sejam terça-feira ou quarta-feira?
   *   $oDtInicio 02/04/2013 (terça-feira)
   *   $oDtFim    23/04/2013 (terça-feira)
   *   $aDiasSemana[0] = 2
   *   $aDiasSemana[1] = 3
   *   
   *   retorno : 2013-04-02
   *             2013-04-03
   *             2013-04-09
   *             2013-04-10
   *             2013-04-16
   *             2013-04-17
   *             2013-04-23
   *          
   * @param DBDate $oDtInicio
   * @param DBDate $oDtFim
   * @param array $aDiasSemana
   * @return array $aDatasNoIntervalo
   */
  static function getDatasNoIntervalo(DBDate $oDtInicio, DBDate $oDtFim, array $aDiasSemana = null) {
    
    if ($oDtInicio->getTimeStamp() > $oDtFim->getTimeStamp()) {
      throw new ParameterException("Data de inicio não pode ser maior que a data final");
    }

    $aDatasNoIntervalo = array();
    $oData             = clone $oDtInicio;
    $lContinue         = true;
    
    do {
      
      if ( count($aDiasSemana) > 0 && in_array($oData->getDiaSemana(), $aDiasSemana) ) {
        $aDatasNoIntervalo[] = clone $oData;
      } else if ( count($aDiasSemana) > 0 && !in_array($oData->getDiaSemana(), $aDiasSemana) ) {
        $lContinue = true;
      } else {
        $aDatasNoIntervalo[] = clone $oData;
      }
      
      $oData->iTimeStamp = mktime(0, 0, 0, $oData->getMes(), $oData->getDia() +1, $oData->getAno());
      
      if ($oData->getTimeStamp() > $oDtFim->getTimeStamp()) {
        $lContinue = false;
      }
      
    } while($lContinue);
  
    return $aDatasNoIntervalo;
  }
  
  /**
   * Retorna a descrição dos meses entre um intervalo de datas indexado por ano e mês
   * @param DBDate $oDtInicio
   * @param DBDate $oDtFim
   * @throws ParameterException
   * @return multitype:array
   */
  static function getMesesNoIntervalo(DBDate $oDtInicio, DBDate $oDtFim) {
    
    if ($oDtInicio->getMes() > $oDtFim->getMes()) {
      throw new ParameterException("Data de inicio não pode ser maior que a data final");
    }
    
    $aMesesRetorno = array();
    
    $oData = clone $oDtInicio;
    
    do {
      
      $aMesesRetorno[$oData->getAno()][(int)$oData->getMes()] = DBDate::getMesExtenso((int)$oData->getMes());
      $oData->iTimeStamp = mktime(0, 0, 0, $oData->getMes() +1, $oData->getDia(), $oData->getAno());
      
      if ($oData->getAno() < $oDtFim->getAno()) {
        $lContinua = true;
      } else if ( ($oData->getAno() == $oDtFim->getAno()) && ($oData->getMes() <= $oDtFim->getMes()) ) {
        $lContinua = true;        
      } else {
        $lContinua = false;
      }
      
    } while ($lContinua);
    
    unset ($oData);
    
    return $aMesesRetorno;
    
  }
  
  /**
   * Transforma uma data através de um intervalo  
   * @param string $sIntervalo
   * 
   * @examples DBDate->modificarIntervalo ('+1 week 2 days 4 hours 2 seconds');
   * @return void
   */
  public function modificarIntervalo ($sIntervalo) {
  	
  	$this->iTimeStamp = strtotime($sIntervalo, $this->iTimeStamp);
  	
  }

  /**
   * Retorna a quantidade de dias do mês
   * @param integer $iMes
   * @param integer $iAno
   * @throws ParameterException
   * @return integer
   */       
  public static function getQuantidadeDiasMes( $iMes, $iAno ) {

  	return cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
  }

  /**
   * Retorna o nome do Mês por extenso
   * @param integer $iMes
   * @throws ParameterException
   * @return string
   */
  public static function getMesExtenso($iMes) {
    
    if ($iMes < DBDate::JANEIRO || $iMes > DBDate::DEZEMBRO) {
    	throw new ParameterException("Mês informado não existe.\nInforme uma valor entre 1 e 12.");
    } 
    
    $aMeses[DBDate::JANEIRO  ]= "Janeiro";
    $aMeses[DBDate::FEVEREIRO]= "Fevereiro";
    $aMeses[DBDate::MARCO    ]= "Março";
    $aMeses[DBDate::ABRIL    ]= "Abril";
    $aMeses[DBDate::MAIO     ]= "Maio";
    $aMeses[DBDate::JUNHO    ]= "Junho";
    $aMeses[DBDate::JULHO    ]= "Julho";
    $aMeses[DBDate::AGOSTO   ]= "Agosto";
    $aMeses[DBDate::SETEMBRO ]= "Setembro";
    $aMeses[DBDate::OUTUBRO  ]= "Outubro";
    $aMeses[DBDate::NOVEMBRO ]= "Novembro";
    $aMeses[DBDate::DEZEMBRO ]= "Dezembro";
    
    return $aMeses[ (int) $iMes];
  }

  
  /**
   * Retorna a data instanciado como uma string
   * @return string
   */
  public function dataPorExtenso() {
  	
    $sData  = "{$this->getDia()} de ";
    $sData .= DBDate::getMesExtenso($this->getMes());
    $sData .= " de {$this->getAno()}";
    
    return $sData; 
  }
}