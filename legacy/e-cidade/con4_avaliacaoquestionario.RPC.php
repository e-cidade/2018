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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/AvaliacaoQuestionarioInterno.model.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->erro    = false;
$oRetorno->message = '';

switch ($oParam->exec) {

  case "getTreeView":
 
    $sCamposArea        = "Distinct at25_descr as area, at26_codarea as codarea";
    $sCamposAreaModulo  = "descr_modulo as modulo,";
    $sCamposAreaModulo .= "at26_codarea as codarea,";
    $sCamposAreaModulo .= "at26_id_item as codmodulo";
    $sOrdemAreaModulo   = "at25_descr";
    $sWhere             = "";

    $oAreaModulo        = new cl_atendcadareamod();
    $oArea              = new cl_atendcadareamod();

    $sSqlArea           = $oArea->sql_query(null, $sCamposArea, $sOrdemAreaModulo, $sWhere);
    $rsArea             = $oArea->sql_record($sSqlArea);

    if(!$rsArea){

      throw new DBException("Nenhuma informação de area encontrada.");
    }

    $sSqlAreaModulo = $oAreaModulo->sql_query(null, $sCamposAreaModulo, $sOrdemAreaModulo, $sWhere);
    $rsAreaModulo   = $oAreaModulo->sql_record($sSqlAreaModulo);
   
    if(!$rsAreaModulo){

      throw new DBException("Nenhuma informação de módulo encontrada.");
    }

    // Sql para buscar as Areas e Modulos que estao selecionadas
    $sSqlSelecionados = "
      select
        db171_modulo as menu
      from 
        avaliacaoquestionariointernomenu
        inner join avaliacaoquestionariointerno on
          db170_sequencial = db171_questionario
      where
        db170_avaliacao = {$oParam->iAvaliacao}
      group by db171_modulo
    ";

    // Array que vai armazenar os selecionados
    $aSelecionados     = array();
    $rsSelecionados    = db_query($sSqlSelecionados);
    
    if(!$rsSelecionados){

      throw new DBException("Erro ao buscar informações do questionário.");
    }
    $aSelecionadosTemp = db_utils::getCollectionByRecord($rsSelecionados, false, false, true);

    if($aSelecionadosTemp){

      foreach ($aSelecionadosTemp as $oSelecionado) {

        $aSelecionados[$oSelecionado->menu] = $oSelecionado->menu;
      }
    }

    if(!$rsAreaModulo){

      throw new Exception("Nenhuma informação de modulo encontrada");
    }
    $aAreas   = db_utils::getCollectionByRecord($rsArea, false, false, true);
    $aModulos = db_utils::getCollectionByRecord($rsAreaModulo, false, false, true);
    foreach ($aAreas as $area) {

      foreach ($aModulos as $modulo) {

        if($area->codarea == $modulo->codarea){


          if(empty($area->filhos)){

            $area->filhos = array();
          }

          if(!empty($aSelecionados[$modulo->codmodulo])){

            $modulo->selecionado = true;
            $area->selecionado = true;
          } else {
            
            if(!isset($area->selecionado)){
            
              $modulo->selecionado = false;
            }
          }
          $area->filhos[] = $modulo;
        }
      }

      if(!isset($area->selecionado)){
        $area->selecionado = false;
      }
    }
    $oRetorno->aAreas = $aAreas;
  break;
  
  case 'getItensMenuByModulo':

    $sSql = "
      select distinct
        i.id_item as item,
        0 as pai,
        m.modulo,
        i.descricao,
        i.funcao,
        case
          when    
            db171_sequencial::numeric != 0::numeric           
          then 
            1
          else 
            0
        end as selecionado,
        m.menusequencia
      from 
        db_itensmenu i 
        inner join db_menu m on 
          m.id_item_filho = i.id_item
        left join avaliacaoquestionariointernomenu on
          db171_menu = i.id_item and
          db171_modulo = m.modulo 
      where 
        m.modulo = ".$oParam->iModulo."
        and m.id_item = {$oParam->iModulo} 
      group by 
      item,
      pai,
      m.modulo,
      i.descricao,
      i.funcao,
      selecionado,
      m.menusequencia 
      order by m.menusequencia asc
    ";
    $rsResult = db_query($sSql);
    
    if(!$rsResult){

      throw new Exception("Nenhuma informação de menu encontrada");
    }

    $aMenu = db_utils::getCollectionByRecord($rsResult, false, false, true);

    foreach ($aMenu as $oItem) {

      $oItem->filhos = getItens($oItem->item, $oParam->iModulo);
    }
    $oRetorno->aMenu = $aMenu;

  break;

  case 'saveList':
    try {

      db_inicio_transacao(); 
  
      $oQuestionario = new AvaliacaoQuestionarioInterno($oParam->iCodigoQuestionarioInterno);
      $oQuestionario->setAvaliacao((int)$oParam->iAvaliacao);
      $oQuestionario->salvar();

      // Verifica as Areas
      if(!empty($oParam->Modulos)){
        // Loop das Areas
        foreach ($oParam->Modulos as $iModulo) {

          $sSql = "
            select
              id_item_filho as item
            from 
              db_menu 
            where 
              modulo = ". $iModulo ."
            group by id_item_filho
          ";
          $rsResult = db_query($sSql);

          if(!$rsResult){

            throw new Exception("Nenhuma informação do menu encontrada.");
          }       
          // Itens 
          $aItem = db_utils::getCollectionByRecord($rsResult, false, false, true);
          
          foreach ($aItem as $oItem) {

            $oQuestionarioMenu = new AvaliacaoQuestionarioInternoMenu();
            $oQuestionarioMenu->setQuestionario($oQuestionario->getCodigoQuestionarioInterno());
            $oQuestionarioMenu->setMenu($oItem->item);
            $oQuestionarioMenu->setModulo($iModulo);
            $oQuestionarioMenu->salvar();
          }
          // $oQuestionarioMenu = new AvaliacaoQuestionarioInternoMenu();
          // $oQuestionarioMenu->setQuestionario($oQuestionario->getCodigoQuestionarioInterno());
          // $oQuestionarioMenu->setMenu(0);
          // $oQuestionarioMenu->setModulo($iModulo);
          // $oQuestionarioMenu->salvar();
        }
      }

      if(!empty($oParam->ItensMenu)){
        // Loop das Areas
        foreach ($oParam->ItensMenu as $oItem) {
       
          $oQuestionarioMenu = new AvaliacaoQuestionarioInternoMenu();
          $oQuestionarioMenu->setQuestionario($oQuestionario->getCodigoQuestionarioInterno());
          $oQuestionarioMenu->setMenu($oItem->item);
          $oQuestionarioMenu->setModulo($oItem->modulo);
          $oQuestionarioMenu->salvar();
        }
      }

      db_fim_transacao(); 

    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
    }
  break;

  case 'getCodigoQuestionario':
    $SQL = "
      select 
        db170_sequencial as codigo
      from 
        avaliacaoquestionariointerno 
      where 
        db170_avaliacao = ".$oParam->iAvaliacao."
        and db170_ativo = 't' and db170_transmitido = 'f' 
    ";

    $result = db_query($SQL);
    if($result){

      $aQuestionario = db_utils::getCollectionByRecord($result, false, false, true);
      $oRetorno->iQuestionario = $aQuestionario[0]->codigo;
    } else {

      $oRetorno->iQuestionario = false;
    }

  break;
}
echo $oJson->encode($oRetorno);

function getItens($iPai, $iModulo){

  $sSqlItens = "
    select distinct
      i.id_item as item,
      m.id_item as pai,
      m.modulo,
      i.descricao,
      i.funcao,
        case
          when    
            db171_sequencial::numeric != 0::numeric           
          then 
            1 
          else 
            0
        end as selecionado,
        m.menusequencia  
    from 
      db_itensmenu i 
      inner join db_menu m on 
        m.id_item_filho = i.id_item
      left join avaliacaoquestionariointernomenu on
        db171_menu = i.id_item and
        db171_modulo = m.modulo  
    where 
      m.modulo      = {$iModulo}
      and m.id_item = {$iPai} 
      and i.libcliente = 't'
    group by
      item,
      pai,
      m.modulo,
      i.descricao,
      selecionado,  
      m.menusequencia
    order by m.menusequencia asc
  ";
  $rsItens = db_query($sSqlItens);

  if($rsItens){

    $aItens = db_utils::getCollectionByRecord($rsItens, false, false, true);
    
    foreach ($aItens as $aItem) {

      $aItem->filhos = getItens($aItem->item, $iModulo);
    }
    return $aItens;
  } else {

    return false;
  }
}
?>