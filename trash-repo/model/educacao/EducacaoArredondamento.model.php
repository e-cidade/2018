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
 *  Classe base para arredondamento de valores conforme regra da educa��o
 *  @author Iuri <iuri@dbseller.com.br>
 *          F�bio Esteves <fabio.esteves@dbseller.com.br>
 *  @package educacao
 */
require_once("std/DBNumber.php");
class EducacaoArredondamento {

  /**
   * conjunto de Regras para realizar o arredondamento
   * @var array
   */
  protected $aConjuntoRegras = array();

  protected $iCodigoRegra;

  /**
   * Numero de casas decimais da Regra;
   */
  protected $iCasasDecimais = 0;

  const ROUND_LOWER = 1;
  const ROUND_MID   = 2;
  const ROUND_UPPER = 3;

  protected $sMascara;

  protected $lArredondar;

  /**
   * Define as Regras de Arredondamento que sera utilizadas para o arredondamento
   * @param integer $iAno ano da configuracao
   * @param stdClass $oRegras conjunto de regras
   */
  public function adicionarRegras($iAno, stdClass $oRegras) {
    $this->aConjuntoRegras[$iAno] = $oRegras;
  }

  /**
   * Retorna as regras de arredondamento
   * @param integer $iAno - Ano de Configuracao
   * @return array
   */
  public function getRegras($iAno) {
    return $this->aConjuntoRegras[$iAno];
  }

  /**
   * Retorna o numero de casas decimais que a regra de arredondamento utiliza.
   * @param  integer $iAno Ano da configuracao
   * @return integer;
   */
  public function getNumeroCasasDecimais($iAno) {

    $iCasasDecimais = 0;
    if (isset($this->aConjuntoRegras[$iAno])) {
      $iCasasDecimais = $this->aConjuntoRegras[$iAno]->iCasasDecimais;
    }
    return $iCasasDecimais;
  }

  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @return integer;
   */
  public function getMascara($iAno) {

    $sMascara = '';
    if (isset($this->aConjuntoRegras[$iAno])) {
      $sMascara = $this->aConjuntoRegras[$iAno]->sMascara;
    }
    return $sMascara;
  }

  /**
   * Verifica se a configura��o permite o arredondamento do valor no ano
   * @param integer $iAno ano da configuracao
   * @return boolean
   */
  public function arredondaValor($iAno) {

    $lArredondar = false;
    if (isset($this->aConjuntoRegras[$iAno])) {
      $lArredondar = $this->aConjuntoRegras[$iAno]->lArredondar;
    }
    return $lArredondar;
  }

  /**
   * Formata o valor conforme mascara
   * @param float $nValor Valor a ser formatado
   * @param integer $iAno ano para buscar as regras de formatacao
   * @return string retorna a nota formatada
   */
  public function formatar($nValor, $iAno) {

    /**
     * For�amos o tamanho do numero com as casas decimais
     */
    if ($nValor == '' || !is_numeric($nValor)) {
      return $nValor;
    }
    return number_format($nValor, $this->getNumeroCasasDecimais($iAno), ".", "");
  }

  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @param integer $iAno ano da configuracao
   * @return integer;
   */
  public function getFaixasDeArredondamento($iAno) {

    $aRegras = array();
    if (isset($this->aConjuntoRegras[$iAno])) {
      $aRegras = $this->aConjuntoRegras[$iAno]->aRegras;
    }
    return $aRegras;
  }

