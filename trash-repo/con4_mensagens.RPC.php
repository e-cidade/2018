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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->mensagem = "";

$oJson  = new services_json();
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));

define('MENSAGENS', 'configuracao.configuracao.con4_mensagens.');

try { 

  switch ($oParametros->exec) {
    
    case 'associacoes':

      $sMensagem    = file_get_contents('mensagens/associacoes/associacoes.json');
      $aAssociacoes = $oJson->decode($sMensagem)->associacoes;

      $aMenus     = array();
      $aItensMenu = array();
      $aArquivos  = array();

      foreach( $aAssociacoes as $oItem ) {

        if ( !isset($oItem->menus) || !is_array($oItem->menus) ) {
          continue;
        }                    

        foreach ( $oItem->menus as $iMenu ) {

          $aItensMenu[]        = $iMenu;
          $aArquivos[$iMenu][] = $oItem->fonte;
        }
      }

      foreach( $aItensMenu as $iItemMenu ) {

        /**
         * @todo - verificar foreach de cima
         */
        if ( empty($iItemMenu) ) {
          continue;
        }

        /**
         * @todo
         * inner join com item menu para ter certeza que item existe 
         */
        $sSqlMenu  = "select * from fc_buscamenus('$iItemMenu', 1) where rsmenu is not null order by 1 desc";
        $rsItemMenu = db_query($sSqlMenu);

        if ( !$rsItemMenu ) {
          continue;
        }
        
        /**
         * @todo guardar menus invalidos
         */
        if ( pg_num_rows($rsItemMenu) == 0 ) {
          continue;
        }

        $aArvoreMenu = db_utils::getCollectionByRecord($rsItemMenu);

        $aArvore = array();

        foreach ( $aArvoreMenu as $oArvoreMenu ) {
          $aArvore[] = $oArvoreMenu->riitem . '#' . $oArvoreMenu->rsmenu;
        }

        $aArvore = montarArvoreMenusMensagens($aArvore, $aArquivos[$iItemMenu]);
        $aMenus = array_merge_recursive($aMenus, $aArvore);
      }
      
      $oRetorno->aMenus = $aMenus;

    break;

    case 'getMensagensArquivo' : 

      $sArquivoJson = 'mensagens/' . $oParametros->sArquivo;
      $oDadosErro = new stdClass();
      $oDadosErro->sArquivo = $sArquivoJson;

      if ( !file_exists($sArquivoJson) ) {
        throw new Exception(_M(MENSAGENS . 'erro_arquivo_json_nao_encontradao', $oDadosErro));
      }

      $sConteudoArquivo = file_get_contents($sArquivoJson); 
      $oMensagens = $oJson->decode($sConteudoArquivo);

      if ( empty($oMensagens) ) {
        throw new Exception(_M(MENSAGENS . 'erro_arquivo_json_invalido', $oDadosErro));
      }

      $oDadosMensagen = new stdClass();

      foreach ($oMensagens as $sMetodo => $sMensagem) {
        $oDadosMensagen->$sMetodo = urlencode($sMensagem);
      }

      $oRetorno->oMensagens = $oDadosMensagen;

    break;

    case 'editarMensagensArquivo' :

      $sConteudoArquivo = "{\n";
      $sCaminhoArquivo = 'mensagens/' . $oParametros->sArquivo;
      $iTotalMensagens = count(get_object_vars($oParametros->oMensagem));
      $iMensagemAtual = 1;
      $sERValidarCampo = "/[^A-Za-z0-9\_]+/";
      $iTamanhoIdentacao = 0;

      /**
       * Busca o maior metodo, usado para identar arquivo 
       */
      foreach ( $oParametros->oMensagem as $sMetodo => $sMensagem ) {
        
        if ( strlen($sMetodo) > $iTamanhoIdentacao ) {
          $iTamanhoIdentacao = strlen($sMetodo) + 1;
        }
      }

      /**
       * Percorre o objeto com as mensagem e monta string para gravar no arquivo 
       */
      foreach ( $oParametros->oMensagem as $sMetodo => $sMensagem ) {

        $lMetodoInvalido = preg_match($sERValidarCampo, $sMetodo);

        /**
         * Metodo possui caracteres invalidos 
         */
        if ( $lMetodoInvalido ) {

          $oDadosErro = new stdClass();
          $oDadosErro->sIdMensagem = $sMetodo;
          throw new Exception(_M(MENSAGENS . 'erro_id_mensagem_invalido', $oDadosErro));
        }

        $sMensagem = db_stdClass::normalizeStringJsonEscapeString($sMensagem);

        /**
         * Escapa as quebra de linha 
         */
        $sMensagem = str_replace("\n", '\n', $sMensagem);

        /**
         * Remove quebras de linhas no inicio e no final da mensagem 
         */
        $sMensagem = trim($sMensagem, '\n');

        /**
         * Mensagem formatada 
         */
        $sConteudoArquivo .= '  "' . str_pad($sMetodo . '"', $iTamanhoIdentacao, ' ') . ' : "' . $sMensagem . '"'; 

        /**
         * Ultima mensagem 
         */
        if ( $iMensagemAtual == $iTotalMensagens) {
          continue;
        }

        $sConteudoArquivo .= ",\n";
        $iMensagemAtual++;
      }
      
      $sConteudoArquivo .= "\n}";
      $lArquivoAlterado = file_put_contents($sCaminhoArquivo, $sConteudoArquivo);

      /**
       * Erro ao salvar arquivo no diretorio "mensagens"
       */
      if ( !$lArquivoAlterado ) {

        $oDadosErro = new stdClass();
        $oDadosErro->sCaminhoArquivo = $sCaminhoArquivo;
        throw new Exception(_M(MENSAGENS . 'erro_salvar_arquivo', $oDadosErro));
      }

      $oDadosMensagem = new stdClass();
      $oDadosMensagem->sCaminhoArquivo = $sCaminhoArquivo;
      $oRetorno->mensagem = _M(MENSAGENS . 'mensagem_arquivo_editado', $oDadosMensagem);

    break;

    default :
      throw new Exception('Parametro inválido.');
    break;
  }

} catch(Exception $oErro) {

  $oRetorno->status = 2;
  $oRetorno->mensagem = $oErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);

