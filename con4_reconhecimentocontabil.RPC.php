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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");

$oJson        = new services_json();
$oParametros  = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno     = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

/**
 * Caminho das mensagens do programa 
 */
define('MENSAGENS', 'financeiro.contabilidade.con4_reconhecimentocontabil.');

try {
  
  switch ( $oParametros->sExecucao ) {

    case 'buscarDados':

      $iReconhecimentoContabil    = $oParametros->iReconhecimentoContabil;
      $oDaoReconhecimentocontabil = db_utils::getDao('reconhecimentocontabil');

      $sCampos   = 'c112_sequencial  as reconhecimento_contabil,';
      $sCampos  .= 'c111_sequencial  as tipo,';
      $sCampos  .= 'z01_numcgm       as numcgm,';
      $sCampos  .= 'z01_nome         as nome,';
      $sCampos  .= 'c112_processoadm as processo,';
      $sCampos  .= 'c112_valor       as valor';
      $sSqlDados = $oDaoReconhecimentocontabil->sql_query($iReconhecimentoContabil, $sCampos);
      $rsDados   = $oDaoReconhecimentocontabil->sql_record($sSqlDados);

      if ( $oDaoReconhecimentocontabil->erro_status == '0' ) {
        throw new Exception($oDaoReconhecimentocontabil->erro_msg);
      }

      $oRetorno->oDados = db_utils::fieldsMemory($rsDados, 0);

    break;
    
    case 'incluir':

      $sTextoComplementar = db_stdClass::normalizeStringJsonEscapeString($oParametros->sTextoComplementar);
      $sProcesso          = db_stdClass::normalizeStringJsonEscapeString($oParametros->sProcesso);

      db_inicio_transacao();

      $oReconhecimentoContabil = new ReconhecimentoContabil();
      $oReconhecimentoContabil->setCredor(CgmFactory::getInstanceByCgm($oParametros->iNumcgm));
      $oReconhecimentoContabil->setTipoReconhecimentoContabil( TipoReconhecimentoContabil::getInstance( $oParametros->iTipo, 
                                                                                                        new DBDate(date('Y-m-d', db_getsession('DB_datausu'))) 
                                                                                                      )
                                                             );
      $oReconhecimentoContabil->setProcessoAdministrativo($oParametros->sProcesso);
      $oReconhecimentoContabil->setValor($oParametros->nValor);
      $oReconhecimentoContabil->salvar($sTextoComplementar);
      
      db_fim_transacao(false);
      $oRetorno->sMensagem = _M(MENSAGENS . 'inclusao_efetuada');     

    break;

    case 'estornar':

      $sTextoComplementar      = db_stdClass::normalizeStringJsonEscapeString($oParametros->sTextoComplementar);
      $iReconhecimentoContabil = $oParametros->iReconhecimentoContabil;

      db_inicio_transacao();
      try {
        $oReconhecimentoContabil = new ReconhecimentoContabil( $oParametros->iReconhecimentoContabil, 
                                                               new DBDate(date('Y-m-d', db_getsession('DB_datausu')))
                                                             );
        $oReconhecimentoContabil->estornar($sTextoComplementar);
        db_fim_transacao(false);
        $oRetorno->sMensagem = _M(MENSAGENS . 'estorno_efetuado');
      } catch (Exception $oException) {
        db_fim_transacao(true);
        $oRetorno->sMensagem = $oException->getMessage();
      }
      
    break;

    default :
      throw new Exception('Parâmetro inválido');
    break;
  }

} catch ( Exception $eErro ) {
  
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);