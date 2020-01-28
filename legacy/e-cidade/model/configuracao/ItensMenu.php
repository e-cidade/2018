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


require_once('libs/JSON.php');

/**
 * Classe responsavel pelos Itens de Menu
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package Configuração
 */
class ItensMenu {
  
  private $iModulo;

  private $iUsuario;

  private $sNomeArquivo;

  const CAMINHO_ARQUIVO = 'cache/menus/';

  /**
   * Construtor da classe recebe como parametro o Modulo e o Id do usuario.
   * @param integer $iModulo  
   * @param integer $iUsuario 
   */
  function __construct($iModulo, $iUsuario) {

    $this->iModulo      = $iModulo;
    $this->iUsuario     = $iUsuario;
    $this->sNomeArquivo = $this->iModulo. '_'. $this->iUsuario . '.json';
  }

  /**
   * Salva um json com os menus que o usuario tem permissão para acesso.
   * @return [type] [description]
   */
  public function salvarArquivo() {
    
    if($this->vericaArquivo()) {
      return false;
    }

    $oJson  = new services_json();
    $aMenus = $this->montaArvoreMenus();
    $sMenus = $oJson->encode($aMenus);

    if (sizeof($aMenus) == 0){
      return false;
    }

    if(!file_exists(ItensMenu::CAMINHO_ARQUIVO)) {
      mkdir(ItensMenu::CAMINHO_ARQUIVO, 0777, true);
    }

    $oHandle = fopen(ItensMenu::CAMINHO_ARQUIVO . $this->sNomeArquivo, 'w');
    fwrite($oHandle, $sMenus);
    fclose($oHandle);
  }

  /**
   * Busca os itens de menu a partir do conteudo informado.
   * 
   * @param  String $sConteudo Conteudo da busca.
   * @return Array  $aRetorno  Resultado da busca
   */
  public function buscaMenu($sConteudo) {

    $oJson  = new services_json();
    $sMenus = db_getsession("DB_menus");

    
    if (!empty($sMenus)){
      $oMenus   = $oJson->decode(db_getsession("DB_menus"));
    } else {

      $sMenus   = file_get_contents(ItensMenu::CAMINHO_ARQUIVO . $this->sNomeArquivo);
      $oMenus   = $oJson->decode($sMenus); 
      db_putsession("DB_menus", $sMenus);
    }

    $aRetorno = array();

    foreach ($oMenus as $oMenu) {

      if (stripos($oMenu->caminho, $sConteudo) !== false) {

        $oItem = new stdClass();
        $oItem->label = $oMenu->caminho;
        $oItem->cod   = $oMenu->funcao;
        $aRetorno[]   = $oItem;
      }
    }

    return $aRetorno;
  }

  /**
   * Verifica se o arquivo ja existe
   * @return boolean.
   */
  private function vericaArquivo() {

    if(file_exists(ItensMenu::CAMINHO_ARQUIVO . $this->sNomeArquivo)) {
      return true;
    }

    return false;
  }
  
  /**
   * Monta a arvore de menus
   * @return Array.
   */
  private function montaArvoreMenus(){

    $aMenus   = array();

    monta_menu($this->iModulo, $this->iModulo,$this->iModulo, $aMenus, $this->iUsuario);
    
    $aItensMenu = $GLOBALS['matriz_item_seleciona'];
    
    for ($iIndice = 0; $iIndice < sizeof($aItensMenu); $iIndice++) {
      
      $aItens   = explode('-', $aItensMenu[$iIndice]);
      $aCaminho = array();
      for ($iItem = 0; $iItem < sizeof($aItens); $iItem++) {

        if ($aItens[$iItem] == $this->iModulo) {
          continue;
        }
        
        $oItemMenu = $this->getItemMenu($aItens[$iItem]);
        if ($oItemMenu) {

          $aCaminho[] = $oItemMenu->descricao;
          if ($oItemMenu->funcao) {

            $aMenus[$iIndice]['caminho'] = utf8_encode(implode(' > ', $aCaminho));
            $aMenus[$iIndice]['funcao']  = $oItemMenu->funcao;
          }
        }
      }
    }
    
    return $aMenus;
  }

  /**
   * Retorna o nome do item de menu.
   * @param  integer $iItem id do Item.
   * @return string         Nome do menu
   */
  private function getItemMenu($iItem) {

    $sSql  = " select descricao,        ";
    $sSql .= "        funcao            ";
    $sSql .= "   from db_itensmenu      ";
    $sSql .= " WHERE id_item = {$iItem} ";

    $rsItens = db_query($sSql);
    if( pg_num_rows($rsItens) > 0 ){
      return db_utils::fieldsMemory($rsItens, 0);
    }

    return false;
  }

  /**
   * Deleta o aerquivo json.
   * @return boolean
   */
  public function removeCache(){
    
    if(file_exists(ItensMenu::CAMINHO_ARQUIVO . $this->sNomeArquivo)) {

      unlink(ItensMenu::CAMINHO_ARQUIVO . $this->sNomeArquivo);
      return true;
    }
    
    return false;
  }

}




?>