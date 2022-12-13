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
 * Representação da competência
 * 
 * @package std
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.12 $
 *
 */
final class DBCompetencia {
	
  /**
   * Ano de competência
   * @var integer
   */
  private $iAno;
  
  /**
   * Mês de competência
   * @var integer
   */
  private $iMes;
  
  const COMPARACAO_IGUAL       = 'COMPARACAO_IGUAL';
  const COMPARACAO_MENOR       = 'COMPARACAO_MENOR';
  const COMPARACAO_MENOR_IGUAL = 'COMPARACAO_MENOR_IGUAL';
  const COMPARACAO_MAIOR       = 'COMPARACAO_MAIOR';
  const COMPARACAO_MAIOR_IGUAL = 'COMPARACAO_MAIOR_IGUAL';
  const COMPARACAO_DIFERENTE   = 'COMPARACAO_DIFERENTE';
  const FORMATO_AAAAMM         = "AAAAMM";
  const FORMATO_MMAAAA         = "MMAAAA";
  
  /**
   * @param integer $iAno
   * @param integer $iMes
   */
  public function __construct($iAno, $iMes) {
  	
    $this->setAno($iAno);
    $this->setMes($iMes);
  }

  /**
   * Seta Ano de competência
   *
   * @param $iAno
   * @throws ParameterException
   */
  protected function setAno ($iAno) {
  	
    if (strlen((int) $iAno) < 4) {
      throw new ParameterException("Ano da competência inválido.");
    }

    $this->iAno = (int) $iAno;
  }
  
  /**
   * Retorna Ano de competência
   * @return integer
   */
  public function getAno () {
    return $this->iAno;
  }

  /**
   * Adiciona Mês de competência
   *
   * @param $iMes
   * @throws ParameterException
   */
  protected function setMes ($iMes) {
     
    $iMes = $iMes + 0;
    if ($iMes < 1 || $iMes > 12) {
      throw new ParameterException("Mês da competência inválido.");
    }
    
    $this->iMes = str_pad($iMes, 2, "0", STR_PAD_LEFT);
  }
  
  /**
   * Retorna Mês de competência
   * @return integer
   */
  public function getMes () {
    return $this->iMes;
  }
  
  /**
   * Retorna a competência
   * @param  string  $sFormato      = tipo de formatação AAAAMM ou MMAAAA 
   * @param  boolean $lUsaSeparador = true  retorna a competencia formatada com uma '/' 
   *                                  false retorna a competencia como string plana
   * @return string
   */
  public function getCompetencia($sFormato = self::FORMATO_AAAAMM, $lUsaSeparador = true) {
    
    $sSeparador = "";
    
    if ($lUsaSeparador) {
      $sSeparador = "/";
    }
  	
    if ($sFormato == self::FORMATO_AAAAMM) {
      return "{$this->iAno}{$sSeparador}{$this->iMes}";
    } else {
      return "{$this->iMes}{$sSeparador}{$this->iAno}";
    }
  }

  /**
   * Compara a competencia atual com outra
   *
   * @param DBCompetencia $oCompetenciaComparar
   * @param string        $sTipoComparacao
   * @return bool
   * @throws ParameterException
   */
  public function comparar( DBCompetencia $oCompetenciaComparar, $sTipoComparacao = DBCompetencia::COMPARACAO_IGUAL ) {

    $oCompetenciaAtual    = $this;
    $sCompetenciaAtual    = $oCompetenciaAtual->getAno() . $oCompetenciaAtual->getMes(); 
    $sCompetenciaComparar = $oCompetenciaComparar->getAno(). $oCompetenciaComparar->getMes();  

    switch ($sTipoComparacao)  {

      case DBCompetencia::COMPARACAO_DIFERENTE:
        $lComparacao = ( $oCompetenciaAtual->getMes() <> $oCompetenciaComparar->getMes()) || 
                       ( $oCompetenciaAtual->getAno() <> $oCompetenciaComparar->getAno());
        break; 
      case DBCompetencia::COMPARACAO_IGUAL:     
        $lComparacao = ( $oCompetenciaAtual->getMes() == $oCompetenciaComparar->getMes()) &&  
                       ( $oCompetenciaAtual->getAno() == $oCompetenciaComparar->getAno());
        break; 
      case DBCompetencia::COMPARACAO_MAIOR: 
        $lComparacao = $sCompetenciaAtual   > $sCompetenciaComparar;
        break; 
      case DBCompetencia::COMPARACAO_MENOR:    
        $lComparacao = $sCompetenciaAtual   < $sCompetenciaComparar;
        break;
      case DBCompetencia::COMPARACAO_MAIOR_IGUAL:
        $lComparacao = $sCompetenciaAtual  >= $sCompetenciaComparar;
        break;
      case DBCompetencia::COMPARACAO_MENOR_IGUAL:
        $lComparacao = $sCompetenciaAtual  <= $sCompetenciaComparar;
        break;
    default:
        throw new ParameterException("Tipo de Comparação Inválida.");
        break; 
    }

    return $lComparacao;
  }

