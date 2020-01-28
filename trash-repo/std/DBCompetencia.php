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
 * Representa��o da compet�ncia
 * 
 * @package std
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.1 $
 *
 */
final class DBCompetencia {
	
  /**
   * Ano de compet�ncia
   * @var integer
   */
  private $iAno;
  
  /**
   * M�s de compet�ncia
   * @var integer
   */
  private $iMes;
  

  const FORMATO_AAAAMM = "AAAAMM";
  const FORMATO_MMAAAA = "MMAAAA";
  
  
  /**
   * 
   * @param integer $iAno
   * @param integer $iMes
   */
  public function __construct($iAno, $iMes) {
  	
    $this->setAno($iAno);
    $this->setMes($iMes);
    
  }
  
  
  /**
   * Seta Ano de compet�ncia
   * @param integer $iAno
   */
  protected function setAno ($iAno) {
  	
    if (strlen((int) $iAno) < 4) {
      throw new ParameterException("Ano inv�lido. O Ano deve conter ao menos 4 digitos.");
    }
    $this->iAno = (int) $iAno;
  }
  
  /**
   * Retorna Ano de compet�ncia
   * @return integer
   */
  public function getAno () {

    return $this->iAno;
  }
  
  /**
   * Seta M�s de compet�ncia
   * @param integer $iMes
   */
  protected function setMes ($iMes) {
     
    $iMes = (int) $iMes;
    if ($iMes < 1 || $iMes > 12) {
      
      $sMsgErro = "M�s informado � inv�lido. O m�s da compet�ncia deve ser um valor entre 01 e 12.";
      throw new ParameterException($sMsgErro);
    }
    
    $this->iMes = str_pad($iMes, 2, "0", STR_PAD_LEFT);
    
  }
  
  /**
   * Retorna M�s de compet�ncia
   * @return integer
   */
  public function getMes () {
    return $this->iMes;
  }
  
  /**
   * Retorna a compet�ncia
   * @param  string  $sFormato      = tipo de formata��o AAAAMM ou MMAAAA 
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
}