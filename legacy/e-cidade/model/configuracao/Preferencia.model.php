<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

abstract class Preferencia {
  
  /**
   * Atributo que receberс os valores: db ou cliente
   * @var string
   */
  private $sTipo       = "db";
  
  /**
   * Skin default
   * @var String
   */
  private $sSkinDefault = "default";
  
  /**
   * Path do arquivo de configuraчуo
   */
  const CONFIGURACAO    = "config/preferencias.json";
    
  /**
   * Objeto que irс receber os valores de configuraчуo por tipo 
   * @var Object
   */
  protected $oPreferencia;

  /**
   * Objeto de configuraчуo para salvar
   * @var Object
   */
  private $oConfiguracao;
  
  /**
   * Contrutor que receberс o tipo da configuraчуo
   * @param String $sTipo
   */
  public function __construct($sTipo = null) {
    
    if($sTipo != null) {

      $this->sTipo = $sTipo;
    }   
    
    $this->oConfiguracao = new stdClass();
    $this->oPreferencia  = new stdClass();    
    if(file_exists(self::CONFIGURACAO)) {
  
      $this->oConfiguracao = json_decode(file_get_contents(self::CONFIGURACAO)); 
      
      if(property_exists($this->oConfiguracao, $sTipo)) {
         
         foreach($this->oConfiguracao->{$sTipo} as $sAtributo => $sValor ) {
             
             if(property_exists($this, $sAtributo)) {
              
              $this->{$sAtributo} = $sValor;
            }
          } 
      }
    } 
  }
  
  /**
   * Salva as preferencias no arquivo de configuraчуo
   * @return void
   */
  public function salvarPreferencias() {
    
    $lAcertaPermissao = false;
    if(!file_exists(self::CONFIGURACAO)) {
      
      $lAcertaPermissao = true;
    }
    $this->oPreferencia->sSkinDefault    = $this->sSkinDefault;
    $this->oConfiguracao->{$this->sTipo} = $this->oPreferencia;  
    $lSalvo = file_put_contents(self::CONFIGURACAO, json_encode($this->oConfiguracao));
    if($lAcertaPermissao) {
      
      chmod(self::CONFIGURACAO, 0775);
    }
    return $lSalvo;
  }
  
  /**
   * Atribui novo valor da skin default
   * @param [ String ] $sSkinDefault
   */
  public function setSkinDefault($sSkinDefault) {
     
    $this->sSkinDefault = $sSkinDefault; 
  }
  
  /**
   * Retorna o nome da skin default 
   * @return String
   */
  public function getSkinDefault() {
  
    return $this->sSkinDefault;
  }

}

?>