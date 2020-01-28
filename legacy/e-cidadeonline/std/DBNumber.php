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
 * 
 * Esta classe deve conter metodos para tratamento de Números.
 * @name DBNumber
 * @package std
 * @author dbseller
 *
 */
class DBNumber {
  
  /**
   * 
   * Classe responsável por arredondar um número float 
   * @param float $nNumber Numero a ser tratado
   * @param integar $iBase base de arredondamento
   * @return float
   */
  static function round($nNumber=null,$iBase=null){
    
    /**
     * Metodo wrapper para correção de bug no metodo round em versões do php <= 5.2
     */
    if (floatval(phpversion()) <= 5.2) {
      return round(round($nNumber*pow(10, $iBase+1), 0), -1)/pow(10, $iBase+1);
    }           
    return round($nNumber,$iBase);   
  }
  
  /**
   * Retorna apenas a parte inteira do numero
   *
   * @param float $nNumero
   * @return integer parte inteira do numero informado 
   */
  function truncate($nNumero, $iPrecisao = 0) {
    
    $aValuesPart  = explode(".", $nNumero);
    $iIntPart     = $aValuesPart[0]; 
    if (isset($aValuesPart[1]) && $iPrecisao > 0) {
      $iIntPart .= ".".substr($aValuesPart[1], 0, $iPrecisao);
    }
    return $iIntPart;
  }
  
}