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
 * Classe para controle de datas
 * @package std
 * @author Andrio Costa
 * @version $Revision: 1.47 $
 * @revision $Author: dbigor.cemim $
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
   * @var array Lista de Meses por Extenso
   */
  private static $aMesesExtenso = array(
    self::JANEIRO   => "Janeiro",
    self::FEVEREIRO => "Fevereiro",
    self::MARCO     => "Março",
    self::ABRIL     => "Abril",
    self::MAIO      => "Maio",
    self::JUNHO     => "Junho",
    self::JULHO     => "Julho",
    self::AGOSTO    => "Agosto",
    self::SETEMBRO  => "Setembro",
    self::OUTUBRO   => "Outubro",
    self::NOVEMBRO  => "Novembro",
    self::DEZEMBRO  => "Dezembro"
  );

  /**
   * @var array Lista de Meses Abreviatura
   */
  private static $aMesesAbreviatura = array(
    self::JANEIRO   => "Jan",
    self::FEVEREIRO => "Fev",
    self::MARCO     => "Mar",
    self::ABRIL     => "Abr",
    self::MAIO      => "Mai",
    self::JUNHO     => "Jun",
    self::JULHO     => "Jul",
    self::AGOSTO    => "Ago",
    self::SETEMBRO  => "Set",
    self::OUTUBRO   => "Out",
    self::NOVEMBRO  => "Nov",
    self::DEZEMBRO  => "Dez"
  );

  /**
   * Dias da Semana
   * @type array
   */
  private static $aDiasSemana = array(
    self::DOMINGO => "Domingo",
    self::SEGUNDA => "Segunda",
    self::TERCA   => "Terça",
    self::QUARTA  => "Quarta",
    self::QUINTA  => "Quinta",
    self::SEXTA   => "Sexta",
    self::SABADO  => "Sábado"
  );

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
   * @return string Data no formato brasileiro
   */
  public function __toString() {
    return $this->getDate(DBDate::DATA_PTBR);
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
      $sMsgErro .= "Formatos aceitos: \"Ano-Mês-Dia\" ou \"Dia/Mês/Ano\"";
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
      $sMsgErro .= "Formatos aceitos: \"Ano-Mês-Dia\" ou \"Dia/Mês/Ano\"";
      throw new ParameterException($sMsgErro);
    }

    if (!checkdate($mes, $dia, $ano)) {
      $sMsgErro = "Data {$sData} inexistente. Favor verificar";
      throw new ParameterException($sMsgErro);
    }
    $sDataValidada = "{$ano}-{$mes}-{$dia}";
    return strtotime($sDataValidada);
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
   * @return integer
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
   *
   * @deprecated quando e intervalo de mes, o mesmo encontra-se fixo com 30 dias.
   *
   * @see getIntervaloEntreDatas
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
   * Retorna um VO do tipo DateInterval
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @return DateInterval
   */
  public static function getIntervaloEntreDatas(DBDate $oDataInicial, DBDate $oDataFinal) {

    $oDateTimeInicial = new DateTime($oDataInicial->getDate());
    $oDateTimeFinal   = new DateTime($oDataFinal->getDate());
    $oDateInterval    = $oDateTimeInicial->diff($oDateTimeFinal);
    return $oDateInterval;
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
   * @return string <string>
   */
  static function getLabelDiaSemana($iDiaSemana) {

    if ($iDiaSemana < 0 || $iDiaSemana > 6) {

      $sMsgErro  = "Dia da semana informado \"{$iDiaSemana}\" não corresponde a nenhum dia da semana previsto no ";
      $sMsgErro .= "padrão da ISO-8601";
      throw new ParameterException ($sMsgErro);
    }
    return self::$aDiasSemana[$iDiaSemana];
  }

  /**
   * Retorna uma coleção de DBDate entre um intervalo de duas Datas (inclusive os extremos)
   *
   * @static
   * @example Quai os dias do mês que tem entre a $oDtInicio e $oDtFim e que sejam terça-feira ou quarta-feira?
   *          $oDtInicio 02/04/2013 (terça-feira)
   *          $oDtFim    23/04/2013 (terça-feira)
   *          $aDiasSemana[0] = 2
   *          $aDiasSemana[1] = 3
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
   * @param array  $aDiasSemana
   * @throws ParameterException
   * @return DBDate[]
   */
  static function getDatasNoIntervalo(DBDate $oDtInicio, DBDate $oDtFim, array $aDiasSemana = null, \Closure $fFormatter = null) {

    if (is_null($fFormatter)) {
      $fFormatter = function(DBDate $oData) {
        return $oData;
      };
    }

    if ($oDtInicio->getTimeStamp() > $oDtFim->getTimeStamp()) {
      throw new ParameterException("Data de inicio não pode ser maior que a data final");
    }

    $aDatasNoIntervalo = array();
    $oData             = clone $oDtInicio;
    $lContinue         = true;

    do {

      if ( count($aDiasSemana) > 0 && in_array($oData->getDiaSemana(), $aDiasSemana) ) {
        $aDatasNoIntervalo[] = $fFormatter(clone $oData);
      } else if ( count($aDiasSemana) > 0 && !in_array($oData->getDiaSemana(), $aDiasSemana) ) {
        $lContinue = true;
      } else {
        $aDatasNoIntervalo[] = $fFormatter(clone $oData);
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
  static function getMesesNoIntervalo(DBDate $oDtInicio, DBDate $oDtFim, $lMeExtenso = true) {

    if ($oDtInicio->getTimeStamp() > $oDtFim->getTimeStamp()) {
      throw new ParameterException("Data de inicio não pode ser maior que a data final");
    }

    $aMesesRetorno = array();

    $oData = clone $oDtInicio;

    do {
      $sMes = (int)$oData->getMes();
      if ($lMeExtenso) {
        $sMes = DBDate::getMesExtenso((int)$oData->getMes());
      }
      $aMesesRetorno[$oData->getAno()][(int)$oData->getMes()] = $sMes;
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

    return self::$aMesesExtenso[ (int) $iMes];
  }

  /**
   * Retorna Lista de Meses por extenso
   * @return mixed
   */
  public static function getMesesExtenso() {
    return self::$aMesesExtenso;
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

  /**
   * Verifica sobreposição entre dois periodos
   *
   * @param DBDate $oData1Inicio - data inicial do primeiro periodo
   * @param DBDate $oData1Fim - data final do primeiro periodo
   * @param DBDate $oData2Inicio - data inicial do segundo periodo
   * @param DBDate $oData2Fim - data final do segundo periodo
   * @return boolean
   */
  public static function overlaps(DBDate $oData1Inicio, DBDate $oData1Fim, DBDate $oData2Inicio, DBDate $oData2Fim) {
    return ($oData1Inicio->getTimeStamp() <= $oData2Fim->getTimeStamp()) && ($oData1Fim->getTimeStamp() >= $oData2Inicio->getTimeStamp());
  }

  /**
   * Transforma a quantidade de dias para xx Anos xx Meses xx Dias
   * @param  inte $iQuantidadeDias Quantidade de dias
   * @return String xx Anos, xx Meses e xx Dias
   */
  public static function getIdadeCompleta($iQuantidadeDias){

    $oDataInicial   = new DateTime("now");
    $oDataFinal     = new DateTime("now + $iQuantidadeDias days");
    $oDiferenca     = $oDataFinal->diff($oDataInicial);
    $aDataFormatada = array();

    if ($oDiferenca->y) {

      $sAno = ($oDiferenca->y > 1)? ' Anos' : ' Ano';
      $aDataFormatada[] = $oDiferenca->y . $sAno;
    }

    if ($oDiferenca->m) {

      $sMeses = ($oDiferenca->m > 1)? ' Meses' : ' Mês';
      $aDataFormatada[] = $oDiferenca->m . $sMeses;
    }

    if ($oDiferenca->d) {

      $sDias = ($oDiferenca->d > 1)? ' Dias' : ' Dia';
      $aDataFormatada[] = $oDiferenca->d . $sDias;
    }

    $sRetorno = implode($aDataFormatada, ', ');

    if (strrpos($sRetorno, ',') !== false) {
      $sRetorno = substr_replace($sRetorno, ' e', strrpos($sRetorno, ','), 1);
    }


    return $sRetorno;
  }

  /**
   * Adianta o período em segundos, minutos, horas ou dias
   *
   * @param  Integer $iQuantidade Quantidade a adiantar
   * @param  String  $sTipo       Tipo de adiantamento, em segundos, minutos, horas ou dias
   *
   * @return DBDate
   */
  public function adiantarPeriodo($iQuantidade = 1, $sTipo = 's') {

    switch ($sTipo) {

    case 'm': //Minutos
      $iTimeStampAdiantar = $iQuantidade * 60;
      break;

    case 'h': //Horas
      $iTimeStampAdiantar = $iQuantidade * 3600;
      break;

    case 'd': //Dias
      $iTimeStampAdiantar = $iQuantidade * 24 * 3600;
      break;

    default: //Segundos
      $iTimeStampAdiantar = $iQuantidade;
      break;
    }

    $this->iTimeStamp = $this->getTimeStamp() + $iTimeStampAdiantar;

    return $this;
  }


  /**
   * Recebe uma data em formarto d/m/Y, Y-m-d e retorna o contrario
   * @param  string $sData
   * @return string
   */
  public static function converter($sData) {

    $sFormat = DBDate::DATA_PTBR;
    if (strpos($sData, "/")) {
      $sFormat = DBDate::DATA_EN;
    }
    $oData = new DBDate($sData);
    return $oData->convertTo($sFormat);
  }

  /**
   * Define se o dia é util ou não
   * @todo implementar feriados nacionais
   * @return bool
   */
  public function diaUtil() {

    $aDiaNaoUtil = array(self::SABADO, self::DOMINGO);
    return !in_array($this->getDiaSemana(), $aDiaNaoUtil);
  }

  public static function create($string) {
    return new \DBDate($string);
  }

  /**
   * Cria um DBDate a partir de um Unix timestamp.
   *
   * @param $iTimestamp
   * @return DBDate
   */
  public static function createFromTimestamp($iTimestamp) {
    return new DBDate(date('Y-m-d', $iTimestamp));
  }

  /**
   * REtorna a competencia da data
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return new DBCompetencia($this->getAno(), $this->getMes());
  }

  /**
   * Retorna o nome do Mês por extenso
   * @param integer $iMes
   * @throws ParameterException
   * @return string
   */
  public static function getMesAbreviado($iMes) {

    if ($iMes < DBDate::JANEIRO || $iMes > DBDate::DEZEMBRO) {
      throw new ParameterException("Mês informado não existe.\nInforme uma valor entre 1 e 12.");
    }

    return self::$aMesesAbreviatura[ (int) $iMes];
  }
}
