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
 * Classe de manipulação de String
 * @package std
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo <renan@dbseller.com.br>
 */
abstract class DBString {

  /**
   * Verica se o tamanho total do nome é no minimo de 2 caracteres
   */
  const NOME_REGRA_1 = 1;
  /**
   * Verifica se a string de nome é composta por nome e sobrenome e cada um deles tem no minimo 2 caracteres 
   */
  const NOME_REGRA_2 = 2;
  /**
   * Verifica se a string nome é composta por nome e sobrenome
   * @var unknown_type
   */
  const NOME_REGRA_3 = 3;
  
  /**
   * Quebra uma string passando por parâmetro o número máximo de caracteres retornando um array
   *
   * @param string  $sString
   * @param integer $iNroMaxCaract
   * @param string  $sValorCompl
   * @return array
   */
  public static function quebrarLinha( $sString, $iNroMaxCaract, $sValorCompl=' ') {
    
    $aPalavras      = explode(' ',$sString);
    $iTamanhoTotal  = 0;
    $iIndLinha      = 0;
    $aRetornoString = array();
    
    foreach ( $aPalavras as $iInd => $sPalavra ) {
    
      $iTamanhoPalavra = strlen($sPalavra);
    
      if ( isset($aRetornoString[$iIndLinha]) ) {
        $iTamanhoPalavra += 1;
      }
    
      $iTamanhoTotal  += $iTamanhoPalavra;
    
      if ( $iTamanhoTotal <= $iNroMaxCaract  ) {
        if ( isset($aRetornoString[$iIndLinha]) ) {
          $aRetornoString[$iIndLinha].= ' '.$sPalavra;
        } else {
          $aRetornoString[$iIndLinha] = $sPalavra;
        }
      } else {
        $aRetornoString[$iIndLinha]   .= str_repeat($sValorCompl,$iNroMaxCaract-(strlen($aRetornoString[$iIndLinha])));
        $aRetornoString[++$iIndLinha]  = $sPalavra;
        $iTamanhoTotal                 = ($iTamanhoPalavra-1);
      }
    
    }
    
    $aRetornoString[$iIndLinha] .= str_repeat($sValorCompl,$iNroMaxCaract-(strlen($aRetornoString[$iIndLinha])));
    
    return $aRetornoString;
  }
  
  /**
   * Recebe a String e valida seu tamanho com o tamanho informado
   * @param string $sString
   * @param integer $iTamanho
   * @return boolean
   */
  public static function validarTamanho($sString, $iTamanho) {
    return (strlen($sString) == $iTamanho);
  }

  /**
   * Valida se a String passada é válida como CPF
   * @param String $sCpf
   * @return Boolean
   */
  public static function isCPF($sCpf) {

    $aBlackList = array(str_repeat("0",11),
        str_repeat("1",11),
        str_repeat("2",11),
        str_repeat("3",11),
        str_repeat("4",11),
        str_repeat("5",11),
        str_repeat("6",11),
        str_repeat("7",11),
        str_repeat("8",11),
        str_repeat("9",11));
    /**
     * Validamos se a String tem o tamanho correto
     */
    if ( !DBString::validarTamanho( $sCpf, 11 ) ) {
      return false;
    }

    /**
     * validamos se o $sCpf esta na BlackList
     */
    if ( in_array($sCpf, $aBlackList) ) {
      return false;
    }

    $sCPFBase            = substr($sCpf, 0, 9);
    $iPosicaoCPFBase     = 0;
    $nTotalCalculoDigito1= 0;
     
    for ( $iPosicaoCalculada = strlen($sCPFBase)+1; $iPosicaoCalculada > 1; $iPosicaoCalculada-- ) {

      $nTotalCalculoDigito1 += $iPosicaoCalculada * $sCPFBase[$iPosicaoCPFBase];
      $iPosicaoCPFBase++;
    }

    /**
     * A Base para O primeiro digito do CPF é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto1   = $nTotalCalculoDigito1 % 11;
    $iDigito1 = ($iResto1 < 2) ? 0 : (11 - $iResto1);

    /**
     * Concatenamos o digito encontrado ao fim do CPF
     */
    $sCPFBase           .= $iDigito1;
    $iPosicaoCPFBase     = 0;
    $nTotalCalculoDigito2= 0;

    for ( $iPosicaoCalculada = strlen($sCPFBase)+1; $iPosicaoCalculada > 1; $iPosicaoCalculada-- ) {

      $nTotalCalculoDigito2 += $iPosicaoCalculada * $sCPFBase[$iPosicaoCPFBase];
      $iPosicaoCPFBase++;
    }

    /**
     * A base para o segundo digito do cpf é formado pelo resto da divisao da soma efetuada a cima
     * se o resto for maior que2 o digito será 0, caso contrario o digito sera 11 menos o resto
     *
     */
    $iResto2  = $nTotalCalculoDigito2 % 11;
    $iDigito2 = ($iResto2 < 2) ? 0 : (11 - $iResto2);
    $sCPFBase.= $iDigito2;
    
    return ($sCPFBase == $sCpf);
  }

