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
 * Model utilizado para indentificaзгo de um campo dentro de uma linha
 * @package configuracao
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbrenan.silva $
 * @version $Revision: 1.16 $    
 */
class DBLayoutLinha {
  
  private $sLinha;
  
  private $aPropriedadesCampos;
 	/**
   * Identifica se usa ou nгo o separador para quebrar a linha e determinar o valor do campo solicitado.
   * Se estiver setado para true e o separador for vazio, pega o valor pelas posiзгo de inнcio do campo.
   * @var boolean
   */
  private $lUsaSeparador;
  
  private $lUsaChr;
  
  private $sNomeCampo = ''; 
  /**
   * @param string $sLinha              // String da linha
   * @param array  $aPropriedadesCampos // Array com as propriedades do campo 
   *                                       Ex:  aPropriedadesCampos[nome_campo_layout][posicao_inicial_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][posicao_final_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][separador_campos]
   *                                            aPropriedadesCampos[nome_campo_layout][indice_campo] 
   *                                            (indice_campo somente para quando se utilizar o separador)
   * @param boolean $lUsaSeparador Identifica se usa ou nгo o separador para quebrar a linha e determinar o valor 
   * do campo solicitado. Se estiver setado para true e o separador for vazio, pega o valor pelas posiзгo de 
   * inнcio do campo.
   */
  function __construct($sLinha,$aPropriedadesCampos, $lUsaSeparador = false, $lUsaChr = false) {
    
    $this->sLinha              = $sLinha;
    $this->aPropriedadesCampos = $aPropriedadesCampos;
    $this->lUsaSeparador       = $lUsaSeparador;
    $this->lUsaChr             = $lUsaChr;
    $this->sNomeCampo          =  key($aPropriedadesCampos);
    
  }

  /**
   * Mйtodo mбgico utilizado para retornar o valor do campo dentro da linha
   *
   * @param  string $sName // Nome do Campo
   * @return string        // Conteъdo do campo dentro da linha    
   */
  public function __get($sName){

    /**
    * Se estiver setado para usar o separador e o separador nгo for vazio.
    */  	
    if ($this->lUsaSeparador 
        && isset($this->aPropriedadesCampos[$sName][2]) 
        && !empty($this->aPropriedadesCampos[$sName][2])) {
      
      if ($this->lUsaChr) {
        eval('$s = '.$this->aPropriedadesCampos[$sName][2].';');
      }else{
        $s = $this->aPropriedadesCampos[$sName][2];
      }
      
      $aTmp = explode($s , $this->sLinha);
      $sValorRetorno = '';
      if (isset($aTmp[$this->aPropriedadesCampos[$sName][3]])) {
        $sValorRetorno = $aTmp[$this->aPropriedadesCampos[$sName][3]];
      }
      return $sValorRetorno;
           
    }
    
    $this->sNameTemp = $sName;
    $iPosIni = $this->aPropriedadesCampos[$sName][0];
    $iPosFim = $this->aPropriedadesCampos[$sName][1];
    
    return trim(substr($this->sLinha, ($iPosIni-1), $iPosFim));
    
  }

  /**
   * Mйtodo mбgico utizado para determinar se existe ou nгo um campo do layout
   *
   * @param  string $sName // Nome do Campo
   * @return boolean       // Existe ou nгo o campo no layout
   */
  public function __isset($sName){

    return isset($this->aPropriedadesCampos[$sName]);

  }

  /**
   * Retorna o nome do campo 
   *
   * @return unknown
   */
  function getNomeLinha() {
  	return $this->sNomeCampo;
  }
  
  public function getProperties($propertie = null) {
    return empty($propertie) ? $this->aPropriedadesCampos : $this->aPropriedadesCampos[$propertie];
  }
  
  public function getLinha() {
    return $this->sLinha;
  }

  /**
   * Substitui um valor em um campo da linha
   *
   * @return $this
   */
  public function substituirConteudoCampo($sConteudo, $sCampo) {

    $aAtributosCampo = $this->getProperties($sCampo);

    if(is_array($aAtributosCampo)) {
      $this->sLinha = substr_replace($this->sLinha, $sConteudo, $aAtributosCampo[0]-1, $aAtributosCampo[1]);
    }

    return $this;
  }
}

?>