echo $oJson->encode($oRetorno);

/**
 * Monta array da arvore dos menus 
 * - montando id com sequencial dos itens de menu, para agrupar sem repetir usando DBTreeView
 *
 * exemplo:
 *    montarArvoreMenusMensagens(
 *      array(
 *        '28#Processo de Compras',
 *        '32#Procedimentos',
 *        '3485#Cadastro de Solicitações',
 *        '3487#Alteração'
 *      ), 
 *      array('patrimonial/compras/com1_solicita005.json')
 *    );
 *
 * retorna: 
 *    $aArrayRetorno['28#Processo de Compras']['2832#Procedimentos']['28323485#Cadastro de Solicitações']['283234853487#Alteração'] = array('patrimonial/compras/com1_solicita005.json');
 *
 * @param array $aArray - itens de menu, com sequencial do menu e descricao, separados por '#'
 * @param array $aArquivos - caminho dos arquivos json
 * @return array
 */
function montarArvoreMenusMensagens(Array $aArray, Array $aArquivos) {

  $aArrayRetorno = array();
  $sArray        = '$aArrayRetorno';
  $sAgrupador    = '';
  
  foreach ( $aArray as $sValor ) {

    $aDadosMenu = explode('#', $sValor);
    $sAgrupador .= $aDadosMenu[0];
    $sItemMenu  = $sAgrupador . '#' . $aDadosMenu[1]; 
    $sArray .= "['" . urlencode($sItemMenu) . "']";
  } 

  $sArray .= ' = $aArquivos;';
  eval($sArray);
  return $aArrayRetorno;
}