  /**
   * VErifica se a String passada é vallida como CNPJ
   * @param string $sCNPJ
   * @return boolean
   */
  public static function isCNPJ( $sCNPJ ) {
    
    $aBlackList = array(
      str_repeat("0",14),
      str_repeat("1",14),
      str_repeat("2",14),
      str_repeat("3",14),
      str_repeat("4",14),
      str_repeat("5",14),
      str_repeat("6",14),
      str_repeat("7",14),
      str_repeat("8",14),
      str_repeat("9",14)
    );
    /**
     * Validamos se a String tem o tamanho correto
     */
    if ( !DBString::validarTamanho($sCNPJ, 14) ) {
      return false;
    }

    /**
     * validamos se o $sCpf esta na BlackList
     */
    if ( in_array($sCNPJ, $aBlackList) ) {
      return false;
    }

    $sBaseCalculo1 = "543298765432";
    $sBaseCalculo2 = "6543298765432";
    $sBaseCNPJ     = substr($sCNPJ, 0, 12);
    $nTotalBase1   = 0;

    for ( $iPosicaoCNPJ = 0; $iPosicaoCNPJ < strlen($sBaseCNPJ); $iPosicaoCNPJ++ ) {
      $nTotalBase1 +=  $sBaseCalculo1[$iPosicaoCNPJ] * $sBaseCNPJ[$iPosicaoCNPJ];
    }

    /**
     * A Base para O primeiro digito do CNPJ é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto1  = $nTotalBase1 % 11;
    $iDigito1 = ($iResto1 < 2) ? 0 : (11 - $iResto1);

    /**
     * Concatenamos o digito encontrado ao fim do CNPJ
     */
    $sBaseCNPJ  .= $iDigito1;
    $nTotalBase2 = 0;

    for ( $iPosicaoCNPJ = 0; $iPosicaoCNPJ < strlen($sBaseCNPJ); $iPosicaoCNPJ++ ) {
      $nTotalBase2 +=  $sBaseCalculo2[$iPosicaoCNPJ] * $sBaseCNPJ[$iPosicaoCNPJ];
    }

    /**
     * A Base para o segundo digito do CNPJ é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto2  = $nTotalBase2 % 11;
    $iDigito2 = ($iResto2 < 2) ? 0 : (11 - $iResto2);

    /**
     * Concatenamos o digito encontrado ao fim do CNPJ
     */
    $sBaseCNPJ .= $iDigito2;

    return ($sBaseCNPJ == $sCNPJ);
  }

  /**
   * Verifica se a string é válida como email
   * @param boolean $sEmail
   */
  public static function isEmail($sEmail){

    /**
     * verifica se possui cacteres validos para um email
     */
  	$sEmail = strtolower($sEmail);
    $sRegex = '/^([0-9a-z\.\-_])+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    return preg_match($sRegex, $sEmail) ? true : false;
  }

  public static function isNomeValido($sNome, $iRegra){
      $aComposicaoNome = explode(' ', $sNome);
    
    
      switch ( $iRegra ) {
        /**
         * Verifica se o nome tem no minimo 2 caracteres
         */
        case DBString::NOME_REGRA_1:
          return DBString::validarTamanhoMinimo($sNome, 2);
        break;
        
        /**
         *  Verifica se o nome é composto pelo menos por 1 espaco, ou seja, nome e sobrenome
         */
        case DBString::NOME_REGRA_2:

          if ( !DBString::isNomeValido($sNome, DBString::NOME_REGRA_3) ) {
            return false;
          }
          if ( strlen($aComposicaoNome[0]) < 2 || strlen($aComposicaoNome[1]) < 2 ) {
            return false;
          }
          
        break;
        
        /**
         *  Verifica se o nome é composto(nome e sobrenome) e se tem no minimo 2 caracteres
         */
        case DBString::NOME_REGRA_3:
          
          $aComposicaoNome = explode(' ', $sNome);
          
          if ( count($aComposicaoNome) < 2 ) {
            return false;
          }
          
        break;
        
        default:
          throw new Exception("Regra Inválida");  
        break;
      }
      
      return true;
  }
  
  /**
   * Verifica se a String informada possui o tamanho minimo solicitado
   * @param stringnknown_type $sSting
   * @param integer $iTamanhoMinimo
   * @return boolean
   */
  public static function validarTamanhoMinimo($sString, $iTamanhoMinimo) {
      return (strlen($sString) >= $iTamanhoMinimo);
  }
  
  public static function isSomenteLetras($sPalavra) {
    
    $sRegex = '/^[a-zA-Z\s]+$/';
    return preg_match($sRegex, $sPalavra) ? true : false;
  }
  
  /**
   * Valida se a string é somente alfanumerica
   * @param string $sPalavra
   * @param boolean $lAceitaBarra
   * @param boolean $lAceitaHifen
   * @param boolean $lAceitaPonto
   * @return boolean
   */
  public static function isSomenteAlfanumerico($sPalavra, $lAceitaBarra=true, $lAceitaHifen=false, $lAceitaPonto=false) {
                            
   if($lAceitaBarra){       
      $sBarra = '\/';       
   }else{                   
      $sBarra = '';         
   }                        

   if($lAceitaHifen){
   		$sHifen = '\-';
   }else{
   		$sHifen = '';
   }
   
   if($lAceitaPonto){
   		$sPonto = '\.';
   }else{
   		$sPonto = '';
   }
    
   $sRegex = "/^[a-zA-Z0-9$sPonto$sHifen$sBarra\s]+$/";
                            
   return preg_match($sRegex, $sPalavra) ? true : false;
  }                          
     
}