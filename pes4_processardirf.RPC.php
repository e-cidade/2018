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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_libpessoal.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libpostgres.php");

db_app::import("pessoal.arquivos.dirf.Dirf");
db_app::import("pessoal.arquivos.dirf.Dirf2012");

$oJson    = new services_json();
$oParam   = $oJson->decode((str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status           = 1;
$oRetorno->message          = 1;
$oRetorno->itens            = array();
$oRetorno->aListaMatriculas = array();

switch($oParam->exec) {

  case "processarDirf":

    $subpes = $oParam->iAno.'/'.db_mesfolha();
    $subini = $oParam->iAno."/01";

    try {

      db_inicio_transacao();

      $oDirf = new Dirf2012($oParam->iAno, $oParam->sCnpj);

      $oDirf->setDesdobramentos($oParam->aDesdobramentos);
      $oDirf->processar($oParam->lProcessaEmpenho);
      $oRetorno->aArquivosInconsistentes = array();

      if ($oDirf->hasInconsistencias()) {

        $aArquivosInconsistentes = $oDirf->geraArquivoInconsistencias();
        foreach ($aArquivosInconsistentes as $sArquivoInconsistente) {

          $oRetorno->aArquivosInconsistentes[] = urlencode($sArquivoInconsistente);
        }
      }

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;

  case 'gerarDirf':

    $oParam->sNomeResponsavel = db_stdClass::db_stripTagsJson($oParam->sNomeResponsavel);

    $oDirf = new Dirf2012($oParam->iAno, $oParam->sCnpj);
    $oDirf->setMatriculas($oParam->aMatriculaSelecionadas);
    $oRetorno->arquivo = $oDirf->gerarArquivo($oParam, $oParam->lProcessaEmpenho);

  break;

  case "getUnidadesCnpjInvalido":

    $oRetorno->unidades = Dirf::retornarUnidadesCnpjInvalido();

  break;

  case "getMatriculasDirf":

    $oDirf = new Dirf($oParam->iAno, $oParam->sCnpj);
    $oRetorno->aListaMatriculas = $oDirf->retornaMatriculasDirf($oParam->lProcessaEmpenho);

  break;
}

echo $oJson->encode($oRetorno);