  /**
   * Realiza o arredondamento do valor passado como parametro, conforme as regras ativas.
   * caso nao exista nenhuma regra ativa ou o valor seja um numero inteiro,
   * apenas retorna ele mesmo;
   * @param float   $nValor
   * @param integer $iAno ano da configuracao
   * @return float
   */
  public function arredondar($nValor, $iAno) {

    /**
     * For�amos o tamanho do numero com as casas decimais
     */
    if ($nValor == '' || !is_numeric($nValor)) {
      return $nValor;
    }

    $iCasasDecimais = $this->getNumeroCasasDecimais($iAno);

    if ( $this->arredondaValor($iAno) && $iCasasDecimais == 0 || 
         ( $this->arredondaValor($iAno) && isset($this->getRegras($iAno)->aRegras) && count($this->getRegras($iAno)->aRegras) == 0 ) ) {
      return round($nValor, $this->getNumeroCasasDecimais($iAno));
    }

    $nValor       = number_format(DBNumber::truncate(trim($nValor), $iCasasDecimais), $iCasasDecimais, ".", "");
    $aPartesValor = explode(".", $nValor);
    /**
     * CAso exista casas decimais configuradas, e o valor for inteiro,
     * for�amos o numero decimal ser 0.
    */
    if ($iCasasDecimais > 0 && count($aPartesValor) == 1) {
      $aPartesValor[1] =  str_repeat("0", $iCasasDecimais);
    }

    if (isset($aPartesValor[1])) {

      if ($this->arredondaValor($iAno)) {

        /**
         * Truncamos o numero para obedecer o tamanho das casas decimais da mascara.
         */
        $aParteDecimal           = substr($aPartesValor[1], 0, $iCasasDecimais);
        $lArredondarParteInteira = $iCasasDecimais == 1;
        $sParteDecimal           = strrev($aParteDecimal);
        $iCasasVerificar         = $iCasasDecimais;
        if ($iCasasDecimais > 1) {
          $iCasasVerificar -= 1;
        }

        $aNumerosAumentar[] = 10;
        if ($iCasasDecimais == 1) {
          $aNumerosAumentar[] = 8;
        }
        /**
         * percorremos as casas decimais e verificamos qual deve ser arredondada;
         */
        for ($iNumero = 0; $iNumero < $iCasasVerificar; $iNumero++) {

          $iNumeroVerificar = substr($sParteDecimal, $iNumero, 1);
          foreach ($this->getFaixasDeArredondamento($iAno) as $oRegra) {

            if ($iNumeroVerificar >= $oRegra->inicio && $iNumeroVerificar <= $oRegra->fim) {

              switch ($oRegra->arrendondar) {

                case self::ROUND_LOWER:

                  $iValorDecimal = '0';
                  $sParteDecimal = substr_replace($sParteDecimal, $iValorDecimal, $iNumero, 1);
                  break;

                case self::ROUND_MID:

                  $iValorDecimal = '5';
                  $sParteDecimal = substr_replace($sParteDecimal, $iValorDecimal, $iNumero, 1);
                  break;

                case self::ROUND_UPPER:

                  $iValorDecimal = '0';
                  if (!$lArredondarParteInteira) {

                    $iProximaCasa  = substr($sParteDecimal, $iNumero+1, 1);
                    $iProximoValor = $iProximaCasa+1;
                    if (in_array($iProximoValor, $aNumerosAumentar)) {

                      /**
                       * caso o valor for 10, diminuimos o valor para 0.
                       */
                      $iProximoValor--;

                      if (($iNumero + 1) == $iCasasVerificar) {

                        /**
                         * caso for a ultima casa, que o valor for 10,
                         * devemos incrementar a part inteira.
                         */
                        $iProximoValor = 0;
                        $aPartesValor[0] += 1;
                      }
                    }
                    $sParteDecimal = substr_replace($sParteDecimal, $iProximoValor, $iNumero+1, 1);
                  } else {
                    $aPartesValor[0] += 1;
                  }
                  $sParteDecimal = substr_replace($sParteDecimal, $iValorDecimal, $iNumero, 1);
                  break;
              }
            }
          }
        }

        $sParteDecimal = substr(strrev($sParteDecimal), 0, $this->getNumeroCasasDecimais($iAno));
        $nValor        = $aPartesValor[0].".".$sParteDecimal;
        $nValor        = number_format($nValor, $this->getNumeroCasasDecimais($iAno), ".", "");
      } else {

        $nValor = "{$aPartesValor[0]}";
        if ($this->getNumeroCasasDecimais($iAno) > 0) {
          $nValor .= ".".substr($aPartesValor[1], 0, $this->getNumeroCasasDecimais($iAno));
        }
      }
    }

    unset($aPartesValor);
    return $nValor;
  }
}