  /**
   * Retorna a competência do mês anterior
   *
   * @return DBCompetencia
   */
  public function getCompetenciaAnterior() {

    $iAnoCompetencia = $this->getAno();
    $iMesCompetencia = $this->getMes();

    if ($iMesCompetencia <= 12 || $iMesCompetencia >= 1) {

      $iAnoAnterior = $iAnoCompetencia;
      $iMesAnterior = $iMesCompetencia - 1;

      if ($iMesAnterior == 0) {

        $iAnoAnterior = $iAnoCompetencia - 1;
        $iMesAnterior = 01;

        if ($iAnoAnterior < $iAnoCompetencia) {
          $iMesAnterior = 12;
        }
      }

      $iAnoCompetencia = $iAnoAnterior;
      $iMesCompetencia = $iMesAnterior;
    }

    $this->setAno($iAnoCompetencia);
    $this->setMes($iMesCompetencia);

    $oCompetenciaAnterior = new DBCompetencia($this->getAno(), $this->getMes());

    return $oCompetenciaAnterior;
  }

  /**
   * Retorna a competência do próximo mês
   *
   * @return DBCompetencia
   */
  public function getProximaCompetencia() {

    $iAnoCompetencia = $this->getAno();
    $iMesCompetencia = $this->getMes();

    if ($iMesCompetencia <= 12 || $iMesCompetencia >= 1) {

      $iProximoAno = $iAnoCompetencia;
      $iProximoMes = $iMesCompetencia + 1;

      if ($iProximoMes == 13) {

        $iProximoAno = $iAnoCompetencia + 1;
        $iProximoMes = 12;

        if ($iProximoAno > $iAnoCompetencia) {
          $iProximoMes = 01;
        }
      }

      $iAnoCompetencia = $iProximoAno;
      $iMesCompetencia = $iProximoMes;
    }

    $oCompetenciaAnterior = new DBCompetencia($iAnoCompetencia, $iMesCompetencia);

    return $oCompetenciaAnterior;
  }

  /**
   * Retorna a data de inicio da competência
   * @return \DBDate
   */
  public function getDataDeInicio() {

    return new DBDate("{$this->getAno()}-{$this->getMes()}-01");
  }
  /**
   * Retorna a data de termino da competência
   * @return \DBDate
   */
  public function getDataDeTermino() {

    $iUltimoDiaCompetencia = $this->getUltimoDia();
    return new DBDate("{$this->getAno()}-{$this->getMes()}-{$iUltimoDiaCompetencia}");
  }

  /**
   * Retorna o Ultimo dia da competência
   * @return int
   */
  public function getUltimoDia() {
    return  cal_days_in_month(CAL_GREGORIAN, $this->getMes(), $this->getAno());
  }

  /**
   * Instancia uma competencia de uma string
   * @param $competencia
   * @return \DBCompetencia
   * @throws \BusinessException
   */
  public static function createFromString($competencia) {
    
    if (strlen($competencia) != 7) {
      throw new BusinessException("Competência {$competencia} com formato inválido.");
    }
    $aPartes = explode("/", $competencia);    
    if (strlen($aPartes[0]) == 2) {
      return new DBCompetencia($aPartes[1], $aPartes[0]);
    }
    if (strlen($aPartes[0]) == 4) {
      return new DBCompetencia($aPartes[0], $aPartes[1]);
    }
  }
}
