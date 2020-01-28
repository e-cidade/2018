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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno    = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$iInstituicao = db_getsession('DB_instit');

try {
  
  switch ($oParametros->exec) {

    /**
     * Busca historico do credigo, origem/destino
     */
    case 'origem' :

      $oDaoAbatimento = db_utils::getDao('abatimento');

      /**
       * Busca na tabela de transferencias a origem do credito pesquisado
       */
      $oDaoTransferencia = db_utils::getDao('abatimentotransferencia');
      $sSqlTransferencia = $oDaoTransferencia->sql_query_file(null, 'k158_abatimentoorigem', null, "k158_abatimentodestino = {$oParametros->iCodigoCredito}");
      $rsTransferencia   = db_query($sSqlTransferencia);

      /**
       * Erro na query 
       */
      if ( !$rsTransferencia ) {
        throw new Exception('Erro ao buscar origem de crédito.');
      }

      /**
       * Não encontrou origem
       */
      if ( pg_num_rows($rsTransferencia) == 0 ) {
        $iCredito = $oParametros->iCodigoCredito;
      } else {
        
        /**
         * Codigo do credito encontrado
         */
        $iCredito = db_utils::fieldsMemory($rsTransferencia, 0)->k158_abatimentoorigem;
      }

      $sSqlCredito = $oDaoAbatimento->sql_queryDadosCreditos($iCredito);
      $rsCredito = db_query($sSqlCredito);

      /**
       * Erro na query 
       */
      if ( !$rsCredito ) {
        throw new Exception('Erro ao buscar crédito: '.pg_last_error());
      }

      /**
       * Nao encontrou credito 
       */
      if ( pg_num_rows($rsCredito) == 0 ) {
        throw new Exception('Crédito não encontrado: '.$iCredito);
      }

      $oCredito = db_utils::fieldsMemory($rsCredito, 0);

      $oDadosCredido = new StdClass;
      $oDadosCredido->sOrigem     = $oCredito->origem;
      $oDadosCredido->sCgm        = $oCredito->dono_credito;
      $oDadosCredido->iCodigo     = $oCredito->k125_sequencial;
      $oDadosCredido->nValor      = db_formatar($oCredito->k125_valordisponivel, 'f');
      $oDadosCredido->sTipoDebito = $oCredito->k00_descr;
      $oDadosCredido->sReceita    = $oCredito->k02_descr;

      $oRetorno->oCredito = $oDadosCredido; 

    break;

    case 'destino' :

      $oDaoAbatimento   = db_utils::getDao('abatimento');
      $aCreditos        = array();
      $aDestinosCredito = array();

      /**
       * Busca na tabela de transferencias o destino do credito pesquisado
       */
      $oDaoTransferencias = db_utils::getDao('abatimentotransferencia');
      $sSqlTransferencias = $oDaoTransferencias->sql_query_file(null, 'k158_abatimentodestino', null, "k158_abatimentoorigem = {$oParametros->iCodigoCredito}");
      $rsTransferencias   = db_query($sSqlTransferencias);
      $iTransferencias    = pg_num_rows($rsTransferencias);

      /**
       * Erro na query 
       */
      if ( !$rsTransferencias ) {
        throw new Exception('Erro ao buscar destino de crédito.');
      }

      /**
       * Não encontrou destino do credito
       */
      if ( $iTransferencias == 0 ) {
        break;
      }

      /**
       * Array com as transferencias do credito 
       */
      $aTransferencias = db_utils::getCollectionByRecord($rsTransferencias);

      foreach ( $aTransferencias as $oTransferencia ) {
        $aDestinosCredito[] = $oTransferencia->k158_abatimentodestino;
      }

      foreach ( $aDestinosCredito as $iCredito ) {
      
        $sSqlCredito = $oDaoAbatimento->sql_queryDadosCreditos($iCredito);
        $rsCredito = db_query($sSqlCredito);

        /**
         * Erro na query 
         */
        if ( !$rsCredito ) {
          throw new Exception('Erro ao buscar crédito: '.pg_last_error());
        }

        /**
         * Nao encontrou credito 
         */
        if ( pg_num_rows($rsCredito) == 0 ) {
          throw new Exception('Crédito não encontrado: '.$iCredito);
        }

        $oCredito = db_utils::fieldsMemory($rsCredito, 0);

        $oDadosCredido = new StdClass;
        $oDadosCredido->sOrigem     = $oCredito->origem;
        $oDadosCredido->sCgm        = $oCredito->dono_credito;
        $oDadosCredido->iCodigo     = $oCredito->k125_sequencial;
        $oDadosCredido->nValor      = db_formatar($oCredito->k125_valor, 'f');
        $oDadosCredido->sTipoDebito = $oCredito->k00_descr;
        $oDadosCredido->sReceita    = $oCredito->k02_descr;

        $aCreditos[] = $oDadosCredido;
      }

      /**
       * Array com as informacoes do credito 
       */
      $oRetorno->aCreditos = $aCreditos;

    break;

    /**
     * Nenhum case encontrado
     */
    default :
      throw new Exception('Nenhum parametro informado.');
    break;

  }
  
} catch (Exception $eErro) {
  
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);