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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

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

      $oDaoAbatimento = new cl_abatimento;

      /**
       * Busca na tabela de transferencias a origem do credito pesquisado
       */
      $oDaoTransferencia = new cl_abatimentotransferencia;
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

      $sCampos = "
        arretipo.k00_descr,
        abatimento.k125_sequencial,
        abatimento.k125_valor,
        abatimentorecibo.k127_numpreoriginal as origem,
        cgm.z01_numcgm as dono_credito,
        recibo.k00_receit as k02_descr
      ";
      $sSqlCredito = $oDaoAbatimento->sql_query_origem($sCampos, "k125_sequencial = {$iCredito}");
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
        throw new Exception('O Crédito não possui origem. Crédito Manual.');
      }

      $oCredito = db_utils::fieldsMemory($rsCredito, 0);

      $oDadosCredido = new StdClass;
      $oDadosCredido->sOrigem     = $oCredito->origem;
      $oDadosCredido->sCgm        = $oCredito->dono_credito;
      $oDadosCredido->iCodigo     = $oCredito->k125_sequencial;
      $oDadosCredido->nValor      = db_formatar($oCredito->k125_valor, 'f');
      $oDadosCredido->sTipoDebito = $oCredito->k00_descr;
      $oDadosCredido->sReceita    = $oCredito->k02_descr;

      $oRetorno->oCredito = $oDadosCredido; 

    break;

    case 'destino' :

      /**
       * Busca na tabela utilizacao o destino do credito pesquisado
       */
      $oDaoUtilizacao = new cl_abatimento();
      $sCampos = "
        k170_numpre,
        k170_numpar,
        k157_valor,
        k157_tipoutilizacao,
        arretipo.k00_descr,
        k157_data,
        recibo.k00_receit,
        case
          when k157_tipoutilizacao = '2' then 'Compensação'
          when k157_tipoutilizacao = '3' then 'Devolução'
        end as tipo_utilizacao
      ";
      $sSqlUtilizacao = $oDaoUtilizacao->sql_query_utilizacao(
        $sCampos, "k157_abatimento= {$oParametros->iCodigoCredito}",
        "k170_numpre, k170_numpar, k157_data asc",
        "k170_numpre, k170_numpar, k157_valor, tipo_utilizacao, k157_tipoutilizacao, k00_descr, k157_data, k00_receit"
      );

      $rsUtilizacao = db_query($sSqlUtilizacao);
      if (!$rsUtilizacao) {
        throw new Exception('Erro ao buscar destino de crédito.');
      }

      /**
       * Array com as transferencias do credito 
       */
      $aUtilizacao = db_utils::getCollectionByRecord($rsUtilizacao);

      $aCreditos = array();
      foreach ($aUtilizacao as $oUtilizacao) {

        $oDadosCredido = new StdClass;
        $oDadosCredido->iNumpre     = (!empty($oUtilizacao->k170_numpre) ? $oUtilizacao->k170_numpre : ' - ');
        $oDadosCredido->iNumpar     = (!empty($oUtilizacao->k170_numpar) ? $oUtilizacao->k170_numpar : ' - ');
        $oDadosCredido->sDestino    = (!empty($oUtilizacao->k00_descr)   ? urlencode($oUtilizacao->k00_descr) : ' - ');
        $oDadosCredido->sTipo       = urlencode($oUtilizacao->tipo_utilizacao);
        $oDadosCredido->sReceita    = $oUtilizacao->k00_receit;
        $oDadosCredido->nValor      = db_formatar($oUtilizacao->k157_valor, 'f');
        $oDadosCredido->sData       = db_formatar($oUtilizacao->k157_data, 'd');

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