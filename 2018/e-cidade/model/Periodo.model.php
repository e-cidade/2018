<?php
/**
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
 * Class PeriodoRelatorioContabil
 *
 * Respons�vel por controlar os per�odos dispon�veis no sistema
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.11 $
 */
final class Periodo {

  /**
   * @type integer
   */
  const PRIMEIRO_BIMESTRE = 6;
  /**
   * @type integer
   */
  const PRIMEIRO_SEMESTRE = 12;

  /**
   * @type integer
   */
  const SEGUNDO_SEMESTRE = 13;

  /**
   * @type integer
   */
  const PRIMEIRO_QUADRIMESTRE = 14;

  /**
   * @type integer
   */
  const SEGUNDO_QUADRIMESTRE  = 15;

  /**
   * @type integer
   */
  const TERCEIRO_QUADRIMESTRE = 16;

  /**
   * C�digo
   * @var integer
   */
  private $iCodigo;

  /**
   * Descri��o
   * @var string
   */
  private $sDescricao;

  /**
   * @var integer
   */
  private $iOrdem;

  /**
   * @var integer
   */
  private $iDiaInicial;

  /**
   * @var integer
   */
  private $iMesInicial;

  /**
   * @var integer
   */
  private $iDiaFinal;

  /**
   * @var integer
   */
  private $iMesFinal;

  /**
   * @var string
   */
  private $sSigla;

  /**
   * Carrega as informa��es do objeto
   * @throws BusinessException
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($this->iCodigo)) {
      return;
    }

    $oDaoPeriodo    = new cl_periodo();
    $rsBuscaPeriodo = db_query($oDaoPeriodo->sql_query_file($iCodigo));
    if (!$rsBuscaPeriodo || pg_num_rows($rsBuscaPeriodo) == 0) {
      throw new BusinessException("Per�odo [{$iCodigo}] n�o encontrado.");
    }
    $oStdPeriodo       = db_utils::fieldsMemory($rsBuscaPeriodo, 0);
    $this->sDescricao  = $oStdPeriodo->o114_descricao;
    $this->iOrdem      = $oStdPeriodo->o114_ordem;
    $this->iDiaInicial = $oStdPeriodo->o114_diainicial;
    $this->iMesInicial = $oStdPeriodo->o114_mesinicial;
    $this->iDiaFinal   = $oStdPeriodo->o114_diafinal;
    $this->iMesFinal   = $oStdPeriodo->o114_mesfinal;
    $this->sSigla      = $oStdPeriodo->o114_sigla;
    unset($oStdPeriodo);
  }

  /**
   * @param $iAno
   * @return DBDate
   */
  public function getDataInicial($iAno) {
    return new DBDate("{$this->iDiaInicial}/{$this->iMesInicial}/{$iAno}");
  }

  /**
   * @param $iAno
   * @return DBDate
   */
  public function getDataFinal($iAno) {
    return new DBDate("{$this->iDiaFinal}/{$this->iMesFinal}/{$iAno}");
  }

  /**
   * Retorna o c�digo sequencial
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descri��o completa
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @return int
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * @return int
   */
  public function getDiaInicial() {
    return $this->iDiaInicial;
  }

  /**
   * @return int
   */
  public function getMesInicial() {
    return $this->iMesInicial;
  }

  /**
   * @return int
   */
  public function getDiaFinal() {
    return $this->iDiaFinal;
  }

  /**
   * @return int
   */
  public function getMesFinal() {
    return $this->iMesFinal;
  }

  /**
   * @return string
   */
  public function getSigla() {
    return $this->sSigla;
  }

  /**
   * Retorna a data final do per�odo informado
   * @param  integer $iPeriodo c�digo do per�odo
   * @param  integer $iAno     ano
   * @throws \Exception
   * @return DBDate
   */
  public static function dataFinalPeriodo($iPeriodo, $iAno) {

    switch ($iPeriodo) {
      case 12: // 1� SEMESTRE
      case 22: // JUNHO
        return new \DBDate("{$iAno}-06-30");
        break;
      case 13: // 2� SEMESTRE
      case 16: // 3� QUADRIMESTRE
      case 28: // DEZEMBRO
        return new \DBDate("{$iAno}-12-31");
        break;
      case 14: // 1� QUADRIMESTRE
      case 20: // ABRIL
        return new \DBDate("{$iAno}-04-30");
        break;
      case 15: // 2� QUADRIMESTRE
      case 24: // AGOSTO
        return new \DBDate("{$iAno}-08-31");
        break;
      case 17: // JANEIRO
        return new \DBDate("{$iAno}-01-31");
        break;
      case 18: // FEVEREIRO

        $iDia = cal_days_in_month(CAL_GREGORIAN, '02', $iAno);
        return new \DBDate("{$iAno}-02-{$iDia}");
        break;
      case 19: // MAR�O
        return new \DBDate("{$iAno}-03-31");
        break;
      case 21: // MAIO
        return new \DBDate("{$iAno}-05-31");
        break;
      case 23: // JULHO
        return new \DBDate("{$iAno}-07-31");
        break;
      case 25: // SETEMBRO
        return new \DBDate("{$iAno}-09-30");
        break;
      case 26: // OUTUBRO
        return new \DBDate("{$iAno}-10-31");
        break;
      case 27: // NOVEMBRO
        return new \DBDate("{$iAno}-11-30");
        break;
      default:
        throw new \Exception("Per�odo n�o implementado.");
        break;
    }
  }
}

